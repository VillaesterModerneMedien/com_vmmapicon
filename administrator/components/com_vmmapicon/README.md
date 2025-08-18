# VMM APICon Component (com_vmmapicon)

**Version:** 1.0.0  
**Author:** Mario Hewera & Kiki Schuelling  
**Company:** Villaester Moderne Medien GmbH  
**License:** GNU General Public License version 2 or later  
**Website:** https://villaester.de

## Übersicht

Die VM Map Icon Komponente ist eine umfassende Joomla 5 Erweiterung zur Verwaltung von API-Konfigurationen und deren Ergebnissen. Sie ermöglicht es Administratoren, externe APIs zu konfigurieren, deren Daten abzurufen und die Ergebnisse zu verwalten.

## Hauptfunktionen

### 🔧 API-Verwaltung
- **Flexible API-Konfiguration**: Unterstützung für GET, POST, PUT, DELETE HTTP-Methoden
- **Parameter-Management**: Dynamische Verwaltung von Header-, Body- und URL-Parametern
- **JSON-Mapping**: Intelligente Zuordnung von API-Antworten zu Datenbankfeldern
- **Validierung**: Automatische Überprüfung der API-Konfiguration

### 📊 API-Ergebnisse
- **Datenverarbeitung**: Automatische Verarbeitung und Speicherung von API-Antworten
- **Filterung**: Erweiterte Filter- und Suchfunktionen
- **Verwaltung**: Vollständige CRUD-Operationen für API-Ergebnisse
- **Beziehungen**: Foreign Key-Beziehungen zwischen APIs und deren Ergebnissen

### 🌐 Multi-Language Support
- **Deutsch (de-DE)**: Vollständige deutsche Übersetzung
- **Englisch (en-GB)**: Vollständige englische Übersetzung
- **Erweiterbar**: Einfache Erweiterung um weitere Sprachen

### 🔌 REST API
- **JSON API**: Vollständige REST API für externe Zugriffe
- **Standardisiert**: Joomla 5 Web Services API konform
- **Sicher**: Integrierte Joomla Authentifizierung und Autorisierung

## Architektur

### Komponenten-Struktur

```
com_vmmapicon/
├── administrator/          # Backend-Administration
│   ├── components/com_vmmapicon/
│   │   ├── forms/         # Formulardefinitionen
│   │   ├── language/      # Sprachdateien (Backend)
│   │   ├── layouts/       # Layout-Templates
│   │   ├── services/      # Dependency Injection
│   │   ├── sql/          # Datenbankschemas
│   │   ├── src/          # PHP-Klassen
│   │   ├── tmpl/         # View-Templates
│   │   └── vmmapicon.xml # Komponentenkonfiguration
├── components/com_vmmapicon/     # Frontend
│   ├── language/         # Sprachdateien (Frontend)
│   ├── src/             # PHP-Klassen
│   └── tmpl/            # View-Templates
├── api/components/com_vmmapicon/ # REST API
│   ├── Controller/      # API-Controller
│   ├── Model/          # API-Modelle
│   └── View/           # API-Views
└── media/com_vmmapicon/         # Assets
    ├── js/             # JavaScript-Dateien
    └── joomla.asset.json # Asset-Konfiguration
```

### Namespace-Struktur

```php
Villaester\Component\Vmmapicon\
├── Administrator\
│   ├── Controller\
│   ├── Extension\
│   ├── Field\
│   ├── Helper\
│   ├── Model\
│   ├── Service\
│   ├── Table\
│   └── View\
├── Site\
│   ├── Controller\
│   ├── Dispatcher\
│   ├── Helper\
│   ├── Model\
│   ├── Service\
│   └── View\
└── Api\
    ├── Controller\
    ├── Model\
    └── View\
```

## Datenbankschema

### Tabelle: `#__vmmapicon_apis`

Speichert API-Konfigurationen:

