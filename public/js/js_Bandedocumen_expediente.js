$('#txtNroexpe').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtNroexpe').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtNroexpe').val(valor2);  //obj.value = valor;      
});

$('#cmbEjecutivo').change(function(){
    var usuario=($(this).val());
    $('#txtEjecutivo').val(usuario);
});

$('#cmbAuxiliar').change(function(){
    var usuario=($(this).val());
    $('#txtAuxiliar').val(usuario);
});

$(function(){
	//Valida y envía form contribuyente
	$('#frmexpediente').validate({
		rules: {
		'txtNro': 'required',
		'txtAno':'required',
		'cmbEjecutivo':'required',
		'cmbAuxiliar':'required',
		'txtFechaexpe':'required',
		'txtObservaciones':'required'
		//'txtNumDoc': { required: true, digits: true }
		},
		messages: {
		'txtnro': 'Debe ingresar el nro del expediente',
		'txtano': 'Debe ingresar el año del expediente',
		'cmbEjecutivo':'Debe seleccionar un ejecutivo',
		'cmbAuxiliar':'Debe seleccionar una auxiliar',
		'txtFechaexpe': 'Debe ingresar la fecha',
		'txtObservaciones': 'Debe ingresar la observación'
		//'cmbZona':'Debe seleccionar una zona'
		//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			var myMask = new Ext.LoadMask(Ext.get('popup_popupexpediente'), {msg:"Guardando..."});			
			$.ajax({     
				type: "POST",     
				url: "bandedocumen/grabarexpediente",
				data: $('#frmexpediente').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardando Expedientes',data);
					closePopup('#popupexpediente');
					Ext.getCmp('xgridBandeja').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}		 
	});
	
});


function imprimeExpediente(){
	var num_exp=$('#txtNroexpe').val();
	var ano_exp=$('#txtAniosexpe').val();
	
	showPopupReport('tipo=pdf&nombrereporte=rptrec01&param=num_exp^'+num_exp+'|ano_exp^'+ano_exp,'popupdocumentoexpe',700,600,'Impresion de Expediente');
	
}

function imprimeCedula(){
	var num_exp=$('#txtNroexpe').val();
	var ano_exp=$('#txtAniosexpe').val();
	
	showPopupReport('tipo=pdf&nombrereporte=rptcedula&param=num_exp^'+num_exp+'|ano_exp^'+ano_exp,'popupdocumentocedu',700,600,'Impresion de Cedula de Notificación');
	
}
