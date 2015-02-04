<?php
switch ( $_GET['action'] ) {
	case "add" :
		if ( ! isset( $_POST['edit_type'] ) ) {
			// Print form
			$this->page_header( __( "Add Category", 'micwag-simple-calendar' ) );
			$this->print_form_category( "add" );
			$this->page_footer();
		} else {
			// Add to database
			$calendar = new MicwagSimpleCalendar();
			$title    = $_POST['category_name'];
			$color    = $_POST['category_color'];
			$color    = substr( $color, 1, 6 );
			if ( $calendar->add_category( array(
				'name'  => $title,
				'color' => $color
			) )
			) {
				// Added successful
				add_action( 'template_redirect', array( get_class( $this ), 'redirectToDasboard' ) );
			} else {
				// Error
				add_action( 'template_redirect', array(
					get_class( $this ),
					'redirectToDasboardError'
				) );
			}
		}
		break;
	case "edit" :
		if ( isset( $_GET['id'] ) ) {
			if ( ! isset( $_POST['edit_type'] ) ) {
				// Print form
				$this->page_header( __( "Edit Category", 'micwag-simple-calendar' ) );
				$this->print_form_category( "edit", $_GET['id'] );
				$this->page_footer();
			} else {
				// Update database
				$calendar = new MicwagSimpleCalendar();
				$title    = $_POST['category_name'];
				$color    = $_POST['categoy_color'];
				$color    = substr( $color, 1, 6 );
				if ( $calendar->update_category( $_GET['id'], array(
					"name"  => $title,
					"color" => $color // TODO: Farbe wird nicht?? gespeichert
				) )
				) {
					// Added successful
					add_action( 'template_redirect', array(
						get_class( $this ),
						'redirectToDasboardUpdated'
					) );
				} else {
					// Error
					add_action( 'template_redirect', array(
						get_class( $this ),
						'redirectToDasboardError'
					) );
				}
			}
		} else {
			// No id defines -> Error
			add_action( 'template_redirect', array( get_class( $this ), 'redirectToDasboardError' ) );
			die();
		}
		break;
	case "delete" :
		if ( isset( $_GET['id'] ) ) {
			$id       = $_GET['id'];
			$id       = mysql_real_escape_string( $id );
			$calendar = new MicwagSimpleCalendar();
			if ( $calendar->delete_category( $id ) ) {
				add_action( 'template_redirect', array(
					get_class( $this ),
					'redirectToDasboardDeleted'
				) );
			}
		} else {
			echo __( 'Missing id', 'micwag-simple-calendar' );
		}
		break;
	default :
		die( 'Invalid request' );
		break;
}