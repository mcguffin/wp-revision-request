<?php
/**
* @package RevisionRequest
* @version 1.0
*/



// ----------------------------------------
//	This class allows you to display revisions on your blog.
// ----------------------------------------


if ( ! class_exists('RevisionDisplay') ) :
class RevisionDisplay {
	private static $revision = null;
	static function init() {
		// add revision selector the_content
		// add revision note to post title / post_meta (this version is from ... goto current version)
		// do something with the permalinks
		add_filter( 'revisioncontroller_actions' , array( __CLASS__ , 'add_action_view' ) , 10 , 3 );
		add_action( 'wp' , array(__CLASS__,'prepare_display_revision')); // wp is the first hook we know $post
		add_filter('the_content',array( __CLASS__,'add_revision_select') );
		add_action( 'revisioncontroller_before' , array( __CLASS__ , 'show_revision_checkbox') );
		add_action( 'post_updated' , array( __CLASS__ , 'update_post_meta' ) );
	}
	static function update_post_meta( $post_ID ) {
		$value = isset( $_POST['_revisionrequest_show_revisions_list'] ) ? (int) $_POST['_revisionrequest_show_revisions_list'] : 0;
		update_post_meta( $post_ID , '_revisionrequest_show_revisions_list' , $value );
	}
	
	static function show_revision_checkbox( $post ) {
		$show_revisions = get_post_meta($post->ID , '_revisionrequest_show_revisions_list' , true);
		?><p><?php
			?><input type="checkbox" name="_revisionrequest_show_revisions_list" id="show_post_revisions_checkbox" value="1" <?php checked($show_revisions,1,true) ?> /><?php
			?><label for="show_post_revisions_checkbox"> <?php
				_e( 'Show revision List below post content.' , 'revisionrequest');
			?></label>
		</p><?php
	}
	
	// -----------------------------------------
	//	Used to display a certain revision.
	// -----------------------------------------
	static function prepare_display_revision( $a ) {
		if ( ! isset( $_REQUEST['revision'] )  )
			return;
		
		global $post;
		
		$revision_ID = intval($_REQUEST['revision']);
		
		if ( is_singular() && $post->ID == wp_is_post_revision( $revision_ID ) ) {
			add_filter('the_title',array( __CLASS__,'revision_title')  , 0 , 1 );
			add_filter('the_content',array( __CLASS__,'revision_content')  , 0 , 1 );
		}
	}
	// -----------------------------------------
	//	get revision from $_request
	// -----------------------------------------
	function get_revision() {
		if ( ! isset( $_REQUEST['revision'] )  )
			return;

		$post = get_post();
		$revision_ID = intval($_REQUEST['revision']);

		if ( is_null( self::$revision ) && is_singular() && $post->ID == wp_is_post_revision( $revision_ID ) )
			self::$revision = get_post( $revision_ID );
		return self::$revision;
	}
	// -----------------------------------------
	//	the_title filter, returns revision's title instead of post title
	// -----------------------------------------
	function revision_title( $title ) {
		if ( in_the_loop() && $revision = self::get_revision() )
			return $revision->post_title;
		return $title;
	}
	// -----------------------------------------
	//	the_content filter, returns revision's content instead of post content
	// -----------------------------------------
	function revision_content( $content ) {
		if ( $revision = self::get_revision() )
			return $revision->post_content;
		return $content;
	}
	
	// -----------------------------------------
	//	the_content filter. Appends revision list and message to post content
	// -----------------------------------------
	static function add_revision_select( $content ) {
		$post = get_post();
		if ( ! is_singular()  || ! get_post_meta($post->ID , '_revisionrequest_show_revisions_list' , true ) || ! post_type_supports( $post->post_type,'revisions' ) || ! $revisions = wp_get_post_revisions( $post->ID ) )
			return $content;
		
		$rows = '';
		$datef = _x( 'j F, Y @ G:i', 'revision date format');
		if ( isset($_REQUEST['revision']) ) {
			$content .= 
				sprintf (  
					apply_filters( 'revision_message_html' , "<div class=\"message\">%s</div>" , $post ) ,
					sprintf( 
						__("You are viewing an old revision of this page. Click <a href='%s'>here</a> to go to the most recent version." , 'revisionrequest' ) , 
						get_permalink( $post->ID ) 
					) 
				);
		}
		foreach ( $revisions as $revision ) {
			if (wp_is_post_autosave( $revision ) )
				continue;
			
			$permalink = self::get_revision_permalink( $post->ID , $revision->ID );
			$class = 'revision-permalink';
			
			if ( isset($_REQUEST['revision']) && $_REQUEST['revision'] == $revision->ID ) 
				$class .= ' selected';
			
			$rows .= sprintf( 
				apply_filters( 'revision_item_html' , '<li class="revision">%s</li>' , $post , $revision ) , 
				sprintf('<a class="'.$class.'" href="'.$permalink.'">' . _x('%1$s as of %2$s' , 'Revision name, Revision date' , 'revisionrequest' ) . '</a>' , $revision->post_title , date_i18n( $datef, strtotime( $revision->post_modified ) )
			) );
		}
		
		if ( $rows )
			$content .= sprintf(  apply_filters('revision_container_html'  ,'<ul class="revisions">%s</ul>' , $post ) , $rows );
		
		return $content;
	}
	
	// -----------------------------------------
	//	the_content filter. Appends revision list and message to post content
	// -----------------------------------------
	static function add_action_view( $actions , $post_ID , $revision_ID ) {
		$actions['view'] = '<a href="' . self::get_revision_permalink( $post_ID , $revision_ID ) . '">' . __( 'View' ) . '</a>';
		return $actions;
	}
	
	// -----------------------------------------
	//	returns revision permalink.
	// -----------------------------------------
	static function get_revision_permalink( $post_ID , $revision_ID ) {
		$post_permalink = get_permalink( $post_ID );
		if ( $post_ID == $revision_ID )
			return $post_permalink;
		
		return add_query_arg( array('revision'=>$revision_ID) , $post_permalink );
	}
}
RevisionDisplay::init();
endif;




?>