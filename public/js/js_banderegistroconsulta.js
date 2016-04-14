Ext.onReady(function(){
	Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	//Guardar formulario
	$('#frmBandeconsulta').validate({
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			
			var xpanel = Ext.getCmp('content');
			
			//Datos del formulario
			data = $(form).serializeObject();
			
			Ext.Ajax.request({
				  url: urljs + "banderegistroconsulta/consultaregistro",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response,opts){			
					  opts.panel.update(response.responseText,true);
				  },
				  panel: xpanel
			});			
		}
	});
	
	//Todo a mayúscula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
});


$('#num_archi').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#num_archi').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#num_archi').val(valor2);  //obj.value = valor;
        
});


$('#cod_contri').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#cod_contri').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#cod_contri').val(valor2);  //obj.value = valor;
        
});

$('#num_expe').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#num_expe').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#num_expe').val(valor2);  //obj.value = valor;
        
});

