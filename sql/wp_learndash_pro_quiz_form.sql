/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_learndash_pro_quiz_form` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `fieldname` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `required` tinyint(1) unsigned NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `data` mediumtext DEFAULT NULL,
  PRIMARY KEY (`form_id`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
