# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

VMMapicon is a Joomla 4/5 component that enables API integration and data mapping, particularly for YooTheme Pro page builder. It allows configuration of external API endpoints, mapping JSON responses to custom fields, and exposing this data through GraphQL.

## Development Commands

### Building the YooTheme Plugin
```bash
cd plugins/system/vmmapiconyt
./build.sh
```
This creates an installable ZIP package in the same directory.

## Architecture Overview

### Component Structure
The project follows Joomla 4+ MVC architecture with namespaced classes under `Villaester\Component\Vmmapicon`:

- **Backend (`administrator/components/com_vmmapicon/`)**: Admin interface for API configuration and mapping
- **Frontend (`components/com_vmmapicon/`)**: Public-facing component views
- **Plugins (`plugins/system/`)**: YooTheme Pro integration via GraphQL

### Key Technical Components

1. **API Configuration System**: Stores API endpoints in `#__vmmapicon_apis` table with support for various HTTP methods, authentication headers, and parameter handling.

2. **JSON Mapping Interface**: Interactive JavaScript UI (`media/com_vmmapicon/js/jsonField.js`) that allows visual mapping of API response fields to YooTheme fields with type definitions.

3. **YooTheme Integration**: Two plugin approaches providing GraphQL type registration and dynamic field resolution:
   - `vmmapicon_yootheme`: Primary integration with TypeProvider
   - `ytvmmapicon`: Alternative implementation approach

### Database Schema
Tables use `#__vmmapicon_` prefix with UTF8MB4 charset. Main table `#__vmmapicon_apis` stores API configurations including URL, method, parameters, headers, and field mappings as JSON.

### API Helper Pattern
Core API functionality centers around `ApiHelper::getApiResult()` which handles:
- HTTP request execution with configurable methods
- Parameter substitution in URLs
- Header management for authentication
- Response parsing (JSON, XML, CSV)
- Error handling with sample data fallback

### Frontend Assets
Uses Joomla 4+ web assets system with dependencies managed through `joomla.asset.json`. The JSON mapping interface provides real-time updates and field type detection.

## Code Conventions

- Follow Joomla 4+ coding standards with PSR-4 autoloading
- Use namespaced classes under `Villaester\Component\Vmmapicon`
- XML form definitions in `forms/` directory
- Template files in `tmpl/` directories follow Joomla naming conventions
- JavaScript uses ES6+ features with event-driven architecture
- Database queries use Joomla's query builder for security
