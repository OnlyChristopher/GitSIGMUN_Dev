Ext.onReady(function(){
	$("input[type='button']").button();
	
	$("#btnCancelPredio").click(function(){
		closePopup('#popupregpredio');
	});

	$("#btnSaveDirPredio").click(
		
		function(){
			 if ($('#hdCodigoVia').val().trim()=='') {
			 	infoMessage('Error','Debe ingresar datos de la v&iacute;a');
			 }else{
				goToFormulario("formDirPredio");
			 }
		}

	);

	//Valida y env�a form contribuyente
	
	$('#formDirPredio').validate({
		rules: {
			
			
			'txtCodigoVia': 'required',
			'txtNumero': 'required',
			'txtMz' : 'required',
			'txtLote': 'required'		
		},

		messages: {
	
			'txtCodigoVia': 'Debe seleccionar tipo de via ',
			'txtNumero': 'Debe ingresar numero',
			'txtMz':'Debe ingresar manzana',
			'txtLote': 'Debe ingresar lote'
		},		
				
		debug: true,
		errorElement: 'div',
		errorContainer: $('#errores'),
		submitHandler: function(form){
					 Ext.Ajax.request({
		                url: urljs + "cartareq/generadircompleta",
		                method: "POST",
		                params: {
		                            id_via : $("#hdCodigoVia").val(),
							        num_call :  $("#txtNumero").val().toUpperCase(),        
							        num_manz :  $("#txtMz").val().toUpperCase(),
							        num_lote :  $("#txtLote").val().toUpperCase(),
							        sub_lote :  $("#txtSubLote").val().toUpperCase(),
							        num_depa : $("#txtDepartamento").val().toUpperCase(),
							        referenc : $("#txtReferencia").val().toUpperCase()
		                        },
		                success: function(response){               
		                	var data = response.responseText;

		                    var msj = data.split("|");
                            var mensaje = msj[1];
                    
                            if (msj[0]=="TRUE") {

                                    addRowsDirPredio2(mensaje);		                    
		                    		closePopup("#popupregpredio");
                            }else{
                                    errorMessage('Mensaje',mensaje);
                            }


		                },
		                failure: function(response, opts){
		                    infoMessage('Alerta','Error');
		                }

            		});     
				
			}
		
	});
	
	

});


function addRowsDirPredio2(dirCompletaPredio){
    var grid = Ext.getCmp('xgridPredios');
    var store = grid.getStore();
    var r = Ext.create('Tpredios', {
    	cod_pred: '',   
    	anexo : '',
    	sub_anexo: '', 	
    	id_urba: '',
        id_via : $("#hdCodigoVia").val(),
        num_call :  $("#txtNumero").val().toUpperCase(),        
        num_manz :  $("#txtMz").val().toUpperCase(),
        num_lote :  $("#txtLote").val().toUpperCase(),
        sub_lote :  $("#txtSubLote").val().toUpperCase(),
        num_depa : $("#txtDepartamento").val().toUpperCase(),
        referenc : $("#txtReferencia").val().toUpperCase(),
        dirPredio: dirCompletaPredio,
     	nueva_dir : 1

     	    });
    store.insert(store.data.length, r);

}