
<x-guest-layout>
    <x-slot:title>Xác thực email - KORU</x-slot:title>

    <div class="verify-page">
        <div class="verify-icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="30" height="30" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <rect x="2" y="4" width="20" height="16"
                      rx="3" />
                <path d="m3 6 9 7 9-7" />
            </svg>
        </div>

        <div class="auth-heading">
            <h1>Kiểm tra email của bạn</h1>
            <p>
                Chúng tôi đã gửi liên kết xác thực đến:
            </p>
        </div>

        <div class="verify-email-address">
            {{ auth()->user()->email }}
        </div>

        <p class="verify-description">
            Nhấn vào liên kết trong email để kích hoạt tài khoản
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="verify-success" role="status">
                Yêu cầu gửi lại email xác thực đã được xử lý.
                Vui lòng kiểm tra hộp thư.
            </div>
        @endif

        <form method="POST"
              action="{{ route('verification.send') }}">
            @csrf

            <button type="submit"
                    class="btn-primary auth-submit">
                Gửi lại email xác thực
            </button>
        </form>

        <p class="verify-help">
            Chưa nhận được? Kiểm tra thư mục Spam.
        </p>

        <div class="verify-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="verify-logout">
                    Đăng xuất
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
