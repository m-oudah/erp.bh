<x-customers-layout>
    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">سجل المواطنين</h2>
                <p class="text-gray-500 mt-1 text-sm">إدارة سجلات المواطنين والعملاء العامين والبحث في بياناتهم</p>
            </div>
            @if(auth()->user()->can('Create-Client') || auth()->user()->can('bhm.subscriptions.edit') || auth()->user()->can('Read-Subscriptions'))
            <a href="{{ route('customers.citizens.create') }}"
               class="inline-flex items-center gap-2 bg-orange hover:bg-orange-dark text-white font-bold py-2.5 px-5 rounded-xl shadow-sm transition-all text-sm shrink-0">
                <x-heroicon-o-user-plus class="w-4 h-4" />
                <span>إضافة مواطن جديد</span>
            </a>
            @endif
        </div>

        {{-- Flash Alerts --}}
        @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center justify-between gap-2 shadow-xs animate-fade-in">
            <div class="flex items-center gap-2">
                <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 text-xs font-bold">✕</button>
        </div>
        @endif

        @if (session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-xs font-bold flex items-center justify-between gap-2 shadow-xs animate-fade-in">
            <div class="flex items-center gap-2">
                <x-heroicon-s-x-circle class="w-5 h-5 text-red-600 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-950 text-xs font-bold">✕</button>
        </div>
        @endif

        <!-- Filter Form - Customers -->
        <div class="bg-gray-50/50 rounded-2xl p-4 md:p-5 border border-gray-100">
            <form action="{{ route('customers.citizens.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="filter-form">
                
                <div>
                    <label class="block text-xs font-bold text-black mb-1.5">رقم الهوية</label>
                    <input type="text" name="id_no" value="{{ request('id_no') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1.5">اسم العميل</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1.5">رقم الجوال</label>
                    <input type="text" name="mobile" value="{{ request('mobile') }}" class="w-full border-gray-200 rounded-xl text-sm focus:ring-orange focus:border-orange bg-white">
                </div>

                <div class="flex items-end justify-end gap-2">
                    @if(request()->anyFilled(['id_no', 'name', 'mobile']))
                    <a href="{{ route('customers.citizens.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-2 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2 h-[42px]" title="مسح الفلاتر">
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
                        <tr class="bg-gray-100 text-black font-bold border-b border-gray-200">
                            <th class="px-5 py-4 text-black font-bold">رقم الهوية</th>
                            <th class="px-5 py-4 text-black font-bold">الاسم الرباعي / الجهة</th>
                            <th class="px-5 py-4 text-black font-bold">أرقام التواصل</th>
                            <th class="px-5 py-4 text-center text-black font-bold">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data as $cus)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-5 py-3 font-mono text-gray-800 font-semibold">{{ $cus->id_no ?? '-' }}</td>
                            <td class="px-5 py-3 text-navy-dark font-medium">{{ $cus->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600" dir="ltr">{{ $cus->mobile ?? $cus->telephone ?? '-' }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('customers.show', $cus->id) }}" class="inline-flex items-center justify-center text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded-lg transition-colors" title="عرض السجل">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>
                                    @if(auth()->user()->can('Update-Client') || auth()->user()->can('bhm.subscriptions.edit') || auth()->user()->can('Read-Subscriptions'))
                                    <a href="{{ route('customers.citizens.edit', $cus->id) }}" class="inline-flex items-center justify-center text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 p-1.5 rounded-lg transition-colors" title="تعديل بيانات المواطن">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                    </a>
                                    @endif
                                </div>
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

    </div>
</x-customers-layout>
