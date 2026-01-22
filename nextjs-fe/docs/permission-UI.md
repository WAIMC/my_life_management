- Yêu cầu ban đầu:
+ Mỗi role có thể quản lý: không có|1|nhiều api khác nhau. Feature độc lập so với role, nhiệm vụ của nó để trực quan theo dõi các api, nó là tên group của các api
+ Ở trên màn hình sẽ tích hợp cả role, api, feature để tránh phân mảnh chức năng, rườm rà, rắc rối ảnh hưởng trải nghiệm user.

- Vấn đề: Nhưng nội dung của tất cả chúng rất dài, để tránh sự nhàm chán, overload thông tin trong 1 lần, gây mệt mỏi cho user, thao tác nhầm,...

- Giải pháp: 
  1. Thiết kế theo dạng phân cấp (Hierarchy)
  + khi thao tác với role, đặt nó làm nội dung chính để user tập trung thao tác cơ bản với role. Để một toggle collapse để mở rộng cấu hình chi tiết hơn,
	toggle này sẽ hiển thị tất cả các feature, các feature này cũng đặt thành toggle collapse, khi click toggle collapse thì hiển thị list api của feature đó. Mỗi thành phần đều đặt checkbox theo từng level: all, all per feature, per api. Để giảm thao tác sử dụng, đơn giản thuận tiện, nhanh chóng hơn khi sử dụng.
	Tổ chức thế này all > feature > api, dễ theo dõi và hình dung cấu trúc.
  + Nội dung này sẽ chia làm 2 phần: Role và phân quyền chi tiết. Cả 2 nội dung này đểu có thể toggle collapse, mặc định ban đầu sẽ hiển thị phần collapse của role và ẩn permission
  + Phần nội dung permission sẽ cố định phần header có search nhanh theo group và api -> search group thì hiển thị tất cả api của group đó, search api
	thì hiển thị group của api đó và api đó, hiển thị tổng số group, api, số lượng api đã click, collapse all các item, collapse permission, kết hợp search filter
	theo method, feature, sort, đặt gợi ý search. Nội dung bên dưới có thể scroll
  + Các group feature mặc định là đóng, mỗi group đặt chú thích number/numer api của group đó được click chọn. Các api hiển thị tên và method. Tên in đậm, path hiển thị mờ, in nhỏ để user focus và hiểu nội dung chính, Đặt badged cho mỗi method để dễ nhận dạng
  + Dùng collapse hay accordion đều như nhau, để tiết kiệm thời gian, hiển thị phần nào thì xử lý tính toán dữ liệu gender cho component đó, thêm step confirm apply change
	để tránh thao tác nhầm. Cố định hiển thị phần header và các button footer còn nội dung bên trong thì scrolll, đổi lại loại dialog modal to hơn để dễ thao tác tổng thể hơn.
	Sử dụng kỹ thuật Virtual Scrolling (hoặc Windowing) để hiển thị các thành phần cần thiết hiển thị các thành phần bị ẩn sẽ xóa đi để tiết kiệm tài nguyên và đáp ứng được hiển thị
	lượng lơn dữ liệu -> trải nghiệm mượt mà,

  2. Thiết kế theo ma trận (matrix)
  Cấu trúc thiết kế dạng ma trận (matrix), phù hợp với hệ thống chúc năng cố định và đồng nhất. vd: full crud, mỗi hàng đặt là feature/api, cột là crud để checkbox, mô hình hệ thống kiểu vậy
	thiết kế dạng matrix là tối ưu nhất.

  3. Thiết kế theo kiểu đóng gói phân chia vùng view + action (split-pane)
  master-nav và deatil-action: Nhiệm vụ bên trái điều hướng, bên phải action chi tiết
  + Header: Hiển thị số lượng api đã chọn/tổng số api.
  + Body Bên trái: Rộng 30%. Chứa danh sách feature theo chiều dọc. Nếu api được check thì tìm group feature, hiển thị background màu khác so với các feature không có api nào thuộc nó được check. Có search theo tên feature, sort. Khi click feature nào thì body bên trái redirect tới vị trí group feature tương ứng và ngược lại thao tác api thì focus đến feature tương ứng.
  + Body bên phải: Rộng 70 %. Chứa danh sách api và được group bằng feature, checkbox feature theo chiều dọc. Checkbox feature sẽ làm toggle checkbox all api của nó, mỗi api đều có checkbox riêng. Header body bên phải có các thành phần sort, search theo api, filter theo method. Nội dung api group feature chỉ có tên thôi. Nội dung api bao gồm: baged method theo loại, tên api chữ in đậm và fort chữ to, path api hiển thị fort chữ nhỏ màu mờ hơn. Khi search hiển thị các api tương ứng và vẫn như group feature của nó
  + Nội dung body không dài vượt modal parent, phần dư thừa cho phép scroll cả hai bên
  + Sử dụng modal or dialog rộng nhất.

  4. Lý do lựa chọn
  + lối thiết kề đóng gói phân chia (Split-Pane) so với lối thiết kế phân cấp (Hierarchy), số lượng thao tác giảm thiểu, tìm kiếm điều hướng và action dễ focus hơn vì nội dung chia ra rõ ràng hơn.	Khi cần tổng quan or chi tiết dễ nhìn hơn. 
  + Matrix :Nhưng hệ thống linh hoạt không đồng bộ, đồng nhất thì khác, như chức năng active mail -> chỉ có 1 api check, tất cả các cell trong column đó bỏ không.

- Tổng kết:
  + Thiết kế build luồng xử lý thành các step, sử dụng Wizard UI để chia flow thao tác màn hình, cụ thể các step:
    * Step 1: Màn hình setup role
    * Step 2: Màn mình setup permission, cho phép skip step này
    * Step 3: Hiển thị tất cả thông tin thay đổi, user confirm cuối cùng
  - Trong quá trình thực hiện tất cả các step, bất kỳ action thoát or hủy thì hiển thị diaglog để user confirm hủy thay đổi, hủy thì tắt modal đi, nếu không thì giữ nguyên như hiện tại. Ở cuối step khi user submit thì hiển thị diaglog cho user confirm xác nhận thay đổi dữ liệu
