Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	///////////////////////////////
	//alert (window.location.hostname);
	///////////////////////////////
    Ext.define('Poblad', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo', type: 'string'},
            {name: 'zona', type: 'string'},
            {name: 'urbanizacion', type: 'string'},
			{name: 'via', type: 'string'},
			{name: 'estado', type: 'string'},
			{name: 'cuadra', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Poblad',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantpoblado/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPoblad',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Centros Poblados...'}
    	},
    	//SELECCIONA UNA LINEA 
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'SINGLE'   
       
        }),//////////


        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 150, 
			align:'center',
            dataIndex: 'codigo'
        },{
            text: 'Zona',
            width: 200,
            dataIndex: 'zona'            
        },{        
        	text: 'Urbanizaci&oacute;n',
        	width: 300,
            dataIndex: 'urbanizacion'
        },{        
        	text: 'Nombre de Via',
        	width: 300,
            dataIndex: 'via'
        },{        
        	text: 'N cuadra',
        	width: 70,
            dataIndex: 'cuadra'
        },{        
        	text: 'Estado',
        	width: 100,
            dataIndex: 'estado'
        },{
            xtype:'actioncolumn',
            width:60,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Centro Poblado',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantpoblado/formu?codigo='+rec.get('codigo'),'#popNueZona','380','300','Editar Centro Poblado');
                }
            },{
                icon: urljs + 'img/view.png',
                style: 'cursor: pointer',
                tooltip: 'Detalle de Via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('mantpoblado/frmdetalle?codigo='+rec.get('codigo'),'#popNueVia','700','450','Detalle de via');
                }
            },{
                icon: urljs + 'img/printer_rec.png',
                style: 'cursor: pointer',
                tooltip: 'Imprimir Reporte',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
					 var codigo=rec.get('codigo');
                    showPopupReport('schema=&tipo=pdf&nombrereporte=Vias&param=codigo^'+codigo,'pouprptvias',700,600,'Reporte');
                }
            }/*,{
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);                    
                    
                    var showResult = function(btn){
            			if(btn=='yes')  //si es 'yes' llamo al ajax
            				
            				$.ajax({
                        		type: "GET",
                        		url: 'mantpoblado/eliminar',
                        		data: 'codigo='+rec.get('codigo'),
                        		success: function(data){
                        			//alert(data);
                        			infoMessage('Eliminado',data);
                        			
                					Ext.getCmp('xgridPoblad').getStore().load();
                        		}
                        	});	
            		};
            		confirmMessage('Eliminar','Seguro de eliminar el c&oacute;digo '+rec.get('codigo')+' ?',showResult);
                }                
            }*/]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridPoblad',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Centros Poblados"
        })
        
    });
    
    grid.render('gridPoblad');
    
    $('#txtCriterio').keypress(function(e){
        if(e.which == 13) {
        	buscarPoblad();
        }
    });
});

function buscarPoblad(){
	var grid = Ext.getCmp('xgridPoblad');
	var store = grid.getStore();//carga la grilla 
	var proxy = store.getProxy();//Actualiza la grilla
	
	var params = proxy.extraParams;//busca las paginas de la grilla
	delete params;
	
	var txtcodigo = $('#txtCodigo').val();
	var txtzona = $('#txtZona').val();
	var txturbanizacion = $('#txtUrbanizacion').val();
	var txtnomvia = $('#txtNomVia').val();
	var cmbEstado = $('#cmbEstado').val();
	
	proxy.extraParams = {
	codigo: txtcodigo, 
	zona: txtzona,
	urbanizacion: txturbanizacion,
	nomvia: txtnomvia,
	estado: cmbEstado
	
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}


$('#txtCriterio').keypress(function (e) {
	
	if($('#rdCriterio1').attr('checked') )
		return validaTeclas(event,'number');
		
	if($('#rdCriterio2').attr('checked'))
		return validaTeclas(event,'text');
		
	if($('#rdCriterio3').attr('checked'))
		return validaTeclas(event,'text');
});

$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	}
});


$('#txtCriterio').blur(function(){
    var numCeros = '0000'; // pon el nº de ceros que necesites
    var valor =  $('#txtCriterio').val().trim();
    var radio = $('#rdCriterio1').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriterio').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriterio').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriterio').val(valor);
    }
    
});
////SELECC UNO Y ENVIA EL VALOR SELECCIONADO(atrapa el codigo)
function verdetalle(){
	var grid = Ext.getCmp('xgridPoblad');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var codigo = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			codigo = item.data.codigo; 
		});
		///abre la ventana con el codigo seleccionado
		showPopup('mantvias/index?codigo='+codigo,'#popDetVias','550','450','Detalle de V&iacute;as');
	}
	else
		infoMessage('Centro Poblado','Seleccione un centro poblado!');	
}
////

