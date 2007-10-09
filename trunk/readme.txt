=== Plugin Name ===
Contributors: b4it
Donate link: 
Tags: avatars, comments
Requires at least: 2.0.2
Tested up to: 2.3
Stable tag: 0.1

This plugin allows you to add MyBlogLog.com or Gravatar.com or Avatars.pl avatars to Wordpress comments.

== Description ==

4Avatars is a simple Plugin for WordPress that allows you to add avatars to comments on your blog. Avatars are from MyBlogLog or Gravatar or even from Polish site Avatars. Of curse avatars are NOT saved on your ftp!
4Avatars have very simple options page which allows you to choose site with avatars and choose default avatar which will be shown when author of comment doesn't have any.

== Installation ==

1. Upload folder 4avatars to /wp-content/plugins/
2. Turn on plugin
4. Fill options in Options > 4Avatars
4. Paste:
	&lt;?php if (function_exists('foravatars')) { foravatars(); } ?&gt;
   Into comments.php
5. Paste:
	.foravatars {
		float: right;
	}
   Into style.css

== Frequently Asked Questions ==

= What class can I use to style avatars? =

Use .foravatars

== Screenshots ==

1. Option page

== Thanks ==

Andrea Micheloni & Napolux for MyBlogLog hack

== Changelog ==

v0.01:

* First version of plugin
