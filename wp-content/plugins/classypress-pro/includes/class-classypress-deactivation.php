<?php

/**
* Deactivates ClassyPress
*
* @package classypress-pro
* @subpackage classypress-pro/includes
* @version 1.0.0
* @since 1.6.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ClassyPress_Deactivation')) {
  class ClassyPress_Deactivation {

    /**
    * Initialize plugin class
    */
    public function __construct() {
      $this->deactivate_classypress();
    }

    /**
     * Remove ClassyPress Templates from WP Template Cache
     *
     * @return null
     * @since 1.6.0
     */
    private function deactivate_classypress() {
      require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-templates.php');
      $cpt = ClassyPress_Templates::get_instance();
      $cpt->remove_mittun_templates();
    }
  }
}
