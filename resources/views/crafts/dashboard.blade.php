<x-crafts-layout>
    <div class="space-y-8">

        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">لوحة التحكم</h2>
                <p class="text-gray-400 mt-1 text-sm">إحصائيات ونظرة عامة على نظام الحرف والأنشطة الاقتصادية</p>
            </div>
            <div class="text-xs text-gray-400 bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 font-medium">
                <x-heroicon-o-clock class="w-3.5 h-3.5 inline-block mb-0.5" />
                {{ now()->translatedFormat('l، d F Y') }}
            </div>
        </div>

        {{-- ==================== TOTAL CARD ==================== --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <x-heroicon-s-currency-dollar class="w-4 h-4" />
                </div>
                <h3 class="text-base font-bold text-gray-700">الأنشطة الاقتصادية</h3>
            </div>

            {{-- Primary card --}}
            <a href="{{ route('crafts.economical.index') }}"
               class="flex items-center gap-5 rounded-3xl px-6 py-5 hover:-translate-y-0.5 transition-transform duration-300 mb-4"
               style="background: linear-gradient(to left, #ea580c, #c2410c); box-shadow: 0 8px 30px -3px rgba(234,88,12,0.35);">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.15);">
                    <x-heroicon-s-currency-dollar class="w-8 h-8 text-white" />
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium mb-1" style="color:rgba(254,215,170,0.9);">إجمالي الأنشطة الاقتصادية المسجلة</div>
                    <div class="text-4xl font-extrabold text-white leading-none">{{ number_format($stats['total']) }}</div>
                </div>
                <div class="text-xs font-medium shrink-0 flex items-center gap-1" style="color:rgba(254,215,170,0.7);">
                    عرض الكل
                    <x-heroicon-s-chevron-left class="w-4 h-4" />
                </div>
            </a>

            {{-- Activity Status sub-stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">

                {{-- Active --}}
                <div class="bg-white rounded-2xl p-4 border border-emerald-100 hover:-translate-y-0.5 transition-transform duration-200"
                     style="box-shadow: 0 2px 10px -3px rgba(16,185,129,0.08);">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">أنشطة نشطة</span>
                    </div>
                    <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['active']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total'] > 0 ? number_format(($stats['active'] / $stats['total']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- Closed --}}
                <div class="bg-white rounded-2xl p-4 border border-gray-200 hover:-translate-y-0.5 transition-transform duration-200"
                     style="box-shadow: 0 2px 10px -3px rgba(107,114,128,0.08);">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-no-symbol class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">أنشطة مغلقة</span>
                    </div>
                    <div class="text-2xl font-extrabold text-gray-600">{{ number_format($stats['closed']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total'] > 0 ? number_format(($stats['closed'] / $stats['total']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- New/Unapproved --}}
                <div class="bg-white rounded-2xl p-4 border border-amber-100 hover:-translate-y-0.5 transition-transform duration-200"
                     style="box-shadow: 0 2px 10px -3px rgba(245,158,11,0.08);">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-clock class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">غير مصنفة</span>
                    </div>
                    <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['new_unapproved']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total'] > 0 ? number_format(($stats['new_unapproved'] / $stats['total']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

                {{-- Dangerous --}}
                <div class="bg-white rounded-2xl p-4 border border-red-100 hover:-translate-y-0.5 transition-transform duration-200"
                     style="box-shadow: 0 2px 10px -3px rgba(239,68,68,0.08);">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                            <x-heroicon-s-fire class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-bold text-gray-500 leading-tight">أنشطة خطرة</span>
                    </div>
                    <div class="text-2xl font-extrabold text-red-600">{{ number_format($stats['dangerous']) }}</div>
                    <div class="text-[10px] text-gray-400 mt-1">
                        {{ $stats['total'] > 0 ? number_format(($stats['dangerous'] / $stats['total']) * 100, 1) : 0 }}% من الإجمالي
                    </div>
                </div>

            </div>

            {{-- License Status Tags --}}
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-600" />
                    <span class="text-xs font-bold text-emerald-700">أنشطة مرخصة</span>
                    <span class="text-sm font-extrabold text-emerald-800">{{ number_format($stats['licensed']) }}</span>
                </div>
                <div class="flex items-center gap-2 bg-red-50 border border-red-100 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-x-circle class="w-4 h-4 text-red-500" />
                    <span class="text-xs font-bold text-red-600">أنشطة غير مرخصة</span>
                    <span class="text-sm font-extrabold text-red-700">{{ number_format($stats['unlicensed']) }}</span>
                </div>
                <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-xl px-4 py-2.5">
                    <x-heroicon-s-shield-check class="w-4 h-4 text-blue-600" />
                    <span class="text-xs font-bold text-blue-700">أنشطة آمنة</span>
                    <span class="text-sm font-extrabold text-blue-800">{{ number_format($stats['safe']) }}</span>
                </div>
            </div>
        </div>

        {{-- ==================== CRAFTS SECTION ==================== --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <x-heroicon-o-wrench-screwdriver class="w-4 h-4" />
                </div>
                <h3 class="text-base font-bold text-gray-700">الحرف والصناعات</h3>
            </div>

            <a href="{{ route('crafts.crafts.index') }}"
               class="flex items-center gap-4 rounded-2xl px-5 py-4 border border-blue-100 bg-white hover:-translate-y-0.5 transition-transform duration-200"
               style="box-shadow: 0 2px 10px -3px rgba(37,99,235,0.08);">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <x-heroicon-o-wrench-screwdriver class="w-6 h-6" />
                </div>
                <div class="flex-1">
                    <div class="text-sm font-bold text-gray-700">عرض قائمة الحرف</div>
                    <div class="text-xs text-gray-400 mt-0.5">إدارة وتصفح سجلات الحرف والصناعات المحلية</div>
                </div>
                <x-heroicon-s-chevron-left class="w-5 h-5 text-gray-300" />
            </a>
        </div>

    </div>
</x-crafts-layout>
