<?php

if(!defined('WPINC')) // MUST have WordPress.
	exit('Do NOT access this file directly: '.basename(__FILE__));

//require_once( plugin_dir_path( __FILE__ ) . '/session/wp-session-manager.php' );

require_once( 'titan-framework-checker.php' );
require_once( 'titan-framework-options.php' );

require_once( plugin_dir_path( __FILE__ ) . '/inc/class.cardProcessor.php' );

if( in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
  require_once( plugin_dir_path( __FILE__ ) . '/inc/class.NinjaForm.php' );;

  require_once( plugin_dir_path( __FILE__ ) . '/stripe-php-4.2.0/init.php' );
