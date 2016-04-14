Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('Licen', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idSolLice', type: 'string'},
            {name: 'nro_licencia', type: 'string'},
            {name: 'cod_sol', type: 'string'},
            {name: 'nombre', type: 'string'},
            {name: 'nom_esta', type: 'string'},
            {name: 'dire_pred', type: 'string'},
            {name: 'fech_ing', type: 'string'},
            {name: 'tipo_cpto',type: 'string'},
            {name: 'nro_expediente',type: 'string'},
            {name: 'resolucion',type: 'string'},
            {name: 'generado',type: 'string'},
            {name: 'area',type: 'string'},
            {name: 'año',type: 'string'},
            {name: 'nro_informe',type: 'string'},
            {name: 'nro_memo',type: 'string'},
            {name: 'tipo', type: 'string'},
            {name: 'idrecibo', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Licen',
    	autoLoad: true,
	    pageSize: 20,
        proxy: {
            type: 'ajax',
            url : 'licensol/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridLicen',
        store: store,        
        title: 'Solicitudes de Licencias',
        viewConfig: {
        	loadMask: {msg: 'cargando...'},
            stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
                switch(record.get('generado')){
                    case '01': clsRow='xrow-liceR'; break
                    case '02': clsRow='xrow-liceE'; break
                    case '03': clsRow='xrow-liceP'; break
                    //case '01': clsRow='xrow-liceP'; break
                    //case '3.0': clsRow='xrow-obs'; break
                    //case '4.0': clsRow='xrow-obs'; break
                }
                return clsRow;

    	    }
        },

        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),
        {
            text: 'N° Solicitud',
            width: 75,
            dataIndex: 'idSolLice',
            hidden:true
        },
        {
            text: 'N° Licencia',
            width: 75,
            dataIndex: 'nro_licencia',
            hidden:true
        },
        {
            text: 'Id Recibo',
            width: 75,
            dataIndex: 'idrecibo',
            hidden:true

        },
        {
            text: 'Año',
            width: 70,
            dataIndex: 'año'

        },
         {  text: 'Nro Informe',
            width: 75,
            dataIndex: 'nro_informe',
            hidden:true

        },
        {
            text: 'Nro Memo',
            width: 75,
            dataIndex: 'nro_memo',
            hidden:true

        },
        {
            text: 'Generado',
            width: 75,
            dataIndex: 'generado',
            hidden:true
        },
        {
            text: 'C&oacute;digo',
            width: 100,
            dataIndex: 'cod_sol',
            hidden:true
        },{
        	text: 'Nombres y Apellidos',
            width: 300,
            dataIndex: 'nombre'
        },{
            text: 'Tipo',
            dataIndex: 'tipo',
            hidden:true
        }
        ,{
            text: 'Establecimiento',
            width: 300,
            dataIndex: 'nom_esta'
        },{
            text: 'Direcci&oacute;n',
            width: 300,
            dataIndex: 'dire_pred'
        },
        {
            text: 'Area',
            width: 75,
            dataIndex: 'area'
        },
        {
            text: 'Tipo',
            width: 75,
            dataIndex: 'tipo_cpto'

        },{
            text: 'Fecha Ingreso',
            width: 90,
            dataIndex: 'fech_ing'
        },
        {
            text: 'N° Expediente',
            width: 90,
            dataIndex: 'nro_expediente'
        },
        {
            text: 'N° Resolucion',
            width: 90,
            dataIndex: 'resolucion'
        },
        {
            xtype:'actioncolumn',
            width:70,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Licencia',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('licendecjurada/index?idSolLice='+rec.get('idSolLice')+'&cod_sol='+rec.get('cod_sol')+'&readonly=readonly'+'&accion=2','#poplicendecjurada','900','565','Editar Solicitud');
                }
            },
                {
                icon: urljs + 'img/delete.png',
                style: 'cursor: pointer',
                tooltip: 'Anular Licencia',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                }
              },
                {
                    icon: urljs + 'img/reload.png',
                    style: 'cursor: pointer',
                    tooltip: 'Imprimir',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        showPopupReport('tipo=pdf&nombrereporte=DDJJ_Licencia_Funcionamiento&param=IDSOLLICE^'+rec.get('idSolLice')+'|COD_PROPIE^'+rec.get('cod_sol'),'pouprptemitido',700,600,'Solicitud de Licencia de Funcionamiento');
                    }

            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridLicen',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Solicitudes"
        })
    });
    
    grid.render('gridLicen');
    
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
        	buscarContri();
        }
    });
});

