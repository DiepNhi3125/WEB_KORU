
<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="kd-page" x-data="{ picker: null }" @keydown.escape.window="picker = null">
        <div class="kd-content">
            <!-- Tiêu đề -->
            <div class="kd-heading">
                <h1>Bảng điều khiển</h1>
                <p>Chọn chức năng bạn muốn sử dụng</p>
            </div>

            <!-- Ba ô chức năng -->
            <div class="kd-grid">
                <!-- Quản lý hồ sơ người bệnh -->
                <a href="{{ route('patients.index') }}" class="kd-card kd-card--patients">
                    <span class="kd-icon">
                        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="16" r="7" />
                            <path d="M5 37v-3c0-7 5.8-12 13-12s13 5 13 12v3H5Z" />
                            <path d="M32 10a6 6 0 0 1 0 12M36 26c4 2 7 5 7 10v1h-7" />
                        </svg>
                    </span>
                    <span class="kd-card-title">Quản lý hồ sơ người bệnh</span>
                    <span class="kd-arrow" aria-hidden="true">→</span>
                </a>

                <!-- Quản lý lịch nhắc -->
                <button type="button" class="kd-card kd-card--reminders"
                        @click="picker = 'reminders'" aria-haspopup="dialog">
                    <span class="kd-icon">
                        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="8" y="10" width="32" height="32" rx="9" />
                            <path d="M16 5v10M32 5v10M8 20h32" />
                            <path d="M24 26v8m-4-4h8" />
                        </svg>
                    </span>
                    <span class="kd-card-title">Quản lý lịch nhắc thuốc</span>
                    <span class="kd-arrow" aria-hidden="true">→</span>
                </button>

                <!-- Thống kê -->
                <button type="button" class="kd-card kd-card--statistics"
                        @click="picker = 'statistics'" aria-haspopup="dialog">
                    <span class="kd-icon">
                        <svg viewBox="0 0 48 48" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 39V26h8v13M20 39V17h8v22M32 39V9h8v30" />
                            <path d="M5 39h38" />
                        </svg>
                    </span>
                    <span class="kd-card-title">Thống kê</span>
                    <span class="kd-arrow" aria-hidden="true">→</span>
                </button>
            </div>

            <p class="kd-footer">
                KORU · Đồng hành cùng người thân
            </p>
        </div>

        <!-- Hộp chọn người bệnh -->
        <div class="kd-overlay" x-show="picker !== null" x-cloak
             @click.self="picker = null" x-transition.opacity>
            <div class="kd-dialog" role="dialog" aria-modal="true"
                 aria-labelledby="kd-dialog-title">
                <div class="kd-dialog-header">
                    <div>
                        <h2 id="kd-dialog-title">Chọn người bệnh</h2>
                        <p x-text="picker === 'reminders'
                            ? 'Chọn hồ sơ để quản lý lịch nhắc thuốc'
                            : 'Chọn hồ sơ để xem thống kê'"></p>
                    </div>
                    <button type="button" class="kd-close"
                            @click="picker = null" aria-label="Đóng">×</button>
                </div>

                @if ($patients->isNotEmpty())
                    <div class="kd-patient-list">
                        @foreach ($patients as $patient)
                            <a href="{{ route('reminders.index', $patient) }}"
                               data-reminders-url="{{ route('reminders.index', $patient) }}"
                               data-statistics-url="{{ route('patients.statistics', $patient) }}"
                               :href="picker === 'reminders'
                                   ? $el.dataset.remindersUrl
                                   : $el.dataset.statisticsUrl"
                               class="kd-patient">
                                <span class="kd-patient-avatar">
                                    {{ mb_strtoupper(mb_substr($patient->full_name, 0, 1)) }}
                                </span>
                                <span class="kd-patient-name">{{ $patient->full_name }}</span>
                                <span class="kd-patient-arrow">→</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="kd-empty">
                        <p>Bạn chưa có hồ sơ người bệnh nào.</p>
                        <a href="{{ route('patients.index') }}" class="kd-empty-link">
                            Quản lý hồ sơ người bệnh
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
