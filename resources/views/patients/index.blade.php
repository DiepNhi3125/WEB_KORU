<x-app-layout>
    <x-slot name="header">
        <h2 class="patients-header">{{ __('Hồ sơ người bệnh') }}</h2>
    </x-slot>

    <!-- Nhúng file CSS từ thư mục public/css/patients.css -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patients-page">
        <div class="patients-toolbar">
            <div>
                <h1 class="patients-title">{{ __('Quản lý người bệnh') }}</h1>
                <p class="patients-subtitle">
                    {{ __('Quản lý các hồ sơ bạn đang chăm sóc.') }}
                </p>
            </div>
            <a href="{{ route('patients.create') }}" class="patients-add-btn">
                + {{ __('Thêm người bệnh') }}
            </a>
        </div>

        @if (session('success'))
            <div class="patients-alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Hồ sơ do người dùng sở hữu -->
        <section class="patients-section">
            <h2 class="patients-section-title">
                {{ __('Hồ sơ của tôi') }} ({{ count($ownedPatients) }})
            </h2>
            <div class="patients-grid">
                @if (count($ownedPatients) > 0)
                    @foreach ($ownedPatients as $patient)
                        <div class="patients-card">
                            <span class="patients-badge patients-badge-owner">
                                {{ __('Người chăm sóc chính') }}
                            </span>
                            <h3 class="patients-card-name">
                                {{ $patient->full_name }}
                            </h3>
                            <p class="patients-card-info">
                                {{ __('Ngày sinh:') }}
                                {{ !empty($patient->birth_date) ? date('d/m/Y', strtotime($patient->birth_date)) : __('Chưa cập nhật') }}
                            </p>
                            <p class="patients-card-info">
                                {{ __('Số điện thoại:') }}
                                {{ $patient->phone ? $patient->phone : __('Chưa cập nhật') }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="patients-empty">
                        {{ __('Bạn chưa tạo hồ sơ người bệnh nào.') }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Hồ sơ được chia sẻ -->
        <section class="patients-section">
            <h2 class="patients-section-title">
                {{ __('Hồ sơ được chia sẻ') }} ({{ count($sharedPatients) }})
            </h2>
            <div class="patients-grid">
                @if (count($sharedPatients) > 0)
                    @foreach ($sharedPatients as $patient)
                        <div class="patients-card">
                            <span class="patients-badge patients-badge-shared">
                                {{ __('Được chia sẻ quyền chăm sóc') }}
                            </span>
                            <h3 class="patients-card-name">
                                {{ $patient->full_name }}
                            </h3>
                            <p class="patients-card-info">
                                {{ __('Người chăm sóc chính:') }}
                                {{ isset($patient->owner) ? $patient->owner->name : __('Chưa xác định') }}
                            </p>
                        </div>
                    @endforeach
                @else
                    <div class="patients-empty">
                        {{ __('Chưa có hồ sơ nào được chia sẻ với bạn.') }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>