<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\Department;
use App\Models\HR\Employee;
use App\Models\HR\Payroll;
use App\Models\HR\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with(['creator', 'approver'])->withCount('items');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderByDesc('year')->orderByDesc('month')->paginate(12)->withQueryString();

        $years = Payroll::select('year')->distinct()->orderByDesc('year')->pluck('year');
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('hr.payroll.index', compact('payrolls', 'years'));
    }

    public function create()
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');

        $activeEmployeesCount = Employee::where('status', 'active')->count();

        return view('hr.payroll.create', compact('currentYear', 'currentMonth', 'activeEmployeesCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2050',
            'month' => 'required|integer|min:1|max:12',
            'title' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
        ], [
            'year.required' => 'يرجى تحديد السنة',
            'month.required' => 'يرجى تحديد الشهر',
        ]);

        $year = (int) $validated['year'];
        $month = (int) $validated['month'];

        // Check if a payroll already exists for this month and year
        $existing = Payroll::where('year', $year)->where('month', $month)->first();
        if ($existing) {
            return back()->withInput()->with('error', "يوجد مسير رواتب مسجل مسبقاً لشهر ({$month}/{$year}) بالرقم: {$existing->payroll_number}");
        }

        // Fetch active employees
        $activeEmployees = Employee::with(['department', 'grade'])
            ->where('status', 'active')
            ->orderBy('department_id')
            ->orderBy('full_name')
            ->get();

        if ($activeEmployees->isEmpty()) {
            return back()->withInput()->with('error', 'لا يوجد موظفون نشطون على رأس عملهم لتوليد مسير الرواتب.');
        }

        $monthsNames = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
        ];

        $title = $validated['title'] ?: "مسير رواتب شهر {$monthsNames[$month]} {$year}";
        $payrollNumber = sprintf('PR-%04d-%02d', $year, $month);

        DB::beginTransaction();
        try {
            $payroll = Payroll::create([
                'payroll_number' => $payrollNumber,
                'year' => $year,
                'month' => $month,
                'title' => $title,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($activeEmployees as $emp) {
                $components = $emp->calculateSalaryComponents();

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'employee_id' => $emp->id,

                    // Snapshot
                    'employee_number' => $emp->employee_number,
                    'employee_name' => $emp->full_name,
                    'job_title' => $emp->job_title,
                    'department_name' => $emp->department?->name ?? 'غير محدد',
                    'contract_type' => $emp->contract_type_arabic,
                    'grade_name' => $emp->grade?->grade_name ?? 'مقطوع / بدون درجة',
                    'step' => $emp->current_step ?? 0,
                    'bank_name' => $emp->bank_name,
                    'bank_account_number' => $emp->bank_account_number,
                    'iban' => $emp->iban,

                    // Earnings
                    'base_salary' => $components['base_salary'],
                    'nature_of_work_allowance' => $components['nature_of_work_allowance'],
                    'spouse_allowance' => $components['spouse_allowance'],
                    'children_allowance' => $components['children_allowance'],
                    'qualification_allowance' => $components['qualification_allowance'],
                    'position_allowance' => $components['position_allowance'],
                    'cost_of_living_allowance' => $components['cost_of_living_allowance'],
                    'risk_allowance' => $components['risk_allowance'],
                    'overtime_allowance' => $components['overtime_allowance'],
                    'bonus_allowance' => $components['bonus_allowance'],
                    'other_allowances' => $components['other_allowances'],
                    'total_allowances' => $components['total_allowances'],
                    'gross_salary' => $components['gross_salary'],

                    // Deductions
                    'pension_deduction' => $components['pension_deduction'],
                    'health_insurance_deduction' => $components['health_insurance_deduction'],
                    'loan_deduction' => $components['loan_deduction'],
                    'other_deductions' => $components['other_deductions'],
                    'total_deductions' => $components['total_deductions'],

                    // Net
                    'net_salary' => $components['net_salary'],
                    'notes' => null,
                    'is_manually_adjusted' => false,
                ]);
            }

            $payroll->recalculateTotals();

            DB::commit();

            return redirect()->route('hr.payroll.show', $payroll->id)
                ->with('success', "تم توليد مسير رواتب شهر ({$month}/{$year}) بنجاح لعدد ({$activeEmployees->count()}) موظف.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء توليد مسير الرواتب: ' . $e->getMessage());
        }
    }

    public function show($id, Request $request)
    {
        $payroll = Payroll::with(['creator', 'approver'])->findOrFail($id);

        $query = $payroll->items()->with('employee');

        // Search & Filters
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department_name', $request->department);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        $items = $query->orderBy('department_name')->orderBy('employee_name')->get();

        // Get unique departments and contract types for filter options
        $departmentsList = $payroll->items()->select('department_name')->distinct()->pluck('department_name');
        $contractTypesList = $payroll->items()->select('contract_type')->distinct()->pluck('contract_type');

        return view('hr.payroll.show', compact('payroll', 'items', 'departmentsList', 'contractTypesList'));
    }

    public function updateItem(Request $request, $payrollId, $itemId)
    {
        $payroll = Payroll::findOrFail($payrollId);

        if ($payroll->status !== 'draft') {
            return back()->with('error', 'لا يمكن تعديل بنود المسير بعد اعتماده أو صرفه.');
        }

        $item = PayrollItem::where('payroll_id', $payroll->id)->findOrFail($itemId);

        $validated = $request->validate([
            'overtime_allowance' => 'nullable|numeric|min:0',
            'bonus_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);

        $item->overtime_allowance = round((float) ($validated['overtime_allowance'] ?? 0), 2);
        $item->bonus_allowance = round((float) ($validated['bonus_allowance'] ?? 0), 2);
        $item->other_allowances = round((float) ($validated['other_allowances'] ?? 0), 2);
        $item->loan_deduction = round((float) ($validated['loan_deduction'] ?? 0), 2);
        $item->other_deductions = round((float) ($validated['other_deductions'] ?? 0), 2);
        $item->notes = $validated['notes'] ?? null;
        $item->is_manually_adjusted = true;

        $item->recalculateItemTotals();
        $item->save();

        $payroll->recalculateTotals();

        return redirect()->route('hr.payroll.show', $payroll->id)
            ->with('success', "تم تحديث راتب الموظف ({$item->employee_name}) بنجاح.");
    }

    public function approve($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status !== 'draft') {
            return back()->with('error', 'المسير معتمد بالفعل مسبقاً.');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('hr.payroll.show', $payroll->id)
            ->with('success', "تم اعتماد مسير رواتب ({$payroll->title}) رسمياً.");
    }

    public function markAsPaid($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status !== 'approved') {
            return back()->with('error', 'يجب اعتماد المسير قبل تحديد حالة الصرف.');
        }

        $payroll->update([
            'status' => 'paid',
        ]);

        return redirect()->route('hr.payroll.show', $payroll->id)
            ->with('success', "تم تأكيد صرف وإغلاق مسير رواتب ({$payroll->title}).");
    }

    public function slip($payrollId, $itemId)
    {
        $payroll = Payroll::findOrFail($payrollId);
        $item = PayrollItem::with('employee')->where('payroll_id', $payroll->id)->findOrFail($itemId);

        return view('hr.payroll.slip', compact('payroll', 'item'));
    }

    public function printFull($id)
    {
        $payroll = Payroll::with(['items', 'creator', 'approver'])->findOrFail($id);
        $items = $payroll->items()->orderBy('department_name')->orderBy('employee_name')->get();

        return view('hr.payroll.print', compact('payroll', 'items'));
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status !== 'draft') {
            return back()->with('error', 'لا يمكن حذف مسير تم اعتماده أو صرفه.');
        }

        $payroll->delete();

        return redirect()->route('hr.payroll.index')
            ->with('success', 'تم حذف مسير الرواتب بنجاح.');
    }
}

