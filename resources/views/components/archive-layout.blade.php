<x-app-layout>
    <x-slot name="module_title">
        <div>
            <div class="text-orange text-[10px] font-bold mb-0.5 tracking-wider">نظام إدارة المستندات</div>
            <h1 class="font-bold text-xl text-white tracking-wide flex items-center gap-2 leading-none">
                الأرشيف الإلكتروني المركزي
            </h1>
            <p class="text-blue-100/70 text-xs mt-1.5 font-medium">بلدية بيت حانون - الخدمات الإلكترونية</p>
        </div>
    </x-slot>

    <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-7xl mx-auto pb-10">
        
        <!-- Sidebar (Right in RTL) -->
        <div class="w-full lg:w-1/4 space-y-4">
            
            @can('archive.files.create')
            <a href="{{ route('archive.files.create') }}" class="w-full flex items-center justify-center gap-2 bg-navy hover:bg-navy-dark text-white font-bold py-3.5 px-4 rounded-2xl shadow-[0_4px_14px_0_rgba(1,31,75,0.39)] transition-all transform hover:-translate-y-0.5">
                <x-heroicon-o-plus-circle class="w-5 h-5" />
                إنشاء ملف جديد
            </a>
            @endcan

            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-3 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-3 pt-2">
                    <x-heroicon-s-bolt class="w-4 h-4 text-orange" />
                    روابط سريعة
                </h4>
                <nav class="space-y-1">
                    <a href="{{ route('archive.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.index') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.index') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-s-squares-2x2 class="w-4 h-4" />
                        </div>
                        الرئيسية
                    </a>
                    <a href="{{ route('archive.files.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.files.*') && !request()->routeIs('archive.files.create') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-folder class="w-5 h-5" />
                        </div>
                        تصفح المجلدات
                    </a>
                    <a href="{{ route('archive.documents.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.documents.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.documents.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
                            <x-heroicon-o-document-text class="w-5 h-5" />
                        </div>
                        تصفح الوثائق
                    </a>
                    <a href="{{ route('archive.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('archive.reports.*') ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50 font-medium' }} transition-colors">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center {{ request()->routeIs('archive.reports.*') ? 'bg-orange text-white shadow-sm' : 'text-gray-400' }}">
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
            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-4 mt-4">
                <h4 class="text-sm font-bold text-gray-700 flex items-center gap-2 mb-3 px-2">
                    <x-heroicon-o-folder-open class="w-4 h-4 text-gray-400" />
                    المجلدات
                </h4>
                <div class="space-y-1">
                    @forelse($sidebarArchiveTypes as $type)
                        <a href="{{ route('archive.files.index', ['type_id' => $type->id]) }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request('type_id') == $type->id ? 'bg-orange/10 text-orange-dark font-bold' : 'text-gray-600 hover:bg-gray-50' }} transition-colors">
                            <div class="flex items-center gap-2">
                                <x-heroicon-s-folder class="w-4 h-4 {{ request('type_id') == $type->id ? 'text-orange' : 'text-gray-300' }}" />
                                <span class="text-sm {{ request('type_id') == $type->id ? 'font-bold' : 'font-medium' }}">{{ $type->name }}</span>
                            </div>
                            @if($type->files_count > 0)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request('type_id') == $type->id ? 'bg-orange text-white' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $type->files_count }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <div class="text-xs text-gray-400 px-3 py-2">لا توجد مجلدات</div>
                    @endforelse
                </div>
                <div class="mt-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('archive.trash.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl {{ request()->routeIs('archive.trash.*') ? 'bg-red-50 text-red-600 font-bold' : 'text-gray-600 hover:bg-gray-50' }} transition-colors">
                        <div class="flex items-center gap-2">
                            <x-heroicon-s-trash class="w-4 h-4 {{ request()->routeIs('archive.trash.*') ? 'text-red-500' : 'text-gray-400' }}" />
                            <span class="text-sm {{ request()->routeIs('archive.trash.*') ? 'font-bold' : 'font-medium' }}">سلة المحذوفات</span>
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
