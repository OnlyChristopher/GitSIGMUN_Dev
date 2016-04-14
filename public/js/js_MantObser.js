Ext.onReady(function(){
    Ext.QuickTips.init();

    $("input[type='button']").button();
    $('#frmdetalleobser').validate({

        rules: {
            'txtnrocer': 	{ required: true},
            'txtFchDes': 	{ required: true},
            'txtFchHas': 	{ required: true}
        },
        messages: {
            'txtnrocer': 	{ required: 'Ingrese Certificado' },
            'txtFchDes': 	{ required: 'Seleccione Fecha' },
            'txtFchHas': 	{ required: 'Seleccione Fecha' }
        },
        debug: true,
        errorElement: 'div',
        submitHandler: function(form){

            //Datos del formulario
            data = $(form).serializeObject();

            //Datos grilla detalle
            var dataDetalle = new Array();
            var gridObser = Ext.getCmp('xgridObser');
            if(gridObser.getStore().data.length>0){
                var i=0;
                gridObser.getStore().each(function(rec) {
                    var rowsDetalle = {
                        nroCerti		: rec.get('nroCerti'),
                        anno	: rec.get('anno'),
                        idObs	: rec.get('idObs'),
                        estado		: rec.get('estado')

                    }
                    dataDetalle[i] = rowsDetalle;
                    i++;
                });
                data['Detalle'] = dataDetalle;
            }

            //Envía data para guardar
            Ext.Ajax.request({
                url: urljs + "MantObser/grabar",
                method: "POST",
                params: {json: JSON.stringify(data)},
                success: function(response){
                 /*   if(response.responseText=='existe'){
                        infoMessage('Guardar','El n&uacute;mero de requerimiento ya existe!','#req_num');
                    }
                    else{*/
                        var datos = response.responseText.split('|');

                        var params = "?req_num="+datos[0]+"&req_anno="+datos[1]+"&depen_cod="+datos[2];
                        reloadPopup('requerimiento/editar','#poprequerim',params,'Editar Requerimiento');

                        infoMessage('Guardar','Datos actualizados correctamente');
                        Ext.getCmp('xgridRequerim').getStore().load();
                    //}
                }
            });
        }
    });


    Ext.define('Obser', {
        extend: 'Ext.data.Model',
        fields: [
            {name: 'idObs', type: 'string'},
            {name: 'descObs', type: 'string'}
        ]
    });

    var store = Ext.create('Ext.data.Store', {
        model: 'Obser',
        autoLoad: true,
        pageSize: 19,
        proxy: {
            type: 'ajax',
            url : 'mantobser/consulta',
            reader: {
                type: 'json',
                root: 'rows'
            }
        }
    });
    var grid = Ext.create('Ext.grid.Panel', {
        id: 'xgridObser',
        store: store,
        title: 'Condiciones de Seguridad',
        selModel: Ext.create('Ext.selection.CheckboxModel'),
        viewConfig: {
            loadMask: {msg: 'cargando...'},
            stripeRows: false,
            getRowClass: function(record) {
                var clsRow = '';
                switch(record.get('idObs')){
                    case '1.0': clsRow='xrow-obs'; break
                    case '2.0': clsRow='xrow-obs'; break
                    case '3.0': clsRow='xrow-obs'; break
                    case '4.0': clsRow='xrow-obs'; break
                }
                return clsRow;

            },
			listeners: {        		
                beforerefresh: function() {
        			//alert('antes refresh');
        			var ind=0;
        			var str=cargaChecks($('#txtnrocer').val());
                    this.store.each(function(record) {
                    	if(str.indexOf(record.get('idObs')) != -1)
                    		Ext.getCmp('xgridObser').getSelectionModel().select(ind,true,false);
                    	ind++;
                    });
                }
            }
		},
        columns: [
            Ext.create('Ext.grid.RowNumberer'),
            {
                text: 'C&oacute;digo',
                width: 60,
                dataIndex: 'idObs',
                hidden: true
            },{
                text: 'Descripcion',
                width: 1100,
                dataIndex: 'descObs'
            }]

    });

    grid.render('gridObser');


});

function cargaChecks(nroCerti){
    var str="";
	$.ajax({
		type: "POST",     
		url: urljs + "MantObser/cargachecks",
		data: { 'nroCerti':nroCerti },
		success: function(data) { 
    		str = data;
		},
		async: false
	});
	//alert(str);
	return str;
}