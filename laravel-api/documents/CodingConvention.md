# Hướng dẫn quy ước mã hóa (coding convention) trong project laravel-api
Tài liệu này tổng hợp các quy ước chung bao gồm các phần như cơ sở dữ liệu, Migration, Trigger (cơ sở dữ liệu), View (giao diện), Model, Controller, Service, Validate (Form Request), Interface, Repository, Resource, Route. Mỗi phần được nêu rõ cách đặt tên, vị trí file và các quy tắc thường dùng. Tất cả đều theo chuẩn Laravel (PSR-2/PSR-12) để code nhất quán và dễ duy trì.

## Thiết kế cơ sở dữ liệu
* **Table:** 
** **Định danh:** Theo dạng **snake_case** số ít. Viết tắt cho những tên định nghĩa dài, ko dấu, tiếng anh.
** **Hậu tố [table name]_[phạm vi: mst|mgmt]_hist:** Là table lưu trữ lịch sử
** **Hậu tố [table name]_mst:** Là table master. sử dụng cụm chức năng common quan trọng như admin hoặc hệ thống
** **Hậu tố [table name]_mgmt:** Là table management. Sử dụng chức năng quản lý sử dụng bởi các phòng ban. Được quản lý bởi cụm chức năng admin or hệ thống
** **Các bảng trung gian** tên định danh bao gồm tên các bảng liên quan. Ví dụ bảng product_tag_mgmt là bảng trung gian của bảng product_mgmt và tag_mgmt

* **Định danh tên cột:**
** **Định danh:** Theo dạng **snake_case** số ít và ko viết tắt, ko dấu, tiếng anh.
** **Mỗi table đều có column:** create_at và update_at data type loại timestamp để ghi nhận thời điểm tạo và cập nhật cho từng record
** **Các column trong bảng liên kết N-1:** Định nghĩa những column bảng [N] là khóa ngoại để có thể liên kết với bảng [1]
** **Các column trong bảng liên kết N-N:** Định nghĩa tổng hợp những column liên kết giữa hai bảng.
** **Tiền tố [is|has]_[column name]:** Cho các column dạng boolean
** **Column là khóa chính:** Để loại auto-increment
** **Hậu tố [column name]_id:** Cho khóa chính và khóa ngoại

* **Loại dữ liệu:** các column cần phân tích kiểu và kích thước tối thiểu hoặc tối đa mà dữ liệu cần sử dụng hiện tại và mở rộng trong tương lai để đưa ra để đảm bảo khả năng lưu trữ. Có thể dựa theo các quy chuẩn quốc tế về các loại dữ liệu và mục đích sử dụng để triển khai. Ví dụ kích thước cho số điện thoại là 12 kí tự, email là 320 kí tự,...

* **Các định nghĩa khác:** cần phân tích phạm vi, khả năng sử dụng của từng column để định nghĩa các thuộc tính default, not null tối ưu sử dụng cho từng column, chỉ định primary key, ko định nghĩa các foreign key để giảm sự phức tạp trong lúc dev

* **Chú thích và giải thích:** Cần viết định nghĩa ngắn gọn về ý nghĩa. Viết hoa chữ cái đầu. Giải thích chi tiết về cách chúng sử dụng cho việc gì ? có những loại nào ? khi nào dùng ở trong 1 bộ tài liệu riêng.

* **Procedure:**
** **Đinh danh** Theo dạng **snake_case** số ít và ko viết tắt, ko dấu, tiếng anh.
** **Hậu tố [function name]_[phạm vi: mst|[mst|mgmt]hist|mgmt]_function** Là tên của Function

* **View:**
** **Đinh danh** Theo dạng **snake_case** số ít và ko viết tắt, ko dấu, tiếng anh.
** **Tiền tố view_[view name]_[phạm vi: mst|[mst|mgmt]hist|mgmt]** Là tên của view

* **Trigger:**
** **Đinh danh** Theo dạng **snake_case** số ít và ko viết tắt, ko dấu, tiếng anh.
** **Tiền tố trigger_[insert|update|delete]_[before|after]_[insert|update|delete]_[table name]_[phạm vi: mst|[mst|mgmt]hist|mgmt]** Là tên của trigger

