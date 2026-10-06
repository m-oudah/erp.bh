<x-buildings-layout>
    <div class="space-y-6">
        
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">المشتركون والعملاء</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة اشتراكات الخدمات (مياه/حرف) وسجلات العملاء العامين</p>
            </div>
        </div>

        <!-- Alpine Tabs for switching modes -->
        <div x-data="{ tab: '{{ $tab }}' }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-6">
            <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'subscriptions'; window.location.href='?tab=subscriptions'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'subscriptions', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'subscriptions'}" class="px-8 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-bolt class="w-5 h-5" />
                    الاشتراكات (مياه، خدمات، أخرى)
                </button>
                <button @click="tab = 'customers'; window.location.href='?tab=customers'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'customers', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'customers'}" class="px-8 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-users class="w-5 h-5" />
                    سجل العملاء والمواطنين
                </button>
            </div>
        </div>

        @if($tab == 'subscriptions')
        <!-- Filter Form - Subscriptions -->
        <div class="bg-gray-50/50 rounded-2xl p-4 md:p-5 border border-gray-100">
            <form action="{{ route('buildings.customers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" id="filter-form">
                <input type="hidden" name="tab" value="subscriptions">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">رقم الاشتراك / الهوية</label>
                    <input type="text" name="id_number" value="{{ request('id_number') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white" placeholder="أدخل رقم الهوية أو الاشتراك">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">اسم المشترك</label>
                    <input type="text" name="subscriber_name" value="{{ request('subscriber_name') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div class="flex items-end justify-end gap-2">
                    @if(request()->anyFilled(['id_number', 'subscriber_name']))
                    <a href="{{ route('buildings.customers.index', ['tab' => 'subscriptions']) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px]" title="مسح الفلاتر">
                        <x-heroicon-o-x-mark class="w-4 h-4" /> مسح
                    </a>
                    @endif
                    <button type="submit" class="bg-navy hover:bg-navy-dark text-white font-medium py-2 px-6 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px] flex-1">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table - Subscriptions -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="px-5 py-4">رقم الهوية / الاشتراك</th>
                            <th class="px-5 py-4">اسم المشترك</th>
                            <th class="px-5 py-4">حالة الدفع</th>
                            <th class="px-5 py-4">العقار المرتبط</th>
                            <th class="px-5 py-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data as $sub)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-5 py-3 font-mono text-gray-800 font-semibold">{{ $sub->id_number ?? '-' }}</td>
                            <td class="px-5 py-3 text-navy-dark font-medium">{{ $sub->subscriber_name ?? 'غير محدد' }}</td>
                            <td class="px-5 py-3">
                                @if($sub->status == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700">دائم</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600">مؤقت / أخرى</span>
                                @endif
                                <div class="text-[10px] text-gray-500 mt-1">متبقي: {{ number_format($sub->remaining ?? 0, 2) }} شيكل</div>
                            </td>
                            <td class="px-5 py-3">
                                @if($sub->building)
                                    <a href="{{ route('buildings.buildings.show', $sub->building_id) }}" class="text-blue-600 hover:underline text-xs font-bold block mb-1">
                                        مبنى #{{ $sub->building->building_number ?? $sub->building_id }}
                                    </a>
                                    <span class="text-[10px] text-gray-500">{{ $sub->building->zone->name ?? '-' }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">غير مربوط بمبنى</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('buildings.subscriptions.show', $sub->id) }}" class="inline-flex items-center justify-center text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors" title="التفاصيل">
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">لا يوجد اشتراكات مطابقة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($data->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $data->links() }}
            </div>
            @endif
        </div>

        @else
        
        <!-- Filter Form - Customers -->
        <div class="bg-gray-50/50 rounded-2xl p-4 md:p-5 border border-gray-100">
            <form action="{{ route('buildings.customers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="filter-form">
                <input type="hidden" name="tab" value="customers">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">رقم الهوية</label>
                    <input type="text" name="id_no" value="{{ request('id_no') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">اسم العميل</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">رقم الجوال</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div class="flex items-end justify-end gap-2">
                    @if(request()->anyFilled(['id_no', 'name', 'mobile']))
                    <a href="{{ route('buildings.customers.index', ['tab' => 'customers']) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px]" title="مسح الفلاتر">
                        <x-heroicon-o-x-mark class="w-4 h-4" /> مسح
                    </a>
                    @endif
                    <button type="submit" class="bg-navy hover:bg-navy-dark text-white font-medium py-2 px-6 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px] flex-1">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        بحث
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table - Customers -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-600 font-semibold border-b border-gray-100">
                            <th class="px-5 py-4">رقم الهوية</th>
                            <th class="px-5 py-4">الاسم الرباعي / الجهة</th>
                            <th class="px-5 py-4">أرقام التواصل</th>
                            <th class="px-5 py-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data as $cus)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-5 py-3 font-mono text-gray-800 font-semibold">{{ $cus->id_no ?? '-' }}</td>
                            <td class="px-5 py-3 text-navy-dark font-medium">{{ $cus->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600" dir="ltr">{{ $cus->mobile ?? $cus->telephone ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('buildings.customers.show', $cus->id) }}" class="inline-flex items-center justify-center text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors" title="عرض السجل">
                                    <x-heroicon-o-user class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-500">لا يوجد عملاء مطابقين للبحث.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($data->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $data->links() }}
            </div>
            @endif
        </div>

        @endif

    </div>
</x-buildings-layout>
