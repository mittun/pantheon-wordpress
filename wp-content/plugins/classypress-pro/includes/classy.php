<?php
/*
classy.org api v2
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Classy{

	private $access_url='https://api.classy.org/oauth2/auth';
	private $end_url='https://api.classy.org/2.0/';
	private $client_id;
	private $client_secret;
	private $organisation_id;
    private $token_type;
    private $access_token;

	function __construct($client_id,$client_secret,$organisation_id){
		$this->client_id=$client_id;
		$this->client_secret=$client_secret;
		$this->organisation_id=$organisation_id;
	}

	public function get_remote_info($section,$additional_arg=array())
	{
		if(empty($this->token_type) || empty($this->access_token))
        {
            $access_response = wp_remote_post($this->access_url,array('body' => array( 'grant_type' => 'client_credentials', 'client_id' => $this->client_id,'client_secret'=>$this->client_secret)));
            $access_body = wp_remote_retrieve_body($access_response);
            if(!empty($access_body))
            {
                $access_body = json_decode($access_body);
                $this->token_type = $access_body->token_type;
                $this->access_token = $access_body->access_token;
            }
        }

        if(empty($this->token_type) || empty($this->access_token))
        {
            return false;
        }

        $arg=array(
			'timeout'=>10,
        	'headers'=>array('Authorization' => $this->token_type.' '.$this->access_token)
        );
        $url=str_replace('{id}',$this->organisation_id,$this->end_url.$section);


        if(!empty($additional_arg))
        {
            $url=add_query_arg( $additional_arg,$url );
        }

        $response = wp_remote_get($url,$arg);
        $body=wp_remote_retrieve_body($response);
        return json_decode($body);
	}

	/*
	============================================================
	Get	all	the	details	about a specific charity account.
	============================================================
	*/
	public function get_account_info($arg=array())
	{
		$account_info=$this->get_remote_info('organizations/{id}',$arg);
		return $account_info;
	}

	/*
	============================================================
	Get	all	site activity for a	specific account.Results returned based	on most recent activity.
	============================================================
	*/
	public function get_account_activity($arg=array())
	{
		$account_activity=$this->get_remote_info('organizations/{id}/activity',$arg);
		if(!empty($account_activity))
		return $account_activity;
		return false;
	}

	/*
	============================================================
	Retrieves the Engagement Settings for an Organization.
	============================================================
	*/
	public function get_engagement_settings($arg=array())
	{
		$engagement_settings=$this->get_remote_info('organizations/{id}/engagement-settings',$arg);
		return $engagement_settings;
	}

	/*
	============================================================
	Get	information	about matching sponsors for a campaign/event
	============================================================
	*/
	public function get_account_sponsor_matching($eid,$arg=array())
	{
		$account_sponsor_matching=$this->get_remote_info("campaigns/$eid/donation-matching-plans",$arg);
		return $account_sponsor_matching;
	}


	/*
	============================================================
	Get	an array of all	campaigns and events for a charity account.
	============================================================
	*/
	public function get_campaigns($arg=array())
	{
		$campaigns=$this->get_remote_info('organizations/{id}/campaigns',$arg);
		if(!empty($campaigns))
		    return $campaigns;
		return false;
	}

	/*
	============================================================
	Get	all	the	specific details about a campaign or event
	============================================================
	*/
	public function get_campaign_info($eid,$arg=array())
	{
		$campaign=$this->get_remote_info("campaigns/$eid/",$arg);
		return $campaign;

	}

	/*
	============================================================
	Get	all	the	specific details about a campaign or event
	============================================================
	*/
	public function get_campaign_overview($eid,$arg=array())
	{
		$campaign_overview=$this->get_remote_info("campaigns/$eid/overview",$arg);
		return $campaign_overview;

	}



	/*
	============================================================
	Get an array of all	tickets	for	a specified	campaign/event
	============================================================
	*/
	public function get_campaign_tickets($eid,$arg=array())
	{
		$tickets=$this->get_remote_info("campaigns/$eid/ticket-types",$arg);
		return $tickets;
	}

	/*
	============================================================
	Get activity for a specific campaign
	============================================================
	*/
	public function get_campaign_activity($eid,$arg=array())
	{
		$campaign_activity=$this->get_remote_info("campaigns/$eid/activity",$arg);
		if(!empty($campaign_activity))
		return $campaign_activity;
		return false;
	}

	/*
	============================================================
	Get	array of individual	fundraising	pages for a specific charity,campaign/event,designation	or	member
	============================================================
	*/
	public function get_campaign_fundraiser_pages($eid,$arg=array())
	{
		$campaign_fundraiser_pages=$this->get_remote_info("campaigns/$eid/fundraising-pages",$arg);
		return $campaign_fundraiser_pages;
	}

	/*
	============================================================
	Get	array of individual	fundraising	pages
	============================================================
	*/
	public function get_fundraiser_pages($arg=array())
	{
		$fundraiser_pages=$this->get_remote_info("organizations/{id}/fundraising-pages",$arg);
		return $fundraiser_pages;
	}

	/*
	============================================================
	Get	the	details	for	a specific individual fundraising page
	============================================================
	*/

	public function get_fundraiser_page_info($fid,$arg=array())
	{
		$fundraiser_info=$this->get_remote_info("fundraising-pages/$fid",$arg);
		return $fundraiser_info;
	}

	/*
	============================================================
	Get activity for a specific fundraising page
	============================================================
	*/
	public function get_fundraiser_page_activity($fid,$arg=array())
	{
		$fundraiser_page_activity=$this->get_remote_info("fundraising-pages/$fid/activity",$arg);
		if(!empty($fundraiser_page_activity))
		return $fundraiser_page_activity;
		return false;
	}

	/*
	============================================================
	Get	a list of the top fundraising teams	ranked by total	$ raised for a specific	campaign
	============================================================
	*/

	public function get_campaign_fundraiser_teams($eid,$arg=array())
	{
		$campaign_fundraiser_teams=$this->get_remote_info("campaigns/$eid/fundraising-teams",$arg);
		return $campaign_fundraiser_teams;
	}
	/*
	============================================================
	Get	a list of the top fundraising teams	ranked by total	$ raised for a specific	campaign
	============================================================
	*/

	public function get_fundraiser_teams($arg=array())
	{
		$fundraiser_teams=$this->get_remote_info("organizations/{id}/fundraising-teams",$arg);
		return $fundraiser_teams;
	}
	/*
	============================================================
	Get	the	details	for	a specific fundraising team	page
	============================================================
	*/
	public function get_fundraiser_team_info($tid,$arg=array())
	{
		$team_info=$this->get_remote_info("fundraising-teams/$tid",$arg);
		return $team_info;
	}

	/*
	============================================================
	Get activity for a specific fundraising teams
	============================================================
	*/
	public function get_fundraiser_team_activity($tid,$arg=array())
	{
		$fundraiser_team_activity=$this->get_remote_info("fundraising-teams/$tid/activity",$arg);
		if(!empty($fundraiser_team_activity))
		return $fundraiser_team_activity;
		return false;
	}

	/*
	============================================================
	Get	array of donations over	a specific date	range
	============================================================
	*/

	public function get_transactions($arg=array())
	{
		$donations=$this->get_remote_info("organizations/{id}/transactions",$arg);
		return $donations;
	}

	/*
	============================================================
	Get	array of donations of a campaign over a	specific date range
	============================================================
	*/

	public function get_campaign_transactions($eid,$arg=array())
	{
		$donations=$this->get_remote_info("campaigns/$eid/transactions",$arg);
		return $donations;
	}

	/*
	============================================================
	Get	array of recurring donation	profiles
	============================================================
	*/
	public function get_recurring_donations($arg=array())
	{
		$recurring_donations=$this->get_remote_info("organizations/{id}/recurring-donation-plans",$arg);
		return $recurring_donations;
	}

	/*
	============================================================
	Get	array of recurring	donation profiles of a campaign
	============================================================
	*/
	public function get_campaign_recurring_donations($eid,$arg=array())
	{
		$recurring_donations=$this->get_remote_info("campaigns/$eid/recurring-donation-plans",$arg);
		return $recurring_donations;
	}

	/*
	============================================================
	Get	all	the	details	about a	specific project.Projects are also referred	to	as
designations and terminology	is sometimes interchanged on Classy.
	============================================================
	*/

	public function get_project_info($pid,$arg=array())
	{
		$project_info=$this->get_remote_info("designation/$pid",$arg);
		return $project_info;
	}



}
?>
