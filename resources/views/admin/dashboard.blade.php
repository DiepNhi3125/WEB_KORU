
@extends('layouts.admin')

@section('title', 'Bảng điều khiển')

@section('content')
    <!-- Tiêu đề -->
    <div class="ka-page-heading">
        <div>
            <span class="ka-eyebrow">TỔNG QUAN HỆ THỐNG</span>
            <h1>Bảng điều khiển</h1>
            <p>Theo dõi và quản lý các tài khoản người chăm sóc trên KORU.</p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="ka-btn-primary">
            Quản lý tài khoản →
        </a>
    </div>

    <!-- Thống kê tài khoản -->
    <section class="ka-stats" aria-label="Thống kê tài khoản">
        <div class="ka-stat-card">
            <div class="ka-stat-icon ka-blue">◉</div>
            <span>Tổng tài khoản</span>
            <strong>{{ number_format($statistics['total']) }}</strong>
            <small>Tài khoản người chăm sóc</small>
        </div>

        <div class="ka-stat-card">
            <div class="ka-stat-icon ka-green">✓</div>
            <span>Đang hoạt động</span>
            <strong>{{ number_format($statistics['active']) }}</strong>
            <small>Trạng thái active</small>
        </div>

        <div class="ka-stat-card">
            <div class="ka-stat-icon ka-yellow">◷</div>
            <span>Chờ kích hoạt</span>
            <strong>{{ number_format($statistics['pending']) }}</strong>
            <small>Trạng thái pending</small>
        </div>

        <div class="ka-stat-card">
            <div class="ka-stat-icon ka-red">⊘</div>
            <span>Bị khóa</span>
            <strong>{{ number_format($statistics['locked']) }}</strong>
            <small>Trạng thái locked</small>
        </div>
    </section>

    <!-- Tài khoản mới đăng ký -->
    <section class="ka-panel">
        <div class="ka-panel-header">
            <div>
                <h2>Tài khoản mới đăng ký</h2>
                <p>5 tài khoản người chăm sóc được tạo gần nhất.</p>
            </div>

            <a href="{{ route('admin.users.index') }}" class="ka-view-all">
                Xem tất cả →
            </a>
        </div>

        <div class="ka-table-wrap">
            <table class="ka-table">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($recentUsers as $user)
                        <tr>
                            <td class="ka-user-name">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="ka-status ka-status-{{ $user->account_status }}">
                                    {{ [
                                        'active' => 'Hoạt động',
                                        'pending' => 'Chờ kích hoạt',
                                        'locked' => 'Bị khóa'
                                    ][$user->account_status] ?? 'Không xác định' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="ka-empty">
                                Chưa có tài khoản người chăm sóc nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
