<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['permissions', 'department'])->orderBy('name')->get();
        return view('settings.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::orderBy('order')->orderBy('name')->get();
        $modulesStructure = $this->getModulesStructure();

        return view('settings.users.create', compact('departments', 'modulesStructure'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
            'job_title' => $request->job_title,
            'phone_number' => $request->phone_number,
        ]);

        if (!empty($request->permissions)) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('settings.users.index')->with('success', 'تمت إضافة المستخدم الجديد بنجاح');
    }

    public function edit(User $user)
    {
        $departments = Department::orderBy('order')->orderBy('name')->get();
        $modulesStructure = $this->getModulesStructure();

        return view('settings.users.edit', compact('user', 'departments', 'modulesStructure'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6',
            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:50',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->job_title = $request->job_title;
        $user->phone_number = $request->phone_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('settings.users.index')->with('success', 'تم تحديث بيانات المستخدم وصلاحياته بنجاح');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('settings.users.index')->with('error', 'لا يمكنك حذف حسابك الحالي المسجل به.');
        }

        $user->delete();

        return redirect()->route('settings.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    private function getModulesStructure(): array
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

        return $modulesStructure;
    }
}
