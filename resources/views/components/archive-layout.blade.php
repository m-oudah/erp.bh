<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-amber-300 text-[10px] font-bold mb-0.5 tracking-wider">نظام إدارة المستندات</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                الأرشيف الإلكتروني المركزي
            </h1>
            <p class="text-teal-100 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-7xl mx-auto pb-10">
        
        <!-- Sidebar (Right in RTL) -->
        <div class="w-full lg:w-1/4 space-y-4">
            
            @can('archive.files.create')
            <a href="{{ route('archive.files.create') }}" class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white font-bold py-3.5 px-4 rounded-2xl shadow-md transition-all transform hover:-translate-y-0.5">
                <x-heroicon-o-plus-circle class="w-5 h-5 text-amber-300" />
                <span>إنشاء ملف جديد</span>
            </a>
            @endcan

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-bolt class="w-4 h-4 text-secondary" style="color: rgb(217 119 6);" />
                    <span>روابط سريعة</span>
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('archive.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.index') ? 'bg-secondary/10 text-secondary-dark font-bold' : 'text-tertiary hover:bg-gray-50 font-bold' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.index') ? 'bg-secondary text-white shadow-sm' : 'bg-amber-50 text-secondary' }}">
                            <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                        </div>
                        الرئيسية
                    </a>
                    <a href="{{ route('archive.files.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create') ? 'bg-secondary/10 text-secondary-dark font-bold' : 'text-tertiary hover:bg-gray-50 font-bold' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create') ? 'bg-secondary text-white shadow-sm' : 'bg-amber-50 text-secondary' }}">
                            <x-heroicon-o-folder class="w-5 h-5" />
                        </div>
                        تصفح المجلدات
                    </a>
                    <a href="{{ route('archive.documents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.documents.*') ? 'bg-secondary/10 text-secondary-dark font-bold' : 'text-tertiary hover:bg-gray-50 font-bold' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.documents.*') ? 'bg-secondary text-white shadow-sm' : 'bg-amber-50 text-secondary' }}">
                            <x-heroicon-o-document-text class="w-5 h-5" />
                        </div>
                        تصفح الوثائق
                    </a>
                    <a href="{{ route('archive.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.reports.*') ? 'bg-secondary/10 text-secondary-dark font-bold' : 'text-tertiary hover:bg-gray-50 font-bold' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.reports.*') ? 'bg-secondary text-white shadow-sm' : 'bg-amber-50 text-secondary' }}">
                            <x-heroicon-o-chart-bar class="w-5 h-5" />
                        </div>
                        التقارير
                    </a>
                </nav>
            </div>
            
            <!-- Folders Tree Widget -->
            @php
                $sidebarArchiveTypes = \App\Models\ArchiveFileType::where('is_active', true)->withCount('files')->get();
            @endphp
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 mt-4">
                <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-3 px-2">
                    <x-heroicon-o-folder-open class="w-4 h-4 text-secondary" style="color: rgb(217 119 6);" />
                    <span>المجلدات</span>
                </h4>
                <div class="space-y-1">
                    @forelse($sidebarArchiveTypes as $type)
                        <a href="{{ route('archive.files.index', ['type_id' => $type->id]) }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type_id') == $type->id ? 'bg-secondary/10 text-secondary-dark font-bold' : 'text-tertiary hover:bg-gray-50 font-semibold' }} transition-colors">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-folder class="w-4 h-4 {{ request('type_id') == $type->id ? 'text-secondary' : 'text-secondary/70' }}" style="color: rgb(217 119 6);" />
                                <span class="text-xs font-bold">{{ $type->name }}</span>
                            </div>
                            @if($type->files_count > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request('type_id') == $type->id ? 'bg-secondary text-white' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $type->files_count }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <div class="text-xs text-gray-400 px-3 py-2 font-medium">لا توجد مجلدات</div>
                    @endforelse
                </div>
                <div class="mt-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('archive.trash.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('archive.trash.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-tertiary hover:bg-gray-50 font-bold' }} transition-colors">
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-trash class="w-4 h-4 {{ request()->routeIs('archive.trash.*') ? 'text-red-500' : 'text-gray-400' }}" />
                            <span class="text-xs font-bold">سلة المحذوفات</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content (Left in RTL) -->
        <div class="w-full lg:w-3/4 space-y-6">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
