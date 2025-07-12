--
-- Table structure for API configurations
--
CREATE TABLE IF NOT EXISTS `#__vmmapicon_apis`
(
    `id`          int(11) unsigned                        NOT NULL AUTO_INCREMENT,
    `title`   varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
    `api_url`     text COLLATE utf8mb4_unicode_ci         NOT NULL,
    `api_method`  varchar(10) COLLATE utf8mb4_unicode_ci  NOT NULL DEFAULT 'GET',
    `api_params`  json                                    NULL,
    `published`   tinyint(4)                              NOT NULL DEFAULT '1',
    `created`     datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_by`  int(11)                                 NOT NULL DEFAULT '0',
    `modified`    datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `modified_by` int(11)                                 NOT NULL DEFAULT '0',
    `checked_out` int(11)                                 NOT NULL DEFAULT '0',
    `checked_out_time` datetime                           NULL DEFAULT NULL,
    `ordering`    int(11)                                 NOT NULL DEFAULT '0',
    `access`      int(11)                                 NOT NULL DEFAULT '1',

    PRIMARY KEY (`id`),
    KEY `idx_published` (`published`),
    KEY `idx_access` (`access`),
    KEY `idx_checkout` (`checked_out`),
    KEY `idx_created_by` (`created_by`),
    KEY `idx_api_method` (`api_method`),
    KEY `idx_ordering` (`ordering`)
    ) ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    COLLATE = utf8mb4_unicode_ci;

--
-- Insert sample data (optional)
--
INSERT INTO `#__vmmapicon_apis` (`api_title`, `api_url`, `api_method`, `api_params`, `published`, `created`, `created_by`) VALUES
('Sample GET API', 'https://jsonplaceholder.typicode.com/posts', 'GET', '[]', 1, NOW(), 0),
('Sample POST API', 'https://httpbin.org/post', 'POST', '[{"key":"Content-Type","value":"application/json","position":"head"}]', 1, NOW(), 0);
