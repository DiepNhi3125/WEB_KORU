<div class="patient-form-group" style="margin-top: 30px;">
    <h2 class="patients-section-title">
        {{ __('Các giờ uống thuốc') }}
    </h2>

    @if (isset($reminder->doseTimes) && count($reminder->doseTimes) > 0)
        @foreach ($reminder->doseTimes as $doseTime)
            <div class="patients-card" style="margin-bottom: 12px;">
                <div>
                    <strong>
                        {{ substr($doseTime->time_of_day, 0, 5) }}
                    </strong>

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