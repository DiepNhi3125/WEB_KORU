<x-guest-layout>
    <x-slot:title>Đặt lại mật khẩu - KORU</x-slot:title>

    <div class="auth-heading">
        <h1>Đặt lại mật khẩu</h1>
        <p>Tạo mật khẩu mới cho tài khoản KORU của bạn</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="auth-form">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email">Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus placeholder="example@koru.vn">
            @error('email')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Mật khẩu mới</label>
            <input id="password" type="password" name="password" required placeholder="Tối thiểu 6 ký tự">
            @error('password')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Xác nhận mật khẩu mới</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới">
        </div>

        <button type="submit" class="btn-primary auth-submit">
            Đặt lại mật khẩu →
        </button>
    </form>
</x-guest-layout>