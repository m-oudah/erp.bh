<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\HR\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $grades = Grade::withCount('employees')
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        return view('hr.grades.index', compact('grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'grade_code' => 'required|string|max:20|unique:hr_grades,grade_code',
            'grade_name' => 'required|string|max:100',
            'base_salary' => 'required|numeric|min:0',
            'annual_raise' => 'required|numeric|min:0',
            'max_steps' => 'required|integer|min:1|max:40',
            'notes' => 'nullable|string',
            'order' => 'nullable|integer',
        ], [
            'category.required' => 'الفئة مطلوبة',
            'grade_code.required' => 'رمز الدرجة مطلوب',
            'grade_code.unique' => 'رمز الدرجة مستخدم مسبقاً',
            'grade_name.required' => 'اسم الدرجة مطلوب',
            'base_salary.required' => 'بداية مربوط الراتب الأساسي مطلوب',
            'annual_raise.required' => 'قيمة العلاوة الدورية (المربوط) مطلوبة',
        ]);

        $validated['is_active'] = true;
        $validated['order'] = $validated['order'] ?? 0;

        Grade::create($validated);

        return redirect()->route('hr.grades.index')
            ->with('success', 'تم إضافة الدرجة الوظيفية بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|string|max:50',
            'grade_code' => 'required|string|max:20|unique:hr_grades,grade_code,' . $id,
            'grade_name' => 'required|string|max:100',
            'base_salary' => 'required|numeric|min:0',
            'annual_raise' => 'required|numeric|min:0',
            'max_steps' => 'required|integer|min:1|max:40',
            'notes' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'category.required' => 'الفئة مطلوبة',
            'grade_code.required' => 'رمز الدرجة مطلوب',
            'grade_code.unique' => 'رمز الدرجة مستخدم مسبقاً',
            'grade_name.required' => 'اسم الدرجة مطلوب',
            'base_salary.required' => 'بداية مربوط الراتب الأساسي مطلوب',
            'annual_raise.required' => 'قيمة العلاوة الدورية (المربوط) مطلوبة',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $grade->update($validated);

        return redirect()->route('hr.grades.index')
            ->with('success', "تم تحديث بيانات الدرجة ({$grade->grade_name}) بنجاح.");
    }

    public function destroy($id)
    {
        $grade = Grade::withCount('employees')->findOrFail($id);

        if ($grade->employees_count > 0) {
            return redirect()->route('hr.grades.index')
                ->with('error', "لا يمكن حذف هذه الدرجة لوجود ({$grade->employees_count}) موظف مسجل عليها.");
        }

        $grade->delete();

        return redirect()->route('hr.grades.index')
            ->with('success', 'تم حذف الدرجة بنجاح.');
    }
}

