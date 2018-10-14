<?php

/**
 * ClassyPress PRO Admin Screens & Navigation
 *
 * @package classypress-pro
 * @subpackage classypress-pro/includes
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_admin{

	public function __construct() {
		add_action( 'admin_menu', array($this, 'mittun_classy_admin_menu') );
		add_action( 'admin_init', array($this,'mittun_classy_admin_options_settings') );
		add_action( 'admin_init', array($this,'mittun_classy_welcome_screen_do_activation_redirect' ));
		add_action( 'admin_head', array($this,'mittun_classy_welcome_screen_remove_menus') );
		add_filter( 'plugin_action_links_' . MITTUN_CLASSY_PLUGIN_BASE, array($this,'mittun_classy_plugin_action_links') );
		add_filter('plugin_row_meta',array($this,'mittun_classy_plugin_row_meta'),10,4);

		if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE) {
			add_action( 'admin_enqueue_scripts', array($this,'mittun_classy_admin_scripts') );
		}
	}

	/**
	 * Add ClassyPress Admin Menu Links
	 */
	public function mittun_classy_admin_menu() {

		global $submenu;

		$mittun_classy_menu = add_menu_page( __('Classy Settings','mittun_classy'), __('ClassyPress','mittun_classy'), 'manage_options', 'mittun-classy',array($this,'mittun_classy_settings'),MITTUN_CLASSY_URL.'/img/donate.png');

		if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE) {
			add_submenu_page('mittun-classy', __('Campaigns','mittun_classy'), __('Campaigns','mittun_classy'), 'manage_options', 'edit.php?post_type=mittun-campaign');
			add_submenu_page('mittun-classy', __('New Campaign','mittun_classy'), __('New Campaign','mittun_classy'), 'manage_options', 'post-new.php?post_type=mittun-campaign');

			// New Campaign Page
			add_submenu_page('mittun-classy', __('New Campaign Page','mittun_classy'), __('New Campaign Page','mittun_classy'), 'manage_options', 'post-new.php?post_type=page&classypress=true');

			add_submenu_page('mittun-classy', __('Combined Campaigns','mittun_classy'), __('Combined Campaigns','mittun_classy'), 'manage_options', 'edit.php?post_type=mittun-multicampaign');
			add_submenu_page('mittun-classy', __('New Combined Campaign','mittun_classy'), __('New Combined Campaign','mittun_classy'), 'manage_options', 'post-new.php?post_type=mittun-multicampaign');
			add_submenu_page('mittun-classy', __('Donation Forms','mittun_classy'), __('Donation Forms','mittun_classy'), 'manage_options', 'edit.php?post_type=mittun-nonclassy');
			add_submenu_page('mittun-classy', __('New Donation Form','mittun_classy'), __('New Donation Form','mittun_classy'), 'manage_options', 'post-new.php?post_type=mittun-nonclassy');
			add_submenu_page('mittun-classy', __('Leaderboards','mittun_classy'), __('Leaderboards','mittun_classy'), 'manage_options', 'edit.php?post_type=mittun-leaderboard');
			add_submenu_page('mittun-classy', __('New Leaderboard','mittun_classy'), __('New Leaderboard','mittun_classy'), 'manage_options', 'post-new.php?post_type=mittun-leaderboard');

			add_submenu_page('mittun-classy', __('Event Listing','mittun_classy'), __('Event Listing','mittun_classy'), 'manage_options', 'edit.php?post_type=mittun-event');
			add_submenu_page('mittun-classy', __('New Event Listing','mittun_classy'), __('New Event Listing','mittun_classy'), 'manage_options', 'post-new.php?post_type=mittun-event');

			add_submenu_page('mittun-classy', __('Import','mittun_classy'), __('Import','mittun_classy'), 'manage_options', 'mittun-import',array($this,'mittun_classy_import'));

			$submenu['mittun-classy'][] = array(__('Plugin Support','mittun_classy'), 'manage_options','http://mittun.com/support/plugins/classy/');
			$submenu['mittun-classy'][] = array(__('Send Feedback','mittun_classy'), 'manage_options','http://mittun.com/feedback/plugins/classy/');
			$submenu['mittun-classy'][] = array(__('Inspiration','mittun_classy'), 'manage_options','http://mittun.com/inspiration/plugins/classy/');

			if(isset($submenu['mittun-classy'][0][0]))
			$submenu['mittun-classy'][0][0]=__('General Settings','mittun_classy');
		}

		add_submenu_page('mittun-classy', __('Welcome To ClassyPress','mittun_classy'), __('Welcome To ClassyPress','mittun_classy'), 'manage_options', 'mittun-classy-welcome',array($this,'mittun_classy_welcome_screen_content'));
	}

	/**
	 * Register ClassyPress Settings
	 */
	public function mittun_classy_admin_options_settings() {
		register_setting( 'mittun-classy-key', 'mittun_classy_key',array($this,'mittun_classy_validate_key') );
		register_setting( 'mittun-classy-settings', 'mittun_classy',array($this,'mittun_classy_validate_classy_settings') );
		register_setting( 'mittun-classy-color-settings', 'mittun_classy_color',array($this,'mittun_classy_validate_classy_color_settings') );
		register_setting( 'mittun-classy-advanced-settings', 'mittun_classy_advanced',array($this,'mittun_classy_validate_classy_advanced_settings') );
	}

	/**
	 * Redirect to ClassyPress Welcome Screen on Activation
	 */
	public function mittun_classy_welcome_screen_do_activation_redirect(){

	  if ( ! get_transient( 'mittun_classy_welcome_screen_activation_redirect' ) ) {
			return;
	  }
	  delete_transient( 'mittun_classy_welcome_screen_activation_redirect' );

	  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
	  }
	  wp_safe_redirect( add_query_arg( array( 'page' => 'mittun-classy-welcome' ), admin_url( 'admin.php' ) ) );
	}

	/**
	 * Add Welcome Screen Content
	 */
	public function mittun_classy_welcome_screen_content() {
		$self_dir = dirname(dirname(__FILE__));
		$plugin_data = get_plugin_data( $self_dir.'/index.php' );
		$plugin_version = $plugin_data['Version'];

		include_once(MITTUN_CLASSY_PATH . '/includes/partials/welcome-screen.php');
	}

	/**
	 * Remove Welcome Screen links from Submenu
	 */
	public function mittun_classy_welcome_screen_remove_menus() {
		remove_submenu_page( 'mittun-classy', 'mittun-classy-welcome' );
	}

	/**
	 * Create ClassyPress Tabs
	 * @param  string $current The current tab we are on
	 * @return string          HTML Markup for Tabs
	 */
	public function mittun_classy_admin_menu_tabs( $current = 'general' ) {
		$tabs = array( 'classy' => __('Classy Settings','mittun_classy'),'color' => __('Global Styles','mittun_classy'),'advanced' => __('Advanced','mittun_classy') );
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name ){
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='?page=mittun-classy&tab=$tab'>$name</a>";
		}
		echo '</h2>';
	}

	/**
	 * Create the Settings page
	 */
	public function mittun_classy_settings() {
		include_once(MITTUN_CLASSY_PATH . '/includes/partials/classypress-settings.php');
	}

	/**
	 * Validate the ClassyPress License Key
	 * @param  string $key user defined classypress license key
	 * @return string      The validated key || WP settings error
	 */
	public function mittun_classy_validate_key($key) {
		if(!mittun_classy_validate_license_key($key)) {
			add_settings_error( 'mittun-classy-key', 'mittun_classy_key', __( 'Authentication key is not valid.', 'mittun_classy' ) , 'error' );
		}

		return $key;
	}

	/**
	 * Validate Global Classy Settings
	 */
	public function mittun_classy_validate_classy_settings($settings) {
		add_settings_error( 'mittun-classy-settings', 'mittun_classy', __( 'Classy settings updated successfully.', 'mittun_classy' ) , 'updated' );
		return $settings;
	}

	/**
	 * Validate ClassyPress Color Settings
	 */
	public function mittun_classy_validate_classy_color_settings($settings) {
		add_settings_error( 'mittun-classy-color-settings', 'mittun_classy_color', __( 'Color settings updated successfully.', 'mittun_classy' ) , 'updated' );
		return $settings;
	}

	/**
	 * Validate ClassyPress Advanced Settings
	 */
	public function mittun_classy_validate_classy_advanced_settings($settings) {
		add_settings_error( 'mittun-classy-advanced-settings', 'mittun_classy_advanced', __( 'Advanced settings updated successfully.', 'mittun_classy' ) , 'updated' );
		return $settings;
	}

	/**
	 * Enqueue ClassyPress Admin Scripts
	 */
	public function mittun_classy_admin_scripts($hook_suffix) {
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('mittun-classy-chosen',MITTUN_CLASSY_URL.'/css/chosen.css');
		wp_enqueue_style('mittun-classy-magnific-popup',MITTUN_CLASSY_URL.'/css/magnific-popup.css');
		wp_enqueue_style('mittun-classy-admin',MITTUN_CLASSY_URL.'/css/classy-mittun-admin.css');
		wp_enqueue_style('jquery-ui','//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('wp-color-picker');

		wp_enqueue_media();
		wp_enqueue_script( 'mittun-classy-chosen',MITTUN_CLASSY_URL.'/js/chosen.jquery.min.js',array('jquery'));
		wp_enqueue_script( 'mittun-classy-magnific-popup',MITTUN_CLASSY_URL.'/js/jquery.magnific-popup.js',array('jquery'));
		wp_enqueue_script( 'mittun-classy-cookie',MITTUN_CLASSY_URL.'/js/js.cookie.js',array('jquery'));
		wp_enqueue_script( 'mittun-classy-admin',MITTUN_CLASSY_URL.'/js/admin.js',array('jquery'));
	}

	/**
	 * Add ClassyPress Action Links
	 */
	public function mittun_classy_plugin_action_links ( $links ) {
		 $settingslinks = array(
		 '<a href="' . admin_url( 'admin.php?page=mittun-classy' ) . '">'.__('Settings','mittun_classy').'</a>',
		 );
		return array_merge($settingslinks,$links );
	}

	/**
	 * Add Plugin Meta
	 */
	public function mittun_classy_plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status) {

		$data=get_plugin_data(MITTUN_CLASSY_PATH.basename(MITTUN_CLASSY_PLUGIN_BASE));
		if($plugin_data['Name']==$data['Name']){
		$plugin_meta[2]='<a href="'.$data['PluginURI'].'" target="_blank">'.__('Visit plugin site','mittun_classy').'</a>';
		$plugin_meta[]='<a href="https://mittun.co/classy-support" target="_blank">'.__('Plugin Support','mittun_classy').'</a>';
		$plugin_meta[]='<a href="http://mittun.com/memberships/" target="_blank">'.__('Membership Benefits','mittun_classy').'</a>';
		$plugin_meta[]='<a href="http://mittun.com/upgrade/" target="_blank">'.__('Upgrade','mittun_classy').'</a>';

		}
		return $plugin_meta;
	}

	/**
	 * Add ClassyPress Import Page
	 */
	public function mittun_classy_import() {
		require_once(MITTUN_CLASSY_PATH.'/includes/import.php');
		new mittun_classy_import();
	}

}
add_action('init',function(){new mittun_classy_admin();},20);
?>