```sql
CREATE TABLE `#__vmmapicon_apis` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(255) NOT NULL DEFAULT '',
    `api_url` text NOT NULL,
    `api_method` varchar(10) NOT NULL DEFAULT 'GET',
    `api_params` longtext NULL,
    `api_mapping` longtext NULL,
    `published` tinyint(4) NOT NULL DEFAULT '1',
    `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by` int(11) NOT NULL DEFAULT '0',
    `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` int(11) NOT NULL DEFAULT '0',
    `access` int(11) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
);
```

**Felder:**
- `id`: Primärschlüssel
- `title`: Beschreibender Titel der API
- `api_url`: Vollständige URL der API
- `api_method`: HTTP-Methode (GET, POST, PUT, DELETE)
- `api_params`: JSON-String mit Parametern (Header, Body, URL)
- `api_mapping`: JSON-String für Feldmapping
- `published`: Veröffentlichungsstatus
- Standard Joomla-Metadaten (created, modified, access)

### Tabelle: `#__vmmapicon_apiresults`

Speichert API-Ergebnisse:

```sql
CREATE TABLE `#__vmmapicon_apiresults` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `api_id` int(10) unsigned NOT NULL,
    `title` varchar(255) NOT NULL DEFAULT '',
    `alias` varchar(400) NOT NULL DEFAULT '',
    `description` text,
    `published` tinyint(1) NOT NULL DEFAULT 1,
    `ordering` int(11) NOT NULL DEFAULT 0,
    `params` text,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_vmmapicon_apiresults_api` 
        FOREIGN KEY (`api_id`) REFERENCES `#__vmmapicon_apis` (`id`) 
        ON DELETE CASCADE
);
```

**Felder:**
- `id`: Primärschlüssel
- `api_id`: Foreign Key zur APIs-Tabelle
- `title`: Titel des Ergebnisses
- `alias`: URL-freundlicher Alias
- `description`: Beschreibung
- `params`: JSON-String mit zusätzlichen Parametern
- Standard Joomla-Metadaten

## Kernklassen

### ApiHelper

**Pfad:** `src/Helper/ApiHelper.php`

Zentrale Hilfsklasse für API-Operationen:

```php
class ApiHelper extends ContentHelper
{
    public static function isApiConfigValid($apiConfig): bool
    public static function makeApiCall($apiConfig): array
    public static function processApiResponse($response, $mapping): array
    protected static function _formatParams($allParams): array
    protected static function _buildUrl($baseUrl, $params): string
    protected static function _buildHeaders($params): array
    protected static function _buildBody($params): string
}
```

**Hauptfunktionen:**
- Validierung von API-Konfigurationen
- Durchführung von HTTP-Requests
- Verarbeitung von API-Antworten
- Parameter-Formatierung

### JsonselectorField

**Pfad:** `src/Field/Jsonselector/JsonselectorField.php`

Benutzerdefiniertes Formularfeld für JSON-Datenauswahl:

```php
class JsonselectorField extends FormField
{
    protected $type = 'Jsonselector';
    protected $layout = 'vmm.form.field.jsonselector';
    
