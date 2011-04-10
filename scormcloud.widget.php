<?php
	
	class ScormCloudRegistrationsWidget extends WP_Widget
	{
		/**
		* Declares the UserRegistrationsWidget class.
		*
		*/
		function ScormCloudRegistrationsWidget(){
			$widget_ops = array('classname' => 'widget_userreg_widget', 'description' => __( "Widget for displaying SCORM registrations to users.") );
			$control_ops = array('width' => 200, 'height' => 300);
			$this->WP_Widget('scormcloudregistrations', __('Scorm Cloud User Training Widget'), $widget_ops, $control_ops);
		}

		/**
		* Displays the Widget
		*
		*/
		function widget($args, $instance){
			extract($args);
			$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
            
			# Before the widget
			echo $before_widget;

			# The title
			if ( $title )
				echo $before_title . $title . $after_title;

			# Make the widget
				
				echo '<link rel="stylesheet" type="text/css" href="'.get_option( 'siteurl' ) . '/wp-content/plugins/wp-e-commerce-scormcloud/css/scormcloud.widget.css" />';
				
			//echo '<h2>SCORM Course Registrations</h2>';
			global $current_user;
			global $wpdb;
			get_currentuserinfo();

			if($current_user->user_login == '')
			{
				echo '<a href="wp-login.php">'.__("Log in").'</a> '.__("to see your training history").'.';
			}
			else
			{
                require_once('scormcloud.wp.php');
                $ScormService = scormcloud_getScormEngineService();
                $regService = $ScormService->getRegistrationService();
                
                $regs = $wpdb->get_results("SELECT reg.reg_id,inv.course_title, inv.active, reg.update_date FROM ".scormcloud_getDBPrefix()."scormcloudinvitationregs reg
                                           JOIN ".scormcloud_getDBPrefix()."scormcloudinvitations inv ON reg.invite_id = inv.invite_id
                                           WHERE user_id = '".$current_user->ID."' AND inv.blog_id = '".$GLOBALS['blog_id']."'
                                           ORDER BY reg.update_date DESC");
                
                //echo count($regs);
                
                echo '<div class="courselistDiv">';
                
                if (count($regs) > 0){
                    echo "<div class='helpMsg'>".__("Click course title to launch.")."</div>";
                } else {
                    echo "<div class='helpMsg'>".__("You have not taken any training.")."</div>";
                }
                
                foreach ($regs as $reg) {
                    $regId = $reg->reg_id;
                    $regResultsXmlStr = $regService->GetRegistrationResult($regId,0,0);
                    $resXml = simplexml_load_string($regResultsXmlStr);
                    
                    $completion = $resXml->registrationreport->complete;
                    $success = $resXml->registrationreport->success;
                    $seconds = $resXml->registrationreport->totaltime;
                    $score = $resXml->registrationreport->score;
                
                    $courseTitle = $reg->course_title;
                    echo "<div class='usercourseblock'>";
                    if ($reg->active == 1){
                        echo "<a class='courseTitle' href='javascript:void(0);' key='$regId' onclick='ScormCloud.Widget.getLaunchURL(\"$regId\",\"Training\");' url='".get_option( 'siteurl' )."/wp-content/plugins/wp-e-commerce-scormcloud/ajax.php' title='Click to launch course $courseTitle'>$courseTitle</a>";
                    } else {
                        echo "<span class='courseTitle' title='".__("This course is currently inactive.")."'>$courseTitle</span>";
                    }
                    
                    echo "<a href='javascript:void(0);' class='toggleButton showDetails' toggleobject='.courselistDiv .regs.courseDetails.$regId' onText='hide details' offText='show details'>".__("show details")."</a>";
                    
                    echo "<div class='regs courseDetails $regId' >";
                    if($seconds > 0)
                    {
                        echo "<div class=''>".__("Completion").": <span class='$completion'>$completion</span></div>";
                        echo "<div class=''>".__("Success").": <span class='$success'>$success</span></div>";
                        echo "<div class=''>".__("Score").": ".($score == "unknown" ? "-" : $score."%")."</div>";

                        echo '<div class="time">'.floor($seconds / 60)."min ".($seconds % 60).__('sec spent in course').'</div>';
                        

                    }else{
                        echo '<div class="">'.__("Not Started").'</div>';
                    }
                    
                    echo "</div>";
                    
                    //$widgetscript .= 'jQuery("#title_'.$reg->package_id.'").click(function(){jQuery("#details_'.$reg->package_id.'").toggle();});';
                    //$widgetscript .= 'jQuery("#title_'.$reg->package_id.'").hover(function(){jQuery(this).addClass("widgetTitleHover");},function(){jQuery(this).removeClass("widgetTitleHover");});';
                    
                    echo '</div>';
                    
			}
			echo '</div>';
			echo '<script language="javascript">'.$widgetscript.'</script>';
			echo '<script language="javascript" src="'.get_option( 'siteurl' ) . '/wp-content/plugins/wp-e-commerce-scormcloud/scripts/scormcloud.widget.js" >'.'</script>';
			}
			# After the widget
			echo $after_widget;
		}

		/**
		* Saves the widgets settings.
		*
		*/
		function update($new_instance, $old_instance){
			$instance = $old_instance;
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));

			return $instance;
		}

		/**
		* Creates the edit form for the widget.
		*
		*/
		function form($instance){
			//Defaults
			$instance = wp_parse_args( (array) $instance, array('title'=>'') );

			$title = htmlspecialchars($instance['title']);

			# Output the options
			echo '<p style="text-align:left;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width: 150px;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		}

	}// END class
?>