<?php
/*
Plugin Name: ClassyPress PRO by Mittun
Plugin URI:  https://www.mittun.com/classypress/
Description: The easiest way to integrate your Classy campaigns and collect donations with WordPress. Simple to use and easy to set up. Quickly display progress bars, embedded donation forms, popup donation forms, event listings, activity feeds, leaderboards, top fundraisers and more! If you need help setting up the plugin, please ask. Someone from our support team will happily help you. Made with love by your new friends at Mittun. (v1.6.3 on 9-26-2018)
Version:     1.6.3
Author:      Mittun
Author URI:  https://www.mittun.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: mittun_classy
*/

define('MITTUN_CLASSY_PATH',plugin_dir_path( __FILE__ ));
define('MITTUN_CLASSY_URL',plugins_url( '',__FILE__));
define('MITTUN_CLASSY_PLUGIN_BASE',plugin_basename(__FILE__));

/**
 * Activate ClassyPress
 */
register_activation_hook(__FILE__, 'mittun_classy_activation');
function mittun_classy_activation() {
  require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-activation.php');
  new ClassyPress_Activation;
}

/**
 * Deactivate ClassyPress
 */
register_deactivation_hook(__FILE__, 'mittun_classy_deactivation');
function mittun_classy_deactivation() {
  require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-deactivation.php');
  new ClassyPress_Deactivation;
}

//Includes files
require_once(MITTUN_CLASSY_PATH . '/includes/class-classypress-init.php');
?>
