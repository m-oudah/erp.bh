<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->canAccessModule($module)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'ليس لديك صلاحية للوصول للنظام أو استخدام هذا الموديول.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'ليس لديك صلاحية للوصول للنظام أو استخدام هذا الموديول.');
        }

        return $next($request);
    }
}
