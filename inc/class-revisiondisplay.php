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
		// add revision note to post title / post_meta (this version s from ... goto current version)
		// do something with the permalinks
		add_filter( 'revisioncontroller_actions' , array( __CLASS__ , 'add_action_view' ) , 10 , 3 );
		add_action( 'wp' , array(__CLASS__,'prepare_display_revision')); // wp is the first hook we know $post
		add_filter('the_content',array( __CLASS__,'add_revision_select') );
	}
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
	function get_revision() {
		if ( ! isset( $_REQUEST['revision'] )  )
			return;

		$post = get_post();
		$revision_ID = intval($_REQUEST['revision']);

		if ( is_null( self::$revision ) && is_singular() && $post->ID == wp_is_post_revision( $revision_ID ) )
			self::$revision = get_post( $revision_ID );
		return self::$revision;
	}
	function revision_title( $title ) {
		if ( in_the_loop() && $revision = self::get_revision() )
			return $revision->post_title;
		return $title;
	}
	function revision_content( $content ) {
		if ( $revision = self::get_revision() )
			return $revision->post_content;
		return $content;
	}
	
	static function add_revision_select( $content ) {
		$post = get_post();
		if ( ! post_type_supports( $post->post_type,'revisions' ) || !$revisions = wp_get_post_revisions( $post->ID ) )
			return $content;
		
		$rows = '';
		$datef = _x( 'j F, Y @ G:i', 'revision date format');
		$post->post_title .= __(' (Current)');
		if ( isset($_REQUEST['revision']) ) 
			array_unshift( $revisions , $post );
		
		foreach ( $revisions as $revision ) {
			if (wp_is_post_autosave( $revision ) )
				continue;
			
			$permalink = self::get_revision_permalink( $post->ID , $revision->ID );
			$class = 'revision-permalink';
			
			if ( isset($_REQUEST['revision']) && $_REQUEST['revision'] == $revision->ID ) 
				$class .= ' selected';
			
			$rows .= sprintf( 
				apply_filters( 'revision_item_html' , '<li class="revision">%s</li>' , $post , $revision ) , 
				sprintf('<a class="%s" href="'.$permalink.'">' . __('%s as of %s'  ) . '</a>', $class , $revision->post_title , date_i18n( $datef, strtotime( $revision->post_modified ) )
			) );
		}
		
		if ( $rows )
			$content .= sprintf(  apply_filters('revision_container_html'  ,'<ul class="revisions">%s</ul>' , $post ) , $rows );
		
		return $content;
	}
	
	static function add_action_view( $actions , $post_ID , $revision_ID ) {
		$actions['view'] = '<a href="' . self::get_revision_permalink( $post_ID , $revision_ID ) . '">' . __( 'View' ) . '</a>';
		return $actions;
	}
	
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