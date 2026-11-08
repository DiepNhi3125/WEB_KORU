<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/reminders.css') }}">

    @php
        // Các mốc giờ đang áp dụng
        $currentTimes = $reminder->doseTimes
            ->filter(
                fn($doseTime) =>
                    $doseTime->status === 'active'
                    && $doseTime->effective_until === null
            )
            ->sortBy('time_of_day');

        // Các mốc giờ đã ngừng áp dụng
        $historyTimes = $reminder->doseTimes
            ->filter(
                fn($doseTime) =>
                    $doseTime->status !== 'active'
                    || $doseTime->effective_until !== null
            )
            ->sortBy('time_of_day');
    @endphp

    <div class="rm-page">

        <!-- 1. THÔNG TIN LỊCH NHẮC -->
        <div class="rm-detail-heading">

            <div class="rm-detail-title">

                <h1>{{ $reminder->name }}</h1>

                <div class="rm-detail-person">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 21v-2a8 8 0 0 1 16 0v2" />
                    </svg>

                    <span>
                        {{ $patient->full_name }}
                        · Mã lịch #{{ str_pad((string) $reminder->id, 4, '0', STR_PAD_LEFT) }}
                    </span>

                </div>

            </div>


            <!-- Các thao tác với lịch nhắc -->

            <div class="rm-detail-actions">

                @if ((int) $patient->owner_id === (int) auth()->id()&& $reminder->status === 'active')

                                <!-- Chỉnh sửa lịch nhắc -->
                                <a href="{{ route('reminders.edit', ['patient' => $patient,'reminder' => $reminder->id]) }}" class="rm-detail-edit">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L9 17l-4 1 1-4Z" />
                                    </svg>

                                    Chỉnh sửa
                                </a>

                                <!-- Dừng lịch nhắc -->
                                <form method="POST" action="{{ route('reminders.stop', ['patient' => $patient,'reminder' => $reminder->id]) }}"
                                    onsubmit="return confirm('Bạn có chắc muốn dừng lịch nhắc này? Lịch sử uống thuốc vẫn được giữ lại.');">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="rm-detail-stop">
                                        Dừng lịch
                                    </button>

                                </form>

                @endif

                <!-- Trạng thái lịch nhắc -->
                <span class="rm-status
        {{ $reminder->status === 'active'? 'rm-status--active': 'rm-status--stopped' }}">

                    @if ($reminder->status === 'active')
                        Đang hoạt động
                    @elseif ($reminder->status === 'stopped')
                        Đã dừng
                    @else
                        {{ $reminder->status }}
                    @endif

                </span>

            </div>



        </div>

        <!-- 2. NỘI DUNG CHI TIẾT -->
        <div class="rm-detail-grid">

            <!-- CỘT TRÁI -->
            <div class="rm-detail-main">

                <!-- THỜI GIAN ÁP DỤNG -->
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
                        </div>

                    </div>

                    <div class="rm-dates">

                        <!-- Ngày bắt đầu -->
                        <div>

                            <span class="rm-date-label">
                                BẮT ĐẦU
                            </span>

                            <strong class="rm-date-value">
                                {{ $reminder->start_date->format('d/m/Y') }}
                            </strong>

                        </div>

                        <!-- Mũi tên -->
                        <div class="rm-date-separator">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14m-6-6 6 6-6 6" />
                            </svg>

                        </div>

                        <!-- Ngày kết thúc -->
                        <div>

                            <span class="rm-date-label">
                                KẾT THÚC
                            </span>

                            <strong class="rm-date-value">
                                {{ $reminder->end_date->format('d/m/Y') }}
                            </strong>

                        </div>

                    </div>

                </section>

                <!-- CÁC MỐC GIỜ UỐNG THUỐC -->
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

                                <!-- Thông tin giờ uống -->
                                <div class="rm-dose-info">

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
                                Không có mốc giờ đang áp dụng.
                            </p>

                        @endforelse

                    </div>

                    <!-- LỊCH SỬ GIỜ UỐNG -->
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
            <!-- KẾT THÚC CỘT TRÁI -->


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
                    </div>

                </div>

                <!-- Số lần nhắc -->
                <div class="rm-rule-row">

                    <span>Số lần nhắc lại</span>

                    <strong>
                        {{ $reminder->repeat_count }}
                        <small>lần</small>
                    </strong>

                </div>

                <!-- Khoảng cách giữa các lần nhắc -->
                <div class="rm-rule-row">

                    <span>Khoảng cách nhắc</span>

                    <strong>
                        {{ $reminder->interval_minutes }}
                        <small>phút</small>
                    </strong>

                </div>

                <!-- Thông tin quy tắc -->
                <div class="rm-rule-note">

                    <strong>Cách hoạt động theo cấu hình</strong>

                    <p class="mt-2">

                        Mỗi mốc giờ có một lần nhắc ban đầu.

                        Nếu người bệnh chưa xác nhận, lịch được
                        cấu hình để nhắc thêm tối đa

                        <b>{{ $reminder->repeat_count }} lần</b>,

                        cách nhau

                        <b>{{ $reminder->interval_minutes }} phút</b>.

                    </p>

                </div>

            </aside>
            <!-- KẾT THÚC CỘT PHẢI -->

        </div>
        <!-- KẾT THÚC RM-DETAIL-GRID -->


        <!-- 3. QUAY LẠI DANH SÁCH -->
        <div class="rm-page-actions">

            <a href="{{ route('reminders.index', $patient) }}" class="rm-back-link">

                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5m6-6-6 6 6 6" />
                </svg>

                Quay lại danh sách lịch nhắc

            </a>

        </div>

    </div>
    <!-- KẾT THÚC RM-PAGE -->

</x-app-layout>