<?php
/**
* @package RevisionRequest
* @version 1.0
*/




// ----------------------------------------
//	This class replaces WordPress' revisions-meta_box,
//	with one that allows you to delete and view revisions.
// ----------------------------------------
if ( !class_exists('RevisionController') ) :

class RevisionController {
	
	private static $del_msg_id;
	
	static function init() {
		// add meta box
		add_action( 'admin_init' , array(__CLASS__,'admin_init') );
	}

	static function admin_init( ) {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type , 'revisions' ) ) {
				remove_meta_box('revisionsdiv', $post_type , 'core');
				add_meta_box('revisioncontroller', __('Revisions'), array(__CLASS__,'post_revisions_meta_box'), $post_type , 'normal', 'core');
			}
		}
		add_filter( 'post_updated_messages' , array(__CLASS__ , 'set_messages' ) ) ;
		add_action( 'admin_action_delete-revision' , array( __CLASS__ , 'delete_revision' ) );
	}
	static function delete_revision(){
		$post_ID = $_REQUEST['post'];
		$revision_ID = $_REQUEST['revision'];
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'] , "delete-post_revision_$post_ID|$revision_ID" ) || ! current_user_can( 'delete_post', $post_ID ) )
			wp_die( __('You do not have permission to delete revisions.' , 'revisionrequest' ) );
			
		if ( $post_ID == wp_is_post_revision( $revision_ID ) )
			wp_delete_post_revision( $revision_ID );
			
		$url = remove_query_arg( array( '_wpnonce','revision' ) );
		$url = add_query_arg( array( 'action' => 'edit' , 'post' => $post_ID , 'message' => 999 ) , $url );
		wp_redirect( $url );
		exit();

	}
	
	static function set_messages( $messages ) {
		foreach ( get_post_types() as $post_type )
			$messages[ $post_type ][999] = __( 'Revision deleted.' , 'revisionrequest' );
		return $messages;
	}
	
	static function post_revisions_meta_box( $post ) {
		add_filter( 'revisioncontroller_actions' , array( __CLASS__ , 'remove_action_delete_autosafe' ) , 10 , 3 );
		self::post_revisions_list( $post );
	}
	static function remove_action_delete_autosafe( $actions , $post_ID , $revision_ID ) {
		if (wp_is_post_autosave( $revision_ID )) 
			unset( $actions['delete'] );
		return $actions;
	}
	static function post_revisions_list( $post = 0 , $args = null ) {
		global $wp_version;
		if ( !$post )
			return;

		$defaults = array( 'parent' => false, 'right' => false, 'left' => false, 'type' => 'all' );
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
		
		if ( !$revisions = wp_get_post_revisions( $post->ID ) )
			return;
	
		$titlef = _x( '%1$s by %2$s', 'post revision' );
		
		$rows = '';
		$class = false;
		$can_edit_post = current_user_can( 'edit_post', $post->ID );
		$can_delete_post = current_user_can( 'delete_post', $post->ID );
		foreach ( $revisions as $revision ) {
			if ( !current_user_can( 'read_post', $revision->ID ) )
				continue;
			if ( 'revision' === $type && wp_is_post_autosave( $revision ) )
				continue;
			$ID = $revision->post_name;
			$date = wp_post_revision_title( $revision );
			$name = get_the_author_meta( 'display_name', $revision->post_author );


			$class = $class ? '' : " class='alternate'";

			$actions = array();
			if ( $post->ID != $revision->ID && $can_edit_post ) { // restore
				if ( $wp_version >= '3.6' )
					$nonce_param = "restore-post_$revision->ID";
				else
					$nonce_param = "restore-post_$post->ID|$revision->ID";
				
				$actions['restore'] = '<a href="' . wp_nonce_url( add_query_arg( array( 'revision' => $revision->ID, 'action' => 'restore'  ), 'revision.php' ), $nonce_param ) . '">' . __( 'Restore' ) . '</a>';
			}
			if ( $post->ID != $revision->ID && $can_delete_post ) // restore
				$actions['delete'] = '<a class="delete-revision" href="' . wp_nonce_url( add_query_arg( array( 'post' => $post->ID , 'revision' => $revision->ID, 'action' => 'delete-revision' )  ), "delete-post_revision_$post->ID|$revision->ID" ) . '">' . __( 'Delete' ) . '</a>';
			
			$actions = implode( ' | ' , apply_filters( 'revisioncontroller_actions' , $actions , $post->ID , $revision->ID ) );
			$rows .= "<tr$class>\n";
			if ( $wp_version < '3.6' )
				$rows .= "\t<td>$ID</td>\n";
			$rows .= "\t<td>$date</td>\n";
			$rows .= "\t<td>$name</td>\n";
			$rows .= "\t<td class='action-links'>$actions</td>\n";
			$rows .= "</tr>\n";
		}
	do_action( 'revisioncontroller_before' , $post );
	?>
	<table class="widefat post-revisions" cellspacing="0" id="post-revisions">
		<?php if ( $wp_version < '3.6' ) { ?>
			<col />
		<?php } ?>
		<col style="width: 33%" />
		<col style="width: 33%" />
		<col style="width: 33%" />
	<thead>
	<tr>
		<?php if ( $wp_version < '3.6' ) { ?>
			<th scope="col"><?php _e( 'ID' ); ?></th>
		<?php } ?>
		<th scope="col"><?php /* translators: column name in revisions */ _ex( 'Date Created', 'revisions column name' ); ?></th>
		<th scope="col"><?php _e( 'Author' ); ?></th>
		<th scope="col" class="action-links"><?php _e( 'Actions' ); ?></th>
	</tr>
	</thead>
	<tbody>

	<?php echo $rows; ?>

	</tbody>
	</table>

	<?php

	do_action( 'revisioncontroller_after' , $post );

	}
	
	
	
	
	/*
	Delete post revision: delete_post_revision( $revision_id ); 
	
	*/
}

RevisionController::init();

endif;



?>