    protected function _getJsonKeys($json): array
    private function collectKeysRecursive($data, &$keys): void
    protected function getInput(): string
}
```

**Funktionen:**
- Extraktion von JSON-Schlüsseln
- Rekursive Datenverarbeitung
- Dynamische Formularfeld-Generierung

### VmmapiconComponent

**Pfad:** `src/Extension/VmmapiconComponent.php`

Hauptkomponenten-Klasse:

```php
class VmmapiconComponent extends MVCComponent implements 
    BootableExtensionInterface, 
    CategoryServiceInterface, 
    FieldsServiceInterface, 
    AssociationServiceInterface, 
    RouterServiceInterface,
    TagServiceInterface
{
    public function boot(ContainerInterface $container): void
    public function getCategory(array $options = []): CategoryInterface
    public function getRouter(SiteApplication $application): RouterInterface
}
```

## API-Endpunkte

### REST API Zugriff

**Basis-URL:** `/api/index.php/v1/vmmapicon`

#### Verfügbare Endpunkte:

1. **GET /apiresults** - Liste aller API-Ergebnisse
2. **GET /apiresults/{id}** - Einzelnes API-Ergebnis
3. **POST /apiresults** - Neues API-Ergebnis erstellen
4. **PUT /apiresults/{id}** - API-Ergebnis aktualisieren
5. **DELETE /apiresults/{id}** - API-Ergebnis löschen

#### Authentifizierung

Die API verwendet Joomla's Standard-Authentifizierung:
- **API Token**: Über Joomla User-Profile konfigurierbar
- **Basic Auth**: Username/Password
- **Session-basiert**: Für Frontend-Zugriffe

#### Beispiel-Request:

```bash
curl -X GET \
  'https://example.com/api/index.php/v1/vmmapicon/apiresults' \
  -H 'Authorization: Bearer YOUR_API_TOKEN' \
  -H 'Content-Type: application/json'
```

## Formulare

### API-Formular (`forms/api.xml`)

Definiert die Eingabefelder für API-Konfigurationen:

```xml
<form>
    <fieldset addfieldprefix="Villaester\Component\Vmmapicon\Administrator\Field">
        <field name="title" type="text" required="true" />
        <field name="api_url" type="text" required="true" />
        <field name="api_method" type="list" default="GET">
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="DELETE">DELETE</option>
        </field>
        <field name="api_params" type="textarea" />
        <field name="api_mapping" type="textarea" />
    </fieldset>
</form>
```

### API-Ergebnis-Formular (`forms/apiresult.xml`)

Definiert die Eingabefelder für API-Ergebnisse:

```xml
<form>
    <fieldset>
        <field name="api_id" type="sql" 
               query="SELECT id, title FROM #__vmmapicon_apis WHERE published = 1" />
        <field name="title" type="text" required="true" />
        <field name="description" type="textarea" />
        <field name="published" type="list" default="1" />
    </fieldset>
