<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ $payroll->title }}</title>
    @verbatim
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>مسير الرواتب</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayRightToLeft/>
                        <x:Selected/>
                        <x:DoNotDisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    @endverbatim
    <style>
        body { font-family: 'Cairo', 'Tahoma', 'Arial', sans-serif; font-size: 11pt; direction: rtl; }
        table { border-collapse: collapse; width: 100%; direction: rtl; }
        th, td { border: 1px solid #718096; padding: 6px 8px; text-align: right; }
        th { background-color: #0d9488; color: #ffffff; font-weight: bold; text-align: center; }
        .sub-header { background-color: #14b8a6; color: #ffffff; font-weight: bold; text-align: center; }
        .num-text { mso-number-format: "\@"; text-align: center; }
        .money { mso-number-format: "#,##0.00"; text-align: left; }
        .center { text-align: center; }
        .total-row { background-color: #f1f5f9; font-weight: bold; border-top: 2px solid #000; border-bottom: 2px solid #000; }
        .title-box { text-align: center; font-size: 16pt; font-weight: bold; color: #0f172a; padding: 10px; }
        .sub-title { text-align: center; font-size: 12pt; font-weight: bold; color: #475569; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="28" class="title-box">بلدية بيت حانون - الدائرة المالية - قسم الرواتب</td>
        </tr>
        <tr>
            <td colspan="28" class="sub-title">{{ $payroll->title }} (رقم المسير: {{ $payroll->payroll_number }})</td>
        </tr>
        <tr>
            <td colspan="28" style="text-align: center; font-size: 9pt; color: #64748b;">تاريخ التصدير: {{ date('Y-m-d H:i') }} | إجمالي الموظفين: {{ $items->count() }}</td>
        </tr>
        <tr><td colspan="28" style="border: none; height: 10px;"></td></tr>
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">#</th>
                <th rowspan="2" style="width: 90px;">الرقم الوظيفي</th>
                <th rowspan="2" style="width: 180px;">اسم الموظف</th>
                <th rowspan="2" style="width: 110px;">رقم الهوية</th>
                <th rowspan="2" style="width: 160px;">المسمى الوظيفي</th>
                <th rowspan="2" style="width: 140px;">الدائرة / القسم</th>
                <th rowspan="2" style="width: 90px;">نوع العقد</th>
                <th rowspan="2" style="width: 90px;">الدرجة</th>
                <th rowspan="2" style="width: 120px;">اسم البنك</th>
                <th rowspan="2" style="width: 110px;">رقم الحساب</th>
                <th rowspan="2" style="width: 95px; background-color: #0f766e;">الأساسي (₪)</th>
                <th colspan="10" class="sub-header">البدلات والعلاوات (الاستحقاقات)</th>
                <th rowspan="2" style="width: 110px; background-color: #047857;">إجمالي القائم (₪)</th>
                <th colspan="4" class="sub-header" style="background-color: #b91c1c;">الاستقطاعات والخصومات</th>
                <th rowspan="2" style="width: 110px; background-color: #991b1b;">إجمالي الخصم (₪)</th>
                <th rowspan="2" style="width: 120px; background-color: #1e3a8a;">صافي الراتب (₪)</th>
            </tr>
            <tr>
                <th style="background-color: #0d9488;">طبيعة العمل</th>
                <th style="background-color: #0d9488;">ع. الزوجة</th>
                <th style="background-color: #0d9488;">ع. الأولاد</th>
                <th style="background-color: #0d9488;">ع. المؤهل</th>
                <th style="background-color: #0d9488;">ع. المنصب</th>
                <th style="background-color: #0d9488;">غلاء معيشة</th>
                <th style="background-color: #0d9488;">ع. المخاطرة</th>
                <th style="background-color: #0d9488;">عمل إضافي</th>
                <th style="background-color: #0d9488;">مكافآت</th>
                <th style="background-color: #0d9488;">علاوات أخرى</th>
                <th style="background-color: #dc2626;">تقاعد ومعاشات</th>
                <th style="background-color: #dc2626;">تأمين صحي</th>
                <th style="background-color: #dc2626;">أقساط وسلف</th>
                <th style="background-color: #dc2626;">خصومات أخرى</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sumBase = 0;
                $sumNature = 0;
                $sumSpouse = 0;
                $sumChildren = 0;
                $sumQual = 0;
                $sumPos = 0;
                $sumCost = 0;
                $sumRisk = 0;
                $sumOvertime = 0;
                $sumBonus = 0;
                $sumOtherAllow = 0;
                $sumGross = 0;
                $sumPension = 0;
                $sumHealth = 0;
                $sumLoan = 0;
                $sumOtherDeduct = 0;
                $sumTotalDeduct = 0;
                $sumNet = 0;
            @endphp
            @foreach($items as $idx => $item)
                @php
                    $sumBase += (float)$item->base_salary;
                    $sumNature += (float)$item->nature_of_work_allowance;
                    $sumSpouse += (float)$item->spouse_allowance;
                    $sumChildren += (float)$item->children_allowance;
                    $sumQual += (float)$item->qualification_allowance;
                    $sumPos += (float)$item->position_allowance;
                    $sumCost += (float)$item->cost_of_living_allowance;
                    $sumRisk += (float)$item->risk_allowance;
                    $sumOvertime += (float)$item->overtime_allowance;
                    $sumBonus += (float)$item->bonus_allowance;
                    $sumOtherAllow += (float)$item->other_allowances;
                    $sumGross += (float)$item->gross_salary;
                    $sumPension += (float)$item->pension_deduction;
                    $sumHealth += (float)$item->health_insurance_deduction;
                    $sumLoan += (float)$item->loan_deduction;
                    $sumOtherDeduct += (float)$item->other_deductions;
                    $sumTotalDeduct += (float)$item->total_deductions;
                    $sumNet += (float)$item->net_salary;
                @endphp
                <tr style="{{ $idx % 2 === 1 ? 'background-color: #f8fafc;' : '' }}">
                    <td class="center">{{ $idx + 1 }}</td>
                    <td class="num-text">{{ $item->employee_number }}</td>
                    <td style="font-weight: bold;">{{ $item->employee_name }}</td>
                    <td class="num-text">{{ $item->employee?->national_id }}</td>
                    <td>{{ $item->job_title }}</td>
                    <td>{{ $item->department_name }}</td>
                    <td class="center">{{ $item->contract_type }}</td>
                    <td class="center">{{ $item->grade_name }}</td>
                    <td>{{ $item->bank_name }}</td>
                    <td class="num-text">{{ $item->bank_account_number }}</td>
                    <td class="money">{{ number_format($item->base_salary, 2) }}</td>
                    <td class="money">{{ number_format($item->nature_of_work_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->spouse_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->children_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->qualification_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->position_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->cost_of_living_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->risk_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->overtime_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->bonus_allowance, 2) }}</td>
                    <td class="money">{{ number_format($item->other_allowances, 2) }}</td>
                    <td class="money" style="font-weight: bold; background-color: #ecfdf5;">{{ number_format($item->gross_salary, 2) }}</td>
                    <td class="money">{{ number_format($item->pension_deduction, 2) }}</td>
                    <td class="money">{{ number_format($item->health_insurance_deduction, 2) }}</td>
                    <td class="money">{{ number_format($item->loan_deduction, 2) }}</td>
                    <td class="money">{{ number_format($item->other_deductions, 2) }}</td>
                    <td class="money" style="font-weight: bold; background-color: #fef2f2;">{{ number_format($item->total_deductions, 2) }}</td>
                    <td class="money" style="font-weight: bold; background-color: #eff6ff; color: #1e3a8a;">{{ number_format($item->net_salary, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="10" class="center" style="font-size: 12pt;">المجموع العام ({{ $items->count() }} موظف)</td>
                <td class="money">{{ number_format($sumBase, 2) }}</td>
                <td class="money">{{ number_format($sumNature, 2) }}</td>
                <td class="money">{{ number_format($sumSpouse, 2) }}</td>
                <td class="money">{{ number_format($sumChildren, 2) }}</td>
                <td class="money">{{ number_format($sumQual, 2) }}</td>
                <td class="money">{{ number_format($sumPos, 2) }}</td>
                <td class="money">{{ number_format($sumCost, 2) }}</td>
                <td class="money">{{ number_format($sumRisk, 2) }}</td>
                <td class="money">{{ number_format($sumOvertime, 2) }}</td>
                <td class="money">{{ number_format($sumBonus, 2) }}</td>
                <td class="money">{{ number_format($sumOtherAllow, 2) }}</td>
                <td class="money" style="background-color: #a7f3d0;">{{ number_format($sumGross, 2) }}</td>
                <td class="money">{{ number_format($sumPension, 2) }}</td>
                <td class="money">{{ number_format($sumHealth, 2) }}</td>
                <td class="money">{{ number_format($sumLoan, 2) }}</td>
                <td class="money">{{ number_format($sumOtherDeduct, 2) }}</td>
                <td class="money" style="background-color: #fecaca;">{{ number_format($sumTotalDeduct, 2) }}</td>
                <td class="money" style="background-color: #bfdbfe; color: #1e3a8a;">{{ number_format($sumNet, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
