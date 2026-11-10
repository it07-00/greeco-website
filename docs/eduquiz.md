# GREECO EduQuiz

## Sử dụng

- Quản trị viên đăng nhập tại **/admin/login** và chọn **Quản lý trắc nghiệm**. Giảng viên đăng nhập tại **/giang-vien/login**. Giảng viên quản lý câu hỏi, bộ đề, phiên và báo cáo của mình. Quản trị viên có quyền xem tất cả và chỉnh thời hạn lưu giữ.
- Soạn câu hỏi hoặc nhập tệp Excel/CSV từ tệp mẫu trong **Ngân hàng câu hỏi**. Tạo bộ đề, sắp xếp câu hỏi rồi gán vào một khóa đào tạo GREECO nếu cần.
- Có thể xóa từng mục hoặc xóa tất cả câu hỏi, bộ đề trong phạm vi quản lý. Xóa tất cả áp dụng cả với mục ngoài bộ lọc hiện tại. Xóa bộ đề giữ lại ngân hàng câu hỏi. Xóa câu hỏi sẽ bỏ câu đó khỏi các bộ đề; bộ đề trống cần được thêm câu hỏi trước khi mở phòng mới. Cả hai thao tác giữ nguyên các phòng đã tạo và kết quả.
- Tại **Phòng thi**, chọn trực tiếp hoặc tự làm. Phiên giữ bản chụp của bộ đề, gồm thứ tự, nội dung, đáp án và thời gian tại lúc tạo. Dùng mã PIN hoặc QR để mời học viên.
- Trang học viên tại **/eduquiz**. Phiên thông thường dùng họ tên và email hoặc số điện thoại. Phiên chính thức có thể yêu cầu mã mời cá nhân. Giảng viên tạo mã gắn với một thông tin liên hệ và gửi riêng cho học viên. Mã chỉ sử dụng được một lần để vào phòng.
- Phiên trực tiếp có một lượt cho mỗi thông tin liên hệ đã chuẩn hóa. Phiên tự làm cho phép số lượt do giảng viên cấu hình. Học viên dùng lại trình duyệt đã tham gia để tiếp tục hoặc làm lại. Đồng hồ tự làm bắt đầu khi vào phòng hoặc bắt đầu lượt mới.
- Báo cáo hỗ trợ tìm học viên, lọc theo khóa, phiên và ngày tạo phiên theo giờ Việt Nam. PDF hỗ trợ tối đa 500 dòng mỗi lần xuất. Excel dùng chung bộ lọc.
- Khi tổ chức xong, giảng viên chọn **Kết thúc phiên** để đóng liên kết, chốt các lượt còn mở và bắt đầu tính thời hạn lưu giữ. Mặc định sau 365 ngày, tác vụ định kỳ xóa họ tên, thông tin liên hệ và mã mời, thu hồi cookie tham gia, giữ lại điểm và đáp án đã ẩn danh. Admin có thể ẩn danh sớm từng phiên đã kết thúc.
- Trong **Phòng thi**, có thể xóa từng phòng hoặc xóa tất cả phòng trong phạm vi quản lý. Xóa phòng vô hiệu hóa PIN và liên kết. Lượt đang làm được chốt từ đáp án đã lưu, kết quả vẫn xem được trong báo cáo, và thời hạn lưu giữ vẫn áp dụng.

## Triển khai thử

1. Dùng URL staging HTTPS và cơ sở dữ liệu staging riêng. Cấu hình session, cache và lưu trữ theo môi trường. Mã QR tạo từ URL thực tế của phòng.
2. Chạy **composer install** theo lock file rồi chạy **php artisan migrate** trước khi mở bản phát hành. Migration EduQuiz tạo nhóm bảng riêng, tham chiếu các bảng users và courses hiện có. Ảnh câu hỏi lưu trên disk local riêng tư, cần giữ thư mục storage khi cập nhật bản phát hành.
3. Đảm bảo máy chủ chạy **php artisan schedule:run** mỗi phút. Tác vụ EduQuiz chốt lượt tự làm quá hạn mỗi phút và ẩn danh dữ liệu đến hạn hằng ngày. Khi học viên đang mở bài, API cũng chốt lượt ngay khi phát hiện hết giờ.
4. Tạo tài khoản giảng viên bằng **php artisan eduquiz:instructor email@example.com**. Lệnh hỏi họ tên và mật khẩu, rồi tạo tài khoản cùng quyền giảng viên trong một giao dịch.
5. Kiểm thử từ hai trình duyệt và điện thoại. Kiểm tra QR, tiếp tục sau tải lại trang, dừng/tiếp tục đồng hồ, công bố đáp án, tự nộp khi hết giờ, quyền truy cập chéo, Excel và PDF có dấu tiếng Việt. Chạy **php artisan test --filter=EduQuizTest** để kiểm tra nghiệp vụ.
6. Phòng trực tiếp cập nhật bằng polling. Khi tổ chức lớp đông, đo tải trên staging với số thiết bị dự kiến. Các thao tác ghi dữ liệu của một phiên được tuần tự hóa tại database để chấm điểm và kiểm tra thời hạn nhất quán.

Chỉ triển khai production sau khi bản staging được phê duyệt.

Khi hoàn nguyên về phiên bản trước EduQuiz, vô hiệu hóa tài khoản giảng viên trước khi mở lại bản cũ vì bản cũ coi mọi tài khoản trong bảng users là quản trị viên.

