Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Registro', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigoregistro', type: 'string'},
            {name: 'codigocontri', type: 'string'},
            {name: 'razonsocial', type: 'string'},
			{name: 'domiciliofiscal', type: 'string'},
			{name: 'domiciliopredial', type: 'string'},
			{name: 'zona', type: 'string'},
			{name: 'anno', type: 'string'},
			{name: 'valor', type: 'string'},
			{name: 'valor_tribu', type: 'string'},
			{name: 'num_expe', type: 'string'},
			{name: 'ano_expe', type: 'string'},
			{name: 'num_valor', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Registro',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'banderegistro/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridRegistro',
        store: store,        
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Registros...'}
    	},
    	//SELECCIONA UNA LINEA 
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'SINGLE'   
       
        }),//////////


        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'anno'            
        },{
            text: 'Valor',
            width: 40,
            dataIndex: 'valor'            
        },{
            text: 'Registro',
            width: 60,            
            dataIndex: 'codigoregistro',
			hidden:true
        },{        
        	text: 'C&oacute;digo ',
        	width: 60,
            dataIndex: 'codigocontri'
        },{
            text: 'Nombre o Razon Social',
            flex:1,
            dataIndex: 'razonsocial'            
        },{
            text: 'Direccion Fiscal',
            //width: 460,
			flex:1,
            dataIndex: 'domiciliofiscal'            
        },{
            text: 'Direccion Predial',
            //width: 460,
			flex:1,
            dataIndex: 'domiciliopredial'            
        },{
            text: 'Zona',
            width: 100,
            dataIndex: 'zona'            
        },{
            text: 'Valor Tribu',
            width: 100,
            dataIndex: 'valor_tribu',
			hidden:true
        },{
            text: 'Num Expe.',
            width: 100,
            dataIndex: 'num_expe',
			hidden:true
        },{
            text: 'Ano Expe.',
            width: 100,
            dataIndex: 'ano_expe',
			hidden:true
        },{
            text: 'Num. Valor',
            width: 100,
            dataIndex: 'num_valor',
			hidden:true
        },{
            xtype:'actioncolumn',
            width:50,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Registro',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('banderegistro/formu?codigoregistro='+rec.get('codigoregistro'),'#popRegistro','900','550','Editara Registro');
                }
            },{
                icon: urljs + 'img/delete.png',
                tooltip: 'Eliminar',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);                    
                    
                    var showResult = function(btn){
            			if(btn=='yes')  //si es 'yes' llamo al ajax
            				
            				$.ajax({
                        		type: "GET",
                        		url: 'banderegistro/eliminarregistro',
                        		data: 'codigoregistro='+rec.get('codigoregistro'),
                        		success: function(data){
                        			//alert(data);
                        			infoMessage('Eliminado',data);
                        			
                					Ext.getCmp('xgridRegistro').getStore().load();
                        		}
                        	});	
            		};
            		confirmMessage('Eliminar','Seguro de eliminar el c&oacute;digo ?',showResult);
                }                
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridRegistro',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Registros"
        })
        
    });
    
    grid.render('gridRegistro');
    
    $('#txtCriterio').keypress(function(e){
        if(e.which == 13) {
        	buscarRegistro();
        }
    });
});

function buscarRegistro(){
	var grid = Ext.getCmp('xgridRegistro');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var cmbanno = $('#cmbanno').val();
	var cmbvalortribu = $('#cmbvalortribu').val();
	
	var txtnumexpe = $('#txtCriterioExpediente').val();
	var txtanoexpe = $('#txtCriterioNumexpediente').val();
	//var cmbusuario = $('#cmbUsuario option:selected').text();
	var cmbusuario = $('#cmbUsuario').val();
	
	var tesodesde= $('#tesodesde').val();
	var tesohasta= $('#tesohasta').val();

	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, anno: cmbanno, valor: cmbvalortribu, numexpe: txtnumexpe, anoexpe: txtanoexpe, usuario: cmbusuario, tesodesde: tesodesde, tesohasta: tesohasta};
	
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
		
		$('#criterio').removeClass( "invisible" );
		$('#E').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		
		$('#criterio').removeClass( "invisible" );
		$('#E').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		
		$('#criterio').removeClass( "invisible" );
		$('#E').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});


$('#rdCriterio4').click(function(){
	
	if($('#rdCriterio4').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		
		$('#criterio').removeClass( "invisible" );
		$('#E').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});

$('#rdCriterio5').click(function(){
	
	if($('#rdCriterio5').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		
		$('#criterio').removeClass( "invisible" );
		$('#E').addClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});

$('#rdCriterio6').click(function(){
	
	if($('#rdCriterio6').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
	
		
		$('#criterio').addClass( "invisible" );
		$('#E').removeClass( "invisible" );
		
		$('#C input').val('');
		$('#N input').val('');
		$('#D input').val('');
		$('#P input').val('');
		$('#Z input').val('');
		$('#E input').val('');
		
	}
});





$('#txtCriterio').blur(function(){
    var numCeros = '0000000'; // pon el nº de ceros que necesites
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

$('#txtCriterioExpediente').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtCriterioExpediente').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtCriterioExpediente').val(valor2);  //obj.value = valor;
        
});
