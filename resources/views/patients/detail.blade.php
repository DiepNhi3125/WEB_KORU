<x-app-layout>
    <x-slot name="header">
        <h2 class="patients-header">{{ __('Chi tiết hồ sơ người bệnh') }}</h2>
    </x-slot>

    <!-- Nhúng file CSS chung patients.css -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">

            <div class="patient-detail-heading">
                <div>
                    <span class="patients-badge {{ $isOwner ? 'patients-badge-owner' : 'patients-badge-shared' }}">
                        {{ $isOwner ? __('Người chăm sóc chính') : __('Được chia sẻ') }}
                    </span>

                    <h1 class="patient-create-title">
                        {{ $patient->full_name }}
                    </h1>

                    <p class="patient-create-description">
                        {{ __('Mã hồ sơ') }} #{{ $patient->id }}
                    </p>
                </div>
            </div>

            <h2 class="patient-detail-section-title">
                {{ __('Thông tin người bệnh') }}
            </h2>

            <div class="patient-detail-grid">
                <!-- Họ và tên -->
                <div class="patient-detail-field">
                    <span>{{ __('Họ và tên') }}</span>
                    <strong>{{ $patient->full_name }}</strong>
                </div>

                <!-- Ngày sinh -->
                <div class="patient-detail-field">
                    <span>{{ __('Ngày sinh') }}</span>
                    <strong>
                        {{ !empty($patient->birth_date) ? date('d/m/Y', strtotime($patient->birth_date)) : __('Chưa cập nhật') }}
                    </strong>
                </div>

                <!-- Giới tính (Thay match() bằng @if @elseif quen thuộc) -->
                <div class="patient-detail-field">
                    <span>{{ __('Giới tính') }}</span>
                    <strong>
                        @if ($patient->gender == 'male')
                            {{ __('Nam') }}
                        @elseif ($patient->gender == 'female')
                            {{ __('Nữ') }}
                        @elseif ($patient->gender == 'other')
                            {{ __('Khác') }}
                        @else
                            {{ __('Chưa cập nhật') }}
                        @endif
                    </strong>
                </div>

                <!-- Số điện thoại -->
                <div class="patient-detail-field">
                    <span>{{ __('Số điện thoại') }}</span>
                    <strong>
                        {{ !empty($patient->phone) ? $patient->phone : __('Chưa cập nhật') }}
                    </strong>
                </div>

                <!-- Người chăm sóc chính -->
                <div class="patient-detail-field">
                    <span>{{ __('Người chăm sóc chính') }}</span>
                    <strong>
                        {{ isset($patient->owner) ? $patient->owner->name : __('Chưa cập nhật') }}
                    </strong>
                </div>
            </div>

            <!-- Nút quay lại -->
            <div class="patient-form-actions">
                <a href="{{ route('patients.index') }}" class="patient-btn patient-btn-cancel">
                    {{ __('Quay lại danh sách') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>