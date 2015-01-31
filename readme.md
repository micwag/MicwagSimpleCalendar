# Micwag Simple Calendar 
Contributors: micwag
Tags: calendar, widget, free, post, news, developer, posts, simple
Stable tag: 4.1
License: The MIT License
License URI: http://opensource.org/licenses/MIT

The plugin adds calendar functionality to wordpress. It contains a widget, which allows custom markup.

## Description

The plugin adds calendar functionality to wordpress. You can create appointments containing the following fields:

- Title
- Description
- Beginning
- End
- Category
- Location

It also contains a widget which allows to display the appointments using custom markup.

To include appointment information, special tags are available, which can be included user-defined. All available 
tags are listed in the section **Tags**.

## Installation
1. Download the plugin WP Last Posts Widget from this page and extract it
2. Copy the extracted folder to the "/wp-content/plugins/" directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the widget in the 'Theme->Widget' menu of your page.

## FAQ

### I can't find the Widget section in my theme's options
Maybe your theme doesn't support widgets. In this case, the widgets-option is not available. To use this plugin
anyway, you must switch your theme to one which has widget-functionality or add widget-functionality by yourself
(just for experienced developers).


## Tags

### Widget Content

- **%appointments%**: List of appointment contents

### Appointment Content

- **%appointment_title%**: Title of the appointment
- **%appointment_id%**: ID of the appointment
- **%appointment_beginning%**: Beginning of the appointment
- **%appointment_end%**: End of the appointment
- **%appointment_description%**: Description of the appointment
- **%appointment_location%**: Location of the appointment
- **%appointment_beginning_html%**: Beginning of the appointment formatted for use in HTML-datetime-attribute
- **%appointment_end_html%**: End  of the appointment formatted for use in HTML-datetime-attribute
- **%appointment_beginning_year%**: Year of the appointments beginning (four digits)
- **%appointment_beginning_month%**: Month of the appointments beginning
- **%appointment_beginning_day%**: Day of the appointments beginning
- **%appointment_beginning_hour%**: Hour of the appointments beginning
- **%appointment_beginning_second%**: Second of the appointments beginning
- **%appointment_beginning_minute%**: Minute of the appointments beginning
- **%appointment_end_year%**: Year of the appointments end (four digits)
- **%appointment_end_month%**: Month of the appointments end
- **%appointment_end_day%**: Day of the appointments end
- **%appointment_end_hour%**: Hour of the appointments end
- **%appointment_end_second%**: Second of the appointments end
- **%appointment_end_minute%**: Minute of the appointments end
