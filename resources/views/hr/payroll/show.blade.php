<x-hr-layout>
    <div x-data="{
        showEditModal: false,
        editAction: '',
        employeeName: '',
        overtimeAllowance: 0,
        bonusAllowance: 0,
        otherAllowances: 0,
        loanDeduction: 0,
        otherDeductions: 0,
        itemNotes: '',

        openEdit(item) {
            this.editAction = '/hr/payroll/{{ $payroll->id }}/items/' + item.id;
            this.employeeName = item.employee_name;
            this.overtimeAllowance = item.overtime_allowance || 0;
            this.bonusAllowance = item.bonus_allowance || 0;
            this.otherAllowances = item.other_allowances || 0;
            this.loanDeduction = item.loan_deduction || 0;
            this.otherDeductions = item.other_deductions || 0;
            this.itemNotes = item.notes || '';
            this.showEditModal = true;
        }
    }" class="space-y-6">

        {{-- Top Bar & Actions --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-xl font-black text-slate-800">{{ $payroll->title }}</h2>
                    @if($payroll->status === 'approved')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>معتمد رسمياً</span>
                        </span>
                    @elseif($payroll->status === 'paid')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-blue-50 text-blue-800 border border-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            <span>مصروع ومغلق</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>مسودة قيد المراجعة</span>
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-3 text-xs text-tertiary mt-2 flex-wrap">
                    <span class="font-mono">رقم المسير: <strong class="text-slate-700">{{ $payroll->payroll_number }}</strong></span>
                    <span>•</span>
                    <span>الشهر: <strong class="text-slate-700">{{ $payroll->monthNameArabic }} {{ $payroll->year }}</strong></span>
                    <span>•</span>
                    <span>عدد الموظفين: <strong class="text-slate-700">{{ $payroll->total_employees }} موظف</strong></span>
                    @if($payroll->approved_at)
                        <span>•</span>
                        <span>تاريخ الاعتماد: <strong class="text-slate-700">{{ $payroll->approved_at->format('Y-m-d H:i') }}</strong></span>
                    @endif
                </div>
            </div>

            {{-- Workflow Buttons --}}
            <div class="flex items-center gap-2 flex-wrap self-end md:self-center">
                {{-- Export to Excel (Split Button with formats) --}}
                <div class="relative inline-flex rounded-xl shadow-xs" x-data="{ exportOpen: false }">
                    <a href="{{ route('hr.payroll.export', $payroll->id) }}"
                       class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-r-xl text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>تصدير إلى إكسل (Excel)</span>
                    </a>
                    <button type="button" @click="exportOpen = !exportOpen"
                            class="px-2 py-2 bg-emerald-800 hover:bg-emerald-900 text-white rounded-l-xl text-xs transition-colors border-r border-emerald-600/70 cursor-pointer"
                            title="خيارات التنسيق">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="exportOpen" @click.away="exportOpen = false" x-transition
                         class="absolute left-0 top-full mt-1.5 w-52 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 z-30 text-xs">
                        <a href="{{ route('hr.payroll.export', [$payroll->id, 'format' => 'xls']) }}"
                           class="flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-800 font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <span>ملف إكسل منسق (.xls)</span>
                        </a>
                        <a href="{{ route('hr.payroll.export', [$payroll->id, 'format' => 'csv']) }}"
                           class="flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-800 font-bold">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <span>ملف بيانات CSV (.csv)</span>
                        </a>
                    </div>
                </div>

                {{-- Print Full Sheet --}}
                <a href="{{ route('hr.payroll.print', $payroll->id) }}" target="_blank"
                   class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                    <x-heroicon-s-printer class="w-4 h-4 text-amber-400" />
                    <span>طباعة كشف الرواتب</span>
                </a>

                {{-- Approve (if draft) --}}
                @if($payroll->status === 'draft')
                    @if(auth()->user()->hasRole('مدير النظام') || auth()->user()->can('Approve-Payroll'))
                        <form method="POST" action="{{ route('hr.payroll.approve', $payroll->id) }}"
                              onsubmit="return confirm('هل أنت متأكد من اعتماد كشف الرواتب نهائياً؟ بعد الاعتماد لا يمكن تعديل البنود إلا بصلاحيات خاصة.')">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs cursor-pointer">
                                <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-200" />
                                <span>اعتماد كشف الرواتب</span>
                            </button>
                        </form>
                    @endif
                @endif

                {{-- Mark as Paid (if approved) --}}
                @if($payroll->status === 'approved')
                    <form method="POST" action="{{ route('hr.payroll.mark-paid', $payroll->id) }}"
                          onsubmit="return confirm('تأكيد صرف الرواتب للموظفين وإغلاق المسير؟')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 shadow-xs">
                            <x-heroicon-s-currency-dollar class="w-4 h-4" />
                            <span>تأكيد الصرف والإغلاق</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('hr.payroll.index') }}"
                   class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors flex items-center gap-1">
                    <x-heroicon-s-arrow-right class="w-4 h-4" />
                    <span>الرجوع</span>
                </a>
            </div>
        </div>

        {{-- KPI Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                <div class="text-[11px] font-bold text-tertiary">إجمالي الرواتب الأساسية</div>
                <div class="text-xl font-black text-slate-800 mt-1">
                    {{ number_format($payroll->total_basic_salary, 2) }} <span class="text-xs text-tertiary">₪</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                <div class="text-[11px] font-bold text-emerald-700">إجمالي العلاوات والبدلات</div>
                <div class="text-xl font-black text-emerald-600 mt-1">
                    + {{ number_format($payroll->total_allowances, 2) }} <span class="text-xs text-emerald-600">₪</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
                <div class="text-[11px] font-bold text-rose-700">إجمالي الخصومات والاستقطاعات</div>
                <div class="text-xl font-black text-rose-600 mt-1">
                    - {{ number_format($payroll->total_deductions, 2) }} <span class="text-xs text-rose-600">₪</span>
                </div>
            </div>

            <div class="bg-gradient-to-l from-slate-900 to-teal-900 text-white rounded-2xl p-4 shadow-sm">
                <div class="text-[11px] font-bold text-amber-300">صافي المسير المصروف للبلدية</div>
                <div class="text-2xl font-black text-amber-300 mt-1">
                    {{ number_format($payroll->total_net_salary, 2) }} <span class="text-xs font-bold">₪</span>
                </div>
            </div>
        </div>

        {{-- Filter / Search Bar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('hr.payroll.show', $payroll->id) }}" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[200px] relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="بحث باسم الموظف أو رقمه الوظيفي..."
                           class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                    </div>
                </div>

                <div class="w-48">
                    <select name="department" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="">جميع الوحدات والدوائر</option>
                        @foreach($departmentsList as $dName)
                            <option value="{{ $dName }}" {{ request('department') === $dName ? 'selected' : '' }}>{{ $dName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-44">
                    <select name="contract_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <option value="">جميع أنواع العقود</option>
                        @foreach($contractTypesList as $cType)
                            <option value="{{ $cType }}" {{ request('contract_type') === $cType ? 'selected' : '' }}>{{ $cType }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-colors">
                    تصفية
                </button>

                @if(request()->hasAny(['search', 'department', 'contract_type']))
                    <a href="{{ route('hr.payroll.show', $payroll->id) }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-colors">
                        إلغاء
                    </a>
                @endif
            </form>
        </div>

        {{-- Master Payroll Sheet Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-100/80 border-b border-slate-200 text-slate-700 font-extrabold text-[11px]">
                            <th class="py-3 px-3">#</th>
                            <th class="py-3 px-3">الموظف</th>
                            <th class="py-3 px-3">الوحدة والمسمى</th>
                            <th class="py-3 px-3">الأساسي</th>
                            <th class="py-3 px-3">طبيعة عمل</th>
                            <th class="py-3 px-3">إجمالي العلاوات</th>
                            <th class="py-3 px-3">القائم</th>
                            <th class="py-3 px-3">إجمالي الخصم</th>
                            <th class="py-3 px-3 bg-teal-50 text-teal-900 font-black">الصافي (₪)</th>
                            <th class="py-3 px-3 text-center">قسيمة / تعديل</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($items as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $item->is_manually_adjusted ? 'bg-amber-50/30' : '' }}">
                                <td class="py-2.5 px-3 font-mono text-tertiary">{{ $items->firstItem() + $index }}</td>
                                <td class="py-2.5 px-3">
                                    <div class="font-extrabold text-slate-900">{{ $item->employee_name }}</div>
                                    <div class="text-[10px] text-tertiary font-mono">{{ $item->employee_number }}</div>
                                </td>
                                <td class="py-2.5 px-3">
                                    <div class="font-bold text-slate-800 text-[11px] truncate max-w-[130px]">{{ $item->job_title }}</div>
                                    <div class="text-[10px] text-tertiary truncate max-w-[130px]">{{ $item->department_name }}</div>
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-slate-800">
                                    {{ number_format($item->base_salary, 2) }}
                                </td>
                                <td class="py-2.5 px-3 font-mono text-emerald-700">
                                    {{ number_format($item->nature_of_work_allowance, 2) }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-emerald-800">
                                    {{ number_format($item->total_allowances, 2) }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-slate-900 bg-slate-50/50">
                                    {{ number_format($item->gross_salary, 2) }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-bold text-rose-700">
                                    {{ number_format($item->total_deductions, 2) }}
                                </td>
                                <td class="py-2.5 px-3 font-mono font-black text-slate-900 text-sm bg-teal-50/60">
                                    {{ number_format($item->net_salary, 2) }}
                                </td>
                                <td class="py-2.5 px-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('hr.payroll.slip', [$payroll->id, $item->id]) }}" target="_blank"
                                           class="p-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg transition-colors" title="طباعة قسيمة الراتب">
                                            <x-heroicon-s-document-text class="w-4 h-4" />
                                        </a>

                                        @if($payroll->status === 'draft')
                                            <button type="button" @click="openEdit({{ json_encode($item) }})"
                                                    class="p-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg transition-colors" title="تعديل الإضافي أو الخصومات">
                                                <x-heroicon-s-pencil-square class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-10 text-tertiary">
                                    لا يوجد موظفون يطابقون شروط البحث
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t-2 border-slate-300">
                        <tr class="bg-slate-200 text-slate-900 font-black text-xs">
                            <td colspan="3" class="py-3 px-3 text-center text-slate-900 font-black bg-slate-200 border-r border-slate-300">
                                الإجمالي العام لمسير الرواتب
                            </td>
                            <td class="py-3 px-3 font-mono font-black text-slate-900 bg-slate-200">
                                {{ number_format($payroll->total_basic_salary, 2) }} ₪
                            </td>
                            <td class="bg-slate-200"></td>
                            <td class="py-3 px-3 font-mono font-black text-slate-900 bg-slate-200">
                                + {{ number_format($payroll->total_allowances, 2) }} ₪
                            </td>
                            <td class="py-3 px-3 font-mono font-black text-slate-900 bg-slate-200">
                                {{ number_format($payroll->total_basic_salary + $payroll->total_allowances, 2) }} ₪
                            </td>
                            <td class="py-3 px-3 font-mono font-black text-slate-900 bg-slate-200">
                                - {{ number_format($payroll->total_deductions, 2) }} ₪
                            </td>
                            <td class="py-3 px-3 font-mono text-black text-sm font-black bg-slate-300 border-x border-slate-300">
                                {{ number_format($payroll->total_net_salary, 2) }} ₪
                            </td>
                            <td class="bg-slate-200"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pagination Links --}}
            @if($items->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
                    <div class="text-xs text-slate-500 font-bold">
                        عرض <span class="font-extrabold text-slate-800">{{ $items->firstItem() }}</span> إلى <span class="font-extrabold text-slate-800">{{ $items->lastItem() }}</span> من إجمالي <span class="font-extrabold text-slate-800">{{ $items->total() }}</span> موظف في الكشف
                    </div>
                    <div>
                        {{ $items->links() }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Edit Item Modal (for manual adjustments in draft mode) --}}
        <div x-show="showEditModal" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showEditModal = false"
                 class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">تعديل بنود راتب الموظف</h3>
                        <p class="text-xs text-primary font-bold mt-0.5" x-text="employeeName"></p>
                    </div>
                    <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <form :action="editAction" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">العمل الإضافي (₪)</label>
                            <input type="number" step="0.01" name="overtime_allowance" x-model.number="overtimeAllowance"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">المكافأة التشجيعية (₪)</label>
                            <input type="number" step="0.01" name="bonus_allowance" x-model.number="bonusAllowance"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">علاوات أخرى (₪)</label>
                            <input type="number" step="0.01" name="other_allowances" x-model.number="otherAllowances"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">قسط سلفة / قرض (₪)</label>
                            <input type="number" step="0.01" name="loan_deduction" x-model.number="loanDeduction"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">خصومات واستقطاعات أخرى (₪)</label>
                        <input type="number" step="0.01" name="other_deductions" x-model.number="otherDeductions"
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ملاحظات التعديل</label>
                        <input type="text" name="notes" x-model="itemNotes" placeholder="سبب التعديل أو المكافأة..."
                               class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-xs">
                            تحديث وإعادة احتساب الراتب
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-hr-layout>

