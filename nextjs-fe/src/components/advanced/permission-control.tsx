'use client';

import { createContext, useContext, ReactNode } from 'react';

export interface Permission {
  module: string;
  action: 'create' | 'read' | 'update' | 'delete' | 'export' | 'import';
}

interface PermissionContextType {
  permissions: Permission[];
  hasPermission: (module: string, action: Permission['action']) => boolean;
  hasAnyPermission: (module: string, actions: Permission['action'][]) => boolean;
  hasAllPermissions: (module: string, actions: Permission['action'][]) => boolean;
}

const PermissionContext = createContext<PermissionContextType | undefined>(undefined);

interface PermissionProviderProps {
  permissions: Permission[];
  children: ReactNode;
}

export function PermissionProvider({ permissions, children }: PermissionProviderProps) {
  const hasPermission = (module: string, action: Permission['action']) => {
    return permissions.some((p) => p.module === module && p.action === action);
  };

  const hasAnyPermission = (module: string, actions: Permission['action'][]) => {
    return actions.some((action) => hasPermission(module, action));
  };

  const hasAllPermissions = (module: string, actions: Permission['action'][]) => {
    return actions.every((action) => hasPermission(module, action));
  };

  return (
    <PermissionContext.Provider
      value={{ permissions, hasPermission, hasAnyPermission, hasAllPermissions }}
    >
      {children}
    </PermissionContext.Provider>
  );
}

export function usePermissions() {
  const context = useContext(PermissionContext);
  if (!context) {
    throw new Error('usePermissions must be used within PermissionProvider');
  }
  return context;
}

// HOC for protecting components
export function withPermission<P extends object>(
  Component: React.ComponentType<P>,
  module: string,
  action: Permission['action']
) {
  return function PermissionWrappedComponent(props: P) {
    const { hasPermission } = usePermissions();

    if (!hasPermission(module, action)) {
      return null;
    }

    return <Component {...props} />;
  };
}

// Component for conditional rendering
interface CanProps {
  module: string;
  action: Permission['action'];
  children: ReactNode;
  fallback?: ReactNode;
}

export function Can({ module, action, children, fallback = null }: CanProps) {
  const { hasPermission } = usePermissions();

  if (!hasPermission(module, action)) {
    return <>{fallback}</>;
  }

  return <>{children}</>;
}
