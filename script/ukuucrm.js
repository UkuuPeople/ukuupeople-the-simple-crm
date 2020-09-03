jQuery(function ($) {
	$("#touchpoint_assign_name_display").attr('readonly', 'true');

	//Welcome page
	$('#mc-embedded-subscribe-form-ukuu').on('submit', function () {
  		var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
  		var email = $('#mce-EMAIL').val();
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

    	$("#ukuu-subscription-status").addClass( $error_type );
    	$("#ukuu-subscription-status").html( $msg );
    	$('#ukuu-subscription-status').css('color', 'red');
    	$('#ukuu-subscription-status').css('font-size', '12px');
    	$('#ukuu-subscription-status').css('font-weight', 'bold');
	}

  	// Contribution Dahsboard code End //
  	// Column of activity graph on Find Contact page code start //

    $('div.tdata').find('svg').each(function(i, el) {
		var ColumnName = $(this).attr("activityc");
		var row = JSON.parse(ColumnName);
		var a=row['val'];
		var b=row['colorlist'];
		var c=row['contactid'];
		var lineData = [
			{ "x": a[0][0],"y": a[0][1]},
			{ "x": a[1][0],  "y": a[1][1]},
		    { "x": a[2][0],  "y": a[2][1]},
		    { "x": a[3][0],  "y": a[3][1]},
		    { "x": a[4][0],  "y": a[4][1]}
		];
		var vis = d3.select("#visualisation_"+c),
			WIDTH = 150,
			HEIGHT = 50,
			MARGINS = {
	    		top: 5,
	    		right: 5,
	    		bottom: 5,
	    		left: 5
			},
			xRange = d3.scale.linear().range([MARGINS.left, WIDTH - MARGINS.right]).domain([
				d3.min(lineData, function (d) {
            		return d.x;
	  			}),
	  			d3.max(lineData, function (d) {
	    			return d.x;
	  			})
			]),
			yRange = d3.scale.linear().range([HEIGHT - MARGINS.top, MARGINS.bottom]).domain([
				d3.min(lineData, function (d) {
          			return d.y;
				}),
				25
			]),
			xAxis = d3.svg.axis().scale(xRange).tickSize(5),
			yAxis = d3.svg.axis().scale(yRange).tickSize(5).orient("left");

		var lineFunc = d3.svg.line().x(function (d) {
    			return xRange(d.x);
  	    	}).y(function (d) {
    			return yRange(d.y);
  	    	}).interpolate('linear');

  		vis.append("svg:path").attr("d", lineFunc(lineData)).attr("stroke", b).attr("stroke-width", 2).attr("fill", "none");
  	});
  	// Column of activity graph on Find Contact page code End //
});

jQuery(document).ready(function($) {
    touchpoint = '#'+$("#touchpoint-types").parent().attr('id');
    $( touchpoint ).insertBefore("#post-body-content");
    $( "#touchpoint-types" ).removeClass( "postbox" );
    $('.graph-main-container').insertBefore("#posts-filter");
    $('#activity-list').insertAfter("#postbox-container-2 #wpcf-post-relationship");
    noteChanges($('select#touchpoint-list'));

    $('.post-type-wp-type-activity #submitpost #publish').click( function() {
		var arr = ['startdate', 'enddate'];
		$.each(arr, function( index, value ) {
	    	var display = $('div[data-wpt-id="wpcf-'+value+'"]').find('input').val();
	    	var note = $('select#touchpoint-list').val();
            if ( (display == '' || !display) && (note == 'wp-type-activity-note')) {
				$('.post-type-wp-type-activity .cmb2-id-wpcf-startdate input').prop("disabled",true);
				$('.post-type-wp-type-activity .cmb2-id-wpcf-enddate input').prop("disabled",true);
				$('.post-type-wp-type-activity .cmb2-id-wpcf-status input').prop("disabled",true);
            }
		});
    });

    $('.post-type-wp-type-contacts .edit_contact a').click( function() {
		$('.post-type-wp-type-contacts #wpcf-group-edit-contact-info').removeClass( 'closed' );
    });
    $('select#touchpoint-list').on('change', function() {
		noteChanges(this);
    });
    $("select[name='dtype']").on('change', function() {
		DashboardNoteChanges(this);
    });
    function DashboardNoteChanges ($this) {
		if ($($this).val() == 'wp-type-activity-note') {
	    	$('#quickAddform .quickadd input[name="dsdate"]').parent().parent().hide();
	    	$('#quickAddform .quickadd input[name="dsdate"]').prop("disabled",true);
	    	$('#quickAddform .quickadd input[name="dedate"]').parent().parent().hide();
		}
		else {
		    $('#quickAddform .quickadd input[name="dsdate"]').parent().parent().show();
	    	$('#quickAddform .quickadd input[name="dsdate"]').prop("disabled",false);
	    	$('#quickAddform .quickadd input[name="dedate"]').parent().parent().show();
		}
    }
    function noteChanges ($this) {
		if ($($this).val() == 'wp-type-activity-note') {
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-startdate').hide();
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-enddate').hide();
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-status').hide();
		}
		else {
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-startdate').show();
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-enddate').show();
	    	$('.post-type-wp-type-activity #wpcf-group-activity-information .cmb2-id-wpcf-status').show();
		}
    }

    //dialog box
    $( "#dialog" ).dialog({
        modal : true,
        autoOpen: false,
        dialogClass: 'override-give-css',
        show: {
        	effect: "blind",
        	duration: 1000
        },
        hide: {
        	effect: "explode",
        	duration: 1000
        },
    });
    $("input[name='wp-contact-type-select']").on('click',function( event ) {
    	window.location = $(this).attr('redirect');
    });
    $("input[name='wp-touchpoint-contact-select']").on('click',function( event ) {
        var contactid = $("#touchpoint_contact_id").val();
        var url = $(this).attr('redirect')+"&cid="+contactid;
        window.location = url;
    });
    //dialog box

    // autocomplete for contact list
    var data = {
    	'action': 'contact_list',
	};
    var result = [];
    $.post( ajaxurl, data, function( response ) {
		var availableTags = JSON.parse(response);
		$.each(availableTags, function( index, value ) {
	    	result.push({ 'value' : value.id , 'label' : value.name});
		});
    });

    // autocomplete for assignee contact
    var assign_data = {
    	'action': 'assign_contact_list',
	};
    var assing_result = [];
    $.post( ajaxurl, assign_data, function( response ) {
		var availableTags = JSON.parse(response);
		$.each(availableTags, function( index, value ) {
	    	assing_result.push({ 'value' : value.id , 'label' : value.name});
		});
    });

	function split( val ) {
  		return val.split( /,\s*/ );
  	}
	function extractLast( term ) {
    	return split( term ).pop();
  	}
  	// Assign cmb classes to datepicker for Widgets
  	$( 'body' ).on( 'click', '.hasDatepicker', function() {
  		$( '.ui-priority-primary' ).addClass( 'button-primary' );
  		$( '.ui-priority-secondary' ).addClass( 'button-secondary' );
  		$( '.ui-datepicker' ).addClass( 'cmb2-element' );
  		if ( $( this ).next().hasClass( 'ukuuclock' ) || $( this ).hasClass( 'cmb2-timepicker' ) ) {
  			$( '#ui-datepicker-div' ).addClass( 'ukuu-clock' ).removeClass( 'ukuu-calendar' );
  		}
  		else {
  			$( '#ui-datepicker-div' ).addClass( 'ukuu-calendar' ).removeClass( 'ukuu-clock' );
  		}
  	});
  	$( document ).on( 'click', '#ui-datepicker-div, .ui-datepicker-current', function() {
  		$( '.ui-priority-primary' ).addClass( 'button-primary' );
  		$( '.ui-priority-secondary' ).addClass( 'button-secondary' );
  	});

	// autocomplete for quick add touchpoint "assign to" comma seprate.
   	$( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='touchpoint_assign_name_display']" ).autocomplete({
		minLength: 0,
   		source: function( request, response ) {
			response( $.ui.autocomplete.filter( assing_result, extractLast( request.term ) ) );
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

        	var labels = $(".quickadd input[name='touchpoint_assign_name_display']").val();
        	var values = $( "#touchpoint_assign_id" ).val();

        	if(labels == "") {
        		$(".quickadd input[name='touchpoint_assign_name_display']").val(selected_label);
        		$( "#touchpoint_assign_id" ).val(selected_value);
      		}
       		else {
        		$(".quickadd input[name='touchpoint_assign_name_display']").val(labels+","+selected_label);
        		$( "#touchpoint_assign_id" ).val(values+","+selected_value);
      		}
	    	return false;
		}
    })._renderItem = function( ul, item ) {// Removed .autocomplete("instance") - unnecessary and improper syntax for newer versions of jquery ui
    	return $( "<li>" ).append( "<a>" + item.label + "</a>" ).appendTo( ul );
    };

    // autocomplete for quick add touchpoint
    $( "#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dname']" ).autocomplete({
		//source: availableTags,
		minLength: 0,
		source: result,
		focus: function( event, ui ) {
	    	$( ".quickadd input[name='dname']" ).val( ui.item.label );
	    	return false;
		},
		select: function( event, ui ) {
	    	$( ".quickadd input[name='dname']" ).val( ui.item.label );
	    	$( "#dcontact_id" ).val( ui.item.value );
	    
	    	return false;
		}
    })._renderItem = function( ul, item ) {// Removed .autocomplete("instance") - unnecessary and improper syntax for newer versions of jquery ui
		return $( "<li>" ).append( "<a>" + item.label + "</a>" ).appendTo( ul );
    };

    // autocomplete for add new touchpoint
    $( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).autocomplete({
		//source: availableTags,
		minLength: 0,
		source: result,
		focus: function( event, ui ) {
	    	$( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).val( ui.item.label );
	    	return false;
		},
		select: function( event, ui ) {
	    	$( ".post-type-wp-type-activity input[name='touchpoint_contact_name']" ).val( ui.item.label );
	    	$( "#touchpoint_contact_id" ).val( ui.item.value );
	    
	    	return false;
		}
    })._renderItem = function( ul, item ) {// Removed .autocomplete("instance") - unnecessary and improper syntax for newer versions of jquery ui
		return $( "<li>" ).append( "<a>" + item.label + "</a>" ).appendTo( ul );
    };

    $('#quickAddform').on('submit', function (e) {
		e.preventDefault();
		var fd = new FormData(); 
		fd.append('action', 'quick_add_touchpoint');

		var dtype = $(this).find('select[name="dtype"]').val();
		var ddetails = $(this).find('textarea[name="ddetails"]').val();
		fd.append('dtype', dtype); 
		fd.append('ddetails', ddetails); 
	
		var arr = ["contact_id","dsubject","dsdate","dedate","dstime","detime","filename","touchpoint_assign_id"];
		$.each(arr, function( index, value ) {
	    	var datavalue = $(document).find('input[name="'+value+'"]').val();
	    	fd.append(value, datavalue);
		});

		$.ajax({
        	type: 'POST',
	    	url : ajaxurl,
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
				location.reload(true);
            }
		});

		$('#quickAddform').trigger('reset');
		$(".quickadd #filename").hide();
		$(".quickadd #touchpoint_assign_name_display").hide();
 	});

	$("#dashboard-widgets-wrap #ukuuCRM-dashboard-createactivity-widget .quickadd input[name='dupload']").click( function() {
    	var frame;

    	// If the media frame already exists, reopen it.
    	if ( frame ) {
      		frame.open();
      		return;
    	}

    	// Create a new media frame
    	frame = wp.media({
      		title: 'Attachments',
      		button: {
        		text: 'Use this media'
      		},
      		multiple: false  // Set to true to allow multiple files to be selected
    	});

    	// When an image is selected in the media frame...
    	frame.on( 'select', function() {
      		// Get media attachment details from the frame state
      		var attachment = frame.state().get('selection').first().toJSON();

      		// Send the attachment URL to our custom image input field.
      		$('.quickadd #filename').val(attachment.url).show();
    	});

    	frame.open();
	});

	$('.post-type-wp-type-activity .over-image').hide();
  	$('.post-type-wp-type-activity .inner-attachment-first').hide();
  	$(".post-type-wp-type-activity .attachment-first").hover(function(){
		var id = this.id;
		$( '.post-type-wp-type-activity #'+id+' .over-image' ).show();
		$( '.post-type-wp-type-activity #'+id+' .inner-attachment-first' ).show();
    }, function(){
		var id = this.id;
		$( '.post-type-wp-type-activity #'+id+' .over-image' ).hide();
		$( '.post-type-wp-type-activity #'+id+' .inner-attachment-first' ).hide();
    });

    $('.post-type-wp-type-activity .edit-touchpoint a').click( function() {
		$('.post-type-wp-type-activity #wpcf-group-activity-information').removeClass( 'closed' );
    });
    
    $('#wpcf-startdate_time').removeAttr( "required" );

    $("#wpcf_touchpoint_assigned_metabox .cmb-add-row-button.button").text('Add another Assignee');
    /* Date of Birth Validation */
    $('input[name="publish"]').prop('disabled', false);
    $("#wpcf-ukuu-date-of-birth").change( function() {
    	var currentDateTime = new Date();
      	if( new Date(this.value) > new Date(currentDateTime) ) {
	    	$('.invalidrange').remove();
	    	$('<div class="invalidrange">Invalid date of birth</div>').insertAfter(".cmb2-id-wpcf-ukuu-date-of-birth .cmb-td");
	    	$('.invalidrange').css('color','red');
	    	$('input[id="publish"]').prop('disabled', true);
	    }
	    else {
	    	$('.invalidrange').remove();
	    	$('input[id="publish"]').prop('disabled', false);
	  	}
  	});
});

