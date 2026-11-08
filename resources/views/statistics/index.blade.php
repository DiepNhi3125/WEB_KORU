
<x-app-layout>

    <link rel="stylesheet"
          href="{{ asset('css/statistics.css') }}">

    <div class="st-page">

        <!-- Tiêu đề -->
        <div class="st-heading">

            <div>
                <h1>Thống kê uống thuốc</h1>
                <p>Người bệnh: {{ $patient->full_name }}</p>
            </div>

            <a href="{{ route('reminders.index', $patient) }}"
               class="st-back">
                ← Lịch nhắc uống thuốc
            </a>

        </div>

        <!-- Bộ lọc thời gian -->
        <div class="st-filters">

            @foreach ([
                'today' => 'Hôm nay',
                'week' => 'Tuần này',
                'month' => 'Tháng này'
            ] as $value => $label)

                <a href="{{ route('patients.statistics', [
                    'patient' => $patient,
                    'period' => $value
                ]) }}"
                   class="st-filter
                   {{ $period === $value ? 'is-active' : '' }}">

                    {{ $label }}

                </a>

            @endforeach

        </div>

        <p class="st-period">
            Từ {{ $start->format('d/m/Y') }}
            đến {{ $today->format('d/m/Y') }}
        </p>

        <!-- Các chỉ số -->
        <div class="st-summary">

            <div class="st-card">
                <span>Tổng ca đã có kết quả</span>
                <strong>{{ $total }}</strong>
            </div>

            <div class="st-card st-card--done">
                <span>Đã uống</span>
                <strong>{{ $totalDone }}</strong>
            </div>

            <div class="st-card st-card--missed">
                <span>Bỏ lỡ</span>
                <strong>{{ $totalMissed }}</strong>
            </div>

        </div>

        <!-- Tỷ lệ hoàn thành -->
        <section class="st-panel">

            <div class="st-panel-heading">
                <h2>Tỷ lệ hoàn thành</h2>

                <strong class="st-rate">
                    {{ $completionRate !== null
                        ? number_format($completionRate, 1) . '%'
                        : '—' }}
                </strong>
            </div>

            <div class="st-progress">
                <div class="st-progress-fill"
                     style="width: {{ $completionRate ?? 0 }}%">
                </div>
            </div>

            <p class="st-note">
                Tỷ lệ được tính từ số ca đã uống
                trên tổng số ca đã có kết quả.
            </p>

        </section>

        <!-- Biểu đồ theo ngày -->
        <section class="st-panel">

            <div class="st-panel-heading">
                <h2>Biểu đồ uống thuốc</h2>
            </div>

            <div class="st-legend">
                <span>
                    <i class="st-dot st-dot--done"></i>
                    Đã uống
                </span>

                <span>
                    <i class="st-dot st-dot--missed"></i>
                    Bỏ lỡ
                </span>
            </div>

            @if ($total > 0)

                <div class="st-chart-scroll">

                    <div class="st-chart"
                         style="min-width: {{ max(320, count($dailyStats) * 38) }}px">

                        @foreach ($dailyStats as $day)

                            <div class="st-chart-day">

                                <div class="st-bar-group">

                                    <div class="st-bar st-bar--done"
                                         title="{{ $day['date'] }}: {{ $day['done'] }} ca đã uống"
                                         style="height: {{ ($day['done'] / $maxCount) * 100 }}%">
                                    </div>

                                    <div class="st-bar st-bar--missed"
                                         title="{{ $day['date'] }}: {{ $day['missed'] }} ca bỏ lỡ"
                                         style="height: {{ ($day['missed'] / $maxCount) * 100 }}%">
                                    </div>

                                </div>
                                <span class="st-chart-label">
                                    {{ $day['label'] }}
                                </span>
                            </div>
                        @endforeach

                    </div>

                </div>
            @else
                <div class="st-empty">
                    Chưa có dữ liệu uống thuốc trong khoảng
                    thời gian đã chọn.
                </div>

            @endif
        </section>

    </div>

</x-app-layout>
