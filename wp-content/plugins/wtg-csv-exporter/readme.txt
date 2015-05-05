=== Plugin Name ===
Contributors: WebTechGlobal, Ryan Bayne
Donate link: http://www.webtechglobal.co.uk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: csv, export, data export, database export, data download, export csv, csv files
Requires at least: 3.8.0
Tested up to: 4.1.1
Stable tag: trunk

New multiple file CSV exporter for 2015 offers export profiles to create the perfect .csv file.

== Description ==

Select the tables you wish to export to one or multiple .csv files. My goal is to offer control over all aspects of the
exported .csv file/s i.e. name the headers rather than defaulting to database column names. This will come in use for
automated features i.e. generate .csv files automatically and deliver them using email or simply placing them in a selected
directory. My approach is going to be the duplication of PHP functions and HTML forms - then adapt them to suit a different need. The idea
is to avoid changing existing features in use. This will require an expanding interface which the WTG CSV Exporter offers.

To begin with the plugin will be simple though. Contributions of any kind (even a Tweet or a Facebook like helps) will
drive us towards the next update. 

= Main Links = 
*   <a href="http://www.webtechglobal.co.uk/wtg-csv-exporter-wordpress/" title="WebTechGlobals CSV Exporter Portal">Portal</a>
*   <a href="http://forum.webtechglobal.co.uk/viewforum.php?f=41" title="WebTechGlobal Forum for CSV Exporter">Forum</a>
*   <a href="https://www.facebook.com/pages/WTG-CSV-Exporter/769024673180682" title="WTG Task Manager Facebook Page">Facebook</a>
*   <a href="http://www.webtechglobal.co.uk/category/wordpress/wtg-csv-exporter/" title="WebTechGlobal blog category for tasks manager">Blog</a>
*   <a href="http://www.twitter.com/WebTechGlobal" title="WebTechGlobal Tweets">Twitter</a>
*   <a href="http://www.youtube.com/playlist?list=PLMYhfJnWwPWCz99LOQSgEVfiXJMmmPa7q" title="WTG CSV Exporter official playlist on YouTube">YouTube Playlist</a>

= Features List = 

1. Export Data to .csv File   
 
== Installation ==

Please install WTG CSV Exporter from WordPress.org by going to Plugins --> Add New and searching "WTG CSV Exporter". This is safer and quicker than any other methods.

== Frequently Asked Questions ==

= As a WebTechGlobal subscriber can I get higher priority support for this plugin? =
Yes - subscribers are put ahead of my Free Workflow and will not only result in a quicker response for support
but requests for new features are marked with higher priority.

= Can I hire you to customize the plugin for me? =
Yes - you can pay to improve the plugin to suit your needs. However many improvements will be done free.
Please post your requirements on the plugins forum first before sending me Paypal or Bitcoins. If your request is acceptable
within my plans it will always be added to the WTG Tasks Management plugin which is part of my workflow system. The tasks
priority can be increased based on your WebTechGlobal subscription status, donations or contributions you have made.

== Screenshots ==

1. Screenshot one.
2. Screenshot two.
3. Screenshot three.

== Languages ==

Translators needed to help localize WTG CSV Exporter.

== Upgrade Notice ==

Please update this plugin using your WordPress Installed Plugins screen. Click on Update Now under this plugins details when an update is ready.
This method is safer than using any other source for the files.

== Changelog == 

= 0.0.4 = 
* Feature Changes
    * None                                       
* Technical Information
    * Menu array class was being loaded too many times in different locations - one global exists for the entire plugin now.
    * Various variable name changes and removal reduntant globals.
* Known Issues
    * None
    
= 0.0.3 =
* Feature Changes
    * Removed Update view - this corrects installation error on yesterdays update                                         
* Technical Information
    * None
* Known Issues
    * None
    
= 0.0.2 =
* Feature Changes
    * Default Profiles renamed to Basic Profiles
    * Added the Create Basic Profiles view (focuses on WP core data)
    * Added file for Create Detailed Profiles view, the view will probably come in 0.0.12                                         
* Technical Information
    * Some exports may serialize data that is in PHP arrays, exporter would need to unseralize. Options will be added for alternative approaches.
* Known Issues
    * Some or all forms may not show properly on the WP dashboard - does not stop the plugin being used though this is just an extra feature.

== Donators ==
These donators have giving their permission to add their site to this list so that plugin authors can
request their support for their own project. Please do not request donations but instead visit their site,
show interest and tell them about your own plugin - you may get lucky. 

* <a href="" title="">Ryan Bayne from WebTechGlobal</a>

== Contributors: Translation ==
These contributors helped to localize WTG Tasks Manager by translating my endless dialog text.

* None Yet

== Contributors: Code ==
These contributers typed some PHP or HTML or CSS or JavaScript or Ajax for WTG Tasks Manager. Bunch of geeks really! 

* None Yet

== Contributors: Design ==
These contributors created graphics for the plugin and are good with Photoshop. No doubt they spend their time merging different species together!

* None Yet

== Contributors: Video Tutorials ==
These contributors published videos on YouTube or another video streaming website for the community to enjoy...and maybe to get some ad clicks.

* None Yet

= When To Update = 

Browse the changes log and decide if you need any recent changes. There is nothing wrong with skipping versions if changes do not
help you - look for security related changes or new features that could really benefit you. If you do not see any you may want
to avoid updating. If you decide to apply the new version - do so after you have backedup your entire WordPress installation 
(files and data). Files only or data only is not a suitable backup. Every WordPress installation is different and creates a different
environment for WTG Task Manager - possibly an environment that triggers faults with the new version of this software. This is common
in software development and it is why we need to make preparations that allow reversal of major changes to our website.

== Version Numbers and Updating ==

Explanation of versioning used by myself Ryan Bayne. The versioning scheme I use is called "Semantic Versioning 2.0.0" and more
information about it can be found at http://semver.org/ 

These are the rules followed to increase the WTG CSV Exporter plugin version number. Given a version number MAJOR.MINOR.PATCH, increment the:

MAJOR version when you make incompatible API changes,
MINOR version when you add functionality in a backwards-compatible manner, and
PATCH version when you make backwards-compatible bug fixes.
Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.


