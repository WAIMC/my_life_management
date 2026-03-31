Truy cập màn hình http://localhost:81/admin/categories trên browser, nếu cần login thì nhập root/12345678. Thực hiện tạo category là AI, entry là Tổng quan AI, với entry desc như sau với parent và child như sau:


Gia phả của AI (Từ sơ khai đến hiện tại)
  AI (Trí tuệ nhân tạo): Là khái niệm rộng nhất. Bất cứ máy móc nào mô phỏng được hành vi thông minh của con người (nhìn, nghe, hiểu, suy luận) đều là AI.

  ML (Machine Learning - Học máy): Là cách để tạo ra AI. Thay vì lập trình cứng (if/else), ta ném cho máy tính một đống dữ liệu để nó tự tìm ra quy luật (giống như cách một đứa trẻ quan sát và học hỏi).

  DL (Deep Learning - Học sâu): Là một kỹ thuật ML "hạng nặng". Nó sử dụng Mạng nơ-ron (Neural Networks) nhiều lớp mô phỏng cấu trúc não người để giải quyết các dữ liệu cực kỳ phức tạp (nhận diện khuôn mặt, dịch máy).

  Generative AI (AI Tạo sinh): Một nhánh cực hot hiện nay. Nó không chỉ phân tích dữ liệu mà còn tạo ra nội dung mới (văn bản, hình ảnh, code) dựa trên những gì đã học.

  LLM (Large Language Model - Mô hình ngôn ngữ lớn): Là "bộ não" của Generative AI chuyên về chữ (như GPT-4, Gemini). Nó hiểu ngữ cảnh và giao tiếp y hệt con người.

  Tóm lại: LLM + Generative AI chính là công thức tạo ra các chatbot thông minh hiện nay. LLM lo phần "hiểu" yêu cầu, Generative AI lo phần "sinh" ra câu trả lời.

Tech Stack - Các "đồ nghề" xây dựng hệ thống
Để tạo ra một con AI biết đọc tài liệu nội bộ và tự tìm kiếm web (Hệ thống RAG - Retrieval-Augmented Generation), bạn cần các công cụ sau:

  Cơ sở dữ liệu & Không gian ngữ nghĩa:

    Vector Embeddings: Phép thuật biến "chữ" thành "tọa độ số". Hai câu có ý nghĩa giống nhau sẽ có tọa độ nằm gần nhau. Giúp AI tìm kiếm theo ngữ nghĩa thay vì khớp từ khóa. Các mô hình embedding hiện đại (như OpenAI text-embedding-3, Cohere, v.v.) biến văn bản thành vector từ 768 đến 3072 chiều. Nó biểu diễn hàng ngàn sắc thái ngữ nghĩa vi tế chứ không chỉ là không gian vật lý đơn thuần.

    ChromaDB / FAISS: Là "nhà kho" (Vector Database) lưu trữ và tìm kiếm các tọa độ số này với tốc độ chóng mặt. FAISS chỉ là thuật toán lõi (core index) chuyên để tính toán khoảng cách vector siêu nhanh. Còn ChromaDB là một Vector Database hoàn chỉnh, bên trong nó đã tích hợp sẵn thuật toán tìm kiếm (thường là HNSW, cũng tương tự FAISS

  Công cụ bên ngoài (Tools/MCP):

    Tavily: Công cụ search web "đo ni đóng giày" cho AI. Nó đọc web và trả về kết quả súc tích, không bị dính rác quảng cáo.

    MCP (Model Context Protocol): Giao thức chuẩn hóa giúp AI an toàn kết nối với dữ liệu ngoài (như gọi thẳng vào PostgreSQL để query).

  Framework điều phối:

    LangChain: Cung cấp các công cụ đóng gói sẵn để xây dựng các bước xử lý (Chains).

    LangGraph: Mở rộng từ LangChain, chuyên dùng để vẽ ra "sơ đồ chiến thuật" phức tạp (có điều kiện, rẽ nhánh, vòng lặp) thay vì chỉ chạy thẳng tuột một đường.

Cấu trúc bên trong một luồng xử lý (Workflow)
Để không bị nhầm lẫn khi code, bạn cần phân biệt rạch ròi 4 khái niệm này:

  Node (Nút): Là một chức năng độc lập (Hàm Python). Ví dụ: Node_Tim_Kiem_Web, Node_Cham_Diem.

  Chain (Chuỗi): Là luồng xử lý tuyến tính (A → B → C). Dùng cho các việc đơn giản: Đưa câu hỏi → LLM suy nghĩ → Trả kết quả format chuẩn.

  Graph (Đồ thị): Là luồng xử lý phi tuyến tính. Quản lý toàn bộ các Node và Chain, có quyền quyết định: nếu thiếu dữ liệu thì quay lại (loop), nếu đủ rồi thì đi tiếp (switch/route).

  Agent (Đặc vụ): Khác với code thông thường chạy theo quy luật chết, Agent dùng "bộ não" LLM để tự quyết định phải dùng công cụ nào (đọc file hay search web) dựa trên câu hỏi của người dùng.

Vòng đời của một câu hỏi (Agentic RAG Flow)
Đây là quá trình "chuẩn format" từ lúc người dùng gõ Enter đến khi có kết quả:

  Phân tích & Định tuyến (Router): * Người dùng nhập câu hỏi. LLM đánh giá: "Câu này hỏi về chính sách công ty (vào DB nội bộ) hay hỏi về thời tiết hôm nay (đi Search Web)?".

  Truy xuất (Retrieve / Web Search): * Lấy dữ liệu thô từ nguồn đã chọn.

  Chấm điểm tài liệu (Document Grader): * LLM kiểm tra: "Tài liệu vừa lấy lên có thực sự liên quan đến câu hỏi không?". Loại bỏ rác để tiết kiệm token. Nếu DB nội bộ không đủ thông tin, tự động rẽ nhánh sang Search Web.

  Tạo sinh (Generate): * Gom câu hỏi + Tài liệu sạch đưa cho LLM để viết câu trả lời.

  Kiểm định chất lượng (Quality Assessment): * Check Ảo giác (Hallucination): Câu trả lời có bịa ra thông tin không có trong tài liệu không? (Nếu có → Sinh lại).

  Check Tính hữu ích (Answer Grader): Câu trả lời có đúng trọng tâm người dùng hỏi chưa? (Nếu chưa → Đi search web thêm).

  Trả kết quả (Final Output): Đưa ra câu trả lời chính xác, có dẫn chứng.