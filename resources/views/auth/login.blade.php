<x-guest-layout>
    <x-slot:title>Đăng nhập - KORU</x-slot:title>

    <div class="auth-heading">
        <h1>Chào mừng trở lại</h1>
        <p>Đăng nhập để theo dõi lịch uống thuốc của người thân</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email -->
        <div class="auth-field">
            <label for="email">Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="example@koru.vn">
            @error('email')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mật khẩu -->
        <div class="auth-field">
            <label for="password">Mật khẩu</label>
            <input id="password" type="password" name="password" required placeholder="Nhập mật khẩu">
            @error('password')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Tùy chọn Ghi nhớ & Quên mật khẩu -->
        <div class="auth-options">
            <label class="auth-remember">
                <input type="checkbox" name="remember">
                <span>Ghi nhớ đăng nhập</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
            @endif
        </div>

        <button type="submit" class="btn-primary auth-submit">
            Đăng nhập →
        </button>
    </form>

    <div class="auth-switch">
        Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
    </div>
</x-guest-layout>