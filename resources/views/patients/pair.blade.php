<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">
            {{ __('Ghép thiết bị người bệnh') }}
        </h2>
    </x-slot>

    <!-- Nhúng file CSS chung patients.css -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">

            <h1 class="patient-create-title">
                {{ __('Ghép thiết bị') }}
            </h1>

            <p class="patient-create-description">
                {{ __('Hồ sơ:') }} {{ $patient->full_name }} (#{{ $patient->id }})
            </p>

            <div class="patient-share-qr">
                <h2 class="patient-form-label">
                    {{ __('Quét QR bằng điện thoại người bệnh') }}
                </h2>

                <img src="{{ $qrCode }}"
                     alt="{{ __('Mã QR ghép thiết bị người bệnh') }}"
                     width="260"
                     height="260">

                <p class="patient-form-hint">
                    {{ __('Sau khi quét QR, người bệnh cần xác thực OTP trước khi sử dụng thiết bị.') }}
                </p>
            </div>

            <div class="patient-form-actions">
                <a href="{{ route('patients.index') }}" class="patient-btn patient-btn-cancel">
                    {{ __('Quay lại') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>