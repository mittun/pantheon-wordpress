<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$supported_currency=array('AUD'=>'Australian Dollar','BRL'=>'Brazilian Real','CAD'=>'Canadian Dollar','CZK'=>'Czech Koruna','DKK'=>'Danish Krone','EUR'=>'Euro','HKD'=>'Hong Kong Dollar','HUF'=>'Hungarian Forint','ILS'=>'Israeli New Sheqel','JPY'=>'Japanese Yen','MYR'=>'Malaysian Ringgit','MXN'=>'Mexican Peso','NOK'=>'Norwegian Krone','NZD'=>'New Zealand Dollar','PHP'=>'Philippine Peso','PLN'=>'Polish Zloty','GBP'=>'Pound Sterling','RUB'=>'Russian Ruble','SGD'=>'Singapore Dollar','SEK'=>'Swedish Krona','CHF'=>'Swiss Franc','TWD'=>'Taiwan New Dollar','THB'=>'Thai Baht','TRY'=>'Turkish Lira','USD'=>'U.S. Dollar');

$supported_currency_to_symbol=array('AUD'=>'&#36','BRL'=>'&#82;&#36;','CAD'=>'&#36;','CZK'=>'&#75;&#269','DKK'=>'&#107;&#114','EUR'=>'&#8364','HKD'=>'&#36;','HUF'=>'&#70;&#116','ILS'=>'&#8362;','JPY'=>'&#165;','MYR'=>'&#82;&#77','MXN'=>'&#36','NOK'=>'&#107;&#114','NZD'=>'&#36','PHP'=>'&#8369;','PLN'=>'&#122;&#322;','GBP'=>'&#163;','RUB'=>'&#1088;&#1091;&#1073','SGD'=>'&#36','SEK'=>'&#107;&#114','CHF'=>'&#67;&#72;&#70','TWD'=>'&#78;&#84;&#36','THB'=>'&#3647','TRY'=>'TRY','USD'=>'&#36');
$rec_donation_interval=array('D'=>'Day','W'=>'Week','M'=>'Month','Y'=>'Year');

//first check if it is authenticate,then initialize all other parts
add_action( 'init', 'is_authenticate',1 );
function is_authenticate()
{
	require_once(MITTUN_CLASSY_PATH.'/includes/authenticate.php');
	$obj=new mittun_classy_authenticate();

	define('IS_AUTHENTICATE',$obj->authenticate());

}

function mittun_classy_validate_license_key($key='')
{
	if(defined('IS_AUTHENTICATE') || !IS_AUTHENTICATE)
	define('IS_AUTHENTICATE',false);
	require_once(MITTUN_CLASSY_PATH.'/includes/authenticate.php');
	$obj=new mittun_classy_authenticate($key);
	return $obj->authenticate();
}


add_action( 'wp_enqueue_scripts', 'mittun_classy_front_scripts' );

function mittun_classy_front_scripts()
{
	wp_enqueue_style( 'mittun-classy-magnific-popup', MITTUN_CLASSY_URL.'/css/magnific-popup.css' );
	wp_enqueue_style( 'mittun-classy-sf-flash', MITTUN_CLASSY_URL.'/css/jquery.sf-flash.min.css' );
	wp_enqueue_style( 'mittun-classy-style', MITTUN_CLASSY_URL.'/css/classy-style.css' );

	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'mittun-classy-magnific-popup',MITTUN_CLASSY_URL.'/js/jquery.magnific-popup.js',array('jquery'),false,true);
	wp_enqueue_script( 'mittun-classy-sf-flash',MITTUN_CLASSY_URL.'/js/jquery.sf-flash.min.js',array('jquery'),false,true);
	wp_enqueue_script( 'mittun-classy-scripts',MITTUN_CLASSY_URL.'/js/classy-scripts.js',array('jquery'),false,true);
	$localize=array('ajax_url'=>admin_url('admin-ajax.php'));
	wp_localize_script( 'mittun-classy-scripts','mittunClassy',$localize) ;
}
/*
Custom function to get plugin options
*/
function mittun_classy_get_option($option,$settings='mittun_classy')
{
	$mittun_classy_settings=get_option($settings);

	if(empty($option))
	return false;

	if(!empty($mittun_classy_settings[$option]))
	return $mittun_classy_settings[$option];
	else
	return false;

}

