<x-portal-layout>
    <x-slot name="title">المباني والعقارات المسجلة - بوابة المواطنين</x-slot>

    <div class="space-y-6" x-data="{ activeImage: null, activeImageTitle: '' }">

        {{-- Page Header --}}
        <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <div class="text-xs font-bold text-teal-700 mb-1 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>سجل الأصول العقارية</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">المباني والعقارات المسجلة</h2>
                <p class="text-xs text-slate-500 mt-1">كافة المباني المسجلة باسمك أو بمشاركتك في سجلات ونظم بلدية بيت حانون</p>
            </div>

            <div class="px-4 py-2 rounded-2xl bg-teal-50 border border-teal-200 text-teal-900 text-xs font-bold shrink-0">
                إجمالي المباني: <span class="font-black text-sm text-teal-700">{{ $buildings->count() }}</span> مبنى
            </div>
        </div>

        {{-- Buildings Cards Grid --}}
        @if($buildings->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/90 shadow-sm space-y-3">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700">لا توجد مباني مسجلة باسمك حالياً</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    إذا كنت تمتلك عقاراً أو مبنى ولم يظهر في هذه القائمة، يرجى التوجه لمركز خدمات الجمهور مصطحباً عقد الملكية وسند التسجيل لتحديث بياناتك.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                @foreach($buildings as $building)
                    <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-sm hover:shadow-lg hover:border-teal-400/50 transition-all duration-300 space-y-4 flex flex-col justify-between">
                        
                        <div>
                            {{-- Building Photo Banner --}}
                            @if(!empty($building['photo_url']))
                                <div class="relative w-full h-48 sm:h-52 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200/80 mb-4 group cursor-pointer shadow-xs"
                                     @click="activeImage = '{{ $building['photo_url'] }}'; activeImageTitle = 'صورة واجهة مبنى رقم {{ $building['building_number'] }}'">
                                    
                                    <img src="{{ $building['photo_url'] }}"
                                         alt="صورة مبنى {{ $building['building_number'] }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    {{-- Fallback if image fails to load --}}
                                    <div class="hidden absolute inset-0 bg-slate-100 items-center justify-center flex-col text-slate-400 p-4 text-center">
                                        <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-[11px] font-bold">تعذر تحميل الصورة</span>
                                    </div>

                                    {{-- Dark gradient overlay for bottom text contrast --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-black/10 to-transparent"></div>
                                    
                                    {{-- Zoom Icon on Hover --}}
                                    <div class="absolute top-2.5 left-2.5 opacity-90 group-hover:opacity-100 transition-opacity">
                                        <span class="p-1.5 rounded-xl bg-black/40 hover:bg-black/60 backdrop-blur-md text-white text-xs flex items-center gap-1 shadow-sm transition-colors" title="انقر لتكبير الصورة">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </span>
                                    </div>

                                    {{-- Bottom Info on Photo --}}
                                    <div class="absolute bottom-2.5 right-3 left-3 flex items-center justify-between text-white text-xs">
                                        <span class="bg-black/50 backdrop-blur-md px-2.5 py-0.5 rounded-lg text-[10px] font-bold flex items-center gap-1 border border-white/10">
                                            <svg class="w-3 h-3 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>صورة الواجهة</span>
                                        </span>
                                        @if(!empty($building['building_name']))
                                            <span class="text-[11px] font-bold truncate max-w-[65%] text-slate-100 drop-shadow">{{ $building['building_name'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="relative w-full h-32 rounded-2xl overflow-hidden bg-gradient-to-br from-slate-100 via-teal-50/20 to-slate-100 border border-slate-200/60 mb-4 flex flex-col items-center justify-center text-slate-400 p-4">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-2xs border border-slate-200/60 flex items-center justify-center text-teal-700/50 mb-1.5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500">لا توجد صورة واجهة مسجلة</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">يمكن إيداع صورة المبنى لدى قسم التنظيم</span>
                                </div>
                            @endif

                            {{-- Building Card Top Header --}}
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-800 flex items-center justify-center font-black text-sm border border-teal-100 shadow-2xs shrink-0">
                                        {{ $building['building_number'] }}
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-900 leading-tight">
                                            مبنى رقم {{ $building['building_number'] }}
                                            @if(!empty($building['building_name']))
                                                <span class="text-xs font-bold text-teal-700 block sm:inline sm:mr-1">({{ $building['building_name'] }})</span>
                                            @endif
                                        </h3>
                                        <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                            <span>{{ $building['street_name'] }}</span>
                                            <span>•</span>
                                            <span>{{ $building['zone_name'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-teal-50 text-teal-800 border border-teal-200 shrink-0">
                                    {{ $building['building_use'] }}
                                </span>
                            </div>

                            {{-- Location & Regulatory Details --}}
                            <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50 p-3.5 rounded-2xl border border-slate-100 my-3">
                                <div>
                                    <span class="text-slate-400 block text-[10px] font-semibold">رقم القطعة / القسيمة</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $building['parcel_number'] ?: 'غير محدد' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px] font-semibold">رقم الحوض / البلوك</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $building['block_number'] ?: 'غير محدد' }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-slate-400 block text-[10px] font-semibold">الحي / المنطقة الفرعية</span>
                                    <span class="font-bold text-slate-800">{{ $building['subzone_name'] }}</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-slate-400 block text-[10px] font-semibold">حالة رخصة البناء</span>
                                    <span class="font-bold text-slate-800 font-mono">{{ $building['license_number'] ? 'مرخص (' . $building['license_number'] . ')' : 'غير مسجل رخصة' }}</span>
                                </div>
                            </div>

                            {{-- Metric Badges --}}
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-600 pt-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                    <span>عدد الطوابق: <b class="text-slate-900 font-black">{{ $building['floors_count'] }}</b></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span>عدد الوحدات: <b class="text-slate-900 font-black">{{ $building['units_count'] }}</b></span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Bottom Action --}}
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                            <span class="text-slate-400">
                                حالة المبنى: <b class="text-slate-700">{{ $building['building_status'] }}</b>
                            </span>
                            <a href="{{ route('portal.units') }}" class="font-bold text-teal-700 hover:text-teal-900 hover:underline flex items-center gap-1">
                                <span>عرض وحدات هذا المبنى</span>
                                <span>←</span>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

        {{-- Lightbox Modal for Full-Size Image Preview --}}
        <template x-teleport="body">
            <div x-show="activeImage"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
                 style="display: none;"
                 @keydown.escape.window="activeImage = null">

                <div class="fixed inset-0" @click="activeImage = null" aria-hidden="true"></div>

                <div class="relative max-w-4xl max-h-[90vh] bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-white/10 z-10 flex flex-col"
                     @click.stop>
                    <div class="flex items-center justify-between p-3.5 px-5 bg-slate-900/95 border-b border-white/10 text-white">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs sm:text-sm font-bold text-teal-200" x-text="activeImageTitle"></span>
                        </div>
                        <button type="button"
                                @click="activeImage = null"
                                class="p-1.5 rounded-xl bg-white/10 hover:bg-rose-500 text-white transition-colors cursor-pointer text-xs font-bold">
                            ✕ إغلاق
                        </button>
                    </div>
                    <div class="p-2 sm:p-4 overflow-auto flex items-center justify-center max-h-[calc(90vh-60px)] bg-black/40">
                        <img :src="activeImage"
                             class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl"
                             alt="معاينة صورة واجهة المبنى">
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-portal-layout>