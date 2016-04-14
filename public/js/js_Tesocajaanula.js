Ext.onReady(function(){

	Ext.QuickTips.init();
	$("input[type='button']").button();
});

$('#txtRecibo').blur(function(){

	var numCeros = '000000000'; // pon el n? de ceros que necesites
    var valor =  $('#txtRecibo').val().trim();

    if(valor.length > 0)// total de carec mayor a cero y ete no esta vacio 
    {
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtRecibo').val(valor2);
    }else{
    	$('#txtRecibo').val(valor);
    }
});

function aceptarRecibos(){

	if($('#txtRecibo').val().length>0){
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",     
					url: urljs + "tesocaja/aceptarrecibo",
					data: $("#frmanular").serializeObject(),     
					success: function(data) { 
						infoMessage('SIGMUN	- Anular recibo',data);
						closePopup('#frmanular');
					},     
					error: function() {
					}
				}); 
			}
		}
		confirmMessage('Anular recibo','Seguro de Anular recibo?',showResult);
	}else{
		infoMessagex('Anular recibo','Ingrese n&uacute;mero de movimiento','#txtRecibo');
	}
}