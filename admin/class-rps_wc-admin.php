<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       ibenic.com
 * @since      1.0.0
 *
 * @package    Rps_wc
 * @subpackage Rps_wc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rps_wc
 * @subpackage Rps_wc/admin
 * @author     Igor Benić <i.benic@hotmail.com>
 */
class Rps_wc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Limit
	 * @var integer
	 */
	private $limit = 3;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->limit = apply_filters( 'rps_wc_sales_points_limit', 3 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rps_wc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rps_wc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rps_wc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
	 	// We want our script to be loaded only on edit or new post
	 	if( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
	 		return;
	 	}

		global $post;
	 	
	 	if( ! $post ) {
	 		return;
	 	}

	 	// We want our script to be loaded only on a product type post
	 	if( 'product' != $post->post_type ) {
	 		return;
	 	}
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rps_wc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rps_wc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rps_wc-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'rps_wc', array( 'limit' => $this->limit ) );
		wp_enqueue_script(  $this->plugin_name );
	}

	/**
	 * Add settings
	 *
	 * @param array $settings
	 */
	public function add_settings( $settings ) {
		$settings[] = array(
			'title' => __( 'Increase Price With Sales', 'woocommerce' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => 'product_increase_price_sales_options',
		);

		$settings[] = array(
			'title'           => __( 'Show in WooCommerce Info style', 'woocommerce' ),
			'desc'            => __( 'Show the notice on increasing the price in WooCommerce style', 'woocommerce' ),
			'id'              => 'rps_use_woocommerce_info_style',
			'default'         => 'no',
			'type'            => 'checkbox',
			'checkboxgroup'   => 'start',
		);

		$settings[] =array(
			'type' => 'sectionend',
			'id'   => 'product_increase_price_sales_options',
		);

		return $settings;
	}

	/**
	 * Template for Prices
	 * @return void 
	 */
	public function wc_product_prices() {
		global $product_object;

		if( ! $product_object ) {
			return;
		}

		$rps_prices    = RPS_WC_Meta::get( $product_object->get_id() );
		$apply_on_sale = RPS_WC_Meta::get( $product_object->get_id(), 'rps_apply_sale' );
		$total_sales   = is_a( $product_object, 'WC_Product_Variation' ) ? get_post_meta( $product_object->get_id(), 'total_sales', true ) : $product_object->get_total_sales();

		$limit = $this->limit;
		include 'partials/rps_wc-admin-prices.php';
		include_once 'partials/js-template.php';
	}

	/**
	 * Saving the Product Prices Data
	 * @param  int $post_id 
	 * @param  WP_Post $post    
	 * @return void          
	 */
	public function wc_product_save( $post_id, $post ) {

		$rps_array = array();

		if( isset( $_POST['rps_wc'] ) ) {

			$limit = $this->limit;
			$count = 0;
			foreach ( $_POST['rps_wc'] as $sale_points ) {

				if( ! $sale_points['sales'] ) {
					continue;
				}

				if( $limit > 0 && $count == $limit ) {
					break;
				}

				$rps_array[ $sale_points['sales'] ] = $sale_points['price'];
				$count++;
			}
		}

		RPS_WC_Meta::update( $post_id, $rps_array );

		if ( isset( $_POST['rps_apply_sale'] ) ) {
			RPS_WC_Meta::update( $post_id, 'yes', 'rps_apply_sale' );
		} else {
			RPS_WC_Meta::delete( $post_id, 'rps_apply_sale' );
		}
		 
	}

	/**
	 * Adding the menu page
	 * @return void 
	 *
	 * @since  1.1.0 
	 */
	public function admin_menu() {

		add_menu_page(
	        __( 'RPS WC', 'rps_wc' ),
	        __( 'Raise Prices with Sales', 'rps_wc' ),
	        'manage_options',
	        'rps-wc',
	        array( $this, 'admin_menu_html' ),
	        'dashicons-chart-line',
	        55);
    }

    /**
     * Admin Page HTML
     * @return void 
     */
    public function admin_menu_html() {
    	include_once 'partials/rps_wc-admin-display.php';
    }

}
