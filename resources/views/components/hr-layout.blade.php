<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">شؤون الموظفين والموارد البشرية</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                الموارد البشرية والرواتب
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - الكادر الوظيفي والهيكلية واحتساب الرواتب</p>
        </div>
    </x-slot>

    <div class="w-full pb-10 space-y-5">

        {{-- Horizontal Tab Navigation --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-4 py-2 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 overflow-x-auto">
            <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto pb-1 md:pb-0">
                {{-- Home Button --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100/90 hover:bg-slate-200 transition-colors shrink-0"
                   title="الرجوع للبوابة الرئيسية للنظام">
                    <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>الرئيسية</span>
                </a>

                <div class="h-5 w-px bg-gray-200 mx-1 shrink-0"></div>

                {{-- Dashboard Tab --}}
                <a href="{{ route('hr.dashboard') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('hr.dashboard')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('hr.dashboard') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-squares-2x2 class="w-3.5 h-3.5" />
                    </div>
                    <span>لوحة التحكم</span>
                </a>

                {{-- Employees Tab --}}
                <a href="{{ route('hr.employees.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('hr.employees.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('hr.employees.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-user-group class="w-3.5 h-3.5" />
                    </div>
                    <span>سجل الموظفين</span>
                </a>

                {{-- Hierarchy Tab --}}
                <a href="{{ route('hr.departments.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('hr.departments.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('hr.departments.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-rectangle-stack class="w-3.5 h-3.5" />
                    </div>
                    <span>الهيكلية الإدارية</span>
                </a>


                {{-- Payroll Tab --}}
                <a href="{{ route('hr.payroll.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('hr.payroll.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('hr.payroll.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-banknotes class="w-3.5 h-3.5" />
                    </div>
                    <span>احتساب الرواتب</span>
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-check class="w-5 h-5" />
                </div>
                <div class="text-sm font-bold flex-1">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-x-mark class="w-5 h-5" />
                </div>
                <div class="text-sm font-bold flex-1">{{ session('error') }}</div>
            </div>
        @endif

        {{-- Content Slot --}}
        <div>
            {{ $slot }}
        </div>

    </div>
</x-app-layout>

