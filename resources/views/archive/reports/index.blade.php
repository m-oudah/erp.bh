<x-archive-layout>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 px-1 print:hidden">
        <h3 class="font-bold text-xl text-gray-800 flex items-center gap-2">
            <x-heroicon-s-chart-bar class="w-7 h-7 text-orange" />
            تقارير الأرشيف
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('archive.reports.export') }}" class="flex items-center gap-2 bg-green-50 text-green-700 hover:bg-green-100 px-4 py-2 rounded-xl font-bold text-sm transition-colors shadow-sm">
                <x-heroicon-s-document-arrow-down class="w-5 h-5" />
                تصدير (CSV)
            </a>
            <button onclick="window.print()" class="flex items-center gap-2 bg-navy text-white hover:bg-navy-dark px-4 py-2 rounded-xl font-bold text-sm transition-colors shadow-sm">
                <x-heroicon-s-printer class="w-5 h-5" />
                طباعة التقرير
            </button>
        </div>
    </div>

    <!-- Print Header (Hidden on Screen) -->
    <div class="hidden print:block mb-8 text-center border-b-2 border-gray-200 pb-4">
        <h1 class="text-2xl font-bold text-black mb-2">تقرير نظام الأرشيف</h1>
        <p class="text-gray-500 text-sm">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-6 flex items-center gap-6 print:border-gray-300 print:shadow-none">
            <div class="w-16 h-16 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0">
                <x-heroicon-s-folder-open class="w-8 h-8" />
            </div>
            <div>
                <h4 class="text-gray-400 font-bold text-sm mb-1">إجمالي الملفات</h4>
                <div class="text-3xl font-black text-navy">{{ number_format($totalFiles) }}</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-6 flex items-center gap-6 print:border-gray-300 print:shadow-none">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                <x-heroicon-s-document-duplicate class="w-8 h-8" />
            </div>
            <div>
                <h4 class="text-gray-400 font-bold text-sm mb-1">إجمالي الوثائق المرفوعة</h4>
                <div class="text-3xl font-black text-navy">{{ number_format($totalDocuments) }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Files By Type -->
        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden print:border-gray-300 print:shadow-none">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                <h4 class="font-bold text-navy-dark flex items-center gap-2">
                    <x-heroicon-o-chart-pie class="w-5 h-5 text-gray-400" />
                    توزيع الملفات حسب النوع
                </h4>
            </div>
            <div class="p-0">
                <table class="w-full text-right text-sm">
                    <thead class="bg-white text-gray-400 text-xs uppercase border-b border-gray-100 font-bold">
                        <tr>
                            <th class="px-6 py-3">نوع الملف</th>
                            <th class="px-6 py-3 w-32 text-center">العدد</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($filesByType as $type)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-bold text-gray-700">{{ $type->name }}</td>
                            <td class="px-6 py-3 text-center font-bold text-navy bg-gray-50/30">{{ number_format($type->files_count) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents By Category -->
        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden print:border-gray-300 print:shadow-none">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                <h4 class="font-bold text-navy-dark flex items-center gap-2">
                    <x-heroicon-o-tag class="w-5 h-5 text-gray-400" />
                    توزيع الوثائق حسب التصنيف
                </h4>
            </div>
            <div class="p-0">
                <table class="w-full text-right text-sm">
                    <thead class="bg-white text-gray-400 text-xs uppercase border-b border-gray-100 font-bold">
                        <tr>
                            <th class="px-6 py-3">تصنيف الوثيقة</th>
                            <th class="px-6 py-3 w-32 text-center">العدد</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($documentsByCategory as $cat)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-bold text-gray-700">{{ $cat->name }}</td>
                            <td class="px-6 py-3 text-center font-bold text-navy bg-gray-50/30">{{ number_format($cat->documents_count) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden print:border-gray-300 print:shadow-none">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
            <h4 class="font-bold text-navy-dark flex items-center gap-2">
                <x-heroicon-o-clock class="w-5 h-5 text-gray-400" />
                أحدث الوثائق المضافة (آخر 10)
            </h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-white text-gray-400 text-xs uppercase border-b border-gray-100 font-bold">
                    <tr>
                        <th class="px-6 py-3">رقم الوثيقة</th>
                        <th class="px-6 py-3">الاسم</th>
                        <th class="px-6 py-3">الملف التابع</th>
                        <th class="px-6 py-3">النوع</th>
                        <th class="px-6 py-3">بواسطة</th>
                        <th class="px-6 py-3">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentDocuments as $doc)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3 font-bold text-gray-700">{{ $doc->document_no }}</td>
                        <td class="px-6 py-3 font-bold text-navy">{{ $doc->document_name }}</td>
                        <td class="px-6 py-3">{{ $doc->archiveFile ? $doc->archiveFile->file_name : '-' }}</td>
                        <td class="px-6 py-3 font-bold text-gray-500">{{ strtoupper($doc->document_type) }}</td>
                        <td class="px-6 py-3">{{ $doc->addedBy ? $doc->addedBy->name : '-' }}</td>
                        <td class="px-6 py-3" dir="ltr">{{ $doc->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">لا توجد وثائق مضافة مؤخراً</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Custom CSS for Print View -->
    <style>
        @media print {
            body { background: #fff !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-\[0_2px_10px_-3px_rgba\(6\,81\,237\,0\.05\)\] { box-shadow: none !important; }
            /* Hide the sidebar from archive-layout */
            aside, .w-1\/4 { display: none !important; }
            .w-3\/4, .lg\:w-3\/4 { width: 100% !important; margin: 0 !important; }
        }
    </style>
</x-archive-layout>
