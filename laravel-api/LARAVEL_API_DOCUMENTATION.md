# Laravel API - Complete Documentation for Next.js Frontend Integration

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [API Endpoints Structure](#api-endpoints-structure)
4. [Request/Response Format](#requestresponse-format)
5. [Authentication & Authorization](#authentication--authorization)
6. [Pagination & Filtering](#pagination--filtering)
7. [Error Handling](#error-handling)
8. [Data Models & TypeScript Interfaces](#data-models--typescript-interfaces)
9. [Validation Rules](#validation-rules)
10. [Complete API Reference](#complete-api-reference)
11. [File Upload & Media Handling](#file-upload--media-handling)
12. [Best Practices for Frontend Integration](#best-practices-for-frontend-integration)
13. [Code Examples](#code-examples)
14. [Real-World Integration Examples](#real-world-integration-examples)

---

## 📖 Project Overview

### Technology Stack
- **Backend**: Laravel 10+ (PHP 8.1+)
- **Database**: MySQL/PostgreSQL
- **Authentication**: JWT (JSON Web Tokens)
- **Cache**: Redis
- **Frontend**: Next.js (TypeScript recommended)

### Key Features
- ✅ RESTful API architecture
- ✅ JWT-based authentication with refresh tokens
- ✅ Role-based access control (RBAC)
- ✅ Automatic transaction handling
- ✅ Comprehensive error logging
- ✅ History tracking for all data changes
- ✅ Pagination support
- ✅ Advanced filtering and sorting

---

## 🏗️ Architecture

### Layer Structure

```
┌─────────────────────────────────────────┐
│          Next.js Frontend               │
│     (UI Components, State Management)   │
└─────────────────┬───────────────────────┘
                  │ HTTP/HTTPS
                  │ JSON
┌─────────────────▼───────────────────────┐
│         Laravel API Gateway             │
│  ┌─────────────────────────────────┐   │
│  │   Middleware Layer              │   │
│  │  - Authentication (JWT)         │   │
│  │  - Authorization (Permissions)  │   │
│  │  - Transaction Management       │   │
│  │  - Response Formatting          │   │
│  └─────────────────────────────────┘   │
│  ┌─────────────────────────────────┐   │
│  │   Controller Layer              │   │
│  │  - Request Validation           │   │
│  │  - Delegate to Services         │   │
│  └─────────────────────────────────┘   │
│  ┌─────────────────────────────────┐   │
│  │   Service Layer                 │   │
│  │  - Business Logic               │   │
│  │  - History Tracking             │   │
│  │  - Data Transformation          │   │
│  └─────────────────────────────────┘   │
│  ┌─────────────────────────────────┐   │
│  │   Repository Layer              │   │
│  │  - Database Queries             │   │
│  │  - Pagination                   │   │
│  │  - Filtering & Sorting          │   │
│  └─────────────────────────────────┘   │
│  ┌─────────────────────────────────┐   │
│  │   Model Layer                   │   │
│  │  - Eloquent ORM                 │   │
│  │  - Relationships                │   │
│  └─────────────────────────────────┘   │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│         Database (MySQL/PostgreSQL)     │
└─────────────────────────────────────────┘
```

---

## 🔌 API Endpoints Structure

### Base URL
```
Production: https://api.yourapp.com
Development: http://localhost:8000
```

### Endpoint Naming Convention

```
/api/{module}/{action}
```

### Available Modules

#### Master Data (Configuration)
- `/api/admin-mst` - Admin users management
- `/api/role-mst` - Roles management
- `/api/department-mst` - Departments
- `/api/feature-mst` - Features/Permissions
- `/api/api-mst` - API endpoints
- `/api/language-mst` - Languages
- `/api/translation-mst` - Translations
- `/api/token-mst` - Token management
- `/api/policy-department-mst` - Department policies
- `/api/original-translator-mst` - Original translators

#### Management Data (Business)
- `/api/banner-mgmt` - Banners
- `/api/category-mgmt` - Categories
- `/api/skill-mgmt` - Skills
- `/api/skill-description-mgmt` - Skill descriptions
- `/api/slider-mgmt` - Sliders
- `/api/social-mgmt` - Social media links
- `/api/user-mgmt` - End users
- `/api/setting-link-mgmt` - Setting links

#### Junction Tables (Many-to-Many)
- `/api/admin-role-mst` - Admin-Role relationships
- `/api/admin-department-mst` - Admin-Department relationships
- `/api/api-role-mst` - API-Role permissions
- `/api/department-management-mst` - Department-Policy relationships
- `/api/translation-language-mst` - Translation-Language relationships
- `/api/category-skill-mgmt` - Category-Skill relationships

### Standard CRUD Endpoints

Each module typically has 4 endpoints:

```
GET    /api/{module}           - List/Search (with pagination)
POST   /api/{module}           - Create new record
PUT    /api/{module}/{id}      - Update existing record
DELETE /api/{module}           - Delete record(s)
```

---

## 📨 Request/Response Format

### Standard Request Headers

```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer {access_token}
```

### List/Search Request (GET)

```typescript
// Query Parameters
interface ListRequest {
  // Pagination
  page?: number;              // Default: 1
  per_page?: number;          // Default: 15, Max: 100
  
  // Filtering (exact match)
  id?: number;
  status?: number;
  is_active?: boolean;
  is_delete?: boolean;
  
  // Filtering (LIKE search)
  name?: string;
  email?: string;
  
  // Date range
  from_date?: string;         // Format: d/m/Y
  to_date?: string;           // Format: d/m/Y
  
  // Sorting
  sort_by?: string;           // Column name
  sort_order?: 'asc' | 'desc'; // Default: 'asc'
}
```

**Example**:
```bash
GET /api/admin-mst?page=1&per_page=20&status=1&sort_by=created_at&sort_order=desc
```

### Create Request (POST)

```typescript
interface CreateRequest {
  // Entity-specific fields
  [key: string]: any;
  
  // Required for history tracking
  author_id?: number;
}
```

**Example**:
```json
POST /api/admin-mst
{
  "email": "admin@example.com",
  "user_name": "admin",
  "password": "SecurePassword123",
  "first_name": "John",
  "last_name": "Doe",
  "status": 1,
  "is_active": true,
  "is_delete": false,
  "author_id": 1
}
```

### Update Request (PUT)

```typescript
interface UpdateRequest {
  id: number;                 // Required in URL or body
  [key: string]: any;
  author_id?: number;
}
```

**Example**:
```json
PUT /api/admin-mst/1
{
  "first_name": "Jane",
  "last_name": "Smith",
  "status": 2,
  "author_id": 1
}
```

### Delete Request (DELETE)

```typescript
interface DeleteRequest {
  ids: number[];              // Array of IDs to delete
  author_id?: number;
}
```

**Example**:
```json
DELETE /api/admin-mst
{
  "ids": [1, 2, 3],
  "author_id": 1
}
```

### Standard Success Response

```typescript
interface SuccessResponse<T> {
  success: true;
  data: T;
  message?: string;
}
```

**List Response**:
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "email": "admin@example.com",
        "user_name": "admin",
        "first_name": "John",
        "last_name": "Doe",
        "status": 1,
        "is_active": true,
        "updated_at": "20/11/2025"
      }
    ],
    "first_page_url": "http://localhost/api/admin-mst?page=1",
    "from": 1,
    "last_page": 5,
    "last_page_url": "http://localhost/api/admin-mst?page=5",
    "links": [...],
    "next_page_url": "http://localhost/api/admin-mst?page=2",
    "path": "http://localhost/api/admin-mst",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 75
  }
}
```

**Create/Update Response**:
```json
{
  "success": true,
  "data": 1  // ID of created/updated record
}
```

**Delete Response**:
```json
{
  "success": true,
  "data": null
}
```

### Standard Error Response

```typescript
interface ErrorResponse {
  success: false;
  message: string;
  errors?: {
    [field: string]: string[];
  };
  status_code: number;
}
```

**Validation Error (422)**:
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  },
  "status_code": 422
}
```

**Authentication Error (401)**:
```json
{
  "success": false,
  "message": "Unauthorized Access",
  "status_code": 401
}
```

**Not Found Error (404)**:
```json
{
  "success": false,
  "message": "Not Found",
  "status_code": 404
}
```

---

## 🔐 Authentication & Authorization

### Login Flow

```typescript
// 1. Login Request
POST /api/auth/login
{
  "email": "admin@example.com",
  "password": "password123"
}

// 2. Login Response
{
  "success": true,
  "data": {
    "auth_type": "bearer",
    "ttl": 300,  // 5 minutes
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "_cookie": "refresh_token=...; HttpOnly; Secure; SameSite=Strict"
  }
}
```

### Token Management

**Access Token**:
- Lifetime: 5 minutes
- Sent in `Authorization: Bearer {token}` header
- Stored in memory (not localStorage for security)

**Refresh Token**:
- Lifetime: 3 days
- Sent as HttpOnly cookie
- Used to get new access token

### Refresh Token Flow

```typescript
// Request
POST /api/auth/refresh
// Cookie: refresh_token=...

// Response
{
  "success": true,
  "data": {
    "auth_type": "bearer",
    "ttl": 300,
    "access_token": "new_access_token...",
    "_cookie": "refresh_token=new_refresh_token..."
  }
}
```

### Logout

```typescript
POST /api/auth/logout
Authorization: Bearer {access_token}
// Cookie: refresh_token=...

// Response
{
  "success": true,
  "data": {
    "auth_type": "bearer",
    "ttl": 300,
    "access_token": null,
    "_cookie": "refresh_token=; expires=Thu, 01 Jan 1970"
  }
}
```

### Permission-Based Access

The API uses role-based permissions. Each endpoint requires specific permissions:

```typescript
// Example: Admin must have permission to access /api/admin-mst
// Permissions are checked via AdminMiddleware
// Frontend should check user permissions before showing UI elements
```

---

## 📄 Pagination & Filtering

### Pagination Parameters

```typescript
interface PaginationParams {
  page?: number;        // Current page (default: 1)
  per_page?: number;    // Items per page (default: 15, max: 100)
}
```

### Filtering

**Exact Match** (for IDs, status, booleans):
```
?id=1&status=1&is_active=true
```

**LIKE Search** (for text fields):
```
?name=john&email=example
```

**Date Range**:
```
?from_date=01/01/2025&to_date=31/12/2025
```

### Sorting

```
?sort_by=created_at&sort_order=desc
```

**Available sort columns**: Any column in the database table (validated server-side for security)

### Combined Example

```bash
GET /api/admin-mst?page=2&per_page=20&status=1&name=john&sort_by=created_at&sort_order=desc
```

---

## ⚠️ Error Handling

### HTTP Status Codes

| Code | Meaning | When |
|------|---------|------|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Missing or invalid token |
| 403 | Forbidden | No permission for resource |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

### Error Messages

All errors follow this format:

```typescript
interface ErrorResponse {
  success: false;
  message: string;
  errors?: Record<string, string[]>;
  status_code: number;
}
```

### Common Error Scenarios

**1. Validation Errors**:
```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  },
  "status_code": 422
}
```

**2. Authentication Errors**:
```json
{
  "success": false,
  "message": "Unauthorized Access",
  "status_code": 401
}
```

**3. Permission Errors**:
```json
{
  "success": false,
  "message": "Access is forbidden",
  "status_code": 403
}
```

**4. Not Found Errors**:
```json
{
  "success": false,
  "message": "Not Found",
  "status_code": 404
}
```

---

## 📊 Data Models & TypeScript Interfaces

### Master Data Models

#### AdminMst (Admin Users)
```typescript
interface AdminMst {
  id: number;
  email: string;
  user_name: string;
  password?: string;          // Only for create/update
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string;             // Format: d/m/Y
  gender: number;             // 1: Male, 2: Female, 3: Other
  status: number;             // 1: Active, 2: Inactive
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;            // URL to avatar image
  email_verified_at?: string;
  updated_at: string;         // Format: d/m/Y
  created_at?: string;
  
  // Relationships (when included)
  roles?: RoleMst[];
  departments?: DepartmentMst[];
}
```

#### RoleMst (Roles)
```typescript
interface RoleMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  
  // Relationships
  apis?: ApiMst[];
  features?: FeatureMst[];
}
```

#### DepartmentMst (Departments)
```typescript
interface DepartmentMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### FeatureMst (Features/Permissions)
```typescript
interface FeatureMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### ApiMst (API Endpoints)
```typescript
interface ApiMst {
  id: number;
  uri: string;                // e.g., "/api/admin-mst"
  method: string;             // GET, POST, PUT, DELETE
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### LanguageMst (Languages)
```typescript
interface LanguageMst {
  id: number;
  code: string;               // e.g., "en", "vi", "ja"
  name: string;               // e.g., "English", "Tiếng Việt"
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### TranslationMst (Translations)
```typescript
interface TranslationMst {
  id: number;
  key: string;                // Translation key
  original_text: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  
  // Relationships
  languages?: LanguageMst[];
  translations?: TranslationLanguageMst[];
}
```

### Management Data Models

#### BannerMgmt (Banners)
```typescript
interface BannerMgmt {
  id: number;
  title: string;
  description?: string;
  image_url: string;
  link_url?: string;
  rank_order: number;         // Display order
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### CategoryMgmt (Categories)
```typescript
interface CategoryMgmt {
  id: number;
  name: string;
  description?: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  
  // Relationships
  skills?: SkillMgmt[];
}
```

#### SkillMgmt (Skills)
```typescript
interface SkillMgmt {
  id: number;
  name: string;
  description?: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  
  // Relationships
  skill_descriptions?: SkillDescriptionMgmt[];
  categories?: CategoryMgmt[];
}
```

#### SkillDescriptionMgmt (Skill Descriptions)
```typescript
interface SkillDescriptionMgmt {
  id: number;
  skill_mgmt_id: number;
  description: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### SliderMgmt (Sliders)
```typescript
interface SliderMgmt {
  id: number;
  title: string;
  description?: string;
  image_url: string;
  link_url?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### SocialMgmt (Social Media Links)
```typescript
interface SocialMgmt {
  id: number;
  platform: string;           // e.g., "facebook", "twitter", "linkedin"
  url: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}
```

#### UserMgmt (End Users)
```typescript
interface UserMgmt {
  id: number;
  email: string;
  user_name: string;
  password?: string;
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string;
  gender: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;
  updated_at: string;
}
```

### Junction Table Models

#### AdminRoleMst (Admin-Role Relationship)
```typescript
interface AdminRoleMst {
  admin_mst_id: number;
  role_mst_id: number;
}

// Update request format
interface UpdateAdminRoleMstRequest {
  admin_mst_id: number;
  delete?: Array<{ admin_mst_id: number; role_mst_id: number }>;
  insert?: Array<{ admin_mst_id: number; role_mst_id: number }>;
}
```

#### CategorySkillMgmt (Category-Skill Relationship)
```typescript
interface CategorySkillMgmt {
  category_mgmt_id: number;
  skill_mgmt_id: number;
}

// Update request format
interface UpdateCategorySkillMgmtRequest {
  category_mgmt_id: number;
  delete?: Array<{ category_mgmt_id: number; skill_mgmt_id: number }>;
  insert?: Array<{ category_mgmt_id: number; skill_mgmt_id: number }>;
}
```

### Common Enums

```typescript
// Status
enum Status {
  ACTIVE = 1,
  INACTIVE = 2,
}

// Gender
enum Gender {
  MALE = 1,
  FEMALE = 2,
  OTHER = 3,
}

// Action Type (for history)
enum ActionType {
  CREATE = 'create',
  UPDATE = 'update',
  DELETE = 'delete',
}
```

---

## ✅ Validation Rules

### AdminMst Validation

#### Create (POST)
```typescript
interface CreateAdminMstValidation {
  email: string;              // Required, email format, unique
  user_name: string;          // Required, min:3, max:50, unique
  password: string;           // Required, min:8, max:255
  first_name: string;         // Required, max:50
  last_name: string;          // Required, max:50
  address?: string;           // Optional, max:255
  phone_number?: string;      // Optional, phone format
  birth?: string;             // Optional, date format (d/m/Y)
  gender: number;             // Required, in:[1,2,3]
  status: number;             // Required, in:[1,2]
  is_active: boolean;         // Required
  is_delete: boolean;         // Required
  avatar?: string;            // Optional, URL format
  author_id?: number;         // Optional, exists in admin_mst
}
```

#### Update (PUT)
```typescript
interface UpdateAdminMstValidation {
  id: number;                 // Required, exists in admin_mst
  email?: string;             // Optional, email format, unique (except self)
  user_name?: string;         // Optional, min:3, max:50, unique (except self)
  password?: string;          // Optional, min:8, max:255
  first_name?: string;        // Optional, max:50
  last_name?: string;         // Optional, max:50
  address?: string;           // Optional, max:255
  phone_number?: string;      // Optional, phone format
  birth?: string;             // Optional, date format (d/m/Y)
  gender?: number;            // Optional, in:[1,2,3]
  status?: number;            // Optional, in:[1,2]
  is_active?: boolean;        // Optional
  is_delete?: boolean;        // Optional
  avatar?: string;            // Optional, URL format
  author_id?: number;         // Optional, exists in admin_mst
}
```

#### Delete (DELETE)
```typescript
interface DeleteAdminMstValidation {
  ids: number[];              // Required, array, each exists in admin_mst
  author_id?: number;         // Optional, exists in admin_mst
}
```

### Common Validation Patterns

**Email**: RFC 5322 compliant  
**Phone**: International format or local format  
**Date**: `d/m/Y` format (e.g., "20/11/2025")  
**URL**: Valid HTTP/HTTPS URL  
**Boolean**: `true`, `false`, `1`, `0`, `"true"`, `"false"`  

### Validation Error Response

```json
{
  "success": false,
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required.",
      "The email must be a valid email address."
    ],
    "password": [
      "The password must be at least 8 characters."
    ]
  },
  "status_code": 422
}
```

---

## 📖 Complete API Reference

### Master Data APIs

#### Admin Management (`/api/admin-mst`)

**List Admins**
```http
GET /api/admin-mst
Query Parameters:
  - id: number
  - email: string (LIKE search)
  - user_name: string (LIKE search)
  - first_name: string (LIKE search)
  - last_name: string (LIKE search)
  - status: number (1 or 2)
  - is_active: boolean
  - is_delete: boolean
  - gender: number (1, 2, or 3)
  - from_date: string (d/m/Y)
  - to_date: string (d/m/Y)
  - page: number
  - per_page: number
  - sort_by: string
  - sort_order: 'asc' | 'desc'
```

**Create Admin**
```http
POST /api/admin-mst
Body: CreateAdminMstValidation (see Validation Rules)
```

**Update Admin**
```http
PUT /api/admin-mst/{id}
Body: UpdateAdminMstValidation (see Validation Rules)
```

**Delete Admin(s)**
```http
DELETE /api/admin-mst
Body: { ids: number[], author_id?: number }
```

#### Role Management (`/api/role-mst`)

**List Roles**
```http
GET /api/role-mst
Query Parameters:
  - id, name, description, status, is_active, is_delete
  - page, per_page, sort_by, sort_order
```

**Create/Update/Delete**: Same pattern as Admin Management

#### Department Management (`/api/department-mst`)

Same CRUD pattern as above.

### Management Data APIs

#### Banner Management (`/api/banner-mgmt`)

**List Banners**
```http
GET /api/banner-mgmt
Query Parameters:
  - id, title, description, status, is_active, is_delete, rank_order
  - page, per_page, sort_by, sort_order
```

**Create Banner**
```http
POST /api/banner-mgmt
Body:
{
  "title": "Summer Sale",
  "description": "Up to 50% off",
  "image_url": "https://example.com/banner.jpg",
  "link_url": "https://example.com/sale",
  "rank_order": 1,
  "status": 1,
  "is_active": true,
  "is_delete": false,
  "author_id": 1
}
```

#### Category Management (`/api/category-mgmt`)
#### Skill Management (`/api/skill-mgmt`)
#### Slider Management (`/api/slider-mgmt`)
#### Social Management (`/api/social-mgmt`)
#### User Management (`/api/user-mgmt`)

All follow the same CRUD pattern.

### Junction Table APIs

#### Admin-Role Assignment (`/api/admin-role-mst`)

**List Assignments**
```http
GET /api/admin-role-mst
Query Parameters:
  - admin_mst_id: number
  - role_mst_id: number
  - page, per_page
```

**Update Assignments**
```http
PUT /api/admin-role-mst
Body:
{
  "admin_mst_id": 1,
  "delete": [
    { "admin_mst_id": 1, "role_mst_id": 2 }
  ],
  "insert": [
    { "admin_mst_id": 1, "role_mst_id": 3 }
  ]
}
```

**Note**: Junction tables don't have traditional create/delete endpoints. Use the update endpoint with `insert` and `delete` arrays.

---

## 📁 File Upload & Media Handling

### Upload Endpoint

```http
POST /api/upload
Content-Type: multipart/form-data

Body:
  - file: File (required)
  - type: string (optional: 'avatar', 'banner', 'slider', 'icon')
```

### Upload Response

```json
{
  "success": true,
  "data": {
    "url": "https://example.com/storage/uploads/2025/11/20/image.jpg",
    "filename": "image.jpg",
    "size": 102400,
    "mime_type": "image/jpeg"
  }
}
```

### File Upload Example (Next.js)

```typescript
// utils/upload.ts
import { apiClient } from '@/lib/api-client';

export async function uploadFile(file: File, type?: string) {
  const formData = new FormData();
  formData.append('file', file);
  if (type) formData.append('type', type);

  const response = await apiClient.post<{ url: string }>('/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  });

  return response.data.url;
}

// Component usage
const handleFileUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
  const file = e.target.files?.[0];
  if (!file) return;

  try {
    const url = await uploadFile(file, 'avatar');
    setFormData({ ...formData, avatar: url });
    toast.success('File uploaded successfully');
  } catch (error) {
    toast.error('Failed to upload file');
  }
};
```

### Supported File Types

- **Images**: jpg, jpeg, png, gif, webp (max: 5MB)
- **Documents**: pdf, doc, docx (max: 10MB)
- **Videos**: mp4, webm (max: 50MB)

---

## 💡 Best Practices for Frontend Integration

### 1. API Client Setup (Next.js)

```typescript
// lib/api-client.ts
import axios, { AxiosInstance, AxiosError } from 'axios';

class ApiClient {
  private client: AxiosInstance;
  private accessToken: string | null = null;

  constructor() {
    this.client = axios.create({
      baseURL: process.env.NEXT_PUBLIC_API_URL,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      withCredentials: true, // Important for refresh token cookie
    });

    // Request interceptor
    this.client.interceptors.request.use(
      (config) => {
        if (this.accessToken) {
          config.headers.Authorization = `Bearer ${this.accessToken}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    // Response interceptor
    this.client.interceptors.response.use(
      (response) => response,
      async (error: AxiosError) => {
        const originalRequest = error.config;

        // If 401 and not already retrying, try to refresh token
        if (error.response?.status === 401 && !originalRequest._retry) {
          originalRequest._retry = true;

          try {
            const { data } = await this.client.post('/auth/refresh');
            this.setAccessToken(data.data.access_token);
            return this.client(originalRequest);
          } catch (refreshError) {
            // Refresh failed, redirect to login
            window.location.href = '/login';
            return Promise.reject(refreshError);
          }
        }

        return Promise.reject(error);
      }
    );
  }

  setAccessToken(token: string) {
    this.accessToken = token;
  }

  clearAccessToken() {
    this.accessToken = null;
  }

  // Generic methods
  async get<T>(url: string, params?: any) {
    const response = await this.client.get<ApiResponse<T>>(url, { params });
    return response.data;
  }

  async post<T>(url: string, data?: any) {
    const response = await this.client.post<ApiResponse<T>>(url, data);
    return response.data;
  }

  async put<T>(url: string, data?: any) {
    const response = await this.client.put<ApiResponse<T>>(url, data);
    return response.data;
  }

  async delete<T>(url: string, data?: any) {
    const response = await this.client.delete<ApiResponse<T>>(url, { data });
    return response.data;
  }
}

export const apiClient = new ApiClient();

// Types
interface ApiResponse<T> {
  success: boolean;
  data: T;
  message?: string;
}
```

### 2. Authentication Hook

```typescript
// hooks/useAuth.ts
import { create } from 'zustand';
import { apiClient } from '@/lib/api-client';

interface AuthState {
  user: User | null;
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  refreshToken: () => Promise<void>;
}

export const useAuth = create<AuthState>((set) => ({
  user: null,
  isAuthenticated: false,

  login: async (email, password) => {
    const response = await apiClient.post('/auth/login', { email, password });
    apiClient.setAccessToken(response.data.access_token);
    
    // Fetch user profile
    const userResponse = await apiClient.get('/auth/me');
    set({ user: userResponse.data, isAuthenticated: true });
  },

  logout: async () => {
    await apiClient.post('/auth/logout');
    apiClient.clearAccessToken();
    set({ user: null, isAuthenticated: false });
  },

  refreshToken: async () => {
    const response = await apiClient.post('/auth/refresh');
    apiClient.setAccessToken(response.data.access_token);
  },
}));
```

### 3. Data Fetching Hook

```typescript
// hooks/useApiData.ts
import { useState, useEffect } from 'react';
import { apiClient } from '@/lib/api-client';

interface UseApiDataOptions {
  page?: number;
  perPage?: number;
  filters?: Record<string, any>;
  sortBy?: string;
  sortOrder?: 'asc' | 'desc';
}

export function useApiData<T>(
  endpoint: string,
  options: UseApiDataOptions = {}
) {
  const [data, setData] = useState<T[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<Error | null>(null);
  const [pagination, setPagination] = useState({
    currentPage: 1,
    lastPage: 1,
    total: 0,
    perPage: 15,
  });

  const fetchData = async () => {
    try {
      setLoading(true);
      const response = await apiClient.get(endpoint, {
        page: options.page || 1,
        per_page: options.perPage || 15,
        ...options.filters,
        sort_by: options.sortBy,
        sort_order: options.sortOrder,
      });

      setData(response.data.data);
      setPagination({
        currentPage: response.data.current_page,
        lastPage: response.data.last_page,
        total: response.data.total,
        perPage: response.data.per_page,
      });
    } catch (err) {
      setError(err as Error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [endpoint, options.page, options.perPage, JSON.stringify(options.filters)]);

  return { data, loading, error, pagination, refetch: fetchData };
}
```

### 4. CRUD Operations Hook

```typescript
// hooks/useCrud.ts
import { useState } from 'react';
import { apiClient } from '@/lib/api-client';
import { toast } from 'react-hot-toast';

export function useCrud<T>(endpoint: string) {
  const [loading, setLoading] = useState(false);

  const create = async (data: Partial<T>) => {
    try {
      setLoading(true);
      const response = await apiClient.post(endpoint, data);
      toast.success('Created successfully');
      return response.data;
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to create');
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const update = async (id: number, data: Partial<T>) => {
    try {
      setLoading(true);
      const response = await apiClient.put(`${endpoint}/${id}`, { id, ...data });
      toast.success('Updated successfully');
      return response.data;
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to update');
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const remove = async (ids: number[]) => {
    try {
      setLoading(true);
      await apiClient.delete(endpoint, { ids });
      toast.success('Deleted successfully');
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to delete');
      throw error;
    } finally {
      setLoading(false);
    }
  };

  return { create, update, remove, loading };
}
```

### 5. Usage Example in Component

```typescript
// app/admin/page.tsx
'use client';

import { useState } from 'react';
import { useApiData } from '@/hooks/useApiData';
import { useCrud } from '@/hooks/useCrud';

interface Admin {
  id: number;
  email: string;
  user_name: string;
  first_name: string;
  last_name: string;
  status: number;
  is_active: boolean;
}

export default function AdminPage() {
  const [page, setPage] = useState(1);
  const [filters, setFilters] = useState({});
  
  const { data, loading, pagination, refetch } = useApiData<Admin>(
    '/admin-mst',
    { page, perPage: 20, filters }
  );
  
  const { create, update, remove } = useCrud<Admin>('/admin-mst');

  const handleCreate = async (formData: Partial<Admin>) => {
    await create(formData);
    refetch();
  };

  const handleUpdate = async (id: number, formData: Partial<Admin>) => {
    await update(id, formData);
    refetch();
  };

  const handleDelete = async (ids: number[]) => {
    await remove(ids);
    refetch();
  };

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      <h1>Admin Management</h1>
      
      {/* Table */}
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {data.map((admin) => (
            <tr key={admin.id}>
              <td>{admin.id}</td>
              <td>{admin.email}</td>
              <td>{admin.first_name} {admin.last_name}</td>
              <td>{admin.is_active ? 'Active' : 'Inactive'}</td>
              <td>
                <button onClick={() => handleUpdate(admin.id, { status: 2 })}>
                  Edit
                </button>
                <button onClick={() => handleDelete([admin.id])}>
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Pagination */}
      <div>
        <button 
          disabled={page === 1}
          onClick={() => setPage(page - 1)}
        >
          Previous
        </button>
        <span>Page {pagination.currentPage} of {pagination.lastPage}</span>
        <button 
          disabled={page === pagination.lastPage}
          onClick={() => setPage(page + 1)}
        >
          Next
        </button>
      </div>
    </div>
  );
}
```

---

## 📝 Code Examples

### Complete CRUD Example

```typescript
// services/admin.service.ts
import { apiClient } from '@/lib/api-client';

export interface Admin {
  id: number;
  email: string;
  user_name: string;
  password?: string;
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string;
  gender: number;
  status: number;
  is_active: boolean;
  avatar?: string;
  updated_at: string;
}

export interface AdminListParams {
  page?: number;
  per_page?: number;
  email?: string;
  user_name?: string;
  status?: number;
  is_active?: boolean;
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
}

export const adminService = {
  // List with pagination and filters
  async list(params: AdminListParams) {
    return apiClient.get<PaginatedResponse<Admin>>('/admin-mst', params);
  },

  // Create new admin
  async create(data: Omit<Admin, 'id' | 'updated_at'>) {
    return apiClient.post<number>('/admin-mst', data);
  },

  // Update existing admin
  async update(id: number, data: Partial<Admin>) {
    return apiClient.put<number>(`/admin-mst/${id}`, { id, ...data });
  },

  // Delete admin(s)
  async delete(ids: number[]) {
    return apiClient.delete('/admin-mst', { ids });
  },

  // Get single admin
  async getById(id: number) {
    const response = await this.list({ id });
    return response.data.data[0];
  },
};

// Types
interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}
```

---

## 🔧 Environment Variables

```env
# .env.local (Next.js)
NEXT_PUBLIC_API_URL=http://localhost:8000/api
NEXT_PUBLIC_APP_URL=http://localhost:3000
```

---

## 📚 Additional Resources

### API Testing
- Use Postman/Insomnia for API testing
- Import collection from `/api/documentation` (if available)

### TypeScript Types
Generate TypeScript types from Laravel models using tools like:
- `laravel-typescript`
- `openapi-typescript`

### State Management Recommendations
- **Zustand**: Lightweight, simple
- **React Query**: Server state management, caching
- **Redux Toolkit**: Complex state management

---

## 🎯 Quick Start Checklist

- [ ] Setup API client with interceptors
- [ ] Implement authentication flow
- [ ] Create reusable hooks (useApiData, useCrud)
- [ ] Setup error handling and toast notifications
- [ ] Implement pagination component
- [ ] Create form validation (use react-hook-form + zod)
- [ ] Setup loading states
- [ ] Implement permission-based UI rendering
- [ ] Add request/response logging (development only)
- [ ] Setup environment variables

---

## 📞 Support

For API issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Enable debug mode in development
- Review middleware behavior
- Check database queries with Laravel Debugbar

---

**Last Updated**: November 20, 2025  
**API Version**: 1.0  
**Laravel Version**: 10+  
**Status**: Production Ready ✅
