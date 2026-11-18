<?php

use App\Http\Controllers\HR\DepartmentHierarchyController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\GradeController;
use App\Http\Controllers\HR\HRDashboardController;
use App\Http\Controllers\HR\PayrollController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'module.access:hr'])->prefix('hr')->name('hr.')->group(function () {
    // HR Dashboard
    Route::get('/', [HRDashboardController::class, 'index'])->name('dashboard');

    // Employees Management
    Route::post('employees/preview-salary', [EmployeeController::class, 'previewSalary'])->name('employees.preview-salary');
    Route::resource('employees', EmployeeController::class);

    // Administrative Hierarchy (Departments, Sections, Units)
    Route::patch('departments/{id}/move', [DepartmentHierarchyController::class, 'move'])->name('departments.move');
    Route::post('departments/{id}/move', [DepartmentHierarchyController::class, 'move']);
    Route::resource('departments', DepartmentHierarchyController::class)->except(['create', 'show', 'edit']);

    // Civil Service Grades Scale (Moved to Settings)
    Route::get('grades', fn() => redirect()->route('settings.hr.index'))->name('grades.index');

    // Payroll Management
    Route::post('payroll/{id}/approve', [PayrollController::class, 'approve'])->name('payroll.approve');
    Route::post('payroll/{id}/mark-paid', [PayrollController::class, 'mark-paid'])->name('payroll.mark-paid');
    Route::put('payroll/{id}/items/{itemId}', [PayrollController::class, 'updateItem'])->name('payroll.items.update');
    Route::get('payroll/{id}/slip/{itemId}', [PayrollController::class, 'slip'])->name('payroll.slip');
    Route::get('payroll/{id}/print', [PayrollController::class, 'printFull'])->name('payroll.print');
    Route::get('payroll/{id}/export', [PayrollController::class, 'exportExcel'])->name('payroll.export');
    Route::resource('payroll', PayrollController::class)->except(['edit', 'update']);
});

