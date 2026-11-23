@props(['withSidebar' => true, 'showFolders' => null, 'showDocCategories' => null])

<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">نظام إدارة المستندات والوثائق</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                الأرشيف الإلكتروني المركزي
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - الفهرسة والتوثيق الرقمي وسجلات الأرشيف الموحدة</p>
        </div>
    </x-slot>

    <div class="w-full pb-10 space-y-5">

        {{-- Horizontal Tab Navigation (Top Bar) --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-4 py-2 flex items-center justify-between gap-2 overflow-x-auto">
            <div class="flex items-center gap-1 sm:gap-2">
                {{-- Home Button --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100/90 hover:bg-slate-200 transition-colors shrink-0"
                   title="الرجوع للصفحة الرئيسية للنظام">
                    <svg class="w-4 h-4" style="color: rgb(217 119 6);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>الرئيسية</span>
                </a>

                <div class="h-5 w-px bg-gray-200 mx-1 shrink-0"></div>

                {{-- Archive Dashboard Tab --}}
                <a href="{{ route('archive.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('archive.index')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('archive.index') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-s-squares-2x2 class="w-3.5 h-3.5" />
                    </div>
                    <span>لوحة الأرشيف</span>
                </a>

                {{-- Files & Folders Tab --}}
                <a href="{{ route('archive.files.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ (request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create'))
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ (request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create')) ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-o-folder class="w-3.5 h-3.5" />
                    </div>
                    <span>سجل الملفات والمجلدات</span>
                </a>

                {{-- Documents Tab --}}
                <a href="{{ route('archive.documents.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('archive.documents.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('archive.documents.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-o-document-text class="w-3.5 h-3.5" />
                    </div>
                    <span>مستودع الوثائق والمستندات</span>
                </a>

                {{-- Reports Tab --}}
                <a href="{{ route('archive.reports.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('archive.reports.*')
                                ? 'bg-secondary/10 text-secondary-dark shadow-xs border border-secondary/20'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('archive.reports.*') ? 'bg-secondary text-white shadow-xs' : 'bg-amber-50 text-secondary' }}">
                        <x-heroicon-o-chart-bar class="w-3.5 h-3.5" />
                    </div>
                    <span>التقارير والإحصائيات</span>
                </a>

                {{-- Trash Tab --}}
                <a href="{{ route('archive.trash.index') }}"
                   class="flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold whitespace-nowrap transition-all duration-200
                          {{ request()->routeIs('archive.trash.*')
                                ? 'bg-red-50 text-red-700 shadow-xs border border-red-200'
                                : 'text-tertiary hover:bg-gray-50 hover:text-gray-800' }}">
                    <div class="w-6 h-6 rounded-lg flex items-center justify-center shrink-0
                                {{ request()->routeIs('archive.trash.*') ? 'bg-red-600 text-white shadow-xs' : 'bg-red-50 text-red-600' }}">
                        <x-heroicon-s-trash class="w-3.5 h-3.5" />
                    </div>
                    <span>سلة المحذوفات</span>
                </a>
            </div>

            {{-- Action Buttons on the Left --}}
            <div class="flex items-center gap-2 shrink-0">
                @can('archive.files.create')
                <a href="{{ route('archive.files.create') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-primary hover:bg-teal-700 shadow-sm transition-all duration-200">
                    <x-heroicon-s-plus-circle class="w-4 h-4 text-amber-300" />
                    <span>إنشاء ملف أرشيفي جديد</span>
                </a>
                @endcan
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold cursor-pointer">إغلاق</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center justify-between text-sm shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-semibold">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-xs font-bold cursor-pointer">إغلاق</button>
            </div>
        @endif

        {{-- Layout Body --}}
        @if($withSidebar)
            <div class="flex flex-col lg:flex-row gap-4 items-start">
                
                {{-- Right Sidebar: Compact Modern Folder & Category Directory --}}
                <div class="w-full lg:w-56 xl:w-60 space-y-3 shrink-0 lg:sticky lg:top-4">
                    @php
                        $isFilesRoute = request()->routeIs('archive.files.*');
                        $isDocsRoute = request()->routeIs('archive.documents.*');

                        $shouldShowFolders = $showFolders !== null ? $showFolders : (!$isDocsRoute);
                        $shouldShowDocCategories = $showDocCategories !== null ? $showDocCategories : (!$isFilesRoute);

                        $sidebarArchiveTypes = $shouldShowFolders
                            ? \App\Models\ArchiveFileType::where('is_active', true)->withCount('files')->orderByDesc('files_count')->get()
                            : collect();

                        $sidebarDocCategories = $shouldShowDocCategories
                            ? \App\Models\ArchiveDocumentCategory::withCount('documents')->orderByDesc('documents_count')->get()
                            : collect();

                        $trashTotal = \App\Models\ArchiveFile::onlyTrashed()->count() + \App\Models\ArchiveDocument::onlyTrashed()->count();
                    @endphp

                    {{-- Card 1: Folder Types Directory (مجلدات وتصنيفات الأرشيف) --}}
                    @if($shouldShowFolders)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2.5" x-data="{ folderSearch: '' }">
                        <div class="flex items-center justify-between px-1.5 pb-2 mb-1.5 border-b border-gray-100">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1">
                                <x-heroicon-o-folder-open class="w-3.5 h-3.5 text-secondary" style="color: rgb(217 119 6);" />
                                <span>مجلدات وتصنيفات الأرشيف</span>
                            </span>
                            <span class="text-[9px] font-bold bg-amber-50 text-amber-800 px-1.5 py-0.2 rounded-full border border-amber-200 font-mono">
                                {{ $sidebarArchiveTypes->count() }}
                            </span>
                        </div>

                        {{-- Search Input for Folders --}}
                        <div class="relative mb-1.5">
                            <input type="text" x-model="folderSearch" placeholder="بحث في المجلدات..."
                                   class="w-full text-[10px] py-1 pr-7 pl-2 bg-slate-50 rounded-lg border-gray-200 focus:border-amber-500 focus:bg-white focus:ring-0 placeholder:text-gray-400">
                            <div class="absolute right-2 top-1.5 text-gray-400 pointer-events-none">
                                <x-heroicon-s-magnifying-glass class="w-3 h-3" />
                            </div>
                        </div>

                        <div class="space-y-0.5 max-h-[350px] overflow-y-auto pr-0.5">
                            <a href="{{ route('archive.files.index') }}"
                               class="flex items-center justify-between px-2 py-1.5 rounded-lg text-[11px] font-bold transition-colors
                                      {{ !request('type_id') && request()->routeIs('archive.files.index') ? 'bg-secondary/10 text-secondary-dark font-extrabold border border-secondary/20 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-1.5">
                                    <x-heroicon-s-squares-2x2 class="w-3.5 h-3.5 text-slate-400" />
                                    <span>كافة المجلدات</span>
                                </div>
                                <span class="text-[9px] text-slate-400 font-mono">الكل</span>
                            </a>

                            @forelse($sidebarArchiveTypes as $type)
                                @php
                                    $isActive = request('type_id') == $type->id || request('archive_file_type_id') == $type->id;
                                    $safeName = addslashes($type->name);
                                @endphp
                                <a href="{{ route('archive.files.index', ['type_id' => $type->id]) }}"
                                   x-show="!folderSearch || '{{ $safeName }}'.toLowerCase().includes(folderSearch.toLowerCase())"
                                   class="flex items-center justify-between px-2 py-1.5 rounded-lg text-[11px] font-medium transition-colors
                                          {{ $isActive ? 'bg-secondary/10 text-secondary-dark font-extrabold border border-secondary/20 shadow-2xs' : 'text-slate-700 hover:bg-slate-50' }}"
                                   title="{{ $type->name }}">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <x-heroicon-s-folder class="w-3.5 h-3.5 shrink-0 {{ $isActive ? 'text-secondary' : 'text-amber-500/70' }}" style="{{ $isActive ? 'color: rgb(217 119 6);' : '' }}" />
                                        <span class="truncate text-[11px]">{{ $type->name }}</span>
                                    </div>
                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-full shrink-0 font-mono {{ $isActive ? 'bg-secondary text-white' : 'bg-slate-100 text-slate-600' }}" style="{{ $isActive ? 'background-color: rgb(217 119 6);' : '' }}">
                                        {{ $type->files_count }}
                                    </span>
                                </a>
                            @empty
                                <div class="text-[10px] text-slate-400 px-2 py-1.5 text-center">لا توجد تصنيفات</div>
                            @endforelse
                        </div>

                        @can('settings.archive')
                        <div class="mt-2 pt-1.5 border-t border-gray-100 flex items-center justify-between px-1">
                            <a href="{{ route('settings.archive.types.index') }}" class="text-[10px] font-bold text-secondary hover:underline flex items-center gap-1" style="color: rgb(217 119 6);">
                                <x-heroicon-m-cog-6-tooth class="w-3 h-3" />
                                <span>إدارة التصنيفات</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                    @endif

                    {{-- Card 2: Document Classifications Directory (تصنيفات الوثائق) --}}
                    @if($shouldShowDocCategories && $sidebarDocCategories->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2.5" x-data="{ catSearch: '' }">
                        <div class="flex items-center justify-between px-1.5 pb-2 mb-1.5 border-b border-gray-100">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1">
                                <x-heroicon-o-document-text class="w-3.5 h-3.5 text-teal-600" />
                                <span>تصنيفات الوثائق</span>
                            </span>
                            <span class="text-[9px] font-bold bg-teal-50 text-teal-800 px-1.5 py-0.2 rounded-full border border-teal-200 font-mono">
                                {{ $sidebarDocCategories->count() }}
                            </span>
                        </div>

                        {{-- Search Input for Document Categories --}}
                        <div class="relative mb-1.5">
                            <input type="text" x-model="catSearch" placeholder="بحث في تصنيفات الوثائق..."
                                   class="w-full text-[10px] py-1 pr-7 pl-2 bg-slate-50 rounded-lg border-gray-200 focus:border-teal-500 focus:bg-white focus:ring-0 placeholder:text-gray-400">
                            <div class="absolute right-2 top-1.5 text-gray-400 pointer-events-none">
                                <x-heroicon-s-magnifying-glass class="w-3 h-3" />
                            </div>
                        </div>

                        <div class="space-y-0.5 max-h-[350px] overflow-y-auto pr-0.5">
                            <a href="{{ route('archive.documents.index') }}"
                               class="flex items-center justify-between px-2 py-1.5 rounded-lg text-[11px] font-bold transition-colors
                                      {{ !request('category_id') && request()->routeIs('archive.documents.index') ? 'bg-teal-50 text-teal-900 font-extrabold border border-teal-200 shadow-2xs' : 'text-slate-600 hover:bg-slate-50' }}">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                                    <span>كافة الوثائق</span>
                                </div>
                                <span class="text-[9px] text-slate-400 font-mono">الكل</span>
                            </a>

                            @foreach($sidebarDocCategories as $cat)
                                @php
                                    $isCatActive = request('category_id') == $cat->id;
                                    $safeCat = addslashes($cat->name);
                                @endphp
                                <a href="{{ route('archive.documents.index', ['category_id' => $cat->id]) }}"
                                   x-show="!catSearch || '{{ $safeCat }}'.toLowerCase().includes(catSearch.toLowerCase())"
                                   class="flex items-center justify-between px-2 py-1.5 rounded-lg text-[11px] font-medium transition-colors
                                          {{ $isCatActive ? 'bg-teal-50 text-teal-900 font-extrabold border border-teal-200 shadow-2xs' : 'text-slate-700 hover:bg-slate-50' }}"
                                   title="{{ $cat->name }}">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <div class="w-2 h-2 rounded-full shrink-0 {{ $isCatActive ? 'bg-teal-600' : 'bg-slate-300' }}"></div>
                                        <span class="truncate text-[11px]">{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-[9px] font-mono px-1.5 py-0.2 rounded-md font-bold {{ $isCatActive ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $cat->documents_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Card 3: Quick Navigation to Trash & Reports --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 space-y-0.5">
                        <a href="{{ route('archive.trash.index') }}" 
                           class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors
                                  {{ request()->routeIs('archive.trash.*') ? 'bg-red-50 text-red-700 border border-red-200' : 'text-slate-600 hover:bg-red-50/50 hover:text-red-700' }}">
                            <div class="flex items-center gap-1.5">
                                <x-heroicon-s-trash class="w-3.5 h-3.5 text-red-500" />
                                <span>سلة المحذوفات</span>
                            </div>
                            @if($trashTotal > 0)
                                <span class="text-[9px] font-mono px-1.5 py-0.2 rounded-full bg-red-100 text-red-700 font-bold">
                                    {{ $trashTotal }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('archive.reports.index') }}" 
                           class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-[11px] font-bold transition-colors
                                  {{ request()->routeIs('archive.reports.*') ? 'bg-secondary/10 text-secondary-dark border border-secondary/20' : 'text-slate-600 hover:bg-slate-50' }}">
                            <div class="flex items-center gap-1.5">
                                <x-heroicon-s-chart-bar class="w-3.5 h-3.5 text-secondary" style="color: rgb(217 119 6);" />
                                <span>التقارير والإحصائيات</span>
                            </div>
                            <x-heroicon-m-chevron-left class="w-3 h-3 text-slate-400" />
                        </a>
                    </div>

                </div>

                {{-- Main Content Area --}}
                <div class="w-full lg:flex-1 min-w-0 space-y-4">
                    {{ $slot }}
                </div>

            </div>
        @else
            <div class="w-full space-y-5">
                {{ $slot }}
            </div>
        @endif

    </div>
</x-app-layout>
