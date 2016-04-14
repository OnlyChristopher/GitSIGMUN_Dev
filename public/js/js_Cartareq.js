Ext.onReady(function(){
    Ext.QuickTips.init();
    $("input[type='button']").button(); 
    busContribuyente();

});

function busContribuyente(){
        Ext.define('Contribuyente', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'id'},
                {name: 'nombre', type: 'string'},
                {name: 'direccion', type: 'string'}
            ]
        });
        
        var store = Ext.create('Ext.data.Store', {
            model: 'Contribuyente',
            autoLoad: true,
            pageSize: 10,       
            proxy: {
                type: 'ajax',
                url : 'cartareq/consultacontrib',
                reader: {
                   type: 'json',
                   root: 'rows'
                }
            }
        });

        var grid = Ext.create('Ext.grid.Panel', {
            id: 'xgridContribuyente',
            store: store,        
            title: 'Tipo de Contribuyente',
            //selModel: Ext.create('Ext.selection.CheckboxModel'),
            listeners: {
                itemdblclick: function (dv,record,item,index,e){
                    showPopup('cartareq/formulistarcartasreq?codigo='+record.get('id'),'#popuplistarcartasreq','630','404','Lista de Cartas de Presentacion y Req. de documentaci&oacute;n');
                }
            },
            columns: [ 
            Ext.create('Ext.grid.RowNumberer',{width:45}),    
            {
                text: 'C&oacute;digo',
                width: 60,            
                dataIndex: 'id'
            },{
                text: 'Nombres',
                flex: 1,
                dataIndex: 'nombre'
            },{
                text: 'Direccion',
                flex: 1,
                dataIndex: 'direccion'
            },{
                xtype:'actioncolumn',
                width:120,
                text:'Generar carta req.',
                align : 'center',
                items: [{
                    icon: urljs + 'img/edit.png',
                    tooltip: 'Generar Carta de Req.',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                       showPopup('cartareq/formulistarcartasreq?codigo='+rec.get('id'),'#popuplistarcartasreq','630','404','Lista de Cartas de Presentacion y Req. de documentaci&oacute;n');

                    }
                }]
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
        
        grid.render('gridContri');
}


function buscarContribuyente(){
    if($("#BCodigo").is(':checked') && $('#txtBValor').val().trim()!=''){
             if($.isNumeric($('#txtBValor').val())){
                buscar();  
             }else{
                 infoMessage('Error','C&oacute;digo inv&aacute;lido, solo n&uacute;meros.');
                 }    
    }else{
        buscar();
    }
    $('#txtBValor').focus();
}

function buscar(){
    var grid = Ext.getCmp('xgridContribuyente');
    var store = grid.getStore();
    var proxy = store.getProxy();
    
    var params = proxy.extraParams;
    delete params;
    
    var buscarPor = $('input[name*="rdBuscarPor"]:checked').val();
    var valor = $('#txtBValor').val();

    proxy.extraParams = {buscarPor: buscarPor, valor: valor};
    
    store.currentPage = 1;  
    store.load({
        callback: function(documents, options, success){}
    });    
}

$('#txtBValor').keypress(function(event){  
    
    if ($("#BCodigo").is(':checked')) {
        var valor = '0000000'+$("#txtBValor").val();
        $("#txtBValor").val(valor.substr(-7));
    };

    var keycode = (event.keyCode ? event.keyCode : event.which);  
          if(keycode == '13'){           
            buscarContribuyente();        
          }   
});  
