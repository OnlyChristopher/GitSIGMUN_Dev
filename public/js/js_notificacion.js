

$(function(){

	//$('#chkResoluciones').attr('checked',true)
	changeDisContri($('#cmbDisContri').val());
	
	
	//$('#divresolucion').hide();
	
	$("#nlicencia").attr("disabled", "disabled");
	$("#cmbanolic").attr("disabled", "disabled");


	if($('#cmbdescargo').val() == 1)
	{
		$("#cmbtipoexpdoc").removeAttr("disabled");
		$("#txtxnroexpdoc").removeAttr("disabled");
		$("#cb_fechexpdoc").removeAttr("disabled");
	}
	else
	{
		$("#cmbtipoexpdoc").attr("disabled", "disabled");
		$("#txtxnroexpdoc").attr("disabled", "disabled");
		$("#cb_fechexpdoc").attr("disabled", "disabled");

	}

	
	//$("#txthora").val('14:02');
	
	
	$("input[type='button']").button();
	
	
	$("#divPeriodo").hide();
	
	$('#frmultedit').validate({
		rules: {
			'codigo_autoridad_municipal': 	{ required: true },
			'fecha_multa': 	{ required: true },
			'preimpreso': 	{ required: true },
			'estado': 	{ required: true },
			'codigo_area': 	{ required: true },
			'tipo_infraccion': 	{ required: true },
			'codigo_infraccion': 	{ required: true },
			'ctxtFechResolucion': 	{ required: true },//1
			'txtNResolucion': 		{ required: true },//2
			'txaObservacion': 		{ required: true },//3
			'notificacion':			{ required: true },
			'informe'     :			{ required: true },
			'cmbuso'	  :			{ required: true },
			'txtnomvia'   :			{ required: true},
			'cmbactividad':			{ required: true},
			'rbttnoti'	  :			{ required: true},
			'txthora'	  :			{ required: true}	
		},
		messages: {
			'codigo_autoridad_municipal': 	{ required: 'Seleccione una autoridad' },
			'fecha_multa': 	{ required: 'Ingrese la fecha' },
			'preimpreso': 	{ required: 'Ingrese el preimpreso' },
			'estado': 	{ required: 'Seleccione el estado' },
			'codigo_area': 	{ required: 'Seleccione el 1� campo de infraccion' },
			'tipo_infraccion': 	{ required: 'Seleccione el 2� campo de infraccion' },
			'codigo_infraccion': 	{ required: 'Seleccione el 3� campo de infraccion' },
			'ctxtFechResolucion': 	{ required: 'Ingrese la fecha de la resolucion' },
			'txtNResolucion': 	{ required: 'Ingrese el Numnero de resolucion' },
			'txaObservacion': 	{ required: 'ingrese la glosa' },
			'notificacion': 	{ required: 'ingrese la notificacion' },
			'informe'     :     { required: 'ingrese el N informe' },
			'cmbuso'      :     { required: 'Seleccione el Uso' },
			'txtnomvia'      :     { required: 'Ingrese el nombre de la Via' },
			'cmbactividad'   :     { required: 'Seleccione la Actividad' },
			'rbttnoti'       :     { required: 'seleccione el tipo de notificacion' },
			'txthora'        :     { required: 'seleccione el tipo de notificacion' },
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			var showResult = function(btn){
			if(btn=='yes')
			$.ajax({     
				type: "POST",     
				url: urljs + "preventiva/grabarnotificacion",
				data: $(form).serializeObject(),     
				success: function(data) { 
					infoMessage('Advertencia',data);
					closePopup('#popnewmulta');					
					Ext.getCmp('xgridMultas').getStore().load();
				},     
				error: function() {
				} 
			});
			};
			confirmMessage('Guardar','Seguro que desea registrar la multa?',showResult);
		}
	});
	
	//Todo a may�scula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	calcUIT($('#fecha_multa').val());
	$('#cantidad').change(function(){
		calcTotal($('#codigo_infraccion').val());
	});
	
	if($('#numeroMulta').val()=='')                    // no muestre el id de un combo
	$("#estado option[value='3']").remove(); 		  //  no muestre el id de un combo
	
	// if($('#chkResoluciones').is(':checked'))
	// {
		// $('#divresolucion').show();
	// }
	
	if($('#chkLicencia').is(':checked'))
	{
		$("#nlicencia").removeAttr("disabled");
        $("#cmbanolic").removeAttr("disabled");	
	}
	
	
	var acceso=$('#txtmod').val();
	if(acceso=='01.09.01')
	console.log('no se puede');
	else
	console.log('si se puede');
	
	
});
//click en cmb codigo_area y me pinta mi otro combo tipo_infraccion, filtrando el codigo

function FiltraCodArea(cod_area){
	var anno=$('#anno_area').val();
	var contentAjax = $('#tipo_infraccion').html('...');
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selectarea",
		//data: "codigo_area="+array[0]+"&anno_area="+array[1],
		data: "codigo_area="+cod_area+"&anno_area="+anno,
		success: function(response){
			contentAjax.html(response);			
		}
	});
} 

