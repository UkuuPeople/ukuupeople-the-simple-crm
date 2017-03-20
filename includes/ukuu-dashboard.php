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
function ukuupeople_dashboard_init() {
	global $ukuupeople_dashboard_page;
	add_action( 'load-' . $ukuupeople_dashboard_page, 'ukuupeople_dashboard_check_feed' );
}

add_action( 'admin_menu', 'admin_menus' );
add_action( 'admin_head', 'welcome_screen_remove_menus' );

function admin_menus() {
  global $ukuupeople_db_version;
  add_dashboard_page(
    'Welcome to UkuuPeople',
    'Welcome to UkuuPeople',
    'manage_options',
    'ukuu-about',
    'ukuupeople_about_page'
  );

  add_dashboard_page(
    'Get started',
    'Get started',
    'manage_options',
    'ukuu-get-started',
    'ukuupeople_get_started_page'
  );

  add_dashboard_page(
    'Add ons',
    'Add ons',
    'manage_options',
    'ukuu-add-ons',
    'ukuupeople_addons_page'
  );
}

function welcome_screen_remove_menus() {
  remove_submenu_page( 'index.php', 'ukuu-about' );
  remove_submenu_page( 'index.php', 'ukuu-get-started' );
  remove_submenu_page( 'index.php', 'ukuu-add-ons' );
}

