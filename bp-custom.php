<?php
/**
 * bp:Add a towns visited tab to buddy press
 */

function buddypress_tab_towns() {
	// Member visited town
	bp_core_new_nav_item( array( 
			'name' => 'Towns Visited', 
			'slug' => 'towns-visited', 
			'screen_function' => 'buddypress_towns_screen',
			'default_subnav_slug' => 'towns-visited-list',
			'position' => 1,
			'show_for_displayed_user' => true,
	) );
	// Show list of towns that the member checked off as visited
	bp_core_new_subnav_item( array( 
		'name' => 'Towns Visited List', 
		'slug' => 'towns-visited-list', 
		'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'towns-visited' ),
		'parent_slug'       => 'towns-visited',
		'screen_function' => 'buddypress_towns_screen',
		'position' => 1,
		'show_for_displayed_user' => true,
	) );
	// Map containing pins for all members visited towns
	bp_core_new_subnav_item( array(
		'name'                  => 'Map',
		'slug'                  => 'map',
		'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'towns-visited' ),
		'parent_slug'       => 'towns-visited',
		'default_subnav_slug' => 'map',
		'screen_function'       => 'map_screen',
		'position'              => 75,
		'show_for_displayed_user' => true,
		) );
	// Link to town list to checkk/uncheck visited towns
	bp_core_new_subnav_item( array(
		'name'              => 'Add/Remove Towns',
		'slug'              => 'add-remove-towns',
		'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'towns-visited' ),
		'parent_slug'       => 'towns-visited',
		'screen_function'   => 'buddypress_add_towns_screen',
		'position'          => 100,
		'show_for_displayed_user' => true,
	) );
}
add_action( 'bp_setup_nav', 'buddypress_tab_towns' );

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
function map_screen() {
	add_action( 'bp_template_title', 'map_tab_title' );
    add_action( 'bp_template_content', 'map_tab_content_content' );
    bp_core_load_template( 'buddypress/members/single/plugins' );
}

/**
 * bp:Define the mark towns screen
 */
function buddypress_add_towns_screen() {
	add_action( 'bp_template_title', 'add_towns_tab_title' );
    add_action( 'bp_template_content', 'buddydev_edit_profile_group_data');
	bp_core_load_template( 'buddypress/members/single/plugins' );
}

/***
 * bp: Display the link to edit visited towns XProfile field
 */
function buddydev_edit_profile_group_data() {
	$edit_towns_visited_url = bp_core_get_user_domain( get_current_user_id()) . '/profile/edit/group/2/';
	echo '<a href='.$edit_towns_visited_url.'> <font color=blue> Click to add or remove towns from your visted towns list</font></a> ';
}

function towns_tab_title() {
  $num_of_towns_visited = count (xprofile_get_field_data('Towns Visited'));
  echo 'You Visited ' . $num_of_towns_visited . ' Towns!<br>';

}

function map_tab_title() {
	echo '<center>Towns Visited Map</center>';
  }

function add_towns_tab_title() {
	echo 'Add/Remove Towns';
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
	echo do_shortcode ('[leaflet-map tileurl=null lat=44.000000 lng=-72.699997 height=650 zoom=8]');
}

/***
 * bp: rename profile tabs
 */

function bpcodex_rename_profile_tabs() {
  
	buddypress()->members->nav->edit_nav( array( 'name' => __( 'Notes', 'textdomain' ) ), BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'Add New Note',), 'edit', BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'Notes: We will be continuing to update this section to make it more user friendly.  Stay tuned!',), BUDDYBLOG_ARCHIVE_SLUG, BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array( 'name' => __( 'Towns Visited Map', 'textdomain' ) ), 'location' );
}
add_action( 'bp_actions', 'bpcodex_rename_profile_tabs' );

/***
 * bp: reorder profile tabs
 */
function bpcodex_change_notifications_nav_position() {
	buddypress()->members->nav->edit_nav( array(
        'position' => 1,
    ), 'towns-visited' );
	buddypress()->members->nav->edit_nav( array(
        'position' => 10,
    ), 'buddyblog' );
	buddypress()->members->nav->edit_nav( array(
        'position' => 12,
    ), 'mediapress' );
}
add_action( 'bp_setup_nav', 'bpcodex_change_notifications_nav_position', 100 );
?>