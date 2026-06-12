/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_pmxi_hash` (
  `hash` binary(16) NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `import_id` smallint(5) unsigned NOT NULL,
  `post_type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
