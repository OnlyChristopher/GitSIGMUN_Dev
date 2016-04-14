Ext.onReady(function(){
	Ext.QuickTips.init();
    
 	$("input[type='button']").button();
 	
 	 
    Ext.define('Multas', {
        extend: 'Ext.data.Model',
        fields: [    
			{name: 'numero'},
            {name: 'preimpreso'},
            {name: 'anno_multa'},
            {name: 'numero_multa'},
            {name: 'fecha_multa'},
            {name: 'anno_area'},
            {name: 'infraccion'},
            {name: 'depen_cod'},
            {name: 'monto_multa'},
            {name: 'antecedente'},
            {name: 'numero_notificacion'},
            {name: 'estado'},
            {name: 'numero_recibo'},
            {name: 'cta_cte'},
            {name: 'cantidad'},
            {name: 'codigo_autoridad_municipal'},
            {name: 'base'},
			{name: 'fecha_notificacion'},
			{name: 'e_resolucion'},
        ]
    });
 
    var store = Ext.create('Ext.data.Store', {
        model: 'Multas',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'multasadmin/consulta?codigo='+$('#codigoContri').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridMultas',
        store: store, 
		heigth: 300,
        title: 'Multas Administrativas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
		columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {    
			text: 'num',
            hidden: true,            
            dataIndex: 'numero'     
        },{         
            text: 'Preimp.',
            width: 65,            
            dataIndex: 'preimpreso'       
        },{   
        	//text: 'A&ntildeo Multa',
			text: 'A&ntildeo',
        	 width: 45,
            dataIndex: 'anno_multa'  
        },{
        	text: '# Multa',
        	width: 85,
			//hidden: true,
            dataIndex: 'numero_multa'  
        },{
        	text: 'Fecha',
        	width: 75,
			//hidden: true,
            dataIndex: 'fecha_multa'  
        },{
        	text: 'A&ntildeo &Aacuterea',
        	width: 50,
			hidden: true,
            dataIndex: 'anno_area'  
        },{
        	text: 'Infracci&oacuten',
        	width: 85,
            dataIndex: 'infraccion'  
        },{
        	text: 'Monto',
        	width: 55,
            dataIndex: 'monto_multa'  
        },{
        	text: 'Antecedente',
        	flex: 1,
			hidden: true,
            dataIndex: 'antecedente'  
        },{
        	text: 'Notificaci&oacuten',
        	flex: 1,
			hidden: true,
            dataIndex: 'numero_notificacion'  
        },{
        	text: 'Estado',
        	width: 50,
			align: 'center',
            dataIndex: 'estado'  
        },{
        	text: 'Recibo Antiguo',
        	width: 65,
			hidden: true,
            dataIndex: 'numero_recibo'  
        },{
        	text: 'Cta. Cte.',
        	width: 85,
			hidden: true,
            dataIndex: 'cta_cte'  
        },{
        	text: 'Cantidad',
        	width: 60,
			align: 'center',
            dataIndex: 'cantidad'  
        },{ 
        	text: 'Autoridad Municipal',
        	flex: 1,
			hidden: true,
            dataIndex: 'codigo_autoridad_municipal'  
        },{ 
        	text: 'Base',
        	width: 55,
        	hidden:false,
            dataIndex: 'base'  
        },{ 
			text: 'F. Notificaci&oacuten',
        	widht:50,
        	hidden:false,
            dataIndex: 'fecha_notificacion'  
        },{ 
			text: 'Resolucion',
        	widht:50,
			hidden: true,
            dataIndex: 'e_resolucion'  
        },{ 
            xtype:'actioncolumn',
            width:50,
			align:'right',
            items: [{
				icon: urljs + 'img/accept.png',
				style: 'cursor: pointer',
				tooltip: 'Multa con resolucion',
				/*
				handler: function(grid, rowIndex, colIndex) {
            	
                    var rec = grid.getStore().getAt(rowIndex);    
             
                    showPopup('multasadmin/resolucion?codigo='+$('#codigoContri').val()+'&numero_multa='+rec.get('numero'),'#popnewmulta','600','450','Editar Multas Administrativas');                     
                },*/
				getClass: function(v, meta, rec) {
					if(rec.get('e_resolucion')!='1')
						return 'xrow-hidden-button';
				}
            },{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar MultasAdministrativas',
                handler: function(grid, rowIndex, colIndex) {
            	
                    var rec = grid.getStore().getAt(rowIndex);    
             
                    showPopup('multasadmin/editar?codigo='+$('#codigoContri').val()+'&numero_multa='+rec.get('numero')+'&codtipocontri='+$('#tipocontri').val(),'#popnewmulta','500','450','Editar Multas Administrativas');                     
                }
            },{
				icon: urljs + 'img/delete.png',
				style: 'cursor: pointer',
				tooltip: 'Eliminar Multas Administrativas',
				handler: function(grid, rowIndex, colIndex) {
					var rec = grid.getStore().getAt(rowIndex);                            
					var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
						if(btn=='yes')
							$.ajax({
								type: "GET", 
								url: 'multasadmin/eliminar?codigo='+$('#codigoContri').val(),
								data: 'numero='+rec.get('numero'),
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
	        displayMsg: 'Mostrando {0} - {1} de {2}',
	        emptyMsg: "No se encontraron registros"
	    })
	});
    
    grid.render('gridMultas');	
	   
});

function mostrarRecibos(){
	var codigo=$('#divCodigo').html();
	showPopup('cuentacorriente/mostrarrecibos?codigo='+codigo,'#poplistarec','650','350','Recibos del Contribuyente');
}
