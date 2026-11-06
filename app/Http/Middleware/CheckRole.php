<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {
        if (!Auth::user() || Auth::user()->role !== $role) {
            return redirect()->back(fallback: url('/'));
        }


        return $next($request);
    }
}