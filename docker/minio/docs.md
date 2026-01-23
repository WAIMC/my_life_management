################ KHỞI TẠO
khi sử dụng minio để quản lý dữ liệu media, tôi cần thực hiện việc đầu tiên là khởi tạo, thực hiện khi build env: 

- Tạo 3 bucket để lưu trữ dữ liệu:
  + media-official: chứa dữ liệu riêng tư như thông tin cá nhân, tài liệu nhạy cảm, ...
    * Cần đánh dấu versioning để backup
    * Setting rule cho phép tồn tại file trong 30 ngày để có thể rollback, sau 30 ngày -> hard delete. 
    * Setting rule để dọn delete marker dư thừa
  + media-temp: chứa dữ liệu tạm thời, có set clear theo ngày
    * Bucket lưu trữ tạm thời setting lifecycle độc lập xóa dữ liệu tự động mỗi ngày, thường vài tiếng nó sẽ scan object với modified_time > 1 ngày -> xóa object
    * Không versioning để tiết kiệm chi phí lưu trữ vì không tạo delete marker, tự động clear dữ liệu mà không tồn rác

  + Lưu trữ media sẽ theo format: '{workspace}/{year}/{month}/{uuid}.{extension}';
  vd: media-official/2026/01/16/abc.jpg
  + Riêng upload media temp sẽ theo format: 'bucket/{uuid}.{extension}';



- Note:
  + MinIO sử dụng dấu / để mô phỏng cấu trúc thư mục. Nếu dồn quá nhiều đối tượng vào 1 prefix duy nhất sẽ gây áp lực truy vấn list và head. Khuyến nghị giữ đối tượng <10.000 đối tượng/prefix. Có thể chia thành nhiều prefix theo năm/tháng/ngày hoặc theo hash của object id.
  + Do đó, setting lifecycle tự động move media xuống tier lưu trữ thấp hơn, các media này là các media ít được sử dụng or lâu rồi không sử dụng or tần xuất truy cập ít và không muốn xóa, di chuyển nó xuống tier thấp hơn nhứ SSD -> HDD or cloud rẻ để tối ưu chi phí lưu trữ, truy vấn. Toàn bộ giao tiếp với dữ liệu đều thông qua giao thức HTTP(S) restful.

- Khi upload sẽ chia thành nhiều part để upload. Mặc định, mọi multiparts upload bị hủy (không hoàn tất) sẽ tự động bị xóa sau 24H và tần xuất quét xóa mặc định là 6H -> Nếu không cần thay đổi thiết lập thì việc này cũng tự động rồi

- Cơ chế delete marker và xóa đối tượng: Nếu bucket bật tính năng versioning, thì mỗi khi xóa object đó chỉ là soft delete. Nó tạo delete marker để đánh dấu lại object đó. Client sẽ không nhìn thấy object đã xóa, nhưng thực tế chúng vẫn còn đang lưu trữ ở disk. Chức năng này có mục đích khôi phục dữ liệu, khi nhầm lẫn xóa object (do người dùng, lỗi logic delete) thì có thể khôi phục lại bằng cách xóa đánh dấu delete marker (current version). Vấn đề là object và delete marker lại không có liên kết ràng buộc lẫn nhau, nó tồn tại độc lập, nên khi xóa object thật vĩnh viễn thì delete marker vẫn còn tồn tại, lúc này delete marker là rác vì nó không đánh dấu cho object nào cả. Do đó cần setting rule để xóa vĩnh viễn delete marker. Vì bật tính năng versioning để cho mục đích khôi phục, nên cần setting rule như cái thùng rác, sẽ tự động xóa vĩnh viễn object sau x/ngày không khôi phục. Để đảm bảo quản lý, lưu trữ dữ liệu tối ưu.

