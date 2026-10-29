
<x-guest-layout>
    <div class="auth-heading">
        <h1>Chào mừng trở lại!</h1>
        <p>Đăng nhập để tiếp tục chăm sóc người thân cùng KORU.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="auth-field">
            <x-input-label for="password" value="Mật khẩu" />
            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="auth-options">
            <label class="auth-remember">
                <input type="checkbox" name="remember">
                <span>Ghi nhớ đăng nhập</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary auth-submit">
            Đăng nhập
        </button>
    </form>

    <p class="auth-switch">
        Chưa có tài khoản?
        <a href="{{ route('register') }}">Đăng ký ngay</a>
    </p>
</x-guest-layout>
