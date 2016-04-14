Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('BusPred', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'cod_pre'},
            {name: 'nombre_via', type: 'string'},
            {name: 'id_via', type: 'string'},
            {name: 'nombabr', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'BusPred',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantpred/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBusPred',
        store: store,        
        title: 'Personas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'cod_pre'
        },{        
        	text: 'Descripcion del Predio',
        	flex: 1,
            dataIndex: 'nombre_via'
        },{
            text: 'Cod. Via',
            width: 100,
            dataIndex: 'id_via'            
        },{
            text: 'Zona',
            width: 100,
            dataIndex: 'nombabr'
        },{
            xtype:'actioncolumn',
            width:20,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Contribuyente',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('mantcontri/formu?codigo='+rec.get('codigo'),'#popcontri','600','450','Editar Contrinuyente');
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuscaPers',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
                closePopup('#popBusPre');
                window.parent.muestraDatosPre(record);//Captura Valores
            }
        }
    });
    
    grid.render('gridBusPre');
    
    $('#txtCritBusPers').keypress(function(e) {
        if(e.which == 13) {
        	buscarPers();
        }
    });
    
    $('#txtCritBusPers').focus();
});

function buscarPers(){
	var grid = Ext.getCmp('xgridBusPers');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCritBusPers"]:checked').val();
	var txtCriterio = $('#txtCritBusPers').val();
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio};
	store.loadPage(1); 
}