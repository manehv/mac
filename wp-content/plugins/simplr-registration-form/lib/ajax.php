<?php
//ajax handlers
class Simplr_Reg_Ajax {
	public $instance;
	
	function __construct( $args = array() ) {
		if( !isset( $_REQUEST['sreg-action'] ) ) return false;

		$action = $_REQUEST['sreg-action'];
		if( !method_exists( $this, $action ) ) die("Could not find sreg method.");

		call_user_func_array( array( $this, $action) , $args  );
	}

	static function instance( $args ) {
		if( is_object( $this->instance ) ) return $this->instance; 

		$this->instance = new Simplr_Reg_Ajax( $args ); 
		return $this->instance;
	}

	public function resend_email() {
		echo 'test';
	}	

}
