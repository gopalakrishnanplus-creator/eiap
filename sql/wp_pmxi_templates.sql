/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_pmxi_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `options` longtext DEFAULT NULL,
  `scheduled` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(200) NOT NULL DEFAULT '',
  `title` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `is_keep_linebreaks` tinyint(1) NOT NULL DEFAULT 0,
  `is_leave_html` tinyint(1) NOT NULL DEFAULT 0,
  `fix_characters` tinyint(1) NOT NULL DEFAULT 0,
  `meta` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
