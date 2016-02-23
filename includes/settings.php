<?php
/**
 * Admin Administer
 *
 * @package     UkuuPeople
 * @since       1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add-ons Page Init
 *
 */
function ukuucal_init() {
	global $ukuucal_page;
	add_action( 'load-' . $ukuucal_page, 'ukuucal_administer' );
}

add_action( 'admin_menu', 'ukuucal_init' );

/**
 * Add-ons Page
 *
 * Renders the add-ons page content.
 *
 * @since 1.0
 * @return void
 */
function ukuucal_administer_plugins() {
	ob_start(); ?>
	<div class="wrap" id="ukuucal-app-sync">
     <?php echo admin_tabs();?>
		<h2><?php _e( 'Google App Sync', 'UkuuPeople' ); ?></h2>
	</div>
	<?php
	echo ob_get_clean();
}

/**
 * Builds the Admin Tabs
 */
function admin_tabs( $current=NULL ) {
  $tabs = array(
    'settings' => __( 'General', 'UkuuPeople' ),
    'googleapp' => __('Integrations', 'UkuuPeople' ),
    'licenses' => __('Licenses', 'UkuuPeople' ),
  );
  if( is_null( $current ) ) {
    if( isset( $_GET['page'] ) ) {
      $current = $_GET['page'];
    }
  }
  $content = '';
  $content .= '<h2 class="nav-tab-wrapper">';
  foreach( $tabs as $location => $tabname ){
    if( $current == $location ){
      $class = ' nav-tab-active';
    } else{
      $class = '';
    }
    $content .= '<a class="nav-tab'.$class.'" href="edit.php?post_type=wp-type-contacts&page='.$location.'">'.$tabname.'</a>';
  }
  $content .= '</h2>';
  return $content;
}

/*
 * Add submenu page for wp-type-contact post type
 */
function settings() {
  echo admin_tabs();
  //$Ukuu_Custom_List = new Ukuu_People_List();
  $activity_type = get_terms( 'wp-type-activity-types','hide_empty=0' );
  $tribes = get_terms( 'wp-type-group','hide_empty=0' );
  $tags = get_terms( 'wp-type-tags','hide_empty=0' );
  echo '<div class="postbox main-type-list"><h3 class="hndle"><span> Touchpoint'. __( 'Types', 'UkuuPeople' ) .'</span></h3>';
  types_list( $activity_type , 'touchpoint' );
  echo '</div>';
  echo "<a href='".admin_url()."edit-tags.php?taxonomy=wp-type-activity-types&post_type=wp-type-contacts'><input type='button' class='button button-primary' value='".__( 'Add New', 'UkuuPeople' )." Touchpoint ". __( 'Type', 'UkuuPeople' ) . "'></a>";
  echo '<div class="postbox main-type-list"><h3 class="hndle"><span>Tribes</span></h3>';
  types_list( $tribes , '' );
  echo '</div>';
  echo "<a href='".admin_url()."edit-tags.php?taxonomy=wp-type-group&post_type=wp-type-contacts'><input type='button' class='button button-primary' value='".__( 'Add New ', 'UkuuPeople' )."Tribe'></a>";
  echo '<div class="postbox main-type-list"><h3 class="hndle"><span>People Tags</span></h3>';
  types_list( $tags , '' );
  echo '</div>';
  echo "<a href='".admin_url()."edit-tags.php?taxonomy=wp-type-tags&post_type=wp-type-contacts'><input type='button' class='button button-primary' value='". __( 'Add New', 'UkuuPeople' ) ." Tag'></a>";
}

function licenses() {
  echo admin_tabs();
  // custom actions for addon license page
  $addonarr = array(
    'gravity_form_license' => 'ukuu_gravity_form_license_key',
    'import_license'       => 'ukuupeople_import_license_key',
    'mailchimp_license'    => 'ukuupeople_mailchimp_license_key',
    'give_license'         => 'ukuupeople_give_license_key',
    'ukuugoogle_license'   => 'ukuupeople_google_license_key',
  );
  foreach ( $addonarr as $key => $value ) {
    if( has_action( $key ) ) {
      do_action( $key );
    }
  }
}

/*
 *Add submenu page for google app integration functionality
 */
function googleapp() {
  echo admin_tabs();
  if( has_action( 'connection' ) ) {
    do_action( 'connection' );
  }
  if( has_action( 'ukuumailchimp' ) ) {
    do_action( 'ukuumailchimp' );
  }
}

/*
 * callback function
 *
 * @param type $types
 * @param type $type_name
 */
function types_list( $types , $type_name ) {
  echo "<table class='type-list'><tbody>";
  $count = 0;
  $color = array(
    'wp-type-activity-meeting' => '#377CB6',
    'wp-type-activity-phone' => '#771D78',
    'wp-type-activity-note' => '#3DA999' ,
    'wp-type-contactform' => '#E6397A'
  );

  foreach ( $types as $key => $value ) {
    if ( $count%2 == 0) echo "<tr>"; else echo "<tr class='alternate'>";
    $URL = get_edit_term_link( $value->term_id ,$value->taxonomy );
    $selectedColor = get_option('term_category_radio_' . $value->slug);
    $deleteURL = get_delete_post_link( $value->term_id);
    $wpn = wp_create_nonce( 'delete-tag_' .$value->term_id );
    $dURL = 'edit-tags.php?action=delete&taxonomy=wp-type-activity-types&tag_ID='.$value->term_id.'&_wpnonce='.$wpn;
    echo "<td><a href='$URL'>$value->name</a>";?>
      <div class="row-actions">
        <span class="edit"><a title="Edit this item" href="<?php echo $URL ?>"><?php _e( 'Edit', 'UkuuPeople' ); ?></a>|</span>
        <span class="trash"><a class="submitdelete" href="<?php echo $dURL ?>" title="Move this item to the Trash"><?php _e( 'Trash', 'UkuuPeople' ); ?></a></span>
      </div>
      <?php if ( $type_name == 'touchpoint' ) {
      if (array_key_exists($value->slug, $color)) {
        echo "</td><td class='color' style='background-color:".$color[$value->slug]."'></td></tr>";
      }
      else {
        echo "</td><td class='color' style='background-color:".$selectedColor."'></td></tr>";
      }
    }
    else echo "</td></tr>";
    $count++;
  }
  echo "</tbody></table>";
}
