# Shared Module

## Purpose
Centralized location for all reusable code, utilities, and configurations used across the application.

## Structure

### `/api` - API Layer
- **client/** - Axios client configuration with interceptors
- **endpoints/** - API endpoint constants (API_ENDPOINTS)
- **interceptors/** - Request/response interceptors
- Clean separation of API communication concerns

### `/constants` - Application Constants
- **constants.ts** - CommonVal (HTTP codes, date formats, validation limits)
- **media.ts** - MediaConst (file types, extensions, size limits)
- **index.ts** - Barrel export
- Synchronized with Laravel backend constants

### `/enums` - Enumerations
- **enums.ts** - Status enums matching Laravel backend
- Type-safe enum values with labels
- Gender, AdminStatus, StatusEnum, CategoryStatus, etc.
- **index.ts** - Barrel export

### `/types` - TypeScript Definitions
- **api.ts** - API response types, entity interfaces
- **authType.ts** - Authentication types
- **media-file.types.ts** - Media file types
- **models/** - Organized type definitions (master, management, history)
- **env.d.ts** - Environment variable types
- All interfaces matching Laravel Resources exactly

### `/validation` - Zod Schemas
- **validation.ts** - Form validation schemas
- **validation-rules.ts** - Validation constants (field lengths)
- **loginSchema.ts** - Login-specific schema
- Matches Laravel FormRequest validation rules

### `/utils` - Utility Functions
- **date-formatter.ts** - Date formatting utilities
- **error-handler.ts** - Error handling utilities
- **type-guards.ts** - TypeScript type guards
- **auth-lock.ts** - Authentication lock mechanism
- **notification.ts** - Notification helpers
- **performance.ts** - Performance monitoring
- **lazy-image.tsx** - Lazy loading image component

### `/config` - Configuration
- **env.ts** - Environment variables
- **navigation.ts** - Navigation menu configuration
- **index.ts** - Barrel export
- Note: All messages (including error codes) are in `/messages/en.json` (next-intl)

### `/hooks` - React Hooks
- **use-auth.ts** - Authentication hook
- **useCrud.ts** - CRUD operations hook
- **useApiData.ts** - API data fetching hook
- **useHistory.ts** - History management hook
- **useJunctionTable.ts** - Junction table hook
- **use-file-manager.ts** - File manager hook
- **store-hooks.ts** - Redux store hooks

### `/services` - API Services
- **base/** - BaseCrudService (generic CRUD operations)
- **modules/** - Feature-specific services
  - Master: admin, role, department, feature, api, token, policy-department
  - Management: user, category, skill, banner, slider, social, skill-description, setting-link
  - Utilities: auth, media-file

## Usage Examples

### Import from Shared
```typescript
// API endpoints and client
import { API_ENDPOINTS, apiClient } from '@/shared/api';

// Constants
import { CommonVal, MediaConst } from '@/shared/constants';

// Enums
import { Gender, AdminStatus, StatusEnum } from '@/shared/enums';

// Types
import type { AdminMst, UserMgmt, CategoryMgmt } from '@/shared/types';

// Validation
import { adminSchema, userSchema, ValidationRules } from '@/shared/validation';

// Utils
import { formatDateForBackend, handleApiError } from '@/shared/utils';

// Hooks
import { useCrud, useAuth, useApiData } from '@/shared/hooks';

// Services
import { AdminService, UserService } from '@/shared/services';
```

### Using BaseCrudService
```typescript
import { BaseCrudService } from '@/shared/services/base/crud.service';
import { API_ENDPOINTS } from '@/shared/api';
import type { AdminMst } from '@/shared/types';

class AdminService extends BaseCrudService<AdminMst> {
  constructor() {
    super(API_ENDPOINTS.MASTER.ADMIN);
  }
  
  // Add custom methods
  async resetPassword(id: number, password: string) {
    return this.apiClient.post(`${this.endpoint}/${id}/reset-password`, { password });
  }
}

export const adminService = new AdminService();
```

### Form Validation Example
```typescript
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { adminSchema } from '@/shared/validation';
import type { AdminMst } from '@/shared/types';

const form = useForm<AdminMst>({
  resolver: zodResolver(adminSchema),
  defaultValues: initialData,
});
```

## Best Practices

### ✅ DO:
- Import from `@/shared/*` barrel exports
- Keep services extending BaseCrudService
- Sync constants/enums with Laravel backend
- Use TypeScript types for all APIs
- Validate forms with Zod schemas
- Add documentation for complex utilities

### ❌ DON'T:
- Import from deep paths like `../../shared/utils/date-formatter`
- Create duplicate constants/types in other folders
- Hardcode validation rules in forms
- Mix business logic with API calls
- Create services without types

## Maintenance

### Adding New Features
1. Add types to `/types/api.ts`
2. Add validation to `/validation/validation.ts`
3. Add service to `/services/modules/`
4. Add endpoints to `/api/endpoints/index.ts`
5. Update barrel exports in `index.ts` files

### Syncing with Backend
1. Check Laravel Resources for response structure
2. Update types in `/types/api.ts`
3. Check Laravel Constants for values
4. Update constants in `/constants/`
5. Check Laravel Enums
6. Update enums in `/enums/enums.ts`
7. Check Laravel FormRequests
8. Update validation in `/validation/validation.ts`

## File Naming Conventions

- **Types**: PascalCase interfaces (e.g., `AdminMst`, `UserMgmt`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `HTTP_OK`, `DATE_FORMAT`)
- **Services**: camelCase with `.service.ts` suffix
- **Hooks**: camelCase with `use` prefix
- **Utils**: camelCase with descriptive names
- **Components**: PascalCase

## Architecture Benefits

1. **Single Source of Truth** - No duplicate definitions
2. **Type Safety** - Full TypeScript coverage
3. **Maintainability** - Easy to find and update code
4. **Scalability** - Add features without clutter
5. **Backend Sync** - Matches Laravel structure
6. **DRY Principle** - Reusable code in one place
7. **Developer Experience** - Clean imports with path aliases
8. **Testing** - Easier to mock and test isolated modules

## Import Path Aliases

Configured in `tsconfig.json`:
```json
{
  "@/shared": ["./src/shared"],
  "@/shared/*": ["./src/shared/*"],
  "@/components/*": ["./src/components/*"],
  "@/app/*": ["./src/app/*"],
  "@/features/*": ["./src/features/*"]
}
```
