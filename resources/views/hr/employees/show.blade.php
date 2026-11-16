<x-hr-layout>
    <div class="space-y-6">

        {{-- Top Employee Profile Header Card --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-teal-600 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-teal-700/20 shrink-0">
                        {{ mb_substr($employee->first_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-xl font-black text-slate-800">{{ $employee->full_name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                                @if($employee->contract_type === 'permanent') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($employee->contract_type === 'temporary_contract') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($employee->contract_type === 'daily') bg-blue-50 text-blue-700 border border-blue-200
                                @elseif($employee->contract_type === 'unemployment') bg-purple-50 text-purple-700 border border-purple-200
                                @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                {{ $employee->contract_type_arabic }}
                            </span>
                            @if($employee->status === 'active')
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>على رأس عمله</span>
                                </span>
                            @elseif($employee->status === 'suspended')
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>موقوف عن العمل</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                                    <span>منتهية خدماته</span>
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 text-xs text-tertiary mt-2 flex-wrap">
                            <span class="flex items-center gap-1">
                                <x-heroicon-s-briefcase class="w-4 h-4 text-slate-400" />
                                <strong class="text-slate-700">{{ $employee->job_title }}</strong>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <x-heroicon-s-building-office class="w-4 h-4 text-slate-400" />
                                <span>{{ $employee->department?->name ?? 'غير مسكن في وحدة إدارية' }}</span>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1 font-mono">
                                <span class="text-slate-400">الرقم الوظيفي:</span>
                                <strong class="text-slate-700">{{ $employee->employee_number }}</strong>
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1 font-mono">
                                <span class="text-slate-400">الهوية:</span>
                                <strong class="text-slate-700">{{ $employee->national_id }}</strong>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 self-end md:self-center">
                    <a href="{{ route('hr.employees.edit', $employee->id) }}"
                       class="px-4 py-2 bg-secondary hover:bg-secondary-dark text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                        <x-heroicon-s-pencil-square class="w-4 h-4" />
                        <span>تعديل الملف</span>
                    </a>

                    <a href="{{ route('hr.employees.index') }}"
                       class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors flex items-center gap-1">
                        <x-heroicon-s-arrow-right class="w-4 h-4" />
                        <span>الرجوع</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Main 2-Column Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left/Main 2-Cols: Financial & Civil Service Breakdown --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Civil Service Salary & Allowances Card --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                                <x-heroicon-s-banknotes class="w-4 h-4" />
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-slate-800">بيانات سلم الرواتب والبدلات والاستقطاعات</h3>
                                <p class="text-[11px] text-tertiary">وفق قانون الخدمة المدنية الفلسطيني ولائحة الهيئات المحلية</p>
                            </div>
                        </div>

                        <div class="text-left">
                            <span class="text-[11px] text-tertiary block">صافي الراتب التقديري الشهري:</span>
                            <span class="text-xl font-black text-emerald-600">
                                {{ number_format($salaryPreview['net_salary'], 2) }} ₪
                            </span>
                        </div>
                    </div>

                    {{-- Grade & Step Metadata --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                        <div>
                            <span class="text-tertiary block text-[11px]">الدرجة المصنفة:</span>
                            <span class="font-extrabold text-slate-800 mt-0.5 block">
                                {{ $employee->grade?->grade_name ?? 'بدون درجة' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-tertiary block text-[11px]">الفئة:</span>
                            <span class="font-bold text-slate-700 mt-0.5 block">
                                {{ $employee->grade?->category ?? 'مقطوع' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-tertiary block text-[11px]">سنوات المربوط (العلاوة الدورية):</span>
                            <span class="font-extrabold text-primary mt-0.5 block">
                                مربوط ({{ $employee->current_step }})
                            </span>
                        </div>
                        <div>
                            <span class="text-tertiary block text-[11px]">الراتب الأساسي المحتسب:</span>
                            <span class="font-black text-slate-900 mt-0.5 block">
                                {{ number_format($salaryPreview['base_salary'], 2) }} ₪
                            </span>
                        </div>
                    </div>

                    {{-- Allowances Breakdown Table --}}
                    <div>
                        <h4 class="text-xs font-black text-slate-700 mb-2.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>تفاصيل الاستحقاقات والعلاوات الشهرية</span>
                        </h4>

                        <div class="bg-slate-50/70 rounded-xl border border-slate-100 overflow-hidden">
                            <table class="w-full text-xs text-right">
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">الراتب الأساسي</td>
                                        <td class="py-2.5 px-4 text-tertiary">مربوط الدرجة وفق سلم الخدمة المدنية</td>
                                        <td class="py-2.5 px-4 font-bold text-slate-900 text-left font-mono">{{ number_format($salaryPreview['base_salary'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">علاوة طبيعة العمل</td>
                                        <td class="py-2.5 px-4 text-tertiary">{{ $employee->nature_of_work_rate }}% من الراتب الأساسي</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['nature_of_work_allowance'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">العلاوة الاجتماعية (الزوجة)</td>
                                        <td class="py-2.5 px-4 text-tertiary">{{ $employee->has_spouse_allowance ? 'مستحقة (ربة منزل غير موظفة)' : 'غير مستحقة' }}</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['spouse_allowance'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">العلاوة الاجتماعية (الأولاد)</td>
                                        <td class="py-2.5 px-4 text-tertiary">{{ $employee->dependents_count }} أبناء مستحقين (20 ₪ لكل ابن)</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['children_allowance'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">علاوة المؤهل العلمي</td>
                                        <td class="py-2.5 px-4 text-tertiary">{{ $employee->qualification_arabic }}</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['qualification_allowance'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">علاوة المنصب / المسؤولية</td>
                                        <td class="py-2.5 px-4 text-tertiary">مسؤولية إدارية وإشرافية</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['position_allowance'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">علاوة غلاء المعيشة والمخاطرة</td>
                                        <td class="py-2.5 px-4 text-tertiary">بدلات ميدانية وظروف خاصة</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['cost_of_living_allowance'] + $salaryPreview['risk_allowance'], 2) }} ₪</td>
                                    </tr>
                                    @if($salaryPreview['other_allowances'] > 0)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">علاوات ثابتة أخرى</td>
                                        <td class="py-2.5 px-4 text-tertiary">بدلات معتمدة</td>
                                        <td class="py-2.5 px-4 font-bold text-emerald-700 text-left font-mono">+ {{ number_format($salaryPreview['other_allowances'], 2) }} ₪</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-emerald-50/50 font-bold">
                                        <td class="py-2.5 px-4 text-emerald-900" colspan="2">إجمالي الراتب القائم (المستحقات)</td>
                                        <td class="py-2.5 px-4 font-black text-emerald-800 text-left font-mono">{{ number_format($salaryPreview['gross_salary'], 2) }} ₪</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Deductions Breakdown Table --}}
                    <div>
                        <h4 class="text-xs font-black text-slate-700 mb-2.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>تفاصيل الاستقطاعات الشهرية الثابتة</span>
                        </h4>

                        <div class="bg-slate-50/70 rounded-xl border border-slate-100 overflow-hidden">
                            <table class="w-full text-xs text-right">
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">هيئة التقاعد والتأمينات والمعاشات</td>
                                        <td class="py-2.5 px-4 text-tertiary">{{ $employee->is_pension_subscribed ? '10% من (الأساسي + طبيعة العمل)' : 'غير خاضع للتقاعد' }}</td>
                                        <td class="py-2.5 px-4 font-bold text-rose-600 text-left font-mono">- {{ number_format($salaryPreview['pension_deduction'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">التأمين الصحي</td>
                                        <td class="py-2.5 px-4 text-tertiary">استقطاع شهري ثابت</td>
                                        <td class="py-2.5 px-4 font-bold text-rose-600 text-left font-mono">- {{ number_format($salaryPreview['health_insurance_deduction'], 2) }} ₪</td>
                                    </tr>
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">أقساط السلف والقروض البلدية</td>
                                        <td class="py-2.5 px-4 text-tertiary">سداد قسط سلفة معتمد</td>
                                        <td class="py-2.5 px-4 font-bold text-rose-600 text-left font-mono">- {{ number_format($salaryPreview['loan_deduction'], 2) }} ₪</td>
                                    </tr>
                                    @if($salaryPreview['other_deductions'] > 0)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-2.5 px-4 text-slate-700">استقطاعات أخرى</td>
                                        <td class="py-2.5 px-4 text-tertiary">خصم ثابت</td>
                                        <td class="py-2.5 px-4 font-bold text-rose-600 text-left font-mono">- {{ number_format($salaryPreview['other_deductions'], 2) }} ₪</td>
                                    </tr>
                                    @endif
                                    <tr class="bg-rose-50/50 font-bold">
                                        <td class="py-2.5 px-4 text-rose-900" colspan="2">إجمالي الاستقطاعات والخصومات</td>
                                        <td class="py-2.5 px-4 font-black text-rose-800 text-left font-mono">- {{ number_format($salaryPreview['total_deductions'], 2) }} ₪</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Summary Net Bar --}}
                    <div class="bg-slate-900 text-white rounded-xl p-4 flex items-center justify-between">
                        <div>
                            <div class="text-xs font-bold text-amber-300">صافي الراتب المستحق في المسير</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">الراتب القائم ({{ number_format($salaryPreview['gross_salary'], 2) }} ₪) - إجمالي الخصومات ({{ number_format($salaryPreview['total_deductions'], 2) }} ₪)</div>
                        </div>
                        <div class="text-2xl font-black text-amber-300">
                            {{ number_format($salaryPreview['net_salary'], 2) }} <span class="text-sm font-bold">₪</span>
                        </div>
                    </div>
                </div>

                {{-- Payroll History for this Employee --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                                <x-heroicon-s-document-text class="w-4 h-4" />
                            </div>
                            <h3 class="text-sm font-extrabold text-slate-800">سجل مسيرات الرواتب السابقة</h3>
                        </div>
                        <span class="text-xs text-tertiary font-bold">{{ $employee->payrollItems->count() }} مسير مسجل</span>
                    </div>

                    @if($employee->payrollItems->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-tertiary font-bold border-b border-slate-100">
                                        <th class="py-2.5 px-3">الشهر / السنة</th>
                                        <th class="py-2.5 px-3">رقم المسير</th>
                                        <th class="py-2.5 px-3">الأساسي</th>
                                        <th class="py-2.5 px-3">العلاوات</th>
                                        <th class="py-2.5 px-3">الاستقطاعات</th>
                                        <th class="py-2.5 px-3">الصافي</th>
                                        <th class="py-2.5 px-3 text-center">قسيمة الراتب</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium">
                                    @foreach($employee->payrollItems as $item)
                                        <tr class="hover:bg-slate-50">
                                            <td class="py-2.5 px-3 font-bold text-slate-800">
                                                {{ $item->payroll?->monthNameArabic }} {{ $item->payroll?->year }}
                                            </td>
                                            <td class="py-2.5 px-3 font-mono text-tertiary">
                                                {{ $item->payroll?->payroll_number }}
                                            </td>
                                            <td class="py-2.5 px-3 font-mono">{{ number_format($item->base_salary, 2) }} ₪</td>
                                            <td class="py-2.5 px-3 font-mono text-emerald-700">+ {{ number_format($item->total_allowances, 2) }} ₪</td>
                                            <td class="py-2.5 px-3 font-mono text-rose-600">- {{ number_format($item->total_deductions, 2) }} ₪</td>
                                            <td class="py-2.5 px-3 font-mono font-black text-slate-900">{{ number_format($item->net_salary, 2) }} ₪</td>
                                            <td class="py-2.5 px-3 text-center">
                                                <a href="{{ route('hr.payroll.slip', [$item->payroll_id, $item->id]) }}" target="_blank"
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 bg-primary/10 hover:bg-primary/20 text-primary font-bold rounded-lg text-[11px] transition-colors">
                                                    <x-heroicon-s-printer class="w-3.5 h-3.5" />
                                                    <span>طباعة القسيمة</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 text-tertiary text-xs">
                            <p>لم يدرج الموظف في أي مسير رواتب حتى الآن.</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column (1-Col): Hierarchy, Banking & Personal Details --}}
            <div class="space-y-6">

                {{-- Hierarchy & Management Card --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center">
                            <x-heroicon-s-rectangle-stack class="w-4 h-4" />
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-800">التسكين الإداري والهيكلية</h3>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-tertiary block text-[11px]">الوحدة الإدارية:</span>
                            <span class="font-extrabold text-slate-800 text-sm mt-0.5 block">
                                {{ $employee->department?->name ?? 'غير محدد' }}
                            </span>
                            <span class="text-[10px] text-tertiary">{{ $employee->department?->type_arabic }}</span>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">المسؤول المباشر:</span>
                            @if($employee->directManager)
                                <a href="{{ route('hr.employees.show', $employee->directManager->id) }}" class="font-extrabold text-primary hover:underline mt-0.5 block">
                                    {{ $employee->directManager->full_name }}
                                </a>
                                <span class="text-[10px] text-tertiary">{{ $employee->directManager->job_title }}</span>
                            @else
                                <span class="font-bold text-slate-600 mt-0.5 block">رئيس البلدية / الإدارة العليا مباشرة</span>
                            @endif
                        </div>

                        @if($employee->subordinates->count() > 0)
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">الموظفون التابعون إدارياً ({{ $employee->subordinates->count() }}):</span>
                            <div class="mt-1 space-y-1">
                                @foreach($employee->subordinates as $sub)
                                    <a href="{{ route('hr.employees.show', $sub->id) }}" class="flex items-center justify-between text-xs p-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                                        <span class="font-bold text-slate-700">{{ $sub->full_name }}</span>
                                        <span class="text-[10px] text-tertiary">{{ $sub->job_title }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">تاريخ المباشرة:</span>
                            <span class="font-bold text-slate-700 mt-0.5 block">
                                {{ $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '—' }}
                            </span>
                        </div>

                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">حساب النظام المرتبط:</span>
                            @if($employee->user)
                                <span class="font-bold text-emerald-700 mt-0.5 block flex items-center gap-1">
                                    <x-heroicon-s-check-circle class="w-4 h-4 text-emerald-500" />
                                    <span>{{ $employee->user->name }} ({{ $employee->user->email }})</span>
                                </span>
                            @else
                                <span class="text-slate-400 mt-0.5 block">سجل موظف فقط (بدون مستخدم نظام)</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Bank & Financial Account Card --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                            <x-heroicon-s-credit-card class="w-4 h-4" />
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-800">بيانات الحساب البنكي</h3>
                    </div>

                    <div class="space-y-3 text-xs font-medium">
                        <div>
                            <span class="text-tertiary block text-[11px]">اسم البنك:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $employee->bank_name ?: 'غير مسجل' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">الفرع:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $employee->bank_branch ?: 'غير مسجل' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">رقم الحساب:</span>
                            <span class="font-bold font-mono text-slate-800 mt-0.5 block">{{ $employee->bank_account_number ?: 'غير مسجل' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">الآيبان الدولي (IBAN):</span>
                            <span class="font-bold font-mono text-slate-800 mt-0.5 block text-[11px]">{{ $employee->iban ?: 'غير مسجل' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Personal & Contact Card --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                            <x-heroicon-s-phone class="w-4 h-4" />
                        </div>
                        <h3 class="text-sm font-extrabold text-slate-800">بيانات الاتصال والعنوان</h3>
                    </div>

                    <div class="space-y-3 text-xs font-medium">
                        <div>
                            <span class="text-tertiary block text-[11px]">الهاتف المحمول:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block font-mono">{{ $employee->mobile ?: '—' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">البريد الإلكتروني:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $employee->email ?: '—' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">عنوان السكن:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $employee->address ?: '—' }}</span>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-tertiary block text-[11px]">الحالة الاجتماعية:</span>
                            <span class="font-bold text-slate-800 mt-0.5 block">{{ $employee->marital_status_arabic }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-hr-layout>

