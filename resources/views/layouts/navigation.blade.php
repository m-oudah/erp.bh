<nav x-data="{ open: false }" class="bg-primary border-b border-primary-dark text-white shadow-md relative z-50" style="background-color: rgb(13 148 136);">
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
                    <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">ERP</div>
                    <h1 class="font-bold text-xl tracking-wide leading-none text-white">نظام بلدية بيت حانون الشامل</h1>
                    <p class="text-teal-100 text-xs mt-1.5 font-medium">بوابة الموظفين والخدمات الداخلية الموحدة</p>
                </div>
            @endif
        </div>

        <!-- Left side (User Menu & Actions) -->
        <div class="flex items-center gap-2.5 sm:gap-3">
            {{-- أيقونة وزر بوابة الأنظمة متناسقة بنفس ارتفاع وحجم قائمة اسم المستخدم --}}
            @if(!request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" 
                   class="h-11 sm:h-12 flex items-center gap-2.5 px-3 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold transition-all border border-white/15 shadow-sm text-white group"
                   title="بوابة الأنظمة">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center text-white shrink-0 group-hover:bg-white/25 transition-colors">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5" style="color: rgb(217 119 6);" />
                    </div>
                    <span class="hidden sm:inline text-xs font-bold text-white">بوابة الأنظمة</span>
                </a>
            @endif

            {{-- قائمة اسم المستخدم: أيقونة المستخدم على يمين الاسم وارتفاع متناسق --}}
            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="h-11 sm:h-12 flex items-center gap-2.5 px-3 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/15 shadow-sm focus:outline-none cursor-pointer">
                        {{-- أيقونة المستخدم على يمين اسم المستخدم (في اتجاه RTL) --}}
                        <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center text-white shrink-0">
                            <x-heroicon-s-user class="w-5 h-5" style="color: rgb(217 119 6);" />
                        </div>

                        {{-- اسم المستخدم والمسمى الوظيفي --}}
                        <div class="text-right hidden sm:block py-0.5">
                            <div class="font-bold text-sm leading-none mb-1 text-white">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-teal-100 leading-none font-medium">{{ Auth::user()->roles->first()?->name ?? 'موظف' }}</div>
                        </div>

                        {{-- سهم القائمة على اليسار --}}
                        <x-heroicon-o-chevron-down class="w-4 h-4 mr-0.5 opacity-70 text-white shrink-0" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 font-medium">
                        <x-heroicon-o-user-circle class="w-5 h-5" style="color: rgb(217 119 6);" />
                        <span>الملف الشخصي</span>
                    </x-dropdown-link>
                    
                    <x-dropdown-link :href="route('settings.index')" class="flex items-center gap-2 font-medium">
                        <x-heroicon-o-cog-6-tooth class="w-5 h-5" style="color: rgb(217 119 6);" />
                        <span>إعدادات النظام</span>
                    </x-dropdown-link>
                    
                    <hr class="my-1 border-gray-100">
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-red-600 hover:text-red-700 font-medium">
                            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5 text-red-500" />
                            <span>تسجيل الخروج</span>
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
        
    </div>
</nav>
