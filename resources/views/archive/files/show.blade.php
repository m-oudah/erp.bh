<x-archive-layout>
    <div class="flex justify-between items-center mb-2 px-1">
        <h3 class="font-bold text-lg text-black flex items-center gap-2">
            <x-heroicon-s-folder-open class="w-6 h-6 text-orange" />
            تفاصيل الملف: {{ $file->file_no }}
        </h3>
        <a href="{{ route('archive.files.index') }}" class="flex items-center gap-2 text-sm font-bold text-black hover:text-navy-dark transition-colors bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl">
            الرجوع للقائمة
            <x-heroicon-o-arrow-left class="w-4 h-4" />
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl font-bold text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- File Info Card -->
    <div class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden mb-6">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h4 class="font-bold text-navy-dark text-base">البيانات الأساسية</h4>
            @can('archive.files.edit')
            <a href="{{ route('archive.files.edit', $file->id) }}" class="text-orange hover:text-orange-dark text-sm font-bold flex items-center gap-2 bg-orange-50 hover:bg-orange-100 transition-colors px-4 py-2 rounded-xl">
                <x-heroicon-o-pencil-square class="w-5 h-5" />
                تعديل البيانات
            </a>
            @endcan
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="text-xs text-black font-bold mb-1">نوع الملف</div>
                <div class="font-bold text-gray-800 bg-orange/10 text-orange-dark inline-block px-2 py-0.5 rounded-md">{{ $file->fileType->name ?? 'غير محدد' }}</div>
            </div>
            <div>
                <div class="text-xs text-black font-bold mb-1">رقم الملف</div>
                <div class="font-bold text-gray-800">{{ $file->file_no }}</div>
            </div>
            @if($file->file_name && $file->file_name !== $file->file_no)
            <div>
                <div class="text-xs text-black font-bold mb-1">اسم المواطن / الملف</div>
                <div class="font-bold text-gray-800">{{ $file->file_name }}</div>
            </div>
            @endif
            
            @if($file->fileType && $file->fileType->fields)
                @foreach($file->fileType->fields as $field)
                <div class="{{ $field->field_type === 'textarea' ? 'md:col-span-3' : '' }}">
                    <div class="text-xs text-black font-bold mb-1">{{ $field->field_label }}</div>
                    <div class="font-bold text-gray-800">
                        @php
                            $value = $file->dynamic_data[$field->field_name] ?? null;
                        @endphp
                        @if($value !== null && $value !== '')
                            {!! nl2br(e($value)) !!}
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif

            <div>
                <div class="text-xs text-black font-bold mb-1">تاريخ الإضافة</div>
                <div class="font-bold text-gray-800">{{ $file->created_at->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div x-data="{ 
        viewMode: 'grid', 
        selectedDocs: [],
        viewerOpen: false,
        viewerIndex: 0,
        documents: [
            @foreach($file->documents as $doc)
                { id: {{ $doc->id }}, name: '{{ addslashes($doc->document_name) }}', type: '{{ strtolower($doc->document_type) }}', url: '{{ Storage::url($doc->document_path) }}' }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],
        openViewer(index) {
            this.viewerIndex = index;
            this.viewerOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeViewer() {
            this.viewerOpen = false;
            document.body.style.overflow = '';
        },
        nextDoc() {
            if(this.viewerIndex < this.documents.length - 1) this.viewerIndex++;
        },
        prevDoc() {
            if(this.viewerIndex > 0) this.viewerIndex--;
        },
        get currentDoc() {
            return this.documents[this.viewerIndex] || null;
        }
    }" @keydown.escape.window="closeViewer()" @keydown.right.window="nextDoc()" @keydown.left.window="prevDoc()" class="bg-white rounded-3xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center flex-wrap gap-4">
            <h4 class="font-bold text-navy-dark text-base flex items-center gap-2">
                <x-heroicon-o-document-duplicate class="w-6 h-6 text-black" />
                الوثائق المؤرشفة ({{ $file->documents->count() }})
            </h4>
            <div class="flex items-center gap-4">
                <!-- Bulk Delete -->
                @can('archive.documents.delete')
                <form action="{{ route('archive.documents.bulk-destroy') }}" method="POST" x-show="selectedDocs.length > 0" class="mr-2" style="display: none;" onsubmit="return confirm('هل أنت متأكد من حذف جميع الوثائق المحددة؟')">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selectedDocs">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                    <button type="submit" class="text-red-500 hover:text-white hover:bg-red-500 text-sm font-bold flex items-center gap-2 bg-red-50 transition-colors px-4 py-2 rounded-xl border border-red-100 shadow-sm">
                        <x-heroicon-s-trash class="w-5 h-5" />
                        حذف المحدد (<span x-text="selectedDocs.length"></span>)
                    </button>
                </form>
                @endcan
                <!-- View Toggle -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button type="button" @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white shadow-sm text-navy' : 'text-gray-400 hover:text-gray-600'" class="p-1.5 rounded-md transition-all" title="عرض كشبكة">
                        <x-heroicon-s-squares-2x2 class="w-5 h-5" />
                    </button>
                    <button type="button" @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white shadow-sm text-navy' : 'text-gray-400 hover:text-gray-600'" class="p-1.5 rounded-md transition-all" title="عرض كقائمة">
                        <x-heroicon-s-list-bullet class="w-5 h-5" />
                    </button>
                </div>
                <!-- Upload Button -->
                @can('archive.documents.create')
                <button type="button" @click="$dispatch('open-upload-modal')" class="text-white hover:bg-navy-light text-sm font-bold flex items-center gap-2 bg-navy transition-colors px-4 py-2 rounded-xl shadow-md">
                    <x-heroicon-s-plus class="w-5 h-5" />
                    إضافة وثائق
                </button>
                @endcan
            </div>
        </div>
        
        <div class="p-8">
            <!-- Upload Form Removed - Replaced by Modal -->

            <!-- Documents Grid View -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($file->documents as $document)
                <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow group relative" :class="selectedDocs.includes('{{ $document->id }}') ? 'ring-2 ring-red-400 shadow-md' : ''">
                    <!-- Checkbox -->
                    @can('archive.documents.delete')
                    <div class="absolute top-3 right-3 z-10">
                        <input type="checkbox" value="{{ $document->id }}" x-model="selectedDocs" class="w-5 h-5 rounded border-gray-300 text-red-500 focus:ring-red-500 bg-white shadow-sm cursor-pointer">
                    </div>
                    @endcan
                    
                    <div class="h-32 bg-gray-50 flex items-center justify-center relative border-b border-gray-50 cursor-pointer" @click="
                        if(selectedDocs.includes('{{ $document->id }}')) {
                            selectedDocs = selectedDocs.filter(id => id !== '{{ $document->id }}')
                        } else {
                            selectedDocs.push('{{ $document->id }}')
                        }
                    ">
                        @if(in_array(strtolower($document->document_type), ['jpg', 'jpeg', 'png']))
                            <img src="{{ Storage::url($document->document_path) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <x-heroicon-o-document class="w-10 h-10 text-gray-300" />
                        @endif
                        
                        <div class="absolute inset-0 bg-navy/80 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3 backdrop-blur-sm">
                            <button type="button" @click.stop="openViewer({{ $loop->index }})" class="w-10 h-10 rounded-full bg-white text-navy flex items-center justify-center hover:bg-orange hover:text-white transition-colors" title="عرض الملف">
                                <x-heroicon-s-eye class="w-5 h-5" />
                            </button>
                            @can('archive.documents.delete')
                            <form action="{{ route('archive.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-full bg-white text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors" title="حذف الملف">
                                    <x-heroicon-s-trash class="w-5 h-5" />
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-black text-sm truncate" title="{{ $document->document_name }}">{{ $document->document_name }}</h4>
                        <div class="text-[10px] text-black mt-1.5 flex justify-between font-bold">
                            <span class="flex items-center gap-1">
                                <x-heroicon-o-tag class="w-3 h-3 text-black" />
                                {{ $document->category ? $document->category->name : 'غير مصنف' }}
                            </span>
                            <span>{{ $document->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center text-gray-500 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <x-heroicon-o-document-magnifying-glass class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                    <p class="text-sm">لا توجد وثائق مؤرشفة في هذا الملف.<br>استخدم النموذج أعلاه لإضافة وثائق.</p>
                </div>
                @endforelse
            </div>

            <!-- Documents List View -->
            <div x-cloak x-show="viewMode === 'list'" class="overflow-x-auto border border-gray-100 rounded-2xl">
                <table class="w-full text-right text-sm text-gray-600">
                    <thead class="bg-gray-100 text-black text-xs font-bold border-b border-gray-200 uppercase">
                        <tr>
                            <th class="px-6 py-4 w-16 text-center">
                                <!-- Checkbox -->
                            </th>
                            <th class="px-6 py-4 font-bold text-black">الوثيقة</th>
                            <th class="px-6 py-4 font-bold text-black">التصنيف</th>
                            <th class="px-6 py-4 font-bold text-black">النوع</th>
                            <th class="px-6 py-4 font-bold text-black">تاريخ الإضافة</th>
                            <th class="px-6 py-4 text-center font-bold text-black">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($file->documents as $document)
                        <tr class="hover:bg-gray-50/50 transition-colors" :class="selectedDocs.includes('{{ $document->id }}') ? 'bg-red-50/30' : ''">
                            <td class="px-6 py-4 text-center">
                                @can('archive.documents.delete')
                                <input type="checkbox" value="{{ $document->id }}" x-model="selectedDocs" class="w-5 h-5 rounded border-gray-300 text-red-500 focus:ring-red-500 bg-white shadow-sm cursor-pointer">
                                @endcan
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center shrink-0 border border-gray-200/50">
                                        @if(in_array(strtolower($document->document_type), ['jpg', 'jpeg', 'png']))
                                            <img src="{{ Storage::url($document->document_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <x-heroicon-o-document class="w-6 h-6 text-gray-400" />
                                        @endif
                                    </div>
                                    <span class="font-bold text-black text-base">{{ $document->document_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($document->category)
                                    <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-bold border border-blue-100 flex items-center gap-1 w-max">
                                        <x-heroicon-s-tag class="w-3 h-3" />
                                        {{ $document->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs font-medium">غير مصنف</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-black">{{ strtoupper($document->document_type) }}</td>
                            <td class="px-6 py-4 font-bold text-black">{{ $document->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" @click.prevent="openViewer({{ $loop->index }})" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="عرض">
                                        <x-heroicon-s-eye class="w-4 h-4" />
                                    </button>
                                    @can('archive.documents.delete')
                                    <form action="{{ route('archive.documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوثيقة؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors" title="حذف">
                                            <x-heroicon-s-trash class="w-4 h-4" />
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50/50">
                                <x-heroicon-o-document-magnifying-glass class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                                <p class="text-sm">لا توجد وثائق مؤرشفة في هذا الملف.<br>استخدم النموذج أعلاه لإضافة وثائق.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Document Viewer Modal -->
        <div x-show="viewerOpen" class="fixed inset-0 z-[100] flex items-center justify-center" style="display: none;">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" @click="closeViewer()"></div>
            
            <!-- Close Button -->
            <button @click="closeViewer()" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors p-2 bg-black/20 hover:bg-black/40 rounded-full z-10">
                <x-heroicon-o-x-mark class="w-8 h-8" />
            </button>

            <!-- Navigation Controls -->
            <button @click="prevDoc()" x-show="viewerIndex > 0" class="absolute right-6 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors p-3 bg-black/20 hover:bg-black/40 rounded-full z-10">
                <x-heroicon-o-chevron-right class="w-10 h-10" />
            </button>
            <button @click="nextDoc()" x-show="viewerIndex < documents.length - 1" class="absolute left-6 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors p-3 bg-black/20 hover:bg-black/40 rounded-full z-10">
                <x-heroicon-o-chevron-left class="w-10 h-10" />
            </button>

            <!-- Content Area -->
            <div class="w-full max-w-5xl max-h-screen p-6 flex flex-col items-center justify-center z-10 pointer-events-none">
                <!-- Header / Title -->
                <div class="text-white mb-4 text-center pointer-events-auto">
                    <h3 class="text-xl font-bold" x-text="currentDoc?.name"></h3>
                    <div class="text-white/60 text-sm mt-1" x-text="(viewerIndex + 1) + ' / ' + documents.length"></div>
                </div>

                <!-- Viewer -->
                <div class="w-full bg-white/5 rounded-2xl p-4 flex items-center justify-center shadow-2xl relative pointer-events-auto" style="height: 80vh;">
                    <template x-if="currentDoc">
                        <div class="w-full h-full flex items-center justify-center">
                            <!-- Image Viewer -->
                            <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(currentDoc.type)">
                                <img :src="currentDoc.url" class="max-w-full max-h-full object-contain rounded-xl" alt="">
                            </template>
                            <!-- PDF Viewer -->
                            <template x-if="currentDoc.type === 'pdf'">
                                <iframe :src="currentDoc.url" class="w-full h-full rounded-xl bg-white border-0"></iframe>
                            </template>
                            <!-- Unsupported Type -->
                            <template x-if="!['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'].includes(currentDoc.type)">
                                <div class="text-center text-white/50">
                                    <x-heroicon-o-document class="w-24 h-24 mx-auto mb-4 opacity-50" />
                                    <p class="text-lg mb-4">لا يمكن معاينة هذا النوع من الملفات مباشرة.</p>
                                    <a :href="currentDoc.url" target="_blank" class="inline-block px-6 py-3 bg-orange text-white rounded-xl font-bold hover:bg-orange-dark transition-colors">
                                        تحميل / فتح الملف
                                    </a>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-data="documentUploadModal()" 
         x-show="isOpen" 
         @open-upload-modal.window="openModal()"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
         
        <div class="flex items-center justify-center min-h-screen px-2.5 py-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div x-show="isOpen" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="isOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block w-full max-w-lg sm:max-w-2xl px-4 pt-5 pb-5 sm:p-8 overflow-hidden text-right align-middle transition-all transform bg-white rounded-3xl shadow-xl sm:my-8 border border-gray-100 relative">
                
                <div class="absolute top-0 left-0 pt-5 pl-5 sm:pt-6 sm:pl-6 z-10">
                    <button @click="closeModal()" type="button" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">إغلاق</span>
                        <x-heroicon-o-x-mark class="w-6 h-6" />
                    </button>
                </div>
                
                <div class="w-full">
                    <!-- Mobile-only Cloud Icon -->
                    <div class="flex sm:hidden items-center justify-center flex-shrink-0 w-12 h-12 mx-auto mb-3 bg-blue-50 rounded-full">
                        <x-heroicon-o-cloud-arrow-up class="w-6 h-6 text-blue-600" />
                    </div>

                    <div class="text-center sm:text-right">
                        <h3 class="text-lg font-bold leading-6 text-black" id="modal-title">إضافة وثائق جديدة</h3>
                    </div>
                        
                        <!-- Step 1: Drag & Drop Zone -->
                        <div x-show="step === 1" class="mt-6">
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" 
                                       class="flex flex-col items-center justify-center w-full h-52 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
                                       @dragover.prevent="dragOver = true"
                                       @dragleave.prevent="dragOver = false"
                                       @drop.prevent="handleDrop($event)"
                                       :class="dragOver ? 'border-navy bg-blue-50' : ''">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 pointer-events-none">
                                        <x-heroicon-o-document-plus class="w-12 h-12 mb-4 text-black" />
                                        <p class="mb-2 text-sm text-black font-semibold"><span class="font-bold">انقر للرفع</span> أو قم بسحب وإفلات الملفات هنا</p>
                                        <p class="text-xs text-black font-medium">PDF, DOC, DOCX, PNG, JPG (الحد الأقصى 10MB)</p>
                                    </div>
                                    <input id="dropzone-file" type="file" class="hidden" multiple accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" @change="handleFileSelect($event)" />
                                </label>
                            </div>

                            <!-- Camera / Alternative Input Options -->
                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 border-t border-gray-200"></div>
                                <span class="text-xs text-gray-400 font-bold shrink-0">أو التقط من</span>
                                <div class="flex-1 border-t border-gray-200"></div>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <!-- Live Camera Capture via getUserMedia -->
                                <button type="button"
                                        @click="$dispatch('open-camera-capture')"
                                        class="flex flex-col items-center justify-center gap-2 py-4 px-3 rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 transition-all group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 group-hover:text-blue-700 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                    </svg>
                                    <span class="text-xs font-bold text-blue-600 group-hover:text-blue-800 text-center">كاميرا الجهاز</span>
                                </button>

                                <!-- Gallery / Files picker -->
                                <button type="button"
                                        @click="$refs.galleryInput.click()"
                                        class="flex flex-col items-center justify-center gap-2 py-4 px-3 rounded-2xl border-2 border-dashed border-green-200 bg-green-50 hover:bg-green-100 hover:border-green-400 transition-all group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500 group-hover:text-green-700 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    <span class="text-xs font-bold text-green-600 group-hover:text-green-800 text-center">معرض الصور</span>
                                </button>
                            </div>

                            <!-- Hidden gallery input -->
                            <input x-ref="galleryInput" type="file" class="hidden" multiple accept="image/*" @change="handleFileSelect($event)" />
                        </div>

                        <!-- Step 2: Selected Files List -->
                        <div x-show="step === 2" class="mt-6 text-right">
                            <h4 class="font-bold text-black mb-4 border-r-4 border-orange pr-2">الملفات المحددة ( <span x-text="files.length"></span> )</h4>
                            
                            <div class="space-y-4 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 border border-gray-100 rounded-xl relative">
                                        <!-- Thumbnail -->
                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-white border border-gray-200 flex items-center justify-center shrink-0">
                                            <template x-if="file.isImage">
                                                <img :src="file.previewUrl" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!file.isImage">
                                                <x-heroicon-s-document-text class="w-6 h-6 text-gray-400" />
                                            </template>
                                        </div>
                                        
                                        <!-- Title and Category Input -->
                                        <div class="flex-1 min-w-0 grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-bold text-black mb-1">عنوان الوثيقة</label>
                                                <input type="text" x-model="file.title" class="w-full text-sm rounded-lg border-gray-200 focus:ring-navy focus:border-navy h-9 font-semibold text-black" placeholder="أدخل عنواناً للوثيقة...">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-black mb-1">تصنيف الوثيقة</label>
                                                <select x-model="file.category_id" class="w-full text-sm rounded-lg border-gray-200 focus:ring-navy focus:border-navy h-9 font-semibold text-black">
                                                    <option value="">(غير مصنف)</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Status Indicator -->
                                        <div x-show="file.status === 'uploading'" class="shrink-0 flex items-center">
                                            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <div x-show="file.status === 'success'" class="shrink-0">
                                            <x-heroicon-s-check-circle class="w-6 h-6 text-green-500" />
                                        </div>
                                        <div x-show="file.status === 'error'" class="shrink-0 group relative">
                                            <x-heroicon-s-x-circle class="w-6 h-6 text-red-500 cursor-pointer" />
                                            <!-- Simple tooltip -->
                                            <div class="absolute bottom-full mb-2 hidden group-hover:block w-48 p-2 bg-red-100 text-red-700 text-xs rounded shadow-sm" x-text="file.errorMessage"></div>
                                        </div>
                                        
                                        <!-- Remove Button -->
                                        <button x-show="file.status === 'pending'" type="button" @click="removeFile(index)" class="shrink-0 text-gray-400 hover:text-red-500 p-1 rounded-md hover:bg-red-50 transition-colors">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                
                <div class="mt-8 sm:flex sm:flex-row-reverse">
                    <!-- Action Buttons for Step 2 -->
                    <template x-if="step === 2">
                        <div class="w-full flex sm:flex-row-reverse gap-3">
                            <button type="button" @click="uploadFiles()" :disabled="isUploading" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2 bg-navy text-base font-bold text-white hover:bg-navy-light focus:outline-none sm:w-auto sm:text-sm disabled:opacity-50 flex items-center gap-2">
                                <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
                                <span x-text="isUploading ? 'جاري الرفع...' : 'حفظ ورفع'"></span>
                            </button>
                            <button type="button" @click="addMoreFiles()" :disabled="isUploading" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-bold text-black hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm disabled:opacity-50">
                                إضافة المزيد
                            </button>
                            <button type="button" @click="closeModal()" :disabled="isUploading" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-bold text-black hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm mr-auto disabled:opacity-50">
                                إلغاء
                            </button>
                        </div>
                    </template>
                    
                    <!-- Action Buttons for Step 1 -->
                    <template x-if="step === 1">
                        <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-6 py-2 bg-white text-base font-bold text-black hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                            إلغاء
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Camera Capture Modal ===== -->
    <div x-data="cameraCapture()"
         x-show="isOpen"
         @open-camera-capture.window="openCamera()"
         style="display: none; z-index: 999999;"
         class="fixed inset-0 flex items-center justify-center"
         @keydown.escape.window="closeCamera()">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="closeCamera()"></div>

        <!-- Modal Panel -->
        <div class="relative z-10 bg-gray-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col"
             style="width: min(96vw, 640px); max-height: 90vh;">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-white/10">
                <h3 class="text-white font-bold text-base flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                    </svg>
                    كاميرا الجهاز
                </h3>
                <!-- Flip Camera -->
                <div class="flex items-center gap-2">
                    <button type="button" @click="flipCamera()"
                            class="text-white/60 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors" title="تبديل الكاميرا">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                    <button type="button" @click="closeCamera()"
                            class="text-white/60 hover:text-white p-2 rounded-full hover:bg-white/10 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Video / Preview Area -->
            <div class="relative bg-black flex items-center justify-center" style="height: 360px;">

                <!-- Error State -->
                <div x-show="errorMsg" class="flex flex-col items-center gap-4 text-center px-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <p class="text-yellow-200 font-bold text-sm leading-relaxed" x-text="errorMsg"></p>

                    <!-- Fallback: native camera input (works on HTTP on mobile) -->
                    <template x-if="noSecureContext">
                        <label class="flex flex-col items-center gap-2 cursor-pointer group">
                            <div class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                </svg>
                                فتح الكاميرا (وضع الجوال)
                            </div>
                            <input type="file" accept="image/*" capture="environment" class="hidden"
                                   @change="
                                       const f = $event.target.files[0];
                                       if (f) {
                                           window.dispatchEvent(new CustomEvent('camera-photo-captured', { detail: { file: f } }));
                                           closeCamera();
                                       }
                                   ">
                            <span class="text-white/40 text-xs">يعمل على الجوال فقط</span>
                        </label>
                    </template>

                    <!-- Retry button (for other errors) -->
                    <template x-if="!noSecureContext">
                        <button type="button" @click="openCamera()" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700">إعادة المحاولة</button>
                    </template>
                </div>

                <!-- Loading -->
                <div x-show="!errorMsg && !streamReady" class="flex flex-col items-center gap-3">
                    <svg class="animate-spin w-10 h-10 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <p class="text-white/60 text-sm">جاري تفعيل الكاميرا...</p>
                </div>

                <!-- Live Video -->
                <video x-ref="videoEl"
                       x-show="streamReady && !captured"
                       autoplay playsinline muted
                       class="w-full h-full object-cover"
                       style="display:none;"></video>

                <!-- Captured Preview -->
                <canvas x-ref="canvasEl"
                        x-show="captured"
                        class="w-full h-full object-contain"
                        style="display:none;"></canvas>

                <!-- Capture Ring Overlay -->
                <div x-show="streamReady && !captured" class="absolute inset-0 pointer-events-none border-4 border-white/10 rounded-none"></div>
            </div>

            <!-- Controls -->
            <div class="px-6 py-5 flex items-center justify-center gap-4">

                <!-- Retake -->
                <button type="button" x-show="captured" @click="retake()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    إعادة التصوير
                </button>

                <!-- Shutter -->
                <button type="button" x-show="streamReady && !captured" @click="capturePhoto()"
                        class="w-16 h-16 rounded-full bg-white border-4 border-white/40 hover:scale-95 active:scale-90 transition-transform shadow-lg"
                        title="التقاط صورة"></button>

                <!-- Use Photo -->
                <button type="button" x-show="captured" @click="usePhoto()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    استخدام الصورة
                </button>
            </div>
        </div>
    </div>
    <!-- ===== End Camera Modal ===== -->

    @push('scripts')
    <script>
        // ===== Camera Capture Component =====
        function cameraCapture() {
            return {
                isOpen: false,
                streamReady: false,
                captured: false,
                errorMsg: '',
                noSecureContext: false,
                stream: null,
                facingMode: 'environment', // start with rear camera

                async openCamera() {
                    this.isOpen = true;
                    this.streamReady = false;
                    this.captured = false;
                    this.errorMsg = '';
                    this.noSecureContext = false;
                    document.body.style.overflow = 'hidden';
                    await this.$nextTick();
                    await this.startStream();
                },

                async startStream() {
                    this.stopStream();
                    this.streamReady = false;
                    this.errorMsg = '';

                    // Check if getUserMedia is supported (requires HTTPS or localhost)
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.errorMsg = 'يتطلب الوصول للكاميرا اتصالاً آمناً (HTTPS). الرجاء استخدام الزر أدناه لالتقاط صورة عبر تطبيق الكاميرا مباشرةً.';
                        this.noSecureContext = true;
                        return;
                    }

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: this.facingMode, width: { ideal: 1280 }, height: { ideal: 720 } },
                            audio: false
                        });
                        const video = this.$refs.videoEl;
                        video.srcObject = this.stream;
                        video.onloadedmetadata = () => {
                            video.play();
                            this.streamReady = true;
                        };
                    } catch (err) {
                        if (err.name === 'NotAllowedError') {
                            this.errorMsg = 'تم رفض الإذن بالوصول للكاميرا. يرجى السماح بالوصول من إعدادات المتصفح.';
                        } else if (err.name === 'NotFoundError') {
                            this.errorMsg = 'لا توجد كاميرا متاحة على هذا الجهاز.';
                        } else {
                            this.errorMsg = 'تعذر فتح الكاميرا: ' + err.message;
                        }
                    }
                },

                stopStream() {
                    if (this.stream) {
                        this.stream.getTracks().forEach(t => t.stop());
                        this.stream = null;
                    }
                },

                closeCamera() {
                    this.stopStream();
                    this.isOpen = false;
                    this.streamReady = false;
                    this.captured = false;
                    document.body.style.overflow = 'auto';
                },

                async flipCamera() {
                    this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
                    this.captured = false;
                    await this.startStream();
                },

                capturePhoto() {
                    const video = this.$refs.videoEl;
                    const canvas = this.$refs.canvasEl;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.captured = true;
                    // Pause the video stream (keep track alive so retake works)
                    video.pause();
                },

                retake() {
                    this.captured = false;
                    this.$refs.videoEl.play();
                },

                usePhoto() {
                    const canvas = this.$refs.canvasEl;
                    canvas.toBlob((blob) => {
                        const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                        const file = new File([blob], `camera-${timestamp}.jpg`, { type: 'image/jpeg' });
                        // Send file to the upload modal
                        window.dispatchEvent(new CustomEvent('camera-photo-captured', { detail: { file } }));
                        this.closeCamera();
                    }, 'image/jpeg', 0.92);
                }
            };
        }

        // ===== Document Upload Modal =====
        function documentUploadModal() {
            return {
                isOpen: false,
                step: 1,
                dragOver: false,
                files: [], // Array of objects
                isUploading: false,
                archiveFileId: {{ $file->id }},
                
                openModal() {
                    this.isOpen = true;
                    this.step = 1;
                    this.files = [];
                    this.isUploading = false;
                    document.body.style.overflow = 'hidden';
                    // Listen for photos from the camera modal
                    if (!this._cameraListener) {
                        this._cameraListener = (e) => {
                            this.processFiles([e.detail.file]);
                        };
                        window.addEventListener('camera-photo-captured', this._cameraListener);
                    }
                },
                
                closeModal() {
                    if (this.isUploading) return;
                    this.isOpen = false;
                    document.body.style.overflow = 'auto';
                    
                    this.files.forEach(f => {
                        if(f.previewUrl) URL.revokeObjectURL(f.previewUrl);
                    });
                },
                
                handleDrop(e) {
                    this.dragOver = false;
                    if (e.dataTransfer.files.length > 0) {
                        this.processFiles(e.dataTransfer.files);
                    }
                },
                
                handleFileSelect(e) {
                    if (e.target.files.length > 0) {
                        this.processFiles(e.target.files);
                    }
                },
                
                processFiles(fileList) {
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/png', 'image/jpeg', 'image/jpg'];
                    
                    for (let i = 0; i < fileList.length; i++) {
                        const file = fileList[i];
                        
                        if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|doc|docx|png|jpg|jpeg)$/i)) {
                            alert('نوع الملف غير مدعوم: ' + file.name);
                            continue;
                        }
                        
                        const defaultTitle = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
                        const isImage = file.type.startsWith('image/');
                        
                        this.files.push({
                            raw: file,
                            title: defaultTitle,
                            category_id: '',
                            isImage: isImage,
                            previewUrl: isImage ? URL.createObjectURL(file) : null,
                            status: 'pending',
                            errorMessage: ''
                        });
                    }
                    
                    if (this.files.length > 0) {
                        this.step = 2;
                    }
                },
                
                removeFile(index) {
                    const file = this.files[index];
                    if(file.previewUrl) URL.revokeObjectURL(file.previewUrl);
                    this.files.splice(index, 1);
                    
                    if (this.files.length === 0) {
                        this.step = 1;
                    }
                },
                
                addMoreFiles() {
                    this.step = 1;
                },
                
                async uploadFiles() {
                    if (this.files.length === 0) return;
                    this.isUploading = true;
                    
                    let allSuccess = true;
                    
                    const uploadPromises = this.files.map(async (fileObj) => {
                        if (fileObj.status === 'success') return;
                        
                        fileObj.status = 'uploading';
                        
                        const formData = new FormData();
                        formData.append('archive_file_id', this.archiveFileId);
                        formData.append('document_file', fileObj.raw);
                        formData.append('document_name', fileObj.title || 'بدون عنوان');
                        if (fileObj.category_id) {
                            formData.append('document_category_id', fileObj.category_id);
                        }
                        
                        try {
                            const response = await fetch('{{ route('archive.documents.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: formData
                            });
                            
                            const data = await response.json();
                            
                            if (response.ok && data.success) {
                                fileObj.status = 'success';
                            } else {
                                fileObj.status = 'error';
                                fileObj.errorMessage = data.message || 'حدث خطأ غير معروف';
                                allSuccess = false;
                            }
                        } catch (error) {
                            fileObj.status = 'error';
                            fileObj.errorMessage = 'فشل الاتصال بالخادم';
                            allSuccess = false;
                        }
                    });
                    
                    await Promise.all(uploadPromises);
                    
                    this.isUploading = false;
                    
                    if (allSuccess) {
                        window.location.reload();
                    } else {
                        alert('حدثت أخطاء أثناء رفع بعض الملفات. يرجى مراجعتها.');
                    }
                }
            };
        }
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
    @endpush
</x-archive-layout>
