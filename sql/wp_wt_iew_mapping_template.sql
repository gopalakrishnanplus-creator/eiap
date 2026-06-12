/*M!999999\- enable the sandbox mode */ 
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_wt_iew_mapping_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_type` varchar(255) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `wp_wt_iew_mapping_template` (`id`, `template_type`, `item_type`, `name`, `data`) VALUES (1,'export','user','export','{\"post_type_form_data\":{\"item_type\":\"user\"},\"method_export_form_data\":{\"method_export\":\"new\",\"mapping_enabled_fields\":[],\"selected_template\":\"0\"},\"filter_form_data\":{\"wt_iew_limit\":0,\"wt_iew_offset\":0,\"wt_iew_roles\":[\"administrator\",\"editor\",\"author\",\"contributor\",\"group_leader\"],\"wt_iew_date_from\":\"\",\"wt_iew_date_to\":\"\",\"wt_iew_order_by\":\"ASC\"},\"mapping_form_data\":{\"mapping_fields\":{\"ID\":[\"ID\",1],\"roles\":[\"roles\",1],\"customer_id\":[\"customer_id\",1],\"user_login\":[\"user_login\",1],\"user_pass\":[\"user_pass\",0],\"user_nicename\":[\"user_nicename\",0],\"user_email\":[\"user_email\",0],\"user_url\":[\"user_url\",0],\"user_registered\":[\"user_registered\",1],\"display_name\":[\"display_name\",1],\"first_name\":[\"first_name\",1],\"last_name\":[\"last_name\",1],\"user_status\":[\"user_status\",0],\"nickname\":[\"nickname\",0],\"description\":[\"description\",0],\"rich_editing\":[\"rich_editing\",0],\"syntax_highlighting\":[\"syntax_highlighting\",0],\"admin_color\":[\"admin_color\",0],\"use_ssl\":[\"use_ssl\",0],\"show_admin_bar_front\":[\"show_admin_bar_front\",0],\"locale\":[\"locale\",0],\"wp_user_level\":[\"wp_user_level\",0],\"dismissed_wp_pointers\":[\"dismissed_wp_pointers\",0],\"show_welcome_panel\":[\"show_welcome_panel\",0],\"session_tokens\":[\"session_tokens\",0],\"last_update\":[\"last_update\",0],\"is_geuest_user\":[\"is_geuest_user\",0]},\"mapping_enabled_fields\":[],\"mapping_selected_fields\":{\"ID\":\"ID\",\"roles\":\"roles\",\"customer_id\":\"customer_id\",\"user_login\":\"user_login\",\"user_registered\":\"user_registered\",\"display_name\":\"display_name\",\"first_name\":\"first_name\",\"last_name\":\"last_name\"}},\"meta_step_form_data\":{\"mapping_fields\":[],\"mapping_selected_fields\":[]},\"advanced_form_data\":{\"wt_iew_batch_count\":30,\"wt_iew_delimiter_preset\":\"comma\",\"wt_iew_delimiter\":\",\"}}');
