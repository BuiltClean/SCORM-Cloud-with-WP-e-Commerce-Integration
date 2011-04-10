<?php

require_once('scormcloud.wp.php');
$ScormService = scormcloud_getScormEngineService();

if($_POST['scormcloud_hidden'] == 'Y') {
	$enablewpecommerce = $_POST['scormcloud_wpecommerce'];

	update_site_option('scormcloud_wpecommerce', $enablewpecommerce);

	echo "<div class='updated'><p><strong>". __("Options saved.")."</strong></p></div>";

} else {
	$enablewpecommerce = get_site_option('scormcloud_wpecommerce');
}

?>
<div class="scormcloud-admin-page wpecommerce">

<div class="wrap">
    
	<?php    echo "<h2>" . __( 'SCORM Cloud / WP e-Commerce Settings' ) . "</h2>";  ?>
	
	<form name="scormcloud_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="scormcloud_hidden" value="Y">
        <p><input type="checkbox" name="scormcloud_wpecommerce" <?php echo ($enablewpecommerce ? "checked" : ""); ?> /><?php _e(" Enable WP e-Commerce SCORM Cloud integration." ); ?></p>
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Settings' ) ?>" />
		</p>
	</form>
	
	<p>This portion of the SCORM Cloud plug-in was developed by BuiltClean / eLearning Enhanced.  
	<br />Please consider donating to support this plugin.  </p>
	
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="7T9ZZUYM32WFJ">
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

</div>

</div>
