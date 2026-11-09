
<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">

    @php
        $unreadCount = auth()->user()->unreadNotifications()->count();
    @endphp

    <div class="nt-page">
        <div class="nt-container">

            <!-- Đường dẫn -->
            <nav class="nt-breadcrumb" aria-label="Đường dẫn">
                <a href="{{ route('dashboard') }}">Bảng điều khiển</a>
                <span>/</span>
                <span>Thông báo</span>
            </nav>

            <!-- Tiêu đề -->
            <div class="nt-heading">
                <div class="nt-heading-content">
                    <h1>Thông báo</h1>
                    <p>Theo dõi các ca uống thuốc của người thân</p>
                </div>

                <div class="nt-overview">
                    <div class="nt-overview-number">
                        {{ $notifications->total() }}
                    </div>
                    <span>Tổng thông báo</span>
                </div>
            </div>

            <!-- Khung danh sách -->
            <section class="nt-card">

                <div class="nt-card-header">
                    <div class="nt-card-title">
                        <h2>Hoạt động gần đây</h2>
                        <span class="nt-counter">
                            {{ $unreadCount }} chưa đọc
                        </span>
                    </div>

                    @if ($unreadCount > 0)
                        <form method="POST"
                              action="{{ route('notifications.read-all') }}">
                            @csrf
                            <button type="submit" class="nt-read-all">
                                Đánh dấu tất cả đã đọc
                            </button>
                        </form>
                    @endif
                </div>

                <div class="nt-list">
                    @forelse ($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isMissed = ($data['event'] ?? '') === 'missed';
                            $isUnread = $notification->read_at === null;
                        @endphp

                        <form method="POST"
                              class="nt-form"
                              action="{{ route('notifications.read', $notification->id) }}">
                            @csrf

                            <button type="submit"
                                    class="nt-item {{ $isUnread ? 'nt-item--unread' : '' }}">

                                <!-- Icon trạng thái -->
                                <span class="nt-icon {{ $isMissed ? 'nt-icon--missed' : 'nt-icon--done' }}">
                                    @if ($isMissed)
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 7v6M12 17h.01"/>
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.8"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="m8 12 2.5 2.5L16 9"/>
                                        </svg>
                                    @endif
                                </span>

                                <!-- Nội dung -->
                                <span class="nt-item-body">
                                    <span class="nt-item-top">
                                        <span class="nt-item-title">
                                            {{ $isMissed
                                                ? 'Bỏ lỡ ca uống thuốc'
                                                : 'Đã xác nhận uống thuốc' }}
                                        </span>

                                        <span class="nt-item-date">
                                            {{ $notification->created_at
                                                ->timezone('Asia/Ho_Chi_Minh')
                                                ->format('H:i · d/m/Y') }}
                                        </span>
                                    </span>

                                    <span class="nt-item-message">
                                        {{ $data['message'] ?? 'Thông báo từ KORU.' }}
                                    </span>

                                    <span class="nt-item-bottom">
                                        <span class="nt-tag {{ $isMissed ? 'nt-tag--missed' : 'nt-tag--done' }}">
                                            {{ $isMissed ? 'Bỏ lỡ' : 'Đã uống' }}
                                        </span>

                                        @if (!empty($data['patient_name']))
                                            <span class="nt-patient-name">
                                                {{ $data['patient_name'] }}
                                            </span>
                                        @endif
                                    </span>
                                </span>

                                @if ($isUnread)
                                    <span class="nt-unread-dot"
                                          title="Chưa đọc"></span>
                                @endif
                            </button>
                        </form>
                    @empty
                        <div class="nt-empty">
                            <span class="nt-empty-icon">
                                <svg viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="1.6"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                                    <path d="M10 21h4"/>
                                </svg>
                            </span>
                            <h3>Chưa có thông báo</h3>
                            <p>Các cập nhật về lịch uống thuốc sẽ xuất hiện tại đây.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Phân trang -->
            @if ($notifications->hasPages())
                <nav class="nt-pagination" aria-label="Phân trang">
                    @if ($notifications->onFirstPage())
                        <span class="nt-page-disabled">Trước</span>
                    @else
                        <a href="{{ $notifications->previousPageUrl() }}">
                            Trước
                        </a>
                    @endif

                    <span>
                        Trang {{ $notifications->currentPage() }}
                        / {{ $notifications->lastPage() }}
                    </span>

                    @if ($notifications->hasMorePages())
                        <a href="{{ $notifications->nextPageUrl() }}">
                            Tiếp
                        </a>
                    @else
                        <span class="nt-page-disabled">Tiếp</span>
                    @endif
                </nav>
            @endif

        </div>
    </div>
</x-app-layout>
