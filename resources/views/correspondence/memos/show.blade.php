<x-correspondence-layout>
    {{-- Header Action Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('correspondence.memos.index') }}" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-slate-600 transition-colors" title="العودة للقائمة">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $memo->memo_number }}</h2>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $memo->priority_badge_class }}">
                        {{ $memo->priority }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $memo->status_badge_class }}">
                        {{ $memo->status }}
                    </span>
                </div>
                <h1 class="text-sm font-semibold text-tertiary mt-0.5">{{ $memo->subject }}</h1>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            @if ($memo->autoArchiveFile)
                <a href="{{ route('archive.files.show', $memo->autoArchiveFile->id) }}"
                   target="_blank"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 transition-colors flex items-center gap-1.5"
                   title="عرض ملف المذكرة في الأرشيف المركزي">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                    <span>ملف الأرشيف المركزي</span>
                </a>
            @endif

            @if ($memo->status !== 'تم الإنجاز')
                {{-- Add Attachment Button --}}
                <button type="button"
                        onclick="document.getElementById('add-attachment-modal').classList.remove('hidden')"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span>إضافة مرفق</span>
                </button>

                {{-- Forward / Route Button --}}
                <button type="button"
                        onclick="document.getElementById('forward-modal').classList.remove('hidden')"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-secondary hover:bg-amber-700 shadow-xs transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                    <span>تحويل مع توجيه إداري</span>
                </button>

                {{-- Complete / Close Button --}}
                <button type="button"
                        onclick="document.getElementById('close-modal').classList.remove('hidden')"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-xs transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>إنجاز وإغلاق المذكرة</span>
                </button>
            @else
                <div class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>تم إنجاز المذكرة وإغلاقها</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Layout: 2 Columns (Details & Timeline) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Left / Content Column (8 cols) --}}
        <div class="lg:col-span-8 space-y-5">
            {{-- Memo Content Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="text-xs font-bold text-tertiary uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        محتوى وتفاصيل المذكرة
                    </span>
                    <span class="text-xs text-tertiary">
                        أنشئت في: {{ $memo->created_at->format('Y-m-d H:i') }}
                    </span>
                </div>

                <div class="text-sm leading-relaxed text-slate-800 whitespace-pre-line font-medium bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                    {{ $memo->content }}
                </div>
            </div>

            {{-- Attachments Section --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        المرفقات والمستندات المؤرشفة ({{ $memo->attachments->count() }})
                    </h3>
                    @if ($memo->autoArchiveFile)
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            مؤرشفة آلياً في الأرشيف المركزي
                        </span>
                    @endif
                </div>

                @if ($memo->attachments->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($memo->attachments as $attachment)
                            <div class="p-3.5 rounded-xl border border-gray-100 bg-slate-50/60 hover:bg-slate-50 hover:border-primary/30 transition-all flex items-center justify-between gap-3 group">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-secondary flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-800 truncate" title="{{ $attachment->file_name }}">
                                            {{ $attachment->file_name }}
                                        </h4>
                                        <div class="flex items-center gap-2 text-[11px] text-tertiary mt-0.5">
                                            <span>{{ $attachment->formatted_size }}</span>
                                            <span>•</span>
                                            <span>رفع بواسطة: {{ $attachment->uploader->name ?? 'موظف' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-1">
                                    <a href="{{ route('correspondence.memos.attachments.download', $attachment->id) }}"
                                       class="p-2 rounded-lg bg-white border border-gray-200 text-slate-700 hover:text-primary hover:border-primary shadow-xs transition-colors"
                                       title="تحميل الملف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-tertiary text-center py-4 bg-gray-50/50 rounded-xl">لا توجد مرفقات مع هذه المذكرة حتى الآن.</p>
                @endif
            </div>

            {{-- Linked Tasks Section --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        المهام والتكليفات المرتبطة بالمذكرة ({{ $memo->tasks->count() }})
                    </h3>
                    <a href="{{ route('correspondence.tasks.index') }}" class="text-xs font-bold text-primary hover:underline">
                        عرض لوحة المهام
                    </a>
                </div>

                @if ($memo->tasks->count() > 0)
                    <div class="space-y-2">
                        @foreach ($memo->tasks as $task)
                            <a href="{{ route('correspondence.tasks.show', $task->id) }}"
                               class="p-3.5 rounded-xl border border-gray-100 bg-slate-50/50 hover:bg-teal-50/30 hover:border-primary/30 transition-all flex items-center justify-between gap-3 block group">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-primary transition-colors">
                                        {{ $task->title }}
                                    </h4>
                                    <div class="flex items-center gap-2 text-[11px] text-tertiary mt-1">
                                        <span>مكلف إلى: <b class="text-slate-700">{{ $task->assignee->name ?? 'غير محدد' }}</b></span>
                                        <span>•</span>
                                        <span>الأولوية: <b>{{ $task->priority }}</b></span>
                                        @if ($task->due_date)
                                            <span>•</span>
                                            <span class="{{ $task->is_overdue ? 'text-rose-600 font-bold' : '' }}">موعد التسليم: {{ $task->due_date->format('Y-m-d') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $task->status_badge_class }}">
                                        {{ $task->status }}
                                    </span>
                                    <svg class="w-4 h-4 text-tertiary group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-tertiary text-center py-4 bg-gray-50/50 rounded-xl">لا توجد مهام منشأة أو مستخرجة عبر @mentions لهذه المذكرة.</p>
                @endif
            </div>
        </div>

        {{-- Right Column: Information Card & Timeline (4 cols) --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Quick Meta Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3.5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-gray-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    بطاقة المعاملة الإدارية
                </h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">المنشئ الأصلي:</span>
                        <span class="font-bold text-slate-800">{{ $memo->creator->name ?? 'غير محدد' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">الدائرة التابع لها:</span>
                        <span class="font-bold text-slate-800">{{ $memo->creator->department->name ?? 'بلدية بيت حانون' }}</span>
                    </div>

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">العهدة الحالية (المستلم):</span>
                        <span class="font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md">
                            {{ $memo->assignee->name ?? 'غير محدد' }}
                        </span>
                    </div>

                    @if ($memo->targetDepartment)
                        <div class="flex items-center justify-between py-1 border-b border-gray-50">
                            <span class="text-tertiary">القسم الموجه إليه:</span>
                            <span class="font-bold text-slate-800">{{ $memo->targetDepartment->name }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-tertiary">تاريخ الإنشاء:</span>
                        <span class="font-semibold text-slate-700">{{ $memo->created_at->format('Y-m-d H:i') }}</span>
                    </div>

                    @if ($memo->due_date)
                        <div class="flex items-center justify-between py-1 border-b border-gray-50">
                            <span class="text-tertiary">الموعد النهائي للإنجاز:</span>
                            <span class="font-bold {{ $memo->due_date->isPast() && $memo->status !== 'تم الإنجاز' ? 'text-rose-600' : 'text-slate-800' }}">
                                {{ $memo->due_date->format('Y-m-d') }}
                            </span>
                        </div>
                    @endif

                    @if ($memo->linkedArchiveFile)
                        <div class="py-1">
                            <span class="text-tertiary block mb-1">ملف الأرشيف المرتبط:</span>
                            <a href="{{ route('archive.files.show', $memo->linkedArchiveFile->id) }}" target="_blank" class="font-bold text-primary hover:underline text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                {{ $memo->linkedArchiveFile->file_no }} - {{ $memo->linkedArchiveFile->file_name }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline Audit Trail Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        الخط الزمني ومسار التوجيهات
                    </h3>
                    <span class="text-[11px] text-tertiary">{{ $memo->movements->count() }} مراحل</span>
                </div>

                {{-- Vertical Timeline List --}}
                <div class="relative pl-2 pr-4 space-y-6 before:absolute before:right-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-teal-100">
                    @foreach ($memo->movements as $movement)
                        <div class="relative flex items-start gap-3">
                            {{-- Node Marker --}}
                            <div class="w-5 h-5 rounded-full bg-white border-2 border-primary flex items-center justify-center shrink-0 z-10 -mr-2.5 shadow-xs">
                                <div class="w-2 h-2 rounded-full bg-secondary"></div>
                            </div>

                            <div class="flex-1 bg-slate-50/70 p-3 rounded-xl border border-slate-100 space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-800">{{ $movement->action_type }}</span>
                                    <span class="text-[10px] text-tertiary" title="{{ $movement->created_at }}">
                                        {{ $movement->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="text-[11px] text-slate-600 flex items-center gap-1.5">
                                    <span class="font-semibold text-teal-800">{{ $movement->fromUser->name ?? 'غير محدد' }}</span>
                                    @if ($movement->toUser)
                                        <svg class="w-3 h-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        <span class="font-semibold text-slate-800">{{ $movement->toUser->name }}</span>
                                    @endif
                                </div>

                                @if ($movement->directive_text)
                                    <div class="text-xs text-slate-700 bg-white p-2.5 rounded-lg border border-gray-100 font-medium leading-relaxed mt-1">
                                        <div class="text-[10px] font-bold text-secondary mb-0.5">الشرح / التوجيه:</div>
                                        {{ $movement->directive_text }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- Modals --}}

    {{-- 1. Forward / Route Modal --}}
    <div id="forward-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                    تحويل المذكرة مع توجيه إداري
                </h3>
                <button type="button" onclick="document.getElementById('forward-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('correspondence.memos.forward', $memo->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تحويل إلى الموظف <span class="text-rose-500">*</span></label>
                    <select name="to_user_id" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-bold text-teal-800">
                        <option value="">اختر الموظف المحول إليه...</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->department->name ?? 'موظف' }} - {{ $user->job_title ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الشروحات والتوجيهات الإدارية <span class="text-rose-500">*</span></label>
                    <textarea name="directive_text"
                              rows="4"
                              required
                              placeholder="اكتب التوجيه أو الشرح الإداري المطلوب تنفيذه... (استخدم @اسم_الموظف للتكليف المباشر بمهمة)"
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">إرفاق مستندات إضافية مع التحويل (اختياري)</label>
                    <input type="file"
                           name="attachments[]"
                           multiple
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-teal-700 cursor-pointer">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('forward-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-secondary hover:bg-amber-700 shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                        </svg>
                        تأكيد التحويل الآن
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. Close Memo Modal --}}
    <div id="close-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    إنجاز وإغلاق المذكرة نهائياً
                </h3>
                <button type="button" onclick="document.getElementById('close-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('correspondence.memos.close', $memo->id) }}" class="space-y-4">
                @csrf
                <p class="text-xs text-tertiary">
                    عند إغلاق المذكرة ستعتبر في حالة "تم الإنجاز"، وسيتم إشعار منشئ المعاملة وكافة الأطراف بالاكتمال.
                </p>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات الإنجاز النهائية (اختياري)</label>
                    <textarea name="final_note"
                              rows="3"
                              placeholder="مثال: تم تدقيق المخططات وإصدار الترخيص اللازم وإغلاق الطلب بنجاح."
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('close-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        تأكيد الإنجاز والإغلاق
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. Add Attachment Modal --}}
    <div id="add-attachment-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    إرفاق مستند جديد للمذكرة
                </h3>
                <button type="button" onclick="document.getElementById('add-attachment-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('correspondence.memos.attachments.store', $memo->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">اختر الملف <span class="text-rose-500">*</span></label>
                    <input type="file"
                           name="attachment"
                           required
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-teal-700 cursor-pointer">
                    <p class="text-[11px] text-teal-700 mt-2 font-medium">سيتم أرشفة الملف تلقائياً ضمن ملف المذكرة في الأرشيف المركزي.</p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('add-attachment-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm flex items-center gap-1.5">
                        رفع وأرشفة الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-correspondence-layout>
