<?php
/**
* @package RevisionRequest
* @version 0.1
*/

/*
Plugin Name: RevisionRequest
Plugin URI: http://wordpress.org/plugins/wp-revision-request/
Description: More revision control: Create revision only upon request. Controls to delete revisions. Display revisions on the blog.
Author: Joern Lund
Version: 1.0.1
Author URI: https://github.com/mcguffin
*/


require_once( dirname(__FILE__). '/inc/class-revisionrequestcore.php' );

function load_backend( ) {
	require_once( dirname(__FILE__). '/inc/class-revisionondemand.php' );
	require_once( dirname(__FILE__). '/inc/class-revisioncontroller.php' );
}

if ( is_admin() ) {
	add_action('load-post.php' , 'load_backend' );
} else {
	require_once( dirname(__FILE__). '/inc/class-revisiondisplay.php' );
}

?>