<x-hr-layout>
    <div class="w-full space-y-6">

        {{-- Top Header --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-calculator class="w-5 h-5 text-secondary" />
                    <span>احتساب رواتب جديد</span>
                </h2>
                <p class="text-xs text-tertiary mt-0.5">احتساب الرواتب الأساسية، البدلات والعلاوات، والاستقطاعات تلقائياً لجميع موظفي البلدية النشطين</p>
            </div>

            <a href="{{ route('hr.payroll.index') }}"
               class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5 shadow-2xs">
                <x-heroicon-s-arrow-right class="w-4 h-4" />
                <span>الرجوع لسجل الرواتب</span>
            </a>
        </div>

        {{-- Two-Column Layout: Right (Form), Left (Guidance & Calculation Methodology) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            {{-- 1. Right Side: Payroll Calculation Form (نصف الصفحة الأيمن) --}}
            <div class="space-y-5 order-1">

                {{-- Active Employees Stats Notice --}}
                <div class="bg-slate-50 border-2 border-slate-300 rounded-2xl p-5 shadow-xs flex items-center justify-between text-black">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center font-black text-xl shrink-0 border border-teal-200">
                            <x-heroicon-s-users class="w-6 h-6 text-teal-700" />
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-black">الكادر الوظيفي الجاهز للاحتساب</h3>
                            <p class="text-xs text-black font-bold mt-0.5">سيتم شمل كافة الموظفين النشطين (على رأس عملهم) في هذا الاحتساب آلياً</p>
                        </div>
                    </div>
                    <div class="text-left bg-white px-4 py-2 rounded-xl border border-slate-300 shrink-0 shadow-2xs">
                        <div class="text-2xl font-black text-black font-mono">{{ $activeEmployeesCount }}</div>
                        <div class="text-[10px] text-black font-bold">موظف نشط</div>
                    </div>
                </div>

                {{-- Generator Form --}}
                <form method="POST" action="{{ route('hr.payroll.store') }}" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                    @csrf

                    <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <x-heroicon-s-calendar-days class="w-4 h-4 text-secondary" />
                            <span>بيانات دورة صرف الراتب</span>
                        </h3>
                        <span class="text-xs text-tertiary font-bold">الحقول بعلامة (*) إلزامية</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Year --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">السنة المالية <span class="text-rose-500">*</span></label>
                            <select name="year" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                @for($y = $currentYear + 1; $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ old('year', $currentYear) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Month --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الشهر المالي <span class="text-rose-500">*</span></label>
                            <select name="month" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                @php
                                    $months = [
                                        1 => 'يناير (شهر 1)',
                                        2 => 'فبراير (شهر 2)',
                                        3 => 'مارس (شهر 3)',
                                        4 => 'أبريل (شهر 4)',
                                        5 => 'مايو (شهر 5)',
                                        6 => 'يونيو (شهر 6)',
                                        7 => 'يوليو (شهر 7)',
                                        8 => 'أغسطس (شهر 8)',
                                        9 => 'سبتمبر (شهر 9)',
                                        10 => 'أكتوبر (شهر 10)',
                                        11 => 'نوفمبر (شهر 11)',
                                        12 => 'ديسمبر (شهر 12)',
                                    ];
                                @endphp
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ old('month', $currentMonth) == $num ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Custom Title --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان كشف الرواتب (اختياري)</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="يُترك فارغاً للتسمية التلقائية (مسير رواتب شهر ...)"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات وقرارات إدارية خاصة بهذا الشهر</label>
                        <textarea name="notes" rows="3" placeholder="أي قرارات مجلس بلدي أو توجيهات مالية وإدارية خاصة بصرف هذا الشهر..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('notes') }}</textarea>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('hr.payroll.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-secondary hover:bg-secondary-dark text-white text-xs font-bold transition-all shadow-sm shadow-secondary/20 flex items-center gap-2 cursor-pointer">
                            <x-heroicon-s-bolt class="w-4 h-4 text-amber-300" />
                            <span>بدء احتساب الرواتب الآن</span>
                        </button>
                    </div>
                </form>

            </div>

            {{-- 2. Left Side: Guidance & Calculation Methodology Box (نصف العمود الأيسر) --}}
            <div class="space-y-4 order-2">

                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

                    {{-- Box Header --}}
                    <div class="p-5 bg-slate-100 border-b-2 border-slate-300 text-black flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-300 text-slate-900 flex items-center justify-center font-black border border-amber-400">
                                <x-heroicon-s-scale class="w-5 h-5 text-slate-900" />
                            </div>
                            <div>
                                <h3 class="text-base font-black text-black">إرشادات آلية التوليد والاحتساب المعتمدة</h3>
                                <p class="text-xs text-black font-bold mt-0.5">وفق قانون الخدمة المدنية الفلسطيني ولائحة الهيئات المحلية</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-black bg-white text-black border border-slate-300 shadow-2xs shrink-0">
                            معايير رسمية
                        </span>
                    </div>

                    {{-- Guidance Content with Larger Font Size --}}
                    <div class="p-6 space-y-4 text-sm text-slate-700 leading-relaxed">

                        {{-- Rule 1: Base Salary & Grade Scale --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                1
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm mb-1">الراتب الأساسي ومربوط الخدمة:</h4>
                                <p class="text-slate-600 text-sm">
                                    يتم احتساب الراتب الأساسي الأولي آلياً وفق <strong>سلم درجات الخدمة المدنية</strong> المعتمد في البلدية، وتُضاف عليه العلاوة الدورية السنوية (المربوط) عن كل سنة خدمة مسجلة للموظف.
                                </p>
                            </div>
                        </div>

                        {{-- Rule 2: Nature of Work Allowance --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                2
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm mb-1">علاوة طبيعة العمل:</h4>
                                <p class="text-slate-600 text-sm">
                                    تُحتسب تلقائياً كنسبة مئوية من الراتب الأساسي بحسب المؤهل العلمي والتخصص الوظيفي المحدد في ملف الموظف (كالمهندسين، المحاسبين، الفنيين، والمهن الإدارية).
                                </p>
                            </div>
                        </div>

                        {{-- Rule 3: Social & Family Allowance --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                3
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm mb-1">العلاوة الاجتماعية (الزوجة والأولاد):</h4>
                                <p class="text-slate-600 text-sm">
                                    تُضاف العلاوة الاجتماعية الثابتة: <strong>60 ₪</strong> عن الزوجة + <strong>20 ₪</strong> عن كل ابن مستحق مسجل في بيانات الموظف وفق الشروط النظامية.
                                </p>
                            </div>
                        </div>

                        {{-- Rule 4: Qualification & Position Allowances --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <div class="w-7 h-7 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                4
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm mb-1">علاوات المؤهل العلمي والمسؤولية الإدارية:</h4>
                                <p class="text-slate-600 text-sm">
                                    تُحتسب علاوة المؤهل العلمي (دبلوم، بكالوريوس، ماجستير، دكتوراه)، بالإضافة إلى علاوة المنصب والمسؤولية بحسب الهيكلية الإدارية (مدير دائرة، رئيس قسم، مسؤول شعبة).
                                </p>
                            </div>
                        </div>

                        {{-- Rule 5: Deductions & Pension --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/70">
                            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-800 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                5
                            </div>
                            <div>
                                <h4 class="font-black text-slate-900 text-sm mb-1">الاستقطاعات الإلزامية والقروض:</h4>
                                <p class="text-slate-600 text-sm">
                                    تُخصم استقطاعات هيئة التقاعد والمعاشات (<strong>10%</strong> من الراتب الأساسي + علاوة طبيعة العمل) لموظفي التثبيت الدائم، بالإضافة إلى قسط التأمين الصحي، وأقساط السلف والديون البلدية.
                                </p>
                            </div>
                        </div>

                        {{-- Rule 6: Draft & Manual Adjustments --}}
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80">
                            <div class="w-7 h-7 rounded-lg bg-amber-200 text-amber-900 flex items-center justify-center font-black text-xs shrink-0 mt-0.5">
                                6
                            </div>
                            <div>
                                <h4 class="font-black text-amber-950 text-sm mb-1">إمكانية التعديل والمراجعة قبل الاعتماد:</h4>
                                <p class="text-amber-900 text-sm">
                                    يتم توليد الكشف كـ <strong class="text-amber-950">مسودة</strong> تتيح للمدقق المالي مراجعة صافي كل موظف وتعديل الساعات الإضافية أو المكافآت أو الخصومات الجزائية يدوياً قبل الاعتماد النهائي والطباعة.
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- Footer Note --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-200 text-xs text-slate-600 flex items-center justify-between">
                        <span>العملة المعتمدة للصرف: <strong class="text-slate-900">شيكل إسرائيلي (₪ NIS)</strong></span>
                        <span class="text-teal-700 font-bold">بلدية بيت حانون</span>
                    </div>

                </div>

            </div>

        </div>

    </div>
</x-hr-layout>