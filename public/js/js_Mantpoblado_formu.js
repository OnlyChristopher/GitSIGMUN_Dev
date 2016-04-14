$(function(){
	
	$("input[type='button']").button();
	
	//Valida y envía form contribuyente
	$('#frmpoblado').validate({
		rules: {
		'rdCriterioTipo': 'required',
		'cmbUbicacion': 'required',
		'txtCuadra': 'required',
		'cmbZona': 'required',
		'rdTipozona':'required',
		'txtNombre':'required'
		},
		messages: {
		'rdCriterioTipo': 'Debe seleccionar un criterio',
		'cmbUbicacion': 'Especifique la ubicación',
		'txtCuadra': 'Ingrese una cuadra',
		'cmbZona': 'Especifique la zona',
		'rdTipozona':'Seleccione el tipo de zona',
		'txtNombre':'Digite el nombre'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			
				$.ajax({     
					type: "POST",  
					url: "mantpoblado/grabar",
					data: $('#frmpoblado').serializeObject(),     
					success: function(data) { 					
						
						infoMessage('Guardando Via',data);
						
						closePopup('#popNueZona');
						Ext.getCmp('xgridPoblad').getStore().load();
					},     
					error: function() {
					} 
				}); 				
					
		}
	});
});

function validarcentro(){
	var cmbUrba = $('#cmbUrba').val().trim();
	var txtNomPoblado = $('#txtNomPoblado').val().trim();
	var cmbZona = $('#cmbZona').val().trim();
	var retorno='true/Guardando Centro Poblado';
	
	if(cmbUrba!=""){
		if (txtNomPoblado==""){
			retorno='false/Por favor ingrese el nombre';
			return(retorno);
		}
		if (cmbZona==""){
			retorno='false/Por favor ingrese la zona';
			return(retorno);	
		}
	}else{
			retorno='false/Por favor ingrese la urbanizacion';
			return(retorno);
		}
	return(retorno);
}


$('#rdTipoVia').click(function(){
	
	 if($('#rdTipoVia').attr('checked'))
	 {
		//alert('via');
		var tipoubicacion=($(this).val());
		//alert(tipoubicacion);
    
			$.ajax({     
				type: "POST",     
				url: "mantpoblado/constubicacion",
				data: 'id_tipo='+tipoubicacion,
				success: function(data) { 
					$('#cmbUbicacion').html(data);		

				}
			});
		
	 }
});


$('#rdTipoDenominacion').click(function(){
	
	 if($('#rdTipoDenominacion').attr('checked'))
	 {
		var tipoubicacion=($(this).val());
		//alert(tipoubicacion);
    
			$.ajax({     
				type: "POST",     
				url: "mantpoblado/constubicacion",
				data: 'id_tipo='+tipoubicacion,
				success: function(data) { 
					$('#cmbUbicacion').html(data);		

				}
			});
		
	 }
});


$('#rdTipoVia').click(function(){
	
	 if($('#rdTipoVia').attr('checked'))
	 {
		//alert($('#rdTipoVia').val());
	 }
});

$('#rdTipoDenominacion').click(function(){
	
	 if($('#rdTipoDenominacion').attr('checked'))
	 {
		//alert($('#rdTipoDenominacion').val());
	 }
});

$("#cmbUbicacion").change(function(){
    //alert($('select[id=cmbUbicacion]').val());
});

$('#txtCuadra').blur(function(){
    var numCeros = '00'; // pon el nº de ceros que necesites
    var valor =  $('#txtCuadra').val().trim();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if( valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriterio').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCuadra').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCuadra').val(valor);
    }
    
});




