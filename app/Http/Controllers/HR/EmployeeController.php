<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\Department;
use App\Models\HR\Employee;
use App\Models\HR\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'grade', 'directManager']);

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Filter by contract type
        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default show all or active
        }

        // Filter by grade
        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        $employees = $query->latest('id')->paginate(15)->withQueryString();

        $departments = Department::orderBy('name')->get();
        $grades = Grade::where('is_active', true)->orderBy('order')->get();

        return view('hr.employees.index', compact('employees', 'departments', 'grades'));
    }

    public function create()
    {
        $departments = Department::with('parent')->orderBy('name')->get();
        $grades = Grade::where('is_active', true)->orderBy('order')->get();
        $managers = Employee::where('status', 'active')->orderBy('full_name')->get();
        $users = User::orderBy('name')->get();

        // Generate suggested employee number
        $lastId = Employee::withTrashed()->max('id') ?? 0;
        $suggestedEmpNumber = 'EMP-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('hr.employees.create', compact('departments', 'grades', 'managers', 'users', 'suggestedEmpNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:30|unique:hr_employees,employee_number',
            'national_id' => 'required|string|size:9|regex:/^[0-9]{9}$/|unique:hr_employees,national_id',
            'first_name' => 'required|string|max:50',
            'second_name' => 'nullable|string|max:50',
            'third_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'dependents_count' => 'nullable|integer|min:0|max:30',
            'has_spouse_allowance' => 'nullable|boolean',

            'mobile' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',

            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'required|string|max:150',
            'contract_type' => 'required|in:permanent,temporary_contract,daily,unemployment,delegation',
            'hire_date' => 'nullable|date',
            'end_of_contract_date' => 'nullable|date',
            'status' => 'required|in:active,suspended,terminated',
            'direct_manager_id' => 'nullable|exists:hr_employees,id',
            'user_id' => 'nullable|exists:users,id',

            'grade_id' => 'nullable|exists:hr_grades,id',
            'current_step' => 'nullable|integer|min:0|max:30',
            'custom_base_salary' => 'nullable|numeric|min:0',

            'nature_of_work_rate' => 'nullable|numeric|min:0|max:100',
            'qualification' => 'nullable|string|max:50',
            'qualification_allowance' => 'nullable|numeric|min:0',
            'position_allowance' => 'nullable|numeric|min:0',
            'cost_of_living_allowance' => 'nullable|numeric|min:0',
            'risk_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',

            'is_pension_subscribed' => 'nullable|boolean',
            'pension_rate' => 'nullable|numeric|min:0|max:100',
            'health_insurance_fixed' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',

            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ], [
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.size' => 'رقم الهوية يجب أن يتكون من 9 أرقام',
            'national_id.regex' => 'رقم الهوية يجب أن يحتوي على أرقام فقط',
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً لموظف آخر',
            'employee_number.required' => 'الرقم الوظيفي مطلوب',
            'employee_number.unique' => 'الرقم الوظيفي مسجل مسبقاً',
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'اسم العائلة مطلوب',
            'job_title.required' => 'المسمى الوظيفي مطلوب',
        ]);

        $fullName = trim(implode(' ', array_filter([
            $validated['first_name'],
            $validated['second_name'] ?? null,
            $validated['third_name'] ?? null,
            $validated['last_name'],
        ])));

        $validated['full_name'] = $fullName;
        $validated['has_spouse_allowance'] = $request->boolean('has_spouse_allowance');
        $validated['is_pension_subscribed'] = $request->boolean('is_pension_subscribed');
        $validated['dependents_count'] = $validated['dependents_count'] ?? 0;
        $validated['current_step'] = $validated['current_step'] ?? 0;

        $employee = Employee::create($validated);

        return redirect()->route('hr.employees.show', $employee->id)
            ->with('success', "تم إضافة الموظف ({$employee->full_name}) بنجاح.");
    }

    public function show($id)
    {
        $employee = Employee::with([
            'department',
            'grade',
            'directManager',
            'subordinates',
            'user',
            'payrollItems.payroll' => function ($q) {
                $q->latest('year')->latest('month');
            }
        ])->findOrFail($id);

        $salaryPreview = $employee->calculateSalaryComponents();

        return view('hr.employees.show', compact('employee', 'salaryPreview'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::with('parent')->orderBy('name')->get();
        $grades = Grade::where('is_active', true)->orderBy('order')->get();
        $managers = Employee::where('status', 'active')->where('id', '!=', $id)->orderBy('full_name')->get();
        $users = User::orderBy('name')->get();

        return view('hr.employees.edit', compact('employee', 'departments', 'grades', 'managers', 'users'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'employee_number' => ['required', 'string', 'max:30', Rule::unique('hr_employees', 'employee_number')->ignore($id)],
            'national_id' => ['required', 'string', 'size:9', 'regex:/^[0-9]{9}$/', Rule::unique('hr_employees', 'national_id')->ignore($id)],
            'first_name' => 'required|string|max:50',
            'second_name' => 'nullable|string|max:50',
            'third_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'dependents_count' => 'nullable|integer|min:0|max:30',
            'has_spouse_allowance' => 'nullable|boolean',

            'mobile' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',

            'department_id' => 'nullable|exists:departments,id',
            'job_title' => 'required|string|max:150',
            'contract_type' => 'required|in:permanent,temporary_contract,daily,unemployment,delegation',
            'hire_date' => 'nullable|date',
            'end_of_contract_date' => 'nullable|date',
            'status' => 'required|in:active,suspended,terminated',
            'direct_manager_id' => ['nullable', 'exists:hr_employees,id', Rule::notIn([$id])],
            'user_id' => 'nullable|exists:users,id',

            'grade_id' => 'nullable|exists:hr_grades,id',
            'current_step' => 'nullable|integer|min:0|max:30',
            'custom_base_salary' => 'nullable|numeric|min:0',

            'nature_of_work_rate' => 'nullable|numeric|min:0|max:100',
            'qualification' => 'nullable|string|max:50',
            'qualification_allowance' => 'nullable|numeric|min:0',
            'position_allowance' => 'nullable|numeric|min:0',
            'cost_of_living_allowance' => 'nullable|numeric|min:0',
            'risk_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',

            'is_pension_subscribed' => 'nullable|boolean',
            'pension_rate' => 'nullable|numeric|min:0|max:100',
            'health_insurance_fixed' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',

            'bank_name' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ], [
            'national_id.required' => 'رقم الهوية مطلوب',
            'national_id.size' => 'رقم الهوية يجب أن يتكون من 9 أرقام',
            'national_id.regex' => 'رقم الهوية يجب أن يحتوي على أرقام فقط',
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً لموظف آخر',
            'employee_number.required' => 'الرقم الوظيفي مطلوب',
            'employee_number.unique' => 'الرقم الوظيفي مسجل مسبقاً',
            'first_name.required' => 'الاسم الأول مطلوب',
            'last_name.required' => 'اسم العائلة مطلوب',
            'job_title.required' => 'المسمى الوظيفي مطلوب',
        ]);

        $fullName = trim(implode(' ', array_filter([
            $validated['first_name'],
            $validated['second_name'] ?? null,
            $validated['third_name'] ?? null,
            $validated['last_name'],
        ])));

        $validated['full_name'] = $fullName;
        $validated['has_spouse_allowance'] = $request->boolean('has_spouse_allowance');
        $validated['is_pension_subscribed'] = $request->boolean('is_pension_subscribed');
        $validated['dependents_count'] = $validated['dependents_count'] ?? 0;
        $validated['current_step'] = $validated['current_step'] ?? 0;

        $employee->update($validated);

        return redirect()->route('hr.employees.show', $employee->id)
            ->with('success', "تم تحديث بيانات الموظف ({$employee->full_name}) بنجاح.");
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $name = $employee->full_name;
        $employee->delete();

        return redirect()->route('hr.employees.index')
            ->with('success', "تم أرشفة وحذف سجل الموظف ({$name}) بنجاح.");
    }

    /**
     * AJAX endpoint to calculate live salary preview
     */
    public function previewSalary(Request $request)
    {
        $gradeId = $request->input('grade_id');
        $step = (int) $request->input('current_step', 0);
        $customBase = $request->input('custom_base_salary');
        $contractType = $request->input('contract_type', 'permanent');

        $baseSalary = 0.0;
        if (!empty($customBase) && (float)$customBase > 0) {
            $baseSalary = (float)$customBase;
        } elseif ($gradeId) {
            $grade = Grade::find($gradeId);
            if ($grade) {
                $baseSalary = $grade->calculateSalaryForStep($step);
            }
        }

        $natureRate = (float) $request->input('nature_of_work_rate', 0);
        $natureOfWork = round($baseSalary * ($natureRate / 100), 2);

        $hasSpouse = filter_var($request->input('has_spouse_allowance'), FILTER_VALIDATE_BOOLEAN);
        $spouseAllowance = $hasSpouse ? 60.00 : 0.00;

        $dependents = (int) $request->input('dependents_count', 0);
        $childrenAllowance = round($dependents * 20.00, 2);

        $qualAllowance = (float) $request->input('qualification_allowance', 0);
        $posAllowance = (float) $request->input('position_allowance', 0);
        $colAllowance = (float) $request->input('cost_of_living_allowance', 0);
        $riskAllowance = (float) $request->input('risk_allowance', 0);
        $otherAllowance = (float) $request->input('other_allowances', 0);

        $totalAllowances = round(
            $natureOfWork +
            $spouseAllowance +
            $childrenAllowance +
            $qualAllowance +
            $posAllowance +
            $colAllowance +
            $riskAllowance +
            $otherAllowance,
            2
        );

        $gross = round($baseSalary + $totalAllowances, 2);

        $isPension = filter_var($request->input('is_pension_subscribed'), FILTER_VALIDATE_BOOLEAN);
        $pensionRate = (float) $request->input('pension_rate', 10);
        $pensionDeduction = 0.0;
        if ($isPension && $contractType === 'permanent') {
            $pensionDeduction = round(($baseSalary + $natureOfWork) * ($pensionRate / 100), 2);
        }

        $healthDeduction = (float) $request->input('health_insurance_fixed', 0);
        $loanDeduction = (float) $request->input('loan_deduction', 0);
        $otherDeduction = (float) $request->input('other_deductions', 0);

        $totalDeductions = round(
            $pensionDeduction +
            $healthDeduction +
            $loanDeduction +
            $otherDeduction,
            2
        );

        $netSalary = max(0.00, round($gross - $totalDeductions, 2));

        return response()->json([
            'base_salary' => $baseSalary,
            'nature_of_work_allowance' => $natureOfWork,
            'spouse_allowance' => $spouseAllowance,
            'children_allowance' => $childrenAllowance,
            'qualification_allowance' => $qualAllowance,
            'position_allowance' => $posAllowance,
            'cost_of_living_allowance' => $colAllowance,
            'risk_allowance' => $riskAllowance,
            'other_allowances' => $otherAllowance,
            'total_allowances' => $totalAllowances,
            'gross_salary' => $gross,
            'pension_deduction' => $pensionDeduction,
            'health_insurance_deduction' => $healthDeduction,
            'loan_deduction' => $loanDeduction,
            'other_deductions' => $otherDeduction,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
        ]);
    }
}

