Ext.onReady(function(){
	Ext.QuickTips.init();
	
 	$("input[type='button']").button();
 	
 	
    Ext.define('Multas', {
        extend: 'Ext.data.Model',
        fields: [    
			{name: 'anno'},
            {name: 'cod_pred'},
            {name: 'direccion'},
			{name: 'glosa'},
            {name: 'tipo_rec'},
            {name: 'motivo'},
            {name: 'insoluto'},
            {name: 'interes'},
            {name: 'f_ingreso'},
            {name: 'f_vigencia'},
			{name: 'condicion'},
			{name: 'estado'},
			{name: 'num_docu'},
			{name: 'ubica'},
            
        ]
    });
 
    var store = Ext.create('Ext.data.Store', {
        model: 'Multas',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'multastributarias/consultatributaria?codigo='+$('#codigoContri').val(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridMultasTributarias',
        store: store, 
		heigth: 300,
        title: 'Multas Tributarias',
           viewConfig: {
        	loadMask: {msg: 'cargando...'},
			stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
				if(record.get('estado')=='1')
				{	
				if(record.get('condicion')=='S')
					clsRow = 'xrow-green';
				
				if(record.get('condicion')=='N')
					clsRow = 'xrow-blue';
				}	
				else 
					clsRow = 'xrow-red';
                return clsRow;
				
            },
    	},
		columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {    
			text: 'Anno',
            width: 65,             
            dataIndex: 'anno'     
        },{         
            text: 'Cod. Predio',
            width: 90,            
            dataIndex: 'cod_pred'       
        },{   
        	text: 'Direccion',
        	 width: 220,
			 hidden:true,
            dataIndex: 'direccion'  
        },{   
        	text: 'Glosa',
        	 width: 360,
            dataIndex: 'glosa'  
        },{
        	text: 'T. Rec',
        	width: 50,
			hidden:true,
            dataIndex: 'tipo_rec'  
        },{
        	text: 'Motivo',
        	width: 55,
            dataIndex: 'motivo'  
        },{
        	text: 'Insoluto',
        	width: 80,
            dataIndex: 'insoluto'  
        },{
        	text: 'Interes',
        	width: 80,
            dataIndex: 'interes'  
        },{
        	text: 'F. Ingreso',
        	width: 75,
            dataIndex: 'f_ingreso'  
        },{
        	text: 'F. Vigencia',
        	width: 75,
			hidden:true,
            dataIndex: 'f_vigencia'  
        },{
        	text: 'Estado',
        	width: 75,
			hidden:true,
            dataIndex: 'estado'  
        },{
        	text: 'N. Doc',
        	width: 75,
			hidden:true,
            dataIndex: 'num_docu'  
        },{
        	text: 'Ubica',
        	width: 75,
			hidden:true,
            dataIndex: 'ubica'  
        },{ 
            xtype:'actioncolumn',
            width:50,
            items: [{
				icon: urljs + 'img/accept.png',
				tooltip: 'TRAMITADO',
				getClass: function(v, meta, rec) {
					if(rec.get('ubica')=='EM')
						return 'xrow-hidden-button';
				}
            },{
				icon: urljs + 'img/delete.png',
				style: 'cursor: pointer',
				tooltip: 'Eliminar Multas Tributarias',
				handler: function(grid, rowIndex, colIndex) {
					var rec = grid.getStore().getAt(rowIndex);                            
					var showResult = function(btn){  // ELIMINAR POR MEDIO DEL BOTON
						if(btn=='yes')
							$.ajax({
								type: "GET", 
								url: 'multastributarias/eliminartributaria?codigo='+$('#codigoContri').val(),
								data: 'numero='+rec.get('num_docu'),
								success: function(data){
									infoMessage('Eliminado ',data);
									
									grid.getStore().load(grid.getStore().currentPage);                    				
						} 
					});                    	
			   	};
			   	confirmMessage('Eliminar','Seguro de Eliminar?',showResult);               
				},	
				getClass: function(v, meta, rec) {
					if(rec.get('condicion')=='N')
						return 'xrow-hidden-button';
					if(rec.get('estado')=='0')
						return 'xrow-hidden-button';
					if(rec.get('ubica')!='EM')
						return 'xrow-hidden-button';
				},
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
    
    grid.render('gridMultasTributarias');	
	  
});

function mostrarRecibos(){
	var codigo=$('#divCodigo').html();
	showPopup('cuentacorriente/mostrarrecibos?codigo='+codigo,'#poplistarec','650','350','Recibos del Contribuyente');
}





