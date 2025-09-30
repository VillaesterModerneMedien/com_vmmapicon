--
-- Table structure for API configurations
--
CREATE TABLE `#__vmmapicon_apis` (
                                        `id` int(11) UNSIGNED NOT NULL,
                                        `title` varchar(255) NOT NULL,
                                        `api_url` text DEFAULT NULL,
                                        `api_method` varchar(10) NOT NULL DEFAULT 'GET',
                                        `api_params` longtext NOT NULL DEFAULT '',
                                        `published` tinyint(4) NOT NULL DEFAULT 1,
                                        `created` datetime NOT NULL DEFAULT current_timestamp(),
                                        `created_by` int(11) NOT NULL DEFAULT 0,
                                        `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                        `modified_by` int(11) NOT NULL DEFAULT 0,
                                        `access` int(11) NOT NULL DEFAULT 1,
                                        `api_type` varchar(20) NOT NULL DEFAULT 'JSON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Insert sample data (optional)
--
INSERT INTO `#__vmmapicon_apis` (`id`, `title`, `api_url`, `api_method`, `api_params`, `published`, `created`, `created_by`, `modified`, `modified_by`, `access`, `api_type`) VALUES
    (16, 'alle News', 'https://news.ts-holding.net/api/index.php/v1/content/articles', 'GET', '{"api_params0":{"key":"X-Joomla-Token","value":"c2hhMjU2OjU0Mzo3MTBjZWVjNjkyZTMyYjFmZDMwZDFjNDIzNzk2ZGIwNTYzMzY1MGNlNjQ0OTM5ZTM0YWViYWM1NzcyNTM0MWQ5","position":"head"},"api_params1":{"key":"filter[category]","value":"9","position":"url"}}', 1, '2025-08-19 10:38:49', 0, '2025-08-20 12:30:14', 311, 1, 'json');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `i83fg_vmmapicon_apis`
--
ALTER TABLE `#__vmmapicon_apis`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idx_published` (`published`),
  ADD KEY `idx_access` (`access`),
  ADD KEY `idx_created_by` (`created_by`),
  ADD KEY `idx_api_method` (`api_method`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `i83fg_vmmapicon_apis`
--
ALTER TABLE `#__vmmapicon_apis`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;


CREATE TABLE `#__vmmapicon_mapping` (
                                     `id` int(11) UNSIGNED NOT NULL,
                                     `alias` varchar(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

