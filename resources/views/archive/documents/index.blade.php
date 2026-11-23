<x-archive-layout :show-folders="false" :show-doc-categories="true">
    <div x-data="{ selectedDocs: [] }" class="space-y-4">
        
        {{-- Search & Filter Bar (Correspondence Module Style) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5">
            <form action="{{ route('archive.documents.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                {{-- Smart Search Box --}}
                <div class="flex-1 min-w-[220px] relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="ابحث باسم الوثيقة، رقم المستند، أو الكلمات المفتاحية..."
                           class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400 py-2">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary" style="color: rgb(217 119 6);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Document Category Filter --}}
                <div class="w-auto min-w-[180px]">
                    <select name="category_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                        <option value="">جميع تصنيفات الوثائق</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="submit" class="bg-primary hover:bg-teal-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 shadow-xs cursor-pointer">
                        <span>تصفية</span>
                    </button>
                    @if (request()->hasAny(['search', 'category_id']))
                        <a href="{{ route('archive.documents.index') }}" class="bg-gray-100 hover:bg-gray-200 text-slate-600 px-2.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1" title="مسح التصفية">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>مسح</span>
                        </a>
                    @endif
                </div>

                {{-- Counter Badge --}}
                <div class="mr-auto text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1.5 rounded-lg shrink-0">
                    المعروض: <span class="text-teal-700 font-extrabold">{{ $documents->total() }}</span> وثيقة رقمية
                </div>
            </form>
        </div>

        {{-- Bulk Selection Bar --}}
        @can('archive.documents.delete')
        <div x-cloak x-show="selectedDocs.length > 0" class="px-4 py-2.5 bg-red-50/80 border border-red-200 rounded-2xl flex items-center justify-between text-xs animate-fade-in">
            <div class="flex items-center gap-2 font-bold text-red-800">
                <x-heroicon-s-check-circle class="w-4 h-4 text-red-600" />
                <span>تم تحديد <span class="font-mono text-sm underline" x-text="selectedDocs.length"></span> وثيقة</span>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="selectedDocs = []" class="text-xs font-bold text-slate-600 hover:text-slate-900 px-3 py-1.5 rounded-xl hover:bg-white transition-colors cursor-pointer">
                    إلغاء التحديد
                </button>
                <form action="{{ route('archive.documents.bulk-destroy') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من نقل جميع الوثائق المحددة إلى سلة المحذوفات؟');" class="inline">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedDocs" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="text-white bg-red-600 hover:bg-red-700 text-xs font-bold flex items-center gap-1.5 transition-colors px-3.5 py-1.5 rounded-xl shadow-xs cursor-pointer">
                        <x-heroicon-s-trash class="w-3.5 h-3.5" />
                        <span>نقل المحدد إلى سلة المحذوفات (<span x-text="selectedDocs.length"></span>)</span>
                    </button>
                </form>
            </div>
        </div>
        @endcan

        {{-- Documents Table (Correspondence Module Style) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs min-w-[800px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3 w-10 text-center">
                                @can('archive.documents.delete')
                                <input type="checkbox" @change="
                                    if($event.target.checked) {
                                        selectedDocs = [{{ $documents->pluck('id')->map(fn($id) => "'$id'")->join(',') }}];
                                    } else {
                                        selectedDocs = [];
                                    }
                                " :checked="selectedDocs.length === {{ $documents->count() }} && {{ $documents->count() }} > 0" class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white shadow-xs cursor-pointer" title="تحديد الكل">
                                @endcan
                            </th>
                            <th class="py-2.5 px-3">اسم ورقم الوثيقة</th>
                            <th class="py-2.5 px-3">الملف / المجلد التابع</th>
                            <th class="py-2.5 px-3">التصنيف الأرشيفي</th>
                            <th class="py-2.5 px-3 text-center">النوع</th>
                            <th class="py-2.5 px-3 text-center">تاريخ الإضافة</th>
                            <th class="py-2.5 px-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($documents as $doc)
                        <tr class="transition-colors duration-150 hover:bg-slate-50/80" :class="selectedDocs.includes('{{ $doc->id }}') ? 'bg-red-50/30' : ''">
                            {{-- Checkbox --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                @can('archive.documents.delete')
                                <input type="checkbox" value="{{ $doc->id }}" x-model="selectedDocs" class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500 bg-white shadow-xs cursor-pointer">
                                @endcan
                            </td>

                            {{-- Document Name --}}
                            <td class="py-2.5 px-3 max-w-xs">
                                <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="font-bold text-slate-800 hover:text-primary transition-colors block text-xs truncate" title="{{ $doc->document_name }}">
                                    {{ $doc->document_name }}
                                </a>
                                @if($doc->document_no)
                                    <span class="inline-block font-mono text-[10px] text-teal-800 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-100 mt-0.5">
                                        #{{ $doc->document_no }}
                                    </span>
                                @endif
                            </td>

                            {{-- Parent File --}}
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                @if($doc->archiveFile)
                                    <a href="{{ route('archive.files.show', $doc->archiveFile->id) }}" class="font-mono font-bold text-teal-800 hover:underline text-xs">
                                        {{ $doc->archiveFile->file_no }}
                                    </a>
                                    <div class="text-[10px] text-slate-400 truncate max-w-[130px]" title="{{ $doc->archiveFile->file_name }}">
                                        {{ $doc->archiveFile->file_name }}
                                    </div>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Category --}}
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                @if($doc->category)
                                    <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                        {{ $doc->category->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            {{-- Document Type --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                @if(in_array(strtolower($doc->document_type), ['pdf']))
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-red-50 text-red-600 border border-red-200 font-mono" title="ملف PDF">PDF</span>
                                @elseif(in_array(strtolower($doc->document_type), ['png', 'jpg', 'jpeg']))
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 text-blue-600 border border-blue-200 font-mono" title="صورة">IMG</span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200 font-mono uppercase" title="{{ $doc->document_type }}">{{ substr($doc->document_type ?: 'DOC', 0, 3) }}</span>
                                @endif
                            </td>

                            {{-- Created Date --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap text-slate-500 text-[11px]">
                                {{ $doc->created_at ? $doc->created_at->format('Y/m/d') : '—' }}
                                <span class="block text-[10px] text-slate-400">{{ $doc->created_at ? $doc->created_at->diffForHumans() : '' }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank"
                                       class="p-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors cursor-pointer" title="عرض ومعاينة الوثيقة">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>

                                    @can('archive.documents.delete')
                                    <form action="{{ route('archive.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من نقل هذه الوثيقة إلى سلة المحذوفات؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer" title="نقل إلى سلة المحذوفات">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-heroicon-o-document-text class="w-8 h-8 text-slate-300" />
                                    <span>لا توجد وثائق مطابقة لشروط البحث والتصفية.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($documents->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-slate-50/50">
                {{ $documents->links() }}
            </div>
            @endif
        </div>

    </div>
</x-archive-layout>
