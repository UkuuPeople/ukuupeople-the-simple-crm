<?php
defined( 'ABSPATH' ) OR exit;

/**
 * Users Contact Page.
 *
 * @package UKUUPEOPLE
 * @subpackage UKUU CRM Contacts
 */

class UkuuPeople {

	public function __construct( $args = array() ) {
    /*
     * Insert Contact Type and Activity Type taxonomy terms on plugin activation
     */
    add_action( 'admin_init', array( $this , 'insert_taxonomy_terms' ));

    add_action( 'wp_dashboard_setup', array( $this, 'ukuuCRM_dashboard_setup' ) );

    /* metabox for custom activity list */
    add_action( 'add_meta_boxes',  array( $this , 'ukuu_custom_touchpoint' ) );

    add_action( 'transition_post_status', array( $this, 'publish_post_privately' ), 999, 3 );

    /**
     * if submitted filter by post meta*/
    add_filter( 'parse_query', array( $this ,'posts_filter_touchpoint_contact') );

    /* Change public text to Create */
    add_filter( 'gettext', array( $this , 'change_publish_button' ) , 10, 2 );

    /* Change email to display name */
    add_filter( 'the_title', array( $this , 'change_related_org_value' ), 9999, 2 );

    // hide fields table meta box
    add_filter("simple_fields_add_post_edit_side_field_settings", array( $this , "hide_simple_side_field_settings" ), 10, 2);

    /*
     * Unset the All/Published/Trash menu filter from WP-Type-Activity and WP-Type-Contact list view
     */
    add_action( 'views_edit-wp-type-contacts',  array( $this , 'remove_views' ));
    add_action( 'views_edit-wp-type-activity',  array( $this , 'remove_views' ));

    /* Remove view link from activities post */
    add_filter( 'post_row_actions', array( $this , 'remove_row_actions'), 10, 1 );

    /* ajax call to get activity list*/
    add_action( 'wp_ajax_get_act_lists', array( $this , 'prepare_query' ) ); //get lists via ajax call
    add_action( 'wp_ajax_ukuu_add_to_fav', array( $this , 'ukuu_add_to_fav' ) ); //get lists via ajax call

    add_action( 'wp_ajax_contact_list', array( $this , 'contact_list' ));
    add_action( 'wp_ajax_assign_contact_list', array( $this , 'assign_contact_list' ) );
    /* ajax call for quick add touchpoint dashlet */
    add_action( 'wp_ajax_quick_add_touchpoint', array( $this , 'quick_add_touchpoint' ) ); //get lists via ajax call

    /*
     * Add custom filters for wp-type-contacts and wp-type-activity post listing
     * Add Contact type graph on wp-type-contacts post type listing
     */
    add_action( 'restrict_manage_posts', array( $this , 'ukuu_custom_filters_posts' ), 1 );

    /*
     * Add custom field columns on wp-type-contacts and wp-type-activity post listing page
     */
    add_action( 'manage_wp-type-contacts_posts_custom_column', array( $this, 'ukuu_custom_field_list_text' ), 10, 2 );
    add_action( 'manage_wp-type-activity_posts_custom_column', array( $this, 'ukuu_custom_field_list_text' ), 10, 2 );
    add_filter( 'manage_wp-type-contacts_posts_columns', array( $this, 'ukku_contacts_custom_fields_list_view' ) );
    add_filter( 'manage_wp-type-activity_posts_columns', array( $this, 'ukku_activity_custom_fields_list_view' ) );
    add_action( 'admin_head', array( $this , 'ukku_contacts_custom_fields_hide_date_filter' ) );

    /*
     * Update related user on wp-type-contact updation
     */
    add_action( 'profile_update', array( $this , 'ukuu_custom_profile_update' ), 10, 2 );

    /*
     * Set contact/activity type on respective new post type saving
     */
    add_action( 'save_post', array( $this, 'ukuu_set_wp_custom_type' ), 99 , 2 );

    /*
     * Remove submenu pages for contact/Activity add and hide/remove title, types meta box
     */
    add_action( 'admin_menu', array( $this , 'ukuu_custom_remove_links_meta'), 9999 );
    add_action( 'admin_menu', array($this, 'ukuupeople_menu') );

    /*
     * Add Contact summary view on Edit link
     */
    add_action( 'edit_form_after_title', array( $this , 'ukuu_custom_summary_view' ));
    add_action( 'edit_form_top', array( $this , 'ukuu_custom_summary_view_activity' ));

    /*
     * Collapse the fields on page load
     */
    add_filter( 'get_user_option_closedpostboxes_wp-type-contacts', array( $this , 'closed_meta_boxes') );

    /*
     * Edit 'Title' label on quick edit for activity
     */
    add_filter('gettext',array( $this , 'custom_enter_title'));

    /*
     * Add wordpress user on wp-type-contacts post save
     */
    add_filter( 'wp_insert_post_data', array( $this , 'ukuu_custom_add_user'), 10, 2 );

    /*
     * Delate respecive contacts post on user deletion
     */
    add_action( 'delete_user', array( $this , 'ukuu_delete_user' ));
    add_action( 'admin_notices', array( $this, 'license_notification' ) );
    add_action( 'wp-type-activity-types_add_form_fields', array ( $this, 'add_color_fields' ) );
    add_action( 'create_wp-type-activity-types', array ( $this, 'save_color_fields' ) );

    /*
     * custom action for tab info in organization view
     */
    add_action( 'tab_info', array ( $this, 'custom_human_info' ) ,10,2);

    /*
     * custom placeholder for activity post type
     */
    add_filter('enter_title_here', array ( $this,'change_placeholder' ), 2, 2);

    /*
     * replace insert into post to add to touchpoint
     */
    add_action('admin_init', array( $this, 'change_mediabutton_name'));

    /*
     * custom placeholder for activity post type
     */
    add_action( 'admin_head', array( $this,'ukuu_icons') );

    /*
     * add admin user to ukuupeople
     */
    add_action ('user_register', array( $this,"register_user"));

    /*
     *to remove dash(-)post-status from posttitle on posts listing page
     */
    add_filter('display_post_states', '__return_false');

    /*
     * Access control list
     */
    add_filter( 'map_meta_cap', array( $this, 'set_meta_cap_ukuupeople' ), 10, 4 );
    add_filter( 'posts_clauses', array( $this, 'ukuupeople_query_clauses' ), 1, 2);
    add_filter( 'map_meta_cap', array( $this,'touchpoint_map_meta_cap'), 10, 4 );
    add_filter( 'posts_clauses', array( $this, 'touchpoint_query_clauses' ), 20, 2 );
    add_action( 'do_meta_boxes' , array( $this, 'remove_post_custom_fields' ) );
    /*
     * TouchPoints Sorting
     */

    /* Sorting table columns of contacts and activity
     * This two filters is linked to posts_clauses filter
     */
    add_filter( 'manage_edit-wp-type-contacts_sortable_columns', array( $this, 'make_all_columns_sortable_contacts' ) );
    add_filter( 'manage_edit-wp-type-activity_sortable_columns', array( $this, 'make_all_columns_sortable_activity' ) );

    // Alter Quick / Bulk edit options
    add_action( 'bulk_edit_custom_box', array( $this,'ukuupeople_alter_quick_edit_options'), 10, 2 );
    add_action( 'quick_edit_custom_box', array( $this,'ukuupeople_alter_quick_edit_options'), 10, 2 );

    // Change view post message.
    add_filter( 'post_updated_messages', array( $this,'post_published_notice' ));

    add_action( 'admin_enqueue_scripts', array( $this, 'ukuupeople_enqueue_scripts' ) );

    // Get Quick Edit Field Data
    add_action( 'wp_ajax_ukuu_quick_edit_get_field_data', array( $this , 'ukuu_get_field_data' ) );

    add_filter( 'custom_menu_order', array( $this, 'ukuu_reorder_submenu' ), 99 );
  }

  /** Reorder Ukuu-Submenu's
   *  UkuuPeople and TouchPoint submenu's should be at the top
   *  Add-ons & Settings should be at the bottom
  **/
  public function ukuu_reorder_submenu( $menu_ord ) {
    global $submenu;

    if ( isset( $submenu['edit.php?post_type=wp-type-contacts'] ) ) {
      // Define top and last array options
      $top_submenu_items  = array( 'People', 'TouchPoints' );
      $last_submenu_items = array( 'Add-ons', 'Settings' );

      $submenu_copy = $submenu['edit.php?post_type=wp-type-contacts'];

      foreach ( $submenu_copy as $submenu_id => $submenu_info ) {
        if ( ( $position = array_search( $submenu_info[0], $top_submenu_items ) ) !== FALSE ) {
          unset( $top_submenu_items[ $position ] );

          $top_submenu_items[ $submenu_id ] = $submenu_info;

          unset( $submenu_copy[ $submenu_id ] );
        }
        elseif ( in_array( $submenu_info[0], $last_submenu_items ) !== FALSE ) {
          unset( $submenu_copy[ $submenu_id ] );
          $submenu_copy[ $submenu_id ] = $submenu_info;
        }
      }
      $submenu['edit.php?post_type=wp-type-contacts'] = $top_submenu_items + $submenu_copy;
    }
    return $menu_ord;
  }

