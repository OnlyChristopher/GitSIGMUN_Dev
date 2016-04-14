<?php

class MantpersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		
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
        $criterionombre = $_REQUEST['criterionombre'];
        $criteriopaterno = $_REQUEST['criteriopaterno'];
        $criteriomaterno = $_REQUEST['criteriomaterno'];
        $criteriorazon = $_REQUEST['criteriorazon'];
        $documento = $_REQUEST['documento'];

        //{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};

        /*switch($rdcriterio)
        {
            case 'C': $codigo = $criterio; break;
            case 'N': $nombre = $criterio; break;
            case 'D': $documento = $criterio; break;
            case 'R': $documento = $criterio; break;
        }*/

        //Para el total
        $parametros[] = array('@busc',6);
        $parametros[] = array('@codigo',$criterio);
        $parametros[] = array('@nombres',$criterionombre);
        $parametros[] = array('@paterno',$criteriopaterno);
        $parametros[] = array('@materno',$criteriomaterno);
        $parametros[] = array('@razon',$criteriorazon);
        $parametros[] = array('@num_doc',$documento);
        $parametros[] = array('@tipo_busqueda',$rdcriterio );

        $rowsTotal = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);

        //Para las filas
        unset($parametros);
        $parametros[] = array('@busc',5);
        $parametros[] = array('@codigo',$criterio);
        $parametros[] = array('@nombres',$criterionombre);
        $parametros[] = array('@paterno',$criteriopaterno);
        $parametros[] = array('@materno',$criteriomaterno);
        $parametros[] = array('@razon',$criteriorazon);
        $parametros[] = array('@num_doc',$documento);
        $parametros[] = array('@tipo_busqueda',$rdcriterio );
        $parametros[] = array('@inicio',$start);
        $parametros[] = array('@final',$end);


        $rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mcontribuyente', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'tipo_doc'=>utf8_decode($row[25]),
						'documento'=>utf8_encode($row[3]),
						'direccion'=>utf8_encode($row[27]),
						'distrito'=>utf8_encode($row[26])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
    
	public function buscarAction()
    {    	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;		
		
		$fn = new Libreria_Pintar();		
		
		$evt[] = array('#btnBusPers',"button","");
		$evt[] = array('#btnNuevaPers',"button","");
		
		$evt[] = array('#btnBusPers',"click","buscarPers()");
		$evt[] = array('#btnNuevaPers',"click","showPopup('mantcontri/formu','#popcontri','800','700','Nuevo Contribuyente');");
		$evt[] = array('#btnCerrarPers',"click","closePopup('#popBusPersSol')");
				
		$fn->PintarEvento($evt);
    }
       
}

