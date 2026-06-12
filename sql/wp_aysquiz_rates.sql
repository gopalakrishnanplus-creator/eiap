/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_aysquiz_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(256) NOT NULL,
  `user_name` text DEFAULT NULL,
  `user_email` text DEFAULT NULL,
  `user_phone` text DEFAULT NULL,
  `rate_date` datetime NOT NULL,
  `score` varchar(256) NOT NULL,
  `review` text NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
