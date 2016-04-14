$(function(){
	$("input[type='button']").button();
	CargaListado();
});


function reporte(varconvenio){
	var flag=$('#txtflag').val();
	var varcodigo=$('#divCodigo').html();
	if(flag==1){
		showPopupReport('tipo=pdf&nombrereporte=ReporteConvenio&param=PCODIGO^'+varcodigo+'|PCONVENIO^'+varconvenio,'pouprptconvenio',700,600,'Convenio');
	}
	if(flag==2){
		showPopupReport('tipo=pdf&nombrereporte=ReporteConvenio_info&param=PCODIGO^'+varcodigo+'|PCONVENIO^'+varconvenio,'pouprptconvenio',700,600,'Convenio');
	}
	
	
}

function CargaListado(){

Ext.define('Listado', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'convenio',type: 'string'},
            {name: 'anno', type: 'string'},
            {name: 'cuotas', type: 'string'},
            {name: 'monto', type: 'string'},
			{name: 'estado', type: 'string'},
			{name: 'usuario', type: 'string'},
			{name: 'fecha', type: 'string'}
        ]
    });
    
    var store = Ext.create('Ext.data.Store', {
    	model: 'Listado',
    	autoLoad: (mod=='14')? false : true,
	    pageSize: 10,    	
        proxy: {
            type: 'ajax',
            url : 'Fraccionar/consultafraccinfo?codigo='+$('#divCodFracc').html(),
            reader: {
               type: 'json',
               root: 'rows'
            }
        }
    });
    var grid = Ext.create('Ext.grid.Panel', {
    	id: 'xgridListado',
        store: store,        
        title: 'Fraccionamientos',
		//Fin valida iconos
        //selModel: Ext.create('Ext.selection.CheckboxModel'),
        columns: [ 
        Ext.create('Ext.grid.RowNumberer'),    
        {
            text: 'Convenio',
            width: 60,            
            dataIndex: 'convenio'
        },{
        	text: 'A&ntilde;o',
        	flex: 1,
            dataIndex: 'anno'
        },{
            text: 'Cuotas',
            flex: 1,
            dataIndex: 'cuotas'            
        },{
            text: 'Monto',
            flex: 1,
            dataIndex: 'monto'
        },{
            text: 'Estado',
            flex: 1,
            dataIndex: 'estado'
        },{
            text: 'Usuario',
            flex: 1,
            dataIndex: 'usuario'
        },{
            text: 'Fecha',
            flex: 1,
            dataIndex: 'fecha'
        },{
            xtype:'actioncolumn',
            width:82,
            items: [{
				//Agrega esto para validar accesos
				altText: 'iconEditContri',
				//Fin valida acceso
                icon: urljs + 'img/printer_rec.png',
                tooltip: 'Reporte Convenio',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    reporte(rec.get('convenio'));
                }
                
            }/*,{
				//Agrega esto para validar accesos
				altText: 'iconEditContri',
				//Fin valida acceso
                icon: urljs + 'img/edit.png',
                tooltip: 'Detalle Convenio',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //Ext.MessageBox.alert('Editar',rec.get('codigo'));
                    showPopup('fraccionar/resolfracc?codigo='+$('#divCodFracc').html()+'&convenio='+rec.get('convenio'),'#popresol','820','370','Detalle de Fraccionamiento');
                },
                getClass: function(value,metadata,record){
                	if (mod=='02.01.00')
                		return gridColumnAction(false);                	   
                }
            }*/]
        }],

    });
    
    grid.render('gridListado');

}



