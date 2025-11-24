'use client';

import React, { useState, useCallback } from 'react';
import { mediaFileService } from '@/services/media-file.service';
import type { UploadFileResponse } from '@/types/media-file.types';

interface FileUploadProps {
  onUploadSuccess?: (file: UploadFileResponse) => void;
  onUploadError?: (error: Error) => void;
  accept?: string;
  maxSize?: number; // in bytes
  isPublic?: boolean;
}

export function FileUpload({
  onUploadSuccess,
  onUploadError,
  accept = 'image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx',
  maxSize = 50 * 1024 * 1024, // 50MB default
  isPublic = false,
}: FileUploadProps) {
  const [uploading, setUploading] = useState(false);
  const [progress, setProgress] = useState(0);
  const [dragActive, setDragActive] = useState(false);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [preview, setPreview] = useState<string | null>(null);

  const handleDrag = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === 'dragenter' || e.type === 'dragover') {
      setDragActive(true);
    } else if (e.type === 'dragleave') {
      setDragActive(false);
    }
  }, []);

  const validateFile = (file: File): string | null => {
    if (file.size > maxSize) {
      return `File size exceeds ${mediaFileService.formatFileSize(maxSize)}`;
    }
    return null;
  };

  const handleFile = useCallback((file: File) => {
    const error = validateFile(file);
    if (error) {
      alert(error);
      return;
    }

    setSelectedFile(file);

    // Generate preview for images
    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreview(reader.result as string);
      };
      reader.readAsDataURL(file);
    } else {
      setPreview(null);
    }
  }, [maxSize]);

  const handleDrop = useCallback((e: React.DragEvent) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);

    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleFile(e.dataTransfer.files[0]);
    }
  }, [handleFile]);

  const handleChange = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    e.preventDefault();
    if (e.target.files && e.target.files[0]) {
      handleFile(e.target.files[0]);
    }
  }, [handleFile]);

  const handleUpload = async () => {
    if (!selectedFile) return;

    setUploading(true);
    setProgress(0);

    try {
      // Simulate progress (in real app, use XMLHttpRequest for actual progress)
      const progressInterval = setInterval(() => {
        setProgress(prev => Math.min(prev + 10, 90));
      }, 200);

      const result = await mediaFileService.upload({
        file: selectedFile,
        is_public: isPublic,
      });

      clearInterval(progressInterval);
      setProgress(100);

      setTimeout(() => {
        setSelectedFile(null);
        setPreview(null);
        setProgress(0);
        setUploading(false);
        onUploadSuccess?.(result);
      }, 500);
    } catch (error) {
      setUploading(false);
      setProgress(0);
      onUploadError?.(error as Error);
      alert('Upload failed: ' + (error as Error).message);
    }
  };

  const handleCancel = () => {
    setSelectedFile(null);
    setPreview(null);
    setProgress(0);
  };

  return (
    <div className="file-upload-container">
      {!selectedFile ? (
        <div
          className={`upload-dropzone ${dragActive ? 'active' : ''}`}
          onDragEnter={handleDrag}
          onDragLeave={handleDrag}
          onDragOver={handleDrag}
          onDrop={handleDrop}
        >
          <input
            type="file"
            id="file-upload-input"
            className="file-input"
            accept={accept}
            onChange={handleChange}
            disabled={uploading}
          />
          <label htmlFor="file-upload-input" className="upload-label">
            <div className="upload-icon">📁</div>
            <p className="upload-text">
              Drag and drop your file here, or click to browse
            </p>
            <p className="upload-hint">
              Max size: {mediaFileService.formatFileSize(maxSize)}
            </p>
          </label>
        </div>
      ) : (
        <div className="file-preview">
          {preview && (
            <div className="preview-image">
              <img src={preview} alt="Preview" />
            </div>
          )}
          <div className="file-info">
            <p className="file-name">{selectedFile.name}</p>
            <p className="file-size">
              {mediaFileService.formatFileSize(selectedFile.size)}
            </p>
          </div>

          {uploading && (
            <div className="upload-progress">
              <div className="progress-bar">
                <div
                  className="progress-fill"
                  style={{ width: `${progress}%` }}
                />
              </div>
              <p className="progress-text">{progress}%</p>
            </div>
          )}

          <div className="file-actions">
            <button
              onClick={handleUpload}
              disabled={uploading}
              className="btn btn-primary"
            >
              {uploading ? 'Uploading...' : 'Upload'}
            </button>
            <button
              onClick={handleCancel}
              disabled={uploading}
              className="btn btn-secondary"
            >
              Cancel
            </button>
          </div>
        </div>
      )}

      <style jsx>{`
        .file-upload-container {
          width: 100%;
          max-width: 600px;
          margin: 0 auto;
        }

        .upload-dropzone {
          border: 2px dashed #cbd5e0;
          border-radius: 8px;
          padding: 40px 20px;
          text-align: center;
          background: #f7fafc;
          transition: all 0.3s ease;
          cursor: pointer;
        }

        .upload-dropzone.active {
          border-color: #4299e1;
          background: #ebf8ff;
        }

        .upload-dropzone:hover {
          border-color: #4299e1;
        }

        .file-input {
          display: none;
        }

        .upload-label {
          cursor: pointer;
        }

        .upload-icon {
          font-size: 48px;
          margin-bottom: 16px;
        }

        .upload-text {
          font-size: 16px;
          color: #2d3748;
          margin-bottom: 8px;
        }

        .upload-hint {
          font-size: 14px;
          color: #718096;
        }

        .file-preview {
          border: 1px solid #e2e8f0;
          border-radius: 8px;
          padding: 20px;
          background: white;
        }

        .preview-image {
          margin-bottom: 16px;
          text-align: center;
        }

        .preview-image img {
          max-width: 100%;
          max-height: 300px;
          border-radius: 4px;
        }

        .file-info {
          margin-bottom: 16px;
        }

        .file-name {
          font-weight: 600;
          color: #2d3748;
          margin-bottom: 4px;
        }

        .file-size {
          font-size: 14px;
          color: #718096;
        }

        .upload-progress {
          margin-bottom: 16px;
        }

        .progress-bar {
          height: 8px;
          background: #e2e8f0;
          border-radius: 4px;
          overflow: hidden;
          margin-bottom: 8px;
        }

        .progress-fill {
          height: 100%;
          background: #4299e1;
          transition: width 0.3s ease;
        }

        .progress-text {
          text-align: center;
          font-size: 14px;
          color: #718096;
        }

        .file-actions {
          display: flex;
          gap: 12px;
          justify-content: center;
        }

        .btn {
          padding: 10px 24px;
          border-radius: 6px;
          font-weight: 500;
          cursor: pointer;
          transition: all 0.2s ease;
          border: none;
        }

        .btn:disabled {
          opacity: 0.6;
          cursor: not-allowed;
        }

        .btn-primary {
          background: #4299e1;
          color: white;
        }

        .btn-primary:hover:not(:disabled) {
          background: #3182ce;
        }

        .btn-secondary {
          background: #e2e8f0;
          color: #2d3748;
        }

        .btn-secondary:hover:not(:disabled) {
          background: #cbd5e0;
        }
      `}</style>
    </div>
  );
}
