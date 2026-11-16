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
        $permissionLabels = self::getPermissionLabels();

        return view('settings.users.create', compact('departments', 'modulesStructure', 'permissionLabels'));
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
        $permissionLabels = self::getPermissionLabels();

        return view('settings.users.edit', compact('user', 'departments', 'modulesStructure', 'permissionLabels'));
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
            'إعدادات النظام' => [
                'الوصول العام لإعدادات النظام (System Settings Access)' => [],
                'إدارة المستخدمين والصلاحيات (Users & Roles)' => [],
            ],
            'الأرشيف الإلكتروني' => [
                'إدارة ملفات ووثائق الأرشيف (Archive Management)' => [],
            ],
            'التنظيم والأبنية' => [
                'المباني والعقارات (Buildings)' => [],
                'الطوابق والوحدات السكنية (Floors & Units)' => [],
                'رخص البناء والتراخيص (Building Licenses)' => [],
                'الآراء والموافقات الفنية (Opinions & Reviews)' => [],
                'المناطق والشوارع والأحياء (Zones & Streets)' => [],
                'إثبات الحالة والكشف التنظيمي (Proof of Case & Inspection)' => [],
            ],
            'المشتركين والعملاء' => [
                'الملاك والعملاء والمشتركون (Owners & Clients)' => [],
            ],
            'الحرف والصناعات' => [
                'المهن والحرف والصناعات (Crafts)' => [],
            ],
            'قلم الجمهور والمعاملات' => [
                'معاملات وطلبات الجمهور (Transactions)' => [],
            ],
            'المراسلات الداخلية' => [
                'نظام المراسلات والبريد الداخلي (Internal Correspondence)' => [],
            ],
            'الموارد البشرية وشؤون الموظفين' => [
                'سجل الموظفين والرواتب والهيكلية (HR & Payroll)' => [],
            ],
        ];

        foreach ($allPermissions as $perm) {
            $name = strtolower($perm->name);
            
            // 1. General System Settings & Backup
            if (str_contains($name, 'setting')) {
                $subgroup = 'الوصول العام لإعدادات النظام (System Settings Access)';
                $modulesStructure['إعدادات النظام'][$subgroup][] = $perm;
            } 
            // 2. Users & Roles Management
            elseif (str_contains($name, 'role') || str_contains($name, 'user')) {
                $subgroup = 'إدارة المستخدمين والصلاحيات (Users & Roles)';
                $modulesStructure['إعدادات النظام'][$subgroup][] = $perm;
            } 
            // 3. Archive Module
            elseif (str_contains($name, 'archive')) {
                $subgroup = 'إدارة ملفات ووثائق الأرشيف (Archive Management)';
                $modulesStructure['الأرشيف الإلكتروني'][$subgroup][] = $perm;
            } 
            // 4. HR Module
            elseif (str_contains($name, 'hr') || str_contains($name, 'employee') || str_contains($name, 'payroll')) {
                $subgroup = 'سجل الموظفين والرواتب والهيكلية (HR & Payroll)';
                $modulesStructure['الموارد البشرية وشؤون الموظفين'][$subgroup][] = $perm;
            }
            // 5. Transactions Module
            elseif (str_contains($name, 'transaction')) {
                $subgroup = 'معاملات وطلبات الجمهور (Transactions)';
                $modulesStructure['قلم الجمهور والمعاملات'][$subgroup][] = $perm;
            }
            // 6. Correspondence Module
            elseif (str_contains($name, 'correspondence') || str_contains($name, 'memo')) {
                $subgroup = 'نظام المراسلات والبريد الداخلي (Internal Correspondence)';
                $modulesStructure['المراسلات الداخلية'][$subgroup][] = $perm;
            }
            // 7. Crafts Module
            elseif (str_contains($name, 'craft')) {
                $subgroup = 'المهن والحرف والصناعات (Crafts)';
                $modulesStructure['الحرف والصناعات'][$subgroup][] = $perm;
            }
            // 8. Customers & Subscriptions
            elseif (str_contains($name, 'owner') || str_contains($name, 'client') || str_contains($name, 'citizen') || str_contains($name, 'subscri') || str_contains($name, 'transferproperty') || str_contains($name, 'customer')) {
                $subgroup = 'الملاك والعملاء والمشتركون (Owners & Clients)';
                $modulesStructure['المشتركين والعملاء'][$subgroup][] = $perm;
            }
            // 9. Buildings & Organization Module
            else {
                if (str_contains($name, 'building')) {
                    $subgroup = 'المباني والعقارات (Buildings)';
                } elseif (str_contains($name, 'floor') || str_contains($name, 'unit')) {
                    $subgroup = 'الطوابق والوحدات السكنية (Floors & Units)';
                } elseif (str_contains($name, 'license')) {
                    $subgroup = 'رخص البناء والتراخيص (Building Licenses)';
                } elseif (str_contains($name, 'opinion') || str_contains($name, 'confirmation') || str_contains($name, 'note') || str_contains($name, 'financial') || str_contains($name, 'organizational')) {
                    $subgroup = 'الآراء والموافقات الفنية (Opinions & Reviews)';
                } elseif (str_contains($name, 'zone') || str_contains($name, 'street')) {
                    $subgroup = 'المناطق والشوارع والأحياء (Zones & Streets)';
                } elseif (str_contains($name, 'proofofcase') || str_contains($name, 'disclosure')) {
                    $subgroup = 'إثبات الحالة والكشف التنظيمي (Proof of Case & Inspection)';
                } else {
                    $subgroup = 'المباني والعقارات (Buildings)';
                }

                $modulesStructure['التنظيم والأبنية'][$subgroup][] = $perm;
            }
        }

        // Clean up any empty subgroups if needed
        foreach ($modulesStructure as $mainModule => $subgroups) {
            foreach ($subgroups as $subgroupName => $perms) {
                if (empty($perms)) {
                    unset($modulesStructure[$mainModule][$subgroupName]);
                }
            }
            if (empty($modulesStructure[$mainModule])) {
                unset($modulesStructure[$mainModule]);
            }
        }

        return $modulesStructure;
    }

    public static function getPermissionLabels(): array
    {
        return [
            // أذونات الموديولات العامة
            'Module-Archive' => 'الوصول الشامل لموديول الأرشيف الإلكتروني',
            'Module-Buildings' => 'الوصول الشامل لموديول التنظيم والأبنية',
            'Module-Crafts' => 'الوصول الشامل لموديول الحرف والصناعات',
            'Module-Customers' => 'الوصول الشامل لموديول المشتركين والعملاء',
            'Module-Transactions' => 'الوصول الشامل لموديول قلم الجمهور والمعاملات',
            'Module-Correspondence' => 'الوصول الشامل لموديول المراسلات الداخلية',
            'Module-HR' => 'الوصول الشامل لموديول شؤون الموظفين والموارد البشرية',
            'Module-Settings' => 'الوصول الشامل لموديول إعدادات النظام',

            // إعدادات النظام والمستخدمين
            'Access-Settings' => 'الوصول العام لإعدادات النظام (إظهار ودخول صفحة الإعدادات)',
            'Settings-Backup' => 'إدارة وتصدير النسخ الاحتياطي لقاعدة البيانات',
            'Users' => 'الوصول الكامل لموديول المستخدمين',
            'Read-Users' => 'عرض قائمة مستخدمي النظام',
            'Create-User' => 'إضافة مستخدم جديد للنظام',
            'Update-User' => 'تعديل بيانات وصلاحيات المستخدمين',
            'Delete-User' => 'حذف حسابات المستخدمين',
            'Roles' => 'الوصول الكامل لموديول الأدوار الوظيفية',
            'Read-Roles' => 'عرض قائمة الأدوار الوظيفية',
            'Create-Role' => 'إضافة دور وظيفي جديد',
            'Update-Role' => 'تعديل الأدوار الوظيفية والصلاحيات',
            'Delete-Role' => 'حذف الأدوار الوظيفية',

            // الأرشيف الإلكتروني
            'archive.files.create' => 'إنشاء وتأسيس ملفات الأرشيف المركزي',
            'archive.files.edit' => 'تعديل بيانات ملفات الأرشيف المركزي',
            'archive.files.delete' => 'حذف ملفات من الأرشيف المركزي',
            'archive.documents.create' => 'أرشفة وإدراج وثائق ومستندات جديدة',
            'archive.documents.delete' => 'حذف وثائق ومستندات الأرشيف',

            // المباني وأنواعها
            'Buildings' => 'الوصول الكامل لموديول المباني والعقارات',
            'Read-Buildings' => 'عرض سجل المباني والعقارات',
            'Show-Building' => 'عرض تفاصيل ملف المبنى / العقار',
            'Create-Building' => 'إضافة عقار / مبنى جديد',
            'Update-Building' => 'تعديل بيانات العقار / المبنى',
            'Delete-Building' => 'حذف عقار / مبنى',
            'BuildingTypes' => 'الوصول لموديول أنواع المباني',
            'Read-BuildingTypes' => 'عرض قائمة أنواع المباني',
            'Create-BuildingType' => 'إضافة نوع مبنى جديد',
            'Update-BuildingType' => 'تعديل نوع المبنى',
            'Delete-BuildingType' => 'حذف نوع مبنى',

            // الطوابق والوحدات
            'Floors' => 'الوصول الكامل لبيانات الطوابق',
            'Read-Floors' => 'عرض طوابق المباني',
            'Create-Floor' => 'إضافة طابق جديد للمبنى',
            'Update-Floor' => 'تعديل بيانات الطابق',
            'Delete-Floor' => 'حذف طابق من المبنى',
            'Create-Floor-Data' => 'إدخال البيانات والمواصفات الفنية للطابق',
            'Edit-Floor-Data' => 'تعديل المواصفات والبيانات الفنية للطابق',
            'Edit-Floor-Financial' => 'تعديل البيانات والرسوم المالية للطابق',
            'Units' => 'الوصول لموديول الوحدات العقارية',
            'Read-Units' => 'عرض قائمة الوحدات العقارية (الشقق والمحلات)',
            'Create-Unit' => 'إضافة وحدة عقارية جديدة',
            'Update-Unit' => 'تعديل بيانات الوحدة العقارية',
            'Delete-Unit' => 'حذف وحدة عقارية',

            // رخص البناء ونماذج الترخيص
            'LicenseForms' => 'الوصول الكامل لنماذج ومعاملات رخص البناء',
            'Read-LicenseForms' => 'عرض سجل طلبات رخص البناء',
            'Show-LicenseForm' => 'استعراض تفاصيل طلب رخصة البناء',
            'Create-LicenseForm' => 'إنشاء وتقديم طلب رخصة بناء جديدة',
            'Update-LicenseForm' => 'تعديل وتحديث طلب رخصة البناء',
            'Delete-LicenseForm' => 'حذف طلب رخصة بناء',
            'LicensePrint' => 'طباعة رخصة البناء الرسمية المعتمدة',

            // المالكين والمواطنين والاشتراكات
            'Owners' => 'الوصول لموديول المالكين',
            'Read-Owners' => 'عرض سجل المالكين',
            'Create-Owner' => 'إضافة مالك جديد للعقار',
            'Update-Owner' => 'تعديل بيانات المالك',
            'Delete-Owner' => 'حذف مالك من السجل',
            'Clients' => 'الوصول لموديول المواطنين والعملاء',
            'Read-Clients' => 'عرض سجل المواطنين والعملاء',
            'Create-Client' => 'إضافة مواطن / عميل جديد',
            'Update-Client' => 'تعديل بيانات المواطنين والعملاء',
            'Delete-Client' => 'حذف مواطن / عميل',
            'Update-Citizen' => 'تعديل وتحديث بيانات المواطنين',
            'Create-Citizen' => 'إضافة مواطن جديد',
            'Read-Citizens' => 'عرض واستعراض سجل المواطنين',
            'Delete-Citizen' => 'حذف سجل مواطن',
            'Subscriptions' => 'الوصول لموديول الاشتراكات والخدمات',
            'Read-Subscriptions' => 'عرض سجل اشتراكات الخدمات',
            'Create-Subscription' => 'إضافة اشتراك خدمة جديد للمواطن',
            'Update-Subscription' => 'تعديل بيانات الاشتراك والخدمة',
            'Delete-Subscription' => 'حذف أو إلغاء اشتراك خدمة',
            'TransferProperty' => 'إجراءات نقل وتحويل الملكية العقارية',

            // الحرف والمهن
            'Crafts' => 'الوصول لموديول المهن والحرف والصناعات',
            'Read-Crafts' => 'عرض سجل رخص المهن والحرف',
            'Create-Craft' => 'تسجيل وإصدار رخصة مهنة / حرفة جديدة',
            'Update-Craft' => 'تعديل وتجديد رخصة الحرفة',
            'Delete-Craft' => 'حذف / إلغاء رخصة حرفة',

            // الآراء والموافقات والاعتمادات
            'Opinions' => 'الوصول لموديول الآراء والتوصيات الإدارية',
            'Show-Opinions' => 'استعراض آراء ومطالعات الأقسام الفنية',
            'Read-Organizational' => 'عرض المطالعة والرأي التنظيمي',
            'Read-Financial' => 'عرض المطالعة والموقف المالي',
            'DepartmentNotes' => 'إضافة وتعديل ملاحظات وتوجيهات الأقسام',
            'FinalConfirmation' => 'الاعتماد والمصادقة النهائية للمعاملة',
            'UrbanPlanning-Opinion' => 'رأي وتوصية قسم التخطيط الحضري والتنظيم',
            'Gis-Opinion' => 'رأي ومطالعة قسم نظم المعلومات الجغرافية (GIS)',
            'SurveyDepartment-Opinion' => 'رأي ومطالعة قسم المساحة الميدانية',
            'WaterDepartment-Opinion' => 'رأي وموافقة قسم شبكات المياه',
            'SewerDepartment-Opinion' => 'رأي وموافقة قسم الصرف الصحي',
            'CollectionDepartment-Opinion' => 'رأي وموقف قسم الجباية والتحصيل',
            'Legal-Opinions' => 'المطالعة والرأي القانوني',

            // المناطق والشوارع والأحياء
            'Zones' => 'الوصول لموديول المناطق التنظيمية',
            'Read-Zones' => 'عرض سجل المناطق التنظيمية',
            'Create-Zone' => 'إضافة منطقة تنظيمية جديدة',
            'Update-Zone' => 'تعديل المنطقة التنظيمية',
            'Delete-Zone' => 'حذف منطقة تنظيمية',
            'Sub-Zones' => 'الوصول لموديول الأحياء والمناطق الفرعية',
            'Read-Sub-Zones' => 'عرض سجل الأحياء والمناطق الفرعية',
            'Create-Sub-Zone' => 'إضافة حي / منطقة فرعية جديدة',
            'Update-Sub-Zone' => 'تعديل الحي / المنطقة الفرعية',
            'Delete-Sub-Zone' => 'حذف حي / منطقة فرعية',
            'Streets' => 'الوصول لموديول الشوارع التنظيمية',
            'Read-Streets' => 'عرض سجل الشوارع التنظيمية',
            'Create-Street' => 'إضافة شارع تنظيمي جديد',
            'Update-Street' => 'تعديل بيانات الشارع التنظيمي',
            'Delete-Street' => 'حذف شارع من المخطط',

            // إثبات الحالة والكشف التنظيمي
            'ProofOfCase' => 'الوصول لموديول إثبات الحالة',
            'Read-ProofOfCase' => 'عرض سجل وطلبات إثبات الحالة',
            'Create-ProofOfCase' => 'إنشاء محضر / طلب إثبات حالة جديد',
            'Update-ProofOfCase' => 'تعديل بيانات محضر إثبات الحالة',
            'Delete-ProofOfCase' => 'حذف محضر إثبات الحالة',
            'Update-RegulatoryDisclosureReport' => 'إعداد وتعديل تقرير الكشف التنظيمي',
        ];
    }
}
