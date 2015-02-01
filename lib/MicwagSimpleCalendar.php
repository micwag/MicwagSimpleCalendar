<?php

/**
 * The class MicwagSimpleCalendar contains methods for data access and manipulation.
 * It provides methods for the following tasks:
 *  - installation (adding needed tables to database)
 *  - initialization (example data)
 *  - update (changing affected tables)
 *  - data reading
 *  - data writing
 *
 *      #################
 *      #   Changes     #
 *      #################
 *
 * Version  Author          Date        Change(s)
 * ----------------------------------------------------------------------------
 * 0.1      Michael Wagner  27.05.2014  First version
 * 0.1      Michael Wagner  28.05.2014  Added methods for category data manipulation
 *
 * @version 0.1
 * @author Michael Wagner
 */
class MicwagSimpleCalendar {
    /**
     * Name of table for dates in database
     */
    private $tableDatesName;

    /**
     * Name of table for categories in database
     */
    private $TableCategoriesName;

    /**
     * Database version used/created by this script
     */
    protected static $DbVersion = "1.0";

    /**
     * Sets table names
     */
    public function __construct() {
        global $wpdb;
        $this -> tableDatesName = $wpdb -> prefix . 'micwag_simple_calendar_dates';
        $this -> TableCategoriesName = $wpdb -> prefix . 'micwag_simple_calendar_categories';
        if (!$this -> is_db_up_to_date()) {
            $this -> install();
        }
    }

    /**
     * Creates tables used by ranger-calendar
     * @since 0.1
     */
    public function install() {
        global $wpdb;

        $table_dates_name = $this -> tableDatesName;
        $sql_create_dates = "CREATE TABLE " . $table_dates_name . "(
                id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                beginning DATETIME NOT NULL,
                end DATETIME NOT NULL,
                full_day BOOL NOT NULL,
                title VARCHAR(256) NOT NULL,
                description TEXT,
                location VARCHAR(512),
                category MEDIUMINT(9),
                PRIMARY KEY  id (id)
            );";

