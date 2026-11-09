<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة المراسلة الداخلية {{ $memo->memo_number }} - بلدية بيت حانون</title>
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
        .memo-info-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .memo-num {
            font-size: 16px;
            font-weight: 900;
            color: #0f766e;
            font-family: monospace;
        }
        .memo-date {
            font-size: 12px;
            color: #334155;
            font-weight: 700;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
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
            position: relative;
            z-index: 1;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
            position: relative;
            z-index: 1;
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
            position: relative;
            z-index: 1;
        }
        .movement-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-right: 4px solid #0d9488;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        .movement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }
        .movement-action {
            font-size: 12px;
            font-weight: 800;
            color: #0f766e;
        }
        .movement-meta {
            font-size: 10.5px;
            color: #64748b;
            font-weight: 600;
        }
        .movement-parties {
            font-size: 11px;
            color: #334155;
            margin-bottom: 6px;
            font-weight: 700;
        }
        .movement-parties span {
            color: #0f766e;
        }
        .movement-text {
            font-size: 11.5px;
            line-height: 1.7;
            color: #1e293b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            white-space: pre-wrap;
        }
        .signatures {
            margin-top: 36px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            text-align: center;
            position: relative;
            z-index: 1;
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
        <a href="{{ route('correspondence.memos.show', $memo->id) }}" class="btn-back">← العودة لتفاصيل المراسلة</a>
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
                <div class="header-title">المراسلات الإدارية الداخلية</div>
                <div class="header-sub">نموذج مذكرة وتوجيه رسمي</div>
                <div class="header-sub">{{ date('Y/m/d') }}</div>
            </div>
        </div>

        {{-- Memo meta bar --}}
        <div class="memo-info-bar">
            <div>
                <span style="font-size:11px; color:#0f766e; font-weight:700;">رقم المراسلة:</span>
                <span class="memo-num">{{ $memo->memo_number }}</span>
            </div>
            <div class="memo-date">
                تاريخ الإنشاء: {{ $memo->created_at->format('Y/m/d H:i') }}
            </div>
            <div>
                <span class="badge" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; margin-left:4px;">
                    {{ $memo->memo_type }}
                </span>
                <span class="badge" style="background:{{ $memo->status === 'تم الإنجاز' ? '#dcfce7; color:#15803d; border:1px solid #bbf7d0;' : '#fef3c7; color:#b45309; border:1px solid #fde68a;' }}">
                    {{ $memo->status }}
                </span>
            </div>
        </div>

        {{-- Section 1: Parties & Department Info --}}
        <div class="section-title">أولاً: بيانات المنشئ والمستلمين</div>
        <div class="grid-2">
            <div class="info-card">
                <div class="info-label">المرسل / المنشئ الأصلي</div>
                <div class="info-value">
                    {{ $memo->creator->name ?? 'غير محدد' }} 
                    <span style="font-size:10px; color:#64748b; font-weight:normal;">({{ $memo->creator->department->name ?? 'بلدية بيت حانون' }})</span>
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">المستلمون / الدائرة الموجهة إليها</div>
                <div class="info-value">
                    {{ $memo->recipients_names }}
                    @if($memo->targetDepartment)
                        <span style="font-size:10px; color:#64748b; font-weight:normal;">— {{ $memo->targetDepartment->name }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid-3">
            <div class="info-card">
                <div class="info-label">درجة الأهمية / الأولوية</div>
                <div class="info-value" style="color:{{ in_array($memo->priority, ['عاجلة', 'سري / عاجل جداً']) ? '#e11d48' : '#0f172a' }};">
                    {{ $memo->priority ?? 'عادية' }}
                </div>
            </div>
            <div class="info-card">
                <div class="info-label">تاريخ الاستحقاق والإنجاز</div>
                <div class="info-value">{{ $memo->due_date ? $memo->due_date->format('Y/m/d') : 'غير محدد' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">عدد الردود والتحويلات الإدارية</div>
                <div class="info-value">{{ count($groupedMovements) }} مرحلة</div>
            </div>
        </div>

        {{-- Section 2: Subject & Content --}}
        <div class="section-title">ثانياً: موضوع المراسلة وتفاصيلها</div>
        <div class="info-card" style="margin-bottom: 10px;">
            <div class="info-label">موضوع المراسلة</div>
            <div class="info-value" style="color:#0d9488; font-size:13px;">{{ $memo->subject }}</div>
        </div>

        <div class="details-box">
            {{ $memo->content }}
        </div>

        {{-- Section 3: Movements, Responses & Directives --}}
        <div class="section-title">ثالثاً: الردود والشروحات والتوجيهات الإدارية</div>
        @forelse($groupedMovements as $item)
            @php $movement = $item['movement']; @endphp
            <div class="movement-card">
                <div class="movement-header">
                    <span class="movement-action">
                        ● {{ $item['action_type'] }}
                    </span>
                    <span class="movement-meta">
                        {{ $movement->created_at->format('Y/m/d H:i') }}
                    </span>
                </div>

                <div class="movement-parties">
                    بواسطة: <span>{{ $item['fromUser']->name ?? 'غير محدد' }}</span>
                    @if(!empty($item['fromUser']->department))
                        ({{ $item['fromUser']->department->name }})
                    @endif
                    @if(!empty($item['recipients']))
                        ← إلى: 
                        @foreach($item['recipients'] as $rec)
                            <span>{{ $rec->name }}</span>{{ !$loop->last ? '، ' : '' }}
                        @endforeach
                    @endif
                </div>

                @if($item['directive_text'])
                    <div class="movement-text">
                        <strong>الشرح والتوجيه / الرد:</strong>
                        <br>
                        {{ $item['directive_text'] }}
                    </div>
                @endif
            </div>
        @empty
            <div class="info-card" style="text-align:center; color:#94a3b8; font-size:11px;">
                لا توجد ردود أو تحويلات إضافية مسجلة بعد.
            </div>
        @endforelse

        {{-- Section 4: Attachments summary if any --}}
        @if($memo->attachments && $memo->attachments->count() > 0)
            <div class="section-title">رابعاً: المرفقات والمستندات المساندة</div>
            <div class="info-card">
                <ul style="list-style:disc; margin-right:18px; font-size:11px; line-height:1.8;">
                    @foreach($memo->attachments as $att)
                        <li>
                            <strong>{{ $att->file_name }}</strong> 
                            <span style="color:#64748b;">({{ round($att->file_size / 1024, 1) }} KB)</span> 
                            — أدرج بواسطة: {{ $att->uploader->name ?? 'الموظف' }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Signatures Block --}}
        <div class="signatures">
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">منشئ المراسلة</div>
                <div class="signature-box">{{ $memo->creator->name ?? 'الموظف المختص' }}</div>
            </div>
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">رئيس الدائرة / المستلم</div>
                <div class="signature-box">التوقيع والخاتم</div>
            </div>
            <div>
                <div style="font-weight:700; margin-bottom:40px; font-size:11px;">رئيس البلدية / الاعتماد</div>
                <div class="signature-box">الاعتماد النهائي</div>
            </div>
        </div>
    </div>
</body>
</html>

