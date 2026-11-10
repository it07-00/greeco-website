# Hướng dẫn sử dụng GREECO EduQuiz

Quy trình tổ chức một bài trắc nghiệm gồm **soạn câu hỏi → lập bộ đề → tạo phòng → mời học viên → tổ chức thi → xem và xuất kết quả**. Tài liệu dùng tên nút trên giao diện. Các đường dẫn bắt đầu bằng `/` được mở trên tên miền GREECO đang sử dụng.

## 1. Vào đúng khu vực

| Vai trò | Cách vào | Phạm vi |
| --- | --- | --- |
| Giảng viên | Đăng nhập tại `/giang-vien/login`, mở **Quản lý trắc nghiệm** | Quản lý câu hỏi, bộ đề, phòng thi và báo cáo trong phạm vi của mình. |
| Quản trị viên | Đăng nhập tại `/admin/login`, chọn **Quản lý trắc nghiệm** | Quản lý dữ liệu của tất cả giảng viên, cấu hình lưu giữ và ẩn danh học viên. |
| Học viên | Mở liên kết phòng, quét QR hoặc nhập PIN tại `/eduquiz` | Tham gia bằng họ tên và thông tin liên hệ. Học viên dùng trình duyệt đã tham gia để mở lại bài làm. |

Sau khi đăng nhập, mở **Quy trình** trong menu hoặc bên cạnh nút **Tạo bộ đề** để xem các bước và tải PDF. Trang **Tổng quan** có thống kê và danh sách phiên gần đây. Quản trị viên có thể xem danh sách tài khoản giảng viên trong **Cài đặt**. Cách cấp tài khoản nằm trong [tài liệu kỹ thuật EduQuiz](eduquiz.md).

## 2. Chuẩn bị câu hỏi

### Soạn hoặc sửa một câu

1. Mở **Ngân hàng câu hỏi → Thêm câu hỏi**. Để sửa câu có sẵn, tìm theo nội dung rồi bấm vào tên câu hỏi.
2. Nhập nội dung và từ **2 đến 6 phương án**. Đánh dấu đúng ở ít nhất một phương án. Nếu đánh dấu nhiều phương án đúng, học viên phải chọn đúng cả nhóm để được điểm.
3. Nhập **Thời gian trả lời (giây)** từ **5 đến 600 giây**. Giá trị này dùng cho từng câu trong phiên **Trực tiếp**. Phiên **Tự làm bài** dùng đồng hồ chung cho cả lượt.
4. Có thể thêm **Giải thích đáp án** và tải ảnh PNG, JPEG hoặc WebP tối đa **4 MB**. Khi sửa, tải ảnh mới để thay ảnh hoặc tích **Xóa ảnh khỏi phiên bản hiện tại**.
5. Bấm **Lưu câu hỏi**. Mở **Lịch sử chỉnh sửa** trên trang câu hỏi để xem nội dung đã ghi nhận, thời điểm và người chỉnh sửa.

Dùng **Nhân bản** bên cạnh câu hỏi để tạo một bản sao và mở bản đó để chỉnh sửa.

### Nhập nhiều câu từ Excel hoặc CSV

1. Mở **Ngân hàng câu hỏi → Nhập câu hỏi từ Excel hoặc CSV → Tải tệp mẫu**.
2. Giữ hàng tiêu đề và thứ tự cột của mẫu. Với Excel, điền vào trang tính đầu tiên và thay dòng minh họa bằng dữ liệu cần nhập.

| Cột | Cách điền |
| --- | --- |
| `question` | Nội dung câu hỏi, mỗi dòng một câu. |
| `a` đến `f` | Điền ít nhất hai phương án. Các cột phương án còn lại có thể để trống. |
| `correct` | Ghi chữ cái tương ứng với phương án đúng, ví dụ `B` hoặc `A,C`. Mỗi chữ cái được đánh dấu phải có nội dung phương án. |
| `seconds` | Số giây cho câu hỏi trực tiếp, từ 5 đến 600. Để trống thì dùng 30 giây. |
| `explanation` | Giải thích đáp án nếu có. Có thể thêm ảnh sau khi nhập bằng màn hình sửa câu hỏi. |

