Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	var ano = (new Date).getFullYear();
	$('#txtAnnoBus').val(ano);
	
    Ext.define('ContriPredio', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'nombres', type: 'string'},
			{name: 'cod_pred', type: 'string'},
			{name: 'anexo', type: 'string'},
			{name: 'sub_anexo', type: 'string'},
            {name: 'documento', type: 'string'},
            {name: 'direccion', type: 'string'},
			{name: 'porcen_propiedad', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'ContriPredio',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'Rentas/consultabuspred',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPrediosbuscado',
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
        	width: 450,
            dataIndex: 'nombres'
        },{        
        	text: 'Codigo Predio',
        	width: 70,
            dataIndex: 'cod_pred'
        },{        
        	text: 'Anexo',
        	width: 50,
            dataIndex: 'anexo'
        },{        
        	text: 'Sub Anexo',
        	width: 55,
            dataIndex: 'sub_anexo'
        },{        
        	text: '% Prop.',
        	width: 55,
            dataIndex: 'porcen_propiedad'
        },{
            text: 'Nro. Documento',
            width: 120,
            dataIndex: 'documento'            
        },{
            text: 'Direcci&oacute;n',
            width: 560,
            dataIndex: 'direccion'
        },{
            xtype:'actioncolumn',
            width:70,
            items: [{
                icon: urljs + 'img/edit.png',
                tooltip: 'Editar Contribuyente',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    //showPopup('mantcontri/formu?codigo='+rec.get('codigo'),'#popcontri','720','440','Editar Contribuyente');
					Ext.Ajax.request({
						  url: urljs + "Rentas/verpredio",
						  method: "POST",
						  params: {codigo: rec.get('codigo'),anno: $('#txtAnnoBus').val(),cod_pred: rec.get('cod_pred'),anexo: rec.get('anexo'),sub_anexo: rec.get('sub_anexo')},
						  success: function(response){					  
							  	data = Ext.JSON.decode(response.responseText);
							  	for(var i=0 ; i < 1; i++){
									window.parent.addDatosPredio(data[i]);
								}
						  }
					});
                },
                getClass: function(value,metadata,record){
                	if (mod=='14')
                		return gridColumnAction(false);
                	   
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridContriPred',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });
    
    grid.render('gridPrediosbuscado');
	
  
	
 /*   $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarContri();
        }
    });*/
});

function buscarContri2(){
	var grid = Ext.getCmp('xgridPrediosbuscado');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterioPred"]:checked').val();
	var txtCriterio = $('#txtCriterioPredio').val();
	var txtCriterioNombre = $('#txtCriterioNombrePredio').val();
	var txtCriterioAPaterno = $('#txtCriterioAPaternoPredio').val();
	var txtCriterioAMaterno = $('#txtCriterioAMaternoPredio').val();
	var txtCriterioRazon = $('#txtCriterioRazonPredio').val();
	var txtDocumento = $('#txtDocumentoPredio').val();
	var txtAnno = $('#txtAnnoBus').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento,anno : txtAnno};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}

function actualizarContri(){
	var grid = Ext.getCmp('xgridPrediosbuscado');
	var store = grid.getStore();
	store.reload();
}

$('#txtAnnoBus').keypress(function (e) {
	
	return validaTeclas(event,'number');
});

$('#txtCriterioPredio').keypress(function (e) {
	
	if($('#rdCriterioPred1').attr('checked') ){
		return validaTeclas(event,'number');}
});

