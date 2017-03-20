jQuery(function ($) {

//Welcome page
jQuery('#mc-embedded-subscribe-form-ukuu').on('submit', function () {
  var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
  var email = jQuery('#mce-EMAIL').val();
  if (!testEmail.test(email)) {
        setMsg( "Invalid Email");
        return false;
    }
});

function setMsg ( $msg, $error_type ) {
    if ( ! $msg )
      return false;

    if ( ! $error_type ) 
      $error_type = 'ukuu-subscription-error';

    jQuery("#ukuu-subscription-status").addClass( $error_type );
    jQuery("#ukuu-subscription-status").html( $msg );
    jQuery('#ukuu-subscription-status').css('color', 'red');
    jQuery('#ukuu-subscription-status').css('font-size', '12px');
    jQuery('#ukuu-subscription-status').css('font-weight', 'bold');
}

  // Contribution Dahsboard code End //
  // Column of activity graph on Find Contact page code start //

jQuery('div.tdata').find('svg').each(function(i, el) {
	var ColumnName=jQuery(this).attr("activityc");
	var row = JSON.parse(ColumnName);
	var a=row['val'];
	var b=row['colorlist'];
	var c=row['contactid'];
	var lineData = [{ "x": a[0][0],  "y": a[0][1]},
			{ "x": a[1][0],  "y": a[1][1]},
		        { "x": a[2][0],  "y": a[2][1]},
		        { "x": a[3][0],  "y": a[3][1]},
		        { "x": a[4][0],  "y": a[4][1]}];
	var vis = d3.select("#visualisation_"+c),
	WIDTH = 150,
	HEIGHT = 50,
	MARGINS = {
	    top: 5,
	    right: 5,
	    bottom: 5,
	    left: 5
	},
	xRange = d3.scale.linear().range([MARGINS.left, WIDTH - MARGINS.right]).domain([d3.min(lineData, function (d) {
            return d.x;
	  }),
	  d3.max(lineData, function (d) {
	    return d.x;
	  })
	]),
	yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([d3.min(lineData, function (d) {
          return d.y;
	}), 25
											]),
	xAxis = d3.svg.axis()
	    .scale(xRange)
	    .tickSize(5),
	yAxis = d3.svg.axis()
	    .scale(yRange)
	    .tickSize(5)
	    .orient("left");

	var lineFunc = d3.svg.line()
  	    .x(function (d) {
    		return xRange(d.x);
  	    })
  	    .y(function (d) {
    		return yRange(d.y);
  	    })
  	    .interpolate('linear');

  	vis.append("svg:path")
  	    .attr("d", lineFunc(lineData))
  	    .attr("stroke", b)
  	    .attr("stroke-width", 2)
  	    .attr("fill", "none");

  });
  // Column of activity graph on Find Contact page code End //
});

