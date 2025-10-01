// Phân loại scope
- hậu tố: mst -> tạo folder Master
- hậu tố: mgmt -> tạo folder Management
- hậu tố: mgmt_hist -> tạo folder Management\Master
- hậu tố: mst_hist -> tạo folder History\Master

// Đọc dữ liệu json trong path ./database/schema/pgsql-schema.json
- loop dữ liệu schema object.properties
- Môi item được loop lấy tên key là tên table {table name}, item.properties lấy tên key là tên các column

// Phân loại QH
- Các table hậu tố (_mst or _mgmt) và table đó nếu có 2 column có dạng *_id thì nó là table trung gian.
Còn lại là table thông thường

// Tạo file validate

- Nếu là table trung gian 
    tạo 2 file validate, với file name như format: 
    + {name}ListRequest
    + Update{name}Request

- Nếu table thông thường
   tạo 4 file validate, với file name như format: 
   + Delete{name}Request
   + {name}ListRequest
   + Store{name}Request
   + Update{name}Request

- Trong đó {name} là tên table được viết cammel case, viết hóa chữ cái đầu tiên,
bỏ dấu _ viết hóa chữ cái đầu tiên của mỗi từ
- Trong đó {name scope} là tên loại bỏ hậu tố (_mst, _mgmt, _mst_hist, _mgmt_hist), table được viết cammel case, viết hóa chữ cái đầu tiên,
bỏ dấu _ viết hóa chữ cái đầu tiên của mỗi từ
- Đặt file trong folder có format url sau: App\Http\Requests\{value của hậu tố}\{file name}.php
vd: table social_mst tạo file 
App\Http\Requests\Master\Social\DeleteSocialMstRequest.php
App\Http\Requests\Master\Social\SocialMstListRequest.php
App\Http\Requests\Master\Social\StoreSocialMstRequest.php
App\Http\Requests\Master\Social\UpdateSocialMstRequest.php

- Nếu tồn tại file đó thì thay thế file đó

- Nội dung file Delete{name}Request như sau:
```<?php

namespace App\Http\Requests\{value của hậu tố}\{name scope};

use App\Constants\CommonVal;
use App\Models\{value của hậu tố}\{name};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class {file name} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array'],
            'ids.*' => [
                'required',
                'integer',
                'min:' . CommonVal::MIN_INTEGER,
                'max:' . CommonVal::MAX_INTEGER,
                Rule::exists({name}::class, 'id')
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'ids' => __('message.{table name}_id'),
            'ids.*' => __('message.{table name}_id'),
        ];
    }

}```

- Các file validate khác theo nội dung sau:


```<?php

namespace App\Http\Requests\{value của hậu tố}\{name scope};

use App\Constants\CommonVal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
{use}

class {file name} extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '{item}' => ['{validate type}', '{validate type}'],
            '{item}' => ['{validate type}', '{validate type}'],
        ];
    }

    /**
     * Get custom attribute names for validator errors
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            '{item}' => __('message.{item}'),
            '{item}' => __('message.{item}'),
        ];
    }
}```

- Trong đó: '{item}' => ['{validate type}', '{validate type}'],
- {item} là tên các column trong table, lấy từ item.properties key
- {validate type} lấy từ kiểu dữ liệu trong, 
lấy theo thứ tưng tự ưu tiên:
- item.properties.{item} -> tên column, nếu không khớp với kiểu dữ liệu trong bảng json bên dưới thì lấy tiếp
- item.properties.{item}.format -> nếu không khớp với kiểu dữ liệu trong bảng json bên dưới thì lấy tiếp
- lấy item.properties.{item}.type so khớp với bảng sau, nếu không khớp thì bỏ qua column đó:
| Kiểu dữ liệu trong json | Kiểu validate trong laravel                                                                |
|------------------------|---------------------------------------------------------------------------------------------|
| integer                | 'numeric', 'min:'. CommonVal::MIN_INTEGER, 'max:'. CommonVal::MAX_INTEGER,                  |
| string                 | 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_VARCHAR,                   |
| boolean                | new Enum(IsActive::class),                                                            |
| date-time              | 'date_format:' . CommonVal::DATE_FORMAT, 'after_or_equal:' . CommonVal::MIN_DATE, 'before_or_equal:' . CommonVal::MAX_DATE, |        
| email                  | 'email:rfc,dns', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_EMAIL, Rule::unique({name}::class, 'email'), |        
| phone_number           | 'string', 'min:' . CommonVal::MIN_VARCHAR, 'max:' . CommonVal::MAX_PHONE_NUMBER, |        
| gender                 | new Enum(Gender::class),                                  |        
| status                 | new Enum(StatusEnum::class),                                 |        
| birth                  | 'date_format:' . CommonVal::DATE_FORMAT, 'after_or_equal:' . CommonVal::MIN_DATE, 'before_or_equal:' . CommonVal::MAX_DATE,                                 |        
| is_active              | new Enum(IsActive::class),                            |        
| is_delete              | new Enum(IsDelete::class),                            |             


