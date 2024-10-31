(function( $ ) {
	'use strict';

	 $(function(){
	 	// Caching elements
	 	var rps_table 	= $( "#rps_table" ),
	 		rps_table_body = rps_table.find('tbody'),
	 		rps_add_row = $( "#rps_add_row" ),
	 		template	= $( "#rps_table_row_template"),
	 		limit 		= rps_wc.limit;

	 	// Adding a new row
	 	$( document ).on( 'click', '.rps-add-row', function(){

            var table = $(this).parent().find('.wc-rps-table');
            var tbody = table.find('tbody');
	 		var length = tbody.children('tr').length;
            var name   = $(this).attr('data-formname') || 'rpt_wc';
	 		var _template = wp.template( 'rps_table_row_template' );
	 		var html = _template({ length: length, name: name });

            tbody.append( html );
	 	 	length += 1;
 
	 	 	/** Appended is +1 */ 
	 	 	if( limit > 0 && length >= limit ) {
	 	 		$(this).attr( 'disabled', 'disabled' );
	 	 		$("#rps_buy_pro").removeClass('hidden');
	 	 	}

	 	});

	 	$(document).on( 'click', '.rps-delete', function(){
            var table = $(this).parents('.wc-rps-table');
	 		$(this).parents('tr').remove();
	 		rps_refresh_table_body( table );
	 	});

	 	/**
	 	 * Refreshed table body
	 	 * @return void 
	 	 */
	 	function rps_refresh_table_body( table ) {
            var _table = table || $('.wc-rps-table'),
                name   = table.attr( 'data-formname' ),
	 		    count  = 0;

            _table.find('tbody').find('tr').each(function(){
	 			var row = $(this);
	 			row.find('.rps-sales-column input').attr( 'name', name + '[' + count + '][sales]' );
	 			row.find('.rps-price-column input').attr( 'name', name + '[' + count + '][price]' );
	 			count++;
	 		});

	 		if( limit > 0 && count < limit ) {
	 			_table.parent().find('.rps-add-row').attr( 'disabled', false );
	 			$("#rps_buy_pro").addClass('hidden');
	 		}

	 	}

	 });

})( jQuery );
