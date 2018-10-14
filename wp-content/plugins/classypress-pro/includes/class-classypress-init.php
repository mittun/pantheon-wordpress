<?php

/**
* Initialize ClassyPress
*
* Sets global ClassyPress variables
* Loads in required files
*
* @package classypress-pro
* @subpackage classypress-pro/includes
* @version 1.6.0
* @since 1.6.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ClassyPress_Init')) {
  class ClassyPress_Init {

    /**
     * Plugin Localization/Slug
     * @var string
     */
    public static $plugin_locale;

    /**
     * Initialize plugin class
     */
    public function __construct() {
      self::$plugin_locale = 'mittun_classy';

      $this->include_classypress_files();
      add_action('plugins_loaded', array('ClassyPress_Templates', 'get_instance'));
    }

    /**
     * Include ClassyPress Files
     *
     * @return null
     * @since 1.6.0
     */
    private function include_classypress_files() {
      //Includes files
      require_once(MITTUN_CLASSY_PATH . '/includes/functions.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/admin.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/post_types.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/shortcode.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/widget.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/update.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-campaigns.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-templates.php');
      require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-transients.php');
    }
  }

  new ClassyPress_Init();
}
