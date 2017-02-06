<?php
defined( 'ABSPATH' ) OR exit;

/**
 * Check for Update process for new version
 **/

class ukuupeople_update {

  /**
   * @var array(revisionNumber) sorted numerically
   */
  private $revisions;
  /**
   * Class contructor
   **/
  function __construct( $old_version, $new_version ) {
    $this->latest_version = $new_version;
    $this->previous_version = $old_version;
  }

  function onUpdate() {
    //Set up incremental update function call
    $revisions = $this->getRevisions();
    if ( $this->hasPendingRevisions() ) {
      $currentRevision = $this->getCurrentRevision();
      foreach ( $this->getRevisions() as $revision ) {
        if ( $revision > $currentRevision ) {
          call_user_func( array($this,'ukuu_update_'.$revision) );
          update_option( 'ukuupeople_version_update_function', $revision );

        }
      }
    }
  }
  // ******** Revision-tracking helpers ********

  /**
   * Determine if there are any pending revisions
   *
   * @return bool
   */
  public function hasPendingRevisions() {
    $revisions = $this->getRevisions();
    $currentRevision = $this->getCurrentRevision();

    if (empty($revisions)) {
      return FALSE;
    }
    if (empty($currentRevision)) {
      return TRUE;
    }

    return ($currentRevision <= max($revisions));
  }

  /**
   * Get a list of revisions
   *
   * @return array(revisionNumbers) sorted numerically
   */
  public function getRevisions() {
    if (! is_array($this->revisions)) {
      $this->revisions = array();

      $clazz = new ReflectionClass(get_class($this));
      $methods = $clazz->getMethods();
      foreach ($methods as $method) {
        if (preg_match('/^ukuu_update_(.*)/', $method->name, $matches)) {
          $this->revisions[] = $matches[1];
        }
      }
      sort($this->revisions, SORT_NUMERIC);
    }

    return $this->revisions;
  }

  public function getCurrentRevision() {
    $key = get_option( 'ukuupeople_version_update_function' );
    if (!$key) {
      $key = 1000;
    }
    return $key;
  }

  // ******** Updater functions ********
  public function ukuu_update_1001() {
    global $ukuupeople_db_version, $wpdb;

    $table_name = $wpdb->prefix . 'ukuu_favorites';
    $retrieve = $wpdb->get_results(
               "
               SELECT *
               FROM $table_name
               "
              );
    $wpdb->query(
               "
               DELETE FROM $table_name
               "
              );

    if ( !empty( $retrieve ) ) {
      foreach ($retrieve as $key => $value ) {
        $retrieve_data = explode(",",$value->user_favs);
        foreach( $retrieve_data as $k => $v ) {
          $post_id = $v;
          $wpdb->insert(
            $table_name,
            array(
              'user_id' => $value->user_id,
              'user_favs' => $post_id,
            )
          );
        }
      }
    }
    $post_table = $wpdb->prefix . 'posts';
    $author_table = $wpdb->prefix . 'users';
    $wpdb->query( "ALTER TABLE $table_name
      MODIFY user_id BIGINT( 20 ) UNSIGNED NOT NULL,
      MODIFY user_favs BIGINT( 20 ) UNSIGNED NOT NULL,
      ADD INDEX (user_favs),
      ADD CONSTRAINT Favourite FOREIGN KEY (user_favs) REFERENCES $post_table (ID) ON UPDATE CASCADE ON DELETE CASCADE,
      ADD CONSTRAINT Author FOREIGN KEY (user_id) REFERENCES $author_table (ID) ON UPDATE CASCADE ON DELETE CASCADE" );
    update_option( "ukuupeople_db_version", $ukuupeople_db_version );
    return true;
  }

 // ******** Updater functions ********
  public function ukuu_update_1002() {
    global $ukuupeople_db_version, $wpdb;
    update_option( "ukuupeople_db_version", $ukuupeople_db_version );
    return true;
  }

 // ******** Updater functions ********
  public function ukuu_update_1003() {
    global $ukuupeople_db_version, $wpdb;
    update_option( "ukuupeople_db_version", $ukuupeople_db_version );
    return true;
  }

  // ******** Updater functions ********
   public function ukuu_update_1004() {
     global $ukuupeople_db_version, $wpdb;
     update_option( "ukuupeople_db_version", $ukuupeople_db_version );
     return true;
   }

   // ******** Updater functions ********
   public function ukuu_update_1005() {
     global $ukuupeople_db_version, $wpdb;
     $table_name = $wpdb->prefix . 'postmeta';
     $wpdb->flush();
     $retrieve = $wpdb->get_results(
       "
               SELECT e.post_id, e.meta_value as meta_value, s.meta_value as attachment_id
               FROM $table_name as e
               LEFT JOIN ( SELECT post_id , meta_value FROM $table_name
               WHERE meta_key = 'wpcf-attachments_id' ) as s
               ON (e.post_id = s.post_id)
               WHERE e.meta_key = 'wpcf-attachments'
               "
     );
     if ( !empty( $retrieve ) ) {
       foreach ( $retrieve as $key => $value ) {
         $image_url = maybe_serialize( array( $value->attachment_id => $value->meta_value ) );
         $post_id = $value->post_id;
         $wpdb->update(
           $table_name,
           array(
             'meta_value' => $image_url,	// string
           ),
           array( 'post_id' => $value->post_id,'meta_key' => 'wpcf-attachments')
         );
       }
     }
     update_option( "ukuupeople_db_version", $ukuupeople_db_version );
     return true;
   }

   // ******** Updater functions ********
   public function ukuu_update_1006() {
     global $ukuupeople_db_version ;
     update_option( "ukuupeople_db_version", $ukuupeople_db_version );
     return true;
   }

}
