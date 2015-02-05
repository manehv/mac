<?php

/**
* Filter the user views list for inactives
*	@params $views (string) 
*/
if(!function_exists('simplr_views_users')):
	function simplr_views_users( $views ) {
		$class = (@$_GET['view_inactive'] == true) ? 'current':'';
		$views['view_inactive'] = '<a href="'.add_query_arg(array('view_inactive' => 'true')).'" class="'.$class.'" >Inactive Users ('.simplr_count_inactive().')</a>';
		return $views;
	}
endif;

if(!function_exists('simplr_count_inactive')):
	function simplr_count_inactive() {
		if( !$count = wp_cache_get('inactive_count','users') ) {
			global $wpdb;
			$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->users WHERE user_status = 2", array()));
			wp_cache_set('inactivate_count', $count,'users', 3600);
		}
		return $count; 
	}
endif;

if(!function_exists('simplr_inactive_query')):
	function simplr_inactive_query( &$query ) {
		if(@$_GET['view_inactive'] === 'true') {
			$query->query_where = $query->query_where.' AND user_status = 2';
		}
		return $query;
	}	
endif;

if(!function_exists("simplr_users_bulk_action")):
	function simplr_users_bulk_action($actions) {
		if(@$_GET['view_inactive'] === 'true') {
			$actions['activate'] = 'Activate Users';
		}
		return $actions;
	}
endif;

if( !function_exists('simplr_resend_email') ) {
	function simplr_resend_email( $id ) {
		global $simplr_options,$blog_id;
	 	$data = (array) get_userdata( $id );
                $data = (array) $data['data'] ;
		$data['blogname'] = get_option('blogname');
		$subj = simplr_token_replace( $simplr_options->mod_email_subj, $data );
		$content = simplr_token_replace( $simplr_options->mod_email, $data );
		$content = simplr_token_replace( $content, array('link' => get_home_url( $blog_id, '/?activation_key='.$data['user_activation_key'] ) ) );
		$headers = "From: ".$data['blogname']." <".get_option('admin_email')."> \r\n";
		wp_mail( $data['user_email'], $subj, $content, $headers);
	}
}
