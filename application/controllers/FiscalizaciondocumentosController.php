<?php

class FiscalizaciondocumentosController extends Zend_Controller_Action
{

    public function init(){
	
    }

    public function indexAction(){
		$fn = new Libreria_Pintar();

		$evt[] = array('#btnResolucion',"click","VerResolucion();");
		$evt[] = array('#btnBuscarRes',"click","buscarResoluciones();");
		$evt[] = array('#btnImprimir',"click","ImprimirResolucion();");
		$evt[] = array('#btnInspeccion',"click","InspeccionResolucion();");	
		$evt[] = array('#btnMulta',"click","MultaResolucion();");		
		
		$fn->PintarEvento($evt);
    }

	public function gridresolucionesAction(){
		
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		$cmbtipos = $this->_request->getParam('cmbtipos','');
		$txtdatos = $this->_request->getParam('txtdatos','');

    	$parametros[] = array('@buscar',$cmbtipos);
		$parametros[] = array('@idPk',$txtdatos);
    	    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MResolucion_Buscar', $parametros);
    	
    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
					'idPk'=>$row[0],
					'valor'=>utf8_encode($row[1]),
					'codigo'=>$row[3],
					'nombre'=>utf8_encode($row[4]),
					'fecha'=>$row[2],
					'montotal'=>utf8_encode($row[5]),
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	
	
    }
	
	public function verdetalleAction(){
		$fn = new Libreria_Pintar();
		
		$txtres = $this->_request->getParam('idrs','');
		$evt[] = array('#btnvercerrar', "click", "closePopup('#popVerResolucion');");
		$val[] = array('#txtres', $txtres, 'val');
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function griddeudaAction(){
	
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
		
		//$txtres = $this->_request->getPost('txtres');
		$txtres = $this->_request->getParam('txtres','');

    	$parametros[] = array('@buscar',2);
		$parametros[] = array('@idPk',$txtres);
    	    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Fiscalizacion.sp_MResolucion_Buscar', $parametros);
    	
		//echo $txtres;

    	$jsonData = array('rows'=>array());
    	foreach($rowRuta as $row){
			$entry = array(
					'idPk'=>$row[0],
					'anio'=>utf8_encode($row[7]),
					'periodo'=>utf8_encode($row[8]),
					'insoluto'=>utf8_encode($row[9]),
					'reajuste'=>utf8_encode($row[10]),
					'mora'=>utf8_encode($row[11]),
					'emision'=>utf8_encode($row[12]),
					'total'=>utf8_encode($row[13]),
					'fecha'=>$row[2],
					'montotal'=>utf8_encode($row[5]),
					'valor'=>utf8_encode($row[1]),
					'estado'=>utf8_encode($row[15]),
					'dfecven'=>utf8_encode($row[14]),
			);
			$jsonData['rows'][] = $entry;
		}
		$this->view->data = json_encode($jsonData);	

	}
}

?>