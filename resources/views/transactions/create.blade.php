@extends('layouts.app')

@section('title', 'تسجيل شكوى جديدة')

@section('content')
<div class="min-h-screen bg-slate-50">
    @include('layouts.partials.header')

    <main class="px-6 py-10">
        <div class="max-w-6xl mx-auto space-y-4">
            <form action="{{ route('complaints.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" id="new-complaint-form-root">
                                @csrf

                                <!-- Header -->
                                <div class="bg-[#14723a] text-white p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <div class="bg-white/10 p-2.5 rounded-xl shrink-0">
                                                <i data-lucide="file-plus" class="w-6 h-6 text-white"></i>
                                            </div>
                                            <div>
                                                <h2 class="text-xl font-bold leading-snug">تسجيل شكوى جديدة لدى قلم الجمهور</h2>
                                                <p class="text-xs text-emerald-100 mt-1">يرجى ملء تفاصيل الشكوى وتوجيهها للأقسام الفنية المختصة فوراً</p>
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

                                <!-- Fields Container -->
                                <div class="p-6 space-y-6">
                                    @if(session('error'))
                                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start space-x-3 space-x-reverse text-xs text-rose-700 font-bold">
                                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                            <span>{{ session('error') }}</span>
                                        </div>
                                    @endif

                                    <!-- Citizen Search Block -->
                                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-2xl p-5 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-black text-[#14723a] flex items-center gap-1.5">
                                                <i data-lucide="user" class="w-4 h-4"></i>
                                                <span>بيانات مقدم الطلب (المواطن)</span>
                                            </h3>
                                            <button type="button" onclick="handlePrepareNewCitizenForm()" class="text-xs text-[#14723a] hover:underline flex items-center gap-1 font-bold cursor-pointer">
                                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                                <span>إضافة مواطن جديد</span>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهوية الوطنية (اختياري)</label>
                                                <div class="flex gap-2">
                                                    <input type="text" id="citizen_id_input" name="citizen_id_input" maxlength="9" minlength="9" pattern="[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); if(this.value && !this.value.match(/^[0-9]{9}$/)){ this.setCustomValidity('يرجى إدخال رقم هوية صحيح مكون من 9 أرقام'); } else { this.setCustomValidity(''); }" placeholder="مثال: 105234567" class="w-full text-sm font-semibold px-4 py-3 rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" />
                                                    <button type="button" id="search_btn" class="inline-flex items-center justify-center rounded-lg bg-[#14723a] px-4 py-3 text-sm font-black text-white hover:bg-[#115e30] transition">بحث</button>
                                                </div>
                                                <p class="text-[11px] text-slate-500 mt-1">اضغط Enter أو زر البحث للاستعلام التلقائي.</p>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-800 mb-1.5">اسم المواطن الكامل (اختياري)</label>
                                                <div class="relative">
                                                    <i data-lucide="user" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                    <input type="text" id="citizen_name" name="applicant_name" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white text-slate-600 focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="الاسم الكامل" />
                                                    <input type="hidden" name="citizen_id" id="hidden_citizen_id">
                                                    <select name="property_id" id="property_select" class="hidden"></select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-800 mb-1.5">رقم الهاتف المحمول (اختياري)</label>
                                                 <div class="relative">
                                                     <i data-lucide="phone" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                     <input type="text" id="citizen_phone" name="applicant_phone" maxlength="10" minlength="10" pattern="^(059|056)[0-9]{7}$" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10); if(this.value && !this.value.match(/^(059|056)[0-9]{7}$/)){ this.setCustomValidity('يرجى إدخال رقم جوال صحيح يبدأ بـ 059 أو 056 ومكون من 10 أرقام'); } else { this.setCustomValidity(''); }" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none" placeholder="مثال: 0599123456" />
                                                 </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-slate-800 mb-1.5">حالة مقدم الطلب (العلاقة بالعقار) (اختياري)</label>
                                                <div class="relative">
                                                    <i data-lucide="user" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                    <select id="citizen_status" name="applicant_status" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a]">
                                                        <option value="">-- اختر --</option>
                                                        <option value="owner">مالك العقار</option>
                                                        <option value="beneficiary">منتفع بالعقار</option>
                                                        <option value="other">أخرى (وكيل/قريب)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-span-1 md:col-span-4">
                                                <label class="block text-xs font-bold text-slate-800 mb-1.5">الحي / المنطقة الجغرافية للشكوى (اختياري)</label>
                                                <div class="relative">
                                                    <i data-lucide="map-pin" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                    <input type="text" id="detailed_address" class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white" placeholder="مثال: وسط البلد، بالقرب من مسجد حمزة" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Selected property display (filled by modal) -->
                                        <div id="selected-property-display-container" class="mt-4 hidden bg-[#14723a]/5 border border-[#14723a]/15 rounded-xl p-4 space-y-3">
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs font-black text-[#14723a] flex items-center gap-1.5">
                                                    <i data-lucide="building" class="w-4 h-4"></i>
                                                    <span>بيانات العقار المختار لمقدم الشكوى:</span>
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

                                    <!-- Source and Type -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-slate-800 mb-1.5">نوع الشكوى <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <i data-lucide="list" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                <select name="complaint_type_id" required class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none">
                                                    <option value="">-- اختر نوع الشكوى --</option>
                                                    @foreach($complaintTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-slate-800 mb-1.5">مصدر الشكوى <span class="text-rose-500">*</span></label>
                                            <div class="relative">
                                                <i data-lucide="globe" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                                <select name="source" required class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none">
                                                    <option value="شخصي">شخصي</option>
                                                    <option value="اتصال هاتفي">اتصال هاتفي</option>
                                                    <option value="واتساب">واتساب</option>
                                                    <option value="فيسبوك">فيسبوك</option>
                                                    <option value="أخرى">أخرى</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Complaint details -->
                                    <div>
                                        <label class="block text-sm font-bold text-slate-800 mb-1.5">موضوع الشكوى (التفاصيل) <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <i data-lucide="file-text" class="absolute right-3 top-3.5 w-5 h-5 text-slate-400"></i>
                                            <textarea name="details" id="details" rows="4" required class="w-full text-sm pr-10 pl-4 py-3 font-semibold rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] outline-none resize-none" placeholder="اكتب شرحاً مفصلاً لسبب الشكوى أو الطلب..."></textarea>
                                        </div>
                                    </div>

                                    <!-- Departments -->
                                    <div class="bg-[#14723a]/5 border border-[#14723a]/15 rounded-xl p-4 space-y-3">
                                        <label class="block text-sm font-extrabold text-[#14723a]">القسم المحول عليه الشكوى (اختر قسم أو أكثر للبث المتوازي) <span class="text-rose-500">*</span></label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                            @foreach($departments as $dept)
                                                <label class="flex items-center justify-between p-3 rounded-lg border transition-all cursor-pointer bg-white hover:bg-slate-50">
                                                    <div class="flex items-center space-x-2.5 space-x-reverse">
                                                        <i data-lucide="building" class="w-4 h-4 text-slate-400"></i>
                                                        <span class="text-xs font-black">{{ $dept->name }}</span>
                                                    </div>
                                                    <input type="checkbox" name="departments[]" value="{{ $dept->id }}" class="h-4 w-4 text-[#14723a] focus:ring-[#14723a]" />
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>

                                <!-- Actions -->
                                <div class="bg-slate-50 px-6 py-4 flex items-center justify-end space-x-3 space-x-reverse border-t border-slate-100">
                                    <a href="{{ route('transactions.index') }}" class="flex items-center space-x-1.5 space-x-reverse text-xs font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 px-5 py-2.5 rounded-lg transition-all">إلغاء</a>
                                    <button type="submit" class="flex items-center space-x-1.5 space-x-reverse text-xs font-bold text-white bg-[#14723a] hover:bg-[#14723a]/90 px-6 py-2.5 rounded-lg transition-all shadow-md">حفظ وإرسال الشكوى</button>
                                </div>
                        </form>
        </div>
    </main>
</div>

@include('system.transactions.partials.citizen_modal')

<script>
    // Custom form validation
    const formRoot = document.getElementById('new-complaint-form-root');
    if (formRoot) {
        formRoot.addEventListener('submit', function(e) {
            // const citizenId = document.getElementById('hidden_citizen_id').value;
            // const propertyId = document.getElementById('property_select').value;
            
            // if (!citizenId) {
            //     e.preventDefault();
            //     alert('عذراً، يجب الاستعلام عن المواطن أولاً وإضافته للشكوى.');
            //     return;
            // }
            
            // if (!propertyId) {
            //     e.preventDefault();
            //     alert('عذراً، يجب إضافة واختيار بيانات العقار المرتبطة بالشكوى أولاً.');
            //     return;
            // }
        });
    }
</script>
@endsection
