<nav x-data="{ open: false }" class="bg-navy border-b border-navy-dark text-white shadow-md relative z-50">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
        
        <!-- Right side (Logo & Title) -->
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="shrink-0 relative">
                <div class="absolute -inset-1 bg-white/20 rounded-full blur-sm"></div>
                <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="h-12 w-auto bg-white rounded-full p-1 shadow-sm relative z-10" />
            </a>
            
            @if(isset($module_title))
                <!-- Module specific title -->
                {{ $module_title }}
            @else
                <!-- Main Title -->
                <div>
                    <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">ERP</div>
                    <h1 class="font-bold text-xl tracking-wide leading-none">نظام بلدية بيت حانون الشامل</h1>
                    <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بوابة الموظفين والخدمات الداخلية الموحدة</p>
                </div>
            @endif
        </div>

        <!-- Left side (User Menu & Actions) -->
        <div class="flex items-center gap-4">
            
            @if(!request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg text-sm font-medium transition-colors border border-white/10 shadow-sm">
                    <x-heroicon-o-squares-2x2 class="w-5 h-5 text-orange" />
                    بوابة الأنظمة
                </a>
            @endif

            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl transition-colors border border-white/10 shadow-sm focus:outline-none">
                        <div class="text-right hidden sm:block py-1">
                            <div class="font-bold text-sm leading-none mb-1 text-white">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-blue-100/70 leading-none">{{ Auth::user()->roles->first()?->name ?? 'موظف' }}</div>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                            <x-heroicon-s-user class="w-5 h-5 text-white/80" />
                        </div>
                        <x-heroicon-o-chevron-down class="w-4 h-4 mr-1 opacity-50" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                        <x-heroicon-o-user-circle class="w-5 h-5 text-gray-400" />
                        الملف الشخصي
                    </x-dropdown-link>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600 hover:text-red-700">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
                            تسجيل الخروج
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
        
    </div>
</nav>
