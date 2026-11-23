<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة حصر الأضرار والخريطة التفاعلية • بلدية بيت حانون</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (via Vite or CDN for standalone consistency) -->
    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Cairo', sans-serif; }
        .leaflet-popup-content-wrapper { border-radius: 1rem; padding: 0.5rem; font-family: 'Cairo', sans-serif; }
        .leaflet-container { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="damagePortalApp()">

    <!-- Top Municipal Navigation -->
    <header class="text-white sticky top-0 z-40 shadow-lg border-b border-teal-700/50"
            style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%); background-color: #0f4c47;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white/10 p-1.5 flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="w-full h-full object-contain filter drop-shadow">
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-[11px] font-black tracking-wide text-amber-300">بلدية بيت حانون</span>
                        <span class="text-[10px] bg-amber-400/20 text-amber-300 font-bold px-2 py-0.5 rounded-full border border-amber-400/30">بوابة الاستعلام والتقارير العامة</span>
                    </div>
                    <h1 class="text-base sm:text-lg font-black text-white leading-tight">منظومة حصر الأضرار وتقييم الاحتياجات والخريطة التفاعلية</h1>
                    <p class="text-[11px] text-teal-100/80 hidden sm:block">بوابة الاستعلام المفتوح والتقارير الرسمية لإعادة إعمار وتأهيل مدينة بيت حانون</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('damage.portal.report') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-amber-500 hover:bg-amber-400 text-slate-950 transition-all shadow-md flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-950" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                    <span>التقرير التوثيقي الرسمي</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        <!-- 1. High Impact Hero KPI Counter -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Damage Recovery USD -->
            <div class="col-span-2 lg:col-span-1 rounded-3xl p-5 text-white shadow-md relative overflow-hidden border border-teal-600/30 flex flex-col justify-between"
                 style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f766e 100%);">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-teal-100">إجمالي تكلفة التعافي والاحتياجات التقديرية</span>
                        <span class="p-1 rounded-lg bg-white/10 text-amber-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                        </span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black text-amber-300 font-mono tracking-tight">${{ number_format($totalCostUsd / 1000000, 1) }}M</div>
                    <div class="text-xs font-semibold text-teal-100 mt-1">
                        {{ number_format($totalCostUsd, 0) }} دولار أمريكي
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-teal-500/30 text-[11px] font-bold text-teal-100 flex items-center justify-between">
                    <span>المعادل بالشيكل:</span>
                    <span class="text-amber-200 font-mono">₪{{ number_format($totalCostIls / 1000000, 1) }} مليون</span>
                </div>
            </div>

            <!-- Destroyed Entities Count -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold text-slate-500">إجمالي السجلات والمنشآت المتضررة</span>
                    <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2 font-mono">
                    {{ number_format($totalDamages) }}
                </div>
                <div class="mt-3 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-xl w-fit flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                    <span>{{ number_format($totalTotalDestruction) }} منشأة مهدمة كلياً</span>
                </div>
            </div>

            <!-- Housing Units & Roads -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold text-slate-500">الوحدات السكنية المتضررة</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2 font-mono">
                    {{ number_format($housingUnitsDestroyed) }} <span class="text-xs font-bold text-slate-400">وحدة</span>
                </div>
                <div class="mt-3 text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-xl w-fit">
                    أطوال الطرق المتضررة: <span class="font-extrabold text-teal-700 font-mono">{{ $roadLengthKm }} كم</span>
                </div>
            </div>

            <!-- Facilities & Economic -->
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-bold text-slate-500">المرافق العامة والحرفية</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-2 font-mono">
                    {{ number_format($publicFacilitiesCount + $economicUnitsCount) }}
                </div>
                <div class="mt-3 text-[11px] font-bold text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-xl w-fit">
                    {{ $publicFacilitiesCount }} مرفق حيوي • {{ $economicUnitsCount }} اقتصادي
                </div>
            </div>
        </div>

        <!-- 2. Interactive GIS Map Section with Layer Controls -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden relative">

            <!-- Map Header & Filter Controls -->
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-900">الخريطة التفاعلية لأضرار مدينة بيت حانون</h2>
                        <p class="text-xs text-slate-500 font-medium">انقر على أي نقطة أو منشأة على الخريطة لعرض تفاصيل الضرر والتقدير المالي</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Sector Filter -->
                    <select x-model="selectedSector" @change="reloadMapData()" class="text-xs font-bold rounded-xl border-slate-300 bg-white text-slate-700 focus:border-teal-600 focus:ring-teal-600 shadow-xs">
                        <option value="all">كافة القطاعات</option>
                        <option value="real_estate">المباني والعقارات</option>
                        <option value="infrastructure">البنية التحتية والطرق</option>
                        <option value="public_facilities">المرافق العامة الحيوية</option>
                        <option value="economic_crafts">القطاع الاقتصادي والحرفي</option>
                    </select>

                    <!-- Damage Level Filter -->
                    <select x-model="selectedDamageLevel" @change="reloadMapData()" class="text-xs font-bold rounded-xl border-slate-300 bg-white text-slate-700 focus:border-teal-600 focus:ring-teal-600 shadow-xs">
                        <option value="all">كافة مستويات الضرر</option>
                        <option value="total_destruction">مهدم كلياً</option>
                        <option value="severe_partial">جزئي بليغ</option>
                        <option value="moderate_partial">جزئي متوسط</option>
                    </select>

                    <!-- Loading Indicator -->
                    <div x-show="loading" class="text-xs font-bold text-teal-700 flex items-center gap-1.5 px-2">
                        <svg class="animate-spin w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span>جاري التحميل...</span>
                    </div>
                </div>
            </div>

            <!-- Map Wrapper with Live Slide-Out Drawer -->
            <div class="relative w-full h-[580px]">
                <!-- Leaflet Map Canvas -->
                <div id="damageMap" class="w-full h-full z-10"></div>

                <!-- Slide-Out Map Drawer -->
                <div x-show="drawerOpen" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="absolute top-0 right-0 w-full sm:w-96 h-full bg-white/95 backdrop-blur-md border-l border-slate-200 shadow-2xl z-20 p-6 flex flex-col justify-between overflow-y-auto"
                     style="display: none;">
                    
                    <div>
                        <!-- Drawer Header -->
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                            <span class="text-xs font-mono font-bold text-teal-700" x-text="activeItem.record_number"></span>
                            <button @click="drawerOpen = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- Item Title & Sector -->
                        <h3 class="text-lg font-black text-slate-900 leading-snug mb-2" x-text="activeItem.name"></h3>
                        
                        <div class="flex items-center gap-2 mb-4 flex-wrap">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200"
                                  x-text="activeItem.damage_level === 'total_destruction' ? 'مهدم كلياً' : 'ضرر جزئي'"></span>
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-100 text-slate-700"
                                  x-text="getSectorName(activeItem.sector)"></span>
                            <template x-if="activeItem.urgent">
                                <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-rose-600 text-white animate-pulse">
                                    تدخل عاجل
                                </span>
                            </template>
                        </div>

                        <!-- Financial Estimation Box -->
                        <div class="p-4 text-white rounded-2xl mb-4 shadow-sm border border-teal-600/30"
                             style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%);">
                            <div class="text-[11px] font-bold text-teal-100">التقدير المالي للتعافي وإعادة التأهيل</div>
                            <div class="text-2xl font-black text-amber-300 mt-1 font-mono" x-text="'$' + Number(activeItem.cost_usd || 0).toLocaleString()"></div>
                            <div class="text-xs font-semibold text-teal-100/90 mt-0.5" x-text="'ما يعادل: ₪' + Number(activeItem.cost_ils || 0).toLocaleString() + ' شيكل'"></div>
                        </div>

                        <!-- Technical Specs -->
                        <div class="space-y-2 text-xs bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-4">
                            <template x-if="activeItem.sector === 'infrastructure'">
                                <div>
                                    <div class="flex justify-between py-1 border-b border-slate-200/60">
                                        <span class="text-slate-500">طول الطريق المتضرر:</span>
                                        <span class="font-bold text-slate-900" x-text="activeItem.length_m + ' متر طولي'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-slate-200/60">
                                        <span class="text-slate-500">حالة الشبكات:</span>
                                        <span class="font-bold text-rose-600">تدمير كامل للشبكات المرافقة</span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="activeItem.sector !== 'infrastructure'">
                                <div>
                                    <div class="flex justify-between py-1 border-b border-slate-200/60">
                                        <span class="text-slate-500">مساحة المسقط:</span>
                                        <span class="font-bold text-slate-900" x-text="activeItem.area_sqm + ' م²'"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-slate-200/60">
                                        <span class="text-slate-500">عدد الوحدات المتضررة:</span>
                                        <span class="font-bold text-slate-900" x-text="activeItem.units + ' وحدة'"></span>
                                    </div>
                                </div>
                            </template>
                            <template x-if="activeItem.block || activeItem.parcel">
                                <div class="flex justify-between py-1">
                                    <span class="text-slate-500">رقم الحوض والقطعة:</span>
                                    <span class="font-bold text-slate-900 font-mono" x-text="'حوض: ' + (activeItem.block || '-') + ' / قطعة: ' + (activeItem.parcel || '-')"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Privacy Note -->
                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/60 text-[11px] text-amber-900 leading-relaxed font-medium">
                            <span class="font-bold">سياسة الخصوصية وحماية البيانات:</span> تم حجب بيانات الملكية وأرقام الهويات الشخصية في هذه البوابة المفتوحة حمايةً لخصوصية المواطنين.
                        </div>
                    </div>

                    <!-- Footer Action in Drawer -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" @click="activeItem = null" 
                                class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition-all cursor-pointer">
                            إغلاق المعاينة
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Legend -->
            <div class="p-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-center gap-4 sm:gap-8 flex-wrap text-xs font-bold text-slate-600">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-rose-600"></span> مباني وعقارات سكنية
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span> طرق وبنية تحتية وشبكات
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> مرافق عامة ومدارس ومساجد
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-purple-600"></span> منشآت اقتصادية وتجارية
                </span>
            </div>
        </div>

        <!-- 3. Sector Aggregations & Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $portalSectors = [
                    'real_estate' => [
                        'title' => 'قطاع الإسكان والعقارات',
                        'desc' => 'المباني والأبراج والمنازل السكنية في كافة أحياء المدينة',
                        'badge_class' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'dot_class' => 'bg-rose-600',
                    ],
                    'infrastructure' => [
                        'title' => 'البنية التحتية والشبكات',
                        'desc' => 'شبكات المياه والصرف الصحي وتعبيد الشوارع والإنارة',
                        'badge_class' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'dot_class' => 'bg-amber-500',
                    ],
                    'public_facilities' => [
                        'title' => 'المرافق العامة والحيوية',
                        'desc' => 'المدارس، المراكز الصحية، المساجد، والمقرات الخدماتية',
                        'badge_class' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'dot_class' => 'bg-emerald-600',
                    ],
                    'economic_crafts' => [
                        'title' => 'القطاع الاقتصادي والتجاري',
                        'desc' => 'الورش الحرفية، المحال والمتاجر، والمؤسسات الاقتصادية',
                        'badge_class' => 'bg-purple-50 text-purple-800 border-purple-200',
                        'dot_class' => 'bg-purple-600',
                    ],
                ];
            @endphp

            @foreach($portalSectors as $sKey => $sec)
                @php
                    $sData = $sectorStats->get($sKey);
                    $sCount = $sData ? $sData->count : 0;
                    $sUsd = $sData ? (float)$sData->total_usd : 0;
                @endphp
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-slate-800 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full {{ $sec['dot_class'] }}"></span>
                                <span>{{ $sec['title'] }}</span>
                            </span>
                            <span class="text-xs font-black px-2 py-0.5 rounded-lg border {{ $sec['badge_class'] }} font-mono">{{ number_format($sCount) }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 font-medium leading-relaxed mb-4">{{ $sec['desc'] }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400">الاحتياج المقدر:</span>
                        <span class="font-black text-slate-900 font-mono">${{ number_format($sUsd, 0) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>
                بلدية بيت حانون • قطاع غزة • منظومة حصر الأضرار وتقييم الاحتياجات &copy; {{ date('Y') }}
            </div>
            <div class="flex items-center gap-4 text-xs font-bold text-slate-600">
                <a href="{{ route('damage.portal.report') }}" class="hover:text-teal-700 transition-colors">التقرير التوثيقي الرسمي</a>
                <span>•</span>
                <a href="{{ route('login') }}" class="text-teal-700 hover:text-teal-800 font-bold transition-colors">بوابة موظفي البلدية</a>
            </div>
        </div>
    </footer>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        function damagePortalApp() {
            return {
                map: null,
                markersLayer: null,
                selectedSector: 'all',
                selectedDamageLevel: 'all',
                loading: false,
                drawerOpen: false,
                activeItem: {},

                init() {
                    this.initMap();
                    this.reloadMapData();
                },

                initMap() {
                    // Center on Beit Hanoun: [31.5422, 34.5345]
                    this.map = L.map('damageMap').setView([31.5422, 34.5345], 14);

                    // OpenStreetMap Standard Tiles
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors • Beit Hanoun Municipality'
                    }).addTo(this.map);

                    this.markersLayer = L.layerGroup().addTo(this.map);
                },

                reloadMapData() {
                    this.loading = true;
                    const url = `{{ route('damage.portal.mapData') }}?sector=${this.selectedSector}&damage_level=${this.selectedDamageLevel}`;

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.markersLayer.clearLayers();

                            const sectorColors = {
                                'real_estate': '#e11d48',       // Rose/Red
                                'infrastructure': '#d97706',    // Amber
                                'public_facilities': '#059669', // Emerald
                                'economic_crafts': '#7c3aed',   // Purple
                                'municipal_assets': '#ea580c',  // Orange
                            };

                            data.features.forEach(item => {
                                const color = sectorColors[item.sector] || '#ea580c';
                                const radius = item.damage_level === 'total_destruction' ? 7 : 5;

                                const marker = L.circleMarker([item.lat, item.lng], {
                                    radius: radius,
                                    fillColor: color,
                                    color: '#ffffff',
                                    weight: 1.5,
                                    opacity: 1,
                                    fillOpacity: 0.85
                                });

                                marker.on('click', () => {
                                    this.activeItem = item;
                                    this.drawerOpen = true;
                                });

                                this.markersLayer.addLayer(marker);
                            });

                            this.loading = false;
                        })
                        .catch(() => {
                            this.loading = false;
                        });
                },

                getSectorName(sec) {
                    const names = {
                        'real_estate': 'مباني وعقارات سكنية',
                        'infrastructure': 'بنية تحتية وشبكات',
                        'public_facilities': 'مرافق عامة وحيوية',
                        'economic_crafts': 'قطاع اقتصادي وحرفي',
                        'municipal_assets': 'أصول بلدية',
                    };
                    return names[sec] || sec;
                }
            };
        }
    </script>
</body>
</html>
