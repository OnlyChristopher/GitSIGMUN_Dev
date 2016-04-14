$('#cmbPartida').change(function(){
    var partida=($(this).val());
    //var partida= combo;
    $('#txtPartida').val(partida);
});

$(function(){
	$('#frmingreso').validate({
		rules: {
		//'txtDesIngreso': 'required'
		//'txtResumen': 'required',
		//'txtPartida': 'required',
		//'cmbContable': 'required',
		//'txtUit': 'required',
		//'txtMonto': 'required',
		//'cmbFuente': 'required',
		//'cmbArea': 'required'
		//'txtNumDoc': { required: true, digits: true }
		},
		messages: {
		//'txtDesIngreso': 'Debe ingresar una descripci&oacute;n'
		//'txtResumen': 'Debe ingresar un resumen',
		//'txtPartida': 'Debe seleccionar una partida',
		//'cmbContable': 'Debe sleeccionar un contable',
		//'txtUit': 'Ingrese la uit',
		//'txtMonto': 'Ingrese el monto',
		//'cmbFuente': 'Debe seleccionar una fuente',
		//'cmbArea': 'debe seleccionar un area'
		//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			//var carga='true/Guardando Datos';
			var carga=validarIngresos();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Alerta',array[1]);
			}
			else{
				infoMessage('Guardando Ingresos',array[1]);
				
				$.ajax({     
					type: "POST",  
					url: "mantingresos/grabar",
					data: $('#frmingreso').serializeObject(),     
					success: function(data){ 
						//alert(data);
						$("#MSG_ERROR").html(data);
						closePopup('#popupIngreso');
						Ext.getCmp('xgridIngresos').getStore().load();
					},     
					error: function() {
					} 
				}); 				
			}
		}
	});
	
});

function validarIngresos(){
	var txtDesIngreso = $('#txtDesIngreso').val().trim();
	var txtResumen = $('#txtResumen').val().trim();
	var txtPartida = $('#txtPartida').val().trim();
	var cmbContable = $('#cmbContable').val().trim();
	var txtUit = $('#txtUit').val().trim();
	var txtMonto = $('#txtMonto').val().trim();
	var cmbFuentes = $('#cmbFuentes').val().trim();
	var cmbArea = $('#cmbArea').val().trim();
	var retorno='true/Guardando Ingresos';
	
	if(txtDesIngreso!=""){
		if (txtResumen==""){
			retorno='false/Por favor ingrese el resumen';
			return(retorno);
		}
		if (txtPartida==""){
			retorno='false/Por favor seleccione la partida';
			return(retorno);
		}
		if (cmbContable==""){
			retorno='false/Por favor seleccione el combo contable';
			return(retorno);
		}
		if (txtUit==""){
			retorno='false/Por favor ingrese la UIT';
			return(retorno);
		}
		if (txtMonto==""){
			retorno='false/Por favor ingrese el monto';
			return(retorno);
		}
		if (cmbFuentes==""){
			retorno='false/Por favor seleccione el combo fuente';
			return(retorno);
		}
		if (cmbArea==""){
			retorno='false/Por favor seleccione el área';
			return(retorno);
		}
	}else{
			retorno='false/Por favor ingrese la descripción';
			return(retorno);
		}
	return(retorno);
}
