<?php

class LicensolController extends Zend_Controller_Action
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

        $cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
        $fn = new Libreria_Pintar();

		$evt[] = array('#contentBox',"tabs","");

		$evt[] = array('#btnNueSol',"click","nuevoLicencia();");
		$evt[] = array('#btnBusLicen',"click","buscarContri()");
        $evt[] = array('#btnDetPa',"click","Genera_Recibo()");
        $evt[] = array('#btnDetLic',"click","Genera_Licencia();");
        $evt[] = array('#btnInforme',"click","Genera_Informe();");
        $evt[] = array('#btnResolucion',"click","Genera_Resolucion();");
        $evt[] = array('#btnImpCar',"click","Imprime_Carton()");
        $evt[] = array('#btnDetExp',"click","Genera_Expediente()");


        unset($parametros);
        $parametros[] = array('@msquery',16);
        $parametros[] = array('@idtipo','0001');
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbCpto',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($tip_lice)),'html');


        $fn->PintarEvento($evt);
        $fn->PintarValor($val);

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
        $criteriocmb = $_REQUEST['criteriocmb'];


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
        $parametros[] = array('@tipo',$criteriocmb);

		$rowsTotal = $cn->ejec_store_procedura_sql('wbSpPredio', $parametros);


		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msql',4);
		$parametros[] = array('@nombre',$nombre);
    	$parametros[] = array('@idSolLice',$nrolicen);
		$parametros[] = array('@cod_sol',$nroexpe);
        $parametros[] = array('@tipo',$criteriocmb);
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
                    'tipo' =>($row[4]),
                    'nom_esta'=>utf8_encode($row[5]),
                    'tipo_cpto'=>utf8_encode($row[6]),
					'dire_pred'=>utf8_encode($row[10]),
					'fech_ing'=>utf8_encode($row[11]),
                    'nro_expediente'=>utf8_encode($row[7]),
                    'resolucion'=>utf8_encode($row[8]),
                    'generado'=>utf8_decode($row[9]),
                    'año'=>utf8_encode($row[13]),
                    'area'=>utf8_encode($row[12])." m2",
                    'nro_informe'=>utf8_encode($row[14]),
                    'nro_memo'=>utf8_encode($row[15]),
                    'idrecibo'=>($row[16])
			);
			$jsonData['rows'][] = $entry;
		}
        }
		$this->view->data = json_encode($jsonData);
    }

}



