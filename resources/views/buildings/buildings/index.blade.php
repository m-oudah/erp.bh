<x-buildings-layout>
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">إدارة المباني</h1>
                <p class="text-sm text-gray-500 mt-1">البحث والتصفح وإدارة بيانات المباني في البلدية</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-2">
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        إجمالي: {{ $buildings->total() }} مبنى
                    </span>
                </div>
                @can('bhm.buildings.create')
                <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-all text-sm">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    إضافة مبنى
                </a>
                @endcan
            </div>
        </div>

        {{-- Search & Filter Card --}}
        <div x-data="{ showFilters: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50/70 transition-colors"
                 :class="showFilters ? 'border-b border-gray-100' : ''">
                <div class="flex items-center gap-3">
                    <x-heroicon-o-funnel class="w-4 h-4 text-black" />
                    <span class="text-sm font-bold text-black">خيارات البحث والتصفية</span>
                    @if(request()->anyFilled(['building_number', 'file_number', 'id_card', 'owner_name', 'zone_id', 'street_id', 'street_number', 'building_type_id', 'block_number', 'parcel_number']))
                    <a href="{{ route('buildings.buildings.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                        <x-heroicon-o-x-mark class="w-3.5 h-3.5" /> مسح الفلاتر
                    </a>
                    @endif
                </div>

                {{-- زر عرض/إخفاء الخيارات على اليسار تماما --}}
                <button type="button"
                        @click="showFilters = !showFilters"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 border shadow-sm cursor-pointer"
                        :class="showFilters ? 'bg-orange/10 text-orange-dark border-orange/30' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-200'">
                    <x-heroicon-o-adjustments-horizontal class="w-4 h-4" />
                    <span x-text="showFilters ? 'إخفاء الخيارات' : 'عرض الخيارات'">عرض الخيارات</span>
                    <span class="inline-flex transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''">
                        <x-heroicon-o-chevron-down class="w-3.5 h-3.5" />
                    </span>
                </button>
            </div>

            <div x-show="showFilters"
                 x-cloak
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2">
                <form action="{{ route('buildings.buildings.index') }}" method="GET" class="p-5" id="building-filter-form">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">رقم المبنى</label>
                        <input type="text" name="building_number" value="{{ request('building_number') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="مثال: 125">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">رقم الملف (حاسوب)</label>
                        <input type="text" name="file_number" value="{{ request('file_number') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم الملف المحوسب">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">رقم القطعة (قسيمة)</label>
                        <input type="text" name="parcel_number" value="{{ request('parcel_number') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم القسيمة">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">رقم القطعة</label>
                        <input type="text" name="block_number" value="{{ request('block_number') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم القطعة">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">رقم هوية المالك</label>
                        <input type="text" name="id_card" value="{{ request('id_card') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم الهوية">
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-black mb-1.5">اسم المالك</label>
                        <input type="text" name="owner_name" value="{{ request('owner_name') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="اسم المالك أو الشريك">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">الحي</label>
                        <select name="zone_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                onchange="document.getElementById('building-filter-form').submit()">
                            <option value="">كل الأحياء</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" @selected(request('zone_id') == $zone->id)>{{ $zone->name ?: 'مجمع ' . $zone->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">الشارع</label>
                        <select name="street_number" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                            <option value="">كل الشوارع</option>
                            @foreach($streets as $street)
                                <option value="{{ $street->street_number }}" @selected(request('street_number') == $street->street_number || request('street_id') == $street->street_number)>
                                    {{ $street->street_name ? $street->street_name . ' (' . $street->street_number . ')' : 'شارع ' . $street->street_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-black mb-1.5">نوع المبنى</label>
                        <select name="building_type_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                onchange="document.getElementById('building-filter-form').submit()">
                            <option value="">كل الأنواع</option>
                            @foreach($buildingTypes as $type)
                                <option value="{{ $type->id }}" @selected(request('building_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1 flex items-end">
                        <button type="submit"
                                class="w-full bg-navy hover:bg-navy-dark text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                            بحث واستعلام
                        </button>
                    </div>

                </div>
            </form>
            </div>
        </div>

        {{-- Active Filters Tags --}}
        @if(request()->anyFilled(['building_number', 'file_number', 'id_card', 'owner_name', 'zone_id', 'street_id', 'street_number', 'building_type_id', 'block_number', 'parcel_number']))
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-black font-bold">فلاتر نشطة:</span>
            @if(request('building_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">رقم المبنى: {{ request('building_number') }}</span>
            @endif
            @if(request('file_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">رقم الملف: {{ request('file_number') }}</span>
            @endif
            @if(request('parcel_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">القسيمة: {{ request('parcel_number') }}</span>
            @endif
            @if(request('block_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">القطعة: {{ request('block_number') }}</span>
            @endif
            @if(request('id_card'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الهوية: {{ request('id_card') }}</span>
            @endif
            @if(request('owner_name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">المالك: {{ request('owner_name') }}</span>
            @endif
            @if(request('zone_id'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الحي: {{ $zones->firstWhere('id', request('zone_id'))->name ?? request('zone_id') }}</span>
            @endif
            @if(request('street_number') || request('street_id'))
                @php
                    $val = request('street_number') ?: request('street_id');
                    $matchedStreet = $streets->first(fn($s) => $s->street_number == $val || $s->id == $val);
                @endphp
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الشارع: {{ $matchedStreet ? ($matchedStreet->street_name ? $matchedStreet->street_name . ' (' . $matchedStreet->street_number . ')' : 'شارع ' . $matchedStreet->street_number) : 'شارع ' . $val }}</span>
            @endif
            @if(request('building_type_id'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">النوع: {{ $buildingTypes->firstWhere('id', request('building_type_id'))->name ?? request('building_type_id') }}</span>
            @endif
        </div>
        @endif

        {{-- Data Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-black font-bold text-xs border-b border-gray-200">
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">رقم الملف التنظيمي</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">المالك الرئيسي</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">رقم القطعة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">رقم القسيمة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">النوع</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center text-black font-bold">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($buildings as $building)
                        <tr class="hover:bg-blue-50/20 transition-colors group">

                            {{-- File Number --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(filled($building->file_number) && trim($building->file_number) !== '0')
                                    <a href="{{ route('buildings.buildings.show', $building->id) }}" class="font-bold text-navy-dark hover:text-orange transition-colors text-base leading-snug">
                                        {{ $building->file_number }}
                                    </a>
                                @else
                                    <a href="{{ route('buildings.buildings.show', $building->id) }}" class="font-semibold text-gray-400 hover:text-orange transition-colors text-sm leading-snug">
                                        (غير موجود)
                                    </a>
                                @endif
                            </td>

                            {{-- Owner --}}
                            <td class="px-5 py-4">
                                @if($building->owners->count())
                                    <div class="font-semibold text-gray-800 leading-snug">{{ $building->owners->first()->full_name }}</div>
                                    @if($building->owners->count() > 1)
                                    <span class="text-[10px] text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full font-bold mt-1 inline-block">
                                        + {{ $building->owners->count() - 1 }} شريك
                                    </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">غير محدد</span>
                                @endif
                            </td>

                            {{-- Block --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(filled($building->block_number))
                                <span class="text-[11px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded font-medium">{{ $building->block_number }}</span>
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Parcel --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(filled($building->parcel_number))
                                <span class="text-[11px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded font-medium">{{ $building->parcel_number }}</span>
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>

                            

                            {{-- Type --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700">
                                    {{ $building->buildingType->name ?? 'غير محدد' }}
                                </span>
                                @if(filled($building->creation_date) && $building->creation_date !== '0000-00-00')
                                <div class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($building->creation_date)->format('Y-m-d') }}</div>
                                @elseif(filled($building->building_date) && $building->building_date !== '0000-00-00')
                                <div class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($building->building_date)->format('Y-m-d') }}</div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('buildings.buildings.show', $building->id) }}"
                                       class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                       title="عرض التفاصيل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @can('bhm.buildings.edit')
                                    <a href="#"
                                       class="text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 p-2 rounded-lg transition-colors"
                                       title="تعديل">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                                        <x-heroicon-o-building-office-2 class="w-8 h-8 text-gray-300" />
                                    </div>
                                    <p class="text-gray-600 font-semibold mb-1">لا توجد نتائج</p>
                                    <p class="text-sm text-gray-400">
                                        @if(request()->anyFilled(['building_number', 'file_number', 'id_card', 'owner_name', 'zone_id', 'street_id', 'street_number', 'building_type_id', 'block_number', 'parcel_number']))
                                            لم يتم العثور على مباني مطابقة لبحثك.
                                            <a href="{{ route('buildings.buildings.index') }}" class="text-orange hover:underline font-medium">عرض الكل</a>
                                        @else
                                            لا توجد مباني مضافة في النظام.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($buildings->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <div class="text-sm text-gray-500">
                    عرض {{ $buildings->firstItem() }}–{{ $buildings->lastItem() }} من أصل {{ $buildings->total() }} مبنى
                </div>
                <div>{{ $buildings->links() }}</div>
            </div>
            @endif
        </div>

    </div>
</x-buildings-layout>