- Setting IAM/policy để có thể có quyền thay đổi dữ liệu: upload, read-only, temp-only. Không dùng root access key cho app, giảm rủi do nhầm lẫn, tăng bảo mật.
  + Tạo file policy.json: Cho phép get, put, delete object phần resource bucket temp và official
  + Apply policy bằng lệnh mc policy add
  + Tạo user và apply policy bằng lệnh mc admin user add, đây chính là access key, secret key
  + Lấy giá trị này và tạo key + apply value vào file 
  + Kiểm tra có tồn tại /home/vinhdv/projects/my_life_management/laravel-api/.env thì tìm value access và secret key và thay thế value vào file .env, nếu không thì tạo mới access và secret key. Nếu không có file .env thì làm tương tự với file /home/vinhdv/projects/my_life_management/laravel-api/.env.example
  VD: cấu hình .env dùng key
    AWS_ACCESS_KEY_ID=backend-user
    AWS_SECRET_ACCESS_KEY=strong-backend-password
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=media
    AWS_ENDPOINT=http://minio:9000
    AWS_USE_PATH_STYLE_ENDPOINT=true
  + Thực hiện cấu hình trong file config/filesystem.php
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
  + Kiể tra lại luồng xử lý back-end, refactor lại sử dụng key này để thao tác dũ liệu thông qua minio

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
  + Phóng to|thu nhỏ: css/player thực hiện
  + Chất lượng đồ họa: Player (HLS/DASH)
  + Âm Lượng: browser thực hiện
  + Thời giản phát: browser
  + Speed: Browser
- Logic khác: multiple videp, playlist thì cần đổi src. Adaptive streaming (HLS/DASH). Hầu hết các control video thì browser nó đã hỗ trợ sẵn, chỉ cần đảm bảo
gửi|nhận accept-ranges: bytes, trả đúng content-range, không buffer, stream ổn định thôi. Nếu cần control nhưng thứ đó có thể custom ở front-end


################
- Upload file nặng lên store (minio)
  + php/laravel không phù hợp cho streaming file lớn, dễ trở thành bottleneck, dễ gặp lỗi 429, chậm
  + Nguyên tắc: laravel or api ở đây chi đóng vai trò xác thực, việc upload file nặng hay streaming file sẽ thực hiện trực tiếp từ client -> strore (minio) thông qua presigned url đã được xác thực. laravel/api sẽ lưu thông tin metadata để sau này dễ quản lý
  + Browser sẽ chia file thành các chunk nhỏ, gửi song song, gửi request upload lên minio thông qua presigned url, minio sẽ lưu các chunk này vào ổ cứng, sau khi nhận đủ các chunk, minio sẽ merge các chunk lại thành file

  -> nếu không xử lý thành công làm sao tôi có thể clean up các chuck này ở minio tự động ?
  Việc để browser chia file thành các chuck nhỏ sẽ tiêu tốn tài nguyên xử lý của máy user, điều này giảm tải cho server, vấn đề là làm thế nào để ngăn ngừa user upload những thứ ngoài phạm vi tài nguyên xử lý của máy user ? tôi có cần check file size và tài nguyên ram + cpu của máy user trước khi quyết định có cho phép upload không ? quá trình chia file thành các chuck nhỏ diễn ra như thế nào ? tôi đảm nhiệm làm các công việc gì trong giai đoạn này ? QUá trình xử lý bị lỗi ở các khâu, các bước khác nhau thì tôi xử lý thế nào ? Vậy presigned url đặt thời gian tồn tại là bao nhiêu cho hợp lý, sát thời gian upload hoàn tất nhất ? tôi có thể tính toán thời gian đó như thế nào ? tôi cần làm gì khi quá trình upload bị gián đoạn or bị lỗi or bị hủy từ nhiều tác nhân khác nhau: mạng, tốc độ xử lý tài nguyên chậm, các thao tác trên browser reload, tab, tắt trình duyệt, ,....

  - User gửi file -> validate front-end -> validate back-end -> return presigned url -> front-end upload file lên minio thông qua presigned url 

  #######################
