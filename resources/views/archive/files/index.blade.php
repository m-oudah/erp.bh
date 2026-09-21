<x-archive-layout>
    <div class="flex justify-between items-center mb-4 px-1">
        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <x-heroicon-o-folder class="w-5 h-5 text-gray-500" />
            تصفح المجلدات
        </h3>
    </div>
    
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('archive.files.index') }}" method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث برقم الهوية، رقم الملف، أو اسم المواطن..." class="w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange shadow-sm text-sm">
                </div>
                <button type="submit" class="bg-navy hover:bg-navy-light text-white px-8 py-2 rounded-xl font-bold shadow-sm transition-colors">
                    تصفية
                </button>
                <a href="{{ route('archive.files.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-xl font-bold shadow-sm transition-colors flex items-center justify-center">
                    إعادة ضبط
                </a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm text-gray-600">
                <thead class="bg-white text-gray-400 text-xs uppercase border-b border-gray-100 font-bold">
                    <tr>
                        <th class="px-6 py-4 font-bold">رقم الملف</th>
                        <th class="px-6 py-4 font-bold">الاسم</th>
                        <th class="px-6 py-4 font-bold">رقم الهوية</th>
                        <th class="px-6 py-4 font-bold">الجوال</th>
                        <th class="px-6 py-4 text-center font-bold">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($files as $file)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-navy">{{ $file->file_no }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800">{{ $file->file_name }}</td>
                        <td class="px-6 py-4">{{ $file->id_no ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $file->file_mobile ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('archive.files.show', $file->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="عرض">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('archive.files.edit', $file->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-50 text-orange hover:bg-orange-100 transition-colors" title="تعديل">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            لا يوجد ملفات مطابقة للبحث.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($files->hasPages())
        <div class="p-4 border-t border-gray-50">
            {{ $files->links() }}
        </div>
        @endif
    </div>
</x-archive-layout>
