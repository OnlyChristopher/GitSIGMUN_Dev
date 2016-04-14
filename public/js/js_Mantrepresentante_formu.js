$("#combo").change(function() {
		var op = $("#combo option:selected").val();
		$('#capa').html(op);
});

function validaText(val)
{
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	var length= $('#txtNumDocRepre').val().length;
	
	//alert(ncar);
	//alert(length);
		
	if(ncar>0)
	{
		$('#txtNumDocRepre').attr('disabled',false);
		$('#txtNumDocRepre').autotab({ maxlength: ncar });
		
	}
	else
	{
		$('#txtNumDocRepre').val('');
		$('#txtNumDocRepre').attr('disabled',true);
	}
}

$(function(){	

    changeDisContri($('#cmbDis').val());

	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmrepre').validate({
		rules: {
			'txtNomRepre': 'required',
			'txtApePatRepre': 'required',
			'txtApeMatRepre': 'required',
			'cmbDocContri': 'required',
			'txtNumDocRepre': 'required',
			'cmbDis': 'required',
			'txtRefRepre': 'required'
			/*'txtMzRepre': 'required',
			'txtLtRepre': 'required',
			'txtSubLtRepre': 'required',
			'txtNumRepre': 'required',
			'txtNumRepre': 'required',
			'cmbTipUrb': 'required',
			'cmbTipVia': 'required',
			'txtDptoRepre': 'required',
			'txtnumero2':'required',
			'txtletra2':'required',
			'txtpiso':'required',
			'txtnumero_interno':'required',
			'txtletra_interno':'required',
			'cmbTipoInterior':'required',
			'cmbTipoIngreso':'required',
			'cmbTipoEdificacion':'required',
			'cmbTipoAgrupamiento':'required',
			'txtEdificacion':'required',
			'txtIngreso':'required',
			'txtAgrupamiento':'required',
			'txtletra1':'required'*/
			
		},
		messages: {
			'txtNomRepre': 'Debe ingresar el Nombre Representante',
			'txtApePatRepre': 'Debe ingresar el apellidos paterno',
			'txtApeMatRepre': 'Debe ingresar el apellido materno',
			'cmbDocRepre': 'Seleccione el tipo de documento',
			'txtNumDocRepre': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'cmbDis': 'Seleccione el distrito',
			'txtRefRepre': 'Debe ingresar la referencia'
			/*'txtMzRepre': 'Debe ingresar la Mz',
			'txtLtRepre': 'Debe ingresar la Lt',
			'txtSubLtRepre': 'Debe ingresar la SubLt',
			'txtNumRepre': 'Debe ingresar la N°',
			'cmbTipUrb': 'Debe ingresar la Urbanizacion',
			'cmbTipVia': 'Debe ingresar la Via',
			'txtDptoRepre': 'Debe ingresar el Dpto',
			'txtnumero2':'Debe ingresar el N1',
			'txtletra2':'Debe ingresar letra 2',
			'txtpiso':'Debe ingresar el piso',
			'txtnumero_interno':'Debe ingresar numero interno',
			'txtletra_interno':'Debe ingresar la letra interna',
			'cmbTipoInterior':'Debe Seleccionar Tipo Interior',
			'cmbTipoIngreso':'Debe seleccionar Tipo Ingreso',
			'cmbTipoAgrupamiento':'Debe seleccionar Agrupamiento',
			'cmbTipoEdificacion':'Debe Seleccionar tipo de Edificacion',
			'txtEdificacion':'Debe ingresar la Edificacion',
			'txtIngreso':'Debe ingresar el Ingreso',
			'txtAgrupamiento':'Debe ingresar el Agrupamiento',
			'txtletra1':'Debe ingresar Letra1'*/
		
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){			
			$.ajax({     
				type: "POST",     
				url: "mantrepresentante/grabar",
				data: $('#frmrepre').serializeObject(),     
				success: function(data) { 
					//alert(data);
					infoMessage('Guardando Documentos',data);
					closePopup('#poprepreformu');
					Ext.getCmp('xgridRepre').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}	
	});
	validaText($('#cmbDocRepre').val().trim());
	//validaText($('#txtNumDoc').val().length);
	//changeDisContri('003');
});


$('#cmbDocRepre').change(function(){
    //$('#txtNumDoc').val().length;
	//var combito = $('#txtNumOcul').val();
	$('#txtNumDocRepre').focus();
	
	//alert($('#cmbDocContri option:selected').val());
	//alert($('#cmbDocContri option:selected').text());
	//alert(combito);
	//alert($(this).val());
	
});


/*
function validaText(val){
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	var length= $('#txtNumDocRepre').val().length;
	
	//alert(ncar);
	//alert(length);
		
	if(ncar>0)
	{
		$('#txtNumDocRepre').attr('disabled',false);
		$('#txtNumDocRepre').autotab({ maxlength: ncar });
		
	}
	else
	{
		$('#txtNumDocRepre').val('');
		$('#txtNumDocRepre').attr('disabled',true);
		
		
	}
	

	

}
*/

function changeDisContri(idDis){
//	rowUrbOtherDis-rowViaOtherDis
	if(idDis=='012'){
		$('#rowUrbOtherDisRepre').hide();
		$('#rowViaOtherDisRepre').hide();
		
		$('#rowZonaDisRepre').show();
		$('#rowUrbDisRepre').show();
		$('#rowViaDisRepre').show();
	}
	else{
		$('#rowZonaDisRepre').hide();
		$('#rowUrbDisRepre').hide();
		$('#rowViaDisRepre').hide();
		
		$('#rowUrbOtherDisRepre').show();
		$('#rowViaOtherDisRepre').show();
	}
}
/*
function validarRepresentante(){
	var txtNomRepre = $('#txtNomRepre').val().trim();
	var cmbDocContri = $('#cmbDocContri').val().trim();
	var txtNumDoc = $('#txtNumDoc').val().trim();
	var txtNume = $('#txtNumDoc').val().trim().length;
	var cmbDisContri = $('#cmbDisContri').val().trim();
	var retorno='true/Guardando Representante';
	var ncar = $('#cmbDocContri').val().substr(3,4);
	
	
	if(txtNomContri!=""){
		if (txtNomRepre=="07/0"){
			retorno='false/Por favor seleccione el tipo de documento';
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
	}else{
			retorno='false/Por favor ingrese su nombre o razón social';
			return(retorno);
		}
	return(retorno);
	
}*/

//Recupero Valores de Mantpred
window.muestraDatos = function(obj) { 
	$('#txtViacodigoRepre').val(obj.get('codigo'));
	$('#txtZonacodigoRepre').val(obj.get('codzona'));
	$('#txtZonaRepre').val(obj.get('nomzona'));
	$('#txtUrbacodigoRepre').val(obj.get('codurba'));
	$('#txtUrbanizacionRepre').val(obj.get('nomurba'));
	$('#txtViaRepre').val(obj.get('nomvia'));
	
	
};