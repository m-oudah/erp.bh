<x-crafts-layout>
    <div x-data="{
            isSubmitting: false,
            buildingSearch: '',
            buildingResults: [],
            isSearchingBuildings: false,
            selectedBuildingId: '{{ old('building_id') }}',
            selectedBuildingLabel: '',
            citizenSearch: '',
            citizenResults: [],
            isSearchingCitizens: false,
            units: [],
            isLoadingUnits: false,
            selectedUnitId: '{{ old('unit_id') }}',

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
         x-init="@if(old('building_id')) loadUnits('{{ old('building_id') }}') @endif"
         class="space-y-6 max-w-7xl mx-auto pb-20">

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
            <div class="flex items-start gap-4">
                <a href="{{ route('crafts.economical.index') }}"
                   class="mt-1 bg-white hover:bg-gray-50 text-gray-400 hover:text-gray-600 p-2.5 rounded-2xl transition-all border border-gray-100 shadow-sm hover:shadow-md flex-shrink-0"
                   title="الرجوع لقائمة الأنشطة">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="bg-orange/10 text-orange-dark text-xs px-3 py-1 rounded-xl font-bold border border-orange/20 flex items-center gap-1.5">
                            <x-heroicon-s-plus-circle class="w-4 h-4 text-orange" />
                            إضافة نشاط جديد
                        </span>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 tracking-tight">
                            تسجيل نشاط اقتصادي أو حرفة
                        </h1>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">إدخال نشاط تجاري أو مهني أو حرفي وربطه بالمباني والوحدات وسجلات الملاك</p>
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

        {{-- Form Start --}}
        <form action="{{ route('crafts.economical.store') }}"
              method="POST"
              id="economical-create-form"
              @submit="isSubmitting = true">
            @csrf

            <input type="hidden" name="building_id" :value="selectedBuildingId">

            <div class="space-y-6">

                {{-- SECTION 1: البيانات الأساسية للنشاط --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-5">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange flex items-center justify-center shrink-0">
                            <x-heroicon-s-building-storefront class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">1. البيانات الأساسية وتصنيف النشاط</h2>
                            <p class="text-xs text-gray-400">الاسم الرسمي والتجاري، القطاع وتصنيف الحرفة</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        {{-- الاسم الرسمي للنشاط --}}
                        <div class="md:col-span-2 lg:col-span-1">
                            <label for="job_formal_name" class="block text-xs font-bold text-gray-700 mb-1">
                                الاسم الرسمي للنشاط / الحرفة <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="job_formal_name"
                                   name="job_formal_name"
                                   required
                                   value="{{ old('job_formal_name') }}"
                                   placeholder="مثال: منجرة السلام، سوبرماركت الأمل..."
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
                                   value="{{ old('trade_name') }}"
                                   placeholder="الاسم على الواجهة إن وجد..."
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
                                <option value="">— اختر القطاع الاقتصادي —</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}" {{ old('job_sector_id') == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('job_sector_id') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- نوع الحرفة / المهنة --}}
                        <div>
                            <label for="craft_type_id" class="block text-xs font-bold text-gray-700 mb-1">
                                نوع الحرفة / المهنة التفصيلية
                            </label>
                            <select id="craft_type_id"
                                    name="craft_type_id"
                                    class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                <option value="">— اختر نوع الحرفة —</option>
                                @foreach($craftTypes as $craft)
                                    <option value="{{ $craft->id }}" {{ old('craft_type_id') == $craft->id ? 'selected' : '' }}>
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
                                <option value="2" {{ old('isActive', '2') == '2' ? 'selected' : '' }}>✓ نشط ومفتوح</option>
                                <option value="1" {{ old('isActive') == '1' ? 'selected' : '' }}>✕ مغلق / غير نشط</option>
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
                                <option value="1" {{ old('work_state', '1') == '1' ? 'selected' : '' }}>نشط / مستمر</option>
                                <option value="0" {{ old('work_state') == '0' ? 'selected' : '' }}>جديد قيد التأسيس</option>
                                <option value="2" {{ old('work_state') == '2' ? 'selected' : '' }}>متوقف مؤقتاً</option>
                            </select>
                            @error('work_state') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- وصف وطبيعة النشاط --}}
                        <div class="md:col-span-2 lg:col-span-3">
                            <label for="job_description" class="block text-xs font-bold text-gray-700 mb-1">
                                وصف وطبيعة النشاط
                            </label>
                            <input type="text"
                                   id="job_description"
                                   name="job_description"
                                   value="{{ old('job_description') }}"
                                   placeholder="وصف مختصر للسلع أو الخدمات أو الأعمال المقدمة..."
                                   class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('job_description') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                {{-- SECTION 2: بيانات المالك / صاحب النشاط --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                <x-heroicon-s-user class="w-5 h-5" />
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-gray-800">2. بيانات صاحب الحرفة / النشاط</h2>
                                <p class="text-xs text-gray-400">رقم الهوية والاسم ورقم الاتصال (مع إمكانية البحث المباشر)</p>
                            </div>
                        </div>

                        {{-- صندوق البحث السريع عن المواطنين --}}
                        <div class="relative w-72 hidden sm:block">
                            <input type="text"
                                   x-model="citizenSearch"
                                   @input.debounce.300ms="searchCitizens()"
                                   placeholder="بحث سريع برقم الهوية أو الاسم..."
                                   class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500/20">
                            <div x-show="isSearchingCitizens" class="absolute left-2.5 top-2.5">
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
                                        <span class="text-[10px] bg-teal-100/70 text-teal-700 px-2 py-0.5 rounded font-bold">اختيار</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- رقم الهوية --}}
                        <div>
                            <label for="id_card" class="block text-xs font-bold text-gray-700 mb-1">
                                رقم الهوية الوطنية
                            </label>
                            <input type="text"
                                   id="id_card"
                                   name="id_card"
                                   value="{{ old('id_card') }}"
                                   placeholder="9 أرقام..."
                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('id_card') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- اسم صاحب النشاط --}}
                        <div class="md:col-span-2">
                            <label for="owner_name" class="block text-xs font-bold text-gray-700 mb-1">
                                اسم صاحب النشاط الرباعي
                            </label>
                            <input type="text"
                                   id="owner_name"
                                   name="owner_name"
                                   value="{{ old('owner_name') }}"
                                   placeholder="الاسم الأول، اسم الأب، اسم الجد، اسم العائلة..."
                                   class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('owner_name') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: الربط المكاني والعقاري --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-building-office class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">3. الموقع والربط بالعقار والوحدة</h2>
                            <p class="text-xs text-gray-400">ربط النشاط بمبنى ووحدة معتمدة في النظام أو تحديد عنوان يدوي</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        {{-- البحث عن المبنى واختياره --}}
                        <div class="md:col-span-2 relative">
                            <label class="block text-xs font-bold text-gray-700 mb-1">
                                المبنى المرتبط
                            </label>
                            
                            {{-- المبنى المختار حالياً --}}
                            <template x-if="selectedBuildingId">
                                <div class="flex items-center justify-between p-2.5 bg-blue-50/80 border border-blue-200 rounded-xl text-xs">
                                    <div class="flex items-center gap-2 text-blue-900 font-bold">
                                        <x-heroicon-s-building-office class="w-4 h-4 text-blue-600 shrink-0" />
                                        <span x-text="selectedBuildingLabel ? selectedBuildingLabel : ('مبنى #' + selectedBuildingId)"></span>
                                    </div>
                                    <button type="button"
                                            @click="clearBuilding()"
                                            class="text-red-500 hover:text-red-700 font-bold px-2 py-0.5 rounded hover:bg-red-50 text-[11px] cursor-pointer">
                                        إلغاء الربط ✕
                                    </button>
                                </div>
                            </template>

                            {{-- حقل البحث في حال عدم اختيار مبنى --}}
                            <div x-show="!selectedBuildingId" class="relative">
                                <input type="text"
                                       x-model="buildingSearch"
                                       @input.debounce.300ms="searchBuildings()"
                                       placeholder="ابحث برقم المبنى أو الشارع أو الحي..."
                                       class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20">
                                
                                <div x-show="isSearchingBuildings" class="absolute left-3 top-3">
                                    <span class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin inline-block"></span>
                                </div>

                                {{-- نتائج البحث عن المباني --}}
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
                                            <span class="text-[10px] bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded">اختيار المبنى</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            @error('building_id') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
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
                                    <option :value="u.id" x-text="u.unit_name + ' (' + u.unit_type + ')'"></option>
                                </template>
                            </select>
                            <span x-show="isLoadingUnits" class="text-[10px] text-blue-500 mt-1 block font-medium">جاري جلب وحدات المبنى...</span>
                            @error('unit_id') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- الشارع --}}
                        <div>
                            <label for="job_street" class="block text-xs font-bold text-gray-700 mb-1">
                                الشارع
                            </label>
                            <input type="text"
                                   id="job_street"
                                   name="job_street"
                                   value="{{ old('job_street') }}"
                                   placeholder="مثال: شارع صلاح الدين..."
                                   class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('job_street') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- العنوان أو وصف الموقع --}}
                        <div class="md:col-span-2">
                            <label for="job_location" class="block text-xs font-bold text-gray-700 mb-1">
                                وصف الموقع التفصيلي
                            </label>
                            <input type="text"
                                   id="job_location"
                                   name="job_location"
                                   value="{{ old('job_location') }}"
                                   placeholder="مثال: مقابل مدرسة بيت حانون، الطابق الأرضي محطة رقم 4..."
                                   class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('job_location') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                {{-- SECTION 4: التراخيص والسلامة العامة --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-shield-check class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">4. بيانات التراخيص والسلامة</h2>
                            <p class="text-xs text-gray-400">حالة الترخيص، أرقام الرخص والملفات ومستوى الخطورة</p>
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
                                <option value="1" {{ old('isLicensed') == '1' ? 'selected' : '' }}>✓ مرخص رسمياً</option>
                                <option value="2" {{ old('isLicensed', '2') == '2' ? 'selected' : '' }}>✕ غير مرخص</option>
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
                                   value="{{ old('license_number') }}"
                                   placeholder="رقم رخصة الحرفة..."
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
                                   value="{{ old('craft_number') }}"
                                   placeholder="رقم الحرفة بالسجل..."
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
                                   value="{{ old('file_number') }}"
                                   placeholder="رقم ملف الأرشيف..."
                                   class="w-full text-xs font-mono font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                            @error('file_number') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- مستوى الخطورة --}}
                        <div>
                            <label for="isDanger" class="block text-xs font-bold text-gray-700 mb-1">
                                مستوى الخطورة
                            </label>
                            <select id="isDanger"
                                    name="isDanger"
                                    class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                <option value="0" {{ old('isDanger', '0') == '0' ? 'selected' : '' }}>عادية (غير خطرة)</option>
                                <option value="1" {{ old('isDanger') == '1' ? 'selected' : '' }}>⚠️ مخاطرة خطرة</option>
                            </select>
                            @error('isDanger') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>

                {{-- SECTION 5: بيانات الاتصال والملاحظات --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
                    <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <x-heroicon-s-phone class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">5. بيانات التواصل والملاحظات الإضافية</h2>
                            <p class="text-xs text-gray-400">أرقام الهواتف والمراسلات والملاحظات الإدارية</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- هاتف النشاط --}}
                        <div>
                            <label for="job_phone" class="block text-xs font-bold text-gray-700 mb-1">هاتف النشاط</label>
                            <input type="text"
                                   id="job_phone"
                                   name="job_phone"
                                   value="{{ old('job_phone') }}"
                                   placeholder="08288XXXX"
                                   class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                            @error('job_phone') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- جوال صاحب النشاط --}}
                        <div>
                            <label for="job_mobile" class="block text-xs font-bold text-gray-700 mb-1">رقم الجوال</label>
                            <input type="text"
                                   id="job_mobile"
                                   name="job_mobile"
                                   value="{{ old('job_mobile') }}"
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
                                   value="{{ old('whatsapp') }}"
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
                                   value="{{ old('email') }}"
                                   placeholder="example@domain.com"
                                   class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                            @error('email') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- ملاحظات --}}
                        <div class="md:col-span-2 lg:col-span-4">
                            <label for="notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات إضافية</label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="2"
                                      placeholder="اكتب أي ملاحظات أو شروط خاصة بالنشاط..."
                                      class="w-full text-xs bg-white border border-gray-200 rounded-xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all leading-relaxed">{{ old('notes') }}</textarea>
                            @error('notes') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

            </div>

            {{-- Sticky Action Bar at Bottom --}}
            <div class="sticky bottom-4 z-40 mt-8 bg-white/95 backdrop-blur-md border border-gray-200/80 rounded-2xl p-4 shadow-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-full bg-orange animate-pulse"></span>
                    <span class="font-bold text-gray-800">تسجيل نشاط اقتصادي / حرفة جديدة</span>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('crafts.economical.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                        إلغاء والعودة
                    </a>
                    <button type="submit"
                            :disabled="isSubmitting"
                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-b from-orange to-orange-dark text-white font-bold py-2.5 px-6 rounded-xl shadow-md shadow-orange/30 hover:shadow-orange/50 transition-all hover:-translate-y-0.5 disabled:opacity-50 cursor-pointer text-xs">
                        <x-heroicon-s-check class="w-4 h-4" />
                        <span>حفظ وإضافة النشاط</span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</x-crafts-layout>
