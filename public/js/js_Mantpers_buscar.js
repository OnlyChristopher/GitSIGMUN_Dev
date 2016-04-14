Ext.onReady(function(){
    Ext.QuickTips.init();
    
    $("input[type='button']").button();
    
    Ext.define('BusPers', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'codigo'},
            {name: 'nombres', type: 'string'},
            {name: 'tipo_doc', type: 'string'},            
            {name: 'documento', type: 'string'},
            {name: 'direccion', type: 'string'},
            {name: 'distrito', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'BusPers',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantpers/consulta',
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridBusPers',
        store: store,        
        title: 'Personas',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
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
            text: 'Tipo Doc.',
            width: 100,
            dataIndex: 'tipo_doc'            
        },{
            text: 'Nro. Documento',
            width: 100,
            dataIndex: 'documento'            
        },{
            text: 'Direcci&oacute;n',
            width: 100,
            dataIndex: 'direccion'
        },{
            text: 'Distrito',
            width: 100,
            dataIndex: 'distrito',
            hidden:true
         },{
            xtype:'actioncolumn',
            width:20,
            items: [{
                icon: urljs + 'img/edit.png',
                style: 'cursor: pointer',
                tooltip: 'Editar Contribuyente',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('mantcontri/formu?codigo='+rec.get('codigo'),'#popcontri','600','450','Editar Contrinuyente');
                }
            }]
        }],
        bbar: Ext.create('Ext.PagingToolbar', {
        	id: 'toolbarGridBuscaPers',
        	pageSize: 10,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; contribuyentes"
        }),
        listeners : {
            itemdblclick: function(dv, record, item, index, e) {
                //alert(record);
                closePopup('#popBusPersSol');
                window.parent.muestraDatosSol(record);//Captura Valores
				//window.parent.muestraDatosEmp(record);//Captura Valores
            }
        }
    });
    
    grid.render('gridBusPers');
    
    $('#txtCriterioC').keypress(function(e) {
        if(e.which == 13) {
        	buscarPers();
        }
    });
    
    $('#txtCriterioC').focus();
});

function buscarPers(){
    var grid = Ext.getCmp('xgridBusPers');
    var store = grid.getStore();
    var proxy = store.getProxy();

    var params = proxy.extraParams;
    delete params;

    var rdCriterio = $('input[name*="rdCriterio"]:checked').val();
    var txtCriterio = $('#txtCriterioC').val();
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
    var grid = Ext.getCmp('xgridContri');
    var store = grid.getStore();
    store.reload();
}

$('#txtCriterioC').keypress(function (e) {

    if($('#rdCriterioC').attr('checked') ){
        return validaTeclas(event,'number');}
});

$('#txtCriterioNombre').keypress(function (e) {

    if($('#rdCriterioN').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioAPaterno').keypress(function (e) {

    if($('#rdCriterioN').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioAMaterno').keypress(function (e) {

    if($('#rdCriterioN').attr('checked') ){
        return validaTeclas(event,'text');}
});
$('#txtCriterioRazon').keypress(function (e) {

    if($('#rdCriterioR').attr('checked') ){
        return validaTeclas(event,'text');}
});

$('#txtCriterioC').keypress(function (e) {
    if(e.keyCode==13){
        var numCeros = '0000000'; // pon el n� de ceros que necesites
        var valor =  $('#txtCriterioC').val().trim();
        var radio = $('#rdCriterioC').val();
        // alert(radio);
        //if(valor.length==0 &&  radio!="C")
        if($('#rdCriterioC').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio
        {
            //$('#txtCriterioC').val(valor);
            var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
            $('#txtCriterioC').val(valor2);  //obj.value = valor;
        }
        else{
            $('#txtCriterioC').val(valor);
        }
        buscarPers();
    }
});
$('#txtCriterioNombre').keypress(function (e) {
    if(e.keyCode==13){
        buscarPers();
    }
});
$('#txtCriterioAPaterno').keypress(function (e) {
    if(e.keyCode==13){
        buscarPers();
    }
});
$('#txtCriterioAMaterno').keypress(function (e) {
    if(e.keyCode==13){
        buscarPers();
    }
});
$('#txtCriterioRazon').keypress(function (e) {
    if(e.keyCode==13){
        buscarPers();
    }
});
$('#txtDocumento').keypress(function (e) {
    if(e.keyCode==13){
        buscarPers();
    }
});



$('#rdCriterioC').click(function(){

    if($('#rdCriterioC').attr('checked'))
    {
        $('#txtCriterioC').val('');
        $('#txtCriterioC').focus();
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

$('#rdCriterioN').click(function(){
    if($('#rdCriterioN').attr('checked'))
    {
        $('#txtCriterioC').val('');
        $('#txtCriterioC').focus();
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

$('#rdCriterioD').click(function(){

    if($('#rdCriterioD').attr('checked'))
    {
        $('#txtCriterioC').val('');
        $('#txtCriterioC').focus();
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
$('#rdCriterioR').click(function(){
    if($('#rdCriterioR').attr('checked'))
    {
        $('#txtCriterioC').val('');
        $('#txtCriterioC').focus();
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

$('#txtCriterioC').blur(function(){
    var numCeros = '0000000'; // pon el n� de ceros que necesites
    var valor =  $('#txtCriterioC').val().trim();
    var radio = $('#rdCriterioC').val();
    // alert(radio);
    //if(valor.length==0 &&  radio!="C")
    if($('#rdCriterioC').attr('checked') && valor.length>0)// total de carec mayor a cero y ete no esta vacio
    {
        //$('#txtCriterioC').val(valor);
        var valor2 = numCeros.substring(0,numCeros.length-valor.length)+valor;
        $('#txtCriterioC').val(valor2);  //obj.value = valor;
    }
    else{
        $('#txtCriterioC').val(valor);
    }

});
