<?php

use Carbon\Carbon;

class MicwagSimpleCalendarWidgetList extends WP_Widget {
	protected $calendar;
	protected $months;

	public function __construct() {
		parent::__construct( '', __( 'Micwag Simple Calendar Next Events', 'micwag-simple-calendar' ),
			array(
				'classname'   => 'MicwagSimpleCalendarWidgetList',
				'description' => __( 'Displays the calendar as a list.', 'micwag-simple-calendar' ),
			) );
		$this->calendar = new MicwagSimpleCalendar();
		$this->months   = array(
			1  => __( 'January' ),
			2  => __( 'February' ),
			3  => __( 'March' ),
			4  => __( 'April' ),
			5  => __( 'May' ),
			6  => __( 'June' ),
			7  => __( 'July' ),
			8  => __( 'August' ),
			9  => __( 'September' ),
			10 => __( 'October' ),
			11 => __( 'November' ),
			12 => __( 'December' )
		);
	}

	/**
	 * Displays the widget's content
	 */
	function widget( $args, $instance ) {
		if ( $instance['title'] == '' ) {
			$instance['title'] = __( 'Dates', 'micwag-simple-calendar' );
		}

		$title    = apply_filters( 'widget_title', $instance['title'] );
		$category = $instance['category'];

		echo $args['before_widget'];

		echo $args['before_title'] . $title . $args['after_title'];

		if ( ! isset( $category ) ) {
			$category = 0;
		}

		$dates       = $this->calendar->get_upcoming_appointments( "beginning ASC", 5 );
		$dates_final = array();

		// Keep only dates from the selected category
		if ( $category != 0 ) {
			foreach ( $dates as $date ) {
				if ( $date['category'] == $category ) {
					$dates_final[] = $date;
				}
			}
		} else {
			$dates_final = $dates;
		}

		$appointmentsContent = $this->generate_html( $instance, $dates_final );

		if ( isset( $instance['widgetContent'] ) ) {
			$widgetContent = $instance['widgetContent'];
		} else {
			$widgetContent = '%appointments%';
		}

		$widgetContent = str_replace( '%appointments%', $appointmentsContent, $widgetContent );
		echo $widgetContent;

		echo $args['after_widget'];
	}

