<?php
use \Stripe\Stripe as Stripe;
use \Stripe\Charge as Charge;
use \Stripe\Customer as Customer;


if(!defined('WPINC')) // MUST have WordPress.
    exit('Do NOT access this file directly: '.basename(__FILE__));
/**
 * CardProcessorS2Stripe
 */
class CardProcessorS2Stripe
{

  private $new_uid = "";

  function __construct()
  {
    add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
    add_shortcode( 'S2StripeDirect', array( $this, 'S2StripeDirect_func' ) );
    add_action('wp_footer', array($this, 'do_footer'));

    add_action( 'wp_ajax_form_submission_with_token', array($this, 'form_submission_with_token_callback') );
    add_action( 'wp_ajax_nopriv_form_submission_with_token', array($this, 'form_submission_with_token_callback') );

    add_action( 'user_register', array($this, 'new_user_register'), 10, 1 );

  }

  public function new_user_register($uid = "") {

    //update_option( 'new_user_register_id', $uid );

    $_SESSION['new_user_register_id'] = $uid;

  }

  public function form_submission_with_token_callback() {

    $form_data = (empty($_POST['form_data']) ? array() : $_POST['form_data']);
    $form_data = maybe_unserialize($form_data);
    parse_str($form_data, $form_data_array);

    $titan = TitanFramework::getInstance( 's2-stripe-onsite-options' );
    $stripe_publishable_key = $titan->getOption('stripe_secret_key');
    $stripe = new Stripe();
    $stripe::setApiKey($stripe_publishable_key);

    $status = $this->ChargeTheCard($form_data_array, $stripe, $titan);


    echo json_encode($status['message']);

    $do_not_upgrade_auth_instant = $titan->getOption('do_not_upgrade_auth_instant');

    if ($status['stat'] == 'i')
      $this->update_account($form_data_array);
    elseif ($status['stat'] == 'a') {

      if (!$do_not_upgrade_auth_instant)
        $this->update_account($form_data_array);

    }

    wp_die();
  }

  private function update_account($form_data_array = array()) {

    $user_id = (empty($_SESSION['new_user_register_id']) ? "" : $_SESSION['new_user_register_id']);


    $user = new WP_User((int) $user_id);
    $role = ((empty($form_data_array['membership_level']) ? "s2member_level0" : "s2member_level".$form_data_array['membership_level']));
    $user->set_role($role);

    $eot_time = ((empty($form_data_array['eot']) ? "" : (time() + ( (int) $form_data_array['eot'] * 86400)) ));

    if (!empty($eot_time))
      update_user_option ($user_id, "s2member_auto_eot_time", $eot_time);

  }

  private function ChargeTheCard($form_data, $stripe, $titan) {

        try {

          $user_id = (empty($_SESSION['new_user_register_id']) ? "" : $_SESSION['new_user_register_id']);

          $user_info = get_userdata((int) $user_id);

          $form_data['amount'] = bcmul($form_data['amount'], 100);

          $customer = Customer::create(array(
            "source" => $form_data['stripeToken'],
            "description" => ( empty($user_info->user_email) ? $form_data['desc'] : $user_info->user_email),
            )
          );


          $charge = Charge::create(array(
            "amount" => $form_data['amount'], // Amount in cents
            "currency" => $form_data['currency'],
            "customer" => $customer->id,
            "description" => $form_data['desc'],
            "capture" => ((empty($form_data['authorize_only']) ? true : false))
            ));
        } catch(Exception $e) {
          echo json_encode($e);
          return array('message' => $titan->getOption('stripe_error_charge_message'), 'stat' => 'e');

        }

        if (empty($form_data['authorize_only']))
          return array('message' => $titan->getOption('stripe_instant_charge_message'), 'stat' => 'i');
        else
          return array('message' => $titan->getOption('stripe_authorize_only_charge_message'), 'stat' => 'a');

  }

  private function authorizeCharge($form_data, $stripe, $titan) {


  }

  public function do_footer() {

    $stripe = new Stripe();
    $titan = TitanFramework::getInstance( 's2-stripe-onsite-options' );
    $stripe_publishable_key = $titan->getOption('stripe_secret_key');

    $stripe::setApiKey($stripe_publishable_key);

    //d($stripe);

  }

