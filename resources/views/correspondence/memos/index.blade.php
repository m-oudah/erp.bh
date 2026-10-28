<x-correspondence-layout>
    {{-- Unified Interactive Filter Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        {{-- 1. Inbox (في عهدتي) --}}
        <a href="{{ route('correspondence.memos.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'inbox'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $tab === 'inbox'
                     ? 'bg-gradient-to-br from-teal-50/90 to-white border-primary shadow-sm ring-2 ring-primary/20'
                     : 'bg-white border-gray-100 hover:border-teal-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $tab === 'inbox' ? 'text-teal-900' : 'text-slate-500' }}">صندوق الوارد (في عهدتي)</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $tab === 'inbox' ? 'bg-primary text-white shadow-xs' : 'bg-teal-50 text-primary group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['inbox'] }}</div>
                <span class="text-[11px] font-semibold {{ $tab === 'inbox' ? 'text-teal-700' : 'text-slate-400' }}">تتطلب إجراءً ومتابعة</span>
            </div>
            @if($tab === 'inbox')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            @endif
        </a>

        {{-- 2. Outbox (الصادر) --}}
        <a href="{{ route('correspondence.memos.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'outbox'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $tab === 'outbox'
                     ? 'bg-gradient-to-br from-amber-50/90 to-white border-secondary shadow-sm ring-2 ring-secondary/20'
                     : 'bg-white border-gray-100 hover:border-amber-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $tab === 'outbox' ? 'text-amber-900' : 'text-slate-500' }}">صندوق الصادر</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $tab === 'outbox' ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary group-hover:scale-105 transition-transform' }}" style="{{ $tab === 'outbox' ? 'background-color: rgb(217 119 6);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['outbox'] }}</div>
                <span class="text-[11px] font-semibold {{ $tab === 'outbox' ? 'text-amber-700' : 'text-slate-400' }}">مراسلات أنشأتها وتتنقل</span>
            </div>
            @if($tab === 'outbox')
                <div class="absolute bottom-0 left-0 right-0 h-1" style="background-color: rgb(217 119 6);"></div>
            @endif
        </a>

        {{-- 3. Completed (المنجزة) --}}
        <a href="{{ route('correspondence.memos.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'completed'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $tab === 'completed'
                     ? 'bg-gradient-to-br from-emerald-50/90 to-white border-emerald-500 shadow-sm ring-2 ring-emerald-500/20'
                     : 'bg-white border-gray-100 hover:border-emerald-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $tab === 'completed' ? 'text-emerald-900' : 'text-slate-500' }}">المراسلات المنجزة</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $tab === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['completed'] }}</div>
                <span class="text-[11px] font-semibold {{ $tab === 'completed' ? 'text-emerald-700' : 'text-slate-400' }}">أغلقت بعد اكتمال المسار</span>
            </div>
            @if($tab === 'completed')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
            @endif
        </a>

        {{-- 4. All Memos (السجل الشامل) --}}
        <a href="{{ route('correspondence.memos.index', array_merge(request()->except(['tab', 'page']), ['tab' => 'all'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $tab === 'all'
                     ? 'bg-gradient-to-br from-slate-100 to-white border-slate-500 shadow-sm ring-2 ring-slate-500/20'
                     : 'bg-white border-gray-100 hover:border-slate-300 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $tab === 'all' ? 'text-slate-900' : 'text-slate-500' }}">السجل الشامل</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $tab === 'all' ? 'bg-slate-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['all'] }}</div>
                <span class="text-[11px] font-semibold {{ $tab === 'all' ? 'text-slate-700' : 'text-slate-400' }}">كافة المراسلات المتاحة</span>
            </div>
            @if($tab === 'all')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-600"></div>
            @endif
        </a>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5">
        <form method="GET" action="{{ route('correspondence.memos.index') }}" class="flex flex-wrap items-center gap-2.5">
            <input type="hidden" name="tab" value="{{ $tab }}">

            {{-- Smart Search Box --}}
            <div class="flex-1 min-w-[240px] relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="ابحث برقم المراسلة، الموضوع، أو نص المحتوى..."
                       class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400 py-2">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Memo Type Filter --}}
            <div class="w-auto min-w-[130px]">
                <select name="memo_type" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                    <option value="">كل أنواع المراسلات</option>
                    <option value="مذكرة داخلية" {{ request('memo_type') == 'مذكرة داخلية' ? 'selected' : '' }}>📋 مذكرة داخلية</option>
                    <option value="تقرير عمل" {{ request('memo_type') == 'تقرير عمل' ? 'selected' : '' }}>📊 تقرير عمل</option>
                    <option value="طلب صرف" {{ request('memo_type') == 'طلب صرف' ? 'selected' : '' }}>💰 طلب صرف</option>
                    <option value="تقرير مالي" {{ request('memo_type') == 'تقرير مالي' ? 'selected' : '' }}>📈 تقرير مالي</option>
                    <option value="استفسار إداري" {{ request('memo_type') == 'استفسار إداري' ? 'selected' : '' }}>❓ استفسار إداري</option>
                    <option value="إشعار إداري" {{ request('memo_type') == 'إشعار إداري' ? 'selected' : '' }}>📢 إشعار إداري</option>
                    <option value="طلب إجازة / مغادرة" {{ request('memo_type') == 'طلب إجازة / مغادرة' ? 'selected' : '' }}>🏖️ طلب إجازة / مغادرة</option>
                    <option value="أخرى" {{ request('memo_type') == 'أخرى' ? 'selected' : '' }}>📝 أخرى</option>
                </select>
            </div>


            {{-- Department Filter --}}
            <div class="w-auto min-w-[150px]">
                <select name="department_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2">
                    <option value="">جميع الدوائر والأقسام</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit & Reset --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="submit" class="bg-primary hover:bg-teal-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 shadow-xs">
                    <span>بحث</span>
                </button>
                @if (request()->hasAny(['search', 'department_id', 'memo_type']))
                    <a href="{{ route('correspondence.memos.index', ['tab' => $tab]) }}" class="bg-gray-100 hover:bg-gray-200 text-slate-600 px-2.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1" title="مسح التصفية">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>مسح</span>
                    </a>
                @endif
            </div>

            {{-- Total results count badge --}}
            <div class="mr-auto text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1.5 rounded-lg shrink-0">
                المعروض: <span class="text-teal-700 font-extrabold">{{ $memos->total() }}</span> مراسلة
            </div>
        </form>
    </div>

    {{-- Memos List Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($memos->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-3 px-4">رقم ونوع المراسلة</th>
                            <th class="py-3 px-4">الموضوع ومقتطف المحتوى</th>
                            <th class="py-3 px-4">مسار المعاملة (المنشئ ⬅ العهدة)</th>
                            <th class="py-3 px-4 text-center">الحالة</th>
                            <th class="py-3 px-4 text-center">المرفقات</th>
                            <th class="py-3 px-4 text-center">التاريخ</th>
                            <th class="py-3 px-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($memos as $memo)
                            @php
                                $isMyCustody = $memo->current_assignee_id === auth()->id() || ($memo->relationLoaded('recipients') && $memo->recipients->contains('id', auth()->id()));
                            @endphp
                            <tr class="transition-colors duration-150 {{ $isMyCustody && $memo->status !== 'تم الإنجاز' ? 'bg-amber-50/30 hover:bg-amber-50/60' : 'hover:bg-slate-50/80' }}">
                                {{-- Memo Number & Type --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-2 h-2 rounded-full {{ $memo->status === 'تم الإنجاز' ? 'bg-emerald-500' : ($isMyCustody ? 'bg-amber-500 ring-2 ring-amber-200' : 'bg-primary') }}"></div>
                                        <a href="{{ route('correspondence.memos.show', $memo->id) }}" class="font-mono font-bold text-teal-800 hover:text-teal-900 hover:underline">
                                            {{ $memo->memo_number }}
                                        </a>
                                    </div>
                                    <div class="mt-1 flex items-center gap-1">
                                        <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                            {{ $memo->memo_type }}
                                        </span>
                                        @if($isMyCustody && $memo->status !== 'تم الإنجاز')
                                            <span class="inline-block bg-amber-100 text-amber-800 text-[9px] font-bold px-1.5 py-0.5 rounded">في عهدتك</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Subject & Excerpt --}}
                                <td class="py-3.5 px-4 max-w-sm">
                                    <a href="{{ route('correspondence.memos.show', $memo->id) }}" class="font-bold text-slate-800 hover:text-primary transition-colors block text-xs">
                                        {{ $memo->subject }}
                                    </a>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5 max-w-xs">
                                        {{ Str::limit(strip_tags($memo->content), 75) }}
                                    </p>
                                    @if ($memo->targetDepartment)
                                        <span class="inline-block mt-1 text-[10px] text-teal-700 bg-teal-50 px-2 py-0.2 rounded font-medium border border-teal-100/60">
                                            {{ $memo->targetDepartment->name }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Workflow (Creator ⬅ Recipients) --}}
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5 text-slate-700">
                                            <span class="text-[10px] text-slate-400 font-medium">من:</span>
                                            <span class="font-semibold text-xs">{{ $memo->creator->name ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-slate-800">
                                            <span class="text-[10px] text-slate-400 font-medium">إلى:</span>
                                            @if ($memo->recipients->isNotEmpty())
                                                @if ($memo->recipients->count() === 1)
                                                    <span class="font-bold text-xs {{ $isMyCustody ? 'text-amber-800' : 'text-slate-800' }}">
                                                        {{ $memo->recipients->first()->name }}
                                                    </span>
                                                @else
                                                    <div class="flex items-center gap-1" title="{{ $memo->recipients->pluck('name')->implode('، ') }}">
                                                        <span class="font-bold text-xs text-slate-800">{{ $memo->recipients->first()->name }}</span>
                                                        <span class="text-[10px] bg-teal-100 text-teal-800 font-black px-1.5 py-0.2 rounded-full cursor-help">
                                                            +{{ $memo->recipients->count() - 1 }}
                                                        </span>
                                                    </div>
                                                @endif
                                            @elseif ($memo->assignee)
                                                <span class="font-bold text-xs {{ $isMyCustody ? 'text-amber-800' : 'text-slate-800' }}">
                                                    {{ $memo->assignee->name }}
                                                </span>
                                            @else
                                                <span class="text-xs text-slate-400">غير محدد</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-bold {{ $memo->status_badge_class }}">
                                        {{ $memo->status }}
                                    </span>
                                </td>

                                {{-- Attachments Count --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    @if ($memo->attachments->count() > 0)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/60">
                                            <svg class="w-3 h-3 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            {{ $memo->attachments->count() }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <div class="font-semibold text-slate-700 text-xs">{{ $memo->created_at->format('Y-m-d') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $memo->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Actions --}}
                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                    <a href="{{ route('correspondence.memos.show', $memo->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-xs
                                              {{ $isMyCustody && $memo->status !== 'تم الإنجاز'
                                                 ? 'bg-primary text-white hover:bg-teal-700'
                                                 : 'bg-slate-100 text-slate-700 hover:bg-primary hover:text-white' }}">
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
            <div class="p-3.5 border-t border-gray-100">
                {{ $memos->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-secondary mx-auto flex items-center justify-center mb-3 shadow-xs">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h4 class="font-bold text-slate-700 text-sm">لا توجد مراسلات داخلية في هذا الصندوق حالياً</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">يمكنك البدء بإنشاء مراسلة داخلية جديدة وتوجيهها للموظف أو القسم المختص.</p>
                <div class="mt-4">
                    <a href="{{ route('correspondence.memos.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 transition-colors shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        إنشاء مراسلة جديدة
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-correspondence-layout>
