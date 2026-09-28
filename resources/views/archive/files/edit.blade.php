<x-archive-layout>
    <div class="flex justify-between items-center mb-2 px-1">
        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <x-heroicon-o-pencil-square class="w-5 h-5 text-gray-500" />
            تعديل الملف: {{ $file->file_name }}
        </h3>
        <a href="{{ route('archive.files.show', $file->id) }}" class="text-sm text-gray-500 hover:text-navy flex items-center gap-1 font-medium">
            <x-heroicon-o-arrow-right class="w-4 h-4" />
            العودة للملف
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden"
         x-data="archiveEditForm({{ $types->toJson() }}, {{ json_encode(old('dynamic_data', $file->dynamic_data ?? [])) }}, '{{ old('archive_file_type_id', $file->archive_file_type_id) }}')">
        
        <form action="{{ route('archive.files.update', $file->id) }}" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- File Type Selection -->
            <div class="mb-6 p-6 bg-blue-50/50 rounded-2xl border border-blue-100">
                <x-input-label for="archive_file_type_id" value="نوع الملف*" class="font-bold text-navy-dark text-lg mb-2 block" />
                <select id="archive_file_type_id" name="archive_file_type_id" x-model="selectedTypeId" @change="updateFields()" class="block w-full md:w-1/2 rounded-xl border-blue-200 focus:border-navy focus:ring-navy shadow-sm text-base py-3" required>
                    <option value="">-- اختر نوع الملف --</option>
                    <template x-for="type in types" :key="type.id">
                        <option :value="type.id" x-text="type.name" :selected="type.id == selectedTypeId"></option>
                    </template>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('archive_file_type_id')" />
            </div>

            <div x-show="selectedTypeId" x-transition class="space-y-6">
                <h4 class="font-bold text-gray-800 border-r-4 border-orange pr-3">البيانات الأساسية للملف</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- رقم الملف -->
                    <div>
                        <x-input-label for="file_no" value="رقم الملف (مرجع)*" class="font-bold text-gray-700" />
                        <x-text-input id="file_no" name="file_no" type="text" class="mt-1 block w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange" value="{{ old('file_no', $file->file_no) }}" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('file_no')" />
                    </div>

                    <!-- اسم المواطن -->
                    <div>
                        <x-input-label for="file_name" value="اسم المواطن / الملف*" class="font-bold text-gray-700" />
                        <x-text-input id="file_name" name="file_name" type="text" class="mt-1 block w-full rounded-xl border-gray-200 focus:border-orange focus:ring-orange" value="{{ old('file_name', $file->file_name) }}" required />
                        <x-input-error class="mt-2" :messages="$errors->get('file_name')" />
                    </div>
                </div>

                <!-- Dynamic Fields Section -->
                <div x-show="currentFields.length > 0" class="mt-8">
                    <h4 class="font-bold text-gray-800 border-r-4 border-orange pr-3 mb-4">البيانات المخصصة (بناءً على نوع الملف)</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="field in currentFields" :key="field.id">
                            <div :class="field.field_type === 'textarea' ? 'md:col-span-2' : ''">
                                <label :for="field.field_name" class="block font-medium text-sm text-gray-700">
                                    <span x-text="field.field_label"></span>
                                    <span x-show="field.is_required" class="text-red-500">*</span>
                                </label>
                                
                                <!-- Text Input -->
                                <template x-if="field.field_type === 'text'">
                                    <input type="text" :id="field.field_name" :name="`dynamic_data[${field.field_name}]`" x-model="dynamicData[field.field_name]" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange focus:ring-orange" :required="field.is_required">
                                </template>
                                
                                <!-- Number Input -->
                                <template x-if="field.field_type === 'number'">
                                    <input type="number" :id="field.field_name" :name="`dynamic_data[${field.field_name}]`" x-model="dynamicData[field.field_name]" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange focus:ring-orange" :required="field.is_required">
                                </template>

                                <!-- Date Input -->
                                <template x-if="field.field_type === 'date'">
                                    <input type="date" :id="field.field_name" :name="`dynamic_data[${field.field_name}]`" x-model="dynamicData[field.field_name]" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange focus:ring-orange" :required="field.is_required">
                                </template>

                                <!-- Textarea -->
                                <template x-if="field.field_type === 'textarea'">
                                    <textarea :id="field.field_name" :name="`dynamic_data[${field.field_name}]`" x-model="dynamicData[field.field_name]" rows="3" class="mt-1 block w-full rounded-xl border-gray-200 shadow-sm focus:border-orange focus:ring-orange" :required="field.is_required"></textarea>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-10 gap-4 pt-8 border-t border-gray-100">
                    <a href="{{ route('archive.files.show', $file->id) }}" class="px-8 py-3 bg-gray-100 text-gray-700 rounded-2xl hover:bg-gray-200 font-bold text-lg flex items-center gap-2 transition-colors">
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                        إلغاء
                    </a>
                    <button type="submit" class="px-10 py-3 bg-navy hover:bg-navy-light text-white rounded-2xl font-bold text-lg shadow-lg shadow-navy/20 flex items-center gap-2 transition-colors">
                        <x-heroicon-o-document-check class="w-6 h-6" />
                        حفظ التعديلات
                    </button>
                </div>
            </div>
            
            <div x-show="!selectedTypeId" class="text-center py-12 text-gray-400">
                <x-heroicon-o-document-text class="w-16 h-16 mx-auto text-gray-200 mb-3" />
                <p>يرجى اختيار نوع الملف أولاً لتظهر الحقول المخصصة له.</p>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('archiveEditForm', (types, oldDynamicData, oldSelectedTypeId) => ({
                types: types,
                selectedTypeId: oldSelectedTypeId || '',
                currentFields: [],
                dynamicData: oldDynamicData || {},

                init() {
                    if (this.selectedTypeId) {
                        this.updateFields();
                    }
                },

                updateFields() {
                    if (!this.selectedTypeId) {
                        this.currentFields = [];
                        return;
                    }
                    
                    const type = this.types.find(t => t.id == this.selectedTypeId);
                    this.currentFields = type ? type.fields : [];
                    
                    // Initialize dynamicData keys if not present
                    this.currentFields.forEach(field => {
                        if (this.dynamicData[field.field_name] === undefined) {
                            this.dynamicData[field.field_name] = '';
                        }
                    });
                }
            }))
        })
    </script>
    @endpush
</x-archive-layout>
