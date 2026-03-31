# Kiến Trúc Hệ Thống: Category, Entry và Entry Description

Tài liệu này mô tả cấu trúc, hành vi và mối quan hệ giữa ba thành phần cốt lõi của hệ thống quản lý nội dung (CMS) phục vụ website document.

## 1. Thành Phần Cốt Lõi

### Category (Danh mục)
- **Bảng DB**: `category_mgmt`
- **Vai trò**: Là cấp độ phân loại cao nhất và độc lập. Mỗi Category đại diện cho một nhóm tài liệu riêng biệt (ví dụ: "AI", "Backend", "Frontend").
- **Hành vi**: Một Category hiển thị danh sách các Entry thuộc về nó. Mối quan hệ giữa Category và Entry là **Nhiều - Nhiều (Many-to-Many)**. Điều này cho phép một Entry xuất hiện ở nhiều Category khác nhau với các vị trí hiển thị khác nhau.

### Entry (Đầu mục)
- **Bảng DB**: `entry_mgmt`
- **Vai trò**: Đóng vai trò như các Menu hoặc Tiêu đề cho một bài viết/trang tài liệu.
- **Hành vi**: Entry giống như khung sườn của bài viết. Nó quản lý các Entry Description (nội dung chi tiết). 
- **Cấu trúc lồng ghép**: Các Entry có thể lồng vào nhau (Entry cha chứa Entry con) để tạo thành menu phân cấp nhiều tầng. Đặc biệt, cấu trúc này có thể thay đổi linh hoạt tùy theo từng Category.

### Entry Description (Mô tả mục)
- **Bảng DB**: `entry_description_mgmt`
- **Vai trò**: Là đơn vị nội dung nhỏ nhất. Chứa tiêu đề phụ và nội dung chi tiết (đoạn văn, code block, v.v.).
- **Hành vi**: Một bài viết (Entry) được cấu thành từ nhiều Entry Description. Chúng giống như các "khối" (blocks) nội dung. 
- **Tái sử dụng & Lồng ghép**: Một đoạn nội dung (Entry Description) có thể xuất hiện trong nhiều bài viết (Entry) khác nhau. Chúng cũng có khả năng lồng cấp vào nhau để tạo ra các tiểu mục (sub-sections) trong một bài viết.

---

## 2. Cơ Chế Liên Kết: Layout Structure (JSON)

Hệ thống **không sử dụng** các khóa ngoại (Foreign Keys) truyền thống để ràng buộc quan hệ cha-con hoặc thứ tự hiển thị. Toàn bộ logic phân cấp và liên kết được quản lý thông qua cột `layout_structure` dạng JSON.

### Đặc điểm của Layout Structure:
- **Kiến trúc lồng ghép**: Cấp bậc được xác định bằng cách lồng các đối tượng con vào mảng `children` của đối tượng cha.
- **Phân cấp hiển thị**: Độ sâu của JSON (số tầng `children`) tương ứng trực tiếp với độ sâu lồng nhau trên giao diện người dùng (UI).
- **Giới hạn độ sâu**: Hệ thống cho phép lồng cấp tối đa là **5 cấp** (tính từ parent ngoài cùng đến child trong cùng).
- **Tính linh hoạt**: Vì định nghĩa bằng JSON, cùng một dữ liệu có thể có cấu trúc hiển thị hoặc phân cấp hoàn toàn khác nhau ở các vị trí khác nhau.
- **Lưu ý về rank_order**: Trường `rank_order` hiện có trong dữ liệu chỉ phục vụ cho tính năng xếp hạng/phân loại truy cập sẽ triển khai trong tương lai, không đóng vai trò quyết định cấu trúc lồng cấp của hệ thống.

---

## 3. Dữ Liệu Tham Khảo Thực Tế

### Ví dụ về cấu trúc Layout dữ liệu thực tế
Dưới đây là một mẫu `layout_structure` điển hình mô tả sự lồng cấp sâu (Nesting) thông qua thuộc tính `children`:

