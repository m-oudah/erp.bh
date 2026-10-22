<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">المراسلات والتكليفات الإدارية</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام المراسلات والمهام الداخلية
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - سير المعاملات والتوجيه الإلكتروني</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10 space-y-5">

        {{-- Horizontal Tab Navigation --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-4 py-2 flex items-center justify-between gap-2 overflow-x-auto">
            <div class="flex items-center gap-1">
                {{-- Memos Tab --}}
                <a href="{{ route('correspondence.memos.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('correspondence.memos.index') || request()->routeIs('correspondence.memos.show')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('correspondence.memos.index') || request()->routeIs('correspondence.memos.show') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    سجل المذكرات والمراسلات
                </a>

                {{-- Tasks Tab --}}
                <a href="{{ route('correspondence.tasks.index') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('correspondence.tasks.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('correspondence.tasks.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    المهام والتكليفات
                </a>
            </div>

            {{-- New Memo Action Button --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('correspondence.memos.create') }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إنشاء مذكرة داخلية جديدة
                </a>
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
