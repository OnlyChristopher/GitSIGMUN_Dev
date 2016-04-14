Ext.onReady(function(){
    Ext.QuickTips.init();

	$("input[type='button']").button();
	
	Ext.define('PerfilObj', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_acceso'},
            {name: 'nombre'},
			{name: 'id_objeto'}
        ]
    });
    
 	var store = Ext.create('Ext.data.Store', {
    	model: 'PerfilObj',
    	autoLoad: true,    	
        proxy: {
            type: 'ajax',
            url : 'mantperfil/conobject?perfil='+$('#hdnPerfAccess').val()+'&pantalla='+$('#hdnPantAccess').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPerfilObj',
        store: store,
        height: '300px',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
        	listeners: {        		
                beforerefresh: function() {
        			//alert('antes refresh');
        			var ind=0;
        			var str=cargaChecks($('#hdnPerfAccess').val());
                    this.store.each(function(record) {
                    	if(str.indexOf(record.get('id_acceso')) != -1)
                    		Ext.getCmp('xgridPerfilObj').getSelectionModel().select(ind,true,false);
                    	ind++;
                    });
                }
            }
    	},
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true,
			showHeaderCheckbox :false
        }),
        columns: [{
            text: 'Acceso',
            width: 60,         
            dataIndex: 'id_acceso'
            
        },{        
        	text: 'M&oacute;dulo',
        	flex: 1,
            dataIndex: 'nombre'
        },{        
        	text: 'Objeto',
        	width: 120,
            dataIndex: 'id_objeto'
        }],
        listeners : {
    		itemclick: function(dv, record, item, index, e) {
    			var bacceso = 0;
				
				if (e.getTarget('.x-grid-row-selected'))
					bacceso = 1;
				
    			var idacceso = record.get('id_acceso');				
    			var idperfil = $("#hdnPerfAccess").val();

    			var datos={
					idacceso: idacceso,
					idperfil: idperfil,
					bacceso: bacceso
    			};
    			
    			$.ajax( {
    				type : "POST",
    				url : "mantperfil/gacceso",
    				data : datos,
    				success : function(response) {
    				}
    			});
    			
    		}
    	}
    });
    
    grid.render('gridPerfilObj');
	
});