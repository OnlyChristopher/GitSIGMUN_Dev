Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	
    Ext.define('Bandeja', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idrecibo'},
            {name: 'expediente', type: 'string'},
            {name: 'monto', type: 'string'},
			{name: 'estadocosta', type: 'string'},
			{name: 'estadogasto', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Bandeja',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'bandecostascontri/consultabandeja?codigo='+$('#divCodigo').html(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
   // xgridContri
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBandeja',
        store: store,   
        title: '',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Recibo',
            width: 60,            
            dataIndex: 'idrecibo',
			hidden:true
        },{        
        	text: 'Expediente',
        	flex: 1,
            dataIndex: 'expediente'
        },{
            text: 'Monto',
            width: 120,
            dataIndex: 'monto'
        },{
            text: 'estadocosta',
            width: 120,
            dataIndex: 'estadocosta',
			hidden:true
        },{
            text: 'estadogasto',
            width: 120,
            dataIndex: 'estadogasto',
			hidden:true
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar',
                handler: function(grid, rowIndex, colIndex) {  //+'&estado='+rec.get('expediente')
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('bandecostascontri/costas?codigo='+$('#divCodigo').html()+'&expediente='+rec.get('expediente')+'&estadocosta='+rec.get('estadocosta')+'&estadogasto='+rec.get('estadogasto'),'#popCostacontri','900','500','Costas Contribuyente');
					
					//alert(rec.get('expediente'));
                }
            },
			{
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);                    
                    
                    var showResult = function(btn){
            			if(btn=='yes')  //si es 'yes' llamo al ajax
            				
            				$.ajax({
                        		type: "GET",
                        		url: 'bandecostascontri/eliminarbandeja',
                        		data: 'expediente='+rec.get('expediente')+'&codigo='+$('#divCodigo').html(),                        		
                        		success: function(data){
                        			infoMessage('Eliminado',data);
                        			
                					Ext.getCmp('xgridBandeja').getStore().load();
                        		}
                        	});	
            		};
            		
            		confirmMessage('Eliminar','Seguro de eliminar el recibo ?',showResult);
                }                
            }]
        }]
    });
    
    grid.render('gridBandeja');
	

	
   
});


function consulta(){


//showPopup('bandecostascontri/deuda?codigo='+$('#txtcodigo').val(),'#popDeuda','300','120','Ingrese Deuda');
showPopup('bandecostascontri/costas?codigo='+$('#divCodigo').html(),'#popCostacontri','900','500','Costas');
}

		