<?php

class CatasconrentasController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */	
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		unset($parametros);
		$parametros[] = array('@busc',3);
		$arData = $cn->ejec_store_procedura_sqlc('MntCatas.pxCRentas', $parametros);
		$arCombo = $ar->RegistrosComboc($arData,0,1,'');
		$val[] = array('#selDistrito',$fn->ContenidoCombo($arCombo,'[Todos]',''),'html');
		
		$evt[] = array('input[name*="rdCritec"]',"click","SelectSearch();");
		$fun[] = array("ActCriterio('C');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->EjecutarFuncion($fun);
    }
	
	public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
		$criterio = $_REQUEST['criterio'];
		switch($criterio)
    	{
    		case 'N': $nombre = $_REQUEST['nombre']; break;
			case 'R': $nombre = $_REQUEST['razsoc']; break;
    	}
		
		//Para el total
    	$parametros[] = array('@busc',2);
		$parametros[] = array('@codigo',$_REQUEST['codigo']);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@paterno',$_REQUEST['apepat']);
		$parametros[] = array('@materno',$_REQUEST['apemat']);
		$parametros[] = array('@num_doc',$_REQUEST['numdoc']);
		$parametros[] = array('@id_dist',$_REQUEST['iddist']);
		$parametros[] = array('@des_urb',$_REQUEST['desurb']);
		$parametros[] = array('@des_via',$_REQUEST['desvia']);
		$parametros[] = array('@Mza',$_REQUEST['manzana']);
		$parametros[] = array('@Lte',$_REQUEST['lote']);
		$parametros[] = array('@SubLte',$_REQUEST['sub_lote']);
		$parametros[] = array('@numero',$_REQUEST['numero']);
		$rowsTotal = $cn->ejec_store_procedura_sqlc('MntCatas.pxCRentas', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',1);
		$parametros[] = array('@codigo',$_REQUEST['codigo']);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@paterno',$_REQUEST['apepat']);
		$parametros[] = array('@materno',$_REQUEST['apemat']);
		$parametros[] = array('@num_doc',$_REQUEST['numdoc']);
		$parametros[] = array('@id_dist',$_REQUEST['iddist']);
		$parametros[] = array('@des_urb',$_REQUEST['desurb']);
		$parametros[] = array('@des_via',$_REQUEST['desvia']);
		$parametros[] = array('@Mza',$_REQUEST['manzana']);
		$parametros[] = array('@Lte',$_REQUEST['lote']);
		$parametros[] = array('@SubLte',$_REQUEST['sub_lote']);
		$parametros[] = array('@numero',$_REQUEST['numero']);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		$rows = $cn->ejec_store_procedura_sqlc('MntCatas.pxCRentas', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'id_tipocontri'=>$row[6],
						'tipo_detalle'=>utf8_encode($row[7]),
						'id_docu'=>$row[8],
						'nombres'=>utf8_encode($row[9]),
						'paterno'=>utf8_encode($row[10]),
						'materno'=>utf8_encode($row[11]),
						'id_subtipocontri'=>$row[12],
						'subtipo_detalle'=>utf8_encode($row[13]),
						'id_post'=>$row[14],
						'codpost'=>utf8_encode($row[4]),
						'telefono1'=>utf8_encode($row[15]),
						'anexo1'=>utf8_encode($row[16]),
						'correo_e'=>utf8_encode($row[17]),
						'id_via'=>$row[18],
						'nombre_via'=>utf8_encode($row[19]),
						'numero'=>utf8_encode($row[20]),
						'nombre_edificio'=>utf8_encode($row[21]),
						'numero2'=>utf8_encode($row[22]),
						'manzana'=>utf8_encode($row[23]),
						'lote'=>utf8_encode($row[24]),
						'sub_lote'=>utf8_encode($row[25]),
						'id_zona'=>$row[26],
						'nombre_zona'=>utf8_encode($row[27]),
						'nombre'=>utf8_encode($row[1]),
						'tipodoc'=>utf8_encode($row[2]),
						'numedoc'=>utf8_encode($row[3]),
						'distrito'=>utf8_encode($row[4]),
						'direccion'=>utf8_encode($row[5])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function datcontriAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$json = $this->_request->getPost('json');
		$this->view->json = $json;
		
		$evt[] = array('#btnDContriAceptar',"click","selectDatosContri();");
		$evt[] = array('#btnDContriCancelar',"click","closePopup('#popcatasrentasdatos');");
		
		$fn->PintarEvento($evt);
    }
}