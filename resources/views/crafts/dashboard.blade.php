<x-crafts-layout>
    <div class="space-y-6">

        {{-- 1. Main Stats Cards (Damage Module Style) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Card 1: Total Economical Activities --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">إجمالي الأنشطة الاقتصادية</span>
                    <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <x-heroicon-s-building-storefront class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($stats['total']) }}</span>
                    <span class="text-xs font-bold text-slate-400">منشأة ومحل</span>
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg w-fit">
                    <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                    <span>تم التوثيق والمسح الميداني الشامل</span>
                </div>
            </div>

            {{-- Card 2: Total Crafts Directory --}}
            <div class="bg-white rounded-3xl p-5 border border-orange-200 shadow-xs relative overflow-hidden bg-gradient-to-br from-white to-orange-50/40">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-orange-950">دليل الحرف والمهن المعتمدة</span>
                    <div class="w-10 h-10 rounded-xl bg-orange-600 text-white flex items-center justify-center shadow-sm shadow-orange-600/30">
                        <x-heroicon-s-wrench-screwdriver class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-orange-700">{{ number_format($totalCrafts) }}</span>
                    <span class="text-xs font-bold text-orange-950">حرفة ومهنة</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-[11px] font-bold text-slate-500">
                    <span>أنشطة تحت الرقابة:</span>
                    <span class="font-black text-rose-700">{{ number_format($stats['dangerous']) }} منشأة خطرة</span>
                </div>
            </div>

            {{-- Card 3: Operational Status Stages --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">حالة النشاط التشغيلي</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <x-heroicon-s-arrow-path class="w-5 h-5" />
                    </div>
                </div>
                <div class="space-y-1.5 text-xs font-bold">
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> أنشطة نشطة ومستمرة
                        </span>
                        <span class="font-extrabold text-emerald-700">{{ number_format($stats['active']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> أنشطة مغلقة أو متوقفة
                        </span>
                        <span class="font-extrabold text-slate-700">{{ number_format($stats['closed']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-amber-600 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> غير مصنفة / جديدة
                        </span>
                        <span class="font-extrabold text-amber-700">{{ number_format($stats['new_unapproved']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Card 4: Licensing & Safety --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-500">رخص المهن والحرف</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <x-heroicon-s-shield-check class="w-5 h-5" />
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-slate-900">{{ number_format($stats['licensed']) }}</span>
                    <span class="text-xs font-bold text-slate-400">منشأة مرخصة</span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs font-bold pt-2 border-t border-slate-100">
                    <span class="text-rose-600">غير مرخصة: {{ number_format($stats['unlicensed']) }}</span>
                    <span class="text-emerald-700">أنشطة آمنة: {{ number_format($stats['safe']) }}</span>
                </div>
            </div>
        </div>

        {{-- 2. Economic Sectors Breakdown --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-black text-slate-900">التصنيف القطاعي وتوزيع الأنشطة الاقتصادية</h2>
                <a href="{{ route('crafts.economical.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>عرض كافة سجلات الحرف</span>
                    <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($topSectors as $s)
                    @php
                        $sPercent = $stats['total'] > 0 ? round(($s->count / $stats['total']) * 100, 1) : 0;
                    @endphp
                    <a href="{{ route('crafts.economical.index', ['sector' => $s->job_sector_id]) }}"
                       class="bg-white rounded-3xl p-4 border border-slate-200/80 hover:border-orange-400 shadow-xs hover:shadow-md transition-all group flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-black text-slate-800 group-hover:text-orange-600 transition-colors">
                                    {{ $s->name }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $sPercent }}%
                                </span>
                            </div>
                            <div class="text-2xl font-black text-slate-900 mb-1">
                                {{ number_format($s->count) }}
                            </div>
                            <div class="text-xs font-bold text-orange-600">
                                {{ $s->subname ?: 'منشأة ومحل' }}
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-slate-500">
                            <span>استعراض الأنشطة</span>
                            <x-heroicon-s-arrow-left class="w-3.5 h-3.5 text-slate-400 group-hover:-translate-x-1 transition-transform" />
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- 3. Safety Compliance & Top Sectors Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Safety & Danger Classification Card --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                        <x-heroicon-s-chart-pie class="w-5 h-5 text-orange-600" />
                        <span>تصنيف السلامة والخطورة المهنية</span>
                    </h3>

                    @php
                        $safetyCats = [
                            [
                                'label' => 'أنشطة آمنة ومستوفية الشروط',
                                'count' => $stats['safe'],
                                'color' => 'bg-emerald-500',
                            ],
                            [
                                'label' => 'أنشطة خطرة (تتطلب شروط خاصة)',
                                'count' => $stats['dangerous'],
                                'color' => 'bg-rose-500',
                            ],
                            [
                                'label' => 'أنشطة قيد التدقيق والسلامة',
                                'count' => max(0, $stats['total'] - $stats['safe'] - $stats['dangerous']),
                                'color' => 'bg-amber-500',
                            ],
                        ];
                    @endphp

                    <div class="space-y-4">
                        @foreach($safetyCats as $sc)
                            @php
                                $scPct = $stats['total'] > 0 ? round(($sc['count'] / $stats['total']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                    <span class="text-slate-700">{{ $sc['label'] }}</span>
                                    <span class="text-slate-900 font-extrabold">{{ number_format($sc['count']) }} ({{ $scPct }}%)</span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $sc['color'] }} rounded-full" style="width: {{ $scPct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-600 leading-relaxed font-medium">
                        <span class="font-bold text-slate-800">ملاحظة رقابية:</span> تخضع المنشآت المصنفة خطرة لإجراءات التفتيش والوقاية المهنية بالتعاون مع الدفاع المدني لضمان سلامة المواطنين والممتلكات.
                    </div>

                    <div class="flex flex-wrap gap-2 pt-1">
                        <div class="flex items-center gap-1.5 bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-1.5 text-xs font-bold text-emerald-700">
                            <x-heroicon-s-check-badge class="w-3.5 h-3.5 text-emerald-600" />
                            <span>أنشطة مرخصة:</span>
                            <span class="font-extrabold text-emerald-900">{{ number_format($stats['licensed']) }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-red-50 border border-red-100 rounded-xl px-3 py-1.5 text-xs font-bold text-red-700">
                            <x-heroicon-s-x-circle class="w-3.5 h-3.5 text-red-500" />
                            <span>غير مرخصة:</span>
                            <span class="font-extrabold text-red-900">{{ number_format($stats['unlicensed']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Sectors Table --}}
            <div class="lg:col-span-2 bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                        <x-heroicon-s-building-office class="w-5 h-5 text-orange-600" />
                        <span>توزيع الأنشطة حسب القطاعات الاقتصادية الرئيسية</span>
                    </h3>
                    <a href="{{ route('crafts.economical.index') }}" class="text-xs font-bold text-orange-600 flex items-center gap-1">
                        <span>عرض كافة الأنشطة</span>
                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                <th class="pb-2.5">القطاع الاقتصادي</th>
                                <th class="pb-2.5">المسمى المهني / النشاط</th>
                                <th class="pb-2.5">عدد المنشآت</th>
                                <th class="pb-2.5">نسبة التركز</th>
                                <th class="pb-2.5 text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($sectorStats as $s)
                                @php
                                    $sPct = $stats['total'] > 0 ? round(($s->count / $stats['total']) * 100, 1) : 0;
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-bold text-slate-900 flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                        <span>{{ $s->sector_title }}</span>
                                    </td>
                                    <td class="py-3 text-slate-700 font-semibold">{{ $s->activity_name }}</td>
                                    <td class="py-3 font-extrabold text-slate-800">{{ number_format($s->count) }} منشأة</td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 sm:w-32 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-orange-500 rounded-full" style="width: {{ min(100, $sPct * 2) }}%"></div>
                                            </div>
                                            <span class="text-[11px] font-bold text-slate-600">{{ $sPct }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <a href="{{ route('crafts.economical.index', ['sector' => $s->job_sector_id]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition-colors">
                                            تصفية الأنشطة
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

        {{-- 4. Recent Economical Activities Table --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-900 flex items-center gap-2">
                    <x-heroicon-s-clock class="w-5 h-5 text-orange-600" />
                    <span>أحدث المنشآت والأنشطة الاقتصادية المسجلة والمحدثة</span>
                </h3>
                <a href="{{ route('crafts.economical.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                    <span>عرض السجل الكامل</span>
                    <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold">
                            <th class="pb-3">الاسم التجاري / المهني</th>
                            <th class="pb-3">المالك / المفوض</th>
                            <th class="pb-3">القطاع والنشاط</th>
                            <th class="pb-3">الموقع / المبنى</th>
                            <th class="pb-3">حالة الترخيص</th>
                            <th class="pb-3">الحالة التشغيلية</th>
                            <th class="pb-3 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($recentEconomical as $eco)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 font-bold text-slate-900">
                                    <div class="text-orange-700 font-bold">{{ $eco->job_formal_name ?: ($eco->trade_name ?: 'نشاط تجاري') }}</div>
                                    @if($eco->craft_number)
                                        <div class="text-[10px] font-mono text-slate-400">رقم الحرفة: {{ $eco->craft_number }}</div>
                                    @endif
                                </td>
                                <td class="py-3 text-slate-800 font-semibold">
                                    {{ $eco->name ?: ($eco->owners->first()?->full_name ?? 'غير محدد') }}
                                    @if($eco->id_card)
                                        <div class="text-[10px] text-slate-400 font-mono">هوية: {{ $eco->id_card }}</div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700">
                                        {{ $eco->sector?->name ?? ($eco->sector?->sector_name ?? 'عام') }}
                                    </span>
                                </td>
                                <td class="py-3 text-slate-600">
                                    @if($eco->job_location)
                                        <div>{{ $eco->job_location }}</div>
                                    @endif
                                    @if($eco->building)
                                        <div class="text-[11px] text-blue-600 font-semibold">مبنى رقم {{ $eco->building->building_number ?? $eco->building_id }}</div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($eco->isLicensed == 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> مرخص
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> غير مرخص
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    @if($eco->isActive == 2)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> نشط
                                        </span>
                                    @elseif($eco->isActive == 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            مغلق
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            قيد المراجعة
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('crafts.economical.show', $eco->id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-700 font-bold text-xs border border-orange-200 transition-colors">
                                        <span>تفاصيل</span>
                                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">لا توجد منشآت مسجلة حديثاً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 5. Quick Actions Strip --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-2">
            <a href="{{ route('crafts.economical.create') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-plus class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">إضافة نشاط</div>
                    <div class="text-[10px] text-slate-400">تسجيل منشأة جديدة</div>
                </div>
            </a>

            <a href="{{ route('crafts.crafts.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-wrench-screwdriver class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">دليل الحرف</div>
                    <div class="text-[10px] text-slate-400">قائمة وتصنيف المهن</div>
                </div>
            </a>

            <a href="{{ route('crafts.economical.index', ['isLicensed' => 1]) }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-check-badge class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">الأنشطة المرخصة</div>
                    <div class="text-[10px] text-slate-400">سجل التراخيص الصادرة</div>
                </div>
            </a>

            <a href="{{ route('crafts.economical.index', ['isDanger' => 1]) }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-shield-exclamation class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">الأنشطة الخطرة</div>
                    <div class="text-[10px] text-slate-400">إجراءات السلامة</div>
                </div>
            </a>

            <a href="{{ route('buildings.buildings.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-building-office-2 class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">المباني والعقارات</div>
                    <div class="text-[10px] text-slate-400">ربط المنشأة بالعقار</div>
                </div>
            </a>

            <a href="{{ route('archive.index') }}" class="bg-white p-3.5 rounded-2xl border border-slate-200/80 hover:border-orange-400 shadow-2xs hover:shadow-xs transition-all flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <x-heroicon-s-archive-box class="w-5 h-5" />
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 truncate">الأرشيف المهني</div>
                    <div class="text-[10px] text-slate-400">وثائق ورخص الحرف</div>
                </div>
            </a>
        </div>

    </div>
</x-crafts-layout>
