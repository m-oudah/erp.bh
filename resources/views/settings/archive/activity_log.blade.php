<x-archive-settings-layout>
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
            <div>
                <h2 class="font-bold text-navy-dark text-xl flex items-center gap-2">
                    <x-heroicon-s-clipboard-document-list class="w-6 h-6 text-orange" />
                    سجل الحركات
                </h2>
                <p class="text-gray-400 text-sm mt-1">جميع العمليات التي تمت على بيانات الأرشيف</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-8 py-4 bg-gray-50/50 border-b border-gray-100">
            <form method="GET" action="{{ route('settings.archive.activity-log') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500">المستخدم</label>
                        <select name="user_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange/40 bg-white text-gray-700">
                            <option value="">-- جميع المستخدمين --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500">نوع الحركة</label>
                        <select name="event" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange/40 bg-white text-gray-700">
                            <option value="">-- جميع الأنواع --</option>
                            <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>إضافة</option>
                            <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>تعديل</option>
                            <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>حذف</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500">من تاريخ</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange/40 bg-white text-gray-700">
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-bold text-gray-500">إلى تاريخ</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-orange/40 bg-white text-gray-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="flex-1 bg-orange text-white px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-orange-dark transition-colors flex items-center justify-center gap-2">
                            <x-heroicon-o-funnel class="w-4 h-4" />
                            تصفية
                        </button>
                        @if(request()->hasAny(['user_id', 'event', 'date_from', 'date_to']))
                            <a href="{{ route('settings.archive.activity-log') }}" class="flex-1 bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-200 transition-colors flex items-center justify-center gap-1">
                                <x-heroicon-o-x-mark class="w-4 h-4" />
                                إلغاء
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead class="bg-white text-gray-400 text-xs uppercase border-b border-gray-100 font-bold">
                    <tr>
                        <th class="px-6 py-4">المستخدم</th>
                        <th class="px-6 py-4">نوع الحركة</th>
                        <th class="px-6 py-4">النموذج / السجل</th>
                        <th class="px-6 py-4">رقم الملف / الوثيقة</th>
                        <th class="px-6 py-4">التغييرات</th>
                        <th class="px-6 py-4">التاريخ والوقت</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($activities as $activity)
                    @php
                        $eventColors = [
                            'created' => 'bg-green-50 text-green-700 border-green-100',
                            'updated' => 'bg-blue-50 text-blue-700 border-blue-100',
                            'deleted' => 'bg-red-50 text-red-700 border-red-100',
                        ];
                        $eventLabels = [
                            'created' => 'إضافة',
                            'updated' => 'تعديل',
                            'deleted' => 'حذف',
                        ];
                        $eventColor = $eventColors[$activity->event] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                        $eventLabel = $eventLabels[$activity->event] ?? $activity->event;

                        // Determine model name for display and link
                        $modelType = class_basename($activity->subject_type ?? '');
                        $subjectId = $activity->subject_id;
                        $link = null;
                        $modelLabel = $modelType;

                        if ($modelType === 'ArchiveFile') {
                            $link = $subjectId ? route('archive.files.show', $subjectId) : null;
                            $modelLabel = 'ملف أرشيف';
                        } elseif ($modelType === 'ArchiveDocument') {
                            // Documents link to their parent file if available
                            $props = $activity->properties->toArray();
                            $archiveFileId = $props['attributes']['archive_file_id'] ?? ($props['old']['archive_file_id'] ?? null);
                            $link = $archiveFileId ? route('archive.files.show', $archiveFileId) : null;
                            $modelLabel = 'وثيقة أرشيف';
                        } elseif ($modelType === 'ArchiveFileType') {
                            $link = route('settings.archive.types.index');
                            $modelLabel = 'نوع ملف';
                        } elseif ($modelType === 'ArchiveDocumentCategory') {
                            $link = route('settings.archive.document-categories.index');
                            $modelLabel = 'تصنيف وثيقة';
                        }

                        // Extract identifier from properties
                        $attributes = $activity->properties['attributes'] ?? [];
                        $identifier = $attributes['file_no'] ?? $attributes['document_no'] ?? $attributes['name'] ?? '#' . $subjectId;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <!-- User -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                
                                <span class="font-bold text-gray-800">{{ $activity->causer?->name ?? 'نظام' }}</span>
                            </div>
                        </td>
                        <!-- Event Type -->
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border {{ $eventColor }}">
                                {{ $eventLabel }}
                            </span>
                        </td>
                        <!-- Model Type -->
                        <td class="px-6 py-4 font-medium text-gray-600">{{ $modelLabel }}</td>
                        <!-- Identifier + Link -->
                        <td class="px-6 py-4">
                            @if($link)
                                <a href="{{ $link }}" class="font-bold text-orange hover:underline flex items-center gap-1">
                                    {{ $identifier }}
                                    <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5 opacity-70" />
                                </a>
                            @else
                                <span class="font-bold text-gray-500">{{ $identifier }}</span>
                            @endif
                        </td>
                        <!-- Changes -->
                        <td class="px-6 py-4">
                            @php
                                $dirty = $activity->properties['dirty'] ?? $activity->properties['attributes'] ?? [];
                                $changed = is_array($dirty) ? array_keys(array_filter($dirty, fn($v) => !is_array($v))) : [];
                                $changed = array_diff($changed, ['updated_at', 'created_at']);
                            @endphp
                            @if(count($changed))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($changed, 0, 4) as $field)
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-md font-medium">{{ $field }}</span>
                                    @endforeach
                                    @if(count($changed) > 4)
                                        <span class="text-xs text-gray-400">+{{ count($changed) - 4 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <!-- Date -->
                        <td class="px-6 py-4 text-gray-500 font-medium" dir="ltr">
                            {{ $activity->created_at->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <x-heroicon-o-clipboard-document-list class="w-12 h-12 mx-auto text-gray-200 mb-3" />
                            <p class="text-gray-400 font-medium">لا توجد حركات مسجلة</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
        <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/30">
            {{ $activities->links() }}
        </div>
        @endif

    </div>
</x-archive-settings-layout>
