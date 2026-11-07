<x-app-layout>
    <x-slot name="header">
        <h2 class="patients-header">{{ __('Lịch nhắc uống thuốc') }}</h2>
    </x-slot>

    <!-- Nhúng file CSS chung patients.css -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patients-page">

        <div class="patients-toolbar">
            <div>
                <h1 class="patients-title">{{ __('Lịch nhắc thuốc') }}</h1>
                <p class="patients-subtitle">
                    {{ __('Người bệnh:') }} {{ $patient->full_name }}
                </p>
            </div>

            @if ($isOwner)
                <a href="{{ route('reminders.create', $patient->id) }}" class="patients-add-btn">
                    + {{ __('Tạo lịch nhắc') }}
                </a>
            @endif
        </div>

        <div class="patients-list">
            @if (count($reminders) > 0)
                @foreach ($reminders as $reminder)
                        <div class="patients-card">
                            <div>
                                <h3 class="patients-card-name">
                                    {{ $reminder->name }}
                                </h3>

                                <p class="patients-subtitle mt-2">
                                    {{ !empty($reminder->start_date) ? date('d/m/Y', strtotime($reminder->start_date)) : '' }}
                                    -
                                    {{ !empty($reminder->end_date) ? date('d/m/Y', strtotime($reminder->end_date)) : '' }}
                                </p>

                                <p class="patients-subtitle mt-2">
                                    {{ __('Giờ uống:') }}

                                    @if (isset($reminder->doseTimes) && count($reminder->doseTimes) > 0)
                                        @foreach ($reminder->doseTimes->sortBy('time_of_day') as $doseTime)
                                            {{ substr($doseTime->time_of_day, 0, 5) }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    @else
                                        {{ __('Chưa đặt giờ') }}
                                    @endif
                                </p>

                                <p class="patients-subtitle mt-2">
                                    {{ __('Trạng thái:') }}
                                    @if ($reminder->status === 'active')
                                        <span class="patients-badge patients-badge-owner">{{ __('Đang hoạt động') }}</span>
                                    @elseif ($reminder->status === 'stopped')
                                        <span class="patients-badge patients-badge-shared">{{ __('Đã dừng') }}</span>
                                    @else
                                        {{ $reminder->status }}
                                    @endif
                                </p>
                                <a href="{{ route('reminders.show', [
                        'patient' => $patient,
                        'reminder' => $reminder
                    ]) }}" class="patients-detail-link">
                                    Xem chi tiết →
                                </a>
                            </div>
                        </div>
                @endforeach
            @else
                <div class="patients-empty">
                    {{ __('Chưa có lịch nhắc thuốc nào.') }}
                </div>
            @endif
        </div>

        <div class="patient-form-actions">
            <a href="{{ route('patients.index') }}" class="patient-btn patient-btn-cancel">
                {{ __('Quay lại hồ sơ') }}
            </a>
        </div>

    </div>
</x-app-layout>