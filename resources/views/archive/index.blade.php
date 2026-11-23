<x-archive-layout>
    <div class="space-y-5">
        
        {{-- Unified Interactive KPI / Info Cards (Matching Correspondence Module) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            {{-- 1. Files & Folders --}}
            <a href="{{ route('archive.files.index') }}"
               class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer bg-gradient-to-br from-teal-50/90 to-white border-primary/40 shadow-xs hover:shadow-md hover:border-primary ring-1 ring-primary/20">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-teal-900">سجل المجلدات والملفات</span>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-primary text-white shadow-xs group-hover:scale-105 transition-transform">
                        <x-heroicon-s-folder class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_files']) }}</div>
                    <span class="text-[11px] font-semibold text-teal-700">مضاف اليوم: {{ number_format($stats['files_today']) }}</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary"></div>
            </a>

            {{-- 2. Digital Documents --}}
            <a href="{{ route('archive.documents.index') }}"
               class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer bg-gradient-to-br from-amber-50/90 to-white border-amber-300 shadow-xs hover:shadow-md hover:border-amber-400 ring-1 ring-amber-200/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-amber-900">الوثائق والمستندات الرقمية</span>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-secondary text-white shadow-xs group-hover:scale-105 transition-transform" style="background-color: rgb(217 119 6);">
                        <x-heroicon-s-document-text class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_documents']) }}</div>
                    <span class="text-[11px] font-semibold text-amber-700">مضاف اليوم: {{ number_format($stats['documents_today']) }}</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1" style="background-color: rgb(217 119 6);"></div>
            </a>

            {{-- 3. Types & Classifications --}}
            <a href="{{ route('settings.archive.types.index') }}"
               class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer bg-gradient-to-br from-emerald-50/90 to-white border-emerald-300 shadow-xs hover:shadow-md hover:border-emerald-500 ring-1 ring-emerald-200/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-emerald-900">تصنيفات المجلدات والوثائق</span>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-emerald-600 text-white shadow-xs group-hover:scale-105 transition-transform">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($stats['total_types']) }}</div>
                    <span class="text-[11px] font-semibold text-emerald-700">تصنيفات الوثائق: {{ number_format($stats['total_categories']) }}</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
            </a>

            {{-- 4. Linked Records (Digital Integration) --}}
            <a href="{{ route('archive.reports.index') }}"
               class="p-4 rounded-2xl border transition-all duration-200 relative overflow-hidden group cursor-pointer bg-gradient-to-br from-slate-100 to-white border-slate-300 shadow-xs hover:shadow-md hover:border-slate-500 ring-1 ring-slate-200/50">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-900">الربط الرقمي للبلدية</span>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 bg-slate-700 text-white shadow-xs group-hover:scale-105 transition-transform">
                        <x-heroicon-s-link class="w-5 h-5 text-white" />
                    </div>
                </div>
                <div class="flex items-baseline justify-between">
                    <div class="text-2xl font-black text-slate-800 tracking-tight">{{ number_format($stats['linked_buildings'] + $stats['linked_memos']) }}</div>
                    <span class="text-[11px] font-semibold text-slate-700">عقارات: {{ number_format($stats['linked_buildings']) }} • مراسلات: {{ number_format($stats['linked_memos']) }}</span>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-slate-600"></div>
            </a>
        </div>

        {{-- Smart Search & Quick Filter Bar (Matching Correspondence Module) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5">
            <form method="GET" action="{{ route('archive.files.index') }}" class="flex flex-wrap items-center gap-2.5">
                {{-- Smart Search Box --}}
                <div class="flex-1 min-w-[240px] relative">
                    <input type="text"
                           name="search"
                           placeholder="ابحث برقم الملف، اسم المواطن، رقم الهوية، أو الكلمات المفتاحية..."
                           class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400 py-2">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary" style="color: rgb(217 119 6);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Type Filter --}}
                <div class="w-auto min-w-[160px]">
                    <select name="type_id" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                        <option value="">جميع أنواع المجلدات</option>
                        @foreach($archiveTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="submit" class="bg-primary hover:bg-teal-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 shadow-xs cursor-pointer">
                        <span>بحث وتصفية</span>
                    </button>
                    <a href="{{ route('archive.documents.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1">
                        <x-heroicon-o-document-text class="w-3.5 h-3.5" />
                        <span>بحث في الوثائق</span>
                    </a>
                </div>

                {{-- Counter Badge --}}
                <div class="mr-auto text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1.5 rounded-lg shrink-0">
                    الأرشيف المركزي: <span class="text-teal-700 font-extrabold">{{ number_format($stats['total_files']) }}</span> ملف • <span class="text-secondary font-extrabold" style="color: rgb(217 119 6);">{{ number_format($stats['total_documents']) }}</span> وثيقة
                </div>
            </form>
        </div>

        {{-- LATEST DATA TABLES (Redesigned with Correspondence Style) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
            
            {{-- TABLE 1: LATEST FILES & FOLDERS --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-slate-50/70">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                                <x-heroicon-s-folder class="w-4 h-4 text-teal-700" />
                            </div>
                            <h4 class="text-xs font-black text-slate-800">أحدث الملفات والمجلدات المؤرشفة</h4>
                        </div>
                        <a href="{{ route('archive.files.index') }}" class="text-[11px] font-bold text-teal-700 hover:text-teal-800 hover:underline flex items-center gap-1">
                            <span>عرض كافة الملفات</span>
                            <x-heroicon-m-chevron-left class="w-3.5 h-3.5" />
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs min-w-[440px]">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-2.5 px-3.5">رقم ونوع الملف</th>
                                    <th class="py-2.5 px-3.5">اسم الملف / المواطن</th>
                                    <th class="py-2.5 px-3.5 text-center">الوثائق</th>
                                    <th class="py-2.5 px-3.5 text-center">التاريخ</th>
                                    <th class="py-2.5 px-3.5 text-center">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($latestFiles as $file)
                                <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                                    {{-- File No & Type --}}
                                    <td class="py-3 px-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                                            <a href="{{ route('archive.files.show', $file->id) }}" class="font-mono font-bold text-teal-800 hover:text-teal-900 hover:underline">
                                                {{ $file->file_no }}
                                            </a>
                                        </div>
                                        <div class="mt-1">
                                            <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                                {{ $file->fileType?->name ?? 'عام' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Name & Citizen --}}
                                    <td class="py-3 px-3.5 max-w-xs">
                                        <a href="{{ route('archive.files.show', $file->id) }}" class="font-bold text-slate-800 hover:text-primary transition-colors block text-xs truncate" title="{{ $file->file_name }}">
                                            {{ $file->file_name }}
                                        </a>
                                        <div class="text-[10px] text-slate-400 mt-0.5 truncate">
                                            {{ $file->department?->name ?? 'الملف المركزي' }}
                                        </div>
                                    </td>

                                    {{-- Document Count Badge --}}
                                    <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full font-mono {{ $file->documents_count > 0 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-500' }}">
                                            <x-heroicon-o-document-text class="w-3 h-3" />
                                            <span>{{ $file->documents_count }}</span>
                                        </span>
                                    </td>

                                    {{-- Date --}}
                                    <td class="py-3 px-3.5 text-center whitespace-nowrap text-[11px] text-slate-500">
                                        {{ $file->created_at->format('Y/m/d') }}
                                        <span class="block text-[10px] text-slate-400">{{ $file->created_at->diffForHumans() }}</span>
                                    </td>

                                    {{-- Action --}}
                                    <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                        <a href="{{ route('archive.files.show', $file->id) }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold text-teal-800 bg-teal-50 hover:bg-teal-100 transition-colors">
                                            <span>فتح</span>
                                            <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400">لا توجد ملفات مؤرشفة حتى الآن.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="p-2.5 bg-slate-50 border-t border-gray-100 text-center">
                    <a href="{{ route('archive.files.index') }}" class="text-xs font-bold text-teal-700 hover:underline">
                        عرض سجل المجلدات والملفات بالكامل &larr;
                    </a>
                </div>
            </div>

            {{-- TABLE 2: LATEST DIGITAL DOCUMENTS --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-slate-50/70">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-secondary flex items-center justify-center shrink-0" style="color: rgb(217 119 6);">
                                <x-heroicon-s-document-text class="w-4 h-4" />
                            </div>
                            <h4 class="text-xs font-black text-slate-800">أحدث الوثائق والمستندات الرقمية</h4>
                        </div>
                        <a href="{{ route('archive.documents.index') }}" class="text-[11px] font-bold text-secondary hover:underline flex items-center gap-1" style="color: rgb(217 119 6);">
                            <span>عرض كافة الوثائق</span>
                            <x-heroicon-m-chevron-left class="w-3.5 h-3.5" />
                        </a>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-right text-xs min-w-[440px]">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-2.5 px-3.5">اسم ورقم الوثيقة</th>
                                    <th class="py-2.5 px-3.5">الملف التابع</th>
                                    <th class="py-2.5 px-3.5">التصنيف</th>
                                    <th class="py-2.5 px-3.5 text-center">التاريخ</th>
                                    <th class="py-2.5 px-3.5 text-center">معاينة</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($latestDocuments as $doc)
                                <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                                    {{-- Doc Name & Type --}}
                                    <td class="py-3 px-3.5 max-w-xs">
                                        <div class="flex items-center gap-1.5">
                                            @if(in_array(strtolower($doc->document_type), ['pdf']))
                                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-red-50 text-red-600 border border-red-200 font-mono">PDF</span>
                                            @elseif(in_array(strtolower($doc->document_type), ['png', 'jpg', 'jpeg']))
                                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-50 text-blue-600 border border-blue-200 font-mono">IMG</span>
                                            @else
                                                <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200 font-mono uppercase">{{ substr($doc->document_type ?: 'DOC', 0, 3) }}</span>
                                            @endif
                                            <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="font-bold text-slate-800 hover:text-secondary transition-colors block text-xs truncate" title="{{ $doc->document_name }}">
                                                {{ $doc->document_name }}
                                            </a>
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">
                                            #{{ $doc->document_no ?: $doc->id }} • بواسطة: {{ $doc->addedBy?->name ?? 'موظف' }}
                                        </div>
                                    </td>

                                    {{-- Linked File --}}
                                    <td class="py-3 px-3.5 whitespace-nowrap">
                                        @if($doc->archiveFile)
                                            <a href="{{ route('archive.files.show', $doc->archiveFile->id) }}" class="font-mono font-bold text-teal-800 hover:underline text-xs">
                                                {{ $doc->archiveFile->file_no }}
                                            </a>
                                            <div class="text-[10px] text-slate-400 truncate max-w-[120px]" title="{{ $doc->archiveFile->file_name }}">
                                                {{ $doc->archiveFile->file_name }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Category --}}
                                    <td class="py-3 px-3.5 whitespace-nowrap">
                                        @if($doc->category)
                                            <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                                {{ $doc->category->name }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs">—</span>
                                        @endif
                                    </td>

                                    {{-- Date --}}
                                    <td class="py-3 px-3.5 text-center whitespace-nowrap text-[11px] text-slate-500">
                                        {{ $doc->created_at->format('Y/m/d') }}
                                        <span class="block text-[10px] text-slate-400">{{ $doc->created_at->diffForHumans() }}</span>
                                    </td>

                                    {{-- Preview Action --}}
                                    <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                        <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank"
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold text-amber-900 bg-amber-50 hover:bg-amber-100 transition-colors" style="color: rgb(180 83 9);">
                                            <span>معاينة</span>
                                            <x-heroicon-m-arrow-top-right-on-square class="w-3 h-3" />
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400">لا توجد وثائق مؤرشفة حتى الآن.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="p-2.5 bg-slate-50 border-t border-gray-100 text-center">
                    <a href="{{ route('archive.documents.index') }}" class="text-xs font-bold text-secondary hover:underline" style="color: rgb(217 119 6);">
                        عرض مستودع الوثائق والمستندات بالكامل &larr;
                    </a>
                </div>
            </div>

        </div>

        {{-- DIRECTORY FOLDERS QUICK ACCESS STRIP --}}
        @if(isset($archiveTypes) && $archiveTypes->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-folder-open class="w-4 h-4 text-secondary" style="color: rgb(217 119 6);" />
                    <h4 class="text-xs font-black text-slate-800">المجلدات وتصنيفات الأرشيف الرئيسية</h4>
                </div>
                @can('settings.archive')
                <a href="{{ route('settings.archive.types.index') }}" class="text-[11px] font-bold text-slate-500 hover:text-secondary flex items-center gap-1" style="hover:color: rgb(217 119 6);">
                    <span>إدارة التصنيفات</span>
                    <x-heroicon-m-cog-6-tooth class="w-3.5 h-3.5" />
                </a>
                @endcan
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                @foreach($archiveTypes as $type)
                <a href="{{ route('archive.files.index', ['type_id' => $type->id]) }}"
                   class="p-3 rounded-xl border border-gray-100 bg-slate-50/70 hover:bg-white hover:border-secondary/40 hover:shadow-2xs transition-all flex flex-col justify-between group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-7 h-7 rounded-lg bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white transition-colors flex items-center justify-center" style="color: rgb(217 119 6);">
                            <x-heroicon-s-folder class="w-4 h-4" />
                        </div>
                        <span class="text-[10px] font-black font-mono px-2 py-0.5 rounded-full {{ $type->files_count > 0 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $type->files_count }}
                        </span>
                    </div>
                    <div class="font-bold text-xs text-slate-800 group-hover:text-secondary transition-colors truncate" title="{{ $type->name }}" style="hover:color: rgb(217 119 6);">
                        {{ $type->name }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-archive-layout>
