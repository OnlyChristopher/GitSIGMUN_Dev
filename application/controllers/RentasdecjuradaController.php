<?php

class RentasdecjuradaController extends Zend_Controller_Action
{
	  
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
    	$path->codigo = $this->_request->getParam('codigo','');
    	
    	$codigo=$path->codigo ;
    	
		$nombrestore = 'Rentas.sp_rentasmain';
        $arraydatos[0]= array('@buscar','3');
        $arraydatos[1]= array('@codigo',$codigo);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

        if (count($datoglobal)>0){
	        $path->nombres=$datoglobal[0][1];;
	        $path->num_doc=$datoglobal[0][2];;
	        $path->direccion=$datoglobal[0][3];;
	        
	        $this->view->varcodigo = $path->codigo;
	        $this->view->varnombre = $path->nombres;
	        $this->view->vardocumen = $path->num_doc;
			$this->view->regimen = $path->num_doc;
	        $this->view->vardomicilio = $path->direccion;
        }
        
		$fn = new Libreria_Pintar();

		$evt[] = array('#btnCerrarDj',"click","closePopup('#popdecjurada');");
		$evt[] = array('#btnNuevoHr',"click","showPopup('rentasdecjurada/hr?actionHr=1','#popeditarhr','620','620','Nueva Hoja de Resumen','frmhr');");
		$evt[] = array('#btnEditarHr',"click","showPopup('rentasdecjurada/hr?actionHr=2','#popeditarhr','620','620','Editar Hoja de Resumen','frmhr');");
		$evt[] = array('#btnNuevoPu',"click","NuevoPu('rentas/predios')");		
		$evt[] = array('#btnInscripcion',"click","Inscripcion('rentas/predios')");
		$evt[] = array("#btnEliminarPu","click","EliminarPredio()");
		$evt[] = array("#btnPrintCup","click","Imprime_Reporte()");
		$evt[] = array(".chcuponera","click","selectcuponera(this)");
		
		$evt[] = array("#btnDeterminacion","click","determinacionPredio('rentasdecjurada/determinacion','$codigo')");
		$evt[] = array("#btnVerbaja","click","Verbajapredi()");
		$evt[] = array("#btnCargabaja","click","Cargarbajapredio()");
		$evt[] = array('#btnVenta',"click","VenderPredio()");
		
