
<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">
            {{ __('Liên kết hồ sơ người bệnh') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">
            <h1 class="patient-create-title">
                {{ __('Nhập mã chia sẻ') }}
            </h1>

            <p class="patient-create-description">
                Nhập mã do người chăm sóc chính cung cấp
                để liên kết với hồ sơ người bệnh.
            </p>

            <form method="POST"
                  action="{{ route('patients.join.store') }}">
                @csrf

                <div class="patient-form-group">
                    <label for="code" class="patient-form-label">
                        {{ __('Mã chia sẻ') }}
                        <span class="patient-required">*</span>
                    </label>

                    <input type="text"
                           id="code"
                           name="code"
                           class="patient-form-input"
                           value="{{ old('code') }}"
                           maxlength="24"
                           autocomplete="off"
                           spellcheck="false"
                           required>

                    @error('code')
                        <p class="patient-form-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="patient-form-actions">
                    <a href="{{ route('patients.index') }}"
                       class="patient-btn patient-btn-cancel">
                        {{ __('Hủy') }}
                    </a>

                    <button type="submit"
                            class="patient-btn patient-btn-save">
                        {{ __('Liên kết hồ sơ') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
