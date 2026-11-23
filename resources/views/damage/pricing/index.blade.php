<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between gap-2 shadow-xs">
            <div class="flex items-center gap-2">
                <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-xs font-bold cursor-pointer">✕</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <x-heroicon-s-x-circle class="w-5 h-5 text-rose-600 shrink-0" />
                <span>يرجى تصحيح الأخطاء التالية:</span>
            </div>
            <ul class="list-disc list-inside mr-6 space-y-0.5 font-normal text-[11px]">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div x-data="{ createModal: false, editModal: false, activeCriterion: {} }">

        {{-- Top Bar with Add Button & Info Alert --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-black text-slate-900">المعايير الهندسية القياسية لمدينة بيت حانون</h2>
                <p class="text-xs text-slate-500 font-medium">تُستخدم هذه المعايير لحساب التكاليف التقديرية لإعادة الإعمار والتعافي آلياً</p>
            </div>
            <button @click="createModal = true" class="px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-xs font-black shadow-md shadow-orange-600/20 transition-all flex items-center gap-1.5 self-start">
                <x-heroicon-s-plus class="w-4 h-4" />
                <span>إضافة معيار هندسي جديد</span>
            </button>
        </div>

        {{-- Dynamic Criteria Cards grouped by sector --}}
        <div class="space-y-6 mb-8">
            @foreach($sectors as $secKey => $secName)
                @php
                    $sectorCriteria = $criteria->where('sector', $secKey);
                @endphp
                @if($sectorCriteria->isNotEmpty())
                    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                <h3 class="text-base font-black text-slate-900">{{ $secName }}</h3>
                                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">
                                    {{ $sectorCriteria->count() }} بند
                                </span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs">
                                <thead>
                                    <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                        <th class="pb-3">رمز البند</th>
                                        <th class="pb-3">اسم المعيار الهندسي</th>
                                        <th class="pb-3">التصنيف</th>
                                        <th class="pb-3">وحدة القياس</th>
                                        <th class="pb-3">سعر الوحدة المعياري ($)</th>
                                        <th class="pb-3">سعر الوحدة (₪)</th>
                                        <th class="pb-3">معاملات الضرر</th>
                                        <th class="pb-3 text-center">الإجراءات والاحتساب الجماعي</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @foreach($sectorCriteria as $crit)
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-3 font-mono font-bold text-orange-600">
                                                {{ $crit->code }}
                                            </td>
                                            <td class="py-3 font-bold text-slate-900">
                                                {{ $crit->name_ar }}
                                                @if($crit->name_en)
                                                    <div class="text-[10px] text-slate-400 font-normal">{{ $crit->name_en }}</div>
                                                @endif
                                            </td>
                                            <td class="py-3 text-slate-600">
                                                {{ $crit->category }}
                                            </td>
                                            <td class="py-3 whitespace-nowrap">
                                                @php
                                                    $unitNames = [
                                                        'sqm' => 'متر مربع (م²)',
                                                        'linear_meter' => 'متر طولي (م.ط)',
                                                        'unit' => 'وحدة / مقطع',
                                                        'lump_sum' => 'مقطوعية',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                                    {{ $unitNames[$crit->unit_type] ?? $crit->unit_type }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-black text-slate-900 whitespace-nowrap">
                                                ${{ number_format($crit->unit_price_usd, 2) }}
                                            </td>
                                            <td class="py-3 font-bold text-slate-600 whitespace-nowrap">
                                                ₪{{ number_format($crit->unit_price_ils, 2) }}
                                            </td>
                                            <td class="py-3">
                                                <div class="flex items-center gap-1 text-[10px] font-bold">
                                                    <span class="px-1.5 py-0.5 bg-rose-50 text-rose-700 rounded" title="كلي">ك: 100%</span>
                                                    <span class="px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded" title="بليغ">ب: {{ ($crit->damage_multipliers['severe_partial'] ?? 0.65) * 100 }}%</span>
                                                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 rounded" title="متوسط">م: {{ ($crit->damage_multipliers['moderate_partial'] ?? 0.35) * 100 }}%</span>
                                                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-700 rounded" title="طفيف">ط: {{ ($crit->damage_multipliers['minor'] ?? 0.15) * 100 }}%</span>
                                                </div>
                                            </td>
                                            <td class="py-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center gap-2">
                                                    {{-- Edit Button --}}
                                                    <button @click="activeCriterion = {{ json_encode($crit) }}; editModal = true" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors flex items-center gap-1">
                                                        <x-heroicon-s-pencil class="w-3.5 h-3.5" />
                                                        <span>تعديل</span>
                                                    </button>

                                                    {{-- Bulk Recalculate Button --}}
                                                    <form method="POST" action="{{ route('damage.pricing.bulkRecalculate', $crit->id) }}" class="inline" onsubmit="return confirm('هل تريد إعادة احتساب كافة السجلات المرتبطة بهذا المعيار في المدينة بالكامل؟');">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-orange-50 hover:bg-orange-100 text-orange-700 border border-orange-200 font-bold text-xs transition-colors flex items-center gap-1" title="إعادة احتساب التكاليف لجميع سجلات هذا المعيار">
                                                            <x-heroicon-s-arrow-path class="w-3.5 h-3.5" />
                                                            <span>تحديث جماعي</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Add Criterion Modal --}}
        <div x-show="createModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.outside="createModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base">إضافة معيار تسعير هندسي جديد</h3>
                    <button @click="createModal = false" class="text-slate-400 hover:text-slate-600">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" action="{{ route('damage.pricing.store') }}" class="space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">رمز المعيار (كود فريد)</label>
                            <input type="text" name="code" required placeholder="مثال: CRIT-CUSTOM-01" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">القطاع</label>
                            <select name="sector" required class="w-full text-xs rounded-xl border-slate-200">
                                @foreach($sectors as $k => $name)
                                    <option value="{{ $k }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">اسم المعيار (عربي)</label>
                        <input type="text" name="name_ar" required placeholder="مثال: شبكة إنارة وطاقة شمسية" class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">التصنيف الداخلي</label>
                            <input type="text" name="category" required placeholder="مثال: electrical" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">وحدة القياس</label>
                            <select name="unit_type" required class="w-full text-xs rounded-xl border-slate-200">
                                <option value="sqm">متر مربع (م²)</option>
                                <option value="linear_meter">متر طولي (م.ط)</option>
                                <option value="unit">وحدة / مقطع</option>
                                <option value="lump_sum">مقطوعية</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">السعر المعياري ($)</label>
                            <input type="number" step="0.01" name="unit_price_usd" required placeholder="100.00" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">السعر المعياري بالشيكل (₪)</label>
                        <input type="number" step="0.01" name="unit_price_ils" required placeholder="370.00" class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="createModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold">إلغاء</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-orange-600 text-white font-bold hover:bg-orange-700">حفظ المعيار</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Criterion Modal --}}
        <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.outside="editModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-black text-slate-900 text-base" x-text="'تعديل المعيار: ' + (activeCriterion.name_ar || '')"></h3>
                    <button @click="editModal = false" class="text-slate-400 hover:text-slate-600">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <form :action="'{{ url('/damage/pricing') }}/' + activeCriterion.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="unit_of_measure" :value="activeCriterion.unit_of_measure || activeCriterion.unit_type || 'sqm'">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">اسم المعيار (عربي)</label>
                        <input type="text" name="name_ar" x-model="activeCriterion.name_ar" required class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">السعر بالدولار ($)</label>
                            <input type="number" step="0.01" name="unit_price_usd" x-model="activeCriterion.unit_price_usd" required class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">السعر بالشيكل (₪)</label>
                            <input type="number" step="0.01" name="unit_price_ils" x-model="activeCriterion.unit_price_ils" required class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-[11px]">
                        <button type="button" @click="if(activeCriterion.unit_price_usd) activeCriterion.unit_price_ils = (parseFloat(activeCriterion.unit_price_usd) * 3.70).toFixed(2)" class="text-orange-600 hover:underline font-bold cursor-pointer">
                            تحويل تلقائي للشيكل ($1 = ₪3.70)
                        </button>
                        <button type="button" @click="if(activeCriterion.unit_price_ils) activeCriterion.unit_price_usd = (parseFloat(activeCriterion.unit_price_ils) / 3.70).toFixed(2)" class="text-teal-700 hover:underline font-bold cursor-pointer">
                            تحويل تلقائي للدولار (₪3.70 = $1)
                        </button>
                    </div>

                    <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 text-[11px] font-medium">
                        تعديل السعر هنا سيؤثر على احتساب التكاليف المستقبلية أو عند النقر على زر "تحديث جماعي".
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="editModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold">إلغاء</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-orange-600 text-white font-bold hover:bg-orange-700">تحديث المعيار</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
