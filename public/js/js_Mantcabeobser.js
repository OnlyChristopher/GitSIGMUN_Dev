Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();

    Ext.define('cabeobser', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'nroCerti', type: 'string'},
            {name: 'anno', type: 'string'},
            {name: 'fecdesde', type: 'string'},
            {name: 'fechasta', type: 'string'}
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'cabeobser',
        autoLoad: true,
        pageSize: 19,
        proxy: {
            type: 'ajax',
            url : 'mantcabeobser/consulta',
            reader: {
                type: 'json',
                root: 'rows'
            }
        }
    });
    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridcabeobser',
        store: store,
        title: 'Certificados',
       // selModel: Ext.create('Ext.selection.CheckboxModel'),
        viewConfig: {
            loadMask: {msg: 'cargando...'}
           /* stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
                switch(record.get('idObs')){
                    case '1.0': clsRow='xrow-obs'; break
                    case '2.0': clsRow='xrow-obs'; break
                    case '3.0': clsRow='xrow-obs'; break
                    case '4.0': clsRow='xrow-obs'; break
                }
                return clsRow;
            */
            },
        columns: [
            Ext.create('Ext.grid.RowNumberer'),
            {
                text: 'Numero de Certificado',
                flex: 1,
                dataIndex: 'nroCerti'
            },{
                text: 'Año',
                flex: 1,
                dataIndex: 'anno'
            },{
                text: 'Desde',
                flex: 1,
                dataIndex: 'fecdesde'
            },{
                text: 'Hasta',
                flex: 1,
                dataIndex: 'fechasta'
            },{
                xtype:'actioncolumn',
                width:70,
                items: [{
                    icon: urljs + 'img/edit.png',
                    style: 'cursor: pointer',
                    tooltip: 'Editar Licencia',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        showPopup('mantobser/index?nroCerti='+rec.get('nroCerti')+'&accion=2','#popupcertificado','1200','600','Editar Observancia de Defensa Civil')
                       // showPopup('licendecjurada/index?idSolLice='+rec.get('idSolLice')+'&cod_sol='+rec.get('cod_sol')+'&readonly=readonly'+'&accion=2','#poplicendecjurada','900','590','Editar Solicitud');
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
            id: 'toolbarGridcabeobser',
            pageSize: 19,
            store: store,
            displayInfo: true,
            displayMsg: 'Mostrando {0} - {1} de {2}',
            emptyMsg: "No se encontr&oacute; Certificados"
        })

    });

    grid.render('gridcabeobser');


});
function nuevoCertificado(){

    showPopup('mantobser/index'+'?accion=1','#popupcertificado','1200','600','Observancia de Defensa Civil');
}

