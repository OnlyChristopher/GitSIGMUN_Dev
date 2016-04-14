Ext.onReady(function(){
    Ext.QuickTips.init();    
	$("input[type='button']").button();	    
	LoadGrid();	
});

$('input[id*="btnBuscar"]').live('click',function(){
	SearchContri();
});

$('#txtCodigo').blur(function(){
	if($(this).val()*1>0){
		var numCeros = '0000000'; 
		var valor =  $(this).val().trim();
		var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
		$(this).val(valor2);
	}
	else{
		$(this).val('');
	}
});

$('#txtCodigo').keypress(function (e) {
	if(e.keyCode==13){
		if($(this).val()*1>0){
			var numCeros = '0000000'; 
			var valor =  $(this).val().trim();
			var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
			$(this).val(valor2);
		}
		else{
			$(this).val('');
		}
		SearchContri();
	}
});

$('#txtNombre,#txtApePat,#txtApeMat,#txtRazSoc,#txtNumDoc,#selDistrito,#txtUrb,#txtVia,#txtMz,#txtLte,#txtSLte,#txtNumero').keypress(function (e) {
	if(e.keyCode==13)
		SearchContri();
});

function SearchContri(){
	var grid = Ext.getCmp('xgridCRentas');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCritec"]:checked').val();
	var txtCodigo = $('#txtCodigo').val();
	if(txtCodigo=='0000000'){
		txtCodigo = ''
		$('#txtCodigo').val('');
	}
	var txtNombre = $('#txtNombre').val();
	var txtApePat = $('#txtApePat').val();
	var txtApeMat = $('#txtApeMat').val();
	var txtRazSoc = $('#txtRazSoc').val();
	var txtNumDoc = $('#txtNumDoc').val();
	var selDistrito = $('#selDistrito').val();
	var txtUrb = $('#txtUrb').val();
	var txtVia = $('#txtVia').val();
	var txtMz = $('#txtMz').val();
	var txtLte = $('#txtLte').val();
	var txtSLte = $('#txtSLte').val();
	var txtNumero = $('#txtNumero').val();
	
	proxy.extraParams = {
		criterio: rdCriterio, 
		codigo: txtCodigo,
		nombre: txtNombre,
		apepat: txtApePat,
		apemat: txtApeMat,
		razsoc: txtRazSoc,
		numdoc: txtNumDoc,
		iddist: selDistrito,
		desurb: txtUrb,
		desvia: txtVia,
		manzana: txtMz,
		lote: txtLte,
		sublote: txtSLte,
		numero: txtNumero
	};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){
			var opt = $('input[name*="rdCritec"]:checked').val();
			switch(opt){
				case 'C': Ext.get('txtCodigo').focus(500); break;
				case 'N': Ext.get('txtNombre').focus(500); break;
				case 'R': Ext.get('txtRazSoc').focus(500); break;
				case 'D': Ext.get('txtNumDoc').focus(500); break;
				case 'F': Ext.get('txtUrb').focus(500); break;
			}			
		}
	});
}

function SelectSearch(){
	var opt = $('input[name*="rdCritec"]:checked').val();
	ActCriterio(opt);
	$('#txtCodigo').val('');
	$('#txtNombre').val('');
	$('#txtApePat').val('');
	$('#txtApeMat').val('');
	$('#txtRazSoc').val('');
	$('#txtNumDoc').val('');
	$('#selDistrito').val('');
	$('#txtUrb').val('');
	$('#txtVia').val();
	$('#txtMz').val();
	$('#txtLte').val();
	$('#txtSLte').val();
	$('#txtNumero').val();
	LoadGrid();
}

