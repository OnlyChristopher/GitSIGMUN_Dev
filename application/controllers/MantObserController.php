<?php

class MantObserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        $cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
        $fn = new Libreria_Pintar();

        $path = new Zend_Session_Namespace('path');
        $this->view->ruta = $path->data;

        $mod = $this->_request->getParam('mod','');
        $this->view->mod = $mod;

        $this->view->title = "Observaciones";

        $fn = new Libreria_Pintar();
        $evt[] = array('#txtFchDes',"datepicker","");
        $evt[] = array('#txtFchHas',"datepicker","");
        $evt[] = array('#contentBox',"tabs","");
        $evt[] = array('#btnGrabar',"click","goToFormulario('frmdetalleobser');");
        $evt[] = array('#btnSalir',"click","closePopup('#popupcertificado');");

        //$evt[] = array('#btnSearchObser',"click","buscarObser()");
        //$evt[] = array('#btnGrabarObser',"click","showPopup('mantobser/formu','#popNObser','600','450','Nueva Observación');");
        $nroCerti = $this->_request->getParam('nroCerti','');
        $this->view->nroCerti=$nroCerti;


             $readonly = $this->_request->getParam('readonly','');
        $this->view->readonly=$readonly;

        $accion = $this->_request->getParam('accion','');
        $this->view->accion=$accion;

        if(strlen($nroCerti)>0)
        {
            $parametros[] = array('@busc',4);
            $parametros[] = array('@nro',$nroCerti);
            $rowLicencia = $cn->ejec_store_procedura_sql('WbSpCabeObsDC', $parametros);

            $nro = $rowLicencia[0][0];
            $fechades = $rowLicencia[0][2];
            $fechahas = $rowLicencia[0][3];
        }
        $val[] = array('#txtnrocer',$nro,'val');
        $val[] = array('#txtFchDes',$fechades,'val');
        $val[] = array('#txtFchHas',$fechahas,'val');


        $fn->PintarEvento($evt);
        $fn->PintarValor($val);

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

        $rows = $cn->ejec_store_procedura_sql('WbSpObsDC', $parametros);

        $jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
            foreach($rows AS $row){
                $entry = array(
                    'idObs'=>$row[0],
                    'descObs'=>utf8_encode($row[1])

                );
                $jsonData['rows'][] = $entry;
            }
        }
        $this->view->data = json_encode($jsonData);
    }
    public function grabarAction() {
        $this->_helper->getHelper ( 'ajaxContext' )->initContext ();
        $this->_helper->viewRenderer->setNoRender ();
        $this->_helper->layout->disableLayout ();
        if ($this->getRequest ()->isXmlHttpRequest ()) {

            $cn = new Model_DbDatos_Datos ();
            $ar = new Libreria_ArraysFunctions();
            $login = new Zend_Session_Namespace('login');

            $json = $this->_request->getPost('json');
            $data = json_decode($json);

            $nroCerti = $data->txtnrocer;
            $fechaDes = $data->txtFchDes;
            $fechaHas = $data->txtFchHas;

            $ano = substr($fechaDes,6,4);

            $arraydatos[] = array("@busc", 1);
            $arraydatos[] = array("@nro",$nroCerti);
            $arraydatos[] = array("@ano",$ano);
            $arraydatos[] = array("@fecdesde",$fechaDes);
            $arraydatos[] = array("@fechasta",$fechaHas);
            $rows = $cn->ejec_store_procedura_sql('WbSpCabeObsDC', $arraydatos);


            if(!$flag){
                if($action=='E'){
                    //actualiza
                    unset($arraydatos);
                    $arraydatos[] = array("@busc", 4);

                    $arraydatos[] = array("@cencos",$cencos);
                    $arraydatos[] = array("@subcencos",$subcencos);
                    $arraydatos[] = array("@meta",$meta);

                    $arraydatos[] = array("@req_num_old",$req_num_old);
                    $arraydatos[] = array("@req_anno_old",$req_anno_old);
                    $arraydatos[] = array("@depen_cod_old",$depen_cod_old);

                    $arraydatos[] = array("@req_num",$req_num);
                    $arraydatos[] = array("@req_anno",$req_anno);
                    $arraydatos[] = array("@depen_cod",$depen_cod);
                    $arraydatos[] = array("@usu_cod",$login->user);
                    $arraydatos[] = array("@req_tipo",$req_tipo);
                    $arraydatos[] = array("@req_fecha",$ar->toDate($req_fecha));
                    $arraydatos[] = array("@req_glosa",$req_glosa);
                    $arraydatos[] = array("@req_est",$req_est);
                    $arraydatos[] = array("@req_snip",$req_snip);

                    $arraydatos[] = array("@fechact",date('d/m/y H:i:s'));
                    $arraydatos[] = array("@usuario",$login->user);

                    $rows = $cn->ejec_store_procedura_sql('pxCabRequerim', $arraydatos);
                }else{
                    //ingresa
                    unset($arraydatos);
                    $arraydatos[] = array("@busc", 3);

                    $arraydatos[] = array("@cencos",$cencos);
                    $arraydatos[] = array("@subcencos",$subcencos);
                    $arraydatos[] = array("@meta",$meta);

                    $arraydatos[] = array("@req_anno",$req_anno);
                    $arraydatos[] = array("@depen_cod",$depen_cod);
                    $arraydatos[] = array("@usu_cod",$login->user);
                    $arraydatos[] = array("@req_tipo",$req_tipo);
                    $arraydatos[] = array("@req_fecha",$req_fecha);
                    $arraydatos[] = array("@req_glosa",$req_glosa);
                    $arraydatos[] = array("@req_est",'E');
                    $arraydatos[] = array("@req_snip",$req_snip);

                    $arraydatos[] = array("@fechins",date('d/m/y H:i:s'));
                    $arraydatos[] = array("@usuario",$login->user);

                    $rows = $cn->ejec_store_procedura_sql('pxCabRequerim', $arraydatos);

                    $req_num = $rows[0][0];
                }

                if(count($rows)){
                    //Guardamos grid detalle
                    //$oldDetalle = explode('|',$data->oldDetItems);
                    $dataDetalle = $data->Detalle;

                    //Insertamos o actualizamos
                    if(count($dataDetalle)){
                        foreach($dataDetalle as $rowDetalle){
                            $det_req_num = $rowDetalle->det_id;

                            if(strlen($det_req_num)>0){
                                //Estamos actualizando
                                unset($arraydatos);
                                $arraydatos[] = array("@busc", 3);
                                $arraydatos[] = array("@det_req_num",$det_req_num);
                                $arraydatos[] = array("@req_num",$req_num);
                                $arraydatos[] = array("@req_anno",$req_anno);
                                $arraydatos[] = array("@depen_cod",$depen_cod);
                                $arraydatos[] = array("@cod_bien_serv",$rowDetalle->det_codigo);
                                $arraydatos[] = array("@cantidad",$rowDetalle->det_cant);
                                $arraydatos[] = array("@fechact",date('d/m/y H:i:s'));
                                $arraydatos[] = array("@usuario",$login->user);

                                $rowEditDetalle = $cn->ejec_store_procedura_sql('pxDetRequerim', $arraydatos);

                            }
                            else{
                                //Estamos insertando
                                unset($arraydatos);
                                $arraydatos[] = array("@busc", 2);
                                $arraydatos[] = array("@req_num",$req_num);
                                $arraydatos[] = array("@req_anno",$req_anno);
                                $arraydatos[] = array("@depen_cod",$depen_cod);
                                $arraydatos[] = array("@cod_bien_serv",$rowDetalle->det_codigo);
                                $arraydatos[] = array("@cantidad",$rowDetalle->det_cant);
                                $arraydatos[] = array("@fechins",date('d/m/y H:i:s'));
                                $arraydatos[] = array("@usuario",$login->user);

                                $rowInsDetalle = $cn->ejec_store_procedura_sql('pxDetRequerim', $arraydatos);
                            }
                        }
                    }
                }

                //Retorna datos insertados o actualizados
                echo $req_num."|".$req_anno."|".$depen_cod;
            }
            else
                echo 'existe';
        }
    }

	public function cargachecksAction(){		
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
	    		
			$cn = new Model_DbDatos_Datos();

            $nroCerti = $this->_request->getPost('nroCerti');


			
			$arraydatos[] = array("@msql",3);
			$arraydatos[] = array("@nroCerti",$nroCerti);
			$rows = $cn->ejec_store_procedura_sql('WbSpDetaObsDC',$arraydatos);
			
			$cadena = "";
			
			foreach($rows AS $row){
				if($row[3])
					$cadena .= $row[2]."|";
			}
			
			echo $cadena;
	    }
	}



}

