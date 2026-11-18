<x-hr-layout>
    <div class="space-y-6">

        {{-- Top Bar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-banknotes class="w-5 h-5 text-secondary" />
                    <span>سجل احتساب الرواتب الشهرية</span>
                </h2>
                <p class="text-xs text-tertiary mt-0.5">
                    احتساب واعتماد ومتابعة كشوف الرواتب الشهرية وقسائم الصرف لموظفي بلدية بيت حانون
                </p>
            </div>

            <a href="{{ route('hr.payroll.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-secondary hover:bg-secondary-dark text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-secondary/20">
                <x-heroicon-s-calculator class="w-4 h-4 text-white" />
                <span>احتساب رواتب جديد</span>
            </a>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('hr.payroll.index') }}" class="flex flex-wrap items-center gap-3">
                <div class="w-40">
                    <select name="year" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800 font-bold">
                        <option value="">جميع السنوات</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>سنة {{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-44">
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800 font-bold">
                        <option value="">جميع الحالات</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة قيد المراجعة</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>معتمد رسمياً</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>مصروع ومغلق</option>
                    </select>
                </div>

                <button type="submit" class="py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-colors">
                    تصفية
                </button>

                @if(request()->hasAny(['year', 'status']))
                    <a href="{{ route('hr.payroll.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-colors">
                        إلغاء التصفية
                    </a>
                @endif
            </form>
        </div>

        {{-- Payroll List Cards / Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-tertiary font-bold">
                            <th class="py-3 px-4">رقم المسير</th>
                            <th class="py-3 px-4">مسير شهر / سنة</th>
                            <th class="py-3 px-4">الموظفين المشمولين</th>
                            <th class="py-3 px-4">إجمالي الأساسي</th>
                            <th class="py-3 px-4">إجمالي العلاوات</th>
                            <th class="py-3 px-4">إجمالي الخصومات</th>
                            <th class="py-3 px-4">صافي المسير (₪)</th>
                            <th class="py-3 px-4">الحالة</th>
                            <th class="py-3 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($payrolls as $pr)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    {{ $pr->payroll_number }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('hr.payroll.show', $pr->id) }}" class="font-extrabold text-slate-800 hover:text-primary transition-colors text-sm">
                                        {{ $pr->title }}
                                    </a>
                                    <div class="text-[10px] text-tertiary mt-0.5">
                                        {{ $pr->monthNameArabic }} {{ $pr->year }}
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-700">
                                    {{ $pr->total_employees }} موظف
                                </td>
                                <td class="py-3.5 px-4 font-mono text-slate-700">
                                    {{ number_format($pr->total_basic_salary, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">
                                    + {{ number_format($pr->total_allowances, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-rose-600">
                                    - {{ number_format($pr->total_deductions, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4 font-mono font-black text-slate-900 text-sm">
                                    {{ number_format($pr->total_net_salary, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($pr->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>معتمد رسمياً</span>
                                        </span>
                                    @elseif($pr->status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-50 text-blue-800 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>مصروع ومغلق</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>مسودة مراجعة</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('hr.payroll.show', $pr->id) }}"
                                           class="px-2.5 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary font-bold rounded-lg text-xs transition-colors flex items-center gap-1">
                                            <x-heroicon-s-eye class="w-3.5 h-3.5" />
                                            <span>عرض الكشف</span>
                                        </a>

                                        <a href="{{ route('hr.payroll.export', $pr->id) }}"
                                           class="p-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg transition-colors" title="تصدير كشف الرواتب لإكسل">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('hr.payroll.print', $pr->id) }}" target="_blank"
                                           class="p-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition-colors" title="طباعة كشف الرواتب">
                                            <x-heroicon-s-printer class="w-4 h-4" />
                                        </a>

                                        @if($pr->status === 'draft')
                                            <form method="POST" action="{{ route('hr.payroll.destroy', $pr->id) }}"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف مسودة هذا المسير؟')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition-colors" title="حذف المسير">
                                                    <x-heroicon-s-trash class="w-4 h-4" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-tertiary">
                                    <x-heroicon-o-banknotes class="w-12 h-12 mx-auto text-slate-300 mb-2" />
                                    <p class="font-bold text-slate-700 text-sm">لم يتم احتساب أي رواتب حتى الآن</p>
                                    <p class="text-[11px] text-tertiary mt-1">اضغط على زر "احتساب رواتب جديد" لحساب رواتب الشهر تلقائياً</p>
                                    <a href="{{ route('hr.payroll.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-secondary text-white font-bold rounded-xl text-xs hover:bg-secondary-dark transition-colors">
                                        <x-heroicon-s-calculator class="w-4 h-4" />
                                        <span>احتساب رواتب جديد</span>
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payrolls->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $payrolls->links() }}
                </div>
            @endif
        </div>

    </div>
</x-hr-layout>

