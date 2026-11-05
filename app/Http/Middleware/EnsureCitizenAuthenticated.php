<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCitizenAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('portal_citizen_id')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح لك، يرجى تسجيل الدخول.'], 401);
            }
            return redirect()->route('portal.login')
                ->with('error', 'انتهت جلستك أو لم تقم بتسجيل الدخول بعد. يرجى إدخال بياناتك للدخول.');
        }

        return $next($request);
    }
}

