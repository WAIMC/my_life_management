Quy trình sử dụng AI để tạo mã CRUD trong Laravel
Quy trình này hướng dẫn cách sử dụng AI (như GitHub Copilot) để tự động hóa việc tạo mã CRUD cho một bảng cụ thể trong Laravel. Rule này áp dụng cho bất kỳ bảng nào, ví dụ: bảng t_admin. Thành viên team sẽ clone repository, đọc tài liệu này, và yêu cầu AI tạo mã dựa trên rule dưới đây.
Mục đích

Tự động hóa việc tạo mã CRUD.
Đảm bảo tính nhất quán trong cấu trúc mã.
Dễ dàng áp dụng cho mọi thành viên trong team.

Cách sử dụng

Clone repository: Lấy source code chứa tài liệu này.
Đọc tài liệu: Xem docs/crud_rule.md để hiểu quy trình.
Yêu cầu AI: Trên editor (như VSCode với GitHub Copilot), nhập yêu cầu:"Tạo mã CRUD cho bảng t_admin theo rule trong docs/crud_rule.md với các cột: id, email (string, 30), user_name (string, 50), timestamps."
Review và chỉnh sửa: Kiểm tra mã được tạo và điều chỉnh nếu cần.

Rule chi tiết cho AI
1. Migration

Tên file: database/migrations/[timestamp]_create_[table]_table.php
Cấu trúc:

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create[Table]Table extends Migration
{
    public function up()
    {
        Schema::create('[table]', function (Blueprint $table) {
            $table->id();
            // Thêm các cột theo yêu cầu, ví dụ:
            $table->string('email', 30)->comment('Admin email');
            $table->string('user_name', 50)->comment('Admin user name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('[table]');
    }
}


Thay thế: [table] là tên bảng (ví dụ: t_admin), [Table] là tên bảng dạng CamelCase (ví dụ: TAdmin).

2. Model

Đường dẫn: app/Models/[Table].php
Cấu trúc:

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class [Table] extends Model
{
    protected $table = '[table]';
    protected $fillable = ['email', 'user_name']; // Thêm các cột từ yêu cầu
}


Thay thế: [table] và [Table] như trên.

3. Repository Interface

Đường dẫn: app/Contracts/Repositories/[Table]RepositoryInterface.php
Cấu trúc:

namespace App\Contracts\Repositories;

interface [Table]RepositoryInterface
{
    public function all();
    public function create(array $data);
    public function find($id);
    public function update($id, array $data);
    public function delete($id);
}

4. Repository

Đường dẫn: app/Repositories/[Table]Repository.php
Cấu trúc:

namespace App\Repositories;

use App\Models\[Table];
use App\Contracts\Repositories\[Table]RepositoryInterface;

class [Table]Repository implements [Table]RepositoryInterface
{
    protected $model;

    public function __construct([Table] $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = $this->find($id);
        $record->delete();
        return true;
    }
}

5. Service

Đường dẫn: app/Services/[Table]Service.php
Cấu trúc:

namespace App\Services;

use App\Repositories\[Table]Repository;
use App\Contracts\Repositories\[Table]RepositoryInterface;

class [Table]Service
{
    protected $repository;

    public function __construct([Table]RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}

6. Controller

Đường dẫn: app/Http/Controllers/[Table]Controller.php
Cấu trúc:

namespace App\Http\Controllers;

use App\Services\[Table]Service;
use App\Http\Requests\[Table]StoreRequest;
use App\Http\Requests\[Table]UpdateRequest;
use Illuminate\Http\Request;

class [Table]Controller extends Controller
{
    protected $service;

    public function __construct([Table]Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json($this->service->getAll());
    }

    public function store([Table]StoreRequest $request)
    {
        return response()->json($this->service->create($request->validated()), 201);
    }

    public function show($id)
    {
        return response()->json($this->service->getById($id));
    }

    public function update([Table]UpdateRequest $request, $id)
    {
        return response()->json($this->service->update($id, $request->validated()));
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }
}

7. Validation

Store Request: app/Http/Requests/[Table]StoreRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class [Table]StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:[table],email',
            'user_name' => 'required|string|max:50',
            // Thêm rules từ yêu cầu
        ];
    }
}


Update Request: app/Http/Requests/[Table]UpdateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class [Table]UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|unique:[table],email,' . $this->route('id'),
            'user_name' => 'required|string|max:50',
            // Thêm rules từ yêu cầu
        ];
    }
}

8. Route

Đường dẫn: routes/api.php
Cấu trúc:

use App\Http\Controllers\[Table]Controller;

Route::prefix('[table]')->controller([Table]Controller::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{id}', 'show');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});

Ví dụ thực tế

Yêu cầu AI: "Tạo mã CRUD cho bảng t_admin theo rule trong docs/crud_rule.md với các cột: id, email (string, 30), user_name (string, 50), timestamps."
AI sẽ tạo các file theo cấu trúc trên, thay [table] bằng t_admin, [Table] bằng TAdmin.

Lưu ý

Đảm bảo các cột trong $fillable và rules() khớp với thiết kế bảng.
Review mã để thêm logic nghiệp vụ nếu cần (ví dụ: mã hóa mật khẩu, quan hệ bảng).

