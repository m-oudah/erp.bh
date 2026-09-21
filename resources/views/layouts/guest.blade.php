<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex flex-col sm:flex-row">
            
            <!-- Right Side (Form) -->
            <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/3 flex flex-col justify-center items-center px-6 py-12 bg-white shadow-2xl z-10 relative">
                <div class="w-full max-w-sm">
                    <div class="text-center mb-10">
                        <a href="/">
                            <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="w-32 h-auto mx-auto mb-6 drop-shadow-md" />
                        </a>
                        <h2 class="text-3xl font-bold text-navy-dark">النظام الموحد</h2>
                        <p class="text-gray-500 mt-2 text-sm">تسجيل الدخول للموارد المؤسسية (ERP)</p>
                    </div>

                    {{ $slot }}
                </div>
                
                <div class="absolute bottom-4 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} جميع الحقوق محفوظة - بلدية بيت حانون
                </div>
            </div>

            <!-- Left Side (Branding / Image / Gradient) -->
            <div class="hidden sm:flex flex-1 bg-gradient-to-br from-navy-dark via-navy to-navy-light justify-center items-center relative overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-orange opacity-20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-blue-400 opacity-20 blur-3xl"></div>
                
                <div class="text-center text-white z-10 px-12 max-w-2xl">
                    <h1 class="text-5xl font-bold mb-6 leading-tight">مركز المعلومات الموحد <br> لبلدية بيت حانون</h1>
                    <p class="text-lg text-blue-100 leading-relaxed">
                        نظام متكامل يربط جميع دوائر وأقسام البلدية في منصة سحابية واحدة لتسهيل العمليات وتقديم خدمات أفضل للمواطنين.
                    </p>
                </div>
            </div>

        </div>
    </body>
</html>
