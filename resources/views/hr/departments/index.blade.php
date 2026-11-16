<x-hr-layout>
    <div x-data="{
        showModal: false,
        isEdit: false,
        modalTitle: '',
        formAction: '{{ route('hr.departments.store') }}',
        deptId: null,
        deptName: '',
        deptType: 'department',
        deptParentId: '',
        deptCode: '',
        deptManagerId: '',
        deptManagerTitle: '',
        deptDescription: '',
        deptOrder: 0,

        zoomLevel: 100,
        viewMode: 'chart', // 'chart' or 'list'
        draggedId: null,
        draggedName: '',

        zoomIn() {
            if (this.zoomLevel < 150) this.zoomLevel += 10;
        },
        zoomOut() {
            if (this.zoomLevel > 60) this.zoomLevel -= 10;
        },
        resetZoom() {
            this.zoomLevel = 100;
        },

        openCreate(parentId = '', defaultType = 'department', defaultTitle = '') {
            this.isEdit = false;
            this.modalTitle = defaultTitle || (parentId ? 'إضافة صندوق / وحدة متفرعة جديدة' : 'إضافة وحدة رئيسية');
            this.formAction = '{{ route('hr.departments.store') }}';
            this.deptId = null;
            this.deptName = '';
            this.deptType = defaultType;
            this.deptParentId = parentId;
            this.deptCode = '';
            this.deptManagerId = '';
            this.deptManagerTitle = '';
            this.deptDescription = '';
            this.deptOrder = 0;
            this.showModal = true;
        },

        openEdit(dept) {
            this.isEdit = true;
            this.modalTitle = 'تعديل بيانات الصندوق: ' + dept.name;
            this.formAction = '/hr/departments/' + dept.id;
            this.deptId = dept.id;
            this.deptName = dept.name;
            this.deptType = dept.type || 'department';
            this.deptParentId = dept.parent_id || '';
            this.deptCode = dept.code || '';
            this.deptManagerId = dept.manager_id || '';
            this.deptManagerTitle = dept.manager_title || '';
            this.deptDescription = dept.description || '';
            this.deptOrder = dept.order || 0;
            this.showModal = true;
        },

        onDragStart(e, id, name) {
            this.draggedId = id;
            this.draggedName = name;
            e.dataTransfer.setData('text/plain', id);
            e.dataTransfer.effectAllowed = 'move';
            e.currentTarget.classList.add('opacity-40', 'scale-95');
        },

        onDragEnd(e) {
            this.draggedId = null;
            e.currentTarget.classList.remove('opacity-40', 'scale-95');
        },

        onDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            e.currentTarget.classList.add('ring-4', 'ring-primary', 'bg-teal-50');
        },

        onDragLeave(e) {
            e.currentTarget.classList.remove('ring-4', 'ring-primary', 'bg-teal-50');
        },

        onDrop(e, targetId, targetName) {
            e.preventDefault();
            e.currentTarget.classList.remove('ring-4', 'ring-primary', 'bg-teal-50');

            if (!this.draggedId || this.draggedId == targetId) return;

            if (confirm(`هل تريد نقل (${this.draggedName}) لتصبح تابعة لـ (${targetName}) في الهيكلية الإدارية؟`)) {
                this.submitMove(this.draggedId, targetId);
            }
        },

        submitMove(sourceId, newParentId) {
            const token = document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content');
            fetch('/hr/departments/' + sourceId + '/move', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ parent_id: newParentId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'تعذر نقل الوحدة');
                }
            })
            .catch(err => {
                alert('حدث خطأ أثناء نقل الوحدة الإدارية');
            });
        }
    }" class="w-full space-y-6">

        {{-- Organogram Styles --}}
        <style>
            .organogram-canvas {
                direction: rtl;
                overflow-x: auto;
                min-height: 600px;
                background-color: #fafafa;
                background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
                background-size: 24px 24px;
            }
            @media print {
                .no-print { display: none !important; }
                .organogram-canvas { background: white !important; overflow: visible !important; }
                @page { size: landscape; margin: 5mm; }
            }
            .org-tree {
                display: flex;
                justify-content: center;
                position: relative;
                padding: 10px 0;
            }
            .org-tree ul {
                display: flex;
                justify-content: center;
                gap: 20px;
                position: relative;
                padding-top: 24px;
                margin: 0;
                list-style: none;
            }
            .org-tree li {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                padding: 24px 8px 0 8px;
            }
            /* Connector lines */
            .org-tree li::before, .org-tree li::after {
                content: '';
                position: absolute;
                top: 0;
                right: 50%;
                border-top: 2px solid #000;
                width: 50%;
                height: 24px;
            }
            .org-tree li::after {
                right: auto;
                left: 50%;
                border-right: 2px solid #000;
            }
            .org-tree li:only-child::after, .org-tree li:only-child::before {
                display: none;
            }
            .org-tree li:only-child {
                padding-top: 0;
            }
            .org-tree li:first-child::before, .org-tree li:last-child::after {
                border: 0 none;
            }
            .org-tree li:last-child::before {
                border-right: 2px solid #000;
                border-radius: 0 8px 0 0;
            }
            .org-tree li:first-child::after {
                border-radius: 8px 0 0 0;
            }
            .org-tree ul ul::before {
                content: '';
                position: absolute;
                top: 0;
                right: 50%;
                border-right: 2px solid #000;
                width: 0;
                height: 24px;
            }
        </style>

        {{-- Top Control Bar --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4 no-print">
            <div>
                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-rectangle-stack class="w-5 h-5 text-secondary" />
                    <span>الإطار العام للهيكلية التنظيمية المعتمدة لبلدية بيت حانون</span>
                </h2>
                <p class="text-xs text-tertiary mt-0.5">
                    مخطط هيكلي تفاعلي مع إمكانية السحب والإفلات (Drag & Drop) لتعديل التبعية وإعادة ترتيب المربعات
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-wrap">
                {{-- Zoom Controls --}}
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                    <button type="button" @click="zoomOut()" class="p-1.5 hover:bg-white rounded-lg transition-colors" title="تصغير">
                        <x-heroicon-s-minus class="w-3.5 h-3.5 text-slate-700" />
                    </button>
                    <span class="px-2 font-mono text-[11px] text-slate-800" x-text="zoomLevel + '%'"></span>
                    <button type="button" @click="zoomIn()" class="p-1.5 hover:bg-white rounded-lg transition-colors" title="تكبير">
                        <x-heroicon-s-plus class="w-3.5 h-3.5 text-slate-700" />
                    </button>
                    <button type="button" @click="resetZoom()" class="p-1.5 hover:bg-white rounded-lg transition-colors text-[10px] text-slate-500" title="إعادة ضبط">
                        100%
                    </button>
                </div>

                {{-- Mode Toggle --}}
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                    <button type="button" @click="viewMode = 'chart'"
                            :class="viewMode === 'chart' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                        <x-heroicon-s-rectangle-group class="w-3.5 h-3.5 text-secondary" />
                        <span>المخطط الهيكلي (الصورة)</span>
                    </button>
                    <button type="button" @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                        <x-heroicon-s-bars-3-bottom-right class="w-3.5 h-3.5 text-primary" />
                        <span>عرض القائمة</span>
                    </button>
                </div>

                {{-- Print Button --}}
                <button type="button" onclick="window.print()"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <x-heroicon-s-printer class="w-4 h-4" />
                    <span>طباعة الهيكلية</span>
                </button>

                {{-- Add Main Unit --}}
                <button type="button" @click="openCreate('', 'directorate', 'إضافة وحدة رئيسية في أعلى الهيكل')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-primary/20 cursor-pointer">
                    <x-heroicon-s-plus class="w-4 h-4 text-amber-300" />
                    <span>إضافة صندوق رئيسي</span>
                </button>
            </div>
        </div>

        {{-- Hint for Drag & Drop --}}
        <div class="bg-amber-50 border border-amber-200 text-amber-900 rounded-xl px-4 py-2.5 text-xs flex items-center justify-between no-print">
            <div class="flex items-center gap-2">
                <span class="text-base">💡</span>
                <span class="font-medium">
                    <strong>ميزة السحب والإفلات التفاعلية (Drag & Drop):</strong> يمكنك سحب أي صندوق بالماوس وإفلاته فوق صندوق آخر لتغيير تبعيته الإدارية ومكانه في الشجرة تلقائياً.
                </span>
            </div>
            <span class="text-[11px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded">
                انقر على أيقونة ✏️ لتعديل بيانات الصندوق
            </span>
        </div>

        {{-- VIEW 1: INTERACTIVE ORGANOGRAM CANVAS (طريقة الصورة المرفقة) --}}
        <div x-show="viewMode === 'chart'" class="bg-white rounded-2xl border-2 border-slate-300 shadow-sm p-6 organogram-canvas">

            {{-- Organogram Document Title Header (كما في الصورة) --}}
            <div class="text-center mb-8 pb-4 border-b-2 border-slate-300 max-w-4xl mx-auto">
                <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-wide underline underline-offset-8">
                    الإطار العام للهيكلية التنظيمية المعتمدة لبلدية بيت حانون
                </h1>
                <p class="text-xs font-bold text-slate-500 mt-2">
                    دولة فلسطين • وزارة الحكم المحلي • الهيكل التنظيمي المعتمد
                </p>
            </div>

            {{-- Zoom Container --}}
            <div :style="'transform: scale(' + (zoomLevel / 100) + '); transform-origin: top center; transition: transform 0.2s ease;'" class="min-w-fit mx-auto pb-16">

                <div class="org-tree">
                    <ul class="flex flex-col items-center">
                        @foreach($rootDepartments as $root)
                            <li class="w-full flex flex-col items-center">

                                {{-- Level 0 Box: المجلس البلدي (Peach / Orange Box - كما في الصورة) --}}
                                <div class="relative group"
                                     draggable="true"
                                     @dragstart="onDragStart($event, {{ $root->id }}, '{{ $root->name }}')"
                                     @dragend="onDragEnd($event)"
                                     @dragover="onDragOver($event)"
                                     @dragleave="onDragLeave($event)"
                                     @drop="onDrop($event, {{ $root->id }}, '{{ $root->name }}')">

                                    <div class="w-56 bg-[#fed7aa] border-2 border-[#ea580c] text-[#7c2d12] rounded-lg p-3 text-center shadow-md relative transition-all duration-200 hover:shadow-lg">
                                        <div class="font-black text-sm text-[#7c2d12]">
                                            المجلس البلدي
                                        </div>
                                        <div class="text-[11px] font-bold text-slate-700 mt-0.5">
                                            الهيئة القيادية العليا
                                        </div>

                                        {{-- Edit / Actions Floating Overlay --}}
                                        <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-300 shadow-md z-20 no-print">
                                            <button type="button" @click="openEdit({{ json_encode($root) }})" class="p-1 hover:bg-slate-100 rounded text-slate-700" title="تعديل">
                                                <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                            </button>
                                            <button type="button" @click="openCreate({{ $root->id }}, 'department', 'إضافة فرع تابع للمجلس البلدي')" class="p-1 hover:bg-slate-100 rounded text-emerald-700" title="إضافة تفريع">
                                                <x-heroicon-s-plus class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Vertical Stem from Council to Mayor --}}
                                <div class="w-0.5 h-8 bg-black"></div>

                                {{-- Lateral Staff / Side Branches attached near Mayor (الرقابة الداخلية) --}}
                                <div class="relative flex items-center justify-center w-full max-w-xl">

                                    {{-- Left side staff box: الرقابة الداخلية (كما في الصورة) --}}
                                    @php
                                        $internalAudit = $root->children->firstWhere('code', 'AUDIT') ?? $root->children->first(fn($c) => str_contains($c->name, 'رقابة'));
                                    @endphp
                                    <div class="absolute right-0 flex items-center">
                                        <div class="w-40 bg-white border-2 border-black rounded-lg p-2 text-center shadow-xs text-xs font-bold text-slate-900 group relative">
                                            <span>الرقابة الداخلية</span>
                                            @if($internalAudit)
                                                <div class="text-[10px] text-slate-500 font-medium">{{ $internalAudit->manager_title ?: $internalAudit->manager?->name }}</div>
                                            @endif
                                        </div>
                                        <div class="w-8 h-0.5 bg-black"></div>
                                    </div>

                                    {{-- Level 1 Box: رئيس البلدية (Sky Blue Box - كما في الصورة) --}}
                                    <div class="relative group z-10"
                                         draggable="true"
                                         @dragstart="onDragStart($event, {{ $root->id }}, 'رئيس البلدية')"
                                         @dragend="onDragEnd($event)"
                                         @dragover="onDragOver($event)"
                                         @dragleave="onDragLeave($event)"
                                         @drop="onDrop($event, {{ $root->id }}, 'رئيس البلدية')">

                                        <div class="w-60 bg-[#bfdbfe] border-2 border-[#2563eb] text-[#1e3a8a] rounded-lg p-3.5 text-center shadow-md relative transition-all duration-200 hover:shadow-lg">
                                            <div class="font-black text-sm text-[#1e3a8a]">
                                                رئيس البلدية
                                            </div>
                                            <div class="text-sm font-black text-slate-900 mt-1">
                                                د. عماد عدوان
                                            </div>

                                            {{-- Edit Floating Overlay --}}
                                            <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-300 shadow-md z-20 no-print">
                                                <button type="button" @click="openEdit({{ json_encode($root) }})" class="p-1 hover:bg-slate-100 rounded text-slate-700" title="تعديل">
                                                    <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                                </button>
                                                <button type="button" @click="openCreate({{ $root->id }}, 'directorate', 'إضافة دائرة رئيسية')" class="p-1 hover:bg-slate-100 rounded text-emerald-700" title="إضافة دائرة">
                                                    <x-heroicon-s-plus class="w-3.5 h-3.5" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right side staff box: مكتب المحاماة / المستشار القانوني (كما في الصورة باللون الأصفر والحدود المتقطعة) --}}
                                    @php
                                        $legalOffice = $root->children->first(fn($c) => str_contains($c->name, 'قانون') || str_contains($c->name, 'محاماة'));
                                    @endphp
                                    <div class="absolute left-0 flex items-center">
                                        <div class="w-8 h-0.5 bg-black"></div>
                                        <div class="w-40 bg-[#fef9c3] border-2 border-dashed border-[#ca8a04] text-[#713f12] rounded-lg p-2 text-center shadow-xs text-xs font-bold group relative">
                                            <div>مكتب المحاماة</div>
                                            <div class="text-[10px] font-black text-[#854d0e] mt-0.5">
                                                {{ $legalOffice?->manager_title ?: ($legalOffice?->manager?->name ?: 'المستشار القانوني') }}
                                            </div>
                                            @if($legalOffice)
                                                <div class="absolute -top-2 -right-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-300 shadow no-print">
                                                    <button type="button" @click="openEdit({{ json_encode($legalOffice) }})" class="p-0.5 text-slate-700">
                                                        <x-heroicon-s-pencil-square class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                {{-- Vertical Stem continuing down --}}
                                <div class="w-0.5 h-6 bg-black"></div>

                                {{-- Directly attached intermediate boxes along central trunk: سكرتير رئيس البلدية + العلاقات العامة (كما في الصورة) --}}
                                <div class="flex flex-col items-center space-y-3">
                                    {{-- سكرتير رئيس البلدية --}}
                                    @php
                                        $secretary = $root->children->first(fn($c) => str_contains($c->name, 'سكرتار') || str_contains($c->name, 'مكتب الرئيس'));
                                    @endphp
                                    <div class="w-48 bg-white border-2 border-black rounded-lg p-2 text-center shadow-xs text-xs font-bold text-slate-900 group relative">
                                        <div>سكرتير رئيس البلدية</div>
                                        @if($secretary && $secretary->manager_title)
                                            <div class="text-[10px] text-slate-600 font-medium">{{ $secretary->manager_title }}</div>
                                        @endif
                                        @if($secretary)
                                            <div class="absolute -top-2 -left-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-300 shadow no-print">
                                                <button type="button" @click="openEdit({{ json_encode($secretary) }})" class="p-0.5 text-slate-700">
                                                    <x-heroicon-s-pencil-square class="w-3 h-3" />
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="w-0.5 h-3 bg-black"></div>

                                    {{-- العلاقات العامة وقلم الجمهور --}}
                                    @php
                                        $publicRelations = $root->children->first(fn($c) => str_contains($c->name, 'علاقات') || str_contains($c->name, 'إعلام') || str_contains($c->name, 'جمهور'));
                                    @endphp
                                    <div class="w-48 bg-white border-2 border-black rounded-lg p-2 text-center shadow-xs text-xs font-bold text-slate-900 group relative">
                                        <div>العلاقات العامة وقلم الجمهور</div>
                                        @if($publicRelations && $publicRelations->manager_title)
                                            <div class="text-[10px] text-slate-600 font-medium">{{ $publicRelations->manager_title }}</div>
                                        @endif
                                        @if($publicRelations)
                                            <div class="absolute -top-2 -left-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-300 shadow no-print">
                                                <button type="button" @click="openEdit({{ json_encode($publicRelations) }})" class="p-0.5 text-slate-700">
                                                    <x-heroicon-s-pencil-square class="w-3 h-3" />
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Main Branches: Horizontal Tier of Directorates & Departments (كما في الصورة) --}}
                                @php
                                    // Exclude staff offices already shown on the vertical trunk
                                    $mainBranches = $root->children->filter(function($c) {
                                        $n = $c->name;
                                        return !str_contains($n, 'رقابة') && !str_contains($n, 'محاماة') && !str_contains($n, 'قانون') && !str_contains($n, 'سكرتار') && !str_contains($n, 'علاقات');
                                    });
                                @endphp

                                @if($mainBranches->count() > 0)
                                    <ul>
                                        @foreach($mainBranches as $dept)
                                            <li>
                                                {{-- Level 2 Department Box: White/Yellow Box with Manager Name (كما في الصورة) --}}
                                                <div class="relative group"
                                                     draggable="true"
                                                     @dragstart="onDragStart($event, {{ $dept->id }}, '{{ $dept->name }}')"
                                                     @dragend="onDragEnd($event)"
                                                     @dragover="onDragOver($event)"
                                                     @dragleave="onDragLeave($event)"
                                                     @drop="onDrop($event, {{ $dept->id }}, '{{ $dept->name }}')">

                                                    <div class="w-52 {{ $dept->manager_title || $dept->manager ? 'bg-[#fef9c3] border-[#ca8a04]' : 'bg-white border-black' }} border-2 rounded-lg p-2.5 text-center shadow-sm relative transition-all duration-200 hover:shadow-md cursor-grab active:cursor-grabbing">
                                                        <div class="font-black text-xs text-slate-900">
                                                            {{ $dept->name }}
                                                        </div>
                                                        @if($dept->manager_title || $dept->manager)
                                                            <div class="text-[11px] font-bold text-[#854d0e] mt-1 border-t border-slate-200 pt-1">
                                                                {{ $dept->manager_title ?: $dept->manager?->name }}
                                                            </div>
                                                        @endif

                                                        {{-- Edit / Actions Overlay --}}
                                                        <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-300 shadow-md z-20 no-print">
                                                            <button type="button" @click="openEdit({{ json_encode($dept) }})" class="p-1 hover:bg-slate-100 rounded text-slate-700" title="تعديل">
                                                                <x-heroicon-s-pencil-square class="w-3.5 h-3.5" />
                                                            </button>
                                                            <button type="button" @click="openCreate({{ $dept->id }}, 'section', 'إضافة شعبة تابعة لـ {{ $dept->name }}')" class="p-1 hover:bg-slate-100 rounded text-emerald-700" title="إضافة شعبة">
                                                                <x-heroicon-s-plus class="w-3.5 h-3.5" />
                                                            </button>
                                                            @if($dept->children->isEmpty() && $dept->hrEmployees->isEmpty())
                                                                <form method="POST" action="{{ route('hr.departments.destroy', $dept->id) }}" onsubmit="return confirm('تأكيد حذف هذا الصندوق؟')" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="p-1 hover:bg-rose-50 rounded text-rose-600" title="حذف">
                                                                        <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Level 3: Sections (الشُعب باللون الأزرق الفاتح - كما في الصورة) --}}
                                                @if($dept->children->count() > 0)
                                                    <ul>
                                                        @foreach($dept->children as $section)
                                                            <li>
                                                                {{-- Section Box (Light Blue / Yellow if named) --}}
                                                                <div class="relative group"
                                                                     draggable="true"
                                                                     @dragstart="onDragStart($event, {{ $section->id }}, '{{ $section->name }}')"
                                                                     @dragend="onDragEnd($event)"
                                                                     @dragover="onDragOver($event)"
                                                                     @dragleave="onDragLeave($event)"
                                                                     @drop="onDrop($event, {{ $section->id }}, '{{ $section->name }}')">

                                                                    <div class="w-44 {{ $section->manager_title ? 'bg-[#fef9c3] border-[#ca8a04]' : 'bg-[#e0f2fe] border-[#0284c7]' }} border-2 rounded-lg p-2 text-center shadow-xs relative transition-all duration-200 hover:shadow-md cursor-grab active:cursor-grabbing">
                                                                        <div class="font-extrabold text-[11px] text-slate-900">
                                                                            {{ $section->name }}
                                                                        </div>
                                                                        @if($section->manager_title)
                                                                            <div class="text-[10px] font-bold text-[#854d0e] mt-0.5">
                                                                                {{ $section->manager_title }}
                                                                            </div>
                                                                        @endif

                                                                        {{-- Edit Overlay --}}
                                                                        <div class="absolute -top-2.5 -left-2.5 hidden group-hover:flex items-center gap-0.5 bg-white p-0.5 rounded border border-slate-300 shadow z-20 no-print">
                                                                            <button type="button" @click="openEdit({{ json_encode($section) }})" class="p-0.5 text-slate-700" title="تعديل">
                                                                                <x-heroicon-s-pencil-square class="w-3 h-3" />
                                                                            </button>
                                                                            <button type="button" @click="openCreate({{ $section->id }}, 'unit', 'إضافة مسمى/وحدة تحت {{ $section->name }}')" class="p-0.5 text-emerald-700" title="إضافة">
                                                                                <x-heroicon-s-plus class="w-3 h-3" />
                                                                            </button>
                                                                            @if($section->children->isEmpty() && $section->hrEmployees->isEmpty())
                                                                                <form method="POST" action="{{ route('hr.departments.destroy', $section->id) }}" onsubmit="return confirm('تأكيد الحذف؟')" class="inline">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="p-0.5 text-rose-600" title="حذف">
                                                                                        <x-heroicon-s-trash class="w-3 h-3" />
                                                                                    </button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Level 4 & 5: Sub-units & Employees (الموظفون والكوادر في عمود رأسي - كما في الصورة) --}}
                                                                @php
                                                                    $hasChildren = $section->children->count() > 0;
                                                                    $hasEmployees = $section->hrEmployees->count() > 0;
                                                                @endphp

                                                                @if($hasChildren || $hasEmployees)
                                                                    <div class="flex flex-col items-center mt-3 pt-3 border-t-2 border-black w-full space-y-1.5">
                                                                        {{-- Sub-units --}}
                                                                        @foreach($section->children as $sub)
                                                                            <div class="w-36 bg-white border border-slate-400 rounded p-1.5 text-center text-[10px] font-bold text-slate-800 shadow-2xs group relative"
                                                                                 draggable="true"
                                                                                 @dragstart="onDragStart($event, {{ $sub->id }}, '{{ $sub->name }}')"
                                                                                 @dragend="onDragEnd($event)"
                                                                                 @dragover="onDragOver($event)"
                                                                                 @dragleave="onDragLeave($event)"
                                                                                 @drop="onDrop($event, {{ $sub->id }}, '{{ $sub->name }}')">
                                                                                <div>{{ $sub->name }}</div>
                                                                                @if($sub->manager_title)
                                                                                    <div class="text-[9px] text-[#854d0e] font-semibold">{{ $sub->manager_title }}</div>
                                                                                @endif
                                                                                <div class="absolute -top-1.5 -left-1.5 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-300 shadow no-print">
                                                                                    <button type="button" @click="openEdit({{ json_encode($sub) }})" class="p-0.5 text-slate-700">
                                                                                        <x-heroicon-s-pencil-square class="w-2.5 h-2.5" />
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach

                                                                        {{-- Staff Employees registered under this Section --}}
                                                                        @foreach($section->hrEmployees as $emp)
                                                                            <div class="w-36 bg-[#fef9c3] border border-[#eab308] rounded p-1.5 text-center text-[10px] text-[#713f12] shadow-2xs">
                                                                                <div class="font-bold truncate">{{ $emp->job_title ?: 'موظف' }}</div>
                                                                                <div class="text-[9px] font-black text-slate-900 mt-0.5 truncate">{{ $emp->full_name }}</div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif

                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            {{-- Legend (دليل ألوان المخطط كما في الصورة المرفقة) --}}
            <div class="mt-8 pt-4 border-t-2 border-slate-200 flex flex-wrap items-center justify-center gap-6 text-xs font-bold no-print">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-4 rounded bg-[#fed7aa] border-2 border-[#ea580c] inline-block shadow-2xs"></span>
                    <span class="text-slate-800">المجلس البلدي (القيادة العليا)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-4 rounded bg-[#bfdbfe] border-2 border-[#2563eb] inline-block shadow-2xs"></span>
                    <span class="text-slate-800">رئيس البلدية (د. عماد عدوان)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-4 rounded bg-[#fef9c3] border-2 border-dashed border-[#ca8a04] inline-block shadow-2xs"></span>
                    <span class="text-slate-800">مكتب المحاماة والمسؤولون المعينون</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-4 rounded bg-white border-2 border-black inline-block shadow-2xs"></span>
                    <span class="text-slate-800">الدوائر والأقسام والوحدات التنظيمية</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-4 rounded bg-[#e0f2fe] border-2 border-[#0284c7] inline-block shadow-2xs"></span>
                    <span class="text-slate-800">الشُعب التنفيذية والفنية</span>
                </div>
            </div>

        </div>

        {{-- VIEW 2: DETAILED LIST VIEW (عرض القائمة التحريرية) --}}
        <div x-show="viewMode === 'list'" style="display: none;" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-800">قائمة الوحدات الإدارية والشُعب</h3>
                <span class="text-xs text-slate-500 font-bold">إجمالي: {{ $stats['total'] }} وحدة مسجلة</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <th class="py-3 px-4">اسم الوحدة / القسم</th>
                            <th class="py-3 px-4">النوع الإداري</th>
                            <th class="py-3 px-4">الوحدة الأب (التبعية)</th>
                            <th class="py-3 px-4">المسؤول / المسمى</th>
                            <th class="py-3 px-4">الكادر المسجل</th>
                            <th class="py-3 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($allDepartments as $d)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-black text-slate-900">
                                    {{ $d->name }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $d->type_arabic }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-bold">
                                    {{ $d->parent?->name ?? 'رأس الهيكل (المجلس/الرئيس)' }}
                                </td>
                                <td class="py-3 px-4 text-slate-800">
                                    {{ $d->manager_title ?: ($d->manager?->name ?? '—') }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-800">
                                    {{ $d->hrEmployees()->count() }} موظف
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" @click="openEdit({{ json_encode($d) }})"
                                                class="px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                            تعديل
                                        </button>
                                        <button type="button" @click="openCreate({{ $d->id }}, 'section', 'إضافة تفريع تحت {{ $d->name }}')"
                                                class="px-2 py-1 rounded bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold transition-colors">
                                            + تفريع
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add / Edit Department Modal --}}
        <div x-show="showModal" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div @click.away="showModal = false"
                 class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800" x-text="modalTitle"></h3>
                    <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <x-heroicon-s-x-mark class="w-5 h-5" />
                    </button>
                </div>

                <form :action="formAction" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم الصندوق / الوحدة الإدارية <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="deptName" required placeholder="مثال: قسم المشاريع والتنظيم، شعبة الصيانة..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">نوع المربع الإداري <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="deptType" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold">
                                @foreach(\App\Models\Correspondence\Department::TYPES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الوحدة الأب (التبعية)</label>
                            <select name="parent_id" x-model="deptParentId" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">-- رأس الهيكل (بدون أب) --</option>
                                @foreach($allDepartments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->type_arabic }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">المسمى الوظيفي للمسؤول</label>
                            <input type="text" name="manager_title" x-model="deptManagerTitle" placeholder="مثال: د. عماد عدوان، طارق ابوريدة..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الرمز الإداري (Code)</label>
                            <input type="text" name="code" x-model="deptCode" placeholder="MAYOR, ENG, FIN..."
                                   class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">المستخدم المرتبط (اختياري)</label>
                        <select name="manager_id" x-model="deptManagerId" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="">-- بدون مستخدم نظام محدد --</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الوصف والمهام</label>
                        <textarea name="description" x-model="deptDescription" rows="2" placeholder="المهام العامة للوحدة..."
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 cursor-pointer">
                            إلغاء
                        </button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-xs cursor-pointer">
                            حفظ الصندوق
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-hr-layout>
