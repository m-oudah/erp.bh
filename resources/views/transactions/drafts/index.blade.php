<x-transactions-layout>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900">طلبات المواطنين الواردة من البوابة الإلكترونية</h3>
                <p class="text-xs text-slate-500 mt-0.5">مراجعة الطلبات والشكاوى المقدمة وتدقيقها تمهيداً لتحويلها لمعاملات رسمية</p>
            </div>
            <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3 py-1.5 rounded-xl transition-colors">
                العودة لسجل المعاملات
            </a>
        </div>

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
</x-transactions-layout>