function buscarContri(){
	var grid = Ext.getCmp('xgridLicen');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
    var cmbcpto = $('#cmbCpto').val();



    proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, criteriocmb: cmbcpto};

   store.load({
        callback: function(documents, options, success){}
    });
}

function anularLicencia(idLic){
	var grid = Ext.getCmp('xgridLicen');
	var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
	if(selectedRecordsArray.length>0){
		
		 var showResult = function(btn){
			if(btn=='yes'){		
				
				var idList = [];
				Ext.each(selectedRecordsArray, function (item) {		  	
					idList.push(item.data.codigo); 
				});
				
				Ext.Ajax.request({
		            url: urljs + "mantcontri/eliminar",
		            method: "POST",
		            params: {json: JSON.stringify(idList)},
		            success: function(){
		                //console.log("ok");		            	
		            	grid.getStore().loadPage(grid.getStore().currentPage);
		            },
		            failure: function(response, opts){
		                //console.log("failed");
		            	infoMessage('Contribuyentes','Error al intentar eliminar!');
		            }
		        });
			}
 		};
 		
 		confirmMessage('Contribuyentes','Seguro de eliminar?',showResult);
		
	}
	else{
		infoMessage('Contribuyentes','Seleccione al menos un registro!');
	}
}
function nuevoLicencia(){
    showPopup('licendecjurada/index'+'?accion=1','#poplicendecjurada','900','565','Solicitud - Declaraci&oacute;n Jurada');
}


function Imprime_Carton(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length > 0){
        var idSolLice = "";
        Ext.each(selectedRecordsArray,function (item){
            idSolLice = item.data.idSolLice;
            nro_licencia = item.data.nro_licencia;
        });

        $.ajax( {
            type : "POST",
            url : "licenexpediente/verificar",
            data :"idSolLice="+idSolLice+"&nro_licencia="+nro_licencia,
            success : function(response) {
                if (response){
                    showPopupReport('schema=&tipo=pdf&nombrereporte=carton&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'Carton');

                }else{
                    infoMessage('Alerta','No Genero Certificado o Resolución, Verificar');
                }
            }
        });

    }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');

    }
}


