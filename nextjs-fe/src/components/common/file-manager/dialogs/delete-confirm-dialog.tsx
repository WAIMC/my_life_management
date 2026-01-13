'use client';

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
import { useTranslations } from 'next-intl';
import type { DeleteConfirmDialogProps } from '@/shared/types/file-manager.types';

export const DeleteConfirmDialog = ({
  open,
  onOpenChange,
  onConfirm,
  count,
  itemName,
}: DeleteConfirmDialogProps) => {
  const t = useTranslations('fileManager.dialogs');

  return (
    <AlertDialog open={open} onOpenChange={onOpenChange}>
      <AlertDialogContent>
        <AlertDialogHeader>
          <AlertDialogTitle>{t('deleteConfirm.title')}</AlertDialogTitle>
          <AlertDialogDescription>
            {count === 1
              ? t('deleteConfirm.singleMessage', { name: itemName || '' })
              : t('deleteConfirm.multipleMessage', { count })}
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter>
          <AlertDialogCancel>{t('cancel')}</AlertDialogCancel>
          <AlertDialogAction
            onClick={(e) => {
              e.preventDefault();
              onConfirm().then(() => onOpenChange(false));
            }}
            className="bg-red-600 hover:bg-red-700"
          >
            {t('delete')}
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
};