3. Chọn tệp `.xlsx` hoặc `.csv` tối đa **3 MB** và **500 dòng dữ liệu**, rồi bấm **Nhập tệp**. Nếu hệ thống báo lỗi theo dòng, sửa dòng đó và nhập lại. Hệ thống kiểm tra cả tệp trước khi lưu câu hỏi.

## 3. Lập bộ đề và gắn khóa đào tạo

1. Mở **Bộ đề thi → Tạo bộ đề**, nhập tên và chọn **Khóa đào tạo** nếu cần. Có thể dùng **Chưa gán khóa**.
2. Chọn câu trong danh sách rồi bấm **Thêm vào đề**. Mỗi bộ đề có từ **1 đến 100 câu**, mỗi câu chỉ xuất hiện một lần.
3. Dùng **Lên**, **Xuống** để xếp thứ tự. Dùng **Bỏ** để đưa câu ra khỏi bộ đề. Bấm **Lưu bộ đề**.
4. Để sửa đề, bấm vào tên đề trong **Bộ đề thi**. Để xem các đề theo khóa, vào **Khóa đào tạo** rồi bấm số bộ đề cạnh tên khóa. Danh sách này dùng các khóa đã có trên website GREECO.

Khi tạo phòng, EduQuiz lưu một bản của đề tại thời điểm đó, gồm thứ tự câu, nội dung, ảnh, đáp án và thời gian từng câu. Muốn dùng nội dung đã sửa cho lần thi tiếp theo, hãy tạo phòng mới từ bộ đề đã cập nhật.

## 4. Tạo phòng và mời học viên

1. Vào **Phòng thi**. Trong **Tạo phiên thi**, chọn một bộ đề có câu hỏi.
2. Chọn **Hình thức** theo cách tổ chức.

| Hình thức | Cách tổ chức | Thời gian và lượt làm |
| --- | --- | --- |
| **Trực tiếp** | Giảng viên mở và chuyển từng câu cho cả lớp. | Đồng hồ riêng cho mỗi câu. Mỗi học viên có một lượt trong phiên. |
| **Tự làm bài** | Học viên vào phòng và tự chuyển câu theo tiến độ của mình. | Đồng hồ chung mỗi lượt. Chọn **Thời lượng tự làm (phút)** từ **1–240 phút** và **Giới hạn số lượt tự làm** từ **1–10 lượt**. |

3. Tích **Yêu cầu mã mời cá nhân cho phiên này** nếu cần kiểm soát người tham gia, rồi bấm **Tạo phiên thi**.
4. Trong **Mời học viên tham gia**, bấm **Sao chép liên kết** để gửi đường dẫn. Có thể cho học viên quét mã QR hoặc gửi **PIN 8 số** để nhập tại `/eduquiz`. Giảng viên có thể mở **Điện thoại học viên ↗** để xem trang tham gia.

### Phòng yêu cầu mã mời

1. Trên trang phòng, nhập **Email hoặc số điện thoại được mời**, rồi bấm **Tạo mã mời cá nhân**.
2. Sao chép mã khi màn hình hiển thị và gửi riêng cho học viên cùng thông tin liên hệ cần dùng. Mã hiện một lần và dùng cho lần tham gia đầu tiên.
3. Nếu cần thay mã chưa dùng, tạo lại bằng cùng thông tin liên hệ. Mã mới thay thế mã cũ. Nếu mã đã dùng, học viên mở lại liên kết bằng trình duyệt đã tham gia.

### Học viên vào phòng

1. Mở liên kết, quét QR hoặc nhập PIN. Điền họ tên và ít nhất một trong hai mục **Email** hoặc **Số điện thoại**.
2. Nếu phòng yêu cầu mã mời, nhập mã và **chỉ điền đúng một thông tin liên hệ đã được mời**. Nếu vào bằng PIN từ trang chung, hệ thống có thể chuyển sang trang phòng để nhập mã mời.
3. Đọc thông báo sử dụng dữ liệu, tích xác nhận rồi bấm **Tham gia phòng thi**. Với phòng **Trực tiếp**, chờ câu hỏi được mở. Với phòng **Tự làm bài**, đồng hồ bắt đầu tính từ khi tham gia thành công.

## 5. Tổ chức phiên Trực tiếp

