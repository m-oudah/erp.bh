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
        <div class="flex items-center gap-4">
            
            @if(!request()->routeIs('correspondence.*'))
                <a href="{{ route('correspondence.memos.index') }}" class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold transition-all border border-white/15 shadow-sm text-white">
                    <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>المراسلات والمهام</span>
                </a>
            @endif

            @if(!request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold transition-all border border-white/15 shadow-sm text-white">
                    <x-heroicon-o-squares-2x2 class="w-4 h-4" style="color: rgb(217 119 6);" />
                    <span>بوابة الأنظمة</span>
                </a>
            @endif

            {{-- Interactive Notifications Bell --}}
            <div x-data="{
                notificationsOpen: false,
                unreadCount: 0,
                notifications: [],
                loading: false,
                fetchNotifications() {
                    fetch('{{ route('notifications.unread') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.unreadCount = data.count;
                            this.notifications = data.notifications;
                        }).catch(() => {});
                }
            }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)" class="relative">
                <button @click="notificationsOpen = !notificationsOpen" class="relative p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/15 text-white focus:outline-none cursor-pointer" title="التنبيهات والإشعارات">
                    <svg class="w-5 h-5" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1 -right-1 bg-rose-500 text-white font-bold rounded-full text-[10px] w-5 h-5 flex items-center justify-center border-2 border-teal-700 shadow-xs" x-text="unreadCount"></span>
                    </template>
                </button>

                {{-- Dropdown --}}
                <div x-show="notificationsOpen"
                     @click.outside="notificationsOpen = false"
                     x-transition
                     class="absolute left-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 p-4 z-50 text-right text-slate-800"
                     style="display: none;">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2.5 mb-2.5">
                        <div class="font-bold text-xs text-slate-800 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>مركز الإشعارات والتنبيهات</span>
                            <span class="bg-primary/10 text-primary text-[10px] font-bold px-1.5 py-0.5 rounded-full" x-text="unreadCount"></span>
                        </div>
                        <template x-if="unreadCount > 0">
                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-teal-600 hover:text-teal-800">تعيين الكل كمقروء</button>
                            </form>
                        </template>
                    </div>

                    <div class="max-h-72 overflow-y-auto space-y-2">
                        <template x-if="notifications.length === 0">
                            <p class="text-xs text-tertiary text-center py-6">لا توجد إشعارات جديدة غير مقروءة حالياً.</p>
                        </template>
                        <template x-for="item in notifications" :key="item.id">
                            <a :href="item.url" class="block p-2.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-gray-100 transition-colors">
                                <div class="flex items-start gap-2">
                                    <div class="w-2 h-2 rounded-full bg-secondary shrink-0 mt-1.5"></div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></div>
                                        <div class="text-[11px] text-teal-700 font-semibold truncate" x-text="item.subject"></div>
                                        <div class="flex items-center justify-between text-[10px] text-tertiary mt-1">
                                            <span x-text="'من: ' + item.from"></span>
                                            <span x-text="item.time"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    <div class="border-t border-gray-100 pt-2.5 mt-2.5 text-center">
                        <a href="{{ route('correspondence.memos.index') }}" class="text-xs font-bold text-primary hover:underline">
                            عرض كافة المذكرات والمهام
                        </a>
                    </div>
                </div>
            </div>

            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/15 shadow-sm focus:outline-none cursor-pointer">
                        <div class="text-right hidden sm:block py-1">
                            <div class="font-bold text-sm leading-none mb-1 text-white">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-teal-100 leading-none font-medium">{{ Auth::user()->roles->first()?->name ?? 'موظف' }}</div>
                        </div>
                        <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center text-white">
                            <x-heroicon-s-user class="w-5 h-5" style="color: rgb(217 119 6);" />
                        </div>
                        <x-heroicon-o-chevron-down class="w-4 h-4 mr-1 opacity-70 text-white" />
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
