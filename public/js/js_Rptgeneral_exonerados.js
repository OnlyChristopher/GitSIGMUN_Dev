Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	$('#txtCodigo').focus();
});

$("#btnProcesar_Exonerados").click(function() {
	
	Procesar_RPT_Exonerados();
	
});

function Procesar_RPT_Exonerados()
{
	 //alert('Procesar');
	 anio=$('#cb_anno').val();
	 
	 data=[anio];
	 callAjax('Rptgeneralexo/reporte04',{json:JSON.stringify(data)},'#ReporteExonerados','html')
	 
}

	
function exportarExonerados()
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
			
			var txtCodigo = $('#txtCodigo').val();
			var cb_anno = $('#cb_anno').val();
		
			
		
	
			
			
			
			//data = "?suministro="+txtSuministro++"&area="+cmbArea+"&req_fech1="+txtFec1+"&req_fech2="+txtFec2;
			data = "?codigo="+txtCodigo+"&anno="+cb_anno;
			
			window.open(urljs+"rptgeneralexo/exportarrpt06"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}
	

	
	