1. **Giảng viên** mở phòng từ danh sách **Các phiên đã tạo**. Khi lớp đã sẵn sàng, bấm **Bắt đầu** để mở câu đầu tiên và chạy đồng hồ.
2. **Học viên** chọn một hoặc nhiều phương án theo hướng dẫn trên câu hỏi, rồi bấm **Gửi đáp án** trước khi hết giờ. Khi màn hình báo **Đã ghi nhận đáp án của bạn**, lựa chọn của câu đó đã được chốt.
3. Trong lúc câu đang mở, **giảng viên** có thể bấm **Tạm dừng**, sau đó bấm **Tiếp tục** để dùng phần thời gian còn lại. Bấm **Kết thúc câu** để khóa sớm. Hệ thống tự khóa câu khi hết giờ.
4. Sau khi khóa, **giảng viên** có thể bấm **Hiện kết quả** để xem lượt và tỷ lệ chọn từng phương án, **Hiện đáp án** để công bố đáp án đúng và giải thích nếu có, hoặc **Bảng xếp hạng** để xem tối đa 10 vị trí theo mã học viên. Với câu nhiều lựa chọn, một người có thể được tính vào nhiều phương án.
5. Bấm **Chuyển câu** để mở câu kế tiếp. Sau câu cuối, bấm **Kết thúc phiên** và xác nhận để chốt điểm cho cả lớp. Học viên xem số câu đúng và điểm trên màn hình của mình.

## 6. Tổ chức phiên Tự làm bài

**Giảng viên** gửi liên kết sau khi tạo phòng. Học viên có thể bắt đầu ngay khi tham gia. Giảng viên theo dõi số học viên và số lượt đã nộp trên trang phòng, hoặc bấm **Xem kết quả & thống kê** để mở báo cáo của phiên.

**Học viên** thực hiện như sau:

1. Màn hình chỉ hiển thị **một câu mỗi lần**, có số thứ tự, ví dụ **Câu hỏi 2/10**, và thời gian còn lại của cả lượt.
2. Chọn phương án. Hệ thống tự lưu và báo **Đã tự lưu lựa chọn**. Dùng **Xóa lựa chọn** để bỏ đáp án đã chọn ở câu đó.
3. Bấm **Tiếp theo** để sang câu khác hoặc **Quay lại** để xem và sửa câu trước khi còn thời gian. Có thể bỏ qua câu rồi quay lại. Các lựa chọn được giữ khi chuyển câu.
4. Bấm **Nộp bài** ở bất kỳ câu nào và xác nhận. Nếu đang tự lưu, đợi thông báo lưu xong rồi bấm nộp lại. Khi hết giờ, hệ thống tự chốt từ các đáp án đã lưu.
5. Xem điểm trên màn hình hoàn thành. Nếu phòng còn mở và còn lượt, bấm **Làm lại**, rồi xác nhận. Lượt mới bắt đầu từ câu đầu với đồng hồ mới.

Nếu tải lại trang, mở cùng liên kết trong trình duyệt đã tham gia. Màn hình bắt đầu từ câu đầu và khôi phục các đáp án đã lưu. Đồng hồ vẫn tính theo hạn của lượt hiện tại.

Khi hết thời gian nhận bài của lớp, **giảng viên** bấm **Kết thúc phiên** và xác nhận. Thao tác đóng phòng đối với người mới, dừng các lượt còn mở và chấm theo đáp án đã lưu.

## 7. Xem điểm, học viên và xuất báo cáo

1. Mở **Kết quả và thống kê** để xem tất cả lượt làm trong phạm vi quản lý. Từ trang phòng, bấm **Xem kết quả & thống kê** để lọc sẵn theo phiên. Mục **Học viên** hiển thị một dòng cho mỗi học viên trong mỗi phiên, lấy lượt mới nhất.
2. Tìm theo tên, email hoặc số điện thoại. Có thể lọc theo **Khóa đào tạo**, **Phiên thi** và khoảng **Ngày tạo phiên**. Hệ thống lấy ngày tạo phòng theo giờ Việt Nam. Bấm **Áp dụng bộ lọc** trước khi xem hoặc xuất.
3. Bấm vào tên học viên để mở **Chi tiết lượt làm bài**. Màn hình hiển thị điểm khi đã chốt, thời gian hoàn thành, phương án đã chọn, trạng thái đúng/sai và các câu chưa trả lời.
4. Bấm **Xuất Excel** hoặc **Xuất PDF** trong **Kết quả và thống kê**. Tệp lấy toàn bộ các dòng khớp bộ lọc, kể cả các trang sau và các lượt đang làm. PDF giới hạn **500 dòng** mỗi lần. Nếu vượt giới hạn, thu hẹp bộ lọc hoặc xuất Excel.

