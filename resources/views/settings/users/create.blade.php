<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-gray-400 text-[10px] font-bold mb-0.5 tracking-wider">لوحة التحكم / إعدادات النظام / المستخدمين</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                إضافة مستخدم جديد
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto pb-10">
        {{-- Top Return Link --}}
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('settings.users.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>العودة لقائمة المستخدمين</span>
            </a>
        </div>

        <form action="{{ route('settings.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Basic Info Column (1 col) -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800">البيانات الأساسية</h2>
                            <span class="text-[10px] font-extrabold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-lg border border-rose-100">* حقول إلزامية</span>
                        </div>
                        <div class="p-6 space-y-4">
                            {{-- Name --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">اسم المستخدم بالكامل <span class="text-rose-500">*</span></label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       required
                                       placeholder="الاسم الرباعي أو الثلاثي"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-medium">
                                @error('name') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            
                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">البريد الإلكتروني (لتسجيل الدخول) <span class="text-rose-500">*</span></label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       placeholder="example@beithanoon.ps"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-mono font-medium" dir="ltr">
                                @error('email') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Department --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">الدائرة / القسم التابع له</label>
                                <select name="department_id" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-medium">
                                    <option value="">-- بدون قسم / إدارة عامة --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Job Title & Phone --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">المسمى الوظيفي</label>
                                    <input type="text"
                                           name="job_title"
                                           value="{{ old('job_title') }}"
                                           placeholder="مهندس / موظف قلم الجمهور"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-medium">
                                    @error('job_title') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5">رقم الهاتف / الجوال</label>
                                    <input type="text"
                                           name="phone_number"
                                           value="{{ old('phone_number') }}"
                                           placeholder="059xxxxxxx"
                                           class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-mono font-medium" dir="ltr">
                                    @error('phone_number') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">كلمة المرور <span class="text-rose-500">*</span></label>
                                <input type="password"
                                       name="password"
                                       required
                                       placeholder="••••••••"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-mono">
                                <p class="text-[11px] text-gray-400 mt-1">يجب ألا تقل عن 6 خانات.</p>
                                @error('password') <span class="text-rose-500 text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">تأكيد كلمة المرور <span class="text-rose-500">*</span></label>
                                <input type="password"
                                       name="password_confirmation"
                                       required
                                       placeholder="••••••••"
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-xs rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors font-mono">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('settings.users.index') }}" class="w-1/3 py-3.5 px-4 text-center rounded-2xl border border-gray-200 text-xs font-bold text-slate-600 bg-white hover:bg-gray-50 transition-colors">
                            إلغاء
                        </a>
                        <button type="submit" class="w-2/3 py-3.5 px-4 border border-transparent rounded-2xl shadow-sm text-xs font-black text-white bg-primary hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all flex items-center justify-center gap-2 cursor-pointer hover:shadow">
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>حفظ وإضافة المستخدم</span>
                        </button>
                    </div>
                </div>

                <!-- Permissions & Modules Column (2 cols) -->
                @php
                    $modulesList = array_keys($modulesStructure);
                    $initialActiveModules = old('active_modules', []);
                    $oldPermissions = old('permissions', []);
                @endphp

                <div class="lg:col-span-2 space-y-6" x-data="{ activeModules: {{ json_encode($initialActiveModules) }} }">
                    
                    <!-- Modules Selection Card -->
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">صلاحيات الوصول للموديولات الأساسية</h2>
                            <p class="text-xs text-gray-500 mt-1">اختر الموديولات التي يمكن للمستخدم الجديد الوصول إليها لتحديد صلاحياتها الفرعية أدناه</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($modulesList as $module)
                            <label class="flex items-center gap-3 cursor-pointer group p-3.5 rounded-2xl bg-gray-50/60 hover:bg-teal-50/50 border border-gray-100 hover:border-teal-200 transition-all">
                                <input type="checkbox"
                                       name="active_modules[]"
                                       value="{{ $module }}"
                                       x-model="activeModules"
                                       @change="document.querySelectorAll('.mod-{{ md5($module) }}').forEach(el => el.checked = $event.target.checked)"
                                       class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                                <div>
                                    <span class="text-sm font-bold text-gray-800 group-hover:text-primary transition-colors block">{{ $module }}</span>
                                    <span class="text-[11px] text-gray-400">تفعيل كامل صلاحيات الموديول</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Permissions Details Card -->
                    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">تفاصيل الصلاحيات لكل موديول</h2>
                            <p class="text-xs text-gray-500 mt-1">حدد الصلاحيات والعمليات المسموح للمستخدم إجراؤها</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-8">
                                @foreach($modulesStructure as $mainModule => $subGroups)
                                    <div x-show="activeModules.includes('{{ $mainModule }}')" style="display: none;" class="space-y-6" x-transition>
                                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                                            <h3 class="text-base font-bold text-primary flex items-center gap-2">
                                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                                <span>{{ $mainModule }}</span>
                                            </h3>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            @foreach($subGroups as $subgroupName => $permissions)
                                                <div class="bg-gray-50/70 rounded-2xl p-4 border border-gray-100">
                                                    
                                                    <!-- Select All for this subgroup -->
                                                    <label class="flex items-center gap-2.5 cursor-pointer group mb-3 pb-2.5 border-b border-gray-200">
                                                        <input type="checkbox"
                                                               class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer" 
                                                               @change="document.querySelectorAll('.perm-{{ md5($mainModule . $subgroupName) }}').forEach(el => el.checked = $event.target.checked)">
                                                        <span class="text-xs font-black text-slate-800">تحديد الكل ({{ $subgroupName }})</span>
                                                    </label>

                                                    <!-- Subgroup Options -->
                                                    <div class="space-y-2.5 pr-1 max-h-60 overflow-y-auto">
                                                        @foreach($permissions as $perm)
                                                            <label class="flex items-center gap-2.5 cursor-pointer group text-xs">
                                                                <input type="checkbox"
                                                                       name="permissions[]"
                                                                       value="{{ $perm->name }}" 
                                                                       {{ in_array($perm->name, $oldPermissions) ? 'checked' : '' }}
                                                                       class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer mod-{{ md5($mainModule) }} perm-{{ md5($mainModule . $subgroupName) }}">
                                                                <span class="font-medium text-gray-700 group-hover:text-primary transition-colors font-mono">{{ $perm->name }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div x-show="activeModules.length === 0" class="text-center py-10">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-600">لم يتم اختيار أي موديول رئيسي بعد</h3>
                                <p class="text-xs text-gray-400 mt-1">حدد موديولاً أو أكثر من القائمة العلوية لتخصيص الصلاحيات الممنوحة للمستخدم.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

