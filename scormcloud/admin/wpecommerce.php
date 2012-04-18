<?php
echo '<div class="scormcloud-admin-page wpecommerce">';
echo "<h2>" . __( 'SCORM Cloud / WP e-Commerce Settings' ) . "</h2>"; 
if($_POST['scormcloud_hidden'] == 'Y') {
	$enablewpecommerce = $_POST['scormcloud_wpecommerce'];

	update_option('scormcloud_wpecommerce', $enablewpecommerce);

	echo "<div class='updated'><p><strong>". __("Options saved.")."</strong></p></div>";

} else {
	$enablewpecommerce = get_option('scormcloud_wpecommerce');
}

?>
<div class="scormcloud-admin-page wpecommerce">

<div class="wrap">
	<form name="scormcloud_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="scormcloud_hidden" value="Y">
        <p><input type="checkbox" name="scormcloud_wpecommerce" <?php echo ($enablewpecommerce ? "checked" : ""); ?> /><?php _e(" Enable WP e-Commerce SCORM Cloud integration." ); ?></p>
		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Update Settings' ) ?>" />
		</p>
	</form>
	
	<p>This portion of the SCORM Cloud plug-in was developed by BuiltClean (http://www.builtclean.com).</p> 
</div>

</div>