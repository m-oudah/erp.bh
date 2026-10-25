<x-buildings-layout>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">لوحة التحكم</h2>
                <p class="text-gray-400 mt-1 text-sm">إحصائيات ونظرة عامة على نظام التنظيم والأبنية والتراخيص</p>
            </div>
            <div class="text-xs text-gray-400 bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 font-medium">
                <x-heroicon-o-clock class="w-3.5 h-3.5 inline-block mb-0.5" />
                {{ now()->translatedFormat('l، d F Y') }}
            </div>
        </div>

        {{-- Main 2-Column Grid: Buildings (Right) & Licenses (Left) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            {{-- ==================== RIGHT COLUMN: BUILDINGS (إدارة المباني) ==================== --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-xs">
                            <x-heroicon-s-building-office class="w-4 h-4" />
                        </div>
                        <h3 class="text-base font-bold text-gray-800">إدارة المباني</h3>
                    </div>
                    <a href="{{ route('buildings.buildings.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        <span>سجل المباني الكامل</span>
                        <x-heroicon-s-chevron-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                {{-- Primary card: total buildings --}}
                <a href="{{ route('buildings.buildings.index') }}"
                   class="flex items-center gap-5 rounded-3xl px-6 py-5 hover:-translate-y-0.5 transition-transform duration-300 block group"
                   style="background: linear-gradient(to left, #1d4ed8, #1e40af); box-shadow: 0 8px 30px -3px rgba(37,99,235,0.35);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                        <x-heroicon-s-building-office class="w-7 h-7 text-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold mb-1" style="color:rgba(219,234,254,0.85);">إجمالي المباني المسجلة</div>
                        <div class="text-3xl font-black text-white leading-none">{{ number_format($stats['total_buildings']) }}</div>
                    </div>
                    <div class="text-xs font-bold shrink-0 flex items-center gap-1 text-white/90 group-hover:text-white">
                        <span>عرض الكل</span>
                        <x-heroicon-s-chevron-left class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                    </div>
                </a>

                {{-- Sub-stats grid: building license status (2x2) - Interactive Filter Links --}}
                <div class="grid grid-cols-2 gap-3">

                    {{-- 1. Unlicensed --}}
                    <a href="{{ route('buildings.buildings.index', ['construction_status' => 0]) }}"
                       class="bg-white rounded-2xl p-4 border border-red-100 shadow-[0_2px_10px_-3px_rgba(239,68,68,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض المباني غير المرخصة">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-red-50 text-red-500 group-hover:bg-red-500 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-x-circle class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-red-600 transition-colors leading-tight">مباني غير مرخصة</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-red-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-red-600">{{ number_format($stats['unlicensed']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_buildings'] > 0 ? number_format(($stats['unlicensed'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-red-500 font-bold group-hover:underline">عرض المباني ←</span>
                        </div>
                    </a>

                    {{-- 2. Licensed Non-Compliant (fees) --}}
                    <a href="{{ route('buildings.buildings.index', ['construction_status' => 2]) }}"
                       class="bg-white rounded-2xl p-4 border border-amber-100 shadow-[0_2px_10px_-3px_rgba(245,158,11,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض المباني المرخصة وغير المستوفية للرسوم">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-exclamation-triangle class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-amber-600 transition-colors leading-tight">غير مستوفية الرسوم</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-amber-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['licensed_non_compliant']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_non_compliant'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-amber-600 font-bold group-hover:underline">عرض المباني ←</span>
                        </div>
                    </a>

                    {{-- 3. Licensed Pending Floor --}}
                    <a href="{{ route('buildings.buildings.index', ['construction_status' => 3]) }}"
                       class="bg-white rounded-2xl p-4 border border-orange-100 shadow-[0_2px_10px_-3px_rgba(249,115,22,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض المباني المرخصة ومتبقي طوابق">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 group-hover:bg-orange-500 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-arrow-up-circle class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-orange-600 transition-colors leading-tight">مرخصة ومتبقي طوابق</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-orange-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-orange-600">{{ number_format($stats['licensed_pending_floor']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_pending_floor'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-orange-600 font-bold group-hover:underline">عرض المباني ←</span>
                        </div>
                    </a>

                    {{-- 4. Licensed Compliant --}}
                    <a href="{{ route('buildings.buildings.index', ['construction_status' => 1]) }}"
                       class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-[0_2px_10px_-3px_rgba(16,185,129,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض المباني المرخصة والمستوفية للرسوم تماماً">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-check-circle class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-emerald-600 transition-colors leading-tight">مستوفية الرسوم تماماً</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['licensed_compliant']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_compliant'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-emerald-600 font-bold group-hover:underline">عرض المباني ←</span>
                        </div>
                    </a>

                </div>

                {{-- Extra tags row: historical / abandoned / adding floor - Interactive Filter Links --}}
                <div class="flex flex-wrap gap-2.5 pt-1">
                    <a href="{{ route('buildings.buildings.index', ['historical' => 1]) }}"
                       class="flex items-center gap-2 bg-purple-50 hover:bg-purple-100 border border-purple-100 rounded-xl px-3.5 py-2 transition-all cursor-pointer group hover:-translate-y-0.5 shadow-2xs"
                       title="استعلام وعرض المباني التاريخية">
                        <x-heroicon-s-building-library class="w-4 h-4 text-purple-600" />
                        <span class="text-xs font-bold text-purple-700 group-hover:underline">مباني تاريخية</span>
                        <span class="text-xs font-extrabold text-purple-900 bg-purple-100/80 px-1.5 py-0.5 rounded-md">{{ number_format($stats['historical']) }}</span>
                    </a>
                    <a href="{{ route('buildings.buildings.index', ['abandoned' => 1]) }}"
                       class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-xl px-3.5 py-2 transition-all cursor-pointer group hover:-translate-y-0.5 shadow-2xs"
                       title="استعلام وعرض المباني المهجورة">
                        <x-heroicon-s-no-symbol class="w-4 h-4 text-gray-500" />
                        <span class="text-xs font-bold text-gray-700 group-hover:underline">مباني مهجورة</span>
                        <span class="text-xs font-extrabold text-gray-800 bg-white px-1.5 py-0.5 rounded-md">{{ number_format($stats['abandoned']) }}</span>
                    </a>
                    <a href="{{ route('buildings.buildings.index', ['adding_new_floor' => 1]) }}"
                       class="flex items-center gap-2 bg-sky-50 hover:bg-sky-100 border border-sky-100 rounded-xl px-3.5 py-2 transition-all cursor-pointer group hover:-translate-y-0.5 shadow-2xs"
                       title="استعلام وعرض مباني إضافة طوابق جديدة">
                        <x-heroicon-s-squares-plus class="w-4 h-4 text-sky-600" />
                        <span class="text-xs font-bold text-sky-700 group-hover:underline">إضافة طوابق</span>
                        <span class="text-xs font-extrabold text-sky-900 bg-sky-100/80 px-1.5 py-0.5 rounded-md">{{ number_format($stats['adding_new_floor']) }}</span>
                    </a>
                </div>
            </div>

            {{-- ==================== LEFT COLUMN: LICENSES (ملخص طلبات التراخيص) ==================== --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-xs">
                            <x-heroicon-s-document-check class="w-4 h-4" />
                        </div>
                        <h3 class="text-base font-bold text-gray-800">ملخص طلبات التراخيص</h3>
                    </div>
                    <a href="{{ route('buildings.licenses.index') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center gap-1">
                        <span>سجل التراخيص</span>
                        <x-heroicon-s-chevron-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                {{-- Primary card: total licenses --}}
                <a href="{{ route('buildings.licenses.index') }}"
                   class="flex items-center gap-5 rounded-3xl px-6 py-5 hover:-translate-y-0.5 transition-transform duration-300 block group"
                   style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 30px -3px rgba(16,185,129,0.30);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                        <x-heroicon-s-document-check class="w-7 h-7 text-white" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold mb-1" style="color:rgba(209,250,229,0.85);">إجمالي طلبات التراخيص</div>
                        <div class="text-3xl font-black text-white leading-none">{{ number_format($stats['total_licenses']) }}</div>
                    </div>
                    <div class="text-xs font-bold shrink-0 flex items-center gap-1 text-white/90 group-hover:text-white">
                        <span>عرض الكل</span>
                        <x-heroicon-s-chevron-left class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                    </div>
                </a>

                {{-- Sub-stats grid: licenses status (2x2 matching buildings) - Interactive Filter Links --}}
                <div class="grid grid-cols-2 gap-3">

                    {{-- 1. Approved Licenses --}}
                    <a href="{{ route('buildings.licenses.index', ['status' => 1]) }}"
                       class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-[0_2px_10px_-3px_rgba(16,185,129,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض طلبات التراخيص المعتمدة">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-check-badge class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-emerald-700 transition-colors leading-tight">طلبات معتمدة ومصدرة</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['approved_licenses']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_licenses'] > 0 ? number_format(($stats['approved_licenses'] / $stats['total_licenses']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-emerald-600 font-bold group-hover:underline">عرض الطلبات ←</span>
                        </div>
                    </a>

                    {{-- 2. Pending Licenses --}}
                    <a href="{{ route('buildings.licenses.index', ['status' => 0]) }}"
                       class="bg-white rounded-2xl p-4 border border-amber-100 shadow-[0_2px_10px_-3px_rgba(245,158,11,0.08)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 block group cursor-pointer"
                       title="استعلام وعرض طلبات التراخيص المعلقة">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 group-hover:bg-amber-500 group-hover:text-white transition-colors flex items-center justify-center shrink-0">
                                    <x-heroicon-s-clock class="w-4 h-4" />
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-amber-700 transition-colors leading-tight">طلبات معلقة وقيد الإجراء</span>
                            </div>
                            <x-heroicon-s-chevron-left class="w-4 h-4 text-gray-300 group-hover:text-amber-500 group-hover:-translate-x-0.5 transition-all" />
                        </div>
                        <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['pending_licenses']) }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 flex items-center justify-between">
                            <span>{{ $stats['total_licenses'] > 0 ? number_format(($stats['pending_licenses'] / $stats['total_licenses']) * 100, 1) : 0 }}% من الإجمالي</span>
                            <span class="text-amber-600 font-bold group-hover:underline">عرض الطلبات ←</span>
                        </div>
                    </a>

                    {{-- 3. Approval Rate --}}
                    <div class="bg-white rounded-2xl p-4 border border-teal-100 shadow-[0_2px_10px_-3px_rgba(13,148,136,0.08)] hover:-translate-y-0.5 transition-transform duration-200">
                        <div class="flex items-center gap-2 mb-2.5">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-chart-bar class="w-4 h-4" />
                            </div>
                            <span class="text-xs font-bold text-gray-600 leading-tight">نسبة إنجاز المعاملات</span>
                        </div>
                        <div class="text-2xl font-extrabold text-teal-700">
                            {{ $stats['total_licenses'] > 0 ? number_format(($stats['approved_licenses'] / $stats['total_licenses']) * 100, 0) : 0 }}%
                        </div>
                        <div class="text-[10px] text-gray-400 mt-1">
                            معاملات استوفت الاعتماد
                        </div>
                    </div>

                    {{-- 4. Search & Manage Quick Action --}}
                    <a href="{{ route('buildings.licenses.index') }}"
                       class="bg-white rounded-2xl p-4 border border-slate-200/80 hover:border-emerald-300 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.04)] hover:-translate-y-1 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                        <div class="flex items-center gap-2 mb-2.5">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 text-secondary group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors flex items-center justify-center shrink-0">
                                <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                            </div>
                            <span class="text-xs font-bold text-gray-600 leading-tight">البحث والفحص الفني</span>
                        </div>
                        <div class="text-xs font-bold text-emerald-700 flex items-center justify-between">
                            <span>فتح السجل الكامل</span>
                            <x-heroicon-s-arrow-left class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
                        </div>
                        <div class="text-[10px] text-gray-400 mt-1">
                            ربط المواطنين والملفات
                        </div>
                    </a>

                </div>

                {{-- Extra tags row for licenses --}}
                <div class="flex flex-wrap gap-2.5 pt-1">
                    <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-xl px-3.5 py-2">
                        <x-heroicon-s-user-group class="w-4 h-4 text-emerald-600" />
                        <span class="text-xs font-bold text-emerald-700">ربط المواطنين بالمعاملات</span>
                        <span class="text-xs font-bold text-emerald-800 bg-emerald-100/60 px-1.5 py-0.5 rounded-md">مفعّل</span>
                    </div>
                    <div class="flex items-center gap-2 bg-teal-50 border border-teal-100 rounded-xl px-3.5 py-2">
                        <x-heroicon-s-archive-box class="w-4 h-4 text-teal-600" />
                        <span class="text-xs font-bold text-teal-700">ربط الأرشيف المركزي</span>
                        <span class="text-xs font-bold text-teal-800 bg-teal-100/60 px-1.5 py-0.5 rounded-md">متاح</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-buildings-layout>
