Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Buscar', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo', type: 'string'},
            {name: 'codzona', type: 'string'},
            {name: 'nomzona', type: 'string'},
            {name: 'codurba', type: 'string'},
            {name: 'nomurba', type: 'string'},
            {name: 'nomvia', type: 'string'},
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Buscar',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'cartareq/consultabusvia?anno='+$("#hAnno").val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBuscar',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando ...'}
    	},
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            flex: 1,            
            dataIndex: 'codigo'
        },{        
        	text: 'CodZona',
        	flex: 1,
            dataIndex: 'codzona',
			hidden: true
        },{
            text: 'Zona',
            flex: 1,
            dataIndex: 'nomzona'            
        },{
            text: 'Codurba',
            flex: 1,
            dataIndex: 'codurba',
			hidden: true
        },{
            text: 'Urbanizaci&oacute;n',
            flex: 1,
            dataIndex: 'nomurba'            
        },{
            text: 'Via',
            flex: 1,
            dataIndex: 'nomvia'            
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuscar',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Datos"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                $("#hdCodigoVia").val(record.get("codigo"));
                $("#txtCodigoVia").val(record.get("codigo"));
                $("#txtNomVia").val(record.get("nomvia"));
                closePopup('#popupbusvia');
            }
        } 
        
    });
    
    grid.render('gridBuscar');
    
    $('#txt_busvia').keypress(function(e) {
        if(e.which == 13) {
        	buscarDatos();
        }
    }); 
    
    $('#txt_busvia').focus();
    
});

function buscarDatos(){
	var grid = Ext.getCmp('xgridBuscar');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;

	var txtCriterio = $('#txt_busvia').val();

	proxy.extraParams = {criterio: txtCriterio};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}
