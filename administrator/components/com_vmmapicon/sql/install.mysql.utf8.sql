--
-- Table structure for API configurations
--
CREATE TABLE `#__vmmapicon_apis` (
                                        `id` int(11) UNSIGNED NOT NULL,
                                        `title` varchar(255) NOT NULL,
                                        `api_url` text DEFAULT NULL,
                                        `api_method` varchar(10) NOT NULL DEFAULT 'GET',
                                        `api_params` longtext NOT NULL DEFAULT '\'\\\'\\\'\'',
                                        `published` tinyint(4) NOT NULL DEFAULT 1,
                                        `created` datetime NOT NULL DEFAULT current_timestamp(),
                                        `created_by` int(11) NOT NULL DEFAULT 0,
                                        `modified` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                                        `modified_by` int(11) NOT NULL DEFAULT 0,
                                        `access` int(11) NOT NULL DEFAULT 1,
                                        `api_mapping` longtext DEFAULT NULL,
                                        `api_type` varchar(20) NOT NULL DEFAULT 'JSON',
                                        `api_selectors` longtext DEFAULT NULL,
                                        `api_mapping_subform` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Insert sample data (optional)
--
INSERT INTO `#__vmmapicon_apis` (`id`, `title`, `api_url`, `api_method`, `api_params`, `published`, `created`, `created_by`, `modified`, `modified_by`, `access`, `api_mapping`, `api_type`, `api_selectors`, `api_mapping_subform`) VALUES
    (16, 'alle News', 'https://news.ts-holding.net/api/index.php/v1/content/articles', 'GET', '{\"api_params0\":{\"key\":\"X-Joomla-Token\",\"value\":\"c2hhMjU2OjU0Mzo3MTBjZWVjNjkyZTMyYjFmZDMwZDFjNDIzNzk2ZGIwNTYzMzY1MGNlNjQ0OTM5ZTM0YWViYWM1NzcyNTM0MWQ5\",\"position\":\"head\"},\"api_params1\":{\"key\":\"filter[category]\",\"value\":\"9\",\"position\":\"url\"}}', 1, '2025-08-19 10:38:49', 0, '2025-08-20 12:30:14', 311, 1, '{\"json_mapping0\":{\"json_path\":\"data->attributes->title\",\"yootheme_name\":\"Beitragstitel\",\"field_type\":\"String\",\"field_label\":\"Titel\"},\"json_mapping1\":{\"json_path\":\"data->attributes->bilder\",\"yootheme_name\":\"Customfield Bilder\",\"field_type\":\"Object\",\"field_label\":\"Bilder\"}}', 'json', 'links,links->self,links->next,links->last,data,data->type,data->id,data->attributes,data->attributes->id,data->attributes->asset_id,data->attributes->title,data->attributes->alias,data->attributes->state,data->attributes->access,data->attributes->created,data->attributes->created_by,data->attributes->created_by_alias,data->attributes->modified,data->attributes->featured,data->attributes->language,data->attributes->hits,data->attributes->publish_up,data->attributes->publish_down,data->attributes->note,data->attributes->images,data->attributes->images->image_intro,data->attributes->images->image_intro_alt,data->attributes->images->float_intro,data->attributes->images->image_intro_caption,data->attributes->images->image_fulltext,data->attributes->images->image_fulltext_alt,data->attributes->images->float_fulltext,data->attributes->images->image_fulltext_caption,data->attributes->metakey,data->attributes->metadesc,data->attributes->metadata,data->attributes->metadata->robots,data->attributes->metadata->author,data->attributes->metadata->rights,data->attributes->version,data->attributes->featured_up,data->attributes->featured_down,data->attributes->typeAlias,data->attributes->text,data->attributes->testfeld,data->attributes->bilder,data->attributes->article-field,data->attributes->tags,data->relationships,data->relationships->category,data->relationships->category->data,data->relationships->category->data->type,data->relationships->category->data->id,data->relationships->created_by,data->relationships->created_by->data,data->relationships->created_by->data->type,data->relationships->created_by->data->id,data->relationships->tags,data->relationships->tags->data,meta,meta->total-pages', '');

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
