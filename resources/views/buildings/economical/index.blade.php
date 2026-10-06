<x-buildings-layout>
    <div class="space-y-6">
        
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">الأنشطة الاقتصادية</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة وتتبع الرخص والمحلات والحرف ضمن المباني</p>
            </div>
            @can('bhm.economical.create')
            <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-all">
                <x-heroicon-o-plus class="w-5 h-5" />
                إضافة نشاط
            </a>
            @endcan
        </div>

        <!-- Filter Form -->
        <div class="bg-gray-50/50 rounded-2xl p-4 md:p-5 border border-gray-100">
            <form action="{{ route('buildings.economical.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="filter-form">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">الاسم التجاري</label>
                    <input type="text" name="trade_name" value="{{ request('trade_name') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">اسم صاحب النشاط</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">رقم الهوية</label>
                    <input type="text" name="id_card" value="{{ request('id_card') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">القطاع / التصنيف</label>
                    <select name="job_sector_id" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white" onchange="document.getElementById('filter-form').submit()">
                        <option value="">الكل</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector->id }}" @selected(request('job_sector_id') == $sector->id)>{{ $sector->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">حالة الترخيص</label>
                    <select name="isLicensed" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white" onchange="document.getElementById('filter-form').submit()">
                        <option value="">الكل</option>
                        <option value="1" @selected(request('isLicensed') === '1')>مرخص</option>
                        <option value="0" @selected(request('isLicensed') === '0')>غير مرخص</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">تصنيف الخطورة</label>
                    <select name="isDanger" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white" onchange="document.getElementById('filter-form').submit()">
                        <option value="">الكل</option>
                        <option value="1" @selected(request('isDanger') === '1')>خطرة</option>
                        <option value="0" @selected(request('isDanger') === '0')>عادية (غير خطرة)</option>
                    </select>
                </div>

                <div class="col-span-1 lg:col-span-2 flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-navy hover:bg-navy-dark text-white font-medium py-2 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px]">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        بحث واستعلام
                    </button>
                    @if(request()->anyFilled(['trade_name', 'name', 'id_card', 'job_sector_id', 'isLicensed', 'isDanger']))
                    <a href="{{ route('buildings.economical.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-3 rounded-xl text-sm transition-colors flex items-center justify-center h-[42px]" title="مسح الفلاتر">
                        <x-heroicon-o-x-mark class="w-4 h-4" />
                    </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="px-5 py-4">النشاط التجاري</th>
                            <th class="px-5 py-4">صاحب النشاط</th>
                            <th class="px-5 py-4">القطاع</th>
                            <th class="px-5 py-4">الترخيص</th>
                            <th class="px-5 py-4">الموقع</th>
                            <th class="px-5 py-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($economicals as $eco)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-5 py-3">
                                <a href="{{ route('buildings.economical.show', $eco->id) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
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
                                    <a href="{{ route('buildings.customers.show', $customer->id) }}" class="font-medium text-blue-600 hover:text-blue-800 hover:underline">{{ $ownerName ?: $customer->name ?: '-' }}</a>
                                @else
                                    <div class="font-medium text-navy-dark">{{ $ownerName ?: ($eco->name ?: '-') }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="text-gray-700 text-xs">{{ $eco->sector->name ?? 'غير مصنف' }}</div>
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
                                @else
                                    <span class="text-gray-400 text-xs">مستقل / غير مربوط</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('buildings.economical.show', $eco->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors" title="التفاصيل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @can('bhm.economical.edit')
                                    <a href="{{ route('buildings.economical.edit', $eco->id) }}" class="text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 p-1.5 rounded-lg transition-colors" title="تعديل">
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
</x-buildings-layout>
