Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('Reportefrac', {
        extend: 'Ext.data.Model',
        fields: [
                 {name: 'codigo', type: 'string'},
                 {name: 'anno', type: 'string'},
                 {name: 'convenio', type: 'string'},
                 {name: 'estado', type: 'string'},
                 {name: 'fecha', type: 'string'},
                 {name: 'deuda_ini', type: 'string'},
                 {name: 'cuotas', type: 'string'},
				 {name: 'cuotas_canceladas', type: 'string'},
				 {name: 'cuotas_vencidas', type: 'string'},
				 {name: 'operador', type: 'string'}
             ]
    });
    
    var recibosStore = Ext.create('Ext.data.Store', {
    	model: 'Reportefrac',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'fraccionar/reporteconsulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridReportefrac',
        store: recibosStore,
        title: 'Fraccionamiento Realizados',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            flex: 1,
			hidden:true,
            dataIndex: 'codigo'
        },{
            text: 'A&ntilde;o',
            flex: 1,
            //renderer : change(),
            dataIndex: 'anno'
        },{        
        	text: 'Convenio',
            flex: 1,
            dataIndex: 'convenio'
        },{
            text: 'Estado',
			flex: 1,
            dataIndex: 'estado'
        },{
            text: 'Fecha',
            flex: 1,
            dataIndex: 'fecha'            
        },{
            text: 'Deuda Ini.',
            flex: 1,
            dataIndex: 'deuda_ini'
        },{
            text: 'Cuotas',
            flex: 1,
            dataIndex: 'cuotas'
        },{
            text: 'Cuot. Canceladas',
			flex: 1,
            dataIndex: 'cuotas_canceladas'
        },{
            text: 'Cuot. Vencidas',
			flex: 1,
            dataIndex: 'cuotas_vencidas'
        },{
            text: 'Usuario',
			flex: 1,
            dataIndex: 'operador'
        }/*,{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs +'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Anular Recibo',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    var showResult = function(btn){
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "POST", 
                    			url: urljs + 'tesocaja/aceptarrecibo',
                    			data: 'txtRecibo='+rec.get('recibo'),
                    			success: function(data){
                    				infoMessage('Eliminando',data);
                    				grid.getStore().load(grid.getStore().currentPage);                    				
                    			} 
                    		});                    	
                    };
                    confirmMessage('Eliminar','Seguro de Anular Recibo '+rec.get('recibo')+'?',showResult);
                }
            }]
        }*/]
    });
    
    grid.render('gridReportefracc');
 
});

function buscarListadofracc(){
	
	var grid = Ext.getCmp('xgridReportefrac');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var frdesde= $('#fracdesde').val();
	var frhasta= $('#frachasta').val();
	var frcmb= $('#cmbUsuario option:selected').html();
	
	proxy.extraParams = {desde: frdesde, hasta: frhasta,cbusuario:frcmb};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			var total_sum=0;
        	store.each(function (rec) { 
			
			});
		}
	});	
}
function generarpdf(){
	//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
var frdesde= $('#fracdesde').val();
	var frhasta= $('#frachasta').val();
	var frcmb= $('#cmbUsuario option:selected').html();
	
	showPopupReportHtml('fraccionar/htmlreporte?desde='+frdesde+'&hasta='+frhasta+'&cbusuario='+frcmb,'reportefracc','Reporte de Fraccionamientos Emitidos');
}