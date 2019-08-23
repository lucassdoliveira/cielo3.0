jQuery(document).on('DOMNodeInserted DOMNodeRemoved', '#checkout-payment-method-load', function () { cieloTres(); });
jQuery(document).ready(function(){ cieloTres() });

function cieloTres()
{
	if (!jQuery("div").is(".card-wrapper"))
	{
	    jQuery( "#payment_form_nitrocielo" ).prepend( "<div class='card-wrapper'></div>" );
	    jQuery( "#payment_form_nitrocielo" ).append( "<input id='card-expiry' name='card-expiry' type='hidden' />" );
	    
	    var card = new Card({
			form: ID_FORM,
	        container: ".card-wrapper",
	        formSelectors: {
	            numberInput: "input#nitrocielo_numero_cartao_cielo",
	            expiryInput: "input#card-expiry",
	            cvcInput: "input#nitrocielo_codigo_seguranca_cielo",
	            nameInput: "input#nitrocielo_portador_cielo"
	        },
		    width: 350,			// Tamanho do cartão
		    formatting: true,
		    debug: false,

		    // Strings for translation - optional
		    messages: {
		        validDate: 'data\nvalid', 	// optional - default 'valid\nthru'
		        monthYear: 'mm/yyyy', 		// optional - default 'month/year'
		    },

		    placeholders: {
		    	name: 'Titular Cartão'
		    }
		});

	    var exp_m = "**";
	    var exp_y = "**";
	    jQuery("#nitrocielo_expiracao_mes_cielo").change(function () {
	        jQuery( "select#nitrocielo_expiracao_mes_cielo option:selected" ).each(function() {
	            exp_m = jQuery( this ).val();
	        });
	        
	        jQuery("input[name='card-expiry']").val(exp_m+"/"+exp_y).change();
	        jQuery(".jp-card-expiry").html(exp_m+"/"+exp_y);
	        jQuery(".jp-card-expiry").addClass("jp-card-focused");
	    });
	    
	    jQuery("#nitrocielo_expiracao_ano_cielo").change(function () {
	        jQuery( "select#nitrocielo_expiracao_ano_cielo option:selected" ).each(function() {
	            exp_y = jQuery( this ).val();
	        });
	        
	        jQuery("input[name='card-expiry']").val(exp_m+"/"+exp_y).change();
	        jQuery(".jp-card-expiry").html(exp_m+"/"+exp_y);
	        jQuery(".jp-card-expiry").addClass("jp-card-focused");
	    });
	}
}
