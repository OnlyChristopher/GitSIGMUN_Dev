Ext.onReady(function(){
    Ext.QuickTips.init();
    $("input[type='button']").button();
	$("#btnCancelar").button({icons:{primary:"ui-icon-print"}});
	$("#btnGuardar").button({icons:{primary:"ui-icon-print"}});
	$("#btnBusper").button({icons:{primary:"ui-icon-print"}});
	
	//$('#txtAnio').mask('00/00/0000');
	
	$("#btnGuardar").click(function(){
		goToFormulario("formHojaResumen");
	});
	//Valida y envia form contribuyente
		
	$('#formHojaResumen').validate({
		rules: {
			'txtAnio': 'required',
			'cbRegimen': 'required'
		},
		messages: {
			'txtAnio': 'Debe ingresar el año',
			'cbRegimen': 'Debe seleccionar el regimen'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizaciondetalle/grabarhresumen",
					data: $('#formHojaResumen').serializeObject(),
					//dataType : 'json',
					success: function(data){
						//console.log(data);
						if ($('#txtaction').val()=='1'){
							var idx= data;
							$('#txtIdrh').val(idx);
							$('#txtaction').val('2');
							RecargarTree();
						}
						
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		
	});
})