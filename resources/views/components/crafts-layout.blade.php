<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">الحرف والصناعات</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                نظام الحرف والصناعات
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10 space-y-5">

        {{-- Horizontal Tab Navigation --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.06)] px-4 py-2 flex items-center gap-1"
             id="crafts-tabs-header"
             style="-ms-overflow-style:none; scrollbar-width:none; overflow-x:auto;">
            <style>#crafts-tabs-header::-webkit-scrollbar{display:none}</style>

            <a href="{{ route('crafts.dashboard') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('crafts.dashboard')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('crafts.dashboard') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                </div>
                الرئيسية
            </a>

            <a href="{{ route('crafts.economical.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('crafts.economical.*')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('crafts.economical.*') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-currency-dollar class="w-4 h-4" />
                </div>
                الأنشطة الاقتصادية
            </a>

            <a href="{{ route('crafts.crafts.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                      {{ request()->routeIs('crafts.crafts.*')
                            ? 'bg-orange/10 text-orange-dark shadow-sm'
                            : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800' }}">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0
                            {{ request()->routeIs('crafts.crafts.*') ? 'bg-orange text-white' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-wrench-screwdriver class="w-4 h-4" />
                </div>
                الحرف
            </a>

        </div>

        {{-- Main Content – Full Width --}}
        <div class="bg-white/60 backdrop-blur-sm border border-gray-100 rounded-3xl p-6 min-h-[500px] shadow-[0_2px_20px_-3px_rgba(6,81,237,0.05)]">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
