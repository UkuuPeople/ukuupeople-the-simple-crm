 <div class='wrap'><h1>View TouchPoint</h1>
 <?php
    if ( isset($touchpoint_object->ID) && ( $touchpoint_object->post_type == 'wp-type-activity' ) ) {
      $custom = get_post_custom( $touchpoint_object->ID );
      $start_date = $time = $subject = $details = $activity_name = $contact_id = $post_id = $attachments = $extension = $status_color = '';
      $display_name = $related_org = $org_name = $contact_dash_url = $contact_image =  $termSlug = $status = '';
      if( !empty( $custom['wpcf-attachments'][0] ) ) {
        $attachments = $custom['wpcf-attachments'][0];
        $explode = explode(".",$custom['wpcf-attachments'][0] );
        $extension = end($explode);
      }
      $start_date = date("F d, Y", $custom['wpcf-startdate'][0]);
      $time = date("h:i a", $custom['wpcf-startdate'][0]);
      $subject = get_the_title($touchpoint_object->ID);
      if( isset( $custom['wpcf-details'][0] ) && !empty( $custom['wpcf-details'][0] ) ) {
        $details = $custom['wpcf-details'][0];
      }
      $termType = $acttype = get_the_terms( $touchpoint_object->ID , 'wp-type-activity-types');
      if ( !empty($acttype) ){
        $term = array_shift( $termType);
        $termSlug = $term->slug;
        $activity_name = $term->name;
      }
      $selectedColor = get_option('term_category_radio_' . $termSlug);
      if( $activity_name == 'Donation' ) {
        $selectedColor = '#d3d3d3';
      }
      $color = array( 'Meeting' => '#377CB6' , 'Phone' => '#771D78'  , 'Note' => '#3DA999' , 'Contact Form' => '#E6397A' );
      foreach( $color as $key => $value ) {
        if( $key == $activity_name ) {
          $selectedColor = $value;
        }
      }
      $assigned_to = get_post_meta($touchpoint_object->ID, 'wpcf_assigned_to', true);
      $post_id = get_post_meta($touchpoint_object->ID, '_wpcf_belongs_wp-type-contacts_id', true);
      $post_type = get_post_type( $post_id );
      if( $post_type == 'wp-type-opportunity' ){
        $display_name = get_post_meta( $post_id, UKKU_LEAD_MANAGEMENT_PREFIX . 'name', true );
      }elseif( $post_type == 'wp-type-contacts' ){
        $display_name = get_post_meta($post_id,'wpcf-display-name', true);
      }
      //~ print_r($post_type);exit;
      $related_org = get_post_meta($post_id, 'wpcf-related-org', true);
      $org_name = get_post_meta($related_org,'wpcf-display-name', true);
      $contact_dash_url = get_edit_post_link($post_id );
      $contact_image = get_post_meta($contact_id,'wpcf-contactimage', true);
      if ( empty( $contact_image ) ) {
        $email = get_post_meta( $post_id,'wpcf-email', true);
        $contact_image = get_avatar( $email ,150 );
      } else {
        $contact_image = "<img src='".$contact_image."' width='150' height='150'>";
      }
      if( isset($custom['wpcf-status'][0]) ) {
        $status = $custom['wpcf-status'][0];
      }
      $statusColor = array( 'completed' => '#39b54a', 'scheduled' => '#2272BB', 'cancel' => '#FF0000' );
      foreach( $statusColor as $statusC => $colorC ) {
        if($status == $statusC) {
          $status_color = $colorC;
        }
      }
      $statusImage = array( 'completed' => '../images/tick.png', 'scheduled' => '../images/event.png', 'cancel' => '../images/cross.png' );
      foreach( $statusImage as $statusI => $imageI ) {
        if($status == $statusI) {
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
      <?php if( $post_id != 0 ) { ?>
      <span class='contact-dashboard'><a href='<?php echo $contact_dash_url ?>' class='button button-primary'><?php _e( 'Contact Dashboard', 'UkuuPeople' ) ?></a></span><?php } ?>
     	</div>
      <div class='subject-summary'><span class='label-subject'>Subject </span><span class='subject-content' style="<?php echo 'color:'.$selectedColor ?>"><?php echo $subject ?></span></div>
      <div class='type-summary'>
      <span class='label-subject'>Type </span>
      <?php if( isset($activity_name ) && !empty($activity_name) ) { ?>
      <svg height='18' width='20'><circle cx='10' cy='10' r='7' fill='<?php echo $selectedColor ?>' /></svg>
      <?php } ?>
      <span class='type-content'><?php echo $activity_name ?></span>
      </div>
      <div class='summary-datetime-section group'>
      <?php if( $activity_name != 'Note' ) {?>
      <div class='summary-datetime-col summary-status date-time-a'>
      <span class="completed-summary" style="<?php echo 'background-color:'.$status_color ?>" ><img style="margin-right:5px" src="<?php echo plugins_url( $status_image, __FILE__ );?>" /><?php echo ucfirst($status); ?></span></div>
      <?php } ?>
      <div class='summary-datetime-col summary-status date-time-b'><span class='ukuucalendar'></span><span class='start-date-summary'><?php echo $start_date ?></span></div>
      <div class='summary-datetime-col summary-status date-time-c'><span class='ukuuclock'></span><span class='start-date-summary'><?php echo $time ?></span></div>
      </div>
      <?php if( !empty( $custom['wpcf-attachments'][0] ) ): ?><div class='attachment-summary label-subject' >Attachments</div>
      <div class='custom-assigned-for-attachments-section' ><?php endif; ?>
      <?php
      if( !empty( $custom['wpcf-attachments'][0] ) ) {
        $attachments = $custom['wpcf-attachments'][0];
        $attachment = unserialize($attachments);
        foreach( $attachment as $key => $attach ) {
          $explode = explode(".",$attach );
          $extension = end($explode);
          echo "<div class='assigned-column assigned-contact-1 assigned-a custom-assigned-for-attachments'  >";
          if( $extension == 'jpg' || $extension == 'jpeg' || $extension == 'gif' || $extension == 'png' || $extension == 'bmp' || $extension == 'tiff' ) {
            echo "<a href='".$attach."' download ><div class='attachment-first' id='".$key."'><img src='".plugins_url( '../images/save.gif', __FILE__ )."' class='over-image'  width='60' height='60'  /><div class='inner-attachment-first'></div><img class='under-image' src='".$attach."' width='118' height='116' /></div></a>";
          } else if ( ( $extension == '' ) && !isset( $custom['wpcf-attachments'][0] ) ) {
          } else {
            echo "<a href='".$attach."' ><div class='attachment-first attachment-doc' id='".$key."'><img src='".plugins_url( '../images/save.gif', __FILE__ )."' class='over-image'  width='60' height='60' /><div class='inner-attachment-first'></div><span class='attachment-text under-image'>".strtoupper($extension)."</span></div></a>";
          }
          echo "</div>";
        }
      }
      ?>
      <?php if( !empty( $custom['wpcf-attachments'][0] ) ): ?>
      </div>
      <?php
      endif; // .custom-assigned-for-attachments-section closed
      ?>
      </div>
      </div>
      <div class='right-summary-activity'>
      <div class='summary-details'><div class='summary-details-head label-subject'>Details</div><div class='summary-details-body'><?php echo $details ?></div>
      </div>
      <div class='summary-assigned-to'><div class='summary-details-head label-subject'><?php _e( 'Assigned to', 'UkuuPeople' ) ?></div>
      <div class='assigned-section' >
      <?php
      if( isset( $assigned_to ) && !empty( $assigned_to ) ) {
        foreach( $assigned_to as $assigned ) {
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
      <?php if( current_user_can('edit_touchpoint') ):?>
      <div class="edit-touchpoint"><a href="post.php?post=<?php echo $touchpoint_object->ID;?>&action=edit#wpcf-group-activity-information">Edit TouchPoint</a></div>
      <?php
      endif;
      ?>
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
    }?></div>

<script>
jQuery('.posts_page_view-touchpoint .over-image').hide();
    jQuery('.posts_page_view-touchpoint .inner-attachment-first').hide();
    jQuery(".posts_page_view-touchpoint .attachment-first").hover(function(){
	var id = this.id;
	jQuery( '.posts_page_view-touchpoint #'+id+' .over-image' ).show();
	jQuery( '.posts_page_view-touchpoint #'+id+' .inner-attachment-first' ).show();
    }, function(){
	var id = this.id;
	jQuery( '.posts_page_view-touchpoint #'+id+' .over-image' ).hide();
	jQuery( '.posts_page_view-touchpoint #'+id+' .inner-attachment-first' ).hide();
    });
</script>
