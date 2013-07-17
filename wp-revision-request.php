<?php
/**
* @package RevisionRequest
* @version 0.1
*/

/*
Plugin Name: RevisionRequest
Plugin URI: https://github.com/mcguffin/wp-revision-request
Description: More revision control: Create revision only upon request. Controls to delete revisions. Display revisions on the blog.
Author: Joern Lund
Version: 1.0.0
Author URI: https://github.com/mcguffin
*/


require_once( dirname(__FILE__). '/inc/class-revisionrequestcore.php' );
require_once( dirname(__FILE__). '/inc/class-revisionondemand.php' );
require_once( dirname(__FILE__). '/inc/class-revisioncontroller.php' );
require_once( dirname(__FILE__). '/inc/class-revisiondisplay.php' );

?>