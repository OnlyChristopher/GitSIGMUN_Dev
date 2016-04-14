Ext.onReady(function(){
    Ext.QuickTips.init();
    
	$("input[type='button']").button();
	
    Ext.define('Historico', {
        extend: 'Ext.data.Model',
        fields: [
			{name: 'codigo', type: 'string'},                     
			{name: 'cod_pred', type: 'string'},            
			{name: 'anexo', type: 'string'},            		
			{name: 'sub_anexo', type: 'string'},            	
			{name: 'dj_predial', type: 'string'},            		
			{name: 'anno', type: 'string'},            
            {name: 'motivo_declaracion', type: 'string'},
			{name: 'condicion_propiedad', type: 'string'},
            {name: 'tipo_adquisicion', type: 'string'},       
			{name: 'fecha', type: 'string'},
			{name: 'porc_propiedad', type: 'string'},
			{name: 'area_terreno', type: 'string'},
			{name: 'registrado', type: 'string'},
			{name: 'fiscalizado', type: 'string'}
        ]
    });
 
	var anexo=$('#divSAnexo').html();
    var store = Ext.create('Ext.data.Store', {
    	model: 'Historico',
    	autoLoad: true,    	
        proxy: {
            type: 'ajax',
            url : 'rentas/historialpred?codigo='+$('#divECodigo').html()+'&cod_pred='+$('#divCodPred').html()+'&anexo='+anexo.substr(0,4)+'&sub_anexo='+anexo.substr(5),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
   // xgridContri
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridHistorico',
        store: store, 
		//height:100,
        title: 'Historial de las Declaraciones Juradas ',
        viewConfig: {
        	loadMask: {msg: 'cargando...'}
    	},
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'codigo',
            width: 100,            
            dataIndex: 'codigo',
            hidden:	true
        },{
            text: 'cod_pred',
            width: 100,            
            dataIndex: 'cod_pred',
            hidden:	true
        },{
            text: 'anexo',
            width: 100,            
            dataIndex: 'anexo',
            hidden:	true
        },
		{
            text: 'sub_anexo',
            width: 100,            
            dataIndex: 'sub_anexo',
            hidden:	true
        },{
            text: 'dj_predial',
            width: 100,            
            dataIndex: 'dj_predial',
            hidden:	true
        },{
            text: 'A&ntilde;o',
			width: 42,       
            dataIndex: 'anno'
        },{
            text: 'Motivo Declaracion',
            width: 162,            
            dataIndex: 'motivo_declaracion'
        },{        
        	text: 'Condicion Propiedad',
        	width: 164,
            dataIndex: 'condicion_propiedad'
        },{        
        	text: 'Tipo Adqui.',
        	width: 115,
            dataIndex: 'tipo_adquisicion'
        },{
            text: 'F. Adquisicion',
            width: 100,
            dataIndex: 'fecha'            
        },{
            text: 'Porc. prop.',
            width: 96,
            dataIndex: 'porc_propiedad'
        },{
            text: 'Area Terreno',
            width: 100,
            dataIndex: 'area_terreno'
        },{
            text: 'Registrado',
            width: 100,
            dataIndex: 'registrado'
        },{
            text: 'Fiscalizado',
            width: 100,
            dataIndex: 'fiscalizado'
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
						var dj_predial=rec.get('dj_predial');
						showPopupReport('schema=&tipo=pdf&nombrereporte=rpto_declaraciondoc01&param=CODIGO^'+codigo+'|ANNO^'+anno+'|COD_PRED^'+cod_pred+'|ANEXO^'+anexo+'|SUB_ANEXO^'+sub_anexo+'|DJ_NRO^'+dj_predial,'reportehistorico',700,600,'Historial Predio');
					}
                }
			]
        }]
       	
    });
    
    grid.render('gridHistorico');
	
    //changeDisContri($('#cmbDisContri').val());
	
});

function generarBajapredio(codigo,anno,cod_pred,anexo,sub_anexo){
	showPopupReport('schema=&tipo=pdf&nombrereporte=rptdescargo&param=codigo^'+codigo+'|anno^'+anno+'|cod_pred^'+cod_pred+'|anexo^'+anexo+'|sub_anexo^'+sub_anexo,'reportebajapredio',700,600,'Baja de Predio');
}

