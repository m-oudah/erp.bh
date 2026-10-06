<x-buildings-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('buildings.economical.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        نشاط: {{ $economical->job_formal_name ?? $economical->name ?? 'بدون اسم' }}
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1.5"><x-heroicon-o-briefcase class="w-4 h-4 text-gray-400"/> القطاع: {{ $economical->sector->name ?? 'غير محدد' }}</span>
                        @if($economical->building)
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('buildings.buildings.show', $economical->building_id) }}" class="flex items-center gap-1.5 text-blue-600 hover:underline">
                            <x-heroicon-o-building-office class="w-4 h-4"/> مبنى رقم {{ $economical->building->building_number ?? $economical->building_id }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @can('bhm.economical.edit')
                <a href="{{ route('buildings.economical.edit', $economical->id) }}" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    تعديل البيانات
                </a>
                @endcan
            </div>
        </div>

        <!-- Quick Stats / Badges -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500 font-medium mb-1">حالة الترخيص</div>
                <div class="font-bold {{ $economical->isLicensed == 1 ? 'text-emerald-600' : 'text-gray-600' }}">
                    {{ $economical->isLicensed == 1 ? 'مرخص' : 'غير مرخص' }}
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500 font-medium mb-1">مستوى الخطورة</div>
                <div class="font-bold {{ $economical->isDanger == 1 ? 'text-red-600' : 'text-emerald-600' }}">
                    {{ $economical->isDanger == 1 ? 'مخاطرة خطرة' : 'عادية' }}
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                <div class="text-xs text-gray-500 font-medium mb-1">عدد الحرف والمهن</div>
                <div class="font-bold text-gray-800">{{ $economical->crafts->count() }} حرفة/مهنة</div>
            </div>
        </div>

        <!-- Alpine Tabs -->
        <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            
            <!-- Tabs Header -->
            <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'basic'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'basic', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'basic'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-information-circle class="w-4 h-4" />
                    بيانات النشاط والمالك
                </button>
                <button @click="tab = 'crafts'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'crafts', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'crafts'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-wrench-screwdriver class="w-4 h-4" />
                    المهن والحرف ({{ $economical->crafts->count() }})
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                
                <!-- Tab: Basic Data -->
                <div x-show="tab === 'basic'" class="space-y-8" style="display: none;">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <!-- Group 1 -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <x-heroicon-s-user-circle class="w-4 h-4 text-gray-400" />
                                بيانات المالك الرئيسي للنشاط
                            </h3>
                            @php
                                $mainOwnerName = $economical->name;
                                $mainOwnerCard = $economical->id_card;
                                $otherOwners = $economical->owners;

                                if (empty(trim($mainOwnerName)) && $economical->owners->count() > 0) {
                                    $firstOwner = $economical->owners->first();
                                    $mainOwnerName = $firstOwner->full_name;
                                    $mainOwnerCard = $firstOwner->id_card;
                                    $otherOwners = $economical->owners->skip(1);
                                }
                            @endphp
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الاسم:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $mainOwnerName ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">رقم الهوية:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $mainOwnerCard ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الاسم التجاري:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $economical->job_formal_name ?: '-' }}</span>
                                </div>
                                @if($otherOwners->count() > 0)
                                <div class="py-2">
                                    <span class="text-sm text-gray-500 block mb-2">شركاء آخرون مسجلون:</span>
                                    <div class="space-y-1">
                                    @foreach($otherOwners as $partner)
                                        <div class="text-xs text-gray-700 bg-gray-50 px-2 py-1.5 rounded">{{ $partner->full_name }} ({{ $partner->id_card }})</div>
                                    @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Group 2 -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <x-heroicon-s-map-pin class="w-4 h-4 text-gray-400" />
                                موقع النشاط
                            </h3>
                            <div class="space-y-3">
                                @if($economical->building)
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">مبنى رقم:</span>
                                    <a href="{{ route('buildings.buildings.show', $economical->building_id) }}" class="text-sm font-bold text-blue-600 hover:underline">{{ $economical->building->building_number ?? $economical->building_id }}</a>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الحي:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $economical->building->zone->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الشارع:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $economical->building->street->name ?? '-' }}</span>
                                </div>
                                @endif

                                @if($economical->unit)
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500">رقم الوحدة/المحل:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $economical->unit->unit_number ?? '-' }} ({{ $economical->unit->unit_name ?? '-' }})</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($economical->notes)
                    <div class="bg-yellow-50/50 rounded-xl p-4 border border-yellow-100 mt-6">
                        <span class="font-bold text-yellow-800 block mb-1 text-sm flex items-center gap-1.5"><x-heroicon-s-information-circle class="w-4 h-4"/> ملاحظات:</span>
                        <span class="text-gray-700 text-sm leading-relaxed">{{ $economical->notes }}</span>
                    </div>
                    @endif

                </div>

                <!-- Tab: Crafts -->
                <div x-show="tab === 'crafts'" class="space-y-6" style="display: none;">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">الحرف والمهن المسجلة</h3>
                        @can('bhm.economical.edit')
                        <button class="text-sm text-orange hover:text-orange-dark font-medium px-3 py-1.5 bg-orange/10 hover:bg-orange/20 rounded-lg transition-colors">
                            إضافة حرفة
                        </button>
                        @endcan
                    </div>

                    @if($economical->crafts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($economical->crafts as $craft)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col justify-between hover:border-blue-200 transition-colors">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-navy-dark">{{ $craft->craftType->name ?? 'غير محدد' }}</h4>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $craft->status->name == 'فعال' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }} font-bold">
                                            {{ $craft->status->name ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mb-3">التصنيف: <span class="font-medium text-gray-700">{{ $craft->category->name ?? '-' }}</span></div>
                                </div>
                                <div class="text-xs text-gray-400 border-t border-gray-100 pt-2 flex justify-between">
                                    <span>تاريخ التسجيل: {{ $craft->created_at ? $craft->created_at->format('Y-m-d') : '-' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <p class="text-gray-500">لا يوجد مهن أو حرف مسجلة لهذا النشاط.</p>
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>
</x-buildings-layout>
