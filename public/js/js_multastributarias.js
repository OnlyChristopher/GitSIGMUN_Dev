Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();

    Ext.define('Contri', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'nombres', type: 'string'},
            {name: 'documento', type: 'string'},
            {name: 'direccion', type: 'string'}
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'Contri',
        autoLoad: true,
        pageSize: 10,
        proxy: {
            type: 'ajax',
            url : 'multastributarias/consulta',
            reader: {
                type: 'json',
                root: 'rows'
            }
        }
    });

    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridMultas',
        store: store,
        title: 'Contribuyentes',
        viewConfig: {
            loadMask: {msg: 'cargando...'}
        },
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [
            Ext.create('Ext.grid.RowNumberer'),
            {
                 text: 'C&oacute;digo',
            width: 60,            
            dataIndex: 'codigo'
        },{
        	text: 'Nombres',
        	flex: 1,
            dataIndex: 'nombres'
        },{
            text: 'Nro. Documento',
            width: 105,
            dataIndex: 'documento'            
        },{
            text: 'Direcci&oacute;n',
            flex: 1,//width: 560,
            dataIndex: 'direccion'
        },{
                xtype:'actioncolumn',
                width:70,
                items: [
                    /*
                     {
                     icon: urljs + 'img/edit.png',
                     tooltip: 'Editar Contribuyente',
                     handler: function(grid, rowIndex, colIndex) {
                     var rec = grid.getStore().getAt(rowIndex);
                     //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                     showPopup('mantcontri/formu?codigo='+rec.get('codigo'),'#popcontri','720','440','Editar Contribuyente');
                     },
                     getClass: function(value,metadata,record){
                     if (mod=='14')
                     return gridColumnAction(false);

                     }
                     },{
                     icon: urljs +'img/delete.png',
                     tooltip: 'Eliminar Contribuyente',
                     handler: function(grid, rowIndex, colIndex) {
                     var rec = grid.getStore().getAt(rowIndex);
                     var showResult = function(btn){
                     if(btn=='yes')
                     $.ajax({
                     type: "GET",
                     url: 'mantcontri/eliminar',
                     data: 'codigo='+rec.get('codigo'),
                     success: function(data){
                     infoMessage('Eliminando',data);

                     grid.getStore().load(grid.getStore().currentPage);
                     }
                     });
                     };
                     confirmMessage('Eliminar','Seguro de Eliminar el c&oacute;digo '+rec.get('codigo')+'?',showResult);
                     },
                     getClass: function(value,metadata,record){
                     if (mod=='14')
                     return gridColumnAction(false);

                     }
                     },{
                     icon: urljs + 'img/view.png',
                     tooltip: 'Declaraci&oacute;n Jurada',
                     handler: function(grid, rowIndex, colIndex) {
                     var rec = grid.getStore().getAt(rowIndex);
                     //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                     showPopup('rentasdecjurada/index?codigo='+rec.get('codigo'),'#popdecjurada','910','500','Declaraci&oacute;n Jurada');
                     },
                     getClass: function(value,metadata,record){
                     if (mod=='14')
                     return gridColumnAction(false);

                     }
                     },*/
					 
					 
					 //aqui cambio comento
                    {
                        icon: urljs + 'img/diner.png',
                        tooltip: 'Generar Multa',
                        handler: function(grid, rowIndex, colIndex) {
                             var rec = grid.getStore().getAt(rowIndex);
                             showPopup('multastributarias/editar?codigo='+rec.get('codigo'),'#popupMultas','800','600','Multas Tributarias');
                        },
                        getClass: function(value,metadata,record){
                           if (mod=='14')
                                return gridColumnAction(false);

                        }
                    }
					
					//aquitermina
					
					/*
                     ,{
                     icon: urljs + 'img/reload.png',
                     tooltip: 'Caja',
                     handler: function(grid, rowIndex, colIndex) {
                     var rec = grid.getStore().getAt(rowIndex);
                     //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                     showPopup('tesocaja/index?codigo='+rec.get('codigo'),'#poptesore','1000','520','Caja');
                     },
                     getClass: function(value,metadata,record){
                     if (mod=='2')
                     return gridColumnAction(false);

                     }
                     },{
                     icon: urljs + 'img/diner.png',
                     tooltip: 'Pagos Diversos',
                     handler: function(grid, rowIndex, colIndex) {
                     var rec = grid.getStore().getAt(rowIndex);
                     //Ext.MessageBox.alert('Ver predios',rec.get('codigo'));
                     showPopup('tesopagosdiv/index?codigo='+rec.get('codigo')+'&nombre='+rec.get('nombres'),'#popPagosDiv','800','400','Pagos Diversos');
                     },
                     getClass: function(value,metadata,record){
                     if (mod=='2')
                     return gridColumnAction(false);
                     }
                     }*/]
            }],
        bbar: Ext.create('Ext.PagingToolbar', {
            id: 'toolbarGridContri',
            pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        })
    });

    grid.render('gridMultas');
    changeDisContri($('#cmbDisContri').val());
    $('#txtCriterio').keypress(function(e) {
        if(e.which == 13) {
            buscarContri();
        }
    });
});



