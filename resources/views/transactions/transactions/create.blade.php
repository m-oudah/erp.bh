<x-transactions-layout>
    <div class="max-w-6xl mx-auto space-y-4">
        <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" id="complaint-form-root" enctype="multipart/form-data">
            @csrf

            {{-- Header Banner --}}
            <div class="bg-gradient-to-r from-teal-800 via-primary to-primary text-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5">
                        <div class="bg-white/15 p-2.5 rounded-2xl shrink-0 backdrop-blur-xs">
                            <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black leading-snug">تسجيل معاملة رسمية جديدة</h2>
                            <p class="text-xs text-teal-100 mt-1 font-medium">مكتب قلم الجمهور - بلدية بيت حانون</p>
                        </div>
                    </div>
                    <a href="{{ route('transactions.index') }}" class="hidden sm:inline-flex shrink-0 items-center gap-1.5 text-xs font-bold text-slate-700 bg-white/90 hover:bg-white px-4 py-2 rounded-xl transition-all shadow-sm">
                        <span>العودة للقائمة</span>
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="p-6 space-y-6">
                @if(session('error'))
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3 text-xs text-rose-700 font-bold">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3 text-xs text-rose-700 font-bold">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Section 1: Citizen Info --}}
                <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-black text-primary flex items-center gap-2">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>أولاً: بيانات مقدم الطلب (المواطن)</span>
                        </h3>
                        <a href="{{ route('customers.citizens.create') }}" target="_blank" class="text-xs text-secondary hover:text-secondary-dark flex items-center gap-1 font-bold cursor-pointer transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <span>إضافة مواطن جديد</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهوية الإلزامي <span class="text-rose-500">*</span></label>
                            <div class="flex gap-2">
                                <input type="text" id="citizen_id_input" maxlength="9" minlength="9" pattern="[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); if(this.value && !this.value.match(/^[0-9]{9}$/)){ this.setCustomValidity('يرجى إدخال رقم هوية صحيح مكون من 9 أرقام'); } else { this.setCustomValidity(''); }" placeholder="مثال: 105234567" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" required />
                                <button type="button" id="search_btn" class="flex items-center justify-center bg-primary hover:bg-primary-dark text-white px-4 rounded-xl border border-primary text-xs font-bold transition-all shadow-xs cursor-pointer">بحث</button>
                            </div>
                            <p class="text-[10px] text-slate-500 mt-1">اضغط Enter أو زر البحث للاستعلام التلقائي.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">اسم المواطن الكامل <span class="text-rose-500">*</span></label>
                            <input type="text" id="citizen_name" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="الاسم رباعي كما في الهوية" required />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهاتف المحمول <span class="text-rose-500">*</span></label>
                            <input type="text" id="citizen_phone" maxlength="10" minlength="10" pattern="^(059|056)[0-9]{7}$" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10); if(this.value && !this.value.match(/^(059|056)[0-9]{7}$/)){ this.setCustomValidity('يرجى إدخال رقم جوال صحيح يبدأ بـ 059 أو 056 ومكون من 10 أرقام'); } else { this.setCustomValidity(''); }" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="مثال: 0599123456" required />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">حالة مقدم الطلب (العلاقة بالعقار) <span class="text-rose-500">*</span></label>
                            <select id="citizen_status" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" required>
                                <option value="owner">مالك العقار</option>
                                <option value="beneficiary">منتفع بالعقار</option>
                                <option value="other">أخرى (وكيل/قريب)</option>
                            </select>
                        </div>

                        <input type="hidden" name="citizen_id" id="hidden_citizen_id">
                        <select name="property_id" id="property_select" class="hidden"></select>
                    </div>

                    {{-- Selected property card --}}
                    <div class="mt-4 bg-primary/5 border border-primary/20 rounded-xl p-4 space-y-3" id="selected-property-display-container">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-black text-primary flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>بيانات العقار المختار لمقدم المعاملة:</span>
                            </p>
                            <div class="flex items-center gap-1.5 text-xs">
                                <span class="text-slate-500 font-semibold">تبديل العقار المختار:</span>
                                <select id="selected_property_switch" class="bg-white border border-slate-300 rounded-lg px-2 py-1 text-[11px] font-bold text-slate-700 outline-none focus:border-primary"></select>
                            </div>
                        </div>
                        <div id="selected-property-card" class="bg-white border border-primary/30 p-4 rounded-xl text-xs flex flex-col justify-between shadow-xs">
                            <div class="flex items-center justify-between w-full mb-2">
                                <span class="font-extrabold text-primary text-sm">العقار المختار حالياً</span>
                                <span id="selected_property_status" class="px-2.5 py-0.5 bg-teal-50 text-teal-800 border border-teal-200 rounded-full text-[10px] font-bold">-</span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 font-semibold text-slate-700">
                                <p>رقم القطعة: <span id="selected_piece" class="font-bold text-slate-950">-</span></p>
                                <p>رقم القسيمة: <span id="selected_voucher" class="font-bold text-slate-950">-</span></p>
                                <p>رقم ملف التنظيم: <span id="selected_reg_file" class="font-bold text-slate-950">-</span></p>
                                <p class="md:col-span-3">الموقع الجغرافي الميداني: <span id="selected_address" class="font-bold text-slate-950">-</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Building & Regulation Info --}}
                <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 space-y-4">
                    <h3 class="text-sm font-black text-primary flex items-center gap-2 border-b border-primary/10 pb-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>ثانياً: البيانات العقارية والتنظيمية</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم القطعة</label>
                            <input type="text" name="piece_number" id="piece_number" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="مثال: 42" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم القسيمة</label>
                            <input type="text" name="voucher_number" id="voucher_number" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="مثال: 145" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم ملف التنظيم (إن وجد)</label>
                            <input type="text" name="regulation_file_number" id="regulation_file_number" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="مثال: REG-2026-88" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم اشتراك المياه (إن وجد)</label>
                            <input type="text" name="subscription_number" id="subscription_number" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="مثال: SUB-4402" />
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">العنوان التفصيلي للعقار (اختياري)</label>
                            <input type="text" name="detailed_address" id="detailed_address" class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="الحي، اسم الشارع، معالم مميزة" />
                        </div>
                    </div>
                </div>

                {{-- Section 3: Transaction Details --}}
                <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 space-y-4">
                    <h3 class="text-sm font-black text-primary flex items-center gap-2 border-b border-primary/10 pb-2">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>ثالثاً: تفاصيل المعاملة والمستندات</span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">نوع المعاملة المطلوب تسجيلها في النظام <span class="text-rose-500">*</span></label>
                        <select name="transaction_type_id" id="transaction_type_id" required class="w-full text-xs font-bold px-3 py-2.5 rounded-xl border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">اختر نوع المعاملة</option>
                            @foreach($transactionTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->sla_days }} أيام SLA)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1.5">تفاصيل وموضوع الطلب <span class="text-rose-500">*</span></label>
                        <textarea name="details" id="details" rows="4" required class="w-full text-xs font-medium px-3 py-2.5 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none resize-none leading-relaxed" placeholder="اكتب هنا شرحاً مفصلاً للمشكلة والطلب المطلوب دراسته..."></textarea>
                    </div>
                </div>

                {{-- Section 4: Departments Assignment --}}
                <div class="bg-primary/5 border border-primary/20 rounded-xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-primary">الأقسام المعنية بالتوجيه الفني المباشر</span>
                    </div>
                    <p class="text-xs text-primary/80 leading-relaxed">
                        يتم توجيه المعاملة للأقسام المختارة معاً فور الحفظ لبدء الدراسة الفنية متوازياً
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                        @foreach($departments as $dept)
                            <label class="flex items-center gap-2.5 bg-white p-3 rounded-xl border border-gray-100 hover:border-primary/40 cursor-pointer transition-all shadow-2xs">
                                <input type="checkbox" name="departments[]" value="{{ $dept->name }}" class="h-4 w-4 text-primary focus:ring-primary border-slate-300 rounded" />
                                <span class="text-xs font-bold text-slate-800">{{ $dept->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Section 5: Attachments --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 space-y-4 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-700">المرفقات والمستندات المساندة</span>
                            <span class="text-[10px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full font-medium">مسموح: PDF, DOC, DOCX, JPG, PNG (حد أقصى 4MB)</span>
                        </div>
                        <button type="button" id="add-attachment-btn" class="flex items-center gap-1 text-xs font-bold text-primary bg-teal-50 hover:bg-teal-100 border border-teal-200 px-3 py-1.5 rounded-xl transition-all cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>إضافة مستند آخر</span>
                        </button>
                    </div>
                    <div class="space-y-2.5" id="attachments-container">
                        <div class="attachment-row flex flex-col md:flex-row items-center gap-2 bg-slate-50/70 p-2.5 rounded-xl border border-slate-200">
                            <span class="attachment-label-num text-[11px] font-bold text-slate-500 md:w-14 text-center bg-white border border-slate-200 py-1 rounded-lg">مرفق 1</span>
                            <input type="text" name="attachments[0][file_label]" placeholder="مثال: صورة الهوية، مخطط موقع، طابو" class="flex-grow text-xs font-bold px-3 py-2 rounded-lg border border-slate-300 bg-white outline-none focus:border-primary" />
                            <div class="flex items-center gap-2 w-full md:w-auto relative">
                                <input type="file" name="attachments[0][file]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden file-input" />
                                <span class="file-name-display text-[10px] text-slate-400 bg-white px-2 py-1.5 border border-dashed border-slate-200 rounded font-mono truncate max-w-[150px]">لم يتم اختيار ملف</span>
                                <button type="button" class="browse-btn text-xs font-bold bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 transition-all cursor-pointer">تصفح...</button>
                                <button type="button" class="remove-attachment-btn hidden text-rose-500 hover:bg-rose-50 p-1.5 rounded-lg transition-all cursor-pointer" title="حذف المرفق">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-slate-50/80 border-t border-slate-100 -mx-6 -mb-6 p-4 flex items-center justify-end gap-3 rounded-b-3xl">
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-xl transition-all">إلغاء وتراجع</a>
                    <button type="submit" class="flex items-center gap-2 text-xs font-bold text-white bg-primary hover:bg-teal-700 px-6 py-2.5 rounded-xl transition-all shadow-sm shadow-primary/20 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>حفظ وإرسال المعاملة</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    @include('transactions.partials.citizen_modal')

    @push('scripts')
    <script>
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
                        try {
                            defaultDepts = JSON.parse(defaultDepts);
                        } catch (e) {
                            defaultDepts = [];
                        }
                    }
                    
                    document.querySelectorAll('input[name="departments[]"]').forEach(cb => {
                        cb.checked = false;
                    });

                    if (Array.isArray(defaultDepts)) {
                        defaultDepts.forEach(deptId => {
                            const cb = document.querySelector(`input[name="departments[]"][value="${deptId}"]`);
                            if (cb) cb.checked = true;
                        });
                    }
                });
            }

            // Attachments Logic
            const attachmentsContainer = document.getElementById('attachments-container');
            const addAttachmentBtn = document.getElementById('add-attachment-btn');

            function bindAttachmentEvents(row) {
                const browseBtn = row.querySelector('.browse-btn');
                const fileInput = row.querySelector('.file-input');
                const fileNameDisplay = row.querySelector('.file-name-display');
                const removeBtn = row.querySelector('.remove-attachment-btn');

                browseBtn.addEventListener('click', () => fileInput.click());

                fileInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        const file = this.files[0];
                        const maxSize = 4 * 1024 * 1024;
                        const allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png'];
                        const extension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

                        if (file.size > maxSize) {
                            alert('عذراً، حجم المرفق يجب أن لا يتجاوز 4 ميغابايت.');
                            this.value = '';
                            fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                            return;
                        }

                        if (!allowedExtensions.includes(extension)) {
                            alert('عذراً، نوع الملف غير مسموح به.');
                            this.value = '';
                            fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                            return;
                        }

                        fileNameDisplay.textContent = file.name;
                        fileNameDisplay.classList.remove('text-slate-400');
                        fileNameDisplay.classList.add('text-slate-700', 'font-semibold');
                    } else {
                        fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                        fileNameDisplay.classList.add('text-slate-400');
                        fileNameDisplay.classList.remove('text-slate-700', 'font-semibold');
                    }
                });

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        row.remove();
                        updateAttachmentLabels();
                    });
                }
            }

            function updateAttachmentLabels() {
                const rows = attachmentsContainer.querySelectorAll('.attachment-row');
                rows.forEach((row, index) => {
                    const numSpan = row.querySelector('.attachment-label-num');
                    if (numSpan) numSpan.textContent = `مرفق ${index + 1}`;
                    
                    const labelInput = row.querySelector('input[name^="attachments["][name$="][file_label]"]');
                    const fileInput = row.querySelector('.file-input');
                    
                    if (labelInput) labelInput.name = `attachments[${index}][file_label]`;
                    if (fileInput) fileInput.name = `attachments[${index}][file]`;
                    
                    const removeBtn = row.querySelector('.remove-attachment-btn');
                    if (removeBtn) {
                        if (rows.length === 1) {
                            removeBtn.classList.add('hidden');
                        } else {
                            removeBtn.classList.remove('hidden');
                        }
                    }
                });
            }

            if (addAttachmentBtn && attachmentsContainer) {
                const initialRow = attachmentsContainer.querySelector('.attachment-row');
                if (initialRow) bindAttachmentEvents(initialRow);

                addAttachmentBtn.addEventListener('click', () => {
                    const newRow = initialRow.cloneNode(true);
                    const labelInput = newRow.querySelector('input[type="text"]');
                    if (labelInput) labelInput.value = '';
                    
                    const fileInput = newRow.querySelector('.file-input');
                    if (fileInput) fileInput.value = '';
                    
                    const fileNameDisplay = newRow.querySelector('.file-name-display');
                    if (fileNameDisplay) {
                        fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                        fileNameDisplay.classList.add('text-slate-400');
                        fileNameDisplay.classList.remove('text-slate-700', 'font-semibold');
                    }

                    attachmentsContainer.appendChild(newRow);
                    bindAttachmentEvents(newRow);
                    updateAttachmentLabels();
                });
            }
        });
    </script>
    @endpush
</x-transactions-layout>
