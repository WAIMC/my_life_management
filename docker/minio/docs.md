### KHỞI TẠO

khi sử dụng minio để quản lý dữ liệu media, tôi cần thực hiện việc đầu tiên là khởi tạo, thực hiện khi build env: 

* Tạo 3 bucket để lưu trữ dữ liệu:
  * media-official: chứa dữ liệu riêng tư như thông tin cá nhân, tài liệu nhạy cảm, ...
    * Cần đánh dấu versioning để backup
    * Setting rule cho phép tồn tại file trong 30 ngày để có thể rollback, sau 30 ngày -> hard delete. 
    * Setting rule để dọn delete marker dư thừa
  * media-temp: chứa dữ liệu tạm thời, có set clear theo ngày
    * Bucket lưu trữ tạm thời setting lifecycle độc lập xóa dữ liệu tự động mỗi ngày, thường vài tiếng nó sẽ scan object với modified_time > 1 ngày -> xóa object
    * Không versioning để tiết kiệm chi phí lưu trữ vì không tạo delete marker, tự động clear dữ liệu mà không tồn rác

  * Lưu trữ media sẽ theo format: '{workspace}/{year}/{month}/{uuid}.{extension}';
  * vd: media-official/2026/01/16/abc.jpg
  * Riêng upload media temp sẽ theo format: 'bucket/{uuid}.{extension}';

* Note:
  * MinIO sử dụng dấu / để mô phỏng cấu trúc thư mục. Nếu dồn quá nhiều đối tượng vào 1 prefix duy nhất sẽ gây áp lực truy vấn list và head. Khuyến nghị giữ đối tượng <10.000 đối tượng/prefix. Có thể chia thành nhiều prefix theo năm/tháng/ngày hoặc theo hash của object id.
  * Do đó, setting lifecycle tự động move media xuống tier lưu trữ thấp hơn, các media này là các media ít được sử dụng or lâu rồi không sử dụng or tần xuất truy cập ít và không muốn xóa, di chuyển nó xuống tier thấp hơn nhứ SSD -> HDD or cloud rẻ để tối ưu chi phí lưu trữ, truy vấn. Toàn bộ giao tiếp với dữ liệu đều thông qua giao thức HTTP(S) restful.

* Khi upload sẽ chia thành nhiều part để upload. Mặc định, mọi multiparts upload bị hủy (không hoàn tất) sẽ tự động bị xóa sau 24H và tần xuất quét xóa mặc định là 6H -> Nếu không cần thay đổi thiết lập thì việc này cũng tự động rồi

* Cơ chế delete marker và xóa đối tượng: Nếu bucket bật tính năng versioning, thì mỗi khi xóa object đó chỉ là soft delete. Nó tạo delete marker để đánh dấu lại object đó. Client sẽ không nhìn thấy object đã xóa, nhưng thực tế chúng vẫn còn đang lưu trữ ở disk. Chức năng này có mục đích khôi phục dữ liệu, khi nhầm lẫn xóa object (do người dùng, lỗi logic delete) thì có thể khôi phục lại bằng cách xóa đánh dấu delete marker (current version). Vấn đề là object và delete marker lại không có liên kết ràng buộc lẫn nhau, nó tồn tại độc lập, nên khi xóa object thật vĩnh viễn thì delete marker vẫn còn tồn tại, lúc này delete marker là rác vì nó không đánh dấu cho object nào cả. Do đó cần setting rule để xóa vĩnh viễn delete marker. Vì bật tính năng versioning để cho mục đích khôi phục, nên cần setting rule như cái thùng rác, sẽ tự động xóa vĩnh viễn object sau x/ngày không khôi phục. Để đảm bảo quản lý, lưu trữ dữ liệu tối ưu.

