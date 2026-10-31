<style>
    .hero-section {
        background: linear-gradient(110deg, #ffffff, #f8fbff 45%, #e6f1ff);
        padding: 50px 0px;
        overflow: hidden;
    }
    .hero-inner {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
    }
    .hero-content {
        width: 50%;
    }
    .hero-label {
        display: inline-block;
        padding: 6px 14px;
        background-color: #dceaff;
        color: #3474f5;
        font-size: 13px;
        font-weight: 600;
        border-radius: 20px;
        margin-bottom: 16px;
    }
    .hero-title {
        font-size: 38px;
        font-weight: 800;
        color: #0c235a;
        line-height: 1.25;
        margin-bottom: 16px;
    }
    .hero-title span {
        color: #3474f5;
    }
    .hero-description {
        font-size: 16px;
        color: #5b7098;
        margin-bottom: 24px;
        line-height: 1.5;
    }
    .hero-actions {
        display: flex;
        gap: 12px;
        margin-bottom: 28px;
    }
    .btn-primary {
        background-color: #3474f5;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }
    .btn-outline {
        border: 1px solid #c8dcff;
        color: #3474f5;
        background-color: #ffffff;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }
    .hero-benefits {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .hero-benefit {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #29416f;
    }
    .hero-visual {
        width: 45%;
    }
    .hero-image img {
        width: 100%;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(12, 35, 90, 0.1);
        object-fit: cover;
    }
</style>

<section class="hero-section" id="home">
    <div class="hero-inner">
        <div class="hero-content">
            <span class="hero-label">Chăm sóc người thân, dù ở bất cứ đâu</span>
            <h1 class="hero-title">Ba mẹ uống thuốc đúng giờ, bạn <span>an tâm hơn mỗi ngày.</span></h1>
            <p class="hero-description">Thiết lập lịch nhắc uống thuốc, theo dõi từ xa và nhận thông báo khi người thân cần bạn — tất cả trong một ứng dụng đơn giản.</p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-primary">Bắt đầu miễn phí →</a>
                <a href="#steps" class="btn-outline">Tìm hiểu cách hoạt động</a>
            </div>

            <div class="hero-benefits">
                @foreach ([
                    ['shield-check', 'Dễ sử dụng'],
                    ['users-round', 'Phù hợp cả gia đình'],
                    ['shield-check', 'Bảo mật thông tin']
                ] as [$icon, $benefit])
                    <span class="hero-benefit">
                        <x-koru-icon :name="$icon" style="width: 16px; height: 16px; color: #3474f5;" />
                        <span>{{ $benefit }}</span>
                    </span>
                @endforeach
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-image">
                <img src="{{ asset('images/koru-family.jpg') }}" alt="Con gái chăm sóc mẹ">
            </div>
        </div>
    </div>
</section>