function ukuupeople_dashboard_page() {
  $ukuupeople = UKUUPEOPLE_RELPATH. '/images/ukuupeople_logo.jpg';
  ?>
 <div class = "ukuu-dahsboard-top">
	  <div class = "ukuu-dahsboard-left">
      <div class = "ukuu-dahsboard-left-inside">
     <p><?php
     global $ukuupeople_db_version; ?>
 			<h1 class = "ukuu-welcome"><?php echo "Welcome to UkuuPeople ".$ukuupeople_db_version ?></h1>
			<?php ukuu_social_media_elements() ?>
    </p>

      <p class = 'ukuu-about'><?php
      printf(
        __( 'Thank you for activating or updating to the latest version of UkuuPeople If you\'re a first time user, welcome! You\'re well on your way to bringing your people together. We encourage you to check out the plugin documentation. Also,check out the Get started guide below.', 'UkuuPeople' )
      );
  ?>
  </p>
        <p class="ukuu-newsletter-intro"><?php esc_html_e( 'Be sure to sign up for the Give newsletter below to stay informed of important updates and news.', 'UkuuPeople' ); ?></p>
    </div> <!-- ukuu-dahsboard-left-inside -->
      <?php ukuu_dashboard_subscribe(); ?>
      </div> <!-- ukuu-dahsboard-left -->

      <div class = "ukuu-dahsboard-right">
      <img src="<?php echo $ukuupeople ?>" alt="<?php esc_attr_e( 'UkuuPeople', 'UkuuPeople' ); ?>" width = 50% height = 50% >
      </div> <!-- ukuu-dahsboard-right -->
    </div> <!-- ukuu-dahsboard-left -->

      <div class = "ukuu-dahsboard-bottom">
        <div class = "ukuu-dahsboard-bottom-tab">
          <?php tabs(); ?>
        </div> <!-- ukuu-dahsboard-bottom-tab -->

      <div class = "ukuu-dahsboard-bottom-right">
      <?php
 }

 function ukuupeople_about_page() {
    $cafe_mockup = UKUUPEOPLE_RELPATH. '/images/cafe_mockup_ukuu_people.jpg';
    $close_up_support_circle = UKUUPEOPLE_RELPATH. '/images/close_up_ukuu_people_support_circle.jpg';
		?>
          <?php ukuupeople_dashboard_page(); ?>
          <div class = "ukuu-cafe-mockup">
            <div class = "div-cafe-mockup">
          <img src="<?php echo $cafe_mockup ?>" alt="<?php esc_attr_e( 'cafe_mockup', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>

          <div class = "div-about-content">
            <h2><?php esc_html_e( 'UkuuPeople -  Bring Your People Together Without Complicated Software', 'UkuuPeople' ); ?></h2>

            <p class = "ukuu-content-p"><?php esc_html_e( 'UkuuPeople helps you elegently manage all your human relationships. UkuuPeople is the easiets CRM tool for Wordpress. The simple CRM effortlessly ties all of your contact interactions and contact data collection tool together. Now you can form one authorative master list of allyour contacts and a record of your interactions with them.', 'UkuuPeople' ); ?></p>
            <a class="button-secondary" target="_blank" title="Google Apps" href="#">
              Learn More
          </a>
          </div>
        </div>

         <div class = "ukuu-support-circle">
          <div class = "div-cafe-mockup">
          <img src="<?php echo $close_up_support_circle ?>" alt="<?php esc_attr_e( 'close_up_support_circle', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>

          <div class = "div-about-content">
            <h2><?php esc_html_e( 'Getting to Know UkuuPeople', 'UkuuPeople' ); ?></h2>
          <p class = "ukuu-content-p"><?php esc_html_e( 'Before you get started with UkuuPeople, we suggest you take a look at the online documentation. There you will find tutorials which will help you get up and running quickly. If you have any question,issue, or bug with the UkuuPeople plugin, Please submit the problem on the UkuuPeople support site by clicking the red Ask a Question button. We also welcome your feedback and feature requests. Welcome to UkuuPeople. We wish you much success with your people.', 'UkuuPeople' ); ?></p>
          <a class="button-secondary" target="_blank" title="Google Apps" href="#">
              Documentation
          </a>
      </div>
        </div>
      </div>

	<?php }

  function ukuupeople_get_started_page() {
    $step1 = UKUUPEOPLE_RELPATH. '/images/step_1_dashboards.gif';
    $step2 = UKUUPEOPLE_RELPATH. '/images/step_2_people.gif';
    $step3 = UKUUPEOPLE_RELPATH. '/images/step_3_touchpoints.gif';
    $step4 = UKUUPEOPLE_RELPATH. '/images/step_4_and_more.png';
    ?>
    <?php ukuupeople_dashboard_page(); ?>
      <h3 class = "ukuu-get-h3"><?php esc_html_e( 'Getting started with UkuuPeople is easy! We put together this quick start guide to help first time users of the plugin. Our  goal is to get up and running in no time. Lets begin!', 'UkuuPeople' ); ?></h3>

      <div class = "ukuu-step-1">
        <h3><?php esc_html_e( 'STEP 1 - DASHBOARD', 'UkuuPeople' ); ?></h3>
  			<div class = "ukuu-get-start-img">
          <img src="<?php echo $step1 ?>" alt="<?php esc_attr_e( 'Step1', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>
				<p class = "p-ukuu-get-started"><?php esc_html_e( 'UkuuPeople is divided into major categories, for tracking People, Touchpoints, and more. Each category has its own Dashboard where you can input and sort data. So take a look at the menu and...', 'UkuuPeople' ); ?></p>
      </div>

      <div class = "ukuu-step-2">
        <h3><?php esc_html_e( 'STEP 2 - PEOPLE', 'UkuuPeople' ); ?></h3>
          <div class = "ukuu-get-start-img">
          <img src="<?php echo $step2 ?>" alt="<?php esc_attr_e( 'Step2', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>
				<p class = "p-ukuu-get-started">
          <?php echo  '... open up the People Dahsboard. <br>Here you can <i><b>Add New</b></i> <br>Choose to add either a <b>Human</b> or an <b>Organization.</b> Enter your desired contact information. (Every field with an asterisk is required)<br> And tap <i><b>Create!</b></i>'; ?></p>
      </div>

      <div class = "ukuu-step-3">
        <h3><?php esc_html_e( 'STEP 3 - TOUCHPOINTS', 'UkuuPeople' ); ?></h3>
          <div class = "ukuu-get-start-img">
      			<img src="<?php echo $step3 ?>" alt="<?php esc_attr_e( 'Step3', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>

					<p class = "p-ukuu-get-started">
            <?php echo 'Now that your contact record is created, you can click on <i><b>Add Touchpoints</b></i> at the top of <br>the record to start adding interactions with this perosn. <br>Decide what type of touchpoint this is by selecting from the drop-down menu. <br>Briefly describe it. <br>Add a date And any other information you choose. <br>Tap <i><b>Create</b></i>, and you schedule your first interaction with this person'; ?></p>
      </div>

     <div class = "ukuu-step-4">
        <h3><?php esc_html_e( 'STEP 4 - AND MORE', 'UkuuPeople' ); ?></h3>
          <div class = "ukuu-get-start-img">
            <img src="<?php echo $step4 ?>" alt="<?php esc_attr_e( 'Step4', 'UkuuPeople' ); ?>" height = 50% width = 50%>
          </div>

					<p class = "p-ukuu-get-started">
            <?php echo 'Its so basic and simple,yes. And you\'ve already made an excellent start! but there\'s so much more you can do. Learn to categorize usign Tribes and Tags and Relationships. Find out how to create additional Touchpoints Types. And add even more functionality with our Add-ons <br>To learn more, <a href= "#">visit our support pages.</a>'; ?></p>
     </div>
    </div>
  <?php }


 function ukuupeople_addons_page() {
   $gravityform = UKUUPEOPLE_RELPATH. '/images/gravity-forms-logo.png';
   $give = UKUUPEOPLE_RELPATH. '/images/give-wp-logo.png';
   $mailchimp = UKUUPEOPLE_RELPATH. '/images/mailchimp.png';
   $opportunity = UKUUPEOPLE_RELPATH. '/images/opportunity_management.png';
   $import = UKUUPEOPLE_RELPATH. '/images/ukuucsvimport.png';
   ?>
     <?php ukuupeople_dashboard_page(); ?>
       <h3 class = "ukuu-addon-h3"><?php esc_html_e( 'Extend the awesome with UkuuPeople\'s add-ons!', 'UkuuPeople' ); ?></h3>
      <div class = "ukuu-add-on-img-main">
        <div class = "ukuu-add-on-img">
          <img src="<?php echo $gravityform ?>" alt="<?php esc_attr_e( 'Gravity form', 'UkuuPeople' ); ?>" height = 30% width = 30%>
          <h4 class ="add-on-text">GRAVITY FORMS</h4>
        </div>

      <div class = "ukuu-add-on-img">
          <img src="<?php echo $give ?>" alt="<?php esc_attr_e( 'Give', 'UkuuPeople' ); ?>" height = 30% width = 30%>
          <h4 class ="add-on-text">GIVE (DONATION PAGES)</h4>
      </div>

      <div class = "ukuu-add-on-img">
          <img src="<?php echo $mailchimp ?>" alt="<?php esc_attr_e( 'Mainchimp', 'UkuuPeople' ); ?>" height = 30% width = 30%>
          <h4 class ="add-on-text">MAILCHIMP</h4>
      </div>

      <div class = "ukuu-add-on-img">
          <img src="<?php echo $opportunity ?>" alt="<?php esc_attr_e( 'opportunity', 'UkuuPeople' ); ?>" height = 30% width = 30%>
          <h4 class ="add-on-text">OPPORTUNITY MANAGER</h4>
      </div>

      <div class = "ukuu-add-on-img">
          <img src="<?php echo $import ?>" alt="<?php esc_attr_e( 'Import', 'UkuuPeople' ); ?>" height = 30% width = 30%>
          <h4 class ="add-on-text">UKUU CSV IMPORT</h4>
      </div>
    </div>

    <div class = "ukuu-add-on-button">
        <h2><a href=" http://ukuupeople.com/add-ons/" class="button-primary ukuupeople-view-addons-all" title="<?php _e( 'Browse All Extensions', 'UkuuPeople' ); ?>" target="_blank"><?php _e( 'View All Add-ons', 'UkuuPeople' ); ?> </a> </h2>
    </div>
  </div>
  <?php }

