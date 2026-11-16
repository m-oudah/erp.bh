<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">المشتركين والعملاء</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                المشتركين والعملاء  
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="w-full pb-10">
        <div class="bg-white/60 backdrop-blur-sm border border-gray-100 rounded-3xl p-6 min-h-[500px] shadow-[0_2px_20px_-3px_rgba(6,81,237,0.05)]">
            {{ $slot }}
        </div>
    </div>
</x-app-layout>
