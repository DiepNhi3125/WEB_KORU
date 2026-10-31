<style>
    .why-section {
        padding: 60px 0px;
        background-color: #ffffff;
    }
    .why-inner {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        gap: 40px;
        align-items: center;
    }
    .why-content {
        width: 38%;
    }
    .section-label {
        display: block;
        color: #3474f5;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 1.2px;
        margin-bottom: 10px;
    }
    .why-content h2 {
        font-size: 28px;
        color: #0c235a;
        margin-bottom: 14px;
        line-height: 1.3;
    }
    .why-content p {
        font-size: 15px;
        color: #5b7098;
        margin-bottom: 20px;
        line-height: 1.5;
    }
    .why-link {
        color: #3474f5;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
    }
    /* Chia 3 cột ngang hàng cho 3 lý do */
    .why-list {
        width: 62%;
        display: flex;
        gap: 16px;
    }
    .why-item {
        flex: 1;
        background-color: #fff7ed;
        border: 1px solid #fed7aa;
        padding: 20px 16px;
        border-radius: 16px;
        text-align: center;
    }
    .why-illustration {
        height: 110px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .why-illustration img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    .why-item h3 {
        font-size: 16px;
        color: #0c235a;
        margin-bottom: 8px;
        font-weight: 700;
    }
    .why-item p {
        font-size: 13px;
        color: #5b7098;
        line-height: 1.4;
        margin: 0;
    }
</style>

<section class="why-section" id="why">
    <div class="why-inner">
        <div class="why-content">
            <span class="section-label">VÌ SAO CẦN KORU?</span>
            <h2>Những lo lắng quen thuộc trong việc chăm sóc người thân</h2>
            <p>Chăm sóc sức khỏe người thân là điều quan trọng, nhưng không phải lúc nào cũng dễ dàng, đặc biệt khi bạn không thể luôn ở bên.</p>
            <a href="#features" class="why-link">KORU giúp giải quyết những lo lắng này →</a>
        </div>

        <div class="why-list">
            @foreach ([
                ['why-forget.jpg', 'Dễ quên giờ uống thuốc', 'Khi có nhiều loại thuốc và khung giờ, người thân có thể bỏ lỡ liều uống.'],
                ['why-distance.jpg', 'Không thể luôn ở bên', 'Công việc và khoảng cách khiến bạn khó theo dõi việc uống thuốc hằng ngày.'],
                ['why-alert.jpg', 'Thiếu thông tin kịp thời', 'Bạn không biết người thân đã uống thuốc hay đang cần được nhắc nhở.']
            ] as [$image, $heading, $description])
                <article class="why-item">
                    <div class="why-illustration">
                        <img src="{{ asset('images/' . $image) }}" alt="{{ $heading }}">
                    </div>
                    <h3>{{ $heading }}</h3>
                    <p>{{ $description }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>