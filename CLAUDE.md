# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Joomla extension package consisting of two main components:

1. **com_vmmapicon** - A Joomla component for API configuration and data mapping
2. **ytvmmapicon** - A system plugin that integrates with the YTFramework for real estate data processing

The system is designed to fetch data from real estate APIs, map the JSON responses to structured fields, and integrate with Joomla's content management system.

## Architecture

### Component Structure (com_vmmapicon)
```
administrator/components/com_vmmapicon/
├── src/
│   ├── Controller/          # MVC Controllers for API and ApiResult management
│   ├── Field/              # Custom form fields (JsonSelector)
│   ├── Helper/             # API helper with validation and HTTP client functionality
│   └── View/               # Admin views
├── forms/                  # XML form definitions for API configuration
├── sql/                    # Database schema and migration files
├── tmpl/                   # View templates
└── language/               # Internationalization files (DE/EN)

components/com_vmmapicon/
├── src/
│   ├── Controller/         # Frontend controllers
│   ├── Model/              # Data models for API handling
│   ├── Service/            # Router service
│   └── View/               # Frontend views
└── tmpl/                   # Frontend templates
```

### Plugin Structure (ytvmmapicon)
```
plugins/system/ytvmmapicon/
├── src/
│   ├── Helper/             # Field mapping helpers
│   ├── Listener/           # Event listeners for framework integration
│   └── Type/               # API type definitions and field configurations
├── element/                # YTFramework element definitions
└── language/               # Plugin language files
```

### Key Classes and Functionality

- **ApiHelper** (`administrator/components/com_vmmapicon/src/Helper/ApiHelper.php`): Core API client with validation, parameter formatting, and HTTP request handling
- **JsonselectorField** (`administrator/components/com_vmmapicon/src/Field/Jsonselector/JsonselectorField.php`): Custom Joomla form field for JSON path selection
- **ApiType** (`plugins/system/ytvmmapicon/src/Type/ApiType.php`): Extensive field configuration for real estate data mapping (1400+ lines defining property fields)

## Development Commands

This is a Joomla extension without traditional build tools like npm or composer. Development involves:

1. **Installation**: Deploy files to Joomla installation directory
2. **Database**: Run SQL scripts in `administrator/components/com_vmmapicon/sql/`
3. **Testing**: Use Joomla's built-in extension installer and test in admin interface
4. **Language**: Modify `.ini` files in language directories for translations
5. **Assets**: JavaScript files in `media/com_vmmapicon/js/` for frontend functionality

## Key Features

### API Configuration
- Flexible API endpoint configuration with method, URL, and parameter management
- Support for headers, URL parameters, and request body data
- JSON response validation and error handling
- SSL verification settings (currently disabled for development)

### Data Mapping
- JSON selector field for mapping API responses to Joomla fields
- Extensive real estate field definitions covering apartments, houses, commercial properties
- Support for multiple property types with grouped field organization
- Image attachment handling and processing

### Integration Points
- Joomla MVC architecture compliance
- YTFramework integration for advanced field types
- Event-driven architecture with plugin listeners
- Multi-language support (German/English)

## Database Schema

The component creates tables for:
- API configurations
- API results/responses
- Field mappings and relationships

SQL files in `administrator/components/com_vmmapicon/sql/` handle installation and updates.

## Security Considerations

- API calls include timeout and redirect limits
- SSL verification currently disabled (should be enabled in production)
- Input validation through Joomla's form framework
- SQL injection protection via Joomla's database abstraction

## File Locations

- **Main component**: `administrator/components/com_vmmapicon/`
- **Frontend component**: `components/com_vmmapicon/`
- **System plugin**: `plugins/system/ytvmmapicon/`
- **Media assets**: `media/com_vmmapicon/`
- **Language files**: Multiple directories under each component/plugin
- **Installation manifest**: `administrator/components/com_vmmapicon/vmmapicon.xml`