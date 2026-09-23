<x-archive-settings-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                تعديل تصنيف وثيقة
            </h1>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto pb-10">
        
        <form action="{{ route('settings.archive.document-categories.update', $documentCategory->id) }}" method="POST" class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-8 border-b border-gray-50">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-2">اسم التصنيف <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $documentCategory->name) }}" required class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy h-11" placeholder="مثال: فاتورة، هوية، الخ">
                        @error('name')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    
                    <div class="flex items-center pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $documentCategory->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-navy focus:ring-navy">
                            <span class="text-sm font-bold text-gray-600">التصنيف نشط</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 flex justify-end gap-3">
                <a href="{{ route('settings.archive.document-categories.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-8 py-2.5 rounded-xl font-bold text-white bg-navy hover:bg-navy-dark shadow-md transition-all">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>
</x-archive-settings-layout>
