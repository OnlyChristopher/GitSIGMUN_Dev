Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('vehiculosModel',{
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idx', type: 'string'},
			{name: 'idvehiculo', type: 'string'},
			{name: 'idtransporte', type: 'string'},
			{name: 'padron', type: 'string'},
			{name: 'placa', type: 'string'},
			{name: 'propietario', type: 'string'},
			{name: 'empresa', type: 'string'}
        ]
    });
    
    var vehiculosStore = Ext.create('Ext.data.Store', {
    	model: 'vehiculosModel',
    	//autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'transporte/buscarvehiculos',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridVehiculos',
        store: vehiculosStore,
		height: '285px',
        title: 'Vehiculos',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
		},
        columns:[
        Ext.create('Ext.grid.RowNumberer'),
        {
            text: 'Registro',
            width: 70,
            dataIndex: 'idvehiculo'
        },{
            text: 'Placa',
            width: 70,            
            dataIndex: 'placa'
        },{        
        	text: 'Padr&oacute;n',
        	width: 70,
            dataIndex: 'padron'
        },{
            text: 'Propietario',
			flex: 1,
            dataIndex: 'propietario'
        },{
            text: 'Empresa',
			flex: 1,
            dataIndex: 'empresa'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs +'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Vehiculo',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('transporte/index?idvehiculo='+rec.get('idvehiculo'),'#popvehiculo','800','600','Editar Vehiculo');
                }
            }]
        }]
    });
    
    grid.render('divgridVehiculos');

});

function buscarVehiculos(){
	var grid = Ext.getCmp('xgridVehiculos');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {
		cmbTipos: $('#cmbTipos').val(),txtDatos: $('#txtDatos').val()
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}
