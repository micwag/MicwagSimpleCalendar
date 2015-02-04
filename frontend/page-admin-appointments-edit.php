<?php
switch ( $_GET['action'] ) {
	case "add" :
		$this->page_header( __( 'Add Date', 'micwag-simple-calendar' ) );
		if ( ! isset( $_POST['edit_type'] ) ) {
			//Print form
			$this->print_form_date( "add" );
		}
		$this->page_footer();
		break;

	case "edit" :
		if ( ! isset( $_POST['edit_type'] ) ) {
			$this->page_header( __( 'Edit Date', 'micwag-simple-calendar' ) );

			$this->print_form_date( "edit", $_GET['id'] );

			$this->page_footer();
		}
		break;

	case "delete" :
		if ( ! isset( $_GET['id'] ) ) {
			echo __( 'Missing id', 'micwag-simple-calendar' );
		}
		break;

	default :
		break;
}
$this->page_footer();