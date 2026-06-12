/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_learndash_pro_quiz_lock` (
  `quiz_id` int(11) NOT NULL,
  `lock_ip` varchar(100) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `lock_type` tinyint(3) unsigned NOT NULL,
  `lock_date` int(11) NOT NULL,
  PRIMARY KEY (`quiz_id`,`lock_ip`,`user_id`,`lock_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_learndash_pro_quiz_lock` (`quiz_id`, `lock_ip`, `user_id`, `lock_type`, `lock_date`) VALUES (55,'157.51.135.81',0,2,1769185287);
