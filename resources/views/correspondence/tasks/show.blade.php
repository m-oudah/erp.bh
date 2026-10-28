<x-correspondence-layout>
    {{-- Header Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('correspondence.tasks.index') }}" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-slate-600 transition-colors" title="العودة لقائمة المهام">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $task->title }}</h2>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $task->priority_badge_class }}">
                        {{ $task->priority }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $task->status_badge_class }}">
                        {{ $task->status }}
                    </span>
                </div>
                <div class="flex items-center gap-3 text-xs text-tertiary mt-1">
                    <span>كلف بواسطة: <b class="text-slate-700">{{ $task->creator->name ?? 'غير محدد' }}</b></span>
                    <span>•</span>
                    <span>المكلف بالتنفيذ: <b class="text-teal-700">{{ $task->assignee->name ?? 'غير محدد' }}</b></span>
                </div>
            </div>
        </div>

        {{-- Quick Status Change Form --}}
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('correspondence.tasks.status', $task->id) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 px-3">
                    <option value="جديدة" {{ $task->status == 'جديدة' ? 'selected' : '' }}>حالة: جديدة</option>
                    <option value="قيد التنفيذ" {{ $task->status == 'قيد التنفيذ' ? 'selected' : '' }}>حالة: قيد التنفيذ</option>
                    <option value="بانتظار الرد" {{ $task->status == 'بانتظار الرد' ? 'selected' : '' }}>حالة: بانتظار الرد</option>
                    <option value="تم الإنجاز" {{ $task->status == 'تم الإنجاز' ? 'selected' : '' }}>حالة: تم الإنجاز</option>
                    <option value="معلقة" {{ $task->status == 'معلقة' ? 'selected' : '' }}>حالة: معلقة</option>
                </select>
            </form>

            @if ($task->memo)
                <a href="{{ route('correspondence.memos.show', $task->memo->id) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>عرض المراسلة الأصلية ({{ $task->memo->memo_number }})</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Layout: Task Details & Feedback Loop (Discussion) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Left / Discussion & Feedback Thread (8 cols) --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- Task Description Box --}}
            @if ($task->description)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-2">
                    <h3 class="text-xs font-bold text-tertiary uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        المطلوب تنفيذه بالتفصيل
                    </h3>
                    <div class="text-sm leading-relaxed text-slate-800 bg-slate-50/60 p-4 rounded-xl border border-slate-100 whitespace-pre-line font-medium">
                        {{ $task->description }}
                    </div>
                </div>
            @endif

            {{-- Comments & Feedback Loop Thread --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        حلقة التغذية الراجعة والنقاش ({{ $task->comments->count() }})
                    </h3>
                    <span class="text-xs text-tertiary">مساحة التواصل المباشر بين المكلِّف والمكلَّف</span>
                </div>

                {{-- Comments List --}}
                <div class="space-y-4">
                    @forelse ($task->comments as $comment)
                        <div class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 {{ $comment->user_id === auth()->id() ? 'bg-teal-50/20 mr-4' : 'bg-slate-50/60 ml-4' }}">
                            <div class="w-8 h-8 rounded-full {{ $comment->user_id === $task->created_by ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }} flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                {{ mb_substr($comment->user->name ?? 'م', 0, 1) }}
                            </div>

                            <div class="flex-1 space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800">{{ $comment->user->name ?? 'موظف' }}</span>
                                        <span class="text-[10px] text-tertiary">({{ $comment->user->department->name ?? 'البلدية' }})</span>
                                        @if ($comment->status_change_to)
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-secondary/10 text-secondary border border-secondary/20">
                                                قام بتغيير الحالة إلى: {{ $comment->status_change_to }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-tertiary" title="{{ $comment->created_at }}">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-700 leading-relaxed font-medium">
                                    {{ $comment->comment }}
                                </div>

                                @if ($comment->attachment_path)
                                    <div class="pt-1.5">
                                        <a href="{{ asset('storage/' . $comment->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-slate-700 hover:text-primary hover:border-primary text-xs font-bold transition-colors">
                                            <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            <span>{{ $comment->attachment_name ?? 'مرفق التعليق' }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-tertiary text-center py-6 bg-gray-50/50 rounded-xl">لا توجد تعليقات أو ردود على هذه المهمة حتى الآن. أضف إفادتك أدناه.</p>
                    @endforelse
                </div>

                {{-- Add Comment Form --}}
                <div class="pt-4 border-t border-gray-100">
                    <form method="POST" action="{{ route('correspondence.tasks.comments.store', $task->id) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">إضافة رد / إفادة إنجاز / ملاحظة</label>
                            <textarea name="comment"
                                      rows="3"
                                      required
                                      placeholder="اكتب ردك أو إفادتك بخصوص ما تم تنفيذه في هذه المهمة..."
                                      class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 items-center">
                            <div>
                                <label class="block text-xs font-semibold text-tertiary mb-1">تغيير حالة المهمة مع هذا الرد</label>
                                <select name="status_change_to" class="w-full text-xs font-bold rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                                    <option value="">بدون تغيير الحالة</option>
                                    <option value="قيد التنفيذ">قيد التنفيذ</option>
                                    <option value="بانتظار الرد">بانتظار الرد / المراجعة</option>
                                    <option value="تم الإنجاز">تم الإنجاز بالكامل</option>
                                    <option value="معلقة">معلقة</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-tertiary mb-1">إرفاق ملف / مسودة تقرير</label>
                                <input type="file"
                                       name="attachment"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-teal-700 cursor-pointer">
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                إرسال التحديث
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Information Card (4 cols) --}}
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3.5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-gray-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    بطاقة بيانات المهمة
                </h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">المكلِّف (صاحب التكليف):</span>
                        <span class="font-bold text-slate-800">{{ $task->creator->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">المكلَّف بالتنفيذ:</span>
                        <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">
                            {{ $task->assignee->name ?? 'غير محدد' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">درجة الأولوية:</span>
                        <span class="font-bold {{ $task->priority_badge_class }} px-2 py-0.5 rounded-md">
                            {{ $task->priority }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">الحالة الحالية:</span>
                        <span class="font-bold {{ $task->status_badge_class }} px-2 py-0.5 rounded-md">
                            {{ $task->status }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">تاريخ التكليف:</span>
                        <span class="font-semibold text-slate-700">{{ $task->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    @if ($task->due_date)
                        <div class="flex items-center justify-between py-1 border-b border-gray-50">
                            <span class="text-tertiary">الموعد النهائي للتسليم:</span>
                            <span class="font-bold {{ $task->is_overdue ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $task->due_date->format('Y-m-d') }}
                            </span>
                        </div>
                    @endif

                    @if ($task->completed_at)
                        <div class="flex items-center justify-between py-1 border-b border-gray-50">
                            <span class="text-tertiary">تاريخ الإنجاز الفعلي:</span>
                            <span class="font-bold text-emerald-700">{{ $task->completed_at->format('Y-m-d H:i') }}</span>
                        </div>
                    @endif

                    @if ($task->memo)
                        <div class="pt-2">
                            <span class="text-tertiary block mb-1">المراسلة المرتبطة:</span>
                            <a href="{{ route('correspondence.memos.show', $task->memo->id) }}" class="font-bold text-primary hover:underline text-xs flex items-center gap-1.5 p-2 rounded-xl bg-teal-50/50 border border-teal-100">
                                <svg class="w-4 h-4 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <div class="text-primary">{{ $task->memo->memo_number }}</div>
                                    <div class="text-[11px] text-tertiary truncate max-w-xs">{{ $task->memo->subject }}</div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</x-correspondence-layout>
