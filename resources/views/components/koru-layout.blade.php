<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'KORU' }}</title>
    
    <!-- CSS CHUNG CHO TOÀN TRANG (HEADER, FOOTER, KHUNG) -->
    <style>
        :root {
            --primary: #3474f5;
            --primary-dark: #1f5fdd;
            --navy: #0c235a;
            --text: #29416f;
            --muted: #5b7098;
            --border: #e4ebf7;
            --soft-blue: #edf5ff;
            --orange: #ff965c;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { color: var(--text); background: #fff; font-family: "Be Vietnam Pro", sans-serif; line-height: 1.65; }
        a { color: inherit; text-decoration: none; }
        .container { width: min(1280px, calc(100% - 48px)); margin-inline: auto; }
        
        /* Header */
        .site-header { background: #fff; border-bottom: 1px solid #f0f3fa; padding: 12px 0; }
        .header-inner { display: flex; align-items: center; justify-content: space-between; }
        .brand { color: var(--primary); font-size: 24px; font-weight: 800; }
        .main-nav { display: flex; gap: 24px; }
        .main-nav a { color: var(--navy); font-weight: 600; font-size: 14px; }
        
        /* Button chung */
        .btn { display: inline-flex; align-items: center; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-outline { border: 1px solid #c8dcff; color: var(--primary); background: #fff; }
        
        /* Footer */
        .site-footer { padding: 12px 0; border-top: 1px solid var(--border); background: #fff; margin-top: 40px; }
        .footer-inner {
            display: flex;
            justify-content: center; /* Căn giữa nội dung footer */
            align-items: center;
            text-align: center;
            font-size: 12px;
            color: #5b7098;
        }
    </style>
</head>
<body>

    <!-- Header dùng chung -->
    <header class="site-header">
        <div class="container header-inner">
            <a href="{{ url('/') }}" class="brand">KORU</a>
            <nav class="main-nav">
                <a href="{{ url('/#home') }}">Trang chủ</a>
                <a href="{{ url('/#why') }}">Vì sao chọn KORU</a>
                <a href="{{ url('/#features') }}">Tính năng</a>
                <a href="{{ url('/#steps') }}">Hướng dẫn</a>
                <a href="{{ url('/#faq') }}">Hỏi đáp</a>
            </nav>
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Bảng điều khiển</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Nội dung các file partials hiển thị ở đây -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer dùng chung -->
    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© {{ date('Y') }} KORU. Chăm sóc sức khỏe gia đình.</p>
        </div>
    </footer>

</body>
</html>