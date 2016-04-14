Ext.onReady(function(){
	$.validator.addMethod("v_ficha", function(value, element) {
		//alert();
		var params = "nrficha="+$('#nrficha').val()+"&nrficha_old="+$('#nrficha_old').val();
		return callAjax("catasfichas/valida",params,'','param');
		//return false;
	}, "Este n&uacute;mero de ficha ya existe" );
	
	//Guardar Ficha
	$('#frmficcot').validate({
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
			
			//Datos grilla cotitulares
			var dataCot = new Array();			
			var gridCot = Ext.getCmp('xgridFicCot');
			if(gridCot.getStore().data.length>0){
				var i=0;
				gridCot.getStore().each(function(rec) {
					var rowsCot = {
						idsigma: rec.get('idsigma'),
						cidfich: rec.get('cidfich'),
						cidpers: rec.get('cidpers'),
						nrcotit: rec.get('nrcotit'),
						nrtotal: rec.get('nrtotal'),
						porcenc: rec.get('porcenc'),
						cidfaqu: rec.get('cidfaqu'),
						fecadqu: rec.get('fecadqu')
					}
					dataCot[i] = rowsCot;
					i++;
				});
				data['Coti'] = dataCot;
			}
			
			//Envía data para guardar
			Ext.Ajax.request({
				  url: urljs + "catasficcot/grabar",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Guardar Ficha',response.responseText);
					  closePopup('#popcatasficcot');
					  Ext.getCmp('xgridCMficha').getStore().load();
				  }
			});
		}
	});
	//Fin guardar ficha
	
	$('#rsector,#rmanzan,#reflote,#redific,#rentrad,#refpiso,#refunid').keyup(function() {
		getDC();
	});
	
	eventCot('C');
	loadGridCot();
	
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
	$('#nompers').keyup(function(e){
		if(e.keyCode == 13){
			searchCot();
		}
	});
	
	//Todos a mayúscula
	$('.cajac').live('blur', function(){
		this.value = this.value.toUpperCase();
	});
});

function loadGridCot(){
	Ext.define('Cotitular', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidfich'},
            {name: 'cidpers'},
			{name: 'nompers'},
            {name: 'nrodocu'},
			{name: 'nrcotit'},
            {name: 'nrtotal'},
            {name: 'porcenc'},
			{name: 'cidfaqu'},
			{name: 'nomfaqu'},
			{name: 'fecadqu'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Cotitular',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicCot',
		columnLines: true,
		store: store,
		width: '820px',
		height: '140px',
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficcot/datagrids",
						  method: "POST",
						  params: {cidcot: $('#idsigma').val()},
						  success: function(response){
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsCot(data[i]);
								}
								//Para ver si se elimina
								$('#oldCotItems').val(strItems);
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
            dataIndex: 'cidpers'
        },{        
        	text: 'Nombre de Cotitular Catastral',
        	width: 200,
            dataIndex: 'nompers'
        },{
            text: 'N&deg; de Documento',
            width: 120,
            dataIndex: 'nrodocu'            
        },{
            text: 'N&deg; de Cotitular',
            width: 100,
            dataIndex: 'nrcotit'
        },{
            text: 'Total Cotitulares',
            width: 120,
            dataIndex: 'nrtotal'
        },{
            text: '% Cotitular',
            width: 100,
            dataIndex: 'porcenc'
        },{
            dataIndex: 'cidfaqu',
			hidden: true
        },{
            text: 'Forma Adquisici&oacute;n',
            width: 150,
            dataIndex: 'nomfaqu'
        },{
            text: 'Fec. Adquisici&oacute;n',
            width: 100,
            dataIndex: 'fecadqu'
        }]
    });
    
    grid.render('gridFicCot');
}

