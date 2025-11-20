# Remaining Implementation Guide

## Overview
This guide provides templates and instructions for completing the remaining 42 UI pages and 6 junction tables.

---

## 📋 Remaining UI Pages (14 modules × 3 pages = 42 pages)

### Pattern to Follow
Each module needs 3 pages following the exact same pattern as Admin/Role/Department/Feature:
1. **List Page** - `src/app/admin/{module}/page.tsx`
2. **Create Page** - `src/app/admin/{module}/create/page.tsx`
3. **Edit Page** - `src/app/admin/{module}/[id]/edit/page.tsx`

---

## 🔧 Quick Implementation Template

### For Simple Modules (Name + Description + Status)
Use Role/Department/Feature as template - they have:
- Name field (required)
- Description field (optional)
- Status dropdown
- Is Active checkbox

**Modules that fit this pattern:**
- API Management
- Language Management
- Policy Department Management
- Original Translator Management
- Category Management
- Skill Management
- Social Management

### For Complex Modules
**Translation Management** - Add:
- Key field (required)
- Value field (required)

**Token Management** - Add:
- Token field (required)
- Admin dropdown (foreign key)

**Banner/Slider Management** - Add:
- Title, Link, Image Upload
- Order number

**Skill Description Management** - Add:
- Skill dropdown (foreign key)
- Description (rich text)

**User Management** - Similar to Admin but simpler

**Setting Link Management** - Add:
- Key, Value, Type fields

---

## 📝 Step-by-Step for Each Module

### 1. Copy Template Files
```bash
# For each new module, copy from Feature module:
cp -r src/app/admin/features src/app/admin/{new-module}
```

### 2. Update Imports
Replace all occurrences:
- `FeatureMst` → `{YourModule}Mst`
- `ENDPOINTS.MASTER.FEATURE` → `ENDPOINTS.MASTER.{YOUR_MODULE}`
- `featureService` → `{yourModule}Service`
- `/admin/features` → `/admin/{your-module}`

### 3. Update Form Schema
Modify the Zod schema based on module fields:
```typescript
const schema = z.object({
  name: z.string().min(1, 'Name is required'),
  // Add your specific fields here
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});
```

### 4. Update Form Fields
Add/remove form fields in create/edit pages based on your schema.

### 5. Update Table Columns
Modify columns array in list page to show relevant fields.

---

## 🔗 Junction Table Implementation

### Pattern for Junction Tables
Junction tables manage many-to-many relationships. Use `useJunctionTable` hook.

### Example: Admin-Role Junction
```typescript
// In Admin edit page, add a tab for roles
const {
  allItems,      // All available roles
  selectedIds,   // Currently assigned role IDs
  toggleSelection,
  save,
  loading
} = useJunctionTable(
  '/admin-role-mst',    // Junction endpoint
  '/role-mst',          // All items endpoint
  'admin_mst_id',       // Parent ID key
  'role_mst_id',        // Child ID key
  adminId               // Parent ID value
);

// Render checkboxes for each role
{allItems.map(role => (
  <label key={role.id}>
    <input
      type="checkbox"
      checked={selectedIds.includes(role.id)}
      onChange={() => toggleSelection(role.id)}
    />
    {role.name}
  </label>
))}

<Button onClick={save}>Save Roles</Button>
```

### All Junction Tables to Implement:
1. **Admin-Role** - In Admin edit page
2. **Admin-Department** - In Admin edit page
3. **API-Role** - In API edit page
4. **Department-Management** - In Department edit page
5. **Translation-Language** - In Translation edit page
6. **Category-Skill** - In Category edit page

---

## 🎯 Priority Order

### High Priority (Core functionality)
1. ✅ Admin Management - DONE
2. ✅ Role Management - DONE
3. ✅ Department Management - DONE
4. ✅ Feature Management - DONE
5. ⏳ API Management
6. ⏳ Language Management
7. ⏳ Translation Management

### Medium Priority (Supporting features)
8. ⏳ Token Management
9. ⏳ User Management
10. ⏳ Category Management
11. ⏳ Skill Management

### Lower Priority (Content management)
12. ⏳ Banner Management (needs image upload)
13. ⏳ Slider Management (needs image upload)
14. ⏳ Social Management
15. ⏳ Skill Description Management
16. ⏳ Setting Link Management
17. ⏳ Policy Department Management
18. ⏳ Original Translator Management

---

## 📦 File Upload Implementation

For Banner and Slider modules that need image upload:

```typescript
// Add to form
<div className="space-y-2">
  <Label htmlFor="image">Image</Label>
  <Input
    id="image"
    type="file"
    accept="image/*"
    onChange={handleImageUpload}
  />
  {imagePreview && (
    <img src={imagePreview} alt="Preview" className="mt-2 max-w-xs" />
  )}
</div>

// Handle upload
const handleImageUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
  const file = e.target.files?.[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('file', file);

  const response = await apiClient.post('/upload', formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
  });

  setImageUrl(response.data.url);
};
```

---

## ✅ Checklist for Each Module

- [ ] Create service file (if not exists)
- [ ] Create list page with DataTable
- [ ] Add filter fields
- [ ] Add table columns
- [ ] Create create page with form
- [ ] Add form validation schema
- [ ] Add all form fields
- [ ] Create edit page
- [ ] Load existing data
- [ ] Add junction table management (if applicable)
- [ ] Update navigation menu
- [ ] Test CRUD operations
- [ ] Test filtering and sorting
- [ ] Test pagination

---

## 🚀 Estimated Completion Time

- **Simple modules** (7 modules): ~2 hours
- **Complex modules** (7 modules): ~2.5 hours
- **Junction tables** (6 tables): ~1 hour
- **Testing & fixes**: ~1 hour

**Total: ~6.5 hours**

---

## 📚 Reference Files

**Best Templates:**
- Simple CRUD: `src/app/admin/roles/`
- Complex form: `src/app/admin/admins/`
- Service: `src/services/role.service.ts`

**Shared Components:**
- DataTable: `src/components/data-table/data-table.tsx`
- Pagination: `src/components/data-table/pagination.tsx`
- FilterPanel: `src/components/data-table/filter-panel.tsx`

**Hooks:**
- useApiData: `src/hooks/useApiData.ts`
- useCrud: `src/hooks/useCrud.ts`
- useJunctionTable: `src/hooks/useJunctionTable.ts`

---

**Note:** All services are already created. You only need to create UI pages following the templates!
