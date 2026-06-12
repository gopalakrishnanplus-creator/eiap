/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_aysquiz_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_key` text DEFAULT NULL,
  `meta_value` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `options` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_aysquiz_settings` (`id`, `meta_key`, `meta_value`, `note`, `options`) VALUES (1,'buttons_texts','','','');
INSERT INTO `wp_aysquiz_settings` (`id`, `meta_key`, `meta_value`, `note`, `options`) VALUES (2,'fields_placeholders','','','');
INSERT INTO `wp_aysquiz_settings` (`id`, `meta_key`, `meta_value`, `note`, `options`) VALUES (3,'options','','','');
