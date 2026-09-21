<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="البريد الإلكتروني أو رقم الموظف" class="text-gray-700 font-semibold" />
            <div class="mt-2 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                    <x-heroicon-o-user class="h-5 w-5" />
                </div>
                <x-text-input id="email" class="block w-full pr-10 border-gray-300 focus:border-orange focus:ring-orange rounded-lg shadow-sm transition-colors" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="user@erp.bh" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="كلمة المرور" class="text-gray-700 font-semibold" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-orange hover:text-orange-dark font-medium transition-colors" href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>
            <div class="mt-2 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                    <x-heroicon-o-lock-closed class="h-5 w-5" />
                </div>
                <x-text-input id="password" class="block w-full pr-10 border-gray-300 focus:border-orange focus:ring-orange rounded-lg shadow-sm transition-colors"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="h-4 w-4 text-orange focus:ring-orange border-gray-300 rounded cursor-pointer transition-colors" name="remember">
            <label for="remember_me" class="mr-2 block text-sm text-gray-700 cursor-pointer">
                تذكرني على هذا الجهاز
            </label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-navy hover:bg-navy-light focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange transition-all duration-200 hover:-translate-y-0.5">
                تسجيل الدخول
            </button>
        </div>
    </form>
</x-guest-layout>
