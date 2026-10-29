
<x-guest-layout>
    <div class="auth-heading">
        <h1>Tạo tài khoản KORU</h1>
        <p>Bắt đầu hành trình chăm sóc người thân dễ dàng hơn.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="auth-field">
            <x-input-label for="name" value="Họ và tên" />
            <x-text-input
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="auth-field">
            <x-input-label for="email" value="Email" />
            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
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
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="auth-field">
            <x-input-label for="password_confirmation" value="Xác nhận mật khẩu" />
            <x-text-input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn btn-primary auth-submit">
            Tạo tài khoản
        </button>
    </form>

    <p class="auth-switch">
        Đã có tài khoản?
        <a href="{{ route('login') }}">Đăng nhập</a>
    </p>
</x-guest-layout>
