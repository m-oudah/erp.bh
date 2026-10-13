<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-[#14723a] text-[10px] font-bold mb-0.5 tracking-wider">نظام قلم الجمهور</div>
            <h1 class="font-bold text-xl tracking-wide flex items-center gap-2 leading-none text-slate-800">
                المعاملات والتراسل
            </h1>
            <p class="text-slate-500 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>




<div class="min-h-screen bg-slate-50">
    

    <main class="px-6 py-10">
        <div class="max-w-7xl mx-auto space-y-8">
            @if(session('success'))
                <div id="flash-success-message" class="rounded-3xl border border-emerald-200 bg-emerald-50 text-emerald-800 p-5 shadow-sm text-sm font-bold">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        const flash = document.getElementById('flash-success-message');
                        if (flash) {
                            flash.style.transition = 'opacity 0.5s ease';
                            flash.style.opacity = '0';
                            setTimeout(() => flash.remove(), 500);
                        }
                    }, 5000);
                </script>
            @endif

            <div>
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-xl transition-all shadow-sm">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    <span>الرجوع للصفحة الرئيسية</span>
                </a>
            </div>

            {{-- ── التابين الرئيسيين ────────────────────────── --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200">
                <div class="flex items-center gap-2 -mb-px">
                    <a href="{{ route('transactions.index', array_merge(request()->except('tab', 'c_search', 'c_status', 'c_page'), ['tab' => 'transactions'])) }}"
                       class="relative pb-3 px-5 text-sm font-black transition-colors {{ $activeTab === 'transactions' ? 'text-[#14723a] border-b-2 border-[#14723a]' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="flex items-center gap-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            المعاملات
                            <span class="bg-slate-100 text-slate-600 rounded-full px-2 py-0.5 text-[10px] font-bold">{{ $transactions->total() }}</span>
                        </span>
                    </a>
                    <a href="{{ route('transactions.index', array_merge(request()->except('tab', 'search', 'status', 'complaint_type', 'transaction_type', 'sort_by', 'sort_dir'), ['tab' => 'complaints'])) }}"
                       class="relative pb-3 px-5 text-sm font-black transition-colors {{ $activeTab === 'complaints' ? 'text-rose-600 border-b-2 border-rose-600' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                            الشكاوى
                            <span class="bg-slate-100 text-slate-600 rounded-full px-2 py-0.5 text-[10px] font-bold">{{ $complaints->total() }}</span>
                        </span>
                    </a>
                    @if(auth()->user()->is_admin || str_contains(auth()->user()->department_name ?? '', 'قلم الجمهور'))
                    <a href="{{ route('transactions.index', array_merge(request()->except('tab', 'search', 'status', 'c_search', 'c_status', 'd_status'), ['tab' => 'drafts', 'd_status' => 'pending'])) }}"
                       class="relative pb-3 px-5 text-sm font-black transition-colors {{ $activeTab === 'drafts' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-slate-500 hover:text-slate-800' }}">
                        <span class="flex items-center gap-2">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                            واردة من الموقع
                            <span class="bg-slate-100 text-slate-600 rounded-full px-2 py-0.5 text-[10px] font-bold">{{ $drafts->total() }}</span>
                        </span>
                    </a>
                    @endif
                </div>

                @if($activeTab === 'transactions')
                <form action="{{ route('transactions.index') }}" method="GET" class="pb-3 flex items-center gap-2">
                    <input type="hidden" name="tab" value="transactions">
                    <input type="hidden" name="status" value="{{ request('status', 'pending') }}" />
                    <input type="hidden" name="complaint_type" value="{{ request('complaint_type', 'all') }}" />
                    <div class="relative">
                        <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="بحث في المعاملات..."
                            class="pr-10 pl-4 py-2 text-sm bg-white border border-slate-200 rounded-2xl focus:border-[#14723a] focus:ring-2 focus:ring-[#14723a]/15 outline-none transition w-64"
                        />
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#14723a] text-white text-sm font-bold rounded-2xl hover:bg-[#115e30] transition">بحث</button>
                </form>
                @endif
            </div>

            {{-- ════════════════════════════════════════════════ --}}
            {{-- تاب المعاملات                                   --}}
            {{-- ════════════════════════════════════════════════ --}}
            @if($activeTab === 'transactions')
            <div class="bg-white rounded-[32px] border border-slate-200 shadow-lg p-8">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-black text-slate-900">جدول المعاملات الكامل</h2>
                        <p class="mt-2 text-sm text-slate-500">ابحث في سجل المعاملات باستخدام رقم المعاملة، الهوية، الجوال، الحالة أو نوع الطلب.</p>
                    </div>
                    @if(!auth()->user()->is_audit_control)
                    <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-[#14723a] text-white text-sm font-bold rounded-2xl hover:bg-[#115e30] transition shadow-sm whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        تسجيل معاملة جديدة
                    </a>
                    @endif
                </div>

                <div class="mt-8 grid gap-4 lg:grid-cols-[1fr_auto] items-center">
                    <div class="flex flex-wrap items-center gap-3">
                        @php $txStatus = request('status', 'new'); @endphp
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'new',         'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'new'         ? 'bg-[#14723a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">جديدة</a>
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'in_progress', 'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'in_progress' ? 'bg-[#14723a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">قيد المعالجة</a>
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'approved',    'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'approved'    ? 'bg-[#14723a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">معتمدة</a>
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'all',         'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'all'         ? 'bg-[#14723a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">جميع المعاملات</a>
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'delayed',     'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'delayed'     ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-rose-600 hover:bg-rose-100' }}">متأخرة</a>
                        @if(auth()->user()->is_admin)
                        <a href="{{ route('transactions.index', ['tab' => 'transactions', 'status' => 'deleted',     'complaint_type' => request('complaint_type', 'all'), 'search' => request('search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $txStatus === 'deleted'     ? 'bg-[#14723a] text-white shadow-sm' : 'bg-slate-100 text-red-600 hover:bg-red-100' }}">محذوفة</a>
                        @endif
                    </div>
                    <form action="{{ route('transactions.index') }}" method="GET" class="flex items-center gap-3">
                        <input type="hidden" name="tab" value="transactions">
                        <input type="hidden" name="status" value="{{ request('status', 'new') }}" />
                        <input type="hidden" name="search" value="{{ request('search') }}" />
                        <select name="complaint_type" onchange="this.form.submit()" class="text-sm font-bold bg-slate-50 border border-slate-200 text-slate-700 px-4 py-3 rounded-3xl focus:ring-2 focus:ring-[#14723a]/15 focus:border-[#14723a] outline-none transition shadow-sm">
                            <option value="all">كل أنواع الطلبات</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}" {{ request('complaint_type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                @if($transactions->count())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] text-right text-sm text-slate-700">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] uppercase tracking-[0.08em] border-b border-slate-200">
                                <tr>
                                    @php
                                        $currentSort = request('sort_by', 'created_at');
                                        $currentDir = request('sort_dir', 'desc');
                                        $toggleDirTx = ($currentSort === 'transaction_number' && $currentDir === 'asc') ? 'desc' : 'asc';
                                        $toggleDirDate = ($currentSort === 'created_at' && $currentDir === 'desc') ? 'asc' : 'desc';
                                    @endphp
                                    <th class="px-5 py-4 font-semibold hover:bg-slate-100 cursor-pointer transition-colors">
                                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'transactions', 'sort_by' => 'transaction_number', 'sort_dir' => $toggleDirTx]) }}" class="flex items-center gap-1">
                                            رقم المعاملة
                                            @if($currentSort === 'transaction_number')
                                                <i data-lucide="{{ $currentDir === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-[#14723a]"></i>
                                            @else
                                                <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-400"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-5 py-4 font-semibold">نوع المعاملة</th>
                                    <th class="px-5 py-4 font-semibold">المواطن</th>
                                    <th class="px-5 py-4 font-semibold">الجوال / الهوية</th>
                                    <th class="px-5 py-4 font-semibold hover:bg-slate-100 cursor-pointer transition-colors">
                                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'transactions', 'sort_by' => 'created_at', 'sort_dir' => $toggleDirDate]) }}" class="flex items-center gap-1">
                                            التاريخ
                                            @if($currentSort === 'created_at')
                                                <i data-lucide="{{ $currentDir === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-[#14723a]"></i>
                                            @else
                                                <i data-lucide="chevrons-up-down" class="w-3 h-3 text-slate-400"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-5 py-4 font-semibold">الحالة</th>
                                    <th class="px-5 py-4 font-semibold">إجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($transactions as $tx)
                                    @php
                                        $slaDays = optional($tx->transactionType)->sla_days ?? 3;
                                        $daysPassed = $tx->created_at->diffInDays(now());
                                        $isDelayed = $tx->status === 'pending' && $daysPassed >= $slaDays;
                                    @endphp
                                    <tr class="transition-colors {{ $isDelayed ? 'bg-red-50 hover:bg-red-100/80 border-r-4 border-red-500' : 'hover:bg-slate-50' }}">
                                        <td class="px-5 py-4 font-black text-slate-900">
                                            <a href="{{ route('transactions.show', $tx->id) }}" class="hover:text-[#14723a] transition font-mono">
                                                {{ $tx->transaction_number }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-4">
                                            <a href="{{ route('transactions.show', $tx->id) }}" class="font-bold text-slate-800 hover:text-[#14723a] hover:underline transition">
                                                {{ optional($tx->transactionType)->name ?? 'معاملة عامة' }}
                                            </a>
                                        </td>
                                        <td class="px-5 py-4">
                                            <a href="{{ route('customers.show', $tx->customer->id) }}" class="font-bold text-[#14723a] hover:underline hover:text-[#115e30] transition inline-flex items-center gap-1.5">
                                                <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-600"></i>
                                                <span>{{ $tx->customer->full_name }}</span>
                                            </a>
                                        </td>
                                        <td class="px-5 py-4 text-slate-500 text-xs">
                                            <div>جوال: {{ $tx->customer->mobile }}</div>
                                            <div>هوية: {{ $tx->customer->id_no }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-slate-600 text-sm">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="px-5 py-4">
                                            @if($tx->trashed())
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-bold">محذوفة</span>
                                            @elseif($tx->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">قيد المعالجة</span>
                                            @elseif($tx->status === 'returned')
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">مرتجعة</span>
                                            @elseif($tx->status === 'approved')
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">معتمدة</span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-[11px] font-bold">مرفوضة</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('transactions.show', $tx->id) }}" title="عرض التفاصيل" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-[#14723a] hover:text-white hover:border-[#14723a] inline-flex items-center justify-center transition shadow-sm">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </a>
                                                <a href="{{ route('transactions.print', $tx->id) }}" title="طباعة المعاملة" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-800 hover:text-white hover:border-slate-800 inline-flex items-center justify-center transition shadow-sm">
                                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                                </a>
                                                @if(auth()->user()->is_admin)
                                                <form action="{{ route('transactions.destroy', $tx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه المعاملة ونقلها للمحذوفات؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="حذف المعاملة" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-rose-600 hover:text-white hover:border-rose-600 inline-flex items-center justify-center transition shadow-sm group">
                                                        <i data-lucide="trash-2" class="w-4 h-4 group-hover:text-white"></i>
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
                    @if($transactions->hasPages())
                        <div class="p-4 border-t border-slate-200 bg-slate-50">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-16 text-center text-slate-500">
                        <div class="text-2xl font-black mb-3">لا توجد معاملات سابقة</div>
                        <p class="text-sm text-slate-400">حاول تغيير معايير البحث أو العودة إلى صفحة التسجيل لبدء معاملة جديدة.</p>
                    </div>
                @endif
            </div>
            @endif

            {{-- ════════════════════════════════════════════════ --}}
            {{-- تاب الشكاوى                                     --}}
            {{-- ════════════════════════════════════════════════ --}}
            @if($activeTab === 'complaints')
            <div class="bg-white rounded-[32px] border border-slate-200 shadow-lg p-8">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-black text-slate-900">سجل الشكاوى المقدمة</h2>
                        <p class="mt-2 text-sm text-slate-500">ابحث في سجل الشكاوى برقم الشكوى، اسم المواطن، الهوية أو الجوال.</p>
                    </div>
                    @if(!auth()->user()->is_audit_control)
                    <a href="{{ route('complaints.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-rose-600 text-white text-sm font-bold rounded-2xl hover:bg-rose-700 transition shadow-sm whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        تسجيل شكوى جديدة
                    </a>
                    @endif
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @php $cStatus = request('c_status', 'in_progress'); @endphp
                    <a href="{{ route('transactions.index', ['tab' => 'complaints', 'c_status' => 'all',         'c_search' => request('c_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $cStatus === 'all'         ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">الكل</a>
                    <a href="{{ route('transactions.index', ['tab' => 'complaints', 'c_status' => 'in_progress', 'c_search' => request('c_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $cStatus === 'in_progress' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">قيد المعالجة</a>
                    <a href="{{ route('transactions.index', ['tab' => 'complaints', 'c_status' => 'resolved',    'c_search' => request('c_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $cStatus === 'resolved'    ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">تم الحل</a>
                    <a href="{{ route('transactions.index', ['tab' => 'complaints', 'c_status' => 'closed',      'c_search' => request('c_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $cStatus === 'closed'      ? 'bg-slate-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">مغلقة</a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('transactions.index', ['tab' => 'complaints', 'c_status' => 'deleted',     'c_search' => request('c_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $cStatus === 'deleted'     ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-red-600 hover:bg-red-100' }}">محذوفة</a>
                    @endif

                    <form action="{{ route('transactions.index') }}" method="GET" class="mr-auto flex items-center gap-2">
                        <input type="hidden" name="tab" value="complaints">
                        <input type="hidden" name="c_status" value="{{ request('c_status', 'in_progress') }}">
                        <div class="relative">
                            <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                            <input type="text" name="c_search" value="{{ request('c_search') }}" placeholder="بحث في الشكاوى..." class="pr-10 pl-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:border-rose-400 focus:ring-2 focus:ring-rose-400/15 outline-none transition w-64">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-2xl hover:bg-slate-700 transition">بحث</button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                @if($complaints->count())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-right text-sm text-slate-700">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] uppercase tracking-[0.08em] border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">رقم الشكوى</th>
                                    <th class="px-5 py-4 font-semibold">المواطن المشتكي</th>
                                    <th class="px-5 py-4 font-semibold">الجوال / الهوية</th>
                                    <th class="px-5 py-4 font-semibold">نوع الشكوى</th>
                                    <th class="px-5 py-4 font-semibold">تاريخ التقديم</th>
                                    <th class="px-5 py-4 font-semibold">الحالة</th>
                                    <th class="px-5 py-4 font-semibold">إجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($complaints as $complaint)
                                <tr class="hover:bg-rose-50/40 transition-colors">
                                    <td class="px-5 py-4 font-black text-slate-900 font-mono">
                                        <a href="{{ route('transactions.show', $complaint->id) }}" class="hover:text-rose-600 transition-colors">
                                            {{ $complaint->transaction_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="font-bold text-slate-800">{{ $complaint->customer->full_name ?? $complaint->full_name ?? 'غير معروف' }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 text-xs">
                                        <div>جوال: {{ $complaint->customer->mobile ?? $complaint->phone ?? 'غير متوفر' }}</div>
                                        <div>هوية: {{ $complaint->customer->id_no ?? $complaint->national_id ?? 'غير متوفر' }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-700 max-w-xs">
                                        <a href="{{ route('transactions.show', $complaint->id) }}" class="hover:text-rose-600 hover:underline transition-colors block">
                                            <span class="line-clamp-2">{{ optional($complaint->complaintType)->name ?? 'غير محدد' }}</span>
                                        </a>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 text-sm">{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4">
                                        @if($complaint->trashed())
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-bold">محذوفة</span>
                                        @elseif($complaint->status === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">قيد المعالجة</span>
                                        @elseif($complaint->status === 'resolved')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">تم الحل</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-bold">مغلقة</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('transactions.show', $complaint->id) }}" title="عرض الشكوى" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-rose-600 hover:text-white hover:border-rose-600 inline-flex items-center justify-center transition shadow-sm">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                            <a href="{{ url('#', $complaint->id) }}" title="طباعة الشكوى" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-800 hover:text-white hover:border-slate-800 inline-flex items-center justify-center transition shadow-sm">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                            </a>
                                            @if(auth()->user()->is_admin)
                                            <form action="{{ url('#', $complaint->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشكوى ونقلها للمحذوفات؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="حذف الشكوى" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-rose-600 hover:text-white hover:border-rose-600 inline-flex items-center justify-center transition shadow-sm group">
                                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:text-white"></i>
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
                    @if($complaints->hasPages())
                        <div class="p-4 border-t border-slate-200 bg-slate-50">
                            {{ $complaints->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-16 text-center text-slate-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-4 text-slate-300"></i>
                        <div class="text-2xl font-black mb-3">لا توجد شكاوى مسجلة</div>
                        <p class="text-sm text-slate-400 mb-6">لم يتم تسجيل أي شكاوى بعد أو لا تطابق معايير البحث الحالية.</p>
                        <a href="{{ route('complaints.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            تسجيل شكوى جديدة
                        </a>
                    </div>
                @endif
            </div>
            @endif

            {{-- ════════════════════════════════════════════════ --}}
            {{-- تاب واردة من الموقع                             --}}
            {{-- ════════════════════════════════════════════════ --}}
            @if($activeTab === 'drafts')
            <div class="bg-white rounded-[32px] border border-slate-200 shadow-lg p-8">
                <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-black text-slate-900">المعاملات والشكاوى الواردة من الموقع</h2>
                        <p class="mt-2 text-sm text-slate-500">هذه الطلبات مقدمة من المواطنين عبر الموقع الإلكتروني، يرجى مراجعتها وتصنيفها وتحويلها إلى معاملة رسمية أو شكوى.</p>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    @php $dStatus = request('d_status', 'pending'); @endphp
                    <a href="{{ route('transactions.index', ['tab' => 'drafts', 'd_status' => 'pending', 'd_search' => request('d_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $dStatus === 'pending' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">قيد المراجعة</a>
                    <a href="{{ route('transactions.index', ['tab' => 'drafts', 'd_status' => 'converted', 'd_search' => request('d_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $dStatus === 'converted' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">تم التحويل</a>
                    <a href="{{ route('transactions.index', ['tab' => 'drafts', 'd_status' => 'all', 'd_search' => request('d_search')]) }}" class="rounded-xl px-4 py-3 text-xs font-black transition {{ $dStatus === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">الكل</a>

                    <form action="{{ route('transactions.index') }}" method="GET" class="mr-auto flex items-center gap-2">
                        <input type="hidden" name="tab" value="drafts">
                        <input type="hidden" name="d_status" value="{{ request('d_status', 'pending') }}">
                        <div class="relative">
                            <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                            <input type="text" name="d_search" value="{{ request('d_search') }}" placeholder="بحث بالاسم أو الرقم..." class="pr-10 pl-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 outline-none transition w-64">
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-2xl hover:bg-slate-700 transition">بحث</button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mt-4">
                @if($drafts->count())
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[800px] text-right text-sm text-slate-700">
                            <thead class="bg-slate-50 text-slate-600 text-[11px] uppercase tracking-[0.08em] border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">رقم التتبع</th>
                                    <th class="px-5 py-4 font-semibold">مقدم الطلب</th>
                                    <th class="px-5 py-4 font-semibold">الخدمة المطلوبة</th>
                                    <th class="px-5 py-4 font-semibold">تاريخ التقديم</th>
                                    <th class="px-5 py-4 font-semibold">الحالة</th>
                                    <th class="px-5 py-4 font-semibold">إجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($drafts as $draft)
                                <tr class="hover:bg-blue-50/40 transition-colors">
                                    <td class="px-5 py-4 font-black text-slate-900 font-mono">
                                        <a href="{{ route('transactions.drafts.show', $draft->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $draft->ticket_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800">{{ $draft->full_name }}</div>
                                        <div class="text-xs text-slate-500">جوال: {{ $draft->phone }} | هوية: {{ $draft->national_id }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-slate-700 max-w-xs">
                                        <span class="font-bold">{{ $draft->service_requested }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-500 text-sm">{{ $draft->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-5 py-4">
                                        @if($draft->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-50 text-yellow-700 text-[11px] font-bold">قيد المراجعة</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold">تم التحويل</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($draft->status === 'pending')
                                            <button type="button" onclick="openConvertModal({{ $draft->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition shadow-sm">
                                                <i data-lucide="arrow-left-right" class="w-3.5 h-3.5"></i>
                                                مراجعة وتحويل
                                            </button>
                                        @else
                                            @php $convertedUrl = $draft->getConvertedModelUrl(); @endphp
                                            @if($convertedUrl)
                                                @if(str_contains($convertedUrl, 'complaints'))
                                                    <a href="{{ $convertedUrl }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition shadow-sm">
                                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                        عرض الشكوى
                                                    </a>
                                                @else
                                                    <a href="{{ $convertedUrl }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition shadow-sm">
                                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                                        عرض المعاملة
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400 font-bold">مكتمل</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($drafts->hasPages())
                        <div class="p-4 border-t border-slate-200 bg-slate-50">
                            {{ $drafts->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-16 text-center text-slate-500">
                        <i data-lucide="globe" class="w-12 h-12 mx-auto mb-4 text-slate-300"></i>
                        <div class="text-2xl font-black mb-3">لا توجد طلبات واردة من الموقع</div>
                        <p class="text-sm text-slate-400 mb-6">جميع الطلبات تمت مراجعتها أو لا توجد طلبات جديدة.</p>
                    </div>
                @endif
            </div>
            @endif

        </div>
    </main>
</div>

<!-- Modal for Convert (will be dynamically populated via JS or separate page) -->
<script>
    function openConvertModal(draftId) {
        window.location.href = `/transactions/drafts/${draftId}`;
    }
</script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
