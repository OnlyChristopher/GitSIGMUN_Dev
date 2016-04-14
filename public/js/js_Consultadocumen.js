Ext.onReady(function(){
    Ext.QuickTips.init();
    
    //enableButton('#btnTodos'); //si quiero que me aprezca uno seleccioando
    
    
    Ext.define('Bandeja', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'contribuyente', type: 'string'},
            {name: 'documento', type: 'string'},
            {name: 'monto', type: 'string'},
            {name: 'fecha', type: 'string'},
            {name: 'valor'},
            {name: 'num'},
            {name: 'ano'},
            {name: 'numero'},
            {name: 'anio'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Bandeja',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'consultadocumen/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBandeja',
    	store: store, 
        width: '862px',
        columnLines: true,
        //height: '100px',
        title: '',
        viewConfig: {
        	loadMask: {msg: 'Listando Documentos...'}/*,
        	listeners: {        		
                beforerefresh: function() {
        			//alert('antes refresh');
        			var ind=0;
        			//var str=cargaChecks($('#txtUsuUsuario').val());
                    this.store.each(function(record) {
                    	if(str.indexOf(record.get('codigo')) != -1)
                    		Ext.getCmp('xgridBandeja').getSelectionModel().select(ind,true,false);
                    	ind++;
                    });
                }
            }*/
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
    	
    	selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode : 'SINGLE'   
        		//mode : 'MULTI'                //seleccionar una sola fila mode : 'SINGLE'  -------------- varios mode:'MULTI'
        }),

    	
        columns: [ 
            Ext.create('Ext.grid.RowNumberer'),          
        {
            text: 'C&oacute;digo',
            width: 80,            
            dataIndex: 'codigo'
        },{
            text: 'Contribuyente',
            width: 210, 
            dataIndex: 'contribuyente'            
        },{
            text: 'Documento',
            width: 320,            
            dataIndex: 'documento'
        },{
            text: 'Monto Total',
            width: 100,
            dataIndex: 'monto',
            align: 'right'
        },{
            text: 'Fecha Emisi&oacute;n',
            width: 100,
            dataIndex: 'fecha',
            align: 'right'
         //   hidden:true
        },{
            text: 'Valor',
            width: 60, 
            dataIndex: 'valor',
            hidden:true
        },{
            text: 'Num',
            width: 60, 
            dataIndex: 'num',
            hidden:true
        },{
            text: 'Ano',
            width: 60, 
            dataIndex: 'ano',
            hidden:true
        },{
            text: 'Numero',
            width: 60, 
            dataIndex: 'numero',
            hidden:true
        },{
            text: 'Anio',
            width: 60, 
            dataIndex: 'anio',
            hidden:true
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridPoblad',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando Documentos Recibidos {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Documentos"
        })
        
    });
    
    grid.render('gridBandeja');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarBandeja();
        }
    }); 
    
    $("input[type='button']").button();
});

$('#rdCriterio1').click(function(){
	
	if($('#rdCriterio1').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});

$('#rdCriterio2').click(function(){
	
	if($('#rdCriterio2').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});

$('#rdCriterio3').click(function(){
	
	if($('#rdCriterio3').attr('checked'))
	{
		$('#txtCriterio').val('');
	}
});
/*
function buscarBandeja(est){
	var grid = Ext.getCmp('xgridBandeja');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	//Ext.MessageBox.alert('Editar',rdCriterio+'/'+txtCriterio);
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio, estado:est};
	store.loadPage(1); 
}
*/

function buscarBandeja(){
	var grid = Ext.getCmp('xgridBandeja');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	//Ext.MessageBox.alert('Editar',rdCriterio+'/'+txtCriterio);
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio};
	store.loadPage(1); 
}

$('#txtCriterio').blur(function(){
    var numCeros = '0000000'; // pon el n? de ceros que necesites
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

function enviarruta(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('consultadocumen/ruta?valor='+valor+'&num='+num+'&ano='+ano,'#popupruta','1190','400','Detalle de Rutas');
		//alert(valor);
	}
}

function verdocumento(){
	var grid = Ext.getCmp('xgridBandeja');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
			
		var valor = "";
		var num = "";
		var ano = "";
		Ext.each(selectedRecordsArray, function (item) {		  	
			valor = item.data.valor; 
			num = item.data.num; 
			ano = item.data.ano; 
		});
		showPopup('consultadocumen/verdocumento?valor='+valor+'&num='+num+'&ano='+ano,'#popupdocumento','700','480','Ver Documento');
		//alert(valor);
	}
}




/*
$('#btnRecibidos').click(function() {
	
	alert($(this).attr("value"));
	//txtventani=$('#txtventani').val();
	
	$.ajax({     
		type: "POST",     
		url: "/turnventanilla/salir", 
		data: 'txtventani='+txtventani, 
		success: function(data) { 
			closePopup('#poptipoentrada');
		},     
		error: function() {
		} 
	});
	
});
*/
















