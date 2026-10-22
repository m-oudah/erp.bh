<x-app-layout>
    <div x-data="{ showNoticeModal: false, noticeTitle: '', noticeDesc: '' }">
        
        <!-- Page Header -->
        <!-- <div class="flex flex-col items-center justify-center text-center mb-8 mt-2">
            <h3 class="text-xl sm:text-2xl md:text-xl font-extrabold text-slate-800 tracking-tight mb-2">
                مرحباً بك في <span class="text-primary">بوابة الأنظمة المركزية</span>
            </h3>
            <p class="text-xs sm:text-sm text-tertiary max-w-xl font-medium leading-relaxed">
                اختر الموديول المخصص لك لمباشرة العمل والوصول السريع إلى الخدمات والعمليات اليومية.
            </p>
        </div> -->

        <!-- 4-Column Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 max-w-7xl mx-auto pb-10 px-2 sm:px-4">
            
            <!-- 1. Active Module: Archive (الأرشيف الإلكتروني) -->
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

            <!-- 2. Active Module: Buildings (نظام التنظيم والأبنية) -->
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

            <!-- 3. Active Module: Crafts (نظام الحرف والصناعات) -->
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

            <!-- 4. Active Module: Customers (المشتركون والعملاء) -->
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
                        المشتركون والعملاء
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

            <!-- 5. Active Module: Transactions (قلم الجمهور والمعاملات) -->
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

            <!-- 6. Active Module: Internal Correspondence & Tasks (المراسلات والمهام الداخلية) -->
            <a href="{{ route('correspondence.memos.index') }}"
               class="group relative bg-white rounded-3xl p-5 border border-slate-200/80 hover:border-primary/60 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-xl hover:shadow-primary/10 transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 cursor-pointer min-h-[230px]">
                
                <div>
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-secondary group-hover:bg-secondary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0 ring-1 ring-secondary/20">
                            <x-heroicon-s-inbox-stack class="w-6 h-6" style="color: inherit;" />
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200 shadow-2xs shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
                            نشط ومفعّل
                        </span>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-800 group-hover:text-primary transition-colors tracking-tight leading-snug">
                        المراسلات والمهام الداخلية
                    </h3>
                    <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                        سير المذكرات، التوجيه والشروحات الإدارية، وتكليف وتتبع إنجاز المهام الداخلية
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs font-bold text-primary pt-4 mt-4 border-t border-slate-100 group-hover:text-primary-dark">
                    <span class="flex items-center gap-1.5 text-[11px]">
                        <span>الدخول للموديول</span>
                        <x-heroicon-s-arrow-left class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" />
                    </span>
                    <span class="text-[11px] font-extrabold bg-teal-50 text-teal-700 px-2.5 py-1 rounded-lg border border-teal-200">
                        متاح الآن
                    </span>
                </div>
            </a>

            <!-- 7. Inactive Module: Human Resources (شؤون الموظفين والموارد البشرية) -->
            <div @click="noticeTitle = 'نظام شؤون الموظفين والموارد البشرية'; noticeDesc = 'موديول شؤون الموظفين مخصص لإدارة الكادر الوظيفي وسجلات الحضور والغياب والإجازات والهيكل التنظيمي، وهو مجدول للمراحل القادمة من تطوير النظام.'; showNoticeModal = true"
                 class="group relative bg-white rounded-3xl p-5 border border-slate-200/70 hover:border-slate-400 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 flex flex-col justify-between hover:-translate-y-1.5 cursor-pointer min-h-[230px]">
                
                <div>
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-tertiary group-hover:bg-tertiary group-hover:text-white flex items-center justify-center transition-all duration-300 shadow-sm shrink-0">
                            <x-heroicon-s-identification class="w-6 h-6" />
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-tertiary border border-slate-200 shrink-0">
                            مستقبلي / قيد التجهيز
                        </span>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-700 group-hover:text-slate-900 transition-colors tracking-tight leading-snug">
                        شؤون الموظفين والموارد البشرية
                    </h3>
                    <p class="text-xs text-tertiary font-normal leading-relaxed mt-2">
                        ملفات الموظفين، الحضور والإجازات، الهيكل الإداري، والتقييم الوظيفي
                    </p>
                </div>

                <div class="flex items-center justify-between text-xs font-bold text-tertiary pt-4 mt-4 border-t border-slate-100">
                    <span class="flex items-center gap-1.5 text-[11px]">
                        <x-heroicon-s-lock-closed class="w-3.5 h-3.5 text-tertiary" />
                        <span>مرحلة قادمة</span>
                    </span>
                    <span class="text-[11px] font-bold bg-slate-100 text-tertiary border border-slate-200 px-2.5 py-1 rounded-lg">
                        مجدول
                    </span>
                </div>
            </div>

        </div>

        <!-- Informational Modal for Coming Soon Modules -->
        <template x-teleport="body">
            <div x-show="showNoticeModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs"
                 style="display: none;"
                 @keydown.escape.window="showNoticeModal = false">
                
                <div class="fixed inset-0" @click="showNoticeModal = false"></div>

                <div @click.stop
                     class="bg-white rounded-3xl p-6 shadow-2xl border border-gray-100 w-full max-w-md relative z-10 text-center space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-secondary flex items-center justify-center mx-auto shadow-inner">
                        <x-heroicon-s-sparkles class="w-7 h-7" />
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-800" x-text="noticeTitle"></h3>
                        <p class="text-xs text-gray-500 leading-relaxed mt-2" x-text="noticeDesc"></p>
                    </div>
                    <div class="pt-2">
                        <button type="button"
                                @click="showNoticeModal = false"
                                class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl transition-colors cursor-pointer">
                            حسناً، فهمت
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>
</x-app-layout>
