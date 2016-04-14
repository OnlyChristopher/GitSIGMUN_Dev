<?php

/**
 * MantuitController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantuitController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
			
		$path = new Zend_Session_Namespace ( 'path' );
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar ();
		$ar = new Libreria_ArraysFunctions();
		
		$this->view->title = "Mantenimiento de UIT";
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array ('#btnsalir', "click", "closePopup('#popup');" );
		
		$evt[] = array ('#btnbuscar',"click", "buscarUIT();" );
		$evt[] = array ('#btnNuevo',"click", "showPopup('mantuit/editar','#popupuit','300','300','Nueva UIT');" );		
		
		$cb_anno   = $this->_request->getParam('cb_anno');
						
		$cn = new Model_DbDatos_Datos ();
		$nombreprocedure = "rentas.sp_uit";
		$arraydatos[]= array ("@busc", 1 );			
		$rows   = $cn->ejec_store_procedura_sql ( $nombreprocedure,$arraydatos);
		$arRows = $ar->RegistrosCombo($rows,0,0);		
		$val[]  = array('#cb_anno',$fn->ContenidoCombo($arRows,'---',''),'html');

		$fn->PintarEvento ( $evt );		
		$fn->PintarValor ( $val );
		
	}
	
	public function consultaAction() {
			
		$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page'])   ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$cb_anno = $_REQUEST['cb_anno'];   
    	
    	//Para el total
    	$arraydatos[]=array('@busc',3);
		$arraydatos[]=array("@anno", $cb_anno );
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_uit', $arraydatos);
    	
		//Para las filas
		unset($arraydatos);
    	$arraydatos[] = array('@busc',2);
		$arraydatos[]=array("@anno", $cb_anno );
		$arraydatos[]=array("@inicio",$start);
		$arraydatos[]=array("@final",$end);			
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_uit', $arraydatos);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
			foreach($rows AS $row){
				$entry = array(
					'anno'    	=>$row[0],				  
					'tipo'		=>($row[1]),
					'valor_uit'	=>($row[2]),
					'imp_min'	=>($row[3]),
					'imp_max'	=>($row[4]),
					'costo_emision'  =>($row[5]),
					'costo_adicional'=>($row[6])						
				);
				$jsonData['rows'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
	}
	
	public function editarAction(){
	 
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();				
		
		$anno  = $this->_request->getParam('anno');
				
		$nombreprocedure = "Rentas.sp_uit";
		//unset ( $arraydatos );
		$arraydatos [] = array ("@busc", 4 );
		$arraydatos [] = array ("@anno", $anno );
		$rows = $cn->ejec_store_procedura_sql ( $nombreprocedure, $arraydatos );
		
		if (count ( $rows ))
		 {
			///para mi editar
			$anno 	 	 =  $rows[0][0];
			$tipo		 =  $rows[0][1];
			$valor_uit   =  $rows[0][2];
			$imp_min	 =  $rows[0][3];
			$imp_max     =  $rows[0][4];
			$costo_emision  =$rows[0][5];
			$costo_adicional=$rows[0][6];
			
		///esto es para mi editar E=editar / N=nuevo
			$val[] = array ('#actionuit', 'E', 'val' );
		///---------->desabilita el texto q quiero	
			$att[] = array('#anno',"readonly","true");
		//////////////////////////////////
		}else{ 	
			$val[] = array ('#actionuit', 'N', 'val' );	

			$att[] = array('#anno',"","");
		}
		$val[] = array('#anno',$anno,'val');
		$val[] = array('#tipo',$tipo,'val');
		$val[] = array('#valor_uit',$valor_uit,'val');
		$val[] = array('#imp_min',$imp_min,'val');
		$val[] = array('#imp_max',$imp_max,'val');
		$val[] = array('#costo_emision',$costo_emision,'val');
		$val[] = array('#costo_adicional',$costo_adicional,'val');
			
		$evt[] = array('#btnSaveUit',"click","goToFormulario('frm_editarUIT');");
		$evt[] = array('#btnCloseUit',"click","closePopup('#popupuit');" );
				
		$fn->PintarEvento ( $evt );	
		$fn->PintarValor ($val);
		
	}
	
	public function grabarAction(){
	
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		if ($this->getRequest ()->isXmlHttpRequest ()) {
						
			$anno           = $this->_request->getParam ('anno');
			$tipo           = $this->_request->getParam ('tipo');
			$valor_uit      = $this->_request->getParam ('valor_uit');
			$imp_min        = $this->_request->getParam ('imp_min');
			$imp_max        = $this->_request->getParam ('imp_max');
			$costo_emision  = $this->_request->getParam ('costo_emision');
			$costo_adicional= $this->_request->getParam ('costo_adicional');
			$accion         = $this->_request->getParam ('actionuit');  
			                                                    
			///condicion pa editar y nuevo
			if($accion=='N')
							
				$tip=5;				
			
			else
				$tip=6;				
				
		///--------------------------------------------------------///
			
			$nombrestore = "Rentas.sp_uit";			
			$arraydatos [] = array ("@busc", $tip);
			$arraydatos [] = array ("@anno", $anno );
			$arraydatos [] = array ("@tipo", $tipo );
			$arraydatos [] = array ("@valor_uit", $valor_uit );
			$arraydatos [] = array ("@imp_min", $imp_min );
			$arraydatos [] = array ("@imp_max", $imp_max );
			$arraydatos [] = array ("@costo_emision", $costo_emision );
			$arraydatos [] = array ("@costo_adicional", $costo_adicional );
			$cn = new Model_DbDatos_Datos ();
			$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
			
			echo 'Se guardo correctamente';
		}
	}
	
	public function eliminarAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$anno = $this->_request->getParam ( 'cb_anno' );
		$nombrestore = "Rentas.sp_uit";
		$arraydatos [] = array ("@busc", 7 );
		$arraydatos [] = array ("@anno", $anno );
		$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
		
		echo 'se elimino correctamente';
	}
//------------>esta es la funcion para validar el campo anno	
	public function validaAction() {
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		$cn = new Model_DbDatos_Datos ();
		
		$actionuit = $this->_request->getParam ( 'actionuit' );
		
			if($actionuit=='N'){
		
				$anno = $this->_request->getParam ( 'anno' );
				$nombrestore = "Rentas.sp_uit";
				$arraydatos [] = array ("@busc", 4 );
				$arraydatos [] = array ("@anno", $anno );
				$rows = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
				
				if(count($rows)>0)
					echo false;
				else
					echo true;
					}
						else
							echo true;
	}

}

	
