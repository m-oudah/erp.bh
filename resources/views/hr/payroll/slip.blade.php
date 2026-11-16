<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قسيمة راتب - {{ $item->employee_name }} - {{ $payroll->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @page {
            size: landscape;
            margin: 8mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white; margin: 0; padding: 0; color: #000 !important; }
            .print-page { border: none !important; box-shadow: none !important; padding: 0 !important; width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-black p-4 sm:p-6">

    {{-- Control Buttons (Hidden on Print) --}}
    <div class="max-w-6xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('hr.payroll.show', $payroll->id) }}"
           class="px-4 py-2 rounded-xl bg-white border border-slate-300 text-black text-xs font-bold hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            <span>الرجوع لاحتساب الرواتب</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()"
                    class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-black text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>طباعة قسيمة الراتب</span>
            </button>
        </div>
    </div>

    {{-- Printable Slip Document --}}
    <div class="max-w-6xl mx-auto bg-white rounded-2xl border-2 border-slate-300 shadow-sm p-6 print-page space-y-4">

        {{-- Official Municipality Header --}}
        <div class="flex items-center justify-between border-b-2 border-black pb-4 text-black">
            <div class="text-right text-xs space-y-0.5">
                <div class="font-bold">دولة فلسطين</div>
                <div class="font-bold">وزارة الحكم المحلي</div>
                <div class="font-black text-sm">بلدية بيت حانون</div>
                <div class="text-[11px] font-semibold">قسم الموارد البشرية والرواتب</div>
            </div>

            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="h-14 w-auto mx-auto mb-1">
                <h1 class="text-base font-black tracking-wide text-black">قسيمة راتب شهري</h1>
                <div class="text-xs font-bold text-black">{{ $payroll->title }}</div>
            </div>

            <div class="text-left text-xs font-mono space-y-0.5 text-black">
                <div>رقم المسير: <strong class="text-black">{{ $payroll->payroll_number }}</strong></div>
                <div>تاريخ الإصدار: <strong class="text-black">{{ date('Y-m-d') }}</strong></div>
                <div>العملة: <strong class="text-black">شيكل إسرائيلي (₪ NIS)</strong></div>
            </div>
        </div>

        {{-- Employee Details Grid (4 Columns Per Row) --}}
        <div class="border border-black rounded-xl overflow-hidden bg-slate-50">
            <table class="w-full text-xs text-black border-collapse" style="table-layout: fixed; width: 100%;">
                <tbody>
                    <tr style="border-bottom: 1px solid #000;">
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">اسم الموظف:</span>
                            <strong style="display: block; font-size: 12px; font-weight: 900; color: #000000; margin-top: 2px;">{{ $item->employee_name }}</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">الرقم الوظيفي:</span>
                            <strong style="display: block; font-size: 12px; font-family: monospace; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->employee_number }}</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">رقم الهوية:</span>
                            <strong style="display: block; font-size: 12px; font-family: monospace; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->employee?->national_id }}</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">نوع العقد:</span>
                            <strong style="display: block; font-size: 12px; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->contract_type }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">المسمى الوظيفي:</span>
                            <strong style="display: block; font-size: 12px; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->job_title }}</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">الوحدة الإدارية:</span>
                            <strong style="display: block; font-size: 12px; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->department_name }}</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px; border-left: 1px solid #000;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">الدرجة والمربوط:</span>
                            <strong style="display: block; font-size: 12px; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->grade_name }} (مربوط {{ $item->step }})</strong>
                        </td>
                        <td style="width: 25%; padding: 8px 12px;">
                            <span style="display: block; font-size: 11px; font-weight: 700; color: #000000;">الحساب البنكي:</span>
                            <strong style="display: block; font-size: 11px; font-family: monospace; font-weight: 800; color: #000000; margin-top: 2px;">{{ $item->bank_name ? "{$item->bank_name} - {$item->bank_account_number}" : 'غير مسجل' }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Earnings & Deductions Tables (Side by Side) --}}
        <div class="grid grid-cols-2 gap-4 text-xs text-black">

            {{-- 1. Earnings (الاستحقاقات والعلاوات) --}}
            <div class="border border-slate-300 rounded-xl overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="bg-slate-100 py-2 px-3 font-black text-black border-b border-slate-300 flex items-center justify-between">
                        <span>الاستحقاقات والعلاوات (المستحقات)</span>
                        <span class="font-mono text-xs font-bold">المبلغ (₪)</span>
                    </div>
                    <table class="w-full text-right divide-y divide-slate-200">
                        <tbody class="font-bold text-black">
                            <tr>
                                <td class="py-1.5 px-3">الراتب الأساسي (مربوط الدرجة)</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->base_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">علاوة طبيعة العمل</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->nature_of_work_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">العلاوة الاجتماعية (الزوجة)</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->spouse_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">العلاوة الاجتماعية (الأولاد)</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->children_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">علاوة المؤهل العلمي</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->qualification_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">علاوة المنصب والمسؤولية</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->position_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">علاوة غلاء المعيشة والمخاطرة</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->cost_of_living_allowance + $item->risk_allowance, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">العمل الإضافي والمكافآت</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->overtime_allowance + $item->bonus_allowance, 2) }}</td>
                            </tr>
                            @if($item->other_allowances > 0)
                            <tr>
                                <td class="py-1.5 px-3">علاوات ثابتة أخرى</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->other_allowances, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="border-t-2 border-black bg-slate-100 py-2 px-3 flex items-center justify-between font-black text-black">
                    <span>إجمالي الراتب القائم</span>
                    <span class="font-mono text-sm text-black">{{ number_format($item->gross_salary, 2) }} ₪</span>
                </div>
            </div>

            {{-- 2. Deductions (الاستقطاعات والخصومات) --}}
            <div class="border border-slate-300 rounded-xl overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="bg-slate-100 py-2 px-3 font-black text-black border-b border-slate-300 flex items-center justify-between">
                        <span>الاستقطاعات والخصومات</span>
                        <span class="font-mono text-xs font-bold">المبلغ (₪)</span>
                    </div>
                    <table class="w-full text-right divide-y divide-slate-200">
                        <tbody class="font-bold text-black">
                            <tr>
                                <td class="py-1.5 px-3">هيئة التقاعد والتأمينات والمعاشات</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->pension_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">التأمين الصحي</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->health_insurance_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="py-1.5 px-3">قسط سلفة / قرض بلدي</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->loan_deduction, 2) }}</td>
                            </tr>
                            @if($item->other_deductions > 0)
                            <tr>
                                <td class="py-1.5 px-3">خصومات واستقطاعات أخرى</td>
                                <td class="py-1.5 px-3 font-mono text-left text-black">{{ number_format($item->other_deductions, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="border-t-2 border-black bg-slate-100 py-2 px-3 flex items-center justify-between font-black text-black">
                    <span>إجمالي الاستقطاعات</span>
                    <span class="font-mono text-sm text-black">{{ number_format($item->total_deductions, 2) }} ₪</span>
                </div>
            </div>

        </div>

        {{-- Net Salary Callout Box --}}
        <div class="border-2 border-black bg-slate-50 text-black rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-right">
            <div>
                <div class="text-sm font-black text-black">صافي الراتب المستحق للصرف في الحساب البنكي</div>
                <div class="text-xs text-black font-semibold mt-0.5">
                    الراتب القائم ({{ number_format($item->gross_salary, 2) }} ₪) - إجمالي الخصومات ({{ number_format($item->total_deductions, 2) }} ₪)
                </div>
            </div>

            <div class="text-3xl font-black text-black font-mono tracking-tight">
                {{ number_format($item->net_salary, 2) }} <span class="text-sm font-bold text-black">شيكل (₪)</span>
            </div>
        </div>

        @if($item->notes)
            <div class="bg-slate-50 border border-slate-300 rounded-lg p-2.5 text-xs text-black font-bold">
                <strong>ملاحظة إدارية:</strong> {{ $item->notes }}
            </div>
        @endif

    </div>

</body>
</html>