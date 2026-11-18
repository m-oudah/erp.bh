<x-buildings-layout>
    <div class="space-y-6 max-w-6xl mx-auto pb-12">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('buildings.units.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 p-2.5 rounded-2xl transition border border-gray-200 shadow-2xs"
                   title="الرجوع لقائمة الوحدات">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-xl font-black border border-blue-100">
                            {{ $unit->unit_type_label }}
                        </span>
                        <span class="text-xs text-gray-400 font-bold">•</span>
                        <span class="font-mono text-xs text-gray-500 font-bold">معرف #{{ $unit->id }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-1">{{ $unit->display_name }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">
                        تفاصيل الوحدة العقارية واستخداماتها وبيانات المبنى التابعة له وملاكها
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('buildings.units.edit', $unit->id) }}"
                   class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-black py-2 px-4 rounded-xl shadow-md shadow-orange/20 transition-all text-xs cursor-pointer">
                    <x-heroicon-s-pencil-square class="w-4 h-4" />
                    <span>تعديل الوحدة</span>
                </a>

                @if($unit->building)
                <a href="{{ route('buildings.buildings.show', $unit->building_id) }}"
                   class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-black py-2 px-4 rounded-xl border border-blue-200 transition-all text-xs">
                    <x-heroicon-s-building-office class="w-4 h-4" />
                    <span>ملف المبنى</span>
                </a>
                @endif

                <form action="{{ route('buildings.units.destroy', $unit->id) }}"
                      method="POST"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الوحدة؟');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl border border-rose-200 transition-colors shadow-2xs cursor-pointer"
                            title="حذف الوحدة">
                        <x-heroicon-s-trash class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </div>

        {{-- Success Banner --}}
        @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs font-bold text-emerald-800 flex items-center gap-2 shadow-2xs">
            <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Key Facts Banner --}}
        <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white rounded-3xl p-6 shadow-xl relative overflow-hidden">
            <div class="absolute -left-10 -bottom-10 opacity-10 pointer-events-none">
                <x-heroicon-s-squares-plus class="w-64 h-64 text-white" />
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 relative z-10">
                <div>
                    <div class="text-[11px] text-blue-200 font-bold">رقم الوحدة</div>
                    <div class="text-2xl font-black text-white mt-1">#{{ $unit->unit_number ?: $unit->id }}</div>
                    <div class="text-[10px] text-blue-300 mt-0.5">في الطابق: {{ $unit->floor ? $unit->floor->floor_name : ('طابق ' . ($unit->floor_number ?? '0')) }}</div>
                </div>

                <div>
                    <div class="text-[11px] text-blue-200 font-bold">المبنى التابعة له</div>
                    <div class="text-xl font-black text-amber-300 mt-1 truncate">
                        {{ $unit->building ? $unit->building->display_name : 'غير محدد' }}
                    </div>
                    <div class="text-[10px] text-blue-300 mt-0.5 font-mono">
                        {{ $unit->building?->file_number ? ('ملف: ' . $unit->building->file_number) : '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-blue-200 font-bold">طبيعة الاستخدام</div>
                    <div class="text-lg font-black text-emerald-300 mt-1 truncate">
                        {{ implode(' • ', $unit->usages_list) ?: 'غير محدد' }}
                    </div>
                    <div class="text-[10px] text-blue-300 mt-0.5">
                        {{ $unit->profession_name ?: 'بدون مسمى مهني' }}
                    </div>
                </div>

                <div>
                    <div class="text-[11px] text-blue-200 font-bold">الوضع الراهن</div>
                    <div class="text-lg font-black text-white mt-1">
                        @switch((int)$unit->current_situation)
                            @case(1) قائم ومستغل @break
                            @case(2) قائم وشاغر @break
                            @case(3) قيد الإنشاء @break
                            @case(4) مهجور @break
                            @default قائم
                        @endswitch
                    </div>
                    <div class="text-[10px] text-blue-300 mt-0.5">
                        تاريخ التسجيل: {{ $unit->creation_date ?: ($unit->created_at ? $unit->created_at->format('Y-m-d') : '—') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- 2-Column Grid: Building Info & Usages --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

            {{-- 1. Building Box (المبنى التابعة له) --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shadow-2xs">
                            <x-heroicon-s-building-office class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900">بيانات المبنى التابعة له الوحدة</h3>
                            <p class="text-[11px] text-gray-400">موقع العقار ومواصفات المبنى</p>
                        </div>
                    </div>

                    @if($unit->building)
                    <a href="{{ route('buildings.buildings.show', $unit->building_id) }}"
                       class="text-xs font-bold text-orange hover:text-orange-dark flex items-center gap-1">
                        <span>فتح ملف المبنى</span>
                        <x-heroicon-s-arrow-up-right class="w-3.5 h-3.5" />
                    </a>
                    @endif
                </div>

                @if($unit->building)
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">اسم / وصف المبنى</span>
                        <span class="font-black text-gray-900 mt-0.5 block">{{ $unit->building->display_name }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">رقم المبنى</span>
                        <span class="font-mono font-black text-gray-900 mt-0.5 block">#{{ $unit->building->building_number ?: 'غير مسجل' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">رقم الملف الأرشيفي</span>
                        <span class="font-mono font-black text-orange-dark mt-0.5 block" dir="ltr">{{ $unit->building->file_number ?: '—' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">الشارع والحي</span>
                        <span class="font-bold text-gray-900 mt-0.5 block">
                            {{ $unit->building->street?->street_name ?: 'شارع #' . ($unit->building->street_id ?? '—') }}
                            {{ $unit->building->zone ? ' • ' . $unit->building->zone->name : '' }}
                        </span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">رقم الحوض (Block)</span>
                        <span class="font-mono font-bold text-gray-800 mt-0.5 block">{{ $unit->building->block_number ?: '—' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">رقم القسيمة (Parcel)</span>
                        <span class="font-mono font-bold text-gray-800 mt-0.5 block">{{ $unit->building->parcel_number ?: '—' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('buildings.buildings.show', $unit->building_id) }}"
                       class="w-full py-2.5 bg-gray-50 hover:bg-orange/10 text-gray-800 hover:text-orange-dark font-bold text-xs rounded-xl border border-gray-200 transition-colors flex items-center justify-center gap-2">
                        <x-heroicon-s-building-office class="w-4 h-4 text-orange" />
                        <span>عرض كافة تفاصيل المبنى ({{ $unit->building->display_name }})</span>
                    </a>
                </div>
                @else
                <div class="p-6 text-center text-xs text-gray-400">لا يوجد مبنى مرتبط</div>
                @endif
            </div>

            {{-- 2. Usages & Profession Box (استخدامات الوحدة) --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-tag class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">استخدامات الوحدة والأنشطة</h3>
                        <p class="text-[11px] text-gray-400">الاستخدامات المعتمدة والأنشطة المهنية</p>
                    </div>
                </div>

                <div>
                    <span class="text-[11px] font-bold text-gray-500 block mb-2">الاستخدامات المعتمدة:</span>
                    <div class="flex items-center gap-2 flex-wrap">
                        @forelse($unit->usages_list as $usage)
                            @php
                                $badgeColor = match($usage) {
                                    'سكني' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'تجاري' => 'bg-blue-50 text-blue-800 border-blue-200',
                                    'صناعي' => 'bg-purple-50 text-purple-800 border-purple-200',
                                    'تعليمي' => 'bg-cyan-50 text-cyan-800 border-cyan-200',
                                    'صحي' => 'bg-rose-50 text-rose-800 border-rose-200',
                                    'ديني / مسجد' => 'bg-teal-50 text-teal-800 border-teal-200',
                                    default => 'bg-amber-50 text-amber-900 border-amber-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black border {{ $badgeColor }} shadow-2xs">
                                {{ $usage }}
                            </span>
                        @empty
                            <span class="text-xs text-gray-400">غير محدد</span>
                        @endforelse
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs pt-2">
                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">المسمى المهني / اسم المحل</span>
                        <span class="font-bold text-gray-900 mt-0.5 block">{{ $unit->profession_name ?: '—' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">نوع المهنة / النشاط</span>
                        <span class="font-bold text-gray-900 mt-0.5 block">{{ $unit->profession_type ?: '—' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">نشطة / قيد العمل</span>
                        <span class="font-bold text-gray-900 mt-0.5 block">{{ $unit->is_working ? 'نعم (تعمل)' : 'غير محدد' }}</span>
                    </div>

                    <div class="p-3 bg-gray-50/70 rounded-2xl">
                        <span class="text-gray-400 font-medium block text-[10px]">وجود يافطة إعلانية</span>
                        <span class="font-bold text-gray-900 mt-0.5 block">
                            {{ $unit->has_banner ? ($unit->is_lighting_banner ? 'يافطة مضاءة' : 'يافطة عادية') : 'لا توجد' }}
                        </span>
                    </div>
                </div>

                @if($unit->unit_usage_notes)
                <div class="p-3.5 bg-amber-50/60 border border-amber-100 rounded-2xl text-xs">
                    <span class="font-bold text-amber-900 block mb-1">ملاحظات الاستخدام:</span>
                    <p class="text-gray-700 leading-relaxed">{{ $unit->unit_usage_notes }}</p>
                </div>
                @endif
            </div>

        </div>

        {{-- 3. Owners Box (ملاك الوحدة والمبنى) --}}
        <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-users class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">سجل ملاك الوحدة والعقار</h3>
                        <p class="text-[11px] text-gray-400">الملاك المسجلون للوحدة أو المرتبطون عبر المبنى</p>
                    </div>
                </div>

                @if($unit->building)
                <span class="text-xs text-gray-500 font-bold bg-gray-100 px-3 py-1 rounded-xl">
                    {{ $unit->all_owners->count() }} مالك مسجل
                </span>
                @endif
            </div>

            @php
                $owners = $unit->all_owners;
            @endphp

            @if($owners->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($owners as $owner)
                <div class="p-4 rounded-2xl border border-gray-200 bg-gray-50/60 space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-orange/20 text-orange-dark flex items-center justify-center font-black text-sm shrink-0 shadow-2xs">
                            <x-heroicon-s-user class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                            <div class="font-black text-gray-900 text-xs truncate">{{ $owner->full_name }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">
                                @if(isset($owner->mokalaf) && $owner->mokalaf)
                                    <span class="text-amber-800 font-bold bg-amber-100 px-1.5 py-0.5 rounded">مكلف رئيسي</span>
                                @else
                                    <span class="text-gray-600">مالك عقار</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-200/70 space-y-1.5 text-xs">
                        @if(!empty($owner->id_card))
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-[11px]">رقم الهوية:</span>
                            <span class="font-mono font-bold text-gray-900 bg-white px-2 py-0.5 rounded border border-gray-200 text-[11px]" dir="ltr">
                                {{ $owner->id_card }}
                            </span>
                        </div>
                        @endif

                        @if(!empty($owner->phone_number) && $owner->phone_number !== '0')
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 text-[11px]">رقم الهاتف:</span>
                            <a href="tel:{{ $owner->phone_number }}" class="font-mono font-bold text-orange hover:underline text-[11px]" dir="ltr">
                                {{ $owner->phone_number }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center text-xs text-gray-400">لا يوجد ملاك مسجلون لهذه الوحدة حالياً</div>
            @endif
        </div>

        {{-- 4. Occupants / Users Info (إذا وجد شاغل مسجل) --}}
        @if($unit->unitUser)
        <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center shadow-2xs">
                    <x-heroicon-s-identification class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900">بيانات شاغل / مستخدم الوحدة (Occupant)</h3>
                    <p class="text-[11px] text-gray-400">المستفيد أو الساكن الحالي للوحدة</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-xs">
                <div class="p-3 bg-gray-50/70 rounded-2xl">
                    <span class="text-gray-400 font-medium block text-[10px]">الاسم الكامل</span>
                    <span class="font-black text-gray-900 mt-0.5 block">{{ $unit->unitUser->full_name }}</span>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-2xl">
                    <span class="text-gray-400 font-medium block text-[10px]">رقم الهوية</span>
                    <span class="font-mono font-bold text-gray-900 mt-0.5 block" dir="ltr">
                        {{ $unit->unitUser->id_card ?: ($unit->unitUser->id_no ?: '—') }}
                    </span>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-2xl">
                    <span class="text-gray-400 font-medium block text-[10px]">رقم الهاتف</span>
                    <span class="font-mono font-bold text-gray-900 mt-0.5 block" dir="ltr">
                        {{ $unit->unitUser->phone_number ?: ($unit->unitUser->mobile ?: '—') }}
                    </span>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-2xl">
                    <span class="text-gray-400 font-medium block text-[10px]">عدد الأفراد / المعالين</span>
                    <span class="font-black text-gray-900 mt-0.5 block">{{ $unit->unitUser->dependents_count ?: 0 }} فرد</span>
                </div>
            </div>
        </div>
        @endif

        {{-- 5. Notes & Audit Info --}}
        @if($unit->unit_notes || $unit->water_notes)
        <div class="bg-white rounded-3xl border border-gray-200/80 p-6 shadow-xs space-y-3">
            <h4 class="text-xs font-black text-gray-800">ملاحظات إضافية</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                @if($unit->unit_notes)
                <div class="p-3.5 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-gray-700 block mb-1">ملاحظات الوحدة العامة:</span>
                    <p class="text-gray-600">{{ $unit->unit_notes }}</p>
                </div>
                @endif
                @if($unit->water_notes)
                <div class="p-3.5 bg-gray-50 rounded-2xl">
                    <span class="font-bold text-gray-700 block mb-1">ملاحظات المياه / الخدمات:</span>
                    <p class="text-gray-600">{{ $unit->water_notes }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</x-buildings-layout>
