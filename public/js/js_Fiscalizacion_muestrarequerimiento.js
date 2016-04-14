Ext.onReady(function(){
    Ext.QuickTips.init();
    $("input[type='button']").button();
	$("#btnCancelar").button({icons:{primary:"ui-icon-print"}});
	$("#btnGuardar").button({icons:{primary:"ui-icon-print"}});
	$("#btnBusper").button({icons:{primary:"ui-icon-print"}});
	
	$("#btnGuardar").click(function(){
		goToFormulario("formRequerimiento");
	});
	//Valida y envia form contribuyente
		
	$('#formRequerimiento').validate({
		rules: {
			'txtCodigo': 'required',
			'txtDocumento': 'required',
			'txtMotivo': 'required',
			'txtFecha': 'required'
		},
		messages: {
			'txtCodigo': 'Debe seleccionar el contribuyente',
			'txtDocumento': 'Debe seleccionar un tipo de documento',
			'txtMotivo': 'Debe seleccionar el motivo',
			'txtFecha': 'Debe ingresar la fecha'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({     
					type: "POST",  
					url: urljs + "fiscalizacion/grabarequerimiento",
					data: $('#formRequerimiento').serializeObject(),
					dataType : 'json',
					success: function(data) { 
						var idx = data.datos[0].idx;
						var nros_rq = data.datos[0].nros_rq;
						var anio_rq = data.datos[0].anio_rq;
						var estado = data.datos[0].estado;
						
						$('#txtIdx').val(idx);
						$('#txtNumero').val(nros_rq);
						$('#txtAnio').val(anio_rq);
						$('#txtAction').val(2);
						
						infoMessage('Requerimiento',estado);
						
						buscarRequerimiento();
					},     
					error: function(data) {
						infoMessage('Error',data);
					} 

				}); 				
			}
		
	});
})

// Recupero Valores de Mantpers

window.muestraDatosSol = function(obj,t) {

	$('#txtCodigo').val(obj.get('codigo'));
	$('#txtContribuyente').val(obj.get('nombres'));	
	
};