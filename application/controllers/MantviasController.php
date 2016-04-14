<?php

/**
 * MantviasController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantviasController extends Zend_Controller_Action {
	
	public function indexAction() {
		
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;		
		$this->view->title = "Búsqueda de Vias";
		$fn = new Libreria_Pintar ();
		
		$codigo = $this->_request->getParam ( 'codigo', '' );
	
		$this->view->codvia = $codigo;
		
		$bd = 'Rentas.sp_MVias';
		$arraydatos [] = array ('@busc', 5 );
		$arraydatos [] = array ('@id_urba', $codigo );
		$cn = new Model_DbDatos_Datos ();
		$rows = $cn->ejec_store_procedura_sql ( $bd, $arraydatos );
/////////capturo los valores////////////
		$val [] = array ('#codigo', $rows[0] [0], 'val' );
		$val [] = array ('#zona',   $rows[0] [3], 'val' );
		$val [] = array ('#cp',     $rows[0] [2], 'val' );
		$val [] = array ('#nom_urb',$rows[0] [4], 'val' );
				
		$evt [] = array ('#btn_nuevaVia',"click", "showPopup('mantvias/editar?id_urba=".$rows[0][0]."&id_zona=".$rows[0][3]."','#popEditVias','300','230','Nueva Vía');" );
		$evt [] = array ('#btn_cerrarVia',"click", "closePopup('#popDetVias');");
		
		/////
		$fn->PintarEvento ( $evt );
		
		$fn->PintarValor ( $val );
		
	}
	
	public function consultaAction() {
		$cn = new Model_DbDatos_Datos ();
		
		$codvia = $this->_request->getParam ( 'codvia', '' );//aqui obtiene el parametro codvia de jsManvia

		//Para las filas
		//unset($parametros);
		$parametros [] = array ('@busc', 4 );
		$parametros [] = array ('@id_urba', $codvia );
		$parametros [] = array ('@inicio', 1 );
		$parametros [] = array ('@final', 10 );
		
		$rows = $cn->ejec_store_procedura_sql ( 'rentas.sp_mvias', $parametros );
		
		$jsonData = array ('total'=>count($rows),'rows'=>array());
			foreach ( $rows as $row ) {
				$entry = array (
					'cod_via'=>$row [4],
					'nombre_via'=>utf8_encode($row[5])
				);
				$jsonData ['rows'] [] = $entry;
		}		
		$this->view->data = json_encode ( $jsonData );	
	}
	
	public function consultarancelAction() {
		$cn = new Model_DbDatos_Datos ();
		
		$codvia = $this->_request->getParam ( 'codvia', '' );//aqui obtiene el parametro codvia de jsManarancel
		//echo $codvia;	    	
		//Para las filas
		//unset($parametros);
		$parametros [] = array ('@busc', 13 );
		$parametros [] = array ('@cod_via', $codvia );
		$parametros [] = array ('@inicio', 1 );
		$parametros [] = array ('@final', 10 );
		
		$rows = $cn->ejec_store_procedura_sql('rentas.sp_mvias',$parametros);
		
		$jsonData = array ('total' => count ( $rows ), 'rows' => array () );
			foreach ( $rows as $row ) {
				$entry = array ('anno' => utf8_encode ( $row [2] ),
							    'arancel'=>utf8_encode( $row [3]));
				$jsonData ['rows'] [] = $entry;
		}		
		$this->view->data = json_encode ( $jsonData );
	
	}
	public function editarAction() {
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();

		$cn = new Model_DbDatos_Datos ();
		
		$evt[] = array('#txtnombre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtnombre',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtcuadra',"keypress","return validaTeclas(event,'number');");
		
		$codvia = trim ( $this->_request->getParam ( 'codvia', '' ) );		
		
		$nombreprocedure = "Rentas.sp_MVias";
		unset ( $arraydatos );
		$arraydatos [0] = array ("@busc", 9 );
		$arraydatos [1] = array ("@cod_via", $codvia );
		$rows0 = $cn->ejec_store_procedura_sql ( $nombreprocedure, $arraydatos );
		if (count ( $rows0 )) {
			///para mi editar
			$cod_via = $rows0 [0] [0];
			$id_zona = $rows0 [0] [1];			
			$id_tipo = $rows0 [0] [3];
			$vcuadra = $rows0 [0] [5];
			$id_urba = $rows0 [0] [7];			
			$nombrevia=$rows0 [0] [6];
			$id_vias = $rows0 [0] [8];
			
		//////nombre de mi hidden q mando de mi formulario editar/////////////
		///esto es para mi nuevo
			$val [] = array ('#datosvias', 'MA', 'val' );
		//////////////////////////////////
		}else{ 
			$id_urba = trim ( $this->_request->getParam ('id_urba' ) );
			$id_zona = trim ( $this->_request->getParam ('id_zona') );			
			
			$val [] = array ('#urbanizacion', $id_urba, 'val' );
			$val [] = array ('#sector',$id_zona, 'val' );			
		}
			
		//////////////////////////tipo///////
		unset ( $arraydatos );
		$nombreprocedure = "Rentas.sp_MVias";
		$arraydatos [0] = array ("@busc", 7 );
		
		$rows3 = $cn->ejec_store_procedura_sql ( $nombreprocedure, $arraydatos );
		$arRows3 = $ar->RegistrosCombo($rows3,0,1);
		$val[] = array('#cb_tipovia',$fn->ContenidoCombo($arRows3,'[Seleccione]',$id_tipo),'html');
					
		unset ( $arraydatos );
		$nombreprocedure = "Rentas.sp_MVias";
		$arraydatos [] = array ("@busc", 6 );
		$arraydatos [] = array ("@id_urba", $codvia );
		
		$rows1 = $cn->ejec_store_procedura_sql ( $nombreprocedure, $arraydatos );
		$arRows1 = $ar->RegistrosCombo($rows1,0,1);
		$val[] = array('#cb_urba',$fn->ContenidoCombo($arRows1,'[Seleccione]',$id_urba),'html');
		
		$arRows2 = $ar->RegistrosCombo($rows1,2,3);

		$val[] = array('#cb_sector_1',$fn->ContenidoCombo($arRows2,'[Seleccione]',$id_zona),'html');
			
		/////////aki capturo mis valores de mi textoculto de la vista editar
		$val [] = array ('#centroP', $codvia, 'val' );
		$val [] = array ('#urbanizacion',$id_urba, 'val' );
		$val [] = array ('#sector',$id_zona, 'val' );
		$val [] = array ('#txtcuadra',$vcuadra,'val');
		$val [] = array ('#txtcod_via',$codvia, 'val' );
		$val [] = array ('#txtnombre',$nombrevia, 'val' );
		$val [] = array ('#id_vias',$id_vias, 'val' );	
				
		$evt [] = array ('#btnSaveVia', "click", "goToFormulario('frm_editarvia')" );
		$evt [] = array ('#btnCloseVia', "click", "closePopup('#popEditVias');" );
		
		$fn->PintarValor ( $val );		
		$fn->PintarEvento ( $evt );
	}
	
	public function grabarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
						
			$centroP = $this->_request->getParam ( 'centroP');
			$cb_urba = $this->_request->getParam ( 'urbanizacion');
			$cb_tipovia = $this->_request->getParam( 'cb_tipovia');
			$vcuadra = $this->_request->getParam( 'txtcuadra');
			$cb_sector_1 = $this->_request->getParam ( 'sector');
			$txtnombre = $this->_request->getParam ( 'txtnombre');
			$id_vias = $this->_request->getParam ( 'id_vias');
			$accion = $this->_request->getParam ( 'datosvias');  
			                                                    
			///condicion pa editar y nuevo
			if(strlen($accion)<=0)
				$tip=1;
			else
				$tip=2;				
		///////////////////////////////////////////
			
			$nombrestore = "Rentas.sp_MVias";			
			$arraydatos [] = array ("@busc", $tip);
			$arraydatos [] = array ("@cod_via", $centroP );
			$arraydatos [] = array ("@id_urba", $cb_urba );
			$arraydatos [] = array ("@tipovia", $cb_tipovia );
			$arraydatos [] = array ("@vcuadra", $vcuadra );
			$arraydatos [] = array ("@id_zona", $cb_sector_1 );
			$arraydatos [] = array ("@nombre_via", $txtnombre );
			$arraydatos [] = array ("@id_vias", $id_vias );
			$cn = new Model_DbDatos_Datos ();
			$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
			
			$codvia = $this->_request->getParam ( 'centroP');
			$cb_urba = $this->_request->getParam ( 'urbanizacion');
			$cb_sector_1 = $this->_request->getParam ( 'sector');
			$id_vias = $this->_request->getParam ( 'id_vias');
				
			echo 'Se guardo correctamente';
		}
	}
	public function eliminarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$codvia = $this->_request->getParam ( 'txtcod_via' );
		$nombrestore = "Rentas.sp_MVias";
		$arraydatos [] = array ("@busc", 3 );
		$arraydatos [] = array ("@cod_via", $codvia );
		$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
		
		echo 'se elimino correctamente';
	}
	
	public function arancelAction() {
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();
		
		$codvia = $this->_request->getParam('codvia');
		$cb_anno   = $this->_request->getParam('cb_anno');
		$txtarancel=$this->_request->getParam('txtarancel');
				
		$this->view->codvia=$codvia;
		
		$cn = new Model_DbDatos_Datos ();
		$nombreprocedure = "Rentas.sp_Mvias";
		$arraydatos[]= array ("@busc", 10 );			
		$rows3 = $cn->ejec_store_procedura_sql ( $nombreprocedure, $arraydatos );
		$arRows3 = $ar->RegistrosCombo($rows3,0,0);
		$val[] = array('#cb_anno',$fn->ContenidoCombo($arRows3,'---',''),'html');

		$evt [] = array ('#btnSaveArancel',"click", "goToFormulario('frm_arancel');" );
		$evt [] = array ('#btnCloseArancel', "click", "closePopup('#popViasArancel');" );
		
		$evt[] = array('#txtarancel',"keypress","return validaTeclas(event,'numeric');");
			
		$fn->PintarEvento ( $evt );
		
		$fn->PintarValor ( $val );
	}
	public function guardararancelAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
		$codvia = $this->_request->getPost('codvia');				
		$cb_anno = $this->_request->getPost ( 'cb_anno');
		$txtarancel = $this->_request->getPost ( 'txtarancel');
		
		$nombrestore = "Rentas.sp_MVias";			
		$arraydatos [] = array ("@busc",16);
		$arraydatos [] = array ("@cod_via", $codvia );
		$arraydatos [] = array ("@anno", $cb_anno );				
		$cn = new Model_DbDatos_Datos ();
		$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
		
			if(count($rows))
				echo 'ya existe';
			else {
			
		$nombrestore = "Rentas.sp_MVias";
		unset($arraydatos);				
		$arraydatos [] = array ("@busc",11);
		$arraydatos [] = array ("@cod_via", $codvia );
		$arraydatos [] = array ("@anno", $cb_anno );
		$arraydatos [] = array ("@arancel", $txtarancel );		
		
		$cn = new Model_DbDatos_Datos ();
		$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
		   echo 'Se guardo correctamente';
	    }
		}
	 
    }
    public function editarancelAction(){
    	$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		$this->view->title = "Actualiza Arancel";
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();

		$cn = new Model_DbDatos_Datos ();
		
		$evt[] = array('#txtarancel1',"keypress","return validaTeclas(event,'numeric');");
					
		$codvia    = trim($this->_request->getParam('codvia',''));				
		$cb_anno   = trim($this->_request->getParam('cb_anno',''));
		$txtarancel= trim($this->_request->getParam('txtarancel',''));	
	
		$val [] = array ('#codvia', $codvia, 'val' );	
		$val [] = array ('#codvia1', $codvia, 'val' );
		$val [] = array ('#cb_anno1', $cb_anno, 'val' );
		$val [] = array ('#cb_anno2', $cb_anno, 'val' );
		$val [] = array ('#txtarancel1',$txtarancel, 'val' );	
		$evt [] = array ('#btnSaveEditArancel', "click", "goToFormulario('frm_editarancel')" );
		$evt [] = array ('#btnCloseEditArancel', "click", "closePopup('#popEditArancel');" );	
		
		$fn->PintarValor ( $val );		
		$fn->PintarEvento ( $evt );
    }
    public function actualizaeditarancelAction(){
    	$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		if ($this->getRequest ()->isXmlHttpRequest ()) {
			
		$codvia = $this->_request->getPost('codvia1');				
		$cb_anno = $this->_request->getPost ( 'cb_anno1');
		$txtarancel = $this->_request->getPost ( 'txtarancel1');
		
		
		$nombrestore = "Rentas.sp_MVias";			
		$arraydatos [] = array ("@busc",12);
		$arraydatos [] = array ("@cod_via", $codvia );
		$arraydatos [] = array ("@anno", $cb_anno );
		$arraydatos [] = array ("@arancel", $txtarancel );	
		
		$cn = new Model_DbDatos_Datos ();
		$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
		   echo 'Se guardo correctamente';
		  
	    }	 
    }
    public function eliminararancelAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$codvia = $this->_request->getParam ('codvia1','');
		$anno= $this->_request->getParam('anno1','');
		
		$nombrestore = "Rentas.sp_MVias";
		$arraydatos [] = array ("@busc", 15 );
		$arraydatos [] = array ("@cod_via", $codvia );
		$arraydatos [] = array ("@anno", $anno );
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos );
		
		echo 'se elimino correctamente';
		
	}
	
    }

		