- Dùng lifecycle AbortIncompleteMultipartUpload ở minio, để thực hiện clean up các chunk thừa tự động. Thời gian xóa 24H, để xóa
các chuck dư thừa, cái này khác với object hoàn chỉnh
- upload file trực tiếp từ clien -> minio sẽ sử dụng tài nguyên của client để xử lý việc chia file thành các chuck nhỏ và upload.
Ram chỉ giữ lại 1 số chuck rất nhỏ cùng thời điểm, cpu xử lý ít tiêu thụ ít xử lý nhanh, Disk gần như không dùng. Do đó không cần
kiểm tra tài nguyên của client, nếu ram thấp -> upload chậm, cpu không đáng kể, mang yếu timeout cho retry, browser crash thì
multipart bị bỏ dở => nếu user retry sẽ lọc ra các chuck chưa hoàn thành để thử lại đến khi nào thành công thì thôi, nếu user không
tiếp tục thì các multipart đó sẽ là rác và minio đã setting lifecycle để clean up các multipart đó. Nhưng để đảm bảo trải nghiệm,
tối ưu xử lý, dung lượng lưu trữ tối thiểu, cần đặt giới hạn cho dung lượng file upload tùy theo hạng mục phát triển, như hệ thống
hiện tại chỉ lưu trữ video stream dưới 1H -> quy ra dung lượng tối đa là 1G để giới hạn. Việc upload chuck giới hạn upload chuck
song song là 1-3 chuck, với mỗi chuck có kích thước tối đa là 5M
- Chuck là logical slice của file, minio sẽ lưu các chunk này vào ổ cứng, sau khi nhận đủ các chunk, minio sẽ merge các chunk lại thành file
browser chỉ đọc từng đoạn byte và gửi lên minio thông qua presigned url, chuck không copy tất cả file vào ram.
- Hầu hết webapp chia chuck theo kích thước file, khuyến nghị

|File size|Chunk size|
|---|---|
|< 100MB|5–10MB|
|100MB – 1GB|10–25MB|
|1GB – 10GB|25–50MB|
|> 10GB|50–100MB|

- Upload song song giới hạn số chuck

|Thiết bị|Concurrency|
|---|---|
|Mobile|1|
|Laptop yếu|1–2|
|Desktop phổ thông|2–3|
|Desktop mạnh|3–4|

- Để tận dụng tài nguyên xử lý upload, dùng Dynamic queue / sliding window upload. Cách hoạt động giữ tối đa N upload đang chạy. Khi 1 chuck upload xong -> đẩy chuck tiếp theo vào queue, số lượng queue tối đa là số lượng Chuck song song. Khi 1 queue thực hiện xong thì kiểm tra queue khác còn stack thì lấy stack cuối cùng của queue có stack nhiều nhất qua để xử lý. Sử dụng web worker để không blocl UI, thực hiện tạo chuck và upload bên trong.

- Các multipart chuck được đẩy lên minio, sẽ được minio tự động merge và tạo thành object hoàn chỉnh. Nên không cần can thiệp.
- Các chuck upload thành công or thất bại sẽ được đánh dấu lại trong ram client, khi upload chuck thất bại sẽ tự động retry lại. Response trả về lỗi chuck dạng lỗi không thể retry được thì thống báo lỗi và ngừng xử lý, còn response những kiểu có thể retry thì mới retry lại. Khi hoàn thành công việc clear ram liên quan đến upload

- Quá trình upload hiển thị process, pause, resume, cancel, retry.

- Lưu trạng thái ở indexedDB để resume (uploadId, uploaded parts, ETags).

- Nêu tất cả thành công call api đê store lại thông tin metadata

- 5) Xử lý lỗi trên các bước khác nhau
  + Bước tạo presigned / start upload (BE):
  + Lỗi: auth fail, validation fail → trả 4xx cho FE, FE hiển thị message.
  + Nếu backend tạo presigned thất bại: log & trả lỗi, không khởi tạo uploadId.

  + Bước upload part (FE → presigned URL):
  + Lỗi mạng/timeouts/5xx: retry tự động với exponential backoff (ví dụ 3–5 lần). Nếu vẫn fail => pause and surface error (allow user resume).
  + Nếu presigned URL expired (403): FE phải request presigned url mới cho part đó (thiết kế endpoint /presign/part).

  + Bước complete (FE → BE → MinIO):
  + Lỗi: missing parts, ETag mismatch → BE trả lỗi, FE có thể re-upload missing parts rồi retry complete.

  + Bước abort (user cancel / timeout):
  + Gọi BE abort endpoint → BE gọi AbortMultipartUpload; cũng nên gọi mc hoặc cron cleanup để giải phóng storage.

  - Edge cases:
  + Partial success: một số parts uploaded, complete failed → uploadId vẫn có parts trên server; xử lý bằng retry complete, hoặc abort and reupload.
  + Race conditions: nếu nhiều client cùng upload same object key, enforce unique object keys (e.g., use GUID + user id + timestamp).

