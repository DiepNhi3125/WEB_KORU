<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <script src="{{ asset('js/toast.js') }}" defer></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')


        <div class="koru-toasts">
            @foreach (['success', 'error', 'warning', 'info'] as $type)
                @if (session()->has($type))
                    <div class="koru-toast koru-toast-{{ $type }}" role="{{ $type === 'error' ? 'alert' : 'status' }}">

                        <span class="koru-toast-message">
                            {{ session($type) }}
                        </span>

                        <button type="button" class="koru-toast-close" aria-label="Đóng thông báo">
                            &times;
                        </button>
                    </div>
                @endif
            @endforeach
        </div>



        <!-- Page Heading -->
        @php
            $isManagementPage = request()->routeIs(
                'patients.*',
                'reminders.*',
                'dashboard',
                'profile.*'
            );

            $routeName = request()->route()?->getName();

            $pageLabels = [
                'patients.create' => 'Tạo hồ sơ',
                'patients.edit' => 'Chỉnh sửa hồ sơ',
                'patients.detail' => 'Chi tiết hồ sơ',
                'patients.join' => 'Liên kết hồ sơ',
                'patients.share' => 'Chia sẻ hồ sơ',
                'patients.pair' => 'Ghép thiết bị',
                'reminders.create' => 'Tạo lịch nhắc',
                'reminders.show' => 'Chi tiết',
                'reminders.edit' => 'Chỉnh sửa',
                'patients.statistics' => 'Thống kê uống thuốc',
            ];
        @endphp

        @if($isManagementPage || isset($header))
            <header class="bg-white border-b border-gray-100">

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
                        {{ $isManagementPage ? 'py-3' : 'py-6' }}">

                    @if ($isManagementPage)

                        <nav class="koru-breadcrumb" aria-label="Đường dẫn điều hướng">

                            @if (request()->routeIs('dashboard'))

                                <span aria-current="page">
                                    Bảng điều khiển
                                </span>

                            @elseif (request()->routeIs('profile.*'))

                                <a href="{{ route('dashboard') }}">
                                    Bảng điều khiển
                                </a>

                                <span class="koru-breadcrumb-separator">/</span>

                                <span aria-current="page">
                                    Hồ sơ tài khoản
                                </span>

                            @elseif (request()->routeIs('patients.index'))

                                <span aria-current="page">
                                    Hồ sơ người bệnh
                                </span>

                            @else

                                <a href="{{ route('patients.index') }}">
                                    Hồ sơ người bệnh
                                </a>

                                <span class="koru-breadcrumb-separator">/</span>

                                @if (request()->routeIs('reminders.*'))

                                    @if (request()->routeIs('reminders.index'))

                                        <span aria-current="page">
                                            Lịch nhắc uống thuốc
                                        </span>

                                    @else

                                        <a href="{{ route(
                                            'reminders.index',
                                            request()->route('patient')
                                        ) }}">
                                            Lịch nhắc uống thuốc
                                        </a>

                                        <span class="koru-breadcrumb-separator">/</span>

                                        <span aria-current="page">
                                            {{ $pageLabels[$routeName] ?? 'Chi tiết' }}
                                        </span>

                                    @endif

                                @else

                                    <span aria-current="page">
                                        {{ $pageLabels[$routeName] ?? 'Hồ sơ' }}
                                    </span>

                                @endif

                            @endif

                        </nav>

                    @else

                        {{ $header }}

                    @endif

                </div>
            </header>
        @endif


        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>