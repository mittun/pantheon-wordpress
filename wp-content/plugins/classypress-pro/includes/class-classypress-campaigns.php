<?php

/**
* Functions for managing ClassyPress Camapaigns
*
* @package classypress-pro
* @subpackage classypress-pro/includes
* @version 1.0.0
* @since 1.6.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('ClassyPress_Campaigns')) {
  class ClassyPress_Campaigns {

    /**
     * Return the Classy Campaigns to the public
     *
     * @return array  Campaigns from Classy
     * @since 1.6.0
     */
    public function get_classypress_campaigns() {
      return $this->get_classy_campaigns();
    }

    /**
    * Retrieve Classy Campaign data from Classy API
    *
    * @return array  Array of campaign data from Classy
    * @since 1.6.0
    */
    private function get_classy_campaigns() {
      $client_id = mittun_classy_get_option('client_id', 'mittun_classy');
      $client_secret = mittun_classy_get_option('client_secret', 'mittun_classy');
      $organisation_id = mittun_classy_get_option('organisation_id', 'mittun_classy');

      $campaigns = array();

      if(!empty($client_id) && !empty($client_secret) && !empty($organisation_id)) {
        require_once(MITTUN_CLASSY_PATH.'/includes/classy.php');

        $classy = new Classy($client_id, $client_secret, $organisation_id);//v2

        $campaign_first = $classy->get_campaigns(array('per_page' => 1, 'page' => 1, 'filter' => 'status=active')); //to get other data i.e. total
        $total_campaign = !empty($campaign_first->total) ? $campaign_first->total : 0;
        $per_page = 100; //this the max limit

        if(!empty($total_campaign)) {
          $pages = ceil($total_campaign / $per_page);
          for($i=1; $i<=$pages; $i++) {
            $campaign_per_page = $classy->get_campaigns(array('per_page' => $per_page, 'page' => $i, 'filter' => 'status=active'));

            if(!empty($campaign_per_page->data)) {
              foreach($campaign_per_page->data as $campaign_per_page) {
                $campaigns[] = $campaign_per_page;
              }
            }
          }
        }
      }

      return $campaigns;
    }

  }
}
