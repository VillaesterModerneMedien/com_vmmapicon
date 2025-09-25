-- Remove mapping-related columns from #__vmmapicon_apis
ALTER TABLE `#__vmmapicon_apis`
  DROP COLUMN IF EXISTS `api_mapping`;

ALTER TABLE `#__vmmapicon_apis`
  DROP COLUMN IF EXISTS `api_selectors`;

ALTER TABLE `#__vmmapicon_apis`
  DROP COLUMN IF EXISTS `api_mapping_subform`;
