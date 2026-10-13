<x-crafts-layout>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">الحرف</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة الحرف وسجلات الحرفيين</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
            <form action="{{ route('crafts.crafts.index') }}" method="GET" id="craft-filter-form">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">اسم المحل/العمل</label>
                        <input type="text" name="business_name" value="{{ request('business_name') }}" placeholder="ابحث بالاسم..."
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم الهوية</label>
                        <input type="text" name="id_number" value="{{ request('id_number') }}" placeholder="رقم الهوية..."
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم الجوال</label>
                        <input type="text" name="mobile" value="{{ request('mobile') }}" placeholder="رقم الجوال..."
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">الحالة</label>
                        <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                onchange="document.getElementById('craft-filter-form').submit()">
                            <option value="">الكل</option>
                            @foreach($statuses as $st)
                                <option value="{{ $st->id }}" @selected(request('status') == $st->id)>{{ $st->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full bg-navy hover:bg-navy-dark text-white font-semibold py-2.5 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                            بحث واستعلام
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Active Filters Tags --}}
        @if(request()->anyFilled(['business_name', 'id_number', 'mobile', 'status']))
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-gray-500 font-medium">فلاتر نشطة:</span>
            @if(request('business_name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الاسم: {{ request('business_name') }}</span>
            @endif
            @if(request('id_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الهوية: {{ request('id_number') }}</span>
            @endif
            @if(request('status'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الحالة: {{ $statuses->firstWhere('id', request('status'))->description ?? request('status') }}</span>
            @endif
        </div>
        @endif

        {{-- Data Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 font-semibold text-xs border-b border-gray-100">
                            <th class="px-5 py-3.5 whitespace-nowrap">الرقم</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">الاسم / العمل</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">رقم الهوية</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">رقم الجوال</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">الحالة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($crafts as $craft)
                        <tr class="hover:bg-blue-50/20 transition-colors group">
                            <td class="px-5 py-4 whitespace-nowrap font-semibold text-gray-700">
                                {{ $craft->id }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-bold text-navy-dark leading-snug">{{ $craft->business_name ?? '—' }}</div>
                                @if($craft->customer)
                                <a href="{{ route('customers.show', $craft->customer->id) }}" class="text-[11px] text-blue-600 hover:underline mt-1 block">
                                    <x-heroicon-o-user class="w-3 h-3 inline-block align-middle" /> 
                                    العميل: {{ $craft->customer->name }}
                                </a>
                                @endif
                                @if($craft->address)
                                <div class="text-[10px] text-gray-400 mt-1 truncate max-w-xs" title="{{ $craft->address }}">{{ $craft->address }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(filled($craft->id_number))
                                <span class="text-[11px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded font-medium">{{ $craft->id_number }}</span>
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                {{ $craft->mobile ?? '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($craft->statusRecord)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-indigo-50 text-indigo-700">
                                    {{ $craft->statusRecord->description }}
                                </span>
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="#"
                                       class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                       title="عرض التفاصيل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                <x-heroicon-o-inbox class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                                <p>لا توجد سجلات تطابق بحثك</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($crafts->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $crafts->links() }}
            </div>
            @endif
        </div>
        
    </div>
</x-crafts-layout>
