<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فضاء المنظمات والجهات المانحة • بلدية بيت حانون</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css'])

    <style>body { font-family: 'Cairo', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col" x-data="{ modalOpen: false, selectedRecordId: null, recordTitle: '' }">

    <!-- Header -->
    <header class="text-white sticky top-0 z-40 shadow-lg border-b border-teal-700/50"
            style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%); background-color: #0f4c47;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('damage.portal.index') }}" class="w-11 h-11 rounded-2xl bg-white/10 p-1.5 flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                    <img src="{{ asset('logo.png') }}" alt="بلدية بيت حانون" class="w-full h-full object-contain filter drop-shadow">
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-[11px] font-black tracking-wide text-amber-300">بلدية بيت حانون</span>
                        <span class="text-[10px] bg-amber-400/20 text-amber-300 font-bold px-2 py-0.5 rounded-full border border-amber-400/30">DONOR COORDINATION SPACE</span>
                    </div>
                    <h1 class="text-base sm:text-lg font-black text-white leading-tight">فضاء تنسيق المانحين والشركاء الدوليين</h1>
                    <p class="text-[11px] text-teal-100/80 hidden sm:block">التحقق الميداني لمنع ازدواجية التمويل وتسجيل التدخلات الإغاثية وإعادة الإعمار</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('damage.portal.index') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition-all flex items-center gap-1.5">
                    &rarr; <span>العودة للخريطة التفاعلية</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

        {{-- Flash Alert --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Informational Banner --}}
        <div class="rounded-3xl p-6 text-white shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-6 border border-teal-600/30"
             style="background: linear-gradient(135deg, #0f4c47 0%, #115e59 50%, #0f172a 100%);">
            <div class="space-y-1.5 max-w-2xl">
                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black bg-amber-500 text-slate-950 uppercase tracking-wider">منظومة عدم الازدواجية</span>
                <h2 class="text-xl font-black">تحقق من حالة المنشأة وسجّل تدخلك التمويلي بدقة</h2>
                <p class="text-xs text-teal-100/90 leading-relaxed font-medium">
                    تتيح هذه المنصة لكافة الشركاء الدوليين والمنظمات الإنسانية وصناديق التنمية البحث في السجلات المركزية وتثبيت نية التدخل لضمان عدم تكرار المشاريع في نفس الموقع وتوجيه الموارد للأحياء الأكثر حاجة.
                </p>
            </div>
            <div class="text-left shrink-0">
                <div class="text-xs font-bold text-teal-100">الهيئات النشطة الموثقة</div>
                <div class="text-3xl font-black text-amber-300 mt-1 font-mono">{{ $donors->count() ?: 12 }} منظمة وهيئة</div>
            </div>
        </div>

        {{-- Search & Verification Filter --}}
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
            <form method="GET" action="{{ route('damage.portal.donorSpace') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-600 mb-1">البحث بالرقم المرجعي (DAM-..)، الحوض والقطعة، أو اسم الموقع</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="أدخل رقم السجل أو الحوض والقطعة..." class="w-full text-xs font-medium rounded-xl border-slate-300 bg-white focus:border-teal-600 focus:ring-teal-600 shadow-xs">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">القطاع</label>
                    <select name="sector" class="w-full text-xs font-medium rounded-xl border-slate-300 bg-white focus:border-teal-600 focus:ring-teal-600 shadow-xs">
                        <option value="">كافة القطاعات</option>
                        <option value="real_estate" {{ request('sector') === 'real_estate' ? 'selected' : '' }}>المباني والعقارات</option>
                        <option value="infrastructure" {{ request('sector') === 'infrastructure' ? 'selected' : '' }}>البنية التحتية والشبكات</option>
                        <option value="public_facilities" {{ request('sector') === 'public_facilities' ? 'selected' : '' }}>المرافق العامة والحيوية</option>
                        <option value="economic_crafts" {{ request('sector') === 'economic_crafts' ? 'selected' : '' }}>القطاع الاقتصادي والحرفي</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                        بحث وتدقيق
                    </button>
                    <a href="{{ route('damage.portal.donorSpace') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition-all">
                        إعادة ضبط
                    </a>
                </div>
            </form>
        </div>

        {{-- Records Table --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between text-xs font-bold text-slate-600">
                <span>نتائج البحث والتحقق من المنشآت</span>
                <span>إجمالي النتائج: <strong class="text-teal-700 font-mono">{{ number_format($records->total()) }}</strong></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-slate-600 font-bold">
                        <tr>
                            <th class="p-3.5">الرقم المرجعي للسجل</th>
                            <th class="p-3.5">المنشأة / الموقع</th>
                            <th class="p-3.5">القطاع</th>
                            <th class="p-3.5">الموقع التنظيمي</th>
                            <th class="p-3.5">مستوى الضرر</th>
                            <th class="p-3.5">الاحتياج المقدر ($)</th>
                            <th class="p-3.5 text-center">إجراء المانح</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($records as $rec)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="p-3.5 font-mono font-bold text-teal-700 whitespace-nowrap">
                                    {{ $rec->record_number }}
                                </td>
                                <td class="p-3.5 font-bold text-slate-900">
                                    {{ $rec->name }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ $rec->sector }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-600 whitespace-nowrap">
                                    @if($rec->block_number || $rec->parcel_number)
                                        <span class="font-mono">حوض: {{ $rec->block_number ?: '-' }} / قطعة: {{ $rec->parcel_number ?: '-' }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    @if($rec->damage_level === 'total_destruction')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200">مهدم كلياً</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200">ضرر جزئي</span>
                                    @endif
                                </td>
                                <td class="p-3.5 font-black text-slate-900 font-mono whitespace-nowrap">
                                    ${{ number_format($rec->estimated_cost_usd, 0) }}
                                </td>
                                <td class="p-3.5 text-center whitespace-nowrap">
                                    <button @click="selectedRecordId = {{ $rec->id }}; recordTitle = '{{ addslashes($rec->name) }} ({{ $rec->record_number }})'; modalOpen = true"
                                            class="px-3 py-1.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs transition-colors shadow-xs flex items-center gap-1 mx-auto">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>تسجيل نية تدخل</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">لا توجد منشآت تطابق معايير البحث</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

        {{-- Intervention Registration Modal --}}
        <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 space-y-4" @click.outside="modalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-black uppercase text-teal-700 tracking-wider">DONOR INTERVENTION</span>
                        <h3 class="font-black text-slate-900 text-base">تسجيل نية تدخل تمويلي للمنشأة</h3>
                    </div>
                    <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs">
                    <span class="text-slate-500 font-bold">المنشأة المستهدفة:</span>
                    <div class="font-black text-slate-900 mt-0.5" x-text="recordTitle"></div>
                </div>

                <form method="POST" action="{{ route('damage.portal.donorIntervention') }}" class="space-y-3.5 text-xs">
                    @csrf
                    <input type="hidden" name="damage_record_id" :value="selectedRecordId">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">اسم المنظمة أو الهيئة المانحة <span class="text-rose-500">*</span></label>
                        <input type="text" name="organization_name" required placeholder="مثال: UNDP / الهلال الأحمر / منظمة الغذاء العالمية" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">اسم منسق المشروع <span class="text-rose-500">*</span></label>
                            <input type="text" name="contact_person" required placeholder="الاسم الكامل" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">البريد الإلكتروني الرسمي <span class="text-rose-500">*</span></label>
                            <input type="email" name="contact_email" required placeholder="donor@agency.org" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">طبيعة التدخل المقترح <span class="text-rose-500">*</span></label>
                            <select name="intervention_type" required class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                                <option value="reconstruction">إعادة إعمار كامل</option>
                                <option value="rehabilitation">ترميم وتأهيل جزئي</option>
                                <option value="emergency_shelter">مأوى ومرافق طوارئ</option>
                                <option value="network_repair">صيانة شبكات وتعبيد</option>
                                <option value="rubble_removal">إزالة ركام وتدعيم سلامة</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">المبلغ المرصود بالدولار ($) <span class="text-rose-500">*</span></label>
                            <input type="number" step="100" name="pledged_amount_usd" required placeholder="مثال: 50000" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">التاريخ المتوقع لبدء الأعمال</label>
                        <input type="date" name="planned_start_date" class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">ملاحظات أو نطاق العمل الميداني</label>
                        <textarea name="notes" rows="2" placeholder="أية تفاصيل إضافية حول التمويل والشركاء المحليين..." class="w-full text-xs rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600/20"></textarea>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold">إلغاء</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-gradient-to-r from-teal-700 to-teal-800 hover:from-teal-800 hover:to-teal-900 text-white font-bold shadow-sm">تثبيت التدخل التمويلي</button>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-center text-xs text-slate-500">
        بلدية بيت حانون • قطاع غزة • وحدة تنسيق المانحين والشركاء &copy; {{ date('Y') }}
    </footer>
</body>
</html>
