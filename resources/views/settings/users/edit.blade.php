<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم / إعدادات النظام / المستخدمين</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                تعديل المستخدم: {{ $user->name }}
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10">
        <form action="{{ route('settings.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Basic Info -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">البيانات الأساسية</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">اسم المستخدم <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-navy focus:border-navy p-3 transition-colors">
                                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-navy focus:border-navy p-3 transition-colors">
                                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">كلمة المرور الجديدة</label>
                                <input type="password" name="password" placeholder="اتركه فارغاً إذا لم ترغب بتغييره" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-navy focus:border-navy p-3 transition-colors">
                                <p class="text-xs text-gray-400 mt-1">يجب أن تكون 6 أحرف على الأقل.</p>
                                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-2xl shadow-sm text-sm font-bold text-white bg-navy hover:bg-navy-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-navy transition-colors">
                        حفظ التعديلات
                    </button>
                </div>

                <!-- Permissions -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">إدارة الصلاحيات والموديولات</h2>
                                <p class="text-sm text-gray-500 mt-1">حدد الصلاحيات التي يمكن للمستخدم الوصول إليها</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach($permissionsByModule as $module => $permissions)
                                    <div class="space-y-4">
                                        <h3 class="text-md font-bold text-navy border-b border-gray-100 pb-2 mb-3">{{ $module }}</h3>
                                        <div class="space-y-3">
                                            @foreach($permissions as $perm)
                                                <label class="flex items-center gap-3 cursor-pointer group">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                                            {{ $user->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                                            class="peer sr-only">
                                                        <div class="w-5 h-5 border-2 border-gray-300 rounded peer-checked:bg-navy peer-checked:border-navy transition-all flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-600 group-hover:text-gray-900 transition-colors">{{ $perm->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
