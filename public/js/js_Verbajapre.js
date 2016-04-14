Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Verbajapre', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'codigo'},            
            {name: 'anno', type: 'string'},
			{name: 'cod_pred', type: 'string'},
            {name: 'anexo', type: 'string'},
            {name: 'sub_anexo', type: 'string'},
			{name: 'direccion', type: 'string'},
			{name: 'fechdescargo', type: 'string'}
        ]
    });
    ////'rentasdecjurada/predios?anno='+anno+'&codigo='+$('#divCodigo').html(),
    var store = Ext.create('Ext.data.Store', {
    	model: 'Verbajapre',
    	autoLoad: true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'mantbajapre/consulta?codigobaja='+$('#txtCodigo').val(),
            reader: {
               type: 'json',
               root: 'rowsbaja'
            }
        }
    });
   // xgridContri
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridVerbajapre',
        store: store, 
		//height:100,
        title: 'Contribuyente Baja de Predio ',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Codigo',
            width: 80,            
            dataIndex: 'codigo'
        },{
            text: 'Año',
            width: 80,            
            dataIndex: 'anno'
        },{        
        	text: 'Codigo Pred',
        	width: 60,
            dataIndex: 'cod_pred'
        },{        
        	text: 'Anexo',
        	width: 60,
            dataIndex: 'anexo'
        },{
            text: 'SubAnexo',
            width: 80,
            dataIndex: 'sub_anexo'            
        },{
            text: 'Direcci&oacute;n',
            flex: 1,
            dataIndex: 'direccion'
        },{
            text: 'Fecha Descargo',
            width: 100,
            dataIndex: 'fechdescargo'
        },{
            xtype:'actioncolumn',
            width:50,
            items: [
				{
					icon: urljs + 'img/export.gif',
					tooltip: 'Reporte Baja Predio',
					handler: function(grid, rowIndex, colIndex) {
						var rec = grid.getStore().getAt(rowIndex);
						
						var codigo=rec.get('codigo');
						var anno=rec.get('anno');
						var cod_pred=rec.get('cod_pred');						
						var anexo=rec.get('anexo');
						var sub_anexo=rec.get('sub_anexo');

					generarBajapredio(codigo,anno,cod_pred,anexo,sub_anexo);
					}
                }
			]
        }]
       	
    });
    
    grid.render('gridVerbajapred');
	
    //changeDisContri($('#cmbDisContri').val());
	
});

function generarBajapredio(codigo,anno,cod_pred,anexo,sub_anexo){
	showPopupReport('schema=&tipo=pdf&nombrereporte=rptdescargo&param=codigo^'+codigo+'|anno^'+anno+'|cod_pred^'+cod_pred+'|anexo^'+anexo+'|sub_anexo^'+sub_anexo,'reportebajapredio',700,600,'Baja de Predio');
}
/*
showPopupReport('tipo=pdf&nombrereporte=rpt_orpa&param=id_valor^'+data.idvalor+'|num_val^'+data.numval+'|ano_val^'+data.anoval,'pouprptemitido',700,600,'Impresion de Orden de Pago');


function generarHrCompleto(codigo,anno,cod_pred,anexo,sub_anexo){
	showPopupReport('schema=&tipo=pdf&nombrereporte=rpto_declaraciondoc&param=CODIGO^'+codigo+'|ANNO^'+anno+'|COD_PRED^'+cod_pred+'|ANEXO^'+anexo+'|SUB_ANEXO^'+sub_anexo,'reportehrc',700,600,'Hoja de Resumen');
}
*/