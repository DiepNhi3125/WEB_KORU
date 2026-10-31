<x-guest-layout>
    <x-slot:title>Quên mật khẩu - KORU</x-slot:title>

    <div class="auth-heading">
        <h1>Quên mật khẩu?</h1>
        <p>Nhập email tài khoản của bạn. Chúng tôi sẽ gửi liên kết thiết lập lại mật khẩu.</p>
    </div>

    @if (session('status'))
        <div style="padding: 12px; background: #dcfce7; border: 1px solid #bbf7d0; color: #15803d; border-radius: 10px; font-size: 13px; margin-bottom: 16px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <label for="email">Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="example@koru.vn">
            @error('email')
                <span style="color: #e11d48; font-size: 12px;">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-primary auth-submit">
            Gửi liên kết khôi phục →
        </button>
    </form>

    <div class="auth-switch">
        Quay lại <a href="{{ route('login') }}">Đăng nhập</a>
    </div>
</x-guest-layout>