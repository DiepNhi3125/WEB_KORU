
@extends('layouts.admin')

@section('title', 'Quản lý tài khoản')

@section('content')
    <!-- Tiêu đề -->
    <div class="ka-page-heading">
        <div>
            <span class="ka-eyebrow">QUẢN TRỊ TÀI KHOẢN</span>
            <h1>Quản lý tài khoản</h1>
            <p>Danh sách tài khoản người chăm sóc đã đăng ký trên KORU.</p>
        </div>
    </div>

    <section class="ka-panel">
        <!-- Tìm kiếm và lọc -->
        <form method="GET"
              action="{{ route('admin.users.index') }}"
              class="ka-filters"
              role="search">

            <input type="search"
                   name="q"
                   value="{{ $search }}"
                   maxlength="100"
                   aria-label="Tìm kiếm tài khoản"
                   placeholder="Tìm theo họ tên hoặc email...">

            <select name="status" aria-label="Lọc trạng thái">
                <option value="">Tất cả trạng thái</option>
                <option value="active" @selected($status === 'active')>
                    Đang hoạt động
                </option>
                <option value="pending" @selected($status === 'pending')>
                    Chờ kích hoạt
                </option>
                <option value="locked" @selected($status === 'locked')>
                    Bị khóa
                </option>
            </select>

            <button type="submit" class="ka-btn-primary">
                Tìm kiếm
            </button>

            <a href="{{ route('admin.users.index') }}" class="ka-btn-reset">
                Xóa lọc
            </a>
        </form>

        <!-- Bảng tài khoản -->
        <div class="ka-table-wrap">
            <table class="ka-table">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Trạng thái</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="ka-user-name">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '—' }}</td>
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
                            <td colspan="5" class="ka-empty">
                                Không tìm thấy tài khoản phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div class="ka-table-footer">
            <span>
                Tổng cộng {{ $users->total() }} tài khoản
            </span>

            @if ($users->hasPages())
                <div class="ka-pagination">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
