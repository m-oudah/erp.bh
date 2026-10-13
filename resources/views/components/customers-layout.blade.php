<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">المشتركون والعملاء</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام المشتركون والعملاء
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-7xl mx-auto pb-10">
        
        <!-- Sidebar (Right in RTL) -->
        <div class="w-full lg:w-1/4 space-y-4">

            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-bolt class="w-4 h-4 text-orange" />
                    روابط سريعة
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('customers.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('customers.dashboard') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('customers.dashboard') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                        </div>
                        الرئيسية
                    </a>
                    <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('customers.index') || request()->routeIs('subscriptions.show') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('customers.index') || request()->routeIs('subscriptions.show') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-users class="w-5 h-5" />
                        </div>
                        الاشتراكات
                    </a>
                    
                    <a href="{{ route('customers.citizens.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('customers.citizens.index') || request()->routeIs('customers.show') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('customers.citizens.index') || request()->routeIs('customers.show') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-user-group class="w-5 h-5" />
                        </div>
                        سجل المواطنين
                    </a>
                </nav>
            </div>
            
        </div>

        <!-- Main Content (Left in RTL) -->
        <div class="w-full lg:w-3/4">
            <div class="bg-white/60 backdrop-blur-sm border border-gray-100 rounded-3xl p-6 min-h-[500px] shadow-[0_2px_20px_-3px_rgba(6,81,237,0.05)]">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
