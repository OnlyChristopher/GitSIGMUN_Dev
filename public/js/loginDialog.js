/**
* Ext.ux.LoginDialog Class
*
* @extends Ext.window.Window
* @version 1.1
*
*/

function errorMessage(t,m,f){
	Ext.MessageBox.show({
        title:t,
        msg: m,
        buttons: Ext.MessageBox.YES,
        buttonText: {yes: 'Aceptar'},
        icon: Ext.MessageBox.ERROR,
        fn: f
    });
}

Ext.define('Ext.ux.LoginDialog', {
    extend: 'Ext.window.Window',
    requires: ['Ext.layout.container.Border', 'Ext.form.Panel', 'Ext.form.field.Checkbox'],
    alias: 'widget.logindialog',

    cls: 'form-login-dialog',
    iconCls: 'form-login-icon-title',
    width: 400,
    height: 220,
    resizable: false,
    closable: false,
    draggable: true,
    modal: true,
    closeAction: 'hide',
    layout: 'border',
    title: 'Ingreso al Sistema',

    headerPanel: undefined,
	leftPanel: undefined,
    formPanel: undefined,

    usernameField: undefined,
    passwordField: undefined,

    loginAction: undefined,
    cancelAction: undefined,

    initComponent: function() {
        var config = {};
        Ext.applyIf(this, Ext.apply(this.initialConfig, config));

        this.messages = this.messages || {};
        this.messages = Ext.Object.merge({
            wait: 'Espere un momento...',
            header: 'Acceso restringido sólo para usuarios autorizados.<br />' +
                'Por favor ingrese su usuario y contraseña.'
        }, this.messages);

        this.headerPanel = this.headerPanel || {};
        this.headerPanel = Ext.create('Ext.panel.Panel', Ext.Object.merge({
            cls: 'form-login-header',
            baseCls: 'x-plain',
            html: this.messages.header,
            region: 'north',
            height: 60
        }, this.headerPanel));
		
		this.leftPanel = this.leftPanel || {};
        this.leftPanel = Ext.create('Ext.panel.Panel', Ext.Object.merge({
            region: 'west',
			html: '<img src="'+urljs+'img/logo.png" style="padding:10px 0 0 10px">',
			border:false,
			width: 160
        }, this.leftPanel));
        
        this.usernameField = this.usernameField || {};
        this.usernameField = Ext.Object.merge({
            xtype: 'textfield',
            ref: 'usernameField',
            id: 'txtusuario',
            name: 'txtusuario',
            fieldLabel: 'Usuario',
            enableKeyEvents: true,
            listeners: {
        		render: {
		            fn:function(field, eOpts) {
		                field.capsWarningTooltip = Ext.create('Ext.tip.ToolTip', {
		                    target: field.bodyEl,
		                    anchor: 'left',
		                    width: 120,
		                    html: 'Ingrese su usuario'
		                });
		
		                field.capsWarningTooltip.disable();
		            },
		            scope:this
		        },
	        	keypress: {
		            fn: function(field, e, eOpts) {
		                var charCode = e.getCharCode();
		                if(charCode == 13){
		                	this.submit();
							field.capsWarningTooltip.disable();
		                }
		            },
		            scope: this
	        	}
        	}
        }, this.usernameField);

        this.passwordField = this.passwordField || {};
        this.passwordField = Ext.Object.merge({
            xtype: 'textfield',
            ref: 'passwordField',
            inputType: 'password',
            id: 'txtclave',
            name: 'txtclave',
            fieldLabel: 'Contraseña',
            enableKeyEvents: true,
            listeners: {
				render: {
			            fn:function(field, eOpts) {
			                field.capsWarningTooltip = Ext.create('Ext.tip.ToolTip', {
			                    target: field.bodyEl,
			                    anchor: 'left',
			                    width: 120,
			                    html: 'Ingrese su clave'
			                });
			
			                field.capsWarningTooltip.disable();
			            },
			            scope:this
			        },
	        	keypress: {
		            fn: function(field, e, eOpts) {
		                var charCode = e.getCharCode();
		                if(charCode == 13){
		                	this.submit();
							field.capsWarningTooltip.disable();
		                }
		            },
		            scope: this
	        	}
        	}
		}, this.passwordField);
                
        this.formPanel = this.formPanel || {};
        this.formPanel = Ext.create('Ext.form.Panel', Ext.Object.merge({
            bodyPadding: 10,
            header: false,
            region: 'center',
            border: false,
            waitMsgTarget: true,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            defaults: {
                labelWidth: 75
            },
            items: [
                this.usernameField,
                this.passwordField,
                this.forgotPassword, {
                    xtype: 'box',
                    autoEl: 'div',
                    height: 10
                }, this.languageField,
                this.rememberMeField
            ]
        }, this.formPanel));

        this.loginAction = this.loginAction || {};
        this.loginAction = Ext.Object.merge({
            text: 'Ingresar',
            ref: '../loginAction',
            iconCls: 'form-login-icon-login',
            scale: 'medium',
            width: 90,
            handler: this.submit,
            scope: this
        }, this.loginAction);

        this.cancelAction = this.cancelAction || {};
        this.cancelAction = Ext.Object.merge({
            text: 'Cancelar',
            ref: '../cancelAction',
            iconCls: 'form-login-icon-cancel',
            scale: 'medium',
            width: 90,
            handler: this.cancel,
            scope: this
        }, this.cancelAction);

        this.buttons = this.buttons || [];
        this.buttons = this.buttons.concat([this.loginAction, this.cancelAction]);

        this.items = this.items || [];
        this.items = this.items.concat([this.headerPanel, this.leftPanel, this.formPanel]);

        this.keys = this.keys || [];
        this.keys = this.keys.concat([{
            key: [10,13],
            handler: this.submit,
            scope: this
        }]);

        if(this.cancelAction && (this.cancelAction.hidden === undefined || this.cancelAction.hidden === false)) {
            this.keys = this.keys.concat([{
                key: [27],
                handler: this.cancel,
                scope: this
            }]);
        }

        this.callParent(arguments);

        this.addEvents ('failure');
    },

    onShow: function() {
        this.callParent(arguments);
        Ext.getCmp('txtusuario').focus(false, 200);
    },

    onRender: function() {
        this.callParent(arguments);
    },

    submit: function () {
        var form = this.formPanel.getForm();
        var correct = true;
        
        if (form.isValid())
        {
        	if(Ext.getCmp('txtusuario').getValue()==""){
        		Ext.getCmp('txtusuario').capsWarningTooltip.enable();
        		Ext.getCmp('txtusuario').capsWarningTooltip.show();
        		Ext.getCmp('txtusuario').focus(false, 200);
        		correct = false;
        	}
        	else
        	if(Ext.getCmp('txtclave').getValue()==""){
        		Ext.getCmp('txtclave').capsWarningTooltip.enable();
				Ext.getCmp('txtclave').capsWarningTooltip.show();
        		Ext.getCmp('txtclave').focus(false, 200);
        		correct = false;
        	}

			if(correct){
	            form.submit({
	                url: form.url,
	                method: form.method || 'post',
	                waitMsg: form.waitMsg || 'Espere un momento...',
	                failure: this.onFailure,
	                scope: this
	            });
			}
        }
    },

    cancel: function() {
    	Ext.getCmp('txtusuario').setValue('');
    	Ext.getCmp('txtclave').setValue('');
    	Ext.getCmp('txtusuario').focus(false, 200);
    },

    onFailure: function (form, action) {
    	if(action.result.flag)
    		window.location.replace(action.result.url);
    	else
    		errorMessage('Error',action.result.message,this.err);    		
    },
    
    err: function(){
    	Ext.getCmp('txtusuario').setValue('');
    	Ext.getCmp('txtclave').setValue('');
    	Ext.getCmp('txtusuario').focus(false, 200);
    }
});
