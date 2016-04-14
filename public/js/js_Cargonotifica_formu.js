Ext.onReady(function(){
	$("input[type='button']").button();
	
	$("#btnCancelCedulon").click(function(){
		closePopup('#popupnotificacion');
	});

	$("#btnSaveNotificacion").click(function(){
    
      goToFormulario("formNotificacion");
    
    });
    
	//Valida y env�a form contribuyente
	$('#formNotificacion').validate({
		
		debug: true,
		errorElement: 'div',
		errorContainer: $('#errores'),
		submitHandler: function(form){
	           $.ajax({     
                    type: "POST",  
                    url: urljs + "cargonotifica/grabar",
                    data: $('#formNotificacion').serializeObject(),     
                    success: function(data) {
                       
                            infoMessage('Cargo de Notificacion',data);
                            closePopup('#popupnotificacion');

                    },     

                    error: function(data) {
                        infoMessage('Error',data);                      
                    } 

                }); 
        }
    		
		
    });

    ActivarOtros();
     
});


function ActivarOtros(){

    if($("#chkVinculoOtros").is(':checked')) {  
             $('#txtVinculoOtros').removeClass('cajaoff').addClass('caja');
             $("#txtVinculoOtros").attr("readonly",false);              
    }

    if($("#chkMotNoEntregaOtros").is(':checked')) {  
             $('#txtNoEntregaOtros').removeClass('cajaoff').addClass('caja');
             $("#txtNoEntregaOtros").attr("readonly",false);              
    }

    if($("#chkInmuebleOtros").is(':checked')) {  
             $('#txtInmuebleOtros').removeClass('cajaoff').addClass('caja');
             $("#txtInmuebleOtros").attr("readonly",false);              
    }
}

function validarMaxDocIndent(){
    if($("#chkDni").is(':checked')) {  
            var maxChar = $("#hmaxDni") .val();
            var valor = $("#txtNroDocIdent").val();
            if (valor.length!=maxChar && valor!="") { 
                infoMessage("Alerta","Nro de documento debe tener "+maxChar+" digitos.");
                $('#txtNroDocIdent').focus();
            };
    }
    if($("#chkIdentidad").is(':checked')) {  
            var maxChar = $("#hmaxIdentidad") .val();
            var valor = $("#txtNroDocIdent").val();
            if (valor.length!=maxChar && valor!="") { 
                infoMessage("Alerta","Nro de documento debe tener "+maxChar+" digitos.");
                $('#txtNroDocIdent').focus();
            };
    }
    if($("#chkExtranjeria").is(':checked')) {  
            var maxChar = $("#hmaxExtranjeria") .val();
            var valor = $("#txtNroDocIdent").val();
            if (valor.length!=maxChar && valor!="") { 
                infoMessage("Alerta","Nro de documento debe tener "+maxChar+" digitos.");
                $('#txtNroDocIdent').focus();
            };
    }
}

//PARA LOS CHECKS Tipos de documentos de persona que recepciona

$("#chkDni").click(function() {  
        if($("#chkDni").is(':checked')) {  
             $("#chkIdentidad").attr('checked', false);  
             $("#chkExtranjeria").attr('checked', false);  
           
        }
        $('#txtNroDocIdent').focus();
      //  validarMaxDocIndent();
}); 

$("#chkIdentidad").click(function() {  
        if($("#chkIdentidad").is(':checked')) {  
             $("#chkDni").attr('checked', false);  
             $("#chkExtranjeria").attr('checked', false);  
        } 
        $('#txtNroDocIdent').focus();
      //  validarMaxDocIndent();
}); 

$("#chkExtranjeria").click(function() {  
        if($("#chkExtranjeria").is(':checked')) {  
             $("#chkDni").attr('checked', false);  
             $("#chkIdentidad").attr('checked', false);  
        } 
        $('#txtNroDocIdent').focus();
     //   validarMaxDocIndent();
});  
/*
$("#txtNroDocIdent").focusout(function(){
    validarMaxDocIndent();
});*/

//PARA LOS CHECKS NegoIdentificar,firmar,recibir
/*
$("#chkNegoIdentificar").click(function() {  
        if($("#chkNegoIdentificar").is(':checked')) {  
             $("#chkNegoFirmar").attr('checked', false);  
             $("#chkNegoRecibir").attr('checked', false);  
        } 
}); 
$("#chkNegoFirmar").click(function() {  
        if($("#chkNegoFirmar").is(':checked')) {  
             $("#chkNegoIdentificar").attr('checked', false);  
             $("#chkNegoRecibir").attr('checked', false);  
        } 
}); 
$("#chkNegoRecibir").click(function() {  
        if($("#chkNegoRecibir").is(':checked')) {  
             $("#chkNegoIdentificar").attr('checked', false);  
             $("#chkNegoFirmar").attr('checked', false);  
        } 
});  
 */
