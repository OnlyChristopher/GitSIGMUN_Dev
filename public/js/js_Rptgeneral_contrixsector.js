Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	$('#txtCodigo').focus();
});



$('#cb_distrito').change(function(){
    var id_distrito=($(this).val());

		$.ajax({     
		type: "POST",     
		url: "rptgeneralcps/muestrasector",
		data: 'id_distrito='+id_distrito,
		success: function(data) { 
			$('#cb_Sector').html(data);		
			}
		});

    
});



$("#btnProcesarContri").click(function() {
	
	Procesar_RPT_Contri_x_Sector();
	
});

function Procesar_RPT_Contri_x_Sector()
{
	 //alert('Procesar');
	 codigo=$('#txtCodigo').val();
	 anio=$('#cb_anno').val();
	 distrito=$('#cb_distrito').val();
	 sector=$('#cb_Sector').val();
	 
	 data=[codigo,anio,distrito,sector];
	 
	 callAjax('Rptgeneralcps/reporte03',{json:JSON.stringify(data)},'#ReporteContriXSector','html')

	 
}

$('#txtCodigo').keypress(function(e) {
        if(e.which == 13) {
        	Procesar_RPT_Predio_x_Uso();
        }
    });
	
	
	function exportarContrixsector()
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
			var cb_distrito = $('#cb_distrito').val();
			var cb_Sector = $('#cb_Sector').val();
			
		
	
			
			
			
			//data = "?suministro="+txtSuministro++"&area="+cmbArea+"&req_fech1="+txtFec1+"&req_fech2="+txtFec2;
			data = "?codigo="+txtCodigo+"&anno="+cb_anno+"&distrito="+cb_distrito+"&sector="+cb_Sector;
			
			window.open(urljs+"rptgeneralcps/exportarrpt04"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}

function imprimeReporteContriDist(id_dist,anio){
	
	//alert(id_dist+'/'+anio);
	
	showPopup('rptgeneralcps/reportedetcontri?id_dist='+id_dist+'&anio='+anio,'#popupreportexuso','1190','400','Reporte Por Distrito');
}

function imprimeReporteContriSec(id_dist,id_sector,id_anio)
{
	//alert(id_dist+'/'+id_sector+'/'+id_anio);
	
	showPopup('rptgeneralcps/reportedetcontrisec?id_dist='+id_dist+'&id_sector='+id_sector+'&id_anio='+id_anio,'#popupreportexcontri','1190','400','Reporte Por Sector');
}
	
	
