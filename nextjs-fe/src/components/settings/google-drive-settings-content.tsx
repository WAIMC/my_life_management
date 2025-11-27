'use client';

import React, { useState, useEffect } from 'react';
import { googleDriveConfigService } from '@/services/google-drive-config.service';
import type { GoogleDriveConfig } from '@/types/google-drive-config.types';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Upload, FolderOpen, Check, X, Trash2, Power } from 'lucide-react';
import toast from 'react-hot-toast';

export function GoogleDriveSettingsContent() {
  const [configs, setConfigs] = useState<GoogleDriveConfig[]>([]);
  const [loading, setLoading] = useState(false);
  const [showUpload, setShowUpload] = useState(false);

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
      setShowUpload(false);
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
    <div className="space-y-6">
      {/* Upload Section */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Tải lên thông tin xác thực</CardTitle>
              <CardDescription>
                Thêm cấu hình Google Drive mới bằng cách tải lên file JSON thông tin xác thực
              </CardDescription>
            </div>
            <Button
              onClick={() => setShowUpload(!showUpload)}
              variant={showUpload ? 'outline' : 'default'}
            >
              <Upload className="mr-2 h-4 w-4" />
              {showUpload ? 'Hủy' : 'Tải lên'}
            </Button>
          </div>
        </CardHeader>

        {showUpload && (
          <CardContent>
            <form onSubmit={handleUpload} className="space-y-4">
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

              <Button type="submit" disabled={uploading} className="w-full">
                {uploading ? 'Đang tải lên...' : 'Tải lên'}
              </Button>
            </form>
          </CardContent>
        )}
      </Card>

      {/* Configurations List */}
      <Card>
        <CardHeader>
          <CardTitle>Danh sách cấu hình</CardTitle>
          <CardDescription>
            Quản lý các cấu hình Google Drive của bạn
          </CardDescription>
        </CardHeader>
        <CardContent>
          {loading ? (
            <div className="flex items-center justify-center py-8">
              <div className="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent" />
            </div>
          ) : configs.length === 0 ? (
            <div className="flex flex-col items-center justify-center py-12 text-center">
              <FolderOpen className="mb-4 h-12 w-12 text-muted-foreground" />
              <p className="text-muted-foreground">
                Chưa có cấu hình nào. Tải lên thông tin xác thực để bắt đầu.
              </p>
            </div>
          ) : (
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
              {configs.map((config) => (
                <Card
                  key={config.id}
                  className={config.is_active ? 'border-green-500 bg-green-50 dark:bg-green-950/20' : ''}
                >
                  <CardHeader className="pb-3">
                    <div className="flex items-start justify-between">
                      <CardTitle className="text-lg">{config.name}</CardTitle>
                      {config.is_active && (
                        <Badge variant="default" className="bg-green-600">
                          <Check className="mr-1 h-3 w-3" />
                          Đang dùng
                        </Badge>
                      )}
                    </div>
                  </CardHeader>
                  <CardContent className="space-y-3">
                    <div className="space-y-1 text-sm">
                      <div className="flex items-start gap-2">
                        <span className="font-medium text-muted-foreground">Root Folder:</span>
                        <span className="break-all font-mono text-xs">{config.root_folder_id}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <span className="font-medium text-muted-foreground">Trạng thái:</span>
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
                      </div>
                      <div className="flex items-center gap-2">
                        <span className="font-medium text-muted-foreground">Tạo lúc:</span>
                        <span>{new Date(config.created_at).toLocaleDateString('vi-VN')}</span>
                      </div>
                    </div>

                    <div className="flex gap-2 pt-2">
                      {!config.is_active && (
                        <Button
                          onClick={() => handleActivate(config.id)}
                          size="sm"
                          variant="default"
                          className="flex-1"
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
                        className={!config.is_active ? 'flex-1' : 'w-full'}
                      >
                        <Trash2 className="mr-1 h-3 w-3" />
                        Xóa
                      </Button>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
