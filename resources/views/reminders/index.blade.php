
<x-app-layout>

    <link rel="stylesheet" href="{{ asset('css/reminders.css') }}">

    @php
        $activeCount = $reminders->where('status', 'active')->count();
        $stoppedCount = $reminders->where('status', 'stopped')->count();
    @endphp

    <div class="rm-list-page"
         x-data="{ filter: 'all', search: '' }">

        <!-- 1. TIÊU ĐỀ VÀ NÚT TẠO -->
        <div class="rm-list-top">

            <div>
                <h1 class="rm-list-title">
                    Lịch nhắc uống thuốc
                </h1>

                <p class="rm-list-subtitle">
                    {{ $patient->full_name }}
                    · {{ $reminders->count() }} lịch nhắc
                </p>
            </div>

            @if ($isOwner)
                <a href="{{ route('reminders.create', $patient) }}"
                   class="rm-list-create">
                    + Tạo lịch nhắc
                </a>
            @endif

        </div>

        <!-- 2. KHUNG DANH SÁCH -->
        <section class="rm-list-panel">

            <!-- TÌM KIẾM VÀ BỘ LỌC -->
            @if ($reminders->isNotEmpty())

                <div class="rm-list-tools">

                    <!-- Tìm kiếm -->
                    <div class="rm-list-search">
                        <input
                            type="search"
                            x-model="search"
                            placeholder="Tìm tên lịch nhắc..."
                            aria-label="Tìm lịch nhắc">
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="rm-list-filters"
                         role="group"
                         aria-label="Lọc trạng thái lịch nhắc">

                        <button
                            type="button"
                            class="rm-list-filter"
                            :class="{ 'is-active': filter === 'all' }"
                            :aria-pressed="filter === 'all'"
                            @click="filter = 'all'">

                            Tất cả ({{ $reminders->count() }})
                        </button>

                        <button
                            type="button"
                            class="rm-list-filter"
                            :class="{ 'is-active': filter === 'active' }"
                            :aria-pressed="filter === 'active'"
                            @click="filter = 'active'">

                            Đang bật ({{ $activeCount }})
                        </button>

                        <button
                            type="button"
                            class="rm-list-filter"
                            :class="{ 'is-active': filter === 'stopped' }"
                            :aria-pressed="filter === 'stopped'"
                            @click="filter = 'stopped'">

                            Đã dừng ({{ $stoppedCount }})
                        </button>

                    </div>

                </div>

                <!-- TIÊU ĐỀ CÁC CỘT -->
                <div class="rm-list-head">
                    <span>Tên lịch nhắc</span>
                    <span>Thời gian áp dụng</span>
                    <span>Giờ uống</span>
                    <span>Trạng thái</span>
                    <span></span>
                </div>

            @endif

            <!-- 3. DỮ LIỆU LỊCH NHẮC -->
            <div class="rm-list-rows">

                @forelse ($reminders as $reminder)

                    @php
                        // Chỉ lấy các giờ chưa bị thay thế
                        $currentTimes = $reminder->doseTimes
                            ->filter(fn ($doseTime) =>
                                $doseTime->status === 'active'
                                && $doseTime->effective_until === null
                            )
                            ->sortBy('time_of_day');
                    @endphp

                    <a
                        href="{{ route('reminders.show', [
                            'patient' => $patient,
                            'reminder' => $reminder
                        ]) }}"
                        class="rm-list-row"
                        data-reminder
                        data-status="{{ $reminder->status }}"
                        data-name="{{ $reminder->name }}"
                        x-show="
                            (filter === 'all' ||
                             filter === $el.dataset.status)
                            &&
                            $el.dataset.name.toLowerCase()
                                .includes(search.trim().toLowerCase())
                        "
                        x-cloak
                        aria-label="Xem chi tiết lịch nhắc {{ $reminder->name }}">

                        <!-- TÊN LỊCH -->
                        <div class="rm-list-name">

                            <strong>
                                {{ $reminder->name }}
                            </strong>

                            <small>
                                Mã lịch
                                #{{ str_pad((string) $reminder->id, 4, '0', STR_PAD_LEFT) }}
                            </small>

                        </div>

                        <!-- THỜI GIAN ÁP DỤNG -->
                        <div class="rm-list-date">

                            {{ $reminder->start_date?->format('d/m/Y') ?? '—' }}

                            –

                            {{ $reminder->end_date?->format('d/m/Y') ?? '—' }}

                        </div>

                        <!-- CÁC GIỜ UỐNG THUỐC -->
                        <div class="rm-list-times">

                            @forelse ($currentTimes->take(3) as $doseTime)

                                <span class="rm-list-time">
                                    {{ substr($doseTime->time_of_day, 0, 5) }}
                                </span>

                            @empty

                                <span class="rm-muted">
                                    Chưa có giờ uống
                                </span>

                            @endforelse

                            @if ($currentTimes->count() > 3)

                                <span class="rm-list-time">
                                    +{{ $currentTimes->count() - 3 }}
                                </span>

                            @endif

                        </div>

                        <!-- TRẠNG THÁI -->
                        <div>

                            @if ($reminder->status === 'active')

                                <span class="rm-list-state is-active">
                                    Đang bật
                                </span>

                            @elseif ($reminder->status === 'stopped')

                                <span class="rm-list-state is-stopped">
                                    Đã dừng
                                </span>

                            @else

                                <span class="rm-muted">
                                    {{ $reminder->status }}
                                </span>

                            @endif

                        </div>

                        <!-- XEM CHI TIẾT -->
                        <div class="rm-list-action">
                            Xem →
                        </div>

                    </a>

                @empty

                    <!-- CHƯA CÓ LỊCH NHẮC -->
                    <div class="rm-list-empty">

                        @if ($isOwner)

                            Chưa có lịch nhắc nào.
                            Hãy tạo lịch nhắc đầu tiên cho người bệnh.

                        @else

                            Người chăm sóc chính chưa thiết lập
                            lịch nhắc cho hồ sơ này.

                        @endif

                    </div>

                @endforelse

                <!-- KHÔNG CÓ KẾT QUẢ TÌM KIẾM -->
                @if ($reminders->isNotEmpty())

                    <div
                        class="rm-list-empty"
                        x-show="
                            !Array.from(
                                $el.parentElement
                                    .querySelectorAll('[data-reminder]')
                            ).some(row =>
                                (filter === 'all' ||
                                 row.dataset.status === filter)
                                &&
                                row.dataset.name.toLowerCase()
                                    .includes(search.trim().toLowerCase())
                            )
                        "
                        x-cloak>

                        Không tìm thấy lịch nhắc phù hợp.
                        Hãy thử thay đổi từ khóa hoặc bộ lọc.

                    </div>

                @endif

            </div>

        </section>

        <!-- 4. QUAY LẠI HỒ SƠ -->
        <a href="{{ route('patients.index') }}"
           class="rm-list-back">
            ← Quay lại hồ sơ người bệnh
        </a>

    </div>

</x-app-layout>
