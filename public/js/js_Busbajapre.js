Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Bajapre', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'nombres', type: 'string'},
            {name: 'documento', type: 'string'},
            {name: 'direccion', type: 'string'},
			{name: 'tipodoc', type: 'string'},
			{name: 'tipopersona', type: 'string'},
			{name: 'subpersona', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Bajapre',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantbusbajapre/consulta',
            reader: {
               type: 'json',
               root: 'rowsbaja'
            }
        }
    });
   // xgridContri
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBajapre',
        store: store,   
        title: 'Contribuyentes',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'codigo'
        },{        
        	text: 'Nombres',
        	flex: 1,
            dataIndex: 'nombres'
        },{
            text: 'Nro. Documento',
            width: 120,
            dataIndex: 'documento'            
        },{
            text: 'Direcci&oacute;n',
            width: 560,
            dataIndex: 'direccion'
        },{
            text: 'Tipodoc',
            width: 80,
            dataIndex: 'tipodoc',
			hidden:true
        },{
            text: 'Tipopersona',
            width: 60,
            dataIndex: 'tipopersona',
			hidden:true
        },{
            text: 'Personasub',
            width: 60,
            dataIndex: 'subpersona'/*,
			hidden:true*/
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBajapre',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
                closePopup('#popbuscabajapre');
                window.parent.muestraDatosBajaPred(record);//Captura Valores
            }
        } 		
    });
    
    grid.render('gridBajapred');
	
    //changeDisContri($('#cmbDisContri').val());
	
    $('#txtCriteriobus').keypress(function(e) {
        if(e.which == 13) {
        	buscarBajapre();
        }
    });
});

function buscarBajapre(){
	var grid = Ext.getCmp('xgridBajapre');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriteriobus = $('input[name*="rdCriteriobus"]:checked').val();
	var txtCriteriobus = $('#txtCriteriobus').val();
	var txtCriterioNombrebus = $('#txtCriterioNombrebus').val();
	var txtCriterioAPaternobus = $('#txtCriterioAPaternobus').val();
	var txtCriterioAMaternobus = $('#txtCriterioAMaternobus').val();
	var txtCriterioRazonbus = $('#txtCriterioRazonbus').val();
	var txtDocumentobus = $('#txtDocumentobus').val();
	
	proxy.extraParams = {rdCriteriobus: rdCriteriobus, txtCriteriobus: txtCriteriobus, txtCriterioNombrebus:txtCriterioNombrebus,txtCriterioAPaternobus: txtCriterioAPaternobus,txtCriterioAMaternobus:txtCriterioAMaternobus,txtCriterioRazonbus:txtCriterioRazonbus,txtDocumentobus:txtDocumentobus};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}
/*
function actualizarContri(){
	var grid = Ext.getCmp('xgridBajapre');
	var store = grid.getStore();
	store.reload();
}*/


$('#txtCriteriobus').keypress(function (e) {
	
	if($('#rdCriteriobus1').attr('checked') ){
		return validaTeclas(event,'number');}
});

$('#txtCriterioNombrebus').keypress(function (e) {
	
	if($('#rdCriteriobus2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAPaternobus').keypress(function (e) {
	
	if($('#rdCriteriobus2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAMaternobus').keypress(function (e) {
	
	if($('#rdCriteriobus2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioRazonbus').keypress(function (e) {
	
	if($('#rdCriteriobus4').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtDocumentobus').keypress(function (e) {
	
	if($('#rdCriteriobus3').attr('checked') ){
		return validaTeclas(event,'number');}
});	


$('#txtCriteriobus').keypress(function (e) {
if(e.keyCode==13){
		 var numCeros = '0000000'; // pon el n? de ceros que necesites
		var valor =  $('#txtCriteriobus').val().trim();
		var radio = $('#rdCriteriobus1').val();
	   // alert(radio);
		//if(valor.length==0 &&  radio!="C")
		if($('#rdCriteriobus1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
		{ 
			//$('#txtCriteriobus').val(valor);
			  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriteriobus').val(valor2);  //obj.value = valor;
		}
		else{
			$('#txtCriteriobus').val(valor);
		}
		buscarBajapre();	
	}
});

$('#txtCriterioNombrebus').keypress(function (e) {
	if(e.keyCode==13){
		buscarBajapre();	
	}		
});

$('#txtCriterioAPaternobus').keypress(function (e) {
	if(e.keyCode==13){
		buscarBajapre();	
	}		
});

$('#txtCriterioAMaternobus').keypress(function (e) {
	if(e.keyCode==13){
		buscarBajapre();	
	}		
});

$('#txtCriterioRazonbus').keypress(function (e) {
	if(e.keyCode==13){
		buscarBajapre();	
	}		
});

$('#txtDocumentobus').keypress(function (e) {
	if(e.keyCode==13){
		buscarBajapre();	
	}		
});



//$("input[type='button']").button();

$('#rdCriteriobus1').click(function(){
	
	if($('#rdCriteriobus1').attr('checked'))
	{
		
		$('#txtCriteriobus').val('');
		$('#txtCriteriobus').focus();
		//removeClass('myClass yourClass');
		//.addClass( "myClass yourClass" );
		$('#Z').removeClass( "invisible" );
		$('#X').addClass( "invisible" );
		$('#Y').addClass( "invisible" );
		$('#V').addClass( "invisible" );
		
		$('#Z input').val('');
		$('#X input').val('');
		$('#Y input').val('');
		$('#V input').val('');
	}
});

$('#rdCriteriobus2').click(function(){


	if($('#rdCriteriobus2').attr('checked'))
	{
		$('#txtCriteriobus').val('');
		$('#txtCriteriobus').focus();
		$('#Z').addClass( "invisible" );
		$('#X').removeClass( "invisible" );
		$('#Y').addClass( "invisible" );
		$('#V').addClass( "invisible" );
		
		$('#Z input').val('');
		$('#X input').val('');
		$('#Y input').val('');
		$('#V input').val('');
	}
});

$('#rdCriteriobus3').click(function(){

	
	if($('#rdCriteriobus3').attr('checked'))
	{
		$('#txtCriteriobus').val('');
		$('#txtCriteriobus').focus();
		$("#txtCriteriobus").attr('maxlength','11');
		
		$('#Z').addClass( "invisible" );
		$('#X').addClass( "invisible" );
		$('#Y').addClass( "invisible" );
		$('#V').removeClass( "invisible" );
		
		$('#Z input').val('');
		$('#X input').val('');
		$('#Y input').val('');
		$('#V input').val('');
	}
});

$('#rdCriteriobus4').click(function(){

	
	if($('#rdCriteriobus4').attr('checked'))
	{
		$('#txtCriteriobus').val('');
		$('#txtCriteriobus').focus();
		$("#txtCriteriobus").attr('maxlength','11');
		
		$('#Z').addClass( "invisible" );
		$('#X').addClass( "invisible" );
		$('#Y').removeClass( "invisible" );
		$('#V').addClass( "invisible" );
		
		$('#Z input').val('');
		$('#X input').val('');
		$('#Y input').val('');
		$('#V input').val('');
	}
});


$('#txtCriteriobus').blur(function(){
    var numCeros = '0000000'; // pon el n? de ceros que necesites
    var valor =  $('#txtCriteriobus').val().trim();
    var radio = $('#rdCriteriobus1').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriteriobus1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriteriobus').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriteriobus').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriteriobus').val(valor);
    }
    
});

