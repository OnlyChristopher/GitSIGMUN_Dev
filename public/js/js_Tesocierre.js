$(function(){
	$("input[type='button']").button();
});

function grabarApertura(){

		var showResult = function(btn){
				if(btn=='yes'){
	
					Ext.Ajax.request({
			            url: urljs + "tesocierre/apertura",
			            method: "POST",
			            success: function(response){
							verificaApertura();
							goToInterno(urljs + "tesocierre/index",'Tesorería Municipal');
			            },
			            failure: function(response, opts){
			            	infoMessage('Contribuyentes','Error al Ejecutar funcion... ');
			            }
			        });
				}
	 		};
	 		
	 		confirmMessage('Apertura y Cierre','Seguro de Aperturar la caja?',showResult);
}

function grabarCierre(){
		var showResult = function(btn){
			if(btn=='yes'){

				Ext.Ajax.request({
					url: urljs + "tesocierre/cierre",
					method: "POST",
					success: function(response){
						if(response.responseText=='1'){
							verificaApertura();
							goToInterno(urljs + "tesocierre/index",'Tesorería Municipal');
							return;
						}

						if(response.responseText=='0'){
							infoMessage('Apertura y Cierre','La caja no esta cuadrada');
							return;
						}
						
					},
					failure: function(response, opts){
						infoMessage('Contribuyentes','Error al Ejecutar funcion... ');
					}
				});
			}
		};
		
		confirmMessage('Apertura y Cierre','Seguro de Cerrar la caja?',showResult);
}
function abrecuadre(){
	goToInterno(urljs + "tesocierre/cuadrecaja",'Tesorería Municipal');
}