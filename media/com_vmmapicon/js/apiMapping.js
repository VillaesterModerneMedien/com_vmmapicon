const ApiMapping = {
    apiData: [],
    fieldName: '',
    fieldId: '',
    hiddenInput: null,
    existingData: {},
    rowCounter: 0,
    initialized: false,

    init() {
        if (this.initialized) return;
        if (!window.apiMappingConfig) {
            // Try again shortly if config not ready yet
            setTimeout(() => this.init(), 50);
            return;
        }

        this.apiData = window.apiMappingConfig.apiData || [];
        this.fieldName = window.apiMappingConfig.fieldName || '';
        this.fieldId = window.apiMappingConfig.fieldId || '';
        this.existingData = window.apiMappingConfig.existingData || {};
        this.lang = window.apiMappingConfig.lang || {};
        this.hiddenInput = document.getElementById(this.fieldId);
        let selectors = document.getElementById('jform_api_selectors');
        if (selectors) {
            try {
                selectors.value = JSON.stringify(this.apiData);
            } catch (e) {
                selectors.value = Array.isArray(this.apiData) ? this.apiData.join(',') : '';
            }
        }

        if (!this.hiddenInput) {
            console.error('Hidden input not found');
            return;
        }

        this.loadExistingData();
        this.bindEvents();

        // Bind add-row button if present
        const addBtn = document.getElementById('apimapping-add-row');
        if (addBtn && !addBtn.__apimappingBound) {
            addBtn.addEventListener('click', () => this.addRow());
            addBtn.__apimappingBound = true;
        }
        this.initialized = true;
    },


    loadExistingData() {
        const container = document.getElementById('subform-rows');
        if (!container) return;

        // Existierende Daten laden
        if (Object.keys(this.existingData).length > 0) {
            Object.entries(this.existingData).forEach(([key, data]) => {
                this.addRow(data, key);
            });
        } else {
            // Erste leere Zeile hinzufügen
            this.addRow();
        }
    },

    addRow(data = null, existingKey = null) {
        const container = document.getElementById('subform-rows');
        if (!container) return;

        const key = existingKey || `json_mapping${this.rowCounter}`;
        this.rowCounter++;

        const l = this.lang;
        const inList = data && data.json_path ? this.apiData.includes(data.json_path) : true;
        const currentPath = data && data.json_path ? data.json_path : '';
        const rowHtml = `
            <div class="subform-row card mb-3" data-key="${key}">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">${l.jsonPathLabel || 'JSON Path'}</label>
                            <div class="d-flex gap-1 align-items-start">
                                <select class="form-select json-path-select" style="${inList ? '' : 'display:none;'}">
                                    <option value="">${l.jsonPathPlaceholder || '-- Select JSON Path --'}</option>
                                    ${this.apiData.map(path =>
                                        `<option value="${path}" ${currentPath === path ? 'selected' : ''}>${path}</option>`
                                    ).join('')}
                                </select>
                                <input type="text" class="form-control json-path-input" value="${!inList ? currentPath : ''}" placeholder="${l.jsonPathInputPlaceholder || 'e.g. data->attributes->title'}" style="${inList ? 'display:none;' : ''}">
                                <button type="button" class="btn btn-outline-secondary btn-sm toggle-path-mode">
                                    ${inList ? (l.jsonPathModeManual || 'Manual') : (l.jsonPathModeSelect || 'Select')}
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">${l.yoothemeNameLabel || 'YOOtheme Name'}</label>
                            <input type="text" class="form-control yootheme-name"
                                   value="${data ? data.yootheme_name || '' : ''}"
                                   placeholder="${l.yoothemeNamePlaceholder || 'e.g. articleTitle'}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">${l.typeLabel || 'Type'}</label>
                            <select class="form-select field-type">
                                ${[
                                    { value: 'String',  label: l.typeString  || 'String' },
                                    { value: 'Number',  label: l.typeNumber  || 'Number' },
                                    { value: 'Boolean', label: l.typeBoolean || 'Boolean' },
                                    { value: 'Array',   label: l.typeArray   || 'Array' },
                                    { value: 'Object',  label: l.typeObject  || 'Object' }
                                ].map(opt => `<option value="${opt.value}" ${data && data.field_type === opt.value ? 'selected' : ''}>${opt.label}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">${l.fieldLabelLabel || 'Label'}</label>
                            <input type="text" class="form-control field-label"
                                   value="${data ? data.field_label || '' : ''}"
                                   placeholder="${l.fieldLabelPlaceholder || 'e.g. Article Title'}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="ApiMapping.removeRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        this.updateHiddenInput();
    },

    removeRow(button) {
        const row = button.closest('.subform-row');
        if (row) {
            row.remove();
            this.updateHiddenInput();
        }
    },

    bindEvents() {
        const container = document.getElementById('subform-rows');
        if (!container) return;

        container.addEventListener('change', () => {
            this.updateHiddenInput();
        });

        container.addEventListener('input', () => {
            this.updateHiddenInput();
        });

        container.addEventListener('click', (e) => {
            const btn = e.target.closest('.toggle-path-mode');
            if (!btn) return;
            const card = btn.closest('.subform-row');
            if (!card) return;
            const select = card.querySelector('.json-path-select');
            const input = card.querySelector('.json-path-input');
            const toManual = select && select.style.display !== 'none';
            if (toManual) {
                // Switch to manual
                input.value = select.value || '';
                select.style.display = 'none';
                input.style.display = '';
                btn.textContent = (this.lang.jsonPathModeSelect || 'Select');
            } else {
                // Switch to select
                select.style.display = '';
                input.style.display = 'none';
                btn.textContent = (this.lang.jsonPathModeManual || 'Manual');
            }
            this.updateHiddenInput();
        });
    },

    updateHiddenInput() {
        const rows = document.querySelectorAll('.subform-row');
        const data = {};
        let index = 0;

        rows.forEach(row => {
            const select = row.querySelector('.json-path-select');
            const input = row.querySelector('.json-path-input');
            const jsonPath = (input && input.style.display !== 'none') ? input.value : (select ? select.value : '');
            const yoothemeName = row.querySelector('.yootheme-name').value;
            const fieldType = row.querySelector('.field-type').value;
            const fieldLabel = row.querySelector('.field-label').value;

            // Nur speichern wenn mindestens ein Feld ausgefüllt ist
            if (jsonPath || yoothemeName || fieldLabel) {
                const key = `json_mapping${index}`;
                data[key] = {
                    json_path: jsonPath,
                    yootheme_name: yoothemeName,
                    field_type: fieldType || 'String',
                    field_label: fieldLabel
                };
                index++;
            }
        });

        if (this.hiddenInput) {
            this.hiddenInput.value = JSON.stringify(data);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    ApiMapping.init();
});

// Expose globally for inline handlers and manual init
window.ApiMapping = ApiMapping;
