'use client';

import { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Save, Filter, Trash2, Star } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import toast from 'react-hot-toast';

export interface SavedFilter {
  id: string;
  name: string;
  filters: Record<string, any>;
  isDefault?: boolean;
}

interface SavedFiltersProps {
  currentFilters: Record<string, any>;
  onApplyFilter: (filters: Record<string, any>) => void;
  storageKey?: string;
  className?: string;
}

export function SavedFilters({
  currentFilters,
  onApplyFilter,
  storageKey = 'saved_filters',
  className,
}: SavedFiltersProps) {
  const [savedFilters, setSavedFilters] = useState<SavedFilter[]>([]);
  const [saveDialogOpen, setSaveDialogOpen] = useState(false);
  const [filterName, setFilterName] = useState('');

  useEffect(() => {
    loadFilters();
  }, []);

  const loadFilters = () => {
    try {
      const stored = localStorage.getItem(storageKey);
      if (stored) {
        setSavedFilters(JSON.parse(stored));
      }
    } catch (error) {
    }
  };

  const saveFilters = (filters: SavedFilter[]) => {
    try {
      localStorage.setItem(storageKey, JSON.stringify(filters));
      setSavedFilters(filters);
    } catch (error) {
      toast.error('Failed to save filter');
    }
  };

  const handleSaveFilter = () => {
    if (!filterName.trim()) {
      toast.error('Please enter a filter name');
      return;
    }

    const newFilter: SavedFilter = {
      id: Date.now().toString(),
      name: filterName,
      filters: currentFilters,
    };

    saveFilters([...savedFilters, newFilter]);
    setFilterName('');
    setSaveDialogOpen(false);
    toast.success('Filter saved successfully');
  };

  const handleDeleteFilter = (id: string) => {
    if (confirm('Are you sure you want to delete this filter?')) {
      saveFilters(savedFilters.filter((f) => f.id !== id));
      toast.success('Filter deleted');
    }
  };

  const handleSetDefault = (id: string) => {
    const updated = savedFilters.map((f) => ({
      ...f,
      isDefault: f.id === id,
    }));
    saveFilters(updated);
    toast.success('Default filter updated');
  };

  const handleApplyFilter = (filter: SavedFilter) => {
    onApplyFilter(filter.filters);
    toast.success(`Applied filter: ${filter.name}`);
  };

  return (
    <div className={className}>
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="outline">
            <Filter className="mr-2 h-4 w-4" />
            Saved Filters
            {savedFilters.length > 0 && (
              <Badge variant="secondary" className="ml-2">
                {savedFilters.length}
              </Badge>
            )}
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" className="w-64">
          {savedFilters.length === 0 ? (
            <div className="p-4 text-center text-sm text-muted-foreground">
              No saved filters
            </div>
          ) : (
            savedFilters.map((filter) => (
              <DropdownMenuItem
                key={filter.id}
                className="flex items-center justify-between"
                onClick={() => handleApplyFilter(filter)}
              >
                <div className="flex items-center gap-2">
                  {filter.isDefault && <Star className="h-3 w-3 fill-yellow-400 text-yellow-400" />}
                  <span>{filter.name}</span>
                </div>
                <div className="flex gap-1">
                  <Button
                    variant="ghost"
                    size="icon"
                    className="h-6 w-6"
                    onClick={(e) => {
                      e.stopPropagation();
                      handleSetDefault(filter.id);
                    }}
                  >
                    <Star className="h-3 w-3" />
                  </Button>
                  <Button
                    variant="ghost"
                    size="icon"
                    className="h-6 w-6"
                    onClick={(e) => {
                      e.stopPropagation();
                      handleDeleteFilter(filter.id);
                    }}
                  >
                    <Trash2 className="h-3 w-3" />
                  </Button>
                </div>
              </DropdownMenuItem>
            ))
          )}
          <DropdownMenuSeparator />
          <DropdownMenuItem onClick={() => setSaveDialogOpen(true)}>
            <Save className="mr-2 h-4 w-4" />
            Save Current Filter
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>

      <Dialog open={saveDialogOpen} onOpenChange={setSaveDialogOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Save Filter</DialogTitle>
            <DialogDescription>
              Give your filter a name to save it for later use
            </DialogDescription>
          </DialogHeader>
          <Input
            placeholder="Filter name"
            value={filterName}
            onChange={(e) => setFilterName(e.target.value)}
            onKeyDown={(e) => e.key === 'Enter' && handleSaveFilter()}
          />
          <DialogFooter>
            <Button variant="outline" onClick={() => setSaveDialogOpen(false)}>
              Cancel
            </Button>
            <Button onClick={handleSaveFilter}>Save</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}