		$fn->PintarEvento($evt);
    }
    
	public function nuevoAction()
    {    		
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnGrabarHr',"click","goToFormulario('frmAnioHr');");
		$evt[] = array('#btnCerrarHr',"click","closePopup('#popnuevohr');");
		
		$fn->PintarEvento($evt);
    }
    
	public function generarAction()
    {
    	$path = new Zend_Session_Namespace('path');
    	
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	$desde = $this->_request->getPost('desde');
    	$hasta = $this->_request->getPost('hasta');

    	$nombrestore = 'Rentas.generahr';
        $arraydatos[0]= array('@codigo',$path->codigo);
        $arraydatos[1]= array('@desde',$desde);
        $arraydatos[2]= array('@hasta',$hasta);
        
        $cn = new Model_DbDatos_Datos();
        @$datosregimen = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
        
        $path->anno=$desde;
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		echo "Se genero correctamente ".$desde."-".$hasta;
    	}
    	
    }
    
    public function hrAction()
    { 
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
	        
		$actionHr 	= $this->_request->getParam('actionHr','');

       	$nombrestore = 'calculo.sp_listacombo';
        $arraydatos[0]= array('@busc','1');        
        
                
        $cn = new Model_DbDatos_Datos();
        $datosregimen = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

        for($i=0; $i < count($datosregimen); $i++){
            $regimen[$i+1] = array($datosregimen[$i][0],$datosregimen[$i][1]);
        }

        $nombrestore = 'calculo.sp_listacombo';
        $arraydatos[0]= array('@busc','2');        

        $datosmotivos = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
        
        $nombrestore = 'rentas.sp_MHRpred';
        $arraydatos [0] = array ('@busc', '3' );
        $arraydatos [1] = array ('@codigo', $path->codigo );
        $arraydatos [2] = array ('@anno', $path->anno );
            
		$datoshr = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
            
        for($i=0; $i < count($datosmotivos); $i++){
            $motivos[$i+1] = array($datosmotivos[$i][0],$datosmotivos[$i][1]);
        }
        
        $val[] = array('#actionHr',$actionHr,'val');
        
		$evt[] = array('#txtfecdecla',"datepicker","");
		$evt[] = array('#txtfecresol',"datepicker","");
		$evt[] = array('#txtdesde',"datepicker","");
		$evt[] = array('#txthasta',"datepicker","");
		
		$regimenval='0';
		$motivosval='01';
		$chkemision=0;

		$this->view->varcodigo = $path->codigo;
		$this->view->varnombre = $path->nombres;
		$this->view->vardirecc = $path->direccion;
				
		if(count($datoshr)>0 and $actionHr=='2'){

                $this->view->txtcodigo = $datoshr[0][0];
                $this->view->txtanno= $datoshr[0][1];
                $this->view->txtpredios = $datoshr[0][13];
                $this->view->txtnrodecla = trim($datoshr[0][2]);
                $this->view->txtfecdecla = $datoshr[0][3];
                $this->view->txtbaseimpo = number_format($datoshr[0][9],2);
                $this->view->txtautovaluo = number_format($datoshr[0][7],2);
                $this->view->txtbaseimp = number_format($datoshr[0][9],2);
                $this->view->txtimpanual = number_format($datoshr[0][10],2);
                $this->view->txtgastos = number_format($datoshr[0][14],2);
                $this->view->txttrimestre = number_format($datoshr[0][11],2);
                $this->view->txtobserva= $datoshr[0][21];
                $this->view->txtnroresol= $datoshr[0][16];
                $this->view->txtfecresol= $datoshr[0][18];
                $this->view->txtnroexp= $datoshr[0][17];
                $this->view->txtbaseleg= $datoshr[0][15];
                $this->view->txtdesde= $datoshr[0][19];
                $this->view->txthasta= $datoshr[0][20];
                $chkemision=$datoshr[0][12];
                $this->view->txtusuario= $this->codigo;//$datoshr[0][23];
                $this->view->txtfecha= $datoshr[0][25];
                $this->view->txtestacion= $datoshr[0][24];
				$regimenval=$datoshr[0][6];
				$motivosval=$datoshr[0][5];
				$this->view->txthrdesde= $datoshr[0][1];
				$this->view->txthrhasta= $datoshr[0][27];
            }

            $cad[] = array("#chkemision","checked", $chkemision==1 ? 'true' : 'false');
			$val[] = array("#cmbRegimen",$fn->ContenidoCombo2($regimen,$regimenval,''),"html");
			$val[] = array("#cmbMotivo",$fn->ContenidoCombo2($motivos,$motivosval,''),"html");
						
			$evt[] = array('#btnEditaHr',"click","goToFormulario('frmhr');");
			$evt[] = array('#btnCierraHr',"click","closePopup('#popeditarhr');");
			
            $fn->PintarEvento($evt);
			$fn->PintarValor($val);
			$fn->AtributoComponente($cad);
    }
    
    public function grabarAction()
    {
        
    	$path = new Zend_Session_Namespace('path');
    	
        $this->_helper->getHelper('ajaxContext')->initContext();
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->_helper->layout->disableLayout();
        }
        
        
