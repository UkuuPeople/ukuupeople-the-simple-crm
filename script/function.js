function first_true () {
    jQuery('.first-page').prop('disabled', true);
    jQuery('.pre-page').prop('disabled', true);
}
function first_false () {
    jQuery('.first-page').prop('disabled', false);
    jQuery('.pre-page').prop('disabled', false);
}
function last_true () {
    jQuery('.next-page').prop('disabled', true);
    jQuery('.last-page').prop('disabled', true);
}
function last_false () {
    jQuery('.next-page').prop('disabled', false);
    jQuery('.last-page').prop('disabled', false);
}

function refreshActivityList ( id, totalRows ,page ) {
    var x = document.getElementById("mySelect").value;
    var total_value = document.getElementById("total-value").innerHTML;
    var paged = document.getElementById("current-value").value;
    if ( page == 'first-page' ) {
	paged= 1;
	first_true();last_false();
    } else if ( page == 'pre-page' ) {
	paged= parseInt(paged) - 1;
	if( paged == 1) { first_true();last_false(); } else { last_false(); }
    } else if ( page == 'next-page' ) {
	paged= parseInt(paged) + 1;
	if( paged == total_value){ first_false();last_true(); } else { first_false(); }
    } else if( page == 'last-page') {
	paged = total_value;
	first_false();last_true();
    } else {
	var opt = activity_pagination(id, totalRows);
	if( opt == 1 ) { first_true();last_true(); } else { first_true();last_false(); }
	paged = 1;
    }
    var data = {
        'action': 'get_act_lists',
	'cid' : id,
        'paged' : paged,
	'limit' : x,
    };
    jQuery.post( ajaxurl, data, function( response ) {
	jQuery('#the-list').html(response);
	jQuery('.page-tablenav .current-page').val(paged);

    });
}

function activity_pagination(id, totalRows) {
    var x = document.getElementById("mySelect").value;
    var opt = Math.ceil(totalRows/x);
    jQuery("#current-value").val(1);
    jQuery("#total-value").html(opt);
    return opt;
}

function addToFav ($post, $entry) {
  var data = {
    'action': 'ukuu_add_to_fav',
    'entry': $entry,
    'post_id' : $post,
    'security': jQuery( '#star-ajax-nonce' ).val()
  };
  var ele = document.getElementById('fav-star');
  jQuery.post(ajaxurl, data, function( response ) {
    console.log(response);
    if ($entry === "del") {
      jQuery(ele).removeClass('remove-star');
      jQuery(ele).addClass('add-star');
      jQuery(ele).attr("onclick", "addToFav("+$post+", 'add');");
    }
    if ($entry === "add") {
      jQuery(ele).removeClass('add-star');
      jQuery(ele).addClass('remove-star');
      jQuery(ele).attr("onclick", "addToFav("+$post+", 'del');");
    }
  });
}