* Setting IAM/policy để có thể có quyền thay đổi dữ liệu: upload, read-only, temp-only. Không dùng root access key cho app, giảm rủi do nhầm lẫn, tăng bảo mật.
  * Tạo file policy.json: Cho phép get, put, delete object phần resource bucket temp và official
  * Apply policy bằng lệnh mc policy add
  * Tạo user và apply policy bằng lệnh mc admin user add, đây chính là access key, secret key
  * Lấy giá trị này và tạo key + apply value vào file 
  * Kiểm tra có tồn tại /home/vinhdv/projects/my_life_management/laravel-api/.env thì tìm value access và secret key và thay thế value vào file .env, nếu không thì tạo mới access và secret key. Nếu không có file .env thì làm tương tự với file /home/vinhdv/projects/my_life_management/laravel-api/.env.example
  VD: cấu hình .env dùng key
    AWS_ACCESS_KEY_ID=backend-user
    AWS_SECRET_ACCESS_KEY=strong-backend-password
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=media
    AWS_ENDPOINT=http://minio:9000
    AWS_USE_PATH_STYLE_ENDPOINT=true
  * Thực hiện cấu hình trong file config/filesystem.php
    'disks' => [
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
    ],
  * Kiểm tra lại luồng xử lý back-end, refactor lại sử dụng key này để thao tác dũ liệu thông qua minio

* Tiering: là tính năng của minio để tự động di chuyển dữ liệu giữa các tier lưu trữ khác nhau, ví dụ: SSD -> HDD or cloude rẻ để tối ưu chi phí lưu trữ, truy vấn. hiện tại bỏ qua làm mặc định phần này, mount 1 disk, chạy MinIO docker single-node. Trong tương lai sẽ chia ra các tier khác nhau để lưu trữ.
  * Pool SSD (hot)
  * Pool HDD (warm): vd dữ liệu sau 90 ngày không sờ vào -> move xuống HDD
  * Hoặc remote S3 (cold): vd dữ liệu sau 1 năm không sờ vào -> move xuống S3

* Tương lai sẽ thực hiện sau các thành phần: Backup & Disaster Recovery, Monitoring & Alert


================================================

# Phân loại và cách thức xử lý từng loại dữ liệu

* Thao tác với dữ liệu trong store (minio):
  * Sử dụng key/secrect để thao tác với minio, thường dùng ở back-end để xử lý các thao tác với minio như delete, rename, move, list, copy, ... Đây là các chức năng yêu cầu bảo mật cao, xử lý không quá nặng.
  * Sử dụng presigned url để thao tác với minio, thường dùng ở FE để xử lý các thao tác với minio như download, upload. Các chức năng này yêu cầu bảo mật thấp, xử lý nặng để front-end xử lý, sử dụng tài nguyên ở FE, giảm tải xử lý cho back-end làm tối ưu hiệu năng.

* Presigned url: Là url tạm thời, có thời gian tồn tại, sau thời gian tồn tại sẽ tự động xóa. Nó ứng dụng cho các thành phần có thể truy cập thao tác mà không cần đang nhập hay có secret key. Ứng dụng cho FE thao tác trực tiếp vói store. Chỉ dùng cho 1 action duy nhất, 1 object or 1 part number, 1 method, không list, đọc object khác, không upload part or object khác. Phù hợp với tính năng one-shot: upload, download, delete, rename, move với các file nhỏ. Hoặc muti-part upload, download. không quá nguy hiểm nên có thể set TTL rộng hơn 1 chút có thể đặt 10p cho upload part nặng chờ lâu và set thời gian ngắn khoảng 5-10s cho file nhỏ.

* File upload nhỏ không cần reconnect or retry.

* Đối với các chức năng one shot không cần cân nhắc dung lượng file: rename, move, delete. Vì vậy khi tạo presigned url có thể set TTL ngắn hơn khoảng 5-10s.

* Đối với các chức năng: upload, streaming, multi-part upload, download, streaming,... cần tính toán dung lượng file, tốc độ mạng, thời gian xử lý để tính toán thời gian tồn tại presigned url.

* Với streaming : Client request -> BE xử lý, hỗ trợ range header -> store minio để lấy -> response client. Bắt buộc hỗ trợ http range

=================================================

# Steaming

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
  + Phóng to|thu nhỏ: css/player thực hiện
  + Chất lượng đồ họa: Player (HLS/DASH)
  + Âm Lượng: browser thực hiện
  + Thời giản phát: browser
  + Speed: Browser

