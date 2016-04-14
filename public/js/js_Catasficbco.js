Ext.onReady(function(){	
	$.validator.addMethod("v_ficha", function(value, element) {
		//alert();
		var params = "nrficha="+$('#nrficha').val()+"&nrficha_old="+$('#nrficha_old').val();
		return callAjax("catasfichas/valida",params,'','param');
		//return false;
	}, "Este n&uacute;mero de ficha ya existe" );
	
	//Guardar Ficha
	$('#frmficbco').validate({
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
			
			//Datos grilla ubicación
			var dataUbica = new Array();			
			var gridUbica = Ext.getCmp('xgridFicBcoUbica');
			if(gridUbica.getStore().data.length>0){
				var i=0;
				gridUbica.getStore().each(function(rec) {
					var rowsUbica = {
						idsigma: rec.get('idsigma'),
						cidindi: rec.get('cidindi'),
						cid_via: rec.get('cid_via'),
						nom_via: rec.get('nom_via'),
						cidpuer: rec.get('cidpuer'),
						nromuni: rec.get('nromuni'),
						cidcnum: rec.get('cidcnum'),
						nrcernu: rec.get('nrcernu')
					}
					dataUbica[i] = rowsUbica;
					i++;
				});
				data['Ubica'] = dataUbica;
			}
			
			//Datos grilla construcciones
			var dataConst = new Array();			
			var gridConst = Ext.getCmp('xgridFicBcoConst');
			if(gridConst.getStore().data.length>0){
				var i=0;
				gridConst.getStore().each(function(rec) {
					var rowsConst = {
						idpisos: rec.get('idpisos'),
						cidindi: rec.get('cidindi'),
						nropiso: rec.get('nropiso'),
						mescons: rec.get('mescons'),
						aniocons: rec.get('aniocons'),
						iddepcl: rec.get('iddepcl'),
						iddepma: rec.get('iddepma'),
						iddepco: rec.get('iddepco'),
						esmuros: rec.get('esmuros'),
						estecho: rec.get('estecho'),
						acapiso: rec.get('acapiso'),
						acapuer: rec.get('acapuer'),
						acareve: rec.get('acareve'),
						acabanio: rec.get('acabanio'),
						instele: rec.get('instele'),
						arconde: rec.get('arconde'),
						arconve: rec.get('arconve'),
						uconant: rec.get('uconant')
					}
					dataConst[i] = rowsConst;
					i++;
				});
				data['Const'] = dataConst;
			}
			
			//Datos grilla instalaciones
			var dataInstal = new Array();			
			var gridInstal = Ext.getCmp('xgridFicBcoInstal');
			if(gridInstal.getStore().data.length>0){
				var i=0;
				gridInstal.getStore().each(function(rec) {
					var rowsInstal = {
						idinsta: rec.get('idinsta'),
						cidindi: rec.get('cidindi'),
						cidinst: rec.get('cidinst'),
						mescons: rec.get('mescons'),
						aniocons: rec.get('aniocons'),
						iddepcl: rec.get('iddepcl'),
						iddepma: rec.get('iddepma'),
						iddepco: rec.get('iddepco'),
						dmlargo: rec.get('dmlargo'),
						dmancho: rec.get('dmancho'),
						dmaltos: rec.get('dmaltos'),
						protota: rec.get('protota'),
						vunimed: rec.get('vunimed'),
						uconant: rec.get('uconant')
					}
					dataInstal[i] = rowsInstal;
					i++;
				});
				data['Instal'] = dataInstal;
			}
			
			//Datos grilla edificios
			var dataEdi = new Array();			
			var gridEdi = Ext.getCmp('xgridFicBcoEdi');
			if(gridEdi.getStore().data.length>0){
				var i=0;
				gridEdi.getStore().each(function(rec) {
					var rowsEdi = {
						idsigma: rec.get('idsigma'),
						cidindi: rec.get('cidindi'),
						edifici: rec.get('edifici'),
						nporcen: rec.get('nporcen'),
						nratcm2: rec.get('nratcm2'),
						nraccm2: rec.get('nraccm2'),
						naoicm2: rec.get('naoicm2')
					}
					dataEdi[i] = rowsEdi;
					i++;
				});
				data['Edi'] = dataEdi;
			}
			
			//Datos grilla bienes comunes
			var dataBie = new Array();			
			var gridBie = Ext.getCmp('xgridFicBcoBie');
			if(gridBie.getStore().data.length>0){
				var i=0;
				gridBie.getStore().each(function(rec) {
					var rowsBie = {
						idsigma: rec.get('idsigma'),
						cidindi: rec.get('cidindi'),
						nroorde: rec.get('nroorde'),
						redific: rec.get('redific'),
						rentrad: rec.get('rentrad'),
						refpiso: rec.get('refpiso'),
						refunid: rec.get('refunid'),
						nporcen: rec.get('nporcen'),
						nratcm2: rec.get('nratcm2'),
						nraccm2: rec.get('nraccm2'),
						naoicm2: rec.get('naoicm2')
					}
					dataBie[i] = rowsBie;
					i++;
				});
				data['Bie'] = dataBie;
			}
						
			//Envía data para guardar
			Ext.Ajax.request({
				  url: urljs + "catasficbco/grabar",
				  method: "POST",
				  params: {json: JSON.stringify(data)},
				  success: function(response){
					  infoMessage('Guardar Ficha',response.responseText);
					  closePopup('#popcatasficbco');
					  Ext.getCmp('xgridCMficha').getStore().load();
				  }
			});
		}
	});
	//Fin guardar ficha
	
	$('#rsector,#rmanzan,#reflote,#redific,#rentrad,#refpiso,#refunid').keyup(function() {
		getDC();
	});
	
	//Tab Ubicación
	eventUbica('C');
	loadGridUbica();
	redimGridHidden('xgridFicBcoUbica',800,102,3);
	
	//Tab Construcciones
	eventConst('C');
	loadGridConst();
	redimGridHidden('xgridFicBcoConst',700,140,2);
	
	//Tab Instalaciones
	eventInstal('C');
	loadGridInstal();
	redimGridHidden('xgridFicBcoInstal',950,140,3);
	
	//Tab Recapitulación 
	//--Edificios--//
	eventEdi('C');
	loadGridEdi();
	redimGridHidden('xgridFicBcoEdi',590,102,2);
	
	//--Bienes Comunes--//
	eventBie('C');
	loadGridBie();
	redimGridHidden('xgridFicBcoBie',590,102,2);
		
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
	
	$('#nropiso').live('blur', function(){
		fullZeros(this,3);
	});
	$('#mescons, #iddepcl, #iddepma, #iddepco').live('blur', function(){
		fullZeros(this,2);
	});
});

