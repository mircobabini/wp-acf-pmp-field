<?php

/*
  Plugin Name: Advanced Custom Fields: Paid Memberships Pro Field
  Plugin URI: http://github.com/mirkolofio/acf-pmp-field
  Description: Add one or more paid memberships pro level to a custom field
  Version: 1.0.0
  Author: Mirco Babini <mirkolofio@gmail.com>
  Author URI: http://github.com/mirkolofio/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class acf_field_pmp_plugin {
	function __construct() {
		// version 4+
		add_action('acf/register_fields', array($this, 'register_fields'));

		// version 3-
		add_action('init', array($this, 'init'));
	}
	function init() {
		if (function_exists('register_field')) {
			register_field('acf_field_pmp', dirname(__File__) . '/acf_pmp-v3.php');
		}
	}
	function register_fields() {
		include_once('acf_pmp-v4.php');
	}

}

new acf_field_pmp_plugin();
?>