        $table_categories_name = $this -> TableCategoriesName;
        $sql_create_categories = "CREATE TABLE " . $table_categories_name . "(
                id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
                name VARCHAR(256) NOT NULL,
                color VARCHAR(6),
                icon VARCHAR(256),
                PRIMARY KEY  id (id)
            );";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_create_dates);
        dbDelta($sql_create_categories);

        add_option("micwag_simple_calendar_db_version", self::$DbVersion);
    }

    /**
     * Inserts example data into tables of ranger-calendar
     *
     * @since 0.1
     */
    public function init() {
        /**
         * @todo insert code
         */
    }

    /**
     * Checks if current database is up-to-date
     *
     * @since 0.1
     * @return bool True if database version is the one given by this script
     */
    protected function is_db_up_to_date() {
        $db_version = get_option('micwag_simple_calendar_db_version', 'none');
        if ($db_version == self::$DbVersion) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Returns an array containing all dates
     *
     * @since 0.1
     * @param int limit: maximum number of dates in array
     */
    public function get_all_appointments($order = "beginning ASC") {
        global $wpdb;

        $sql = "SELECT id, beginning, end, full_day, title, description, location, category FROM ";
        $sql .= $this -> tableDatesName;
        $sql .= " ORDER BY " . $order;
        $db_result = $wpdb -> get_results($sql, ARRAY_A);

        return $db_result;
    }

    /**
     * Returns an array containing all future dates
     *
     * @since 0.1
     * @param int limit: maximum number of dates in array
     */
    public function get_upcoming_appointments($order = "beginning ASC", $limit=0) {
        global $wpdb;

        $sql = "SELECT id, beginning, end, full_day, title, description, location, category FROM ";
        $sql .= $this -> tableDatesName . " WHERE beginning >= NOW()";
        $sql .= " ORDER BY " . $order;
		if($limit != 0) {
			$sql .= " LIMIT 0," . $limit;
		}
        $db_result = $wpdb -> get_results($sql, ARRAY_A);

        return $db_result;
    }

    /**
     * Returns an array containing all current dates
     *
     * @since 0.1
     * @param int limit: maximum number of dates in array
     */
    public function get_current_appointments($order = "beginning ASC") {
        global $wpdb;

        $sql = "SELECT id, beginning, end, full_day, title, description, location, category FROM ";
        $sql .= $this -> tableDatesName . " WHERE beginning <= NOW() AND end >= NOW()";
        $sql .= " ORDER BY " . $order;
        $db_result = $wpdb -> get_results($sql, ARRAY_A);

        return $db_result;
    }

    /**
     * Returns an array containing all elapsed dates
     *
     * @since 0.1
     * @param int limit: maximum number of dates in array
     */
    public function get_elapsed_appointments($order = "beginning DESC") {
        global $wpdb;

        $sql = "SELECT id, beginning, end, full_day, title, description, location, category FROM ";
        $sql .= $this -> tableDatesName . " WHERE end < NOW()";
        $sql .= " ORDER BY " . $order;
        $db_result = $wpdb -> get_results($sql, ARRAY_A);

        return $db_result;
    }

    /**
     * Returns date properties as associative array for a single date specified
     * by its id
     *
     * @since 0.1
     * @param int date_id
     * @return array values
     */
    public function get_appointment($date_id) {
        global $wpdb;
        $date_id = $wpdb -> _real_escape($date_id);
        $sql = "SELECT beginning, end, full_day, title, description, location, category FROM ";
        $sql .= $this -> tableDatesName . " WHERE id=" . $date_id;

        $db_result = $wpdb -> get_row($sql, ARRAY_A);
        return $db_result;
    }

    /**
     * Adds a new date to database
     * Possible arguments in array:
     *  - beginning
     *  - end
     *  - full_day
     *  - title
     *  - description
     *  - location
     *  - category
     *
     * @since 0.1
     * @param Array $args
     */
    public function add_appointment($args) {
        global $wpdb;
        $datetime_now = date('Y-m-d') . 'T' . date('H:m:i') . 'Z';
        $defaults = array(
            'beginning' => $datetime_now,
            'end' => $datetime_now,
            'full_day' => FALSE,
            'title' => 'title',
            'description' => '',
            'location' => '',
            'category' => '0'
        );

        $args = wp_parse_args($args, $defaults);
        return $wpdb -> insert($this -> tableDatesName, $args);
    }

    /**
     * Updates a date in database specified by its id
     *
     * @since 0.1
     * @param int date_id
     * @param array data
     */
    public function update_appointment($date_id, $data) {
        global $wpdb;
        $result = $wpdb -> update($this -> tableDatesName, $data, array('id' => $date_id));
        return $result;
    }

    /**
     * Deletes a date from database specified by its id. If a row was deleted,
     * truw is returned.
     *
     * @since 0.1
     * @param int id
     * @return bool true if suceeded
     */
    public function delete_appointment($date_id) {
        global $wpdb;
        $num_rows = $wpdb -> delete($this -> tableDatesName, array('id' => $date_id));
        if ($num_rows == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Returns an associative array containing all categories and their
     * parameters
     *
     * @since 0.1
     * @param none
     * @return array Associative array containing all categories and their
     * parameters
     */
    public function get_categories() {
        global $wpdb;
        $sql = "SELECT id, name, color, icon FROM " . $this -> TableCategoriesName . " ORDER BY NAME ASC";
        $db_result = $wpdb -> get_results($sql, ARRAY_A);
        return $db_result;
    }

    /**
     * Returns an associative array containing the parameters of category with
     * given id
     *
     * @since 0.1
     * @param int id
     * @return array Associative array containing the parameters of the category
     */
    public function get_category($id) {
        global $wpdb;
        $id = $wpdb -> _real_escape($id);
        $sql = "SELECT name, color, icon FROM " . $this -> TableCategoriesName;
        $sql .= " WHERE id=" . $id;
        $db_result = $wpdb -> get_row($sql, ARRAY_A);
        return $db_result;
    }

    /**
     * Inserts a new categoy with given parameters into database.
     * The id of the created category is returned.
     * Possible arguments in parameters array:
     *  - name
     *  - color
     *  - icon uri
     *
     * @since 0.1
     * @param array parameters of new category
     * @return int id
     */
    public function add_category($args) {
        global $wpdb;

        $defaults = array(
            'name' => 'category_name',
            'color' => '#FF0000',
            'icon' => ''
        );

        $args = wp_parse_args($args, $defaults);

        $id = $wpdb -> insert($this -> TableCategoriesName, $args);
        return $id;
    }

    /**
     * Deletes the date with given id from database. If it suceeded, true is
     * returned.
     *
     * @since 0.1
     * @param int id
     * @return bool true if a row was deleted
     */
    public function delete_category($id) {
        global $wpdb;
        $num_rows = $wpdb -> delete($this -> TableCategoriesName, array('id' => $id));
        if ($num_rows == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Updates a category in database specified by its id
     *
     * @since 0.1
     * @param int date_id
     * @param array data
     */
    public function update_category($category_id, $data) {
        global $wpdb;
        $result = $wpdb -> update($this -> TableCategoriesName, $data, array('id' => $category_id));
        return $result;
    }

}

register_activation_hook(__FILE__, array(
    'micwag_simple_calendar',
    'install'
));
register_activation_hook(__FILE__, array(
    'micwag_simple_calendar',
    'init'
));
