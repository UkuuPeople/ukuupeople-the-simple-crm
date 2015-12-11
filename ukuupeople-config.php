<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */
if ( file_exists( UKUUPEOPLE_ABSPATH . '/includes/cmb2/init.php' ) ) {
	require_once UKUUPEOPLE_ABSPATH . '/includes/cmb2/init.php';
} elseif ( file_exists( UKUUPEOPLE_ABSPATH . '/includes/CMB2/init.php' ) ) {
	require_once UKUUPEOPLE_ABSPATH . '/includes/CMB2/init.php';
}

/**
 * Get the custom fields array
 */
global $customterm;
if ( file_exists( UKUUPEOPLE_ABSPATH . '/custom-fields.php' ) ) {
  require_once UKUUPEOPLE_ABSPATH . '/custom-fields.php';
}
/**
 * To create custom post type
 */
add_action( 'init','create_post_type' );

/**
 * To create custom fields for ukuupeople
 */
add_action( 'cmb2_init', 'ukuu_register_contact_metabox' );
add_action( 'cmb2_init', 'ukuu_register_setting_metabox' );
add_action( 'cmb2_init', 'ukuu_register_address_metabox' );
add_action( 'cmb2_init', 'ukuu_register_related_org_metabox' );

/**
 * To create custom fields for Touchpoints
 */
add_action( 'cmb2_init', 'touchpoints_register_metabox' );
add_action( 'cmb2_init', 'touchpoints_register_assigned_to_metabox' );

/**
 * To change the year range
 */
add_filter( 'cmb2_localized_data', 'update_date_picker_defaults' );

