<?php
$this->page_header( __( 'Calendar-Overview', 'micwag-simple-calendar' ) );

echo "<h3>";
echo __( 'Upcoming appointments', 'micwag-simple-calendar' );
echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=add\" class=\"add-new-h2\">"
     . __( 'Add', 'micwag-simple-calendar' ) . "</a>";
echo "</h3>";

$appointments = $this->calendar->get_upcoming_appointments();

if ( count( $appointments ) === 0 ) {
	echo "<p>";
	echo __( 'No dates found.', 'micwag-simple-calendar' );
	echo "</p>";
} else {
	echo "<table class=\"widefat\">";
	echo "
	<thead>
	<tr>
		<th>
			" . __( 'Id', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Title', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Beginning', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'End', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Full Day', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Category', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Actions', 'micwag-simple-calendar' ) . "
		</th>
	</tr>
	</thead>";
	echo "<tbody>";
	foreach ( $appointments as $appointment ) {
		echo "<tr>";

		echo "<td>";
		echo $appointment['id'];
		echo "</td>";

		echo "<td><strong>";
		echo $appointment['title'];
		echo "</strong></td>";

		echo "<td>";
		echo $appointment['beginning'];
		echo "</td>";

		echo "<td>";
		echo $appointment['end'];
		echo "</td>";

		echo "<td>";
		if ( $appointment['full_day'] ) {
			echo __( 'Yes', 'micwag-simple-calendar' );
		} else {
			echo __( 'No', 'micwag-simple-calendar' );
		}
		echo "</td>";

		$category = $this->calendar->get_category( $appointment['category'] );
		echo "<td style=\"color: #";
		if ( $category['id'] != strval( 0 ) ) {
			echo $category['color'];
		} else {
			echo "000000";
		}
		echo "\">";
		if ( $category['id'] != strval( 0 ) ) {
			echo $category['name'];
		} else {
			echo __( "None", 'micwag-simple-calendar' );
		}
		echo "</td>";

		echo "<td>";
		echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=edit&id="
		     . $appointment['id']
		     . "\" title=\"\"><span class=\"dashicons dashicons-edit\"></span></a>";
		echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=delete&id="
		     . $appointment['id']
		     . "\" title=\"\"><span class=\"dashicons dashicons-trash\"></span></a>";
		echo "</td>";

		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";


	echo "<h3>";
	echo __( 'Elapsed appointments', 'micwag-simple-calendar' );
	echo "</h3>";

	$appointments = $this->calendar->get_elapsed_appointments();

	if ( count( $appointments ) === 0 ) {
		echo "<p>";
		echo __( 'No dates found.', 'micwag-simple-calendar' );
		echo "</p>";
	} else {
		echo "<table class=\"widefat\">";
		echo "
	<thead>
	<tr>
		<th>
			" . __( 'Id', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Title', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Beginning', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'End', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Full Day', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Category', 'micwag-simple-calendar' ) . "
		</th>
		<th>
			" . __( 'Actions', 'micwag-simple-calendar' ) . "
		</th>
	</tr>
	</thead>";
		echo "<tbody>";
		foreach ( $appointments as $appointment ) {
			echo "<tr>";

			echo "<td>";
			echo $appointment['id'];
			echo "</td>";

			echo "<td><strong>";
			echo $appointment['title'];
			echo "</strong></td>";

			echo "<td>";
			echo $appointment['beginning'];
			echo "</td>";

			echo "<td>";
			echo $appointment['end'];
			echo "</td>";

			echo "<td>";
			if ( $appointment['full_day'] ) {
				echo __( 'Yes', 'micwag-simple-calendar' );
			} else {
				echo __( 'No', 'micwag-simple-calendar' );
			}
			echo "</td>";

			$category = $this->calendar->get_category( $appointment['category'] );
			echo "<td style=\"color: #";
			if ( $category['id'] != strval( 0 ) ) {
				echo $category['color'];
			} else {
				echo "000000";
			}
			echo "\">";
			if ( $category['id'] != strval( 0 ) ) {
				echo $category['name'];
			} else {
				echo __( "None", 'micwag-simple-calendar' );
			}
			echo "</td>";

			echo "<td>";
			echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=edit&id="
			     . $appointment['id']
			     . "\" title=\"\"><span class=\"dashicons dashicons-edit\"></span></a>";
			echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=delete&id="
			     . $appointment['id']
			     . "\" title=\"\"><span class=\"dashicons dashicons-trash\"></span></a>";
			echo "</td>";

			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}

	$this->page_footer();
}