<x-correspondence-layout>
    <div class="max-w-4xl mx-auto">
        {{-- Header Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-secondary"></span>
                        إنشاء وتوجيه مذكرة داخلية جديدة
                    </h2>
                    <p class="text-xs text-tertiary mt-1">توجيه إداري إلكتروني ذكي مع أرشفة تلقائية وتكليف بالمهام</p>
                </div>
                <a href="{{ route('correspondence.memos.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-gray-50 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition-colors">
                    إلغاء والعودة
                </a>
            </div>

            <form method="POST" action="{{ route('correspondence.memos.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Subject & Priority --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">موضوع المذكرة <span class="text-rose-500">*</span></label>
                        <input type="text"
                               name="subject"
                               value="{{ old('subject') }}"
                               placeholder="مثال: طلب تقرير فني بخصوص المخطط التفصيلي للشارع الغربي..."
                               required
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                        @error('subject')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">درجة الأولوية <span class="text-rose-500">*</span></label>
                        <select name="priority" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-semibold">
                            <option value="عادية" {{ old('priority') == 'عادية' ? 'selected' : '' }}>عادية</option>
                            <option value="متوسطة" {{ old('priority') == 'متوسطة' ? 'selected' : '' }}>متوسطة</option>
                            <option value="عاجلة" {{ old('priority') == 'عاجلة' ? 'selected' : '' }}>عاجلة</option>
                            <option value="سري / عاجل جداً" {{ old('priority') == 'سري / عاجل جداً' ? 'selected' : '' }}>سري / عاجل جداً</option>
                        </select>
                        @error('priority')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Routing / Assignee & Department --}}
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-4">
                    <div class="text-xs font-bold text-slate-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                        وجهة المذكرة والمسؤولية المباشرة
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">القسم / الدائرة المعنية</label>
                            <select name="target_department_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                                <option value="">اختياري (حدد الدائرة المعنية إن وجدت)</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('target_department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">الموظف الموجهة إليه تحديداً (في عهدته) <span class="text-rose-500">*</span></label>
                            <select name="current_assignee_id" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-bold text-teal-800">
                                <option value="">اختر الموظف المستلم للمعاملة...</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('current_assignee_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->department->name ?? 'موظف' }} - {{ $user->job_title ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-tertiary mt-1">تصل المذكرة فوراً إلى صندوق الوارد الخاص به فقط وتظهر في صادر حسابك.</p>
                        </div>
                    </div>
                </div>

                {{-- Content & Details --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">تفاصيل ومحتوى المذكرة <span class="text-rose-500">*</span></label>
                        <span class="text-[11px] text-secondary font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            ميزة ذكية: استخدم <span class="font-bold underline text-secondary">@اسم_الموظف</span> في النص لاستخراج وتكليف مهمة تلقائياً!
                        </span>
                    </div>
                    <textarea name="content"
                              rows="6"
                              required
                              placeholder="اكتب تفاصيل المذكرة هنا... بإمكانك كتابة توجيه محدد لموظف بالصيغة: @أحمد_علي يرجى تدقيق الملف الهندسي وإبداء الرأي خلال 48 ساعة."
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date & Archive File Linking --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الموعد النهائي للإنجاز / الاستحقاق</label>
                        <input type="date"
                               name="due_date"
                               value="{{ old('due_date') }}"
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ربط بملف أرشيف مركزي قائم (اختياري)</label>
                        <select name="linked_archive_file_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                            <option value="">لا يوجد ربط (سيتم إنشاء ملف أرشيف خاص بالمذكرة تلقائياً)</option>
                            @foreach ($archiveFiles as $file)
                                <option value="{{ $file->id }}" {{ old('linked_archive_file_id') == $file->id ? 'selected' : '' }}>
                                    {{ $file->file_no }} - {{ $file->file_name ?? $file->fileType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Attachments Upload --}}
                <div class="border-2 border-dashed border-gray-200 hover:border-primary/50 rounded-2xl p-6 text-center transition-colors bg-slate-50/50">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary mx-auto flex items-center justify-center mb-2 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        إرفاق وثائق ومستندات
                    </label>
                    <p class="text-xs text-tertiary mb-3">
                        PDF, DOCX, XLSX, صور أو ملفات مضغوطة (بحد أقصى 15MB للملف).
                    </p>
                    <input type="file"
                           name="attachments[]"
                           multiple
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-teal-700 cursor-pointer">
                    <div class="mt-2 text-[11px] text-teal-700 font-semibold flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        سيتم حفظ وأرشفة جميع المرفقات تلقائياً في الأرشيف المركزي الموحد.
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('correspondence.memos.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-primary hover:bg-teal-700 shadow-md transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        إرسال وتوجيه المذكرة الآن
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-correspondence-layout>
