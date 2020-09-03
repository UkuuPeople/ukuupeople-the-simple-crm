jQuery(document).ready(function($) {
  var $inline_editor = inlineEditPost.edit;

  inlineEditPost.edit = function(id) {
    //call old copy 
    $inline_editor.apply( this, arguments );
    //our custom functionality below
    var post_id = 0;
    if ( typeof(id) == 'object' ) {
      post_id = parseInt( this.getId(id) );
    }

    //if we have our post
    if ( post_id != 0 ) {
      // get assignee contacts
      get_assignee_contacts(post_id);

      //find our row
      $row = $('#edit-' + post_id);

      // for touchpoints only
      if ( $("body").hasClass('post-type-wp-type-activity') ) {

        // Note does not have status so we need to remove
        if ( $("#inline_" + post_id + " div.post_category" ).html() == 6 ) {
          $row.find("#ukuu-quick-edit-activity-status").remove();
        }

        //post featured
        post = $("#post-" + post_id + " td[data-colname='Status'] div");
        wp_status = $(post).attr('id'); 

        if( wp_status ) {
          $row.find("#touchpoint-status-" + wp_status).val(wp_status).attr('checked', true);
        }
      }
    }
  }

  get_assignee_contacts();

  function get_assignee_contacts( post_id ) {
    // If post_id is passed then it's inline edit else bulk-edit
    $row = ( post_id ) ? $('#edit-' + post_id) : $( "#bulk-edit" );

    // Get Assignee contacts
    var data = {
      action  : 'ukuu_quick_edit_get_field_data',
      field   : 'wpcf-assignee',
      id      : post_id,
      nonce   : quick_edit.nonce,
    }

    // Ajax call for getting assigne contacts
    $.ajax({
      method  : "POST",
      url     : quick_edit.ajaxurl,
      data    : data,
    }).done( function( response ) {
      var result = $.parseJSON( response );
      if ( result.options ) {
        $( result.options ).each( function( id, val ) {
          var checked = '';

          if ( result.values ) {
            $.each( result.values[0], function( index, field_val ) {
              if ( field_val == String(val.id) ) {
                checked = 'checked="checked"';
              }
            });
          }

          html = '<li>';
          html += '<label class="selectit">';
          html += '<input id="touchpoint-assignee-' + val.name + '" type="checkbox" value="' + val.id + '" name="touchpoint_assignee[]"' + checked + '> ' + val.name;
          html += '</label>';
          html += '</li>';
          $('.touchpoint-assignee-checklist', $row).append( html );
        });
      }
    });
  }

   // Alter taxonomy field for single-selection in quick edit (for touchpoint only)
   $(".wp-type-activity-types-checklist input[type='checkbox']").on('click', function () {
    var current_selection = $(this).val();

    $("input[type='checkbox']", ".wp-type-activity-types-checklist").each( function (index, value) {
      if ( $(this).val() != current_selection )
        $(this).prop('checked', false);
    });
  });

   $(".touchpoint-status-checklist input[type='checkbox']").on('click', function () {
    var current_selection = $(this).val();

    $("input[type='checkbox']", ".touchpoint-status-checklist").each( function (index, value) {
      if ( $(this).val() != current_selection )
        $(this).prop('checked', false);
    });
  });
   // Alter taxonomy field for single-selection in quick edit (for Contacts only)
   $(".wp-type-contacts-subtype-checklist input[type='checkbox']").on('click', function () {
    var current_selection = $(this).val();

    $("input[type='checkbox']", ".wp-type-contacts-subtype-checklist").each( function (index, value) {
      if ( $(this).val() != current_selection )
        $(this).prop('checked', false);
    });
  });
});
