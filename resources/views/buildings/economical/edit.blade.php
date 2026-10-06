<x-buildings-layout>
    <div class="space-y-6">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 tracking-tight">تعديل بيانات النشاط الاقتصادي</h1>
                <p class="text-sm text-gray-500 mt-1">تحديث معلومات النشاط رقم {{ $economical->id }}</p>
            </div>
            
            <a href="{{ route('buildings.economical.show', $economical->id) }}" class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors font-medium shadow-sm">
                <x-heroicon-o-arrow-right class="w-4 h-4" />
                عودة للتفاصيل
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6">
                <form action="{{ route('buildings.economical.update', $economical->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Trade Name -->
                        <div>
                            <label for="job_formal_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم التجاري</label>
                            <input type="text" name="job_formal_name" id="job_formal_name" value="{{ old('job_formal_name', $economical->job_formal_name) }}" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('job_formal_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Sector -->
                        <div>
                            <label for="job_sector_id" class="block text-sm font-medium text-gray-700 mb-2">القطاع</label>
                            <select name="job_sector_id" id="job_sector_id" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">غير مصنف</option>
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->id }}" {{ old('job_sector_id', $economical->job_sector_id) == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_sector_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- License Status -->
                        <div>
                            <label for="isLicensed" class="block text-sm font-medium text-gray-700 mb-2">حالة الترخيص</label>
                            <select name="isLicensed" id="isLicensed" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="1" {{ old('isLicensed', $economical->isLicensed) == 1 ? 'selected' : '' }}>مرخص</option>
                                <option value="2" {{ old('isLicensed', $economical->isLicensed) == 2 ? 'selected' : '' }}>غير مرخص</option>
                            </select>
                            @error('isLicensed') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Danger Status -->
                        <div>
                            <label for="isDanger" class="block text-sm font-medium text-gray-700 mb-2">مستوى الخطورة</label>
                            <select name="isDanger" id="isDanger" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="0" {{ old('isDanger', $economical->isDanger) == 0 ? 'selected' : '' }}>عادية</option>
                                <option value="1" {{ old('isDanger', $economical->isDanger) == 1 ? 'selected' : '' }}>مخاطرة خطرة</option>
                            </select>
                            @error('isDanger') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                            <textarea name="notes" id="notes" rows="4" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $economical->notes) }}</textarea>
                            @error('notes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('buildings.economical.show', $economical->id) }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition-colors flex items-center gap-2">
                            <x-heroicon-o-check class="w-5 h-5" />
                            حفظ التعديلات
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-buildings-layout>
