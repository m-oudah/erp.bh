<x-portal-layout>
    <x-slot name="title">تأكيد رمز التحقق - بوابة المواطنين</x-slot>

    <div class="w-full max-w-sm sm:max-w-md bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden animate-fade-in my-auto"
         x-data="{
             resendSeconds: {{ $resendAvailableIn }},
             timer: null,
             init() {
                 this.timer = setInterval(() => {
                     if (this.resendSeconds > 0) this.resendSeconds--;
                 }, 1000);
             }
         }">

        {{-- Card Header --}}
        <div class="p-3.5 sm:p-5 text-white text-center relative overflow-hidden shrink-0"
             style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 60%, #042f2e 100%); background-color: #0f4c47;">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-400/20 text-amber-300 p-2 mx-auto mb-1.5 border border-amber-400/30 flex items-center justify-center">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h2 class="text-sm sm:text-base font-black text-white tracking-wide">
                {{ $isFirstLogin ? 'تفعيل الحساب لأول مرة' : 'تأكيد رمز الدخول المعتمد' }}
            </h2>
            <p class="text-[10.5px] sm:text-[11px] text-teal-100/90 mt-0.5 font-medium">
                @if($isFirstLogin)
                    تم إرسال رمز الدخول لأول مرة في رسالة (SMS) إلى:
                @else
                    أدخل رمز الدخول الدائم الذي وصلك سابقاً عبر (SMS) إلى:
                @endif
            </p>
            <div class="inline-block mt-1 px-3 py-0.5 bg-white/10 rounded-full border border-white/20 font-mono text-amber-300 font-bold text-xs tracking-wider dir-ltr">
                {{ $maskedMobile }}
            </div>
        </div>

        {{-- Body --}}
        <div class="p-4 sm:p-6 space-y-3.5">

            {{-- Explanatory Note --}}
            <div class="p-2.5 rounded-xl {{ $isFirstLogin ? 'bg-amber-50 border-amber-200/90 text-amber-900' : 'bg-teal-50 border-teal-200 text-teal-900' }} border flex items-start gap-2 text-[11px] font-semibold leading-tight">
                <svg class="w-4 h-4 {{ $isFirstLogin ? 'text-amber-600' : 'text-teal-700' }} shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>
                    @if($isFirstLogin)
                        احتفظ بهذا الرمز جيداً؛ حيث سيتم اعتماده كرمز دخول دائم لحسابك دون الحاجة لإرسال SMS في كل مرة.
                    @else
                        حسابك مفعّل ولديك رمز دخول دائم مسبقاً. في حال نسيانه، يمكنك طلب رمز جديد بالأسفل.
                    @endif
                </span>
            </div>

            <form method="POST" action="{{ route('portal.verify-otp.submit') }}" class="space-y-3.5">
                @csrf

                <div>
                    <label for="otp_code" class="block text-xs font-bold text-slate-700 mb-1.5 text-center">
                        أدخل رمز الدخول المكوّن من 6 أرقام
                    </label>
                    <input type="text"
                           name="otp_code"
                           id="otp_code"
                           maxlength="6"
                           inputmode="numeric"
                           required
                           autofocus
                           autocomplete="one-time-code"
                           placeholder="------"
                           class="w-full text-center text-2xl sm:text-3xl font-black tracking-[0.4em] py-2 sm:py-2.5 rounded-2xl border-2 border-teal-500/50 focus:border-teal-600 focus:ring-4 focus:ring-teal-100 text-slate-900 font-mono shadow-xs">
                    
                    @error('otp_code')
                        <p class="text-rose-600 text-xs mt-1.5 text-center font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Badge --}}
                <div class="flex items-center justify-between text-[11px] text-slate-500 bg-slate-50 p-2 rounded-xl border border-slate-100">
                    <span class="flex items-center gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>حالة الرمز:</span>
                    </span>
                    <span class="font-bold text-teal-700">رمز معتمد ودائم لحسابك</span>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="submit"
                            style="background: linear-gradient(135deg, #0f766e 0%, #115e59 100%); background-color: #0f766e;"
                            class="w-full py-2.5 sm:py-3 px-4 rounded-xl font-black text-white hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-700 shadow-md transition-all flex items-center justify-center gap-2 text-sm cursor-pointer hover:shadow-lg active:scale-[0.99]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>تأكيد والدخول للبوابة</span>
                    </button>
                </div>
            </form>

            {{-- Resend & Change Number Actions --}}
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2 text-[11px]">
                
                {{-- Resend Form --}}
                <form method="POST" action="{{ route('portal.resend-otp') }}">
                    @csrf
                    <button type="submit"
                            :disabled="resendSeconds > 0"
                            class="text-teal-700 hover:text-teal-900 font-bold hover:underline disabled:text-slate-400 disabled:no-underline disabled:cursor-not-allowed flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>نسيت الرمز؟ إرسال رمز جديد</span>
                        <template x-if="resendSeconds > 0">
                            <span class="font-mono">(<span x-text="resendSeconds"></span>ث)</span>
                        </template>
                    </button>
                </form>

                {{-- Back to Login --}}
                <a href="{{ route('portal.login') }}" class="text-slate-500 hover:text-slate-800 font-medium hover:underline">
                    العودة لشاشة الدخول ←
                </a>
            </div>

        </div>
    </div>
</x-portal-layout>
