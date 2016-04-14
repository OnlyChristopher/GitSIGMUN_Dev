Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('conductorModel',{
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idx', type: 'string'},
			{name: 'conductor', type: 'string'},
			{name: 'licencia', type: 'string'},
			{name: 'desde', type: 'string'},
			{name: 'hasta', type: 'string'}
        ]
    });
    
    var conductorStore = Ext.create('Ext.data.Store', {
    	model: 'conductorModel',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'transporte/buscarconductores?txtcid='+$('#txtcid').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridConductor',
        store: conductorStore,
		height: '350px',
        title: 'Conductor',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
		},
        columns:[
        Ext.create('Ext.grid.RowNumberer'),
        {
            text: 'Id',
            width: 70,
            dataIndex: 'idx'
        },{
            text: 'Conductor',
            flex: 1,            
            dataIndex: 'conductor'
        },{        
        	text: 'Licencia',
        	width: 70,
            dataIndex: 'licencia'
        },{
            text: 'Desde',
        	width: 70,
            dataIndex: 'desde'
        },{
            text: 'Hasta',
        	width: 70,
            dataIndex: 'hasta'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs +'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Vehiculo',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					showPopup('transporte/mantenimientoconductores?txtidx='+rec.get('idx'),'#popConductores','700','210','Editar Conductor');
                }
            }]
        }]
    });
    
    grid.render('divgridConductores');

});

function buscarConductores(){
	var grid = Ext.getCmp('xgridConductor');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {
		txtcid: $('#txtcid').val()
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}
