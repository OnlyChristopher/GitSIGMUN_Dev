<?php

class MantcabeobserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $path = new Zend_Session_Namespace('path');
        $this->view->ruta = $path->data;

        $mod = $this->_request->getParam('mod','');
        $this->view->mod = $mod;

        $this->view->title = "Certificados de Condiciones de Seguridad";

        $fn = new Libreria_Pintar();

        $evt[] = array('#contentBox',"tabs","");

        //$evt[] = array('#btnSearchObser',"click","buscarObser()");
        //$evt[] = array('#btnGrabarObser',"click","showPopup('mantobser/formu','#popNObser','600','450','Nueva Observación');");
        $evt[] = array('#btnNueSol',"click","nuevoCertificado();");


        $fn->PintarEvento($evt);

    }

    public function consultaAction()
    {
        $cn = new Model_DbDatos_Datos();

        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 19;

        $start = (($page-1) * $limit)+1;
        $end = $start + $limit - 1;

       //Para el total
        $parametros[] = array('@busc',6);

        $rowsTotal = $cn->ejec_store_procedura_sql('WbSpObsDC', $parametros);


        //Para las filas
        unset($parametros);
        $parametros[] = array('@busc',5);
        $parametros[] = array('@inicio',$start);
        $parametros[] = array('@final',$end);

        $rows = $cn->ejec_store_procedura_sql('WbSpCabeObsDC', $parametros);

        $jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
            foreach($rows AS $row){
                $entry = array(
                    'nroCerti'=>$row[0],
                    'anno'=>$row[1],
                    'fecdesde'=>$row[2],
                    'fechasta'=>$row[3]

                );
                $jsonData['rows'][] = $entry;
            }
        }
        $this->view->data = json_encode($jsonData);
    }



}