  // Add js to admin pages
  public function ukuupeople_enqueue_scripts( $hook ) {
    $current_page = get_current_screen();

    if ( $hook == 'edit.php' && in_array( $current_page->post_type,  array( 'wp-type-activity', 'wp-type-contacts' ) ) ) {
      wp_enqueue_script( 'quick_edit_js', plugins_url( '../script/ukuu-edit.js', __FILE__ ) );
      wp_localize_script( 'quick_edit_js', 'quick_edit', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'ukuu_quick_edit' ) ) );
    }
    else {
      wp_dequeue_script( 'quick_edit_js' );
    }
  }

  // Build data for fields in quick edit
  public function ukuu_get_field_data() {
    $field = array();
    switch ( $_POST['field'] ) {
      case 'wpcf-assignee' :
        // get all people's related to our team
        $field['options'] = $this->assign_contact_list( false );

        if ( isset( $_POST['id'] ) && $_POST['id'] != NULL )
          $field['values'] = get_post_meta( $_POST['id'], 'wpcf_assigned_to' );
        break;
    }
    echo json_encode( $field );
    wp_die();
  }

  // Change view post message.
  public function post_published_notice( $messages ) {
    global $post;
    if ($post->post_type == "wp-type-activity") {
      $link = get_post_permalink($post->ID);
      $messages["post"][1] = "TouchPoint updated. <a href = '{$link}' > View TouchPoint</a>";
      $messages["post"][6] = "TouchPoint created. <a href = '{$link}' > View TouchPoint</a>";
    }
    else if ($post->post_type == "wp-type-contacts") {
      $link = get_post_permalink($post->ID);
      $messages["post"][1] = "Contact updated.<a href = '{$link}' > View contact</a>";
      $messages["post"][6] = "Contact created. <a href = '{$link}' > View contact</a>";
    }
    return $messages;
  }

  // Alter quick edit options in UkuuPeople & TouchPoints
  public function ukuupeople_alter_quick_edit_options( $column_name, $post_type ) {
    if ( ! in_array( $post_type, array('wp-type-contacts', 'wp-type-activity') ) ) {
      return;
    }

    // Added condition so that script get included once only
    if ( in_array( $column_name, array('wp-contact-full-name', 'wp-fullname') ) ) {
      // Remove Date, Password and Status fields using jquery. Wordpress doesnot provide any other option :(
      echo "
        <script>
          jQuery(document).ready(function () {
            // Remove date field
            if ( jQuery('fieldset.inline-edit-date').length )
              jQuery('fieldset.inline-edit-date').remove();

            // Remove password field
            if ( jQuery('div.inline-edit-group').length )
              jQuery('div.inline-edit-group').remove();

            // Remove post status field
            if ( jQuery('fieldset.inline-edit-col-right').length )
              jQuery('fieldset.inline-edit-col-right').remove();
          })
        </script>
      ";
    }

    // Add tags and contact-types to quick edit of wp-type-contacts
    if ( $post_type == 'wp-type-activity' && $column_name == 'wp-startdate' ) {
      // get status
      global $customterm;
      $touchpoint_status = $customterm['fields']['status'];?>

      <fieldset class='inline-edit-col-left ukuu-custom-quick-edit'>
        <div class='inline-edit-col'>
          <div id='ukuu-quick-edit-activity-status'>
            <span class='title ukuu-inline-edit-touchpoint-status-label'>
              <?php echo esc_html( trim( $touchpoint_status['name'], '*' ) ); ?>
            </span>
            <input type='hidden' name='touchpoint_status[]' value='0' />

            <ul class='cat-checklist touchpoint-status-checklist'>
            <?php foreach ( $touchpoint_status['options'] as $status_name => $status_label ) : ?>
              <li>
                <label class="selectit">
                  <input type="checkbox" id="touchpoint-status-<?php echo $status_name; ?>" name="touchpoint_status[]" value="<?php echo $status_name; ?>"> <?php echo $status_label; ?>
                </label>
              </li>
            <?php endforeach; ?>
            </ul>
          </div>
          <div id='ukuu-quick-edit-touchpoint-assignee'>
            <span class='title ukuu-inline-edit-touchpoint-assignee-label'>
              Add Assignee
            </span>
            <input type='hidden' name='touchpoint_assignee[]' value='0' />
            <ul class='cat-checklist touchpoint-assignee-checklist'>
              <!-- Build li using js -->
            </ul>
          </div>
        </div>
      </fieldset>

    <?php
    }
  }

  public function make_all_columns_sortable_contacts( $sortable_columns ) {
    global $post_type;
    $sortable_columns['wp-contact-full-name'] = 'wp-contact-full-name';
    $sortable_columns['wp-email']             = 'wp-email';
    $sortable_columns['wp-phone']             = 'wp-phone';
    $sortable_columns['wp-type-activity']     = 'wp-type-activity';
    $sortable_columns['wp-inactive_days']     = 'wp-inactive_days';
    return $sortable_columns;
  }

  public function make_all_columns_sortable_activity( $sortable_columns ) {
    global $post_type;
    $sortable_columns['wp-startdate']    = 'wp-startdate';
    $sortable_columns['wp-contact-type'] ='wp-contact-type';
    $sortable_columns['wp-fullname']     = 'wp-fullname';
    //$sortable_columns['wp-assigned']   = 'wp-assigned';
    $sortable_columns['wp-status']       = 'wp-status';
    return $sortable_columns;
  }

  function remove_post_custom_fields() {
    remove_meta_box( 'members-cp' , 'wp-type-contacts' , 'advanced' );
    remove_meta_box( 'members-cp' , 'wp-type-activity' , 'advanced' );
  }

  /*
   * Access control list
   */
  function touchpoint_map_meta_cap( $caps, $cap, $user_id, $args ) {
    if ( 'edit_touchpoint' == $cap || 'delete_touchpoint' == $cap || 'read_touchpoint' == $cap ) {
      $postID = NULL;
      if ( isset( $args[0] ) ) {
        $post = get_post( $args[0] );
      }
      else if ( isset( $_GET['touchpoint_id'] ) ) {
        $postID = $_GET['touchpoint_id'];
        $post = get_post( $postID );
      }
      $post_type = get_post_type_object( $post->post_type );

      /* Set an empty array for the caps. */
      $caps = array();
    }
    if ( 'read_touchpoint' == $cap ) {
      if ( $user_id == $post->post_author || $user_id == 1 || current_user_can( 'read_all_touchpoints')) {
        $caps[] = 'read';
      } else {
        return false;
      }
    } elseif ( 'edit_touchpoint' == $cap ) {
      if ( ( $user_id == $post->post_author && current_user_can('edit_own_touchpoints') ) || current_user_can('edit_all_touchpoints') || $user_id == 1  )
        $caps[] = $post_type->cap->edit_posts;
      else {
        return false;
      }
    } elseif ( 'delete_touchpoint' == $cap ) {
      if ( $user_id == 1 || current_user_can( 'delete_all_touchpoints' ) )
        $caps[] = $post_type->cap->delete_others_posts;
      elseif ( $user_id == $post->post_author && current_user_can('delete_own_touchpoints') )
        $caps[] = $post_type->cap->delete_posts;
      else {
        return false;
      }
    } elseif ( $cap == 'edit_posts' && current_user_can( 'edit_own_touchpoints') && current_user_can( 'access_touchpoints') && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wp-type-activity') {
      return array();
    }
    /* Return the capabilities required by the user. */
    return $caps;
  }

  function touchpoint_query_clauses( $pieces, $query ) {
    global $wpdb, $user_info, $pagenow;
    $user_info = wp_get_current_user();
    // Filter Opportunities By Logged-In User
    // Switch Condition implements this join but for remaining conditions we need to implement this
     if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wp-type-activity' ) {
      if ( strpos( $pieces['join'], $wpdb->postmeta ) == false ) {
        $pieces['join'] .= " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
      }

      if ( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ) {
        $pieces['where'] = " AND {$wpdb->posts}.post_type = 'wp-type-activity' AND ({$wpdb->posts}.post_status = 'trash')";
      }
      else if ( isset($_GET['s']) ) {
        $search = $_GET['s'];
        $pieces['where'] =  "AND ((({$wpdb->posts}.post_title LIKE '%{$search}%') OR ({$wpdb->posts}.post_content LIKE '%{$search}%')))  AND {$wpdb->posts}.post_type = 'wp-type-activity' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private')";
      }
      else {
        $pieces['where'] = " AND {$wpdb->posts}.post_type = 'wp-type-activity' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' )";
      }
      // If user has read_all_permissions OR edit_all_permissions OR delete_all_permissions then display all opportunities
      $access_all = FALSE;
      foreach ( array( 'read_all_touchpoints', 'edit_all_touchpoints', 'delete_all_touchpoints' ) as $permission ) {
        if ( current_user_can( $permission ) ) {
          $access_all = TRUE;
          break;
        }
      }

      if ( ! $access_all ) {
        //$que = "SELECT spm.post_id FROM {$wpdb->postmeta} spm WHERE spm.meta_value = '{$user_info->user_email}' AND spm.meta_key = 'wpcf-email'";
        $que = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT $wpdb->postmeta.post_id
               FROM $wpdb->postmeta
               WHERE $wpdb->postmeta.meta_value = %s
               AND $wpdb->postmeta.meta_key = 'wpcf-email'
               ",
               $user_info->user_email
               ) ,ARRAY_A);
        $data = explode( ",", $que[0]['post_id']);
        $que = serialize($data);

        $pieces['where'] .= " AND ( {$wpdb->posts}.post_author = {$user_info->ID} OR ( {$wpdb->postmeta}.meta_key = '_wpcf_belongs_wp-type-contacts_id' AND {$wpdb->postmeta}.meta_value = (SELECT spm.post_id FROM {$wpdb->postmeta} spm WHERE spm.meta_value = '{$user_info->user_email}' AND spm.meta_key = 'wpcf-email') ) OR ( {$wpdb->postmeta}.meta_key = 'wpcf_assigned_to' AND {$wpdb->postmeta}.meta_value = '{$que}' ) ) ";
      }

      // Similar scenario for groupby
      if ( empty( $pieces['groupby'] ) ) {
        $pieces['groupby'] .= " {$wpdb->posts}.ID";
      }
    }

    if( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && 'wp-type-activity' == $_GET['post_type']  && $query->is_main_query() )  {
      if(  ( isset($_GET['wp-type-activity-types']) && $_GET['wp-type-activity-types'] != '' ) ) {
        $pieces['join'] .= " INNER JOIN {$wpdb->terms} ON ({$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->terms}.term_id)";

        $subtype = $_GET['wp-type-activity-types'];
        $pieces['where'] .= " AND ({$wpdb->terms}.slug = '$subtype')";
      }

      if ( isset( $_GET['touchpoint-assignee'] ) && $_GET['touchpoint-assignee'] != '' ) {
        $pieces['join']  .= " INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.id = {$wpdb->postmeta}.post_id)";

        // Build serialized text
        $filter_text_length = strlen( $_GET['touchpoint-assignee'] ); // get text length
        $serialized_text    = 's:' . $filter_text_length . ':"'. $_GET['touchpoint-assignee'] . '"';

        $pieces['where'] .= " AND ({$wpdb->postmeta}.meta_key = 'wpcf_assigned_to' AND {$wpdb->postmeta}.meta_value LIKE '%{$serialized_text}%')";
      }
    }
    return $pieces;
  }

  /**
   * Alter different parts of the query
   *
   * @param array $pieces
   *
   * @return array $pieces
   */
  function ukuupeople_query_clauses( $pieces, $query ) {
    if ( ! isset( $_GET['post_type'] ) || ! in_array( $_GET['post_type'], array( 'wp-type-contacts', 'wp-type-activity' ) ) ) {
      return $pieces;
    }

    global $wpdb, $user_info, $pagenow;
    $user_info = wp_get_current_user();
    // Filter Opportunities By Logged-In User
    // Switch Condition implements this join but for remaining conditions we need to implement this
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wp-type-contacts' && !isset($_GET['s']) && ! empty( $_GET['s'] ) && !isset($_GET['page']) ) {
      if ( strpos( $pieces['join'], $wpdb->postmeta ) == false ) {
        $pieces['join'] .= " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id )";
      }

      $pieces['where'] = " AND ( {$wpdb->postmeta}.meta_key = 'wpcf-email' ) AND {$wpdb->posts}.post_type = 'wp-type-contacts' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' )";

      if ( isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ) {
        $pieces['where'] = " AND {$wpdb->posts}.post_type = 'wp-type-contacts' AND ({$wpdb->posts}.post_status = 'trash')";
      }
      else {
        $pieces['where'] = " AND {$wpdb->posts}.post_type = 'wp-type-contacts' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' )";
      }

      // If user has read_all_permissions OR edit_all_permissions OR delete_all_permissions then display all opportunities
      $access_all = FALSE;
      foreach ( array( 'read_all_ukuupeoples', 'edit_all_ukuupeoples', 'delete_all_ukuupeoples' ) as $permission ) {
        if ( current_user_can( $permission ) ) {
          $access_all = TRUE;
          break;
        }
      }
      if ( !$access_all ) {
        $pieces['where'] .= " AND ( {$wpdb->posts}.post_author = {$user_info->ID} OR ( {$wpdb->postmeta}.meta_key = 'wpcf-email' AND {$wpdb->postmeta}.meta_value = '{$user_info->user_email}' ) ) ";
      }

      // Similar scenario for groupby
      if ( empty( $pieces['groupby'] ) )
        $pieces['groupby'] .= " {$wpdb->posts}.ID";
    }

    //Code for sorting contact and activity columns. (this function is linked to make_all_columns_sortable_activity() and make_all_columns_sortable_contacts())

    if( isset( $_GET['orderby'] ) ) {
      $orderby = $_GET['orderby'];
    } else {
      $orderby = $query->get('orderby');
    }

    if ($orderby == NULL && $_GET["post_type"] == "wp-type-activity" ) {
      $orderby =  'wp-startdate';
    }
    // Check if order by column is not title, since title is not a metavalue
    if ( $query->is_main_query() && $orderby != NULL ) {
      if( isset( $_GET['order'] ) ) {
        $order = strtoupper( $_GET['order'] );
      } else {
        $order = strtoupper( $query->get('order') );
      }

      if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) )
        $order = 'ASC';

      switch( $orderby ) {
        case 'wp-startdate' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-startdate'";
          $pieces['orderby'] = "wp_rd1.meta_value+0 $order, " . $pieces['orderby'];
          break;
        case 'wp-status' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-status'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-fullname' :
          $pieces['join'] .= " LEFT JOIN {$wpdb->postmeta} wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = '_wpcf_belongs_wp-type-activity_id' LEFT JOIN {$wpdb->postmeta} pm3 ON pm3.post_id = wp_rd1.meta_value AND pm3.meta_key = 'wpcf-display-name'";
          $pieces['orderby'] = "pm3.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-assigned' :
          $pieces['join'] .= "LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = {$wpdb->posts}.ID AND pm1.meta_key = 'wpcf_assigned_to'
LEFT JOIN {$wpdb->postmeta} pm1 ON pm1.post_id = SUBSTRING( pm1.meta_value, 15, SUBSTRING( pm1.meta_value, 12)) AND pm1.meta_key = 'wpcf-display-name'";
          $pieces['orderby'] = "pm1.meta_value $order, " . $pieces['orderby'];
        case 'wp-contact-full-name' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-display-name'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-email' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-email'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-phone' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-phone'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-phone' :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = 'wpcf-phone'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
        case 'wp-inactive_days' :
          $pieces['fields']  .= " , IFNULL( TIMESTAMPDIFF( DAY, MAX(p1.post_date), NOW() ), 0 ) as inactive_days";
          $pieces['join'] = " LEFT JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.meta_value ) LEFT JOIN {$wpdb->posts} p1 ON {$wpdb->postmeta}.post_id = p1.ID AND p1.post_type = 'wp-type-activity'";

          $pieces['where'] = " AND {$wpdb->posts}.post_type = 'wp-type-contacts' AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'refunded' OR {$wpdb->posts}.post_status = 'failed' OR {$wpdb->posts}.post_status = 'revoked' OR {$wpdb->posts}.post_status = 'abandoned' OR {$wpdb->posts}.post_status = 'active' OR {$wpdb->posts}.post_status = 'inactive' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private')";
          $pieces['groupby'] = " {$wpdb->posts}.ID";
          $pieces['orderby'] = " inactive_days $order";
          break;
        case 'wp-contact-type' :
          $pieces['fields'] .= " ,(SELECT name FROM {$wpdb->terms} WHERE term_id = MAX(tr1.term_taxonomy_id)) as contact_type";
          $pieces['join'] .= " LEFT JOIN {$wpdb->postmeta} pm2 ON {$wpdb->posts}.ID = pm2.post_id LEFT JOIN {$wpdb->posts} p11 ON p11.ID = pm2.meta_value AND p11.post_type IN ('wp-type-contacts', 'wp-type-opportunity') LEFT JOIN {$wpdb->term_relationships} tr1 ON tr1.object_id = p11.ID AND tr1.term_taxonomy_id IN ( SELECT term_id FROM {$wpdb->terms} WHERE slug IN ('wp-type-ind-contact', 'wp-type-org-contact'))";
          $pieces['groupby'] = " {$wpdb->posts}.ID";
          $pieces['orderby'] = " contact_type $order";
          break;
        default :
          $pieces['join'] .= " LEFT JOIN $wpdb->postmeta wp_rd1 ON wp_rd1.post_id = {$wpdb->posts}.ID AND wp_rd1.meta_key = '" . $orderby . "'";
          $pieces['orderby'] = "wp_rd1.meta_value $order, " . $pieces['orderby'];
          break;
      }
    }
    return $pieces;
  }

  function set_meta_cap_ukuupeople( $caps, $cap, $user_id, $args ) {
    if ( 'edit_ukuupeople' == $cap || 'delete_ukuupeople' == $cap || 'read_ukuupeople' == $cap ) {
      $postID = NULL;
      if ( isset( $args[0] ) )
        $postID = $args[0];
      elseif ( isset( $_GET['cid'] ) )
        $postID = $_GET['cid'];
      else
        return false;
      $post = get_post( $postID );

      if ( $post != NULL )
        $post_type = get_post_type_object( $post->post_type );
      else
        return false;

      /* Set an empty array for the caps. */
      $caps = array();
    }

    if ( 'read_ukuupeople' == $cap ) {
      if ( $user_id == $post->post_author || $user_id == 1 || current_user_can( 'read_all_ukuupeoples')) {
        $caps[] = 'read';
      } else {
        if( $postID ) {
          $current_user_email = wp_get_current_user()->user_email;
          $post_author_email = get_post_meta( $args[0], 'wpcf-email', TRUE );
          if ( $current_user_email == $post_author_email ) {
            $caps[] = 'read';
          } else {
            return false;
          }
        }
      }
    } elseif ( 'edit_ukuupeople' == $cap ) {
      if ( ( $user_id == $post->post_author && current_user_can('edit_own_ukuupeoples') ) || current_user_can('edit_all_ukuupeoples') || $user_id == 1 ) {
        $caps[] = $post_type->cap->edit_posts;
      } else {
        $current_user_email = wp_get_current_user()->user_email;
        $post_author_email = get_post_meta( $args[0], 'wpcf-email', TRUE );
        if ( $current_user_email == $post_author_email && current_user_can('edit_own_ukuupeoples') ) {
          $caps[] = $post_type->cap->edit_posts;
        } else {
          return false;
        }
      }
    } elseif ( 'delete_ukuupeople' == $cap ) {
      if ( $user_id == 1 || current_user_can( 'delete_all_ukuupeoples' ) )
        $caps[] = $post_type->cap->delete_others_posts;
      elseif ( $user_id == $post->post_author && current_user_can('delete_own_ukuupeoples') )
        $caps[] = $post_type->cap->delete_posts;
      else {
        $current_user_email = wp_get_current_user()->user_email;
        $post_author_email = get_post_meta( $args[0], 'wpcf-email', TRUE );
        if ( $current_user_email == $post_author_email && current_user_can('delete_own_ukuupeoples') ) {
          $caps[] = $post_type->cap->delete_posts;
        } else {
          return false;
        }
      }
    } elseif ( 'edit_posts' == $cap && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wp-type-contacts' && ( current_user_can( 'edit_own_ukuupeoples') || current_user_can( 'edit_all_ukuupeoples' ) ) && current_user_can( 'access_ukuupeoples' ) ) {
      return array();
    }
    return $caps;
  }

  function register_user() {
    if ( isset( $_POST['email'] ) ) {
      $people_post = array(
        'post_title'    => $_POST['email'],
        'post_type'     => 'wp-type-contacts',
        'post_status'   => 'private',
        'post_author'   => 1 ,
      );

      // Insert the people into the database
      $people_ID = wp_insert_post( $people_post );

      // update post meta for peeple
      $name = $_POST['first_name'];
      if ( $name == '' )
        update_post_meta( $people_ID, 'wpcf-first-name', 'admin');
      else
        update_post_meta( $people_ID, 'wpcf-first-name', $name);

      $name = $_POST['last_name'];
      if ( $name == '' )
        update_post_meta( $people_ID, 'wpcf-last-name', 'admin');
      else
        update_post_meta( $people_ID, 'wpcf-last-name', $name);

      update_post_meta( $people_ID, 'wpcf-display-name', $_POST['user_login']);
      update_post_meta( $people_ID, 'wpcf-email', $_POST['email'] );

      wp_set_object_terms( $people_ID, 'wp-type-ind-contact', 'wp-type-contacts-subtype', true );
      wp_set_object_terms( $people_ID, 'wp-type-our-team', 'wp-type-group', true );
    }
  }

  // Here it check the pages that we are working on are the ones used by the Media Uploader.
  function change_mediabutton_name() {
    global $pagenow;
    if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
      // Now we will replace the 'Insert into Post Button inside Thickbox'
      add_filter('gettext', array( $this,'replace_window_text'), 1, 2);
      // gettext filter and every sentence.
    }
  }

  /*
   * Referer parameter in our script file is for to know from which page we are launching the Media Uploader as we want to change the text "Insert into Post".
   */
  function replace_window_text($translated_text, $text) {
    if ('Insert into Post' == $text) {
      $referer = strpos(wp_get_referer(), 'media_page');
      return __('Add to touchpoint', 'UkuuPeople');
    }
    return $translated_text;
  }

  function contact_list() {
    $list = array();
    $args = array(
      'fields' => 'ids',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => array( 'publish', 'private' ) ,
      'post_type' => 'wp-type-contacts',
      'suppress_filters' => 0,
    );
    $items = get_posts($args);
    if ( !empty ( $items ) ) {
      foreach ( $items as $temp_post => $val ) {
        $display = get_post_meta( $val , 'wpcf-display-name', true );
        $list[] = array('id' => $val, 'name' => $display);
      }
    }
    echo json_encode($list);
    wp_die();
  }

  function assign_contact_list( $echo = true ) {
    $list = array();
    $args = array(
      'fields' => 'ids',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
      'post_status' => array( 'publish', 'private' ) ,
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
    $items = get_posts( $args );
    if ( !empty ( $items ) ) {
      foreach ( $items as $temp_post => $val ) {
        $display = get_post_meta( $val , 'wpcf-display-name', true );
        $list[] = array( 'id' => $val, 'name' => $display );
      }
    }

    if ( $echo ) {
      echo json_encode( $list );
      wp_die();
    }
    else {
      return $list;
    }
  }

  function change_placeholder( $label, $post ){

    if( $post->post_type == 'wp-type-activity' )
      $label= __( 'Enter TouchPoint Short Description', 'UkuuPeople' );

    return $label;
  }

  function get_AttachedImageID($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_results(($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )),OBJECT);
    return $attachment[0];
  }

  function quick_add_touchpoint(){
    // Create post object
    global $current_user , $wpdb;
    $my_post = array(
      'post_title'    => $_POST['dsubject'],
      'post_type'     => 'wp-type-activity',
      'post_status'   => 'private',
      'post_author'   => $current_user->ID ,
    );

    // Insert the TouchPoint into the database
    $post_ID = wp_insert_post( $my_post );

    // NOTE : Dates in the m/d/y or d-m-y formats are disambiguated by looking at the separator between the various components: if the separator is a slash (/), then the American m/d/y is assumed; whereas if the separator is a dash (-) or a dot (.), then the European d-m-y format is assumed.
    $startdate = str_replace( '-', '/', $_POST['dsdate'] ) . " " . $_POST['dstime'];
    $enddate   = str_replace( '-', '/', $_POST['dedate'] ) . " " . $_POST['detime'];

    // update post meta for peeple
    update_post_meta( $post_ID, 'wpcf-startdate', strtotime( $startdate ));
    if ( $_POST['dedate'] || $_POST['detime'] ) update_post_meta( $post_ID, 'wpcf-enddate', strtotime( $enddate ));
    update_post_meta( $post_ID, 'wpcf-status', 'scheduled' );
    if ( $_POST['ddetails'] ) update_post_meta( $post_ID, 'wpcf-details', $_POST['ddetails'] );
    $image_id = $this->get_AttachedImageID($_POST['filename']);
    $imgID = $image_id->ID;
    if ( $_POST['filename'] ) update_post_meta( $post_ID, 'wpcf-attachments', array($imgID => $_POST['filename']) );
    if ( $_POST['contact_id'] ) update_post_meta( $post_ID, "_wpcf_belongs_wp-type-activity_id", $_POST['contact_id'] );
    if ( $_POST['contact_id'] ) update_post_meta( $post_ID, "_wpcf_belongs_wp-type-contacts_id", $_POST['contact_id'] );

    if ( $_POST['dtype'] ) wp_set_object_terms( $post_ID ,  $_POST['dtype'], 'wp-type-activity-types', true );
    if ( $_POST['touchpoint_assign_id'] ) {
      $data = explode( ",", $_POST['touchpoint_assign_id']);
      $assign = serialize($data);
      $table_name = $wpdb->prefix . 'postmeta';
      $wpdb->insert(
        $table_name,
        array(
          'post_id' => $post_ID,
          'meta_key' => "wpcf_assigned_to",
          'meta_value' => $assign
        )
      );
    }
    wp_die( );
  }

  /*
   * callback function for tab_info action
   */
  function org_members( $id ) {
    global $wpdb;
    $display = $wpdb->get_results( $wpdb->prepare(
                 "
               SELECT ID
               FROM $wpdb->posts
               LEFT JOIN $wpdb->postmeta
               ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND post_type = 'wp-type-contacts'
               AND meta_key='wpcf-related-org' and meta_value like %s
               ",
                 $id
               ) , OBJECT_K);
    echo "<div class='scrolldiv'>";
    if ( $display ) {
      foreach ( $display as $key => $value ) {
        $URL = get_edit_post_link( $key );
        echo "<a href='".$URL."'><div class='org-members'>";
        $contact_image = get_post_meta( $key, 'wpcf-contactimage', true );
        echo "<div class='tabcontactimage'>";
        if ( ! empty( $contact_image ) ) {
          echo "<span class='contactimage'><img src='".$contact_image."' width='32' height='32'></span>";
        } else {
          $avatar = get_avatar( get_post_meta( $key , 'wpcf-email', true ) ,32);
          echo $avatar;
        }
        echo "</div><div class='namemail'>";
        $name = get_post_meta( $key , 'wpcf-display-name', true );
        $email = get_post_meta( $key , 'wpcf-email', true );
        echo "<div class='tabname'>".$name."</div>";
        echo "<div class='tabemail'>".$email."</div>";
        echo "</div></div></a>";
      }
    }
    echo "</div>";
  }

  // custom ukuupeople icon
  function ukuu_icons() { ?>
    <style type="text/css" media="screen">
        #menu-posts-wp-type-contacts .wp-menu-image {
          background: url( <?php echo UKUUPEOPLE_RELPATH.'/images/ukuu_people_dashboard_icon_gray.png'; ?>) no-repeat 8px 6px !important;
        background-size: 55% 55% !important;
        }
        #menu-posts-wp-type-contacts:hover .wp-menu-image, #menu-posts-wp-type-contacts.wp-has-current-submenu .wp-menu-image {
    background: url( <?php echo UKUUPEOPLE_RELPATH.'/images/ukuu_people_dashboard_icon_white.png'; ?>) no-repeat 8px 6px!important;
  background-size: 55% 55% !important;
        }
        #adminmenu  #menu-posts-wp-type-contacts div.wp-menu-image:before {
            content: "";
        }
    </style><?php
    }

  /*
   * custom action for tab info in organization view
   */
  function custom_human_info( $edit , $slug ) {
    wp_enqueue_script( 'jquery-ui-tabs' );
    $tab_filter = array();
    // version dependent code
    $plugin_info = get_plugins();
    if( class_exists( 'UKUUGIVE' ) ){
      $plugin_exist = array_key_exists(UKUUGIVE_BASENAME,$plugin_info);
      if ( $plugin_exist ){
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin_active = is_plugin_active(UKUUGIVE_BASENAME);
        if ( $plugin_active ){
          $plugin_version = $plugin_info[UKUUGIVE_BASENAME]['Version'];
          if($plugin_version <= '1.0.2'){
            $tab_filter['contribution'] =array(
              'id' => 'donation-tab',
              'action' => 'ukuugive_tab_view',
              'icon' => 'ukuucontributions',
            );
          }
        }
      }
    }
    // version dependent code
    $tab_filter = apply_filters( 'ukuupeople_view_tab_filter', $tab_filter );
    if ( $slug == 'wp-type-org-contact' ) {
      $tab_filter['membership'] = array(
        'id' => 'org-members',
        'action' => '',
        'icon' => 'ukuumembership',
      );
      wp_enqueue_style( 'ukuutab style', UKUUPEOPLE_RELPATH.'/css/ukuutab.css' );
      echo '<div id="third-sidebar-contact">';
      echo "<ul>";
      $count = 0;
      $jsn_tab_filter = htmlspecialchars(json_encode($tab_filter));
      foreach ( array_reverse( $tab_filter ) as $key => $value ) {
        if ( $value['action'] == '' || ( $value['action'] != '' && has_action($value['action'] ) ) ) {
          $count++;
          if ( $count == 1 ) $value['icon'] = $value['icon'].'-blue';
          echo '<li class="tab'.$key.'" onclick="tab_filter(\''.$key.'\','.$jsn_tab_filter.')"><a href="#'.$value['id'].'"><span class="'.$value['icon'].'"></span></a></li>';
        }
      }
      ?></ul><div id="org-members">
      <span class='org-title'><?php _e( 'Organization', 'UkuuPeople' ) ?> <?php _e( 'Team Members' , 'UkuuPeople' ) ?></span>
      <?php $this->org_members( $edit->ID );
         echo "</div>";
         foreach ( $tab_filter as $key => $value ){
           echo '<div id="'.$value['id'].'">';
           do_action( $value['action'] , $edit );
           echo '</div>';
         }
         echo "</div>";
    } elseif( $slug == 'wp-type-ind-contact' && $tab_filter ) {
      $flag= false;
      foreach ( $tab_filter as $key => $value ){
        if ( $value['action'] != '' && has_action($value['action'] ) ) {
          $flag= true;
        }
      }
      if ( $flag ) {
        wp_enqueue_style( 'ukuutab style', UKUUPEOPLE_RELPATH.'/css/ukuutab.css' );
        echo '<div id="third-sidebar-contact">';
        echo "<ul>";
        $count = 0;
        $jsn_tab_filter = htmlspecialchars( json_encode( $tab_filter ) );
        foreach ( $tab_filter as $key => $value ) {
          $count++;
          if ( $count == 1 ) $value['icon'] = $value['icon'].'-blue';
          echo '<li class="tab'.$key.'" onclick="tab_filter(\''.$key.'\','.$jsn_tab_filter.')"><a href="#'.$value['id'].'"><span class="'.$value['icon'].'"></span></a></li>';
        }
        echo "</ul>";
        foreach ( $tab_filter as $key => $value ){
          echo '<div id="'.$value['id'].'">';
          do_action( $value['action'] , $edit );
          echo "</div>";
        }
        echo "</div>";
      }
    }
  }

  /*
   * license notification on ukuupeople screen
   */
  function license_notification() {
    $screen = get_current_screen();
    $valStatus = false;
    $addonarr = array(
      'ukuu_gravity_form' => 'UkuuGravityForms' ,
      'ukuupeople_import' => 'ukuupeople_import_licensing' ,
      'ukuupeople_mailchimp' => 'UKUU_MAILCHIMP',
      'ukuupeople_give' => 'UkuuGive' ,
      'ukuupeople_google' => 'UKUU_GOOGLE' ,
    );
    foreach ( $addonarr as $key => $value ) {
      if ( class_exists( $value ) ) {
        $key = get_option( $key.'_license_status' );
        if( $key != 'valid') {
          $valStatus = true;
        }
      }
    }

    if( $valStatus && ( ( $screen->id == 'wp-type-contacts' ) || ( $screen->id == 'wp-type-activity' ) || ( $screen->id == 'edit-wp-type-activity' ) || ( $screen->id == 'edit-wp-type-contacts' ) ) ) {
?>
      <div class="ukuupeople-notification">
        <div class="ukuupeople-container">
        <div class="ukuu-logo-notification">
        <img src="<?php echo plugins_url( '../images/ukuu-icon.png' ,__FILE__); ?>" width="60" height="60" />
        </div>
        <div class="ukuupeople-purchase-addon"><a class="button button-primary ukuupeople-purchase-button"  href="http://ukuupeople.com/add-ons/" target="_blank"><span>Purchase Add-on License</span></a></div>
        <div class="ukuupeople-purchase-description">Thank you for using UkuuPeople! You are currently using an UkuuPeople add-on that does not have an active license key. Please enter your license key <a href="<?php echo admin_url( 'edit.php?post_type=wp-type-contacts&page=licenses' ); ?>" >here</a> or visit our website to <a href="http://ukuupeople.com/add-ons/" target="_blank">purchase a license key</a> for the add-on.</div>
        </div>
      </div>
<?php
    }

    // Dialog box for Contact and touchpoint page
    if ( $screen->id == 'wp-type-activity' || $screen->id == 'edit-wp-type-activity' || $screen->id == 'wp-type-contacts' || $screen->id == 'edit-wp-type-contacts' ) {
      // Common scripts
      wp_enqueue_script( 'jquery-ui-dialog' );
      // Common styles
      wp_enqueue_style( "wp-jquery-ui-dialog" );
      global $wp_version;
      if ( $wp_version >= '4.7' ) {?>
      <script>
          jQuery(document).ready(function() {
              jQuery( ".wrap a.page-title-action" ).attr("href", "#");
              jQuery( ".wrap a.page-title-action" ).click(function() {
                console.log('page-title-action clicked');
                  jQuery( "#dialog" ).dialog( "open" );
                });
            });
      </script><?php
      }
      elseif ( $wp_version <= '4.2.7' ) {?>
      <script>
          jQuery(document).ready(function() {
              jQuery( ".wrap h2 a" ).attr("href", "#");
              jQuery( ".wrap h2 a" ).click(function() {
                  jQuery( "#dialog" ).dialog( "open" );
                });
            });
      </script><?php
        } else {?>
      <script>
          jQuery(document).ready(function() {
              jQuery( ".wrap h1 a" ).attr("href", "#");
              jQuery( ".wrap h1 a" ).click(function() {
                  jQuery( "#dialog" ).dialog( "open" );
                });
            });
      </script><?php
        }
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
    }

    if ( $screen->id == 'wp-type-activity' || $screen->id == 'edit-wp-type-activity' ) {
      $Url = admin_url( 'post-new.php?post_type=wp-type-activity' );
      ?>
      <div style="display:none" id="dialog" title="<?php _e( 'Add New','UkuuPeople') ?>">
         <table>
         <tr>
         <td><?php echo __( 'TouchPoint Contact', 'UkuuPeople' ); ?></td>
         <td><input type="text" name="touchpoint_contact_name" placeholder="<?php _e('Start typing name', 'UkuuPeople') ?>"></td>
         </tr>
         <input type="hidden" id="touchpoint_contact_id">
         <tr><td></td><td><input type="button" class="button button-primary" value="Create" name="wp-touchpoint-contact-select" redirect="<?php echo $Url?>"></td></tr>
         </table>
         </div>
      <?php
    }
    if ( $screen->id == 'wp-type-contacts' || $screen->id == 'edit-wp-type-contacts' ) {
      $indUrl = admin_url( 'post-new.php?post_type=wp-type-contacts&ctype=wp-type-ind-contact' );
      $orgUrl = admin_url( 'post-new.php?post_type=wp-type-contacts&ctype=wp-type-org-contact');
      ?>
      <div style="display:none" id="dialog" title="<?php _e( 'Add New','UkuuPeople') ?>">
      <label for="wp-type-ind-contact"><input name="wp-contact-type-select" type="radio" value="wp-type-ind-contact" id="wp-type-ind-contact" redirect="<?php echo $indUrl?>"><?php _e('Human', 'UkuuPeople'); ?></label><br/> <label for="wp-type-org-contact"><input name="wp-contact-type-select" type="radio" value="wp-type-org-contact" id="wp-type-org-contact" redirect="<?php echo $orgUrl?>"><?php _e('Organization', 'UkuuPeople'); ?></label><br/> </div>
    <?php
    }
    // Dialog box for Contact and touchpoint page
  }

  function ukuupeople_menu() {
    add_submenu_page( null, __( 'Add New Contact', 'UkuuPeople' ), __( 'Add New Contact', 'UkuuPeople' ), 'access_ukuupeoples', 'add-new-contact', array( $this, 'add_new_contact_type') );
    add_submenu_page( 'edit.php?post_type=wp-type-contacts' , __( 'TouchPoint', 'UkuuPeople' ), __( 'TouchPoints', 'UkuuPeople' ), 'access_touchpoints', 'edit.php?post_type=wp-type-activity', '' );
    require_once( UKUUPEOPLE_ABSPATH.'/includes/add-ons.php' );
    $ukuupeople_add_ons_page = add_submenu_page( 'edit.php?post_type=wp-type-contacts', __( 'UkuuPeople Add-ons', 'UkuuPeople' ), __( 'Add-ons', 'UkuuPeople' ), 'install_plugins', 'ukuupeople-addons', 'ukuupeople_add_ons_page' );
    if ( current_user_can( 'manage_categories' ) ) {
      require_once( UKUUPEOPLE_ABSPATH.'/includes/settings.php' );
      add_submenu_page( 'edit.php?post_type=wp-type-contacts', __( 'setting', 'UkuuPeople' ), __( 'Settings', 'UkuuPeople' ), 'access_ukuupeoples','settings' , 'settings' );
      add_submenu_page( null, '', 'Google App Integration', 'access_ukuupeoples', 'licenses', 'licenses' );
      add_submenu_page( NULL , '', 'googleapp', 'access_ukuupeoples', 'googleapp', 'googleapp' );
    }

    add_submenu_page( NULL , '', 'View Ukuu People', 'access_ukuupeoples', 'view-ukuupeople', array($this,'view_ukuu_people') );
    add_submenu_page( NULL , '', 'View Ukuu TouchPoint', 'access_touchpoints', 'view-touchpoint', array($this,'view_touchpoint') );
  }

  function ukuuCRM_dashboard_setup() {
    wp_add_dashboard_widget (
      'ukuuCRM-dashboard-activities-widget',
      __( 'My Activities', 'UkuuPeople' ),
      array( $this , 'ukuuCRM_dashboard_activities_content' ),
      $control_callback = null
    );
    wp_add_dashboard_widget (
      'ukuuCRM-dashboard-favorites-widget',
      __( 'My Favorites' ,'UkuuPeople' ),
      array( $this, 'ukuuCRM_dashboard_favorites_content' ),
      $control_callback = null
    );
    wp_add_dashboard_widget (
      'ukuuCRM-dashboard-createactivity-widget',
      __( 'Quick Add TouchPoint' ,'UkuuPeople' ),
      array( $this, 'ukuuCRM_dashboard_createactivity_content' ),
      $control_callback = null
    );
	}

  // Function for adding color option for TouchPoints
  function add_color_fields( $taxonomy_name ) {
    ?>
    <div>
      <label><?php __( 'Choose color for', 'UkuuPeople' ); ?> TouchPoint</label>
         <input type="radio" name="category-radio" id="act-color-1" value="#666" style="display: inline-block; width: 25px;" />
        <label for="act-color-1" style="display: inline-block; width: auto; vertical-align: top;"><div style="width: 30px;height: 20px;border-width: 1px;background-color:#666;"></div></label>
        <input type="radio" name="category-radio" id="act-color-2" value="#FE4D39" style="display: inline-block; width: 25px;" />
        <label for="act-color-2" style="display: inline-block; width: auto; vertical-align: top;"><div style="width: 30px;height: 20px;border-width: 1px;background-color:#FE4D39;"></div></label>
        <input type="radio" name="category-radio" id="act-color-3" value="#E6397A" style="display: inline-block; width: 25px;" />
        <label for="act-color-3" style="display: inline-block; width: auto; vertical-align: top;"><div style="width: 30px;height: 20px;border-width: 1px;background-color:#E6397A;"></div></label>
    </div></br>
    <?php
  }

  /**
   * save our custom fields and all term related data.
   *
   * @param type $term_id
   */
  function save_color_fields( $term_id ) {
    //collect all term related data for this new taxonomy
    $term_item = get_term( $term_id,'wp-type-activity-types' );
    $term_slug = $term_item->slug;
    if ( isset( $_POST['category-radio'] ) ) {
      //collect our custom fields
      $term_category_radio = sanitize_text_field( $_POST['category-radio'] );
      //save our custom fields as wp-options
      update_option( 'term_category_radio_' . $term_slug, $term_category_radio );
    }
  }

  function publish_post_privately( $new_status, $old_status, $post ) {
    if ( ( $post->post_type == 'wp-type-contacts' || $post->post_type == 'wp-type-activity' ) && $new_status == 'publish' && $old_status  != $new_status ) {
      $post->post_status = 'private';
      wp_update_post( $post );
    }
  }

  /**
   * Unset the All/Published/Trash menu filter from WP-Type-Activity and WP-Type-Contact list view.
   *
   * @param type $views
   * @return type
   */
  function remove_views( $views ) {
    $views = array();
    return $views;
  }

  /**
   * Remove view link from activities post.
   *
   * @param type $actions
   * @return type
   */
  function remove_row_actions( $actions ) {
    if ( get_post_type() === 'wp-type-activity' ) {
      $viewURL = admin_url('admin.php?page=view-touchpoint&touchpoint_id='.get_the_ID());
      $actions['view'] = "<a href={$viewURL}>View</a>";
    }
    if( get_post_type() === 'wp-type-contacts' ) {
      unset( $actions['edit'] );
      unset( $actions['view'] );
      unset( $actions['trash'] );
      unset( $actions['inline hide-if-no-js'] );
    }
    return $actions;
  }

  /**
   * hide screen option for simple-fields meta box.
   *
   * @param type $simple_field_setting
   * @return type
   */
  function hide_simple_side_field_settings( $simple_field_setting, $post ) {
    if ( $post->post_type == 'wp-type-activity' || $post->post_type == 'wp-type-contacts' ) {
      $simple_field_setting = false;
    }
    return $simple_field_setting;
  }

  /**
   * Returns filter text.
   *
   * @global type $wpdb
   * @param type $text
   * @return type
   */
  function change_publish_button( $translation, $text ) {
    global $post_type;
    if ( $post_type == 'wp-type-contacts' ||  $post_type == 'wp-type-activity' ) {
      /* change for publish */
      if ( $text == 'Publish' )
        return __( 'Create', 'UkuuPeople' );
    }
    return $translation;
  }

  /**
   * Returns display name for $id.
   *
   * @global type $wpdb
   * @param type $id
   * @return type
   */
  function change_related_org_value( $title, $id = NULL ) {
    if ( !empty( $id ) && 'wp-type-contacts' == get_post_type( $id ) ) {
      $display = get_post_meta( $id , 'wpcf-display-name', true );
      $title = $display;
    }
    return $title;
  }

  /**
   * parse query for custom search.
   *
   * @param type $query
   */
  function posts_filter_touchpoint_contact( $query ) {
    $qv = &$query->query_vars;//grab a reference to manipulate directly
    Global $pagenow;
    if( $pagenow =='edit.php' && isset( $_GET['post_type'] ) && 'wp-type-contacts' == $_GET['post_type']  && $query->is_main_query() ) {
      /* If this drop-down has been affected, add a meta query to the query
       *
       */
      if( !empty( $_GET['s'] ) ) {
        global $wpdb;
        // custom filter for display name //
        $meta_value = $_GET['s'];
        $terms = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT ID
               FROM $wpdb->posts
               LEFT JOIN $wpdb->postmeta
               ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND post_type = 'wp-type-contacts'
               AND ((meta_key='wpcf-display-name' and meta_value like %s) OR (meta_key='wpcf-email' and meta_value = %s)
               OR (meta_key='wpcf-first-name' and meta_value like %s ) OR (meta_key='wpcf-last-name' and meta_value like %s))
               ",
               $meta_value,
               $meta_value,
	        $meta_value,
               $meta_value
        ) , OBJECT );

        global $wp_version;
        if ( !empty( $terms ) ) {
          $postID = array_map ( function ( $v ){ return $v->ID; }, $terms );
          $qv['s']= '';
          $qv['post__in'] =$postID;
        } ?>
        <script src="<?php echo includes_url().'js/jquery/jquery.js'; ?>"></script>
        <script type="text/javascript">
           jQuery( function($){
               version = '<?php echo $wp_version; ?>';
               var str = document.getElementsByName('s')[0].value;
               if ( version <= '4.2.4' ) {
                 $('.post-type-wp-type-contacts .wrap h2 .subtitle').html("<?php _e( 'Search results for','UkuuPeople'); ?> '"+str+"' ");
               } else {
                 $('.post-type-wp-type-contacts .wrap h1 .subtitle').html("<?php _e( 'Search results for','UkuuPeople'); ?> '"+str+"' ");
               }
             });
        </script><?php
            } else {
        // parse query to make default display sorted by first name and alphabetically //
        $qv['order'] = 'ASC';
        $qv['orderby'] = 'meta_value';
        $qv['meta_key'] = 'wpcf-display-name';
      }
    }
  }

  /**
   * Custom metabox for TouchPoint list.
   */
  function ukuu_custom_touchpoint() {
    //To hide screen options
    global $wp_meta_boxes;
    unset( $wp_meta_boxes['wp-type-contacts']['side']['high']['wpcf-marketing'] );
    unset( $wp_meta_boxes['wp-type-activity']['side']['high']['wpcf-marketing'] );
    unset( $wp_meta_boxes['wp-type-contacts']['side']['core']['categorydiv'] );
    unset( $wp_meta_boxes['wp-type-contacts']['normal']['default']['wpcf-post-relationship'] );
    unset( $wp_meta_boxes['wp-type-contacts']['normal']['core']['slugdiv'] );

    $screens = array( 'wp-type-activity' );
    foreach ( $screens as $screen ) {
      add_action( 'edit_form_after_editor', array( $this , 'ukuu_custom_touchpoint_list' ) );
    }
  }

  /**
   * Content callback for custom metabox TouchPoint list.
   */
  function ukuu_custom_touchpoint_list( $post ) {
    if ( isset( $post->ID ) && $post->post_type == 'wp-type-activity' ) {
      // To change Dropdown email list to display name//
      global $wpdb;
      //  To change Dropdown email list to display name //
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
      $contact_id = '';
      if ( isset( $_GET['cid'] ) ) $contact_id = $_GET['cid'];

      echo "<input type='hidden' value='$contact_id' name='hidden_cid'>";
      $post_term = wp_get_post_terms( $post->ID, 'wp-type-activity-types', array("fields" => "names") );
      $acttype = get_terms( 'wp-type-activity-types' ,'hide_empty=0' );
      echo '<select name="touchpoint-list" id="touchpoint-list" class="postbox form-control" required="">';
      if( empty( $post_term ) ){
        echo "<option value='' selected>".__( 'Select TouchPoint Type', 'UkuuPeople' )."</option>";
      }
      foreach ($acttype as $key => $value ) {
        if( isset( $post_term[0] ) && $value->name == $post_term[0] ){
          echo "<option value=".$value->slug." selected>".$value->name."</option>";
        } else {
          echo "<option value=".$value->slug.">".$value->name."</option>";
        }
      }
      echo "</select>";
    }
  }

  /**
   * TouchPoint list for dashboard.
   *
   */
  public static function ukuuCRM_dashboard_activities_content() {
    global $current_user;
    $args = array(
      'post_type' => 'wp-type-contacts',
      'post_status' => array( 'private' , 'publish' ),
      'posts_per_page'=> 1,
      'meta_query' => array(
        array(
          'key' => 'wpcf-email',
          'value' => $current_user->user_email,
        )
      )
    );
    $postslist = get_posts( $args );
    $args = array(
      'post_type' => 'wp-type-activity', 'posts_per_page'=> 4, 'meta_query' => array(
        'relation' => 'AND',
        array(
          'key'     => 'wpcf-status',
          'value'   => 'scheduled',
        ),
        array(
          'key'     => 'wpcf_assigned_to',
          'value'   => $postslist[0]->ID,
          'compare' => 'LIKE'
        ),
        array(
          'key'     => 'wpcf-startdate',
          'value'   => strtotime(date('Y-m-d')),
          'compare' => '>=',
        ),
      ),
      'meta_key'         => 'wpcf-startdate',
      'orderby'          => 'meta_value_num',
      'order'            => 'ASC',
      'suppress_filters' => true,
    );
    $loop = new WP_Query( $args );
    global $base_url;
    echo "<div class='activity-wrapper'>";
    while ( $loop->have_posts() ) : $loop->the_post();
    $iduser = get_the_author();
    $custom = get_post_custom( get_the_ID() );
    if ( isset( $custom['wpcf-startdate'][0] ) && !empty( $custom['wpcf-startdate'][0] ) && isset( $custom['wpcf-status'][0] ) && $custom['wpcf-status'][0] == 'scheduled' ) {
      $URL = get_edit_post_link( get_the_ID() );
      echo "<div class='user_activity' style='overflow:auto;'><div class='activity_date' style='background-color:#0074a2;'><div class='month-act'>";
      echo date( 'F' ,$custom['wpcf-startdate'][0] );
      echo "</div><div class='day-act'>";
      echo date( 'd' ,$custom['wpcf-startdate'][0] );
      echo "</div></div>";
      echo "<div class='activity_info'>";
      if( !wp_is_mobile() ) {
        echo "<div><a href='$URL'><strong>";
        the_title();echo "</a></strong></div>";
      } else {
        echo "<div><a href='$URL'><strong>";
        echo substr( get_the_title(), 0, 15 ).'...';echo "</a></strong></div>";
      }
      _e('created by', 'UkuuPeople');
      echo " <span><a href='#'>$iduser</a></span>";
      $with_id = get_post_meta( get_the_ID() , '_wpcf_belongs_wp-type-contacts_id', true );
      if ( $with_id ) {
        $display_name =  get_post_meta( $with_id , 'wpcf-display-name', true );
        $contact_view_url = get_edit_post_link ( $with_id );
        echo "<div>" . __('with', 'UkuuPeople') . " <span><a href='$contact_view_url'>".$display_name."</a></span></div>";
      }
      echo "</div><div class='activity_time' style='background-color:#0074a2;'><div class='dayName'>";
      echo date( 'l' ,$custom['wpcf-startdate'][0] );echo "</div><div class='time'>";
      echo date( 'g:i a' ,$custom['wpcf-startdate'][0] );
      echo "</div></div></div>";
    }
    endwhile;
    echo "</div>";
  }

  /**
   * TouchPoint favorites list for dashboard.
   */
  public static function ukuuCRM_dashboard_favorites_content() {
    $user_id = get_current_user_id();
    $posts = self::retrieve_favorites();
    global $base_url;
    echo "<div class='dashboard-favorites-wrapper'>";
    if ( !empty( $posts ) ) {
      foreach ( $posts as $k => $postID ) {
        $custom = get_post_custom( $postID );
        echo "<div class='dashboard-favorites-list'>";
        echo "<div class='contact-image'>";
        if ( ! empty( $custom['wpcf-contactimage'][0] ) ) {
          echo "<img src='".$custom['wpcf-contactimage'][0]."' width='69' height='69'>";
        } else {
          $avatar = get_avatar( $custom['wpcf-email'][0] ,69 );
          echo $avatar;
        }
        echo "</div>";
        echo '<div class="display-name">';
        echo "<span class='contact-name'>";
        $URL = get_edit_post_link( $postID );
        echo "<a href='{$URL}'><b>";
        if( isset( $custom['wpcf-display-name'][0]  ) ) echo $custom['wpcf-display-name'][0];
        echo "</b></a></span>";
        if( isset( $custom['wpcf-email'][0] ) ) echo "<br/><span class='dashboard-email'><a href='mailto:{$custom['wpcf-email'][0]}' title='email'><u>{$custom['wpcf-email'][0]}</u></a></span>";
        if( isset( $custom['wpcf-phone'][0] ) ) echo "<br/><span class='dashboard-email'>{$custom['wpcf-phone'][0]}</span>";

        echo '</div>';
        echo "<div class='phone'>";
        echo '<img src="' . plugins_url( '../images/phone-call.jpg', __FILE__ ) . '" />';
        echo "</div>";
        echo "<div class='clear'></div>";
        echo "</div>";
      }
    }
    echo "</div>";
  }

  /**
   * Quick Add TouchPoint
   */
  public static function ukuuCRM_dashboard_createactivity_content() {
    wp_enqueue_media();
    wp_enqueue_script( 'thickbox' );
    wp_enqueue_style( 'thickbox' );
    wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array('jquery','media-upload','thickbox') );
    // Common scripts
    wp_enqueue_script( 'jquery-ui-dialog' );
		// Common styles
    wp_enqueue_style( "wp-jquery-ui-dialog" );
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_script( 'timepicker ui' , UKUUPEOPLE_RELPATH.'/includes/CMB2/js/jquery-ui-timepicker-addon.min.js' );
    wp_enqueue_style( 'datetimepicker css' , UKUUPEOPLE_RELPATH.'/includes/CMB2/css/cmb2.min.css' );
    $acttype = get_terms( 'wp-type-activity-types' ,'hide_empty=0' );
    $items = get_id_and_displayname();
    $data  = "<select name='touchpoint_assign_name'>";
    $data .= "<option value='' selected>".__( 'Select', 'UkuuPeople' )."..</option>";
    foreach( $items as $item_id => $item_value ) {
      $data .= "<option value=".$item_id.">".$item_value."</option>";
    }
    $data .= "</select>";
    ?>
    <div id="dialog" title="<?php _e( 'Add New','UkuuPeople') ?>">
       <table>
       <tr><td><?php echo __( 'Assigned To', 'UkuuPeople' ); ?></td><td><?php echo $data; ?></td></tr>
       </table>
    </div>
    <script>
    <!--Custom touchpoint contact dialog box-->
       jQuery(function($) {
           var assigneeval = [];
           $( "#dialog" ).dialog({
             modal : true,
                 autoOpen: false,
                 show: {
               effect: "blind",
                   duration: 1000
                   },
                 hide: {
               effect: "explode",
                   duration: 1000
                   },
                 buttons: {
                   Ok: function() {
                   $( this ).dialog( "close" );

                   $newtext = $( "select[name='touchpoint_assign_name'] option:selected" ).text();
                   $prvtxt = jQuery(".quickadd input[name='touchpoint_assign_name_display']").val();

                   if ($prvtxt == "") {
                      jQuery( "#touchpoint_assign_name_display" ).val( $newtext );
                      $prval = $( "select[name='touchpoint_assign_name'] option:selected" ).val();
                      assigneeval.push($prval);
                    }

                   else {
                      $prval = $( "select[name='touchpoint_assign_name'] option:selected" ).val();
                      if(jQuery.inArray($prval, assigneeval) === -1) {
                          assigneeval.push($prval);
                          jQuery(".quickadd input[name='touchpoint_assign_name_display']").val($prvtxt+","+$newtext);
                        }
                    }
                   jQuery( "#touchpoint_assign_id" ).val( assigneeval );
                   $( "#touchpoint_assign_name_display" ).show();
                 }
               }
            });

           $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dassign']" ).click(function() {
               $( "#dialog" ).dialog( "open" );
             });
           var datearr = {
           dateFormat : 'mm-dd-yy',
           showButtonPanel: true,
           changeMonth: true,
           changeYear: true,
           yearRange: "1900:c+10",
           };
           var timearr = {
           showDuration: true,
           scrollDefault: 'now',
           timeFormat: 'hh:mm tt',
           };
           $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dsdate']" ).datepicker(datearr);
           $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dedate']" ).datepicker(datearr);
           $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dstime']" ).timepicker(timearr);
           $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='detime']" ).timepicker(timearr);
      });
    </script>
    <form name="quickaddform" enctype="multipart/form-data" method="post" id="quickAddform">
    <table class="quickadd">
      <tr>
      <td class="quickadd-label"><?php _e( 'Contact*', 'UkuuPeople' ) ?></td>
      <td><input type="text" name="dname" required placeholder="<?php _e( 'Start Typing Name', 'UkuuPeople' ) ?>"><input id="dcontact_id" type="hidden" name="contact_id"></td>
      </tr>

      <tr>
      <td class="quickadd-label"><?php _e( 'Subject*', 'UkuuPeople' ) ?></td>
      <td><input type="text" name="dsubject" required placeholder="<?php _e( 'Enter Subject', 'UkuuPeople' ) ?>"></td>
      </tr>

      <tr>
      <td class="quickadd-label"><?php _e( 'Type*', 'UkuuPeople' ) ?></td>
      <td><select class="form-control" required="" name="dtype"><?php
      echo "<option value='' selected>".__( 'Select TouchPoint Type', 'UkuuPeople' )."</option>";
      foreach ( $acttype as $key => $value ) {
        echo "<option value=".$value->slug.">".$value->name."</option>";
      }
      ?>
      </select></td>
      </tr>

      <tr>
      <td class="quickadd-label"><?php _e( 'Start*', 'UkuuPeople' ) ?></td>
      <td>
      <input type="text" name="dsdate" required><span class="ukuucalendar"></span>
      <input type="text" name="dstime"><span class="ukuuclock"></span>
      </td>
      </tr>

      <tr>
      <td class="quickadd-label"><?php _e('End', 'UkuuPeople') ?></td>
      <td>
      <input type="text" name="dedate"><span class="ukuucalendar"></span>
      <input type="text" name="detime"><span class="ukuuclock"></span>
      </td>
      </tr>

      <tr>
      <td class="quickadd-label"><?php _e('Details', 'UkuuPeople') ?></td>
      <td><textarea rows="3" cols="20" name="ddetails"></textarea></td>
      </tr>

      <tr>
      <td class="quickadd-label"></td>
      <td><input type="button" name="dupload" value="<?php _e('Click to Upload Attachments', 'UkuuPeople') ?>">
      <input type="text" id="filename" name="filename" style="display:none">
      </td>
      </tr>

      <tr>
      <td class="quickadd-label"></td>
      <td><input type="button" name="dassign" value="<?php _e('Click to Select Assignee', 'UkuuPeople') ?>">
      <input type="text" id="touchpoint_assign_name_display" name="touchpoint_assign_name_display" style="display:none">
      <p id="new-tag-post_tag-desc" class="seprate" style ="color:#666;font-style:italic;display:none;">Separate assigness with commas</p>
      <input type="hidden" id="touchpoint_assign_id" name="touchpoint_assign_id"></td>
      </tr>

      </table>
      <input type="submit" class="button button-primary" name="quickAdd" value="<?php _e('Create', 'UkuuPeople') ?>">
      </form>
    <?php
  }

  /*
   * Retrive all the favorite contacts of logged in User.
   */
  public static function retrieve_favorites() {
    global $wpdb;
    $uid = $user_ID = get_current_user_id();
    $table_name = $wpdb->prefix . "ukuu_favorites";
    $retrieve_data = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT *
               FROM $table_name
               where user_id = %s
               ",
               $uid
    ) , OBJECT_K );

    if ( !empty( $retrieve_data ) ) {
      $is_fav = array();
      foreach( $retrieve_data as $k => $v ) {
        $post_id = $v->user_favs;
        $postttt = get_post( $post_id );
        if ( get_post_status( $post_id ) == 'trash' || ( FALSE === get_post_status( $post_id ) ) ) {
          $wpdb->delete(
            $table_name,
            array(
              'user_id' => $uid,
              'user_favs' => $post_id,
            )
          );
        } else {
          $is_fav[$k] = $post_id;
        }
      }
      return $is_fav;
    }
    return NULL;
  }

  /**
   * Add favorite for post id.
   */
  function ukuu_add_to_fav() {
    check_ajax_referer( 'star-ajax-nonce', 'security', false );
    $post_id = $_REQUEST['post_id'];
    $entry = $_REQUEST['entry'];
    global $wpdb;
    $table_name = $wpdb->prefix . 'ukuu_favorites';
    $uid = $user_ID = get_current_user_id();
    if ( $entry == "del" ) {
      $message = "Removed as favorite";
      $wpdb->delete(
        $table_name,
        array(
          'user_id' => $uid,
          'user_favs' => $post_id,
        )
      );
      wp_die( $message );
    } elseif ( $entry == "add" ) {
      $wpdb->insert(
        $table_name,
        array(
          'user_id' => $uid,
          'user_favs' => $post_id,
        )
      );
      $message = "Added as favorite";
    }
    wp_die( $message );
  }

  /* ajax call to get activity list
   *
   */
  function prepare_query( ) {
    $cid = $_REQUEST['cid'];
    $paged = isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1;
    $limit = isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : -1;
    $offset = ( $paged - 1 ) * $limit;
    $lists = $this->get_contact_activity_list( $limit , $paged , $cid );
    echo $this->activity_list( $cid , $lists );
    die();
  }

  /* $cid as contact id and $lists as activity list related to $cid
   *
   * @param type $cid as post id, $lists as touchpoint list
   */
  function activity_list( $cid , $lists ) {
    $count = 0;
    $listdata = '';
    foreach ( $lists as $key => $value ) {
      $custom = get_post_custom( $value->ID );
      $title = $value->post_title;
      $ids = $value->ID;
      if ( $count%2 == 0 )  $listdata .= "<tr><th class='check-column' scope='row'><input type='checkbox'></th><td>"; else $listdata .= "<tr class='alternate'><th class='check-column' scope='row'><input type='checkbox'></th><td>";
      $listdata .= $this->activity_startdate ( $ids );
      $listdata .= "</td><td>";
      $URL = get_edit_post_link( $ids );
      $listdata .= $title;
      $listdata .= '<div class="row-actions"><span class="edit"><a title="Edit this item" href="'.$URL.'">'.__( 'Open', 'UkuuPeople' ).'</a></span></div>';
      $listdata .=  '</td><td class="activity-desc">';
      $details = '';
      if ( isset( $custom['wpcf-details'][0] ) ) $details = $custom['wpcf-details'][0];
      $listdata .= $details;
      $listdata .=  '</td><td>';
      $listdata .= $this->activity_assigned ( $ids );
      $listdata .=  '</td><td>';
        if ( $custom['wpcf-status'][0] == 'scheduled' ) $listdata .= "<div class='ukuuclock'></div>";
        else if( $custom['wpcf-status'][0] == 'completed' ) $listdata .= "<div class='ukuucheck'></div>";

      $listdata .= "</td></tr>";
      $count++;
    }
    return $listdata;
  }

  /**
   * custom touchpoint list with pagination.
   */
  function ukuu_activity_list_meta_box() {
    // callback on custom touchpoint list for pagination //
    wp_enqueue_script( 'ukuu-function', UKUUPEOPLE_RELPATH. '/script/function.js' , array() );
    $lists = $this->get_contact_activity_list( 5 , 0 , get_the_ID() );
    $activity_listcount = sizeof( $lists );
    $total_list_value = $this->get_contact_activity_list( -1, 0 , get_the_ID() );
    $total_list_count = ceil(sizeof( $total_list_value )/5);
    $button_disable = '';
    if ( $total_list_count == 1 ) {
      $button_disable = 'disabled';
    }
    echo '<div id="activity"><div class="inside">';
    if ( $activity_listcount ) {
      $page_links[] = "<span style='color:#959595'>".sizeof($total_list_value)." items</span>";
      $page_links[] = "<input class='first-page' type='button' value='&laquo;' onclick='refreshActivityList( ".get_the_ID()." , $activity_listcount ,\"first-page\")' disabled>";
      $page_links[] = "<input class='pre-page' type='button' value='&lsaquo;' onclick='refreshActivityList( ".get_the_ID()." , $activity_listcount ,\"pre-page\")' disabled>";
      $page_links[] = '<span class="paging-input"><input id="current-value" class="current-page" type="text" size="1" value="1" name="paged" title="Current page" readonly> of <span id="total-value" class="total-pages">'.$total_list_count.'</span></span>';
      $page_links[] = "<input class='next-page' type='button' value='&rsaquo;' onclick='refreshActivityList( ".get_the_ID()." , $activity_listcount ,\"next-page\")' ". $button_disable.">";
      $page_links[] = "<input class='last-page' type='button' value='&raquo;' onclick='refreshActivityList( ".get_the_ID()." , $activity_listcount ,\"last-page\")' ". $button_disable.">";
      $pagination_links_class = 'pagination-links';
      $output = "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';
      echo "<div class='page-tablenav'>$output</div>";
      ?>
      <table class='widefat fixed posts'>
         <thead><tr>
         <th id="ab" class="manage-column column-ab check-column" style="" scope="col"><input type="checkbox" /></th>
         <th class="manage-column activity-datetime" style="" scope="col"><?php _e( 'Date and Time', 'UkuuPeople' ) ?></th>
         <th class="manage-column sortable desc activity-subject" style="" scope="col"><?php _e( 'Subject', 'UkuuPeople' ) ?></th>
         <th class="manage-column" style="" scope="col"><?php _e( 'Description', 'UkuuPeople' ) ?></th>
         <th class="manage-column activity-assign" style="" scope="col"><?php _e( 'Assigned to', 'UkuuPeople' ) ?></th>
         <th class="manage-column activity-status" style="" scope="col"><?php _e( 'Status', 'UkuuPeople' ) ?></th>
         </tr></thead>
         <tfoot><tr>
         <th class="manage-column column-ab check-column" style="" scope="col"><input type="checkbox" /></th>
         <th class="manage-column" style="" scope="col"><?php _e( 'Date and Time', 'UkuuPeople' ) ?></th>
         <th class="manage-column sortable desc" style="" scope="col"><?php _e( 'Subject', 'UkuuPeople' ) ?></th>
         <th class="manage-column" style="" scope="col"><?php _e( 'Description', 'UkuuPeople' ) ?></th>
         <th class="manage-column" style="" scope="col"><?php _e( 'Assigned to', 'UkuuPeople' ) ?></th>
         <th class="manage-column" style="" scope="col"><?php _e( 'Status', 'UkuuPeople' ) ?></th>
         </tr></tfoot>
         <tbody id='the-list'> <?php
         $main_table = self::activity_list(get_the_ID(), $lists);
         $main_table = apply_filters( 'display_touchpoints', $main_table, get_the_ID() , $lists );
         echo $main_table; ?>
         </tbody>
      </table>
      <div id="" class="form-item form-item-select wpcf-form-item wpcf-form-item-select"><?php _e( 'Show', 'UkuuPeople' ) ?>
       <select id="mySelect" class="wpcf-relationship-items-per-page wpcf-form-select form-select select" name="_wpcf_relationship_items_per_page" onchange="refreshActivityList(<?php echo get_the_ID(); ?>, <?php echo sizeof($total_list_value) ?> , '')">
         <option class="wpcf-form-option form-option option" value=""><?php _e( 'All', 'UkuuPeople' ) ?></option>
         <option class="wpcf-form-option form-option option" selected="selected" value="5">5</option>
         <option class="wpcf-form-option form-option option" value="10">10</option>
         <option class="wpcf-form-option form-option option" value="15">15</option>
       </select>TouchPoints
      </div>
      <?php } echo "</div></div>";
  }

  /*
   * returns the activity list related to contact id as $cid and $limit as posts per page and $paged as offset value
   */
  function get_contact_activity_list( $limit , $paged , $cid ) {
    $args = array(
      'post_type' => 'wp-type-activity',
      'posts_per_page' => $limit,
      'paged' => $paged,
      'meta_query' => array(
        array(
          'key'     => '_wpcf_belongs_wp-type-contacts_id',
          'value'   => $cid,
        ),
      )
    );
    $loop = new WP_Query( $args );
    $lists = array();
    return $loop->posts;
  }

  /*
   * Insert taxonomy terms for custom taxonomy on plugin activation.
   */
