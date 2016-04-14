Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('Anun', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idSolLice', type: 'string'},
            {name: 'nro_licencia', type: 'string'},
            {name: 'cod_sol', type: 'string'},
            {name: 'nombre', type: 'string'},
            {name: 'nom_esta', type: 'string'},
            {name: 'dire_pred', type: 'string'},
            {name: 'fech_ing', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Anun',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'anunciosol/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridLicen',
        store: store,        
        title: 'Solicitudes de Auncios',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'N° Licencia',
            width: 75,            
            dataIndex: 'idSolLice',
            hidden:true
        },{
            text: 'N° Licencia',
            width: 75,
            dataIndex: 'nro_licencia'
        },{
            text: 'C&oacute;digo',
            width: 100,
            dataIndex: 'cod_sol',
            hidden:true
        },{
        	text: 'Nombres',
        	flex: 1,
            dataIndex: 'nombre'
        },{
            text: 'Establecimiento',
            flex: 1,
            dataIndex: 'nom_esta'
        },{
            text: 'Direcci&oacute;n',
            flex: 1,
            dataIndex: 'dire_pred'
        },{
            text: 'Fecha Ingreso',
            flex: 1,
            dataIndex: 'fech_ing'
        },{
            xtype:'actioncolumn',
            width:70,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Licencia',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    showPopup('anundecjurada/index?idSolLice='+rec.get('idSolLice')+'&cod_sol='+rec.get('cod_sol')+'&readonly=readonly'+'&accion=2','#popanundecjurada','900','670','Editar Solicitud');
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
                        //generarpdf();
                    }

            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridLicen',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; licencias"
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
	
	proxy.extraParams = {page: '1', start: '0', limit: '10', rdcriterio: rdCriterio, criterio: txtCriterio};
	store.loadPage(1); 
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

function nuevoAnuncio(){
	
	showPopup('anundecjurada/index'+'?accion=1','#popanundecjurada','900','670','Solicitud - Declaraci&oacute;n Jurada');
}
function generarpdf(){
    //d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
    //var tesodesde=$('#tesodesde').val();
    //var tesohasta=$('#tesohasta').val();
    //showPopupReport('tipo=pdf&nombrereporte=rpt_solicitud_declaracion_jurada&param=FECDESDE^'+tesodesde+'|FECHASTA^'+tesohasta,'pouprptemitido',700,600,'Recibos emitidos');
    showPopupReport('tipo=pdf&nombrereporte=rpt_solicitud_declaracion_jurada','pouprptemitido',750,600,'Solicitud de Licencia de Funcionamiento');
}

$('#rdCriterio1').click(function(){

    if($('#rdCriterio1').attr('checked'))
    {
        $('#txtCriterio').val('');
        $('#txtCriterio').focus();
        //removeClass('myClass yourClass');
        //.addClass( "myClass yourClass" );
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
/*
$('#txtCriterioNombre').keypress(function (e) {
   
    if($('#rdCriterio2').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioAPaterno').keypress(function (e) {

    if($('#rdCriterio2').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioAMaterno').keypress(function (e) {

    if($('#rdCriterio2').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioRazon').keypress(function (e) {

    if($('#rdCriterio4').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtDocumento').keypress(function (e) {

    if($('#rdCriterio3').attr('checked') ){
        return validaTeclas(event,'number');}
});
*/