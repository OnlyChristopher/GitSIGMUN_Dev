<?php

class LicenresolucionController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

        $getlogin = new Zend_Session_Namespace('login');
        $area = $getlogin->narea;
        $username= $getlogin->name;
        $encargado = $getlogin->encargado;

        $cn = new Model_DbDatos_Datos();

		$fn = new Libreria_Pintar();

        $evt[] = array('#txtfechresol',"datepicker","");
       // $evt[] = array('#txtfechmemo',"datepicker","");
        // $evt[] = array('#btnGenerarInf',"click","goToFormulario('frmliceninforme');");
        // $evt[] = array('#btnSalirInf',"click","closePopup('#popGenerarResol');");
		
		$evt[] = array('#btnGenerarResol3',"click","goToFormulario('frmresolucion');");
        $evt[] = array('#btnSalirResol2',"click","closePopup('#popGenerarResolucion');");
		$evt[] = array('#btnImprimiresol',"click","ImprimirResolucion();");

        $idSolLice = $this->_request->getParam('idSolLice','');
        $this->view->idSolLice2=$idSolLice;

        $ColSol = $this->_request->getParam('ColSol','');
        $this->view->ColSol2=$ColSol;

        unset($parametros);
        if(strlen($idSolLice)>0)
        {
            $parametros[] = array('@idSolLice',$idSolLice);
            $rowLicenciaF = $cn->ejec_store_procedura_sql('wbSpLicenciaFinal', $parametros);

            $fechaL = $rowLicenciaF[0][0];
            $certiL = $rowLicenciaF[0][1];
            $zonifL = $rowLicenciaF[0][2];
            $expedL = $rowLicenciaF[0][3];
            $vigeL = $rowLicenciaF[0][4];
            $resoL = $rowLicenciaF[0][5];
            $ordeL = $rowLicenciaF[0][6];
            $razon = $rowLicenciaF[0][7];
            $ubicL = $rowLicenciaF[0][8];
            $giroL = $rowLicenciaF[0][9];
            $rucL =  $rowLicenciaF[0][10];
            $areaL = $rowLicenciaF[0][11];
            $tipol = $rowLicenciaF[0][13];
            $tipoc = $rowLicenciaF[0][14];
            $tipop = $rowLicenciaF[0][15];
			$tipoinforme = $rowLicenciaF[0][16];
			
			$idresolucion = $rowLicenciaF[0][17];
			$resolucion = $rowLicenciaF[0][18];
			$fecharesol = $rowLicenciaF[0][19];
			
			$fun[] = array('disableButton("#btnImprimiresol");');
			
			 if(strlen($idresolucion)>0)	
			{ 
				 $att[] = array("#txtresol","readonly",'true');
				 $fun[] = array('disableButton("#btnGenerarResol3");');
				 $fun[] = array('enableButton("#btnImprimiresol");');
			}
			

        }
						

        $val[] = array('#txtArea',$area,'html');
        $val[] = array('#txtEncargado',$encargado,'html');
        $val[] = array('#txtTecnico',$username,'html');
        $val[] = array('#txtfechL',$fechaL,'html');
        $val[] = array('#txtrazonL',$razon,'html');
        $val[] = array('#txtcertL',$certiL,'val');
        $val[] = array('#txtzonif',$zonifL,'html');
        $val[] = array('#txtexpL',$expedL,'html');

        $val[] = array('#txtvigenL',$vigeL,'html');
        //$val[] = array('#txtresoL',$resoL,'html');
        $val[] = array('#txtordeL',$ordeL,'html');

        $val[] = array('#txtubiL',$ubicL,'html');
        $val[] = array('#txtgiroL',$giroL,'html');
        $val[] = array('#txtrucL',$rucL,'html');
        $val[] = array('#txtareaL',$areaL,'html');

        $val[] = array('#TipoLice2',$tipol,'val');
        $val[] = array('#TipoCpto2',$tipoc,'val');
        $val[] = array('#TipoPers2',$tipop,'val');
		
		$val[] = array('#Tipoinforme',$tipoinforme,'val');
		$val[] = array('#idresolucion',$idresolucion,'val');
		
		$val[] = array('#txtresol',$resolucion,'val');
		$val[] = array('#txtfechresol',$fecharesol,'val');

        //$evt[] = array('#btnSalir',"click","closePopup('#popGenerarExp');");
        $fn->PintarEvento($evt);
        $fn->PintarValor($val);
		$fn->EjecutarFuncion($fun);
		if(count($att))
			$fn->AtributoComponente($att);

    }
	
	public function generarresolucionAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

        $getlogin = new Zend_Session_Namespace('login');
        $area = $getlogin->narea;
        $username= $getlogin->name;
        $encargado = $getlogin->encargado;

       	$fn = new Libreria_Pintar();
		
		
		$idSolLice = $this->_request->getParam('idSolLice','');
        $this->view->idSolLice=$idSolLice;

        $ColSol = $this->_request->getParam('ColSol','');
        $this->view->ColSol=$ColSol;
		
		$this->view->Defensa=CD;
		$this->view->Defen=SD;
		
		$evt[] = array('#btnSalirResolucion',"click","closePopup('#popGenerarResolucion');");
		$evt[] = array('#btnGenerarResol',"click","Generar_Resolucion()");
		$evt[] = array('#btnGenerarResol2',"click","Generar_Resolucion2()");
		
		
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
    public function grabarAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $getlogin = new Zend_Session_Namespace('login');
        $username= $getlogin->name;
        $encargado = $getlogin->encargado;




        $idLicencia = $this->_request->getPost('idSolLice2');

        if($this->getRequest()->isXmlHttpRequest()){
            $cn = new Model_DbDatos_Datos();
            $parametrosL[] = array('@mssql',1);
            $parametrosL[] = array('@idSolLice',$idLicencia);
            $parametrosL[] = array('@resol',$this->_request->getPost('txtresol'));
            $parametrosL[] = array('@fecharesol',$this->_request->getPost('txtfechresol'));
            $parametrosL[] = array('@idTipoLice',$this->_request->getPost('TipoLice2'));
            $parametrosL[] = array('@idCpto',$this->_request->getPost('TipoCpto2'));
            $parametrosL[] = array('@idTipoPer',$this->_request->getPost('TipoPers2'));
            $parametrosL[] = array('@tecnico',$username);
            $parametrosL[] = array('@encargo',$encargado);
            $parametrosL[] = array('@tiporesolucion',$this->_request->getPost('Tipoinforme'));
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenResolucion', $parametrosL);
            echo "Update ";
        }
    }

        public function verificarAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametrosL[] = array('@mssql',4);
            $parametrosL[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosL[] = array('@informe',$this->_request->getPost('nro_informe'));
            $parametrosL[] = array('@memo',$this->_request->getPost('nro_memo'));

            $rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosL);

            if(count($rows))
                echo '1';
            else
                echo '';


        }
     }


}






