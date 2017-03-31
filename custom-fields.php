<?php
$prefix = 'wpcf-';
$customterm = array(
  'fields' =>array(
    'first-name' => array(
      'name' => __( 'First Name*', 'UkuuPeople' ),
      'desc' => __( '',  'UkuuPeople' ),
      'id'   => $prefix . 'first-name',
      'type' => 'text_medium',
      'attributes'  => array(
        'required'    => 'required',
      ),
    ) ,
    'last-name' =>array(
      'name' => __( 'Last Name*', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'last-name',
      'type' => 'text_medium',
      'attributes'  => array(
        'required'    => 'required',
      ),
    ),
    'display-name' => array(
      'name' => __( 'Display Name', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'display-name',
      'type' => 'text_medium',
    ) ,
    'email' => array(
      'name' => __( 'Email*', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'email',
      'type' => 'text_email',
      'attributes'  => array(
        'required'    => 'required',
      ),
    ),
    'phone' => array(
      'name' => __( 'Phone', 'UkuuPeople' ),
      'desc' => __( '',  'UkuuPeople' ),
      'id'   => $prefix . 'phone',
      'type' => 'text_medium',
    ) ,
    'mobile' => array(
      'name' => __( 'Mobile', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'mobile',
      'type' => 'text_medium',
    ) ,
    'website' =>array(
      'name' => __( 'Website', 'UkuuPeople' ),
      'desc' => __( '',  'UkuuPeople' ),
      'id'   => $prefix . 'website',
      'type' => 'text_url',
    ) ,
    'contactimage' => array(
      'name' => __( 'Contact Image', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'contactimage',
      'type' => 'file',
    ) ,
    'ukuu-job-title' => array(
      'name' => __( 'Job Title', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'ukuu-job-title',
      'type' => 'text_medium',
    ) ,
    'ukuu-twitter-handle' => array(
      'name' => __( 'Twitter Handle', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'ukuu-twitter-handle',
      'type' => 'text_medium',
      'attributes'  => array(
        'placeholder' => __( '@sampleteitterhandle', 'UkuuPeople' )
      ),
    ) ,
    'ukuu-facebook-url' => array(
      'name' => __( 'Facebook Username', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'ukuu-facebook-url',
      'type' => 'text_medium',
      'attributes'  => array(
        'placeholder' => __( 'samplefacebookusername', 'UkuuPeople' )
      ),
    ),
    'ukuu-date-of-birth' => array(
      'name' => __( 'Date Of Birth', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'ukuu-date-of-birth',
      'type' => 'text_date',
    ),
    'streetaddress' => array(
      'name' => __( 'Street Address', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'streetaddress',
      'type' => 'text_medium',
    ) ,
    'streetaddress2' => array(
      'name' => __( 'Street Address Line 2', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'streetaddress2',
      'type' => 'text_medium',
    ),
    'city' => array(
      'name' => __( 'City', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'city',
      'type' => 'text_medium',
    ) ,
    'postalcode' => array(
      'name' => __( 'Postal Code', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'postalcode',
      'type' => 'text_medium',
    ) ,
    'country' => array(
      'name' => __( 'Country', 'UkuuPeople' ),
      'desc' => __( '',  'UkuuPeople' ),
      'id'   => $prefix . 'country',
      'type' => 'text_medium',
    ) ,
    'state' => array(
      'name' => __( 'State', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'state',
      'type' => 'text_medium',
    ) ,
    'privacy-settings' => array(
      'name'    => __( 'Preferred Contact Method', 'UkuuPeople' ),
      'desc'    => __( '', 'UkuuPeople' ),
      'id'      => $prefix . 'privacy-settings',
      'type'    => 'multicheck',
      // 'multiple' => true, // Store values in individual rows
      'options' => array(
        'do_not_phone' => __( 'Phone', 'UkuuPeople' ),
        'do_not_email' => __( 'Email', 'UkuuPeople' ),
        'do_not_sms' => __( 'SMS', 'UkuuPeople' ),
      ),
      // 'inline'  => true, // Toggles display to inline
    ),
    'bulk-mailings' => array(
      'name'    => __( 'Bulk Mailings', 'UkuuPeople' ),
      'desc'    => __( '', 'UkuuPeople' ),
      'id'      => $prefix . 'bulk-mailings',
      'type'    => 'multicheck',
      // 'multiple' => true, // Store values in individual rows
      'options' => array(
        'opt_out' => __( 'Opt Out', 'UkuuPeople' ),
      ),
      // 'inline'  => true, // Toggles display to inline
    ) ,
    'startdate' => array(
      'name' => __( 'Start Date*', 'UkuuPeople' ),
      'desc' => __( 'Enter the Start Date', 'UkuuPeople' ),
      'id'   => $prefix . 'startdate',
      'type' => 'text_datetime_timestamp',
      'attributes'  => array(
        'required'    => 'required',
      ),
    ) ,
    'enddate' => array(
      'name' => __( 'End Date', 'UkuuPeople' ),
      'desc' => __( 'Enter the End Date', 'UkuuPeople' ),
      'id'   => $prefix . 'enddate',
      'type' => 'text_datetime_timestamp',
      'attributes'  => array(
      ),
    ),
    'status' => array(
      'name'             => __( 'Status*', 'UkuuPeople' ),
      'desc'             => __( 'Status of TouchPoint', 'UkuuPeople' ),
      'id'               => $prefix . 'status',
      'type'             => 'select',
      'attributes'  => array(
        'required'    => 'required',
      ),
      'options'          => array(
        'scheduled' => __( 'Scheduled', 'UkuuPeople' ),
        'completed'   => __( 'Completed','UkuuPeople' ),
        'cancel'     => __( 'Cancel', 'UkuuPeople' ),
      ),
    ) ,
    'details' => array(
      'name' => __( 'Details', 'UkuuPeople' ),
      'desc' => __( '', 'UkuuPeople' ),
      'id'   => $prefix . 'details',
      'type' => 'textarea_small',
    ) ,
    'attachments' => array(
      'name' => __( 'Attachments', 'UkuuPeople' ),
      'desc' => __( 'Upload a file or enter a URL.', 'UkuuPeople' ),
      'id'   => $prefix . 'attachments',
      'type' => 'file_list',
      'preview_size' => array( 100, 100 ),
    ),
  )
);