- Tính toán thời gian tồn tại presigned url cho hợp lý: 
Công thức ước lượng:
  1. estimated_seconds = file_size_bytes / (parallel_connections * expected_bytes_per_second_per_connection)
  2. expiry_seconds = estimated_seconds * safety_factor + fixed_margin
    + safety_factor: 1.5 → 3 (tùy quan sát độ ổn định).
    + fixed_margin: 60–300s (1–5 phút) để xử lý complete/handshake.

  Ví dụ:

    File 10 GB = 10 * 1024 MB = 10,240 MB. Nếu bạn upload 4 connection, mỗi connection trung bình 5 MB/s: tổng throughput = 20 MB/s → thời gian ≈ 10240 / 20 = 512s ≈ 8.5 phút.

    Nếu dùng safety_factor = 2 → expiry ≈ 17 phút + margin 3 phút → ~20 phút. (Bạn có thể đặt 30 phút để an toàn.)

    Quy tắc thực tế:

    Small files (<100MB): presigned 5–15 phút ok.

    Medium (100MB–2GB): 15–60 phút.

    Very large (>2GB): 1–4 giờ (hoặc dùng per-part presign so each part has shorter expiry).

    Or đặt presigned url có thời gian tồn tại ngắn, trong quá trình upload - thời gian ước lượng > thời gian tồn tại, thì thực hiện call api để lấy presigned url mới, lấy presigned url mới để upload tiếp tục.

- 



7) Nếu upload bị gián đoạn / bị lỗi / user reload / close tab

Resume (best practice):

Store upload state trên client (IndexedDB): uploadId, partsUploaded[] (partNumber + ETag), file meta (name, size), lastModified.

Khi trang load lại: nếu tìm thấy state, FE hỏi BE: GET /uploads/:uploadId/status → BE trả parts already uploaded (call ListParts if needed) → resume from next part.

Nếu uploadId expired/stale: BE trả lỗi → FE có thể restart new multipart upload and resume from beginning (or inform user).

Network offline:

Detect via navigator.onLine and online/offline events; on offline → pause uploads; on online → resume automatically.

Tab close / browser crash:

Persist state immediately to IndexedDB after every successful part upload.

On reopen, lookup state & call BE to resume.

Server-side action on long interruption:

If uploadId older than threshold and not completed, cleanup via cron/abort as in §1.

User cancels:

Provide a Cancel button that calls backend abort endpoint and clears client state.

8) Một số khuyến nghị cấu hình và thông số

Chunk size khuyến nghị: 5–50MB; 10–25MB là trade-off tốt giữa overhead và memory usage.

Concurrency: 3–6 parallel uploads; nếu device low-memory or mobile, set 1–3.

Retries: 3–6 attempts per part, exponential backoff (base 500–1000ms).

Progress reporting: track bytes uploaded / total bytes (sum of finished part sizes + uploading part progress).

Throttle: detect navigator.connection.effectiveType and throttle concurrency & chunk size if 2g or slow.

9) Các tác vụ bạn (developer) cần đảm nhiệm trong giai đoạn này

Backend (Laravel):

Implement endpoints: startMultipart, presignPart(optional), completeMultipart, abortMultipart, statusMultipart.

Lưu uploadId, metadata, parts uploaded, timestamps.

Worker/cron để abort stale uploads (và script mc rm -I nếu muốn).

Validation: file size, mime, quota, rate-limit.

Optional: virus-scan worker after complete before marking file ready.

Frontend (Next.js):

Build chunking/upload logic, concurrency, retry, web worker.

Persist state to IndexedDB for resume.

UI for progress, pause/resume/cancel, warnings for close tab.

Optionally measure throughput early (upload small probe chunk) to estimate expected speed and choose expiry.

Ops:

Configure MinIO retention/cleanup policy or schedule mc cron job.

Monitor storage used by incomplete parts and alert.

10) Tổng kết ngắn — checklist triển khai

Use multipart upload cho file lớn.

Frontend: chunking (File.slice), web worker, IndexedDB resume, retry/backoff, adaptive concurrency.

Backend: createMultipartUpload → return uploadId (và presign urls), completeMultipartUpload, abortMultipartUpload, status endpoint. Store metadata.

Cleanup: cron job + mc rm -I hoặc programmatic abort cho uploadId cũ. (Không dựa hoàn toàn vào bucket lifecycle MinIO vì version/compat khác nhau.)

Presigned expiry: tính theo bandwidth estimate + safety factor; prefer presign-per-part nếu có khả năng.

Không block người dùng bằng kiểm tra CPU/RAM cứng; dùng navigator.deviceMemory/hardwareConcurrency/connection làm hint để điều chỉnh UX.