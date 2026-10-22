<x-correspondence-layout>
    {{-- Statistics Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Inbox Card --}}
        <a href="{{ route('correspondence.memos.index', ['tab' => 'inbox']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'inbox' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">صندوق الوارد (في عهدتي)</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['inbox'] }}</h3>
                    <span class="text-[11px] text-teal-600 font-bold">تتطلب إجراءً ومتابعة</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary" style="width: {{ min(100, $counts['inbox'] * 15) }}%"></div>
            </div>
        </a>

        {{-- Outbox Card --}}
        <a href="{{ route('correspondence.memos.index', ['tab' => 'outbox']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'outbox' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">صندوق الصادر</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['outbox'] }}</h3>
                    <span class="text-[11px] text-amber-600 font-bold">مذكرات أنشأتها وتتنقل</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-secondary" style="width: {{ min(100, $counts['outbox'] * 10) }}%"></div>
            </div>
        </a>

        {{-- Completed Card --}}
        <a href="{{ route('correspondence.memos.index', ['tab' => 'completed']) }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group {{ $tab === 'completed' ? 'ring-2 ring-primary border-transparent' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">المذكرات المنجزة</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['completed'] }}</h3>
                    <span class="text-[11px] text-emerald-600 font-bold">أغلقت بعد اكتمال المسار</span>
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

        {{-- Tasks Card --}}
        <a href="{{ route('correspondence.tasks.index') }}"
           class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm transition-all duration-200 hover:shadow-md hover:border-primary/40 relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-tertiary">مهامي المفتوحة</span>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $counts['my_tasks'] }}</h3>
                    <span class="text-[11px] text-rose-600 font-bold">تكليفات بانتظار الإنجاز</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500" style="width: {{ min(100, $counts['my_tasks'] * 20) }}%"></div>
            </div>
        </a>
    </div>

    {{-- Filter, Search, and Tabs Section --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-4">
        {{-- Internal Tabs Navigation --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('correspondence.memos.index', ['tab' => 'inbox']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'inbox' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>صندوق الوارد</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'inbox' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['inbox'] }}</span>
                </a>

                <a href="{{ route('correspondence.memos.index', ['tab' => 'outbox']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'outbox' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>صندوق الصادر</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'outbox' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['outbox'] }}</span>
                </a>

                <a href="{{ route('correspondence.memos.index', ['tab' => 'completed']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'completed' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>المذكرات المنجزة</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'completed' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['completed'] }}</span>
                </a>

                <a href="{{ route('correspondence.memos.index', ['tab' => 'all']) }}"
                   class="px-3.5 py-1.5 rounded-lg text-sm font-bold transition-all duration-200 flex items-center gap-2
                          {{ $tab === 'all' ? 'bg-primary text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span>سجل المراسلات الشامل</span>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $tab === 'all' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $counts['all'] }}</span>
                </a>
            </div>

            <div class="text-xs text-tertiary font-medium">
                عرض المذكرات: {{ $memos->total() }} مذكرة
            </div>
        </div>

        {{-- Search & Filters Form --}}
        <form method="GET" action="{{ route('correspondence.memos.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <input type="hidden" name="tab" value="{{ $tab }}">

            {{-- Keyword Search --}}
            <div class="md:col-span-5 relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="ابحث برقم المذكرة، الموضوع، أو نص المحتوى..."
                       class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Priority Filter --}}
            <div class="md:col-span-3">
                <select name="priority" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    <option value="">جميع الأولويات</option>
                    <option value="عادية" {{ request('priority') == 'عادية' ? 'selected' : '' }}>عادية</option>
                    <option value="متوسطة" {{ request('priority') == 'متوسطة' ? 'selected' : '' }}>متوسطة</option>
                    <option value="عاجلة" {{ request('priority') == 'عاجلة' ? 'selected' : '' }}>عاجلة</option>
                    <option value="سري / عاجل جداً" {{ request('priority') == 'سري / عاجل جداً' ? 'selected' : '' }}>سري / عاجل جداً</option>
                </select>
            </div>

            {{-- Department Filter --}}
            <div class="md:col-span-3">
                <select name="department_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary">
                    <option value="">جميع الدوائر والأقسام</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="md:col-span-1 flex items-center gap-1">
                <button type="submit" class="w-full bg-primary hover:bg-teal-700 text-white p-2.5 rounded-xl font-bold transition-colors flex items-center justify-center shadow-xs" title="بحث">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                @if (request()->hasAny(['search', 'priority', 'department_id']))
                    <a href="{{ route('correspondence.memos.index', ['tab' => $tab]) }}" class="bg-gray-100 hover:bg-gray-200 text-slate-600 p-2.5 rounded-xl transition-colors" title="إلغاء التصفية">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Memos List Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($memos->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100 text-tertiary text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-4">رقم المذكرة</th>
                            <th class="py-3.5 px-4">الموضوع والمحتوى</th>
                            <th class="py-3.5 px-4">المرسل (المنشئ)</th>
                            <th class="py-3.5 px-4">العهدة الحالية</th>
                            <th class="py-3.5 px-4 text-center">الأولوية</th>
                            <th class="py-3.5 px-4 text-center">الحالة</th>
                            <th class="py-3.5 px-4 text-center">المرفقات</th>
                            <th class="py-3.5 px-4 text-center">التاريخ</th>
                            <th class="py-3.5 px-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($memos as $memo)
                            <tr class="hover:bg-teal-50/40 transition-colors duration-150">
                                {{-- Memo Number --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <a href="{{ route('correspondence.memos.show', $memo->id) }}" class="font-bold text-primary hover:underline flex items-center gap-1.5">
                                        <div class="w-2 h-2 rounded-full {{ $memo->status === 'تم الإنجاز' ? 'bg-emerald-500' : 'bg-primary' }}"></div>
                                        <span>{{ $memo->memo_number }}</span>
                                    </a>
                                </td>

                                {{-- Subject & Excerpt --}}
                                <td class="py-4 px-4 max-w-xs">
                                    <a href="{{ route('correspondence.memos.show', $memo->id) }}" class="font-bold text-slate-800 hover:text-primary transition-colors block">
                                        {{ $memo->subject }}
                                    </a>
                                    <p class="text-xs text-tertiary truncate mt-0.5">
                                        {{ Str::limit(strip_tags($memo->content), 80) }}
                                    </p>
                                    @if ($memo->targetDepartment)
                                        <span class="inline-block mt-1 text-[11px] text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md font-medium">
                                            {{ $memo->targetDepartment->name }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Creator --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ mb_substr($memo->creator->name ?? 'م', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-800 text-xs">{{ $memo->creator->name ?? 'غير محدد' }}</div>
                                            <div class="text-[11px] text-tertiary">{{ $memo->creator->job_title ?? 'موظف' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Current Assignee --}}
                                <td class="py-4 px-4 whitespace-nowrap">
                                    @if ($memo->assignee)
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-amber-50 text-secondary flex items-center justify-center font-bold text-xs shrink-0">
                                                {{ mb_substr($memo->assignee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 text-xs">{{ $memo->assignee->name }}</div>
                                                <div class="text-[11px] text-tertiary">{{ $memo->assignee->department->name ?? 'موظف' }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">غير محدد</span>
                                    @endif
                                </td>

                                {{-- Priority --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $memo->priority_badge_class }}">
                                        {{ $memo->priority }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $memo->status_badge_class }}">
                                        {{ $memo->status }}
                                    </span>
                                </td>

                                {{-- Attachments Count --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if ($memo->attachments->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-secondary bg-amber-50 px-2 py-0.5 rounded-md">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            {{ $memo->attachments->count() }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-300">-</span>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <div class="text-xs font-semibold text-slate-700">{{ $memo->created_at->format('Y-m-d') }}</div>
                                    <div class="text-[11px] text-tertiary">{{ $memo->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Actions --}}
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <a href="{{ route('correspondence.memos.show', $memo->id) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all shadow-xs">
                                        <span>عرض وتوجيه</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-100">
                {{ $memos->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 text-secondary mx-auto flex items-center justify-center mb-3 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-700 text-base">لا توجد مذكرات داخلية في هذا الصندوق حالياً</h4>
                <p class="text-xs text-tertiary mt-1 max-w-sm mx-auto">يمكنك البدء بإنشاء مذكرة داخلية جديدة وتوجيهها للموظف أو القسم المختص.</p>
                <div class="mt-4">
                    <a href="{{ route('correspondence.memos.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        إنشاء أول مذكرة
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-correspondence-layout>
