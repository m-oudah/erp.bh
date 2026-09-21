<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                أنواع ملفات الأرشيف
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-navy-dark">إدارة أنواع الملفات وحقولها</h2>
            <a href="{{ route('settings.archive-types.create') }}" class="bg-navy hover:bg-navy-dark text-white font-bold py-2 px-6 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <x-heroicon-o-plus class="w-5 h-5" />
                إضافة نوع جديد
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">اسم النوع</th>
                            <th class="px-6 py-4">الوصف</th>
                            <th class="px-6 py-4 text-center">عدد الحقول المخصصة</th>
                            <th class="px-6 py-4 text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($types as $type)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-navy">{{ $type->name }}</td>
                                <td class="px-6 py-4">{{ $type->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-orange/10 text-orange font-bold px-3 py-1 rounded-full text-xs">
                                        {{ $type->fields_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('settings.archive-types.edit', $type->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="تعديل">
                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                        </a>
                                        <form action="{{ route('settings.archive-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="حذف">
                                                <x-heroicon-o-trash class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    لا يوجد أنواع ملفات مضافة بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
