<x-archive-layout>
    <div class="flex justify-between items-center mb-2 px-1">
        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <x-heroicon-s-folder-open class="w-6 h-6 text-orange" />
            تفاصيل الملف: {{ $file->file_name }}
        </h3>
    </div>

    <!-- File Info Card -->
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center">
            <h4 class="font-bold text-navy-dark text-sm">البيانات الأساسية</h4>
            <a href="{{ route('archive.files.edit', $file->id) }}" class="text-orange hover:text-orange-dark text-xs font-bold flex items-center gap-1 bg-orange-50 px-3 py-1.5 rounded-lg">
                <x-heroicon-o-pencil-square class="w-4 h-4" />
                تعديل البيانات
            </a>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-xs text-gray-400 mb-1">رقم الملف</div>
                <div class="font-bold text-gray-800">{{ $file->file_no }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">اسم المواطن / الملف</div>
                <div class="font-bold text-gray-800">{{ $file->file_name }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">رقم الهوية</div>
                <div class="font-bold text-gray-800">{{ $file->id_no ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">رقم الجوال</div>
                <div class="font-bold text-gray-800">{{ $file->file_mobile ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">القطعة / القسيمة</div>
                <div class="font-bold text-gray-800">{{ $file->qetaa ?? '-' }} / {{ $file->qasema ?? '-' }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 mb-1">تاريخ الإضافة</div>
                <div class="font-bold text-gray-800">{{ $file->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center">
            <h4 class="font-bold text-navy-dark text-sm flex items-center gap-2">
                <x-heroicon-o-document-duplicate class="w-5 h-5 text-gray-400" />
                الوثائق المؤرشفة ({{ $file->documents->count() }})
            </h4>
        </div>
        
        <div class="p-6">
            <!-- Upload Form -->
            <form action="{{ route('archive.documents.store') }}" method="POST" enctype="multipart/form-data" class="mb-8 p-6 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                @csrf
                <input type="hidden" name="archive_file_id" value="{{ $file->id }}">
                
                <div class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full">
                        <x-input-label for="document_name" value="عنوان الوثيقة*" class="text-sm font-bold text-gray-700" />
                        <x-text-input id="document_name" name="document_name" type="text" class="mt-1 block w-full rounded-xl border-gray-200 text-sm" required />
                    </div>
                    <div class="flex-1 w-full">
                        <x-input-label for="document_no" value="رقم الوثيقة (إن وجد)" class="text-sm text-gray-600" />
                        <x-text-input id="document_no" name="document_no" type="text" class="mt-1 block w-full rounded-xl border-gray-200 text-sm" />
                    </div>
                    <div class="flex-1 w-full">
                        <x-input-label for="document_file" value="الملف المرفق (PDF, JPG, PNG, DOC)*" class="text-sm font-bold text-gray-700" />
                        <input type="file" name="document_file" id="document_file" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange hover:file:bg-orange-100 cursor-pointer" required>
                    </div>
                    <div>
                        <button type="submit" class="bg-navy hover:bg-navy-light text-white px-6 py-2.5 rounded-xl font-bold shadow-sm transition-colors w-full md:w-auto mt-6 text-sm">
                            رفع الوثيقة
                        </button>
                    </div>
                </div>
                @error('document_file') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
            </form>

            <!-- Documents Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($file->documents as $document)
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow group relative">
                    <div class="h-32 bg-gray-50 flex items-center justify-center relative border-b border-gray-50">
                        @if(in_array(strtolower($document->document_type), ['jpg', 'jpeg', 'png']))
                            <img src="{{ Storage::url($document->document_path) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <x-heroicon-o-document class="w-10 h-10 text-gray-300" />
                        @endif
                        
                        <div class="absolute inset-0 bg-navy/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <a href="{{ Storage::url($document->document_path) }}" target="_blank" class="w-10 h-10 rounded-full bg-white text-navy flex items-center justify-center hover:bg-orange hover:text-white transition-colors" title="عرض الملف">
                                <x-heroicon-s-eye class="w-5 h-5" />
                            </a>
                            <form action="{{ route('archive.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-full bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="حذف الملف">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 text-sm truncate" title="{{ $document->document_name }}">{{ $document->document_name }}</h4>
                        <div class="text-[10px] text-gray-400 mt-1.5 flex justify-between font-medium">
                            <span>{{ strtoupper($document->document_type) }}</span>
                            <span>{{ $document->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <x-heroicon-o-document-magnifying-glass class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                    <p class="text-sm">لا توجد وثائق مؤرشفة في هذا الملف.<br>استخدم النموذج أعلاه لإضافة وثائق.</p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-archive-layout>
