<?php 

function enrollInPurchasedCourses($result) {
	global $wpdb;

	$purchase_id = $result['cart_item']['prodid'];
	
	if(!get_site_option('scormcloud_wpecommerce')) {
		return;
	}

	$product_data = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PRODUCT_LIST."` WHERE `id`='{$purchase_id}' LIMIT 1", ARRAY_A) ;
	
	preg_match_all('/\[scormcloud.training:.*\]/',$product_data['description'],$cloudTagArray);

	$cloudTags = $cloudTagArray[0];
	$responseString = "success";

	foreach($cloudTags as $tagString){
		if($responseString != "success") {
			continue;
		}
		require_once('scormcloud.wp.php');
		$ScormService = scormcloud_getScormEngineService();
		$isValidAccount = $ScormService->isValidAccount();
		
		$inviteId = substr($tagString,21,strlen($tagString) - 22);
		
		$querystr = "SELECT * FROM ".scormcloud_getDBPrefix()."scormcloudinvitations WHERE invite_id = '$inviteId'";
		$invites = $wpdb->get_results($querystr, OBJECT);
		$invite = $invites[0];
				
		if ($isValidAccount && $invite->active == 1){

			$regsRemaining = scormcloud_regsRemaining();
			
			global $current_user;
			global $wpdb;
			get_currentuserinfo();
			
			if($current_user->user_login != '')
			{
				$userId = $current_user->ID;
				$querystr = "SELECT reg_id FROM ".scormcloud_getDBPrefix()."scormcloudinvitationregs WHERE invite_id = '$inviteId' AND user_id = '$userId' ORDER BY update_date DESC";
				$regs = $wpdb->get_results($querystr, OBJECT);
				if (count($regs) == 0){
					if ($regsRemaining > 0){
						
						$courseId = $invite->course_id;
						
						$userData = get_userdata($userId);
						if (!($user_first_name = $current_user->user_firstname) || strlen($user_first_name) < 1){
							$user_first_name = $current_user->display_name;
						}
						if (!($user_last_name = $current_user->user_lastname) || strlen($user_last_name) < 1){
							$user_last_name = $current_user->display_name;
						}
						
						$regid = $inviteId."-".uniqid();
						$regService = $ScormService->getRegistrationService();
						$response = $regService->CreateRegistration($regid, $courseId, $userData->user_email, $user_first_name, $user_last_name);
						
						$xml = simplexml_load_string($response);
						if (isset($xml->success)){   
							$wpdb->query($wpdb->prepare( "
								INSERT INTO ".scormcloud_getDBPrefix()."scormcloudinvitationregs 
									(invite_id, reg_id, user_id, user_email)
									VALUES (%s, %s, %d, %s)", 
										$inviteId, $regid, $userData->ID, $userData->user_email));
							echo "registered $inviteId, $regid, " + $userData->ID + " , " + $userData->user_email;
						} else if ($xml->err['code'] == '4') {
							$responseString = 'There was a problem creating a new training. The maximum number of registrations for this account has been reached.';
						} else {
							$responseString = 'There was a problem creating a new training. '.$xml->err['msg'];
						}
						echo $responseString;
					} 
				}
			}
		}
	}
}
?>