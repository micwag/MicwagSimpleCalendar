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

		echo $this->generate_html( $dates );

		echo $args['after_widget'];
	}

	public function generate_html( $dates ) {
		$html = "<ol class=\"date-list\">";
		foreach ( $dates as $date ) {
			$id        = $date['id'];
			$beginning = $date['beginning'];
			$datetime  = date_create( $beginning );
			$title     = $date['title'];

			$html .= "<li>";
			$html .= "<time datetime=\"" . $beginning . "\">";
			$html .= date_format( $datetime, "d. " ) . $this->months[ intval( date_format( $datetime, 'm' ) ) ];
			$html .= "</time> ";
			$html .= $title;
			$html .= "</li>";
		}
		$html .= "</ol>";

		return $html;
	}

	public function form( $instance ) {

		// Title field
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Dates', 'micwag-simple-calendar' );
		}
		echo "<p>";
		echo "<label for='" . $this->get_field_id( 'title' );
		echo "'>" . __( 'Title', 'ranger-calendar' ) . "</label> ";

		echo "<input type='text' id='" . $this->get_field_id( 'title' );
		echo "' name='" . $this->get_field_name( 'title' );
		echo "' value='" . esc_attr( $title ) . "' />";
		echo "</p>";

		// Category field
		if ( isset( $instance['category'] ) ) {
			$category = $instance['category'];
		} else {
			$category = 0;
		}
		$categories = $this->calendar->get_categories();
		echo "<p>";
		echo "<label for='" . $this->get_field_id( 'category' );
		echo "'>" . __( 'Category', 'micwag-simple-calendar' ) . "</label> ";

		echo "<select name='" . $this->get_field_name( 'category' );
		echo "' id='" . $this->get_field_id( 'category' );
		echo "'>";

		echo "<option value='0'";
		if ( $instance['category'] == '0' ) {
			echo " selected";
		}
		echo ">" . __( 'None', 'micwag-simple-calendar' ) . "</option>";

		foreach ( $categories as $category ) {
			echo "<option value='" . $category['id'] . "'";
			if ( $category['id'] == $instance['category'] ) {
				echo " selected";
			}
			echo ">" . $category['name'] . "</option>";
		}

		echo "</select>";
		echo "</p>";
	}

	public function update( $new_instance, $old_instance ) {
		if ( is_array( $old_instance ) ) {
			$instance = $old_instance;
		} else {
			$instance = array();
		}

		if(isset($new_instance['title'])) {
			$instance['title'] = $new_instance['title'];
		} else {
			if(isset($old_instance['title'])) {
				$instance['title'] = $old_instance['title'];
			} else {
				$instance['title'] = '';
			}
		}

		if(isset($new_instance['category'])) {
			$instance['category'] = $new_instance['category'];
		} else {
			if(isset($old_instance['category'])) {
				$instance['category'] = $old_instance['category'];
			} else {
				$instance['category'] = '';
			}
		}

		return $instance;
	}

}

function registerMicwagSimpleCalendarWidgetList() {
	register_widget( 'MicwagSimpleCalendarWidgetList' );
}

add_action( 'widgets_init', 'registerMicwagSimpleCalendarWidgetList' );