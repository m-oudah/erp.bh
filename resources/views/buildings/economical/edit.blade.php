<x-crafts-layout>
    <div x-data="{ isSubmitting: false }" class="space-y-6 max-w-7xl mx-auto pb-16">

        {{-- Form Start --}}
        <form action="{{ route('crafts.economical.update', $economical->id) }}"
              method="POST"
              id="economical-edit-form"
              @submit="isSubmitting = true">
            @csrf
            @method('PUT')

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
                        <p class="text-xs text-gray-400 mt-1">تحديث وتعديل بيانات النشاط الاقتصادي والتراخيص وتصنيف القطاع والموقع</p>
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

            {{-- Main Layout: 3 Columns (Left 1/3 Summary + Right 2/3 Form Sections) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- Right Column (2/3 in RTL): Main Form Sections --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card 1: البيانات الأساسية للنشاط التجاري --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-orange/10 text-orange-dark flex items-center justify-center shrink-0">
                                    <x-heroicon-s-building-storefront class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-gray-800">البيانات الأساسية وتصنيف النشاط</h2>
                                    <p class="text-xs text-gray-400">تعديل الاسم التجاري والرسمي والقطاع وطبيعة العمل</p>
                                </div>
                            </div>
                        </div>

                        {{-- Groups Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            {{-- المجموعة 1: الأسماء والمسميات --}}
                            <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3.5">
                                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                    <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                        <x-heroicon-s-identification class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">المسميات التجارية</span>
                                </div>

                                {{-- الاسم الرسمي للنشاط --}}
                                <div>
                                    <label for="job_formal_name" class="block text-xs font-bold text-gray-700 mb-1">
                                        الاسم الرسمي للنشاط <span class="text-orange">*</span>
                                    </label>
                                    <input type="text" id="job_formal_name" name="job_formal_name"
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
                                    <input type="text" id="trade_name" name="trade_name"
                                           value="{{ old('trade_name', $economical->trade_name) }}"
                                           placeholder="الاسم المكتوب على الواجهة أو اللوحة..."
                                           class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    @error('trade_name') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- المجموعة 2: القطاع والتصنيف --}}
                            <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3.5">
                                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                    <div class="p-1.5 bg-purple-100 text-purple-600 rounded-lg">
                                        <x-heroicon-s-tag class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">التصنيف والقطاع</span>
                                </div>

                                {{-- القطاع --}}
                                <div>
                                    <label for="job_sector_id" class="block text-xs font-bold text-gray-700 mb-1">
                                        القطاع الاقتصادي
                                    </label>
                                    <select id="job_sector_id" name="job_sector_id"
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

                                {{-- وصف وطبيعة العمل --}}
                                <div>
                                    <label for="job_description" class="block text-xs font-bold text-gray-700 mb-1">
                                        وصف وطبيعة النشاط
                                    </label>
                                    <input type="text" id="job_description" name="job_description"
                                           value="{{ old('job_description', $economical->job_description) }}"
                                           placeholder="مثال: بيع مواد تموينية ومشروبات..."
                                           class="w-full text-xs font-semibold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                    @error('job_description') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Card 2: التراخيص ومستوى السلامة والخطورة --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <x-heroicon-s-shield-check class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-gray-800">التراخيص والسلامة العامة</h2>
                                    <p class="text-xs text-gray-400">تعديل الوضع القانوني للترخيص ومستوى الخطورة</p>
                                </div>
                            </div>
                        </div>

                        {{-- Groups Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            
                            {{-- حالة الترخيص --}}
                            <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3">
                                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                    <div class="p-1.5 bg-emerald-100 text-emerald-700 rounded-lg">
                                        <x-heroicon-s-document-check class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">الترخيص التنظيمي</span>
                                </div>

                                <div>
                                    <label for="isLicensed" class="block text-xs font-bold text-gray-700 mb-1.5">
                                        حالة الترخيص
                                    </label>
                                    <select id="isLicensed" name="isLicensed"
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
                            </div>

                            {{-- مستوى الخطورة والسلامة --}}
                            <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100 space-y-3">
                                <div class="flex items-center gap-2 pb-2.5 border-b border-gray-200/60">
                                    <div class="p-1.5 bg-amber-100 text-amber-700 rounded-lg">
                                        <x-heroicon-s-exclamation-triangle class="w-4 h-4" />
                                    </div>
                                    <span class="text-xs font-bold text-gray-700">مستوى الخطورة والسلامة</span>
                                </div>

                                <div>
                                    <label for="isDanger" class="block text-xs font-bold text-gray-700 mb-1.5">
                                        تصنيف مستوى الخطورة
                                    </label>
                                    <select id="isDanger" name="isDanger"
                                            class="w-full text-xs font-bold bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                        <option value="0" {{ old('isDanger', $economical->isDanger) == 0 ? 'selected' : '' }}>
                                            عادية (غير خطرة)
                                        </option>
                                        <option value="1" {{ old('isDanger', $economical->isDanger) == 1 ? 'selected' : '' }}>
                                            ⚠️ مخاطرة خطرة (تتطلب شروط سلامة خاصة)
                                        </option>
                                    </select>
                                    @error('isDanger') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Card 3: بيانات التواصل والموقع --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <x-heroicon-s-phone class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-gray-800">بيانات التواصل والموقع</h2>
                                    <p class="text-xs text-gray-400">أرقام الهواتف، العناوين ومعلومات الاتصال</p>
                                </div>
                            </div>
                        </div>

                        {{-- Inputs Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            {{-- هاتف النشاط --}}
                            <div>
                                <label for="job_phone" class="block text-xs font-bold text-gray-700 mb-1">هاتف النشاط / المحل</label>
                                <input type="text" id="job_phone" name="job_phone"
                                       value="{{ old('job_phone', $economical->job_phone) }}"
                                       placeholder="مثال: 08288XXXX"
                                       class="w-full text-xs font-bold bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('job_phone') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- رقم الجوال --}}
                            <div>
                                <label for="job_mobile" class="block text-xs font-bold text-gray-700 mb-1">رقم الجوال</label>
                                <input type="text" id="job_mobile" name="job_mobile"
                                       value="{{ old('job_mobile', $economical->job_mobile) }}"
                                       placeholder="مثال: 0599XXXXXX"
                                       class="w-full text-xs font-bold bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('job_mobile') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- واتساب --}}
                            <div>
                                <label for="whatsapp" class="block text-xs font-bold text-gray-700 mb-1">رقم الواتساب</label>
                                <input type="text" id="whatsapp" name="whatsapp"
                                       value="{{ old('whatsapp', $economical->whatsapp) }}"
                                       placeholder="مثال: 0599XXXXXX"
                                       class="w-full text-xs font-bold bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('whatsapp') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- البريد الإلكتروني --}}
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', $economical->email) }}"
                                       placeholder="example@domain.com"
                                       class="w-full text-xs font-bold bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all" dir="ltr">
                                @error('email') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- العنوان أو وصف الموقع --}}
                            <div class="md:col-span-2">
                                <label for="job_location" class="block text-xs font-bold text-gray-700 mb-1">العنوان أو وصف الموقع التفصيلي</label>
                                <input type="text" id="job_location" name="job_location"
                                       value="{{ old('job_location', $economical->job_location) }}"
                                       placeholder="مثال: بجوار مسجد النور، شارع صلاح الدين..."
                                       class="w-full text-xs font-semibold bg-gray-50/50 border border-gray-200 rounded-xl px-3 py-2.5 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all">
                                @error('job_location') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Card 4: الملاحظات العامة --}}
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                                    <x-heroicon-s-chat-bubble-left-ellipsis class="w-5 h-5" />
                                </div>
                                <div>
                                    <h2 class="text-base font-extrabold text-gray-800">ملاحظات إضافية</h2>
                                    <p class="text-xs text-gray-400">أي ملاحظات إدارية أو تنظيمية خاصة بالنشاط</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <textarea id="notes" name="notes" rows="3"
                                      placeholder="اكتب أي ملاحظات أو تفاصيل إضافية هنا..."
                                      class="w-full text-xs bg-gray-50/50 border border-gray-200 rounded-xl p-3 text-gray-800 focus:border-orange focus:ring-1 focus:ring-orange/20 transition-all leading-relaxed">{{ old('notes', $economical->notes) }}</textarea>
                            @error('notes') <span class="text-red-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
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
                        </div>
                    </div>

                    {{-- Context Card: Building Connection --}}
                    @if($economical->building)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <div class="p-1.5 bg-blue-100 text-blue-600 rounded-lg">
                                <x-heroicon-s-building-office class="w-4 h-4" />
                            </div>
                            <h3 class="text-xs font-extrabold text-gray-800">المبنى المرتبط</h3>
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
                    @php
                        $firstOwner = $economical->owners->first();
                    @endphp
                    @if($firstOwner || $economical->name)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
                        <div class="flex items-center gap-2 pb-3 border-b border-gray-100">
                            <div class="p-1.5 bg-teal-100 text-teal-600 rounded-lg">
                                <x-heroicon-s-user class="w-4 h-4" />
                            </div>
                            <h3 class="text-xs font-extrabold text-gray-800">صاحب النشاط المسجل</h3>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">الاسم:</span>
                                <span class="font-bold text-gray-800">
                                    {{ $firstOwner ? $firstOwner->full_name : ($economical->name ?: '—') }}
                                </span>
                            </div>
                            @if($firstOwner && $firstOwner->id_card)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">رقم الهوية:</span>
                                <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $firstOwner->id_card }}</span>
                            </div>
                            @elseif($economical->id_card)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">رقم الهوية:</span>
                                <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $economical->id_card }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

            </div>

            {{-- Sticky Action Bar at Bottom (مثل صفحة تعديل المباني تماماً) --}}
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
