'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Download, Upload, FileSpreadsheet, FileText } from 'lucide-react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import toast from 'react-hot-toast';

export type ExportFormat = 'csv' | 'excel' | 'json';
export type ImportFormat = 'csv' | 'excel';

interface ImportExportProps {
  onExport?: (format: ExportFormat) => Promise<void> | void;
  onImport?: (file: File, format: ImportFormat) => Promise<void> | void;
  onDownloadTemplate?: (format: ImportFormat) => Promise<void> | void;
  exportFormats?: ExportFormat[];
  importFormats?: ImportFormat[];
  moduleName?: string;
}

export function ImportExport({
  onExport,
  onImport,
  onDownloadTemplate,
  exportFormats = ['csv', 'excel'],
  importFormats = ['csv', 'excel'],
  moduleName = 'data',
}: ImportExportProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [isProcessing, setIsProcessing] = useState(false);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);

  const handleExport = async (format: ExportFormat) => {
    if (!onExport) return;

    setIsProcessing(true);
    try {
      await onExport(format);
      toast.success(`Exported ${moduleName} as ${format.toUpperCase()}`);
      setIsOpen(false);
    } catch (error) {
      toast.error('Export failed');
    } finally {
      setIsProcessing(false);
    }
  };

  const handleImport = async (format: ImportFormat) => {
    if (!onImport || !selectedFile) {
      toast.error('Please select a file');
      return;
    }

    setIsProcessing(true);
    try {
      await onImport(selectedFile, format);
      toast.success(`Imported ${moduleName} successfully`);
      setSelectedFile(null);
      setIsOpen(false);
    } catch (error) {
      toast.error('Import failed');
    } finally {
      setIsProcessing(false);
    }
  };

  const handleDownloadTemplate = async (format: ImportFormat) => {
    if (!onDownloadTemplate) return;

    setIsProcessing(true);
    try {
      await onDownloadTemplate(format);
      toast.success(`Downloaded ${format.toUpperCase()} template`);
    } catch (error) {
      toast.error('Template download failed');
    } finally {
      setIsProcessing(false);
    }
  };

  const getFormatIcon = (format: string) => {
    switch (format) {
      case 'csv':
        return <FileText className="h-4 w-4" />;
      case 'excel':
        return <FileSpreadsheet className="h-4 w-4" />;
      default:
        return <FileText className="h-4 w-4" />;
    }
  };

  return (
    <>
      <Button
        variant="outline"
        size="sm"
        onClick={() => setIsOpen(true)}
        className="gap-2"
      >
        <Download className="h-4 w-4" />
        Import/Export
      </Button>

      <Dialog open={isOpen} onOpenChange={setIsOpen}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>Import/Export {moduleName}</DialogTitle>
            <DialogDescription>
              Export data to file or import data from file
            </DialogDescription>
          </DialogHeader>

          <Tabs defaultValue="export" className="w-full">
            <TabsList className="grid w-full grid-cols-2">
              <TabsTrigger value="export">Export</TabsTrigger>
              <TabsTrigger value="import">Import</TabsTrigger>
            </TabsList>

            {/* Export Tab */}
            <TabsContent value="export" className="space-y-4">
              <div className="space-y-2">
                <Label>Select Format</Label>
                <div className="grid grid-cols-2 gap-2">
                  {exportFormats.map((format) => (
                    <Button
                      key={format}
                      variant="outline"
                      onClick={() => handleExport(format)}
                      disabled={isProcessing}
                      className="gap-2"
                    >
                      {getFormatIcon(format)}
                      {format.toUpperCase()}
                    </Button>
                  ))}
                </div>
              </div>
              <p className="text-sm text-muted-foreground">
                Export all data to the selected format
              </p>
            </TabsContent>

            {/* Import Tab */}
            <TabsContent value="import" className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="file">Select File</Label>
                <Input
                  id="file"
                  type="file"
                  accept={importFormats.map(f => f === 'csv' ? '.csv' : '.xlsx,.xls').join(',')}
                  onChange={(e) => setSelectedFile(e.target.files?.[0] || null)}
                  disabled={isProcessing}
                />
                {selectedFile && (
                  <p className="text-sm text-muted-foreground">
                    Selected: {selectedFile.name}
                  </p>
                )}
              </div>

              <div className="space-y-2">
                <Label>Import As</Label>
                <div className="grid grid-cols-2 gap-2">
                  {importFormats.map((format) => (
                    <Button
                      key={format}
                      variant="outline"
                      onClick={() => handleImport(format)}
                      disabled={isProcessing || !selectedFile}
                      className="gap-2"
                    >
                      <Upload className="h-4 w-4" />
                      {format.toUpperCase()}
                    </Button>
                  ))}
                </div>
              </div>

              {onDownloadTemplate && (
                <>
                  <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                      <span className="w-full border-t" />
                    </div>
                    <div className="relative flex justify-center text-xs uppercase">
                      <span className="bg-background px-2 text-muted-foreground">
                        Or
                      </span>
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label>Download Template</Label>
                    <div className="grid grid-cols-2 gap-2">
                      {importFormats.map((format) => (
                        <Button
                          key={format}
                          variant="secondary"
                          onClick={() => handleDownloadTemplate(format)}
                          disabled={isProcessing}
                          className="gap-2"
                        >
                          <Download className="h-4 w-4" />
                          {format.toUpperCase()}
                        </Button>
                      ))}
                    </div>
                  </div>
                </>
              )}
            </TabsContent>
          </Tabs>

          <DialogFooter>
            <Button
              variant="outline"
              onClick={() => setIsOpen(false)}
              disabled={isProcessing}
            >
              Close
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </>
  );
}
