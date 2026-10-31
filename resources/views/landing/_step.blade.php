<style>
    .steps-section {
        padding: 60px 0px;
        background-color: #ffffff;
    }
    .steps-inner {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
    }
    .steps-heading {
        text-align: center;
        margin-bottom: 40px;
    }
    .steps-heading h2 {
        font-size: 28px;
        color: #0c235a;
        margin-bottom: 8px;
    }
    .steps-heading p {
        font-size: 15px;
        color: #5b7098;
    }
    /* Chia 3 cột ngang hàng cho 3 bước */
    .steps-list {
        display: flex;
        gap: 20px;
    }
    .step-card {
        flex: 1;
        background-color: #eff6ff;
        border: 1px solid #dbeafe;
        padding: 24px;
        border-radius: 16px;
        position: relative;
    }
    .step-number {
        display: inline-block;
        background-color: #3474f5;
        color: #ffffff;
        font-size: 12px;
        font-weight: bold;
        padding: 4px 10px;
        border-radius: 12px;
        margin-bottom: 12px;
    }
    .step-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #dbeafe;
        color: #3474f5;
        margin-bottom: 12px;
    }
    .step-card h3 {
        font-size: 18px;
        color: #0c235a;
        margin-bottom: 8px;
    }
    .step-card p {
        font-size: 14px;
        color: #5b7098;
        line-height: 1.5;
    }
</style>

<section class="steps-section" id="steps">
    <div class="steps-inner">
        <div class="steps-heading">
            <h2>Bắt đầu chăm sóc chỉ với 3 bước</h2>
            <p>Không cần thao tác phức tạp, bạn có thể từng bước thiết lập KORU cho gia đình mình.</p>
        </div>

        <div class="steps-list">
            @foreach ([
                ['user-round-plus', 'Tạo hồ sơ người thân', 'Đăng ký tài khoản và thêm thông tin người thân cần được chăm sóc.'],
                ['calendar-days', 'Thiết lập lịch uống thuốc', 'Nhập thông tin thuốc, thời gian uống và kết nối thiết bị khi cần.'],
                ['heart-handshake', 'Theo dõi và đồng hành', 'Kiểm tra tình trạng uống thuốc và chủ động quan tâm khi có liều bị bỏ lỡ.']
            ] as $index => [$icon, $heading, $description])
                <article class="step-card">
                    <span class="step-number">Bước {{ $index + 1 }}</span>
                    <div class="step-icon">
                        <x-koru-icon :name="$icon" style="width: 24px; height: 24px;" />
                    </div>
                    <h3>{{ $heading }}</h3>
                    <p>{{ $description }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>