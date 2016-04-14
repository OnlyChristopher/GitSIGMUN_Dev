Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('Reporte', {
        extend: 'Ext.data.Model',
        fields: [
                 {name: 'codigo', type: 'string'},
                 {name: 'nombre', type: 'string'},
                 {name: 'anno', type: 'string'},
                 {name: 'cod_pred', type: 'string'},
                 {name: 'anexo', type: 'string'},
                 {name: 'sub_anexo', type: 'string'},
                 {name: 'uso_predio', type: 'string'},
				 {name: 'direccion', type: 'string'},
				 {name: 'area_terreno', type: 'string'},
				 {name: 'area_construccion', type: 'string'},
				 {name: 'sector', type: 'string'},
				 {name: 'insoluto_ip', type: 'string'},
				 {name: 'insoluto_arb', type: 'string'}
             ]
    });
    
    var recibosStore = Ext.create('Ext.data.Store', {
    	model: 'Reporte',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'recaudacionreportes/recibos',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridReporte',
        store: recibosStore,
		height: '480px',
        title: 'Parte Diario',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            flex: 1,
            dataIndex: 'codigo'
        },{
            text: 'Nombre',
            flex: 1,
            dataIndex: 'nombre'
        },{        
        	text: 'A&ntilde;o',
            flex: 1,
            dataIndex: 'anno'
        },{
            text: 'Cod. Pred.',
            flex: 1,
            dataIndex: 'cod_pred'
        },{
            text: 'Anexo',
            width: 70,
            dataIndex: 'anexo'            
        },{
            text: 'Sub Anex.',
            flex: 1,
            dataIndex: 'sub_anexo'
        },{
            text: 'Uso',
            width: 100,
            align:'right',
            dataIndex: 'uso_predio'
        },{
            text: 'Direccion',
            width: 70,
            dataIndex: 'direccion'
        },{
            text: 'Area Ter.',
            width: 70,
            dataIndex: 'area_terreno'
        },{
            text: 'Area Contr.',
            width: 70,
            dataIndex: 'area_construccion'
        },{
            text: 'Sector',
            width: 70,
            dataIndex: 'sector'
        },{
            text: 'Deuda IP',
            width: 70,
            dataIndex: 'insoluto_ip'
        },{
            text: 'Deuda Arb.',
            width: 70,
            dataIndex: 'insoluto_arb'
        }]
    });
    
    grid.render('gridDeclaracion');
 
});


function buscarRecibos(){
	
	var grid = Ext.getCmp('xgridReporte');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	/*
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	*/
	var cmbanno= $('#cmbanno').val();
	var cmbuso= $('#cmbuso').val();

	
	proxy.extraParams = {cmbanno: cmbanno, cmbuso: cmbuso};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
		/*	var total_sum=0;
        	store.each(function (rec) { 
				//alert(rec.get('monto')); 
				total_sum += Number(rec.get('monto'));				
			});
			$('#txttotal').val(Math.round(total_sum*100)/100).autoNumeric();
			//$('#txttotal').autoNumeric();
			*/
		}
	});	
}
/*
function actualizarContri(){
	var grid = Ext.getCmp('xgridContri');
	var store = grid.getStore();
	store.reload();
}
*/

function generarpdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	//showPopupReport('tipo=pdf&nombrereporte=rpt_emitidos&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta+'|CAJERO^'+cmbcajas+'|USUARIO^'+userjs,'pouprptemitido',700,600,'Recibos emitidos');
	showPopupReportHtml('tesoreportes/reporterecibosemitidos?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas,'pouprptemitidos','Reporte de Recibos Emitidos');
}

function generarpdfParidas(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
	var tesodesde=$('#tesodesde').val();
	var tesohasta=$('#tesohasta').val();
	var cmbcajas= $('#cmbcajas').val();
	//alert(userjs);
	
	//showPopupReport('tipo=pdf&nombrereporte=ReportePartidas&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta+'|CAJERO^'+cmbcajas+'|USUARIO^'+userjs,'pouprptemitido',700,600,'Recibos emitidos');
	showPopupReportHtml('tesoreportes/reportexpartida?desde='+tesodesde+'&hasta='+tesohasta+'&cajero='+cmbcajas,'pouprptpartidas','Reporte de Partidas');
}

function exportarInafectos(){
	
	var showResult = function(btn){
		if(btn=='yes'){
			data = "?cmbanno="+$('#cmbanno').val()+"&cmbuso="+$('#cmbuso').val();
					
			window.open(urljs+"recaudacionreportes/exporta"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}

