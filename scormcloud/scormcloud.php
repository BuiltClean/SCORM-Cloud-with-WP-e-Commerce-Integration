<?php 
	/*
	Plugin Name: SCORM Cloud with WP e-Commerce Integration
	Plugin URI: http://elearningenhanced.com/products/scorm-cloud-wp-e-commerce-integration
	Description: This plug-in takes the existing SCORM Cloud plug-in and adds WP e-Commerce integration.
	Author: BuiltClean
	Version: 1.1.6
	Author URI: http://www.builtclean.com
	*/
	
define('SCORMCLOUD_BASE', WP_PLUGIN_DIR.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__)));

require_once('scormcloudplugin.php');
require_once('scormcloudui.php');
require_once('SCORMCloud_PHPLibrary/DebugLogger.php');

load_plugin_textdomain('scormcloud', false, dirname(plugin_basename( __FILE__ )).'/langs/');

register_activation_hook(__FILE__, array('ScormCloudPlugin', 'activate'));
register_deactivation_hook(__FILE__, array('ScormCloudPlugin', 'deactivate'));
register_uninstall_hook(__FILE__, array('ScormCloudPlugin', 'uninstall'));

add_action('plugins_loaded', array('ScormCloudPlugin', 'update_check'));
add_action('init', array('ScormCloudPlugin', 'initialize'));
add_action('init', array('ScormCloudUi', 'initialize'));
add_action('widgets_init', array('ScormCloudUi', 'initialize_widgets'));

	function scormcloud_enrollInPurchasedCourses($results) {
		require_once('scormcloud_wp-ecommerce.php');
		
		return enrollInPurchasedCourses($results);
	}
	add_action('wpsc_transaction_result_cart_item', 'scormcloud_enrollInPurchasedCourses');

	function scormcloud_addWPECommerceHook() {
		require_once('scormcloud_wp-ecommerce.php');
	}
  

