'use client';

import React, { useState, useEffect } from 'react';
import { googleDriveConfigService } from '@/services/google-drive-config.service';
import type { GoogleDriveConfig } from '@/types/google-drive-config.types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { Upload, Check, X, Trash2, Power, Plus } from 'lucide-react';
import toast from 'react-hot-toast';

export function GoogleDriveSettingsTable() {
  const [configs, setConfigs] = useState<GoogleDriveConfig[]>([]);
  const [loading, setLoading] = useState(false);
  const [showUploadDialog, setShowUploadDialog] = useState(false);

  // Upload form state
  const [file, setFile] = useState<File | null>(null);
  const [configName, setConfigName] = useState('');
  const [rootFolderId, setRootFolderId] = useState('');
  const [uploading, setUploading] = useState(false);

  const loadConfigs = async () => {
    setLoading(true);
    try {
      const response = await googleDriveConfigService.list();
      setConfigs(response.data);
    } catch (error) {
      toast.error('Không thể tải cấu hình');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadConfigs();
  }, []);

  const handleUpload = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file || !configName || !rootFolderId) return;

    setUploading(true);
    try {
      await googleDriveConfigService.upload({
        file,
        name: configName,
        root_folder_id: rootFolderId,
      });

      toast.success('Tải lên thông tin xác thực thành công!');
      setFile(null);
      setConfigName('');
      setRootFolderId('');
      setShowUploadDialog(false);
      loadConfigs();
    } catch (error) {
      toast.error('Tải lên thất bại: ' + (error as Error).message);
    } finally {
      setUploading(false);
    }
  };

  const handleActivate = async (id: number) => {
    try {
      await googleDriveConfigService.activate(id);
      toast.success('Kích hoạt cấu hình thành công!');
      loadConfigs();
    } catch (error) {
      toast.error('Không thể kích hoạt cấu hình');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Bạn có chắc chắn muốn xóa cấu hình này?')) return;

    try {
      await googleDriveConfigService.delete([id]);
      toast.success('Xóa cấu hình thành công!');
      loadConfigs();
    } catch (error) {
      toast.error('Xóa thất bại: ' + (error as Error).message);
    }
  };

  return (
    <div className="space-y-4">
      {/* Header with Create Button */}
      <div className="flex items-center justify-between">
        <div>
          <h3 className="text-lg font-medium">Danh sách cấu hình</h3>
          <p className="text-sm text-muted-foreground">
            Quản lý các cấu hình Google Drive của bạn
          </p>
        </div>
        <Button onClick={() => setShowUploadDialog(true)}>
          <Plus className="mr-2 h-4 w-4" />
          Tạo mới
        </Button>
      </div>

      {/* Table */}
      <div className="rounded-md border">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Tên cấu hình</TableHead>
              <TableHead>Root Folder ID</TableHead>
              <TableHead>Trạng thái</TableHead>
              <TableHead>Đang sử dụng</TableHead>
              <TableHead>Ngày tạo</TableHead>
              <TableHead className="text-right">Hành động</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {loading ? (
              <TableRow>
                <TableCell colSpan={6} className="h-24 text-center">
                  <div className="flex items-center justify-center">
                    <div className="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent" />
                  </div>
                </TableCell>
              </TableRow>
            ) : configs.length === 0 ? (
              <TableRow>
                <TableCell colSpan={6} className="h-24 text-center">
                  <div className="flex flex-col items-center justify-center text-muted-foreground">
                    <Upload className="mb-2 h-8 w-8" />
                    <p>Chưa có cấu hình nào. Nhấn "Tạo mới" để bắt đầu.</p>
                  </div>
                </TableCell>
              </TableRow>
            ) : (
              configs.map((config) => (
                <TableRow key={config.id}>
                  <TableCell className="font-medium">{config.name}</TableCell>
                  <TableCell>
                    <code className="rounded bg-muted px-2 py-1 text-xs">
                      {config.root_folder_id}
                    </code>
                  </TableCell>
                  <TableCell>
                    {config.has_valid_credentials ? (
                      <Badge variant="outline" className="border-green-500 text-green-700 dark:text-green-400">
                        <Check className="mr-1 h-3 w-3" />
                        Hợp lệ
                      </Badge>
                    ) : (
                      <Badge variant="outline" className="border-red-500 text-red-700 dark:text-red-400">
                        <X className="mr-1 h-3 w-3" />
                        Không hợp lệ
                      </Badge>
                    )}
                  </TableCell>
                  <TableCell>
                    {config.is_active ? (
                      <Badge variant="default" className="bg-green-600">
                        <Check className="mr-1 h-3 w-3" />
                        Đang dùng
                      </Badge>
                    ) : (
                      <span className="text-sm text-muted-foreground">-</span>
                    )}
                  </TableCell>
                  <TableCell>
                    {new Date(config.created_at).toLocaleDateString('vi-VN')}
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      {!config.is_active && (
                        <Button
                          onClick={() => handleActivate(config.id)}
                          size="sm"
                          variant="outline"
                        >
                          <Power className="mr-1 h-3 w-3" />
                          Kích hoạt
                        </Button>
                      )}
                      <Button
                        onClick={() => handleDelete(config.id)}
                        size="sm"
                        variant="destructive"
                        disabled={config.is_active}
                      >
                        <Trash2 className="mr-1 h-3 w-3" />
                        Xóa
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>

      {/* Upload Dialog */}
      <Dialog open={showUploadDialog} onOpenChange={setShowUploadDialog}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>Tải lên thông tin xác thực</DialogTitle>
            <DialogDescription>
              Thêm cấu hình Google Drive mới bằng cách tải lên file JSON thông tin xác thực
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleUpload}>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="configName">Tên cấu hình</Label>
                <Input
                  id="configName"
                  type="text"
                  value={configName}
                  onChange={(e) => setConfigName(e.target.value)}
                  placeholder="VD: Production Config"
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="rootFolderId">Root Folder ID</Label>
                <Input
                  id="rootFolderId"
                  type="text"
                  value={rootFolderId}
                  onChange={(e) => setRootFolderId(e.target.value)}
                  placeholder="ID thư mục Google Drive"
                  required
                />
                <p className="text-sm text-muted-foreground">
                  Lấy ID này từ URL thư mục Google Drive của bạn
                </p>
              </div>

              <div className="space-y-2">
                <Label htmlFor="credentialsFile">File JSON thông tin xác thực</Label>
                <Input
                  id="credentialsFile"
                  type="file"
                  accept=".json"
                  onChange={(e) => setFile(e.target.files?.[0] || null)}
                  required
                />
                <p className="text-sm text-muted-foreground">
                  File JSON thông tin xác thực Service Account
                </p>
              </div>
            </div>
            <DialogFooter>
              <Button
                type="button"
                variant="outline"
                onClick={() => setShowUploadDialog(false)}
                disabled={uploading}
              >
                Hủy
              </Button>
              <Button type="submit" disabled={uploading}>
                {uploading ? 'Đang tải lên...' : 'Tải lên'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
