<x-buildings-layout>
    <div class="space-y-6">

        {{-- 1. Main Stats Cards (Damage Module Style) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Buildings --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">إجمالي المباني المسجلة</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <x-heroicon-s-building-office class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_buildings']) }}</span>
                    <span class="text-xs font-bold text-slate-400">مبنى ومنشأة</span>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg w-fit">
                    <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                    <span>تم التوثيق والمسح الميداني (GIS)</span>
                </div>
            </div>

            {{-- Card 2: Total Units & Real Estate Wealth --}}
            <div class="bg-white rounded-3xl p-5 border border-blue-200 shadow-xs relative overflow-hidden bg-gradient-to-br from-white to-blue-50/40">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-blue-950">إجمالي الوحدات العقارية</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-sm shadow-blue-600/30">
                        <x-heroicon-s-squares-plus class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-blue-700">{{ number_format($stats['total_units']) }}</span>
                    <span class="text-xs font-bold text-blue-900">وحدة عقارية</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-[11px] font-bold text-slate-500">
                    <span>إضافة طوابق:</span>
                    <span class="font-black text-blue-800">{{ number_format($stats['adding_new_floor']) }} مبنى قيد التوسعة</span>
                </div>
            </div>

            {{-- Card 3: Construction & Compliance Stages --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">حالة التراخيص والامتثال</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <x-heroicon-s-shield-check class="w-5 h-5" />
                    </div>
                </div>
                <div class="space-y-1.5 text-xs font-bold">
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> مرخص ومستوفٍ الرسوم
                        </span>
                        <span class="font-extrabold text-emerald-700">{{ number_format($stats['licensed_compliant']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-amber-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> غير مستوفٍ / متبقي طوابق
                        </span>
                        <span class="font-extrabold text-amber-700">{{ number_format($stats['licensed_non_compliant'] + $stats['licensed_pending_floor']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-rose-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> غير مرخص
                        </span>
                        <span class="font-extrabold text-rose-700">{{ number_format($stats['unlicensed']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 4: Building Licenses & Approvals --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">معاملات التراخيص الهندسية</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <x-heroicon-s-document-check class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total_licenses']) }}</span>
                    <span class="text-xs font-bold text-slate-400">معاملة ترخيص</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs font-bold pt-2 border-t border-slate-100">
                    <span class="text-emerald-700">معتمدة: {{ number_format($stats['approved_licenses']) }}</span>
                    <span class="text-amber-600">قيد الإجراء: {{ number_format($stats['pending_licenses']) }}</span>
                </div>
            </div>
        </div>

        {{-- 2. Building Types Performance Breakdown --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-black text-slate-900">التصنيف الإنشائي وتوزيع أنماط المباني</h2>
                <a href="{{ route('buildings.buildings.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <span>عرض كافة سجلات المباني</span>
                    <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($topBuildingTypes as $bt)
                    @php
                        $bPercent = $stats['total_buildings'] > 0 ? round(($bt->count / $stats['total_buildings']) * 100, 1) : 0;
                    @endphp
                    <a href="{{ route('buildings.buildings.index', ['building_type_id' => $bt->building_type_id]) }}"
                       class="bg-white rounded-3xl p-4 border border-slate-200/80 hover:border-blue-400 shadow-xs hover:shadow-md transition-all group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-black text-slate-800 group-hover:text-blue-600 transition-colors">
                                    {{ $bt->name }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $bPercent }}%
                                </span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 mb-1">
                                {{ number_format($bt->count) }}
                            </div>
                            <div class="text-xs font-bold text-blue-600">
                                مبنى ومنشأة
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-500">
                            <span>استعراض المباني</span>
                            <x-heroicon-s-arrow-left class="w-3.5 h-3.5 text-slate-400 group-hover:-translate-x-1 transition-transform" />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. Construction Compliance & Top Zones Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Construction Status Bars Card --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                        <x-heroicon-s-chart-pie class="w-5 h-5 text-blue-600" />
                        <span>مستويات الامتثال والتراخيص الإنشائية</span>
                    </h3>

                    @php
                        $statuses = [
                            [
                                'label' => 'مرخص ومستوفٍ الرسوم تماماً',
                                'count' => $stats['licensed_compliant'],
                                'color' => 'bg-emerald-500',
                                'filter' => 1
                            ],
                            [
                                'label' => 'مرخص وغير مستوفٍ الرسوم',
                                'count' => $stats['licensed_non_compliant'],
                                'color' => 'bg-amber-500',
                                'filter' => 2
                            ],
                            [
                                'label' => 'مرخص ومتبقي طوابق إضافية',
                                'count' => $stats['licensed_pending_floor'],
                                'color' => 'bg-orange-500',
                                'filter' => 3
                            ],
                            [
                                'label' => 'مباني غير مرخصة',
                                'count' => $stats['unlicensed'],
                                'color' => 'bg-rose-500',
                                'filter' => 0
                            ],
                        ];
                    @endphp

                    <div class="space-y-4">
                        @foreach($statuses as $st)
                            @php
                                $sPct = $stats['total_buildings'] > 0 ? round(($st['count'] / $stats['total_buildings']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                    <a href="{{ route('buildings.buildings.index', ['construction_status' => $st['filter']]) }}" class="text-slate-700 hover:text-blue-600 transition-colors">
                                        {{ $st['label'] }}
                                    </a>
                                    <span class="text-slate-900 font-extrabold">{{ number_format($st['count']) }} ({{ $sPct }}%)</span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $st['color'] }} rounded-full" style="width: {{ $sPct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 leading-relaxed font-medium">
                        <span class="font-bold text-slate-800">ملاحظة تنظيمية:</span> تخضع المباني غير المرخصة لإجراءات التسوية والتسجيل الميداني مع ربط ملفاتها بالأرشيف الإلكتروني المركزي وسجل التراخيص.
                    </div>

                    <div class="flex flex-wrap gap-2 pt-1">
                        <a href="{{ route('buildings.buildings.index', ['historical' => 1]) }}"
                           class="flex items-center gap-1.5 bg-purple-50 hover:bg-purple-100 border border-purple-100 rounded-xl px-3 py-1.5 text-xs font-bold text-purple-700 transition-all">
                            <x-heroicon-s-building-library class="w-3.5 h-3.5 text-purple-600" />
                            <span>مباني تاريخية:</span>
                            <span class="font-extrabold text-purple-900">{{ number_format($stats['historical']) }}</span>
                        </a>
                        <a href="{{ route('buildings.buildings.index', ['abandoned' => 1]) }}"
                           class="flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-xl px-3 py-1.5 text-xs font-bold text-gray-700 transition-all">
                            <x-heroicon-s-no-symbol class="w-3.5 h-3.5 text-gray-500" />
                            <span>مهجورة:</span>
                            <span class="font-extrabold text-gray-900">{{ number_format($stats['abandoned']) }}</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Top Zones Table --}}
            <div class="lg:col-span-2 bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <x-heroicon-s-map-pin class="w-5 h-5 text-blue-600" />
                        <span>توزيع المباني حسب الأحياء والمناطق السكنية</span>
                    </h3>
                    <a href="{{ route('buildings.buildings.index') }}" class="text-xs font-bold text-blue-600 flex items-center gap-1">
                        <span>عرض كافة الأحياء</span>
                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                <th class="pb-2.5">المنطقة / الحي</th>
                                <th class="pb-2.5">عدد المباني</th>
                                <th class="pb-2.5">نسبة التركز العمراني</th>
                                <th class="pb-2.5 text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($zoneStats as $z)
                                @php
                                    $zoneName = $zones[$z->zone_id] ?? ('حي رقم ' . $z->zone_id);
                                    $zPct = $stats['total_buildings'] > 0 ? round(($z->count / $stats['total_buildings']) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-bold text-slate-900 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <span>{{ $zoneName }}</span>
                                    </td>
                                    <td class="py-3 font-extrabold text-slate-800">{{ number_format($z->count) }} مبنى</td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 sm:w-36 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, $zPct * 2.5) }}%"></div>
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-600">{{ $zPct }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('buildings.buildings.index', ['zone_id' => $z->zone_id]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-colors">
                                            تصفية المباني
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">لا توجد بيانات متاحة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 4. Recent Buildings Table --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                    <x-heroicon-s-clock class="w-5 h-5 text-blue-600" />
                    <span>أحدث المباني والمنشآت المسجلة والمحدثة</span>
                </h3>
                <a href="{{ route('buildings.buildings.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <span>عرض السجل الكامل</span>
                    <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold">
                            <th class="pb-3">رقم المبنى / الملف</th>
                            <th class="pb-3">المالك الرئيسي</th>
                            <th class="pb-3">نوع المبنى</th>
                            <th class="pb-3">الموقع / الحوض والقطعة</th>
                            <th class="pb-3">الحالة الإنشائية والتنظيمية</th>
                            <th class="pb-3 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($recentBuildings as $bld)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-mono font-bold text-blue-700">
                                    <div>#{{ $bld->building_number ?: $bld->id }}</div>
                                    @if($bld->file_number)
                                        <div class="text-[11px] font-normal text-slate-400">ملف: {{ $bld->file_number }}</div>
                                    @endif
                                </td>
                                <td class="py-3 font-bold text-slate-900">
                                    {{ $bld->main_owner?->full_name ?? ($bld->building_name ?: 'غير محدد') }}
                                    @if($bld->owners->count() > 1)
                                        <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-normal mr-1">
                                            +{{ $bld->owners->count() - 1 }} شركاء
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                        {{ $bld->buildingType?->name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-600">
                                    @if($bld->zone)
                                        <div>{{ $bld->zone->zone_name }}</div>
                                    @endif
                                    @if($bld->street)
                                        <div class="text-[11px] text-slate-500">{{ $bld->street->street_name }}</div>
                                    @endif
                                    @if($bld->block_number || $bld->parcel_number)
                                        <div class="text-[10px] text-slate-400 font-mono">حوض: {{ $bld->block_number ?: '-' }} / قطعة: {{ $bld->parcel_number ?: '-' }}</div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($bld->construction_status === 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> مرخص ومستوفٍ
                                        </span>
                                    @elseif($bld->construction_status === 2)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> غير مستوفٍ الرسوم
                                        </span>
                                    @elseif($bld->construction_status === 3)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700 border border-orange-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> متبقي طوابق
                                        </span>
                                    @elseif($bld->construction_status === 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> غير مرخص
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            قيد التدقيق
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('buildings.buildings.show', $bld->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs border border-blue-200 transition-colors">
                                        <span>تفاصيل</span>
                                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">لا توجد مباني مسجلة حديثاً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Quick Actions Strip --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-2">
            <a href="{{ route('buildings.buildings.create') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-plus class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">إضافة مبنى</div>
                    <div class="text-[10px] text-slate-400">تسجيل عقار جديد</div>
                </div>
            </a>

            <a href="{{ route('buildings.units.create') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-squares-plus class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">إضافة وحدة</div>
                    <div class="text-[10px] text-slate-400">شقة أو محل أو مخزن</div>
                </div>
            </a>

            <a href="{{ route('buildings.licenses.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-document-check class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">سجل التراخيص</div>
                    <div class="text-[10px] text-slate-400">متابعة المعاملات</div>
                </div>
            </a>

            <a href="{{ route('buildings.streets.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-map-pin class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">دليل الشوارع</div>
                    <div class="text-[10px] text-slate-400">المحاور والأحياء</div>
                </div>
            </a>

            <a href="{{ route('buildings.units.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-table-cells class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">سجل الوحدات</div>
                    <div class="text-[10px] text-slate-400">جدول الوحدات الشامل</div>
                </div>
            </a>

            <a href="{{ route('archive.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-blue-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-archive-box class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">الأرشيف المركزي</div>
                    <div class="text-[10px] text-slate-400">الملفات الهندسية</div>
                </div>
            </a>
        </div>

    </div>
</x-buildings-layout>
