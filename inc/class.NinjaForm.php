<?php

if(!defined('WPINC')) // MUST have WordPress.
    exit('Do NOT access this file directly: '.basename(__FILE__));

/**
 * NinjaFormWPS2 extends NF_Notification_Base_Type
 */
class NinjaFormWPS2
{

  private static $the_session;

  function __construct()
  {

    //add_action('wp_footer', array($this, 'test_transient'));

    add_action('init', array($this, 'set_session'));
    add_action('wp_logout', array('destroy_session'));
    add_action('wp_login', array('destroy_session'));
    //add_action ( 's2_stripe_post_process', array($this, 's2_stripe_onsite_attach_func'), 20);


  }

  public function destroy_session() {

    session_destroy ();

  }

  public function set_session() {

    if(!session_id()) {
        session_start();
    }


    //file_put_contents("rty67.txt", "dfgfdg");

  }

  public function test_transient() {

    ///$data = 'a:5:{s:7:"form_id";s:1:"3";s:8:"settings";a:18:{s:10:"objectType";s:12:"Form%20Setting";s:10:"editActive";b:1;s:5:"title";s:12:"testRegister";s:10:"show_title";i:1;s:14:"clear_complete";i:1;s:13:"hide_complete";i:1;s:17:"default_label_pos";s:5:"above";s:13:"wrapper_class";s:0:"";s:13:"element_class";s:0:"";s:3:"key";s:0:"";s:10:"add_submit";i:1;s:8:"currency";s:0:"";s:9:"logged_in";b:0;s:17:"not_logged_in_msg";s:0:"";s:16:"sub_limit_number";N;s:13:"sub_limit_msg";s:0:"";s:12:"calculations";a:0:{}s:15:"formContentData";a:3:{i:0;s:23:"firstname_1480023542515";i:1;s:19:"email_1480023454454";i:2;s:20:"submit_1480023501320";}}s:5:"extra";a:0:{}s:6:"fields";a:3:{i:21;a:10:{s:8:"settings";a:9:{s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:1;s:5:"label";s:10:"First%20Name";s:4:"type";s:9:"firstname";s:3:"key";s:23:"firstname_1480023542515";s:9:"label_pos";s:7:"default";s:5:"value";s:7:"fdfdfdf";s:2:"id";i:21;}s:2:"id";i:21;s:5:"value";s:7:"fdfdfdf";s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:1;s:5:"label";s:10:"First%20Name";s:4:"type";s:9:"firstname";s:3:"key";s:23:"firstname_1480023542515";s:9:"label_pos";s:7:"default";}i:19;a:11:{s:8:"settings";a:10:{s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:2;s:4:"type";s:5:"email";s:5:"label";s:5:"Email";s:3:"key";s:19:"email_1480023454454";s:9:"label_pos";s:7:"default";s:10:"manual_key";b:1;s:5:"value";s:20:"fdgdfggd@dfsgdfg.net";s:2:"id";i:19;}s:2:"id";i:19;s:5:"value";s:20:"fdgdfggd@dfsgdfg.net";s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:2;s:4:"type";s:5:"email";s:5:"label";s:5:"Email";s:3:"key";s:19:"email_1480023454454";s:9:"label_pos";s:7:"default";s:10:"manual_key";b:1;}i:20;a:10:{s:8:"settings";a:9:{s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:3;s:4:"type";s:6:"submit";s:5:"label";s:6:"Submit";s:16:"processing_label";s:10:"Processing";s:3:"key";s:20:"submit_1480023501320";s:5:"value";s:0:"";s:2:"id";i:20;}s:2:"id";i:20;s:5:"value";s:0:"";s:10:"objectType";s:5:"Field";s:12:"objectDomain";s:6:"fields";s:5:"order";i:3;s:4:"type";s:6:"submit";s:5:"label";s:6:"Submit";s:16:"processing_label";s:10:"Processing";s:3:"key";s:20:"submit_1480023501320";}}s:17:"processed_actions";a:0:{}}';

    $user = new WP_User((int) 4);
    d($user);

    $user = new WP_User((int) 9);
    $user->set_role("s2member_level1");

    d($user);

  }

  public function s2_stripe_onsite_attach_func($form_data = "") {

    //global $ninja_forms_processing;
    //$ninja_forms_processing['sub_id'] = $ninja_forms_processing->get_form_setting( 'sub_id' );
    //file_put_contents(s2_stripe_onsite_PLUGIN_DIR."test_onsite_3.txt", maybe_serialize($ninja_forms_processing->get_form_setting( 'sub_id' )));
    //file_put_contents("test450.txt", maybe_serialize($ninja_forms_processing));
    //$_SESSION['form_data'] = maybe_serialize($ninja_forms_processing);

    //$all_fields = $ninja_forms_processing->get_all_fields();

      file_put_contents("test410.txt", maybe_serialize($form_data));
  }


}

$nf = new NinjaFormWPS2();

?>
