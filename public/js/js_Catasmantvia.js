Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Via', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo', type: 'string'},
            {name: 'tipo', type: 'string'},
            {name: 'nombrevia', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Via',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'catasmantvia/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridVia',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Vias...'}
    	},
    	//SELECCIONA UNA LINEA 
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'SINGLE'   
       
        }),//////////


        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'codigo'
        },{        
        	text: 'Tipo',
        	width: 460,
            dataIndex: 'tipo'
        },{
            text: 'Nombre V&iacute;a',
            width: 460,
            dataIndex: 'nombrevia'            
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('catasmantvia/formu?codigo='+rec.get('codigo'),'#popviacata','420','140','Editar Via');
                }
            },{
                icon: urljs + 'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Eliminar via',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);                    
                    
                    var showResult = function(btn){
            			if(btn=='yes')  //si es 'yes' llamo al ajax
            				
            				$.ajax({
                        		type: "GET",
                        		url: 'catasmantvia/eliminar',
                        		data: 'codigo='+rec.get('codigo'),
                        		success: function(data){
                        			//alert(data);
                        			infoMessage('Eliminado',data);
                        			grid.getStore().load(grid.getStore().currentPage);
                        		}
                        	});	
            		};
            		confirmMessage('Eliminar','Seguro de eliminar el c&oacute;digo '+rec.get('codigo')+' ?',showResult);
                }                
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridVia',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Vias"
        })
        
    });
    
    grid.render('gridVia');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarViacata();
        }
    }); 
});

function buscarViacata(){
	var grid = Ext.getCmp('xgridVia');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio};
	
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
    var numCeros = '000000'; // pon el nº de ceros que necesites
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