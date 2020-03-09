=== WP Revision Request ===
Contributors: podpirate
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CXETZUWYAAVC8
Tags: revisions
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

More revision control in Wordpress: create revision on demand, delete revisions and show a revision history.

== Description ==


<div class="plugin-notice notice notice-error notice-alt">

“WP Revision Request” is no longer maintained. It may disappear without further notice.

</div>


This lightweight plugin adds three features to WordPress' revision management.

1. Saving revisions on demand: Select wheter you want to save a revision or not.
2. Manually delete a revision.
3. Display revisions right on Your blog.
4. Plugin-API
5. English and German localization

Latest files on [GitHub](https://github.com/mcguffin/wp-revision-request).

== Installation ==

1. Upload the 'wp-revision-request.zip' to the `/wp-content/plugins/` directory and unzip it.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= I found a bug. Where should I post it? =

I personally prefer GitHub. The plugin code is here: [GitHub](https://github.com/mcguffin/wp-revision-request)

= I want to use the latest files. How can I do this? =

Use the GitHub Repo rather than the WordPress Plugin. Do as follows:
1. If you haven't already done: [Install git](https://help.github.com/articles/set-up-git)
2. in the console cd into Your 'wp-content/plugins' directory
3. type 'git clone git@github.com:mcguffin/wp-revision-request.git'
4. If you want to update to the latest files (be careful, might be untested on Your WP-Version) type 'git pull'.

= I found a bug and fixed it. How can I let You know? =

Either post it on [GitHub](https://github.com/mcguffin/wp-revision-request) or—if you are working on a cloned repository—send me a pull request.


== Screenshots ==

1. Improved Posts editor.


== Changelog ==

= 1.0.3 =
- End-of-Life

= 1.0.2 =
- Several fixes
- determine WordPress' revision behaviour by has_action($hook , 'wp_save_post_revision') rather than by version number.
- Add and translate help panel.

= 1.0.1 =
- Require files only if needed

= 1.0.0 =
Initial Release


== Plugin API ==

The plugin offers a couple of filters to allow Theme authors to hook in.

Visit the [GitHub-Repo](https://github.com/mcguffin/wp-revision-request) for details.
