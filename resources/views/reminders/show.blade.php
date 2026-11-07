<x-app-layout>
    <x-slot name="header">
        <h2 class="patient-create-header">
            {{ __('Chi tiết lịch nhắc thuốc') }}
        </h2>
    </x-slot>


    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patient-create-page">
        <div class="patient-create-card">

            <h1 class="patient-create-title">
                {{ $reminder->name }}
            </h1>

            <p class="patient-create-description">
                {{ __('Người bệnh:') }} {{ $patient->full_name }}
            </p>

            <div class="patient-form-group">
                <span class="patient-form-label">{{ __('Trạng thái') }}</span>

                <b>
                    @if ($reminder->status === 'active')
                        <span class="patients-badge patients-badge-owner">{{ __('Đang hoạt động') }}</span>
                    @elseif ($reminder->status === 'stopped')
                        <span class="patients-badge patients-badge-shared">{{ __('Đã dừng') }}</span>
                    @else
                        {{ $reminder->status }}
                    @endif
                </b>
            </div>

            <div class="patient-modal-grid">
                <div class="patient-modal-field">
                    <dt>{{ __('Ngày bắt đầu') }}</dt>
                    <dd>{{ !empty($reminder->start_date) ? date('d/m/Y', strtotime($reminder->start_date)) : '' }}</dd>
                </div>

                <div class="patient-modal-field">
                    <dt>{{ __('Ngày kết thúc') }}</dt>
                    <dd>{{ !empty($reminder->end_date) ? date('d/m/Y', strtotime($reminder->end_date)) : '' }}</dd>
                </div>

                <div class="patient-modal-field">
                    <dt>{{ __('Số lần nhắc lại') }}</dt>
                    <dd>{{ $reminder->repeat_count }} {{ __('lần') }}</dd>
                </div>

                <div class="patient-modal-field">
                    <dt>{{ __('Khoảng cách nhắc lại') }}</dt>
                    <dd>{{ $reminder->interval_minutes }} {{ __('phút') }}</dd>
                </div>
            </div>

            <div class="patient-form-group" style="margin-top: 30px;">
                <h2 class="patients-section-title">
                    {{ __('Các giờ uống thuốc') }}
                </h2>

                @if (isset($reminder->doseTimes) && count($reminder->doseTimes) > 0)
                    @foreach ($reminder->doseTimes as $doseTime)
                        <div class="patients-card" style="margin-bottom: 12px;">
                            <div>
                                <b>
                                    {{ substr($doseTime->time_of_day, 0, 5) }}
                                </b>

                                <p class="patients-subtitle mt-2">
                                    {{ __('Áp dụng từ') }}
                                    {{ !empty($doseTime->effective_from) ? date('d/m/Y', strtotime($doseTime->effective_from)) : '' }}

                                    @if (!empty($doseTime->effective_until))
                                        {{ __('đến') }}
                                        {{ date('d/m/Y', strtotime($doseTime->effective_until)) }}
                                    @endif
                                </p>

                                @if (!empty($doseTime->note))
                                    <p class="patients-subtitle mt-2">
                                        {{ __('Ghi chú:') }} {{ $doseTime->note }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="patients-subtitle">
                        {{ __('Chưa có giờ uống thuốc.') }}
                    </p>
                @endif
            </div>

            <div class="patient-form-actions">
                <a href="{{ route('reminders.index', $patient->id) }}" class="patient-btn patient-btn-cancel">
                    {{ __('Quay lại danh sách') }}
                </a>
            </div>

        </div>
    </div>
</x-app-layout>