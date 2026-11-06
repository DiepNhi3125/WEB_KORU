<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">{{ __('Chỉnh sửa hồ sơ người bệnh') }}</h2>
    </x-slot>

    <!-- Nhúng file CSS chung patients.css -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">
            <h1 class="patient-create-title">
                {{ __('Cập nhật thông tin người bệnh') }}
            </h1>
            <p class="patient-create-description">
                {{ __('Chỉnh sửa thông tin của hồ sơ #') }}{{ $patient->id }}
            </p>

            <form method="POST" action="{{ route('patients.update', $patient->id) }}">
                @csrf
                @method('PATCH')

                <!-- Họ và tên -->
                <div class="patient-form-group">
                    <label for="full_name" class="patient-form-label">
                        {{ __('Họ và tên') }} <span class="patient-required">*</span>
                    </label>
                    <input type="text"
                        id="full_name"
                        name="full_name"
                        class="patient-form-input"
                        value="{{ old('full_name', $patient->full_name) }}"
                        maxlength="255"
                        required>
                    @error('full_name')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ngày sinh -->
                <div class="patient-form-group">
                    <label for="birth_date" class="patient-form-label">
                        {{ __('Ngày sinh') }}
                    </label>
                    <input type="date"
                        id="birth_date"
                        name="birth_date"
                        class="patient-form-input"
                        value="{{ old('birth_date', !empty($patient->birth_date) ? date('Y-m-d', strtotime($patient->birth_date)) : '') }}"
                        max="{{ date('Y-m-d') }}">
                    @error('birth_date')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Giới tính -->
                <div class="patient-form-group">
                    <label for="gender" class="patient-form-label">
                        {{ __('Giới tính') }}
                    </label>
                    <select id="gender" name="gender" class="patient-form-input">
                        <option value="">{{ __('Chọn giới tính') }}</option>
                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>
                            {{ __('Nam') }}
                        </option>
                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                            {{ __('Nữ') }}
                        </option>
                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>
                            {{ __('Khác') }}
                        </option>
                    </select>
                    @error('gender')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div class="patient-form-group">
                    <label for="phone" class="patient-form-label">
                        {{ __('Số điện thoại') }} <span class="patient-required">*</span>
                    </label>
                    <input type="tel"
                        id="phone"
                        name="phone"
                        class="patient-form-input"
                        value="{{ old('phone', $patient->phone) }}"
                        maxlength="10"
                        inputmode="numeric"
                        required>
                    @error('phone')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Các nút hành động -->
                <div class="patient-form-actions">
                    <a href="{{ route('patients.index') }}" class="patient-btn patient-btn-cancel">
                        {{ __('Hủy') }}
                    </a>
                    <button type="submit" class="patient-btn patient-btn-save">
                        {{ __('Lưu thay đổi') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>