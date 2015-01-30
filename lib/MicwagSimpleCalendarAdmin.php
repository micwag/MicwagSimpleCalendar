<?php

/**
 *
 */
final class MicwagSimpleCalendarAdmin {
	private $calendar;
	private $pageTitleGeneral;

	/**
	 * Initializes class attributes
	 */
	public function __construct() {
		$this->calendar         = new MicwagSimpleCalendar();
		$this->pageTitleGeneral = __( 'Calendar', 'micwag-simple-calendar' );

		add_action( 'admin_menu', array(
			&$this,
			'append_menu'
		) );
	}

	public function append_menu() {
		add_menu_page( __( 'Calendar' ), __( 'Calendar', 'micwag-simple-calendar' ),
			'edit_pages', 'micwag-simple-calendar-dashboard.php', array( $this, 'page_dashboard' ),
			'dashicons-calendar', 50 );

		add_submenu_page( 'micwag-simple-calendar-dashboard.php', __( 'Categories', 'micwag-simple-calendar' ),
			__( 'Categories', 'micwag-simple-calendar' ), 'edit_pages', 'micwag-simple-calendar-categories.php',
			array( $this, 'page_categories' ) );

		add_submenu_page( null, __( 'Edit Date', 'micwag-simple-calendar' ),
			__( 'Edit Date', 'micwag-simple-calendar' ), 'edit_pages', 'micwag-simple-calendar-date-edit.php',
			array( $this, 'page_edit_date' ) );

		add_submenu_page( null, __( 'Edit Category', 'micwag-simple-calendar' ),
			__( 'Edit Category', 'micwag-simple-calendar' ), 'edit_pages', 'micwag-simple-calendar-category-edit.php',
			array( $this, 'page_edit_category' ) );
	}

	/**
	 * Prints page header and title
	 *
	 * @param String title
	 *
	 * @return void
	 */
	private function page_header( $title = null ) {
		echo "<div class=\"wrap\">";
		if ( isset( $_GET['message'] ) ) {
			switch ( $_GET['message'] ) {
				case "added" :
					echo "<div class=\"message message-added\">"
					     . __( 'Item added successful.', 'micwag-simple-calendar' )
					     . "</div>";
					break;
				case "deleted" :
					echo "<div class=\"message message-deleted\">"
					     . __( 'Item deleted successful.', 'micwag-simple-calendar' )
					     . "</div>";
					break;
				case "updated" :
					echo "<div class=\"message message-updated\">"
					     . __( 'Item updated successful.', 'micwag-simple-calendar' )
					     . "</div>";
					break;
				case "error" :
					echo "<div class=\"message message-error\">" . __( 'An error occured.', 'micwag-simple-calendar' )
					     . "</div>";
					break;
			}
		}

		echo "<h2>";
		if ( is_null( $title ) ) {
			echo $this->pageTitleGeneral;
		} else {
			echo $title;
		}
		echo "</h2>";
	}

	/**
	 * Prints page footer
	 *
	 * @return void
	 */
	private function page_footer() {
		echo "</div>";
	}

