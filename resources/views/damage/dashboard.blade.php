<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    {{-- 1. Main Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Card 1: Total Records --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500">إجمالي السجلات المحصورة</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <x-heroicon-s-clipboard-document-list class="w-5 h-5" />
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ number_format($totalRecords) }}</span>
                <span class="text-xs font-bold text-slate-400">سجل ومنشأة</span>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg w-fit">
                <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                <span>تم التأسيس الشامل (Baseline)</span>
            </div>
        </div>

        {{-- Card 2: Total Recovery Cost USD --}}
        <div class="bg-white rounded-3xl p-5 border border-orange-200 shadow-xs relative overflow-hidden bg-gradient-to-br from-white to-orange-50/30">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-orange-950">تقدير تكلفة التعافي الإجمالية ($)</span>
                <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-sm shadow-orange-500/30">
                    <x-heroicon-s-currency-dollar class="w-5 h-5" />
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-orange-600">${{ number_format($totalCostUsd, 0) }}</span>
                <span class="text-xs font-bold text-orange-950">USD</span>
            </div>
            <div class="mt-3 text-[11px] font-bold text-slate-500">
                ما يعادل: <span class="font-black text-slate-800">₪{{ number_format($totalCostIls, 0) }} شيكل</span>
            </div>
        </div>

        {{-- Card 3: Workflow Stages --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500">دورة الاعتماد الهندسي</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <x-heroicon-s-shield-check class="w-5 h-5" />
                </div>
            </div>
            <div class="space-y-1.5 text-xs font-bold">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span> إدخال أولي (Draft)
                    </span>
                    <span class="font-extrabold text-slate-800">{{ number_format($totalDraft) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-amber-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> قيد المراجعة
                    </span>
                    <span class="font-extrabold text-amber-700">{{ number_format($totalReview) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-emerald-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> اعتماد نهائي
                    </span>
                    <span class="font-extrabold text-emerald-700">{{ number_format($totalApproved) }}</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Criteria & Integration --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500">معايير التسعير والمانحين</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <x-heroicon-s-adjustments-horizontal class="w-5 h-5" />
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ $criteriaCount }}</span>
                <span class="text-xs font-bold text-slate-400">معيار تسعير نشط</span>
            </div>
            <div class="mt-3 flex items-center justify-between text-xs font-bold pt-2 border-t border-slate-100">
                <span class="text-slate-500">هيئات مانحة موثقة:</span>
                <span class="text-orange-600 font-extrabold">{{ $donorsCount }} هيئة</span>
            </div>
        </div>
    </div>

    {{-- 2. Sectoral Performance Breakdown --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-base font-black text-slate-900">التصنيف القطاعي وتوزيع التكاليف</h2>
            <a href="{{ route('damage.records.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">
                عرض كافة السجلات القطاعية &larr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($sectors as $key => $s)
                @php
                    $stat = $sectorStats->get($key);
                    $count = $stat ? $stat->count : 0;
                    $usd = $stat ? (float)$stat->total_usd : 0;
                    $percent = $totalRecords > 0 ? round(($count / $totalRecords) * 100, 1) : 0;
                @endphp
                <a href="{{ route('damage.records.index', ['sector' => $key]) }}"
                   class="bg-white rounded-3xl p-4 border border-slate-200/80 hover:border-orange-400 shadow-xs hover:shadow-md transition-all group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-black text-slate-800 group-hover:text-orange-600 transition-colors">
                                {{ $s['name_ar'] }}
                            </span>
                            <span class="text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $percent }}%
                            </span>
                        </div>
                        <div class="text-2xl font-black text-slate-900 mb-1">
                            {{ number_format($count) }}
                        </div>
                        <div class="text-xs font-bold text-orange-600">
                            ${{ number_format($usd, 0) }}
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-500">
                        <span>استعراض السجلات</span>
                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5 text-slate-400 group-hover:-translate-x-1 transition-transform" />
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- 3. Damage Severity & Top Zones --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- Damage Levels Card --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                <x-heroicon-s-chart-pie class="w-5 h-5 text-orange-500" />
                <span>مستويات ودرجات التدمير</span>
            </h3>

            @php
                $levels = [
                    'total_destruction' => ['name' => 'مهدم كلياً (تدمير شامل)', 'color' => 'bg-rose-500', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'],
                    'severe_partial' => ['name' => 'ضرر جزئي بليغ (غير صالح للسكن)', 'color' => 'bg-orange-500', 'badge' => 'bg-orange-50 text-orange-700 border-orange-200'],
                    'moderate_partial' => ['name' => 'ضرر جزئي متوسط (قابل للترميم)', 'color' => 'bg-amber-500', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'],
                    'minor' => ['name' => 'ضرر جزئي طفيف', 'color' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                ];
            @endphp

            <div class="space-y-4">
                @foreach($levels as $lKey => $lvl)
                    @php
                        $cnt = $damageLevels[$lKey] ?? 0;
                        $pct = $totalRecords > 0 ? round(($cnt / $totalRecords) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-700">{{ $lvl['name'] }}</span>
                            <span class="text-slate-900 font-extrabold">{{ number_format($cnt) }} ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $lvl['color'] }} rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 leading-relaxed font-medium">
                <span class="font-bold text-slate-800">ملاحظة هندسية:</span> تم تأسيس كود التدمير الشامل استناداً للواقع الميداني، مع إمكانية التعديل عند توفر تقارير لجان الحصر الميداني.
            </div>
        </div>

        {{-- Top Affected Zones --}}
        <div class="lg:col-span-2 bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                    <x-heroicon-s-map-pin class="w-5 h-5 text-orange-500" />
                    <span>توزيع الأضرار حسب الأحياء والمناطق السكنية</span>
                </h3>
                <a href="{{ route('damage.portal.index') }}" target="_blank" class="text-xs font-bold text-orange-600 flex items-center gap-1">
                    <span>فتح الخريطة التفاعلية</span>
                    <x-heroicon-s-arrow-top-right-on-square class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold">
                            <th class="pb-2.5">المنطقة / الحي</th>
                            <th class="pb-2.5">عدد المنشآت المتضررة</th>
                            <th class="pb-2.5">التكلفة التقديرية ($)</th>
                            <th class="pb-2.5">نسبة الضرر من الإجمالي</th>
                            <th class="pb-2.5 text-center">إجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($zoneStats as $z)
                            @php
                                $zoneName = $zones[$z->zone_id] ?? ('حي رقم ' . $z->zone_id);
                                $zPct = $totalRecords > 0 ? round(($z->count / $totalRecords) * 100, 1) : 0;
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 font-bold text-slate-900">{{ $zoneName }}</td>
                                <td class="py-2.5 font-extrabold text-slate-800">{{ number_format($z->count) }}</td>
                                <td class="py-2.5 font-black text-orange-600">${{ number_format($z->total_usd, 0) }}</td>
                                <td class="py-2.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-orange-500 rounded-full" style="width: {{ min(100, $zPct * 2) }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-600">{{ $zPct }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 text-center">
                                    <a href="{{ route('damage.records.index', ['zone_id' => $z->zone_id]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px]">
                                        تصفية
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-400">لا توجد بيانات متاحة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 4. Recent Damage Records Table --}}
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                <x-heroicon-s-clock class="w-5 h-5 text-orange-500" />
                <span>أحدث سجلات حصر الأضرار المسجلة والمحدثة</span>
            </h3>
            <a href="{{ route('damage.records.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">
                عرض السجل الكامل &larr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                        <th class="pb-3">رقم السجل</th>
                        <th class="pb-3">اسم المنشأة / العقار</th>
                        <th class="pb-3">القطاع</th>
                        <th class="pb-3">الموقع / الحوض والقطعة</th>
                        <th class="pb-3">مستوى الضرر</th>
                        <th class="pb-3">التكلفة التقديرية</th>
                        <th class="pb-3">حالة الاعتماد</th>
                        <th class="pb-3 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentRecords as $r)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 font-mono font-bold text-orange-600">
                                {{ $r->record_number }}
                            </td>
                            <td class="py-3 font-bold text-slate-900">
                                {{ $r->name }}
                                @if($r->citizen_name)
                                    <div class="text-[11px] font-normal text-slate-500">مالك: {{ $r->citizen_name }}</div>
                                @endif
                            </td>
                            <td class="py-3">
                                @php
                                    $sectorLabels = [
                                        'real_estate' => 'مباني وعقارات',
                                        'infrastructure' => 'بنية تحتية',
                                        'public_facilities' => 'مرافق عامة',
                                        'economic_crafts' => 'حرف وصناعات',
                                        'municipal_assets' => 'أصول بلدية',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                    {{ $sectorLabels[$r->sector] ?? $r->sector }}
                                </span>
                            </td>
                            <td class="py-3 text-slate-600">
                                @if($r->zone)
                                    <div>{{ $r->zone->zone_name }}</div>
                                @endif
                                @if($r->block_number || $r->parcel_number)
                                    <div class="text-[11px] text-slate-400 font-mono">حوض: {{ $r->block_number ?: '-' }} / قطعة: {{ $r->parcel_number ?: '-' }}</div>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($r->damage_level === 'total_destruction')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">مهدم كلياً</span>
                                @elseif($r->damage_level === 'severe_partial')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-orange-50 text-orange-700 border border-orange-200">جزئي بليغ</span>
                                @elseif($r->damage_level === 'moderate_partial')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200">جزئي متوسط</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">طفيف</span>
                                @endif
                            </td>
                            <td class="py-3 font-black text-slate-900">
                                ${{ number_format($r->estimated_cost_usd, 0) }}
                                <div class="text-[10px] font-medium text-slate-400">₪{{ number_format($r->estimated_cost_ils, 0) }}</div>
                            </td>
                            <td class="py-3">
                                @if($r->workflow_status === 'final_approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> معتمد نهائياً
                                    </span>
                                @elseif($r->workflow_status === 'engineering_review')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> قيد المراجعة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        مسودة أولية
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('damage.records.show', $r->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold text-xs border border-orange-200 transition-colors">
                                    <span>تفاصيل</span>
                                    <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">لا توجد سجلات مسجلة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
