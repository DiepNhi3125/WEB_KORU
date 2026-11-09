
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị') - KORU</title>

    <!-- Font chung KORU -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    <!-- CSS và JS Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSS Admin -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="font-sans antialiased">
    <div class="ka-app">

        <!-- Sidebar -->
        <aside class="ka-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="ka-brand">
                <x-application-logo class="ka-logo" />
                <span>ADMIN</span>
            </a>

            <div class="ka-navigation">
                <p class="ka-nav-heading">QUẢN LÝ HỆ THỐNG</p>

                <nav class="ka-nav" aria-label="Điều hướng quản trị">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="ka-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        <span>Bảng điều khiển</span>
                    </a>

                    <!-- Quản lý tài khoản -->
                    <a href="{{ route('admin.users.index') }}"
                       class="ka-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       @if(request()->routeIs('admin.users.*')) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Quản lý tài khoản</span>
                    </a>
                </nav>
            </div>

            <p class="ka-sidebar-footer">KORU · Hệ thống quản trị</p>
        </aside>

        <!-- Nội dung chính -->
        <div class="ka-main">
            <header class="ka-topbar">
                <span class="ka-topbar-title">Hệ thống quản trị KORU</span>

                <div class="ka-account">
                    <span class="ka-avatar">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>

                    <span class="ka-username">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="ka-logout">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </header>

            <main class="ka-content">
                @yield('content')
            </main>
        </div>

    </div>
</body>

</html>
