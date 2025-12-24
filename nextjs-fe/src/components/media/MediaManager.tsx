'use client';

import React, { useState, useEffect } from 'react';
import { mediaFileService } from '@/services/media-file.service';
import { FileUpload } from './FileUpload';
import type { MediaFile, ListFilesParams, FileType } from '@/types/media-file.types';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';

export function MediaManager() {
  const [files, setFiles] = useState<MediaFile[]>([]);
  const [loading, setLoading] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [fileType, setFileType] = useState<FileType>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [showUpload, setShowUpload] = useState(false);
  const [selectedFiles, setSelectedFiles] = useState<Set<number>>(new Set());

  const loadFiles = async () => {
    setLoading(true);
    try {
      const params: ListFilesParams = {
        page: currentPage,
        per_page: 20,
        search: searchQuery || undefined,
        file_type: fileType !== 'all' ? fileType : undefined,
      };

      const response = await mediaFileService.list(params);
      setFiles(response.data);
      setTotalPages(response.last_page);
    } catch (error) {
      console.error('Failed to load files:', error);
      alert('Failed to load files');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadFiles();
  }, [currentPage, fileType]);

  const handleSearch = () => {
    setCurrentPage(1);
    loadFiles();
  };

  const handleUploadSuccess = () => {
    setShowUpload(false);
    loadFiles();
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this file?')) return;

    try {
      await mediaFileService.deleteSingle(id);
      loadFiles();
    } catch (error) {
      console.error('Failed to delete file:', error);
      alert('Failed to delete file');
    }
  };

  const handleBulkDelete = async () => {
    if (selectedFiles.size === 0) return;
    if (!confirm(`Delete ${selectedFiles.size} file(s)?`)) return;

    try {
      await mediaFileService.delete({ ids: Array.from(selectedFiles) });
      setSelectedFiles(new Set());
      loadFiles();
    } catch (error) {
      console.error('Failed to delete files:', error);
      alert('Failed to delete files');
    }
  };

  const toggleFileSelection = (id: number) => {
    const newSelection = new Set(selectedFiles);
    if (newSelection.has(id)) {
      newSelection.delete(id);
    } else {
      newSelection.add(id);
    }
    setSelectedFiles(newSelection);
  };

  return (
    <div className="mx-auto max-w-7xl px-6 py-6">
      {/* Header */}
      <div className="mb-6 flex items-center justify-between">
        <h1 className="text-3xl font-bold text-slate-900 dark:text-white">Media Manager</h1>
        <Button onClick={() => setShowUpload(!showUpload)}>
          {showUpload ? 'Hide Upload' : 'Upload File'}
        </Button>
      </div>

      {/* Upload Section */}
      {showUpload && (
        <div className="mb-8 rounded-lg bg-slate-50 p-6 dark:bg-slate-900">
          <FileUpload onUploadSuccess={handleUploadSuccess} />
        </div>
      )}

      {/* Toolbar */}
      <div className="mb-6 flex flex-wrap items-center gap-4">
        {/* Search Box */}
        <div className="flex gap-2">
          <Input
            type="text"
            placeholder="Search files..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
            className="min-w-[250px]"
          />
          <Button onClick={handleSearch} variant="secondary" size="sm">
            Search
          </Button>
        </div>

        {/* Filter Tabs */}
        <div className="flex gap-2">
          {(['all', 'images', 'videos', 'documents'] as FileType[]).map((type) => (
            <Button
              key={type}
              onClick={() => setFileType(type)}
              variant={fileType === type ? 'default' : 'outline'}
              size="sm"
            >
              {type.charAt(0).toUpperCase() + type.slice(1)}
            </Button>
          ))}
        </div>

        {/* Bulk Delete */}
        {selectedFiles.size > 0 && (
          <Button onClick={handleBulkDelete} variant="destructive" size="sm">
            Delete Selected ({selectedFiles.size})
          </Button>
        )}
      </div>

      {/* Loading State */}
      {loading ? (
        <div className="py-16 text-center text-slate-500 dark:text-slate-400">
          <div className="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600 dark:border-slate-800 dark:border-t-blue-400" />
          <p>Loading...</p>
        </div>
      ) : (
        <>
          {/* Files Grid */}
          <div className="mb-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {files.map((file) => (
              <Card key={file.id} className="group relative overflow-hidden transition-shadow hover:shadow-lg">
                {/* Checkbox */}
                <div className="absolute left-3 top-3 z-10">
                  <input
                    type="checkbox"
                    checked={selectedFiles.has(file.id)}
                    onChange={() => toggleFileSelection(file.id)}
                    className="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
                  />
                </div>

                {/* File Preview */}
                <div className="flex h-48 items-center justify-center overflow-hidden bg-slate-100 dark:bg-slate-800">
                  {file.mime_type && file.mime_type.startsWith('image/') ? (
                    <img
                      src={file.url || ''}
                      alt={file.original_name}
                      className="h-full w-full object-cover"
                    />
                  ) : (
                    <div className="text-6xl">
                      {mediaFileService.getFileTypeIcon(file.mime_type || '')}
                    </div>
                  )}
                </div>

                {/* File Details */}
                <div className="p-4">
                  <p
                    className="mb-1 truncate font-semibold text-slate-900 dark:text-white"
                    title={file.original_name}
                  >
                    {file.original_name}
                  </p>
                  <p className="text-sm text-slate-500 dark:text-slate-400">
                    {mediaFileService.formatFileSize(file.size || 0)} • {new Date(file.created_at).toLocaleDateString()}
                  </p>
                </div>

                {/* File Actions */}
                <div className="flex justify-center gap-2 border-t border-slate-200 p-3 dark:border-slate-700">
                  <Button
                    onClick={() => window.open(file.url || '', '_blank')}
                    variant="ghost"
                    size="sm"
                    title="View"
                  >
                    👁️
                  </Button>
                  <Button
                    onClick={() => mediaFileService.download(file.id, file.original_name)}
                    variant="ghost"
                    size="sm"
                    title="Download"
                  >
                    ⬇️
                  </Button>
                  <Button
                    onClick={() => handleDelete(file.id)}
                    variant="ghost"
                    size="sm"
                    title="Delete"
                  >
                    🗑️
                  </Button>
                </div>
              </Card>
            ))}
          </div>

          {/* Empty State */}
          {files.length === 0 && !loading && (
            <div className="py-16 text-center text-slate-500 dark:text-slate-400">
              <p className="text-lg">No files found</p>
            </div>
          )}

          {/* Pagination */}
          {totalPages > 1 && (
            <div className="flex items-center justify-center gap-4">
              <Button
                onClick={() => setCurrentPage((p) => Math.max(1, p - 1))}
                disabled={currentPage === 1}
                variant="outline"
                size="sm"
              >
                Previous
              </Button>
              <span className="text-slate-700 dark:text-slate-300">
                Page {currentPage} of {totalPages}
              </span>
              <Button
                onClick={() => setCurrentPage((p) => Math.min(totalPages, p + 1))}
                disabled={currentPage === totalPages}
                variant="outline"
                size="sm"
              >
                Next
              </Button>
            </div>
          )}
        </>
      )}
    </div>
  );
}
