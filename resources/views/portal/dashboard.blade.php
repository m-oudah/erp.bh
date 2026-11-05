<x-portal-layout>
    <x-slot name="title">لوحة معلومات المواطن</x-slot>

    <div class="space-y-6">

        {{-- Welcome Profile Card --}}
        <div class="rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-teal-700/50"
             style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%); background-color: #0f4c47;">
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-teal-600/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute left-10 top-0 w-40 h-40 bg-amber-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-400 text-slate-950 uppercase tracking-wider">
                            حساب موثق عبر SMS OTP
                        </span>
                        <span class="text-xs text-teal-200">سجلات بلدية بيت حانون</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                        <span>أهلاً وسهلاً بك،</span>
                        <span class="text-amber-300">{{ $citizenName }}</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-teal-100 max-w-2xl leading-relaxed font-medium">
                        هذه البوابة مخصصة للاستعلام المباشر عن كافة الأصول العقارية والوحدات السكنية والتجارية والتراخيص المهنية المسجلة باسمك لدى البلدية.
                    </p>
                </div>

                {{-- Citizen Meta Badges --}}
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 bg-white/10 p-3 sm:p-4 rounded-2xl border border-white/15 backdrop-blur-xs">
                    <div class="px-3 py-1">
                        <div class="text-[10px] text-teal-200 font-bold uppercase">رقم الهوية</div>
                        <div class="font-mono text-base font-black text-amber-300">{{ $idCard }}</div>
                    </div>
                    <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                    <div class="px-3 py-1">
                        <div class="text-[10px] text-teal-200 font-bold uppercase">رقم الجوال</div>
                        <div class="font-mono text-base font-black text-white dir-ltr">{{ $citizenMobile }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistical Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            
            {{-- Buildings Card --}}
            <a href="{{ route('portal.buildings') }}"
               class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-teal-500/60 transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center group-hover:bg-teal-700 group-hover:text-white transition-all shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-full group-hover:bg-teal-100 transition-colors">
                            العقارات
                        </span>
                    </div>
                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $summary['buildings_count'] }}</div>
                    <h3 class="text-sm font-bold text-slate-700 mt-1">المباني والعقارات المسجلة</h3>
                    <p class="text-xs text-slate-500 mt-1">المباني التي تمتلكها أو تشترك في ملكيتها في بيت حانون</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-700 group-hover:text-teal-900">
                    <span>استعراض كافة المباني</span>
                    <span class="group-hover:-translate-x-1 transition-transform">←</span>
                </div>
            </a>

            {{-- Units Card --}}
            <a href="{{ route('portal.units') }}"
               class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-teal-500/60 transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-amber-800 bg-amber-50 px-2.5 py-1 rounded-full group-hover:bg-amber-100 transition-colors">
                            الوحدات
                        </span>
                    </div>
                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $summary['units_count'] }}</div>
                    <h3 class="text-sm font-bold text-slate-700 mt-1">الوحدات السكنية والتجارية</h3>
                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-1">
                        <span>{{ $summary['residential_units_count'] }} شقة سكنية</span>
                        <span>•</span>
                        <span>{{ $summary['commercial_units_count'] }} محل / تجاري</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-amber-800 group-hover:text-amber-950">
                    <span>استعراض قائمة الوحدات والشقق</span>
                    <span class="group-hover:-translate-x-1 transition-transform">←</span>
                </div>
            </a>

            {{-- Crafts Card --}}
            <a href="{{ route('portal.crafts') }}"
               class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm hover:shadow-xl hover:border-teal-500/60 transition-all duration-300 group flex flex-col justify-between sm:col-span-2 lg:col-span-1">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center group-hover:bg-emerald-700 group-hover:text-white transition-all shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-full group-hover:bg-emerald-100 transition-colors">
                            الحرف والمهن
                        </span>
                    </div>
                    <div class="text-3xl font-black text-slate-800 tracking-tight">{{ $summary['crafts_count'] }}</div>
                    <h3 class="text-sm font-bold text-slate-700 mt-1">الحرف والصناعات والمنشآت</h3>
                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                        <span class="text-emerald-700 font-bold">{{ $summary['licensed_crafts_count'] }} رخصة سارية</span>
                        @if($summary['crafts_count'] > $summary['licensed_crafts_count'])
                            <span class="text-slate-400">|</span>
                            <span class="text-amber-700">{{ $summary['crafts_count'] - $summary['licensed_crafts_count'] }} تحتاج تجديد أو ترخيص</span>
                        @endif
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-emerald-800 group-hover:text-emerald-950">
                    <span>استعراض سجل الحرف والمهن</span>
                    <span class="group-hover:-translate-x-1 transition-transform">←</span>
                </div>
            </a>

        </div>

        {{-- Overview Previews: Buildings & Crafts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Recent Buildings Preview --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-black text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>ملخص المباني المسجلة</span>
                    </h3>
                    <a href="{{ route('portal.buildings') }}" class="text-xs font-bold text-teal-700 hover:underline">
                        عرض الكل ({{ $summary['buildings_count'] }})
                    </a>
                </div>

                @if($summary['buildings']->isEmpty())
                    <div class="text-center py-8 text-slate-400 text-xs">
                        لا توجد مباني مسجلة باسم هذا المواطن حالياً في قاعدة بيانات البلدية.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($summary['buildings']->take(3) as $building)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-slate-900">مبنى رقم {{ $building['building_number'] }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-100 text-teal-800">
                                            {{ $building['building_use'] }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate">
                                        {{ $building['street_name'] }} • {{ $building['zone_name'] }}
                                    </p>
                                </div>
                                <div class="text-left shrink-0">
                                    <div class="text-xs font-black text-teal-800">{{ $building['units_count'] }} وحدة</div>
                                    <div class="text-[10px] text-slate-400">{{ $building['floors_count'] }} طوابق</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recent Crafts Preview --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-black text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span>ملخص الحرف والأنشطة الاقتصادية</span>
                    </h3>
                    <a href="{{ route('portal.crafts') }}" class="text-xs font-bold text-amber-700 hover:underline">
                        عرض الكل ({{ $summary['crafts_count'] }})
                    </a>
                </div>

                @if($summary['crafts']->isEmpty())
                    <div class="text-center py-8 text-slate-400 text-xs">
                        لا توجد منشآت أو رخص مهنية مسجلة باسم هذا المواطن.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($summary['crafts']->take(3) as $craft)
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-black text-slate-900 truncate">{{ $craft['trade_name'] }}</span>
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $craft['is_licensed'] ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $craft['is_licensed'] ? 'مرخص' : 'غير مرخص' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 truncate">
                                        {{ $craft['formal_name'] }} • {{ $craft['street_name'] }}
                                    </p>
                                </div>
                                <div class="text-left shrink-0">
                                    <div class="text-xs font-mono font-bold text-slate-700">{{ $craft['license_number'] ?: 'بدون رخصة' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $craft['sector_name'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Notice / Support Banner --}}
        <div class="p-4 sm:p-5 rounded-3xl bg-slate-50 border border-slate-200 text-slate-600 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-800 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-xs leading-relaxed font-medium">
                    <b class="text-slate-800 font-bold">هل هناك بيانات غير دقيقة أو مفقودة في سجلك؟</b><br>
                    يمكنك مراجعة قسم خدمات الجمهور في مقر بلدية بيت حانون لتقديم طلب تدقيق وتحديث البيانات، مصطحباً المستندات الثبوتية.
                </div>
            </div>
            <div class="text-xs text-slate-400 font-mono shrink-0">
                آخر تحديث للبيانات: {{ date('Y-m-d') }}
            </div>
        </div>

    </div>
</x-portal-layout>

