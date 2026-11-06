<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePatientOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $patient = $request->route('patient');

        if (
            !$patient instanceof Patient ||
            (int) $patient->owner_id !== (int) Auth::id()
        ) {
            return redirect()->route('patients.index');
        }

        return $next($request);
    }
}
