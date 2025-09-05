--------------------------------------------------------------
- Viết câu promt ngắn gọn và chi tiết
- Viết dạng đi vào hành động nhiều hơn là dạng suy nghĩ
- Chia nhỏ yêu cầu thành các bước cụ thể
- Nếu yêu cầu mông lung, phức tạp thì cung cấp thêm ví dụ cụ thể
- Kiểm tra kết quả đâu ra
- AI giởi hạn bởi token và context. Nếu đưa ra 1 tài liệu quá dài hoặc thiếu thông tin sẽ khiến AI có thể bị miss or bỏ quả các requirement. Dẫn đén AI thường mắc kẹt trong các vòng lặp, or đưa
ra các câu hỏi cần thêm thông tin do AI đã quyên nội dung trao đổi
- Bộ nhớ AI giông như bộ nhớ đệm ngắn hạn hơn là sự hiểu biết thông minh. Nếu không có sự can thiệp cụ thể và liên tục thì tỷ lệ tuân thủ sẽ giảm dần. Khi không có đầy đủ thông tin do user cung
cấp thiếu or phức tạp AI không xử lý được thì AI sẽ sủ dụng các cách phổ biến đã được đào tạo.
- AI giống như 1 trợ lý hơn là 1 tác nhân tác động đến kết quả
------------------------------
STEP
- Note ra ý tưởng
- Tổng hợp, research các nội dung cần thực hiện
- Note ra từng bước thực hiện chi tiết và cụ thể. Có ví dụ minh họa
- Đưa AI generate ra các câu promt
- Yêu cầu AI thực hiện từng câu promt đó
- Thu thập kết quả và xác thực đầu ra như nào là hợp lệ. Nếu pass thì thực hiện bước tiếp theo. Nếu không rollback thay đối sau đó thực hiện lại nếu có nhiều thay đổi sai, sửa nếu
ít thay đổi sai
- Yêu cầu lặp lại các thao tác này cho phạm vi tiếp theo or theo danh sách sẽ được liệt kê trước
------------------------------

Mẫu này không chỉ là việc hiển thị mã được tạo; nó còn là việc minh họa quá trình tương tác với AI, các prompt được sử dụng và mức độ tuân thủ đạt được. Bằng cách hiển thị từng bước tạo mã cho một bảng, nó minh họa tính khả thi và nỗ lực cần thiết. Mẫu này đóng vai trò như một bản thiết kế thu nhỏ cho tác vụ lớn hơn của người dùng. Nó sẽ tiết lộ sự phức tạp của kỹ thuật thiết kế prompt cho từng thành phần và sự cần thiết của việc tinh chỉnh lặp lại. Nó cũng ngụ ý rằng ngay cả với các phương pháp tốt nhất, một số can thiệp thủ công vẫn có thể cần thiết, củng cố ý tưởng về AI như một trợ lý mạnh mẽ chứ không phải là một nhà phát triển hoàn toàn tự động trong kịch bản bị ràng buộc cao này.

Note: Nếu khối lượng promt có nội dung gần giống nhau được lặp lại sẽ tạo ra khối lượng công việc lớn và bị trùng lặp gây lãng phí. Do đó sẽ thực hiện mô hình đào tạo cho AI dựa trên dữ liệu hiện có
Đây là cách tối ưu nhất để nhúng các quy ước, thông tin quan trọng trực tiếp vào kiến thức AI

////////////////////////////////////////
# Content of agent
---
description: 'Laravel API CRUD generator mode with coding convention compliance.'
tools: ['codebase', 'usages', 'vscodeAPI', 'think', 'problems', 'changes', 'testFailure', 'terminalSelection', 'terminalLastCommand', 'openSimpleBrowser', 'fetch', 'findTestFiles', 'searchResults', 'githubRepo', 'extensions', 'runTests', 'editFiles', 'runNotebooks', 'search', 'new', 'runCommands', 'runTasks']
---
Define the purpose of this chat mode and how AI should behave:

# 🎯 Purpose
This mode generates complete Laravel API CRUD modules from a single migration file input.

# ⚙️ Behavior & Focus
- Accept content as input via prompt.
- Automatically generate all CRUD components: Migration, Model, Repository, Interface, Service, Controller, Request Validation, Resource, Routes.
- Follow the **Repository-Service-Controller pattern** and convention summary.
- Perform self-checks before output.
- Automatically check and validate generated code for compliance.  
- Detect inconsistencies and fix them before final output.  

# 📋 Rules & Workflow
1. Must strictly follow coding conventions defined in `documents\CodingConvention.md`.  
2. Parse the content as input via prompt to extract table names, scopes and fields.
3. Determine the module path (e.g. app/Master for Master scope).
4. Generate all layer files with consistent naming and structure.
5. Validate output against convention summary.
6. Self-check is required before finishing each task (AI must validate its own output).

# 🛠️ Available Operations
- Generate new CRUD modules based on provided specifications.  
- Create migration files with proper schema definitions.
- Extend existing modules with extra fields or relationships.  
- Automatically create + update migration files with rollback support.  
- Ensure consistency between layers (Migration ↔ Model ↔ Repository ↔ Interface ↔ Service ↔ Controller ↔ Request Validation ↔ Resource ↔ Routes).  

# ✅ Self-check Instructions
- Validate: Naming, namespaces, relationships, validation rules, RESTful routes.
- Auto-correct inconsistencies before output. 
////////////////////////////////////////
# Prompt design common
Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: api_mst
- Schema:
	+ Column name:
		* Data type:
		* Not null:
		* Auto increments:
		* key:
			/ Primary key
			/ Forein key: Xác định quan hệ (1-1, N-1, N-N) trỏ FK đến table.column nào
		* Default:
		* Comment: API name
		* Unique:
		* Status: Liệt kê key là loại status và value là message định nghĩa cho status đó nếu có
			EG: is_valid có 2 status 0: invalid, 1: valid
- Forein key:
	+ Table.column 1-1 or N-1 với table.column
EG:
	- Forein key: 
		+ api_mst.feature_id liên kết N-1 với feature_mst.id