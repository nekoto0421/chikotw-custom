<?php
/*
 * Plugin Name: Chiko客製功能
 * Description: Chiko客製功能
 * Author: nekoto
 * Plugin URI:https://nekoto-technique.com/
 * Version:1.0
 */
if(!defined('CHIKO_DIR')){
    define('CHIKO_DIR', dirname(__FILE__));
}

define('CHIKO_URL', plugin_dir_url(__FILE__));

function LoadCHIKO_Class(){
    include CHIKO_DIR.'/includes/class_chiko-main.php';
    $GLOBALS['CHIKOMain']=CHIKOMain();
}

function CHIKOMain(){
    return chiko_Main::instance();
}

add_action('plugins_loaded', 'LoadCHIKO_Class');

function CHIKO_activation() {
    if(!defined('ABSPATH'))exit;
    global $wpdb;
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    $table_name=$wpdb->prefix.'chiko_discount';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name){
        $sql="CREATE TABLE `{$wpdb->prefix}chiko_discount` (
              `member_plan_id` int(11) NOT NULL,
              `discount` int(11) NOT NULL,
              PRIMARY KEY (`member_plan_id`,`discount`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        dbDelta($sql);
    }

    $table_name=$wpdb->prefix.'qa_content';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name){
        $sql="CREATE TABLE `{$wpdb->prefix}qa_content` (
          `qa_id` int(11) NOT NULL,
          `content` text NOT NULL,
          PRIMARY KEY (`member_plan_id`,`discount`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        dbDelta($sql);
    }

    $table_name=$wpdb->prefix.'qa_title';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name){
        $sql="CREATE TABLE `{$wpdb->prefix}qa_title` (
              `ID` int(11) NOT NULL AUTO_INCREMENT,
              `parent_id` int(11) DEFAULT NULL,
              `content` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        dbDelta($sql);
    }
}
 
register_activation_hook( __FILE__, 'CHIKO_activation' );
?>