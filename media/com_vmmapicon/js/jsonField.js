const ApiMapper = {

    apiData: {apiData},

    cmsFields: {},
    availableTypes: ['String', 'Number', 'Boolean', 'Date', 'Object'],

    init(containerId) {
        this.container = document.getElementById(containerId);
        this.loadApiData();
        this.renderFields();
        this.bindEvents();
    },

    loadApiData() {
        this.cmsFields = {}; // vollständig neu aufbauen
        this.processApiData(this.apiData, ''); // Pfad leer am Anfang
    },

    processApiData(obj, path) {
        Object.entries(obj).forEach(([key, value]) => {
            const fullKey = path ? `${path}.${key}` : key;

            if (value && typeof value === 'object' && ('value' in value || 'name' in value)) {
                this.cmsFields[fullKey] = {
                    type: value.type || 'String',
                    metadata: {
                        label: value.name || key,
                        value: value.value || ''
                    }
                };

                // Suche nach verschachtelten Unterfeldern
                const subkeys = Object.keys(value).filter(k => typeof value[k] === 'object' && !['value', 'type', 'name'].includes(k));
                subkeys.forEach(subkey => {
                    this.processApiData({ [subkey]: value[subkey] }, fullKey);
                });
            }
        });
    },

    renderFields() {
        this.container.innerHTML = '';
        Object.keys(this.cmsFields).forEach(key => {
            const field = this.cmsFields[key];
            const depth = key.split('.').length - 1;

            const fieldHtml = `
                <div class="field-row d-flex align-items-center mb-2" data-key="${key}" style="margin-left:${depth * 20}px;">
                    <div class="me-2 fw-bold" style="width:150px">${key}</div>
                    <select class="form-select form-select-sm me-2 field-type" style="width:130px;">
                        ${this.availableTypes.map(type =>
                `<option value="${type}" ${type === field.type ? 'selected' : ''}>${type}</option>`
            ).join('')}
                    </select>
                    <input type="text" class="form-control form-control-sm me-2 field-label" value="${field.metadata.label}" placeholder="Label" style="width:160px;">
                    <input type="text" class="form-control form-control-sm field-value" value="${field.metadata.value}" placeholder="Value">
                </div>
            `;
            this.container.insertAdjacentHTML('beforeend', fieldHtml);
        });

        this.updateJsonOutput();
    },

    bindEvents() {
        this.container.addEventListener('input', (e) => {
            const row = e.target.closest('.field-row');
            if (!row) return;
            const key = row.dataset.key;
            if (e.target.classList.contains('field-type')) {
                this.cmsFields[key].type = e.target.value;
            } else if (e.target.classList.contains('field-label')) {
                this.cmsFields[key].metadata.label = e.target.value;
            } else if (e.target.classList.contains('field-value')) {
                this.cmsFields[key].metadata.value = e.target.value;
            }
            this.updateJsonOutput();
        });
    },

    updateJsonOutput() {
        const outputElement = document.getElementById('json-output');
        if (outputElement) {
            outputElement.textContent = JSON.stringify(this.cmsFields, null, 2);
        }
    }
};

let apiData = '';
document.addEventListener('DOMContentLoaded', () => {
    apiData = Joomla.getOptions('com_vmmapicon');
    ApiMapper.init('api-mapper');
});