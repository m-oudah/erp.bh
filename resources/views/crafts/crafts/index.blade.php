<x-crafts-layout>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">الحرف</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة الحرف وسجلات الحرفيين</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-2">
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        إجمالي: {{ $crafts->total() }} حرفة
                    </span>
                </div>
            </div>
        </div>

        {{-- Search & Filter Card --}}
        <div x-data="{ showFilters: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50/70 transition-colors"
                 :class="showFilters ? 'border-b border-gray-100' : ''">
                <div class="flex items-center gap-3">
                    <div @click="showFilters = !showFilters"
                         class="flex items-center gap-2 cursor-pointer select-none group py-1"
                         role="button"
                         tabindex="0"
                         @keydown.enter.prevent="showFilters = !showFilters"
                         @keydown.space.prevent="showFilters = !showFilters">
                        <x-heroicon-o-funnel class="w-4 h-4 text-black group-hover:text-orange transition-colors" />
                        <span class="text-sm font-bold text-black group-hover:text-orange transition-colors">خيارات البحث والتصفية</span>
                    </div>
                    @if(request()->anyFilled(['business_name', 'id_number', 'mobile', 'status']))
                    <a href="{{ route('crafts.crafts.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                        <x-heroicon-o-x-mark class="w-3.5 h-3.5" /> مسح الفلاتر
                    </a>
                    @endif
                </div>

                {{-- زر عرض/إخفاء الخيارات على اليسار تماما --}}
                <button type="button"
                        @click="showFilters = !showFilters"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 border shadow-sm cursor-pointer"
                        :class="showFilters ? 'bg-orange/10 text-orange-dark border-orange/30' : 'bg-white text-gray-700 hover:bg-gray-100 border-gray-200'">
                    <x-heroicon-o-adjustments-horizontal class="w-4 h-4" />
                    <span x-text="showFilters ? 'إخفاء الخيارات' : 'عرض الخيارات'">عرض الخيارات</span>
                    <span class="inline-flex transition-transform duration-200" :class="showFilters ? 'rotate-180' : ''">
                        <x-heroicon-o-chevron-down class="w-3.5 h-3.5" />
                    </span>
                </button>
            </div>

            <div x-show="showFilters"
                 x-cloak
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2">
                <form action="{{ route('crafts.crafts.index') }}" method="GET" class="p-5" id="craft-filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">اسم المحل/العمل</label>
                            <input type="text" name="business_name" value="{{ request('business_name') }}" placeholder="ابحث بالاسم..."
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">رقم الهوية</label>
                            <input type="text" name="id_number" value="{{ request('id_number') }}" placeholder="رقم الهوية..."
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">رقم الجوال</label>
                            <input type="text" name="mobile" value="{{ request('mobile') }}" placeholder="رقم الجوال..."
                                   class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-black mb-1.5">الحالة</label>
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
        </div>

        {{-- Active Filters Tags --}}
        @if(request()->anyFilled(['business_name', 'id_number', 'mobile', 'status']))
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-black font-bold">فلاتر نشطة:</span>
            @if(request('business_name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الاسم: {{ request('business_name') }}</span>
            @endif
            @if(request('id_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الهوية: {{ request('id_number') }}</span>
            @endif
            @if(request('mobile'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الجوال: {{ request('mobile') }}</span>
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
