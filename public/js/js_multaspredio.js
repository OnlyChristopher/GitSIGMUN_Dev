Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
	});
	
    $("input[type='button']").button();

    Ext.define('Multastribu', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'anno', type: 'string'},
            {name: 'codpred', type: 'string'},
            {name: 'anexo', type: 'string'}, 
			{name: 'subanexo', type: 'string'},
			{name: 'direccion', type: 'string'}
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'Multastribu',
        autoLoad: false,
        pageSize: 10,
        proxy: {
            type: 'ajax',
            url : 'multastributarias/consultamulta',
			//url : 'multasadmin/consultamulta',
            reader: {
                type: 'json',
                root: 'rowsMulta'
            }
        }
    });

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridMultastribu',
        store: store,
        //title: 'Contribuyentes',
        //viewConfig: {
            //loadMask: {msg: 'cargando...'}
        //},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
		selModel: Ext.create('Ext.selection.CheckboxModel',{
				   mode : 'SINGLE' 
					}),
        columns: [
            Ext.create('Ext.grid.RowNumberer'),
            {
                text: 'C&oacute;digo',
                width: 60,
                dataIndex: 'codigo'
            },{
                text: 'Año',
                //flex: 1,
				width: 50,
                dataIndex: 'anno'
            },{
                text: 'Codigo Predio',
                //flex: 1,
				width: 100,
                dataIndex: 'codpred'
            },{
                text: 'Anexo',
                width:70,
                dataIndex: 'anexo'
            },{
                text: 'Sub Anexo',
                width: 70,
                dataIndex: 'subanexo'
            },{
                text: 'Direccion Predio',
                width: 460,
                dataIndex: 'direccion'
            }]
    });

    grid.render('gridMultastribu');
   
  Ext.define('Multa', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'anno'},
            {name: 'tributo', type: 'string'},
            {name: 'subtributo', type: 'string'},
            {name: 'periodo', type: 'string'},
			{name: 'fecha', type: 'string'},
			{name: 'importe', type: 'string'},
			{name: 'emision', type: 'string'},
			{name: 'subtotal', type: 'string'},
			{name: 'observ', type: 'string'},
			{name: 'tributohide', type: 'string'},
			{name: 'subtributohide', type: 'string'},
			{name: 'fechavenci', type: 'string'}
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'Multa',
        autoLoad: false,
        pageSize: 10,
        proxy: {
            type: 'ajax',
            url : 'multastributarias/consultamulta',
			//url : 'multasadmin/consultamulta',
            reader: {
                type: 'json',
                root: 'rowsMulta'
            }
        }
    });
/////////////////////////////////// AGREGADO CASIMIRO*/// CHECK  EN LA GRILLA////
	/*selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'Multa',
        	checkOnly: true,
			showHeaderCheckbox :false
        }),*/
