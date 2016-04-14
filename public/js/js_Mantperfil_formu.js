Ext.onReady(function(){
    Ext.QuickTips.init();

	$("input[type='button']").button();
	
	$('#frmperfil').validate( {
		rules : {
			'txtNomPerfil' : 'required'
		},
		messages : {
			'txtNomPerfil' : 'Debe ingresar la descripci&oacute;n'
		},
		debug : true,
		errorElement : 'div',
		submitHandler : function(form) {
			$.ajax( {
				type : "POST",
				url : "mantperfil/grabar",
				data : $(form).serializeObject(),
				success : function(response) {
					
					$("#txtIdPerfil").val(response);
					
					var perfil = $("#txtIdPerfil").val().trim();
					$("#gridPerfilMod").html('');
					$("#gridPerfilAcc").html('');
					
					if(perfil!=''){
						cargaGridModulos(perfil);
						cargaGridAccesos(perfil);
						infoMessage('Guardar Perfil', 'Datos guardados correctamente!');
					}
					
					Ext.getCmp('xgridPerfil').getStore().load();					
				}
			});
		}
	});

	//Todo a mayúscula
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
	//Aca carga las grillas
	var perfil = $("#txtIdPerfil").val().trim();
	
	cargaGridModulos(perfil);
	cargaGridAccesos(perfil);
	
});

//----------- CARGAR-GRIDMODULOS---------------------------
function cargaGridModulos(perfil){
	 Ext.define('PerfilMod', {
	        extend: 'Ext.data.Model',
	        fields: [
	            {name: 'id_acceso'},
	            {name: 'nombre'}
	        ]
	    });
	    
	 	var store = Ext.create('Ext.data.Store', {
	    	model: 'PerfilMod',
	    	autoLoad: true,    	
	        proxy: {
	            type: 'ajax',
	            url : 'mantperfil/conmodulos?perfil='+perfil,
	            reader: {
	               type: 'json',
	               root: 'rows'
	            }
	        }
	    });
	    
	    var grid = Ext.create('Ext.grid.Panel', {
	    	id: 'xgridPerfilMod',
	        store: store,        
	        title: 'M&oacute;dulos',
	        height: '240px',
			viewConfig: { 
				loadMask: {msg: 'Cargando...'}
			},
	        columns: [ 
	            Ext.create('Ext.grid.RowNumberer'),    
	        {
	            text: 'Acceso',
	            width: 100,            
	            dataIndex: 'id_acceso'
	        },{        
	        	text: 'M&oacute;dulo',
	        	flex: 1,
	            dataIndex: 'nombre'
	        }],
	        //----------------------------------  funcion de click ----------------------------------------
	        listeners : {
	    		itemclick: function(dv, record, item, index, e) {
	    		
	    			//alert(record.get('id_acceso'));
	    			var grid = Ext.getCmp('xgridPerfilAcc');
	    			var store = grid.getStore();
	    			var proxy = store.getProxy();
	    			
	    			var params = proxy.extraParams;
	    			delete params;
	    			
	    			proxy.extraParams = {id_acceso: record.get('id_acceso')};
	    			store.loadPage(1); 
	    		}
	    	}
	   //----------------------------------------------------------------------------------------------------------            
	    });
	    
	    grid.render('gridPerfilMod');
}

//----------- CARGAR-GRIDACCESO---------------------------
function cargaGridAccesos(perfil){
	Ext.define('PerfilAcc', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'id_acceso'},
            {name: 'nombre'}
        ]
    });
    
 	var store = Ext.create('Ext.data.Store', {
    	model: 'PerfilAcc',
    	autoLoad: true,    	
        proxy: {
            type: 'ajax',
            url : 'mantperfil/conacceso?perfil='+perfil,
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPerfilAcc',
        store: store,        
        title: 'Accesos',
        height: '240px',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
        	listeners: {        		
                beforerefresh: function() {
        			//alert('antes refresh');
        			var ind=0;
        			var str=cargaChecks($('#txtIdPerfil').val());
                    this.store.each(function(record) {
                    	if(str.indexOf(record.get('id_acceso')) != -1)
                    		Ext.getCmp('xgridPerfilAcc').getSelectionModel().select(ind,true,false);
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
            xtype:'actioncolumn',
            width:20,
            items: [{
                icon: urljs + 'img/view.png',
                tooltip: 'Objetos',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					var flag = 0;
					
					var selectedRecordsArray = Ext.getCmp('xgridPerfilAcc').getView().getSelectionModel().getSelection();
					Ext.each(selectedRecordsArray, function (item) {
						if(rec.get('id_acceso')==item.data.id_acceso)
							flag = 1;
					});
					
                    if(flag){
						showPopup('mantperfil/object?idPerfil='+$('#txtIdPerfil').val()+'&idPantalla='+rec.get('id_acceso'),'#popMantPerfilObj','500','400','Accesos Perfil');
					}
					else{
						infoMessage('Accesos Perfil','Debe seleccionar el m&oacute;dulo primero!');
					}
                }                
            }]
        }],
        listeners : {
    		itemclick: function(dv, record, item, index, e) {
    			var bacceso = 0;
				
				if (e.getTarget('.x-grid-row-selected'))
					bacceso = 1;
				
    			var idacceso = record.get('id_acceso');				
    			var idperfil = $("#txtIdPerfil").val().trim();

    			var datos={
					idacceso: idacceso,
					idperfil: idperfil,
					bacceso: bacceso
    			};
    			
    			$.ajax( {
    				type : "POST",
    				url : "mantperfil/gacceso",
    				data : datos,
    				success : function(data) {
    				}
    			});
    			
    		}
    	}
    });
    
    grid.render('gridPerfilAcc');
    
}

function cargaChecks(id_perfil){
	var str="";
	$.ajax({
		type: "POST",     
		url: urljs + "mantperfil/cargachecks",
		data: { 'id_perfil':id_perfil },
		success: function(data) { 
    		str = data;
		},
		async: false
	});
	//alert(str);
	return str;
}
	
