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
    <body class="font-sans antialiased bg-[#f8fafc]">
        <div class="min-h-screen flex flex-col">
            <!-- Navigation Header -->
            @include('layouts.navigation', ['module_title' => $module_title ?? null])

            <!-- Page Content -->
            <main class="flex-1 w-full max-w-[1600px] mx-auto p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-6 text-center text-sm text-gray-400 mt-auto border-t border-gray-200 bg-white">
                نظام بلدية بيت حانون الشامل للأقسام والخدمات - قسم تكنولوجيا المعلومات والتحول الرقمي &copy; {{ date('Y') }}
            </footer>
        </div>
        
        @stack('scripts')
    </body>
</html>
