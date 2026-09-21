<x-archive-layout>
    <div class="flex justify-between items-center mb-2 px-1">
        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <x-heroicon-o-plus-circle class="w-5 h-5 text-gray-500" />
            إضافة ملف / مجلد جديد
        </h3>
    </div>

    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        <form action="{{ route('archive.files.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- رقم الملف -->
                <div>
                    <x-input-label for="file_no" value="رقم الملف (مرجع)*" class="font-bold text-gray-700" />
                    <x-text-input id="file_no" name="file_no" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('file_no')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('file_no')" />
                </div>

                <!-- اسم المواطن -->
                <div>
                    <x-input-label for="file_name" value="اسم المواطن / الملف*" class="font-bold text-gray-700" />
                    <x-text-input id="file_name" name="file_name" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('file_name')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('file_name')" />
                </div>

                <!-- رقم الهوية -->
                <div>
                    <x-input-label for="id_no" value="رقم الهوية" class="text-gray-700" />
                    <x-text-input id="id_no" name="id_no" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('id_no')" />
                    <x-input-error class="mt-2" :messages="$errors->get('id_no')" />
                </div>

                <!-- رقم الجوال -->
                <div>
                    <x-input-label for="file_mobile" value="رقم الجوال" class="text-gray-700" />
                    <x-text-input id="file_mobile" name="file_mobile" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('file_mobile')" />
                    <x-input-error class="mt-2" :messages="$errors->get('file_mobile')" />
                </div>

                <!-- القطعة -->
                <div>
                    <x-input-label for="qetaa" value="القطعة" class="text-gray-700" />
                    <x-text-input id="qetaa" name="qetaa" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('qetaa')" />
                    <x-input-error class="mt-2" :messages="$errors->get('qetaa')" />
                </div>

                <!-- القسيمة -->
                <div>
                    <x-input-label for="qasema" value="القسيمة" class="text-gray-700" />
                    <x-text-input id="qasema" name="qasema" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('qasema')" />
                    <x-input-error class="mt-2" :messages="$errors->get('qasema')" />
                </div>
            </div>

            <!-- ملاحظات -->
            <div>
                <x-input-label for="notes" value="ملاحظات" class="text-gray-700" />
                <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-200 focus:border-orange focus:ring-orange rounded-xl shadow-sm" rows="3">{{ old('notes') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
            </div>

            <div class="flex items-center justify-end mt-6 gap-3 pt-6 border-t border-gray-50">
                <a href="{{ route('archive.files.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-bold transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-8 py-2.5 bg-navy hover:bg-navy-light text-white rounded-xl font-bold shadow-md transition-colors">
                    حفظ الملف
                </button>
            </div>
        </form>
    </div>
</x-archive-layout>