- Logic khác: multiple videp, playlist thì cần đổi src. Adaptive streaming (HLS/DASH). Hầu hết các control video thì browser nó đã hỗ trợ sẵn, chỉ cần đảm bảo
gửi|nhận accept-ranges: bytes, trả đúng content-range, không buffer, stream ổn định thôi. Nếu cần control nhưng thứ đó có thể custom ở front-end

============================================
* Vấn đề upload:
  * Php/laravel không phù hợp để xử lý file, stream, dễ bottleneck, dễ lỗi 429, chậm.
  * Nguyên tắc: laravel/php (api), chỉ đóng vai trò xác thực, một số chức năng oneshot, xử lý lưu trữ quản lý dữ liệu metadata. Còn lại xử lý file, stream, multipart upload, virus scan, backup, cleanup, ... sử dụng các cơ chế khác để thực hiện như: batch, queue, worker, cron, ...
  * Dùng lifecycle AbortIncompleteMultipartUpload cho bucket temp ở minio, để thực hiện clean up các part không được commit complete tự động -> không cấu thành object hoàn chỉnh, thời gian xóa 24H.
  * Upload file trực tiếp từ client -> minio thông qua presigned url. Sẽ sử dụng tài nguyên của client để tính toán xử lý file.
  * Ram chỉ giữ lại 1 số part rất nhỏ cùng thời điểm, cpu xử lý ít tiêu thụ ít xử lý nhanh, Disk gần như không dùng. Do đó không cần kiểm tra tài nguyên của client, nếu ram thấp -> upload chậm, cpu không đáng kể, mạng yếu timeout cho retry
  * Nếu browser crash thì multipart bị bỏ dở => lifecyle sẽ clean các part dư thừa không commit complete.
  * Các part upload lền minio sẽ được lưu trữ tạm thời, sau khi commit complete sẽ được merge lại thành object hoàn chỉnh.

* Nguyên lý: Xử lý upload file nặng và multipart trên minio
  * File được chia nhỏ thành nhiều part, mỗi part sẽ được upload lên minio độc lập. 
  * Vì minio không biết khi nào việc upload đã hoàn thành hay chưa. Mục đích cho phép retry/replace part và tránh object thiếu part, object lỗi.
  * Nếu không complete thì object đó sẽ không tồn tại, part sẽ lưu tạm thời và sẽ bị cleanup sau 1 thời gian (lifecycle). Do đó multipart upload xong cần commit complete bằng cách gửi request CompleteMultipartUpload với payload: uploadId, part chứa partNumber và etag tương ứng. Việc còn lại minio sẽ kiểm tra hợp lệ payload, ghép các part theo thứ tự, tạo thành object hoàn chỉnh, xóa dữ liệu tạm. 
  * Bạn đóng vai trò là người xác nhận, commit confirm hoàn thành upload, đóng hòm xử lý.

* Có 2 hình thức upload qua presiged url: Multipart upload (upload part, part là các phần trong 1 file được chia nhỏ) và Single upload (upload 1 file nhỏ)

* Bản chất upload file là đính kèm luồng byte (binary stream) bên trong request và gửi thông qua HTTP. Với file nhỏ số byte ít, thời gian xử lý ngắn nên không bị timeout, tốc độ truyền tải dữ liệu mạnh khiến file nhỏ được xử lý rất nhanh fail thì retry lại dễ chịu hơn. Ngược lại file lớn, khiến request rất nặng do browser gửi file theo stream, OS + TCP stack từng phần nhỏ, request xử lý quá lâu bị timeout, thời gian xử lý lâu khiến user có thể thực hiện hành động khác nhau như reload, off, sleep,... khiến nó bị fail và việc retry lại cảm giác khi chịu. Việc upload 1 lần file nặng không tận dụng hết băng thông, khó control kết quả, UX tệ hại.

