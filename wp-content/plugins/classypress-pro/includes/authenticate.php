<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_authenticate{

	var $endpoint_url = 'https://mittun.co/';

	var $key = '';

	function __construct($key='')
	{
		if(!empty($key))
		$this->key=$key;
		else
		$this->key=get_option('mittun_classy_key');
	}

	function authenticate()
	{
		/*if(empty($this->key))
		return false;
		$response = wp_remote_post( $this->endpoint_url, array(
			'timeout' => 45,
			'sslverify'=>false,
			'body' => array( 'action'=>'validate','key' => $this->key, 'ip' =>$_SERVER['REMOTE_ADDR'] ),
			)
		);
		$body = json_decode(wp_remote_retrieve_body($response));

		if(!empty($body->valid))
		return true;
		return false;*/
		return true;

	}
}

?>
