<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       ibenic.com
 * @since      1.0.0
 *
 * @package    Rps_wc
 * @subpackage Rps_wc/admin/partials
 */

$field_name      = isset( $field_name ) ? $field_name : 'rps_wc';
$field_name_sale = isset( $field_name_sale ) ? $field_name_sale : 'rps_apply_sale';
woocommerce_wp_checkbox(
	array(
		'id'      => $field_name_sale,
		'value'   => $apply_on_sale ? $apply_on_sale : 'no',
		'label'   => __( 'Change the Sale Price?', 'rps_wc' ),
		'description'   => __( 'While the Product is on sale, the price change will change the sale price instead of regular', 'rps_wc' ),
		'cbvalue' => 'yes',
	)
);


?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wc-rps-metabox">
    <strong><?php esc_html_e( 'Current Total Sales:', 'rps_wc' ); ?> <?php echo esc_html( $total_sales); ?></strong>
    <table id="rps_table" data-formname="<?php echo $field_name; ?>"  class="wc-rps-table" cellspacing="0" cellpadding="0">
		<thead>
            <tr>
                <?php do_action( 'rps_wc_table_th_start' ); ?>
                <th>
                    <?php _e( 'Sales Needed', 'rps_wc' ); ?>
                </th>
                <th>
                    <?php _e( 'Price Change', 'rps_wc' );?> <?php echo ' (' . get_woocommerce_currency_symbol() . ')'; ?>
                </th>
                <?php do_action( 'rps_wc_table_th_end' ); ?>
                <th class="rps-delete-column"></th>
            </tr>
		</thead>
		<tbody>
			<?php 
			$count = 0;
			if( $rps_prices ) {
				
				foreach ( $rps_prices as $sales => $prices ) {
					?>
					<tr>
						<?php do_action( 'rps_wc_table_td_start', $count ); ?>
						<td class="rps-sales-column">
							<input name="<?php echo $field_name; ?>[<?php echo $count; ?>][sales]" class="widefat" value="<?php echo $sales; ?>">
						</td>
						<td class="rps-price-column">
							<input name="<?php echo $field_name; ?>[<?php echo $count; ?>][price]" class="widefat" value="<?php echo $prices; ?>">
						</td>
						<?php do_action( 'rps_wc_table_td_end', $count, $field_name ); ?>
						<td class="rps-delete-column" align="center">
							<button type="button" class="button button-default button-small rps-delete">X</buton>
						</td>
					</tr>
					<?php
					$count++;
				}
			} else {
			?>
			<tr>
				<?php do_action( 'rps_wc_table_td_start', 0 ); ?>
				<td class="rps-sales-column">
					<input name="<?php echo $field_name; ?>[0][sales]" class="widefat">
				</td>
				<td class="rps-price-column">
					<input name="<?php echo $field_name; ?>[0][price]" class="widefat">
				</td>
				<?php do_action( 'rps_wc_table_td_end', 0, $field_name ); ?>
				<td class="rps-delete-column" align="center">
					<button type="button" class="button button-default button-small rps-delete">X</buton>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php 
		$button_attr = '';
		$error_class = '';
		if( $count && $limit > 0 && $count >= $limit ) {
			$button_attr = 'disabled="disabled"';

		} else { 
			$error_class = 'hidden'; 
		} 

	?>
	<button type="button" data-formname="<?php echo $field_name; ?>" class="button button-default rps-add-row" <?php echo $button_attr; ?>><?php _e( 'Add Sale Point', 'rps_wc' ); ?></button>
	
	<span id="rps_buy_pro" class="<?php echo $error_class;?> notice notice-error rps-error"><em><?php _e( 'Free Version is Limited to 3 Sales Points. Click the Upgrade link under the Raise Prices with Sales menu.', 'rps_wc' ); ?></em></span>
	

</div>