# Công thức ước lượng và tính toán
  * `Tính toán tốc độ upload để hiển thị cho user`
    * percent = (loaded/total) * 100
      * loaded: Tổng số byte của các part đã xong + số byte đang tải của các part hiện tại
      * Total: Tổng dung lượng file ban đầu (đơn vị: byte)

  * `Công thức tính toán thời gian thử lại`
    * t_delay = t_base * 2^n + random(0, 100)
      * t_base: thời gian chờ cơ bản (eg: 1000s)
      * n: số lần thử lại thất bại (0, 1, 2, 3)
      * random(0, 100): độ ngẫu nhiên, tránh việc nhiều máy, nhiều request thử lại cùng 1 thời điểm
      * Nếu n >= 3, dừng quá trình và báo lỗi

  * `Công thức tính số lượng part song song`
    | HTTP | C_http |
    |------|--------|
    | HTTP/1.1 | <= 6 |
    | HTTP/2 | 8 – 16 |
    | HTTP/3 | 16 – 32 |

  * `Công thức tính toán part size`
    | File size | S_spec |
    |-----------|--------|
    | <100 MB | ko multipart, dùng single upload object |
    | 100MB - 500 MB | max(16MB, ceil(FileSize / 10_000)) |
    | 500MB - 10GB | max(32MB, ceil(FileSize / 10_000)) |
    | 10 – 100 GB | max(64MB, ceil(FileSize / 10_000)) |
    | >100 GB | max(128MB, ceil(FileSize / 10_000)). 128MB chuẩn hiệu xuất, nếu kết quả lớn hơn 128MB thì lấy để đảm bảo nó không vượt qua giới hạn 10.000 part |

  * `Công thức tính TTL cho presigned url`
    | File size | TTL presign |
    |-----------|-------------|
    | ≤100MB | 300s |
    | 100MB–10GB | 60s |
    | 10GB–100GB | 60–120s |
    | >100GB | ≤120s |

  * Note: 
    * 16/32/64/128 MB: kích thước part tối thiểu cho từng loại file size, để đảm bảo tốc độ và sự ổn định.
    * FileSize: dung lượng file, đơn vị MB
    * 10.000: số lượng tối đa part 1 object, do store minio và s3 quy định
    * Multipart upload cho file lớn >100MB. Mỗi part >5MB trừ part cuối. Tối đa 5BG/part. Part size 16/32/64MB. Tối đa 10.000 part/file. File size tối đa 5TB
  
  * VD: 
    * 500MB. Công thức: max(16MB, ceil(500 / 10_000)) ~ < 16MB tiêu chuẩn -> lấy 16MB => 31,25part
    * 100GB -> 102.400MB. Công thức: max(64MB, ceil(102,400 / 10.000)) ~ 10 < tiêu chuẩn 64MB -> lấy 64MB => 1.600part

### Flow

- Client
  + Có thể dùng 1 request test để đánh giá tốc độ xử lý sau đó chọn giải pháp phù hợp.
  + Tính toán thời gian upload dự kiến để set ttl cho presigned url. Ví dụ: 100MB/s, file 10GB -> 100s -> set ttl 120s


================
* `Flow upload file nhẹ`
  * Minio: Tạo serect key để cho phép hệ thống connect tới bảo mật. Tạo presignUrl tạm thời sau này.

  * Client: 
    * Validate: Tên, loại file, dung lượng file min max, min max tên file,...
    * Kiểm tra nếu file size < 100MB thì thực hiện tiếp
    * Call api để setup presign url upload lên s3 tạm thời. Cho phép bất kỳ put object. Payload request chứa thông tin của file để chỉ định path lưu trữ (uuid làm tên của object, nó sẽ được lưu vào bucket temp)

  * Back-end:
    * Nhận request validate
    * Tạo presign url upload lên bucket temp. Set time life là 5p
    * Trả về response chứa presign url cho client

  * Client:
    * Nhận response nếu thất bại thì thông báo lỗi cho user
    * Nhận response nếu thành công thì call presign url để upload file lên minio, với payload chứa file dưới dạng blob
  
  * Minio:
    * Nhận request upload file, với payload chứa file dưới dạng blob
    * Upload file lên minio
    * Trả về response cho client

  * Client:
    * Nhận response nếu thất bại thì thông báo lỗi cho user
    * Nhận response nếu thành công thì call api để xử lý dữ liệu
    
  * Back-end:
    * Validate thông tin
    * Store thông tin
    * Move file mới upload từ bucket temp vào trong bucket chính. Tìm file trong bucket temp theo thông tin file lấy từ payload. Cập nhật thông tin file vào database
    * Return về kết quả cho client

  * Client:
    * Nếu thất bại thì thông báo lỗi cho user
    * Nếu thành công thì hiển thị thông báo kết quả upload cho user. Quá trình upload hiển thị process, pause, resume, cancel, retry.

