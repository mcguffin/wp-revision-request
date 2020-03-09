<?php
/**
* @package RevisionRequest
* @version 0.1
*/

/*
Plugin Name: RevisionRequest
Plugin URI: http://wordpress.org/plugins/wp-revision-request/
Description: <strong>PLUGIN ABANDONED!</strong> More revision control: Create revision only upon request. Controls to delete revisions. Display revisions on the blog.
Author: Joern Lund
Version: 1.0.3
Author URI: https://github.com/mcguffin
*/


require_once( dirname(__FILE__). '/inc/class-revisionrequestcore.php' );

function load_backend( ) {
	require_once( dirname(__FILE__). '/inc/class-revisionondemand.php' );
	require_once( dirname(__FILE__). '/inc/class-revisioncontroller.php' );
}

if ( is_admin() ) {
	add_action('load-post.php' , 'load_backend' );
}
require_once( dirname(__FILE__). '/inc/class-revisiondisplay.php' );



function wp_revisionrequest_deprecation_notice() {
/*
'<tr class="plugin-update-tr%s" id="%s" data-slug="%s" data-plugin="%s">' .

*/
	?>
	<tr class="plugin-update-tr active" >
		<td class="plugin-update colspanchange" colspan="3" style="position:relative;top:-1px;">
			<div class="notice notice-error notice-alt inline">
				<p>
					<span class="dashicons dashicons-warning"></span>
					<strong><?php esc_html_e('Warning:','revisionrequest'); ?></strong>
					<?php esc_html_e('“RevisionRequest” is no longer maintained. It may disappear without further notice.','revisionrequest'); ?>
				</p>
			</div>
		</td>
	</tr>
	<?php
}

if ( is_admin() ) {
	add_action('after_plugin_row_wp-revision-request/wp-revision-request.php','wp_revisionrequest_deprecation_notice');
}
