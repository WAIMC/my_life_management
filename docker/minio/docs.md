################ KHỞI TẠO
khi sử dụng minio để quản lý dữ liệu media, tôi cần thực hiện việc đầu tiên là khởi tạo, thực hiện khi build env: 

- Tạo 2 bucket để lưu trữ dữ liệu:
	+ Official: lưu trữ dữ liệu dài hạn	
		* MinIO sử dụng dấu / để mô phỏng cấu trúc thư mục. Nếu dồn quá nhiều đối tượng vào 1 prefix duy nhất sẽ gây áp lực truy vấn list và head. Khuyến nghị giữ đối tượng <10.000 đối tượng/prefix. Có thể chia thành nhiều prefix theo năm/tháng/ngày hoặc theo hash của object id.
		* Do đó, setting lifecycle tự động move media xuống tier lưu trữ thấp hơn, các media này là các media ít được sử dụng or lâu rồi không sử dụng or tần xuất truy cập ít và không muốn xóa, di chuyển nó xuống tier thấp hơn nhứ SSD -> HDD or cloude rẻ để tối ưu chi phí lưu trữ, truy vấn. Toàn bộ giao tiếp với dữ liệu đều thông qua giao thức HTTP(S) restful.
    * Cần đánh dấu versioning để backup
    * Setting rule cho phép tồn tại file trong 30 ngày để có thể rollback, sau 30 ngày -> hard delete. 
    * Setting rule để dọn delete marker dư thừa
	+ Temp: là lưu trữ dữ liệu tạm thời
		* Bucket lưu trữ tạm thời setting lifecycle độc lập xóa dữ liệu tự động mỗi ngày, thường vài tiếng nó sẽ scan object với modified_time > 1 ngày -> xóa object
    * Không versioning để tiết kiệm chi phí lưu trữ vì không tạo delete marker, tự động clear dữ liệu mà không tồn rác

- Khi upload sẽ chia thành nhiều part để upload, mặc định, mọi multiparts upload bị hủy (không hoàn tất) sẽ tự động bị xóa sau 24H và tần xuất quét xóa mặc định là 6H -> Nếu không cần thay đổi thiết lập thì việc này cũng tự động rồi

- Cơ chế delete marker và xóa đối tượng: Nếu bucket bật tính năng versioning, thì mỗi khi xóa object đó chỉ là soft delete. Nó tạo delete marker để đánh dấu lại object đó. Client sẽ không nhìn thấy object đã xóa, nhưng thực tế chúng vẫn còn đang lưu trữ ở disk. Chức năng này có mục đích khôi phục dữ liệu, khi nhầm lẫn xóa object (do người dùng, lỗi logic delete) thì có thể khôi phục lại bằng cách xóa đánh dấu delete marker (current version). Vấn đề là object và delete marker lại không có liên kết ràng buộc lẫn nhau, nó tồn tại độc lập, nên khi xóa object thật vĩnh viễn thì delete marker vẫn còn tồn tại, lúc này delete marker là rác vì nó không đánh dấu cho object nào cả. Do đó cần setting rule để xóa vĩnh viễn delete marker. Vì bật tính năng versioning để cho mục đích khôi phục, nên cần setting rule như cái thùng rác, sẽ tự động xóa vĩnh viễn object sau x/ngày không khôi phục. Để đảm bảo quản lý, lưu trữ dữ liệu tối ưu.

- Setting IAM/policy để có thể có quyền thay đổi dữ liệu: upload, read-only, temp-only. Không dùng root access key cho app, giảm rủi do nhầm lẫn, tăng bảo mật.

- Tiering: là tính năng của minio để tự động di chuyển dữ liệu giữa các tier lưu trữ khác nhau, ví dụ: SSD -> HDD or cloude rẻ để tối ưu chi phí lưu trữ, truy vấn. hiện tại bỏ qua làm mặc định phần này, mount 1 disk, chạy MinIO docker single-node. Trong tương lai sẽ chia ra các tier khác nhau để lưu trữ.
  + Pool SSD (hot)
  + Pool HDD (warm): vd dữ liệu sau 90 ngày không sờ vào -> move xuống HDD
  + Hoặc remote S3 (cold): vd dữ liệu sau 1 năm không sờ vào -> move xuống S3

- Tương lai sẽ thực hiện sau các thành phần: Backup & Disaster Recovery, Monitoring & Alert


#################################################################################### Phân loại và cách thức xử lý từng loại dữ liệu

- Presigned url: là url tạm thời để truy cập vào file, có thời gian tồn tại, sau thời gian tồn tại sẽ tự động xóa. Nó ứng dụng cho các các thành phần có thể truy cập thao tác
mà không cần đang nhập hay có secrect key. Ứng dụng cho FE thao tác trực tiếp vói store. Phù hợp với tính năng one-shot: upload, download, delete, rename, move với các file nhỏ.
Thời gian xử lý thấp có thể ước lượng trước, không cần reconnect or retry.

