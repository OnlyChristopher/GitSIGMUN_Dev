Ext.onReady(function(){
    Ext.QuickTips.init();
    
		$("#txtnomden").attr("disabled", "disabled");
		$("#txtnomcalle").attr("disabled", "disabled");
		$("#txtnomvia").removeAttr("disabled");	
		$("#txtnomvia").focus();
		$("#cmbdeno").attr("disabled", "disabled");
	
	$("input[type='button']").button();
	
    Ext.define('BusacPredio', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'direccion'},
            {name: 'propietario', type: 'string'},
            {name: 'dj', type: 'string'},
			{name: 'motivo', type: 'string'},
			{name: 'fecha', type: 'string'},
			{name: 'cod_predio', type: 'string'},
			{name: 'porcentaje', type: 'string'},
			{name: 'sector', type: 'string'},
			{name: 'manzana', type: 'string'},
			{name: 'distrito', type: 'string'},
			{name: 't_via', type: 'string'},
			{name: 'via', type: 'string'}
           
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
        model: 'BusacPredio',
        autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'Buscpredio/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
  });
  
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBuscPredio',
        store: store, 
		
		// selModel: Ext.create('Ext.selection.CheckboxModel',{
        	// mode : 'SINGLE'   
       
        // }),
        //title: 'Listado Predios',
		height:'300px',
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Direccion',
            width: 150,            
            dataIndex: 'direccion'
        },  
		{
            text: 'Propietario',
            width: 150,            
            dataIndex: 'propietario'
        },
		{
            text: 'DJ',
            width: 60, 
			align:'center',
            dataIndex: 'dj'
        }, 
		{
            text: 'Motivo',
            width: 110, 
			align:'center',
            dataIndex: 'motivo'
        }, 
		{
            text: 'F. Declaracion',
            width: 100,
			align:'center',            
            dataIndex: 'fecha'
        },  
		{
            text: 'Cod. Predio',
            width: 90,
			align:'center',
            dataIndex: 'cod_predio'
        },  
		{
            text: 'Porcentaje',
            width: 80, 
			align:'center',
            dataIndex: 'porcentaje'
        },  
		{
            text: 'Sector',
            width: 100,            
            dataIndex: 'sector'
        }, 
		{
            text: 'Mz.',
            width: 30,            
            dataIndex: 'manzana'
        },  
		{
            text: 'Distrito',
            width: 98,
			align:'center',
            dataIndex: 'distrito'
        }, 
		{
            text: 'T. Via',
            width: 40, 
			align:'center',
            dataIndex: 't_via'
        },{  
        	text: 'Via',
        	width: 100,       
            dataIndex: 'via'
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });
    
    grid.render('gridBuscPredio');
    
     $('#txtCriterio').keypress(function(e){
        if(e.which == 13) {
        	ListarPredios();
        }
    });

    
});


function ListarPredios(){
	var grid = Ext.getCmp('xgridBuscPredio');
	var store = grid.getStore();//carga la grilla 
	var proxy = store.getProxy();//Actualiza la grilla
	
	var params = proxy.extraParams;//busca las paginas de la grilla
	delete params;
	
	
	var cmbAnno 		= 	$('#cmbAnno').val();
	var cbxopcion 		= 	$('input[name*="cbxopcion"]:checked').val();
	var cmbvias 		= 	$('#cmbvias').val();
	var txtnomvia 		= 	$('#txtnomvia').val();
	var cmbdeno 		= 	$('#cmbdeno').val();
	var txtnomden 		= 	$('#txtnomden').val();
	var txtnomcalle 	= 	$('#txtnomcalle').val();
	
	alert(cbxopcion+'/'+txtnomvia);
	
	proxy.extraParams = {
	cmbAnno: cmbAnno, 
	cbxopcion: cbxopcion,
	cmbvias: cmbvias,
	txtnomvia: txtnomvia,
	cmbdeno: cmbdeno,
	txtnomden: txtnomden,
	txtnomcalle: txtnomcalle
	
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}



$('#cbxTipoVia').click(function() {
	if($('#cbxTipoVia').is(':checked'))
	{
		$("#txtnomden").attr("disabled", "disabled");
		$("#txtnomcalle").attr("disabled", "disabled");
		$("#cmbdeno").attr("disabled", "disabled");
		$("#cmbvias").removeAttr("disabled");	
		$("#txtnomvia").removeAttr("disabled");	
		$("#txtnomvia").focus();
	}

});
$('#cbxTipoDeno').click(function() {
	if($('#cbxTipoDeno').is(':checked'))
	{
		$("#txtnomvia").attr("disabled", "disabled");
		$("#txtnomcalle").attr("disabled", "disabled");
		$("#cmbvias").attr("disabled", "disabled");
		$("#cmbdeno").removeAttr("disabled");	
		$("#txtnomden").removeAttr("disabled");	
		$("#txtnomden").focus();
	}

});
$('#cbxTipoCalle').click(function() {
	if($('#cbxTipoCalle').is(':checked'))
	{
		$("#txtnomvia").attr("disabled", "disabled");
		$("#txtnomden").attr("disabled", "disabled");
		$("#txtnomcalle").removeAttr("disabled");
		$("#cmbdeno").attr("disabled", "disabled");	
		$("#cmbvias").attr("disabled", "disabled");	
		$("#txtnomcalle").focus();		
	}

});








