<style>
    .faq-section {
        padding: 60px 0px;
        background-color: #f8fbff;
    }
    .faq-inner {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        gap: 40px;
    }
    .faq-title-block {
        width: 35%;
    }
    .faq-title-block h2 {
        font-size: 28px;
        color: #0c235a;
        margin-bottom: 12px;
    }
    .faq-title-block p {
        font-size: 15px;
        color: #5b7098;
    }
    .faq-list {
        width: 65%;
    }
    .faq-item {
        background-color: #ffffff;
        padding: 18px 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }
    .faq-item summary {
        font-weight: 700;
        color: #0c235a;
        cursor: pointer;
        font-size: 15px;
    }
    .faq-item p {
        margin-top: 10px;
        font-size: 14px;
        color: #5b7098;
        line-height: 1.5;
    }
</style>

<section class="faq-section" id="faq">
    <div class="faq-inner">
        <div class="faq-title-block">
            <h2>Bạn có thể đang thắc mắc?</h2>
            <p>Một vài thông tin giúp bạn hiểu rõ hơn về cách sử dụng ứng dụng KORU.</p>
        </div>

        <div class="faq-list">
            @foreach ([
                ['Người bệnh có cần tạo tài khoản không?', 'Không. Người chăm sóc quản lý hồ sơ; người bệnh sử dụng thiết bị được kết nối với hồ sơ.'],
                ['Có thể cùng người khác chăm sóc một người bệnh không?', 'Có. Người chăm sóc chính có thể chia sẻ quyền chăm sóc thông qua mã liên kết.'],
                ['KORU có hỗ trợ nhắc uống thuốc hằng ngày không?', 'Có. Bạn có thể thiết lập lịch theo khung giờ và khoảng ngày sử dụng thuốc.'],
                ['Nếu người bệnh quên uống thuốc thì sao?', 'KORU ghi nhận liều bị bỏ lỡ, giúp người chăm sóc chủ động kiểm tra và liên hệ ngay.']
            ] as [$question, $answer])
                <details class="faq-item">
                    <summary>{{ $question }}</summary>
                    <p>{{ $answer }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>