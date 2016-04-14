Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('BusGiro', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idgiro'},
            {name: 'descrip', type: 'string'}
      ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'BusGiro',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantgiro/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBusGiro',
        store: store,        
        title: 'Giro',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'idgiro'
        },{        
        	text: 'Descripcion',
        	flex: 1,
            dataIndex: 'descrip'
        },{
            xtype:'actioncolumn',
            width:40,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Giro',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('mantgiro/formu?idgiro='+rec.get('idgiro')+'&readonly=readonly','#popNgiro','600','400','Editar Giro');
                }},{
                icon: urljs + 'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Anular Giro',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);                    
                }
        
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuscaGiro',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; giro"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
                closePopup('#popBusGiro');
                window.parent.muestraDatosGi(record);//Captura Valores
            }
        }
    });
    
    grid.render('gridBusGiro');
    
    $('#txtCritBusGiro').keypress(function(e) {
        if(e.which == 13) {
        	buscarGiro();
        }
    });
    $('#txtCritBusGiro').focus();
});

function buscarGiro(){
	var grid = Ext.getCmp('xgridBusGiro');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCritBusGiro = $('input[name*="rdCritBusGiro"]:checked').val();
	var txtCritBusGiro = $('#txtCritBusGiro').val();

    proxy.extraParams = {rdCritBusGiro: rdCritBusGiro, txtCritBusGiro: txtCritBusGiro};
    store.load({
        callback: function(documents, options, success){}

    });
}


/*
 reload ();
function actualizarContri(){
	var grid = Ext.getCmp('xgridContri');
	var store = grid.getStore();
	store.reload();
}
*/