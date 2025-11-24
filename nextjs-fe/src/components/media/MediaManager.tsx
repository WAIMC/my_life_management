'use client';

import React, { useState, useEffect } from 'react';
import { mediaFileService } from '@/services/media-file.service';
import { FileUpload } from './FileUpload';
import type { MediaFile, ListFilesParams, FileType } from '@/types/media-file.types';

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
    <div className="media-manager">
      <div className="manager-header">
        <h1>Media Manager</h1>
        <button onClick={() => setShowUpload(!showUpload)} className="btn btn-primary">
          {showUpload ? 'Hide Upload' : 'Upload File'}
        </button>
      </div>

      {showUpload && (
        <div className="upload-section">
          <FileUpload onUploadSuccess={handleUploadSuccess} />
        </div>
      )}

      <div className="manager-toolbar">
        <div className="search-box">
          <input
            type="text"
            placeholder="Search files..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            onKeyPress={(e) => e.key === 'Enter' && handleSearch()}
          />
          <button onClick={handleSearch} className="btn btn-sm">Search</button>
        </div>

        <div className="filter-tabs">
          {(['all', 'images', 'videos', 'documents'] as FileType[]).map((type) => (
            <button
              key={type}
              onClick={() => setFileType(type)}
              className={`tab ${fileType === type ? 'active' : ''}`}
            >
              {type.charAt(0).toUpperCase() + type.slice(1)}
            </button>
          ))}
        </div>

        {selectedFiles.size > 0 && (
          <button onClick={handleBulkDelete} className="btn btn-danger">
            Delete Selected ({selectedFiles.size})
          </button>
        )}
      </div>

      {loading ? (
        <div className="loading">Loading...</div>
      ) : (
        <>
          <div className="files-grid">
            {files.map((file) => (
              <div key={file.id} className="file-card">
                <div className="file-checkbox">
                  <input
                    type="checkbox"
                    checked={selectedFiles.has(file.id)}
                    onChange={() => toggleFileSelection(file.id)}
                  />
                </div>

                <div className="file-preview">
                  {file.mime_type.startsWith('image/') ? (
                    <img src={file.view_url} alt={file.original_name} />
                  ) : (
                    <div className="file-icon">
                      {mediaFileService.getFileTypeIcon(file.mime_type)}
                    </div>
                  )}
                </div>

                <div className="file-details">
                  <p className="file-name" title={file.original_name}>
                    {file.original_name}
                  </p>
                  <p className="file-meta">
                    {file.human_size} • {new Date(file.created_at).toLocaleDateString()}
                  </p>
                </div>

                <div className="file-actions">
                  <button
                    onClick={() => window.open(file.view_url, '_blank')}
                    className="action-btn"
                    title="View"
                  >
                    👁️
                  </button>
                  <button
                    onClick={() => mediaFileService.download(file.id, file.original_name)}
                    className="action-btn"
                    title="Download"
                  >
                    ⬇️
                  </button>
                  <button
                    onClick={() => handleDelete(file.id)}
                    className="action-btn"
                    title="Delete"
                  >
                    🗑️
                  </button>
                </div>
              </div>
            ))}
          </div>

          {files.length === 0 && (
            <div className="empty-state">
              <p>No files found</p>
            </div>
          )}

          {totalPages > 1 && (
            <div className="pagination">
              <button
                onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                disabled={currentPage === 1}
                className="btn btn-sm"
              >
                Previous
              </button>
              <span className="page-info">
                Page {currentPage} of {totalPages}
              </span>
              <button
                onClick={() => setCurrentPage(p => Math.min(totalPages, p + 1))}
                disabled={currentPage === totalPages}
                className="btn btn-sm"
              >
                Next
              </button>
            </div>
          )}
        </>
      )}

      <style jsx>{`
        .media-manager {
          padding: 24px;
          max-width: 1400px;
          margin: 0 auto;
        }

        .manager-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 24px;
        }

        .manager-header h1 {
          font-size: 28px;
          font-weight: 700;
          color: #1a202c;
        }

        .upload-section {
          margin-bottom: 32px;
          padding: 24px;
          background: #f7fafc;
          border-radius: 8px;
        }

        .manager-toolbar {
          display: flex;
          gap: 16px;
          margin-bottom: 24px;
          flex-wrap: wrap;
          align-items: center;
        }

        .search-box {
          display: flex;
          gap: 8px;
        }

        .search-box input {
          padding: 8px 12px;
          border: 1px solid #cbd5e0;
          border-radius: 6px;
          min-width: 250px;
        }

        .filter-tabs {
          display: flex;
          gap: 8px;
        }

        .tab {
          padding: 8px 16px;
          border: 1px solid #cbd5e0;
          background: white;
          border-radius: 6px;
          cursor: pointer;
          transition: all 0.2s;
        }

        .tab.active {
          background: #4299e1;
          color: white;
          border-color: #4299e1;
        }

        .files-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
          gap: 20px;
          margin-bottom: 24px;
        }

        .file-card {
          border: 1px solid #e2e8f0;
          border-radius: 8px;
          padding: 16px;
          background: white;
          transition: all 0.2s;
          position: relative;
        }

        .file-card:hover {
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .file-checkbox {
          position: absolute;
          top: 12px;
          left: 12px;
        }

        .file-preview {
          width: 100%;
          height: 180px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: #f7fafc;
          border-radius: 6px;
          margin-bottom: 12px;
          overflow: hidden;
        }

        .file-preview img {
          max-width: 100%;
          max-height: 100%;
          object-fit: cover;
        }

        .file-icon {
          font-size: 64px;
        }

        .file-details {
          margin-bottom: 12px;
        }

        .file-name {
          font-weight: 600;
          color: #2d3748;
          margin-bottom: 4px;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        .file-meta {
          font-size: 13px;
          color: #718096;
        }

        .file-actions {
          display: flex;
          gap: 8px;
          justify-content: center;
        }

        .action-btn {
          padding: 6px 12px;
          border: 1px solid #e2e8f0;
          background: white;
          border-radius: 4px;
          cursor: pointer;
          transition: all 0.2s;
        }

        .action-btn:hover {
          background: #f7fafc;
        }

        .loading, .empty-state {
          text-align: center;
          padding: 60px 20px;
          color: #718096;
        }

        .pagination {
          display: flex;
          justify-content: center;
          align-items: center;
          gap: 16px;
        }

        .page-info {
          color: #4a5568;
        }

        .btn {
          padding: 10px 20px;
          border-radius: 6px;
          font-weight: 500;
          cursor: pointer;
          border: none;
          transition: all 0.2s;
        }

        .btn-primary {
          background: #4299e1;
          color: white;
        }

        .btn-primary:hover {
          background: #3182ce;
        }

        .btn-danger {
          background: #f56565;
          color: white;
        }

        .btn-danger:hover {
          background: #e53e3e;
        }

        .btn-sm {
          padding: 6px 12px;
          font-size: 14px;
        }

        .btn:disabled {
          opacity: 0.5;
          cursor: not-allowed;
        }
      `}</style>
    </div>
  );
}
