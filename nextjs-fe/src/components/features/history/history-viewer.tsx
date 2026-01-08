'use client';

import { useState, useEffect } from 'react';
import { useHistory } from '@/shared/hooks/useHistory';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { History, RotateCcw, Eye, Calendar, User } from 'lucide-react';
import { formatDistanceToNow } from 'date-fns';
import type { BaseHistory } from '@/shared/types/models';
import { useTranslations } from 'next-intl';

interface HistoryViewerProps {
  // New interface (preferred)
  entityType?: string;
  entityId?: number;
  endpoint?: string;
  // Old interface (backwards compatibility)
  baseUrl?: string;
  recordId?: number;
  // Common props
  onRestore?: (historyId: number) => void;
  className?: string;
}

export function HistoryViewer({
  entityType,
  entityId,
  endpoint,
  baseUrl,
  recordId,
  onRestore,
  className,
}: HistoryViewerProps) {
  const t = useTranslations();
  // Support both new and old interfaces
  const finalBaseUrl = endpoint || baseUrl || '';
  const finalRecordId = entityId || recordId || 0;

  const { history, isLoading, fetchHistory, restoreVersion } = useHistory({
    baseUrl: finalBaseUrl,
    recordId: finalRecordId,
  });

  const [selectedHistory, setSelectedHistory] = useState<BaseHistory | null>(null);

  useEffect(() => {
    fetchHistory();
  }, [fetchHistory]);

  const handleRestore = async (historyId: number) => {
    if (confirm(t('history.restoreConfirm'))) {
      await restoreVersion(historyId);
      onRestore?.(historyId);
    }
  };

  const getActionColor = (action: string) => {
    switch (action) {
      case 'create':
        return 'bg-green-100 text-green-800';
      case 'update':
        return 'bg-blue-100 text-blue-800';
      case 'delete':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getActionLabel = (action: string) => {
    return action.charAt(0).toUpperCase() + action.slice(1);
  };

  if (isLoading) {
    return (
      <Card className={className}>
        <CardContent className="flex h-64 items-center justify-center">
          <div className="text-center text-muted-foreground">
            <History className="mx-auto h-8 w-8 animate-spin" />
            <p className="mt-2">Loading history...</p>
          </div>
        </CardContent>
      </Card>
    );
  }

  if (history.length === 0) {
    return (
      <Card className={className}>
        <CardContent className="flex h-64 items-center justify-center">
          <div className="text-center text-muted-foreground">
            <History className="mx-auto h-8 w-8" />
            <p className="mt-2">No history found</p>
          </div>
        </CardContent>
      </Card>
    );
  }

  return (
    <Card className={className}>
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <History className="h-5 w-5" />
          Change History
        </CardTitle>
        <CardDescription>
          View all changes made to this record
        </CardDescription>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          {history.map((item, index) => (
            <div key={item.id}>
              <div className="flex items-start gap-4">
                <div className="flex-1">
                  <div className="flex items-center gap-2">
                    <Badge className={getActionColor(item.action)}>
                      {getActionLabel(item.action)}
                    </Badge>
                    <span className="text-sm text-muted-foreground">
                      {(() => {
                        try {
                          const date = item.changed_at ? new Date(item.changed_at) : null;
                          return date && !isNaN(date.getTime()) 
                            ? formatDistanceToNow(date, { addSuffix: true })
                            : 'Unknown time';
                        } catch (e) {
                          return 'Invalid date';
                        }
                      })()}
                    </span>
                  </div>
                  
                  <div className="mt-2 flex items-center gap-4 text-sm">
                    <div className="flex items-center gap-1 text-muted-foreground">
                      <User className="h-4 w-4" />
                      <span>User #{item.changed_by}</span>
                    </div>
                    <div className="flex items-center gap-1 text-muted-foreground">
                      <Calendar className="h-4 w-4" />
                      <span>
                        {(() => {
                           try {
                             const date = item.changed_at ? new Date(item.changed_at) : null;
                             return date && !isNaN(date.getTime())
                               ? date.toLocaleString()
                               : 'Unknown date';
                           } catch (e) {
                             return 'Invalid date';
                           }
                        })()}
                      </span>
                    </div>
                  </div>

                  {item.ip_address && (
                    <div className="mt-1 text-xs text-muted-foreground">
                      IP: {item.ip_address}
                    </div>
                  )}
                </div>

                <div className="flex gap-2">
                  <Button
                    size="sm"
                    variant="outline"
                    onClick={() => setSelectedHistory(item)}
                  >
                    <Eye className="h-4 w-4" />
                  </Button>
                  {item.action !== 'delete' && onRestore && (
                    <Button
                      size="sm"
                      variant="outline"
                      onClick={() => handleRestore(item.id)}
                    >
                      <RotateCcw className="h-4 w-4" />
                    </Button>
                  )}
                </div>
              </div>
              
              {index < history.length - 1 && <Separator className="mt-4" />}
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  );
}
