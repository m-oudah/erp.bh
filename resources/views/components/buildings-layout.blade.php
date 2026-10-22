<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">التنظيم والأبنية</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام التنظيم والأبنية الموحد
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10 space-y-5">

        {{-- Horizontal Tab Navigation --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-4 py-2 flex items-center gap-1 overflow-x-auto">

            <a href="{{ route('buildings.dashboard') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.dashboard')
                            ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                            : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.dashboard') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                    <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                </div>
                الرئيسية
            </a>

            <a href="{{ route('buildings.buildings.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.buildings.*')
                            ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                            : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.buildings.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                    <x-heroicon-o-building-office class="w-4 h-4" />
                </div>
                إدارة المباني
            </a>

            <a href="{{ route('buildings.licenses.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.licenses.*')
                            ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                            : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.licenses.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                    <x-heroicon-o-document-check class="w-4 h-4" />
                </div>
                التراخيص والتقارير
            </a>

            <a href="{{ route('buildings.streets.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.streets.*')
                            ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                            : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.streets.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                </div>
                الشوارع
            </a>

        </div>

        {{-- Main Content – Full Width --}}
        <div class="bg-white border border-gray-100 rounded-3xl p-6 min-h-[500px] shadow-sm">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
