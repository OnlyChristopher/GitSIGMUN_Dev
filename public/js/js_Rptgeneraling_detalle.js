Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	CargaGrilla();

});

function CargaGrilla(){
	
	var Anio_Pago=$('#txtAnioPago').val();
	var Anio_Obl=$('#txtAnioObl').val();
	var Id_Zona=$('#txtIdZona').val();
	var Id_Mes=$('#txtIdMes').val();
	var cod_via=$('#cb_Vias').val();
	
	 data=[Anio_Pago,Anio_Obl,Id_Zona,Id_Mes,cod_via];
	 
	 
	 	Ext.Ajax.request({
		  url: urljs + "Rptgeneraling/rptdetalladovia",
		  method: "POST",
		  params: {json: JSON.stringify(data)},
		  success: function(response){
			  $('#ReporteIngre_Detallado').html(response.responseText);
		  }
	});
	
}

$( "#cb_Vias" ).change(function() {
  CargaGrilla();
});


