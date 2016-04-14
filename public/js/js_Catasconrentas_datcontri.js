Ext.onReady(function(){
    Ext.QuickTips.init();    
	$("input[type='button']").button();	    
	
	Ext.define('DContri', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'id'},
			{name: 'label'},
			{name: 'text'},
            {name: 'value'}
        ]
    });
    
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'DContri',
        data: []
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridDContri',
		columnLines: true,
		store: store,
		height: '580px',
		viewConfig: {
        	listeners: {        		
                viewready: function(view) {
					if(datax){
						data.id = 1;
						data.label = "C&oacute;digo de Contribuyente:";
						data.text = datax.codigo;
						data.value = datax.codigo;
						addRowsDContri(data);
						
						data.id = 2;
						data.label = "Tipo Titular:";
						data.text = datax.tipo_detalle;
						data.value = datax.id_tipocontri;
						addRowsDContri(data);
						
						data.id = 3;
						data.label = "Tipo Doc. Identidad:";
						data.text = datax.tipodoc;
						data.value = datax.id_docu;
						addRowsDContri(data);
						
						data.id = 4;
						data.label = "Num. Doc. Identidad:";
						data.text = datax.numedoc;
						data.value = datax.numedoc;
						addRowsDContri(data);
						
						if(datax.id_tipocontri!='02'){
							data.id = 5;
							data.label = "Nombres:";
							data.text = datax.nombres;
							data.value = datax.nombres;
							addRowsDContri(data);
							
							data.id = 6;
							data.label = "Apellido Paterno:";
							data.text = datax.paterno;
							data.value = datax.paterno;
							addRowsDContri(data);
							
							data.id = 7;
							data.label = "Apellido Materno:";
							data.text = datax.materno;
							data.value = datax.materno;
							addRowsDContri(data);
						}
						if(datax.id_tipocontri=='02'){
							data.id = 8;
							data.label = "Raz&oacute;n Social:";
							data.text = datax.nombres;
							data.value = datax.nombres;
							addRowsDContri(data);
							
							data.id = 9;
							data.label = "Tipo de Persona Jur&iacute;dica:";
							data.text = datax.subtipo_detalle;
							data.value = datax.id_subtipocontri;
							addRowsDContri(data);
						}
						data.id = 10;
						data.label = "Departamento:";
						data.text = 'LIMA';
						data.value = '15';
						addRowsDContri(data);
						cargaUbigeo('#cidprov',2,'15','');
						
						data.id = 11;
						data.label = "Provincia:";
						data.text = 'LIMA';
						data.value = '01';
						addRowsDContri(data);
						cargaUbigeo('#ciddist',3,'15','01');
						
						data.id = 12;
						data.label = "Distrito:";
						data.text = datax.codpost;
						data.value = datax.id_post*1;
						addRowsDContri(data);
						
						data.id = 13;
						data.label = "Tel&eacute;fono:";
						data.text = datax.telefono1;
						data.value = datax.telefono1;
						addRowsDContri(data);
						
						data.id = 14;
						data.label = "Anexo:";
						data.text = datax.anexo1;
						data.value = datax.anexo1;
						addRowsDContri(data);
						
						data.id = 15;
						data.label = "E-mail:";
						data.text = datax.correo_e;
						data.value = datax.correo_e;
						addRowsDContri(data);
						
						data.id = 16;
						data.label = "Nombre de Zona:";
						data.text = datax.nombre_zona;
						data.value = datax.nombre_zona;
						addRowsDContri(data);
						
						data.id = 17;
						data.label = "Nombre de V&iacute;a:";
						data.text = datax.nombre_via;
						data.value = datax.id_via;
						addRowsDContri(data);
						
						data.id = 18;
						data.label = "N&deg; Municipal:";
						data.text = datax.numero;
						data.value = datax.numero;
						addRowsDContri(data);
						
						data.id = 19;
						data.label = "Nombre de Edificaci&oacute;n:";
						data.text = datax.nombre_edificio;
						data.value = datax.nombre_edificio;
						addRowsDContri(data);
						
						data.id = 20;
						data.label = "N&deg; Interior:";
						data.text = datax.numero2;
						data.value = datax.numero2;
						addRowsDContri(data);
						
						data.id = 21;
						data.label = "Manzana:";
						data.text = datax.manzana;
						data.value = datax.manzana;
						addRowsDContri(data);
						
						data.id = 22;
						data.label = "Lote:";
						data.text = datax.lote;
						data.value = datax.lote;
						addRowsDContri(data);
						
						data.id = 23;
						data.label = "Sub-Lote:";
						data.text = datax.sub_lote;
						data.value = datax.sub_lote;
						addRowsDContri(data);
					}
            	}
            }
    	},
		selModel: Ext.create('Ext.selection.CheckboxModel',{ mode : 'MULTI', checkOnly: true }),
        columns: [
		{
            dataIndex: 'id',
			hidden: true
        },{
            dataIndex: 'value',
			hidden: true
        },{
            text: 'Dato:',
            width: 160,
            dataIndex: 'label'
        },{
			text: 'Valor:',
			flex: 1,
            dataIndex: 'text'
        }]
    });
    
    grid.render('gridDContri');
});

