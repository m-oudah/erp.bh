<x-customers-layout>
    <div class="space-y-6">
        
        {{-- Dashboard Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-orange/10 text-orange-dark border border-orange/20">
                        لوحة التحكم المركزية
                    </span>
                    <span class="text-xs text-gray-400">بلدية بيت حانون</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                    <span>نظام المشتركين وسجل المواطنين</span>
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    إدارة شاملة لبيانات المواطنين، الاشتراكات والخدمات البلدية، والربط مع العقارات والأنشطة
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('customers.citizens.index') }}" class="inline-flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-2xl shadow-2xs transition-colors text-xs">
                    <x-heroicon-o-user-group class="w-4 h-4 text-gray-600" />
                    <span>سجل المواطنين</span>
                </a>

                <button type="button"
                        onclick="if(typeof window.openCitizenModal === 'function'){ window.openCitizenModal({onSuccess: function(c){ window.location.href='/customers/customers/' + c.id; }}); } else { window.location.href='{{ route('customers.citizens.create') }}'; }"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2.5 px-4 rounded-2xl shadow-sm hover:shadow-md transition-all text-xs cursor-pointer">
                    <x-heroicon-s-user-plus class="w-4 h-4" />
                    <span>إضافة مواطن جديد</span>
                </button>
            </div>
        </div>

        {{-- SUMMARY STATS / KPI CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- KPI 1: CITIZENS --}}
            <a href="{{ route('customers.citizens.index') }}" class="bg-white rounded-3xl p-5 border border-teal-100 shadow-2xs hover:shadow-md hover:border-teal-500 transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500">إجمالي سجل المواطنين</span>
                        <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-heroicon-o-user-group class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['total_citizens']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">مواطن مسجل في السجل الموحد</div>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                    <span>موثق بهوية: <strong class="text-gray-800">{{ number_format($stats['citizens_with_id']) }}</strong></span>
                    <span class="text-teal-700 font-bold group-hover:translate-x-1 transition-transform">استعراض ←</span>
                </div>
            </a>

            {{-- KPI 2: SUBSCRIPTIONS --}}
            <a href="{{ route('customers.index') }}" class="bg-white rounded-3xl p-5 border border-orange/20 shadow-2xs hover:shadow-md hover:border-orange transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500">اشتراكات المياه والخدمات</span>
                        <div class="w-10 h-10 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-heroicon-o-sparkles class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['total_subscriptions']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">اشتراك وعقد مياه مسجل</div>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                    <span>مبانٍ مستفيدة: <strong class="text-gray-800">{{ number_format($stats['buildings_with_subs']) }}</strong></span>
                    <span class="text-orange font-bold group-hover:translate-x-1 transition-transform">استعراض ←</span>
                </div>
            </a>

            {{-- KPI 3: BUILDING OWNERS --}}
            <a href="{{ route('buildings.buildings.index') }}" class="bg-white rounded-3xl p-5 border border-blue-100 shadow-2xs hover:shadow-md hover:border-blue-500 transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500">ملاك المباني والعقارات</span>
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-heroicon-o-building-office-2 class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['owners_count']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">مالك عقار مسجل بالسجلات</div>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                    <span>إجمالي المباني: <strong class="text-gray-800">{{ number_format($stats['total_buildings']) }}</strong></span>
                    <span class="text-blue-700 font-bold group-hover:translate-x-1 transition-transform">استعراض ←</span>
                </div>
            </a>

            {{-- KPI 4: CRAFTS & BUSINESSES --}}
            <a href="{{ route('crafts.economical.index') }}" class="bg-white rounded-3xl p-5 border border-amber-100 shadow-2xs hover:shadow-md hover:border-amber-500 transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500">أصحاب الحرف والأنشطة</span>
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <x-heroicon-o-briefcase class="w-5 h-5" />
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-900 tracking-tight">{{ number_format($stats['crafts_count']) }}</div>
                    <div class="text-xs text-gray-400 mt-1">مواطن يملك رخصة أو نشاط</div>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500 font-medium">
                    <span>الحرف والأنشطة التجارية</span>
                    <span class="text-amber-700 font-bold group-hover:translate-x-1 transition-transform">استعراض ←</span>
                </div>
            </a>

        </div>

        {{-- QUICK ACCESS PORTAL CARDS --}}
        <div>
            <h3 class="text-base font-black text-gray-900 mb-3 flex items-center gap-2">
                <x-heroicon-s-squares-2x2 class="w-4 h-4 text-orange" />
                <span>الخدمات والعمليات الرئيسية</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                {{-- Portal 1: Citizens Registry --}}
                <a href="{{ route('customers.citizens.index') }}" class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-2xs hover:shadow-lg hover:border-teal-400 hover:-translate-y-1 transition-all relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-2 h-full bg-teal-600"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-teal-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-identification class="w-6 h-6" />
                        </div>
                        <h4 class="text-lg font-black text-gray-900 mb-1 group-hover:text-teal-700 transition-colors">سجل المواطنين الموحد</h4>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">
                            البحث والتحقق من هويات المواطنين، فتح ملف المواطن الشامل (Citizen 360)، واستعراض كافة ممتلكاتهم ومعاملاتهم.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-teal-700">
                        <span>فتح سجل المواطنين</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                    </div>
                </a>

                {{-- Portal 2: Subscriptions --}}
                <a href="{{ route('customers.index') }}" class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-2xs hover:shadow-lg hover:border-orange/40 hover:-translate-y-1 transition-all relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute top-0 right-0 w-2 h-full bg-orange"></div>
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-orange/10 text-orange flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-orange group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-users class="w-6 h-6" />
                        </div>
                        <h4 class="text-lg font-black text-gray-900 mb-1 group-hover:text-orange-dark transition-colors">إدارة المشتركين والخدمات</h4>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">
                            استعراض عقود واشتراكات المياه، بيانات الجباية، الوحدات المرتبطة بكل اشتراك، وتفاصيل الحسابات للمشتركين.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-orange-dark">
                        <span>فتح سجل الاشتراكات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                    </div>
                </a>

                {{-- Portal 3: Create Citizen Action --}}
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50/60 rounded-3xl p-6 border border-emerald-200 shadow-2xs hover:shadow-lg hover:-translate-y-1 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-4 shadow-sm">
                            <x-heroicon-s-user-plus class="w-6 h-6" />
                        </div>
                        <h4 class="text-lg font-black text-gray-900 mb-1">إضافة مواطن جديد</h4>
                        <p class="text-xs text-gray-600 leading-relaxed mb-4">
                            تسجيل مواطن جديد في قاعدة البيانات الموحدة مباشرة، ليصبح متاحاً للربط الفوري في المعاملات، المباني، والحرف.
                        </p>
                    </div>
                    <div class="pt-3 border-t border-emerald-200/60 flex items-center justify-between">
                        <button type="button"
                                onclick="if(typeof window.openCitizenModal === 'function'){ window.openCitizenModal({onSuccess: function(c){ window.location.href='/customers/customers/' + c.id; }}); } else { window.location.href='{{ route('customers.citizens.create') }}'; }"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl shadow-xs transition-colors text-xs cursor-pointer w-full justify-center">
                            <x-heroicon-s-plus class="w-4 h-4" />
                            <span>فتح نموذج الإضافة الموحد</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>

        {{-- LATEST DATA PREVIEWS (TWO COLUMNS) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
            
            {{-- COLUMN 1: LATEST CITIZENS --}}
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-2xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h4 class="text-sm font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-s-user-group class="w-4 h-4 text-teal-600" />
                        <span>أحدث المواطنين المسجلين</span>
                    </h4>
                    <a href="{{ route('customers.citizens.index') }}" class="text-xs font-bold text-teal-700 hover:underline flex items-center gap-1">
                        <span>عرض الكل</span>
                        <x-heroicon-m-chevron-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($latestCitizens as $citizen)
                    <div class="py-3 flex items-center justify-between gap-3 hover:bg-gray-50/50 rounded-xl px-2 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ mb_substr($citizen->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('customers.citizens.show', $citizen->id) }}" target="_blank" class="font-bold text-gray-900 text-xs hover:text-teal-700 hover:underline block truncate">
                                    {{ $citizen->name }}
                                </a>
                                <div class="text-[11px] text-gray-400 font-mono mt-0.5 flex items-center gap-2">
                                    <span>هوية: {{ $citizen->id_no ?? '-' }}</span>
                                    @if($citizen->mobile)
                                    <span>• جوال: {{ $citizen->mobile }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('customers.citizens.show', $citizen->id) }}" target="_blank" class="shrink-0 text-[11px] font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 px-2.5 py-1 rounded-lg transition-colors inline-flex items-center gap-1">
                            <span>الملف</span>
                            <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
                        </a>
                    </div>
                    @empty
                    <div class="py-8 text-center text-xs text-gray-400">لا توجد سجلات مواطنين حتى الآن.</div>
                    @endforelse
                </div>
            </div>

            {{-- COLUMN 2: LATEST SUBSCRIPTIONS --}}
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-2xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h4 class="text-sm font-black text-gray-900 flex items-center gap-2">
                        <x-heroicon-s-sparkles class="w-4 h-4 text-orange" />
                        <span>أحدث اشتراكات الخدمات والمياه</span>
                    </h4>
                    <a href="{{ route('customers.index') }}" class="text-xs font-bold text-orange hover:underline flex items-center gap-1">
                        <span>عرض الكل</span>
                        <x-heroicon-m-chevron-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($latestSubscriptions as $sub)
                    <div class="py-3 flex items-center justify-between gap-3 hover:bg-gray-50/50 rounded-xl px-2 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange flex items-center justify-center font-bold text-xs shrink-0">
                                #{{ $sub->id }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('customers.subscriptions.show', $sub->id) }}" class="font-bold text-gray-900 text-xs hover:text-orange-dark hover:underline block truncate">
                                    {{ $sub->subscriber_name ?? "مشترك رقم {$sub->id}" }}
                                </a>
                                <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-2">
                                    <span>مبنى: {{ $sub->building?->building_number ?? '-' }}</span>
                                    <span>• المنطقة: {{ $sub->building?->zone?->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('customers.subscriptions.show', $sub->id) }}" class="shrink-0 text-[11px] font-bold text-orange bg-orange/10 hover:bg-orange/20 px-2.5 py-1 rounded-lg transition-colors inline-flex items-center gap-1">
                            <span>تفاصيل</span>
                            <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
                        </a>
                    </div>
                    @empty
                    <div class="py-8 text-center text-xs text-gray-400">لا توجد اشتراكات مسجلة حتى الآن.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-customers-layout>
