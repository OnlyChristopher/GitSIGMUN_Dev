
//Para los forms
$.fn.serializeObject = function(){  
	var o = {};  
	var a = this.serializeArray();  
	$.each(a, function() {  
		if (o[this.name]) {  
			if (!o[this.name].push) {  
				o[this.name] = [o[this.name]];  
			}  
			o[this.name].push(this.value || '');  
		} else {  
			o[this.name] = this.value || '';  
		}  
	});  
	return o;
}; 

$.fn.reset = function () {
  $(this).each (function() { this.reset(); });
};

function showPopupESC(d,e,w,h,t){
	var namePopup = 'popup_'+e.substr(1);
	
	var alto = parseInt(h) + 60;
	
	var center_area = new Ext.Panel({	
	    id: 'framepopup_'+e.substr(1),
	    region: 'center',
	    layout: 'fit',
        height: alto,
	    border: false,
	    autoScroll:true,
	    
	    bodyStyle: 'padding: 10px',
	    autoLoad:
        {  
    	   url : urljs + d,
    	   scripts: true
        }
	});
			
	var ancho = parseInt(w) + 20;
	var alto = parseInt(h) + 100;
	
	win = new Ext.Window(
	    {
	    	id: namePopup,
	    	title: t,
	        layout: 'fit',
	        width: ancho,
	        height: alto,
	        modal: true,
	        border: false,
	        resizable: false,
			onEsc: function() {
				var me = this;
				Ext.Msg.confirm(
					'Mensaje',
					'Deseas Salir',
					function(btn) {
						if (btn == 'yes')
							me.hide();
					}
					);
		    }, 
	        closeAction: 'destroy',	        
	        items: new Ext.Panel(
	        {
	        	items: [ center_area ]
	        })
	    });
    
    win.show();
	/*win.setLoading(true, true);
	setTimeout(function (target) {
                    target.setLoading(false);
                }, 2000, win)*/
	
	verificaAccesos(userjs,$('#hdnAcceso').val());
}

function saveMessage(){
	Ext.MessageBox.show({
           msg: 'Guardando la informaci&oacute;n, por favor espere un momento...',
           progressText: 'Guardando...',
           width:300,
           wait:true,
           waitConfig: {interval:200},
           //icon:'ext-mb-download', //custom class in msg-box.html
           icon: 'messageGuardar'
    });
}
    
function showPopup(d,e,w,h,t){
	var namePopup = 'popup_'+e.substr(1);
	
	var alto = parseInt(h) + 60;
	
	var center_area = new Ext.Panel({		
	    region: 'center',
	    layout: 'fit',
        height: alto,
	    border: false,
	    autoScroll:true,
	    bodyStyle: 'padding: 10px',
	    autoLoad:
        {  
    	   url : urljs + d,
    	   scripts: true
        }
	});
			
	var ancho = parseInt(w) + 20;
	var alto = parseInt(h) + 100;
	
	win = new Ext.Window(
	    {
	    	id: namePopup,
	    	title: t,
	        layout: 'fit',
	        width: ancho,
	        height: alto,
	        modal: true,
	        border: false,
	        resizable: false,
	        closeAction: 'destroy',	        
	        items: new Ext.Panel(
	        {
	        	items: [ center_area ]
	        })
	    });
    
    win.show();
	
	verificaAccesos(userjs,$('#hdnAcceso').val());
}

function showPopupData(data,d,e,w,h,t){
	var namePopup = 'popup_'+e.substr(1);
	
	var alto = parseInt(h) + 60;
	
	var center_area = new Ext.Panel({		
	    region: 'center',
	    layout: 'fit',
        height: alto,
	    border: false,
	    autoScroll:true,
	    bodyStyle: 'padding: 10px',
    	scripts: true
	});
	
	Ext.Ajax.request({
		  url: urljs + d,
		  method: "POST",
		  params: {json: JSON.stringify(data)},
		  success: function(response,opts){			
			  opts.panel.update(response.responseText,true);
		  },
		  panel: center_area
	});
	
	var ancho = parseInt(w) + 20;
	var alto = parseInt(h) + 100;
	
	win = new Ext.Window(
	    {
	    	id: namePopup,
	    	title: t,
	        layout: 'fit',
	        width: ancho,
	        height: alto,
	        modal: true,
	        border: false,
	        resizable: false,
	        closeAction: 'destroy',	        
	        items: new Ext.Panel(
	        {
	        	items: [ center_area ]
	        })
	    });
    
    win.show();
	
	verificaAccesos(userjs,$('#hdnAcceso').val());
}
//d=parametros,e=nombrepoup,w=ancho,h=alto,t=titulo
function showPopupReport(d,e,w,h,t,s){
	var namePopup = 'popup_'+e.substr(1);

	var ancho = parseInt(w) + 20;
	var alto = parseInt(h) + 100;
	var schema = '';
	
	if(s)
		schema = s;
	
	win = new Ext.Window({
    	id: namePopup,
    	title: t,
        layout: 'fit',
        //width: ancho,
        //height: alto,
        modal: true,
        border: false, 
        maximizable: false,
        resizable: false,
        closeAction: 'destroy',    
        plain:true,
        html: '<iframe id="popup-report" src="'+urlrpt+'schema='+schema+'&'+d+'" width="100%" height="100%" />'
	});
    
    win.show();
    win.maximize()
}

