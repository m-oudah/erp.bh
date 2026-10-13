<x-archive-layout>
    <div class="flex justify-between items-center mb-4 px-1">
        <h3 class="font-bold text-lg text-black flex items-center gap-2">
            <x-heroicon-s-trash class="w-6 h-6 text-red-500" />
            سلة المحذوفات
        </h3>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div x-data="{ tab: 'files' }" class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        
        <!-- Tabs Header -->
        <div class="flex border-b border-gray-100 bg-gray-50/50">
            <button @click="tab = 'files'" :class="{ 'text-navy border-b-2 border-navy bg-white': tab === 'files', 'text-black hover:text-navy-dark': tab !== 'files' }" class="px-8 py-4 font-bold text-sm transition-all focus:outline-none">
                الملفات المحذوفة ({{ $deletedFiles->count() }})
            </button>
            <button @click="tab = 'documents'" :class="{ 'text-navy border-b-2 border-navy bg-white': tab === 'documents', 'text-black hover:text-navy-dark': tab !== 'documents' }" class="px-8 py-4 font-bold text-sm transition-all focus:outline-none">
                الوثائق المحذوفة ({{ $deletedDocuments->count() }})
            </button>
        </div>

        <!-- Files Tab -->
        <div x-show="tab === 'files'" class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600">
                    <thead class="bg-gray-100 text-black text-xs uppercase border-b border-gray-200 font-bold">
                        <tr>
                            <th class="px-6 py-4 font-bold text-black">رقم الملف</th>
                            <th class="px-6 py-4 font-bold text-black">الاسم</th>
                            <th class="px-6 py-4 font-bold text-black">نوع الملف</th>
                            <th class="px-6 py-4 font-bold text-black">تاريخ الحذف</th>
                            <th class="px-6 py-4 text-center font-bold text-black">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($deletedFiles as $file)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-navy">{{ $file->file_no }}</td>
                            <td class="px-6 py-4 font-bold text-black">{{ $file->file_name }}</td>
                            <td class="px-6 py-4 text-orange-dark font-semibold">{{ $file->fileType->name ?? 'غير محدد' }}</td>
                            <td class="px-6 py-4 text-black font-semibold" dir="ltr">{{ $file->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('archive.trash.files.restore', $file->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="استعادة الملف">
                                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        </button>
                                    </form>
                                    <form action="{{ route('archive.trash.files.forceDelete', $file->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="حذف نهائي">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                                لا توجد ملفات محذوفة في السلة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Documents Tab -->
        <div x-show="tab === 'documents'" style="display: none;" class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600">
                    <thead class="bg-gray-100 text-black text-xs uppercase border-b border-gray-200 font-bold">
                        <tr>
                            <th class="px-6 py-4 font-bold text-black">رقم / اسم الوثيقة</th>
                            <th class="px-6 py-4 font-bold text-black">الملف التابع له</th>
                            <th class="px-6 py-4 font-bold text-black">تاريخ الحذف</th>
                            <th class="px-6 py-4 text-center font-bold text-black">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($deletedDocuments as $doc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-black">{{ $doc->document_name }}</div>
                                <div class="text-xs text-black font-semibold">{{ $doc->document_no }}</div>
                            </td>
                            <td class="px-6 py-4 text-navy font-bold">{{ $doc->archiveFile->file_name ?? 'ملف غير معروف' }}</td>
                            <td class="px-6 py-4 text-black font-semibold" dir="ltr">{{ $doc->deleted_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ asset('storage/' . $doc->document_path) }}" target="_blank" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="عرض الوثيقة">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    <form action="{{ route('archive.trash.documents.restore', $doc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-100 transition-colors" title="استعادة الوثيقة">
                                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        </button>
                                    </form>
                                    <form action="{{ route('archive.trash.documents.forceDelete', $doc->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف النهائي؟ لا يمكن التراجع عن هذا الإجراء.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="حذف نهائي">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-medium">
                                لا توجد وثائق محذوفة في السلة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-archive-layout>
