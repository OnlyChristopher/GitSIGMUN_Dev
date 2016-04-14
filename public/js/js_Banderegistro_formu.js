disableButton('#btnBusPredio');

$(function(){
	
	$("input[type='button']").button();
	
	// $.validator.addMethod("num_tribu", function(value, element) {
		
		// var params = "txtNumvalor="+$('#txtNumvalor').val();
		// return callAjax('banderegistro/validanumtribu',params,'','param');
		
	// }, "Este Nº Valor ya exite" );
	
	
	
	
	// $.validator.addMethod("tipo_tribu", function(value, element) {
		
		// var params = "cmbvalor="+$('#cmbvalor').val();
		// return callAjax('banderegistro/validatipo',params,'','param');
		
	// }, "Este valor ya existess" );
	

	$('#frmBanderegistro').validate({
		rules: {		
		//'txtItem': 'required',
		'txtNumarchi': 'required',		
		'txtCodcontrib': 'required',	
		'txtNombre': 'required',	
		'txtDomifis': 'required',	
		//'txtSector': 'required',	
		//'txtDomipre': 'required',	
		'anios_deuda': 'required',
		'txtPeriodo': 'required',
		'cmbvalor': 'required',
		'cmbEstado': 'required',
		'txtNumexpedi': 'required',
		'txtAnoexpedien': 'required',
		'txtMonto': 'required',
		'txtNumvalor': 'required',
		//'txtFechrec': 'required',
		//'txtObservacion': 'required'

		},
		messages: {		
		//'txtItem': 'Debe ingresar un item',
		'txtNumarchi': 'Debe ingresar un numero',
		'txtCodcontrib': 'Debe ingresar un codigo contribuyente',
		'txtNombre': 'Debe ingresar un nombre',
		'txtDomifis': 'Debe ingresar una direccion fiscal',
		//'txtSector': 'Debe ingresar un sector',
		//'txtDomipre': 'Debe ingresar una direccion predial',
		'anios_deuda': 'Debe seleccionar el año',
		'txtPeriodo': 'Debe ingresar el periodo',  
		'cmbvalor':  'Debe seleccionar el valor',
		'cmbEstado': 'Debe seleccionar un estado',
		'txtNumexpedi': 'Debe ingresar un mumero expediente',
		'txtAnoexpedien': 'Debe ingresar el año expediente',
		'txtMonto': 'Debe ingresar un momto',
		'txtNumvalor': 'Debe ingresar el num valor'
		//'txtFechrec': 'Debe seleccionar la fecha',
		//'txtObservacion': 'Debe ingresar una observacion'
		
		},
		debug: true,
		errorElement: 'div',
		//errorContainer: $('#errores'),
		submitHandler: function(form){	

					data = $("#frmBanderegistro").serializeObject();
				
				var dataConst = new Array();			
				var gridConst = Ext.getCmp('xgridDetalle');
				if(gridConst.getStore().data.length>0){
					var i=0;
					gridConst.getStore().each(function(rec) {
						var rowsConst = {
							idDetalle: rec.get('idDetalle'),
							num_archivador: rec.get('num_archivador'),
							idregistro: rec.get('idregistro'),
							tipo_tributo: rec.get('tipo_tributo'),
							anno: rec.get('anno'),
							insoluto: rec.get('insoluto'),
							reajuste: rec.get('reajuste'),
							costo_emision: rec.get('costo_emision'),
							mora: rec.get('mora'),
							periodo: rec.get('periodo'),
							total: rec.get('total')
						}
						dataConst[i] = rowsConst;
						i++;
					});
					
					data['Const'] = dataConst;
				}

				Ext.Ajax.request({
				  url: urljs + "banderegistro/grabar",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Alerta',response.responseText +' Se Registro Correctamente');
					  closePopup('#popRegistro');
					  Ext.getCmp('xgridRegistro').getStore().reload();
				  }
				});


		}
	});
	
	
eventConst('C');
loadGridDetalle();
redimGridHidden('xgridDetalle',600,100,2);
	
});


window.muestraDatosContricoac = function(obj) { 
	 $('#txtCodcontrib').val(obj.get('codigo'));
	 $('#txtNombre').val(obj.get('nombres'));
	 $('#txtDomifis').val(obj.get('direccionfiscal'));
	 $('#txtNumdocu').val(obj.get('documento'));
};

window.muestraDatosContripredio = function(obj) { 
	 $('#txtDomipre').val(obj.get('direccionpredio'));
	 $('#txtSector').val(obj.get('zona'));
};




$('.caja').blur(function(event){
		this.value = this.value.toUpperCase();
});


$('#txtNumexpedi').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtNumexpedi').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtNumexpedi').val(valor2);  //obj.value = valor;
        
});

// $('#txtMora').blur(function(){
	// var total=Number($('#txtInsoluto').val())+Number($('#txtReajuste').val())+Number($('#txtCosto_emision').val())+Number($(this).val());
	// var total_redondeo=Number(total).toFixed(2);
        // $('#txtTotal').val(total_redondeo);
        