function showPopupReportHtml(d,e,t){
	var namePopup = 'popup_'+e.substr(1);
	
	var tb = new Ext.Toolbar({
    	items : 
		[{
			text : 'Imprimir',
			iconCls: 'print',
			listeners: {
            	click: function(){
					$("#popup-report").get(0).contentWindow.print();
				}
            }
		},{
			text : 'Cerrar',
			iconCls: 'close',
			listeners: {
            	click: function(){
					closePopup(e);
				}
            }
		}]
	});
	
	win = new Ext.Window({
    	id: namePopup,
    	title: t,
        layout: 'fit',
		tbar : tb,
        modal: true,
        border: false, 
        maximizable: false,
        resizable: false,
        closeAction: 'destroy',	        
        plain:true,
        html: '<iframe id="popup-report" src="'+urljs+d+'" width="100%" height="100%" />'
	});
    
    win.show();
    win.maximize()
}

function closePopup(e){
	Ext.getCmp('popup_'+e.substr(1)).close();
}

function confirmMessage(t,m,f){
	Ext.MessageBox.show({
        title:t,
        msg: m,
        buttons: Ext.MessageBox.YESNO,
        buttonText: {yes: 'Si'},
        fn: f,
        icon: Ext.MessageBox.QUESTION
    });
}

function infoMessage(t,m,e){
	Ext.MessageBox.show({
        title:t,
        msg: m,
        buttons: Ext.MessageBox.YES,
        buttonText: {yes: 'Aceptar'},
        icon: Ext.MessageBox.INFO
		
    });
}

function infoMessagex(t,m,o){
	function showResultMessage(btn){
		setTimeout(function() {
			$(o).focus();
		}, 300);		
	}
		
	Ext.MessageBox.show({
        title:t,
        msg: m,
        buttons: Ext.MessageBox.YES,
        buttonText: {yes: 'Aceptar'},
        icon: Ext.MessageBox.INFO,
		fn: showResultMessage
    });
}

function errorMessage(t,m){
	Ext.MessageBox.show({
        title:t,
        msg: m,
        buttons: Ext.MessageBox.YES,
        buttonText: {yes: 'Aceptar'},
        icon: Ext.MessageBox.INFO
    });
}

function goToFormulario(idForm){
	$('#'+idForm).submit();
}

function goToInterno(ruta,params,title){
	
	Ext.getCmp('content').update('');
	Ext.getCmp('content').setTitle(title);

	var myMask = new Ext.LoadMask(Ext.getCmp('content'), {
		msg : "Cargando..."
	});
	myMask.show();
	
	$('#content-body').load(ruta, params, function() {
		myMask.hide();
		myMask.destroy();
		
		verificaAccesos(userjs,$('#hdnAcceso').val());
	});
}
//callAjax('rptgeneraling/muestrameses2?anio='+anio,'','#divMeses','html');
function callAjax(controller,parameters,element,tipo){
	var pasync = true;
	if(tipo=='html')
		var contentAjax = $(element).html(cargando);
	if(tipo=='param')
	{
		var contentAjax = "";
		pasync = false;
	}
	$.ajax({
		type: "POST",
		url: urljs + controller,
		data: parameters,
		async: pasync,
		success: function(data){
			switch(tipo)
			{
				case 'html': contentAjax.html(data); break;
				case 'val': $(element).val(data); break;
				case 'param': contentAjax = data; break;
			}
		}
	});
	
	if(tipo=='param')
		return contentAjax;
}

function marcaChecks(e,nameChkItems){
	if(!$('#'+e.id).attr('checked'))
		$('input[name*="'+nameChkItems+'"]').attr('checked',false);
	else
		$('input[name*="'+nameChkItems+'"]').attr('checked',true);	
}

