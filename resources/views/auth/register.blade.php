<x-guest-layout>
    <x-slot:title>Đăng ký tài khoản - KORU</x-slot:title>

    <div class="auth-heading">
        <h1>Tạo tài khoản mới</h1>
        <p>Bắt đầu quản lý và chăm sóc sức khỏe gia đình</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Họ và tên -->
        <div class="auth-field">
            <label for="name">Họ và tên</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nhập họ và tên">
            @error('name')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Số điện thoại -->
        <div class="auth-field">
            <label for="phone">Số điện thoại</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="Nhập số điện thoại">
            @error('phone')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Email -->
        <div class="auth-field">
            <label for="email">Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="example@koru.vn">
            @error('email')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Mật khẩu -->
        <div class="auth-field">
            <label for="password">Mật khẩu</label>
            <input id="password" type="password" name="password" required placeholder="Tối thiểu 6 ký tự">
            @error('password')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <!-- Xác nhận mật khẩu -->
        <div class="auth-field">
            <label for="password_confirmation">Xác nhận mật khẩu</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
        </div>

        <button type="submit" class="btn-primary auth-submit">
            Đăng ký tài khoản →
        </button>
    </form>

    <div class="auth-switch">
        Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
    </div>
</x-guest-layout>