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

@section('title', 'مراجعة الطلب الوارد من الموقع')




{{-- Sticky Back Bar --}}
<header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-4 space-x-reverse">
            <a href="{{ route('transactions.index', ['tab' => 'drafts']) }}" class="w-10 h-10 bg-slate-50 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl flex items-center justify-center border border-slate-200 transition-colors">
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-base font-black text-slate-800 leading-tight">
                    مراجعة طلب وارد من الموقع
                    <span class="font-mono text-indigo-600">#{{ $draft->id }}</span>
                </h1>
                <p class="text-[10px] text-slate-500 font-medium">
                    {{ $draft->service_requested }} — مقدم من: {{ $draft->full_name }}
                    <span class="inline-flex items-center gap-1 mr-2 px-2 py-0.5 rounded-full text-[10px] font-bold
                        {{ $draft->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $draft->status === 'pending' ? 'قيد المراجعة' : 'تم التحويل' }}
                    </span>
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <span class="text-xs text-slate-400 font-medium self-center">
                {{ \Carbon\Carbon::parse($draft->created_at)->diffForHumans() }}
            </span>
        </div>
    </div>
</header>

<main class="flex-grow p-6 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

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
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-[#14723a]/5 border-b border-[#14723a]/15 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-sm font-black text-[#14723a] flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            أولاً: ربط الطلب بمواطن مسجل في النظام
                        </h2>
                        <a href="{{ route('customers.create') }}" target="_blank" class="text-xs text-[#14723a] hover:underline flex items-center gap-1 font-bold cursor-pointer">
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
                                        class="w-full text-sm px-3 py-2.5 font-semibold rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" />
                                    <button type="button" id="search_btn" class="flex-shrink-0 bg-[#14723a] hover:bg-[#14723a]/90 text-white px-4 rounded-lg text-sm font-black transition-all">
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
                        <div class="bg-emerald-50/50 border border-emerald-200 rounded-xl p-4 hidden" id="selected-property-display-container">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-xs font-black text-[#14723a] flex items-center gap-1.5">
                                    <i data-lucide="building-2" class="w-4 h-4"></i>
                                    العقار المختار
                                </p>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-slate-500 font-semibold">تبديل:</span>
                                    <select id="selected_property_switch" class="bg-white border border-slate-300 rounded-md px-2 py-1 text-[11px] font-bold text-slate-700 outline-none focus:border-[#14723a]"></select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs font-semibold text-slate-600">
                                <div><span class="block text-[10px] text-slate-400 mb-0.5">الحالة</span><span id="selected_property_status" class="text-[#14723a] font-bold">-</span></div>
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
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-slate-50 border-b border-slate-200 px-6 py-4">
                        <h2 class="text-sm font-black text-slate-800 flex items-center gap-2">
                            <i data-lucide="git-branch" class="w-4 h-4 text-[#14723a]"></i>
                            ثانياً: قرار التحويل والتوجيه
                        </h2>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Type Selector --}}
                        <div class="flex gap-3">
                            <label class="flex-1 flex items-center gap-3 bg-white p-3 rounded-xl border-2 border-[#14723a] cursor-pointer hover:bg-emerald-50/30 transition shadow-sm" id="radio-transaction-label">
                                <input type="radio" name="convert_to" value="transaction" checked class="w-4 h-4 text-[#14723a] focus:ring-[#14723a] border-slate-300" onchange="toggleForm(this)">
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
                                <select name="transaction_type_id" id="transaction_type_id" class="w-full text-sm px-4 py-2.5 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a]">
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
                                        <label class="flex items-center gap-3 bg-slate-50 hover:bg-emerald-50/30 p-3 rounded-xl border border-slate-200 hover:border-[#14723a]/30 cursor-pointer transition-all">
                                            <input type="checkbox" name="departments[]" value="{{ $dept->id }}" class="h-4 w-4 text-[#14723a] focus:ring-[#14723a] border-slate-300 rounded" />
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
                        <button type="submit" class="flex items-center gap-2 text-xs font-black text-white bg-[#14723a] hover:bg-[#14723a]/90 px-6 py-2.5 rounded-lg transition-all shadow-md">
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
            transLabel.classList.replace('border-slate-200', 'border-[#14723a]');
            complLabel.classList.replace('border-rose-400', 'border-slate-200');
        } else {
            txFields.style.display = 'none';
            cmpFields.style.display = 'block';
            complLabel.classList.replace('border-slate-200', 'border-rose-400');
            transLabel.classList.replace('border-[#14723a]', 'border-slate-200');
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
</x-app-layout>
