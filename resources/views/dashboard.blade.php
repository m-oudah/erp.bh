<x-app-layout>
    <div x-data="{
        showNoticeModal: false,
        noticeTitle: '',
        noticeDesc: '',
        showNoAccessModal: false,
        noAccessModuleName: '',
        openNoAccess(name) {
            this.noAccessModuleName = name;
            this.showNoAccessModal = true;
        }
    }">

        {{-- Flash Session Alert (if blocked by middleware) --}}
        @if(session('error'))
            <div class="max-w-7xl mx-auto px-2 sm:px-4 mb-4">
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 text-xs font-bold shadow-xs">
                    <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0">
                        <x-heroicon-s-lock-closed class="w-4 h-4" />
                    </div>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- 4-Column Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-7xl mx-auto pb-10 px-2 sm:px-4">

            <!-- 1. Module: Archive (الأرشيف الإلكتروني) -->
            @php $canArchive = auth()->user()?->canAccessModule('archive'); @endphp
            @if($canArchive)
                <a href="{{ route('archive.index') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-document-text class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                نشط بالكامل
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            الأرشيف الإلكتروني والمستندات
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            أرشفة وتنظيم الوثائق الرقمية والملفات التنظيمية والبحث المتقدم في السجلات
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('الأرشيف الإلكتروني والمستندات')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-document-text class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            الأرشيف الإلكتروني والمستندات
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            أرشفة وتنظيم الوثائق الرقمية والملفات التنظيمية والبحث المتقدم في السجلات
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2. Module: Buildings (نظام التنظيم والأبنية) -->
            @php $canBuildings = auth()->user()?->canAccessModule('buildings'); @endphp
            @if($canBuildings)
                <a href="{{ route('buildings.dashboard') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-building-office class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                نشط جزئياً
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            نظام التنظيم والأبنية
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            إدارة ملفات العقارات، رخص البناء، الكشف التنظيمي، وسجل المخططات
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('نظام التنظيم والأبنية')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-building-office class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            نظام التنظيم والأبنية
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            إدارة ملفات العقارات، رخص البناء، الكشف التنظيمي، وسجل المخططات
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 3. Module: Crafts (نظام الحرف والصناعات) -->
            @php $canCrafts = auth()->user()?->canAccessModule('crafts'); @endphp
            @if($canCrafts)
                <a href="{{ route('crafts.dashboard') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-wrench-screwdriver class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                نشط بالكامل
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            نظام الحرف والصناعات
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            إصدار وتجديد رخص المهن، إدارة المنشآت الحرفية، والرقابة والمتابعة
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('نظام الحرف والصناعات')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-wrench-screwdriver class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            نظام الحرف والصناعات
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            إصدار وتجديد رخص المهن، إدارة المنشآت الحرفية، والرقابة والمتابعة
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 4. Module: Customers (المشتركون والعملاء) -->
            @php $canCustomers = auth()->user()?->canAccessModule('customers'); @endphp
            @if($canCustomers)
                <a href="{{ route('customers.dashboard') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-users class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                نشط بالكامل
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            المشتركين والعملاء
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            سجل المواطنين والمكلفين، إدارة الاشتراكات والخدمات، وربط المستفيدين
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('المشتركين والعملاء')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-users class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            المشتركين والعملاء
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            سجل المواطنين والمكلفين، إدارة الاشتراكات والخدمات، وربط المستفيدين
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 5. Module: Transactions (قلم الجمهور والمعاملات) -->
            @php $canTransactions = auth()->user()?->canAccessModule('transactions'); @endphp
            @if($canTransactions)
                <a href="{{ route('transactions.index') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-document-duplicate class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                نشط بالكامل
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            قلم الجمهور والمعاملات
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            استقبال طلبات الجمهور، تتبع المعاملات، والتوزيع والتوجيه بين الأقسام
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('قلم الجمهور والمعاملات')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-document-duplicate class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            قلم الجمهور والمعاملات
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            استقبال طلبات الجمهور، تتبع المعاملات، والتوزيع والتوجيه بين الأقسام
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 6. Module: Internal Correspondence (المراسلات الداخلية) -->
            @php $canCorrespondence = auth()->user()?->canAccessModule('correspondence'); @endphp
            @if($canCorrespondence)
                @php
                    $unreadCorrespondenceCount = auth()->user()?->unreadNotifications()->count() ?? 0;
                @endphp
                <a href="{{ route('correspondence.memos.index') }}"
                   x-data="{
                       unreadCount: {{ (int) $unreadCorrespondenceCount }},
                       fetchUnread() {
                           fetch('{{ route('notifications.unread') }}')
                               .then(res => res.json())
                               .then(data => {
                                   this.unreadCount = data.count || 0;
                               }).catch(() => {});
                       }
                   }"
                   x-init="fetchUnread(); setInterval(() => fetchUnread(), 30000)"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 cursor-pointer min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="relative">
                                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0 ring-1 ring-secondary/20 relative">
                                    <x-heroicon-s-inbox-stack class="w-6 h-6" style="color: inherit;" />
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute -top-1.5 -right-1.5 flex h-5 min-w-5 px-1.5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-black text-white ring-2 ring-white shadow-md leading-none animate-bounce"
                                              x-text="unreadCount"
                                              title="مراسلات جديدة غير مقروءة"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                <template x-if="unreadCount > 0">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        <span x-text="unreadCount + ' جديدة'"></span>
                                    </span>
                                </template>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200 shadow-2xs shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                                    نشط ومفعّل
                                </span>
                            </div>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            المراسلات الداخلية
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            سير المراسلات، التوجيه والشروحات الإدارية، وإعادة التوجيه كبريد داخلي موحد
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-primary pt-4 mt-4 border-t border-slate-100 group-hover:text-primary-dark">
                        <span class="flex items-center gap-1.5 text-[11px]">
                            <span>الدخول للموديول</span>
                            <x-heroicon-s-arrow-left class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
                        </span>
                        <template x-if="unreadCount > 0">
                            <span class="text-[11px] font-extrabold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-lg border border-rose-200 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                <span x-text="unreadCount + ' وارد جديد'"></span>
                            </span>
                        </template>
                        <template x-if="unreadCount == 0">
                            <span class="text-[11px] font-extrabold bg-teal-50 text-teal-700 px-2.5 py-1 rounded-lg border border-teal-200">
                                متاح الآن
                            </span>
                        </template>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('المراسلات الداخلية')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-inbox-stack class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            المراسلات الداخلية
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            سير المراسلات، التوجيه والشروحات الإدارية، وإعادة التوجيه كبريد داخلي موحد
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

            <!-- 7. Module: Human Resources (شؤون الموظفين والموارد البشرية) -->
            @php $canHr = auth()->user()?->canAccessModule('hr'); @endphp
            @if($canHr)
                <a href="{{ route('hr.dashboard') }}"
                   class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                                <x-heroicon-s-identification class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                نشط بالكامل
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                            شؤون الموظفين والموارد البشرية
                        </h3>
                        <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                            ملفات الموظفين، الهيكلية الإدارية، قانون الخدمة المدنية، واحتساب الرواتب الشهرية
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-600 group-hover:text-primary pt-4 mt-4 border-t border-slate-100 transition-colors">
                        <span>دخول النظام</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 group-hover:bg-primary group-hover:text-white text-slate-600 flex items-center justify-center transition-all">
                            <x-heroicon-s-arrow-left class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" />
                        </div>
                    </div>
                </a>
            @else
                <div @click="openNoAccess('شؤون الموظفين والموارد البشرية')"
                     class="group relative bg-slate-100/70 rounded-3xl p-5 border border-slate-200/90 shadow-2xs opacity-60 grayscale hover:opacity-85 transition-all duration-300 flex flex-col justify-between cursor-not-allowed select-none min-h-[230px]">
                    <div>
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center transition-all duration-300 shrink-0">
                                <x-heroicon-s-identification class="w-6 h-6" style="color: inherit;" />
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-500 border border-slate-300 shrink-0">
                                <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-slate-500" />
                                معطل / غير مصرح
                            </span>
                        </div>

                        <h3 class="text-lg font-extrabold text-slate-500 tracking-tight leading-snug">
                            شؤون الموظفين والموارد البشرية
                        </h3>
                        <p class="text-xs text-slate-400 font-normal leading-relaxed mt-2">
                            ملفات الموظفين، الهيكلية الإدارية، قانون الخدمة المدنية، واحتساب الرواتب الشهرية
                        </p>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold text-slate-400 pt-4 mt-4 border-t border-slate-200 transition-colors">
                        <span>ليس لديك صلاحية للوصول</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-400 flex items-center justify-center">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Modal: No Access to Module (تنبيه عدم وجود صلاحية) -->
        <template x-teleport="body">
            <div x-show="showNoAccessModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
                 style="display: none;"
                 @keydown.escape.window="showNoAccessModal = false">
                
                <div class="fixed inset-0" @click="showNoAccessModal = false"></div>

                <div @click.stop
                     class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-rose-100 w-full max-w-md relative z-10 text-center space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto shadow-inner border border-rose-100">
                        <x-heroicon-s-lock-closed class="w-8 h-8 text-rose-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">ليس لديك صلاحية للوصول للنظام</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2.5">
                            عذراً، حسابك الحالي لا يمتلك صلاحية الوصول إلى موديول <span class="font-black text-rose-600" x-text="'(' + noAccessModuleName + ')'"></span>.
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1.5 font-medium">
                            يُرجى مراجعة مدير النظام لمنحك الصلاحيات اللازمة للوصول إلى هذا القسم.
                        </p>
                    </div>
                    <div class="pt-2">
                        <button type="button"
                                @click="showNoAccessModal = false"
                                class="w-full py-2.5 bg-slate-900 hover:bg-black text-white font-bold text-xs rounded-xl transition-all shadow-sm cursor-pointer">
                            حسناً، فهمت
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
