<?php
defined( 'ABSPATH' ) OR exit;
define( 'UKUUPEOPLE_ABSPATH', dirname( __FILE__ ) );
define( 'UKUUPEOPLE_RELPATH', plugins_url() . '/' . basename( UKUUPEOPLE_ABSPATH ) );

/**
 * @package UKUU CRM
 */
/*
  Plugin Name: UKUU PEOPLE
  Plugin URI: http://ukuupeople.com/
  Description: Ukuu People is the premium plugin that helps you elegantly manage all of your human relationships.
  Ukuu People effortlessly ties all of your contact interactions and contact data collection tools together to form one authoritative master list of all of your contacts and a record of your interactions with them.
  Version: 1.5.6
  Author: UkuuPeople
  Author URI: http://ukuupeople.com/
  License: GPL 3
  Text Domain: UkuuPeople
*/
/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

global $ukuupeople_db_version;
$ukuupeople_db_version = '1.5.6';

register_activation_hook( __FILE__, 'on_activation' );
register_deactivation_hook( __FILE__, 'on_deactivation' );
register_uninstall_hook( __FILE__ , 'on_uninstall' );

// Check for upgraded version
global $ukuupeople_db_version;
$installed_ver = get_site_option( "ukuupeople_db_version" );
if ( !empty( $installed_ver ) &&  $installed_ver != $ukuupeople_db_version ) {
  //UkuuPeople Updater
  if ( !class_exists( 'ukuupeople_update' ) ) {
    // load our custom updater
    include( dirname( __FILE__ ) . '/ukuupeople-update.php' );
  }
  // setup and run the updater
  $ukuupeople_updater = new ukuupeople_update( $installed_ver, $ukuupeople_db_version );
  $ukuupeople_updater->onUpdate();
  update_option( "ukuupeople_db_version", $ukuupeople_db_version );
}
/* Activate welcome page */
add_action('admin_init', 'welcome_screen_do_activation_redirect');
function welcome_screen_do_activation_redirect() {
  if ( ! get_transient( '_welcome_screen_activation_redirect' ) ) {
    return;
  }
  // Delete the redirect transient
  delete_transient( '_welcome_screen_activation_redirect' );
  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'ukuu-about' ), admin_url( 'index.php' ) ) );
}

/**
 * Activation hook.
 *
 * Create/activate some of data.
 */
function on_activation() {
  if ( ! current_user_can( 'activate_plugins' ) )
    return;

  global $wpdb;
  global $ukuupeople_db_version;
  $table_name = $wpdb->prefix . 'ukuu_favorites';
  $post_table = $wpdb->prefix . 'posts';
  $author_table = $wpdb->prefix . 'users';
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE if not exists $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      user_id BIGINT( 20 ) UNSIGNED NOT NULL,
      user_favs BIGINT( 20 ) UNSIGNED NOT NULL,
      UNIQUE KEY id (id ) ,
      INDEX (user_favs),
      CONSTRAINT Favourite FOREIGN KEY (user_favs) REFERENCES $post_table (ID) ON UPDATE CASCADE ON DELETE CASCADE,
      CONSTRAINT Author FOREIGN KEY (user_id) REFERENCES $author_table (ID) ON UPDATE CASCADE ON DELETE CASCADE
	) $charset_collate;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
  global $ukuupeople_db_version;
  update_option( "ukuupeople_db_version", $ukuupeople_db_version );
  require_once( UKUUPEOPLE_ABSPATH . '/ukuupeople-config.php' );
  $capabilities_ukuupeople = ukuupeople_capabilities();
  $capabilities_touchpoint = touchpoint_capabilities();
  if ( is_admin() ) {
    $user_role = get_role( 'administrator' );
    foreach( $capabilities_ukuupeople as $key => $value ) {
      $user_role->add_cap( $value );
    }
    foreach( $capabilities_touchpoint as $key => $value ) {
      $user_role->add_cap( $value );
    }
    $user_role->remove_cap('edit_ukuupeople');
    $user_role->remove_cap('read_ukuupeople');
    $user_role->remove_cap('delete_ukuupeople');
    $user_role->remove_cap('edit_touchpoint');
    $user_role->remove_cap('read_touchpoint');
    $user_role->remove_cap('delete_touchpoint');
  }
  //Welcome page display only when ukuupeople activated
  if (isset($_GET['plugin']) && $_GET['plugin'] == "ukuupeople-the-simple-crm/ukuupeople.php") {
    set_transient( '_welcome_screen_activation_redirect', true, 30 );
  }
}

/**
 * Deactivation hook.
 *
 * Reset some of data.
 */
function on_deactivation() {
  if ( ! current_user_can( 'activate_plugins' ) )
    return;
  delete_option( 'ukuupeople_plugin_deferred_admin_notices' );
  delete_option( 'ukuupeople_license_verify_notices' );
  $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
}

// create custom fields using CMB2
require_once( UKUUPEOPLE_ABSPATH.'/ukuupeople-config.php' );
// instantiate the plugin class
require_once( UKUUPEOPLE_ABSPATH.'/includes/class-ukuupeople.php' );
global $wp_ukuu_people;
$wp_ukuu_people = new UkuuPeople();

/**
 * Load UkuuPeople taxtdomain.
 *
 */
add_action( 'plugins_loaded', 'load_ukuu_textdomain' );
function load_ukuu_textdomain() {
  load_plugin_textdomain( 'UkuuPeople', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * set locale for wp-type-activity post type.
 *
 */
add_filter( 'locale', 'set_my_locale' ,99);
function set_my_locale( $lang ) {
  global $typenow;
  global $post_type;
  if ( ( $typenow == 'wp-type-activity' || $typenow == 'wp-type-contacts' ) && 'en_US' == $lang ){
    return 'en_GB';
  }
  return $lang;
}

add_action( 'admin_notices', 'ukuupeople_admin_notices' );
function ukuupeople_admin_notices() {
  if ( $notices = get_option( 'ukuupeople_deferred_admin_notices' ) ) {
    delete_option( 'ukuupeople_deferred_admin_notices' );
    foreach ( $notices as $notice )
      echo "<div class='updated'><p>$notice</p></div>";
  }
}
/*
 * Add Ukuu css file
 */
add_action( 'admin_enqueue_scripts', 'ukuupeople_style' );
function ukuupeople_style() {
  wp_enqueue_style( 'ukuupeople-style', UKUUPEOPLE_RELPATH.'/css/ukuupeople.css');
  wp_enqueue_script( 'jquery-ui-autocomplete' );
}

function on_uninstall() {
  if ( ! current_user_can( 'activate_plugins' ) )
    return;
  if ( __FILE__ != WP_UNINSTALL_PLUGIN )
    return;
}