/////////////////////////////////// AGREGADO CASIMIRO*/// CHECK  EN LA GRILLA//

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridMulta',
        store: store,
        //title: 'Contribuyentes',
        //viewConfig: {
            //loadMask: {msg: 'cargando...'}
        //},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
		selModel: Ext.create('Ext.selection.CheckboxModel',{
				   mode : 'SINGLE' 
					}),
		    columns: [
            Ext.create('Ext.grid.RowNumberer'),
            {
                text: 'Año',
                width: 45,
                dataIndex: 'anno'
            },{
                text: 'Tributo',
                //flex: 1,
				width: 70,
                dataIndex: 'tributo'
            },{
                text: 'Sub Tributo',
                //flex: 1,
				width: 120,
                dataIndex: 'subtributo'
            },{
                text: 'Periodos',
                width:60,
                dataIndex: 'periodo'
            },{
                text: 'Fecha Vencimiento',
                width: 120,
                dataIndex: 'fechavenci'
				
			},{
                text: 'Importe',
                width: 60,
                dataIndex: 'importe'
				
			},{
                text: 'Der.Emisi&oacute;n',
                width: 100,
                dataIndex: 'emision'
				
			},{
                text: 'Sub Total',
                width: 100,
                dataIndex: 'subtotal'
				
			},{
                text: 'Observ.',
                width: 110,
                dataIndex: 'observ'
				
            },{
                text: 'Fecha Registro',
                width: 120,
                dataIndex: 'fecha'
				
			},{
                text: 'TributoHide',
                //flex: 1,
				width: 70,
				hidden: true,
                dataIndex: 'tributohide'
            },{
                text: 'Sub TributoHide',
                //flex: 1,
				width: 120,
				hidden: true,
                dataIndex: 'subtributohide'
            }]
    });

    grid.render('gridMulta');
       
   $.ajax({     
		type: "POST",     
		url: "multastributarias/fecha",
		//data: '',
		success: function(data) { 
			$('#txtSalida').val(data);			
			//alert(data);
		}
	});
	
	$('#frmultedit').validate({
		rules: {
	},
		messages: {
	},
		submitHandler: function(form){
			
			var carga='true/Guardando Datos';
			var carga=validadatos();
		
			validar=carga.split('/');
			
			if (validar[0]=='false')
			{
				errorMessage('Error',validar[1]);
			}
			else
			{
				data = $("#frmultedit").serializeObject();
				
				var dataMultastribu = new Array();			
				var gridMultastribu = Ext.getCmp('xgridMultastribu');
				
				var selectedRecordsArray = gridMultastribu.getView().getSelectionModel().getSelection();
				
				if(selectedRecordsArray.length>0){
					var idMulta = [];
					
					Ext.each(selectedRecordsArray, function (item) {
					idMulta.push(
						{
							codigo:item.data.codigo,
							codpred: item.data.codpred, 
							anexo:item.data.anexo,
							subanexo:item.data.subanexo
						});
					});
					
					 data['Multribu'] = idMulta;
				}
								
				var dataMultas = new Array();			
				var gridMultas = Ext.getCmp('xgridMulta');
				if(gridMultas.getStore().data.length>0){
					var i=0;
					gridMultas.getStore().each(function(rec) {
						var rowsMultas = {
							anno: rec.get('anno'),
							tributohide: rec.get('tributohide'),
							subtributohide: rec.get('subtributohide'),
							importe: rec.get('importe'),
							emision: rec.get('emision'),
							subtotal: rec.get('subtotal'),
							fecha: rec.get('fecha'),
							periodo: rec.get('periodo'),
							observ: rec.get('observ'),
							fechavenci: rec.get('fechavenci')
						}
						dataMultas[i] = rowsMultas;
						i++;
					});
					
					data['Mult'] = dataMultas;
				}
											
				Ext.Ajax.request({
				  url: urljs + "Multastributarias/gmultas",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Geenerar Multas',response.responseText +' Se Registro Correctamente');
					  closePopup('#popupMultas');
					 // Ext.getCmp('xgridMulta').getStore().reload();
				  }
				});

			}
		}
	});
});

function cargarMulta(){
	var grid = Ext.getCmp('xgridMultastribu');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var divcodigo = $('#divCodigo').html();
	var cmbAnio = $('#anios_deuda').val();
	
	proxy.extraParams = {divcodigo: divcodigo, cmbAnio: cmbAnio};
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});
}


$('#cmbImporteanual').change(function(){
    var tipomulta=($(this).val());
//$('#txtAreades').val(areadestino);
	
	    
    $.ajax({     
		type: "POST",     
		url: "multastributarias/consubtribu",
		data: 'id_tipomulta='+tipomulta,
		success: function(data) { 
			$('#cmbSubtributo').html(data);		
			//alert(data);
		}
	});
});


$('#anios_deuda').change(function(){
    var anno_venci=($(this).val());
	var f = new Date();
	$('#txtfechavenci').val("01/03/" + anno_venci);		
});

