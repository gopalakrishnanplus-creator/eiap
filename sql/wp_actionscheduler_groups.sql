/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_actionscheduler_groups` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_actionscheduler_groups` (`group_id`, `slug`) VALUES (1,'action-scheduler-migration');
INSERT INTO `wp_actionscheduler_groups` (`group_id`, `slug`) VALUES (2,'shepherd_tec_queue_default');
INSERT INTO `wp_actionscheduler_groups` (`group_id`, `slug`) VALUES (3,'ActionScheduler');
