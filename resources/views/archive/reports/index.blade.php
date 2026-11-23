<x-archive-layout>
    <div class="space-y-5">
        {{-- Header & Export / Print Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-wrap items-center justify-between gap-3 print:hidden">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0" style="color: rgb(217 119 6);">
                    <x-heroicon-s-chart-bar class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">تقارير وإحصائيات الأرشيف الإلكتروني</h3>
                    <p class="text-[11px] text-slate-400">كشف تحليلي لتوزيع الملفات والوثائق الرقمية حسب الأنواع والتصنيفات</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('archive.reports.export') }}"
                   class="flex items-center gap-1.5 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200/80 px-3.5 py-2 rounded-xl font-bold text-xs transition-colors shadow-2xs">
                    <x-heroicon-s-document-arrow-down class="w-4 h-4 text-emerald-700" />
                    <span>تصدير البيانات (CSV)</span>
                </a>
                <button type="button" onclick="window.print()"
                        class="flex items-center gap-1.5 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 px-3.5 py-2 rounded-xl font-bold text-xs transition-colors shadow-2xs cursor-pointer">
                    <x-heroicon-s-printer class="w-4 h-4 text-slate-600" />
                    <span>طباعة التقرير</span>
                </button>
            </div>
        </div>

        {{-- Print Header (Hidden on Screen) --}}
        <div class="hidden print:block mb-6 text-center border-b-2 border-slate-200 pb-4">
            <h1 class="text-2xl font-black text-slate-900 mb-1">بلدية بيت حانون - تقرير نظام الأرشيف الإلكتروني</h1>
            <p class="text-xs text-slate-500 font-mono">تاريخ الاستخراج: {{ now()->format('Y/m/d H:i') }}</p>
        </div>

        {{-- Overview KPI Cards (Matching Correspondence Style) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 print:grid-cols-2">
            <div class="p-4 rounded-2xl border bg-white border-gray-100 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500">إجمالي المجلدات والملفات</span>
                    <div class="text-3xl font-black text-slate-800 tracking-tight mt-1">{{ number_format($totalFiles) }}</div>
                    <span class="text-[11px] font-semibold text-teal-700">ملف ومجلد مؤرشف في النظام</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-folder-open class="w-6 h-6" />
                </div>
            </div>

            <div class="p-4 rounded-2xl border bg-white border-gray-100 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-500">إجمالي الوثائق الرقمية المحفوظة</span>
                    <div class="text-3xl font-black text-slate-800 tracking-tight mt-1">{{ number_format($totalDocuments) }}</div>
                    <span class="text-[11px] font-semibold text-amber-700">مستند ومرفق رقمي مسجل</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary flex items-center justify-center shrink-0" style="color: rgb(217 119 6);">
                    <x-heroicon-s-document-duplicate class="w-6 h-6" />
                </div>
            </div>
        </div>

        {{-- Two Tables: Files by Type & Docs by Category --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            {{-- Files By Type --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-gray-100 bg-slate-50/70 flex items-center gap-2">
                    <x-heroicon-o-chart-pie class="w-4 h-4 text-teal-700" />
                    <h4 class="text-xs font-black text-slate-800">توزيع الملفات حسب نوع المجلد</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-2 px-3">نوع المجلد</th>
                                <th class="py-2 px-3 w-28 text-center">العدد</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($filesByType as $type)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 font-bold text-slate-800">{{ $type->name }}</td>
                                <td class="py-2.5 px-3 text-center font-bold text-teal-800 font-mono">{{ number_format($type->files_count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Documents By Category --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-3.5 py-2.5 border-b border-gray-100 bg-slate-50/70 flex items-center gap-2">
                    <x-heroicon-o-tag class="w-4 h-4 text-secondary" style="color: rgb(217 119 6);" />
                    <h4 class="text-xs font-black text-slate-800">توزيع الوثائق حسب التصنيف الأرشيفي</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                <th class="py-2 px-3">تصنيف الوثيقة</th>
                                <th class="py-2 px-3 w-28 text-center">العدد</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($documentsByCategory as $cat)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-2.5 px-3 font-bold text-slate-800">{{ $cat->name }}</td>
                                <td class="py-2.5 px-3 text-center font-bold text-secondary font-mono" style="color: rgb(217 119 6);">{{ number_format($cat->documents_count) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Uploads Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-3.5 py-2.5 border-b border-gray-100 bg-slate-50/70 flex items-center gap-2">
                <x-heroicon-o-clock class="w-4 h-4 text-slate-600" />
                <h4 class="text-xs font-black text-slate-800">أحدث الوثائق المضافة للنظام (آخر 10)</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs min-w-[550px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-2 px-3">رقم الوثيقة</th>
                            <th class="py-2 px-3">اسم الوثيقة</th>
                            <th class="py-2 px-3">الملف التابع</th>
                            <th class="py-2 px-3 text-center">النوع</th>
                            <th class="py-2 px-3">بواسطة</th>
                            <th class="py-2 px-3 text-center">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentDocuments as $doc)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-700">#{{ $doc->document_no ?: $doc->id }}</td>
                            <td class="py-2.5 px-3 font-bold text-teal-800">{{ $doc->document_name }}</td>
                            <td class="py-2.5 px-3 text-slate-700 font-medium">{{ $doc->archiveFile ? $doc->archiveFile->file_name : '—' }}</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold text-[10px] text-slate-600 uppercase">{{ strtoupper($doc->document_type) }}</td>
                            <td class="py-2.5 px-3 text-slate-600">{{ $doc->addedBy ? $doc->addedBy->name : 'موظف' }}</td>
                            <td class="py-2.5 px-3 text-center font-mono text-[11px] text-slate-500">{{ $doc->created_at->format('Y/m/d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">لا توجد وثائق مضافة مؤخراً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-archive-layout>
