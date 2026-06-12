/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_aysquiz_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `border_radius` varchar(255) NOT NULL,
  `show_result_presentage` int(11) NOT NULL,
  `show_result_answers` int(11) NOT NULL,
  `buttons_color` varchar(255) NOT NULL,
  `buttons_bg_color` varchar(255) NOT NULL,
  `buttons_hover_color` varchar(255) NOT NULL,
  `buttons_hover_bg_color` varchar(255) NOT NULL,
  `quiz_title_color` varchar(255) NOT NULL,
  `quiz_description_color` varchar(255) NOT NULL,
  `question_color` varchar(255) NOT NULL,
  `question_bg_color` varchar(255) NOT NULL,
  `question_answer_color` varchar(255) NOT NULL,
  `question_answer_bg_color` varchar(255) NOT NULL,
  `question_answer_hover_color` varchar(255) NOT NULL,
  `question_answer_hover_bg_color` varchar(255) NOT NULL,
  `question_correct_answer_bg_color` varchar(255) NOT NULL,
  `question_incorrect_answer_bg_color` varchar(255) NOT NULL,
  `pagination_bg_color` varchar(255) NOT NULL,
  `pagination_color` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_aysquiz_themes` (`id`, `title`, `border_radius`, `show_result_presentage`, `show_result_answers`, `buttons_color`, `buttons_bg_color`, `buttons_hover_color`, `buttons_hover_bg_color`, `quiz_title_color`, `quiz_description_color`, `question_color`, `question_bg_color`, `question_answer_color`, `question_answer_bg_color`, `question_answer_hover_color`, `question_answer_hover_bg_color`, `question_correct_answer_bg_color`, `question_incorrect_answer_bg_color`, `pagination_bg_color`, `pagination_color`) VALUES (1,'Default','4',1,1,'#ffffff','#70b1f2','#ffffff','#4797e7','#000000','#000000','#ffffff','#70b1f2','#7a7575','#efefef','#7a7575','#d6d2c9','#4fed24','#ed3324','#efefef','#70b1f2');
