<?php
/**
 * Admin Add-ons
 *
 * @package     UkuuPeople
 * @since       1.0.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add-ons Page Init
 *
 */
function ukuupeople_add_ons_init() {
	global $ukuupeople_add_ons_page;
	add_action( 'load-' . $ukuupeople_add_ons_page, 'ukuupeople_add_ons_check_feed' );
}

add_action( 'admin_menu', 'ukuupeople_add_ons_init' );

/**
 * Add-ons Page
 *
 * Renders the add-ons page content.
 *
 * @since 1.0
 * @return void
 */
function ukuupeople_add_ons_page() {
	ob_start(); ?>
	<div class="wrap" id="ukuupeople-add-ons">
     <h2><?php _e( 'UkuuPeople Add-ons', 'UkuuPeople' ); ?>
     &nbsp;&mdash;&nbsp;<a href=" http://ukuupeople.com/add-ons/" class="button-primary ukuupeople-view-addons-all" title="<?php _e( 'Browse All Extensions', 'UkuuPeople' ); ?>" target="_blank"><?php _e( 'View All Add-ons', 'UkuuPeople' ); ?><span class="dashicons dashicons-external"></span></a>
		</h2>
<p><?php _e( 'Extend the awesomeness of Ukuu People.' , 'UkuuPeople' ); ?></p>
		<?php echo ukuupeople_add_ons(); ?>
	</div>
	<?php
	echo ob_get_clean();
}

/**
 * Add-ons Feed
 */
function ukuupeople_add_ons() {
  $mailchimp = UKUUPEOPLE_RELPATH. '/images/mailchimp.png';
   $gravity_forms = UKUUPEOPLE_RELPATH.'/images/gravity-forms-logo.png';
   $googlecal = UKUUPEOPLE_RELPATH.'/images/google-cal.png';
   $give = UKUUPEOPLE_RELPATH.'/images/give-logo.png';
   $csvimport = UKUUPEOPLE_RELPATH.'/images/ukuucsvimport.png';
   $opportunity = UKUUPEOPLE_RELPATH.'/images/opportunity_management.png';
   $give = UKUUPEOPLE_RELPATH. '/images/give-wp-logo.png';
   $mailchimp = UKUUPEOPLE_RELPATH. '/images/mailchimp.png';
   $opportunity = UKUUPEOPLE_RELPATH. '/images/opportunity_management.png';
   $import = UKUUPEOPLE_RELPATH. '/images/ukuucsvimport.png';
   $bundlebasic = UKUUPEOPLE_RELPATH.'/images/basic_bundle.png';
   $bundlenonprofit = UKUUPEOPLE_RELPATH.'/images/nonprofit_plus_bundle.png';
   $bundlesales = UKUUPEOPLE_RELPATH.'/images/sales_management_bundle.png';
   $reminders = UKUUPEOPLE_RELPATH.'/images/reminders.png';
   $output = '<div class="ukuu-addon-wrapper">
<div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Mailchimp" href="https://shop.ukuupeople.com/add-on/basic-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Basic Bundle" src="'.$bundlebasic.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Basic Bundle" href="https://shop.ukuupeople.com/add-on/basic-bundle/">Basic Bundle</a>
    </h3>
    <p>This basic bundle includes all the essentials you\'ll need to get up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Basic Bundle" href="https://shop.ukuupeople.com/add-on/basic-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Nonprofit Plus" href="https://shop.ukuupeople.com/add-on/nonprofit-plus-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Nonprofit Plus Bundle" src="'.$bundlenonprofit.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Nonprofit Plus" href="http://shop.ukuupeople.com/add-on/mailchimp/">Nonprofit Plus</a>
    </h3>
    <p>The Nonprofit Plus Bundle includes everything you\'ll need to get your nonprofit organization up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Nonprofit Plus" href="https://shop.ukuupeople.com/add-on/nonprofit-plus-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Sales Management Bundle" href="https://shop.ukuupeople.com/add-on/sales-management-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Sales Management Bundle" src="'.$bundlesales.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Sales Management Bundle" href="https://shop.ukuupeople.com/add-on/sales-management-bundle/">Sales Management Bundle</a>
    </h3>
    <p>The Sales Management Bundle includes everything you’ll need to manage your sales pipeline and get up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Sales Management Bundle" href="https://shop.ukuupeople.com/add-on/sales-management-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Mailchimp" href="http://shop.ukuupeople.com/add-on/mailchimp/">
      <img width="100%" height="100%" alt="UkkuPeople Mailchimp" src="'.$mailchimp.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Mailchimp" href="http://shop.ukuupeople.com/add-on/mailchimp/">Mailchimp</a>
    </h3>
    <p>MailChimp offers awesome mass mailing power. Now you can connect your people with MailChimp.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Mailchimp" href="http://shop.ukuupeople.com/add-on/mailchimp/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Google Calendar" href=" http://shop.ukuupeople.com/add-on/google-apps/">
      <img width="100%" height="100%" alt="Google Calendar" src="'.$googlecal.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Google Calendar" href="http://shop.ukuupeople.com/add-on/google-apps/">Google Calendar</a>
    </h3>
    <p>Sync your activities to your Google Calendar!</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Google Apps" href="http://shop.ukuupeople.com/add-on/google-apps/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
  </div>
        </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Gravity Forms" href="http://shop.ukuupeople.com/add-on/gravity-forms/">
      <img width="100%" height="100%" alt="Gravity Forms" src="'.$gravity_forms.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Gravity Forms" href="http://shop.ukuupeople.com/add-on/gravity-forms/">Gravity Forms</a>
    </h3>
    <p>Bring your form data back into UkuuPeople in the form of People or Touchpoints.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Gravity Forms" href="http://shop.ukuupeople.com/add-on/gravity-forms/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
  </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Give" href="http://shop.ukuupeople.com/add-on/give-donation-pages/">
      <img width="100%" height="100%" alt="Give" src="'.$give.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Give" href="http://shop.ukuupeople.com/add-on/give-donation-pages/">Give (Donation Pages)</a>
    </h3>
    <p>Integrated with UkuuPeople you will be able to see a history of your giving by individual.
Sweet.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Give"href="http://shop.ukuupeople.com/add-on/give-donation-pages/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="CSV Import" href="http://shop.ukuupeople.com/add-on/ukuu-csv-import/">
      <img width="100%" height="100%" alt="CSV Import" src="'.$csvimport.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="CSV Import" href="http://shop.ukuupeople.com/add-on/ukuu-csv-import/">Ukuu CSV Import</a>
    </h3>
    <p>The Ukuu CSV Import/Export tool makes setting up your CRM a flash!</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="CSV Import" href="http://shop.ukuupeople.com/add-on/ukuu-csv-import/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-managment/">
      <img width="100%" height="100%" alt="Opportunity" src="'.$opportunity.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-management/">Opportunity Management</a>
    </h3>
    <p>The Opportunity Management is perfect for tracking leads from a cold contact all the way through to a won opportunity.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-managment/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
    
       <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/reminders/">
      <img width="100%" height="100%" alt="Opportunity" src="'.$reminders.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/reminders/">Reminders</a>
    </h3>
    <p>Get email notifications about all the important things!</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/reminders/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
    
    
    </div>
    </div>';
	return $output;

}
