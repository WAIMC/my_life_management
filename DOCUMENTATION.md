# 📖 Hệ Thống Quản Lý Tài Liệu - Tài Liệu Toàn Diện

> **Mục đích**: Tài liệu một lần duy nhất - gom toàn bộ kiến thức về cấu trúc dữ liệu, format JSON, quy tắc nghiệp vụ và hướng dẫn nhập liệu cho AI.

---

## 📋 Mục Lục

- [1. Kiến Trúc Hệ Thống](#1-kiến-trúc-hệ-thống)
- [2. Layout Structure JSON](#2-layout-structure-json)
- [3. Template & Công Thức](#3-template--công-thức)
- [4. Quy Tắc Validation](#4-quy-tắc-validation)
- [5. AI Workflow - Nhập Liệu](#5-ai-workflow---nhập-liệu)
- [6. Database Connection & Queries](#6-database-connection--queries)
- [7. Best Practices](#7-best-practices)
- [8. Tính Năng Tương Lai (Note)](#8-tính-năng-tương-lai-note)

---

## 1. Kiến Trúc Hệ Thống

### Sơ Đồ Kiến Trúc

```
Category (Danh mục)
  ↓ layout_structure (JSON)
Entry (Đầu mục / Menu)
  ↓ layout_structure (JSON, optional)
Entry Description (Nội dung)
  ↓ article (Tiptap JSON)
```

### 3 Thành Phần Chính

| Thành phần | Bảng | Vai trò |
|:---|:---|:---|
| **Category** | `category_mgmt` | Danh mục, quản lý Entry qua `layout_structure` |
| **Entry** | `entry_mgmt` | Menu/Tiêu đề, tùy chọn quản lý Entry Description qua `layout_structure` |
| **Entry Description** | `entry_description_mgmt` | Nội dung chi tiết (article = Tiptap JSON), KHÔNG dùng `layout_structure` |

### Đặc Điểm Kiến Trúc

- **No Foreign Key**: Dùng JSON `layout_structure` thay vì Foreign Key truyền thống
  - ⚠️ **Category PHẢI có `layout_structure`** để liên kết với Entry (không có FK)
  - ⚠️ **Entry PHẢI có `layout_structure`** để liên kết với Entry Description (không có FK)
  - Entry Description KHÔNG có `layout_structure` (cột không tồn tại)
- **Many-to-Many**: 1 Entry có thể xuất hiện ở nhiều Category với cấu trúc khác nhau
- **Denormalization**: Duplicate `name`, `slug`, `title` trong JSON để tránh JOIN
- **Flexible Structure**: Hỗ trợ cả cấu trúc phẳng (flat) và phân cấp (nested)

---

## 2. Layout Structure JSON

### Quy Tắc Core (QUAN TRỌNG ⭐)

| Quy tắc | Chi tiết |
|:---|:---|
| **Default** | `NULL` - Entry/Category chưa được setup |
| **Xóa hết dữ liệu** | `[]` (empty array) |
| **Node có con** | CÓ key `"children"` (mảng) |
| **Node lá (leaf)** | KHÔNG có key `"children"` |
| **Max nested depth** | 5 cấp (root → lv1 → lv2 → lv3 → lv4 → lv5) |

### Quy Tắc Field

- **`ui_id`**: UUID duy nhất, phục vụ UI state, independent DB ID
- **`entry_mgmt_id`**: Reference tới entry_mgmt.id (dùng trong Category → Entry)
- **`entry_desc_id`**: Reference tới entry_description_mgmt.id (dùng trong Entry → Entry Description)
  - ⚠️ **CHÚ Ý**: Frontend dùng tên ngắn `entry_desc_id`, KHÔNG phải `entry_description_mgmt_id`
- **`name` / `slug`** (Category → Entry): Duplicate từ bảng gốc entry_mgmt
- **`title`** (Entry → Entry Description): Duplicate từ bảng gốc entry_description_mgmt

---

## 3. Template & Công Thức

### Template: Category → Entry

```json
[
  {
    "ui_id": "UUID",
    "entry_mgmt_id": 123,
    "name": "Entry Name",
    "slug": "entry-slug",
    "children": [...]  // CHỈ thêm khi node thực sự có con
  }
]
```

**Trường hợp sử dụng**:
- Category lưu danh sách Entry (cấu trúc chính)
- Nếu không có con → KHÔNG thêm key `"children"`
- Nếu có con → `"children"` là mảng chứa các node con

**⚠️ LƯU Ý QUAN TRỌNG**: Vì không có foreign key trong database, `layout_structure` là cách DUY NHẤT để liên kết Category → Entry!

---

### Template: Entry → Entry Description

```json
[
  {
    "ui_id": "UUID",
    "entry_desc_id": 456,
    "title": "Description Title",
    "children": [...]  // CHỈ thêm khi node thực sự có con
  }
]
```

**⚠️ LƯU Ý QUAN TRỌNG**: 
- Entry PHẢI có `layout_structure` để liên kết với Entry Description(s)
- Ngay cả khi Entry chỉ quản lý 1 description, vẫn PHẢI có `layout_structure` (mảng 1 phần tử)
- Nếu Entry có `layout_structure = NULL`, sẽ KHÔNG biết Entry đó thuộc Entry Description nào!
- Cấu trúc tương tự Category, nhưng thay `name`/`slug` → `title`
- **Field name**: Dùng `entry_desc_id`, KHÔNG phải `entry_description_mgmt_id` (frontend convention)

**Ví dụ Entry có 1 description**:
```json
[
  {
    "ui_id": "UUID",
    "entry_desc_id": 456,
    "title": "My Single Description"
  }
]
```

**Ví dụ Entry có nhiều descriptions**:

---

### Cấu Trúc Flat (Danh Sách Phẳng)

**Khi nào sử dụng**: Các items cùng cấp, không có phân cấp

```json
[
  {
    "ui_id": "UUID",
    "entry_mgmt_id": 1,
    "name": "Item 1",
    "slug": "item-1"
  },
  {
    "ui_id": "UUID",
    "entry_mgmt_id": 2,
    "name": "Item 2",
    "slug": "item-2"
  }
]
```

**Đặc điểm**:
- ✅ Không có key `"children"`
- ✅ Mỗi object là một node lá độc lập
- ✅ UI hiển thị danh sách đơn giản

---

### Cấu Trúc Nested (Phân Cấp)

**Khi nào sử dụng**: Items có quan hệ cha-con

```json
[
  {
    "ui_id": "UUID",
    "entry_mgmt_id": 1,
    "name": "Parent",
    "slug": "parent",
    "children": [
      {
        "ui_id": "UUID",
        "entry_mgmt_id": 2,
        "name": "Child 1",
        "slug": "child-1",
        "children": [
          {
            "ui_id": "UUID",
            "entry_mgmt_id": 3,
            "name": "Grandchild",
            "slug": "grandchild"
          }
        ]
      },
      {
        "ui_id": "UUID",
        "entry_mgmt_id": 4,
        "name": "Child 2",
        "slug": "child-2"
      }
    ]
  }
]
```

**Đặc điểm**:
- ✅ Node cha CÓ key `"children"` (mảng)
- ✅ Node lá KHÔNG có key `"children"`
- ✅ Cấu trúc lồng tối đa 5 cấp
- ✅ UI hiển thị menu phân cấp

---

### ⚠️ QUAN TRỌNG: Entry LUÔN cần layout_structure

**Lưu ý**: Vì database KHÔNG có foreign key giữa `entry_mgmt` và `entry_description_mgmt`, quan hệ giữa chúng được quản lý HOÀN TOÀN qua JSON `layout_structure`.

→ **Entry PHẢI có `layout_structure`** để liên kết với Entry Description(s), bất kể có 1 hay nhiều descriptions.

→ Nếu Entry không có `layout_structure = NULL`, sẽ KHÔNG biết Entry đó thuộc Entry Description nào!

---

### Entry Description Content (Tiptap JSON)

Nội dung của Entry Description lưu trong cột `article` dạng Tiptap JSON:

```json
{
  "type": "doc",
  "content": [
    {
      "type": "heading",
      "attrs": {"level": 2},
      "content": [{"type": "text", "text": "My Title"}]
    },
    {
      "type": "paragraph",
      "content": [{"type": "text", "text": "My content here"}]
    },
    {
      "type": "bulletList",
      "content": [
        {
          "type": "listItem",
          "content": [
            {"type": "paragraph", "content": [{"type": "text", "text": "Item 1"}]}
          ]
        }
      ]
    }
  ]
}
```

**Quy tắc**:
- Entry Description KHÔNG có `layout_structure` (luôn NULL)
- Nội dung lưu trong `article` (Tiptap JSON format)

---

### ⚠️ QUAN TRỌNG: API Response Format cho Article

**Database Storage**:
- Column type: `json` (PostgreSQL native JSON type)
- Data lưu trữ: JSON object (Tiptap format)

**Laravel Model**:
- Cast: `'article' => 'array'` - Laravel tự động decode JSON thành array khi fetch từ DB
- Khi truy cập `$model->article`, nhận được PHP array, KHÔNG phải string

**API Resource (EntryDescriptionMgmtResource.php)**:
```php
'article' => json_encode($this->article, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
```
- PHẢI convert array → JSON string trước khi trả về API
- Frontend expect `article` là string chứa JSON, KHÔNG phải object

**Frontend Handling**:
- TypeScript type: `article?: string`
- Form code: `JSON.parse(initialData.article as string)` để convert string → object
- Editor: Nhận Tiptap JSON object, lưu: `JSON.stringify(content)`

**Lưu ý**:
- ❌ **SAI**: API trả về `article: {...}` (object)
- ✅ **ĐÚNG**: API trả về `article: "{\"type\":\"doc\",...}"` (JSON string)

---

## 4. Quy Tắc Validation

### Checklist trước khi insert vào DB

```
[ ] layout_structure format hợp lệ JSON hoặc NULL
[ ] Tất cả ui_id là UUID valid & unique
[ ] Tất cả *_mgmt_id tồn tại trong bảng gốc
[ ] Duplicate fields (name/slug/title) khớp data gốc
[ ] Depth ≤ 5 cấp
[ ] Leaf nodes KHÔNG có key "children"
[ ] Parent nodes CÓ key "children"
[ ] Node không có con: KHÔNG thêm "children"
[ ] Entry Description: layout_structure luôn NULL
[ ] Article content: Tiptap JSON format hợp lệ
```

### Lỗi Phổ Biến & Fix

| Lỗi | Nguyên nhân | Fix |
|:---|:---|:---|
| `"children": []` ở leaf node | Chưa bỏ key | Xóa key hoàn toàn |
| Depth > 5 | Lồng quá sâu | Re-structure data |
| UUID trùng | Random generation | Dùng `crypto.randomUUID()` |
| name/slug/title sai | Data mismatch | Sync từ bảng gốc |
| Entry Description có `layout_structure` | Sai quy tắc | Bỏ, để NULL |

---

## 5. AI Workflow - Nhập Liệu

### Bước 1: Kết Nối Database

```bash
docker exec -it ml-postgres psql -U ml_pg_user -d ml_pg_db
```

**Connection Info**:
- User: `ml_pg_user`
- Database: `ml_pg_db`
- Host: localhost (via docker)

### Bước 2: Phân Tích Tài Liệu Input

Khi nhận tài liệu từ người dùng:

```
Tài liệu Input
  ↓
Xác định Category (danh mục)
  ↓
Xác định Entry (tiêu đề/menu)
  ↓
Xác định Entry Description (nội dung chi tiết)
  ↓
Map cấu trúc: flat hay nested?
  ↓
Xác định depth level (tối đa 5 cấp)
```

### Bước 3: Convert Content → Tiptap JSON

**Input** (Markdown):
```markdown
## Prerequisites
- Node.js v18+
- Docker
```

**Output** (Tiptap JSON):
```json
{
  "type": "doc",
  "content": [
    {
      "type": "heading",
      "attrs": {"level": 2},
      "content": [{"type": "text", "text": "Prerequisites"}]
    },
    {
      "type": "bulletList",
      "content": [
        {
          "type": "listItem",
          "content": [
            {"type": "paragraph", "content": [{"type": "text", "text": "Node.js v18+"}]}
          ]
        },
        {
          "type": "listItem",
          "content": [
            {"type": "paragraph", "content": [{"type": "text", "text": "Docker"}]}
          ]
        }
      ]
    }
  ]
}
```

### Bước 4: Insert Entry Description

```sql
INSERT INTO entry_description_mgmt (
  title,
  summary,
  article,
  status,
  is_display,
  rank_order,
  is_delete,
  created_at,
  updated_at,
  layout_structure
) VALUES (
  'Prerequisites',
  'System requirements and prerequisites',
  '{"type":"doc","content":[...]}'::json,
  1,
  true,
  0,
  false,
  NOW(),
  NOW(),
  NULL
) RETURNING id;
```

**Repeat** cho tất cả Entry Descriptions, ghi chú ID được trả về.

### Bước 5: Insert hoặc Lấy Entry

**Nếu Entry đã tồn tại**:
```sql
SELECT id FROM entry_mgmt WHERE slug = 'installation' AND is_delete = false;
```

**Nếu Entry chưa có** (tạo mới):
```sql
INSERT INTO entry_mgmt (
  name,
  slug,
  status,
  is_display,
  rank_order,
  is_delete,
  created_at,
  updated_at,
  layout_structure
) VALUES (
  'Installation',
  'installation',
  1,
  true,
  0,
  false,
  NOW(),
  NOW(),
  NULL
) RETURNING id;
```

### Bước 6: Tạo Entry layout_structure (BẮT BUỘC)

**⚠️ QUAN TRỌNG**: Bước này là BẮT BUỘC, không phải optional!

Entry PHẢI có `layout_structure` để liên kết với Entry Description(s):

**Ví dụ Entry có 1 description**:
```sql
UPDATE entry_mgmt
SET layout_structure = '[
  {
    "ui_id": "UUID-1",
    "entry_desc_id": 100,
    "title": "My Description Title"
  }
]'::json
WHERE id = 50;
```

**⚠️ CHÚ Ý Field Name**: Dùng `entry_desc_id`, KHÔNG phải `entry_description_mgmt_id`!

**Ví dụ Entry có nhiều descriptions**:

```sql
UPDATE entry_mgmt
SET layout_structure = '[
  {
    "ui_id": "UUID-1",
    "entry_desc_id": 100,
    "title": "Prerequisites"
  },
  {
    "ui_id": "UUID-2",
    "entry_desc_id": 101,
    "title": "Clone Repository"
  }
]'::json
WHERE id = 50;
```

### Bước 7: Update Category layout_structure

Lấy Category hiện tại:
```sql
SELECT layout_structure FROM category_mgmt WHERE id = <category-id>;
```

**Thêm Entry vào layout**:

```sql
-- Nếu Category rỗng (layout_structure = NULL)
UPDATE category_mgmt
SET layout_structure = '[
  {
    "ui_id": "UUID-1",
    "entry_mgmt_id": 50,
    "name": "Installation",
    "slug": "installation"
  }
]'::json
WHERE id = <category-id>;

-- Nếu thêm vào danh sách hiện tại
UPDATE category_mgmt
SET layout_structure = jsonb_insert(
  layout_structure::jsonb,
  '{-1}',
  '{"ui_id":"UUID-2","entry_mgmt_id":50,"name":"Installation","slug":"installation"}'::jsonb
)::json
WHERE id = <category-id>;
```

### Bước 8: Validation

```sql
-- Check Entry Description
SELECT id, title FROM entry_description_mgmt WHERE is_delete = false;

-- Check Entry
SELECT id, name, layout_structure FROM entry_mgmt WHERE is_delete = false;

-- Check Category
SELECT id, name, jsonb_pretty(layout_structure::jsonb) FROM category_mgmt WHERE is_delete = false;
```

---

## 6. Database Connection & Queries

### Connection

```bash
# Connect to PostgreSQL
docker exec -it ml-postgres psql -U ml_pg_user -d ml_pg_db

# Or run query directly
docker exec ml-postgres psql -U ml_pg_user -d ml_pg_db -c "SELECT * FROM category_mgmt WHERE is_delete = false;"
```

### Useful Queries

**Xem tất cả Category structure**:
```sql
SELECT id, name, 
       CASE WHEN layout_structure IS NULL THEN 'NULL'
            WHEN layout_structure::text = '[]' THEN 'EMPTY'
            ELSE 'HAS_VALUE' END as status,
       jsonb_pretty(layout_structure::jsonb) as structure
FROM category_mgmt 
WHERE is_delete = false;
```

**Xem Entry Descriptions**:
```sql
SELECT id, title, summary FROM entry_description_mgmt 
WHERE is_delete = false ORDER BY id;
```

**Xem Entry layout_structure**:
```sql
SELECT id, name, layout_structure FROM entry_mgmt 
WHERE is_delete = false AND layout_structure IS NOT NULL;
```

**Count records**:
```sql
SELECT 
  (SELECT COUNT(*) FROM category_mgmt WHERE is_delete = false) as categories,
  (SELECT COUNT(*) FROM entry_mgmt WHERE is_delete = false) as entries,
  (SELECT COUNT(*) FROM entry_description_mgmt WHERE is_delete = false) as descriptions;
```

**Pretty print JSON**:
```sql
SELECT id, jsonb_pretty(layout_structure::jsonb) FROM category_mgmt WHERE is_delete = false;
```

**Search by UUID**:
```sql
SELECT * FROM category_mgmt 
WHERE layout_structure::text ILIKE '%ui_id-value%' 
AND is_delete = false;
```

---

## 7. Best Practices

### Tạo Layout Structure

1. **UUID mới** cho mỗi node
   - Sử dụng `crypto.randomUUID()` hoặc UUID v4
   - Đảm bảo tính duy nhất

2. **Duplicate** name/slug/title từ bảng gốc
   - Luôn giữ consistency
   - Tránh out-of-sync issues

3. **Chỉ thêm "children"** khi node thực sự có con
   - Leaf nodes: KHÔNG có "children"
   - Parent nodes: PHẢI có "children"

4. **Max 5 cấp** lồng
   - root → lv1 → lv2 → lv3 → lv4 → lv5
   - Nếu vượt → re-structure data

### Entry Description

- **Entry PHẢI CÓ `layout_structure`** để liên kết với Entry Description (bắt buộc!)
- Nếu Entry chỉ có 1 description → `layout_structure` là mảng 1 phần tử
- Nếu Entry có nhiều descriptions → `layout_structure` là mảng nhiều phần tử (có thể nested)
- Nội dung Entry Description luôn lưu trong `article` (Tiptap JSON)
- Entry Description KHÔNG có `layout_structure` (cột không tồn tại trong bảng)

### Maintenance

- **Regular validation**: Check depth, UUID uniqueness, ID references
- **Test queries**: Trước khi update production
- **Backup**: Trước các thay đổi lớn
- **Monitor denormalization**: Đảm bảo name/slug/title không drift

---

## 8. Tính Năng Tương Lai (Note)

⚠️ **KHÔNG implement hiện tại, chỉ note để tham khảo**:

### Chuẩn Bị nhưng chưa dùng:
- **Entry Description nesting**: `entry_description_mgmt.layout_structure` (reserved)
- **History tables**: `*_mgmt_hist` (prepared, not integrated)
- **Multi-language**: Cấu trúc có thể extend
- **Access Control**: Có thể thêm `permissions` vào nodes
- **Analytics**: Tracking via `ui_id`
- **Rank Order**: `rank_order` field (prepared for future)

**→ Hiện tại bỏ qua, tập trung logic cốt lõi.**

---

## 🎯 Tóm Tắt Quick Tips

| Cần làm | Cách làm |
|:---|:---|
| **Tạo Entry Description** | INSERT vào `entry_description_mgmt` với `article` = Tiptap JSON |
| **Tạo Entry** | INSERT vào `entry_mgmt`, SAU ĐÓ update `layout_structure` để link với Entry Description |
| **Update Category** | UPDATE `category_mgmt.layout_structure` JSON |
| **Entry Description có layout_structure?** | KHÔNG - cột không tồn tại trong bảng |
| **Entry có layout_structure?** | **BẮT BUỘC** - để liên kết với Entry Description(s), không có foreign key |
| **Category có layout_structure?** | **BẮT BUỘC** - để quản lý Entry, không có foreign key |
| **Max nested depth** | 5 cấp |
| **Node lá có "children"?** | KHÔNG |
| **Cấu trúc flat vs nested?** | Tùy yêu cầu, cả 2 đều được |
| **UUID validation** | UUID v4, duy nhất, hợp lệ |
| **Entry có 1 description** | `layout_structure` = mảng 1 phần tử (KHÔNG để NULL) |
| **Entry có nhiều descriptions** | `layout_structure` = mảng nhiều phần tử |
| **⚠️ Field naming** | Category → Entry: `entry_mgmt_id`<br/>Entry → Entry Desc: `entry_desc_id` (KHÔNG dùng `entry_description_mgmt_id`) |

---

Xử lý ... Dữ liệu đã được phân tích, định dạng, thực thi lưu trữ trực tiếp vào database PostgreSQL theo đúng quy tắc, hướng dẫn và yêu cầu từ ...:

Sau khi thực hiện xong kiểm tra kết quả các record đã thực hiện đúng và đã có liên kết layout_structure hay chưa.

Kết quả mong muốn, tất cả dữ liệu được xử lý phân loại đúng đắn theo yêu cầu trong database. Không cần giải thích lại, không tạo tài liệu mới. Nếu tạo các file mới để thực thi thì kết thúc xử lý cần loại bỏ các dữ liệu rác này, vì chúng không cần thiết để lưu trữ lại.