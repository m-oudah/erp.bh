<x-buildings-layout>
    <div class="space-y-6 max-w-7xl mx-auto pb-10">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <a href="{{ route('buildings.buildings.index') }}" class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                            مبنى رقم {{ $building->building_number ?? '—' }}
                        </h1>
                        @if($building->historical)
                        <span class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs px-3 py-1.5 rounded-xl font-bold shadow-sm shadow-purple-500/20 flex items-center gap-1">
                            <x-heroicon-s-building-library class="w-3.5 h-3.5" />
                            تاريخي
                        </span>
                        @endif
                        @if($building->buildingStatus)
                        @php
                            $statusColors = [
                                'مهدوم' => 'from-red-500 to-rose-600 shadow-red-500/20', 
                                'قيد الإنشاء' => 'from-amber-400 to-orange-500 shadow-orange-500/20', 
                                'مكتمل' => 'from-emerald-400 to-teal-500 shadow-teal-500/20', 
                                'مسكون' => 'from-blue-500 to-indigo-600 shadow-blue-500/20'
                            ];
                            $colorClass = $statusColors[$building->buildingStatus->name] ?? 'from-gray-500 to-gray-600 shadow-gray-500/20';
                        @endphp
                        <span class="bg-gradient-to-r {{ $colorClass }} text-white text-xs px-3 py-1.5 rounded-xl font-bold shadow-sm flex items-center gap-1">
                            {{ $building->buildingStatus->name }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2 flex-wrap font-medium">
                        @if($building->file_number)
                        <span class="flex items-center gap-1.5 bg-white px-3 py-1 rounded-lg border border-gray-100 shadow-sm">
                            <x-heroicon-o-folder class="w-4 h-4 text-orange"/>
                            ملف حاسوب: <span class="font-bold text-gray-700">{{ $building->file_number }}</span>
                        </span>
                        @endif
                        <span class="flex items-center gap-1.5 bg-white px-3 py-1 rounded-lg border border-gray-100 shadow-sm">
                            <x-heroicon-o-map-pin class="w-4 h-4 text-blue-500"/>
                            {{ $building->zone->name ?? '' }}
                            @if($building->street) <span class="text-gray-300 mx-1">•</span> {{ $building->street->name }} @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @can('bhm.buildings.edit')
                <a href="#" class="group relative inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-2xl shadow-lg shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    <x-heroicon-s-pencil-square class="w-4 h-4 relative z-10" />
                    <span class="relative z-10">تعديل البيانات</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Hero Section: Photo (Left) + Stats (Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Stats Cards (Right side in RTL, so it comes first in DOM) --}}
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col gap-4">
                <div class="bg-white rounded-3xl p-1 shadow-sm border border-gray-100 h-full flex flex-col gap-1">
                    
                    <div class="group flex items-center justify-between p-4 rounded-2xl hover:bg-blue-50/50 transition-all cursor-default">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center shrink-0 shadow-inner border border-blue-100/50 group-hover:scale-110 transition-transform">
                                <x-heroicon-s-building-office class="w-6 h-6 text-blue-500" />
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold mb-1 tracking-wider">حالة المبنى</div>
                                <div class="font-extrabold text-gray-800 text-base">{{ $building->buildingStatus->name ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-50 mx-4">
                    
                    <div class="group flex items-center justify-between p-4 rounded-2xl hover:bg-purple-50/50 transition-all cursor-default">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center shrink-0 shadow-inner border border-purple-100/50 group-hover:scale-110 transition-transform">
                                <x-heroicon-s-key class="w-6 h-6 text-purple-500" />
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold mb-1 tracking-wider">نوع الملكية</div>
                                <div class="font-extrabold text-gray-800 text-base">{{ $building->buildingPropertyType->name ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-50 mx-4">
                    
                    <div class="group flex items-center justify-between p-4 rounded-2xl hover:bg-orange/5 transition-all cursor-default">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange/20 to-orange/5 flex items-center justify-center shrink-0 shadow-inner border border-orange/10 group-hover:scale-110 transition-transform">
                                <x-heroicon-s-squares-2x2 class="w-6 h-6 text-orange" />
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold mb-1 tracking-wider">عدد الطوابق</div>
                                <div class="font-extrabold text-gray-800 text-base flex items-baseline gap-1.5">
                                    <span class="text-xl">{{ $building->floors->count() }}</span>
                                    <span class="text-gray-400 font-medium text-sm">طابق</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-50 mx-4">
                    
                    <div class="group flex items-center justify-between p-4 rounded-2xl hover:bg-emerald-50/50 transition-all cursor-default">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center shrink-0 shadow-inner border border-emerald-100/50 group-hover:scale-110 transition-transform">
                                <x-heroicon-s-home class="w-6 h-6 text-emerald-500" />
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-400 font-bold mb-1 tracking-wider">عدد الوحدات</div>
                                <div class="font-extrabold text-gray-800 text-base flex items-baseline gap-1.5">
                                    <span class="text-xl">{{ $building->units->count() }}</span>
                                    <span class="text-gray-400 font-medium text-sm">وحدة</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Building Photo (Left side in RTL) --}}
            <div class="lg:col-span-7 xl:col-span-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col relative group">
                    @php $photo = $building->attachments->first(); @endphp
                    @if($photo && $photo->path)
                    <div class="relative w-full h-full min-h-[350px]">
                        <img src="{{ asset('storage/' . $photo->path) }}"
                             alt="صورة المبنى {{ $building->building_number }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
                    </div>
                    @else
                    <div class="w-full h-full min-h-[350px] bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center border-2 border-dashed border-gray-200 m-2 rounded-2xl w-[calc(100%-16px)] h-[calc(100%-16px)] relative">
                        <div class="absolute inset-0 bg-grid-slate-100/[0.04] bg-[size:20px_20px]"></div>
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="w-24 h-24 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 text-gray-300">
                                <x-heroicon-o-photo class="w-10 h-10" />
                            </div>
                            <span class="text-base text-gray-500 font-bold mb-1">لا توجد صورة للمبنى</span>
                            <span class="text-xs text-gray-400 max-w-xs text-center mb-6">يرجى رفع صورة واضحة للمبنى لإضافتها إلى السجل</span>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Photo Overlay Controls --}}
                    <div class="absolute bottom-0 left-0 right-0 p-6 flex items-end justify-between z-20">
                        <div>
                            @if($photo && $photo->path)
                            <h3 class="text-white font-bold text-xl mb-1 shadow-sm">الواجهة الرئيسية</h3>
                            <p class="text-white/80 text-sm flex items-center gap-1">
                                <x-heroicon-o-clock class="w-4 h-4" />
                                أُضيفت {{ $photo->created_at->diffForHumans() }}
                            </p>
                            @endif
                        </div>
                        @can('bhm.buildings.edit')
                        <button class="bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white font-medium px-4 py-2.5 rounded-xl transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                            <x-heroicon-o-arrow-up-tray class="w-4 h-4" />
                            {{ ($photo && $photo->path) ? 'تحديث الصورة' : 'رفع صورة الآن' }}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

        </div>

        {{-- Main Content: Tabs Section --}}
        <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-8">

            {{-- Tabs Header --}}
            <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/80 px-2 pt-2 scrollbar-hide">
                <button @click="tab = 'basic'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'basic', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'basic'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-map-pin class="w-4 h-4" x-bind:class="{'text-orange': tab === 'basic'}" />
                    البيانات والموقع
                </button>
                <button @click="tab = 'owners'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'owners', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'owners'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-users class="w-4 h-4" x-bind:class="{'text-orange': tab === 'owners'}" />
                    سجل الملاك
                    <span :class="{'bg-orange/10 text-orange-dark': tab === 'owners', 'bg-gray-200 text-gray-600': tab !== 'owners'}" 
                          class="text-[10px] font-extrabold px-2 py-0.5 rounded-full ml-1">{{ $building->owners->count() }}</span>
                </button>
                <button @click="tab = 'structure'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'structure', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'structure'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-squares-plus class="w-4 h-4" x-bind:class="{'text-orange': tab === 'structure'}" />
                    هيكلية المبنى
                </button>
                <button @click="tab = 'materials'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'materials', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'materials'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-swatch class="w-4 h-4" x-bind:class="{'text-orange': tab === 'materials'}" />
                    المواصفات والتشطيب
                </button>
                <button @click="tab = 'financial'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'financial', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'financial'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-banknotes class="w-4 h-4" x-bind:class="{'text-orange': tab === 'financial'}" />
                    البيانات المالية
                </button>
                <button @click="tab = 'archive'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'archive', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'archive'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-folder-open class="w-4 h-4" x-bind:class="{'text-orange': tab === 'archive'}" />
                    ملفات الأرشيف
                    <span :class="{'bg-orange/10 text-orange-dark': tab === 'archive', 'bg-gray-200 text-gray-600': tab !== 'archive'}" 
                          class="text-[10px] font-extrabold px-2 py-0.5 rounded-full ml-1">{{ isset($archiveFiles) ? $archiveFiles->count() : 0 }}</span>
                </button>
            </div>

            {{-- Tab Content Area --}}
            <div class="p-8">

                {{-- Tab 1: Basic & Location --}}
                <div x-show="tab === 'basic'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8" style="display: none;">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Location Block --}}
                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-sm font-extrabold text-gray-800 mb-6 flex items-center gap-2">
                                <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg"><x-heroicon-s-map-pin class="w-4 h-4" /></div>
                                تفاصيل الموقع
                            </h3>
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">المنطقة / الحي</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->zone->name ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">المنطقة الفرعية</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->subzone->name ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">الشارع</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->street->name ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">رقم القطعة</span>
                                    <span class="text-sm font-mono font-bold text-gray-800 bg-white px-2 py-0.5 rounded border border-gray-200 shadow-sm">{{ $building->block_number ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                    <span class="text-sm text-gray-500 font-medium">رقم القسيمة</span>
                                    <span class="text-sm font-mono font-bold text-gray-800 bg-white px-2 py-0.5 rounded border border-gray-200 shadow-sm">{{ $building->parcel_number ?? '—' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Structural Info Block --}}
                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-sm font-extrabold text-gray-800 mb-6 flex items-center gap-2">
                                <div class="p-1.5 bg-orange/10 text-orange rounded-lg"><x-heroicon-s-building-library class="w-4 h-4" /></div>
                                بيانات الإنشاء
                            </h3>
                            <div class="space-y-4">
                                @if($building->building_name)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">اسم المبنى</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->building_name }}</span>
                                </div>
                                @endif
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">نوع المبنى</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->buildingType->name ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">تاريخ الإنشاء</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->building_date ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 pb-3 border-b border-gray-100 border-dashed">
                                    <span class="text-sm text-gray-500 font-medium">المشرف المسؤول</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->supervisor->name ?? '—' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                    <span class="text-sm text-gray-500 font-medium">حالة البناء الخارجية</span>
                                    <span class="text-sm font-bold text-gray-800">{{ $building->external_status_label }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($building->ownership_notes || $building->overall_status_notes)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($building->ownership_notes)
                        <div class="bg-amber-50/50 border border-amber-100/50 rounded-2xl p-5 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-100 rounded-full opacity-50"></div>
                            <h4 class="font-bold text-amber-800 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5 relative z-10">
                                <x-heroicon-s-document-text class="w-4 h-4" /> ملاحظات الملكية
                            </h4>
                            <p class="text-amber-900 text-sm leading-relaxed relative z-10">{{ $building->ownership_notes }}</p>
                        </div>
                        @endif
                        @if($building->overall_status_notes)
                        <div class="bg-blue-50/50 border border-blue-100/50 rounded-2xl p-5 relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-100 rounded-full opacity-50"></div>
                            <h4 class="font-bold text-blue-800 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5 relative z-10">
                                <x-heroicon-s-information-circle class="w-4 h-4" /> ملاحظات الحالة
                            </h4>
                            <p class="text-blue-900 text-sm leading-relaxed relative z-10">{{ $building->overall_status_notes }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                {{-- Tab 2: Owners --}}
                <div x-show="tab === 'owners'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6" style="display: none;">

                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">قائمة المُلاك</h3>
                            <p class="text-sm text-gray-500">الأشخاص المسجلين كمالكين لهذا المبنى أو وحداته</p>
                        </div>
                        @can('bhm.buildings.owners.manage')
                        <button class="text-sm text-white font-bold px-4 py-2 bg-gray-900 hover:bg-black rounded-xl transition-colors inline-flex items-center gap-2 shadow-sm hover:shadow-md">
                            <x-heroicon-o-plus class="w-4 h-4" /> إضافة مالك
                        </button>
                        @endcan
                    </div>

                    @if($building->owners->count() > 0)
                    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                        <table class="w-full text-right text-sm">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="px-6 py-4">المالك</th>
                                    <th class="px-6 py-4">رقم الهوية</th>
                                    <th class="px-6 py-4">رقم الهاتف</th>
                                    <th class="px-6 py-4 text-center">الوحدات المملوكة</th>
                                    <th class="px-6 py-4 text-center">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($building->owners as $owner)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ mb_substr($owner->full_name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-gray-800">{{ $owner->full_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-mono font-medium text-gray-600">{{ $owner->id_card }}</td>
                                    <td class="px-6 py-4 text-gray-500 font-mono text-xs" dir="ltr">{{ $owner->phone_number ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[2.5rem] bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg text-xs font-bold border border-gray-200">
                                            {{ $owner->units->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button class="text-gray-400 hover:text-blue-600 transition-colors p-1 rounded-lg hover:bg-blue-50 opacity-0 group-hover:opacity-100">
                                            <x-heroicon-o-eye class="w-5 h-5" />
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-16 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 mx-auto mb-4">
                            <x-heroicon-o-users class="w-8 h-8 text-gray-400" />
                        </div>
                        <h4 class="text-lg font-bold text-gray-700 mb-1">لا يوجد ملاك مسجلون</h4>
                        <p class="text-sm text-gray-500 mb-5">لم يتم إضافة أي ملاك لهذا المبنى حتى الآن.</p>
                        @can('bhm.buildings.owners.manage')
                        <button class="text-sm text-gray-700 font-bold px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-all shadow-sm">
                            إضافة المالك الأول
                        </button>
                        @endcan
                    </div>
                    @endif

                </div>

                {{-- Tab 3: Structure (Floors & Units) --}}
                <div x-show="tab === 'structure'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-10" style="display: none;">

                    {{-- Floors --}}
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <div class="p-1.5 bg-indigo-100 text-indigo-600 rounded-lg"><x-heroicon-s-squares-plus class="w-4 h-4" /></div>
                                تفاصيل الطوابق
                            </h3>
                            <span class="text-sm font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-lg border border-gray-200">الإجمالي: {{ $building->floors->count() }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @forelse($building->floors as $floor)
                            <div class="bg-white border border-gray-200 rounded-2xl p-5 hover:border-indigo-300 hover:shadow-lg hover:shadow-indigo-500/5 transition-all group relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-1.5 h-full bg-indigo-500 transform origin-right scale-y-0 group-hover:scale-y-100 transition-transform duration-300"></div>
                                <div class="flex justify-between items-start mb-5">
                                    <h4 class="font-extrabold text-gray-800 text-lg">{{ $floor->floor_name }}</h4>
                                    <span class="text-[10px] px-2.5 py-1 rounded-lg bg-gray-100 border border-gray-200 font-bold text-gray-600">{{ $floor->license_status_label }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-3 text-center">
                                    <div class="bg-gray-50/80 rounded-xl p-2.5 border border-gray-100">
                                        <div class="text-xs text-gray-500 mb-1 font-medium">المساحة</div>
                                        <div class="text-lg font-black text-gray-800">{{ $floor->area ?? 0 }}<span class="text-[10px] text-gray-400 font-medium mr-0.5">م²</span></div>
                                    </div>
                                    <div class="bg-orange/5 rounded-xl p-2.5 border border-orange/10">
                                        <div class="text-xs text-orange-dark/70 mb-1 font-medium">محلات</div>
                                        <div class="text-lg font-black text-orange">{{ $floor->stores ?? 0 }}</div>
                                    </div>
                                    <div class="bg-blue-50/50 rounded-xl p-2.5 border border-blue-100">
                                        <div class="text-xs text-blue-600/70 mb-1 font-medium">شقق</div>
                                        <div class="text-lg font-black text-blue-600">{{ $floor->departments ?? 0 }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-3 text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-sm text-gray-400 font-medium">
                                لا يوجد تفاصيل طوابق مسجلة
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Units --}}
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <div class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg"><x-heroicon-s-home class="w-4 h-4" /></div>
                                الوحدات السكنية والتجارية
                            </h3>
                            <span class="text-sm font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-lg border border-gray-200">الإجمالي: {{ $building->units->count() }}</span>
                        </div>
                        
                        @if($building->units->count() > 0)
                        <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                            <table class="w-full text-right text-sm">
                                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider font-bold">
                                    <tr>
                                        <th class="px-5 py-4 w-24">الوحدة</th>
                                        <th class="px-5 py-4">الطابق</th>
                                        <th class="px-5 py-4">النوع</th>
                                        <th class="px-5 py-4">وصف النشاط</th>
                                        <th class="px-5 py-4">المالك</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($building->units as $unit)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-5 py-4 font-black text-gray-800 text-lg">{{ $unit->unit_number ?? '—' }}</td>
                                        <td class="px-5 py-4 text-gray-600 font-medium">{{ $unit->floor->floor_name ?? '—' }}</td>
                                        <td class="px-5 py-4">
                                            @if($unit->unit_type == 2)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-orange/10 text-orange-dark border border-orange/20">
                                                    <x-heroicon-s-building-storefront class="w-3 h-3" />
                                                    {{ $unit->unit_type_label }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    <x-heroicon-s-home class="w-3 h-3" />
                                                    {{ $unit->unit_type_label }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-gray-600">{{ $unit->unit_name ?? '—' }}</td>
                                        <td class="px-5 py-4">
                                            @if($unit->buildingOwner)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                                    {{ mb_substr($unit->buildingOwner->full_name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-700 text-xs">{{ $unit->buildingOwner->full_name }}</span>
                                            </div>
                                            @else
                                            <span class="text-gray-400 text-xs font-medium">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200 text-sm text-gray-400 font-medium">
                            لا يوجد وحدات مسجلة
                        </div>
                        @endif
                    </div>

                </div>

                {{-- Tab 4: Materials --}}
                <div x-show="tab === 'materials'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-8" style="display: none;">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Basic Materials --}}
                        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-gray-50 rounded-full"></div>
                            <h3 class="font-extrabold text-gray-800 pb-4 mb-5 border-b border-gray-100 text-base flex items-center gap-2.5 relative z-10">
                                <div class="p-1.5 bg-gray-100 text-gray-600 rounded-lg"><x-heroicon-s-swatch class="w-4 h-4" /></div>
                                مواد البناء الأساسية
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 relative z-10">
                                @forelse($building->materials as $mat)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-check class="w-4 h-4" />
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $mat->name }}</span>
                                </div>
                                @empty
                                <div class="col-span-2 text-sm text-gray-400 italic py-2">لا توجد بيانات مسجلة</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Exterior Finish --}}
                        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50/50 rounded-full"></div>
                            <h3 class="font-extrabold text-gray-800 pb-4 mb-5 border-b border-gray-100 text-base flex items-center gap-2.5 relative z-10">
                                <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg"><x-heroicon-s-paint-brush class="w-4 h-4" /></div>
                                التشطيب الخارجي
                            </h3>
                            
                            @php
                                $finishes = [];
                                if($building->finish_qesara) $finishes[] = 'قصارة';
                                if($building->finish_italian) $finishes[] = 'إيطالي';
                                if($building->finish_tiles) $finishes[] = 'كراميكا / بلاط';
                                if($building->finish_j_stone) $finishes[] = 'حجر قدسي';
                                if($building->finish_granuleet) $finishes[] = 'جرانيليت';
                                if($building->finish_other) $finishes[] = 'أخرى: ' . $building->finish_notes;
                            @endphp
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 relative z-10">
                                @forelse($finishes as $finish)
                                <div class="flex items-center gap-3 p-3 bg-blue-50/30 rounded-xl border border-blue-100/50">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-check class="w-4 h-4" />
                                    </div>
                                    <span class="text-sm font-bold text-gray-700">{{ $finish }}</span>
                                </div>
                                @empty
                                <div class="col-span-2 text-sm text-gray-400 italic py-2">لا توجد بيانات مسجلة</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Services & Facilities --}}
                    <div class="bg-gray-900 rounded-3xl p-8 relative overflow-hidden shadow-xl">
                        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                        
                        <h3 class="font-extrabold text-white pb-5 mb-6 border-b border-white/10 text-lg flex items-center gap-3 relative z-10">
                            <div class="p-2 bg-white/10 rounded-xl"><x-heroicon-s-wrench-screwdriver class="w-5 h-5" /></div>
                            الخدمات والمرافق
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                            <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl border border-white/10 p-5">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center mb-4">
                                    <x-heroicon-o-funnel class="w-5 h-5" />
                                </div>
                                <div class="text-[11px] text-gray-400 font-medium mb-1">مصدر المياه</div>
                                <div class="text-base font-bold text-white">
                                    {{ $building->water_source == 1 ? 'شبكة البلدية' : ($building->water_source == 2 ? 'بئر خاص' : 'أخرى') }}
                                </div>
                            </div>
                            
                            <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl border border-white/10 p-5">
                                <div class="w-10 h-10 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center mb-4">
                                    <x-heroicon-o-beaker class="w-5 h-5" />
                                </div>
                                <div class="text-[11px] text-gray-400 font-medium mb-1">الصرف الصحي</div>
                                <div class="text-base font-bold text-white">{{ $building->sewage_label }}</div>
                            </div>
                            
                            <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl border border-white/10 p-5">
                                <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center mb-4">
                                    <x-heroicon-o-arrows-up-down class="w-5 h-5" />
                                </div>
                                <div class="text-[11px] text-gray-400 font-medium mb-1">المصاعد</div>
                                <div class="text-2xl font-black text-white flex items-end gap-1">
                                    {{ $building->elevators_count ?? 0 }}
                                    <span class="text-xs text-gray-400 font-medium pb-1">مصعد</span>
                                </div>
                            </div>
                            
                            <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl border border-white/10 p-5">
                                <div class="w-10 h-10 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center mb-4">
                                    <x-heroicon-o-arrow-trending-down class="w-5 h-5" />
                                </div>
                                <div class="text-[11px] text-gray-400 font-medium mb-1">أدراج الهروب</div>
                                <div class="text-2xl font-black text-white flex items-end gap-1">
                                    {{ $building->escape_stairs ?? 0 }}
                                    <span class="text-xs text-gray-400 font-medium pb-1">درج</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Tab 5: Financial --}}
                <div x-show="tab === 'financial'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6" style="display: none;">

                    @if($building->financial)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white border-2 border-blue-100 rounded-3xl p-6 shadow-sm shadow-blue-100/50 relative overflow-hidden group hover:border-blue-300 transition-colors">
                            <div class="absolute left-0 top-0 h-full w-1.5 bg-blue-500"></div>
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-4 group-hover:scale-110 transition-transform">
                                <x-heroicon-o-map class="w-6 h-6" />
                            </div>
                            <div class="text-xs text-blue-500/70 font-bold uppercase tracking-wider mb-1">مساحة الأرض المرخصة</div>
                            <div class="text-3xl font-black text-gray-800">{{ number_format($building->financial->land_area ?? 0) }} <span class="text-sm text-gray-400 font-medium">م²</span></div>
                        </div>
                        
                        <div class="bg-white border-2 border-emerald-100 rounded-3xl p-6 shadow-sm shadow-emerald-100/50 relative overflow-hidden group hover:border-emerald-300 transition-colors">
                            <div class="absolute left-0 top-0 h-full w-1.5 bg-emerald-500"></div>
                            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition-transform">
                                <x-heroicon-o-currency-dollar class="w-6 h-6" />
                            </div>
                            <div class="text-xs text-emerald-600/70 font-bold uppercase tracking-wider mb-1">رسوم التطوير</div>
                            <div class="text-3xl font-black text-gray-800">{{ number_format($building->financial->dev_fees ?? 0, 2) }} <span class="text-sm text-gray-400 font-medium">شيكل</span></div>
                        </div>
                        
                        <div class="bg-white border-2 border-red-100 rounded-3xl p-6 shadow-sm shadow-red-100/50 relative overflow-hidden group hover:border-red-300 transition-colors">
                            <div class="absolute left-0 top-0 h-full w-1.5 bg-red-500"></div>
                            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 mb-4 group-hover:scale-110 transition-transform">
                                <x-heroicon-o-banknotes class="w-6 h-6" />
                            </div>
                            <div class="text-xs text-red-600/70 font-bold uppercase tracking-wider mb-1">الرسوم المتبقية</div>
                            <div class="text-3xl font-black text-red-600">{{ number_format($building->financial->fees_remains ?? 0, 2) }} <span class="text-sm text-red-400 font-medium">شيكل</span></div>
                        </div>
                    </div>
                    
                    @if($building->financial->notes)
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mt-6">
                        <div class="text-sm font-bold text-gray-800 uppercase tracking-wide mb-2 flex items-center gap-2">
                            <x-heroicon-o-document-text class="w-5 h-5 text-gray-500" />
                            ملاحظات مالية
                        </div>
                        <div class="text-sm text-gray-600 leading-relaxed pl-7">{{ $building->financial->notes }}</div>
                    </div>
                    @endif
                    @else
                    <div class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 mx-auto mb-4">
                            <x-heroicon-o-banknotes class="w-10 h-10 text-gray-300" />
                        </div>
                        <h4 class="text-lg font-bold text-gray-700 mb-1">لا يوجد بيانات مالية</h4>
                        <p class="text-sm text-gray-500 mb-5">لم يتم إدخال بيانات أو التزامات مالية لهذا المبنى.</p>
                        @can('bhm.buildings.edit')
                        <button class="text-sm text-white font-bold px-5 py-2.5 bg-gray-900 hover:bg-black rounded-xl transition-all shadow-sm">
                            إضافة بيانات مالية
                        </button>
                        @endcan
                    </div>
                    @endif

                </div>

                {{-- Tab 6: Archive Files --}}
                <div x-show="tab === 'archive'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6" style="display: none;">
                    
                    @if(isset($archiveFiles) && $archiveFiles->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($archiveFiles as $archiveFile)
                            <a href="{{ route('archive.files.show', $archiveFile->id) }}" class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm hover:shadow-md hover:border-blue-300 transition-all group block">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <x-heroicon-o-folder-open class="w-6 h-6" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $archiveFile->fileType->name ?? 'ملف' }}</span>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">{{ $archiveFile->file_name }}</h4>
                                <div class="text-sm text-gray-500 font-mono">{{ $archiveFile->file_no }}</div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 mx-auto mb-4">
                                <x-heroicon-o-folder class="w-10 h-10 text-gray-300" />
                            </div>
                            <h4 class="text-lg font-bold text-gray-700 mb-1">لا توجد ملفات أرشيف</h4>
                            <p class="text-sm text-gray-500 mb-5">لم يتم العثور على أي ملفات مؤرشفة مرتبطة برقم هذا المبنى ({{ $building->file_number ?? 'غير محدد' }}).</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</x-buildings-layout>
