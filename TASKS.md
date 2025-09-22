# WORKING TASKS

## Instructions:
1. Your task is to make a single API item from the com_vmmapicon component available as a custom source for Yootheme. A single API item from the component contains an API response which can also contain several entries.
Furthermore, a field api_mapping, which maps the field names and values from the API response to your own yootheme field names and field types.
2. a single API item must also be available as a template source for Yootheme. If you create a menu item with a Single API View, it must be possible to use it as a template in Yootheme. The contents of the API result should then be available as dynamic content in Yootheme with the field names mapped to them.

GENERAL IMPORTANT INSTRUCTIONS:
Refer to the official resources that describe the creation of custom sources. Always use Context7 to obtain information and documentation.
You also need to familiarise yourself extensively with Joomla 5 and GraphQL, as they are all interlinked. Always use Sequential Thinking to get the right information. Always proceed step by step and understand the information you receive.
Never create pointless fallbacks and ALWAYS follow the JOOMLA CODING STANDARDS. NEVER CREATE POINTLESS TESTS OR DEBUG FUNCTIONS.
IF THERE ARE ANY UNCERTAINTIES THEN ASK ME BEFORE YOU MAKE ASSUMPTIONS.

## 1. Make a single API Entry available as custom source for Yootheme
**Priority: High**

- Single API as custom source for Yootheme
- Single API source holds a list of API results -> the API items have to be mapped to yootheme fields
- Field api_mapping should map the field names and values from the API to own yootheme field names
- Example API mapping:
  ```json
  {
    "api_field_name": "yootheme_field_name",
    "title": "headline",
    "description": "text"
  }
  ```

## 2. Make a single API Entry available as template source for Yootheme
**Priority: High**

- A single API item must also be available as a template source for Yootheme
- If you create a menu item with a Single API View, it must be possible to use it as a template in Yootheme
- The contents of the API result should then be available as dynamic content in Yootheme with the field names mapped to them
- Example usage in Yootheme:
  - Create a menu item of type "Single API View"
  - Select this menu item as data source in Yootheme
  - The mapped fields are now available as dynamic content

## 3. Technical Requirements and Constraints
**Priority: Medium**

- Use Joomla 5 MVC structure
- Integration with Joomla's GraphQL API
- Adherence to Yootheme development guidelines for custom sources
- Support for multilingual content (de-DE, en-GB)

## 4. Verification and Completion Criteria
**Priority: High**

A task is considered complete when:
- [ ] API data is correctly provided as Yootheme source
- [ ] API mapping works correctly and maps all relevant fields
- [ ] Template integration in Yootheme works without errors
- [ ] Code complies with Joomla Coding Standards
- [ ] No unnecessary fallbacks or debug functions are implemented

## Coding Style & Naming Conventions
- PHP: PSR‑12, 4‑space indent. Joomla namespaces (`Villaester\Component\Vmmapicon\…`). Keep files free of side effects; honor existing `phpcs` annotations.
- JS: Modern ES, 2‑space indent; keep small modules under `media/.../js`.
- Language keys: UPPER_SNAKE_CASE (e.g., `COM_VMMAPICON_TITLE`). Files: `en-GB.*.ini`, `de-DE.*.ini`.
- Naming: Joomla conventions for extensions (`com_vmmapicon`, `vmmapiconyt`).
