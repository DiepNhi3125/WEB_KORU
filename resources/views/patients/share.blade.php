
<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">
            {{ __('Chia sẻ hồ sơ người bệnh') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">

            <h1 class="patient-create-title">
                {{ __('Mã chia sẻ hồ sơ') }}
            </h1>

            <p class="patient-create-description">
                {{ __('Hồ sơ:') }} {{ $patient->full_name }}
                (#{{ $patient->id }})
            </p>

            <!-- Mã chia sẻ cố định -->
            <div class="patient-form-group">
                <label for="sharingCode" class="patient-form-label">
                    {{ __('Mã chia sẻ cố định') }}
                </label>

                <input type="text"
                       id="sharingCode"
                       class="patient-form-input"
                       value="{{ $code }}"
                       readonly
                       spellcheck="false">

                <p class="patient-form-hint">
                    {{ __('Gửi mã này cho người chăm sóc mà bạn muốn chia sẻ quyền truy cập hồ sơ.') }}
                </p>
            </div>

            <!-- Mã QR chia sẻ hồ sơ: THÊM Ở ĐÂY -->
            <div class="patient-share-qr">
                <h2 class="patient-form-label">
                    {{ __('Quét QR để liên kết hồ sơ') }}
                </h2>

                <img src="{{ $qrCode }}"
                     alt="Mã QR liên kết hồ sơ người bệnh"
                     width="260"
                     height="260">

                <p class="patient-form-hint">
                    {{ __('Người chăm sóc phụ quét mã QR bằng camera điện thoại để mở trang liên kết hồ sơ.') }}
                </p>
            </div>

            <p class="patient-form-hint">
                {{ __('Mã không hết hạn. Chỉ chia sẻ với người đáng tin cậy.') }}
            </p>

            <div class="patient-form-actions">
                <a href="{{ route('patients.index') }}"
                   class="patient-btn patient-btn-cancel">
                    {{ __('Quay lại') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
