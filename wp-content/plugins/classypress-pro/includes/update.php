<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_update{

  var $remote_url;
  var $plugin_folder = '';
  var $plugin_file = '';

  function __construct() {
    $this->plugin_folder = dirname(MITTUN_CLASSY_PLUGIN_BASE);
    $this->plugin_file = basename(MITTUN_CLASSY_PLUGIN_BASE);
    $this->remote_url = (is_ssl() ? 'https://' : 'http://') . 'downloads.mittun.com/plugins/classy/index.php';

    if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE) {
      $disable_autoupdate = mittun_classy_get_option('disable_autoupdate','mittun_classy_advanced');
      if(empty($disable_autoupdate)){
        add_action('admin_init', array($this,'mittun_classy_check_update'), 100);
        add_filter('plugins_api', array($this,'plugin_api_call'), 10, 3);
      }
    }
  }

  function mittun_classy_check_update() {

    // get currently installed version
    if (!function_exists('get_plugin_data')) {
      require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    $plugin_data = get_plugin_data(MITTUN_CLASSY_PATH.$this->plugin_file);

    if (defined('WP_INSTALLING')) {
      return false;
    }

    // get most recent version from mittun
    $raw_response = wp_remote_post($this->remote_url, array('body'=>array('type'=>'pro')));
    $response = json_decode(wp_remote_retrieve_body($raw_response));
    if (!isset($response) || !isset($response->version)) {
      return false;
    }

    // compare versions
    if(version_compare($plugin_data['Version'], $response->version) >= 0) {
      return false;
    }

    $plugin_transient = get_site_transient('update_plugins');
    $a = array(
      'slug' => $this->plugin_folder,
      'new_version' => $response->version,
      'plugin'=>$this->plugin_folder.'/'.$this->plugin_file,
      'url' => $this->remote_url,
      'package' => $response->download_url
    );

    $o = (object) $a;
    $plugin_transient->response[$this->plugin_folder.'/'.$this->plugin_file] = $o;
    set_site_transient('update_plugins', $plugin_transient);
  }

  function plugin_api_call($def, $action, $args) {

    if (!isset($args->slug) || ($args->slug != $this->plugin_folder) || $action != 'plugin_information')
    return false;

    // Get the current version
    $plugin_info = get_site_transient('update_plugins');
    $current_version = $plugin_info->checked[$this->plugin_folder .'/'. $this->plugin_file .'.php'];
    $args->version = $current_version;

    $request = wp_remote_post($this->remote_url, array('body'=>array('type'=>'pro')));

    if (is_wp_error($request)) {
      $res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $request->get_error_message());
    } else {
      $res = json_decode($request['body']);
      $sections_arr=array();
      $banners_arr=array();
      foreach($res->sections as $key=>$value)
      {
        $sections_arr[$key]=$value;
      }
      $res->sections=$sections_arr;
      foreach($res->banners as $key=>$value)
      {
        $banners_arr[$key]=$value;
      }
      $res->banners=$banners_arr;
      if ($res === false)
      $res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['body']);
    }

    return $res;
  }
}
add_action('admin_init',function(){new mittun_classy_update();},20);
?>
