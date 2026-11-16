<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        // Check if user has permission to access system settings or is admin
        if (!$user->hasRole('مدير النظام') && 
            !$user->can('Access-Settings') && 
            !$user->hasPermissionTo('Access-Settings') &&
            !$user->can('Users') && 
            !$user->can('Read-Users')) {
            abort(403, 'عذراً، ليس لديك صلاحية الوصول إلى إعدادات النظام.');
        }

        return view('settings.index');
    }
}
