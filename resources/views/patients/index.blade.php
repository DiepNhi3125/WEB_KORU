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
            <a href="{{ route('patients.create') }}" class="patients-add-btn">
                + {{ __('Thêm người bệnh') }}
            </a>
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
                <p class="patient-modal-eyebrow">{{ __('CHI TIẾT HỒ SƠ') }}</p>
                <h2 id="modalPatientName"></h2>
                <span id="modalPatientId"></span>
            </div>
            <button type="button" class="patient-modal-close" id="closePatientModal"
                aria-label="{{ __('Đóng') }}">&times;</button>
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
            <button type="button" id="deletePatientButton" class="patient-modal-delete" hidden>
                Gỡ hồ sơ
            </button>

            <a id="editPatientLink" class="patient-btn patient-btn-save" hidden>
                {{ __('Chỉnh sửa') }}
            </a>

            <button type="button" class="patient-modal-done" id="donePatientModal">
                {{ __('Đóng') }}
            </button>
        </div>

    </dialog>
    
<!-- Modal xác nhận gỡ hồ sơ -->
<dialog id="patientDeleteModal"
        class="patient-delete-modal"
        aria-labelledby="patientDeleteTitle">

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
            <button type="button"
                    id="cancelDeletePatient"
                    class="patient-delete-cancel">
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