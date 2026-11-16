<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم / إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                إعدادات شئون الموظفين والرواتب
            </h1>
            <p class="text-teal-100 text-xs mt-1 font-medium">سلم درجات الخدمة المدنية الفلسطيني وبداية المربوط والعلاوات الدورية السنوية</p>
        </div>
    </x-slot>

    <div x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: '',
        formAction: '{{ route('settings.hr.grades.store') }}',
        gradeId: null,
        gradeCategory: 'الثانية',
        gradeCode: '',
        gradeName: '',
        gradeBaseSalary: 0,
        gradeAnnualRaise: 0,
        gradeMaxSteps: 20,
        gradeNotes: '',

        openCreate() {
            this.isEdit = false;
            this.modalTitle = 'إضافة درجة وظيفية جديدة إلى السلم';
            this.formAction = '{{ route('settings.hr.grades.store') }}';
            this.gradeId = null;
            this.gradeCategory = 'الثانية';
            this.gradeCode = '';
            this.gradeName = '';
            this.gradeBaseSalary = 1600;
            this.gradeAnnualRaise = 20;
            this.gradeMaxSteps = 20;
            this.gradeNotes = '';
            this.showModal = true;
        },

        openEdit(grade) {
            this.isEdit = true;
            this.modalTitle = 'تعديل الدرجة: ' + grade.grade_name;
            this.formAction = '/settings/hr/grades/' + grade.id;
            this.gradeId = grade.id;
            this.gradeCategory = grade.category;
            this.gradeCode = grade.grade_code;
            this.gradeName = grade.grade_name;
            this.gradeBaseSalary = grade.base_salary;
            this.gradeAnnualRaise = grade.annual_raise;
            this.gradeMaxSteps = grade.max_steps;
            this.gradeNotes = grade.notes || '';
            this.showModal = true;
        }
    }" class="max-w-7xl mx-auto pb-10 space-y-6">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-emerald-50 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 border border-emerald-200 shadow-xs text-xs font-bold">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 text-rose-800 px-4 py-3 rounded-2xl flex items-center gap-3 border border-rose-200 shadow-xs text-xs font-bold">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Top Navigation & Action Bar --}}
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.06)] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black">
                    <x-heroicon-s-academic-cap class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-black text-slate-800">سلم درجات وجدول رواتب الخدمة المدنية</h2>
                    <p class="text-xs text-slate-500 mt-0.5">تحديد رواتب بداية المربوط والعلاوات الدورية السنوية المعتمدة قانونياً للهيئات المحلية</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('settings.index') }}"
                   class="px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>الرجوع للإعدادات</span>
                </a>

                <button type="button" @click="openCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-2xl text-xs font-bold transition-all shadow-sm shadow-primary/20 cursor-pointer">
                    <x-heroicon-s-plus class="w-4 h-4 text-amber-300" />
                    <span>إضافة درجة وظيفية</span>
                </button>
            </div>
        </div>

        {{-- Metrics Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">الدرجات المعتمدة بالسلم</div>
                <div class="text-xl font-black text-slate-800 font-mono">{{ $stats['total_grades'] }}</div>
                <div class="text-[10px] text-emerald-700 font-semibold mt-1">فئات الخدمة المدنية (عليا - خامسة)</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">الموظفون المسجلون على السلم</div>
                <div class="text-xl font-black text-slate-800 font-mono">{{ $stats['total_assigned_employees'] }}</div>
                <div class="text-[10px] text-teal-700 font-semibold mt-1">كادر بلدي مرتبط بدرجات وظيفية</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">أدنى بداية مربوط (أساسي)</div>
                <div class="text-xl font-black text-slate-800 font-mono">{{ number_format($stats['min_base_salary'], 2) }} ₪</div>
                <div class="text-[10px] text-slate-500 font-semibold mt-1">الحد الأدنى لرواتب الدرجات</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">أعلى بداية مربوط (أساسي)</div>
                <div class="text-xl font-black text-slate-800 font-mono">{{ number_format($stats['max_base_salary'], 2) }} ₪</div>
                <div class="text-[10px] text-slate-500 font-semibold mt-1">الحد الأعلى للفئة العليا (وكيل مساعد)</div>
            </div>
        </div>

        {{-- Grades Table Container --}}
        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.06)] border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-800">جدول السلم القياسي المعتمد</h3>
                    <p class="text-xs text-slate-500 mt-0.5">يمكنك تعديل أي درجة لتغيير الراتب الأساسي ومقدار المربوط السنوي الذي يُحسب تلقائياً في مسير الرواتب</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/80 text-slate-500 font-bold">
                            <th class="py-3.5 px-4">الفئة</th>
                            <th class="py-3.5 px-4">رمز الدرجة</th>
                            <th class="py-3.5 px-4">مسمى الدرجة الوظيفية</th>
                            <th class="py-3.5 px-4">بداية المربوط (الأساسي الأولي)</th>
                            <th class="py-3.5 px-4">العلاوة الدورية السنوية (المربوط)</th>
                            <th class="py-3.5 px-4">الحد الأقصى للسنوات</th>
                            <th class="py-3.5 px-4">الموظفون المسجلون</th>
                            <th class="py-3.5 px-4">ملاحظات ومؤهل الدرجة</th>
                            <th class="py-3.5 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($grades as $grade)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                        @if($grade->category === 'العليا') bg-purple-50 text-purple-700 border border-purple-200
                                        @elseif($grade->category === 'الأولى') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($grade->category === 'الثانية') bg-teal-50 text-teal-700 border border-teal-200
                                        @elseif($grade->category === 'الثالثة') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($grade->category === 'الرابعة') bg-slate-100 text-slate-700 border border-slate-200
                                        @else bg-emerald-50 text-emerald-700 border border-emerald-200 @endif">
                                        {{ $grade->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    {{ $grade->grade_code }}
                                </td>
                                <td class="py-3.5 px-4 font-black text-slate-800">
                                    {{ $grade->grade_name }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-black text-slate-900">
                                    {{ number_format($grade->base_salary, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-emerald-700">
                                    + {{ number_format($grade->annual_raise, 2) }} ₪
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-600">
                                    {{ $grade->max_steps }} سنوات
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('hr.employees.index', ['grade_id' => $grade->id]) }}"
                                       class="inline-flex items-center gap-1 font-bold text-primary hover:underline">
                                        <x-heroicon-s-users class="w-3.5 h-3.5 text-primary" />
                                        <span>{{ $grade->employees_count }} موظف</span>
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px] max-w-xs truncate">
                                    {{ $grade->notes ?: '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" @click="openEdit({{ json_encode($grade) }})"
                                                class="px-2 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center gap-1 text-[11px] font-bold transition-colors cursor-pointer" title="تعديل الدرجة">
                                            <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                            <span>تعديل</span>
                                        </button>
                                        @if($grade->employees_count === 0)
                                            <form method="POST" action="{{ route('settings.hr.grades.destroy', $grade->id) }}"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الدرجة من سلم الرواتب؟')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition-colors cursor-pointer" title="حذف">
                                                    <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add / Edit Grade Modal --}}
        <div x-show="showModal" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showModal = false"
                 class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800" x-text="modalTitle"></h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <form :action="formAction" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الفئة القياسية <span class="text-rose-500">*</span></label>
                            <select name="category" x-model="gradeCategory" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="العليا">العليا</option>
                                <option value="الأولى">الأولى</option>
                                <option value="الثانية">الثانية</option>
                                <option value="الثالثة">الثالثة</option>
                                <option value="الرابعة">الرابعة</option>
                                <option value="الخامسة">الخامسة (عمال وخدمات)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">رمز الدرجة <span class="text-rose-500">*</span></label>
                            <input type="text" name="grade_code" x-model="gradeCode" required placeholder="مثال: A1, 1, 2, 5..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">مسمى الدرجة الوظيفية <span class="text-rose-500">*</span></label>
                        <input type="text" name="grade_name" x-model="gradeName" required placeholder="مثال: الدرجة الأولى، الدرجة الخامسة..."
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">بداية المربوط (₪) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="base_salary" x-model.number="gradeBaseSalary" required
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">العلاوة الدورية (₪) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="annual_raise" x-model.number="gradeAnnualRaise" required
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">أقصى مربوط <span class="text-rose-500">*</span></label>
                            <input type="number" name="max_steps" x-model.number="gradeMaxSteps" required min="1" max="40"
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">ملاحظات وشروط المؤهل</label>
                        <textarea name="notes" x-model="gradeNotes" rows="2" placeholder="المؤهل المطلوب وشروط الترقية..."
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-xs cursor-pointer">
                            حفظ الدرجة
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
