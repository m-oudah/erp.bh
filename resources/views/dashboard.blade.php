<x-app-layout>
    <div class="flex flex-col items-center justify-center mb-6 mt-4">
        <h2 class="text-2xl md:text-3xl font-extrabold text-navy-dark mb-2 tracking-tight">مرحباً بك في بوابة الأنظمة</h2>
        <p class="text-sm text-gray-500 font-medium">الرجاء اختيار الموديول المخصص لك لمباشرة العمل.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 max-w-6xl mx-auto pb-4 px-4">
        
        <!-- Active Module: Archive -->
        <a href="{{ route('archive.index') }}" class="group bg-navy hover:bg-navy-dark rounded-3xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center text-center h-40 relative border-b-4 border-orange">
            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white">
                <x-heroicon-s-document-text class="w-5 h-5" />
            </div>
            <h3 class="text-xl font-bold text-white mt-4 mb-1">الأرشيف الإلكتروني والمستندات</h3>
            <span class="text-xs text-blue-100/70 font-medium">نشط بالكامل</span>
        </a>

        <!-- Inactive Module 1 -->
        <div class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-40 relative">
            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-building-office class="w-5 h-5" />
            </div>
            <h3 class="text-lg font-bold text-navy-dark mt-4 mb-1">نظام التنظيم والأبنية</h3>
            <span class="text-xs text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 2 -->
        <div class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-40 relative">
            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-map class="w-5 h-5" />
            </div>
            <h3 class="text-lg font-bold text-navy-dark mt-4 mb-1">نظام المعلومات الجغرافية GIS</h3>
            <span class="text-xs text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 3 -->
        <div class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-40 relative">
            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-users class="w-5 h-5" />
            </div>
            <h3 class="text-lg font-bold text-navy-dark mt-4 mb-1">نظام شؤون الموظفين والموارد البشرية</h3>
            <span class="text-xs text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 4 -->
        <div class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-40 relative">
            <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-wrench-screwdriver class="w-5 h-5" />
            </div>
            <h3 class="text-lg font-bold text-navy-dark mt-4 mb-1">نظام الصيانة والشكاوى</h3>
            <span class="text-xs text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>
        
    </div>
</x-app-layout>
