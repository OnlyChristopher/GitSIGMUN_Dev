Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();
	$("#btncrearPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btnbajarPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btneditaPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btnCopiaPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btnCopiaRentas").button({icons:{primary:"ui-icon-print"}});

   Ext.define('xRequerimientoModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idPu', type: 'string'},
				{name: 'tipopu', type: 'string'},
				{name: 'codigopu', type: 'string'},
				{name: 'ubicacion', type: 'string'},
				{name: 'autovaluo', type: 'string'},
				{name: 'porcen', type: 'string'},
				{name: 'totalautovaluo', type: 'string'},
				{name: 'tot_autoavaluo', type: 'string'},
				{name: 'base_imponible', type: 'string'},
				{name: 'impanual', type: 'string'},
        ]
    });

    var xRequerimientoStore = Ext.create('Ext.data.Store', {
    	model: 'xRequerimientoModel',
    	autoLoad: false,
        proxy: {
            type: 'ajax',
            url : 'fiscalizacionpredio/gridpredio',
			extraParams: {
				txtidre  : $('#txtidre').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
	
    var xgridRequerimiento = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPredios',
        store: xRequerimientoStore,
		height: '300px',
		//width: '100px',
        //title: 'Requerimiento',
		columnLines: true,
        viewConfig: {
        	//loadMask: {msg: 'cargando...'}
			loadMask: false
		},
        enableColumnHide : false,
        enableColumnMove : false,
        enableDragDrop : false,
        enableHdMenu : false,
		/*selModel: Ext.create('Ext.selection.CheckboxModel',{
        	mode: 'MULTI',
        	checkOnly: true
        }),*/
        columns: [{
            text: 'idx',
            width: 70, 
			hidden: true,
            dataIndex: 'idPu',
			fixed: true,
			align:'LEFT',
			sortable: false
        },{
            text: 'Tipo',
            width: 35,
            dataIndex: 'tipopu',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
        	text: 'C&oacute;digo de Predio',
        	width: 67,
            dataIndex: 'codigopu',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
			id: 'topic',
            text: 'Ubicaci&oacute;n',
            flex: 1,
            dataIndex: 'ubicacion',
			fixed: true,
			align:'LEFT',
			renderer: function(value, metaData, record, rowIdx, colIdx, store) {
				metaData.tdAttr = 'data-qtip="' + value + '"';
				return value;
			},
			sortable: false
        },{
            text: 'Autoval&uacute;o',
            width: 91,
            dataIndex: 'autovaluo',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('autovaluo'),'0,000.00');
            }
        },{
            text: '%',
            width: 47,
            dataIndex: 'porcen',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('porcen'),'0,000.00');
            }
        },{
            text: 'Total Autoval&uacute;o',
            width: 91,
            dataIndex: 'totalautovaluo',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('totalautovaluo'),'0,000.00');
            }
        }],
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
				ModificarPredio();
            }
		}
    });
    xgridRequerimiento.render('divGridConstrucciones');
	MostrarPredios();
});

function MostrarPredios(){
	
	var grid = Ext.getCmp('xgridPredios');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	proxy.extraParams = {
				txtidre  : $('#txtidre').val()
			};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			count = this.getCount();
			//total = this.getTotalCount();
			//console.log(count);
			//console.log(total);
			$('#txtautova').val('0.00');
			$('#txtbaseimp').val('0.00');
			$('#txtimppred').val('0.00');
			
			if( count > 0 ){
				store.each(function (rec){
					txtautova= rec.get('tot_autoavaluo');
					txtbaseimp= rec.get('base_imponible');
					txtimppred= rec.get('impanual');
				});
			
				$('#txtautova').val(Ext.util.Format.number(txtautova,'0,000.00'));
				$('#txtbaseimp').val(Ext.util.Format.number(txtbaseimp,'0,000.00'));
				$('#txtimppred').val(Ext.util.Format.number(txtimppred,'0,000.00'));
			}
		}
	});	
}

function NuevoHResumen(){
	var getvalue = $('#txtidrq').val();
	//var getvalue = $('#txtDatos').val();
	showPopup('fiscalizaciondetalle/hresumen?idrq='+getvalue+'&txtaction=1','#pophresumen','340','400','Hoja de Resumen ( HR )');		
}

function ModificarHResumen(){
	var getvalue  = $('#txtidrq').val();
	var txtidre   = $('#txtidre').val();
	
	if(txtidre == ''){
		infoMessage('Requerimiento','No existe hoja de resumen...');
		return;
	}
	
	showPopup('fiscalizaciondetalle/hresumen?idrq='+getvalue+'&txtaction=2&txtidre='+txtidre,'#pophresumen','340','400','Hoja de Resumen ( HR )');		
}

