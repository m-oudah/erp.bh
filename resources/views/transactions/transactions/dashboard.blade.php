<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-[#14723a] text-[10px] font-bold mb-0.5 tracking-wider">نظام قلم الجمهور</div>
            <h1 class="font-bold text-xl tracking-wide flex items-center gap-2 leading-none text-slate-800">
                البوابة الموحدة
            </h1>
            <p class="text-slate-500 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>




<div class="min-h-screen bg-slate-50">
    

    <main class="px-6 py-10">
        <div class="max-w-5xl mx-auto space-y-6">

            <div class="bg-white rounded-[32px] border border-slate-200 shadow-lg p-8">
                <div class="mx-auto max-w-3xl space-y-6 text-center">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] shadow-inner">
                        <i data-lucide="message-square" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-slate-900">البوابة الموحدة لمتابعة الشكاوى والطلبات</h2>
                        <p class="mt-3 text-sm md:text-base text-slate-500 leading-relaxed">
                         يمكنك الاستعلام الفوري عن حالة المعاملة عن طريق إدخال رقم الهوية أو رقم الجوال أو رقم المعاملة.
                        </p>
                    </div>

                    <form action="{{ route('transactions.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="أدخل رقم الهوية (9 خانات)، رقم المعاملة، أو رقم الجوال..."
                            class="w-full sm:flex-1 text-sm font-bold pr-12 pl-4 py-4 bg-slate-50 border border-slate-300 rounded-3xl outline-none focus:border-[#14723a] focus:ring-2 focus:ring-[#14723a]/15 transition"
                        />
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 rounded-3xl bg-[#14723a] text-white text-sm font-black hover:bg-[#115e30] transition">استعلام الآن</button>
                    </form>
                </div>
            </div>

            @php
                $user = auth()->user();
                $visibleCards = 0;
                if (true) $visibleCards++;
                if (true) $visibleCards++;
                if (true) $visibleCards++;
                if (true) $visibleCards++;
                
                $gridColsClass = 'md:grid-cols-4';
                if ($visibleCards === 3) {
                    $gridColsClass = 'md:grid-cols-3';
                } elseif ($visibleCards === 2) {
                    $gridColsClass = 'md:grid-cols-2';
                } elseif ($visibleCards === 1) {
                    $gridColsClass = 'md:grid-cols-1';
                }
            @endphp

            <div class="grid grid-cols-1 {{ $gridColsClass }} gap-5">
                @if(true)
                <a href="{{ route('transactions.create') }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                        <i data-lucide="pen-tool" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">تسجيل معاملة جديدة</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">تسجيل معاملة رسمية جديدة للمواطن</p>
                </a>
                @endif

                @if(true)
                <a href="{{ route('transactions.drafts.index') }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                        <i data-lucide="message-square" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">تسجيل شكوى جديدة</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">تسجيل شكوى مواطن سريعة وتوجيهها</p>
                </a>
                @endif
                
                @if(true)
                <a href="{{ route('transactions.history') }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                        <i data-lucide="search" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">متابعة المعاملات</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">الاستعلام الفوري والتتبع الذكي للمعاملات</p>
                </a>
                @endif

                @if(true)
                <a href="{{ route('customers.index') }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">سجل المواطنين</h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">عرض قائمة المواطنين المسجلين في النظام</p>
                </a>
                @endif
            </div>

            {{-- قسم أدوات الإشراف (للمدير والرقابة الإدارية) --}}
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'audit_control')
            <div class="bg-white rounded-[32px] border border-slate-200 shadow-lg p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-900">أدوات الإشراف والرقابة</h2>
                        <p class="text-xs text-slate-400 font-bold mt-0.5">صلاحيات الرقابة الإدارية والتحكم في النظام</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @if(auth()->user()->role === 'admin')
                    {{-- إعدادات النظام --}}
                    <a href="{{ route('settings.index') }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                        <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                            <i data-lucide="settings" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">إعدادات النظام</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">إدارة المستخدمين، الأقسام، المعاملات، قوالب SMS وإعدادات SLA</p>
                    </a>

                    {{-- إدارة المستخدمين --}}
                    <a href="{{ route('settings.index') }}?tab=users" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                        <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">إدارة المستخدمين</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">إضافة وتعديل وحذف حسابات موظفي البلدية وصلاحياتهم</p>
                    </a>
                    @endif

                    {{-- سجل التدقيق --}}
                    <a href="{{ '#' }}" class="rounded-[28px] border border-slate-200 bg-white p-6 text-center shadow-sm hover:shadow-md transition">
                        <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-[#14723a] mb-4">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">سجل التدقيق</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">مراجعة كافة العمليات والأحداث المسجلة في النظام</p>
                    </a>
                </div>
            </div>
            @endif

            @if(request()->filled('search'))
                @if($transactions->count())
                <div class="bg-white rounded-[32px] border border-slate-200 p-6 shadow-sm mt-6">
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">نتائج البحث</h2>
                            <p class="text-sm text-slate-500">تم العثور على المعاملات التالية المطابقة لبحثك.</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[760px] text-right text-sm text-slate-700">
                            <thead class="bg-slate-50 text-slate-600 text-xs uppercase tracking-[0.1em] border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-4 font-bold">رقم المعاملة</th>
                                    <th class="px-5 py-4 font-bold">المواطن</th>
                                    <th class="px-5 py-4 font-bold">نوع المعاملة</th>
                                    <th class="px-5 py-4 font-bold">الحالة</th>
                                    <th class="px-5 py-4 font-bold">إجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($transactions as $tx)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-4 font-semibold text-slate-900">
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="text-[#14723a] hover:underline font-mono">{{ $tx->transaction_number }}</a>
                                    </td>
                                    <td class="px-5 py-4 text-slate-700">{{ $tx->customer->full_name }}</td>
                                        <td class="px-5 py-4 text-slate-700">
                                        <a href="{{ route('transactions.show', $tx->id) }}" class="hover:text-[#14723a] hover:underline transition">
                                            {{ optional($tx->transactionType)->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($tx->status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold">قيد الدراسة</span>
                                        @elseif($tx->status === 'returned')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[11px] font-bold">مرجعة</span>
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
                                            <a href="{{ '#' }}" title="طباعة المعاملة" class="w-8 h-8 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-800 hover:text-white hover:border-slate-800 inline-flex items-center justify-center transition shadow-sm">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                    <div class="mt-4">{{ $transactions->links() }}</div>
                    @endif
                </div>
                @else
                <div class="bg-white rounded-[32px] border border-slate-200 p-8 shadow-sm mt-6 text-center">
                    <div class="mx-auto inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-400 mb-4">
                        <i data-lucide="search-x" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">لا توجد نتائج</h3>
                    <p class="mt-2 text-sm text-slate-500">لم يتم العثور على أي معاملات تطابق معايير البحث الخاصة بك.</p>
                </div>
                @endif
            @endif
        </div>
    </main>
</div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