function buscarContri(){
	var grid = Ext.getCmp('xgridMultas');
	var store = grid.getStore();
	var proxy = store.getProxy();
	
	var params = proxy.extraParams;
	delete params;
	
	var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
	var txtCriterio = $('#txtCriterio').val();
	var txtCriterioNombre = $('#txtCriterioNombre').val();
	var txtCriterioAPaterno = $('#txtCriterioAPaterno').val();
	var txtCriterioAMaterno = $('#txtCriterioAMaterno').val();
	var txtCriterioRazon = $('#txtCriterioRazon').val();
	var txtDocumento = $('#txtDocumento').val();
	
	proxy.extraParams = {rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
	
	store.currentPage = 1;	
	store.load({
		callback: function(documents, options, success){}
	});

}


function actualizarContri(){
    var grid = Ext.getCmp('xgridMultas');
    var store = grid.getStore();
    store.reload();
}

$('#txtCriterio').keypress(function (e) {

    if($('#rdCriterio1').attr('checked') ){
        return validaTeclas(event,'number');}
});

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

$('#txtCriterio').keypress(function (e) {
    if(e.keyCode==13){
        var numCeros = '0000000'; // pon el n� de ceros que necesites
        var valor =  $('#txtCriterio').val().trim();
        var radio = $('#rdCriterio1').val();
        // alert(radio);
        //if(valor.length==0 &&  radio!="C")
        if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio
        {
            //$('#txtCriterio').val(valor);
            var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
            $('#txtCriterio').val(valor2);  //obj.value = valor;
        }
        else{
            $('#txtCriterio').val(valor);
        }
        buscarContri();
    }
});
$('#txtCriterioNombre').keypress(function (e) {
    if(e.keyCode==13){
        buscarContri();
    }
});
$('#txtCriterioAPaterno').keypress(function (e) {
    if(e.keyCode==13){
        buscarContri();
    }
});
$('#txtCriterioAMaterno').keypress(function (e) {
    if(e.keyCode==13){
        buscarContri();
    }
});
$('#txtCriterioRazon').keypress(function (e) {
    if(e.keyCode==13){
        buscarContri();
    }
});
$('#txtDocumento').keypress(function (e) {
    if(e.keyCode==13){
        buscarContri();
    }
});



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
    var radio = $('#rdCriterio1').val();
    // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterio1').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio 
    {
        //$('#txtCriterio').val(valor);
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtCriterio').val(valor2);  //obj.value = valor;
    }
    else{
        $('#txtCriterio').val(valor);
    }

});


function changeDisContri(idDis){
    if(idDis=='003'){
        $('#rowUrbOtherDis').hide();
        $('#rowViaOtherDis').hide();

        $('#rowZonaDis').show();
        $('#rowUrbDis').show();
        $('#rowViaDis').show();
    }
    else{
        $('#rowZonaDis').hide();
        $('#rowUrbDis').hide();
        $('#rowViaDis').hide();

        $('#rowUrbOtherDis').show();
        $('#rowViaOtherDis').show();
    }
}

/*  ??=?????
function pagotupaNuevo(){
    showPopup('tesopagosdiv/index?codigo=9999999&nombre=','#popPagosDiv','800','400','Pagos Diversos');
}
*/
