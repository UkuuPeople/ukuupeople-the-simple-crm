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
     <a target="_blank" title="UkkuPeople Mailchimp" href="https://ukuupeople.com/add-ons/basic-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Basic Bundle" src="'.$bundlebasic.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Basic Bundle" href="https://ukuupeople.com/add-ons/basic-bundle/">Basic Bundle</a>
    </h3>
    <p>This basic bundle includes all the essentials you\'ll need to get up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Basic Bundle" href="https://ukuupeople.com/add-ons/basic-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Nonprofit Plus" href="https://ukuupeople.com/add-ons/nonprofit-plus-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Nonprofit Plus Bundle" src="'.$bundlenonprofit.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Nonprofit Plus" href="https://ukuupeople.com/add-ons/nonprofit-plus-bundle/"</a>
    </h3>
    <p>The Nonprofit Plus Bundle includes everything you\'ll need to get your nonprofit organization up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Nonprofit Plus" href="https://ukuupeople.com/add-ons/nonprofit-plus-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Sales Management Bundle" href="https://ukuupeople.com/add-ons/sales-management-plus-bundle/">
      <img width="100%" height="100%" alt="UkkuPeople Sales Management Bundle" src="'.$bundlesales.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Sales Management Bundle" href="https://ukuupeople.com/add-ons/sales-management-plus-bundle/">Sales Management Bundle</a>
    </h3>
    <p>The Sales Management Bundle includes everything you’ll need to manage your sales pipeline and get up and running with UkuuPeople.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Sales Management Bundle" href="https://ukuupeople.com/add-ons/sales-management-plus-bundle/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
     <a target="_blank" title="UkkuPeople Mailchimp" href="https://ukuupeople.com/mailchimp-integration/">
      <img width="100%" height="100%" alt="UkkuPeople Mailchimp" src="'.$mailchimp.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="UkuuPeople Mailchimp" href="https://ukuupeople.com/mailchimp-integration/">Mailchimp</a>
    </h3>
    <p>MailChimp offers awesome mass mailing power. Now you can connect your people with MailChimp.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="UkuuPeople Mailchimp" href="https://ukuupeople.com/mailchimp-integration/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
          </div></div></div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Google Calendar" href="https://ukuupeople.com/add-ons/google-calendar-integration/">
      <img width="100%" height="100%" alt="Google Calendar" src="'.$googlecal.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Google Calendar" href="https://ukuupeople.com/add-ons/google-calendar-integration/">Google Calendar</a>
    </h3>
    <p>Sync your activities to your Google Calendar!</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Google Calendar" href="https://ukuupeople.com/add-ons/google-calendar-integration/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
  </div>
        </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Gravity Forms" href="https://ukuupeople.com/gravity-forms-integration/">
      <img width="100%" height="100%" alt="Gravity Forms" src="'.$gravity_forms.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Gravity Forms" href="https://ukuupeople.com/gravity-forms-integration/">Gravity Forms</a>
    </h3>
    <p>Bring your form data back into UkuuPeople in the form of People or TouchPoints.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Gravity Forms" href="https://ukuupeople.com/gravity-forms-integration/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
  </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Give" href="https://ukuupeople.com/give-integration/">
      <img width="100%" height="100%" alt="Give" src="'.$give.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Give" href="https://ukuupeople.com/give-integration/">Give (Donation Pages)</a>
    </h3>
    <p>Integrated with UkuuPeople you will be able to see a history of your giving by individual.
Sweet.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Give"href="https://ukuupeople.com/give-integration/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Import, Export, Report" href="https://ukuupeople.com/ukuupeople-import/">
      <img width="100%" height="100%" alt="CSV Import" src="'.$csvimport.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Import, Export, Report" href="https://ukuupeople.com/ukuupeople-import/">Ukuu Import, Export, Report</a>
    </h3>
    <p>The Ukuu CSV Import/Export tool makes setting up your CRM a flash! Now includes simple reporting.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Import, Export, Report" href="https://ukuupeople.com/ukuupeople-import/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
   <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Opportunity" href="https://ukuupeople.com/opportunity-management/">
      <img width="100%" height="100%" alt="Opportunity" src="'.$opportunity.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Opportunity" href="https://ukuupeople.com/opportunity-management/">Opportunity Management</a>
    </h3>
    <p>The Opportunity Management is perfect for tracking leads from a cold contact all the way through to a won opportunity.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Opportunity" href="https://ukuupeople.com/opportunity-management/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
    
       <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
      <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Reminders" href="https://ukuupeople.com/add-ons/ukuupeople-reminders/">
      <img width="100%" height="100%" alt="Opportunity" src="'.$reminders.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Reminders" href="https://ukuupeople.com/add-ons/ukuupeople-reminders/">Reminders</a>
    </h3>
    <p>Get email notifications about all the important things!</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Opportunity" href="https://ukuupeople.com/add-ons/ukuupeople-reminders/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
       </div></div>
    
    
    </div>
    </div>';
	return $output;

}
