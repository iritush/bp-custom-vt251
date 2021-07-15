<?php
/**
 * bp:Add a towns tab to buddy press
 */

function buddypress_tab_towns() {
	bp_core_new_nav_item( array( 
			'name' => 'Towns Visited', 
			'slug' => 'towns-visited', 
			'screen_function' => 'buddypress_towns_screen',
			'default_subnav_slug' => 'towns-visited-list',
			'position' => 40,
			'show_for_displayed_user' => true,
	) );
	bp_core_new_subnav_item( array( 
		'name' => 'Towns Visited List', 
		'slug' => 'towns-visited-list', 
		'parent_url'        => trailingslashit( bp_displayed_user_domain() . 'towns-visited' ),
		'parent_slug'       => 'towns-visited',
		'screen_function' => 'buddypress_towns_screen',
		'position' => 40,
		'show_for_displayed_user' => true,
	) );
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
  
  // Add title and content
  add_action( 'bp_template_title', 'towns_tab_title' );
  add_action( 'bp_template_content', 'towns_tab_content' );
  bp_core_load_template( 'buddypress/members/single/plugins' );
}

/**
 * bp:Define the mark towns screen
 */
function buddypress_add_towns_screen() {
  
	// Add title and content
	add_action( 'bp_template_title', 'add_towns_tab_title' );
    add_action( 'bp_template_content', 'buddydev_edit_profile_group_data');
	bp_core_load_template( 'buddypress/members/single/plugins' );
}

function buddydev_filter_args_for_visited_towns_group( $args ) {
    
    $args['profile_group_id']  = 2; //Your Profile Group ID Here

    return $args;
}
//Load the loop
function buddydev_edit_profile_group_data() {
	$current_user = wp_get_current_user();
	$username = $current_user->user_login;
	$edit_towns_visited_url = get_site_url().'/'.'members'.'/'.$username.'/profile/edit/group/2/';
	echo '<a href='.$edit_towns_visited_url.'> <font color=blue> Click to add or remove towns from your visted towns list</font></a> ';
}

function towns_tab_title() {
  echo 'Towns You Visited';
}

function add_towns_tab_title() {
	echo 'Add/Remove Towns';
  }

/***
 * bp: town content 
 */
function towns_tab_content() {
	$towns_visited = xprofile_get_field_data('Towns Visited');
	sort ($towns_visited);
	foreach($towns_visited as $town){
		echo $town . '<br>';
	} 

}

/***
 * bp: rename profile tabs
 */

function bpcodex_rename_profile_tabs() {
  
	buddypress()->members->nav->edit_nav( array( 'name' => __( 'Notes', 'textdomain' ) ), BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'Add New Note',), 'edit', BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array('name' => 'My Notes',), BUDDYBLOG_ARCHIVE_SLUG, BP_BUDDYBLOG_SLUG );
	buddypress()->members->nav->edit_nav( array( 'name' => __( 'Towns Visited Map', 'textdomain' ) ), 'location' );
}
add_action( 'bp_actions', 'bpcodex_rename_profile_tabs' );
?>