Điểm của một lượt là **số câu đúng trên tổng số câu**. Câu nhiều đáp án chỉ tính đúng khi chọn đủ mọi đáp án đúng và không chọn thêm phương án sai. Câu bỏ trống không có điểm. **Điểm trung bình** trong báo cáo là trung bình tỷ lệ đúng của các lượt đã hoàn thành theo bộ lọc.

## 8. Kết thúc, xóa và lưu giữ dữ liệu

| Thao tác | Cách thực hiện và tác động |
| --- | --- |
| **Kết thúc phiên** | Bấm trên trang phòng để đóng lượt tham gia mới, chốt các lượt đang mở và bắt đầu tính thời hạn lưu dữ liệu. |
| **Xóa câu hỏi** | Bấm cạnh câu trong **Ngân hàng câu hỏi**. Câu bị gỡ khỏi ngân hàng và các bộ đề đang dùng câu đó. Bộ đề trống cần bổ sung câu trước khi tạo phòng. Nội dung của các phòng đã tạo và kết quả được giữ. |
| **Xóa bộ đề** | Bấm cạnh đề trong **Bộ đề thi**. Câu hỏi trong ngân hàng, phòng đã tạo và kết quả được giữ. |
| **Xóa phòng thi** | Bấm cạnh phòng trong **Phòng thi**. PIN, QR và liên kết mất hiệu lực. Lượt đang làm được chốt theo đáp án đã lưu. Báo cáo vẫn lọc được phiên có nhãn **(đã xóa)**. |
| **Xóa tất cả** | Mở mục xóa tất cả tương ứng ở cuối danh sách và xác nhận. Thao tác áp dụng cho toàn bộ mục trong phạm vi quản lý, kể cả mục ngoài bộ lọc, ngoài trang hiện tại và phòng đang mở. |

Trong **Cài đặt → Lưu giữ dữ liệu học viên**, quản trị viên có thể đặt thời hạn từ **1 đến 3.650 ngày** kể từ lúc phiên kết thúc. Mặc định là **365 ngày**. Khi đến hạn, tác vụ định kỳ thay tên bằng nhãn ẩn danh, xóa email, số điện thoại, mã mời và thu hồi quyền truy cập của học viên. Điểm và đáp án đã lưu được giữ ở dạng ẩn danh.

Để ẩn danh sớm, quản trị viên mở trang của một phiên đã kết thúc, bấm **Ẩn danh học viên** và xác nhận.

## 9. Xử lý tình huống thường gặp

| Tình huống | Cách xử lý |
| --- | --- |
| Không thấy đề khi tạo phòng | Kiểm tra bộ đề đã lưu và còn ít nhất một câu hỏi trong phạm vi quản lý của tài khoản. |
| PIN báo sai hoặc liên kết báo phòng không còn khả dụng | Đối chiếu PIN đủ 8 số hoặc mở lại QR/liên kết giảng viên gửi. Giảng viên kiểm tra phòng đã kết thúc hay đã bị xóa. |
| Mã mời không được chấp nhận | Kiểm tra đúng phòng, đúng mã mới nhất và chỉ điền email hoặc số điện thoại đã được mời. |
| Báo thông tin liên hệ đã đăng ký | Dùng lại liên kết phòng trên trình duyệt và hồ sơ trình duyệt đã tham gia. Nếu đã xóa dữ liệu trình duyệt hoặc đổi thiết bị, liên hệ giảng viên để kiểm tra lượt đã ghi nhận. |
| Tự làm bài báo chưa lưu được lựa chọn | Giữ trang mở để hệ thống thử lưu lại khi mạng ổn định. Kiểm tra thông báo lưu thành công trước khi nộp nếu còn thời gian. |
| Trực tiếp không gửi được đáp án | Kiểm tra câu còn đang mở và đồng hồ còn thời gian. Nếu giảng viên đang tạm dừng, đợi phiên tiếp tục. |
| Báo cáo chưa có điểm hoặc thiếu dòng | Kiểm tra trạng thái lượt và bộ lọc. Lượt đang làm có điểm sau khi được chốt. Khoảng ngày lọc theo ngày tạo phiên. |
