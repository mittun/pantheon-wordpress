<?php
class mittun_classy_post_types{

	var $form_fields_arr=array();

	function __construct()
	{
		$this->fake_date = '1970-01-05';
		if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE)
		{
			$this->form_fields_arr=array('amount'=>__('Amounts','mittun_classy'),'recurring'=>__('Payment Type','mittun_classy'),'first'=>__('First Name','mittun_classy'),'last'=>__('Last Name','mittun_classy'),'email'=>__('Email','mittun_classy'),'phone'=>__('Phone','mittun_classy'),'street'=>__('Address','mittun_classy'),'city'=>__('City','mittun_classy'),'state'=>__('State','mittun_classy'),'zip'=>__('Zip','mittun_classy'));
			add_action( 'init', array($this,'mittun_classy_post_type_init' ),25);
			add_action( 'add_meta_boxes',array($this, 'mittun_classy_meta_box_init'),1 );
			add_action( 'save_post', array($this,'mittun_classy_campaign_save_meta_data') );
			add_action( 'save_post', array($this,'mittun_classy_combined_campaign_save_meta_data') );
			add_action( 'save_post', array($this,'mittun_nonclassy_campaign_save_meta_data') );
			add_action( 'save_post', array($this,'mittun_classy_leaderboard_save_meta_data') );
			add_action( 'save_post', array($this,'mittun_classy_event_save_meta_data') );
			add_filter('manage_edit-mittun-campaign_columns', array($this,'mittun_classy_campaign_columns'));
			add_action('manage_mittun-campaign_posts_custom_column', array($this,'manage_mittun_classy_campaign_columns'), 10, 2);

			add_filter('manage_edit-mittun-nonclassy_columns', array($this,'mittun_classy_nonclassy_columns'));
			add_action('manage_mittun-nonclassy_posts_custom_column', array($this,'manage_mittun_classy_nonclassy_columns'), 10, 2);

			add_filter('manage_edit-mittun-multicampaign_columns', array($this,'mittun_classy_multicampaign_columns'));
			add_action('manage_mittun-multicampaign_posts_custom_column', array($this,'manage_mittun_classy_multicampaign_columns'), 10, 2);
			add_filter('manage_edit-mittun-leaderboard_columns', array($this,'mittun_classy_leaderboard_columns'));
			add_action('manage_mittun-leaderboard_posts_custom_column', array($this,'manage_mittun_classy_leaderboard_columns'), 10, 2);
			add_filter('manage_edit-mittun-event_columns', array($this,'mittun_classy_event_columns'));
			add_action('manage_mittun-event_posts_custom_column', array($this,'manage_mittun_classy_event_columns'), 10, 2);
			if ( current_user_can( 'export' ) ) {
				add_filter('post_row_actions',array($this,'mittun_classy_action_row'), 10, 2);
				add_filter( 'export_args', array( $this, 'mittun_classy_export_args' ) );
				add_filter( 'query',array( $this, 'mittun_classy_export_query' ) );
			}
		}
	}

	function mittun_classy_post_type_init()
	{
		$labels = array(
			'name'               => __( 'Campaigns - Classypress', 'mittun_classy' ),
			'singular_name'      => __( 'Campaigns - Classypress', 'mittun_classy' ),
			'menu_name'          => __( 'Campaigns - Classypress', 'mittun_classy' ),
			'name_admin_bar'     => __( 'Campaigns - Classypress', 'mittun_classy' ),
			'add_new'            => __( 'Add New', 'mittun_classy' ),
			'add_new_item'       => __( 'Add New Campaigns - Classypress', 'mittun_classy' ),
			'new_item'           => __( 'Add New', 'mittun_classy' ),
			'edit_item'          => __( 'Edit', 'mittun_classy' ),
			'view_item'          => __( 'View', 'mittun_classy' ),
			'all_items'          => __( 'All Campaigns - Classypress', 'mittun_classy' ),
			'search_items'       => __( 'Search Campaigns - Classypress', 'mittun_classy' ),
			'parent_item_colon'  => __( 'Parent Campaigns - Classypress:', 'mittun_classy' ),
			'not_found'          => __( 'Nothing found.', 'mittun_classy' ),
			'not_found_in_trash' => __( 'Nothing found in Trash.', 'mittun_classy' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Campaign post type', 'mittun_classy' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'mittun-campaign' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_menu' 		 => 'edit.php?post_type=mittun-campaign',
			'supports'           => array( 'title')
		);

		register_post_type( 'mittun-campaign', $args );
		flush_rewrite_rules();

		$labels = array(
			'name'               => __( 'Combined Campaigns - Classypress', 'mittun_classy' ),
			'singular_name'      => __( 'Combined Campaigns - Classypress', 'mittun_classy' ),
			'menu_name'          => __( 'Combined Campaigns - Classypress', 'mittun_classy' ),
			'name_admin_bar'     => __( 'Combined Campaigns - Classypress', 'mittun_classy' ),
			'add_new'            => __( 'Add New', 'mittun_classy' ),
			'add_new_item'       => __( 'Add New Combined Campaigns - Classypress', 'mittun_classy' ),
			'new_item'           => __( 'Add New', 'mittun_classy' ),
			'edit_item'          => __( 'Edit', 'mittun_classy' ),
			'view_item'          => __( 'View', 'mittun_classy' ),
			'all_items'          => __( 'All Combined Campaigns - Classypress', 'mittun_classy' ),
			'search_items'       => __( 'Search Combined Campaigns - Classypress', 'mittun_classy' ),
			'parent_item_colon'  => __( 'Parent Combined Campaigns - Classypress:', 'mittun_classy' ),
			'not_found'          => __( 'Nothing found.', 'mittun_classy' ),
			'not_found_in_trash' => __( 'Nothing found in Trash.', 'mittun_classy' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Combined Campaign post type', 'mittun_classy' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'mittun-multicampaign' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_menu' 		 => 'edit.php?post_type=mittun-multicampaign',
			'supports'           => array( 'title')
		);

		register_post_type( 'mittun-multicampaign', $args );
		flush_rewrite_rules();

		$labels = array(
			'name'               => __( 'Donation Form - Classypress', 'mittun_classy' ),
			'singular_name'      => __( 'Donation Form - Classypress', 'mittun_classy' ),
			'menu_name'          => __( 'Donation Form - Classypress', 'mittun_classy' ),
			'name_admin_bar'     => __( 'Donation Form - Classypress', 'mittun_classy' ),
			'add_new'            => __( 'Add New', 'mittun_classy' ),
			'add_new_item'       => __( 'Add New Donation Form - Classypress', 'mittun_classy' ),
			'new_item'           => __( 'Add New', 'mittun_classy' ),
			'edit_item'          => __( 'Edit', 'mittun_classy' ),
			'view_item'          => __( 'View', 'mittun_classy' ),
			'all_items'          => __( 'All Donation Forms - Classypress', 'mittun_classy' ),
			'search_items'       => __( 'Search Donation Forms - Classypress', 'mittun_classy' ),
			'parent_item_colon'  => __( 'Parent Donation Forms - Classypress:', 'mittun_classy' ),
			'not_found'          => __( 'Nothing found.', 'mittun_classy' ),
			'not_found_in_trash' => __( 'Nothing found in Trash.', 'mittun_classy' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Non classy Campaign post type', 'mittun_classy' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'mittun-nonclassy' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_menu' 		 => 'edit.php?post_type=mittun-nonclassy',
			'supports'           => array( 'title')
		);

		register_post_type( 'mittun-nonclassy', $args );
		flush_rewrite_rules();


		$labels = array(
			'name'               => __( 'Leaderboards - Classypress', 'mittun_classy' ),
			'singular_name'      => __( 'Leaderboards - Classypress', 'mittun_classy' ),
			'menu_name'          => __( 'Leaderboards - Classypress', 'mittun_classy' ),
			'name_admin_bar'     => __( 'Leaderboards - Classypress', 'mittun_classy' ),
			'add_new'            => __( 'Add New', 'mittun_classy' ),
			'add_new_item'       => __( 'Add New Leaderboards - Classypress', 'mittun_classy' ),
			'new_item'           => __( 'Add New', 'mittun_classy' ),
			'edit_item'          => __( 'Edit', 'mittun_classy' ),
			'view_item'          => __( 'View', 'mittun_classy' ),
			'all_items'          => __( 'All Leaderboards - Classypress', 'mittun_classy' ),
			'search_items'       => __( 'Search Leaderboards - Classypress', 'mittun_classy' ),
			'parent_item_colon'  => __( 'Parent Leaderboards - Classypress:', 'mittun_classy' ),
			'not_found'          => __( 'Nothing found.', 'mittun_classy' ),
			'not_found_in_trash' => __( 'Nothing found in Trash.', 'mittun_classy' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Leaderboard post type', 'mittun_classy' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'mittun-leaderboard' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_menu' 		 => 'edit.php?post_type=mittun-leaderboard',
			'supports'           => array( 'title')
		);

		register_post_type( 'mittun-leaderboard', $args );
		flush_rewrite_rules();

		$labels = array(
			'name'               => __( 'Event Listings - Classypress', 'mittun_classy' ),
			'singular_name'      => __( 'Event Listings - Classypress', 'mittun_classy' ),
			'menu_name'          => __( 'Event Listings - Classypress', 'mittun_classy' ),
			'name_admin_bar'     => __( 'Event Listings - Classypress', 'mittun_classy' ),
			'add_new'            => __( 'Add New', 'mittun_classy' ),
			'add_new_item'       => __( 'Add New Event Listings - Classypress', 'mittun_classy' ),
			'new_item'           => __( 'Add New', 'mittun_classy' ),
			'edit_item'          => __( 'Edit', 'mittun_classy' ),
			'view_item'          => __( 'View', 'mittun_classy' ),
			'all_items'          => __( 'All Event Listings - Classypress', 'mittun_classy' ),
			'search_items'       => __( 'Search Event Listings - Classypress', 'mittun_classy' ),
			'parent_item_colon'  => __( 'Parent Event Listings - Classypress:', 'mittun_classy' ),
			'not_found'          => __( 'Nothing found.', 'mittun_classy' ),
			'not_found_in_trash' => __( 'Nothing found in Trash.', 'mittun_classy' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Event post type', 'mittun_classy' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => false,
			'rewrite'            => array( 'slug' => 'mittun-event' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_menu' 		 => 'edit.php?post_type=mittun-event',
			'supports'           => array( 'title')
		);

		register_post_type( 'mittun-event', $args );
		flush_rewrite_rules();

	}

	function mittun_classy_meta_box_init() {
		add_meta_box('mittun_classy_campaign_meta_box',__( 'Campaign Details', 'mittun_classy' ),array($this,'mittun_classy_campaign_meta_callback'),'mittun-campaign','normal','default');
		add_meta_box('mittun_classy_campaign_meta_box',__( 'Campaign Details', 'mittun_classy' ),array($this,'mittun_classy_multicampaign_meta_callback'),'mittun-multicampaign','normal','default');
		add_meta_box('mittun_classy_campaign_meta_box',__( 'Campaign Details', 'mittun_classy' ),array($this,'mittun_classy_nonclassycampaign_meta_callback'),'mittun-nonclassy','normal','default');
		add_meta_box('mittun_classy_leaderboard_meta_box',__( 'Leaderboard Details', 'mittun_classy' ),array($this,'mittun_classy_leaderboard_meta_callback'),'mittun-leaderboard','normal','default');
		add_meta_box('mittun_classy_event_meta_box',__( 'Event Details', 'mittun_classy' ),array($this,'mittun_classy_event_meta_callback'),'mittun-event','normal','default');

	}

	function mittun_classy_campaign_meta_callback($post)
	{

		wp_nonce_field( 'mittun_classy_campaign_meta', 'mittun_classy_campaign_meta_nonce' );

		global $post_type ;

		$classy_campaign_id = get_post_meta($post->ID,'_classy_campaign_id',true);
		$skin = get_post_meta($post->ID,'_classy_campaign_skin',true);
		$skin =!empty($skin)?$skin:mittun_classy_get_option('skin','mittun_classy_color');
		$sliding_form_icon =get_post_meta($post->ID,'_classy_campaign_sliding_form_icon',true);
		$sliding_form_icon =!empty($sliding_form_icon)?$sliding_form_icon:MITTUN_CLASSY_URL.'/img/menu.png';
		$sliding_form_position =get_post_meta($post->ID,'_classy_campaign_sliding_form_position',true);
		$sliding_form_position =empty($sliding_form_position)?'left':$sliding_form_position;
		$sliding_form_bg_color =get_post_meta($post->ID,'_classy_campaign_sliding_form_bg_color',true);
		$sliding_form_bg_color=empty($sliding_form_bg_color)?'#000000':$sliding_form_bg_color;
		$css_class = get_post_meta($post->ID,'_classy_campaign_css_class',true);
		$display_campaign_title = get_post_meta($post->ID,'_classy_campaign_display_campaign_title',true);

		$display_progress_bar = get_post_meta($post->ID,'_classy_campaign_display_progress_bar',true);
		$progress_bar_style = get_post_meta($post->ID,'_classy_campaign_progress_bar_style',true);
		$progress_bar_style =!empty($progress_bar_style)?$progress_bar_style:mittun_classy_get_option('progress_bar_style','mittun_classy_color');
		$progress_bar_color=get_post_meta($post->ID,'_classy_campaign_progress_bar_color',true);
		$progress_bar_text_color=get_post_meta($post->ID,'_classy_campaign_progress_bar_text_color',true);
		$progress_bar_marker_color=get_post_meta($post->ID,'_classy_campaign_progress_bar_marker_color',true);
		$primary_btn_text=get_post_meta($post->ID,'_classy_campaign_primary_btn_text',true);
		$primary_btn_text=empty($primary_btn_text)?__('Donate Now','mittun_classy'):$primary_btn_text;
		$primary_btn_text_color=get_post_meta($post->ID,'_classy_campaign_primary_btn_text_color',true);
		$primary_btn_bg_color=get_post_meta($post->ID,'_classy_campaign_primary_btn_bg_color',true);
		$popup_top_text=get_post_meta($post->ID,'_classy_campaign_popup_top_text',true);
		$popup_bottom_text=get_post_meta($post->ID,'_classy_campaign_popup_bottom_text',true);

		$heading_color=get_post_meta($post->ID,'_classy_campaign_heading_color',true);

		$display_goal_amount = get_post_meta($post->ID,'_classy_campaign_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($post->ID,'_classy_campaign_goal_amount_text_color',true);


		$display_amount_raised =get_post_meta($post->ID,'_classy_campaign_display_amount_raised',true);
		$display_donor_count =get_post_meta($post->ID,'_classy_campaign_display_donor_count',true);
		$amount_raised_calculation_type =get_post_meta($post->ID,'_classy_campaign_amount_raised_calculation_type',true);
		if(empty($amount_raised_calculation_type))
		$amount_raised_calculation_type='only_donation';
		$display_fee_details = get_post_meta($post->ID,'_classy_campaign_display_fee_details',true);
		$amount_raised_text_color = get_post_meta($post->ID,'_classy_campaign_amount_raised_text_color',true);
		$display_amount_raised_heading =get_post_meta($post->ID,'_classy_campaign_display_amount_raised_heading',true);
		$amount_raised_heading =get_post_meta($post->ID,'_classy_campaign_amount_raised_heading',true);
		if(empty($amount_raised_heading))
		$amount_raised_heading=__('FUNDRAISING PROGRESS:','mittun_classy');
		$display_amount_raised_percentage_number =get_post_meta($post->ID,'_classy_campaign_display_amount_raised_percentage_number',true);

		$donation_type =get_post_meta($post->ID,'_classy_campaign_donation_type',true);
		if(empty($donation_type))
			$donation_type='form';
		$display_form_type =get_post_meta($post->ID,'_classy_campaign_display_form_type',true);
		if(empty($display_form_type))
		$display_form_type='inline';
		$display_custom_checkout_url =get_post_meta($post->ID,'_classy_campaign_display_custom_checkout_url',true);
		$custom_checkout_url =get_post_meta($post->ID,'_classy_campaign_custom_checkout_url',true);
		$form_type =get_post_meta($post->ID,'_classy_campaign_form_type',true);
		if(empty($form_type))
		$form_type='short';
		$set_donation_amt =get_post_meta($post->ID,'_classy_campaign_set_donation_amt',true);
		$display_custom_amount_btn =get_post_meta($post->ID,'_classy_campaign_display_custom_amount_btn',true);
		$amount_btn_text =get_post_meta($post->ID,'_classy_campaign_amount_btn_text',true);
		$amount_btn_text=empty($amount_btn_text)?__('Other Amount','mittun_classy'):$amount_btn_text;
		$amount_btn_text_color =get_post_meta($post->ID,'_classy_campaign_amount_btn_text_color',true);
		$amount_btn_text_color=!empty($amount_btn_text_color)?$amount_btn_text_color:mittun_classy_get_option('amount_btn_text_color','mittun_classy_color');
		$amount_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_amount_btn_bg_color',true);

		$active_amount_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_active_amount_btn_bg_color',true);
		$donation_amt =get_post_meta($post->ID,'_classy_campaign_donation_amt',true);

		$payment_btn_text_color =get_post_meta($post->ID,'_classy_campaign_payment_btn_text_color',true);
		$payment_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_payment_btn_bg_color',true);
		$payment_active_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_payment_active_btn_bg_color',true);

		$sf_payment_btn_text_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_btn_text_color',true);
		$sf_payment_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_btn_bg_color',true);
		$sf_payment_active_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_active_btn_bg_color',true);

		$submit_btn_text_color =get_post_meta($post->ID,'_classy_campaign_submit_btn_text_color',true);
		$submit_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_submit_btn_bg_color',true);
		$fields_to_display =get_post_meta($post->ID,'_classy_campaign_fields_to_display',true);
		$submit_btn_label =get_post_meta($post->ID,'_classy_campaign_submit_btn_label',true);
		if(empty($submit_btn_label))
		$submit_btn_label=__('Submit','mittun_classy');

		$default_donation_amt =get_post_meta($post->ID,'_classy_campaign_default_donation_amt',true);
		if(empty($default_donation_amt))
		$default_donation_amt=25;

		$once_btn_text =get_post_meta($post->ID,'_classy_campaign_once_btn_text',true);
		if(empty($once_btn_text))
		$once_btn_text=__('Once','mittun_classy');

		$monthly_btn_text =get_post_meta($post->ID,'_classy_campaign_monthly_btn_text',true);
		if(empty($monthly_btn_text))
		$monthly_btn_text=__('Monthly','mittun_classy');

		$fundraiser_btn_text=get_post_meta($post->ID,'_classy_campaign_fundraiser_btn_text',true);
		$fundraiser_btn_url=get_post_meta($post->ID,'_classy_campaign_fundraiser_btn_url',true);
		$fundraiser_target=get_post_meta($post->ID,'_classy_campaign_fundraiser_target',true);
		$fundraiser_btn_color=get_post_meta($post->ID,'_classy_campaign_fundraiser_btn_color',true);
		$fundraiser_btn_text_color=get_post_meta($post->ID,'_classy_campaign_fundraiser_btn_text_color',true);
		$fundraiser_btn_side=get_post_meta($post->ID,'_classy_campaign_fundraiser_btn_side',true);
		$donate_btn_text=get_post_meta($post->ID,'_classy_campaign_donate_btn_text',true);
		$donate_btn_url=get_post_meta($post->ID,'_classy_campaign_donate_btn_url',true);
		$donate_target=get_post_meta($post->ID,'_classy_campaign_donate_target',true);
		$donate_btn_color=get_post_meta($post->ID,'_classy_campaign_donate_btn_color',true);
		$donate_btn_text_color=get_post_meta($post->ID,'_classy_campaign_donate_btn_text_color',true);
		$donate_btn_side=get_post_meta($post->ID,'_classy_campaign_donate_btn_side',true);

		$display_account_activity=get_post_meta($post->ID,'_classy_campaign_display_account_activity',true);
		$account_activity_type=get_post_meta($post->ID,'_classy_campaign_account_activity_type',true);
		$account_activity_type=empty($account_activity_type)?'all':$account_activity_type;
		$display_activity_title=get_post_meta($post->ID,'_classy_campaign_display_activity_title',true);
		$activity_title=get_post_meta($post->ID,'_classy_campaign_activity_title',true);
		$activity_title=empty($activity_title)?'Activity Feed':$activity_title;
		$display_activity_profile_picture=get_post_meta($post->ID,'_classy_campaign_display_activity_profile_picture',true);
		$account_activity_limit=get_post_meta($post->ID,'_classy_campaign_account_activity_limit',true);
		$account_activity_limit=empty($account_activity_limit)?10:$account_activity_limit;

		$display_donation=get_post_meta($post->ID,'_classy_campaign_display_donation',true);
		$display_donation_type=get_post_meta($post->ID,'_classy_campaign_display_donation_type',true);
		$display_donation_type=empty($display_donation_type)?'all':$display_donation_type;
		$display_donation_title=get_post_meta($post->ID,'_classy_campaign_display_donation_title',true);
		$donation_title=get_post_meta($post->ID,'_classy_campaign_donation_title',true);
		$donation_title=empty($donation_title)?'Donations':$donation_title;
		$donation_limit=get_post_meta($post->ID,'_classy_campaign_donation_limit',true);
		$donation_limit=empty($donation_limit)?10:$donation_limit;


		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id))
		{
			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			$campaigns=array();
			$campaign_first=$classy->get_campaigns(array('aggregates'=>'true','per_page'=>1,'page'=>1,'filter'=>'status=active'));//to get other data i.e. total
			$total_campaign=!empty($campaign_first->total)?$campaign_first->total:0;
			//$per_page=100;//this the max limit
			$per_page = 20; // Temporary 9.18.2018
			if(!empty($total_campaign))
			{
				$pages = ceil($total_campaign / $per_page);
				for($i=1;$i<=$pages;$i++)
				{
					$campaign_per_page=$classy->get_campaigns(array('aggregates'=>'true','per_page'=>$per_page,'page'=>$i,'filter'=>'status=active'));

					if(!empty($campaign_per_page->data)){
						foreach($campaign_per_page->data as $campaign_per_page)
						{
							$campaigns[]=$campaign_per_page;
						}
					}
				}

			}
		}

		?>
		<table class="form-table">
				 <tbody>

                 <!--Start of campaign section-->

				   <tr>
						<th scope="row"><?php _e( 'Select Campaign', 'mittun_classy' ) ?></th>
						<td>
							<select name="_classy_campaign_id" id="_classy_campaign_id">
								<option value=""><?php _e('Select','mittun_classy') ?></option>
								<?php if(!empty($campaigns)){ ?>
									<?php foreach($campaigns as $campaign){ ?>
										<option value="<?php echo $campaign->id;  ?>" <?php selected($campaign->id,$classy_campaign_id,true); ?>><?php echo $campaign->name;  ?></option>
									<?php }?>
								<?php } ?>
							</select>

						</td>
					</tr>

                    <!--End of campaign section-->

                     <!--Start of Skin section-->
                     <tr valign="top">
                        <th scope="row"><?php _e('Layout Type','mittun_classy'); ?></th>
                        <td>
                        <input type="radio" id="skin_1" name="_classy_campaign_skin" value="skin_1" <?php echo ($skin=='skin_1' || empty($skin)?'checked="checked"':''); ?>><?php _e('Original','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" id="skin_2"  name="_classy_campaign_skin" value="skin_2" <?php checked($skin,'skin_2',true); ?>><?php _e('Maverick','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_5"  name="_classy_campaign_skin" value="skin_3" <?php checked($skin,'skin_3',true); ?>><?php _e('Sliding in-out','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_4"  name="_classy_campaign_skin" value="skin_4" <?php checked($skin,'skin_4',true); ?>><?php _e('4 - Coming Soon','mittun_classy'); ?>

                          &nbsp;
                        <input type="radio" id="skin_3"  name="_classy_campaign_skin" value="skin_5" <?php checked($skin,'skin_5',true); ?>><?php _e('5 - Coming Soon','mittun_classy'); ?>
                        </td>

                     </tr>
					 <!--Start of sliding in/out-->
					<tr valign="top"<?php echo ($skin!='skin_3')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Icon','mittun_classy'); ?></th>
                        <td>
                        <input type="text" size="36" name="_classy_campaign_sliding_form_icon" value="<?php echo $sliding_form_icon; ?>" />
						<input class="button button-primary button-large mittun-classy-upload"  type="button" value="<?php _e('Upload Icon','mittun_classy');?>" />
						<span class="mittun-uploaded-snap"><?php if(!empty($sliding_form_icon))echo '<img src="'.$sliding_form_icon.'" style="max-width:50px;max-height:50px;">'; ?></span>
                        </td>
                     </tr>
					 <tr  valign="top"<?php echo ($skin!='skin_3')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Position','mittun_classy'); ?></th>
                        <td>
                        <input type="radio"  name="_classy_campaign_sliding_form_position" value="left" <?php checked($sliding_form_position,'left',true); ?>><?php _e('Top Left','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" name="_classy_campaign_sliding_form_position" value="right" <?php checked($sliding_form_position,'right',true); ?>><?php _e('Top Right','mittun_classy'); ?>
                        </td>
                     </tr>
					 <tr  valign="top"<?php echo ($skin!='skin_3')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Background Color','mittun_classy'); ?></th>
                        <td>
						<input name="_classy_campaign_sliding_form_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sliding_form_bg_color; ?>" />
                        </td>
                     </tr>

					<!--End of sliding in/out-->
					 <tr valign="top" class="sliding-style-end">
                        <th scope="row" class="indent"><?php _e('Theme Style','mittun_classy'); ?></th>
                        <td class="classy-button-set" style="position:relative">

                        <input type="radio" id="style_1" name="_classy_campaign_progress_bar_style" value="style_1" <?php echo ($progress_bar_style=='style_1' || empty($progress_bar_style)?'checked="checked"':''); ?>>
                          <label for="style_1"><?php _e('Style 1','mittun_classy'); ?></label>

                          <input type="radio" id="style_2"  name="_classy_campaign_progress_bar_style" value="style_2" <?php checked($progress_bar_style,'style_2',true); ?>>
                          <label for="style_2" <?php selected($progress_bar_style,'style_1',true); ?>><?php _e('Style 2','mittun_classy'); ?></label>

                        </td>
                     </tr>
					<tr>
						<td colspan="2">
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style1.png" data-rel="style_1" class="mittun-classy-style-sanp"/>
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style2.png" data-rel="style_2" class="mittun-classy-style-sanp"/>
						</td>
					</tr>
                    <!--End of Skin section-->
					<!--Start of custom css class section-->
                     <tr valign="top">
                        <th scope="row"><?php _e('Campaign Parent Class','mittun_classy'); ?></th>
                        <td>
                       <input name="_classy_campaign_css_class" type="text" class="regular-text "  value="<?php echo $css_class; ?>" />
                        </td>
                     </tr>
                    <!--End of custom css class section-->


                     <!--Start of Campaign title section-->

                    <tr>
						<th scope="row"><?php _e('Display Campaign Title','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_campaign_title" id="_classy_campaign_display_campaign_title" type="checkbox" class=""  value="true" <?php checked(!empty($display_campaign_title),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_campaign_title))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Campaign Title Text Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_heading_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $heading_color; ?>" />
						</td>
					 </tr>

                     <!--End of Campaign title section-->


                    <!--Start of Progress bar section-->

					 <tr valign="top" class="display-campaign-title-end">
						<th scope="row"><?php _e('Display Progress Bar','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_progress_bar" id="_classy_campaign_display_progress_bar" type="checkbox" class=""  value="true" <?php checked(!empty($display_progress_bar),true,true); ?>/>
						</td>
					 </tr>

					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_progress_bar_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_color; ?>" />
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Progress Bar Marker Text Color','mittun_classy'); ?></th>
                        <td>
                            <input name="_classy_campaign_progress_bar_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Marker Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_progress_bar_marker_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_marker_color; ?>" />
						</td>
					 </tr>

                     <!--End of Progress bar section-->


					 <!--Start of display goal amount section-->


					 <tr valign="top" class="progress-bar-style-end">
						<th scope="row"><?php _e('Display Goal Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_goal_amount" id="_classy_campaign_display_goal_amount" type="checkbox" class=""  value="true" <?php checked(!empty($display_goal_amount),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_goal_amount))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Goal Amount Text Color','mittun_classy'); ?></th>
                        <td>
                        <?php $goal_amount_color=mittun_classy_get_option('goal_amount_color'); ?>
                        <input name="_classy_campaign_goal_amount_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $goal_amount_text_color; ?>" />
                        </td>
                     </tr>

                     <!--End of display goal amount section-->


                     <!--Start of display amount raised section-->

					 <tr valign="top" class="display-goal-amount-end">
						<th scope="row"><?php _e('Display Amount Raised','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_amount_raised" id="_classy_campaign_display_amount_raised" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Display Donor Count','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_donor_count" id="_classy_campaign_display_donor_count" type="checkbox" class=""  value="true" <?php checked(!empty($display_donor_count),true,true); ?>/>
						</td>
					 </tr>

                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('How should the total be calculated?','mittun_classy'); ?></th>
						<td>
					     <input type="radio" name="_classy_campaign_amount_raised_calculation_type" value="fees_donation"  <?php checked($amount_raised_calculation_type,'fees_donation',true); ?>/><?php _e('Include donation totals AND registration fees','mittun_classy'); ?><br/><br/><input type="radio" name="_classy_campaign_amount_raised_calculation_type" value="only_donation"  <?php checked($amount_raised_calculation_type,'only_donation',true); ?>/><?php _e('Include donation totals only','mittun_classy'); ?>
						</td>
					 </tr>
                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Display Fee Details','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_fee_details" id="_classy_campaign_display_fee_details" type="checkbox" class=""  value="true" <?php checked(!empty($display_fee_details),true,true); ?>/>
						</td>
					 </tr>
                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_raised_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_raised_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Add Custom Heading For Amount Raised','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_amount_raised_heading" id="_classy_campaign_display_amount_raised_heading" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised_heading),true,true); ?>/>
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised_heading) || empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_raised_heading" type="text" class="regular-text"  value="<?php echo $amount_raised_heading; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?> class="amount-raised-heading-style-end">
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Percentage Number','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_amount_raised_percentage_number" id="_classy_campaign_display_amount_raised_percentage_number" type="checkbox" class=""  value="true" <?php checked($display_amount_raised_percentage_number,true,true); ?>/>
                        </td>
                     </tr>
                     <!--End of display amount raised section-->


                     <!--Start of display donation type section-->

					<tr valign="top" class="amount-raised-style-end">
                        <th scope="row"><?php _e('Donation Type','mittun_classy'); ?></th>
                        <td>
                        <input type="radio"  name="_classy_campaign_donation_type" value="form" <?php checked($donation_type,'form',true); ?>><?php _e('Display Form','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" name="_classy_campaign_donation_type" value="fundraise" <?php checked($donation_type,'fundraise',true); ?>><?php _e('Fundraise/Donate','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" name="_classy_campaign_donation_type" value="none" <?php  checked($donation_type,'none',true);?>><?php _e('None','mittun_classy'); ?>
                        </td>
                     </tr>
					 <!--Start of display form section-->

					 <tr valign="top" <?php echo ($donation_type!='form')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Display Form In','mittun_classy'); ?></th>
						<td>
						<input type="radio" name="_classy_campaign_display_form_type" value="inline"  <?php checked($display_form_type,'inline',true); ?>/><?php _e('Inline (embeded)','mittun_classy');?>
                        &nbsp;
						<input type="radio" name="_classy_campaign_display_form_type" value="popup"  <?php checked($display_form_type,'popup',true); ?>/><?php _e('Popup (lightbox)','mittun_classy');?>
						</td>
					 </tr>

					 <!--Start of primary submit button section under Display Form In popup-->
                     <tr valign="top"  <?php echo ($donation_type!='form' || $display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_text" type="text" class="regular-text "  value="<?php echo $primary_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo ($donation_type!='form' || $display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top"<?php echo ($donation_type!='form' || $display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_bg_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"<?php echo ($donation_type!='form' || $display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Top Custom Text','mittun_classy'); ?></th>
                        <td>
                        <textarea name="_classy_campaign_popup_top_text" class="regular-text "><?php echo $popup_top_text; ?></textarea>
                        </td>
                     </tr>
					 <tr valign="top"<?php echo ($donation_type!='form' || $display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Bottom Custom Text','mittun_classy'); ?></th>
                        <td>
                        <textarea name="_classy_campaign_popup_bottom_text" class="regular-text "><?php echo $popup_bottom_text; ?></textarea>
                        </td>
                     </tr>
					 <!--End of primary submit button section under Display Form In popup-->

					<!--Start of Form Type-->
                     <tr valign="top" class="primary-btn-style-end"  <?php echo ($donation_type!='form')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Form Type','mittun_classy'); ?>
                        <div>
                            <i>
                            <?php _e('Short form: User submits by clicking once or monthly buttons','mittun_classy'); ?><br/><?php _e('Long form: User selects once or monthly as an option, and submits by clicking the final submit button after completing the form','mittun_classy'); ?>
                            </i>
                        </div>
                        </th>
                        <td>
                        <input type="radio" name="_classy_campaign_form_type" value="short"  <?php checked($form_type,'short',true); ?>/><?php _e('Short (Once/Recurring buttons only)','mittun_classy');?>
                        &nbsp;
                        <input type="radio" name="_classy_campaign_form_type" value="long"  <?php checked($form_type,'long',true); ?>/><?php _e('Long (all field options)','mittun_classy');?>

                        </td>

                     </tr>
					<!--Start of long form-->
					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row"><?php _e('Enable Set Donation Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_set_donation_amt" id="_classy_campaign_set_donation_amt" type="checkbox"   value="true" <?php checked(!empty($set_donation_amt),true,true) ?>/>
						</td>
					 </tr>
					  <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Donation Amounts','mittun_classy'); ?></th>
						<td>
							<div id="mittun-classy-amount">
							  <div id="mittun-classy-amount-wrapper">
								<p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text" value="<?php echo (!empty($donation_amt[0])?$donation_amt[0]:''); ?>" /></p>
								<?php
								if(!empty($donation_amt)){
									for($i=1;$i<count($donation_amt);$i++)
									{
										if(!empty($donation_amt[$i])){
										?>
										<p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text"  value="<?php echo (!empty($donation_amt[$i])?$donation_amt[$i]:''); ?>" />&nbsp;<a href="javascript:void(0)" class="mittun-classy-amt-remove">X</a></p>
										<?php
										}
									}
								}
								?>
							   </div>
                               <p>
								<input type="button" value="Add New" class="mittun-classy-amt-more button-primary" data-field="_classy_campaign_donation_amt[]" data-container="mittun-classy-amount-wrapper"/>
                                </p>
							</div>
						</td>
					 </tr>
<tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
						<th scope="row"><?php _e('Show Other Amount Button','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_custom_amount_btn" id="_classy_campaign_display_custom_amount_btn" type="checkbox"   value="true" <?php checked(!empty($display_custom_amount_btn),true,true) ?>/>
						</td>
					 </tr>
					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt) || empty($display_custom_amount_btn))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Other Amount Button Custom Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_text" type="text" class="regular-text "  value="<?php echo $amount_btn_text; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" class="custom-amount-btn-style-end" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Buttons Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Active Amount Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_active_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $active_amount_btn_bg_color; ?>" />
                        </td>
                     </tr>

                     <tr valign="top" class="set-amt-style-end" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Buttons Text Color(Once/Monthly)','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Active Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_active_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Form Submit Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_submit_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Form Submit Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_submit_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_bg_color; ?>" />
                        </td>
                     </tr>

					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Fields To Display','mittun_classy'); ?></th>
						<td>
							<?php
							foreach($this->form_fields_arr as $key=>$field)
							{
								echo '<input type="checkbox" name="_classy_campaign_fields_to_display[]" ';
								if(empty($fields_to_display))echo 'checked="checked"';
								else if(!empty($fields_to_display) && in_array($key,$fields_to_display))echo 'checked="checked"';
								echo 'value="'.$key.'" />'.$field.'&nbsp;&nbsp;';
							}

							?>
						</td>
					 </tr>
					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Submit Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_submit_btn_label" value="<?php echo $submit_btn_label; ?>" />
						</td>
					 </tr>
					 <!--End of long form-->
					 <!--Start of short form-->
					 <tr class="display-form-long-style-end" valign="top" <?php echo ($donation_type!='form' || $form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Active Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_active_btn_bg_color; ?>" />
                        </td>
                     </tr>

					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='long')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Monthly Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_monthly_btn_text" value="<?php echo $monthly_btn_text; ?>" />
						</td>
					 </tr>

					 <tr valign="top" <?php echo ($donation_type!='form' || $form_type=='long')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Once Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_once_btn_text" value="<?php echo $once_btn_text; ?>" />
						</td>
					 </tr>
					 <!--End of short form-->
					 <!--End of Form Type-->
					 <tr valign="top" class="display-form-short-style-end" <?php echo ($donation_type!='form')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Default Donation Amount','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_default_donation_amt" value="<?php echo $default_donation_amt; ?>" />
						</td>
					 </tr>
					 <tr valign="top" class="display-checkout-url-override" style="background:#999;opacity:0.5;<?php echo ($donation_type!='form')?'display:none;':''; ?>" >
                        <th scope="row" class="indent"><?php _e('Custom Checkout URL Override','mittun_classy'); ?></th>
                        <td>
                         <input name="_classy_campaign_display_custom_checkout_url" id="_classy_campaign_display_custom_checkout_url" type="checkbox"   value="true" <?php checked(!empty($display_custom_checkout_url),true,true) ?>/>
                        </td>
                     </tr>
					 <tr valign="top" class="display-checkout-url-override" style="background:#999;opacity:0.5;<?php echo ($donation_type!='form' || empty($display_custom_checkout_url))?'display:none;':''; ?>" >
                        <th scope="row" class="indent"><u><?php _e('Custom URL Override','mittun_classy'); ?></u><br/><i><?php _e('Use this to override campaign checkout URL','mittun_classy') ?></i></th>
                        <td>
                        <input name="_classy_campaign_custom_checkout_url" type="text" class="regular-text "  value="<?php echo $custom_checkout_url; ?>" />
                        </td>
                     </tr>
					 <!--End of display form section-->
					 <!--Start of fundraise/donation section-->

					 <tr valign="top" class="display-form-style-end"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Fundraise Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_fundraiser_btn_text" type="text" class="regular-text "  value="<?php echo $fundraiser_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Fundraise Button URL','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_fundraiser_btn_url" type="text" class="regular-text "  value="<?php echo $fundraiser_btn_url; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Open In New Tab','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_fundraiser_target" type="checkbox"  value="true" <?php checked(!empty($fundraiser_target),true,true) ?> />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Fundraiser Button Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_fundraiser_btn_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $fundraiser_btn_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Fundraiser Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_fundraiser_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $fundraiser_btn_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Float Right/Left','mittun_classy'); ?></th>
                        <td>

						<input type="radio" name="_classy_campaign_fundraiser_btn_side" value="left"  <?php checked($fundraiser_btn_side,'left',true); ?>/><?php _e('Left','mittun_classy');?>
                        &nbsp;
						<input type="radio" name="_classy_campaign_fundraiser_btn_side" value="right"  <?php checked($fundraiser_btn_side,'right',true); ?>/><?php _e('Right','mittun_classy');?>
                        </td>
                     </tr>

					 <tr valign="top"   <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Donate Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donate_btn_text" type="text" class="regular-text "  value="<?php echo $donate_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Donate Button URL','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donate_btn_url" type="text" class="regular-text "  value="<?php echo $donate_btn_url; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Open In New Tab','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donate_target" type="checkbox"  value="true" <?php checked(!empty($donate_target),true,true) ?> />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Donate Button Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donate_btn_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $donate_btn_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Donate Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donate_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $donate_btn_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo ($donation_type!='fundraise' )?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Float Right/Left','mittun_classy'); ?></th>
                        <td>

						<input type="radio" name="_classy_campaign_donate_btn_side" value="left"  <?php checked($donate_btn_side,'left',true); ?>/><?php _e('Left','mittun_classy');?>
                        &nbsp;
						<input type="radio" name="_classy_campaign_donate_btn_side" value="right"  <?php checked($donate_btn_side,'right',true); ?>/><?php _e('Right','mittun_classy');?>
                        </td>
                     </tr>
					 <!--End of fundraise/donation section-->

					 <!--End of display donation type section-->

					 <tr valign="top" class="donation-type-style-end">
                        <th scope="row"><?php _e('Activity Feed','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_account_activity" id="_classy_campaign_display_account_activity" type="checkbox"   value="true" <?php checked(!empty($display_account_activity),true,true) ?>/>
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_account_activity))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Activity Feed Type','mittun_classy'); ?></th>
                        <td>
                        <input type="radio" name="_classy_campaign_account_activity_type" value="all"  <?php checked($account_activity_type,'all',true); ?>/><?php _e('Display All Activity','mittun_classy');?>
                        &nbsp;
                        <input type="radio" name="_classy_campaign_account_activity_type" value="donation"  <?php checked($account_activity_type,'donation',true); ?>/><?php _e('Display Donation Activity Only','mittun_classy');?>

                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_account_activity))?'style="display:none"':''; ?>>
                        <th scope="row"><?php _e('Show Campaign Activity Title','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_activity_title" id="_classy_campaign_display_activity_title" type="checkbox"   value="true" <?php checked(!empty($display_activity_title),true,true) ?>/>
                        </td>
                     </tr>

					 <tr valign="top" <?php echo (empty($display_activity_title))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Custom Title Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_activity_title" type="text" class="regular-text"  value="<?php echo $activity_title; ?>" />
                        </td>
                     </tr>
					  <tr valign="top" class="display-activity-title-style-end" <?php echo (empty($display_account_activity))?'style="display:none"':''; ?>>
                        <th scope="row"><?php _e('Display Profile Picture','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_activity_profile_picture" id="_classy_campaign_display_activity_profile_picture" type="checkbox"   value="true" <?php checked($display_activity_profile_picture,true,true) ?>/>
                        </td>
                     </tr>
					 <tr valign="top"  <?php echo (empty($display_account_activity))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Number Of Items To Display','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_account_activity_limit" id="_classy_campaign_account_activity_limit" type="number"  min="1" max="100" value="<?php echo $account_activity_limit; ?>" />
                        </td>
                     </tr>
                      <!--End of activity section-->

					 <tr valign="top" class="display-activity-style-end">
                        <th scope="row"><?php _e('Donation List','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_donation" id="_classy_campaign_display_donation" type="checkbox"   value="true" <?php checked(!empty($display_donation),true,true) ?>/>
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_donation))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Donation Type','mittun_classy'); ?></th>
                        <td>
                        <input type="radio" name="_classy_campaign_display_donation_type" value="all"  <?php checked($display_donation_type,'all',true); ?>/><?php _e('Display All Donation','mittun_classy');?>
                        &nbsp;
                        <input type="radio" name="_classy_campaign_display_donation_type" value="offline"  <?php checked($display_donation_type,'offline',true); ?>/><?php _e('Offline Donation Only','mittun_classy');?>

                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_donation))?'style="display:none"':''; ?>>
                        <th scope="row"><?php _e('Show Donation Title','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_display_donation_title" id="_classy_campaign_display_donation_title" type="checkbox"   value="true" <?php checked(!empty($display_donation_title),true,true) ?>/>
                        </td>
                     </tr>

					 <tr valign="top" <?php echo (empty($display_donation_title))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Custom Title Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donation_title" type="text" class="regular-text"  value="<?php echo $donation_title; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" class="display-donation-title-style-end"  <?php echo (empty($display_donation))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Number Of Items To Display','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_donation_limit" id="_classy_campaign_donation_limit" type="number"  min="1" max="100" value="<?php echo $donation_limit; ?>" />
                        </td>
                     </tr>


					 <tr valign="top" class="display-donation-style-end">
						<th scope="row">&nbsp;</th>
						<td>
						<div id="mittun_classy_shortcode">
						<?php
						echo '[mittun_classy ';
						echo 'id="'.$post->ID.'"] ';
						?>
						</div>
						<br/>
						<input type="button" value="<?php _e('Copy To Clipboard') ?>" class="mittun-classy-copy button-primary" />
						</td>
					 </tr>
                     <tr valign="top">
					<th scope="row">&nbsp;</th>
                        <td  style="text-decoration:none;">
						<span>
						<?php
						if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
							?>
							<input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />							<?php
						}
						else{
							?>
							<input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
							<?php

						}
						?>
						</span>
						<span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:none;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
						</td>
                     </tr>


				</tbody>
			  </table>
		<?php
	}
	function mittun_classy_campaign_save_meta_data($post_id)
	{
		global $wpdb;
		if ( ! isset( $_POST['mittun_classy_campaign_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mittun_classy_campaign_meta_nonce'], 'mittun_classy_campaign_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		$classy_campaign_id=$_POST['_classy_campaign_id'];
		$classy_campaign_skin=$_POST['_classy_campaign_skin'];
		$classy_campaign_sliding_form_icon=$_POST['_classy_campaign_sliding_form_icon'];
		$classy_campaign_sliding_form_position=$_POST['_classy_campaign_sliding_form_position'];
		$classy_campaign_sliding_form_bg_color=$_POST['_classy_campaign_sliding_form_bg_color'];
		$classy_campaign_css_class=$_POST['_classy_campaign_css_class'];
		$classy_campaign_display_campaign_title=!empty($_POST['_classy_campaign_display_campaign_title'])?true:false;
		$classy_campaign_display_progress_bar=!empty($_POST['_classy_campaign_display_progress_bar'])?true:false;
		$classy_campaign_progress_bar_style=$_POST['_classy_campaign_progress_bar_style'];
		$classy_campaign_progress_bar_color=$_POST['_classy_campaign_progress_bar_color'];
		$classy_campaign_progress_bar_text_color=$_POST['_classy_campaign_progress_bar_text_color'];
		$classy_campaign_progress_bar_marker_color=$_POST['_classy_campaign_progress_bar_marker_color'];


		$classy_campaign_primary_btn_text=$_POST['_classy_campaign_primary_btn_text'];
		$classy_campaign_primary_btn_text_color=$_POST['_classy_campaign_primary_btn_text_color'];
		$classy_campaign_primary_btn_bg_color=$_POST['_classy_campaign_primary_btn_bg_color'];
		$classy_campaign_popup_top_text=$_POST['_classy_campaign_popup_top_text'];
		$classy_campaign_popup_bottom_text=$_POST['_classy_campaign_popup_bottom_text'];

		$classy_campaign_heading_color=$_POST['_classy_campaign_heading_color'];
		$classy_campaign_display_goal_amount=!empty($_POST['_classy_campaign_display_goal_amount'])?true:false;
		$classy_campaign_goal_amount_text_color=$_POST['_classy_campaign_goal_amount_text_color'];

		$classy_campaign_display_amount_raised=!empty($_POST['_classy_campaign_display_amount_raised'])?true:false;
		$classy_campaign_display_donor_count=!empty($_POST['_classy_campaign_display_donor_count'])?true:false;
		$classy_campaign_display_fee_details=!empty($_POST['_classy_campaign_display_fee_details'])?true:false;
		$classy_campaign_amount_raised_text_color=$_POST['_classy_campaign_amount_raised_text_color'];
		$classy_campaign_amount_raised_calculation_type=$_POST['_classy_campaign_amount_raised_calculation_type'];
		$classy_campaign_display_amount_raised_heading=!empty($_POST['_classy_campaign_display_amount_raised_heading'])?true:false;
		$classy_campaign_amount_raised_heading=$_POST['_classy_campaign_amount_raised_heading'];
		$classy_campaign_display_amount_raised_percentage_number=!empty($_POST['_classy_campaign_display_amount_raised_percentage_number'])?true:false;
		$classy_campaign_donation_type=$_POST['_classy_campaign_donation_type'];
		$classy_campaign_display_form_type=$_POST['_classy_campaign_display_form_type'];
		$classy_campaign_display_custom_checkout_url=!empty($_POST['_classy_campaign_display_custom_checkout_url'])?true:false;
		$classy_campaign_custom_checkout_url=$_POST['_classy_campaign_custom_checkout_url'];
		$classy_campaign_form_type=$_POST['_classy_campaign_form_type'];
		$classy_campaign_set_donation_amt=$_POST['_classy_campaign_set_donation_amt'];

		$classy_campaign_display_custom_amount_btn=$_POST['_classy_campaign_display_custom_amount_btn'];
		$classy_campaign_amount_btn_text=$_POST['_classy_campaign_amount_btn_text'];
		$classy_campaign_amount_btn_text_color=$_POST['_classy_campaign_amount_btn_text_color'];
		$classy_campaign_amount_btn_bg_color=$_POST['_classy_campaign_amount_btn_bg_color'];
		$classy_campaign_active_amount_btn_bg_color=$_POST['_classy_campaign_active_amount_btn_bg_color'];
		$classy_campaign_donation_amt=$_POST['_classy_campaign_donation_amt'];


		$classy_campaign_payment_btn_text_color=$_POST['_classy_campaign_payment_btn_text_color'];
		$classy_campaign_payment_btn_bg_color=$_POST['_classy_campaign_payment_btn_bg_color'];
		$classy_campaign_payment_active_btn_bg_color=$_POST['_classy_campaign_payment_active_btn_bg_color'];
		$classy_campaign_submit_btn_text_color=$_POST['_classy_campaign_submit_btn_text_color'];
		$classy_campaign_submit_btn_bg_color=$_POST['_classy_campaign_submit_btn_bg_color'];

		$classy_campaign_sf_payment_btn_text_color=$_POST['_classy_campaign_sf_payment_btn_text_color'];
		$classy_campaign_sf_payment_btn_bg_color=$_POST['_classy_campaign_sf_payment_btn_bg_color'];
		$classy_campaign_sf_payment_active_btn_bg_color=$_POST['_classy_campaign_sf_payment_active_btn_bg_color'];

		$classy_campaign_fields_to_display=$_POST['_classy_campaign_fields_to_display'];
		$classy_campaign_submit_btn_label=$_POST['_classy_campaign_submit_btn_label'];
		$classy_campaign_default_donation_amt=$_POST['_classy_campaign_default_donation_amt'];
		$classy_campaign_once_btn_text=$_POST['_classy_campaign_once_btn_text'];
		$classy_campaign_monthly_btn_text=$_POST['_classy_campaign_monthly_btn_text'];

		$classy_campaign_fundraiser_btn_text=$_POST['_classy_campaign_fundraiser_btn_text'];
		$classy_campaign_fundraiser_btn_url=$_POST['_classy_campaign_fundraiser_btn_url'];
		$classy_campaign_fundraiser_target=!empty($_POST['_classy_campaign_fundraiser_target'])?true:false;
		$classy_campaign_fundraiser_btn_color=$_POST['_classy_campaign_fundraiser_btn_color'];
		$classy_campaign_fundraiser_btn_text_color=$_POST['_classy_campaign_fundraiser_btn_text_color'];
		$classy_campaign_fundraiser_btn_side=$_POST['_classy_campaign_fundraiser_btn_side'];

		$classy_campaign_donate_btn_text=$_POST['_classy_campaign_donate_btn_text'];
		$classy_campaign_donate_btn_url=$_POST['_classy_campaign_donate_btn_url'];
		$classy_campaign_donate_target=!empty($_POST['_classy_campaign_donate_target'])?true:false;
		$classy_campaign_donate_btn_color=$_POST['_classy_campaign_donate_btn_color'];
		$classy_campaign_donate_btn_text_color=$_POST['_classy_campaign_donate_btn_text_color'];
		$classy_campaign_donate_btn_side=$_POST['_classy_campaign_donate_btn_side'];


		$classy_campaign_display_account_activity=!empty($_POST['_classy_campaign_display_account_activity'])?true:false;
		$classy_campaign_account_activity_type=$_POST['_classy_campaign_account_activity_type'];
		$classy_campaign_display_activity_title=!empty($_POST['_classy_campaign_display_activity_title'])?true:false;
		$classy_campaign_activity_title=$_POST['_classy_campaign_activity_title'];
		$classy_campaign_display_activity_profile_picture=!empty($_POST['_classy_campaign_display_activity_profile_picture'])?true:false;
		$classy_campaign_account_activity_limit=$_POST['_classy_campaign_account_activity_limit'];

		$classy_campaign_display_donation=!empty($_POST['_classy_campaign_display_donation'])?true:false;
		$classy_campaign_display_donation_type=$_POST['_classy_campaign_display_donation_type'];
		$classy_campaign_display_donation_title=!empty($_POST['_classy_campaign_display_donation_title'])?true:false;
		$classy_campaign_donation_title=$_POST['_classy_campaign_donation_title'];
		$classy_campaign_donation_limit=$_POST['_classy_campaign_donation_limit'];



		update_post_meta($post_id,'_classy_campaign_skin',$classy_campaign_skin);
		update_post_meta($post_id,'_classy_campaign_sliding_form_icon',$classy_campaign_sliding_form_icon);
		update_post_meta($post_id,'_classy_campaign_sliding_form_position',$classy_campaign_sliding_form_position);
		update_post_meta($post_id,'_classy_campaign_sliding_form_bg_color',$classy_campaign_sliding_form_bg_color);
		update_post_meta($post_id,'_classy_campaign_id',$classy_campaign_id);
		update_post_meta($post_id,'_classy_campaign_css_class',$classy_campaign_css_class);
		update_post_meta($post_id,'_classy_campaign_display_campaign_title',$classy_campaign_display_campaign_title);
		update_post_meta($post_id,'_classy_campaign_display_progress_bar',$classy_campaign_display_progress_bar);
		update_post_meta($post_id,'_classy_campaign_progress_bar_style',$classy_campaign_progress_bar_style);
		update_post_meta($post_id,'_classy_campaign_progress_bar_color',$classy_campaign_progress_bar_color);
		update_post_meta($post_id,'_classy_campaign_progress_bar_text_color',$classy_campaign_progress_bar_text_color);
		update_post_meta($post_id,'_classy_campaign_progress_bar_marker_color',$classy_campaign_progress_bar_marker_color);

		update_post_meta($post_id,'_classy_campaign_primary_btn_text',$classy_campaign_primary_btn_text);
		update_post_meta($post_id,'_classy_campaign_primary_btn_text_color',$classy_campaign_primary_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_primary_btn_bg_color',$classy_campaign_primary_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_popup_top_text',$classy_campaign_popup_top_text);
		update_post_meta($post_id,'_classy_campaign_popup_bottom_text',$classy_campaign_popup_bottom_text);
		update_post_meta($post_id,'_classy_campaign_heading_color',$classy_campaign_heading_color);
		update_post_meta($post_id,'_classy_campaign_display_goal_amount',$classy_campaign_display_goal_amount);
		update_post_meta($post_id,'_classy_campaign_goal_amount_text_color',$classy_campaign_goal_amount_text_color);
		update_post_meta($post_id,'_classy_campaign_display_amount_raised',$classy_campaign_display_amount_raised);
		update_post_meta($post_id,'_classy_campaign_display_donor_count',$classy_campaign_display_donor_count);
		update_post_meta($post_id,'_classy_campaign_display_fee_details',$classy_campaign_display_fee_details);
		update_post_meta($post_id,'_classy_campaign_amount_raised_calculation_type',$classy_campaign_amount_raised_calculation_type);
		update_post_meta($post_id,'_classy_campaign_amount_raised_text_color',$classy_campaign_amount_raised_text_color);



		update_post_meta($post_id,'_classy_campaign_display_amount_raised_heading',$classy_campaign_display_amount_raised_heading);
		update_post_meta($post_id,'_classy_campaign_amount_raised_heading',$classy_campaign_amount_raised_heading);
		update_post_meta($post_id,'_classy_campaign_display_amount_raised_percentage_number',$classy_campaign_display_amount_raised_percentage_number);

		update_post_meta($post_id,'_classy_campaign_donation_type',$classy_campaign_donation_type);
		update_post_meta($post_id,'_classy_campaign_display_form_type',$classy_campaign_display_form_type);
		update_post_meta($post_id,'_classy_campaign_set_donation_amt',$classy_campaign_set_donation_amt);
		update_post_meta($post_id,'_classy_campaign_display_custom_checkout_url',$classy_campaign_display_custom_checkout_url);
		update_post_meta($post_id,'_classy_campaign_custom_checkout_url',$classy_campaign_custom_checkout_url);
		update_post_meta($post_id,'_classy_campaign_form_type',$classy_campaign_form_type);
		update_post_meta($post_id,'_classy_campaign_display_custom_amount_btn',$classy_campaign_display_custom_amount_btn);
		update_post_meta($post_id,'_classy_campaign_amount_btn_text',$classy_campaign_amount_btn_text);
		update_post_meta($post_id,'_classy_campaign_amount_btn_text_color',$classy_campaign_amount_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_amount_btn_bg_color',$classy_campaign_amount_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_active_amount_btn_bg_color',$classy_campaign_active_amount_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_donation_amt',$classy_campaign_donation_amt);
		update_post_meta($post_id,'_classy_campaign_payment_btn_text_color',$classy_campaign_payment_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_payment_btn_bg_color',$classy_campaign_payment_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_payment_active_btn_bg_color',$classy_campaign_payment_active_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_text_color',$classy_campaign_submit_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_bg_color',$classy_campaign_submit_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_fields_to_display',$classy_campaign_fields_to_display);
		update_post_meta($post_id,'_classy_campaign_sf_payment_btn_text_color',$classy_campaign_sf_payment_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_sf_payment_btn_bg_color',$classy_campaign_sf_payment_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_sf_payment_active_btn_bg_color',$classy_campaign_sf_payment_active_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_label',$classy_campaign_submit_btn_label);
		update_post_meta($post_id,'_classy_campaign_default_donation_amt',$classy_campaign_default_donation_amt);
		update_post_meta($post_id,'_classy_campaign_once_btn_text',$classy_campaign_once_btn_text);
		update_post_meta($post_id,'_classy_campaign_monthly_btn_text',$classy_campaign_monthly_btn_text);

		update_post_meta($post_id,'_classy_campaign_fundraiser_btn_text',$classy_campaign_fundraiser_btn_text);
		update_post_meta($post_id,'_classy_campaign_fundraiser_btn_url',$classy_campaign_fundraiser_btn_url);
		update_post_meta($post_id,'_classy_campaign_fundraiser_target',$classy_campaign_fundraiser_target);
		update_post_meta($post_id,'_classy_campaign_fundraiser_btn_color',$classy_campaign_fundraiser_btn_color);
		update_post_meta($post_id,'_classy_campaign_fundraiser_btn_text_color',$classy_campaign_fundraiser_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_fundraiser_btn_side',$classy_campaign_fundraiser_btn_side);
		update_post_meta($post_id,'_classy_campaign_donate_btn_text',$classy_campaign_donate_btn_text);
		update_post_meta($post_id,'_classy_campaign_donate_btn_url',$classy_campaign_donate_btn_url);
		update_post_meta($post_id,'_classy_campaign_donate_target',$classy_campaign_donate_target);
		update_post_meta($post_id,'_classy_campaign_donate_btn_color',$classy_campaign_donate_btn_color);
		update_post_meta($post_id,'_classy_campaign_donate_btn_text_color',$classy_campaign_donate_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_donate_btn_side',$classy_campaign_donate_btn_side);

		update_post_meta($post_id,'_classy_campaign_display_account_activity',$classy_campaign_display_account_activity);
		update_post_meta($post_id,'_classy_campaign_account_activity_type',$classy_campaign_account_activity_type);
		update_post_meta($post_id,'_classy_campaign_display_activity_title',$classy_campaign_display_activity_title);
		update_post_meta($post_id,'_classy_campaign_activity_title',$classy_campaign_activity_title);
		update_post_meta($post_id,'_classy_campaign_display_activity_profile_picture',$classy_campaign_display_activity_profile_picture);
		update_post_meta($post_id,'_classy_campaign_account_activity_limit',$classy_campaign_account_activity_limit);

		update_post_meta($post_id,'_classy_campaign_display_donation',$classy_campaign_display_donation);
		update_post_meta($post_id,'_classy_campaign_display_donation_type',$classy_campaign_display_donation_type);
		update_post_meta($post_id,'_classy_campaign_display_donation_title',$classy_campaign_display_donation_title);
		update_post_meta($post_id,'_classy_campaign_donation_title',$classy_campaign_donation_title);
		update_post_meta($post_id,'_classy_campaign_donation_limit',$classy_campaign_donation_limit);

	}

	function mittun_classy_multicampaign_meta_callback($post)
	{
		wp_nonce_field( 'mittun_classy_combined_campaign_meta', 'mittun_classy_combined_campaign_meta_nonce' );
		global $post_type;
		$campaign_ids =(array) get_post_meta($post->ID,'_classy_combined_campaign_ids',true);
		$skin = get_post_meta($post->ID,'_classy_combined_campaign_skin',true);
		$skin =!empty($skin)?$skin:mittun_classy_get_option('skin','mittun_classy_color');
		$css_class = get_post_meta($post->ID,'_classy_combined_campaign_css_class',true);
		$display_campaign_title = get_post_meta($post->ID,'_classy_combined_campaign_display_campaign_title',true);

		$display_progress_bar = get_post_meta($post->ID,'_classy_combined_campaign_display_progress_bar',true);
		$progress_bar_style = get_post_meta($post->ID,'_classy_combined_campaign_progress_bar_style',true);
		$progress_bar_style =!empty($progress_bar_style)?$progress_bar_style:mittun_classy_get_option('progress_bar_style','mittun_classy_color');
		$progress_bar_color=get_post_meta($post->ID,'_classy_combined_campaign_progress_bar_color',true);
		$progress_bar_text_color=get_post_meta($post->ID,'_classy_combined_campaign_progress_bar_text_color',true);
		$progress_bar_marker_color=get_post_meta($post->ID,'_classy_combined_campaign_progress_bar_marker_color',true);
		$heading_color=get_post_meta($post->ID,'_classy_combined_campaign_heading_color',true);

		$display_goal_amount = get_post_meta($post->ID,'_classy_combined_campaign_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($post->ID,'_classy_combined_campaign_goal_amount_text_color',true);


		$display_amount_raised =get_post_meta($post->ID,'_classy_combined_campaign_display_amount_raised',true);
		$display_donor_count =get_post_meta($post->ID,'_classy_combined_campaign_display_donor_count',true);
		$amount_raised_calculation_type =get_post_meta($post->ID,'_classy_combined_campaign_amount_raised_calculation_type',true);
		if(empty($amount_raised_calculation_type))
		$amount_raised_calculation_type='only_donation';
		$display_fee_details = get_post_meta($post->ID,'_classy_combined_campaign_display_fee_details',true);
		$amount_raised_text_color = get_post_meta($post->ID,'_classy_combined_campaign_amount_raised_text_color',true);
		$display_amount_raised_heading =get_post_meta($post->ID,'_classy_combined_campaign_display_amount_raised_heading',true);
		$amount_raised_heading =get_post_meta($post->ID,'_classy_combined_campaign_amount_raised_heading',true);
		if(empty($amount_raised_heading))
		$amount_raised_heading=__('FUNDRAISING PROGRESS:','mittun_classy');
		$display_amount_raised_percentage_number =get_post_meta($post->ID,'_classy_combined_campaign_display_amount_raised_percentage_number',true);


		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id))
		{
			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			$campaigns=array();
			$campaign_first=$classy->get_campaigns(array('aggregates'=>'true', 'per_page'=>1,'page'=>1,'filter'=>'status=active'));//to get other data i.e. total
			$total_campaign=!empty($campaign_first->total)?$campaign_first->total:0;
			//$per_page=100;//this the max limit
			$per_page = 20; // Temporary added 9.18.2018
			if(!empty($total_campaign))
			{
				$pages = ceil($total_campaign / $per_page);
				for($i=1;$i<=$pages;$i++)
				{
					$campaign_per_page=$classy->get_campaigns(array('aggregates'=>'true', 'per_page'=>$per_page,'page'=>$i,'filter'=>'status=active'));

					if(!empty($campaign_per_page->data)){
						foreach($campaign_per_page->data as $campaign_per_page)
						{
							$campaigns[]=$campaign_per_page;
						}
					}
				}

			}
		}

		?>

		<table class="form-table">
				 <tbody>

				   <tr>
						<th scope="row"><?php _e( 'Select Campaign', 'mittun_classy' ) ?></th>
						<td>
							<select name="_classy_combined_campaign_ids[]" data-placeholder="<?php _e('Select Campaign','mittun_classy') ?>" multiple class="chosen-select chosen-select-width" tabindex="16">
								<option value=""><?php _e('Select Campaign','mittun_classy') ?></option>
								<?php
								if(!empty($campaigns)) {
									foreach($campaigns as $campaign) {

									?>
									<option value="<?php echo $campaign->id;?>" <?php echo in_array($campaign->id,$campaign_ids)?'selected="selected"':''; ?>><?php echo $campaign->name;?></option>
									<?php

									}

								}
								?>

							</select>

						</td>
					</tr>

					 <!--Start of Skin section-->
                     <tr valign="top">
                        <th scope="row"><?php _e('Layout Type','mittun_classy'); ?></th>
                        <td>
                        <input type="radio" id="skin_1" name="_classy_combined_campaign_skin" value="skin_1" <?php echo ($skin=='skin_1' || empty($skin)?'checked="checked"':''); ?>><?php _e('Original','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" id="skin_2"  name="_classy_combined_campaign_skin" value="skin_2" <?php checked($skin,'skin_2',true); ?>><?php _e('Maverick','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_3"  name="_classy_combined_campaign_skin" value="skin_3" <?php checked($skin,'skin_3',true); ?>><?php _e('Style 3','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_4"  name="_classy_combined_campaign_skin" value="skin_4" <?php checked($skin,'skin_4',true); ?>><?php _e('Style 4','mittun_classy'); ?>

                        </td>

                     </tr>
					 <tr valign="top">
                        <th scope="row" class="indent"><?php _e('Theme Style','mittun_classy'); ?></th>
                        <td class="classy-button-set" style="position:relative">

                        <input type="radio" id="style_1" name="_classy_combined_campaign_progress_bar_style" value="style_1" <?php echo ($progress_bar_style=='style_1' || empty($progress_bar_style)?'checked="checked"':''); ?>>
                          <label for="style_1"><?php _e('Style 1','mittun_classy'); ?></label>

                          <input type="radio" id="style_2"  name="_classy_combined_campaign_progress_bar_style" value="style_2" <?php checked($progress_bar_style,'style_2',true); ?>>
                          <label for="style_2" <?php selected($progress_bar_style,'style_1',true); ?>><?php _e('Style 2','mittun_classy'); ?></label>

                        </td>
                     </tr>
					<tr>
						<td colspan="2">
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style1.png" data-rel="style_1" class="mittun-classy-style-sanp"/>
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style2.png" data-rel="style_2" class="mittun-classy-style-sanp"/>
						</td>
					</tr>
                    <!--End of Skin section-->
					<!--Start of custom css class section-->
                     <tr valign="top">
                        <th scope="row"><?php _e('Campaign Parent Class','mittun_classy'); ?></th>
                        <td>
                       <input name="_classy_combined_campaign_css_class" type="text" class="regular-text "  value="<?php echo $css_class; ?>" />
                        </td>
                     </tr>
                    <!--End of custom css class section-->


                     <!--Start of Campaign title section-->

                    <tr>
						<th scope="row"><?php _e('Display Campaign Title','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_campaign_title" id="_classy_combined_campaign_display_campaign_title" type="checkbox" class=""  value="true" <?php checked(!empty($display_campaign_title),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_campaign_title))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Campaign Title Text Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_heading_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $heading_color; ?>" />
						</td>
					 </tr>

                     <!--End of Campaign title section-->


                    <!--Start of Progress bar section-->

					 <tr valign="top" class="display-campaign-title-end">
						<th scope="row"><?php _e('Display Progress Bar','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_progress_bar" id="_classy_combined_campaign_display_progress_bar" type="checkbox" class=""  value="true" <?php checked(!empty($display_progress_bar),true,true); ?>/>
						</td>
					 </tr>

					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_progress_bar_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_color; ?>" />
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Progress Bar Marker Text Color','mittun_classy'); ?></th>
                        <td>
                            <input name="_classy_combined_campaign_progress_bar_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Marker Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_progress_bar_marker_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_marker_color; ?>" />
						</td>
					 </tr>

                     <!--End of Progress bar section-->


					 <!--Start of display goal amount section-->


					 <tr valign="top" class="progress-bar-style-end">
						<th scope="row"><?php _e('Display Goal Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_goal_amount" id="_classy_campaign_display_goal_amount" type="checkbox" class=""  value="true" <?php checked(!empty($display_goal_amount),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_goal_amount))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Goal Amount Text Color','mittun_classy'); ?></th>
                        <td>
                        <?php $goal_amount_color=mittun_classy_get_option('goal_amount_color'); ?>
                        <input name="_classy_combined_campaign_goal_amount_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $goal_amount_text_color; ?>" />
                        </td>
                     </tr>

                     <!--End of display goal amount section-->


                     <!--Start of display amount raised section-->

					 <tr valign="top" class="display-goal-amount-end">
						<th scope="row"><?php _e('Display Amount Raised','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_amount_raised" id="_classy_campaign_display_amount_raised" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Display Donor Count','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_donor_count" id="_classy_campaign_display_donor_count" type="checkbox" class=""  value="true" <?php checked(!empty($display_donor_count),true,true); ?>/>
						</td>
					 </tr>

                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('How should the total be calculated?','mittun_classy'); ?></th>
						<td>
					     <input type="radio" name="_classy_combined_campaign_amount_raised_calculation_type" value="fees_donation"  <?php checked($amount_raised_calculation_type,'fees_donation',true); ?>/><?php _e('Include donation totals AND registration fees','mittun_classy'); ?><br/><br/><input type="radio" name="_classy_campaign_amount_raised_calculation_type" value="only_donation"  <?php checked($amount_raised_calculation_type,'only_donation',true); ?>/><?php _e('Include donation totals only','mittun_classy'); ?>
						</td>
					 </tr>
                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Display Fee Details','mittun_classy'); ?></th>
						<td>
						<input name="_classy_combined_campaign_display_fee_details" id="_classy_campaign_display_fee_details" type="checkbox" class=""  value="true" <?php checked(!empty($display_fee_details),true,true); ?>/>
						</td>
					 </tr>
                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_combined_campaign_amount_raised_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_raised_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Add Custom Heading For Amount Raised','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_combined_campaign_display_amount_raised_heading" id="_classy_campaign_display_amount_raised_heading" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised_heading),true,true); ?>/>
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised_heading) || empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_combined_campaign_amount_raised_heading" type="text" class="regular-text"  value="<?php echo $amount_raised_heading; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?> class="amount-raised-heading-style-end">
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Percentage Number','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_combined_campaign_display_amount_raised_percentage_number" id="_classy_campaign_display_amount_raised_percentage_number" type="checkbox" class=""  value="true" <?php checked($display_amount_raised_percentage_number,true,true); ?>/>
                        </td>
                     </tr>
                     <!--End of display amount raised section-->



					<tr valign="top" class="amount-raised-style-end">
						<th scope="row">&nbsp;</th>
						<td>
						<div id="mittun_classy_shortcode">
						<?php
						echo '[mittun_classy_combined_campaign ';
						echo 'id="'.$post->ID.'"] ';
						?>

						</div>
						<br/>
						<input type="button" value="<?php _e('Copy To Clipboard') ?>" class="mittun-classy-copy button-primary" />
						</td>
					 </tr>
                     <tr valign="top">
                        <th scope="row">&nbsp;</th>
                         <td  style="text-decoration:none;">
						<span>
						<?php
						if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
							?>
							<input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />							<?php
						}
						else{
							?>
							<input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
							<?php

						}
						?>
						</span>
						<span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:none;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
						</td>
                     </tr>
				</tbody>
		</table>
		<?php
	}

	function mittun_classy_combined_campaign_save_meta_data($post_id)
	{
		if ( ! isset( $_POST['mittun_classy_combined_campaign_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mittun_classy_combined_campaign_meta_nonce'], 'mittun_classy_combined_campaign_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$classy_combined_campaign_ids=$_POST['_classy_combined_campaign_ids'];


		$classy_combined_campaign_skin=$_POST['_classy_combined_campaign_skin'];
		$classy_combined_campaign_css_class=$_POST['_classy_combined_campaign_css_class'];
		$classy_combined_campaign_display_campaign_title=!empty($_POST['_classy_combined_campaign_display_campaign_title'])?true:false;
		$classy_combined_campaign_display_progress_bar=!empty($_POST['_classy_combined_campaign_display_progress_bar'])?true:false;
		$classy_combined_campaign_progress_bar_style=$_POST['_classy_combined_campaign_progress_bar_style'];
		$classy_combined_campaign_progress_bar_color=$_POST['_classy_combined_campaign_progress_bar_color'];
		$classy_combined_campaign_progress_bar_text_color=$_POST['_classy_combined_campaign_progress_bar_text_color'];
		$classy_combined_campaign_progress_bar_marker_color=$_POST['_classy_combined_campaign_progress_bar_marker_color'];
		$classy_combined_campaign_heading_color=$_POST['_classy_combined_campaign_heading_color'];
		$classy_combined_campaign_display_goal_amount=!empty($_POST['_classy_combined_campaign_display_goal_amount'])?true:false;
		$classy_combined_campaign_goal_amount_text_color=$_POST['_classy_combined_campaign_goal_amount_text_color'];

		$classy_combined_campaign_display_amount_raised=!empty($_POST['_classy_combined_campaign_display_amount_raised'])?true:false;
		$classy_combined_campaign_display_donor_count=!empty($_POST['_classy_combined_campaign_display_donor_count'])?true:false;
		$classy_combined_campaign_display_fee_details=!empty($_POST['_classy_combined_campaign_display_fee_details'])?true:false;
		$classy_combined_campaign_amount_raised_text_color=$_POST['_classy_combined_campaign_amount_raised_text_color'];
		$classy_combined_campaign_amount_raised_calculation_type=$_POST['_classy_combined_campaign_amount_raised_calculation_type'];
		$classy_combined_campaign_display_amount_raised_heading=!empty($_POST['_classy_combined_campaign_display_amount_raised_heading'])?true:false;
		$classy_combined_campaign_amount_raised_heading=$_POST['_classy_combined_campaign_amount_raised_heading'];
		$classy_combined_campaign_display_amount_raised_percentage_number=!empty($_POST['_classy_combined_campaign_display_amount_raised_percentage_number'])?true:false;


		update_post_meta($post_id,'_classy_combined_campaign_ids',$classy_combined_campaign_ids);

		update_post_meta($post_id,'_classy_combined_campaign_skin',$classy_combined_campaign_skin);
		update_post_meta($post_id,'_classy_combined_campaign_id',$classy_combined_campaign_id);
		update_post_meta($post_id,'_classy_combined_campaign_css_class',$classy_combined_campaign_css_class);
		update_post_meta($post_id,'_classy_combined_campaign_display_campaign_title',$classy_combined_campaign_display_campaign_title);
		update_post_meta($post_id,'_classy_combined_campaign_display_progress_bar',$classy_combined_campaign_display_progress_bar);
		update_post_meta($post_id,'_classy_combined_campaign_progress_bar_style',$classy_combined_campaign_progress_bar_style);
		update_post_meta($post_id,'_classy_combined_campaign_progress_bar_color',$classy_combined_campaign_progress_bar_color);
		update_post_meta($post_id,'_classy_combined_campaign_progress_bar_text_color',$classy_combined_campaign_progress_bar_text_color);
		update_post_meta($post_id,'_classy_combined_campaign_progress_bar_marker_color',$classy_combined_campaign_progress_bar_marker_color);
		update_post_meta($post_id,'_classy_combined_campaign_heading_color',$classy_combined_campaign_heading_color);
		update_post_meta($post_id,'_classy_combined_campaign_display_goal_amount',$classy_combined_campaign_display_goal_amount);
		update_post_meta($post_id,'_classy_combined_campaign_goal_amount_text_color',$classy_combined_campaign_goal_amount_text_color);
		update_post_meta($post_id,'_classy_combined_campaign_display_amount_raised',$classy_combined_campaign_display_amount_raised);
		update_post_meta($post_id,'_classy_combined_campaign_display_donor_count',$classy_combined_campaign_display_donor_count);
		update_post_meta($post_id,'_classy_combined_campaign_display_fee_details',$classy_combined_campaign_display_fee_details);
		update_post_meta($post_id,'_classy_combined_campaign_amount_raised_calculation_type',$classy_combined_campaign_amount_raised_calculation_type);
		update_post_meta($post_id,'_classy_combined_campaign_amount_raised_text_color',$classy_combined_campaign_amount_raised_text_color);



		update_post_meta($post_id,'_classy_combined_campaign_display_amount_raised_heading',$classy_combined_campaign_display_amount_raised_heading);
		update_post_meta($post_id,'_classy_combined_campaign_amount_raised_heading',$classy_combined_campaign_amount_raised_heading);
		update_post_meta($post_id,'_classy_combined_campaign_display_amount_raised_percentage_number',$classy_combined_campaign_display_amount_raised_percentage_number);
	}

	function mittun_classy_nonclassycampaign_meta_callback($post){
		wp_nonce_field( 'mittun_nonclassy_campaign_meta', 'mittun_nonclassy_campaign_meta_nonce' );
		global $post_type;
		$campaign_url=get_post_meta($post->ID,'_classy_campaign_url',true);
		$primary_btn_text=get_post_meta($post->ID,'_classy_campaign_primary_btn_text',true);
		$primary_btn_text=empty($primary_btn_text)?__('Donate Now','mittun_classy'):$primary_btn_text;
		$primary_btn_text_color=get_post_meta($post->ID,'_classy_campaign_primary_btn_text_color',true);
		$primary_btn_bg_color=get_post_meta($post->ID,'_classy_campaign_primary_btn_bg_color',true);
		$popup_top_text=get_post_meta($post->ID,'_classy_campaign_popup_top_text',true);
		$popup_bottom_text=get_post_meta($post->ID,'_classy_campaign_popup_bottom_text',true);
		$display_form_type =get_post_meta($post->ID,'_classy_campaign_display_form_type',true);
		if(empty($display_form_type))
		$display_form_type='inline';
		$form_type =get_post_meta($post->ID,'_classy_campaign_form_type',true);
		if(empty($form_type))
		$form_type='short';
		$set_donation_amt =get_post_meta($post->ID,'_classy_campaign_set_donation_amt',true);
		$display_custom_amount_btn =get_post_meta($post->ID,'_classy_campaign_display_custom_amount_btn',true);
		$amount_btn_text =get_post_meta($post->ID,'_classy_campaign_amount_btn_text',true);
		$amount_btn_text=empty($amount_btn_text)?__('Other Amount','mittun_classy'):$amount_btn_text;
		$amount_btn_text_color =get_post_meta($post->ID,'_classy_campaign_amount_btn_text_color',true);
		$amount_btn_text_color=!empty($amount_btn_text_color)?$amount_btn_text_color:mittun_classy_get_option('amount_btn_text_color','mittun_classy_color');
		$amount_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_amount_btn_bg_color',true);
		$active_amount_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_active_amount_btn_bg_color',true);
		$donation_amt =get_post_meta($post->ID,'_classy_campaign_donation_amt',true);

		$payment_btn_text_color =get_post_meta($post->ID,'_classy_campaign_payment_btn_text_color',true);
		$payment_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_payment_btn_bg_color',true);
		$payment_active_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_payment_active_btn_bg_color',true);

		$sf_payment_btn_text_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_btn_text_color',true);
		$sf_payment_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_btn_bg_color',true);
		$sf_payment_active_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_sf_payment_active_btn_bg_color',true);

		$submit_btn_text_color =get_post_meta($post->ID,'_classy_campaign_submit_btn_text_color',true);
		$submit_btn_bg_color =get_post_meta($post->ID,'_classy_campaign_submit_btn_bg_color',true);
		$fields_to_display =get_post_meta($post->ID,'_classy_campaign_fields_to_display',true);
		$submit_btn_label =get_post_meta($post->ID,'_classy_campaign_submit_btn_label',true);
		if(empty($submit_btn_label))
		$submit_btn_label=__('Submit','mittun_classy');

		$default_donation_amt =get_post_meta($post->ID,'_classy_campaign_default_donation_amt',true);
		if(empty($default_donation_amt))
		$default_donation_amt=25;

		$once_btn_text =get_post_meta($post->ID,'_classy_campaign_once_btn_text',true);
		if(empty($once_btn_text))
		$once_btn_text=__('Once','mittun_classy');

		$monthly_btn_text =get_post_meta($post->ID,'_classy_campaign_monthly_btn_text',true);
		if(empty($monthly_btn_text))
		$monthly_btn_text=__('Monthly','mittun_classy');

		$donation_type =get_post_meta($post->ID,'_classy_campaign_donation_type',true);
		if(empty($donation_type))
			$donation_type='form';

		?>
		<table class="form-table">
				 <tbody>

					<tr valign="top">
						<th scope="row" class="indent"><?php _e('Checkout Url','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_url" value="<?php echo $campaign_url; ?>" />
						</td>
					 </tr>
					 <tr valign="top">
						<th scope="row" class="indent"><?php _e('Display Form In','mittun_classy'); ?></th>
						<td>
						<input type="radio" name="_classy_campaign_display_form_type" value="inline"  <?php checked($display_form_type,'inline',true); ?>/><?php _e('Inline (embeded)','mittun_classy');?>
                        &nbsp;
						<input type="radio" name="_classy_campaign_display_form_type" value="popup"  <?php checked($display_form_type,'popup',true); ?>/><?php _e('Popup (lightbox)','mittun_classy');?>
						</td>
					 </tr>

					 <!--Start of primary submit button section under Display Form In popup-->
                     <tr valign="top"  <?php echo ($display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_text" type="text" class="regular-text "  value="<?php echo $primary_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo ($display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top"<?php echo ($display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_primary_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_bg_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top"<?php echo ($display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Top Custom Text','mittun_classy'); ?></th>
                        <td>
                        <textarea name="_classy_campaign_popup_top_text" class="regular-text "><?php echo $popup_top_text; ?></textarea>
                        </td>
                     </tr>
					 <tr valign="top"<?php echo ($display_form_type!='popup')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Popup Bottom Custom Text','mittun_classy'); ?></th>
                        <td>
                        <textarea name="_classy_campaign_popup_bottom_text" class="regular-text "><?php echo $popup_bottom_text; ?></textarea>
                        </td>
                     </tr>
					 <!--End of primary submit button section under Display Form In popup-->

					<!--Start of Form Type-->
                     <tr valign="top" class="primary-btn-style-end" >
                        <th scope="row" class="indent"><?php _e('Form Type','mittun_classy'); ?>
                        <div>
                            <i>
                            <?php _e('Short form: User submits by clicking once or monthly buttons','mittun_classy'); ?><br/><?php _e('Long form: User selects once or monthly as an option, and submits by clicking the final submit button after completing the form','mittun_classy'); ?>
                            </i>
                        </div>
                        </th>
                        <td>
                        <input type="radio" name="_classy_campaign_form_type" value="short"  <?php checked($form_type,'short',true); ?>/><?php _e('Short (Once/Recurring buttons only)','mittun_classy');?>
                        &nbsp;
                        <input type="radio" name="_classy_campaign_form_type" value="long"  <?php checked($form_type,'long',true); ?>/><?php _e('Long (all field options)','mittun_classy');?>

                        </td>

                     </tr>
					<!--Start of long form-->
					 <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row"><?php _e('Enable Set Donation Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_set_donation_amt" id="_classy_campaign_set_donation_amt" type="checkbox"   value="true" <?php checked(!empty($set_donation_amt),true,true) ?>/>
						</td>
					 </tr>
					  <tr valign="top" <?php echo ($form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Donation Amounts','mittun_classy'); ?></th>
						<td>
							<div id="mittun-classy-amount">
							  <div id="mittun-classy-amount-wrapper">
								<p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text" value="<?php echo (!empty($donation_amt[0])?$donation_amt[0]:''); ?>" /></p>
								<?php
								if(!empty($donation_amt)){
									for($i=1;$i<count($donation_amt);$i++)
									{
										if(!empty($donation_amt[$i])){
										?>
										<p><input name="_classy_campaign_donation_amt[]" type="text" class="regular-text"  value="<?php echo (!empty($donation_amt[$i])?$donation_amt[$i]:''); ?>" />&nbsp;<a href="javascript:void(0)" class="mittun-classy-amt-remove">X</a></p>
										<?php
										}
									}
								}
								?>
							   </div>
                               <p>
								<input type="button" value="Add New" class="mittun-classy-amt-more button-primary" data-field="_classy_campaign_donation_amt[]" data-container="mittun-classy-amount-wrapper"/>
                                </p>
							</div>
						</td>
					 </tr>
					<tr valign="top" <?php echo ($form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
						<th scope="row"><?php _e('Show Other Amount Button','mittun_classy'); ?></th>
						<td>
						<input name="_classy_campaign_display_custom_amount_btn" id="_classy_campaign_display_custom_amount_btn" type="checkbox"   value="true" <?php checked(!empty($display_custom_amount_btn),true,true) ?>/>
						</td>
					 </tr>
					 <tr valign="top" <?php echo ($form_type=='short' || empty($set_donation_amt) || empty($display_custom_amount_btn))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Other Amount Button Custom Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_text" type="text" class="regular-text "  value="<?php echo $amount_btn_text; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" class="custom-amount-btn-style-end" <?php echo ($donation_type!='form' || $form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Buttons Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Active Amount Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_active_amount_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $active_amount_btn_bg_color; ?>" />
                        </td>
                     </tr>

                     <tr valign="top" class="set-amt-style-end" <?php echo ($form_type=='short' || empty($set_donation_amt))?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Buttons Text Color(Once/Monthly)','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Buttons Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Payment Type Active Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $payment_active_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Form Submit Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_submit_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Form Submit Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_submit_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $submit_btn_bg_color; ?>" />
                        </td>
                     </tr>

					 <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Fields To Display','mittun_classy'); ?></th>
						<td>
							<?php
							foreach($this->form_fields_arr as $key=>$field)
							{
								echo '<input type="checkbox" name="_classy_campaign_fields_to_display[]" ';
								if(empty($fields_to_display))echo 'checked="checked"';
								else if(!empty($fields_to_display) && in_array($key,$fields_to_display))echo 'checked="checked"';
								echo 'value="'.$key.'" />'.$field.'&nbsp;&nbsp;';
							}

							?>
						</td>
					 </tr>
					 <tr valign="top" <?php echo ($form_type=='short')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Submit Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_submit_btn_label" value="<?php echo $submit_btn_label; ?>" />
						</td>
					 </tr>
					 <!--End of long form-->
					 <!--Start of short form-->
					 <tr class="display-form-long-style-end" valign="top" <?php echo ($form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_btn_bg_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo ($form_type=='long')?'style="display:none"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Once/Monthly Active Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_campaign_sf_payment_active_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $sf_payment_active_btn_bg_color; ?>" />
                        </td>
                     </tr>

					 <tr valign="top" <?php echo ($form_type=='long')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Monthly Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_monthly_btn_text" value="<?php echo $monthly_btn_text; ?>" />
						</td>
					 </tr>

					 <tr valign="top" <?php echo ($form_type=='long')?'style="display:none"':''; ?>>
						<th scope="row" class="indent"><?php _e('Once Button Text','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_once_btn_text" value="<?php echo $once_btn_text; ?>" />
						</td>
					 </tr>
					 <!--End of short form-->
					 <!--End of Form Type-->
					 <tr valign="top" class="display-form-short-style-end">
						<th scope="row" class="indent"><?php _e('Default Donation Amount','mittun_classy'); ?></th>
						<td>
							<input type="text" name="_classy_campaign_default_donation_amt" value="<?php echo $default_donation_amt; ?>" />
						</td>
					 </tr>
					 <!--End of display form section-->

					 <tr valign="top" class="display-form-style-end">
						<th scope="row">&nbsp;</th>
						<td>
						<div id="mittun_classy_shortcode">
						<?php
						echo '[mittun_non_classy ';
						echo 'id="'.$post->ID.'"] ';
						?>
						</div>
						<br/>
						<input type="button" value="<?php _e('Copy To Clipboard') ?>" class="mittun-classy-copy button-primary" />
						</td>
					 </tr>
                     <tr valign="top">
					<th scope="row">&nbsp;</th>
                        <td  style="text-decoration:e;">
						<span>
						<?php
						if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
							?>
							<input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />							<?php
						}
						else{
							?>
							<input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
							<?php

						}
						?>
						</span>
						<span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:e;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
						</td>
                     </tr>


				</tbody>
			  </table>
		<?php
	}

	function mittun_nonclassy_campaign_save_meta_data($post_id)
	{
		global $wpdb;
		if ( ! isset( $_POST['mittun_nonclassy_campaign_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mittun_nonclassy_campaign_meta_nonce'], 'mittun_nonclassy_campaign_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$classy_campaign_url=$_POST['_classy_campaign_url'];
		$classy_campaign_primary_btn_text=$_POST['_classy_campaign_primary_btn_text'];
		$classy_campaign_primary_btn_text_color=$_POST['_classy_campaign_primary_btn_text_color'];
		$classy_campaign_primary_btn_bg_color=$_POST['_classy_campaign_primary_btn_bg_color'];
		$classy_campaign_popup_top_text=$_POST['_classy_campaign_popup_top_text'];
		$classy_campaign_popup_bottom_text=$_POST['_classy_campaign_popup_bottom_text'];



		$classy_campaign_display_form_type=$_POST['_classy_campaign_display_form_type'];
		$classy_campaign_form_type=$_POST['_classy_campaign_form_type'];
		$classy_campaign_set_donation_amt=$_POST['_classy_campaign_set_donation_amt'];

		$classy_campaign_display_custom_amount_btn=$_POST['_classy_campaign_display_custom_amount_btn'];
		$classy_campaign_amount_btn_text=$_POST['_classy_campaign_amount_btn_text'];
		$classy_campaign_amount_btn_text_color=$_POST['_classy_campaign_amount_btn_text_color'];
		$classy_campaign_amount_btn_bg_color=$_POST['_classy_campaign_amount_btn_bg_color'];
		$classy_campaign_active_amount_btn_bg_color=$_POST['_classy_campaign_active_amount_btn_bg_color'];
		$classy_campaign_donation_amt=$_POST['_classy_campaign_donation_amt'];


		$classy_campaign_payment_btn_text_color=$_POST['_classy_campaign_payment_btn_text_color'];
		$classy_campaign_payment_btn_bg_color=$_POST['_classy_campaign_payment_btn_bg_color'];
		$classy_campaign_payment_active_btn_bg_color=$_POST['_classy_campaign_payment_active_btn_bg_color'];
		$classy_campaign_submit_btn_text_color=$_POST['_classy_campaign_submit_btn_text_color'];
		$classy_campaign_submit_btn_bg_color=$_POST['_classy_campaign_submit_btn_bg_color'];

		$classy_campaign_sf_payment_btn_text_color=$_POST['_classy_campaign_sf_payment_btn_text_color'];
		$classy_campaign_sf_payment_btn_bg_color=$_POST['_classy_campaign_sf_payment_btn_bg_color'];
		$classy_campaign_sf_payment_active_btn_bg_color=$_POST['_classy_campaign_sf_payment_active_btn_bg_color'];

		$classy_campaign_fields_to_display=$_POST['_classy_campaign_fields_to_display'];
		$classy_campaign_submit_btn_label=$_POST['_classy_campaign_submit_btn_label'];
		$classy_campaign_default_donation_amt=$_POST['_classy_campaign_default_donation_amt'];
		$classy_campaign_once_btn_text=$_POST['_classy_campaign_once_btn_text'];
		$classy_campaign_monthly_btn_text=$_POST['_classy_campaign_monthly_btn_text'];



		update_post_meta($post_id,'_classy_campaign_url',$classy_campaign_url);

		update_post_meta($post_id,'_classy_campaign_primary_btn_text',$classy_campaign_primary_btn_text);
		update_post_meta($post_id,'_classy_campaign_primary_btn_text_color',$classy_campaign_primary_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_primary_btn_bg_color',$classy_campaign_primary_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_popup_top_text',$classy_campaign_popup_top_text);
		update_post_meta($post_id,'_classy_campaign_popup_bottom_text',$classy_campaign_popup_bottom_text);



		update_post_meta($post_id,'_classy_campaign_display_form_type',$classy_campaign_display_form_type);
		update_post_meta($post_id,'_classy_campaign_set_donation_amt',$classy_campaign_set_donation_amt);
		update_post_meta($post_id,'_classy_campaign_form_type',$classy_campaign_form_type);


		update_post_meta($post_id,'_classy_campaign_display_custom_amount_btn',$classy_campaign_display_custom_amount_btn);
		update_post_meta($post_id,'_classy_campaign_amount_btn_text',$classy_campaign_amount_btn_text);
		update_post_meta($post_id,'_classy_campaign_amount_btn_text_color',$classy_campaign_amount_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_amount_btn_bg_color',$classy_campaign_amount_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_active_amount_btn_bg_color',$classy_campaign_active_amount_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_donation_amt',$classy_campaign_donation_amt);
		update_post_meta($post_id,'_classy_campaign_payment_btn_text_color',$classy_campaign_payment_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_payment_btn_bg_color',$classy_campaign_payment_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_payment_active_btn_bg_color',$classy_campaign_payment_active_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_text_color',$classy_campaign_submit_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_bg_color',$classy_campaign_submit_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_fields_to_display',$classy_campaign_fields_to_display);
		update_post_meta($post_id,'_classy_campaign_sf_payment_btn_text_color',$classy_campaign_sf_payment_btn_text_color);
		update_post_meta($post_id,'_classy_campaign_sf_payment_btn_bg_color',$classy_campaign_sf_payment_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_sf_payment_active_btn_bg_color',$classy_campaign_sf_payment_active_btn_bg_color);
		update_post_meta($post_id,'_classy_campaign_submit_btn_label',$classy_campaign_submit_btn_label);
		update_post_meta($post_id,'_classy_campaign_default_donation_amt',$classy_campaign_default_donation_amt);
		update_post_meta($post_id,'_classy_campaign_once_btn_text',$classy_campaign_once_btn_text);
		update_post_meta($post_id,'_classy_campaign_monthly_btn_text',$classy_campaign_monthly_btn_text);



	}

	function mittun_classy_leaderboard_meta_callback($post)
	{

		wp_nonce_field( 'mittun_classy_leaderboard_meta', 'mittun_classy_leaderboard_meta_nonce' );


		$leaderboard_campaign_id = get_post_meta($post->ID,'_classy_leaderboard_campaign_id',true);
		$skin = get_post_meta($post->ID,'_classy_leaderboard_skin',true);
		$skin =!empty($skin)?$skin:mittun_classy_get_option('skin','mittun_classy_color');
		$type = get_post_meta($post->ID,'_classy_leaderboard_type',true);
		$type =empty($type)?'team':$type;
		$leaderboard_count = get_post_meta($post->ID,'_classy_leaderboard_count',true);
		$leaderboard_count=!empty($leaderboard_count)?$leaderboard_count:3;
		$leaderboard_column = get_post_meta($post->ID,'_classy_leaderboard_column',true);
		$leaderboard_column=!empty($leaderboard_column)?$leaderboard_column:1;
		$display_title = get_post_meta($post->ID,'_classy_leaderboard_display_title',true);
		$title_link = get_post_meta($post->ID,'_classy_leaderboard_title_link',true);
		$title_link_tab = get_post_meta($post->ID,'_classy_leaderboard_title_link_tab',true);
		$display_image = get_post_meta($post->ID,'_classy_leaderboard_display_image',true);
		$heading_color=get_post_meta($post->ID,'_classy_leaderboard_heading_color',true);
		$display_intro_text = get_post_meta($post->ID,'_classy_leaderboard_display_intro_text',true);
		$intro_text_color = get_post_meta($post->ID,'_classy_leaderboard_intro_text_color',true);
		$display_goal_amount = get_post_meta($post->ID,'_classy_leaderboard_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($post->ID,'_classy_leaderboard_goal_amount_text_color',true);
		$display_amount_raised = get_post_meta($post->ID,'_classy_leaderboard_display_amount_raised',true);
		$amount_raised_text_color = get_post_meta($post->ID,'_classy_leaderboard_amount_raised_text_color',true);
		$display_amount_raised_heading = get_post_meta($post->ID,'_classy_leaderboard_display_amount_raised_heading',true);
		$amount_raised_heading = get_post_meta($post->ID,'_classy_leaderboard_amount_raised_heading',true);
		$display_amount_raised_percentage_number = get_post_meta($post->ID,'_classy_leaderboard_display_amount_raised_percentage_number',true);
		$display_progress_bar = get_post_meta($post->ID,'_classy_leaderboard_display_progress_bar',true);
		$progress_bar_style = get_post_meta($post->ID,'_classy_leaderboard_progress_bar_style',true);

		$progress_bar_color=get_post_meta($post->ID,'_classy_leaderboard_progress_bar_color',true);
		$progress_bar_text_color=get_post_meta($post->ID,'_classy_leaderboard_progress_bar_text_color',true);
		$progress_bar_marker_color=get_post_meta($post->ID,'_classy_leaderboard_progress_bar_marker_color',true);

		$display_primary_btn=get_post_meta($post->ID,'_classy_leaderboard_display_primary_btn',true);
		$primary_btn_text=get_post_meta($post->ID,'_classy_leaderboard_primary_btn_text',true);
		$primary_btn_text=empty($primary_btn_text)?__('Donate Now','mittun_classy'):$primary_btn_text;
		$primary_btn_text_color=get_post_meta($post->ID,'_classy_leaderboard_primary_btn_text_color',true);
		$primary_btn_bg_color=get_post_meta($post->ID,'_classy_leaderboard_primary_btn_bg_color',true);


		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id))
		{
			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			$campaigns=array();
			$campaign_first=$classy->get_campaigns(array('aggregates'=>'true', 'per_page'=>1,'page'=>1,'filter'=>'status=active'));//to get other data i.e. total
			$total_campaign=!empty($campaign_first->total)?$campaign_first->total:0;
			//$per_page=100;//this the max limit
			$per_page = 20; // Temporary added 9.18.2018
			if(!empty($total_campaign))
			{
				$pages = ceil($total_campaign / $per_page);
				for($i=1;$i<=$pages;$i++)
				{
					$campaign_per_page=$classy->get_campaigns(array('aggregates'=>'true', 'per_page'=>$per_page,'page'=>$i,'filter'=>'status=active'));	

					if(!empty($campaign_per_page->data)){
						foreach($campaign_per_page->data as $campaign_per_page)
						{
							$campaigns[]=$campaign_per_page;
						}
					}
				}
			}
		}
		?>
		<table class="form-table">
				 <tbody>

				   <tr>
						<th scope="row"><?php _e( 'Select Campaign', 'mittun_classy' ) ?></th>
						<td>
							<select name="_classy_leaderboard_campaign_id" id="_classy_leaderboard_campaign_id">
								<option value=""><?php _e('Select','mittun_classy') ?></option>
								<?php if(!empty($campaigns)){ ?>
									<?php foreach($campaigns as $campaign){ ?>
										<option value="<?php echo $campaign->id;  ?>" <?php selected($campaign->id,$leaderboard_campaign_id,true); ?>><?php echo $campaign->name;  ?></option>
									<?php }?>
								<?php } ?>
							</select>

						</td>
					</tr>
                     <tr valign="top">
                        <th scope="row"><?php _e('Layout Type','mittun_classy'); ?></th>
                        <td>

                        <input type="radio" id="skin_1" name="_classy_leaderboard_skin" value="skin_1" <?php echo ($skin=='skin_1' || empty($skin)?'checked="checked"':''); ?>><?php _e('Original','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" id="skin_2"  name="_classy_leaderboard_skin" value="skin_2" <?php checked($skin,'skin_2',true); ?>><?php _e('Maverick','mittun_classy'); ?>
                        &nbsp;
                        <input type="radio" id="skin_3"  name="_classy_leaderboard_skin" value="skin_3" <?php checked($skin,'skin_3',true); ?>><?php _e('Style 3','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_4"  name="_classy_leaderboard_skin" value="skin_4" <?php checked($skin,'skin_4',true); ?>><?php _e('Style 4','mittun_classy'); ?>
                        </td>

                     </tr>
					 <tr valign="top">
                        <th scope="row" class="indent"><?php _e('Theme Style','mittun_classy'); ?></th>

                        <td class="classy-button-set" style="position:relative">

                        <input type="radio" id="style_1" name="_classy_leaderboard_progress_bar_style" value="style_1" <?php echo ($progress_bar_style=='style_1' || empty($progress_bar_style)?'checked="checked"':''); ?>>
                          <label for="style_1"><?php _e('Style 1','mittun_classy'); ?></label>

                          <input type="radio" id="style_2"  name="_classy_leaderboard_progress_bar_style" value="style_2" <?php checked($progress_bar_style,'style_2',true); ?>>
                          <label for="style_2" <?php selected($progress_bar_style,'style_1',true); ?>><?php _e('Style 2','mittun_classy'); ?></label>
                        </td>
                     </tr>
					 <tr>
						<td colspan="2">
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style1.png" data-rel="style_1" class="mittun-classy-style-sanp"/>
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style2.png" data-rel="style_2" class="mittun-classy-style-sanp"/>
						</td>
					</tr>
                  <tr>
						<th scope="row"><?php _e( 'Diaplay Leaderboard', 'mittun_classy' ) ?></th>
						<td>
                            <input type="radio" name="_classy_leaderboard_type" value="team" <?php  checked($type,'team',true); ?>/><?php _e( 'By Team', 'mittun_classy' ) ?>&nbsp;&nbsp;<input type="radio" name="_classy_leaderboard_type" value="individual" <?php  checked($type,'individual',true); ?>/><?php _e( 'By Individual', 'mittun_classy' ) ?>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Number Of Items To Show', 'mittun_classy' ) ?></th>
						<td>
							<input type="number" max="100" min="1" value="<?php echo $leaderboard_count; ?>" name="_classy_leaderboard_count" id="_classy_leaderboard_count"/>

						</td>
					</tr>
                  <tr>
						<th scope="row"><?php _e( 'Columns', 'mittun_classy' ) ?></th>
						<td>
							 <input type="radio" name="_classy_leaderboard_column" value="1" <?php echo checked($leaderboard_column,1,true); ?> /><?php _e( 'One', 'mittun_classy' ) ?>&nbsp;&nbsp; <input type="radio" name="_classy_leaderboard_column" value="2" <?php echo checked($leaderboard_column,2,true); ?> /><?php _e( 'Two', 'mittun_classy' ) ?>&nbsp;&nbsp; <input type="radio" name="_classy_leaderboard_column" value="3" <?php echo checked($leaderboard_column,3,true); ?> /><?php _e( 'Three', 'mittun_classy' ) ?>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Display Name/Title','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_display_title" id="_classy_leaderboard_display_title" type="checkbox" class=""  value="true" <?php checked(!empty($display_title),true,true); ?>/>
						</td>
					</tr>
                  <tr valign="top" <?php echo (empty($display_title))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Name/Title Text Color','mittun_classy'); ?></th>
						<td>
						    <input name="_classy_leaderboard_heading_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $heading_color; ?>" />
						</td>
					</tr>
					<tr valign="top" <?php echo (empty($display_title))?'style="display:none;"':''; ?> style="background-color:#f5f5f5;">
						<th scope="row" class="indent"><?php _e('Link Name/Title To Page On Classy','mittun_classy'); ?></th>
						<td>
						    <input name="_classy_leaderboard_title_link" id="_classy_leaderboard_title_link" type="checkbox" class=""  value="true" <?php checked(!empty($title_link),true,true); ?>/>
						</td>
					 </tr>
					<tr valign="top" <?php echo (empty($display_title) || empty($title_link))?'style="display:none;"':''; ?> style="background-color:#f5f5f5;">
						<th scope="row" class="indent"><?php _e('Open Link In New Tab','mittun_classy'); ?></th>
						<td>
						    <input name="_classy_leaderboard_title_link_tab" id="_classy_leaderboard_title_link_tab" type="checkbox" class=""  value="true" <?php checked(!empty($title_link_tab),true,true); ?>/>
						</td>
					</tr>
                     <tr class="display-campaign-title-end">
						<th scope="row"><?php _e('Display Image','mittun_classy'); ?></th>
						<td>
						    <input name="_classy_leaderboard_display_image" id="_classy_leaderboard_display_image" type="checkbox" class=""  value="true" <?php checked(!empty($display_image),true,true); ?>/>
						</td>
					</tr>
                  <tr>
						<th scope="row"><?php _e('Display Intro Text','mittun_classy'); ?></th>
						<td>
					        <input name="_classy_leaderboard_display_intro_text" id="_classy_leaderboard_display_intro_text" type="checkbox" class=""  value="true" <?php checked(!empty($display_intro_text),true,true); ?>/>
						</td>
					</tr>
                  <tr valign="top" <?php echo (empty($display_intro_text))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Intro Text Color','mittun_classy'); ?></th>
                        <td>
                            <input name="_classy_leaderboard_intro_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $intro_text_color; ?>" />
                        </td>
                  </tr>
					<tr>

                     <tr class="display-intro-text-end">
						<th scope="row"><?php _e( 'Display Progress Bar', 'mittun_classy' ) ?></th>
						<td>
							<input name="_classy_leaderboard_display_progress_bar" id="_classy_leaderboard_display_progress_bar" type="checkbox" class=""  value="true" <?php checked(!empty($display_progress_bar),true,true); ?>/>

						</td>
					</tr>

					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_progress_bar_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_color; ?>" />
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Progress Bar Marker Text Color','mittun_classy'); ?></th>
                        <td>
                            <input name="_classy_leaderboard_progress_bar_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Marker Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_progress_bar_marker_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_marker_color; ?>" />
						</td>
					 </tr>

                      <tr class="progress-bar-style-end">
						<th scope="row"><?php _e('Display Goal Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_display_goal_amount" id="_classy_leaderboard_display_goal_amount" type="checkbox" class=""  value="true" <?php checked(!empty($display_goal_amount),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_goal_amount))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Goal Amount Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_goal_amount_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $goal_amount_text_color; ?>" />
                        </td>
                     </tr>
					 <tr>
                      <tr valign="top" class="display-goal-amount-end">
						<th scope="row"><?php _e('Display Amount Raised','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_display_amount_raised" id="_classy_leaderboard_display_amount_raised" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised),true,true); ?>/>
						</td>
					 </tr>

                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_amount_raised_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_raised_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_display_amount_raised_heading" id="_classy_leaderboard_display_amount_raised_heading" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised_heading),true,true); ?>/>
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised_heading) || empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_amount_raised_heading" type="text" class="regular-text"  value="<?php echo $amount_raised_heading; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?> class="amount-raised-heading-style-end">
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Percentage Number','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_display_amount_raised_percentage_number" id="_classy_leaderboard_display_amount_raised_percentage_number" type="checkbox" class=""  value="true" <?php checked($display_amount_raised_percentage_number,true,true); ?>/>
                        </td>
                     </tr>


                     <tr valign="top" class="amount-raised-style-end">
						<th scope="row"><?php _e('Display Donate Button','mittun_classy'); ?></th>
						<td>
						<input name="_classy_leaderboard_display_primary_btn" id="_classy_leaderboard_display_primary_btn" type="checkbox"   value="true" <?php checked(!empty($display_primary_btn),true,true) ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_primary_btn_text" type="text" class="regular-text "  value="<?php echo $primary_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_primary_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_leaderboard_primary_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_bg_color; ?>" />
                        </td>
                     </tr>


					 <tr valign="top" class="primary-btn-style-end">
						<th scope="row">&nbsp;</th>
						<td>
						<div id="mittun_classy_shortcode">
						<?php
						echo '[mittun_classy_leaderboard ';
						echo 'id="'.$post->ID.'"] ';
						?>

						</div>
						<br/>
						<input type="button" value="<?php _e('Copy To Clipboard') ?>" class="mittun-classy-copy button-primary" />
						</td>
					 </tr>
                     <tr valign="top">
                        <th scope="row">&nbsp;</th>
                         <td  style="text-decoration:none;">
						<span>
						<?php
						if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
							?>
							<input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />							<?php
						}
						else{
							?>
							<input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
							<?php

						}
						?>
						</span>
						<span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:none;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
						</td>
                     </tr>


				</tbody>
			  </table>
		<?php
	}
	function mittun_classy_leaderboard_save_meta_data($post_id)
	{
		global $wpdb;
		if ( ! isset( $_POST['mittun_classy_leaderboard_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mittun_classy_leaderboard_meta_nonce'], 'mittun_classy_leaderboard_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		$classy_leaderboard_campaign_id=$_POST['_classy_leaderboard_campaign_id'];
		$classy_leaderboard_skin=$_POST['_classy_leaderboard_skin'];
		$classy_leaderboard_type=$_POST['_classy_leaderboard_type'];
		$classy_leaderboard_count=$_POST['_classy_leaderboard_count'];
		$classy_leaderboard_column=$_POST['_classy_leaderboard_column'];
		$classy_leaderboard_display_title=!empty($_POST['_classy_leaderboard_display_title'])?true:false;
		$classy_leaderboard_title_link=!empty($_POST['_classy_leaderboard_title_link'])?true:false;
		$classy_leaderboard_title_link_tab=!empty($_POST['_classy_leaderboard_title_link_tab'])?true:false;
		$classy_leaderboard_display_image=!empty($_POST['_classy_leaderboard_display_image'])?true:false;
		$classy_leaderboard_heading_color=$_POST['_classy_leaderboard_heading_color'];
		$classy_leaderboard_display_intro_text=!empty($_POST['_classy_leaderboard_display_intro_text'])?true:false;
		$classy_leaderboard_intro_text_color=$_POST['_classy_leaderboard_intro_text_color'];
		$classy_leaderboard_display_goal_amount=!empty($_POST['_classy_leaderboard_display_goal_amount'])?true:false;
		$classy_leaderboard_goal_amount_text_color=$_POST['_classy_leaderboard_goal_amount_text_color'];
		$classy_leaderboard_display_amount_raised=!empty($_POST['_classy_leaderboard_display_amount_raised'])?true:false;
		$classy_leaderboard_amount_raised_text_color=$_POST['_classy_leaderboard_amount_raised_text_color'];
		$classy_leaderboard_display_amount_raised_heading=!empty($_POST['_classy_leaderboard_display_amount_raised_heading'])?true:false;
		$classy_leaderboard_amount_raised_heading=$_POST['_classy_leaderboard_amount_raised_heading'];
		$classy_leaderboard_display_amount_raised_percentage_number=!empty($_POST['_classy_leaderboard_display_amount_raised_percentage_number'])?true:false;
		$classy_leaderboard_display_progress_bar=!empty($_POST['_classy_leaderboard_display_progress_bar'])?true:false;
		$classy_leaderboard_progress_bar_style=$_POST['_classy_leaderboard_progress_bar_style'];

		$classy_leaderboard_progress_bar_color=$_POST['_classy_leaderboard_progress_bar_color'];
		$classy_leaderboard_progress_bar_text_color=$_POST['_classy_leaderboard_progress_bar_text_color'];
		$classy_leaderboard_progress_bar_marker_color=$_POST['_classy_leaderboard_progress_bar_marker_color'];

		$classy_leaderboard_display_primary_btn=!empty($_POST['_classy_leaderboard_display_primary_btn'])?true:false;
		$classy_leaderboard_primary_btn_text=$_POST['_classy_leaderboard_primary_btn_text'];
		$classy_leaderboard_primary_btn_text_color=$_POST['_classy_leaderboard_primary_btn_text_color'];
		$classy_leaderboard_primary_btn_bg_color=$_POST['_classy_leaderboard_primary_btn_bg_color'];


		update_post_meta($post_id,'_classy_leaderboard_campaign_id',$classy_leaderboard_campaign_id);
		update_post_meta($post_id,'_classy_leaderboard_skin',$classy_leaderboard_skin);
		update_post_meta($post_id,'_classy_leaderboard_type',$classy_leaderboard_type);
		update_post_meta($post_id,'_classy_leaderboard_count',$classy_leaderboard_count);
		update_post_meta($post_id,'_classy_leaderboard_column',$classy_leaderboard_column);
		update_post_meta($post_id,'_classy_leaderboard_display_title',$classy_leaderboard_display_title);
		update_post_meta($post_id,'_classy_leaderboard_title_link',$classy_leaderboard_title_link);
		update_post_meta($post_id,'_classy_leaderboard_title_link_tab',$classy_leaderboard_title_link_tab);
		update_post_meta($post_id,'_classy_leaderboard_display_image',$classy_leaderboard_display_image);
		update_post_meta($post_id,'_classy_leaderboard_heading_color',$classy_leaderboard_heading_color);
		update_post_meta($post_id,'_classy_leaderboard_display_intro_text',$classy_leaderboard_display_intro_text);
		update_post_meta($post_id,'_classy_leaderboard_intro_text_color',$classy_leaderboard_intro_text_color);
		update_post_meta($post_id,'_classy_leaderboard_display_goal_amount',$classy_leaderboard_display_goal_amount);
		update_post_meta($post_id,'_classy_leaderboard_goal_amount_text_color',$classy_leaderboard_goal_amount_text_color);
		update_post_meta($post_id,'_classy_leaderboard_display_amount_raised',$classy_leaderboard_display_amount_raised);
		update_post_meta($post_id,'_classy_leaderboard_amount_raised_text_color',$classy_leaderboard_amount_raised_text_color);
		update_post_meta($post_id,'_classy_leaderboard_display_amount_raised_heading',$classy_leaderboard_display_amount_raised_heading);
		update_post_meta($post_id,'_classy_leaderboard_amount_raised_heading',$classy_leaderboard_amount_raised_heading);
		update_post_meta($post_id,'_classy_leaderboard_display_amount_raised_percentage_number',$classy_leaderboard_display_amount_raised_percentage_number);

		update_post_meta($post_id,'_classy_leaderboard_display_progress_bar',$classy_leaderboard_display_progress_bar);

		update_post_meta($post_id,'_classy_leaderboard_progress_bar_style',$classy_leaderboard_progress_bar_style);

		update_post_meta($post_id,'_classy_leaderboard_progress_bar_color',$classy_leaderboard_progress_bar_color);
		update_post_meta($post_id,'_classy_leaderboard_progress_bar_text_color',$classy_leaderboard_progress_bar_text_color);
		update_post_meta($post_id,'_classy_leaderboard_progress_bar_marker_color',$classy_leaderboard_progress_bar_marker_color);


		update_post_meta($post_id,'_classy_leaderboard_display_primary_btn',$classy_leaderboard_display_primary_btn);
		update_post_meta($post_id,'_classy_leaderboard_primary_btn_text',$classy_leaderboard_primary_btn_text);
		update_post_meta($post_id,'_classy_leaderboard_primary_btn_text_color',$classy_leaderboard_primary_btn_text_color);
		update_post_meta($post_id,'_classy_leaderboard_primary_btn_bg_color',$classy_leaderboard_primary_btn_bg_color);

	}

	function mittun_classy_event_meta_callback($post)
	{

		wp_nonce_field( 'mittun_classy_event_meta', 'mittun_classy_event_meta_nonce' );

		$event_count = get_post_meta($post->ID,'_classy_event_count',true);
		$event_count=!empty($event_count)?$event_count:1;
		$skin = get_post_meta($post->ID,'_classy_event_skin',true);
		$skin =!empty($skin)?$skin:mittun_classy_get_option('skin','mittun_classy_color');
		$display_type = get_post_meta($post->ID,'_classy_event_display_type',true);
		$display_type=!empty($display_type)?$display_type:'all';
		$event_column = get_post_meta($post->ID,'_classy_event_column',true);
		$event_column=!empty($event_column)?$event_column:1;
		$display_title = get_post_meta($post->ID,'_classy_event_display_title',true);
		$display_image = get_post_meta($post->ID,'_classy_event_display_image',true);
		$heading_color=get_post_meta($post->ID,'_classy_event_heading_color',true);
		$display_intro_text = get_post_meta($post->ID,'_classy_event_display_intro_text',true);
		$intro_text_color = get_post_meta($post->ID,'_classy_event_intro_text_color',true);
		$display_goal_amount = get_post_meta($post->ID,'_classy_event_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($post->ID,'_classy_event_goal_amount_text_color',true);
		$display_amount_raised = get_post_meta($post->ID,'_classy_event_display_amount_raised',true);
		$amount_raised_text_color = get_post_meta($post->ID,'_classy_event_amount_raised_text_color',true);
		$display_amount_raised_heading = get_post_meta($post->ID,'_classy_event_display_amount_raised_heading',true);
		$amount_raised_heading = get_post_meta($post->ID,'_classy_event_amount_raised_heading',true);
		$amount_raised_heading =empty($amount_raised_heading)?__('Amount Raised','mittun_classy'):$amount_raised_heading;
		$display_amount_raised_percentage_number = get_post_meta($post->ID,'_classy_event_display_amount_raised_percentage_number',true);

		$display_progress_bar = get_post_meta($post->ID,'_classy_event_display_progress_bar',true);
		$progress_bar_style = get_post_meta($post->ID,'_classy_event_progress_bar_style',true);

		$progress_bar_color=get_post_meta($post->ID,'_classy_event_progress_bar_color',true);
		$progress_bar_text_color=get_post_meta($post->ID,'_classy_event_progress_bar_text_color',true);
		$progress_bar_marker_color=get_post_meta($post->ID,'_classy_event_progress_bar_marker_color',true);

		$display_primary_btn=get_post_meta($post->ID,'_classy_event_display_primary_btn',true);
		$primary_btn_text=get_post_meta($post->ID,'_classy_event_primary_btn_text',true);
		$primary_btn_text=empty($primary_btn_text)?__('Donate Now','mittun_classy'):$primary_btn_text;
		$primary_btn_text_color=get_post_meta($post->ID,'_classy_event_primary_btn_text_color',true);
		$primary_btn_bg_color=get_post_meta($post->ID,'_classy_event_primary_btn_bg_color',true);

		?>
		<table class="form-table">
				 <tbody>
					<tr>
						<th scope="row"><?php _e( 'Number Of Events To Show', 'mittun_classy' ) ?></th>
						<td>
							<input type="number"  min="1" value="<?php echo $event_count; ?>" name="_classy_event_count" id="_classy_event_count"/>

						</td>
					</tr>
                     <tr valign="top">
                        <th scope="row"><?php _e('Layout Type','mittun_classy'); ?></th>
                        <td>

                        <input type="radio" id="skin_1" name="_classy_event_skin" value="skin_1" <?php echo ($skin=='skin_1' || empty($skin)?'checked="checked"':''); ?>><?php _e('Original','mittun_classy'); ?>
                         &nbsp;
                        <input type="radio" id="skin_2"  name="_classy_event_skin" value="skin_2" <?php checked($skin,'skin_2',true); ?>><?php _e('Maverick','mittun_classy'); ?>
                        &nbsp;
                        <input type="radio" id="skin_3"  name="_classy_event_skin" value="skin_3" <?php checked($skin,'skin_3',true); ?>><?php _e('Style 3','mittun_classy'); ?>
						&nbsp;
                        <input type="radio" id="skin_4"  name="_classy_event_skin" value="skin_4" <?php checked($skin,'skin_4',true); ?>><?php _e('Style 4','mittun_classy'); ?>
                        </td>

                     </tr>
					 <tr valign="top">
                        <th scope="row" class="indent"><?php _e('Theme Style','mittun_classy'); ?></th>

                        <td class="classy-button-set" style="position:relative">

                        <input type="radio" id="style_1" name="_classy_event_progress_bar_style" value="style_1" <?php echo ($progress_bar_style=='style_1' || empty($progress_bar_style)?'checked="checked"':''); ?>>
                          <label for="style_1"><?php _e('Style 1','mittun_classy'); ?></label>

                          <input type="radio" id="style_2"  name="_classy_event_progress_bar_style" value="style_2" <?php checked($progress_bar_style,'style_2',true); ?>>
                          <label for="style_2" <?php selected($progress_bar_style,'style_1',true); ?>><?php _e('Style 2','mittun_classy'); ?></label>

                        </td>

                     </tr>
					 <tr>
						<td colspan="2">
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style1.png" data-rel="style_1" class="mittun-classy-style-sanp"/>
						<img src="<?php echo MITTUN_CLASSY_URL; ?>/img/style2.png" data-rel="style_2" class="mittun-classy-style-sanp"/>
						</td>
					</tr>
                    <tr>
						<th scope="row"><?php _e( 'Display Events', 'mittun_classy' ) ?></th>
						<td>
							<input type="radio" name="_classy_event_display_type" value="all" <?php echo checked($display_type,'all',true); ?>/><?php _e( 'All', 'mittun_classy' ) ?>&nbsp;&nbsp;<input type="radio" name="_classy_event_display_type" value="current" <?php echo checked($display_type,'current',true); ?>/><?php _e( 'Current', 'mittun_classy' ) ?>&nbsp;&nbsp;<input type="radio" name="_classy_event_display_type" value="past" <?php echo checked($display_type,'past',true); ?>/><?php _e( 'Past', 'mittun_classy' ) ?>&nbsp;&nbsp;<input type="radio" name="_classy_event_display_type" value="upcoming" <?php echo checked($display_type,'upcoming',true); ?>/><?php _e( 'Upcoming', 'mittun_classy' ); ?>

						</td>
					</tr>
                    <tr>
						<th scope="row"><?php _e( 'Columns', 'mittun_classy' ) ?></th>
						<td>
							<input type="radio" name="_classy_event_column" value="1" <?php echo checked($event_column,1,true); ?> /><?php _e( 'One', 'mittun_classy' ) ?>&nbsp;&nbsp; <input type="radio" name="_classy_event_column" value="2" <?php echo checked($event_column,2,true); ?> /><?php _e( 'Two', 'mittun_classy' ) ?>&nbsp;&nbsp; <input type="radio" name="_classy_event_column" value="3" <?php echo checked($event_column,3,true); ?> /><?php _e( 'Three', 'mittun_classy' ) ?>

						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e('Display Name/Title','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_title" id="_classy_event_display_title" type="checkbox" class=""  value="true" <?php checked(!empty($display_title),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_title))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Name/Title Text Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_heading_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $heading_color; ?>" />
						</td>
					 </tr>
                     <tr class="display-campaign-title-end">
						<th scope="row"><?php _e('Display Image','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_image" id="_classy_event_display_image" type="checkbox" class=""  value="true" <?php checked(!empty($display_image),true,true); ?>/>
						</td>
					 </tr>

                     <tr>
						<th scope="row"><?php _e('Display Intro Text','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_intro_text" id="_classy_event_display_intro_text" type="checkbox" class=""  value="true" <?php checked(!empty($display_intro_text),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_intro_text))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Intro Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_intro_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $intro_text_color; ?>" />
                        </td>
                     </tr>
					 <tr>

                     <tr class="display-intro-text-end">
						<th scope="row"><?php _e( 'Display Progress Bar', 'mittun_classy' ) ?></th>
						<td>
							<input name="_classy_event_display_progress_bar" id="_classy_event_display_progress_bar" type="checkbox" class=""  value="true" <?php checked(!empty($display_progress_bar),true,true); ?>/>

						</td>
					</tr>

					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_progress_bar_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_color; ?>" />
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Progress Bar Marker Text Color','mittun_classy'); ?></th>
                        <td>
                            <input name="_classy_event_progress_bar_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_text_color; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_progress_bar))?'style="display:none;"':''; ?>>
						<th scope="row" class="indent"><?php _e('Progress Bar Marker Color','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_progress_bar_marker_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $progress_bar_marker_color; ?>" />
						</td>
					 </tr>

                      <tr class="progress-bar-style-end">
						<th scope="row"><?php _e('Display Goal Amount','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_goal_amount" id="_classy_event_display_goal_amount" type="checkbox" class=""  value="true" <?php checked(!empty($display_goal_amount),true,true); ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_goal_amount))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Goal Amount Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_goal_amount_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $goal_amount_text_color; ?>" />
                        </td>
                     </tr>
					 <tr>
                      <tr valign="top" class="display-goal-amount-end">
						<th scope="row"><?php _e('Display Amount Raised','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_amount_raised" id="_classy_event_display_amount_raised" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised),true,true); ?>/>
						</td>
					 </tr>

                      <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_amount_raised_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $amount_raised_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_display_amount_raised_heading" id="_classy_event_display_amount_raised_heading" type="checkbox" class=""  value="true" <?php checked(!empty($display_amount_raised_heading),true,true); ?>/>
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised_heading) || empty($display_amount_raised))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Amount Raised Heading','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_amount_raised_heading" type="text" class="regular-text"  value="<?php echo $amount_raised_heading; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_amount_raised))?'style="display:none;"':''; ?> class="amount-raised-heading-style-end">
                        <th scope="row" class="indent"><?php _e('Display Amount Raised Percentage Number','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_display_amount_raised_percentage_number" id="_classy_event_display_amount_raised_percentage_number" type="checkbox" class=""  value="true" <?php checked($display_amount_raised_percentage_number,true,true); ?>/>
                        </td>
                     </tr>


                     <tr valign="top" class="amount-raised-style-end">
						<th scope="row"><?php _e('Display Donate Button','mittun_classy'); ?></th>
						<td>
						<input name="_classy_event_display_primary_btn" id="_classy_event_display_primary_btn" type="checkbox"   value="true" <?php checked(!empty($display_primary_btn),true,true) ?>/>
						</td>
					 </tr>
                     <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Text','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_primary_btn_text" type="text" class="regular-text "  value="<?php echo $primary_btn_text; ?>" />
                        </td>
                     </tr>
					 <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Text Color','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_primary_btn_text_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_text_color; ?>" />
                        </td>
                     </tr>
                     <tr valign="top" <?php echo (empty($display_primary_btn))?'style="display:none;"':''; ?>>
                        <th scope="row" class="indent"><?php _e('Primary Submit Button Background','mittun_classy'); ?></th>
                        <td>
                        <input name="_classy_event_primary_btn_bg_color" type="text" class="regular-text classy-color-picker"  value="<?php echo $primary_btn_bg_color; ?>" />
                        </td>
                     </tr>

					 <tr valign="top" class="primary-btn-style-end">
						<th scope="row">&nbsp;</th>
						<td>
						<div id="mittun_classy_shortcode">
						<?php
						echo '[mittun_classy_event ';
						echo 'id="'.$post->ID.'"] ';
						?>

						</div>
						<br/>
						<input type="button" value="<?php _e('Copy To Clipboard') ?>" class="mittun-classy-copy button-primary" />
						</td>
					 </tr>
                     <tr valign="top">
                        <th scope="row">&nbsp;</th>
                         <td  style="text-decoration:none;">
						<span>
						<?php
						if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
							?>
							<input  type="button" class="button button-primary button-large metabox_submit"  value="<?php esc_attr_e( 'Publish' ) ?>" />							<?php
						}
						else{
							?>
							<input  type="button" class="button button-primary button-large metabox_submit" value="<?php esc_attr_e( 'Update' ) ?>" />
							<?php

						}
						?>
						</span>
						<span style="text-align:right;"><a href="https://mittun.co/" target="_blank" style="text-decoration:none;"><h3><?php _e('Built with love by Mittun','mittun_classy'); ?></h3></span></a>
						</td>
                     </tr>


				</tbody>
			  </table>
		<?php
	}

	function mittun_classy_event_save_meta_data($post_id)
	{
		global $wpdb;
		if ( ! isset( $_POST['mittun_classy_event_meta_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['mittun_classy_event_meta_nonce'], 'mittun_classy_event_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		$classy_event_count=$_POST['_classy_event_count'];
		$classy_event_skin=$_POST['_classy_event_skin'];
		$classy_event_display_type=$_POST['_classy_event_display_type'];
		$classy_event_column=$_POST['_classy_event_column'];
		$classy_event_display_title=!empty($_POST['_classy_event_display_title'])?true:false;
		$classy_event_display_image=!empty($_POST['_classy_event_display_image'])?true:false;
		$classy_event_heading_color=$_POST['_classy_event_heading_color'];
		$classy_event_display_intro_text=!empty($_POST['_classy_event_display_intro_text'])?true:false;
		$classy_event_intro_text_color=$_POST['_classy_event_intro_text_color'];
		$classy_event_display_goal_amount=!empty($_POST['_classy_event_display_goal_amount'])?true:false;
		$classy_event_goal_amount_text_color=$_POST['_classy_event_goal_amount_text_color'];
		$classy_event_display_amount_raised=!empty($_POST['_classy_event_display_amount_raised'])?true:false;
		$classy_event_amount_raised_text_color=$_POST['_classy_event_amount_raised_text_color'];
		$classy_event_display_amount_raised_heading=!empty($_POST['_classy_event_display_amount_raised_heading'])?true:false;
		$classy_event_amount_raised_heading=$_POST['_classy_event_amount_raised_heading'];
		$classy_event_display_amount_raised_percentage_number=!empty($_POST['_classy_event_display_amount_raised_percentage_number'])?true:false;
		$classy_event_display_progress_bar=!empty($_POST['_classy_event_display_progress_bar'])?true:false;
		$classy_event_progress_bar_style=$_POST['_classy_event_progress_bar_style'];

		$classy_event_progress_bar_color=$_POST['_classy_event_progress_bar_color'];
		$classy_event_progress_bar_text_color=$_POST['_classy_event_progress_bar_text_color'];
		$classy_event_progress_bar_marker_color=$_POST['_classy_event_progress_bar_marker_color'];

		$classy_event_display_primary_btn=!empty($_POST['_classy_event_display_primary_btn'])?true:false;
		$classy_event_primary_btn_text=$_POST['_classy_event_primary_btn_text'];
		$classy_event_primary_btn_text_color=$_POST['_classy_event_primary_btn_text_color'];
		$classy_event_primary_btn_bg_color=$_POST['_classy_event_primary_btn_bg_color'];


		update_post_meta($post_id,'_classy_event_count',$classy_event_count);
		update_post_meta($post_id,'_classy_event_skin',$classy_event_skin);
		update_post_meta($post_id,'_classy_event_display_type',$classy_event_display_type);
		update_post_meta($post_id,'_classy_event_column',$classy_event_column);
		update_post_meta($post_id,'_classy_event_display_title',$classy_event_display_title);
		update_post_meta($post_id,'_classy_event_display_image',$classy_event_display_image);
		update_post_meta($post_id,'_classy_event_heading_color',$classy_event_heading_color);
		update_post_meta($post_id,'_classy_event_display_intro_text',$classy_event_display_intro_text);
		update_post_meta($post_id,'_classy_event_intro_text_color',$classy_event_intro_text_color);
		update_post_meta($post_id,'_classy_event_display_goal_amount',$classy_event_display_goal_amount);
		update_post_meta($post_id,'_classy_event_goal_amount_text_color',$classy_event_goal_amount_text_color);
		update_post_meta($post_id,'_classy_event_display_amount_raised',$classy_event_display_amount_raised);
		update_post_meta($post_id,'_classy_event_amount_raised_text_color',$classy_event_amount_raised_text_color);
		update_post_meta($post_id,'_classy_event_display_amount_raised_heading',$classy_event_display_amount_raised_heading);
		update_post_meta($post_id,'_classy_event_amount_raised_heading',$classy_event_amount_raised_heading);
		update_post_meta($post_id,'_classy_event_display_amount_raised_percentage_number',$classy_event_display_amount_raised_percentage_number);

		update_post_meta($post_id,'_classy_event_display_progress_bar',$classy_event_display_progress_bar);

		update_post_meta($post_id,'_classy_event_progress_bar_style',$classy_event_progress_bar_style);

		update_post_meta($post_id,'_classy_event_progress_bar_color',$classy_event_progress_bar_color);
		update_post_meta($post_id,'_classy_event_progress_bar_text_color',$classy_event_progress_bar_text_color);
		update_post_meta($post_id,'_classy_event_progress_bar_marker_color',$classy_event_progress_bar_marker_color);


		update_post_meta($post_id,'_classy_event_display_primary_btn',$classy_event_display_primary_btn);
		update_post_meta($post_id,'_classy_event_primary_btn_text',$classy_event_primary_btn_text);
		update_post_meta($post_id,'_classy_event_primary_btn_text_color',$classy_event_primary_btn_text_color);
		update_post_meta($post_id,'_classy_event_primary_btn_bg_color',$classy_event_primary_btn_bg_color);

	}



	function mittun_classy_campaign_columns($columns)
	{
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Campaign Name', 'mittun_classy');
		$new_columns['shortcode'] = __('Shortcode', 'mittun_classy');

		return $new_columns;
	}
	function manage_mittun_classy_campaign_columns($column_name, $id) {
		global $wpdb;
		switch ($column_name) {
		case 'shortcode':
			echo '[mittun_classy id="'.$id.'"] ';
			break;
		default:
			break;
		}
	}

		function mittun_classy_nonclassy_columns($columns)
	{
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Campaign Name', 'mittun_classy');
		$new_columns['shortcode'] = __('Shortcode', 'mittun_classy');

		return $new_columns;
	}
	function manage_mittun_classy_nonclassy_columns($column_name, $id) {
		global $wpdb;
		switch ($column_name) {
		case 'shortcode':
			echo '[mittun_non_classy id="'.$id.'"] ';
			break;
		default:
			break;
		}
	}

	function mittun_classy_multicampaign_columns($columns)
	{
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Campaign Name', 'mittun_classy');
		$new_columns['shortcode'] = __('Shortcode', 'mittun_classy');

		return $new_columns;
	}
	function manage_mittun_classy_multicampaign_columns($column_name, $id) {
		global $wpdb;
		switch ($column_name) {
		case 'shortcode':
			echo '[mittun_classy_combined_campaign id="'.$id.'"] ';
			break;
		default:
			break;
		}
	}


	function mittun_classy_leaderboard_columns($columns)
	{
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Leaderboard Name', 'mittun_classy');
		$new_columns['shortcode'] = __('Shortcode', 'mittun_classy');
		return $new_columns;
	}



	function manage_mittun_classy_leaderboard_columns($column_name, $id) {
		switch ($column_name) {
		case 'shortcode':
			echo '[mittun_classy_leaderboard id="'.$id.'"]';
			break;
		default:
			break;
		}
	}

	function mittun_classy_event_columns($columns)
	{
		$new_columns['cb'] = '<input type="checkbox" />';
		$new_columns['title'] = __('Leaderboard Name', 'mittun_classy');
		$new_columns['shortcode'] = __('Shortcode', 'mittun_classy');
		return $new_columns;
	}



	function manage_mittun_classy_event_columns($column_name, $id) {
		switch ($column_name) {
		case 'shortcode':
			echo '[mittun_classy_event id="'.$id.'"]';
			break;
		default:
			break;
		}
	}
	function mittun_classy_action_row($actions, $post)
	{
		if($post->post_type =="mittun-campaign" || $post->post_type =="mittun-multicampaign" || $post->post_type =="mittun-leaderboard" || $post->post_type =="mittun-event")
		{
			$actions['export'] = '<a href="'.(esc_url( admin_url( 'export.php?download&export_single='. $post->ID ) )).'">'.__('Export','mittun_classy').'</a>';
		}
		return $actions;
	}

	function mittun_classy_export_args($args)
	{
		// if no export_single var, it's a normal export - don't interfere
		if ( ! isset( $_GET['export_single'] ) ){
			return $args;
		}

		// use our fake date so the query is easy to find (because we don't have a good hook to use)
		$args['content']    = 'post';
		$args['start_date'] = $this->fake_date;
		$args['end_date']   = $this->fake_date;

		return $args;
	}
	function mittun_classy_export_query($query)
	{
		if ( ! isset( $_GET['export_single'] ) ) {
			return $query;
		}

		global $wpdb;

		// to see if it matches, then if it we replace it
		$test = $wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts}  WHERE {$wpdb->posts}.post_type = 'post' AND {$wpdb->posts}.post_status != 'auto-draft' AND {$wpdb->posts}.post_date >= %s AND {$wpdb->posts}.post_date < %s",
			date( 'Y-m-d', strtotime( $this->fake_date ) ),
			date( 'Y-m-d', strtotime('+1 month', strtotime( $this->fake_date ) ) )
		);

		if ( $test != $query ) {
			return $query;
		}

		// divide query
		$split    = explode( 'WHERE', $query );
		// replace WHERE clause
		$split[1] = $wpdb->prepare( " {$wpdb->posts}.ID = %d", intval( $_GET['export_single'] ) );
		// put query back together
		$query    = implode( 'WHERE', $split );

		return $query;
	}
}

add_action('init',function(){new mittun_classy_post_types();},20);
?>
