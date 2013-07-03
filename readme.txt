=== Advanced Custom Fields - Paid Memberships Pro Field ===
Contributors: Mirco Babini <mirkolofio@gmail.com>
Donate link: https://github.com/mirkolofio/
Tags: acf, pmp, paid memberships pro, advanced custom fields
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a 'Paid Memberships Pro level' field type for the Advanced Custom Fields WordPress plugin.

== Description ==
Adds a 'Paid Memberships Pro level' field type for the Advanced Custom Fields WordPress plugin.

Store one or multiple levels in an advanced custom field.
Mark one or more levels as disabled to prevent them from being selected.
Field is returned as Paid Memberships Pro level ID 

== Compatibility ==
This add-on will work with:
4.	version 4 and upper
3.	version 3 and lower

== Installation ==
This add-on can be treated as both a WP plugin and a theme include.

**Install as Plugin**
Copy the 'acf-pmp' folder into your plugins folder
Activate the plugin via the Plugins Admin Page

**Include within theme**
1.	Copy the 'acf-pmp' folder into your theme folder (can use sub folders). You can place the folder anywhere inside the 'wp-content' directory
2.	Edit your functions.php file and add the code below (Make sure the path is correct to include the acf-pmp.php file)

`add_action('acf/register_fields', 'my_register_fields');
function my_register_fields() {
    include_once('acf-pmp/acf-pmp.php');
}`

== Frequently asked questions ==

== Screenshots ==

== Changelog ==

== Upgrade notice ==

