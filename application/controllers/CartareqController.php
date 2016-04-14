<?php 

class CartareqController extends Zend_Controller_Action
{
	

	public function indexAction(){

		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$this->view->title = "Lista de contribuyentes";

		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();

		$evt[] = array('#btnSearchContribuyente',"click","buscarContribuyente()");
		$evt[] = array('#contentBox',"tabs","");		
		$evt[] = array('#btnNewContribuyente',"click","showPopup('contribuyente/formu','#popupcontribuyente','800','451','Nuevo contribuyente');"); 			
		
		$fn->PintarEvento($evt);		

	}

	public function consultacontribAction(){
    	$cn = new Model_DbDatos_Datos();
        
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
        
        $buscarPor = $this->_request->getParam('buscarPor','');
        $valorBuscar = $this->_request->getParam('valor','');


        $start = (($page-1) * $limit)+1;
        $end = $start + $limit - 1;     


        //Para el total
        $parametros[] = array('@mquery',11);
        if (!empty($buscarPor) && $buscarPor=='C') { $parametros[] = array('@codigo',trim($valorBuscar)); } 
        if (!empty($buscarPor) && $buscarPor=='CO') { $parametros[] = array('@nomCompletoContrib',trim($valorBuscar)); } 
       
        $rowsTotal = $cn->ejec_store_procedura_sql('[SP_FISCA_CONTRIBUYENTE]', $parametros);


        //Para las filas
        unset($parametros);
        $parametros[] = array('@mquery',10);
        $parametros[] = array('@inicio',$start);
        $parametros[] = array('@final',$end);
        if (!empty($buscarPor) && $buscarPor=='C') { $parametros[] = array('@codigo',trim($valorBuscar)); } 
        if (!empty($buscarPor) && $buscarPor=='CO') { $parametros[] = array('@nomCompletoContrib',trim($valorBuscar)); } 
       


        $rows = $cn->ejec_store_procedura_sql('[SP_FISCA_CONTRIBUYENTE]', $parametros);
       

        $jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
            foreach($rows AS $row){
                $entry = array(

                        'id'=>$row[0],                
                        'nombre'=>utf8_encode($row[1]),
                        'direccion'=>utf8_encode($row[2])
                        );

                $jsonData['rows'][] = $entry;
            }
        }
        $this->view->data = json_encode($jsonData);
    	
    }

    public function formulistarcartasreqAction(){
    	
    	$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();

    	$codigo = $this->_request->getParam('codigo','');


    	if (!empty($codigo)) {
    		 
    		unset($parametros);
    		$parametros[] = array('@mquery',12);
			$parametros[] = array('@codigo',$codigo);
			$rowUsu = $cn->ejec_store_procedura_sql('[SP_FISCA_CONTRIBUYENTE]', $parametros);
			
			

    		if (count($rowUsu)>0) { // si ejecuto correctamente
    			
    			$row = $rowUsu[0];

    			$nombres = strtoupper($row[1]);    			
    			$app = strtoupper($row[2]);
    			$apm = strtoupper($row[3]);
    			$nomCompleto = trim($app." ".$apm." ".$nombres);
    			
    			$this->view->contribuyente = $nomCompleto;
    			$this->view->codContrib = $codigo;
    			$val[] = array('#codContrib',$codigo,'val');

    		}else{
				echo utf8_encode($rowUsu[1]);
				exit();
			}
		}

    			
		$evt[] = array('#contentBox3',"tabs","");				
		$evt[] = array('#btnNewFisca',"click","showPopup('cartareq/formu?codContrib=".$codigo."','#popupcartareq','840','570','Nueva - CARTA DE PRESENTACION Y REQUERIMIENTO DE DOCUMENTACION');");
        $evt[] = array('#btnVerActas',"click","detalleRequerimiento();");

		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }

    public function consultacartasreqAction(){
    	$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
        
        $codigo = $this->_request->getParam('codigo','');

        if(!empty($codigo)){

            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1; //2
            $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0; //10
            $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10; //10
            
            $start = (($page-1) * $limit)+1; //11
            $end = $start + $limit - 1; //20
            
            //Para el total
            $parametros[] = array('@mquery',5); 
            $parametros[] = array('@codContrib',$codigo);
            $rowsTotal = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);
            
            unset($parametros);
            $parametros[] = array('@mquery',4);
            $parametros[] = array('@codContrib',$codigo);
            $parametros[] = array('@inicio',$start);
            $parametros[] = array('@final',$end);
            
            $rows = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);
            
            $jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());;
            if(count($rows))
            {   

                foreach($rows AS $row){
                    $entry = array(                  
                        'idCarta' =>$row[0],
                        'nroCarta' =>$row[1],
                        'anio'=>$row[2],                    
                        'fecInspec'=>$row[3]."/".$row[4]."/".$row[5],
                        'motivo'=>$row[6]           
                    );
                    $jsonData['rows'][] = $entry;
                }
            }
        }
        
        $this->view->data = json_encode($jsonData);
    }

    public function formuAction(){

        $cn = new Model_DbDatos_Datos();
        $fn = new Libreria_Pintar();
        $ar = new Libreria_ArraysFunctions();

        $codContrib = $this->_request->getParam('codContrib','');
        $codCarta = $this->_request->getParam('codCarta','');
            
        //cargando el combo periodos            
        unset($parametros);
        $parametros[] = array('@mquery',7);
        $rowsPeriodos = $cn->ejec_store_procedura_sql('SP_FISCA_T_ANIO', $parametros);
        $periodosCombo = $ar->RegistrosComboc($rowsPeriodos,0,1,'');
        $val[] = array('#cbAnioDesde',$fn->ContenidoCombo($periodosCombo,'[Seleccione]',''),'html');
        

        //cargando el combo motivos
        unset($parametros);
        $parametros[] = array('@mquery',7);
        $rowsMotivos = $cn->ejec_store_procedura_sql('[SP_FISCA_MOTIVO]',$parametros);
        $motivosCombo = $ar->RegistrosComboc($rowsMotivos,0,1,'');
        $val[] = array('#cbMotivo',$fn->ContenidoCombo($motivosCombo,'[Seleccione]',''),'html');

        #nuevo
        if (empty($codCarta) && !empty($codContrib)) {

            #datos del contrib
            unset($parametros);
            $parametros[] = array('@mquery',12);
            $parametros[] = array('@codigo',$codContrib);
            $rowUsu = $cn->ejec_store_procedura_sql('[SP_FISCA_CONTRIBUYENTE]', $parametros);
            
            if (count($rowUsu)>0) { // si ejecuto correctamente
                
                $row = $rowUsu[0];
                $codigo = $row[0];
                $nombres = strtoupper($row[1]);             
                $app = strtoupper($row[2]);
                $apm = strtoupper($row[3]);
                $nomCompleto = trim($app." ".$apm." ".$nombres);
                $fechaActual = date("d/m/Y");

                $val[] = array('#txtCodigoContribuyente',$codigo,'val');
                $val[] = array("#txtContribuyente",$nomCompleto,'val');
                $val[] = array("#txtFecha",$fechaActual,'val');

            }else{
                echo utf8_encode($rowUsu[1]);
            }

            #max anio
            unset($parametros);
            $parametros[] = array('@mquery',8);
            $rowAnio = $cn->ejec_store_procedura_sql('[SP_FISCA_T_ANIO]', $parametros);
            $val[] = array("#dvAnio",$rowAnio[0][0],'html');

        }else{
            #editar
    
            unset($parametros);
            $parametros[] = array('@mquery',6);
            $parametros[] = array('@idCarta',$codCarta);
            $rowCARTA_REQ = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);
          
            if ($rowCARTA_REQ[0]) { // si ejecuto correctamente
                $row = $rowCARTA_REQ[0];
    
                $codCarta = $row[0];
                $nroCarta = $row[1]."-".$row[2];    
                
                $codContrib2 = $row[3];
                $nomCompleto = trim($row[5]." ".$row[6]." ".$row[4]);

                $fecInspec = $row[8]."/".$row[9]."/".$row[10];
                $horaInspec = $row[11];
                $codMotivo = $row[12];
                $fiscaAnioDesde = $row[13];
                $fiscaAnio = $row[14];

                
                $val[] = array('#hcodCarta',$codCarta,'val');
                $val[] = array('#txtNroCartaReq',$nroCarta,'val');
                $val[] = array('#txtCodigoContribuyente',$codContrib2,'val');
                $val[] = array('#txtContribuyente',$nomCompleto,'val');
                $val[] = array('#txtFecha',$fecInspec,'val');               
                $val[] = array('#txtHora',$horaInspec,'val');  
                $val[] = array('#cbMotivo',$codMotivo,'val');
                $val[] = array('#cbAnioDesde',$fiscaAnioDesde,'val');
                $val[] = array('#dvAnio',$fiscaAnio,'html');

                $evt[] = array('#btnActaVisita',"click","showPopup('actavisita/formu?codCarta=".$codCarta."','#popupactavisita','800','510','Editar - ACTA DE VISITA');");
                $evt[] = array('#btnNotificacionCedulon',"click","showPopup('cedulon/formu?codCarta=".$codCarta."','#popupcedulon','900','395','Editar - NOTIFICACION POR CEDULON');");
                $evt[] = array('#btnCargoNotificacion',"click","showPopup('cargonotifica/formu?codCarta=".$codCarta."','#popupnotificacion','900','470','Editar - CARGO DE NOTIFICACION');");               
                $evt[] = array('#btnImprimirCartaReq',"click","showPopupReport('schema=&tipo=pdf&nombrereporte=CartaReq&param=idCartaReq^'+'".$codCarta."','reporteCartaReq',700,600,'Reporte CartaReq');");
                                
            }else{
                echo utf8_encode($rowCARTA_REQ[1]);
               // exit();
            }

        }
        $evt[] = array('#contentBox2',"tabs","");               
        $evt[] = array('#tabs',"tabs","");

        $fn->PintarEvento($evt);
        $fn->PintarValor($val);
    	
	}

	public function listarprediosAction(){
    	$cn = new Model_DbDatos_Datos();
        $ar = new Libreria_ArraysFunctions();
        
        $codContrib = $this->_request->getParam('codContrib','');
        $anno = $this->_request->getParam('anio');
        $codCarta = $this->_request->getParam('codCarta','');
        
        $rows="";

                unset($parametros);
                $parametros[] = array('@mquery',10);           
                if (!empty($codCarta)) {
                    $parametros [] = array('@idCarta',$codCarta);
                }elseif (empty($codCarta) && !empty($codContrib)) {
                    $parametros[] = array('@codigo',$codContrib);
                    $parametros[] = array('@anno',$anno);
                }

                $rows = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);

                if(count($rows)){
                    foreach($rows AS $row){
                        $referencia = $row[11];
                        $referencia = str_replace("'", "''", $referencia);

                        $entry = array(
                            'cod_pred' =>trim($row['0']),
                            'anexo' =>trim($row['1']),
                            'sub_anexo' =>trim($row['2']),
                            'id_urba' =>trim(utf8_encode($row['3'])),
                            'id_via' =>trim(utf8_encode($row['4'])),
                            'num_manz' =>trim(utf8_encode($row['5'])),
                            'num_lote' =>trim(utf8_encode($row['6'])),
                            'sub_lote' =>trim(utf8_encode($row['7'])),
                            'num_call' =>trim(utf8_encode($row['8'])),
                            'num_depa' =>trim(utf8_encode($row['9'])),
                            'referenc' =>trim(utf8_encode($row['10'])),
                            'dirPredio' =>trim(utf8_encode($row['11'])),
                            'confirmado'=>$row['12'],
                            'nueva_dir'=>$row['13']
                        );
                        $jsonData[] = $entry;
                    }
                }       
       
            
        $this->view->data = json_encode($jsonData);

    }

    public function listarfiscalizadoresAction(){
    	
        $ar = new Libreria_ArraysFunctions();      
        $cn = new Model_DbDatos_Datos();
        $codCarta = $this->_request->getParam('codCarta','');
    
        //Nueva fiscalizacion
        if (empty($codCarta)) {
        
            //Para las filas
            unset($parametros);
            $parametros[] = array('@mquery',6);

            $rows = $cn->ejec_store_procedura_sql('[SP_FISCA_FISCALIZADORES]', $parametros);
            
            $jsonData = array();
            if(count($rows))
            {
                foreach($rows AS $row){
                    $entry = array(
                        'id'=>trim($row[0]),                 
                        'fiscalizador'=>strtoupper(trim(utf8_encode($row[1]))),
                        'seleccionado'=>0
                    );
                    $jsonData[] = $entry;
                }
            }
            
        }else{
            
            // editar fiscalizacion, muestra usuarios con usuarios seleccionados
                    $parametros[] = array('@mquery',11);
                    $parametros[] = array('@idCarta',$codCarta);
                    $rows = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);
                    
                    $jsonData = array();
                    if(count($rows))
                    {
                        foreach($rows AS $row){
                            $entry = array(
                                'id'=>trim($row[0]),
                                'fiscalizador' => trim(utf8_encode($row[1])),
                                'seleccionado' => (strtoupper($row[2])=="TRUE")? 1 : 0                 
                            );
                            $jsonData[] = $entry;
                        }
                    }
                  
        }

        $this->view->data = json_encode($jsonData);
    	
    }

    public function formupredioAction(){
        $cn = new Model_DbDatos_Datos();
        $fn = new Libreria_Pintar();
        $ar = new Libreria_ArraysFunctions();

        $anno = date("Y");
        $evt[] = array('#btnBusVia',"click","showPopup('cartareq/busvia?anno=".$anno."','#popupbusvia','700','300','Buscador Via');");
        $fn->PintarEvento($evt);
		
    }

    public function busviaAction(){

        $anno = $this->_request->getParam("anno");
        $fn = new Libreria_Pintar();
        $val[] = array("#hAnno",$anno,'val');
        
        $fn->PintarValor($val);
    }
    
    public function consultabusviaAction(){

        $cn = new Model_DbDatos_Datos();
        
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
        $limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
        $anno = $this->_request->getParam("anno");

        $start = (($page-1) * $limit)+1;
        $end = $start + $limit - 1;
        
        $criterio = trim($_REQUEST['criterio']);


        //Para el total
        $parametros[] = array('@msquery',3);
        $parametros[] = array('@anno',$anno);
        $parametros[] = array('@nombre_via',$criterio);
        
        
        $rowsTotal = $cn->ejec_store_procedura_sql('SP_FISCA_VIAS', $parametros);
        
        //Para las filas
        unset($parametros);
        $parametros[] = array('@msquery',2);
        $parametros[] = array('@nombre_via',$criterio);
        $parametros[] = array('@anno',$anno);
        $parametros[] = array('@inicio',$start);
        $parametros[] = array('@final',$end);
        
        
        $rows = $cn->ejec_store_procedura_sql('SP_FISCA_VIAS', $parametros);
        
        $jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
        if(count($rows))
        {
            foreach($rows AS $row){
                $entry = array(
                        'codigo'=>trim($row[0]),
                        'codzona'=>trim($row[1]),
                        'nomzona'=>utf8_encode($row[2]),
                        'codurba'=>trim($row[3]),
                        'nomurba'=>utf8_encode($row[4])." ".utf8_encode($row[5]),
                        'nomvia'=>utf8_encode($row[7])
                );
            
                $jsonData['rows'][] = $entry;
            }
        }
        $this->view->data = json_encode($jsonData);
    }

	public function grabarAction(){
        
        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()){
            
            $codCarta = $this->_request->getPost('idCarta');
            $codContrib = $this->_request->getPost('idContrib');
            $fechaFiscalizacion = $this->_request->getPost('fechaFiscalizacion');
            $hora = $this->_request->getPost('hora');
            $anioDesde = $this->_request->getPost('anioDesde');
            $anio = $this->_request->getPost('anio');
            $idMotivo = $this->_request->getPost('idMotivo');
            $jsonDirPredios = $this->_request->getPost('jsonPredios');
            $jsonFiscalizadores = $this->_request->getPost('jsonFiscalizadores');

            
            $nombrepctemp=explode('.',gethostbyaddr($_SERVER['REMOTE_ADDR']));
            $login = new Zend_Session_Namespace('login');

            $estacion = $nombrepctemp[0];
            $operador = $login->user;

            
            $dataDirPredios = json_decode($jsonDirPredios); 
            $dataFiscalizadores = json_decode($jsonFiscalizadores); 

            $dxmlDirPredios=""; 
            $dxmlFiscalizadores=""; 

                               
            foreach ($dataDirPredios as $key => $value){ 
                        
                        $dxmlDirPredios=$dxmlDirPredios."<row ";
                        
                        foreach ($value as $k => $v) { 
                         
                            if ($k=='mz') { 
                                $v=str_replace("'", "''",$v);
                            }          
                            
                            $dxmlDirPredios.=$k.' = "'.str_replace("'", "''",$v).'" '; 

                        }
                        $dxmlDirPredios.=" />";
            }

            foreach ($dataFiscalizadores as $key => $value){ 
                        
                        $dxmlFiscalizadores=$dxmlFiscalizadores."<row ";
                        
                        foreach ($value as $k => $v) { 
                            $dxmlFiscalizadores.=$k.' = "'.$v.'" '; 
                        }
                        $dxmlFiscalizadores.=" />";
            }


            $codCarta = $this->_request->getPost('idCarta');
            $codContrib = $this->_request->getPost('idContrib');
            $fechaFiscalizacion = $this->_request->getPost('fechaFiscalizacion');
            $hora = $this->_request->getPost('hora');
            $anioDesde = $this->_request->getPost('anioDesde');
            $anio = $this->_request->getPost('anio');
            $idMotivo = $this->_request->getPost('idMotivo');
            $jsonDirPredios = $this->_request->getPost('jsonPredios');
            $jsonFiscalizadores = $this->_request->getPost('jsonFiscalizadores');

            $cn = new Model_DbDatos_Datos();
            unset($parametros);
            $parametros[] = array('@mquery',(!empty($codCarta)) ? 2 : 1);
            if (!empty($codCarta)) { 
                $parametros[] = array('@idCarta',$codCarta); 
            }else{
                $parametros[] = array("@codContrib",$codContrib);
            }
            $parametros[] = array('@fecInspec',$fechaFiscalizacion);
            $parametros[] = array('@horaInspec',$hora);
            $parametros[] = array('@anioDesde',$anioDesde);
            $parametros[] = array('@anio',$anio);
            $parametros[] = array('@idMotivo',$idMotivo);
            $parametros[] = array('@estacion',$estacion);
            $parametros[] = array('@operador',$operador);
            $parametros[] = array('@dataxmlDirPredios',$dxmlDirPredios);
            $parametros[] = array('@dataxmlFiscalizadores',$dxmlFiscalizadores);    
            
            $resultado = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]',$parametros);

            #si se ejecuto correctamente
            if ($resultado[0]) {
              
                #se muestra el mensaje de retorno
                $row = $resultado[0];
                
                #validaciones que retorna el PROCEDURE
                if (strtoupper($row[0])=="TRUE") {
                    echo trim($row[0])."|".trim($row[1]);
                }else{
                    echo "FALSE|".trim(utf8_encode($row[1]));
                }

            }else{
                #en caso de error
                echo "FALSE|".utf8_encode($resultado[0][1]);
                exit();
            }            

        }       

	}

	public function eliminarAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) 
        {
                $codCarta = trim($this->_request->getPost('codCarta'));
                $cn = new Model_DbDatos_Datos();
                $parametros[] = array('@mquery',"3");
                $parametros[] = array('@idCarta',$codCarta);
                $rowCARTA_REQ = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTA_REQ]', $parametros);
                
                if($rowCARTA_REQ[0]){
                    $datos=$rowCARTA_REQ[0];
                    echo $datos[0];
                }
                else{
                    echo utf8_encode($rowCARTA_REQ[0][0]);
                    exit();
                }
        }
        else 
        { 
             echo "Error: No se pudo realizar la accion";
             exit;
        }

	}

    public function generadircompletaAction(){

        $this->_helper->getHelper('ajaxContext')->initContext();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()){
            
            $id_via = $this->_request->getParam("id_via");
            $num_call = $this->_request->getParam("num_call");
            $num_manz = $this->_request->getParam("num_manz");
            $num_lote = $this->_request->getParam("num_lote");
            $sub_lote = $this->_request->getParam("sub_lote");
            $num_depa = $this->_request->getParam("num_depa");
            $referenc = $this->_request->getParam("referenc");

            unset($parametros);
            $cn = new Model_DbDatos_Datos();
            $parametros [] = array("@mquery",10);
            $parametros [] = array("@anio",date("Y"));
            $parametros [] = array("@id_via",$id_via);
            $parametros [] = array("@num_call",$num_call);
            $parametros [] = array("@num_manz",$num_manz);
            $parametros [] = array("@num_lote",$num_lote);
            $parametros [] = array("@sub_lote",$sub_lote);
            $parametros [] = array("@num_depa",$num_depa);
            $parametros [] = array("@referenc",$referenc);

            $resultado = $cn->ejec_store_procedura_sql('[SP_FISCA_CARTAPREDIOS]',$parametros);

            #si se ejecuto correctamente
            if ($resultado[0]) {
              
                #se muestra el mensaje de retorno
                $row = $resultado[0];
                
                #validaciones que retorna el PROCEDURE
                if (strtoupper($row[0])=="TRUE") {
                    echo trim($row[0])."|".trim(utf8_encode($row[1]));
                }else{
                    echo "FALSE|".trim(utf8_encode($row[1]));
                }

            }else{
                #en caso de error
                echo "FALSE|".utf8_encode($resultado[0][1]);
                exit();
            }            


        }
    }

}