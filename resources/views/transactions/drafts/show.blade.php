<x-transactions-layout>
    {{-- Header Action Bar --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index', ['tab' => 'drafts']) }}" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 text-slate-600 transition-colors" title="العودة للطلبات">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">مراجعة طلب وارد من الموقع</h2>
                    <span class="font-mono text-teal-800 font-bold bg-teal-50 px-2.5 py-0.5 rounded-lg border border-teal-200 text-xs">
                        {{ $draft->ticket_number ?? ('#'.$draft->id) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold
                        {{ $draft->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200' }}">
                        {{ $draft->status === 'pending' ? 'بانتظار المراجعة والتحويل' : 'تم التحويل' }}
                    </span>
                </div>
                <h1 class="text-xs font-bold text-tertiary mt-1">
                    {{ $draft->service_requested }} — مقدم من: <span class="text-slate-800 font-extrabold">{{ $draft->full_name }}</span>
                </h1>
            </div>
        </div>
        <div>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100">
                وارد منذ: {{ \Carbon\Carbon::parse($draft->created_at)->diffForHumans() }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ====== RIGHT COLUMN: Main Form (2/3) ====== --}}
        <div class="lg:col-span-2 space-y-5">
            <form action="{{ route('transactions.drafts.convert', $draft->id) }}" method="POST" class="space-y-5" id="complaint-form-root">
                @csrf

                @if(session('error') || $errors->any())
                <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3 text-xs text-rose-700 font-bold">
                    <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
                    <div>
                        @if(session('error'))<p>{{ session('error') }}</p>@endif
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                </div>
                @endif

                {{-- Section 1: Citizen Search --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-primary/5 border-b border-primary/15 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-sm font-black text-primary flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4 text-secondary"></i>
                            أولاً: ربط الطلب بمواطن مسجل في النظام
                        </h2>
                        <a href="{{ route('customers.citizens.create') }}" target="_blank" class="text-xs text-secondary hover:underline flex items-center gap-1 font-bold cursor-pointer">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                            إضافة مواطن جديد
                        </a>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- ID Search --}}
                            <div class="md:col-span-1">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهوية</label>
                                <div class="flex gap-2">
                                    <input type="text" id="citizen_id_input"
                                        value="{{ old('national_id', $draft->national_id) }}"
                                        maxlength="9" minlength="9" pattern="[0-9]{9}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9)"
                                        placeholder="مثال: 105234567"
                                        class="w-full text-sm px-3 py-2.5 font-semibold rounded-lg border border-slate-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" />
                                    <button type="button" id="search_btn" class="flex-shrink-0 bg-primary hover:bg-primary-dark text-white px-4 rounded-lg text-sm font-black transition-all">
                                        <i data-lucide="search" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">اضغط Enter أو أيقونة البحث</p>
                            </div>

                            {{-- Name (auto-filled) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المواطن الكامل</label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute right-3 top-2.5 w-4 h-4 text-slate-400"></i>
                                    <input type="text" id="citizen_name" readonly value="{{ $draft->full_name }}"
                                        class="w-full text-sm pr-9 pl-3 py-2.5 font-semibold rounded-lg border border-slate-200 bg-slate-50 text-slate-600" />
                                </div>
                            </div>

                            {{-- Phone (auto-filled) --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الجوال</label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute right-3 top-2.5 w-4 h-4 text-slate-400"></i>
                                    <input type="text" id="citizen_phone" readonly value="{{ $draft->phone }}"
                                        class="w-full text-sm pr-9 pl-3 py-2.5 font-semibold rounded-lg border border-slate-200 bg-slate-50 text-slate-600" />
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="citizen_id" id="hidden_citizen_id">
                        <select name="property_id" id="property_select" class="hidden"></select>

                        {{-- Property Display --}}
                        <div class="bg-teal-50/50 border border-teal-200 rounded-xl p-4 hidden" id="selected-property-display-container">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-black text-primary flex items-center gap-1.5">
                                    <i data-lucide="building-2" class="w-4 h-4 text-secondary"></i>
                                    العقار المختار
                                </p>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-slate-500 font-semibold">تبديل:</span>
                                    <select id="selected_property_switch" class="bg-white border border-slate-300 rounded-md px-2 py-1 text-[11px] font-bold text-slate-700 outline-none focus:border-primary"></select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-semibold text-slate-600">
                                <div><span class="block text-[10px] text-slate-400 mb-0.5">الحالة</span><span id="selected_property_status" class="text-primary font-bold">-</span></div>
                                <div><span class="block text-[10px] text-slate-400 mb-0.5">رقم القطعة</span><span id="selected_piece" class="font-bold text-slate-800">-</span></div>
                                <div><span class="block text-[10px] text-slate-400 mb-0.5">رقم القسيمة</span><span id="selected_voucher" class="font-bold text-slate-800">-</span></div>
                                <div><span class="block text-[10px] text-slate-400 mb-0.5">ملف التنظيم</span><span id="selected_reg_file" class="font-bold text-slate-800">-</span></div>
                                <div class="col-span-2 md:col-span-4"><span class="block text-[10px] text-slate-400 mb-0.5">العنوان الميداني</span><span id="selected_address" class="font-bold text-slate-800">-</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Validation Error Alert --}}
                <div id="validation-error-container" class="bg-rose-50 border border-rose-200 rounded-2xl p-4 hidden items-start gap-3 text-xs text-rose-700 font-bold shadow-sm">
                    <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
                    <span id="validation-error-message"></span>
                </div>

                {{-- Section 2: Conversion Decision --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="bg-slate-50 border-b border-gray-100 px-6 py-4">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <i data-lucide="git-branch" class="w-4 h-4 text-secondary"></i>
                            ثانياً: قرار التحويل والتوجيه
                        </h2>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Type Selector --}}
                        <div class="flex gap-3">
                            <label class="flex-1 flex items-center gap-3 bg-white p-3 rounded-xl border-2 border-primary cursor-pointer hover:bg-teal-50/30 transition shadow-sm" id="radio-transaction-label">
                                <input type="radio" name="convert_to" value="transaction" checked class="w-4 h-4 text-primary focus:ring-primary border-slate-300" onchange="toggleForm(this)">
                                <div>
                                    <span class="block text-xs font-black text-slate-800">معاملة رسمية</span>
                                    <span class="text-[10px] text-slate-400">تتطلب توجيه لأقسام</span>
                                </div>
                            </label>
                            <label class="flex-1 flex items-center gap-3 bg-white p-3 rounded-xl border-2 border-slate-200 cursor-pointer hover:border-rose-400 transition shadow-sm" id="radio-complaint-label">
                                <input type="radio" name="convert_to" value="complaint" class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300" onchange="toggleForm(this)">
                                <div>
                                    <span class="block text-xs font-black text-slate-800">شكوى عامة</span>
                                    <span class="text-[10px] text-slate-400">بدون توجيه للأقسام</span>
                                </div>
                            </label>
                        </div>

                        {{-- Type Selection Fields --}}
                        <div class="space-y-4 mt-5">
                            <div id="transaction-fields">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع المعاملة <span class="text-rose-500">*</span></label>
                                <select name="transaction_type_id" id="transaction_type_id" class="w-full text-sm px-4 py-2.5 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                    <option value="">ابحث عن نوع المعاملة...</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->sla_days }} أيام)</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="complaint-fields" style="display: none;">
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع الشكوى <span class="text-rose-500">*</span></label>
                                <select name="complaint_type_id" id="complaint_type_id" class="w-full text-sm px-4 py-2.5 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                    <option value="">اختر نوع الشكوى...</option>
                                    @foreach($complaintTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="departments-section">
                                <label class="block text-xs font-bold text-slate-700 mb-2">توجيه إلى الأقسام الفنية <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                    @foreach($departments as $dept)
                                        <label class="flex items-center gap-3 bg-slate-50 hover:bg-teal-50/30 p-3 rounded-xl border border-slate-200 hover:border-primary/40 cursor-pointer transition-all">
                                            <input type="checkbox" name="departments[]" value="{{ $dept->id }}" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded" />
                                            <span class="text-xs font-bold text-slate-700">{{ $dept->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex justify-end gap-3">
                        <a href="{{ route('transactions.index', ['tab' => 'drafts']) }}" class="text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 px-5 py-2.5 rounded-lg transition-all">إلغاء</a>
                        <button type="submit" class="flex items-center gap-2 text-xs font-black text-white bg-primary hover:bg-primary-dark px-6 py-2.5 rounded-xl transition-all shadow-md shadow-primary/20 cursor-pointer">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            اعتماد وتحويل الطلب
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ====== LEFT COLUMN: Read-Only Citizen Data (1/3) ====== --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Citizen Info Card --}}
            <div class="bg-white rounded-2xl border border-indigo-200 shadow-sm overflow-hidden">
                <div class="bg-indigo-500 px-5 py-3.5">
                    <h3 class="text-sm font-black text-white flex items-center gap-2">
                        <i data-lucide="globe" class="w-4 h-4"></i>
                        بيانات الطلب الوارد من الموقع
                    </h3>
                    <p class="text-[10px] text-indigo-200 mt-0.5">للقراءة فقط — كما أدخلها المواطن</p>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">اسم المواطن</span>
                        <p class="text-sm font-black text-slate-800">{{ $draft->full_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">رقم الهوية</span>
                            <p class="text-sm font-mono font-bold text-slate-700">{{ $draft->national_id ?: '—' }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">رقم الجوال</span>
                            <p class="text-sm font-mono font-bold text-slate-700">{{ $draft->phone ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Property Info Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 px-5 py-3">
                    <h3 class="text-xs font-black text-slate-600 flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        معلومات العقار
                    </h3>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 mb-1">العنوان / الموقع</span>
                        <p class="font-semibold text-slate-700">{{ $draft->property_address ?: '—' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 mb-1">رقم القطعة</span>
                            <p class="font-bold text-slate-800">{{ $draft->parcel_number ?: '—' }}</p>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 mb-1">رقم القسيمة</span>
                            <p class="font-bold text-slate-800">{{ $draft->plot_number ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Request Details Card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-slate-100 border-b border-slate-200 px-5 py-3">
                    <h3 class="text-xs font-black text-slate-600 flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        تفاصيل الطلب
                    </h3>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 mb-1">نوع الخدمة المطلوبة</span>
                        <p class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 font-black px-3 py-1.5 rounded-lg text-xs border border-indigo-100">
                            {{ $draft->service_requested }}
                        </p>
                    </div>
                    @if($draft->details)
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 mb-1">تفاصيل الطلب</span>
                        <p class="text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 whitespace-pre-wrap font-medium">{{ $draft->details }}</p>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-slate-400">تاريخ الإرسال</span>
                        <span class="font-bold text-slate-600">{{ \Carbon\Carbon::parse($draft->created_at)->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- END LEFT COLUMN --}}

    </div>
</main>



@push('scripts')
<script>
    window.defaultCitizenName = @json($draft->full_name);
    window.defaultCitizenPhone = @json($draft->phone);

    function toggleForm(radio) {
        const txFields = document.getElementById('transaction-fields');
        const cmpFields = document.getElementById('complaint-fields');
        const transLabel = document.getElementById('radio-transaction-label');
        const complLabel = document.getElementById('radio-complaint-label');

        if (radio.value === 'transaction') {
            txFields.style.display = 'block';
            cmpFields.style.display = 'none';
            transLabel.classList.replace('border-slate-200', 'border-primary');
            complLabel.classList.replace('border-rose-400', 'border-slate-200');
        } else {
            txFields.style.display = 'none';
            cmpFields.style.display = 'block';
            complLabel.classList.replace('border-slate-200', 'border-rose-400');
            transLabel.classList.replace('border-primary', 'border-slate-200');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const transactionTypesDepts = {
            @foreach($transactionTypes as $type)
                "{{ $type->id }}": @json($type->default_depts ?? []),
            @endforeach
        };

        const transactionTypeSelect = document.getElementById('transaction_type_id');
        if (transactionTypeSelect) {
            transactionTypeSelect.addEventListener('change', function() {
                const selectedId = this.value;
                let defaultDepts = transactionTypesDepts[selectedId] || [];
                if (typeof defaultDepts === 'string') {
                    try { defaultDepts = JSON.parse(defaultDepts); } catch (e) { defaultDepts = []; }
                }
                document.querySelectorAll('input[name="departments[]"]').forEach(cb => { cb.checked = false; });
                if (Array.isArray(defaultDepts)) {
                    defaultDepts.forEach(deptId => {
                        const cb = document.querySelector(`input[name="departments[]"][value="${deptId}"]`);
                        if (cb) cb.checked = true;
                    });
                }
            });
        }

        const formRoot = document.getElementById('complaint-form-root');
        const errorContainer = document.getElementById('validation-error-container');
        const errorMessage = document.getElementById('validation-error-message');
        const propertySelect = document.getElementById('property_select');

        if (propertySelect) {
            propertySelect.addEventListener('change', function() {
                if (this.value && errorContainer) {
                    errorContainer.classList.add('hidden');
                    errorContainer.classList.remove('flex');
                }
            });
        }

        if (formRoot) {
            formRoot.addEventListener('submit', function(e) {
                const convertTo = document.querySelector('input[name="convert_to"]:checked').value;
                const propertySelectVal = propertySelect ? propertySelect.value : '';
                const citizenIdVal = document.getElementById('hidden_citizen_id').value;
                const transactionTypeVal = document.getElementById('transaction_type_id') ? document.getElementById('transaction_type_id').value : '';
                const complaintTypeVal = document.getElementById('complaint_type_id') ? document.getElementById('complaint_type_id').value : '';
                const checkedDepartments = document.querySelectorAll('input[name="departments[]"]:checked').length;

                const showError = (msg) => {
                    e.preventDefault();
                    if (errorMessage && errorContainer) {
                        errorMessage.textContent = msg;
                        errorContainer.classList.remove('hidden');
                        errorContainer.classList.add('flex');
                        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                };

                if (checkedDepartments === 0) {
                    showError('يجب اختيار قسم فني واحد على الأقل لتحويل الطلب.');
                    return;
                }

                if (convertTo === 'transaction') {
                    if (!citizenIdVal) {
                        showError('يرجى ربط الطلب بمواطن مسجل أولاً (ابحث برقم الهوية)');
                        return;
                    }
                    if (!propertySelectVal) {
                        showError('يجب اختيار بيانات العقار لإتمام المعاملة');
                        return;
                    }
                    if (!transactionTypeVal) {
                        showError('يجب اختيار نوع المعاملة');
                        return;
                    }
                } else if (convertTo === 'complaint') {
                    if (!complaintTypeVal) {
                        showError('يجب اختيار نوع الشكوى');
                        return;
                    }
                }
                
                if (errorContainer) {
                    errorContainer.classList.add('hidden');
                    errorContainer.classList.remove('flex');
                }
            });
        }
    });
</script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('transaction_type_id')) {
            new TomSelect("#transaction_type_id", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "ابحث عن نوع المعاملة..."
            });
        }
        if (document.getElementById('complaint_type_id')) {
            new TomSelect("#complaint_type_id", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "ابحث عن نوع الشكوى..."
            });
        }
    });
</script>
@endpush
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</x-transactions-layout>
