<style>
    .features-section {
    background-color: #f0f7ff;
    padding: 50px 0;
    overflow: hidden;
}

.features-inner {
    width: 94%;
    max-width: 1400px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 28% 22% 1fr;
    gap: 24px;
    align-items: center;
}

.features-intro {
    width: 100%;
}

.features-phone {
    display: flex;
    justify-content: center;
    align-items: center;
    align-self: end;
}

.features-phone img {
    display: block;
    width: 100%;
    max-width: 300px;
    height: auto;
    object-fit: contain;
}

.features-list {
    width: 100%;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px 16px;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: transparent;
    border: none;
    padding: 8px;
}
    .features-intro h2 {
        font-size: 28px;
        color: #0c235a;
        margin-bottom: 12px;
    }
    
   
    .feature-item h3 {
        font-size: 16px;
        color: #0c235a;
        margin-bottom: 6px;
    }
    .feature-item p {
        font-size: 13px;
        color: #5b7098;
        line-height: 1.4;
    }
    @media (max-width: 600px) {
    .features-inner {
        grid-template-columns: 1fr;
    }

    .features-phone img {
        max-width: 260px;
    }

    .features-list {
        grid-template-columns: 1fr;
    }
}

</style>

<section class="features-section" id="features">
    <div class="features-inner">
        <div class="features-intro">
            <h2>Một ứng dụng, thêm nhiều an tâm</h2>
            <p>KORU giúp việc quản lý lịch uống thuốc và chăm sóc người thân trở nên đơn giản hơn.</p>
            <a href="{{ route('register') }}" class="btn-primary" style="background: #3474f5; color: #fff; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">Khám phá ngay →</a>
        </div>
        <div class="features-phone">
    <img
        src="{{ asset('images/feature-phone.jpg') }}"
        alt="Giao diện quản lý lịch uống thuốc KORU trên điện thoại"
    >
</div>

        <div class="features-list">
            @foreach ([
                ['alarm-clock', 'Nhắc uống thuốc đúng giờ', 'Thiết lập lịch uống thuốc theo từng ngày và khung giờ.'],
                ['clipboard-check', 'Theo dõi tình trạng', 'Xem những liều đã xác nhận và những liều bị bỏ lỡ.'],
                ['users', 'Cùng gia đình chăm sóc', 'Chia sẻ quyền chăm sóc để người thân cùng theo dõi.'],
                ['smartphone', 'Kết nối thiết bị', 'Ghép thiết bị bằng mã QR và xác thực an toàn.'],
                ['contact-round', 'Quản lý hồ sơ người bệnh', 'Lưu thông tin người thân và tổ chức lịch nhắc.'],
                ['bell-ring', 'Thông báo kịp thời', 'Hỗ trợ nhận biết các trường hợp bỏ lỡ liều uống thuốc.']
            ] as [$icon, $heading, $description])
                <article class="feature-item">
                    <div class="feature-icon">
                    <x-koru-icon
                        :name="$icon"
                        style="width: 24px; height: 24px; color: #3474f5;" />
                    </div>

    <div>
        <h3>{{ $heading }}</h3>
        <p>{{ $description }}</p>
    </div>
</article>
            @endforeach
        </div>
    </div>
</section>