```json
[
  {
    "ui_id": "f887aef5-aeb3-4f89-ad27-57c4edfcc898",
    "entry_mgmt_id": 1,
    "name": "Installation",
    "slug": "installation",
    "children": [
      {
        "ui_id": "24cfdeeb-b94b-4dd0-a33e-3edba37112e1",
        "entry_mgmt_id": 2,
        "name": "Project Structure",
        "slug": "project-structure",
        "children": [
          {
            "ui_id": "0f24e2b1-a91a-433e-9279-fa258441c5a1",
            "entry_mgmt_id": 3,
            "name": "Configuration",
            "slug": "configuration",
            "children": [
              {
                "ui_id": "abc7c66c-ea87-40e4-81a0-f4a2c61aac4b",
                "entry_mgmt_id": 4,
                "name": "Overview",
                "slug": "overview",
                "children": [
                  {
                    "ui_id": "8dd32e52-6559-4421-975a-2d5ee231fbee",
                    "entry_mgmt_id": 5,
                    "name": "Layered Architecture",
                    "slug": "layered-architecture"
                  }
                ]
              },
              {
                "ui_id": "ec9b7beb-6c0f-4d22-897a-aec951665938",
                "entry_mgmt_id": 6,
                "name": "Design Patterns",
                "slug": "design-patterns"
              }
            ]
          },
          {
            "ui_id": "3e9f445b-6f9a-43a8-a432-9f01f1f6b11b",
            "entry_mgmt_id": 7,
            "name": "Authentication",
            "slug": "authentication"
          }
        ]
      },
      {
        "ui_id": "d938a44d-d8a2-4271-8e22-ed73e34c756e",
        "entry_mgmt_id": 8,
        "name": "Categories API",
        "slug": "categories-api"
      }
    ]
  },
  {
    "ui_id": "78915a49-b759-4aac-9a82-2529a863a8b9",
    "entry_mgmt_id": 9,
    "name": "Entries API",
    "slug": "entries-api"
  }
]
```
*(Cấu trúc trên cho thấy rõ việc `children` nằm bên trong đối tượng cha để tạo ra phân cấp)*

### Cấu trúc dữ liệu chi tiết của Entry Description (Dữ liệu thật từ DB)
Cột `article` trong `entry_description_mgmt` lưu trữ nội dung thực tế dưới dạng JSON. Hệ thống hiện tại sử dụng công nghệ **Tiptap** để quản lý và hiển thị nội dung Rich Text này.

| ID | Title | Entry MGMT ID |
|:---|:---|:---|
| 1 | Prerequisites | 1 |
| 2 | Clone Repository | 1 |

**Mẫu nội dung đơn giản (Tiptap JSON Content):**
```json
{
  "type": "doc",
  "content": [
    {
      "type": "heading",
      "attrs": {"level": 2},
      "content": [{"type": "text", "text": "Tiêu đề bài viết"}]
    },
    {
      "type": "paragraph",
      "content": [{"type": "text", "text": "Đây là nội dung văn bản đơn giản."}]
    }
  ]
}
```

---

## 4. Tóm tắt Mối Quan Hệ

1. **Category** quản lý danh sách **Entry** thông qua JSON `layout_structure`.
2. **Entry** quản lý các **Entry Description** (các khối nội dung) cũng thông qua JSON `layout_structure`.
3. Việc hiển thị lồng nhau (Nested UI) được quyết định hoàn toàn bởi cấu trúc lồng nhau của các Object bên trong mảng JSON này.
4. **Entry** đóng vai trò là "Nhạc trưởng" điều phối các nội dung nhỏ (**Entry Description**) để tạo nên một bài viết hoàn chỉnh.

Đọc nội dung ... để hiểu nghiệp vụ, tôi đang build môi trường trên docker, hãy truy cập vào postgres để xem cấu trúc table như nội dung này mô tả.
Tiếp theo đọc tài liệu ... format chúng lại thành 3 đơn vị category, entry và entry description. Sau đo đưa tôi review nội dung. trước khi tôi yêu cầu tiếp theo

Từ nội dung phân tách tài liệu tôi yêu cầu format thành 3 nội dung category, entry và entry description. Hãy thực hiện insert/update trực tiếp vào cơ sở dũ liệu cho tôi.