  public function enqueue_scripts() {

    wp_register_script( 's2-stripe-general-script', s2_stripe_onsite_PLUGIN_URL.'js/script_general.js', array( 'jquery' ), '', false );

    wp_localize_script( 's2-stripe-general-script', 'plugin_s2stripe', array( 'ajax_url' => admin_url('admin-ajax.php'), 'product_id' => $the_id, 'plugins_url' => $plugins_url ));

    //wp_enqueue_script( 's2-stripe-general-script' );

    wp_register_script( 's2-stripe-stripejs-script', "https://js.stripe.com/v2/", "", '' );
    //wp_enqueue_script( 's2-stripe-stripejs-script' );
    wp_register_script( 's2-stripe-stripeapi-script', s2_stripe_onsite_PLUGIN_URL.'js/script_stripeapi.js');
    wp_register_script( 's2-stripe-jquery-payment-script', s2_stripe_onsite_PLUGIN_URL.'js/jquery.payment.js');

    wp_register_style( 's2-stripe-bootstrap-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
    wp_register_style( 's2-stripe-general-style', s2_stripe_onsite_PLUGIN_URL.'css/style.css' );


  }

  public function S2StripeDirect_func( $atts = "" ) {

    $atts = shortcode_atts( array(
      'authorize_only' => 0,
      'membership_level' => 1,
      'amount' => 0.00,
      'eot' => 0,
      'currency' => "usd",
      'desc' => ""
    ), $atts, 'S2StripeDirect' );

wp_enqueue_script( 's2-stripe-stripejs-script' );

$titan = TitanFramework::getInstance( 's2-stripe-onsite-options' );
$stripe_publishable_key = $titan->getOption('stripe_publishable_key');

$stripe_publishable_key = preg_replace('/\s+/','', $stripe_publishable_key);

wp_localize_script( 's2-stripe-stripeapi-script', 'plugin_stripeapi', array( 'ajax_url' => admin_url('admin-ajax.php'), 'publishable_key' => $stripe_publishable_key));
wp_enqueue_script( 's2-stripe-stripeapi-script' );

wp_enqueue_script( 's2-stripe-jquery-payment-script' );
wp_enqueue_script( 's2-stripe-general-script' );
wp_enqueue_style( 's2-stripe-bootstrap-style' );

$this->the_form($atts, $titan);

    return;


  }

private function the_form($atts = "", $titan = "") {

  $atts = (empty($atts) ? array() : $atts);

  $session_form_data = ( empty( $_SESSION['form_data'] ) ? array() : $_SESSION['form_data']);
  $session_form_data = maybe_unserialize($session_form_data);
  $form_fields   =  (empty($session_form_data[ 'fields' ]) ? array() : $session_form_data[ 'fields' ]);
  $custom_user_data = array();

      foreach( $form_fields as $field ){
          $field_id    = $field[ 'id' ];
          $field_key   = $field[ 'key' ];
          $field_value = $field[ 'value' ];

          // Example Field Key comparison
          if( 'email' == $field[ 'key' ] || 'username' == $field[ 'key' ] || 'password' == $field[ 'key' ] || 'firstname' == $field[ 'key' ] || 'lastname' == $field[ 'key' ]){
              $custom_user_data[ $field[ 'key' ] ] = $field[ 'value' ];
          }
      }

  $custom_user_data['user_id'] = (empty($_SESSION['new_user_register_id']) ? "" : $_SESSION['new_user_register_id']);

  $warning_message = '  <div class="alert alert-danger" role="alert">
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      <span class="sr-only">Error:</span>
      '.$titan->getOption('stripe_s2_warning_message').'
    </div>';

    if ( ($titan->getOption('is_stripe_s2_warning_mode_enabled')) && (empty($custom_user_data['user_id'])) )
      _e($warning_message);

?>
<div class="the_form_stripes2">

<form id="onSitePaymentForm" novalidate autocomplete="on" method="POST">

  <span class="payment-errors"></span>

      <div class="form-group">
        <label for="cc-number" class="control-label">Card number<small class="text-muted">[<span class="cc-brand"></span>]</small></label>
        <input id="cc-number" type="tel" data-stripe="number" class="input-lg form-control cc-number" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" required>
      </div>

      <div class="form-group">
        <label for="cc-exp" class="control-label">Card expiry</label>
        <input id="cc-exp" type="tel" class="input-lg form-control cc-exp" autocomplete="cc-exp" placeholder="mm/yy" required>
      </div>

      <div class="form-group">
        <label for="cc-cvc" class="control-label">Card CVC</label>
        <input id="cc-cvc" type="tel" data-stripe="cvc" class="input-lg form-control cc-cvc" autocomplete="off" placeholder="•••" required>
      </div>

      <!--<div class="form-group">
        <label for="numeric" class="control-label">Restrict numeric</label>
        <input id="numeric" type="tel" class="input-lg form-control" data-numeric>
      </div>-->

      <?php

      foreach ($custom_user_data as $key => $value) {
        _e('<input type="hidden" name="'.$key.'" value="'.$value.'">');
      }

      foreach ($atts as $key => $value) {
        _e('<input type="hidden" name="'.$key.'" value="'.$value.'">');
      }
      ?>

      <button type="submit" autocomplete="off" class="submit btn btn-lg btn-primary">Submit</button>

      <h2 class="validation"></h2>
    </form>
  </div>

<?php


}


}

$cardProcessorS2Stripe = new CardProcessorS2Stripe();

?>
