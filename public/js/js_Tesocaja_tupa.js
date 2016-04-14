Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
    
	Ext.define('TesoTupa', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'partida'},
            {name: 'nombres'},
			{name: 'tiporec'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'TesoTupa',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'tesocaja/consultatupa',
            reader: {
               type: 'json',
               root: 'rows'
            },
			actionMethods: {
                create : 'POST',
                read   : 'POST',
                update : 'POST',
                destroy: 'POST'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridTesoTupa',
        store: store,
		height: '200px',
        title: 'Conceptos',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        //Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Partida',
            width: 110,            
            dataIndex: 'partida'
        },{        
        	text: 'Item',
        	flex: 1,
            dataIndex: 'nombres'
        },{        
        	text: 'codigo',
        	hidden:	true,
            dataIndex: 'tiporec'
        },{
            xtype:'actioncolumn',
            width:82,
            items: [{
                icon: urljs + 'img/view.png',
                tooltip: 'Pagos Por Tupa',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					
					var params={
						tiporec: rec.get('tiporec')
					};
					
					goToInterno('tesocaja/tupadatconcep',params,'Tesorer&iacutea Municipal - Pagos TUPA');
                }
            }]
        }],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
				//var rec = grid.getStore().getAt(rowIndex);
				
				var params={
					tiporec: record.get('tiporec')
				};
				
				goToInterno('tesocaja/tupadatconcep',params,'Tesorer�a Municipal - Pagos TUPA');
            }
		}
    });
    
    grid.render('gridTesoTupa');
	
	$('#txtpalabra').keypress(function(e){
		if(e.which == 13) {
			mostrarTupa();
		}
	});

	$('#txtpartidas').keypress(function(e){
		if(e.which == 13) {
			mostrarTupa();
		}
	});

    $('#cmbCC').change(function(){
        mostrarTupa()
    })
});
	
function mostrarTupa(){

	var txtpalabra=$('#txtpalabra').val();	
	var txtpartidas=$('#txtpartidas').val();
    var cmbcc = $('#cmbCC').val();
	var grid = Ext.getCmp('xgridTesoTupa');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	var params = proxy.extraParams;
	delete params;

	proxy.extraParams = {txtpalabra: txtpalabra,txtpartidas:txtpartidas,cmbcc:cmbcc};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}