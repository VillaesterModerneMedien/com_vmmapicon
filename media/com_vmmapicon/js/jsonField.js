const ApiMapper = {
    cmsFields: {},
    availableTypes: ['String', 'Number', 'Boolean', 'Date', 'Object', 'Array'],

    init(containerId, apiData = null) {
        this.container = document.getElementById(containerId);

        if (apiData) {
            this.apiData = apiData;
        }

        console.log('ApiMapper init:', { containerId, apiData: this.apiData, container: this.container });

        if (!this.container) {
            console.error('Container not found:', containerId);
            return;
        }

        this.loadApiData();
        this.renderFields();
        this.bindEvents();
    },

    loadApiData() {
        this.cmsFields = {};
        console.log('Processing complete API data:', this.apiData);

        if (this.apiData && typeof this.apiData === 'object') {
            this.processApiData(this.apiData, '');
        }

        console.log('Final CMS fields:', this.cmsFields);
    },

    processApiData(obj, path) {
        if (obj === null || obj === undefined) return;

        if (Array.isArray(obj)) {
            obj.forEach((item, index) => {
                const arrayPath = path ? `${path}[${index}]` : `[${index}]`;

                if (item && typeof item === 'object') {
                    this.processApiData(item, arrayPath);
                } else {
                    this.cmsFields[arrayPath] = {
                        jsonPath: arrayPath,
                        yoothemeName: this.generateYooThemeName(arrayPath),
                        type: this.getFieldType(item),
                        metadata: {
                            label: this.generateLabel(arrayPath)
                        }
                    };
                }
            });
        } else if (typeof obj === 'object') {
            Object.entries(obj).forEach(([key, value]) => {
                const fullKey = path ? `${path}.${key}` : key;

                if (value && typeof value === 'object') {
                    this.processApiData(value, fullKey);
                } else {
                    this.cmsFields[fullKey] = {
                        jsonPath: fullKey,
                        yoothemeName: this.generateYooThemeName(key),
                        type: this.getFieldType(value),
                        metadata: {
                            label: this.generateLabel(key)
                        }
                    };
                }
            });
        }
    },

    generateYooThemeName(path) {
        // Extrahiere den letzten Teil des Pfads und konvertiere zu camelCase
        const lastPart = path.split(/[.\[\]]/).filter(Boolean).pop() || path;
        return lastPart.replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
    },

    generateLabel(key) {
        // Generiere ein schönes Label aus dem Key
        const lastPart = key.split(/[.\[\]]/).filter(Boolean).pop() || key;
        return lastPart.charAt(0).toUpperCase() + lastPart.slice(1).replace(/([A-Z])/g, ' $1');
    },

    getFieldType(value) {
        if (value === null || value === undefined) return 'String';
        if (typeof value === 'number') return 'Number';
        if (typeof value === 'boolean') return 'Boolean';
        if (Array.isArray(value)) return 'Array';
        if (typeof value === 'object') return 'Object';
        return 'String';
    },

    renderFields() {
        if (!this.container) {
            console.error('Container not available for rendering');
            return;
        }

        this.container.innerHTML = '';

        if (Object.keys(this.cmsFields).length === 0) {
            this.container.innerHTML = '<div class="alert alert-info">Keine API-Daten verfügbar.</div>';
            return;
        }

        // Header
        const headerHtml = `
            <div class="row fw-bold border-bottom pb-2 mb-3">
                <div class="col-3">JSON Pfad</div>
                <div class="col-3">YooTheme Name</div>
                <div class="col-2">Typ</div>
                <div class="col-4">Label</div>
            </div>
        `;
        this.container.insertAdjacentHTML('beforeend', headerHtml);

        // Sortiere die Keys für bessere Darstellung
        const sortedKeys = Object.keys(this.cmsFields).sort();

        sortedKeys.forEach(key => {
            const field = this.cmsFields[key];
            const depth = key.split(/[.\[\]]/).filter(Boolean).length - 1;

            const fieldHtml = `
                <div class="field-row row align-items-center mb-2 p-2 border rounded" data-key="${key}" style="margin-left:${depth * 10}px;">
                    <div class="col-3">
                        <small class="text-muted font-monospace">${field.jsonPath}</small>
                    </div>
                    <div class="col-3">
                        <input type="text" class="form-control form-control-sm field-yootheme-name" value="${field.yoothemeName}" placeholder="YooTheme Name">
                    </div>
                    <div class="col-2">
                        <select class="form-select form-select-sm field-type">
                            ${this.availableTypes.map(type =>
                `<option value="${type}" ${type === field.type ? 'selected' : ''}>${type}</option>`
            ).join('')}
                        </select>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control form-control-sm field-label" value="${field.metadata.label}" placeholder="Label">
                    </div>
                </div>
            `;
            this.container.insertAdjacentHTML('beforeend', fieldHtml);
        });

    },

    bindEvents() {
        if (!this.container) return;

        this.container.addEventListener('input', (e) => {
            const row = e.target.closest('.field-row');
            if (!row) return;

            const key = row.dataset.key;
            if (!this.cmsFields[key]) return;

            if (e.target.classList.contains('field-type')) {
                this.cmsFields[key].type = e.target.value;
            } else if (e.target.classList.contains('field-label')) {
                this.cmsFields[key].metadata.label = e.target.value;
            } else if (e.target.classList.contains('field-yootheme-name')) {
                this.cmsFields[key].yoothemeName = e.target.value;
            }

        });
    },

};

// Globale Initialisierung für Joomla
document.addEventListener('DOMContentLoaded', () => {
    function initApiMapper() {

        const apiData = document.getElementById('api-mapping').value;

        console.log('Initializing YooTheme mapper with API data:', apiData);

        const container = document.getElementById('api-mapper');
        if (container) {
            ApiMapper.init('api-mapper', apiData);
        }
    }

    initApiMapper();
});
