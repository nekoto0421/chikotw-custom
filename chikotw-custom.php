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
}
 
register_activation_hook( __FILE__, 'CHIKO_activation' );
?>