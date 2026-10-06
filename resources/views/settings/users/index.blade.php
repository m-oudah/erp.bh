<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم / إعدادات النظام</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                إعدادات المستخدمين
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10">
        @if (session('success'))
            <div class="mb-6 bg-green-50 text-green-600 px-4 py-3 rounded-2xl flex items-center gap-3 border border-green-100">
                <x-heroicon-o-check-circle class="w-5 h-5" />
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">قائمة المستخدمين</h2>
                    <p class="text-sm text-gray-500 mt-1">إدارة مستخدمي النظام وصلاحياتهم</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">عدد الصلاحيات</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-navy/5 flex items-center justify-center text-navy font-bold">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-600">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold">
                                        <x-heroicon-o-shield-check class="w-4 h-4 text-green-500" />
                                        {{ $user->permissions->count() }} صلاحية
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('settings.users.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white transition-colors" title="تعديل المستخدم والصلاحيات">
                                            <x-heroicon-s-pencil class="w-4 h-4" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</x-app-layout>
