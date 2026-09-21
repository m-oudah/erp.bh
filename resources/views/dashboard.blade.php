<x-app-layout>
    <div class="flex flex-col items-center justify-center mb-12 mt-8">
        <h2 class="text-3xl font-extrabold text-navy-dark mb-3 tracking-tight">مرحباً بك في بوابة الأنظمة</h2>
        <p class="text-gray-500 font-medium">الرجاء اختيار الموديول المخصص لك لمباشرة العمل.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 max-w-6xl mx-auto pb-10">
        
        <!-- Active Module: Archive -->
        <a href="{{ route('archive.index') }}" class="group bg-navy hover:bg-navy-dark rounded-3xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center text-center h-52 relative border-b-4 border-orange">
            <div class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white">
                <x-heroicon-s-document-text class="w-6 h-6" />
            </div>
            <h3 class="text-2xl font-bold text-white mt-6 mb-2">الأرشيف الإلكتروني والمستندات</h3>
            <span class="text-sm text-blue-100/70 font-medium">نشط بالكامل</span>
        </a>

        <!-- Inactive Module 1 -->
        <div class="group bg-white rounded-3xl p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-52 relative">
            <div class="absolute top-6 right-6 w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-building-office class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-navy-dark mt-6 mb-2">نظام التنظيم والأبنية</h3>
            <span class="text-sm text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 2 -->
        <div class="group bg-white rounded-3xl p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-52 relative">
            <div class="absolute top-6 right-6 w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-map class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-navy-dark mt-6 mb-2">نظام المعلومات الجغرافية GIS</h3>
            <span class="text-sm text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 3 -->
        <div class="group bg-white rounded-3xl p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-52 relative">
            <div class="absolute top-6 right-6 w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-users class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-navy-dark mt-6 mb-2">نظام شؤون الموظفين والموارد البشرية</h3>
            <span class="text-sm text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>

        <!-- Inactive Module 4 -->
        <div class="group bg-white rounded-3xl p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex flex-col items-center justify-center text-center h-52 relative">
            <div class="absolute top-6 right-6 w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                <x-heroicon-s-wrench-screwdriver class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-navy-dark mt-6 mb-2">نظام الصيانة والشكاوى</h3>
            <span class="text-sm text-gray-400 font-medium">مستقبلي / قيد التطوير</span>
        </div>
        
    </div>
</x-app-layout>
