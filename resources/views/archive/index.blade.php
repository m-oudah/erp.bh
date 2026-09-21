<x-archive-layout>
    <!-- Top Section -->
    <div class="flex justify-between items-center mb-2 px-1">
        <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
            <x-heroicon-o-chart-bar class="w-5 h-5 text-gray-500" />
            إدارة الأرصدة والملفات
        </h3>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Stat Card 1 -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-bold text-gray-800 text-lg">المجلدات المؤرشفة</h4>
                <div class="text-sm font-bold text-navy bg-blue-50 px-3 py-1 rounded-full">
                    الكلي: {{ $filesCount ?? 0 }}
                </div>
            </div>
            <div class="text-sm text-gray-500 font-medium">مضاف اليوم: {{ \App\Models\ArchiveFile::whereDate('created_at', today())->count() }}</div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <h4 class="font-bold text-gray-800 text-lg">إجمالي الوثائق</h4>
                <div class="text-sm font-bold text-orange bg-orange-50 px-3 py-1 rounded-full">
                    الكلي: {{ $documentsCount ?? 0 }}
                </div>
            </div>
            <div class="text-sm text-gray-500 font-medium">تمت أرشفته بنجاح</div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] mt-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
                أحدث الملفات
            </h3>
            <a href="{{ route('archive.files.index') }}" class="text-sm font-medium text-navy hover:text-navy-dark">عرض الكل &larr;</a>
        </div>

        @if(isset($recentFiles) && $recentFiles->count() > 0)
        <div class="space-y-3">
            @foreach($recentFiles as $file)
                <a href="{{ route('archive.files.show', $file->id) }}" class="flex items-center gap-4 p-4 rounded-2xl border border-gray-50 hover:border-gray-200 hover:bg-gray-50/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-white group-hover:text-navy group-hover:shadow-sm transition-all">
                        <x-heroicon-s-folder class="w-6 h-6" />
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-gray-800 group-hover:text-navy-dark mb-0.5">
                            {{ $file->file_name }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium">
                            رقم الملف: {{ $file->file_no }}
                        </div>
                    </div>
                    <div class="text-sm text-gray-400 font-medium">
                        {{ $file->created_at->diffForHumans() }}
                    </div>
                </a>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <x-heroicon-o-calendar-days class="w-16 h-16 text-gray-200 mb-4" />
            <p class="text-gray-400 font-medium text-lg">لا يوجد ملفات حتى الآن</p>
            <p class="text-gray-400 text-sm mt-1">قم بإنشاء مجلدك الأول للبدء في الأرشفة</p>
        </div>
        @endif
    </div>
</x-archive-layout>
