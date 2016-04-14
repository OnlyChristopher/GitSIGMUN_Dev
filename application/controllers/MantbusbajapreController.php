<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantbusbajapreController extends Zend_Controller_Action {
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
				
		//$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$evt[] = array('#btnSearchBaja',"click","buscarBajapre()");
						
		$fn->PintarEvento($evt);
	}

    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriteriobus = $_REQUEST['rdCriteriobus'];
    	$criteriobus = $_REQUEST['txtCriteriobus'];
		$criterionombrebus = $_REQUEST['txtCriterioNombrebus'];
		$criteriopaternobus = $_REQUEST['txtCriterioAPaternobus'];
		$criteriomaternobus = $_REQUEST['txtCriterioAMaternobus'];
		$criteriorazonbus = $_REQUEST['txtCriterioRazonbus'];
		$documentobus = $_REQUEST['txtDocumentobus'];
    	
		//{rdCriteriobus: rdCriteriobus, txtCriteriobus: txtCriteriobus, txtCriterioNombrebus:txtCriterioNombrebus
			//,txtCriterioAPaternobus: txtCriterioAPaternobus,txtCriterioAMaternobus:txtCriterioAMaternobus,txtCriterioRazonbus:txtCriterioRazonbus,txtDocumentobus:txtDocumentobus};
		//{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
		
    
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$criteriobus);
		$parametros[] = array('@nombres',$criterionombrebus);
		$parametros[] = array('@paterno',$criteriopaternobus);
		$parametros[] = array('@materno',$criteriomaternobus);
		$parametros[] = array('@razon',$criteriorazonbus);
		$parametros[] = array('@num_doc',$documentobus);
		$parametros[] = array('@tipo_busqueda',$rdcriteriobus );
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentebaja', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$criteriobus);
		$parametros[] = array('@nombres',$criterionombrebus);
		$parametros[] = array('@paterno',$criteriopaternobus);
		$parametros[] = array('@materno',$criteriomaternobus);
		$parametros[] = array('@razon',$criteriorazonbus);
		$parametros[] = array('@num_doc',$documentobus);
		$parametros[] = array('@tipo_busqueda',$rdcriteriobus );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rowsBaja = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentebaja', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rowsbaja'=>array());
		if(count($rowsBaja))
		{
			foreach($rowsBaja AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'documento'=>utf8_encode($row[3]),
						'direccion'=>utf8_encode($row[15]),
						'tipodoc'=>utf8_encode($row[25]),
						'tipopersona'=>utf8_encode($row[26]),
						'subpersona'=>utf8_encode($row[27])
				);
				$jsonData['rowsbaja'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	

}

