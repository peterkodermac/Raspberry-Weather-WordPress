=== Raspberry Weather ===
Tags: Raspberry Pi, Raspberry Weather, temperature, humidity
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 4.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin displays data recorded by your weather station. It connects to your database and draws a nice line graph. 

== Description ==

By following the tutorials on www.raspberryweather.com you can use your Raspberry Pi to create **your own weather station**. When the initial steps are completed,
you can display the readings in a nice graph. It connects to the Wordpress database and draws a graph that contains temperature and humidity readings. The graph
is customizable. 

You can decide if you want to display the temperature and humidity or just temperature or humidity. The graph displays the data from the date you
want - today, yesterday or a specific date. This plugin adds a new icon to your kitchen sink. By clicking on it, a shortcode is generated and pasted in your post.

If you just want to take a look at how this plugin looks, you can. It will display some dummy data to show you how everything looks like.

== Installation ==

1. Upload the package to */wp-content/plugins directory*
2. Activate the plugin through "Plugins" menu in Wordpress
3. Create the graph by clicking the icon on kitchen sink
4. Optionally modify the shortcode that is displayed

== Frequently Asked Questions ==

= What do I need for this plugin? =

You need a Raspberry Pi, temperature sensor and some spare time. More information is provided on www.raspberryweather.com.

= Something is not working! =

This is the first release, bugs are likely to be present. Drop me a line at peter@raspberryweather.com, I will do my best to fix it.

= What caching plugin should I use? =

To speed the loading time on your Raspberry Pi, you should use a caching plugin. The Raspberry Weather plugin works well with Quick Cache plugin.

= I need more information about this plugin =

Same as above, drop me a line on my email. Or take a look at http://www.raspberryweather.com/raspberry-weather-graph/.

== Screenshots ==

1. Graph for temperature reading

2. Sample graph with dummy data

== Changelog ==

= 1.5 =

Small fix for shortcode generating button

= 1.4 =

Added option to display last 30 days and last 7 days in a graph

= 1.3 =

Another fine contribution by Stefan Mayer, removed hardcoded path to plugin js

= 1.2 =
Thanks to Stefan Mayer for removing deprecated functions and adding some improvements! Graph now works on newest PHP versions!

= 1.1 =
Fixed bug for negative values, thank you Simon P.

= 1.0 =
* Initial release
