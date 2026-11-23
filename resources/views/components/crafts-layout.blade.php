<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">الحرف والصناعات</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام الحرف والصناعات
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="w-full pb-10 space-y-5">

        {{-- Horizontal Tab Navigation (Correspondence Module Style) --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-4 py-2 flex items-center justify-between gap-2 overflow-x-auto">
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Home Button --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100/90 hover:bg-slate-200 transition-colors shrink-0"
                   title="الرجوع للصفحة الرئيسية للنظام">
                    <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>الرئيسية</span>
                </a>

                <div class="h-5 w-px bg-gray-200 mx-1 shrink-0"></div>

                <a href="{{ route('crafts.dashboard') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('crafts.dashboard')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('crafts.dashboard') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-squares-2x2 class="w-3.5 h-3.5" />
                    </div>
                    <span>لوحة الحرف والصناعات</span>
                </a>

                <a href="{{ route('crafts.economical.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('crafts.economical.*') && !request()->routeIs('crafts.economical.create')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('crafts.economical.*') && !request()->routeIs('crafts.economical.create') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-o-currency-dollar class="w-3.5 h-3.5" />
                    </div>
                    <span>الأنشطة الاقتصادية والمحلات</span>
                </a>

                <a href="{{ route('crafts.crafts.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('crafts.crafts.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('crafts.crafts.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-o-wrench-screwdriver class="w-3.5 h-3.5" />
                    </div>
                    <span>دليل الحرف والتصنيفات</span>
                </a>
            </div>

            {{-- Action Buttons on the Left --}}
            <div class="flex items-center gap-2 shrink-0">
                @if(auth()->user()->can('Create-Craft') || auth()->user()->can('bhm.crafts.create') || auth()->user()->can('Read-Crafts'))
                <a href="{{ route('crafts.economical.create') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all duration-200">
                    <x-heroicon-s-plus class="w-4 h-4 text-amber-300" />
                    <span>إضافة منشأة / حرفة</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold cursor-pointer">إغلاق</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">إغلاق</button>
            </div>
        @endif

        {{-- Main Content – Full Width (Correspondence Module Standard) --}}
        {{ $slot }}

    </div>
</x-app-layout>
