<script type="text/html" id="tmpl-rps_table_row_template">
	<tr>
		<?php do_action( 'rps_wc_table_td_template_start' ); ?>
		<td class="rps-sales-column">
			<input name="{{ data.name }}[{{data.length}}][sales]" class="widefat">
		</td>
		<td class="rps-price-column">
			<input name="{{ data.name }}[{{data.length}}][price]" class="widefat">
		</td>
		<?php do_action( 'rps_wc_table_td_template_end' ); ?>
		<td class="rps-delete-column" align="center">
			<button type="button" class="button button-default button-small rps-delete">X</buton>
		</td>
	</tr>
</script>