<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_widget{

	function __construct()
	{
		if(defined('IS_AUTHENTICATE') && IS_AUTHENTICATE)
		{
			add_action( 'wp_dashboard_setup', array($this,'mittun_classy_dashboard_widget' ));

		}
	}
	function mittun_classy_dashboard_widget(){
		// wp_add_dashboard_widget('mittun_classy_dashboard_widget',__('Mittun Classy Statistics','mittun_classy'),array($this,'mittun_classy_dashboard_widget_callback' ));
	}
	function mittun_classy_dashboard_widget_callback(){

		require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

		$client_id=mittun_classy_get_option('client_id','mittun_classy');
		$client_secret=mittun_classy_get_option('client_secret','mittun_classy');
		$organisation_id=mittun_classy_get_option('organisation_id','mittun_classy');

		$output='';

		if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)){

			$classy=new Classy($client_id,$client_secret,$organisation_id);//v2
			$transaction_init=$classy->get_transactions(array('aggregates'=>'true','page'=>1,'per_page'=>100));

			$today=$today_count=$this_week=$this_month=$last_month=$this_year=0;
			if(!empty($transaction_init->last_page))
			{

				for($i=1;$i<=$transaction_init->last_page;$i++)
				{
					$transactions=$classy->get_transactions(array('aggregates'=>'true','page'=>$i,'per_page'=>100));
					if(!empty($transactions->data)){
						foreach($transactions->data as $transaction)
						{
							if($transaction->status=='success'){
								//check for last 24 hours/last day
								if(strtotime($transaction->purchased_at) >= strtotime('-24 hours')){
									$today+=$transaction->total_gross_amount;
									$today_count+=1;
								}
								//check for last week
								else if(strtotime($transaction->purchased_at) >= strtotime('-7 days'))
									$this_week+=$transaction->total_gross_amount;
								//check for this month
								else if(strtotime($transaction->purchased_at) >= strtotime('-30 days'))
									$this_month+=$transaction->total_gross_amount;
								//check for last month
								else if(strtotime($transaction->purchased_at) <= strtotime('-30 days') && strtotime($transaction->purchased_at) >= strtotime('-60 days'))
									$last_month+=$transaction->total_gross_amount;
								//check for this year
								else if(strtotime($transaction->purchased_at) >= strtotime('-365 days'))
									$this_year+=$transaction->total_gross_amount;
							}
						}
					}
				}
			}

			?>
			<table style="width:100%">
				<tr>
					<td style="text-align:center;" colspan="2">
						<p>
						<?php echo date('F j,Y');?>
						</p>
						<p>
						<?php _e('Happy','mittun_classy');echo '&nbsp;'. date('l');?>
						</p>
						<p>
							<h3 style="color:green;font-size:32px;">$<?php echo number_format($today,2); ?></h3>
						</p>
						<p>
						<?php echo $today_count.'&nbsp;';_e('donations today','mittun_classy')?>
						</p>
					</td>
				</tr>
				<tr>
					<td style="width:48%;text-align:center;border:1px solid grey;">
						<h5 style="font-size:25px;color:green;">$<?php echo number_format($this_week,2); ?></h5>
						<?php _e('This week','mittun_classy'); ?>
					</td>
					<td style="width:48%;text-align:center;border:1px solid grey;">
						<h5 style="font-size:25px;color:green;">$<?php echo number_format($this_month,2); ?></h5>
						<?php _e('This month','mittun_classy'); ?>
					</td>
				</tr>
				<tr>
					<td style="width:48%;text-align:center;border:1px solid grey;">
						<h5 style="font-size:25px;color:green;">$<?php echo number_format($last_month,2); ?></h5>
						<?php _e('Last month','mittun_classy'); ?>
					</td>
					<td style="width:48%;text-align:center;border:1px solid grey;">
						<h5 style="font-size:25px;color:green;">$<?php echo number_format($this_year,2); ?></h5>
						<?php _e('This year','mittun_classy'); ?>
					</td>
				</tr>
			</table>

			<?php


		}
		else
		echo __("Classy set-up is not complete",'mittun_classy');
	}
}
add_action('init',function(){new mittun_classy_widget();},20);
?>
