<?php

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

		if ( ! $category ) {
			$category = 0;
		}

		$dates       = $this->calendar->get_future_dates( "beginning ASC", 5 );
		$dates_final = array();
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
	 * @param       $dates
	 *
	 * @return string
	 */
	public function generate_html( $instance, $dates ) {
		$appointmentsContent = "";
		if ( isset( $instance['appointmentContent'] ) ) {
			$appointmentMarkup = $instance['appointmentContent'];
		} else {
			$appointmentMarkup = '<article><h1><a href="%appointment_permalink%">%appointment_title%</a></h1></article>';
		}

		foreach ( $dates as $date ) {
			$appointmentContent = $appointmentMarkup;
			$appointmentContent = str_replace( '%appointment_title%', $date['title'], $appointmentContent );
			$appointmentContent = str_replace( '%appointment_id%', $date['id'], $appointmentContent );
			$appointmentContent = str_replace( '%appointment_beginning%', $date['beginning'], $appointmentContent );
			$appointmentContent = str_replace( '%appointment_end%', $date['end'], $appointmentContent );
			$appointmentContent = str_replace( '%appointment_description%', $date['description'], $appointmentContent );
			$appointmentContent = str_replace( '%appointment_location%', $date['location'], $appointmentContent );

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
		echo '">' . __( 'Title', 'ranger-calendar' ) . '</label>';

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