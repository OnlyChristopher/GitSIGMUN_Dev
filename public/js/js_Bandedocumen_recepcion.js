/*$("#combo").change(function() {
		var op = $("#combo option:selected").val();
		$('#capa').html(op);
});
*/
function validaText(val){
	var op = val.substr(0,2);
	var ncar = val.substr(3,4);
	
	//alert(ncar);
	if(ncar>0)
	{
		$('#txtDocumento').attr('disabled',false);
		$('#txtDocumento').autotab({ maxlength: ncar });
	}
	else
	{
		$('#txtDocumento').val('');
		$('#txtDocumento').attr('disabled',true);
	}
}

	
$(function(){
	//Valida y envía form contribuyente
	$('#frmrecepcion').validate({
		rules: {
		'txtNotificador': 'required'
		//'txtRecepcionista':'required',
		//'cmbDocumento':'required',
		//'txtDocumento': 'required',
		//'txtParentesco': 'required',
		//'txtFecharecep': 'required',
		//'txtSuministro': 'required',
		//'txtObservaciones': 'required'
		//'txtNumDoc': { required: true, digits: true }
		},
		messages: {
		'txtNotificador': 'Debe ingresar el notificador'
		//'txtRecepcionista':'required',
		//'cmbDocumento':'required',
		//'txtDocumento': 'required',
		//'txtParentesco': 'required',
		//'txtFecharecep': 'required',
		//'txtSuministro': 'required',
		//'txtObservaciones': 'required'
		//'txtNomPoblado': 'Debe ingresar el nombre',
		//'cmbAreades':'Debe seleccionar un area',
		//'cmbSituacion':'Debe seleccionar una Situación',
		//'txtareas': 'Debe ingresar uns observación'
		//'cmbZona':'Debe seleccionar una zona'
		//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			var myMask = new Ext.LoadMask(Ext.get('popup_popuprecepcion'), {msg:"Guardando..."});
			
	        var controles={
	        		txtValorrecep:$('#txtValorrecep').val(),
	        		txtNumrecep:$('#txtNumrecep').val(),
	        		txtAnorecep:$('#txtAnorecep').val(),
	        		txtNotificador:$('#txtNotificador').val(),
	        		txtRecepcionista:$('#txtRecepcionista').val(),
	        		cmbDocumento:$('#cmbDocumento').val(),
	        		txtDocumento:$('#txtDocumento').val(),
	        		txtParentesco:$('#txtParentesco').val(),
	        		txtFecharecep:$('#txtFecharecep').val(),	                
	        		txtSuministro:$('#txtSuministro').val(),
	        		txtObservaciones:$('#txtObservaciones').val(),
	        		chbFirmo:$('#chbFirmo').is(':checked')
	            };
	        
			$.ajax({     
				type: "POST",     
				url: "bandedocumen/grabarrecepcion",
				//data: $('#frmrecepcion').serializeObject(),   
				data: controles, 
				success: function(data) { 
					//alert(data);
					infoMessage('Guardando Recepcion',data);
					closePopup('#popuprecepcion');
					Ext.getCmp('xgridBandeja').getStore().load();
				},     
				error: function() {
				} 
			}); 
		}		 
	});
	
	validaText('01/8');
	
});


$('#cmbDocumento').change(function(){
    $('#txtDocumento').val('');
});

/*
$('#cmbAreades').change(function(){
    var areadestino=($(this).val());
   
    $('#txtAreades').val(areadestino);
    
    $.ajax({     
		type: "POST",     
		url: "bandedocumen/consusario",
		data: 'area='+areadestino,
		success: function(data) { 
			$('#cmbUsuario').html(data);		
			$('#txtUsuario').val('');
			//alert(data);
		}
	});
});


$('#cmbUsuario').change(function(){
    var usuario=($(this).val());
    $('#txtUsuario').val(usuario);
});


$(function(){

	$.ajax({     
		type: "POST",     
		url: "bandedocumen/fecha",
		//data: '',
		success: function(data) { 
			$('#txtSalida').val(data);			
			//alert(data);
		}
	});
});

*/


/*
$('#cmbAreades').change(function(){
	alert($(this).val());   
});
*/
/*
var partida= combo;
$('#txtPartida').val(partida);
*/

