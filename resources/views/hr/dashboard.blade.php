<x-hr-layout>
    <div class="space-y-6">

        {{-- Top Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Employees --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-tertiary mb-1">إجمالي الكادر الوظيفي</div>
                    <div class="text-2xl font-black text-slate-800">{{ number_format($totalEmployees) }}</div>
                    <div class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ $activeEmployees }} على رأس عملهم</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-user-group class="w-6 h-6" />
                </div>
            </div>

            {{-- Monthly Payroll Estimate --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-tertiary mb-1">فاتورة الرواتب الشهرية التقديرية</div>
                    <div class="text-2xl font-black text-slate-800">
                        {{ number_format($estimatedMonthlyTotal, 2) }} <span class="text-xs font-bold text-amber-600">₪</span>
                    </div>
                    <div class="text-[11px] text-tertiary font-semibold mt-1">
                        صافي مستحق للموظفين النشطين
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-banknotes class="w-6 h-6" />
                </div>
            </div>

            {{-- Departments & Units --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-tertiary mb-1">الهيكل والوحدات الإدارية</div>
                    <div class="text-2xl font-black text-slate-800">{{ $totalDepartments }}</div>
                    <div class="text-[11px] text-tertiary font-semibold mt-1">
                        {{ $directoratesCount }} دائرة كبرى / {{ $sectionsCount }} قسم وشعبة
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-rectangle-stack class="w-6 h-6" />
                </div>
            </div>

            {{-- Civil Service Grades --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <div class="text-xs font-bold text-tertiary mb-1">سلم درجات الخدمة المدنية</div>
                    <div class="text-2xl font-black text-slate-800">{{ $gradesCount }}</div>
                    <div class="text-[11px] text-emerald-600 font-semibold mt-1">
                        معتمد ومفعل تلقائياً
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-academic-cap class="w-6 h-6" />
                </div>
            </div>
        </div>

        {{-- Middle Row: Contracts breakdown + Latest Payroll --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Contracts Breakdown (2 cols on LG) --}}
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">توزيع الكادر الوظيفي حسب نوع العقد</h3>
                        <p class="text-xs text-tertiary mt-0.5">أنواع العقود المعتمدة في البلدية وتوزيع العاملين عليها</p>
                    </div>
                    <a href="{{ route('hr.employees.index') }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                        <span>عرض الكل</span>
                        <x-heroicon-s-arrow-left class="w-3.5 h-3.5" />
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    {{-- 1. Permanent --}}
                    <div class="bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">تثبيت دائم</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">{{ $contracts['permanent'] }}</div>
                        <div class="text-[11px] text-tertiary mt-1">خاضع للخدمة المدنية والتقاعد</div>
                    </div>

                    {{-- 2. Temporary Contract --}}
                    <div class="bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">عقد مؤقت</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">{{ $contracts['temporary_contract'] }}</div>
                        <div class="text-[11px] text-tertiary mt-1">عقود محددة المدة</div>
                    </div>

                    {{-- 3. Daily --}}
                    <div class="bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">مياومة / يومي</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">{{ $contracts['daily'] }}</div>
                        <div class="text-[11px] text-tertiary mt-1">أجر يومي / عمال وفنيين</div>
                    </div>

                    {{-- 4. Unemployment --}}
                    <div class="bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">بطالة تشغيل مؤقت</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">{{ $contracts['unemployment'] }}</div>
                        <div class="text-[11px] text-tertiary mt-1">مشاريع تشغيل طارئة</div>
                    </div>

                    {{-- 5. Delegation --}}
                    <div class="bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-xl p-4 border border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-700">انتداب</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                        </div>
                        <div class="text-2xl font-black text-slate-900">{{ $contracts['delegation'] }}</div>
                        <div class="text-[11px] text-tertiary mt-1">منتدب من جهات أخرى</div>
                    </div>

                    {{-- Action Card --}}
                    <a href="{{ route('hr.employees.create') }}"
                       class="border-2 border-dashed border-primary/30 hover:border-primary/60 hover:bg-primary/5 rounded-xl p-4 flex flex-col items-center justify-center text-center transition-all group">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                            <x-heroicon-s-plus class="w-5 h-5" />
                        </div>
                        <span class="text-xs font-bold text-primary">إضافة موظف جديد</span>
                    </a>
                </div>
            </div>

            {{-- Latest Payroll Status --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-extrabold text-slate-800">آخر احتساب رواتب</h3>
                        <a href="{{ route('hr.payroll.index') }}" class="text-xs font-bold text-secondary hover:underline">السجل الكامل</a>
                    </div>

                    @if($latestPayroll)
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-sm text-slate-800">{{ $latestPayroll->title }}</span>
                                @if($latestPayroll->status === 'approved')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">معتمد</span>
                                @elseif($latestPayroll->status === 'paid')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 border border-blue-200">مصروع</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200">مسودة</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-tertiary">رقم المسير:</span>
                                    <span class="font-bold text-slate-700 font-mono">{{ $latestPayroll->payroll_number }}</span>
                                </div>
                                <div>
                                    <span class="text-tertiary">الموظفين:</span>
                                    <span class="font-bold text-slate-700">{{ $latestPayroll->total_employees }} موظف</span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-200/80 flex items-center justify-between">
                                <span class="text-xs text-tertiary font-bold">إجمالي الصافي:</span>
                                <span class="text-base font-black text-emerald-700">
                                    {{ number_format($latestPayroll->total_net_salary, 2) }} ₪
                                </span>
                            </div>

                            <div class="pt-2 flex gap-2">
                                <a href="{{ route('hr.payroll.show', $latestPayroll->id) }}"
                                   class="flex-1 text-center py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-colors">
                                    عرض الكشف
                                </a>
                                <a href="{{ route('hr.payroll.print', $latestPayroll->id) }}" target="_blank"
                                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                                   title="طباعة كشف المسير">
                                    <x-heroicon-s-printer class="w-4 h-4" />
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-tertiary text-xs">
                            <x-heroicon-o-banknotes class="w-10 h-10 mx-auto text-slate-300 mb-2" />
                            <p>لم يتم احتساب أي رواتب حتى الآن.</p>
                            <a href="{{ route('hr.payroll.create') }}" class="inline-block mt-3 px-4 py-2 bg-secondary text-white font-bold rounded-xl text-xs hover:bg-secondary-dark transition-colors">
                                احتساب رواتب للشهر الحالي
                            </a>
                        </div>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                    <p class="text-[11px] text-tertiary font-medium">العملة الرسمية لرواتب البلدية: الشيكل الفلسطيني / الإسرائيلي (₪ NIS)</p>
                </div>
            </div>
        </div>

    </div>
</x-hr-layout>

