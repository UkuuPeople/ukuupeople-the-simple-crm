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
  $googleapp = UKUUPEOPLE_RELPATH.'/images/google-apps.png';
  $give = UKUUPEOPLE_RELPATH.'/images/give-logo.png';
  $csvimport = UKUUPEOPLE_RELPATH.'/images/ukuucsvimport.png';
  $opportunity = UKUUPEOPLE_RELPATH.'/images/opportunity_management.png';
  $output = '<div class="ukuupeople-add-on-main">
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
  </div></div>
  <div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Google Apps" href=" http://shop.ukuupeople.com/add-on/google-apps/">
      <img width="100%" height="100%" alt="Google Apps" src="'.$googleapp.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Google Apps" href="http://shop.ukuupeople.com/add-on/google-apps/">Google Apps</a>
    </h3>
    <p>Sync your activities to your Google Calendar, sync your contact list to your business contact list and more.</p>
  </div>
  <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Google Apps" href="http://shop.ukuupeople.com/add-on/google-apps/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
  </div>
  </div>
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
  </div>
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
  </div>
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
</div>
<div class="ukuupeople-add-on-main">
  <div class="featured-img">
    <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-managment/">
      <img width="100%" height="100%" alt="Opportunity" src="'.$opportunity.'">
    </a>
  </div>
  <div class="addon-content">
    <h3 class="addon-heading">
      <a target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-managment/">Opportunity Managment</a>
    </h3>
    <p>The Opportunity Management is perfect for tracking leads from a cold contact all the way through to a won opportunity.</p>
   </div>
   <div class="addon-footer-wrap give-clearfix">
    <a class="button-secondary" target="_blank" title="Opportunity" href="http://shop.ukuupeople.com/add-on/opportunity-managment/">
      LEARN MORE
      <span class="dashicons dashicons-external"></span>
    </a>
   </div>
</div>';
	return $output;

}
