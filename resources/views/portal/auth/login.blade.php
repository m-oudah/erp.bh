<x-portal-layout>
    <x-slot name="title">تسجيل الدخول - بوابة المواطنين</x-slot>

    <div class="w-full max-w-sm sm:max-w-md bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden animate-fade-in my-auto"
         x-data="{
             tab: '{{ old('tab', (session('active_tab') ?? ((isset($errors) && $errors->has('mobile')) || session('cooldown_seconds') || session('is_daily_limit') ? 'sms' : 'direct'))) }}',
             idCard: '{{ old('id_card', '') }}',
             mobile: '{{ old('mobile', '') }}',
             cooldownSeconds: {{ (int) session('cooldown_seconds', 0) }},
             isDailyLimit: {{ session('is_daily_limit', false) ? 'true' : 'false' }},
             timer: null,

             init() {
                 if (this.cooldownSeconds > 0) {
                     this.startTimer();
                 }
                 if (this.idCard.length >= 9) {
                     this.checkStatus();
                 }
             },

             startTimer() {
                 if (this.timer) clearInterval(this.timer);
                 this.timer = setInterval(() => {
                     if (this.cooldownSeconds > 0) {
                         this.cooldownSeconds--;
                     } else {
                         clearInterval(this.timer);
                         this.timer = null;
                     }
                 }, 1000);
             },

             formatTime(sec) {
                 const m = Math.floor(sec / 60);
                 const s = sec % 60;
                 return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
             },

             async checkStatus() {
                 if (!this.idCard || this.idCard.length < 7) return;
                 try {
                     const res = await fetch(`{{ route('portal.check-cooldown') }}?id_card=${encodeURIComponent(this.idCard)}&mobile=${encodeURIComponent(this.mobile)}`);
                     const data = await res.json();
                     if (data.reason === 'daily_limit') {
                         this.isDailyLimit = true;
                         this.cooldownSeconds = 0;
                     } else if (data.reason === 'cooldown' && data.wait_seconds > 0) {
                         this.isDailyLimit = false;
                         this.cooldownSeconds = data.wait_seconds;
                         this.startTimer();
                     } else {
                         this.isDailyLimit = false;
                         this.cooldownSeconds = 0;
                     }
                 } catch (e) {
                     console.error(e);
                 }
             }
         }">
        
        {{-- Card Header: Municipality Identity --}}
        <div class="p-3.5 sm:p-5 text-white text-center relative overflow-hidden shrink-0"
             style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 60%, #042f2e 100%); background-color: #0f4c47;">
            <div class="absolute -right-8 -bottom-8 w-28 h-28 bg-teal-500/20 rounded-full blur-xl pointer-events-none"></div>
            <div class="absolute -left-8 -top-8 w-28 h-28 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>

            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white/10 p-1.5 mx-auto mb-1.5 border border-white/20 shadow-inner flex items-center justify-center">
                <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون" class="w-full h-full object-contain filter drop-shadow">
            </div>

            <div class="flex items-center justify-center gap-1.5 mb-0.5">
                <span class="text-xs font-black tracking-wide text-amber-300">بلدية بيت حانون</span>
            </div>
            <h2 class="text-sm sm:text-base font-black text-white tracking-wide">
               بوابة استعلام المواطنين
            </h2>
            <p class="text-[10.5px] sm:text-[11px] text-teal-100/90 mt-0.5 font-medium leading-tight">
                استعلم عن عقاراتك، وحداتك السكنية، وسجلات الحرف والمهن
            </p>
        </div>

        {{-- Mode Switcher Tabs --}}
        <div class="p-1 mx-3 sm:mx-5 mt-3 bg-slate-100 rounded-xl flex items-center text-xs font-bold border border-slate-200/80">
            <button type="button"
                    @click="tab = 'direct'"
                    :class="tab === 'direct' ? 'bg-white text-teal-800 shadow-xs font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                    class="flex-1 py-1.5 px-2 rounded-lg transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>دخول برمز الدخول</span>
            </button>
            <button type="button"
                    @click="tab = 'sms'"
                    :class="tab === 'sms' ? 'bg-white text-teal-800 shadow-xs font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                    class="flex-1 py-1.5 px-2 rounded-lg transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>أول دخول / استلام رمز</span>
            </button>
        </div>

        {{-- Card Body --}}
        <div class="p-3.5 sm:p-5 pt-2.5 sm:pt-3 space-y-3">

            {{-- TAB 1: Direct Login with ID + Permanent Code --}}
            <div x-show="tab === 'direct'" x-cloak class="space-y-3">
                <form method="POST" action="{{ route('portal.login.direct') }}" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="tab" value="direct">

                    {{-- National ID Input --}}
                    <div>
                        <label for="direct_id_card" class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>رقم الهوية الوطنية</span>
                            <span class="text-slate-400 font-normal text-[10px]">(9 أرقام)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="id_card"
                                   id="direct_id_card"
                                   inputmode="numeric"
                                   required
                                   autofocus
                                   placeholder="أدخل رقم الهوية (9 أرقام)"
                                   value="{{ old('id_card') }}"
                                   class="w-full pr-9 pl-3 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm font-bold tracking-wider placeholder:font-normal placeholder:text-slate-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-100 transition-colors shadow-2xs">
                        </div>
                        @error('id_card')
                            <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Permanent Access Code Input --}}
                    <div>
                        <label for="direct_otp_code" class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>رمز الدخول المعتمد</span>
                            <span class="text-slate-400 font-normal text-[10px]">(6 أرقام)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <input type="password"
                                   name="otp_code"
                                   id="direct_otp_code"
                                   inputmode="numeric"
                                   maxlength="6"
                                   required
                                   placeholder="رمز الدخول الذي وصلك عبر SMS"
                                   class="w-full pr-9 pl-3 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm font-mono font-bold tracking-widest placeholder:tracking-normal placeholder:font-sans placeholder:font-normal placeholder:text-slate-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-100 transition-colors shadow-2xs">
                        </div>
                        @error('otp_code')
                            <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-1">
                        <button type="submit"
                                style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); background-color: #0f766e;"
                                class="w-full py-2.5 px-4 rounded-xl font-black text-white hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-700 shadow-md transition-all flex items-center justify-center gap-2 text-sm cursor-pointer hover:shadow-lg active:scale-[0.99]">
                            <span>دخول إلى البوابة</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="text-center pt-1">
                    <button type="button"
                            @click="tab = 'sms'"
                            class="text-[11px] text-teal-700 hover:text-teal-900 font-bold hover:underline cursor-pointer">
                        أول تسجيل دخول لك أو نسيت رمز الدخول؟ اضغط هنا ←
                    </button>
                </div>
            </div>

            {{-- TAB 2: First-Time Login / SMS OTP Request --}}
            <div x-show="tab === 'sms'" x-cloak class="space-y-3">
                <div class="p-2 rounded-xl bg-amber-50 border border-amber-200/90 flex items-center gap-2 text-amber-900 text-[8.5px] font-semibold leading-tight">
                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>لأول تسجيل دخول، سيتم التحقق وإرسال رمز دخول دائم إلى رقم جوالك المسجل عبر SMS.</span>
                </div>

                <form method="POST" action="{{ route('portal.send-otp') }}" class="space-y-2.5">
                    @csrf
                    <input type="hidden" name="tab" value="sms">

                    {{-- National ID Input --}}
                    <div>
                        <label for="sms_id_card" class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>رقم الهوية الوطنية</span>
                            <span class="text-slate-400 font-normal text-[10px]">(9 أرقام)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="id_card"
                                   id="sms_id_card"
                                   inputmode="numeric"
                                   required
                                   placeholder="أدخل رقم الهوية (9 أرقام)"
                                   value="{{ old('id_card') }}"
                                   x-model="idCard"
                                   @input.debounce.500ms="checkStatus()"
                                   class="w-full pr-9 pl-3 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm font-bold tracking-wider placeholder:font-normal placeholder:text-slate-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-100 transition-colors shadow-2xs">
                        </div>
                    </div>

                    {{-- Registered Mobile Input --}}
                    <div>
                        <label for="sms_mobile" class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                            <span>رقم الجوال المسجل</span>
                            <span class="text-slate-400 font-normal text-[10px]">(059xxxxxxx أو 056xxxxxxx)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   name="mobile"
                                   id="sms_mobile"
                                   inputmode="tel"
                                   required
                                   placeholder="مثال: 059XXXXXXX"
                                   value="{{ old('mobile') }}"
                                   x-model="mobile"
                                   @input.debounce.500ms="checkStatus()"
                                   class="w-full pr-9 pl-3 py-2 rounded-xl border border-slate-300 text-slate-900 text-sm font-bold tracking-wider placeholder:font-normal placeholder:text-slate-400 focus:border-teal-600 focus:ring-2 focus:ring-teal-100 transition-colors shadow-2xs">
                        </div>
                        @error('mobile')
                            <p class="text-rose-600 text-[11px] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dynamic Countdown Timer Card (Active Cooldown 5 minutes) --}}
                    <div x-show="cooldownSeconds > 0" x-cloak class="p-2.5 rounded-xl bg-amber-50 border border-amber-300 text-amber-950 text-xs flex items-center justify-between gap-2 shadow-2xs">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-bold text-[11px] text-amber-950">يجب الانتظار 5 دقائق قبل طلب الرمز للمرة الثانية</p>
                                <p class="text-[10px] text-amber-800">الوقت المتبقي لإمكانية الإرسال:</p>
                            </div>
                        </div>
                        <div class="font-mono font-black text-sm bg-white border border-amber-300 text-amber-950 px-2 py-0.5 rounded-lg dir-ltr shrink-0 shadow-2xs"
                             x-text="formatTime(cooldownSeconds) + ' د'"></div>
                    </div>

                    {{-- Dynamic Daily Limit Card (Max 2 requests in 24 hours) --}}
                    <div x-show="isDailyLimit" x-cloak class="p-2.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-950 text-xs flex items-start gap-2 shadow-2xs">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="space-y-0.5">
                            <p class="font-bold text-[11px] text-rose-950">تم استنفاد الحد الأقصى لطلب الرمز (مرتان خلال 24 ساعة)</p>
                            <p class="text-[10px] text-rose-800 leading-tight">
                                يُمنع طلب الرمز أكثر من مرتين خلال 24 ساعة. يمكنك الدخول المباشر برمزك السابق.
                            </p>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-1">
                        <button type="submit"
                                :disabled="cooldownSeconds > 0 || isDailyLimit"
                                :class="(cooldownSeconds > 0 || isDailyLimit) ? 'bg-slate-200 text-slate-500 border border-slate-300 cursor-not-allowed shadow-none' : 'text-white hover:opacity-95 shadow-md hover:shadow-lg active:scale-[0.99] cursor-pointer'"
                                style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); background-color: #0f766e;"
                                :style="(cooldownSeconds > 0 || isDailyLimit) ? 'background: #e2e8f0 !important; color: #64748b !important;' : ''"
                                class="w-full py-2.5 px-4 rounded-xl font-black text-sm transition-all flex items-center justify-center gap-2">
                            <template x-if="cooldownSeconds > 0">
                                <span class="flex items-center gap-1.5 font-bold text-xs text-amber-900">
                                    <span>انتظر</span>
                                    <span class="font-mono font-black bg-amber-100 text-amber-950 px-1.5 py-0.5 rounded border border-amber-300 dir-ltr" x-text="formatTime(cooldownSeconds)"></span>
                                    <span>قبل طلب الرمز للمرة الثانية</span>
                                </span>
                            </template>
                            <template x-if="cooldownSeconds === 0 && isDailyLimit">
                                <span class="text-rose-700 text-xs">تم استنفاد الحد الأقصى (مرتان / 24 ساعة)</span>
                            </template>
                            <template x-if="cooldownSeconds === 0 && !isDailyLimit">
                                <span class="flex items-center gap-2">
                                    <span>متابعة وإرسال رمز SMS</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </template>
                        </button>
                    </div>
                </form>

                <div class="text-center pt-1">
                    <button type="button"
                            @click="tab = 'direct'"
                            class="text-[11px] text-teal-700 hover:text-teal-900 font-bold hover:underline cursor-pointer">
                        لديك رمز دخول مسبقاً؟ العودة للدخول المباشر ←
                    </button>
                </div>
            </div>

            {{-- Compact Help Section --}}
            <div class="pt-1.5 border-t border-slate-100 text-center">
                <p class="text-[10px] text-slate-500 font-medium leading-tight">
                    لتحديث رقم جوالك المسجل أو بياناتك، يرجى مراجعة مركز خدمات الجمهور بمقر بلدية بيت حانون مصطحباً بطاقة الهوية.
                </p>
            </div>

        </div>
    </div>
</x-portal-layout>
