<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ArchiveUserPermissionsController extends Controller
{
    // Human-readable labels for the archive permissions
    const PERMISSION_LABELS = [
        'archive.files.create'     => 'إضافة ملف',
        'archive.files.edit'       => 'تعديل ملف',
        'archive.files.delete'     => 'حذف ملف',
        'archive.documents.create' => 'إضافة وثائق / مستندات',
        'archive.documents.delete' => 'حذف وثائق / مستندات',
    ];

    public function index()
    {
        $users = User::orderBy('name')->get();
        $permissions = array_keys(self::PERMISSION_LABELS);
        $permissionLabels = self::PERMISSION_LABELS;

        // Pre-build a map: userId => [permission_name => bool]
        $userPermissionsMap = [];
        foreach ($users as $user) {
            foreach ($permissions as $perm) {
                $userPermissionsMap[$user->id][$perm] = $user->hasPermissionTo($perm);
            }
        }

        return view('settings.archive.user_permissions', compact('users', 'permissions', 'permissionLabels', 'userPermissionsMap'));
    }

    public function update(Request $request, User $user)
    {
        $validPermissions = array_keys(self::PERMISSION_LABELS);
        $granted = $request->input('permissions', []);

        // Sync only the archive-related permissions for this user
        $toSync = [];
        foreach ($validPermissions as $perm) {
            if (in_array($perm, $granted)) {
                $toSync[] = $perm;
            }
        }

        // Remove all archive permissions from user, then re-add granted ones
        $user->revokePermissionTo($validPermissions);
        if (count($toSync) > 0) {
            $user->givePermissionTo($toSync);
        }

        // Log the activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['user_id' => $user->id, 'permissions' => $toSync])
            ->log('تعديل صلاحيات الأرشيف للمستخدم: ' . $user->name);

        return back()->with('success', 'تم تحديث صلاحيات ' . $user->name . ' بنجاح.');
    }
}
