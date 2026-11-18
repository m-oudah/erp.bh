<x-hr-layout>
    <div class="space-y-5">

        {{-- Top Bar & Filters --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <span>سجل موظفي البلدية</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200">
                            {{ $employees->total() }} موظف
                        </span>
                    </h2>
                    <p class="text-xs text-tertiary mt-0.5">إدارة ومتابعة كادر بلدية بيت حانون والبيانات الوظيفية والمالية</p>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('hr.employees.create') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-primary/20">
                        <x-heroicon-s-user-plus class="w-4 h-4 text-amber-300" />
                        <span>إضافة موظف جديد</span>
                    </a>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('hr.employees.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 pt-3 border-t border-slate-100">
                {{-- Search query --}}
                <div class="lg:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="بحث بالاسم، رقم الهوية، الرقم الوظيفي..."
                           class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800">
                    <div class="absolute left-3 top-2.5 text-slate-400">
                        <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800 font-bold">
                        <option value="">جميع الحالات (النشطين، المتقاعدين...)</option>
                        @foreach(\App\Models\HR\Employee::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Contract Type Filter --}}
                <div>
                    <select name="contract_type" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800">
                        <option value="">جميع أنواع العقود</option>
                        @foreach(\App\Models\HR\Employee::CONTRACT_TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('contract_type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Department Filter --}}
                <div>
                    <select name="department_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-800">
                        <option value="">جميع الوحدات والدوائر</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-secondary hover:bg-secondary-dark text-white rounded-xl text-xs font-bold transition-colors cursor-pointer">
                        تصفية
                    </button>
                    @if(request()->hasAny(['search', 'contract_type', 'department_id', 'status', 'grade_id']))
                        <a href="{{ route('hr.employees.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-colors" title="إعادة تعيين">
                            إلغاء
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-tertiary font-bold">
                            <th class="py-3 px-4">الموظف</th>
                            <th class="py-3 px-4">رقم الهوية</th>
                            <th class="py-3 px-4">المسمى الوظيفي</th>
                            <th class="py-3 px-4">الوحدة الإدارية</th>
                            <th class="py-3 px-4">الدرجة / السلم</th>
                            <th class="py-3 px-4">نوع العقد</th>
                            <th class="py-3 px-4">الحالة</th>
                            <th class="py-3 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($employees as $emp)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 font-bold flex items-center justify-center shrink-0 text-xs">
                                            {{ mb_substr($emp->first_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('hr.employees.show', $emp->id) }}" class="font-extrabold text-slate-800 hover:text-primary transition-colors">
                                                {{ $emp->full_name }}
                                            </a>
                                            <div class="text-[10px] text-tertiary font-mono">{{ $emp->employee_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                    {{ $emp->national_id }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-700">
                                    {{ $emp->job_title }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">
                                    {{ $emp->department?->name ?? '—' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($emp->grade)
                                        <span class="inline-flex items-center gap-1 text-emerald-700 font-bold">
                                            <span>{{ $emp->grade->grade_code }}</span>
                                            <span class="text-tertiary text-[10px]">مربوط ({{ $emp->current_step }})</span>
                                        </span>
                                    @elseif($emp->custom_base_salary)
                                        <span class="text-slate-700 font-bold">مقطوع ({{ number_format($emp->custom_base_salary) }} ₪)</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold
                                        @if($emp->contract_type === 'permanent') bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @elseif($emp->contract_type === 'temporary_contract') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($emp->contract_type === 'daily') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($emp->contract_type === 'unemployment') bg-purple-50 text-purple-700 border border-purple-200
                                        @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                        {{ $emp->contract_type_arabic }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($emp->status === 'active')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>على رأس عمله</span>
                                        </span>
                                    @elseif($emp->status === 'martyr')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                                            <span>شهيد</span>
                                        </span>
                                    @elseif($emp->status === 'retired')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            <span>متقاعد</span>
                                        </span>
                                    @elseif($emp->status === 'prisoner')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <span>أسير</span>
                                        </span>
                                    @elseif($emp->status === 'abroad')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-md border border-cyan-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                            <span>مسافر خارج الوطن</span>
                                        </span>
                                    @elseif($emp->status === 'suspended')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>موقوف</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>{{ $emp->status_arabic }}</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('hr.employees.show', $emp->id) }}"
                                           class="w-7 h-7 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-700 flex items-center justify-center transition-colors"
                                           title="الملف الوظيفي 360°">
                                            <x-heroicon-s-eye class="w-3.5 h-3.5" />
                                        </a>
                                        <a href="{{ route('hr.employees.edit', $emp->id) }}"
                                           class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors"
                                           title="تعديل البيانات">
                                            <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                        </a>
                                        <form method="POST" action="{{ route('hr.employees.destroy', $emp->id) }}"
                                              onsubmit="return confirm('هل أنت متأكد من حذف سجل الموظف؟')" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors"
                                                    title="حذف الموظف">
                                                <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-tertiary">
                                    <x-heroicon-o-users class="w-12 h-12 mx-auto text-slate-300 mb-2" />
                                    <p class="font-bold text-slate-700">لا يوجد موظفون يطابقون خيارات البحث والتصفية</p>
                                    <p class="text-[11px] text-tertiary mt-1">تأكد من شروط البحث أو قم بإضافة موظف جديد</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($employees->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>

    </div>
</x-hr-layout>

