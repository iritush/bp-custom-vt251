<?php
/**
 * bp:Add a towns visited tab to buddy press
 */

function buddypress_towns_tabs() {
	// Member visited town
	bp_core_new_nav_item( array( 
			'name' => 'Town list', 
			'slug' => 'towns-visited', 
			'screen_function' => 'buddypress_towns_screen',
			'position' => 1,
			'show_for_displayed_user' => true,
	) );
	// Map containing dots for all members visited towns
	bp_core_new_nav_item( array(
		'name'                  => 'Visited Towns Map',
		'slug'                  => 'map',
		'screen_function'       => 'buddypress_map_screen',
		'position'              => 2,
		'show_for_displayed_user' => true,
		) );
}
add_action( 'bp_setup_nav', 'buddypress_towns_tabs' );

/**
 * bp:Define the towns screen
 */
function buddypress_towns_screen() { 
  add_action( 'bp_template_title', 'towns_tab_title' );
  add_action( 'bp_template_content', 'towns_tab_content' );
  bp_core_load_template( 'buddypress/members/single/plugins' );
}

/**
 * bp:Define the map screen
 */
function buddypress_map_screen() {
	add_action( 'bp_template_title', 'map_tab_title' );
    add_action( 'bp_template_content', 'map_tab_content_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

function towns_tab_title() {
  $num_of_towns_visited = count (xprofile_get_field_data('Towns Visited'));
  $edit_towns_visited_url = bp_core_get_user_domain( get_current_user_id()) . '/profile/edit/group/2/';
  echo '<center>You Visited ' . $num_of_towns_visited . ' Towns! <br> <a href='.$edit_towns_visited_url.'> Add/remove towns from visted towns list</a></center>';

}

function map_tab_title() {
	// shortcode for printing using print o matic plugin. More info:  https://pluginoven.com/plugins/print-o-matic/documentation/shortcode/
	
	$edit_towns_visited_url = bp_core_get_user_domain( get_current_user_id()) . '/profile/edit/group/2/';
	echo '<upperleft>'.do_shortcode ('[print-me target=".leaflet-pane"/]').'</upperleft>';
  }
/***
 * bp: Filter and display list of checked towns from the visited towns list Xprofile field
 */
function towns_tab_content() {
	$towns_visited = xprofile_get_field_data('Towns Visited');
	sort ($towns_visited);
	foreach($towns_visited as $town){
		echo $town . '<br>';
	} 
}

function map_tab_content_content() {
	echo do_shortcode ('[leaflet-map tileurl=null lat=44.000000 lng=-72.699997 height=650 zoom=8 zoomcontrol=1 min_zoom=7 max_zoom=9]');
}

/***
 * bp: rename profile tabs
 */

function bpcodex_rename_profile_tabs() {
  
	buddypress()->members->nav->edit_nav( array( 'name' => __( 'Notes', 'textdomain' ) ), BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'Add New Note',), 'edit', BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'Notes: We will be continuing to update this section to make it more user friendly.  Stay tuned!',), BUDDYBLOG_ARCHIVE_SLUG, BP_BUDDYBLOG_SLUG );
}
add_action( 'bp_actions', 'bpcodex_rename_profile_tabs' );

?>