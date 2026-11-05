<x-customers-layout>
    <div class="space-y-6 max-w-4xl mx-auto pb-10" x-data="{ isSubmitting: false }">

        {{-- Page Header --}}
        <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-5">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.show', $customer->id) }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2.5 rounded-2xl transition-colors shrink-0"
                   title="الرجوع إلى ملف المواطن">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-orange/10 text-orange-dark text-xs px-2.5 py-0.5 rounded-lg font-bold border border-orange/20 flex items-center gap-1">
                            <x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-orange" />
                            تعديل بيانات المواطن
                        </span>
                        <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-0.5 rounded-lg font-mono font-bold border border-blue-100">
                            هوية: {{ $customer->id_no ?? '-' }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">
                        {{ $customer->name ?? 'تعديل بيانات المواطن' }}
                    </h2>
                    <p class="text-xs text-gray-400 mt-1">
                        تحديث وتصحيح بيانات المواطن الشخصية وأرقام التواصل والعنوان بسجلات البلدية
                    </p>
                </div>
            </div>
            
            <div>
                <a href="{{ route('customers.show', $customer->id) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl text-xs border border-gray-200 transition-colors">
                    <x-heroicon-o-eye class="w-4 h-4 text-gray-500" />
                    عرض الملف
                </a>
            </div>
        </div>

        {{-- Error Alerts --}}
        @if (isset($errors) && $errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-xs shadow-xs animate-fade-in">
            <div class="font-bold flex items-center gap-2 mb-2 text-sm">
                <x-heroicon-s-x-circle class="w-5 h-5 text-red-500 shrink-0" />
                يرجى تصحيح الملاحظات التالية قبل الحفظ:
            </div>
            <ul class="list-disc list-inside space-y-1 pr-2 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Success Alert --}}
        @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2 shadow-xs animate-fade-in">
            <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
        @endif

        {{-- Citizen Edit Form --}}
        <form action="{{ route('customers.citizens.update', $customer->id) }}"
              method="POST"
              id="edit-citizen-form"
              @submit="isSubmitting = true"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Card 1: Basic Identity --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-5">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                    <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shrink-0">
                        <x-heroicon-s-identification class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-800">بيانات الهوية والاسم</h3>
                        <p class="text-[11px] text-gray-400">الرقم الوطني والاسم الرباعي الرسمي</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- رقم الهوية --}}
                    <div>
                        <label for="id_no" class="block text-xs font-bold text-gray-700 mb-1 flex items-center justify-between">
                            <span>رقم الهوية الوطنية <span class="text-orange">*</span></span>
                            <span class="text-gray-400 font-normal text-[11px]">(9 أرقام)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-hashtag class="w-4 h-4" />
                            </div>
                            <input type="text"
                                   id="id_no"
                                   name="id_no"
                                   inputmode="numeric"
                                   maxlength="20"
                                   required
                                   placeholder="مثال: 400123456"
                                   value="{{ old('id_no', $customer->id_no) }}"
                                   class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 text-gray-900 text-xs font-mono font-bold tracking-wider focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white">
                        </div>
                        @error('id_no')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الاسم الكامل --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 mb-1">
                            الاسم الرباعي / الجهة <span class="text-orange">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-user class="w-4 h-4" />
                            </div>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   required
                                   placeholder="مثال: أحمد محمد خليل المصري"
                                   value="{{ old('name', $customer->name) }}"
                                   class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 text-gray-900 text-xs font-bold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Card 2: Contact Information --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-5">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <x-heroicon-s-phone class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-800">بيانات وأرقام التواصل</h3>
                        <p class="text-[11px] text-gray-400">تستخدم هذه الأرقام في الإشعارات والتحقق عبر بوابة المواطنين</p>
                    </div>
                </div>

                {{-- Alert / Hint about Mobile & Portal OTP --}}
                <div class="p-3 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-center gap-2.5 text-xs text-amber-900 font-medium">
                    <x-heroicon-s-shield-check class="w-5 h-5 text-amber-600 shrink-0" />
                    <span>تعديل رقم الجوال يتيح للمواطن تسجيل الدخول الفوري إلى بوابة الخدمات الإلكترونية بالرقم الجديد واستلام رمز التحقق (SMS OTP).</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    {{-- رقم الجوال --}}
                    <div>
                        <label for="mobile" class="block text-xs font-bold text-gray-700 mb-1">
                            رقم الجوال (المعتمد)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-device-phone-mobile class="w-4 h-4" />
                            </div>
                            <input type="text"
                                   id="mobile"
                                   name="mobile"
                                   inputmode="tel"
                                   dir="ltr"
                                   placeholder="059xxxxxxx أو 056xxxxxxx"
                                   value="{{ old('mobile', $customer->mobile) }}"
                                   class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 text-gray-900 text-xs font-mono font-bold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white text-right">
                        </div>
                        @error('mobile')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الهاتف الثابت --}}
                    <div>
                        <label for="telephone" class="block text-xs font-bold text-gray-700 mb-1">
                            الهاتف الثابت (اختياري)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-phone-arrow-down-left class="w-4 h-4" />
                            </div>
                            <input type="text"
                                   id="telephone"
                                   name="telephone"
                                   inputmode="tel"
                                   dir="ltr"
                                   placeholder="0828xxxxx"
                                   value="{{ old('telephone', $customer->telephone) }}"
                                   class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 text-gray-900 text-xs font-mono font-bold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white text-right">
                        </div>
                        @error('telephone')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 mb-1">
                            البريد الإلكتروني (اختياري)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-envelope class="w-4 h-4" />
                            </div>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   dir="ltr"
                                   placeholder="example@domain.com"
                                   value="{{ old('email', $customer->email) }}"
                                   class="w-full pr-10 pl-3 py-2.5 rounded-xl border border-gray-200 text-gray-900 text-xs font-bold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white text-right">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Card 3: Address & Notes --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-5">
                <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <x-heroicon-s-map-pin class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-gray-800">العنوان والملاحظات</h3>
                        <p class="text-[11px] text-gray-400">مكان السكن وتفاصيل إضافية عن السجل</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- العنوان التفصيلي --}}
                    <div>
                        <label for="address" class="block text-xs font-bold text-gray-700 mb-1">
                            العنوان التفصيلي ومكان السكن
                        </label>
                        <textarea id="address"
                                  name="address"
                                  rows="3"
                                  placeholder="الحي، اسم الشارع، المعلم القريب، رقم البناية أو المنزل..."
                                  class="w-full p-3 rounded-2xl border border-gray-200 text-gray-900 text-xs font-semibold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white">{{ old('address', $customer->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ملاحظات عامة --}}
                    <div>
                        <label for="notes" class="block text-xs font-bold text-gray-700 mb-1">
                            ملاحظات عامة حول السجل
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  placeholder="أي ملاحظات إدارية أو توثيقية إضافية..."
                                  class="w-full p-3 rounded-2xl border border-gray-200 text-gray-900 text-xs font-semibold focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all bg-gray-50/50 focus:bg-white">{{ old('notes', $customer->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-between gap-4 pt-2">
                <a href="{{ route('customers.show', $customer->id) }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                    إلغاء والعودة
                </a>

                <button type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer text-xs">
                    <template x-if="!isSubmitting">
                        <span class="flex items-center gap-1.5">
                            <x-heroicon-s-check class="w-4 h-4" />
                            <span>حفظ التعديلات</span>
                        </span>
                    </template>
                    <template x-if="isSubmitting">
                        <span class="flex items-center gap-1.5">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>جاري حفظ التعديلات...</span>
                        </span>
                    </template>
                </button>
            </div>

        </form>

    </div>
</x-customers-layout>