function eventCot(act){
	switch(act){
		case 'A':
			$('#divAddCot').show();
			$('#rowSaveCot').show();
			$('#rowCancelCot').show();
			$('#rowAddCot').hide();
			$('#rowEditCot').hide();
			$('#btnDelCot').attr('disabled',true);
			disableButton('#btnDelCot');
			$("#actCot").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicCot');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventCot('A');
				$("#iddcotitu").val(rec.get('idsigma'));
				$("#cidpersxx").val(rec.get('cidpers'));
				$("#nrodocu").val(rec.get('nrodocu'));
				$('#nompers').val(rec.get('nompers'));
				$("#nrcotit").val(rec.get('nrcotit'));
				$('#nrtotal').val(rec.get('nrtotal'));
				$('#porcenc').val(rec.get('porcenc'));
				$('#cidfaqu').val(rec.get('cidfaqu'));
				$('#fecadqu').val(rec.get('fecadqu'));
				$("#actCot").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddCot').hide();
			$('#rowAddCot').show();
			$('#rowEditCot').show();
			$('#rowSaveCot').hide();
			$('#rowCancelCot').hide();
			$('#btnDelCot').attr('disabled',false);
			enableButton('#btnDelCot');			
			$('#iddcotitu').val('');
			$('#cidpersxx').val('');
			$('#nrodocu').val('');
			$('#nompers').val('');
			$('#nrcotit').val('');
			$('#nrtotal').val('');
			$('#porcenc').val('');
			$('#cidfaqu option:first').attr('selected', true);
			$('#fecadqu').val('');
			$("#actCot").val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicCot');
			var store = grid.getStore();
			
			if($('#cidpersxx').val()!=''){
				if($("#actCot").val()=='A'){					
					var r = Ext.create('Cotitular', {
						idsigma: '',
						cidindi: $('#cidfich').val(),
						cidpers: $('#cidpersxx').val(),		
						nompers: $('#nompers').val(),
						nrodocu: $('#nrodocu').val(),
						nrcotit: $('#nrcotit').val(),
						nrtotal: $('#nrtotal').val(),
						porcenc: $('#porcenc').val(),
						cidfaqu: $('#cidfaqu').val(),
						nomfaqu: ($('#cidfaqu :selected').text()=='[Seleccione]') ? '' : $('#cidfaqu :selected').text(),						
						fecadqu: $('#fecadqu').val()
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();					
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#iddcotitu').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidpers', $('#cidpersxx').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrodocu', $('#nrodocu').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nompers', $('#nompers').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrcotit', $('#nrcotit').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrtotal', $('#nrtotal').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('porcenc', $('#porcenc').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidfaqu', $('#cidfaqu').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nomfaqu', ($('#cidfaqu :selected').text()=='[Seleccione]') ? '' : $('#cidfaqu :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('fecadqu', $('#fecadqu').val());
					grid.getStore().sync();				
				}
				eventCot('C');
			}
			else
				infoMessage('Guardar Cotitular','Debe seleccione al cotitular catastral!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicCot');
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

function actDec(est){
	if(est){
		$('input[dir=dfirmas]').removeClass('cajacoff');
		$('input[dir=dfirmas]').addClass('cajac');
		$('input[dir=dfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=dfirmas]').removeClass('cajac');
		$('input[dir=dfirmas]').addClass('cajacoff');
		$('input[dir=dfirmas]').attr('readonly',true);
	}
}

function actSup(est){
	if(est){
		$('input[dir=sfirmas]').removeClass('cajacoff');
		$('input[dir=sfirmas]').addClass('cajac');
		$('input[dir=sfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=sfirmas]').removeClass('cajac');
		$('input[dir=sfirmas]').addClass('cajacoff');
		$('input[dir=sfirmas]').attr('readonly',true);		
	}
}

function actTec(est){
	if(est){
		$('input[dir=tfirmas]').removeClass('cajacoff');
		$('input[dir=tfirmas]').addClass('cajac');
		$('input[dir=tfirmas]').attr('readonly',false);
	}
	else{
		$('input[dir=tfirmas]').removeClass('cajac');
		$('input[dir=tfirmas]').addClass('cajacoff');
		$('input[dir=tfirmas]').attr('readonly',true);		
	}
}

function actVer(est){
	if(est){
		$('input[dir=vfirmas]').removeClass('cajacoff');
		$('input[dir=vfirmas]').addClass('cajac');
		$('input[dir=vfirmas]').attr('readonly',false);		
	}
	else{
		$('input[dir=vfirmas]').removeClass('cajac');
		$('input[dir=vfirmas]').addClass('cajacoff');
		$('input[dir=vfirmas]').attr('readonly',true);
	}
}

function searchCot(){
	showPopup('cataspersona/buscarcoti','#popcatascoti','600','400','B&uacute;squeda de Cotitulares');
}

function selectCoti(obj){
	$('#cidpersxx').val(obj.get('idsigma'));
	$('#nompers').val(obj.get('nombres'));
	$('#nrodocu').val(obj.get('numedoc'));
}

function addRowsCot(data){
	var grid = Ext.getCmp('xgridFicCot');
	var store = grid.getStore();
	var r = Ext.create('Cotitular', {
		idsigma: data.idsigma,
		cidfich: data.cidfich,
		cidpers: data.cidpers,
		nompers: data.nompers,
		nrodocu: data.nrodocu,
		nrcotit: data.nrcotit,
		nrtotal: data.nrtotal,
		porcenc: data.porcenc,
		cidfaqu: data.cidfaqu,
		nomfaqu: data.nomfaqu,
		fecadqu: data.fecadqu		
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