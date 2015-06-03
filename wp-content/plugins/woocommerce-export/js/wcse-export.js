/**
 * Main JS file for WooCommerce Smart Export Plugin
 * Author: THemology
 */

(function ($) {
    "use strict";

    $(document).ready(function(){

        $('.custom_date').datepicker({

				dateFormat : "yy-mm-dd",
				maxDate: 0

		});


		$('#wcse-td-command-status').hide();
		$('#wcse-td-command-product-display').hide();
		$('#wcse-td-coupon-description').hide();

		$('#wcse-entity').on('change',function(e){

			 var optionSelected = $("option:selected", this);
    		 var valueSelected = this.value;
    		 if(valueSelected == 'orders'){
    		 	$('#wcse-td-command-status').show();
    		 	$('#wcse-td-user-infos').hide();
    		 	$('#wcse-td-command-product-display').show();
    		 	$('#wcse-td-coupon-description').hide();
    		 }
             else if(valueSelected == 'coupons'){
                $('#wcse-td-command-status').show();
                $('#wcse-td-user-infos').hide();
                $('#wcse-td-command-product-display').hide();
                $('#wcse-td-coupon-description').show();
             }
    		 else{
    		 	$('#wcse-td-command-status').hide();	
    		 	$('#wcse-td-user-infos').show();
    		 	$('#wcse-td-command-product-display').hide();
    		 	$('#wcse-td-coupon-description').hide();
    		 }

		});

    });

}(jQuery));