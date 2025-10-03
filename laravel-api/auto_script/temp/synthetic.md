- CodingConvention.md (chuẩn kiến trúc + naming + convention rõ ràng)
- Agent (mô tả mục tiêu, rule, behavior, workflow)
- Prompt mẫu (cách bạn feed input: scope, table, schema, FK, status)
- Một vài module mẫu đã hoàn chỉnh (để AI và Copilot “học” cách triển khai thực tế).
- Migration meta data dạng yaml/json. Có thể tạo từ migrate, file .sql, ERD,... mô tả table, column, fk, status.

**************************
Đề xuất Biểu Mẫu Prompt Chuẩn:

Persona: <Nhập vai trò kỹ thuật cụ thể> <Bạn là một Kỹ sư Backend cao cấp>

Context: <Đường dẫn tới coding convention, các tệp liên quan>

Task Breakdown (Chain-of-Thought): <Liệt kê các bước chi tiết để tạo module>

Schema Input: <JSON string của một bảng cụ thể>

Relationship Mapping: <Hướng dẫn rõ ràng về cách xử lý các foreign_keys, ví dụ: "Bảng 'A' có quan hệ một-một với bảng 'B' qua trường 'B_id', hãy nhúng đối tượng 'B' vào đối tượng 'A'.">

Constraints & Requirements: <Các yêu cầu cụ thể khác, ví dụ: xử lý lỗi, bảo mật, v.v.>

+++
- Trước mỗi lần enter prompt
+ Đính kèm codingConvention và metadata vào context
+ Chọn agent
+ paste propmt + enter
parse schema + convention → Generate prompt → Feed vào agent -> enter prompt

*************************************************************************************************************************************
GIAI ĐOẠN

+++ Giai đoạn 1 (Hiện tại): Triển khai quy trình tạo mã API CRUD. Tập trung vào việc xây dựng nền tảng vững chắc, bao gồm xác thực schema, tự động hóa prompt và tích hợp kiểm tra chất lượng cơ bản.

Giai đoạn 2 (Trung hạn): Mở rộng quy trình để tự động hóa việc tạo ORM (Object-Relational Mapping) và các migration files dựa trên schema. Điều này sẽ giúp đồng bộ hóa giữa lược đồ cơ sở dữ liệu và mã nguồn ứng dụng một cách tự động.   

Giai đoạn 3 (Dài hạn): Tích hợp AI sâu hơn vào quy trình, không chỉ để tạo mã mà còn để viết tài liệu , tái cấu trúc mã  và tự động gỡ lỗi. Mục tiêu là biến AI trở thành một thành viên tích cực của đội ngũ phát triển, không chỉ là một công cụ tạo mã đơn thuần.   

**********************************************************************************************************************
- hậu tố: mst -> tạo folder App\Http\Requests\Master

- hậu tố: mgmt -> tạo folder App\Http\Requests\Management

- hậu tố: mgmt_hist -> tạo folder App\Http\Requests\Management\Master

- hậu tố: mst_hist -> tạo folder App\Http\Requests\History\Master

- Các table hậu tố _mst và _mgmt + kiểm tra các column nếu có 2 column có dạng *_id thì nó là table trung gian. Còn lại là table trung gian

- Table thông thường tạo: 4 file validate theo format: Delete{name}Request, {name}ListRequest, Store{name}Request, Update{name}Request. Trong folder hậu tố tương ứng được tạo phía trên

- Table trung gian tạo: 2 file validate theo format: {name}ListRequest, Update{name}Request. Trong folder hậu tố tương ứng được tạo phía trên

- Trong đó name lấy tên table viết dạng camel case social_mgmt_hist -> SocialMgmtHist

- Kiểm tra có tồn tại file đó không nếu không thì thêm mới, nếu có rồi thì replace nội dung

nội dung file validate như sau:

```<?php



namespace App\Http\Requests\{path folder};



use App\Constants\CommonVal;

use App\Models\{path folder}\{name tương tự tên model dạng cammel case vd: BannerMgmtHist};

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;



class {request name} extends FormRequest

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

           '{item}' =>  __('message.{item}'),

        ];

    }

}```



trong đó file delete request chỉ bao gồm payload là ids dạng array, mỗi phần tử [

                'required',

                'integer',

                'min:' . CommonVal::MIN_INTEGER,

                'max:' . CommonVal::MAX_INTEGER,

                Rule::exists({tên model}::class, 'id')

            ],



Các file validate store, update, list thực hiện validate tất cả các column có trong table. Set rule tương ứng với từng column. File validate list thêm rule sau

'from_date' => [

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

            ],



Tôi định nghĩa CommonVal để lưu common val trong đó có các const để validate vd public const DATE_FORMAT = 'd/m/Y';

    public const BLACKLIST = 'blacklist';



    public const MIN_INTEGER = 0;

    public const MAX_INTEGER = 2147483647;

    public const MIN_DATE = '1900-01-01';

    public const MAX_DATE = '2100-12-31';

    public const MIN_VARCHAR = 0;

    public const MAX_VARCHAR = 255;

    public const MAX_EMAIL = 254;

    public const MAX_PHONE_NUMBER = 12;

và sử dụng vd: 'birth' => [

                'nullable',

                'date_format:' . CommonVal::DATE_FORMAT,

                'after_or_equal:' . CommonVal::MIN_DATE,

                'before_or_equal:' . CommonVal::MAX_DATE,

            ],

Viết bash Thực hiện loop dữ liệu file tôi đính kèm, mỗi table đều sẽ thực hiện tạo file validate như yêu cầu trên. cung cấp cho tôi các loại const để tôi định nghĩa trong const và khi validate sẽ dùng các const này

**************************************************************
- Bộ 3: ERD schema, DDL, json schema
    + ERD và DDL có thể sử dụng đồng thời ở dbeaver
    + Convert DDL -> json schema (để apply các tính năng mapping trong code):
        cài đặt package python simple-ddl-parser để convert, coding file, đọc nội dung file DDL, convert qua json schema
**************************************************************