//        $busc=strlen($this->_request->getPost('txtcodigo'))==0 ? '1' : '2';
        $hostname= strtoupper(gethostname());

        //$nombrestore='rentas.sp_MHRpred';
        $nombrestore='Rentas.generahr';
        //$arraydatos[] = array("@busc", '2');
        $arraydatos[] = array("@desde",$this->_request->getPost('txthrdesde'));
        $arraydatos[] = array("@hasta",$this->_request->getPost('txthrhasta'));
        
        $arraydatos[] = array("@codigo",$path->codigo);
        $arraydatos[] = array("@anno", $path->anno);
        $arraydatos[] = array("@estacion", $hostname);
        $arraydatos[] = array("@fech_resol", $this->_request->getPost('txtfecresol'));
        $arraydatos[] = array("@base_legal", $this->_request->getPost('txtbaseleg'));
        $arraydatos[] = array("@vigencia_desde", $this->_request->getPost('txtdesde'));
        $arraydatos[] = array("@vigencia_hasta", $this->_request->getPost('txthasta'));
        $arraydatos[] = array("@expe_exone", $this->_request->getPost('txtnroexp'));
        $arraydatos[] = array("@id_motivo", $this->_request->getPost('cmbMotivo'));
        $arraydatos[] = array("@id_inafec", $this->_request->getPost('cmbRegimen'));
        $arraydatos[] = array("@nume_resol", $this->_request->getPost('txtnroresol'));
        $arraydatos[] = array("@observacion", $this->_request->getPost('txtobserva'));
        $arraydatos[] = array("@fec_decla", $this->_request->getPost('txtfecdecla'));
        $arraydatos[] = array("@afect_emi", $this->_request->getPost('chkemision'));
        $arraydatos[] = array("@num_decla", $this->_request->getPost('txtnrodecla'));
        
        
        $cn = new Model_DbDatos_Datos();
        @$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
        if($this->getRequest()->isXmlHttpRequest()){
    		echo "Se genero correctamente";
    	}
    }
    
	public function annosAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$codigo = $this->_request->getParam('codigo','');
    	
        $nombrestore='Rentas.sp_rentasmain';
        $arraydatos[0] = array("@buscar", '1');
        $arraydatos[1] = array("@codigo", $path->codigo);
        
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$jsonData = array('root'=>array());
		
		foreach($rows AS $row){
			$entry = array(
					'id'=>$row[1],
					'text'=>$row[1],				  
					'leaf'=>true,
					'iconCls'=>'folder',
					'title'=>$row[0]);
			$jsonData['root'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }

	public function prediosAction()
    {
    	$path = new Zend_Session_Namespace('path');
    	
        $anno = $this->_request->getParam('anno');
        $codigo = $this->_request->getParam('codigo');
        
        $nombrestore='Rentas.sp_rentasmain';
        $arraydatos[0] = array("@buscar", '4');
        $arraydatos[1] = array("@codigo", $path->codigo);
        $arraydatos[2] = array("@anno", $path->anno);
        
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$jsonData = array('root'=>array());
		foreach($rows AS $row){
			$entry = array(
					'tipo'=>$row[8],
					'cod_pred'=>$row[2],
					'anexo'=>$row[3],
					'direccion'=>utf8_encode($row[4]),				  
					'area_terreno'=>$row[5],
					'porcen_propiedad'=>$row[6],
					'total_autoavaluo'=>$row[7],
					'predio_vendido'=>$row[11]
			);
			$jsonData['root'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }
    
	public function detalleannoAction()
    {
        $path = new Zend_Session_Namespace('path');
        
        $anno = $this->_request->getPost('txtanno');
        $codigo = $this->_request->getPost('txtcodigo');

        $path->anno=$anno;
        
        $nombrestore='Rentas.sp_rentasmain';
        $arraydatos[0] = array("@buscar", '2');
        $arraydatos[1] = array("@codigo", $path->codigo);
        $arraydatos[2] = array("@anno",   $path->anno);
        
        
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$jsonData = array('datoshr'=>array());
		foreach($rows AS $row){
			$entry = array(
					'codigo'=>$row[0],
					'anno'=>$row[1],				  
					'nro_predi'=>$row[8],
					'tot_autoavaluo'=>$row[2],
					'base_imponible'=>$row[4],
					'imp_anual'=>$row[5],
					'imp_trime'=>$row[6],
					'costo_emi'=>$row[9],
					'por_inafec'=>$row[13]
			);
			$jsonData['datoshr'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }
	/*
	public function bajapredioAction()
	{
		
		if ($this->_request->isPost())
		{
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		
		$txtcodigo=trim($this->_request->getPost('txtcodigo'));
		$txtcodpred=trim($this->_request->getPost('txtcodpred'));
		$txtanexo=trim($this->_request->getPost('txtanexo'));
		$txtanno=trim($this->_request->getPost('txtanno'));
		
		$array=explode('-',$txtanexo);
		
		$nombreprocedure="[Rentas].[BajasPredio]";
		$parametros[]=array("@buscar",1);
		$parametros[]=array("@codigo",$txtcodigo);
		$parametros[]=array("@anno",$txtanno);
		$parametros[]=array("@cod_pred",$txtcodpred);
		$parametros[]=array("@anexo",$array[0]);
		$parametros[]=array("@sub_anexo",$array[1]);
		$parametros[]=array("@usuario",'CRICHITO');
		$parametros[]=array("@estacion",'INFO04');
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocedure,$parametros);
		echo $rows[0][0];
		}
		else{
		$path = new Zend_Session_Namespace('path');
		$codigo = trim($this->_request->getParam('codigo',''));
        $anno = trim($this->_request->getParam('anno',''));
		$cod_pred = trim($this->_request->getParam('cod_pred',''));
        $anexo = trim($this->_request->getParam('anexo',''));
		
		$fn = new Libreria_Pintar();
		$array=explode('-',$anexo);
		$anexo=$array[0];
		$sub_anexo=$array[1];
		
		$nombreprocesdure="Rentas.sp_rentasmain";
		$arraydatos[]=array("@buscar",5);				 
		$arraydatos[]=array("@codigo",$codigo);				 
		$arraydatos[]=array("@anno",$anno);				 
		$arraydatos[]=array("@cod_pred",$cod_pred);				 
		$arraydatos[]=array("@anexo",$anexo);				 
		$arraydatos[]=array("@sub_anexo",$sub_anexo);				 		
		
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocesdure,$arraydatos);
		
		$val[] = array("#divECodigo",$rows[0][0],"html");
		$val[] = array("#divENombre",$rows[0][1],"html");
		$val[] = array("#divCodPred",$rows[0][2],"html");
		$val[] = array("#divSAnexo",$rows[0][3],"html");
		$val[] = array("#divDireccion",$rows[0][4],"html");
		$val[] = array("#divAnno",$anno,"html");
		
		$val[] = array("#hd_ECodigo",$rows[0][0],"val");
		$val[] = array("#hd_CodPred",$rows[0][2],"val");
		$val[] = array("#hd_SAnexo",$rows[0][3],"val");
		$val[] = array("#hd_anno",$anno,"val");
		
		$evt[] = array('#btnGrabar',"click","goToFormulario('frmbajapredio');");
		$evt[] = array('#btnCerrar',"click","closePopup('#bajapredio');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		}
	}
	*/
	
	public function bajapredioAction()
	{
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);		
		
		
		if ($this->_request->isPost())
		{
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		
		$txtcodigo=trim($this->_request->getPost('txtcodigo'));
		$txtcodpred=trim($this->_request->getPost('txtcodpred'));
		$txtanexo=trim($this->_request->getPost('txtanexo'));
		$txtanno=trim($this->_request->getPost('txtanno'));
		$txtdireccion=trim($this->_request->getPost('txtdireccion'));
		
		$array=explode('-',$txtanexo);
		
		$id_motivo_descargo=trim($this->_request->getPost('cmbDescargo'));
		$porc_propiedad=trim($this->_request->getPost('txtPropiedad'));
		$observacion=trim($this->_request->getPost('txtGlosa'));
		$fech_transparencia=trim($this->_request->getPost('txtfecha'));
		$id_notaria=trim($this->_request->getPost('cmbNotaria'));
		$codigo_adquiriente=trim($this->_request->getPost('txtAdquiriente'));
		
		
		$nombreprocedure="[Rentas].[BajasPredio]";
		$parametros[]=array("@buscar",1);
		$parametros[]=array("@codigo",$txtcodigo);
		$parametros[]=array("@anno",$txtanno);
		$parametros[]=array("@cod_pred",$txtcodpred);
		$parametros[]=array("@anexo",$array[0]);
		$parametros[]=array("@sub_anexo",$array[1]);
		$parametros[]=array("@usuario",$username);
		$parametros[]=array("@estacion",$hostname);
		
		$parametros[]=array("@tipo_pred",1);
		$parametros[]=array("@id_motivo_descargo",$id_motivo_descargo);
		$parametros[]=array("@porc_propiedad",$porc_propiedad);
		$parametros[]=array("@observacion",$observacion);
		$parametros[]=array("@fech_transparencia",$fech_transparencia);
		$parametros[]=array("@id_notaria",$id_notaria);
		$parametros[]=array("@codigo_adquiriente",$codigo_adquiriente);
		$parametros[]=array("@direccion_predio",$txtdireccion);
		$parametros[]=array("@operador", $username);
		$parametros[]=array("@estacion2", $hostname);
		
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocedure,$parametros);
		echo $rows[0][0];
		}
		else
		{
		$path = new Zend_Session_Namespace('path');
		$codigo = trim($this->_request->getParam('codigo',''));
        $anno = trim($this->_request->getParam('anno',''));
		$cod_pred = trim($this->_request->getParam('cod_pred',''));
        $anexo = trim($this->_request->getParam('anexo',''));
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$array=explode('-',$anexo);
		$anexo=$array[0];
		$sub_anexo=$array[1];
		
		$nombreprocesdure="Rentas.sp_rentasmain";
		$arraydatos[]=array("@buscar",5);				 
		$arraydatos[]=array("@codigo",$codigo);				 
		$arraydatos[]=array("@anno",$anno);				 
		$arraydatos[]=array("@cod_pred",$cod_pred);				 
		$arraydatos[]=array("@anexo",$anexo);				 
		$arraydatos[]=array("@sub_anexo",$sub_anexo);				 		
		
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocesdure,$arraydatos);
		
		
		unset($arraydatos);
		$arraydatos[] = array('@buscar',7);	
		$combodescargo = $cn->ejec_store_procedura_sql('Rentas.sp_rentasmain',$arraydatos);
		$arDescargo = $ar->RegistrosCombo($combodescargo,0,1);
		$val[] = array('#cmbDescargo',$fn->ContenidoCombo($arDescargo,'[Seleccione]',''),'html');
		
		
		unset($arraydatos);
		$arraydatos[] = array('@buscar',8);	
		$combonotaria = $cn->ejec_store_procedura_sql('Rentas.sp_rentasmain',$arraydatos);
		$arNotaria = $ar->RegistrosCombo($combonotaria,0,1);
		$val[] = array('#cmbNotaria',$fn->ContenidoCombo($arNotaria,'[Seleccione]',''),'html');
		
		
		
		$val[] = array("#divECodigo",$rows[0][0],"html");
		$val[] = array("#divENombre",$rows[0][1],"html");
		$val[] = array("#divCodPred",$rows[0][2],"html");
		$val[] = array("#divSAnexo",$rows[0][3],"html");
		$val[] = array("#divDireccion",$rows[0][4],"html");
		$val[] = array("#divAnno",$anno,"html");
		
		$val[] = array("#hd_ECodigo",$rows[0][0],"val");
		$val[] = array("#hd_CodPred",$rows[0][2],"val");
		$val[] = array("#hd_SAnexo",$rows[0][3],"val");
		$val[] = array("#hd_anno",$anno,"val");
		$val[] = array("#hd_direccion",$rows[0][4],"val");
		
		$evt[] = array('#btnGrabar',"click","goToFormulario('frmbajapredio');");
		$evt[] = array('#btnCerrar',"click","closePopup('#bajapredio');");
		
		$evt[] = array('#txtfecha',"datepicker","");
		
		//$evt[] = array('#txtfecha',"keypress","return validaTeclas(event,'number');");
		//$evt[] = array('#txtPropiedad',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#txtGlosa',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#btnBusBajaPre',"click","showPopup('mantbusbajapre/buscar','#popbuscabajapre','1000','358','Busqueda Contribuyente','frmbajapredio');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		}
	}
	public function determinacionAction(){
		$path = new Zend_Session_Namespace('path');
		$codigo = trim($this->_request->getParam('codigo',''));
		$anno = trim($this->_request->getParam('anno',''));
		
		$nombreprocesdure="Rentas.sp_rentasmain";
		$arraydatos[]=array("@buscar",3);				 
		$arraydatos[]=array("@codigo",$codigo);				
		
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocesdure,$arraydatos);
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		
		$val[] = array("#divECodigo",$rows[0][0],"html");
		$val[] = array("#divENombre",$rows[0][1],"html");
		$val[] = array("#divDireccion",$rows[0][3],"html");
		$val[] = array("#divAnno",$anno,"html");
		$evt[] = array('#btnDeterminacionip',"click","calcularip();");
		$evt[] = array('#btnDeterminacionarb',"click","calculararb();");
		$evt[] = array('#btnCerrar',"click","closePopup('#popupdeterminacion');");
		
		
		
		$fn->PintarValor($val);		
		$fn->PintarEvento($evt);
	}
	public function determinacionimpuestoAction(){
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$getlogin = new Zend_Session_Namespace('login');
		$hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);		
		
		$codigo = $this->_request->getPost('codigo');
		$anno = $this->_request->getPost('anno');
		$tipodeterminacion=$this->_request->getPost('tipodeterminacion');
	
		$cn = new Model_DbDatos_Datos();
		$arraydatos[]=array("@msquery",1);				 
		$arraydatos[]=array("@codigo",$codigo);	
		$arraydatos[]=array("@anno",$anno);	
		$arraydatos[]=array("@cod_pred",'');	
		$arraydatos[]=array("@anexo",'');	
		$arraydatos[]=array("@sub_anexo",'');	
		$arraydatos[]=array("@tipo",'02.01');	
		$arraydatos[]=array("@tipo_rec",'02.01');	
		$arraydatos[]=array("@cuotas",4);	
		$arraydatos[]=array("@operador",$username);	
		$arraydatos[]=array("@estacion",$hostname);	
		$arraydatos[]=array("@tipo_calculo",$tipodeterminacion);	
		$rows = $cn->ejec_store_procedura_sql_noparamatro("[Rentas].[generador_recibo]",$arraydatos);
		
		if($rows){
			echo "Se Genero el  IP con exito!";
		}
		else{
			echo "Ocurrio un problema al Generar el IP.";
		}
		
	}
	public function determinacionarbitrioAction(){
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$getlogin = new Zend_Session_Namespace('login');
		$hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);		
		
		$json = $this->_request->getPost('json');
		$data = json_decode($json);
	
		$cn = new Model_DbDatos_Datos();
				
		foreach($data as $dPredio){
			$codigo=$dPredio->codigo;
			$anno=$dPredio->anno;
			$cod_pred=$dPredio->cod_pred;
			$anexo=$dPredio->anexo;
			$sub_anexo=$dPredio->sub_anexo;
			$tipodeterminacion=$dPredio->tipodeterminacion;
			
			unset($arraydatos);
			$cn = new Model_DbDatos_Datos();
			$arraydatos[]=array("@codigo",$codigo);	
			$arraydatos[]=array("@ano_s",$anno);	
			$arraydatos[]=array("@cod_pred",$cod_pred);	
			$arraydatos[]=array("@anexo",$anexo);	
			$arraydatos[]=array("@sub_anexo",$sub_anexo);		
			$arraydatos[]=array("@operador",$username);	
			$arraydatos[]=array("@estacion",$hostname);	
			$arraydatos[]=array("@tipo_calculo",$tipodeterminacion);	
			$rows = $cn->ejec_store_procedura_sql_noparamatro("Rentas.Calculo_Arbitrios",$arraydatos);
			//echo print_r($arraydatos)."<br>";
			
		}
		if($rows){
			echo "Se Generon los Arbitrios con exito!";
		}
		else{
			echo "Ocurrio un problema al Generar los Arbitrios.";
		}

	}
	public function ventapredioAction(){
		$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		$getlogin = new Zend_Session_Namespace('login');
		$hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);		
		
		$codigo = $this->_request->getPost('codigo');
        $anno = $this->_request->getPost('anno');
		$cod_pred = $this->_request->getPost('cod_pred');
        $anexo = $this->_request->getPost('anexo');
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$array=explode('-',$anexo);
		$anexo=$array[0];
		$sub_anexo=$array[1];
		
		$nombreprocedure="[Rentas].[BajasPredio]";
		$parametros[]=array("@buscar",3);
		$parametros[]=array("@codigo",$codigo);
		$parametros[]=array("@anno",$anno);
		$parametros[]=array("@cod_pred",$cod_pred);
		$parametros[]=array("@anexo",$array[0]);
		$parametros[]=array("@sub_anexo",$array[1]);
		$parametros[]=array("@usuario",$username);
		$parametros[]=array("@estacion",$hostname);
		
		
		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql($nombreprocedure,$parametros);
		echo $rows[0][0];
		
		
	
	}
		
}