* **Sequence:**
** **Đinh danh** Theo dạng **snake_case** số ít và ko viết tắt, ko dấu, tiếng anh.
** **Hâu tố [table name]_[phạm vi: mst|[mst|mgmt]hist|mgmt]_seq** Là tên của Sequence



## Migration

* **Chạy câu lệnh tạo file migrate**:
  ```
  php artisan make:migration [create|update]_[component name]_[component] --path=[path]/[sub path]
  ```
** **[create|update]** create nếu chưa tồn tại, update dùng đã tồn tại [table|view|procedure|view|trigger|sequence]
** **[component]** [table|view|procedure|view|trigger|sequence]
** **[path]** ``` Database\Migrations ```

** **[Sub Path table]**
| Tiền tố | Hậu tố        | Sub Path                    |
| ------- | ------------- | --------------------------- |
|         | `_mst_hist`   | `\Table\History\Master`     |
|         | `_mgmt_hist`  | `\Table\History\Management` |
|         | `_mst`        | `\Table\Master`             |
|         | `_mgmt`       | `\Table\Management`         |

** **[Sub Path procedure]**
| Tiền tố | Hậu tố                 | Sub Path                        |
| ------- | ---------------------- | ------------------------------- |
|         | `_mst_hist_function`   | `\Procudure\History\Master`     |
|         | `_mgmt_hist_function`  | `\Procudure\History\Management` |
|         | `_mst_function`        | `\Procudure\Master`             |
|         | `_mgmt_function`       | `\Procudure\Management`         |

** **[Sub Path view]**
| Tiền tố | Hậu tố        | Sub Path                   |
| ------- | ------------- | -------------------------- |
| `view_` | `_mst_hist`   | `\View\History\Master`     |
| `view_` | `_mgmt_hist`  | `\View\History\Management` |
| `view_` | `_mst`        | `\View\Master`             |
| `view_` | `_mgmt`       | `\View\Management`         |

** **[Sub Path trigger]**
| Tiền tố   | Chuỗi tiếp theo            | Hậu tố        | Sub Path                                   |
| --------- | -------------------------- | ------------- | ------------------------------------------ |
| `tigger_` | `[*]_[*]_[*]_[table name]` | `_mst_hist`   | `\Trigger\History\Master\[table name]`     |
| `tigger_` | `[*]_[*]_[*]_[table name]` | `_mgmt_hist`  | `\Trigger\History\Management\[table name]` |
| `tigger_` | `[*]_[*]_[*]_[table name]` | `_mst`        | `\Trigger\Master\[table name]`             |
| `tigger_` | `[*]_[*]_[*]_[table name]` | `_mgmt`       | `\Trigger\Management\[table name]`         |

** **[Sub Path sequence]**
| Tiền tố | Hậu tố            | Sub Path                       |
| ------- | ----------------- | ------------------------------ |
|         | `_mst_hist_seq`   | `\Sequence\History\Master`     |
|         | `_mgmt_hist_seq`  | `\Sequence\History\Management` |
|         | `_mst_seq`        | `\Sequence\Master`             |
|         | `_mgmt_seq`       | `\Sequence\Management`         |


* **Định danh:** Tên file migration theo mẫu `yyyy_mm_dd_hhmmss_create_table_name_table.php`.
* **Tên bảng:** Laravel mặc định dùng **snake\_case** và số nhiều. Trong dự án này đặt tên theo **Thiết kế cơ sở dữ liệu**
* **Định nghĩa cột:** Định danh và thiết lập các thuộc tính như trong quy tắc **Thiết kế cơ sở dữ liệu** cho migrate. Sử dụng `$table->id()` cho cột ID,`$table->timestamps()` cho `created_at` và `updated_at` trong function up(). Viết các tính năng rollback dữ liệu trong function down() như drop hoặc update lại thay đổi migrate
* **Tạo đối tượng [view|produre|trigger|sequence]** do laravel không hỗ trợ trực tiếp tạo migrate cho các đối tượng này. Vì vậy sẽ viết sql thuần túy thông qua schema builder, cụ thể sử dụng phương thức `DB::statement()` hoặc `DB::unprepared()` để thực thi. Các đối tượng này viết trong function up() và thực hiện viết logic rollback lại các thay đổi trong function down(), như drop hoặc update


