=== WP Revision Request ===
Contributors: podpirate
Donate link: 
Tags: revisions
Requires at least: 3.5
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

More revision control in Wordpress: create revision on demand, delete revisions and show a revision history.

== Description ==

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

1.0.0 Initial Release


== Upgrade notice ==


== Plugin API ==

The plugin offers a couple of filters to allow Theme authors to hook in.

Filter `revisioncontroller_actions`:
Use it to add or remove Actions from the revisions metabox.
'<?php
	// will remove the restore action from Controller Meta Box
	function remove_restore_action( $actions , $post_ID , $revision_ID ) {
		unset($actions['restore']);
		return $actions;
	}
	add_filter( 'revisioncontroller_actions', 'remove_restore_action' , 10 , 3 );
?>'

Filter `revision_container_html`:
Use it to change the default container HTML. Make sure you include a `%s`, where the item HTML is merged.

'<?php
	function gimme_a_div( $html , $post ) {
		return '<div>%s</div>';
	}
	add_filter( 'revision_container_html', 'gimme_a_div' , 10 , 2 );
?>'

Filter `revision_item_html`:
Use it to change the default item HTML. Make sure you include a `%s`, where the item's link is merged.

'<?php
	function gimme_a_span( $html , $post , $revision ) {
		return '<span>%s</span>';
	}
	add_filter( 'revision_item_html', 'gimme_a_span' , 10 , 3 );
?>'