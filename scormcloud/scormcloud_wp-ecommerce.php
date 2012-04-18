<?php 

require_once(SCORMCLOUD_BASE.'scormcloudplugin.php');
require_once(SCORMCLOUD_BASE.'db/scormclouddatabase.php');

function enrollInPurchasedCourses($result) {
	global $wpdb;

	$purchase_id = $result['cart_item']['prodid'];
	
	if(!get_site_option('scormcloud_wpecommerce')) {
		return;
	}

	$product_data = $wpdb->get_row("SELECT * FROM `".WPSC_TABLE_PRODUCT_LIST."` WHERE `id`='{$purchase_id}' LIMIT 1", ARRAY_A) ;
	
		preg_match_all('/\[scormcloud.training:.*\]/',$product_data['description'],$cloudTagArray);
	
		$cloudTags = $cloudTagArray[0];
	
		foreach($cloudTags as $tagString) {
			$ScormService = ScormCloudPlugin::get_cloud_service();
			try {
				$isValidAccount = $ScormService->isValidAccount();
			} catch (Exception $e) {
				$isValidAccount = false;
			}
	
			$inviteId = substr($tagString,21,strlen($tagString) - 22);
	
			$invite = ScormCloudDatabase::get_invitation($inviteId);
			if ($invite == null) {
				$content = str_replace($tagString,'',$content);
			}
				
		if ($isValidAccount && $invite->active == 1){
	
					$regsRemaining = ScormCloudPlugin::remaining_registrations();
	
					global $current_user;
					global $wpdb;
					get_currentuserinfo();
	
			if(isset($current_user->user_login) && $current_user->user_login != '')
			{
						$userId = $current_user->ID;
						$query = $wpdb->prepare('SELECT reg_id FROM '.ScormCloudDatabase::get_registrations_table().' WHERE invite_id = %s AND
												 user_id = %s ORDER BY update_date DESC', array($inviteId, $userId));
						$reg = $wpdb->get_row($query, OBJECT);
						if ($reg == null){
					$responseString = 'success';
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
						$regResultsXmlStr = $regService->GetRegistrationResult($regId,0,0);
						$resXml = simplexml_load_string($regResultsXmlStr);
						if (isset($xml->success)){
							$wpdb->insert(ScormCloudDatabase::get_registrations_table(),
								array('invite_id' => $inviteId,
									'reg_id' => $regid,
									'user_id' => $userData->ID,
									'user_email' => $userData->user_email),
								array('%s', '%s', '%d', '%s'));
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