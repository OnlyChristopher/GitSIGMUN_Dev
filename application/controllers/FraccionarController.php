<?php

class FraccionarController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {
	
		$porcen_inicial=0;
		$nmonto_inicial=0;
		$nmonto_saldo=0;
		
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$totalpagar = $this->_request->getParam('totalpagar','');
		
		$param = $this->_request->getParam('param','');
		$val[] = array('#txtflag',$param,'val');
		//capturamos valores para las excepciones de un fraccionamineto
		$porc_ini = $this->_request->getParam('porc_ini','');
		$max_cuotas = $this->_request->getParam('max_cuotas','');
		$condicion_id = $this->_request->getParam('condicion_id','');
		$estado = $this->_request->getParam('estado','');

		$val[] = array('#porc_ini',$porc_ini,'val');
		$val[] = array('#max_cuotas',$max_cuotas,'val');
		$val[] = array('#condicion_id',$condicion_id,'val');
		$val[] = array('#estado',$estado,'val');


		$porcen_inicial=$fecharow[0][3];
		$nmonto_inicial=$totalpagar * ($porcen_inicial/100);
		$nmonto_saldo=$totalpagar-$nmonto_inicial;

		if($estado==1 and $param!=2)
		{
			$porcen_inicial=$porc_ini;
			$monto_ini=$totalpagar * ($porc_ini/100);
			$nmonto_inicial=$monto_ini;
			$nmonto_saldo=$totalpagar-$monto_ini;

		}
		else
		{
			if($param==1 )
			{
				$max_cuotas=25;
			}
			else if ($param==2)
			{
				$max_cuotas=11;
				$val[] = array('#porc_ini',$porcen_inicial,'val');
			}
		}
		
		$val[] = array('#txtFracc',$totalpagar,'val');
		$val[] = array('#txtFecha',$fecharow[0][0],'val');
		$val[] = array('#txtVencimiento',$fecharow[0][0],'val');
		$val[] = array('#txtInteres',$fecharow[0][2],'val');
		
		$val[] = array('#txtPorcentaje',$porcen_inicial,'val');
		$val[] = array('#txtInicial',$nmonto_inicial,'val');
		$val[] = array('#txtSaldo',$nmonto_saldo,'val');

		$mask[] = array("txtEmision");
		$mask[] = array("txtFracc");
		$mask[] = array("txtInicial");
		$mask[] = array("txtPorcentaje");
		$mask[] = array("txtSaldo");
		$mask[] = array("txtInteres");
		
		
		$evt[] = array('#btnDeudas',"button","");
    	$evt[] = array('#btnConvenio',"button","");
    	$evt[] = array('#btnVencimiento',"button","");
    	$evt[] = array('#btnInicial',"button","");
    	$evt[] = array('#btnSalir',"button","");
    	

//		$evt[] = array('#txtFecha',"datepicker","");
//		$evt[] = array('#txtVencimiento',"datepicker","");
		
