<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange-300 text-[10px] font-bold mb-0.5 tracking-wider">DAMS • ENGINEERING</div>
            <h1 class="font-bold text-xl tracking-wide leading-none text-white">منظومة حصر الأضرار وتقييم الاحتياجات</h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">الدائرة الهندسية والتنظيم • بلدية بيت حانون</p>
        </div>
    </x-slot>

    @include('damage.partials.header')

    {{-- 1. Main Action & Status Header --}}
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="font-mono text-xs font-black bg-orange-50 text-orange-700 px-3 py-1 rounded-xl border border-orange-200">
                        {{ $record->record_number }}
                    </span>

                    {{-- Sector Badge --}}
                    @php
                        $sectorMap = [
                            'real_estate' => ['name' => 'المباني والعقارات', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                            'infrastructure' => ['name' => 'البنية التحتية والشبكات', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                            'public_facilities' => ['name' => 'المرافق العامة والحيوية', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                            'economic_crafts' => ['name' => 'القطاع الاقتصادي والحرفي', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                            'municipal_assets' => ['name' => 'ممتلكات وأصول البلدية', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                        ];
                        $sec = $sectorMap[$record->sector] ?? ['name' => $record->sector, 'class' => 'bg-slate-100 text-slate-700 border-slate-200'];
                    @endphp
                    <span class="text-xs font-bold px-3 py-1 rounded-xl border {{ $sec['class'] }}">
                        {{ $sec['name'] }}
                    </span>

                    {{-- Damage Level Badge --}}
                    @if($record->damage_level === 'total_destruction')
                        <span class="px-3 py-1 rounded-xl text-xs font-black bg-rose-50 text-rose-700 border border-rose-200">
                            مهدم كلياً (تدمير شامل)
                        </span>
                    @elseif($record->damage_level === 'severe_partial')
                        <span class="px-3 py-1 rounded-xl text-xs font-black bg-orange-50 text-orange-700 border border-orange-200">
                            ضرر جزئي بليغ
                        </span>
                    @elseif($record->damage_level === 'moderate_partial')
                        <span class="px-3 py-1 rounded-xl text-xs font-black bg-amber-50 text-amber-700 border border-amber-200">
                            ضرر جزئي متوسط
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-xl text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ضرر جزئي طفيف
                        </span>
                    @endif

                    {{-- Workflow Badge --}}
                    @if($record->workflow_status === 'final_approved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            معتمد نهائياً
                        </span>
                    @elseif($record->workflow_status === 'engineering_review')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            قيد المراجعة الهندسية
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            مسودة أولية
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl font-black text-slate-900">{{ $record->name }}</h1>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    تاريخ الحصر الأولي: {{ $record->initial_assessment_date ?: $record->created_at->format('Y-m-d') }}
                    @if($record->is_baseline_auto)
                        • <span class="text-amber-600 font-bold">إسناد تأسيسي تلقائي (Baseline)</span>
                    @endif
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Print Certificate --}}
                <a href="{{ route('damage.records.certificate', $record->id) }}" target="_blank"
                   class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                    <x-heroicon-s-qr-code class="w-4 h-4 text-orange-400" />
                    <span>إفادة حصر الأضرار الإلكترونية (QR)</span>
                </a>

                {{-- Recalculate --}}
                <form method="POST" action="{{ route('damage.records.recalculate', $record->id) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5" title="إعادة احتساب التقدير المالي للكميات">
                        <x-heroicon-s-calculator class="w-4 h-4" />
                        <span>إعادة التسعير</span>
                    </button>
                </form>

                {{-- Edit --}}
                <a href="{{ route('damage.records.edit', $record->id) }}" 
                   class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <x-heroicon-s-pencil-square class="w-4 h-4" />
                    <span>تعديل السجل</span>
                </a>

                {{-- Workflow Actions Dropdown/Buttons --}}
                @if($record->workflow_status === 'draft')
                    <form method="POST" action="{{ route('damage.records.workflow', $record->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="workflow_status" value="engineering_review">
                        <button type="submit" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                            <x-heroicon-s-arrow-path class="w-4 h-4" />
                            <span>رفع للمراجعة الهندسية</span>
                        </button>
                    </form>
                @elseif($record->workflow_status === 'engineering_review')
                    <form method="POST" action="{{ route('damage.records.workflow', $record->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="workflow_status" value="final_approved">
                        <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5">
                            <x-heroicon-s-check-badge class="w-4 h-4" />
                            <span>اعتماد نهائي للسجل</span>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('damage.records.workflow', $record->id) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من إعادة السجل للمسودة؟');">
                        @csrf
                        <input type="hidden" name="workflow_status" value="draft">
                        <button type="submit" class="px-3 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1">
                            <x-heroicon-s-arrow-uturn-left class="w-4 h-4" />
                            <span>إلغاء الاعتماد</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- 2. Grid Layout: Left Column (Pricing Engine) / Right Column (Technical & Cadastral) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Column 1: Financial & Engineering Estimation Engine --}}
        <div class="space-y-6">
            {{-- Cost Summary Card --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-orange-500/10 rounded-full blur-2xl"></div>

                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400">التقدير المالي الهندسي للتعافي</span>
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-orange-500 text-white">
                        محسوب آلياً
                    </span>
                </div>

                <div class="mb-4">
                    <div class="text-3xl font-black text-orange-400">
                        ${{ number_format($record->estimated_cost_usd, 2) }}
                    </div>
                    <div class="text-sm font-bold text-slate-300 mt-1">
                        ما يعادل: ₪{{ number_format($record->estimated_cost_ils, 2) }} شيكل
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-700/80 space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>المعيار الهندسي المطبق:</span>
                        <span class="font-bold text-white">{{ $record->pricingCriteria?->name_ar ?: 'معيار تقديري افتراضي' }}</span>
                    </div>
                    @if($record->pricingCriteria)
                        <div class="flex items-center justify-between text-slate-300">
                            <span>سعر الوحدة المعياري:</span>
                            <span class="font-bold text-white">${{ number_format($record->pricingCriteria->unit_price_usd, 2) }} / {{ $record->pricingCriteria->unit_type }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-slate-300">
                        <span>معامل نسبة الضرر:</span>
                        <span class="font-bold text-orange-400">
                            {{ $record->damage_level === 'total_destruction' ? '100% (1.0)' : ($record->damage_level === 'severe_partial' ? '65% (0.65)' : ($record->damage_level === 'moderate_partial' ? '35% (0.35)' : '15% (0.15)')) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Applied Pricing Breakdown details --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs">
                <h3 class="text-sm font-black text-slate-900 mb-3 flex items-center gap-2">
                    <x-heroicon-s-calculator class="w-5 h-5 text-orange-500" />
                    <span>تفصيل احتساب الكميات والتسعير</span>
                </h3>

                @if($record->sector === 'infrastructure')
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">طول الطريق / الشارع:</span>
                            <span class="font-bold text-slate-900">{{ number_format($record->length_meters, 1) }} متر طولي</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">عرض الطريق:</span>
                            <span class="font-bold text-slate-900">{{ number_format($record->width_meters, 1) }} متر</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">مساحة التعبيد المتضررة:</span>
                            <span class="font-bold text-slate-900">{{ number_format($record->area_sqm, 1) }} م²</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">نوع الطريق:</span>
                            <span class="font-bold text-slate-900">{{ $record->road_type === 'interlock' ? 'إنترلوك' : 'إسفلت قياسي' }}</span>
                        </div>
                    </div>
                @else
                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">المساحة الإجمالية للمسقط:</span>
                            <span class="font-bold text-slate-900">{{ number_format($record->area_sqm, 1) }} م²</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">عدد الطوابق:</span>
                            <span class="font-bold text-slate-900">{{ $record->floors_count }} طوابق</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">إجمالي المساحة المبنية:</span>
                            <span class="font-bold text-slate-900">{{ number_format($record->area_sqm * ($record->floors_count ?: 1), 1) }} م²</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">عدد الوحدات:</span>
                            <span class="font-bold text-slate-900">{{ $record->units_count }} وحدة</span>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-slate-100">
                            <span class="text-slate-500">طبيعة الهيكل الإنشائي:</span>
                            <span class="font-bold text-slate-900">
                                {{ $record->structure_type === 'asbestos' ? 'سقف أسبست / زينكو' : ($record->structure_type === 'steel_hanger' ? 'هنغار حديدي' : 'باطون مسلح') }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            {{-- QR Code Verification Card --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs text-center">
                <div class="mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('damage.verify-certificate', $record->certificate_uuid)) }}" 
                         alt="QR Code" class="w-32 h-32 mx-auto rounded-xl border border-slate-200 p-2 shadow-xs bg-white">
                </div>
                <div class="text-xs font-bold text-slate-800">رمز التحقق الإلكتروني للإفادة</div>
                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ substr($record->certificate_uuid, 0, 16) }}...</div>
                <a href="{{ route('damage.verify-certificate', $record->certificate_uuid) }}" target="_blank"
                   class="inline-block mt-3 text-xs font-bold text-orange-600 hover:underline">
                    معاينة صفحة التحقق العامة &larr;
                </a>
            </div>
        </div>

        {{-- Column 2 & 3: Detailed Data, Cadastral, Before & After, Audit Trail --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Cadastral & Ownership Card --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-black text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-building-office class="w-5 h-5 text-orange-500" />
                    <span>البيانات التنظيمية والعقارية والمالك</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                        <div class="font-bold text-slate-700 text-sm mb-2">الموقع التنظيمي</div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">المنطقة / الحي:</span>
                            <span class="font-bold text-slate-900">{{ $record->zone ? $record->zone->zone_name : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">الشارع:</span>
                            <span class="font-bold text-slate-900">{{ $record->street ? $record->street->street_name : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">رقم الحوض:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $record->block_number ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">رقم القطعة:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $record->parcel_number ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">رقم المبنى:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $record->building_number ?: '-' }}</span>
                        </div>
                    </div>

                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2">
                        <div class="font-bold text-slate-700 text-sm mb-2">بيانات المالك / المستفيد</div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">اسم المالك:</span>
                            <span class="font-bold text-slate-900">{{ $record->citizen_name ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">رقم الهوية:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $record->citizen_national_id ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">رقم الهاتف:</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $record->citizen_phone ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">الحالة التشغيلية:</span>
                            <span class="font-bold text-rose-600">
                                {{ $record->operational_status === 'destroyed' ? 'مدمر كلياً وغير قابل للاستخدام' : ($record->operational_status === 'out_of_service' ? 'خارج الخدمة' : 'قيد التشغيل الجزئي') }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($record->notes)
                    <div class="mt-4 p-3.5 bg-amber-50/50 rounded-2xl border border-amber-200/70 text-xs">
                        <span class="font-bold text-amber-900">ملاحظات الحصر والتأسيس:</span>
                        <p class="text-amber-800 mt-1 leading-relaxed">{{ $record->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Before & After Comparison Card --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                        <x-heroicon-s-photo class="w-5 h-5 text-orange-500" />
                        <span>التوثيق والمقارنة (قبل الحرب وبعد التدمير)</span>
                    </h3>
                    <span class="text-xs font-bold text-slate-400">التكامل مع الأرشيف المركزي ورخص البناء</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Before: Cadastral & Old License Data --}}
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-200">
                            <span class="text-xs font-black text-slate-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> بيانات وسجلات ما قبل الحرب
                            </span>
                            <span class="text-[10px] font-bold text-slate-400">نظام التنظيم والأبنية</span>
                        </div>

                        <div class="space-y-2 text-xs text-slate-600">
                            <div><span class="font-bold">رقم الملف التنظيمي:</span> {{ $record->file_number ?: 'غير مسجل' }}</div>
                            <div><span class="font-bold">حالة البناء السابقة:</span> قائم ومستخدم</div>
                            <div><span class="font-bold">الاستخدام المرخص:</span> {{ $record->sector === 'real_estate' ? 'سكني' : ($record->sector === 'economic_crafts' ? 'تجاري وحرفي' : 'مرفق عام') }}</div>
                        </div>

                        <div class="mt-4 p-4 rounded-xl bg-white border border-slate-200 text-center text-slate-400 text-xs">
                            <x-heroicon-s-document-magnifying-glass class="w-8 h-8 mx-auto text-slate-300 mb-1" />
                            <span>المخطط الهندسي والرخصة التنظيمية القديمة مفهرسة بالأرشيف</span>
                        </div>
                    </div>

                    {{-- After: Damage Documentation & Current Status --}}
                    <div class="border border-rose-200 rounded-2xl p-4 bg-rose-50/20">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-rose-100">
                            <span class="text-xs font-black text-rose-700 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> واقع التدمير بعد العدوان
                            </span>
                            <span class="text-[10px] font-bold text-rose-600">توثيق الدائرة الهندسية</span>
                        </div>

                        <div class="space-y-2 text-xs text-slate-700">
                            <div><span class="font-bold">تصنيف الضرر:</span> {{ $record->damage_level === 'total_destruction' ? 'تدمير كلي شامل' : 'ضرر جزئي' }}</div>
                            <div><span class="font-bold">تاريخ التوثيق:</span> {{ $record->initial_assessment_date }}</div>
                            <div><span class="font-bold">الحاجة لتدخل عاجل:</span> {{ $record->urgent_intervention_needed ? 'نعم (أولوية قصوى)' : 'عادي' }}</div>
                        </div>

                        <div class="mt-4 p-4 rounded-xl bg-white border border-rose-200 text-center text-slate-500 text-xs">
                            <x-heroicon-s-camera class="w-8 h-8 mx-auto text-rose-300 mb-1" />
                            <span>يمكن للمهندس الميداني إرفاق صور الركام والتقييم التفصيلي</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Audit Trail Timeline --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-black text-slate-900 mb-4 flex items-center gap-2">
                    <x-heroicon-s-clock class="w-5 h-5 text-orange-500" />
                    <span>سجل التدقيق ودورة التعديلات والاعتمادات (Audit Trail)</span>
                </h3>

                <div class="space-y-3">
                    @forelse($record->auditLogs as $log)
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
                            <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                <x-heroicon-s-user class="w-4 h-4" />
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">{{ $log->user ? $log->user->name : 'النظام المركزي' }}</span>
                                    <span class="text-[11px] text-slate-400 font-mono">{{ $log->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <p class="text-slate-600 mt-1 font-medium">{{ $log->notes ?: $log->action }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 rounded-2xl bg-slate-50 text-xs text-slate-500 text-center">
                            تم التأسيس التلقائي لسجل الأضرار بحالة مهدم كلياً
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
