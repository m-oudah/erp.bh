<x-portal-layout>
    <x-slot name="title">الوحدات السكنية والتجارية - بوابة المواطنين</x-slot>

    <div class="space-y-6"
         x-data="{
             filter: 'all',
             matches(usage) {
                 if (this.filter === 'all') return true;
                 if (this.filter === 'residential') return usage.includes('سكنية') || usage.includes('شقة');
                 if (this.filter === 'commercial') return usage.includes('تجاري') || usage.includes('محل') || usage.includes('صناعي');
                 return true;
             }
         }">

        {{-- Page Header --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-bold text-amber-700 mb-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                    <span>سجل الوحدات والشقق والمحلات</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">الوحدات السكنية والتجارية</h2>
                <p class="text-xs text-slate-500 mt-1">تفاصيل الشقق السكنية والمحلات والمخازن التابعة لعقاراتك</p>
            </div>

            {{-- Quick Filter Buttons --}}
            <div class="flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 text-xs font-bold shrink-0">
                <button type="button" 
                        @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    الكل ({{ $units->count() }})
                </button>
                <button type="button" 
                        @click="filter = 'residential'"
                        :class="filter === 'residential' ? 'bg-white text-teal-800 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    سكني ({{ $units->where('usage_label', 'وحدة سكنية (شقة)')->count() }})
                </button>
                <button type="button" 
                        @click="filter = 'commercial'"
                        :class="filter === 'commercial' ? 'bg-white text-amber-800 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    تجاري ({{ $units->where('usage_label', 'محل / تجاري')->count() }})
                </button>
            </div>
        </div>

        {{-- Units Grid --}}
        @if($units->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 shadow-sm space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700">لا توجد وحدات مسجلة باسمك حالياً</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    لم يتم العثور على وحدات سكنية أو تجارية مقيدة باسمك في سجلات البلدية.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach($units as $unit)
                    <div x-show="matches('{{ $unit['usage_label'] }}')"
                         x-transition
                         class="bg-white rounded-3xl p-5 border border-slate-200/90 shadow-sm hover:shadow-md hover:border-amber-400/60 transition-all duration-300 space-y-3 flex flex-col justify-between">
                        
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black {{ str_contains($unit['usage_label'], 'سكنية') ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                    {{ $unit['usage_label'] }}
                                </span>

                                <span class="text-xs font-mono font-bold text-slate-400">
                                    الطابق: {{ $unit['floor_number'] == 0 ? 'الأرضي' : $unit['floor_number'] }}
                                </span>
                            </div>

                            <h3 class="text-base font-black text-slate-900 leading-tight">
                                {{ $unit['unit_name'] }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">
                                وحدة رقم <b class="text-slate-800">{{ $unit['unit_number'] }}</b> • مبنى رقم <b class="text-slate-800">{{ $unit['building_number'] }}</b>
                            </p>
                        </div>

                        {{-- Details Box --}}
                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 text-xs space-y-1.5">
                            <div class="flex items-center justify-between text-slate-600">
                                <span class="text-slate-400 text-[11px]">الشارع والحي:</span>
                                <span class="font-bold text-slate-800 truncate max-w-[150px]">{{ $unit['street_name'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span class="text-slate-400 text-[11px]">طبيعة الملكية:</span>
                                <span class="font-bold text-slate-800">{{ $unit['ownership_type'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-slate-600">
                                <span class="text-slate-400 text-[11px]">حالة الإشغال:</span>
                                <span class="font-bold text-emerald-700">{{ $unit['current_situation'] }}</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-portal-layout>

