//CASIMIRO MODIFICO ACTULIZACION CONTRTIBUYENTE
$("#combo").change(function() {
		var op = $("#combo option:selected").val();
		$('#capa').html(op);
});

function validaText(val){
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	var length= $('#txtNumDoc').val().length;
	
	//alert(ncar);
	//alert(length);
		
	if(ncar>0)
	{
		$('#txtNumDoc').attr('disabled',false);
		$('#txtNumDoc').autotab({ maxlength: ncar });
		
	}
	else
	{
		$('#txtNumDoc').val('');
		$('#txtNumDoc').attr('disabled',true);
		
		
	}
	

	

}

$(function(){	

    changeDisContri($('#cmbDisContri').val());

	$("input[type='button']").button();
	
	//Valida y env�a form contribuyente
	$('#frminfractor').validate({
		rules: {
			//'txtNomContri': 'required',
			//'txtApePatContri': 'required',
			//'txtApeMatContri': 'required',
			//'cmbDocContri': 'required',
			//'txtNumDoc': 'required',
			//'cmbDisContri': 'required',
		},
		messages: {
			//'txtNomContri': 'Debe ingresar el nombre',
			//'txtApePatContri': 'Debe ingresar el apellidos paterno',
			//'txtApeMatContri': 'Debe ingresar el apellido materno',
			//'cmbDocContri': 'Seleccione el tipo de documento',
			//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			//'cmbDisContri': 'Seleccione el distrito'
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){

			$('#txtRef').val($('#txtRef').val().replace("'","''"))
			var carga=validarContribuyente();
			array=carga.split('/');
			errorMessage('Error',array[0]);
			if (array[0]=='false')
			{
				errorMessage('Alerta',array[1]);
			}
			else{0.
				
				$.ajax({     
					type: "POST",  
					url: urljs + "mantinfractor/grabar",
					data: $('#frminfractor').serializeObject(),     
					success: function(data) { 
						//infoMessage('Guardar Contribuyente',data);
						if(data=='A'){
							closePopup('#popinfractor');
							Ext.getCmp('xgridPreventiva').getStore().load();
						}
						if(data=='B'){
							infoMessage('Informe','No se puede registrar con un numero de documento ya registrado');
						}
						
					},     
					error: function() {
					} 
				}); 				
			}
		}
	});
	validaText($('#cmbDocContri').val().trim());
	//validaText($('#txtNumDoc').val().length);
	//changeDisContri('003');
});


$('#cmbDocContri').change(function(){
    //$('#txtNumDoc').val().length;
	//var combito = $('#txtNumOcul').val();
	$('#txtNumDoc').focus();
	
	//alert($('#cmbDocContri option:selected').val());
	//alert($('#cmbDocContri option:selected').text());
	//alert(combito);
	//alert($(this).val());
	
});


function changeDisContri(idDis){
	if(idDis=='012'){
		$('#rowUrbOtherDis').hide();
		$('#rowViaOtherDis').hide();
		
		$('#rowZonaDis').show();
		$('#rowUrbDis').show();
		$('#rowViaDis').show();
	}
	else{
		$('#rowZonaDis').hide();
		$('#rowUrbDis').hide();
		$('#rowViaDis').hide();
		
		$('#rowUrbOtherDis').show();
		$('#rowViaOtherDis').show();
	}
}

function validarContribuyente(){
	var txtNomContri = $('#txtNomContri').val().trim();
	var cmbDocContri = $('#cmbDocContri').val().trim();
	var txtNumDoc = $('#txtNumDoc').val().trim();
	var txtletra1 = $('#txtletra1').val().trim();
	var txtletra2 = $('#txtletra2').val().trim();
	var txtnumero2 = $('#txtnumero2').val().trim();
	var txtNume = $('#txtNumDoc').val().trim().length;
	var cmbDisContri = $('#cmbDisContri').val().trim();
	var cmbTipoInterior = $('#cmbTipoInterior').val().trim();
	//var cmbTipoActuContri = $('#cmbTipoActuContri').val().trim(); 
	var retorno='true/Guardando Contribuyente';
	var ncar = $('#cmbDocContri').val().substr(3,4);
	var txtNumDoc = $('#cmbDisContri').val().trim();
	
	
	if(txtNomContri!=""){
		if (cmbDocContri==""){
			retorno='false/Por favor seleccion el distrito en la direcion fiscal';
			return(retorno);
		}
	/*	if (txtletra1==""){
		retorno='false/Por favor ingrese letra 1';
		return(retorno);
		}
		
		if (txtletra2==""){
		retorno='false/Por favor ingrese letra 2';
		return(retorno);
		}
		
		if (txtnumero2==""){
		retorno='false/Por favor ingrese numero 2';
		return(retorno);
		}
		
		if (txtNumDoc==""){
			retorno='false/Por favor ingrese su numero de documento';
			return(retorno);	
		}	
		if (txtNume!=ncar){
			retorno='false/Deben ser '+ncar+' digitos en el campo numero';
			return(retorno);	
		}		
		if (cmbDisContri==""){
			retorno='false/Por favor seleccione su distrito';
			return(retorno);	
		}
		if (cmbTipoInterior==""){
			retorno='false/Por favor seleccione el tipo de interior';
			return(retorno);
		}*/
		
	}else{
			retorno='false/Por favor ingrese su nombre o raz&oacuten social';
			return(retorno);
		}
	return(retorno);
}

//Recupero Valores de Mantpred
window.muestraDatos = function(obj) { 
	$('#txtViacodigo').val(obj.get('codigo'));
	$('#txtZonacodigo').val(obj.get('codzona'));
	$('#txtZona').val(obj.get('nomzona'));
	$('#txtUrbacodigo').val(obj.get('codurba'));
	$('#txtUrbanizacion').val(obj.get('nomurba'));
	$('#txtViacontri').val(obj.get('nomvia'));
};

$('#cmbTipoContri').change(function(){
    var tipocontri=($(this).val());
   
    //$('#txtAreades').val(areadestino);
    
    $.ajax({     
		type: "POST",     
		url: "mantcontri/conscontri",
		data: 'id_tipocontri='+tipocontri,
		success: function(data) { 
			$('#cmbTipoContri2').html(data);		

		}
	});
});

function suceciondetalle(valor){
	if(valor=='02'){
		$('#suceciondetalle').show();
	}
	else{
		$('#suceciondetalle').hide();
	}
}