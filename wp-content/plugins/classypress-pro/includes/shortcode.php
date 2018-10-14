<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_shortcode
{
	function __construct()
	{
		if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE)
		{
			add_shortcode('mittun_classy',array($this,'mittun_classy_shortcode_callback'));
			add_shortcode('mittun_classy_combined_campaign',array($this,'mittun_classy_combined_campaign_shortcode_callback'));
			add_shortcode('mittun_non_classy',array($this,'mittun_non_classy_shortcode_callback'));
			add_shortcode('mittun_classy_leaderboard',array($this,'mittun_classy_leaderboard_shortcode_callback'));
			add_shortcode('mittun_classy_event',array($this,'mittun_classy_event_shortcode_callback'));

			add_action( 'wp_ajax_mittun_classy_more_activity', array($this,'mittun_classy_more_activity' ));
			add_action( 'wp_ajax_nopriv_mittun_classy_more_activity', array($this,'mittun_classy_more_activity' ));

			add_action( 'wp_ajax_mittun_classy_more_donation', array($this,'mittun_classy_more_donation' ));
			add_action( 'wp_ajax_nopriv_mittun_classy_more_donation', array($this,'mittun_classy_more_donation' ));

		}
	}

	function mittun_classy_shortcode_callback($atts)
	{
		global $form_fields_arr;

		$atts = extract (shortcode_atts( array(
			'id' => 0,
		), $atts, 'mittun_classy' ));

		if(!empty($id) && get_post_type($id)!='mittun-campaign')
			return false;

		$campaign_id=get_post_meta($id,'_classy_campaign_id',true);
		$skin=get_post_meta($id,'_classy_campaign_skin',true);
		$sliding_form_icon =get_post_meta($id,'_classy_campaign_sliding_form_icon',true);
		$sliding_form_position =get_post_meta($id,'_classy_campaign_sliding_form_position',true);
		$sliding_form_position =empty($sliding_form_position)?'left':$sliding_form_position;
		$sliding_form_bg_color =get_post_meta($id,'_classy_campaign_sliding_form_bg_color',true);
		$sliding_form_bg_color=empty($sliding_form_bg_color)?'#000000':$sliding_form_bg_color;
		$css_class = get_post_meta($id,'_classy_campaign_css_class',true);
		$display_campaign_title = get_post_meta($id,'_classy_campaign_display_campaign_title',true);
		$display_progress_bar=get_post_meta($id,'_classy_campaign_display_progress_bar',true);
		$progress_bar_style = get_post_meta($id,'_classy_campaign_progress_bar_style',true);
		$progress_bar_color=get_post_meta($id,'_classy_campaign_progress_bar_color',true);
		$progress_bar_color=!empty($progress_bar_color)?$progress_bar_color:mittun_classy_get_option('progress_bar_color','mittun_classy_color');
		$progress_bar_text_color=get_post_meta($id,'_classy_campaign_progress_bar_text_color',true);
		$progress_bar_text_color=!empty($progress_bar_text_color)?$progress_bar_text_color:mittun_classy_get_option('progress_bar_text_color','mittun_classy_color');
		$progress_bar_marker_color=get_post_meta($id,'_classy_campaign_progress_bar_marker_color',true);
		$progress_bar_marker_color=!empty($progress_bar_marker_color)?$progress_bar_marker_color:mittun_classy_get_option('progress_bar_marker_color','mittun_classy_color');
		$display_primary_btn=get_post_meta($id,'_classy_campaign_display_primary_btn',true);
		$primary_btn_text=get_post_meta($id,'_classy_campaign_primary_btn_text',true);
		$primary_btn_text_color=get_post_meta($id,'_classy_campaign_primary_btn_text_color',true);
		$primary_btn_text_color=!empty($primary_btn_text_color)?$primary_btn_text_color:mittun_classy_get_option('primary_btn_text_color','mittun_classy_color');
		$primary_btn_bg_color=get_post_meta($id,'_classy_campaign_primary_btn_bg_color',true);
		$primary_btn_bg_color=!empty($primary_btn_bg_color)?$primary_btn_bg_color:mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color');
		$heading_color=get_post_meta($id,'_classy_campaign_heading_color',true);
		$heading_color=!empty($heading_color)?$heading_color:mittun_classy_get_option('heading_color','mittun_classy_color');
		$display_goal_amount=get_post_meta($id,'_classy_campaign_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($id,'_classy_campaign_goal_amount_text_color',true);
		$goal_amount_text_color=!empty($goal_amount_text_color)?$goal_amount_text_color:mittun_classy_get_option('goal_amount_text_color','mittun_classy_color');
		$display_amount_raised=get_post_meta($id,'_classy_campaign_display_amount_raised',true);
		$display_donor_count=get_post_meta($id,'_classy_campaign_display_donor_count',true);
		$amount_raised_calculation_type=get_post_meta($id,'_classy_campaign_amount_raised_calculation_type',true);
		$display_fee_details = get_post_meta($id,'_classy_campaign_display_fee_details',true);

		$amount_raised_text_color = get_post_meta($id,'_classy_campaign_amount_raised_text_color',true);
		$amount_raised_text_color=!empty($amount_raised_text_color)?$amount_raised_text_color:mittun_classy_get_option('amount_raised_text_color','mittun_classy_color');
		$display_amount_raised_heading =get_post_meta($id,'_classy_campaign_display_amount_raised_heading',true);
		$amount_raised_heading =get_post_meta($id,'_classy_campaign_amount_raised_heading',true);
		$display_amount_raised_percentage_number =get_post_meta($id,'_classy_campaign_display_amount_raised_percentage_number',true);
		$donation_type=get_post_meta($id,'_classy_campaign_donation_type',true);
		$display_form_type =get_post_meta($id,'_classy_campaign_display_form_type',true);
		$form_type =get_post_meta($id,'_classy_campaign_form_type',true);
		$popup_top_text=get_post_meta($id,'_classy_campaign_popup_top_text',true);
		$popup_bottom_text=get_post_meta($id,'_classy_campaign_popup_bottom_text',true);
		$amount_btn_text =get_post_meta($id,'_classy_campaign_amount_btn_text',true);
		$amount_btn_text_color =get_post_meta($id,'_classy_campaign_amount_btn_text_color',true);
		$amount_btn_bg_color =get_post_meta($id,'_classy_campaign_amount_btn_bg_color',true);
		$amount_btn_bg_color=!empty($amount_btn_bg_color)?$amount_btn_bg_color:mittun_classy_get_option('amount_btn_bg_color','mittun_classy_color');
		$active_amount_btn_bg_color =get_post_meta($id,'_classy_campaign_active_amount_btn_bg_color',true);
		$active_amount_btn_bg_color=!empty($active_amount_btn_bg_color)?$active_amount_btn_bg_color:mittun_classy_get_option('active_amount_btn_bg_color','mittun_classy_color');
		$amounts=get_post_meta($id,'_classy_campaign_donation_amt',true);
		$payment_btn_text_color =get_post_meta($id,'_classy_campaign_payment_btn_text_color',true);
		$payment_btn_text_color=!empty($payment_btn_text_color)?$payment_btn_text_color:mittun_classy_get_option('payment_btn_text_color','mittun_classy_color');
		$payment_btn_bg_color =get_post_meta($id,'_classy_campaign_payment_btn_bg_color',true);
		$payment_btn_bg_color=!empty($payment_btn_bg_color)?$payment_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
		$payment_active_btn_bg_color =get_post_meta($id,'_classy_campaign_payment_active_btn_bg_color',true);
		$payment_active_btn_bg_color=!empty($payment_active_btn_bg_color)?$payment_active_btn_bg_color:mittun_classy_get_option('payment_active_btn_bg_color','mittun_classy_color');
		$submit_btn_text_color =get_post_meta($id,'_classy_campaign_submit_btn_text_color',true);
		$submit_btn_text_color=!empty($submit_btn_text_color)?$submit_btn_text_color:mittun_classy_get_option('submit_btn_text_color','mittun_classy_color');
		$submit_btn_bg_color =get_post_meta($id,'_classy_campaign_submit_btn_bg_color',true);
		$submit_btn_bg_color=!empty($submit_btn_bg_color)?$submit_btn_bg_color:mittun_classy_get_option('submit_btn_bg_color','mittun_classy_color');
		$fields=get_post_meta($id,'_classy_campaign_fields_to_display',true);
		$submit_button=get_post_meta($id,'_classy_campaign_submit_btn_label',true);
		$fundraiser_btn_text=get_post_meta($id,'_classy_campaign_fundraiser_btn_text',true);
		$fundraiser_btn_url=get_post_meta($id,'_classy_campaign_fundraiser_btn_url',true);
		$fundraiser_target=get_post_meta($id,'_classy_campaign_fundraiser_target',true);
		$fundraiser_btn_color=get_post_meta($id,'_classy_campaign_fundraiser_btn_color',true);
		$fundraiser_btn_text_color=get_post_meta($id,'_classy_campaign_fundraiser_btn_text_color',true);
		$fundraiser_btn_side=get_post_meta($id,'_classy_campaign_fundraiser_btn_side',true);
		$donate_btn_text=get_post_meta($id,'_classy_campaign_donate_btn_text',true);
		$donate_btn_url=get_post_meta($id,'_classy_campaign_donate_btn_url',true);
		$donate_target=get_post_meta($id,'_classy_campaign_donate_target',true);
		$donate_btn_color=get_post_meta($id,'_classy_campaign_donate_btn_color',true);
		$donate_btn_text_color=get_post_meta($id,'_classy_campaign_donate_btn_text_color',true);
		$donate_btn_side=get_post_meta($id,'_classy_campaign_donate_btn_side',true);
		$display_account_activity=get_post_meta($id,'_classy_campaign_display_account_activity',true);
		$account_activity_type=get_post_meta($id,'_classy_campaign_account_activity_type',true);
		$display_activity_title=get_post_meta($id,'_classy_campaign_display_activity_title',true);
		$activity_title=get_post_meta($id,'_classy_campaign_activity_title',true);
		$display_activity_profile_picture=get_post_meta($id,'_classy_campaign_display_activity_profile_picture',true);
		$account_activity_limit=get_post_meta($id,'_classy_campaign_account_activity_limit',true);

		$display_donation=get_post_meta($id,'_classy_campaign_display_donation',true);
		$display_donation_type=get_post_meta($id,'_classy_campaign_display_donation_type',true);
		$display_donation_title=get_post_meta($id,'_classy_campaign_display_donation_title',true);
		$donation_title=get_post_meta($id,'_classy_campaign_donation_title',true);
		$donation_limit=get_post_meta($id,'_classy_campaign_donation_limit',true);

		if($form_type=='short')
		{
			$payment_btn_text_color =get_post_meta($id,'_classy_campaign_sf_payment_btn_text_color',true);
			$payment_btn_text_color=!empty($payment_btn_text_color)?$payment_btn_text_color:mittun_classy_get_option('payment_btn_text_color','mittun_classy_color');
			$payment_btn_bg_color =get_post_meta($id,'_classy_campaign_sf_payment_btn_bg_color',true);
			$payment_btn_bg_color=!empty($payment_btn_bg_color)?$payment_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
			$payment_active_btn_bg_color=get_post_meta($id,'_classy_campaign_sf_payment_btn_bg_color',true);
			$payment_active_btn_bg_color=!empty($payment_active_btn_bg_color)?$payment_active_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
		}



		if(empty($campaign_id)) {
			return false;
		}

		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		$classy_campaign_base_trans = '_classypress_campaign_base_' . $campaign_id;
		$classy_campaign_overview_trans = '_classypress_campaign_overview_' . $campaign_id;
		$classy_campaign_activity_trans = '_classypress_campaign_activity_' . $campaign_id;
		$classy_campaign_donation_trans = '_classypress_campaign_donation_list_' . $campaign_id;

		$campaign = get_transient($classy_campaign_base_trans);
		$campaign_overview = get_transient($classy_campaign_overview_trans);

		if(!$campaign || !$campaign_overview) {
			if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {

				$classy = new Classy($client_id,$client_secret,$organisation_id);//v2

				if(!$campaign) {
					$campaign = $classy->get_campaign_info($campaign_id,array('aggregates'=>'true','filter'=>'status=active'));
					set_transient($classy_campaign_base_trans, $campaign, 1 * HOUR_IN_SECONDS);
				}

				if(!$campaign_overview) {
					$campaign_overview = $classy->get_campaign_overview($campaign_id,array('aggregates'=>'true','filter'=>'status=active'));
					set_transient($classy_campaign_overview_trans, $campaign_overview, 1 * HOUR_IN_SECONDS);
				}

				$donor_numbers = '';
				if(!empty($campaign_overview->donors_count)) {
					$donor_numbers=$campaign_overview->donors_count;
				}
			}
		}

		$output = '';

		if(!empty($campaign->id)) {

			if($campaign->goal<1) {
				$percent=0;
			} else {
				if($amount_raised_calculation_type == 'fees_donation') {
					$percent = @round(floatval(str_replace(',', '',$campaign_overview->total_gross_amount)) * 100 / floatval(str_replace(',', '',$campaign->goal)));
				} else {
					$percent= @round(floatval(str_replace(',', '',$campaign_overview->donation_net_amount)) * 100 / floatval(str_replace(',', '',$campaign->goal)));
				}
			}

			if($amount_raised_calculation_type=='fees_donation') {
				$donation_amount='$'.number_format(round($campaign_overview->total_gross_amount));
			} else {
				$donation_amount='$'.number_format(round($campaign_overview->donation_net_amount));
			}

			$rand='campaign-'.$id;

			$output='<style type="text/css">
			#mittun-classy-'.$rand.' .mittun-thermometer {border-left:1px solid '.$progress_bar_color.'; border-right:1px solid '.$progress_bar_color.';}
			#mittun-classy-'.$rand.' .mittun-thermometer-goal{color:'.$goal_amount_text_color.';}
			#mittun-classy-'.$rand.' .mittun-thermometer-goal span{color:'.$goal_amount_text_color.';}
			#mittun-classy-'.$rand.' .mittun-thermometer-progress { background:'.$progress_bar_color.'; }
			#mittun-classy-'.$rand.' .mittun-thermometer-progress-marker { background:'.$progress_bar_marker_color.'; }
			#mittun-classy-'.$rand.' .mittun-thermometer-progress-marker-text{color:'.$progress_bar_text_color.';}
			#mittun-classy-'.$rand.' .mittun-thermometer-value span {color:'.$amount_raised_text_color.';}
			#mittun-classy-'.$rand.' .mittun-campaign-link a {color:'.$primary_btn_text_color.';background:'.$primary_btn_bg_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"]{background-color:'.$amount_btn_bg_color.';color:'.$amount_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="button"].active,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"].active{background-color:'.$active_amount_btn_bg_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="submit"]{background-color:'.$submit_btn_bg_color.';color:'.$submit_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-popup-'.$rand.' .classy-donation-form .recurring-options-container label{background-color:'.$payment_btn_bg_color.';color:'.$payment_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="radio"]:checked + label,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="radio"]:checked + label {background-color: '.$payment_active_btn_bg_color.';}
			#mittun-classy-'.$rand.' .mittun-fundDon-link.donate a {background:'.$donate_btn_color.';color:'.$donate_btn_text_color.';}
			#mittun-classy-'.$rand.' .mittun-fundDon-link.fundraise a {background:'.$fundraiser_btn_color.';color:'.$fundraiser_btn_text_color.';}';

			if($skin=='skin_3' && !empty($sliding_form_icon))
			{
				$output.='#mittun-classy-'.$rand.' .mittun-classy-sidenav{'.$sliding_form_position.':0px!important;background-color:'.$sliding_form_bg_color.'}';
				$output.='#mittun-classy-'.$rand.' .mittun-classy-sidenav .closebtn{'.($sliding_form_position=='left'?'right':'left').':30px!important;background-color:'.$sliding_form_bg_color.'}';
				$output.='#mittun-classy-'.$rand.' .mittun-classy-sliding-open{position:fixed;top:40px;'.$sliding_form_position.':30px!important;z-index:9999;}';
			}

			if($progress_bar_style=='style_2')
			{
				$output.='#mittun-classy-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-popup-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-'.$rand.' .mittun-campaign-link a{border-radius:20px;}';
			}


			$output.='</style>';


			$output.='<div id="mittun-classy-'.$rand.'" class="classypress-master campaign-container-master'.$skin.' '.$progress_bar_style.' '.$css_class.'">';
			if($skin=='skin_3' && !empty($sliding_form_icon))
				$output.='<div class="mittun-classy-sliding-open"><img src="'.$sliding_form_icon.'"></div>';

			$output.='<div class="classypress-inner campaign-container-inner '.($skin=='skin_3'?'mittun-classy-sidenav':'').'"> ';
			$output.=($skin=='skin_3')?'<a href="javascript:void(0)" class="closebtn" >&times;</a>':'';
            $output.='<!-- START OF THERMOMETER AND BASIC DATA FOR EACH CAMPAIGN -->
                     <div class="mittun-thermometer-container">';
					 if(!empty($display_campaign_title))
                     	$output.='<h3 style="color:'.$heading_color.';"> '.$campaign->name.'</h3>';
						if($display_amount_raised){
						if(!empty($display_amount_raised_heading))
						$output.='<h3 style="color:'.$heading_color.';">'.$amount_raised_heading.'</h3>';
                       	$output.= '<div class="mittun-thermometer-value"><span>'.$donation_amount.'</span></div>';
					   if(!empty($display_fee_details))
					   {
					   		$output.= '<div style="text-align:center;">'.__('Donation Net Amount : ').'$'.$campaign_overview->donation_net_amount.'</div>';
							$output.= '<div style="text-align:center;">'.__('Donation Fees Amount : ').'$'.$campaign_overview->fees_amount.'</div>';
					   }


					   }

					   if($display_goal_amount)
                       $output.='<div class="mittun-thermometer-goal">'.__('OF OUR','mittun_classy').' <span>$'.(number_format(round($campaign->goal))).'</span> '.__('GOAL','mittun_classy');

					   //Total Donor
					   if(!empty($donor_numbers) && !empty($display_donor_count) && !empty($display_amount_raised))
					   {
							$output.='<p>'.__('Total Donations','mittun_classy').'&nbsp;'.$donor_numbers.'</p>';
					   }
					   if($display_goal_amount)
					   $output.='</div><!--End of .mittun-thermometer-goal-->';

					   if($display_progress_bar){
                         $output.='<div class="mittun-thermometer">
                            <div class="mittun-thermometer-progress" style="width:'.$percent.'%;">';
							$output.='<div class="mittun-thermometer-progress-marker"></div>';
							$output.='<div class="mittun-clear-fix"> </div>';
							if($display_amount_raised && $display_amount_raised_percentage_number)
							$output.='<div class="mittun-thermometer-progress-marker-text">'.$percent.'%</div>';

                         $output.='</div>';

						 $output.='<div class="mittun-clear-fix"> </div>';
                         $output.='</div>';
                         }
                     $output.='</div>

                     <!-- END OF THERMOMETER AND BASIC DATA FOR EACH CAMPAIGN -->';
					  if($donation_type=='form' && $display_form_type=='inline') {
						  ob_start();
						  if($form_type=='long') {
								$this->mittun_classy_long_donation_form($id);
							} else {
								$this->mittun_classy_short_donation_form($id);
							}

						  $output .= ob_get_contents();
						  ob_end_clean();
					 }

					 if(($donation_type=='form' && $display_form_type=='popup') ){

					  $popup_attr='';
					  if($donation_type=='form'){
						  $popup_attr='data-mfp-src="#mittun-classy-popup-'.$rand.'"';
						}

						//add div for popup
						if($donation_type=='form' && $display_form_type=='popup')
						{
							 $output.=' <!-- DONATE BUTTON -->
							<div class="mittun-campaign-link"><a title="'.__('Click Here to Donate To Someone Awesome via StayClassy','mittun_classy').'" class="fade-hover mittun-classy-donate" href="https://www.classy.org/checkout/donation?eid='.$campaign->id.'" target="_blank" data-campaign-id="'.$campaign->id.'" '.$popup_attr.'>'.$primary_btn_text.'</a></div> ';

							$popup_elem_attr='class="white-popup mfp-hide mittun-classy-popup '.$skin.' '.$css_class.'"';

							$output.='<div id="mittun-classy-popup-'.$rand.'" '.$popup_elem_attr.'>';
							$output.='<div class="mittun-classy-popup-text-container mittun-popup-text-top">'.$popup_top_text.'</div>';
							ob_start();
							 if($form_type=='long')
							  $this->mittun_classy_long_donation_form($id);
							  else
							  $this->mittun_classy_short_donation_form($id);
							$output.= ob_get_contents();
							ob_end_clean();
							$output.='<div class="mittun-classy-popup-text-container mittun-popup-text-bottom">'.$popup_bottom_text.'</div>';
							$output.='</div>';
						}


                       }

					if($donation_type=='fundraise'){
						$output.='<div>';
						$output.='<div class="mittun-fundDon-link donate" style="float:'.$donate_btn_side.'"><a title="'.__('Click Here to Donate To Someone Awesome via StayClassy','mittun_classy').'" class="fade-hover" href="'.esc_url($donate_btn_url).'" target="'.(!empty($donate_target)?'_blank':'').'" >'.$donate_btn_text.'</a></div> ';
						$output.='<div class="mittun-fundDon-link fundraise" style="float:'.$fundraiser_btn_side.'"><a title="'.__('Click Here to Donate To Someone Awesome via StayClassy','mittun_classy').'" class="fade-hover" href="'.esc_url($fundraiser_btn_url).'" target="'.(!empty($fundraiser_target)?'_blank':'').'">'.$fundraiser_btn_text.'</a></div> ';
						$output.='</div>';
					}

					if(!empty($display_account_activity)) {

						$campaign_activity = get_transient($classy_campaign_activity_trans);

						$output.='<div class="mittun-classy-account-activity">';
						if(!empty($display_activity_title))
						$output.='<h2>'.$activity_title.'</h2>';

						$req_arg=array('per_page'=>$account_activity_limit,'sort'=>'created_at:desc');
						if($account_activity_type=='donation')
							$req_arg['filter'].='type=donation_created';

						if(!$campaign_activity) {
							$campaign_activity=$classy->get_campaign_activity($campaign_id,$req_arg);
							set_transient($classy_campaign_activity_trans, $campaign_activity, 1 * HOUR_IN_SECONDS);
						}

						if(!empty($campaign_activity->data)){
							$output .= $this->mittun_classy_activities_loop($campaign_activity->data, array('display_activity_profile_picture'=>$display_activity_profile_picture));

							if($campaign_activity->total>$account_activity_limit) {
								$output.='<div class="mittun-classy-activity-more">';
								$output.='<input type="button" value="'.__('Load More','mittun_classy').'" data-basic="campaign" data-id="'.$id.'" data-current="'.$campaign_activity->current_page.'" data-last="'.$campaign_activity->last_page.'">';
								$output.='<img src="'.MITTUN_CLASSY_URL.'/img/loader.gif" style="display:none;">';
								$output.='</div>';
							}
						}

						$output.='</div>';
					}


					if(!empty($display_donation)) {

						$donation_list = get_transient($classy_campaign_donation_trans);

						$output.='<div class="mittun-classy-account-activity">';

						if(!empty($display_donation_title)) {
							$output.='<h2>'.$donation_title.'</h2>';
						}

						if(!$donation_list) {
							$req_arg = array('per_page'=>$donation_limit,'sort'=>'created_at:desc','filter'=>'status=success');

							if($display_donation_type=='offline') {
								$req_arg['filter'].=',payment_method=Offline';
							}

							$donation_list = $classy->get_campaign_transactions($campaign_id,$req_arg);

							set_transient($classy_campaign_donation_trans, $donation_list, 1 * HOUR_IN_SECONDS);
						}

						if(!empty($donation_list->data)){
							$output.=$this->mittun_classy_donation_loop($donation_list->data);
							if($donation_list->total>$donation_limit){
								$output.='<div class="mittun-classy-donation-more">';
								$output.='<input type="button" value="'.__('Load More','mittun_classy').'" data-basic="campaign" data-id="'.$id.'" data-current="'.$donation_list->current_page.'" data-last="'.$donation_list->last_page.'">';
								$output.='<img src="'.MITTUN_CLASSY_URL.'/img/loader.gif" style="display:none;">';
								$output.='</div>';
							}
						}

						$output.='</div>';
					}

                $output.='</div>';
				$output.='</div><!-- end of .campaign-master -->';



		}

		return $output;


	}

	function mittun_classy_combined_campaign_shortcode_callback($atts)
	{
		$atts = extract (shortcode_atts( array(
			'id' => 0,
		), $atts, 'mittun_classy' ));

		if(!empty($id) && get_post_type($id)!='mittun-multicampaign')
			return false;

		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		$output='';

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)){

			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2

			$combined_campaign_ids = get_post_meta($id,'_classy_combined_campaign_ids',true);

			$skin=get_post_meta($id,'_classy_combined_campaign_skin',true);
			$css_class = get_post_meta($id,'_classy_combined_campaign_css_class',true);
			$display_campaign_title = get_post_meta($id,'_classy_combined_campaign_display_campaign_title',true);
			$display_progress_bar=get_post_meta($id,'_classy_combined_campaign_display_progress_bar',true);
			$progress_bar_style = get_post_meta($id,'_classy_combined_campaign_progress_bar_style',true);
			$progress_bar_color=get_post_meta($id,'_classy_combined_campaign_progress_bar_color',true);
			$progress_bar_color=!empty($progress_bar_color)?$progress_bar_color:mittun_classy_get_option('progress_bar_color','mittun_classy_color');
			$progress_bar_text_color=get_post_meta($id,'_classy_combined_campaign_progress_bar_text_color',true);
			$progress_bar_text_color=!empty($progress_bar_text_color)?$progress_bar_text_color:mittun_classy_get_option('progress_bar_text_color','mittun_classy_color');
			$progress_bar_marker_color=get_post_meta($id,'_classy_combined_campaign_progress_bar_marker_color',true);
			$progress_bar_marker_color=!empty($progress_bar_marker_color)?$progress_bar_marker_color:mittun_classy_get_option('progress_bar_marker_color','mittun_classy_color');
			$heading_color=get_post_meta($id,'_classy_combined_campaign_heading_color',true);
			$heading_color=!empty($heading_color)?$heading_color:mittun_classy_get_option('heading_color','mittun_classy_color');
			$display_goal_amount=get_post_meta($id,'_classy_combined_campaign_display_goal_amount',true);
			$goal_amount_text_color = get_post_meta($id,'_classy_combined_campaign_goal_amount_text_color',true);
			$goal_amount_text_color=!empty($goal_amount_text_color)?$goal_amount_text_color:mittun_classy_get_option('goal_amount_text_color','mittun_classy_color');
			$display_amount_raised=get_post_meta($id,'_classy_combined_campaign_display_amount_raised',true);
			$display_donor_count=get_post_meta($id,'_classy_combined_campaign_display_donor_count',true);
			$amount_raised_calculation_type=get_post_meta($id,'_classy_combined_campaign_amount_raised_calculation_type',true);
			$display_fee_details = get_post_meta($id,'_classy_combined_campaign_display_fee_details',true);

			$amount_raised_text_color = get_post_meta($id,'_classy_combined_campaign_amount_raised_text_color',true);
			$amount_raised_text_color=!empty($amount_raised_text_color)?$amount_raised_text_color:mittun_classy_get_option('amount_raised_text_color','mittun_classy_color');
			$display_amount_raised_heading =get_post_meta($id,'_classy_combined_campaign_display_amount_raised_heading',true);
			$amount_raised_heading =get_post_meta($id,'_classy_combined_campaign_amount_raised_heading',true);
			$display_amount_raised_percentage_number =get_post_meta($id,'_classy_combined_campaign_display_amount_raised_percentage_number',true);

			$campaign_names=array();
			$total_goal=$total_donation_amount=$total_percent_raised=$total_donation_net_amount=$total_fees_amount=$total_donor_numbers=0;

			if(!empty($combined_campaign_ids))
			{
				foreach($combined_campaign_ids as $campaign_id)
				{
					$campaign=$classy->get_campaign_info($campaign_id);
					$campaign_overview=$classy->get_campaign_overview($campaign_id);
					if(!empty($campaign->id))
					{
						$campaign_names[]=$campaign->name;
						$total_goal+=$campaign->goal;

						$donor_numbers='';
						if(!empty($campaign_overview->donors_count))
						{
							$total_donor_numbers+=$campaign_overview->donors_count;
						}

						if($amount_raised_calculation_type=='fees_donation')
							$total_donation_amount+=$campaign_overview->total_gross_amount;
						else
							$total_donation_amount+=$campaign_overview->donation_net_amount;

						$total_donation_net_amount+=$campaign_overview->donation_net_amount;
						$total_fees_amount+=$campaign_overview->fees_amount;


					}
				}
				//Shortcode html here
				if(!empty($campaign_names))
				{
					$rand=wp_generate_password(10,false);

					if(empty($total_goal)) {
						$total_percent_raised = 100;
					} else {
						$total_percent_raised=round(($total_donation_amount*100)/$total_goal,2);
					}

					$output='<style type="text/css">
					#mittun-classy-'.$rand.' .mittun-thermometer {border-left:1px solid '.$progress_bar_color.'; border-right:1px solid '.$progress_bar_color.';}
					#mittun-classy-'.$rand.' .mittun-thermometer-goal{color:'.$goal_amount_text_color.';}
					#mittun-classy-'.$rand.' .mittun-thermometer-goal span{color:'.$goal_amount_text_color.';}
					#mittun-classy-'.$rand.' .mittun-thermometer-progress { background:'.$progress_bar_color.'; }
					#mittun-classy-'.$rand.' .mittun-thermometer-progress-marker { background:'.$progress_bar_marker_color.'; }
					#mittun-classy-'.$rand.' .mittun-thermometer-progress-marker-text{color:'.$progress_bar_text_color.';}
					#mittun-classy-'.$rand.' .mittun-thermometer-value span {color:'.$amount_raised_text_color.';}';

					$output.='</style>';

					$output.='<div id="mittun-classy-'.$rand.'" class="classypress-master campaign-container-master'.$skin.' '.$progress_bar_style.' '.$css_class.'">';
			$output.='<div class="classypress-inner campaign-container-inner">
            		<!-- START OF THERMOMETER AND BASIC DATA FOR EACH CAMPAIGN -->
                     <div class="mittun-thermometer-container">';
					 if(!empty($display_campaign_title))
                     	$output.='<h3 style="color:'.$heading_color.';"> '.(implode($campaign_names,',')).'</h3>';
						if($display_amount_raised){
						if(!empty($display_amount_raised_heading))
						$output.='<h3 style="color:'.$heading_color.';">'.$amount_raised_heading.'</h3>';
                       	$output.= '<div class="mittun-thermometer-value"><span>$'.number_format(round($total_donation_amount)).'</span></div>';
					   if(!empty($display_fee_details))
					   {
					   		$output.= '<div style="text-align:center;">'.__('Donation Net Amount : ').'$'.number_format(round($total_donation_net_amount)).'</div>';
							$output.= '<div style="text-align:center;">'.__('Donation Fees Amount : ').'$'.number_format(round($total_fees_amount)).'</div>';
					   }


					   }

					   if($display_goal_amount)
                       $output.='<div class="mittun-thermometer-goal">'.__('OF OUR','mittun_classy').' <span>$'.(number_format(round($total_goal))).'</span> '.__('GOAL','mittun_classy');

					   //Total Donor
					   if(!empty($total_donor_numbers) && !empty($display_donor_count) && !empty($display_amount_raised))
					   {
							$output.='<p>'.__('Total Donations','mittun_classy').'&nbsp;'.$total_donor_numbers.'</p>';
					   }
					   if($display_goal_amount)
					   $output.='</div><!--End of .mittun-thermometer-goal-->';

					   if($display_progress_bar){
                         $output.='<div class="mittun-thermometer">
                            <div class="mittun-thermometer-progress" style="width:'.$total_percent_raised.'%;">';
							$output.='<div class="mittun-thermometer-progress-marker"></div>';
							$output.='<div class="mittun-clear-fix"> </div>';
							if($display_amount_raised && $display_amount_raised_percentage_number)
							$output.='<div class="mittun-thermometer-progress-marker-text">'.$total_percent_raised.'%</div>';

                         $output.='</div>';

						 $output.='<div class="mittun-clear-fix"> </div>';
                         $output.='</div>';
                         }
                     $output.='</div>

                     <!-- END OF THERMOMETER AND BASIC DATA FOR EACH CAMPAIGN -->';


					$output.='</div>';
					$output.='</div><!-- end of .campaign-master -->';
				}
			}
		}

		return $output;
	}

	function mittun_non_classy_shortcode_callback($atts)
	{
		global $form_fields_arr;

		$atts = extract (shortcode_atts( array(
			'id' => 0,
		), $atts, 'mittun_classy' ));

		if(empty($id) || $id == 0) {
			return false;
		}

		$eid=get_post_meta($id,'_classy_campaign_id',true);
		$action='https://www.classy.org/checkout/donation/';
		$checkout_url_type = mittun_classy_get_option('checkout_url_type', 'mittun_classy_advanced');
		if($checkout_url_type == 'custom') {
			$custom_checkout_url = mittun_classy_get_option('custom_checkout_url', 'mittun_classy_advanced');
			$action = trim($custom_checkout_url, '/') . '/give/' . $eid . '/#!/donation/checkout';
		}

		$skin=get_post_meta($id,'_classy_campaign_skin',true);
		$css_class = get_post_meta($id,'_classy_campaign_css_class',true);
		$donate_btn_color=get_post_meta($id,'_classy_campaign_donate_btn_color',true);
		$donate_btn_text_color=get_post_meta($id,'_classy_campaign_donate_btn_text_color',true);
		$primary_btn_text=get_post_meta($id,'_classy_campaign_primary_btn_text',true);
		$primary_btn_text_color=get_post_meta($id,'_classy_campaign_primary_btn_text_color',true);
		$primary_btn_text_color=!empty($primary_btn_text_color)?$primary_btn_text_color:mittun_classy_get_option('primary_btn_text_color','mittun_classy_color');
		$primary_btn_bg_color=get_post_meta($id,'_classy_campaign_primary_btn_bg_color',true);
		$primary_btn_bg_color=!empty($primary_btn_bg_color)?$primary_btn_bg_color:mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color');

		$display_form_type =get_post_meta($id,'_classy_campaign_display_form_type',true);
		$form_type =get_post_meta($id,'_classy_campaign_form_type',true);
		$fundraiser_btn_color=get_post_meta($id,'_classy_campaign_fundraiser_btn_color',true);
		$fundraiser_btn_text_color=get_post_meta($id,'_classy_campaign_fundraiser_btn_text_color',true);
		$popup_top_text=get_post_meta($id,'_classy_campaign_popup_top_text',true);
		$popup_bottom_text=get_post_meta($id,'_classy_campaign_popup_bottom_text',true);
		$amount_btn_text =get_post_meta($id,'_classy_campaign_amount_btn_text',true);
		$amount_btn_text_color =get_post_meta($id,'_classy_campaign_amount_btn_text_color',true);
		$amount_btn_bg_color =get_post_meta($id,'_classy_campaign_amount_btn_bg_color',true);
		$amount_btn_bg_color=!empty($amount_btn_bg_color)?$amount_btn_bg_color:mittun_classy_get_option('amount_btn_bg_color','mittun_classy_color');
		$active_amount_btn_bg_color =get_post_meta($id,'_classy_campaign_active_amount_btn_bg_color',true);
		$active_amount_btn_bg_color=!empty($active_amount_btn_bg_color)?$active_amount_btn_bg_color:mittun_classy_get_option('active_amount_btn_bg_color','mittun_classy_color');
		$amounts=get_post_meta($id,'_classy_campaign_donation_amt',true);
		$payment_btn_text_color =get_post_meta($id,'_classy_campaign_payment_btn_text_color',true);
		$payment_btn_text_color=!empty($payment_btn_text_color)?$payment_btn_text_color:mittun_classy_get_option('payment_btn_text_color','mittun_classy_color');
		$payment_btn_bg_color =get_post_meta($id,'_classy_campaign_payment_btn_bg_color',true);
		$payment_btn_bg_color=!empty($payment_btn_bg_color)?$payment_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
		$payment_active_btn_bg_color =get_post_meta($id,'_classy_campaign_payment_active_btn_bg_color',true);
		$payment_active_btn_bg_color=!empty($payment_active_btn_bg_color)?$payment_active_btn_bg_color:mittun_classy_get_option('payment_active_btn_bg_color','mittun_classy_color');
		$progress_bar_style = get_post_meta($id,'_classy_campaign_progress_bar_style',true);
		$submit_btn_text_color =get_post_meta($id,'_classy_campaign_submit_btn_text_color',true);
		$submit_btn_text_color=!empty($submit_btn_text_color)?$submit_btn_text_color:mittun_classy_get_option('submit_btn_text_color','mittun_classy_color');
		$submit_btn_bg_color =get_post_meta($id,'_classy_campaign_submit_btn_bg_color',true);
		$submit_btn_bg_color=!empty($submit_btn_bg_color)?$submit_btn_bg_color:mittun_classy_get_option('submit_btn_bg_color','mittun_classy_color');
		$fields=get_post_meta($id,'_classy_campaign_fields_to_display',true);
		$submit_button=get_post_meta($id,'_classy_campaign_submit_btn_label',true);

		if($form_type=='short')
		{
			$payment_btn_text_color =get_post_meta($id,'_classy_campaign_sf_payment_btn_text_color',true);
			$payment_btn_text_color=!empty($payment_btn_text_color)?$payment_btn_text_color:mittun_classy_get_option('payment_btn_text_color','mittun_classy_color');
			$payment_btn_bg_color =get_post_meta($id,'_classy_campaign_sf_payment_btn_bg_color',true);
			$payment_btn_bg_color=!empty($payment_btn_bg_color)?$payment_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
			$payment_active_btn_bg_color=get_post_meta($id,'_classy_campaign_sf_payment_btn_bg_color',true);
			$payment_active_btn_bg_color=!empty($payment_active_btn_bg_color)?$payment_active_btn_bg_color:mittun_classy_get_option('payment_btn_bg_color','mittun_classy_color');
		}



		$output='';


			$rand='campaign-'.$id;

			$output='<style type="text/css">
			#mittun-classy-'.$rand.' .mittun-campaign-link a {color:'.$primary_btn_text_color.';background:'.$primary_btn_bg_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"]{background-color:'.$amount_btn_bg_color.';color:'.$amount_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="button"].active,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"].active{background-color:'.$active_amount_btn_bg_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="submit"]{background-color:'.$submit_btn_bg_color.';color:'.$submit_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-popup-'.$rand.' .classy-donation-form .recurring-options-container label{background-color:'.$payment_btn_bg_color.';color:'.$payment_btn_text_color.';}
			#mittun-classy-'.$rand.' .classy-donation-form input[type="radio"]:checked + label,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="radio"]:checked + label {background-color: '.$payment_active_btn_bg_color.';}
			#mittun-classy-'.$rand.' .mittun-fundDon-link.donate a {background:'.$donate_btn_color.';color:'.$donate_btn_text_color.';}
			#mittun-classy-'.$rand.' .mittun-fundDon-link.fundraise a {background:'.$fundraiser_btn_color.';color:'.$fundraiser_btn_text_color.';}';


			if($progress_bar_style=='style_2')
			{
				$output.='#mittun-classy-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="button"],#mittun-classy-popup-'.$rand.' .classy-donation-form input[type="submit"],#mittun-classy-popup-'.$rand.' .classy-donation-form .recurring-options-container label,#mittun-classy-'.$rand.' .mittun-campaign-link a{border-radius:20px;}';
			}


			$output.='</style>';


			$output.='<div id="mittun-classy-'.$rand.'" class="classypress-master campaign-container-master'.$skin.' '.$progress_bar_style.' '.$css_class.'">';
			$output.='<div class="classypress-inner campaign-container-inner">';
					  if($display_form_type=='inline'){
						  ob_start();
						  if($form_type=='long')
						  $this->mittun_classy_long_donation_form($id);
						  else
						  $this->mittun_classy_short_donation_form($id);
						  $output.= ob_get_contents();
						  ob_end_clean();
					 }


						//add div for popup
						$popup_attr='';
						if($display_form_type=='popup')
						{
							$popup_attr='data-mfp-src="#mittun-classy-popup-'.$rand.'"';

							 $output.=' <!-- DONATE BUTTON -->
							 <div class="mittun-campaign-link"><a title="'.__('Click Here to Donate To Someone Awesome via StayClassy','mittun_classy').'" class="fade-hover mittun-classy-donate" href="' . $action .'" target="_blank" data-campaign-id="' . $eid .'" '.$popup_attr.'>'.$primary_btn_text.'</a></div> ';

							$popup_elem_attr='class="white-popup mfp-hide mittun-classy-popup '.$css_class.'"';

							$output.='<div id="mittun-classy-popup-'.$rand.'" '.$popup_elem_attr.'>';
							$output.='<div class="mittun-classy-popup-text-container mittun-popup-text-top">'.$popup_top_text.'</div>';
							ob_start();
							 if($form_type=='long')
							  $this->mittun_classy_long_donation_form($id);
							  else
							  $this->mittun_classy_short_donation_form($id);
							$output.= ob_get_contents();
							ob_end_clean();
							$output.='<div class="mittun-classy-popup-text-container mittun-popup-text-bottom">'.$popup_bottom_text.'</div>';
							$output.='</div>';
						}



                $output.='</div>';
				$output.='</div><!-- end of .campaign-master -->';



		return $output;
	}

	function mittun_classy_long_donation_form($id)
	{
		$eid=get_post_meta($id,'_classy_campaign_id',true);
		$donation_amt=get_post_meta($id,'_classy_campaign_donation_amt',true);
		$default_donation_amt=get_post_meta($id,'_classy_campaign_default_donation_amt',true);
		$set_donation_amt=trim(get_post_meta($id,'_classy_campaign_set_donation_amt',true));
		$display_custom_amount_btn=trim(get_post_meta($id,'_classy_campaign_display_custom_amount_btn',true));
		$amount_btn_text=trim(get_post_meta($id,'_classy_campaign_amount_btn_text',true));
		$once_btn_text =get_post_meta($id,'_classy_campaign_once_btn_text',true);
		$monthly_btn_text =get_post_meta($id,'_classy_campaign_monthly_btn_text',true);

		$campaign_display_custom_checkout_url=trim(get_post_meta($id,'_classy_campaign_display_custom_checkout_url',true));
		$campaign_custom_checkout_url=trim(get_post_meta($id,'_classy_campaign_custom_checkout_url',true));

		$fields_to_display=get_post_meta($id,'_classy_campaign_fields_to_display',true);
		$submit_button=get_post_meta($id,'_classy_campaign_submit_btn_label',true);
		$label_id=wp_generate_password(10,false);

		$action = 'https://www.classy.org/checkout/donation/';
		$checkout_url_type = mittun_classy_get_option('checkout_url_type','mittun_classy_advanced');
		if($checkout_url_type == 'custom') {
			$custom_checkout_url=mittun_classy_get_option('custom_checkout_url','mittun_classy_advanced');
			$action=trim($custom_checkout_url,'/').'/give/'.$eid.'/#!/donation/checkout';
		}
		//Overwrite campaign with specific url
		if(!empty($campaign_display_custom_checkout_url) && !empty($campaign_custom_checkout_url))
				$action = trim($campaign_custom_checkout_url,'/').'/give/'.$classy_campaign_id.'/#!/donation/checkout';

		$campaign_page = get_post_meta($id, '_classy_campaign_page', true);

		if(get_post_type($id)=='mittun-nonclassy' || $campaign_page == 1) {
			$campaign_url = get_post_meta($id,'_classy_campaign_url',true);

			if($campaign_url && !empty($campaign_url)) {
				$checkout_url_type = 'custom';
				$action = $campaign_url;
			}
		}
		?>
		<form method="get" action="<?php echo $action; ?>" target="_blank" class="classy-donation-form">
		<?php
			if($checkout_url_type!='custom' && (empty($campaign_display_custom_checkout_url) || empty($campaign_custom_checkout_url)))
			{
			?>
			<input type="hidden" name="eid" id="eid" value="<?php echo $eid; ?>"/>
			<?php
			}
			?>
			<?php if(empty($fields_to_display) || in_array('amount',$fields_to_display)){
			if(!empty($donation_amt) && !empty($set_donation_amt))
			{
			?>
			<p class="classy-amount">
			<?php

				foreach($donation_amt as $amt)
				{
					if(!empty($amt)){
					?>
					<input type="button"  value="$<?php echo $amt; ?>" data-amount="<?php echo $amt; ?>"/>

					<?php
					}
				}


			if(!empty($amount_btn_text) && !empty($display_custom_amount_btn)){
			?>
			<input type="button" value="<?php echo $amount_btn_text;?>" />
			<?php } ?>
			</p>
			<?php } ?>
			<p  style="position: relative;">
			<span class="classy-currency">$</span><input class="effect-2" type="text" value="<?php echo $default_donation_amt; ?>" name="amount"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('recurring',$fields_to_display)){ ?>
			<p class="recurring-options-container recurring_long_donation_form">
			<input class="recurring-inputs once-input_long_donation_form" type="radio" name="recurring" value="0"  id="once<?php echo $label_id; ?>"/>
			<label class="recurring-labels once-label_long_donation_form" for='once<?php echo $label_id; ?>'><?php echo $once_btn_text; ?></label>
			<input class="recurring-inputs recurring-input_long_donation_form" type="radio" name="recurring" value="1" id="recurring<?php echo $label_id; ?>"/>
			<label class="recurring-labels recurring-label_long_donation_form" for='recurring<?php echo $label_id; ?>'><?php echo $monthly_btn_text; ?></label>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('first',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" name="first" class="effect-2" placeholder="<?php _e('First Name','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('last',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" class="effect-2" name="last" placeholder="<?php _e('Last Name','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('email',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="email" class="effect-2" name="email" placeholder="<?php _e('Email','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('phone',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" class="effect-2" name="phone" placeholder="<?php _e('Phone','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('street',$fields_to_display)){ ?>
			<p style="position: relative;">
			<textarea name="street" class="effect-2"  placeholder="<?php _e('Address','mittun_classy'); ?>"></textarea>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('city',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" class="effect-2" name="city" placeholder="<?php _e('City','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('state',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" class="effect-2" name="state" placeholder="<?php _e('State','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>
			<?php if(empty($fields_to_display) || in_array('zip',$fields_to_display)){ ?>
			<p style="position: relative;">
			<input type="text" class="effect-2" name="zip" placeholder="<?php _e('Zip','mittun_classy'); ?>"/>
			<span class="focus-border"></span>
			</p>
			<?php } ?>

			<input type="submit" value="<?php echo $submit_button; ?>" />
			</form>
		<?php

	}

	function mittun_classy_short_donation_form($id)
	{
		$label_id=wp_generate_password(10,false);
		$classy_campaign_id = get_post_meta($id,'_classy_campaign_id',true);
		$default_donation_amt=get_post_meta($id,'_classy_campaign_default_donation_amt',true);
		$donation_amt=get_post_meta($id,'_classy_campaign_donation_amt',true);
		$set_donation_amt=trim(get_post_meta($id,'_classy_campaign_set_donation_amt',true));
		$display_custom_amount_btn=trim(get_post_meta($id,'_classy_campaign_display_custom_amount_btn',true));
		$amount_btn_text=trim(get_post_meta($id,'_classy_campaign_amount_btn_text',true));
		$once_btn_text =get_post_meta($id,'_classy_campaign_once_btn_text',true);
		$monthly_btn_text =get_post_meta($id,'_classy_campaign_monthly_btn_text',true);
		$campaign_display_custom_checkout_url=trim(get_post_meta($id,'_classy_campaign_display_custom_checkout_url',true));
		$campaign_custom_checkout_url=trim(get_post_meta($id,'_classy_campaign_custom_checkout_url',true));
		$action='https://www.classy.org/checkout/donation/';
		$checkout_url_type=mittun_classy_get_option('checkout_url_type','mittun_classy_advanced');
		if($checkout_url_type=='custom')
		{
			$custom_checkout_url=mittun_classy_get_option('custom_checkout_url','mittun_classy_advanced');
			$action=trim($custom_checkout_url,'/').'/give/'.$classy_campaign_id.'/#!/donation/checkout';
		}
		//Overwrite campaign with specific url
		if(!empty($campaign_display_custom_checkout_url) && !empty($campaign_custom_checkout_url))
			$action=trim($campaign_custom_checkout_url,'/').'/give/'.$classy_campaign_id.'/#!/donation/checkout';;

			$campaign_page = get_post_meta($id, '_classy_campaign_page', true);

			if(get_post_type($id)=='mittun-nonclassy' || $campaign_page == 1) {
				$campaign_url = get_post_meta($id,'_classy_campaign_url',true);

				if($campaign_url && !empty($campaign_url)) {
					$checkout_url_type = 'custom';
					$action = get_post_meta($id,'_classy_campaign_url',true);
				}
			}
		?>
		<form method="get" action="<?php echo $action; ?>" target="_blank" class="classy-donation-form short">
			<?php
			if($checkout_url_type!='custom' && (empty($campaign_display_custom_checkout_url) || empty($campaign_custom_checkout_url)))
			{
			?>
			<input type="hidden" name="eid" id="eid" value="<?php echo $classy_campaign_id; ?>"/>
			<?php
			}
			?>
			<?php
			if(!empty($donation_amt) && !empty($set_donation_amt))
			{
			?>
			<p class="classy-amount">
			<?php

				foreach($donation_amt as $amt)
				{
					if(!empty($amt)){
					?>
					<input type="button"  value="$<?php echo $amt; ?>" data-amount="<?php echo $amt; ?>"/>

					<?php
					}
				}


			if(!empty($amount_btn_text) && !empty($display_custom_amount_btn)){
			?>
			<input type="button" value="<?php echo $amount_btn_text;?>" />
		<?php } } ?>
			</p>
			<p style="position: relative;">
			<span class="classy-currency">$</span><input type="text" class="effect-2" value="<?php echo $default_donation_amt; ?>" name="amount"/>
			<span class="focus-border"></span>
			</p>
			<p class="recurring-options-container recurring_short_donation_form">
			<input class="recurring-inputs once-input_short_donation_form" type="radio" name="recurring" value="0"  id="once<?php echo $label_id; ?>"/>
			<label class="recurring-labels recurring-labels once-label_short_donation_form" for='once<?php echo $label_id; ?>'><?php echo $once_btn_text; ?></label>
			<input class="recurring-inputs recurring-input_short_donation_form" type="radio" name="recurring" value="1" id="recurring<?php echo $label_id; ?>"/>
			<label class="recurring-labels recurring-label_short_donation_form" for='recurring<?php echo $label_id; ?>'><?php echo $monthly_btn_text; ?></label>
			</p>

			</form>
		<?php
	}

	function mittun_classy_leaderboard_shortcode_callback($atts)
	{
		$atts = extract (shortcode_atts( array(
			'id' => 0,
		), $atts, 'mittun_classy' ));

		if(!empty($id) && get_post_type($id)!='mittun-leaderboard')
			return false;

		$campaign_id=get_post_meta($id,'_classy_leaderboard_campaign_id',true);
		$skin=get_post_meta($id,'_classy_leaderboard_skin',true);
		$type = get_post_meta($id,'_classy_leaderboard_type',true);
		$count=get_post_meta($id,'_classy_leaderboard_count',true);
		$column=get_post_meta($id,'_classy_leaderboard_column',true);
		$display_title = get_post_meta($id,'_classy_leaderboard_display_title',true);
		$title_link = get_post_meta($id,'_classy_leaderboard_title_link',true);
		$title_link_tab = get_post_meta($id,'_classy_leaderboard_title_link_tab',true);
		$display_image = get_post_meta($id,'_classy_leaderboard_display_image',true);
		$heading_color=get_post_meta($id,'_classy_leaderboard_heading_color',true);
		$heading_color=!empty($heading_color)?$heading_color:mittun_classy_get_option('heading_color','mittun_classy_color');
		$display_intro_text = get_post_meta($id,'_classy_leaderboard_display_intro_text',true);
		$intro_text_color = get_post_meta($id,'_classy_leaderboard_intro_text_color',true);
		$intro_text_color=!empty($intro_text_color)?$intro_text_color:mittun_classy_get_option('intro_text_color','mittun_classy_color');
		$display_goal_amount = get_post_meta($id,'_classy_leaderboard_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($id,'_classy_leaderboard_goal_amount_text_color',true);
		$goal_amount_text_color=!empty($goal_amount_text_color)?$goal_amount_text_color:mittun_classy_get_option('goal_amount_text_color','mittun_classy_color');
		$display_amount_raised = get_post_meta($id,'_classy_leaderboard_display_amount_raised',true);
		$amount_raised_text_color = get_post_meta($id,'_classy_leaderboard_amount_raised_text_color',true);
		$amount_raised_text_color=!empty($amount_raised_text_color)?$amount_raised_text_color:mittun_classy_get_option('amount_raised_text_color','mittun_classy_color');
		$display_amount_raised_heading = get_post_meta($id,'_classy_leaderboard_display_amount_raised_heading',true);
		$amount_raised_heading = get_post_meta($id,'_classy_leaderboard_amount_raised_heading',true);
		$display_amount_raised_percentage_number = get_post_meta($id,'_classy_leaderboard_display_amount_raised_percentage_number',true);
		$display_progress_bar = get_post_meta($id,'_classy_leaderboard_display_progress_bar',true);
		$progress_bar_style = get_post_meta($id,'_classy_leaderboard_progress_bar_style',true);

		$progress_bar_color=get_post_meta($id,'_classy_leaderboard_progress_bar_color',true);
		$progress_bar_color=!empty($progress_bar_color)?$progress_bar_color:mittun_classy_get_option('progress_bar_color','mittun_classy_color');
		$progress_bar_text_color=get_post_meta($id,'_classy_leaderboard_progress_bar_text_color',true);
		$progress_bar_text_color=!empty($progress_bar_text_color)?$progress_bar_text_color:mittun_classy_get_option('progress_bar_text_color','mittun_classy_color');
		$progress_bar_marker_color=get_post_meta($id,'_classy_leaderboard_progress_bar_marker_color',true);
		$progress_bar_marker_color=!empty($progress_bar_marker_color)?$progress_bar_marker_color:mittun_classy_get_option('progress_bar_marker_color','mittun_classy_color');
		$display_primary_btn=get_post_meta($id,'_classy_leaderboard_display_primary_btn',true);
		$primary_btn_text=get_post_meta($id,'_classy_leaderboard_primary_btn_text',true);
		$primary_btn_text_color=get_post_meta($id,'_classy_leaderboard_primary_btn_text_color',true);
		$primary_btn_text_color=!empty($primary_btn_text_color)?$primary_btn_text_color:mittun_classy_get_option('primary_btn_text_color','mittun_classy_color');
		$primary_btn_bg_color=get_post_meta($id,'_classy_leaderboard_primary_btn_bg_color',true);
		$primary_btn_bg_color=!empty($primary_btn_bg_color)?$primary_btn_bg_color:mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color');

		$leaderboard_pages_trans = '_classypress_leaderboard_' . $id;
		$leaderboard_pages = get_transient($leaderboard_pages_trans);

		if(!$leaderboard_pages) {
			require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

			$client_id=mittun_classy_get_option('client_id','mittun_classy');
			$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
			$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

			if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {

				$classy = new Classy($client_id,$client_secret,$organisation_id);//v2
				$filter = null;

				if($type=='team') {
					if(!empty($campaign_id)) {
						$leaderboard_pages=$classy->get_campaign_fundraiser_teams($campaign_id,array('aggregates'=>'true','filter'=>$filter,'with'=>'cover_photo,team_lead','per_page'=>$count,'sort'=>'total_raised:desc','filter'=>'status=active'));
					} else {
						$leaderboard_pages=$classy->get_fundraiser_teams(array('aggregates'=>'true','filter'=>$filter,'with'=>'cover_photo,team_lead','per_page'=>$count,'sort'=>'total_raised:desc','filter'=>'status=active'));//created_at
					}
				} else if($type=='individual') {
					if(!empty($campaign_id) ) {
						$leaderboard_pages=$classy->get_campaign_fundraiser_pages($campaign_id,array('aggregates'=>'true','filter'=>$filter,'with'=>'cover_photo,member','per_page'=>$count,'sort'=>'total_raised:desc','filter'=>'status=active'));
					} else {
						$leaderboard_pages=$classy->get_fundraiser_pages(array('aggregates'=>'true','filter'=>$filter,'with'=>'cover_photo,member','per_page'=>$count,'sort'=>'total_raised:desc','filter'=>'status=active'));
					}
				}

				// Set leaderboard pages transient
				set_transient($leaderboard_pages_trans, $leaderboard_pages, 1 * HOUR_IN_SECONDS);
			}
		}

		$rand='leaderboard-'.$id;
		$output='';

		if(!empty($leaderboard_pages->data)) {

			$output.='<style type="text/css">
			.leaderboard-style-'.$rand.' .mittun-thermometer {border-left:1px solid '.$progress_bar_color.'; border-right:1px solid '.$progress_bar_color.';}
			.leaderboard-style-'.$rand.' .mittun-thermometer-progress { background:'.$progress_bar_color.'; }
			.leaderboard-style-'.$rand.' .mittun-thermometer-progress-marker { background:'.$progress_bar_marker_color.'; }
			.leaderboard-style-'.$rand.' .mittun-thermometer-progress-marker-text{color:'.$progress_bar_text_color.';}
			.leaderboard-style-'.$rand.' .mittun-thermometer-value span {color:'.$amount_raised_text_color.';}
			.leaderboard-style-'.$rand.' .mittun-campaign-link a {color:'.$primary_btn_text_color.';background:'.$primary_btn_bg_color.';}';


			if($progress_bar_style=='style_2')
			{
				$output.='.leaderboard-style-'.$rand.' .mittun-campaign-link a{border-radius:20px;}';
			}
			$output.='</style>';


			$output.='<div class="classypress-master leaderboard-container-master leaderboard-by-'.$type.$skin.'">';
			$output.='<div class="classypress-inner leaderboard-container-inner">';
			foreach($leaderboard_pages->data as $leaderboard)
			{

				switch ($type) {
					case 'team':
						$title=$leaderboard->name;
						$intro_text=$leaderboard->description;
						$cover_image=$leaderboard->cover_photo;
						$cover_image=!empty($cover_image)?$cover_image:(!empty($leaderboard->team_lead->thumbnail_medium)?$leaderboard->team_lead->thumbnail_medium:MITTUN_CLASSY_URL.'/img/user.png');
						$donation_url="https://www.classy.org/checkout/donation?ftid=$leaderboard->id";
						break;
					case 'individual':
						$title=$leaderboard->alias;
						$intro_text=$leaderboard->title;
						$cover_image=$leaderboard->cover_photo;
						$cover_image=!empty($cover_image)?$cover_image:(!empty($leaderboard->member->thumbnail_medium)?$leaderboard->member->thumbnail_medium:MITTUN_CLASSY_URL.'/img/user.png');
						$donation_url="https://www.classy.org/checkout/donation?fcid=$leaderboard->id";
						break;
				}
				if($leaderboard->goal<1)
				$percent=0;
				else
				$percent= @round(floatval(str_replace(',', '',$leaderboard->total_raised)) * 100 / floatval(str_replace(',', '',$leaderboard->goal)));

				$output.="<div id='leaderboard-".$id."-module-".$leaderboard->id."' class='".$progress_bar_style.(($column==3) ? ' leaderboard-col-3' : (($column==2) ? ' leaderboard-col-2' : ' leaderboard-col-1'))." leaderboard-container-classypress leaderboard-style-".$rand."'>";

				if(!empty($display_title)){
					$output.='<div class="leaderboardTitle"><h2 style="color:'.$heading_color.'">';
					if(!empty($title_link))
						$output.='<a href="https://www.classy.org/fundraise'.($type=='team'?'/team?ftid='.$leaderboard->id:'?fcid='.$leaderboard->id).'" target="'.(empty($title_link_tab)?'_self':'_blank').'" style="color:'.$heading_color.'">';
					$output.=$title;
					if(!empty($title_link))
						$output.='</a>';
					$output.='</h2></div>';
				}
				if(!empty($cover_image) && !empty($display_image))
				$output.="<div class='leaderboardImg'><img src='".$cover_image."'  /></div>";
				if(!empty($display_intro_text))
				$output.='<div style="color:'.$intro_text_color.'">'.$intro_text.'</div>';
				if(!empty($display_amount_raised)){
				$output.="<div class='leaderboardraise' style='color:".$amount_raised_text_color."'>";
				if(!empty($display_amount_raised_heading) && !empty($amount_raised_heading))
				$output.=$amount_raised_heading.":";

				$output.='&nbsp;&#36;'.number_format(round($leaderboard->total_raised))."</div>";
							if(!empty($display_goal_amount))
				$output.="<div class='leaderboardgoal' style='color:".$goal_amount_text_color."'>".__('Goal','mittun_classy').":&nbsp;&#36;".@number_format(round($leaderboard->goal))."</div>";


				}
				if(!empty($display_progress_bar)){
				$output.='<div class="mittun-thermometer">
							<div class="mittun-thermometer-progress" style="width:'.$percent.'%;">
								<div class="mittun-thermometer-progress-marker"></div>
								<div class="mittun-clear-fix">
							</div>';
							if(!empty($display_amount_raised_percentage_number))
							$output.='<div class="mittun-thermometer-progress-marker-text">'.$percent.'%</div>';
							$output.='</div>
							<div class="mittun-clear-fix"> </div>
						</div>';
				}
				if(!empty($display_primary_btn))
				$output.="<div class='mittun-campaign-link'><a href='$donation_url' target='_blank'>".$primary_btn_text."</a></div>";


				$output.="</div>";

			}
			$output.='</div>';
			$output.='</div>';
			$output.='<div class="mittun-clear-fix"> </div>';
		}
		return $output;
	}

	function mittun_classy_event_shortcode_callback($atts)
	{
		$atts = extract (shortcode_atts( array(
			'id' => 0,
		), $atts, 'mittun_classy' ));

		if(!empty($id) && get_post_type($id)!='mittun-event')
			return false;

		$count=get_post_meta($id,'_classy_event_count',true);
		$skin=get_post_meta($id,'_classy_event_skin',true);
		$display_type=get_post_meta($id,'_classy_event_display_type',true);
		$column=get_post_meta($id,'_classy_event_column',true);
		$display_title = get_post_meta($id,'_classy_event_display_title',true);
		$display_image = get_post_meta($id,'_classy_event_display_image',true);
		$heading_color=get_post_meta($id,'_classy_event_heading_color',true);
		$heading_color=!empty($heading_color)?$heading_color:mittun_classy_get_option('heading_color','mittun_classy_color');
		$display_intro_text = get_post_meta($id,'_classy_event_display_intro_text',true);
		$intro_text_color = get_post_meta($id,'_classy_event_intro_text_color',true);
		$intro_text_color=!empty($intro_text_color)?$intro_text_color:mittun_classy_get_option('intro_text_color','mittun_classy_color');
		$display_goal_amount = get_post_meta($id,'_classy_event_display_goal_amount',true);
		$goal_amount_text_color = get_post_meta($id,'_classy_event_goal_amount_text_color',true);
		$goal_amount_text_color=!empty($goal_amount_text_color)?$goal_amount_text_color:mittun_classy_get_option('goal_amount_text_color','mittun_classy_color');
		$display_amount_raised = get_post_meta($id,'_classy_event_display_amount_raised',true);
		$amount_raised_text_color = get_post_meta($id,'_classy_event_amount_raised_text_color',true);
		$amount_raised_text_color=!empty($amount_raised_text_color)?$amount_raised_text_color:mittun_classy_get_option('amount_raised_text_color','mittun_classy_color');
		$display_amount_raised_heading = get_post_meta($id,'_classy_event_display_amount_raised_heading',true);
		$amount_raised_heading = get_post_meta($id,'_classy_event_amount_raised_heading',true);
		$display_amount_raised_percentage_number = get_post_meta($id,'_classy_event_display_amount_raised_percentage_number',true);
		$display_progress_bar = get_post_meta($id,'_classy_event_display_progress_bar',true);
		$progress_bar_style = get_post_meta($id,'_classy_event_progress_bar_style',true);

		$progress_bar_color=get_post_meta($id,'_classy_event_progress_bar_color',true);
		$progress_bar_color=!empty($progress_bar_color)?$progress_bar_color:mittun_classy_get_option('progress_bar_color','mittun_classy_color');
		$progress_bar_text_color=get_post_meta($id,'_classy_event_progress_bar_text_color',true);
		$progress_bar_text_color=!empty($progress_bar_text_color)?$progress_bar_text_color:mittun_classy_get_option('progress_bar_text_color','mittun_classy_color');
		$progress_bar_marker_color=get_post_meta($id,'_classy_event_progress_bar_marker_color',true);
		$progress_bar_marker_color=!empty($progress_bar_marker_color)?$progress_bar_marker_color:mittun_classy_get_option('progress_bar_marker_color','mittun_classy_color');
		$display_primary_btn=get_post_meta($id,'_classy_event_display_primary_btn',true);
		$primary_btn_text=get_post_meta($id,'_classy_event_primary_btn_text',true);
		$primary_btn_text_color=get_post_meta($id,'_classy_event_primary_btn_text_color',true);
		$primary_btn_text_color=!empty($primary_btn_text_color)?$primary_btn_text_color:mittun_classy_get_option('primary_btn_text_color','mittun_classy_color');
		$primary_btn_bg_color=get_post_meta($id,'_classy_event_primary_btn_bg_color',true);
		$primary_btn_bg_color=!empty($primary_btn_bg_color)?$primary_btn_bg_color:mittun_classy_get_option('primary_btn_bg_color','mittun_classy_color');

		$classy_event_trans = '_classypress_event_trans_' . $id;

		$campaigns = get_transient($classy_event_trans);

		$rand='event-'.$id;
		$output='';

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {
			require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			if(!$campaigns) {

					$campaigns=array();
					$filter='status=active';
					switch ($display_type) {
						case 'all':
							$filter='status=active';
							break;
						case 'current':
							$filter=',ended_at'.urlencode('>').date('Y-m-d\TH:i:s');
							break;
						case 'past':
							$filter=',ended_at'.urlencode('<').date('Y-m-d\TH:i:s');
							break;
						case 'upcoming':
							$filter=',started_at'.urlencode('>').date('Y-m-d\TH:i:s');
							break;
						default:
							$filter='status=active';
					}


						$count = 20; //temporary addition added 9.18.2018

					if($count<100){
						$campaigns_list=$classy->get_campaigns(array('aggregates' => 'true', 'per_page'=>$count,'with'=>'organization','filter'=>$filter));
						$campaigns=$campaigns_list->data;

					}else{
						$campaign_first=$classy->get_campaigns(array('aggregates' => 'true', 'per_page'=>1,'page'=>1,'filter'=>'status=active'));//to get other data i.e. total
						$total_campaign=!empty($campaign_first->total)?$campaign_first->total:0;

						//$per_page=100;//this the max limit
						$per_page = 20; // Temporary added 9.18.2018

						if(!empty($total_campaign))
						{
							$pages = ceil($total_campaign / $per_page);
							for($i=1;$i<=$pages;$i++)
							{
								$campaign_per_page=$classy->get_campaigns(array('aggregates'=>'true', 'per_page'=>$per_page,'page'=>$i,'with'=>'organization','filter'=>$filter));

								if(!empty($campaign_per_page->data)){
									foreach($campaign_per_page->data as $campaign_per_page)
									{
										$campaigns[]=$campaign_per_page;
									}
								}
							}

						}
					}

					set_transient($classy_event_trans, $campaigns, 1 * HOUR_IN_SECONDS);
			}

			if(!empty($campaigns))
			{


				$output.='<style type="text/css">
				.event-style-'.$rand.' .mittun-thermometer {border-left:1px solid '.$progress_bar_color.'; border-right:1px solid '.$progress_bar_color.';}
				.event-style-'.$rand.' .mittun-thermometer-progress { background:'.$progress_bar_color.'; }
				.event-style-'.$rand.' .mittun-thermometer-progress-marker { background:'.$progress_bar_marker_color.'; }
				.event-style-'.$rand.' .mittun-thermometer-progress-marker-text{color:'.$progress_bar_text_color.';}
				.event-style-'.$rand.' .mittun-thermometer-value span {color:'.$amount_raised_text_color.';}
				.event-style-'.$rand.' .mittun-campaign-link a {color:'.$primary_btn_text_color.';background:'.$primary_btn_bg_color.';}';


				if($progress_bar_style=='style_2')
				{
					$output.='.event-style-'.$rand.' .mittun-campaign-link a{border-radius:20px;}';
				}
				$output.='</style>';

				$output.='<div class="classypress-master events-container-master'.$skin.'">';
				$output.='<div class="classypress-inner events-container-inner">';
				foreach($campaigns as $campaign)
				{
					if(empty($campaign->goal))
						continue;

					$classy_campaign_overview_trans = '_classypress_campaign_overview_' . $campaign->id;

					$campaign_overview = get_transient($classy_campaign_overview_trans);

					if(!$campaign_overview) {
						$campaign_overview=$classy->get_campaign_overview($campaign->id,array('aggregates'=>'true','filter'=>'status=active'));
						set_transient($classy_campaign_overview_trans, $campaign_overview, 1 * HOUR_IN_SECONDS);
					}

					if($campaign->goal<1)
						$percent=0;
					else
						$percent= @round(floatval(str_replace(',', '',$campaign_overview->total_gross_amount)) * 100 / floatval(str_replace(',', '',$campaign->goal)));

					$cover_image=!empty($campaign->logo_id)?$campaign->logo_url:MITTUN_CLASSY_URL.'/img/no-image.png';
					$output.="<div id='event-".$id."-module-".$campaign->id."' class='".$progress_bar_style.(($column==3) ? ' event-col-3' : (($column==2) ? ' event-col-2' : ' event-col-1'))." event-container-classypress event-style-".$rand."'>";

					if(!empty($display_title))
						$output.='<div><h2 style="color:'.$heading_color.'">'.$campaign->name.'</h2></div>';
					if(!empty($cover_image) && !empty($display_image))
						$output.="<div class='eventImg'><img src='".$cover_image."'  /></div>";
					if(!empty($display_intro_text))
						$output.='<div style="color:'.$intro_text_color.'">'.$campaign->default_page_appeal.'</div>';
					if(!empty($display_amount_raised)){
						$output.="<div class='eventraise' style='color:".$amount_raised_text_color."'>";
						if(!empty($display_amount_raised_heading))
							$output.=$amount_raised_heading.":&nbsp;&#36;";
						$output.=number_format(round($campaign_overview->total_gross_amount))."</div>";
						if(!empty($display_goal_amount))
							$output.="<div class='eventgoal' style='color:".$goal_amount_text_color."'>".__('Goal','mittun_classy').":&nbsp;&#36;".number_format(round($campaign->goal))."</div>";
						$output.='<div class="mittun-clear-fix"> </div>';
					}

					if(!empty($display_progress_bar)){
						$output.='<div class="mittun-thermometer">
								<div class="mittun-thermometer-progress" style="width:'.$percent.'%;">
									<div class="mittun-thermometer-progress-marker"></div>
									<div class="mittun-clear-fix">
								</div>';
								if(!empty($display_amount_raised_percentage_number))
								$output.='<div class="mittun-thermometer-progress-marker-text">'.$percent.'%</div>';
								$output.='</div>
								<div class="mittun-clear-fix"> </div>
							</div>';
					}
					if(!empty($display_primary_btn))
						$output.="<div class='mittun-campaign-link'><a href='https://www.classy.org/checkout/donation?eid=".$campaign->id."' target='_blank'>".$primary_btn_text."</a></div>";

					$output.="</div>";
				}
				$output.='</div>';
				$output.='</div>';
				$output.='<div class="mittun-clear-fix"> </div>';
			}
		}

		return $output;
	}

	function mittun_classy_activities_loop($activities=array(),$arg=array())
	{
		$output=false;
		if(!empty($activities))
		{
			extract($arg);
			foreach($activities as $activity)
			{
				$temp_str='';
				switch($activity->type)
				{
					case 'donation_created':
					$temp_str.='<span class="activity-feed-author">'.$activity->member->first_name.'&nbsp'.$activity->member->last_name.'</span>&nbsp<span class="activity-feed-action">'.__('donated','mittun_classy').'&nbsp'.$activity->link_text.'</span>';
					break;
					case 'fundraising_team_joined':
					$temp_str.='<span class="activity-feed-author">'.$activity->member->first_name.'&nbsp'.$activity->member->last_name.'</span>&nbsp<span class="activity-feed-action">'.__('joined','mittun_classy').'&nbsp'.$activity->link_text.'</span>';
					break;
					case 'fundraising_page_created':
					$temp_str.='<span class="activity-feed-author">'.$activity->member->first_name.'&nbsp'.$activity->member->last_name.'</span>&nbsp<span class="activity-feed-action">'.__('created page','mittun_classy').'&nbsp'.$activity->link_text.'</span>';
					break;
					case 'fundraising_team_created':
					$temp_str.='<span class="activity-feed-author">'.$activity->member->first_name.'&nbsp'.$activity->member->last_name.'</span>&nbsp<span class="activity-feed-action">'.__('created team','mittun_classy').'&nbsp'.$activity->link_text.'</span>';
					break;
					case 'campaign_created':
					$temp_str.='<span class="activity-feed-author">'.$activity->member->first_name.'&nbsp'.$activity->member->last_name.'</span>&nbsp<span class="activity-feed-action">'.__('created campaign','mittun_classy').'&nbsp'.$activity->link_text.'</span>';
					break;
				}

				if(!empty($temp_str))
				{
					$output.='<div class="activity-feed-element">';
					if(!empty($display_activity_profile_picture)){
						if(!empty($activity->member->thumbnail_small))
							$output.='<img src="'.$activity->member->thumbnail_small.'">';
						else
							$output.='<img src="'.MITTUN_CLASSY_URL.'/img/user.png">';
					}
					$output.=$temp_str;
					$output.='<span class="activity-feed-time">'.mittun_classy_time_ago(strtotime($activity->created_at)).'</span>';
					$output.='</div>';
				}
			}
		}

		return $output;
	}

	function mittun_classy_donation_loop($donations=array(),$arg=array())
	{
		$output=false;
		if(!empty($donations))
		{
			extract($arg);
			foreach($donations as $donation)
			{
				$output.='<div class="activity-feed-element">';
				$output.='<span class="activity-feed-author">'.$donation->member_name.'</span>&nbsp<span class="activity-feed-action">'.__('donated','mittun_classy').'&nbsp$'.$donation->total_gross_amount.'</span>';
				$output.='<span class="activity-feed-time">'.mittun_classy_time_ago(strtotime($donation->purchased_at)).'</span>';
				$output.='</div>';
			}
		}
		return $output;
	}

	function mittun_classy_more_activity()
	{
		extract($_POST);
		$output=array('loop_data'=>'','current_page'=>'','error'=>false);
		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		$classy_campaign_more_activity_trans = '_classypress_campaign_more_activity_' . $organisation_id;
		$campaign_activity = get_transient($classy_campaign_more_activity_trans);

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id))
		{
			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			if($basic=='campaign')
			{
				$campaign_id=get_post_meta($id,'_classy_campaign_id',true);
				$account_activity_type=get_post_meta($id,'_classy_campaign_account_activity_type',true);
				$account_activity_limit=get_post_meta($id,'_classy_campaign_account_activity_limit',true);

				if(!$campaign_activity) {
					$req_arg=array('aggregates'=>'true','per_page'=>$account_activity_limit,'sort'=>'created_at:desc','page'=>($current_page+1));
					if($account_activity_type=='donation') {
						$req_arg['filter'].='type=donation_created';
					}

					$campaign_activity=$classy->get_campaign_activity($campaign_id,$req_arg);

					set_transient($classy_campaign_more_activity_trans, $campaign_activity);
				}

				$display_activity_profile_picture=get_post_meta($id,'_classy_campaign_display_activity_profile_picture',true);
				if(!empty($campaign_activity->data)) {
					$output['loop_data']=$this->mittun_classy_activities_loop($campaign_activity->data,array('display_activity_profile_picture'=>$display_activity_profile_picture));
					$output['current_page']=($current_page+1);
				} else {
					$output['error']=true;
				}
			}
		}
		echo json_encode($output);
		die;
	}

	function mittun_classy_more_donation()
	{
		extract($_POST);
		$output=array('loop_data'=>'','current_page'=>'','error'=>false);

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		$classy_donation_trans = '_classypress_more_donation_' . $organisation_id;
		$donation_list = get_transient($classy_donation_trans);

		if(!$donation_list) {
			require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

			$donation_list = array();

			if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {
				$classy = new Classy($client_id,$client_secret,$organisation_id);//v2

				$campaign_id = get_post_meta($id,'_classy_campaign_id',true);
				$display_donation_type = get_post_meta($id,'_classy_campaign_display_donation_type',true);
				$donation_limit = get_post_meta($id,'_classy_campaign_donation_limit',true);

				$req_arg = array('aggregates'=>'true','per_page'=>$donation_limit,'sort'=>'created_at:desc','filter'=>'status=success');

				if($display_donation_type == 'offline') {
					$req_arg['filter'] .= ',payment_method=Offline';
				}

				$donation_list = $classy->get_campaign_transactions($campaign_id,$req_arg);
			}

			set_transient($classy_donation_trans, $donation_list, 1 * HOUR_IN_SECONDS);
		}

		if(!empty($donation_list->data)) {
			$output['loop_data']=$this->mittun_classy_donation_loop($donation_list->data);
			$output['current_page']=($current_page+1);
		} else {
			$output['error'] = true;
		}

		echo json_encode($output);
		die;
	}
}
add_action('init',function(){new mittun_classy_shortcode();},20);
?>
