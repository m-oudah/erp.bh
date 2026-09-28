<x-archive-settings-layout>
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">

        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-100">
            <h2 class="font-bold text-navy-dark text-xl flex items-center gap-2">
                <x-heroicon-s-shield-check class="w-6 h-6 text-orange" />
                صلاحيات المستخدمين - نظام الأرشيف
            </h2>
            <p class="text-gray-400 text-sm mt-1">يمكنك تحديد الصلاحيات الممنوحة لكل مستخدم في نظام الأرشيف</p>
        </div>

        @if(session('success'))
            <div class="mx-8 mt-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Permissions Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-gray-50/70 text-gray-500 text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold text-right w-52">المستخدم</th>
                        @foreach($permissionLabels as $perm => $label)
                            <th class="px-4 py-4 font-bold text-center whitespace-nowrap">{{ $label }}</th>
                        @endforeach
                        <th class="px-6 py-4 font-bold text-center">حفظ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-navy/10 text-navy flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-800 text-sm">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        
                        <form action="{{ route('settings.archive.user-permissions.update', $user->id) }}" method="POST" id="perm-form-{{ $user->id }}" class="contents">
                            @csrf
                            @foreach($permissions as $perm)
                                <td class="px-4 py-4 text-center">
                                    <label class="relative inline-flex items-center cursor-pointer justify-center">
                                        <input 
                                            type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $perm }}"
                                            class="sr-only peer"
                                            form="perm-form-{{ $user->id }}"
                                            {{ ($userPermissionsMap[$user->id][$perm] ?? false) ? 'checked' : '' }}
                                        >
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-orange peer-focus:ring-2 peer-focus:ring-orange/30 transition-colors after:content-[''] after:absolute after:top-0.5 after:right-0.5 after:bg-white after:rounded-full after:w-5 after:h-5 after:transition-all peer-checked:after:translate-x-[-20px] after:shadow-sm"></div>
                                    </label>
                                </td>
                            @endforeach
                            <td class="px-6 py-4 text-center">
                                <button type="submit" form="perm-form-{{ $user->id }}" class="inline-flex items-center gap-1.5 bg-orange/10 text-orange hover:bg-orange hover:text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors">
                                    <x-heroicon-s-check class="w-3.5 h-3.5" />
                                    حفظ
                                </button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Legend -->
        <div class="px-8 py-4 border-t border-gray-100 bg-gray-50/30">
            <p class="text-xs text-gray-400 flex items-center gap-2">
                <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400 shrink-0" />
                يرجى الضغط على "حفظ" في صف كل مستخدم على حدة لتطبيق التعديلات الخاصة به.
            </p>
        </div>
    </div>
</x-archive-settings-layout>