function Genera_Recibo(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var ColSol = "";
        var idrecibo = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            ColSol = item.data.cod_sol;
            idrecibo = item.data.idrecibo;
        });
        $.ajax( {
            type : "POST",
            url : "licenrecibo/verificar",
            data :"idSolLice="+idSolLice+"&idrecibo="+idrecibo,
            success : function(response) {
                if (response){
                    infoMessage('Alerta','Recibo Generado');
                }else{
                    showPopup('licenrecibo/index?idSolLice='+idSolLice+'&ColSol='+ColSol+'&idrecibo='+idrecibo,'#popGenerarRecibo','700','160','Generar Recibo');
                }
            }
        });

    }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');
    }

}
function Genera_Expediente(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var ColSol = "";
        var idrecibo = "";
        var nro_expediente = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            ColSol = item.data.cod_sol;
            idrecibo = item.data.idrecibo;
            nro_expediente = item.data.nro_expediente;
        });
        $.ajax( {
            type : "POST",
            url : "licenrecibo/verificarpago",
            data :"idSolLice="+idSolLice+"&idrecibo="+idrecibo,
            success : function(response) {
                if (response){
                    infoMessage('Alerta','Recibo no Cancelado');
                }else{
                    $.ajax( {
                        type : "POST",
                        url : "licenexpediente/consulta",
                        data :"idSolLice="+idSolLice+"&nro_expediente="+nro_expediente,
                        success : function(response) {
                            if (response){
                                infoMessage('Alerta','Expediente Generado');
                            }else{
                                Validar_Expediente();
                            }
                        }
                    });

                }
            }
        });

    }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');
    }

}
function Validar_Expediente(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var idrecibo = "";
        var ColSol = "";

        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            idrecibo = item.data.idrecibo;
            ColSol = item.data.cod_sol;
        });
            $.ajax( {
                type : "POST",
                url : "licenexpediente/consultarecibo",
                data :"idSolLice="+idSolLice+"&idrecibo="+idrecibo,
                success : function(response) {
                    if (response){
                        showPopup('licenexpediente/expediente?idSolLice='+idSolLice+'&ColSol='+ColSol+'&idrecibo='+idrecibo,'#popGenerarExpediente','700','160','Generar Expediente');
                    }else{
                        infoMessage('Alerta','Recibo no generado');

                    }
                }
            });

    }
}
function Genera_Licencia(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var ColSol = "";
        var nro_licencia = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            ColSol = item.data.cod_sol;
            nro_licencia = item.data.nro_licencia;
        });
        $.ajax( {
            type : "POST",
            url : "licenexpediente/verificar",
            data :"idSolLice="+idSolLice+"&nro_licencia="+nro_licencia,
            success : function(response) {
                if (response){
                    infoMessage('Alerta','Licencia Generada');
                }else{
                    Validar_Licencia();                }
            }
        });
        }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');
    }
}
function Validar_Licencia(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var nro_expediente
        var ColSol = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            nro_expediente = item.data.nro_expediente
            ColSol = item.data.cod_sol;
        });
        $.ajax( {
            type : "POST",
            url : "licenexpediente/consultaexpedienterecibo",
            data :"idSolLice="+idSolLice+"&nro_expediente="+nro_expediente,
            success : function(response) {
                if (response){
                    showPopup('licenexpediente/index?idSolLice='+idSolLice+'&ColSol='+ColSol,'#popGenerarExp','700','200','Generar Licencia');
                }else{
                    infoMessage('Alerta','Genere Expediente');
                }
            }
        });
    }
}
function Genera_Informe(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var ColSol = "";
        var nro_informe = "";
        var nro_memo = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            ColSol = item.data.cod_sol;
            nro_informe = item.data.nro_informe;
            nro_memo = item.data.nro_memo;
        });
        $.ajax( {
            type : "POST",
            url : "liceninforme/verificar",
            data :"idSolLice="+idSolLice+"&nro_informe="+nro_informe+"&nro_memo="+nro_memo,
            success : function(response) {
            //    if (response){
            //        showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADA&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA');
            //    }
			//	else{
                    Validar_Informe();                
			//	}
            }
        });
    }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');
    }

}
function Validar_Informe(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var nro_expediente
        var ColSol = "";
        var generado = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            nro_expediente = item.data.nro_expediente
            ColSol = item.data.cod_sol;
            generado = item.data.generado;
        });
                if (generado == '03'){
                    //showPopup('liceninforme/index?idSolLice='+idSolLice+'&ColSol='+ColSol,'#popGenerarInfor','820','255','Generar Informe');
					showPopup('liceninforme/generarinforme?idSolLice='+idSolLice+'&ColSol='+ColSol,'#popGenerarInforme','380','60','Generar Informes');
                }else{
                    infoMessage('Alerta','Genere Expediente');
                }
            }

}

function Generar_Informe(){
    var idsolicitud = $('#idSolLice').val();
    var codsolicitud = $('#ColSol').val();
    var defensa = $('#defensa').val();
    var informe = $('#informeid').val();
    showPopup('liceninforme/index?idSolLice='+idsolicitud+'&ColSol='+codsolicitud+'&defensa='+defensa+'&informe='+informe,'#popGenInforme','825','280','Generar Informe');
}

function Generar_Informe2(){
    var idsolicitud = $('#idSolLice').val();
    var codsolicitud = $('#ColSol').val();
    var defensa = $('#defen').val();
    var informe = $('#informeid').val();
    showPopup('liceninforme/index?idSolLice='+idsolicitud+'&ColSol='+codsolicitud+'&defensa='+defensa+'&informe='+informe,'#popGenInforme','820','260','Generar Informe');
}



