/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_learndash_pro_quiz_prerequisite` (
  `prerequisite_quiz_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  PRIMARY KEY (`prerequisite_quiz_id`,`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (9,1);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (10,-1);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (11,10);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (12,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (12,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (12,8);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (13,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (13,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (13,8);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (13,12);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (14,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (14,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (14,8);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (15,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (15,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (15,8);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (15,14);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (20,17);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (21,20);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (24,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (24,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (25,24);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (26,8);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (26,17);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (26,18);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (26,19);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,5);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,6);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,18);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,24);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,25);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (27,26);
INSERT INTO `wp_learndash_pro_quiz_prerequisite` (`prerequisite_quiz_id`, `quiz_id`) VALUES (79,75);
