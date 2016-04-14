<?php

class AnunciosolController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

		$this->view->title = "B&uacute;squeda de Solicitudes";

		$fn = new Libreria_Pintar();

		$evt[] = array('#contentBox',"tabs","");

		$evt[] = array('#btnNueSol',"click","nuevoAnuncio();");
		$evt[] = array('#btnBusLicen',"click","buscarContri()");

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

    	$rdcriterio = $_REQUEST['rdcriterio'];
    	$criterio = $_REQUEST['criterio'];

    	switch($rdcriterio)
    	{
    		case 'S': $nombre = $criterio; break;
    		case 'E': $nroexpe = $criterio; break;
    		case 'N': $nrolicen = $criterio; break;
    	}

    	//Para el total
    	$parametros[] = array('@msql',5);
		$parametros[] = array('@nombre',$nombre);
    	$parametros[] = array('@idSolLice',$nrolicen);
		$parametros[] = array('@cod_sol',$nroexpe);

		$rowsTotal = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);



		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msql',4);
		$parametros[] = array('@nombre',$nombre);
    	$parametros[] = array('@idSolLice',$nrolicen);
		$parametros[] = array('@cod_sol',$nroexpe);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);


		$rows = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);

		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
		foreach($rows AS $row){
			$entry = array(
					'idSolLice'=>$row[0],
                    'nro_licencia'=>utf8_encode($row[1]),
					'cod_sol'=>utf8_encode($row[2]),
                    'nombre'=>utf8_encode($row[3]),
                    'nom_esta'=>utf8_encode($row[4]),
					'dire_pred'=>utf8_encode($row[5]),
					'fech_ing'=>utf8_encode($row[6])
			);
			$jsonData['rows'][] = $entry;
		}
        }
		$this->view->data = json_encode($jsonData);
    }

}


