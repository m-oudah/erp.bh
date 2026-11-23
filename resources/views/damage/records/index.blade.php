<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    {{-- Sector Tabs --}}
    <div class="bg-white rounded-3xl p-3 border border-slate-200/80 shadow-xs mb-5 flex items-center gap-2 overflow-x-auto">
        @php
            $currentSector = request('sector');
            $tabs = [
                '' => ['name' => 'كافة القطاعات', 'count' => array_sum($sectorCounts)],
                'real_estate' => ['name' => 'المباني والعقارات', 'count' => $sectorCounts['real_estate'] ?? 0],
                'infrastructure' => ['name' => 'البنية التحتية والشبكات', 'count' => $sectorCounts['infrastructure'] ?? 0],
                'public_facilities' => ['name' => 'المرافق العامة والحيوية', 'count' => $sectorCounts['public_facilities'] ?? 0],
                'economic_crafts' => ['name' => 'القطاع الاقتصادي والحرفي', 'count' => $sectorCounts['economic_crafts'] ?? 0],
                'municipal_assets' => ['name' => 'أصول وممتلكات البلدية', 'count' => $sectorCounts['municipal_assets'] ?? 0],
            ];
        @endphp

        @foreach($tabs as $secKey => $tab)
            @php
                $isActive = ($secKey === '' && !$currentSector) || ($secKey === $currentSector);
                $url = $secKey ? route('damage.records.index', array_merge(request()->except(['page', 'sector']), ['sector' => $secKey])) : route('damage.records.index', request()->except(['page', 'sector']));
            @endphp
            <a href="{{ $url }}"
               class="px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-2 {{ $isActive ? 'bg-orange-600 text-white shadow-md shadow-orange-600/20' : 'bg-slate-50 hover:bg-slate-100 text-slate-700' }}">
                <span>{{ $tab['name'] }}</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                    {{ number_format($tab['count']) }}
                </span>
            </a>
        @endforeach
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs mb-6" x-data="{ expanded: false }">
        <form method="GET" action="{{ route('damage.records.index') }}">
            @if(request('sector'))
                <input type="hidden" name="sector" value="{{ request('sector') }}">
            @endif
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if(request('direction'))
                <input type="hidden" name="direction" value="{{ request('direction') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-3">
                {{-- Search Box --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">البحث السريع (الاسم، رقم السجل، الهوية، الحوض، القطعة)</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="أدخل كلمة البحث..." 
                               class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500/20 pr-9">
                        <div class="absolute right-3 top-2.5 text-slate-400">
                            <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                {{-- Damage Level --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">مستوى الضرر</label>
                    <select name="damage_level" class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500/20">
                        <option value="">كافة المستويات</option>
                        <option value="total_destruction" {{ request('damage_level') == 'total_destruction' ? 'selected' : '' }}>مهدم كلياً</option>
                        <option value="severe_partial" {{ request('damage_level') == 'severe_partial' ? 'selected' : '' }}>جزئي بليغ</option>
                        <option value="moderate_partial" {{ request('damage_level') == 'moderate_partial' ? 'selected' : '' }}>جزئي متوسط</option>
                        <option value="minor" {{ request('damage_level') == 'minor' ? 'selected' : '' }}>جزئي طفيف</option>
                    </select>
                </div>

                {{-- Workflow Status --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5">حالة الاعتماد</label>
                    <select name="workflow_status" class="w-full text-xs font-medium rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500/20">
                        <option value="">كافة الحالات</option>
                        <option value="draft" {{ request('workflow_status') == 'draft' ? 'selected' : '' }}>مسودة أولية</option>
                        <option value="engineering_review" {{ request('workflow_status') == 'engineering_review' ? 'selected' : '' }}>قيد المراجعة الهندسية</option>
                        <option value="final_approved" {{ request('workflow_status') == 'final_approved' ? 'selected' : '' }}>معتمد نهائياً</option>
                    </select>
                </div>

                {{-- Actions --}}
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5">
                        <x-heroicon-s-funnel class="w-4 h-4 text-orange-400" />
                        <span>تصفية</span>
                    </button>
                    <a href="{{ route('damage.records.export', request()->query()) }}" 
                       class="py-2.5 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-1.5 shrink-0" 
                       title="تصدير السجلات المعروضة حالياً حسب التصفية والفرز إلى ملف إكسل">
                        <x-heroicon-s-document-arrow-down class="w-4 h-4" />
                        <span>تصدير إكسل</span>
                    </a>
                    <a href="{{ route('damage.records.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all shrink-0" title="إعادة تعيين">
                        <x-heroicon-s-arrow-path class="w-4 h-4" />
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Records Table --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden mb-6">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="text-xs font-bold text-slate-600 flex items-center gap-2 flex-wrap">
                <span>عرض <span class="font-extrabold text-slate-900">{{ $records->firstItem() ?? 0 }}</span> إلى <span class="font-extrabold text-slate-900">{{ $records->lastItem() ?? 0 }}</span> من إجمالي <span class="font-extrabold text-orange-600">{{ number_format($records->total()) }}</span> سجل</span>
                @if(request('sort'))
                    <span class="text-slate-300">•</span>
                    <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/60">
                        فرز: {{ request('sort') }} ({{ request('direction') === 'asc' ? 'تصاعدي ↑' : 'تنازلي ↓' }})
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('damage.records.export', request()->query()) }}" 
                   class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 shadow-sm shadow-emerald-600/20"
                   title="تصدير السجلات المعروضة حالياً حسب التصفية والفرز إلى ملف إكسل">
                    <x-heroicon-s-document-arrow-down class="w-4 h-4" />
                    <span>تصدير إلى إكسل</span>
                </a>
                <a href="{{ route('damage.records.create') }}" class="px-3.5 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-1 shadow-sm">
                    <x-heroicon-s-plus class="w-4 h-4" />
                    <span>إدخال حصر جديد</span>
                </a>
            </div>
        </div>

        @php
            $currentSort = request('sort', 'id');
            $currentDir = request('direction', 'desc');
            $sortUrl = function($col) use ($currentSort, $currentDir) {
                $newDir = ($currentSort === $col && $currentDir === 'asc') ? 'desc' : 'asc';
                return route('damage.records.index', array_merge(request()->query(), ['sort' => $col, 'direction' => $newDir]));
            };
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-bold">
                    <tr>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('record_number') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>رقم السجل</span>
                                @if($currentSort === 'record_number')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5">
                            <a href="{{ $sortUrl('name') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>اسم المنشأة / العقار</span>
                                @if($currentSort === 'name')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('sector') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>القطاع</span>
                                @if($currentSort === 'sector')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5">الموقع والتنظيم</th>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('damage_level') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>مستوى الضرر</span>
                                @if($currentSort === 'damage_level')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('area_sqm') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>الكميات والمساحة</span>
                                @if($currentSort === 'area_sqm')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('estimated_cost_usd') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>التقدير المالي ($)</span>
                                @if($currentSort === 'estimated_cost_usd')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5 whitespace-nowrap">
                            <a href="{{ $sortUrl('workflow_status') }}" class="inline-flex items-center gap-1 hover:text-orange-600 transition-colors">
                                <span>حالة الاعتماد</span>
                                @if($currentSort === 'workflow_status')
                                    @if($currentDir === 'asc')
                                        <x-heroicon-s-chevron-up class="w-3.5 h-3.5 text-orange-600" />
                                    @else
                                        <x-heroicon-s-chevron-down class="w-3.5 h-3.5 text-orange-600" />
                                    @endif
                                @endif
                            </a>
                        </th>
                        <th class="p-3.5 text-center whitespace-nowrap">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($records as $r)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="p-3.5 font-mono font-bold text-orange-600 whitespace-nowrap">
                                <a href="{{ route('damage.records.show', $r->id) }}" class="hover:underline">
                                    {{ $r->record_number }}
                                </a>
                            </td>
                            <td class="p-3.5">
                                <a href="{{ route('damage.records.show', $r->id) }}" class="font-bold text-slate-900 hover:text-orange-600 block">
                                    {{ $r->name }}
                                </a>
                                @if($r->citizen_name)
                                    <div class="text-[11px] text-slate-500 font-normal mt-0.5 flex items-center gap-1">
                                        <span>مالك: {{ $r->citizen_name }}</span>
                                        @if($r->citizen_national_id)
                                            <span class="text-slate-400">({{ $r->citizen_national_id }})</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                @php
                                    $sectorBadges = [
                                        'real_estate' => ['name' => 'عقارات ومباني', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                        'infrastructure' => ['name' => 'بنية تحتية', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                        'public_facilities' => ['name' => 'مرافق عامة', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                        'economic_crafts' => ['name' => 'حرف وصناعات', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                        'municipal_assets' => ['name' => 'أصول بلدية', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                    ];
                                    $b = $sectorBadges[$r->sector] ?? ['name' => $r->sector, 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
                                @endphp
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $b['class'] }}">
                                    {{ $b['name'] }}
                                </span>
                            </td>
                            <td class="p-3.5 text-slate-600 whitespace-nowrap">
                                <div>{{ $r->zone ? $r->zone->zone_name : '-' }}</div>
                                @if($r->block_number || $r->parcel_number)
                                    <div class="text-[10px] text-slate-400 font-mono">حوض: {{ $r->block_number ?: '-' }} / ق: {{ $r->parcel_number ?: '-' }}</div>
                                @endif
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                @if($r->damage_level === 'total_destruction')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">مهدم كلياً</span>
                                @elseif($r->damage_level === 'severe_partial')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-orange-50 text-orange-700 border border-orange-200">جزئي بليغ</span>
                                @elseif($r->damage_level === 'moderate_partial')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200">جزئي متوسط</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">طفيف</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-slate-700 whitespace-nowrap">
                                @if($r->sector === 'infrastructure')
                                    <span class="font-bold">{{ number_format($r->length_meters, 0) }} م.ط</span>
                                    <div class="text-[10px] text-slate-400">عرض: {{ $r->width_meters }}م</div>
                                @else
                                    <span class="font-bold">{{ number_format($r->area_sqm, 0) }} م²</span>
                                    <div class="text-[10px] text-slate-400">{{ $r->floors_count }} طوابق • {{ $r->units_count }} وحدة</div>
                                @endif
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                <div class="font-black text-slate-900">${{ number_format($r->estimated_cost_usd, 0) }}</div>
                                <div class="text-[10px] font-medium text-slate-400">₪{{ number_format($r->estimated_cost_ils, 0) }}</div>
                            </td>
                            <td class="p-3.5 whitespace-nowrap">
                                @if($r->workflow_status === 'final_approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> معتمد نهائياً
                                    </span>
                                @elseif($r->workflow_status === 'engineering_review')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> قيد المراجعة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        مسودة
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('damage.records.show', $r->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors" title="عرض التفاصيل">
                                        <x-heroicon-s-eye class="w-4 h-4" />
                                    </a>
                                    <a href="{{ route('damage.records.certificate', $r->id) }}" target="_blank" class="p-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 transition-colors" title="إفادة حصر الأضرار الإلكترونية">
                                        <x-heroicon-s-qr-code class="w-4 h-4" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400">
                                لا توجد سجلات تطابق شروط البحث المختارة
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
