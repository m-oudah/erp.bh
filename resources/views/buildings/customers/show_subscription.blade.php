<x-buildings-layout>
    <div class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('buildings.customers.index', ['tab' => 'subscriptions']) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        اشتراك رقم: {{ $subscription->id_number ?? '-' }}
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1.5"><x-heroicon-o-user class="w-4 h-4 text-gray-400"/> المشترك: {{ $subscription->subscriber_name ?? '-' }}</span>
                        @if($subscription->building)
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('buildings.buildings.show', $subscription->building_id) }}" class="flex items-center gap-1.5 text-blue-600 hover:underline">
                            <x-heroicon-o-building-office class="w-4 h-4"/> مبنى رقم {{ $subscription->building->building_number ?? $subscription->building_id }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @can('bhm.subscriptions.edit')
                <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    تعديل البيانات
                </a>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Subscription Info -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2">بيانات الاشتراك</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">حالة الاشتراك:</span>
                        <span class="text-sm font-semibold {{ $subscription->status == 1 ? 'text-emerald-600' : 'text-gray-600' }}">
                            {{ $subscription->status == 1 ? 'دائم' : 'أخرى' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">سنة الاشتراك:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $subscription->year ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">المالك الأصلي للوحدة:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $subscription->owner->full_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-sm text-gray-500">ملاحظات:</span>
                        <span class="text-sm text-gray-700 whitespace-pre-wrap">{{ $subscription->notes ?: 'لا يوجد ملاحظات' }}</span>
                    </div>
                </div>
            </div>

            <!-- Financial Info -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2 flex items-center gap-2">
                    <x-heroicon-s-banknotes class="w-5 h-5 text-emerald-500" /> الذمم المالية للاشتراك
                </h3>
                <div class="space-y-6">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500 font-medium">المبلغ المطلوب (شيكل):</span>
                            <span class="text-gray-800 font-bold">{{ number_format($subscription->amount ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-500 font-medium">المبلغ المدفوع (شيكل):</span>
                            <span class="text-blue-600 font-bold">{{ number_format($subscription->paid ?? 0, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                            @php
                                $percent = $subscription->amount > 0 ? min(100, ($subscription->paid / $subscription->amount) * 100) : 0;
                            @endphp
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1 mt-4">
                            <span class="text-gray-500 font-medium">المبلغ المتبقي (شيكل):</span>
                            <span class="text-red-600 font-bold">{{ number_format($subscription->remaining ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Linked Units -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm md:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mb-5 border-b border-gray-100 pb-2">الوحدات المستفيدة من هذا الاشتراك</h3>
                @if($subscription->units->count() > 0)
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="w-full text-right text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3">رقم الوحدة</th>
                                <th class="px-4 py-3">نوع الوحدة</th>
                                <th class="px-4 py-3">النشاط / الوصف</th>
                                <th class="px-4 py-3">الطابق</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($subscription->units as $unit)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $unit->unit_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $unit->unit_type_label }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $unit->unit_name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $unit->floor->floor_name ?? 'غير محدد' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-6 bg-gray-50 rounded-xl text-sm text-gray-500">
                    لم يتم ربط أي وحدات أو شقق بهذا الاشتراك.
                </div>
                @endif
            </div>

        </div>

    </div>
</x-buildings-layout>