## Model
* **Chạy câu lệnh tạo file model**:
  ```
  php artisan make:model [model name] --path=[path]/[sub path]
  ```
** **[model name]** Dùng **PascalCase**, tên số ít, viết tắt cho những tên định nghĩa dài, ko dấu, tiếng anh. Dựa vào tên table chuyển tử **snake_case** sang **PascalCase** để đặt tên cho modal
** **[path]** ``` App\Models ```

** **[Sub Path model]**
| Tiền tố | Hậu tố        | Sub Path                    |
| ------- | ------------- | --------------------------- |
|         | `_mst_hist`   | `\History\Master`           |
|         | `_mgmt_hist`  | `\History\Management`       |
|         | `_mst`        | `\Master`                   |
|         | `_mgmt`       | `\Management`               |

* **Định nghĩa thuôc tính:**
** **Model đại diện cho table** `protected $table = '[table name]';`. Trong đó [table name] là tên table như **Thiết kế cơ sở dữ liệu**.
** **Các column sử dụng** `protected $fillable = ['columns',...];`. Trong đó [columns] là các column của table đó với format như **Thiết kế cơ sở dữ liệu**.
** **Trạng thái column** `public const [const name] = ['active'  => 1,...];`. Trong đó [const name] là tên hằng số đại diện cho 1 column, format dạng **CONSTANT_CASE**
* **Quan hệ (Relation):** Tên hàm quan hệ theo **camelCase**, 
  ```
    public function [table name format **camelCase**](): [hasOne|HasMany|BelongsTo|BelongsToMany]
    {
      return $this->[hasOne|HasMany|BelongsTo|BelongsToMany]([model name format **PascalCase**]::class);
    }
  ```
* **Các phương thức khác:** Đặt theo camelCase, mô tả rõ chức năng. 

## Controller
* **Chạy câu lệnh tạo file controller**:
  ```
  php artisan make:controller [controller name]Controller --path=[path]/[sub path]
  ```
** **[controller name]** Dùng **PascalCase**, tên số ít, viết tắt cho những tên định nghĩa dài, ko dấu, tiếng anh. Dựa vào tên table chuyển tử **snake_case** sang **PascalCase** để đặt tên cho controller
** **[path]** ``` App\Models ```

** **[Sub Path model]**
| Tiền tố | Hậu tố        | Sub Path                    |
| ------- | ------------- | --------------------------- |
|         | `_mst_hist`   | `\History\Master`           |
|         | `_mgmt_hist`  | `\History\Management`       |
|         | `_mst`        | `\Master`                   |
|         | `_mgmt`       | `\Management`               |

* **Định dạng:** Các property và method format dạng **camelCase**. Các method phải có comment: ý nghĩa method, mô tả data type của param, data type trả về. Ví dụ:
```
  /**
   * [Nhiệm vụ của method này]
   *
   * @param [data type] [tên param]
   * @return [data type]
   */
  public function [function name]([data type] [tên param]): [data type trả về]
  {
    return $this->handleRequest(function () use ([tên param]) {
      // Check valid method
      if ($request->method() !== Api::TYPE_OF_METHOD[x]) {
        throw new MethodNotAllowedException(
          [Api::TYPE_OF_METHOD[x]],
          Messages::E0405,
          CommonVal::HTTP_METHOD_NOT_ALLOWED
        );
      }

      return [Service name]::getInstance()->[method name]([param]);
    });
  }
```
** **[x]** là phương thức ứng với request đó: 
```[
    0 => "GET",
    1 => "POST",
    2 => "PUT",
    3 => "PATCH",
    4 => "DELETE",
  ];```


## Service
* **Vi trí service**:
  ```
  [path]/[sub path]/[service name]Service.php
  ```
** **[service name]** Dùng **PascalCase**, tên số ít, viết tắt cho những tên định nghĩa dài, ko dấu, tiếng anh. Dựa vào tên table chuyển tử **snake_case** sang **PascalCase** để đặt tên cho service
** **[path]** ``` App\Services ```

