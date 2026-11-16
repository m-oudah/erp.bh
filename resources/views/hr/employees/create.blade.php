<x-hr-layout>
    <div x-data="salaryCalculator()" x-init="calculate()" class="space-y-6">

        {{-- Top Header --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-user-plus class="w-5 h-5 text-secondary" />
                    <span>إضافة موظف جديد إلى كادر البلدية</span>
                </h2>
                <p class="text-xs text-tertiary mt-0.5">تسجيل بيانات الهوية، الهيكلية الإدارية، سلم الخدمة المدنية، والبدلات والاستقطاعات</p>
            </div>

            <a href="{{ route('hr.employees.index') }}"
               class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                <x-heroicon-s-arrow-right class="w-4 h-4" />
                <span>الرجوع لسجل الموظفين</span>
            </a>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('hr.employees.store') }}" class="space-y-6">
            @csrf

            {{-- 1. Personal & Identity --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs">1</div>
                    <h3 class="text-sm font-extrabold text-slate-800">بيانات الهوية والمعلومات الشخصية</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Employee Number --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الرقم الوظيفي <span class="text-rose-500">*</span></label>
                        <input type="text" name="employee_number" value="{{ old('employee_number', $suggestedEmpNumber) }}" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary @error('employee_number') border-rose-400 @enderror">
                        @error('employee_number') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- National ID --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهوية الوطنية (9 أرقام) <span class="text-rose-500">*</span></label>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" maxlength="9" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary @error('national_id') border-rose-400 @enderror">
                        @error('national_id') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الجنس <span class="text-rose-500">*</span></label>
                        <select name="gender" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تاريخ الميلاد</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                {{-- Names Quadruple --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الاسم الأول <span class="text-rose-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary @error('first_name') border-rose-400 @enderror">
                        @error('first_name') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم الأب</label>
                        <input type="text" name="second_name" value="{{ old('second_name') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم الجد</label>
                        <input type="text" name="third_name" value="{{ old('third_name') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم العائلة <span class="text-rose-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary @error('last_name') border-rose-400 @enderror">
                        @error('last_name') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Social & Family --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الحالة الاجتماعية</label>
                        <select name="marital_status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(\App\Models\HR\Employee::MARITAL_STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ old('marital_status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">عدد الأبناء المستحقين للعلاوة</label>
                        <input type="number" name="dependents_count" x-model.number="dependentsCount" @input="calculate()" min="0" max="30" value="{{ old('dependents_count', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p class="text-[10px] text-tertiary mt-1">20 ₪ لكل ابن مستحق وفق القانون</p>
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="relative flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_spouse_allowance" value="1" x-model="hasSpouseAllowance" @change="calculate()"
                                   {{ old('has_spouse_allowance') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-primary focus:ring-primary/20 border-slate-300">
                            <span class="text-xs font-bold text-slate-700">استحقاق علاوة الزوجة (60 ₪)</span>
                        </label>
                    </div>
                </div>

                {{-- Contacts --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">رقم الهاتف المحمول</label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" placeholder="059xxxxxxx"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">عنوان السكن</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="بيت حانون - الحي..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>
            </div>

            {{-- 2. Organization & Job --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs">2</div>
                    <h3 class="text-sm font-extrabold text-slate-800">الهيكلية الإدارية والوظيفة</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Department / Unit --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الوحدة الإدارية / الدائرة</label>
                        <select name="department_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">-- اختر الوحدة الإدارية --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }} ({{ $dept->type_arabic }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Job Title --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">المسمى الوظيفي <span class="text-rose-500">*</span></label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" required placeholder="مهندس مدني، محاسب، سائق..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary @error('job_title') border-rose-400 @enderror">
                        @error('job_title') <p class="text-[10px] text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contract Type --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">نوع العقد المعتمد <span class="text-rose-500">*</span></label>
                        <select name="contract_type" x-model="contractType" @change="calculate()" required
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(\App\Models\HR\Employee::CONTRACT_TYPES as $key => $label)
                                <option value="{{ $key }}" {{ old('contract_type', 'permanent') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Direct Manager --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">المسؤول المباشر (تحديد يدوي)</label>
                        <select name="direct_manager_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">-- بدون مسؤول مباشر / رئيس البلدية --</option>
                            @foreach($managers as $mgr)
                                <option value="{{ $mgr->id }}" {{ old('direct_manager_id') == $mgr->id ? 'selected' : '' }}>
                                    {{ $mgr->full_name }} ({{ $mgr->job_title }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                    {{-- Hire Date --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تاريخ مباشرة العمل / التعيين</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- End of Contract Date --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تاريخ انتهاء العقد (للعقود المؤقتة)</label>
                        <input type="date" name="end_of_contract_date" value="{{ old('end_of_contract_date') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- Employment Status --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الحالة الوظيفية <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(\App\Models\HR\Employee::STATUSES as $key => $label)
                                <option value="{{ $key }}" {{ old('status', 'active') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- System User Account Link --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ربط بحساب مستخدم نظام (اختياري)</label>
                        <select name="user_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">-- بدون حساب مستخدم (سجل موظف فقط) --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- 3. Palestinian Civil Service Salary Scale & Allowances --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs">3</div>
                        <h3 class="text-sm font-extrabold text-slate-800">سلم الخدمة المدنية، البدلات، والاستقطاعات</h3>
                    </div>
                    <span class="text-[11px] font-bold text-primary bg-primary/5 px-2.5 py-1 rounded-lg">
                        يتم الاحتساب آلياً بناء على الدرجة وسنوات المربوط
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Civil Service Grade --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الدرجة وفق الخدمة المدنية</label>
                        <select name="grade_id" x-model="selectedGradeId" @change="onGradeChange()"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">-- بدون درجة (راتب مقطوع / يومي) --</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}"
                                        data-base="{{ $grade->base_salary }}"
                                        data-raise="{{ $grade->annual_raise }}"
                                        data-steps="{{ $grade->max_steps }}"
                                        {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->grade_name }} (أساسي: {{ number_format($grade->base_salary) }} ₪)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Step (العلاوة الدورية / المربوط) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">سنوات المربوط (العلاوة الدورية)</label>
                        <input type="number" name="current_step" x-model.number="currentStep" @input="calculate()" min="0" max="25" value="{{ old('current_step', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p class="text-[10px] text-tertiary mt-1" x-text="`العلاوة السنوية للدرجة: ${annualRaise} ₪`"></p>
                    </div>

                    {{-- Custom Base Salary Override --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الراتب الأساسي المقطوع (استثنائي / يومي)</label>
                        <input type="number" step="0.01" name="custom_base_salary" x-model.number="customBaseSalary" @input="calculate()" placeholder="في حال تحديد راتب مقطوع..."
                               value="{{ old('custom_base_salary') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p class="text-[10px] text-tertiary mt-1">يُترك فارغاً لاعتماد سلم الدرجة تلقائياً</p>
                    </div>

                    {{-- Nature of Work Allowance Rate --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">علاوة طبيعة العمل (% من الأساسي)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="nature_of_work_rate" x-model.number="natureOfWorkRate" @input="calculate()" placeholder="0"
                                   value="{{ old('nature_of_work_rate', 0) }}"
                                   class="w-full pl-8 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">%</span>
                        </div>
                        <p class="text-[10px] text-tertiary mt-1">مهندسون (40-60%)، محاسبون وقانون (20-30%)</p>
                    </div>
                </div>

                {{-- Allowances --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 pt-2">
                    {{-- Qualification --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">المؤهل العلمي</label>
                        <select name="qualification" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            @foreach(\App\Models\HR\Employee::QUALIFICATIONS as $key => $label)
                                <option value="{{ $key }}" {{ old('qualification') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Qualification Allowance --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">علاوة المؤهل (₪)</label>
                        <input type="number" step="0.01" name="qualification_allowance" x-model.number="qualificationAllowance" @input="calculate()"
                               value="{{ old('qualification_allowance', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- Position / Responsibility Allowance --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">علاوة المنصب / المسؤولية (₪)</label>
                        <input type="number" step="0.01" name="position_allowance" x-model.number="positionAllowance" @input="calculate()"
                               value="{{ old('position_allowance', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- Cost of Living Allowance --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">غلاء معيشة ومخاطرة (₪)</label>
                        <input type="number" step="0.01" name="cost_of_living_allowance" x-model.number="costOfLivingAllowance" @input="calculate()"
                               value="{{ old('cost_of_living_allowance', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    {{-- Other Allowances --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">علاوات ثابتة أخرى (₪)</label>
                        <input type="number" step="0.01" name="other_allowances" x-model.number="otherAllowances" @input="calculate()"
                               value="{{ old('other_allowances', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                {{-- Deductions --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pt-3 border-t border-slate-100">
                    <div class="flex flex-col justify-center">
                        <label class="relative flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_pension_subscribed" value="1" x-model="isPensionSubscribed" @change="calculate()"
                                   {{ old('is_pension_subscribed', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-primary focus:ring-primary/20 border-slate-300">
                            <span class="text-xs font-bold text-slate-700">خاضع لهيئة التقاعد (10%)</span>
                        </label>
                        <p class="text-[10px] text-tertiary mt-1">10% من (الأساسي + طبيعة العمل) للتثبيت الدائم</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">التأمين الصحي الثابت (₪)</label>
                        <input type="number" step="0.01" name="health_insurance_fixed" x-model.number="healthInsuranceFixed" @input="calculate()"
                               value="{{ old('health_insurance_fixed', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">قسط سلف / قرض بلدي شهري (₪)</label>
                        <input type="number" step="0.01" name="loan_deduction" x-model.number="loanDeduction" @input="calculate()"
                               value="{{ old('loan_deduction', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">استقطاعات ثابتة أخرى (₪)</label>
                        <input type="number" step="0.01" name="other_deductions" x-model.number="otherDeductions" @input="calculate()"
                               value="{{ old('other_deductions', 0) }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                {{-- LIVE SALARY ESTIMATOR WIDGET --}}
                <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/90 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black text-slate-900 flex items-center gap-1.5">
                            <x-heroicon-s-calculator class="w-4 h-4 text-emerald-700" />
                            <span>المعاينة الفورية للراتب التقديري (قانون الخدمة المدنية الفلسطيني)</span>
                        </span>
                        <span class="text-[10px] bg-white border border-slate-200 text-slate-700 font-bold px-2 py-0.5 rounded shadow-2xs">حساب لحظي</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div class="bg-white p-3 rounded-xl border border-slate-200/90 shadow-2xs">
                            <div class="text-[11px] font-bold text-black">الراتب الأساسي</div>
                            <div class="text-base font-black text-black mt-1">
                                <span x-text="computedBase.toFixed(2)"></span> ₪
                            </div>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-slate-200/90 shadow-2xs">
                            <div class="text-[11px] font-bold text-black">إجمالي العلاوات</div>
                            <div class="text-base font-black text-black mt-1">
                                + <span x-text="computedAllowances.toFixed(2)"></span> ₪
                            </div>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-slate-200/90 shadow-2xs">
                            <div class="text-[11px] font-bold text-black">إجمالي الاستقطاعات</div>
                            <div class="text-base font-black text-black mt-1">
                                - <span x-text="computedDeductions.toFixed(2)"></span> ₪
                            </div>
                        </div>

                        <div class="bg-emerald-800 text-white p-3 rounded-xl border border-emerald-900 shadow-sm" style="background-color: #065f46;">
                            <div class="text-[11px] font-bold text-emerald-100">صافي الراتب المتوقع</div>
                            <div class="text-lg font-black text-white mt-1">
                                <span x-text="computedNet.toFixed(2)"></span> ₪
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Bank Information --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs">4</div>
                    <h3 class="text-sm font-extrabold text-slate-800">بيانات الحساب البنكي والتحويل المالي</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم البنك</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="بنك فلسطين، البنك الإسلامي الفلسطيني..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">فرع البنك</label>
                        <input type="text" name="bank_branch" value="{{ old('bank_branch') }}" placeholder="فرع جباليا، فرع الرمال..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">رقم الحساب</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">رقم الآيبان (IBAN)</label>
                        <input type="text" name="iban" value="{{ old('iban') }}" placeholder="PS00..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">ملاحظات إضافية</label>
                    <textarea name="notes" rows="2" placeholder="أي بيانات أو شروط وظيفية إضافية..."
                              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('hr.employees.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dark text-white text-xs font-bold transition-all shadow-sm shadow-primary/20 hover:shadow">
                    حفظ وتسجيل الموظف
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
        function salaryCalculator() {
            return {
                selectedGradeId: '{{ old('grade_id') }}',
                gradeBase: 0,
                annualRaise: 0,
                currentStep: {{ old('current_step', 0) }},
                customBaseSalary: '{{ old('custom_base_salary') }}' ? parseFloat('{{ old('custom_base_salary') }}') : null,
                natureOfWorkRate: {{ old('nature_of_work_rate', 0) }},
                hasSpouseAllowance: {{ old('has_spouse_allowance') ? 'true' : 'false' }},
                dependentsCount: {{ old('dependents_count', 0) }},
                qualificationAllowance: {{ old('qualification_allowance', 0) }},
                positionAllowance: {{ old('position_allowance', 0) }},
                costOfLivingAllowance: {{ old('cost_of_living_allowance', 0) }},
                otherAllowances: {{ old('other_allowances', 0) }},
                isPensionSubscribed: {{ old('is_pension_subscribed', '1') ? 'true' : 'false' }},
                contractType: '{{ old('contract_type', 'permanent') }}',
                healthInsuranceFixed: {{ old('health_insurance_fixed', 0) }},
                loanDeduction: {{ old('loan_deduction', 0) }},
                otherDeductions: {{ old('other_deductions', 0) }},

                computedBase: 0,
                computedAllowances: 0,
                computedDeductions: 0,
                computedNet: 0,

                onGradeChange() {
                    const sel = document.querySelector('select[name="grade_id"]');
                    if (sel && sel.selectedIndex > 0) {
                        const opt = sel.options[sel.selectedIndex];
                        this.gradeBase = parseFloat(opt.dataset.base || 0);
                        this.annualRaise = parseFloat(opt.dataset.raise || 0);
                    } else {
                        this.gradeBase = 0;
                        this.annualRaise = 0;
                    }
                    this.calculate();
                },

                calculate() {
                    if (this.gradeBase === 0 && this.selectedGradeId) {
                        const sel = document.querySelector('select[name="grade_id"]');
                        if (sel && sel.selectedIndex > 0) {
                            const opt = sel.options[sel.selectedIndex];
                            this.gradeBase = parseFloat(opt.dataset.base || 0);
                            this.annualRaise = parseFloat(opt.dataset.raise || 0);
                        }
                    }

                    let base = 0;
                    if (this.customBaseSalary && this.customBaseSalary > 0) {
                        base = parseFloat(this.customBaseSalary);
                    } else if (this.gradeBase > 0) {
                        base = this.gradeBase + (this.annualRaise * (this.currentStep || 0));
                    }
                    this.computedBase = base;

                    // Allowances
                    let nature = base * ((this.natureOfWorkRate || 0) / 100);
                    let spouse = this.hasSpouseAllowance ? 60.00 : 0.00;
                    let children = (this.dependentsCount || 0) * 20.00;
                    let qual = parseFloat(this.qualificationAllowance || 0);
                    let pos = parseFloat(this.positionAllowance || 0);
                    let col = parseFloat(this.costOfLivingAllowance || 0);
                    let other = parseFloat(this.otherAllowances || 0);

                    this.computedAllowances = nature + spouse + children + qual + pos + col + other;

                    // Deductions
                    let pension = 0;
                    if (this.isPensionSubscribed && this.contractType === 'permanent') {
                        pension = (base + nature) * 0.10;
                    }
                    let health = parseFloat(this.healthInsuranceFixed || 0);
                    let loan = parseFloat(this.loanDeduction || 0);
                    let otherDed = parseFloat(this.otherDeductions || 0);

                    this.computedDeductions = pension + health + loan + otherDed;

                    let gross = base + this.computedAllowances;
                    this.computedNet = Math.max(0, gross - this.computedDeductions);
                }
            }
        }
    </script>
    @endpush
</x-hr-layout>

