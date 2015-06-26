jQuery(document).ready(function($)
{

        
        //OLD Code
        //$.lockfixed("#idSticky",{offset: {top: 120, bottom: 70}});
        //New Code
        if($(window).height() > ($("#idSticky").height() + 100) ){  ; // 200 is buffer kept for menu and footer
                $.lockfixed("#idSticky",{offset: {top: 0, bottom: 150}});
        }
        //for showing gallery
         $('#showImage').click(function(){
                 $('.woocommerce-main-image').click();
                 return false;
         });
         //onclick="javascript: document.getElementsByClassName('woocommerce-main-image').item(0).click();return false;"
         $('#scroll-top').click(function(){
                        $('html,body').animate({ scrollTop: $('.clsContent').offset().top - 140 },1000, function () {
                        });
                        return false;
                });
        //console.log($(".widget_shopping_cart_content").html())
        /*
        $(".widget_shopping_cart_content").slimScroll({
        height:'350px'
    });
        */
        $('#des-top').click(function(){
                        $('html,body').animate({  scrollTop: $('#desc').offset().top - 140 },1000, function () {
        });
        return false;
        });

        $(".clsFinish").each(function() {
                var text = $(this).text();
                text = text.replace("pa_color", "color");
                $(this).text(text);
        });

        if( $('.variations_form').length > 0 ){
                $('#idSticky .single_add_to_cart_button').on( 'click', function(e){
                        e.preventDefault();
                        console.log("kidnap");
                        $('.clsContent .single_add_to_cart_button').click();
                });
        }
        setTimeout(function(){ // Do not remove setTimeout as woocoomerce "on" event is running very late. so its much needed
                        $(document).off('click',".plus, .minus")
                                                                 .on("click",".plus, .minus", function(e) {
                
                // Get values
                var $qty                = $( this ).closest( '.quantity' ).find( '.qty' ),
                        currentVal      = parseFloat( $qty.val() ),
                        max                     = parseFloat( $qty.data( 'max' ) ),
                        min                     = parseFloat( $qty.data( 'min' ) ),
                        step            = $qty.attr( 'step' ),  
                        $qty_all = $( '.qty' ) ;

                // Format values
                if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
                if ( max === '' || max === 'NaN' ) max = '';
                if ( min === '' || min === 'NaN' ) min = 0;
                if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;
                console.log(max);
                // Change the value
                if ( $( this ).is( '.plus' ) ) {
                        if ( max && ( max == currentVal || currentVal > max ) ) {
                                if(!$(this).parents(".single-product").length){
                                        $qty.val( max );
                                }
                                else{
                                        $qty_all.val(max);
                                }
                        } else {
                                var $val = currentVal + parseFloat( step );
                                if(!$(this).parents(".single-product").length){
                                        $qty.val( $val );
                                }
                                else{
                                        $qty_all.val($val);
                                }
                        }

                } else {

                        if ( min && ( min == currentVal || currentVal < min ) ) {
                                if(!$(this).parents(".single-product").length){
                                        $qty.val( min );
                                }
                                else{
                                        $qty_all.val(min);
                                }
                        } else if ( currentVal > 0 ) {
                                var $val = currentVal - parseFloat( step );
                                if(!$(this).parents(".single-product").length){
                                        $qty.val( $val );
                                }
                                else{
                                        $qty_all.val( $val);
                                }
                        }

                }
                // Trigger change event
                $qty.trigger( 'change' );
        });     
        }, 1000); // Timeout
        if($(".tax-total").length){
            console.log( "totals remove ");
            $('tr.tax-total').css('color','red').css('display','none').remove();
        }
        if($("#order_comments").length){
                $("#order_comments").attr("placeholder","");
        }
        if( $("td.Estado").length ){
            if(  $("td.Estado").text() == 'Aprobada' ){
                $( '.fakealert' ).text( 'Gracias por tu compra, estamos procesando tu pedido.' ); 
            }
            if(  $("td.Estado").text() == 'Rechazada' ){
                $( '.fakealert' ).text( 'Tu transacción ha sido cancelada.' ); 
            }
        }
        
        $("#btncheck").on('click','#createaccount', function(){
					  checked();
				});

   $('#billing_company').on('change',function(){
     state= $(this).val();
		 
		 $.ajax({
              url: "/mac/wp-admin/admin-ajax.php",
              type: "POST",
              dataType:"json",
    				  data:'action=cities_ajax_call&state='+state,
					    success: function(data) {
							$('#billing_city').empty();
								$.each(data.cities, function(index, value) {
									$("#billing_city").append($('<option>').html(value)) ;
								});
 							}
							
     })
		 
	})


	  $('#shipping_company').on('change',function(){
     state= $(this).val();
		 $('#shipping_city').empty();
		 $.ajax({
              url: "/mac/wp-admin/admin-ajax.php",
              type: "POST",
              dataType:"json",
    				  data:'action=cities_ajax_call&state='+state,

					    success: function(data) {
             
							$.each(data.cities, function(index, value) {
               $("#shipping_city").append($('<option>').html(value)) ;
              });
						}

     })

	})
				
        
});
