<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('permissions')->orderBy('name')->get();
        return view('settings.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $allPermissions = Permission::orderBy('name')->get();
        
        $modulesStructure = [
            'الأرشيف الإلكتروني' => [],
            'التنظيم والأبنية' => []
        ];

        foreach ($allPermissions as $perm) {
            $name = strtolower($perm->name);
            
            if (str_contains($name, 'archive')) {
                // Archive Module
                $subgroup = 'إدارة الأرشيف';
                $modulesStructure['الأرشيف الإلكتروني'][$subgroup][] = $perm;
            } else {
                // Organization and Buildings Module
                if (str_contains($name, 'building')) $subgroup = 'المباني (Buildings)';
                elseif (str_contains($name, 'owner') || str_contains($name, 'client')) $subgroup = 'المالكين والعملاء (Owners/Clients)';
                elseif (str_contains($name, 'craft')) $subgroup = 'الحرف (Crafts)';
                elseif (str_contains($name, 'opinion')) $subgroup = 'الآراء (Opinions)';
                elseif (str_contains($name, 'role') || str_contains($name, 'user')) $subgroup = 'المستخدمين والصلاحيات (Users/Roles)';
                else $subgroup = 'صلاحيات أخرى (Other)';

                $modulesStructure['التنظيم والأبنية'][$subgroup][] = $perm;
            }
        }

        return view('settings.users.edit', compact('user', 'modulesStructure'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'permissions' => 'nullable|array'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('settings.users.index')->with('success', 'تم تحديث بيانات المستخدم وصلاحياته بنجاح');
    }
}
