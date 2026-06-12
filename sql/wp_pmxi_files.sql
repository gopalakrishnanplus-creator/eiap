/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_pmxi_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `import_id` bigint(20) unsigned NOT NULL,
  `name` text DEFAULT NULL,
  `path` text DEFAULT NULL,
  `registered_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_pmxi_files` (`id`, `import_id`, `name`, `path`, `registered_on`) VALUES (1,1,'Quizzes-Export-2025-March-03-1236.csv','/srv/htdocs/wp-content/uploads/wpallexport/exports/ca55162e85d2f1550ca453947e6b3522/Quizzes-Export-2025-March-03-1236.xml','2025-03-03 12:36:47');
INSERT INTO `wp_pmxi_files` (`id`, `import_id`, `name`, `path`, `registered_on`) VALUES (3,2,'Import_it_Quizzes_Export_2025_March_03_1236.csv','/wpallimport/uploads/d24e0bae15049fe5c65d6e03476d7b68/Import_it_Quizzes_Export_2025_March_03_1236.xml','2025-03-03 12:40:21');