//Para los checks Vinculo
$("#chkTitular").click(function() {  
        if($("#chkTitular").is(':checked')) {  
             $("#chkFamiliar").attr('checked', false);  
             $("#chkVigilante").attr('checked', false);  
             $("#chkEmpleado").attr('checked', false);  
             $("#chkRepresentante").attr('checked', false);  
             $("#chkVinculoOtros").attr('checked', false);            
             $("#txtVinculoOtros").val('');
             $('#txtVinculoOtros').removeClass('caja').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly", true);   

             //muestra datos de titular
             $("#txtNomApeRecepciona").val($("#txtContribuyenteN").val());
             $("#txtNroDocIdent").val($("#hNroDocIdent").val());

             var tipoDocIdent =  $("#hTipoDocIdent").val();
             if (tipoDocIdent==1) {$("#chkDni").attr('checked', true);};
             if (tipoDocIdent==5) {$("#chkIdentidad").attr('checked', true);};
             if (tipoDocIdent==9) {$("#chkExtranjeria").attr('checked', true);};

             $("#txtNroDocIdent").focus();

        } 
}); 

$("#chkFamiliar").click(function() {  
        if($("#chkFamiliar").is(':checked')) {  
             $("#chkTitular").attr('checked', false);  
             $("#chkVigilante").attr('checked', false);  
             $("#chkEmpleado").attr('checked', false);  
             $("#chkRepresentante").attr('checked', false);  
             $("#chkVinculoOtros").attr('checked', false);            
             $("#txtVinculoOtros").val('');
             $('#txtVinculoOtros').removeClass('caja').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly", true);   

        } 
}); 

$("#chkVigilante").click(function() {  
        if($("#chkVigilante").is(':checked')) {  
             $("#chkTitular").attr('checked', false);  
             $("#chkFamiliar").attr('checked', false);  
             $("#chkEmpleado").attr('checked', false);  
             $("#chkRepresentante").attr('checked', false);  
             $("#chkVinculoOtros").attr('checked', false);            
             $("#txtVinculoOtros").val('');
             $('#txtVinculoOtros').removeClass('caja').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly", true);   

        } 
}); 

$("#chkEmpleado").click(function() {  
        if($("#chkEmpleado").is(':checked')) {  
             $("#chkTitular").attr('checked', false);  
             $("#chkFamiliar").attr('checked', false);  
             $("#chkVigilante").attr('checked', false);  
             $("#chkRepresentante").attr('checked', false);  
             $("#chkVinculoOtros").attr('checked', false);            
             $("#txtVinculoOtros").val('');
             $('#txtVinculoOtros').removeClass('caja').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly", true);   

        } 
});

$("#chkRepresentante").click(function() {  
        if($("#chkRepresentante").is(':checked')) {  
             $("#chkTitular").attr('checked', false);  
             $("#chkFamiliar").attr('checked', false);  
             $("#chkVigilante").attr('checked', false);  
             $("#chkEmpleado").attr('checked', false);  
             $("#chkVinculoOtros").attr('checked', false);            
             $("#txtVinculoOtros").val('');
             $('#txtVinculoOtros').removeClass('caja').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly", true);   

        } 
});

$("#chkVinculoOtros").click(function() {  
        if($("#chkVinculoOtros").is(':checked')) {  
             
             $("#chkTitular").attr('checked', false);            
             $("#chkFamiliar").attr('checked', false);  
             $("#chkVigilante").attr('checked', false);  
             $("#chkEmpleado").attr('checked', false);  
             $("#chkRepresentante").attr('checked', false);  
             $('#txtVinculoOtros').removeClass('cajaoff').addClass('caja');
             $("#txtVinculoOtros").attr("readonly",false);   
             $("#txtVinculoOtros").val(''); 
             $("#txtVinculoOtros").focus();

        }else{
            $('#txtVinculoOtros').removeClass('cajao').addClass('cajaoff');
             $("#txtVinculoOtros").attr("readonly",true);   
             $("#txtVinculoOtros").val('');              
        } 
}); 
 

 //*********** Notificacion por pegado de cedulon y motviso de no entrega ***********/

    //--- Checks Persona incapaz y do micilio cerrado
 
