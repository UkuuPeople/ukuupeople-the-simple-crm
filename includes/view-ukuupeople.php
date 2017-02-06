<?php
		$type = get_the_terms( $people_object->ID , 'wp-type-contacts-subtype');
        $custom = get_post_custom( $id );
        $contactdetails_keys = array( 'wpcf-phone' => '' , 'wpcf-mobile'  =>'' );
        if ( isset( $custom['wpcf-phone'][0] ) && !preg_match( '/[^0-9]/', $custom['wpcf-phone'][0] ) ) {
          $phoneNumber = $custom['wpcf-phone'][0];
          $phoneNumber = substr( $phoneNumber, 0, 3 ).'-'.substr( $phoneNumber, 3, 3 ).'-'.substr( $phoneNumber, 6 );
          $phoneNumber = rtrim($phoneNumber , '-');
          $custom['wpcf-phone'][0] =  $phoneNumber;
        }

        $contactdetailsblock = array_intersect_key ( $custom, $contactdetails_keys ) ;
        $ctags = wp_get_post_terms( $id, 'wp-type-tags', array( "fields" => "names" ) );
        // Get Ukuupeople subtype
        $ukuu_subtype = get_the_terms( $id, 'wp-type-contacts-subtype' );
        echo "<div class='wrap'><h1>View {$ukuu_subtype[0]->name}</h1>";
        echo '<div id="post-body-content">';
        echo '<div id="first-sidebar-contact"> <div id="user-images">';
        if ( !empty( $custom['wpcf-contactimage'][0] ) ) {
          echo "<img src='".$custom['wpcf-contactimage'][0]."' width='150' height='150'>";
        } else {
          $avatar = get_avatar( $custom['wpcf-email'][0] ,150);
          echo $avatar;
        }
        echo '</div>';

        $action = 'add';
        $posts = self::retrieve_favorites();
        echo '<input type="hidden" name="star-ajax-nonce" id="star-ajax-nonce" value="' . wp_create_nonce( 'star-ajax-nonce' ) . '" />';
        if ( $posts && in_array( $id, $posts ) ) {
          $action = "del";
          echo "<div class='remove_fav_star' style='float:left;'><div id='fav-star' class='remove-star'></div></div>";
        } else {
          echo "<div class='add_to_fav_star' style='float:left;'><div id='fav-star' class='add-star'></div></div>";
        }

        echo '<div class="display-name"><div id="display-name">';
        if( isset($custom['wpcf-display-name'][0] ) ) {
          if ( isset( $type[0]->slug ) && $type[0]->slug == 'wp-type-org-contact' )
            echo '<font color="#30A08B">'.$custom['wpcf-display-name'][0].'wwww</font>';
          else
            echo '<font color="#0072BB">'.$custom['wpcf-display-name'][0].'</font>';
        }
        echo '</div>';
        $related_org = get_post_meta( $id, 'wpcf-related-org', true );
        if ( $related_org ) {
          $contact_org_url = get_edit_post_link ( $related_org );
          echo "<span class='contact-org-url'><a href='".$contact_org_url."'>".get_post_meta( $related_org, 'wpcf-display-name', true )."</a></span>";
        }
        echo '<div class="add-touchpoint">';
        $contact_id = get_the_ID();
        echo '</div></div>';
        echo '<div id="contactdetailsblock"><table id="contactdetail-table">';

        if ( isset( $custom['wpcf-email'] ) ) {
          echo "<tr><td><span class='contactdetailhead'>".__( 'Email', 'UkuuPeople' ) ."</span></td><td class='title-value'><a href='mailto:".$custom['wpcf-email'][0]. "'>".$custom['wpcf-email'][0]."</a></td></tr>";
        }

        foreach ( $contactdetailsblock as $key => $value ) {
          echo "<tr><td><span class='contactdetailhead'>".ucfirst( substr( $key, 5 ) )."</span></td><td class='title-value'>".$value[0]."</td></tr>";
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
        $city = isset( $custom['wpcf-city'] ) ? $custom['wpcf-city'][0] : '';
        $state = isset( $custom['wpcf-state'] ) ? $custom['wpcf-state'][0] : '';
        $postalcode = isset($custom['wpcf-postalcode']) ? $custom['wpcf-postalcode'][0] : '';

        $html .= "<tr><td>{$city}".( !empty( $city ) ? ', ' : '')."{$state} {$postalcode}</td></tr></table>";
        echo $html.'</div><div id="tagblock">';
        $html = "<table id='contactdetail-table'><tr><td><span class='contactdetailhead'>".__( 'Tags', 'UkuuPeople' ) ."</span></td></tr><tr>";
        $tags = '';
        if ( ! empty( $ctags ) ) {
          $tags = implode( ', ' , $ctags );
        }
        $html .= "<td><p class='title-value'>$tags</p></td></tr></table>";
        echo $html.'</div></div>';
        echo '</div>'; //post-body-content closed
        echo '</div>'; //post-body-content closed
        //~ do_action( 'tab_info' , $people_object ,$type[0]->slug );
        echo'<a class="button button-primary" href="'.admin_url('edit.php?post_type=wp-type-contacts').'">Back</a>';
