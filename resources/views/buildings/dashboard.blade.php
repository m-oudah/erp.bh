<x-buildings-layout>
    <div class="space-y-6">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">نظرة عامة</h2>
                <p class="text-gray-500 mt-1 text-sm">إحصائيات سريعة لنظام التنظيم والأبنية</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Total Buildings -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_2px_15px_-3px_rgba(6,81,237,0.05)] border border-gray-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-building-office class="w-7 h-7" />
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium mb-1">إجمالي المباني</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_buildings']) }}</div>
                </div>
            </div>

            <!-- Licenses -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_2px_15px_-3px_rgba(6,81,237,0.05)] border border-gray-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-document-check class="w-7 h-7" />
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium mb-1">طلبات التراخيص</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_licenses']) }}</div>
                </div>
            </div>

            <!-- Economical -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_2px_15px_-3px_rgba(6,81,237,0.05)] border border-gray-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-currency-dollar class="w-7 h-7" />
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium mb-1">أنشطة اقتصادية</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_economical']) }}</div>
                </div>
            </div>

            <!-- Historical -->
            <div class="bg-white rounded-3xl p-6 shadow-[0_2px_15px_-3px_rgba(6,81,237,0.05)] border border-gray-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-building-library class="w-7 h-7" />
                </div>
                <div>
                    <div class="text-gray-500 text-sm font-medium mb-1">مباني تاريخية</div>
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['historical']) }}</div>
                </div>
            </div>
            
        </div>
        
    </div>
</x-buildings-layout>
