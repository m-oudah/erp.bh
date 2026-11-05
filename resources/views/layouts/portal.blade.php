<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'بوابة المواطنين للاستعلام' }} - بلدية بيت حانون</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>

@if(request()->routeIs('portal.login', 'portal.verify-otp'))
    {{-- Auth Mode: Strictly No-Scroll, 100dvh full viewport fit across all screens --}}
    <body class="bg-gradient-to-br from-slate-100 via-teal-50/40 to-slate-200 text-slate-800 antialiased h-[100dvh] max-h-screen overflow-hidden flex flex-col justify-between selection:bg-teal-600 selection:text-white p-3 sm:p-5">
        
        {{-- Flash Alerts (Floating compact toast) --}}
        @if (session('success') || session('error'))
            <div class="w-full max-w-md mx-auto shrink-0 z-50">
                @if (session('success'))
                    <div class="p-2.5 px-3.5 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs font-bold flex items-center justify-between gap-2 shadow-sm animate-fade-in">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-emerald-600 text-sm">✓</span>
                            <span class="truncate">{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-xs shrink-0">✕</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-2.5 px-3.5 rounded-xl bg-rose-50 border border-rose-300 text-rose-900 text-xs font-bold flex items-center justify-between gap-2 shadow-sm animate-fade-in">
                        <div class="flex items-center gap-1.5 truncate">
                            <span class="text-rose-600 text-sm">✕</span>
                            <span class="truncate">{{ session('error') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-950 font-bold text-xs shrink-0">✕</button>
                    </div>
                @endif
            </div>
        @else
            <div></div>
        @endif

        {{-- Center Content (Card) --}}
        <main class="flex-1 flex items-center justify-center w-full max-w-md mx-auto my-auto min-h-0">
            {{ $slot }}
        </main>

        {{-- Footer Note --}}
        <footer class="text-center text-[10px] sm:text-[11px] text-slate-400 py-1 shrink-0">
            <span class="font-bold text-slate-600">بلدية بيت حانون</span> - بوابة الخدمات الإلكترونية للمواطنين &copy; {{ date('Y') }}
        </footer>

    </body>
@else
    {{-- Authenticated Portal Mode: Scrollable, Full Navigation Layout --}}
    <body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-teal-600 selection:text-white">

        {{-- Top Navbar --}}
        <header class="text-white shadow-lg sticky top-0 z-40 border-b border-teal-700/50"
                style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%); background-color: #0f4c47;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 sm:h-20">
                    
                    {{-- Logo & Portal Title --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white/10 p-2 flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                            <img src="{{ asset('logo.png') }}" alt="شعار بلدية بيت حانون" class="w-full h-full object-contain filter drop-shadow">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-sm font-black tracking-wide text-amber-400">بلدية بيت حانون</span>
                                <span class="text-[10px] bg-amber-400/20 text-amber-300 font-bold px-2 py-0.5 rounded-full border border-amber-400/30">بوابة المواطنين</span>
                            </div>
                            <h1 class="text-sm sm:text-base font-extrabold text-white leading-tight">
                                بوابة الخدمات والاستعلام الإلكتروني
                            </h1>
                        </div>
                    </div>

                    {{-- Citizen Info & Actions --}}
                    @if(session()->has('portal_citizen_id'))
                        <div class="flex items-center gap-2 sm:gap-4">
                            <div class="text-right hidden sm:block">
                                <div class="text-xs font-bold text-teal-100 leading-tight">المواطن الكريم:</div>
                                <div class="text-sm font-black text-amber-300 truncate max-w-[200px]">{{ session('portal_citizen_name') }}</div>
                            </div>

                            <form method="POST" action="{{ route('portal.logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-rose-500/80 text-white hover:text-white transition-all border border-white/15 cursor-pointer shadow-sm"
                                        title="تسجيل الخروج">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="hidden sm:inline">تسجيل خروج</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Navigation Tabs for Authenticated Citizen --}}
                @if(session()->has('portal_citizen_id'))
                    <nav class="flex items-center gap-1 sm:gap-2 pb-2 overflow-x-auto scrollbar-none border-t border-white/10 pt-2 text-xs sm:text-sm font-bold">
                        <a href="{{ route('portal.dashboard') }}"
                           class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 flex items-center gap-1.5 {{ request()->routeIs('portal.dashboard') ? 'bg-amber-400 text-slate-950 font-black shadow-md' : 'text-teal-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>لوحة المعلومات</span>
                        </a>

                        <a href="{{ route('portal.buildings') }}"
                           class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 flex items-center gap-1.5 {{ request()->routeIs('portal.buildings') ? 'bg-amber-400 text-slate-950 font-black shadow-md' : 'text-teal-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>المباني والعقارات</span>
                        </a>

                        <a href="{{ route('portal.units') }}"
                           class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 flex items-center gap-1.5 {{ request()->routeIs('portal.units') ? 'bg-amber-400 text-slate-950 font-black shadow-md' : 'text-teal-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                            <span>الوحدات السكنية والتجارية</span>
                        </a>

                        <a href="{{ route('portal.crafts') }}"
                           class="px-3.5 py-1.5 rounded-xl transition-all shrink-0 flex items-center gap-1.5 {{ request()->routeIs('portal.crafts') ? 'bg-amber-400 text-slate-950 font-black shadow-md' : 'text-teal-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>الحرف والصناعات والمهن</span>
                        </a>
                    </nav>
                @endif
            </div>
        </header>

        {{-- Main Container --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-5">
            
            {{-- Flash Alerts --}}
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-xs font-bold">✕</button>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-sm font-bold flex items-center justify-between gap-3 shadow-xs">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 text-xs font-bold">✕</button>
                </div>
            @endif

            {{-- Page Slot Content --}}
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-slate-200 text-slate-500 text-xs py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-right">
                <div>
                    <span class="font-bold text-slate-700">بلدية بيت حانون</span> - بوابة الخدمات الإلكترونية للمواطنين &copy; {{ date('Y') }}
                </div>
                <div class="text-[11px] text-slate-400">
                    للاستفسار والدعم الفني يرجى التواصل مع قسم خدمات الجمهور في مبنى البلدية
                </div>
            </div>
        </footer>

    </body>
@endif
</html>