	public function page_dashboard() {
		if ( current_user_can( 'edit_pages', 'micwag-simple-calendar' ) ) {
			$this->page_header( __( 'Calendar-Overview', 'micwag-simple-calendar' ) );

			echo "<h3>";
			echo __( 'Current dates', 'micwag-simple-calendar' );
			echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=add\" class=\"add-new-h2\">"
			     . __( 'Add', 'micwag-simple-calendar' ) . "</a>";
			echo "</h3>";

			$dates = $this->calendar->get_all_dates();

			if ( count( $dates ) === 0 ) {
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
				foreach ( $dates as $date ) {
					echo "<tr>";

					echo "<td>";
					echo $date['id'];
					echo "</td>";

					echo "<td><strong>";
					echo $date['title'];
					echo "</strong></td>";

					echo "<td>";
					echo $date['beginning'];
					echo "</td>";

					echo "<td>";
					echo $date['end'];
					echo "</td>";

					echo "<td>";
					if ( $date['full_day'] ) {
						echo __( 'Yes', 'micwag-simple-calendar' );
					} else {
						echo __( 'No', 'micwag-simple-calendar' );
					}
					echo "</td>";

					$category = $this->calendar->get_category( $date['category'] );
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
					echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=edit&id=" . $date['id']
					     . "\" title=\"\"><span class=\"dashicons dashicons-edit\"></span></a>";
					echo "<a href=\"admin.php?page=micwag-simple-calendar-date-edit.php&action=delete&id=" . $date['id']
					     . "\" title=\"\"><span class=\"dashicons dashicons-trash\"></span></a>";
					echo "</td>";

					echo "</tr>";
				}
				echo "</tbody>";
				echo "</table>";
			}

			$this->page_footer();
		} else {
			echo __( 'Permission denied', 'micwag-simple-calendar' );
		}
	}

	public function page_categories() {
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
		echo "<th>" . __( 'Icon', 'micwag-simple-calendar' ) . "</th>";
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
			echo $category['icon'];
			echo "</td>";
			echo "<td>";
			echo "<a href=\"admin.php?page=micwag-simple-calendar-category-edit.php&action=edit&id=" . $category['id']
			     . "\" title=\"\"><span class=\"dashicons dashicons-edit\"></span></a>";
			echo "<a href=\"admin.php?page=micwag-simple-calendar-category-edit.php&action=delete&id=" . $category['id']
			     . "\" title=\"\"><span class=\"dashicons dashicons-trash\"></span></a>";
			echo "</td>";
			echo "</tr>";
		}

		echo "</table>";

