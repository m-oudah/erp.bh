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
            <div class="mb-6 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 border border-emerald-100 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-rose-50 text-rose-700 px-4 py-3 rounded-2xl flex items-center gap-3 border border-rose-100 shadow-xs">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">قائمة المستخدمين</h2>
                    <p class="text-sm text-gray-500 mt-1">إدارة مستخدمي النظام والدوائر وصلاحيات الوصول</p>
                </div>
                <a href="{{ route('settings.users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-primary hover:bg-teal-700 text-white text-sm font-bold shadow-xs hover:shadow transition-all cursor-pointer">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>إضافة مستخدم جديد</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">الدائرة / القسم</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">عدد الصلاحيات</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold">
                                            {{ mb_substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                            @if($user->job_title)
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $user->job_title }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-600 font-mono">{{ $user->email }}</div>
                                    @if($user->phone_number)
                                        <div class="text-xs text-gray-400 mt-0.5 font-mono" dir="ltr">{{ $user->phone_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->department)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-teal-50 text-teal-700 text-xs font-bold border border-teal-100">
                                            {{ $user->department->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400 italic">بدون قسم محدد</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        {{ $user->permissions->count() }} صلاحية
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('settings.users.edit', $user) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-teal-700 bg-teal-50 hover:bg-primary hover:text-white transition-colors" title="تعديل المستخدم والصلاحيات">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('settings.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المستخدم {{ $user->name }}؟');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white transition-colors cursor-pointer" title="حذف المستخدم">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
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
