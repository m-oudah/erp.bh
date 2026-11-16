<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Correspondence\Department;
use App\Models\HR\Employee;
use App\Models\HR\Grade;
use App\Models\HR\Payroll;
use Illuminate\Http\Request;

class HRDashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $suspendedEmployees = Employee::where('status', 'suspended')->count();
        $terminatedEmployees = Employee::where('status', 'terminated')->count();

        // Contract breakdown
        $contracts = [
            'permanent' => Employee::where('contract_type', 'permanent')->where('status', 'active')->count(),
            'temporary_contract' => Employee::where('contract_type', 'temporary_contract')->where('status', 'active')->count(),
            'daily' => Employee::where('contract_type', 'daily')->where('status', 'active')->count(),
            'unemployment' => Employee::where('contract_type', 'unemployment')->where('status', 'active')->count(),
            'delegation' => Employee::where('contract_type', 'delegation')->where('status', 'active')->count(),
        ];

        // Total departments and units
        $totalDepartments = Department::count();
        $directoratesCount = Department::where('type', 'directorate')->count();
        $sectionsCount = Department::whereIn('type', ['department', 'section', 'unit'])->count();

        // Latest payroll
        $latestPayroll = Payroll::latest('id')->first();
        $recentPayrolls = Payroll::latest('id')->take(5)->get();

        // Recent added employees
        $recentEmployees = Employee::with('department', 'grade')->latest('id')->take(6)->get();

        // Estimate total monthly salaries for active employees
        $activeList = Employee::with('grade')->where('status', 'active')->get();
        $estimatedMonthlyTotal = 0;
        foreach ($activeList as $emp) {
            $comps = $emp->calculateSalaryComponents();
            $estimatedMonthlyTotal += $comps['net_salary'];
        }

        $gradesCount = Grade::where('is_active', true)->count();

        return view('hr.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'suspendedEmployees',
            'terminatedEmployees',
            'contracts',
            'totalDepartments',
            'directoratesCount',
            'sectionsCount',
            'latestPayroll',
            'recentPayrolls',
            'recentEmployees',
            'estimatedMonthlyTotal',
            'gradesCount'
        ));
    }
}

