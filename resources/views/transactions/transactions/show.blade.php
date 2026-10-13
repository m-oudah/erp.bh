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




<header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
    <div class="max-w-6xl mx-auto px-6 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4 space-x-reverse">
            <a href="{{ route('transactions.index') }}" class="w-10 h-10 bg-slate-50 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl flex items-center justify-center border border-slate-200 transition-colors">
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-base font-black text-slate-800 leading-tight">تفاصيل المعاملة: <span class="font-mono text-[#14723a]">{{ $transaction->transaction_number }}</span></h1>
                <p class="text-[10px] text-slate-500 font-medium">{{ optional($transaction->type)->name ?? 'بدون نوع' }} - مقدمة من: {{ $transaction->customer->full_name }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('transactions.print', $transaction->id) }}" class="flex bg-[#14723a] hover:bg-[#14723a]/90 text-white px-3.5 py-2 rounded-lg text-xs font-bold items-center gap-1.5 transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i> طباعة المعاملة
            </a>
        </div>
    </div>
</header>

<main class="flex-grow p-6">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- العمود الأيمن (التفاصيل والردود الفنية) -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($transaction->trashed())
            <div class="bg-red-50 border-r-4 border-red-600 p-4 rounded-xl mb-6 shadow-sm flex items-start gap-3">
                <i data-lucide="trash-2" class="w-6 h-6 text-red-600 shrink-0"></i>
                <div>
                    <h3 class="text-red-800 font-black text-lg">هذه المعاملة محذوفة</h3>
                    <p class="text-red-700 text-sm mt-1">أنت تشاهد أرشيف معاملة محذوفة. لا يمكنك اتخاذ أي إجراءات عليها أو إضافة ردود فنية جديدة. هذه الصفحة معروضة لغرض الاطلاع فقط بناءً على صلاحياتك كمدير للنظام.</p>
                </div>
            </div>
            @endif

            <!-- تفاصيل الطلب -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-[#14723a]"></div>
                <!-- نوع المعاملة -->
                <div class="mb-4 pb-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block mb-0.5">نوع المعاملة</span>
                        <h3 class="text-lg font-black text-[#14723a]">{{ optional($transaction->transactionType)->name ?? 'معاملة عامة' }}</h3>
                    </div>
                </div>

                <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5 text-[#14723a]"></i>
                    تفاصيل الطلب
                </h2>
                <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed border border-slate-100">
                    {{ optional($transaction)->details ?? 'لا توجد تفاصيل.' }}
                </div>
            </div>

                  <!-- بطاقة المواطن والعقار -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-[#14723a]"></i>
                    مقدم الطلب والعقار
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-[14px] text-slate-400 font-bold mb-0.5">الاسم</p>
                        <p class="font-bold text-[#14723a]">{{ $transaction->customer->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-[14px] text-slate-400 font-bold mb-0.5">رقم الهوية</p>
                        <p class="text-xs font-mono font-bold text-slate-700">{{ $transaction->customer->id_no }}</p>
                    </div>
                    <div>
                        <p class="text-[14px] text-slate-400 font-bold mb-0.5">رقم الجوال</p>
                        <p class="text-xs font-mono font-bold text-slate-700">{{ $transaction->customer->mobile }}</p>
                    </div>
                </div>
                <div class="pt-4 mt-2 border-t border-slate-100">
                    <h3 class="text-[14px] font-bold text-slate-400 mb-2">العقار المستهدف</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-[13px] text-slate-400 font-bold mb-0.5">القطعة</p>
                            <p class="font-bold text-slate-700 font-mono">{{ '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[13px] text-slate-400 font-bold mb-0.5">القسيمة</p>
                            <p class="font-bold text-slate-700 font-mono">{{ '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[13px] text-slate-400 font-bold mb-0.5">العنوان التفصيلي</p>
                            <p class="text-xs font-bold text-slate-700">{{ '-' ?? 'غير محدد' }}</p>
                        </div>
                    </div>
                </div>
            </div>



            <!-- الردود الفنية المتوازية -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="git-merge" class="w-5 h-5 text-[#14723a]"></i>
                    ردود الأقسام الفنية
                </h2>
                
                <div class="space-y-4">
                    @foreach($transaction->departmentResponses as $response)
                    <div class="border rounded-xl p-4 {{ $response->status === 'pending' ? 'border-amber-200 bg-amber-50/30' : 'border-emerald-200 bg-emerald-50/30' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg {{ $response->status === 'pending' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center">
                                    <i data-lucide="building" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-800">{{ $response->department_name }}</h4>
                                    <p class="text-[10px] text-slate-500">
                                        الحالة: 
                                        @if($response->status === 'pending')
                                            <span class="text-amber-600 font-bold">قيد الدراسة</span>
                                        @else
                                            <span class="text-emerald-600 font-bold">تم الرد ({{ $response->updated_at->format('Y-m-d H:i') }})</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($response->is_delayed)
                                <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold animate-pulse">متأخر (تجاوز SLA)</span>
                            @endif
                        </div>

                        @if($response->status === 'responded')
                            <div class="bg-white rounded-lg p-3 text-sm text-slate-700 border border-slate-100">
                                {{ $response->response_text }}
                            </div>
                        @endif

                        <!-- Form for department head to respond (مخفي عن الرقابة الإدارية) -->
                        @if(!$transaction->trashed() && $response->status === 'pending' && !auth()->user()->is_audit_control && str_contains(auth()->user()->department_name ?? '', $response->department_name))
                            <form action="{{ route('transactions.department-responses.update', $response->id) }}" method="POST" class="mt-4 pt-4 border-t border-slate-200 border-dashed">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="department_id" value="{{ $response->department_id }}">
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-[#14723a]">إضافة الرد الفني للقسم</label>
                                    <textarea name="response_text" rows="3" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-[#14723a] focus:border-[#14723a] resize-none" placeholder="اكتب الرأي الفني للقسم هنا..." required></textarea>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-[#14723a] text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-[#115e30]">إرسال الرد</button>
                                    </div>
                                </div>
                            </form>
                        @endif

                        {{-- رسالة إعلامية للرقابة الإدارية عند وجود ردود معلقة --}}
                        @if(auth()->user()->is_audit_control && $response->status === 'pending')
                            <div class="mt-3 pt-3 border-t border-dashed border-purple-200 flex items-center gap-2 text-xs text-purple-600 bg-purple-50/50 rounded-lg px-3 py-2">
                                <i data-lucide="eye" class="w-3.5 h-3.5 shrink-0"></i>
                               <span>لم يتم الرد على هذه المعاملة حتى الآن.</span>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- قرار رئيس البلدية -->
            @if(auth()->user()->role === 'mayor' || $transaction->status === 'approved' || $transaction->status === 'closed')
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 left-0 h-1 bg-blue-500"></div>
                <h2 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                    <i data-lucide="award" class="w-5 h-5 text-blue-600"></i>
                    قرار السيد رئيس البلدية
                </h2>
                
                @if(!$transaction->trashed() && ($transaction->status === 'pending' || $transaction->status === 'returned'))
                    @if(auth()->user()->role === 'mayor')
                    <div class="space-y-4">
                        <div class="flex items-center gap-6 mb-2 border-b border-slate-100 pb-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mayor_action" value="decision"
                                       class="w-4 h-4 text-blue-600"
                                       onchange="toggleMayorAction('decision')" checked>
                                <span class="text-sm font-bold text-slate-700">اتخاذ قرار (اعتماد / رفض)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="mayor_action" value="forward"
                                       class="w-4 h-4 text-blue-600"
                                       onchange="toggleMayorAction('forward')">
                                <span class="text-sm font-bold text-slate-700">إعادة توجيه المعاملة</span>
                            </label>
                        </div>

                        <div id="mayor-decision">
                            <form action="{{ route('transactions.status.update', $transaction->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 mb-1">رأي رئيس البلدية وتوجيهاته</label>
                                    <textarea name="mayor_opinion" rows="3" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="ملاحظات رئيس البلدية..."></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="submit" name="status" value="approved" class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-blue-700 text-center">اعتماد وموافقة نهائية</button>
                                    <button type="submit" name="status" value="rejected" class="flex-1 bg-rose-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-rose-700 text-center">رفض المعاملة وإغلاقها</button>
                                </div>
                            </form>
                        </div>

                        <div id="mayor-forward" class="hidden">
                            @php
                                $assignedDeptIds = $transaction->departmentResponses->pluck('department_id')->toArray();
                                $availableDepts = $departments->filter(fn($d) => !in_array($d->id, $assignedDeptIds));
                            @endphp
                            <form action="{{ route('transactions.mayor.forward', $transaction->id) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <label class="block text-xs font-bold text-blue-600">اختر الأقسام لإعادة توجيه المعاملة إليها</label>
                                @if($availableDepts->isEmpty())
                                    <p class="text-xs text-slate-400 italic">جميع الأقسام مُضافة بالفعل لهذه المعاملة.</p>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-blue-100 rounded-lg p-3 bg-white">
                                        @foreach($availableDepts as $dept)
                                        <label class="flex items-center gap-2 cursor-pointer hover:bg-blue-50 p-1.5 rounded-lg transition">
                                            <input type="checkbox" name="forward_departments[]" value="{{ $dept->id }}"
                                                   class="w-4 h-4 text-blue-600 rounded">
                                            <span class="text-sm text-slate-700 font-medium">{{ $dept->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-600 mb-1 mt-2">ملاحظات التوجيه (اختياري)</label>
                                        <textarea name="mayor_note" rows="2" class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="أضف ملاحظة للتوجيه..."></textarea>
                                    </div>
                                    <div class="flex justify-end mt-3">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-black shadow-md transition-all flex items-center gap-1.5">
                                            <i data-lucide="forward" class="w-3.5 h-3.5"></i>
                                            إعادة توجيه المعاملة
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="bg-slate-50 p-4 rounded-xl border {{ $transaction->status === 'approved' ? 'border-emerald-200' : 'border-rose-200' }}">
                        <p class="text-sm font-bold mb-2">
                            الحالة النهائية: 
                            @if($transaction->status === 'approved') <span class="text-emerald-600">معتمدة ومغلقة بإيجاب</span>
                            @else <span class="text-rose-600">مرفوضة ومغلقة</span> @endif
                        </p>
                        @if($transaction->mayor_opinion)
                        <div class="text-sm text-slate-700 bg-white p-3 rounded-lg border border-slate-200">
                            <strong>توجيهات الرئيس:</strong> <br>
                            {{ $transaction->mayor_opinion }}
                        </div>
                        @endif
                    </div>
                @endif
            </div>
            @endif

        </div>

        <!-- العمود الأيسر (البيانات الجانبية والمرفقات وسجل التدقيق) -->
        <div class="space-y-6">
            
          

            <!-- المرفقات -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-black text-slate-800 flex items-center gap-2">
                        <i data-lucide="paperclip" class="w-4 h-4 text-slate-400"></i>
                        المرفقات
                    </h3>
                </div>
                
                <ul class="space-y-2 mb-4">
                    @forelse($transaction->attachments as $attachment)
                    <li class="flex items-center justify-between p-2 hover:bg-slate-50 rounded-lg border border-transparent hover:border-slate-200 transition-colors">
                        <div class="flex items-center gap-2">
                            <i data-lucide="file" class="w-4 h-4 text-slate-400"></i>
                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="text-xs font-bold text-[#14723a] hover:underline">{{ $attachment->file_label }}</a>
                        </div>
                    </li>
                    @empty
                    <li class="text-xs text-slate-400 text-center py-2">لا يوجد مرفقات</li>
                    @endforelse
                </ul>
            </div>

            <!-- سجل التدقيق المصغر -->
            <div class="bg-slate-800 rounded-2xl border border-slate-700 p-5 shadow-sm text-slate-300">
                <h3 class="text-sm font-black text-white mb-4 flex items-center gap-2 border-b border-slate-700 pb-2">
                    <i data-lucide="activity" class="w-4 h-4 text-emerald-400"></i>
                    سجل حركات المعاملة
                </h3>
                <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-600 before:to-transparent pr-4 border-r border-slate-600 mr-2">
                    @foreach($transaction->logs()->orderBy('created_at', 'desc')->take(5)->get() as $log)
                    <div class="relative pl-4">
                        <div class="absolute right-[-21px] top-1 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-slate-800 ring-2 ring-slate-800"></div>
                        <div class="text-[10px] text-slate-400 font-mono mb-0.5">{{ $log->created_at->format('Y-m-d H:i') }}</div>
                        <div class="text-xs text-white font-bold">{{ $log->action }}</div>
                        <div class="text-[10px] text-slate-400">بواسطة: {{ $log->user_name }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</main>

<script>
function toggleMayorAction(action) {
    const decisionDiv = document.getElementById('mayor-decision');
    const forwardDiv = document.getElementById('mayor-forward');
    
    if (action === 'decision') {
        if(decisionDiv) decisionDiv.classList.remove('hidden');
        if(forwardDiv) forwardDiv.classList.add('hidden');
    } else {
        if(decisionDiv) decisionDiv.classList.add('hidden');
        if(forwardDiv) forwardDiv.classList.remove('hidden');
    }
}
</script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-app-layout>