</form>
```

## Sprachdateien

### Administrator-Sprachen

**Deutsch (`de-DE/com_vmmapicon.ini`):**
```ini
COM_VMMAPICON_XML_DESCRIPTION="VM Map Icon Komponente zur Verwaltung von API-Konfigurationen"
COM_VMMAPICON_VIEW_APIS="APIs"
COM_VMMAPICON_FIELD_APITITLE_LABEL="API-Titel"
COM_VMMAPICON_FIELD_APIURL_LABEL="API-URL"
```

**Englisch (`en-GB/com_vmmapicon.ini`):**
```ini
COM_VMMAPICON_XML_DESCRIPTION="VM Map Icon component for managing API configurations"
COM_VMMAPICON_VIEW_APIS="APIs"
COM_VMMAPICON_FIELD_APITITLE_LABEL="API Title"
COM_VMMAPICON_FIELD_APIURL_LABEL="API URL"
```

### System-Sprachen

**System-Dateien (`*.sys.ini`):**
- Menüeinträge
- Komponentenbeschreibungen
- Installationsmeldungen

## Installation

### Systemanforderungen

- **Joomla:** 5.0 oder höher
- **PHP:** 8.1 oder höher
- **MySQL:** 5.7 oder höher / MariaDB 10.3 oder höher
- **Extensions:** cURL, JSON

### Installationsschritte

1. **Paket hochladen:**
   ```
   Extensions → Manage → Install
   ```

2. **Automatische Installation:**
   - Datenbankschema wird automatisch erstellt
   - Sprachdateien werden installiert
   - Menüeinträge werden erstellt

3. **Konfiguration:**
   - Komponenten-Einstellungen über `System → Global Configuration`
   - API-Konfigurationen über `Components → VM Map Icon`

### Deinstallation

```sql
-- Automatische Bereinigung durch uninstall.mysql.utf8.sql
DROP TABLE IF EXISTS `#__vmmapicon_apiresults`;
DROP TABLE IF EXISTS `#__vmmapicon_apis`;
```

## Konfiguration

### Komponenten-Einstellungen

**Pfad:** `System → Global Configuration → VM Map Icon`

Verfügbare Optionen:
- **API Timeout**: Standard-Timeout für API-Calls (Sekunden)
- **Cache-Dauer**: Caching-Zeit für API-Ergebnisse
- **Debug-Modus**: Erweiterte Fehlerprotokollierung
- **Berechtigungen**: Zugriffskontrolle für verschiedene Benutzergruppen

### API-Parameter-Format

API-Parameter werden als JSON gespeichert:

```json
{
    "headers": {
        "Authorization": "Bearer token123",
        "Content-Type": "application/json"
    },
    "body": {
        "param1": "value1",
        "param2": "value2"
    },
    "url_params": {
        "limit": "10",
        "offset": "0"
    }
}
```

### Mapping-Konfiguration

Feldmapping für API-Antworten:

```json
{
    "title": "response.data.name",
    "description": "response.data.description",
    "custom_field": "response.metadata.custom"
}
```

## Entwicklung

### Erweiterung der Komponente

#### Neue Felder hinzufügen

1. **Datenbankschema erweitern:**
   ```sql
   ALTER TABLE `#__vmmapicon_apis` 
   ADD COLUMN `new_field` VARCHAR(255) NULL;
   ```

2. **Formular aktualisieren:**
   ```xml
   <field name="new_field" type="text" 
          label="COM_VMMAPICON_FIELD_NEWFIELD_LABEL" />
   ```

3. **Sprachdateien erweitern:**
   ```ini
   COM_VMMAPICON_FIELD_NEWFIELD_LABEL="Neues Feld"
   ```

#### Custom Fields erstellen

```php
namespace Villaester\Component\Vmmapicon\Administrator\Field;

use Joomla\CMS\Form\FormField;

class CustomField extends FormField
{
    protected $type = 'Custom';
    
    protected function getInput()
    {
        // Custom field logic
        return '<input type="text" name="' . $this->name . '" />';
    }
}
```

### Testing

#### Unit Tests

```php
use PHPUnit\Framework\TestCase;
use Villaester\Component\Vmmapicon\Administrator\Helper\ApiHelper;

class ApiHelperTest extends TestCase
{
    public function testIsApiConfigValid()
    {
        $config = new \stdClass();
        $config->api_url = 'https://api.example.com';
        $config->api_method = 'GET';
        $config->api_params = '{}';
        
        $this->assertTrue(ApiHelper::isApiConfigValid($config));
    }
}
```

#### Integration Tests

```php
public function testApiCall()
{
    $config = $this->getValidApiConfig();
    $result = ApiHelper::makeApiCall($config);
    
    $this->assertIsArray($result);
    $this->assertArrayHasKey('status', $result);
    $this->assertEquals('success', $result['status']);
}
```

## Fehlerbehebung

### Häufige Probleme

#### 1. API-Calls schlagen fehl

**Symptom:** API-Ergebnisse werden nicht abgerufen

**Lösungen:**
- cURL-Extension prüfen: `php -m | grep curl`
- SSL-Zertifikate validieren
- Firewall-Einstellungen überprüfen
- API-URL und Parameter validieren

#### 2. Datenbankfehler

**Symptom:** Foreign Key Constraint Fehler

**Lösung:**
```sql
-- Prüfe Referenzen
SELECT * FROM `#__vmmapicon_apiresults` 
WHERE `api_id` NOT IN (SELECT `id` FROM `#__vmmapicon_apis`);

