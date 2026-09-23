<x-app-layout>
    @if(isset($module_title))
        <x-slot name="module_title">
            {{ $module_title }}
        </x-slot>
    @endif

    <div class="max-w-7xl mx-auto pb-10 flex flex-col md:flex-row gap-6">
        
        <!-- Sidebar Navigation -->
        <div class="w-full md:w-1/4 shrink-0">
            <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 p-4 sticky top-6">
                <h3 class="text-[11px] font-bold text-gray-400 mb-4 px-3 border-b border-gray-100 pb-2">قائمة إعدادات الأرشيف</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('settings.archive.types.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl font-bold transition-colors {{ request()->routeIs('settings.archive.types.*') ? 'bg-orange/10 text-orange' : 'text-gray-600 hover:bg-gray-50 hover:text-navy' }}">
                            <x-heroicon-o-document-text class="w-5 h-5 {{ request()->routeIs('settings.archive.types.*') ? 'text-orange' : 'text-gray-400' }}" />
                            أنواع الملفات والحقول
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.archive.document-categories.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl font-bold transition-colors {{ request()->routeIs('settings.archive.document-categories.*') ? 'bg-orange/10 text-orange' : 'text-gray-600 hover:bg-gray-50 hover:text-navy' }}">
                            <x-heroicon-o-tag class="w-5 h-5 {{ request()->routeIs('settings.archive.document-categories.*') ? 'text-orange' : 'text-gray-400' }}" />
                            تصنيفات الوثائق
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0">
            {{ $slot }}
        </div>

    </div>
</x-app-layout>
