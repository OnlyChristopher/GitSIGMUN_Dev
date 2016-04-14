Ext.onReady(function(){
    Ext.QuickTips.init();

    loadGridDocumentos();
    redimGridHidden('xgridFicIndDoc',705,180,1);

    $("input[type='button']").button();
     $('#frmlicendecjurada').validate({
		rules: {
            'txtNroLi': 'required',
            'cmbTipLicencia' : 'required',
            'txtFechaPre': 'required',
			'txtNomPer': 'required',
            'txtRuc' : 'required',
            'cmbzonificacion' : 'required',
            'txtAreaTo' : 'required'
		},
		messages: {
		    'txtNroLi':     'Debe ingresar Nro. Lic.',
            'cmbTipLicencia' : 'Debe seleccionar un tipo',
            'txtFechaPre':  'Debe ingresar una fecha',
            'txtNomPer':    'Buscar Solicitante',
            'txtNomEs':     'Debe ingresar nombre de establecimiento',
            'txtRuc' :      'Debe ingresar nro de ruc',
            'cmbzonificacion' : 'Debe seleccionar zonificacion',
            'txtAreaTo' :    'Debe ingresar Area'

		},
		debug: true,
		errorElement: 'div',
		ignore: [],
		submitHandler: function(form){
			$.ajax({
				type: "POST",
				url: urljs + "Licendecjurada/grabar",
				data: $('#frmlicendecjurada').serializeObject(),
				success: function(data) {
                    infoMessage('Se Genero la Solicitud',data);
					if(data!='El Solicitante Existe')
					closePopup('#poplicendecjurada');
					Ext.getCmp('xgridLicen').getStore().load();
				},
				error: function() {
				}
			});
            grabarDocumentos();
		}

});
    $("#txtAreaL").val('');
    $("#txtAreaAl").val('');



    $('#txtNroLi').live('blur', function(){
        this.value = this.value.toUpperCase();

        var numCeros = '0000000';
        var valor =  $('#txtNroLi').val().trim();
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtNroLi').val(valor2);
      });



});


//Eventos Pagos
function addRowPagos(codigo,detalle){
	var strHtmlTr = "";
	var IdRow = $('#cDetPagos').val();
	IdRow++;
	strHtmlTr += "<tr id='rowPagos" + IdRow + "'>";
	strHtmlTr += "<td><input type='text' id='codigoGiro" + IdRow + "' name='codigoGiro[" + IdRow + "][0]' value='" + codigo + "' class='cajaoff' size='10' maxlength='80' readonly='readonly'/></td>";
	strHtmlTr += "<td><input type='text' id='detalleGiro" + IdRow + "' name='codigoGiro[" + IdRow+ "][1]' value='" + detalle + "'class='cajaoff' style='width:100%' maxlength='80' readonly='readonly'/></td>";
	strHtmlTr += "<td>";
	strHtmlTr += "<input type='button' class='btnDelDetPagos' value='Eliminar' onclick='delRowPagos("+IdRow+")'/>";
    strHtmlTr += "</td>";
    strHtmlTr += "</tr>";

	$("#detallePago").append(strHtmlTr);
	$('.btnDelDetPagos').button();
	$('#cDetPagos').val(IdRow);
	evenRowsPagos();
}
function maxRowPagos(){
 var max = $('#cDetPagos').val();
     if (max == 3) {
     //alert();
     infoMessage('Alerta','Solo puede ingresar 3 giros');
      //   alert(max);
     }else{
        showPopup('mantgiro/buscar','#popBusGiro','700','400','Buscador de Giros');
     }
}

function evenRowsPagos(){
	$('input[name*="codigoGiro[]"]').autotab({ maxlength: 80, format: 'number' });
	$('input[name*="detalleGiro[]"]').autotab({ maxlength: 80, format: 'number' });
}

function delRowPagos(sRow){

	var showResult = function(btn){
		if(btn=='yes')
			$("#rowPagos" + sRow).remove();
        sRow = sRow -1 ;
        $('#cDetPagos').val(sRow);
	};

	confirmMessage('Eliminar','Seguro de eliminar?',showResult);

}


// Recupero Valores de Mantpers
window.muestraDatosSol = function(obj) {
	$('#txtCodPer').val(obj.get('codigo'));
	$('#txtNomPer').val(obj.get('nombres'));
	$('#txtDirPer').val(obj.get('direccion'));
    if($.trim(obj.get('tipo_doc'))== 'DNI')
    {
      $('#txtNroDoc').val($.trim(obj.get('documento')));
      $('#txtNroRuc').val('');
    }else{
      $('#txtNroRuc').val($.trim(obj.get('documento')));
      $('#txtNroDoc').val('');
    }
    $('#txtDis').val(obj.get('distrito'));
};

// Recupero Valores de Mantpred
window.muestraDatosPre = function(obj) {
	$('#txtCodPre').val(obj.get('cod_pre'));
	$('#txtDirePre').val(obj.get('nombre_via'));
	$('#txtUsoPre').val(obj.get('id_via'));
};

// Recupero Valores de Mantgiro
window.muestraDatosGi = function(obj) {
	var flag = 0;
	$('input[name*="codigoGiro"]').each( function(i) {
    	if(obj.get('idgiro')==$(this).val())
			flag = 1;			
	});
	
	if(flag==1){
        infoMessage('Alerta','  ');
    }

	else{
		addRowPagos(obj.get('idgiro'),obj.get('descrip'));
    }
};


