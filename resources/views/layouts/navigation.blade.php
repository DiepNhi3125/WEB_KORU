
<nav class="koru-nav" aria-label="Thanh điều hướng chính">
    <div class="koru-nav-inner">
        <a href="{{ route('dashboard') }}"
           class="koru-nav-brand"
           aria-label="KORU - Về bảng điều khiển">
            <x-application-logo class="koru-nav-logo" />
        </a>

        <div class="koru-nav-account">
            <!-- Chuông thông báo -->
            <div class="kn-wrap"
                 x-data="koruBell"
                 data-feed-url="{{ route('notifications.feed') }}"
                 data-read-base="{{ url('/notifications') }}"
                 data-read-all-url="{{ route('notifications.read-all') }}"
                 @click.outside="open = false"
                 @keydown.escape.window="open = false">

                <button type="button"
                        class="kn-button"
                        aria-label="Thông báo"
                        aria-haspopup="dialog"
                        :aria-expanded="open.toString()"
                        @click="toggle()">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                        <path d="M10 21h4"/>
                    </svg>

                    <span class="kn-badge"
                          x-cloak
                          x-show="unread > 0"
                          x-text="unread > 99 ? '99+' : unread">
                    </span>
                </button>

                <!-- Danh sách thông báo -->
                <div class="kn-panel"
                     x-cloak
                     x-show="open"
                     x-transition.opacity.duration.150ms>

                    <div class="kn-header">
                        <div>
                            <h3>Thông báo</h3>
                            <p>
                                <span x-text="unread"></span>
                                thông báo chưa đọc
                            </p>
                        </div>

                        <button type="button"
                                class="kn-mark-all"
                                x-show="unread > 0"
                                :disabled="busy"
                                @click="markAll()">
                            Đã đọc tất cả
                        </button>
                    </div>

                    <div class="kn-list">
                        <div class="kn-empty"
                             x-show="loading && items.length === 0">
                            Đang tải thông báo...
                        </div>

                        <div class="kn-error" x-show="error">
                            Không thể cập nhật thông báo.
                            <button type="button" @click="load()">
                                Thử lại
                            </button>
                        </div>

                        <div class="kn-empty"
                             x-show="!loading && !error && items.length === 0">
                            Bạn chưa có thông báo nào.
                        </div>

                        <template x-for="item in items" :key="item.id">
                            <button type="button"
                                    class="kn-item"
                                    :class="{ 'is-unread': !item.read }"
                                    :disabled="busy"
                                    @click="openItem(item)">

                                <span class="kn-item-icon"
                                      :class="item.event === 'missed'
                                          ? 'kn-item-icon--missed'
                                          : 'kn-item-icon--done'"
                                      x-text="item.event === 'missed' ? '!' : '✓'">
                                </span>

                                <span class="kn-item-content">
                                    <span class="kn-item-title"
                                          x-text="item.event === 'missed'
                                              ? 'Bỏ lỡ ca uống thuốc'
                                              : 'Đã xác nhận uống thuốc'">
                                    </span>

                                    <span class="kn-item-message"
                                          x-text="item.message">
                                    </span>

                                    <span class="kn-item-time"
                                          x-text="item.time">
                                    </span>
                                </span>

                                <span class="kn-unread-dot"
                                      x-show="!item.read">
                                </span>
                            </button>
                        </template>
                    </div>

                    <div class="kn-footer">
                        <a href="{{ route('notifications.index') }}">
                            Xem tất cả thông báo
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Menu tài khoản hiện tại -->
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button type="button"
                            class="koru-nav-user"
                            :aria-expanded="open.toString()"
                            aria-haspopup="true">

                        <span class="koru-nav-avatar">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </span>

                        <span class="koru-nav-username">
                            {{ auth()->user()->name }}
                        </span>

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             class="koru-nav-chevron"
                             aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        Hồ sơ tài khoản
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="koru-nav-logout">
                            Đăng xuất
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</nav>
