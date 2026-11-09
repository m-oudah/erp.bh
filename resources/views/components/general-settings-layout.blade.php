<x-app-layout>
    @if(isset($module_title))
        <x-slot name="module_title">
            {{ $module_title }}
        </x-slot>
    @else
        <x-slot name="module_title">
            <div>
                <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم</div>
                <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                    إعدادات عامة
                </h1>
                <p class="text-teal-100 text-xs mt-1 font-medium">النسخ الاحتياطي لقاعدة البيانات وإدارة النظام</p>
            </div>
        </x-slot>
    @endif

    <div class="max-w-7xl mx-auto pb-10 flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar Navigation (Right side in RTL) -->
        <div class="w-full md:w-1/4 shrink-0">
            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-4 sticky top-6 space-y-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 mb-3 px-3 border-b border-gray-100 pb-2">قائمة الإعدادات العامة</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('settings.general.backup') }}" 
                               class="flex items-center gap-3 px-3 py-3 rounded-2xl font-bold transition-all {{ request()->routeIs('settings.general.backup*') ? 'bg-primary/10 text-primary border-r-4 border-primary' : 'text-slate-600 hover:bg-gray-50 hover:text-slate-900' }}">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 {{ request()->routeIs('settings.general.backup*') ? 'bg-primary text-white shadow-xs' : 'bg-slate-100 text-slate-500' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-black">النسخ الاحتياطي</span>
                                    <span class="block text-[10px] text-slate-400 font-medium">قاعدة البيانات والأرشيف</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Back to settings link --}}
                <div class="pt-2 border-t border-gray-100">
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-slate-500 hover:text-primary transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                        <span>العودة لإعدادات النظام</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>

