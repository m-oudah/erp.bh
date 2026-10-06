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

            <!-- Other future settings can go here -->

        </div>
    </div>
</x-app-layout>
