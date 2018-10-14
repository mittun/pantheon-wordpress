<?php

/**
* Activate ClassyPress
*
* Runs on Plugin Activation
* Sets default ClassyPress settings
*
* @package classypress-pro
* @subpackage classypress-pro/includes
* @version 1.0.0
* @since 1.6.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ClassyPress_Activation')) {
  class ClassyPress_Activation {

    /**
    * Initialize plugin class
    */
    public function __construct() {
      $this->set_default_options();
      set_transient( 'mittun_classy_welcome_screen_activation_redirect', true, 30 );
    }

    /**
     * Set default ClassyPress Options
     *
     * @return null
     * @since 1.6.0
     */
    private function set_default_options() {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      $color_option = get_option('mittun_classy_color');
      if(empty($color_option)) {
        $default_color_options = array(
          'skin'=>'skin_1',
          'progress_bar_style'=>'style_1',
          'heading_color'=>'#000000',
          'intro_text_color'=>'#000000',
          'progress_bar_color'=>'#28aded',
          'progress_bar_text_color'=>'#ffffff',
          'progress_bar_marker_color'=>'#28aded',
          'primary_btn_text_color'=>'#ffffff',
          'primary_btn_bg_color'=>'#0ca8d5',
          'goal_amount_text_color'=>'#0ca8d5',
          'amount_raised_text_color'=>'#0ca8d5',
          'amount_btn_text_color'=>'#ffffff',
          'amount_btn_bg_color'=>'#0ca8d5',
          'active_amount_btn_bg_color'=>'#000000',
          'payment_btn_text_color'=>'#ffffff',
          'payment_btn_bg_color'=>'#0ca8d5',
          'payment_active_btn_bg_color'=>'#000000',
          'submit_btn_text_color'=>'#ffffff',
          'submit_btn_bg_color'=>'#0ca8d5',
        );

        update_option('mittun_classy_color',$default_color_options);
      }

      $advanced_option = get_option('mittun_classy_advanced');

      if(empty($advanced_option)) {
        $default_advanced_options = array(
          'checkout_url_type' => 'default',
        );
        update_option('mittun_classy_advanced', $default_advanced_options);
      }
    }

  }
}