function ukuu_dashboard_subscribe() {
?>
  <div id="ukuu-subscription-status"> </div>
  <form action="" method="post" id="mc-embedded-subscribe-form-ukuu" name="mc-embedded-subscribe-form-ukuu" class="validate" target="" novalidate>
			<div class="form-table ukuu-dashboard-subscribe-form">
					<div class = "div-subscribe-email">
						<input type="text" name="EMAIL" id="mce-EMAIL" placeholder="<?php esc_attr_e( 'Email Address (required)', 'UkuuPeople' ); ?>" class="required email" value="" required>
					</div>
					<div class = "div-subscribe-fname">
						<input type="text" name="FNAME" id="mce-FNAME" placeholder="<?php esc_attr_e( 'First Name', 'UkuuPeople' ); ?>" class="" value="">
					</div>
					<div class = "div-subscribe-lname">
						<input type="text" name="LNAME" id="mce-LNAME" placeholder="<?php esc_attr_e( 'Last Name', 'UkuuPeople' ); ?>" class="" value="">
					</div>
					<div class = "div-subscribe-button">
						<input type="submit" name="subscribe_btn" id="mc-embedded-subscribe" class="button" value="<?php esc_attr_e( 'Subscribe', 'UkuuPeople' ); ?>">
					</div>
			</div>
    </form>

  <?php
     wp_enqueue_script( 'ukuucrm', UKUUPEOPLE_RELPATH.'/script/ukuucrm.js' , array() );
     if( !empty($_POST) ) {
      if( isset($_POST['subscribe_btn'])) {
        $to = "help@ukuupeople.com";
        $from = $_POST["EMAIL"];
        $message = "
         Following are the user details
         First Name: ".$_POST["FNAME"]."
         Last Name: ".$_POST["LNAME"]."
         Email Id: ".$_POST["EMAIL"];

        $email_result = wp_mail( $to, "User signup for ukuupeople", $message, "From: {$from} <{$from}>");
        if ($email_result == 1){
            echo "<script type='text/javascript'>
                jQuery(document).ready(function(){
                  jQuery('#ukuu-subscription-status').html('Thanks for Subscribing!');
                  jQuery('#ukuu-subscription-status').css('color', 'green');
                  jQuery('#mc-embedded-subscribe-form-ukuu').hide();
                });
            </script>";
          }
        }
    }
}

