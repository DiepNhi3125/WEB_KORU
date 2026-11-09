<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountNotLocked
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        // Nếu tài khoản bị khóa thì hủy phiên đăng nhập
        if ($user && $user->account_status === 'locked') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Tài khoản đã bị khóa. Vui lòng liên hệ quản trị viên.',
                ]);
        }

        return $next($request);
    }
}