function selectChecks(e,nameChkItems){
	var flag=true;
	
	if(!$(e).attr('checked'))
	{
		$('input[name*="'+nameChkItems+'"]').each(function () {
	        if(!this.checked) {
	        	flag=false; 
	        	return false;
	        }
		});
		$(e).attr('checked',flag);
	}
	else{
		$('input[name*="'+nameChkItems+'"]').each(function () {
	        if(this.checked) {
	        	flag=false; 
	        	return false;
	        }
		});		
		$(e).attr('checked',flag);
	}
}

function disableButton(e){
	
	$(e).removeClass('ui-button ui-widget ui-state-default ui-corner-all');
	$(e).addClass('ui-button ui-widget ui-state-default ui-corner-all ui-state-active');
	$(e).attr('disabled',true);
}

function enableButton(e){
	
	$(e).removeClass('ui-state-disabled');
	$(e).removeClass('ui-button ui-widget ui-state-default ui-corner-all ui-state-active');
	$(e).addClass('ui-button ui-widget ui-state-default ui-corner-all');
	$(e).attr('disabled',false);
}

function validaTeclas(e,tip){
	
	var tecla = document.all ? tecla = e.keyCode : tecla = e.which;
	
	var soloLetras="abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ ";
	var soloAlphan="abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ 0123456789";	
	var soloNumero="0123456789";
	var soloDecima="0123456789.";
	var sinCeros="123456789";
	var soloNumerocoma="0123456789,-";
	
	switch(tip)
	{
		case 'text':
			return (soloLetras.indexOf(String.fromCharCode(tecla))>-1);
		break;		
		case 'alpha':
			return (soloAlphan.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'number':			
			return (soloNumero.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'numeric':
			return (soloDecima.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'sinceros':
			return (sinCeros.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'numcoma':
			return (soloNumerocoma.indexOf(String.fromCharCode(tecla))>-1);
		break;
	}
}

function valoresUnitarios(e,tip){
	
	var tecla = document.all ? tecla = e.keyCode : tecla = e.which;
	
	var soloPisos="0123456789";
	var soloUnitario="abcdefghijABCDEFGHIJ";
	var soloMaterial="123";
	var soloEstados="12345";
	var soloClasificacion="1234";
	
	switch(tip)
	{
		case 'pisos':
			return (soloPisos.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'letras':
			return (soloUnitario.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'material':
			return (soloMaterial.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'estados':
			return (soloEstados.indexOf(String.fromCharCode(tecla))>-1);
		break;
		case 'clasificacion':
			return (soloClasificacion.indexOf(String.fromCharCode(tecla))>-1);
		break;
	}
}

function gridColumnAction(val){
	if (val) {
		return 'x-grid-center-icon';	
	} else {
		return 'x-hide-display';
	}
}

function fullZeros(obj,ceros){
	var numCeros='';
	for(i=1;i<=ceros;i++)
		numCeros+='0';
	if(parseInt($(obj).val())>0 && $(obj).val().trim().length<=ceros){
		var valor = numCeros.substring(0,numCeros.length-$(obj).val().trim().length)+$(obj).val();
		$(obj).val(valor);
	}
}

function redimGridHidden(grid,w,h,i){
	Ext.getCmp(grid).setWidth(w);
	Ext.getCmp(grid).setHeight(h);	
	Ext.getCmp(grid).columns[i].setHeight(22);
	//console.log(Ext.getCmp(grid));
}

function rellenarceros(n, length){
   n = n.toString();
   while(n.length < length) n = "0" + n;
   return n;
}

var strHideIcons = "";

function verificaAccesos(user,acceso){
	strHideIcons = "";
	$.ajax({
		type: "POST", 
		url: 'main/verifica',
		data: 'usuario='+user+'&acceso='+acceso,
		success: function(response){
			//alert(response);
			if(response){
				var arAccesos = response.split('|');
				for(i=0; i<=arAccesos.length; i++){
					if(arAccesos[i]){
						var partes = arAccesos[i].split('&');
						//alert(partes[0]+"---"+partes[1]);
						if(partes[1]=='0'){
							disableButton('#'+partes[0]);
							strHideIcons += partes[0] + "|";
						}
						else
							enableButton('#'+partes[0]);
					}
				}
			}
		}		
	});
}

function verificaIconos(){
	if(strHideIcons.length>0){
		var arAccesos = strHideIcons.split("|");
		for(i=0; i<=arAccesos.length; i++){
			if(arAccesos[i])
				$('img[alt="'+arAccesos[i]+'"]').hide();
		}		
	}
}

function IsNumeric(input)
{
    return (input - 0) == input && (input+'').replace(/^\s+|\s+$/g, "").length > 0;
}

function getEnlace(ruta) {	
	ruta = urljs + ruta;	
	$('#content-body').load(ruta, function() {});
}