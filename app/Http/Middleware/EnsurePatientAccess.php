<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $patient = $request->route('patient');

        if (!$patient instanceof Patient) {
            return redirect()->route('patients.index');
        }

        $isOwner = (int) $patient->owner_id === (int) Auth::id();

        $isShared = $patient->caregivers()
            ->where('users.id', Auth::id())
            ->exists();

        if (!$isOwner && !$isShared) {
            return redirect()->route('patients.index');
        }

        return $next($request);
    }
}
