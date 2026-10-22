<x-buildings-layout>
    <script>
        function buildingEditor(buildingId, initialFileNo, initialArchiveFile) {
            const cleanInitialFileNo = (initialFileNo && String(initialFileNo).trim() !== '0') ? String(initialFileNo).trim() : '';

            return {
                isSubmitting: false,
                selectedZone: '{{ old('zone_id', $building->zone_id) }}',

                // Archive File Linking State
                buildingId: buildingId,
                archiveFileNo: cleanInitialFileNo,
                currentArchiveFile: cleanInitialFileNo ? initialArchiveFile : null,
                showArchiveModal: false,
                archiveSearchQuery: '',
                archiveSearchResults: [],
                selectedArchiveFile: null,
                isSearchingArchive: false,
                isLinkingArchive: false,
                isUnlinkingArchive: false,
                archiveErrorMessage: '',
                archiveSuccessMessage: '',

                openArchiveModal() {
                    this.showArchiveModal = true;
                    this.archiveSearchQuery = '';
                    this.archiveSearchResults = [];
                    this.selectedArchiveFile = null;
                    this.archiveErrorMessage = '';
                    this.archiveSuccessMessage = '';
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
                    this.archiveSuccessMessage = '';
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

                async linkSelectedArchiveFile() {
                    if (!this.selectedArchiveFile) return;
                    this.isLinkingArchive = true;
                    this.archiveErrorMessage = '';
                    try {
                        const res = await fetch(`{{ url('/buildings/buildings') }}/${this.buildingId}/link-archive-file`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                file_no: this.selectedArchiveFile.file_no
                            })
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            this.archiveSuccessMessage = json.message || 'تم ربط المبنى بملف الأرشيف بنجاح!';
                            this.archiveFileNo = this.selectedArchiveFile.file_no;
                            this.currentArchiveFile = {
                                id: this.selectedArchiveFile.id,
                                file_no: this.selectedArchiveFile.file_no,
                                file_name: this.selectedArchiveFile.file_name,
                                file_type: this.selectedArchiveFile.file_type,
                                documents_count: this.selectedArchiveFile.documents_count
                            };
                            setTimeout(() => {
                                this.closeArchiveModal();
                            }, 600);
                        } else {
                            this.archiveErrorMessage = json.message || 'حدث خطأ أثناء الربط، يرجى المحاولة مرة أخرى.';
                        }
                    } catch (err) {
                        this.archiveErrorMessage = 'فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.';
                    } finally {
                        this.isLinkingArchive = false;
                    }
                },

                async unlinkArchiveFile() {
                    if (!confirm('هل أنت متأكد من رغبتك في إزالة ربط هذا المبنى بملف الأرشيف؟')) {
                        return;
                    }
                    this.isUnlinkingArchive = true;
                    this.archiveErrorMessage = '';
                    try {
                        const res = await fetch(`{{ url('/buildings/buildings') }}/${this.buildingId}/unlink-archive-file`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            this.archiveFileNo = '';
                            this.currentArchiveFile = null;
                            if (this.showArchiveModal) {
                                this.closeArchiveModal();
                            }
                        } else {
                            alert(json.message || 'حدث خطأ أثناء إزالة ربط ملف الأرشيف.');
                        }
                    } catch (err) {
                        alert('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.');
                    } finally {
                        this.isUnlinkingArchive = false;
                    }
                }
            };
        }
        window.buildingEditor = buildingEditor;
        if (window.Alpine) {
            window.Alpine.data('buildingEditor', buildingEditor);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('buildingEditor', buildingEditor);
            });
        }
    </script>

    <div x-data="buildingEditor({{ $building->id }}, {{ json_encode(old('file_number', $building->file_number ?? '')) }}, {{ json_encode($archiveFileData ?? null) }})" class="space-y-6 max-w-7xl mx-auto pb-16">

        {{-- Form Start --}}
        <form action="{{ route('buildings.buildings.update', $building->id) }}"
              method="POST"
              enctype="multipart/form-data"
              id="building-edit-form"
              @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('buildings.buildings.show', $building->id) }}"
                       class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0"
                       title="الرجوع لصفحة تفاصيل المبنى">
                        <x-heroicon-o-arrow-right class="w-5 h-5" />
                    </a>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="bg-orange/10 text-orange-dark text-xs px-3 py-1 rounded-xl font-bold border border-orange/20 flex items-center gap-1">
                                <x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-orange" />
                                وضع التعديل
                            </span>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">
                                {{ $building->display_name }}
                            </h1>
                            @if(filled($building->building_number))
                            <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1.5 rounded-xl font-bold border border-blue-100/80 shadow-xs flex items-center gap-1">
                                <x-heroicon-s-hashtag class="w-3.5 h-3.5 text-blue-500" />
                                مبنى رقم {{ $building->building_number }}
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-1">قم بتعديل بيانات المبنى والموقع، ورفع الصورة والمواصفات وحفظ التغييرات</p>
                    </div>
                </div>
            </div>

            {{-- Error Alerts --}}
            @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-xs mb-6 shadow-xs">
                <div class="font-bold flex items-center gap-2 mb-2 text-sm">
                    <x-heroicon-s-x-circle class="w-5 h-5 text-red-500" />
                    يرجى تصحيح الأخطاء التالية قبل الحفظ:
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
                                        <p class="text-xs text-gray-400">تعديل معلومات المبنى والموقع الجغرافي والتنظيمي</p>
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
                                                <option value="{{ $zone->id }}" {{ old('zone_id', $building->zone_id) == $zone->id ? 'selected' : '' }}>
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
                                                <option value="{{ $subzone->id }}" {{ old('subzone_id', $building->subzone_id) == $subzone->id ? 'selected' : '' }}>
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
                                                <option value="{{ $street->id }}" {{ old('street_id', $building->street_id) == $street->id ? 'selected' : '' }}>
                                                    {{ $street->street_name ? $street->street_name . ' (' . $street->street_number . ')' : 'شارع ' . $street->street_number }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- رقم القطعة ورقم القسيمة --}}
                                    <div class="grid grid-cols-2 gap-2.5 pt-1">
                                        <div>
                                            <label for="block_number" class="block text-xs font-bold text-gray-700 mb-1">رقم القطعة</label>
                                            <input type="text" id="block_number" name="block_number"
                                                   value="{{ old('block_number', $building->block_number) }}"
                                                   placeholder="مثال: 12"
                                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                        </div>
                                        <div>
                                            <label for="parcel_number" class="block text-xs font-bold text-gray-700 mb-1">رقم القسيمة</label>
                                            <input type="text" id="parcel_number" name="parcel_number"
                                                   value="{{ old('parcel_number', $building->parcel_number) }}"
                                                   placeholder="مثال: 45"
                                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                        </div>
                                    </div>
                                </div>

                                {{-- مجموعة البيانات التنظيمية --}}
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
                                               value="{{ old('building_number', $building->building_number) }}"
                                               placeholder="مثال: 9A"
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
                                                        مرتبط
                                                    </span>
                                                </template>
                                                <template x-if="!archiveFileNo">
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                                                        غير مرتبط
                                                    </span>
                                                </template>
                                            </div>
                                        </div>

                                        {{-- الحقل المخفي لترحيل القيمة مع النموذج --}}
                                        <input type="hidden" id="file_number" name="file_number" :value="archiveFileNo || ''">

                                        {{-- بطاقة الملف المرتبط حالياً --}}
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
                                                        <span>تعديل الربط</span>
                                                    </button>

                                                    <button type="button"
                                                            @click="unlinkArchiveFile()"
                                                            :disabled="isUnlinkingArchive"
                                                            class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-all cursor-pointer disabled:opacity-50"
                                                            title="إزالة ربط ملف الأرشيف بالمبنى">
                                                        <template x-if="!isUnlinkingArchive">
                                                            <span class="flex items-center gap-1">
                                                                <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                                                <span>إزالة الربط</span>
                                                            </span>
                                                        </template>
                                                        <template x-if="isUnlinkingArchive">
                                                            <span class="flex items-center gap-1">
                                                                <svg class="animate-spin w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                                <span>جاري الإزالة...</span>
                                                            </span>
                                                        </template>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- بطاقة الحالة غير المرتبطة --}}
                                        <template x-if="!archiveFileNo">
                                            <div class="p-4 rounded-xl bg-orange/5 border border-dashed border-orange/30 text-center space-y-2">
                                                <div class="w-8 h-8 rounded-lg bg-orange/10 text-orange flex items-center justify-center mx-auto shadow-inner">
                                                    <x-heroicon-s-folder-plus class="w-4 h-4" />
                                                </div>
                                                <div>
                                                    <div class="text-xs font-bold text-gray-800">المبنى غير مرتبط بملف أرشيف</div>
                                                    <div class="text-[11px] text-gray-400 mt-0.5">يمكنك ربط المبنى بملف أرشيف من سجلات التنظيم</div>
                                                </div>
                                                <button type="button"
                                                        @click="openArchiveModal()"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange hover:bg-orange-dark text-white rounded-lg text-xs font-bold shadow-xs hover:shadow-sm transition-all cursor-pointer">
                                                    <x-heroicon-s-magnifying-glass class="w-3.5 h-3.5" />
                                                    <span>البحث في الأرشيف والربط</span>
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
                                               value="{{ old('building_name', $building->building_name) }}"
                                               placeholder="مثال: برج السلام، عمارة القدس"
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
                                                    <option value="{{ $status->id }}" {{ old('building_status_id', $building->building_status_id) == $status->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $propType->id }}" {{ old('building_property_type_id', $building->building_property_type_id) == $propType->id ? 'selected' : '' }}>
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
                <div class="lg:col-span-1 flex flex-col min-w-0" x-data="buildingPhotoUploader('{{ $building->attachments->first()?->path ? asset('storage/' . $building->attachments->first()->path) : '' }}')">
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
                                <template x-if="previewUrl && !removePhoto">
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
                            <input type="hidden" name="remove_photo" :value="removePhoto ? 1 : 0">

                            {{-- Drag & Drop Box --}}
                            <div class="relative w-full aspect-4/3 min-h-[220px] rounded-2xl border-2 border-dashed transition-all overflow-hidden flex flex-col items-center justify-center cursor-pointer group"
                                 :class="isDragging ? 'border-orange bg-orange/5 ring-4 ring-orange/10 scale-[1.01]' : (previewUrl && !removePhoto ? 'border-gray-200 bg-gray-900' : 'border-gray-200 hover:border-orange/60 bg-gray-50/60 hover:bg-orange/5')"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)"
                                 @click="$refs.fileInput.click()">

                                {{-- Image Preview State --}}
                                <template x-if="previewUrl && !removePhoto">
                                    <div class="relative w-full h-full">
                                        <img :src="previewUrl"
                                             alt="معاينة صورة المبنى"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-gray-950/80 via-black/20 to-transparent flex flex-col justify-end p-4">
                                            <div class="flex items-center justify-between text-white">
                                                <div>
                                                    <span class="inline-block text-[10px] px-2 py-0.5 rounded-full font-bold bg-white/20 backdrop-blur-md mb-1"
                                                          x-text="isNewFile ? 'صورة جديدة محددة' : 'الصورة الحالية'"></span>
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
                                <template x-if="!previewUrl || removePhoto">
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
                                <template x-if="isNewFile">
                                    <span class="text-emerald-600 font-bold flex items-center gap-1">
                                        <x-heroicon-s-check-circle class="w-3.5 h-3.5" />
                                        جاهز للحفظ والرفع
                                    </span>
                                </template>
                                <template x-if="removePhoto">
                                    <span class="text-red-600 font-bold flex items-center gap-1">
                                        <x-heroicon-s-exclamation-triangle class="w-3.5 h-3.5" />
                                        سيتم حذف الصورة عند الحفظ
                                    </span>
                                </template>
                                <template x-if="!isNewFile && !removePhoto">
                                    <span class="text-gray-400">يمكنك استبدال الصورة بسحب ملف جديد</span>
                                </template>
                                <template x-if="removePhoto">
                                    <button type="button" @click="restoreOriginal()" class="text-blue-600 hover:underline font-bold mr-auto">
                                        تراجع عن الحذف
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 mt-4">
                            <button type="button" @click="$refs.fileInput.click()"
                                    class="w-full py-2 px-3 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold rounded-xl text-xs transition-colors border border-gray-200 flex items-center justify-center gap-1.5">
                                <x-heroicon-o-photo class="w-4 h-4 text-gray-500" />
                                <span x-text="previewUrl && !removePhoto ? 'اختيار صورة بديلة' : 'تحديد صورة من الملفات'"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Main Tabs Section --}}
            <div x-data="{ tab: 'basic' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Tabs Header --}}
                <div id="building-edit-tabs-header" class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/80 px-2 pt-2">
                    <style>#building-edit-tabs-header::-webkit-scrollbar{display:none}#building-edit-tabs-header{-ms-overflow-style:none;scrollbar-width:none;}</style>
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
                    <button type="button" @click="tab = 'structure'"
                            :class="{'text-orange-dark border-orange bg-white shadow-sm font-extrabold': tab === 'structure', 'text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 border-transparent font-bold': tab !== 'structure'}"
                            class="px-5 py-3.5 text-xs transition-all whitespace-nowrap flex items-center gap-2 border-b-2 rounded-t-xl mb-[-2px]">
                        <x-heroicon-s-squares-plus class="w-4 h-4" x-bind:class="{'text-orange': tab === 'structure'}" />
                        الملاك والهيكلية (معلومات)
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
                                        <option value="{{ $bType->id }}" {{ old('building_type_id', $building->building_type_id) == $bType->id ? 'selected' : '' }}>
                                            {{ $bType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- تاريخ الإنشاء --}}
                            <div>
                                <label for="building_date" class="block text-xs font-bold text-gray-700 mb-1.5">تاريخ الإنشاء</label>
                                <input type="date" id="building_date" name="building_date"
                                       value="{{ old('building_date', $building->building_date ? \Carbon\Carbon::parse($building->building_date)->format('Y-m-d') : '') }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- المشرف المسؤول --}}
                            <div>
                                <label for="supervisor_id" class="block text-xs font-bold text-gray-700 mb-1.5">المشرف المسؤول</label>
                                <select id="supervisor_id" name="supervisor_id"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor_id', $building->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
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
                                    <option value="1" {{ old('out_status', $building->out_status) == 1 ? 'selected' : '' }}>مشطب كامل</option>
                                    <option value="2" {{ old('out_status', $building->out_status) == 2 ? 'selected' : '' }}>مشطب جزئي</option>
                                    <option value="3" {{ old('out_status', $building->out_status) == 3 ? 'selected' : '' }}>غير مشطب</option>
                                </select>
                            </div>

                            {{-- الحالة العامة للمبنى --}}
                            <div>
                                <label for="overall_status" class="block text-xs font-bold text-gray-700 mb-1.5">الحالة العامة للمبنى</label>
                                <select id="overall_status" name="overall_status"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('overall_status', $building->overall_status) == 1 ? 'selected' : '' }}>ممتازة</option>
                                    <option value="2" {{ old('overall_status', $building->overall_status) == 2 ? 'selected' : '' }}>جيدة</option>
                                    <option value="3" {{ old('overall_status', $building->overall_status) == 3 ? 'selected' : '' }}>سيئة</option>
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
                                    <input type="checkbox" name="historical" value="1" {{ old('historical', $building->historical) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">مبنى تاريخي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="adding_new_floor" value="1" {{ old('adding_new_floor', $building->adding_new_floor) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">إضافة طابق جديد</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="abandond" value="1" {{ old('abandond', $building->abandond) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">مبنى مهجور</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="building_special_case" value="1" {{ old('building_special_case', $building->building_special_case) ? 'checked' : '' }}
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
                                    $currentUseIds = old('uses', $building->uses->pluck('id')->toArray());
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
                                          class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('ownership_notes', $building->ownership_notes) }}</textarea>
                            </div>
                            <div>
                                <label for="overall_status_notes" class="block text-xs font-bold text-gray-700 mb-1.5">ملاحظات الحالة العامة</label>
                                <textarea id="overall_status_notes" name="overall_status_notes" rows="3"
                                          placeholder="ملاحظات حول السلامة الإنشائية أو الصيانة..."
                                          class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('overall_status_notes', $building->overall_status_notes) }}</textarea>
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
                                    $currentMatIds = old('materials', $building->materials->pluck('id')->toArray());
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
                                    <input type="checkbox" name="finish_qesara" value="1" {{ old('finish_qesara', $building->finish_qesara) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">قصارة</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_italian" value="1" {{ old('finish_italian', $building->finish_italian) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">إيطالي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_tiles" value="1" {{ old('finish_tiles', $building->finish_tiles) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">سيراميك / بلاط</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_j_stone" value="1" {{ old('finish_j_stone', $building->finish_j_stone) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">حجر قدسي</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_granuleet" value="1" {{ old('finish_granuleet', $building->finish_granuleet) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">جرانيليت</span>
                                </label>
                                <label class="flex items-center gap-2 p-3 bg-white rounded-xl border border-gray-200 hover:border-orange/50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="finish_other" value="1" {{ old('finish_other', $building->finish_other) ? 'checked' : '' }}
                                           class="rounded text-orange focus:ring-orange/30 w-4 h-4">
                                    <span class="text-xs font-bold text-gray-800">أخرى</span>
                                </label>
                            </div>
                            <div>
                                <label for="finish_notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات التشطيب الخارجي</label>
                                <input type="text" id="finish_notes" name="finish_notes"
                                       value="{{ old('finish_notes', $building->finish_notes) }}"
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
                                        <input type="checkbox" name="wall_stone" value="1" {{ old('wall_stone', $building->wall_stone) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_reinforced_concrete" value="1" {{ old('wall_reinforced_concrete', $building->wall_reinforced_concrete) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>خرسانة مسلحة</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_concrete_stone" value="1" {{ old('wall_concrete_stone', $building->wall_concrete_stone) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر باطون</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="wall_sand_stone" value="1" {{ old('wall_sand_stone', $building->wall_sand_stone) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>حجر رملي</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="text" name="wall_notes" value="{{ old('wall_notes', $building->wall_notes) }}"
                                           placeholder="ملاحظات الجدران..."
                                           class="w-full text-xs bg-white border border-gray-200 rounded-xl px-3 py-1.5 text-gray-800">
                                </div>
                            </div>

                            {{-- سقف الطابق الأخير --}}
                            <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60 space-y-3">
                                <h4 class="text-xs font-bold text-gray-700 pb-2 border-b border-gray-200">سقف الطابق الأخير</h4>
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_concrete" value="1" {{ old('last_floor_concrete', $building->last_floor_concrete) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>خرسانة</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_asbast" value="1" {{ old('last_floor_asbast', $building->last_floor_asbast) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>أسبست</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_carmeed" value="1" {{ old('last_floor_carmeed', $building->last_floor_carmeed) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>قرميد</span>
                                    </label>
                                    <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-gray-200 cursor-pointer">
                                        <input type="checkbox" name="last_floor_zenko" value="1" {{ old('last_floor_zenko', $building->last_floor_zenko) ? 'checked' : '' }} class="rounded text-orange w-3.5 h-3.5">
                                        <span>زينكو / صفيح</span>
                                    </label>
                                </div>
                                <div>
                                    <input type="text" name="last_floor_notes" value="{{ old('last_floor_notes', $building->last_floor_notes) }}"
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
                                    <option value="1" {{ old('water_source', $building->water_source) == 1 ? 'selected' : '' }}>شبكة البلدية</option>
                                    <option value="2" {{ old('water_source', $building->water_source) == 2 ? 'selected' : '' }}>بئر خاص</option>
                                    <option value="3" {{ old('water_source', $building->water_source) == 3 ? 'selected' : '' }}>أخرى</option>
                                </select>
                            </div>

                            {{-- شبكة الصرف الصحي --}}
                            <div>
                                <label for="sewage" class="block text-xs font-bold text-gray-700 mb-1.5">الصرف الصحي</label>
                                <select id="sewage" name="sewage"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير محدد —</option>
                                    <option value="1" {{ old('sewage', $building->sewage) == 1 ? 'selected' : '' }}>بلدية</option>
                                    <option value="2" {{ old('sewage', $building->sewage) == 2 ? 'selected' : '' }}>بئر خاص / حفرة امتصاصية</option>
                                    <option value="3" {{ old('sewage', $building->sewage) == 3 ? 'selected' : '' }}>لا يوجد</option>
                                </select>
                            </div>

                            {{-- عدد المصاعد --}}
                            <div>
                                <label for="elevators_count" class="block text-xs font-bold text-gray-700 mb-1.5">عدد المصاعد</label>
                                <input type="number" id="elevators_count" name="elevators_count" min="0"
                                       value="{{ old('elevators_count', $building->elevators_count ?? 0) }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- أدراج الهروب --}}
                            <div>
                                <label for="escape_stairs" class="block text-xs font-bold text-gray-700 mb-1.5">عدد أدراج الهروب</label>
                                <input type="number" id="escape_stairs" name="escape_stairs" min="0"
                                       value="{{ old('escape_stairs', $building->escape_stairs ?? 0) }}"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- مصدر الكهرباء --}}
                            <div>
                                <label for="electricity_source" class="block text-xs font-bold text-gray-700 mb-1.5">مصدر الكهرباء (الاشتراك)</label>
                                <input type="number" id="electricity_source" name="electricity_source"
                                       value="{{ old('electricity_source', $building->electricity_source) }}"
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
                                       value="{{ old('land_area', $building->financial?->land_area) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- رسوم التطوير --}}
                            <div>
                                <label for="dev_fees" class="block text-xs font-bold text-gray-700 mb-1.5">رسوم التطوير (شيكل)</label>
                                <input type="number" step="0.01" id="dev_fees" name="dev_fees" min="0"
                                       value="{{ old('dev_fees', $building->financial?->dev_fees) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- الرسوم المتبقية --}}
                            <div>
                                <label for="fees_remains" class="block text-xs font-bold text-gray-700 mb-1.5">الرسوم المتبقية (شيكل)</label>
                                <input type="number" step="0.01" id="fees_remains" name="fees_remains" min="0"
                                       value="{{ old('fees_remains', $building->financial?->fees_remains ?? $building->financial?->dev_remains) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- قيمة الخصم --}}
                            <div>
                                <label for="dev_disc_val" class="block text-xs font-bold text-gray-700 mb-1.5">قيمة الخصم (شيكل)</label>
                                <input type="number" step="0.01" id="dev_disc_val" name="dev_disc_val" min="0"
                                       value="{{ old('dev_disc_val', $building->financial?->dev_disc_val) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- نسبة الخصم --}}
                            <div>
                                <label for="dev_disc_rat" class="block text-xs font-bold text-gray-700 mb-1.5">نسبة الخصم (%)</label>
                                <input type="number" step="0.01" id="dev_disc_rat" name="dev_disc_rat" min="0" max="100"
                                       value="{{ old('dev_disc_rat', $building->financial?->dev_disc_rat) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- رسوم المتر --}}
                            <div>
                                <label for="dev_per_meter" class="block text-xs font-bold text-gray-700 mb-1.5">رسوم المتر الواحد</label>
                                <input type="number" step="0.01" id="dev_per_meter" name="dev_per_meter" min="0"
                                       value="{{ old('dev_per_meter', $building->financial?->dev_per_meter) }}"
                                       placeholder="0.00"
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                        </div>

                        {{-- ملاحظات مالية --}}
                        <div>
                            <label for="financial_notes" class="block text-xs font-bold text-gray-700 mb-1.5">ملاحظات مالية</label>
                            <textarea id="financial_notes" name="financial_notes" rows="3"
                                      placeholder="ملاحظات حول سداد الرسوم أو الاتفاقات المالية..."
                                      class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-2xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">{{ old('financial_notes', $building->financial?->notes) }}</textarea>
                        </div>
                    </div>

                    {{-- Tab 5: Owners & Structure Info --}}
                    <div x-show="tab === 'structure'" class="space-y-6" style="display: none;">
                        <div class="p-4 bg-blue-50/70 border border-blue-100 rounded-2xl flex items-center gap-3 text-xs text-blue-900 font-medium">
                            <x-heroicon-s-information-circle class="w-5 h-5 text-blue-600 shrink-0" />
                            <span>الملاك، الطوابق، والوحدات مسجلة ككيانات فرعية مستقلة مرتبطة بهذا المبنى. يُعرض هنا ملخص للمعلومات الحالية.</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- الملاك --}}
                            <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                                <h4 class="font-extrabold text-gray-800 text-xs mb-3 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-s-users class="w-4 h-4 text-orange" />
                                        الملاك المسجلون
                                    </span>
                                    <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-[10px]">{{ $building->owners->count() }}</span>
                                </h4>
                                @if($building->owners->count() > 0)
                                <div class="space-y-2">
                                    @foreach($building->owners as $owner)
                                    <div class="p-2.5 bg-white rounded-xl border border-gray-100 flex items-center justify-between text-xs">
                                        <div class="font-bold text-gray-800">{{ $owner->full_name }}</div>
                                        <div class="text-[11px] font-mono text-gray-500">{{ $owner->id_card }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-xs text-gray-400 py-3 text-center">لا يوجد ملاك مسجلون حالياً</div>
                                @endif
                            </div>

                            {{-- الطوابق والوحدات --}}
                            <div class="bg-gray-50/60 rounded-2xl p-5 border border-gray-200/60">
                                <h4 class="font-extrabold text-gray-800 text-xs mb-3 flex items-center justify-between">
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-s-home class="w-4 h-4 text-emerald-600" />
                                        الهيكلية الإنشائية
                                    </span>
                                    <span class="text-[10px] text-gray-500">
                                        {{ $building->floors->count() }} طابق • {{ $building->units->count() }} وحدة
                                    </span>
                                </h4>
                                <div class="space-y-2">
                                    @forelse($building->floors as $floor)
                                    <div class="p-2.5 bg-white rounded-xl border border-gray-100 flex items-center justify-between text-xs">
                                        <div class="font-bold text-gray-800">{{ $floor->floor_name }}</div>
                                        <div class="text-gray-500 text-[11px]">المساحة: {{ $floor->area ?? 0 }} م²</div>
                                    </div>
                                    @empty
                                    <div class="text-xs text-gray-400 py-3 text-center">لا توجد طوابق مسجلة حالياً</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- Sticky Action Bar at Bottom --}}
            <div class="sticky bottom-4 z-40 mt-8 bg-white/90 backdrop-blur-md border border-gray-200/80 rounded-2xl p-4 shadow-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange animate-pulse"></span>
                    <span class="font-bold text-gray-800">أنت في وضع تعديل المبنى:</span>
                    <span class="font-bold text-orange-dark truncate max-w-[200px]">{{ $building->display_name }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('buildings.buildings.show', $building->id) }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                        إلغاء والعودة
                    </a>
                    <button type="submit"
                            :disabled="isSubmitting"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer text-xs">
                        <x-heroicon-s-check class="w-4 h-4" />
                        <span>حفظ التعديلات</span>
                    </button>
                </div>
            </div>

        </form>
        {{-- Form End --}}

        {{-- نافذة البحث والربط بملفات الأرشيف --}}
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
                                <h3 class="text-base font-extrabold text-gray-800">البحث في الأرشيف وربط المبنى</h3>
                                <p class="text-xs text-gray-400">ابحث برقم الملف أو اسم المالك لربطه بالمبنى ({{ $building->display_name }})</p>
                            </div>
                        </div>
                        <button @click="closeArchiveModal()" type="button" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                            <x-heroicon-o-x-mark class="w-5 h-5" />
                        </button>
                    </div>

                    {{-- محتوى النافذة --}}
                    <div class="p-6">
                        {{-- رسائل التنبيه --}}
                        <div x-show="archiveErrorMessage" x-transition class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <x-heroicon-s-x-circle class="w-4 h-4 shrink-0 text-red-500" />
                            <span x-text="archiveErrorMessage"></span>
                        </div>

                        <div x-show="archiveSuccessMessage" x-transition class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                            <x-heroicon-s-check-circle class="w-4 h-4 shrink-0 text-emerald-500" />
                            <span x-text="archiveSuccessMessage"></span>
                        </div>

                        {{-- شريط الملف المرتبط حالياً إن وجد --}}
                        <template x-if="archiveFileNo">
                            <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs text-gray-700 min-w-0">
                                    <x-heroicon-s-folder class="w-5 h-5 text-gray-400 shrink-0" />
                                    <span class="text-gray-500">المرتبط حالياً:</span>
                                    <span class="font-bold text-gray-900 truncate" x-text="currentArchiveFile ? currentArchiveFile.file_name : ('ملف رقم ' + archiveFileNo)"></span>
                                    <span class="font-mono font-extrabold text-orange-dark text-[11px] bg-white px-2 py-0.5 rounded border border-gray-200 shrink-0" dir="ltr" x-text="archiveFileNo"></span>
                                </div>
                                <button type="button"
                                        @click="unlinkArchiveFile()"
                                        :disabled="isUnlinkingArchive"
                                        class="px-2.5 py-1 text-xs font-bold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-red-200 shrink-0 flex items-center gap-1 cursor-pointer">
                                    <x-heroicon-s-trash class="w-3 h-3" />
                                    <span>إزالة الربط الحالي</span>
                                </button>
                            </div>
                        </template>

                        {{-- حقل البحث --}}
                        <div class="mb-4">
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                    <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                                </div>
                                <input type="text"
                                       x-ref="archiveSearchInput"
                                       x-model="archiveSearchQuery"
                                       @input.debounce.300ms="performArchiveSearch()"
                                       placeholder="ابحث برقم الملف (مثال: 9-34) أو اسم الملف / المالك..."
                                       class="w-full pr-11 pl-10 py-3 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-orange focus:ring-2 focus:ring-orange/20 transition-all">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <template x-if="isSearchingArchive">
                                        <svg class="animate-spin w-4 h-4 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    </template>
                                    <template x-if="!isSearchingArchive && archiveSearchQuery">
                                        <button @click="archiveSearchQuery = ''; performArchiveSearch()" type="button" class="text-gray-400 hover:text-gray-600 p-1">
                                            <x-heroicon-s-x-mark class="w-4 h-4" />
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- قائمة نتائج البحث --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2 px-1">
                                <span class="text-xs font-bold text-gray-500" x-show="archiveSearchQuery.trim().length >= 2">نتائج البحث:</span>
                                <span class="text-xs font-bold text-gray-400" x-show="archiveSearchQuery.trim().length < 2">يرجى البحث لعرض النتائج</span>
                                <span class="text-[11px] text-gray-400" x-show="archiveSearchQuery.trim().length >= 2" x-text="archiveSearchResults.length + ' نتيجة'"></span>
                            </div>

                            <div class="max-h-56 overflow-y-auto divide-y divide-gray-100 rounded-2xl border border-gray-200 bg-white">
                                {{-- الحالة 1: قبل إدخال نص البحث --}}
                                <template x-if="archiveSearchQuery.trim().length < 2 && !isSearchingArchive">
                                    <div class="p-8 text-center text-gray-400 text-xs">
                                        <x-heroicon-o-magnifying-glass class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                        <div class="font-bold text-gray-700 text-sm mb-1">ابحث برقم الملف أو اسم المالك</div>
                                        <p class="text-gray-400">يرجى كتابة حرفين أو رقمين على الأقل في حقل البحث أعلاه لعرض الملفات المطابقة فقط</p>
                                    </div>
                                </template>

                                {{-- الحالة 2: البحث جاري --}}
                                <template x-if="isSearchingArchive">
                                    <div class="p-8 text-center text-gray-400 text-xs">
                                        <svg class="animate-spin w-7 h-7 mx-auto mb-2 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                        <div class="font-bold text-gray-600">جاري البحث في ملفات الأرشيف...</div>
                                    </div>
                                </template>

                                {{-- الحالة 3: تم البحث ولم يتم العثور على نتائج --}}
                                <template x-if="archiveSearchQuery.trim().length >= 2 && !isSearchingArchive && archiveSearchResults.length === 0">
                                    <div class="p-8 text-center text-gray-400 text-xs">
                                        <x-heroicon-o-folder-open class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                        <div class="font-bold text-gray-700 text-sm mb-1">لا توجد نتائج مطابقة</div>
                                        <p class="text-gray-400">لم يتم العثور على أي ملف مطابق لكلمات البحث. يرجى تجربة كلمات أو أرقام أخرى.</p>
                                    </div>
                                </template>

                                {{-- الحالة 4: عرض النتائج المطابقة --}}
                                <template x-if="archiveSearchQuery.trim().length >= 2 && !isSearchingArchive && archiveSearchResults.length > 0">
                                    <div>
                                        <template x-for="file in archiveSearchResults" :key="file.id">
                                            <div @click="selectArchiveFile(file)"
                                                 :class="selectedArchiveFile && selectedArchiveFile.id === file.id ? 'bg-orange/10 border-orange/40 text-orange-dark font-bold' : 'hover:bg-gray-50/80 cursor-pointer'"
                                                 class="p-3.5 flex items-center justify-between transition-colors cursor-pointer group">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div :class="selectedArchiveFile && selectedArchiveFile.id === file.id ? 'bg-orange text-white' : 'bg-orange/10 text-orange group-hover:scale-105'" 
                                                         class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-transform shadow-2xs">
                                                        <x-heroicon-s-folder class="w-4 h-4" />
                                                    </div>
                                                    <div class="min-w-0">
                                                        <div class="text-xs font-bold text-gray-800 truncate" x-text="file.file_name"></div>
                                                        <div class="text-[11px] text-gray-400 flex items-center gap-2 mt-0.5">
                                                            <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded text-[10px]" dir="ltr">
                                                                <span>رقم الملف: </span><span x-text="file.file_no"></span>
                                                            </span>
                                                            <span>•</span>
                                                            <span x-text="file.file_type"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2 shrink-0">
                                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-bold" x-text="file.documents_count + ' وثيقة'"></span>
                                                    <div :class="selectedArchiveFile && selectedArchiveFile.id === file.id ? 'border-orange bg-orange text-white' : 'border-gray-300 text-transparent'"
                                                         class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors">
                                                        <x-heroicon-s-check class="w-3.5 h-3.5" />
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- جدول بيانات الملف المختار للربط وبجانبه زر الربط --}}
                        <div x-show="selectedArchiveFile" x-transition class="mt-4 border border-emerald-200 rounded-2xl overflow-hidden shadow-sm bg-white">
                            <div class="bg-emerald-50/70 px-4 py-2.5 border-b border-emerald-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                    <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-600" />
                                    بيانات الملف المختار للربط
                                </span>
                                <span class="text-[11px] text-emerald-600 font-medium">تأكيد الربط</span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-right text-xs">
                                    <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3">نوع الملف</th>
                                            <th class="px-4 py-3">اسم الملف</th>
                                            <th class="px-4 py-3">رقم الملف</th>
                                            <th class="px-4 py-3 text-center">عدد الوثائق</th>
                                            <th class="px-4 py-3 text-center">إجراء الربط</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 bg-white">
                                        <tr>
                                            <td class="px-4 py-3.5 font-semibold text-gray-800 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg border border-blue-100 text-[11px]" x-text="selectedArchiveFile ? selectedArchiveFile.file_type : ''"></span>
                                            </td>
                                            <td class="px-4 py-3.5 font-bold text-gray-900" x-text="selectedArchiveFile ? selectedArchiveFile.file_name : ''"></td>
                                            <td class="px-4 py-3.5 font-mono font-extrabold text-orange-dark text-xs whitespace-nowrap" dir="ltr" x-text="selectedArchiveFile ? selectedArchiveFile.file_no : ''"></td>
                                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2.5 py-1 rounded-full font-bold text-[11px]">
                                                    <x-heroicon-s-document-text class="w-3.5 h-3.5 text-gray-400" />
                                                    <span x-text="selectedArchiveFile ? selectedArchiveFile.documents_count : 0"></span>
                                                    <span>وثيقة</span>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                                <button type="button"
                                                        @click="linkSelectedArchiveFile()"
                                                        :disabled="isLinkingArchive"
                                                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all disabled:opacity-50 cursor-pointer">
                                                    <template x-if="!isLinkingArchive">
                                                        <span class="flex items-center gap-1">
                                                            <x-heroicon-s-link class="w-3.5 h-3.5" />
                                                            ربط بهذا الملف
                                                        </span>
                                                    </template>
                                                    <template x-if="isLinkingArchive">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                            جاري الربط...
                                                        </span>
                                                    </template>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end">
                            <button @click="closeArchiveModal()" type="button" class="px-5 py-2 text-gray-500 hover:bg-gray-100 rounded-xl font-bold text-xs transition-colors">
                                إغلاق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

    @push('scripts')
    <script>

        function buildingPhotoUploader(initialPhotoUrl) {
            return {
                isDragging: false,
                previewUrl: initialPhotoUrl || null,
                originalUrl: initialPhotoUrl || null,
                fileName: '',
                fileSize: '',
                isNewFile: false,
                removePhoto: false,

                handleDrop(event) {
                    this.isDragging = false;
                    const files = event.dataTransfer?.files;
                    if (files && files.length > 0) {
                        this.processFile(files[0]);
                    }
                },

                handleFileSelect(event) {
                    const files = event.target?.files;
                    if (files && files.length > 0) {
                        this.processFile(files[0]);
                    }
                },

                processFile(file) {
                    if (!file.type.match('image.*')) {
                        alert('يرجى اختيار ملف صورة صالح (JPG, PNG, WEBP).');
                        return;
                    }
                    if (file.size > 10 * 1024 * 1024) {
                        alert('حجم الصورة كبير جداً، الحد الأقصى المسموح به هو 10 ميجابايت.');
                        return;
                    }

                    this.fileName = file.name;
                    this.fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    this.previewUrl = URL.createObjectURL(file);
                    this.isNewFile = true;
                    this.removePhoto = false;

                    // Ensure file is assigned to input if dropped
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    this.$refs.fileInput.files = dt.files;
                },

                clearPhoto() {
                    this.removePhoto = true;
                    this.isNewFile = false;
                    this.fileName = '';
                    this.fileSize = '';
                    this.$refs.fileInput.value = '';
                },

                restoreOriginal() {
                    this.removePhoto = false;
                    this.previewUrl = this.originalUrl;
                    this.isNewFile = false;
                    this.fileName = '';
                    this.fileSize = '';
                    this.$refs.fileInput.value = '';
                }
            };
        }
    </script>
    @endpush
</x-buildings-layout>
