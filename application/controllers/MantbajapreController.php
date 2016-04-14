<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantbajapreController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		// TODO Auto-generated MantbusquedaController::indexAction() default action
	}	
	
	public function buscarAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
			
		$codigo=$this->_request->getParam('codigobaja','');	
		
		//$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$evt[] = array('#btnSearchBaja',"click","buscarBajapre()");
		
		$val[] = array('#txtCodigo',$codigo,'val');	
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}

    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
		$codigo = $_REQUEST['codigobaja'];
		
    	$parametros[] = array('@busc',5);		
		$parametros[] = array('@codigo',$codigo);
		$rowsBaja = $cn->ejec_store_procedura_sql('Rentas.sp_Verbaja', $parametros);
		
		$jsonData = array();
		if(count($rowsBaja))
		{
			foreach($rowsBaja AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'anno'=>$row[1],	
						'cod_pred'=>$row[2],
						'anexo'=>utf8_encode($row[4]),
						'sub_anexo'=>utf8_encode($row[5]),
						'direccion'=>utf8_encode($row[12]),
						'fechdescargo'=>utf8_encode($row[9]),
						'dj_predial'=>utf8_encode($row[19])
				);
				$jsonData[] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	

}

