<?php

class LiceninformeController extends Zend_Controller_Action
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
		
		$ar = new Libreria_ArraysFunctions();

        $evt[] = array('#txtfechinf',"datepicker","");
        $evt[] = array('#txtfechmemo',"datepicker","");
        // $evt[] = array('#btnGenerarInf',"click","goToFormulario('frmliceninforme');");
        // $evt[] = array('#btnSalirInf',"click","closePopup('#popGenerarInfor');");
		
		$evt[] = array('#btnGenerarInf3',"click","goToFormulario('frmliceninforme');");
        $evt[] = array('#btnSalirInf2',"click","closePopup('#popGenInforme');");
		
		$evt[] = array('#btnImprimir',"click","ImprimirInforme();");
		
		//$evt[] = array('#btnGenerarInf3',"click","Generando()");

        $idSolLice = $this->_request->getParam('idSolLice','');
        $this->view->idSolLice2=$idSolLice;

        $ColSol = $this->_request->getParam('ColSol','');
        $this->view->ColSol2=$ColSol;
		
		$ConDefensa = $this->_request->getParam('defensa','');
        $this->view->defensas=$ConDefensa;
		
		$Idinforme = $this->_request->getParam('informe','');
        $this->view->informeid=$Idinforme;
		
		
		unset($parametros); 
        $parametros[] = array('@msquery',18);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbSexo',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',''),'html');
		
		unset($parametros); 
        $parametros[] = array('@msquery',17);
        $documentos = $cn->ejec_store_procedura_sql('WbSpLicenciaCombos', $parametros);
        $arDocumentos = $ar->RegistrosCombo($documentos,0,1);
        $val[] = array('#cmbCargo',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',''),'html');

        unset($parametros);
        if(strlen($idSolLice)>0  && empty($Idinforme) )
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
			
			$fun[] = array('disableButton("#btnImprimir");');

        }
		
		 else{
			
			 $arraydatos[] = array('@msquery',2);
			 $arraydatos[] = array('@id',$Idinforme);
             $rowInforme = $cn->ejec_store_procedura_sql('wbSpInformes', $arraydatos);
			
			  $informe = $rowInforme[0][9];
			  $fechainforme = $rowInforme[0][10];
			  $acronimo = $rowInforme[0][15];			 
			  $cargo = $rowInforme[0][16];
			  $memorando = $rowInforme[0][12];
			  $fechamemo = $rowInforme[0][13];
			  $observancia = $rowInforme[0][14];
			  $certitecnico = $rowInforme[0][17];
			  $tipol = $rowInforme[0][18];
			  
			  $razon = $rowInforme[0][3];
			  $vigeL = $rowInforme[0][1];
			  $zonifL = $rowInforme[0][0];
			  $ordeL = $rowInforme[0][2];
			  $ubicL = $rowInforme[0][4];
			  $giroL = $rowInforme[0][5];
			  $rucL = $rowInforme[0][6];
			  $areaL = $rowInforme[0][7];
			  $tipop = $rowInforme[0][8];
			  
			  $att[] = array("#txtinfor","readonly",'true');
			  $att[] = array("#txtdj","readonly",'true');
			  $att[] = array("#cmbSexo","disabled",'true');
			  $att[] = array("#cmbCargo","disabled",'true');
			  $att[] = array("#txtmemo","readonly",'true');		
			  $att[] = array("#txtcertificado","readonly",'true');			  
			  $fun[] = array('disableButton("#btnGenerarInf3");');
   
		 }

		$val[] = array('#txtinfor',$informe,'val');
		$val[] = array('#cmbSexo',$acronimo,'val');
		$val[] = array('#cmbCargo',$cargo,'val');
		$val[] = array('#txtmemo',$memorando,'val');
		$val[] = array('#txtfechinf',$fechainforme,'val');
		$val[] = array('#txtfechmemo',$fechamemo,'val');
		$val[] = array('#txtdj',$observancia,'val');
		$val[] = array('#txtcertificado',$certitecnico,'val');
		
        $val[] = array('#txtArea',$area,'html');
        $val[] = array('#txtEncargado',$encargado,'html');
        $val[] = array('#txtTecnico',$username,'html');
        $val[] = array('#txtfechL',$fechaL,'html');
        $val[] = array('#txtrazonL',$razon,'html');
        $val[] = array('#txtcertL',$certiL,'val');
        $val[] = array('#txtzonif',$zonifL,'html');
        $val[] = array('#txtexpL',$expedL,'html');

        $val[] = array('#txtvigenL',$vigeL,'html');
        $val[] = array('#txtresoL',$resoL,'html');
        $val[] = array('#txtordeL',$ordeL,'html');

        $val[] = array('#txtubiL',$ubicL,'html');
        $val[] = array('#txtgiroL',$giroL,'html');
        $val[] = array('#txtrucL',$rucL,'html');
        $val[] = array('#txtareaL',$areaL,'html');

        $val[] = array('#TipoLice2',$tipol,'val');
        $val[] = array('#TipoCpto2',$tipoc,'val');
        $val[] = array('#TipoPers2',$tipop,'val');
		
		

       
        $fn->PintarEvento($evt);
        $fn->PintarValor($val);
		$fn->EjecutarFuncion($fun);
		if(count($att))
			$fn->AtributoComponente($att);

		
		
		
    }
	

	public function generarinformeAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

        $getlogin = new Zend_Session_Namespace('login');
        $area = $getlogin->narea;
        $username= $getlogin->name;
        $encargado = $getlogin->encargado;

       	$fn = new Libreria_Pintar();
		
		$cn = new Model_DbDatos_Datos();
		
		$idSolLice = $this->_request->getParam('idSolLice','');
        $this->view->idSolLice=$idSolLice;

        $ColSol = $this->_request->getParam('ColSol','');
        $this->view->ColSol=$ColSol;
		
		$this->view->Defensa=CD;
		$this->view->Defen=SD;
		
		//unset($parametros);
	    $parametros[] = array('@msquery',1);
		$parametros[] = array('@idSolLice',$idSolLice);
		$rowInformes = $cn->ejec_store_procedura_sql('wbSpInformes', $parametros);		
		$id = $rowInformes[0][0];
		$tipoinforme = $rowInformes[0][15];
	
		 if($tipoinforme =='CD')
		 {
			$fun[] = array('disableButton("#btnGenerarInfor2");');
            $fn->EjecutarFuncion($fun);
		 }
		 elseif($tipoinforme =='SD')
		 {
		   $fun[] = array('disableButton("#btnGenerarInfor");');
           $fn->EjecutarFuncion($fun);
         }
		
		
		$evt[] = array('#btnSalirInfor',"click","closePopup('#popGenerarInforme');");
		$evt[] = array('#btnGenerarInfor',"click","Generar_Informe()");
		$evt[] = array('#btnGenerarInfor2',"click","Generar_Informe2()");

        $val[] = array('#informeid',$id,'val');


		
		
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
            $parametrosL[] = array('@informe',$this->_request->getPost('txtinfor'));
            $parametrosL[] = array('@fechainf',$this->_request->getPost('txtfechinf'));
            $parametrosL[] = array('@idTipoLice',$this->_request->getPost('TipoLice2'));
            $parametrosL[] = array('@idCpto',$this->_request->getPost('TipoCpto2'));
            $parametrosL[] = array('@idTipoPer',$this->_request->getPost('TipoPers2'));
            $parametrosL[] = array('@tecnico',$username);
            $parametrosL[] = array('@encargo',$encargado);
            $parametrosL[] = array('@memo',$this->_request->getPost('txtmemo'));
            $parametrosL[] = array('@fechamem',$this->_request->getPost('txtfechmemo'));
			$parametrosL[] = array('@acronimo',$this->_request->getPost('cmbSexo'));
			$parametrosL[] = array('@cargo',$this->_request->getPost('cmbCargo'));
			$parametrosL[] = array('@observancia',$this->_request->getPost('txtdj'));
			$parametrosL[] = array('@certi_tecnico',$this->_request->getPost('txtcertificado'));
			$parametrosL[] = array('@tipoinforme',$this->_request->getPost('defensas'));
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenInformes', $parametrosL);
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






