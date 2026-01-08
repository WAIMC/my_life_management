'use client';

import { useState } from 'react';
import { ChevronRight, ChevronDown, Building2 } from 'lucide-react';
import { cn } from "@/shared/utils";
import { Button } from '@/components/ui/button';
import type { DepartmentMst } from '@/shared/types/api';

interface DepartmentTreeProps {
  departments: DepartmentMst[];
  onSelect?: (department: DepartmentMst) => void;
  selectedId?: number;
  className?: string;
}

interface TreeNodeProps {
  department: DepartmentMst;
  children: DepartmentMst[];
  level: number;
  onSelect?: (department: DepartmentMst) => void;
  selectedId?: number;
}

function TreeNode({ department, children, level, onSelect, selectedId }: TreeNodeProps) {
  const [isExpanded, setIsExpanded] = useState(true);
  const hasChildren = children.length > 0;
  const isSelected = selectedId === department.id;

  return (
    <div>
      <div
        className={cn(
          'flex items-center gap-2 rounded-md px-2 py-1.5 hover:bg-muted cursor-pointer transition-colors',
          isSelected && 'bg-primary/10 text-primary font-medium'
        )}
        style={{ paddingLeft: `${level * 1.5}rem` }}
        onClick={() => onSelect?.(department)}
      >
        {hasChildren ? (
          <button
            onClick={(e) => {
              e.stopPropagation();
              setIsExpanded(!isExpanded);
            }}
            className="p-0.5 hover:bg-muted-foreground/10 rounded"
          >
            {isExpanded ? (
              <ChevronDown className="h-4 w-4" />
            ) : (
              <ChevronRight className="h-4 w-4" />
            )}
          </button>
        ) : (
          <div className="w-5" />
        )}
        
        <Building2 className="h-4 w-4 text-muted-foreground" />
        
        <span className="flex-1">{department.name}</span>
        
        {department.description && (
          <span className="text-xs text-muted-foreground hidden md:block">
            {department.description}
          </span>
        )}
      </div>

      {hasChildren && isExpanded && (
        <div>
          {children.map((child) => {
            const grandChildren = buildTree([child], department.id);
            return (
              <TreeNode
                key={child.id}
                department={child}
                children={grandChildren}
                level={level + 1}
                onSelect={onSelect}
                selectedId={selectedId}
              />
            );
          })}
        </div>
      )}
    </div>
  );
}

function buildTree(departments: DepartmentMst[], parentId?: number): DepartmentMst[] {
  return departments.filter((dept) => dept.parent_id === parentId);
}

export function DepartmentTree({
  departments,
  onSelect,
  selectedId,
  className,
}: DepartmentTreeProps) {
  const rootDepartments = buildTree(departments, undefined);

  if (departments.length === 0) {
    return (
      <div className="flex h-32 items-center justify-center text-sm text-muted-foreground">
        No departments found
      </div>
    );
  }

  return (
    <div className={cn('space-y-1', className)}>
      {rootDepartments.map((dept) => {
        const children = buildTree(departments, dept.id);
        return (
          <TreeNode
            key={dept.id}
            department={dept}
            children={children}
            level={0}
            onSelect={onSelect}
            selectedId={selectedId}
          />
        );
      })}
    </div>
  );
}
