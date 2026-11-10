@extends('eduquiz.layout', ['title' => 'Quy trình', 'staff' => true])

@section('content')
<header class="section-head">
    <div>
        <p class="eyebrow">HƯỚNG DẪN EDUQUIZ</p>
        <h1>Quy trình tạo đề và tổ chức thi</h1>
        <p class="muted">Từ ngân hàng câu hỏi đến báo cáo kết quả.</p>
    </div>
    <a class="button primary" href="{{ versioned_asset('assets/eduquiz/huong-dan-eduquiz.pdf') }}" download>Tải hướng dẫn PDF</a>
</header>

<section class="card" aria-labelledby="guide-steps">
    <div class="card-header"><h2 id="guide-steps">Các bước thực hiện</h2></div>
    <ol class="record-list">
        <li>
            <div>
                <strong>1. Chuẩn bị câu hỏi</strong>
                <p class="muted">Soạn câu hỏi hoặc nhập Excel/CSV từ tệp mẫu. Mỗi câu cần từ 2 đến 6 phương án và ít nhất một đáp án đúng.</p>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.questions') }}">Ngân hàng câu hỏi</a>
        </li>
        <li>
            <div>
                <strong>2. Lập bộ đề</strong>
                <p class="muted">Nhập tên đề, chọn khóa đào tạo nếu cần. Chọn từng câu và bấm <b>Thêm vào đề</b>. Dùng <b>Lên</b>, <b>Xuống</b> hoặc <b>Bỏ</b> để điều chỉnh, rồi bấm <b>Lưu bộ đề</b>.</p>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.quizzes.edit') }}">Tạo bộ đề</a>
        </li>
        <li>
            <div>
                <strong>3. Tạo phòng và mời học viên</strong>
                <p class="muted">Chọn bộ đề và hình thức thi. Nếu chọn <b>Tự làm bài</b>, đặt thời lượng và số lượt. Tạo phiên, rồi chia sẻ liên kết, QR hoặc PIN 8 số. Nếu phòng yêu cầu mã mời, tạo và gửi mã riêng cho từng học viên.</p>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.rooms') }}">Phòng thi</a>
        </li>
        <li>
            <div>
                <strong>4. Tổ chức thi</strong>
                <p class="muted"><b>Trực tiếp</b> — Giảng viên bấm <b>Bắt đầu</b>, điều khiển từng câu và kết thúc phiên để chốt điểm.</p>
                <p class="muted"><b>Tự làm bài</b> — Học viên làm từng câu, dùng <b>Quay lại</b> hoặc <b>Tiếp theo</b> và nộp bài. Hệ thống tự lưu lựa chọn và tự chốt bài khi hết giờ.</p>
            </div>
        </li>
        <li>
            <div>
                <strong>5. Xem và xuất kết quả</strong>
                <p class="muted">Lọc theo phiên hoặc khóa đào tạo, bấm tên học viên để xem từng lượt làm bài, rồi xuất Excel hoặc PDF theo bộ lọc.</p>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.results') }}">Kết quả và thống kê</a>
        </li>
    </ol>
</section>
@endsection
