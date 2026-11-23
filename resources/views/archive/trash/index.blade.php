<x-archive-layout>
    <div x-data="{ tab: 'files' }" class="space-y-4">
        
        {{-- Header & Sub-Tabs --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <button type="button"
                        @click="tab = 'files'"
                        :class="tab === 'files' ? 'bg-secondary/10 text-secondary-dark font-extrabold border border-secondary/20 shadow-xs' : 'text-slate-600 hover:bg-slate-50 font-bold'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all cursor-pointer">
                    <x-heroicon-o-folder class="w-4 h-4" />
                    <span>الملفات المحذوفة</span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full"
                          :class="tab === 'files' ? 'bg-secondary text-white' : 'bg-slate-100 text-slate-600'" style="background-color: {{ $deletedFiles->count() > 0 ? '' : '' }}">
                        {{ $deletedFiles->count() }}
                    </span>
                </button>

                <button type="button"
                        @click="tab = 'documents'"
                        :class="tab === 'documents' ? 'bg-secondary/10 text-secondary-dark font-extrabold border border-secondary/20 shadow-xs' : 'text-slate-600 hover:bg-slate-50 font-bold'"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm flex items-center gap-2 transition-all cursor-pointer">
                    <x-heroicon-o-document-text class="w-4 h-4" />
                    <span>الوثائق المحذوفة</span>
                    <span class="text-[10px] font-mono px-2 py-0.5 rounded-full"
                          :class="tab === 'documents' ? 'bg-secondary text-white' : 'bg-slate-100 text-slate-600'">
                        {{ $deletedDocuments->count() }}
                    </span>
                </button>
            </div>

            <div class="text-[11px] font-bold text-red-600 bg-red-50 border border-red-100 px-3 py-1.5 rounded-xl flex items-center gap-1.5">
                <x-heroicon-s-trash class="w-3.5 h-3.5 text-red-500" />
                <span>سلة المحذوفات المؤقتة للأرشيف</span>
            </div>
        </div>

        {{-- Files Tab Content --}}
        <div x-show="tab === 'files'" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-fade-in">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs min-w-[650px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3">رقم الملف</th>
                            <th class="py-2.5 px-3">اسم المواطن / الملف</th>
                            <th class="py-2.5 px-3">نوع الملف</th>
                            <th class="py-2.5 px-3 text-center">تاريخ ووقت الحذف</th>
                            <th class="py-2.5 px-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($deletedFiles as $file)
                        <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                            {{-- File No --}}
                            <td class="py-2.5 px-3 font-mono font-bold text-teal-800 whitespace-nowrap">
                                {{ $file->file_no }}
                            </td>

                            {{-- File Name --}}
                            <td class="py-2.5 px-3 font-bold text-slate-800">
                                {{ $file->file_name }}
                            </td>

                            {{-- Type --}}
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $file->fileType->name ?? 'غير محدد' }}
                                </span>
                            </td>

                            {{-- Deleted At --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap text-slate-500 text-[11px] font-mono">
                                {{ $file->deleted_at ? $file->deleted_at->format('Y/m/d H:i') : '—' }}
                            </td>

                            {{-- Actions --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <form action="{{ route('archive.trash.files.restore', $file->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors cursor-pointer" title="استعادة الملف">
                                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        </button>
                                    </form>

                                    <form action="{{ route('archive.trash.files.forceDelete', $file->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer" title="حذف نهائي">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 text-xs">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-heroicon-o-check-circle class="w-8 h-8 text-slate-300" />
                                    <span>سلة محذوفات الملفات فارغة حالياً.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Documents Tab Content --}}
        <div x-show="tab === 'documents'" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-fade-in">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs min-w-[650px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3">اسم ورقم الوثيقة</th>
                            <th class="py-2.5 px-3">الملف التابع</th>
                            <th class="py-2.5 px-3 text-center">تاريخ ووقت الحذف</th>
                            <th class="py-2.5 px-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($deletedDocuments as $doc)
                        <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                            {{-- Doc Info --}}
                            <td class="py-2.5 px-3 font-bold text-slate-800">
                                {{ $doc->document_name }}
                                @if($doc->document_no)
                                    <span class="inline-block font-mono text-[10px] text-teal-800 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-100 mt-0.5">
                                        #{{ $doc->document_no }}
                                    </span>
                                @endif
                            </td>

                            {{-- File Info --}}
                            <td class="py-2.5 px-3 font-mono text-teal-800 font-bold whitespace-nowrap">
                                {{ $doc->archiveFile->file_name ?? $doc->archiveFile->file_no ?? 'ملف غير معروف' }}
                            </td>

                            {{-- Deleted At --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap text-slate-500 text-[11px] font-mono">
                                {{ $doc->deleted_at ? $doc->deleted_at->format('Y/m/d H:i') : '—' }}
                            </td>

                            {{-- Actions --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank"
                                       class="p-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors cursor-pointer" title="معاينة الوثيقة">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>

                                    <form action="{{ route('archive.trash.documents.restore', $doc->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors cursor-pointer" title="استعادة الوثيقة">
                                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        </button>
                                    </form>

                                    <form action="{{ route('archive.trash.documents.forceDelete', $doc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer" title="حذف نهائي">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 text-xs">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-heroicon-o-check-circle class="w-8 h-8 text-slate-300" />
                                    <span>سلة محذوفات الوثائق فارغة حالياً.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-archive-layout>
