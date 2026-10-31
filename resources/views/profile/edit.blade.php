<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản lý tài khoản cá nhân') }}
        </h2>
    </x-slot>

    <style>
        .profile-wrapper {
            max-width: 760px;
            margin: 0 auto;
        }

        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            padding: 24px 28px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit-icon {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #2563eb;
            border: none;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-edit-icon:hover {
            background: #2563eb;
            color: #ffffff;
        }

        /* Khung hiển thị văn bản tĩnh xem thông tin */
        .info-display-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            padding-top: 20px;
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Form ẩn mặc định */
        .form-editable-box {
            display: none;
            padding-top: 20px;
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="py-10">
        <div class="profile-wrapper">

            <!-- 1. THẺ THÔNG TIN CÁ NHÂN -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Thông tin cá nhân</span>
                    </div>

                    <!-- ICON CÂY BÚT CHỈNH SỬA -->
                    <button type="button" class="btn-edit-icon" onclick="toggleEditSection('profile-info')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        <span id="profile-info-btn-text">Chỉnh sửa</span>
                    </button>
                </div>

                <!-- Dạng XEM TĨNH (Hiện mặc định) -->
                <div id="profile-info-view" class="info-display-grid">
                    <div>
                        <div class="info-label">Họ và tên</div>
                        <div class="info-value">{{ Auth::user()->name }}</div>
                    </div>
                    <div>
                        <div class="info-label">Địa chỉ Email</div>
                        <div class="info-value">{{ Auth::user()->email }}</div>
                    </div>
                    <div>
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">{{ Auth::user()->phone ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>

                <!-- Form SỬA (Ẩn mặc định, chỉ hiện khi nhấn icon bút) -->
                <div id="profile-info-form" class="form-editable-box">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- 2. THẺ ĐỔI MẬT KHẨU -->
            <div class="profile-card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <span>Mật khẩu</span>
                    </div>

                    <button type="button" class="btn-edit-icon" onclick="toggleEditSection('password-sec')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        <span id="password-sec-btn-text">Đổi mật khẩu</span>
                    </button>
                </div>

                <div id="password-sec-view" class="info-display-grid">
                    <div>
                        <div class="info-label">Mật khẩu hiện tại</div>
                        <div class="info-value">••••••••••••</div>
                    </div>
                </div>

                <div id="password-sec-form" class="form-editable-box">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 3. XÓA TÀI KHOẢN (Ẩn gọn bên dưới) -->
            <div class="profile-card" style="border-color: #fee2e2;">
                <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
                    <div class="card-title" style="color: #dc2626; font-size: 15px;">
                        <span>Xóa tài khoản cá nhân</span>
                    </div>
                    <button type="button" class="btn-edit-icon" style="background: #fef2f2; color: #dc2626;" onclick="toggleEditSection('delete-sec')">
                        <span id="delete-sec-btn-text">Quản lý</span>
                    </button>
                </div>

                <div id="delete-sec-form" class="form-editable-box">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    <!-- Script bật/tắt hiển thị form -->
    <script>
        function toggleEditSection(sectionId) {
            const viewEl = document.getElementById(sectionId + '-view');
            const formEl = document.getElementById(sectionId + '-form');
            const btnText = document.getElementById(sectionId + '-btn-text');

            if (formEl.style.display === 'block') {
                formEl.style.display = 'none';
                if(viewEl) viewEl.style.display = 'grid';
                if(btnText) btnText.innerText = (sectionId === 'password-sec') ? 'Đổi mật khẩu' : 'Chỉnh sửa';
            } else {
                formEl.style.display = 'block';
                if(viewEl) viewEl.style.display = 'none';
                if(btnText) btnText.innerText = 'Đóng lại';
            }
        }
    </script>
</x-app-layout>