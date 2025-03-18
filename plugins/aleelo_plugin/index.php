<?php

defined('PLUGINPATH') or exit('No direct script access allowed');

/*
  Plugin Name: aleelo plugin
  Description: It's a aleelo plugin plugin.
  Version: 1.0
  Requires at least: 3.0
  Author: aleelo
  Author URL: https://author_url.demo
 */

//add menu item to left menu
app_hooks()->add_filter('app_filter_staff_left_menu', function ($sidebar_menu) {
   
    $sidebar_menu["items"] = array("name" => "items", "url" => "", "class" => "layers","position" => 5,);
            $sidebar_menu["items_list"] = array("name" => "items_list", "url" => "items_list", "class" => "layers","position" => 6,);
            $sidebar_menu["assigning_items"] = array("name" => "assigning_items", "url" => "assigning_items", "class" => "layers","position" => 7,);
            $sidebar_menu["expense"] = array("name" => "expense", "url" => "expense", "class" => "arrow-right-circle","position" => 8,);
            $sidebar_menu["project"] = array("name" => "project", "url" => "project/all_projects", "class" => "command","position" => 9,);
            $sidebar_menu["staff"] = array("name" => "staff", "url" => "team_member", "class" => "users","position" => 10,);
            $sidebar_menu["client"] = array("name" => "client", "url" => "client", "class" => "briefcase","position" => 11,);
            $sidebar_menu["Screen_size"] = array("name" => "Screen_size", "url" => "Screen_size", "class" => "layers","position" => 12,);
    return $sidebar_menu;
});
$routes = service('routes');

 $routes->group('', ['namespace' => 'aleelo_plugin\Controllers'], function($routes) {
    $routes->get('team_member', 'Team_member::index');
    $routes->get('team_member/(:any)', 'Team_member::$1');
    $routes->post('team_member/(:any)', 'Team_member::$1');

    $routes->get('team_members', 'Team_member::index');
    $routes->get('team_members/(:any)', 'Team_member::$1');
    $routes->post('team_members/(:any)', 'Team_member::$1');

    $routes->get('clients', 'Client::index');
    $routes->get('clients/(:any)', 'Client::$1');
    $routes->post('clients/(:any)', 'Client::$1');

    $routes->get('clientss', 'Client::index');
    $routes->get('clientss/(:any)', 'Client::$1');
    $routes->post('clientss/(:any)', 'Client::$1');

    $routes->get('expenses', 'expense::index');
    $routes->get('expenses/(:any)', 'expense::$1');
    $routes->post('expenses/(:any)', 'expense::$1');

    $routes->get('projects', 'Project::index');
    $routes->get('projects/(:any)', 'Project::$1');
    $routes->post('projects/(:any)', 'Project::$1');

    $routes->get('signin', 'Signinn::index');
    $routes->get('signin/(:any)', 'Signinn::$1');
    $routes->post('signin/(:any)', 'Signinn::$1');
    
    $routes->get('tasks', 'Tasks::index');
    $routes->get('tasks/(:any)', 'Tasks::$1');
    $routes->post('tasks/(:any)', 'Tasks::$1');

      $routes->get('estimates', 'estimates::index');
    $routes->get('estimates/(:any)', 'estimates::$1');
    $routes->post('estimates/(:any)', 'estimates::$1');

    $routes->get('invoice_payments', 'Invoice_payments::index');
    $routes->get('invoice_payments/(:any)', 'Invoice_payments::$1');
    $routes->post('invoice_payments/(:any)', 'Invoice_payments::$1');

    $routes->get('invoice_payments', 'invoices::index');
    $routes->get('invoice_payments/(:any)', 'invoices::$1');
    $routes->post('invoice_payments/(:any)', 'invoices::$1');

    $routes->get('invoices', 'invoices::index');
    $routes->get('invoices/(:any)', 'invoices::$1');
    $routes->post('invoices/(:any)', 'invoices::$1');
    
 });
//add admin setting menu item
app_hooks()->add_filter('app_filter_admin_settings_menu', function ($settings_menu) {
    $settings_menu["plugins"][] = array("name" => "demo", "url" => "demo_settings");
    return $settings_menu;
});

