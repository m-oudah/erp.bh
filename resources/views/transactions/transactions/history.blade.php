<x-transactions-layout>
    {{-- Unified Interactive Filter Cards (مطابق لبرنامج المراسلات الداخلية) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        {{-- 1. Pending (قيد الدراسة والمتابعة) --}}
        @php
            $currentStatus = $currentStatus ?? request('status', 'pending');
            $activeTab = $activeTab ?? request('tab', 'transactions');
        @endphp
        <a href="{{ route('transactions.index', array_merge(request()->except(['status', 'page']), ['status' => 'pending', 'tab' => 'transactions'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ ($currentStatus === 'pending' || !$currentStatus) && $activeTab === 'transactions'
                     ? 'bg-gradient-to-br from-teal-50/90 to-white border-primary shadow-sm ring-2 ring-primary/20'
                     : 'bg-white border-gray-100 hover:border-teal-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ ($currentStatus === 'pending' || !$currentStatus) && $activeTab === 'transactions' ? 'text-teal-900' : 'text-slate-500' }}">قيد الدراسة والمتابعة</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ ($currentStatus === 'pending' || !$currentStatus) && $activeTab === 'transactions' ? 'bg-primary text-white shadow-xs' : 'bg-teal-50 text-primary group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['pending'] ?? 0 }}</div>
                <span class="text-[11px] font-semibold {{ ($currentStatus === 'pending' || !$currentStatus) && $activeTab === 'transactions' ? 'text-teal-700' : 'text-slate-400' }}">تتطلب إجراءً وردوداً</span>
            </div>
            @if(($currentStatus === 'pending' || !$currentStatus) && $activeTab === 'transactions')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            @endif
        </a>

        {{-- 2. Approved (المعاملات المعتمدة) --}}
        <a href="{{ route('transactions.index', array_merge(request()->except(['status', 'page']), ['status' => 'approved', 'tab' => 'transactions'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $currentStatus === 'approved' && $activeTab === 'transactions'
                     ? 'bg-gradient-to-br from-emerald-50/90 to-white border-emerald-500 shadow-sm ring-2 ring-emerald-500/20'
                     : 'bg-white border-gray-100 hover:border-emerald-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $currentStatus === 'approved' && $activeTab === 'transactions' ? 'text-emerald-900' : 'text-slate-500' }}">المعاملات المعتمدة</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $currentStatus === 'approved' && $activeTab === 'transactions' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-600 group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['approved'] ?? 0 }}</div>
                <span class="text-[11px] font-semibold {{ $currentStatus === 'approved' && $activeTab === 'transactions' ? 'text-emerald-700' : 'text-slate-400' }}">مكتملة وموافق عليها</span>
            </div>
            @if($currentStatus === 'approved' && $activeTab === 'transactions')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
            @endif
        </a>

        {{-- 3. Returned (المرجعة للاستيفاء) --}}
        <a href="{{ route('transactions.index', array_merge(request()->except(['status', 'page']), ['status' => 'returned', 'tab' => 'transactions'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $currentStatus === 'returned' && $activeTab === 'transactions'
                     ? 'bg-gradient-to-br from-amber-50/90 to-white border-secondary shadow-sm ring-2 ring-secondary/20'
                     : 'bg-white border-gray-100 hover:border-amber-200 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $currentStatus === 'returned' && $activeTab === 'transactions' ? 'text-amber-900' : 'text-slate-500' }}">المرجعة للاستيفاء</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $currentStatus === 'returned' && $activeTab === 'transactions' ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary group-hover:scale-105 transition-transform' }}" style="{{ $currentStatus === 'returned' && $activeTab === 'transactions' ? 'background-color: rgb(217 119 6);' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['returned'] ?? 0 }}</div>
                <span class="text-[11px] font-semibold {{ $currentStatus === 'returned' && $activeTab === 'transactions' ? 'text-amber-700' : 'text-slate-400' }}">تحتاج وثائق أو مراجعة</span>
            </div>
            @if($currentStatus === 'returned' && $activeTab === 'transactions')
                <div class="absolute bottom-0 left-0 right-0 h-1" style="background-color: rgb(217 119 6);"></div>
            @endif
        </a>

        {{-- 4. All (السجل الشامل) --}}
        <a href="{{ route('transactions.index', array_merge(request()->except(['status', 'page']), ['status' => 'all', 'tab' => 'transactions'])) }}"
           class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer
                  {{ $currentStatus === 'all' && $activeTab === 'transactions'
                     ? 'bg-gradient-to-br from-slate-100 to-white border-slate-500 shadow-sm ring-2 ring-slate-500/20'
                     : 'bg-white border-gray-100 hover:border-slate-300 hover:shadow-xs' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold {{ $currentStatus === 'all' && $activeTab === 'transactions' ? 'text-slate-900' : 'text-slate-500' }}">السجل الشامل</span>
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $currentStatus === 'all' && $activeTab === 'transactions' ? 'bg-slate-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 group-hover:scale-105 transition-transform' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <div class="text-2xl font-black text-slate-800 tracking-tight">{{ $counts['all'] ?? 0 }}</div>
                <span class="text-[11px] font-semibold {{ $currentStatus === 'all' && $activeTab === 'transactions' ? 'text-slate-700' : 'text-slate-400' }}">كافة المعاملات والطلبات</span>
            </div>
            @if($currentStatus === 'all' && $activeTab === 'transactions')
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-600"></div>
            @endif
        </a>
    </div>

    {{-- Tabs Selection Strip --}}
    <div class="flex items-center justify-between border-b border-gray-200">
        <div class="flex items-center gap-2 -mb-px">
            <a href="{{ route('transactions.index', array_merge(request()->except('tab'), ['tab' => 'transactions'])) }}"
               class="relative pb-3 px-4 text-xs sm:text-sm font-bold transition-all flex items-center gap-2
                      {{ $activeTab === 'transactions' ? 'text-teal-800 border-b-2 border-primary font-black' : 'text-tertiary hover:text-slate-800' }}">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>سجل المعاملات الرسمية</span>
                <span class="bg-teal-50 text-teal-800 border border-teal-200 rounded-full px-2 py-0.5 text-[10px] font-extrabold">{{ $counts['all'] ?? 0 }}</span>
            </a>

            <a href="{{ route('transactions.index', array_merge(request()->except('tab'), ['tab' => 'drafts'])) }}"
               class="relative pb-3 px-4 text-xs sm:text-sm font-bold transition-all flex items-center gap-2
                      {{ $activeTab === 'drafts' ? 'text-teal-800 border-b-2 border-primary font-black' : 'text-tertiary hover:text-slate-800' }}">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span>طلبات البوابة الإلكترونية</span>
                <span class="bg-amber-50 text-amber-800 border border-amber-200 rounded-full px-2 py-0.5 text-[10px] font-extrabold">{{ $counts['drafts'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    {{-- Search & Filter Bar (مطابق تماماً لبرنامج المراسلات الداخلية) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5">
        <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap items-center gap-2.5">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">

            {{-- Smart Search Box --}}
            <div class="flex-1 min-w-[240px] relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="{{ $activeTab === 'drafts' ? 'ابحث برقم التذكرة، اسم المواطن، رقم الهوية أو الهاتف...' : 'ابحث برقم المعاملة، اسم المواطن، رقم الهوية، أو موضوع الطلب...' }}"
                       class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400 py-2 font-medium">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            @if($activeTab === 'transactions')
                {{-- Transaction Type Filter --}}
                <div class="w-auto min-w-[150px]">
                    <select name="transaction_type_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                        <option value="">كل أنواع المعاملات</option>
                        @foreach ($transactionTypes as $type)
                            <option value="{{ $type->id }}" {{ request('transaction_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="w-auto min-w-[130px]">
                    <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                        <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>قيد الدراسة والمتابعة</option>
                        <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>السجل الشامل (جميع الحالات)</option>
                        <option value="approved" {{ $currentStatus === 'approved' ? 'selected' : '' }}>معتمدة ومكتملة</option>
                        <option value="returned" {{ $currentStatus === 'returned' ? 'selected' : '' }}>مرجعة للاستيفاء</option>
                        <option value="closed" {{ $currentStatus === 'closed' ? 'selected' : '' }}>مغلقة ومؤرشفة</option>
                    </select>
                </div>
            @endif

            {{-- Submit & Reset Buttons --}}
            <div class="flex items-center gap-1.5 shrink-0">
                <button type="submit" class="bg-primary hover:bg-teal-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>بحث</span>
                </button>
                @if (request()->hasAny(['search', 'transaction_type_id', 'status']) && (request('status') !== 'pending' || request('search') || request('transaction_type_id')))
                    <a href="{{ route('transactions.index', ['tab' => $activeTab]) }}" class="bg-gray-100 hover:bg-gray-200 text-slate-600 px-2.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 cursor-pointer" title="مسح التصفية">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>مسح</span>
                    </a>
                @endif
            </div>

            {{-- Total results count badge --}}
            <div class="mr-auto text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1.5 rounded-lg shrink-0">
                المعروض: <span class="text-teal-700 font-extrabold">{{ $activeTab === 'drafts' ? $drafts->total() : $transactions->total() }}</span> {{ $activeTab === 'drafts' ? 'طلب' : 'معاملة' }}
            </div>
        </form>
    </div>

    {{-- Main Table View (جدول المعاملات مطابق لجدول المراسلات) --}}
    @if($activeTab === 'transactions')
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">رقم ونوع المعاملة</th>
                                <th class="py-3 px-4">مقدم الطلب (المواطن)</th>
                                <th class="py-3 px-4">موضوع وتفاصيل المعاملة</th>
                                <th class="py-3 px-4">التوجيه والإجراء الفني</th>
                                <th class="py-3 px-4 text-center">الحالة</th>
                                <th class="py-3 px-4 text-center">تاريخ التقديم</th>
                                <th class="py-3 px-4 text-center">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transactions as $tx)
                                <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                                    {{-- Transaction Number & Type --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2.5 h-2.5 rounded-full {{ $tx->status === 'approved' ? 'bg-emerald-500' : ($tx->status === 'returned' ? 'bg-amber-500 ring-2 ring-amber-200' : ($tx->status === 'pending' ? 'bg-primary ring-2 ring-teal-200' : 'bg-slate-400')) }}"></div>
                                            <a href="{{ route('transactions.show', $tx->id) }}" class="font-mono font-bold text-teal-800 hover:text-teal-900 hover:underline">
                                                {{ $tx->transaction_number }}
                                            </a>
                                        </div>
                                        <div class="mt-1 flex items-center gap-1">
                                            <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                                {{ optional($tx->transactionType)->name ?? 'معاملة عامة' }}
                                            </span>
                                            @if($tx->building)
                                                <span class="inline-block bg-amber-50 text-amber-800 text-[9px] font-bold px-1.5 py-0.5 rounded border border-amber-200/60" title="ملف العقار">
                                                    عقار #{{ $tx->building->file_number }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Customer Info --}}
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800">{{ $tx->customer->name ?? $tx->customer->full_name ?? 'مواطن غير مسجل' }}</div>
                                        <div class="text-[11px] text-tertiary flex items-center gap-2 mt-0.5">
                                            @if($tx->customer && $tx->customer->id_no)
                                                <span class="font-mono text-slate-500">هوية: {{ $tx->customer->id_no }}</span>
                                            @endif
                                            @if($tx->customer && $tx->customer->mobile)
                                                <span class="font-mono text-slate-500">{{ $tx->customer->mobile }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Subject & Details snippet --}}
                                    <td class="py-3.5 px-4 max-w-xs">
                                        <p class="text-slate-700 font-medium line-clamp-2 leading-relaxed text-xs">
                                            {{ $tx->details ?? 'لا توجد تفاصيل إضافية مسجلة' }}
                                        </p>
                                    </td>

                                    {{-- Department & Routing --}}
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($tx->departmentResponses->isNotEmpty())
                                            @php $resp = $tx->departmentResponses->first(); @endphp
                                            <div class="flex items-center gap-1.5">
                                                <span class="inline-block bg-teal-50 text-teal-800 border border-teal-200 text-[10px] font-bold px-2 py-0.5 rounded-lg">
                                                    {{ $resp->department_name }}
                                                </span>
                                            </div>
                                            <div class="text-[10px] text-tertiary mt-1 line-clamp-1 max-w-[180px]">
                                                {{ $resp->response_text ?? 'بانتظار الرد الفني' }}
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-medium">قيد التوجيه الإداري</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $tx->status_badge_class }}">
                                            {{ $tx->status_label }}
                                        </span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap text-tertiary">
                                        <div class="font-bold text-slate-700">{{ $tx->created_at->format('Y-m-d') }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $tx->created_at->diffForHumans() }}</div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('transactions.show', $tx->id) }}"
                                               class="p-1.5 rounded-xl bg-slate-50 hover:bg-primary hover:text-white text-slate-600 transition-colors border border-gray-100 shadow-2xs"
                                               title="عرض وتفاصيل المعاملة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('transactions.print', $tx->id) }}"
                                               target="_blank"
                                               class="p-1.5 rounded-xl bg-slate-50 hover:bg-secondary hover:text-white text-slate-600 transition-colors border border-gray-100 shadow-2xs"
                                               title="طباعة المعاملة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if ($transactions->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 bg-slate-50/50">
                        {{ $transactions->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="text-center py-12 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-secondary flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">لا توجد معاملات مطابقة</h3>
                    <p class="text-xs text-tertiary mt-1 max-w-sm mx-auto">لم يتم العثور على أي معاملات وفق معايير البحث أو التصفية الحالية.</p>
                    <div class="mt-4">
                        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>تسجيل معاملة جديدة</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    @elseif($activeTab === 'drafts')
        {{-- Table for Portal Drafts (الواردة من الموقع) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if ($drafts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-3 px-4">رقم التذكرة والخدمة</th>
                                <th class="py-3 px-4">المواطن (مقدم الطلب)</th>
                                <th class="py-3 px-4">تفاصيل الطلب والعنوان</th>
                                <th class="py-3 px-4 text-center">الحالة</th>
                                <th class="py-3 px-4 text-center">تاريخ الورود</th>
                                <th class="py-3 px-4 text-center">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($drafts as $draft)
                                <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2.5 h-2.5 rounded-full {{ $draft->status === 'pending' ? 'bg-amber-500 ring-2 ring-amber-200' : 'bg-emerald-500' }}"></div>
                                            <span class="font-mono font-bold text-teal-800">{{ $draft->ticket_number ?? ('#'.$draft->id) }}</span>
                                        </div>
                                        <div class="mt-1 text-[11px] font-bold text-slate-800">
                                            {{ $draft->service_requested }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800">{{ $draft->full_name }}</div>
                                        <div class="text-[11px] text-tertiary flex items-center gap-2 mt-0.5">
                                            @if($draft->national_id)
                                                <span class="font-mono text-slate-500">هوية: {{ $draft->national_id }}</span>
                                            @endif
                                            @if($draft->phone)
                                                <span class="font-mono text-slate-500">{{ $draft->phone }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 max-w-sm">
                                        <p class="text-slate-700 font-medium line-clamp-2 leading-relaxed text-xs">
                                            {{ $draft->details }}
                                        </p>
                                        @if($draft->property_address)
                                            <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                </svg>
                                                <span>{{ $draft->property_address }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold {{ $draft->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                                            {{ $draft->status === 'pending' ? 'بانتظار المراجعة والتحويل' : 'تم التحويل' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap text-tertiary">
                                        <div class="font-bold text-slate-700">{{ $draft->created_at->format('Y-m-d') }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ $draft->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        <a href="{{ route('transactions.drafts.show', $draft->id) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-2xs transition-colors">
                                            <span>معاينة وتحويل</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5l-7 7 7 7M4 12h16"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($drafts->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 bg-slate-50/50">
                        {{ $drafts->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12 px-4">
                    <div class="w-14 h-14 rounded-2xl bg-teal-50 text-primary flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">لا توجد طلبات إلكترونية جديدة</h3>
                    <p class="text-xs text-tertiary mt-1">كافة الطلبات الواردة عبر بوابة المواطنين تم تدقيقها والتعامل معها.</p>
                </div>
            @endif
        </div>
    @endif
</x-transactions-layout>
