/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  `term_order` int(4) DEFAULT 0,
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1400 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1,'Uncategorized','uncategorized',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1356,'Blogroll','blogroll',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1357,'twentytwentythree','twentytwentythree',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1359,'Academic Pearls','academic-pearls',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1360,'post-format-video','post-format-video',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1361,'Pg Teaching','pg-teaching',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1362,'Intensive Care Teaching','intensive-care-teaching',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1363,'Lectures in Pediatrics','lectures-in-pediatrics',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1364,'National Calendar','national-calendar',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1365,'Regional Calender','regional-calender',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1366,'Services','services',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1367,'Academic Pearls Test','academic-pearls-test',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1368,'New Cat','new-cat',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1369,'new post cat','new-post-cat',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1371,'Academic Pearls','academic-pearls',0,5);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1372,'Intensive Care Teaching','intensive-care-teaching',0,6);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1373,'Expert lectures','expert-lectures',0,3);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1374,'PG Teaching','pg-teaching',0,7);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1379,'Webinar Archives','iap-webinars-and-clinics',0,4);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1380,'Parent Education Videos','parent-education-videos',0,9);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1382,'IAP Courses','iap-courses',0,2);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1383,'IAP Webinar Calendar','iap-webinar-calendar',0,1);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1393,'Academic Pearls 2023','academic-pearls-2023',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1394,'tec','tec',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1395,'twentytwentytwo','twentytwentytwo',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1398,'Menu 1','menu-1',0,0);
INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`, `term_order`) VALUES (1399,'Articles &amp; Mini CME','articles-mini-cme',0,0);
