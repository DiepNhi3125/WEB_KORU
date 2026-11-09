
<nav class="koru-nav" aria-label="Thanh điều hướng chính">
    <div class="koru-nav-inner">
        <a href="{{ route('dashboard') }}" class="koru-nav-brand" aria-label="KORU - Về bảng điều khiển">
            <x-application-logo class="koru-nav-logo" />
        </a>

        <div class="koru-nav-account">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button" class="koru-nav-user" :aria-expanded="open.toString()" aria-haspopup="true">
                        <span class="koru-nav-avatar">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <span class="koru-nav-username">{{ auth()->user()->name }}</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" class="koru-nav-chevron" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        Hồ sơ tài khoản
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="koru-nav-logout">
                            Đăng xuất
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>
