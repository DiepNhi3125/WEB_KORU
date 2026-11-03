<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Xác thực email và kích hoạt tài khoản.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Xác thực email bằng chức năng có sẵn của Laravel
        if (! $user->hasVerifiedEmail()) {
            $request->fulfill();
        }

        // Chỉ kích hoạt tài khoản đang chờ xác thực
        if ($user->hasVerifiedEmail() && $user->account_status === 'pending') {
            $user->account_status = 'active';
            $user->save();
        }

        return redirect()->intended(
            route('dashboard', absolute: false).'?verified=1'
        );
    }
}
