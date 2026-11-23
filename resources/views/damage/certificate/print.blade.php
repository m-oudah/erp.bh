<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إفادة حصر وتقييم أضرار رسمية • {{ $record->record_number }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Cairo', sans-serif; background-color: #f1f5f9; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .cert-paper { border: 2px solid #0f172a !important; box-shadow: none !important; margin: 0 !important; max-width: 100% !important; border-radius: 0 !important; padding: 1.5rem !important; }
            @page { size: A4 portrait; margin: 1cm; }
        }
    </style>
</head>
<body class="p-4 sm:p-8">

    {{-- Top Action Toolbar (No Print) --}}
    <div class="max-w-4xl mx-auto mb-6 no-print flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('damage.records.show', $record->id) }}" class="text-xs font-bold text-slate-600 hover:text-orange-600 flex items-center gap-1">
            &rarr; العودة لتفاصيل السجل
        </a>
        <button onclick="window.print()" class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white font-bold text-xs shadow-md shadow-orange-600/20 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/></svg>
            <span>طباعة الإفادة الرسمية / حفظ كملف PDF</span>
        </button>
    </div>

    {{-- Certificate Container (A4) --}}
    <div class="cert-paper max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border-4 border-slate-900 relative overflow-hidden">

        {{-- Watermark Seal --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
            <img src="{{ asset('logo.png') }}" class="w-[500px] h-[500px]">
        </div>

        {{-- Header --}}
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900 mb-6">
            <div class="text-right">
                <div class="text-xs font-black text-slate-600">دولة فلسطين</div>
                <div class="text-xl font-black text-slate-900">بلدية بيت حانون</div>
                <div class="text-xs font-bold text-orange-600">الدائرة الهندسية والتنظيم</div>
                <div class="text-[11px] text-slate-500 mt-1">منظومة حصر الأضرار الإلكترونية</div>
            </div>

            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون" class="h-20 w-auto mx-auto mb-1">
            </div>

            <div class="text-left font-sans">
                <div class="text-xs font-black text-slate-600">State of Palestine</div>
                <div class="text-xl font-black text-slate-900">Beit Hanoun Municipality</div>
                <div class="text-xs font-bold text-orange-600">Engineering & Urban Planning</div>
                <div class="text-[11px] text-slate-500 mt-1">Electronic Damage Certificate</div>
            </div>
        </div>

        {{-- Certificate Title --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-wide">إفادة حصر وتقييم أضرار رسمية</h1>
            <p class="text-xs font-bold text-slate-500 mt-1">Official Electronic Damage Assessment Certificate</p>
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-mono font-bold border border-slate-200">
                <span>رقم الوثيقة:</span>
                <span class="text-orange-600">{{ $record->record_number }}</span>
            </div>
        </div>

        {{-- Body Intro --}}
        <div class="text-xs text-slate-700 leading-relaxed font-medium mb-6 text-justify">
            تفيد بلدية بيت حانون - الدائرة الهندسية والتنظيم، وبناءً على الكشوفات الميدانية وسجلات منظومة حصر الأضرار وتقييم الاحتياجات المعتمدة، بأن العقار / المنشأة الموضحة بياناتها أدناه قد تعرضت للتدمير والضرر جراء العدوان، وفق التفاصيل الهندسية والفنية التالية:
        </div>

        {{-- Data Grid --}}
        <div class="grid grid-cols-2 gap-4 text-xs mb-6">
            {{-- Citizen / Beneficiary --}}
            <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 space-y-2">
                <div class="font-black text-slate-900 border-b border-slate-200 pb-1.5 text-sm">بيانات المالك / المستفيد</div>
                <div class="flex justify-between">
                    <span class="text-slate-500">اسم المالك:</span>
                    <span class="font-bold text-slate-900">{{ $record->citizen_name ?: 'غير مسجل' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">رقم بطاقة الهوية:</span>
                    <span class="font-bold text-slate-900 font-mono">{{ $record->citizen_national_id ?: 'غير مسجل' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">رقم الهاتف:</span>
                    <span class="font-bold text-slate-900 font-mono">{{ $record->citizen_phone ?: '-' }}</span>
                </div>
            </div>

            {{-- Cadastral --}}
            <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 space-y-2">
                <div class="font-black text-slate-900 border-b border-slate-200 pb-1.5 text-sm">الموقع والتنظيم العقاري</div>
                <div class="flex justify-between">
                    <span class="text-slate-500">الحي / المنطقة:</span>
                    <span class="font-bold text-slate-900">{{ $record->zone ? $record->zone->zone_name : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">الشارع:</span>
                    <span class="font-bold text-slate-900">{{ $record->street ? $record->street->street_name : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">رقم الحوض والقطعة:</span>
                    <span class="font-bold text-slate-900 font-mono">حوض: {{ $record->block_number ?: '-' }} / قطعة: {{ $record->parcel_number ?: '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Damage & Engineering Specs Table --}}
        <div class="border border-slate-200 rounded-2xl overflow-hidden mb-6 text-xs">
            <table class="w-full text-right">
                <thead class="bg-slate-900 text-white font-bold">
                    <tr>
                        <th class="p-3">مستوى التدمير</th>
                        <th class="p-3">المساحة المقدرة</th>
                        <th class="p-3">عدد الطوابق</th>
                        <th class="p-3">عدد الوحدات</th>
                        <th class="p-3">طبيعة الهيكل الإنشائي</th>
                        <th class="p-3">التقدير المالي ($)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 font-medium bg-slate-50/50">
                    <tr>
                        <td class="p-3 font-black text-rose-600">
                            {{ $record->damage_level === 'total_destruction' ? 'مهدم كلياً (تدمير شامل)' : 'ضرر جزئي بليغ' }}
                        </td>
                        <td class="p-3 font-bold text-slate-800">
                            {{ $record->sector === 'infrastructure' ? (number_format($record->length_meters, 0) . ' م.ط') : (number_format($record->area_sqm, 0) . ' م²') }}
                        </td>
                        <td class="p-3 font-bold text-slate-800">{{ $record->floors_count ?: 1 }}</td>
                        <td class="p-3 font-bold text-slate-800">{{ $record->units_count ?: 1 }}</td>
                        <td class="p-3 font-bold text-slate-800">
                            {{ $record->structure_type === 'asbestos' ? 'سقف أسبست' : ($record->structure_type === 'steel_hanger' ? 'هنغار معدني' : 'باطون مسلح') }}
                        </td>
                        <td class="p-3 font-black text-orange-600">
                            ${{ number_format($record->estimated_cost_usd, 0) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- QR Code Verification Card & Security Stamp --}}
        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 bg-slate-50 flex items-center justify-between gap-6 mb-8">
            <div class="space-y-1 text-xs">
                <div class="font-black text-slate-900 text-sm flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>وثيقة رسمية مؤمنة إلكترونياً</span>
                </div>
                <p class="text-slate-600 leading-relaxed font-medium">
                    تم إصدار هذه الإفادة وتوقيعها رقمياً برمز فريد عبر منظومة حصر الأضرار وتقييم الاحتياجات المركزية لبلدية بيت حانون.
                </p>
                <div class="text-[10px] text-slate-400 font-mono">
                    UUID: {{ $record->certificate_uuid }}
                </div>
                <div class="text-[10px] font-bold text-orange-600">
                    امسح رمز الـ QR للتحقق الفوري من صحة هذه الإفادة عبر بوابة البلدية الرسمية
                </div>
            </div>

            <div class="shrink-0 text-center">
                <img src="{{ $qrUrl }}" alt="QR Code" class="w-28 h-28 p-1 bg-white border border-slate-300 rounded-xl shadow-xs mx-auto">
            </div>
        </div>

        {{-- Official Signatures --}}
        <div class="grid grid-cols-3 gap-6 text-center text-xs pt-4 border-t-2 border-slate-900">
            <div>
                <div class="font-bold text-slate-800">مهندس الحصر والتدقيق</div>
                <div class="h-14"></div>
                <div class="text-slate-400 text-[10px]">التوقيع</div>
            </div>
            <div>
                <div class="font-bold text-slate-800">مدير الدائرة الهندسية</div>
                <div class="h-14"></div>
                <div class="text-slate-400 text-[10px]">التوقيع والاعتماد</div>
            </div>
            <div>
                <div class="font-bold text-slate-800">رئيس بلدية بيت حانون</div>
                <div class="h-14"></div>
                <div class="text-slate-400 text-[10px]">الختم الرسمي للبلدية</div>
            </div>
        </div>

    </div>

</body>
</html>
