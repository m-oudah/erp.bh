<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    <div class="w-full">
        <form method="POST" action="{{ route('damage.records.update', $record->id) }}" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. Basic Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">اسم المنشأة / العقار أو الشارع <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $record->name) }}" required class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">مستوى التدمير / الضرر <span class="text-rose-500">*</span></label>
                    <select name="damage_level" required class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                        <option value="total_destruction" {{ $record->damage_level === 'total_destruction' ? 'selected' : '' }}>مهدم كلياً (تدمير كامل للكتلة الإنشائية)</option>
                        <option value="severe_partial" {{ $record->damage_level === 'severe_partial' ? 'selected' : '' }}>ضرر جزئي بليغ (غير صالح للسكن وبحاجة تدعيم)</option>
                        <option value="moderate_partial" {{ $record->damage_level === 'moderate_partial' ? 'selected' : '' }}>ضرر جزئي متوسط (قابل للتأهيل والترميم)</option>
                        <option value="minor" {{ $record->damage_level === 'minor' ? 'selected' : '' }}>ضرر جزئي طفيف</option>
                    </select>
                </div>
            </div>

            {{-- 2. Status & Priority --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">الحالة التشغيلية <span class="text-rose-500">*</span></label>
                    <select name="operational_status" required class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                        <option value="destroyed" {{ $record->operational_status === 'destroyed' ? 'selected' : '' }}>مدمر كلياً وغير قابل للاستخدام</option>
                        <option value="out_of_service" {{ $record->operational_status === 'out_of_service' ? 'selected' : '' }}>خارج الخدمة حالياً</option>
                        <option value="partially_operational" {{ $record->operational_status === 'partially_operational' ? 'selected' : '' }}>تشغيل جزئي ومحدود</option>
                        <option value="operational" {{ $record->operational_status === 'operational' ? 'selected' : '' }}>صالح وقيد التشغيل</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="urgent_intervention_needed" value="1" {{ $record->urgent_intervention_needed ? 'checked' : '' }} class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                        <span class="text-xs font-bold text-rose-700">يتطلب تدخلاً هندسياً عاجلاً (أولوية قصوى)</span>
                    </label>
                </div>
            </div>

            {{-- 3. Dimensions & Engineering Specs --}}
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-4">
                <div class="text-xs font-black text-slate-800">الأبعاد والكميات الهندسية المعتمدة</div>

                @if($record->sector === 'infrastructure')
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">طول الطريق (متر طولي)</label>
                            <input type="number" step="0.1" name="length_meters" value="{{ old('length_meters', $record->length_meters) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">عرض الطريق (متر)</label>
                            <input type="number" step="0.1" name="width_meters" value="{{ old('width_meters', $record->width_meters) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">نوع الطريق</label>
                            <select name="road_type" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                                <option value="asphalt" {{ $record->road_type === 'asphalt' ? 'selected' : '' }}>إسفلت قياسي</option>
                                <option value="interlock" {{ $record->road_type === 'interlock' ? 'selected' : '' }}>بلاط إنترلوك</option>
                                <option value="dirt" {{ $record->road_type === 'dirt' ? 'selected' : '' }}>شارع ترابي</option>
                            </select>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">مساحة المسقط (م²)</label>
                            <input type="number" step="0.1" name="area_sqm" value="{{ old('area_sqm', $record->area_sqm) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">عدد الطوابق</label>
                            <input type="number" min="1" name="floors_count" value="{{ old('floors_count', $record->floors_count) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">عدد الوحدات السكنية</label>
                            <input type="number" min="0" name="units_count" value="{{ old('units_count', $record->units_count) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 mb-1">الهيكل الإنشائي</label>
                            <select name="structure_type" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20 bg-white">
                                <option value="reinforced_concrete" {{ $record->structure_type === 'reinforced_concrete' ? 'selected' : '' }}>باطون مسلح</option>
                                <option value="asbestos" {{ $record->structure_type === 'asbestos' ? 'selected' : '' }}>سقف أسبست أو زينكو</option>
                                <option value="steel_hanger" {{ $record->structure_type === 'steel_hanger' ? 'selected' : '' }}>هنغار معدني</option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 4. Cadastral Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">المنطقة / الحي</label>
                    <select name="zone_id" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                        <option value="">اختر الحي...</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" {{ $record->zone_id == $z->id ? 'selected' : '' }}>
                                {{ $z->zone_name ?: ('حي رقم ' . $z->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">رقم الحوض (Block)</label>
                    <input type="text" name="block_number" value="{{ old('block_number', $record->block_number) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">رقم القطعة (Parcel)</label>
                    <input type="text" name="parcel_number" value="{{ old('parcel_number', $record->parcel_number) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">رقم المبنى</label>
                    <input type="text" name="building_number" value="{{ old('building_number', $record->building_number) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>
            </div>

            {{-- 5. Citizen & Ownership Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">اسم المالك / المستفيد</label>
                    <input type="text" name="citizen_name" value="{{ old('citizen_name', $record->citizen_name) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">رقم الهوية</label>
                    <input type="text" name="citizen_national_id" value="{{ old('citizen_national_id', $record->citizen_national_id) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">رقم الهاتف للتواصل</label>
                    <input type="text" name="citizen_phone" value="{{ old('citizen_phone', $record->citizen_phone) }}" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                </div>
            </div>

            {{-- 6. Pricing Criteria Override --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">معيار التسعير الهندسي المطبق</label>
                <select name="pricing_criteria_id" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">
                    <option value="">معيار تلقائي بحسب القطاع والنوع</option>
                    @foreach($pricingCriteria as $crit)
                        <option value="{{ $crit->id }}" {{ $record->pricing_criteria_id == $crit->id ? 'selected' : '' }}>
                            {{ $crit->name_ar }} (${{ number_format($crit->unit_price_usd, 2) }} / {{ $crit->unit_type }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 7. Notes --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">ملاحظات الحصر والتوصيف الهندسي</label>
                <textarea name="notes" rows="3" class="w-full text-xs font-medium rounded-xl border-slate-200 focus:border-orange-500 focus:ring-orange-500/20">{{ old('notes', $record->notes) }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('damage.records.show', $record->id) }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-orange-600 hover:bg-orange-700 text-white text-xs font-black shadow-md shadow-orange-600/20 transition-all flex items-center gap-1.5">
                    <x-heroicon-s-check class="w-4 h-4" />
                    <span>تحديث السجل وإعادة احتساب التقدير المالي</span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