function eventConst(act){
	switch(act){
		case 'S':
			 var grid = Ext.getCmp('xgridMulta');
			 var store = grid.getStore();
			 var retorno="";
				
				retorno=EvaluarMulta();
				array=retorno.split('/');
			/*	var valor=retorno.substr(0,5);
				var mensaje=retorno.substr(6);*/
				
				if(array[0]=='true'){
					
					var r = Ext.create('Multa', {
						anno: $('#anios_deuda').val(),
						tributo: $('#cmbImporteanual option:selected').text(),
						subtributo: $('#cmbSubtributo option:selected').text(),
						observacion: $('#observacion').val(),
						emision: $('#der_emision').val(),
						observ:$('#observacion').val(),
						importe: $('#cant').val(),
						fecha:$('#txtSalida').val(),
						periodo:$("input[name*='chkPeriodo']:checked:checked").val(),
						tributohide: $('#cmbImporteanual').val(),
						subtributohide: $('#cmbSubtributo').val(),
						fechavenci:$('#txtfechavenci').val(),
						subtotal:parseFloat($('#cant').val()) + parseFloat($('#der_emision').val()) 
					});
					
					store.insert(store.data.length, r);	
				}
				else {
					infoMessagex('Guardar Multa',array[1],array[2]);
				}
		break;
		case 'D':
			var grid = Ext.getCmp('xgridMulta');
			var store = grid.getStore();			
			if(store.data.length>0){
				var showResult = function(btn){
					if(btn=='yes'){
						var sm = grid.getSelectionModel();
						store.remove(sm.getSelection());
					}
				};
				confirmMessage('Eliminar','Desea eliminar el registro seleccionado?',showResult);
			}
		break;
	}
}

function valida_multas(anno,tributo,subtributo,importe,observ){
	var retorno='true/Datos Correctos';

	if(anno==''){
		retorno='false/Debe Seleccionar el Año de la Deuda/#anios_deuda';
		return(retorno);
	}
	if(tributo==''){
		retorno='false/Debe Seleccionar el Tributo/#cmbImporteanual';
		return(retorno);
	}
	if(subtributo==''){
		retorno='false/Debe Seleccionar un Sub Tributo/#cmbSubtributo';
		return(retorno);
	}
	if(importe=='' || parseFloat(importe)<=0 || !IsNumeric(importe)){
		retorno='false/Debe Ingresar un importe  mayor a 0.00/#cant';
		return(retorno);
	}
	if(observ==''){
		retorno='false/Debe Ingresar una observacion/#observacion';
		return(retorno);
	}
	return(retorno);
}



function EvaluarMulta(){
	
	var anno=$('#anios_deuda').val();
	var tributo = $('#cmbImporteanual').val();
	var subtributo = $('#cmbSubtributo').val();
	var importe = $('#cant').val();
	var observ = $('#observacion').val();	
	var cad="";
	cad=valida_multas(anno,tributo,subtributo,importe,observ);
	//alert(cad);
	return cad;
}

function validadatos(){
	var anios_deuda = $('#anios_deuda').val();
	var cmbImporteanual = $('#cmbImporteanual').val();

	var retorno='true/Guardando Datos';
	
	var gridMultasTribu = Ext.getCmp('xgridMultastribu');
	var selRecordsMultasTribu = gridMultasTribu.getView().getSelectionModel().getSelection();
				
	var gridMulta = Ext.getCmp('xgridMulta');
	var selRecordsMulta = gridMulta.getView().getSelectionModel().getSelection();
	
	if(anios_deuda==""){
		retorno='false/Debe Seleccionar Año de la deuda';		
	}
	else
	if (cmbImporteanual==""){
		retorno='false/Debe Seleccionar el Tributo';
	}
	else
	if(selRecordsMultasTribu.length<=0){
		retorno='false/Debe Seleccionar el predio';
	}
	else
	if(selRecordsMulta.length<=0){
		retorno='false/Debe Seleccionar la multa generada';
	}
	
	return(retorno);
}

function Tributaria(){
	var codigo=$('#codigo').val();
	showPopup('multastributarias/multas?codigo='+codigo,'#popupVerMultas','900','500','Listado de Multas Tributarias');
}









