Ext.onReady(function(){
	$("input[type='button']").button();
	
	$("#btnCancelCedulon").click(function(){
		closePopup('#popupcedulon');
	});

	$("#btnSaveCedulon").click(function(){
    
      goToFormulario("formCedulon");
    
    });
    
	//Valida y env�a form contribuyente
	$('#formCedulon').validate({
		rules: {
           'dpFechaC'  : 'required',
           'txtHoraC' : 'required'
        },

        messages:{
            'dpFechaC'  : 'Debe ingresar Fecha del cedulon',
            'txtHoraC' : 'Debe ingresar hora del cedulon'            
        },
		debug: true,
		errorElement: 'div',
		errorContainer: $('#errores'),
		submitHandler: function(form){
	           $.ajax({     
                    type: "POST",  
                    url: urljs + "cedulon/grabar",
                    data: $('#formCedulon').serializeObject(),     
                    success: function(data) {
                       
                            infoMessage('Cedulon',data);
                            closePopup('#popupcedulon');

                    },     

                    error: function(data) {
                        infoMessage('Error',data);                      
                    } 

                }); 
        }
    		
		
    });
   
});

$("#chkDomicilioCerrado").click(function() {  
        if($("#chkDomicilioCerrado").is(':checked')) {  
             $("#chkPersonaIncapaz").attr('checked', false);  
        } 
}); 
$("#chkPersonaIncapaz").click(function() {  
        if($("#chkPersonaIncapaz").is(':checked')) {  
             $("#chkDomicilioCerrado").attr('checked', false);  
        } 
}); 

$( "#cbTipoDoc1" ).change(function() {  validar(1); });
$( "#cbTipoDoc2" ).change(function() {  validar(2); });
$( "#cbTipoDoc3" ).change(function() {  validar(3); });

function validar(nro){
    var valor = $("#cbTipoDoc"+nro).val();
    
    if (valor.trim()!='') {
        //si no se repite el tipo de doc en los otros comnbos, entonces es valido seleccionar
        if(verificaCombosTipoDoc(nro)){
            var texto = $("#cbTipoDoc"+nro+" option:selected").html().toUpperCase();
            if (texto == "ACTA DE VISITA") { $('#txtNroDoc'+nro).val($('#tempNroActaVisita').val());};  
            if (texto == "CARTA DE PRESENTACION Y REQUERIMIENTO DE DOCUMENTACION") {$('#txtNroDoc'+nro).val($('#tempNroCarta').val());};  
            if (texto == "CARGO DE NOTIFICACION") {$('#txtNroDoc'+nro).val($('#tempNroCargoNotifica').val());};  
        
        }else{            
            infoMessage("Alerta","El tipo de documento ya se encuentra seleccionado")            
            $("#cbTipoDoc"+nro).val("");
            $("#txtNroDoc"+nro).val("");
            $("#cbTipoDoc"+nro).focus();
        }
    }else{        
        $("#txtNroDoc"+nro).val('');
    }
}
function verificaCombosTipoDoc(nro){
    var valor1 = $("#cbTipoDoc1 option:selected").html().toUpperCase();
    var valor2 = $("#cbTipoDoc2 option:selected").html().toUpperCase();
    var valor3 = $("#cbTipoDoc3 option:selected").html().toUpperCase();
    var habilitado = true;
    if (nro==1) {
        if (valor1 == valor2 || valor1 == valor3) {habilitado=false};
    }else if(nro==2){
        if (valor2 == valor3 || valor2 == valor1) {habilitado=false};
    }else if (nro==3) {
        if (valor3 == valor2 || valor3 == valor1) {habilitado=false};
    }

    return habilitado;

}

  $('#btnImprimirCedulon').click(function(){
    showPopupReport('schema=&tipo=pdf&nombrereporte=CartaReq_cedulon&param=idCartaReq^'+$('#hcodCartaReq').val(),'reporteCedulon',700,600,'Reporte Notificaci&oacute;n por cedul&oacute;n');
    });
