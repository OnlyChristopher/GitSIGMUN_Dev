<?php

class LicenreciboController extends Zend_Controller_Action
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


        $this->view->title = "B&uacute;squeda de Solicitudes";

        $fn = new Libreria_Pintar();

        $evt[] = array('#txtfechinf',"datepicker","");
        $evt[] = array('#txtfechmemo',"datepicker","");
        $evt[] = array('#btnGenRecibo',"click","Generar();");
        $evt[] = array('#btnSalirGR',"click","closePopup('#popGenerarRecibo');");

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
            $metros = $rowLicenciaF[0][12];

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
        $val[] = array('#txtresoL',$resoL,'html');
        $val[] = array('#txtordeL',$ordeL,'html');

        $val[] = array('#txtubiL',$ubicL,'html');
        $val[] = array('#txtgiroL',$giroL,'html');
        $val[] = array('#txtrucL',$rucL,'html');
        $val[] = array('#txtareaL',$areaL,'html');
        $val[] = array('#metro',$metros,'val');


        $evt[] = array('#btnSalir',"click","closePopup('#popGenerarExp');");
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


        $idLicencia = $this->_request->getPost('idSolLice');
        $metros = $this->_request->getPost('metro');

        if($this->getRequest()->isXmlHttpRequest()){
            $cn = new Model_DbDatos_Datos();
            $parametrosL[] = array('@buscar',1);
            $parametrosL[] = array('@idSolLice',$idLicencia);
            $parametrosL[] = array('@codigo',$this->_request->getPost('ColSol'));
            $parametrosL[] = array('@tipo','44.73');
            $parametrosL[] = array('@tipo_rec','44.73');
            $parametrosL[] = array('@metro',(int)$metros);
            @$rows = $cn->ejec_store_procedura_sql('wbSpGenRecibo', $parametrosL);
            echo "Genero ";
        }
    }

    public function verificarAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametrosV[] = array('@mssql',5);
            $parametrosV[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosV[] = array('@idrecibo',$this->_request->getPost('idrecibo'));
            $rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosV);

            if(count($rows))
                echo '1';
            else
                echo '';


        }
    }

    public function verificarpagoAction()
    {
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if($this->getRequest()->isXmlHttpRequest()){

            $cn = new Model_DbDatos_Datos();

            $parametrosP[] = array('@mssql',6);
            $parametrosP[] = array('@idSolLice',$this->_request->getPost('idSolLice'));
            $parametrosP[] = array('@idrecibo',$this->_request->getPost('idrecibo'));
            $rows = $cn->ejec_store_procedura_sql('wbSpGenLicencia', $parametrosP);
            if(count($rows))
                echo '1';
            else
                echo '';


        }
    }


}