function Genera_Resolucion(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var ColSol = "";
        var nro_informe = "";
        var nro_memo = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            ColSol = item.data.cod_sol;
            nro_informe = item.data.nro_informe;
            nro_memo = item.data.nro_memo;
        });
        $.ajax( {
            type : "POST",
            url : "liceninforme/verificar",
            data :"idSolLice="+idSolLice+"&nro_informe="+nro_informe+"&nro_memo="+nro_memo,
            success : function(response) {
                //if (response){
                  //  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADA&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA');
                //}else{
                    Validar_Resolucion();
                //}
            }
        });

    }else{
        infoMessage('Alerta','Debe seleccionar una solicitud!');
    }
}
function Validar_Resolucion(){
    var grid = Ext.getCmp('xgridLicen');
    var selectedRecordsArray = grid.getView().getSelectionModel().getSelection();
    if(selectedRecordsArray.length>0){
        var idSolLice = "";
        var nro_expediente = "";
        var nro_informe = "";
        var ColSol = "";
        var generado = "";
        Ext.each(selectedRecordsArray, function (item) {
            idSolLice = item.data.idSolLice;
            nro_expediente = item.data.nro_expediente
            nro_informe = item.data.nro_informe;
            ColSol = item.data.cod_sol;
            generado = item.data.generado;
        });

        if(nro_informe==''){
            infoMessage('Alerta','Genere Informe');
        }else if (generado == '03'){
			showPopup('licenresolucion/index?idSolLice='+idSolLice+'&ColSol='+ColSol,'#popGenerarResolucion','820','255','Generar Resolucion');
        }else{
            infoMessage('Alerta','Genere Expediente');
        }
    }

}

function Generar_Resolucion(){
    var idsolicitud = $('#idSolLice').val();
    var codsolicitud = $('#ColSol').val();
    var defensa = $('#defensa').val();
    showPopup('licenresolucion/index?idSolLice='+idsolicitud+'&ColSol='+codsolicitud+'&defensa='+defensa,'#popGenResolucion','820','255','Generar Informe3');
}

function Generar_Resolucion2(){
    var idsolicitud = $('#idSolLice').val();
    var codsolicitud = $('#ColSol').val();
    var defensa = $('#defensa').val();
    showPopup('licenresolucion/index?idSolLice='+idsolicitud+'&ColSol='+codsolicitud+'&defensa='+defensa,'#popGenResolucion','820','255','Generar Informe3');
}


$('#rdCriterio1').click(function(){

    if($('#rdCriterio1').attr('checked'))
    {
        $('#txtCriterio').val('');
        $('#txtCriterio').focus();
        $('#C').removeClass( "invisible" );
        $('#N').addClass( "invisible" );
        $('#R').addClass( "invisible" );
        $('#D').addClass( "invisible" );

        $('#C input').val('');
        $('#N input').val('');
        $('#R input').val('');
        $('#D input').val('');
    }
});

$('#rdCriterio2').click(function(){

    if($('#rdCriterio2').attr('checked'))
    {
        $('#txtCriterio').val('');
        $('#txtCriterio').focus();
        $('#C').addClass( "invisible" );
        $('#N').removeClass( "invisible" );
        $('#R').addClass( "invisible" );
        $('#D').addClass( "invisible" );

        $('#C input').val('');
        $('#N input').val('');
        $('#R input').val('');
        $('#D input').val('');
    }
});