add_action('wp_footer','mittun_classy_custom_css');
function mittun_classy_custom_css()
{
	$custom_css=mittun_classy_get_option('custom_css','mittun_classy_advanced');
	?>
    <style type="text/css">
    <?php
	echo $custom_css;
	?>
	</style>
	<?php
}

function mittun_classy_cron_intervals($schedules) {

	$schedules['minute'] = array(
		'interval' => 60,
		'display' => __('Once Per Minute')
	);

	return $schedules;
}
add_filter( 'cron_schedules', 'mittun_classy_cron_intervals');

function mittun_classy_time_ago($time_ago){
$cur_time 	= time();
$time_elapsed 	= $cur_time - $time_ago;
$seconds 	= $time_elapsed ;
$minutes 	= round($time_elapsed / 60 );
$hours 		= round($time_elapsed / 3600);
$days 		= round($time_elapsed / 86400 );
$weeks 		= round($time_elapsed / 604800);
$months 	= round($time_elapsed / 2600640 );
$years 		= round($time_elapsed / 31207680 );

$output='';
// Seconds
if($seconds <= 60){
	$output.= $seconds.__(" seconds ago",'mittun_classy');
}
//Minutes
else if($minutes <=60){
	if($minutes==1){
		$output.= __("one minute ago",'mittun_classy');
	}
	else{
		$output.= $minutes.__(" minutes ago",'mittun_classy');
	}
}
//Hours
else if($hours <=24){
	if($hours==1){
		$output.= __("an hour ago",'mittun_classy');
	}else{
		$output.= $hours.__(" hours ago",'mittun_classy');
	}
}
//Days
else if($days <= 7){
	if($days==1){
		$output.= __("yesterday",'mittun_classy');
	}else{
		$output.= $days.__(" days ago",'mittun_classy');
	}
}
//Weeks
else if($weeks <= 4.3){
	if($weeks==1){
		$output.= __("a week ago",'mittun_classy');
	}else{
		$output.= $weeks.__(" weeks ago",'mittun_classy');
	}
}
//Months
else if($months <=12){
	if($months==1){
		$output.= __("a month ago",'mittun_classy');
	}else{
		$output.= $months.__(" months ago",'mittun_classy');
	}
}
//Years
else{
	if($years==1){
		$output.= __("one year ago",'mittun_classy');
	}else{
		$output.= $years.__(" years ago",'mittun_classy');
	}
}
return $output;
}

