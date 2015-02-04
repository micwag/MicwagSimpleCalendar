<?php
$this->page_header( __( 'Categories', 'micwag-simple-calendar' ) );

echo "<p>";
echo "<a href=\"admin.php?page=micwag-simple-calendar-category-edit.php&action=add\" class=\"add-new-h2\">"
     . __( 'Add', 'micwag-simple-calendar' ) . "</a>";
echo "</p>";

$calendar   = new MicwagSimpleCalendar();
$categories = $calendar->get_categories();

echo "<table class=\"widefat\">";
echo "<thead>";
echo "<th>" . __( 'Id', 'micwag-simple-calendar' ) . "</th>";
echo "<th>" . __( 'Name', 'micwag-simple-calendar' ) . "</th>";
echo "<th>" . __( 'Color', 'micwag-simple-calendar' ) . "</th>";
echo "<th>" . __( 'Actions', 'micwag-simple-calendar' ) . "</th>";
echo "</tr>";
echo "</thead>";
foreach ( $categories as $category ) {
	echo "<tr>";
	echo "<td>";
	echo $category['id'];
	echo "</td>";
	echo "<td>";
	echo $category['name'];
	echo "</td>";
	echo "<td style=\"color: #";
	echo $category['color'];
	echo "\">";
	echo $category['color'];
	echo "</td>";
	echo "<td>";
	echo "<a href=\"admin.php?page=micwag-simple-calendar-category-edit.php&action=edit&id="
	     . $category['id']
	     . "\" title=\"\"><span class=\"dashicons dashicons-edit\"></span></a>";
	echo "<a href=\"admin.php?page=micwag-simple-calendar-category-edit.php&action=delete&id="
	     . $category['id']
	     . "\" title=\"\"><span class=\"dashicons dashicons-trash\"></span></a>";
	echo "</td>";
	echo "</tr>";
}

echo "</table>";

$this->page_footer();