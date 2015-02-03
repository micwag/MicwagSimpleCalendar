<?php
/**
 * Plugin Name: Micwag Simple Calendar
 * Plugin URI: http://micwag.de
 * Author: Michael Wagner
 * Author URI: http://micwag.de
 * Version: 0.1 dev
 *
 * The Plugin Micwag Simple Calendar creates a calender. Dates can be organized in categories.
 *
 * The plugin contains widgets for displaying the calendar as a list and table.
 * The management of dates should be done in wordpress backend.
 * The dates are stored in the wordpress database.
 */

require_once 'vendor/carbon.php';
require_once 'lib/MicwagSimpleCalendar.php';
require_once 'lib/MicwagSimpleCalendarAdmin.php';
require_once 'lib/MicwagSimpleCalendarWidgetList.php';

add_action('wp_enqueue_scripts', 'load_dashicons');
function load_dashicons() {
	wp_enqueue_style('dashicons');
}

add_action('init', 'load_lang');
function load_lang() {
	load_plugin_textdomain('micwag-simple-calendar', false, basename( dirname( __FILE__ ) ) . '/lang');
}
