<x-correspondence-layout>
<script>
function memoForwarder() {
    return {
        searchQuery: '',
        showDropdown: false,
        allUsers: @json($usersList),
        selectedUsers: [],
        attachments: [],

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

    <div class="w-full space-y-4" x-data="memoForwarder()">
        {{-- Header Card with Navigation Actions --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-secondary flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-black text-slate-800 leading-tight">تحويل وإعادة توجيه مراسلة داخلية</h2>
                        <span class="px-2 py-0.5 rounded-md text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-200">
                            معاد توجيهها (Forward)
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        إعادة توجيه المعاملة <span class="font-mono font-bold text-teal-800">{{ $memo->memo_number }}</span> لموظف آخر مع شروحات وتوجيهات إدارية ملزمة
                    </p>
                </div>
            </div>

            {{-- Navigation Action Buttons --}}
            <div class="flex items-center gap-2">
                {{-- Back to Memo Button --}}
                <a href="{{ route('correspondence.memos.show', $memo->id) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-xs"
                   title="الرجوع إلى عرض المراسلة">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span>الرجوع للمراسلة</span>
                </a>

                {{-- Correspondence Index Button --}}
                <a href="{{ route('correspondence.memos.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200/60 transition-colors shadow-xs"
                   title="العودة إلى سجل المراسلات">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span>سجل المراسلات</span>
                </a>

                {{-- Home Button --}}
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors"
                   title="الرجوع إلى لوحة التحكم الرئيسية">
                    <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>الرئيسية</span>
                </a>
            </div>
        </div>

        {{-- Forward Composer Form --}}
        <form method="POST" action="{{ route('correspondence.memos.forward', $memo->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Hidden real file input --}}
            <input type="file"
                   x-ref="fileInput"
                   name="attachments[]"
                   multiple
                   @change="handleFiles($event)"
                   class="hidden">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                
                {{-- Meta Banner: تفاصيل المراسلة المعاد توجيهها --}}
                <div class="p-3.5 rounded-xl bg-amber-50/60 border border-amber-200/80 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                        <span class="font-bold text-slate-800">المراسلة الأصلية:</span>
                        <span class="font-mono font-black text-teal-900">{{ $memo->memo_number }}</span>
                        <span class="px-2 py-0.2 rounded-md font-bold bg-white text-slate-700 border border-amber-200">
                            {{ $memo->memo_type }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 text-slate-600">
                        <span>المنشئ الأصلي: <strong class="text-slate-800">{{ $memo->creator->name ?? 'غير محدد' }}</strong></span>
                        <span class="text-slate-300">|</span>
                        <span>تاريخ الإنشاء: <strong class="text-slate-800">{{ $memo->created_at->format('Y-m-d H:i') }}</strong></span>
                    </div>
                </div>

                {{-- Row 1: حقل الموظف المحول إليه (إلى / To: - متعدد المستلمين مع البحث التفاعلي مثل البريد الإلكتروني) --}}
                <div class="relative" @click.away="showDropdown = false">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>تحويل إلى (الموظف المحول إليه في عهدته)</span>
                            <span class="text-rose-500">*</span>
                        </span>
                        <span class="text-[11px] text-amber-800 font-semibold bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200">
                            يمكنك اختيار موظف أو أكثر للتحويل والتوجيه
                        </span>
                    </label>

                    {{-- Email "To:" Multi-Recipient Box with Tag Chips --}}
                    <div class="min-h-[50px] p-2 bg-white rounded-xl border border-gray-200 focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary/20 transition-all flex flex-wrap items-center gap-2 cursor-text"
                         @click="$refs.userInput.focus(); showDropdown = true">
                        
                        {{-- To: Prefix Label --}}
                        <div class="px-2 py-1 bg-amber-50 text-amber-800 rounded-lg text-xs font-bold shrink-0 flex items-center gap-1 border border-amber-100">
                            <svg class="w-3.5 h-3.5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                            <span>تحويل إلى:</span>
                        </div>

                        {{-- Selected Users Pills / Chips --}}
                        <template x-for="user in selectedUsers" :key="user.id">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-950 border border-amber-300 shadow-2xs">
                                <input type="hidden" name="assignee_ids[]" :value="user.id">
                                <span class="w-2 h-2 rounded-full bg-secondary shrink-0"></span>
                                <span x-text="user.name"></span>
                                <span class="text-[10px] text-amber-800 opacity-80" x-text="'(' + user.department + ')'"></span>
                                <button type="button"
                                        @click.stop="removeUser(user.id)"
                                        class="w-4 h-4 rounded-full hover:bg-amber-200 text-amber-900 inline-flex items-center justify-center transition-colors mr-0.5 cursor-pointer"
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
                               :placeholder="selectedUsers.length === 0 ? 'اكتب اسم الموظف أو دائرته للتحويل إليه...' : 'أضف موظفاً آخر...'"
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
                                 class="p-2.5 rounded-xl hover:bg-amber-50/70 hover:border-amber-200 border border-transparent transition-all flex items-center justify-between gap-3 cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-secondary group-hover:text-white transition-colors">
                                        <span x-text="user.name.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 group-hover:text-secondary transition-colors" x-text="user.name"></div>
                                        <div class="text-[11px] text-slate-500" x-text="user.department + (user.job_title ? ' - ' + user.job_title : '')"></div>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md group-hover:bg-amber-100 transition-colors">
                                    + اختيار
                                </span>
                            </div>
                        </template>
                    </div>

                    @error('assignee_ids')
                        <p class="text-rose-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 2: موضوع المراسلة --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">موضوع المراسلة المحولة</label>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="text-secondary font-black">إعادة توجيه:</span>
                        <span>{{ $memo->subject }}</span>
                    </div>
                </div>

                {{-- Row 3: شريط أدوات البريد الإلكتروني للمرفقات الإضافية والتكليف الذكي --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 pb-1 border-y border-gray-100">
                    <div class="flex items-center gap-2">
                        {{-- Attach Files Button --}}
                        <button type="button"
                                @click="triggerFileInput()"
                                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors cursor-pointer shadow-2xs">
                            <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <span>إرفاق مستندات إضافية مع التحويل</span>
                            <template x-if="attachments.length > 0">
                                <span class="bg-amber-400 text-slate-900 text-[10px] px-1.5 py-0.2 rounded-full font-black" x-text="attachments.length"></span>
                            </template>
                        </button>
                    </div>

                    {{-- Smart Mention Tip --}}
                    <div class="text-[11px] text-amber-700 font-semibold flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200/60">
                        <svg class="w-3.5 h-3.5 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>بإمكانك كتابة <span class="underline font-bold text-secondary">@اسم_الموظف</span> لإنشاء مهمة مباشرة له</span>
                    </div>
                </div>

                {{-- Attached Files List (New files) --}}
                <div x-show="attachments.length > 0" x-transition class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <div class="text-xs font-bold text-slate-700 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>المستندات الإضافية الجديدة المرفقة (<span x-text="attachments.length"></span> ملف)</span>
                        </span>
                        <button type="button" @click="triggerFileInput()" class="text-[11px] font-bold text-primary hover:underline">
                            + إضافة المزيد
                        </button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        <template x-for="(att, idx) in attachments" :key="att.id">
                            <div class="p-2.5 rounded-xl bg-white border border-gray-200 flex items-center justify-between gap-2 shadow-2xs">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center shrink-0">
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

                {{-- Row 4: مربع نص لكتابة التوجيه الإداري الجديد --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800 flex items-center justify-between">
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-secondary"></span>
                            <span>الشروحات والتوجيهات الإدارية الجديدة</span>
                            <span class="text-rose-500">*</span>
                        </span>
                        <span class="text-[11px] text-tertiary">حدد الإجراء والمطلوب تنفيذه بدقة من الموظف المحول إليه</span>
                    </label>
                    <textarea name="directive_text"
                              rows="6"
                              required
                              placeholder="اكتب التوجيه أو الشرح الإداري المطلوب تنفيذه... مثال: يرجى المتابعة الفورية والتدقيق المالي وإفادتنا بالنتيجة خلال 48 ساعة."
                              class="w-full text-base rounded-xl border-gray-200 focus:border-secondary focus:ring-secondary p-4 leading-relaxed font-semibold text-slate-800 shadow-2xs">{{ old('directive_text') }}</textarea>
                    @error('directive_text')
                        <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 5: المحتوى الأصلي للمراسلة (غير قابل للتعديل / Read-Only Quoted Block) --}}
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200/70 pb-2.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <span class="text-xs font-extrabold text-slate-700">المحتوى الأصلي للمراسلة (غير قابل للتعديل)</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500">
                            من: <strong>{{ $memo->creator->name ?? 'غير محدد' }}</strong> — في {{ $memo->created_at->format('Y-m-d H:i') }}
                        </span>
                    </div>

                    {{-- Original Text Body --}}
                    <div class="bg-white p-4 rounded-xl border border-slate-200/70 text-slate-700 text-sm leading-relaxed whitespace-pre-line font-medium select-text">
                        {{ $memo->content }}
                    </div>

                    {{-- Original Attachments --}}
                    @if ($memo->attachments->count() > 0)
                        <div class="pt-2">
                            <span class="text-xs font-bold text-slate-600 block mb-2">المرفقات والمستندات الأصلية ({{ $memo->attachments->count() }}):</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach ($memo->attachments as $att)
                                    <div class="p-2.5 rounded-xl bg-white border border-gray-200 flex items-center justify-between gap-2 shadow-2xs">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-800 flex items-center justify-center shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs font-bold text-slate-800 truncate" title="{{ $att->file_name }}">{{ $att->file_name }}</div>
                                                <div class="text-[10px] text-tertiary">{{ $att->formatted_size }}</div>
                                            </div>
                                        </div>
                                        <a href="{{ route('correspondence.memos.attachments.download', $att->id) }}"
                                           class="p-1 rounded-lg text-primary hover:bg-teal-50"
                                           title="تحميل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
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
                    <a href="{{ route('correspondence.memos.show', $memo->id) }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        إلغاء والعودة للمراسلة
                    </a>
                    <a href="{{ route('correspondence.memos.index') }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
                        سجل المراسلات
                    </a>
                </div>

                <button type="submit"
                        class="px-7 py-2.5 rounded-xl text-sm font-black text-white bg-secondary hover:bg-amber-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                    <span>تأكيد التحويل وإعادة التوجيه الآن</span>
                </button>
            </div>
        </form>
    </div>
</x-correspondence-layout>
