<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">بوابة الموظف / الحساب الشخصي</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                الملف الشخصي: {{ $user->name }}
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">إدارة بيانات الدخول والصورة الشخصية</p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10 space-y-6">

        {{-- Top Return Link --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>العودة للبوابة الرئيسية للنظام</span>
            </a>

            <div class="text-xs text-tertiary">
                آخر تحديث للملف: <span class="font-bold text-slate-700 font-mono">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        {{-- Status Alerts --}}
        @if (session('status') === 'password-updated')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-check class="w-5 h-5" />
                </div>
                <div class="text-xs font-bold flex-1">تم تحديث كلمة المرور الخاصة بحسابك بنجاح.</div>
            </div>
        @endif

        @if (session('status') === 'avatar-updated')
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-check class="w-5 h-5" />
                </div>
                <div class="text-xs font-bold flex-1">تم تحديث صورة الملف الشخصي بنجاح.</div>
            </div>
        @endif

        @if (session('status') === 'avatar-deleted')
            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-check class="w-5 h-5" />
                </div>
                <div class="text-xs font-bold flex-1">تم حذف الصورة الشخصية بنجاح واستعادة الأيقونة الافتراضية.</div>
            </div>
        @endif

        @if ($errors->updatePassword->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                    <x-heroicon-s-x-mark class="w-5 h-5" />
                </div>
                <div class="text-xs font-bold flex-1">
                    يرجى تصحيح الأخطاء التالية في نموذج تغيير كلمة المرور:
                    <ul class="list-disc list-inside mt-1 font-normal text-rose-700">
                        @foreach ($errors->updatePassword->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- 1. Right Column: Profile Picture Card --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Profile Photo Upload Card --}}
                <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden"
                     x-data="{ photoName: null, photoPreview: null }">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center">
                                <x-heroicon-s-user-circle class="w-5 h-5" />
                            </div>
                            <h2 class="text-base font-bold text-gray-800">صورة الملف الشخصي</h2>
                        </div>
                        <span class="text-[10px] font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-lg border border-teal-100">معرف #{{ $user->id }}</span>
                    </div>

                    <div class="p-6 text-center space-y-4">
                        {{-- Avatar Preview Frame --}}
                        <div class="relative w-32 h-32 mx-auto">
                            <!-- Current Photo -->
                            <div x-show="!photoPreview" class="w-32 h-32 rounded-3xl overflow-hidden ring-4 ring-slate-100 shadow-md bg-gradient-to-tr from-slate-100 to-slate-200 flex items-center justify-center mx-auto">
                                @if($user->avatar_url)
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-teal-700 bg-teal-50 font-black text-3xl">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>

                            <!-- New Selected Photo Preview -->
                            <div x-show="photoPreview" style="display: none;" class="w-32 h-32 rounded-3xl overflow-hidden ring-4 ring-primary/30 shadow-md mx-auto">
                                <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                      :style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>
                        </div>

                        {{-- User Identity Mini Details --}}
                        <div>
                            <h3 class="font-extrabold text-slate-800 text-base leading-tight">{{ $user->name }}</h3>
                            <div class="text-xs text-tertiary mt-1 font-mono" dir="ltr">{{ $user->email }}</div>
                            <div class="mt-2.5 flex items-center justify-center gap-1.5 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                    {{ $user->roles->first()?->name ?? 'موظف نظام' }}
                                </span>
                                @if($user->department)
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $user->department->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Upload Form --}}
                        <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" class="pt-3 border-t border-slate-100 space-y-3">
                            @csrf

                            {{-- Hidden file input --}}
                            <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden"
                                   x-ref="photo"
                                   x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                   ">

                            <div class="flex items-center justify-center gap-2">
                                <button type="button" x-on:click.prevent="$refs.photo.click()"
                                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 transition-colors flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                    <x-heroicon-s-camera class="w-4 h-4 text-secondary" />
                                    <span>اختيار صورة جديدة</span>
                                </button>

                                <button type="submit" x-show="photoPreview" style="display: none;"
                                        class="px-4 py-2.5 rounded-xl bg-primary hover:bg-teal-700 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                                    <x-heroicon-s-check class="w-4 h-4 text-amber-300" />
                                    <span>حفظ الصورة</span>
                                </button>
                            </div>

                            @error('avatar')
                                <p class="text-rose-500 text-xs font-bold text-center mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-[10px] text-gray-400">
                                الصيغ المسموحة: JPG, PNG, WEBP (الحد الأقصى 2 ميجابايت)
                            </p>
                        </form>

                        {{-- Delete Avatar Button (if user has custom avatar) --}}
                        @if($user->avatar)
                            <form action="{{ route('profile.avatar.destroy') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف صورتك الشخصية واستعادة الأيقونة الافتراضية؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 hover:underline inline-flex items-center gap-1 transition-colors">
                                    <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                    <span>حذف الصورة الحالية</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Security Notice Card --}}
                <div class="bg-gradient-to-br from-slate-900 to-teal-950 text-white rounded-3xl p-6 shadow-sm space-y-3">
                    <div class="flex items-center gap-2 text-amber-300 font-bold text-xs">
                        <x-heroicon-s-shield-check class="w-5 h-5" />
                        <span>أمان وخصوصية الحساب</span>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed font-normal">
                        احرص دائماً على اختيار كلمة مرور قوية تتكون من 8 خانات على الأقل وتجمع بين الحروف والأرقام لضمان حماية بيانات النظام والعمليات الموكلة إليك.
                    </p>
                    <div class="pt-2 text-[11px] text-teal-200/80 border-t border-white/10 font-mono">
                        حالة الحساب: <span class="text-emerald-400 font-bold">نشط وآمن ●</span>
                    </div>
                </div>

            </div>

            {{-- 2. Left Column: Password Update & Account Overview --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Password Update Form Card --}}
                <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden"
                     x-data="{ showCurrent: false, showNew: false, showConfirm: false }">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-secondary flex items-center justify-center">
                                <x-heroicon-s-key class="w-4 h-4" />
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-800">تعديل كلمة المرور</h2>
                                <p class="text-[11px] text-tertiary">قم بتحديث كلمة المرور الخاصة بك لتسجيل الدخول للنظام</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-primary bg-primary/5 px-2.5 py-1 rounded-lg">إجراء أمني</span>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Current Password --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                كلمة المرور الحالية <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input :type="showCurrent ? 'text' : 'password'"
                                       name="current_password"
                                       required
                                       placeholder="أدخل كلمة المرور الحالية للتأكيد"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 pl-10 transition-colors font-mono font-medium @if($errors->updatePassword->has('current_password')) border-rose-400 @endif">
                                <button type="button" @click="showCurrent = !showCurrent" class="absolute left-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                                    <x-heroicon-s-eye x-show="!showCurrent" class="w-4 h-4" />
                                    <x-heroicon-s-eye-slash x-show="showCurrent" style="display: none;" class="w-4 h-4" />
                                </button>
                            </div>
                            @if($errors->updatePassword->has('current_password'))
                                <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $errors->updatePassword->first('current_password') }}</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                            {{-- New Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                    كلمة المرور الجديدة <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showNew ? 'text' : 'password'"
                                           name="password"
                                           required
                                           placeholder="8 خانات على الأقل"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 pl-10 transition-colors font-mono font-medium @if($errors->updatePassword->has('password')) border-rose-400 @endif">
                                    <button type="button" @click="showNew = !showNew" class="absolute left-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                                        <x-heroicon-s-eye x-show="!showNew" class="w-4 h-4" />
                                        <x-heroicon-s-eye-slash x-show="showNew" style="display: none;" class="w-4 h-4" />
                                    </button>
                                </div>
                                @if($errors->updatePassword->has('password'))
                                    <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $errors->updatePassword->first('password') }}</span>
                                @endif
                            </div>

                            {{-- Confirm New Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">
                                    تأكيد كلمة المرور الجديدة <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'"
                                           name="password_confirmation"
                                           required
                                           placeholder="أعد إدخال كلمة المرور الجديدة"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 pl-10 transition-colors font-mono font-medium">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute left-3 top-3 text-slate-400 hover:text-slate-600 transition-colors">
                                        <x-heroicon-s-eye x-show="!showConfirm" class="w-4 h-4" />
                                        <x-heroicon-s-eye-slash x-show="showConfirm" style="display: none;" class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-[11px] text-gray-400">تأكد من حفظ كلمة المرور في مكان آمن بعد تحديثها.</span>

                            <button type="submit"
                                    class="py-3 px-6 border border-transparent rounded-2xl shadow-sm text-xs font-black text-white bg-primary hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all flex items-center justify-center gap-2 cursor-pointer hover:shadow">
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>حفظ كلمة المرور الجديدة</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Account Details & Administrative Placement (Read-Only Information Card) --}}
                <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                                <x-heroicon-s-identification class="w-4 h-4" />
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-gray-800">بيانات الحساب والتسكين الإداري</h2>
                                <p class="text-[11px] text-tertiary">معلومات الحساب المسجلة لدى قسم تكنولوجيا المعلومات وإدارة النظام</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">للقراءة والمراجعة</span>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Name --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">اسم المستخدم الكامل</div>
                                <div class="text-sm font-extrabold text-gray-800">{{ $user->name }}</div>
                            </div>

                            {{-- Email --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">البريد الإلكتروني المسجل</div>
                                <div class="text-sm font-mono font-bold text-gray-800" dir="ltr">{{ $user->email }}</div>
                            </div>

                            {{-- Department --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">الدائرة / الوحدة الإدارية</div>
                                <div class="text-sm font-bold text-gray-800">
                                    {{ $user->department?->name ?? 'غير مسكن في قسم محدد' }}
                                    @if($user->department)
                                        <span class="text-[11px] text-teal-700 font-normal">({{ $user->department->type_arabic }})</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Job Title --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">المسمى الوظيفي</div>
                                <div class="text-sm font-bold text-gray-800">{{ $user->job_title ?: 'موظف' }}</div>
                            </div>

                            {{-- Phone --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">رقم الهاتف للتواصل</div>
                                <div class="text-sm font-mono font-bold text-gray-800" dir="ltr">{{ $user->phone_number ?: 'غير مسجل' }}</div>
                            </div>

                            {{-- Role --}}
                            <div class="bg-gray-50/80 rounded-2xl p-4 border border-gray-100">
                                <div class="text-[11px] text-gray-500 font-bold mb-1">الدور الوظيفي في النظام</div>
                                <div class="text-sm font-extrabold text-secondary-dark flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                    <span>{{ $user->roles->first()?->name ?? 'مستخدم عادي' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Notice for editing admin details --}}
                        <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 flex items-start gap-3">
                            <x-heroicon-s-information-circle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong>تنويه إداري:</strong> لتعديل بياناتك الوظيفية (مثل الاسم، البريد الإلكتروني، القسم، أو المسمى الوظيفي) أو صلاحيات الوصول للموديولات، يرجى مراجعة <strong>قسم تكنولوجيا المعلومات وإدارة النظام</strong> لتقديم طلب التحديث.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
