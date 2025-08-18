--
-- Table structure for API configurations
--
CREATE TABLE IF NOT EXISTS `#__vmmapicon_apis`
(
    `id`          int(11) unsigned                        NOT NULL AUTO_INCREMENT,
    `title`       varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `api_url`     text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `api_method`  varchar(10) COLLATE utf8mb4_unicode_ci  NOT NULL DEFAULT 'GET',
    `api_params`  longtext                                    NULL,
    `api_mapping` longtext                                    NULL,
    `published`   tinyint(4)                              NOT NULL DEFAULT '1',
    `created`     datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`  int(11)                                 NOT NULL DEFAULT '0',
    `modified`    datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` int(11)                                 NOT NULL DEFAULT '0',
    `access`      int(11)                                 NOT NULL DEFAULT '1',

    PRIMARY KEY (`id`),
    KEY `idx_published` (`published`),
    KEY `idx_access` (`access`),
    KEY `idx_created_by` (`created_by`),
    KEY `idx_api_method` (`api_method`)
    ) ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
CREATE TABLE IF NOT EXISTS `#__vmmapicon_apiresults` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `api_id` int(10) unsigned NOT NULL,
    `title` varchar(255) NOT NULL DEFAULT '',
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` text,
    `published` tinyint(1) NOT NULL DEFAULT 1,
    `ordering` int(11) NOT NULL DEFAULT 0,
    `checked_out` int(10) unsigned,
    `checked_out_time` datetime,
    `created` datetime NOT NULL,
    `created_by` int(10) unsigned NOT NULL DEFAULT 0,
    `modified` datetime NOT NULL,
    `modified_by` int(10) unsigned NOT NULL DEFAULT 0,
    `params` text,
     PRIMARY KEY (`id`),
     KEY `idx_api_id` (`api_id`),
     KEY `idx_published` (`published`),
     KEY `idx_checkout` (`checked_out`),
     KEY `idx_created_by` (`created_by`),
     CONSTRAINT `fk_vmmapicon_apiresults_api` FOREIGN KEY (`api_id`) REFERENCES `#__vmmapicon_apis` (`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
