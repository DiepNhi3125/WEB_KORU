<x-app-layout>



    <link rel="stylesheet" href="{{ asset('css/reminders.css') }}">

    @php
        // Những mốc giờ chưa bị thay thế
        $currentTimes = $reminder->doseTimes
            ->filter(
                fn($doseTime) =>
                    $doseTime->status === 'active'
                    && $doseTime->effective_until === null
            )
            ->sortBy('time_of_day');

        // Những mốc giờ cũ được giữ lại làm lịch sử
        $historyTimes = $reminder->doseTimes
            ->filter(
                fn($doseTime) =>
                    $doseTime->status !== 'active'
                    || $doseTime->effective_until !== null
            )
            ->sortBy('time_of_day');
    @endphp

    <div class="rm-page">



        <!-- Tiêu đề lịch nhắc -->
        <div class="rm-detail-title">
            <h1>{{ $reminder->name }}</h1>

            <div class="rm-detail-person">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21v-2a8 8 0 0 1 16 0v2" />
                </svg>

                <span>
                    {{ $patient->full_name }}
                    ·
                    Mã lịch #{{ str_pad($reminder->id, 4, '0', STR_PAD_LEFT) }}
                </span>
            </div>
        </div>

        <!-- Trạng thái -->
        <span class="rm-status
                {{ $reminder->status === 'active'
    ? 'rm-status--active'
    : 'rm-status--stopped' }}">

            @if ($reminder->status === 'active')
                Đang hoạt động
            @elseif ($reminder->status === 'stopped')
                Đã dừng
            @else
                {{ $reminder->status }}
            @endif
        </span>

    </div>

    <!-- Nội dung chi tiết -->
    <div class="rm-detail-grid">

        <!-- CỘT TRÁI -->
        <div class="rm-detail-main">

            <!-- Khoảng ngày áp dụng -->
            <section class="rm-panel">

                <div class="rm-panel-header">
                    <div class="rm-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="5" width="18" height="16" rx="2" />
                            <path d="M7 3v4M17 3v4M3 10h18" />
                        </svg>
                    </div>

                    <div>
                        <h2>Thời gian áp dụng</h2>
                        <p>Khoảng ngày thực hiện lịch nhắc</p>
                    </div>
                </div>

                <div class="rm-dates">

                    <div>
                        <span class="rm-date-label">BẮT ĐẦU</span>

                        <strong class="rm-date-value">
                            {{ $reminder->start_date->format('d/m/Y') }}
                        </strong>
                    </div>

                    <div class="rm-date-separator">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14m-6-6 6 6-6 6" />
                        </svg>
                    </div>

                    <div>
                        <span class="rm-date-label">KẾT THÚC</span>

                        <strong class="rm-date-value">
                            {{ $reminder->end_date->format('d/m/Y') }}
                        </strong>
                    </div>

                </div>
            </section>

            <!-- Các giờ uống thuốc -->
            <section class="rm-panel">

                <div class="rm-panel-header">
                    <div class="rm-panel-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 2" />
                        </svg>
                    </div>

                    <div>
                        <h2>Các mốc giờ uống thuốc</h2>
                        <p>
                            {{ $currentTimes->count() }}
                            mốc giờ đang được thiết lập
                        </p>
                    </div>
                </div>

                <div class="rm-dose-list">

                    @forelse ($currentTimes as $doseTime)

                        <div class="rm-dose">

                            <!-- Giờ uống -->
                            <div class="rm-dose-clock">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 2" />
                                </svg>

                                {{ substr($doseTime->time_of_day, 0, 5) }}
                            </div>

                            <!-- Chi tiết mốc giờ -->
                            <div class="rm-dose-info">

                                <span class="rm-dose-label">
                                    Khung giờ đã thiết lập
                                </span>

                                <span class="rm-dose-range">
                                    Áp dụng từ
                                    {{ $doseTime->effective_from->format('d/m/Y') }}
                                </span>

                                @if ($doseTime->note)
                                    <span class="rm-dose-note">
                                        Ghi chú: {{ $doseTime->note }}
                                    </span>
                                @endif

                            </div>
                        </div>

                    @empty
                        <p class="rm-muted">
                            Không có mốc giờ hiện tại.
                        </p>
                    @endforelse

                </div>

                <!-- Lịch sử thay đổi giờ uống -->
                @if ($historyTimes->isNotEmpty())

                    <div class="mt-6 pt-5 border-t border-gray-100">

                        <h3 class="text-sm font-bold mb-4" style="color: #0c235a;">
                            Lịch sử giờ uống
                        </h3>

                        <div class="rm-dose-list">

                            @foreach ($historyTimes as $doseTime)

                                <div class="rm-dose">

                                    <div class="rm-dose-clock">
                                        {{ substr($doseTime->time_of_day, 0, 5) }}
                                    </div>

                                    <div class="rm-dose-info">

                                        <span class="rm-dose-label">
                                            Khung giờ trước đây
                                        </span>

                                        <span class="rm-dose-range">
                                            Từ
                                            {{ $doseTime->effective_from->format('d/m/Y') }}

                                            @if ($doseTime->effective_until)
                                                đến
                                                {{ $doseTime->effective_until->format('d/m/Y') }}
                                            @endif
                                        </span>

                                        @if ($doseTime->note)
                                            <span class="rm-dose-note">
                                                Ghi chú: {{ $doseTime->note }}
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>
                    </div>

                @endif

            </section>

        </div>

        <!-- CỘT PHẢI: QUY TẮC NHẮC LẠI -->
        <aside class="rm-panel rm-rule">

            <div class="rm-panel-header">
                <div class="rm-panel-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M20 7h-9a4 4 0 0 0-4 4v1" />
                        <path d="M4 17h9a4 4 0 0 0 4-4v-1" />
                        <path d="M16 4l4 3-4 3" />
                        <path d="M8 14l-4 3 4 3" />
                    </svg>
                </div>

                <div>
                    <h2>Quy tắc nhắc lại</h2>
                    <p>Cấu hình thông báo của lịch</p>
                </div>
            </div>

            <!-- Số lần nhắc lại -->
            <div class="rm-rule-row">
                <span>Số lần nhắc lại</span>

                <strong>
                    {{ $reminder->repeat_count }}
                    <small>lần</small>
                </strong>
            </div>

            <!-- Khoảng cách nhắc -->
            <div class="rm-rule-row">
                <span>Khoảng cách nhắc</span>

                <strong>
                    {{ $reminder->interval_minutes }}
                    <small>phút</small>
                </strong>
            </div>

            <!-- Giải thích quy tắc -->
            <div class="rm-rule-note">

                <strong>Cách hoạt động theo cấu hình</strong>

                <p class="mt-2">
                    Mỗi mốc giờ có một lần nhắc ban đầu.
                    Nếu người bệnh chưa xác nhận,
                    lịch được cấu hình để nhắc thêm tối đa
                    <b>{{ $reminder->repeat_count }} lần</b>,
                    cách nhau
                    <b>{{ $reminder->interval_minutes }} phút</b>.
                </p>

            </div>

            <div class="rm-tip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M12 11v5M12 7h.01" />
                </svg>

                <span>
                    Đây là quy tắc đã lưu cho lịch nhắc.
                    Chức năng gửi thông báo tự động
                    sẽ được triển khai ở bước xử lý lịch.
                </span>
            </div>

        </aside>

    </div>

    <!-- Quay lại danh sách -->
    <div class="rm-page-actions">
        <a href="{{ route('reminders.index', $patient) }}" class="rm-back-link">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5m6-6-6 6 6 6" />
            </svg>

            Quay lại danh sách lịch nhắc
        </a>
    </div>

    </div>
</x-app-layout>