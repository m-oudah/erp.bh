<x-crafts-layout>
    <div class="space-y-6">
        
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">الأنشطة الاقتصادية</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة وتتبع الرخص والمحلات والحرف ضمن المباني</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-2">
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        إجمالي: {{ $economicals->total() }} نشاط
                    </span>
                </div>
                @can('bhm.economical.create')
                <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-all text-sm">
                    <x-heroicon-o-plus class="w-4 h-4" />
                    إضافة نشاط
                </a>
                @endcan
            </div>
        </div>

        {{-- Search & Filter Card --}}
        <div x-data="{ showFilters: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50/70 transition-colors"
                 :class="showFilters ? 'border-b border-gray-100' : ''">
                <div class="flex items-center gap-3">
                    <div @click="showFilters = !showFilters"
                         class="flex items-center gap-2 cursor-pointer select-none group py-1"
                         role="button"
                         tabindex="0"
                         @keydown.enter.prevent="showFilters = !showFilters"
                         @keydown.space.prevent="showFilters = !showFilters">
                        <x-heroicon-o-funnel class="w-4 h-4 text-black group-hover:text-orange transition-colors" />
                        <span class="text-sm font-bold text-black group-hover:text-orange transition-colors">خيارات البحث والتصفية</span>
                    </div>
                    @if(request()->anyFilled(['trade_name', 'name', 'id_card', 'job_sector_id', 'craft_type_id', 'isLicensed', 'isDanger']))
                    <a href="{{ route('crafts.economical.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
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
                <form action="{{ route('crafts.economical.index') }}" method="GET" class="p-5" id="economical-filter-form">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">الاسم التجاري</label>
                            <input type="text" name="trade_name" value="{{ request('trade_name') }}"
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                   placeholder="ابحث بالاسم التجاري...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">اسم صاحب النشاط</label>
                            <input type="text" name="name" value="{{ request('name') }}"
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                   placeholder="اسم صاحب النشاط...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">رقم الهوية</label>
                            <input type="text" name="id_card" value="{{ request('id_card') }}"
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                   placeholder="رقم هوية المالك...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">القطاع</label>
                            <select name="job_sector_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                    onchange="document.getElementById('economical-filter-form').submit()">
                                <option value="">جميع القطاعات</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}" @selected(request('job_sector_id') == $sec->id)>{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">حالة الترخيص</label>
                            <select name="isLicensed" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                    onchange="document.getElementById('economical-filter-form').submit()">
                                <option value="">الكل</option>
                                <option value="1" @selected(request('isLicensed') == '1')>مرخص</option>
                                <option value="2" @selected(request('isLicensed') == '2' || request('isLicensed') === '0')>غير مرخص</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">تصنيف الخطورة</label>
                            <select name="isDanger" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                    onchange="document.getElementById('economical-filter-form').submit()">
                                <option value="">الكل</option>
                                <option value="1" @selected(request('isDanger') === '1')>خطرة</option>
                                <option value="0" @selected(request('isDanger') === '0')>عادية (غير خطرة)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">الترتيب</label>
                            <select name="sort" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                    onchange="document.getElementById('economical-filter-form').submit()">
                                <option value="sector_first" @selected(request('sort', 'sector_first') === 'sector_first')>المصنفة قطاعياً أولاً</option>
                                <option value="id_desc" @selected(request('sort') === 'id_desc')>الأحدث أولاً (تنازلي)</option>
                                <option value="id_asc" @selected(request('sort') === 'id_asc')>الأقدم أولاً (تصاعدي)</option>
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
        @if(request()->anyFilled(['trade_name', 'name', 'id_card', 'job_sector_id', 'isLicensed', 'isDanger']))
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-black font-bold">فلاتر نشطة:</span>
            @if(request('trade_name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الاسم التجاري: {{ request('trade_name') }}</span>
            @endif
            @if(request('name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">صاحب النشاط: {{ request('name') }}</span>
            @endif
            @if(request('id_card'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الهوية: {{ request('id_card') }}</span>
            @endif
            @if(request('job_sector_id'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">القطاع: {{ $sectors->firstWhere('id', request('job_sector_id'))->name ?? request('job_sector_id') }}</span>
            @endif
            @if(request('isLicensed'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الترخيص: {{ request('isLicensed') == '1' ? 'مرخص' : 'غير مرخص' }}</span>
            @endif
            @if(request('isDanger') !== null && request('isDanger') !== '')
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الخطورة: {{ request('isDanger') === '1' ? 'خطرة' : 'عادية' }}</span>
            @endif
        </div>
        @endif

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-100 text-black font-bold border-b border-gray-200">
                            <th class="px-5 py-4 text-black font-bold">النشاط التجاري</th>
                            <th class="px-5 py-4 text-black font-bold">صاحب النشاط</th>
                            <th class="px-5 py-4 text-black font-bold">القطاع</th>
                            <th class="px-5 py-4 text-black font-bold">الترخيص</th>
                            <th class="px-5 py-4 text-black font-bold">الموقع</th>
                            <th class="px-5 py-4 text-center text-black font-bold">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($economicals as $eco)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-5 py-3">
                                <a href="{{ route('crafts.economical.show', $eco->id) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $eco->job_formal_name ?? 'بدون اسم تجاري' }}
                                </a>
                                @if($eco->crafts->count())
                                    <div class="text-[10px] text-gray-500 mt-1 truncate max-w-[200px]">
                                        {{ $eco->crafts->first()->craftType->name ?? '-' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $owner = $eco->owners->first();
                                    $ownerName = $owner ? trim(collect([$owner->first_name, $owner->second_name, $owner->third_name, $owner->sur_name])->filter()->implode(' ')) : null;
                                    $ownerCard = $owner->id_card ?? $eco->id_card ?? null;
                                    $customer = $ownerCard ? ($customersMap[$ownerCard] ?? null) : null;
                                @endphp
                                @if($customer)
                                    <a href="{{ route('customers.show', $customer->id) }}" class="font-medium text-blue-600 hover:text-blue-800 hover:underline">{{ $ownerName ?: $customer->name ?: '-' }}</a>
                                @else
                                    <div class="font-medium text-navy-dark">{{ $ownerName ?: ($eco->name ?: '-') }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-black font-semibold text-xs" title="{{ $eco->sector->name ?? '' }}">
                                    {{ $eco->sector->sector_name ?: ($eco->sector->name ?? ($eco->category->name ?? 'غير مصنف')) }}
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if($eco->isLicensed == 1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">مرخص</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">غير مرخص</span>
                                    @endif
                                    
                                    @if($eco->isDanger == 1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">مخاطرة</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if($eco->building)
                                    <a href="{{ route('buildings.buildings.show', $eco->building_id) }}" class="text-blue-600 hover:underline text-xs font-medium block">
                                        مبنى #{{ $eco->building->building_number ?? $eco->building_id }}
                                    </a>
                                @elseif($eco->job_street || $eco->job_location)
                                    <span class="text-black font-semibold text-xs truncate max-w-[150px] block" title="{{ $eco->job_street ?: $eco->job_location }}">
                                        {{ $eco->job_street ?: $eco->job_location }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">مستقل / غير مربوط</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('crafts.economical.show', $eco->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors" title="التفاصيل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @can('Update-Craft')
                                    <a href="{{ route('crafts.economical.edit', $eco->id) }}" class="text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 p-1.5 rounded-lg transition-colors" title="تعديل">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <x-heroicon-o-currency-dollar class="w-8 h-8 text-gray-300" />
                                    </div>
                                    <p class="text-gray-500 font-medium">لا يوجد أنشطة اقتصادية مطابقة للبحث</p>
                                    <p class="text-sm text-gray-400 mt-1">حاول تغيير معايير البحث أو قم بإضافة نشاط جديد</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($economicals->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $economicals->links() }}
            </div>
            @endif
        </div>

    </div>
</x-crafts-layout>
