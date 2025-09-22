# Repository Guidelines

## Project Structure & Module Organization
- Component (admin): `administrator/components/com_vmmapicon` (MVC, forms, layouts, SQL install/updates).
- Component (site): `components/com_vmmapicon` (controllers, models, views, router/services).
- Plugins: `plugins/system/vmmapiconyt`, `plugins/system/ytdataset`.
- Assets: `media/com_vmmapicon` (JS/CSS, `joomla.asset.json`).
- Languages: `language/en-GB`, `language/de-DE` (+ component/plugin language folders).
- Manifest: `vmmapicon.xml` (install/packaging entry point).

## Testing Environment:

https://joomla5.joomla.local/administrator
Username: whykiki
Password: 421BBdc0ad++
warum is

## Coding Style & Naming Conventions
- PHP: PSR‑12, 4‑space indent. Joomla namespaces (`Villaester\Component\Vmmapicon\…`). Keep files free of side effects; honor existing `phpcs` annotations.
- JS: Modern ES, 2‑space indent; keep small modules under `media/.../js`.
- Language keys: UPPER_SNAKE_CASE (e.g., `COM_VMMAPICON_TITLE`). Files: `en-GB.*.ini`, `de-DE.*.ini`.
- Naming: Joomla conventions for extensions (`com_vmmapicon`, `vmmapiconyt`).