function tabs() {
  $selected = isset( $_GET['page'] ) ? $_GET['page'] : 'ukuu-about'; ?>
    <h2 class="nav-tab-wrapper">
       <a class="nav-tab <?php echo $selected == 'ukuu-about' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ukuu-about' ), 'index.php' ) ) ); ?>">
       <?php esc_html_e( 'About UkuuPeople', 'UkuuPeople' ); ?>
       </a>

       <a class="nav-tab <?php echo $selected == 'ukuu-get-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ukuu-get-started' ), 'index.php' ) ) ); ?>">
       <?php esc_html_e( 'Get Started', 'UkuuPeople' ); ?>
       </a>

       <a class="nav-tab <?php echo $selected == 'ukuu-add-ons' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'ukuu-add-ons' ), 'index.php' ) ) ); ?>">
       <?php esc_html_e( 'Add-ons', 'UkuuPeople' ); ?>
       </a>
       </h2>
       <?php
 }

function ukuu_social_media_elements() {
  ?>
	<div class="ukuu-social-items-wrap">
		<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fwpgive&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=220596284639969" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>
		<a href="https://twitter.com/ukuupeople" class="twitter-follow-button" data-show-count="false"><?php
    printf(
      /* translators: %s: Ukuupeople twitter user @ukuupeople */
      esc_html_e( 'Follow', 'UkuuPeople' ),
      ''
    );
  ?></a>
  <script>!function (d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
    if (!d.getElementById(id)) {
      js = d.createElement(s);
      js.id = id;
      js.src = p + '://platform.twitter.com/widgets.js';
      fjs.parentNode.insertBefore(js, fjs);
    }
  }(document, 'script', 'twitter-wjs');
  </script>
      </div>
      <!--/.social-items-wrap -->
 <?php
  }
