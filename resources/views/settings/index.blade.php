<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                إعدادات النظام
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10">
        <h2 class="text-2xl font-bold text-navy-dark mb-6">إعدادات النظام الموحد</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="{{ route('settings.archive.types.index') }}" class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center gap-4 hover:border-orange hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-orange/10 flex items-center justify-center text-orange group-hover:bg-orange group-hover:text-white transition-colors">
                    <x-heroicon-s-document-text class="w-7 h-7" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">إعدادات الأرشيف</h3>
                    <p class="text-sm text-gray-500 font-medium">الأنواع، الحقول، والتصنيفات</p>
                </div>
            </a>

            <!-- Users Settings -->
            <a href="{{ route('settings.users.index') }}" class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center gap-4 hover:border-navy hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-navy/10 flex items-center justify-center text-navy group-hover:bg-navy group-hover:text-white transition-colors">
                    <x-heroicon-s-users class="w-7 h-7" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">إعدادات المستخدمين</h3>
                    <p class="text-sm text-gray-500 font-medium">الصلاحيات، البريد، وكلمات المرور</p>
                </div>
            </a>

            <!-- General Settings (إعدادات عامة) -->
            <a href="{{ route('settings.general.backup') }}" class="group bg-white rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 flex items-center gap-4 hover:border-teal-500 hover:shadow-md transition-all">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 group-hover:bg-primary group-hover:text-white transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">إعدادات عامة</h3>
                    <p class="text-sm text-gray-500 font-medium">النسخ الاحتياطي لقاعدة البيانات وإدارة النظام</p>
                </div>
            </a>

            <!-- Other future settings can go here -->

        </div>
    </div>
</x-app-layout>
