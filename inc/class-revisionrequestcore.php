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
			$checkbox_label = __('Create new Revision','revisionrequest');
			$intro_1 = _x( 'Sometimes when you revise a post you might like to keep your previous edit. Post revisions are a way to do this.' , 'Explain Revisions' , 'revisionrequest');
			if ( has_action( 'post_updated' , 'wp_save_post_revision' ) ) {
				$intro_2 = sprintf(_x( 'To keep your previous edits just check <code>%1$s</code> and save the post. Then make your edits and save again.'  , 'Explain Revisions' , 'revisionrequest') , $checkbox_label);
				$explain_new_rev = __('If checked WordPress will save your changes and then create a new revision of this post.' , 'revisionrequest' , 'Explain Revision Creation' );
			} else if ( has_action( 'pre_post_update' , 'wp_save_post_revision' ) ) {
				$intro_2 = sprintf(_x( 'To save your changes in a new revision by keeping the previous version just check <code>%1$s</code> and save the post.'  , 'Explain Revisions' , 'revisionrequest' ) , $checkbox_label);
				$explain_new_rev = _x('If checked WordPress will save your changes in a new revision of this post.' , 'Explain Revision Creation' , 'revisionrequest' );
			} else { // cant't say when revisions get saved.
				return;
			}

			if ( WP_POST_REVISIONS === true )
				$intro_3 = sprintf( _x( 'You can store unlimited revisions of this post.' , 'Explain Revisions' , 'revisionrequest') , WP_POST_REVISIONS);
			else 
				$intro_3 = sprintf( _x( 'You can store up to %1$d revisions of this post.' , 'Explain Revisions' , 'revisionrequest') , WP_POST_REVISIONS);

			$explain_rev_ctl = _x('This is a list of all revisions belonging to this post. Use the links in the actions column to delete, restore or view a revision.' , 'Explain Revisions Metabox'  , 'revisionrequest');
			$args = array(  
				'title' => __( 'Revisions' ), 
				'id' => 'revisionrequest-controller', 
				'content' => sprintf( '<p class="description">%1$s</p>
				<p class="description">%2$s</p><ul>
					<li>
						<strong>%3$s</strong> – %4$s
					</li>
					<li>
						<strong>%5$s</strong> – %6$s
					</li>
				</ul>', 
					$intro_1 . ' ' . $intro_2 , 
					$intro_3 ,
					
					$checkbox_label,
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