* `Flow upload file nặng`
  * Minio: Tạo serect key để cho phép hệ thống connect tới bảo mật. Tạo presignUrl tạm thời sau này. Setup lifecycle AbortIncompleteMultipartUpload ở bucket temp, để thực hiện clean part  đã CreateMultipartUpload khi bị lỗi or chưa commit complete, sau 24H.

  * Client: 
    * Validate: Tên, loại file, dung lượng file min max, min max tên file,...
    * Kiểm tra nếu file size > 100MB thì thực hiện tiếp
    * Call api với payload: file name, file size, file type. Không gửi payload part.

  * Back-end:
    * Verify request, check quyền bằng middleware
    * Validate: Các thông tin request từ client
    * Lấy thông tin file từ payload
    * Tính toán số lượng part song song theo công thức `Công thức tính số lượng part song song`, mặc định là 6. Tính toán số lượng part theo công thức `Công thức tính toán part size`. Tính toán cho mỗi part theo công thức `Công thức tính TTL cho presigned url`
    * Generate UUID làm tên object để chỉ định tên lưu trữ trong store
    * Call CreateMultipartUpload minio với object id là uuid đã generate để tạo uploadID, mục đích tạo khoảng trống upload trong bucket temp. Dùng để chứa các part file. Dùng cho upload part, Resume, Complete, Abort.
    * Xử lý dữ liệu: 
    * Trả về response chứa: UploadID, objectID, partSize, partNumber, ttl, presignedUrl của từng part.
    * Vì browser giới hạn số lượng request đồng thời, nên số lượng part nhiều hơn giới hạn sẽ cần upload theo từng đợt. Nếu các part phía sau, có thời gian presined URL ngắn hơn thời gian upload của các part phía trước, thì ttl của part đó sẽ hết hạn và không được upload do lỗi. Vì vậy, các part được chia thành các đợt, mỗi đợt có số lượng part bằng giới hạn request đồng thời của browser. Mỗi đợt có thời gian presined URL bằng nhau và bằng thời gian upload của đợt đó.
    * Tạo presigned url tạm thời cho từng giới hạn 6 (giới hạn request đồng thời của browser), thời gian ttl 10p.
    mỗi part là 1 request độc lập. Không thể dùng 1 presigned url để upload nhiều part hoặc tạo nhiều presigned url cho cùng 1 part. 1 part = 1 presign Url. Vì để tránh 1 presigned url bị leak ra ngoài và gây ra vấn đề ghi vô hạn dữ liệu. Do browser giới hạn 6-15 connection/domain, nếu quá nhiều sẽ dễ throttle network, tăng latency -> để tầm khoảng 6-8 connection song song khi upload. Để tránh mạng kém, số lượng quá tải khiến xử lý chậm, presignUrl ttl ngắn sẽ hết hạn trước khi xử lý hết đặt TTL dài 10p.
    * Response về thông tin upload part cho client

  * Client:
    * Nhận response: Nếu có lỗi thì thông báo lỗi cho user, nếu thành công thì tiếp tục
    * Cắt file thành các part (byte-range), mỗi part có kích thước bằng nhau (trừ part cuối cùng), mỗi part có thông tin partNumber và partSize. Hệ thống này dùng ở web, dùng Blob.slice để cắt file,. Logic cắt part lấy thông tin từ response
    * Tạo metadata các part: part number, part size, etag, presigned url, số lần upload fail.
    * Tạo worker để xử lý upload part song song, giới hạn số lượng worker bằng giới hạn request đồng thời của browser. Đặt là 6. Tương đương upload 6 part song song
      * Kiểm tra part đó có presigned url không ? nếu có tiếp tục upload. Nếu không thì call api lấy presigned url, kết quả lỗi thì thông báo cho user, thành công thì xử lý tiếp.
      * Gửi request presigned url upload part từ medatadata của part đó, đính kèm part file vào body request. URL presigned http...&partNumber=x&uploadId=abc123. Đính kèm content-type vào header request nếu không sẽ bị minio reject. API tạo 1 api riêng chỉ để tạo presigned url upload part.
      * Nhận response
        * Nếu thành công đánh dấu kết quả, cập nhật etag từ header response và tiếp tục.
        * Nếu lỗi mạng/timeout/5xx thì kiểm tra, nếu part đó có upload fail đến lần thứ 3 thì dừng lại và thông báo lỗi cho user, còn không thì cập nhật tăng số lần upload fail của part đó và thử lại, chiến lược thử lại làm theo công thức `Công thức tính toán thời gian thử lại`. Để mong đợi mạng hoạt động ổn định trở lại. Nếu lỗi url 403 thì thông báo lỗi cho user và kết thúc.
      * Sử dụng sliding window, 1 slot hoàn thành, đánh dấu part done, lấy part tiếp theo trong pending queue đẩy part đó vào slot trống.
    * Khi hoàn tất upload tất cả các part thì tạo payload chứa: object id, upload id, part number, part size, etag. Gọi api với payload này.
    * Sử dụng process bar với công thức `Tính toán tốc độ upload để hiển thị cho user`. Hiển thị process, pause, resume, cancel, retry.

  * Back-end:
    * Validate: Payload gửi lên
    * Gọi CompleteMultipartUpload (không cần presigned URL) minio, với payload là payload của client gửi, để commit tất cả các part lại thành 1 file hoàn chỉnh.
    * Call service để scan virus file vừa upload xong. Nếu file bị nhiễm virus thì thông báo lỗi cho user và xóa file. `Cái này tương lai xử lý sau`.
    * Xử lý dữ liệu, store lại thông tin, move file mới upload từ bucket temp vào bucket chính official.
    * Response về kết quả cho client.
  
  * Client:
    * Nhận response từ back-end. Kết thúc xử lý và thông báo kết quả cuối cùng.