$('#rdCriterio3').click(function(){

    if($('#rdCriterio3').attr('checked'))
    {
        $('#txtCriterio').val('');
        $('#txtCriterio').focus();
        $("#txtCriterio").attr('maxlength','11');

        $('#C').addClass( "invisible" );
        $('#N').addClass( "invisible" );
        $('#R').addClass( "invisible" );
        $('#D').removeClass( "invisible" );

        $('#C input').val('');
        $('#N input').val('');
        $('#R input').val('');
        $('#D input').val('');
    }
});
$('#rdCriterio4').click(function(){

    if($('#rdCriterio4').attr('checked'))
    {
        $('#txtCriterio').val('');
        $('#txtCriterio').focus();
        $("#txtCriterio").attr('maxlength','11');

        $('#C').addClass( "invisible" );
        $('#N').addClass( "invisible" );
        $('#R').removeClass( "invisible" );
        $('#D').addClass( "invisible" );

        $('#C input').val('');
        $('#N input').val('');
        $('#R input').val('');
        $('#D input').val('');
    }
});
$('#txtCriterio').blur(function(){
    var numCeros = '0000000'; // pon el n� de ceros que necesites
    var valor =  $('#txtCriterio').val().trim();
    var radio = $('#rdCriterio3').val();
    // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterio3').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio
    {
        //$('#txtCriterio').val(valor);
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtCriterio').val(valor2);  //obj.value = valor;
    }
    else{
        $('#txtCriterio').val(valor);
    }

});
$('#txtCriterio').keypress(function (e) {

    if($('#rdCriterio3').attr('checked') ){
        return validaTeclas(event,'number');}
});


function ImprimirInforme()
{
	//console.log(11111)
	
	var idSolLice=$('#idSolLice2').val();
	var defensas=$('#defensas').val();
	var tipopersona=$('#TipoPers2').val();
	var tiposolicitud=$('#TipoLice2').val();
	
	
	//INDETERMINADA
	if(defensas == 'SD' && tipopersona == 'N' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADA&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA NATURAL');
	
	else if(defensas == 'CD' && tipopersona == 'N' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADACD&param=IDSOLLICE^'+idSolLice,'#pouprptcartonn',700,600,'PN INDETERMINADA NATURAL');

	else if(defensas == 'SD' && tipopersona == 'J' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADAJURISD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA JURIDICA');
	 
	else if(defensas == 'CD' && tipopersona == 'J' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADAJURICD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA JURIDICA');	

	//TEMPORAL	  
    else if(defensas == 'SD' && tipopersona == 'N' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_SD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');	
	 
	else if(defensas == 'CD' && tipopersona == 'N' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_CD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');
	 
    else if(defensas == 'SD' && tipopersona == 'J' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_JURISD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');
	 
	else if(defensas == 'CD' && tipopersona == 'J' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_JURICD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');
  
	  
}

function ImprimirResolucion()
{
	var idSolLice=$('#idSolLice2').val();
	var tipoinforme=$('#Tipoinforme').val();
	var tipopersona=$('#TipoPers2').val();
	var tiposolicitud=$('#TipoLice2').val();	

    console.log(idSolLice,tipoinforme,tipopersona,tiposolicitud);
	//INDETERMINADA	
	 if(tipoinforme == 'SD' && tipopersona == 'N' && tiposolicitud == '0001')	
	   showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADARSD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN RESOLUCION NATURAL');
	
	 else if(tipoinforme == 'CD' && tipopersona == 'N' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADARCD&param=IDSOLLICE^'+idSolLice,'#pouprptcartonn',700,600,'PN RESOLUCION NATURAL');
 
	 else if(tipoinforme == 'SD' && tipopersona == 'J' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADAJURIRSD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA JURIDICA');
	 
	 else if(tipoinforme == 'CD' && tipopersona == 'J' && tiposolicitud == '0001')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_INDETERMINADAJURIRCD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN INDETERMINADA JURIDICA');
 
	//TEMPORAL
    else if(tipoinforme == 'SD' && tipopersona == 'N' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_RSD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');	

	else if(tipoinforme == 'CD' && tipopersona == 'N' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_RCD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');	
  
	else if(tipoinforme == 'SD' && tipopersona == 'J' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_JURIRSD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');	
    
	else if(tipoinforme == 'CD' && tipopersona == 'J' && tiposolicitud == '0002')
	  showPopupReport('schema=&tipo=pdf&nombrereporte=PN_TEMPORAL_JURIRCD&param=IDSOLLICE^'+idSolLice,'#pouprptcarton',700,600,'PN TEMPORAL NATURAL');	

  
}


