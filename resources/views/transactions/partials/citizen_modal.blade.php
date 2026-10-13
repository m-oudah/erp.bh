<div id="citizen_modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/70">
    <div class="w-full max-w-3xl mx-auto rounded-3xl bg-white shadow-2xl overflow-hidden max-h-[calc(100vh-4rem)] flex flex-col">
        <div class="flex-none flex items-center justify-between gap-4 border-b border-slate-200 bg-slate-50 px-6 py-4">
            <div>
                <h2 id="modal_title" class="text-lg font-black text-slate-900">بيانات المواطن والعقار</h2>
                <p id="modal_message" class="text-sm text-slate-600 mt-1">تأكد من بيانات المواطن واختر العقار المستهدف أو أضف عقاراً جديداً.</p>
            </div>
            <button type="button" class="modal-close-btn inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition" aria-label="إغلاق النافذة">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex-1 px-6 py-6 space-y-6 overflow-y-auto scrollbar-thin scrollbar-track-slate-200 scrollbar-thumb-[#14723a]/50">
            <div id="modal_alert" class="hidden rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">رقم الهوية الوطنية</label>
                    <input id="modal_citizen_id" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white text-slate-800" placeholder="مثال: 105234567" maxlength="9" minlength="9" pattern="[0-9]{9}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 9)" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">اسم المواطن</label>
                    <input id="modal_citizen_name" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white text-slate-800" placeholder="اكتب اسم المواطن" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">رقم الجوال</label>
                    <input id="modal_citizen_phone" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white text-slate-800" placeholder="مثال: 0591234567" maxlength="10" minlength="10" pattern="^(059|056)[0-9]{7}$" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">الحالة التنظيمية للمواطن</label>
                    <select id="modal_citizen_status" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white outline-none focus:ring-2 focus:ring-[#14723a]/20 focus:border-[#14723a] transition">
                        <option value="owner">مالك</option>
                        <option value="beneficiary">منتفع</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-xs font-black uppercase tracking-[0.3em] text-slate-500">العقارات المرتبطة</div>
                        <button type="button" id="toggle_new_property_btn" class="text-xs font-bold text-[#14723a] hover:underline flex items-center gap-1">
                            <i data-lucide="plus" class="w-3 h-3"></i> إضافة عقار جديد
                        </button>
                    </div>
                    <div id="modal_property_summary" class="rounded-3xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">سيتم عرض العقارات هنا بعد البحث أو الإضافة.</div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-black text-slate-900 mb-3">اختيار العقار المستهدف للمعاملة</h3>
                <div id="modal_property_list" class="space-y-3"></div>
                <p id="modal_no_properties" class="text-sm text-slate-500 hidden">لا توجد عقارات مسجلة حالياً، أضف عقاراً جديداً أدناه.</p>
            </div>

            <div id="new_property_form_container" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 hidden">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">إضافة عقار جديد</h3>
                        <p class="text-xs text-slate-500">أضف عقاراً واحداً على الأقل لتوثيق المعاملة.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">رقم القطعة</label>
                        <input id="modal_prop_piece" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white" placeholder="مثال: 42" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">رقم القسيمة</label>
                        <input id="modal_prop_voucher" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white" placeholder="مثال: 145" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">رقم ملف التنظيم</label>
                        <input id="modal_prop_reg_file" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white" placeholder="إن وجد" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">رقم اشتراك المياه</label>
                        <input id="modal_prop_sub_number" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white" placeholder="إن وجد" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2">العنوان التفصيلي</label>
                        <input id="modal_prop_address" type="text" class="w-full text-sm px-4 py-3 rounded-2xl border border-slate-300 bg-white" placeholder="أدخل الموقع التفصيلي للعقار" />
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <button id="modal_add_property_button" type="button" class="inline-flex items-center justify-center rounded-3xl bg-[#14723a] px-5 py-3 text-sm font-black text-white hover:bg-[#115e30] transition">أضف العقار</button>
                    <span id="modal_add_property_hint" class="text-sm text-slate-500">أضف كل عقار تريد ربطه بالمواطن.</span>
                </div>
            </div>
            <div class="mt-8 flex items-center justify-between gap-3 pt-6 border-t border-slate-200">
                <button type="button" class="modal-close-btn inline-flex items-center justify-center rounded-3xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-100 transition shadow-sm">إلغاء</button>
                <button id="modal_save_btn" type="button" class="inline-flex items-center justify-center rounded-3xl bg-[#14723a] px-6 py-3 text-sm font-black text-white hover:bg-[#115e30] transition shadow-md shadow-emerald-700/20">حفظ المواطن واختيار العقار</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const citizenIdInput = document.getElementById('citizen_id_input');
    const searchBtn = document.getElementById('search_btn');
    const citizenNameInput = document.getElementById('citizen_name');
    const citizenPhoneInput = document.getElementById('citizen_phone');
    const citizenStatusSelect = document.getElementById('citizen_status');
    const hiddenCitizenId = document.getElementById('hidden_citizen_id');
    const propertySelect = document.getElementById('property_select');
    const pieceNumberInput = document.getElementById('piece_number');
    const voucherNumberInput = document.getElementById('voucher_number');
    const regulationFileNumberInput = document.getElementById('regulation_file_number');
    const subscriptionNumberInput = document.getElementById('subscription_number');
    const detailedAddressInput = document.getElementById('detailed_address');
    const selectedPropertySwitch = document.getElementById('selected_property_switch');
    const selectedPropertyStatus = document.getElementById('selected_property_status');
    const selectedPiece = document.getElementById('selected_piece');
    const selectedVoucher = document.getElementById('selected_voucher');
    const selectedRegFile = document.getElementById('selected_reg_file');
    const selectedAddress = document.getElementById('selected_address');
    const selectedPropertyDisplayContainer = document.getElementById('selected-property-display-container');

    const citizenModal = document.getElementById('citizen_modal');
    const modalTitle = document.getElementById('modal_title');
    const modalMessage = document.getElementById('modal_message');
    const modalAlert = document.getElementById('modal_alert');
    const modalCitizenIdInput = document.getElementById('modal_citizen_id');
    const modalCitizenNameInput = document.getElementById('modal_citizen_name');
    const modalCitizenPhoneInput = document.getElementById('modal_citizen_phone');
    const modalCitizenStatusSelect = document.getElementById('modal_citizen_status');
    const modalPropertySummary = document.getElementById('modal_property_summary');
    const modalPropertyList = document.getElementById('modal_property_list');
    const modalNoProperties = document.getElementById('modal_no_properties');
    const modalAddPropertyButton = document.getElementById('modal_add_property_button');
    const modalPropPiece = document.getElementById('modal_prop_piece');
    const modalPropVoucher = document.getElementById('modal_prop_voucher');
    const modalPropRegFile = document.getElementById('modal_prop_reg_file');
    const modalPropSubNumber = document.getElementById('modal_prop_sub_number');
    const modalPropAddress = document.getElementById('modal_prop_address');
    const modalSaveBtn = document.getElementById('modal_save_btn');
    const modalCloseButtons = document.querySelectorAll('.modal-close-btn');
    const toggleNewPropertyBtn = document.getElementById('toggle_new_property_btn');
    const newPropertyFormContainer = document.getElementById('new_property_form_container');

    let modalProperties = [];
    let selectedModalPropertyIndex = null;
    let modalIsNewCitizen = false;
    let modalHasCreatedProperties = false;

    function openModal() {
        citizenModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        citizenModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        modalAlert.classList.add('hidden');
    }

    function showModalAlert(message) {
        modalAlert.textContent = message;
        modalAlert.classList.remove('hidden');
    }

    function clearModalAlert() {
        modalAlert.classList.add('hidden');
        modalAlert.textContent = '';
    }

    function handlePrepareNewCitizenForm() {
        modalCitizenIdInput.value = '';
        modalCitizenNameInput.value = (typeof window.defaultCitizenName !== 'undefined' ? window.defaultCitizenName : '');
        modalCitizenPhoneInput.value = (typeof window.defaultCitizenPhone !== 'undefined' ? window.defaultCitizenPhone : '');
        modalCitizenStatusSelect.value = 'owner';
        modalProperties = [];
        selectedModalPropertyIndex = null;
        modalIsNewCitizen = true;
        modalHasCreatedProperties = false;
        modalTitle.textContent = 'تسجيل مواطن جديد';
        modalMessage.textContent = 'أضف بيانات المواطن وعقاره في النافذة المنبثقة.';
        modalAlert.classList.add('hidden');
        renderModalProperties();
        openModal();
    }

    function updateSelectedPropertyDisplay(property) {
        if (!property) {
            if (selectedPropertyDisplayContainer) {
                selectedPropertyDisplayContainer.classList.add('hidden');
            }
            return;
        }

        if (selectedPropertyDisplayContainer) {
            selectedPropertyDisplayContainer.classList.remove('hidden');
        }
        if (selectedPropertyStatus) {
            selectedPropertyStatus.textContent = property.relation_status === 'owner' ? 'مالك' : property.relation_status === 'beneficiary' ? 'منتفع' : 'أخرى';
        }
        if (selectedPiece) selectedPiece.textContent = property.piece_number || '-';
        if (selectedVoucher) selectedVoucher.textContent = property.voucher_number || '-';
        if (selectedRegFile) selectedRegFile.textContent = property.regulation_file_number || '-';
        if (selectedAddress) selectedAddress.textContent = property.detailed_address || '-';
    }

    function populateSelectedPropertySwitch(properties, selectedIndex = 0) {
        if (!selectedPropertySwitch) return;
        selectedPropertySwitch.innerHTML = '';

        if (!properties.length) {
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'لا توجد عقارات مختارة';
            selectedPropertySwitch.appendChild(emptyOption);
            return;
        }

        properties.forEach((prop, index) => {
            const option = document.createElement('option');
            option.value = String(index);
            option.textContent = `قطعة ${prop.piece_number} | قسيمة ${prop.voucher_number}`;
            selectedPropertySwitch.appendChild(option);
        });

        if (selectedIndex !== null && selectedIndex >= 0 && selectedIndex < selectedPropertySwitch.options.length) {
            selectedPropertySwitch.selectedIndex = selectedIndex;
        }
    }

    function setMainFormCitizen(citizenId, citizenName, properties, selectedIndex = 0) {
        hiddenCitizenId.value = citizenId;
        citizenNameInput.value = citizenName;

        if (propertySelect) {
            propertySelect.disabled = false;
            propertySelect.innerHTML = '';

            properties.forEach((prop, index) => {
                const option = document.createElement('option');
                option.value = prop.id;
                option.textContent = `قطعة ${prop.piece_number} | قسيمة ${prop.voucher_number} | ${prop.detailed_address}`;
                option.dataset.status = prop.relation_status || prop.citizen_status || 'owner';
                propertySelect.appendChild(option);
            });

            if (selectedIndex !== null && selectedIndex >= 0 && propertySelect.options[selectedIndex]) {
                propertySelect.selectedIndex = selectedIndex;
            }
        }

        populateSelectedPropertySwitch(properties, selectedIndex);
        updateSelectedPropertyDisplay(properties[selectedIndex] || null);
        if (propertySelect) {
            propertySelect.dispatchEvent(new Event('change'));
        }
    }

    if (selectedPropertySwitch) {
        selectedPropertySwitch.addEventListener('change', () => {
            const selectedIndex = Number(selectedPropertySwitch.value);
            if (propertySelect && !Number.isNaN(selectedIndex) && propertySelect.options[selectedIndex]) {
                propertySelect.selectedIndex = selectedIndex;
                propertySelect.dispatchEvent(new Event('change'));
                updateSelectedPropertyDisplay({
                    piece_number: propertySelect.options[selectedIndex].textContent?.split('|')[0]?.trim().replace('قطعة ', '') || '-',
                    voucher_number: propertySelect.options[selectedIndex].textContent?.split('|')[1]?.trim().replace('قسيمة ', '') || '-',
                    detailed_address: propertySelect.options[selectedIndex].textContent?.split('|')[2]?.trim() || '-',
                    relation_status: propertySelect.options[selectedIndex].dataset.status || 'owner'
                });
            }
        });
    }

    function renderModalProperties() {
        modalPropertyList.innerHTML = '';

        if (!modalProperties.length) {
            modalNoProperties.classList.remove('hidden');
            modalPropertySummary.textContent = 'لا يوجد عقارات حتى الآن. أضف عقاراً جديداً أدناه.';
            return;
        }

        modalNoProperties.classList.add('hidden');
        modalPropertySummary.textContent = `عدد العقارات المدرجة: ${modalProperties.length}`;

        modalProperties.forEach((prop, index) => {
            const row = document.createElement('div');
            row.className = 'flex flex-col gap-2 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between';

            const left = document.createElement('div');
            left.className = 'flex items-start gap-3';

            const radioWrapper = document.createElement('label');
            radioWrapper.className = 'inline-flex items-center gap-2 text-slate-700';
            radioWrapper.innerHTML = `<input type="radio" name="modal_property_select" class="w-4 h-4 text-[#14723a] border-slate-300" ${selectedModalPropertyIndex === index ? 'checked' : ''}>`;
            radioWrapper.querySelector('input').addEventListener('change', () => {
                selectedModalPropertyIndex = index;
            });

            const info = document.createElement('div');
            info.className = 'text-sm';
            info.innerHTML = `<p class="font-black text-slate-900">قطعة ${prop.piece_number} — قسيمة ${prop.voucher_number}</p>
                <p class="text-slate-500">العنوان: ${prop.detailed_address || 'غير محدد'}</p>
                <p class="text-slate-500">الحالة: ${prop.relation_status === 'owner' ? 'مالك' : prop.relation_status === 'beneficiary' ? 'منتفع' : 'أخرى'}</p>
                <p class="text-slate-500">ملف التنظيم: ${prop.regulation_file_number || 'غير متوفر'} — اشتراك المياه: ${prop.subscription_number || 'غير متوفر'}</p>`;

            left.appendChild(radioWrapper);
            left.appendChild(info);
            row.appendChild(left);

            if (prop.isNewProperty) {
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'inline-flex items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-rose-700 text-xs font-bold hover:bg-rose-100 transition';
                removeBtn.textContent = 'حذف';
                removeBtn.addEventListener('click', () => {
                    modalProperties = modalProperties.filter((_, idx) => idx !== index);
                    if (selectedModalPropertyIndex === index) {
                        selectedModalPropertyIndex = modalProperties.length ? 0 : null;
                    } else if (selectedModalPropertyIndex !== null && selectedModalPropertyIndex > index) {
                        selectedModalPropertyIndex -= 1;
                    }
                    renderModalProperties();
                });
                row.appendChild(removeBtn);
            }

            modalPropertyList.appendChild(row);
        });

        if (selectedModalPropertyIndex === null) {
            selectedModalPropertyIndex = 0;
        }
    }

    const citizenSearchUrl = @json(route('citizens.search'));
    const citizenStoreUrl = @json(route('citizens.store'));

    async function fetchCitizenData(citizenId) {
        try {
            const response = await fetch(`${citizenSearchUrl}?citizen_id=${encodeURIComponent(citizenId)}`, {
                headers: { Accept: 'application/json' }
            });
            return await response.json();
        } catch (error) {
            console.error(error);
            return { found: false, message: 'حدث خطأ أثناء الاتصال بالخادم.' };
        }
    }

    async function storeCitizenData(payload) {
        try {
            const response = await fetch(citizenStoreUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'فشل حفظ بيانات المواطن');
            }
            return data;
        } catch (error) {
            console.error(error);
            throw error;
        }
    }

    function buildPropertyPayload(prop) {
        return {
            piece_number: prop.piece_number,
            voucher_number: prop.voucher_number,
            detailed_address: prop.detailed_address,
            regulation_file_number: prop.regulation_file_number,
            subscription_number: prop.subscription_number,
            relation_status: prop.relation_status || 'owner'
        };
    }

    function collectPropertyData() {
        if (selectedModalPropertyIndex === null || !modalProperties[selectedModalPropertyIndex]) {
            return null;
        }
        return modalProperties[selectedModalPropertyIndex];
    }

    function updateFormFromModal(citizenId, citizenName, properties) {
        const selectedIndex = selectedModalPropertyIndex !== null ? selectedModalPropertyIndex : 0;
        setMainFormCitizen(citizenId, citizenName, properties, selectedIndex);
    }

    async function handleModalSave() {
        clearModalAlert();

        const citizenId = modalCitizenIdInput.value.trim();
        const citizenName = modalCitizenNameInput.value.trim();
        const citizenPhone = modalCitizenPhoneInput.value.trim();

        const idRegex = /^[0-9]{9}$/;
        if (!idRegex.test(citizenId)) {
            showModalAlert('يجب أن يكون رقم الهوية مكوناً من 9 خانات وأرقام فقط.');
            return;
        }
        if (!citizenName) {
            showModalAlert('الرجاء إدخال اسم المواطن.');
            return;
        }
        const phoneRegex = /^(059|056)[0-9]{7}$/;
        if (!phoneRegex.test(citizenPhone)) {
            showModalAlert('يجب أن يكون رقم الجوال مكوناً من 10 خانات ويبدأ بـ 059 أو 056.');
            return;
        }
        if (!modalProperties.length) {
            showModalAlert('يجب إضافة عقار واحد على الأقل.');
            return;
        }
        if (selectedModalPropertyIndex === null || !modalProperties[selectedModalPropertyIndex]) {
            showModalAlert('اختر العقار المستهدف من القائمة.');
            return;
        }

        const foundProperty = collectPropertyData();
        if (!foundProperty) {
            showModalAlert('هناك خطأ في اختيار العقار.');
            return;
        }

        const selectedProperty = collectPropertyData();
        if (!selectedProperty) {
            showModalAlert('هناك خطأ في اختيار العقار.');
            return;
        }

        const fillFormFields = (citizenIdValue, citizenNameValue, citizenPhoneValue, property) => {
            hiddenCitizenId.value = citizenIdValue;
            citizenNameInput.value = citizenNameValue;
            if (citizenPhoneInput) citizenPhoneInput.value = citizenPhoneValue;
            if (citizenStatusSelect) citizenStatusSelect.value = property.relation_status || property.citizen_status || 'owner';
            if (pieceNumberInput) pieceNumberInput.value = property.piece_number || '';
            if (voucherNumberInput) voucherNumberInput.value = property.voucher_number || '';
            if (regulationFileNumberInput) regulationFileNumberInput.value = property.regulation_file_number || '';
            if (subscriptionNumberInput) subscriptionNumberInput.value = property.subscription_number || '';
            if (detailedAddressInput) detailedAddressInput.value = property.detailed_address || '';
        };

        if (modalIsNewCitizen || modalHasCreatedProperties) {
            try {
                modalSaveBtn.disabled = true;
                modalSaveBtn.textContent = 'جاري الحفظ...';
                const payload = {
                    citizen_id: citizenId,
                    name: citizenName,
                    phone: citizenPhone,
                    properties: modalProperties.map(buildPropertyPayload)
                };
                const result = await storeCitizenData(payload);
                const citizen = result.citizen;
                modalProperties = citizen.properties.map(p => ({
                    id: p.id,
                    piece_number: p.piece_number,
                    voucher_number: p.voucher_number,
                    detailed_address: p.detailed_address,
                    regulation_file_number: p.regulation_file_number || '',
                    subscription_number: p.subscription_number || '',
                    relation_status: p.relation_status || p.citizen_status || 'owner',
                    isNewProperty: false
                }));
                selectedModalPropertyIndex = 0;
                modalIsNewCitizen = false;
                modalHasCreatedProperties = false;
                updateFormFromModal(citizen.citizen_id, citizen.name, modalProperties);
                fillFormFields(citizen.citizen_id, citizen.name, citizen.phone || '', modalProperties[0]);
                closeModal();
            } catch (error) {
                showModalAlert(error.message || 'فشل حفظ بيانات المواطن.');
            } finally {
                modalSaveBtn.disabled = false;
                modalSaveBtn.textContent = 'حفظ المواطن واختيار العقار';
            }
            return;
        }

        updateFormFromModal(citizenId, citizenName, modalProperties);
        fillFormFields(citizenId, citizenName, citizenPhone, selectedProperty);
        closeModal();
    }

    async function handleSearchClick() {
        const citizenId = citizenIdInput.value.trim();
        if (citizenId.length !== 9) {
            showAlert('يرجى إدخال رقم هوية صالح مكون من 9 خانات.');
            return;
        }

        const data = await fetchCitizenData(citizenId);
        modalCitizenIdInput.value = citizenId;
        modalCitizenNameInput.value = data.citizen?.name || (typeof window.defaultCitizenName !== 'undefined' ? window.defaultCitizenName : '');
        modalCitizenPhoneInput.value = data.citizen?.phone || (typeof window.defaultCitizenPhone !== 'undefined' ? window.defaultCitizenPhone : '');
        modalCitizenStatusSelect.value = 'owner';
        modalAlert.classList.add('hidden');
        modalHasCreatedProperties = false;

        if (!data.found) {
            modalIsNewCitizen = true;
            modalProperties = [];
            selectedModalPropertyIndex = null;
            modalTitle.textContent = 'تسجيل مواطن جديد';
            modalMessage.textContent = data.message || 'المواطن غير مسجل، الرجاء إضافة بيانات المواطن وعقاراته.';
            modalNoProperties.classList.remove('hidden');
            renderModalProperties();
            openModal();
            return;
        }

        modalIsNewCitizen = false;
        modalProperties = (data.citizen.properties || []).map(p => ({
            id: p.id,
            piece_number: p.piece_number || '',
            voucher_number: p.voucher_number || '',
            detailed_address: p.detailed_address || '',
            regulation_file_number: p.regulation_file_number || '',
            subscription_number: p.subscription_number || '',
            relation_status: p.relation_status || p.citizen_status || 'owner',
            isNewProperty: false
        }));
        selectedModalPropertyIndex = modalProperties.length ? 0 : null;
        modalTitle.textContent = 'المواطن موجود في النظام';
        modalMessage.textContent = 'تم العثور على المواطن. اختر أحد العقارات المرتبطة أو أضف عقاراً جديداً.';
        renderModalProperties();
        openModal();
    }

    function handleAddPropertyToModal() {
        clearModalAlert();
        const piece = modalPropPiece.value.trim();
        const voucher = modalPropVoucher.value.trim();
        const address = modalPropAddress.value.trim();
        const regFile = modalPropRegFile.value.trim();
        const subNumber = modalPropSubNumber.value.trim();
        const relationStatus = modalCitizenStatusSelect.value;

        if (!piece && !voucher && !address && !regFile && !subNumber) {
            showModalAlert('يرجى تعبئة أي بيان للعقار لإضافته.');
            return;
        }
        if (piece && voucher && modalProperties.some(prop => prop.piece_number === piece && prop.voucher_number === voucher)) {
            showModalAlert('هذا العقار موجود بالفعل في القائمة.');
            return;
        }

        const newProp = {
            piece_number: piece,
            voucher_number: voucher,
            detailed_address: address || 'غير محدد',
            regulation_file_number: regFile || '',
            subscription_number: subNumber || '',
            relation_status: relationStatus,
            isNewProperty: true
        };

        modalProperties.push(newProp);
        selectedModalPropertyIndex = modalProperties.length - 1;
        modalHasCreatedProperties = true;
        renderModalProperties();
        modalPropPiece.value = '';
        modalPropVoucher.value = '';
        modalPropAddress.value = '';
        modalPropRegFile.value = '';
        modalPropSubNumber.value = '';
    }

    searchBtn.addEventListener('click', handleSearchClick);
    citizenIdInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            handleSearchClick();
        }
    });

    modalAddPropertyButton.addEventListener('click', handleAddPropertyToModal);
    modalSaveBtn.addEventListener('click', handleModalSave);
    modalCloseButtons.forEach(btn => btn.addEventListener('click', closeModal));

    if (toggleNewPropertyBtn) {
        toggleNewPropertyBtn.addEventListener('click', () => {
            newPropertyFormContainer.classList.toggle('hidden');
        });
    }

    function showAlert(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'تنبيه', text: message, confirmButtonText: 'حسناً' });
            return;
        }
        alert(message);
    }
</script>
@endpush
