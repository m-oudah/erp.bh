<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تنبيه: وثيقة غير صالحة • بلدية بيت حانون</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between p-4 sm:p-6">

    <div class="max-w-md w-full mx-auto my-auto">
        <div class="bg-white rounded-3xl p-8 border border-rose-200 shadow-xl text-center space-y-4">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>

            <h1 class="text-lg font-black text-rose-950">رمز إفادة غير صالح أو غير موجود</h1>
            <p class="text-xs text-slate-600 leading-relaxed font-medium">
                لم يتم العثور على أي سجل حصر أضرار يطابق الرمز الممسوح ({{ $uuid }}). يُرجى التأكد من مسح الرمز الأصلي الصادر رسمياً عن بلدية بيت حانون.
            </p>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('damage.portal.index') }}" class="inline-block px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition-all">
                    العودة لبوابة الاستعلام والخريطة التفاعلية
                </a>
            </div>
        </div>
    </div>

    <footer class="text-center text-xs text-slate-400 py-4">
        بلدية بيت حانون &copy; {{ date('Y') }}
    </footer>

</body>
</html>
