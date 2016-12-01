<?php

if (!defined('ABSPATH'))
  exit;


add_action( 'tf_create_options', 's2_stripe_onsite_options', 150 );

function s2_stripe_onsite_options() {


	$titan = TitanFramework::getInstance( 's2-stripe-onsite-options' );
	$section = $titan->createAdminPanel( array(
		    'name' => __( 's2Member Stripe Onsite Options', 's2-stripe-onsite' ),
		    'icon'	=> 'dashicons-feedback'
		) );

	$tab = $section->createTab( array(
    		'name' =>  __( 'Stripe API Connect', 's2-stripe-onsite' )
		) );

  $tab->createOption( array(
    'name' => 'Publishable Key',
    'id' => 'stripe_publishable_key',
    'type' => 'text',
    'desc' => 'Stripe Publishable Key'
    ) );


      $tab->createOption( array(
        'name' => 'Secret Key',
        'id' => 'stripe_secret_key',
        'type' => 'text',
        'desc' => 'Stripe Secret Key'
        ) );

    $tab->createOption( array(
    'name' => 'Enable Test Mode',
    'id' => 'is_stripe_test_mode_enabled',
    'type' => 'enable',
    'default' => false,
    'desc' => 'Enable or disable Stripe Test Mode [Publishable and Secret Key MUST be TEST key]',
    ) );

    $tab->createOption([
			'name' => 'Instant Charge Message',
			'id' => 'stripe_instant_charge_message',
			'type' => 'textarea',
			'desc' => 'Instant Charge Message',
      'default' => 'Your payment has been accepted! Please wait for account confirmation mail.'
			]);

      $tab->createOption([
  			'name' => 'Authorize Only Message',
  			'id' => 'stripe_authorize_only_charge_message',
  			'type' => 'textarea',
  			'desc' => 'Authorize Only Message',
        'default' => 'Your payment has been authorized! Please wait for next instruction.'
  			]);
      $tab->createOption([
  			'name' => 'Payment Error Message',
  			'id' => 'stripe_error_charge_message',
  			'type' => 'textarea',
  			'desc' => 'Payment Error Message',
        'default' => 'Payment Error! Please contact support.'
  			]);


        $tab->createOption( array(
        'name' => 'Enable Warning',
        'id' => 'is_stripe_s2_warning_mode_enabled',
        'type' => 'enable',
        'default' => false,
        'desc' => 'Enable or disable Warning Mode',
        ) );

        $tab->createOption([
          'name' => 'Warning Message',
          'id' => 'stripe_s2_warning_message',
          'type' => 'textarea',
          'desc' => 'Warning Message For User',
          'default' => 'No User ID found and you may need to contact support.'
          ]);

          $tab->createOption( array(
          'name' => 'Do not upgrade authorized payment accounts instantly',
          'id' => 'do_not_upgrade_auth_instant',
          'type' => 'enable',
          'default' => false,
          'desc' => 'Decide what to do with user accounts if you are just authorizing payments and want to capture later.',
          ) );

          $tab->createOption(array(
            'name' => 'Shortcode Extra For s2Member',
            'type' => 'custom',
            'custom' => '<div style="width: 100%">
Simply add your payment page url as a value of <code>success=""</code> parameter.<br>
i.e - <code>[s2Member-Pro-... success="'.get_site_url().'/my-custom-payment-page" /]</code>
       </div>'

          ));

          $tab->createOption(array(
            'name' => 'Shortcode Explanation for [S2StripeDirect]',
            'type' => 'custom',
            'custom' => '<div style="width: 100%">
<code>[S2StripeDirect amount="0.00" authorize_only="0" membership_level="1" eot="0" currency="usd"]</code><br><br>
<code>amount=""</code> = amount need to be charged/authorized. Default - 0.00.<br>
<code>authorize_only=""</code> = 1 to enable authorize only, 0 to disable. Default - 0.<br>
<code>membership_level=""</code> = membership level. Default - 1.<br>
<code>eot=""</code> = expiration time in days, 0  means lifetime. Default - 0.<br>
<code>currency=""</code> = preferred currency. Default - usd.<br>

       </div>'

          ));
		//$tab->createOption(['name' => 'Color Name | Hexadecimal Value', 'id' => 'color_list_opt', 'type' => 'textarea', 'desc' => 'Color Name | Hexadecimal Value', 'default' => ""]);

	/*			$tab = $section->createTab( array(
    		'name' => 'Product Field Options'
		) );

		$tab->createOption([
			'name' => 'Availibility Options',
			'id' => 'availability_opts',
			'type' => 'textarea',
			'desc' => 'Availibility Value|Availibility Title'
			]);

		$tab->createOption([
			'name' => 'Durations',
			'id' => 'var_price_opts',
			'type' => 'textarea',
			'desc' => 'Default Durations'
			]);


*/
		$section->createOption( array(
  			  'type' => 'save',
		) );


		/////////////New

/*		$embroidery_sub = $section->createAdminPanel(array('name' => 'Embroidering Pricing'));


		$embroidery_tab = $embroidery_sub->createTab( array(
    		'name' => 'Profiles'
		) );


		$wp_expert_custom_options['embroidery_tab'] = $embroidery_tab;

		return $wp_expert_custom_options;
*/
}


 ?>
