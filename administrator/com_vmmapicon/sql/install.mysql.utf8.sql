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
