<x-transactions-layout>
    {{-- Header Action Bar (مطابق لشريط مراسلات الداخلية) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-slate-600 transition-colors" title="العودة للقائمة">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight font-mono">{{ $transaction->transaction_number }}</h2>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-800 border border-slate-200">
                        {{ optional($transaction->transactionType)->name ?? 'معاملة عامة' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $transaction->status_badge_class }}">
                        {{ $transaction->status_label }}
                    </span>
                </div>
                <h1 class="text-xs font-bold text-tertiary mt-1">
                    مقدمة من: <span class="text-slate-800 font-extrabold">{{ $transaction->customer->name ?? $transaction->customer->full_name ?? 'مواطن' }}</span>
                    @if($transaction->customer && $transaction->customer->id_no)
                        <span class="font-mono text-slate-400 mr-1.5">(هوية: {{ $transaction->customer->id_no }})</span>
                    @endif
                </h1>
            </div>
        </div>

        {{-- Top Right Actions --}}
        <div class="flex items-center gap-2">
            <button type="button"
                    onclick="openPrintModal()"
                    class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all flex items-center gap-1.5 cursor-pointer hover:shadow">
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>طباعة المعاملة</span>
            </button>
        </div>
    </div>

    {{-- Main Layout: 2 Columns (Details & Timeline/Attachments) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        {{-- Right / Main Column (8 cols) --}}
        <div class="lg:col-span-8 space-y-5">
            @if($transaction->trashed())
                <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">هذه المعاملة محذوفة أو مؤرشفة</h4>
                        <p class="text-xs text-rose-700 mt-0.5">الصفحة معروضة للاطلاع فقط بناءً على صلاحيات الإشراف.</p>
                    </div>
                </div>
            @endif

            {{-- 1. Transaction Details Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-primary"></div>
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
                    <span class="text-xs font-bold text-slate-500">موضوع وتفاصيل المعاملة</span>
                    <span class="text-[11px] font-bold text-slate-400">تاريخ التسجيل: {{ $transaction->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="text-sm text-slate-800 leading-relaxed font-medium bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                    {{ $transaction->details ?? 'لا توجد تفاصيل إضافية مسجلة.' }}
                </div>
            </div>

            {{-- 2. Citizen & Property Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-black text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>بيانات المواطن والعقار المستهدف</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">اسم المواطن</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $transaction->customer->name ?? $transaction->customer->full_name ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">رقم الهوية</span>
                        <span class="font-bold text-slate-800 font-mono text-sm">{{ $transaction->customer->id_no ?? '-' }}</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <span class="text-slate-400 block mb-1">رقم الجوال</span>
                        <span class="font-bold text-slate-800 font-mono text-sm">{{ $transaction->customer->mobile ?? '-' }}</span>
                    </div>
                </div>

                @if($transaction->building)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-bold text-slate-500">العقار المرتبط بالمعاملة:</span>
                            <span class="font-bold text-teal-800 bg-teal-50 px-2 py-0.5 rounded text-xs border border-teal-200">ملف رقم #{{ $transaction->building->file_number }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                            <div>الشارع: <span class="font-bold text-slate-700">{{ $transaction->building->street_name ?? 'غير محدد' }}</span></div>
                            <div>رقم المبنى: <span class="font-bold text-slate-700">{{ $transaction->building->building_number ?? '-' }}</span></div>
                            <div>الحوض / القطعة: <span class="font-bold text-slate-700 font-mono">{{ $transaction->building->block_number ?? '-' }} / {{ $transaction->building->parcel_number ?? '-' }}</span></div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- 3. Department Responses & Workflow --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-black text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>الدراسات الفنية وردود الأقسام المختصة</span>
                </h3>
                
                <div class="space-y-3">
                    @forelse($transaction->departmentResponses as $response)
                        <div class="border rounded-2xl p-4 {{ $response->status === 'pending' ? 'border-amber-200 bg-amber-50/30' : 'border-teal-200 bg-teal-50/20' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg {{ $response->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700' }} flex items-center justify-center font-bold text-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-xs text-slate-900">{{ $response->department_name }}</h4>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $response->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }}">
                                    {{ $response->status === 'pending' ? 'قيد الدراسة والمتابعة' : 'تم الرد الفني' }}
                                </span>
                            </div>
                            @if($response->response_text)
                                <div class="bg-white rounded-xl p-3 text-xs text-slate-800 border border-slate-100 leading-relaxed font-medium mt-2">
                                    {{ $response->response_text }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-400 bg-slate-50 rounded-xl">
                            لا توجد ردود فنية مسجلة حتى الآن.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 4. Mayor / Executive Decision --}}
            @if($transaction->mayor_opinion || $transaction->status === 'approved' || $transaction->status === 'closed')
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 left-0 h-1 bg-secondary"></div>
                    <h3 class="text-sm font-black text-slate-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>قرار وتوجيهات رئيس البلدية</span>
                    </h3>
                    <div class="bg-amber-50/40 border border-amber-200/60 rounded-xl p-4 text-xs font-semibold text-slate-800 leading-relaxed">
                        {{ $transaction->mayor_opinion ?? 'تم اعتماد المعاملة رسمياً.' }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Left / Sidebar Column (4 cols) --}}
        <div class="lg:col-span-4 space-y-5">
            {{-- Status Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <span class="text-xs font-bold text-slate-400 block mb-2">حالة المعاملة الحالية</span>
                <div class="p-3 rounded-xl {{ $transaction->status_badge_class }} flex items-center justify-between">
                    <span class="font-black text-xs">{{ $transaction->status_label }}</span>
                    <div class="w-2.5 h-2.5 rounded-full {{ $transaction->status === 'approved' ? 'bg-emerald-500' : ($transaction->status === 'returned' ? 'bg-amber-500' : 'bg-primary') }}"></div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100 text-xs text-slate-500 space-y-2">
                    <div class="flex justify-between">
                        <span>نوع المعاملة:</span>
                        <span class="font-bold text-slate-800">{{ optional($transaction->transactionType)->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>مدة الإنجاز المستهدفة (SLA):</span>
                        <span class="font-bold text-teal-800 font-mono">{{ optional($transaction->transactionType)->sla_days ?? 0 }} أيام</span>
                    </div>
                    <div class="flex justify-between">
                        <span>الرسوم المقررة:</span>
                        <span class="font-bold text-slate-800">{{ optional($transaction->transactionType)->request_fees ?? 'مجاني' }}</span>
                    </div>
                </div>
            </div>

            {{-- Attachments Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h4 class="text-xs font-black text-slate-800 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span>المرفقات والوثائق</span>
                </h4>
                <div class="space-y-2">
                    @forelse($transaction->attachments as $att)
                        <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-700 truncate">{{ $att->file_label ?? 'مرفق' }}</span>
                            <a href="{{ Storage::url($att->file_path) }}" target="_blank" class="text-teal-700 hover:underline font-bold text-[11px] shrink-0">عرض</a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-3">لا توجد مرفقات مرفوعة مع المعاملة</p>
                    @endforelse
                </div>
            </div>

            {{-- Audit Trail Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h4 class="text-xs font-black text-slate-800 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>سجل حركات المعاملة</span>
                </h4>
                <div class="space-y-3">
                    @forelse($transaction->logs()->orderBy('created_at', 'desc')->take(6)->get() as $log)
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="w-2 h-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $log->action }}</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $log->user_name ?? 'النظام' }} — {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="w-2 h-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                            <div>
                                <p class="font-bold text-slate-800">إنشاء وتوثيق المعاملة</p>
                                <p class="text-[10px] text-slate-400 font-medium">{{ $transaction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Print Preview Popup Modal --}}
    <div id="print_modal" class="fixed inset-0 z-50 hidden overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closePrintModal()"></div>

        <div class="fixed inset-0 flex items-center justify-center p-2 sm:p-3 text-center z-10 pointer-events-none">
            <div class="pointer-events-auto relative transform overflow-hidden rounded-2xl sm:rounded-3xl bg-white text-right shadow-2xl transition-all w-full max-w-5xl h-[98vh] flex flex-col border border-slate-200">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-3 border-b border-gray-100 bg-slate-50/90 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 border border-teal-200/80 text-primary flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800">معاينة نموذج طباعة المعاملة</h3>
                            <p class="text-[11px] font-bold text-slate-500 font-mono">{{ $transaction->transaction_number }}</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                onclick="printIframeDocument()" 
                                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>طباعة الآن</span>
                        </button>
                        
                        <button type="button" 
                                onclick="closePrintModal()" 
                                class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                                title="إغلاق النافذة">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body: Iframe Container (Full Height) --}}
                <div class="flex-1 min-h-0 bg-slate-100 p-2 sm:p-3 overflow-hidden flex flex-col relative">
                    <iframe id="print_frame" 
                            src="about:blank" 
                            data-src="{{ route('transactions.print', $transaction->id) }}?modal=1"
                            class="w-full h-full rounded-2xl bg-white border border-gray-200 shadow-inner"
                            title="نموذج طباعة المعاملة"></iframe>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-between px-6 py-2.5 border-t border-gray-100 bg-white text-xs shrink-0">
                    <span class="text-slate-500 font-medium">يمكنك مراجعة كافة تفاصيل المعاملة ثم الضغط على "طباعة الآن" أو إغلاق النافذة.</span>
                    <button type="button" 
                            onclick="closePrintModal()" 
                            class="px-4 py-1.5 rounded-xl border border-slate-300 font-bold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openPrintModal() {
            const modal = document.getElementById('print_modal');
            const iframe = document.getElementById('print_frame');
            if (iframe && (!iframe.src || iframe.src === 'about:blank' || iframe.src.indexOf('about:blank') !== -1)) {
                iframe.src = iframe.getAttribute('data-src');
            }
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closePrintModal() {
            const modal = document.getElementById('print_modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function printIframeDocument() {
            const iframe = document.getElementById('print_frame');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePrintModal();
            }
        });
    </script>
    @endpush
</x-transactions-layout>