** **[Sub Path model]**
| Tiền tố | Hậu tố        | Sub Path                    |
| ------- | ------------- | --------------------------- |
|         | `_mst_hist`   | `\History\Master`           |
|         | `_mgmt_hist`  | `\History\Management`       |
|         | `_mst`        | `\Master`                   |
|         | `_mgmt`       | `\Management`               |

* **Kế thừa:** Các class extend SingletonService từ `use App\Services\SingletonService;`
* **Định dạng:** Các property và method format dạng **camelCase**. Các method phải có comment: ý nghĩa method, mô tả data type của param, data type trả về. Ví dụ:
```
  /**
   * [Nhiệm vụ của method này]
   *
   * @param [data type] [tên param]
   * @return [data type]
   */
  public function [function name]([data type] [tên param]): [data type trả về]
  {
    $validator = (new CommonService())->validationManual(
      (new [validation name]Request()),
      [param]
    );

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    [variable] = [Repoitory name]Repository::[Method name]([param]);

    return [variable]
      ? [Resource name]Resource::collection([variable])
      : [];
  }


## Validate Request
* **Chạy câu lệnh tạo file request validate**:
  ```
  php artisan make:request [request name]Request --path=[path]/[sub path]
  ```
** **[validate name]** Dùng **PascalCase**, tên số ít, viết tắt cho những tên định nghĩa dài, ko dấu, tiếng anh. Dựa vào tên [service name] + [tên method] + hậu tố "Request" dạng **PascalCase** để đặt tên cho validate. VÍ dụ: CategoryService.php có function store() thì tạo tên file kiểu như sau: Category/CategoryStoreRequest.php
** **[path]** ``` App\Http\Requests\ ```

** **[Sub Path model]**
| Tiền tố | Hậu tố        | Sub Path                    |
| ------- | ------------- | --------------------------- |
|         | `_mst_hist`   | `\History\Master`           |
|         | `_mgmt_hist`  | `\History\Management`       |
|         | `_mst`        | `\Master`                   |
|         | `_mgmt`       | `\Management`               |

* **Định dạng:** Các property và method format dạng **camelCase**. Các method phải có comment: ý nghĩa method, mô tả data type của param, data type trả về. 
* **Viết rule** Trong function rule() lấy tất cả param request, mỗi param viết rule riêng. Các param hầu hết tương ứng với column của 1 table cùng tên model, lấy các điều kiện migrate của table đó để xác định rule validate vd:
- Ở trong migrate nội dung của table category như sau:
  $table->increments('id');
  $table->unsignedInteger('parent_id')->default(0)->comment('Parent category');
  $table->string('name', 50)->comment('Category name');
  $table->string('slug', 50)->comment('Category slug');
  $table->string('description', 150)->nullable()->comment('Category description');
  $table->unsignedTinyInteger('status')->default(0)->comment('Category status');
  $table->boolean('is_display')->default(false)->comment('Display category');
  $table->unsignedSmallInteger('rank_order')->default(0)->comment('Category order');
  $table->timestamps();
- Ở file migrate thực hiện validate trước khi store với rule
  public function rules(): array
  {
    return [
      'parent_id'   => 'numeric|min:0',
      'name'        => 'required|string|min:0|max:50|unique:App\Models\Master\category,name',
      'slug'        => 'required|string|min:0|max:50|unique:App\Models\Master\category,slug',
      'description' => 'string|min:0|max:150',
      'status'      => 'in:' . implode(',', array_values(Category::CATEGORY_STATUS)),
      'is_display'  => 'bool',
      'rank_order'  => 'numeric|min:0'
    ];
  }
* **Định nghĩa message validate** Ở trong function messages() return về định nghĩa từng message của từng param ứng với từng rule của param đó. Ví dụ:
  /**
  * Parent Id
  */
  'parent_id.numeric' => Messages::getMessage(
    Messages::E0001,
    ['attributes' => Category::attributes()['parent_id']]
  ),
  'parent_id.min' => Messages::getMessage(
    Messages::E0010,
    [
      'attributes' => Category::attributes()['parent_id'],
      'number' => Category::LENGTH_ATTR[0],
    ]
  ),