<x-customers-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.citizens.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2.5 rounded-2xl transition-colors shadow-2xs" title="الرجوع لسجل المواطنين">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-teal-50 text-teal-700 border border-teal-200">ملف المواطن الموحد</span>
                        <span class="text-xs text-gray-400 font-mono">#{{ $customer->id }}</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 mt-1 flex items-center gap-2">
                        <span>{{ $customer->name ?? '-' }}</span>
                    </h2>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 mt-1">
                        <span class="flex items-center gap-1.5 font-bold text-gray-700">
                            <x-heroicon-o-identification class="w-4 h-4 text-teal-600"/>
                            رقم الهوية:
                            <span class="font-mono text-gray-900 font-extrabold text-sm">{{ $customer->id_no ?? '-' }}</span>
                        </span>
                        @if($customer->created_at)
                        <span class="flex items-center gap-1 text-gray-400">
                            <x-heroicon-o-calendar class="w-3.5 h-3.5"/>
                            تاريخ التسجيل: {{ $customer->created_at->format('Y-m-d') }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('customers.citizens.print', $customer->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200/90 font-bold py-2.5 px-3.5 rounded-xl shadow-2xs transition-colors text-xs cursor-pointer" title="طباعة كشف ملف وسجلات المواطن في نافذة مستقلة">
                    <x-heroicon-o-printer class="w-4 h-4 text-teal-700" />
                    <span>طباعة الملف</span>
                </a>
                @if(auth()->user()?->can('Update-Citizen') || auth()->user()?->can('Update-Client') || auth()->user()?->can('bhm.subscriptions.edit'))
                <a href="{{ route('customers.citizens.edit', $customer->id) }}" class="inline-flex items-center gap-1.5 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-4 rounded-xl shadow-sm transition-colors text-xs">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    <span>تعديل بيانات المواطن</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between gap-2 shadow-xs">
            <div class="flex items-center gap-2">
                <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-xs font-bold">✕</button>
        </div>
        @endif

        {{-- Basic Citizen Info Card --}}
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="text-gray-400 font-bold mb-1">رقم الهوية</div>
                    <div class="font-mono text-gray-900 font-black text-sm">{{ $customer->id_no ?? '-' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100 sm:col-span-2">
                    <div class="text-gray-400 font-bold mb-1">الاسم الرباعي الكامل</div>
                    <div class="font-bold text-gray-900 text-sm truncate" title="{{ $customer->name }}">{{ $customer->name ?? '-' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="text-gray-400 font-bold mb-1">رقم الجوال</div>
                    <div class="font-mono font-bold text-gray-900 text-sm" dir="ltr">{{ $customer->mobile ?: 'غير مسجل' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="text-gray-400 font-bold mb-1">الهاتف الثابت</div>
                    <div class="font-mono font-bold text-gray-900 text-sm" dir="ltr">{{ $customer->telephone ?: '-' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="text-gray-400 font-bold mb-1">البريد الإلكتروني</div>
                    <div class="font-medium text-gray-800 truncate" title="{{ $customer->email }}">{{ $customer->email ?: '-' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100 sm:col-span-4 lg:col-span-4">
                    <div class="text-gray-400 font-bold mb-1">العنوان السكني</div>
                    <div class="font-bold text-gray-800 text-xs">{{ $customer->address ?: 'لا يوجد عنوان تفصيلي مسجل' }}</div>
                </div>

                <div class="p-3 bg-gray-50/70 rounded-xl border border-gray-100 sm:col-span-2 lg:col-span-2">
                    <div class="text-gray-400 font-bold mb-1">ملاحظات السجل</div>
                    <div class="font-medium text-gray-600 text-xs truncate" title="{{ $customer->notes }}">{{ $customer->notes ?: 'لا توجد ملاحظات إضافية' }}</div>
                </div>
            </div>
        </div>

        {{-- Section Title --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-2">
            <div>
                <h3 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <x-heroicon-s-squares-2x2 class="w-5 h-5 text-teal-700" />
                    <span>سجلات وأنشطة المواطن في موديولات النظام</span>
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    اضغط على أي بطاقة للانتقال مباشرة إلى صفحة النظام المخصصة لعرض سجلات هذا المواطن
                </p>
            </div>
        </div>

        {{-- MODULE CARDS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            
            {{-- CARD 1: BUILDINGS & PROPERTIES --}}
            <a href="{{ route('buildings.buildings.index', ['id_card' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-teal-100/90 shadow-2xs hover:shadow-lg hover:border-teal-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-teal-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-teal-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-building-office-2 class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ $buildings->count() > 0 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $buildings->count() }} {{ $buildings->count() === 1 ? 'عقار ومبنى' : 'عقارات ومباني' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-teal-700 transition-colors flex items-center gap-2">
                        <span>المباني والعقارات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-teal-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if($buildings->count() > 0)
                            المواطن مسجل كمالك في <strong>{{ $buildings->count() }}</strong> مبنى وعقار في بلدية بيت حانون
                            @if($buildings->first())
                                (مثل: مبنى رقم {{ $buildings->first()->building_number }})
                            @endif
                        @else
                            لا توجد مبانٍ أو عقارات مسجلة باسم المواطن حالياً في السجل العقاري.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-teal-700 transition-colors">نظام إدارة المباني والعقارات</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-teal-50 text-teal-800 group-hover:bg-teal-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 2: UNITS --}}
            @php
                $unitsLink = ($units->count() > 0 && $units->first()->building_id)
                    ? route('buildings.buildings.show', $units->first()->building_id)
                    : route('buildings.buildings.index', ['id_card' => $customer->id_no]);
            @endphp
            <a href="{{ $unitsLink }}" target="_blank" class="bg-white rounded-3xl p-6 border border-blue-100/90 shadow-2xs hover:shadow-lg hover:border-blue-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-blue-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-blue-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-home-modern class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ $units->count() > 0 ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $units->count() }} {{ $units->count() === 1 ? 'وحدة عقارية' : 'وحدات عقارية' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-blue-700 transition-colors flex items-center gap-2">
                        <span>الوحدات السكنية والتجارية</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-blue-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if($units->count() > 0)
                            يملك المواطن <strong>{{ $units->count() }}</strong> شقة أو وحدة سكنية أو مخزن/محل مسجل في المباني.
                        @else
                            لا توجد وحدات أو شقق مفرزة ومسجلة باسم المواطن بشكل مباشر.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-blue-700 transition-colors">الوحدات والشقق والمخازن</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-blue-50 text-blue-800 group-hover:bg-blue-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح العقار والوحدات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 3: CRAFTS & BUSINESSES --}}
            <a href="{{ route('crafts.economical.index', ['id_card' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-amber-100/90 shadow-2xs hover:shadow-lg hover:border-amber-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-amber-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-amber-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-briefcase class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ ($crafts->count() + $economicals->count()) > 0 ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $crafts->count() + $economicals->count() }} نشاط ورخصة
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-amber-700 transition-colors flex items-center gap-2">
                        <span>الحرف والصناعات والأنشطة</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-amber-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if(($crafts->count() + $economicals->count()) > 0)
                            مسجل باسم المواطن <strong>{{ $crafts->count() + $economicals->count() }}</strong> منشأة اقتصادية، حرفة أو مهنة تجارية.
                        @else
                            لا توجد رخص حرف أو منشآت اقتصادية مسجلة باسم المواطن حالياً.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-amber-700 transition-colors">نظام رخص المهن والحرف</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-amber-50 text-amber-800 group-hover:bg-amber-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 4: PUBLIC RELATIONS (قلم الجمهور) --}}
            <a href="{{ route('transactions.index', ['search' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-primary/25 shadow-2xs hover:shadow-lg hover:border-primary hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-primary"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-primary flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-document-text class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ $transactions->count() > 0 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $transactions->count() }} {{ $transactions->count() === 1 ? 'معاملة وشكوى' : 'معاملات وشكاوى' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-primary transition-colors flex items-center gap-2">
                        <span>معاملات قلم الجمهور</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-primary opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if($transactions->count() > 0)
                            إجمالي <strong>{{ $transactions->count() }}</strong> معاملة وطلب رسمي أو شكوى مسجلة في قلم الجمهور.
                        @else
                            لا توجد طلبات أو معاملات رسمية مسجلة في قلم الجمهور لهذا المواطن.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-primary transition-colors">قلم الجمهور والشكاوى والطلبات</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-teal-50 text-primary group-hover:bg-primary group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 5: BUILDING LICENSES --}}
            <a href="{{ route('buildings.licenses.index', ['id_card' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-indigo-100/90 shadow-2xs hover:shadow-lg hover:border-indigo-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-indigo-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-clipboard-document-check class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ $licenses->count() > 0 ? 'bg-indigo-50 text-indigo-800 border border-indigo-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $licenses->count() }} {{ $licenses->count() === 1 ? 'رخصة بناء' : 'رخص بناء' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-indigo-700 transition-colors flex items-center gap-2">
                        <span>رخص البناء والتنظيم</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if($licenses->count() > 0)
                            إجمالي <strong>{{ $licenses->count() }}</strong> طلب ورخصة بناء وتقارير هندسية معتمدة أو قيد الإجراء.
                        @else
                            لا توجد طلبات ترخيص بناء مسجلة باسم المواطن حالياً.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-indigo-700 transition-colors">قسم التنظيم ورخص الأبنية</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-800 group-hover:bg-indigo-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 6: MUNICIPAL SUBSCRIPTIONS & WATER --}}
            <a href="{{ route('customers.index', ['id_number' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-sky-100/90 shadow-2xs hover:shadow-lg hover:border-sky-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-sky-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-sky-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-sparkles class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black {{ $subscriptions->count() > 0 ? 'bg-sky-50 text-sky-800 border border-sky-200' : 'bg-gray-100 text-gray-500' }}">
                            {{ $subscriptions->count() }} {{ $subscriptions->count() === 1 ? 'اشتراك خدمة' : 'اشتراكات وخدمات' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-sky-700 transition-colors flex items-center gap-2">
                        <span>اشتراكات المياه والخدمات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-sky-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        @if($subscriptions->count() > 0)
                            إجمالي <strong>{{ $subscriptions->count() }}</strong> اشتراك مياه وخدمات بلدية مسجلة باسم المواطن.
                        @else
                            لا توجد اشتراكات مياه مباشرة مسجلة برقم هوية المواطن.
                        @endif
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-sky-700 transition-colors">نظام المشتركين وخدمات الجباية</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-sky-50 text-sky-800 group-hover:bg-sky-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>

            {{-- CARD 7: DAMAGE RECORDS --}}
            @if(isset($damageRecords) && $damageRecords->count() > 0)
            <a href="{{ route('damage.records.index', ['search' => $customer->id_no]) }}" target="_blank" class="bg-white rounded-3xl p-6 border border-rose-100/90 shadow-2xs hover:shadow-lg hover:border-rose-500 hover:-translate-y-1 transition-all flex flex-col justify-between group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2.5 h-full bg-rose-500"></div>
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-rose-700 group-hover:text-white transition-all shadow-xs">
                            <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                        </div>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-rose-50 text-rose-800 border border-rose-200">
                            {{ $damageRecords->count() }} {{ $damageRecords->count() === 1 ? 'سجل حصر أضرار' : 'سجلات حصر أضرار' }}
                        </span>
                    </div>

                    <h4 class="text-lg font-black text-gray-900 mb-1.5 group-hover:text-rose-700 transition-colors flex items-center gap-2">
                        <span>حصر الأضرار والاحتياجات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4 text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity" />
                    </h4>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        يوجد <strong>{{ $damageRecords->count() }}</strong> سجل حصر أضرار وتقييم مسجل باسم المواطن في منظومة حصر الأضرار.
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-400 group-hover:text-rose-700 transition-colors">منظومة حصر الأضرار</span>
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-rose-50 text-rose-800 group-hover:bg-rose-700 group-hover:text-white transition-all shadow-2xs">
                        <span>فتح السجلات</span>
                        <x-heroicon-m-arrow-top-right-on-square class="w-3.5 h-3.5" />
                    </span>
                </div>
            </a>
            @endif

        </div>

    </div>
</x-customers-layout>
