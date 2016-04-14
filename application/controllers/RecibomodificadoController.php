<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RecibomodificadoController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	// public function indexAction() {
	//	TODO Auto-generated MantbusquedaController::indexAction() default action
	// }	
	
	
	
	public function recibomodiAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;
		
		
		$fn = new Libreria_Pintar();
	
		
		$evt[] = array('#txtNumdocu',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtGlosa',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#btnSalirRecibo',"click","closePopup('#popuprecibomodi');");
		$evt[] = array('#btnGenerarRecibo',"click","GenerarReciboModificado();");
		$val[] = array('#txtUsuario',trim($login->user),'val');
	
		
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }

	public function grabarreciboAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$this->_helper->Layout->disableLayout();		
		
		
		//$txtNumdocu = $this->_request->getPost('txtNumdocu');
		$txtGlosa = $this->_request->getPost('txtGlosa');
		
		
		$json = $this->_request->getPost('json');
		$data = json_decode($json);
		$dxml = '';
			foreach ($data as $key => $value){ 
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			

			$cn = new Model_DbDatos_Datos();
	
			$nombrestore="Coactivo.SP_RecibosModificado";
			
			$arraydatos[]=array("@dataxml", $dxml);
			//$arraydatos[]=array("@nro_docu", $txtNumdocu);
			$arraydatos[]=array("@glosa", $txtGlosa);
			$arraydatos[]=array("@usuario", $username);

			$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
			
		
}
	
}

