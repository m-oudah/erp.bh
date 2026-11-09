<x-general-settings-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">إعدادات عامة</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                النسخ الاحتياطي لقاعدة البيانات
            </h1>
            <p class="text-teal-100 text-xs mt-1 font-medium">تصدير وأرشفة نسخة احتياطية شاملة لقاعدة بيانات النظام الموحد</p>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 bg-teal-50 border border-teal-200 text-teal-800 rounded-2xl flex items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2 text-xs font-bold">
                    <svg class="w-5 h-5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @if(session('download_filename'))
                    <a href="{{ route('settings.general.backup.download', session('download_filename')) }}" 
                       class="px-3.5 py-1.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-teal-700 transition-colors shadow-xs shrink-0 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>تحميل الملف الآن</span>
                    </a>
                @endif
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-2 text-xs font-bold shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Database Metrics Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">قاعدة البيانات</div>
                <div class="text-base font-black text-slate-800 font-mono tracking-tight">{{ $dbName }}</div>
                <div class="text-[10px] text-teal-700 font-semibold mt-1">MySQL Server</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">إجمالي الجداول</div>
                <div class="text-xl font-black text-slate-800">{{ $tablesCount }} <span class="text-xs font-normal text-slate-500">جدول</span></div>
                <div class="text-[10px] text-slate-400 font-medium mt-1">جميع الموديولات والبيانات</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">حجم البيانات التقديري</div>
                <div class="text-xl font-black text-slate-800 font-mono">{{ $sizeMb }} <span class="text-xs font-normal text-slate-500">MB</span></div>
                <div class="text-[10px] text-slate-400 font-medium mt-1">حجم الجداول والفهارس</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <div class="text-[11px] font-bold text-slate-400 mb-1">تاريخ اليوم</div>
                <div class="text-base font-black text-slate-800 font-mono">{{ now()->format('Y-m-d') }}</div>
                <div class="text-[10px] text-amber-700 font-bold mt-1">تاريخ النسخة المعتمد</div>
            </div>
        </div>

        {{-- Export Action Box --}}
        <div class="bg-gradient-to-br from-white to-teal-50/40 rounded-3xl border border-teal-100 shadow-xs p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>تصدير نسخة احتياطية جديدة لقاعدة البيانات</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">
                        سيتم إنشاء ملف تصدير SQL كامل يشتمل على هيكلية الجداول والبيانات المسجلة، محفوظاً باسم قاعدة البيانات وتاريخ يوم التصدير.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-[11px] text-slate-400 font-bold block">الاسم المعتمد للملف المصدر:</span>
                    <span class="font-mono text-xs font-extrabold text-primary bg-teal-50 px-3 py-1 rounded-lg border border-teal-200/80 inline-block dir-ltr">
                        {{ $dbName }}_backup_{{ now()->format('Y-m-d') }}.sql
                    </span>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    {{-- Standard Export (Save to server and show download) --}}
                    <form method="POST" action="{{ route('settings.general.backup.export') }}" onsubmit="document.getElementById('export-btn').disabled = true; document.getElementById('export-btn').innerHTML = 'جاري التصدير...';">
                        @csrf
                        <button type="submit" 
                                id="export-btn"
                                class="px-5 py-2.5 rounded-xl text-xs font-black text-white bg-primary hover:bg-teal-700 shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer hover:shadow-lg">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            <span>تصدير نسخة احتياطية الآن</span>
                        </button>
                    </form>

                    {{-- Direct Download Export --}}
                    <form method="POST" action="{{ route('settings.general.backup.export') }}">
                        @csrf
                        <input type="hidden" name="action" value="direct_download">
                        <button type="submit" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                title="تصدير وتنزيل فوري للمتصفح">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>تحميل مباشر</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Historical Backups List --}}
        <div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-900">سجل النسخ الاحتياطية المصدرة</h3>
                    <p class="text-xs text-slate-500 mt-0.5">الملفات المحفوظة على خادم النظام وجاهزة للتحميل في أي وقت</p>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                    عدد النسخ: <strong class="text-primary font-black">{{ count($backups) }}</strong>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50/80 text-slate-500 uppercase font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5">اسم الملف المصدر</th>
                            <th class="px-6 py-3.5">حجم الملف</th>
                            <th class="px-6 py-3.5">تاريخ ووقت التصدير</th>
                            <th class="px-6 py-3.5 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($backups as $b)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-800 text-[11px] dir-ltr text-right">
                                    {{ $b['filename'] }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-slate-600">
                                    {{ $b['size'] }} MB
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-medium">
                                    {{ $b['created_at']->format('Y/m/d - H:i:s') }}
                                    <span class="text-[10px] text-slate-400 mr-1 font-normal">({{ $b['created_at']->diffForHumans() }})</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Download button --}}
                                        <a href="{{ route('settings.general.backup.download', $b['filename']) }}" 
                                           class="p-2 rounded-xl bg-teal-50 hover:bg-primary text-primary hover:text-white transition-colors border border-teal-200/60 shadow-2xs"
                                           title="تحميل الملف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>

                                        {{-- Delete button --}}
                                        <form method="POST" action="{{ route('settings.general.backup.delete', $b['filename']) }}" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف ملف النسخة الاحتياطية هذا نهائياً؟');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white transition-colors border border-rose-200/60 shadow-2xs cursor-pointer"
                                                    title="حذف النسخة">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="font-bold text-xs">لا توجد نسخ احتياطية محفوظة حالياً.</p>
                                        <p class="text-[11px] text-slate-400">اضغط على زر "تصدير نسخة احتياطية الآن" بالأعلى لبدء أول عملية تصدير.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-general-settings-layout>

