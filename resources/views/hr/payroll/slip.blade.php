<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قسيمة راتب - {{ $item->employee_name }} - {{ $payroll->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #f1f5f9;
            color: #000;
        }

        .slip-outer-frame {
            border: 1.5px solid #333333;
            background-color: #ffffff;
        }

        .slip-box {
            border: 1px solid #333333;
        }

        .slip-table {
            border-collapse: collapse;
            width: 100%;
        }

        .slip-table th, .slip-table td {
            border: 1px solid #333333;
            padding: 2.5px 6px;
        }

        .header-teal {
            background-color: #a3d9cf !important;
            color: #000000 !important;
            font-weight: 800;
        }

        .net-highlight {
            background-color: #a3d9cf !important;
            color: #000000 !important;
            font-weight: 900;
        }

        .admin-box {
            border: 1px solid #333333;
            display: flex;
            background-color: #ffffff;
        }

        .admin-col-group {
            display: flex;
            flex: 1;
        }

        .admin-col-group:first-child {
            border-left: 1px solid #333333;
        }

        .admin-labels {
            border-left: 1px solid #333333;
            padding: 5px 6px;
            font-weight: bold;
            color: #1e293b;
        }

        .admin-values {
            padding: 5px 8px;
            font-weight: 600;
            color: #000000;
        }

        @page {
            size: landscape;
            margin: 6mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #000 !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 8px !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: 1.5px solid #000 !important;
            }
            .slip-outer-frame, .slip-box, .slip-table th, .slip-table td,
            .admin-box, .admin-col-group:first-child, .admin-labels {
                border-color: #000 !important;
            }
            .header-teal, .net-highlight {
                background-color: #a3d9cf !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="p-3 sm:p-6 text-slate-900">

    @php
        $emp = $item->employee;
        $monthsLevant = [
            1 => 'كانون ثاني', 2 => 'شباط', 3 => 'آذار', 4 => 'نيسان',
            5 => 'أيار', 6 => 'حزيران', 7 => 'تموز', 8 => 'آب',
            9 => 'أيلول', 10 => 'تشرين أول', 11 => 'تشرين ثاني', 12 => 'كانون أول'
        ];
        $monthName = $monthsLevant[(int)$payroll->month] ?? ($payroll->monthNameArabic ?? 'الشهر الحالي');

        // Extract employee number cleanly (e.g. EMP-0148 -> 148)
        $empNumClean = $emp ? str_replace('EMP-', '', $emp->employee_number) : str_replace('EMP-', '', $item->employee_number);
        $empNumClean = ltrim($empNumClean, '0') ?: $empNumClean;

        // Sequence / Dept code for top right box (like 102 in the photo)
        $deptCode = $emp?->department?->code;
        if (!$deptCode || $deptCode === 'ROOT') {
            $deptCode = '102';
        }

        // Directorates / Departments
        $deptDirectorate = $emp?->department?->parent?->name ?? 'الادارة العامة';
        $deptSection = $item->department_name ?: ($emp?->department?->name ?? 'الحاسوب');

        // Marital status
        $maritalStatuses = [
            'single' => 'أعزب',
            'married' => 'متزوج',
            'divorced' => 'مطلق',
            'widowed' => 'أرمل',
        ];
        $maritalStatus = $maritalStatuses[$emp?->marital_status] ?? 'متزوج';

        // Contract type
        $contractType = $emp?->contract_type_arabic ?? ($item->contract_type ?? 'ثابت');
        if (str_contains($contractType, 'دائم') || str_contains($contractType, 'تثبيت')) {
            $contractType = 'ثابت';
        }

        // Allowances & Additions mapping
        $familyAllowance = (float)$item->spouse_allowance + (float)$item->children_allowance;
        $baseSalary = (float)$item->base_salary;
        $natureOfWork = (float)$item->nature_of_work_allowance;
        $overtime = (float)$item->overtime_allowance;
        $bonus = (float)$item->bonus_allowance;
        $posAllowance = (float)$item->position_allowance;
        $qualAllowance = (float)$item->qualification_allowance;
        $costOfLiving = (float)$item->cost_of_living_allowance;
        $riskAllowance = (float)$item->risk_allowance;
        $otherAllowances = (float)$item->other_allowances;

        // Total Additions (Gross Salary)
        $totalAdditions = (float)$item->gross_salary;

        // Deductions mapping
        $pensionDeduct = (float)$item->pension_deduction;
        $healthDeduct = (float)$item->health_insurance_deduction;
        $loanDeduct = (float)$item->loan_deduction;
        $otherDeduct = (float)$item->other_deductions;
        $totalDeductions = (float)$item->total_deductions;

        $netSalary = (float)$item->net_salary;
        $tafqeetWords = \App\Helpers\Tafqeet::convert($netSalary);

        // Nature of work rate
        $natureRate = $emp && $emp->nature_of_work_rate > 0 ? (int)$emp->nature_of_work_rate : 45;
        $dependents = $emp?->dependents_count ?? 4;
        $stepVal = $item->step > 0 ? $item->step : ($emp?->current_step ?? 1);
        $gradeName = $item->grade_name ?: ($emp?->grade?->name ?? 'الثالثة');
        $nationalId = $emp?->national_id ?: ($item->national_id ?? '410690341');
        $accountNo = $item->bank_account_number ?: ($emp?->bank_account_number ?: '2205885');
        $bankName = $item->bank_name ?: ($emp?->bank_name ?: 'البنك الاسلامي الفلسطيني');
    @endphp

    {{-- Control Action Bar (Hidden on Print) --}}
    <div class="max-w-[1150px] mx-auto mb-3 flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('hr.payroll.show', $payroll->id) }}"
           class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-800 text-xs font-bold hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-2xs">
            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            <span>الرجوع لمسير الرواتب ({{ $payroll->title }})</span>
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('hr.payroll.export', $payroll->id) }}"
               class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>تصدير المسير لإكسل</span>
            </a>

            <button onclick="window.print()"
                    class="px-5 py-2 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition-all shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة قسيمة الراتب</span>
            </button>
        </div>
    </div>

    {{-- Main Printable Payslip Container (Exact Replica of Municipal Slip) --}}
    <div class="max-w-[1150px] mx-auto bg-white p-4 sm:p-5 print-container slip-outer-frame shadow-xs">

        {{-- Top Header Row (Matching Attached Image Exactly) --}}
        <div class="slip-box p-2 mb-3 flex items-center justify-between text-xs bg-white relative">
            {{-- Right Section: Sequence No. & Municipality Info --}}
            <div class="text-right flex flex-col items-start gap-1">
                <div class="border border-slate-800 px-3 py-0.5 inline-block text-center font-mono font-bold text-xs bg-white">
                    {{ $deptCode }}
                </div>
                <div class="text-[12px] font-bold text-slate-900">
                    بلدية بيت حانون-الشئون الادارية-بيانات الموظفين
                </div>
            </div>

            {{-- Center Section: Main Title --}}
            <div class="text-center absolute left-1/2 -translate-x-1/2">
                <h1 class="text-base sm:text-lg font-bold text-slate-900 tracking-normal">
                    قسيمة راتب موظف عن شهر {{ $monthName }} لسنة {{ $payroll->year }}
                </h1>
            </div>

            {{-- Left Section: Department --}}
            <div class="text-left font-bold text-sm text-slate-900 pl-2">
                قسم الرواتب
            </div>
        </div>

        {{-- Main Two-Column Layout:
             In RTL:
             Column 1 (first child in HTML) renders on the RIGHT: Administrative Data (البيانات الادارية)
             Column 2 (second child in HTML) renders on the LEFT: Financial Data (البيانات المالية)
        --}}
        <div class="grid grid-cols-12 gap-4 items-start">

            {{-- RIGHT COLUMN (52% width): Administrative Data (البيانات الادارية) --}}
            <div class="col-span-12 lg:col-span-6 space-y-2">
                
                {{-- Section Title --}}
                <div class="text-xs font-bold text-slate-900 pr-1">
                    البيانات الادارية :
                </div>

                {{-- Administrative Details Box (4 vertical columns, no horizontal borders) --}}
                <div class="admin-box text-xs leading-normal">
                    
                    {{-- Right Sub-Block (Sub-col 1 in RTL: Labels + Values) --}}
                    <div class="admin-col-group">
                        {{-- Values Column (Center/Right aligned) --}}
                        <div class="admin-values flex-1 flex flex-col justify-between py-1.5 space-y-1 text-slate-900">
                            <div>{{ $empNumClean }}</div>
                            <div class="font-bold truncate">{{ $item->employee_name }}</div>
                            <div>{{ $item->job_title }}</div>
                            <div>{{ $contractType }}</div>
                            <div class="truncate">{{ $bankName }}</div>
                            <div>{{ $dependents }}</div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span>{{ $natureRate }} %</span>
                                <span class="text-slate-700">عدد الخاضعين</span>
                                <span class="pl-1">0</span>
                            </div>
                            <div>0</div>
                        </div>

                        {{-- Labels Column --}}
                        <div class="admin-labels w-[28%] flex flex-col justify-between py-1.5 space-y-1 text-right text-slate-800">
                            <div>رقم الموظف</div>
                            <div>اسم الموظف</div>
                            <div>الوظيفة</div>
                            <div>نوع الموظف</div>
                            <div>اسم البنك</div>
                            <div>عدد الاولاد</div>
                            <div>د.ط.العمل</div>
                            <div>عدد العائل</div>
                        </div>
                    </div>

                    {{-- Left Sub-Block (Sub-col 2 in RTL: Labels + Values) --}}
                    <div class="admin-col-group">
                        {{-- Values Column --}}
                        <div class="admin-values flex-1 flex flex-col justify-between py-1.5 space-y-1 text-slate-900 font-mono">
                            <div>{{ $nationalId }}</div>
                            <div class="font-sans">{{ $deptDirectorate }}</div>
                            <div class="font-sans">{{ $deptSection }}</div>
                            <div>{{ $accountNo }}</div>
                            <div class="font-sans">{{ $maritalStatus }}</div>
                            <div class="font-sans">{{ $gradeName }}</div>
                            <div>{{ $stepVal }}</div>
                            <div>0</div>
                            <div>{{ $emp?->grade?->category ?? '2' }}</div>
                        </div>

                        {{-- Labels Column --}}
                        <div class="admin-labels w-[34%] flex flex-col justify-between py-1.5 space-y-1 text-right text-slate-800">
                            <div>رقم الهوية</div>
                            <div>الدائرة</div>
                            <div>القسم</div>
                            <div>رقم الحساب</div>
                            <div>الحالة الاجتماعية</div>
                            <div>الدرجة</div>
                            <div>ع . بعرض الترقية</div>
                            <div>ع . زيادة الراتب</div>
                            <div>الفئة</div>
                        </div>
                    </div>

                </div>

                {{-- Below Administrative Box: Notice Box (Right) & Empty Box (Left) --}}
                <div class="grid grid-cols-2 gap-3 pt-1">
                    {{-- Right Box: Official Notice --}}
                    <div class="slip-box p-3 text-center flex flex-col justify-center items-center min-h-[85px] bg-white">
                        <div class="font-bold text-xs text-slate-900 leading-relaxed">
                            في حالة وجود أي خطأ في استمارة الراتب
                        </div>
                        <div class="font-bold text-[11px] text-slate-800 mt-1">
                            يرجى مراجعة قسم شؤون الموظفين
                        </div>
                    </div>

                    {{-- Left Box: Empty Bordered Frame (Matching Image) --}}
                    <div class="slip-box min-h-[85px] bg-white"></div>
                </div>

            </div>


            {{-- LEFT COLUMN (48% width): Financial Data (البيانات المالية) --}}
            <div class="col-span-12 lg:col-span-6 space-y-2">
                
                {{-- Section Title --}}
                <div class="text-xs font-bold text-slate-900 pr-1">
                    البيانات المالية :
                </div>

                {{-- Two Sub-Tables: Right = الاضافات, Left = الخصميات --}}
                <div class="grid grid-cols-12 gap-3">

                    {{-- 1. Right Sub-Table in RTL (62%): الاضافات (Additions) --}}
                    <div class="col-span-7">
                        <table class="slip-table text-[11px] leading-tight">
                            <thead>
                                <tr>
                                    <th colspan="2" class="header-teal text-center py-0.5 text-xs">
                                        الاضافات
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-bold text-right text-slate-800 w-[58%]">الاساسي</td>
                                    <td class="font-mono font-bold text-left text-slate-900 w-[42%]">{{ number_format($baseSalary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">طبيعة العمل</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($natureOfWork, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">العلاوة العائلية</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($familyAllowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">السفر الداخلي</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">عمل اضافي</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($overtime, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">اضافات</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($bonus, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة زيادة الراتب</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">ع . ط . العمل (تكملة)</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">ع . ط . العمل (68%)</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوات اخرى</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($otherAllowances, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة ادارية</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($posAllowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة تخصص</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($qualAllowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة مقطوعة</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة المعاشات</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">بدل جوال</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">فرق راتب</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">ع . غلاء المعيشة</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($costOfLiving, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة ترقية 5%</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة ترقية أقدمية</td>
                                    <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                </tr>
                                <tr>
                                    <td class="font-bold text-right text-slate-800">علاوة خطر</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($riskAllowance, 2) }}</td>
                                </tr>
                                {{-- Total Additions --}}
                                <tr>
                                    <td class="font-bold text-right text-slate-900 bg-white">مجموع الاضافات</td>
                                    <td class="font-mono font-bold text-left text-slate-900 bg-white">{{ number_format($totalAdditions, 2) }}</td>
                                </tr>
                                {{-- Net Salary Highlight --}}
                                <tr class="net-highlight">
                                    <td class="font-bold text-right">الصافي للقبض</td>
                                    <td class="font-mono font-bold text-left text-xs">{{ number_format($netSalary, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- 2. Left Sub-Table in RTL (38%): الخصميات (Deductions) + Coupon --}}
                    <div class="col-span-5 flex flex-col justify-between">
                        <div>
                            <table class="slip-table text-[11px] leading-tight">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="header-teal text-center py-0.5 text-xs">
                                            الخصميات
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800 w-[55%]">تأمين ومعاشات</td>
                                        <td class="font-mono font-bold text-left text-slate-900 w-[45%]">{{ number_format($pensionDeduct, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">علاوة معاشات</td>
                                        <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">تأمين صحي</td>
                                        <td class="font-mono font-bold text-left text-slate-900">{{ number_format($healthDeduct, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">ضريبة الدخل</td>
                                        <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">القسط</td>
                                        <td class="font-mono font-bold text-left text-slate-900">{{ number_format($loanDeduct, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">غياب</td>
                                        <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">خصم مياه</td>
                                        <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">خصميات اخرى</td>
                                        <td class="font-mono font-bold text-left text-slate-900">{{ number_format($otherDeduct, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-bold text-right text-slate-800">خصم جوال</td>
                                        <td class="font-mono font-bold text-left text-slate-900">0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Total Deductions (Aligned with مجموع الإضافات) & Dashed Coupon Box --}}
                        <div class="mt-auto pt-2 space-y-3">
                            <table class="slip-table text-[11px] leading-tight">
                                <tbody>
                                    <tr>
                                        <td class="font-bold text-right text-slate-900 bg-white w-[55%]">مجموع الخصميات</td>
                                        <td class="font-mono font-bold text-left text-slate-900 bg-white w-[45%]">{{ number_format($totalDeductions, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- Dashed Health Insurance Coupon Box (Bottom Left as in Photo) --}}
                            <div class="p-2 border-2 border-dashed border-slate-900 text-xs font-mono font-bold text-slate-900 bg-white space-y-0.5">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-sans font-bold">رقم الهوية</span>
                                    <span>{{ $nationalId }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="font-sans font-bold">تأمين صحي</span>
                                    <span>{{ number_format($healthDeduct, 2) }}</span>
                                </div>
                                <div class="text-left text-[11px] pt-0.5">
                                    {{ $payroll->year }}/{{ sprintf('%02d', (int)$payroll->month) }}
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Spelled-out Words (Tafqeet) Below Additions & Net Amount --}}
                <div class="pt-1 text-xs font-bold text-slate-900 text-center leading-normal">
                    {{ $tafqeetWords }}
                </div>

            </div>

        </div>

    </div>

</body>
</html>