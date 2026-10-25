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
            
            {{-- زر المراسلات والمهام التفاعلي مع أيقونة الجرس وبادج الإشعارات وقائمة إشعارات موحدة --}}
            <div x-data="{
                dropdownOpen: false,
                unreadCount: 0,
                notifications: [],
                loading: false,
                fetchData() {
                    fetch('{{ route('notifications.unread') }}')
                        .then(res => res.json())
                        .then(data => {
                            this.unreadCount = data.count || 0;
                            this.notifications = data.notifications || [];
                        }).catch(() => {});
                }
            }" x-init="fetchData(); setInterval(() => fetchData(), 30000)" class="relative">

                <button @click="dropdownOpen = !dropdownOpen" 
                        class="relative flex items-center gap-2 px-3 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold transition-all border border-white/15 shadow-sm text-white focus:outline-none cursor-pointer"
                        title="المراسلات والمهام">
                    
                    {{-- أيقونة الجرس --}}
                    <div class="relative flex items-center justify-center">
                        <svg class="w-4 h-4 shrink-0" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-rose-500 rounded-full animate-ping"></span>
                        </template>
                    </div>

                    <span>المراسلات والمهام</span>

                    {{-- إظهار الإشعارات والعدد على زر المراسلات والمهام --}}
                    <template x-if="unreadCount > 0">
                        <span class="bg-rose-500 text-white font-bold rounded-full text-[10px] px-1.5 py-0.5 min-w-[18px] text-center shadow-xs leading-none" x-text="unreadCount"></span>
                    </template>

                    <svg class="w-3.5 h-3.5 opacity-70 transition-transform duration-200" :class="{ 'rotate-180': dropdownOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- القائمة المنسدلة: قائمة إشعارات موحدة بدون تبويبات --}}
                <div x-show="dropdownOpen"
                     @click.outside="dropdownOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute left-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 text-right text-slate-800 overflow-hidden"
                     style="display: none;">
                    
                    {{-- ترويسة القائمة المنسدلة --}}
                    <div class="px-4 py-3 bg-slate-50/80 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                                <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h3 class="text-xs font-bold text-slate-800 leading-tight">الإشعارات والتنبيهات</h3>
                                    <template x-if="unreadCount > 0">
                                        <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-1.5 py-0.2 rounded-full border border-rose-200" x-text="unreadCount + ' جديدة'"></span>
                                    </template>
                                </div>
                                <p class="text-[10px] text-slate-500">المراسلات والمهام الواردة</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <template x-if="unreadCount > 0">
                                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-bold text-teal-600 hover:text-teal-800 hover:underline">تعيين الكل كمقروء</button>
                                </form>
                            </template>
                            <a href="{{ route('correspondence.memos.create') }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-[11px] font-bold shadow-xs transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>مذكرة</span>
                            </a>
                        </div>
                    </div>

                    {{-- قائمة الإشعارات الموحدة بدون تبويبات --}}
                    <div class="max-h-80 overflow-y-auto p-3 space-y-2">
                        <template x-if="notifications.length === 0">
                            <div class="text-center py-8 text-slate-400">
                                <svg class="w-9 h-9 mx-auto mb-2 opacity-30 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <p class="text-xs font-medium">لا توجد إشعارات حالياً</p>
                            </div>
                        </template>

                        <template x-for="item in notifications" :key="item.id">
                            <a :href="item.url" 
                               class="block p-2.5 rounded-xl border transition-all"
                               :class="item.is_unread ? 'bg-amber-50/40 border-amber-200/70 hover:bg-amber-50' : 'bg-white border-gray-100 hover:bg-slate-50'">
                                <div class="flex items-start gap-2.5">
                                    {{-- مؤشر حالة الإشعار (مقروء / غير مقروء) --}}
                                    <div class="shrink-0 mt-1">
                                        <template x-if="item.is_unread">
                                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 block ring-2 ring-amber-200 animate-pulse"></span>
                                        </template>
                                        <template x-if="!item.is_unread">
                                            <span class="w-2 h-2 rounded-full bg-slate-300 block"></span>
                                        </template>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1 mb-1">
                                            <span class="text-xs font-bold text-slate-800 truncate" x-text="item.title"></span>
                                            <template x-if="item.type === 'memo' || item.title.includes('مذكرة')">
                                                <span class="text-[10px] bg-teal-50 text-teal-700 px-1.5 py-0.2 rounded font-semibold border border-teal-100 shrink-0">مذكرة</span>
                                            </template>
                                            <template x-if="item.type === 'task' || item.title.includes('مهمة')">
                                                <span class="text-[10px] bg-amber-50 text-amber-700 px-1.5 py-0.2 rounded font-semibold border border-amber-100 shrink-0">مهمة</span>
                                            </template>
                                        </div>

                                        <p class="text-[11px] text-teal-800 font-medium truncate" x-text="item.subject"></p>

                                        <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1.5">
                                            <span x-text="item.from ? ('من: ' + item.from) : ''"></span>
                                            <span x-text="item.time"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>

                    {{-- أسفل القائمة: روابط سريعة لكافة المذكرات والمهام --}}
                    <div class="border-t border-gray-100 p-2.5 bg-slate-50 flex items-center justify-between text-xs font-bold">
                        <a href="{{ route('correspondence.memos.index') }}" class="text-teal-700 hover:text-teal-900 flex items-center gap-1 hover:underline">
                            <span>سجل المذكرات</span>
                            <span>←</span>
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('correspondence.tasks.index') }}" class="text-teal-700 hover:text-teal-900 flex items-center gap-1 hover:underline">
                            <span>جدول المهام</span>
                            <span>←</span>
                        </a>
                    </div>
                </div>
            </div>

            @if(!request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" class="hidden sm:flex items-center gap-2 px-3.5 py-1.5 bg-white/10 hover:bg-white/20 rounded-xl text-xs font-bold transition-all border border-white/15 shadow-sm text-white">
                    <x-heroicon-o-squares-2x2 class="w-4 h-4" style="color: rgb(217 119 6);" />
                    <span>بوابة الأنظمة</span>
                </a>
            @endif

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
