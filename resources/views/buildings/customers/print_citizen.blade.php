<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف المواطن الموحد - {{ $customer->name ?? '-' }} (هوية: {{ $customer->id_no ?? '-' }}) - بلدية بيت حانون</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            font-size: 11px;
            line-height: 1.5;
        }
        .print-container {
            max-width: 880px;
            margin: 0 auto;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 32px 36px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 360px;
            height: 360px;
            opacity: 0.038;
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
        .content-wrap {
            position: relative;
            z-index: 1;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .header-col {
            text-align: center;
        }
        .header-title {
            font-size: 14px;
            font-weight: 800;
            color: #0d9488;
            margin-bottom: 3px;
        }
        .header-sub {
            font-size: 11px;
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
            width: 76px;
            height: 76px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.06));
        }
        .logo-box .est {
            font-size: 9px;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
        }
        .unified-info-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        .info-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 800;
            padding: 5.5px 10px;
            text-align: right;
            border: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .info-table td {
            padding: 5.5px 10px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }
        .kpi-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 4px;
            text-align: center;
        }
        .kpi-count {
            font-size: 14px;
            font-weight: 900;
            color: #0f766e;
            font-family: monospace;
        }
        .kpi-label {
            font-size: 9px;
            color: #64748b;
            font-weight: 700;
            margin-top: 1px;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #0f766e;
            border-right: 4px solid #d97706;
            padding-right: 8px;
            margin: 16px 0 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-count {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 12px;
        }
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
        }
        .info-label {
            font-size: 9.5px;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 11.5px;
            color: #0f172a;
            font-weight: 700;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-top: 6px;
            margin-bottom: 12px;
        }
        .table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            padding: 6px 8px;
            text-align: right;
            border: 1px solid #cbd5e1;
        }
        .table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .table tbody tr:nth-child(even) {
            background-color: #fafbfc;
        }
        .empty-note {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            color: #64748b;
            font-size: 10.5px;
            text-align: center;
            margin-bottom: 10px;
        }
        .verification-card {
            margin-top: 20px;
            border: 1px dashed #94a3b8;
            border-radius: 10px;
            padding: 12px 16px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .verification-text {
            font-size: 10px;
            color: #475569;
            line-height: 1.6;
        }
        .signatures {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            text-align: center;
        }
        .signature-box {
            border-top: 1px dashed #94a3b8;
            padding-top: 6px;
            font-size: 10.5px;
            font-weight: 700;
            color: #475569;
        }
        .no-print-bar {
            max-width: 880px;
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
            font-size: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-back {
            background: #e2e8f0;
            color: #334155;
            padding: 8px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
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
                padding: 0;
                max-width: 100%;
            }
            .no-print-bar {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 10mm 12mm 10mm;
            }
            tr, .info-card, .section-title, .verification-card, .signatures {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @if(!request('modal') && !request('embedded'))
    <div class="no-print-bar">
        <a href="{{ route('customers.citizens.show', $customer->id) }}" class="btn-back">← العودة لملف المواطن</a>
        <button onclick="window.print()" class="btn-print">
            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/></svg>
            <span>طباعة الملف (A4)</span>
        </button>
    </div>
    @endif

    <div class="print-container">
        {{-- Central Watermark --}}
        <div class="watermark">
            <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون">
        </div>

        <div class="content-wrap">
            {{-- Official Municipal Header --}}
            <div class="header">
                <div class="header-col">
                    <div class="header-title">دولة فلسطين</div>
                    <div class="header-sub">وزارة الحكم المحلي</div>
                    <div class="header-sub" style="color: #0d9488; font-weight: 800;">بلدية بيت حانون</div>
                    <div class="header-sub" style="font-size: 9.5px;">دائرة خدمة الجمهور ونظم المعلومات</div>
                </div>
                <div class="logo-box">
                    <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون">
                </div>
                <div class="header-col">
                    <div class="header-title">نظام سجل المواطنين الموحد</div>
                    <div class="header-sub">كشف السجل الشامل وبيانات المواطن</div>
                    <div class="header-sub" style="font-family: monospace; font-weight: 700; color:#0f766e;">ID-{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="header-sub" style="font-size: 9.5px;">{{ now()->format('Y/m/d H:i') }}</div>
                </div>
            </div>

            {{-- Section 1: Citizen Personal & Contact Info --}}
            <div class="section-title" style="margin-top: 8px;">
                <span>أولاً: البيانات الأساسية والتواصل</span>
            </div>
            <div class="unified-info-box">
                <table class="info-table">
                    <tbody>
                        <tr>
                            <th style="width: 14%;">الاسم الكامل</th>
                            <td style="width: 36%; font-weight: 800; font-size: 11.5px; color: #0f172a;">{{ $customer->name ?? '-' }}</td>
                            <th style="width: 14%;">رقم الهوية</th>
                            <td style="width: 36%; font-family: monospace; font-size: 12px; font-weight: 800; color: #0f766e;">{{ $customer->id_no ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>رقم الجوال</th>
                            <td dir="ltr" style="text-align: right; font-family: monospace; font-weight: 700;">{{ $customer->mobile ?: '—' }}</td>
                            <th>العنوان السكني</th>
                            <td>{{ $customer->address ?: 'لا يوجد عنوان تفصيلي مسجل' }}</td>
                        </tr>
                        @if($customer->notes)
                        <tr>
                            <th>ملاحظات السجل</th>
                            <td colspan="3" style="color: #64748b;">{{ $customer->notes }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Summary KPI Strip --}}
            <div class="kpi-strip">
                <div class="kpi-item">
                    <div class="kpi-count">{{ $buildings->count() }}</div>
                    <div class="kpi-label">مبانٍ وعقارات</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-count">{{ $units->count() }}</div>
                    <div class="kpi-label">وحدات وشقق</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-count">{{ $transactions->count() }}</div>
                    <div class="kpi-label">معاملات قلم الجمهور</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-count">{{ $licenses->count() }}</div>
                    <div class="kpi-label">رخص أبنية وتنظيم</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-count">{{ $crafts->count() + $economicals->count() }}</div>
                    <div class="kpi-label">حرف ومنشآت تجارية</div>
                </div>
                <div class="kpi-item">
                    <div class="kpi-count">{{ $subscriptions->count() }}</div>
                    <div class="kpi-label">اشتراكات مياه وخدمات</div>
                </div>
            </div>

            {{-- Section 2: Properties & Buildings --}}
            <div class="section-title">
                <span>ثانياً: سجل المباني والعقارات والوحدات (موديول الأبنية والتنظيم)</span>
                <span class="section-count">{{ $buildings->count() }} مبنى / {{ $units->count() }} وحدة</span>
            </div>
            @if($buildings->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 12%;">رقم المبنى</th>
                            <th style="width: 28%;">اسم المبنى / المالك</th>
                            <th style="width: 20%;">المنطقة / الحي</th>
                            <th style="width: 15%;">الشارع</th>
                            <th style="width: 15%;">نوع المبنى</th>
                            <th style="width: 10%;">الحوض/القطعة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $b)
                            <tr>
                                <td style="font-weight:700; font-family:monospace;">{{ $b->building_number ?? $b->id }}</td>
                                <td style="font-weight:700;">{{ $b->building_name ?? ($customer->name . ' وعائلته') }}</td>
                                <td>{{ $b->zone?->zone_name ?? '—' }}</td>
                                <td>{{ $b->street?->street_name ?? '—' }}</td>
                                <td>{{ $b->buildingType?->name ?? 'سكني' }}</td>
                                <td style="font-family:monospace;">{{ $b->block_number ? $b->block_number . '/' . $b->parcel_number : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-note">لا توجد مبانٍ أو عقارات مسجلة باسم المواطن في السجل العقاري للبلدية.</div>
            @endif

            @if($units->count() > 0)
                <div style="font-size:10px; font-weight:800; color:#334155; margin:6px 0 3px 0;">الوحدات والشقق والمخازن التابعة للمواطن:</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">رقم الوحدة</th>
                            <th style="width: 25%;">نوع الوحدة / الاستخدام</th>
                            <th style="width: 20%;">الطابق</th>
                            <th style="width: 20%;">رقم المبنى التابع</th>
                            <th style="width: 20%;">الموقع / الحي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($units as $u)
                            <tr>
                                <td style="font-weight:700; font-family:monospace;">{{ $u->unit_number ?? 'وحدة #' . $u->id }}</td>
                                <td>{{ $u->unit_type_label ?? $u->unit_name ?? 'شقة سكنية' }}</td>
                                <td>{{ $u->floor?->floor_name ?? '—' }}</td>
                                <td style="font-family:monospace;">مبنى #{{ $u->building?->building_number ?? $u->building_id ?? '—' }}</td>
                                <td>{{ $u->building?->zone?->zone_name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Section 3: Transactions & Requests --}}
            <div class="section-title">
                <span>ثالثاً: سجل معاملات وطلبات قلم الجمهور (موديول قلم الجمهور)</span>
                <span class="section-count">{{ $transactions->count() }} معاملة</span>
            </div>
            @if($transactions->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 14%;">رقم المعاملة</th>
                            <th style="width: 13%;">تاريخ التسجيل</th>
                            <th style="width: 28%;">نوع المعاملة / موضوع الطلب</th>
                            <th style="width: 17%;">الموقع / الحي</th>
                            <th style="width: 13%;">حالة المعاملة</th>
                            <th style="width: 15%;">القرار / التوجيه</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $tx)
                            <tr>
                                <td style="font-weight:800; font-family:monospace; color:#0f766e;">{{ $tx->transaction_number }}</td>
                                <td>{{ $tx->created_at ? $tx->created_at->format('Y/m/d') : '—' }}</td>
                                <td style="font-weight:700;">{{ $tx->transactionType?->name ?? 'معاملة عامة' }}</td>
                                <td>{{ $tx->building?->zone?->zone_name ?? '—' }}</td>
                                <td>
                                    <span style="font-weight:700; color:
                                        @if($tx->status == 'completed' || $tx->status == 'approved') #16a34a
                                        @elseif($tx->status == 'rejected') #dc2626
                                        @elseif($tx->status == 'processing') #0284c7
                                        @else #d97706 @endif;">
                                        {{ $tx->status_label ?? $tx->status }}
                                    </span>
                                </td>
                                <td style="font-size:10px;">{{ Str::limit($tx->mayor_opinion ?: ($tx->details ?: 'قيد الإجراء'), 45) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-note">لا توجد طلبات أو معاملات رسمية مسجلة في قلم الجمهور لهذا المواطن.</div>
            @endif

            {{-- Section 4: Building Licenses --}}
            <div class="section-title">
                <span>رابعاً: سجل رخص البناء والتنظيم (موديول رخص الأبنية)</span>
                <span class="section-count">{{ $licenses->count() }} رخصة</span>
            </div>
            @if($licenses->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">رقم الملف / الرخصة</th>
                            <th style="width: 25%;">نوع الرخصة / الطلب</th>
                            <th style="width: 20%;">تاريخ الطلب</th>
                            <th style="width: 25%;">الموقع (الحي / الشارع)</th>
                            <th style="width: 15%;">حالة الطلب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($licenses as $lic)
                            <tr>
                                <td style="font-weight:700; font-family:monospace;">{{ $lic->license_number ?? ('LIC-' . $lic->id) }}</td>
                                <td>{{ $lic->license_type_label ?? 'ترخيص بناء جديد' }}</td>
                                <td>{{ $lic->created_at ? $lic->created_at->format('Y/m/d') : '—' }}</td>
                                <td>{{ ($lic->building?->zone?->zone_name ?? '') . ' ' . ($lic->building?->street?->street_name ?? '') ?: '—' }}</td>
                                <td style="font-weight:700; color:#0f766e;">{{ $lic->status_label ?? 'معتمد' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-note">لا توجد رخص بناء أو ملفات تنظيم مسجلة باسم المواطن حالياً.</div>
            @endif

            {{-- Section 5: Crafts & Economical Activities --}}
            <div class="section-title">
                <span>خامساً: سجل الحرف والمنشآت الاقتصادية (موديول الحرف والمهن)</span>
                <span class="section-count">{{ $crafts->count() + $economicals->count() }} منشأة / رخصة</span>
            </div>
            @if(($crafts->count() + $economicals->count()) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">رقم الرخصة / النشاط</th>
                            <th style="width: 30%;">اسم المنشأة / المحل</th>
                            <th style="width: 25%;">نوع النشاط / الحرفة</th>
                            <th style="width: 20%;">الموقع / الحي</th>
                            <th style="width: 10%;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($crafts as $cr)
                            <tr>
                                <td style="font-weight:700; font-family:monospace;">{{ $cr->craft_number ?? $cr->id }}</td>
                                <td style="font-weight:700;">{{ $cr->trade_name ?? $cr->name ?? 'منشأة مهنية' }}</td>
                                <td>{{ $cr->craftType?->name ?? 'حرفة / مهنة' }}</td>
                                <td>{{ $cr->address ?? 'بيت حانون' }}</td>
                                <td style="font-weight:700; color:#16a34a;">{{ $cr->statusRecord?->name ?? 'نشط' }}</td>
                            </tr>
                        @endforeach
                        @foreach($economicals as $ec)
                            <tr>
                                <td style="font-weight:700; font-family:monospace;">{{ $ec->id_card ?? ('EC-' . $ec->id) }}</td>
                                <td style="font-weight:700;">{{ $ec->name ?? 'منشأة اقتصادية' }}</td>
                                <td>{{ $ec->category?->name ?? 'نشاط تجاري' }}</td>
                                <td>{{ $ec->building?->zone?->zone_name ?? '—' }}</td>
                                <td style="font-weight:700; color:#16a34a;">مرخص</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-note">لا توجد رخص مهن أو منشآت تجارية مسجلة باسم المواطن.</div>
            @endif

            {{-- Section 6: Municipal Subscriptions & Water --}}
            <div class="section-title">
                <span>سادساً: سجل اشتراكات المياه والخدمات البلدية (موديول خدمات المشتركين)</span>
                <span class="section-count">{{ $subscriptions->count() }} اشتراك</span>
            </div>
            @if($subscriptions->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">رقم الاشتراك</th>
                            <th style="width: 25%;">اسم المشترك</th>
                            <th style="width: 20%;">الموقع / المبنى</th>
                            <th style="width: 10%;">الحالة</th>
                            <th style="width: 10%;">المطلوب (₪)</th>
                            <th style="width: 10%;">المسدد (₪)</th>
                            <th style="width: 10%;">المتبقي (₪)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalRemaining = 0; @endphp
                        @foreach($subscriptions as $sub)
                            @php $totalRemaining += (float)($sub->remaining ?? 0); @endphp
                            <tr>
                                <td style="font-weight:800; font-family:monospace; color:#0284c7;">{{ $sub->id_number ?? $sub->id }}</td>
                                <td style="font-weight:700;">{{ $sub->subscriber_name ?? $customer->name }}</td>
                                <td>{{ $sub->building?->zone?->zone_name ?? '—' }}</td>
                                <td>{{ $sub->status == 1 ? 'دائم / فعال' : 'أخرى' }}</td>
                                <td style="font-family:monospace;">{{ number_format($sub->amount ?? 0, 2) }}</td>
                                <td style="font-family:monospace; color:#16a34a;">{{ number_format($sub->paid ?? 0, 2) }}</td>
                                <td style="font-family:monospace; font-weight:800; color: {{ ($sub->remaining ?? 0) > 0 ? '#dc2626' : '#16a34a' }};">
                                    {{ number_format($sub->remaining ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if($totalRemaining > 0)
                        <tfoot>
                            <tr style="background:#fef2f2; font-weight:800;">
                                <td colspan="6" style="text-align:left; color:#991b1b;">إجمالي الذمم والالتزامات المالية المتبقية:</td>
                                <td style="font-family:monospace; color:#b91c1c; font-size:11px;">{{ number_format($totalRemaining, 2) }} ₪</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            @else
                <div class="empty-note">لا توجد اشتراكات مياه أو خدمات مباشرة مسجلة برقم الهوية.</div>
            @endif

            {{-- Section 7: Damage Assessment Records (if any) --}}
            @if(isset($damageRecords) && $damageRecords->count() > 0)
                <div class="section-title">
                    <span>سابعاً: سجل حصر الأضرار وتقييم الاحتياجات (موديول حصر الأضرار)</span>
                    <span class="section-count">{{ $damageRecords->count() }} سجل أضرار</span>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 14%;">رمز الحصر</th>
                            <th style="width: 18%;">القطاع المتضرر</th>
                            <th style="width: 18%;">مستوى الضرر</th>
                            <th style="width: 22%;">الحي والشارع</th>
                            <th style="width: 16%;">التكلفة المقدرة</th>
                            <th style="width: 12%;">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($damageRecords as $dmg)
                            @php
                                $levelText = match($dmg->damage_level) {
                                    'total_destruction' => 'مهدم كلياً (تدمير شامل)',
                                    'severe_partial', 'severe_damage' => 'ضرر جزئي بليغ (غير صالح للسكن)',
                                    'moderate_partial', 'partial_damage' => 'ضرر جزئي متوسط (قابل للتأهيل)',
                                    'minor', 'minor_damage' => 'ضرر جزئي طفيف',
                                    'intact' => 'سليم / بدون أضرار',
                                    default => ($dmg->damage_level_label ?: ($dmg->damage_level ?: 'غير محدد'))
                                };
                                $levelColor = match($dmg->damage_level) {
                                    'total_destruction' => '#dc2626',
                                    'severe_partial', 'severe_damage' => '#ea580c',
                                    'moderate_partial', 'partial_damage' => '#d97706',
                                    'minor', 'minor_damage' => '#2563eb',
                                    default => '#475569'
                                };
                            @endphp
                            <tr>
                                <td style="font-weight:800; font-family:monospace; color:#d97706;">{{ $dmg->record_code ?? ('DMG-' . $dmg->id) }}</td>
                                <td style="font-weight:700;">{{ $dmg->sector_label ?? $dmg->sector_name ?? 'قطاع المباني' }}</td>
                                <td>
                                    <span style="font-weight:800; color: {{ $levelColor }};">
                                        {{ $levelText }}
                                    </span>
                                </td>
                                <td>{{ ($dmg->zone?->zone_name ?? '') . ' ' . ($dmg->street_name ?? '') ?: '—' }}</td>
                                <td style="font-family:monospace; font-weight:700;">
                                    ${{ number_format($dmg->estimated_cost_usd ?? 0, 0) }}
                                </td>
                                <td style="font-weight:700; color:#0f766e;">{{ $dmg->workflow_status_label ?? $dmg->status_arabic ?? 'معتمد' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Official Verification Card with QR Code --}}
            <div class="verification-card">
                <div class="verification-text">
                    <strong style="color:#0f766e; display:block; margin-bottom:3px; font-size:11px;">إشعار توثيق واستخراج رسمي:</strong>
                    هذه الوثيقة مستخرجة إلكترونياً من قاعدة البيانات المركزية لبلدية بيت حانون - دائرة خدمة الجمهور ونظم المعلومات، وتتضمن حصراً شاملاً لكافة السجلات والأنشطة والارتباطات الرسمية المسجلة باسم المواطن حتى تاريخ وتوقيت طباعتها. يمكن التحقق من صحة هذه الوثيقة إلكترونياً عبر مسح رمز الاستجابة السريعة (QR Code).
                </div>
                @if(isset($qrUrl))
                <div style="shrink:0; text-align:center;">
                    <img src="{{ $qrUrl }}" alt="QR Code" style="width:68px; height:68px; border:1px solid #cbd5e1; border-radius:6px; padding:2px; background:white;">
                    <div style="font-size:8px; color:#64748b; font-weight:700; margin-top:2px;">مسح للتحقق</div>
                </div>
                @endif
            </div>

            {{-- Official Signatures & Municipal Stamp --}}
            <div class="signatures">
                <div>
                    <div style="font-weight:800; margin-bottom:32px; font-size:10.5px; color:#334155;">موظف الاستعلامات والسجلات</div>
                    <div class="signature-box">{{ auth()->user()->name ?? 'موظف قلم الجمهور' }}</div>
                </div>
                <div>
                    <div style="font-weight:800; margin-bottom:32px; font-size:10.5px; color:#334155;">رئيس قسم خدمات الجمهور</div>
                    <div class="signature-box">التوقيع والاعتماد</div>
                </div>
                <div>
                    <div style="font-weight:800; margin-bottom:32px; font-size:10.5px; color:#334155;">مدير الدائرة / رئيس البلدية</div>
                    <div class="signature-box">الختم الرسمي لبلدية بيت حانون</div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>