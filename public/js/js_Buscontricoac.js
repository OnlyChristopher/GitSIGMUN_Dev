Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Buscontricoac', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'nombres', type: 'string'},
            {name: 'documento', type: 'string'},
            {name: 'direccion', type: 'string'},
			{name: 'tipodoc', type: 'string'},
			{name: 'tipopersona', type: 'string'},
			{name: 'subpersona', type: 'string'},
			{name: 'direccionfiscal', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Buscontricoac',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantbusquedacontri/consulta',
            reader: {
               type: 'json',
               root: 'rowsbaja'
            }
        }
    });
   // xgridContri
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBuscontricoac',
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
            dataIndex: 'direccion',
			hidden:true
        },{
            text: 'Direccion Fiscal',
            //width: 60,
			flex:1,
            dataIndex: 'direccionfiscal'/*,
			hidden:true*/
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
            dataIndex: 'subpersona',
			hidden:true
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
                closePopup('#popbuscacontri');
				enableButton('#btnBusPredio');
                window.parent.muestraDatosContricoac(record);//Captura Valores
            }
        },
         bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridContri',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })	
    });
    
    grid.render('gridBuscontricoac');
	
    //changeDisContri($('#cmbDisContri').val());
	
    $('#txtCriteriobuscoac').keypress(function(e) {
        if(e.which == 13) {
        	buscarContricoac();
        }
    });
});

function buscarContricoac(){
	var grid = Ext.getCmp('xgridBuscontricoac');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriteriobuscoac = $('input[name*="rdCriteriobuscoac"]:checked').val();
	var txtCriteriobuscoac = $('#txtCriteriobuscoac').val();
	var txtCriterioNombrebuscoac = $('#txtCriterioNombrebuscoac').val();
	var txtCriterioAPaternobuscoac = $('#txtCriterioAPaternobuscoac').val();
	var txtCriterioAMaternobuscoac = $('#txtCriterioAMaternobuscoac').val();
	var txtCriterioRazonbuscoac = $('#txtCriterioRazonbuscoac').val();
	var txtDocumentobuscoac = $('#txtDocumentobuscoac').val();
	
	proxy.extraParams = {rdCriteriobuscoac: rdCriteriobuscoac, txtCriteriobuscoac: txtCriteriobuscoac, txtCriterioNombrebuscoac:txtCriterioNombrebuscoac,txtCriterioAPaternobuscoac: txtCriterioAPaternobuscoac,txtCriterioAMaternobuscoac:txtCriterioAMaternobuscoac,txtCriterioRazonbuscoac:txtCriterioRazonbuscoac,txtDocumentobuscoac:txtDocumentobuscoac};
	
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


$('#txtCriteriobuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac1').attr('checked') ){
		return validaTeclas(event,'number');}
});

$('#txtCriterioNombrebuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAPaternobuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAMaternobuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioRazonbuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac4').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtDocumentobuscoac').keypress(function (e) {
	
	if($('#rdCriteriobuscoac3').attr('checked') ){
		return validaTeclas(event,'alpha');}
});	


$('#txtCriteriobuscoac').keypress(function (e) {
if(e.keyCode==13){
		 var numCeros = '0000000'; // pon el n? de ceros que necesites
		var valor =  $('#txtCriteriobuscoac').val().trim();
		var radio = $('#rdCriteriobuscoac1').val();
	   // alert(radio);
		//if(valor.length==0 &&  radio!="C")
		if($('#rdCriteriobuscoac1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
		{ 
			//$('#txtCriteriobuscoac').val(valor);
			  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriteriobuscoac').val(valor2);  //obj.value = valor;
		}
		else{
			$('#txtCriteriobuscoac').val(valor);
		}
		buscarContricoac();	
	}
});

$('#txtCriterioNombrebuscoac').keypress(function (e) {
	if(e.keyCode==13){
		buscarContricoac();	
	}		
});

$('#txtCriterioAPaternobuscoac').keypress(function (e) {
	if(e.keyCode==13){
		buscarContricoac();	
	}		
});

$('#txtCriterioAMaternobuscoac').keypress(function (e) {
	if(e.keyCode==13){
		buscarContricoac();	
	}		
});

$('#txtCriterioRazonbuscoac').keypress(function (e) {
	if(e.keyCode==13){
		buscarContricoac();	
	}		
});

$('#txtDocumentobuscoac').keypress(function (e) {
	if(e.keyCode==13){
		buscarContricoac();	
	}		
});



//$("input[type='button']").button();

$('#rdCriteriobuscoac1').click(function(){
	
	if($('#rdCriteriobuscoac1').attr('checked'))
	{
		
		$('#txtCriteriobuscoac').val('');
		$('#txtCriteriobuscoac').focus();
		//removeClass('myClass yourClass');
		//.addClass( "myClass yourClass" );
		$('#J').removeClass( "invisible" );
		$('#K').addClass( "invisible" );
		$('#L').addClass( "invisible" );
		$('#M').addClass( "invisible" );
		
		$('#J input').val('');
		$('#K input').val('');
		$('#L input').val('');
		$('#M input').val('');
	}
});

$('#rdCriteriobuscoac2').click(function(){


	if($('#rdCriteriobuscoac2').attr('checked'))
	{
		$('#txtCriteriobuscoac').val('');
		$('#txtCriteriobuscoac').focus();
		$('#J').addClass( "invisible" );
		$('#K').removeClass( "invisible" );
		$('#L').addClass( "invisible" );
		$('#M').addClass( "invisible" );
		
		$('#J input').val('');
		$('#K input').val('');
		$('#L input').val('');
		$('#M input').val('');
	}
});

$('#rdCriteriobuscoac3').click(function(){

	
	if($('#rdCriteriobuscoac3').attr('checked'))
	{
		$('#txtCriteriobuscoac').val('');
		$('#txtCriteriobuscoac').focus();
		$("#txtCriteriobuscoac").attr('maxlength','11');
		
		$('#J').addClass( "invisible" );
		$('#K').addClass( "invisible" );
		$('#L').addClass( "invisible" );
		$('#M').removeClass( "invisible" );
		
		$('#J input').val('');
		$('#K input').val('');
		$('#L input').val('');
		$('#M input').val('');
	}
});

$('#rdCriteriobuscoac4').click(function(){

	
	if($('#rdCriteriobuscoac4').attr('checked'))
	{
		$('#txtCriteriobuscoac').val('');
		$('#txtCriteriobuscoac').focus();
		$("#txtCriteriobuscoac").attr('maxlength','11');
		
		$('#J').addClass( "invisible" );
		$('#K').addClass( "invisible" );
		$('#L').removeClass( "invisible" );
		$('#M').addClass( "invisible" );
		
		$('#J input').val('');
		$('#K input').val('');
		$('#L input').val('');
		$('#M input').val('');
	}
});


$('#txtCriteriobuscoac').blur(function(){
    var numCeros = '0000000'; // pon el n? de ceros que necesites
    var valor =  $('#txtCriteriobuscoac').val().trim();
    var radio = $('#rdCriteriobuscoac1').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriteriobuscoac1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriteriobuscoac').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriteriobuscoac').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriteriobuscoac').val(valor);
    }
    
});

