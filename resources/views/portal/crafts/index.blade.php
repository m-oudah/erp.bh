<x-portal-layout>
    <x-slot name="title">الحرف والصناعات والأنشطة الاقتصادية - بوابة المواطنين</x-slot>

    <div class="space-y-6"
         x-data="{
             statusFilter: 'all',
             matches(isLicensed) {
                 if (this.statusFilter === 'all') return true;
                 if (this.statusFilter === 'licensed') return isLicensed === '1';
                 if (this.statusFilter === 'unlicensed') return isLicensed === '0';
                 return true;
             }
         }">

        {{-- Page Header --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-bold text-emerald-700 mb-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>سجل الرخص والأنشطة الاقتصادية</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">الحرف والمهن والأنشطة الاقتصادية</h2>
                <p class="text-xs text-slate-500 mt-1">كافة المنشآت والمحلات والورش المهنية المسجلة باسمك في بلدية بيت حانون</p>
            </div>

            {{-- Quick Filter Buttons --}}
            <div class="flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 text-xs font-bold shrink-0">
                <button type="button" 
                        @click="statusFilter = 'all'"
                        :class="statusFilter === 'all' ? 'bg-white text-slate-900 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    الكل ({{ $crafts->count() }})
                </button>
                <button type="button" 
                        @click="statusFilter = 'licensed'"
                        :class="statusFilter === 'licensed' ? 'bg-white text-emerald-800 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    مرخص ({{ $crafts->where('is_licensed', true)->count() }})
                </button>
                <button type="button" 
                        @click="statusFilter = 'unlicensed'"
                        :class="statusFilter === 'unlicensed' ? 'bg-white text-rose-800 shadow-sm font-black' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                    غير مرخص ({{ $crafts->where('is_licensed', false)->count() }})
                </button>
            </div>
        </div>

        {{-- Crafts Grid --}}
        @if($crafts->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 shadow-sm space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700">لا توجد سجلات حرف أو مهن مسجلة باسمك</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    لم تسجل أي أنشطة تجارية أو مهنية أو صناعية باسم صاحب هذه الهوية في قيود البلدية.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                @foreach($crafts as $craft)
                    <div x-show="matches('{{ $craft['is_licensed'] ? '1' : '0' }}')"
                         x-transition
                         class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-lg hover:border-emerald-400/50 transition-all duration-300 space-y-4 flex flex-col justify-between">
                        
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div>
                                    <h3 class="text-lg font-black text-slate-900 leading-tight">
                                        {{ $craft['trade_name'] }}
                                    </h3>
                                    <div class="text-xs text-emerald-800 font-bold mt-1">
                                        {{ $craft['formal_name'] }}
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                    <span class="px-2.5 py-1 rounded-lg text-[11px] font-black {{ $craft['is_licensed'] ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                        {{ $craft['is_licensed'] ? '✓ مرخص رسمياً' : '✕ غير مرخص' }}
                                    </span>

                                    @if($craft['is_danger'])
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900">
                                            ⚠️ حرفة مصنفة خطرة
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Detailed Metadata Grid --}}
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100 my-3">
                                <div>
                                    <span class="text-slate-400 block text-[10px] font-semibold">رقم الرخصة المهنية</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $craft['license_number'] ?: 'بدون رخصة' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] font-semibold">رقم ملف الحرفة</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $craft['file_number'] ?: $craft['craft_number'] ?: 'غير محدد' }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-slate-400 block text-[10px] font-semibold">القطاع الاقتصادي</span>
                                    <span class="font-bold text-slate-800">{{ $craft['sector_name'] }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-slate-400 block text-[10px] font-semibold">الموقع والشارع</span>
                                    <span class="font-bold text-slate-800 truncate block">{{ $craft['street_name'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-500 font-medium">
                                حالة العمل: <b class="text-slate-800">{{ $craft['work_state'] }}</b>
                            </span>
                            @if(!$craft['is_licensed'])
                                <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">
                                    يرجى مراجعة قسم الحرف للترخيص
                                </span>
                            @else
                                <span class="text-[11px] font-bold text-emerald-700">
                                    مسجل ومعتمد في البلدية
                                </span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</x-portal-layout>

