<x-buildings-layout>
    <div x-data="buildingArchiveLinking({{ $building->id }}, '{{ addslashes($building->file_number ?? '') }}', '{{ addslashes($building->main_owner?->full_name ?? $building->building_name ?? '') }}')" class="space-y-6 max-w-7xl mx-auto pb-10">

        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-xs font-bold text-sm">
            <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <a href="{{ route('buildings.buildings.index') }}" class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                            {{ $building->display_name }}
                        </h1>
                        @if(filled($building->building_number) && !str_contains($building->display_name, 'مبنى رقم ' . $building->building_number))
                        <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1.5 rounded-xl font-bold border border-blue-100/80 shadow-xs flex items-center gap-1">
                            <x-heroicon-s-hashtag class="w-3.5 h-3.5 text-blue-500" />
                            مبنى رقم {{ $building->building_number }}
                        </span>
                        @endif
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
                
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @if(auth()->user()->can('Update-Building') || auth()->user()->can('bhm.buildings.edit'))
                <a href="{{ route('buildings.buildings.edit', $building->id) }}" class="group relative inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-2xl shadow-lg shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 overflow-hidden">
                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    <x-heroicon-s-pencil-square class="w-4 h-4 relative z-10" />
                    <span class="relative z-10">تعديل البيانات</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Hero Section: Photo (Left 1/3) + Data & Location (Right 2/3) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
            
            {{-- Data & Location Card (Right side in RTL -> 2/3 of width) --}}
            <div class="lg:col-span-2 flex flex-col min-w-0">
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-full flex flex-col justify-between">
                    
                    <div>
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shrink-0">
                                    <x-heroicon-s-map-pin class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-lg font-extrabold text-gray-800">البيانات والموقع</h2>
                                    <p class="text-xs text-gray-400">معلومات المبنى الأساسية وموقعه الجغرافي</p>
                                </div>
                            </div>
                        </div>

                        {{-- Content Grid: Location Details (Right) + Building Stats Cards (Left) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-stretch">

                            {{-- تفاصيل الموقع --}}
                            <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 flex flex-col justify-between">
                                <div class="flex items-center gap-2 mb-3 pb-2.5 border-b border-gray-200/60">
                                    <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                        <x-heroicon-s-map class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">تفاصيل الموقع</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-200/50 border-dashed text-xs">
                                        <span class="text-gray-500 font-medium">المنطقة / الحي</span>
                                        <span class="font-bold text-gray-800">{{ $building->zone ? ($building->zone->name ?: 'مجمع ' . $building->zone->id) : '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-200/50 border-dashed text-xs">
                                        <span class="text-gray-500 font-medium">المنطقة الفرعية</span>
                                        <span class="font-bold text-gray-800">{{ $building->subzone ? ($building->subzone->name ?: $building->subzone->id) : '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-200/50 border-dashed text-xs">
                                        <span class="text-gray-500 font-medium">الشارع</span>
                                        <span class="font-bold text-gray-800 truncate max-w-[180px]" title="{{ $building->street ? ($building->street->street_name ? $building->street->street_name . ' (' . $building->street->street_number . ')' : 'شارع ' . $building->street->street_number) : '—' }}">
                                            @if($building->street)
                                                {{ $building->street->street_name ? $building->street->street_name . ' (' . $building->street->street_number . ')' : 'شارع ' . $building->street->street_number }}
                                            @else
                                                —
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-gray-200/50 border-dashed text-xs">
                                        <span class="text-gray-500 font-medium">رقم القطعة</span>
                                        <span class="font-mono font-bold text-gray-800 bg-white px-2.5 py-0.5 rounded-lg border border-gray-200 shadow-2xs">{{ $building->block_number ?? '—' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-2 text-xs">
                                        <span class="text-gray-500 font-medium">رقم القسيمة</span>
                                        <span class="font-mono font-bold text-gray-800 bg-white px-2.5 py-0.5 rounded-lg border border-gray-200 shadow-2xs">{{ $building->parcel_number ?? '—' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- بطاقات بيانات المبنى --}}
                            <div class="flex flex-col gap-2.5 justify-between">
                                {{-- Row 1: بطاقتين بجوار بعضهما (حالة المبنى + ملف حاسوب) --}}
                                <div class="grid grid-cols-2 gap-2">
                                    {{-- بطاقة حالة المبنى --}}
                                    <div class="group flex items-center gap-2.5 p-3 rounded-2xl bg-blue-50/40 hover:bg-blue-50/70 border border-blue-100/60 transition-all cursor-default">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center shrink-0 shadow-inner border border-blue-100/50 group-hover:scale-105 transition-transform">
                                            <x-heroicon-s-building-office class="w-4 h-4 text-blue-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-[10px] text-gray-400 font-bold mb-0.5 tracking-wider truncate">حالة المبنى</div>
                                            <div class="font-extrabold text-gray-800 text-xs truncate" title="{{ $building->buildingStatus->name ?? 'غير محدد' }}">
                                                {{ $building->buildingStatus->name ?? 'غير محدد' }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- بطاقة ملف أرشيف التنظيم الرئيسي --}}
                                    @php
                                        $targetArchiveFile = $primaryArchiveFile ?? null;
                                        $hasArchive = !is_null($targetArchiveFile);
                                    @endphp

                                    @if($hasArchive)
                                        {{-- عند وجود ملف تنظيم رئيسي: تحويل مباشر مع زر التغيير والربط --}}
                                        <div class="group flex items-center justify-between p-3 rounded-2xl bg-orange/5 hover:bg-orange/10 border border-orange/20 hover:border-orange/40 hover:shadow-sm transition-all shadow-2xs">
                                            <a href="{{ route('archive.files.show', $targetArchiveFile->id) }}" target="_blank"
                                               class="flex items-center gap-2.5 min-w-0 flex-1 cursor-pointer"
                                               title="عرض ملف أرشيف التنظيم الرئيسي في موديول الأرشيف">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange/20 to-orange/5 flex items-center justify-center shrink-0 shadow-inner border border-orange/10 group-hover:scale-105 transition-transform">
                                                    <x-heroicon-s-folder class="w-4 h-4 text-orange" />
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-[10px] text-orange-dark font-bold mb-0.5 tracking-wider truncate flex items-center gap-1">
                                                        <span>أرشيف التنظيم الرئيسي</span>
                                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" title="الملف مسجل بالأرشيف"></span>
                                                    </div>
                                                    <div class="font-extrabold text-gray-800 text-xs truncate font-mono" dir="ltr" title="{{ $targetArchiveFile->file_no }}">
                                                        {{ $targetArchiveFile->file_no }}
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="flex items-center gap-1 shrink-0 mr-1">
                                                <a href="{{ route('archive.files.show', $targetArchiveFile->id) }}" target="_blank"
                                                   class="p-1.5 text-orange/70 hover:text-orange hover:bg-orange/10 rounded-lg transition-colors"
                                                   title="فتح الملف في نافذة جديدة">
                                                    <x-heroicon-s-arrow-top-right-on-square class="w-3.5 h-3.5" />
                                                </a>
                                                @if(auth()->user()->can('Update-Building') || auth()->user()->can('bhm.buildings.edit') || auth()->user()->can('Read-Buildings'))
                                                <button type="button"
                                                        @click="openModal('primary')"
                                                        class="p-1.5 text-gray-400 hover:text-orange hover:bg-orange/10 rounded-lg transition-colors"
                                                        title="تغيير ملف أرشيف التنظيم الرئيسي">
                                                    <x-heroicon-s-arrows-right-left class="w-3.5 h-3.5" />
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- عند عدم وجود ملف أرشيف تنظيم رئيسي: زر لإظهار خيارات الربط أو الإنشاء --}}
                                        <button type="button"
                                                @click="openModal('primary')"
                                                class="group flex items-center justify-between p-3 rounded-2xl bg-orange/5 hover:bg-orange/10 border border-dashed border-orange/30 hover:border-orange/60 hover:shadow-sm transition-all cursor-pointer text-right w-full shadow-2xs"
                                                title="لا يوجد ملف أرشيف تنظيم رئيسي مسجل - انقر للربط أو الإنشاء">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange/20 to-orange/5 flex items-center justify-center shrink-0 shadow-inner border border-orange/10 group-hover:scale-105 transition-transform">
                                                    <x-heroicon-s-folder-plus class="w-4 h-4 text-orange" />
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-[10px] text-gray-400 font-bold mb-0.5 tracking-wider truncate">أرشيف التنظيم الرئيسي</div>
                                                    <div class="font-extrabold text-gray-800 text-xs truncate font-mono" dir="ltr" title="{{ $building->file_number }}">
                                                        @if(filled($building->file_number) && trim($building->file_number) !== '0' && trim($building->file_number) !== 'لا يوجد ملف')
                                                            {{ $building->file_number }}
                                                        @else
                                                            <span class="font-semibold text-gray-400 text-[10px] font-sans">(غير مسجل - انقر للربط)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-orange/60 group-hover:text-orange group-hover:scale-110 transition-all shrink-0 mr-1">
                                                <x-heroicon-s-plus-circle class="w-4 h-4" />
                                            </div>
                                        </button>
                                    @endif
                                </div>

                                {{-- نوع الملكية --}}
                                <div class="group flex items-center justify-between p-3 rounded-2xl bg-purple-50/30 hover:bg-purple-50/60 border border-purple-100/40 transition-all cursor-default">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center shrink-0 shadow-inner border border-purple-100/50 group-hover:scale-105 transition-transform">
                                            <x-heroicon-s-key class="w-4 h-4 text-purple-500" />
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold mb-0.5 tracking-wider">نوع الملكية</div>
                                            <div class="font-extrabold text-gray-800 text-xs">{{ $building->buildingPropertyType->name ?? 'غير محدد' }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- عدد الطوابق وعدد الوحدات --}}
                                <div class="grid grid-cols-2 gap-2">
                                    {{-- عدد الطوابق --}}
                                    <div class="group flex items-center gap-2.5 p-3 rounded-2xl bg-orange/5 hover:bg-orange/10 border border-orange/15 transition-all cursor-default">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange/20 to-orange/5 flex items-center justify-center shrink-0 shadow-inner border border-orange/10 group-hover:scale-105 transition-transform">
                                            <x-heroicon-s-squares-2x2 class="w-4 h-4 text-orange" />
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold mb-0.5 tracking-wider">عدد الطوابق</div>
                                            <div class="font-extrabold text-gray-800 text-xs flex items-baseline gap-1">
                                                <span class="text-base font-mono font-extrabold">{{ $building->floors->count() }}</span>
                                                <span class="text-gray-400 font-medium text-[10px]">طابق</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- عدد الوحدات --}}
                                    <div class="group flex items-center gap-2.5 p-3 rounded-2xl bg-emerald-50/30 hover:bg-emerald-50/60 border border-emerald-100/40 transition-all cursor-default">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center shrink-0 shadow-inner border border-emerald-100/50 group-hover:scale-105 transition-transform">
                                            <x-heroicon-s-home class="w-4 h-4 text-emerald-500" />
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-gray-400 font-bold mb-0.5 tracking-wider">عدد الوحدات</div>
                                            <div class="font-extrabold text-gray-800 text-xs flex items-baseline gap-1">
                                                <span class="text-base font-mono font-extrabold">{{ $building->units->count() }}</span>
                                                <span class="text-gray-400 font-medium text-[10px]">وحدة</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- Building Photo (Left side in RTL -> 1/3 of width) --}}
            <div class="lg:col-span-1 flex flex-col min-w-0">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col relative group min-h-[300px]">
                    @php $photo = $building->attachments->first(); @endphp
                    @if($photo && $photo->path)
                    <div class="relative w-full h-full min-h-[300px]">
                        <img src="{{ asset('storage/' . $photo->path) }}"
                             alt="صورة المبنى {{ $building->building_number }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
                    </div>
                    @else
                    <div class="p-3 w-full h-full min-h-[300px] flex flex-col">
                        <div class="w-full h-full flex-1 bg-gradient-to-br from-gray-50 to-gray-100 flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 relative">
                            <div class="absolute inset-0 bg-grid-slate-100/[0.04] bg-[size:20px_20px]"></div>
                            <div class="relative z-10 flex flex-col items-center text-center">
                                <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 text-gray-300">
                                    <x-heroicon-o-photo class="w-8 h-8" />
                                </div>
                                <span class="text-sm text-gray-600 font-bold mb-1">لا توجد صورة للمبنى</span>
                                <span class="text-xs text-gray-400 max-w-xs text-center">يرجى رفع صورة واضحة للمبنى لإضافتها إلى السجل</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Photo Overlay Controls --}}
                    @if($photo && $photo->path)
                    <div class="absolute bottom-0 left-0 right-0 p-5 flex items-end justify-between z-20">
                        <div>
                            <h3 class="text-white font-bold text-lg mb-0.5 shadow-sm">الواجهة الرئيسية</h3>
                            <p class="text-white/80 text-xs flex items-center gap-1">
                                <x-heroicon-o-clock class="w-3.5 h-3.5" />
                                أُضيفت {{ $photo->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Main Content: Tabs Section --}}
        <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-8">

            {{-- Tabs Header --}}
            <div id="building-tabs-header" class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/80 px-2 pt-2">
            <style>#building-tabs-header::-webkit-scrollbar{display:none}#building-tabs-header{-ms-overflow-style:none;scrollbar-width:none;}</style>
                <button @click="tab = 'basic'"
                        :class="{'text-orange-dark border-orange bg-white shadow-sm': tab === 'basic', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent': tab !== 'basic'}"
                        class="px-6 py-3.5 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2.5 border-b-2 rounded-t-xl mb-[-2px]">
                    <x-heroicon-s-building-library class="w-4 h-4" x-bind:class="{'text-orange': tab === 'basic'}" />
                    بيانات الإنشاء
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

                {{-- Tab 1: Construction & Notes --}}
                <div x-show="tab === 'basic'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-6" style="display: none;">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
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

                        {{-- Notes Block --}}
                        <div class="space-y-4">
                            @if($building->ownership_notes)
                            <div class="bg-amber-50/50 border border-amber-100/60 rounded-2xl p-5 relative overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-16 h-16 bg-amber-100 rounded-full opacity-50"></div>
                                <h4 class="font-bold text-amber-800 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5 relative z-10">
                                    <x-heroicon-s-document-text class="w-4 h-4" /> ملاحظات الملكية
                                </h4>
                                <p class="text-amber-900 text-sm leading-relaxed relative z-10">{{ $building->ownership_notes }}</p>
                            </div>
                            @endif

                            @if($building->overall_status_notes)
                            <div class="bg-blue-50/50 border border-blue-100/60 rounded-2xl p-5 relative overflow-hidden">
                                <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-100 rounded-full opacity-50"></div>
                                <h4 class="font-bold text-blue-800 text-xs uppercase tracking-wider mb-2 flex items-center gap-1.5 relative z-10">
                                    <x-heroicon-s-information-circle class="w-4 h-4" /> ملاحظات الحالة
                                </h4>
                                <p class="text-blue-900 text-sm leading-relaxed relative z-10">{{ $building->overall_status_notes }}</p>
                            </div>
                            @endif

                            @if(!$building->ownership_notes && !$building->overall_status_notes)
                            <div class="bg-gray-50/50 border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center text-center h-full min-h-[160px]">
                                <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mb-2">
                                    <x-heroicon-o-chat-bubble-bottom-center-text class="w-5 h-5" />
                                </div>
                                <p class="text-xs text-gray-400 font-medium">لا توجد ملاحظات إضافية مسجلة لهذا المبنى</p>
                            </div>
                            @endif
                        </div>
                    </div>

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
                            <thead class="bg-gray-100 text-black text-xs uppercase tracking-wider font-bold border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-black font-bold">المالك</th>
                                    <th class="px-6 py-4 text-black font-bold">رقم الهوية</th>
                                    <th class="px-6 py-4 text-black font-bold">رقم الهاتف</th>
                                    <th class="px-6 py-4 text-center text-black font-bold">الوحدات المملوكة</th>
                                    <th class="px-6 py-4 text-center text-black font-bold">إجراءات</th>
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
                                <thead class="bg-gray-100 text-black text-xs uppercase tracking-wider font-bold border-b border-gray-200">
                                    <tr>
                                        <th class="px-5 py-4 w-24 text-black font-bold">الوحدة</th>
                                        <th class="px-5 py-4 text-black font-bold">الطابق</th>
                                        <th class="px-5 py-4 text-black font-bold">النوع</th>
                                        <th class="px-5 py-4 text-black font-bold">وصف النشاط</th>
                                        <th class="px-5 py-4 text-black font-bold">المالك</th>
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
                    
                    {{-- شريط التحكم ورأس تبويب الأرشيف --}}
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0 shadow-inner">
                                <x-heroicon-s-folder-open class="w-6 h-6" />
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-extrabold text-gray-800">ملفات الأرشيف المرتبطة بالمبنى</h3>
                                    <span class="bg-orange/10 text-orange-dark text-xs font-black px-2.5 py-0.5 rounded-full">
                                        {{ isset($archiveFiles) ? $archiveFiles->count() : 0 }} ملفات
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    يمكنك ربط هذا المبنى بملفات أرشيفية متعددة (أرشيف التنظيم، أرشيف الجباية، أرشيف الحرف والصناعات، الدائرة القانونية، وغيرها).
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if(auth()->user()->can('Update-Building') || auth()->user()->can('bhm.buildings.edit') || auth()->user()->can('Read-Buildings'))
                            <button type="button"
                                    @click="openModal('additional')"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-orange to-orange-dark hover:from-orange-dark hover:to-orange text-white rounded-2xl font-bold text-xs sm:text-sm shadow-md shadow-orange/20 hover:shadow-lg transition-all cursor-pointer">
                                <x-heroicon-s-link class="w-4 h-4" />
                                <span>ربط ملف أرشيف جديد</span>
                            </button>
                            @endif

                            <a href="{{ route('archive.files.create', ['type_id' => 1]) }}" target="_blank"
                               class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-bold text-xs sm:text-sm transition-all"
                               title="فتح شاشة إنشاء ملف جديد في نظام الأرشيف">
                                <x-heroicon-s-plus class="w-4 h-4 text-gray-500" />
                                <span>إنشاء ملف جديد بالأرشيف</span>
                            </a>
                        </div>
                    </div>

                    {{-- شبكة بطاقات ملفات الأرشيف المرتبطة --}}
                    @if(isset($archiveFiles) && $archiveFiles->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            @foreach($archiveFiles as $archiveFile)
                            @php
                                $typeId = $archiveFile->archive_file_type_id;
                                $isPrimary = (bool)($archiveFile->pivot?->is_primary);
                                $typeTheme = match($typeId) {
                                    1 => [
                                        'border' => 'border-blue-200 hover:border-blue-400',
                                        'icon_bg' => 'bg-blue-100 text-blue-700',
                                        'badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    ],
                                    3 => [
                                        'border' => 'border-amber-200 hover:border-amber-400',
                                        'icon_bg' => 'bg-amber-100 text-amber-700',
                                        'badge' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    ],
                                    4 => [
                                        'border' => 'border-purple-200 hover:border-purple-400',
                                        'icon_bg' => 'bg-purple-100 text-purple-700',
                                        'badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ],
                                    5 => [
                                        'border' => 'border-emerald-200 hover:border-emerald-400',
                                        'icon_bg' => 'bg-emerald-100 text-emerald-700',
                                        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ],
                                    2 => [
                                        'border' => 'border-sky-200 hover:border-sky-400',
                                        'icon_bg' => 'bg-sky-100 text-sky-700',
                                        'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
                                    ],
                                    6 => [
                                        'border' => 'border-slate-200 hover:border-slate-400',
                                        'icon_bg' => 'bg-slate-200 text-slate-700',
                                        'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ],
                                    default => [
                                        'border' => 'border-gray-200 hover:border-gray-400',
                                        'icon_bg' => 'bg-gray-100 text-gray-700',
                                        'badge' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    ],
                                };
                            @endphp
                            <div class="bg-white border {{ $isPrimary ? 'border-amber-300 ring-2 ring-amber-400/20 shadow-md' : $typeTheme['border'] }} rounded-3xl p-5 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                                <div>
                                    {{-- ترويسة البطاقة: النوع وشارة الرئيسي --}}
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-2xl {{ $typeTheme['icon_bg'] }} flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                                                <x-heroicon-s-folder class="w-5 h-5" />
                                            </div>
                                            <span class="text-xs font-bold px-2.5 py-1 rounded-xl border {{ $typeTheme['badge'] }}">
                                                {{ $archiveFile->fileType->name ?? 'ملف أرشيف' }}
                                            </span>
                                        </div>

                                        @if($isPrimary)
                                            <span class="bg-gradient-to-r from-amber-400 to-orange-500 text-white font-black text-[10px] px-2.5 py-1 rounded-xl shadow-xs flex items-center gap-1">
                                                <x-heroicon-s-star class="w-3.5 h-3.5" />
                                                <span>ملف التنظيم الرئيسي</span>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- اسم الملف ورقم الملف --}}
                                    <h4 class="text-sm font-extrabold text-gray-900 mb-2 line-clamp-2 leading-relaxed" title="{{ $archiveFile->file_name }}">
                                        {{ $archiveFile->file_name }}
                                    </h4>

                                    <div class="flex flex-wrap items-center gap-2 text-xs mb-3">
                                        <span class="text-gray-400 font-bold">رقم الملف:</span>
                                        <span class="font-mono font-black text-xs px-2.5 py-0.5 rounded-lg bg-gray-100 text-gray-800 border border-gray-200/80" dir="ltr">
                                            {{ $archiveFile->file_no }}
                                        </span>
                                    </div>

                                    {{-- عدد الوثائق --}}
                                    <div class="text-xs text-gray-500 font-semibold flex items-center gap-1.5">
                                        <x-heroicon-s-document-text class="w-4 h-4 text-gray-400" />
                                        <span>{{ $archiveFile->documents_count ?? $archiveFile->documents->count() }} وثيقة مؤرشفة</span>
                                    </div>
                                </div>

                                {{-- شريط الإجراءات أسفل البطاقة --}}
                                <div class="border-t border-gray-100 pt-3 mt-4 flex items-center justify-between gap-2">
                                    <a href="{{ route('archive.files.show', $archiveFile->id) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl transition-colors">
                                        <span>فتح بالأرشيف</span>
                                        <x-heroicon-s-arrow-top-right-on-square class="w-3.5 h-3.5" />
                                    </a>

                                    <div class="flex items-center gap-1">
                                        @if(!$isPrimary && (auth()->user()->can('Update-Building') || auth()->user()->can('bhm.buildings.edit') || auth()->user()->can('Read-Buildings')))
                                            <button type="button"
                                                    @click="setPrimaryFile({{ $archiveFile->id }})"
                                                    class="text-[11px] font-bold text-amber-700 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-2.5 py-1.5 rounded-xl transition-colors"
                                                    title="تعيين هذا الملف كملف تنظيم رئيسي للمبنى">
                                                تعيين كرئيسي
                                            </button>
                                        @endif

                                        @if(auth()->user()->can('Update-Building') || auth()->user()->can('bhm.buildings.edit') || auth()->user()->can('Read-Buildings'))
                                            <button type="button"
                                                    @click="unlinkSpecificFile({{ $archiveFile->id }}, '{{ addslashes($archiveFile->file_no) }}')"
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors"
                                                    title="إلغاء ربط هذا الملف بالمبنى">
                                                <x-heroicon-s-trash class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        {{-- حالة عدم وجود ملفات أرشيف --}}
                        <div class="text-center py-20 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 mx-auto mb-4">
                                <x-heroicon-o-folder class="w-10 h-10 text-gray-300" />
                            </div>
                            <h4 class="text-lg font-bold text-gray-700 mb-1">لا توجد ملفات أرشيف مرتبطة</h4>
                            <p class="text-sm text-gray-500 mb-5 max-w-md mx-auto">
                                لم يتم ربط هذا المبنى بأي ملفات أرشيفية بعد. يمكنك البحث في أرشيف البلدية وربط ملفات التنظيم أو الجباية أو الحرف.
                            </p>
                            <button type="button"
                                    @click="openModal('additional')"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange hover:bg-orange-dark text-white rounded-xl font-bold text-sm shadow-sm transition-colors cursor-pointer">
                                <x-heroicon-s-folder-plus class="w-4 h-4" />
                                <span>البحث وربط ملف أرشيف الآن</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- نافذة إدارة والبحث عن ملفات الأرشيف والربط المتقدم --}}
        <template x-teleport="body">
            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs overflow-y-auto"
                 style="display: none;"
                 @keydown.escape.window="closeModal()">
                
                {{-- خلفية معتمة قابلة للنقر للإغلاق --}}
                <div class="fixed inset-0" @click="closeModal()" aria-hidden="true"></div>

                <div @click.stop
                     x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-3xl overflow-hidden relative transition-all my-auto z-10">
                
                {{-- رأس النافذة --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/70">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0 shadow-inner">
                            <x-heroicon-s-folder-plus class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-800"
                                x-text="targetMode === 'primary' ? 'ربط ملف أرشيف التنظيم الرئيسي' : 'ربط ملف أرشيف بالمبنى (متعدد الأقسام)'">
                            </h3>
                            <p class="text-xs text-gray-400"
                               x-text="targetMode === 'primary' ? 'اختر ملف الأرشيف التنظيمي لتعيينه كملف رئيسي يظهر بأعلى بطاقة المبنى' : 'ابحث برقم الملف أو اسم المالك لربطه بقائمة ملفات الأرشيف التابعة للمبنى'">
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- محتوى النافذة --}}
                <div class="p-6">
                    {{-- رسائل التنبيه والنجاح --}}
                    <div x-show="errorMessage" x-transition class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                        <x-heroicon-s-x-circle class="w-4 h-4 shrink-0 text-red-500" />
                        <span x-text="errorMessage"></span>
                    </div>

                    <div x-show="successMessage" x-transition class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                        <x-heroicon-s-check-circle class="w-4 h-4 shrink-0 text-emerald-500" />
                        <span x-text="successMessage"></span>
                    </div>

                    {{-- قسم الفلترة وحقل البحث --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        {{-- تصنيف الأرشيف --}}
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 mb-1">قسم / نوع الأرشيف:</label>
                            <select x-model="selectedTypeId"
                                    @change="performSearch()"
                                    class="w-full py-2.5 px-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:border-orange focus:ring-2 focus:ring-orange/20 transition-all">
                                <option value="">جميع أقسام الأرشيف</option>
                                @if(isset($archiveFileTypes))
                                    @foreach($archiveFileTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- حقل البحث بالاسم أو الرقم --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">البحث برقم الملف أو اسم المواطن / المالك:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                                </div>
                                <input type="text"
                                       x-ref="searchInput"
                                       x-model="searchQuery"
                                       @input.debounce.300ms="performSearch()"
                                       placeholder="ابحث برقم الملف (مثال: 9-34 أو ا-10-12) أو اسم المالك..."
                                       class="w-full pr-11 pl-10 py-2.5 bg-gray-50/80 border border-gray-200 rounded-xl text-xs sm:text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-orange focus:ring-2 focus:ring-orange/20 transition-all">
                                
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <template x-if="isSearching">
                                        <svg class="animate-spin w-4 h-4 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    </template>
                                    <template x-if="!isSearching && searchQuery">
                                        <button @click="searchQuery = ''; performSearch()" class="text-gray-400 hover:text-gray-600 p-1">
                                            <x-heroicon-s-x-mark class="w-4 h-4" />
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- قائمة نتائج البحث --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-xs font-bold text-gray-600">اختر ملفاً من النتائج أدناه للربط:</span>
                            <span class="text-[11px] text-gray-400" x-text="searchResults.length + ' نتيجة'"></span>
                        </div>

                        <div class="max-h-60 overflow-y-auto divide-y divide-gray-100 rounded-2xl border border-gray-200 bg-white">
                            <template x-for="file in searchResults" :key="file.id">
                                <div @click="selectFile(file)"
                                     :class="{
                                         'bg-orange/10 border-orange/40 text-orange-dark font-bold': selectedFile && selectedFile.id === file.id,
                                         'hover:bg-gray-50/80 cursor-pointer': !file.is_already_linked,
                                         'opacity-60 bg-gray-50/40 cursor-not-allowed': file.is_already_linked
                                     }"
                                     class="p-3.5 flex items-center justify-between transition-colors group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div :class="selectedFile && selectedFile.id === file.id ? 'bg-orange text-white' : 'bg-orange/10 text-orange group-hover:scale-105'" 
                                             class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-transform shadow-2xs">
                                            <x-heroicon-s-folder class="w-4 h-4" />
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-bold text-gray-800 truncate" x-text="file.file_name"></div>
                                            <div class="text-[11px] text-gray-400 flex items-center gap-2 mt-0.5">
                                                <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded text-[10px]" dir="ltr" x-text="file.file_no"></span>
                                                <span>•</span>
                                                <span class="font-bold text-gray-600" x-text="file.file_type"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <template x-if="file.is_already_linked">
                                            <span class="text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full font-bold flex items-center gap-1">
                                                <x-heroicon-s-check-circle class="w-3.5 h-3.5 text-emerald-600" />
                                                <span>مرتبط بالمبنى</span>
                                            </span>
                                        </template>

                                        <template x-if="!file.is_already_linked">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-bold" x-text="file.documents_count + ' وثيقة'"></span>
                                                <div :class="selectedFile && selectedFile.id === file.id ? 'border-orange bg-orange text-white' : 'border-gray-300 text-transparent'"
                                                     class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors">
                                                    <x-heroicon-s-check class="w-3.5 h-3.5" />
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!isSearching && searchResults.length === 0">
                                <div class="p-8 text-center text-gray-400 text-xs">
                                    <x-heroicon-o-folder-open class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                    <span>لم يتم العثور على ملفات مطابقة. يرجى تجربة كلمات بحث أخرى أو تغيير تصنيف الأرشيف.</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- بطاقة تأكيد الملف المختار للربط --}}
                    <div x-show="selectedFile" x-transition class="mt-4 border border-emerald-200 rounded-2xl overflow-hidden shadow-sm bg-white">
                        <div class="bg-emerald-50/70 px-4 py-2.5 border-b border-emerald-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-600" />
                                <span>بيانات الملف المختار للربط</span>
                            </span>
                            <span class="text-[11px] text-emerald-600 font-bold">جاهز للربط</span>
                        </div>

                        <div class="p-4 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <div class="text-[10px] text-gray-400 font-bold mb-1">نوع الملف</div>
                                    <div class="font-bold text-blue-700" x-text="selectedFile ? selectedFile.file_type : ''"></div>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 sm:col-span-2">
                                    <div class="text-[10px] text-gray-400 font-bold mb-1">اسم ورقم الملف</div>
                                    <div class="font-extrabold text-gray-900 flex items-center gap-2">
                                        <span x-text="selectedFile ? selectedFile.file_name : ''"></span>
                                        <span class="font-mono bg-white px-2 py-0.5 rounded border border-gray-200 text-xs font-bold" dir="ltr" x-text="selectedFile ? selectedFile.file_no : ''"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- خيار التعيين كملف تنظيم رئيسي --}}
                            <div class="flex items-center justify-between p-3 bg-amber-50/60 rounded-xl border border-amber-200/80">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800">
                                    <input type="checkbox" x-model="markAsPrimary" :disabled="targetMode === 'primary'" class="rounded text-orange focus:ring-orange/20 w-4 h-4">
                                    <span>تعيين هذا الملف كملف تنظيم رئيسي للمبنى (يظهر في الخانة العلوية)</span>
                                </label>
                                <span class="text-[11px] text-amber-700 font-medium" x-text="markAsPrimary ? 'سيتم عرضه كملف رئيسي في أعلى بطاقة المبنى' : 'سيضاف كملف إضافي في تبويب الأرشيف'"></span>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="button"
                                        @click="linkSelectedFile()"
                                        :disabled="isLinking"
                                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all disabled:opacity-50 cursor-pointer">
                                    <template x-if="!isLinking">
                                        <span class="flex items-center gap-1.5">
                                            <x-heroicon-s-link class="w-4 h-4" />
                                            <span>تأكيد ربط الملف بهذا المبنى</span>
                                        </span>
                                    </template>
                                    <template x-if="isLinking">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                            <span>جاري الربط...</span>
                                        </span>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <a href="{{ route('archive.files.create', ['type_id' => 1]) }}" target="_blank" class="text-xs text-orange hover:text-orange-dark font-bold flex items-center gap-1">
                            <x-heroicon-s-plus-circle class="w-4 h-4" />
                            <span>إنشاء ملف جديد في نظام الأرشيف</span>
                        </a>

                        <button @click="closeModal()" type="button" class="px-5 py-2 text-gray-500 hover:bg-gray-100 rounded-xl font-bold text-xs transition-colors cursor-pointer">
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </template>

    </div>

    @push('scripts')
    <script>
        function buildingArchiveLinking(buildingId, initialFileNo, defaultSearchQuery) {
            return {
                showModal: false,
                targetMode: 'additional',
                searchQuery: defaultSearchQuery || initialFileNo || '',
                selectedTypeId: '',
                searchResults: [],
                selectedFile: null,
                markAsPrimary: false,
                isSearching: false,
                isLinking: false,
                isUnlinking: false,
                isSettingPrimary: false,
                errorMessage: '',
                successMessage: '',

                openModal(target = 'additional') {
                    this.targetMode = target;
                    this.showModal = true;
                    this.selectedFile = null;
                    this.errorMessage = '';
                    this.successMessage = '';

                    if (target === 'primary') {
                        this.selectedTypeId = '1';
                        this.markAsPrimary = true;
                    } else {
                        this.selectedTypeId = '';
                        this.markAsPrimary = false;
                    }

                    this.performSearch();
                    document.body.style.overflow = 'hidden';

                    this.$nextTick(() => {
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.errorMessage = '';
                    this.successMessage = '';
                    document.body.style.overflow = '';
                },

                async performSearch() {
                    this.isSearching = true;
                    this.errorMessage = '';
                    try {
                        const params = new URLSearchParams({
                            q: this.searchQuery,
                            type_id: this.selectedTypeId,
                            building_id: buildingId
                        });
                        const res = await fetch(`{{ route('buildings.buildings.search-archive-files') }}?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.searchResults = json.data;
                        } else {
                            this.searchResults = [];
                        }
                    } catch (err) {
                        console.error(err);
                        this.searchResults = [];
                    } finally {
                        this.isSearching = false;
                    }
                },

                selectFile(file) {
                    if (file.is_already_linked) {
                        return;
                    }
                    this.selectedFile = file;
                    this.errorMessage = '';
                },

                async linkSelectedFile() {
                    if (!this.selectedFile) return;
                    this.isLinking = true;
                    this.errorMessage = '';
                    try {
                        const res = await fetch(`{{ url('/buildings/buildings') }}/${buildingId}/link-archive-file`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                archive_file_id: this.selectedFile.id,
                                file_no: this.selectedFile.file_no,
                                is_primary: this.markAsPrimary
                            })
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            this.successMessage = json.message || 'تم ربط المبنى بملف الأرشيف بنجاح!';
                            setTimeout(() => {
                                window.location.reload();
                            }, 700);
                        } else {
                            this.errorMessage = json.message || 'حدث خطأ أثناء الربط، يرجى المحاولة مرة أخرى.';
                        }
                    } catch (err) {
                        this.errorMessage = 'فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.';
                    } finally {
                        this.isLinking = false;
                    }
                },

                async unlinkSpecificFile(fileId, fileNo) {
                    if (!confirm(`هل أنت متأكد من رغبتك في إلغاء ربط ملف الأرشيف رقم (${fileNo}) بهذا المبنى؟`)) {
                        return;
                    }
                    this.isUnlinking = true;
                    try {
                        const res = await fetch(`{{ url('/buildings/buildings') }}/${buildingId}/unlink-archive-file`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                archive_file_id: fileId
                            })
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            window.location.reload();
                        } else {
                            alert(json.message || 'حدث خطأ أثناء إلغاء الربط.');
                        }
                    } catch (err) {
                        alert('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.');
                    } finally {
                        this.isUnlinking = false;
                    }
                },

                async setPrimaryFile(fileId) {
                    if (!confirm('هل تريد تعيين هذا الملف كملف أرشيف تنظيم رئيسي للمبنى؟')) {
                        return;
                    }
                    this.isSettingPrimary = true;
                    try {
                        const res = await fetch(`{{ url('/buildings/buildings') }}/${buildingId}/set-primary-archive-file`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                archive_file_id: fileId
                            })
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            window.location.reload();
                        } else {
                            alert(json.message || 'حدث خطأ أثناء تعيين الملف الرئيسي.');
                        }
                    } catch (err) {
                        alert('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.');
                    } finally {
                        this.isSettingPrimary = false;
                    }
                }
            };
        }
    </script>
    @endpush
</x-buildings-layout>
