Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
});

$("#btnProcesarTOP").click(function() {
	
	Procesar_RPT_Top_IP();
	
});

function Procesar_RPT_Top_IP()
{
	 //alert('Procesar');
	 cb_limita=$('#cb_limita').val();
	 cb_concepto=$('#cb_concepto').val();
	//alert(cb_limita);
	
	 data=[cb_limita,cb_concepto];
	 callAjax('Rptgeneraltopdeudor/reporte05',{json:JSON.stringify(data)},'#ReporteTopDeudores','html')

	
}

function exportarTopDeudaIp()
{
	var showResult = function(btn){
		if(btn=='yes'){
		/*
			var txtSuministro = $('#txtSuministro').val();
			var txtParqueUbi = $('#txtParqueUbi').val();
			var cmbArea = $('#cmbArea').val();
			var txtFec1 = $('#txtFecha1').val();
			var txtFec2 = $('#txtFecha2').val();
			*/
			
			
			var cb_limita = $('#cb_limita').val();
			var cb_concepto = $('#cb_concepto').val();
			
		
			//data = "?suministro="+txtSuministro++"&area="+cmbArea+"&req_fech1="+txtFec1+"&req_fech2="+txtFec2;
			data = "?limita="+cb_limita+"&concepto="+cb_concepto;
			
			window.open(urljs+"rptgeneraltopdeudor/exportarrpt10"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}


	
	
