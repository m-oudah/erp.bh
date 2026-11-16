<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\Department;
use App\Models\HR\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentHierarchyController extends Controller
{
    public function index()
    {
        // Root nodes: parent_id is null with deep eager loading
        $rootDepartments = Department::whereNull('parent_id')
            ->with([
                'manager',
                'hrEmployees',
                'children' => function ($q) {
                    $q->withCount('hrEmployees')->with([
                        'manager',
                        'children' => function ($sq) {
                            $sq->withCount('hrEmployees')->with([
                                'manager',
                                'children' => function ($ssq) {
                                    $ssq->withCount('hrEmployees')->with('manager');
                                }
                            ]);
                        }
                    ]);
                }
            ])
            ->withCount('hrEmployees')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        // Flat list for dropdowns
        $allDepartments = Department::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('full_name')->get();

        $stats = [
            'total' => Department::count(),
            'directorates' => Department::where('type', 'directorate')->count(),
            'departments' => Department::where('type', 'department')->count(),
            'sections' => Department::whereIn('type', ['section', 'unit'])->count(),
        ];

        return view('hr.departments.index', compact('rootDepartments', 'allDepartments', 'users', 'employees', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:council,directorate,department,section,unit',
            'parent_id' => 'nullable|exists:departments,id',
            'code' => 'nullable|string|max:20|unique:departments,code',
            'manager_id' => 'nullable|exists:users,id',
            'manager_title' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'اسم الوحدة الإدارية مطلوب',
            'type.required' => 'نوع الوحدة الإدارية مطلوب',
            'code.unique' => 'الرمز الإداري مستخدم بالفعل',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        Department::create($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', 'تم إضافة الوحدة الإدارية الجديدة بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|in:council,directorate,department,section,unit',
            'parent_id' => ['nullable', 'exists:departments,id', function ($attribute, $value, $fail) use ($id) {
                if ($value == $id) {
                    $fail('لا يمكن تعيين الوحدة الإدارية كأب لنفسها.');
                }
            }],
            'code' => 'nullable|string|max:20|unique:departments,code,' . $id,
            'manager_id' => 'nullable|exists:users,id',
            'manager_title' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'اسم الوحدة الإدارية مطلوب',
            'type.required' => 'نوع الوحدة الإدارية مطلوب',
            'code.unique' => 'الرمز الإداري مستخدم بالفعل',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        $department->update($validated);

        return redirect()->route('hr.departments.index')
            ->with('success', "تم تحديث بيانات ({$department->name}) بنجاح.");
    }

    public function move(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                'exists:departments,id',
                function ($attribute, $value, $fail) use ($id) {
                    if ($value == $id) {
                        $fail('لا يمكن تعيين الوحدة الإدارية كأب لنفسها.');
                    }
                    if ($value) {
                        $parent = Department::find($value);
                        $current = $parent;
                        while ($current) {
                            if ($current->id == $id) {
                                $fail('لا يمكن نقل الوحدة لتصبح تابعة لأحد فروعها.');
                                break;
                            }
                            $current = $current->parent;
                        }
                    }
                }
            ],
            'order' => 'nullable|integer',
        ]);

        $department->update([
            'parent_id' => $validated['parent_id'],
            'order' => $validated['order'] ?? $department->order,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم نقل ({$department->name}) بنجاح في الهيكلية الإدارية.",
                'department' => $department,
            ]);
        }

        return redirect()->route('hr.departments.index')
            ->with('success', "تم نقل ({$department->name}) بنجاح في الهيكلية الإدارية.");
    }

    public function destroy($id)
    {
        $department = Department::withCount(['children', 'hrEmployees'])->findOrFail($id);

        if ($department->children_count > 0) {
            return redirect()->route('hr.departments.index')
                ->with('error', 'لا يمكن حذف هذه الوحدة لأنها تحتوي على أقسام أو شعب فرعية تابعة لها.');
        }

        if ($department->hr_employees_count > 0) {
            return redirect()->route('hr.departments.index')
                ->with('error', 'لا يمكن حذف هذه الوحدة لأنها مرتبطة بموظفين حاليين. يرجى نقل الموظفين أولاً.');
        }

        $department->delete();

        return redirect()->route('hr.departments.index')
            ->with('success', 'تم حذف الوحدة الإدارية بنجاح.');
    }
}

