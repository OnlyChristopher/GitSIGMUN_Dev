<?php

class LicenexpedienteController extends Zend_Controller_Action
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


    $this->view->title = "B&uacute;squeda de Solicitudes";

    $fn = new Libreria_Pintar();

    $evt[] = array('#contentBox',"tabs","");
    $evt[] = array('#btnGenerar',"click","goToFormulario('frmlicenexpediente');");
    $evt[] = array('#btnSalirG',"click","closePopup('#popGenerarExp');");

    $idSolLice = $this->_request->getParam('idSolLice','');
    $this->view->idSolLice=$idSolLice;

    $ColSol = $this->_request->getParam('ColSol','');
    $this->view->ColSol=$ColSol;

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

    }

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

    $evt[] = array('#btnSalir',"click","closePopup('#popGenerarExp');");

    $fn->PintarEvento($evt);
    $fn->PintarValor($val);


}
    public function expedienteAction()
    {
        $path = new Zend_Session_Namespace('path');
        $this->view->ruta = $path->data;
        $cn = new Model_DbDatos_Datos();
        $fn = new Libreria_Pintar();

        $evt[] = array('#contentBox',"tabs","");
        $evt[] = array('#btnGenerarExpiente',"click","goToFormulario('frmlicengeneraexpediente');");
        $evt[] = array('#btnSalirExp',"click","closePopup('#popGenerarExpediente');");

        $idSolLice = $this->_request->getParam('idSolLice','');
        $this->view->idSolLice=$idSolLice;
        $ColSol = $this->_request->getParam('ColSol','');
        $this->view->ColSol=$ColSol;

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
        }

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

        $evt[] = array('#btnSalir',"click","closePopup('#popGenerarExp');");

        $fn->PintarEvento($evt);
        $fn->PintarValor($val);
    }

	public function consultaAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $nro_expediente = $this->_request->getPost('nro_expediente');
        if (strlen(ltrim($nro_expediente)) == ''){
            $nro_expediente  = 'xx';
        }else{
            $nro_expediente = $this->_request->getPost('nro_expediente');
        }
        if($this->getRequest()->isXmlHttpRequest()){
            $cn = new Model_DbDatos_Datos();
            $parametrosE[] = array('@mssql',7);
            $parametrosE[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosE[] = array('@nro_expediente',$nro_expediente);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosE);
            if(count($rows))
                echo '1';
            else
                echo '';
        }
    }

    public function consultareciboAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $idrecibo = $this->_request->getPost('idrecibo');
        if (strlen(ltrim($idrecibo)) == ''){
            $idrecibo  = 'xx';
        }else{
            $idrecibo = $this->_request->getPost('idrecibo');
        }

        if($this->getRequest()->isXmlHttpRequest()){
            $cn = new Model_DbDatos_Datos();
            $parametrosE[] = array('@mssql',5);
            $parametrosE[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosE[] = array('@idrecibo',$idrecibo);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosE);
            if(count($rows))
                echo '1';
            else
                echo '';
        }
    }
    public function consultaexpedientereciboAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $nro_expediente = $this->_request->getPost('nro_expediente');

        if (strlen(ltrim($nro_expediente)) == ''){
            $nro_expediente  = 'xx';
        }else{
            $nro_expediente = $this->_request->getPost('nro_expediente');
        }
        if($this->getRequest()->isXmlHttpRequest()){
            $cn = new Model_DbDatos_Datos();
            $parametrosEr[] = array('@mssql',8);
            $parametrosEr[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosEr[] = array('@nro_expediente',$nro_expediente);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosEr);
            if(count($rows))
                echo '1';
            else
                echo '';
        }
    }
    public function grabarAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $idLicencia = $this->_request->getPost('idSolLice');

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametrosL[] = array('@mssql',1);
            $parametrosL[] = array('@idSolLice',$idLicencia);
            $parametrosL[] = array('@nro_licencia',$this->_request->getPost('txtcertL'));
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosL);
            echo "Update ";

            $parametrosO[] = array('@mssql',2);
            $parametrosO[] = array('@idSolLice',$idLicencia);
            $parametrosO[] = array('@nro',$this->_request->getPost('txtdcL'));

            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosO);
            echo "Insert ";

        }
    }
    public function generaexpedienteAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $idLicencia = $this->_request->getPost('idSolLice');
        $colSol = $this->_request->getPost('ColSol');

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametros[] = array('@mssql',1);
            $parametros[] = array('@idSolLice',$idLicencia);
            $parametros[] = array('@ColSol',$colSol);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenTramiteWeb', $parametros);

            $parametrosExp[] = array('@mssql',2);
            $parametrosExp[] = array('@idSolLice',$idLicencia);
            $parametrosExp[] = array('@ColSol',$colSol);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenTramiteWeb', $parametrosExp);
            echo "Registro Expediente";
        }
    }

        public function verificarAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        $nro_licencia = $this->_request->getPost('nro_licencia');
       if (strlen(ltrim($nro_licencia)) == ''){
           $nro_licencia  = 'xx';
        }else{
           $nro_licencia = $this->_request->getPost('nro_licencia');
        }

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametrosL[] = array('@mssql',3);
            $parametrosL[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosL[] = array('@nro_licencia',$nro_licencia);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosL);
            if(count($rows))
                echo '1';
            else
                echo '';


        }
     }


}





