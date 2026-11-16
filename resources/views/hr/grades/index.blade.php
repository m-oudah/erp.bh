<x-hr-layout>
    <div x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: '',
        formAction: '{{ route('hr.grades.store') }}',
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
            this.modalTitle = 'إضافة درجة وظيفية جديدة';
            this.formAction = '{{ route('hr.grades.store') }}';
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
            this.formAction = '/hr/grades/' + grade.id;
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
    }" class="space-y-6">

        {{-- Top Bar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-academic-cap class="w-5 h-5 text-secondary" />
                    <span>جدول درجات وسلم رواتب الخدمة المدنية الفلسطيني</span>
                </h2>
                <p class="text-xs text-tertiary mt-0.5">
                    الرواتب الأساسية المعتمدة والعلاوات الدورية السنوية (المربوط) وفق قانون الخدمة المدنية ولائحة الهيئات المحلية
                </p>
            </div>

            <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-primary/20">
                <x-heroicon-s-plus class="w-4 h-4 text-amber-300" />
                <span>إضافة درجة وظيفية</span>
            </button>
        </div>

        {{-- Grades Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-tertiary font-bold">
                            <th class="py-3 px-4">الفئة</th>
                            <th class="py-3 px-4">رمز الدرجة</th>
                            <th class="py-3 px-4">مسمى الدرجة</th>
                            <th class="py-3 px-4">بداية المربوط (الأساسي الأولي)</th>
                            <th class="py-3 px-4">العلاوة الدورية السنوية (المربوط)</th>
                            <th class="py-3 px-4">الحد الأقصى للسنوات</th>
                            <th class="py-3 px-4">الموظفون على الدرجة</th>
                            <th class="py-3 px-4">ملاحظات والتأهيل</th>
                            <th class="py-3 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($grades as $grade)
                            <tr class="hover:bg-slate-50/70 transition-colors">
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
                                <td class="py-3.5 px-4 font-extrabold text-slate-800">
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
                                <td class="py-3.5 px-4 text-tertiary text-[11px] max-w-xs truncate">
                                    {{ $grade->notes ?: '—' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" @click="openEdit({{ json_encode($grade) }})"
                                                class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors" title="تعديل الدرجة">
                                            <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                        </button>
                                        @if($grade->employees_count === 0)
                                            <form method="POST" action="{{ route('hr.grades.destroy', $grade->id) }}"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الدرجة؟')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors" title="حذف">
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
                 class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-800" x-text="modalTitle"></h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">
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
                            <label class="block text-xs font-bold text-slate-700 mb-1">الفئة <span class="text-rose-500">*</span></label>
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
                            <input type="text" name="grade_code" x-model="gradeCode" required placeholder="A1, 1, 2, عمال..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم / وصف الدرجة <span class="text-rose-500">*</span></label>
                        <input type="text" name="grade_name" x-model="gradeName" required placeholder="الدرجة الخامسة (5)..."
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
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-xs">
                            حفظ
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-hr-layout>

