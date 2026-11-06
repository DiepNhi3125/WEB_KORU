
<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">Tạo lịch nhắc thuốc</h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">

            <h1 class="patient-create-title">Thêm lịch nhắc</h1>

            <p class="patient-create-description">
                Người bệnh: {{ $patient->full_name }}
            </p>

            <form method="POST"
                  action="{{ route('reminders.store', $patient) }}">
                @csrf

                <div class="patient-form-group">
                    <label class="patient-form-label" for="name">
                        Tên lịch nhắc
                    </label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           class="patient-form-input"
                           placeholder="VD: Uống thuốc huyết áp"
                           required>
                    @error('name')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="patient-form-group">
                    <label class="patient-form-label" for="start_date">
                        Ngày bắt đầu
                    </label>
                    <input type="date" id="start_date" name="start_date"
                           value="{{ old('start_date') }}"
                           class="patient-form-input" required>
                    @error('start_date')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="patient-form-group">
                    <label class="patient-form-label" for="end_date">
                        Ngày kết thúc
                    </label>
                    <input type="date" id="end_date" name="end_date"
                           value="{{ old('end_date') }}"
                           class="patient-form-input" required>
                    @error('end_date')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="patient-form-group">
                    <label class="patient-form-label">
                        Giờ uống thuốc mỗi ngày
                    </label>

                    <div id="doseTimeList" class="space-y-3">
                        @foreach (old('times', ['07:00']) as $time)
                            <input type="time"
                                   name="times[]"
                                   value="{{ $time }}"
                                   class="patient-form-input"
                                   aria-label="Giờ uống thuốc"
                                   required>
                        @endforeach
                    </div>

                    <button type="button"
                            id="addDoseTime"
                            class="patients-detail-link">
                        + Thêm giờ uống
                    </button>

                    @if ($errors->has('times') || $errors->has('times.*'))
                        <p class="patient-form-error">
                            {{ $errors->first('times') ?: $errors->first('times.*') }}
                        </p>
                    @endif
                </div>

                <div class="patient-form-group">
                    <label class="patient-form-label" for="repeat_count">
                        Số lần nhắc lại
                    </label>
                    <input type="number" id="repeat_count"
                           name="repeat_count"
                           min="0" max="20"
                           value="{{ old('repeat_count', 3) }}"
                           class="patient-form-input" required>
                    @error('repeat_count')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="patient-form-group">
                    <label class="patient-form-label" for="interval_minutes">
                        Khoảng cách giữa các lần nhắc (phút)
                    </label>
                    <input type="number" id="interval_minutes"
                           name="interval_minutes"
                           min="1" max="1440"
                           value="{{ old('interval_minutes', 5) }}"
                           class="patient-form-input" required>
                    @error('interval_minutes')
                        <p class="patient-form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="patient-form-actions">
                    <a href="{{ route('reminders.index', $patient) }}"
                       class="patient-btn patient-btn-cancel">
                        Hủy
                    </a>

                    <button type="submit"
                            class="patient-btn patient-btn-save">
                        Lưu lịch nhắc
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('addDoseTime')
            .addEventListener('click', function () {
                const list = document.getElementById('doseTimeList');

                if (list.querySelectorAll('input').length >= 12) return;

                const input = document.createElement('input');
                input.type = 'time';
                input.name = 'times[]';
                input.className = 'patient-form-input';
                input.setAttribute('aria-label', 'Giờ uống thuốc');
                input.required = true;

                list.appendChild(input);
            });
    </script>
</x-app-layout>
