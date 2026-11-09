<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">قلم الجمهور والمعاملات</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                قلم الجمهور والمعاملات
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - بوابة المعاملات والشكاوى وخدمة المواطنين الموحدة</p>
        </div>
    </x-slot>

    <div class="w-full pb-10 space-y-5">

        {{-- Horizontal Tab Navigation (مطابق تماماً لبرنامج المراسلات الداخلية) --}}
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

                {{-- Transactions Tab (سجل المعاملات) --}}
                <a href="{{ route('transactions.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ (request()->routeIs('transactions.index') || request()->routeIs('transactions.history') || request()->routeIs('transactions.show')) && !request()->routeIs('transactions.create') && request('tab') !== 'drafts'
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ (request()->routeIs('transactions.index') || request()->routeIs('transactions.history') || request()->routeIs('transactions.show')) && !request()->routeIs('transactions.create') && request('tab') !== 'drafts' ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}" style="{{ (request()->routeIs('transactions.index') || request()->routeIs('transactions.history') || request()->routeIs('transactions.show')) && !request()->routeIs('transactions.create') && request('tab') !== 'drafts' ? 'background-color: rgb(217 119 6);' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span>سجل المعاملات والشكاوى</span>
                </a>

                {{-- Portal Drafts Tab (طلبات البوابة) --}}
                <a href="{{ route('transactions.index', ['tab' => 'drafts']) }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request('tab') === 'drafts' || request()->routeIs('transactions.drafts.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request('tab') === 'drafts' || request()->routeIs('transactions.drafts.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}" style="{{ request('tab') === 'drafts' || request()->routeIs('transactions.drafts.*') ? 'background-color: rgb(217 119 6);' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <span>واردة من الموقع (المسودات)</span>
                    @php
                        $pendingDraftsCount = \App\Models\Transactions\BhmDraftTransaction::where('status', 'pending')->count();
                    @endphp
                    @if($pendingDraftsCount > 0)
                        <span class="bg-rose-500 text-white font-extrabold text-[10px] px-1.5 py-0.5 rounded-full leading-none">{{ $pendingDraftsCount }}</span>
                    @endif
                </a>

                {{-- Citizens Registry Tab --}}
                <a href="{{ route('customers.citizens.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 text-tertiary hover:bg-gray-50 hover:text-gray-800">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 bg-amber-50 text-secondary">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span>سجل المواطنين</span>
                </a>

                {{-- Create Tab (when on create page) --}}
                @if(request()->routeIs('transactions.create'))
                    <a href="{{ route('transactions.create') }}"
                       class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200 bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0 bg-secondary text-white shadow-xs" style="background-color: rgb(217 119 6);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span>تسجيل معاملة جديدة</span>
                    </a>
                @endif
            </div>

            {{-- New Transaction Action Button (when not on create page) --}}
            @if(!request()->routeIs('transactions.create'))
                <div class="flex items-center gap-2">
                    <a href="{{ route('transactions.create') }}"
                       class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all duration-200 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>تسجيل معاملة جديدة</span>
                    </a>
                </div>
            @endif
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
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold">إغلاق</button>
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
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold">إغلاق</button>
            </div>
        @endif

        {{-- Page Content --}}
        {{ $slot }}

    </div>
</x-app-layout>
