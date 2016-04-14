Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Valor UIT', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'anno',		    type: 'string'},
            {name: 'tipo', 			type: 'string'},
            {name: 'valor_uit', 	type: 'string'},
            {name: 'imp_min',   	type: 'string'},
            {name: 'imp_max',       type: 'string'},
            {name: 'costo_emision', type: 'string'},
            {name: 'costo_adicional',type:'string'}
           
        ]
    });
    ////?anno='+anno
    var store = Ext.create('Ext.data.ArrayStore', {
    	pageSize: 10,
        model: 'Valor UIT',
        autoLoad: true,	    	
        proxy: {
            type: 'ajax',
            url : 'mantuit/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
    /*
    var id_urba=$('#codigo').val;
    var id_urba=$('#zona').val;
    */
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridUIT',
        store: store,        
        title: 'Editar valor UIT',
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Año',
            width: 50,            
            dataIndex: 'anno'
        },{        
        	text: 'Tipo',        	
        	width: 90,        	
            dataIndex: 'tipo'
        },{        
        	text: 'Valor UIT',
        	width: 75,        	
            dataIndex: 'valor_uit'
        },{        
        	text: 'Imp. Min.',
        	width: 90,        	
            dataIndex: 'imp_min'
        },{        
        	text: 'Imp. Max.',
        	width: 90,        	
            dataIndex: 'imp_max'
        },{        
        	text: 'Costo Emision',
        	width: 90,        	
            dataIndex: 'costo_emision'
        },{        
        	text: 'Costo Adicional',
        	width: 90,        	
            dataIndex: 'costo_adicional'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Editar Valor UIT',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));&nom='+_x+'
/////////////////////////////////////nombre variable/////nombre grilla///
                    showPopup('mantuit/editar?anno='+rec.get('anno'),'#popupuit','300','300','Editar Valor UIT');
                    
                }                
            },{
                icon: urljs +'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Eliminar Valor UIT',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    //showPopup('mantvias/eliminar?actionContri=E&codigo='+rec.get('cod_via'),'#popup','600','450','Editar Contrinuyente');
                    var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantuit/eliminar',
                    			data: 'cb_anno='+rec.get('anno'),
                    			success: function(data){
                    				infoMessage('Eliminado ',data);
                    				
                    				grid.getStore().load(grid.getStore().currentPage);                    				
                    			} 
                    		});                    	
                    };
                     confirmMessage('Eliminar','Seguro de Eliminar?',showResult);               
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} of {2}',
            emptyMsg: "No se encontraron registros"
        })
    });
    
    grid.render('gridUIT');
});

function buscarUIT(){
	var grid = Ext.getCmp('xgridUIT');
	var store = grid.getStore();//carga la grilla
	var proxy = store.getProxy();//actualiza la grilla
	
	var params = proxy.extraParams;//busca las paginas de la grilla
	delete params;
	
	var cb_anno = $('#cb_anno').val();
		
	proxy.extraParams = {cb_anno: cb_anno};

	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
	
}

