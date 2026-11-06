<x-app-layout>
    <x-slot name="header">
        <h2 class="patients-header">{{ __('Hồ sơ người bệnh') }}</h2>
    </x-slot>

    <!-- Nhúng file CSS chung -->
    <link rel="stylesheet" href="{{ asset('css/patients.css') }}">

    <div class="patients-page">
        <div class="patients-toolbar">
            <div>
                <h1 class="patients-title">{{ __('Quản lý người bệnh') }}</h1>
                <p class="patients-subtitle">
                    {{ __('Quản lý các hồ sơ bạn đang chăm sóc.') }}
                </p>
            </div>

            <div class="patients-toolbar-actions">
                <a href="{{ route('patients.join') }}" class="patient-btn patient-btn-cancel">
                    {{ __('Nhập mã chia sẻ') }}
                </a>

                <a href="{{ route('patients.create') }}" class="patients-add-btn">
                    + {{ __('Thêm người bệnh') }}
                </a>
            </div>

        </div>



        <!-- Hồ sơ do người dùng sở hữu -->
        <section class="patients-section">
            <h2 class="patients-section-title">
                {{ __('Hồ sơ người bệnh') }} ({{ count($ownedPatients) }})
            </h2>
            <div class="patients-list">
                @if (count($ownedPatients) > 0)
                    @foreach ($ownedPatients as $patient)
                        <div class="patients-card">
                            <div class="patients-card-heading">
                                @if ($patient->gender == 'male')
                                    <span class="patients-gender patients-gender-male" title="{{ __('Nam') }}"
                                        aria-label="{{ __('Nam') }}">♂</span>
                                @elseif ($patient->gender == 'female')
                                    <span class="patients-gender patients-gender-female" title="{{ __('Nữ') }}"
                                        aria-label="{{ __('Nữ') }}">♀</span>
                                @else
                                    <span class="patients-gender patients-gender-other"
                                        title="{{ __('Giới tính khác hoặc chưa cập nhật') }}">◎</span>
                                @endif

                                <h3 class="patients-card-name">
                                    {{ $patient->full_name }}
                                </h3>
                            </div>

                            <button type="button" class="patients-detail-link" data-patient-modal data-id="{{ $patient->id }}"
                                data-name="{{ $patient->full_name }}"
                                data-birth="{{ !empty($patient->birth_date) ? date('d/m/Y', strtotime($patient->birth_date)) : __('Chưa cập nhật') }}"
                                data-gender="{{ $patient->gender }}"
                                data-phone="{{ $patient->phone ? '*******' . substr($patient->phone, -3) : __('Chưa cập nhật') }}"
                                data-owner="{{ Auth::user()->name }}" data-edit-url="{{ route('patients.edit', $patient) }}"
                                data-delete-url="{{ route('patients.destroy', $patient) }}"
                                data-share-url="{{ route('patients.share', $patient) }}"
                                data-pair-url="{{ route('patients.pair', $patient) }}"
                                aria-label="{{ __('Xem chi tiết') }} {{ $patient->full_name }}">
                                {{ __('Xem chi tiết') }}
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="patients-empty">
                        {{ __('Bạn chưa tạo hồ sơ người bệnh nào.') }}
                    </div>
                @endif
            </div>
        </section>

        <!-- Hồ sơ được chia sẻ -->
        <section class="patients-section">
            <h2 class="patients-section-title">
                {{ __('Hồ sơ được chia sẻ') }} ({{ count($sharedPatients) }})
            </h2>
            <div class="patients-list">
                @if (count($sharedPatients) > 0)
                    @foreach ($sharedPatients as $patient)
                        <div class="patients-card">
                            <div class="patients-card-heading">
                                @if ($patient->gender == 'male')
                                    <span class="patients-gender patients-gender-male" title="{{ __('Nam') }}"
                                        aria-label="{{ __('Nam') }}">♂</span>
                                @elseif ($patient->gender == 'female')
                                    <span class="patients-gender patients-gender-female" title="{{ __('Nữ') }}"
                                        aria-label="{{ __('Nữ') }}">♀</span>
                                @else
                                    <span class="patients-gender patients-gender-other"
                                        title="{{ __('Giới tính khác hoặc chưa cập nhật') }}">◎</span>
                                @endif

                                <h3 class="patients-card-name">
                                    {{ $patient->full_name }}
                                </h3>
                            </div>

                            <button type="button" class="patients-detail-link" data-patient-modal data-id="{{ $patient->id }}"
                                data-name="{{ $patient->full_name }}"
                                data-birth="{{ !empty($patient->birth_date) ? date('d/m/Y', strtotime($patient->birth_date)) : __('Chưa cập nhật') }}"
                                data-gender="{{ $patient->gender }}"
                                data-phone="{{ $patient->phone ? '*******' . substr($patient->phone, -3) : 'Chưa cập nhật' }}"
                                data-owner="{{ isset($patient->owner) ? $patient->owner->name : __('Chưa xác định') }}"
                                aria-label="{{ __('Xem chi tiết') }} {{ $patient->full_name }}">
                                {{ __('Xem chi tiết') }}
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="patients-empty">
                        {{ __('Chưa có hồ sơ nào được chia sẻ với bạn.') }}
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Modal HTML5 xem chi tiết -->
    <dialog id="patientDetailModal" class="patient-modal" aria-labelledby="modalPatientName">

        <div class="patient-modal-header">
            <div class="patient-modal-avatar" id="modalAvatar" aria-hidden="true"></div>

            <div class="patient-modal-identity">
                <p class="patient-modal-eyebrow">
                    {{ __('CHI TIẾT HỒ SƠ') }}
                </p>
                <h2 id="modalPatientName"></h2>
                <span id="modalPatientId"></span>
            </div>

            <!-- Các thao tác nhanh ở góc phải -->
            <div class="patient-modal-quick-actions">

                <!-- Chỉnh sửa -->
                <a id="editPatientLink" class="patient-modal-icon" title="Chỉnh sửa hồ sơ" aria-label="Chỉnh sửa hồ sơ"
                    hidden>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20h4l10.5-10.5-4-4L4 16v4Z" />
                        <path d="m13.5 6.5 4 4" />
                    </svg>
                </a>

                <!-- Chia sẻ -->
                <a id="sharePatientLink" class="patient-modal-icon" title="Chia sẻ hồ sơ" aria-label="Chia sẻ hồ sơ"
                    hidden>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="5" r="3" />
                        <circle cx="6" cy="12" r="3" />
                        <circle cx="18" cy="19" r="3" />
                        <path d="m8.7 10.7 6.6-4" />
                        <path d="m8.7 13.3 6.6 4" />
                    </svg>
                </a>

                <!-- Đóng Modal -->
                <button type="button" id="closePatientModal" class="patient-modal-close patient-modal-icon" title="Đóng"
                    aria-label="Đóng">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M18 6 6 18" />
                        <path d="M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>


        <div class="patient-modal-body">
            <h3 class="patient-modal-section">{{ __('Thông tin cá nhân') }}</h3>
            <dl class="patient-modal-grid">
                <div class="patient-modal-field">
                    <dt>{{ __('Ngày sinh') }}</dt>
                    <dd id="modalBirth"></dd>
                </div>
                <div class="patient-modal-field">
                    <dt>{{ __('Giới tính') }}</dt>
                    <dd id="modalGender"></dd>
                </div>
                <div class="patient-modal-field">
                    <dt>{{ __('Số điện thoại') }}</dt>
                    <dd id="modalPhone"></dd>
                </div>
            </dl>
            <div class="patient-modal-caregiver">
                <span>{{ __('Người chăm sóc chính') }}</span>
                <strong id="modalOwner"></strong>
            </div>
        </div>



        <div class="patient-modal-footer">

            <!-- Thao tác nguy hiểm: nằm riêng bên trái -->
            <button type="button" id="deletePatientButton" class="patient-modal-delete" hidden>
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M8 6V4h8v2" />
                    <path d="M5 6l1 15h12l1-15" />
                    <path d="M10 10v7" />
                    <path d="M14 10v7" />
                </svg>
                {{ __('Gỡ hồ sơ') }}
            </button>

            <!-- Thao tác bên phải -->
            <div class="patient-modal-footer-actions">


                <a id="pairPatientLink" class="patient-modal-pair" hidden>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="6" y="2" width="12" height="20" rx="2" />
                        <path d="M11 18h2" />
                    </svg>
                    {{ __('Ghép thiết bị') }}
                </a>

            </div>
        </div>


    </dialog>

    <!-- Modal xác nhận gỡ hồ sơ -->
    <dialog id="patientDeleteModal" class="patient-delete-modal" aria-labelledby="patientDeleteTitle">

        <h2 id="patientDeleteTitle" class="patient-delete-title">
            Xác nhận gỡ hồ sơ
        </h2>

        <p class="patient-delete-description">
            Bạn có chắc muốn gỡ hồ sơ
            <strong id="deletePatientName"></strong>?
        </p>

        <p class="patient-delete-warning">
            Thao tác này sẽ xóa vĩnh viễn hồ sơ, lịch nhắc thuốc,
            lịch sử uống thuốc và các liên kết liên quan.
            Dữ liệu đã xóa không thể khôi phục qua ứng dụng.
        </p>

        <form id="patientDeleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="patient-delete-actions">
                <button type="button" id="cancelDeletePatient" class="patient-delete-cancel">
                    Hủy
                </button>

                <button type="submit" class="patient-delete-confirm">
                    Xóa vĩnh viễn
                </button>
            </div>
        </form>
    </dialog>

    <!-- File Javascript điều khiển Modal -->
    <script src="{{ asset('js/patient-modal.js') }}" defer></script>
</x-app-layout>