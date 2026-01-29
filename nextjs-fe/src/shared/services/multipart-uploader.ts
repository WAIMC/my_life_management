import { mediaFileService } from './modules/media-file.service';
import { MultipartPart, Part, UploadProgress, HeavyUploadResult } from '../types/media-file.types';
import { MULTIPART_UPLOAD_CONFIG } from '../config/constant';
import en from '../../../messages/en.json';

export class MultipartUploader {
    private file: File;
    private uploadId: string = '';
    private key: string = '';
    private partSize: number = 0;
    private partsCount: number = 0;
    private parts: Part[] = [];
    private completedParts: MultipartPart[] = [];
    private onProgress: (progress: UploadProgress) => void;
    
    private pendingQueue: Part[] = [];
    private activeUploads: number = 0;
    private failed: boolean = false;
    private loadedBytesByPart: Record<number, number> = {};
    
    private workspaceId?: number;
    private parentPath?: string;

    constructor(
        file: File, 
        onProgress: (progress: UploadProgress) => void,
        workspaceId?: number,
        parentPath?: string
    ) {
        this.file = file;
        this.onProgress = onProgress;
        this.workspaceId = workspaceId;
        this.parentPath = parentPath;
    }

    async start(): Promise<HeavyUploadResult> {
        // 1. Init Multipart Upload
        const initData = await mediaFileService.initMultipartUpload({
            extension: this.file.name.split('.').pop() || '',
            size: this.file.size,
            mime_type: this.file.type,
            original_name: this.file.name
        });

        this.uploadId = initData.upload_id;
        this.key = initData.key;
        this.partSize = initData.part_size;
        this.partsCount = initData.parts_count;

        // 2. Prepare Parts
        for (let i = 0; i < this.partsCount; i++) {
            const start = i * this.partSize;
            const end = Math.min(start + this.partSize, this.file.size);
            this.parts.push({
                partNumber: i + 1,
                start,
                end,
                blob: this.file.slice(start, end),
                attempts: 0
            });
        }
        
        this.pendingQueue = [...this.parts];

        // 3. Start Upload Loop
        return new Promise<HeavyUploadResult>((resolve, reject) => {
            this.processQueue(resolve, reject);
        });
    }

    private processQueue(resolve: (value: HeavyUploadResult) => void, reject: (reason?: Error) => void) {
        if (this.failed) return; // Stop if already failed

        // Check if done
        if (this.pendingQueue.length === 0 && this.activeUploads === 0) {
            // All done, complete upload
            this.finalizeUpload().then(resolve).catch(reject);
            return;
        }

        // Fill slots
        while (this.activeUploads < MULTIPART_UPLOAD_CONFIG.CONCURRENT_UPLOADS && this.pendingQueue.length > 0) {
            const part = this.pendingQueue.shift();
            if (part) {
                this.uploadPart(part, resolve, reject);
            }
        }
    }

    private async uploadPart(part: Part, resolve: (value: HeavyUploadResult) => void, reject: (reason?: Error) => void) {
        this.activeUploads++;
        
        try {
            // Get URL if missing
            if (!part.url) {
                const urlData = await mediaFileService.getMultipartPresignedUrl({
                    key: this.key,
                    upload_id: this.uploadId,
                    part_number: part.partNumber,
                    size: this.file.size
                });
                part.url = urlData.url;
            }

            // Upload
            const xhr = new XMLHttpRequest();
            await new Promise((res, rej) => {
                xhr.open('PUT', part.url!, true);
                
                // Track progress
                xhr.upload.onprogress = (e) => {
                   if (e.lengthComputable) {
                       this.loadedBytesByPart[part.partNumber] = e.loaded;
                       this.updateProgress();
                   }
                };

                xhr.onload = () => {
                    if (xhr.status >= MULTIPART_UPLOAD_CONFIG.HTTP_STATUS_OK_MIN && xhr.status < MULTIPART_UPLOAD_CONFIG.HTTP_STATUS_OK_MAX) {
                        const etag = xhr.getResponseHeader('ETag');
                        if (etag) {
                            part.etag = etag.replace(/"/g, ''); // Remove quotes
                            res(null);
                        } else {
                            rej(new Error(en.media.multipart.noEtag));
                        }
                    } else {
                        const message = en.media.multipart.uploadFailedStatus
                            .replace('{status}', xhr.status.toString());
                        rej(new Error(message));
                    }
                };

                xhr.onerror = () => rej(new Error(en.media.multipart.networkError));
                xhr.ontimeout = () => rej(new Error(en.media.multipart.timeout));
                
                xhr.send(part.blob);
            });

            // Success
            this.completedParts.push({
                part_number: part.partNumber,
                etag: part.etag!
            });

            this.activeUploads--;
            // loadedBytesByPart is fully updated for this part
            this.processQueue(resolve, reject);

        } catch (error) {
            this.activeUploads--;
            part.attempts++;
            
            if (part.attempts >= MULTIPART_UPLOAD_CONFIG.MAX_RETRIES) {
                this.failed = true;
                const message = en.media.multipart.partFailedMaxRetries
                    .replace('{partNumber}', part.partNumber.toString())
                    .replace('{maxRetries}', MULTIPART_UPLOAD_CONFIG.MAX_RETRIES.toString());
                reject(new Error(message));
                return;
            }

            // Retry logic with backoff
            const delay = MULTIPART_UPLOAD_CONFIG.RETRY_DELAY_BASE * Math.pow(2, part.attempts) + Math.random() * MULTIPART_UPLOAD_CONFIG.RETRY_JITTER;
            const warnMessage = en.media.multipart.partFailedRetry
                .replace('{partNumber}', part.partNumber.toString())
                .replace('{delay}', delay.toFixed(0));
            console.warn(`[MultipartUploader] ${warnMessage}`, error);
            
            setTimeout(() => {
                if (this.failed) return;
                this.pendingQueue.unshift(part); // Put back to retry
                this.processQueue(resolve, reject);
            }, delay);
        }
    }

    private updateProgress() {
        const loaded = Object.values(this.loadedBytesByPart).reduce((a, b) => a + b, 0);
        const total = this.file.size;
        this.onProgress({
            loaded,
            total,
            percentage: Math.round((loaded / total) * 100)
        });
    }

    private async finalizeUpload() {
        this.completedParts.sort((a, b) => a.part_number - b.part_number);
         
         const requestPayload = {
            key: this.key,
            upload_id: this.uploadId,
            parts: this.completedParts,
            original_name: this.file.name,
            extension: this.file.name.split('.').pop() || '',
            size: this.file.size,
            mime_type: this.file.type,
            workspace_id: this.workspaceId,
            parent_path: this.parentPath
        };

        // Result is now metadata object including key
        const result = await mediaFileService.completeMultipartUpload(requestPayload);
        
        return {
            original_name: this.file.name,
            extension: this.file.name.split('.').pop() || '',
            size: this.file.size,
            mime_type: this.file.type,
            key: result.key, // Use key from backend response
        }; 
    }
}
