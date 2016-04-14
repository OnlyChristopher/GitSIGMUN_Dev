Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	$('#txtCodigo').focus();
});

$("#btnProcesarReporteXUso").click(function() {
	
	Procesar_RPT_Predio_x_Uso();
	
});

function Procesar_RPT_Predio_x_Uso()
{	
	 codigo=$('#txtCodigo').val();
	 anio=$('#cb_anno').val();
	 uso=$('#cb_uso').val();
	 
	 data=[codigo,anio,uso];
	
	 callAjax('Rptgeneralppu/reporte01',{json:JSON.stringify(data)},'#ReporteXUso','html')
	 
}

$('#txtCodigo').keypress(function(e) {
        if(e.which == 13) {
        	Procesar_RPT_Predio_x_Uso();
        }
    });
	
function ReporteIngresoxGerencia()
{
	var cb_anno=$('#cb_anno').val();
	//alert(cb_anno); 
	showPopupReportHtml('rptgeneralppu/graprpt01?anio='+cb_anno,'pouprptgraph01','GRAFICO --> PREDIOS X USO');
}
	
	
function exportarPredxuso()
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
			var cb_uso = $('#cb_uso').val();
			
		
	
			
			
			
			//data = "?suministro="+txtSuministro++"&area="+cmbArea+"&req_fech1="+txtFec1+"&req_fech2="+txtFec2;
			data = "?codigo="+txtCodigo+"&anno="+cb_anno+"&uso="+cb_uso;
			
			window.open(urljs+"rptgeneralppu/exportarrpt01"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}

function imprimeReportexUsos(anno,uso,tipo)
{
	var codigo = $('#txtCodigo').val();
	
	//alert(codigo);
	
	//alert(anno+'-'+uso+'-'+tipo+'-'+codigo);
	
	showPopup('rptgeneralppu/reportexusos?anno='+anno+'&uso='+uso+'&tipo='+tipo+'&codigo='+codigo,'#popupreportexuso','1190','400','Reporte Por Uso');
	
}	




