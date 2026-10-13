<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-emerald text-[10px] font-bold mb-0.5 tracking-wider">نظام قلم الجمهور</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                المعاملات والتراسل
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-7xl mx-auto pb-10">
        
        <!-- Sidebar (Right in RTL) -->
        <div class="w-full lg:w-1/4 space-y-4">
            
            <a href="{{ route('transactions.create') }}" class="w-full flex items-center justify-center gap-2 bg-emerald hover:bg-emerald-dark text-white font-bold py-3.5 px-4 rounded-2xl shadow-[0_4px_14px_0_rgba(16,185,129,0.39)] transition-all transform hover:-translate-y-0.5">
                <x-heroicon-o-plus-circle class="w-5 h-5" />
                إنشاء معاملة جديدة
            </a>

            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-bolt class="w-4 h-4 text-emerald" />
                    العمليات
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('transactions.index') ? 'bg-emerald/10 text-emerald-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('transactions.index') ? 'bg-emerald text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-s-document-duplicate class="w-4 h-4" />
                        </div>
                        صندوق المعاملات
                    </a>
                    <a href="{{ route('transactions.drafts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('transactions.drafts.*') ? 'bg-emerald/10 text-emerald-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('transactions.drafts.*') ? 'bg-emerald text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-inbox-arrow-down class="w-5 h-5" />
                        </div>
                        طلبات البوابة (المسودات)
                    </a>
                </nav>
            </div>
            
            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-cog-6-tooth class="w-4 h-4 text-gray-400" />
                    الإعدادات
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('transactions.types.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('transactions.types.*') ? 'bg-gray-100 text-gray-800 font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('transactions.types.*') ? 'bg-gray-200 text-gray-700 shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                        </div>
                        أنواع المعاملات
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content (Left in RTL) -->
        <div class="w-full lg:w-3/4 space-y-6">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
