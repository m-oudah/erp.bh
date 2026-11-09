<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة المعاملة رقم {{ $transaction->transaction_number }} - بلدية بيت حانون</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 20px;
        }
        .print-container {
            max-width: 860px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 36px 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            height: 320px;
            opacity: 0.045;
            pointer-events: none;
            z-index: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 20px;
            margin-bottom: 24px;
            position: relative;
            z-index: 1;
        }
        .header-col {
            text-align: center;
        }
        .header-title {
            font-size: 15px;
            font-weight: 800;
            color: #0d9488;
            margin-bottom: 4px;
        }
        .header-sub {
            font-size: 12px;
            color: #475569;
            font-weight: 600;
        }
        .logo-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logo-box img {
            width: 84px;
            height: 84px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.06));
        }
        .tx-info-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .tx-num {
            font-size: 16px;
            font-weight: 900;
            color: #0f766e;
            font-family: monospace;
        }
        .tx-date {
            font-size: 12px;
            color: #334155;
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
        }
        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f766e;
            border-right: 4px solid #d97706;
            padding-right: 8px;
            margin: 20px 0 12px 0;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
        }
        .info-label {
            font-size: 10px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 12px;
            color: #0f172a;
            font-weight: 700;
        }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px;
            font-size: 12px;
            line-height: 1.8;
            color: #1e293b;
            white-space: pre-wrap;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 8px;
        }
        .table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            padding: 8px 10px;
            text-align: right;
            border: 1px solid #cbd5e1;
        }
        .table td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .signatures {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
        }
        .signature-box {
            border-top: 1px dashed #94a3b8;
            padding-top: 8px;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
        }
        .no-print-bar {
            max-width: 860px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #0d9488;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            border: none;
            cursor: pointer;
        }
        .btn-back {
            background: #e2e8f0;
            color: #334155;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-container {
                border: none;
                box-shadow: none;
                padding: 10px;
                max-width: 100%;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    @if(!request('modal') && !request('embedded'))
    <div class="no-print-bar">
        <a href="{{ route('transactions.show', $transaction->id) }}" class="btn-back">← العودة لتفاصيل المعاملة</a>
        <button onclick="window.print()" class="btn-print">🖨️ طباعة الآن</button>
    </div>
    @endif

    <div class="print-container">
        {{-- Central Watermark --}}
        <div class="watermark">
            <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون">
        </div>

        {{-- Header --}}
        <div class="header">
            <div class="header-col">
                <div class="header-title">دولة فلسطين</div>
                <div class="header-sub">وزارة الحكم المحلي</div>
                <div class="header-sub" style="color: #0d9488; font-weight: 800;">بلدية بيت حانون</div>
            </div>
            <div class="logo-box">
                <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون">
            </div>
            <div class="header-col">
                <div class="header-title">نظام قلم الجمهور</div>
                <div class="header-sub">نموذج استدعاء ومعاملة رسمية</div>
                <div class="header-sub">{{ date('Y/m/d') }}</div>
            </div>
        </div>

        {{-- Transaction meta bar --}}
        <div class="tx-info-bar">
            <div>
                <span style="font-size:11px; color:#0f766e; font-weight:700;">رقم المعاملة:</span>
                <span class="tx-num">{{ $transaction->transaction_number }}</span>
            </div>
            <div class="tx-date">
                تاريخ التسجيل: {{ $transaction->created_at->format('Y/m/d H:i') }}
            </div>
            <div>
                <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                    {{ $transaction->status_label }}
                </span>
            </div>
        </div>

        {{-- Section 1: Citizen & Property --}}
        <div class="section-title">أولاً: بيانات مقدم الطلب والعقار</div>
        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">اسم المواطن مقدم الطلب</div>
                <div class="info-value">{{ $transaction->customer->name ?? 'غير محدد' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">رقم الهوية الوطنية</div>
                <div class="info-value">{{ $transaction->customer->id_no ?? $transaction->customer->id_number ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">رقم الهاتف / الجوال</div>
                <div class="info-value">{{ $transaction->customer->mobile ?? $transaction->customer->telephone ?? '-' }}</div>
            </div>
        </div>

        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">رقم القطعة</div>
                <div class="info-value">{{ $transaction->building->parcel_number ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">رقم القسيمة</div>
                <div class="info-value">{{ $transaction->building->block_number ?? '-' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">رقم ملف التنظيم</div>
                <div class="info-value">{{ $transaction->building->file_number ?? '-' }}</div>
            </div>
        </div>

        {{-- Section 2: Transaction Subject & Details --}}
        <div class="section-title">ثانياً: نوع المعاملة وموضوع الطلب</div>
        <div class="info-card" style="margin-bottom: 12px;">
            <div class="info-label">نوع المعاملة المطلوب</div>
            <div class="info-value" style="color: #0d9488; font-size: 13px;">
                {{ $transaction->transactionType->name ?? 'معاملة عامة' }}
            </div>
        </div>

        <div class="details-box">
            {{ $transaction->details ?? 'لا يوجد تفاصيل مسجلة' }}
        </div>

        {{-- Section 3: Department Recommendations --}}
        @if($transaction->departmentResponses && $transaction->departmentResponses->count())
            <div class="section-title">ثالثاً: دراسات وتوصيات الأقسام المختصة</div>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%;">القسم المختص</th>
                        <th style="width: 15%;">الحالة الفنية</th>
                        <th style="width: 45%;">التوصية الفنية</th>
                        <th style="width: 15%;">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->departmentResponses as $resp)
                        <tr>
                            <td style="font-weight:700;">{{ $resp->department_name }}</td>
                            <td>
                                @if($resp->status == 'approved')
                                    <span style="color:#16a34a; font-weight:700;">موافق عليه</span>
                                @elseif($resp->status == 'rejected')
                                    <span style="color:#dc2626; font-weight:700;">مرفوض</span>
                                @else
                                    <span style="color:#d97706; font-weight:700;">قيد الدراسة</span>
                                @endif
                            </td>
                            <td>{{ $resp->response_notes ?? '—' }}</td>
                            <td style="font-size:10px;">{{ $resp->responded_at ? \Carbon\Carbon::parse($resp->responded_at)->format('Y/m/d') : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Section 4: Final Executive Decision --}}
        <div class="section-title">رابعاً: قرار رئاسة البلدية / الإدارة التنفيذية</div>
        <div class="details-box" style="border-right: 4px solid #0d9488;">
            @if($transaction->mayor_opinion)
                <p><strong>الرأي / التوجيه الإداري:</strong> {{ $transaction->mayor_opinion }}</p>
            @endif
            @if($transaction->rejection_reason)
                <p style="color:#dc2626; margin-top:4px;"><strong>أسباب الإعادة / الرفض:</strong> {{ $transaction->rejection_reason }}</p>
            @endif
            @if(!$transaction->mayor_opinion && !$transaction->rejection_reason)
                <p style="color:#94a3b8; font-style:italic;">المعاملة قيد استكمال الإجراءات والتنسيق الإداري.</p>
            @endif
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">موظف قلم الجمهور</div>
                <div class="signature-box">{{ auth()->user()->name ?? 'موظف الاستقبال' }}</div>
            </div>
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">مدير الدائرة المختصة</div>
                <div class="signature-box">التوقيع والخاتم</div>
            </div>
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">رئيس البلدية / المفوض</div>
                <div class="signature-box">الاعتماد النهائي</div>
            </div>
        </div>
    </div>
</body>
</html>
