Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();

	$("#btnGrabarPredio").button({icons:{primary:"ui-icon-print"}});
	$("#btnCerrarPredio").button({icons:{primary:"ui-icon-print"}});

	$("#btnGrabarPredio").click(function(){
		goToFormulario("formModificarPredio");
	});
	//Valida y envia form contribuyente
		
	$('#formModificarPredio').validate({
		rules: {
			'txtcodigopu': 'required'
		},
		messages: {
			'txtcodigopu': 'Debe seleccionar el contribuyente'
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/grabarpredio",
					data: $('#formModificarPredio').serializeObject(),
					dataType : 'json',
					success: function(data){
						var getvalue = $('#txtidpu').val();
						closePopup('#popmodificarpredio');						
						showPopup('fiscalizacionpredio/modificarpredio?idpu='+getvalue,'#popmodificarpredio','770','620','Modificar Predio...');
						MostrarPredios();
					},     
					error: function(data) {
						infoMessage('Error',data);
					} 
				});
			}
		
	});
	
	/*Grid Pisos */
   Ext.define('xPisosModel', {
        extend: 'Ext.data.Model',
        fields: [
				{name: 'idxxx', type: 'string'},
				{name: 'nivel', type: 'string'},
				{name: 'cx', type: 'string'},
				{name: 'mx', type: 'string'},
				{name: 'ex', type: 'string'},
				{name: 'anio', type: 'string'},
				{name: 'u1', type: 'string'},
				{name: 'u2', type: 'string'},
				{name: 'u3', type: 'string'},
				{name: 'u4', type: 'string'},
				{name: 'u5', type: 'string'},
				{name: 'u6', type: 'string'},
				{name: 'u7', type: 'string'},
				{name: 'area_const', type: 'string'},
				{name: 'valo_unita', type: 'string'},
				{name: 'valo_areas', type: 'string'},
				{name: 'area_comun', type: 'string'},
				{name: 'valo_total', type: 'string'},
        ]
    });

    var xPisosStore = Ext.create('Ext.data.Store', {
    	model: 'xPisosModel',
    	autoLoad: true,
        proxy: {
            type: 'ajax',
            url : 'fiscalizacionpredio/gridpisos',
			extraParams: {
				txtidpu  : $('#txtidpu').val()
			},
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
	
    var xgridPisos = Ext.create('Ext.grid.Panel', {
    	id: 'xgridPisos',
        store: xPisosStore,
		height: '138px',
		//width: '100px',
        //title: 'Requerimiento',
		columnLines: true,
        viewConfig: {
        	//loadMask: {msg: 'Cargando...'},
			loadMask: false
		},
        enableColumnHide : false,
        enableColumnMove : false,
        enableDragDrop : false,
        enableHdMenu : false,
        columns: [{
            text: 'idxxx',
            width: 70, 
			hidden: true,
            dataIndex: 'idxxx',
        },{
            text: 'Piso',
            width: 35,
            dataIndex: 'nivel',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
        	text: 'C',
        	width: 18,
            dataIndex: 'cx',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'M',
        	width: 18,
            dataIndex: 'mx',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'E',
            width: 18,
            dataIndex: 'ex',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'anio',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(1)',
            width: 25,
            dataIndex: 'u1',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(2)',
            width: 25,
            dataIndex: 'u2',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(3)',
            width: 25,
            dataIndex: 'u3',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(4)',
            width: 25,
            dataIndex: 'u4',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(5)',
            width: 25,
            dataIndex: 'u5',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(6)',
            width: 25,
            dataIndex: 'u6',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: '(7)',
            width: 25,
            dataIndex: 'u7',
			fixed: true,
			align:'CENTER',
			sortable: false
        },{
            text: 'Area Construida',
            width: 70,
            dataIndex: 'area_const',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('area_const'),'0,000.00');
            }
        },{
            text: 'Valor Unitario',
            width: 78,
            dataIndex: 'valo_unita',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('valo_unita'),'0,000.00');
            }
        },{
            text: 'Valor Area Construida',
            width: 88,
            dataIndex: 'valo_areas',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('valo_areas'),'0,000.00');
            }
        },{
            text: 'Area Com&uacute;n',
            width: 68,
            dataIndex: 'area_comun',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('area_comun'),'0,000.00');
            }
        },{
            text: 'Valor de Construcci&oacute;n',
            width: 88,
            dataIndex: 'valo_total',
			fixed: true,
			align:'RIGHT',
			sortable: false,
            renderer: function(value, metaData, record, rowIdx, colIdx, store, view){
                return Ext.util.Format.number(record.get('valo_total'),'0,000.00');
            }
        }]
    });
    xgridPisos.render('xgridPisos');
	
	/*Toolbar Pisos*/
	var toolPisos = Ext.create('Ext.toolbar.Toolbar', {
		layout: 'vbox',
		width: '27px',
		height: '138px',
		items: [{
			xtype: 'button',
			text: '',
			icon: 'img/add.png',
			handler: function(){
				
				var msgpago='Se ingresara un nuevo piso, Seguro de Continuar?';
				var showResult = function(btn){
					if(btn=='yes'){

							$.ajax({
								type: "POST",  
								url: urljs + "fiscalizacionpredio/crearpiso",
								data: $('#formModificarPredio').serializeObject(),
								//dataType : 'json',
								success: function(data) { 
									MostrarPisos();
								},     
								error: function(data) {
									infoMessage('Error',data);
								} 
							});
					}
				};
				confirmMessage('SIGMUN',msgpago,showResult);
				
			}
			},{
			xtype: 'button',
			text: '', 
			icon: 'img/edit.png',
			handler: function(){
					ModificarPiso();
				}
			},{
			xtype: 'button', 
			text: '', 
			icon: 'img/delete.png',
			handler: function(){
					EliminarPiso();
				}
			},{
			xtype: 'button', 
			text: '', 
			icon: 'img/view.png',
			handler: function(){
					MuestraInstalacion();
				}
			}],
		renderTo: 'btnsPisos'
	});
	
	var toolVias = Ext.create('Ext.toolbar.Toolbar', {
		layout: 'vbox',
		width: '27px',
		height: '28px',
		items: [{
			xtype: 'button',
			text: '',
			icon: 'img/view.png',
			handler: function(){
					var anno_bvia=$('#txtperiodopu').val();
					showPopup('rentas/busvias?anno='+anno_bvia,'#popbuscar','700','300','Buscador de vias');
				}
			}],
		renderTo: 'btncallvia'
	});
	
	window.muestraDatos = function(obj) {
		$('#txtdireccpu').val(obj.get('nomurba')+' '+obj.get('nomvia'));
		$('#txtcodigovia').val(obj.get('codigo'));
		//$('#txtCp').val(obj.get('nomurba'));
		//$('#txtSector').val(obj.get('nomzona'));
		$('#txtarancel').val(obj.get('arancel'));
		
	};
});