//graph on Contact and event list
jQuery( '#graph' ).ready(function($) {
    if ($('#chart').attr('type') && $('#chart').attr('color')) {
		var type = JSON.parse($('#chart').attr('type'));
		var colorSet = JSON.parse($('#chart').attr('color'));
		var dataset = [];
		var color = [];
		var mainTotal = 0;
		color.push("#ffffff");
		$.each( colorSet , function( i, val ) {
	    	color.push(val);
	    	color.push("#ffffff");
		});
		$.each( type, function( i, val ) {
	    	mainTotal += parseInt(val);
		});
		mainTotal = mainTotal*0.001;

		var data = {};
		var numeric = [[]];
		numeric[0]['yHandler'] = mainTotal;
		data.data = numeric;
		data.name= 0;
		dataset.push(data);
		$.each( type, function( i, val ) {
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

		var dynamic_width = $("#graph").width();
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

		var svg = d3.select('#graph').append('svg').attr('width', width + margins.left + margins.right + 20 ).attr('height', height + margins.top + margins.bottom).attr('transform', 'translate(' + margins.left + ',' + margins.top + ')').style('margin-left','10')

		var xMax = d3.max(dataset, function (group) {
	    	return d3.max(group, function (d) {
				return d.x+d.x0;
	    	});
		})

		var xScale = d3.scale.linear().domain([0, xMax]).range([0, width]);

		var heightGraph = dataset[0].map(function (d) {
	    	return d.y;
		});

		var yScale = d3.scale.ordinal().domain(heightGraph).rangeRoundBands([0, height], .1);

		var colours = d3.scale.ordinal().range(color).domain([0,1,2]);

		var groups = svg.selectAll('g').data(dataset).enter().append('g').style('fill', function (d, i) {
			return colours(i);
	    });

		var text = groups.selectAll("rect").data(dataset).enter().append("text");

		var textLabels = text.attr("x", function(dataset) {
	    	var x;
	    	$.each(dataset, function(key, value) {
		    	x = value.x0;
		    	x1 = value.x;
	        });
	        var rangeBand = x/xMax*width;
	        return rangeBand+5;
		}).attr("y", "50").text( function(dataset) {
	    	var x;
	        $.each(dataset, function(key, value) {
				x = value.x;
	        });
	        if(x == 0 || x == mainTotal){
		    	//do nothing
	        }
	        else {
		    	return x;
	        }
	    }).attr("font-size", "30px").attr("font-weight", "bold").attr("fill", "#FFFFFF").style("margin-left", "5");

		var rects = groups.selectAll('rect').data(function (d) {
			return d;
	    }).enter().append('rect').attr('x', function (d) {
			return xScale(d.x0);
	    }).attr('y', function (d, i) {
			return yScale(d.y);
	    }).attr('height', function (d) {
			return yScale.rangeBand();
	    }).attr('width', function (d) {
			return xScale(d.x);
	    });

		svg.append('g').attr('class', 'x axis').attr('transform', 'translate(0,' + height + ')');

		svg.append('g').attr('class', 'y axis');
    }
});

jQuery('.post-type-wp-type-activity #post').on('submit', function (e) {
    var datavalue = jQuery('input[name="post_title"]').val();
    if ( datavalue == "" ) {
		jQuery('.errordesc').remove();
		jQuery('<div class="errordesc">Please enter valid description</div>').insertAfter("#titlewrap");
		jQuery('.errordesc').css('color','red');
		return false;
    }
    else{
		jQuery('.errordesc').remove();
		return true;
    }
});