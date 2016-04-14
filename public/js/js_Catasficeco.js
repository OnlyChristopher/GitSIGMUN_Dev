Ext.onReady(function(){
	$.validator.addMethod("v_ficha", function(value, element) {
		//alert();
		var params = "nrficha="+$('#nrficha').val()+"&nrficha_old="+$('#nrficha_old').val();
		return callAjax("catasfichas/valida",params,'','param');
		//return false;
	}, "Este n&uacute;mero de ficha ya existe" );
	
	//Guardar Ficha
	$('#frmficeco').validate({
		rules: {
			'nrficha': { required: true, digits: true, v_ficha: true },
			'lote_01': { required: true, digits: true },
			'lote_02': { required: true, digits: false }
		},
		messages: {
			'nrficha': { required: 'Ingrese nro de ficha', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'lote_01': { required: 'Ingrese nro de correlativo de ficha', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			'lote_02': { required: 'Ingrese nro de total de fichas', digits: 'Ingrese s&oacute;lo n&uacute;meros' }
		},
		debug: true,
		errorElement: 'div',
		submitHandler: function(form){
			
			//Datos del formulario
			data = $(form).serializeObject();
			
			//Datos grilla actividades
			var dataActivi = new Array();			
			var gridActivi = Ext.getCmp('xgridFicEcoActivi');
			if(gridActivi.getStore().data.length>0){
				var i=0;
				gridActivi.getStore().each(function(rec) {
					var rowsActivi = {
						idsigma: rec.get('idsigma'),
						cidfich: rec.get('cidfich'),
						cidacti: rec.get('cidacti'),
						desacti: rec.get('desacti')
					}
					dataActivi[i] = rowsActivi;
					i++;
				});
				data['Activi'] = dataActivi;
			}
			
			//Datos grilla anuncios
			var dataAnunci = new Array();			
			var gridAnunci = Ext.getCmp('xgridFicEcoAnunci');
			if(gridAnunci.getStore().data.length>0){
				var i=0;
				gridAnunci.getStore().each(function(rec) {
					var rowsAnunci = {
						idsigma: rec.get('idsigma'),
						cidfich: rec.get('cidfich'),
						cidanuc: rec.get('cidanuc'),
						nrolado: rec.get('nrolado'),
						areauto: rec.get('areauto'),
						areveri: rec.get('areveri'),
						nroexpe: rec.get('nroexpe'),
						nrolice: rec.get('nrolice'),
						fexpedi: rec.get('fexpedi'),
						fvencim: rec.get('fvencim')
					}
					dataAnunci[i] = rowsAnunci;
					i++;
				});
				data['Anunci'] = dataAnunci;
			}
			
			//Envía data para guardar
			Ext.Ajax.request({
				  url: urljs + "catasficeco/grabar",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Guardar Ficha',response.responseText);
					  closePopup('#popcatasficeco');
					  Ext.getCmp('xgridCMficha').getStore().load();
				  }
			});
		}
	});
	//Fin guardar ficha
	
	$('#rsector,#rmanzan,#reflote,#redific,#rentrad,#refpiso,#refunid').keyup(function() {
		getDC();
	});
	
	//Tab Actividades
	eventActivi('C');
	loadGridActivi();
	redimGridHidden('xgridFicEcoActivi',350,140,2);
	
	//Tab Anuncio
	eventAnunci('C');
	loadGridAnunci();
	redimGridHidden('xgridFicEcoAnunci',950,100,3);
	
	$('#dfirmas').live('change', function(){
		actDec($(this).is(':checked'));
	});
	$('#sfirmas').live('change', function(){
		actSup($(this).is(':checked'));
	});
	$('#tfirmas').live('change', function(){
		actTec($(this).is(':checked'));
	});
	$('#vfirmas').live('change', function(){
		actVer($(this).is(':checked'));
	});
	
	//Todos a mayúscula
	$('.cajac').live('blur', function(){
		this.value = this.value.toUpperCase();
	});
});

//Tab Identificación
function searchConduc(){
	showPopup('cataspersona/buscarcoti','#popcatascoti','600','400','Buscar Persona');
}

function selectCoti(obj){
	$('#cid_per').val(obj.get('idsigma'));
	$('#nom_per').val(obj.get('nombres'));
}

//Tab Autorización de Funcionamiento
function loadGridActivi(){
	Ext.define('Actividad', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidfich'},
            {name: 'cidacti'},
			{name: 'desacti'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Actividad',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicEcoActivi',
		columnLines: true,
		store: store,
		width: '300px',
		height: '140px',
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficeco/datagrids",
						  method: "POST",
						  params: {tip: "ACT",cideco: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsActivi(data[i]);
								}
								//Para ver si se elimina
								$('#oldActItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [
		{
            dataIndex: 'idsigma',
			hidden: true
        },{
            dataIndex: 'cidfich',
			hidden: true
        },{
            text: 'C&oacute;digo',
            width: 60,		
            dataIndex: 'cidacti'
        },{
			text: 'Descripci&oacute;n',
			flex: 1,
            dataIndex: 'desacti'
        }]
    });
    
    grid.render('gridFicEcoActivi');
}

function eventActivi(act){
	switch(act){
		case 'A':
			$('#divAddActivi').show();
			$('#rowSaveActivi').show();
			$('#rowCancelActivi').show();
			$('#rowAddActivi').hide();
			$('#rowEditActivi').hide();
			$('#btnDelActivi').attr('disabled',true);
			disableButton('#btnDelActivi');
			$("#actActivi").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicEcoActivi');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventActivi('A');
				$("#idcactivi").val(rec.get('idsigma'));
				$("#cidacti").val(rec.get('cidacti'));
				$("#actActivi").val('E');
			}
			else
				infoMessage('Editar Actividad','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddActivi').hide();
			$('#rowAddActivi').show();
			$('#rowEditActivi').show();
			$('#rowSaveActivi').hide();
			$('#rowCancelActivi').hide();
			$('#btnDelActivi').attr('disabled',false);
			enableButton('#btnDelActivi');
			$('#cidacti option:first').attr('selected', true);
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicEcoActivi');
			var store = grid.getStore();
			
			if($('#cidacti').val()!=''){
				if($("#actActivi").val()=='A'){
					var r = Ext.create('Actividad', {
						idsigma: '',
						cidfich: $('#cidfich').val(),
						cidacti: $('#cidacti').val(),
						desacti: ($('#cidacti :selected').text()=='[Seleccione]') ? '' : $('#cidacti :selected').text()
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#idcactivi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidacti', $('#cidacti').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('desacti', ($('#cidacti :selected').text()=='[Seleccione]') ? '' : $('#cidacti :selected').text());
					grid.getStore().sync();				
				}
				eventActivi('C');
			}
			else
				infoMessage('Guardar Actividad','Debe seleccione una actividad!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicEcoActivi');
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

function addRowsActivi(data){
	var grid = Ext.getCmp('xgridFicEcoActivi');
	var store = grid.getStore();
	var r = Ext.create('Actividad', {
		idsigma: data.idsigma,
		cidfich: data.cidfich,
		cidacti: data.cidacti,
		desacti: data.desacti
	});
	store.insert(store.data.length, r);
}

//Tab Autorización de Anuncio
function loadGridAnunci(){
	Ext.define('Anuncio', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidfich'},
            {name: 'cidanuc'},
			{name: 'nomanuc'},
			{name: 'nrolado'},
			{name: 'areauto'},
			{name: 'areveri'},
			{name: 'nroexpe'},
			{name: 'nrolice'},
			{name: 'fexpedi'},
			{name: 'fvencim'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Anuncio',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicEcoAnunci',
		columnLines: true,
		store: store,
		width: '950px',
		height: '100px',
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficeco/datagrids",
						  method: "POST",
						  params: {tip: "ANU",cideco: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsAnunci(data[i]);
								}
								//Para ver si se elimina
								$('#oldAnuItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [
		{
            dataIndex: 'idsigma',
			hidden: true
        },{
            dataIndex: 'cidfich',
			hidden: true
        },{
            dataIndex: 'cidanuc',
			hidden: true
        },{
            text: 'Descripci&oacute;n de la Actividad',
            flex: 1,		
            dataIndex: 'nomanuc'
        },{
			text: 'Nº de lados',
			width: 90,
            dataIndex: 'nrolado'
        },{
			text: 'A. Aut. de Anuncio',
			width: 120,
            dataIndex: 'areauto'
        },{
			text: 'A. Ver. de Anuncio',
			width: 120,
            dataIndex: 'areveri'
        },{
			text: 'Nº de Expediente',
			width: 110,
            dataIndex: 'nroexpe'
        },{
			text: 'Nº de Licencia',
			width: 100,
            dataIndex: 'nrolice'
        },{
			text: 'F. Expedici&oacute;n',
			width: 100,
            dataIndex: 'fexpedi'
        },{
			text: 'F. Vencimiento',
			width: 100,
            dataIndex: 'fvencim'
        }]
    });
    
    grid.render('gridFiceAnunci');
}

function eventAnunci(act){
	switch(act){
		case 'A':
			$('#divAddAnunci').show();
			$('#rowSaveAnunci').show();
			$('#rowCancelAnunci').show();
			$('#rowAddAnunci').hide();
			$('#rowEditAnunci').hide();
			$('#rowDelAnunci').attr('disabled',true);
			disableButton('#btnDelAnunci');
			$("#actAnunci").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicEcoAnunci');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventAnunci('A');
				$("#idcanunci").val(rec.get('idsigma'));
				$("#cidfich").val(rec.get('cidfich'));
				$("#cidanuc").val(rec.get('cidanuc'));
				$("#nrolado").val(rec.get('nrolado'));
				$('#areauto').val(rec.get('areauto'));
				$("#areveri").val(rec.get('areveri'));
				$('#nroexpe').val(rec.get('nroexpe'));
				$('#nrolice').val(rec.get('nrolice'));
				$('#fexpedi').val(rec.get('fexpedi'));
				$('#fvencim').val(rec.get('fvencim'));
				$("#actAnunci").val('E');
			}
			else
				infoMessage('Editar Anuncio','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddAnunci').hide();
			$('#rowAddAnunci').show();
			$('#rowEditAnunci').show();
			$('#rowSaveAnunci').hide();
			$('#rowCancelAnunci').hide();
			$('#btnDelAnunci').attr('disabled',false);
			enableButton('#btnDelAnunci');
			$('#cidanuc option:first').attr('selected', true);
			$('#nrolado').val('');
			$("#areauto").val('');
			$('#areveri').val('');
			$('#nroexpe').val('');
			$('#nrolice').val('');
			$("#fexpedi").val('');
			$('#fvencim').val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicEcoAnunci');
			var store = grid.getStore();
			if($('#cidanuc').val()!=''){
				if($("#actAnunci").val()=='A')
				{
					var r = Ext.create('Anuncio', {
						idsigma: '',
						cidfich: $('#cidfich').val(),
						cidanuc: $('#cidanuc').val(),
						nomanuc: ($('#cidanuc :selected').text()=='[Seleccione]') ? '' : $('#cidanuc :selected').text(),
						nrolado: $('#nrolado').val(),
						areauto: $('#areauto').val(),
						areveri: $('#areveri').val(),
						nroexpe: $('#nroexpe').val(),
						nrolice: $('#nrolice').val(),
						fexpedi: $('#fexpedi').val(),
						fvencim: $('#fvencim').val()		
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#idcanunci').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidfich', $('#cidfich').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidanuc', $('#cidanuc').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nomanuc', ($('#cidanuc :selected').text()=='[Seleccione]') ? '' : $('#cidanuc :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrolado', $('#nrolado').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('areauto', $('#areauto').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('areveri', $('#areveri').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nroexpe', $('#nroexpe').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrolice', $('#nrolice').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('fexpedi', $('#fexpedi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('fvencim', $('#fvencim').val());				
					grid.getStore().sync();				
				}
				eventAnunci('C');
			}
			else
				infoMessage('Guardar Anuncio','Debe seleccionar el tipo!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicEcoAnunci');
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

function addRowsAnunci(data){
	var grid = Ext.getCmp('xgridFicEcoAnunci');
	var store = grid.getStore();
	var r = Ext.create('Anuncio', {
		idsigma: data.idsigma,
		cidfich: data.cidfich,
		cidanuc: data.cidanuc,
		nomanuc: data.nomanuc,
		nrolado: data.nrolado,
		areauto: data.areauto,
		areveri: data.areveri,
		nroexpe: data.nroexpe,
		nrolice: data.nrolice,
		fexpedi: data.fexpedi,
		fvencim: data.fvencim
	});
	store.insert(store.data.length, r);
}

function selectDirPerson(){
	if($('#ciddpto').val()=='15' && $('#cidprov').val()=='01' && $('#ciddist').val()=='12'){
		$('#cidvias').show();
		$('#nomvia').hide();			
		$('#btnViaC').removeClass('selecciona');
		$('#btnViaC').addClass('inserta');		
		var cidvias = $('#cidvias').val();
		if(cidvias.trim()!=''){
			$('#cidvias').show();
			$('#nomvia').hide();			
			$('#btnViaC').removeClass('inserta');
			$('#btnViaC').addClass('selecciona');
		}
		var nomvia = $('#nomvia').val();
		if(nomvia.trim()!=''){
			$('#cidvias').hide();
			$('#nomvia').show();
			$('#btnViaC').removeClass('selecciona');
			$('#btnViaC').addClass('inserta');
		}
		
		$('#cidhabu').show();
		$('#nomhu').hide();			
		$('#btnHabU').removeClass('selecciona');
		$('#btnHabU').addClass('inserta');
		var cidhabu = $('#cidhabu').val();
		if(cidhabu.trim()!=''){
			$('#cidhabu').show();
			$('#nomhu').hide();			
			$('#btnHabU').removeClass('inserta');
			$('#btnHabU').addClass('selecciona');
		}
		var nomhu = $('#nomhu').val();
		if(nomhu.trim()!=''){
			$('#cidhabu').hide();
			$('#nomhu').show();
			$('#btnHabU').removeClass('selecciona');
			$('#btnHabU').addClass('inserta');
		}
	}
	else{
		$('#cidvias').hide();
		$('#nomvia').show();
		$('#cidhabu').hide();
		$('#nomhu').show();
		
		$('#btnViaC').removeClass('selecciona');
		$('#btnViaC').addClass('inserta');
		
		$('#btnHabU').removeClass('selecciona');
		$('#btnHabU').addClass('inserta');
	}
}

function selVia(){
	var clase = $('#btnVia').attr('class');
	if(clase=='inserta'){
		$('#btnVia').removeClass('inserta');
		$('#btnVia').addClass('selecciona');
		$('#cid_via').hide();
		$('#nom_via').show();
		$('#cid_via').val('');
	}
	else{
		$('#btnVia').removeClass('selecciona');
		$('#btnVia').addClass('inserta');
		$('#nom_via').hide();
		$('#cid_via').show();
		$('#nom_via').val('');
	}
}

function selVI(){	
	if($('#ciddpto').val()=='15' && $('#cidprov').val()=='01' && $('#ciddist').val()=='12'){
		var clase = $('#btnViaC').attr('class');
		if(clase=='inserta'){
			$('#btnViaC').removeClass('inserta');
			$('#btnViaC').addClass('selecciona');
			$('#cidvias').hide();
			$('#nomvia').show();
			$('#cidvias').val('');
		}
		else{
			$('#btnViaC').removeClass('selecciona');
			$('#btnViaC').addClass('inserta');
			$('#nomvia').hide();
			$('#cidvias').show();
			$('#nomvia').val('');
		}
	}
}

function selHU(){	
	if($('#ciddpto').val()=='15' && $('#cidprov').val()=='01' && $('#ciddist').val()=='12'){
		var clase = $('#btnHabU').attr('class');
		if(clase=='inserta'){
			$('#btnHabU').removeClass('inserta');
			$('#btnHabU').addClass('selecciona');
			$('#cidhabu').hide();
			$('#nomhu').show();
			$('#cidhabu').val('');
		}
		else{
			$('#btnHabU').removeClass('selecciona');
			$('#btnHabU').addClass('inserta');
			$('#nomhu').hide();
			$('#cidhabu').show();
			$('#nomhu').val('');
		}
	}
}