- Đo tốc độ mạng, đo kích thước file, phân loại chức năng là: download, delete, rename, upload, move, streaming,... => tính thời gian xử lý + buffer thời gian = thời gian tồn tại presigned url
  + Việc này thực hiện theo điều kiện: dung lượng file <5mb, file loại hình ảnh, zip, docs, pdf,... thời gian tồn tại presigned url không quá x giây

- Với streaming : Client request -> BE xử lý, hỗ trợ range header -> store minio để lấy -> response client. Bắt buộc hỗ trợ http range

################# Vận hành với website (FE, BE, DB, Store)
- upload file lên store có 2 phương thức
	+ Gửi file kèm key/secrect lên store. BE xử lý dữ liệu và upload. Như vậy BE sẽ đảm nhận thêm logic xử lý, bị chiếm tài nguyên xử lý
	+ Tao url tạm thời để upload. Cách này bảo mật ko cần truyền key/secrect. Để FE xử lý, tài nguyên xử lý lấy từ máy user để tính toán và gửi dữ liệu

- Flow:
	+ Tạo serect key để cho phép hệ thống connect tới bảo mật. Tạo presignUrl tạm thời sau này. Set preurl có ttl là 5s để đảm bảo thu hồi nhanh chóng -> bảo mật. 
	+ FE validate: tên, loại file, dung lượng file <5MB, min max tên file,...
	+ FE call API BE để setup tạo url upload lên s3 tạm thời để client upload trực tiếp mà ko cần key/secrect.
		- Tạo presign uri tạm thời (set time life) cho bất kỳ ai ko có quyền IAM có thể action PutObject (upload file) lên bucket s3 theo path
		- Giúp giảm tải cho server, vì upload ko thông qua BE tốc độ xử lý nhanh hơn vì dùng tài nguyên của client để xử lý dữ liệu upload trực tiếp lên s3
		- Thực hiện connect, tạo path upload lên s3, presigned URL (path: địa chỉ upload) để upload này có thời gian tồn tại 5s đê bảo mật
	+ FE nhận kết quả thất bại thì thông báo lỗi, thành công thì lấy presigned tạm thời.
		- upload file trực tiếp từ client lên s3 thông qua url tạm thời
		- Submit form route('products.image.update') với uuid (là random id, làm tên folder trong path upload s3)
	+ FE nếu thành công, request submit form dữ liệu lên BE để lưu trữ dữ liệu
	+ BE xử lý dữ liệu thành công, sau đó lấy path đến các file lưu trữ tạm thời. Sau đó move các file lưu trữ tạm thời đó đến kho lưu trữ lâu dài. Cập nhật lại path connect lấy media.

####################################
- Đầu tiên check minio nằm trong nội bộ network docker thì có cho phép access từ nginx không ?
Nếu khác network docker, thì phải thêm access_key và secret_key cho mỗi request đến minio. (cái này phức tạp ít dùng)

- Client
  + Gửi request vd
    GET /media/stream/{file_id}
    Cookie: access_token
    Range: bytes=1048576-

- nginx
  + Proxy request đến api xác thực

- API
  + Xác thực token + check permission
  + return response status code
  + không response body, stream, gọi minio
  + Lấy request client bóc tách xử lý để lấy path đến minio, trả về header X-Object-Key: videos/2025/01/16/abc.mp4

- Nginx
  + Check response request status code
  + Nếu status code = 200, proxy request đến minio
  + Lấy header X-Object-Key từ response API, tạo và gửi request đến minio
  vd :  GET /my-bucket/videos/abc.mp4 HTTP/1.1
        Host: minio:9000
        Range: bytes=1048576-
        Authorization: AWS <access_key>:<signature>

      or 
      proxy_pass http://minio:9000/media/$object_key;

  range header giữ nguyên từ client
  + Nếu status code != 200, return response

- Minio:
  + Nhận request range
  + Tìm object
  + Đọc đoạn byte tương ứng
  + return response vd:
    HTTP/1.1 206 Partial Content
    Content-Range: bytes 1048576-2097151/987654321
    Content-Type: video/mp4
    body: <binary chunk>

- Nginx: trả vể client

- Client: nhận response -> browser tự xử lý video (bufer, decode, seek, play)
  HTTP/1.1 206 Partial Content
  Content-Type: video/mp4
  Accept-Ranges: bytes


################
- Các action stream
  + play: browser gửi request với range từ 0-
  + Pause: Browser tự ngắt TCP
  + Seek, next, back: Browser gửi request với range tương ứng
  + Resume: Browser gửi range tiếp
- Logic khác: multiple videp, playlist thì cần đổi src. Adaptive streaming (HLS/DASH)