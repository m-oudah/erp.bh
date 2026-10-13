<x-customers-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('customers.index', ['tab' => 'customers']) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        ملف العميل: {{ $customer->name ?? '-' }}
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1.5"><x-heroicon-o-identification class="w-4 h-4 text-gray-400"/> رقم الهوية: <span class="font-mono">{{ $customer->id_no ?? '-' }}</span></span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @can('bhm.subscriptions.edit')
                <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    تعديل بيانات العميل
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2">بيانات التواصل الأساسية</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 py-2 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <x-heroicon-s-phone class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5 font-medium">رقم الجوال</div>
                            <div class="text-sm font-semibold text-gray-800 font-mono" dir="ltr">{{ $customer->mobile ?? 'غير متوفر' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-2 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <x-heroicon-s-phone-arrow-down-left class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5 font-medium">الهاتف الثابت</div>
                            <div class="text-sm font-semibold text-gray-800 font-mono" dir="ltr">{{ $customer->telephone ?? 'غير متوفر' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-2 border-b border-gray-50">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <x-heroicon-s-envelope class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5 font-medium">البريد الإلكتروني</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $customer->email ?? 'غير متوفر' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                            <x-heroicon-s-map-pin class="w-5 h-5" />
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-0.5 font-medium">العنوان التفصيلي</div>
                            <div class="text-sm font-semibold text-gray-800">{{ $customer->address ?? 'غير متوفر' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes or Info -->
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-center items-center text-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm border border-gray-100">
                    <x-heroicon-s-information-circle class="w-8 h-8 text-gray-400" />
                </div>
                <h3 class="text-lg font-bold text-gray-700 mb-2">سجل العميل العام</h3>
                <p class="text-sm text-gray-500">
                    هذا السجل يمثل قاعدة البيانات المركزية للعملاء، يمكن ربطه لاحقاً في باقي أنظمة البلدية كالإيرادات والضرائب لتكوين ملف مالي موحد للعميل.
                </p>
                <div class="mt-4 text-xs text-gray-400">
                    تاريخ إنشاء السجل: {{ $customer->created_at ? $customer->created_at->format('Y-m-d') : 'غير محدد' }}
                </div>
            </div>
            
        </div>

        @if($customer->crafts && $customer->crafts->count() > 0)
        <!-- Crafts Linked to this Customer -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-6">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <x-heroicon-s-briefcase class="w-5 h-5 text-orange" />
                    بطاقة معلومات الحرف المرتبطة
                </h3>
                <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">{{ $customer->crafts->count() }} حرفة</span>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($customer->crafts as $craft)
                    <div class="border border-gray-100 rounded-2xl p-4 hover:shadow-md hover:border-orange/30 transition-all bg-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-1 h-full bg-orange opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-gray-800 text-base leading-tight">{{ $craft->business_name ?? 'بدون اسم' }}</h4>
                            @if($craft->statusRecord)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700">{{ $craft->statusRecord->description }}</span>
                            @endif
                        </div>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            @if($craft->craftType)
                            <div class="flex items-center gap-2 text-xs">
                                <x-heroicon-o-tag class="w-3.5 h-3.5 text-gray-400" />
                                <span class="font-medium text-gray-700">{{ $craft->craftType->name }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2 text-xs">
                                <x-heroicon-o-map-pin class="w-3.5 h-3.5 text-gray-400" />
                                <span class="truncate" title="{{ $craft->address }}">{{ $craft->address ?? 'لا يوجد عنوان' }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <x-heroicon-o-phone class="w-3.5 h-3.5 text-gray-400" />
                                <span dir="ltr">{{ $craft->mobile ?? 'لا يوجد هاتف' }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-t border-gray-50 flex justify-end">
                            <a href="{{ route('crafts.economical.show', $craft->id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1 group-hover:gap-2 transition-all">
                                عرض التفاصيل
                                <x-heroicon-m-arrow-left class="w-3 h-3" />
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>
</x-customers-layout>
