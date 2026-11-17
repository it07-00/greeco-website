@extends('eduquiz.layout', ['title' => 'Quy trình', 'staff' => true])

@section('content')
<header class="section-head">
    <div>
        <p class="eyebrow">HƯỚNG DẪN EDUQUIZ</p>
        <h1>Quy trình tạo đề và tổ chức thi</h1>
        <p class="muted">Hướng dẫn từng bước từ chuẩn bị ngân hàng câu hỏi đến xuất báo cáo kết quả.</p>
    </div>
    <a class="button primary" href="{{ versioned_asset('assets/eduquiz/huong-dan-eduquiz.pdf') }}" download>Tải hướng dẫn PDF</a>
</header>

<section class="card" aria-labelledby="guide-steps">
    <div class="card-header">
        <h2>Các bước thực hiện</h2>
        <span class="status-badge">5 bước chuẩn</span>
    </div>
    <ol class="guide-steps" id="guide-steps">
        <li class="guide-step">
            <div class="guide-step-main">
                <span class="guide-step-num">01</span>
                <div class="guide-step-body">
                    <h3>Chuẩn bị ngân hàng câu hỏi</h3>
                    <p class="muted">Soạn câu hỏi trực tiếp hoặc nhập hàng loạt từ tệp Excel/CSV theo tệp mẫu. Mỗi câu hỏi cần từ <strong>2 đến 6 phương án</strong> và ít nhất <strong>1 đáp án đúng</strong>.</p>
                </div>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.questions') }}">Ngân hàng câu hỏi →</a>
        </li>
        <li class="guide-step">
            <div class="guide-step-main">
                <span class="guide-step-num">02</span>
                <div class="guide-step-body">
                    <h3>Lập và sắp xếp bộ đề thi</h3>
                    <p class="muted">Nhập tên bộ đề, gắn vào khóa đào tạo GREECO nếu cần. Chọn từng câu hỏi rồi bấm <strong>Thêm vào đề</strong>, dùng <strong>Lên</strong> / <strong>Xuống</strong> / <strong>Bỏ</strong> để sắp xếp thứ tự, sau đó bấm <strong>Lưu bộ đề</strong>.</p>
                </div>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.quizzes.edit') }}">Tạo bộ đề →</a>
        </li>
        <li class="guide-step">
            <div class="guide-step-main">
                <span class="guide-step-num">03</span>
                <div class="guide-step-body">
                    <h3>Tạo phòng thi và mời học viên</h3>
                    <p class="muted">Chọn bộ đề và hình thức thi. Với hình thức <strong>Tự làm bài</strong>, thiết lập thời lượng và số lượt tối đa. Sau khi tạo phiên, chia sẻ liên kết, mã QR hoặc mã PIN 8 số (hoặc tạo mã mời cá nhân nếu phòng yêu cầu).</p>
                </div>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.rooms') }}">Phòng thi →</a>
        </li>
        <li class="guide-step">
            <div class="guide-step-main">
                <span class="guide-step-num">04</span>
                <div class="guide-step-body">
                    <h3>Tổ chức và điều hành thi</h3>
                    <div class="guide-modes">
                        <div class="guide-mode">
                            <strong>Thi trực tiếp (Live)</strong>
                            <p class="muted small">Giảng viên bấm <strong>Bắt đầu</strong>, điều khiển từng câu (Tạm dừng, Khóa câu, Hiện đáp án, Chuyển câu) và bấm <strong>Kết thúc phiên</strong> để chốt điểm.</p>
                        </div>
                        <div class="guide-mode">
                            <strong>Tự làm bài (Self-paced)</strong>
                            <p class="muted small">Học viên chủ động làm từng câu và nộp bài trong thời gian quy định. Hệ thống tự lưu lựa chọn và tự động chốt bài khi hết giờ.</p>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="guide-step">
            <div class="guide-step-main">
                <span class="guide-step-num">05</span>
                <div class="guide-step-body">
                    <h3>Theo dõi và xuất báo cáo kết quả</h3>
                    <p class="muted">Lọc kết quả theo khóa đào tạo, phiên thi hoặc khoảng ngày. Bấm vào tên học viên để xem chi tiết từng câu trả lời, sau đó xuất báo cáo <strong>Excel</strong> hoặc <strong>PDF</strong>.</p>
                </div>
            </div>
            <a class="button quiet" href="{{ route('eduquiz.staff.results') }}">Kết quả và thống kê →</a>
        </li>
    </ol>
</section>
@endsection