	/**
	 * Returns the markup for the appointments
	 *
	 * @param array $instance
	 * @param       $appointments
	 *
	 * @return string
	 */
	public function generate_html( $instance, $appointments ) {
		if ( isset( $instance['dateFormat'] ) && ! is_null( $instance['dateFormat'] ) ) {
			$dateFormat = $instance['dateFormat'];
		} else {
			$dateFormat = get_option( 'date_format' ) . ' H:i';
		}

		$appointmentsContent = "";
		if ( isset( $instance['appointmentContent'] ) ) {
			$appointmentMarkup = $instance['appointmentContent'];
		} else {
			$appointmentMarkup = '<article><h1><a href="%appointment_permalink%">%appointment_title%</a></h1></article>';
		}

		$timezone = get_option( 'timezone_string' );
		if ( $timezone == '' ) {
			$timezone = 'Europe/Berlin';
		}

		foreach ( $appointments as $appointment ) {
			$appointmentTitle           = isset( $appointment['title'] ) ? $appointment['title'] : '';
			$appointmentId              = isset( $appointment['id'] ) ? $appointment['id'] : 0;
			$appointmentDescription     = isset( $appointment['description'] ) ? $appointment['description'] : '';
			$appointmentLocation        = isset( $appointment['location'] ) ? $appointment['location'] : '';
			$appointmentBeginning       = isset( $appointment['beginning'] ) ?
				Carbon::createFromFormat( 'Y-m-d H:i:s', $appointment['beginning'], $timezone ) : null;
			$appointmentEnd             = isset( $appointment['end'] ) ?
				Carbon::createFromFormat( 'Y-m-d H:i:s', $appointment['end'], $timezone ) : null;
			$appointmentBeginningHtml   = isset( $appointmentBeginning ) ? $appointmentBeginning->toW3cString() : '';
			$appointmentEndHtml         = isset( $appointmentEnd ) ? $appointmentEnd->toW3cString() : '';
			$appointmentBeginningYear   = isset( $appointmentBeginning ) ? $appointmentBeginning->year : '';
			$appointmentBeginningMonth  = isset( $appointmentBeginning ) ? $appointmentBeginning->month : '';
			$appointmentBeginningDay    = isset( $appointmentBeginning ) ? $appointmentBeginning->day : '';
			$appointmentBeginningHour   = isset( $appointmentBeginning ) ? $appointmentBeginning->hour : '';
			$appointmentBeginningMinute = isset( $appointmentBeginning ) ? $appointmentBeginning->minute : '';
			$appointmentBeginningSecond = isset( $appointmentBeginning ) ? $appointmentBeginning->second : '';
			$appointmentEndYear         = isset( $appointmentEnd ) ? $appointmentEnd->year : '';
			$appointmentEndMonth        = isset( $appointmentEnd ) ? $appointmentEnd->month : '';
			$appointmentEndDay          = isset( $appointmentEnd ) ? $appointmentEnd->day : '';
			$appointmentEndHour         = isset( $appointmentEnd ) ? $appointmentEnd->hour : '';
			$appointmentEndMinute       = isset( $appointmentEnd ) ? $appointmentEnd->minute : '';
			$appointmentEndSecond       = isset( $appointmentEnd ) ? $appointmentEnd->second : '';

			$beginningString = $appointmentBeginning->format( $dateFormat );
			$beginningString = str_replace( 'January', __( 'January' ), $beginningString );
			$beginningString = str_replace( 'February', __( 'February' ), $beginningString );
			$beginningString = str_replace( 'March', __( 'March' ), $beginningString );
			$beginningString = str_replace( 'April', __( 'April' ), $beginningString );
			$beginningString = str_replace( 'June', __( 'June' ), $beginningString );
			$beginningString = str_replace( 'July', __( 'July' ), $beginningString );
			$beginningString = str_replace( 'August', __( 'August' ), $beginningString );
			$beginningString = str_replace( 'October', __( 'October' ), $beginningString );
			$beginningString = str_replace( 'November', __( 'November' ), $beginningString );
			$beginningString = str_replace( 'December', __( 'December' ), $beginningString );

			$endString = $appointmentBeginning->format( $dateFormat );
			$endString = str_replace( 'January', __( 'January' ), $endString );
			$endString = str_replace( 'February', __( 'February' ), $endString );
			$endString = str_replace( 'March', __( 'March' ), $endString );
			$endString = str_replace( 'April', __( 'April' ), $endString );
			$endString = str_replace( 'June', __( 'June' ), $endString );
			$endString = str_replace( 'July', __( 'July' ), $endString );
			$endString = str_replace( 'August', __( 'August' ), $endString );
			$endString = str_replace( 'October', __( 'October' ), $endString );
			$endString = str_replace( 'November', __( 'November' ), $endString );
			$endString = str_replace( 'December', __( 'December' ), $endString );

			$appointmentContent = $appointmentMarkup;
			$appointmentContent = str_replace( '%appointment_title%', $appointmentTitle, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_id%', $appointmentId, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning%', $beginningString, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_end%', $endString, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_description%', $appointmentDescription, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_location%', $appointmentLocation, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_html%', $appointmentBeginningHtml, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_html%', $appointmentEndHtml, $appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_year%', $appointmentBeginningYear,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_month%', $appointmentBeginningMonth,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_day%', $appointmentBeginningDay,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_hour%', $appointmentBeginningHour,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_minute%', $appointmentBeginningMinute,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning_second%', $appointmentBeginningSecond,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_year%', $appointmentEndYear,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_month%', $appointmentEndMonth,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_day%', $appointmentEndDay,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_hour%', $appointmentEndHour,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_minute%', $appointmentEndMinute,
				$appointmentContent );
			$appointmentContent = str_replace( '%appointment_end_second%', $appointmentEndSecond,
				$appointmentContent );

			$appointmentsContent .= $appointmentContent;
		}

		return $appointmentsContent;
	}

