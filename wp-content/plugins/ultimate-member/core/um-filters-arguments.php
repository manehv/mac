<?php

	/***
	***	@conditional logout form
	***/
	add_filter('um_shortcode_args_filter', 'um_display_logout_form', 99);
	function um_display_logout_form( $args ) {
		global $ultimatemember;

		if ( is_user_logged_in() && isset( $args['mode'] ) && $args['mode'] == 'login' ) {
			
			if ( get_current_user_id() != um_user('ID' ) ) {
				um_fetch_user( get_current_user_id() );
			}
			
			$args['template'] = 'logout';
		
		}
		
		return $args;
		
	}
	
	/***
	***	@filter for shortcode args
	***/
	add_filter('um_shortcode_args_filter', 'um_shortcode_args_filter', 99);
	function um_shortcode_args_filter( $args ) {
		global $ultimatemember;

		if ($ultimatemember->shortcodes->message_mode == true) {
			$args['template'] = 'message';
			$ultimatemember->shortcodes->custom_message = um_user( um_user('status')  . '_message' );
			um_reset_user();
		}
		
		foreach( $args as $k => $v ) {
			if ( $ultimatemember->validation->is_serialized( $args[$k] ) ) {
				if ( !empty( $args[$k] ) ) {
					$args[$k] = unserialize( $args[$k] );
				}
			}
		}
		
		return $args;
		
	}