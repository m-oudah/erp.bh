<x-buildings-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('buildings.licenses.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        معاملة ترخيص رقم: {{ $license->id }}
                        @if($license->status == 1)
                            <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-md font-bold">فعال</span>
                        @elseif($license->status == 2)
                            <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-md font-bold">منجز</span>
                        @elseif($license->status == 3)
                            <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-md font-bold">ملغي / مجمد</span>
                        @endif
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1.5"><x-heroicon-o-calendar class="w-4 h-4 text-gray-400"/> تاريخ التقديم: {{ $license->created_at ? $license->created_at->format('Y-m-d') : '-' }}</span>
                        @if($license->building)
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('buildings.buildings.show', $license->building_id) }}" class="flex items-center gap-1.5 text-blue-600 hover:underline">
                            <x-heroicon-o-building-office class="w-4 h-4"/> مبنى رقم {{ $license->building->building_number ?? $license->building_id }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @can('bhm.license-forms.approve')
                <button class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-check-circle class="w-4 h-4" />
                    إنجاز / اعتماد
                </button>
                @endcan
                @can('bhm.license-forms.edit')
                <a href="#" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    تعديل
                </a>
                @endcan
            </div>
        </div>

        <!-- Alpine Tabs -->
        <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            
            <!-- Tabs Header -->
            <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'basic'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'basic', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'basic'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-4 h-4" />
                    نموذج الترخيص
                </button>
                @if($license->report)
                <button @click="tab = 'report'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'report', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'report'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
                    تقرير الكشف التنظيمي
                </button>
                @endif
                <button @click="tab = 'opinions'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'opinions', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'opinions'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-bottom-center-text class="w-4 h-4" />
                    آراء الجهات المختصة
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                
                <!-- Tab: Basic Data -->
                <div x-show="tab === 'basic'" class="space-y-8" style="display: none;">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <!-- Applicant Info -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">بيانات مقدم الطلب</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الاسم الرباعي:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->full_name ?: '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">رقم الهوية:</span>
                                    <span class="text-sm font-semibold text-gray-800 font-mono">{{ $license->id_card ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">رقم الهاتف/الجوال:</span>
                                    <span class="text-sm font-semibold text-gray-800" dir="ltr">{{ $license->phone ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500">رقم المبنى/الملف الخاص بالمواطن:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building_number ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Building Target -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">بيانات العقار المستهدف</h3>
                            @if($license->building)
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الحي:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->zone->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الشارع:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->street->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">رقم القسيمة:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->parcel_number ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500">نوع المبنى:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->buildingType->name ?? '-' }}</span>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-6 bg-gray-50 rounded-xl text-sm text-gray-500">
                                لم يتم ربط هذا الترخيص بمبنى محدد في النظام.
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                        <h3 class="text-sm font-bold text-navy-dark mb-2">موضوع الترخيص المطلوبة</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $license->subject ?? 'لم يتم تحديد موضوع الترخيص.' }}</p>
                    </div>

                    @if($license->floors->count() > 0)
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">الطوابق المرتبطة بهذه المعاملة</h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="w-full text-right text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3">اسم الطابق</th>
                                        <th class="px-4 py-3">المساحة</th>
                                        <th class="px-4 py-3">حالة الترخيص للطابق</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($license->floors as $floor)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $floor->floor_name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $floor->area ?? 0 }} م²</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            <span class="text-xs px-2 py-1 rounded-md font-medium bg-gray-100 text-gray-700">{{ $floor->license_status_label }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Tab: Report -->
                @if($license->report)
                <div x-show="tab === 'report'" class="space-y-6" style="display: none;">
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <x-heroicon-s-clipboard-document-check class="w-6 h-6 text-orange" />
                            <h3 class="text-lg font-bold text-gray-800">بيانات الكشف التنظيمي (المهندس)</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">الوضع القائم للمبنى</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->existing_status ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">عرض الشارع</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->street_width ?? '-' }} متر</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">الارتدادات التنظيمية</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->bounces ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">التصنيف التنظيمي للمنطقة</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->regulatory_rating ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">نسبة البناء المسموحة والمقترحة</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->building_percentage ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($license->report->notes)
                        <div class="mt-6">
                            <span class="text-xs text-gray-500 block mb-1">التوصيات / الملاحظات الفنية</span>
                            <div class="text-sm text-gray-700 bg-white p-4 rounded-xl border border-gray-100 shadow-sm leading-relaxed whitespace-pre-wrap">{{ $license->report->notes }}</div>
                        </div>
                        @endif
                    </div>

                </div>
                @endif

                <!-- Tab: Opinions -->
                <div x-show="tab === 'opinions'" class="space-y-6" style="display: none;">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-scale class="w-4 h-4 text-gray-400" />
                                الرأي القانوني
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->legalOpinionReply->reply ?? $license->legal_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-map class="w-4 h-4 text-gray-400" />
                                رأي المساحة والتنظيم
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->areaOpinionReply->reply ?? $license->area_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-clipboard-document-list class="w-4 h-4 text-gray-400" />
                                رأي التخطيط
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->planOpinionReply->reply ?? $license->plan_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-beaker class="w-4 h-4 text-gray-400" />
                                رأي شبكات المياه
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->waterOpinionReply->reply ?? $license->water_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-arrow-path-rounded-square class="w-4 h-4 text-gray-400" />
                                رأي شبكات الصرف الصحي
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->sewerOpinionReply->reply ?? $license->sewer_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-banknotes class="w-4 h-4 text-gray-400" />
                                رأي الجباية (براءة ذمة)
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->collectionOpinionReply->reply ?? $license->collection_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors md:col-span-2">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-globe-alt class="w-4 h-4 text-gray-400" />
                                رأي نظم المعلومات الجغرافية (GIS)
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->gisOpinionReply->reply ?? $license->gis_opinion ?: 'لا يوجد' }}</p>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
</x-buildings-layout>
