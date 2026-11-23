<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    <div class="w-full space-y-6" x-data="{
        sector: '{{ $defaultSector }}',
        blockNumber: '',
        parcelNumber: '',
        nationalId: '',
        damageLevel: 'total_destruction',
        areaSqm: 150,
        floorsCount: 1,
        unitsCount: 1,
        lengthMeters: 100,
        widthMeters: 8,
        selectedCriteriaId: '',
        criteriaList: @json($pricingCriteria),
        duplicates: [],

        checkDuplicates() {
            if ((this.blockNumber && this.parcelNumber) || (this.nationalId && this.nationalId.length >= 9)) {
                fetch(`{{ route('damage.ajax.duplicate') }}?block_number=${this.blockNumber}&parcel_number=${this.parcelNumber}&citizen_national_id=${this.nationalId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.duplicates = data.duplicates || [];
                    }).catch(() => {});
            }
        },

        get activeCriteria() {
            if (this.selectedCriteriaId) {
                return this.criteriaList.find(c => c.id == this.selectedCriteriaId) || null;
            }
            return this.criteriaList.find(c => c.sector === this.sector) || null;
        },

        get estimatedQuantity() {
            if (this.sector === 'infrastructure') {
                return (parseFloat(this.lengthMeters) || 0) * (parseFloat(this.widthMeters) || 0);
            }
            return (parseFloat(this.areaSqm) || 0) * (parseInt(this.floorsCount) || 1);
        },

        get damageMultiplier() {
            const mults = {
                'total_destruction': 1.0,
                'severe_partial': 0.65,
                'moderate_partial': 0.35,
                'minor': 0.15
            };
            return mults[this.damageLevel] || 1.0;
        },

        get estimatedCostUsd() {
            const crit = this.activeCriteria;
            const rate = crit ? parseFloat(crit.unit_price_usd) : (this.sector === 'infrastructure' ? 45 : 350);
            return Math.round(this.estimatedQuantity * rate * this.damageMultiplier);
        },

        get estimatedCostIls() {
            const crit = this.activeCriteria;
            const rate = crit ? parseFloat(crit.unit_price_ils) : (this.sector === 'infrastructure' ? 166.5 : 1295);
            return Math.round(this.estimatedQuantity * rate * this.damageMultiplier);
        }
    }">

        {{-- Top Breadcrumb & Action Bar --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('damage.records.index') }}" 
                   class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-all shrink-0" 
                   title="العودة لسجلات الحصر">
                    <x-heroicon-s-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2.5 py-0.5 rounded-lg border border-orange-200">
                            استمارة حصر ميداني
                        </span>
                        <span class="text-xs font-medium text-slate-400">•</span>
                        <span class="text-xs text-slate-500 font-medium">
                            توليد كود تلقائي <span class="font-mono font-bold text-slate-700">DAM-{{ date('Y') }}-XXXXXX</span>
                        </span>
                    </div>
                    <h2 class="text-lg font-black text-slate-800">إدخال وتقييم سجل حصر جديد بكافة الحقول الهندسية</h2>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('damage.records.index') }}" 
                   class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    إلغاء والعودة
                </a>
                <button type="submit" form="damage-create-form"
                        class="px-5 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-black shadow-md shadow-orange-600/20 transition-all flex items-center gap-1.5">
                    <x-heroicon-s-check class="w-4 h-4" />
                    <span>حفظ السجل وتوليد الرمز</span>
                </button>
            </div>
        </div>

        {{-- Sector Selection Full-Width Visual Tabs --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-3.5">
                <div>
                    <h3 class="text-xs font-black text-slate-800">حدد القطاع المستهدف للحصر الهندسي <span class="text-rose-500">*</span></h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">تتغير حقول القياس والأبعاد والمعايير السعرية تلقائياً بناءً على القطاع المختار</p>
                </div>
                <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-xl border border-orange-200">
                    5 قطاعات معتمدة
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                {{-- Real Estate --}}
                <label class="cursor-pointer">
                    <input type="radio" name="sector_picker" value="real_estate" x-model="sector" class="peer sr-only">
                    <div class="h-full p-4 rounded-2xl border transition-all text-right flex flex-col justify-between
                                peer-checked:bg-gradient-to-br peer-checked:from-orange-500 peer-checked:to-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-md peer-checked:shadow-orange-500/20
                                border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                 :class="sector === 'real_estate' ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-600'">
                                <x-heroicon-s-building-office-2 class="w-5 h-5" />
                            </div>
                            <span class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center"
                                  :class="sector === 'real_estate' ? 'border-white bg-white' : 'border-slate-300'">
                                <span class="w-2 h-2 rounded-full" :class="sector === 'real_estate' ? 'bg-orange-600' : 'hidden'"></span>
                            </span>
                        </div>
                        <div>
                            <div class="font-black text-xs">المباني والعقارات</div>
                            <div class="text-[10px] opacity-80 mt-1 line-clamp-1">سكني، تجاري، أبراج، فلل</div>
                        </div>
                    </div>
                </label>

                {{-- Infrastructure --}}
                <label class="cursor-pointer">
                    <input type="radio" name="sector_picker" value="infrastructure" x-model="sector" class="peer sr-only">
                    <div class="h-full p-4 rounded-2xl border transition-all text-right flex flex-col justify-between
                                peer-checked:bg-gradient-to-br peer-checked:from-orange-500 peer-checked:to-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-md peer-checked:shadow-orange-500/20
                                border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                 :class="sector === 'infrastructure' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600'">
                                <x-heroicon-s-wrench-screwdriver class="w-5 h-5" />
                            </div>
                            <span class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center"
                                  :class="sector === 'infrastructure' ? 'border-white bg-white' : 'border-slate-300'">
                                <span class="w-2 h-2 rounded-full" :class="sector === 'infrastructure' ? 'bg-orange-600' : 'hidden'"></span>
                            </span>
                        </div>
                        <div>
                            <div class="font-black text-xs">البنية التحتية والشبكات</div>
                            <div class="text-[10px] opacity-80 mt-1 line-clamp-1">شوارع، مياه، صرف، كهرباء</div>
                        </div>
                    </div>
                </label>

                {{-- Public Facilities --}}
                <label class="cursor-pointer">
                    <input type="radio" name="sector_picker" value="public_facilities" x-model="sector" class="peer sr-only">
                    <div class="h-full p-4 rounded-2xl border transition-all text-right flex flex-col justify-between
                                peer-checked:bg-gradient-to-br peer-checked:from-orange-500 peer-checked:to-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-md peer-checked:shadow-orange-500/20
                                border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                 :class="sector === 'public_facilities' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-600'">
                                <x-heroicon-s-academic-cap class="w-5 h-5" />
                            </div>
                            <span class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center"
                                  :class="sector === 'public_facilities' ? 'border-white bg-white' : 'border-slate-300'">
                                <span class="w-2 h-2 rounded-full" :class="sector === 'public_facilities' ? 'bg-orange-600' : 'hidden'"></span>
                            </span>
                        </div>
                        <div>
                            <div class="font-black text-xs">المرافق العامة والحيوية</div>
                            <div class="text-[10px] opacity-80 mt-1 line-clamp-1">مساجد، مدارس، مستشفيات</div>
                        </div>
                    </div>
                </label>

                {{-- Economic Crafts --}}
                <label class="cursor-pointer">
                    <input type="radio" name="sector_picker" value="economic_crafts" x-model="sector" class="peer sr-only">
                    <div class="h-full p-4 rounded-2xl border transition-all text-right flex flex-col justify-between
                                peer-checked:bg-gradient-to-br peer-checked:from-orange-500 peer-checked:to-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-md peer-checked:shadow-orange-500/20
                                border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                 :class="sector === 'economic_crafts' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-600'">
                                <x-heroicon-s-building-storefront class="w-5 h-5" />
                            </div>
                            <span class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center"
                                  :class="sector === 'economic_crafts' ? 'border-white bg-white' : 'border-slate-300'">
                                <span class="w-2 h-2 rounded-full" :class="sector === 'economic_crafts' ? 'bg-orange-600' : 'hidden'"></span>
                            </span>
                        </div>
                        <div>
                            <div class="font-black text-xs">القطاع الاقتصادي والحرفي</div>
                            <div class="text-[10px] opacity-80 mt-1 line-clamp-1">محلات، ورش، مخازن، مصانع</div>
                        </div>
                    </div>
                </label>

                {{-- Municipal Assets --}}
                <label class="cursor-pointer">
                    <input type="radio" name="sector_picker" value="municipal_assets" x-model="sector" class="peer sr-only">
                    <div class="h-full p-4 rounded-2xl border transition-all text-right flex flex-col justify-between
                                peer-checked:bg-gradient-to-br peer-checked:from-orange-500 peer-checked:to-orange-600 peer-checked:text-white peer-checked:border-orange-600 peer-checked:shadow-md peer-checked:shadow-orange-500/20
                                border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors"
                                 :class="sector === 'municipal_assets' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-600'">
                                <x-heroicon-s-shield-check class="w-5 h-5" />
                            </div>
                            <span class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center"
                                  :class="sector === 'municipal_assets' ? 'border-white bg-white' : 'border-slate-300'">
                                <span class="w-2 h-2 rounded-full" :class="sector === 'municipal_assets' ? 'bg-orange-600' : 'hidden'"></span>
                            </span>
                        </div>
                        <div>
                            <div class="font-black text-xs">أصول وممتلكات البلدية</div>
                            <div class="text-[10px] opacity-80 mt-1 line-clamp-1">آبار، مضخات، مقرات، آليات</div>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Smart Duplicate Alert Banner --}}
        <template x-if="duplicates.length > 0">
            <div class="p-5 bg-amber-50 border-2 border-amber-300 rounded-3xl text-amber-900 shadow-sm flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
                </div>
                <div class="flex-1 text-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-black text-sm text-amber-900">تنبيه ذكي: احتمالية تكرار الحصر أو تشابه البيانات!</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-200/80 text-amber-900" x-text="duplicates.length + ' سجل مطابق'"></span>
                    </div>
                    <div class="space-y-1 text-amber-800 font-medium">
                        <template x-for="d in duplicates" :key="d.record_id || d.type">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <span x-text="d.message"></span>
                                <template x-if="d.record_id">
                                    <a :href="'{{ url('/damage/records') }}/' + d.record_id" target="_blank" class="text-orange-700 font-bold underline hover:text-orange-900 text-[11px] mr-1">
                                        عرض السجل المسجل
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        {{-- Main Form Container: Full Width Grid --}}
        <form method="POST" action="{{ route('damage.records.store') }}" id="damage-create-form">
            @csrf
            {{-- Hidden sector bound to Alpine --}}
            <input type="hidden" name="sector" :value="sector">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Left/Main Form Fields (8 Columns on Large Screens) --}}
                <div class="lg:col-span-8 space-y-6">

                    {{-- Card 1: Basic Identification & Damage Severity --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-information-circle class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">1. البيانات الأساسية ومستوى الضرر الإنشائي</h3>
                                <p class="text-[11px] text-slate-400">تحديد اسم الكيان المتضرر ودرجة التدمير والحالة التشغيلية</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Entity Name --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    اسم المنشأة / العقار أو الشارع المتضرر <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" required 
                                           placeholder="مثال: مبنى عائلة فلان / مدرسة هارون الرشيد / شارع خليل الرحمن" 
                                           class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                                </div>
                            </div>

                            {{-- Damage Level --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    مستوى التدمير / الضرر الإنشائي <span class="text-rose-500">*</span>
                                </label>
                                <select name="damage_level" required x-model="damageLevel"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="total_destruction">مهدم كلياً (تدمير كامل للكتلة الإنشائية)</option>
                                    <option value="severe_partial">ضرر جزئي بليغ (غير صالح للاستخدام وبحاجة تدعيم)</option>
                                    <option value="moderate_partial">ضرر جزئي متوسط (قابل للتأهيل والترميم)</option>
                                    <option value="minor">ضرر جزئي طفيف (أضرار سطحية وتشطيبات)</option>
                                </select>
                            </div>

                            {{-- Operational Status --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                    الحالة التشغيلية الحالية
                                </label>
                                <select name="operational_status"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="destroyed">مدمر كلياً وخارج الخدمة نهائياً</option>
                                    <option value="out_of_service">خارج الخدمة مؤقتاً</option>
                                    <option value="partially_operational">تشغيل جزئي ومحدود</option>
                                    <option value="operational">صالح وقيد التشغيل الجزئي</option>
                                </select>
                            </div>

                            {{-- Structure Type (Hidden for Infrastructure) --}}
                            <div x-show="sector !== 'infrastructure'">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع الهيكل الإنشائي</label>
                                <select name="structure_type"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="reinforced_concrete">باطون مسلح (أعمدة وجسور وسقف خرساني)</option>
                                    <option value="asbestos">سقف أسبست أو زنكو على جدران طوب</option>
                                    <option value="steel_hanger">هنغار معدني / جملونات حديدية</option>
                                    <option value="stone">بناء حجر / عقود قديمة</option>
                                    <option value="other">طبيعة إنشائية أخرى</option>
                                </select>
                            </div>

                            {{-- Facility Type (For Public Facilities) --}}
                            <div x-show="sector === 'public_facilities'">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع المرفق العام</label>
                                <select name="facility_type"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="مسجد">مسجد / مصلّى</option>
                                    <option value="مدرسة">مدرسة / مؤسسة تعليمية</option>
                                    <option value="مستشفى">مستشفى / مركز صحي</option>
                                    <option value="مركز مجتمعي">مركز مجتمعي / ثقافي / شبابي</option>
                                    <option value="مقر حكومي">مقر حكومي / خدماتي</option>
                                    <option value="مرفق آخر">مرفق عام آخر</option>
                                </select>
                            </div>

                            {{-- Operating Entity (For Public Facilities / Municipal Assets) --}}
                            <div x-show="sector === 'public_facilities' || sector === 'municipal_assets'">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">الجهة المشغّلة / المالكة</label>
                                <input type="text" name="operating_entity" 
                                       placeholder="مثال: وزارة الأوقاف / بلدية بيت حانون / وكالة الغوث UNRWA" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Cadastral & GIS Location --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-map-pin class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">2. الموقع الجغرافي والتنظيم الهيكلي</h3>
                                <p class="text-[11px] text-slate-400">تحديد الحي والشارع والحوض والقطعة لإسقاط المعلم جغرافياً</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            {{-- Zone --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">المنطقة / الحي</label>
                                <select name="zone_id"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="">اختر المنطقة أو الحي...</option>
                                    @foreach($zones as $z)
                                        <option value="{{ $z->id }}">{{ $z->zone_name ?: ('حي رقم ' . $z->id) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Street --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">الشارع</label>
                                <select name="street_id"
                                        class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                    <option value="">اختر الشارع إن وجد...</option>
                                    @foreach($streets as $st)
                                        <option value="{{ $st->id }}">{{ $st->street_name ?: $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Block Number --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الحوض (Block)</label>
                                <input type="text" name="block_number" x-model="blockNumber" @blur="checkDuplicates()"
                                       placeholder="مثال: 12" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                            </div>

                            {{-- Parcel Number --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم القطعة (Parcel)</label>
                                <input type="text" name="parcel_number" x-model="parcelNumber" @blur="checkDuplicates()"
                                       placeholder="مثال: 45" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                            </div>

                            {{-- Building Number --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم المبنى</label>
                                <input type="text" name="building_number" 
                                       placeholder="مثال: 108" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                            </div>

                            {{-- Unit Number --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الشقة / المحل</label>
                                <input type="text" name="unit_number" 
                                       placeholder="مثال: 3" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                            </div>

                            {{-- GIS Coordinates --}}
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">خط العرض (Latitude - GIS)</label>
                                <input type="number" step="0.0000001" name="latitude" 
                                       placeholder="31.54XXXX" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white font-mono">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">خط الطول (Longitude - GIS)</label>
                                <input type="number" step="0.0000001" name="longitude" 
                                       placeholder="34.53XXXX" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white font-mono">
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Engineering Dimensions & Measured Quantities --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-variable class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">3. المقاييس والكميات الهندسية الميدانية</h3>
                                <p class="text-[11px] text-slate-400">تحديد المساحات والأبعاد لحساب التكلفة التقديرية بدقة</p>
                            </div>
                        </div>

                        {{-- Section A: Buildings / Facilities / Crafts / Assets --}}
                        <div x-show="sector !== 'infrastructure'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        مساحة مسقط الدور الأرضي (م²)
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="0" name="area_sqm" x-model="areaSqm" 
                                               placeholder="150" 
                                               class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                        <span class="absolute left-3 top-2.5 text-[11px] font-bold text-slate-400">م²</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        عدد الطوابق
                                    </label>
                                    <input type="number" min="1" name="floors_count" x-model="floorsCount" 
                                           placeholder="1" 
                                           class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        عدد الوحدات السكنية / المحلات
                                    </label>
                                    <input type="number" min="0" name="units_count" x-model="unitsCount" 
                                           placeholder="1" 
                                           class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                </div>
                            </div>

                            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600">إجمالي المساحة الطابقية التقديرية (مسقط × طوابق):</span>
                                <span class="text-sm font-black text-slate-900 bg-white px-3 py-1 rounded-xl border border-slate-200 shadow-2xs font-mono"
                                      x-text="((parseFloat(areaSqm) || 0) * (parseInt(floorsCount) || 1)).toLocaleString() + ' م²'">
                                </span>
                            </div>
                        </div>

                        {{-- Section B: Infrastructure & Networks --}}
                        <div x-show="sector === 'infrastructure'" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        طول الطريق المتضرر (متر طولي)
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="0" name="length_meters" x-model="lengthMeters" 
                                               placeholder="مثال: 350" 
                                               class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                        <span class="absolute left-3 top-2.5 text-[11px] font-bold text-slate-400">م.ط</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                        عرض الطريق (متر)
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="0" name="width_meters" x-model="widthMeters" 
                                               placeholder="مثال: 8" 
                                               class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                        <span class="absolute left-3 top-2.5 text-[11px] font-bold text-slate-400">م</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع التعبيد والسطح</label>
                                    <select name="road_type" 
                                            class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-white">
                                        <option value="asphalt">إسفلت قياسي (طبقة تأسيس + إسفلت)</option>
                                        <option value="interlock">بلاط خرساني (إنترلوك)</option>
                                        <option value="dirt">شارع ترابي / تسوية أولية</option>
                                    </select>
                                </div>
                            </div>

                            <div class="p-3.5 bg-blue-50 rounded-2xl border border-blue-200/80 flex items-center justify-between">
                                <span class="text-xs font-bold text-blue-900">إجمالي مسطح الطريق الإسفلتي المتضرر (طول × عرض):</span>
                                <span class="text-sm font-black text-blue-950 bg-white px-3 py-1 rounded-xl border border-blue-200 shadow-2xs font-mono"
                                      x-text="((parseFloat(lengthMeters) || 0) * (parseFloat(widthMeters) || 0)).toLocaleString() + ' م²'">
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: Citizen & Beneficiary Ownership Data --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-user class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">4. بيانات المالك أو المواطن المستفيد</h3>
                                <p class="text-[11px] text-slate-400">توثيق بيانات صاحب العلاقة للتواصل وإصدار شهادة الحصر المعتمدة</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المالك / المستفيد الرباعي</label>
                                <input type="text" name="citizen_name" 
                                       placeholder="الاسم الرباعي كاملاً" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهوية الوطنية (9 أرقام)</label>
                                <input type="text" name="citizen_national_id" x-model="nationalId" @blur="checkDuplicates()"
                                       placeholder="رقم الهوية المكون من 9 أرقام" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50 font-mono">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم هاتف التواصل</label>
                                <input type="text" name="citizen_phone" 
                                       placeholder="059xxxxxxx أو 056xxxxxxx" 
                                       class="w-full text-xs font-semibold rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 py-2.5 px-3.5 bg-slate-50/50 font-mono">
                            </div>
                        </div>
                    </div>

                    {{-- Card 5: Engineering Notes & Priority Assessment --}}
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-5">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-document-text class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800">5. الملاحظات الهندسية والتوصيات الميدانية</h3>
                                <p class="text-[11px] text-slate-400">تدوين أي متطلبات تدعيم عاجلة، تقييم السلامة، أو ملاحظات خاصة</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات الحصر والتوصيف الميداني</label>
                            <textarea name="notes" rows="3" 
                                      placeholder="أية ملاحظات إضافية حول التصدعات، خطورة الانهيار، حالة الجوار، أو التوصيات الهندسية الميدانية..." 
                                      class="w-full text-xs font-medium rounded-2xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 p-3.5 bg-slate-50/50"></textarea>
                        </div>

                        <div class="p-4 bg-rose-50/60 border border-rose-200/70 rounded-2xl flex items-center gap-3">
                            <input type="checkbox" name="urgent_intervention_needed" value="1" id="urgent_intervention"
                                   class="w-4 h-4 rounded-md border-slate-300 text-rose-600 focus:ring-rose-500/20">
                            <label for="urgent_intervention" class="text-xs font-bold text-rose-900 cursor-pointer">
                                تصنيف كـ (تدخل هندسي عاجل): العقار يشكل خطراً وشيكاً على السلامة العامة أو يتطلب تدعيماً فورياً.
                            </label>
                        </div>
                    </div>

                </div>

                {{-- Right/Sidebar Column: Live Estimator & Sticky Actions (4 Columns on Large Screens) --}}
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-6">

                    {{-- Live Calculation & Pricing Card --}}
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-6 shadow-xl border border-slate-700/80 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-700/80">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-orange-500 text-white flex items-center justify-center shrink-0">
                                    <x-heroicon-s-calculator class="w-4 h-4" />
                                </div>
                                <div>
                                    <h4 class="text-xs font-black">الاحتساب والتقدير الفوري</h4>
                                    <p class="text-[10px] text-slate-400">معايير الدائرة الهندسية</p>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-orange-500/20 text-orange-400 border border-orange-500/30 font-mono">
                                LIVE
                            </span>
                        </div>

                        {{-- Pricing Criteria Select --}}
                        <div>
                            <label class="block text-[11px] font-bold text-slate-300 mb-1.5">معيار التسعير الهندسي المرتبط</label>
                            <select name="pricing_criteria_id" x-model="selectedCriteriaId"
                                    class="w-full text-xs font-semibold rounded-xl border-slate-700 bg-slate-800/90 text-white focus:border-orange-500 focus:ring-orange-500/20 py-2 px-3">
                                <option value="">معيار التسعير التلقائي للقطاع</option>
                                <template x-for="c in criteriaList.filter(item => item.sector === sector)" :key="c.id">
                                    <option :value="c.id" x-text="c.name_ar + ' (' + c.unit_price_usd + ' $)'"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Live Metric Stats --}}
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <div class="bg-slate-800/80 p-3 rounded-2xl border border-slate-700/50">
                                <div class="text-[10px] font-bold text-slate-400 mb-1">الكمية المقاسة</div>
                                <div class="text-sm font-black text-white font-mono" x-text="estimatedQuantity.toLocaleString() + (sector === 'infrastructure' ? ' م²' : ' م²')">
                                    0 م²
                                </div>
                            </div>

                            <div class="bg-slate-800/80 p-3 rounded-2xl border border-slate-700/50">
                                <div class="text-[10px] font-bold text-slate-400 mb-1">معامل الضرر</div>
                                <div class="text-sm font-black text-orange-400 font-mono" x-text="(damageMultiplier * 100) + '%'">
                                    100%
                                </div>
                            </div>
                        </div>

                        {{-- Calculated Total Cost Display --}}
                        <div class="p-4 rounded-2xl bg-gradient-to-r from-orange-600/30 to-amber-600/30 border border-orange-500/40 space-y-2">
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs font-bold text-slate-300">التقدير المالي بالدولار:</span>
                                <div class="text-xl font-black text-white font-mono">
                                    <span x-text="'$' + estimatedCostUsd.toLocaleString()">$0</span>
                                </div>
                            </div>

                            <div class="flex items-baseline justify-between pt-2 border-t border-white/10">
                                <span class="text-xs font-bold text-slate-400">التقدير المالي بالشيكل:</span>
                                <div class="text-sm font-bold text-orange-300 font-mono">
                                    <span x-text="'₪' + estimatedCostIls.toLocaleString()">₪0</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-[10px] text-slate-400 leading-relaxed">
                            * يتم تطبيق المعادلة الهندسية الرسمية بناءً على جداول الكميات المعتمدة للبلدية ويمكن إعادة تدقيقها لاحقاً.
                        </div>
                    </div>

                    {{-- Form Actions Card --}}
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                        <button type="submit" 
                                class="w-full py-3.5 px-4 rounded-2xl bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-white font-black text-xs shadow-lg shadow-orange-600/30 transition-all flex items-center justify-center gap-2">
                            <x-heroicon-s-check class="w-5 h-5" />
                            <span>حفظ سجل الحصر وتوليد الرمز الموثق</span>
                        </button>

                        <a href="{{ route('damage.records.index') }}" 
                           class="w-full py-3 px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all text-center block">
                            إلغاء والعودة لقائمة السجلات
                        </a>
                    </div>

                    {{-- Field Surveyor Quick Guidelines --}}
                    <div class="bg-slate-50 rounded-3xl p-5 border border-slate-200/80 space-y-3">
                        <div class="text-xs font-black text-slate-800 flex items-center gap-2">
                            <x-heroicon-s-shield-check class="w-4 h-4 text-teal-600" />
                            <span>توجيهات تدقيق واستكمال الحصر</span>
                        </div>
                        <ul class="text-[11px] text-slate-500 space-y-2 list-disc list-inside leading-relaxed">
                            <li>تأكد من إدخال رقم الحوض والقطعة بدقة لتفادي تكرار توثيق العقار.</li>
                            <li>في حال كان العقار مهدم كلياً، يعتبر المسقط الأرضي أساس احتساب المساحة التراكمية.</li>
                            <li>سجل الحصر يحصل فور حفظه على رمز استجابة سريعة QR مشفر للتحقق الإلكتروني.</li>
                        </ul>
                    </div>

                </div>

            </div>
        </form>

    </div>
</x-app-layout>