add_action( 'wp_ajax_leaderboard_select', 'mittun_classy_leaderboard_select' );
add_action( 'wp_ajax_nopriv_leaderboard_select', 'mittun_classy_leaderboard_select' );
function mittun_classy_leaderboard_select()
{

	$leaderboard_campaign_id=$_POST['campaign_id'];

	$client_id=mittun_classy_get_option('client_id','mittun_classy');
	$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
	$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

	$output=array('teams'=>'<option value="">Select</option>','individual'=>'<option value="">Select</option>');
	//$per_page=100;//this the max limit
	$per_page = 20; // Temporary adjustment 9.18.2018

	$leaderboard_team_trans = '_classypress_leaderboard_teams_' . $client_id;
	$leaderboard_individual_trans = '_classypress_leaderboard_individuals_' . $client_id;

	$leaderboard_teams = get_transient($leaderboard_team_trans);
	$leaderboard_individuals = get_transient($leaderboard_individual_trans);

	if(!$leaderboard_teams || !$leaderboard_individuals) {
		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {
			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2

			if(!$leaderboard_teams) {
				$leaderboard_teams=array();
				if(!empty($leaderboard_campaign_id)) {
					$leaderboard_team_first=$classy->get_campaign_fundraiser_teams($leaderboard_campaign_id,array('aggregates'=>'true','per_page'=>1,'page'=>1,'filter'=>'status=active'));
				} else {
					$leaderboard_team_first=$classy->get_fundraiser_teams(array('aggregates'=>'true','per_page'=>1,'page'=>1,'filter'=>'status=active'));
				}

				$total_team=!empty($leaderboard_team_first->total)?$leaderboard_team_first->total:0;

				if(!empty($total_team)) {
					$pages = ceil($total_team / $per_page);
					for($i=1;$i<=$pages;$i++) {
						if(!empty($leaderboard_campaign_id)) {
							$team_per_page=$classy->get_campaign_fundraiser_teams($leaderboard_campaign_id,array('aggregates'=>'true','fields'=>'id,name','per_page'=>$per_page,'sort'=>'total_raised:desc','filter'=>'status=active'));
						} else {
							$team_per_page=$classy->get_fundraiser_teams(array('aggregates'=>'true','fields'=>'id,name','per_page'=>$per_page,'sort'=>'total_raised:desc','filter'=>'status=active'));
						}

						if(!empty($team_per_page->data)) {
							foreach($team_per_page->data as $team_per_page) {
								$leaderboard_teams[]=$team_per_page;
							}
						}
					}
				}
				set_transient($leaderboard_team_trans, $leaderboard_teams, 1 * HOUR_IN_SECONDS);
			}

			if(!$leaderboard_individuals) {
				$leaderboard_individuals = array();
				if(!empty($leaderboard_campaign_id)) {
					$leaderboard_individual_first=$classy->get_campaign_fundraiser_pages($leaderboard_campaign_id,array('aggregates'=>'true','per_page'=>1,'page'=>1,'filter'=>'status=active'));
				} else {
					$leaderboard_individual_first=$classy->get_fundraiser_pages(array('aggregates'=>'true','per_page'=>1,'page'=>1,'filter'=>'status=active'));
				}

				$total_individuals = !empty($leaderboard_individual_first->total) ? $leaderboard_individual_first->total : 0;

				if(!empty($total_individuals)) {
					$pages = ceil($total_individuals / $per_page);

					for($i=1;$i<=$pages;$i++) {
						if(!empty($leaderboard_campaign_id) ) {
							$individuals_per_page=$classy->get_campaign_fundraiser_pages($leaderboard_campaign_id,array('aggregates'=>'true','fields'=>'id,alias','per_page'=>$per_page,'sort'=>'total_raised:desc','filter'=>'status=active'));
						} else {
							$individuals_per_page=$classy->get_fundraiser_pages(array('aggregates'=>'true','fields'=>'id,alias','per_page'=>$per_page,'sort'=>'total_raised:desc','filter'=>'status=active'));
						}

						if(!empty($individuals_per_page->data)) {
							foreach($individuals_per_page->data as $individuals_per_page) {
								$leaderboard_individuals[]=$individuals_per_page;
							}
						}
					}
				}

				set_transient($leaderboard_individual_trans, $leaderboard_individuals, 1 * HOUR_IN_SECONDS);
			}
		}
	}


	if(!empty($leaderboard_teams)) {
		foreach($leaderboard_teams as $team) {
			$output['teams'].='<option value="'.$team->id.'" >'.$team->name.'</option>';
		}
	}

	if(!empty($leaderboard_individuals)) {
		foreach($leaderboard_individuals as $individual) {
			$output['individual'].='<option value="'.$individual->id.'">'.$individual->alias.'</option>';
		}
	}

	echo json_encode($output);
	die;
}

add_action( 'wp_ajax_import_campaign', 'mittun_classy_import_campaign' );
add_action( 'wp_ajax_nopriv_import_campaign', 'mittun_classy_import_campaign' );

function mittun_classy_import_campaign()
{
	$dir=$_POST['dir'];
	$output=array('error'=>true,'msg'=>__('Can\'t be imported','mittun_classy'),'redirect'=>'');
	require_once(MITTUN_CLASSY_PATH.'/includes/import.php');
	$imported_post= mittun_classy_import::mittun_classy_import_campaign($dir);
	if(!empty($imported_post))
	{
		$output['error']=false;
		$output['msg']=__('Imported','mittun_classy');
		$output['redirect']=admin_url('post.php?post='.$imported_post.'&action=edit');
	}
	echo json_encode($output);
	die;
}


?>
