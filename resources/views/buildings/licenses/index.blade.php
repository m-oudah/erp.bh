<x-buildings-layout>
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">التراخيص والتقارير</h1>
                <p class="text-sm text-gray-500 mt-1">إدارة معاملات تراخيص البناء وتقارير الكشف التنظيمي</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-2">
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-lg">
                        إجمالي: {{ $licenses->total() }} معاملة
                    </span>
                </div>
                @can('bhm.license-forms.create')
                <a href="#" class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-5 rounded-xl shadow-md transition-all text-sm">
                    <x-heroicon-o-document-plus class="w-4 h-4" />
                    ترخيص جديد
                </a>
                @endcan
            </div>
        </div>

        {{-- Search & Filter Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 px-5 py-3 bg-gray-50/70 border-b border-gray-100">
                <x-heroicon-o-funnel class="w-4 h-4 text-gray-400" />
                <span class="text-sm font-semibold text-gray-600">خيارات البحث والتصفية</span>
                @if(request()->anyFilled(['building_id', 'building_number', 'name', 'id_card', 'phone', 'subject', 'status']))
                <a href="{{ route('buildings.licenses.index') }}" class="mr-auto text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors">
                    <x-heroicon-o-x-mark class="w-3.5 h-3.5" /> مسح الفلاتر
                </a>
                @endif
            </div>

            <form action="{{ route('buildings.licenses.index') }}" method="GET" class="p-5" id="license-filter-form">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم المبنى (محوسب)</label>
                        <input type="text" name="building_id" value="{{ request('building_id') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="مثال: 2501">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم مبنى الطلب</label>
                        <input type="text" name="building_number" value="{{ request('building_number') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم المبنى اليدوي">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">اسم مقدم الطلب</label>
                        <input type="text" name="name" value="{{ request('name') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="الاسم الأول أو الأخير">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم الهوية</label>
                        <input type="text" name="id_card" value="{{ request('id_card') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم الهوية">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">رقم الجوال</label>
                        <input type="text" name="phone" value="{{ request('phone') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="رقم الهاتف">
                    </div>

                    <div class="col-span-2 md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">موضوع الطلب</label>
                        <input type="text" name="subject" value="{{ request('subject') }}"
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                               placeholder="ابحث في موضوع المعاملة...">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">حالة المعاملة</label>
                        <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                                onchange="document.getElementById('license-filter-form').submit()">
                            <option value="">الكل</option>
                            <option value="1" @selected(request('status') === '1')>فعال (قيد العمل)</option>
                            <option value="2" @selected(request('status') === '2')>منجز</option>
                            <option value="3" @selected(request('status') === '3')>ملغي / مجمد</option>
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1 flex items-end">
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
        @if(request()->anyFilled(['building_id', 'building_number', 'name', 'id_card', 'phone', 'subject', 'status']))
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-gray-500 font-medium">فلاتر نشطة:</span>
            @if(request('building_id'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">رقم المبنى: {{ request('building_id') }}</span>
            @endif
            @if(request('building_number'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">رقم مبنى الطلب: {{ request('building_number') }}</span>
            @endif
            @if(request('name'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الاسم: {{ request('name') }}</span>
            @endif
            @if(request('id_card'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الهوية: {{ request('id_card') }}</span>
            @endif
            @if(request('phone'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الجوال: {{ request('phone') }}</span>
            @endif
            @if(request('subject'))
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الموضوع: "{{ Str::limit(request('subject'), 30) }}"</span>
            @endif
            @if(request('status'))
                @php $statusLabels = ['1' => 'فعال', '2' => 'منجز', '3' => 'ملغي']; @endphp
                <span class="bg-orange/10 text-orange-dark font-bold px-2.5 py-1 rounded-lg">الحالة: {{ $statusLabels[request('status')] ?? '-' }}</span>
            @endif
        </div>
        @endif

        {{-- Data Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 text-gray-500 font-semibold text-xs border-b border-gray-100">
                            <th class="px-5 py-3.5 whitespace-nowrap"># المعاملة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">مقدم الطلب</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">موضوع المعاملة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">المبنى / الموقع</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">الحالة</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($licenses as $license)
                        <tr class="hover:bg-blue-50/20 transition-colors group">

                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('buildings.licenses.show', $license->id) }}" class="flex items-center gap-2 font-bold text-navy-dark hover:text-orange transition-colors">
                                    <x-heroicon-s-document-text class="w-4 h-4 text-blue-400 shrink-0" />
                                    {{ $license->id }}
                                </a>
                                <div class="text-[10px] text-gray-400 mt-0.5 pr-6" dir="ltr">
                                    {{ $license->created_at ? $license->created_at->format('Y-m-d') : '-' }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-800 leading-snug">{{ $license->full_name ?: 'غير محدد' }}</div>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    @if($license->id_card)
                                    <span class="text-[10px] font-mono text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">{{ $license->id_card }}</span>
                                    @endif
                                    @if($license->phone)
                                    <span class="text-[10px] text-gray-400 flex items-center gap-1" dir="ltr">
                                        <x-heroicon-s-phone class="w-3 h-3" />{{ $license->phone }}
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="text-gray-700 max-w-xs leading-snug" title="{{ $license->subject }}">
                                    {{ $license->subject ? Str::limit($license->subject, 55) : '—' }}
                                </div>
                                @if($license->report)
                                <span class="inline-flex items-center gap-1 text-[10px] text-orange-dark bg-orange/10 px-1.5 py-0.5 rounded-md mt-1 font-bold">
                                    <x-heroicon-s-clipboard-document-check class="w-3 h-3" />
                                    يوجد تقرير كشف تنظيمي
                                </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($license->building)
                                    <a href="{{ route('buildings.buildings.show', $license->building_id) }}" class="flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-bold text-xs transition-colors">
                                        <x-heroicon-s-building-office class="w-3.5 h-3.5" />
                                        مبنى رقم {{ $license->building->building_number ?? $license->building_id }}
                                    </a>
                                    <div class="text-[10px] text-gray-500 mt-0.5 pr-5">
                                        {{ $license->building->zone->name ?? '' }}{{ $license->building->street ? ' · ' . $license->building->street->name : '' }}
                                    </div>
                                @elseif($license->building_number)
                                    <span class="text-xs text-gray-700 font-medium">مبنى {{ $license->building_number }}</span>
                                    <div class="text-[10px] text-gray-400">غير مربوط بالنظام</div>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($license->status == 1)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse block"></span>
                                        قيد العمل
                                    </span>
                                @elseif($license->status == 2)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-emerald-100 text-emerald-700">
                                        <x-heroicon-s-check-circle class="w-3 h-3" />
                                        منجز
                                    </span>
                                @elseif($license->status == 3)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-100 text-red-600">
                                        <x-heroicon-s-x-circle class="w-3 h-3" />
                                        ملغي / مجمد
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-gray-100 text-gray-500">غير محدد</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('buildings.licenses.show', $license->id) }}"
                                       class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                       title="عرض التفاصيل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @can('bhm.license-forms.edit')
                                    <a href="#"
                                       class="text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 p-2 rounded-lg transition-colors"
                                       title="تعديل">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                                        <x-heroicon-o-document-magnifying-glass class="w-8 h-8 text-gray-300" />
                                    </div>
                                    <p class="text-gray-600 font-semibold mb-1">لا توجد نتائج</p>
                                    <p class="text-sm text-gray-400">
                                        @if(request()->anyFilled(['building_id', 'building_number', 'name', 'id_card', 'phone', 'subject', 'status']))
                                            لم يتم العثور على معاملات مطابقة.
                                            <a href="{{ route('buildings.licenses.index') }}" class="text-orange hover:underline font-medium">عرض الكل</a>
                                        @else
                                            لا توجد معاملات تراخيص مضافة في النظام.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($licenses->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-2">
                <div class="text-sm text-gray-500">
                    عرض {{ $licenses->firstItem() }}–{{ $licenses->lastItem() }} من أصل {{ $licenses->total() }} معاملة
                </div>
                <div>{{ $licenses->links() }}</div>
            </div>
            @endif
        </div>

    </div>
</x-buildings-layout>