//Tab Ubicación
function loadGridUbica(){
	Ext.define('UbiPredio', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidindi'},
            {name: 'cid_via'},
			{name: 'nom_via'},
            {name: 'cidpuer'},
			{name: 'nompuer'},
            {name: 'nromuni'},
            {name: 'cidcnum'},
			{name: 'nomcnum'},
			{name: 'nrcernu'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'UbiPredio',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicBcoUbica',
		columnLines: true,
		store: store,
		width: '800px',
		height: '102px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficbco/datagrids",
						  method: "POST",
						  params: {tip: "UBI",cidindi: $('#idsigma').val()},
						  success: function(response){
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsUbica(data[i]);
								}
								//Para ver si se elimina
								$('#oldUbiItems').val(strItems);
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
            dataIndex: 'cidindi',
			hidden: true
        },{
            dataIndex: 'cid_via',
			hidden: true
        },{
            text: 'C&oacute;digo V&iacute;a - Tipo V&iacute;a - Nombre V&iacute;a',
            width: 200,		
            dataIndex: 'nom_via'
        },{
            dataIndex: 'cidpuer',
			hidden: true
        },{        
        	text: 'Tipo de Puerta',
        	flex: 1,
            dataIndex: 'nompuer'
        },{
            text: 'N&deg; Municipal',
            width: 150,
            dataIndex: 'nromuni'            
        },{
            dataIndex: 'cidcnum',
			hidden: true
        },{
            text: 'Cond. N&uacute;mero',
            width: 150,
            dataIndex: 'nomcnum'
        },{
            text: 'Certificado de Numeraci&oacute;n',
            width: 180,
            dataIndex: 'nrcernu'
        }]
    });
    
    grid.render('gridFicBcoUbica');
}

