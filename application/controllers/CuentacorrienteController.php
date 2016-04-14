<?php


class CuentacorrienteController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}
	
	public function indexAction()
	{
		$codigo=$this->_request->getParam('codigo','');

		$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;
		$ar = new Libreria_ArraysFunctions();
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#chkTotPeriodos',"click","marcaChecks(this,'chkperiodo[]')");
		$evt[] = array('#chkTotArbitrio',"click","marcaChecks(this,'chkarbitrio[]')");
		$evt[] = array('#chkTotAnios',"click","marcaChecks(this,'chkanio[]')");
		$evt[] = array('#chkTotPreddd',"click","marcaChecks(this,'predio[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");

		$evt[] = array('#btnPagarRecibos',"click","enviadeuda();");
		$evt[] = array('#btnMostrar',"click","mostrarRecContri(1);");
		//------------------agregó manuel----------------
		$evt[] = array('#btnSinInteres',"click","mostrarRecContri(2);");
		//-----------------------------------------------
		$evt[] = array('#btnVerRec',"click","mostrarRecibos();");
		$evt[] = array('#btnAnular',"click","anulaRecibos()");
		$evt[] = array('#btnEstCta',"click","imprimeCuentaPdf()");
		$evt[] = array('#btnCerrar',"click","closePopup('#poptesore');");
		$evt[] = array('#btnFraccionar',"click","fraccionar();");
		$evt[] = array('#btnMostrarAnulado',"click","mostrarRecibos();");
		$evt[] = array('#btnAjustecuenta',"click","imputardeuda();");
		
		$evt[] = array('#btnBaja',"click","getAjustecuenta();");
		$evt[] = array('#btnImputar',"click","getCompensacuenta();");

		//$evt[] = array('#btnBaja',"click","darbaja();");

		$fn->PintarEvento($evt);

		$nomcombo="rentas.sp_cuentacorriente";//"store_caja_framework";
		$arraydatos_nomcombo[]=array("@msquery",'2');//("@msquery",'1');
		$arraydatos_nomcombo[]=array("@codigo",$codigo);
		$cn = new Model_DbDatos_Datos();
	
		$rows = $cn->ejec_store_procedura_sql($nomcombo, $arraydatos_nomcombo);
		/*for ($i=0;$i<count($rows);$i++){*/
			$codigo=$rows[0][0];
			$nombre=$rows[0][1];
			//$doc=$rows[$i][2];
			$numdoc=$rows[0][3];
			$direccion=$rows[0][2];
			//$cantpred=$rows[$i][5];----------------
			//$apepater=$rows[$i][6];
			//$apemater=$rows[$i][7];
		//}
	//var_dump($rows);
	$contricaja = new Zend_Session_Namespace('contri');
    $contricaja->contri=$codigo;
    $contricaja->nombre=$nombre;
    $contricaja->numdoc=$numdoc;
    $contricaja->direccion=$direccion;
                
	 //divPredios
	 $val[] = array('#divCodigo',$codigo,"html");//en caso de de div html - input val	 
	 $val[] = array('#divContri',$nombre,"html");
	 $val[] = array('#divDirec',$direccion,"html");
	 $val[] = array('#divDocu',$numdoc,"html");
	 
	 $this->view->codigocaja=$codigo;
	 
	unset ($arraydatos);
	$arraydatos []= array ('@msquery',11);
	$rows=$cn->ejec_store_procedura_sql('[Rentas].[sp_cuentacorriente]',$arraydatos);
	$arRows= $ar->RegistrosCombo($rows,0,1);
	$val[]=array('#tipo_doc_sustento',$fn->ContenidoCombo($arRows,'[Seleccione]','',''),'html');
	 
	
	$fn->PintarValor($val);

	}
	
		public function coactivoAction()
	{
		$codigo=$this->_request->getParam('codigo','');

		$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;
		$ar = new Libreria_ArraysFunctions();
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#chkTotPeriodos',"click","marcaChecks(this,'chkperiodo[]')");
		$evt[] = array('#chkTotArbitrio',"click","marcaChecks(this,'chkarbitrio[]')");
		$evt[] = array('#chkTotAnios',"click","marcaChecks(this,'chkanio[]')");
		$evt[] = array('#chkTotPreddd',"click","marcaChecks(this,'predio[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");

		$evt[] = array('#btnPagarRecibos',"click","enviadeuda();");
		$evt[] = array('#btnMostrar',"click","mostrarRecContri(1);");
		//------------------agregó manuel----------------
		$evt[] = array('#btnSinInteres',"click","mostrarRecContri(2);");
		//-----------------------------------------------
		$evt[] = array('#btnVerRec',"click","mostrarRecibos();");
		$evt[] = array('#btnAnular',"click","anulaRecibos()");
		$evt[] = array('#btnEstCta',"click","imprimeCuentaPdf()");
		$evt[] = array('#btnCerrar',"click","closePopup('#poptesore');");
		$evt[] = array('#btnFraccionar',"click","fraccionar();");
		$evt[] = array('#btnMostrarAnulado',"click","mostrarRecibos();");
		$evt[] = array('#btnAjustecuenta',"click","imputardeuda();");
		
		$evt[] = array('#btnBaja',"click","getAjustecuenta();");
		$evt[] = array('#btnImputar',"click","getCompensacuenta();");

		//$evt[] = array('#btnBaja',"click","darbaja();");

		$fn->PintarEvento($evt);

		$nomcombo="rentas.sp_cuentacorriente";//"store_caja_framework";
		$arraydatos_nomcombo[]=array("@msquery",'2');//("@msquery",'1');
		$arraydatos_nomcombo[]=array("@codigo",$codigo);
		$cn = new Model_DbDatos_Datos();
	
		$rows = $cn->ejec_store_procedura_sql($nomcombo, $arraydatos_nomcombo);
		/*for ($i=0;$i<count($rows);$i++){*/
			$codigo=$rows[0][0];
			$nombre=$rows[0][1];
			//$doc=$rows[$i][2];
			$numdoc=$rows[0][3];
			$direccion=$rows[0][2];
			//$cantpred=$rows[$i][5];----------------
			//$apepater=$rows[$i][6];
			//$apemater=$rows[$i][7];
		//}
	//var_dump($rows);
	$contricaja = new Zend_Session_Namespace('contri');
    $contricaja->contri=$codigo;
    $contricaja->nombre=$nombre;
    $contricaja->numdoc=$numdoc;
    $contricaja->direccion=$direccion;
                
	 //divPredios
	 $val[] = array('#divCodigo',$codigo,"html");//en caso de de div html - input val	 
	 $val[] = array('#divContri',$nombre,"html");
	 $val[] = array('#divDirec',$direccion,"html");
	 $val[] = array('#divDocu',$numdoc,"html");
	 
	 $this->view->codigocaja=$codigo;
	 
	unset ($arraydatos);
	$arraydatos []= array ('@msquery',11);
	$rows=$cn->ejec_store_procedura_sql('[Rentas].[sp_cuentacorriente]',$arraydatos);
	$arRows= $ar->RegistrosCombo($rows,0,1);
	$val[]=array('#tipo_doc_sustento',$fn->ContenidoCombo($arRows,'[Seleccione]','',''),'html');
	 
	
	$fn->PintarValor($val);

	}
	
	
	
	public function prediosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();

			$codigo=$this->_request->getParam('codigocaja');
			
			$nombrestorepredios="store_caja_framework 15,@codigo='$codigo'";
			$rowpredios = $cn->ejec_store_procedura_sql($nombrestorepredios, null);
			$predios='';
			for ($i=0;$i<count($rowpredios);$i++){

				$predios.='<tr> <td><label style="font-size:10px;"><input type="checkbox" name="predio[]" id="predio[]" value="'.$var.$rowpredios[$i][2].$var.'" style="float:left;"> <div style="float:left; margin:0 0 5px 5px; width:280px">'.$rowpredios[$i][2].' - '.$rowpredios[$i][3].' '.utf8_encode($rowpredios[$i][4]).'</div></label></td>  </tr>';

			}

			$pred='<table width="100%">'.$predios."</table>";
			echo $pred;
		}		 
	}
	
	public function periodosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			$codigo=$this->_request->getParam('codigocaja');
			
			$nombrestoreperiodos="rentas.sp_cuentacorriente";//"store_caja_framework 5,@codigo='$codigo'";
			$arraydatos[]=array("@msquery",'4');//("@msquery",'1');
			$arraydatos[]=array("@codigo",$codigo);
			$rowperiodo = $cn->ejec_store_procedura_sql($nombrestoreperiodos, $arraydatos);
			$periodos='';
			for ($i=$rowperiodo[0][0];$i<=$rowperiodo[0][1];$i++){
				
				$periodos.='<tr> <td><label><input type="checkbox" name="chkperiodo[]" id="chkperiodo[]" value="'.$var.str_pad($i, 2, 0, STR_PAD_LEFT).$var.'"> '.str_pad($i, 2, 0, STR_PAD_LEFT).'</label></td>  </tr>';
	//			$periodos.='<tr> <td><label><input type="checkbox" name="chkperiodo[]" id="chkperiodo[]" value="'.$var.str_pad($i, 2, 0, STR_PAD_LEFT).$var.'" onclick="fraccionaperiodo(this);"> '.str_pad($i, 2, 0, STR_PAD_LEFT).'</label></td>  </tr>';

			}
			$periodo=$pred='<table width="100%">'.$periodos."</table>";
			echo $periodo;
		}
		 
	}

	public function aniosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			
			$nombrestoreanios="rentas.sp_cuentacorriente";//"store_caja_framework 6,@codigo='$codigo'";
			$codigo=$this->_request->getParam('codigocaja');
			$arraydatos[]=array("@msquery",5);
			$arraydatos[]=array("@codigo",$codigo);
			$rowanios = $cn->ejec_store_procedura_sql($nombrestoreanios, $arraydatos);
			$anios='';
			$j=0;
			for ($i=$rowanios[0][0];$i<=$rowanios[0][1];$i++){
				$brper='';
				$j=$j+1;
				if($j==12 || $j==24){
					$branio='<br>';
				}else{
					$branio='';
				}
				$var='*';
//				$i=$var.$i.$var;
				$anios.='<tr> <td><label><input type="checkbox" name="chkanio[]" id="chkanio[]" value='.$var.$i.$var.'> '.$i.''.$branio.'</label></td>  </tr>';
//				$anios.='<tr> <td><label><input type="checkbox" name="chkanio[]" id="chkanio[]" value='.$var.$i.$var.' onclick="fraccionaperiodo(this);"> '.$i.''.$branio.'</label></td>  </tr>';
				//$rowpredios[$i][0];

			}
			$anio='<table width="100%">'.$anios."</table>";

			echo $anio;
		}		 
	}
	
	public function anularreciboAction(){
		$fn = new Libreria_Pintar();
		$evt[] = array('#btnAceptar',"button","");
		$evt[] = array('#btnSalir',"button","");
		$evt[] = array('#btnRecibo',"button","");
		
		$evt[] = array('#btnAceptar',"click","aceptarRecibos();");
		$evt[] = array('#btnSalir',"click","closePopup('#popanularec');");
		//$evt[] = array('#btnRecibo',"click","mostrarRecContri();");
		$fn->PintarEvento($evt);
	}

	public function buscareciboAction(){
		$fn = new Libreria_Pintar();
		$evt[] = array('#btnAceptar',"button","");
		$evt[] = array('#btnSalir',"button","");
		$evt[] = array('#btnRecibo',"button","");
		$evt[] = array('#btnAceptar',"click","mostrarRecContri();");
		$evt[] = array('#btnSalir',"click","closePopup('#popanularec');");
		$evt[] = array('#btnRecibo',"click","mostrarRecContri();");
		
		$fn->PintarEvento($evt);
	}
	
	public function aceptarreciboAction(){
		
		$recibo = $this->_request->getPost('txtRecibo');
		
		$cn = new Model_DbDatos_Datos();
		
		$nombrestore="rentas.sp_cuentacorriente";//'Caja.sp_Recibos_emitidos';
		$arraydatos[]=array("@msquery", '7');
		$arraydatos[]=array("@num_ingr", $recibo);
		@$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		echo 'Recibo nro. '.$recibo.' anulado con exito';
	}
	
	public function conreccontriAction()
	{
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
		$str_annos='';
		$str_tipos='';
		$str_tiporec='';
		$str_periodos='';
		$str_predios='';
		
		$json = $this->_request->getPost('json');		
		$data_query = json_decode($json);

		$str_codigo=$data_query[5];
		//--------agrego manuel-----
		$str_flag=$data_query[7];
		//--------------------------
		$data_annos=$data_query[1];
		$data_tipos=$data_query[2];
		$data_tipo_deta=$data_query[3];
		$data_periodos=$data_query[0];
		$data_predios=$data_query[4];
		$str_estado=$data_query[6];
		//var_dump($data_tipos);
		//var_dump($data_tipo_deta);
		
		for($i=0; $i < count($data_annos); $i++){
            $str_annos .=$data_annos[$i].',';
        }
		$str_annos=substr($str_annos,0,-1);

		for($i=0; $i < count($data_tipos); $i++){
            $str_tipos .=$data_tipos[$i].',';
        }
		$str_tipos=substr($str_tipos,0,-1);
		
		for($i=0; $i < count($data_tipo_deta); $i++){
            $str_tiporec .=$data_tipo_deta[$i].',';
        }
		/*
		for($i=0; $i < count($data_tipos); $i++){
            $str_tiporec .=$data_tipos[$i].',';
        }
		*/
		$str_tiporec=substr($str_tiporec,0,-1);
		//echo $str_tiporec;
		for($i=0; $i < count($data_periodos); $i++){
            $str_periodos .=$data_periodos[$i].',';
        }
		$str_periodos=substr($str_periodos,0,-1);
		
		for($i=0; $i < count($data_predios); $i++){
            $str_predios .=$data_predios[$i].',';
        }
		
		$str_predios=substr($str_predios,0,-1);
		/*
			echo $str_annos.'<br>';
			echo $str_tipos;
			echo $str_periodos.'<br>';
			echo $str_predios.'<br>';
		*/
		//print_r($data_annos);
		//print_r($data_annos);
		//echo $str_annos;
		$codigo=$codigocontri;
		//$concept=$concept;//'"02.01","11.00","11.04"';
		//$arbitrio='"11.00"';
		//$aniose=$data_query[0][0];//'"2004","2005","2006","2007","2008","2009","2010","2011","2012"';
		//$perio=$periodos;//'"01","02","03","04","05","06","07","08","09","10","11","12"';
		//$preddd=$predios;//'"0000003"';

		if($str_flag=="1")
		$nombrestore='Caja.sp_EstCta_Rentas';
		else if($str_flag=="2")
		$nombrestore='Caja.sp_EstCta_Rentas_SinInteres';
		
		$arraydatos[]=array("@codigo", $str_codigo);
		$arraydatos[]=array("@annos", $str_annos);
		$arraydatos[]=array("@tipos", $str_tipos);
		$arraydatos[]=array("@tiporec", $str_tiporec);
		$arraydatos[]=array("@perio", $str_periodos);
		$arraydatos[]=array("@predio", $str_predios);
		$arraydatos[]=array("@estado", $str_estado);

		@$rows = $cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		
		$ntotal=count($rows);
		
		if($ntotal > 0){
			$jsonData = array('total'=>$ntotal,'rows'=>array());
			foreach($rows AS $row){
				$entry = array(
						'idrecibo'=>$row[0],				  
						'codigo'=>$row[1],
						'tipo'=>$row[2],
						'anno'=>$row[3],
						'cod_pred'=>$row[4],
						'anexo'=>$row[5],
						'sub_anexo'=>$row[6],
						'tipo_rec'=>$row[9],
						'periodo'=>$row[10],
						'imp_insol'=>$row[11],
						'fact_reaj'=>$row[13],
						'imp_reaj'=>$row[14],
						'fact_mora'=>$row[15],
						'mora'=>$row[16],
						'cost_emis'=>$row[12],						
						'estado'=>$row[18],
						'ubica'=>$row[19],
						'fec_venc'=>$ar->toDate($row[20]),
						'fec_pago'=>$ar->toDate($row[26]),
						'num_ingr'=>$row[21],
						'des_tipo'=>$row[27],
						'total'=>number_format($row[14]+$row[16]+$row[12],2)
				);
				$jsonData['rows'][] = $entry;
			}

			$this->view->data = json_encode($jsonData);
		}else{
			$this->view->data = 'No se encontraron registros';
		}

	}
	
	public function grabarimputarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
		
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
						
			$dataRecibo = $data->Recibo;
			
			
			if(count($dataRecibo)){
				foreach($dataRecibo as $dRecibo){
					unset($arraydatos);
					$arraydatos[]=array("@msquery", 8);
					$arraydatos[]=array("@idrecibo", $dRecibo->idrecibo);
					$arraydatos[]=array("@codigo", $dRecibo->codigo);
					$arraydatos[]=array("@anno", $dRecibo->anno);
					$arraydatos[]=array("@cod_pred", $dRecibo->cod_pred);					
					$arraydatos[]=array("@anexo", $dRecibo->anexo);
					$arraydatos[]=array("@sub_anexo", $dRecibo->sub_anexo);
					$arraydatos[]=array("@tipo", $dRecibo->tipo);
					$arraydatos[]=array("@tipo_rec", $dRecibo->tipo_rec);
					$arraydatos[]=array("@periodo", $dRecibo->periodo);
					$arraydatos[]=array("@imp_insol", $dRecibo->imp_insol);
					$arraydatos[]=array("@fact_reaj", $dRecibo->fact_reaj);
					$arraydatos[]=array("@fact_mora", $dRecibo->fact_mora);
					$arraydatos[]=array("@mora", $dRecibo->mora);
					$arraydatos[]=array("@cost_emis", $dRecibo->cost_emis);
					$arraydatos[]=array("@estado", $dRecibo->estado);
					$arraydatos[]=array("@ubica", $dRecibo->ubica);
					$arraydatos[]=array("@fec_venc", $dRecibo->fec_venc);
					$arraydatos[]=array("@fec_pago", $dRecibo->fec_pago);
					$arraydatos[]=array("@num_ingr", $dRecibo->num_ingr);
					$arraydatos[]=array("@doc_sustento", $data->doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $data->nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $data->tipo_doc_sustento);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
										
					@$rows = $cn->ejec_store_procedura_sql("Rentas.sp_cuentacorriente",$arraydatos);
					
				}
			}
				
		}		
	}
	/*public function grabarbajaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
						
			$dataRecibo = $data->Recibo;
			
			$estado = $data->compensar_o_baja;
						
			if(count($dataRecibo)){
				foreach($dataRecibo as $dRecibo){
					unset($arraydatos);
					$arraydatos[]=array("@msquery", 9);
					$arraydatos[]=array("@idrecibo", $dRecibo->idrecibo);
					$arraydatos[]=array("@codigo", $dRecibo->codigo);
					$arraydatos[]=array("@anno", $dRecibo->anno);
					$arraydatos[]=array("@cod_pred", $dRecibo->cod_pred);					
					$arraydatos[]=array("@anexo", $dRecibo->anexo);
					$arraydatos[]=array("@sub_anexo", $dRecibo->sub_anexo);
					$arraydatos[]=array("@tipo", $dRecibo->tipo);
					$arraydatos[]=array("@tipo_rec", $dRecibo->tipo_rec);
					$arraydatos[]=array("@periodo", $dRecibo->periodo);
					$arraydatos[]=array("@imp_insol", $dRecibo->imp_insol);
					$arraydatos[]=array("@fact_reaj", $dRecibo->fact_reaj);
					$arraydatos[]=array("@fact_mora", $dRecibo->fact_mora);
					$arraydatos[]=array("@mora", $dRecibo->mora);
					$arraydatos[]=array("@cost_emis", $dRecibo->cost_emis);
					$arraydatos[]=array("@estado", '2');
					$arraydatos[]=array("@ubica", $dRecibo->ubica);
					$arraydatos[]=array("@fec_venc", $dRecibo->fec_venc);
					$arraydatos[]=array("@fec_pago", $dRecibo->fec_pago);
					$arraydatos[]=array("@num_ingr", $dRecibo->num_ingr);
					$arraydatos[]=array("@doc_sustento", $data->doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $data->nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $data->tipo_doc_sustento);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
					
					//@$rows = $cn->ejec_store_procedura_sql("Rentas.sp_cuentacorriente",$arraydatos);
					
				}
			}
			
		}		
	}*/
	public function grabarbajaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			$dataform=$this->_request->getPost('dataform');
			$nro_doc_sustento=$this->_request->getPost('nro_doc_sustento');
			$doc_sustento=$this->_request->getPost('doc_sustento');
			$tipo_doc_sustento=$this->_request->getPost('tipo_doc_sustento');
			
			$txtMontoajustar = $this->_request->getPost('txtMontoajustar');
			$txt_total = $this->_request->getPost('txt_total');
						
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			$dxml = '';
			foreach ($data as $key => $value){ 
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			echo $data;
			
					
					unset($arraydatos);
					$arraydatos[]=array("@dataxml", $dxml);
					$arraydatos[]=array("@montoacuenta", $txtMontoajustar);
					$arraydatos[]=array("@monto", $txt_total);
					$arraydatos[]=array("@doc_sustento", $doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $tipo_doc_sustento);
					$arraydatos[]=array("@tipo_baja", 2);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
					$rows = $cn->ejec_store_procedura_sql("[Rentas].[sp_Anularrecibos]",$arraydatos);
						
				
			}
			
		}

	public function grabarcompensacionAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$login = new Zend_Session_Namespace('login');
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			$login = new Zend_Session_Namespace('login');
			$dataform=$this->_request->getPost('dataform');
			$nro_doc_sustento=$this->_request->getPost('nro_doc_sustento');
			$doc_sustento=$this->_request->getPost('doc_sustento');
			$tipo_doc_sustento=$this->_request->getPost('tipo_doc_sustento');
			
			$txtMontoajustar = $this->_request->getPost('txtMontoajustar');
			$txt_total = $this->_request->getPost('txt_total');
						
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			$dxml = '';
			foreach ($data as $key => $value){ 
				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}
			echo $data;
			
					
					unset($arraydatos);
					$arraydatos[]=array("@dataxml", $dxml);
					$arraydatos[]=array("@montoacuenta", $txtMontoajustar);
					$arraydatos[]=array("@monto", $txt_total);
					$arraydatos[]=array("@doc_sustento", $doc_sustento);
					$arraydatos[]=array("@nro_doc_sustento", $nro_doc_sustento);
					$arraydatos[]=array("@tipo_doc_sustento", $tipo_doc_sustento);
					$arraydatos[]=array("@tipo_baja", 0);
					$arraydatos[]=array("@operador", $login->user);
					$arraydatos[]=array("@estacion", php_uname('n'));
					$rows = $cn->ejec_store_procedura_sql("[Rentas].[sp_Compensarrecibos]",$arraydatos);
						
				
			}
			
		}		
	
	
	public function mostrarrecibosAction(){
		
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		//$idrecibo=$this->_request->getParam('idrecibo');
		$codigo=$this->_request->getParam('codigo');
		
		$nombrestore="rentas.sp_cuentacorriente";//'Caja.sp_Recibos_emitidos';
		$arraydatos[]=array("@msquery", 10);		
		$arraydatos[]=array("@codigo",$codigo);
		//$arraydatos[]=array("@idrecibo", $idrecibo);		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);		
		
		//print_r($rowpagos);
		for($i = 0; $i < count ( $rows ); $i ++) {
			
			switch(trim($rows[$i][0])){
				case 'Imputado': $color='pink'; break;
				case 'Anulado': $color='skyblue'; break;
			}
			
			$detalle .= '<tr style="background-color:'.$color.'"  >';
			$detalle .= '<td width="87" align="center">' . $rows [$i] [0] . '</td>';//Estado
			$detalle .= '<td width="40" align="center">' . $rows [$i] [1] . '</td>';//codigo
			$detalle .= '<td width="78" align="center">' . $rows [$i] [3] . '</td>';//idrecibo
			$detalle .= '<td width="70" align="center">' . $rows [$i] [4] . '</td>';//anno
			$detalle .= '<td width="70" align="center">' . $rows [$i] [12] . '</td>';//anno
			$detalle .= '<td width="70" align="center">' . $rows [$i] [11] . '</td>';//DEscrip
			$detalle .= '<td width="70" align="center">' . $rows [$i] [8] . '</td>';//Insol
			$detalle .= '<td width="70" align="center">' . $rows [$i] [9] . '</td>';//Reaj
			$detalle .= '<td width="70" align="center">' . $rows [$i] [10] . '</td>';//mora
			
			$detalle .= '<td width="70" align="center">' . $rows [$i] [6] . '</td>';//fecha_ing
			$detalle .= '<td width="70" align="center">' . $rows [$i] [7] . '</td>';//fecha_ing
			$detalle .= '<td width="70" align="center">' . $rows [$i] [4] . '</td>';//fecha_ing
			$detalle .= '<td width="70" align="center">' . $rows [$i] [5] . '</td>';//fecha_ing
			
			$detalle .= '</tr>';
		}
		$this->view->datarows=$detalle;
	}
	public function registrarajusteAction(){
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		$this->_helper->Layout->disableLayout();		
		$codigo = $this->_request->getPost('codigo');
		$json = $this->_request->getPost('json');
		$formaPago = $this->_request->getPost('rdformaPago');
		$cmbTarjeta = $this->_request->getPost('cmbTarjeta');
		$cmbBanco = $this->_request->getPost('cmbBanco');
		$txtCheque = $this->_request->getPost('txtCheque');
		$txtFecCobro = $this->_request->getPost('txtFecCobro');
		$txtObservacion = $this->_request->getPost('txtObservacion');
		$txtCobrar = $this->_request->getPost('txtCobrar');
		$txtEfectivo = $this->_request->getPost('txtEfectivo');
		
		
	}
	
	
}