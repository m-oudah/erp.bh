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

    <div class="max-w-7xl mx-auto pb-10 space-y-5">

        {{-- Horizontal Tab Navigation --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.06)] px-4 py-2 flex items-center gap-1 overflow-x-auto">

            <a href="{{ route('buildings.dashboard') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.dashboard')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.dashboard') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                </div>
                الرئيسية
            </a>

            <a href="{{ route('buildings.buildings.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.buildings.*')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.buildings.*') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-building-office class="w-4 h-4" />
                </div>
                إدارة المباني
            </a>

            <a href="{{ route('buildings.licenses.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.licenses.*')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.licenses.*') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-document-check class="w-4 h-4" />
                </div>
                التراخيص والتقارير
            </a>

            <a href="{{ route('buildings.streets.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('buildings.streets.*')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('buildings.streets.*') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-map-pin class="w-4 h-4" />
                </div>
                الشوارع
            </a>

        </div>

        {{-- Main Content – Full Width --}}
        <div class="bg-white/60 backdrop-blur-sm border border-gray-100 rounded-3xl p-6 min-h-[500px] shadow-[0_2px_20px_-3px_rgba(6,81,237,0.05)]">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
