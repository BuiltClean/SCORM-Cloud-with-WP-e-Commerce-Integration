<?php
require_once('scormcloud.wp.php');
        global $wpdb;
	   	global $scormcloud_db_version;
	
		$scormcloud_db_version = "1.0";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	   	$table_name = $wpdb->prefix . "scormcloudinvitations";
	   	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
		  		invite_id VARCHAR(50) NOT NULL,
                blog_id VARCHAR(50) NOT NULL,
                post_id VARCHAR(50) NOT NULL,
                app_id VARCHAR(50) NOT NULL,
                course_id VARCHAR(50) NOT NULL,
                course_title text NOT NULL,
		  		header tinytext NOT NULL,
		  		description text NOT NULL,
                show_course_info tinyint(2) DEFAULT '1' NOT NULL,
		  		active tinyint(2) DEFAULT '1' NOT NULL,
		  		require_login tinyint(2) DEFAULT '0' NOT NULL,
                create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
				);";
 
	      dbDelta($sql);
	
			add_option("scormcloud_db_version", $scormcloud_db_version);
		}
        
        $table_name = $wpdb->prefix . "scormcloudinvitationregs";
	   	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name . " (
		  		invite_id VARCHAR(50) NOT NULL,
                reg_id VARCHAR(50) NOT NULL,
                user_id bigint(20) unsigned NULL,
                user_email VARCHAR(50) NULL,
                update_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
		  		);";
 
	      dbDelta($sql);
		}
        if (function_exists('is_multisite') && is_multisite() && is_plugin_active_for_network('scormcloud/scormcloud.php')){
            $installed_ver = get_site_option( "scormcloud_db_version" );
            
        } else {
            $installed_ver = get_option( "scormcloud_db_version" );
        }

        if( $installed_ver != $scormcloud_db_version ) {

          //update the tables here...
            if (function_exists('is_multisite') && is_multisite() && is_plugin_active_for_network('scormcloud/scormcloud.php')){
                update_site_option( "scormcloud_db_version", $scormcloud_db_version );
                update_site_option( "scormcloud_dbprefix", $wpdb->prefix);
                update_site_option( "scormcloud_networkManaged", 'true');
            } else {
                update_option( "scormcloud_db_version", $scormcloud_db_version );
            }
       }
?>