function create_post_type() {
	$labels = array(
		'name'               => _x( 'UkuuPeople', 'post type general name', 'UkuuPeople' ),
		'singular_name'      => _x( 'Human', 'post type singular name', 'UkuuPeople' ),
		'menu_name'          => _x( 'UkuuPeople', 'admin menu', 'UkuuPeople' ),
		'name_admin_bar'     => _x( 'Human', 'add new on admin bar', 'UkuuPeople' ),
		'add_new'            => _x( 'Add New', 'Human', 'UkuuPeople' ),
		'add_new_item'       => __( 'Add New Human', 'UkuuPeople' ),
		'new_item'           => __( 'New Human', 'UkuuPeople' ),
		'edit_item'          => __( 'Edit Human', 'UkuuPeople' ),
		'view_item'          => __( 'View Human', 'UkuuPeople' ),
		'all_items'          => __( 'People', 'UkuuPeople' ),
		'search_items'       => __( 'Search Human', 'UkuuPeople' ),
		'parent_item_colon'  => __( 'Parent text:', 'UkuuPeople' ),
		'not_found'          => __( 'No People found.', 'UkuuPeople' ),
		'not_found_in_trash' => __( 'No People found in Trash.', 'UkuuPeople' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => '',
		'supports'           => array()
	);

  register_post_type( 'wp-type-contacts', $args );

  $labels =  array(
    'name'               => _x( 'Touchpoints', 'post type general name', 'UkuuPeople' ),
    'singular_name'      => _x( 'Touchpoint', 'post type singular name', 'UkuuPeople' ),
    'menu_name'          => _x( 'Touchpoints', 'admin menu', 'UkuuPeople' ),
    'name_admin_bar'     => _x( 'Touchpoints', 'add new on admin bar', 'UkuuPeople' ),
    'add_new'            => _x( 'Add New', 'Touchpoint', 'UkuuPeople' ),
    'add_new_item'       => __( 'Add New Touchpoint', 'UkuuPeople' ),
    'new_item'           => __( 'New Touchpoint', 'UkuuPeople' ),
    'edit_item'          => __( 'Touchpoint', 'UkuuPeople' ),
    'view_item'          => __( 'View Touchpoint', 'UkuuPeople' ),
    'all_items'          => __( 'All Touchpoints', 'UkuuPeople' ),
    'search_items'       => __( 'Search Touchpoints', 'UkuuPeople' ),
    'parent_item_colon'  => __( 'Parent text:', 'UkuuPeople' ),
    'not_found'          => __( 'No Touchpoints found.', 'UkuuPeople' ),
    'not_found_in_trash' => __( 'No Touchpoints found in Trash.', 'UkuuPeople' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array(),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => '',
    'supports'           => array( 'title' )
  );

  register_post_type( 'wp-type-activity', $args );


	// create a new taxonomy
	$labels = array(
		'name'              => _x( 'Tribes', 'taxonomy general name', 'UkuuPeople' ),
		'singular_name'     => _x( 'Tribe', 'taxonomy singular name', 'UkuuPeople' ),
		'search_items'      => __( 'Search Tribe', 'UkuuPeople' ),
		'all_items'         => __( 'All Tribe', 'UkuuPeople' ),
		'parent_item'       => __( 'Parent Tribe', 'UkuuPeople' ),
		'parent_item_colon' => __( 'Parent Tribe:', 'UkuuPeople' ),
		'edit_item'         => __( 'Edit Tribe', 'UkuuPeople' ),
		'update_item'       => __( 'Update Tribe', 'UkuuPeople' ),
		'add_new_item'      => __( 'Add New Tribe', 'UkuuPeople' ),
		'new_item_name'     => __( 'New Tribe Name', 'UkuuPeople' ),
		'menu_name'         => __( 'Tribe', 'UkuuPeople' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( ),
	);

	register_taxonomy( 'wp-type-group', array( 'wp-type-contacts' ), $args );

	// create a new taxonomy
	$labels = array(
		'name'              => _x( 'Tags', 'taxonomy general name', 'UkuuPeople' ),
		'singular_name'     => _x( 'Tag', 'taxonomy singular name', 'UkuuPeople' ),
		'search_items'      => __( 'Search Tag', 'UkuuPeople' ),
		'all_items'         => __( 'All Tag', 'UkuuPeople' ),
		'parent_item'       => __( 'Parent Tag', 'UkuuPeople' ),
		'parent_item_colon' => __( 'Parent Tag:', 'UkuuPeople' ),
		'edit_item'         => __( 'Edit Tag', 'UkuuPeople' ),
		'update_item'       => __( 'Update Tag', 'UkuuPeople' ),
		'add_new_item'      => __( 'Add New Tag', 'UkuuPeople' ),
		'new_item_name'     => __( 'New Tag Name', 'UkuuPeople' ),
		'menu_name'         => __( 'Tag', 'UkuuPeople' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( ),
	);

	register_taxonomy( 'wp-type-tags', array( 'wp-type-contacts' ), $args );

	// create a new taxonomy
	$labels = array(
		'name'              => _x( 'Contact Types', 'taxonomy general name', 'UkuuPeople' ),
		'singular_name'     => _x( 'Contact Type', 'taxonomy singular name', 'UkuuPeople' ),
		'search_items'      => __( 'Search Contact Type', 'UkuuPeople' ),
		'all_items'         => __( 'All Contact Type', 'UkuuPeople' ),
		'parent_item'       => __( 'Parent Contact Type' , 'UkuuPeople'),
		'parent_item_colon' => __( 'Parent Contact Type:', 'UkuuPeople' ),
		'edit_item'         => __( 'Edit Contact Type', 'UkuuPeople' ),
		'update_item'       => __( 'Update Contact Type', 'UkuuPeople' ),
		'add_new_item'      => __( 'Add New Contact Type', 'UkuuPeople' ),
		'new_item_name'     => __( 'New Contact Type Name', 'UkuuPeople' ),
		'menu_name'         => __( 'Contact Type', 'UkuuPeople' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( ),
	);

	register_taxonomy( 'wp-type-contacts-subtype', array( 'wp-type-contacts' ), $args );

	// create a new taxonomy
	$labels = array(
		'name'              => _x( 'Touchpoint Types', 'taxonomy general name', 'UkuuPeople' ),
		'singular_name'     => _x( 'Touchpoint Type', 'taxonomy singular name', 'UkuuPeople' ),
		'search_items'      => __( 'Search Touchpoint Type', 'UkuuPeople' ),
		'all_items'         => __( 'All Touchpoint Type', 'UkuuPeople' ),
		'parent_item'       => __( 'Parent Touchpoint Type', 'UkuuPeople' ),
		'parent_item_colon' => __( 'Parent Touchpoint Type:', 'UkuuPeople' ),
		'edit_item'         => __( 'Edit Touchpoint Type', 'UkuuPeople' ),
		'update_item'       => __( 'Update Touchpoint Type', 'UkuuPeople' ),
		'add_new_item'      => __( 'Add New Touchpoint Type', 'UkuuPeople' ),
		'new_item_name'     => __( 'New Touchpoint Type Name', 'UkuuPeople' ),
		'menu_name'         => __( 'Touchpoint Type', 'UkuuPeople' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( ),
	);

	register_taxonomy( 'wp-type-activity-types', array( 'wp-type-activity' ), $args );
  	delete_option( 'simple_fields_groups' );
}

/**
 * Define the metabox and field configurations.
 */
function ukuu_register_contact_metabox() {
  $prefix = 'wpcf-';
  $cmb_demo = new_cmb2_box( array(
                'id'            => $prefix . 'group-edit-contact-info',
                'title'         => __( 'Edit Contact Info', 'UkuuPeople' ),
                'object_types'  => array( 'wp-type-contacts', ), // Post type
              ) );

  $actual_fields = array(
    0 => 'first-name',
    1 => 'last-name',
    2 => 'display-name',
    3 => 'email',
    4 => 'phone',
    5 => 'mobile',
    6 => 'website',
    7 => 'contactimage',
    8 => 'ukuu-job-title',
    9 => 'ukuu-twitter-handle',
    10 => 'ukuu-facebook-url',
    11 => 'ukuu-date-of-birth',
  );

  add_fields( $actual_fields , $cmb_demo);
}



/**
 * Define the metabox and field configurations.
 */
function ukuu_register_setting_metabox() {
  $prefix = 'wpcf-';
  $cmb_demo = new_cmb2_box( array(
                'id'            => $prefix . 'group-edit_contact_privacy_settings',
                'title'         => __( 'Edit Privacy Settings', 'UkuuPeople' ),
                'object_types'  => array( 'wp-type-contacts', ), // Post type
              ) );

  $actual_fields = array(
    0 => 'privacy-settings',
    1 => 'bulk-mailings',
  );
  add_fields( $actual_fields , $cmb_demo);
}

function ukuu_register_related_org_metabox(){
  $prefix = 'wpcf-';
  $cmb_demo = new_cmb2_box( array(
                'id'            => $prefix . 'related-org-metabox',
                'title'         => __( 'Related Organization', 'UkuuPeople' ),
                'object_types'  => array( 'wp-type-contacts', ), // Post type
              ) );

  $cmb_demo->add_field( array(
      'name'             => __( 'select organization', 'UkuuPeople' ),
      'desc'             => __( '', 'UkuuPeople' ),
      'id'               => $prefix . 'related-org',
      'type'             => 'select',
      'show_option_none' => true,
      'options'          => get_related_org_value(),
    ) );
}

/**
 * Define the metabox and field configurations.
 */
function ukuu_register_address_metabox() {
  $prefix = 'wpcf-';
  $cmb_demo = new_cmb2_box( array(
                'id'            => $prefix . 'group-edit_contact_address',
                'title'         => __( 'Edit Contact Address', 'UkuuPeople' ),
                'object_types'  => array( 'wp-type-contacts', ), // Post type
              ) );

  $actual_fields = array(
    0 => 'streetaddress',
    1 => 'streetaddress2',
    2 => 'city',
    3 => 'state',
    4 => 'postalcode',
    5 => 'country',
  );
  add_fields( $actual_fields , $cmb_demo);
}

/**
 * Define the metabox and field configurations.
 */
function touchpoints_register_metabox() {
  $prefix = 'wpcf-';
  $activity_information = new_cmb2_box( array(
                            'id'            => $prefix . 'group-activity-information',
                            'title'         => __( 'Activity information', 'UkuuPeople' ),
                            'object_types'  => array( 'wp-type-activity', ), // Post type
                          ) );

  $actual_fields = array(
    0 => 'startdate',
    1 => 'enddate',
    2 => 'status',
    3 => 'details',
    4 => 'attachments',
  );
  add_fields( $actual_fields , $activity_information);
}

function touchpoints_register_assigned_to_metabox() {
  $prefix = 'wpcf_';

  $touchpoint_assigned = new_cmb2_box( array(
                           'id'            => $prefix . 'touchpoint_assigned_metabox',
                           'title'         => __( 'Assigned To', 'UkuuPeople' ),
                           'object_types'  => array( 'wp-type-activity' ,), // Post type
                           'context'    => 'normal',
                           'priority'   => 'high',
                           'closed'     => true, // true to keep the metabox closed by default
                         ) );

  $touchpoint_assigned->add_field( array(
      'name'             => __( 'Assigned to', 'UkuuPeople' ),
      'desc'             => __( '', 'UkuuPeople' ),
      'id'               => $prefix . 'assigned_to',
      'type'             => 'select',
      'show_option_none' => true,
      'options'          => get_id_and_displayname(),
      'repeatable'      => true,
    ) );
}

function get_id_and_displayname() {
  $args = array(
    'fields' => 'ids',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'post_status' => array( 'publish', 'private' ),
    'post_type' => 'wp-type-contacts',
    'suppress_filters' => 0,
    'tax_query' => array(
      'relation' => 'AND',
      array(
        'taxonomy' => 'wp-type-contacts-subtype',
        'field' => 'slug',
        'terms' => 'wp-type-ind-contact'
      ),
      array(
        'taxonomy' => 'wp-type-group',
        'field' => 'slug',
        'terms' => 'wp-type-our-team'
      )
    )
  );
  $items = (array) get_posts($args);
  $display_names = array();
  foreach( $items as $item ) {
    $display_name = get_post_meta($item, 'wpcf-display-name', true);
    $display_names[$item] = $display_name;
  }
  $array_values = "";
  foreach ( $display_names as $key => $values ) {
    $array_values[$key] = __( $values, 'UkuuPeople' );
  }
  if ( !empty( $array_values ) ) return $array_values;
}

function get_related_org_value() {
  $args = array(
    'fields' => 'ids',
    'numberposts' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
    'post_status' => array( 'publish', 'private' ),
    'post_type' => 'wp-type-contacts',
    'suppress_filters' => 0,
    'tax_query' => array(
      array(
        'taxonomy' => 'wp-type-contacts-subtype',
        'field' => 'slug',
        'terms' => 'wp-type-org-contact'
      )
    )
  );
  $items = (array) get_posts($args);
  $display_names = array();
  foreach( $items as $item ) {
    $display_name = get_post_meta($item, 'wpcf-display-name', true);
    $display_names[$item] = $display_name;
  }
  $array_values = "";
  foreach ( $display_names as $key => $values ) {
    $array_values[$key] = __( $values, 'UkuuPeople' );
  }
  if ( !empty( $array_values ) ) return $array_values;
}

function add_fields( $actual_fields , $cmb_demo) {
  global $customterm;
  foreach ( $customterm['fields'] as $key => $value ){
    if ( in_array($key , $actual_fields) )
      $cmb_demo->add_field( $value );
  }
}

function update_date_picker_defaults( $l10n ) {
  $l10n['defaults']['date_picker']['yearRange'] = '1900:+10';
  return $l10n;
}