function EliminarHResumen(){
	var getvalue  = $('#txtidrq').val();
	var txtidre   = $('#txtidre').val();
	var txtaniohr   = $('#txtaniohr').val();
	
	if(txtidre == ''){
		infoMessage('Requerimiento','No existe hoja de resumen...');
		return;
	}
	
	var msgpago='Se eliminara toda la hoja de resumen del a&ntilde;o '+txtaniohr+', Seguro de Continuar?';
	var showResult = function(btn){
		if(btn=='yes'){
			$.ajax({
				type: "POST",  
				url: urljs + "fiscalizaciondetalle/eliminarresumen",
				data: { getvalue: getvalue, txtidre : txtidre},
				//dataType : 'json',
				success: function(data){
					//console.log(data);
					RecargarTree();
				},     
				error: function(data) {
					infoMessage('Error',data);
				}
			});
		}
	};
	confirmMessage('SIGMUN',msgpago,showResult);
}

function CrearPredio(){
	
	var getvalue = $('#txtidre').val();
	
	if(getvalue !== ''){
		showPopup('fiscalizacionpredio/index','#popcrearpredio','340','120','Crear Predio...');
	}else{
		infoMessage('Requerimiento','Crear HR antes registrar Predios..');
	}
}

function ModificarPredio(){
	var grid = Ext.getCmp('xgridPredios');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPu');
		showPopup('fiscalizacionpredio/modificarpredio?idpu='+getvalue,'#popmodificarpredio','770','620','Modificar Predio...');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function EliminarPredio(){

	var grid = Ext.getCmp('xgridPredios');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idPu');
		var getcodpre = selectedRecords.get('codigopu');
		var txtaniohr   = $('#txtaniohr').val();
		var txtidre   = $('#txtidre').val();
		
		var msgpago='Se esta dando de baja el Predio con codigo '+getcodpre+' del a&ntilde;o '+txtaniohr+', Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/eliminarpredio",
					data: {idpu: getvalue, idre: txtidre},
					//dataType : 'json',
					success: function(data){
						MostrarPredios();
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Predio...','Seleccione el Predio...');
	}
}

function CopiarPredio(){

	var adesde   = $('#txtdesde').val();
	var ahasta   = $('#txthasta').val();
	var aorige   = $('#txtorigen').val();
	var stranios = '';
	
	for (var i = adesde, limit = ahasta; i <= limit; i++) {
		stranios = stranios+i+',';
    }
	
	var grid = Ext.getCmp('xgridPredios');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getidpu = selectedRecords.get('idPu');
		var getcodpre = selectedRecords.get('codigopu');
		var txtaniohr   = $('#txtaniohr').val();
		var txtidrq   = $('#txtidrq').val();
		
		var msgpago='Se realizara una copia de los predios seleccionados a los a&ntilde;os '+stranios+' Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/copiapredios",
					data: {getidpu: getidpu, stranios: stranios, txtidrq: txtidrq, aorige: aorige},
					//dataType : 'json',
					success: function(data){
						MostrarPredios();
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Predio...','Seleccione el Predio...');
	}
}

function CopiarPredioRentas(){

	var aorige   = $('#txtaniodecla').val();
	var codigo   = $('#txtmcodigo').val();
	var xidhre   = $('#txtidre').val();
	var txtidrq   = $('#txtidrq').val();
	
	if(aorige.length > 0){
		
		var msgpago='Se realizara una copia de todos los predios declarados el a&ntilde;os '+aorige+' Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/copiaprediosrentas",
					data: {aorige: aorige, codigo: codigo, xidhre: xidhre, txtidrq: txtidrq},
					//dataType : 'json',
					success: function(data){
						MostrarPredios();
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Predio...','Seleccione el a&ntilde;o...');
	}
}

function GenerarResolucion(){

	var txtidrq   = $('#txtidrq').val();
	
	if(txtidrq.length > 0){
		
		var msgpago='Se va generar la Resoluci&oacute;n de Determinaci&oacute;n, Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizaciondetalle/generaresolucion",
					data: {txtidrq: txtidrq},
					//dataType : 'json',
					success: function(data){
						infoMessage('SIGMUN','Se genero con existo la Resoluci&oacute;n de Determinaci&oacute;n Nro.'+data);
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Predio...','Seleccione el Requerimiento...');
	}
}


function GenerarResolucionmt(){

	var txtidrq   = $('#txtidrq').val();
	var txtidbl   = $('#comboMT').val();
	
	if(txtidrq.length > 0){
		
		var msgpago='Se va generar la Resoluci&oacute;n de Determinaci&oacute;n, Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizaciondetalle/generaresolucionmt",
					data: {txtidrq: txtidrq, txtidbl: txtidbl},
					//dataType : 'json',
					success: function(data){
						infoMessage('SIGMUN','Se genero con existo la Resoluci&oacute;n de Determinaci&oacute;n Nro.'+data);
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Predio...','Seleccione el Requerimiento...');
	}
}