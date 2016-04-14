Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();
     $('#frmlicendecjurada').validate({
		rules: {
            'txtNroLi': 'required',
            'cmbTipLicencia' : 'required',
            'txtFechaPre': 'required',
			'txtNomPer': 'required',
            'txtNomEs' : 'required',
            'txtRuc' : 'required'
           /* 'txtUbiEs' : 'required',
            'txtNroEs' : 'required',
            'txtDptoEs' : 'required',
            'txtInEs' : 'required',
            'txtMzEs' : 'required',
            'txtLtEs' : 'required',
            'txtModEs' : 'required',
            'txtUrb' : 'required',
            'txtAreaL' : 'required',
            'txtAreaAl' : 'required'*/
			//'txtApeMatContri': 'required',
			//'cmbDocContri': 'required',
			//'txtNumDoc': 'required',
			//'cmbDisContri': 'required',
		},
		messages: {
			'txtNroLi':     'Debe ingresar Nro. Lic.',
            'cmbTipLicencia' : 'Debe seleccionar un tipo',
            'txtFechaPre':  'Debe ingresar una fecha',
            'txtNomPer':    'Buscar Solicitante',
            'txtNomEs':     'Debe ingresar nombre de establecimiento',
            'txtRuc' :      'Debe ingresar nro de ruc'
            /*'txtUbiEs' :    'Debe ingresar ubicación',
            'txtNroEs' :    'Debe ingresar nro',
            'txtDptoEs' :   'Debe ingresar departamento',
            'txtInEs' :     'Debe ingresar interior',
            'txtMzEs' :     'Debe ingresar manzana',
            'txtLtEs' :     'Debe ingresar lote',
            'txtModEs' :    'Debe ingresar modulo',
            'txtUrb' :      'Debe ingresar urbanización',
            'txtAreaL' :    'Debe ingresar area del local',
            'txtAreaAl' :   'Debe ingresar area del almacen'*/
			//'txtApeMatContri': 'Debe ingresar el apellido materno',
			//'cmbDocContri': 'Seleccione el tipo de documento',
			//'txtNumDoc': { required: 'Ingrese el documento', digits: 'Ingrese s&oacute;lo n&uacute;meros' },
			//'cmbDisContri': 'Seleccione el distrito'
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
					infoMessage('Guardar Giro',data);
					if(data!='El Solicitante Existe')
					closePopup('#poplicendecjurada');
					Ext.getCmp('xgridLicen').getStore().load();
				},
				error: function() {
				}
			});
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
	strHtmlTr += "<td><input type='text' id='codigo" + IdRow + "' name='detallesPagos[0][" + IdRow + "]' value='" + codigo + "' class='cajaoff' size='10' maxlength='80' readonly='readonly'/></td>";
	strHtmlTr += "<td><input type='text' id='detalle" + IdRow + "' name='detallesPagos[1][" + IdRow+ "]' value='" + detalle + "'class='cajaoff' style='width:100%' maxlength='80' readonly='readonly'/></td>";
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
	$('input[name*="detallesPagos[0][]"]').autotab({ maxlength: 80, format: 'number' });
	$('input[name*="detallesPagos[1][]"]').autotab({ maxlength: 80, format: 'number' });
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
	$('#txtTipDoc').val(obj.get('tipo_doc'));
	$('#txtNroDoc').val(obj.get('documento'));
};

// Recupero Valores de Mantpred
window.muestraDatosPre = function(obj) {
	$('#txtCodPre').val(obj.get('cod_pre'));
	$('#txtDirePre').val(obj.get('nombre_via'));
	$('#txtUsoPre').val(obj.get('id_via'));
	//$('#txtNroDoc').val(obj.get('documento'));
};

// Recupero Valores de Mantgiro
window.muestraDatosGi = function(obj) {
	var flag = 0;
	$('input[name*="detallesPagos[0]"]').each( function(i) {
    	if(obj.get('idgiro')==$(this).val())
			flag = 1;			
	});
	
	if(flag==1){
        infoMessage('Alerta','  ');
    }
		//alert('existe');
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


    }
});

$('#personaJ').click(function(){


    if($('#personaJ').attr('checked'))
    {
        $('#txtApeNom').attr('readonly', false);
        $('#txtApeNom').removeClass("cajaoff").addClass('caja');


        $('#cmbTipPredio2').attr('disabled', false);
        //$('#cmbTipPredio2').removeClass("caja").addClass('cajaoff');


        $('#txtNroDocRe').attr('readonly', false);
        $('#txtNroDocRe').removeClass("cajaoff").addClass('caja');


        $('#txtPhone').attr('readonly', false);
        $('#txtPhone').removeClass("cajaoff").addClass('caja');


        $('#txtSUNARP').attr('readonly', false);
        $('#txtSUNARP').removeClass("cajaoff").addClass('caja');

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


/*
 $('#txtAreaAl').keydown(function() {
 $('#txtAreaTo').val = parseInt($('#txtAreaL').val) + parseInt($('#txtAreaAl').val);
 });
 */
/*
 $("#txtAreaL").change(function(){

 var suma = parseFloat($("#txtAreaL").val()) + parseFloat($("#txtAreaAl").val());
 $("#txtAreaTo").val('');
 // alert(suma);
 $("#txtAreaTo").val(suma.toFixed(2));


 });

 $("#txtAreaAl").change(function(){

 var suma = parseFloat($("#txtAreaL").val()) + parseFloat($("#txtAreaAl").val());
 $("#txtAreaTo").val('');
 // alert(suma);
 $("#txtAreaTo").val(suma.toFixed(2));


 });
 */