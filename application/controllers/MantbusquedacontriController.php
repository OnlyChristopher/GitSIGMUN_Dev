<?php

/**
 * MantbusquedaController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantbusquedacontriController extends Zend_Controller_Action {
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
		$evt[] = array('#btnSearchcontri',"click","buscarContricoac()");
						
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
    	
    	$rdCriteriobuscoac = $_REQUEST['rdCriteriobuscoac'];
    	$txtCriteriobuscoac = $_REQUEST['txtCriteriobuscoac'];
		$txtCriterioNombrebuscoac = $_REQUEST['txtCriterioNombrebuscoac'];
		$txtCriterioAPaternobuscoac = $_REQUEST['txtCriterioAPaternobuscoac'];
		$txtCriterioAMaternobuscoac = $_REQUEST['txtCriterioAMaternobuscoac'];
		$txtCriterioRazonbuscoac = $_REQUEST['txtCriterioRazonbuscoac'];
		$txtDocumentobuscoac = $_REQUEST['txtDocumentobuscoac'];
    	
		//{rdCriteriobus: rdCriteriobus, txtCriteriobus: txtCriteriobus, txtCriterioNombrebus:txtCriterioNombrebus
			//,txtCriterioAPaternobus: txtCriterioAPaternobus,txtCriterioAMaternobus:txtCriterioAMaternobus,txtCriterioRazonbus:txtCriterioRazonbus,txtDocumentobus:txtDocumentobus};
		//{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
		
    
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$txtCriteriobuscoac);
		$parametros[] = array('@nombres',$txtCriterioNombrebuscoac);
		$parametros[] = array('@paterno',$txtCriterioAPaternobuscoac);
		$parametros[] = array('@materno',$txtCriterioAMaternobuscoac);
		$parametros[] = array('@razon',$txtCriterioRazonbuscoac);
		$parametros[] = array('@num_doc',$txtDocumentobuscoac);
		$parametros[] = array('@tipo_busqueda',$rdCriteriobuscoac);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecoactivo', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$txtCriteriobuscoac);
		$parametros[] = array('@nombres',$txtCriterioNombrebuscoac);
		$parametros[] = array('@paterno',$txtCriterioAPaternobuscoac);
		$parametros[] = array('@materno',$txtCriterioAMaternobuscoac);
		$parametros[] = array('@razon',$txtCriterioRazonbuscoac);
		$parametros[] = array('@num_doc',$txtDocumentobuscoac);
		$parametros[] = array('@tipo_busqueda',$rdCriteriobuscoac);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rowsBaja = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyentecoactivo', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rowsbaja'=>array());
		if(count($rowsBaja))
		{
			foreach($rowsBaja AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[5])." ".utf8_encode($row[6])." ".utf8_encode($row[4]),
						'documento'=>utf8_encode($row[3]),
						'direccionfiscal'=>utf8_encode($row[28]),
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

