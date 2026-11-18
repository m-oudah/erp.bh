<x-buildings-layout>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="bg-secondary/10 text-secondary-dark text-xs px-3 py-1 rounded-xl font-extrabold border border-secondary/20">
                        نظام الوحدات العقارية
                    </span>
                    <span class="text-xs text-gray-400 font-bold">•</span>
                    <span class="text-xs text-gray-500 font-bold">إجمالي: {{ number_format($units->total()) }} وحدة</span>
                </div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-1">سجل الوحدات العقارية</h1>
                <p class="text-xs text-gray-500 mt-1">
                    استعراض واستعلام وإدارة الوحدات المسجلة في البلدية، وربطها بالمباني وملاكها واستخداماتها المعتمدة
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('buildings.units.create') }}"
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-orange to-orange-dark hover:from-orange-dark hover:to-orange text-white font-black py-2.5 px-5 rounded-2xl shadow-md shadow-orange/25 hover:shadow-orange/40 transition-all text-xs cursor-pointer">
                    <x-heroicon-s-plus class="w-4 h-4" />
                    <span>إضافة وحدة جديدة</span>
                </a>
            </div>
        </div>

        {{-- Summary Cards Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            {{-- 1. Total Units --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-2xl p-4 border border-blue-100/80 shadow-2xs">
                <div class="flex items-center justify-between text-blue-700 mb-2">
                    <span class="text-[11px] font-extrabold">إجمالي الوحدات</span>
                    <div class="w-7 h-7 rounded-lg bg-blue-500 text-white flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-squares-plus class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-blue-950">{{ number_format($stats['total_units']) }}</div>
                <div class="text-[10px] text-blue-600/80 font-bold mt-0.5">وحدة مسجلة بالكامل</div>
            </div>

            {{-- 2. Residential --}}
            <a href="{{ route('buildings.units.index', ['usage' => 'residential']) }}"
               class="bg-gradient-to-br from-emerald-50 to-teal-50/50 rounded-2xl p-4 border border-emerald-100/80 shadow-2xs hover:-translate-y-0.5 transition-transform block">
                <div class="flex items-center justify-between text-emerald-700 mb-2">
                    <span class="text-[11px] font-extrabold">وحدات سكنية</span>
                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-home class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-950">{{ number_format($stats['residential']) }}</div>
                <div class="text-[10px] text-emerald-600/80 font-bold mt-0.5">شقق ومنازل</div>
            </a>

            {{-- 3. Commercial --}}
            <a href="{{ route('buildings.units.index', ['usage' => 'commercial']) }}"
               class="bg-gradient-to-br from-amber-50 to-orange/10 rounded-2xl p-4 border border-amber-200/80 shadow-2xs hover:-translate-y-0.5 transition-transform block">
                <div class="flex items-center justify-between text-amber-800 mb-2">
                    <span class="text-[11px] font-extrabold">وحدات تجارية</span>
                    <div class="w-7 h-7 rounded-lg bg-orange text-white flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-building-storefront class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-950">{{ number_format($stats['commercial']) }}</div>
                <div class="text-[10px] text-amber-700/80 font-bold mt-0.5">محلات ومخازن</div>
            </a>

            {{-- 4. Industrial --}}
            <a href="{{ route('buildings.units.index', ['usage' => 'industrial']) }}"
               class="bg-gradient-to-br from-purple-50 to-violet-50/50 rounded-2xl p-4 border border-purple-100/80 shadow-2xs hover:-translate-y-0.5 transition-transform block">
                <div class="flex items-center justify-between text-purple-700 mb-2">
                    <span class="text-[11px] font-extrabold">وحدات صناعية</span>
                    <div class="w-7 h-7 rounded-lg bg-purple-600 text-white flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-cog-6-tooth class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-purple-950">{{ number_format($stats['industrial']) }}</div>
                <div class="text-[10px] text-purple-600/80 font-bold mt-0.5">ورش ومصانع</div>
            </a>

            {{-- 5. Other Usages --}}
            <a href="{{ route('buildings.units.index', ['usage' => 'other']) }}"
               class="bg-gradient-to-br from-slate-50 to-gray-100/80 rounded-2xl p-4 border border-slate-200 shadow-2xs hover:-translate-y-0.5 transition-transform block col-span-2 sm:col-span-1">
                <div class="flex items-center justify-between text-slate-700 mb-2">
                    <span class="text-[11px] font-extrabold">استخدامات أخرى</span>
                    <div class="w-7 h-7 rounded-lg bg-slate-600 text-white flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-building-office-2 class="w-4 h-4" />
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900">{{ number_format($stats['other_usages']) }}</div>
                <div class="text-[10px] text-slate-500 font-bold mt-0.5">تعليمي، صحي، ديني...</div>
            </a>
        </div>

        {{-- Search & Filters Card --}}
        @php
            $hasActiveFilters = request()->anyFilled([
                'search', 'unit_number', 'building_number', 'street_number',
                'floor_number', 'unit_type', 'usage', 'id_card', 'owner_name'
            ]);
        @endphp

        <div x-data="{ showFilters: {{ $hasActiveFilters ? 'true' : 'false' }} }" class="bg-white rounded-3xl border border-gray-200/80 shadow-xs overflow-hidden">
            {{-- Filter Bar Header --}}
            <div class="flex items-center justify-between px-5 py-3.5 bg-gray-50/70 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div @click="showFilters = !showFilters"
                         class="flex items-center gap-2 cursor-pointer select-none group py-1">
                        <div class="w-7 h-7 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center group-hover:scale-105 transition-transform shadow-2xs">
                            <x-heroicon-s-funnel class="w-4 h-4" />
                        </div>
                        <span class="text-xs font-black text-gray-800 group-hover:text-orange-dark transition-colors">
                            خيارات البحث والاستعلام عن الوحدات
                        </span>
                    </div>

                    @if($hasActiveFilters)
                    <a href="{{ route('buildings.units.index') }}"
                       class="text-[11px] text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1 bg-rose-50 hover:bg-rose-100 px-3 py-1 rounded-xl border border-rose-200 transition-colors">
                        <x-heroicon-s-x-mark class="w-3.5 h-3.5" />
                        <span>مسح الفلاتر</span>
                    </a>
                    @endif
                </div>

                <button type="button"
                        @click="showFilters = !showFilters"
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all border shadow-2xs cursor-pointer"
                        :class="showFilters ? 'bg-orange/10 text-orange-dark border-orange/30' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-200'">
                    <x-heroicon-s-adjustments-horizontal class="w-4 h-4" />
                    <span x-text="showFilters ? 'إخفاء الخيارات' : 'عرض الخيارات المتقدمة'"></span>
                    <span class="inline-flex transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''">
                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5" />
                    </span>
                </button>
            </div>

            {{-- Filter Form --}}
            <form action="{{ route('buildings.units.index') }}" method="GET" class="p-5 space-y-4" id="unit-filter-form">
                {{-- Main Search Input --}}
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                        <x-heroicon-s-magnifying-glass class="w-5 h-5 text-gray-400" />
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="ابحث بالاسم، رقم الوحدة، رقم المبنى، رقم الملف الأرشيفي، اسم المالك، أو رقم الهوية..."
                           class="w-full pr-11 pl-28 py-3 bg-gray-50/70 border border-gray-200 rounded-2xl text-xs font-bold text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-orange focus:ring-2 focus:ring-orange/20 transition-all outline-none">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center gap-1.5">
                        <button type="submit"
                                class="px-4 py-1.5 bg-gradient-to-r from-orange to-orange-dark hover:from-orange-dark hover:to-orange text-white text-xs font-black rounded-xl shadow-xs transition-all cursor-pointer">
                            بحث فوري
                        </button>
                    </div>
                </div>

                {{-- Advanced Filters Panel --}}
                <div x-show="showFilters" x-transition class="pt-3 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Usage Filter --}}
                    <div>
                        <label for="filter_usage" class="block text-xs font-bold text-gray-700 mb-1">طبيعة الاستخدام</label>
                        <select id="filter_usage" name="usage"
                                class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            <option value="">— كافة الاستخدامات —</option>
                            <option value="residential" {{ request('usage') == 'residential' ? 'selected' : '' }}>سكني</option>
                            <option value="commercial" {{ request('usage') == 'commercial' ? 'selected' : '' }}>تجاري</option>
                            <option value="industrial" {{ request('usage') == 'industrial' ? 'selected' : '' }}>صناعي</option>
                            <option value="educational" {{ request('usage') == 'educational' ? 'selected' : '' }}>تعليمي</option>
                            <option value="health" {{ request('usage') == 'health' ? 'selected' : '' }}>صحي</option>
                            <option value="religous" {{ request('usage') == 'religous' ? 'selected' : '' }}>ديني</option>
                            <option value="other" {{ request('usage') == 'other' ? 'selected' : '' }}>استخدامات أخرى</option>
                        </select>
                    </div>

                    {{-- Unit Number --}}
                    <div>
                        <label for="filter_unit_number" class="block text-xs font-bold text-gray-700 mb-1">رقم الوحدة</label>
                        <input type="text" id="filter_unit_number" name="unit_number"
                               value="{{ request('unit_number') }}"
                               placeholder="مثال: 1 أو 2..."
                               class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                    </div>

                    {{-- Building Number --}}
                    <div>
                        <label for="filter_building_number" class="block text-xs font-bold text-gray-700 mb-1">رقم المبنى</label>
                        <input type="text" id="filter_building_number" name="building_number"
                               value="{{ request('building_number') }}"
                               placeholder="مثال: 8 أو 4435..."
                               class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                    </div>

                    {{-- Owner ID Card --}}
                    <div>
                        <label for="filter_id_card" class="block text-xs font-bold text-gray-700 mb-1">رقم هوية المالك</label>
                        <input type="text" id="filter_id_card" name="id_card"
                               value="{{ request('id_card') }}"
                               placeholder="مثال: 908980817"
                               class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all"
                               dir="ltr">
                    </div>

                    {{-- Owner Name --}}
                    <div>
                        <label for="filter_owner_name" class="block text-xs font-bold text-gray-700 mb-1">اسم المالك</label>
                        <input type="text" id="filter_owner_name" name="owner_name"
                               value="{{ request('owner_name') }}"
                               placeholder="اسم المالك أو العائلة..."
                               class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                    </div>

                    {{-- Floor Number --}}
                    <div>
                        <label for="filter_floor_number" class="block text-xs font-bold text-gray-700 mb-1">رقم الطابق</label>
                        <select id="filter_floor_number" name="floor_number"
                                class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            <option value="">— كافة الطوابق —</option>
                            <option value="0" {{ request('floor_number') === '0' ? 'selected' : '' }}>الطابق الأرضي (0)</option>
                            <option value="1" {{ request('floor_number') === '1' ? 'selected' : '' }}>الطابق الأول (1)</option>
                            <option value="2" {{ request('floor_number') === '2' ? 'selected' : '' }}>الطابق الثاني (2)</option>
                            <option value="3" {{ request('floor_number') === '3' ? 'selected' : '' }}>الطابق الثالث (3)</option>
                            <option value="4" {{ request('floor_number') === '4' ? 'selected' : '' }}>الطابق الرابع (4)</option>
                            <option value="5" {{ request('floor_number') === '5' ? 'selected' : '' }}>الطابق الخامس (5)</option>
                        </select>
                    </div>

                    {{-- Street Number --}}
                    <div>
                        <label for="filter_street_number" class="block text-xs font-bold text-gray-700 mb-1">الشارع</label>
                        <select id="filter_street_number" name="street_number"
                                class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            <option value="">— كافة الشوارع —</option>
                            @foreach($streets as $st)
                                <option value="{{ $st->id }}" {{ request('street_number') == $st->id ? 'selected' : '' }}>
                                    {{ $st->street_name ?: ('شارع #' . $st->street_number) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl text-xs transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                            <x-heroicon-s-funnel class="w-4 h-4" />
                            <span>تطبيق الفلاتر</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Units Table & Cards --}}
        <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs overflow-hidden">
            @if($units->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-gray-50/90 text-gray-600 font-extrabold border-b border-gray-200/80">
                        <tr>
                            <th class="px-4 py-3.5">الوحدة العقارية</th>
                            <th class="px-4 py-3.5">المبنى التابعة له</th>
                            <th class="px-4 py-3.5">استخدامات الوحدة</th>
                            <th class="px-4 py-3.5">ملاك الوحدة / المبنى</th>
                            <th class="px-4 py-3.5 text-center">الطابق</th>
                            <th class="px-4 py-3.5 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($units as $unit)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            {{-- 1. Unit Info --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-700 flex items-center justify-center font-black text-xs shrink-0 shadow-2xs border border-blue-100">
                                        #{{ $unit->unit_number ?: $unit->id }}
                                    </div>
                                    <div>
                                        <a href="{{ route('buildings.units.show', $unit->id) }}"
                                           class="font-black text-gray-900 hover:text-orange text-xs block transition-colors">
                                            {{ $unit->display_name }}
                                        </a>
                                        <div class="flex items-center gap-2 mt-0.5 text-[10px] text-gray-400 font-medium">
                                            <span class="font-mono">معرف #{{ $unit->id }}</span>
                                            @if($unit->system_number)
                                                <span>•</span>
                                                <span class="font-mono">نظام: {{ $unit->system_number }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Building Info (المبنى التابعة له) --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if($unit->building)
                                <div class="space-y-1">
                                    <a href="{{ route('buildings.buildings.show', $unit->building_id) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-black text-orange-dark hover:underline">
                                        <x-heroicon-s-building-office class="w-3.5 h-3.5 text-orange shrink-0" />
                                        <span>مبنى {{ $unit->building->display_name }}</span>
                                    </a>
                                    <div class="text-[10px] text-gray-500 flex items-center gap-2">
                                        @if($unit->building->file_number)
                                            <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-bold" dir="ltr">
                                                {{ $unit->building->file_number }}
                                            </span>
                                        @endif
                                        @if($unit->building->street)
                                            <span>• {{ $unit->building->street->street_name }}</span>
                                        @endif
                                    </div>
                                </div>
                                @else
                                <span class="text-gray-400 text-xs">غير محدد</span>
                                @endif
                            </td>

                            {{-- 3. Usages (استخدامات الوحدة) --}}
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @forelse($unit->usages_list as $usage)
                                        @php
                                            $badgeColor = match($usage) {
                                                'سكني' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'تجاري' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'صناعي' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'تعليمي' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                                'صحي' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                'ديني / مسجد' => 'bg-teal-50 text-teal-700 border-teal-200',
                                                default => 'bg-amber-50 text-amber-800 border-amber-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black border {{ $badgeColor }}">
                                            {{ $usage }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-[11px]">—</span>
                                    @endforelse

                                    @if($unit->profession_name)
                                        <span class="inline-flex items-center gap-1 text-[10px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded-lg font-bold">
                                            <x-heroicon-s-tag class="w-3 h-3 text-gray-400" />
                                            {{ $unit->profession_name }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- 4. Owners (ملاك الوحدة / المبنى) --}}
                            <td class="px-4 py-3.5">
                                @php
                                    $owners = $unit->all_owners;
                                @endphp
                                @if($owners->isNotEmpty())
                                <div class="space-y-1">
                                    @foreach($owners->take(2) as $owner)
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-s-user class="w-3.5 h-3.5 text-gray-400 shrink-0" />
                                        <span class="font-bold text-gray-800 text-xs">{{ $owner->full_name }}</span>
                                        @if(!empty($owner->id_card))
                                        <span class="font-mono text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded" dir="ltr">
                                            {{ $owner->id_card }}
                                        </span>
                                        @endif
                                    </div>
                                    @endforeach
                                    @if($owners->count() > 2)
                                        <div class="text-[10px] text-orange font-bold">+ {{ $owners->count() - 2 }} ملاك آخرين</div>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400 text-xs">لا يوجد ملاك مسجلون</span>
                                @endif
                            </td>

                            {{-- 5. Floor --}}
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-gray-100 text-gray-700 text-[11px] font-bold">
                                    <x-heroicon-s-home class="w-3 h-3 text-gray-400" />
                                    {{ $unit->floor ? $unit->floor->floor_name : ('طابق ' . ($unit->floor_number ?? '0')) }}
                                </span>
                            </td>

                            {{-- 6. Actions --}}
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('buildings.units.show', $unit->id) }}"
                                       class="p-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors shadow-2xs"
                                       title="عرض التفاصيل الكاملة">
                                        <x-heroicon-s-eye class="w-4 h-4" />
                                    </a>

                                    <a href="{{ route('buildings.units.edit', $unit->id) }}"
                                       class="p-2 rounded-xl bg-orange/10 text-orange-dark hover:bg-orange/20 transition-colors shadow-2xs"
                                       title="تعديل الوحدة">
                                        <x-heroicon-s-pencil-square class="w-4 h-4" />
                                    </a>

                                    <form action="{{ route('buildings.units.destroy', $unit->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors shadow-2xs cursor-pointer"
                                                title="حذف الوحدة">
                                            <x-heroicon-s-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $units->links() }}
            </div>

            @else
            {{-- Empty State --}}
            <div class="p-16 text-center space-y-3">
                <div class="w-16 h-16 rounded-3xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto shadow-inner">
                    <x-heroicon-o-squares-plus class="w-8 h-8" />
                </div>
                <div class="text-base font-black text-gray-800">لم يتم العثور على أي وحدات مطابقة للبحث</div>
                <p class="text-xs text-gray-400 max-w-md mx-auto">
                    يرجى تعديل معايير البحث أو تصفية النتائج، أو إضافة وحدة عقارية جديدة في النظام.
                </p>
                <div class="pt-2 flex items-center justify-center gap-2">
                    @if($hasActiveFilters)
                    <a href="{{ route('buildings.units.index') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition">
                        إعادة تعيين الفلاتر
                    </a>
                    @endif
                    <a href="{{ route('buildings.units.create') }}"
                       class="px-4 py-2 bg-orange text-white rounded-xl text-xs font-bold hover:bg-orange-dark transition shadow-sm">
                        إضافة وحدة جديدة
                    </a>
                </div>
            </div>
            @endif
        </div>

    </div>
</x-buildings-layout>