// });



$('#txtNumarchi').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtNumarchi').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtNumarchi').val(valor2);  //obj.value = valor;
        
});

$('#txtItem').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtItem').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtItem').val(valor2);  //obj.value = valor;
        
});

$('#txtNumvalor').blur(function(){
        var numCeros = '0000000'; // pon el nº de ceros que necesites
        var valor =  $('#txtNumvalor').val();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtNumvalor').val(valor2);  //obj.value = valor;
        
});


/***DETALLE***/

function loadGridDetalle(){

	$('#gridDetalle').html('');

	var idregistro=$('#txtCodregistro').val();	
		
	Ext.define('Detalle', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idDetalle', type: 'string'},
			{name: 'num_archivador', type: 'string'},
			{name: 'idregistro', type: 'string'},
			{name: 'tipo_tributo', type: 'string'},
			{name: 'anno', type: 'string'},
			{name: 'periodo', type: 'string'},
			{name: 'insoluto', type: 'string'},
			{name: 'reajuste', type: 'string'},
			{name: 'costo_emision', type: 'string'},
			{name: 'mora', type: 'string'},
			{name: 'total', type: 'string'},
			{name: 'nom_tribu', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Detalle',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridDetalle',
		columnLines: true,
		store: store,
		width: '750px',
		height: '120px',        
		viewConfig: {
        	listeners: {   
				itemclick: 
            	function(s,r) {
					//
					
				},
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "Banderegistro/detalleconsulta",
						  method: "POST",
						  params: {idregistro: idregistro},
						  success: function(response){	
//alert(response.responseText);						  
							  	data = Ext.JSON.decode(response.responseText);
								//var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									//strItems = strItems + data[i].idpisos + '|';
									addRowsConst(data[i]);
								}
								//Para ver si se elimina
							//	$('#oldConItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {	text: 'idDetalle',
            dataIndex: 'idDetalle',
			hidden: true
        },{
			text: 'num_archivador',
            dataIndex: 'num_archivador',
			hidden: true
        },{
			text: 'idregistro',
            dataIndex: 'idregistro',
			hidden: true
        },{
			text: 'Tipo Trib. ',
			width: 80,
			dataIndex: 'tipo_tributo',
			hidden:true
		},{
			text: 'Tributo ',
			width: 80,
			dataIndex: 'nom_tribu'
		},{
			text: 'A&ntilde;o',
            width: 70,
            dataIndex: 'anno'
        },{
			text: 'Periodo',
            width: 150,
            dataIndex: 'periodo'
        },{
			text: 'Insoluto',
            width: 90,
            dataIndex: 'insoluto',
			hidden: true			
        },{
			text: 'Reajuste',
            width: 90,
            dataIndex: 'reajuste',
			hidden: true
        },{
			text: 'Costo. Emi.',
            width: 90,
            dataIndex: 'costo_emision',
			hidden: true
        },{
			text: 'Mora',
            width: 100,
            dataIndex: 'mora',
			hidden: true
        },{
			text: 'Total',
            width: 100,
            dataIndex: 'total'
        }]
    });
    
    grid.render('gridDetalle');
//	grid.setDisabled(true);
}

function addRowsConst(data){
	var grid = Ext.getCmp('xgridDetalle');
	var store = grid.getStore();
	var r = Ext.create('Detalle', {

						idDetalle: data.idDetalle,
						num_archivador: data.num_archivador,
						idregistro: data.idregistro,
						tipo_tributo: data.tipo_tributo,
						anno: data.anno,
						insoluto: data.insoluto,
						reajuste: data.reajuste,
						costo_emision: data.costo_emision,
						mora: data.mora,
						periodo: data.periodo,
						total: data.total,
						nom_tribu: data.nom_tribu
						
	});
	store.insert(store.data.length, r);
}

