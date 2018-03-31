=== Po.et ===
Contributors: lautarodragan, knowledgearc
Donate link:
Tags: po.et, frost, decentralized, intellectual property, bitcoin
Requires at least: 4.1
Requires PHP: 5.2.4
Tested up to: 4.9.4
Stable tag: 1.0.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Po.et WordPress Plugin allows you to automatically submit your blog posts to Po.et.

== Description ==
The Po.et WordPress Plugin allows you to automatically submit your blog posts to Po.et.
All you need to do is install and configure it,
then every time you post a new blog entry it'll automatically be posted to the Po.et network,
and thus will be permanently timestamped on the Bitcoin blockchain.

This plugin makes use of the Frost API to interact with Po.et.


== Installation ==
1. You can either install this plugin from the WordPress Plugin Directory,
  or manually  [download the plugin](https://github.com/poetapp/wordpress-plugin/releases) and upload it through the 'Plugins > Add New' menu in WordPress
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Register at frost.po.et to get an API key
1. Copy your API Key into the "Token" input in the plugin's settings

== How to Use ==

Once the plugin is properly configured it functions automatically. Every time you create or edit a post, it'll be saved to Po.et through Frost.

You can optionally place the shortcode `[poet-badge]` in your posts to automatically display a "Timestamped with Po.et" badge.

== Frequently asked questions ==

= How much does this cost?

Nothing. Po.et, Frost and this plugin are completely free.
Furthermore, the Po.et Node and this plugin are open source (free as in "freedom").

= What is Po.et? =

Po.et is a decentralized platform for managing creative assets.
See https://po.et for more info.

= What is Frost? =
Frost is an API layer that greatly simplifies interaction with the Po.et network
See https://frost.po.et for more info.

== Screenshots ==

1. Po.et plugin settings.
2. Verified on Po.et.

== Changelog ==

= 1.0.0 =
Initial release.
