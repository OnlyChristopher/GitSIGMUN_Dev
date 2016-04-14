Ext.onReady(function(){
	Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
	var data = $('#frmBandeconsulta').serializeObject();
	
	Ext.define('Regisconsul', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'num_archi'},
			{name: 'num_expe'},
			{name: 'ano_expe'},
            {name: 'cod_contribuyente'},
			{name: 'razon_social'},
			{name: 'domicilio_fiscal'},
			{name: 'domicilio_predio'},
            {name: 'sector_zona'},
            {name: 'ano_periodo'},
			{name: 'abreviatura'},
			{name: 'num_valor_tribu'},
			{name: 'monto'},
			{name: 'rec1'},
			{name: 'fech_rec1'},
			{name: 'observacion'},
			{name: 'estado_desc'},
			{name: 'nomdigitador'},
			{name: 'fecha_ing'}
			
        ]
    });
 	
    var store = Ext.create('Ext.data.Store', {
        model: 'Regisconsul',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'banderegistroconsulta/reconsul',
			actionMethods: {
           		read: 'POST'
       		},
		   	extraParams: data,
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  	});
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRegisconsul',
        store: store, 
        title: 'Registro',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
		columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Num. Archivador',
            width: 100,            
            dataIndex: 'num_archi'       
        },{
            text: 'Num. Expediente',
            width: 100,            
            dataIndex: 'num_expe'       
        },{        
        	text: 'A&ntilde;o Expediente',
        	width: 100,
            dataIndex: 'ano_expe'  
        },{        
        	text: 'Codigo',
        	width: 100,
            dataIndex: 'cod_contribuyente'  
        },{        
        	text: 'Contribuyente',
        	width: 100,
            dataIndex: 'razon_social'  
        },{
            text: 'Domicilio Fiscal',
            width: 100,
            dataIndex: 'domicilio_fiscal'       
        },{        
        	text: 'Domicilio Predio',
        	width: 100,
            dataIndex: 'domicilio_predio'  
        },{
        	text: 'Sector',
        	width: 100,
            dataIndex: 'sector_zona'  
        },{
        	text: 'A&ntilde;o Valor',
        	width: 100,
            dataIndex: 'ano_periodo'  
        },{
        	text: 'Valor',
        	width: 100,
            dataIndex: 'abreviatura'  
        },{
        	text: 'Numero Valor',
        	width: 100,
            dataIndex: 'num_valor_tribu'  
        },{
        	text: 'Monto',
        	width: 100,
            dataIndex: 'monto'  
        },{
        	text: 'Rec1',
        	width: 100,
            dataIndex: 'rec1'  
        },{
        	text: 'Fecha Rec1',
        	width: 100,
            dataIndex: 'fech_rec1'  
        },{
        	text: 'Observacion',
        	width: 100,
            dataIndex: 'observacion'  
        },{
        	text: 'Estado',
        	width: 100,
            dataIndex: 'estado_desc'  
        },{
        	text: 'Usuario',
        	width: 100,
            dataIndex: 'nomdigitador'  
        },{
        	text: 'Fecha Ingreso',
        	width: 100,
            dataIndex: 'fecha_ing'  
        }],
	    bbar: Ext.create('Ext.PagingToolbar', {
	    	pageSize: 10,
	        store: store,
	        displayInfo: true,
	        beforePageText: "P&aacute;gina",
			afterPageText: "de {0}",
			firstText: "Primera p&aacute;gina",
			prevText: "P&aacute;gina anterior",
			nextText: "P&aacute;gina siguiente",
			lastText: "&Uacute;ltima p&aacute;gina",
			refreshText: "Actualizar",
	        displayMsg: 'Mostrando {0} - {1} de {2}',
	        emptyMsg: "No se encontraron registros"
	    })
	});
    
    grid.render('gridRegisconsul');
});

function exportarConsulta(){
	
	var showResult = function(btn){
		if(btn=='yes'){
			data = "?num_expe="+$('#num_expe').val()+"&ano_expe="+$('#ano_expe').val()+"&cod_contri="+$('#cod_contri').val()+"&nom_contri="+$('#nom_contri').val()+"&usuario_cod="+$('#usuario_cod').val()+"&registro_estado="+$('#registro_estado').val()+"&fec_desde="+$('#fec_desde').val()+"&fec_hasta="+$('#fec_hasta').val()+"&num_archi="+$('#num_archi').val();
					
			window.open(urljs+"banderegistroconsulta/exporta"+data);
		}
	};
	confirmMessage('Exportar','Desea exportar los datos seleccionados?',showResult);	
}