"use client";

import React from 'react';
import FileManager from '@/components/layout/FileManagerFixed';

export default function MediaPage(): React.ReactElement {
  const [showFileManager, setShowFileManager] = React.useState<boolean>(true);

  return (
    <div className="p-6">
      <div className="flex items-center justify-between mb-4">
        <h1 className="text-2xl font-semibold">Media Manager</h1>
        <label className="flex items-center gap-2 text-sm">
          <input type="checkbox" checked={showFileManager} onChange={(e) => setShowFileManager(e.target.checked)} />
          <span>Show File Manager</span>
        </label>
      </div>
      {showFileManager ? (
        <div>
          <FileManager />
        </div>
      ) : (
        <div className="p-6 text-sm text-slate-500">File manager hidden. Toggle to show.</div>
      )}
    </div>
  );
}