		$evt[] = array('#btnDeudas',"click","mostrarcuotas($max_cuotas);");
		$evt[] = array('#btnConvenio',"click","generaconvenio();");
		$evt[] = array('#btnSimulado',"click","generasimulado();");
		$evt[] = array('#txtInicial',"blur","getporcentaje();");
		$evt[] = array('#btnSalir',"click","closePopup('#popfraccionardeuda');");
		
//		$evt[] = array('#txtEfectivo',"change", "restarmontosapagar();");

		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
		
    }
    public function muestracuotasAction()
    {

    	$cuotas = $this->_request->getPost('cuotas');
    	$total_deuda = $this->_request->getPost('total_deuda');
    	$total_inici = $this->_request->getPost('total_inici');
    	$fec_gen = $this->_request->getPost('fec_gen');
    	$fec_cuo = $this->_request->getPost('fec_cuo');
    	
    	$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = 'Rentas.CuotasConvenio';
		$arraydatos[]=array("@cuotas", $cuotas);
		$arraydatos[]=array("@total_deuda", str_replace(',','',$total_deuda));
		$arraydatos[]=array("@total_inici", str_replace(',','',$total_inici));
		$arraydatos[]=array("@fec_gen", $fec_gen);
		$arraydatos[]=array("@fec_cuo", $fec_cuo);
		
		$rowcuotas = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		$ntotal=count($rowcuotas);

		$jsonData = array('total'=>$ntotal,'rows'=>array());
		foreach($rowcuotas AS $row){
			$entry = array(
					'cuota'=>$row[0],				  
					'anno'=>$row[1],
					'total_deuda'=>$row[2],
					'cuota_ini'=>$row[3],
					'saldo_deuda'=>$row[4],
					'monto_cuota'=>$row[5],
					'intereses'=>$row[6],
					'cuota_total'=>$row[7],
					'total_frac'=>$row[8],
					'cuotas'=>$row[9],
					'fec_gen'=>$row[10]
			);
			$jsonData['rows'][] = $entry;
		}

		$this->view->data = json_encode($jsonData);
    }
    
    public function generaconvenioAction(){
    		
    	$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$this->_helper->Layout->disableLayout();		
		//$this->_helper->ViewRenderer->setNoRender();
		
		$codigo = $this->_request->getPost('codigo');
    	$cuotas = $this->_request->getPost('cuotas');
    	$total_deuda = $this->_request->getPost('total_deuda');
    	$total_inici = $this->_request->getPost('total_inici');
    	$fec_gen = $this->_request->getPost('fec_gen');
    	$fec_cuo = $this->_request->getPost('fec_cuo');
		$json = $this->_request->getPost('json');

		$condicon_id = $this->_request->getPost('condicon_id');

		
		$data = json_decode($json);
		$dxml = '';
		$cajero=$getlogin->caja;

		if(strlen($cajero)>0){
			foreach ($data as $key => $value){ 
	//			echo "<h2>$key</h2>";
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			
			//echo $dxml;
			
			$cn = new Model_DbDatos_Datos();
	
			$nombrestore="Rentas.GeneraConvenio";
			$arraydatos[]=array("@codigo", $codigo);
			$arraydatos[]=array("@cuotas", $cuotas);
			$arraydatos[]=array("@operador", $username);
			$arraydatos[]=array("@estacion", $hostname);
			$arraydatos[]=array("@total_deuda", $total_deuda);
			$arraydatos[]=array("@total_inici", $total_inici);
			$arraydatos[]=array("@fec_gen", $fec_gen);
			$arraydatos[]=array("@fec_cuo", $fec_cuo);
			$arraydatos[]=array("@condicion_id", $condicon_id);
			$arraydatos[]=array("@varxml", $dxml);
			

			$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
			echo $rowrecibos[0][0];
		}else{
			echo 'El usuario no tiene caja asignada';
		}
		
    }

	public function detallefracAction(){
		$path->codigo = $this->_request->getParam('codigo','');
    	
		$flag= $this->_request->getParam('flag','');
    	$val[] = array("#txtflag",$flag,'val');
		
    	$codigo=$path->codigo ;
    	
		$nombrestore = 'Rentas.sp_rentasmain';
        $arraydatos[0]= array('@buscar','3');
        $arraydatos[1]= array('@codigo',$codigo);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		$val[] = array("#divCodFracc",$codigo,"html");
		$val[] = array("#divNombre",$datoglobal[0][1],"html");
/*		$val[] = array("#divDireccion",$rows[0][3],"html");
		$val[] = array("#divAnno",$anno,"html");
		$evt[] = array('#btnDeterminacionip',"click","calcularip();");
		$evt[] = array('#btnDeterminacionarb',"click","calculararb();");
		
*/		$evt[] = array('#btnSalir',"click","closePopup('#poplistafrac');");
		//$evt[] = array('#btnReporte',"click","closePopup('#poplistafrac');");
		$evt[] = array('#btnReporteFracc',"click","showPopup('fraccionar/reportes','#listadoreporte','900','620','Reporte de Fraccionamiento','frmReporteFracc');");
		
		
		$fn->PintarValor($val);		
		$fn->PintarEvento($evt);	
		
		
	}

		public function detallefracinfoAction(){
		$path->codigo = $this->_request->getParam('codigo','');
		
		$flag= $this->_request->getParam('flag','');
    	$val[] = array("#txtflag",$flag,'val');
    	$codigo=$path->codigo ;
    	
		$nombrestore = 'Rentas.sp_rentasmain';
        $arraydatos[0]= array('@buscar','3');
        $arraydatos[1]= array('@codigo',$codigo);
        
        $cn = new Model_DbDatos_Datos();
        $datoglobal = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);

		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		$val[] = array("#divCodFracc",$codigo,"html");
		$val[] = array("#divNombre",$datoglobal[0][1],"html");
/*		$val[] = array("#divDireccion",$rows[0][3],"html");
		$val[] = array("#divAnno",$anno,"html");
		$evt[] = array('#btnDeterminacionip',"click","calcularip();");
		$evt[] = array('#btnDeterminacionarb',"click","calculararb();");
		
*/		$evt[] = array('#btnSalir',"click","closePopup('#poplistafrac');");
		//$evt[] = array('#btnReporte',"click","closePopup('#poplistafrac');");
		$evt[] = array('#btnReporteFracc',"click","showPopup('fraccionar/reportes','#listadoreporte','900','620','Reporte de Fraccionamiento','frmReporteFracc');");
		
		
		$fn->PintarValor($val);		
		$fn->PintarEvento($evt);	
		
		
	}

	public function simuladofracAction(){
		$this->_helper->Layout->disableLayout();	
		$login = new Zend_Session_Namespace('login');
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			
			$path=	new Zend_Session_Namespace('path');
			
			$datadeuda=$data->deuda;
			$dxml = '';
			$username= strtoupper($login->user);

			
				foreach ($datadeuda as $key => $value){ 
					$dxml.="<row ";
					foreach ($value as $k => $v) { 
						$dxml.=$k.' = "'.$v.'" '; 
					}
					$dxml.=" />";
				}
			
			$cn = new Model_DbDatos_Datos();
			$fn = new Libreria_Pintar();
			
			$datocontri=$data->codigo;
			foreach($datocontri as $contri){
				$codigo=$contri->codigoc;
			}
			
			unset($arraydatos);
			$arraydatos[]=array("@buscar",3);
			$arraydatos[]=array("@codigo",$codigo);
			$rowcontri = $cn->ejec_store_procedura_sql("[Rentas].[sp_rentasmain]", $arraydatos);
			
			$val[] = array("#lblNombreContribuyente",$rowcontri[0][1],"html");
			$val[] = array("#lblNombreContribuyente2",$rowcontri[0][1],"html");
			$val[] = array("#lblDomicilio",$rowcontri[0][3],"html");
			$val[] = array("#lblCodigoContribuyente",$rowcontri[0][0],"html");
			$val[] = array("#lblNumeroDocumento",$rowcontri[0][2],"html");
			$val[] = array("#lblNumeroDocumento2",$rowcontri[0][2],"html");
			$val[] = array("#lblMontoDeuda",$data->txtFracc,"html");
			$val[] = array("#lblCuotas",$data->txtNumero,"html");
			$val[] = array("#lblFechaProyeccion","","html");
			$val[] = array("#lblFechaEmision","","html");
			
			unset($arraydatos);
			$arraydatos[]=array("@codigo",$codigo);
			$arraydatos[]=array("@operador",'');
			$arraydatos[]=array("@estacion",'');
			$arraydatos[]=array("@varxml",$dxml);			
			$rowdeuda = $cn->ejec_store_procedura_sql("[Rentas].[GeneraConvenio_simulado_deuda]", $arraydatos);
			
			$table_deuda='';
			$suma_deuda=0;
			for($i=0;$i<count($rowdeuda);$i++){
				$table_deuda.="<tr>
								<td align='center' class='recibo_fr_col_anno'>".$rowdeuda[$i][1]."</td>
								<td align='left' class='recibo_fr_col_anno'>".$rowdeuda[$i][7]."</td>
								<td align='center' class='recibo_fr_col_anno'>".$rowdeuda[$i][9]."</td>
								<td align='center' class='recibo_fr_col_anno'>".$rowdeuda[$i][3]."</td>
								<td align='left' class='recibo_fr_col_anno'>".$rowdeuda[$i][2]."</td>
								<td align='right' class='recibo_fr_col_total'>".$rowdeuda[$i][14]."</td>
							  </tr>	
								";
				$suma_deuda=$suma_deuda+$rowdeuda[$i][14];		
			}
			
			unset($arraydatos);
			$arraydatos[]=array("@cuotas",$data->txtNumero);
			$arraydatos[]=array("@total_deuda",str_replace(',','',$data->txtFracc));
			$arraydatos[]=array("@total_inici",str_replace(',','',$data->txtInicial));
			$arraydatos[]=array("@operador",'');
			$arraydatos[]=array("@fec_gen",$data->txtFecha);
			$arraydatos[]=array("@fec_cuo",$data->txtVencimiento);
			$rowcuotas = $cn->ejec_store_procedura_sql("[Rentas].[GeneraConvenio_simulado_cuotas]", $arraydatos);
			
			$tabla_cuotas='';
			$suma_cuotas=0;
			
			for($i=0;$i<count($rowcuotas);$i++){
				if($rowcuotas[$i][0]=='00'){ $interes='0.00';}
			    else{ $interes=$rowcuotas[$i][6]; }
				$tabla_cuotas.="<tr>
								<td align='center'class='recibo_fr_col_periodo'>".$rowcuotas[$i][0]."</td>
								<td align='center'class='recibo_fr_col_anno'>".$rowcuotas[$i][1]."</td>
								<td align='center'class='recibo_fr_col_fecha_vencimiento'>".$rowcuotas[$i][10]."</td>
								<td align='right'class='recibo_fr_col_amo_total'>".$rowcuotas[$i][5]."</td>
								<td align='right'class='recibo_fr_col_int_total'>".$interes."</td>
								<td align='right'class='recibo_fr_col_total'>".$rowcuotas[$i][9]."</td>
							  </tr>";
				$suma_cuotas=$suma_cuotas+$rowcuotas[$i][9];		
			}
		$this->view->tabla_deuda=$table_deuda;
		$this->view->tabla_cuotas=$tabla_cuotas;
		$this->view->tdeuda_fraccionada=$suma_deuda;
		$this->view->tdeuda_cuotas=$suma_cuotas;
		$this->view->fecha=$rowcuotas[0][10];
		$this->view->usuario=$username;
		//$val[] = array("#tabla_deuda",$table_deuda,"append");
		//$val[] = array("#tabla_cuotas",$tabla_cuotas,"append");
		
		$fn->PintarValor($val);		
		//$fn->PintarEvento($evt);	
		}
	
	}
	
	public function consultafraccAction()
    {

    	$codigo = $this->_request->getParam('codigo','');
    	    	
    	$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = '[Rentas].[ImprimeConvenio]';
		$arraydatos[]=array("@buscar", 4);
		$arraydatos[]=array("@codigo", $codigo);
		
		$rowcuotas = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		$ntotal=count($rowcuotas);

		$jsonData = array('total'=>$ntotal,'rows'=>array());
		foreach($rowcuotas AS $row){
			$entry = array(
					'convenio'=>$row[5],				  
					'anno'=>$row[4],
					'cuotas'=>$row[11],
					'monto'=>$row[7],
					'estado'=>$row[16],
					'usuario'=>$row[19],
					'fecha'=>$row[21]
			);
			$jsonData['rows'][] = $entry;
		}

		$this->view->data = json_encode($jsonData);
    }

    public function consultafraccinfoAction()
    {

    	$codigo = $this->_request->getParam('codigo','');
    	    	
    	$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = '[Rentas].[ImprimeConvenio_infosat]';
		$arraydatos[]=array("@buscar", 4);
		$arraydatos[]=array("@codigo", $codigo);
		
		$rowcuotas = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		$ntotal=count($rowcuotas);

		$jsonData = array('total'=>$ntotal,'rows'=>array());
		foreach($rowcuotas AS $row){
			$entry = array(
					'convenio'=>$row[1],				  
					'anno'=>$row[2],
					'cuotas'=>$row[3],
					'monto'=>$row[4],
					'estado'=>utf8_encode($row[6]),
					'usuario'=>$row[7],
					'fecha'=>$row[8]
			);
			$jsonData['rows'][] = $entry;
		}

		$this->view->data = json_encode($jsonData);
    }


	public function resolfraccAction(){
	
		$codigo = $this->_request->getParam('codigo','');
		$convenio = $this->_request->getParam('convenio','');
				
    	$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
    	$nombrestore  = '[Rentas].[ImprimeConvenio] ';
		$arraydatos[]=array("@buscar",5);
		$arraydatos[]=array("@codigo",$codigo);
		$arraydatos[]=array("@convenio",$convenio);
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		$porcentaje_inicial=$rows[0][7]*(100/$rows[0][3]);
		$nmonto_saldo=$rows[0][3]-$rows[0][7];
		
		$val[] = array('#txtFracc',$rows[0][3],'val');
		$val[] = array('#txtFecha',$rows[0][12],'val');
		$val[] = array('#txtInteres',$fecharow[0][2],'val');
		
		$val[] = array('#txtPorcentaje',$porcentaje_inicial,'val');
		$val[] = array('#txtInicial',$rows[0][7],'val');
		$val[] = array('#txtSaldo',$nmonto_saldo,'val');
		
		$val[] = array('#hdCodigofrac',$codigo,'val');
		$val[] = array('#hdCodConvenio',$convenio,'val');
		$val[] = array('#txtNumero',$rows[0][6],'val');
		$val[] = array('#lblEstadoConvenio',$rows[0][9],'html');
		$val[] = array('#hdEstadoConvenio',$rows[0][14],'val');
		$val[] = array('#hdNroRecibo',$rows[0][13],'val');

		$mask[] = array("txtFracc");
		$mask[] = array("txtInicial");
		$mask[] = array("txtPorcentaje");
		$mask[] = array("txtSaldo");
		
		$evt[] = array('#btnSalirResol',"click","closePopup('#popresol');");
		$evt[] = array('#btnGenResol',"click","generaResolucion('".$codigo."','".$convenio."','fraccionar/resoluciongenera');");
		$evt[] = array('#btnImproResol',"click","ReoporteResolucion('".$codigo."','".$convenio."');");
		$evt[] = array('#BtnAnularConvenio',"click","anularFrac('fraccionar/anularfrac','".$codigo."','".$convenio."');");
		$evt[] = array('#BtnAnularConveniosc',"click","anularFracsc('fraccionar/anularfracsc','".$codigo."','".$convenio."');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
	
	}
	public function resolcuotasAction(){
		$codigo = $this->_request->getParam('codigo','');
		$convenio = $this->_request->getParam('convenio','');
		
		$cn = new Model_DbDatos_Datos();
    	
    	$nombrestore  = '[Rentas].[ImprimeConvenio]';
		$arraydatos[]=array("@buscar", 6);
		$arraydatos[]=array("@codigo", $codigo);
		$arraydatos[]=array("@convenio", $convenio);
		
		$rowcuotas = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		$ntotal=count($rowcuotas);

		$jsonData = array('total'=>$ntotal,'rows'=>array());
		foreach($rowcuotas AS $row){
			$entry = array(
					'periodo'=>$row[0],				  
					'imp_insol'=>$row[1],
					'reaj'=>$row[2],
					'fecha_v'=>$row[3],
					'nro_recibo'=>$row[4],
					'total'=>$row[1]+$row[2]
			);
			$jsonData['rows'][] = $entry;
		}

		$this->view->data = json_encode($jsonData);
	}
	public function resoluciongeneraAction(){
		$this->_helper->Layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$codigo = $this->_request->getPost('codigo');
		$convenio = $this->_request->getPost('convenio');
		
		$nombrestore  = '[Rentas].[ImprimeConvenio]';
		$arraydatos[]=array("@buscar", 7);
		$arraydatos[]=array("@codigo", $codigo);
		$arraydatos[]=array("@convenio", $convenio);
		
		$cn = new Model_DbDatos_Datos();
		$row = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		echo "Resolucion Generada Correctamente";
	}
	public function anularfracAction(){
		$this->_helper->Layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$codigo = $this->_request->getPost('codigo');
		$convenio = $this->_request->getPost('convenio');
		
		$nombrestore  = '[Rentas].[Anularconvenio]';
		$arraydatos[]=array("@codigo", $codigo);
		$arraydatos[]=array("@convenio", $convenio);
		$arraydatos[]=array("@operador", $username);
		$arraydatos[]=array("@estacion", $hostname);
		
		$cn = new Model_DbDatos_Datos();
		$row = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		echo "Resolucion Generada Correctamente";
	}
	public function reportesAction(){
		$fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','8');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbUsuario",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$this->view->tesodesde = $fecharow[0][0];
		$this->view->tesohasta = $fecharow[0][0];
		
		$evt[] = array('#fracdesde',"datepicker","");
		$evt[] = array('#frachasta',"datepicker","");
		
		$evt[] = array("#btnBuscarFracc", "click", "buscarListadofracc();");
		$evt[] = array("#btnImprimirfrac", "click", "generarpdf();");
		//$evt[] = array("#btnprintPartidas", "click", "generarpdfParidas();");
		//$mask[] = array("txtArancel");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		//$fn->CampoDinero($mask);
	
	}
	public function reporteconsultaAction(){
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cbusuario = $this->_request->getParam('cbusuario','');
		
		$nombrestore="[Rentas].[ImprimeConvenio]";
		$arraydatos[]=array("@buscar",8);
		$arraydatos[]=array("@fech_inicio",$desde);
		$arraydatos[]=array("@fech_fin",$hasta);
		$arraydatos[]=array("@operador",$cbusuario);
		
		$cn = new Model_DbDatos_Datos();
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
        
		$jsonData = array('rows'=>array());
		if(count($rows)){
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'anno'=>utf8_encode($row[1]),
						'convenio'=>utf8_encode($row[2]),
						'estado'=>utf8_encode($row[3]),
						'fecha'=>utf8_encode($row[4]),
						'deuda_ini'=>utf8_encode($row[5]),
						'cuotas'=>utf8_encode($row[6]),
						'cuotas_canceladas'=>utf8_encode($row[7]),
						'cuotas_vencidas'=>utf8_encode($row[8]),
						'operador'=>utf8_encode($row[9]),
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
		
	}
	
	public function htmlreporteAction(){
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cbusuario = $this->_request->getParam('cbusuario','');
		
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		unset($nombrestore);
		unset($arraydatos);
		$nombrestore="[Rentas].[ImprimeConvenio]";
		$arraydatos[]=array("@buscar",8);
		$arraydatos[]=array("@fech_inicio",$desde);
		$arraydatos[]=array("@fech_fin",$hasta);
		$arraydatos[]=array("@operador",$cbusuario);
		
		$cn = new Model_DbDatos_Datos();
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$extorno_efectivo+=$row[18];
				$extorno_mcheques+=$row[19];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[0]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[10]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[4]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[5]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[6]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[7]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[8]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[9]."</td>";
					
				$strHtml .= "</tr>";
			}
		}
		$this->view->strHtml = $strHtml;
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		
	}
	public function anularfracscAction(){
		$this->_helper->Layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$codigo = $this->_request->getPost('codigo');
		$convenio = $this->_request->getPost('convenio');
		
		$nombrestore  = '[Rentas].[Anularconveniosc]';
		$arraydatos[]=array("@codigo", $codigo);
		$arraydatos[]=array("@convenio", $convenio);
		$arraydatos[]=array("@operador", $username);
		$arraydatos[]=array("@estacion", $hostname);
		
		$cn = new Model_DbDatos_Datos();
		$row = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		
		echo "Resolucion Generada Correctamente";
	}
}		
		

		