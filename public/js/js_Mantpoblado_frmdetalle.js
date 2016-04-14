Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Vias', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
			{name: 'codvia'},
            {name: 'anno', type: 'string'},
            {name: 'arancel', type: 'string'},
			 {name: 'estado', type: 'string'}
           
        ]
    });
    //alert($('#codigo').val());
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Vias',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantpoblado/consultadetalle?codigovia='+$('#codigo').val(),
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
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            width: 98,            
            dataIndex: 'codigo'
        },{
            text: 'Cod Via',
            width: 150,            
            dataIndex: 'codvia'
        },{        
        	text: 'Ano',
        	width: 100,
            dataIndex: 'anno'
        },{        
        	text: 'Arancel',
        	width: 100,
            dataIndex: 'arancel'
        },{        
        	text: 'Estado',
        	width: 100,
            dataIndex: 'estado'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Editar Via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantpoblado/frmarancel?codigo='+rec.get('codigo'),'#popEditArancel','210','150','Editar arancel');
                    
                }                
            }/*,{
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
            }*/]
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



function CodDetalle(){
	showPopup('mantpoblado/frmarancel?codigo_via='+$('#codigo').val(),'#popEditArancel','210','150','Editar Arancel');
}