$("#chkPersonaIncapaz").click(function() {  
        if($("#chkPersonaIncapaz").is(':checked')) {               
             $("#chkDomicilioCerrado").attr('checked', false);                       
        } 
});

$("#chkDomicilioCerrado").click(function() {  
        if($("#chkDomicilioCerrado").is(':checked')) {               
             $("#chkPersonaIncapaz").attr('checked', false);                       
        } 
});

     //--- Motivos de no entrega
   
$("#chkDireccionIncorrecta").click(function() {  
        if($("#chkDireccionIncorrecta").is(':checked')) {  
             $("#chkDireccionInexistente").attr('checked', false);               
             $("#chkMotNoEntregaOtros").attr('checked', false);               
             $("#txtNoEntregaOtros").val('');
             $('#txtNoEntregaOtros').removeClass('caja').addClass('cajaoff');
             $("#txtNoEntregaOtros").attr("readonly", true);   

        } 
}); 

$("#chkDireccionInexistente").click(function() {  
        if($("#chkDireccionInexistente").is(':checked')) {  
             $("#chkDireccionIncorrecta").attr('checked', false); 
             $("#chkMotNoEntregaOtros").attr('checked', false);                  
             $("#txtNoEntregaOtros").val('');
             $('#txtNoEntregaOtros').removeClass('caja').addClass('cajaoff');
             $("#txtNoEntregaOtros").attr("readonly", true);   

        } 
}); 

$("#chkMotNoEntregaOtros").click(function() {  
        if($("#chkMotNoEntregaOtros").is(':checked')) {              
             $("#chkDireccionInexistente").attr('checked', false);            
             $("#chkDireccionIncorrecta").attr('checked', false);            
             $('#txtNoEntregaOtros').removeClass('cajaoff').addClass('caja');
             $("#txtNoEntregaOtros").attr("readonly",false);                
             $("#txtNoEntregaOtros").focus();

        }else{
             $("#txtNoEntregaOtros").val('');
             $('#txtNoEntregaOtros').removeClass('caja').addClass('cajaoff');
             $("#txtNoEntregaOtros").attr("readonly", true);   
        } 
}); 

    //--- Datos del inmueble

$("#chkCasa").click(function() {  
        if($("#chkCasa").is(':checked')) {               
             $("#chkEdificio").attr('checked', false);                       
        } 
});

$("#chkEdificio").click(function() {  
        if($("#chkEdificio").is(':checked')) {               
             $("#chkCasa").attr('checked', false);                       
        } 
});

   //--- Datos del inmueble

$("#chkMadera").click(function() {  
        if($("#chkMadera").is(':checked')) {               
             $("#chkFierro").attr('checked', false);                       
        } 
});

$("#chkFierro").click(function() {  
        if($("#chkFierro").is(':checked')) {               
             $("#chkMadera").attr('checked', false);                       
        } 
});

$("#chkInmuebleOtros").click(function() {  
        if($("#chkInmuebleOtros").is(':checked')) {              
                
             $('#txtInmuebleOtros').removeClass('cajaoff').addClass('caja');
             $("#txtInmuebleOtros").attr("readonly",false);                
             $("#txtInmuebleOtros").focus();

        }else{
             $("#txtInmuebleOtros").val('');
             $('#txtInmuebleOtros').removeClass('caja').addClass('cajaoff');
             $("#txtInmuebleOtros").attr("readonly", true);   
        } 
}); 

$('#cbNotificadores').change(function(){
    var value = $('#cbNotificadores').val();
    var pos = value.indexOf('-');
    var dni = value.substring(pos+1);
    $('#txtdniNotificador').val(dni);
});

  $('#btnImprimirCargo').click(function(){
    showPopupReport('schema=&tipo=pdf&nombrereporte=CartaReq_cargonotificacion&param=idCartaReq^'+$('#hcodCartaReq').val(),'reporteCedulon',700,600,'Reporte Cargo de Notificaci&oacute;n');
    //showPopupReport('schema=&tipo=pdf&nombrereporte=rptdescargo&param=codigo^'+codigo+'|anno^'+anno+'|cod_pred^'+cod_pred+'|anexo^'+anexo+'|sub_anexo^'+sub_anexo,'reportebajapredio',700,600,'Baja de Predio');    
    });
