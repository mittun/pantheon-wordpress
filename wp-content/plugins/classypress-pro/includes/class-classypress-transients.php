<?php

if( ! class_exists( 'ClassyPress_Transients' ) ) {
  class ClassyPress_Transients {

    public $trans_prefix;

    public function __construct() {
      $this->trans_prefix = '_classypress_';

      add_action( 'wp_ajax_classypress_clear_cache', array( $this, 'delete_classypress_transients' ) );
    }

    /**
     * Get all transients set by ClassyPress from database
     *
     * @param  string $prefix The transient prefix
     * @return array          Returns an array of transients to delete
     */
    public function get_transients_by_prefix( $prefix ) {
      global $wpdb;

      $prefix = '_transient_' . $prefix;

      $sql = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '_transient__classypress_%'";

      $transients = $wpdb->get_results( $wpdb->prepare( $sql, $prefix ), ARRAY_A );

      if( $transients && ! is_wp_error( $transients ) ) {
        return $transients;
      }

      return false;
    }

    /**
     * Delete all transients from database set by ClassyPress
     *
     * @param  array $transients  Array of transients to delete
     * @return bool               True/False if successful
     */
    public function delete_transients_from_keys( $transients ) {
      if ( ! isset( $transients ) ) {
    		return false;
    	}
    	// If we get a string key passed in, might as well use it correctly
    	if ( is_string( $transients ) ) {
    		$transients = array( array( 'option_name' => $transients ) );
    	}
    	// If its not an array, we can't do anything
    	if ( ! is_array( $transients ) ) {
    		return false;
    	}

    	$results = array();

      foreach( $transients as $transient ) {
        if( is_array( $transient ) ) {
          foreach( $transient as $trans ) {
            $trans = str_replace('_transient_', '', $trans);
            delete_transient( $trans );
          }
        }
      }

      return true;
    }

    /**
     * AJAX Callback to delete transients
     */
    public function delete_classypress_transients() {
      $transients = $this->get_transients_by_prefix( $this->trans_prefix );
      $res = $this->delete_transients_from_keys( $transients );

      if( $res ) {
        echo 'success';
      } else {
        echo 'fail';
      }

      die();
    }
  }

  new ClassyPress_Transients();
}
