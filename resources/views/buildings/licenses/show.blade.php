<x-buildings-layout>
    <script>
        function licenseCitizenLinking(licenseId, initialName, initialIdCard) {
            return {
                tab: 'basic',
                showModal: false,
                searchQuery: '',
                searchResults: [],
                selectedCitizen: null,
                isSearching: false,
                isLinking: false,
                isUnlinking: false,
                errorMessage: '',
                successMessage: '',

                openModal() {
                    this.showModal = true;
                    this.searchQuery = '';
                    this.searchResults = [];
                    this.selectedCitizen = null;
                    this.errorMessage = '';
                    this.successMessage = '';
                    document.body.style.overflow = 'hidden';
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.errorMessage = '';
                    this.successMessage = '';
                    document.body.style.overflow = '';
                },

                async performSearch() {
                    const q = (this.searchQuery || '').trim();
                    if (q.length < 2) {
                        this.searchResults = [];
                        this.isSearching = false;
                        return;
                    }

                    this.isSearching = true;
                    this.errorMessage = '';
                    try {
                        const params = new URLSearchParams({ q });
                        const res = await fetch(`{{ route('buildings.licenses.search-citizens') }}?${params.toString()}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        if (json.status === 'success') {
                            this.searchResults = json.data;
                        } else {
                            this.searchResults = [];
                        }
                    } catch (err) {
                        console.error(err);
                        this.searchResults = [];
                    } finally {
                        this.isSearching = false;
                    }
                },

                selectCitizen(citizen) {
                    this.selectedCitizen = citizen;
                    this.errorMessage = '';
                },

                async linkSelectedCitizen() {
                    if (!this.selectedCitizen) return;
                    this.isLinking = true;
                    this.errorMessage = '';
                    try {
                        const res = await fetch(`{{ url('/buildings/licenses') }}/${licenseId}/link-citizen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                client_id: this.selectedCitizen.id,
                                id_number: this.selectedCitizen.id_number
                            })
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            this.successMessage = json.message || 'تم ربط بيانات مقدم الطلب بالمواطن بنجاح!';
                            setTimeout(() => {
                                window.location.reload();
                            }, 800);
                        } else {
                            this.errorMessage = json.message || 'حدث خطأ أثناء الربط، يرجى المحاولة مرة أخرى.';
                        }
                    } catch (err) {
                        this.errorMessage = 'فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.';
                    } finally {
                        this.isLinking = false;
                    }
                },

                async unlinkCitizen() {
                    if (!confirm('هل أنت متأكد من رغبتك في إزالة ربط المواطن من هذه المعاملة؟ ستصبح بيانات مقدم الطلب فارغة.')) {
                        return;
                    }
                    this.isUnlinking = true;
                    try {
                        const res = await fetch(`{{ url('/buildings/licenses') }}/${licenseId}/unlink-citizen`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const json = await res.json();
                        if (res.ok && json.status === 'success') {
                            if (this.showModal) {
                                this.closeModal();
                            }
                            window.location.reload();
                        } else {
                            alert(json.message || 'حدث خطأ أثناء إزالة ربط المواطن.');
                        }
                    } catch (err) {
                        alert('فشل الاتصال بالخادم، يرجى المحاولة لاحقاً.');
                    } finally {
                        this.isUnlinking = false;
                    }
                }
            };
        }
        window.licenseCitizenLinking = licenseCitizenLinking;
        if (window.Alpine) {
            window.Alpine.data('licenseCitizenLinking', licenseCitizenLinking);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('licenseCitizenLinking', licenseCitizenLinking);
            });
        }
    </script>

    <div x-data="licenseCitizenLinking({{ $license->id }}, {{ json_encode($license->full_name ?? '') }}, {{ json_encode($license->id_card ?? '') }})" class="space-y-6">
        
        <!-- Header -->
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('buildings.licenses.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-700 p-2 rounded-xl transition-colors">
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        معاملة ترخيص رقم: {{ $license->id }}
                        @if($license->status == 1)
                            <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-md font-bold">فعال</span>
                        @elseif($license->status == 2)
                            <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-md font-bold">منجز</span>
                        @elseif($license->status == 3)
                            <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-md font-bold">ملغي / مجمد</span>
                        @endif
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mt-2">
                        <span class="flex items-center gap-1.5"><x-heroicon-o-calendar class="w-4 h-4 text-gray-400"/> تاريخ التقديم: {{ $license->created_at ? $license->created_at->format('Y-m-d') : '-' }}</span>
                        @if($license->building)
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('buildings.buildings.show', $license->building_id) }}" class="flex items-center gap-1.5 text-blue-600 hover:underline">
                            <x-heroicon-o-building-office class="w-4 h-4"/> مبنى رقم {{ $license->building->building_number ?? $license->building_id }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @can('bhm.license-forms.approve')
                <button class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-check-circle class="w-4 h-4" />
                    إنجاز / اعتماد
                </button>
                @endcan
                @can('bhm.license-forms.edit')
                <a href="#" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl shadow-sm transition-colors text-sm">
                    <x-heroicon-o-pencil-square class="w-4 h-4" />
                    تعديل
                </a>
                @endcan
            </div>
        </div>

        <!-- Alpine Tabs -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            
            <!-- Tabs Header -->
            <div class="flex overflow-x-auto border-b border-gray-100 bg-gray-50/50">
                <button @click="tab = 'basic'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'basic', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'basic'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-4 h-4" />
                    نموذج الترخيص
                </button>
                @if($license->report)
                <button @click="tab = 'report'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'report', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'report'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4" />
                    تقرير الكشف التنظيمي
                </button>
                @endif
                <button @click="tab = 'opinions'" :class="{'text-orange-dark border-b-2 border-orange bg-white font-bold': tab === 'opinions', 'text-gray-500 hover:text-gray-700 font-medium hover:bg-gray-100/50': tab !== 'opinions'}" class="px-6 py-4 text-sm transition-all whitespace-nowrap flex items-center gap-2">
                    <x-heroicon-o-chat-bubble-bottom-center-text class="w-4 h-4" />
                    آراء الجهات المختصة
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                
                <!-- Tab: Basic Data -->
                <div x-show="tab === 'basic'" class="space-y-8" style="display: none;">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <!-- Applicant Info -->
                        @php
                            $hasApplicant = filled($license->full_name) || filled($license->id_card);
                        @endphp
                        <div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2 mb-4">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-gray-800">بيانات مقدم الطلب</h3>
                                    @if($hasApplicant)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            مرتبط
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                                            غير مدخل
                                        </span>
                                    @endif
                                </div>
                                
                                @if($hasApplicant)
                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                                @click="openModal()"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl text-orange bg-orange/10 hover:bg-orange/20 border border-orange/20 transition-all cursor-pointer">
                                            <x-heroicon-s-arrow-path class="w-3.5 h-3.5" />
                                            <span>تغيير المواطن / إعادة الربط</span>
                                        </button>
                                        
                                        <button type="button"
                                                @click="unlinkCitizen()"
                                                :disabled="isUnlinking"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 transition-all cursor-pointer disabled:opacity-50"
                                                title="إزالة ربط المواطن لتصبح بيانات مقدم الطلب فارغة">
                                            <template x-if="!isUnlinking">
                                                <span class="flex items-center gap-1">
                                                    <x-heroicon-s-trash class="w-3.5 h-3.5" />
                                                    <span>إزالة ربط المواطن</span>
                                                </span>
                                            </template>
                                            <template x-if="isUnlinking">
                                                <span class="flex items-center gap-1">
                                                    <svg class="animate-spin w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                    <span>جاري الإزالة...</span>
                                                </span>
                                            </template>
                                        </button>
                                    </div>
                                @else
                                    <button type="button"
                                            @click="openModal()"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl text-white bg-orange hover:bg-orange-dark shadow-sm shadow-orange/20 transition-all cursor-pointer">
                                        <x-heroicon-s-link class="w-3.5 h-3.5" />
                                        <span>ربط ببيانات مواطن</span>
                                    </button>
                                @endif
                            </div>

                            @if(!$hasApplicant)
                                <div class="p-5 rounded-2xl bg-orange/5 border border-dashed border-orange/30 text-center space-y-3">
                                    <div class="w-12 h-12 rounded-2xl bg-orange/10 text-orange flex items-center justify-center mx-auto shadow-inner">
                                        <x-heroicon-s-user-plus class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">لم يتم إدخال بيانات مقدم الطلب بعد</h4>
                                        <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto leading-relaxed">
                                            يمكنك جلب وربط بيانات مقدم الطلب مباشرة من سجل المواطنين (الاسم الرباعي، رقم الهوية، رقم الجوال) بالبحث باسم المواطن أو رقم الهوية.
                                        </p>
                                    </div>
                                    <button type="button"
                                            @click="openModal()"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-orange hover:bg-orange-dark text-white rounded-xl text-xs font-bold shadow-md shadow-orange/20 hover:shadow-orange/30 transition-all cursor-pointer">
                                        <x-heroicon-s-magnifying-glass class="w-4 h-4" />
                                        <span>البحث في سجل المواطنين والربط</span>
                                    </button>
                                </div>
                            @else
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span class="text-sm text-gray-500">الاسم الرباعي:</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ $license->full_name ?: '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span class="text-sm text-gray-500">رقم الهوية:</span>
                                        <span class="text-sm font-semibold text-gray-800 font-mono">{{ $license->id_card ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                        <span class="text-sm text-gray-500">رقم الهاتف/الجوال:</span>
                                        <span class="text-sm font-semibold text-gray-800" dir="ltr">{{ $license->phone ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2">
                                        <span class="text-sm text-gray-500">رقم المبنى/الملف الخاص بالمواطن:</span>
                                        <span class="text-sm font-semibold text-gray-800">{{ $license->building_number ?? $license->building?->building_number ?? '-' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Building Target -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">بيانات العقار المستهدف</h3>
                            @if($license->building)
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الحي:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->zone->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">الشارع:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->street->name ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-sm text-gray-500">رقم القسيمة:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->parcel_number ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-500">نوع المبنى:</span>
                                    <span class="text-sm font-semibold text-gray-800">{{ $license->building->buildingType->name ?? '-' }}</span>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-6 bg-gray-50 rounded-xl text-sm text-gray-500">
                                لم يتم ربط هذا الترخيص بمبنى محدد في النظام.
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                        <h3 class="text-sm font-bold text-navy-dark mb-2">موضوع الترخيص المطلوبة</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $license->subject ?? 'لم يتم تحديد موضوع الترخيص.' }}</p>
                    </div>

                    @if($license->floors->count() > 0)
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">الطوابق المرتبطة بهذه المعاملة</h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-100">
                            <table class="w-full text-right text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="px-4 py-3">اسم الطابق</th>
                                        <th class="px-4 py-3">المساحة</th>
                                        <th class="px-4 py-3">حالة الترخيص للطابق</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($license->floors as $floor)
                                    <tr class="hover:bg-gray-50/50">
                                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $floor->floor_name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $floor->area ?? 0 }} م²</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            <span class="text-xs px-2 py-1 rounded-md font-medium bg-gray-100 text-gray-700">{{ $floor->license_status_label }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>

                <!-- Tab: Report -->
                @if($license->report)
                <div x-show="tab === 'report'" class="space-y-6" style="display: none;">
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <x-heroicon-s-clipboard-document-check class="w-6 h-6 text-orange" />
                            <h3 class="text-lg font-bold text-gray-800">بيانات الكشف التنظيمي (المهندس)</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">الوضع القائم للمبنى</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->existing_status ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">عرض الشارع</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->street_width ?? '-' }} متر</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">الارتدادات التنظيمية</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->bounces ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">التصنيف التنظيمي للمنطقة</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->regulatory_rating ?? '-' }}</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block mb-1">نسبة البناء المسموحة والمقترحة</span>
                                    <div class="text-sm font-medium text-gray-800 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">{{ $license->report->building_percentage ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($license->report->notes)
                        <div class="mt-6">
                            <span class="text-xs text-gray-500 block mb-1">التوصيات / الملاحظات الفنية</span>
                            <div class="text-sm text-gray-700 bg-white p-4 rounded-xl border border-gray-100 shadow-sm leading-relaxed whitespace-pre-wrap">{{ $license->report->notes }}</div>
                        </div>
                        @endif
                    </div>

                </div>
                @endif

                <!-- Tab: Opinions -->
                <div x-show="tab === 'opinions'" class="space-y-6" style="display: none;">
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-scale class="w-4 h-4 text-gray-400" />
                                الرأي القانوني
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->legalOpinionReply->reply ?? $license->legal_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-map class="w-4 h-4 text-gray-400" />
                                رأي المساحة والتنظيم
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->areaOpinionReply->reply ?? $license->area_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-clipboard-document-list class="w-4 h-4 text-gray-400" />
                                رأي التخطيط
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->planOpinionReply->reply ?? $license->plan_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-beaker class="w-4 h-4 text-gray-400" />
                                رأي شبكات المياه
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->waterOpinionReply->reply ?? $license->water_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-arrow-path-rounded-square class="w-4 h-4 text-gray-400" />
                                رأي شبكات الصرف الصحي
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->sewerOpinionReply->reply ?? $license->sewer_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-banknotes class="w-4 h-4 text-gray-400" />
                                رأي الجباية (براءة ذمة)
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->collectionOpinionReply->reply ?? $license->collection_opinion ?: 'لا يوجد' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 hover:border-blue-200 transition-colors md:col-span-2">
                            <h4 class="font-bold text-gray-800 flex items-center gap-2 mb-3 border-b border-gray-200 pb-2">
                                <x-heroicon-s-globe-alt class="w-4 h-4 text-gray-400" />
                                رأي نظم المعلومات الجغرافية (GIS)
                            </h4>
                            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $license->gisOpinionReply->reply ?? $license->gis_opinion ?: 'لا يوجد' }}</p>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    {{-- نافذة البحث والربط ببيانات المواطنين --}}
    <template x-teleport="body">
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-xs overflow-y-auto"
             style="display: none;"
             @keydown.escape.window="closeModal()">
            
            {{-- خلفية معتمة قابلة للنقر للإغلاق --}}
            <div class="fixed inset-0" @click="closeModal()" aria-hidden="true"></div>

            <div @click.stop
                 x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-3xl overflow-hidden relative transition-all my-auto z-10">
            
                {{-- رأس النافذة --}}
                <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange/10 text-orange flex items-center justify-center shrink-0 shadow-inner">
                            <x-heroicon-s-user-plus class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-gray-800">البحث في سجل المواطنين وربط مقدم الطلب</h3>
                            <p class="text-xs text-gray-400">ابحث باسم المواطن أو رقم الهوية لجلب البيانات وربطها بالترخيص رقم {{ $license->id }}</p>
                        </div>
                    </div>
                    <button @click="closeModal()" type="button" class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">
                        <x-heroicon-o-x-mark class="w-5 h-5" />
                    </button>
                </div>

                {{-- محتوى النافذة --}}
                <div class="p-6">
                    {{-- رسائل التنبيه --}}
                    <div x-show="errorMessage" x-transition class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                        <x-heroicon-s-x-circle class="w-4 h-4 shrink-0 text-red-500" />
                        <span x-text="errorMessage"></span>
                    </div>

                    <div x-show="successMessage" x-transition class="mb-4 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                        <x-heroicon-s-check-circle class="w-4 h-4 shrink-0 text-emerald-500" />
                        <span x-text="successMessage"></span>
                    </div>

                    {{-- شريط المواطن المرتبط حالياً إن وجد --}}
                    @if($hasApplicant)
                        <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs text-gray-700 min-w-0">
                                <x-heroicon-s-user-circle class="w-5 h-5 text-gray-400 shrink-0" />
                                <span class="text-gray-500">المرتبط حالياً:</span>
                                <span class="font-bold text-gray-900 truncate">{{ $license->full_name }}</span>
                                <span class="font-mono text-gray-500 text-[11px] bg-white px-2 py-0.5 rounded border border-gray-200 shrink-0" dir="ltr">{{ $license->id_card }}</span>
                            </div>
                            <button type="button"
                                    @click="unlinkCitizen()"
                                    :disabled="isUnlinking"
                                    class="px-2.5 py-1 text-xs font-bold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-red-200 shrink-0 flex items-center gap-1 cursor-pointer">
                                <x-heroicon-s-trash class="w-3 h-3" />
                                <span>إزالة الربط الحالي</span>
                            </button>
                        </div>
                    @endif

                    {{-- حقل البحث --}}
                    <div class="mb-4">
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-gray-400">
                                <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                            </div>
                            <input type="text"
                                   x-ref="searchInput"
                                   x-model="searchQuery"
                                   @input.debounce.300ms="performSearch()"
                                   placeholder="ابحث باسم المواطن (مثال: ابراهيم عابد) أو رقم الهوية (مثال: 700158470)..."
                                   class="w-full pr-11 pl-10 py-3 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-orange focus:ring-2 focus:ring-orange/20 transition-all">
                            
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <template x-if="isSearching">
                                    <svg class="animate-spin w-4 h-4 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                </template>
                                <template x-if="!isSearching && searchQuery">
                                    <button @click="searchQuery = ''; performSearch()" type="button" class="text-gray-400 hover:text-gray-600 p-1">
                                        <x-heroicon-s-x-mark class="w-4 h-4" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- قائمة نتائج البحث --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2 px-1">
                            <span class="text-xs font-bold text-gray-500" x-show="searchQuery.trim().length >= 2">نتائج البحث:</span>
                            <span class="text-xs font-bold text-gray-400" x-show="searchQuery.trim().length < 2">يرجى البحث لعرض النتائج</span>
                            <span class="text-[11px] text-gray-400" x-show="searchQuery.trim().length >= 2" x-text="searchResults.length + ' نتيجة'"></span>
                        </div>

                        <div class="max-h-56 overflow-y-auto divide-y divide-gray-100 rounded-2xl border border-gray-200 bg-white">
                            {{-- الحالة 1: قبل إدخال نص البحث --}}
                            <template x-if="searchQuery.trim().length < 2 && !isSearching">
                                <div class="p-8 text-center text-gray-400 text-xs">
                                    <x-heroicon-o-magnifying-glass class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                    <div class="font-bold text-gray-700 text-sm mb-1">ابحث باسم المواطن أو رقم الهوية</div>
                                    <p class="text-gray-400">يرجى كتابة حرفين على الأقل في حقل البحث أعلاه لعرض النتائج المطابقة فقط</p>
                                </div>
                            </template>

                            {{-- الحالة 2: البحث جاري --}}
                            <template x-if="isSearching">
                                <div class="p-8 text-center text-gray-400 text-xs">
                                    <svg class="animate-spin w-7 h-7 mx-auto mb-2 text-orange" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    <div class="font-bold text-gray-600">جاري البحث في سجل المواطنين...</div>
                                </div>
                            </template>

                            {{-- الحالة 3: تم البحث ولم يتم العثور على نتائج --}}
                            <template x-if="searchQuery.trim().length >= 2 && !isSearching && searchResults.length === 0">
                                <div class="p-8 text-center text-gray-400 text-xs">
                                    <x-heroicon-o-user class="w-8 h-8 mx-auto mb-2 text-gray-300" />
                                    <div class="font-bold text-gray-700 text-sm mb-1">لا توجد نتائج مطابقة</div>
                                    <p class="text-gray-400">لم يتم العثور على أي مواطن مطابق لكلمات البحث. يرجى تجربة كلمات أو أرقام أخرى.</p>
                                </div>
                            </template>

                            {{-- الحالة 4: عرض النتائج المطابقة --}}
                            <template x-if="searchQuery.trim().length >= 2 && !isSearching && searchResults.length > 0">
                                <div>
                                    <template x-for="citizen in searchResults" :key="citizen.id">
                                        <div @click="selectCitizen(citizen)"
                                             :class="selectedCitizen && selectedCitizen.id === citizen.id ? 'bg-orange/10 border-orange/40 text-orange-dark font-bold' : 'hover:bg-gray-50/80 cursor-pointer'"
                                             class="p-3.5 flex items-center justify-between transition-colors cursor-pointer group">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div :class="selectedCitizen && selectedCitizen.id === citizen.id ? 'bg-orange text-white' : 'bg-orange/10 text-orange group-hover:scale-105'" 
                                                     class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-transform shadow-2xs">
                                                    <x-heroicon-s-user class="w-4 h-4" />
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs font-bold text-gray-800 truncate" x-text="citizen.name"></div>
                                                    <div class="text-[11px] text-gray-400 flex items-center gap-2 mt-0.5">
                                                        <span class="font-mono font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded text-[10px]" dir="ltr">
                                                            <span>هوية: </span><span x-text="citizen.id_number"></span>
                                                        </span>
                                                        <template x-if="citizen.mobile">
                                                            <span class="flex items-center gap-1 font-mono text-gray-600 bg-gray-50 px-2 py-0.5 rounded text-[10px]" dir="ltr">
                                                                <x-heroicon-s-phone class="w-3 h-3 text-gray-400" />
                                                                <span x-text="citizen.mobile"></span>
                                                            </span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div :class="selectedCitizen && selectedCitizen.id === citizen.id ? 'border-orange bg-orange text-white' : 'border-gray-300 text-transparent'"
                                                     class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors">
                                                    <x-heroicon-s-check class="w-3.5 h-3.5" />
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- جدول بيانات المواطن المختار للربط وبجانبه زر الربط --}}
                    <div x-show="selectedCitizen" x-transition class="mt-4 border border-emerald-200 rounded-2xl overflow-hidden shadow-sm bg-white">
                        <div class="bg-emerald-50/70 px-4 py-2.5 border-b border-emerald-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                <x-heroicon-s-check-badge class="w-4 h-4 text-emerald-600" />
                                بيانات المواطن المختار للربط
                            </span>
                            <span class="text-[11px] text-emerald-600 font-medium">تأكيد الربط</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-right text-xs">
                                <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3">الاسم الرباعي</th>
                                        <th class="px-4 py-3">رقم الهوية</th>
                                        <th class="px-4 py-3">رقم الجوال</th>
                                        <th class="px-4 py-3 text-center">إجراء الربط</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    <tr>
                                        <td class="px-4 py-3.5 font-bold text-gray-900" x-text="selectedCitizen ? selectedCitizen.name : ''"></td>
                                        <td class="px-4 py-3.5 font-mono font-extrabold text-orange-dark text-xs whitespace-nowrap" dir="ltr" x-text="selectedCitizen ? selectedCitizen.id_number : ''"></td>
                                        <td class="px-4 py-3.5 font-mono text-gray-700 whitespace-nowrap" dir="ltr" x-text="selectedCitizen && selectedCitizen.mobile ? selectedCitizen.mobile : '-'"></td>
                                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                            <button type="button"
                                                    @click="linkSelectedCitizen()"
                                                    :disabled="isLinking"
                                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all disabled:opacity-50 cursor-pointer">
                                                <template x-if="!isLinking">
                                                    <span class="flex items-center gap-1">
                                                        <x-heroicon-s-link class="w-3.5 h-3.5" />
                                                        ربط بهذا المواطن
                                                    </span>
                                                </template>
                                                <template x-if="isLinking">
                                                    <span class="flex items-center gap-1">
                                                        <svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                                        جاري الربط...
                                                    </span>
                                                </template>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-end">
                        <button @click="closeModal()" type="button" class="px-5 py-2 text-gray-500 hover:bg-gray-100 rounded-xl font-bold text-xs transition-colors">
                            إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    </div>
</x-buildings-layout>
