<x-buildings-layout>
    <div class="space-y-8">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">لوحة التحكم</h2>
                <p class="text-gray-400 mt-1 text-sm">إحصائيات ونظرة عامة على نظام التنظيم والأبنية</p>
            </div>
            <div class="text-xs text-gray-400 bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 font-medium">
                <x-heroicon-o-clock class="w-3.5 h-3.5 inline-block mb-0.5" />
                {{ now()->translatedFormat('l، d F Y') }}
            </div>
        </div>

        {{-- ==================== SECTION: BUILDINGS ==================== --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <x-heroicon-s-building-office class="w-4 h-4" />
                </div>
                <h3 class="text-base font-bold text-gray-700">إدارة المباني</h3>
            </div>

            {{-- Primary card: total buildings --}}
            <a href="{{ route('buildings.buildings.index') }}"
               class="flex items-center gap-5 rounded-3xl px-6 py-5 hover:-translate-y-0.5 transition-transform duration-300 mb-4"
               style="background: linear-gradient(to left, #1d4ed8, #1e40af); box-shadow: 0 8px 30px -3px rgba(37,99,235,0.35);">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                    <x-heroicon-s-building-office class="w-8 h-8 text-white" />
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium mb-1" style="color:rgba(219,234,254,0.85);">إجمالي المباني المسجلة</div>
                    <div class="text-4xl font-extrabold text-white leading-none">{{ number_format($stats['total_buildings']) }}</div>
                </div>
                <div class="text-xs font-medium shrink-0 flex items-center gap-1" style="color:rgba(191,219,254,0.7);">
                    عرض الكل
                    <x-heroicon-s-chevron-left class="w-4 h-4" />
                </div>
            </a>

            {{-- Sub-stats grid: building license status --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">

                {{-- Unlicensed --}}
                <div class="bg-white rounded-2xl p-4 border border-red-100 shadow-[0_2px_10px_-3px_rgba(239,68,68,0.08)] hover:-translate-y-0.5 transition-transform duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-x-circle class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">مباني غير مرخصة</span>
                    </div>
                    <div class="text-2xl font-extrabold text-red-600">{{ number_format($stats['unlicensed']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total_buildings'] > 0 ? number_format(($stats['unlicensed'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- Licensed Non-Compliant (fees) --}}
                <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-[0_2px_10px_-3px_rgba(245,158,11,0.08)] hover:-translate-y-0.5 transition-transform duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-exclamation-triangle class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">مرخصة وغير مستوفية الرسوم</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['licensed_non_compliant']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_non_compliant'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- Licensed Pending Floor --}}
                <div class="bg-white rounded-2xl p-4 border border-orange-100 shadow-[0_2px_10px_-3px_rgba(249,115,22,0.08)] hover:-translate-y-0.5 transition-transform duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-arrow-up-circle class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">مرخصة ومتبقي طوابق</span>
                    </div>
                    <div class="text-2xl font-extrabold text-orange-600">{{ number_format($stats['licensed_pending_floor']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_pending_floor'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- Licensed Compliant --}}
                <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-[0_2px_10px_-3px_rgba(16,185,129,0.08)] hover:-translate-y-0.5 transition-transform duration-200">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">مرخصة ومستوفية الرسوم</span>
                    </div>
                    <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['licensed_compliant']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total_buildings'] > 0 ? number_format(($stats['licensed_compliant'] / $stats['total_buildings']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

            </div>

            {{-- Extra tags row: historical / abandoned / adding floor --}}
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2 bg-purple-50 border border-purple-100 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-building-library class="w-4 h-4 text-purple-600" />
                    <span class="text-xs font-bold text-purple-700">مباني تاريخية</span>
                    <span class="text-sm font-extrabold text-purple-800">{{ number_format($stats['historical']) }}</span>
                </div>
                <div class="flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-no-symbol class="w-4 h-4 text-gray-500" />
                    <span class="text-xs font-bold text-gray-600">مباني مهجورة</span>
                    <span class="text-sm font-extrabold text-gray-700">{{ number_format($stats['abandoned']) }}</span>
                </div>
                <div class="flex items-center gap-2 bg-sky-50 border border-sky-100 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-squares-plus class="w-4 h-4 text-sky-600" />
                    <span class="text-xs font-bold text-sky-700">إضافة طوابق جديدة</span>
                    <span class="text-sm font-extrabold text-sky-800">{{ number_format($stats['adding_new_floor']) }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- ==================== SECTION: LICENSES ==================== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <x-heroicon-s-document-check class="w-4 h-4" />
                    </div>
                    <h3 class="text-base font-bold text-gray-700">طلبات التراخيص</h3>
                </div>

                <a href="{{ route('buildings.licenses.index') }}"
                   class="flex items-center gap-4 rounded-3xl px-5 py-4 hover:-translate-y-0.5 transition-transform duration-300 mb-3"
                   style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 8px 30px -3px rgba(16,185,129,0.30);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                        <x-heroicon-s-document-check class="w-7 h-7 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium mb-0.5" style="color:rgba(209,250,229,0.85);">إجمالي الطلبات</div>
                        <div class="text-4xl font-extrabold text-white leading-none">{{ number_format($stats['total_licenses']) }}</div>
                    </div>
                </a>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <x-heroicon-s-check-badge class="w-3.5 h-3.5" />
                            </div>
                            <span class="text-xs font-bold text-gray-500">طلبات معتمدة</span>
                        </div>
                        <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['approved_licenses']) }}</div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center">
                                <x-heroicon-s-clock class="w-3.5 h-3.5" />
                            </div>
                            <span class="text-xs font-bold text-gray-500">طلبات معلقة</span>
                        </div>
                        <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['pending_licenses']) }}</div>
                    </div>
                </div>
            </div>

            {{-- ==================== SECTION: ECONOMICAL ==================== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <x-heroicon-s-currency-dollar class="w-4 h-4" />
                    </div>
                    <h3 class="text-base font-bold text-gray-700">الأنشطة الاقتصادية</h3>
                </div>

                <div class="flex items-center gap-4 rounded-3xl px-5 py-4 hover:-translate-y-0.5 transition-transform duration-300 mb-3"
                     style="background: linear-gradient(135deg, #f97316, #ea580c); box-shadow: 0 8px 30px -3px rgba(249,115,22,0.30);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                        <x-heroicon-s-currency-dollar class="w-7 h-7 text-white" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium mb-0.5" style="color:rgba(255,237,213,0.85);">إجمالي الأنشطة</div>
                        <div class="text-4xl font-extrabold text-white leading-none">{{ number_format($stats['total_economical']) }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-sm">
                        <div class="flex items-center gap-1.5 mb-2">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                            </div>
                            <span class="text-[10px] font-bold text-gray-500">مرخصة</span>
                        </div>
                        <div class="text-xl font-extrabold text-emerald-600">{{ number_format($stats['licensed_economical']) }}</div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-red-100 shadow-sm">
                        <div class="flex items-center gap-1.5 mb-2">
                            <div class="w-6 h-6 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                                <x-heroicon-s-x-circle class="w-3.5 h-3.5" />
                            </div>
                            <span class="text-[10px] font-bold text-gray-500">غير مرخصة</span>
                        </div>
                        <div class="text-xl font-extrabold text-red-600">{{ number_format($stats['unlicensed_economical']) }}</div>
                    </div>
                    <div class="bg-white rounded-2xl p-4 border border-red-200 shadow-sm">
                        <div class="flex items-center gap-1.5 mb-2">
                            <div class="w-6 h-6 rounded-lg bg-red-100 text-red-700 flex items-center justify-center">
                                <x-heroicon-s-fire class="w-3.5 h-3.5" />
                            </div>
                            <span class="text-[10px] font-bold text-gray-500">أنشطة خطرة</span>
                        </div>
                        <div class="text-xl font-extrabold text-red-700">{{ number_format($stats['dangerous_economical']) }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-buildings-layout>
