Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Vias', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'cod_via'},
            {name: 'nombre_via', type: 'string'},
            {name: 'arancel', type: 'string'}
           
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Vias',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantvias/consulta?codvia='+codvia,
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
  
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridVias',
        store: store,        
        title: 'Detalle de Vias',
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            width: 98,            
            dataIndex: 'cod_via'
        },{        
        	text: 'Nombre Via',
        	flex: 1,
            dataIndex: 'nombre_via'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Editar Via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantvias/editar?codvia='+rec.get('cod_via'),'#popEditVias','300','230','Editar Vias');
                    
                }                
            },{
                icon: urljs + 'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Eliminar via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                    	if(btn=='yes')
                    		$.ajax({
                    			type: "GET", 
                    			url: 'mantvias/eliminar',
                    			data: 'txtcod_via='+rec.get('cod_via'),
                    			success: function(data){
                    				infoMessage('Eliminado ',data);
                    				
                    				grid.getStore().load(grid.getStore().currentPage);                    				
                    			} 
                    		});                    	
                    };
                     confirmMessage('Eliminar','Seguro de Eliminar?',showResult);               
                }
            } ,{
            icon: urljs + 'img/view.png',
            tooltip: 'Arancel',
            handler: function(grid, rowIndex, colIndex) {
                var rec1 = grid.getStore().getAt(rowIndex);
                //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                showPopup('mantvias/arancel?codvia='+rec1.get('cod_via'),'#popViasArancel','400','400','Detalle de Arancel');
             }          
        }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} of {2}',
            emptyMsg: "No se encontraron registos"
        })
    });
    
    grid.render('gridVias');
    
  //coloca los ceros que desees
    $('#txtcuadra').live('blur',function(){
        var numCeros = '00'; // pon el nº de ceros que necesites
        var valor =  $('#txtcuadra').val().trim();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
      	    $('#txtcuadra').val(valor2);  //
      	    $('#txtnombre').focus(); 
           
    });

    
});
