<x-buildings-layout>
    <div class="space-y-6 max-w-5xl mx-auto pb-12" x-data="unitFormHandler({{ $selectedBuilding ? $selectedBuilding->id : 'null' }})">

        {{-- Page Header --}}
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('buildings.units.index') }}"
                   class="bg-white hover:bg-gray-50 text-gray-500 hover:text-gray-700 p-2.5 rounded-2xl transition border border-gray-200 shadow-2xs"
                   title="الرجوع لسجل الوحدات">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <span class="bg-orange/10 text-orange-dark text-xs px-3 py-1 rounded-xl font-bold border border-orange/20">
                        نموذج تسجيل وحدة
                    </span>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-1">إضافة وحدة عقارية جديدة</h1>
                    <p class="text-xs text-gray-500 mt-0.5">
                        تسجيل وحدة جديدة في جدول الوحدات (bhm_units) وربطها بالمبنى وملاكها واستخداماتها
                    </p>
                </div>
            </div>

            <a href="{{ route('buildings.units.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
                إلغاء
            </a>
        </div>

        {{-- Validation Errors Banner --}}
        @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-800 space-y-1 shadow-2xs">
            <div class="font-black flex items-center gap-2">
                <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-rose-600" />
                <span>يرجى تصحيح الأخطاء التالية للمتابعة:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pr-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Main Form --}}
        <form action="{{ route('buildings.units.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- 1. Building Selection Card --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-building-office class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">المبنى التابعة له هذه الوحدة</h3>
                        <p class="text-[11px] text-gray-400">حدد المبنى الذي تقع فيه الوحدة (إلزامي)</p>
                    </div>
                </div>

                {{-- Building Search & Autocomplete --}}
                <div class="relative">
                    <label for="building_search_input" class="block text-xs font-bold text-gray-700 mb-1.5">
                        البحث عن المبنى (بالرقم أو رقم الملف أو الشارع أو اسم المالك) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="building_search_input"
                               x-model="buildingSearchQuery"
                               @input.debounce.300ms="searchBuildings()"
                               placeholder="ابحث برقم المبنى أو رقم الملف..."
                               class="w-full pr-10 pl-4 py-2.5 bg-gray-50/70 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:bg-white focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                        </div>
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center" x-show="isSearchingBuilding">
                            <svg class="animate-spin w-4 h-4 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </div>
                    </div>

                    {{-- Autocomplete Results Dropdown --}}
                    <div x-show="buildingSearchResults.length > 0"
                         x-transition
                         class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-2xl shadow-xl max-h-56 overflow-y-auto divide-y divide-gray-100">
                        <template x-for="b in buildingSearchResults" :key="b.id">
                            <div @click="selectBuilding(b)"
                                 class="p-3 hover:bg-orange/5 cursor-pointer flex items-center justify-between transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-orange/10 text-orange-dark flex items-center justify-center font-bold text-xs">
                                        #<span x-text="b.building_number || b.id"></span>
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-xs" x-text="b.display_name"></div>
                                        <div class="text-[10px] text-gray-400 flex items-center gap-2">
                                            <span x-text="'ملف: ' + (b.file_number || 'غير مسجل')"></span>
                                            <span x-show="b.street_name" x-text="'• شارع: ' + b.street_name"></span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-teal-50 text-teal-700 px-2 py-1 rounded-md font-bold">اختيار</span>
                            </div>
                        </template>
                    </div>

                    <input type="hidden" name="building_id" x-model="selectedBuildingId" required>
                </div>

                {{-- Selected Building Summary Badge --}}
                <div x-show="selectedBuilding" x-transition class="p-4 bg-blue-50/70 border border-blue-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-2xs font-bold text-xs">
                            <x-heroicon-s-check-circle class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-xs font-black text-blue-950" x-text="'المبنى المختار: ' + (selectedBuilding ? selectedBuilding.display_name : '')"></div>
                            <div class="text-[11px] text-blue-700 mt-0.5 flex items-center gap-2">
                                <span x-show="selectedBuilding && selectedBuilding.file_number" x-text="'رقم الملف: ' + selectedBuilding.file_number"></span>
                                <span x-show="selectedBuilding && selectedBuilding.street_name" x-text="'• الشارع: ' + selectedBuilding.street_name"></span>
                            </div>
                        </div>
                    </div>
                    <button type="button" @click="clearSelectedBuilding()" class="text-xs text-rose-600 hover:text-rose-800 font-bold px-2 py-1 bg-white rounded-lg border border-rose-200">
                        تغيير المبنى
                    </button>
                </div>
            </div>

            {{-- 2. Unit Basic Info Card --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-squares-plus class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">بيانات الوحدة العقارية</h3>
                        <p class="text-[11px] text-gray-400">الرقم والطابق والاسم والنوع</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Unit Number --}}
                    <div>
                        <label for="unit_number" class="block text-xs font-bold text-gray-700 mb-1.5">
                            رقم الوحدة <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="unit_number" name="unit_number"
                               value="{{ old('unit_number') }}"
                               placeholder="مثال: 1 أو 2 أو 101"
                               class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none"
                               required>
                    </div>

                    {{-- Unit Name --}}
                    <div>
                        <label for="unit_name" class="block text-xs font-bold text-gray-700 mb-1.5">اسم / وصف الوحدة</label>
                        <input type="text" id="unit_name" name="unit_name"
                               value="{{ old('unit_name') }}"
                               placeholder="مثال: شقة شمالية، محل تجاري..."
                               class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>

                    {{-- Floor Number --}}
                    <div>
                        <label for="floor_number" class="block text-xs font-bold text-gray-700 mb-1.5">رقم الطابق</label>
                        <select id="floor_number" name="floor_number"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                            <option value="0" {{ old('floor_number', '0') == '0' ? 'selected' : '' }}>الطابق الأرضي (0)</option>
                            <option value="1" {{ old('floor_number') == '1' ? 'selected' : '' }}>الطابق الأول (1)</option>
                            <option value="2" {{ old('floor_number') == '2' ? 'selected' : '' }}>الطابق الثاني (2)</option>
                            <option value="3" {{ old('floor_number') == '3' ? 'selected' : '' }}>الطابق الثالث (3)</option>
                            <option value="4" {{ old('floor_number') == '4' ? 'selected' : '' }}>الطابق الرابع (4)</option>
                            <option value="5" {{ old('floor_number') == '5' ? 'selected' : '' }}>الطابق الخامس (5)</option>
                            <option value="-1" {{ old('floor_number') == '-1' ? 'selected' : '' }}>طابق التسوية / البدروم (-1)</option>
                        </select>
                    </div>

                    {{-- Unit Type --}}
                    <div>
                        <label for="unit_type" class="block text-xs font-bold text-gray-700 mb-1.5">نوع الوحدة الرئيسي</label>
                        <select id="unit_type" name="unit_type"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                            <option value="1" {{ old('unit_type', '1') == '1' ? 'selected' : '' }}>سكني</option>
                            <option value="2" {{ old('unit_type') == '2' ? 'selected' : '' }}>تجاري</option>
                            <option value="3" {{ old('unit_type') == '3' ? 'selected' : '' }}>صناعي</option>
                            <option value="11" {{ old('unit_type') == '11' ? 'selected' : '' }}>خدماتي / أخرى</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- 3. Usages Selection Card --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-tag class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">استخدامات الوحدة الفعلية</h3>
                        <p class="text-[11px] text-gray-400">حدد كافة الاستخدامات المنطبقة على هذه الوحدة</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-emerald-50/50 hover:border-emerald-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="residential" value="1" {{ old('residential', '1') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-gray-800">سكني</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-blue-50/50 hover:border-blue-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="commercial" value="1" {{ old('commercial') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="text-xs font-bold text-gray-800">تجاري</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-purple-50/50 hover:border-purple-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="industrial" value="1" {{ old('industrial') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                        <span class="text-xs font-bold text-gray-800">صناعي</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-cyan-50/50 hover:border-cyan-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="educational" value="1" {{ old('educational') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-cyan-600 rounded border-gray-300 focus:ring-cyan-500">
                        <span class="text-xs font-bold text-gray-800">تعليمي</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-rose-50/50 hover:border-rose-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="health" value="1" {{ old('health') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-rose-600 rounded border-gray-300 focus:ring-rose-500">
                        <span class="text-xs font-bold text-gray-800">صحي</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-teal-50/50 hover:border-teal-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="religous" value="1" {{ old('religous') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500">
                        <span class="text-xs font-bold text-gray-800">ديني / مسجد</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-indigo-50/50 hover:border-indigo-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="cultural" value="1" {{ old('cultural') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="text-xs font-bold text-gray-800">ثقافي</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-amber-50/50 hover:border-amber-200 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="tourism" value="1" {{ old('tourism') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-amber-600 rounded border-gray-300 focus:ring-amber-500">
                        <span class="text-xs font-bold text-gray-800">سياحي</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-slate-100 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="institutions" value="1" {{ old('institutions') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-slate-700 rounded border-gray-300 focus:ring-slate-500">
                        <span class="text-xs font-bold text-gray-800">مؤسسات</span>
                    </label>

                    <label class="p-3 rounded-2xl border border-gray-200 bg-gray-50/60 hover:bg-gray-100 transition-colors flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="other_usage" value="1" {{ old('other_usage') == '1' ? 'checked' : '' }}
                               class="w-4 h-4 text-gray-700 rounded border-gray-300 focus:ring-gray-500">
                        <span class="text-xs font-bold text-gray-800">أخرى</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label for="profession_name" class="block text-xs font-bold text-gray-700 mb-1">اسم المهنة / المحل التجاري (إن وجد)</label>
                        <input type="text" id="profession_name" name="profession_name"
                               value="{{ old('profession_name') }}"
                               placeholder="مثال: سوبرماركت، صالون، مكتب..."
                               class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>

                    <div>
                        <label for="current_situation" class="block text-xs font-bold text-gray-700 mb-1">الوضع الراهن للوحدة</label>
                        <select id="current_situation" name="current_situation"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                            <option value="1" {{ old('current_situation', '1') == '1' ? 'selected' : '' }}>قائم ومستغل</option>
                            <option value="2" {{ old('current_situation') == '2' ? 'selected' : '' }}>قائم وشاغر</option>
                            <option value="3" {{ old('current_situation') == '3' ? 'selected' : '' }}>قيد الإنشاء والتشطيب</option>
                            <option value="4" {{ old('current_situation') == '4' ? 'selected' : '' }}>مهجور / غير صالح</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- 4. Unit Owner Details Card --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shadow-2xs">
                            <x-heroicon-s-user class="w-4 h-4" />
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-gray-900">بيانات مالك الوحدة</h3>
                            <p class="text-[11px] text-gray-400">ربط الوحدة بأحد ملاك المبنى أو إدخال مالك مستقل من سجل المواطنين</p>
                        </div>
                    </div>

                    <button type="button"
                            onclick="window.openCitizenModal({
                                title: 'اختيار مواطن من السجل الموحد لمالك الوحدة',
                                onSuccess: function(c) {
                                    document.getElementById('owner_id_card').value = c.id_no || '';
                                    const parts = (c.name || '').trim().split(/\s+/);
                                    document.getElementById('owner_first_name').value = parts[0] || '';
                                    document.getElementById('owner_second_name').value = parts[1] || '';
                                    document.getElementById('owner_third_name').value = parts[2] || '';
                                    document.getElementById('owner_sur_name').value = parts.slice(3).join(' ') || (parts[3] || '');
                                    const phoneEl = document.getElementById('owner_phone');
                                    if (phoneEl) phoneEl.value = c.mobile || c.telephone || '';
                                }
                            })"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold rounded-xl border border-teal-200 transition cursor-pointer">
                        <x-heroicon-s-magnifying-glass class="w-3.5 h-3.5 text-teal-600" />
                        <span>البحث في سجل المواطنين الموحد</span>
                    </button>
                </div>

                {{-- Option 1: Choose from building owners --}}
                <div x-show="buildingOwners.length > 0" x-transition class="p-3.5 bg-gray-50/70 border border-gray-200 rounded-2xl">
                    <label for="building_owner_id" class="block text-xs font-bold text-gray-700 mb-1">اختيار من ملاك هذا المبنى المسجلين</label>
                    <select id="building_owner_id" name="building_owner_id"
                            @change="onBuildingOwnerSelect($event.target.value)"
                            class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                        <option value="">— اختيار مالك من المبنى أو كتابة مالك جديد أدناه —</option>
                        <template x-for="bo in buildingOwners" :key="bo.id">
                            <option :value="bo.id" x-text="bo.name + ' (هوية: ' + bo.id_card + ')'"></option>
                        </template>
                    </select>
                </div>

                {{-- Option 2: Individual owner details --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 pt-1">
                    <div>
                        <label for="owner_id_card" class="block text-xs font-bold text-gray-700 mb-1">رقم الهوية الوطنية</label>
                        <input type="text" id="owner_id_card" name="owner_id_card"
                               value="{{ old('owner_id_card') }}"
                               placeholder="مثال: 908980817"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none"
                               dir="ltr">
                    </div>

                    <div>
                        <label for="owner_first_name" class="block text-xs font-bold text-gray-700 mb-1">الاسم الأول</label>
                        <input type="text" id="owner_first_name" name="owner_first_name"
                               value="{{ old('owner_first_name') }}"
                               placeholder="الاسم الأول"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>

                    <div>
                        <label for="owner_second_name" class="block text-xs font-bold text-gray-700 mb-1">اسم الأب</label>
                        <input type="text" id="owner_second_name" name="owner_second_name"
                               value="{{ old('owner_second_name') }}"
                               placeholder="اسم الأب"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>

                    <div>
                        <label for="owner_third_name" class="block text-xs font-bold text-gray-700 mb-1">اسم الجد</label>
                        <input type="text" id="owner_third_name" name="owner_third_name"
                               value="{{ old('owner_third_name') }}"
                               placeholder="اسم الجد"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>

                    <div>
                        <label for="owner_sur_name" class="block text-xs font-bold text-gray-700 mb-1">العائلة / اللقب</label>
                        <input type="text" id="owner_sur_name" name="owner_sur_name"
                               value="{{ old('owner_sur_name') }}"
                               placeholder="اسم العائلة"
                               class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label for="owner_phone" class="block text-xs font-bold text-gray-700 mb-1">رقم الهاتف / الجوال</label>
                    <input type="text" id="owner_phone" name="owner_phone"
                           value="{{ old('owner_phone') }}"
                           placeholder="مثال: 0599123456"
                           class="w-full max-w-sm px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none"
                           dir="ltr">
                </div>
            </div>

            {{-- 5. Notes Card --}}
            <div class="bg-white rounded-3xl border border-gray-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center gap-2.5 border-b border-gray-100 pb-3">
                    <div class="w-8 h-8 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center shadow-2xs">
                        <x-heroicon-s-document-text class="w-4 h-4" />
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900">ملاحظات الوحدة</h3>
                        <p class="text-[11px] text-gray-400">أي ملاحظات إدارية أو فنية حول الوحدة</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="unit_notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات عامة</label>
                        <textarea id="unit_notes" name="unit_notes" rows="3"
                                  placeholder="ملاحظات حول حالة الوحدة أو اشتراكاتها..."
                                  class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">{{ old('unit_notes') }}</textarea>
                    </div>

                    <div>
                        <label for="unit_usage_notes" class="block text-xs font-bold text-gray-700 mb-1">ملاحظات الاستخدام</label>
                        <textarea id="unit_usage_notes" name="unit_usage_notes" rows="3"
                                  placeholder="تفاصيل حول طريقة استغلال الوحدة أو نشاطها..."
                                  class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all outline-none">{{ old('unit_usage_notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Form Actions Bar --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('buildings.units.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition cursor-pointer">
                    إلغاء والعودة
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-orange to-orange-dark hover:from-orange-dark hover:to-orange text-white font-black py-3 px-8 rounded-xl shadow-md shadow-orange/30 hover:shadow-orange/50 transition-all text-xs cursor-pointer">
                    <x-heroicon-s-check class="w-4 h-4" />
                    <span>حفظ وتسجيل الوحدة العقارية</span>
                </button>
            </div>
        </form>

    </div>

    <script>
        function unitFormHandler(initialBuildingId) {
            return {
                selectedBuildingId: initialBuildingId || '',
                selectedBuilding: null,
                buildingSearchQuery: '',
                buildingSearchResults: [],
                isSearchingBuilding: false,
                buildingOwners: [],

                init() {
                    @if($selectedBuilding)
                        this.selectedBuilding = {
                            id: {{ $selectedBuilding->id }},
                            display_name: '{{ $selectedBuilding->display_name }}',
                            file_number: '{{ $selectedBuilding->file_number }}',
                            street_name: '{{ $selectedBuilding->street?->street_name }}'
                        };
                        this.selectedBuildingId = {{ $selectedBuilding->id }};
                        this.buildingOwners = @json($buildingOwners->map(fn($o) => [
                            'id' => $o->id,
                            'name' => $o->full_name,
                            'id_card' => $o->id_card,
                            'first_name' => $o->first_name,
                            'second_name' => $o->second_name,
                            'third_name' => $o->third_name,
                            'sur_name' => $o->sur_name,
                            'phone' => $o->phone_number
                        ]));
                    @endif
                },

                async searchBuildings() {
                    const q = (this.buildingSearchQuery || '').trim();
                    if (q.length < 1) {
                        this.buildingSearchResults = [];
                        return;
                    }
                    this.isSearchingBuilding = true;
                    try {
                        const url = '{{ route('buildings.units.search-buildings') }}';
                        const res = await fetch(`${url}?q=${encodeURIComponent(q)}`, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.buildingSearchResults = json.data;
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isSearchingBuilding = false;
                    }
                },

                selectBuilding(b) {
                    this.selectedBuildingId = b.id;
                    this.selectedBuilding = b;
                    this.buildingOwners = b.owners || [];
                    this.buildingSearchResults = [];
                    this.buildingSearchQuery = '';
                },

                clearSelectedBuilding() {
                    this.selectedBuildingId = '';
                    this.selectedBuilding = null;
                    this.buildingOwners = [];
                },

                onBuildingOwnerSelect(ownerId) {
                    if (!ownerId) return;
                    const owner = this.buildingOwners.find(o => String(o.id) === String(ownerId));
                    if (owner) {
                        document.getElementById('owner_id_card').value = owner.id_card || '';
                        document.getElementById('owner_first_name').value = owner.first_name || '';
                        document.getElementById('owner_second_name').value = owner.second_name || '';
                        document.getElementById('owner_third_name').value = owner.third_name || '';
                        document.getElementById('owner_sur_name').value = owner.sur_name || '';
                        document.getElementById('owner_phone').value = (owner.phone && owner.phone !== '0') ? owner.phone : '';
                    }
                }
            };
        }
    </script>
</x-buildings-layout>

