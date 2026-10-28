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
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                        {{ $memo->memo_type }}
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
                   title="عرض ملف المراسلة في الأرشيف المركزي">
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
                <a href="{{ route('correspondence.memos.forward-page', $memo->id) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-secondary hover:bg-amber-700 shadow-xs transition-all flex items-center gap-1.5"
                   title="تحويل المراسلة مع توجيه إداري في صفحة جديدة">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                    <span>تحويل مع توجيه إداري</span>
                </a>

                {{-- Complete / Close Button --}}
                <button type="button"
                        onclick="document.getElementById('close-modal').classList.remove('hidden')"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-xs transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>إنجاز وإغلاق المراسلة</span>
                </button>
            @else
                <div class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>تم إنجاز المراسلة وإغلاقها</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Main Layout: 2 Columns (Details & Timeline) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- Right / Main Content Column (8 cols): Content & Timeline --}}
        <div class="lg:col-span-8 space-y-5">
            {{-- Memo Content Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="text-xs font-bold text-tertiary uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        محتوى وتفاصيل المراسلة
                    </span>
                    <span class="text-xs text-tertiary">
                        أنشئت في: {{ $memo->created_at->format('Y-m-d H:i') }}
                    </span>
                </div>

                <div class="text-base leading-relaxed text-slate-800 whitespace-pre-line font-medium bg-slate-50/60 p-5 rounded-xl border border-slate-100">
                    {{ $memo->content }}
                </div>
            </div>

            {{-- Timeline Audit Trail Card (الخط الزمني ومسار التوجيهات - الآن مكان المرفقات) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-black text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>الخط الزمني ومسار التوجيهات</span>
                    </h3>
                    <span class="text-xs text-tertiary font-bold bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                        @php
                            $groupedMovements = [];
                            foreach ($memo->movements as $m) {
                                $lastIdx = count($groupedMovements) - 1;
                                if (
                                    $lastIdx >= 0 &&
                                    $groupedMovements[$lastIdx]['action_type'] === $m->action_type &&
                                    $groupedMovements[$lastIdx]['from_user_id'] === $m->from_user_id &&
                                    $groupedMovements[$lastIdx]['directive_text'] === $m->directive_text &&
                                    abs(strtotime((string)$groupedMovements[$lastIdx]['created_at']) - strtotime((string)$m->created_at)) <= 30
                                ) {
                                    if ($m->toUser && !in_array($m->toUser->id, array_map(fn($u) => $u->id, $groupedMovements[$lastIdx]['recipients']))) {
                                        $groupedMovements[$lastIdx]['recipients'][] = $m->toUser;
                                    }
                                } else {
                                    $initialRecipients = [];
                                    if ($m->action_type === 'إنشاء المراسلة' && $memo->recipients->isNotEmpty()) {
                                        $initialRecipients = $memo->recipients->all();
                                    } elseif ($m->toUser) {
                                        $initialRecipients = [$m->toUser];
                                    }

                                    $groupedMovements[] = [
                                        'movement' => $m,
                                        'action_type' => $m->action_type,
                                        'from_user_id' => $m->from_user_id,
                                        'fromUser' => $m->fromUser,
                                        'directive_text' => $m->directive_text,
                                        'created_at' => $m->created_at,
                                        'recipients' => $initialRecipients,
                                    ];
                                }
                            }
                        @endphp
                        {{ count($groupedMovements) }} مراحل
                    </span>
                </div>

                {{-- Vertical Timeline List --}}
                <div class="relative pl-2 pr-5 space-y-6 before:absolute before:right-2.5 before:top-3 before:bottom-3 before:w-0.5 before:bg-teal-200">
                    @foreach ($groupedMovements as $item)
                        @php
                            $movement = $item['movement'];
                        @endphp
                        <div class="relative flex items-start gap-4">
                            {{-- Node Marker --}}
                            <div class="w-6 h-6 rounded-full bg-white border-2 border-primary flex items-center justify-center shrink-0 z-10 -mr-3 shadow-xs">
                                <div class="w-2.5 h-2.5 rounded-full bg-secondary"></div>
                            </div>

                            <div class="flex-1 bg-slate-50/80 p-4 sm:p-5 rounded-2xl border border-slate-100 shadow-2xs space-y-3">
                                {{-- Stage Action & Time --}}
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200/60 pb-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                                        <span class="font-extrabold text-sm sm:text-base text-slate-900">{{ $item['action_type'] }}</span>
                                    </div>
                                    <span class="text-xs text-slate-500 font-semibold" title="{{ $movement->created_at }}">
                                        {{ $movement->created_at->diffForHumans() }} ({{ $movement->created_at->format('Y-m-d H:i') }})
                                    </span>
                                </div>

                                {{-- Users (From -> To) with Enlarged Bold Fonts --}}
                                <div class="flex flex-wrap items-center gap-2 text-sm sm:text-base">
                                    <span class="text-xs text-slate-500 font-bold">بواسطة:</span>
                                    <span class="font-extrabold text-teal-900 bg-teal-50 px-3 py-1 rounded-xl border border-teal-100 shadow-2xs flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $item['fromUser']->name ?? 'غير محدد' }}</span>
                                    </span>

                                    @if (!empty($item['recipients']))
                                        <div class="flex items-center gap-1 text-secondary font-bold text-xs sm:text-sm mx-1">
                                            <span>إلى</span>
                                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @foreach ($item['recipients'] as $rec)
                                                <span class="font-extrabold text-slate-900 bg-amber-50 px-3 py-1 rounded-xl border border-amber-200/70 shadow-2xs flex items-center gap-1.5">
                                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span>{{ $rec->name }}</span>
                                                    @if ($rec->department)
                                                        <span class="text-[11px] font-semibold text-amber-800 opacity-80">({{ $rec->department->name }})</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Directive Text (الشرح والتوجيه الإداري والتفاصيل تحتها) with Enlarged Fonts --}}
                                @if ($item['directive_text'])
                                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1.5">
                                        <div class="text-xs sm:text-sm font-black text-secondary flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            <span>الشرح والتوجيه الإداري:</span>
                                        </div>
                                        <div class="text-sm sm:text-base font-semibold text-slate-800 leading-relaxed whitespace-pre-line pt-0.5">
                                            {{ $item['directive_text'] }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Left / Sidebar Column (4 cols): Information, Tasks & Attachments --}}
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

                    <div class="py-2 border-b border-gray-50 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <span class="text-tertiary">المستلمون (الموجهة إليهم):</span>
                            <span class="text-[11px] font-bold text-teal-800 bg-teal-50 px-2 py-0.2 rounded-full border border-teal-100">
                                {{ $memo->recipients->isNotEmpty() ? $memo->recipients->count() : ($memo->assignee ? 1 : 0) }} مستلم
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-1.5 pt-0.5">
                            @if($memo->recipients->isNotEmpty())
                                @foreach($memo->recipients as $rec)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-900 border border-teal-200/70 shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600 shrink-0"></span>
                                        <span>{{ $rec->name }}</span>
                                        @if($rec->department)
                                            <span class="text-[10px] text-teal-700 opacity-75">({{ $rec->department->name }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            @elseif($memo->assignee)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-900 border border-teal-200/70 shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-600 shrink-0"></span>
                                    <span>{{ $memo->assignee->name }}</span>
                                    @if($memo->assignee->department)
                                        <span class="text-[10px] text-teal-700 opacity-75">({{ $memo->assignee->department->name }})</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-slate-400 font-semibold">غير محدد</span>
                            @endif
                        </div>
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

            {{-- Linked Tasks Section (المهام والتكليفات المرتبطة بالمراسلة) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3.5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        المهام والتكليفات المرتبطة ({{ $memo->tasks->count() }})
                    </h3>
                    <a href="{{ route('correspondence.tasks.index') }}" class="text-[11px] font-bold text-primary hover:underline">
                        لوحة المهام
                    </a>
                </div>

                @if ($memo->tasks->count() > 0)
                    <div class="space-y-2">
                        @foreach ($memo->tasks as $task)
                            <a href="{{ route('correspondence.tasks.show', $task->id) }}"
                               class="p-3 rounded-xl border border-gray-100 bg-slate-50/50 hover:bg-teal-50/30 hover:border-primary/30 transition-all block group">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-1">
                                        {{ $task->title }}
                                    </h4>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold shrink-0 {{ $task->status_badge_class }}">
                                        {{ $task->status }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 text-[10px] text-tertiary mt-1.5 flex-wrap">
                                    <span>المكلف: <b class="text-slate-700">{{ $task->assignee->name ?? 'غير محدد' }}</b></span>
                                    @if ($task->due_date)
                                        <span>•</span>
                                        <span class="{{ $task->is_overdue ? 'text-rose-600 font-bold' : '' }}">موعد: {{ $task->due_date->format('m/d') }}</span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-tertiary text-center py-3 bg-gray-50/50 rounded-xl">لا توجد مهام منشأة أو مستخرجة عبر @mentions لهذه المراسلة.</p>
                @endif
            </div>

            {{-- Attachments Section (المرفقات والمستندات المؤرشفة) --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3.5">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        المرفقات والمستندات ({{ $memo->attachments->count() }})
                    </h3>
                    @if ($memo->status !== 'تم الإنجاز')
                        <button type="button"
                                onclick="document.getElementById('add-attachment-modal').classList.remove('hidden')"
                                class="text-[11px] font-bold text-primary hover:text-teal-700 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>إضافة</span>
                        </button>
                    @endif
                </div>

                @if ($memo->autoArchiveFile)
                    <div class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-emerald-100">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>مؤرشفة آلياً في الأرشيف المركزي</span>
                    </div>
                @endif

                @if ($memo->attachments->count() > 0)
                    <div class="space-y-2">
                        @foreach ($memo->attachments as $attachment)
                            <div class="p-2.5 rounded-xl border border-gray-100 bg-slate-50/60 hover:bg-slate-50 hover:border-primary/30 transition-all flex items-center justify-between gap-2 group">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-secondary flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-bold text-slate-800 truncate" title="{{ $attachment->file_name }}">
                                            {{ $attachment->file_name }}
                                        </h4>
                                        <div class="text-[10px] text-tertiary">
                                            <span>{{ $attachment->formatted_size }}</span> • <span>{{ $attachment->uploader->name ?? 'موظف' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    <a href="{{ route('correspondence.memos.attachments.download', $attachment->id) }}"
                                       class="p-1.5 rounded-lg bg-white border border-gray-200 text-slate-700 hover:text-primary hover:border-primary shadow-xs transition-colors inline-flex items-center justify-center"
                                       title="تحميل الملف">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-tertiary text-center py-3 bg-gray-50/50 rounded-xl">لا توجد مرفقات مع هذه المراسلة حتى الآن.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Modals --}}

    {{-- 2. Close Memo Modal --}}
    <div id="close-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100 animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    إنجاز وإغلاق المراسلة نهائياً
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
                    عند إغلاق المراسلة ستعتبر في حالة "تم الإنجاز"، وسيتم إشعار منشئ المعاملة وكافة الأطراف بالاكتمال.
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
                    إرفاق مستند جديد للمراسلة
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
                    <p class="text-[11px] text-teal-700 mt-2 font-medium">سيتم أرشفة الملف تلقائياً ضمن ملف المراسلة في الأرشيف المركزي.</p>
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
