=== Plugin Name ===
Contributors: troyef
Tags: elearning, learning, scorm, aicc, education, training, cloud
Requires at least: 2.9
Tested up to: 3.0.1
Stable tag: trunk

Tap the power of SCORM to deliver and track training right from your WordPress-powered site.

== Description ==

SCORM Cloud For WordPress enables you to manage and deliver training from within WordPress.  Harnessing the SCORM Engine powered SCORM Cloud training delivery service, this plugin provides all version SCORM compliance to any WordPress blog or WordPress Multi-Site installation, including BuddyPress support.


*   Upload SCORM or AICC courses to the SCORM Cloud from within WordPress.
*   Embed training into posts and pages of your blog. Select whether all users or only logged in users can launch courseware.
*   Assign training to other WordPress users directly.
*   Include widgets on your blog for displaying a logged in user's training or displaying a catalog of available courses to launch.
*   View learner progress as well as overall reports of learner training, including aggregated reports based on course, blog post, and all training.
*   Activate in network mode for WordPress Multi-Site to enable all blog sites to use a single SCORM Cloud account, but to manage their training individually.  

In addition to the functionality found in the SCORM Cloud For WordPress plugin, you will have the full power and functionality provided by the <a href='https://cloud.scorm.com/sc/guest/SignInForm'>SCORM Cloud site</a> which provides access to even more history information, account management, and extensive SCORM testing tools. A SCORM Cloud account is required to use the SCORM Cloud service features of the plugin; accounts are free for limited training usage but unlimited testing within the SCORM Cloud site.

Visit the <a href='http://www.scorm.com/scorm-solved/scorm-cloud/'>SCORM Cloud website</a> to learn more about SCORM Cloud.

== Installation ==

You can download and install SCORM Cloud For WordPress using the built in WordPress plugin installer. If you download SCORM Cloud For WordPress manually, make sure it is uploaded to "/wp-content/plugins/wp-e-commerce-scormcloud/".

Activate SCORM Cloud For WordPress in the "Plugins" admin panel using the "Activate" or "Network Activate"link. 

On the left-hand Admin menu panel, open the SCORM Cloud menu and click on the Settings link.  On the SCORM Cloud Settings page, enter your AppID and Secret Key which can be found on the <a href='http://cloud.scorm.com/sc/user/Apps'>SCORM Cloud Apps</a> page by logging into your SCORM Cloud account.  If you are a super-admin and setting up a network-activated plugin, your credentials will be used for all sites and you need to choose whether to allow courses to be shared across all sites.  Click "Update Settings".



== Frequently Asked Questions ==

= Does a SCORM Cloud account cost money? =

Yes, although there is a free trial level account that allows for limited monthly training usage and unlimited SCORM testing.  If you decide that you need more than the 10 monthly free training registrations, there are several different tiers of paid accounts available, based on your usage needs. More <a href='http://www.scorm.com/scorm-solved/scorm-cloud/scorm-cloud-pricing/'>info on pricing</a>.

= What BuddyPress support do you provide? =

The SCORM Cloud For WordPress basic functionality works with BuddyPress without issue, including the SCORM Cloud widgets.  Additionally, SCORM Cloud for WordPress updates the BuddyPress activity stream with information about SCORM Cloud training that users take.

== Screenshots ==



== Changelog ==

= 1.0.2 =
* Modified the launch functionality so that non wordpress account holders can relaunch a course by entering the same email as their original launch into the launch form.

= 1.0.1 =
* Added a course Package Properties Editor to the admin courses page.

= 1.0 =
* Original Release.

== Upgrade Notice ==

= 1.0.2 =
With this upgrade, if a learner who does not have an account on your WordPress system enters the same learner information and clicks launch a on the same course a second time, the course will re-launch where the learner left off instead of starting a new training and using a new SCORM Cloud registration.

= 1.0.1 =
The added course Package Properties Editor allows administrators to set course properties settings from within WordPress.  The course properties help determine how the course is delivered to the user.
