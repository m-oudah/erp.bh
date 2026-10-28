<x-correspondence-layout>
    {{-- Statistics Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- My Tasks --}}
        <a href="{{ route('correspondence.tasks.index', ['tab' => 'assigned_to_me']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'assigned_to_me' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">مهامي الموكلة إليّ</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['assigned_to_me'] }}</h3>
                    <span class="text-[11px] text-teal-600 font-bold">مهام وتكليفات تتطلب إنجازك</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary" style="width: {{ min(100, $counts['assigned_to_me'] * 20) }}%"></div>
            </div>
        </a>

        {{-- Tasks I Assigned --}}
        <a href="{{ route('correspondence.tasks.index', ['tab' => 'assigned_by_me']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'assigned_by_me' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">مهام كلفتُ بها الآخرين</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['assigned_by_me'] }}</h3>
                    <span class="text-[11px] text-amber-600 font-bold">لمتابعة نسب تنفيذ الموظفين</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-secondary" style="width: {{ min(100, $counts['assigned_by_me'] * 15) }}%"></div>
            </div>
        </a>

        {{-- Completed Tasks --}}
        <a href="{{ route('correspondence.tasks.index', ['tab' => 'completed']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'completed' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">المهام المنجزة</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['completed'] }}</h3>
                    <span class="text-[11px] text-emerald-600 font-bold">مهام اكتمل تنفيذها بنجاح</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500" style="width: {{ min(100, $counts['completed'] * 10) }}%"></div>
            </div>
        </a>
    </div>

    {{-- Filter, Search, and Action Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('correspondence.tasks.index', ['tab' => 'assigned_to_me']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'assigned_to_me' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>مهامي الموكلة إليّ</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'assigned_to_me' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['assigned_to_me'] }}</span>
                </a>

                <a href="{{ route('correspondence.tasks.index', ['tab' => 'assigned_by_me']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'assigned_by_me' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>مهام كلفتُ بها الآخرين</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'assigned_by_me' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['assigned_by_me'] }}</span>
                </a>

                <a href="{{ route('correspondence.tasks.index', ['tab' => 'completed']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'completed' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>المهام المنجزة</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['completed'] }}</span>
                </a>
            </div>

            <button type="button"
                    onclick="document.getElementById('new-task-modal').classList.remove('hidden')"
                    class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-xs transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>تكليف بمهمة جديدة</span>
            </button>
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('correspondence.tasks.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">

            <div class="md:col-span-6 relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="ابحث بعنوان المهمة أو تفاصيلها..."
                       class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="md:col-span-3">
                <select name="priority" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    <option value="">جميع الأولويات</option>
                    <option value="عادية" {{ request('priority') == 'عادية' ? 'selected' : '' }}>عادية</option>
                    <option value="متوسطة" {{ request('priority') == 'متوسطة' ? 'selected' : '' }}>متوسطة</option>
                    <option value="عاجلة" {{ request('priority') == 'عاجلة' ? 'selected' : '' }}>عاجلة</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <select name="status" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    <option value="">جميع الحالات</option>
                    <option value="جديدة" {{ request('status') == 'جديدة' ? 'selected' : '' }}>جديدة</option>
                    <option value="قيد التنفيذ" {{ request('status') == 'قيد التنفيذ' ? 'selected' : '' }}>قيد التنفيذ</option>
                    <option value="بانتظار الرد" {{ request('status') == 'بانتظار الرد' ? 'selected' : '' }}>بانتظار الرد</option>
                    <option value="معلقة" {{ request('status') == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="submit" class="w-full bg-primary hover:bg-teal-700 text-white p-2.5 rounded-xl font-bold transition-colors flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Tasks Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($tasks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100 text-tertiary text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">عنوان المهمة والتفاصيل</th>
                            <th class="py-3.5 px-4">المكلِّف (المرسل)</th>
                            <th class="py-3.5 px-4">المكلَّف بالتنفيذ</th>
                            <th class="py-3.5 px-4">المراسلة المصدر</th>
                            <th class="py-3.5 px-4 text-center">الأولوية</th>
                            <th class="py-3.5 px-4 text-center">الحالة</th>
                            <th class="py-3.5 px-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($tasks as $task)
                            <tr class="hover:bg-teal-50/40 transition-colors duration-150">
                                {{-- Title & Excerpt --}}
                                <td class="py-4 px-4 max-w-sm">
                                    <a href="{{ route('correspondence.tasks.show', $task->id) }}" class="font-bold text-slate-800 hover:text-primary transition-colors block">
                                        {{ $task->title }}
                                    </a>
                                    @if ($task->description)
                                        <p class="text-xs text-tertiary truncate mt-0.5">
                                            {{ Str::limit($task->description, 70) }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Creator --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="text-xs font-semibold text-slate-800">{{ $task->creator->name ?? 'غير محدد' }}</div>
                                    <div class="text-[11px] text-tertiary">{{ $task->creator->department->name ?? 'موظف' }}</div>
                                </td>

                                {{-- Assignee --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="text-xs font-bold text-teal-800">{{ $task->assignee->name ?? 'غير محدد' }}</div>
                                    <div class="text-[11px] text-tertiary">{{ $task->assignee->job_title ?? 'موظف' }}</div>
                                </td>

                                {{-- Linked Memo --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($task->memo)
                                        <a href="{{ route('correspondence.memos.show', $task->memo->id) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $task->memo->memo_number }}
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">مهمة مباشرة</span>
                                    @endif
                                </td>

                                {{-- Priority --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $task->priority_badge_class }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $task->status_badge_class }}">
                                        {{ $task->status }}
                                    </span>
                                </td>

                                {{-- Action --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <a href="{{ route('correspondence.tasks.show', $task->id) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-50 text-primary hover:bg-primary hover:text-white transition-all shadow-xs"
                                       title="عرض تفاصيل المهمة">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-secondary mx-auto flex items-center justify-center mb-3 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-700 text-base">لا توجد مهام داخلية في هذا القسم حالياً</h4>
                <p class="text-xs text-tertiary mt-1 max-w-sm mx-auto">يمكنك تكليف أحد الموظفين بمهمة مباشرة أو من خلال الإشارات في المذكرات الداخلية.</p>
                <div class="mt-4">
                    <button type="button" onclick="document.getElementById('new-task-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        تكليف بمهمة جديدة الآن
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Create Task Modal --}}
    <div id="new-task-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 animate-scale-up space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                    تكليف بمهمة داخلية جديدة
                </h3>
                <button type="button" onclick="document.getElementById('new-task-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('correspondence.tasks.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">عنوان المهمة <span class="text-rose-500">*</span></label>
                    <input type="text"
                           name="title"
                           required
                           placeholder="مثال: إعداد تقرير مالي لنفقات الصيانة عن شهر أكتوبر..."
                           class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الموظف المكلَّف بالتنفيذ <span class="text-rose-500">*</span></label>
                        <select name="assigned_to" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-bold text-teal-800">
                            <option value="">اختر الموظف...</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->job_title ?? 'موظف' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الأولوية <span class="text-rose-500">*</span></label>
                        <select name="priority" required class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                            <option value="عادية">عادية</option>
                            <option value="متوسطة">متوسطة</option>
                            <option value="عاجلة">عاجلة</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">موعد التسليم النهائي</label>
                        <input type="date"
                               name="due_date"
                               class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ربط بمراسلة داخلية (اختياري)</label>
                        <select name="memo_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                            <option value="">مهمة مستقلة</option>
                            @foreach ($memos as $m)
                                <option value="{{ $m->id }}">{{ $m->memo_number }} - {{ Str::limit($m->subject, 30) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المطلوب تنفيذه بالتفصيل</label>
                    <textarea name="description"
                              rows="3"
                              placeholder="حدد الإجراءات المطلوبة والتوجيهات بدقة للموظف المنفذ..."
                              class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('new-task-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm flex items-center gap-1.5">
                        تكليف وإرسال المهمة
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-correspondence-layout>
