<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Bảng điều khiển Admin
    public function dashboard(): View
    {
        $caregivers = User::query()
            ->where('role', 'caregiver');

        $statistics = [
            'total' => (clone $caregivers)->count(),

            'active' => (clone $caregivers)
                ->where('account_status', 'active')
                ->count(),

            'pending' => (clone $caregivers)
                ->where('account_status', 'pending')
                ->count(),

            'locked' => (clone $caregivers)
                ->where('account_status', 'locked')
                ->count(),
        ];

        // 5 tài khoản đăng ký gần nhất
        $recentUsers = (clone $caregivers)
            ->select(
                'id',
                'name',
                'email',
                'account_status',
                'created_at'
            )
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'statistics',
            'recentUsers'
        ));
    }

    // Danh sách và tìm kiếm tài khoản
    public function users(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:pending,active,locked'],
        ]);

        $search = trim($filters['q'] ?? '');
        $status = $filters['status'] ?? '';

        $query = User::query()
            ->where('role', 'caregiver')
            ->select(
                'id',
                'name',
                'email',
                'phone',
                'account_status',
                'created_at'
            );

        // Tìm theo họ tên hoặc email
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($status !== '') {
            $query->where('account_status', $status);
        }

        // Sắp xếp mới nhất và phân trang
        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'search',
            'status'
        ));
    }


    // Xem chi tiết tài khoản người chăm sóc
    public function showUser(User $user): View
    {
        abort_unless($user->role === 'caregiver', 404);

        return view('admin.users.show', compact('user'));
    }

    // Admin khóa tài khoản người chăm sóc
    public function lockUser(User $user): RedirectResponse
    {
        abort_unless($user->role === 'caregiver', 404);

        if ($user->account_status === 'locked') {
            return back()->with(
                'warning',
                'Tài khoản này đã bị khóa.'
            );
        }

        // Khóa tài khoản và vô hiệu hóa Remember Me
        $user->account_status = 'locked';
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Thu hồi các phiên đăng nhập đang lưu trong database
        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->delete();
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Đã khóa tài khoản thành công.');
    }

    // Admin mở khóa tài khoản người chăm sóc
    public function unlockUser(User $user): RedirectResponse
    {
        abort_unless($user->role === 'caregiver', 404);

        if ($user->account_status !== 'locked') {
            return back()->with(
                'warning',
                'Tài khoản này không ở trạng thái bị khóa.'
            );
        }

        // Đã xác thực email thì active, chưa xác thực thì pending
        $user->account_status = $user->hasVerifiedEmail()
            ? 'active'
            : 'pending';

        $user->save();

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Đã mở khóa tài khoản thành công.');
    }

}
