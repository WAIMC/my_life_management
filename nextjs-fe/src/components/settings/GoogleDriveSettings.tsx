'use client';

import React, { useState, useEffect } from 'react';
import { googleDriveConfigService } from '@/services/google-drive-config.service';
import type { GoogleDriveConfig } from '@/types/google-drive-config.types';

export function GoogleDriveSettings() {
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
      alert('Failed to load configurations');
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
      
      alert('Credentials uploaded successfully!');
      setFile(null);
      setConfigName('');
      setRootFolderId('');
      setShowUpload(false);
      loadConfigs();
    } catch (error) {
      alert('Upload failed: ' + (error as Error).message);
    } finally {
      setUploading(false);
    }
  };

  const handleActivate = async (id: number) => {
    try {
      await googleDriveConfigService.activate(id);
      alert('Configuration activated successfully!');
      loadConfigs();
    } catch (error) {
      alert('Failed to activate configuration');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this configuration?')) return;

    try {
      await googleDriveConfigService.delete([id]);
      alert('Configuration deleted successfully!');
      loadConfigs();
    } catch (error) {
      alert('Failed to delete: ' + (error as Error).message);
    }
  };

  return (
    <div className="google-drive-settings">
      <div className="header">
        <h1>Google Drive Settings</h1>
        <button onClick={() => setShowUpload(!showUpload)} className="btn btn-primary">
          {showUpload ? 'Cancel' : 'Upload Credentials'}
        </button>
      </div>

      {showUpload && (
        <div className="upload-form">
          <h2>Upload Google Drive Credentials</h2>
          <form onSubmit={handleUpload}>
            <div className="form-group">
              <label>Configuration Name</label>
              <input
                type="text"
                value={configName}
                onChange={(e) => setConfigName(e.target.value)}
                placeholder="e.g., Production Config"
                required
              />
            </div>

            <div className="form-group">
              <label>Root Folder ID</label>
              <input
                type="text"
                value={rootFolderId}
                onChange={(e) => setRootFolderId(e.target.value)}
                placeholder="Google Drive folder ID"
                required
              />
              <small>Get this from your Google Drive folder URL</small>
            </div>

            <div className="form-group">
              <label>Credentials JSON File</label>
              <input
                type="file"
                accept=".json"
                onChange={(e) => setFile(e.target.files?.[0] || null)}
                required
              />
              <small>Service account credentials JSON file</small>
            </div>

            <button type="submit" disabled={uploading} className="btn btn-primary">
              {uploading ? 'Uploading...' : 'Upload'}
            </button>
          </form>
        </div>
      )}

      <div className="configs-list">
        <h2>Configurations</h2>
        {loading ? (
          <p>Loading...</p>
        ) : configs.length === 0 ? (
          <p>No configurations found. Upload credentials to get started.</p>
        ) : (
          <div className="configs-grid">
            {configs.map((config) => (
              <div key={config.id} className={`config-card ${config.is_active ? 'active' : ''}`}>
                <div className="config-header">
                  <h3>{config.name}</h3>
                  {config.is_active && <span className="badge">Active</span>}
                </div>
                
                <div className="config-details">
                  <p><strong>Root Folder ID:</strong> {config.root_folder_id}</p>
                  <p><strong>Status:</strong> {config.has_valid_credentials ? '✅ Valid' : '❌ Invalid'}</p>
                  <p><strong>Created:</strong> {new Date(config.created_at).toLocaleDateString()}</p>
                </div>

                <div className="config-actions">
                  {!config.is_active && (
                    <button
                      onClick={() => handleActivate(config.id)}
                      className="btn btn-sm btn-success"
                    >
                      Activate
                    </button>
                  )}
                  <button
                    onClick={() => handleDelete(config.id)}
                    className="btn btn-sm btn-danger"
                    disabled={config.is_active}
                  >
                    Delete
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      <style jsx>{`
        .google-drive-settings {
          padding: 24px;
          max-width: 1200px;
          margin: 0 auto;
        }

        .header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 32px;
        }

        .header h1 {
          font-size: 28px;
          font-weight: 700;
        }

        .upload-form {
          background: #f7fafc;
          padding: 24px;
          border-radius: 8px;
          margin-bottom: 32px;
        }

        .upload-form h2 {
          font-size: 20px;
          margin-bottom: 20px;
        }

        .form-group {
          margin-bottom: 20px;
        }

        .form-group label {
          display: block;
          font-weight: 600;
          margin-bottom: 8px;
        }

        .form-group input {
          width: 100%;
          padding: 10px 12px;
          border: 1px solid #cbd5e0;
          border-radius: 6px;
        }

        .form-group small {
          display: block;
          margin-top: 4px;
          color: #718096;
          font-size: 13px;
        }

        .configs-list h2 {
          font-size: 20px;
          margin-bottom: 20px;
        }

        .configs-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
          gap: 20px;
        }

        .config-card {
          border: 2px solid #e2e8f0;
          border-radius: 8px;
          padding: 20px;
          background: white;
        }

        .config-card.active {
          border-color: #48bb78;
          background: #f0fff4;
        }

        .config-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 16px;
        }

        .config-header h3 {
          font-size: 18px;
          font-weight: 600;
        }

        .badge {
          background: #48bb78;
          color: white;
          padding: 4px 12px;
          border-radius: 12px;
          font-size: 12px;
          font-weight: 600;
        }

        .config-details {
          margin-bottom: 16px;
        }

        .config-details p {
          margin-bottom: 8px;
          font-size: 14px;
          color: #4a5568;
        }

        .config-actions {
          display: flex;
          gap: 8px;
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

        .btn-sm {
          padding: 6px 12px;
          font-size: 14px;
        }

        .btn-success {
          background: #48bb78;
          color: white;
        }

        .btn-success:hover {
          background: #38a169;
        }

        .btn-danger {
          background: #f56565;
          color: white;
        }

        .btn-danger:hover:not(:disabled) {
          background: #e53e3e;
        }

        .btn:disabled {
          opacity: 0.5;
          cursor: not-allowed;
        }
      `}</style>
    </div>
  );
}