function insert_taxonomy_terms() {
  // To add custom taxonomy terms
  $cTypes = array( 'ind' => array('name' => 'Individual', 'slug' => 'wp-type-ind-contact'),'org' => array('name' => 'Organization', 'slug' => 'wp-type-org-contact') ) ;
  self::add_taxonomies($cTypes, 'wp-type-contacts-subtype');
  $aTypes = array( 'meeting' => array( 'name' => 'Meeting', 'slug' => 'wp-type-activity-meeting' ),'phone' => array( 'name' => 'Phone', 'slug' => 'wp-type-activity-phone') , 'note' => array('name' => 'Note', 'slug' => 'wp-type-activity-note' ) , 'contact-form' => array('name' => 'Contact Form', 'slug' => 'wp-type-contactform'));
  self::add_taxonomies( $aTypes, 'wp-type-activity-types' );
  $gTypes = array( 'our-team' => array( 'name' => 'Our Team', 'slug' => 'wp-type-our-team' ) );
  self::add_taxonomies( $gTypes, 'wp-type-group' );
  if ( !get_option( 'ukuupeople_admin_integrate') ) {
    // To update adminstrator to ukuupeople contacts
    $users = get_users( array( 'role' => 'administrator') );
    foreach ( $users as $key => $value ) {
      $args = array(
        'post_type' => 'wp-type-contacts',
        'post_status' => array( 'publish', 'private' ),
        'meta_query' => array(
          array(
            'key'     => 'wpcf-email',
            'value'   => $value->user_email,
          ),
        )
      );
      $loop = new WP_Query( $args );
      if ( empty( $loop->posts ) )
        {
          $people_post = array(
            'post_title'    => $value->user_email,
            'post_type'     => 'wp-type-contacts',
            'post_status'   => 'private',
            'post_author'   => $value->ID ,
          );

          // Insert the people into the database
          $people_ID = wp_insert_post( $people_post );

          // update post meta for peeple
          $name = $value->user_firstname;
          if ( $name == '' )
            update_post_meta( $people_ID, 'wpcf-first-name', 'admin');
          else
            update_post_meta( $people_ID, 'wpcf-first-name', $name);

          $name = $value->user_lastname;
          if ( $name == '' )
            update_post_meta( $people_ID, 'wpcf-last-name', 'admin');
          else
            update_post_meta( $people_ID, 'wpcf-last-name', $name);

          update_post_meta( $people_ID, 'wpcf-display-name', $value->display_name);
          update_post_meta( $people_ID, 'wpcf-email', $value->user_email );

          wp_set_object_terms( $people_ID, 'wp-type-ind-contact', 'wp-type-contacts-subtype', true );
          wp_set_object_terms( $people_ID, 'wp-type-our-team', 'wp-type-group', true );
        }
    }
    update_option( "ukuupeople_admin_integrate", true );
  }
  //Code to remove default title and editor form contact and touchpoint.
  remove_post_type_support( 'wp-type-contacts', 'title' );
  remove_post_type_support( 'wp-type-contacts', 'editor' );
  remove_post_type_support( 'wp-type-activity', 'editor' );

  // Activate welcome page
  if ( ! get_transient( '_welcome_screen_activation_redirect' ) ) {
    return;
  }
  // Delete the redirect transient
  delete_transient( '_welcome_screen_activation_redirect' );
  // Redirect to bbPress about page
  wp_safe_redirect( add_query_arg( array( 'page' => 'ukuu-about' ), admin_url( 'index.php' ) ) );
}

  /*
   * Callback for Insert taxonomy terms.
   */
  public static function add_taxonomies ( $terms, $tax ) {
    foreach ( $terms as $termKey => $termValue ) {
      $term = get_term_by('name', $termValue['name'], $tax );
      if ( empty( $term ) ) {
        wp_insert_term(
          $termValue['name'], // the term
          $tax, // the taxonomy
          array(
            'description'=> '',
            'slug' => $termValue['slug'],
          )
        );
      }
    }
  }

  /*
   * Add custom filters for wp-type-contacts and wp-type-activity post listing
   * Add Contact type graph on wp-type-contacts post type listing
   */
  function ukuu_custom_filters_posts() {
    global $typenow;
    $user_ID = get_current_user_id();

    $full_access = FALSE;
    if ( $user_ID == 1 || user_can( $user_ID, 'edit_all_ukuupeoples' ) || user_can( $user_ID, 'read_all_ukuupeoples' ) || user_can( $user_ID, 'delete_all_ukuupeoples' ) || user_can( $user_ID, 'read_all_touchpoints' ) || user_can( $user_ID, 'edit_all_touchpoints' ) || user_can( $user_ID, 'delete_all_touchpoints' ))
    $full_access = TRUE;

    if ( $typenow == "wp-type-contacts" ) {
      wp_enqueue_script( 'd3', UKUUPEOPLE_RELPATH.'/script/d3/d3.min.js' , array() );
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
      $url = admin_url( 'edit.php' );
      $string = $graph = array();
      $filters = array( 'wp-type-group', 'wp-type-tags', 'wp-type-contacts-subtype' );
      foreach ( $filters as $tax_slug ) {
        $tax_obj = get_taxonomy( $tax_slug );
        $tax_name = $tax_obj->labels->name;
        global $wpdb, $current_user;

        if ( ! $full_access ) {
          $terms = $wpdb->get_results( $wpdb->prepare(
                     "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->posts
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND post_type = 'wp-type-contacts'
               AND ( post_author = %d OR post_title = %s )
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
                     $user_ID,
                     $current_user->user_email,
                     $tax_slug ,
                     $tax_slug
                   ) , OBJECT);

           $get_trash = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->posts
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'trash')
               AND post_type = 'wp-type-contacts'
               AND ( post_author = %d OR post_title = %s )
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
                     $user_ID,
                     $current_user->user_email,
                     $tax_slug ,
                     $tax_slug
                   ) , OBJECT);

          $trashCount = 0;
          foreach ( $get_trash as $trash_object ) {
            $trashCount += $trash_object->count;
          }
        }
        else {
          $terms = $wpdb->get_results( $wpdb->prepare(
                     "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->posts
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND post_type = 'wp-type-contacts'
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
                     $tax_slug ,
                     $tax_slug
                   ) , OBJECT);
        }
          echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
          echo "<option value=''>".__( 'Show All','UkuuPeople' )." $tax_name</option>";
          foreach ( $terms as $term ) {
            $selected = isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : null;
            echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
            if ( $tax_slug == "wp-type-contacts-subtype" ) {
              $graph[] = $term->count;
              if ( isset( $_GET['wp-type-contacts-subtype'] ) && $term->slug == $_GET['wp-type-contacts-subtype'] ) {
                $class = ' class="current"';
              } else {
                $class = '';
              }
              $string[]  = "<li class='{$term->slug}'><a href=' ". add_query_arg( array('post_type' => 'wp-type-contacts', 'wp-type-contacts-subtype' => $term->slug) ,$url )."' $class'>{$term->name} </a>($term->count)</li>";
            }
          }
          echo "</select>";
        }
      $count_posts = (array) wp_count_posts( $typenow, 'readable', false );
      unset( $count_posts['auto-draft'] );
      $counts = $count_posts['private'];
     if ( ! $full_access ) {
        $counts = 0;
        foreach( $terms as $k => $v ){
          $counts += $v->count;
        }
      }
      $selected = ( count( $_GET ) == 1 ) ? 'current' : '';
      $string[]  = "<li class='all'><a href='". add_query_arg( array( 'post_type' => 'wp-type-contacts' ) ,$url )."' class='$selected'>".__( 'All','UkuuPeople' )."</a>($counts)</li>";
      $trashCount = isset( $trashCount ) ? $trashCount : $count_posts['trash'];
      $selected = isset($_GET['post_status']) && $_GET['post_status'] == 'trash' ? 'current' : '';
      $string[]  = "<li class='trash'><a href=' ". add_query_arg( array('post_type' => 'wp-type-contacts', 'post_status' => 'trash') ,$url)."' class='$selected'>".__( 'Trash', 'UkuuPeople' )."</a>($trashCount)</li>";
      if ( !empty($graph) ) {
        echo '<div class="graph-main-container" style="width:70% !important">';
        $str = implode($string, ' | ');
        echo '<div class="graph-container clear">';
        if(!wp_is_mobile()) {
          echo "<div id='graph'>
    		<div id='graph-container' style=''>";
          $color = array( 'Individual' => '#0072BB','Organization' => '#30A08B' );
          $contacts_count = $graph;
          echo "<div class='type-indicator'>";
          echo "<div class='ukuu-head'>";
          echo "<table><tr>";
          foreach ( $color as $keys => $values ) {
            echo "<td><svg height='10' width='10'>";
            echo "<circle cx='5' cy='5' r='4' stroke-width='3' fill='$values' />";
            echo "</svg></td>";
            echo "<td style='padding-right:15px;font-size:16px;color: #777777;'><span>".$keys."</span></td>";
          }
          echo "</tr></table>";
          echo "</div></div>";
          echo "<div  id='chart' type='".json_encode( $contacts_count )."' color='".json_encode( array_values($color) )."'></div></div></div>";
          echo "<ul class='subsubsub'>$str</ul>";
        }
        echo "</div></div>";
      }
    }
    if ( $typenow == "wp-type-activity" ) {
      global $current_user;
      $args = array(
        'fields' => 'ids',
        'post_type' => 'wp-type-contacts',
        'post_status' => array( 'private' , 'publish' ),
        'posts_per_page'=> 1,
        'meta_query' => array(
          array(
            'key' => 'wpcf-email',
            'value' => $current_user->user_email,
          )
        )
      );
      $postslist = get_posts( $args );
      wp_enqueue_script( 'd3', UKUUPEOPLE_RELPATH.'/script/d3/d3.min.js' , array() );
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
      $filters = array('wp-type-activity-types');
      $url = admin_url('edit.php');
      $string = $graph = array();
      $graphTouchpoint = array();
      $li_color = array( 'wp-type-activity-meeting' => '#377CB6' , 'wp-type-activity-phone' => '#771D78'  , 'wp-type-activity-note' => '#3DA999' , 'wp-type-contactform' => '#E6397A' , 'other' => '#d3d3d3');
      foreach ( $filters as $tax_slug ) {
        $tax_obj = get_taxonomy( $tax_slug );
        $tax_name = $tax_obj->labels->name;
        global $wpdb;
         if ( ! $full_access ) {
          $que = $wpdb->get_results( $wpdb->prepare(
                   "
               SELECT $wpdb->postmeta.post_id
               FROM $wpdb->postmeta
               WHERE $wpdb->postmeta.meta_value = %s
               AND $wpdb->postmeta.meta_key = 'wpcf-email'
               ",
               $current_user->user_email
               ) ,ARRAY_A);
         $data = explode( ",", $que[0]['post_id']);
         $que = serialize($data);

          $terms = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->users
               LEFT JOIN $wpdb->posts ON ($wpdb->posts.post_author = $wpdb->users.ID)
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND (post_author = %d OR $wpdb->posts.ID IN (SELECT $wpdb->posts.ID FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE ( ($wpdb->postmeta.meta_key = '_wpcf_belongs_wp-type-contacts_id' AND $wpdb->postmeta.meta_value = %d) OR ($wpdb->postmeta.meta_key = 'wpcf_assigned_to' AND $wpdb->postmeta.meta_value = '{$que}') ) AND $wpdb->posts.post_author != %d ))
               AND post_type = 'wp-type-activity'
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
               $user_ID,
               $postslist[0],
               $user_ID,
               $tax_slug,
               $tax_slug
               ) , OBJECT );

          $get_trash = $wpdb->get_results( $wpdb->prepare(
               "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->users
               LEFT JOIN $wpdb->posts ON ($wpdb->posts.post_author = $wpdb->users.ID)
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'trash')
               AND (post_author = %d OR $wpdb->posts.ID IN (SELECT $wpdb->posts.ID FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON ( $wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE ( ($wpdb->postmeta.meta_key = '_wpcf_belongs_wp-type-contacts_id' AND $wpdb->postmeta.meta_value = %d) OR ($wpdb->postmeta.meta_key = 'wpcf_assigned_to' AND $wpdb->postmeta.meta_value = '{$que}') ) AND $wpdb->posts.post_author != %d ))
               AND post_type = 'wp-type-activity'
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
               $user_ID,
               $postslist[0],
               $user_ID,
               $tax_slug,
               $tax_slug
               ) , OBJECT );

          $trashCount = 0;
          foreach ( $get_trash as $trash_object ) {
            $trashCount += $trash_object->count;
          }

        } else {
          $terms = $wpdb->get_results( $wpdb->prepare(
                     "
               SELECT $wpdb->terms.term_id, $wpdb->terms.slug , $wpdb->terms.name, COALESCE(s.count ,0) AS count
               FROM $wpdb->terms
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
               LEFT JOIN (SELECT  COUNT($wpdb->posts.ID) as count, slug , name , $wpdb->terms.term_id FROM $wpdb->posts
               LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
               LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
               LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
               WHERE (post_status = 'publish' OR  post_status = 'private')
               AND post_type = 'wp-type-activity'
               AND slug IS NOT NULL
               AND taxonomy= %s group by slug ) as s
               ON ($wpdb->term_taxonomy.term_id = s.term_id)
               WHERE taxonomy= %s ORDER BY $wpdb->terms.term_id ASC
               ",
               $tax_slug ,
               $tax_slug
               ) , OBJECT);
        }
        echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
        echo "<option value=''>Show All $tax_name</option>";
        foreach ( $terms as $term ) {
          $selected = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : null;
          echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
          if ($tax_slug == "wp-type-activity-types" ) {
            $graphTouchpoint[] = $term->count;
            $selectedColor[$term->slug] = get_option('term_category_radio_' . $term->slug);
            if ( isset( $_GET['wp-type-activity-types'] ) && $term->slug == $_GET['wp-type-activity-types'] ) {
              $class = ' class="current"';
            } else {
              $class = '';
            }
            $li_colors = array_merge($selectedColor, $li_color);
            if ( array_key_exists($term->slug, $li_colors) && !empty($li_colors[$term->slug]) ) {
              $style = $li_colors[$term->slug];
            }
            else {
              $selectedColor[$term->slug] = $style = '#d3d3d3';
            }
            $string[]  = "<li class='{$term->slug}''><svg height='10' width='10'><circle cx='5' cy='5' r='4' stroke-width='3' fill='$style' /></svg> <a href=' ". add_query_arg( array('post_type' => 'wp-type-activity', 'wp-type-activity-types' => $term->slug), $url )."' $class'>{$term->name} </a>($term->count)</li>";
          }
        }
        echo "</select>";
      }
      // Filter post by TouchPoint Contact
      $assignee_contacts = get_id_and_displayname(); // Get assignee_contact
      echo "<select name='touchpoint-assignee' id='touchpoint-assignee' class='postform'>";
      echo "<option value=''>".__( 'Show All', 'UkuuPeople' )." Assignees</option>";
      foreach ( $assignee_contacts as $assignee_contact_id => $assignee_contact_name ) {
        $selected = isset($_GET['touchpoint-assignee']) ? $_GET['touchpoint-assignee'] : null;

        echo '<option value='. $assignee_contact_id , $selected == $assignee_contact_id ? ' selected="selected"' : '','>' . $assignee_contact_name .'</option>';
      }
      echo "</select>";
      $count_posts = (array) wp_count_posts( $typenow, 'readable', false );
      unset($count_posts['auto-draft']);
      $counts = $count_posts['private'];
      // backward compatibility
      if ( isset( $count_posts['publish'] ) )
        $counts += $count_posts['publish'];
      if ( ! $full_access ) {
        $counts = 0;
        foreach( $terms as $k => $v ){
          $counts += $v->count;
        }
      }
      $selected = ( count( $_GET ) == 1 ) ? 'current' : '';
      $string[]  = "<li class='all'><a href=' ". add_query_arg( array( 'post_type' => 'wp-type-activity' ) ,$url )."' class='$selected'>".__( 'All', 'UkuuPeople' )." </a>($counts)</li>";
      $trashCount = isset( $trashCount ) ? $trashCount : $count_posts['trash'];
      $selected = isset( $_GET['post_status'] ) && $_GET['post_status'] == 'trash' ? 'current' : '';
      $string[]  = "<li class='trash'><a href=' ". add_query_arg( array('post_type' => 'wp-type-activity', 'post_status' => 'trash') ,$url )."' class='$selected'>".__( 'Trash', 'UkuuPeople' )."</a>($trashCount)</li>";
      if ( !empty( $graphTouchpoint ) ) {
        echo '<div class="graph-main-container" style="width:70% !important">';
        $str = implode($string, ' | ');
        echo '<div class="graph-container clear">';
        if ( !wp_is_mobile() ) {
          echo "<div id='graph'><div id='graph-container' style=''>";
          $touchpoint_count = $graphTouchpoint;
          $colors = array_merge($selectedColor, $li_color);
          echo "<div  id='chart' type='".json_encode( $touchpoint_count )."' color='".json_encode( array_values($colors) )."'></div></div></div>";
          echo "<ul class='subsubsub'>$str</ul>";
        }
        echo "</div></div>";
      }
    }
  }

  /*
   * add submenu for custom post type
   */
  public static function add_new_contact_type() {
    echo '<h3>'.__('ADD NEW','UkuuPeople').'</h3>';
    $indUrl = admin_url( 'post-new.php?post_type=wp-type-contacts&ctype=wp-type-ind-contact' );
    $orgUrl = admin_url( 'post-new.php?post_type=wp-type-contacts&ctype=wp-type-org-contact');
    ?>

    <input name="wp-contact-type-select" type="radio" value="wp-type-ind-contact" redirect="<?php echo $indUrl?>"><?php _e('Human', 'UkuuPeople'); ?><br/>
    <input name="wp-contact-type-select" type="radio" value="wp-type-org-contact" redirect="<?php echo $orgUrl?>"><?php _e('Organization', 'UkuuPeople'); ?><br/>
    <script type="text/javascript">
      jQuery("input[name='wp-contact-type-select']").on('click',function( event ) {
        window.location = jQuery(this).attr('redirect');
        return false;
      });
    </script>
    <?php
  }

  /*
   * Add custom field columns on wp-type-contacts and wp-type-activity post listing page
   *
   * @param type $column custom column name
   * @param type $post_id
   */
  function ukuu_custom_field_list_text( $column, $post_id ) {
    global $post;
    switch ( $column ) {
      case 'wp-fullname': //Contact Full Name
        $id = get_post_meta( get_the_ID() , '_wpcf_belongs_wp-type-contacts_id', true );
        $display =  get_post_meta( $id , 'wpcf-display-name', true );
        $URL = get_edit_post_link( $id );
        echo "<a href='$URL'>$display</a>";
        break;
      case 'wp-assigned': // Contacts activity is assigned to
        echo $this->activity_assigned( get_the_ID() );
        break;
      case 'wp-status': // Activity Status
        $activity_status = get_post_meta( get_the_ID(), 'wpcf-status', true);
        if ( $activity_status == 'scheduled' ) {
          echo "<div class='ukuuclock' id='scheduled'></div>";
        }
        elseif ( $activity_status == 'completed' ) {
          echo "<div class='ukuucheck' id='completed'></div>";
        }
        else {
          echo "<div id='{$activity_status}'></div>";
        }
        if ( get_post_meta( get_the_ID(), 'wpcf-attachments', true) ) {
          echo "<div class='paper-icon'></div>";
        }
        break;
      case 'wp-contact-type': // Contact type
        echo $this->activity_contact_type( get_the_ID() );
        break;
      case 'wp-startdate': // Activity start sate
        echo $this->activity_startdate( get_the_ID());
        break;
      case 'wp-contact-full-name': // Full name
        $ids = get_the_ID();
        $data = get_post( $ids );
        if ( $data->post_type == 'wp-type-contacts' ) {
          $display =  get_post_meta( $ids , 'wpcf-display-name', true );
          $URL = get_edit_post_link( $ids );
          $viewURL = get_permalink( $ids );
          $deleteURL = get_delete_post_link( $ids );
          $permDeleteURL = get_delete_post_link( $ids, null, true );
          if ( current_user_can( 'edit_all_ukuupeoples' ) )
            echo "<a href=$URL>".$display."</a>";
          else
            echo $display;
          if ( get_post_status ( $ids ) == 'trash' ) {
            $_wpnonce = wp_create_nonce( 'untrash-post_' . $ids );
            $url = admin_url( 'post.php?post=' . $ids . '&action=untrash&_wpnonce=' . $_wpnonce ); ?>
            <div class="row-actions">
            <?php if ( current_user_can('delete_ukuupeople', $ids) ) { ?>
            <span class="untrash"><a href="<?php echo $url; ?>" title="<?php _e( 'Restore this item from the Trash','UkuuPeople' ); ?> "><?php _e( 'Restore','UkuuPeople' ); ?></a>
            <?php } ?>
            <?php if ( current_user_can('delete_ukuupeople', $ids) ) { ?>
            |</span><span class="delete"><a class="submitdelete" href="<?php echo $permDeleteURL; ?>" title="<?php _e( 'Delete this item permanently','UkuuPeople' ); ?>"><?php _e( 'Delete Permanently', 'UkuuPeople' ); ?></a></span>
            <?php } else echo "</span>"; ?>
            </div><?php
          }
          else {
            // Build info for quick edit
            if ( current_user_can('edit_ukuupeople', $ids) ) {
              get_inline_data($post);
            } ?>
            <div class="row-actions">
              <?php if ( current_user_can('edit_ukuupeople', $ids) ) { ?>
            <span class="edit"><a title="Edit this item" href="<?php echo $URL; ?>"><?php _e( 'Edit', 'UkuuPeople' ); ?></a>|</span>
            <span class="inline hide-if-no-js"><a class="editinline" title="Edit this item inline" href="#"><?php _e( 'Quick Edit', 'UkuuPeople' ); ?></a>|
            <?php } ?>
            <?php if ( current_user_can('delete_ukuupeople', $ids) ) { ?>
            </span><span class="trash"><a class="submitdelete" href="<?php echo $deleteURL; ?>" title="<?php _e( 'Move this item to the Trash', 'UkuuPeople' ); ?>"><?php _e( 'Trash', 'UkuuPeople' ); ?></a></span>|
            <?php } else echo "</span>"; ?>
            <?php
            // View Ukuupeople COde
            if ( current_user_can('read_ukuupeople', $ids) ) { ?>
            <span class="view"><a title="View this item" href="<?php echo admin_url('admin.php?page=view-ukuupeople&cid='.$ids); ?>"><?php _e( 'View', 'UkuuPeople' ); ?></a></span>
            <?php }
            echo '</div>'; // row-actions closed
          }
        }
        break;
      case 'wp-phone': // Contact's phone
        $phoneNumber = get_post_meta( get_the_ID(), 'wpcf-phone', true);
        if( $phoneNumber ) {
          if ( !preg_match('/[^0-9]/', $phoneNumber ) ) {
            $phoneNumber = substr($phoneNumber, 0, 3).'-'.substr($phoneNumber, 3, 3).'-'.substr($phoneNumber, 6);
            $phoneNumber = rtrim($phoneNumber , '-');
          }
          echo $phoneNumber;
        }
        break;
      case 'wp-email': // Email
        echo get_post_meta( get_the_ID(), 'wpcf-email', true);
        break;
      case 'wp-color-code': // Contact type

        $contact_image = get_post_meta( get_the_ID(), 'wpcf-contactimage', true );
        if ( ! empty( $contact_image ) ) {
          echo "<span class='contactimage'><img src='".$contact_image."' width='32' height='32'></span>";
        } else {
          $avatar = get_avatar( get_post_meta( get_the_ID() , 'wpcf-email', true ) ,32);
          echo "<span class='contactimage'>".$avatar."</span>";
        }

        $color = array( 'Individual' => '#0072BB','Organization' => '#30A08B' );
        $type = get_the_terms( get_the_ID(), 'wp-type-contacts-subtype');
        if ( $type ) {
          foreach ( $type as $key => $value ) {
            $contacttype = $value->name;
          }
          echo "<svg height='10' width='10'>";
          echo "<circle cx='5' cy='5' r='4' stroke-width='3' fill='$color[$contacttype]' />";
          echo "</svg>";
        }
        break;
      case 'wp-favorites':
        $action = 'add';
        $posts = self::retrieve_favorites();
        if ( $posts && in_array(get_the_ID(), $posts) ) {
          echo "<div class='add_to_fav_star' style='float:left;'><div id='fav-star' class='remove-star'></div></div>";
        }
        break;
      case 'wp-inactive_days' :
        $args = array('post_type' => 'wp-type-activity' , 'posts_per_page'=> -1, 'post_status' => array( 'publish', 'private' ) ,
                'meta_query' => array(
                  'relation' => 'AND',
                  array(
                    'key'     => '_wpcf_belongs_wp-type-activity_id',
                    'value'   => $post_id,
                    'compare' => '=',
                  ),
                ));
        $loop = get_posts( $args );
        $inactive_days_default = 0;
        if( isset( $loop[0]->ID ) ) {
          $post_obj = get_post( $loop[0]->ID );
          $created_date  = strtotime( $post_obj->post_date );
          $currentDateTime = strtotime( date('Y-m-d H:i:s' ) );
          $inactive_days = round(($currentDateTime - $created_date) /60/60/24);
          if ( $inactive_days > 100 ) {
            $inactive_days_default = "> 100";
          }
          else {
            $inactive_days_default = $inactive_days;
          }
        }
        echo $inactive_days_default;
        break;
      case 'wp-type-activity': // Activity chart
        $monthsArray[date('Y')][date('n')] =  date( 'n' );
        for ( $i = 4; $i >= 0; $i-- ) {
          $year =  date( 'Y', strtotime( "-$i month", strtotime( date( 'Y-m-1 H:i:s' ) ) ) );
          $month =  date( 'n', strtotime( "-$i month", strtotime( date( 'Y-m-1 H:i:s' ) ) ) );
          $monthsArray[$year][$month] = $month;
          ksort($monthsArray[$year]);
        }
        ksort( $monthsArray, true );
        $months = array();
        foreach ( $monthsArray as $mk => $mv ) {
          $months = array_merge($months, $mv);
        }
        $timeline = array();
        $args = array(
          'post_type' => 'wp-type-activity',
          'posts_per_page' => -1,
          'meta_query' => array(
            array(
              'key'     => '_wpcf_belongs_wp-type-contacts_id',
              'value'   => get_the_ID(),
            ),
          )
        );
        $loop = new WP_Query( $args );
        foreach ( $loop->posts as $child_post ) {
          $date = get_post_meta( $child_post->ID, 'wpcf-startdate', true);
          if ( !empty($date) ) {
            $activityMonth =  date( 'n', date($date) );
            if ( in_array($activityMonth,$months) ) {
              $monthKey = array_search($activityMonth, $months);
              $monthKey = $months[$monthKey];
              $monthsDate = date( "Y-m-d", mktime( 0, 0, 0, $monthKey  ,01 , date( "Y" ) ) );
              $activity_date_time =  date( "Y-m-d", $date );
              $monthsLastDate = date( "Y-m-d", mktime( 0, 0, 0, $monthKey, 31, date( "Y" ) ) );
              if ( ( strtotime ( $monthsDate ) <= strtotime( $activity_date_time ) ) && ( strtotime( $activity_date_time )  <= strtotime ( $monthsLastDate ) ) ) {
                if ( array_key_exists($monthKey, $timeline) ) {
                  $timeline[$monthKey] = $timeline[$monthKey] + 1;
                } else {
                  $timeline[$monthKey] = 1;
                }
              }
            }
          }
        }
        $co_ordinates = array();
        foreach ( $months as $key => $value ) {
          if ( ! isset( $timeline[$value] ) ) {
            $co_ordinates[] = array($key, 0);
          } else{
            $co_ordinates[] = array($key, $timeline[$value]);
          }
        }
        $combine = array( 'val' => $co_ordinates , 'colorlist' => '#344F8C' ,'contactid' => get_the_ID() );
        echo "<div class='tdata' ><svg id='visualisation_".get_the_ID()."' activityc='".json_encode( $combine )."' width='175' height='50'></svg></div>";
        break;
    }
  }

  /*
   * Returns custom field columns for wp-type-contacts post
   *
   * @param type $defaults
   * @return type
   */
  function ukku_contacts_custom_fields_list_view($defaults) {
    unset( $defaults['date'] );
    unset( $defaults['categories'] );
    $defaults['cb'] = __('input id="cb-select-all-1" type="checkbox"');
    $defaults['wp-color-code'] = __('');
    $defaults['wp-contact-full-name'] = __('Name','UkuuPeople');
    $defaults['wp-email'] = __('Email','UkuuPeople');
    $defaults['wp-phone'] = __('Phone','UkuuPeople');
    $defaults['wp-type-activity'] = __('TouchPoint','UkuuPeople');
    $defaults['wp-favorites'] = __('');
    $defaults['wp-inactive_days'] = __('Inactive Days', 'UkuuPeople' );
    $Order = array( 'cb' ,'wp-color-code' , 'wp-contact-full-name' , 'wp-email' , 'wp-phone' , 'wp-type-activity','wp-favorites','wp-inactive_days');
    foreach ( $Order as $colname ) {
      $new[$colname] = $defaults[$colname];
    }
    $defaults = $new;
    return $defaults;
  }

  function ukku_contacts_custom_fields_hide_date_filter() {
    $screen = get_current_screen();
    if ( 'wp-type-contacts' == $screen->post_type || 'wp-type-activity' == $screen->post_type ){
      add_filter('months_dropdown_results', '__return_empty_array');
    }
  }

  /*
   * Returns custom field columns for wp-type-activity post
   *
   * @param type $defaults
   * @return type
   */
  function ukku_activity_custom_fields_list_view( $defaults ) {
    unset( $defaults['title'] );
    unset( $defaults['date'] );
    $defaults['cb'] = __('input id="cb-select-all-1" type="checkbox"');
    $defaults['wp-startdate'] = __('Date and Time', 'UkuuPeople' );
    $defaults['wp-contact-type'] = __('Contact Type', 'UkuuPeople' );
    $defaults['wp-fullname'] = __('TouchPoint Contact', 'UkuuPeople' );
    $defaults['title'] = __('Subject', 'UkuuPeople' );
    $defaults['wp-assigned'] = __('Assigned to', 'UkuuPeople' );
    $defaults['wp-status'] = __('Status', 'UkuuPeople' );
    $Order = array('cb','wp-startdate', 'title', 'wp-contact-type', 'wp-fullname', 'wp-assigned','wp-status');
    foreach ( $Order as $colname ) {
      $new[$colname] = $defaults[$colname];
    }
    $defaults = $new;
    return $defaults;
  }

  /*
   * returns the contacts assigned to $id
   *
   * @param type $id activity id
   */
  function activity_assigned ( $id ) {
    $repeatable_field_values = get_post_meta( $id , 'wpcf_assigned_to', true);
    $data = array();
    if ( $repeatable_field_values ) {
      foreach ( $repeatable_field_values as $key => $val ) {
        $data[] = get_post_meta( $val , 'wpcf-display-name', true );
      }
    }
    $data = implode ( ',', $data );
    return $data;
  }

  /*
   * returns the activity start date
   *
   * @param type $id activity id
   */
  function activity_startdate ( $id ) {
    $data ='';
    $time = $month = $datenum = '';
    $atype = '';
    $termType = $acttype = get_the_terms( $id , 'wp-type-activity-types');
    $termSlug = '';
    if ( !empty( $acttype ) ) {
      $term = array_shift( $termType );
      $termSlug = $term->slug;
    }
    $selectedColor = get_option( 'term_category_radio_' . $termSlug );
    $color = array( 'Meeting' => '#377CB6' , 'Phone' => '#771D78'  , 'Note' => '#3DA999' , 'Contact Form' => '#E6397A' );
    if ( !empty ( $acttype ) ) {
      $atype = array_map( function($a) { return $a->name; }, $acttype );
      $atype = reset( $atype );
    }
    $date = get_post_meta( $id, 'wpcf-startdate', true );
    if ( !empty ( $date ) ) {
      $time = date('h:i a', $date);
      $month = date('F', $date);
      $datenum = date('d', $date);
    }
    if (array_key_exists($atype, $color) ) {
      $data .= "<div style= 'background-color:$color[$atype]' class='contribution_amount activityColor'>$month <br><span class='date_number'>$datenum</span><br>$time</div>";
    }
    else if( $selectedColor && !empty($selectedColor) ) {
      $data .= "<div style= 'background-color:$selectedColor' class='contribution_amount activityColor'>$month <br><span class='date_number'>$datenum</span><br>$time</div>";
    } else {
      $data .= "<div style= 'background-color:#d3d3d3' class='contribution_amount activityColor'>$month <br><span class='date_number'>$datenum</span><br>$time</div>";
    }
    return $data;
  }

  /*
   * returns the ukuupeople type
   *
   * @param type $id activity id
   */
  function activity_contact_type ( $id ) {
    $type = array();
    $contacttype = '';
    $data = '';
    $id = get_post_meta( $id , '_wpcf_belongs_wp-type-contacts_id', true);
    $color = array( 'Individual' => '#0072BB','Organization' => '#30A08B' );
    $type = get_the_terms( $id , 'wp-type-contacts-subtype');
    if ( !empty($type) ) {
      foreach ( $type as $k => $v ) {
        $contacttype = $v->name;
      }
      $data .= "<svg height='10' width='10'>";
      $data .= "<circle cx='5' cy='5' r='4' stroke-width='3' fill='$color[$contacttype]' />";
      $data .= "</svg>";
    }
    $data .= $contacttype;
    return $data;
  }

  /*
   * Update related user on wp-type-contact updation
   *
   * @param type $user_id
   * @param type $old_user_data
   */
  function ukuu_custom_profile_update( $user_id, $old_user_data ) {
    $userdata = get_user_meta( $user_id );
    $user = get_userdata( $user_id );
    $post = get_page_by_title( $user->user_login, OBJECT, 'wp-type-contacts' );
    if ( !empty( $post ) ) {
      $postID = $post->ID;
      $meta_values = get_post_meta( $postID );
      foreach ( $meta_values as $keys => $id ){
        $k = str_replace('wpcf-', '', $keys);
        $key = str_replace('-', '_', $k);
        if ( isset($userdata[$key]) ) {
          update_post_meta( $postID, $keys, $userdata[$key][0] );
        }
        update_post_meta( $postID, 'wpcf-email', $user->user_email );
      }
    }
  }

  /*
   * Set contact/activity type on respective new post type saving
   *
   * @param type $post_ID
   */
  function ukuu_set_wp_custom_type( $post_ID , $post ) {
    global $typenow;
    global $wp_meta_boxes;
    $url = admin_url();
    $types = array(
      'wp-type-ind-contact' => 'wp-type-ind-contact',
      'wp-type-org-contact' => 'wp-type-org-contact',
      'wp-type-activity-phone' => 'wp-type-activity-phone',
      'wp-type-activity-meeting' => 'wp-type-activity-meeting',
    );
    if ( !isset( $_GET['ctype'] ) && $typenow == "wp-type-contacts" && isset( $post ) && $post->filter != 'raw' && !isset( $_GET['action'] )) {
      wp_redirect($url.'admin.php?page=add-new-contact');
      exit();
    }

    if ( (isset($_GET['ctype']) || isset($_GET['atype']) ) && ($typenow == "wp-type-contacts" || $typenow == 'wp-type-activity') ) {
      $subtype = isset( $_GET['ctype'] ) ? $_GET['ctype'] : $_GET['atype'];
      if ( array_key_exists ( $subtype, $types ) ) {
        $mainType = ( $typenow == 'wp-type-contacts' ) ? 'wp-type-contacts-subtype' : 'wp-type-activity-types';
        wp_set_object_terms( $post_ID, $types[$subtype], $mainType, true );
      }
    }

    if ( !isset ( $_GET['ctype'] ) && $typenow == "wp-type-contacts" && !isset( $_REQUEST['action'] )) {
      wp_set_object_terms( $post_ID, 'wp-type-ind-contact', 'wp-type-contacts-subtype', true );
    }
    if ( $typenow == "wp-type-contacts" ) {
      $type = get_the_terms( $post_ID, 'wp-type-contacts-subtype');
      $meta_data = get_post_meta($post_ID);
      if ( !empty( $type ) && $type[0]->slug == 'wp-type-ind-contact' ) {
        $first = isset( $meta_data['wpcf-first-name'][0] ) ? $meta_data['wpcf-first-name'][0] : '';
        $last = isset( $meta_data['wpcf-last-name'][0] ) ? $meta_data['wpcf-last-name'][0] : '';
        $display = $first." ".$last;
        if ( isset( $meta_data['wpcf-first-name'][0] ) && isset( $meta_data['wpcf-first-name'][0] ) ) {
          update_post_meta( $post_ID, 'wpcf-display-name', $display );
        }
      }
      $postVal = get_post($post_ID, ARRAY_A);
      $validEmail = get_post_meta( $post_ID, 'wpcf-email');
      $meta_data = get_post_meta($post_ID);
      if ( isset( $postVal['post_status'] ) && $postVal['post_status'] != 'auto-draft' && isset( $validEmail ) && !empty($meta_data) ) {
        $title = $postVal['post_title'];
        $userdata = array(
          'first_name' => isset( $meta_data['wpcf-first-name'][0] ) ? $meta_data['wpcf-first-name'][0] : '',
          'last_name' => isset( $meta_data['wpcf-last-name'][0] ) ? $meta_data['wpcf-last-name'][0] : '',
          'display_name' => isset( $display) ? $display : $meta_data['wpcf-display-name'][0],
          'user_email' => $meta_data['wpcf-email'][0],
        );
        $userdata['user_login'] = $meta_data['wpcf-email'][0];
        $user = get_user_by( 'email', $userdata['user_login'] );

        if ( !$user ) {
          $user = get_user_by( 'login', $title );
        }
        if ( !$user ) {
          $title = $postVal['post_title'] = $meta_data['wpcf-email'][0];
          $userdata['user_pass'] = Null;
          $user_id = wp_insert_user( $userdata ) ;
          wp_new_user_notification( $user_id, NULL );
        }
        else {
          $user = $user->data;
          $userdata['ID'] = $user->ID;
          $userdata['user_login'] = $user->user_login;
          $title = $postVal['post_title'] = $user->user_login;
          $user_id = wp_update_user( $userdata );
        }
        if ( ! wp_is_post_revision( $post_ID ) ) {
          remove_action( 'save_post', array( $this, 'ukuu_set_wp_custom_type' ), 99, 2 );
          $args = array();
          $args['ID'] = $post_ID;
          $args['post_title' ] = $title;
          wp_update_post( $args );
          if ( isset($display) ) {
            update_post_meta( $post_ID, 'wpcf-display-name', $display );
          }
          add_action( 'save_post', array( $this, 'ukuu_set_wp_custom_type' ), 99, 2 );
        }
      }
    }
    if ( $typenow == "wp-type-activity" ) {
      $type = get_the_terms( $post_ID, 'wp-type-activity-types' );
      $meta_data = get_post_meta( $post_ID );
      if ( !empty( $type ) && $type[0]->slug == 'wp-type-activity-note' ) {
        update_post_meta( $post_ID, 'wpcf-startdate', strtotime(get_the_date('Y-m-d H:i:s') ) );
        update_post_meta( $post_ID, 'wpcf-enddate', strtotime(get_the_date('Y-m-d H:i:s') ) );
        update_post_meta( $post_ID, 'wpcf-status', '' );
      }
      if ( isset( $_POST['wpcf-pr-belongs'] ) && empty( $_POST['hidden_cid'] ) )
      update_post_meta( $post_ID , "_wpcf_belongs_wp-type-contacts_id", $_POST['wpcf-pr-belongs'] );
    }

    // Quick-edit save options
    if ( isset( $_POST['action'] ) && $_POST['action'] == 'inline-save' ) {
      //if not autosaving
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return false;
      }

      // save fields
      if ( isset( $_POST['touchpoint_status'][1] ) ) {
        update_post_meta( $_POST['post_ID'], 'wpcf-status', $_POST['touchpoint_status'][1] );
      }

      if (isset( $_POST['touchpoint_assignee'] ) ) {
        $touchpoint_assignee = array_filter( $_POST['touchpoint_assignee'] );
        update_post_meta( $_POST['post_ID'], 'wpcf_assigned_to', $touchpoint_assignee );
      }
    }
    elseif ( isset( $_GET['bulk_edit'] ) && $_GET['bulk_edit'] == 'Update' && $_GET['post_type'] == 'wp-type-activity' ) {
      if ( empty ( $_GET['post'] ) )
        return false;

      $update_options = array();
      if ( isset( $_GET['touchpoint_status'][1] ) ) {
        $update_options['wpcf-status'] = $_GET['touchpoint_status'][1];
      }

      $touchpoint_assignee = array_filter( $_GET['touchpoint_assignee'] );

      if ( ! empty( $touchpoint_assignee ) ) {
        $update_options['wpcf_assigned_to'] = $touchpoint_assignee;
      }

      foreach ( $_GET['post'] as $touchpoint ) {
        foreach ( $update_options as $field => $value ) {
          update_post_meta( $touchpoint, $field, $value );
        }
      }
    }
  }

  /*
   * Remove submenu pages for contact/Activity add and hide/remove title, types meta box
   */
  function ukuu_custom_remove_links_meta() {
    global $typenow;
    global $submenu;
    global $wp_post_types;

    if ( isset($_GET['ctype']) ) {
      //change title of human and Organization
      $postLabels = $wp_post_types['wp-type-contacts']->labels;
      if ( $_GET['ctype'] == 'wp-type-ind-contact' )
        $postLabels->add_new_item = __('Add New Human', 'UkuuPeople');
      if ( $_GET['ctype'] == 'wp-type-org-contact' )
        $postLabels->add_new_item = __('Add New Organization', 'UkuuPeople');
    }

    //Hide Contact post title
    remove_post_type_support( 'wp-type-contacts', 'title' );
    //Hide Contact/Actiivty Types meta box
    remove_meta_box( 'wp-type-contacts-subtypediv', 'wp-type-contacts' , 'side' );
    remove_meta_box( 'wp-type-activity-typesdiv', 'wp-type-activity' , 'side' );
    remove_menu_page( 'edit.php?post_type=wp-type-activity' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts', 'post-new.php?post_type=wp-type-contacts' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts', 'edit.php?post_type=wp-type-contacts&page=add-new-contact' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts','edit-tags.php?taxonomy=category&amp;post_type=wp-type-contacts' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts','edit-tags.php?taxonomy=wp-type-group&amp;post_type=wp-type-contacts' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts','edit-tags.php?taxonomy=wp-type-tags&amp;post_type=wp-type-contacts' );
    remove_submenu_page( 'edit.php?post_type=wp-type-contacts','edit-tags.php?taxonomy=wp-type-contacts-subtype&amp;post_type=wp-type-contacts' );
  }

  /*
   * Collapse the fields on page load
   *
   * @param type $closed
   * @return type
   */
  function closed_meta_boxes( $closed ) {
    global $post;
    if ( isset($post->ID) && $post->filter == 'edit' ) {
      $closed = array( 'wpcf-group-edit-contact-info', 'wpcf-group-edit_contact_address', 'wpcf-group-edit_contact_privacy_settings' , 'wpcf-related-org-metabox' );
    } elseif ( isset( $post->ID ) && $post->filter == 'raw' ) {
      $closed = array();
    }
    return $closed;
  }

  /*
   * Add Contact summary view on Edit link
   *
   * @param type $edit post id
   */
  function ukuu_custom_summary_view($edit) {
    if ( isset( $edit->ID ) && $edit->post_type == 'wp-type-contacts' ) {
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
      $type = get_the_terms( $edit->ID , 'wp-type-contacts-subtype');
      // Custom fields update for ukuupeople organization //
      if ( isset ( $type[0]->slug ) && $type[0]->slug =='wp-type-org-contact' ) { ?><script>
        jQuery( document ).ready(function() {
          jQuery('.post-type-wp-type-contacts #wpcf-related-org-metabox').hide();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-first-name').hide();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-last-name').hide();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-ukuu-job-title').remove();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-ukuu-date-of-birth').remove();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-first-name').find("input").prop("disabled",true);
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-last-name').find("input").prop("disabled",true);
          // Validation for Display name field //
          jQuery('.post-type-wp-type-contacts .cmb2-id-wpcf-display-name label').append('*');
          jQuery('.post-type-wp-type-contacts .cmb2-id-wpcf-display-name input').prop('required',true);
        }); </script><?php
      }
      // Custom fields update for ukuupeople Individual //
      if ( isset ( $type[0]->slug ) && $type[0]->slug =='wp-type-ind-contact' ) { ?><script>
        jQuery( document ).ready(function() {
          jQuery('.post-type-wp-type-contacts #wpcf-related-org-metabox').show();
          jQuery('.post-type-wp-type-contacts #cmb2-metabox-wpcf-group-edit-contact-info .cmb2-id-wpcf-display-name').hide();
        }); </script><?php
      }

      if ( $edit->filter == 'edit' ) {
        $custom = get_post_custom( $edit->ID );
        $contactdetails_keys = array( 'wpcf-phone' => '' , 'wpcf-mobile'  =>'' );
        if( isset( $custom['wpcf-phone'][0] ) && !preg_match( '/[^0-9]/', $custom['wpcf-phone'][0] ) ) {
          $phoneNumber = $custom['wpcf-phone'][0];
          $phoneNumber = substr($phoneNumber, 0, 3).'-'.substr($phoneNumber, 3, 3).'-'.substr($phoneNumber, 6);
          $phoneNumber = rtrim($phoneNumber , '-');
          $custom['wpcf-phone'][0] =  $phoneNumber;
        }

        $contactdetailsblock = array_intersect_key ( $custom, $contactdetails_keys ) ;
        $ctags = wp_get_post_terms($edit->ID, 'wp-type-tags', array("fields" => "names"));
        echo '<div class="sidebar-contact"><div id="first-sidebar-contact"> <div class="profiledetailsblock"><div id="user-images">';
        if ( ! empty( $custom['wpcf-contactimage'][0] ) ) {
          echo "<img src='".$custom['wpcf-contactimage'][0]."' width='150' height='150'>";
        } else {
          $avatar = get_avatar( $custom['wpcf-email'][0] ,150);
          echo $avatar;
        }
        echo '</div>';

        $action = 'add';
        $posts = self::retrieve_favorites();
        echo '<input type="hidden" name="star-ajax-nonce" id="star-ajax-nonce" value="' . wp_create_nonce( 'star-ajax-nonce' ) . '" />';
        if ( $posts && in_array( $edit->ID, $posts ) ) {
          $action = "del";
          echo "<div class='remove_fav_star' style='display:inline-block;vertical-align:top;'><div id='fav-star' class='remove-star' onclick=\"addToFav($edit->ID, '{$action}');\"></div></div>";
        } else {
          echo "<div class='add_to_fav_star' style='display:inline-block;vertical-align:top;'><div id='fav-star' class='add-star' onclick=\"addToFav($edit->ID, '{$action}');\" /></div></div>";
        }

        echo '<div class="display-name"><div id="display-name">';
        if( isset($custom['wpcf-display-name'][0] ) ) {
          if ( isset( $type[0]->slug ) && $type[0]->slug == 'wp-type-org-contact' )
            echo '<font color="#30A08B">'.$custom['wpcf-display-name'][0].'</font>';
          else
            echo '<font color="#0072BB">'.$custom['wpcf-display-name'][0].'</font>';
        }
        echo '</div>';
        $related_org = get_post_meta( $edit->ID, 'wpcf-related-org', true );
        if( $related_org ) {
          $contact_org_url = get_edit_post_link ( $related_org );
          echo "<span class='contact-org-url'><a href='".$contact_org_url."'>".get_post_meta( $related_org, 'wpcf-display-name', true )."</a></span>";
        }
        echo '<div class="add-touchpoint">';
        $contact_id = get_the_ID();
        echo "<div class='edit_contact'><a href='#wpcf-group-edit-contact-info' class='button button-primary'>Edit Contact</a></div>";
        echo "<div><a href='".admin_url()."post-new.php?post_type=wp-type-activity&cid=$contact_id' class='button button-primary'>Add TouchPoint</a></div>";
        echo '</div></div></div>';
        echo '<div id="contactdetailsblock"><table id="contactdetail-table">';

        if ( isset( $custom['wpcf-email'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Email', 'UkuuPeople' ) ."</span></td><td class='title-value'><a href='mailto:".$custom['wpcf-email'][0]. "'>".$custom['wpcf-email'][0]."</a></td></tr>";
        }

        foreach ( $contactdetailsblock as $key => $value ) {
          echo "<tr><td><span class='contactdetailhead'>".ucfirst(substr($key, 5))."</span></td><td class='title-value'>".$value[0]."</td></tr>";
        }

        if ( isset( $custom['wpcf-website'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Website', 'UkuuPeople' ) ."</span></td><td class='title-value'><a href='". $custom['wpcf-website'][0] ."' target='_blank'>".$custom['wpcf-website'][0]."</a></td></tr>";
        }
        if ( isset( $custom['wpcf-ukuu-job-title'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Job Title', 'UkuuPeople' ) ."</span></td><td class='title-value'>". $custom['wpcf-ukuu-job-title'][0]. "</td></tr>";
        }

        if ( isset( $custom['wpcf-ukuu-twitter-handle'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Twitter Handle', 'UkuuPeople' ) ."</span></td><td class='title-value'><a href='http://twitter.com/". ltrim( $custom['wpcf-ukuu-twitter-handle'][0], '@') ."' target='_blank'>".$custom['wpcf-ukuu-twitter-handle'][0]."</a></td></tr>";
        }
        if ( isset( $custom['wpcf-ukuu-facebook-url'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Facebook URL', 'UkuuPeople' ) ."</span></td><td class='title-value'><a href='http://facebook.com/". $custom['wpcf-ukuu-facebook-url'][0] ."' target='_blank'>".$custom['wpcf-ukuu-facebook-url'][0]."</a></td></tr>";
        }
        if ( isset( $custom['wpcf-ukuu-date-of-birth'] ) && !empty( $custom['wpcf-ukuu-date-of-birth'][0] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Date Of Birth', 'UkuuPeople' ) ."</span></td><td class='title-value'>". $custom['wpcf-ukuu-date-of-birth'][0] ."</td></tr>";
        }

        echo '</table></div></div><div id="second-sidebar-contact"><div id="addressblock">';
        $html = "<table id='contactdetail-table'><tr><td><span class='contactdetailhead'>".__( 'Address', 'UkuuPeople' ) ."</span></td></tr>";
        $streetaddress = $city = $country = $postalcode = $state ='';
        if ( isset ( $custom['wpcf-streetaddress'] ) ) {
          $html .= "<tr><td>".$custom['wpcf-streetaddress'][0]."</td></tr>";
        }
        if ( isset ( $custom['wpcf-streetaddress2'] ) ) {
          $html .= "<tr><td>".$custom['wpcf-streetaddress2'][0]."</td></tr>";
        }

        $html .= "<tr><td></td></tr>";
        $city = isset($custom['wpcf-city']) ? $custom['wpcf-city'][0] : '';
        $state = isset($custom['wpcf-state']) ? $custom['wpcf-state'][0] : '';
        $postalcode = isset($custom['wpcf-postalcode']) ? $custom['wpcf-postalcode'][0] : '';

        $html .= "<tr><td>{$city}".(!empty($city) ? ', ' : '')."{$state} {$postalcode}</td></tr></table>";
        echo $html.'</div><div id="tagblock">';
        $html = "<table id='contactdetail-table'><tr><td><span class='contactdetailhead'>".__( 'Tags', 'UkuuPeople' ) ."</span></td></tr><tr>";
        $tags = '';
        if ( ! empty( $ctags ) ) {
          $tags = implode( ', ' , $ctags );
        }
        $html .= "<td><p class='title-value'>$tags</p></td></tr></table>";
        echo $html.'</div></div>';
        do_action( 'tab_info' , $edit ,$type[0]->slug );
        echo '</div>';
        $this->ukuu_activity_list_meta_box();
      }
    }
  }

  function ukuu_custom_summary_view_activity( $edit ) {
    if ( isset($edit->ID) && ( $edit->post_type == 'wp-type-activity' ) && ( isset($_GET['action']) && ( $_GET['action'] == 'edit' ) ) ) {
      $custom = get_post_custom( $edit->ID );
      $start_date = $time = $subject = $details = $activity_name = $contact_id = $attachments = $extension = $status_color = '';
      $display_name = $related_org = $org_name = $contact_dash_url = $contact_image =  $termSlug = $status = '';
      if( !empty( $custom['wpcf-attachments'][0] ) ) {
        $attachments = $custom['wpcf-attachments'][0];
        $explode = explode(".",$custom['wpcf-attachments'][0] );
        $extension = end($explode);
      }
      $start_date = date("F d, Y", $custom['wpcf-startdate'][0]);
      $time = date("h:i a", $custom['wpcf-startdate'][0]);
      $subject = get_the_title($edit->ID);
      if( isset( $custom['wpcf-details'][0] ) && !empty( $custom['wpcf-details'][0] ) ) {
        $details = $custom['wpcf-details'][0];
      }
      $termType = $acttype = get_the_terms( $edit->ID , 'wp-type-activity-types');
      if ( !empty( $acttype ) ) {
        $term = array_shift( $termType);
        $termSlug = $term->slug;
        $activity_name = $term->name;
      }
      $selectedColor = get_option( 'term_category_radio_' . $termSlug );
      if( $activity_name == 'Donation' ) {
        $selectedColor = '#d3d3d3';
      }
      $color = array( 'Meeting' => '#377CB6' , 'Phone' => '#771D78'  , 'Note' => '#3DA999' , 'Contact Form' => '#E6397A' );
      foreach( $color as $key => $value ) {
        if ( $key == $activity_name ) {
          $selectedColor = $value;
        }
      }
      $assigned_to = get_post_meta( $edit->ID, 'wpcf_assigned_to', true );
      $contact_id = $custom['_wpcf_belongs_wp-type-contacts_id'][0];
      $display_name = get_post_meta( $contact_id,'wpcf-display-name', true );
      $related_org = get_post_meta( $contact_id, 'wpcf-related-org', true );
      $org_name = get_post_meta( $related_org,'wpcf-display-name', true );
      $contact_dash_url = get_edit_post_link( $contact_id );
      $contact_image = get_post_meta( $contact_id,'wpcf-contactimage', true );
      if ( empty( $contact_image ) ) {
        $email = get_post_meta( $contact_id,'wpcf-email', true);
        $contact_image = get_avatar( $email ,150 );
      } else {
        $contact_image = "<img src='".$contact_image."' width='150' height='150'>";
      }
      if ( isset( $custom['wpcf-status'][0] ) ) {
        $status = $custom['wpcf-status'][0];
      }
      $statusColor = array( 'completed' => '#39b54a', 'scheduled' => '#2272BB', 'cancel' => '#FF0000' );
      foreach ( $statusColor as $statusC => $colorC ) {
        if ( $status == $statusC ) {
          $status_color = $colorC;
        }
      }
      $statusImage = array( 'completed' => '../images/tick.png', 'scheduled' => '../images/event.png', 'cancel' => '../images/cross.png' );
      foreach( $statusImage as $statusI => $imageI ) {
        if ( $status == $statusI ) {
          $status_image = $imageI;
        }
      }
?>
<div class='summary-activity-main'>
	<div class='left-summary-activity'>
    <div class='left-activity-main'>
    	<div class='left-photo-activity'><?php echo $contact_image ?></div>
      <div class='left-photo-summary-activity'>
      	<span class='summary-display-name'><?php echo $display_name ?></span>
      	<span class='summary-org-name'><?php echo $org_name ?></span>
 <?php  $activity_id = get_post($edit->ID);
        $meta_value_id = get_post_meta($activity_id->ID, '_wpcf_belongs_wp-type-activity_id', true);$post_type_name = get_post($meta_value_id);
        if ( $contact_id != 0  && $post_type_name->post_type == 'wp-type-contacts' ) { ?>
        <span class='contact-dashboard'><a href='<?php echo $contact_dash_url ?>' class='button button-primary'>Contact Dashboard</a></span>
   <?php } ?>
     	</div>
      <div class='subject-summary'><span class='label-subject'>Subject </span><span class='subject-content' style="<?php echo 'color:'.$selectedColor ?>"><?php echo $subject ?></span></div>
      <div class='type-summary'>
      	<span class='label-subject'>Type </span>
   <?php  if ( isset($activity_name ) && !empty($activity_name) ) { ?>
      	<svg height='18' width='20'><circle cx='10' cy='10' r='7' fill='<?php echo $selectedColor ?>' /></svg>
  <?php    } ?>
      	<span class='type-content'><?php echo $activity_name ?></span>
      </div>
      <div class='summary-datetime-section group'>
<?php if( $activity_name != 'Note' ) {?>
      	<div class='summary-datetime-col summary-status date-time-a'>
          <span class="completed-summary" style="<?php echo 'background-color:'.$status_color ?>" ><img style="margin-right:5px" src="<?php echo plugins_url( $status_image, __FILE__ );?>" /><?php echo ucfirst($status); ?></span></div>
<?php }?>
      	<div class='summary-datetime-col summary-status date-time-b'><span class='ukuucalendar'></span><span class='start-date-summary'><?php echo $start_date ?></span></div>
      	<div class='summary-datetime-col summary-status date-time-c'><span class='ukuuclock'></span><span class='start-date-summary'><?php echo $time ?></span></div>
      </div>
      <div class='attachment-summary label-subject' >Attachments</div>
      <div class='custom-assigned-for-attachments-section' >
   <?php
      if ( !empty( $custom['wpcf-attachments'][0] ) ) {
     $attachments = $custom['wpcf-attachments'][0];
     $attachment = unserialize($attachments);
     foreach ( $attachment as $key => $attach ) {
       $explode = explode(".",$attach );
       $extension = end($explode);
       echo "<div class='assigned-column assigned-contact-1 assigned-a custom-assigned-for-attachments'  >";
       if ( $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png' || $extension == 'bmp' || $extension == 'tiff' ) {
         echo "<a href='".$attach."' download ><div class='attachment-first' id='".$key."'><img src='".plugins_url( '../images/save.gif', __FILE__ )."' class='over-image'  width='60' height='60' /><div class='inner-attachment-first'></div><img class='under-image' src='".$attach."' width='118' height='116' /></div></a>";
       } else if ( ( $extension == '' ) && !isset( $custom['wpcf-attachments'][0] ) ) {
       } else {
         echo "<a href='".$attach."' ><div class='attachment-first attachment-doc' id='".$key."'><img src='".plugins_url( '../images/save.gif', __FILE__ )."' class='over-image'  width='60' height='60' /><div class='inner-attachment-first'></div><span class='attachment-text under-image'>".strtoupper($extension)."</span></div></a>";
       }
       echo "</div>";
     }
   }
?>
    </div>
		</div>
  </div>
  <div class='right-summary-activity'>
  	<div class='summary-details'><div class='summary-details-head label-subject'>Details</div><div class='summary-details-body'><?php echo $details ?></div>
  	</div>
  	<div class='summary-assigned-to'><div class='summary-details-head label-subject'><?php _e( 'Assigned to', 'UkuuPeople' ) ?></div>
    <div class='assigned-section' >
   <?php
      if ( isset( $assigned_to ) && !empty( $assigned_to ) ) {
        foreach ( $assigned_to as $assigned ) {
          $contact_url = get_edit_post_link($assigned );
          $assigned_display_name = get_post_meta( $assigned,'wpcf-display-name', true );
          $assigned_contact_image = get_post_meta( $assigned,'wpcf-contactimage', true );
          if ( empty( $assigned_contact_image ) ) {
            $email = get_post_meta( $assigned, 'wpcf-email', true );
            $assigned_contact_image = get_avatar( $email ,120 );
          } else {
            $assigned_contact_image = "<img src='".$assigned_contact_image."' width='120' height='120'>";
          }
?>
    <div class='assigned-column assigned-contact-1 assigned-a'  >
    	<a href="<?php echo $contact_url ?>"><div class='assigned-image'><?php echo $assigned_contact_image ?></div></a><span class='assigned-name'><?php echo $assigned_display_name ?></span>
    </div>
<?php
       }
      }
?>
    </div>
   </div>
   <div class="edit-touchpoint"><a href="#wpcf-group-activity-information">Edit TouchPoint</a></div>
  </div>
</div>
<?php
    } else if ( isset( $_GET['cid'] ) && !empty( $_GET['cid'] ) ) {
      $contact_id = $_GET['cid'];
      $contact_image = get_post_meta($contact_id,'wpcf-contactimage', true);
      if ( empty( $contact_image ) ) {
        $email = get_post_meta( $contact_id,'wpcf-email', true);
        $contact_image = get_avatar( $email ,150 );
      } else {
        $contact_image = "<img src='".$contact_image."' width='150' height='150'>";
      }
      $display_name = get_post_meta($contact_id,'wpcf-display-name', true);
      $related_org = get_post_meta($contact_id, 'wpcf-related-org', true);
      $org_name = get_post_meta($related_org,'wpcf-display-name', true);
      $contact_dash_url = get_edit_post_link($contact_id );
      ?>
<div class='summary-activity-main'>
	<div class='summary-activity-view' style="min-height: 165px !important;">
    <div class='left-activity-main'>
    	<div class='left-photo-activity left-photo-adjustments'><?php echo $contact_image ?></div>
      <div class='left-photo-summary-activity'>
      	<span class='summary-display-name'><?php echo $display_name ?></span>
      	<span class='summary-org-name'><?php echo $org_name ?></span>
      	<span class='contact-dashboard'><a href='<?php echo $contact_dash_url ?>' class='button button-primary'><?php _e( 'Contact Dashboard', 'UkuuPeople' ) ?></a></span>
     	</div>
		</div>
  </div>
</div>
<?php
    }
  }

  /*
   * Edit 'Title' label on quick edit for activity
   *
   * @param type $input
   * @return type
   */
  function custom_enter_title( $input ) {
    global $post_type, $typenow;
    if ( is_admin() && ( ( 'Title' == $input && 'wp-type-activity' == $post_type ) ||
        ('Post Title' == $input && 'wp-type-contacts' == $typenow)) ) {
      return 'Subject';
    }
    return $input;
  }

  /*
   * Add wordpress user on wp-type-contacts post save
   *
   * @param type $data
   * @return type
   */
  function ukuu_custom_add_user( $data , $postarr ) {
    if ( $postarr['post_type'] == 'wp-type-activity'   ) {
      if ( array_key_exists('touchpoint-list', $_POST ) ) {
        $post_term = wp_get_post_terms($postarr['ID'], 'wp-type-activity-types', array("fields" => "all"));
        if ( !empty( $post_term ) ) {
          wp_delete_object_term_relationships( $postarr['ID'],  'wp-type-activity-types'  );
        }
        wp_set_object_terms( $postarr['ID'], $_POST['touchpoint-list'] , 'wp-type-activity-types' , true );
        if ( isset($_POST['hidden_cid'] ) && !empty( $_POST['hidden_cid'] ) ) {
          update_post_meta( $postarr['ID'], "_wpcf_belongs_wp-type-activity_id", $_POST['hidden_cid'] );
          update_post_meta( $postarr['ID'], "_wpcf_belongs_wp-type-contacts_id", $_POST['hidden_cid'] );
        }
      }
    }
    return $data;
  }

  /*
   * Delete respective contacts post on user deletion
   *
   * @param type $user_id
   */
  function ukuu_delete_user( $user_id ) {
    $user = get_userdata( $user_id );
    $post = get_page_by_title($user->user_login, OBJECT, 'wp-type-contacts');
    $postID = $post->ID;
    wp_delete_post($postID);
  }

  function view_ukuu_people( ) {
    if ( ! isset( $_GET['cid'] ) ||  $_GET['cid'] == NULL )
      return;

    $id = $_GET['cid'];
    $people_object = get_post( $_GET['cid'] );

    if ( isset ( $people_object->post_type ) && $people_object->post_type == 'wp-type-contacts' ) {
      wp_enqueue_style( 'ukuupeople-style', UKUUPEOPLE_ABSPATH.'/css/ukuupeople.css');
      require_once( 'view-ukuupeople.php' );
    }
    else
      echo 'Ukuupeople doesnot exist';
  }

 function view_touchpoint( ) {
    if( ! isset( $_GET['touchpoint_id'] ) ||  $_GET['touchpoint_id'] == NULL )
   return;

    $touchpoint_id = $_GET['touchpoint_id'];
   $touchpoint_object = get_post( $_GET['touchpoint_id'] );

   if ( isset ( $touchpoint_object->post_type ) && $touchpoint_object->post_type == 'wp-type-activity' ) {
      wp_enqueue_style( 'ukuupeople-style', UKUUPEOPLE_ABSPATH.'/css/ukuupeople.css');
      wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
     require_once( 'view-touchpoint.php' );
   }
    else
      echo 'TouchPoint doesnot exist';
  }

  function manage_wp_posts_be_qe_posts_clauses( $pieces, $query ) {
     global $wpdb;
     $query->set( 'orderby', 'wp-startdate' );

     if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {
       // Get the order query variable - ASC or DESC
       $order = strtoupper( $query->get( 'order' ) );
       // Make sure the order setting qualifies. If not, set default as ASC
       if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) )
         $order = 'ASC';
       switch( $orderby ) {
         // ordering by date
         case 'wp-startdate':
           //We have to join the postmeta table to include our date in the query.
           $pieces[ 'join' ] .= " LEFT JOIN {$wpdb->postmeta} wp_sd ON wp_sd.post_id = {$wpdb->posts}.ID AND wp_sd.meta_key = 'wpcf-startdate'";
           $pieces[ 'orderby' ] = " wp_sd.meta_value $order";
 		break;
       }
     }
     return $pieces;
   }
}