function ModificarPiso(){
	var grid = Ext.getCmp('xgridPisos');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idxxx');
		showPopup('fiscalizacionpredio/modificarpiso?idpi='+getvalue,'#popmodificarpiso','550','320','Modificar Piso...');
	}else{
		infoMessage('Requerimiento','Seleccione el Predio  ');
	}
}

function MostrarPisos(){
	
	var grid = Ext.getCmp('xgridPisos');
	var store = grid.getStore();
	store.removeAll();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	proxy.extraParams = {txtidpu  : $('#txtidpu').val()};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			var total_sum=0;
        	store.each(function (rec) { 
				total_sum += Number(rec.get('valo_total'));				
			});
			$('#txttconstruccion').val(Math.round(total_sum*100)/100).autoNumeric();
		}
	});	
}

function EliminarPiso(){

	var grid = Ext.getCmp('xgridPisos');
	var selected = grid.getView().getSelectionModel().getSelection();
	var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	if(selected.length > 0){
		var getvalue = selectedRecords.get('idxxx');
		var getpisos = selectedRecords.get('nivel');

		var msgpago='Se eliminara el piso '+getpisos+', Seguro de Continuar?';
		var showResult = function(btn){
			if(btn=='yes'){
				$.ajax({
					type: "POST",  
					url: urljs + "fiscalizacionpredio/eliminarpiso",
					data: { getvalue: getvalue},
					//dataType : 'json',
					success: function(data){
						//console.log(data);
						MostrarPisos();
					},     
					error: function(data) {
						infoMessage('Error',data);
					}
				});
			}
		};
		confirmMessage('SIGMUN',msgpago,showResult);
	}else{
		infoMessage('Requerimiento','Seleccione el Piso  ');
	}

}

function MuestraInstalacion(){
	//var grid = Ext.getCmp('xgridPisos');
	//var selected = grid.getView().getSelectionModel().getSelection();
	//var selectedRecords = grid.getView().getSelectionModel().getSelection()[0];
	
	//if(selected.length > 0){
		var getvalue = $('#txtidpu').val();
		showPopup('fiscalizacionpredio/muestrainstalacion?txtidpu='+getvalue,'#popmodificarinstalaciones','800','320','Otras Instalaciones...');
	//}else{
	//	infoMessage('Requerimiento','Seleccione el Predio  ');
	//}
}