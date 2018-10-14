<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mittun_classy_import{

	function __construct()
	{
		$this->list_themes();
	}

	function list_themes() {
		$path = MITTUN_CLASSY_PATH.'/themes';

		// directory handle
		$dir = dir($path);

		require_once(MITTUN_CLASSY_PATH . '/includes/partials/classy-import.php');
	}

	static function mittun_classy_import_campaign($dir)
	{
		$path=MITTUN_CLASSY_PATH.'/themes/'.$dir;
		$file=$path.'/import.xml';
		$css=$path.'/campaign.css';

		if(is_dir($path)){

			if(file_exists($file))
			{
				$posts = array();$inserted_post=false;

				$internal_errors = libxml_use_internal_errors(true);

				$dom = new DOMDocument;
				$old_value = null;
				if ( function_exists( 'libxml_disable_entity_loader' ) ) {
					$old_value = libxml_disable_entity_loader( true );
				}
				$success = $dom->loadXML( file_get_contents( $file ) );
				if ( ! is_null( $old_value ) ) {
					libxml_disable_entity_loader( $old_value );
				}

				if ( ! $success || isset( $dom->doctype ) ) {
					return false;
				}

				$xml = simplexml_import_dom( $dom );
				unset( $dom );

				// halt if loading produces an error
				if ( ! $xml )
					return false;

				$wxr_version = $xml->xpath('/rss/channel/wp:wxr_version');
				if ( ! $wxr_version )
					return false;

				$wxr_version = (string) trim( $wxr_version[0] );
				// confirm that we are dealing with the correct file format
				if ( ! preg_match( '/^\d+\.\d+$/', $wxr_version ) )
					return false;

				$base_url = $xml->xpath('/rss/channel/wp:base_site_url');
				$base_url = (string) trim( $base_url[0] );

				$namespaces = $xml->getDocNamespaces();
				if ( ! isset( $namespaces['wp'] ) )
					$namespaces['wp'] = 'http://wordpress.org/export/1.1/';
				if ( ! isset( $namespaces['excerpt'] ) )
					$namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';


				// grab posts
				foreach ( $xml->channel->item as $item ) {
					$post = array(
						'post_title' => '',//leave the post title blank
						'guid' => '',//leave the guid blank
					);

					$dc = $item->children( 'http://purl.org/dc/elements/1.1/' );
					$post['post_author'] = get_current_user_id();//(string) $dc->creator;

					$content = $item->children( 'http://purl.org/rss/1.0/modules/content/' );
					$excerpt = $item->children( $namespaces['excerpt'] );
					$post['post_content'] = (string) $content->encoded;
					$post['post_excerpt'] = (string) $excerpt->encoded;

					$wp = $item->children( $namespaces['wp'] );
					$post['comment_status'] = 'closed';
					$post['ping_status'] = 'closed';
					$post['status'] = 'publish';
					$post['post_parent'] = 0;
					$post['menu_order'] = 0;
					$post['post_type'] = $wp->post_type;
					$post['post_password'] = '';
					$post['is_sticky'] = 0;


					foreach ( $wp->postmeta as $meta ) {
						$post['postmeta'][] = array(
							'key' => (string) $meta->meta_key,
							'value' => (string) $meta->meta_value
						);
					}


					$posts[] = $post;
				}

				if(!empty($posts))
				{
					foreach($posts as $p){
						$postmetas=$p['postmeta'];
						unset($p['postmeta']);
						$inserted_post=wp_insert_post($p);
						if(!is_wp_error($inserted_post))
						{
							foreach($postmetas as $meta)
							{
								$value=maybe_unserialize($meta['value']);
								update_post_meta( $inserted_post, $meta['key'],$value );
							}
						}
						break;//Import only one if have more than one
					}
				}

				//Import css (non-mandatory)
				if(file_exists($css))
				{
					$new_css=file_get_contents($css);
					$custom_css=mittun_classy_get_option('custom_css','mittun_classy_advanced');

					update_option( 'mittun_classy_advanced', array('custom_css'=>$custom_css.$new_css));

				}

				return $inserted_post;
			}
		}


		return false;

	}
}
?>
