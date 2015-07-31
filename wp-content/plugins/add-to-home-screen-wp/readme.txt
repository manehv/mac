=== Add to home screen WP Plugin ===
Contributors: tulipwork
Donate link: http://tulipemedia.com
Tags: iPhone, iPad, iOs, homescreen, home screen, icon, iPod touch, mobile, tablet, app, web app
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add to HomeScreen WordPress plugin invites your readers to add your blog as an icon / web app on home screen of their iPhone, iPad and iPod touch.

== Description ==

This plugin uses [Add to home screen's Cubiq script](http://cubiq.org/add-to-home-screen "Add to home screen") to place a floating balloon inviting the user to add a website to their home screen as a standard iOs application.
It's a good way to retain visitors coming to your blog, especially if you don't want to develop an expensive application just to let them read articles of your WordPress blog.

The floating balloon is compatible with iPhone/iPod touch, iPhone4, iPhone 4S, iPhone 5, iPhone 5S and iPad.

= Features =


*Thanks to the functions of the Cubiq script, there are many features and allowed customizations:*

* Message: type the message that you want inside the floating window. If you don't custom the message, the script checks the user's locale and shows a default message in an appropriate language. You can also force a language.
* Animation: drop, bubble or fade.
* Delays: before showing the message and its duration.
* Expire: minutes before displaying the message again.
* Touch icon: icon of your app but also image while your app is loading.
* Show to returning visitors only: to only target returning visitors and not irritate first time visitors.
* Some CSS customizations (font-size and line-height): use with caution.

*Additional features:*

* Custom the title of your application.
* Show message on homepage only or on the entire blog.
* Non responsive theme settings: the plugin is optimized for responsive WordPress themes. However, if you don't have one, you can find a way to display the message by filling out values that are recommended for non responsive templates in the plugin options page.
* Choose between using Safari as your app browser or fullscreen mode with custom navigation bar.

= Demo =

[Check a demo in my blog](http://tulipemedia.com) (load it on an iPhone for instance).

[Read more and documentation on my blog](http://tulipemedia.com/en/add-to-home-screen-wordpress-plugin/ "Add to home screen WP Plugin documentation on Tulipe Media")
<br />[En savoir plus et lire la documentation sur mon blog](http://tulipemedia.com/plugin-wordpress-pour-ajouter-son-blog-a-lecran-daccueil-iphone-ipad-et-ipod-touch/ "Plugin Add to Home Screen pour WordPress sur Tulipe Media")

= Internationalisation =
This plugin provides support for language translations. Help me to translate it by [notifying me in comments](http://tulipemedia.com/en/add-to-home-screen-wordpress-plugin/ "notify me in comments") and I'll add your mo/po files to the official plugin.

Available languages: English, French and German.
Thanks to [Julian](http://profiles.wordpress.org/h3p315t05 "Julian") for the German translation!

= Follow me =
Keep in touch with me on:
<br /><a target="_blank" rel="author" href="https://plus.google.com/u/0/110058232548204790434/?rel=author">Google+</a>
<br /><a target="_blank" href="http://twitter.com/tulipemedia">Twitter</a>
<br /><a target="_blank" href="http://www.facebook.com/tulipemedia">Facebook</a>

== Installation ==

1. Upload add-to-home-screen-wp folder to the '/wp-content/plugins/' directory or install it via the WordPress dashboard.
2. Activate the plugin through the 'Plugins' menu in WordPress. The floating balloon is now enabled.
3. Go to Settings > ATHS Options and play with settings.

== Frequently Asked Questions ==

= Is the plugin works for non responsive theme? =

This plugin works very well on responsive theme without any customization. However, if you do not have a responsive template, you can make the plugin works by increasing dimensions of the floating balloon on the plugin options page because it may be very small on non responsive theme.
I advise you to fill out recommended values mentionned in the options page.

= If a visitor adds my blog to its home screen, will the balloon continue to appear? =
Basically, you have two ways to let visitors browse your blog: on Safari or in a separate window.

* In Safari mode, your blog will open on Safari browser when you will tap on your icon, and the balloon will continue to appear (unfortunately, we cannot prevent it from being opened in this mode even if user has alreay added the blog to its homescreen). The solution is to set an important expire timeframe (e.g on year) in the options page.
* In Fullscreen mode, the web app will not open in Safari mode but as a separate Web application with a custom nav bottom bar. The pro is that the balloon will never appear again in this mode. The con is that you will not have Safari native options.
* Anyway, you should really set a long time frame in order to not disturb your visitors who come to your blog on Safari browser.

= I made changes on options page but nothing's changed when I load my blog? =

Try to:

* Clear your cache if you're using a cache plugin
* Clear your Safari cache/cookies.
* Reboot Safari: on iPhone for instance, you have to double-click on the home button, then press Safari button several seconds and click on the close button.

= The blog title on my icon is cut? =

Application names on the home screen are limited to a <strong>maximum of 12 characters</strong>, so anything beyond that will be truncated. Try to keep the title of your application under 13 characters on the iPhone if you want to prevent it from being cut off. Fortunately, there is an option in the plugin to customize your application title, it can be very useful especially if the title of your blog is too long.

= Will the floating balloon appear on iOS7 devices? =

Yes, and the icon will be displayed correctly depending on both pre and post iOS 7 devices.

== Screenshots ==

1. Plugin Option page
2. Plugin Option page
3. Plugin Option page
4. Example of the English floating balloon
5. Example of the French floating balloon
6. Your blog as a web application, in fullscreen mode (portrait view)
7. Your blog as a web application, in fullscreen mode (landscape view)
8. Example of the floating balloon in iOS 7
9. Example of the floating balloon in pre iOS 7 devices.

== Changelog ==

= 1.1 =
Fix for the iOS 7 web app status bar.
German translation added.

= 1.0 =
Floating balloon updated for iOS 7.

= 0.9 =
New home screen icons and startup screens for all iOs devices (ipad, iPhone 5, etc...).

= 0.8 =
Fix bug with "homepage only or all pages" option.
Some little performance improvements.

= 0.7 =
Improvement of the bottom navigation bar on Web App: added forward and reload buttons.
Allow using Safari mode in Web App.
Fix bug with the "returningVisitor" function.

= 0.6 =
Improvement CSS of the Web App.
Allow opening the balloon on homepage only or all pages.

= 0.5 =
Touch startup image that is displayed while the web application launches.
Prevent links switching to Safari browser.
Add navigation bar (back button) in the Web App.

= 0.4 =
Allow customizing Web App Title.

= 0.3 =
Ability to use device and icon tags when customizing the message.
Allow using apostrophe in custom message.

= 0.2 =
Display title of the page.

= 0.1 =
First version of the plugin.

== Upgrade Notice ==

= 1.1 =
Fix for the iOS 7 web app status bar.
German translation added.

= 1.0 =
Floating balloon updated for iOS 7.

= 0.9 =
New home screen icons and startup screens for all iOs devices (ipad, iPhone 5, etc...).

= 0.8 =
Fix bug with "homepage only or all pages" option.
Some little performance improvements.

= 0.7 =
Improvement of the bottom navigation bar on Web App with forward and reload buttons.
Allow using Safari mode in Web App.
Fix bug with the "returningVisitor" function.

= 0.6 =
Improvement CSS of the Web App.
Allow opening the balloon on homepage only or all pages.

= 0.5 =
Touch startup image that is displayed while the web application launches.
Prevent links switching to Safari browser.
Add navigation bar (back and forward buttons) in the Web App.

= 0.4 =
Allow customizing Web App Title.

= 0.3 =
Allow using device and icon tags when customizing the message.
Function addslashes added to allow using apostrophe in the custom message field.

= 0.2 =
Retrieve the wp_title function to display real page title below the home screen icon.

= 0.1 =
First version of the plugin.

== Credits ==
This plugin has been written by [Ziyad B](http://tulipemedia.com) and uses the [Add to Home Screen Floating Layer script](http://cubiq.org/add-to-home-screen) by Matteo Spinelli that is released under the MIT License (see below).

## License

This software is released under the MIT License.

Copyright (c) 2013 Matteo Spinelli, http://cubiq.org/

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use,
copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following
conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.