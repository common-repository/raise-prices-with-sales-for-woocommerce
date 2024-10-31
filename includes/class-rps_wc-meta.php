<?php

/**
 * The Meta Class for handling meta data
 */

class RPS_WC_Meta {
	
	/**
	 * Getting the Meta for RPS Sales
	 * @param  int $post_id 
	 * @return mixed          
	 */
	public static function get( $post_id, $key =  '_rps_sales' ) {	
		return get_post_meta( $post_id, $key, true );
	}

	/**
	 * Getting the Meta for RPS Sales
	 * @param  int $post_id 
	 * @param  mixes $value
	 * @return mixed          
	 */
	public static function update( $post_id, $value, $key = '_rps_sales' ) {	
		return update_post_meta( $post_id, $key, $value );
	}

	/**
	 * Delete the Meta for RPS Sales
	 * @param  int $post_id 
	 * @return mixed          
	 */
	public static function delete( $post_id, $key =  '_rps_sales' ) {	
		return delete_post_meta( $post_id, $key );
	}
}