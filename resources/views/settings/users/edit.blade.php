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

                <!-- Permissions & Modules -->
                @php
                    $modulesList = array_keys($modulesStructure);
                    $initialActiveModules = [];
                    foreach($modulesStructure as $mainModule => $subGroups) {
                        foreach($subGroups as $perms) {
                            foreach($perms as $perm) {
                                if($user->hasPermissionTo($perm->name)) {
                                    $initialActiveModules[] = $mainModule;
                                    break 2;
                                }
                            }
                        }
                    }
                @endphp

                <div class="lg:col-span-2 space-y-6" x-data="{ activeModules: {{ json_encode($initialActiveModules) }} }">
                    
                    <!-- Modules Selection -->
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">صلاحيات الوصول للموديولات الأساسية</h2>
                            <p class="text-sm text-gray-500 mt-1">اختر الموديولات التي يمكن للمستخدم الوصول إليها لإظهار تفاصيلها (إلغاء التحديد سيلغي الصلاحيات المرتبطة)</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($modulesList as $module)
                            <label class="flex items-center gap-3 cursor-pointer group p-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-all">
                                <input type="checkbox" value="{{ $module }}" x-model="activeModules"
                                    @change="document.querySelectorAll('.mod-{{ md5($module) }}').forEach(el => el.checked = $event.target.checked)"
                                    class="w-5 h-5 rounded border-gray-300 text-navy focus:ring-navy cursor-pointer">
                                <span class="text-md font-bold text-gray-800 group-hover:text-navy transition-colors">{{ $module }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Permissions Details -->
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">تفاصيل الصلاحيات لكل موديول</h2>
                            <p class="text-sm text-gray-500 mt-1">حدد الصلاحيات الفرعية الخاصة بكل موديول</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-8">
                                @foreach($modulesStructure as $mainModule => $subGroups)
                                    <div x-show="activeModules.includes('{{ $mainModule }}')" style="display: none;" class="space-y-6" x-transition>
                                        <h3 class="text-xl font-bold text-navy border-b border-navy/20 pb-3">{{ $mainModule }}</h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            @foreach($subGroups as $subgroupName => $permissions)
                                                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100">
                                                    
                                                    <!-- Right Aligned Select All directly above the options -->
                                                    <label class="flex items-center gap-3 cursor-pointer group mb-4 pb-3 border-b border-gray-200">
                                                        <input type="checkbox" class="w-5 h-5 rounded border-gray-300 text-navy focus:ring-navy cursor-pointer" 
                                                            @change="document.querySelectorAll('.perm-{{ md5($mainModule . $subgroupName) }}').forEach(el => el.checked = $event.target.checked)">
                                                        <span class="text-sm font-bold text-navy">تحديد الكل ({{ $subgroupName }})</span>
                                                    </label>

                                                    <!-- Subgroup Options -->
                                                    <div class="space-y-3 pl-1">
                                                        @foreach($permissions as $perm)
                                                            <label class="flex items-center gap-3 cursor-pointer group">
                                                                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                                                    {{ $user->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                                                    class="w-5 h-5 rounded border-gray-300 text-navy focus:ring-navy cursor-pointer mod-{{ md5($mainModule) }} perm-{{ md5($mainModule . $subgroupName) }}">
                                                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">{{ $perm->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div x-show="activeModules.length === 0" style="display: none;" class="text-center py-10">
                                <x-heroicon-o-shield-exclamation class="w-14 h-14 text-gray-300 mx-auto mb-4" />
                                <h3 class="text-lg font-bold text-gray-500">لا توجد موديولات محددة</h3>
                                <p class="text-sm text-gray-400 mt-2">يرجى تحديد موديول واحد على الأقل من الأعلى (مثل التنظيم والأبنية) لعرض الصلاحيات الخاصة به.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
