const JsonSubform = {
    apiData: [],
    fieldName: '',
    fieldId: '',
    hiddenInput: null,
    existingData: {},
    rowCounter: 0,

    init() {
        if (!window.jsonSubformConfig) {
            console.error('JsonSubform config not found');
            return;
        }

        this.apiData = window.jsonSubformConfig.apiData || [];
        this.fieldName = window.jsonSubformConfig.fieldName || '';
        this.fieldId = window.jsonSubformConfig.fieldId || '';
        this.existingData = window.jsonSubformConfig.existingData || {};
        this.hiddenInput = document.getElementById(this.fieldId);
        let selectors = document.getElementById('jform_api_selectors');
        selectors.value = this.apiData;

        if (!this.hiddenInput) {
            console.error('Hidden input not found');
            return;
        }

        this.loadExistingData();
        this.bindEvents();
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

        const rowHtml = `
            <div class="subform-row card mb-3" data-key="${key}">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">JSON Pfad</label>
                            <select class="form-select json-path-select">
                                <option value="">-- JSON Pfad wählen --</option>
                                ${this.apiData.map(path =>
            `<option value="${path}" ${data && data.json_path === path ? 'selected' : ''}>${path}</option>`
        ).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">YooTheme Name</label>
                            <input type="text" class="form-control yootheme-name"
                                   value="${data ? data.yootheme_name || '' : ''}"
                                   placeholder="z.B. articleTitle">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Typ</label>
                            <select class="form-select field-type">
                                <option value="String" ${data && data.field_type === 'String' ? 'selected' : ''}>String</option>
                                <option value="Number" ${data && data.field_type === 'Number' ? 'selected' : ''}>Number</option>
                                <option value="Boolean" ${data && data.field_type === 'Boolean' ? 'selected' : ''}>Boolean</option>
                                <option value="Array" ${data && data.field_type === 'Array' ? 'selected' : ''}>Array</option>
                                <option value="Object" ${data && data.field_type === 'Object' ? 'selected' : ''}>Object</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control field-label"
                                   value="${data ? data.field_label || '' : ''}"
                                   placeholder="z.B. Artikel Titel">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="JsonSubform.removeRow(this)">
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
    },

    updateHiddenInput() {
        const rows = document.querySelectorAll('.subform-row');
        const data = {};
        let index = 0;

        rows.forEach(row => {
            const jsonPath = row.querySelector('.json-path-select').value;
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
    JsonSubform.init();
});