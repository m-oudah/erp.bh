<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>التحقق من صحة إفادة حصر الأضرار • بلدية بيت حانون</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between p-4 sm:p-6">

    <div class="max-w-2xl w-full mx-auto my-auto space-y-6">

        {{-- Verification Card --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl overflow-hidden relative">

            {{-- Top Verified Banner --}}
            <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl mb-6">
                <div class="w-12 h-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-sm shadow-emerald-500/20">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-black text-emerald-950">إفادة حصر وتقييم أضرار رسمية ومعتمدة</h2>
                    <p class="text-xs text-emerald-800 font-medium">سجلات بلدية بيت حانون المركزية • الدائرة الهندسية والتنظيم</p>
                </div>
            </div>

            {{-- Document Meta --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 text-xs mb-4">
                <div>
                    <span class="text-slate-400">رقم السجل:</span>
                    <span class="font-mono font-black text-orange-600 mr-1">{{ $record->record_number }}</span>
                </div>
                <div class="text-slate-400">
                    تاريخ الاستعلام: <span class="font-bold text-slate-700">{{ now()->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            {{-- Beneficiary & Ownership (Privacy Preserved) --}}
            <div class="space-y-3 text-xs mb-6">
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">اسم المالك / المستفيد:</span>
                    <span class="font-black text-slate-900">{{ $record->citizen_name ?: 'مسجل بالسجلات المركزية' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">رقم الهوية (محمي جزئياً):</span>
                    <span class="font-mono font-bold text-slate-700">{{ $maskedId ?: 'غير مدرج' }}</span>
                </div>

                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">المنطقة والحي:</span>
                    <span class="font-bold text-slate-900">{{ $record->zone ? $record->zone->zone_name : 'بيت حانون' }}</span>
                </div>

                @if($record->block_number || $record->parcel_number)
                    <div class="flex justify-between py-2 border-b border-slate-100">
                        <span class="text-slate-500">الموقع التنظيمي (الحوض والقطعة):</span>
                        <span class="font-mono font-bold text-slate-900">حوض: {{ $record->block_number ?: '-' }} / قطعة: {{ $record->parcel_number ?: '-' }}</span>
                    </div>
                @endif

                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">مستوى الضرر الإنشائي:</span>
                    <span class="font-black text-rose-600">
                        {{ $record->damage_level === 'total_destruction' ? 'مهدم كلياً (تدمير شامل)' : 'ضرر جزئي بليغ' }}
                    </span>
                </div>

                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">المساحة المقدرة للمنشأة:</span>
                    <span class="font-bold text-slate-900">
                        {{ $record->sector === 'infrastructure' ? (number_format($record->length_meters, 0) . ' م.ط') : (number_format($record->area_sqm, 0) . ' م²') }}
                    </span>
                </div>

                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">حالة اعتماد الوثيقة:</span>
                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> معتمد ومطابق للسجلات
                    </span>
                </div>
            </div>

            {{-- Municipal Disclaimer & Contact --}}
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-[11px] text-slate-600 leading-relaxed font-medium">
                <span class="font-bold text-slate-800">بيان رسمي:</span> هذه الصفحة تأكيد إلكتروني مباشر صادر عن خوادم بلدية بيت حانون المركزية لإثبات سلامة وصحة الإفادة الورقية الممسوحة ضوئياً، وتعتبر وثيقة معتمدة لكافة المؤسسات والجهات الإغاثية والمانحة.
            </div>

            {{-- Municipal Seal & Logo at bottom --}}
            <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('logo.png') }}" class="h-8 w-auto">
                    <span class="text-xs font-black text-slate-900">بلدية بيت حانون</span>
                </div>
                <div class="text-[10px] text-slate-400 font-mono">
                    ID: {{ substr($record->certificate_uuid, 0, 18) }}...
                </div>
            </div>

        </div>

        {{-- Back Link --}}
        <div class="text-center">
            <a href="{{ route('damage.portal.index') }}" class="text-xs font-bold text-orange-600 hover:underline">
                الانتقال إلى البوابة العامة والخريطة التفاعلية &rarr;
            </a>
        </div>

    </div>

    <footer class="text-center text-xs text-slate-400 py-4">
        نظام بلدية بيت حانون الشامل &copy; {{ date('Y') }}
    </footer>

</body>
</html>
