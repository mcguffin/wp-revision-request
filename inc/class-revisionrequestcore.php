<?php
/**
* @package RevisionRequest
* @version 1.0
*/



// ----------------------------------------
//	This class initializes the RevisionRequest plugin.
//	(As of version 1.0 it only loads an apropriate plugin textdomain for translation readyness.)
// ----------------------------------------


if ( ! class_exists('RevisionRequestCore') ) :
class RevisionRequestCore {
	static function init() {
		add_action( 'plugins_loaded' , array( __CLASS__, 'plugin_loaded' ) );
		add_action( 'load-post.php' , array( __CLASS__, 'load_help' ) );
	}
	
	// translation ready.
	static function plugin_loaded() {
		load_plugin_textdomain( 'revisionrequest' , false, dirname(dirname( plugin_basename( __FILE__ ))) . '/lang');
	}
	
	static function load_help(){
		add_filter('contextual_help', array( __CLASS__ , 'controller_help' ) , 10, 3);
	}
	
	
	static function controller_help( $contextual_help, $screen_id, $screen ) {
		if ( post_type_exists( $screen_id ) && post_type_supports( $screen_id , 'revisions' ) ) {
			global $wp_version;
			if ( $wp_version >= '3.6' )
				$explain_new_rev = __('If checked WordPress will save your changes and then create a new revision of this post.' , 'Explain Revision Creation' , 'revisionrequest' );
			else 
				$explain_new_rev = __('If checked WordPress will save your changes a new revision of this post.' , 'Explain Revision Creation' , 'revisionrequest' );
			$explain_rev_ctl = _x('This is a list of all revisions belonging to this post. In the ' , 'Explain Revisions Metabox' , 'revisionrequest' );
			$args = array(  
				'title' => __( 'Revisions' ), 
				'id' => 'revisionrequest-controller', 
				'content' => sprintf( __('<ul>
					<li>
						<strong>%1$s</strong> – %2$s
					</li>
					<li>
						<strong>%3$s</strong> – %4$s
					</li>
				</ul>', 'revisionrequest') , 
					__('Create new Revision','revisionrequest'),
					$explain_new_rev,
					__( 'Revisions' ),
					$explain_rev_ctl
				),
			);
			$screen->add_help_tab( $args );
		}
	}
	
}
RevisionRequestCore::init();
endif;

?>