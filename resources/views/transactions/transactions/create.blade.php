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

@section('title', 'تسجيل معاملة جديدة')


<div class="min-h-screen bg-slate-50">
    

    <main class="px-6 py-10">
        <div class="max-w-6xl mx-auto space-y-4">
            <form action="{{ route('transactions.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" id="complaint-form-root" enctype="multipart/form-data">
                @csrf

                <div class="bg-[#14723a] text-white p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-white/10 p-2 rounded-lg shrink-0">
                                <i data-lucide="file-plus" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold leading-snug">تسجيل معاملة جديدة</h2>
                                <p class="text-xs text-emerald-100 mt-1">مكتب قلم الجمهور - بلدية الزوايدة</p>
                                <a href="{{ route('transactions.index') }}" class="inline-flex md:hidden items-center gap-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-xl transition-all shadow-sm mt-3">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    <span>الرجوع للصفحة الرئيسية</span>
                                </a>
                            </div>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="hidden md:inline-flex shrink-0 items-center gap-2 text-sm font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 px-4 py-2 rounded-xl transition-all shadow-sm">
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            <span>الرجوع للصفحة الرئيسية</span>
                        </a>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    @if(session('error'))
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start space-x-3 space-x-reverse text-xs text-rose-700 font-bold">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start space-x-3 space-x-reverse text-xs text-rose-700 font-bold">
                            <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5"></i>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-black text-[#14723a] flex items-center gap-1.5">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span>أولاً: بيانات مقدم الطلب (المواطن)</span>
                            </h3>
                            <a href="{{ route('customers.create') }}" target="_blank" class="text-xs text-[#14723a] hover:underline flex items-center gap-1 font-bold cursor-pointer">
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                <span>إضافة مواطن جديد</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهوية الإلزامي <span class="text-rose-500">*</span></label>
                                <div class="flex gap-2">
                                    <input type="text" id="citizen_id_input" maxlength="9" minlength="9" pattern="[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); if(this.value && !this.value.match(/^[0-9]{9}$/)){ this.setCustomValidity('يرجى إدخال رقم هوية صحيح مكون من 9 أرقام'); } else { this.setCustomValidity(''); }" placeholder="مثال: 105234567" class="w-full text-sm px-4 py-3 font-semibold rounded-lg border border-slate-300 focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" required />
                                    <button type="button" id="search_btn" class="flex items-center justify-center bg-[#14723a] hover:bg-[#14723a]/90 text-white px-4 rounded-lg border border-[#14723a] text-sm font-black transition-all">بحث</button>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1">اضغط Enter أو زر البحث للاستعلام التلقائي.</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">اسم المواطن الكامل <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" id="citizen_name" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="الاسم رباعي كما في الهوية" required />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهاتف المحمول <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <i data-lucide="phone" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" id="citizen_phone" maxlength="10" minlength="10" pattern="^(059|056)[0-9]{7}$" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10); if(this.value && !this.value.match(/^(059|056)[0-9]{7}$/)){ this.setCustomValidity('يرجى إدخال رقم جوال صحيح يبدأ بـ 059 أو 056 ومكون من 10 أرقام'); } else { this.setCustomValidity(''); }" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: 0599123456" required />
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">حالة مقدم الطلب (العلاقة بالعقار) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <i data-lucide="user" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <select id="citizen_status" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] transition-all" required>
                                        <option value="owner">مالك العقار</option>
                                        <option value="beneficiary">منتفع بالعقار</option>
                                        <option value="other">أخرى (وكيل/قريب)</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="citizen_id" id="hidden_citizen_id">
                            <select name="property_id" id="property_select" class="hidden"></select>
                        </div>

                        <div class="mt-4 bg-[#14723a]/5 border border-[#14723a]/15 rounded-xl p-4 space-y-3" id="selected-property-display-container">
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-black text-[#14723a] flex items-center gap-1.5">
                                    <i data-lucide="building" class="w-4 h-4"></i>
                                    <span>بيانات العقار المختار لمقدم المعاملة:</span>
                                </p>
                                <div class="flex items-center gap-1.5 text-xs">
                                    <span class="text-slate-500 font-semibold">تبديل العقار المختار:</span>
                                    <select id="selected_property_switch" class="bg-white border border-slate-300 rounded-md px-2 py-1 text-[11px] font-bold text-slate-700 outline-none focus:border-[#14723a]"></select>
                                </div>
                            </div>
                            <div id="selected-property-card" class="bg-white border border-emerald-500/30 p-4 rounded-xl text-xs flex flex-col justify-between shadow-sm">
                                <div class="flex items-center justify-between w-full mb-2">
                                    <span class="font-extrabold text-[#14723a] text-sm">العقار المختار حالياً</span>
                                    <span id="selected_property_status" class="px-2.5 py-0.5 bg-emerald-100 text-[#14723a] rounded-full text-[10px] font-bold">-</span>
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

                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-2xl p-5 space-y-4">
                        <h3 class="text-sm font-black text-[#14723a] flex items-center gap-1.5 border-b border-[#14723a]/10 pb-2">
                            <i data-lucide="building" class="w-4 h-4"></i>
                            <span>ثانياً: البيانات العقارية والتنظيمية</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم القطعة</label>
                                <div class="relative">
                                    <i data-lucide="building" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="piece_number" id="piece_number" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: 42" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم القسيمة</label>
                                <div class="relative">
                                    <i data-lucide="building" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="voucher_number" id="voucher_number" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: 145" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم ملف التنظيم (إن وجد)</label>
                                <div class="relative">
                                    <i data-lucide="file-text" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="regulation_file_number" id="regulation_file_number" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: REG-2026-88" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم اشتراك المياه (إن وجد)</label>
                                <div class="relative">
                                    <i data-lucide="file-text" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="subscription_number" id="subscription_number" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: SUB-4402" />
                                </div>
                            </div>
                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-slate-800 mb-1.5">العنوان التفصيلي للعقار (اختياري)</label>
                                <div class="relative">
                                    <i data-lucide="map-pin" class="absolute right-3.5 top-3.5 w-5 h-5 text-slate-400"></i>
                                    <input type="text" name="detailed_address" id="detailed_address" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="الحي، اسم الشارع، معالم مميزة" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-2xl p-5 space-y-4">
                        <h3 class="text-sm font-black text-[#14723a] flex items-center gap-1.5 border-b border-[#14723a]/10 pb-2">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>ثالثاً: تفاصيل المعاملة والمستندات</span>
                        </h3>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">نوع المعاملة المطلوب تسجيلها في النظام <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="building" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                <select name="transaction_type_id" id="transaction_type_id" required class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a]">
                                    <option value="">اختر نوع المعاملة</option>
                                    @foreach($transactionTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->sla_days }} أيام)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1.5">تفاصيل موضوع الطلب <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="file-text" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                <textarea name="details" id="details" rows="4" required class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none resize-none" placeholder="اكتب هنا شرحاً مفصلاً للمشكلة والطلب المطلوب دراسته..."></textarea>
                            </div>
                        </div>

                       

                    </div>

                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="text-xs font-bold text-[#14723a]">الأقسام المعنية بالتوجيه الفني المتوازي</span>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-[#14723a] leading-relaxed pr-1">
                            يتم توجيه المعاملة للأقسام المختارة معاً فور الحفظ لبدء الدراسة الفنية متوازياً
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                            @foreach($departments as $dept)
                                <label class="flex items-start space-x-3 space-x-reverse bg-white p-3 rounded-lg border hover:bg-emerald-50/20 cursor-pointer transition-all">
                                    <input type="checkbox" name="departments[]" value="{{ $dept->id }}" class="mt-0.5 h-4 w-4 text-[#14723a] focus:ring-[#14723a] border-slate-300 rounded" />
                                    <div>
                                        <span class="block text-xs font-bold text-slate-800">{{ $dept->name }}</span>
                                        <span class="block text-[10px] text-slate-400 mt-0.5">&nbsp;</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>



                     <div class="bg-white border border-[#14723a]/15 rounded-xl p-4 space-y-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1.5 space-x-reverse">
                                    <span class="text-xs font-bold text-slate-700">المرفقات والمستندات المساندة</span>
                                    <span class="text-[10px] text-slate-400 bg-slate-200/50 px-2 py-0.5 rounded-full font-medium">مسموح: PDF, DOC, DOCX, JPG, PNG, JPEG (بحد أقصى 4 ميجابايت)</span>
                                </div>
                                <button type="button" id="add-attachment-btn" class="flex items-center space-x-1 space-x-reverse text-xs font-bold text-[#14723a] bg-emerald-100/80 hover:bg-emerald-100 border border-emerald-200 px-3 py-1.5 rounded-lg transition-all cursor-pointer">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>إضافة مستند آخر</span>
                                </button>
                            </div>
                            <div class="space-y-2.5" id="attachments-container">
                                <div class="attachment-row flex flex-col md:flex-row items-center gap-2 bg-slate-50/50 p-2.5 rounded-lg border border-slate-200">
                                    <span class="attachment-label-num text-[11px] font-bold text-slate-500 md:w-12 text-center bg-slate-100 py-1 rounded">مرفق 1</span>
                                    <input type="text" name="attachments[0][file_label]" placeholder="مثال: صورة الهوية، طابو العقار" class="flex-grow text-sm font-semibold px-3 py-2 rounded border border-slate-300 bg-white outline-none focus:border-[#14723a]" />
                                    <div class="flex items-center gap-2 w-full md:w-auto relative">
                                        <input type="file" name="attachments[0][file]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden file-input" />
                                        <span class="file-name-display text-[10px] text-slate-400 bg-slate-50 px-2 py-1.5 border border-dashed border-slate-200 rounded font-mono truncate max-w-[150px]">لم يتم اختيار ملف</span>
                                        <button type="button" class="browse-btn text-xs bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded border border-slate-300 text-slate-600 transition-all cursor-pointer">تصفح...</button>
                                        <button type="button" class="remove-attachment-btn hidden text-rose-500 hover:bg-rose-50 p-1.5 rounded transition-all cursor-pointer" title="حذف المرفق">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                </div>

                <div class="bg-slate-50 border-t border-slate-100 p-4 flex justify-end space-x-3 space-x-reverse">
                    <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-slate-600 bg-white hover:bg-slate-100 border border-slate-200 px-4 py-2.5 rounded-lg transition-all">إلغاء وتراجع</a>
                    <button type="submit" class="flex items-center space-x-1.5 space-x-reverse text-xs font-bold text-white bg-[#14723a] hover:bg-[#14723a]/90 px-5 py-2.5 rounded-lg transition-all shadow-md shadow-emerald-700/10">حفظ وإرسال المعاملة</button>
                </div>
            </form>
        </div>
    </main>
</div>

{{-- @include('transactions.partials.citizen_modal') --}}

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
                
                // Uncheck all
                document.querySelectorAll('input[name="departments[]"]').forEach(cb => {
                    cb.checked = false;
                });

                // Check defaults
                if (Array.isArray(defaultDepts)) {
                    defaultDepts.forEach(deptId => {
                        const cb = document.querySelector(`input[name="departments[]"][value="${deptId}"]`);
                        if (cb) {
                            cb.checked = true;
                        }
                    });
                }
            });
        }

        // Attachments Logic
        const attachmentsContainer = document.getElementById('attachments-container');
        const addAttachmentBtn = document.getElementById('add-attachment-btn');
        let attachmentCount = 1;

        function bindAttachmentEvents(row) {
            const browseBtn = row.querySelector('.browse-btn');
            const fileInput = row.querySelector('.file-input');
            const fileNameDisplay = row.querySelector('.file-name-display');
            const removeBtn = row.querySelector('.remove-attachment-btn');

            browseBtn.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                 if (this.files && this.files.length > 0) {
                     const file = this.files[0];
                     const maxSize = 4 * 1024 * 1024; // 4MB
                     const allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png'];
                     const extension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();

                     if (file.size > maxSize) {
                         alert('عذراً، حجم المرفق يجب أن لا يتجاوز 4 ميغابايت.');
                         this.value = '';
                         fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                         fileNameDisplay.classList.add('text-slate-400');
                         fileNameDisplay.classList.remove('text-slate-700', 'font-semibold');
                         return;
                     }

                     if (!allowedExtensions.includes(extension)) {
                         alert('عذراً، نوع الملف غير مسموح به. الأنواع المسموحة هي: PDF, DOC, DOCX, JPG, PNG, JPEG فقط.');
                         this.value = '';
                         fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                         fileNameDisplay.classList.add('text-slate-400');
                         fileNameDisplay.classList.remove('text-slate-700', 'font-semibold');
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
                if (numSpan) {
                    numSpan.textContent = `مرفق ${index + 1}`;
                }
                
                const labelInput = row.querySelector('input[name^="attachments["][name$="][file_label]"]');
                const fileInput = row.querySelector('.file-input');
                
                if (labelInput) labelInput.name = `attachments[${index}][file_label]`;
                if (fileInput) fileInput.name = `attachments[${index}][file]`;
                
                // Hide remove button for first item if it's the only one
                const removeBtn = row.querySelector('.remove-attachment-btn');
                if (removeBtn) {
                    if (rows.length === 1) {
                        removeBtn.classList.add('hidden');
                    } else {
                        removeBtn.classList.remove('hidden');
                    }
                }
            });
            attachmentCount = rows.length;
        }

        if (addAttachmentBtn && attachmentsContainer) {
            // Bind initial row
            const initialRow = attachmentsContainer.querySelector('.attachment-row');
            if (initialRow) bindAttachmentEvents(initialRow);

            addAttachmentBtn.addEventListener('click', () => {
                const newRow = initialRow.cloneNode(true);
                
                // Reset values
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
                
                // Re-initialize feather icons in the new row if needed (assuming lucide)
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons({ root: newRow });
                }
            });
        }
    });

    // Custom form validation
    const formRoot = document.getElementById('complaint-form-root');
    if (formRoot) {
        formRoot.addEventListener('submit', function(e) {
            const citizenId = document.getElementById('hidden_citizen_id').value;
            const propertyId = document.getElementById('property_select').value;
            
            if (!citizenId) {
                e.preventDefault();
                alert('عذراً، يجب الاستعلام عن المواطن أولاً وإضافته للمعاملة.');
                return;
            }
            
            if (!propertyId) {
                e.preventDefault();
                alert('عذراً، يجب إضافة واختيار بيانات العقار المرتبطة بالمعاملة أولاً.');
                return;
            }
        });
    }
</script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect("#transaction_type_id",{
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "ابحث عن نوع المعاملة..."
        });
    });
</script>
@endpush

</x-app-layout>