	public function form( $instance ) {
		// Title
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Dates', 'micwag-simple-calendar' );
		}
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'title' );
		echo '">' . __( 'Title', 'micwag-simple-calendar' ) . '</label>';

		echo '<input class="widefat" type="text" id="' . $this->get_field_id( 'title' );
		echo '" name="' . $this->get_field_name( 'title' );
		echo '" value="' . esc_attr( $title ) . '" />';
		echo "</p>";

		// Category
		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else {
			$category = 0;
		}
		$categories = $this->calendar->get_categories();
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'category' );
		echo '">' . __( 'Category', 'micwag-simple-calendar' ) . '</label>';

		echo '<select class="widefat" name="' . $this->get_field_name( 'category' );
		echo '" id="' . $this->get_field_id( 'category' );
		echo '">';

		echo '<option value="0"';
		if ( $instance['category'] == '0' ) {
			echo ' selected';
		}
		echo '>' . __( 'None', 'micwag-simple-calendar' ) . '</option>';

		foreach ( $categories as $category ) {
			echo '<option value="' . $category['id'] . '"';
			if ( $category['id'] == $instance['category'] ) {
				echo ' selected';
			}
			echo '>' . $category['name'] . '</option>';
		}

		echo '</select>';
		echo '</p>';

		// Date format
		if ( isset( $instance['dateFormat'] ) ) {
			$dateFormat = $instance['dateFormat'];
		} else {
			$dateFormat = get_option( 'date_format' ) . ' H:i';
		}
		echo '<p>';
		echo '<label for="' . $this->get_field_id( 'dateFormat' );
		echo '">' . __( 'Date format', 'ranger-calendar' ) . '</label>';

		echo '<input class="widefat" type="text" id="' . $this->get_field_id( 'dateFormat' );
		echo '" name="' . $this->get_field_name( 'dateFormat' );
		echo '" value="' . esc_attr( $dateFormat ) . '" />';
		echo "</p>";

		// Widget Content
		if ( isset( $instance['widgetContent'] ) ) {
			$widgetContent = $instance['widgetContent'];
		} else {
			$widgetContent = '%appointments%';
		}
		echo '<p><label for="' . $this->get_field_id( 'widgetContent' ) . '">' . __( 'Widget content',
				'wp-last-posts-widget' ) . ': </label>';
		echo '<textarea class="widefat" id="' . $this->get_field_id( 'widgetContent' ) . '" name="'
		     . $this->get_field_name( 'widgetContent' ) . '">' . esc_attr( $widgetContent ) . '</textarea></p>';

		// Appointment Content
		if ( isset( $instance['appointmentContent'] ) ) {
			$appointmentContent = $instance['appointmentContent'];
		} else {
			$appointmentContent = '<article><h1><a href="%appointment_permalink%">%appointment_title%</a></h1></article>';
		}
		echo '<p><label for="' . $this->get_field_id( 'appointmentContent' ) . '">' . __( 'Appointment content',
				'wp-last-posts-widget' ) . ': </label>';
		echo '<textarea class="widefat" id="' . $this->get_field_id( 'appointmentContent' ) . '" name="'
		     . $this->get_field_name( 'appointmentContent' ) . '">' . esc_attr( $appointmentContent )
		     . '</textarea></p>';
	}

	public function update( $new_instance, $old_instance ) {
		if ( is_array( $old_instance ) ) {
			$instance = $old_instance;
		} else {
			$instance = array();
		}

		if ( isset( $new_instance['title'] ) ) {
			$instance['title'] = $new_instance['title'];
		} else {
			if ( isset( $old_instance['title'] ) ) {
				$instance['title'] = $old_instance['title'];
			} else {
				$instance['title'] = '';
			}
		}

		if ( isset( $new_instance['category'] ) ) {
			$instance['category'] = $new_instance['category'];
		} else {
			if ( isset( $old_instance['category'] ) ) {
				$instance['category'] = $old_instance['category'];
			} else {
				$instance['category'] = '';
			}
		}

		if ( isset( $new_instance['dateFormat'] ) ) {
			$instance['dateFormat'] = $new_instance['dateFormat'];
		} else {
			if ( isset( $old_instance['dateFormat'] ) ) {
				$instance['dateFormat'] = $old_instance['dateFormat'];
			} else {
				$instance['dateFormat'] = get_option( 'date_format' ) . ' H:i';
			}
		}

		if ( isset( $new_instance['widgetContent'] ) ) {
			$instance['widgetContent'] = $new_instance['widgetContent'];
		} else {
			if ( isset( $old_instance['widgetContent'] ) ) {
				$instance['widgetContent'] = $old_instance['widgetContent'];
			} else {
				$instance['widgetContent'] = '';
			}
		}

		if ( isset( $new_instance['appointmentContent'] ) ) {
			$instance['appointmentContent'] = $new_instance['appointmentContent'];
		} else {
			if ( isset( $old_instance['appointmentContent'] ) ) {
				$instance['appointmentContent'] = $old_instance['appointmentContent'];
			} else {
				$instance['appointmentContent'] = '';
			}
		}

		return $instance;
	}

}

function registerMicwagSimpleCalendarWidgetList() {
	register_widget( 'MicwagSimpleCalendarWidgetList' );
}

add_action( 'widgets_init', 'registerMicwagSimpleCalendarWidgetList' );