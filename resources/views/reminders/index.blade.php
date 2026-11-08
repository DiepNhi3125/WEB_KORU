<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold" style="color: #0c235a;">
            {{ __('Lịch nhắc uống thuốc') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="{{ asset('css/reminders.css') }}">

    @php
        $activeCount = $reminders->where('status', 'active')->count();
        $stoppedCount = $reminders->where('status', 'stopped')->count();
    @endphp

    <div class="rm-page" x-data="{ filter: 'all', search: '' }">

        <!-- Đường dẫn điều hướng -->
        <nav class="rm-breadcrumb" aria-label="Đường dẫn">
            <a href="{{ route('patients.index') }}">
                Hồ sơ người bệnh
            </a>
            <span>/</span>
            <span>Lịch nhắc uống thuốc</span>
        </nav>

        <!-- Giới thiệu trang -->
        <header class="rm-hero">
            <p class="rm-eyebrow">KORU · QUẢN LÝ CHĂM SÓC</p>

            <h1>Lịch nhắc uống thuốc</h1>

            <p>
                Quản lý lịch uống thuốc cho
                <span class="rm-owner-name">
                    {{ $patient->full_name }}
                </span>
            </p>
        </header>

        <!-- Tổng quan -->
        <section class="rm-overview" aria-label="Tổng quan lịch nhắc">

            <div class="rm-stat">
                <div class="rm-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="16" rx="2" />
                        <path d="M7 3v4M17 3v4M3 10h18" />
                    </svg>
                </div>

                <div class="rm-stat-copy">
                    <span>Tổng lịch nhắc</span>
                    <strong>{{ $reminders->count() }}</strong>
                </div>
            </div>

            <div class="rm-stat">
                <div class="rm-stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m8 12 3 3 5-6" />
                    </svg>
                </div>

                <div class="rm-stat-copy">
                    <span>Đang hoạt động</span>
                    <strong>{{ $activeCount }}</strong>
                </div>
            </div>

            <div class="rm-stat">
                <div class="rm-stat-icon slate">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M9 9l6 6M15 9l-6 6" />
                    </svg>
                </div>

                <div class="rm-stat-copy">
                    <span>Đã dừng</span>
                    <strong>{{ $stoppedCount }}</strong>
                </div>
            </div>

        </section>

        <!-- Tiêu đề danh sách -->
        <section>
            <div class="rm-toolbar">
                <div>
                    <h2>Danh sách lịch nhắc</h2>
                    <p>Xem và quản lý các lịch uống thuốc đã thiết lập.</p>
                </div>

                @if ($isOwner)
                    <a href="{{ route('reminders.create', $patient) }}" class="rm-primary-btn">

                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>

                        Tạo lịch nhắc
                    </a>
                @endif
            </div>

            <!-- Bộ lọc -->
            @if ($reminders->isNotEmpty())
                <div class="rm-filters" role="group" aria-label="Lọc theo trạng thái">

                    <button type="button" class="rm-filter" :class="{ 'is-active': filter === 'all' }"
                        :aria-pressed="filter === 'all'" @click="filter = 'all'">
                        Tất cả ({{ $reminders->count() }})
                    </button>

                    <button type="button" class="rm-filter" :class="{ 'is-active': filter === 'active' }"
                        :aria-pressed="filter === 'active'" @click="filter = 'active'">
                        Đang hoạt động ({{ $activeCount }})
                    </button>

                    <button type="button" class="rm-filter" :class="{ 'is-active': filter === 'stopped' }"
                        :aria-pressed="filter === 'stopped'" @click="filter = 'stopped'">
                        Đã dừng ({{ $stoppedCount }})
                    </button>
                </div>

                <!-- Tìm kiếm -->
                <div class="mb-5">
                    <input type="search" x-model="search" placeholder="Tìm theo tên lịch nhắc..." aria-label="Tìm lịch nhắc"
                        class="w-full sm:max-w-xs rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-400 focus:ring-blue-400">
                </div>
            @endif

            <!-- Danh sách lịch -->
            <div class="rm-list">

                @forelse ($reminders as $reminder)

                                @php
                                    $currentTimes = $reminder->doseTimes
                                        ->filter(
                                            fn($doseTime) =>
                                                $doseTime->status === 'active'
                                                && $doseTime->effective_until === null
                                        )
                                        ->sortBy('time_of_day');
                                @endphp

                                <article class="rm-card" data-status="{{ $reminder->status }}" data-name="{{ $reminder->name }}" x-show="(filter === 'all' || filter === $el.dataset.status)
                                                 && $el.dataset.name.toLowerCase().includes(search.toLowerCase())" x-cloak>

                                    <!-- Thông tin chính -->
                                    <div class="rm-card-head">

                                        <div class="rm-card-symbol">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <circle cx="12" cy="13" r="8" />
                                                <path d="M12 9v4l3 2" />
                                                <path d="M5 3 2 6M19 3l3 3" />
                                            </svg>
                                        </div>

                                        <div class="rm-card-name">
                                            <h3>
                                                <a href="{{ route('reminders.show', [
                        'patient' => $patient,
                        'reminder' => $reminder
                    ]) }}">
                                                    {{ $reminder->name }}
                                                </a>
                                            </h3>

                                            <p>
                                                Mã lịch #{{ str_pad($reminder->id, 4, '0', STR_PAD_LEFT) }}
                                            </p>
                                        </div>

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

                                    <!-- Khoảng thời gian -->
                                    <div class="rm-card-data">

                                        <span class="rm-metadata">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="3" y="5" width="18" height="16" rx="2" />
                                                <path d="M7 3v4M17 3v4M3 10h18" />
                                            </svg>

                                            {{ $reminder->start_date->format('d/m/Y') }}
                                            –
                                            {{ $reminder->end_date->format('d/m/Y') }}
                                        </span>

                                        <span class="rm-metadata">
                                            {{ $currentTimes->count() }} giờ uống mỗi ngày
                                        </span>

                                    </div>

                                    <!-- Các giờ uống thuốc -->
                                    <div class="rm-card-times">

                                        <span class="rm-card-times-label">
                                            Giờ uống
                                        </span>

                                        @forelse ($currentTimes->take(5) as $doseTime)
                                            <span class="rm-time-pill">
                                                {{ substr($doseTime->time_of_day, 0, 5) }}
                                            </span>
                                        @empty
                                            <span class="rm-muted">
                                                Chưa có giờ uống
                                            </span>
                                        @endforelse

                                        @if ($currentTimes->count() > 5)
                                            <span class="rm-muted">
                                                +{{ $currentTimes->count() - 5 }} giờ khác
                                            </span>
                                        @endif

                                    </div>

                                    <!-- Chân thẻ -->
                                    <div class="rm-card-footer">
                                        <small>
                                            Nhắc lại {{ $reminder->repeat_count }} lần
                                            · Cách nhau {{ $reminder->interval_minutes }} phút
                                        </small>

                                        <a href="{{ route('reminders.show', [
                        'patient' => $patient,
                        'reminder' => $reminder
                    ]) }}" class="rm-text-link">

                                            Xem chi tiết

                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M5 12h14m-6-6 6 6-6 6" />
                                            </svg>
                                        </a>
                                    </div>

                                </article>

                @empty
                    <!-- Khi chưa có lịch nhắc -->
                    <div class="rm-empty">
                        <div class="rm-empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                                <path d="M8 3v4M16 3v4M4 10h16" />
                            </svg>
                        </div>

                        <h3>Chưa có lịch nhắc nào</h3>

                        <p>
                            @if ($isOwner)
                                Hãy tạo lịch nhắc đầu tiên cho người bệnh.
                            @else
                                Người chăm sóc chính chưa thiết lập lịch nhắc.
                            @endif
                        </p>
                    </div>
                @endforelse

                <!-- Khi bộ lọc không có kết quả -->
                @if ($reminders->isNotEmpty())
                    <div class="rm-empty" x-show="!Array.from($el.parentElement.querySelectorAll('.rm-card')).some(
                                 card => (filter === 'all' || card.dataset.status === filter)
                                 && card.dataset.name.toLowerCase().includes(search.toLowerCase())
                             )" x-cloak>
                        <h3>Không tìm thấy lịch phù hợp</h3>
                        <p>Thử thay đổi trạng thái hoặc từ khóa tìm kiếm.</p>
                    </div>
                @endif

            </div>
        </section>

        <!-- Quay lại -->
        <div class="rm-page-actions">
            <a href="{{ route('patients.index') }}" class="rm-back-link">
                ← Quay lại hồ sơ người bệnh
            </a>
        </div>

    </div>
</x-app-layout>