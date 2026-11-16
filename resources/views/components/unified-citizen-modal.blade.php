<div id="unified_citizen_modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-xs transition-opacity duration-200">
    <div class="w-full max-w-2xl mx-auto rounded-3xl bg-white shadow-2xl overflow-hidden flex flex-col max-h-[92vh] animate-in fade-in zoom-in-95 duration-150">
        {{-- Header --}}
        <div class="flex-none flex items-center justify-between gap-4 border-b border-slate-100 bg-slate-50/80 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 border border-teal-200 text-teal-700 flex items-center justify-center shrink-0">
                    <x-heroicon-s-user-plus class="w-5 h-5" />
                </div>
                <div>
                    <h3 id="ucm_title" class="text-base font-black text-slate-900">نموذج تسجيل / ربط مواطن موحد</h3>
                    <p class="text-xs text-slate-500 mt-0.5">يتم حفظ وتحديث البيانات في السجل العام الموحد لكافة أقسام البلدية</p>
                </div>
            </div>
            <button type="button" onclick="window.closeCitizenModal()" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition cursor-pointer" aria-label="إغلاق">
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 px-6 py-5 space-y-4 overflow-y-auto">
            {{-- Alert --}}
            <div id="ucm_alert" class="hidden rounded-2xl border border-rose-200 bg-rose-50 p-3.5 text-xs font-bold text-rose-700 flex items-start gap-2">
                <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" />
                <span id="ucm_alert_msg"></span>
            </div>

            {{-- Quick Found Notice --}}
            <div id="ucm_notice" class="hidden rounded-2xl border border-teal-200 bg-teal-50/70 p-3 text-xs font-bold text-teal-800 flex items-center gap-2">
                <x-heroicon-s-information-circle class="w-4 h-4 text-teal-600 shrink-0" />
                <span id="ucm_notice_msg">تم استرجاع بيانات المواطن المسجل مسبقاً بنجاح.</span>
            </div>

            <form id="unified_citizen_form" onsubmit="event.preventDefault(); window.submitUnifiedCitizenModal();" class="space-y-4">
                <input type="hidden" id="ucm_building_id" name="building_id" value="">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- ID Card --}}
                    <div>
                        <label for="ucm_id_no" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم الهوية الوطنية <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="ucm_id_no" name="id_no" maxlength="9" minlength="7"
                                   placeholder="مثال: 901509612"
                                   class="w-full text-xs font-mono font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9); window.checkCitizenOnInput(this.value);"
                                   required />
                            <div id="ucm_search_spinner" class="absolute left-3 top-3 hidden">
                                <span class="w-3.5 h-3.5 border-2 border-teal-600 border-t-transparent rounded-full animate-spin inline-block"></span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">رقم الهوية مكون من 9 أرقام، يتم التحقق الفوري من وجوده بالسجل.</p>
                    </div>

                    {{-- Full Name --}}
                    <div>
                        <label for="ucm_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الاسم الرباعي الكامل <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input type="text" id="ucm_name" name="name"
                               placeholder="الاسم الأول، اسم الأب، الجد، العائلة"
                               class="w-full text-xs font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition"
                               required />
                    </div>

                    {{-- Mobile --}}
                    <div>
                        <label for="ucm_mobile" class="block text-xs font-bold text-slate-700 mb-1.5">
                            رقم الهاتف المحمول <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <input type="text" id="ucm_mobile" name="mobile" maxlength="10"
                               placeholder="مثال: 0599123456 أو 0569123456"
                               class="w-full text-xs font-mono font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);"
                               required />
                    </div>

                    {{-- Telephone --}}
                    <div>
                        <label for="ucm_telephone" class="block text-xs font-bold text-slate-700 mb-1.5">
                            الهاتف الثابت / هاتف بديل (اختياري)
                        </label>
                        <input type="text" id="ucm_telephone" name="telephone" maxlength="15"
                               placeholder="مثال: 082820000"
                               class="w-full text-xs font-mono font-bold px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="ucm_email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            البريد الإلكتروني (اختياري)
                        </label>
                        <input type="email" id="ucm_email" name="email"
                               placeholder="name@example.com"
                               class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition" />
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="ucm_address" class="block text-xs font-bold text-slate-700 mb-1.5">
                            العنوان السكني التفصيلي
                        </label>
                        <input type="text" id="ucm_address" name="address"
                               placeholder="الشارع، الحي، المعلم القريب"
                               class="w-full text-xs font-medium px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition" />
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label for="ucm_notes" class="block text-xs font-bold text-slate-700 mb-1.5">
                            ملاحظات إضافية حول المواطن
                        </label>
                        <textarea id="ucm_notes" name="notes" rows="2"
                                  placeholder="أي ملاحظات تنظيمية أو إدارية..."
                                  class="w-full text-xs font-medium px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 outline-none transition resize-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" onclick="window.closeCitizenModal()" class="text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 px-5 py-2.5 rounded-xl transition cursor-pointer">
                        إلغاء
                    </button>
                    <button type="submit" id="ucm_submit_btn" class="inline-flex items-center gap-2 text-xs font-extrabold text-white bg-teal-700 hover:bg-teal-800 px-6 py-2.5 rounded-xl transition shadow-sm shadow-teal-700/20 cursor-pointer">
                        <x-heroicon-s-check class="w-4 h-4" />
                        <span id="ucm_submit_text">حفظ في سجل المواطنين واعتماد</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        let callbackFn = null;
        let searchDebounce = null;

        window.openCitizenModal = function(options = {}) {
            const modal = document.getElementById('unified_citizen_modal');
            if (!modal) return;

            // Reset form
            document.getElementById('unified_citizen_form').reset();
            document.getElementById('ucm_alert').classList.add('hidden');
            document.getElementById('ucm_notice').classList.add('hidden');
            document.getElementById('ucm_building_id').value = options.buildingId || '';

            callbackFn = options.onSuccess || null;

            if (options.title) {
                document.getElementById('ucm_title').textContent = options.title;
            } else {
                document.getElementById('ucm_title').textContent = 'نموذج تسجيل / ربط مواطن موحد';
            }

            if (options.idNo) {
                document.getElementById('ucm_id_no').value = options.idNo;
                window.checkCitizenOnInput(options.idNo);
            }
            if (options.name) document.getElementById('ucm_name').value = options.name;
            if (options.mobile) document.getElementById('ucm_mobile').value = options.mobile;

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => document.getElementById('ucm_id_no').focus(), 100);
        };

        window.closeCitizenModal = function() {
            const modal = document.getElementById('unified_citizen_modal');
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('ucm_alert').classList.add('hidden');
            document.getElementById('ucm_notice').classList.add('hidden');
        };

        window.checkCitizenOnInput = function(idVal) {
            clearTimeout(searchDebounce);
            const spinner = document.getElementById('ucm_search_spinner');
            const notice = document.getElementById('ucm_notice');
            const noticeMsg = document.getElementById('ucm_notice_msg');

            idVal = (idVal || '').trim();
            if (idVal.length < 8) {
                notice.classList.add('hidden');
                if (spinner) spinner.classList.add('hidden');
                return;
            }

            if (spinner) spinner.classList.remove('hidden');

            searchDebounce = setTimeout(async () => {
                try {
                    const searchUrl = @json(route('citizens.search'));
                    const res = await fetch(`${searchUrl}?citizen_id=${encodeURIComponent(idVal)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();

                    if (spinner) spinner.classList.add('hidden');

                    if (data && data.found && (data.customer || data.citizen)) {
                        const c = data.customer || data.citizen;
                        if (!document.getElementById('ucm_name').value) {
                            document.getElementById('ucm_name').value = c.name || '';
                        }
                        if (!document.getElementById('ucm_mobile').value) {
                            document.getElementById('ucm_mobile').value = c.mobile || c.phone || '';
                        }
                        if (!document.getElementById('ucm_telephone').value && c.telephone) {
                            document.getElementById('ucm_telephone').value = c.telephone;
                        }
                        if (!document.getElementById('ucm_email').value && c.email) {
                            document.getElementById('ucm_email').value = c.email;
                        }
                        if (!document.getElementById('ucm_address').value && c.address) {
                            document.getElementById('ucm_address').value = c.address;
                        }

                        noticeMsg.textContent = `المواطن مسجل مسبقاً باسم: ${c.name}. يمكنك تحديث البيانات أو اعتمادها مباشرة.`;
                        notice.classList.remove('hidden');
                    } else {
                        notice.classList.add('hidden');
                    }
                } catch (e) {
                    if (spinner) spinner.classList.add('hidden');
                }
            }, 300);
        };

        window.submitUnifiedCitizenModal = async function() {
            const alertBox = document.getElementById('ucm_alert');
            const alertMsg = document.getElementById('ucm_alert_msg');
            const submitBtn = document.getElementById('ucm_submit_btn');
            const submitText = document.getElementById('ucm_submit_text');

            alertBox.classList.add('hidden');

            const idNo = document.getElementById('ucm_id_no').value.trim();
            const name = document.getElementById('ucm_name').value.trim();
            const mobile = document.getElementById('ucm_mobile').value.trim();
            const telephone = document.getElementById('ucm_telephone').value.trim();
            const email = document.getElementById('ucm_email').value.trim();
            const address = document.getElementById('ucm_address').value.trim();
            const notes = document.getElementById('ucm_notes').value.trim();
            const buildingId = document.getElementById('ucm_building_id').value.trim();

            if (!idNo || idNo.length < 7) {
                alertMsg.textContent = 'يرجى إدخال رقم هوية صحيح مكون من 7 إلى 9 أرقام.';
                alertBox.classList.remove('hidden');
                return;
            }

            if (!name) {
                alertMsg.textContent = 'يرجى إدخال اسم المواطن كاملاً.';
                alertBox.classList.remove('hidden');
                return;
            }

            if (!mobile) {
                alertMsg.textContent = 'يرجى إدخال رقم هاتف محمول للتواصل.';
                alertBox.classList.remove('hidden');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            submitBtn.disabled = true;
            submitText.textContent = 'جاري الحفظ...';

            try {
                const storeUrl = @json(route('citizens.store'));
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        id_no: idNo,
                        citizen_id: idNo,
                        name: name,
                        mobile: mobile,
                        phone: mobile,
                        telephone: telephone,
                        email: email,
                        address: address,
                        notes: notes,
                        building_id: buildingId || null,
                        mokalaf: 1
                    })
                });

                const data = await response.json();

                if (!response.ok || data.status === 'error') {
                    throw new Error(data.message || 'حدث خطأ أثناء حفظ بيانات المواطن.');
                }

                const savedCitizen = data.customer || data.citizen;

                // Dispatch global event
                window.dispatchEvent(new CustomEvent('citizen-created', { detail: savedCitizen }));
                window.dispatchEvent(new CustomEvent('citizen-saved', { detail: savedCitizen }));

                // Invoke callback if provided
                if (typeof callbackFn === 'function') {
                    callbackFn(savedCitizen);
                }

                window.closeCitizenModal();

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: 'تم حفظ واعتماد بيانات المواطن في السجل الموحد.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            } catch (err) {
                alertMsg.textContent = err.message || 'تعذر الاتصال بالخادم، يرجى المحاولة لاحقاً.';
                alertBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitText.textContent = 'حفظ في سجل المواطنين واعتماد';
            }
        };

        // Close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('unified_citizen_modal');
                if (modal && !modal.classList.contains('hidden')) {
                    window.closeCitizenModal();
                }
            }
        });
    })();
</script>
