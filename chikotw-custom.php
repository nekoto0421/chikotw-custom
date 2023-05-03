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
        $sql="CREATE TABLE `wp_chiko_discount` (
              `member_plan_id` int(11) NOT NULL,
              `discount` int(11) NOT NULL,
              PRIMARY KEY (`member_plan_id`,`discount`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        dbDelta($sql);
    }
}
 
register_activation_hook( __FILE__, 'CHIKO_activation' );
?>