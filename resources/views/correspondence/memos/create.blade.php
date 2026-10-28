<x-correspondence-layout>
<script>
function memoComposer() {
    return {
        memoType: @json(old('memo_type', 'مذكرة داخلية')),
        searchQuery: '',
        showDropdown: false,
        allUsers: @json($usersList),
        selectedUsers: [],
        attachments: [],
        showAdvanced: {{ old('due_date') || old('linked_archive_file_id') ? 'true' : 'false' }},

        init() {
            const oldIds = @json($oldAssigneeIds);
            if (oldIds && oldIds.length > 0) {
                this.selectedUsers = this.allUsers.filter(u => oldIds.map(Number).includes(Number(u.id)));
            }
        },

        get filteredUsers() {
            if (!this.searchQuery.trim()) {
                return this.allUsers.filter(u => !this.isSelected(u.id));
            }
            const q = this.searchQuery.toLowerCase().trim();
            return this.allUsers.filter(u => {
                return !this.isSelected(u.id) && (
                    u.name.toLowerCase().includes(q) ||
                    (u.department && u.department.toLowerCase().includes(q)) ||
                    (u.job_title && u.job_title.toLowerCase().includes(q))
                );
            });
        },

        isSelected(id) {
            return this.selectedUsers.some(u => Number(u.id) === Number(id));
        },

        addUser(user) {
            if (!this.isSelected(user.id)) {
                this.selectedUsers.push(user);
            }
            this.searchQuery = '';
            this.showDropdown = false;
            this.$nextTick(() => {
                if (this.$refs.userInput) this.$refs.userInput.focus();
            });
        },

        removeUser(id) {
            this.selectedUsers = this.selectedUsers.filter(u => Number(u.id) !== Number(id));
        },

        handleBackspace() {
            if (this.searchQuery === '' && this.selectedUsers.length > 0) {
                this.selectedUsers.pop();
            }
        },

        triggerFileInput() {
            this.$refs.fileInput.click();
        },

        handleFiles(event) {
            const files = Array.from(event.target.files);
            this.attachments = files.map((file, idx) => ({
                id: Date.now() + '_' + idx,
                name: file.name,
                size: this.formatSize(file.size),
                file: file
            }));
        },

        removeFile(index) {
            const dt = new DataTransfer();
            const input = this.$refs.fileInput;
            const files = input.files;
            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]);
                }
            }
            input.files = dt.files;
            this.attachments.splice(index, 1);
        },

        formatSize(bytes) {
            if (!bytes || bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }
    };
}
</script>

    <div class="w-full space-y-4" x-data="memoComposer()">
        {{-- Header Card with Navigation Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-primary flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-800 leading-tight">إنشاء مراسلة داخلية جديدة</h2>
                    <p class="text-xs text-slate-500 mt-0.5">إرسال وتوجيه المراسلات والتكليفات الإدارية للموظفين والأقسام كبريد داخلي موحد</p>
                </div>
            </div>

            {{-- Navigation Action Buttons --}}
            <div class="flex items-center gap-2">
                {{-- Home Button --}}
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs"
                   title="الرجوع إلى لوحة التحكم الرئيسية">
                    <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>الرجوع للرئيسية</span>
                </a>

                {{-- Back to Correspondence Index --}}
                <a href="{{ route('correspondence.memos.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200/60 transition-colors shadow-xs"
                   title="العودة إلى سجل المراسلات">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>سجل المراسلات</span>
                </a>
            </div>
        </div>

        {{-- Single-Page Email-Style Composer Form --}}
        <form method="POST" action="{{ route('correspondence.memos.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Hidden real file input --}}
            <input type="file"
                   x-ref="fileInput"
                   name="attachments[]"
                   multiple
                   @change="handleFiles($event)"
                   class="hidden">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                
                {{-- Row 1: نوع المراسلة --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>نوع المراسلة</span>
                            <span class="text-rose-500">*</span>
                        </span>
                        <span class="text-[11px] text-tertiary">حدد تصنيف المعاملة</span>
                    </label>
                    <select name="memo_type"
                            x-model="memoType"
                            required
                            class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary font-bold text-slate-800 py-2.5">
                        <option value="مذكرة داخلية">📋 مذكرة داخلية</option>
                        <option value="تقرير عمل">📊 تقرير عمل</option>
                        <option value="طلب صرف">💰 طلب صرف</option>
                        <option value="تقرير مالي">📈 تقرير مالي</option>
                        <option value="استفسار إداري">❓ استفسار إداري</option>
                        <option value="إشعار إداري">📢 إشعار إداري</option>
                        <option value="طلب إجازة / مغادرة">🏖️ طلب إجازة / مغادرة</option>
                        <option value="أخرى">📝 أخرى</option>
                    </select>
                    @error('memo_type')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 2: حقل الموظف الموجهة إليه (إلى / To: - متعدد المستلمين مع البحث التفاعلي مثل البريد الإلكتروني) --}}
                <div class="relative" @click.away="showDropdown = false">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>المستلمون (الموظف الموجهة إليه في عهدته)</span>
                            <span class="text-rose-500">*</span>
                        </span>
                        <span class="text-[11px] text-teal-700 font-semibold bg-teal-50 px-2.5 py-0.5 rounded-full border border-teal-100">
                            يمكنك اختيار أكثر من موظف عبر البحث بالاسم
                        </span>
                    </label>

                    {{-- Email "To:" Multi-Recipient Box with Tag Chips --}}
                    <div class="min-h-[50px] p-2 bg-white rounded-xl border border-gray-200 focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20 transition-all flex flex-wrap items-center gap-2 cursor-text"
                         @click="$refs.userInput.focus(); showDropdown = true">
                        
                        {{-- To: Prefix Label --}}
                        <div class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold shrink-0 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>إلى:</span>
                        </div>

                        {{-- Selected Users Pills / Chips --}}
                        <template x-for="user in selectedUsers" :key="user.id">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-900 border border-teal-200/70 shadow-2xs animate-scale-up">
                                <input type="hidden" name="assignee_ids[]" :value="user.id">
                                <span class="w-2 h-2 rounded-full bg-teal-600 shrink-0"></span>
                                <span x-text="user.name"></span>
                                <span class="text-[10px] text-teal-700 opacity-80" x-text="'(' + user.department + ')'"></span>
                                <button type="button"
                                        @click.stop="removeUser(user.id)"
                                        class="w-4 h-4 rounded-full hover:bg-teal-200/80 text-teal-800 inline-flex items-center justify-center transition-colors mr-0.5 cursor-pointer"
                                        title="إزالة">
                                    &times;
                                </button>
                            </div>
                        </template>

                        {{-- Active Search Input inside the box --}}
                        <input type="text"
                               x-ref="userInput"
                               x-model="searchQuery"
                               @focus="showDropdown = true"
                               @input="showDropdown = true"
                               @keydown.backspace="handleBackspace()"
                               :placeholder="selectedUsers.length === 0 ? 'اكتب اسم الموظف أو دائرته للبحث والاختيار...' : 'أضف موظفاً آخر...'"
                               class="flex-1 min-w-[180px] border-0 focus:ring-0 p-1 text-xs sm:text-sm text-slate-800 placeholder-slate-400 bg-transparent">
                    </div>

                    {{-- Dropdown Search Results --}}
                    <div x-show="showDropdown && filteredUsers.length > 0"
                         x-transition
                         class="absolute z-30 right-0 left-0 mt-1 max-h-60 overflow-y-auto bg-white rounded-2xl border border-gray-200 shadow-xl p-2 space-y-1">
                        <div class="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-gray-100 flex items-center justify-between">
                            <span>نتائج البحث (<span x-text="filteredUsers.length"></span> موظف)</span>
                            <span class="text-[10px] text-slate-400">انقر على الموظف للإضافة</span>
                        </div>
                        <template x-for="user in filteredUsers" :key="user.id">
                            <div @click="addUser(user)"
                                 class="p-2.5 rounded-xl hover:bg-teal-50/70 hover:border-teal-200 border border-transparent transition-all flex items-center justify-between gap-3 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-800 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                                        <span x-text="user.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover:text-primary transition-colors" x-text="user.name"></div>
                                        <div class="text-[11px] text-slate-500" x-text="user.department + (user.job_title ? ' - ' + user.job_title : '')"></div>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md group-hover:bg-teal-100 transition-colors">
                                    + إضافة
                                </span>
                            </div>
                        </template>
                    </div>

                    {{-- Empty Search Results Notice --}}
                    <div x-show="showDropdown && searchQuery.trim() !== '' && filteredUsers.length === 0"
                         x-transition
                         class="absolute z-30 right-0 left-0 mt-1 bg-white rounded-2xl border border-gray-200 shadow-xl p-4 text-center text-xs text-slate-500">
                        لا توجد نتائج مطابقة لـ "<span class="font-bold text-slate-800" x-text="searchQuery"></span>"
                    </div>

                    @error('assignee_ids')
                        <p class="text-rose-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 3: موضوع المراسلة --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        <span>موضوع المراسلة</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="subject"
                           value="{{ old('subject') }}"
                           placeholder="مثال: طلب تقرير فني بخصوص المخطط التفصيلي للشارع الغربي..."
                           required
                           class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2.5 font-semibold">
                    @error('subject')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 4: شريط أدوات البريد الإلكتروني للمحتوى والمرفقات --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 pb-1 border-y border-gray-100">
                    <div class="flex items-center gap-2">
                        {{-- Attach Files Button --}}
                        <button type="button"
                                @click="triggerFileInput()"
                                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer shadow-2xs">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span>إرفاق ملفات ومستندات</span>
                            <template x-if="attachments.length > 0">
                                <span class="bg-amber-400 text-slate-900 text-[10px] px-1.5 py-0.2 rounded-full font-black" x-text="attachments.length"></span>
                            </template>
                        </button>

                        {{-- Toggle Advanced Options --}}
                        <button type="button"
                                @click="showAdvanced = !showAdvanced"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition-colors cursor-pointer"
                                :class="showAdvanced ? 'bg-teal-50 text-teal-800 border border-teal-200/60' : 'bg-white hover:bg-slate-50 text-slate-600 border border-gray-200'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                            <span>خيارات إضافية (الأرشفة والموعد)</span>
                            <svg class="w-3.5 h-3.5 transition-transform" :class="showAdvanced ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Smart Mention Tip --}}
                    <div class="text-[11px] text-amber-700 font-semibold flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/60">
                        <svg class="w-3.5 h-3.5 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>اكتب <span class="underline font-bold text-secondary">@اسم_الموظف</span> في النص لإنشاء مهمة مباشرة له</span>
                    </div>
                </div>

                {{-- Attached Files List (Email-style attachments chips) --}}
                <div x-show="attachments.length > 0" x-transition class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>الملفات المرفقة الجاهزة للإرسال (<span x-text="attachments.length"></span> ملف)</span>
                        </span>
                        <button type="button" @click="triggerFileInput()" class="text-[11px] font-bold text-primary hover:underline">
                            + إضافة المزيد
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        <template x-for="(att, idx) in attachments" :key="att.id">
                            <div class="p-2.5 rounded-xl bg-white border border-gray-200 flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-800 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate" x-text="att.name"></div>
                                        <div class="text-[10px] text-tertiary" x-text="att.size"></div>
                                    </div>
                                </div>
                                <button type="button"
                                        @click="removeFile(idx)"
                                        class="p-1 rounded-lg hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-colors"
                                        title="إلغاء المرفق">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Row 5: خيارات إضافية (تاريخ الاستحقاق والربط بالأرشيف) --}}
                <div x-show="showAdvanced" x-transition class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- تاريخ الاستحقاق --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                الموعد النهائي للإنجاز / تاريخ الاستحقاق (اختياري)
                            </label>
                            <input type="date"
                                   name="due_date"
                                   value="{{ old('due_date') }}"
                                   class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2.5">
                            <p class="text-[10px] text-slate-400 mt-1">تحديد جدول زمني محدد للمتابعة وإشعار الموظف بالاستحقاق.</p>
                        </div>

                        {{-- ربط بملف أرشيف --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                ربط بملف أرشيف مركزي قائم (اختياري)
                            </label>
                            <select name="linked_archive_file_id" class="w-full text-sm rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2.5">
                                <option value="">لا يوجد ربط (سيتم إنشاء ملف أرشيف خاص بالمراسلة تلقائياً)</option>
                                @foreach ($archiveFiles as $file)
                                    <option value="{{ $file->id }}" {{ old('linked_archive_file_id') == $file->id ? 'selected' : '' }}>
                                        {{ $file->file_no }} — {{ $file->file_name ?? $file->fileType->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">لربط المراسلة بملف مواطن أو رخصة بناء قائمة في الأرشيف الموحد.</p>
                        </div>
                    </div>
                </div>

                {{-- Row 6: نص وتفاصيل المراسلة --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span>نص وتفاصيل المراسلة</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="content"
                              rows="10"
                              required
                              placeholder="اكتب نص المراسلة أو التوجيه هنا... بإمكانك إضافة تكليف لموظف مباشرة بالصيغة: @أحمد_علي يرجى تدقيق الملف وإبداء الرأي خلال 48 ساعة."
                              class="w-full text-base rounded-xl border-gray-200 focus:border-primary focus:ring-primary p-4 leading-relaxed font-medium">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Persistent Bottom Action Bar --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button"
                            @click="triggerFileInput()"
                            class="px-3.5 py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <span>إرفاق ملف</span>
                    </button>
                    <a href="{{ route('correspondence.memos.index') }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        إلغاء والعودة
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
                        الرئيسية
                    </a>
                </div>

                <button type="submit"
                        class="px-7 py-2.5 rounded-xl text-sm font-black text-white bg-primary hover:bg-teal-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span>إرسال وتوجيه المراسلة الآن</span>
                </button>
            </div>
        </form>
    </div>
</x-correspondence-layout>
