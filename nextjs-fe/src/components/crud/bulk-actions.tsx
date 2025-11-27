'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Trash2, Archive, CheckCircle, XCircle, MoreHorizontal } from 'lucide-react';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';

export interface BulkAction {
  label: string;
  icon?: React.ReactNode;
  variant?: 'default' | 'destructive' | 'outline';
  onClick: (selectedIds: number[]) => void | Promise<void>;
  confirmMessage?: string;
  confirmTitle?: string;
}

interface BulkActionsProps {
  selectedIds: number[];
  onClearSelection: () => void;
  actions?: BulkAction[];
  isLoading?: boolean;
}

export function BulkActions({
  selectedIds,
  onClearSelection,
  actions = [],
  isLoading = false,
}: BulkActionsProps) {
  const [confirmAction, setConfirmAction] = useState<BulkAction | null>(null);
  const [isExecuting, setIsExecuting] = useState(false);

  const handleActionClick = (action: BulkAction) => {
    if (action.confirmMessage) {
      setConfirmAction(action);
    } else {
      executeAction(action);
    }
  };

  const executeAction = async (action: BulkAction) => {
    setIsExecuting(true);
    try {
      await action.onClick(selectedIds);
      onClearSelection();
    } catch (error) {
    } finally {
      setIsExecuting(false);
      setConfirmAction(null);
    }
  };

  if (selectedIds.length === 0) {
    return null;
  }

  const defaultActions: BulkAction[] = [
    {
      label: 'Delete Selected',
      icon: <Trash2 className="h-4 w-4" />,
      variant: 'destructive',
      onClick: async (ids) => {
      },
      confirmMessage: `Are you sure you want to delete ${selectedIds.length} item(s)? This action cannot be undone.`,
      confirmTitle: 'Delete Items',
    },
    {
      label: 'Archive Selected',
      icon: <Archive className="h-4 w-4" />,
      onClick: async (ids) => {
      },
    },
    {
      label: 'Activate Selected',
      icon: <CheckCircle className="h-4 w-4" />,
      onClick: async (ids) => {
      },
    },
    {
      label: 'Deactivate Selected',
      icon: <XCircle className="h-4 w-4" />,
      onClick: async (ids) => {
      },
    },
  ];

  const allActions = actions.length > 0 ? actions : defaultActions;

  return (
    <>
      <div className="flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 p-3 dark:border-blue-800 dark:bg-blue-900/20">
        <span className="text-sm font-medium text-blue-900 dark:text-blue-100">
          {selectedIds.length} item(s) selected
        </span>
        
        <div className="ml-auto flex items-center gap-2">
          {allActions.slice(0, 2).map((action, index) => (
            <Button
              key={index}
              size="sm"
              variant={action.variant || 'outline'}
              onClick={() => handleActionClick(action)}
              disabled={isLoading || isExecuting}
              className="gap-2"
            >
              {action.icon}
              {action.label}
            </Button>
          ))}
          
          {allActions.length > 2 && (
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button
                  size="sm"
                  variant="outline"
                  disabled={isLoading || isExecuting}
                >
                  <MoreHorizontal className="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                {allActions.slice(2).map((action, index) => (
                  <DropdownMenuItem
                    key={index}
                    onClick={() => handleActionClick(action)}
                    className="gap-2"
                  >
                    {action.icon}
                    {action.label}
                  </DropdownMenuItem>
                ))}
              </DropdownMenuContent>
            </DropdownMenu>
          )}

          <DropdownMenuSeparator className="h-6" />
          
          <Button
            size="sm"
            variant="ghost"
            onClick={onClearSelection}
            disabled={isLoading || isExecuting}
          >
            Clear
          </Button>
        </div>
      </div>

      {/* Confirmation Dialog */}
      <AlertDialog open={!!confirmAction} onOpenChange={() => setConfirmAction(null)}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>
              {confirmAction?.confirmTitle || 'Confirm Action'}
            </AlertDialogTitle>
            <AlertDialogDescription>
              {confirmAction?.confirmMessage}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel disabled={isExecuting}>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={() => confirmAction && executeAction(confirmAction)}
              disabled={isExecuting}
            >
              {isExecuting ? 'Processing...' : 'Continue'}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </>
  );
}
