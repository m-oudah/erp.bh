<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>كشف رواتب الموظفين - {{ $payroll->title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            @page { size: landscape; margin: 8mm; }
            body { background: white; margin: 0; padding: 0; font-size: 10px; color: #000; }
            .print-table th, .print-table td { padding: 4px 6px !important; border-color: #94a3b8 !important; }
            .print-table tfoot td {
                background-color: #e2e8f0 !important;
                color: #000000 !important;
                border-color: #64748b !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 p-4">

    {{-- Controls --}}
    <div class="max-w-7xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('hr.payroll.show', $payroll->id) }}"
           class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-2xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            <span>الرجوع لكشف الرواتب</span>
        </a>

        <button onclick="window.print()"
                class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>طباعة كشف الرواتب (Landscape)</span>
        </button>
    </div>

    {{-- Printable Sheet --}}
    <div class="max-w-7xl mx-auto bg-white rounded-xl border border-slate-200 p-6 shadow-sm space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3">
            <div class="text-right text-xs">
                <div class="font-bold text-slate-600">دولة فلسطين - وزارة الحكم المحلي</div>
                <div class="font-black text-slate-900 text-sm">بلدية بيت حانون - الدائرة المالية</div>
                <div class="text-slate-500 text-[10px]">قسم الموارد البشرية وشؤون الموظفين</div>
            </div>

            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="h-14 w-auto mx-auto mb-1">
                <h1 class="text-base font-black text-slate-900">كشف رواتب الموظفين العام</h1>
                <div class="text-xs font-bold text-primary">{{ $payroll->title }}</div>
            </div>

            <div class="text-left text-xs font-mono space-y-0.5">
                <div>رقم الكشف: <strong>{{ $payroll->payroll_number }}</strong></div>
                <div>الحالة: <strong>{{ $payroll->status_arabic }}</strong></div>
                <div>تاريخ الطباعة: <strong>{{ date('Y-m-d') }}</strong></div>
                <div>العدد: <strong>{{ $payroll->total_employees }} موظف</strong></div>
            </div>
        </div>

        {{-- Master Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-right text-[10px] print-table border-collapse border border-slate-300">
                <thead>
                    <tr class="bg-slate-200 text-slate-900 font-black text-center border-b border-slate-300">
                        <th class="border border-slate-300 py-1.5 px-1 w-6">#</th>
                        <th class="border border-slate-300 py-1.5 px-2 text-right">الموظف / الرقم الوظيفي</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">الأساسي</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">طبيعة عمل</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">اجتماعية</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">مؤهل ومنصب</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">إضافي ومكافأة</th>
                        <th class="border border-slate-300 py-1.5 px-1.5 bg-emerald-100/60">إجمالي العلاوات</th>
                        <th class="border border-slate-300 py-1.5 px-1.5 bg-slate-100 font-black">الراتب القائم</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">التقاعد 10%</th>
                        <th class="border border-slate-300 py-1.5 px-1.5">تأمين وسلف</th>
                        <th class="border border-slate-300 py-1.5 px-1.5 bg-rose-100/60">إجمالي الخصم</th>
                        <th class="border border-slate-300 py-1.5 px-2 bg-teal-100 font-black text-xs">صافي الراتب (₪)</th>
                        <th class="border border-slate-300 py-1.5 px-2 text-right">البنك / رقم الحساب</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium">
                    @foreach($items as $i => $item)
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 py-1 px-1 text-center font-mono">{{ $i + 1 }}</td>
                            <td class="border border-slate-300 py-1 px-2">
                                <div class="font-bold text-slate-900 leading-tight">{{ $item->employee_name }}</div>
                                <div class="font-mono text-[9px] text-slate-500">{{ $item->employee_number }}</div>
                            </td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono font-bold">{{ number_format($item->base_salary, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono text-emerald-700">{{ number_format($item->nature_of_work_allowance, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono">{{ number_format($item->spouse_allowance + $item->children_allowance, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono">{{ number_format($item->qualification_allowance + $item->position_allowance, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono">{{ number_format($item->overtime_allowance + $item->bonus_allowance, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono font-bold text-emerald-800 bg-emerald-50/50">+ {{ number_format($item->total_allowances, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono font-black bg-slate-50">{{ number_format($item->gross_salary, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono text-rose-600">{{ number_format($item->pension_deduction, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono text-rose-600">{{ number_format($item->health_insurance_deduction + $item->loan_deduction + $item->other_deductions, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-1.5 text-center font-mono font-bold text-rose-700 bg-rose-50/50">- {{ number_format($item->total_deductions, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-2 text-center font-mono font-black text-slate-900 bg-teal-50 text-[11px]">{{ number_format($item->net_salary, 2) }}</td>
                            <td class="border border-slate-300 py-1 px-2 text-[9px] font-mono">
                                <div>{{ $item->bank_name }}</div>
                                <div>{{ $item->bank_account_number }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-400">
                    <tr class="bg-slate-200 text-black font-black text-[11px] text-center">
                        <td colspan="2" class="py-2.5 px-2 border border-slate-400 text-right font-black text-black bg-slate-200">
                            الإجمالي العام ({{ $payroll->total_employees }} موظف)
                        </td>
                        <td class="py-2.5 px-1 font-mono font-black text-black border border-slate-400 bg-slate-200">
                            {{ number_format($payroll->total_basic_salary, 2) }} ₪
                        </td>
                        <td colspan="4" class="border border-slate-400 bg-slate-200"></td>
                        <td class="py-2.5 px-1 font-mono font-black text-black border border-slate-400 bg-slate-200">
                            + {{ number_format($payroll->total_allowances, 2) }} ₪
                        </td>
                        <td class="py-2.5 px-1 font-mono font-black text-black border border-slate-400 bg-slate-200">
                            {{ number_format($payroll->total_basic_salary + $payroll->total_allowances, 2) }} ₪
                        </td>
                        <td colspan="2" class="border border-slate-400 bg-slate-200"></td>
                        <td class="py-2.5 px-1 font-mono font-black text-black border border-slate-400 bg-slate-200">
                            - {{ number_format($payroll->total_deductions, 2) }} ₪
                        </td>
                        <td class="py-2.5 px-2 font-mono text-black text-xs font-black border border-slate-400 bg-slate-300">
                            {{ number_format($payroll->total_net_salary, 2) }} ₪
                        </td>
                        <td class="border border-slate-400 bg-slate-200"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Official Signatures --}}
        <div class="grid grid-cols-3 gap-8 text-center pt-6 border-t-2 border-slate-800 text-xs">
            <div>
                <span class="font-bold text-slate-700 block mb-10">إعداد وتدقيق: محاسب الرواتب</span>
                <span class="border-b border-dashed border-slate-400 block w-40 mx-auto"></span>
            </div>

            <div>
                <span class="font-bold text-slate-700 block mb-10">تدقيق واعتماد: المدير المالي</span>
                <span class="border-b border-dashed border-slate-400 block w-40 mx-auto"></span>
            </div>

            <div>
                <span class="font-bold text-slate-700 block mb-10">مصادقة: رئيس بلدية بيت حانون</span>
                <span class="border-b border-dashed border-slate-400 block w-40 mx-auto"></span>
            </div>
        </div>

    </div>

</body>
</html>
