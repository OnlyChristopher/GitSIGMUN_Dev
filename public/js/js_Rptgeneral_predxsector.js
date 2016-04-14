Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	$('#txtCodigo').focus();
});

$("#btnProcesarPrediosXSector").click(function() {
	
	Procesar_RPT_Predio_x_Sector();
	
});

function Procesar_RPT_Predio_x_Sector()
{
	 //alert('Procesar');
	 codigo=$('#txtCodigo').val();
	 anio=$('#cb_anno').val();
	 sector=$('#cb_sector').val();
	 
	 data=[codigo,anio,sector];
	 
	callAjax('Rptgeneralpps/reporte02',{json:JSON.stringify(data)},'#ReporteXSector','html')

}

$('#txtCodigo').keypress(function(e) {
        if(e.which == 13) {
        	Procesar_RPT_Predio_x_Uso();
        }
});
	
function GrapIngresoxGerencia()
{
	var cb_anno=$('#cb_anno').val();
	var cb_sector=$('#cb_sector').val();
	
	//alert(cb_anno); 
	showPopupReportHtml('rptgeneralpps/graprpt02?anio='+cb_anno+'&sector='+cb_sector,'pouprptgraph02','GRAFICO --> PREDIOS X SECTOR');
	
}

	function exportarPredxsector()
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
			var cb_sector = $('#cb_sector').val();
			
		
	
			
			
			
			//data = "?suministro="+txtSuministro++"&area="+cmbArea+"&req_fech1="+txtFec1+"&req_fech2="+txtFec2;
			data = "?codigo="+txtCodigo+"&anno="+cb_anno+"&sector="+cb_sector;
			
			window.open(urljs+"rptgeneralpps/exportarrpt02"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}

function imprimeReportexSector(flag,id_zona,anno)
{
	//alert(flag+'/'+id_zona+'/'+anno);
	showPopup('rptgeneralpps/reportexsector?anno='+anno+'&id_zona='+id_zona+'&flag='+flag,'#popupreportexsector','1190','400','Reporte Por Sector');
	
}

	