jQuery(document).ready(function() {
    touchpoint = '#'+jQuery("#touchpoint-types").parent().attr('id');
    jQuery( touchpoint ).insertBefore("#post-body-content");
    jQuery( "#touchpoint-types" ).removeClass( "postbox" );
    jQuery('.graph-main-container').insertBefore("#posts-filter");
    jQuery('#activity-list').insertAfter("#postbox-container-2 #wpcf-post-relationship");
    noteChanges(jQuery('select#touchpoint-list'));

    jQuery('.post-type-wp-type-activity #submitpost #publish').click( function() {
	var arr = ['startdate', 'enddate'];
	jQuery.each(arr, function( index, value ) {
	    var display = jQuery('div[data-wpt-id="wpcf-'+value+'"]').find('input').val();
	    var note = jQuery('select#touchpoint-list').val();
            if ( (display == '' || !display) && (note == 'wp-type-activity-note')) {
		jQuery('.post-type-wp-type-activity .cmb2-id-wpcf-startdate input').prop("disabled",true);
		jQuery('.post-type-wp-type-activity .cmb2-id-wpcf-enddate input').prop("disabled",true);
		jQuery('.post-type-wp-type-activity .cmb2-id-wpcf-status input').prop("disabled",true);
            }
	});
    });

    jQuery('.post-type-wp-type-contacts .edit_contact a').click( function() {
	jQuery('.post-type-wp-type-contacts #wpcf-group-edit-contact-info').removeClass( 'closed' );
    });
    jQuery('select#touchpoint-list').on('change', function() {
	noteChanges(this);
    });
    jQuery("select[name='dtype']").on('change', function() {
	DashboardNoteChanges(this);
    });
    function DashboardNoteChanges ($this) {
	if (jQuery($this).val() == 'wp-type-activity-note') {
	    jQuery('#quickAddform .quickadd input[name="dsdate"]').parent().parent().hide();
	    jQuery('#quickAddform .quickadd input[name="dsdate"]').prop("disabled",true);
	    jQuery('#quickAddform .quickadd input[name="dedate"]').parent().parent().hide();
	} else {
	    jQuery('#quickAddform .quickadd input[name="dsdate"]').parent().parent().show();
	    jQuery('#quickAddform .quickadd input[name="dsdate"]').prop("disabled",false);
	    jQuery('#quickAddform .quickadd input[name="dedate"]').parent().parent().show();
	}
    }
    function noteChanges ($this) {
	if (jQuery($this).val() == 'wp-type-activity-note') {
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-startdate').hide();
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-enddate').hide();
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-status').hide();
	} else {
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-startdate').show();
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-enddate').show();
	    jQuery('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-status').show();
	}
    }

    //dialog box
    jQuery( "#dialog" ).dialog({
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
    });
    jQuery("input[name='wp-contact-type-select']").on('click',function( event ) {
        window.location = jQuery(this).attr('redirect');
    });
    jQuery("input[name='wp-touchpoint-contact-select']").on('click',function( event ) {
        var contactid = jQuery("#touchpoint_contact_id").val();
        var url = jQuery(this).attr('redirect')+"&cid="+contactid;
        window.location = url;
    });
    //dialog box

    // autocomplete for contact list
    var data = {
            'action': 'contact_list',
	};
    var result = [];
    jQuery.post( ajaxurl, data, function( response ) {
	var availableTags = JSON.parse(response);
	jQuery.each(availableTags, function( index, value ) {
	    result.push({ 'value' : value.id , 'label' : value.name});
	});
    });

    // autocomplete for assignee contact
    var assign_data = {
            'action': 'assign_contact_list',
	};
    var assing_result = [];
    jQuery.post( ajaxurl, assign_data, function( response ) {
	var availableTags = JSON.parse(response);
	jQuery.each(availableTags, function( index, value ) {
	    assing_result.push({ 'value' : value.id , 'label' : value.name});
	});
    });


  function split( val ) {
    return val.split( /,\s*/ );
  }

  function extractLast( term ) {
    return split( term ).pop();
  }

// autocomplete for quick add touchpoint "assign to" comma seprate.
   jQuery( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='touchpoint_assign_name_display']" )
    .autocomplete({
	minLength: 0,
   source: function( request, response ) {
response( jQuery.ui.autocomplete.filter(
assing_result, extractLast( request.term ) ) );
},
	focus: function( event, ui ) {
	    return false;
	},
	select: function( event, ui ) {
        var terms = split( this.value );
        terms.pop();
        this.value = terms.join( ", " );
        var selected_label = ui.item.label;
        var selected_value = ui.item.value;

        var labels = jQuery(".quickadd input[name='touchpoint_assign_name_display']").val();
        var values = jQuery( "#touchpoint_assign_id" ).val();

        if(labels == "") {
        jQuery(".quickadd input[name='touchpoint_assign_name_display']").val(selected_label);
        jQuery( "#touchpoint_assign_id" ).val(selected_value);
      }
       else {
        jQuery(".quickadd input[name='touchpoint_assign_name_display']").val(labels+","+selected_label);
        jQuery( "#touchpoint_assign_id" ).val(values+","+selected_value);
      }
	    return false;
	}
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return jQuery( "<li>" )
	    .append( "<a>" + item.label + "</a>" )
	    .appendTo( ul );
    };

    // autocomplete for quick add touchpoint
    jQuery( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dname']" ).autocomplete({
	//source: availableTags,
	minLength: 0,
	source: result,
	focus: function( event, ui ) {
	    jQuery( ".quickadd input[name='dname']" ).val( ui.item.label );
	    return false;
	},
	select: function( event, ui ) {
	    jQuery( ".quickadd input[name='dname']" ).val( ui.item.label );
	    jQuery( "#dcontact_id" ).val( ui.item.value );
	    
	    return false;
	}
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return jQuery( "<li>" )
	    .append( "<a>" + item.label + "</a>" )
	    .appendTo( ul );
    };

    // autocomplete for add new touchpoint
    jQuery( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).autocomplete({
	//source: availableTags,
	minLength: 0,
	source: result,
	focus: function( event, ui ) {
	    jQuery( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).val( ui.item.label );
	    return false;
	},
	select: function( event, ui ) {
	    jQuery( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).val( ui.item.label );
	    jQuery( "#touchpoint_contact_id" ).val( ui.item.value );
	    
	    return false;
	}
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return jQuery( "<li>" )
	    .append( "<a>" + item.label + "</a>" )
	    .appendTo( ul );
    };

    jQuery('#quickAddform').on('submit', function (e) {
	e.preventDefault();
	var fd = new FormData(); 
	fd.append('action', 'quick_add_touchpoint');

	var dtype = jQuery(this).find('select[name="dtype"]').val();
	var ddetails = jQuery(this).find('textarea[name="ddetails"]').val();
	fd.append('dtype', dtype); 
	fd.append('ddetails', ddetails); 
	
	var arr = ["contact_id","dsubject","dsdate","dedate","dstime","detime","filename","touchpoint_assign_id"];
	jQuery.each(arr, function( index, value ) {
	    var datavalue = jQuery(document).find('input[name="'+value+'"]').val();
	    fd.append(value, datavalue); 
	});

	jQuery.ajax({
            type: 'POST',
	    url : ajaxurl,
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
		location.reload(true);
            }
	});

	jQuery('#quickAddform').trigger('reset');
	jQuery(".quickadd #filename").hide();
	jQuery(".quickadd #touchpoint_assign_name_display").hide();
	jQuery(".quickadd .seprate").hide();
    });

    jQuery("#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dupload']").click( function() {
	formfield = jQuery('.quickadd #filename').attr('name');
	tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
	return false;
    });

  window.send_to_editor = function(html) {
	imgurl = jQuery(html).attr('src');
    fileurl = jQuery(html).attr('href');
    var url;
    if (imgurl == undefined) {
      url = fileurl;
    }
   else {
    url = imgurl;
    }
	jQuery('.quickadd #filename').val(url);
	jQuery(".quickadd #filename").show();
	tb_remove();
    }

    jQuery('.post-type-wp-type-activity .over-image').hide();
    jQuery('.post-type-wp-type-activity .inner-attachment-first').hide();
    jQuery(".post-type-wp-type-activity .attachment-first").hover(function(){
	var id = this.id;
	jQuery( '.post-type-wp-type-activity #'+id+' .over-image' ).show();
	jQuery( '.post-type-wp-type-activity #'+id+' .inner-attachment-first' ).show();
    }, function(){
	var id = this.id;
	jQuery( '.post-type-wp-type-activity #'+id+' .over-image' ).hide();
	jQuery( '.post-type-wp-type-activity #'+id+' .inner-attachment-first' ).hide();
    });

    jQuery('.post-type-wp-type-activity .edit-touchpoint a').click( function() {
	jQuery('.post-type-wp-type-activity #wpcf-group-activity-information').removeClass( 'closed' );
    });
    
    jQuery('#wpcf-startdate_time').removeAttr( "required" );

    jQuery("#wpcf_touchpoint_assigned_metabox .cmb-add-row-button.button").text('Add another Assignee');
    /* Date of Birth Validation */
    jQuery('input[name="publish"]').prop('disabled', false);
    jQuery("#wpcf-ukuu-date-of-birth").change( function() {
      var currentDateTime = new Date();
      if( new Date(this.value) > new Date(currentDateTime) ) {
	    jQuery('.invalidrange').remove();
	    jQuery('<div class="invalidrange">Invalid date of birth</div>').insertAfter(".cmb2-id-wpcf-ukuu-date-of-birth .cmb-td");
	    jQuery('.invalidrange').css('color','red');
	    jQuery('input[id="publish"]').prop('disabled', true);
	    } else {
	    jQuery('.invalidrange').remove();
	    jQuery('input[id="publish"]').prop('disabled', false);
	  }
  });
});

//graph on Contact and event list
jQuery( '#graph' ).ready(function() {
    if (jQuery('#chart').attr('type') && jQuery('#chart').attr('color')) {
	var type = JSON.parse(jQuery('#chart').attr('type'));
	var colorSet = JSON.parse(jQuery('#chart').attr('color'));
	var dataset = [];
	var color = [];
	var mainTotal = 0;
	color.push("#ffffff");
	jQuery.each( colorSet , function( i, val ) {
	    color.push(val);
	    color.push("#ffffff");
	});
	jQuery.each( type, function( i, val ) {
	    mainTotal += parseInt(val);
	});
	mainTotal = mainTotal*0.001;

	var data = {};
	var numeric = [[]];
	numeric[0]['yHandler'] = mainTotal;
	data.data = numeric;
	data.name= 0;
	dataset.push(data);
	jQuery.each( type, function( i, val ) {
	    var data = {};
	    var numeric = [[]];
	    numeric[0]['yHandler'] = parseInt(val);
	    data.data = numeric;
	    data.name= i;
            dataset.push(data);
	    var data = {};
	    var numeric = [[]];
	    numeric[0]['yHandler'] = mainTotal;
	    data.data = numeric;
	    data.name= i;
            dataset.push(data);
	});
	drawit(dataset);
    }

    function drawit(dataset) {

	var margins = {
	    top: 0,
	    left: 5,
	    right: 5,
	    bottom: 5
	};

	var dynamic_width = jQuery("#graph").width();
	var width = dynamic_width-25;
	var height = 75;

	var series = dataset.map(function (d) {
	    return d.name;
	});

	var dataset = dataset.map(function (d) {
	    return d.data.map(function (o, i) {
		// Structure it so that your numeric
		// axis (the stacked amount) is y
		return {
		    y: +o.yHandler
		};
	    });
	});

	stack = d3.layout.stack();

	stack(dataset);
	var dataset = dataset.map(function (group) {
	    return group.map(function (d) {
		// Invert the x and y values, and y0 becomes x0
		return {
		    x0: d.y0,
		    x: d.y
		};
	    });
	});

	var svg = d3.select('#graph')
	    .append('svg')
	    .attr('width', width + margins.left + margins.right + 20 )
	    .attr('height', height + margins.top + margins.bottom)
	    .attr('transform', 'translate(' + margins.left + ',' + margins.top + ')')
	    .style('margin-left','10')

	var xMax = d3.max(dataset, function (group) {
	    return d3.max(group, function (d) {
		return d.x+d.x0;
	    });
	})

	var xScale = d3.scale.linear()
	    .domain([0, xMax])
	    .range([0, width]);

	var heightGraph = dataset[0].map(function (d) {
	    return d.y;
	});

	var yScale = d3.scale.ordinal()
	    .domain(heightGraph)
	    .rangeRoundBands([0, height], .1);

	var colours = d3.scale.ordinal()
	    .range(color)
	    .domain([0,1,2]);


	var groups = svg.selectAll('g')
	    .data(dataset)
	    .enter()
	    .append('g')
	    .style('fill', function (d, i) {
		return colours(i);
	    });

	var text = groups.selectAll("rect")
	    .data(dataset)
	    .enter()
	    .append("text");

	var textLabels = text
	    .attr("x", function(dataset) {
	        var x;
	        jQuery.each(dataset, function(key, value) {
		    x = value.x0;
		    x1 = value.x;

	        });
	        var rangeBand = x/xMax*width;
	        return rangeBand+5;
	    }

	         )
	    .attr("y", "50")
	    .text( function(dataset) {
	        var x;
	        jQuery.each(dataset, function(key, value) {
		    x = value.x;
	        });
	        if(x == 0 || x == mainTotal){
		    //do nothing
	        } else {
		    return x;
	        }
	    })
	    .attr("font-size", "30px")
	    .attr("font-weight", "bold")
	    .attr("fill", "#FFFFFF")
	    .style("margin-left", "5");

	var rects = groups.selectAll('rect')
	    .data(function (d) {
		return d;
	    })
	    .enter()
	    .append('rect')
	    .attr('x', function (d) {
		return xScale(d.x0);
	    })
	    .attr('y', function (d, i) {
		return yScale(d.y);
	    })
	    .attr('height', function (d) {
		return yScale.rangeBand();
	    })
	    .attr('width', function (d) {
		return xScale(d.x);
	    });

	svg.append('g')
	    .attr('class', 'x axis')
	    .attr('transform', 'translate(0,' + height + ')');

	svg.append('g')
	    .attr('class', 'y axis');
    }
});

 // Alter taxonomy field for single-selection in quick edit (for touchpoint only)
 jQuery(".wp-type-activity-types-checklist input[type='checkbox']").on('click', function () {
  var current_selection = jQuery(this).val();

  jQuery("input[type='checkbox']", ".wp-type-activity-types-checklist").each( function (index, value) {
    if ( jQuery(this).val() != current_selection )
      jQuery(this).prop('checked', false);
  });
});

 // Alter taxonomy field for single-selection in quick edit (for Contacts only)
 jQuery(".wp-type-contacts-subtype-checklist input[type='checkbox']").on('click', function () {
  var current_selection = jQuery(this).val();

  jQuery("input[type='checkbox']", ".wp-type-contacts-subtype-checklist").each( function (index, value) {
    if ( jQuery(this).val() != current_selection )
      jQuery(this).prop('checked', false);
  });
});

jQuery('.post-type-wp-type-activity #post').on('submit', function (e) {
    var datavalue = jQuery('input[name="post_title"]').val();
    if ( datavalue == "" ) {
	jQuery('.errordesc').remove();
	jQuery('<div class="errordesc">Please enter valid description</div>').insertAfter("#titlewrap");
	jQuery('.errordesc').css('color','red');
	return false;
    } else{
	jQuery('.errordesc').remove();
	return true;
    }
});
