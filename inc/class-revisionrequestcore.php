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
		add_action( 'admin_init' , array( __CLASS__, 'admin_init' ) );
	}
	
	// translation ready.
	static function plugin_loaded() {
		load_plugin_textdomain( 'revisionrequest' , false, dirname(dirname( plugin_basename( __FILE__ ))) . '/lang');
	}
	
	static function admin_init(){
		add_filter('contextual_help', array( __CLASS__ , 'controller_help' ) , 10, 3);
	}
	
	
	static function controller_help( $contextual_help, $screen_id, $screen ) {
		if ( post_type_exists( $screen_id ) && post_type_supports( $screen_id , 'revisions' ) ) {
			$args = array(  
				'title' => __( 'Revisions' ), 
				'id' => 'revisionrequest-controller', 
				'content' => sprintf( __('<ul>
					<li>
						<strong>%1$s</strong> – …
					</li>
					<li>
						<strong>%2$s</strong> – …
					</li>
				</ul>', 'revisionrequest') , 
					__('Create new Revision','revisionrequest'),
					__( 'Revisions' )
				),
			);
			$screen->add_help_tab( $args );
		}
	}
	
}
RevisionRequestCore::init();
endif;

?>