function selectDatosContri(){	
	var grid = Ext.getCmp('xgridDContri');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
		var tperson = '';
		Ext.each(selectedRecordsArray, function (item) {			
			switch(item.data.id){
				case 1: 
					$('#cidcont').val($.trim(item.data.value)); 
				break;
				case 2:
					tperson = item.data.value;
					var v = item.data.text;
					v = v.replace("í", "i"); 
					var valor = $('#cidpersx option:contains('+$.trim(v).toUpperCase()+')').val();
					$('#cidpersx').val(valor);
				break;
				case 3: 
					var valor = $('#ciddocu option:contains('+$.trim(item.data.text).toUpperCase()+')').val();
					$('#ciddocu').val(valor); 
				break;
				case 4: 
					$('#numedoc').val($.trim(item.data.value)); 
				break;
				case 5:
					if(tperson=='01'){
						$('#nombres').val($.trim(item.data.value));
						$('#razsoc').val('');
					}
					if(tperson=='02'){
						$('#nombres').val('');
						$('#razsoc').val($.trim(item.data.value));
					}
					else						
						$('#nombres').val($.trim(item.data.value));
				break;
				case 6: 
					if(tperson=='01'){
						$('#apatern').val($.trim(item.data.value)); 
						$('#razsoc').val('');
					}
					else
					if(tperson=='02'){
						$('#apatern').val(''); 
					}
					else
						$('#apatern').val($.trim(item.data.value));
				break;
				case 7: 
					if(tperson=='01'){
						$('#amatern').val($.trim(item.data.value)); 
						$('#razsoc').val('');
					}					
					else
					if(tperson=='02'){
						$('#amatern').val('');
					}
					else
						$('#amatern').val($.trim(item.data.value));
				case 8: 
					if(tperson=='02'){
						$('#razsoc').val($.trim(item.data.value)); 
						$('#nombres').val('');
						$('#apatern').val('');
						$('#amatern').val('');
					}
					else
						$('#razsoc').val(''); 
				break;
				case 9: 
					var valor = $('#cidjuri option:contains('+$.trim(item.data.text).toUpperCase()+')').val();
					$('#cidjuri').val(valor); 
				break;
				case 10:
					$('#ciddpto').val($.trim(item.data.value));
				break;
				case 11:
					$('#cidprov').val($.trim(item.data.value));
				break;
				case 12:
					$('#ciddist').val($.trim(item.data.value));
				break;
				case 13:
					$('#nrtelef').val($.trim(item.data.value));
				break;
				case 14:
					$('#nranexo').val($.trim(item.data.value));
				break;
				case 15:
					$('#ve_mail').val($.trim(item.data.value));
				break;
				case 16:
					$('#zosecetp').val($.trim(item.data.value));
				break;
				case 17:
					$('#nomvia').val($.trim(item.data.text));
				break;
				case 18:
					$('#nromunip').val($.trim(item.data.value));
				break;
				case 19:
					$('#nmbredip').val($.trim(item.data.value));
				break;
				case 20:
					$('#nrointep').val($.trim(item.data.value));
				break;
				case 21:
					$('#manzanap').val($.trim(item.data.value));
				break;
				case 22:
					$('#cidlotep').val($.trim(item.data.value));
				break;
				case 23:
					$('#sublotep').val($.trim(item.data.value));
				break;
			}
		});
		if(tperson!='02')			
			enableButton('#btnSelectCon');
		else
			disableButton('#btnSelectCon');
			
		closePopup('#popcatasrentasdatos');
	}
	else{
		infoMessage('Alerta','Debe seleccionar al menos un dato!');
	}
}

function addRowsDContri(data){
	var grid = Ext.getCmp('xgridDContri');
	var store = grid.getStore();
	var r = Ext.create('DContri', {
		id: data.id,
		label: data.label,
		text: data.text,
		value: data.value,
	});
	store.insert(store.data.length, r);
}