<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التقرير الإحصائي الشامل لحصر الأضرار • بلدية بيت حانون</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; font-size: 11pt !important; }
            .print-border { border: 1px solid #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased p-4 sm:p-8">

    {{-- Print Action Bar --}}
    <div class="max-w-5xl mx-auto mb-6 no-print flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('damage.portal.index') }}" class="text-xs font-bold text-slate-600 hover:text-teal-700 transition-colors flex items-center gap-1">
            &rarr; العودة للبوابة الإلكترونية
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white font-bold text-xs shadow-md shadow-teal-700/20 transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/></svg>
                <span>طباعة التقرير / حفظ PDF</span>
            </button>
        </div>
    </div>

    {{-- Report Paper Container --}}
    <div class="max-w-5xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-slate-200 space-y-8">

        {{-- Official Municipal Bilingual Header --}}
        <div class="flex items-center justify-between pb-6 border-b-2 border-slate-900">
            <div class="text-right">
                <div class="text-xs font-black text-slate-500">دولة فلسطين</div>
                <div class="text-lg font-black text-slate-900">بلدية بيت حانون</div>
                <div class="text-xs font-bold text-teal-700">الدائرة الهندسية والتنظيم</div>
                <div class="text-[11px] text-slate-400 mt-1">منظومة حصر الأضرار وتقييم الاحتياجات</div>
            </div>

            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون" class="h-20 w-auto mx-auto mb-1">
            </div>

            <div class="text-left font-sans">
                <div class="text-xs font-black text-slate-500">State of Palestine</div>
                <div class="text-lg font-black text-slate-900">Beit Hanoun Municipality</div>
                <div class="text-xs font-bold text-teal-700">Engineering & Urban Planning</div>
                <div class="text-[11px] text-slate-400 mt-1">Damage Assessment & Needs System (DAMS)</div>
            </div>
        </div>

        {{-- Report Title --}}
        <div class="text-center py-2 bg-slate-50 rounded-2xl border border-slate-200">
            <h1 class="text-xl font-black text-slate-900">تقرير الموقف الشامل: حصر أضرار العدوان والاحتياجات التقديرية لإعادة الإعمار</h1>
            <p class="text-xs font-bold text-slate-500 mt-0.5">Comprehensive Damage Assessment & Reconstruction Needs Report • Beit Hanoun City</p>
            <div class="text-[11px] font-bold text-teal-700 mt-1">تاريخ الإصدار: {{ date('Y-m-d') }}</div>
        </div>

        {{-- Executive KPI Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-slate-500 font-bold">إجمالي المنشآت المحصورة</div>
                <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ number_format($totalRecords) }}</div>
                <div class="text-[10px] text-slate-400">Total Recorded Units</div>
            </div>

            <div class="p-4 rounded-2xl bg-teal-50 border border-teal-200">
                <div class="text-xs text-teal-900 font-bold">إجمالي الاحتياج التقديري ($)</div>
                <div class="text-2xl font-black text-teal-800 mt-1 font-mono">${{ number_format($totalCostUsd / 1000000, 1) }}M</div>
                <div class="text-[10px] text-teal-700">Total Needs (USD)</div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs text-slate-500 font-bold">التقدير المعادل بالشيكل (₪)</div>
                <div class="text-2xl font-black text-slate-900 mt-1 font-mono">₪{{ number_format($totalCostIls / 1000000, 1) }}M</div>
                <div class="text-[10px] text-slate-400">Total Equivalent (ILS)</div>
            </div>

            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200">
                <div class="text-xs text-rose-900 font-bold">نسبة التدمير الكلي المبدئي</div>
                <div class="text-2xl font-black text-rose-600 mt-1 font-mono">100%</div>
                <div class="text-[10px] text-rose-800">Baseline Total Destruction</div>
            </div>
        </div>

        {{-- Sector Breakdown Table --}}
        <div>
            <h3 class="text-sm font-black text-slate-900 mb-3 flex items-center justify-between">
                <span>التوزيع القطاعي للأضرار والتقديرات المالية</span>
                <span class="text-xs font-normal text-slate-500 font-sans">Sectoral Distribution & Financial Estimates</span>
            </h3>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-3">القطاع / Sector</th>
                            <th class="p-3">عدد السجلات</th>
                            <th class="p-3">التكلفة التقديرية بالدولار ($)</th>
                            <th class="p-3">التكلفة بالشيكل (₪)</th>
                            <th class="p-3">النسبة المئوية (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @php
                            $sectorArEn = [
                                'real_estate' => ['ar' => 'المباني والعقارات السكنية', 'en' => 'Residential & Real Estate'],
                                'infrastructure' => ['ar' => 'البنية التحتية والشبكات والطرق', 'en' => 'Infrastructure & Networks'],
                                'public_facilities' => ['ar' => 'المرافق العامة والحيوية', 'en' => 'Public Facilities & Services'],
                                'economic_crafts' => ['ar' => 'القطاع الاقتصادي والحرفي', 'en' => 'Economic & Commercial'],
                                'municipal_assets' => ['ar' => 'ممتلكات وأصول البلدية', 'en' => 'Municipal Assets'],
                            ];
                        @endphp
                        @foreach($sectorStats as $stat)
                            @php
                                $sInfo = $sectorArEn[$stat->sector] ?? ['ar' => $stat->sector, 'en' => $stat->sector];
                                $pct = $totalCostUsd > 0 ? round(($stat->total_usd / $totalCostUsd) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="p-3 font-bold text-slate-900">
                                    {{ $sInfo['ar'] }}
                                    <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $sInfo['en'] }}</div>
                                </td>
                                <td class="p-3 font-bold text-slate-800">{{ number_format($stat->count) }}</td>
                                <td class="p-3 font-black text-teal-800 font-mono">${{ number_format($stat->total_usd, 0) }}</td>
                                <td class="p-3 font-bold text-slate-700">₪{{ number_format($stat->total_ils, 0) }}</td>
                                <td class="p-3 font-extrabold text-slate-900">{{ $pct }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 font-black border-t-2 border-slate-300">
                        <tr>
                            <td class="p-3 text-slate-900">الإجمالي العام / TOTAL</td>
                            <td class="p-3 text-slate-900">{{ number_format($totalRecords) }}</td>
                            <td class="p-3 text-teal-800 font-mono">${{ number_format($totalCostUsd, 0) }}</td>
                            <td class="p-3 text-slate-900">₪{{ number_format($totalCostIls, 0) }}</td>
                            <td class="p-3 text-slate-900">100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Notes & Signatures --}}
        <div class="pt-8 border-t border-slate-200">
            <div class="text-xs text-slate-600 leading-relaxed font-medium mb-12">
                <span class="font-bold text-slate-800">ملاحظة اعتماد:</span> هذه البيانات تمثل مخرجات منظومة حصر الأضرار وتقييم الاحتياجات الرسمية لبلدية بيت حانون، وتُعد وثيقة مرجعية للجهات المانحة والمؤسسات الدولية لتنسيق مشاريع التعافي وإعادة الإعمار.
            </div>

            <div class="grid grid-cols-3 gap-6 text-center text-xs">
                <div>
                    <div class="font-bold text-slate-800">مهندس الحصر والتقييم</div>
                    <div class="h-16"></div>
                    <div class="text-slate-400 text-[11px]">التوقيع والختم</div>
                </div>
                <div>
                    <div class="font-bold text-slate-800">مدير الدائرة الهندسية</div>
                    <div class="h-16"></div>
                    <div class="text-slate-400 text-[11px]">التوقيع والختم</div>
                </div>
                <div>
                    <div class="font-bold text-slate-800">رئيس بلدية بيت حانون</div>
                    <div class="h-16"></div>
                    <div class="text-slate-400 text-[11px]">الختم الرسمي للبلدية</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
