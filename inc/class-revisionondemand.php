<?php
/**
* @package RevisionRequest
* @version 1.0
*/



// ----------------------------------------
//	This class adds a checkbox to the publish metabox, 
//	that gives you control over whether a revision is saved or not.
// ----------------------------------------

if ( !class_exists( 'RevisionOnDemand' ) ) :
class RevisionOnDemand {
	private static $_revision_field_name  = 'do_store_post_revision';
	private static $_revision_field_value = 'do_store';

	static function init() {
		// add checkbox to wp-publish metabox
		self::add_submitbox_action();
		// add action.
		add_action( 'pre_post_update' , array(__CLASS__,'revision_on_demand') , 1 );
	}
	
	static function post_submitbox_revision_action() {
		global $post, $wp_version;
		if ( ! post_type_supports( $post->post_type , 'revisions' ) )
			return;
		?><div class="misc-pub-section curtime">
		<input id="do_store_revision" type="checkbox" name="<?php echo self::$_revision_field_name ?>" value="<?php echo self::$_revision_field_value ?>" />
		<label for="do_store_revision"><?php _e('Create new Revision','revisionrequest') ?></label>
		<p class="howto"><?php 
			if ( has_action( 'post_updated' , 'wp_save_post_revision' ) ) // > 3.6 behaviour
				_e( 'Saves this post and creates a new revision.' , 'revisionrequest' ) ;
			else if ( has_action( 'pre_post_update' , 'wp_save_post_revision' ) ) 
				_e( 'Saves your changes in a new revision.' , 'revisionrequest' ) ;
		?></p>
	</div><?php // /misc-pub-section ?>
	<?php
	}
	
	static function revision_on_demand( $post_ID ) {
		// if there is no request to store a revision remove WP-action
		if ( ! post_type_supports( get_post($post_ID)->post_type , 'revisions' ) )
			return;
		global $wp_version;
		if ( ! isset($_REQUEST['do_store_post_revision']) || $_REQUEST['do_store_post_revision'] != self::$_revision_field_value ) {
			if ( has_action( 'post_updated' , 'wp_save_post_revision' ) ) // > 3.6 behaviour
				remove_action( 'post_updated' , 'wp_save_post_revision' );
			else if ( has_action( 'pre_post_update' , 'wp_save_post_revision' ) ) 
				remove_action( 'pre_post_update' , 'wp_save_post_revision' );
		}
	}
	

	// --------------------------------------------------
	// general actions
	// --------------------------------------------------


	// translation ready.
	static function add_submitbox_action() {
		add_action('post_submitbox_misc_actions' , array(__CLASS__,'post_submitbox_revision_action'));
	}

}
RevisionOnDemand::init();

endif;
