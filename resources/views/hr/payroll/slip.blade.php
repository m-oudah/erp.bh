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
            background-color: #f8fafc;
            color: #0f172a;
        }

        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-custom th, .table-custom td {
            border-bottom: 1px solid #e2e8f0;
            padding: 3.5px 7px;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .admin-grid {
            display: grid !important;
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
            width: 100% !important;
            gap: 12px !important;
        }

        .financial-row {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            width: 100% !important;
            gap: 16px !important;
        }

        .financial-col-additions {
            width: 100% !important;
            min-width: 0 !important;
        }

        .financial-col-deductions {
            width: 100% !important;
            min-width: 0 !important;
        }

        @page {
            size: A4 portrait;
            margin: 6mm 8mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                color: #000000 !important;
                width: 100% !important;
                height: auto !important;
                font-size: 10px !important;
            }
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 4mm 5mm !important;
                box-shadow: none !important;
                border: 1px solid #64748b !important;
                border-radius: 12px !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
            }
            .print-border {
                border-color: #94a3b8 !important;
            }
            .print-bg-light {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-bg-teal {
                background-color: #ccfbf1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-bg-dark {
                background-color: #0f172a !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .financial-row {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                width: 100% !important;
                gap: 12px !important;
            }
            .financial-col-additions {
                width: 100% !important;
                min-width: 0 !important;
                display: block !important;
            }
            .financial-col-deductions {
                width: 100% !important;
                min-width: 0 !important;
                display: flex !important;
                flex-direction: column !important;
            }
            .admin-grid {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                width: 100% !important;
                gap: 8px !important;
            }
            .hero-salary-card {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-between !important;
                align-items: center !important;
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

        // Sequence / Dept code
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
    <div class="w-full mb-4 p-3 bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-3 no-print">
        <a href="{{ route('hr.payroll.show', $payroll->id) }}"
           style="background-color: #f8fafc; color: #0f172a; border: 1.5px solid #cbd5e1;"
           class="px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-100 transition-all flex items-center gap-2 shadow-xs cursor-pointer">
            <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            <span class="font-extrabold text-slate-900">الرجوع لكشف الرواتب ({{ $payroll->title }})</span>
        </a>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('hr.payroll.export', $payroll->id) }}"
               style="background-color: #059669; color: #ffffff;"
               class="px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-extrabold text-white">تصدير المسير لإكسل</span>
            </a>

            <button onclick="window.print()"
                    style="background-color: #1d4ed8; color: #ffffff;"
                    class="px-5 py-2.5 rounded-xl font-extrabold text-xs hover:bg-blue-800 transition-all flex items-center gap-2 shadow-md cursor-pointer">
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span class="text-white font-extrabold">طباعة قسيمة الراتب (A4)</span>
            </button>
        </div>
    </div>

    {{-- Main Printable Document (A4 Portrait Container) --}}
    <div class="w-full bg-white p-5 sm:p-7 print-container border border-slate-300 rounded-3xl shadow-sm space-y-4">

        {{-- 1. Modern Header Row --}}
        <div class="pb-3 border-b border-slate-200 flex items-center justify-between gap-3">
            {{-- Right: Municipality Crest & Branding --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="w-12 h-12 object-contain shrink-0" onerror="this.style.display='none'">
                <div>
                    <div class="text-[11px] font-bold text-slate-500 leading-tight">دولة فلسطين - وزارة الحكم المحلي</div>
                    <div class="text-sm font-black text-slate-900 leading-tight mt-0.5">بلدية بيت حانون - الشئون الإدارية</div>
                    <div class="text-[10px] font-extrabold text-teal-700">قسم الرواتب والمستحقات</div>
                </div>
            </div>

            {{-- Center: Slip Title Badge --}}
            <div class="text-center">
                <span class="inline-block px-3 py-1 rounded-full bg-teal-50 border border-teal-200 text-teal-800 text-[11px] font-black tracking-wide">
                    قسيمة راتب موظف
                </span>
                <h1 class="text-sm sm:text-base font-black text-slate-900 mt-1">
                    عن شهر {{ $monthName }} لسنة {{ $payroll->year }}
                </h1>
            </div>

            {{-- Left: Slip ID & Dept Code --}}
            <div class="text-left font-mono space-y-1">
                <div class="flex items-center justify-end gap-1.5">
                    <span class="text-[10px] font-sans text-slate-500 font-bold">الرمز:</span>
                    <span class="px-2 py-0.5 rounded-lg bg-slate-100 border border-slate-200 text-xs font-black text-slate-900">{{ $deptCode }}</span>
                </div>
                <div class="text-[10px] text-slate-500 font-sans">
                    الرقم: <span class="font-bold text-slate-800 font-mono">#{{ sprintf('%03d', $empNumClean) }}</span>
                </div>
            </div>
        </div>

        {{-- 2. Administrative Information Card (بطاقة البيانات الإدارية والوظيفية) --}}
        <div class="bg-slate-50/80 rounded-2xl p-3.5 border border-slate-200 space-y-2.5">
            {{-- Top Employee Summary Row --}}
            <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-slate-200/80">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-mono font-black flex items-center justify-center text-xs shadow-xs shrink-0">
                        {{ $empNumClean }}
                    </div>
                    <div>
                        <div class="text-sm font-black text-slate-900 leading-tight">{{ $item->employee_name }}</div>
                        <div class="text-[11px] font-bold text-slate-600 mt-0.5">
                            {{ $item->job_title }} • <span class="text-teal-700">{{ $contractType }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-xs font-mono">
                    <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-800 font-bold">
                        هوية: <strong class="text-slate-900">{{ $nationalId }}</strong>
                    </span>
                    <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-800 font-bold">
                        فئة: <strong class="text-slate-900">{{ $emp?->grade?->category ?? '2' }}</strong>
                    </span>
                </div>
            </div>

            {{-- Grid of Detailed Administrative Fields (4 Columns in 1 Row) --}}
            <div class="admin-grid grid grid-cols-4 gap-2.5 text-xs w-full">
                <div class="p-2.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                    <span class="text-[10px] text-slate-500 font-bold block mb-0.5">الدائرة / القسم</span>
                    <span class="font-black text-slate-900 block text-[11px] leading-tight" title="{{ $deptDirectorate }} - {{ $deptSection }}">
                        {{ $deptDirectorate }} - {{ $deptSection }}
                    </span>
                </div>

                <div class="p-2.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                    <span class="text-[10px] text-slate-500 font-bold block mb-0.5">اسم البنك / الحساب</span>
                    <span class="font-black text-slate-900 block text-[11px] leading-tight" title="{{ $bankName }} ({{ $accountNo }})">
                        {{ $bankName }} • <span class="font-mono">{{ $accountNo }}</span>
                    </span>
                </div>

                <div class="p-2.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                    <span class="text-[10px] text-slate-500 font-bold block mb-0.5">الحالة / الأولاد / المعالين</span>
                    <span class="font-black text-slate-900 block text-[11px] leading-tight">
                        {{ $maritalStatus }} • {{ $dependents }} أولاد • 0 معال
                    </span>
                </div>

                <div class="p-2.5 bg-white rounded-xl border border-slate-200/90 shadow-2xs">
                    <span class="text-[10px] text-slate-500 font-bold block mb-0.5">الدرجة / ع. الترقية / الزيادة</span>
                    <span class="font-black text-slate-900 block text-[11px] leading-tight">
                        {{ $gradeName }} • رتبة {{ $stepVal }} • زيادة 0
                    </span>
                </div>
            </div>
        </div>

        {{-- 3. Financial Columns: Additions (Right) & Deductions (Left) - Spanning Full Width (2 Columns in 1 Row) --}}
        <div class="financial-row grid grid-cols-2 gap-6 w-full">

            {{-- 3.1. RIGHT COLUMN (Additions / الإضافات والاستحقاقات) --}}
            <div class="financial-col-additions w-full space-y-2">
                <div class="flex items-center justify-between px-1">
                    <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-600 inline-block"></span>
                        <span>بيانات الإضافات والاستحقاقات</span>
                    </span>
                    <span class="text-[10px] font-bold text-slate-500">20 بنداً مالياً</span>
                </div>

                <div class="rounded-2xl border border-slate-300 overflow-hidden bg-white shadow-2xs w-full">
                    <table class="table-custom text-[11px] leading-tight w-full">
                        <thead>
                            <tr class="bg-teal-50/80 text-teal-900 font-black border-b border-teal-200">
                                <th class="text-right py-1 px-2.5">البند / العلاوة</th>
                                <th class="text-left py-1 px-2.5 font-mono w-28">المبلغ (₪)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="bg-slate-50/40">
                                <td class="font-bold text-slate-800">الراتب الأساسي</td>
                                <td class="font-mono font-black text-left text-slate-900">{{ number_format($baseSalary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">طبيعة العمل ({{ $natureRate }}%)</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($natureOfWork, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">العلاوة العائلية (زوجة + أبناء)</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($familyAllowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">السفر الداخلي</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">عمل إضافي</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($overtime, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">إضافات ومكافآت</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($bonus, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة زيادة الراتب</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">ع . ط . العمل (تكملة)</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">ع . ط . العمل (68%)</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوات أخرى</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($otherAllowances, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة إدارية (منصب)</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($posAllowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة تخصص ومؤهل علمي</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($qualAllowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة مقطوعة</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة المعاشات (مساهمة البلدية)</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">بدل جوال</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">فرق راتب</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">ع . غلاء المعيشة</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($costOfLiving, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة ترقية 5%</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة ترقية أقدمية</td>
                                <td class="font-mono text-left text-slate-500">0.00</td>
                            </tr>
                            <tr>
                                <td class="text-slate-800">علاوة خطر</td>
                                <td class="font-mono font-bold text-left text-slate-900">{{ number_format($riskAllowance, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-100 font-black border-t-2 border-slate-300">
                                <td class="text-right py-2 px-2.5 text-slate-900">مجموع الإضافات (الراتب القائم)</td>
                                <td class="font-mono font-black text-left text-slate-900 text-xs py-2 px-2.5">{{ number_format($totalAdditions, 2) }} ₪</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- 3.2. LEFT COLUMN (Deductions / الخصميات والاستقطاعات + كوبون التأمين) --}}
            <div class="financial-col-deductions w-full space-y-3">
                <div class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                            <span>الخصميات والاستقطاعات</span>
                        </span>
                        <span class="text-[10px] font-bold text-slate-500">9 بنود</span>
                    </div>

                    <div class="rounded-2xl border border-slate-300 overflow-hidden bg-white shadow-2xs w-full">
                        <table class="table-custom text-[11px] leading-tight w-full">
                            <thead>
                                <tr class="bg-rose-50/70 text-rose-900 font-black border-b border-rose-200">
                                    <th class="text-right py-1 px-2.5">بند الاستقطاع</th>
                                    <th class="text-left py-1 px-2.5 font-mono w-24">المبلغ (₪)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="text-slate-800">تأمين ومعاشات (تقاعد)</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($pensionDeduct, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">علاوة معاشات</td>
                                    <td class="font-mono text-left text-slate-500">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">تأمين صحي حكومي</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($healthDeduct, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">ضريبة الدخل</td>
                                    <td class="font-mono text-left text-slate-500">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">سلف وقروض (القسط)</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($loanDeduct, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">خصم غياب</td>
                                    <td class="font-mono text-left text-slate-500">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">خصم مياه</td>
                                    <td class="font-mono text-left text-slate-500">0.00</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">خصميات أخرى</td>
                                    <td class="font-mono font-bold text-left text-slate-900">{{ number_format($otherDeduct, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-800">خصم جوال</td>
                                    <td class="font-mono text-left text-slate-500">0.00</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-rose-50/50 font-black border-t-2 border-slate-300">
                                    <td class="text-right py-2 px-2.5 text-rose-950">مجموع الخصميات</td>
                                    <td class="font-mono font-black text-left text-rose-950 text-xs py-2 px-2.5">{{ number_format($totalDeductions, 2) }} ₪</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Dashed Health Insurance Coupon Box (Bottom Left as in Municipal Spec) --}}
                <div class="rounded-2xl border-2 border-dashed border-teal-700/60 p-3 bg-teal-50/40 space-y-1.5 text-xs font-mono w-full">
                    <div class="flex items-center justify-between text-[11px] font-sans font-bold text-teal-900 pb-1 border-b border-teal-200/60">
                        <span>قسيمة اقتطاع التأمين الصحي</span>
                        <span class="text-[10px] text-teal-700 font-mono">{{ $payroll->year }}/{{ sprintf('%02d', (int)$payroll->month) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-sans text-slate-600 font-bold">رقم هوية الموظف:</span>
                        <span class="font-black text-slate-900">{{ $nationalId }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-sans text-slate-600 font-bold">قيمة التأمين المقتطع:</span>
                        <span class="font-black text-teal-900">{{ number_format($healthDeduct, 2) }} ₪</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- 4. Net Salary Hero Card & Tafqeet (البطاقة الكبرى لصافي القبض والتفقيط) --}}
        <div class="hero-salary-card rounded-2xl border-2 border-teal-600 bg-gradient-to-r from-teal-50 via-emerald-50/60 to-white p-3.5 sm:p-4 flex flex-row items-center justify-between gap-3 shadow-xs">
            <div class="space-y-1 flex-1">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-teal-600 inline-block shrink-0"></span>
                    <span class="text-xs font-black text-teal-950 tracking-wide uppercase">الصافي المستحق للصرف (Net Salary)</span>
                </div>
                <div class="text-xs font-bold text-slate-700 pr-5 leading-normal">
                    فقط: <span class="font-extrabold text-slate-900 underline decoration-teal-500/50 decoration-2">{{ $tafqeetWords }}</span>
                </div>
            </div>

            <div class="text-left shrink-0 sm:border-r sm:border-teal-200/80 sm:pr-4 sm:mr-2">
                <span class="text-[11px] font-bold text-teal-800 block">المبلغ الصافي للقبض</span>
                <span class="text-2xl font-black text-teal-950 font-mono tracking-tight">{{ number_format($netSalary, 2) }} <span class="text-sm font-sans">شيكل</span></span>
            </div>
        </div>

        {{-- 5. Signatures Row --}}
        <div class="grid grid-cols-3 gap-4 text-center pt-2 text-xs text-slate-600">
            <div class="space-y-6">
                <span class="font-bold block">إعداد: محاسب الرواتب</span>
                <span class="border-b border-dashed border-slate-300 block w-28 mx-auto"></span>
            </div>
            <div class="space-y-6">
                <span class="font-bold block">تدقيق: المدير المالي</span>
                <span class="border-b border-dashed border-slate-300 block w-28 mx-auto"></span>
            </div>
            <div class="space-y-6">
                <span class="font-bold block">اعتماد: رئيس البلدية</span>
                <span class="border-b border-dashed border-slate-300 block w-28 mx-auto"></span>
            </div>
        </div>

        {{-- 6. Footer Notice (تذييل الاستمارة) --}}
        <div class="pt-3 border-t border-slate-200 text-center">
            <p class="text-xs font-bold text-slate-700">
                في حالة وجود أي خطأ في استمارة الراتب يرجى مراجعة قسم شؤون الموظفين بالبلدية
            </p>
        </div>

    </div>

</body>
</html>
