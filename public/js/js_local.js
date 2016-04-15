Ext.onReady(function(){
	Ext.QuickTips.init();
    
 	$("input[type='button']").button();
 	
 	 
    Ext.define('Multas', {
        extend: 'Ext.data.Model',
        fields: [    
			{name: 'id_notif'},
			{name: 'anno_notif'},
			{name: 'codigo'},
			{name: 'n_notif'},
			{name: 't_notif'},
			{name: 'f_notif'},
			{name: 'codigo_area'},
			{name: 'tipo_infraccion'},
			{name: 'codigo_adicional'},
			{name: 'infraccion'},
			{name: 'base_cal'},
			{name: 'factor'},
			{name: 'monto'},
            
        ]
    });
 
    var store = Ext.create('Ext.data.Store', {
        model: 'Multas',
        autoLoad: true,
	    pageSize: 10, 
		height: 700,
        proxy: {
            type: 'ajax',
            url : 'preventiva/consultalocal?codigo='+$('#codigoContri').val(),
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
        title: 'Listado de Preventivas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
			stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
				if(record.get('t_notif')=='A')
				{	
					clsRow = 'xrow-green';
				}	
				else 
					clsRow = 'xrow-blue';
                return clsRow;
				
            },
		},
		columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {    
			text: 'N. Preventiva',
            width: '110px', 
			align:'center',
            dataIndex: 'id_notif'     
        },{         
            text: 'A&ntildeo',
            width: '60px', 
			align:'center',
            dataIndex: 'anno_notif'       
        },{   
        	//text: 'A&ntildeo Multa',
			text: 'Codigo',
        	 width: 45,
			 hidden: true, 
            dataIndex: 'codigo'  
        },{
        	text: 'N. Notificacion',
        	width: '100px',
			align:'center',
			//hidden: true,
            dataIndex: 'n_notif'  
        },{
        	text: 'T. Notif',
        	width: 75,
			hidden: true,
            dataIndex: 't_notif'  
        },{
        	text: 'F. Notificacion',
        	width: '100px',
			align:'center',
            dataIndex: 'f_notif'  
        },{
        	text: 'Cod. Area',
        	width: 85,
			hidden: true,
            dataIndex: 'codigo_area'  
        },{
        	text: 'T. Infrac',
        	width: 55,
			hidden: true,
            dataIndex: 'tipo_infraccion'  
        },{
        	text: 'Cod. Adicional',
        	flex: 1,
			hidden: true,
            dataIndex: 'codigo_adicional'  
        },{
        	text: 'Infraccion',
        	width: '90px',
			align: 'center',
            dataIndex: 'infraccion'  
        },{
        	text: 'Base',
        	width: '80px',
			align: 'center',
            dataIndex: 'base_cal'  
        },{
        	text: 'Factor',
        	width: '80px',
			align: 'center',
            dataIndex: 'factor'  
        },{
        	text: 'Monto',
        	width: '80px',
			align: 'center',
            dataIndex: 'monto'  
        },{ 
            xtype:'actioncolumn',
            width:50,
			align:'right',
            items: [
			{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar MultasAdministrativas',
                handler: function(grid, rowIndex, colIndex) {
            	
                    var rec = grid.getStore().getAt(rowIndex);    
					//alert(rec.get('codigo')+'/'+rec.get('id_notif')+'/'+rec.get('anno_notif')+'/'+$('#tipocontri').val());
                    showPopup('preventiva/notificacion?codigo='+rec.get('codigo')+'&anno_notif='+rec.get('anno_notif')+'&id_notif='+rec.get('id_notif')+'&codtipocontri='+$('#tipocontri').val(),'#popnewmulta','700','500','Editar Preventiva');
					
                }
            }
			// ,
			// {
                // icon: urljs + 'img/printer_rec.png',
                // style: 'cursor: pointer',
                // tooltip: 'Imprimir Preventiva',
                // handler: function(grid, rowIndex, colIndex) {
                    // var rec = grid.getStore().getAt(rowIndex);
					 // var codigo=rec.get('codigo');
					 // var anno_notif=rec.get('anno_notif');
					 // var id_notif=rec.get('id_notif');
					 // var t_notif=rec.get('t_notif');
					 
                    // showPopupReport('schema=&tipo=pdf&nombrereporte=rpt_notificamulta&param=CODIGO^'+codigo+'|ANNO^'+anno_notif+'|ID_NOTIF^'+id_notif+'|T_NOTIF^'+t_notif,'pouprptvias',700,600,'Resolucion de Multas Administrativas');
                // }
            // }
			]
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
