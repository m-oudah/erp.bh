@php
    if (!isset($pageTitle)) {
        if (request()->routeIs('damage.dashboard')) {
            $pageTag = 'DAMS • DASHBOARD';
            $pageTitle = 'لوحة المتابعة المركزية والتقديرات الهندسية';
            $pageSubtitle = 'متابعة إحصائيات الأضرار الميدانية وتكاليف التعافي والترميم';
        } elseif (request()->routeIs('damage.records.index')) {
            $pageTag = 'DAMS • RECORDS';
            $pageTitle = 'سجلات حصر الأضرار والتقييم الهندسي';
            $pageSubtitle = 'إدارة وتدقيق ومطابقة سجلات الأضرار الميدانية والمكتبية';
        } elseif (request()->routeIs('damage.records.create')) {
            $pageTag = 'DAMS • NEW ASSESSMENT';
            $pageTitle = 'إدخال حصر وتقييم أضرار جديد';
            $pageSubtitle = 'توثيق وتسجيل ميداني لعقار أو منشأة أو مرفق أو طريق متضرر';
        } elseif (request()->routeIs('damage.records.edit')) {
            $pageTag = 'DAMS • EDIT RECORD';
            $pageTitle = 'تعديل سجل حصر الأضرار' . (isset($record) ? ': ' . $record->record_number : '');
            $pageSubtitle = 'تعديل البيانات الهندسية والميدانية وإعادة احتساب التكلفة التقديرية';
        } elseif (request()->routeIs('damage.records.show')) {
            $pageTag = 'DAMS • RECORD DETAILS';
            $pageTitle = isset($record) ? ($record->name . ' (' . $record->record_number . ')') : 'تفاصيل سجل حصر الأضرار';
            $pageSubtitle = isset($record) ? ('سجل رقم: ' . $record->record_number . ' • تدقيق ومراجعة هندسية وفنية') : 'مراجعة وتدقيق البيانات الفنية والمالية والاعتماد';
        } elseif (request()->routeIs('damage.pricing.*')) {
            $pageTag = 'DAMS • ENGINEERING PRICING';
            $pageTitle = 'معايير التسعير والتقدير المالي والكميات';
            $pageSubtitle = 'لوحة تحكم الدائرة الهندسية لتحديث أسعار الوحدات وإعادة الاحتساب الجماعي';
        } else {
            $pageTag = 'DAMS';
            $pageTitle = 'منظومة حصر الأضرار وتقييم الاحتياجات';
            $pageSubtitle = 'الدائرة الهندسية والتنظيم • بلدية بيت حانون';
        }
    }
@endphp

<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-teal-700 to-teal-600 text-white flex items-center justify-center shadow-md shadow-teal-700/20 shrink-0">
                <x-heroicon-s-chart-bar-square class="w-6 h-6" />
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-black uppercase tracking-wider text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200">{{ $pageTag ?? 'DAMS' }}</span>
                    <h1 class="text-xl font-black text-slate-900 leading-tight">{{ $pageTitle }}</h1>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $pageSubtitle ?? 'الدائرة الهندسية والتنظيم • بلدية بيت حانون' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Navigation Pills --}}
            <a href="{{ route('damage.dashboard') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request()->routeIs('damage.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                <x-heroicon-s-home class="w-4 h-4" />
                <span>الرئيسية</span>
            </a>

            <a href="{{ route('damage.records.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request()->routeIs('damage.records.*') && !request()->routeIs('damage.records.create') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                <x-heroicon-s-clipboard-document-list class="w-4 h-4" />
                <span>سجلات الحصر</span>
            </a>

            <a href="{{ route('damage.pricing.index') }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ request()->routeIs('damage.pricing.*') ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                <x-heroicon-s-calculator class="w-4 h-4" />
                <span>المعايير الهندسية والتسعير</span>
            </a>

            <a href="{{ route('damage.portal.index') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl text-xs font-bold bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 transition-all flex items-center gap-1.5">
                <x-heroicon-s-globe-alt class="w-4 h-4 text-amber-600" />
                <span>بوابة الاستعلام والخريطة التفاعلية</span>
                <x-heroicon-s-arrow-top-right-on-square class="w-3.5 h-3.5 opacity-70" />
            </a>

            <a href="{{ route('damage.records.create') }}" 
               class="px-4 py-2 rounded-xl text-xs font-black bg-teal-700 hover:bg-teal-800 text-white shadow-md shadow-teal-700/20 transition-all flex items-center gap-1.5">
                <x-heroicon-s-plus class="w-4 h-4" />
                <span>إضافة حصر جديد</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-xs">
            <x-heroicon-s-exclamation-circle class="w-5 h-5 text-rose-600 shrink-0" />
            <span>{{ session('error') }}</span>
        </div>
    @endif
</div>
