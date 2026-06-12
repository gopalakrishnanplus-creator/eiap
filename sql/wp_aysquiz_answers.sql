/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_aysquiz_answers` (
  `id` int(150) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` int(11) unsigned NOT NULL,
  `answer` text NOT NULL,
  `image` text DEFAULT NULL,
  `correct` tinyint(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `weight` double DEFAULT 0,
  `placeholder` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (1,1,'300',NULL,0,1,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (2,1,'100',NULL,0,2,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (3,1,'200',NULL,1,3,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (4,2,'30',NULL,1,1,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (5,2,'40',NULL,0,2,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (6,2,'50',NULL,0,3,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (7,3,'60',NULL,0,1,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (8,3,'50',NULL,1,2,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (9,3,'100',NULL,0,3,0,NULL);
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (10,4,'Alen Mask',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (11,4,'Ambani',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (12,4,'Adani',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (13,4,'Bazez',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (14,5,'Rahul Gandhi',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (15,5,'Yogi',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (16,5,'Mr Modi',NULL,1,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (17,5,'Kejriwal',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (18,6,'Rahul Gandhi',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (19,6,'Kejriwal',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (20,6,'Yogi',NULL,1,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (21,6,'Modi Ji',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (22,7,'Naturally acquired active immunity',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (23,7,'Naturally acquired passive immunity',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (24,7,'Artificially acquired active immunity',NULL,1,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (25,7,'Artificially acquired passive immunity',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (26,8,'Preparation of Ice packs.',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (27,8,'Storage of vaccines',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (28,9,'1 Inch',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (29,9,'2 Inch',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (30,9,'3 Inch',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (31,9,'4 Inch',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (32,10,'3 years',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (33,10,'5 years',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (34,10,'7 years',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (35,10,'10 years',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (36,11,'10 m IU/ml',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (37,11,'15 m IU/ml',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (38,11,'20 m IU/ml',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (39,11,'30 m IU/ml',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (40,12,'True',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (41,12,'False',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (42,13,'Yes',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (43,13,'No',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (44,14,'5 months',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (45,14,'6 months',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (46,14,'7 months',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (47,14,'9 months',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (48,15,'28 days',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (49,15,'14 days',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (50,15,'3 months',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (51,15,'6 months',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (52,16,'G2P4',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (53,16,'G1P8',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (54,16,'G9P11',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (55,16,'G3P4',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (56,17,'13 weeks 6 days',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (57,17,'14 weeks 6 days',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (58,17,'15 weeks 6 days',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (59,17,'16 weeks',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (60,18,'1 years',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (61,18,'9 months',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (62,18,'15 months',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (63,18,'2 years',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (64,19,'1 week',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (65,19,'2 week',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (66,19,'3 week',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (67,19,'4 week',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (68,20,'1',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (69,20,'2',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (70,20,'3',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (71,20,'4',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (72,21,'Yes',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (73,21,'No',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (74,22,'Yes',NULL,1,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (75,22,'No',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (76,23,'6 weeks, 10 weeks, 14 weeks',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (77,23,'6 weeks, 10 weeks, 9 months',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (78,23,'6 weeks, 14 weeks, 9 months',NULL,1,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (79,23,'6 weeks, 14 weeks, 18 months',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (80,24,'2 months',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (81,24,'3 months',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (82,24,'6 months',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (83,24,'1 years',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (84,25,'6 month',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (85,25,'7 month',NULL,0,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (86,25,'8 month',NULL,1,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (87,25,'11 month',NULL,0,4,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (88,26,'1 dose',NULL,0,1,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (89,26,'2 dose',NULL,1,2,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (90,26,'3 dose',NULL,0,3,0,'');
INSERT INTO `wp_aysquiz_answers` (`id`, `question_id`, `answer`, `image`, `correct`, `ordering`, `weight`, `placeholder`) VALUES (91,26,'4 dose',NULL,0,4,0,'');