function LoadGrid(){
	$('#gridCRentas').html('');
	
	Ext.define('CRentas', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
			{name: 'id_tipocontri'},
			{name: 'tipo_detalle'},
			{name: 'id_docu'},
			{name: 'nombres'},
			{name: 'paterno'},
			{name: 'materno'},
			{name: 'id_subtipocontri'},
			{name: 'subtipo_detalle'},
			{name: 'id_post'},
			{name: 'codpost'},
			{name: 'telefono1'},
			{name: 'anexo1'},
			{name: 'correo_e'},
			{name: 'id_via'},
			{name: 'nombre_via'},
			{name: 'numero'},
			{name: 'nombre_edificio'},
			{name: 'numero2'},
			{name: 'manzana'},
			{name: 'lote'},
			{name: 'sub_lote'},
			{name: 'id_zona'},
			{name: 'nombre_zona'},
			{name: 'nombre'},
			{name: 'tipodoc'},
			{name: 'numedoc'},
            {name: 'distrito'},
			{name: 'direccion'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'CRentas',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'catasconrentas/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridCRentas',
        store: store,        
        title: 'Contribuyentes de Rentas',
        viewConfig: {
        	loadMask: { msg: 'cargando...' }
    	},
		listeners: {
			itemdblclick: {
				fn : function(grid, record) {
					var data = [];						
					data.push({						
						codigo: record.get('codigo'),
						id_tipocontri: record.get('id_tipocontri'),
						tipo_detalle: record.get('tipo_detalle'),
						id_docu: record.get('id_docu'),
						nombres: record.get('nombres'),
						paterno: record.get('paterno'),
						materno: record.get('materno'),
						id_subtipocontri: record.get('id_subtipocontri'),
						subtipo_detalle: record.get('subtipo_detalle'),
						id_post: record.get('id_post'),
						codpost: record.get('codpost'),
						telefono1: record.get('telefono1'),
						anexo1: record.get('anexo1'),
						correo_e: record.get('correo_e'),
						id_via: record.get('id_via'),
						nombre_zona: record.get('nombre_zona'),
						nombre_via: record.get('nombre_via'),
						numero: record.get('numero'),
						nombre_edificio: record.get('nombre_edificio'),
						numero2: record.get('numero2'),
						manzana: record.get('manzana'),
						lote: record.get('lote'),
						sub_lote: record.get('sub_lote'),
						id_zona: record.get('id_zona'),
						nonbre_zona: record.get('nombre_zona'),
						nombre: record.get('nombre'),
						tipodoc: record.get('tipodoc'),
						numedoc: record.get('numedoc'),
						distrito: record.get('distrito'),
						direccion: record.get('direccion')
					});
						
					showPopupData(data,'catasconrentas/datcontri','#popcatasrentasdatos','800','600','Datos del Contribuyente Seleccionado');
					closePopup('#popcatasconrentas');
				}
			}
		},
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            hidden: true,
            dataIndex: 'codigo'
        },{
            hidden: true,
            dataIndex: 'id_tipocontri'
        },{
            hidden: true,
            dataIndex: 'id_docu'
        },{
            hidden: true,
            dataIndex: 'tipo_detalle'
        },{
            hidden: true,
            dataIndex: 'nombres'
        },{
            hidden: true,
            dataIndex: 'paterno'
        },{
            hidden: true,
            dataIndex: 'materno'
        },{
            hidden: true,
            dataIndex: 'id_post'
        },{
            hidden: true,
            dataIndex: 'codpost'
        },{
            hidden: true,
            dataIndex: 'telefono1'
        },{
            hidden: true,
            dataIndex: 'anexo1'
        },{
            hidden: true,
            dataIndex: 'correo_e'
        },{
            hidden: true,
            dataIndex: 'id_via'
        },{
            hidden: true,
            dataIndex: 'nombre_via'
        },{
            hidden: true,
            dataIndex: 'numero'
        },{
            hidden: true,
            dataIndex: 'nombre_edificio'
        },{
            hidden: true,
            dataIndex: 'numero2'
        },{
            hidden: true,
            dataIndex: 'manzana'
        },{
            hidden: true,
            dataIndex: 'lote'
        },{
            hidden: true,
            dataIndex: 'sub_lote'
        },{
            hidden: true,
            dataIndex: 'id_zona'
        },{
            hidden: true,
            dataIndex: 'nombre_zona'
        },{
            text: 'C&oacute;digo',
            width: 60,
            dataIndex: 'codigo'
        },{        
        	text: 'Nombres / Raz&oacute;n Social',
        	width: 150,
            dataIndex: 'nombre'
        },{
            text: 'Tipo Doc.',
            width: 80,
            dataIndex: 'tipodoc'            
        },{
            text: 'Nro. Doc.',
            width: 100,
            dataIndex: 'numedoc'            
        },{
            text: 'Distrito',
            width: 100,
            dataIndex: 'distrito'
        },{
            text: 'Direcci&oacute;n',
            flex: 1,
            dataIndex: 'direccion'
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });
    
    grid.render('gridCRentas');
}
