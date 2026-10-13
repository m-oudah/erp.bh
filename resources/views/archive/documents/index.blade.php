<x-archive-layout>
    <div x-data="{ selectedDocs: [] }">
        <div class="flex justify-between items-center mb-4 px-1 flex-wrap gap-3">
            <h3 class="font-bold text-lg text-black flex items-center gap-2">
                <x-heroicon-o-document-text class="w-5 h-5 text-black" />
                تصفح الوثائق ({{ $documents->total() }})
            </h3>
        </div>
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <form action="{{ route('archive.documents.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث باسم الوثيقة..." class="w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange shadow-sm text-sm">
                        
                        <select name="category_id" class="w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange shadow-sm text-sm font-semibold text-black">
                            <option value="">جميع تصنيفات الوثائق</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="archive_file_type_id" class="w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange shadow-sm text-sm font-semibold text-black">
                            <option value="">جميع أنواع الملفات والمجلدات</option>
                            @foreach($fileTypes as $type)
                                <option value="{{ $type->id }}" {{ request('archive_file_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="bg-navy hover:bg-navy-light text-white px-6 py-2 rounded-xl font-bold shadow-sm transition-colors flex items-center justify-center">
                            تصفية
                        </button>
                        <a href="{{ route('archive.documents.index') }}" class="bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 rounded-xl font-bold shadow-sm transition-colors flex items-center justify-center">
                            إعادة ضبط
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bulk Selection Info Bar -->
            @can('archive.documents.delete')
            <div x-cloak x-show="selectedDocs.length > 0" class="px-6 py-3 bg-red-50/70 border-b border-red-100 flex items-center justify-between transition-all">
                <div class="flex items-center gap-2 text-sm font-bold text-red-700">
                    <x-heroicon-s-check-circle class="w-5 h-5 text-red-500" />
                    <span>تم تحديد <span x-text="selectedDocs.length"></span> وثيقة</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="selectedDocs = []" class="text-xs font-semibold text-gray-600 hover:text-black px-3 py-1.5 rounded-lg hover:bg-white transition-colors">
                        إلغاء التحديد
                    </button>
                    <form action="{{ route('archive.documents.bulk-destroy') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من نقل جميع الوثائق المحددة إلى سلة المحذوفات؟');" class="inline">
                        @csrf
                        @method('DELETE')
                        <template x-for="id in selectedDocs" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-700 text-xs font-bold flex items-center gap-1.5 transition-colors px-4 py-2 rounded-xl shadow-sm">
                            <x-heroicon-s-trash class="w-4 h-4" />
                            نقل المحدد إلى سلة المحذوفات (<span x-text="selectedDocs.length"></span>)
                        </button>
                    </form>
                </div>
            </div>
            @endcan

            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600">
                    <thead class="bg-gray-100 text-black text-xs uppercase border-b border-gray-200 font-bold">
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">
                                @can('archive.documents.delete')
                                <input type="checkbox" @change="
                                    if($event.target.checked) {
                                        selectedDocs = [{{ $documents->pluck('id')->map(fn($id) => "'$id'")->join(',') }}];
                                    } else {
                                        selectedDocs = [];
                                    }
                                " :checked="selectedDocs.length === {{ $documents->count() }} && {{ $documents->count() }} > 0" class="w-5 h-5 rounded border-gray-300 text-red-500 focus:ring-red-500 bg-white shadow-sm cursor-pointer" title="تحديد الكل">
                                @endcan
                            </th>
                            <th class="px-6 py-4 font-bold text-black">اسم الوثيقة</th>
                            <th class="px-6 py-4 font-bold text-black">التصنيف</th>
                            <th class="px-6 py-4 font-bold text-black">ملف / مجلد الأب</th>
                            <th class="px-6 py-4 font-bold text-center text-black">النوع</th>
                            <th class="px-6 py-4 text-center font-bold text-black">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50 transition-colors" :class="selectedDocs.includes('{{ $doc->id }}') ? 'bg-red-50/40' : ''">
                            <td class="px-6 py-4 text-center">
                                @can('archive.documents.delete')
                                <input type="checkbox" value="{{ $doc->id }}" x-model="selectedDocs" class="w-5 h-5 rounded border-gray-300 text-red-500 focus:ring-red-500 bg-white shadow-sm cursor-pointer">
                                @endcan
                            </td>
                            <td class="px-6 py-4 font-bold text-black">
                                {{ $doc->document_name }}
                                @if($doc->document_no)
                                <span class="block text-xs font-semibold text-black mt-0.5">رقم: {{ $doc->document_no }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($doc->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-gray-100 text-black">
                                        {{ $doc->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($doc->archiveFile)
                                    <a href="{{ route('archive.files.show', $doc->archiveFile->id) }}" class="text-navy font-bold hover:underline">
                                        {{ $doc->archiveFile->file_no }}
                                    </a>
                                    <span class="block text-xs font-semibold text-black mt-0.5">{{ Str::limit($doc->archiveFile->file_name, 30) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(in_array(strtolower($doc->document_type), ['pdf']))
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 font-bold text-xs" title="PDF">PDF</span>
                                @elseif(in_array(strtolower($doc->document_type), ['png', 'jpg', 'jpeg']))
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-500 font-bold text-xs" title="صورة">IMG</span>
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-500 font-bold text-xs uppercase" title="{{ $doc->document_type }}">{{ substr($doc->document_type, 0, 3) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="عرض الوثيقة">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @can('archive.documents.delete')
                                    <form action="{{ route('archive.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من نقل هذه الوثيقة إلى سلة المحذوفات؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="نقل إلى سلة المحذوفات">
                                            <x-heroicon-s-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                لا يوجد وثائق مطابقة للبحث.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($documents->hasPages())
            <div class="p-4 border-t border-gray-50">
                {{ $documents->links() }}
            </div>
            @endif
        </div>
    </div>
</x-archive-layout>