//install dependencies
register_installation_hook("aleelo_plugin", function ($item_purchase_code) {
    /*
     * you can verify the item puchase code from here if you want. 
     * you'll get the inputted puchase code with $item_purchase_code variable
     * use exit(); here if there is anything doesn't meet it's requirements
     */

    $this_is_required = true;
    if (!$this_is_required) {
        echo json_encode(array("success" => false, "message" => "This is required!"));
        exit();
    }

    //run installation sql
    $db = db_connect('default');
    $dbprefix = get_db_prefix();

    $sql_query = "CREATE TABLE IF NOT EXISTS `" . $dbprefix . "demo_settings` (
        `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
        `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app',
        `deleted` tinyint(1) NOT NULL DEFAULT '0',
        UNIQUE KEY `setting_name` (`setting_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->query($sql_query);

   

    $sql_query = "CREATE TABLE IF NOT EXISTS `" . $dbprefix . "assigning_items` (
       `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `screen_size_id` int(11) NOT NULL,
  `quantity` varchar(200) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` date DEFAULT NULL,
  `labels` varchar(200) NOT NULL,
  `is_lead` int(11) NOT NULL,
  `starred_by` int(11) NOT NULL,
  `lead_status_id` int(11) NOT NULL,
  `deleted` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
    $db->query($sql_query);
    

    $sql_query = "CREATE TABLE IF NOT EXISTS `" . $dbprefix . "screen_size` (
     `id` int(11) NOT NULL  AUTO_INCREMENT,
  `screen_size` text NOT NULL,
  `deleted` int(11) NOT NULL,
         PRIMARY KEY (`id`)
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
     $db->query($sql_query);

     $sql_query = "CREATE TABLE IF NOT EXISTS `" . $dbprefix . "items_list` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
       `item_name` varchar(200) NOT NULL,
       `description` text NOT NULL,
       `quantity` varchar(200) NOT NULL,
       `model` varchar(200) NOT NULL,
       `created_by` int(11) NOT NULL,
       `created_at` date DEFAULT NULL,
       `labels` text NOT NULL,
       `is_lead` int(11) NOT NULL,
       `starred_by` int(11) NOT NULL,
       `lead_status_id` int(11) NOT NULL,
       `deleted` int(11) NOT NULL,
                 PRIMARY KEY (`id`)
                 
                 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
             $db->query($sql_query);
     
             $sql_query = "ALTER TABLE `" . $dbprefix . "expenses` 
             ADD COLUMN `company_id` int(11) NULL,
                     ADD COLUMN `created_by` int(11) NULL,
                     ADD COLUMN  `status` enum('unpaid','paid','rejected') NOT NULL DEFAULT 'unpaid',
                     ADD COLUMN `created_at` date DEFAULT NULL;";
     
         $db->query($sql_query);
         $sql_query = "ALTER TABLE `" . $dbprefix . "clients` 
         ADD COLUMN `company_id` int(11) NULL;";
     $db->query($sql_query);
     $sql_query = "ALTER TABLE `" . $dbprefix . "team_member_job_info` 
     ADD COLUMN `company_id` int(11) NULL;";
     $db->query($sql_query);
         $sql_query = "ALTER TABLE `" . $dbprefix . "projects` 
             ADD COLUMN `company_id` int(11) NULL, 
             ADD COLUMN `supervisor_id` int(11) NOT NULL,
             ADD COLUMN `location` text NOT NULL,
             ADD COLUMN `project_date` date DEFAULT NULL,
             ADD COLUMN `screen_size_id` int(11) NOT NULL;";
         $db->query($sql_query);
         
         $sql_query = "ALTER TABLE `" . $dbprefix . "users` 
             ADD COLUMN `uuid` varchar(255) DEFAULT '',
             ADD COLUMN `login_type` enum('normal_login','azure_login') NOT NULL DEFAULT 'azure_login',
             ADD COLUMN `private_email` varchar(200) DEFAULT NULL;";
         $db->query($sql_query);
          
     });
     
     //add setting link to the plugin setting
     app_hooks()->add_filter('app_filter_action_links_of_Demo', function () {
         $action_links_array = array(
             anchor(get_uri("demo"), "Demo"),
             anchor(get_uri("demo_settings"), "Demo settings"),
         );
     
         return $action_links_array;
     });
     
     //update plugin
     register_update_hook("aleelo_plugin", function () {
         echo "Please follow this instructions to update:";
         echo "<br />";
         echo "Your logic to update...";
     });
     
     //uninstallation: remove data from database
     register_uninstallation_hook("aleelo_plugin", function () {
         $dbprefix = get_db_prefix();
         $db = db_connect('default');
     
         $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "demo_settings`;";
         $db->query($sql_query);
         $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "assigning_items`;";
         $db->query($sql_query);
         $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "screen_size`;";
         $db->query($sql_query);
             $sql_query = "DROP TABLE IF EXISTS `" . $dbprefix . "items_list`;";
             $db->query($sql_query);
            
             $sql_query = "ALTER TABLE `" . $dbprefix . "clients` DROP COLUMN `company_id`;";
             $db->query($sql_query);
             $sql_query = "ALTER TABLE `" . $dbprefix . "team_member_job_info` DROP COLUMN `company_id`;";
             $db->query($sql_query);
             $sql_query = "ALTER TABLE `" . $dbprefix . "projects` DROP COLUMN `company_id`, DROP COLUMN `supervisor_id`, DROP COLUMN `location`, DROP COLUMN `project_date`, DROP COLUMN `screen_size_id`;";
             $db->query($sql_query);
             $sql_query = "ALTER TABLE `" . $dbprefix . "users` DROP COLUMN `uuid`, DROP COLUMN `login_type`, DROP COLUMN `private_email`;";
             $db->query($sql_query);
             $sql_query = "ALTER TABLE `" . $dbprefix . "expenses` DROP COLUMN `company_id`, DROP COLUMN `created_by`, DROP COLUMN `status`, DROP COLUMN `created_at`;";
             $db->query($sql_query);
             
     
     });