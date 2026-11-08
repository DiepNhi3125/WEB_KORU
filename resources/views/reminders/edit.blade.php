
<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/reminders.css') }}">

    @php
        $hasStarted = $reminder->start_date
            ->lte(\Carbon\Carbon::today('Asia/Ho_Chi_Minh'));

        $formTimes = old(
            'times',
            $currentTimes
                ->map(fn ($time) => substr($time->time_of_day, 0, 5))
                ->values()
                ->all()
        );

        if (empty($formTimes)) {
            $formTimes = ['07:00'];
        }
    @endphp

    <div class="rm-page">

        <!-- TÊN LỊCH NHẮC -->
        <div class="rm-edit-heading">
            <h1>{{ $reminder->name }}</h1>
            <p>Người bệnh: {{ $patient->full_name }}</p>
        </div>

        <form
            method="POST"
            action="{{ route('reminders.update', [
                'patient' => $patient,
                'reminder' => $reminder->id
            ]) }}"
            class="rm-edit-form">

            @csrf
            @method('PATCH')

            <!-- THÔNG TIN CHUNG -->
            <section class="rm-panel">

                <h2 class="rm-edit-section-title">
                    Thông tin lịch nhắc
                </h2>

                <div class="rm-edit-field">
                    <label for="name" class="rm-edit-label">
                        Tên lịch nhắc
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="rm-edit-input"
                        value="{{ old('name', $reminder->name) }}"
                        maxlength="255"
                        required>

                    @error('name')
                        <p class="rm-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rm-edit-grid">

                    <div class="rm-edit-field">
                        <label for="start_date" class="rm-edit-label">
                            Ngày bắt đầu
                        </label>

                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="rm-edit-input"
                            value="{{ old('start_date', $reminder->start_date->format('Y-m-d')) }}"
                            @if ($hasStarted) readonly @endif
                            required>

                        @if ($hasStarted)
                            <p class="rm-edit-hint">
                                Lịch đã bắt đầu nên không thể đổi ngày này.
                            </p>
                        @endif

                        @error('start_date')
                            <p class="rm-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rm-edit-field">
                        <label for="end_date" class="rm-edit-label">
                            Ngày kết thúc
                        </label>

                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="rm-edit-input"
                            value="{{ old('end_date', $reminder->end_date->format('Y-m-d')) }}"
                            required>

                        @error('end_date')
                            <p class="rm-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </section>

            <!-- GIỜ UỐNG THUỐC -->
            <section class="rm-panel">

                <h2 class="rm-edit-section-title">
                    Các giờ uống thuốc
                </h2>

                <p class="rm-edit-hint">
                    Có thể thiết lập tối đa 12 mốc giờ mỗi ngày.
                </p>

                <div id="rm-edit-times" class="rm-edit-times">

                    @foreach ($formTimes as $time)

                        <div class="rm-edit-time-row">

                            <input
                                type="time"
                                name="times[]"
                                class="rm-edit-input"
                                value="{{ $time }}"
                                aria-label="Giờ uống thuốc"
                                required>

                            <button
                                type="button"
                                class="rm-edit-remove"
                                data-remove-time>
                                Xóa
                            </button>

                        </div>

                    @endforeach

                </div>

                <button
                    type="button"
                    id="rm-add-time"
                    class="rm-edit-add">
                    + Thêm giờ uống
                </button>

                @if ($errors->has('times') || $errors->has('times.*'))
                    <p class="rm-edit-error">
                        {{ $errors->first('times') ?: $errors->first('times.*') }}
                    </p>
                @endif

                <p class="rm-edit-hint">
                    Nếu thay đổi giờ uống của lịch đã bắt đầu,
                    giờ mới sẽ được áp dụng từ ngày hôm sau.
                    Lịch sử giờ uống trước đó được giữ lại.
                </p>

            </section>

            <!-- QUY TẮC NHẮC LẠI -->
            <section class="rm-panel">

                <h2 class="rm-edit-section-title">
                    Quy tắc nhắc lại
                </h2>

                <div class="rm-edit-grid">

                    <div class="rm-edit-field">
                        <label for="repeat_count" class="rm-edit-label">
                            Số lần nhắc lại
                        </label>

                        <input
                            type="number"
                            id="repeat_count"
                            name="repeat_count"
                            class="rm-edit-input"
                            min="0"
                            max="20"
                            value="{{ old('repeat_count', $reminder->repeat_count) }}"
                            required>

                        @error('repeat_count')
                            <p class="rm-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rm-edit-field">
                        <label for="interval_minutes" class="rm-edit-label">
                            Khoảng cách nhắc (phút)
                        </label>

                        <input
                            type="number"
                            id="interval_minutes"
                            name="interval_minutes"
                            class="rm-edit-input"
                            min="1"
                            max="1440"
                            value="{{ old('interval_minutes', $reminder->interval_minutes) }}"
                            required>

                        @error('interval_minutes')
                            <p class="rm-edit-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </section>

            <!-- NÚT HÀNH ĐỘNG -->
            <div class="rm-edit-actions">

                <a
                    href="{{ route('reminders.show', [
                        'patient' => $patient,
                        'reminder' => $reminder->id
                    ]) }}"
                    class="rm-edit-cancel">
                    Hủy
                </a>

                <button type="submit" class="rm-edit-save">
                    Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const list = document.getElementById('rm-edit-times');
            const addButton = document.getElementById('rm-add-time');
            const MAX_TIMES = 12;

            if (!list || !addButton) return;

            function updateButtons() {
                const rows = list.querySelectorAll('.rm-edit-time-row');

                addButton.disabled = rows.length >= MAX_TIMES;

                rows.forEach(row => {
                    const removeButton = row.querySelector('[data-remove-time]');
                    removeButton.disabled = rows.length <= 1;
                });
            }

            addButton.addEventListener('click', function () {
                if (list.querySelectorAll('.rm-edit-time-row').length >= MAX_TIMES) {
                    return;
                }

                const row = document.createElement('div');
                row.className = 'rm-edit-time-row';

                const input = document.createElement('input');
                input.type = 'time';
                input.name = 'times[]';
                input.className = 'rm-edit-input';
                input.required = true;
                input.setAttribute('aria-label', 'Giờ uống thuốc');

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'rm-edit-remove';
                removeButton.dataset.removeTime = '';
                removeButton.textContent = 'Xóa';

                row.append(input, removeButton);
                list.appendChild(row);

                updateButtons();
                input.focus();
            });

            list.addEventListener('click', function (event) {
                const button = event.target.closest('[data-remove-time]');

                if (!button || button.disabled) return;

                button.closest('.rm-edit-time-row').remove();
                updateButtons();
            });

            updateButtons();
        });
    </script>

</x-app-layout>