function eventConst(act){
	switch(act){
		case 'A':
			
			$("input[type='button']").button();
			$('#divAddConst').show();
			$('#rowSaveConst').show();
			$('#rowCancelConst').show();
			$('#rowAddConst').hide();
			$('#rowEditConst').hide();
			$('#btnDelConst').hide();
			
			$("#actConst").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridDetalle');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventConst('A');
				
				$("#idDetalle").val(rec.get('idDetalle'));
				$("#num_archivador").val(rec.get('num_archivador'));
				$("#idregistro").val(rec.get('idregistro'));
				$("#num_archivador").val(rec.get('nropiso'));
				$("#txtPeriodo").val(rec.get('periodo'));
				$("#txtInsoluto").val(rec.get('insoluto'));
				$("#txtReajuste").val(rec.get('reajuste'));
				$("#txtCosto_emision").val(rec.get('costo_emision'));
				$("#txtMora").val(rec.get('mora'));
				$("#txtTotal").val(rec.get('total'));
				
				$("#cmb_anno option[value="+ rec.get('anno') +"]").attr("selected",true);
				$("#cmb_tributo option[value="+ rec.get('tipo_tributo') +"]").attr("selected",true);
				
				//$("#cmb_tributo option[value="+ rec.get('nom_tribu') +"]").attr("selected",true);
				
				
				
				
				
				
				$("#actConst").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddConst').hide();
			$('#rowAddConst').show();
			$('#rowEditConst').show();
			$('#rowSaveConst').hide();
			$('#rowCancelConst').hide();
			$('#btnDelConst').show();
			
			
				$("#idDetalle").val('');
				$("#num_archivador").val('');
				$("#idregistro").val('');
				$("#num_archivador").val('');
				$("#txtPeriodo").val('');
				$("#txtInsoluto").val('0.00');
				$("#txtReajuste").val('0.00');
				$("#txtCosto_emision").val('0.00');
				$("#txtMora").val('0.00');
				$("#txtTotal").val('');
				
			$('#cmb_anno option:first').attr('selected', true);
			$('#cmb_tributo option:first').attr('selected', true);
			//$('#cmb_tributo option:first').attr('selected', true);
			
		break;
		case 'S':
			var grid = Ext.getCmp('xgridDetalle');
			var store = grid.getStore();
			var retorno="";
			  
			var array='true';
		
			if(array=='true'){
				if($("#actConst").val()=='A')
				{
					var r = Ext.create('Detalle', {
						idDetalle: $('#idDetalle').val(),
						num_archivador: $('#num_archivador').val(),
						idregistro: $('#idregistro').val(),
						tipo_tributo: $('#cmb_tributo').val(),
						anno: $('#cmb_anno').val(),
						insoluto: $('#txtInsoluto').val(),
						reajuste: $('#txtReajuste').val(),
						costo_emision: $('#txtCosto_emision').val(),
						mora: $('#txtMora').val(),
						periodo: $('#txtPeriodo').val(),
						total: $('#txtTotal').val(),
						nom_tribu: $('#cmb_tributo option:selected').text()
						
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idDetalle', $('#idDetalle').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('num_archivador', $('#num_archivador').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('idregistro', $('#idregistro').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('tipo_tributo', $('#cmb_tributo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('anno', $('#cmb_anno').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('insoluto', $('#txtInsoluto').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('reajuste', $('#txtReajuste').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('costo_emision', $('#txtCosto_emision').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mora', $('#txtMora').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('periodo', $('#txtPeriodo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('total', $('#txtTotal').val());
					
					grid.getStore().getAt(store.indexOf(sm[0])).set('nom_tribu', $('#cmb_tributo option:selected').text());
					
					grid.getStore().sync();				
				}
				
				
				var grid2 = Ext.getCmp('xgridDetalle');
				var store2 = grid.getStore();
				var txt_totalmonto=0;
				
				store2.each(function (rec){
	    		
				txt_totalmonto = txt_totalmonto+parseFloat(rec.get('total'));
				
				});
				$('#txtMonto').val(Number(txt_totalmonto).toFixed(2));
				
				
				
				
				eventConst('C');
			}
				
		break;
		case 'D':
		
		
		var grid = Ext.getCmp('xgridDetalle');
		var store = grid.getStore();	
		
	
		//var idDetalle = "";
		
		
		if (grid.getSelectionModel().selected.length == 1){
		
			
		 var showResult = function(btn){
			if(btn=='yes'){
						var sm = grid.getSelectionModel().getSelection();
						var rec = store.getAt(store.indexOf(sm[0]));
						var cad=rec.get('idDetalle');
						
						if(cad==''){
							store.remove(grid.getSelectionModel().getSelection());		
							infoMessage('Eliminar','Registro Eliminado!');
							calcRegistro();
						}
						else{
						Ext.Ajax.request({
						  url: urljs + "Banderegistro/eliminar",
						  method: "POST",
						  params: {idDetalle: cad},
						  success: function(response){	
							  	data = response.responseText;
								if(data=='1'){
									//var dm = grid.getSelectionModel();
									store.remove(grid.getSelectionModel().getSelection());	
									infoMessage('Eliminar','Registro Eliminado!');
								}		
								calcRegistro();
						  }
						});
						}
						
						//Ext.getCmp('xgridDetalle').getStore().load();
						
						}
			};
 		
 		confirmMessage('Alerta','Seguro de eliminar el concepto?',showResult);
		
		}
		break;
	}
}



function calcRegistro(){

	var txt_totalmonto=0;
	var grid2 = Ext.getCmp('xgridDetalle');

	
	if(grid2.getStore().data.length>0){
	
		grid2.getStore().each(function(rec) {
		
			txt_totalmonto = txt_totalmonto+parseFloat(rec.get('total'));

		});
		
	}

	//alert(txt_totalmonto);
	
	$('#txtMonto').val(Number(txt_totalmonto).toFixed(2));	
		
	//Ext.getCmp('xgridDetalle').getStore().load();
	
	
}





















