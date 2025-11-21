'use client';

import { useState, useMemo } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Search, Save } from 'lucide-react';

interface JunctionManagerProps<T extends { id: number; name: string }> {
  allItems: T[];
  selectedIds: number[];
  onSelectionChange: (ids: number[]) => void;
  onSave: () => Promise<void>;
  loading?: boolean;
  saving?: boolean;
  title?: string;
  itemLabel?: string;
  searchPlaceholder?: string;
}

export function JunctionManager<T extends { id: number; name: string }>({
  allItems,
  selectedIds,
  onSelectionChange,
  onSave,
  loading = false,
  saving = false,
  title = 'Manage Relationships',
  itemLabel = 'items',
  searchPlaceholder = 'Search...',
}: JunctionManagerProps<T>) {
  const [searchQuery, setSearchQuery] = useState('');

  // Filter items based on search query
  const filteredItems = useMemo(() => {
    if (!searchQuery) return allItems;
    const query = searchQuery.toLowerCase();
    return allItems.filter((item) => item.name.toLowerCase().includes(query));
  }, [allItems, searchQuery]);

  // Calculate pending changes
  const pendingChanges = useMemo(() => {
    return selectedIds.length;
  }, [selectedIds]);

  const handleToggle = (id: number) => {
    if (selectedIds.includes(id)) {
      onSelectionChange(selectedIds.filter((selectedId) => selectedId !== id));
    } else {
      onSelectionChange([...selectedIds, id]);
    }
  };

  const handleSelectAll = () => {
    if (selectedIds.length === allItems.length) {
      onSelectionChange([]);
    } else {
      onSelectionChange(allItems.map((item) => item.id));
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center py-12">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
        <span className="ml-3 text-sm text-gray-600 dark:text-gray-400">Loading...</span>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h3 className="text-lg font-semibold">{title}</h3>
          <p className="text-sm text-gray-600 dark:text-gray-400">
            {selectedIds.length} of {allItems.length} {itemLabel} selected
          </p>
        </div>
        <Button onClick={onSave} disabled={saving} className="gap-2">
          <Save className="h-4 w-4" />
          {saving ? 'Saving...' : 'Save Changes'}
        </Button>
      </div>

      {/* Search Bar */}
      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
        <Input
          type="text"
          placeholder={searchPlaceholder}
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="pl-10"
        />
      </div>

      {/* Select All */}
      <div className="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <Checkbox
          id="select-all"
          checked={selectedIds.length === allItems.length && allItems.length > 0}
          onCheckedChange={handleSelectAll}
        />
        <Label htmlFor="select-all" className="cursor-pointer font-medium">
          Select All
        </Label>
        {pendingChanges > 0 && (
          <Badge variant="secondary" className="ml-auto">
            {pendingChanges} selected
          </Badge>
        )}
      </div>

      {/* Items List */}
      <div className="border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
        {filteredItems.length === 0 ? (
          <div className="p-8 text-center text-gray-500">
            {searchQuery ? 'No items found matching your search' : `No ${itemLabel} available`}
          </div>
        ) : (
          filteredItems.map((item) => (
            <div
              key={item.id}
              className="flex items-center gap-3 p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
            >
              <Checkbox
                id={`item-${item.id}`}
                checked={selectedIds.includes(item.id)}
                onCheckedChange={() => handleToggle(item.id)}
              />
              <Label
                htmlFor={`item-${item.id}`}
                className="flex-1 cursor-pointer font-normal"
              >
                {item.name}
              </Label>
              {selectedIds.includes(item.id) && (
                <Badge variant="default" className="text-xs">
                  Selected
                </Badge>
              )}
            </div>
          ))
        )}
      </div>

      {/* Footer Info */}
      <div className="text-xs text-gray-500 dark:text-gray-400">
        Click "Save Changes" to apply your selections
      </div>
    </div>
  );
}
