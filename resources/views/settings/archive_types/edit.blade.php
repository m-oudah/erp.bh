<x-archive-settings-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                تعديل نوع ملف الأرشيف
            </h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-10" x-data="archiveTypeForm(@js($archiveType->fields))">
        
        <form action="{{ route('settings.archive.types.update', $archiveType->id) }}" method="POST" class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <!-- Basic Info -->
            <div class="p-8 border-b border-gray-50">
                <h3 class="font-bold text-navy-dark mb-4 text-lg border-r-4 border-orange pr-3">البيانات الأساسية للنوع</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-2">اسم النوع <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $archiveType->name) }}" required class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy h-11" placeholder="مثال: ملف تنظيمي">
                        @error('name')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-2">الوصف (اختياري)</label>
                        <input type="text" name="description" value="{{ old('description', $archiveType->description) }}" class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy h-11" placeholder="وصف قصير لنوع الملف">
                    </div>
                </div>
            </div>

            <!-- Dynamic Fields -->
            <div class="p-8 bg-gray-50/30">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-navy-dark text-lg border-r-4 border-orange pr-3">الحقول المخصصة لهذا النوع</h3>
                    <button type="button" @click="addField()" class="text-navy hover:text-white hover:bg-navy text-sm font-bold flex items-center gap-2 bg-navy/5 transition-colors px-4 py-2 rounded-xl">
                        <x-heroicon-s-plus class="w-4 h-4" />
                        إضافة حقل
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="flex items-start gap-4 p-4 bg-white border border-gray-100 rounded-2xl shadow-sm relative group">
                            <input type="hidden" :name="`fields[${index}][field_name]`" x-model="field.field_name">
                            
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1.5 uppercase">اسم الحقل <span class="text-red-500">*</span></label>
                                    <input type="text" :name="`fields[${index}][field_label]`" x-model="field.field_label" required class="w-full text-sm rounded-lg border-gray-200 focus:ring-navy focus:border-navy h-10" placeholder="مثال: رقم المبنى">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 mb-1.5 uppercase">نوع الحقل</label>
                                    <select :name="`fields[${index}][field_type]`" x-model="field.field_type" class="w-full text-sm rounded-lg border-gray-200 focus:ring-navy focus:border-navy h-10">
                                        <option value="text">نص قصير</option>
                                        <option value="number">رقم</option>
                                        <option value="date">تاريخ</option>
                                        <option value="textarea">نص طويل</option>
                                    </select>
                                </div>
                                <div class="flex items-center pt-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" :name="`fields[${index}][is_required]`" x-model="field.is_required" class="w-5 h-5 rounded border-gray-300 text-navy focus:ring-navy">
                                        <span class="text-sm font-bold text-gray-600">حقل إلزامي</span>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="button" @click="removeField(index)" x-show="fields.length > 1" class="w-10 h-10 shrink-0 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors">
                                <x-heroicon-o-trash class="w-5 h-5" />
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Submit -->
            <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('settings.archive.types.index') }}" class="px-6 py-2.5 rounded-xl font-bold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-8 py-2.5 rounded-xl font-bold text-white bg-navy hover:bg-navy-dark shadow-md transition-all">
                    حفظ التعديلات
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('archiveTypeForm', (initialFields = []) => ({
                fields: initialFields.length > 0 ? initialFields.map(f => ({
                    field_name: f.field_name,
                    field_label: f.field_label,
                    field_type: f.field_type,
                    is_required: f.is_required ? true : false
                })) : [
                    { field_name: '', field_label: '', field_type: 'text', is_required: false }
                ],
                addField() {
                    this.fields.push({ field_name: '', field_label: '', field_type: 'text', is_required: false });
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                }
            }))
        })
    </script>
    @endpush
</x-archive-settings-layout>
