<?php
/**
 * Plugin Name: I3MEDIA Security Plugin
 * Plugin URI: http://www.i3-media.com
 * Description: A WP Activity Log plugin extension
 * Text Domain: my-custom-textdomain
 * Author: Juan Perez
 * Author URI: http://www.i3-media.com
 * Version: 1.0.0
 * License: GPL2
 * Network: true
 *
 * @package Wsal
 * @subpackage Wsal Custom Events Loader
 */

/*
	Copyright(c) 2020  WP White Security  (email : info@wpwhitesecurity.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 REQUIRED. Here we include and fire up the main core class. This will be needed regardless so be sure to leave line 37-39 in tact.
*/
require_once plugin_dir_path( __FILE__ ) . 'core/class-extension-core.php';
$wsal_extension = new WPWhiteSecurity\ActivityLog\Extensions\Common\Core( __FILE__, 'wsal-example-extension' );
/*
	From here, you may now place your custom code. Examples of the functions
	needed in a typical extension are provided as a basis, however you MUST
	rename the functions (make them unique) to avoid duplication conflicts.

	Each function provide is complete with internal workings, simply uncomment
	and edit as you wish.
*/

 
// Check if get_plugins() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.


/**
 * Register a custom event object within WSAL.
 *
 * @param array $objects array of objects current registered within WSAL.
 */
function wsal_extension_core_add_custom_event_objects( $objects ) {
	// $new_objects = array(
	// 	'my_custom_obj' => esc_html__( 'My Object Label (Typically the name of the plugin your creating an event for)', 'wp-security-audit-log' ),
	// );
	//
	// combine the two arrays.
	// $objects = array_merge( $objects, $new_objects );
	//
	// return $objects;
}





// display the plugin settings page
function myplugin_display_purpose_page() {
	
 

// Check if get_plugins() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.
if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
 
$all_plugins = get_plugins();

// this does work and as long as the select doesnt have the matching $plugins var it will displau, otherwise for some reason it
function list_my_plugins(){
    $plugins = get_plugins();
    $output = '<ul id="">';
    foreach ( $plugins as $plugin ){
        $output .= '<li>' . $plugin['Name'] . '</li>';
    }
    $output .= '</ul>';
    return $output;
}


//find out why it isnt grabbing the name as it is for the list my plugins one?? 
function selection_plugin() {
	$choices = get_plugins();
	$selection = '<select name="plugins">';
	foreach($choices as $choice) {
		$selection .= '<option value="' . $choice['Name'] .'">' . $choice['Name'] . '</option>';
	}
	$selection .= '</select>';
	return $selection;
}

	// check if user is allowed access
	if ( ! current_user_can( 'manage_options' ) ) return;
	
	?>




	<!-- below is the function for how to properly display to the plugin page and where the user input should be handled -->
	<!-- GOT IT WORKING! Now need to have the input post to the database to be saved and so we can display it in the log.  -->
	<div class="wrap">
		
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<h2>Please select the Plugin and the purpose it serves.</h2>
<form>
	<?php echo selection_plugin()?>
	<br>
	<textarea type="text" placeholder="Enter purpose here..." rows="10" cols="50"></textarea>
	<br>
    <input type="submit" value="Submit">
</form>


<!-- this displays all the plugins. simply for testing to find a way to implement this into the selection drop down -->
		<h2><?php echo list_my_plugins() ?> </h2>

	</div>

	
	<?php
}


// TODO: Find out why this is showing on both the purpose and log sections both times. Need to have Purpose function show on purpose 
//and Log show on log.


function myplugin_display_log_page() {
	
	// check if user is allowed access
	if ( ! current_user_can( 'manage_options' ) ) return;
	
	?>
	<!-- below is the function for how to properly display to the plugin page and where the user input should be handled -->
	<div class="wrap">
		<h2>Plugin Log</h2>
		
		<form action="options.php" method="post">
			
			<?php
			
			// output security fields
			settings_fields( 'myplugin_options' );
			
			// output setting sections
			do_settings_sections( 'myplugin' );
			
			// submit button
			submit_button();
			
			?>
			
		</form>
	</div>
	
	<?php
}


/**
 * Add a custom post type to the ignored list.
 * If your plugin uses a CPT, you may wish to ensure WSAL does not treat
 * the post as a regular post (reporting updates, creation etc). Use your
 * CPTs slug to add it to the list of "igored post types".
 *
 * @param array $post_types Current list of post types to ignore.
 */
function wsal_extension_core_add_custom_ignored_cpt( $post_types ) {
	// $new_post_types = array(
	// 	'my_cpt_slug',    // Your custom post types slug.
	// );
	//
	// // combine the two arrays.
	// $post_types = array_merge( $post_types, $new_post_types );
	//
	// return $post_types;
}

/**
 * Add our own meta formatter. If you wish to create your own custom variable to be
 * used within your custom event message etc, you can register the variable here as well
 * as specify how to handle it.
 *
 * @param string $value Value of variable.
 * @param string $name  Variable name we wish to change.
 */
function wsal_extension_core_add_custom_meta_format( $value, $name ) {
	// $check_value = (string) $value;
	// if ( '%MyCustomVariable%' === $name ) {
	// 	if ( 'NULL' !== $check_value ) {
	// 		return '<a target="_blank" href="' . esc_url( $value ) . '">' . __( 'View form in the editor', 'wp-security-audit-log' ) . '</a>';
	// 	}
	// 	return $value;
	// }
	//
	// return $value;
}


/*
	Filter in our custom functions into WSAL.
 */
add_filter( 'wsal_event_objects', 'wsal_extension_core_add_custom_event_objects', 10, 2 );
add_filter( 'wsal_meta_formatter_custom_formatter', 'wsal_extension_core_add_custom_meta_format', 10, 2 );

// add sub-level administrative menu
function myplugin_add_sublevel_menu() {
	
	/*
	
	add_submenu_page(
		string   $parent_slug,
		string   $page_title,
		string   $menu_title,
		string   $capability,
		string   $menu_slug,
		callable $function = ''
	);
	
	*/
	
	add_submenu_page(
		'plugins.php',
		'Purpose of Plugin',
		'Purpose',
		'manage_options',
		'myplugin',
		'myplugin_display_purpose_page'
	);
	add_submenu_page(
		'plugins.php',
		'Log',
		'Log',
		'manage_options',
		'myplugin',
		'myplugin_display_log_page'
	);
	
}
add_action( 'admin_menu', 'myplugin_add_sublevel_menu' );

