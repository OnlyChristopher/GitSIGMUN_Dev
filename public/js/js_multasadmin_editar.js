//Ext.onReady(function(){
	// if($('#chkResoluciones').attr('checked',true))
	// {
		// $('#divresolucion').show();			
	// }
	// else
	// {
		// $('#divresolucion').hide();
	// }
	
	//$('#divresolucion').hide();
//});

$(function(){

	//$('#chkResoluciones').attr('checked',true)

	$('#divresolucion').hide();
	
	$('#divmonto').hide();
	
	$("#divvalorizacion").hide();
	
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
			'txaObservacion': 		{ required: true }//3
		},
		messages: {
			'codigo_autoridad_municipal': 	{ required: 'Seleccione una autoridad' },
			'fecha_multa': 	{ required: 'Ingrese la fecha' },
			'preimpreso': 	{ required: 'Ingrese el preimpreso' },
			'estado': 	{ required: 'Seleccione el estado' },
			'codigo_area': 	{ required: 'Seleccione el 1° campo de infraccion' },
			'tipo_infraccion': 	{ required: 'Seleccione el 2° campo de infraccion' },
			'codigo_infraccion': 	{ required: 'Seleccione el 3° campo de infraccion' },
			'ctxtFechResolucion': 	{ required: 'Ingrese la fecha de la resolucion' },
			'txtNResolucion': 	{ required: 'Ingrese el Numnero de resolucion' },
			'txaObservacion': 	{ required: 'ingrese la glosa' },
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			var showResult = function(btn){
			if(btn=='yes')
			$.ajax({     
				type: "POST",     
				url: urljs + "multasadmin/grabar",
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
	
	//Todo a mayúscula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	calcUIT($('#fecha_multa').val());
	$('#cantidad').change(function(){
		calcTotal($('#codigo_infraccion').val());
	});
	
	if($('#numeroMulta').val()=='')                    // no muestre el id de un combo
	$("#estado option[value='3']").remove(); 		  //  no muestre el id de un combo
	
	if($('#chkResoluciones').is(':checked'))
	{
		$('#divresolucion').show();
	}
	
	if($('#chkGuardiania2').is(':checked'))
	{
		$('#divguardiania').hide();
		$('#divmonto').show();
	}
	
	
});

// EVENTO EL CUAL VA A PERMITIR VISUALIZAR LA CATEGORIA DE CADA AREA

function FiltraCodArea(cod_area){
	//alert(cod_area);
	//var array=val.split('*');
	//alert(array[0]+' + '+array[1]);
	var anno=$('#anno_area').val();
	//limpiamos el combo de categorias
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
	//alert(infraccion);
	var contentAjax = $('#codigo_infraccion').html('...');
	var anno=$('#anno_area').val();
	
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selecttipo",
		//data: "tipo_infraccion="+infraccion,
		data: "tipo_infraccion="+infraccion+"&tipocontri_multa="+$('#tipocontri_multa').val()+"&anno_area="+anno,
		success: function(response){
			contentAjax.html(response);			
		}
	});
}

function calcUIT(val){
	//alert(val);
	var anno = val.substr(6,4);
	$.ajax({
		type: "POST",
		url: urljs + "multasadmin/selectuit",
		data: "anno="+anno,
		success: function(response){
			$('#base').val(response);
			calcTotal($('#codigo_infraccion').val());		
		}
	});
	
}

//MOSTRAR LOS VALORES DE FACTOR Y MONTO TOTAL 
//01COM:1:101:1:1:25.00
function calcTotal(valor){

	//alert(valor);
	var array=valor.split(':');
	if(valor.substr(0,11)=='07URB:1:102' || valor.substr(0,11)=='07URB:1:103' || valor.substr(0,11)=='07URB:1:104' || valor.substr(0,15)=='331-2015:23:147' || valor.substr(0,15)=='331-2015:23:148'){
		$("#divvalorizacion").show();
		$("#divcalculo").hide();
		$('#txtfactorvalorizacion').val(array[5]);
		$('#txtvalorizacion').val('');
		$('#txtvalorizacion').focus();
		$('#txtmultavalorizacion').val('0.00');
	}
	else{
		$("#divvalorizacion").hide();
		$("#divcalculo").show();
	}
	
	//alert('manuel');
	var partes = valor.split(':');

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


$('#chkResoluciones').click(function() {
	if($('#chkResoluciones').is(':checked'))
	{
		$('#divresolucion').show();			
	}
	else
	{
		$('#divresolucion').hide();	
	}	
});


$('#chkGuardiania2').click(function() {
	if($('#chkGuardiania2').is(':checked'))
	{
		$('#txtdias').focus();
		//alert('checked');
		 $('#divguardiania').hide();
		 $('#divmonto').show();
		 $('#divvalorizacion').hide();
	}
	else
	{	//alert('unchecked');
		 $('#divguardiania').show();
		 $('#divmonto').hide();
	}	
});

$('#rbtmototaxi').click(function() {
	if($('#rbtmototaxi').is(':checked'))
	{
		 $('#txtdias').val('');
		 $('#txtdias').focus();
		 $('#txtvalor').val('15.00');
		 $('#txtvalorinternamiento').val('0.00');
	}	
});

$('#rbtcarreta').click(function() {
	if($('#rbtcarreta').is(':checked'))
	{
		 $('#txtdias').val('');
		 $('#txtdias').focus();
		 $('#txtvalor').val('10.00');
		 $('#txtvalorinternamiento').val('0.00');
	}	
});

$( "#txtvalorizacion" ).keyup(function() {
  var valor1=$('#txtvalorizacion').val();
  if(valor1.length=='0')

  $('#txtmultavalorizacion').val('0.00');
else{
  var valor2=$('#txtfactorvalorizacion').val();
  var resc=(parseFloat(valor1)*parseFloat(valor2))/100;
  $('#txtmultavalorizacion').val(resc.toFixed(2));
  }
});

$( "#txtdias" ).keyup(function() {
  var valor1=$('#txtdias').val();
  if(valor1.length=='0')

  $('#txtvalorinternamiento').val('0.00');
else{
  var valor2=$('#txtvalor').val();
  var resc=(parseFloat(valor1)*parseFloat(valor2));
  $('#txtvalorinternamiento').val(resc.toFixed(2));
}  
});








