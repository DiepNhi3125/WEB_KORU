
@extends('layouts.admin')

@section('title', 'Chi tiết tài khoản')

@section('content')

    <!-- Tiêu đề -->
    <div class="ka-page-heading">
        <div>
            <span class="ka-eyebrow">QUẢN LÝ TÀI KHOẢN</span>
            <h1>Chi tiết tài khoản</h1>
            <p>Xem thông tin và quản lý trạng thái tài khoản người chăm sóc.</p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="ka-btn-reset">
            ← Quay lại danh sách
        </a>
    </div>

    <!-- Thông báo -->
    @foreach (['success', 'warning', 'error'] as $type)
        @if (session()->has($type))
            <div class="ka-alert ka-alert-{{ $type }}"
                 role="{{ $type === 'error' ? 'alert' : 'status' }}">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <!-- Thông tin tài khoản -->
    <section class="ka-panel">
        <div class="ka-detail-header">

            <div class="ka-detail-user">
                <span class="ka-detail-avatar">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                </span>

                <div>
                    <h2>{{ $user->name }}</h2>
                    <p>Mã tài khoản #{{ $user->id }}</p>
                </div>
            </div>

            <span class="ka-status ka-status-{{ $user->account_status }}">
                {{ [
                    'active' => 'Đang hoạt động',
                    'pending' => 'Chờ xác thực',
                    'locked' => 'Bị khóa'
                ][$user->account_status] ?? 'Không xác định' }}
            </span>
        </div>

        <div class="ka-detail-body">
            <h3>Thông tin tài khoản</h3>

            <div class="ka-detail-grid">

                <div class="ka-detail-field">
                    <span>Họ và tên</span>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="ka-detail-field">
                    <span>Email</span>
                    <strong>{{ $user->email }}</strong>
                </div>

                <div class="ka-detail-field">
                    <span>Số điện thoại</span>
                    <strong>{{ $user->phone ?? 'Chưa cập nhật' }}</strong>
                </div>

                <div class="ka-detail-field">
                    <span>Vai trò</span>
                    <strong>Người chăm sóc</strong>
                </div>

                <div class="ka-detail-field">
                    <span>Xác thực email</span>
                    <strong>
                        {{ $user->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}
                    </strong>
                </div>

                <div class="ka-detail-field">
                    <span>Ngày đăng ký</span>
                    <strong>
                        {{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}
                    </strong>
                </div>

            </div>
        </div>

        <!-- Thao tác quản trị -->
        <div class="ka-detail-actions">

            @if ($user->account_status === 'locked')

                <p>Tài khoản đang bị khóa và không được phép sử dụng hệ thống.</p>

                <form method="POST"
                      action="{{ route('admin.users.unlock', $user) }}"
                      onsubmit="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="ka-btn-unlock">
                        Mở khóa tài khoản
                    </button>
                </form>

            @else

                <p>Khóa tài khoản sẽ ngăn người dùng truy cập hệ thống KORU.</p>

                <form method="POST"
                      action="{{ route('admin.users.lock', $user) }}"
                      onsubmit="return confirm('Bạn có chắc muốn khóa tài khoản này?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="ka-btn-lock">
                        Khóa tài khoản
                    </button>
                </form>

            @endif

        </div>
    </section>

@endsection
