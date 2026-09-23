<x-archive-settings-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                إضافة نوع ملف جديد
            </h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto pb-10" x-data="archiveTypeForm()">
        
        <form action="{{ route('settings.archive.types.store') }}" method="POST" class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
            @csrf

            <!-- Basic Info -->
            <div class="p-8 border-b border-gray-50">
                <h3 class="font-bold text-navy-dark mb-4 text-lg border-r-4 border-orange pr-3">البيانات الأساسية للنوع</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" value="اسم النوع (مثال: ملف تنظيمي)*" class="font-bold text-gray-700" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('name')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="description" value="وصف (اختياري)" class="text-gray-700" />
                        <x-text-input id="description" name="description" type="text" class="mt-1 block w-full rounded-xl border-gray-200" :value="old('description')" />
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                </div>
            </div>

            <!-- Fields Builder -->
            <div class="p-8 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-navy-dark text-lg border-r-4 border-orange pr-3">الحقول المخصصة لهذا النوع</h3>
                    <button type="button" @click="addField()" class="bg-orange hover:bg-orange-dark text-white text-xs font-bold py-2 px-4 rounded-lg flex items-center gap-1 transition-colors">
                        <x-heroicon-s-plus class="w-4 h-4" />
                        إضافة حقل
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex flex-col md:flex-row gap-4 items-end relative group">
                            
                            <!-- Remove Button -->
                            <button type="button" @click="removeField(index)" class="absolute top-2 left-2 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-lg p-1 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5" />
                            </button>

                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-600 mb-1">الاسم البرمجي للحقل (بالانجليزية)</label>
                                <input type="text" x-model="field.field_name" :name="`fields[${index}][field_name]`" class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy" placeholder="e.g. citizen_name" required pattern="[a-zA-Z0-9_]+">
                            </div>
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-600 mb-1">اسم الحقل (للعرض)</label>
                                <input type="text" x-model="field.field_label" :name="`fields[${index}][field_label]`" class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy" placeholder="e.g. اسم المواطن" required>
                            </div>
                            <div class="flex-1 w-full">
                                <label class="block text-xs font-bold text-gray-600 mb-1">نوع البيانات</label>
                                <select x-model="field.field_type" :name="`fields[${index}][field_type]`" class="w-full text-sm rounded-xl border-gray-200 focus:ring-navy focus:border-navy">
                                    <option value="text">نص (Text)</option>
                                    <option value="number">رقم (Number)</option>
                                    <option value="date">تاريخ (Date)</option>
                                    <option value="textarea">نص طويل (Textarea)</option>
                                </select>
                            </div>
                            <div class="w-full md:w-auto pb-2 px-2 flex items-center">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="field.is_required" :name="`fields[${index}][is_required]`" value="1" class="rounded border-gray-300 text-navy focus:ring-navy">
                                    <span class="text-sm font-bold text-gray-700">مطلوب؟</span>
                                </label>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="fields.length === 0" class="text-center py-8 text-gray-400 bg-white border border-dashed border-gray-200 rounded-2xl">
                    لا يوجد حقول مخصصة حتى الآن. يمكنك إضافة حقول لتظهر عند إنشاء ملف من هذا النوع.
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-white">
                <a href="{{ route('settings.archive-types.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-bold transition-colors">
                    إلغاء
                </a>
                <button type="submit" class="px-8 py-2.5 bg-navy hover:bg-navy-light text-white rounded-xl font-bold shadow-md transition-colors">
                    حفظ وإضافة
                </button>
            </div>
        </form>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('archiveTypeForm', () => ({
                fields: [
                    { field_name: 'id_no', field_label: 'رقم الهوية', field_type: 'text', is_required: false },
                ],
                addField() {
                    this.fields.push({
                        field_name: '',
                        field_label: '',
                        field_type: 'text',
                        is_required: false
                    });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                }
            }))
        })
    </script>
    @endpush
</x-archive-settings-layout>
