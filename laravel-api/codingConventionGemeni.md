---

# Hướng dẫn Tối ưu: Tự động hóa Phát triển Laravel Tuân thủ Tiêu chuẩn với AI

Tài liệu này là một hướng dẫn thực hành, tập trung vào các bước hành động cụ thể để tận dụng AI, đặc biệt là GitHub Copilot, trong quá trình phát triển Laravel, nhằm đảm bảo tuân thủ nghiêm ngặt các quy ước mã hóa và kiến trúc tùy chỉnh.

## 1. Hiểu rõ AI: Trợ lý Mạnh mẽ, Không phải Tác nhân Tự động Hoàn toàn

Để sử dụng AI hiệu quả, hãy nắm vững các điểm cốt lõi sau:

- **AI là Trợ lý, không phải Tác nhân Tự động Hoàn toàn:** AI hỗ trợ tạo mã, nhưng cần sự hướng dẫn rõ ràng và xác thực liên tục từ con người để đảm bảo tuân thủ các quy tắc phức tạp, tùy chỉnh.
- **Giới hạn Ngữ cảnh và Token:** Các công cụ AI có giới hạn về lượng thông tin mà chúng có thể "ghi nhớ" và xử lý cùng lúc. Tài liệu quá dài hoặc thiếu thông tin có thể khiến AI bỏ lỡ yêu cầu hoặc lặp lại câu hỏi.
- **Ưu tiên Mẫu Phổ biến:** AI được đào tạo trên lượng lớn mã công khai và có xu hướng tạo ra các mẫu "phổ biến". Để tuân thủ các quy ước tùy chỉnh, nội bộ, bạn cần cung cấp hướng dẫn rất cụ thể.
- **Bộ nhớ Ngắn hạn:** "Bộ nhớ" của AI giống một bộ đệm ngắn hạn hơn là sự hiểu biết thông minh, bền vững về toàn bộ bộ quy tắc của dự án. Điều này đòi hỏi phải lặp lại các hướng dẫn quan trọng hoặc chia nhỏ tác vụ để AI có thể duy trì ngữ cảnh hiệu quả.

## 2. Kỹ thuật Thiết kế Prompt Tối ưu

Để tối đa hóa hiệu suất của AI, hãy áp dụng các chiến lược thiết kế prompt sau:

- **Viết Prompt Ngắn gọn, Chi tiết và Hướng hành động:**
  - **Cụ thể hóa Yêu cầu:** Sử dụng động từ hành động để chỉ rõ hành động mong muốn (ví dụ: "Tạo", "Viết", "Sửa đổi"). Xác định rõ định dạng đầu ra và mức độ chi tiết.
  - **Cung cấp Ví dụ:** Đưa các đoạn mã mẫu hoặc ví dụ đầu vào/đầu ra vào prompt để định hướng phản hồi của AI.
  - **Chia nhỏ Tác vụ Phức tạp:** Thay vì yêu cầu tạo toàn bộ module cùng lúc, hãy chia nhỏ thành các tác vụ nhỏ hơn (ví dụ: tạo migration, sau đó model, rồi repository, v.v.).
  - **Tránh Mơ hồ:** Sử dụng các thuật ngữ chính xác và tránh các tham chiếu không rõ ràng.

- **Tận dụng Tệp Hướng dẫn Tùy chỉnh (.github/copilot-instructions.md):**
  - **Chuyển đổi Quy ước:** Chuyển đổi các quy tắc cốt lõi từ CodingConvention.md sang tệp .github/copilot-instructions.md. Ưu tiên các quy tắc quan trọng nhất và thường xuyên áp dụng.
  - **Kích hoạt:** Đặt cài đặt `github.copilot.chat.codeGeneration.useInstructionFiles` thành `true` trong VS Code.

- **Quản lý Ngữ cảnh Đa tệp và Lặp lại:**
  - **Chỉ định Ngữ cảnh:** Mở các tệp liên quan, tô sáng mã, hoặc sử dụng `@workspace` (cho toàn bộ dự án đang mở) hoặc `#file:filename.ext` (cho các tệp cụ thể).
  - **Kiểm tra và Tinh chỉnh:** Nếu phản hồi ban đầu không như mong đợi, hãy lặp lại prompt, xóa các đề xuất không phù hợp hoặc yêu cầu sửa đổi cụ thể.
  - **Quản lý Lịch sử Chat:** Sử dụng các luồng chat mới cho các tác vụ mới để giữ lịch sử liên quan.

## 3. Lộ trình Hành động: Xây dựng Module Laravel Tuân thủ Tiêu chuẩn

Đây là kế hoạch hành động chi tiết, từng bước để xây dựng các module API trên Laravel tuân thủ CodingConvention.md.

### 3.1. Giai đoạn 1: Thiết lập Quy ước Ban đầu

- **Thiết lập Quy ước trong AI:**
  - **Hành động:** Chuyển đổi các quy tắc cốt lõi từ CodingConvention.md sang .github/copilot-instructions.md. Chia nhỏ các quy tắc phức tạp thành các câu lệnh đơn giản hơn.
  - **Lưu ý:** Tách các quy tắc thành rule common (cấu trúc thư mục chuẩn và quy ước chung) và rule thành phần (migration, model, controller, v.v.).

- **Thiết lập Ngữ cảnh Dự án:**
  - **Hành động:** Mở thư mục gốc của dự án trong VS Code để tận dụng ngữ cảnh `@workspace`.

### 3.2. Giai đoạn 2: Tạo Module Lặp lại (Tự động hóa có sự hỗ trợ của Con người)

Thực hiện lần lượt theo thứ tự ưu tiên cho các module (ví dụ: Master > Management > History/Master > History/Management). Trừ admin.