// Định nghĩa rule import
- boolean thì `use App\Enums\IsActive;`
- gender thì `use App\Enums\Gender;`
- status thì `use App\Enums\StatusEnum;`
- is_active thì `use App\Enums\IsActive;`
- is_delete thì `use App\Enums\IsDelete;`
- Nếu validate loại (Store{name}Request or Update{name}Request) và
kiểu dữ liệu trong json có dạng *_id, thì lấy * bỏ _id, thêm dòng mới sau use cuối cùng `use App\Models\{value của hậu tố}\{lấy * bỏ _id};`
ngoại trừ column tên `author_id`, `row_id`, `parent_id`, `failed_job_ids`, `field_id`, `tokenable_id`,
- Nếu Kiểu validate trong laravel  có Rule::unique({name}::class, '{item}') thì thêm dòng `use App\Models\{value của hậu tố}\{name};`
- Bỏ qua column `created_at` và `updated_at`
-> thay thế {use} vào dòng cuối use và xuống hàng, nếu use trùng thì không thêm nữa, nếu không có thì bỏ {use} đi.
dùng '\n' không xuống dòng mới khi run file .sh

- Thứ tự ưu tiên, 
+ Nếu là loại {name}ListRequest thì tất cả các column đều là nullable (ưu tiên)
+ có required thì thêm 'required' vào đầu mảng validate type, nếu không thì thêm 'nullable' vào đầu mảng validate type

- Nếu là file {name}ListRequest thêm rules sau vào cuối array rules:
`'from_date' => [
    'nullable',
    'date_format:' . CommonVal::DATE_FORMAT,
    'after_or_equal:' . CommonVal::MIN_DATE,
    'before_or_equal:' . CommonVal::MAX_DATE,
],
'to_date' => [
    'nullable',
    'date_format:' . CommonVal::DATE_FORMAT,
    'after_or_equal:' . CommonVal::MIN_DATE,
    'before_or_equal:' . CommonVal::MAX_DATE,
    'after:from_date'
],`


- khi run shell script sẽ ghi đè lên các file đã tồn tại
- logic kiểm tra là table trung gian: không có column "id" và có >= 2 column dạng *_id
- Nếu là table trung gian thì có 2 file validate, list và update (insert, delete)
Nội dung file validate
- sắp xếp thự tự các column như định nghĩa của table object
- delete thì kiểm tra tồn tại của id trong bảng. **Nếu là table liên kêt với table khác thì cần kiểm tra
không còn liên kết với table khác mới được xóa**
- store và update thì kiểm tra tồn tại của các *_id trong bảng tương ứng. ngoại trừ các id
đặc biệt như author_id, row_id, parent_id, failed_job_ids, field_id, tokenable_id
- Nếu là table thông thường thì có 4 file validate, delete, list, store,


-------------------------------------------------------------
prompt generate code shell script:
bạn là senior back-end file đính kèm pgsql-schema.json là schema db dữ
 liệu quan hệ. File test.php là các logic, yêu cầu chi tiết từng thành
  phần của file validate. Tôi muốn tạo 1 file shell script (1 file .sh)
  , khi chạy sẽ tạo file validate với path, nội dung, ... giống như định
   nghĩa logic trong file test.php đính kèm, không bỏ qua chi tiết nào