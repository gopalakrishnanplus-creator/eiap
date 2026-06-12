/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_pmxi_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `import_id` bigint(20) unsigned NOT NULL,
  `type` enum('manual','processing','trigger','continue','cli','') NOT NULL DEFAULT '',
  `time_run` text DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `summary` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_pmxi_history` (`id`, `import_id`, `type`, `time_run`, `date`, `summary`) VALUES (1,2,'manual','22','2025-03-03 12:40:21','0 Quizzes created 62 updated 0 skipped');