**Trong mỗi Module:**

- **Liệt kê và Quét tệp Migration:**
  - **Hành động:** Liệt kê các tệp migration trong thư mục module (ví dụ: `database/migrations/master/`). Trong mỗi tệp migration, quét nội dung để trích xuất tên bảng và các trường.

- **Vòng lặp Tạo Thành phần (theo từng Bảng):**
  - **Hành động:** Đối với mỗi bảng được xác định từ migration, thực hiện tạo (nếu chưa có) hoặc sửa lại (nếu đã tồn tại) các thành phần sau, tuân thủ các quy ước đã định nghĩa.

**Quy trình Mẫu cho một Thành phần (Ví dụ: Model):**

- **Xác định Table, Field:** (Ví dụ: bảng `products` với các trường `id`, `name`, `description`, `price`, `stock`).
- **Tạo Prompt cho Model:**
  - **Hành động:** Viết prompt ngắn gọn, chi tiết, dựa trên rule common và rule Model.
  - **Ví dụ Prompt:**
    > Tạo Eloquent Model `Product` trong thư mục `App/Modules/Master/Models` cho bảng `products`.
    > Model này phải có các thuộc tính `$fillable` cho `name`, `description`, `price`, `stock`.
    > Đảm bảo class và các phương thức công khai có JSDoc-style comment đầy đủ.
    > Tuân thủ các quy ước đặt tên và cấu trúc thư mục đã định nghĩa trong `.github/copilot-instructions.md`.
  - **Cung cấp Prompt này cho Copilot.**

- **Thu thập và Xác thực Đầu ra:** Kiểm tra tên folder, file, class name, nội dung theo quy ước.
- **Yêu cầu Chỉnh sửa:** Nếu đầu ra không đúng, cung cấp phản hồi cụ thể cho Copilot để sửa đổi.

- **Lặp lại tương tự cho các Thành phần khác:**
  - Repository: Dựa theo rule common và rule Repository.
  - Interface: Dựa theo rule common và rule Interface.
  - Service: Dựa theo rule common và rule Service.
  - Validate: Dựa theo rule common và rule Validate.
  - Controller: Dựa theo rule common và rule Controller.
  - Resource: Dựa theo rule common và rule Resource.
  - Route: Dựa theo rule common và rule Route.

- **Xem xét và Xác nhận Sample:**
  - **Hành động:** Yêu cầu tạo 1 bản sample để chính người dùng review và confirm lại các nội dung chỉnh sửa trước khi apply.
  - **Lưu ý:** Nếu bản sample đầu tiên được chấp nhận hoặc yêu cầu chỉnh sửa đã được ghi nhận và áp dụng, hãy bỏ qua bước này cho các lần lặp lại sau nếu nội dung tương tự.

### 3.3. Giai đoạn 3: Mở rộng và Tự động hóa Nâng cao (Chiến lược Dài hạn)

- **Điều phối bằng Script:**
  - **Hành động:** Phát triển một script phức tạp hơn để tự động hóa việc tạo các prompt, sử dụng công cụ đó để tương tác với AI khác. Script này sẽ quản lý luồng "Thực hiện lần lượt, tương tự cho các module khác một cách tự động".
  - **Mục tiêu:** Xây dựng một script tự động từ A-Z, thống kê, liệt kê chi tiết tỉ mỉ từng bước nhỏ nhất, từ đầu đến cuối. Điều này đòi hỏi kiến thức và kinh nghiệm chuyên môn sâu rộng để bao quát toàn bộ luồng xử lý tùy chỉnh phức tạp. Khi setup chuẩn chỉ, bao quát, các công cụ AI sẽ thực hiện tất cả các khâu một cách tự động, nhanh chóng, chính xác.

- **Fine-tuning LLM Tùy chỉnh:**
  - **Hành động:** Nếu khối lượng tạo mã tùy chỉnh cao và sự tuân thủ nghiêm ngặt là không thể thương lượng, hãy khám phá việc tạo một mô hình GitHub Copilot tùy chỉnh bằng cách đào tạo nó trên cơ sở mã hiện có, tuân thủ của tổ chức bạn.
  - **Lợi ích:** Đây là cách hiệu quả nhất để nhúng các quy ước cụ thể trực tiếp vào kiến thức của AI, giúp đạt được độ chính xác và tuân thủ gần như 100% cho các mẫu đã xác định.
  - **Lưu ý:** Việc fine-tuning trực tiếp nhúng các quy ước tùy chỉnh vào trọng số của mô hình, đây là giải pháp tối ưu nhất để giải quyết vấn đề "bộ nhớ ngắn hạn" của AI và đảm bảo sự tuân thủ nhất quán.

## 4. Kết luận

Việc tự động hóa phát triển Laravel với AI là một mục tiêu khả thi, nhưng đòi hỏi một cách tiếp cận có hệ thống và hiểu biết sâu sắc về khả năng của AI. Bằng cách:

- Nắm vững kỹ thuật thiết kế prompt: Cụ thể, chi tiết và hướng hành động.
- Tận dụng các tệp hướng dẫn tùy chỉnh: Để nhúng các quy tắc cốt lõi.
- Áp dụng lộ trình từng bước: Với sự xác thực và tinh chỉnh liên tục của con người.
- Đầu tư vào tự động hóa nâng cao và fine-tuning: Cho mục tiêu dài hạn.

Bạn có thể biến AI từ một trợ lý đơn thuần thành một công cụ mạnh mẽ, giúp tăng tốc độ phát triển và đảm bảo chất lượng mã, tuân thủ các tiêu chuẩn cao nhất của dự án.


https://www.youtube.com/watch?v=ualSK9XsZ4o&list=RDualSK9XsZ4o&start_radio=1&t=2654s
24:30