function eventUbica(act){
	switch(act){
		case 'A':
			$('#divAddUbica').show();
			$('#rowSaveUbica').show();
			$('#rowCancelUbica').show();
			$('#rowAddUbica').hide();
			$('#rowEditUbica').hide();
			$('#btnDelUbica').attr('disabled',true);
			disableButton('#btnDelUbica');
			$("#actUbica").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicBcoUbica');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventUbica('A');
				$("#idcmdirec").val(rec.get('idsigma'));
				
				//Verificamos la vía
				if(rec.get('cid_via')!=''){
					$('#btnVia').removeClass('inserta');
					$('#btnVia').removeClass('selecciona');
					$('#btnVia').addClass('inserta');
					$("#cid_via").show();
					$("#nom_via").hide();
				}
				else{
					$('#btnVia').removeClass('inserta');
					$('#btnVia').removeClass('selecciona');
					$('#btnVia').addClass('selecciona');
					$("#cid_via").hide();
					$("#nom_via").show();
				}
				//Fin verifica vía
				
				$("#cid_via").val(rec.get('cid_via'));
				$("#nom_via").val(rec.get('nom_via'));
				$("#cidpuer").val(rec.get('cidpuer'));
				$('#nromuni').val(rec.get('nromuni'));
				$("#cidcnum").val(rec.get('cidcnum'));
				$('#nrcernu').val(rec.get('nrcernu'));
				$("#actUbica").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddUbica').hide();
			$('#rowAddUbica').show();
			$('#rowEditUbica').show();
			$('#rowSaveUbica').hide();
			$('#rowCancelUbica').hide();
			$('#btnDelUbica').attr('disabled',false);
			enableButton('#btnDelUbica');
			$('#cid_via option:first').attr('selected', true);
			$('#nom_via').val('');
			$('#cidpuer option:first').attr('selected', true);
			$('#nromuni').val('');
			$('#cidcnum option:first').attr('selected', true);
			$('#nrcernu').val('');
			$("#actUbica").val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicBcoUbica');
			var store = grid.getStore();
			
			if($('#cid_via').val()!='' || $('#nom_via').val()!=''){
				if($("#actUbica").val()=='A'){
					var r = Ext.create('UbiPredio', {
						idsigma: '',
						cidindi: $('#cidfich').val(),
						cid_via: $('#cid_via').val(),
						nom_via: ($('#cid_via :selected').text()=='[Seleccione]') ? $('#nom_via').val() : $('#cid_via :selected').text(),
						cidpuer: $('#cidpuer').val(),
						nompuer: ($('#cidpuer :selected').text()=='[Seleccione]') ? '' : $('#cidpuer :selected').text(),
						nromuni: $('#nromuni').val(),
						cidcnum: $('#cidcnum').val(),
						nomcnum: ($('#cidcnum :selected').text()=='[Seleccione]') ? '' : $('#cidcnum :selected').text(),
						nrcernu: $('#nrcernu').val(),
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#idcmdirec').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cid_via', $('#cid_via').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nom_via', ($('#cid_via :selected').text()=='[Seleccione]') ? $('#nom_via').val() : $('#cid_via :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidpuer', $('#cidpuer').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nompuer', ($('#cidpuer :selected').text()=='[Seleccione]') ? '' : $('#cidpuer :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nromuni', $('#nromuni').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidcnum', $('#cidcnum').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nomcnum', ($('#cidcnum :selected').text()=='[Seleccione]') ? '' : $('#cidcnum :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nrcernu', $('#nrcernu').val());
					grid.getStore().sync();				
				}
				eventUbica('C');
			}
			else
				infoMessage('Guardar Ubicaci&oacute;n','Debe seleccione una v&iacute;a!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicBcoUbica');
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

function addRowsUbica(data){
	var grid = Ext.getCmp('xgridFicBcoUbica');
	var store = grid.getStore();
	var r = Ext.create('UbiPredio', {
		idsigma: data.idsigma,
		cidindi: data.cidindi,
		cid_via: data.cid_via,
		nom_via: data.nom_via,
		cidpuer: data.cidpuer,
		nompuer: data.nompuer,
		nromuni: data.nromuni,
		cidcnum: data.cidcnum,
		nomcnum: data.nomcnum,
		nrcernu: data.nrcernu
	});
	store.insert(store.data.length, r);
}

//Tab Construcciones
function loadGridConst(){
	Ext.define('Construc', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idpisos'},
			{name: 'cidindi'},
			{name: 'nropiso'},
			{name: 'mescons'},
			{name: 'aniocons'},
			{name: 'iddepcl'}, //MEP
			{name: 'iddepma'}, //ECS
			{name: 'iddepco'}, //ECC
			{name: 'esmuros'},
			{name: 'estecho'},
			{name: 'acapiso'},
			{name: 'acapuer'},
			{name: 'acareve'},
			{name: 'acabanio'},
			{name: 'instele'},
			{name: 'arconde'}, //Area Dec
			{name: 'arconve'}, //Area Ver
			{name: 'uconant'}, //Uca
			{name: 'vdescri'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Construc',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicBcoConst',
		columnLines: true,
		store: store,
		width: '700px',
		height: '140px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficbco/datagrids",
						  method: "POST",
						  params: {tip: "CON",cidindi: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idpisos + '|';
									addRowsConst(data[i]);
								}
								//Para ver si se elimina
								$('#oldConItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            dataIndex: 'idpisos',
			hidden: true
        },{
            dataIndex: 'cidindi',
			hidden: true
        },{
            text: 'N&deg; Piso',
            width: 50,		
            dataIndex: 'nropiso'
        },{
			text: 'Mes',
            width: 50,
            dataIndex: 'mescons'
        },{
			text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'aniocons'
        },{
			text: 'MEP',
            width: 50,
            dataIndex: 'iddepcl'
        },{
			text: 'ECS',
            width: 50,
            dataIndex: 'iddepma'
        },{
			text: 'ECC',
            width: 50,
            dataIndex: 'iddepco'
        },{
			text: '(1)',
            width: 30,
            dataIndex: 'esmuros'
        },{
			text: '(2)',
            width: 30,
            dataIndex: 'estecho'
        },{
			text: '(3)',
            width: 30,
            dataIndex: 'acapiso'
        },{
			text: '(4)',
            width: 30,
            dataIndex: 'acapuer'
        },{
			text: '(5)',
            width: 30,
            dataIndex: 'acareve'
        },{
			text: '(6)',
            width: 30,
            dataIndex: 'acabanio'
        },{
			text: '(7)',
            width: 30,
            dataIndex: 'instele'
        },{
			text: 'Declar.',
            width: 50,
            dataIndex: 'arconde'
        },{
			text: 'Verif.',
            width: 50,
            dataIndex: 'arconve'
        },{
			text: 'uconant',
            hidden: true,
        },{
			text: 'UCA',
            flex: 1,
            dataIndex: 'vdescri'
        }]
    });
    
    grid.render('gridFicBcoConst');
}

function eventConst(act){
	switch(act){
		case 'A':
			$('#divAddConst').show();
			$('#rowSaveConst').show();
			$('#rowCancelConst').show();
			$('#rowAddConst').hide();
			$('#rowEditConst').hide();
			$('#btnDelConst').attr('disabled',true);
			disableButton('#btnDelConst');
			$("#actConst").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicBcoConst');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventConst('A');
				$("#idcpisos").val(rec.get('idpisos'));
				$("#nropiso").val(rec.get('nropiso'));
				$("#mescons").val(rec.get('mescons'));
				$("#aniocons").val(rec.get('aniocons'));
				$('#iddepcl').val(rec.get('iddepcl'));
				$("#iddepma").val(rec.get('iddepma'));
				$('#iddepco').val(rec.get('iddepco'));
				$('#esmuros').val(rec.get('esmuros'));
				$('#estecho').val(rec.get('estecho'));
				$('#acapiso').val(rec.get('acapiso'));
				$('#acapuer').val(rec.get('acapuer'));
				$('#acareve').val(rec.get('acareve'));
				$('#acabanio').val(rec.get('acabanio'));
				$('#instele').val(rec.get('instele'));
				$('#arconde').val(rec.get('arconde'));
				$('#arconve').val(rec.get('arconve'));
				$('#uconant').val(rec.get('uconant'));
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
			$('#btnDelConst').attr('disabled',false);
			enableButton('#btnDelConst');
			$("#idcpisos").val('');
			$("#nropiso").val('');
			$("#mescons").val('');
			$("#aniocons").val('');
			$('#iddepcl').val('');
			$("#iddepma").val('');
			$('#iddepco').val('');
			$('#esmuros').val('');
			$('#estecho').val('');
			$('#acapiso').val('');
			$('#acapuer').val('');
			$('#acareve').val('');
			$('#acabanio').val('');
			$('#instele').val('');
			$('#arconde').val('');
			$('#arconve').val('');
			$('#uconant option:first').attr('selected', true);
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicBcoConst');
			var store = grid.getStore();
			
			if($('#nropiso').val()!=''){
				if($("#actConst").val()=='A')
				{
					var r = Ext.create('Construc', {
						idpisos: '',
						cidindi: $('#cidfich').val(),
						nropiso: $('#nropiso').val(),
						mescons: $('#mescons').val(),
						aniocons: $('#aniocons').val(),
						iddepcl: $('#iddepcl').val(),
						iddepma: $('#iddepma').val(),
						iddepco: $('#iddepco').val(),
						esmuros: $('#esmuros').val(),
						estecho: $('#estecho').val(),
						acapiso: $('#acapiso').val(),
						acapuer: $('#acapuer').val(),
						acareve: $('#acareve').val(),
						acabanio: $('#acabanio').val(),
						instele: $('#instele').val(),
						arconde: $('#arconde').val(),
						arconve: $('#arconve').val(),
						uconant: $('#uconant').val(),
						vdescri: ($('#uconant :selected').text()=='[Seleccione]') ? '' : $('#uconant :selected').text()
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idpisos', $('#idcpisos').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nropiso', $('#nropiso').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mescons', $('#mescons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('aniocons', $('#aniocons').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepcl', $('#iddepcl').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepma', $('#iddepma').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepco', $('#iddepco').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('esmuros', $('#esmuros').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('estecho', $('#estecho').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acapiso', $('#acapiso').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acapuer', $('#acapuer').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acareve', $('#acareve').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('acabanio', $('#acabanio').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('instele', $('#instele').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('arconde', $('#arconde').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('arconve', $('#arconve').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('uconant', $('#uconant').val());				
					grid.getStore().getAt(store.indexOf(sm[0])).set('vdescri', ($('#uconant :selected').text()=='[Seleccione]') ? '' : $('#uconant :selected').text());
					grid.getStore().sync();				
				}
				eventConst('C');
			}
			else
				infoMessage('Guardar Construcci&oacute;n','Debe ingresar el n&uacute;mero de piso!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicBcoConst');
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

function addRowsConst(data){
	var grid = Ext.getCmp('xgridFicBcoConst');
	var store = grid.getStore();
	var r = Ext.create('Construc', {
		idpisos: data.idpisos,
		cidindi: data.cidindi,
		nropiso: data.nropiso,
		mescons: data.mescons,
		aniocons: data.aniocons,
		iddepcl: data.iddepcl,
		iddepma: data.iddepma,
		iddepco: data.iddepco,
		esmuros: data.esmuros,
		estecho: data.estecho,
		acapiso: data.acapiso,
		acapuer: data.acapuer,
		acareve: data.acareve,
		acabanio: data.acabanio,
		instele: data.instele,
		arconde: data.arconde,
		arconve: data.arconve,
		uconant: data.uconant,
		vdescri: data.vdescri
	});
	store.insert(store.data.length, r);
}

//Tab Instalaciones
function loadGridInstal(){
	Ext.define('Instal', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idinsta'},
			{name: 'cidindi'},
			{name: 'cidinst'},
			{name: 'cidnomb'},
			{name: 'mescons'},
			{name: 'aniocons'},
			{name: 'iddepcl'}, //MEP
			{name: 'iddepma'}, //ECS
			{name: 'iddepco'}, //ECC
			{name: 'dmlargo'},
			{name: 'dmancho'},
			{name: 'dmaltos'},
			{name: 'protota'},
			{name: 'vunimed'},
			{name: 'uconant'}, //Uca
			{name: 'vdescri'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Instal',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicBcoInstal',
		columnLines: true,
		store: store,
		width: '950px',
		height: '140px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficbco/datagrids",
						  method: "POST",
						  params: {tip: "INS",cidindi: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idinsta + '|';
									addRowsInstal(data[i]);
								}
								//Para ver si se elimina
								$('#oldInsItems').val(strItems);
						  }
					});					
            	}
            }
    	},
        columns: [ 
        {
            dataIndex: 'idinsta',
			hidden: true
        },{
            dataIndex: 'cidindi',
			hidden: true
        },{
            dataIndex: 'cidinst',
			hidden: true
        },{
            text: 'Descripci&oacute;n',
            width: 200,		
            dataIndex: 'cidnomb'
        },{
			text: 'Mes',
            width: 50,
            dataIndex: 'mescons'
        },{
			text: 'A&ntilde;o',
            width: 40,
            dataIndex: 'aniocons'
        },{
			text: 'MEP',
            width: 50,
            dataIndex: 'iddepcl'
        },{
			text: 'ECS',
            width: 50,
            dataIndex: 'iddepma'
        },{
			text: 'ECC',
            width: 50,
            dataIndex: 'iddepco'
        },{
			text: 'Largo',
            width: 50,
            dataIndex: 'dmlargo'
        },{
			text: 'Ancho',
            width: 50,
            dataIndex: 'dmancho'
        },{
			text: 'Alto',
            width: 50,
            dataIndex: 'dmaltos'
        },{
			text: 'Prod. Total',
            width: 80,
            dataIndex: 'protota'
        },{
			text: 'U. Medida',
            width: 80,
            dataIndex: 'vunimed'
        },{
			text: 'uconant',
            hidden: true,
        },{
			text: 'UCA',
            flex: 1,
            dataIndex: 'vdescri'
        }]
    });
    
    grid.render('gridFicBcoInstal');
}

function eventInstal(act){
	switch(act){
		case 'A':
			$('#divAddInstal').show();
			$('#rowSaveInstal').show();
			$('#rowCancelInstal').show();
			$('#rowAddInstal').hide();
			$('#rowEditInstal').hide();
			$('#btnDelInstal').attr('disabled',true);
			disableButton('#btnDelInstal');
			$("#actInstal").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicBcoInstal');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventInstal('A');
				$("#idcminsta").val(rec.get('idinsta'));
				$("#cidinst").val(rec.get('cidinst'));
				$("#mesconsi").val(rec.get('mescons'));
				$("#anioconsi").val(rec.get('aniocons'));
				$('#iddepcli').val(rec.get('iddepcl'));
				$("#iddepmai").val(rec.get('iddepma'));
				$('#iddepcoi').val(rec.get('iddepco'));
				$('#dmlargo').val(rec.get('dmlargo'));
				$('#dmancho').val(rec.get('dmancho'));
				$('#dmaltos').val(rec.get('dmaltos'));
				$('#protota').val(rec.get('protota'));
				$('#vunimed').val(rec.get('vunimed'));
				$('#uconanti').val(rec.get('uconant'));
				$("#actInstal").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddInstal').hide();
			$('#rowAddInstal').show();
			$('#rowEditInstal').show();
			$('#rowSaveInstal').hide();
			$('#rowCancelInstal').hide();
			$('#btnDelInstal').attr('disabled',false);
			enableButton('#btnDelInstal');
			$("#idcminsta").val('');
			$('#cidinst option:first').attr('selected', true);
			$("#mesconsi").val('');
			$("#anioconsi").val('');
			$('#iddepcli').val('');
			$("#iddepmai").val('');
			$('#iddepcoi').val('');
			$('#dmlargo').val('');
			$('#dmancho').val('');
			$('#dmaltos').val('');
			$('#protota').val('');
			$('#vunimed').val('');
			$('#uconanti option:first').attr('selected', true);
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicBcoInstal');
			var store = grid.getStore();
			
			if($('#cidinst').val()!=''){
				if($("#actInstal").val()=='A')
				{
					var r = Ext.create('Instal', {
						idinsta: '',
						cidindi: $('#cidfich').val(),
						cidinst: $('#cidinst').val(),
						cidnomb: ($('#cidinst :selected').text()=='[Seleccione]') ? '' : $('#cidinst :selected').text(),
						mescons: $('#mesconsi').val(),
						aniocons: $('#anioconsi').val(),
						iddepcl: $('#iddepcli').val(),
						iddepma: $('#iddepmai').val(),
						iddepco: $('#iddepcoi').val(),
						dmlargo: $('#dmlargo').val(),
						dmancho: $('#dmancho').val(),
						dmaltos: $('#dmaltos').val(),
						protota: $('#protota').val(),
						vunimed: $('#vunimed').val(),
						uconant: $('#uconanti').val(),
						vdescri: ($('#uconanti :selected').text()=='[Seleccione]') ? '' : $('#uconanti :selected').text()
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idinsta', $('#idcminsta').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidinst', $('#cidinst').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('cidnomb', ($('#cidinst :selected').text()=='[Seleccione]') ? '' : $('#cidinst :selected').text());
					grid.getStore().getAt(store.indexOf(sm[0])).set('mescons', $('#mesconsi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('aniocons', $('#anioconsi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepcl', $('#iddepcli').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepma', $('#iddepmai').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('iddepco', $('#iddepcoi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmlargo', $('#dmlargo').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmancho', $('#dmancho').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('dmaltos', $('#dmaltos').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('protota', $('#protota').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('vunimed', $('#vunimed').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('uconant', $('#uconanti').val());				
					grid.getStore().getAt(store.indexOf(sm[0])).set('vdescri', ($('#uconanti :selected').text()=='[Seleccione]') ? '' : $('#uconanti :selected').text());
					grid.getStore().sync();				
				}
				eventInstal('C');
			}
			else
				infoMessage('Guardar Instalac&oacute;n','Debe seleccionar el tipo!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicBcoInstal');
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

function addRowsInstal(data){
	var grid = Ext.getCmp('xgridFicBcoInstal');
	var store = grid.getStore();
	var r = Ext.create('Instal', {
		idinsta: data.idinsta,
		cidindi: data.cidindi,
		cidinst: data.cidinst,
		cidnomb: data.cidnomb,
		mescons: data.mescons,
		aniocons: data.aniocons,
		iddepcl: data.iddepcl,
		iddepma: data.iddepma,
		iddepco: data.iddepco,
		dmlargo: data.dmlargo,
		dmancho: data.dmancho,
		dmaltos: data.dmaltos,
		protota: data.protota,
		vunimed: data.vunimed,
		uconant: data.uconant,
		vdescri: data.vdescri
	});
	store.insert(store.data.length, r);
}

//Tab Recapitulación Edificios
function loadGridEdi(){
	Ext.define('Edificio', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidindi'},
			{name: 'edifici'},
			{name: 'nporcen'},
			{name: 'nratcm2'},
			{name: 'nraccm2'},
			{name: 'naoicm2'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Edificio',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicBcoEdi',
		columnLines: true,
		store: store,
		width: '590px',
		height: '102px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficbco/datagrids",
						  method: "POST",
						  params: {tip: "EDI",cidindi: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsEdi(data[i]);
								}
								//Para ver si se elimina
								$('#oldEdiItems').val(strItems);
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
            dataIndex: 'cidindi',
			hidden: true
        },{
            text: 'Edificio',
            flex: 1,			
            dataIndex: 'edifici'
        },{
			text: 'Porcentaje (%)',
            width: 120,
            dataIndex: 'nporcen'
        },{
			text: 'ATC (m2)',
            width: 120,
            dataIndex: 'nratcm2'
        },{
			text: 'ACC (m2)',
            width: 120,
            dataIndex: 'nraccm2'
        },{
			text: 'AOIC (m2)',
            width: 120,
            dataIndex: 'naoicm2'
        }]
    });
    
    grid.render('gridFicBcoEdi');
}

function eventEdi(act){
	switch(act){
		case 'A':
			$('#divAddEdi').show();
			$('#rowSaveEdi').show();
			$('#rowCancelEdi').show();
			$('#rowAddEdi').hide();
			$('#rowEditEdi').hide();
			$('#btnDelEdi').attr('disabled',true);
			disableButton('#btnDelEdi');
			$("#actEdi").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicBcoEdi');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventEdi('A');
				$("#idcbreedi").val(rec.get('idsigma'));
				$("#edifici1").val(rec.get('edifici'));
				$("#nporcen1").val(rec.get('nporcen'));
				$("#nratcm21").val(rec.get('nratcm2'));
				$("#nraccm21").val(rec.get('nraccm2'));
				$('#naoicm21').val(rec.get('naoicm2'));
				$("#actEdi").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddEdi').hide();
			$('#rowAddEdi').show();
			$('#rowEditEdi').show();
			$('#rowSaveEdi').hide();
			$('#rowCancelEdi').hide();
			$('#btnDelEdi').attr('disabled',false);
			enableButton('#btnDelEdi');
			$("#idcbreedi").val('');
			$("#edifici1").val('');
			$("#nporcen1").val('');
			$("#nratcm21").val('');
			$('#nraccm21').val('');
			$("#naoicm21").val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicBcoEdi');
			var store = grid.getStore();
			
			if($('#edifici1').val()!=''){
				if($("#actEdi").val()=='A')
				{
					var r = Ext.create('Edificio', {
						idsigma: '',
						cidindi: $('#cidfich').val(),
						edifici: $('#edifici1').val(),
						nporcen: $('#nporcen1').val(),
						nratcm2: $('#nratcm21').val(),
						nraccm2: $('#nraccm21').val(),
						naoicm2: $('#naoicm21').val()
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#idcbreedi').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('edifici', $('#edifici1').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nporcen', $('#nporcen1').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nratcm2', $('#nratcm21').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nraccm2', $('#nraccm21').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('naoicm2', $('#naoicm21').val());
					grid.getStore().sync();				
				}
				eventEdi('C');
			}
			else
				infoMessage('Guardar Recap. Edificios','Debe ingresar el edificio!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicBcoEdi');
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

function addRowsEdi(data){
	var grid = Ext.getCmp('xgridFicBcoEdi');
	var store = grid.getStore();
	var r = Ext.create('Edificio', {
		idsigma: data.idsigma,
		cidindi: data.cidindi,
		edifici: data.edifici,
		nporcen: data.nporcen,
		nratcm2: data.nratcm2,
		nraccm2: data.nraccm2,
		naoicm2: data.naoicm2
	});
	store.insert(store.data.length, r);
}

//Tab Recapitulación de Bienes Comunes
function loadGridBie(){
	Ext.define('Bien', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'idsigma'},
			{name: 'cidindi'},
			{name: 'nroorde'},
			{name: 'redific'},
			{name: 'rentrad'},
			{name: 'refpiso'},
			{name: 'refunid'},
			{name: 'nporcen'},
			{name: 'nratcm2'},
			{name: 'nraccm2'},
			{name: 'naoicm2'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Bien',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridFicBcoBie',
		columnLines: true,
		store: store,
		width: '590px',
		height: '102px',        
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					Ext.Ajax.request({
						  url: urljs + "catasficbco/datagrids",
						  method: "POST",
						  params: {tip: "BIE",cidindi: $('#idsigma').val()},
						  success: function(response){			
							  	data = Ext.JSON.decode(response.responseText);
								var strItems = "";
							  	for(var i=0 ; i < data.length; i++){
									strItems = strItems + data[i].idsigma + '|';
									addRowsBie(data[i]);
								}
								//Para ver si se elimina
								$('#oldBieItems').val(strItems);
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
            dataIndex: 'cidindi',
			hidden: true
        },{
            text: 'N&deg;',
            flex: 1,
            dataIndex: 'nroorde'
        },{
			text: 'Edificaci&oacute;n',
            width: 80,
            dataIndex: 'redific'
        },{
			text: 'Entrada',
            width: 60,
            dataIndex: 'rentrad'
        },{
			text: 'Piso',
            width: 50,
            dataIndex: 'refpiso'
        },{
			text: 'Unidad',
            width: 60,
            dataIndex: 'refunid'
        },{
			text: '%',
            width: 40,
            dataIndex: 'nporcen'
        },{
			text: 'ATC (m2)',
            width: 80,
            dataIndex: 'nratcm2'
        },{
			text: 'ACC (m2)',
            width: 80,
            dataIndex: 'nraccm2'
        },{
			text: 'AOIC (m2)',
            width: 80,
            dataIndex: 'naoicm2'
        }]
    });
    
    grid.render('gridFicBcoBie');
}

function eventBie(act){
	switch(act){
		case 'A':
			$('#divAddBie').show();
			$('#rowSaveBie').show();
			$('#rowCancelBie').show();
			$('#rowAddBie').hide();
			$('#rowEditBie').hide();
			$('#btnDelBie').attr('disabled',true);
			disableButton('#btnDelBie');
			$("#actBie").val('A');
		break;
		case 'E':
			var grid = Ext.getCmp('xgridFicBcoBie');
			var store = grid.getStore();
			if (grid.getSelectionModel().selected.length == 1)
            {
				var sm = grid.getSelectionModel().getSelection();
				var rec = store.getAt(store.indexOf(sm[0]));
				eventBie('A');
				$("#idcbrebie").val(rec.get('idsigma'));
				$("#nroorde2").val(rec.get('nroorde'));
				$("#redific2").val(rec.get('redific'));
				$("#rentrad2").val(rec.get('rentrad'));
				$("#refpiso2").val(rec.get('refpiso'));
				$("#refunid2").val(rec.get('refunid'));
				$("#nporcen2").val(rec.get('nporcen'));
				$("#nratcm22").val(rec.get('nratcm2'));
				$("#nraccm22").val(rec.get('nraccm2'));
				$("#naoicm22").val(rec.get('naoicm2'));
				$("#actBie").val('E');
			}
			else
				infoMessage('Editar','Seleccione un registro!');
		break;
		case 'C':
			$('#divAddBie').hide();
			$('#rowAddBie').show();
			$('#rowEditBie').show();
			$('#rowSaveBie').hide();
			$('#rowCancelBie').hide();
			$('#btnDelBie').attr('disabled',false);
			enableButton('#btnDelBie');
			$("#idcbrebie").val('');
			$("#nroorde2").val('');
			$("#redific2").val('');
			$("#rentrad2").val('');
			$("#refpiso2").val('');
			$("#refunid2").val('');
			$("#nporcen2").val('');
			$("#nratcm22").val('');
			$("#nraccm22").val('');
			$("#naoicm22").val('');
		break;
		case 'S':
			var grid = Ext.getCmp('xgridFicBcoBie');
			var store = grid.getStore();
			
			if($('#nroorde2').val()!=''){
				if($("#actBie").val()=='A')
				{
					var r = Ext.create('Bien', {
						idsigma: '',
						cidindi: $('#cidfich').val(),
						nroorde: $('#nroorde2').val(),
						redific: $('#redific2').val(),
						rentrad: $('#rentrad2').val(),
						refpiso: $('#refpiso2').val(),
						refunid: $('#refunid2').val(),
						nporcen: $('#nporcen2').val(),
						nratcm2: $('#nratcm22').val(),
						nraccm2: $('#nraccm22').val(),
						naoicm2: $('#naoicm22').val(),
					});
					
					store.insert(store.data.length, r);
				}
				else{				
					var sm = grid.getSelectionModel().getSelection();
					grid.getStore().getAt(store.indexOf(sm[0])).set('idsigma', $('#idcbrebie').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nroorde', $('#nroorde2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('redific', $('#redific2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('rentrad', $('#rentrad2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('refpiso', $('#refpiso2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('refunid', $('#refunid2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nporcen', $('#nporcen2').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nratcm2', $('#nratcm22').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('nraccm2', $('#nraccm22').val());
					grid.getStore().getAt(store.indexOf(sm[0])).set('naoicm2', $('#naoicm22').val());
					grid.getStore().sync();				
				}
				eventBie('C');
			}
			else
				infoMessage('Guardar Recap. Bienes Comunes','Debe ingresar el n&uacute;mero!');
		break;
		case 'D':
			var grid = Ext.getCmp('xgridFicBcoBie');
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

function addRowsBie(data){
	var grid = Ext.getCmp('xgridFicBcoBie');
	var store = grid.getStore();
	var r = Ext.create('Bien', {
		idsigma: data.idsigma,
		cidindi: data.cidindi,
		nroorde: data.nroorde,
		redific: data.redific,
		rentrad: data.rentrad,
		refpiso: data.refpiso,
		refunid: data.refunid,
		nporcen: data.nporcen,
		nratcm2: data.nratcm2,
		nraccm2: data.nraccm2,
		naoicm2: data.naoicm2
	});
	store.insert(store.data.length, r);
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