<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">التنظيم والأبنية</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام التنظيم والأبنية الموحد
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-7xl mx-auto pb-10">
        
        <!-- Sidebar (Right in RTL) -->
        <div class="w-full lg:w-1/4 space-y-4">
            
            @can('bhm.buildings.create')
            <a href="#" class="w-full flex items-center justify-center gap-2 bg-navy hover:bg-navy-dark text-white font-bold py-3.5 px-4 rounded-2xl shadow-[0_4px_14px_0_rgba(1,31,75,0.39)] transition-all transform hover:-translate-y-0.5">
                <x-heroicon-o-plus-circle class="w-5 h-5" />
                إضافة مبنى جديد
            </a>
            @endcan

            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-bolt class="w-4 h-4 text-orange" />
                    روابط سريعة
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('buildings.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('buildings.dashboard') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('buildings.dashboard') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                        </div>
                        الرئيسية
                    </a>
                    <a href="{{ route('buildings.buildings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('buildings.buildings.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('buildings.buildings.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-building-office class="w-5 h-5" />
                        </div>
                        إدارة المباني
                    </a>
                    <a href="{{ route('buildings.licenses.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('buildings.licenses.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('buildings.licenses.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-document-check class="w-5 h-5" />
                        </div>
                        التراخيص والتقارير
                    </a>
                    <a href="{{ route('buildings.economical.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('buildings.economical.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('buildings.economical.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-currency-dollar class="w-5 h-5" />
                        </div>
                        النشاط الاقتصادي
                    </a>
                    <a href="{{ route('buildings.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('buildings.customers.*') || request()->routeIs('buildings.subscriptions.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('buildings.customers.*') || request()->routeIs('buildings.subscriptions.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-users class="w-5 h-5" />
                        </div>
                        المشتركون والعملاء
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-600 hover:bg-gray-50 font-medium transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400">
                            <x-heroicon-o-cog-6-tooth class="w-5 h-5" />
                        </div>
                        الإعدادات
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
