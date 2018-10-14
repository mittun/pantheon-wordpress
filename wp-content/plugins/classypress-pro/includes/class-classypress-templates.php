<?php

/**
* Register ClassyPress templates to WP System
* Manage ClassyPress Template Settings/Fields
*
* @package classypress-pro
* @subpackage classypress-pro/includes
* @version 1.0.0
* @since 1.6.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ClassyPress_Templates')) {
  class ClassyPress_Templates {

    /**
    * Instance of this class
    * @var obj
    */
    private static $instance;

    /**
    * Array of templates from ClassyPress
    * @var array
    */
    protected $templates;

    /**
    * Key used for the theme's cache
    * @var string
    */
    private $cache_key;

    /**
    * Mittun template file name
    * @var string
    */
    private $template_file;

    /**
    * Array of user chosen form fields
    * @var array
    */
    public $form_fields_arr;

    /**
     * Filename for default Template BG Image
     * @var string
     */
    private $default_image_file;

    /**
    * Returns instance of class.
    * An implementation of the singleton design pattern.
    *
    * @return obj A reference to an instance of this class
    * @since 1.6.0
    */
    public static function get_instance() {
      if(null == self::$instance) {
        self::$instance = new ClassyPress_Templates();
      }

      return self::$instance;
    }

    /**
    * Initializes the class
    * Checks current WP version and registers hooks/filters accordingly
    *
    * @since 1.6.0
    */
    private function __construct() {
      $this->templates = array();
      $this->template_file = 'template-mittun-auto-donate.php';
      $this->default_image_file = 'classypress-default-bg.jpg';

      $this->form_fields_arr = array(
        'amount' => __('Amounts', 'mittun_classy'),
        'recurring'=>__('Payment Type', 'mittun_classy'),
        'first'=>__('First Name', 'mittun_classy'),
        'last'=>__('Last Name', 'mittun_classy'),
        'email'=>__('Email', 'mittun_classy'),
        'phone' => __('Phone', 'mittun_classy'),
        'street' => __('Address', 'mittun_classy'),
        'city'=>__('City', 'mittun_classy'),
        'state'=>__('State', 'mittun_classy'),
        'zip'=>__('Zip', 'mittun_classy')
      );

      if (version_compare( floatval($GLOBALS['wp_version']), '4.7', '<' )) {
        // 4.6 and older
        add_filter('page_attributes_dropdown_pages_args', array($this, 'register_mittun_templates'));
      } else {
        // 4.7+
        add_filter('theme_page_templates', array($this, 'add_mittun_templates'));
      }

      add_filter('wp_insert_post_data', array( $this, 'register_mittun_templates' ));
      add_filter('template_include', array( $this, 'view_mittun_template'));

      add_action('admin_enqueue_scripts', array($this, 'enqueue_background_script'));
      add_action('wp_enqueue_scripts', array($this, 'enqueue_template_frontend'));

      // Select the 'Donation Page' template by default
      add_action('add_meta_boxes', array($this, 'select_mittun_template'), 1);
      add_action('add_meta_boxes_page', array($this, 'add_template_meta_boxes'));
      add_action('save_post', array($this, 'classy_save_template_campaign_meta'));

      add_filter('body_class', array($this, 'add_mittun_body_class'));

      $this->templates = array(
        $this->template_file => __('ClassyPress Donation Page', 'mittun_classy')
      );

      $templates = wp_get_theme()->get_page_templates();
      $templates = array_merge($templates, $this->templates);

      $this->cache_key = 'page_templates-' . md5(get_theme_root() . '/' . get_stylesheet());
    }

    /**
    * Adds our template to the page cache to trick WP
    * into thinking the template file exists where it really doesn't
    *
    * @param  array $atts  The attributes shown for page dropdown
    * @return array $atts  Updated attributes for page dropdown
    * @since 1.6.0
    */
    public function register_mittun_templates($atts) {
      $templates = wp_cache_get($this->cache_key, 'themes');

      if(empty($templates)) {
        $templates = array();
      }

      wp_cache_delete($this->cache_key, 'themes');

      $templates = array_merge($templates, $this->templates);

      wp_cache_add($this->cache_key, $templates, 'themes', 1800);

      return $atts;
    }

    /**
    * Function to add the ClassyPress template to WP 4.7+
    *
    * @param   array   $post_templates Array of existing post templates
    * @return  array                   Modified/updated array of post templates
    * @since 1.6.0
    */
    public function add_mittun_templates($post_templates) {
      $post_templates = array_merge($post_templates, $this->templates);
      return $post_templates;
    }

    /**
    * Check the template is assigned to the current page
    *
    * @param  string $template The current page template
    * @return string           The template file location
    * @since 1.6.0
    */
    public function view_mittun_template($template) {
      global $post;

      // If no post is found, return to avoid error
      if(!isset($post)) {
        return $template;
      }

      // If the template is not already added to the theme templates, return to avoid error
      if(!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
        return $template;
      }

      $file = MITTUN_CLASSY_PATH . '/templates/' . get_post_meta($post->ID, '_wp_page_template', true);

      if(file_exists($file)) {
        return $file;
      }

      return $template;
    }

    /**
    * Remove the ClassyPress registered templates from theme
    * Runs on plugin deactivation
    *
    * @return null
    * @since 1.6.0
    */
    public function remove_mittun_templates() {
      $this->delete_mittun_template($this->template_file);
    }

    /**
    * Select the donation page template by default
    *
    * @return null
    * @since 1.6.0
    */
    public function select_mittun_template() {
      global $post;

      if(isset($_GET['classypress']) && $_GET['classypress'] === 'true') {
        // Make sure there is no template already selected and this is not the posts page.
        if('page' == $post->post_type && count(get_page_templates($post)) != 0 && get_option('page_for_posts') != $post->ID && $post->page_template == '') {
          $post->page_template = $this->template_file;
        }
      }
    }

    /**
    * Get Classy Checkout Data
    *
    * @return array  User entered checkout data
    * @since 1.6.0
    */
    public function get_checkout_data() {
      global $post;

      $return = array();

      $eid = get_post_meta($post->ID, '_classy_campaign_id', true);

      $return['campaign_id'] = $eid;
      $return['checkout_url'] = 'https://www.classy.org/checkout/donation/';

      $checkout_url_type = mittun_classy_get_option('checkout_url_type', 'mittun_classy_advanced');

      if($checkout_url_type == 'custom') {
        $custom_checkout_url = mittun_classy_get_option('custom_checkout_url', 'mittun_classy_advanced');
        $action = trim($custom_checkout_url, '/') . '/give/' . $eid . '/#!/donation/checkout';

        $return['checkout_url'] = esc_url($action);
      }

      return $return;
    }

    /**
    * Add Metaboxes to 'ClassyPress Donation Page' template ONLY
    *
    * @param object  $post The WP Post Object
    * @since 1.6.0
    */
    public function add_template_meta_boxes($post) {
      if($this->is_campaign_template($post)) {
        add_meta_box(
          'mittun_classy_campaign_meta_box',
          __( 'Campaign Details', 'mittun_classy' ),
          array($this, 'classy_template_campaign_meta_callback'),
          'page',
          'normal',
          'high'
        );

        // Image Upload
        add_meta_box(
          'mittun_classy_background_image',
          __( 'Background Image', 'mittun_classy' ),
          array( $this, 'template_background_meta_callback' ),
          'page',
          'side',
          'high'
        );

        // Background Color
        add_meta_box(
          'mittun_classy_background_color',
          __( 'Background Color', 'mittun_classy' ),
          array( $this, 'template_background_color_meta_callback' ),
          'page',
          'side',
          'high'
        );
      }
    }

    /**
    * ClassyPress Donation Page Metabox Markup
    *
    * @return null
    * @since 1.6.0
    */
    public function classy_template_campaign_meta_callback() {
      global $post;

      $classy_campaign_id = get_post_meta($post->ID, '_classy_campaign_id', true);

      $campaign_obj = new ClassyPress_Campaigns();
      $campaigns = $campaign_obj->get_classypress_campaigns();

      $campaign_url = get_post_meta($post->ID, '_classy_campaign_url', true);

      $primary_btn_text = get_post_meta($post->ID, '_classy_campaign_primary_btn_text', true);
      $primary_btn_text = empty($primary_btn_text) ? __('Donate Now', 'mittun_classy') : $primary_btn_text;

      $primary_btn_text_color = get_post_meta($post->ID, '_classy_campaign_primary_btn_text_color', true);
      if(empty($primary_btn_text_color)) {
        $primary_btn_text_color = mittun_classy_get_option('primary_btn_text_color','mittun_classy_color');
      }

      $primary_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_primary_btn_bg_color', true);
      if(empty($primary_btn_bg_color)) {
        $primary_btn_bg_color = mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color');
      }

      $inline_top_text = get_post_meta($post->ID, '_classy_campaign_inline_top_text', true);
      $inline_bottom_text = get_post_meta($post->ID, '_classy_campaign_inline_bottom_text', true);

      $popup_top_text = get_post_meta($post->ID, '_classy_campaign_popup_top_text', true);
      $popup_bottom_text = get_post_meta($post->ID, '_classy_campaign_popup_bottom_text', true);
      $display_form_type = get_post_meta($post->ID, '_classy_campaign_display_form_type', true);

      if(empty($display_form_type)) {
        $display_form_type = 'inline';
      }

      $form_type = get_post_meta($post->ID, '_classy_campaign_form_type', true);

      if(empty($form_type)) {
        $form_type = 'short';
      }

      $set_donation_amt = get_post_meta($post->ID, '_classy_campaign_set_donation_amt', true);
      $display_custom_amount_btn = get_post_meta($post->ID, '_classy_campaign_display_custom_amount_btn', true);
      $amount_btn_text = get_post_meta($post->ID, '_classy_campaign_amount_btn_text', true);
      $amount_btn_text = empty($amount_btn_text) ? __('Other Amount', 'mittun_classy') : $amount_btn_text;
      $amount_btn_text_color = get_post_meta($post->ID, '_classy_campaign_amount_btn_text_color', true);
      $amount_btn_text_color = !empty($amount_btn_text_color) ? $amount_btn_text_color : mittun_classy_get_option('amount_btn_text_color', 'mittun_classy_color');
      $amount_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_amount_btn_bg_color', true);
      $active_amount_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_active_amount_btn_bg_color', true);
      $donation_amt = get_post_meta($post->ID, '_classy_campaign_donation_amt', true);

      $payment_btn_text_color = get_post_meta($post->ID, '_classy_campaign_payment_btn_text_color', true);
      $payment_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_payment_btn_bg_color', true);
      $payment_active_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_payment_active_btn_bg_color', true);

      $sf_payment_btn_text_color = get_post_meta($post->ID, '_classy_campaign_sf_payment_btn_text_color', true);
      $sf_payment_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_sf_payment_btn_bg_color', true);
      $sf_payment_active_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_sf_payment_active_btn_bg_color', true);

      $submit_btn_text_color = get_post_meta($post->ID, '_classy_campaign_submit_btn_text_color', true);
      $submit_btn_bg_color = get_post_meta($post->ID, '_classy_campaign_submit_btn_bg_color', true);
      $fields_to_display = get_post_meta($post->ID, '_classy_campaign_fields_to_display', true);

      $submit_btn_label = get_post_meta($post->ID, '_classy_campaign_submit_btn_label', true);
      if(empty($submit_btn_label)) {
        $submit_btn_label = __('Submit', 'mittun_classy');
      }

      $default_donation_amt = get_post_meta($post->ID, '_classy_campaign_default_donation_amt', true);
      if(empty($default_donation_amt)) {
        $default_donation_amt = 25;
      }

      $once_btn_text = get_post_meta($post->ID, '_classy_campaign_once_btn_text', true);
      if(empty($once_btn_text)) {
        $once_btn_text = __('Once', 'mittun_classy');
      }

      $monthly_btn_text = get_post_meta($post->ID, '_classy_campaign_monthly_btn_text', true);
      if(empty($monthly_btn_text)) {
        $monthly_btn_text = __('Monthly', 'mittun_classy');
      }

      $donation_type = get_post_meta($post->ID, '_classy_campaign_donation_type', true);
      if(empty($donation_type)) {
        $donation_type = 'form';
      }

      $custom_css = get_post_meta($post->ID, '_classy_campaign_custom_css', true);
      if(empty($custom_css)) {
        $custom_css = file_get_contents(MITTUN_CLASSY_PATH . '/css/auto-donation-template.default.css');
      }

      require_once(MITTUN_CLASSY_PATH . '/includes/partials/classy-donation-template-fields.php');
    }

    /**
    * Save Template Meta Data
    *
    * @param  int $pid   Post ID to attach the meta to
    * @return null
    * @since 1.6.0
    */
    public function classy_save_template_campaign_meta($pid) {

      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
      }

      update_post_meta($pid, '_classy_campaign_page', true);

      if(isset($_POST['_classy_campaign_id'])) {
        $classy_campaign_id = $_POST['_classy_campaign_id'];
        update_post_meta($pid, '_classy_campaign_id', $classy_campaign_id);
      }

      if(isset($_POST['_classy_campaign_url'])) {
        $classy_campaign_url = $_POST['_classy_campaign_url'];
        update_post_meta($pid, '_classy_campaign_url', $classy_campaign_url);
      }

      if(isset($_POST['_classy_campaign_primary_btn_text'])) {
        $classy_campaign_primary_btn_text = $_POST['_classy_campaign_primary_btn_text'];
        update_post_meta($pid, '_classy_campaign_primary_btn_text', $classy_campaign_primary_btn_text);
      }

      if(isset($_POST['_classy_campaign_primary_btn_text_color'])) {
        $classy_campaign_primary_btn_text_color = $_POST['_classy_campaign_primary_btn_text_color'];
        update_post_meta($pid, '_classy_campaign_primary_btn_text_color', $classy_campaign_primary_btn_text_color);
      }

      if(isset($_POST['_classy_campaign_primary_btn_bg_color'])) {
        $classy_campaign_primary_btn_bg_color = $_POST['_classy_campaign_primary_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_primary_btn_bg_color', $classy_campaign_primary_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_inline_top_text'])) {
        $classy_campaign_inline_top_text = $_POST['_classy_campaign_inline_top_text'];
        update_post_meta($pid, '_classy_campaign_inline_top_text', $classy_campaign_inline_top_text);
      }

      if(isset($_POST['_classy_campaign_inline_bottom_text'])) {
        $classy_campaign_inline_bottom_text = $_POST['_classy_campaign_inline_bottom_text'];
        update_post_meta($pid, '_classy_campaign_inline_bottom_text', $classy_campaign_inline_bottom_text);
      }

      if(isset($_POST['_classy_campaign_popup_top_text'])) {
        $classy_campaign_popup_top_text = $_POST['_classy_campaign_popup_top_text'];
        update_post_meta($pid, '_classy_campaign_popup_top_text', $classy_campaign_popup_top_text);
      }

      if(isset($_POST['_classy_campaign_popup_bottom_text'])) {
        $classy_campaign_popup_bottom_text = $_POST['_classy_campaign_popup_bottom_text'];
        update_post_meta($pid, '_classy_campaign_popup_bottom_text', $classy_campaign_popup_bottom_text);
      }

      if(isset($_POST['_classy_campaign_display_form_type'])) {
        $classy_campaign_display_form_type = $_POST['_classy_campaign_display_form_type'];
        update_post_meta($pid, '_classy_campaign_display_form_type', $classy_campaign_display_form_type);
      }

      if(isset($_POST['_classy_campaign_form_type'])) {
        $classy_campaign_form_type = $_POST['_classy_campaign_form_type'];
        update_post_meta($pid, '_classy_campaign_form_type', $classy_campaign_form_type);
      }

      if(isset($_POST['_classy_campaign_set_donation_amt'])) {
        $classy_campaign_set_donation_amt = $_POST['_classy_campaign_set_donation_amt'];
        update_post_meta($pid, '_classy_campaign_set_donation_amt', $classy_campaign_set_donation_amt);
      } else {
        update_post_meta($pid, '_classy_campaign_set_donation_amt', false);
      }

      if(isset($_POST['_classy_campaign_display_custom_amount_btn'])) {
        $classy_campaign_display_custom_amount_btn = $_POST['_classy_campaign_display_custom_amount_btn'];
        update_post_meta($pid, '_classy_campaign_display_custom_amount_btn', $classy_campaign_display_custom_amount_btn);
      } else {
        update_post_meta($pid, '_classy_campaign_display_custom_amount_btn', false);
      }

      if(isset($_POST['_classy_campaign_amount_btn_text'])) {
        $classy_campaign_amount_btn_text = $_POST['_classy_campaign_amount_btn_text'];
        update_post_meta($pid, '_classy_campaign_amount_btn_text', $classy_campaign_amount_btn_text);
      }

      if(isset($_POST['_classy_campaign_amount_btn_text_color'])) {
        $classy_campaign_amount_btn_text_color = $_POST['_classy_campaign_amount_btn_text_color'];
        update_post_meta($pid, '_classy_campaign_amount_btn_text_color', $classy_campaign_amount_btn_text_color);
      }

      if(isset($_POST['_classy_campaign_amount_btn_bg_color'])) {
        $classy_campaign_amount_btn_bg_color = $_POST['_classy_campaign_amount_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_amount_btn_bg_color', $classy_campaign_amount_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_active_amount_btn_bg_color'])) {
        $active_amount_btn_bg_color = $_POST['_classy_campaign_active_amount_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_active_amount_btn_bg_color', $active_amount_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_donation_amt'])) {
        $classy_campaign_donation_amt = $_POST['_classy_campaign_donation_amt'];
        update_post_meta($pid, '_classy_campaign_donation_amt', $classy_campaign_donation_amt);
      }

      if(isset($_POST['_classy_campaign_payment_btn_text_color'])) {
        $classy_campaign_payment_btn_text_color = $_POST['_classy_campaign_payment_btn_text_color'];
        update_post_meta($pid, '_classy_campaign_payment_btn_text_color', $classy_campaign_payment_btn_text_color);
      }

      if(isset($_POST['_classy_campaign_payment_btn_bg_color'])) {
        $classy_campaign_payment_btn_bg_color = $_POST['_classy_campaign_payment_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_payment_btn_bg_color', $classy_campaign_payment_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_payment_active_btn_bg_color'])) {
        $classy_campaign_payment_active_btn_bg_color = $_POST['_classy_campaign_payment_active_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_payment_active_btn_bg_color', $classy_campaign_payment_active_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_submit_btn_text_color'])) {
        $classy_campaign_submit_btn_text_color = $_POST['_classy_campaign_submit_btn_text_color'];
        update_post_meta($pid, '_classy_campaign_submit_btn_text_color', $classy_campaign_submit_btn_text_color);
      }

      if(isset($_POST['_classy_campaign_submit_btn_bg_color'])) {
        $classy_campaign_submit_btn_bg_color = $_POST['_classy_campaign_submit_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_submit_btn_bg_color', $classy_campaign_submit_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_sf_payment_btn_text_color'])) {
        $classy_campaign_sf_payment_btn_text_color = $_POST['_classy_campaign_sf_payment_btn_text_color'];
        update_post_meta($pid, '_classy_campaign_sf_payment_btn_text_color', $classy_campaign_sf_payment_btn_text_color);
      }

      if(isset($_POST['_classy_campaign_sf_payment_btn_bg_color'])) {
        $classy_campaign_sf_payment_btn_bg_color = $_POST['_classy_campaign_sf_payment_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_sf_payment_btn_bg_color', $classy_campaign_sf_payment_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_sf_payment_active_btn_bg_color'])) {
        $classy_campaign_sf_payment_active_btn_bg_color = $_POST['_classy_campaign_sf_payment_active_btn_bg_color'];
        update_post_meta($pid, '_classy_campaign_sf_payment_active_btn_bg_color', $classy_campaign_sf_payment_active_btn_bg_color);
      }

      if(isset($_POST['_classy_campaign_fields_to_display'])) {
        $classy_campaign_fields_to_display = $_POST['_classy_campaign_fields_to_display'];
        update_post_meta($pid, '_classy_campaign_fields_to_display', $classy_campaign_fields_to_display);
      }

      if(isset($_POST['_classy_campaign_submit_btn_label'])) {
        $classy_campaign_submit_btn_label = $_POST['_classy_campaign_submit_btn_label'];
        update_post_meta($pid, '_classy_campaign_submit_btn_label', $classy_campaign_submit_btn_label);
      }

      if(isset($_POST['_classy_campaign_default_donation_amt'])) {
        $classy_campaign_default_donation_amt = $_POST['_classy_campaign_default_donation_amt'];
        update_post_meta($pid, '_classy_campaign_default_donation_amt', $classy_campaign_default_donation_amt);
      }

      if(isset($_POST['_classy_campaign_once_btn_text'])) {
        $classy_campaign_once_btn_text = $_POST['_classy_campaign_once_btn_text'];
        update_post_meta($pid, '_classy_campaign_once_btn_text', $classy_campaign_once_btn_text);
      }

      if(isset($_POST['_classy_campaign_monthly_btn_text'])) {
        $classy_campaign_monthly_btn_text = $_POST['_classy_campaign_monthly_btn_text'];
        update_post_meta($pid, '_classy_campaign_monthly_btn_text', $classy_campaign_monthly_btn_text);
      }

      if(isset($_POST['classy_donation_bg']) && strlen($_POST['classy_donation_bg']) >= 1 && $_POST['classy_donation_bg'] !== false) {
        update_post_meta( $pid, '_classy_donation_bg', $_POST['classy_donation_bg'] );
      } else {
        update_post_meta( $pid, '_classy_donation_bg', '');
      }

      if(isset($_POST['classy_donation_bg_color']) && strlen($_POST['classy_donation_bg_color']) > 1) {
        $bg_color = sanitize_text_field($_POST['classy_donation_bg_color']);
        update_post_meta($pid, '_classy_donation_bg_color', $bg_color);
      } else {
        update_post_meta($pid, '_classy_donation_bg_color', '');
      }

      if(isset($_POST['_classy_campaign_custom_css'])) {
        $custom_css = $_POST['_classy_campaign_custom_css'];
        update_post_meta($pid, '_classy_campaign_custom_css', $custom_css);
      }

      return $pid;
    }

    /**
    * Enqueue custom image upload JS script
    * Enqueue color picker scripts
    *
    * @return null
    * @since 1.6.0
    */
    public function enqueue_background_script() {
      wp_enqueue_script( 'classypress-background', MITTUN_CLASSY_URL . '/js/classy-bg.js', array('jquery'), null, false );
      wp_enqueue_script( 'wp-color-picker' );
      wp_enqueue_style( 'wp-color-picker' );
    }

    /**
    * Enqueue frontend styles on auto donation template
    *
    * @return null
    * @since 1.6.0
    */
    public function enqueue_template_frontend() {
      global $post;

      if($this->is_campaign_template($post)) {
        wp_enqueue_style('auto-donate-style', MITTUN_CLASSY_URL . '/css/auto-donation-template.css', '1.6.0');
      }
    }

    /**
    * Echo out the background image meta box
    *
    * @param  object $post WP Post Object
    * @since 1.6.0
    */
    public function template_background_meta_callback( $post ) {
      echo $this->template_background_image_field( 'classy_donation_bg', get_post_meta($post->ID, '_classy_donation_bg', true) );
    }

    /**
    * Echo out the background image meta box
    *
    * @param  object $post WP Post Object
    * @since 1.6.0
    */
    public function template_background_color_meta_callback( $post ) {
      echo $this->template_background_color_field($post);
    }

    /**
    * Create the Background Image Upload Field
    * Set the default background image if on a new page
    *
    * @param  string $name  Name of image field
    * @param  string $value Value/Image
    * @return string        HTML Markup for Meta Box
    * @since 1.6.0
    */
    private function template_background_image_field($name, $value = '') {
      $image = ' button">' . __('Upload image', 'mittun_classy');
      $image_size = 'thumbnail';
      $display = 'none'; // display state ot the "Remove image" button

      $image_attributes = wp_get_attachment_image_src($value, $image_size);

      if($image_attributes) {
        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%; display:block;" />';
        $display = 'inline-block';
      } else {
        global $pagenow;

        // Make sure we are CREATING a new donation page (not editing)
        if($pagenow === 'post-new.php') {
          $value = $this->upload_default_image();
          $image_attributes = wp_get_attachment_image_src($value, $image_size);
          $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%; display:block;" />';
          $display = 'inline-block';
        }
      }
      require_once(MITTUN_CLASSY_PATH . '/includes/partials/auto-donation-background-field.php');
    }

    /**
     * Render the color picker field for the donation background color
     * @param  object $post WP_Post object
     * @return string       HTML Markup for color selector
     */
    private function template_background_color_field($post) {
      $bg_color = get_post_meta($post->ID, '_classy_donation_bg_color', true);
      require_once(MITTUN_CLASSY_PATH . '/includes/partials/auto-donation-background-color-field.php');
    }

    /**
    * Check if we are viewing the campaign template
    *
    * @param  boolean $post Post Object || false
    * @return boolean       True/False whether or not we are viewing campaign template
    * @since 1.6.0
    */
    private function is_campaign_template($post = false) {
      if(!$post || is_object($post)) {
        global $post;
      }

      if(get_post_meta($post->ID, '_wp_page_template', true) === $this->template_file) {
        return true;
      }

      if(isset($_GET['classypress']) && $_GET['classypress'] === 'true') {
        return true;
      }

      return false;
    }

    /**
    * Delete the templates from the theme
    *
    * @param  string $filename The file/templates to delete
    * @return null
    * @since 1.6.0
    */
    private function delete_mittun_template($filename) {
      $theme_path = get_template_directory();
      $template_path = $theme_path . '/' . $filename;

      if(file_exists($template_path)) {
        unlink($template_path);
      }

      wp_cache_delete($this->cache_key, 'themes');
    }

    /**
     * Check if default background image has been uploaded/saved already
     *
     * @return int  ID of default background image
     * @since 1.6.0
     */
    private function check_default_image_upload() {
      global $wpdb;

      $query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$this->default_image_file'";

      if ($wpdb->get_var($query)) {
        return $wpdb->get_var($query);
      }

      return false;
    }

    /**
     * Upload default ClassyPress BG Image
     *
     * @return int  ID of Image Attachment
     * @since 1.6.0
     */
    private function upload_default_image() {
      if(!$this->is_campaign_template()) {
        return;
      }

      $img_id = $this->check_default_image_upload();

      if(!$img_id || !is_numeric($img_id)) {
        $img_path = MITTUN_CLASSY_PATH . '/img/' . $this->default_image_file;
        $img_name = basename($img_path);

        $upload_file = wp_upload_bits($img_name, null, file_get_contents($img_path));

        if(!$upload_file['error']) {
          $img_type = wp_check_filetype($img_name);

          $img_data = array(
            'guid' => $upload_file['file'],
            'post_title' => __('ClassyPress Donation Page Background', 'mittun_classy'),
            'post_content' => __('ClassyPress Donation Page Background', 'mittun_classy'),
            'post_status' => 'publish',
            'post_mime_type' => $img_type['type'],
            'post_type' => 'attachment'
          );

          $img_id = wp_insert_attachment($img_data, $upload_file['file']);

          if(!is_wp_error($img_id)) {
            require_once(ABSPATH . '/wp-admin/includes/image.php');
            wp_update_attachment_metadata($img_id, wp_generate_attachment_metadata($img_id, $upload_file['file']));
          }
        }
      }

      return $img_id;
    }

    /**
     * Add unique body class for template specific styling
     *
     * @param array $classes Array of existing body classes from WP
     * @return array         Modified body class array
     * @since 1.6.0
     */
    public function add_mittun_body_class($classes) {
      if($this->is_campaign_template()) {
        $classes[] = 'mittun-auto-donate-template';
      }

      return $classes;
    }

    /**
    * Return the appropriate background image for the form
    *
    * @param  obj $post  WP Post Object
    * @return string     Background Image URL
    * @since 1.6.0
    */
    public static function return_donation_background($post) {
      $bg_image = false;
      $bg_image = get_post_meta($post->ID, '_classy_donation_bg', true);
      $bg_color = get_post_meta($post->ID, '_classy_donation_bg_color', true);
      $bg_str = '';

      if($bg_image) {
        $bg_image = wp_get_attachment_url($bg_image);
      }

      $bg_str = ' style="background: ' . $bg_color . ($bg_image ? ' url(' . $bg_image . ') no-repeat center center;' : '') . '"';

      return $bg_str;
    }

    /**
     * Strip CSS Comments from Custom CSS Output
     *
     * @param  string $content The user defined CSS
     * @return string          The reformatted CSS w/o comments
     * @since 1.6.0
     */
    public static function strip_custom_css_comments($content) {
      $regex = array(
        "`^([\t\s]+)`ism"=>'',
        "`^\/\*(.+?)\*\/`ism"=>"",
        "`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1",
        "`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"$1\n",
        "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
      );

      return preg_replace(array_keys($regex), $regex, $content);
    }
  }
}