Note: 
* Trường hợp upload dở dang mà user reload, close tab, close browser, sleep, turn off,... thì sẽ không hoàn thành upload, các part đã upload sẽ không được merge thành file hoàn chỉnh. Nó sẽ được dọn dẹp bằng lifecycle của minio.

* Kiểm tra web server (nginx, apache, caddy, ...) dùng giao thức HTTP version bao nhiêu để chọn giải pháp xử lý request đồng thời theo công thức `Tính toán số lượng part song song`. Nhưng cũng cần cân nhắc đến khả năng xử lý của client, trình duyệt, thiết bị, đường truyền, ... để chọn giải pháp phù hợp.

===========
# ý nghĩa & nguyên lý hoạt động của một số công nghệ

* Băng thông: Năng lực xử lý tối đa của đường truyền.
  
  * EG: 100Mbps (100 mega bit per second) ~ 12.5 MB. Không dành riêng cho 1 request mà nó là tổng tài nguyên traffic.
    * Thời gian = dung lượng request / tốc độ
  
  * EG: dung lượng request 10Mb, tốc độ xử lý 10Mbps -> thời gian xử lý 1s. Header/tls/metadat rất nhỏ so với body nên có thể bỏ qua.
	
  * Cả 2 request chạy song song, băng thông chia kiểu gì không biết cảm giác như
  được chia đều, request xong trước giải phóng băng thông, băng còn dư sẽ sử dụng cho request đang thực hiện. 
  
  * Trường hợp đang có request chạy mà có request mới vào, thì request cũ vẫn chiếm phần lớn băng thông, request mới từ từ tính toán băng thông lại, nhưng không chia đều ngay. Rồi dần dần chúng mới lại. giống như chia đều băng thông.

* Tốc độ mạng: Tốc độ thực tế của đường truyền.

* Đơn vị đo lường file size:
  * 1 Byte (B) = 8 Bits
  * 1 KB (Kilobyte) = 1.024 Bytes
  * 1 MB (Megabyte) = 1.024 KB
  * 1 GB (Gigabyte) = 1.024 MB
  * 1 TB (Terabyte) = 1.024 GB
  * 1 PB (Petabyte) = 1.024 TB
  EG: MB (megabyte) = Mb (megabit) * 8

* Tốc độ mạng:
  * 4G (20-100 Mbps)
  * 5G (187-393 Mbps)
  * 1 MB (megabyte) = 8Mb (megabit)
