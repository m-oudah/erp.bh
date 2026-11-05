<x-buildings-layout>
    <script>
        function buildingCreator(initialFileNo, initialArchiveFile) {
            const cleanInitialFileNo = (initialFileNo && String(initialFileNo).trim() !== '0') ? String(initialFileNo).trim() : '';

            return {
                isSubmitting: false,
                selectedZone: '{{ old('zone_id', '') }}',

                // Archive File Linking State
                archiveFileNo: cleanInitialFileNo,
                archiveFileId: '{{ old('archive_file_id', '') }}',
                currentArchiveFile: cleanInitialFileNo ? initialArchiveFile : null,
                showArchiveModal: false,
                archiveSearchQuery: '',
                archiveSearchResults: [],
                selectedArchiveFile: null,
                isSearchingArchive: false,
                archiveErrorMessage: '',

                openArchiveModal() {
                    this.showArchiveModal = true;
                    this.archiveSearchQuery = '';
                    this.archiveSearchResults = [];
                    this.selectedArchiveFile = null;
                    this.archiveErrorMessage = '';
                    document.body.style.overflow = 'hidden';
                    this.$nextTick(() => {
                        if (this.$refs.archiveSearchInput) {
                            this.$refs.archiveSearchInput.focus();
                        }
                    });
                },

                closeArchiveModal() {
                    this.showArchiveModal = false;
                    this.archiveErrorMessage = '';
                    document.body.style.overflow = '';
                },

                async performArchiveSearch() {
                    const q = (this.archiveSearchQuery || '').trim();
                    if (q.length < 2) {
                        this.archiveSearchResults = [];
                        this.isSearchingArchive = false;
                        return;
                    }

                    this.isSearchingArchive = true;
                    this.archiveErrorMessage = '';
                    try {
                        const params = new URLSearchParams({ q });
                        const res = await fetch(`{{ route('buildings.buildings.search-archive-files') }}?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.archiveSearchResults = json.data;
                        } else {
                            this.archiveSearchResults = [];
                        }
                    } catch (err) {
                        console.error(err);
                        this.archiveSearchResults = [];
                    } finally {
                        this.isSearchingArchive = false;
                    }
                },

                selectArchiveFile(file) {
                    this.selectedArchiveFile = file;
                    this.archiveErrorMessage = '';
                },

                confirmArchiveSelection() {
                    if (!this.selectedArchiveFile) return;
                    this.archiveFileNo = this.selectedArchiveFile.file_no;
                    this.archiveFileId = this.selectedArchiveFile.id;
                    this.currentArchiveFile = {
                        id: this.selectedArchiveFile.id,
                        file_no: this.selectedArchiveFile.file_no,
                        file_name: this.selectedArchiveFile.file_name,
                        file_type: this.selectedArchiveFile.file_type,
                        documents_count: this.selectedArchiveFile.documents_count
                    };
                    this.closeArchiveModal();
                },

                clearArchiveFile() {
                    this.archiveFileNo = '';
                    this.archiveFileId = '';
                    this.currentArchiveFile = null;
                    this.selectedArchiveFile = null;
                }
            };
        }

        function buildingPhotoCreator() {
            return {
                previewUrl: null,
                fileName: '',
                fileSize: '',
                isDragging: false,

                handleFileSelect(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.processFile(file);
                    }
                },

                handleDrop(e) {
                    this.isDragging = false;
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const input = this.$refs.fileInput;
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        input.files = dataTransfer.files;
                        this.processFile(file);
                    }
                },

                processFile(file) {
                    if (file.size > 10 * 1024 * 1024) {
                        alert('حجم الصورة كبير جداً، يرجى اختيار صورة أقل من 10 ميجابايت.');
                        return;
                    }
                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024 > 1024) 
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' ميجابايت' 
                        : (file.size / 1024).toFixed(1) + ' كيلوبايت';

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },

                clearPhoto() {
                    this.previewUrl = null;
                    this.fileName = '';
                    this.fileSize = '';
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                }
            };
        }

        window.buildingCreator = buildingCreator;
        window.buildingPhotoCreator = buildingPhotoCreator;
    </script>

    <div x-data="buildingCreator({{ json_encode(old('file_number', '')) }}, null)" class="space-y-6 max-w-7xl mx-auto pb-16">

        {{-- Form Start --}}
        <form action="{{ route('buildings.buildings.store') }}"
              method="POST"
              enctype="multipart/form-data"
              id="building-create-form"
              @submit="isSubmitting = true">
            @csrf

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('buildings.buildings.index') }}"
                       class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0"
                       title="الرجوع لقائمة المباني">
                        <x-heroicon-o-arrow-right class="w-5 h-5" />
                    </a>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="bg-emerald-50 text-emerald-700 text-xs px-3 py-1 rounded-xl font-bold border border-emerald-200 flex items-center gap-1">
                                <x-heroicon-s-plus-circle class="w-3.5 h-3.5 text-emerald-600" />
                                إضافة مبنى جديد
                            </span>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">
                                تسجيل وتوثيق مبنى جديد
                            </h1>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">أدخل بيانات المبنى، والموقع الجغرافي، وملف الأرشيف التنظيمي، والمواصفات الإنشائية والمالية</p>
                    </div>
                </div>
            </div>

            {{-- Error Alerts --}}
            @if (isset($errors) && $errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-xs mb-6 shadow-xs animate-fade-in">
                <div class="font-bold flex items-center gap-2 mb-2 text-sm">
                    <x-heroicon-s-x-circle class="w-5 h-5 text-red-500" />
                    يرجى تصحيح الملاحظات التالية قبل الحفظ:
                </div>
                <ul class="list-disc list-inside space-y-1 pr-2 font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Hero Section: Photo Drag-and-Drop (Left 1/3) + Data & Location (Right 2/3) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch mb-8">

                {{-- Data & Location Card (Right 2/3 in RTL) --}}
                <div class="lg:col-span-2 flex flex-col min-w-0">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-full flex flex-col justify-between">
                        <div>
                            {{-- Card Header --}}
                            <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shrink-0">
                                        <x-heroicon-s-map-pin class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-extrabold text-gray-800">البيانات الأساسية والموقع</h2>
                                        <p class="text-xs text-gray-400">تحديد معلومات المبنى والموقع الجغرافي والتنظيمي</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Grid of Form Inputs --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- مجموعة الموقع الجغرافي --}}
                                <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3.5">
                                    <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                        <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                            <x-heroicon-s-map class="w-4 h-4" />
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">الموقع الجغرافي</span>
                                    </div>

                                    {{-- الحي / المنطقة --}}
                                    <div>
                                        <label for="zone_id" class="block text-xs font-bold text-gray-700 mb-1">المنطقة / الحي</label>
                                        <select id="zone_id" name="zone_id" x-model="selectedZone"
                                                class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                            <option value="">— اختر المنطقة / الحي —</option>
                                            @foreach($zones as $zone)
                                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                                    {{ $zone->name ?: 'مجمع ' . $zone->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- المنطقة الفرعية --}}
                                    <div>
                                        <label for="subzone_id" class="block text-xs font-bold text-gray-700 mb-1">المنطقة الفرعية</label>
                                        <select id="subzone_id" name="subzone_id"
                                                class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                            <option value="">— اختر المنطقة الفرعية —</option>
                                            @foreach($subzones as $subzone)
                                                <option value="{{ $subzone->id }}" {{ old('subzone_id') == $subzone->id ? 'selected' : '' }}>
                                                    {{ $subzone->name ?: ('منطقة فرعية ' . $subzone->id) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- الشارع --}}
                                    <div>
                                        <label for="street_id" class="block text-xs font-bold text-gray-700 mb-1">الشارع</label>
                                        <select id="street_id" name="street_id"
                                                class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                            <option value="">— اختر الشارع —</option>
                                            @foreach($streets as $street)
                                                <option value="{{ $street->id }}" {{ old('street_id') == $street->id ? 'selected' : '' }}>
                                                    {{ $street->street_name ? $street->street_name . ' (' . $street->street_number . ')' : 'شارع ' . $street->street_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- رقم القطعة ورقم القسيمة --}}
                                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                                        <div>
                                            <label for="block_number" class="block text-xs font-bold text-gray-700 mb-1">رقم القطعة / الحوض</label>
                                            <input type="text" id="block_number" name="block_number"
                                                   value="{{ old('block_number') }}"
                                                   placeholder="مثال: 12"
                                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                        </div>
                                        <div>
                                            <label for="parcel_number" class="block text-xs font-bold text-gray-700 mb-1">رقم القسيمة</label>
                                            <input type="text" id="parcel_number" name="parcel_number"
                                                   value="{{ old('parcel_number') }}"
                                                   placeholder="مثال: 45"
                                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                        </div>
                                    </div>
                                </div>

                                {{-- مجموعة البيانات التنظيمية ورقم المبنى --}}
                                <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3.5">
                                    <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                        <div class="p-1.5 bg-orange/10 text-orange rounded-lg">
                                            <x-heroicon-s-building-office class="w-4 h-4" />
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">البيانات التنظيمية ورقم المبنى</span>
                                    </div>

                                    {{-- رقم المبنى --}}
                                    <div>
                                        <label for="building_number" class="block text-xs font-bold text-gray-700 mb-1">
                                            رقم المبنى <span class="text-orange">*</span>
                                        </label>
                                        <input type="text" id="building_number" name="building_number"
                                               value="{{ old('building_number') }}"
                                               required
                                               placeholder="مثال: 9A أو 105"
                                               class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    </div>

                                    {{-- ملف الأرشيف التنظيمي --}}
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-xs font-bold text-gray-700">
                                                ملف الأرشيف التنظيمي
                                            </label>
                                            <div>
                                                <template x-if="archiveFileNo">
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded-md">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                        محدد للربط
                                                    </span>
                                                </template>
                                                <template x-if="!archiveFileNo">
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-400 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded-md">
                                                        غير محدد
                                                    </span>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- الحقول المخفية لترحيل القيمة --}}
                                        <input type="hidden" id="file_number" name="file_number" :value="archiveFileNo || ''">
                                        <input type="hidden" id="archive_file_id" name="archive_file_id" :value="archiveFileId || ''">

                                        {{-- بطاقة الملف المحدد --}}
                                        <template x-if="archiveFileNo">
                                            <div class="p-3 bg-white border border-gray-200 rounded-xl space-y-2.5 shadow-2xs">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="flex items-start gap-2.5 min-w-0">
                                                        <div class="w-8 h-8 rounded-lg bg-orange/10 text-orange flex items-center justify-center shrink-0 mt-0.5">
                                                            <x-heroicon-s-folder class="w-4 h-4" />
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="text-xs font-bold text-gray-900 truncate" x-text="currentArchiveFile ? currentArchiveFile.file_name : ('ملف رقم ' + archiveFileNo)"></div>
                                                            <div class="flex items-center gap-1.5 flex-wrap text-[11px] mt-0.5">
                                                                <span class="font-mono font-extrabold text-orange-dark bg-orange/10 px-2 py-0.5 rounded" dir="ltr" x-text="archiveFileNo"></span>
                                                                <template x-if="currentArchiveFile && currentArchiveFile.file_type">
                                                                    <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-[10px] font-semibold" x-text="currentArchiveFile.file_type"></span>
                                                                </template>
                                                                <template x-if="currentArchiveFile && currentArchiveFile.documents_count !== undefined">
                                                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-semibold" x-text="currentArchiveFile.documents_count + ' وثيقة'"></span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- أزرار الإجراءات --}}
                                                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                                                    <button type="button"
                                                            @click="openArchiveModal()"
                                                            class="flex-1 inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-lg text-orange bg-orange/10 hover:bg-orange/20 border border-orange/20 transition-all cursor-pointer">
                                                        <x-heroicon-s-arrow-path class="w-3.5 h-3.5" />
                                                        <span>تغيير الملف</span>
                                                    </button>

                                                    <button type="button"
                                                            @click="clearArchiveFile()"
                                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-all cursor-pointer"
                                                            title="إلغاء تحديد الملف">
                                                        <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                                        <span>إلغاء</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- بطاقة البحث إذا لم يحدد ملف --}}
                                        <template x-if="!archiveFileNo">
                                            <div class="p-4 rounded-xl bg-orange/5 border border-dashed border-orange/30 text-center space-y-2">
                                                <div class="w-8 h-8 rounded-lg bg-orange/10 text-orange flex items-center justify-center mx-auto shadow-inner">
                                                    <x-heroicon-s-folder-plus class="w-4 h-4" />
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-gray-800">ربط المبنى بملف أرشيف تنظيم</div>
                                                    <div class="text-[11px] text-gray-400 mt-0.5">يمكنك البحث في ملفات الأرشيف واختيار الملف المناسب</div>
                                                </div>
                                                <button type="button"
                                                        @click="openArchiveModal()"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange hover:bg-orange-dark text-white rounded-lg text-xs font-bold shadow-xs hover:shadow-sm transition-all cursor-pointer">
                                                    <x-heroicon-s-magnifying-glass class="w-3.5 h-3.5" />
                                                    <span>البحث في الأرشيف واختيار الملف</span>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- الاسم الاعتباري للمبنى --}}
                                    <div>
                                        <label for="building_name" class="block text-xs font-bold text-gray-700 mb-1">
                                            الاسم الاعتباري للمبنى
                                        </label>
                                        <input type="text" id="building_name" name="building_name"
                                               value="{{ old('building_name') }}"
                                               placeholder="مثال: عمارة الشفاء، برج الأندلس"
                                               class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    </div>

                                    {{-- حالة المبنى ونوع الملكية --}}
                                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                                        <div>
                                            <label for="building_status_id" class="block text-xs font-bold text-gray-700 mb-1">حالة المبنى</label>
                                            <select id="building_status_id" name="building_status_id"
                                                    class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-2.5 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                                <option value="">— غير محدد —</option>
                                                @foreach($buildingStatuses as $status)
                                                    <option value="{{ $status->id }}" {{ old('building_status_id') == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="building_property_type_id" class="block text-xs font-bold text-gray-700 mb-1">نوع الملكية</label>
                                            <select id="building_property_type_id" name="building_property_type_id"
                                                    class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-2.5 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                                <option value="">— غير محدد —</option>
                                                @foreach($buildingPropertyTypes as $propType)
                                                    <option value="{{ $propType->id }}" {{ old('building_property_type_id') == $propType->id ? 'selected' : '' }}>
                                                        {{ $propType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Photo Drag-and-Drop Area (Left 1/3 in RTL) --}}
                <div class="lg:col-span-1 flex flex-col min-w-0" x-data="buildingPhotoCreator()">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 h-full flex flex-col justify-between">
                        <div>
                            {{-- Card Header --}}
                            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                        <x-heroicon-s-camera class="w-4 h-4" />
                                    </div>
                                    <div>
                                        <h2 class="text-sm font-extrabold text-gray-800">صورة المبنى</h2>
                                        <p class="text-[10px] text-gray-400">سحب وإفلات صورة الواجهة</p>
                                    </div>
                                </div>
                                <template x-if="previewUrl">
                                    <button type="button" @click="clearPhoto()"
                                            class="text-[11px] text-red-500 hover:text-red-700 font-bold flex items-center gap-1 p-1 hover:bg-red-50 rounded-lg transition-colors"
                                            title="إزالة الصورة">
                                        <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                        <span>إزالة</span>
                                    </button>
                                </template>
                            </div>

                            {{-- Hidden Inputs --}}
                            <input type="file"
                                   id="photo-input"
                                   name="photo"
                                   accept="image/jpeg,image/png,image/webp,image/jpg"
                                   class="hidden"
                                   x-ref="fileInput"
                                   @change="handleFileSelect($event)">

                            {{-- Drag & Drop Box --}}
                            <div class="relative w-full aspect-4/3 min-h-[220px] rounded-2xl border-2 border-dashed transition-all overflow-hidden flex flex-col items-center justify-center cursor-pointer group"
                                 :class="isDragging ? 'border-orange bg-orange/5 ring-4 ring-orange/10 scale-[1.01]' : (previewUrl ? 'border-gray-200 bg-gray-900' : 'border-gray-200 hover:border-orange/60 bg-gray-50/60 hover:bg-orange/5')"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)"
                                 @click="$refs.fileInput.click()">

                                {{-- Image Preview State --}}
                                <template x-if="previewUrl">
                                    <div class="relative w-full h-full">
                                        <img :src="previewUrl"
                                             alt="معاينة صورة المبنى"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-black/20 to-transparent flex flex-col justify-end p-4">
                                            <div class="flex items-center justify-between text-white">
                                                <div>
                                                    <span class="inline-block text-[10px] px-2 py-0.5 rounded-full font-bold bg-white/20 backdrop-blur-md mb-1">
                                                        صورة محددة للرفع
                                                    </span>
                                                    <div class="text-xs font-bold truncate max-w-[170px]" x-text="fileName || 'واجهة المبنى الرئيسية'"></div>
                                                    <div class="text-[10px] text-white/70" x-show="fileSize" x-text="fileSize"></div>
                                                </div>
                                                <button type="button" class="p-2 bg-white/20 hover:bg-white/30 backdrop-blur-md rounded-xl text-white text-xs transition-colors shrink-0" title="انقر لتغيير الصورة">
                                                    <x-heroicon-s-arrow-path class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Empty Dropzone State --}}
                                <template x-if="!previewUrl">
                                    <div class="p-5 text-center flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 rounded-2xl bg-orange/10 text-orange flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-inner">
                                            <x-heroicon-o-arrow-up-tray class="w-7 h-7" />
                                        </div>
                                        <div class="text-xs font-extrabold text-gray-800 mb-1">
                                            اسحب وأفلت صورة المبنى هنا
                                        </div>
                                        <div class="text-[11px] text-orange-dark font-bold mb-2">أو انقر للاختيار من جهازك</div>
                                        <div class="text-[10px] text-gray-400">JPG, PNG, WEBP حتى 10 ميجابايت</div>
                                    </div>
                                </template>

                            </div>

                            {{-- File Info / Reset notice --}}
                            <div class="mt-3 flex items-center justify-between text-[11px]">
                                <template x-if="previewUrl">
                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                        <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                        جاهز للرفع مع بيانات المبنى
                                    </span>
                                </template>
                                <template x-if="!previewUrl">
                                    <span class="text-gray-400">الصورة اختيارية ويمكن إضافتها لاحقاً</span>
                                </template>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 mt-4">
                            <button type="button" @click="$refs.fileInput.click()"
                                    class="w-full py-2 px-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold rounded-xl text-xs transition-colors border border-gray-200 flex items-center justify-center gap-1.5">
                                <x-heroicon-o-photo class="w-4 h-4 text-gray-500" />
                                <span x-text="previewUrl ? 'اختيار صورة بديلة' : 'تحديد صورة من الملفات'"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Main Tabs Section --}}
            <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Tabs Header --}}
                <div id="building-create-tabs-header" class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/80 px-2 pt-2">
                    <style>#building-create-tabs-header::-webkit-scrollbar{display:none}#building-create-tabs-header{-ms-overflow-style:none;scrollbar-width:none;}</style>
                    <button type="button" @click="tab = 'basic'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'basic', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'basic'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-building-library class="w-4 h-4" x-bind:class="{'text-orange': tab === 'basic'}" />
                        بيانات الإنشاء والحالة
                    </button>
                    <button type="button" @click="tab = 'materials'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'materials', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'materials'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-swatch class="w-4 h-4" x-bind:class="{'text-orange': tab === 'materials'}" />
                        المواصفات والتشطيب
                    </button>
                    <button type="button" @click="tab = 'services'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'services', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'services'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-wrench-screwdriver class="w-4 h-4" x-bind:class="{'text-orange': tab === 'services'}" />
                        الخدمات والمرافق
                    </button>
                    <button type="button" @click="tab = 'financial'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'financial', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'financial'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-banknotes class="w-4 h-4" x-bind:class="{'text-orange': tab === 'financial'}" />
                        البيانات المالية
                    </button>
                    <button type="button" @click="tab = 'owner'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'owner', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'owner'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-user-plus class="w-4 h-4" x-bind:class="{'text-orange': tab === 'owner'}" />
                        المالك الأولي / المكلف
                    </button>
                </div>

                {{-- Tabs Content --}}
                <div class="p-6 md:p-8">

                    {{-- Tab 1: Basic & Construction --}}
                    <div x-show="tab === 'basic'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- نوع المبنى --}}
                            <div>
                                <label for="building_type_id" class="block text-xs font-bold text-gray-700 mb-1.5">نوع المبنى</label>
                                <select id="building_type_id" name="building_type_id"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— اختر نوع المبنى —</option>
                                    @foreach($buildingTypes as $bType)
                                        <option value="{{ $bType->id }}" {{ old('building_type_id') == $bType->id ? 'selected' : '' }}>
                                            {{ $bType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- تاريخ الإنشاء --}}
                            <div>
                                <label for="building_date" class="block text-xs font-bold text-gray-700 mb-1.5">تاريخ الإنشاء</label>
                                <input type="date" id="building_date" name="building_date"
                                       value="{{ old('building_date') }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- المشرف المسؤول --}}
                            <div>
                                <label for="supervisor_id" class="block text-xs font-bold text-gray-700 mb-1.5">المشرف المسؤول</label>
                                <select id="supervisor_id" name="supervisor_id"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                            {{ $supervisor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- حالة البناء الخارجية --}}
                            <div>
                                <label for="out_status" class="block text-xs font-bold text-gray-700 mb-1.5">حالة البناء الخارجية</label>
                                <select id="out_status" name="out_status"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('out_status') == 1 ? 'selected' : '' }}>مشطب كامل</option>
                                    <option value="2" {{ old('out_status') == 2 ? 'selected' : '' }}>مشطب جزئي</option>
                                    <option value="3" {{ old('out_status') == 3 ? 'selected' : '' }}>غير مشطب</option>
                                </select>
                            </div>

                            {{-- الحالة العامة للمبنى --}}
                            <div>
                                <label for="overall_status" class="block text-xs font-bold text-gray-700 mb-1.5">الحالة العامة للمبنى</label>
                                <select id="overall_status" name="overall_status"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('overall_status') == 1 ? 'selected' : '' }}>ممتازة</option>
                                    <option value="2" {{ old('overall_status') == 2 ? 'selected' : '' }}>جيدة</option>
                                    <option value="3" {{ old('overall_status') == 3 ? 'selected' : '' }}>سيئة</option>
                                </select>
                            </div>

                        </div>

                        {{-- خصائص المبنى والخيارات الخاصة --}}
                        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                            <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                                <x-heroicon-s-tag class="w-4 h-4 text-orange" />
                                خصائص وحالات خاصة
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="historical" value="1" {{ old('historical') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">مبنى تاريخي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="adding_new_floor" value="1" {{ old('adding_new_floor') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">إضافة طابق جديد</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="abandond" value="1" {{ old('abandond') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">مبنى مهجور</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="building_special_case" value="1" {{ old('building_special_case') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">حالة خاصة</span>
                                </label>
                            </div>
                        </div>

                        {{-- استخدامات المبنى --}}
                        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                            <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                                <x-heroicon-s-building-storefront class="w-4 h-4 text-orange" />
                                استخدامات المبنى (حدد كل ما ينطبق)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
                                @php
                                    $currentUseIds = old('uses', []);
                                @endphp
                                @foreach($buildingUses as $bUse)
                                <label class="flex items-center gap-2 p-2.5 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="uses[]" value="{{ $bUse->id }}" {{ in_array($bUse->id, $currentUseIds) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">{{ $bUse->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- الملاحظات --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="ownership_notes" class="block text-xs font-bold text-gray-700 mb-1.5">ملاحظات الملكية</label>
                                <textarea id="ownership_notes" name="ownership_notes" rows="3"
                                          placeholder="أي تفاصيل تتعلق بالملكية أو أصحاب العقار..."
                                          class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('ownership_notes') }}</textarea>
                            </div>
                            <div>
                                <label for="overall_status_notes" class="block text-xs font-bold text-gray-700 mb-1.5">ملاحظات الحالة العامة</label>
                                <textarea id="overall_status_notes" name="overall_status_notes" rows="3"
                                          placeholder="ملاحظات حول السلامة الإنشائية أو الصيانة..."
                                          class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('overall_status_notes') }}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- Tab 2: Materials & Finishes --}}
                    <div x-show="tab === 'materials'" class="space-y-6" style="display: none;">

                        {{-- مواد البناء الأساسية --}}
                        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                            <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                                <x-heroicon-s-swatch class="w-4 h-4 text-orange" />
                                مواد البناء الأساسية
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                                @php
                                    $currentMatIds = old('materials', []);
                                @endphp
                                @foreach($buildingMaterials as $mat)
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="materials[]" value="{{ $mat->id }}" {{ in_array($mat->id, $currentMatIds) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">{{ $mat->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- التشطيب الخارجي --}}
                        <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                            <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center gap-1.5">
                                <x-heroicon-s-paint-brush class="w-4 h-4 text-orange" />
                                نوع التشطيب الخارجي
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 mb-3">
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_qesara" value="1" {{ old('finish_qesara') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">قصارة</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_italian" value="1" {{ old('finish_italian') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">إيطالي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_tiles" value="1" {{ old('finish_tiles') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">سيراميك / بلاط</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_j_stone" value="1" {{ old('finish_j_stone') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">حجر قدسي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_granuleet" value="1" {{ old('finish_granuleet') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">جرانيليت</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_other" value="1" {{ old('finish_other') ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">أخرى</span>
                                </label>
                            </div>
                            <div>
                                <label for="finish_notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات التشطيب الخارجي</label>
                                <input type="text" id="finish_notes" name="finish_notes"
                                       value="{{ old('finish_notes') }}"
                                       placeholder="تفاصيل إضافية حول تشطيب الواجهات..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>
                        </div>

                        {{-- مواد الجدران وسقف الطابق الأخير --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- مواد الجدران --}}
                            <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60 space-y-3">
                                <h4 class="text-xs font-bold text-gray-700 pb-2 border-b border-gray-200">مواد الجدران</h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_stone" value="1" {{ old('wall_stone') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_reinforced_concrete" value="1" {{ old('wall_reinforced_concrete') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>خرسانة مسلحة</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_concrete_stone" value="1" {{ old('wall_concrete_stone') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر باطون</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_sand_stone" value="1" {{ old('wall_sand_stone') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر رملي</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="text" name="wall_notes" value="{{ old('wall_notes') }}"
                                           placeholder="ملاحظات الجدران..."
                                           class="w-full text-xs bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-gray-800">
                                </div>
                            </div>

                            {{-- سقف الطابق الأخير --}}
                            <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60 space-y-3">
                                <h4 class="text-xs font-bold text-gray-700 pb-2 border-b border-gray-200">سقف الطابق الأخير</h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_concrete" value="1" {{ old('last_floor_concrete') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>خرسانة</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_asbast" value="1" {{ old('last_floor_asbast') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>أسبست</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_carmeed" value="1" {{ old('last_floor_carmeed') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>قرميد</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_zenko" value="1" {{ old('last_floor_zenko') ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>زينكو / صفيح</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="text" name="last_floor_notes" value="{{ old('last_floor_notes') }}"
                                           placeholder="ملاحظات السقف الأخير..."
                                           class="w-full text-xs bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-gray-800">
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Tab 3: Services & Utilities --}}
                    <div x-show="tab === 'services'" class="space-y-6" style="display: none;">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- مصدر المياه --}}
                            <div>
                                <label for="water_source" class="block text-xs font-bold text-gray-700 mb-1.5">مصدر المياه</label>
                                <select id="water_source" name="water_source"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('water_source') == 1 ? 'selected' : '' }}>شبكة البلدية</option>
                                    <option value="2" {{ old('water_source') == 2 ? 'selected' : '' }}>بئر خاص</option>
                                    <option value="3" {{ old('water_source') == 3 ? 'selected' : '' }}>أخرى</option>
                                </select>
                            </div>

                            {{-- شبكة الصرف الصحي --}}
                            <div>
                                <label for="sewage" class="block text-xs font-bold text-gray-700 mb-1.5">الصرف الصحي</label>
                                <select id="sewage" name="sewage"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('sewage') == 1 ? 'selected' : '' }}>بلدية</option>
                                    <option value="2" {{ old('sewage') == 2 ? 'selected' : '' }}>بئر خاص / حفرة امتصاصية</option>
                                    <option value="3" {{ old('sewage') == 3 ? 'selected' : '' }}>لا يوجد</option>
                                </select>
                            </div>

                            {{-- عدد المصاعد --}}
                            <div>
                                <label for="elevators_count" class="block text-xs font-bold text-gray-700 mb-1.5">عدد المصاعد</label>
                                <input type="number" id="elevators_count" name="elevators_count" min="0"
                                       value="{{ old('elevators_count', 0) }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- أدراج الهروب --}}
                            <div>
                                <label for="escape_stairs" class="block text-xs font-bold text-gray-700 mb-1.5">عدد أدراج الهروب</label>
                                <input type="number" id="escape_stairs" name="escape_stairs" min="0"
                                       value="{{ old('escape_stairs', 0) }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- مصدر الكهرباء --}}
                            <div>
                                <label for="electricity_source" class="block text-xs font-bold text-gray-700 mb-1.5">مصدر الكهرباء (الاشتراك)</label>
                                <input type="number" id="electricity_source" name="electricity_source"
                                       value="{{ old('electricity_source') }}"
                                       placeholder="رقم أو كود المصدر"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                        </div>
                    </div>

                    {{-- Tab 4: Financial Data --}}
                    <div x-show="tab === 'financial'" class="space-y-6" style="display: none;">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                            {{-- مساحة الأرض المرخصة --}}
                            <div>
                                <label for="land_area" class="block text-xs font-bold text-gray-700 mb-1.5">مساحة الأرض المرخصة (م²)</label>
                                <input type="number" step="0.01" id="land_area" name="land_area" min="0"
                                       value="{{ old('land_area') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- رسوم التطوير --}}
                            <div>
                                <label for="dev_fees" class="block text-xs font-bold text-gray-700 mb-1.5">رسوم التطوير (شيكل)</label>
                                <input type="number" step="0.01" id="dev_fees" name="dev_fees" min="0"
                                       value="{{ old('dev_fees') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- الرسوم المتبقية --}}
                            <div>
                                <label for="fees_remains" class="block text-xs font-bold text-gray-700 mb-1.5">الرسوم المتبقية (شيكل)</label>
                                <input type="number" step="0.01" id="fees_remains" name="fees_remains" min="0"
                                       value="{{ old('fees_remains') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- قيمة الخصم --}}
                            <div>
                                <label for="dev_disc_val" class="block text-xs font-bold text-gray-700 mb-1.5">قيمة الخصم (شيكل)</label>
                                <input type="number" step="0.01" id="dev_disc_val" name="dev_disc_val" min="0"
                                       value="{{ old('dev_disc_val') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- نسبة الخصم --}}
                            <div>
                                <label for="dev_disc_rat" class="block text-xs font-bold text-gray-700 mb-1.5">نسبة الخصم (%)</label>
                                <input type="number" step="0.01" id="dev_disc_rat" name="dev_disc_rat" min="0" max="100"
                                       value="{{ old('dev_disc_rat') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- رسوم المتر --}}
                            <div>
                                <label for="dev_per_meter" class="block text-xs font-bold text-gray-700 mb-1.5">رسوم المتر الواحد</label>
                                <input type="number" step="0.01" id="dev_per_meter" name="dev_per_meter" min="0"
                                       value="{{ old('dev_per_meter') }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                        </div>

                        {{-- ملاحظات مالية --}}
                        <div>
                            <label for="financial_notes" class="block text-xs font-bold text-gray-700 mb-1.5">ملاحظات مالية</label>
                            <textarea id="financial_notes" name="financial_notes" rows="3"
                                      placeholder="ملاحظات حول سداد الرسوم أو الاتفاقات المالية..."
                                      class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('financial_notes') }}</textarea>
                        </div>
                    </div>

                    {{-- Tab 5: Initial Owner & Taxpayer (Optional during creation) --}}
                    <div x-show="tab === 'owner'" class="space-y-6" style="display: none;">
                        <div class="p-4 bg-orange/5 border border-orange/20 rounded-2xl flex items-center gap-3 text-xs text-orange-900 font-medium">
                            <x-heroicon-s-information-circle class="w-5 h-5 text-orange shrink-0" />
                            <span>يمكنك تحديد بيانات المالك الرئيسي أو المكلف الآن لربطه مباشرة بالمبنى، أو تركه فارغاً وإضافته لاحقاً من صفحة تفاصيل المبنى.</span>
                        </div>

                        <div class="bg-gray-50/70 rounded-2xl p-5 border border-gray-200/70 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- رقم الهوية --}}
                                <div>
                                    <label for="owner_id_card" class="block text-xs font-bold text-gray-700 mb-1">رقم الهوية الوطنية</label>
                                    <input type="text" id="owner_id_card" name="owner_id_card"
                                           value="{{ old('owner_id_card') }}"
                                           placeholder="مثال: 400123456"
                                           class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>

                                {{-- الاسم الأول --}}
                                <div>
                                    <label for="owner_first_name" class="block text-xs font-bold text-gray-700 mb-1">الاسم الأول</label>
                                    <input type="text" id="owner_first_name" name="owner_first_name"
                                           value="{{ old('owner_first_name') }}"
                                           placeholder="الاسم الأول"
                                           class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>

                                {{-- اسم الأب --}}
                                <div>
                                    <label for="owner_second_name" class="block text-xs font-bold text-gray-700 mb-1">اسم الأب</label>
                                    <input type="text" id="owner_second_name" name="owner_second_name"
                                           value="{{ old('owner_second_name') }}"
                                           placeholder="اسم الأب"
                                           class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>

                                {{-- اسم الجد --}}
                                <div>
                                    <label for="owner_third_name" class="block text-xs font-bold text-gray-700 mb-1">اسم الجد</label>
                                    <input type="text" id="owner_third_name" name="owner_third_name"
                                           value="{{ old('owner_third_name') }}"
                                           placeholder="اسم الجد"
                                           class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>

                                {{-- اسم العائلة --}}
                                <div>
                                    <label for="owner_sur_name" class="block text-xs font-bold text-gray-700 mb-1">اسم العائلة</label>
                                    <input type="text" id="owner_sur_name" name="owner_sur_name"
                                           value="{{ old('owner_sur_name') }}"
                                           placeholder="اسم العائلة"
                                           class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>

                                {{-- رقم الهاتف / الجوال --}}
                                <div>
                                    <label for="owner_phone" class="block text-xs font-bold text-gray-700 mb-1">رقم الهاتف / الجوال</label>
                                    <input type="text" id="owner_phone" name="owner_phone"
                                           value="{{ old('owner_phone') }}"
                                           placeholder="مثال: 0599123456"
                                           class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                </div>
                            </div>

                            <div class="pt-2 flex items-center justify-between">
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="owner_mokalaf" value="1" {{ old('owner_mokalaf', 1) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">تعيين هذا المالك كمكلف رئيسي بالرسوم والضرائب (المكلف)</span>
                                </label>
                            </div>

                            <div>
                                <label for="owner_notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات حول المالك</label>
                                <input type="text" id="owner_notes" name="owner_notes"
                                       value="{{ old('owner_notes') }}"
                                       placeholder="أي ملاحظات إضافية..."
                                       class="w-full text-xs bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800">
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Sticky Action Bar at Bottom --}}
            <div class="sticky bottom-4 z-40 mt-8 bg-white/95 backdrop-blur-md border border-gray-200/90 rounded-2xl p-4 shadow-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-bold text-gray-800">إضافة مبنى جديد إلى سجلات البلدية</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('buildings.buildings.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                        إلغاء والعودة
                    </a>
                    <button type="submit"
                            :disabled="isSubmitting"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer text-xs">
                        <template x-if="!isSubmitting">
                            <span class="flex items-center gap-1.5">
                                <x-heroicon-s-check class="w-4 h-4" />
                                <span>حفظ وتثبيت المبنى</span>
                            </span>
                        </template>
                        <template x-if="isSubmitting">
                            <span class="flex items-center gap-1.5">
                                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>جاري حفظ المبنى...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </div>

        </form>
        {{-- Form End --}}

        {{-- نافذة البحث والاختيار من ملفات الأرشيف --}}
        <template x-teleport="body">
            <div x-show="showArchiveModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs overflow-y-auto"
                 style="display: none;"
                 @keydown.escape.window="closeArchiveModal()">

                {{-- خلفية معتمة قابلة للنقر للإغلاق --}}
                <div class="fixed inset-0" @click="closeArchiveModal()" aria-hidden="true"></div>

                <div @click.stop
                     x-show="showArchiveModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-3xl overflow-hidden relative transition-all my-auto z-10">

                    {{-- رأس النافذة --}}
                    <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0 shadow-inner">
                                <x-heroicon-s-folder-plus class="w-5 h-5" />
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-gray-800">البحث في الأرشيف واختيار ملف التنظيم</h3>
                                <p class="text-xs text-gray-400">ابحث برقم الملف أو اسم المالك لربطه بالمبنى الجديد</p>
                            </div>
                        </div>
                        <button @click="closeArchiveModal()" type="button" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- شريط البحث --}}
                    <div class="p-5 border-b border-gray-100 bg-white">
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                            </div>
                            <input type="text"
                                   x-ref="archiveSearchInput"
                                   x-model="archiveSearchQuery"
                                   @input.debounce.350ms="performArchiveSearch()"
                                   placeholder="أدخل رقم الملف أو اسم صاحب الملف (حرفين على الأقل)..."
                                   class="w-full pr-10 pl-10 py-2.5 text-xs font-bold text-gray-800 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <template x-if="isSearchingArchive">
                                    <svg class="animate-spin w-4 h-4 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- نتائج البحث --}}
                    <div class="p-5 max-h-[380px] overflow-y-auto space-y-2">
                        <template x-if="isSearchingArchive">
                            <div class="py-10 text-center text-xs text-gray-400">
                                <svg class="animate-spin w-6 h-6 text-orange mx-auto mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>جاري البحث في الأرشيف...</span>
                            </div>
                        </template>

                        <template x-if="!isSearchingArchive && archiveSearchResults.length === 0 && archiveSearchQuery.trim().length >= 2">
                            <div class="py-10 text-center text-xs text-gray-400">
                                <x-heroicon-o-information-circle class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                                <span>لم يتم العثور على أي ملف أرشيف يطابق بحثك.</span>
                            </div>
                        </template>

                        <template x-if="!isSearchingArchive && archiveSearchQuery.trim().length < 2">
                            <div class="py-10 text-center text-xs text-gray-400">
                                <x-heroicon-o-magnifying-glass class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                                <span>اكتب رقم الملف أو اسم صاحب الملف لبدء البحث الفوري</span>
                            </div>
                        </template>

                        <template x-for="file in archiveSearchResults" :key="file.id">
                            <div @click="selectArchiveFile(file)"
                                 class="p-3 rounded-2xl border transition-all cursor-pointer flex items-center justify-between gap-3"
                                 :class="selectedArchiveFile && selectedArchiveFile.id === file.id ? 'border-orange bg-orange/5 ring-2 ring-orange/20' : 'border-gray-100 hover:border-gray-200 bg-white hover:bg-gray-50/50'">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                                         :class="selectedArchiveFile && selectedArchiveFile.id === file.id ? 'bg-orange text-white' : 'bg-gray-100 text-gray-600'">
                                        <x-heroicon-s-folder class="w-4 h-4" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-gray-900 truncate" x-text="file.file_name"></div>
                                        <div class="flex items-center gap-2 text-[11px] text-gray-500 mt-0.5">
                                            <span class="font-mono font-bold text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded" dir="ltr" x-text="file.file_no"></span>
                                            <span>•</span>
                                            <span class="text-blue-600 font-medium" x-text="file.file_type"></span>
                                            <span>•</span>
                                            <span x-text="file.documents_count + ' وثيقة'"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <template x-if="selectedArchiveFile && selectedArchiveFile.id === file.id">
                                        <span class="w-6 h-6 rounded-full bg-orange text-white flex items-center justify-center shadow-xs">
                                            <x-heroicon-s-check class="w-3.5 h-3.5" />
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- تذييل النافذة --}}
                    <div class="flex items-center justify-between p-4 border-t border-gray-100 bg-gray-50/60">
                        <div class="text-xs text-gray-500">
                            <template x-if="selectedArchiveFile">
                                <span>الملف المحدد: <strong class="text-orange-dark font-mono" x-text="selectedArchiveFile.file_no"></strong></span>
                            </template>
                            <template x-if="!selectedArchiveFile">
                                <span>اختر ملفاً من القائمة أعلاه</span>
                            </template>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="closeArchiveModal()" type="button" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                                إلغاء
                            </button>
                            <button @click="confirmArchiveSelection()"
                                    :disabled="!selectedArchiveFile"
                                    type="button"
                                    class="px-5 py-2 text-xs font-bold bg-orange hover:bg-orange-dark text-white rounded-xl shadow-xs transition-all disabled:opacity-50 cursor-pointer">
                                اختيار وتأكيد الربط
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </template>

    </div>
</x-buildings-layout>