function FiltraTipInfrac(infraccion){

	var contentAjax = $('#codigo_infraccion').html('...');
	var anno=$('#anno_area').val();
	
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selecttipo",
		data: "tipo_infraccion="+infraccion+"&tipocontri_multa="+$('#tipocontri_multa').val()+"&anno_area="+anno,
		success: function(response){
			contentAjax.html(response);			
		}
	});
	
}

function calcUIT(val){
	var anno = val.substr(6,4);
	$.ajax({
		type: "POST",
		url: urljs + "preventiva/selectuit",
		data: "anno="+anno,
		success: function(response){
			$('#base').val(response);
			calcTotal($('#codigo_infraccion').val());
			$('#anno_multa').val(anno);
		}
	});
	
}

function calcTotal(val){

	var partes = val.split(':');
	var factor = parseFloat(partes[5]);
	
	var cantidad = parseFloat($('#cantidad').val());
	var base = parseFloat($('#base').val());
	
	var total = factor*base/100*cantidad;
	
	if(!isNaN(factor))
		$('#factor').val(factor.toFixed(2));
	else
		$('#factor').val('000');
	
	if(!isNaN(factor))	
		$('#montoTotal').val(total.toFixed(2));
	else
		$('#montoTotal').val('000');
}
//$('#preimpreso').autotab({ format: 'alpha'});
$('#cantidad').autotab({ format: 'numeric'});

$('#tipo_infraccion').change(function(){
	
	if($(this).val()=='2011:10TRP:2')
	{
		$("#divPeriodo").show();
	}
	else
	{
		$("#divPeriodo").hide();
	}   
});

$('#chkPeriodo').click(function() {
	if($('#chkPeriodo').is(':checked'))
	{
		$('#factor').val('0.00');
	}
	else
	{
		calcTotal($('#codigo_infraccion').val())
	}
});






// $('#chkResoluciones').click(function() {
	// if($('#chkResoluciones').is(':checked'))
	// {
		// $('#divresolucion').show();			
	// }
	// else
	// {
		// $('#divresolucion').hide();	
	// }	
// });

function mostrarVias(flag){
	//var anno_bvia=$('#hd_anno').val();
	var fecha = new Date();
	var ano = fecha.getFullYear()
	//alert(ano);
	showPopup('preventiva/busvias?anno='+ano+'&valor='+flag,'#popbuscar','620','300','Listado de Vias');
}

window.muestraDatos = function(obj) { 
	$('#txtnomvia').val(obj.get('nomvia'));
	$('#txtidvia').val(obj.get('codigo'));
	//$('#txtCp').val(obj.get('nomurba'));
	$('#txtsector').val(obj.get('nomzona'));
	//$('#txtArancel').val(obj.get('arancel'));
	$('#txtsector').focus();
	
};

window.muestraDatos2 = function(obj) { 

	$('#txtZonacodigo').val(obj.get('codzona'));
	$('#txtZona').val(obj.get('nomzona'));
	
	$('#txtUrbacodigo').val(obj.get('codurba'));
	$('#txtUrbanizacion').val(obj.get('nomurba'));
	
	$('#txtViacodigo').val(obj.get('codigo'));
	$('#txtViacontri').val(obj.get('nomvia'));
	
	
	//$('#txtCp').val(obj.get('nomurba'));
	
	//$('#txtArancel').val(obj.get('arancel'));
	
};



$('#chkLicencia').click(function() {
	if($('#chkLicencia').is(':checked'))
	{
		$("#nlicencia").removeAttr("disabled");
        $("#cmbanolic").removeAttr("disabled");
	}
	else
	{
		$("#nlicencia").attr("disabled", "disabled");
		$("#cmbanolic").attr("disabled", "disabled");
	}	
});


$('#cmbdescargo').change(function() {
	if($('#cmbdescargo').val() == 1)
	{
		$("#cmbtipoexpdoc").removeAttr("disabled");
		$("#txtxnroexpdoc").removeAttr("disabled");
		$("#cb_fechexpdoc").removeAttr("disabled");
	}
	else
	{
		$("#cmbtipoexpdoc").attr("disabled", "disabled");
		$("#txtxnroexpdoc").attr("disabled", "disabled");
		$("#cb_fechexpdoc").attr("disabled", "disabled");

	}
});

// $('#chkResoluciones').click(function() {
	// if($('#chkResoluciones').is(':checked'))
	// {
		// var hora=$("#txthora").val();
		//alert(hora);
	// }	
// });

function validaText(val){
	
	
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	//var length= $('#txtndoc').val().length;
	$('#txtndoc').val('');
	$('#txtndoc').focus();
	
	//alert(ncar);
	//alert(length);
		
	if(ncar>0)
	{
		$('#txtndoc').attr('disabled',false);
		$('#txtndoc').autotab({ maxlength: ncar });
		
	}
	else
	{
		$('#txtndoc').val('');
		$('#txtndoc').attr('disabled',true);
		
		
	}

}


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
