Ext.define('TreeFilter', {
    extend: 'Ext.AbstractPlugin'
        , alias: 'plugin.treefilter'
        , collapseOnClear: true                                                 // collapse all nodes when clearing/resetting the filter
        , allowParentFolders: false                                             // allow nodes not designated as 'leaf' (and their child items) to  be matched by the filter
        , init: function (tree) {
            var me = this;
            me.tree = tree;
            tree.filter = Ext.Function.bind(me.filter, me);
            tree.clearFilter = Ext.Function.bind(me.clearFilter, me);
        }
        , filter: function (value, property, re) {
            var me = this
                , tree = me.tree
                , matches = []                                                  // array of nodes matching the search criteria
                , root = tree.getRootNode()                                     // root node of the tree
                , property = property || 'text'                                 // property is optional - will be set to the 'text' propert of the  treeStore record by default
                , re = re || new RegExp(value, "ig")                            // the regExp could be modified to allow for case-sensitive, starts  with, etc.
                , visibleNodes = []                                             // array of nodes matching the search criteria + each parent non-leaf  node up to root
                , viewNode;

            if (Ext.isEmpty(value)) {                                           // if the search field is empty
                me.clearFilter();
                return;
            }

            tree.expandAll();                                                   // expand all nodes for the the following iterative routines

            // iterate over all nodes in the tree in order to evalute them against the search criteria
            root.cascadeBy(function (node) {
                if (node.get(property).match(re)) {                             // if the node matches the search criteria and is a leaf (could be  modified to searh non-leaf nodes)
                    matches.push(node);                                         // add the node to the matches array
                }
            });

            if (me.allowParentFolders === false) {                              // if me.allowParentFolders is false (default) then remove any  non-leaf nodes from the regex match
                Ext.each(matches, function (match) {
                    if (!match.isLeaf()) {
                        Ext.Array.remove(matches, match);
                    }
                });
            }

            Ext.each(matches, function (item, i, arr) {                         // loop through all matching leaf nodes
                root.cascadeBy(function (node) {                                // find each parent node containing the node from the matches array
                    if (node.contains(item) == true) {
                        visibleNodes.push(node);                                // if it's an ancestor of the evaluated node add it to the visibleNodes  array
                    }
                });
                if (me.allowParentFolders === true && !item.isLeaf()) {        // if me.allowParentFolders is true and the item is  a non-leaf item
                    item.cascadeBy(function (node) {                            // iterate over its children and set them as visible
                        visibleNodes.push(node);
                    });
                }
                visibleNodes.push(item);                                        // also add the evaluated node itself to the visibleNodes array
            });

            root.cascadeBy(function (node) {                                    // finally loop to hide/show each node
                viewNode = Ext.fly(tree.getView().getNode(node));               // get the dom element assocaited with each node
                if (viewNode) {                                                 // the first one is undefined ? escape it with a conditional
                    viewNode.setVisibilityMode(Ext.Element.DISPLAY);            // set the visibility mode of the dom node to display (vs offsets)
                    viewNode.setVisible(Ext.Array.contains(visibleNodes, node));
                }
            });
        }

        , clearFilter: function () {
            var me = this
                , tree = this.tree
                , root = tree.getRootNode();

            if (me.collapseOnClear) {
                tree.collapseAll();                                             // collapse the tree nodes
            }
            root.cascadeBy(function (node) {                                    // final loop to hide/show each node
                viewNode = Ext.fly(tree.getView().getNode(node));               // get the dom element assocaited with each node
                if (viewNode) {                                                 // the first one is undefined ? escape it with a conditional and show  all nodes
                    viewNode.show();
                }
            });
        }
});

Ext.onReady(function(){
    Ext.QuickTips.init();
	
	$("input[type='button']").button();

	$("#btnNuevoHR").button({icons:{primary:"ui-icon-print"}});
	$("#btnEditaHR").button({icons:{primary:"ui-icon-print"}});
	$("#btnEliminarHR").button({icons:{primary:"ui-icon-print"}});
	$("#btnGeneraRD").button({icons:{primary:"ui-icon-print"}});
    $("#btnGeneraMT").button({icons:{primary:"ui-icon-print"}});
	$("#btnCerraIndex").button({icons:{primary:"ui-icon-print"}});
	
	RecargarTree()
	
});

function RecargarTree(){

	
	$('#IdPanelRequerimiento').html('');
	
	var anioini='.';
	var idini='0';
	var panelCentro = Ext.create('Ext.Panel', {
		id : 'contentCenter',
		region : 'center',
		margins : '2 2 2 2',
	    autoLoad:
        {  
    	   url : urljs + 'fiscalizaciondetalle/indexcentro?txtidrq='+$('#txtidrq').val(),
    	   scripts: true
        }
	}) 

//tree begin
    var storeTree = Ext.create('Ext.data.TreeStore', {
		proxy: {
            type: 'ajax',
            url: urljs + 'fiscalizaciondetalle/datostree',
			extraParams: {
				txtidx  : $('#txtidrq').val(),
			},
            reader: {
                type: 'json',
                root: 'children'
            }
        },
		listeners: {
			append: function(thisNode, newChildNode, index, eOpts) {
				//Capturamos el último nodo
					anioini = newChildNode.get('text');
					idini = newChildNode.get('id');
				}
		}
    }); 

//margins: '[Arriba] [Derecha] [Abajo] [Izquierda]',
    var tree = new Ext.tree.TreePanel({
    	id: 'IdTreeRequerimiento',
		singleExpand: false,
		rootVisible: false,
        width: 160,
 		region:'west',
		layout: 'fit',
        useArrows: true,
		margins: '2 2 2 2',
        store: storeTree,
		autoScroll:true,
		plugins: [{
			ptype: 'treefilter',
			allowParentFolders: true
		}],
		dockedItems: [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'trigger',
				width : 170,
                triggerCls: 'x-form-clear-trigger',
				fieldLabel: '<b>Buscar </b>',
				emptyText: '2015',
                onTriggerClick: function(){
                    this.reset();
                    this.focus();
                },
				listeners: {
                    change: function (field, newVal) {
                        var tree = field.up('treepanel');
                        tree.filter(newVal);
                    },
					buffer: 250
                }
            }]
        }],
		//split:true,
		//collapsible:true,
        listeners: {
            itemclick:function(s,r) {
    			var anio = r.data.text ;			
				var idre = r.data.id ;
				if (idre!=='_001'){
					$('#txtaniohr').val(anio);
					$('#txtorigen').val(anio);
					$('#txtaniodecla').val(anio);
					$('#txtidre').val(idre);
					MostrarPredios();
				}
            },
			load:function(loader,node,response){
				//Cargamos la grilla
				/* if (idini!=='_001'){
					$('#txtaniohr').val(anioini);
					$('#txtorigen').val(anioini);
					$('#txtidre').val(idini);
				} */
				//MostrarPredios();
		    }
        }
    });
//tree end

	var center_area = new Ext.Panel({
		//title	: 'Panel',
	    layout	: 'border',
        height	: 550,
		//width	: 600,
	    border	: true,
	    autoScroll: true,
	    bodyStyle : 'padding: 1px', // todo el marco
		items	  : [tree,panelCentro],
	    renderTo  :'IdPanelRequerimiento'
	});
}