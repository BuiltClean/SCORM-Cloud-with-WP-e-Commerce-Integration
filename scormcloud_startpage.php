<?php

if ( defined('ABSPATH') )
	require_once(ABSPATH . 'wp-load.php');
else
	require_once('../../../wp-load.php');
require_once(ABSPATH . 'wp-admin/admin.php');

global $wpdb;

echo '<div class="scormcloud-admin-page startpage">';
    
require_once('scormcloud.wp.php');
$ScormService = scormcloud_getScormEngineService();

    $isValidAccount = $ScormService->isValidAccount();

if ($isValidAccount){
	//Reportage Includes
	echo '<script type="text/javascript" ';
	echo "src=\"http://cloud.scorm.com/Reportage/scripts/reportage.combined.js\"></script>\n";
	echo '<link rel="stylesheet" ';
	echo "href=\"http://cloud.scorm.com/Reportage/css/reportage.combined.css\" type=\"text/css\" media=\"screen\" />\n";
	echo '<div class="mod-scormcloud">';

//Check for some defaults to set the form up
	$rptService = $ScormService->getReportingService();
	$rptAuth = $rptService->GetReportageAuth('FREENAV',true);
	$rServiceUrl = $rptService->GetReportageServiceUrl();
    
} 
    
    
//Report banner SCORM Cloud branded?
	echo '<div class="header">
            <h1>'. __("SCORM Cloud for Wordpress").'</h1>
			<a id="CloudConsoleLink" href="https://cloud.scorm.com" 
				target="_blank" title="'. __("Open the SCORM Cloud Site in a new window.").'">'. __("SCORM Cloud Account Management").'</a>';
    if ($isValidAccount){
        $reportageUrl = $rServiceUrl.'Reportage/reportage.php?appId='.$ScormService->getAppId()."&registrationTags=$inviteId|_all";
        echo '&nbsp;&nbsp;|&nbsp;&nbsp;
            <a id="ReportageLink" href="'.$rptService->GetReportUrl($rptAuth, $reportageUrl).'" 
				target="_blank" title="'. __("Open the SCORM Reportage Console in a new window.").'">'. __("SCORM Cloud Reportage").'</a>';
    }
    echo "</div>";
    
    if (!$isValidAccount){
        
        echo '<div class="settingsPageLink"><a href="'.get_option( 'siteurl' ).'/wp-admin/admin.php?page=scormcloudsettings" 
				title="'. __("Click here to configure your SCORM Cloud plugin.").'">'. __("Click Here to go to the settings page to configure the SCORM Cloud wordpress Plugin.").'</a></div>';
        
    }
    /*
    echo "<div class='meta-box-sortables'>";
    echo "<div class='reportageWrapper postbox ".($isValidAccount ? "closed" : "")."'>";
    echo "<div class='handlediv'><br></div><h3 class='hndle'><span>". __("About SCORM Cloud")."</span></h3>";
    echo "<div class='inside'>";

    echo '<div class="aboutScorm">
        <object style="float:right;" width="480" height="385"><param name="movie" value="http://www.youtube.com/v/nP657pV6OWU&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/nP657pV6OWU&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>
        This will be an explantion of SCORM Cloud.  Maybe with a video of how it works... maybe like this...
        <div style="clear:both;"></div>
    </div>';
    
    echo "</div></div>";
    */
    
    if ($isValidAccount){
        //  AppId Summary Report
        if(!isset($dateRangeStart))
        {
            $dateRangeStart = '2009-01-01';
        }
        if(!isset($dateRangeEnd))
        {
            $dateRangeEnd = date("Y-m-d");
        }
    
        $dateOptions = new DateRangeSettings($dateRangeType,$dateRangeStart,$dateRangeEnd,$dateCriteria);
        
        $tagSettings = new TagSettings();
        $tagSettings->addTag('registration',$GLOBALS['blog_id']);
        
        $sumWidgetSettings = new WidgetSettings($dateOptions,$tagSettings);
        $sumWidgetSettings->setShowTitle(true);
        $sumWidgetSettings->setScriptBased(true);
        $sumWidgetSettings->setEmbedded(true);
        $sumWidgetSettings->setVertical(false);
        $sumWidgetSettings->setDivname('TotalSummary');
        
        $coursesWidgetSettings = new WidgetSettings($dateOptions);
        $coursesWidgetSettings->setShowTitle(true);
        $coursesWidgetSettings->setScriptBased(true);
        $coursesWidgetSettings->setEmbedded(true);
        $coursesWidgetSettings->setExpand(true);
        $coursesWidgetSettings->setDivname('CourseListDiv');
        
        $learnersWidgetSettings = new WidgetSettings($dateOptions);
        $learnersWidgetSettings->setShowTitle(true);
        $learnersWidgetSettings->setScriptBased(true);
        $learnersWidgetSettings->setEmbedded(true);
        $learnersWidgetSettings->setExpand(true);
        $learnersWidgetSettings->setDivname('LearnersListDiv');
    
        $summaryUrl = $rptService->GetWidgetUrl($rptAuth,'allSummary',$sumWidgetSettings);
        $coursesUrl = $rptService->GetWidgetUrl($rptAuth,'courseRegistration',$coursesWidgetSettings);
        $learnersUrl = $rptService->GetWidgetUrl($rptAuth,'learnerRegistration',$learnersWidgetSettings);
        
        $dateRelavance = $rptService->GetReportageDate();
        
        
        echo "<div class='meta-box-sortables'>";
        echo "<div class='reportageWrapper postbox'>";
        echo "<div title='Click to toggle' class='handlediv'><br></div><h3 class='hndle'>". __("Overall Reportage Summary");
        echo "</h3>";
        echo "<div class='inside'>";
        echo "<span class='dateRelevance'>". __("Data current as of ")."<span class='localizeRecentDate' utcdate='".date("d M Y H:i:s", strtotime($dateRelavance))."'></span></span>";
        echo '<table class="reportageTable"><tr class="summary"><td colspan="2">';
        echo '<div id="TotalSummary">'. __("Loading Summary...").'</div>';
        echo '<br></td></tr>';
        echo '<tr class="details">';
        // Courses Detail Widget
        echo '<td class="wp_details"><div id="CourseListDiv" class="wp_details_div">'. __("Loading All Courses...").'</div>';
        echo '</td>';
        //Learners Detail Widget
        echo '<td class="wp_details"><div id="LearnersListDiv" class="wp_details_div">'. __("Loading All Learners...").'</div>';
        echo '</td></tr></table>';
        //Load 'em Up...
        echo '<script type="text/javascript">';
        echo 'jQuery(document).ready(function(){';
        echo '	loadScript("'.$summaryUrl.'");';
        echo '	loadScript("'.$coursesUrl.'");';
        echo '	loadScript("'.$learnersUrl.'");';
        
        echo '});';
        
        echo '</script>';
        echo '</div></div></div>';//reportage wrapper
    } 
    
    
    
    
    echo '</div>';//overall page wrapper

?>

