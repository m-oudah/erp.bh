<x-archive-layout :show-folders="true" :show-doc-categories="false">
    <div class="space-y-4">
        {{-- Search & Filter Bar (Correspondence Module Style) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-3.5">
            <form action="{{ route('archive.files.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
                {{-- Smart Search Box --}}
                <div class="flex-1 min-w-[240px] relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="ابحث برقم الهوية، رقم الملف، أو اسم المواطن..."
                           class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary pr-9 pl-4 placeholder:text-gray-400 py-2">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-secondary" style="color: rgb(217 119 6);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Type Filter --}}
                <div class="w-auto min-w-[160px]">
                    <select name="type_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-gray-200 focus:border-primary focus:ring-primary py-2 font-medium">
                        <option value="">جميع أنواع المجلدات</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" {{ request('type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="submit" class="bg-primary hover:bg-teal-700 text-white px-3.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1 shadow-xs cursor-pointer">
                        <span>تصفية</span>
                    </button>
                    @if (request()->hasAny(['search', 'type_id']))
                        <a href="{{ route('archive.files.index') }}" class="bg-gray-100 hover:bg-gray-200 text-slate-600 px-2.5 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1" title="مسح التصفية">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>مسح</span>
                        </a>
                    @endif
                </div>

                {{-- Counter Badge --}}
                <div class="mr-auto text-[11px] font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2.5 py-1.5 rounded-lg shrink-0">
                    المعروض: <span class="text-teal-700 font-extrabold">{{ $files->total() }}</span> ملف مؤرشف
                </div>
            </form>
        </div>

        {{-- Files Table (Correspondence Module Style) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs min-w-[720px]">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-gray-100 text-slate-600 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3">رقم ونوع الملف</th>
                            <th class="py-2.5 px-3">اسم المواطن / الملف</th>
                            <th class="py-2.5 px-3">نوع الملف والتصنيف</th>
                            <th class="py-2.5 px-3 text-center">الوثائق الرقمية</th>
                            <th class="py-2.5 px-3 text-center">تاريخ الإنشاء</th>
                            <th class="py-2.5 px-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($files as $file)
                        <tr class="transition-colors duration-150 hover:bg-slate-50/80">
                            {{-- File Number with Status Dot --}}
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                    <a href="{{ route('archive.files.show', $file->id) }}" class="font-mono font-bold text-teal-800 hover:text-teal-900 hover:underline">
                                        {{ $file->file_no }}
                                    </a>
                                </div>
                            </td>

                            {{-- File Name --}}
                            <td class="py-2.5 px-3 max-w-xs">
                                <a href="{{ route('archive.files.show', $file->id) }}" class="font-bold text-slate-800 hover:text-primary transition-colors block text-xs truncate" title="{{ $file->file_name }}">
                                    {{ $file->file_name }}
                                </a>
                                @if(isset($file->notes) && $file->notes)
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ Str::limit($file->notes, 50) }}</p>
                                @endif
                            </td>

                            {{-- File Type Badge --}}
                            <td class="py-2.5 px-3 whitespace-nowrap">
                                <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $file->fileType->name ?? 'غير محدد' }}
                                </span>
                            </td>

                            {{-- Documents Count --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-full font-mono {{ ($file->documents_count ?? 0) > 0 ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-500' }}">
                                    <x-heroicon-o-document-text class="w-3 h-3" />
                                    <span>{{ $file->documents_count ?? ($file->relationLoaded('documents') ? $file->documents->count() : 0) }}</span>
                                </span>
                            </td>

                            {{-- Created Date --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap text-slate-500 text-[11px]">
                                {{ $file->created_at ? $file->created_at->format('Y/m/d') : '—' }}
                                <span class="block text-[10px] text-slate-400">{{ $file->created_at ? $file->created_at->diffForHumans() : '' }}</span>
                            </td>

                            {{-- Actions --}}
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('archive.files.show', $file->id) }}"
                                       class="p-1.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 transition-colors cursor-pointer" title="عرض تفاصيل الملف">
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </a>

                                    @can('archive.files.edit')
                                    <a href="{{ route('archive.files.edit', $file->id) }}"
                                       class="p-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors cursor-pointer" title="تعديل بيانات الملف">
                                        <x-heroicon-o-pencil class="w-4 h-4" />
                                    </a>
                                    @endcan

                                    @can('archive.files.delete')
                                    <form action="{{ route('archive.files.destroy', $file->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من نقل هذا الملف إلى سلة المحذوفات؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer" title="حذف الملف">
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <x-heroicon-o-folder-open class="w-8 h-8 text-slate-300" />
                                    <span>لا توجد ملفات مطابقة لشروط البحث والتصفية.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($files->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-slate-50/50">
                {{ $files->links() }}
            </div>
            @endif
        </div>
    </div>
</x-archive-layout>