		$this->page_footer();
	}

	public function page_edit_category() {
		if ( current_user_can( 'edit_pages' ) ) {
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
							header( 'location: ' . network_site_url( '/' )
							        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php&message=added' );
						} else {
							// Error
							header( 'location: ' . network_site_url( '/' )
							        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php&message=error' );
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
								header( 'location: ' . network_site_url( '/' )
								        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php&message=updated' );
							} else {
								// Error
								header( 'location: ' . network_site_url( '/' )
								        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php&message=error' );
							}
						}
					} else {
						// No id defines -> Error
						header( 'location: ' . network_site_url( '/' )
						        . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php&message=error' );
						die();
					}
					break;
				case "delete" :
					if ( isset( $_GET['id'] ) ) {
						$id       = $_GET['id'];
						$id       = mysql_real_escape_string( $id );
						$calendar = new MicwagSimpleCalendar();
						if ( $calendar->delete_category( $id ) ) {
							header( 'location: ' . network_site_url( '/' )
							        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php&message=deleted' );
						}
					} else {
						echo __( 'Missing id', 'micwag-simple-calendar' );
					}
					break;
				default :
					header( 'location: ' . network_site_url( '/' )
					        . 'wp-admin/admin.php?page=micwag-simple-calendar-categories.php' );
					break;
			}
		} else {
			die( "No permission" );
		}
	}

	public function page_edit_date() {
		if ( current_user_can( 'edit_pages' ) ) {
			if ( ! isset( $_GET['action'] ) ) {
				wp_safe_redirect( network_site_url( '/' )
				                  . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php' );
				exit;
			} else {
				switch ( $_GET['action'] ) {

					case "add" :
						$this->page_header( __( 'Add Date', 'micwag-simple-calendar' ) );
						if ( ! isset( $_POST['edit_type'] ) ) {
							//Print form
							$this->print_form_date( "add" );
						} else {
							//Add date to database
							if ( $_POST['edit_type'] == 'add' ) {
								$calendar = new MicwagSimpleCalendar();
								if ( isset( $_POST['date_full_day'] ) ) {
									$full_day = 1;
								} else {
									$full_day = 0;
								}

								$args = array(
									'title'       => $_POST['date_title'],
									'beginning'   => $_POST['date_beginning'],
									'end'         => $_POST['date_end'],
									'full_day'    => $full_day,
									'description' => $_POST['date_description'],
									'location'    => $_POST['date_location'],
									'category'    => intval( $_POST['date_category'] )
								);
								if ( $calendar->add_date( $args ) ) {
									// Added successful
									header( 'location: ' . network_site_url( '/' )
									        . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php&message=added' );
								} else {
									// Error
									header( 'location: ' . network_site_url( '/' )
									        . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php&message=error' );
								}
							} else {
								// Wrong $_POST['edit_type']
							}
						}
						$this->page_footer();
						break;

					case "edit" :
						if ( ! isset( $_POST['edit_type'] ) ) {
							$this->page_header( __( 'Edit Date', 'micwag-simple-calendar' ) );

							$this->print_form_date( "edit", $_GET['id'] );

							$this->page_footer();
						} else {
							if ( $_POST['edit_type'] == 'edit' ) {
								//Save data
								$calendar = new MicwagSimpleCalendar();
								if ( ! is_null( $calendar->get_date( $_POST['date_id'] ) ) ) {
									// Given date id date exists
									if ( isset( $_POST['date_full_day'] ) ) {
										$full_day = 1;
									} else {
										$full_day = 0;
									}
									$args = array(
										'title'       => $_POST['date_title'],
										'beginning'   => $_POST['date_beginning'],
										'end'         => $_POST['date_end'],
										'full_day'    => $full_day,
										'description' => $_POST['date_description'],
										'location'    => $_POST['date_location'],
										'category'    => intval( $_POST['date_category'] )
									);
									if ( ! ( $calendar->update_date( $_POST['date_id'], $args ) === false ) ) {
										// Update successful
										header( 'location: ' . network_site_url( '/' )
										        . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php&message=updated' );
									} else {
										// Update aborted
										echo __( 'Error during database update.', 'micwag-simple-calendar' );
										echo '<br />';
										echo print_r( $args );
									}
								} else {
									// Given date id doens't exist
								}
							} else {
								// Wrong $_POST['edit_type']
							}
						}
						break;

					case "delete" :
						if ( isset( $_GET['id'] ) ) {
							$id       = $_GET['id'];
							$id       = mysql_real_escape_string( $id );
							$calendar = new MicwagSimpleCalendar();
							if ( $calendar->delete_date( $id ) ) {
								header( 'location: ' . network_site_url( '/' )
								        . 'wp-admin/admin.php?page=micwag-simple-calendar-dashboard.php&message=deleted' );
							}
						} else {
							echo __( 'Missing id', 'micwag-simple-calendar' );
						}
						break;

					default :
						break;
				}
				$this->page_footer();
			}
		} else {
			echo __( 'Permission denied', 'micwag-simple-calendar' );
		}
	}

	private function print_form_category( $action = "edit", $id = null ) {

		if ( $action == "edit" ) {
			$calendar = new MicwagSimpleCalendar();
			$data     = $calendar->get_category( $id );
		}

		echo "<form method=\"POST\">";
		$action = $_GET['action'];
		$edit   = true;
		//Hidden input for edit type selection (edit/add)
		if ( $action == "edit" && count( $data ) != 0 ) {
			echo "<input type=\"hidden\" name=\"edit_type\" value=\"edit\" />";
			echo "<input type=\"hidden\" name=\"category_id\" value=\"";
			echo $id;
			echo "\" />";
		} elseif ( $action == "add" ) {
			echo "<input type=\"hidden\" name=\"edit_type\" value=\"add\" />";
			$edit = false;
		}
		echo "<table class=\"form-table\">";
		echo "<tr>";
		echo "<th>" . __( "Title", 'micwag-simple-calendar' ) . "</th>";
		echo "<td>";
		echo "<input type=\"text\" id=\"category_name\" name=\"category_name\"";
		if ( $action == "edit" ) {
			echo " value=\"" . $data['name'] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>" . __( 'Color', 'micwag-simple-calendar' ) . "</th>";
		echo "<td>";
		echo "<input type=\"color\" id=\"category_color\" name=\"category_color\"";
		if ( $action == "edit" ) {
			echo " value=\"#" . $data["color"] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<th>&nbsp;</th>";
		echo "<td>";
		echo "<input type=\"submit\" value=\"" . __( "Save", 'micwag-simple-calendar' )
		     . "\" class=\"button-primary\" />";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</form>";
	}

	private function print_form_date( $action = "edit", $id = null ) {
		$calendar = new MicwagSimpleCalendar();
		if ( $action == "edit" ) {
			$data = $calendar->get_date( $id );
		}

		echo "<form method=\"POST\">";

		$action = $_GET['action'];
		$edit   = true;
		//Hidden input for edit type selection (edit/add)
		if ( $action == "edit" && count( $data ) != 0 ) {
			echo "<input type=\"hidden\" name=\"edit_type\" value=\"edit\" />";
			echo "<input type=\"hidden\" name=\"date_id\" value=\"";
			echo $id;
			echo "\" />";
		} elseif ( $action == "add" ) {
			echo "<input type=\"hidden\" name=\"edit_type\" value=\"add\" />";
			$edit = false;
		}

		//Normal input fields
		echo "<table class=\"form-table\">";

		//Title
		echo "<tr>";
		echo "<th>";
		echo __( 'Title', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<input type=\"text\" name=\"date_title\"";
		if ( $edit ) {
			echo " value=\"" . $data['title'] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";

		//Category
		echo "<tr>";
		echo "<th>";
		echo __( 'Category', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<select name=\"date_category\" size=\"1\">";
		echo $data['category'];
		echo "<option value=\"0\">" . __( 'None', 'micwag-simple-calendar' ) . "</option>";
		$categories = $calendar->get_categories();
		foreach ( $categories as $category ) {
			echo "<option value=\"" . $category['id'] . "\"";
			if ( $edit && $data['category'] == $category['id'] ) {
				echo " selected";
			}
			echo " >" . $category['name'] . "</option>";
		}
		echo "</select>";
		echo "</td>";
		echo "</tr>";

		//Beginning
		echo "<tr>";
		echo "<th>";
		echo __( 'Beginning', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<input type=\"datetime\" name=\"date_beginning\"";
		if ( $edit ) {
			echo " value=\"" . $data['beginning'] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";

		//End
		echo "<tr>";
		echo "<th>";
		echo __( 'End', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<input type=\"datetime\" name=\"date_end\"";
		if ( $edit ) {
			echo " value=\"" . $data['end'] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";

		//Full day
		echo "<tr>";
		echo "<th>";
		echo __( 'Full Day', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<input type=\"checkbox\" name=\"date_full_day\"";
		if ( $edit && $data['full_day'] == 1 ) {
			echo " checked";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";
		echo "</tr>";

		//Location
		echo "<tr>";
		echo "<th>";
		echo __( 'Location', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<input type=\"text\" name=\"date_location\"";
		if ( $edit ) {
			echo " value=\"" . $data['location'] . "\"";
		}
		echo " />";
		echo "</td>";
		echo "</tr>";

		//Description
		echo "<tr>";
		echo "<th>";
		echo __( 'Description', 'micwag-simple-calendar' );
		echo "</th>";
		echo "<td>";
		echo "<textarea name=\"date_description\">";
		if ( $edit ) {
			echo $data['description'];
		}
		echo "</textarea>";
		echo "</td>";
		echo "</tr>";

		//Submit
		echo "<tr>";
		echo "<th>";
		echo "&nbsp;";
		echo "</th>";
		echo "<td>";
		echo "<input type=\"submit\" class=\"button-primary\" value=\"" . __( 'Save', 'micwag-simple-calendar' )
		     . "\" />";
		echo "</td>";
		echo "</tr>";

		echo "</table>";

		echo "</form>";
	}

}

new MicwagSimpleCalendarAdmin();