-- Bereinige verwaiste Einträge
DELETE FROM `#__vmmapicon_apiresults` 
WHERE `api_id` NOT IN (SELECT `id` FROM `#__vmmapicon_apis`);
```

#### 3. Sprachdateien werden nicht geladen

**Symptom:** Englische Sprachschlüssel werden angezeigt

**Lösung:**
- Sprachdateien-Pfade in `vmmapicon.xml` prüfen
- Cache leeren: `System → Clear Cache`
- Dateiberechtigungen überprüfen

### Debug-Modus

**Aktivierung:**
```php
// In configuration.php
public $debug = true;
public $debug_lang = true;
```

**Log-Dateien:**
- `administrator/logs/everything.php`
- `administrator/logs/error.php`

### Performance-Optimierung

#### Caching aktivieren

```php
// In ApiHelper.php
use Joomla\CMS\Cache\CacheControllerFactoryInterface;

$cache = Factory::getContainer()
    ->get(CacheControllerFactoryInterface::class)
    ->createCacheController('output', ['lifetime' => 3600]);
```

#### Datenbankindizes

```sql
-- Optimierung für häufige Abfragen
CREATE INDEX idx_api_published ON `#__vmmapicon_apis` (`published`);
CREATE INDEX idx_apiresults_api_published ON `#__vmmapicon_apiresults` (`api_id`, `published`);
```

## Sicherheit

### Berechtigungen

**ACL-Konfiguration (`access.xml`):**
```xml
<access component="com_vmmapicon">
    <section name="component">
        <action name="core.admin" title="JACTION_ADMIN" />
        <action name="core.manage" title="JACTION_MANAGE" />
        <action name="core.create" title="JACTION_CREATE" />
        <action name="core.edit" title="JACTION_EDIT" />
        <action name="core.delete" title="JACTION_DELETE" />
    </section>
</access>
```

### Input-Validierung

```php
// In Model-Klassen
use Joomla\CMS\Filter\InputFilter;

$filter = InputFilter::getInstance();
$cleanData = $filter->clean($data, 'string');
```

### SQL-Injection-Schutz

```php
// Prepared Statements verwenden
$query = $db->getQuery(true)
    ->select('*')
    ->from('#__vmmapicon_apis')
    ->where('id = :id')
    ->bind(':id', $id, ParameterType::INTEGER);
```

## Changelog

### Version 1.0.0 (Mai 2025)
- ✅ Initiale Veröffentlichung
- ✅ API-Verwaltung implementiert
- ✅ API-Ergebnisse-System
- ✅ Multi-Language-Support (DE/EN)
- ✅ REST API-Endpunkte
- ✅ Custom JSON-Selector Field
- ✅ Vollständige Joomla 5 Kompatibilität

### Geplante Features (Version 1.1.0)
- 🔄 Erweiterte Mapping-Optionen
- 🔄 Bulk-Import/Export
- 🔄 Erweiterte Caching-Mechanismen
- 🔄 Webhook-Support
- 🔄 Erweiterte Benutzeroberfläche

## Support

### Dokumentation
- **GitHub:** https://github.com/villaester/com_vmmapicon
- **Wiki:** https://github.com/villaester/com_vmmapicon/wiki

### Community
- **Forum:** https://villaester.de/forum
- **Discord:** https://discord.gg/villaester

### Kommerzieller Support
- **E-Mail:** support@villaester.de
- **Telefon:** +49 (0) 123 456789
- **Website:** https://villaester.de/support

## Lizenz

Diese Komponente ist unter der GNU General Public License version 2 or later lizenziert.

```
Copyright (C) 2025 Villaester Moderne Medien GmbH

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## Mitwirkende

- **Mario Hewera** - Hauptentwickler
- **Kiki Schuelling** - Co-Entwickler
- **VMM Development Team** - Qualitätssicherung

---

**Villaester Moderne Medien GmbH**  
Website: https://villaester.de  
E-Mail: info@villaester.de

*Diese Dokumentation wurde automatisch generiert und wird regelmäßig aktualisiert.*
