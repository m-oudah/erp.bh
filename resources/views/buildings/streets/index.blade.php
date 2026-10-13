<x-buildings-layout>
    <div class="space-y-6" x-data="streetManager()">

        {{-- Page Header --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">دليل الشوارع</h1>
                <p class="text-sm text-gray-500 mt-1">تصفح وتحديث أسماء وأرقام الشوارع وربطها بالمباني في بلدية بيت حانون</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1.5 rounded-xl border border-blue-100">
                    إجمالي: {{ number_format($stats['total']) }} شارع
                </span>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-4 border border-blue-100 shadow-[0_2px_10px_-3px_rgba(37,99,235,0.06)]">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <x-heroicon-s-map-pin class="w-4 h-4" />
                    </div>
                    <span class="text-xs font-bold text-gray-500">إجمالي الشوارع</span>
                </div>
                <div class="text-2xl font-extrabold text-blue-600">{{ number_format($stats['total']) }}</div>
                <div class="text-[10px] text-gray-400 mt-1">مسجلة في قاعدة البيانات</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-emerald-100 shadow-[0_2px_10px_-3px_rgba(16,185,129,0.06)]">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <x-heroicon-s-check-circle class="w-4 h-4" />
                    </div>
                    <span class="text-xs font-bold text-gray-500">شوارع مسمّاة</span>
                </div>
                <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($stats['named']) }}</div>
                <div class="text-[10px] text-gray-400 mt-1">
                    {{ $stats['total'] > 0 ? number_format(($stats['named'] / $stats['total']) * 100, 1) : 0 }}% من الإجمالي
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-[0_2px_10px_-3px_rgba(245,158,11,0.06)]">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                        <x-heroicon-s-question-mark-circle class="w-4 h-4" />
                    </div>
                    <span class="text-xs font-bold text-gray-500">شوارع بدون اسم</span>
                </div>
                <div class="text-2xl font-extrabold text-amber-600">{{ number_format($stats['unnamed']) }}</div>
                <div class="text-[10px] text-gray-400 mt-1">تحتاج إلى تسمية</div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-purple-100 shadow-[0_2px_10px_-3px_rgba(168,85,247,0.06)]">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                        <x-heroicon-s-building-office class="w-4 h-4" />
                    </div>
                    <span class="text-xs font-bold text-gray-500">تحتوي مباني</span>
                </div>
                <div class="text-2xl font-extrabold text-purple-600">{{ number_format($stats['with_buildings']) }}</div>
                <div class="text-[10px] text-gray-400 mt-1">مرتبطة بمبانٍ مسجلة</div>
            </div>
        </div>

        {{-- Search & Filters --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 md:p-5">
            <form action="{{ route('buildings.streets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4" id="street-filter-form">

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-black mb-1.5">البحث باسم الشارع أو رقمه</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="مثال: يوسف الكفارنة أو 9206..."
                               class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50 pr-10">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <x-heroicon-o-magnifying-glass class="w-4 h-4" />
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1.5">حالة التسمية والمباني</label>
                    <select name="filter_type" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                            onchange="document.getElementById('street-filter-form').submit()">
                        <option value="">جميع الشوارع</option>
                        <option value="named" @selected(request('filter_type') === 'named')>شوارع مسماة فقط</option>
                        <option value="unnamed" @selected(request('filter_type') === 'unnamed')>شوارع بدون اسم فقط</option>
                        <option value="with_buildings" @selected(request('filter_type') === 'with_buildings')>تحتوي مباني مسجلة</option>
                        <option value="no_buildings" @selected(request('filter_type') === 'no_buildings')>لا تحتوي مباني</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1.5">الترتيب</label>
                    <select name="sort" class="w-full border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-orange/30 focus:border-orange bg-gray-50/50"
                            onchange="document.getElementById('street-filter-form').submit()">
                        <option value="number_asc" @selected(request('sort', 'number_asc') === 'number_asc')>رقم الشارع (تصاعدي)</option>
                        <option value="number_desc" @selected(request('sort') === 'number_desc')>رقم الشارع (تنازلي)</option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>اسم الشارع (أبجدياً)</option>
                        <option value="buildings_desc" @selected(request('sort') === 'buildings_desc')>الأكثر مباني أولاً</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex items-center justify-between pt-1 border-t border-gray-100">
                    <div class="text-xs text-gray-400">
                        @if(request()->anyFilled(['search', 'filter_type', 'sort']))
                            <span class="text-black font-bold">فلاتر نشطة</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['search', 'filter_type']) || (request('sort') && request('sort') !== 'number_asc'))
                            <a href="{{ route('buildings.streets.index') }}"
                               class="text-xs text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-xl transition-colors flex items-center gap-1 font-semibold">
                                <x-heroicon-o-x-mark class="w-3.5 h-3.5" /> مسح الفلاتر
                            </a>
                        @endif
                        <button type="submit"
                                class="bg-navy hover:bg-navy-dark text-white font-semibold py-2 px-5 rounded-xl text-xs transition-colors flex items-center gap-1.5">
                            <x-heroicon-o-magnifying-glass class="w-3.5 h-3.5" />
                            تطبيق البحث
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- Global Notification Banner --}}
        <div x-show="globalMessage"
             x-cloak
             x-transition
             class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2 text-sm font-bold">
                <x-heroicon-s-check-circle class="w-5 h-5 text-emerald-600" />
                <span x-text="globalMessage"></span>
            </div>
            <button type="button" @click="globalMessage = ''" class="text-emerald-500 hover:text-emerald-700">
                <x-heroicon-o-x-mark class="w-4 h-4" />
            </button>
        </div>

        {{-- Streets Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm">
                    <thead>
                        <tr class="bg-gray-100 text-black font-bold text-xs border-b border-gray-200">
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold w-16">#</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold w-36">رقم الشارع</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold">اسم الشارع (انقر للتعديل المباشر)</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold w-48 text-center">المباني في الشارع</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-black font-bold w-32 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($streets as $index => $street)
                        <tr x-data="streetRow({
                                id: {{ $street->id }},
                                initialName: @js($street->street_name ?? ''),
                                streetNumber: @js($street->street_number),
                                updateUrl: '{{ route('buildings.streets.update', $street->id) }}'
                            })"
                            class="hover:bg-blue-50/20 transition-colors group">
                            
                            {{-- Index --}}
                            <td class="px-5 py-3.5 text-xs text-gray-400 font-mono">
                                {{ $streets->firstItem() + $index }}
                            </td>

                            {{-- Street Number --}}
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-mono font-bold text-xs bg-slate-100 text-slate-800 border border-slate-200 px-3 py-1.5 rounded-xl">
                                    <x-heroicon-o-hashtag class="w-3 h-3 text-slate-400" />
                                    {{ $street->street_number }}
                                </span>
                            </td>

                            {{-- Street Name with Inline Edit --}}
                            <td class="px-5 py-3.5">
                                {{-- View Mode --}}
                                <div x-show="!editing" class="flex items-center gap-3">
                                    <div @click="startEdit()"
                                         class="cursor-pointer group/name flex items-center gap-2 py-1 px-2.5 -mx-2.5 rounded-xl hover:bg-orange/5 hover:ring-1 hover:ring-orange/30 transition-all max-w-xl">
                                        <template x-if="currentName && currentName.trim() !== ''">
                                            <span class="font-bold text-gray-900 group-hover/name:text-orange-dark transition-colors text-sm"
                                                  x-text="currentName"></span>
                                        </template>
                                        <template x-if="!currentName || currentName.trim() === ''">
                                            <span class="text-xs text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-lg font-medium italic">
                                                (بدون اسم - اضغط لتسمية الشارع)
                                            </span>
                                        </template>
                                        <x-heroicon-o-pencil-square class="w-3.5 h-3.5 text-gray-300 group-hover/name:text-orange opacity-0 group-hover/name:opacity-100 transition-all shrink-0" />
                                    </div>

                                    {{-- Success badge flash --}}
                                    <span x-show="justSaved"
                                          x-cloak
                                          x-transition
                                          class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg">
                                        <x-heroicon-s-check class="w-3 h-3 text-emerald-600" />
                                        تم الحفظ
                                    </span>
                                </div>

                                {{-- Inline Edit Mode --}}
                                <div x-show="editing" x-cloak class="flex items-center gap-2 max-w-xl">
                                    <div class="relative flex-1">
                                        <input type="text"
                                               x-model="inputName"
                                               x-ref="inputField"
                                               @keydown.enter.prevent="save()"
                                               @keydown.escape.prevent="cancel()"
                                               :disabled="saving"
                                               placeholder="اكتب اسم الشارع الجديد..."
                                               class="w-full text-sm font-bold border-orange ring-2 ring-orange/20 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-orange bg-white">
                                        <template x-if="errorMessage">
                                            <p class="text-[11px] text-red-600 mt-1" x-text="errorMessage"></p>
                                        </template>
                                    </div>

                                    <button type="button"
                                            @click="save()"
                                            :disabled="saving"
                                            class="inline-flex items-center gap-1 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold text-xs px-3 py-2 rounded-xl transition-colors shrink-0">
                                        <template x-if="!saving">
                                            <span class="flex items-center gap-1">
                                                <x-heroicon-s-check class="w-3.5 h-3.5" />
                                                حفظ
                                            </span>
                                        </template>
                                        <template x-if="saving">
                                            <span class="flex items-center gap-1">
                                                <svg class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                جاري...
                                            </span>
                                        </template>
                                    </button>

                                    <button type="button"
                                            @click="cancel()"
                                            :disabled="saving"
                                            class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs px-3 py-2 rounded-xl transition-colors shrink-0">
                                        <x-heroicon-s-x-mark class="w-3.5 h-3.5" />
                                        إلغاء
                                    </button>
                                </div>
                            </td>

                            {{-- Buildings Count --}}
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                @if($street->buildings_count > 0)
                                    <a href="{{ route('buildings.buildings.index', ['street_number' => $street->street_number]) }}"
                                       title="عرض مباني هذا الشارع في إدارة المباني"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200/60 px-3 py-1.5 rounded-xl transition-all group/btn">
                                        <x-heroicon-s-building-office class="w-3.5 h-3.5 text-blue-500" />
                                        <span>{{ number_format($street->buildings_count) }} مبنى</span>
                                        <x-heroicon-o-arrow-left class="w-3 h-3 text-blue-400 group-hover/btn:-translate-x-0.5 transition-transform" />
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-400 bg-gray-50 border border-gray-100 px-2.5 py-1 rounded-xl">
                                        0 مبنى
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button"
                                            @click="startEdit()"
                                            x-show="!editing"
                                            title="تعديل اسم الشارع"
                                            class="inline-flex items-center gap-1 text-orange hover:text-orange-dark bg-orange/10 hover:bg-orange/20 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                        <x-heroicon-o-pencil-square class="w-4 h-4" />
                                        تعديل
                                    </button>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-3 text-gray-400">
                                        <x-heroicon-o-map-pin class="w-8 h-8" />
                                    </div>
                                    <p class="text-base font-bold text-gray-700">لا توجد شوارع مطابقة للبحث</p>
                                    <p class="text-xs text-gray-400 mt-1">جرّب تغيير كلمات البحث أو إعادة ضبط خيارات التصفية</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($streets->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $streets->links() }}
            </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
        function streetManager() {
            return {
                globalMessage: ''
            };
        }

        function streetRow(config) {
            return {
                id: config.id,
                currentName: config.initialName || '',
                inputName: config.initialName || '',
                updateUrl: config.updateUrl,
                editing: false,
                saving: false,
                justSaved: false,
                errorMessage: '',

                startEdit() {
                    this.inputName = this.currentName;
                    this.errorMessage = '';
                    this.editing = true;
                    this.$nextTick(() => {
                        if (this.$refs.inputField) {
                            this.$refs.inputField.focus();
                            this.$refs.inputField.select();
                        }
                    });
                },

                cancel() {
                    this.editing = false;
                    this.inputName = this.currentName;
                    this.errorMessage = '';
                },

                async save() {
                    if (this.saving) return;
                    this.saving = true;
                    this.errorMessage = '';

                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                    try {
                        const response = await fetch(this.updateUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                street_name: this.inputName
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            this.currentName = data.street_name || '';
                            this.editing = false;
                            this.justSaved = true;
                            setTimeout(() => {
                                this.justSaved = false;
                            }, 3000);
                        } else {
                            this.errorMessage = data.message || 'حدث خطأ أثناء حفظ اسم الشارع';
                        }
                    } catch (err) {
                        this.errorMessage = 'تعذر الاتصال بالخادم. يرجى المحاولة مرة أخرى.';
                    } finally {
                        this.saving = false;
                    }
                }
            };
        }
    </script>
    @endpush
</x-buildings-layout>
