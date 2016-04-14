Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Arancel', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'cod_via'},
            {name: 'anno'},
            {name: 'arancel'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
    	pageSize: 10,
        model: 'Arancel',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantvias/consultarancel?codvia='+$('#codviaescondido').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
    
	var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridArancel',
        store: store,        
        title: 'Arancel',
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'A&ntilde;o',
            width: 50,            
            dataIndex: 'anno'
        },{        
        	text: 'Arancel',
        	flex: 1,
            dataIndex: 'arancel'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Actualizar',
                handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                showPopup('mantvias/editarancel?codvia='+$('#codviaescondido').val()+'&cb_anno='+rec.get('anno')+'&txtarancel='+rec.get('arancel'),'#popEditArancel','250','200','Actualiza de Arancel');
             }          
        },{
            icon: urljs + 'img/delete.png',
            style: 'cursor: pointer',
            tooltip: 'Eliminar Arancel',
            handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
                	if(btn=='yes')
                		$.ajax({
                			type: "GET", 
                			url: 'mantvias/eliminararancel',
                			data: 'anno1='+rec.get('anno')+'&codvia1='+$('#codviaescondido').val(),
                			success: function(data){
                				infoMessage('Eliminado ',data);
                				grid.getStore().loadPage(grid.getStore().currentPage);
                			       				
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
            emptyMsg: "No se encontraron registos"
        })
    });
    
    grid.render('gridArancel');
});

$(function(){	
	//Valida arancel
	$('#frm_arancel').validate({
		rules: {
		'cb_anno': 'required',
		'txtarancel':'required'
		},
		messages: {
		'cb_anno': 'Seleccione el a&ntilde;o',
		'txtarancel':'Ingrese el arancel'
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){
			$.ajax({
				type: "POST",
				url: 'mantvias/guardararancel',
				data: 'codvia='+$('#codviaescondido').val()+'&cb_anno='+$('#cb_anno').val()+'&txtarancel='+$('#txtarancel').val(),		
				success: function(data){
					//alert(data);
					infoMessage('Guardar Arancel',data);
					
					var grid = Ext.getCmp('xgridArancel');
					var store = grid.getStore();
					var proxy = store.getProxy();
					
					var params = proxy.extraParams;
					delete params;
					
					var codvia = $('#codviaescondido').val();
					
					proxy.extraParams = {page: '1', start: '0', limit: '10', codvia: codvia};
					store.loadPage(1);
				}
			});
		}
	});
	
	$('#txtNomPoblado').autotab({format: 'alpha'});
	//$val[] = array('#txtNomPoblado',"{maxlength: 2}","autotab");
	
});

function guardararancel(){
	//alert($('#codviaescondido').val());
	$.ajax({
		type: "POST",
		url: 'mantvias/guardararancel',
		data: 'codvia='+$('#codviaescondido').val()+'&cb_anno='+$('#cb_anno').val()+'&txtarancel='+$('#txtarancel').val(),		
		success: function(data){
			alert(data);
		///////////////pinte al momento de ingresar en el grid
			var grid = Ext.getCmp('xgridArancel');
			var store = grid.getStore();
			var proxy = store.getProxy();
			
			var params = proxy.extraParams;
			delete params;
			
			var codvia = $('#codviaescondido').val();
			
			proxy.extraParams = {page: '1', start: '0', limit: '10', codvia: codvia};
			store.loadPage(1);
			///////////////////////////////
		}
	});
	
}

$('#txtarancel').autotab({format: 'number'});
