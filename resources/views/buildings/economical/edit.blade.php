<x-crafts-layout>
    @php
        $firstOwner = $economical->owners->first();
        $ownerFullName = $firstOwner ? trim(collect([$firstOwner->first_name, $firstOwner->second_name, $firstOwner->third_name, $firstOwner->sur_name])->filter()->implode(' ')) : ($economical->name ?? '');
        $ownerIdCard = $firstOwner->id_card ?? ($economical->id_card ?? '');
        $currentCraft = $economical->crafts->first();
        $currentCraftTypeId = $currentCraft->craft_type_id ?? ($economical->craft_type_id ?? '');
        $buildingLabel = $economical->building ? ("مبنى رقم #" . ($economical->building->building_number ?? $economical->building->id) . ' (' . ($economical->building->zone->name ?? '') . ' - ' . ($economical->building->street->street_name ?? '') . ')') : '';
    @endphp

    <div x-data="{
            isSubmitting: false,
            buildingSearch: '',
            buildingResults: [],
            isSearchingBuildings: false,
            selectedBuildingId: '{{ old('building_id', $economical->building_id) }}',
            selectedBuildingLabel: '{{ $buildingLabel }}',
            citizenSearch: '',
            citizenResults: [],
            isSearchingCitizens: false,
            units: {{ json_encode($buildingUnits->map(fn($u) => ['id' => $u->id, 'unit_number' => $u->unit_number, 'unit_name' => $u->unit_name ?: "وحدة رقم {$u->unit_number}", 'unit_type' => $u->unit_type_label])) }},
            isLoadingUnits: false,
            selectedUnitId: '{{ old('unit_id', $economical->unit_id) }}',

            searchBuildings() {
                if (this.buildingSearch.trim().length < 1) {
                    this.buildingResults = [];
                    return;
                }
                this.isSearchingBuildings = true;
                fetch(`{{ route('crafts.economical.search-buildings') }}?q=${encodeURIComponent(this.buildingSearch.trim())}`)
                    .then(res => res.json())
                    .then(data => {
                        this.buildingResults = data.data || [];
                    })
                    .catch(() => { this.buildingResults = []; })
                    .finally(() => { this.isSearchingBuildings = false; });
            },

            selectBuilding(building) {
                this.selectedBuildingId = building.id;
                this.selectedBuildingLabel = building.label;
                this.buildingResults = [];
                this.buildingSearch = '';
                this.loadUnits(building.id);
            },

            clearBuilding() {
                this.selectedBuildingId = '';
                this.selectedBuildingLabel = '';
                this.units = [];
                this.selectedUnitId = '';
            },

            loadUnits(buildingId) {
                if (!buildingId) {
                    this.units = [];
                    return;
                }
                this.isLoadingUnits = true;
                fetch(`{{ url('crafts/economical/building-units') }}/${buildingId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.units = data.data || [];
                    })
                    .catch(() => { this.units = []; })
                    .finally(() => { this.isLoadingUnits = false; });
            },

            searchCitizens() {
                if (this.citizenSearch.trim().length < 2) {
                    this.citizenResults = [];
                    return;
                }
                this.isSearchingCitizens = true;
                fetch(`{{ route('crafts.economical.search-citizens') }}?q=${encodeURIComponent(this.citizenSearch.trim())}`)
                    .then(res => res.json())
                    .then(data => {
                        this.citizenResults = data.data || [];
                    })
                    .catch(() => { this.citizenResults = []; })
                    .finally(() => { this.isSearchingCitizens = false; });
            },

            selectCitizen(citizen) {
                document.getElementById('id_card').value = citizen.id_no || '';
                document.getElementById('owner_name').value = citizen.name || '';
                if (citizen.mobile) {
                    const mobileInput = document.getElementById('job_mobile');
                    if (mobileInput && !mobileInput.value) {
                        mobileInput.value = citizen.mobile;
                    }
                }
                this.citizenResults = [];
                this.citizenSearch = '';
            }
         }"
         class="space-y-6 max-w-7xl mx-auto pb-20">

        {{-- Form Start --}}
        <form action="{{ route('crafts.economical.update', $economical->id) }}"
              method="POST"
              id="economical-edit-form"
              @submit="isSubmitting = true">
            @csrf
            @method('PUT')

            <input type="hidden" name="building_id" :value="selectedBuildingId">

            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                <div class="flex items-start gap-4">
                    <a href="{{ route('crafts.economical.show', $economical->id) }}"
                       class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0"
                       title="الرجوع لصفحة تفاصيل النشاط">
                        <x-heroicon-o-arrow-right class="w-5 h-5" />
                    </a>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="bg-orange/10 text-orange-dark text-xs px-3 py-1 rounded-xl font-bold border border-orange/20 flex items-center gap-1">
                                <x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-orange" />
                                وضع التعديل
                            </span>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">
                                {{ $economical->job_formal_name ?: ($economical->trade_name ?: 'نشاط اقتصادي #' . $economical->id) }}
                            </h1>
                            <span class="bg-blue-50 text-blue-700 text-xs px-3 py-1.5 rounded-xl font-bold border border-blue-100/80 shadow-xs flex items-center gap-1">
                                <x-heroicon-s-hashtag class="w-3.5 h-3.5 text-blue-500" />
                                نشاط رقم {{ $economical->id }}
                            </span>
                            @if($economical->building)
                            <a href="{{ route('buildings.buildings.show', $economical->building_id) }}" 
                               target="_blank"
                               class="bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs px-3 py-1.5 rounded-xl font-bold border border-amber-100/80 shadow-xs flex items-center gap-1 transition-colors">
                                <x-heroicon-s-building-office class="w-3.5 h-3.5 text-amber-500" />
                                مبنى رقم {{ $economical->building->building_number ?? $economical->building_id }}
                            </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-1">تحديث وتعديل بيانات النشاط الاقتصادي والتراخيص وتصنيف القطاع والموقع والربط العقاري</p>
                    </div>
                </div>
            </div>

            {{-- Error Alerts --}}
            @if (isset($errors) && $errors->any())
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

            {{-- Main Layout: 3 Columns (Left 1/3 Summary + Right 2/3 Form Sections) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- Right Column (2/3 in RTL): Main Form Sections --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card 1: البيانات الأساسية للنشاط التجاري --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-5">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shrink-0">
                                <x-heroicon-s-building-storefront class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-gray-800">1. البيانات الأساسية وتصنيف النشاط</h2>
                                <p class="text-xs text-gray-400">تعديل الاسم التجاري والرسمي والقطاع وطبيعة العمل</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- الاسم الرسمي للنشاط --}}
                            <div>
                                <label for="job_formal_name" class="block text-xs font-bold text-gray-700 mb-1">
                                    الاسم الرسمي للنشاط <span class="text-orange">*</span>
                                </label>
                                <input type="text"
                                       id="job_formal_name"
                                       name="job_formal_name"
                                       required
                                       value="{{ old('job_formal_name', $economical->job_formal_name) }}"
                                       placeholder="مثال: سوبرماركت الأمل، صالون الهدى..."
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('job_formal_name') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- الاسم التجاري المتداول --}}
                            <div>
                                <label for="trade_name" class="block text-xs font-bold text-gray-700 mb-1">
                                    الاسم التجاري (المتداول / اللوحة)
                                </label>
                                <input type="text"
                                       id="trade_name"
                                       name="trade_name"
                                       value="{{ old('trade_name', $economical->trade_name) }}"
                                       placeholder="الاسم المكتوب على الواجهة أو اللوحة..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('trade_name') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- القطاع الاقتصادي --}}
                            <div>
                                <label for="job_sector_id" class="block text-xs font-bold text-gray-700 mb-1">
                                    القطاع الاقتصادي
                                </label>
                                <select id="job_sector_id"
                                        name="job_sector_id"
                                        class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— غير مصنف —</option>
                                    @foreach($sectors as $sec)
                                        <option value="{{ $sec->id }}" {{ old('job_sector_id', $economical->job_sector_id) == $sec->id ? 'selected' : '' }}>
                                            {{ $sec->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_sector_id') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- نوع الحرفة / المهنة --}}
                            <div>
                                <label for="craft_type_id" class="block text-xs font-bold text-gray-700 mb-1">
                                    نوع الحرفة / المهنة
                                </label>
                                <select id="craft_type_id"
                                        name="craft_type_id"
                                        class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="">— اختر نوع الحرفة —</option>
                                    @foreach($craftTypes as $craft)
                                        <option value="{{ $craft->id }}" {{ old('craft_type_id', $currentCraftTypeId) == $craft->id ? 'selected' : '' }}>
                                            {{ $craft->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('craft_type_id') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- الحالة التشغيلية --}}
                            <div>
                                <label for="isActive" class="block text-xs font-bold text-gray-700 mb-1">
                                    الحالة التشغيلية
                                </label>
                                <select id="isActive"
                                        name="isActive"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="2" {{ old('isActive', $economical->isActive) == 2 ? 'selected' : '' }}>✓ نشط ومفتوح</option>
                                    <option value="1" {{ old('isActive', $economical->isActive) == 1 ? 'selected' : '' }}>✕ مغلق / غير نشط</option>
                                </select>
                                @error('isActive') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- وضع العمل --}}
                            <div>
                                <label for="work_state" class="block text-xs font-bold text-gray-700 mb-1">
                                    وضع العمل
                                </label>
                                <select id="work_state"
                                        name="work_state"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="1" {{ old('work_state', $economical->work_state) == 1 ? 'selected' : '' }}>نشط / مستمر</option>
                                    <option value="0" {{ old('work_state', $economical->work_state) === '0' || old('work_state', $economical->work_state) === 0 ? 'selected' : '' }}>جديد قيد التأسيس</option>
                                    <option value="2" {{ old('work_state', $economical->work_state) == 2 ? 'selected' : '' }}>متوقف مؤقتاً</option>
                                </select>
                                @error('work_state') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- وصف وطبيعة العمل --}}
                            <div class="md:col-span-2">
                                <label for="job_description" class="block text-xs font-bold text-gray-700 mb-1">
                                    وصف وطبيعة النشاط
                                </label>
                                <input type="text"
                                       id="job_description"
                                       name="job_description"
                                       value="{{ old('job_description', $economical->job_description) }}"
                                       placeholder="مثال: بيع مواد تموينية ومشروبات..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('job_description') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: بيانات صاحب النشاط / المالك --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                    <x-heroicon-s-user class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-gray-800">2. بيانات صاحب الحرفة / النشاط</h2>
                                    <p class="text-xs text-gray-400">تحديث رقم الهوية واسم صاحب المنشأة</p>
                                </div>
                            </div>

                            {{-- صندوق البحث السريع عن المواطنين --}}
                            <div class="relative w-64 hidden sm:block">
                                <input type="text"
                                       x-model="citizenSearch"
                                       @input.debounce.300ms="searchCitizens()"
                                       placeholder="بحث برقم الهوية أو الاسم..."
                                       class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-gray-800 focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500/20">
                                <div x-show="isSearchingCitizens" class="absolute left-2.5 top-2">
                                    <span class="w-3.5 h-3.5 border-2 border-teal-500 border-t-transparent rounded-full animate-spin inline-block"></span>
                                </div>
                                {{-- Dropdown results --}}
                                <div x-show="citizenResults.length > 0"
                                     @click.outside="citizenResults = []"
                                     class="absolute z-50 left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-gray-100 max-h-56 overflow-y-auto divide-y divide-gray-50">
                                    <template x-for="c in citizenResults" :key="c.id_no">
                                        <button type="button"
                                                @click="selectCitizen(c)"
                                                class="w-full text-right p-2.5 hover:bg-teal-50/60 transition-colors flex items-center justify-between text-xs cursor-pointer">
                                            <div>
                                                <div class="font-bold text-gray-800" x-text="c.name"></div>
                                                <div class="font-mono text-gray-400 text-[10px]" x-text="c.id_no"></div>
                                            </div>
                                            <span class="text-[10px] bg-teal-100/70 text-teal-700 px-2 py-0.5 rounded font-bold">تحديد</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="id_card" class="block text-xs font-bold text-gray-700 mb-1">
                                    رقم الهوية الوطنية
                                </label>
                                <input type="text"
                                       id="id_card"
                                       name="id_card"
                                       value="{{ old('id_card', $ownerIdCard) }}"
                                       placeholder="9 أرقام..."
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('id_card') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="owner_name" class="block text-xs font-bold text-gray-700 mb-1">
                                    اسم صاحب النشاط الرباعي
                                </label>
                                <input type="text"
                                       id="owner_name"
                                       name="owner_name"
                                       value="{{ old('owner_name', $ownerFullName) }}"
                                       placeholder="الاسم الرباعي الكامل..."
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('owner_name') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: الربط العقاري والموقع --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-building-office class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-gray-800">3. الموقع والربط بالعقار والوحدة</h2>
                                <p class="text-xs text-gray-400">تغيير المبنى أو الوحدة المرتبطة وتفاصيل العنوان</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- المبنى المرتبط --}}
                            <div class="md:col-span-2 relative">
                                <label class="block text-xs font-bold text-gray-700 mb-1">
                                    المبنى المرتبط
                                </label>
                                
                                <template x-if="selectedBuildingId">
                                    <div class="flex items-center justify-between p-2.5 bg-blue-50/80 border border-blue-200 rounded-xl text-xs">
                                        <div class="flex items-center gap-2 text-blue-900 font-bold">
                                            <x-heroicon-s-building-office class="w-4 h-4 text-blue-600 shrink-0" />
                                            <span x-text="selectedBuildingLabel ? selectedBuildingLabel : ('مبنى #' + selectedBuildingId)"></span>
                                        </div>
                                        <button type="button"
                                                @click="clearBuilding()"
                                                class="text-red-500 hover:text-red-700 font-bold px-2 py-0.5 rounded hover:bg-red-50 text-[11px] cursor-pointer">
                                            تغيير المبنى ✕
                                        </button>
                                    </div>
                                </template>

                                <div x-show="!selectedBuildingId" class="relative">
                                    <input type="text"
                                           x-model="buildingSearch"
                                           @input.debounce.300ms="searchBuildings()"
                                           placeholder="ابحث برقم المبنى أو الشارع أو الحي..."
                                           class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20">
                                    
                                    <div x-show="isSearchingBuildings" class="absolute left-3 top-3">
                                        <span class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin inline-block"></span>
                                    </div>

                                    <div x-show="buildingResults.length > 0"
                                         @click.outside="buildingResults = []"
                                         class="absolute z-50 left-0 right-0 mt-1 bg-white rounded-xl shadow-xl border border-gray-100 max-h-56 overflow-y-auto divide-y divide-gray-50">
                                        <template x-for="b in buildingResults" :key="b.id">
                                            <button type="button"
                                                    @click="selectBuilding(b)"
                                                    class="w-full text-right p-3 hover:bg-blue-50/60 transition-colors flex items-center justify-between text-xs cursor-pointer">
                                                <div>
                                                    <div class="font-bold text-gray-800" x-text="'مبنى رقم #' + b.building_number"></div>
                                                    <div class="text-[10px] text-gray-400" x-text="b.zone_name + ' - ' + b.street_name"></div>
                                                </div>
                                                <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded">اختيار</span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- الوحدة السكنية / التجارية --}}
                            <div>
                                <label for="unit_id" class="block text-xs font-bold text-gray-700 mb-1">
                                    الوحدة داخل المبنى
                                </label>
                                <select id="unit_id"
                                        name="unit_id"
                                        x-model="selectedUnitId"
                                        :disabled="!selectedBuildingId || units.length === 0"
                                        class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all disabled:bg-gray-100 disabled:text-gray-400">
                                    <option value="">— اختر الوحدة إن وجدت —</option>
                                    <template x-for="u in units" :key="u.id">
                                        <option :value="u.id" x-text="u.unit_name + ' (' + u.unit_type + ')'" :selected="selectedUnitId == u.id"></option>
                                    </template>
                                </select>
                                <span x-show="isLoadingUnits" class="text-[10px] text-blue-500 mt-1 block font-medium">جاري جلب وحدات المبنى...</span>
                            </div>

                            {{-- الشارع --}}
                            <div>
                                <label for="job_street" class="block text-xs font-bold text-gray-700 mb-1">
                                    الشارع
                                </label>
                                <input type="text"
                                       id="job_street"
                                       name="job_street"
                                       value="{{ old('job_street', $economical->job_street) }}"
                                       placeholder="اسم الشارع..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            </div>

                            {{-- العنوان أو وصف الموقع --}}
                            <div class="md:col-span-2">
                                <label for="job_location" class="block text-xs font-bold text-gray-700 mb-1">
                                    العنوان أو وصف الموقع التفصيلي
                                </label>
                                <input type="text"
                                       id="job_location"
                                       name="job_location"
                                       value="{{ old('job_location', $economical->job_location) }}"
                                       placeholder="مثال: بجوار مسجد النور، شارع صلاح الدين..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('job_location') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Card 4: التراخيص ومستوى السلامة والخطورة --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-shield-check class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-gray-800">4. التراخيص والسلامة العامة</h2>
                                <p class="text-xs text-gray-400">تعديل الوضع القانوني للترخيص ومستوى الخطورة وأرقام الرخص</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            {{-- حالة الترخيص --}}
                            <div>
                                <label for="isLicensed" class="block text-xs font-bold text-gray-700 mb-1">
                                    حالة الترخيص
                                </label>
                                <select id="isLicensed"
                                        name="isLicensed"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="1" {{ old('isLicensed', $economical->isLicensed) == 1 ? 'selected' : '' }}>
                                        ✓ مرخص رسمياً
                                    </option>
                                    <option value="2" {{ old('isLicensed', $economical->isLicensed) == 2 || old('isLicensed', $economical->isLicensed) === 0 ? 'selected' : '' }}>
                                        ✕ غير مرخص
                                    </option>
                                </select>
                                @error('isLicensed') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- رقم الرخصة --}}
                            <div>
                                <label for="license_number" class="block text-xs font-bold text-gray-700 mb-1">
                                    رقم الرخصة
                                </label>
                                <input type="text"
                                       id="license_number"
                                       name="license_number"
                                       value="{{ old('license_number', $economical->license_number) }}"
                                       placeholder="رقم الرخصة..."
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('license_number') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- رقم الحرفة --}}
                            <div>
                                <label for="craft_number" class="block text-xs font-bold text-gray-700 mb-1">
                                    رقم الحرفة
                                </label>
                                <input type="text"
                                       id="craft_number"
                                       name="craft_number"
                                       value="{{ old('craft_number', $economical->craft_number) }}"
                                       placeholder="رقم الحرفة..."
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('craft_number') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- رقم الملف --}}
                            <div>
                                <label for="file_number" class="block text-xs font-bold text-gray-700 mb-1">
                                    رقم الملف
                                </label>
                                <input type="text"
                                       id="file_number"
                                       name="file_number"
                                       value="{{ old('file_number', $economical->file_number) }}"
                                       placeholder="رقم الملف..."
                                       class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('file_number') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- مستوى الخطورة والسلامة --}}
                            <div>
                                <label for="isDanger" class="block text-xs font-bold text-gray-700 mb-1">
                                    مستوى الخطورة
                                </label>
                                <select id="isDanger"
                                        name="isDanger"
                                        class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    <option value="0" {{ old('isDanger', $economical->isDanger) == 0 ? 'selected' : '' }}>
                                        عادية (غير خطرة)
                                    </option>
                                    <option value="1" {{ old('isDanger', $economical->isDanger) == 1 ? 'selected' : '' }}>
                                        ⚠️ مخاطرة خطرة
                                    </option>
                                </select>
                                @error('isDanger') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Card 5: بيانات التواصل والملاحظات --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-phone class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-gray-800">5. بيانات التواصل والملاحظات</h2>
                                <p class="text-xs text-gray-400">أرقام الهواتف، العناوين ومعلومات الاتصال والملاحظات</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- هاتف النشاط --}}
                            <div>
                                <label for="job_phone" class="block text-xs font-bold text-gray-700 mb-1">هاتف النشاط</label>
                                <input type="text"
                                       id="job_phone"
                                       name="job_phone"
                                       value="{{ old('job_phone', $economical->job_phone) }}"
                                       placeholder="08288XXXX"
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('job_phone') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- رقم الجوال --}}
                            <div>
                                <label for="job_mobile" class="block text-xs font-bold text-gray-700 mb-1">رقم الجوال</label>
                                <input type="text"
                                       id="job_mobile"
                                       name="job_mobile"
                                       value="{{ old('job_mobile', $economical->job_mobile) }}"
                                       placeholder="0599XXXXXX"
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('job_mobile') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- واتساب --}}
                            <div>
                                <label for="whatsapp" class="block text-xs font-bold text-gray-700 mb-1">واتساب</label>
                                <input type="text"
                                       id="whatsapp"
                                       name="whatsapp"
                                       value="{{ old('whatsapp', $economical->whatsapp) }}"
                                       placeholder="0599XXXXXX"
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('whatsapp') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- البريد الإلكتروني --}}
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $economical->email) }}"
                                       placeholder="example@domain.com"
                                       class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('email') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- ملاحظات إضافية --}}
                            <div class="md:col-span-2 lg:col-span-4">
                                <label for="notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات إضافية</label>
                                <textarea id="notes"
                                          name="notes"
                                          rows="2"
                                          placeholder="اكتب أي ملاحظات أو تفاصيل إضافية هنا..."
                                          class="w-full text-xs bg-white border border-gray-200 rounded-xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all leading-relaxed">{{ old('notes', $economical->notes) }}</textarea>
                                @error('notes') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Left Column (1/3 in RTL): Summary & Context Cards --}}
                <div class="space-y-6">

                    {{-- Overview Card --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <div class="p-1.5 bg-orange/10 text-orange rounded-lg">
                                <x-heroicon-s-information-circle class="w-4 h-4" />
                            </div>
                            <h3 class="text-xs font-extrabold text-gray-800">نظرة عامة على النشاط</h3>
                        </div>

                        {{-- Quick Stats Pills --}}
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">حالة الترخيص:</span>
                                @if($economical->isLicensed == 1)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        مرخص
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-gray-600 bg-gray-200/70 px-2.5 py-0.5 rounded-lg">
                                        غير مرخص
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">مستوى الخطورة:</span>
                                @if($economical->isDanger == 1)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-700 bg-red-100/70 px-2.5 py-0.5 rounded-lg">
                                        مخاطرة خطرة
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-lg">
                                        عادية
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">المهن والحرف:</span>
                                <span class="text-xs font-bold text-gray-800 bg-white px-2.5 py-0.5 rounded-lg border border-gray-200 shadow-2xs">
                                    {{ $economical->crafts->count() }} حرفة
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 border border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">تاريخ الإنشاء:</span>
                                <span class="text-xs font-mono font-bold text-gray-700">
                                    {{ $economical->created_at ? $economical->created_at->format('Y-m-d') : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Context Card: Building Connection --}}
                    @if($economical->building)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                <x-heroicon-s-building-office class="w-4 h-4" />
                            </div>
                            <h3 class="text-xs font-extrabold text-gray-800">المبنى المرتبط حالياً</h3>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">رقم المبنى:</span>
                                <a href="{{ route('buildings.buildings.show', $economical->building_id) }}" 
                                   target="_blank"
                                   class="font-mono font-bold text-blue-600 hover:underline">
                                    #{{ $economical->building->building_number ?? $economical->building_id }}
                                </a>
                            </div>
                            @if($economical->building->zone)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">الحي / المنطقة:</span>
                                <span class="font-bold text-gray-700">{{ $economical->building->zone->name }}</span>
                            </div>
                            @endif
                            @if($economical->building->street)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">الشارع:</span>
                                <span class="font-bold text-gray-700">{{ $economical->building->street->street_name }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Context Card: Owner Connection --}}
                    @if($firstOwner || $economical->name)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <div class="p-1.5 bg-teal-100 text-teal-600 rounded-lg">
                                <x-heroicon-s-user class="w-4 h-4" />
                            </div>
                            <h3 class="text-xs font-extrabold text-gray-800">صاحب النشاط في الأرشيف</h3>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">الاسم:</span>
                                <span class="font-bold text-gray-800">
                                    {{ $ownerFullName ?: '—' }}
                                </span>
                            </div>
                            @if($ownerIdCard)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">رقم الهوية:</span>
                                <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $ownerIdCard }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

            </div>

            {{-- Sticky Action Bar at Bottom --}}
            <div class="sticky bottom-4 z-40 mt-8 bg-white/95 backdrop-blur-md border border-gray-200/80 rounded-2xl p-4 shadow-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange animate-pulse"></span>
                    <span class="font-bold text-gray-800">أنت في وضع تعديل النشاط:</span>
                    <span class="font-bold text-orange-dark truncate max-w-[200px] sm:max-w-[320px]">
                        {{ $economical->job_formal_name ?: ($economical->trade_name ?: 'نشاط #' . $economical->id) }}
                    </span>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('crafts.economical.show', $economical->id) }}"
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

    </div>
</x-crafts-layout>