function getTipoAnuncio(){

    var cmbTipAnun = $('#cmbTipAnun').val();

    if(cmbTipAnun=='0014')
    {
        $('#txtOtroTip').attr('readonly', false);
        $('#txtOtroTip').removeClass("cajaoff").addClass('caja');
        $('#txtOtroTip').focus();
    }
    else
    {
        $('#txtOtroTip').attr('readonly', true);
        $('#txtOtroTip').removeClass("caja").addClass('cajaoff');

    }
}

$('#chkOtro').click(function(){

    if($('#chkOtro').attr('checked'))
    {
        $('#txtOtro').attr('readonly', false);
        $('#txtOtro').removeClass("cajaoff").addClass('caja');
        $('#txtOtro').focus();
    }
    else
    {
        $('#txtOtro').attr('readonly', true);
        $('#txtOtro').removeClass("caja").addClass('cajaoff');
        $('#txtOtro').val('');

    }
});


$('#personaN').click(function(){


     if($('#personaN').attr('checked'))
    {
        $('#txtApeNom').attr('readonly', true);
        $('#txtApeNom').removeClass("caja").addClass('cajaoff');


        $('#cmbTipPredio2').attr('disabled', true);
        //$('#cmbTipPredio2').removeClass("caja").addClass('cajaoff');


        $('#txtNroDocRe').attr('readonly', true);
        $('#txtNroDocRe').removeClass("caja").addClass('cajaoff');


        $('#txtPhone').attr('readonly', true);
        $('#txtPhone').removeClass("caja").addClass('cajaoff');


        $('#txtSUNARP').attr('readonly', true);
        $('#txtSUNARP').removeClass("caja").addClass('cajaoff');

        $('#txtRemail').attr('readonly', true);
        $('#txtRemail').removeClass("caja").addClass('cajaoff');


    }
});

$('#personaJ').click(function(){


    if($('#personaJ').attr('checked'))
    {
        $('#txtApeNom').attr('readonly', false);
        $('#txtApeNom').removeClass("cajaoff").addClass('caja');


        $('#cmbTipPredio2').attr('disabled', false);

        $('#txtNroDocRe').attr('readonly', false);
        $('#txtNroDocRe').removeClass("cajaoff").addClass('caja');


        $('#txtPhone').attr('readonly', false);
        $('#txtPhone').removeClass("cajaoff").addClass('caja');


        $('#txtSUNARP').attr('readonly', false);
        $('#txtSUNARP').removeClass("cajaoff").addClass('caja');

        $('#txtRemail').attr('readonly', false);
        $('#txtRemail').removeClass("cajaoff").addClass('caja');



    }
});

$('#cmbTipLicencia').change(function(){
    var tipo=($(this).val());
    $.ajax({
        type: "POST",
        url: "licendecjurada/conslicen",
        data: 'cmbTipLicencia='+tipo,
        success: function(data) {
            $('#cmbCptoLicencia').html(data);

        }
    });
});

function loadGridDocumentos(){
    var codigo=$('#hd_codigo').val();
    var anno=$('#hd_anno').val();
    var cod_pred="";
    var anexo="";
    var sub_anexo="";
    var tipo_mov=$('#hd_tipomov').val();

    Ext.define('Doc', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'item'},
            {name: 'descrip'}
        ]
    });
    var store = Ext.create('Ext.data.ArrayStore', {
        model: 'Doc',
        data: []
    });

    var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridFicIndDoc',
        columnLines: true,
        store: store,
        width: '950px',
        height: '140px',
        viewConfig: {
            listeners: {
                viewready: function(view) {
                    Ext.Ajax.request({
                        url: urljs + "licendecjurada/griddocumentos",
                        method: "POST",
                        params: {codigo: codigo,anno: anno,cod_pred: cod_pred,anexo: anexo,sub_anexo: sub_anexo},
                        success: function(response){
                            data = Ext.JSON.decode(response.responseText);
                            var strItems = "";
                            for(var i=0 ; i < data.length; i++){
                                strItems = strItems + data[i].idinsta + '|';
                                addRowsDocumento(data[i]);
                            }
                            //Para ver si se elimina
                            $('#oldDocItems').val(strItems);
                        }
                    });
                }
            }
        },
        columns: [
            {   text: 'Item',
                width: 40,
                dataIndex: 'item'
            },
            {
                text: 'Documentos',
                width: 660,
                dataIndex: 'descrip',
                tdCls: 'mayuscula',
                editor: {
                    allowBlank: true
                }
            }
        ],
        plugins: [cellEditing]
    });

    grid.render('gridFicIndDoc');
    grid.setDisabled(false);
}


function addRowsDocumento(data){
    var grid = Ext.getCmp('xgridFicIndDoc');
    var store = grid.getStore();
    var r = Ext.create('Doc', {
        item: data.item,
        descrip: data.descrip
    });
    store.insert(store.data.length, r);
}

function grabarDocumentos() {
    data = $("#frmpredios").serializeObject();

    var dataDoc = new Array();
    var gridInstal = Ext.getCmp('xgridFicIndDoc');
    if(gridInstal.getStore().data.length>0){
        var i=0;
        gridInstal.getStore().each(function(rec) {
            var rowsDoc = {
                descrip: rec.get('descrip')
            }
            dataDoc[i] = rowsDoc;
            i++;
        });
        data['Doc'] = dataDoc;
    }
        Ext.Ajax.request({
            url: urljs + "Licendecjurada/gpredios",
            method: "POST",
            params: {json: JSON.stringify(data)},
            success: function(response){
                console.log("Se guardo los documentos anexados")
            }
        });

}