$('#txtCriterioNombrePredio').keypress(function (e) {
	
	if($('#rdCriterioPred2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAPaternoPredio').keypress(function (e) {
	
	if($('#rdCriterioPred3').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioAMaternoPredio').keypress(function (e) {
	
	if($('#rdCriterioPred2').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtCriterioRazonPredio').keypress(function (e) {
	
	if($('#rdCriterioPred4').attr('checked') ){
		return validaTeclas(event,'text');}
});
$('#txtDocumentoPredio').keypress(function (e) {
	
	if($('#rdCriterioPred3').attr('checked') ){
		return validaTeclas(event,'number');}
});	

$('#txtCriterioPredio').keypress(function (e) {
if(e.keyCode==13){
		 var numCeros = '0000000'; // pon el n� de ceros que necesites
		var valor =  $('#txtCriterioPredio').val();
		var radio = $('#rdCriterioPred1').val();
	   // alert(radio);
		//if(valor.length==0 &&  radio!="C")
		if($('#rdCriterioPred1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
		{ 
			//$('#txtCriterio').val(valor);
			  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$('#txtCriterioPredio').val(valor2);  //obj.value = valor;
		}
		else{
			$('#txtCriterioPredio').val(valor);
		}
		buscarContri();	
	}
});
$('#txtCriterioNombrePredio').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioAPaternoPredio').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioAMaternoPredio').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtCriterioRazonPredio').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});
$('#txtDocumentoPredio').keypress(function (e) {
	if(e.keyCode==13){
		buscarContri();	
	}		
});



$('#rdCriterioPred1').click(function(){
	
	if($('#rdCriterioPred1').attr('checked'))
	{
		$('#txtCriterioPredio').val('');
		$('#txtCriterioPredio').focus();
		//removeClass('myClass yourClass');
		//.addClass( "myClass yourClass" );
		$('#CPred').removeClass( "invisible" );
		$('#NPred').addClass( "invisible" );
		$('#RPred').addClass( "invisible" );
		$('#DPred').addClass( "invisible" );
		
		$('#CPred input').val('');
		$('#NPred input').val('');
		$('#RPred input').val('');
		$('#DPred input').val('');
	}
});

$('#rdCriterioPred2').click(function(){
	//alert(2);
	if($('#rdCriterioPred2').attr('checked'))
	{
		$('#txtCriterioPredio').val('');
		$('#txtCriterioPredio').focus();
		$('#CPred').addClass( "invisible" );
		$('#NPred').removeClass( "invisible" );
		$('#RPred').addClass( "invisible" );
		$('#DPred').addClass( "invisible" );
		
		$('#CPred input').val('');
		$('#NPred input').val('');
		$('#RPred input').val('');
		$('#DPred input').val('');
	}
});

$('#rdCriterioPred3').click(function(){
	
	if($('#rdCriterioPred3').attr('checked'))
	{
		$('#txtCriterioPredio').val('');
		$('#txtCriterioPredio').focus();
		$("#txtCriterioPredio").attr('maxlength','11');
		
		$('#CPred').addClass( "invisible" );
		$('#NPred').addClass( "invisible" );
		$('#RPred').removeClass( "invisible" );
		$('#DPred').addClass( "invisible" );
		
		$('#CPred input').val('');
		$('#NPred input').val('');
		$('#RPred input').val('');
		$('#DPred input').val('');
	}
});
$('#rdCriterioPred4').click(function(){
	
	if($('#rdCriterioPred4').attr('checked'))
	{
		$('#txtCriterio').val('');
		$('#txtCriterio').focus();
		$("#txtCriterio").attr('maxlength','11');
		
		$('#CPred').addClass( "invisible" );
		$('#NPred').addClass( "invisible" );
		$('#RPred').addClass( "invisible" );
		$('#DPred').removeClass( "invisible" );
		
		$('#CPred input').val('');
		$('#NPred input').val('');
		$('#RPred input').val('');
		$('#DPred input').val('');
	}
});

$('#txtCriterioPredio').blur(function(){
    var numCeros = '0000000'; // pon el n� de ceros que necesites
    var valor =  $('#txtCriterioPredio').val().trim();
    var radio = $('#rdCriterio1Predio').val();
   // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterioPred1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    { 
    	//$('#txtCriterio').val(valor);
    	  var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
  	    $('#txtCriterioPredio').val(valor2);  //obj.value = valor;
    }
    else{
    	$('#txtCriterioPredio').val(valor);
    }
    
});

function changeDisContri(idDis){
	if(idDis=='003'){
		$('#rowUrbOtherDis').hide();
		$('#rowViaOtherDis').hide();
		
		$('#rowZonaDis').show();
		$('#rowUrbDis').show();
		$('#rowViaDis').show();
	}
	else{
		$('#rowZonaDis').hide();
		$('#rowUrbDis').hide();
		$('#rowViaDis').hide();
		
		$('#rowUrbOtherDis').show();
		$('#rowViaOtherDis').show();
	}
}

function pagotupaNuevo(){
	showPopup('tesopagosdiv/index?codigo=9999999&nombre=','#popPagosDiv','800','400','Pagos Diversos');
}

