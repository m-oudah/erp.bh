<x-hr-layout>
    <div x-data="deptHierarchyApp()" class="w-full space-y-4">

        {{-- Styles --}}
        <style>
            .organogram-canvas {
                direction: rtl;
                overflow: auto;
                min-height: 500px;
                background-color: #f8fafc;
                background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
                background-size: 22px 22px;
            }
            .org-node { transition: all 0.2s; }
            .drag-target-active {
                outline: 3px dashed #0d9488 !important;
                background-color: #f0fdfa !important;
                transform: scale(1.04);
                z-index: 50;
                position: relative;
            }

            /* Tree connector lines */
            .tree-children {
                display: flex;
                justify-content: center;
                gap: 0;
                position: relative;
                padding-top: 28px;
            }
            .tree-children::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 28px;
                background: #334155;
            }
            .tree-children-row {
                display: flex;
                justify-content: center;
                gap: 16px;
                position: relative;
                padding-top: 0;
            }
            /* Horizontal bar above children */
            .tree-children-row::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                left: 0;
                height: 2px;
                background: #334155;
            }
            .tree-child-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                padding: 0 8px;
            }
            .tree-child-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 28px;
                background: #334155;
            }
            .tree-child-item > .org-node {
                margin-top: 28px;
            }
            /* For single child: no horizontal bar */
            .tree-children-row:has(.tree-child-item:only-child)::before {
                display: none;
            }
            .tree-children-row .tree-child-item:only-child::before {
                display: block;
            }

            /* Sub-children lines */
            .tree-sub-children {
                display: flex;
                justify-content: center;
                gap: 10px;
                position: relative;
                padding-top: 28px;
            }
            .tree-sub-children::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 28px;
                background: #64748b;
            }
            .tree-sub-row {
                display: flex;
                justify-content: center;
                gap: 10px;
                position: relative;
            }
            .tree-sub-row::before {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                left: 0;
                height: 2px;
                background: #64748b;
            }
            .tree-sub-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                padding: 0 4px;
            }
            .tree-sub-item::before {
                content: '';
                position: absolute;
                top: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 22px;
                background: #64748b;
            }
            .tree-sub-item > .org-node { margin-top: 22px; }
            .tree-sub-row:has(.tree-sub-item:only-child)::before { display: none; }

            @media print {
                .no-print { display: none !important; }
                .organogram-canvas { background: white !important; overflow: visible !important; min-height: unset; }
                @page { size: landscape; margin: 6mm; }
                body { font-size: 10px; }
                .org-node { break-inside: avoid; }
            }
        </style>

        {{-- Top Control Bar --}}
        <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4 no-print">
            <div>
                <h2 class="text-base font-black text-slate-800 flex items-center gap-2">
                    <x-heroicon-s-rectangle-stack class="w-5 h-5 text-secondary" />
                    <span>الهيكلية التنظيمية المعتمدة لبلدية بيت حانون</span>
                </h2>
                <p class="text-[11px] text-tertiary mt-0.5">
                    مخطط هيكلي تفاعلي — اسحب أي صندوق وأفلته فوق صندوق آخر لتغيير تبعيته الإدارية
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Zoom Controls --}}
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                    <button type="button" @click="zoomOut()" class="p-1.5 hover:bg-white rounded-lg transition-colors" title="تصغير">
                        <x-heroicon-s-minus class="w-3.5 h-3.5 text-slate-700" />
                    </button>
                    <span class="px-2 font-mono text-[11px] text-slate-800 min-w-[3rem] text-center" x-text="zoomLevel + '%'"></span>
                    <button type="button" @click="zoomIn()" class="p-1.5 hover:bg-white rounded-lg transition-colors" title="تكبير">
                        <x-heroicon-s-plus class="w-3.5 h-3.5 text-slate-700" />
                    </button>
                    <button type="button" @click="resetZoom()" class="p-1.5 hover:bg-white rounded-lg transition-colors text-[10px] text-slate-500">↺</button>
                </div>

                {{-- Mode Toggle --}}
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-bold">
                    <button type="button" @click="viewMode = 'chart'"
                            :class="viewMode === 'chart' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                        <x-heroicon-s-rectangle-group class="w-3.5 h-3.5 text-secondary" />
                        <span>المخطط الهيكلي</span>
                    </button>
                    <button type="button" @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500'"
                            class="px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                        <x-heroicon-s-bars-3-bottom-right class="w-3.5 h-3.5 text-primary" />
                        <span>القائمة</span>
                    </button>
                </div>

                <button type="button" onclick="window.print()"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors flex items-center gap-1.5">
                    <x-heroicon-s-printer class="w-4 h-4" />
                    <span>طباعة</span>
                </button>

                <button type="button" @click="openCreate('', 'directorate', 'إضافة وحدة رئيسية')"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-xl text-xs font-bold transition-all shadow-sm cursor-pointer">
                    <x-heroicon-s-plus class="w-4 h-4 text-amber-300" />
                    <span>إضافة وحدة</span>
                </button>
            </div>
        </div>

        {{-- Drag & Drop Hint --}}
        <div class="bg-teal-50 border border-teal-200 text-teal-900 rounded-xl px-4 py-2 text-xs flex items-center gap-3 no-print">
            <span class="text-base shrink-0">💡</span>
            <span class="font-medium">
                <strong>Drag & Drop:</strong> اسحب أي مربع بالماوس وأفلته فوق مربع آخر لتغيير تبعيته في الهيكلية. انقر على <strong>✏️</strong> لتعديل بيانات الصندوق أو <strong>＋</strong> لإضافة وحدة فرعية.
            </span>
        </div>

        {{-- ===== VIEW 1: INTERACTIVE ORGANOGRAM CHART ===== --}}
        <div x-show="viewMode === 'chart'" class="bg-white rounded-2xl border-2 border-slate-200 shadow-sm organogram-canvas p-6">

            {{-- Document Title --}}
            <div class="text-center mb-8 pb-5 border-b-2 border-slate-300">
                <h1 class="text-xl font-black text-slate-900 tracking-wide">
                    الإطار العام للهيكلية التنظيمية المعتمدة لبلدية بيت حانون
                </h1>
                <p class="text-xs font-bold text-slate-500 mt-1.5">دولة فلسطين • وزارة الحكم المحلي • 2026</p>
            </div>

            {{-- Zoom Container --}}
            <div :style="'transform: scale(' + (zoomLevel / 100) + '); transform-origin: top center; transition: transform 0.25s ease;'" class="min-w-fit mx-auto pb-10">

                @foreach($rootDepartments as $root)
                    {{-- ============ ROOT NODE: المجلس البلدي ============ --}}
                    <div class="flex flex-col items-center">

                        {{-- Council Box (peach) --}}
                        <div class="org-node relative group cursor-grab active:cursor-grabbing select-none"
                             draggable="true"
                             @dragstart="onDragStart($event, {{ $root->id }}, '{{ addslashes($root->name) }}')"
                             @dragend="onDragEnd($event)"
                             @dragover.prevent="onDragOver($event, {{ $root->id }})"
                             @dragleave="onDragLeave($event)"
                             @drop="onDrop($event, {{ $root->id }}, '{{ addslashes($root->name) }}')">
                            <div class="bg-orange-100 border-2 border-orange-400 text-orange-900 rounded-xl px-5 py-2.5 text-center shadow-md w-64">
                                <div class="text-xs font-bold text-orange-600 mb-0.5">المجلس البلدي</div>
                                <div class="text-sm font-black">{{ $root->name }}</div>
                            </div>
                            {{-- Hover actions --}}
                            <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-300 shadow-lg z-30 no-print">
                                <button type="button" @click.stop="openEdit({{ json_encode($root) }})" class="p-1 hover:bg-slate-100 rounded-md" title="تعديل">
                                    <x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-blue-600" />
                                </button>
                                <button type="button" @click.stop="openCreate({{ $root->id }}, 'directorate', 'إضافة دائرة')" class="p-1 hover:bg-emerald-50 rounded-md" title="إضافة دائرة">
                                    <x-heroicon-s-plus class="w-3.5 h-3.5 text-emerald-600" />
                                </button>
                            </div>
                        </div>

                        {{-- Stem Down --}}
                        <div class="w-0.5 h-7 bg-slate-700"></div>

                        {{-- Rais Box (blue) with side staff boxes --}}
                        <div class="relative flex items-center justify-center">
                            {{-- Right side: سكرتاريا / مكتب المحاماة --}}
                            @php
                                $sideDiwan = $root->children->first(fn($c) => str_contains($c->name, 'ديوان') || str_contains($c->name, 'سكرتار') || str_contains($c->name, 'مكتب الرئيس'));
                            @endphp
                            @if($sideDiwan)
                                <div class="flex items-center ml-4">
                                    <div class="org-node relative group cursor-grab select-none"
                                         draggable="true"
                                         @dragstart="onDragStart($event, {{ $sideDiwan->id }}, '{{ addslashes($sideDiwan->name) }}')"
                                         @dragend="onDragEnd($event)"
                                         @dragover.prevent="onDragOver($event, {{ $sideDiwan->id }})"
                                         @dragleave="onDragLeave($event)"
                                         @drop="onDrop($event, {{ $sideDiwan->id }}, '{{ addslashes($sideDiwan->name) }}')">
                                        <div class="bg-white border-2 border-slate-500 rounded-xl px-3 py-2 text-center w-44 shadow-sm">
                                            <div class="text-[11px] font-black text-slate-800">{{ $sideDiwan->name }}</div>
                                            @if($sideDiwan->manager_title)
                                                <div class="text-[10px] font-semibold text-slate-600 mt-0.5">{{ $sideDiwan->manager_title }}</div>
                                            @endif
                                        </div>
                                        <div class="absolute -top-2 -left-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-200 shadow no-print gap-0.5">
                                            <button type="button" @click.stop="openEdit({{ json_encode($sideDiwan) }})" class="p-0.5"><x-heroicon-s-pencil-square class="w-3 h-3 text-blue-600" /></button>
                                        </div>
                                    </div>
                                    <div class="w-10 h-0.5 bg-slate-500"></div>
                                </div>
                            @endif

                            {{-- Mayor Box (blue highlight) --}}
                            <div class="org-node relative group cursor-grab active:cursor-grabbing select-none z-10"
                                 draggable="true"
                                 @dragstart="onDragStart($event, {{ $root->id }}, 'رئيس البلدية')"
                                 @dragend="onDragEnd($event)"
                                 @dragover.prevent="onDragOver($event, {{ $root->id }})"
                                 @dragleave="onDragLeave($event)"
                                 @drop="onDrop($event, {{ $root->id }}, 'رئيس البلدية')">
                                <div class="bg-blue-100 border-2 border-blue-500 text-blue-900 rounded-xl px-6 py-3 text-center shadow-lg w-72">
                                    <div class="text-xs font-bold text-blue-600 mb-0.5">رئيس البلدية</div>
                                    <div class="text-base font-black">د. عماد عدوان</div>
                                    @if($root->manager_title && !str_contains($root->manager_title, 'عدوان'))
                                        <div class="text-[10px] text-blue-700 mt-0.5">{{ $root->manager_title }}</div>
                                    @endif
                                </div>
                                <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-lg border border-slate-300 shadow-lg z-30 no-print">
                                    <button type="button" @click.stop="openEdit({{ json_encode($root) }})" class="p-1 hover:bg-slate-100 rounded-md"><x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-blue-600" /></button>
                                    <button type="button" @click.stop="openCreate({{ $root->id }}, 'directorate', 'إضافة دائرة')" class="p-1 hover:bg-emerald-50 rounded-md"><x-heroicon-s-plus class="w-3.5 h-3.5 text-emerald-600" /></button>
                                </div>
                            </div>

                            {{-- Left side: العلاقات العامة --}}
                            @php
                                $sidePublic = $root->children->first(fn($c) => str_contains($c->name, 'علاقات') || str_contains($c->name, 'جمهور'));
                            @endphp
                            @if($sidePublic)
                                <div class="flex items-center mr-4">
                                    <div class="w-10 h-0.5 bg-slate-500"></div>
                                    <div class="org-node relative group cursor-grab select-none"
                                         draggable="true"
                                         @dragstart="onDragStart($event, {{ $sidePublic->id }}, '{{ addslashes($sidePublic->name) }}')"
                                         @dragend="onDragEnd($event)"
                                         @dragover.prevent="onDragOver($event, {{ $sidePublic->id }})"
                                         @dragleave="onDragLeave($event)"
                                         @drop="onDrop($event, {{ $sidePublic->id }}, '{{ addslashes($sidePublic->name) }}')">
                                        <div class="bg-yellow-50 border-2 border-dashed border-yellow-500 rounded-xl px-3 py-2 text-center w-44 shadow-sm">
                                            <div class="text-[11px] font-black text-slate-800">{{ $sidePublic->name }}</div>
                                            @if($sidePublic->manager_title)
                                                <div class="text-[10px] font-semibold text-amber-700 mt-0.5">{{ $sidePublic->manager_title }}</div>
                                            @endif
                                        </div>
                                        <div class="absolute -top-2 -right-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-200 shadow no-print gap-0.5">
                                            <button type="button" @click.stop="openEdit({{ json_encode($sidePublic) }})" class="p-0.5"><x-heroicon-s-pencil-square class="w-3 h-3 text-blue-600" /></button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Main Stem Down --}}
                        <div class="w-0.5 h-7 bg-slate-700"></div>

                        {{-- ============ LEVEL 1: MAIN DIRECTORATES ============ --}}
                        @php
                            $excludeIds = collect([$sideDiwan?->id, $sidePublic?->id])->filter()->all();
                            $mainDepts = $root->children->whereNotIn('id', $excludeIds)->values();
                        @endphp

                        @if($mainDepts->count() > 0)
                            <div class="tree-children w-full">
                                {{-- Horizontal bar --}}
                                <div class="relative w-full">
                                    <div class="tree-children-row">
                                        @foreach($mainDepts as $dept)
                                            <div class="tree-child-item">
                                                {{-- Directorate Box (yellow border) --}}
                                                <div class="org-node relative group cursor-grab active:cursor-grabbing select-none"
                                                     draggable="true"
                                                     @dragstart="onDragStart($event, {{ $dept->id }}, '{{ addslashes($dept->name) }}')"
                                                     @dragend="onDragEnd($event)"
                                                     @dragover.prevent="onDragOver($event, {{ $dept->id }})"
                                                     @dragleave="onDragLeave($event)"
                                                     @drop="onDrop($event, {{ $dept->id }}, '{{ addslashes($dept->name) }}')">
                                                    <div class="bg-yellow-50 border-2 border-amber-500 rounded-xl px-3.5 py-2.5 text-center shadow-md w-52 hover:shadow-lg transition-shadow">
                                                        <div class="text-xs font-black text-slate-900 leading-tight">{{ $dept->name }}</div>
                                                        @if($dept->manager_title)
                                                            <div class="text-[10px] font-bold text-amber-700 mt-1 pt-1 border-t border-amber-200">
                                                                {{ $dept->manager_title }}
                                                            </div>
                                                        @endif
                                                        @if($dept->hr_employees_count > 0)
                                                            <div class="text-[9px] text-slate-500 font-mono mt-0.5">{{ $dept->hr_employees_count }} موظف</div>
                                                        @endif
                                                    </div>
                                                    {{-- Hover actions --}}
                                                    <div class="absolute -top-3 -left-3 hidden group-hover:flex items-center gap-0.5 bg-white p-1 rounded-lg border border-slate-300 shadow-lg z-30 no-print">
                                                        <button type="button" @click.stop="openEdit({{ json_encode($dept) }})" class="p-1 hover:bg-blue-50 rounded-md" title="تعديل"><x-heroicon-s-pencil-square class="w-3.5 h-3.5 text-blue-600" /></button>
                                                        <button type="button" @click.stop="openCreate({{ $dept->id }}, 'section', 'إضافة شعبة تحت ' + '{{ addslashes($dept->name) }}')" class="p-1 hover:bg-emerald-50 rounded-md" title="إضافة شعبة"><x-heroicon-s-plus class="w-3.5 h-3.5 text-emerald-600" /></button>
                                                        @if($dept->children->isEmpty() && $dept->hr_employees_count == 0)
                                                            <form method="POST" action="{{ route('hr.departments.destroy', $dept->id) }}" onsubmit="return confirm('تأكيد حذف هذا الصندوق؟')" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="p-1 hover:bg-rose-50 rounded-md" title="حذف"><x-heroicon-s-trash class="w-3.5 h-3.5 text-rose-500" /></button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- ============ LEVEL 2: SECTIONS under this Directorate ============ --}}
                                                @if($dept->children->count() > 0)
                                                    <div class="tree-sub-children w-full">
                                                        <div class="tree-sub-row">
                                                            @foreach($dept->children as $section)
                                                                <div class="tree-sub-item">
                                                                    {{-- Section Box (light blue) --}}
                                                                    <div class="org-node relative group cursor-grab active:cursor-grabbing select-none"
                                                                         draggable="true"
                                                                         @dragstart="onDragStart($event, {{ $section->id }}, '{{ addslashes($section->name) }}')"
                                                                         @dragend="onDragEnd($event)"
                                                                         @dragover.prevent="onDragOver($event, {{ $section->id }})"
                                                                         @dragleave="onDragLeave($event)"
                                                                         @drop="onDrop($event, {{ $section->id }}, '{{ addslashes($section->name) }}')">
                                                                        <div class="bg-sky-50 border-2 border-sky-400 rounded-xl px-3 py-2 text-center shadow-sm w-44 hover:shadow-md transition-shadow">
                                                                            <div class="text-[11px] font-black text-slate-900 leading-tight">{{ $section->name }}</div>
                                                                            @if($section->manager_title)
                                                                                <div class="text-[10px] font-semibold text-sky-700 mt-0.5 pt-0.5 border-t border-sky-200">
                                                                                    {{ $section->manager_title }}
                                                                                </div>
                                                                            @endif
                                                                            @if($section->hr_employees_count > 0)
                                                                                <div class="text-[9px] text-slate-400 font-mono mt-0.5">{{ $section->hr_employees_count }} موظف</div>
                                                                            @endif
                                                                        </div>
                                                                        <div class="absolute -top-2.5 -left-2.5 hidden group-hover:flex items-center gap-0.5 bg-white p-0.5 rounded border border-slate-200 shadow-md z-30 no-print">
                                                                            <button type="button" @click.stop="openEdit({{ json_encode($section) }})" class="p-0.5 hover:bg-blue-50 rounded"><x-heroicon-s-pencil-square class="w-3 h-3 text-blue-600" /></button>
                                                                            <button type="button" @click.stop="openCreate({{ $section->id }}, 'unit', 'إضافة وحدة')" class="p-0.5 hover:bg-emerald-50 rounded"><x-heroicon-s-plus class="w-3 h-3 text-emerald-600" /></button>
                                                                            @if($section->children->isEmpty() && $section->hr_employees_count == 0)
                                                                                <form method="POST" action="{{ route('hr.departments.destroy', $section->id) }}" onsubmit="return confirm('تأكيد الحذف؟')" class="inline">
                                                                                    @csrf @method('DELETE')
                                                                                    <button type="submit" class="p-0.5 hover:bg-rose-50 rounded"><x-heroicon-s-trash class="w-3 h-3 text-rose-500" /></button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    {{-- Level 3: Sub-units --}}
                                                                    @if($section->children->count() > 0)
                                                                        <div class="flex flex-col items-center mt-4 space-y-2">
                                                                            <div class="w-0.5 h-4 bg-slate-400"></div>
                                                                            @foreach($section->children as $sub)
                                                                                <div class="org-node relative group cursor-grab select-none"
                                                                                     draggable="true"
                                                                                     @dragstart="onDragStart($event, {{ $sub->id }}, '{{ addslashes($sub->name) }}')"
                                                                                     @dragend="onDragEnd($event)"
                                                                                     @dragover.prevent="onDragOver($event, {{ $sub->id }})"
                                                                                     @dragleave="onDragLeave($event)"
                                                                                     @drop="onDrop($event, {{ $sub->id }}, '{{ addslashes($sub->name) }}')">
                                                                                    <div class="bg-slate-50 border border-slate-400 rounded-lg px-3 py-1.5 text-center w-40 shadow-xs">
                                                                                        <div class="text-[10px] font-black text-slate-800">{{ $sub->name }}</div>
                                                                                        @if($sub->manager_title)
                                                                                            <div class="text-[9px] text-amber-700 mt-0.5">{{ $sub->manager_title }}</div>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="absolute -top-2 -left-2 hidden group-hover:flex bg-white p-0.5 rounded border border-slate-200 shadow no-print">
                                                                                        <button type="button" @click.stop="openEdit({{ json_encode($sub) }})" class="p-0.5"><x-heroicon-s-pencil-square class="w-2.5 h-2.5 text-blue-600" /></button>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                @endforeach

            </div>{{-- end zoom container --}}

            {{-- Legend --}}
            <div class="mt-6 pt-4 border-t-2 border-slate-200 flex flex-wrap items-center justify-center gap-5 text-xs font-bold no-print">
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-orange-100 border-2 border-orange-400 inline-block"></span>
                    <span class="text-slate-700">المجلس البلدي</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-blue-100 border-2 border-blue-500 inline-block"></span>
                    <span class="text-slate-700">رئاسة البلدية</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-yellow-50 border-2 border-dashed border-yellow-500 inline-block"></span>
                    <span class="text-slate-700">وحدة استشارية / جانبية</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-yellow-50 border-2 border-amber-500 inline-block"></span>
                    <span class="text-slate-700">دائرة / قسم رئيسي</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-sky-50 border-2 border-sky-400 inline-block"></span>
                    <span class="text-slate-700">شعبة / وحدة تنفيذية</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-4 rounded bg-slate-50 border border-slate-400 inline-block"></span>
                    <span class="text-slate-700">وحدة فرعية</span>
                </div>
            </div>
        </div>

        {{-- ===== VIEW 2: LIST VIEW ===== --}}
        <div x-show="viewMode === 'list'" style="display:none;" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs space-y-4">
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
                            <th class="py-3 px-4">التبعية</th>
                            <th class="py-3 px-4">المسؤول</th>
                            <th class="py-3 px-4">الكادر</th>
                            <th class="py-3 px-4 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($allDepartments as $d)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="font-black text-slate-900">{{ $d->name }}</span>
                                    @if($d->code) <span class="ml-1 text-[10px] text-slate-400 font-mono">[{{ $d->code }}]</span> @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $d->type_arabic }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-bold">
                                    {{ $d->parent?->name ?? 'رأس الهيكل' }}
                                </td>
                                <td class="py-3 px-4 text-slate-800">
                                    {{ $d->manager_title ?: ($d->manager?->name ?? '—') }}
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                                    {{ $d->hrEmployees()->count() }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <button type="button" @click="openEdit({{ json_encode($d) }})"
                                                class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition-colors border border-blue-200">
                                            تعديل
                                        </button>
                                        <button type="button" @click="openCreate({{ $d->id }}, 'section', 'إضافة شعبة تحت ' + '{{ addslashes($d->name) }}')"
                                                class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition-colors border border-emerald-200">
                                            + فرعية
                                        </button>
                                        @if($d->children->isEmpty() && $d->hrEmployees()->count() == 0)
                                            <form method="POST" action="{{ route('hr.departments.destroy', $d->id) }}" onsubmit="return confirm('هل تريد حذف هذه الوحدة؟')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition-colors border border-rose-200">
                                                    حذف
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

        {{-- ===== MODAL: Add / Edit Department ===== --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs no-print"
             style="display:none;"
             @keydown.escape.window="showModal = false">

            <div class="fixed inset-0" @click="showModal = false"></div>

            <div @click.stop class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg relative z-10">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-black text-slate-800" x-text="modalTitle"></h3>
                    <button type="button" @click="showModal = false" class="p-1.5 hover:bg-slate-100 rounded-lg transition-colors text-slate-500">
                        <x-heroicon-s-x-mark class="w-4 h-4" />
                    </button>
                </div>

                {{-- Modal Form --}}
                <form :action="formAction" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="id" :value="deptId">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">اسم الوحدة / الصندوق <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="deptName" required placeholder="مثال: دائرة الهندسية، قسم التخطيط..."
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">نوع المربع <span class="text-rose-500">*</span></label>
                            <select name="type" x-model="deptType" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary font-bold">
                                @foreach(\App\Models\Correspondence\Department::TYPES as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">التبعية (الوحدة الأب)</label>
                            <select name="parent_id" x-model="deptParentId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary">
                                <option value="">-- رأس الهيكل --</option>
                                @foreach($allDepartments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">المسمى الوظيفي للمسؤول</label>
                        <input type="text" name="manager_title" x-model="deptManagerTitle" placeholder="مثال: د. عماد عدوان - رئيس البلدية"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الرمز الإداري (Code)</label>
                            <input type="text" name="code" x-model="deptCode" placeholder="ENG, FIN, HR..."
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">الترتيب</label>
                            <input type="number" name="order" x-model="deptOrder" min="0" placeholder="0"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">الوصف والمهام (اختياري)</label>
                        <textarea name="description" x-model="deptDescription" rows="2" placeholder="المهام العامة للوحدة..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200 cursor-pointer transition-colors">
                            إلغاء
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-primary hover:bg-primary-dark text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition-colors">
                            حفظ الوحدة
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function deptHierarchyApp() {
            return {
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

                zoomLevel: 85,
                viewMode: 'chart',
                draggedId: null,
                draggedName: '',
                dragOverId: null,

                zoomIn()  { if (this.zoomLevel < 150) this.zoomLevel += 10; },
                zoomOut() { if (this.zoomLevel > 40) this.zoomLevel -= 10; },
                resetZoom() { this.zoomLevel = 85; },

                openCreate(parentId = '', defaultType = 'department', defaultTitle = '') {
                    this.isEdit = false;
                    this.modalTitle = defaultTitle || (parentId ? 'إضافة وحدة فرعية جديدة' : 'إضافة وحدة رئيسية');
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
                    this.modalTitle = 'تعديل بيانات: ' + dept.name;
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
                    e.dataTransfer.setData('text/plain', String(id));
                    e.dataTransfer.effectAllowed = 'move';
                    e.currentTarget.classList.add('opacity-50', 'scale-95', 'ring-2', 'ring-amber-400');
                },

                onDragEnd(e) {
                    this.draggedId = null;
                    this.draggedName = '';
                    this.dragOverId = null;
                    e.currentTarget.classList.remove('opacity-50', 'scale-95', 'ring-2', 'ring-amber-400');
                    document.querySelectorAll('.drag-target-active').forEach(el => el.classList.remove('drag-target-active'));
                },

                onDragOver(e, targetId) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    if (this.draggedId && this.draggedId != targetId) {
                        this.dragOverId = targetId;
                        e.currentTarget.classList.add('drag-target-active');
                    }
                },

                onDragLeave(e) {
                    this.dragOverId = null;
                    e.currentTarget.classList.remove('drag-target-active');
                },

                onDrop(e, targetId, targetName) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.currentTarget.classList.remove('drag-target-active');
                    this.dragOverId = null;

                    if (!this.draggedId || this.draggedId == targetId) return;

                    const srcName = this.draggedName;
                    const srcId = this.draggedId;
                    if (confirm('هل تريد نقل [' + srcName + '] لتصبح تابعةً لـ [' + targetName + '] في الهيكلية الإدارية؟')) {
                        this.submitMove(srcId, targetId);
                    }
                },

                submitMove(sourceId, newParentId) {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    fetch('/hr/departments/' + sourceId + '/move', {
                        method: 'PATCH',
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
                    .catch(() => alert('حدث خطأ أثناء نقل الوحدة الإدارية'));
                }
            };
        }
    </script>
</x-hr-layout>
