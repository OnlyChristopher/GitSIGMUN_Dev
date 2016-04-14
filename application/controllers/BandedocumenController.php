<?php

/**
 * BandedocumenController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class BandedocumenController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$path = new Zend_Session_Namespace('path');
		
		//$path->id_user ='0000001';
		//$user=$path->id_user;
		
		
		$this->view->ruta = $path->data;
		
		$this->view->title = "Bandeja de Documentos";
		
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBusBandeja',"click","buscarBandeja()");
		
		$evt[] = array('#btnRecibidos',"click","buscarBandejaEst('1')");
		
		$evt[] = array('#btnEspera',"click","buscarBandejaEst('0')");
		
		//$evt[] = array('#btnEnviar',"click","");
		
		$evt[] = array('#btnEnviar',"click","enviardocumento()");
		
		$evt[] = array('#btnVer',"click","enviarruta()");
		
		$evt[] = array('#btnVerdocumento',"click","verdocumento()");
		
		$evt[] = array('#btnExpendiente',"click","expediente()");
		
		$evt[] = array('#btnRecepcion',"click","recepcion()");
		
		$evt[] = array('#btnRecep',"click","recepbandeja()");
		
		//$evt[] = array('#btnExigibilidad',"click","exigibilidad()");
		
		$evt[] = array('#btnAgrupamiento',"click","exigibilidadgrupal()");
		
		$evt[] = array('#btnMostrar',"click","mostrar()");
		
		$evt[] = array(".chcuponera","click","selectexigibilidad(this)");
		
		$evt[] = array('#btnGenerarexigibilidad',"click","exigibilidad()");
		
		//$evt[] = array("#btnGenerarexigibilidad","click","GenerarExigibilidad()");
		
		// $parametros[] = array('@msquery',2);
		// $rowsTotal = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		// $contador=$rowsTotal[0][0];
		
		
		// $this->view->conta=$contador;
					
		$fn->PintarEvento($evt);
	}
	
    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
		$login = new Zend_Session_Namespace('login');
	
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
		$estado = $_REQUEST['estado'];
		
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'T': $contribuyente = $criterio;break;
    		case 'D': $documento = $criterio;break;
    		
    	}
    	
    	//Para el total
    	
    	$parametros[] = array('@msquery',2);
    	$parametros[] = array('@codigo',$codigo);
    	$parametros[] = array('@unombre',$contribuyente);
		$parametros[] = array('@num_val',$documento);
		$parametros[] = array('@area_user',$login->area);
		$parametros[] = array('@recep',$estado);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		//$contador=$rowsTotal[0][0];
	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',1);
    	$parametros[] = array('@codigo',$codigo);
    	$parametros[] = array('@unombre',$contribuyente);
		$parametros[] = array('@num_val',$documento);
		$parametros[] = array('@area_user',$login->area);
		$parametros[] = array('@recep',$estado);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'contribuyente'=>utf8_encode($row[1]),
						'documento'=>$row[2]."-".$row[3]."-".$row[4],
						'monto'=>$row[5],
						'fecha'=>$row[6],
						'valor'=>$row[7],
						'num'=>$row[3],
						'ano'=>$row[4],
						'numero'=>$row[8],
						'anio'=>$row[9],
						'orden'=>$row[11],
						'id'=>$row[12],
						'flag'=>$row[13],
						'flag1'=>$row[14],
						'numexig'=>$row[15],
						'anoexig'=>$row[16],		
						'lote'=>$row[17]
				);
			
				$jsonData['rows'][] = $entry;
			}
			
    	}
		$this->view->data = json_encode($jsonData);
		
		
    }
    
    public function formuAction()
    { 
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnArea',"button","");
		$evt[] = array('#btnUsuario',"button","");
		$evt[] = array('#btnEnvia',"button","");
		$evt[] = array('#btnSalida',"button","");
		
		$evt[] = array('#txtareas',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtareas',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#btnSalida',"click","closePopup('#popupenvios');");
		
		$evt[] = array('#btnEnvia',"click","goToFormulario('frmbandeja');");
		
		$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);
		
		$rowBandeja = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		
		$codigo = $rowBandeja[0][0]; 
		$contribuyente = $rowBandeja[0][1]; 
		
		$num = $rowBandeja[0][3];
		$ano = $rowBandeja[0][4];
		
		$documento = $rowBandeja[0][2]."-".$rowBandeja[0][3]."-".$rowBandeja[0][4];
		
		
		
		$val[] = array('#txtValor',$cod,'val');
		$val[] = array('#txtNum',$num,'val');
		$val[] = array('#txtAno',$ano,'val');
		
		$val[] = array('#lblDocumento',$documento,'html');
		
		$val[] = array('#lblCodigo',$codigo,'html');
		$val[] = array('#lblContribuyente',$contribuyente,'html');
		
		
		unset($parametros);
		$parametros[] = array('@msquery',3);	
		$comboarea = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		$arArea = $ar->RegistrosCombo($comboarea,0,1);
		$val[] = array('#cmbAreades',$fn->ContenidoCombo($arArea,'[Seleccione]',''),'html');
		

		unset($parametros);
		$parametros[] = array('@msquery',7);	
		$combosituacion = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		$arSituacion = $ar->RegistrosCombo($combosituacion,0,1);
		$val[] = array('#cmbSituacion',$fn->ContenidoCombo($arSituacion,'[Seleccione]',''),'html');
		
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
    
	public function consusarioAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$area = $this->_request->getPost('area');
			
			$combostore1 ='Rentas.SP_Mvalores';
			$arraydatos1[0] = array("@msquery",5);
			$arraydatos1[1] = array("@area",$area);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			$cb_Areausu='<option value="">[Seleccione]</option>';
    
			for ($i=0;$i<count($rows1);$i++){
            	$cb_Areausu.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
        	}
		
			echo $cb_Areausu;
    	}  
    }
    
    public function fechaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$parametros[] = array('@msquery',6);
			$rows = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
			
			echo $rows[0][0];
    	}   
    }
    
    public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$codvalor = $this->_request->getPost('txtValor');
			$num_val=$this->_request->getPost('txtNum');
			$ano_val=$this->_request->getPost('txtAno');
			$area_destin=$this->_request->getPost('txtAreades');
			$fec_envios=$this->_request->getPost('txtSalida');
			$user_origen=$this->_request->getPost('txtUsuario');
			$observacion=$this->_request->getPost('txtareas');
			$situacion=$this->_request->getPost('cmbSituacion');
			
			//id_valor,num_val,ano_val,area_origen,fec_envios,fech_ing	
			$parametro1[] = array('@msquery',2);
			$parametro1[] = array('@id_valor',$codvalor);
			$parametro1[] = array('@num_val',$num_val);
			$parametro1[] = array('@ano_val',$ano_val);
			$parametro1[] = array('@area_destin',$area_destin);
			$parametro1[] = array('@fec_envios',$fec_envios);
			$parametro1[] = array('@user_destin',$user_origen);
			$parametro1[] = array('@observacion',$observacion);
			@$rows = $cn->ejec_store_procedura_sql('Rentas.SP_MHRuta', $parametro1);
			
			$parametro2[] = array('@msquery',1);
			$parametro2[] = array('@id_valor',$codvalor);
			$parametro2[] = array('@num_val',$num_val);
			$parametro2[] = array('@ano_val',$ano_val);
			$parametro2[] = array('@area_destin',$area_destin);
			$parametro2[] = array('@fec_envios',$fec_envios);
			$parametro2[] = array('@user_origen',$user_origen);
			$parametro2[] = array('@observacion',$observacion);
			$parametro2[] = array('@id_situac',$situacion);
			@$rows = $cn->ejec_store_procedura_sql('Rentas.SP_MHRuta', $parametro2);
			
			echo "Se grabo correctamente";
    	}  
    }
    
    public function rutaAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnSalidaruta',"button","");
		$evt[] = array('#btnSalidaruta',"click","closePopup('#popupruta');");
		
		$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		
		$this->view->valor = $cod;//nuevo
		$this->view->num = $num;//nuevo
		$this->view->ano = $ano;//nuevo
		
		//$this->view->valor = '01';//nuevo
		//$this->view->num = '000001';//nuevo
		//$this->view->ano = '2013';//nuevo
		
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);
		$rowBandeja = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		$documento = $rowBandeja[0][1];
		$codigo = $rowBandeja[0][0]; 
		$contribuyente = $rowBandeja[0][1]; 
		
		$num = $rowBandeja[0][3];
		$ano = $rowBandeja[0][4];
		
		$documento = $rowBandeja[0][2]."-".$rowBandeja[0][3]."-".$rowBandeja[0][4];
		
		//'documento'=>$row[2]."-".$row[3]."-".$row[4],
		
		$val[] = array('#txtValorruta',$cod,'val');
		$val[] = array('#txtNumruta',$num,'val');
		$val[] = array('#txtAnoruta',$ano,'val');
		
		$val[] = array('#lblDocumentoruta',$documento,'html');
		
		$val[] = array('#lblCodigoruta',$codigo,'html');
		$val[] = array('#lblContribuyenteruta',$contribuyente,'html');
    
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
    
    public function detallerutaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$valor = $this->_request->getParam('valor',''); //recupero 
    	$num_val=$this->_request->getParam('num','');
		$ano_val=$this->_request->getParam('ano','');
    	
    	$parametros[] = array('@msquery',3);
    	$parametros[] = array('@id_valor',$valor); 
    	$parametros[] = array('@num_val',$num_val);
    	$parametros[] = array('@ano_val',$ano_val);
    	    	
    	$rowRuta = $cn->ejec_store_procedura_sql('Rentas.SP_MHRuta', $parametros);
    	
    	$jsonData = array('total'=>count($rowRuta),'rows'=>array());
    	foreach($rowRuta AS $row){
			$entry = array(
					'orden'=>utf8_encode($row[0]),
					'areaorigen'=>utf8_encode($row[1]),
					'usuarioorigen'=>$row[2],
					'fechaorigen'=>$row[3],
					'areadestino'=>utf8_encode($row[4]),
					'usuariodestino'=>$row[5],
					'fechadestino'=>$row[6],
					'observacion'=>$row[7],
					'situacion'=>$row[8],
					'frecepcion'=>$row[9]
			);
			$jsonData['rows'][] = $entry;
		}
		
		$this->view->data = json_encode($jsonData);
    }
    
    public function verdocumentoAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
    	$fn = new Libreria_Pintar();
    	
  
		$evt[] = array('#btnEnviadocu',"button","");
		$evt[] = array('#btnSalidadocu',"button","");
		$evt[] = array('#btnReporteop',"button","");
		
		$evt[] = array('#btnReporteop',"click","imprimeOpPdf()");
		
		$evt[] = array('#btnSalidadocu',"click","closePopup('#popupdocumento');");
    	
		$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		$parametros[] = array('@msquery',4);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);		
		$rowBandeja = $cn->ejec_store_procedura_sql('Rentas.SP_Mvalores', $parametros);
		
		$codigo = $rowBandeja[0][0]; 
		$contribuyente = $rowBandeja[0][1]; 
		
		$num = $rowBandeja[0][3];
		$ano = $rowBandeja[0][4];
		
		$documento = $rowBandeja[0][2]."-".$rowBandeja[0][3]."-".$rowBandeja[0][4];
		
		
		
		$val[] = array('#txtValordocu',$cod,'val');
		$val[] = array('#txtNumdocu',$num,'val');
		$val[] = array('#txtAnodocu',$ano,'val');
		
		$val[] = array('#lblVerdocu',$documento,'html');
		
		$val[] = array('#lblCodigodocu',$codigo,'html');
		$val[] = array('#lblContribuyentedocu',$contribuyente,'html');
		
		
		$parametros[0] = array("@msquery",1);
	    $rows1 = $cn->ejec_store_procedura_sql('Rentas.SP_Dvalores',$parametros);
	    $cad1='';
		
   		for($i = 0; $i < count ( $rows1 ); $i ++) {
   			  
   			  if($rows1[$i][1]==0)
   			  {
   			  	$cad1.="<tr>";
	    		$cad1.="<td><input type='checkbox' onclick='mostrardetalle(this.checked,".$rows1[$i][8].")'></td>";
	    		$cad1.="<td><b>".$rows1[$i][2]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][3]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][4]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][5]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][6]."</b></td>";
				$cad1.="<td align='right'><b>".$rows1[$i][7]."</b></td></tr>";
   			  }
 	    	  else{ 
	    	  	$cad1.="<tr class='chk".$rows1[$i][8]."' style='display:none'>";	
	    	    $cad1.="<td></td>";
	    	    $cad1.="<td align='center'>".$rows1[$i][2]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][3]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][4]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][5]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][6]."</td>";
				$cad1.="<td align='right'>".$rows1[$i][7]."</td></tr>";
	    	  } /*	    	  	
	    		$cad1.="<td>".$rows1[$i][2]."</td>";
				$cad1.="<td>".$rows1[$i][3]."</td>";
				$cad1.="<td>".$rows1[$i][4]."</td>";
				$cad1.="<td>".$rows1[$i][5]."</td>";
				$cad1.="<td>".$rows1[$i][6]."</td>";
				$cad1.="<td>".$rows1[$i][7]."</td></tr>";*/
		}
		
		$val[] = array('#detallesDocumento',$cad1,'append');

		unset($parametros);
		$parametros[0] = array("@msquery",2);
		$parametros[1] = array('@id_valor',$cod);
		$parametros[2] = array('@num_val',$num);
		$parametros[3] = array('@ano_val',$ano);	
		$rowMonto = $cn->ejec_store_procedura_sql('Rentas.SP_Dvalores',$parametros);
		
		$montototal = $rowMonto[0][0]; 
		
		$val[] = array('#txtMontototal',$montototal,'val');
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    }
    
    public function expedienteAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
    	$fn = new Libreria_Pintar();
		
		$json = $this->_request->getPost('json');
		$data = json_decode($json);
		
    	$evt[] = array('#btnGrabaexpe',"button","");
		$evt[] = array('#btnSalidaexpe',"button","");
		$evt[] = array('#btnImpriexpe',"button","");
		$evt[] = array('#btnImpriexpece',"button","");
		
		$evt[] = array('#btnGrabaexpe',"click","goToFormulario('frmexpediente');");
		
		$evt[] = array('#txtFechaexpe',"datepicker","");		
		$evt[] = array('#txtFechaexpeproyec',"datepicker","");   
    	
		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexpediente');");
		
		$evt[] = array('#txtObservaciones',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtObservaciones',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtNroexpe',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#txtAniosexpe',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtAniosexpe',"keypress","return validaTeclas(event,'alpha');");
		
		$evt[] = array('#txtMemo',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtMemo',"keypress","return validaTeclas(event,'alpha');");
		
		$evt[] = array('#btnImpriexpe',"click","imprimeExpediente()");
		
		$evt[] = array('#btnImpriexpece',"click","imprimeCedula()");
		
		$numexpe=trim($this->_request->getParam('numero',''))=="0000000" ? "": trim($this->_request->getParam('numero',''));
		$anioexpe=trim($this->_request->getParam('anio',''))=="0000" ? "": trim($this->_request->getParam('anio',''));
		
		
		$numexpe=trim($data[0]->numero)==="0000000" ? "": trim($data[0]->numero);
		$anioexpe=trim($data[0]->anio)=="0000" ? "": trim($data[0]->anio);
		

		$val[] = array('#txtValorexpe',$data[0]->valor,'val');
		$val[] = array('#txtNumexpe',$data[0]->num,'val');
		$val[] = array('#txtAnoexpe',$data[0]->ano,'val');
		$val[] = array('#txtCodigoex',$data[0]->codigo,'val');
		
		$val[] = array('#txtNumeroexpe',$numexpe,'val');
		$val[] = array('#txtAnioexpe',$anioexpe,'val');
				
		if(!empty($numexpe) && !empty($anioexpe))
		{
			unset($parametros);
			$parametros[] = array("@msquery",9);
			//$parametros[1] = array("@codigo",$data[0]->codigo);
			$parametros[] = array("@num_exp",$data[0]->numero);
			$parametros[] = array("@ano_exp",$data[0]->anio);
			$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente', $parametros);
			
			$numexpe = $rows [0] [2];
			$anioexpe = $rows [0] [3];	
			$ejecutivo = $rows [0] [5];
			$auxiliar = $rows [0] [6];		
			$fecha	=	$rows [0] [7];
			$observaciones = $rows [0] [8];
			$memo = $rows [0] [10];
			$fecha_proyec_rec	=	$rows [0] [11];
			
			unset($parametros);
			$parametros[] = array('@msquery',2);
			$rowsEjecutivo = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente',$parametros);
			$cb_Ejecutivo='<option value="">[Seleccione]</option>';
			
			for ($i=0;$i<count($rowsEjecutivo);$i++)
			{
				if(trim($rowsEjecutivo[$i][0])==trim($rows[0][5]))
				{
					$cb_Ejecutivo.='<option value="'.$rowsEjecutivo[$i][0].'" selected>'.$rowsEjecutivo[$i][1].'</option>';
				}
				else{
					$cb_Ejecutivo.='<option value="'.$rowsEjecutivo[$i][0].'" >'.$rowsEjecutivo[$i][1].'</option>';						
				}
        	}
        	
        	unset($parametros);
        	$parametros[] = array('@msquery',3);
			$rowsAuxiliar = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente',$parametros);
			$cb_Auxiliar='<option value="">[Seleccione]</option>';
			
			for ($i=0;$i<count($rowsAuxiliar);$i++)
			{
				if(trim($rowsAuxiliar[$i][0])==trim($rows[0][6]))
				{
					$cb_Auxiliar.='<option value="'.$rowsAuxiliar[$i][0].'" selected>'.$rowsAuxiliar[$i][1].'</option>';
				}
				else{
					$cb_Auxiliar.='<option value="'.$rowsAuxiliar[$i][0].'" >'.$rowsAuxiliar[$i][1].'</option>';						
				}
			}
			
			
			$cad3='';
			$suma=0;
			for($i = 0; $i < count ($rows); $i ++) 
			{
				$suma += $rows[$i][1];
				$cad3 .= "<tr>";
				$cad3 .= "<td>".$rows[$i][0]."</td>";
				$cad3 .= "<td align='right'>".$rows[$i][1]."</td>";
				$cad3 .= "</tr>";
			}
		
			$val[] = array('#detallesExpediente',$cad3,'append');
			

	    	$val[] = array('#cmbEjecutivo',$cb_Ejecutivo,'html');	
	        $val[] = array('#cmbAuxiliar',$cb_Auxiliar,'html');
	        
	        $cad[] = array('#popup_popupexpediente input',"readonly","true");
       		$cad[] = array('#popup_popupexpediente select',"disabled","true");
       		$cad[] = array('#popup_popupexpediente textarea',"disabled","true");
       		$cad[] = array('#btnGrabaexpe',"disabled","true");
			
       		$val[] = array('#popup_popupexpediente input',"caja","removeClass");
			$val[] = array('#popup_popupexpediente input',"cajaoff","addClass");
			$val[] = array('#popup_popupexpediente textarea',"caja","removeClass");
			$val[] = array('#popup_popupexpediente textarea',"cajaoff","addClass");
	        
        		
		}
		
		else
		{
			unset($parametros);
			$parametros[] = array('@msquery',2);
			$rowsEjecutivo = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente',$parametros);
			$cb_Ejecutivo='<option value="">[Seleccione]</option>';		
			
	    	for ($i=0;$i<count($rowsEjecutivo);$i++){
	            	$cb_Ejecutivo.='<option value="'.$rowsEjecutivo[$i][0].'" >'.$rowsEjecutivo[$i][1].'</option>';
	        }
	        $val [] = array('#cmbEjecutivo',$cb_Ejecutivo,'html');
	        
	    	unset($parametros);
			$parametros[] = array('@msquery',3);
			$rowsAuxiliar = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente',$parametros);
			$cb_Auxiliar='<option value="">[Seleccione]</option>';		
			
	    	for ($i=0;$i<count($rowsAuxiliar);$i++){
	            	$cb_Auxiliar.='<option value="'.$rowsAuxiliar[$i][0].'" >'.$rowsAuxiliar[$i][1].'</option>';
	        }
			$val [] = array('#cmbAuxiliar',$cb_Auxiliar,'html');
			
			$cad2='';
			$suma=0;
			for($i = 0; $i < count ($data); $i ++) 
			{
				$suma += $data[$i]->monto;
				$cad2 .= "<tr>";
				$cad2 .= "<td>";
				$cad2 .= "<input type='hidden' id='d_id_valor[]' name='d_id_valor[]' value='".$data[$i]->valor."' />";
				$cad2 .= "<input type='hidden' id='d_num_val[]' name='d_num_val[]' value='".$data[$i]->num."' />";
				$cad2 .= "<input type='hidden' id='d_ano_val[]' name='d_ano_val[]' value='".$data[$i]->ano."' />";
				$cad2 .= $data[$i]->documento;
				$cad2 .= "</td>";
				$cad2 .= "<td align='right'>".$data[$i]->monto."</td>";
				$cad2 .= "</tr>";
			}
			
			$val[] = array('#detallesExpediente',$cad2,'append');
			$cad[] = array('#btnImpriexpe',"disabled","true");
			$cad[] = array('#btnImpriexpece',"disabled","true");
			
		}
		        
	    $txtTotal=str_replace(",","",number_format($suma,2));
	    
	    //$val[] = array('#txtTotal',number_format($suma,2),'val');
		$val[] = array('#txtTotal',$txtTotal,'val'); 
	    $val[] = array('#txtNroexpe',$numexpe,'val');
		$val[] = array('#txtAniosexpe',$anioexpe,'val');
		$val[] = array('#txtCodigoexpe',$data[0]->codigo,'val');
		$val[] = array('#txtContribuyenteexpe',$data[0]->contribuyente,'val');
		$val[] = array('#txtEjecutivo',$ejecutivo,'val');
		$val[] = array('#txtAuxiliar',$auxiliar,'val');
		$val[] = array('#txtFechaexpe',$fecha,'val');
		$val[] = array('#txtObservaciones',$observaciones,'val');
		$val[] = array('#txtMemo',$memo,'val');
		$val[] = array('#txtFechaexpeproyec',$fecha_proyec_rec,'val');
	   // $val [] = array ('#txtTotal',$monto,'val');
	    
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		if(count($cad)>0){
			$fn->AtributoComponente($cad);
		}
		
    }
    
	
	
    public function grabarexpedienteAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if($this->getRequest()->isXmlHttpRequest()){
    		
    		$cn = new Model_DbDatos_Datos();
    		
			$d_id_valor = $this->_request->getPost('d_id_valor');
			$d_num_val = $this->_request->getPost('d_num_val');
			$d_ano_val = $this->_request->getPost('d_ano_val');
			
			
    		$parametros[] = array('@msquery',6);
    		$parametros[] = array('@num_exp',$this->_request->getPost('txtNroexpe'));
    		@$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente', $parametros);
    		
    		if(count($rows))
				echo 'El Nro de expediente ingresado ya existe';
			else
			{  
				unset($parametros);	 		
				$parametros[] = array('@msquery',4);
				$parametros[] = array('@num_exp',$this->_request->getPost('txtNroexpe'));
				$parametros[] = array('@ano_exp',$this->_request->getPost('txtAniosexpe'));
				$parametros[] = array('@codigo',$this->_request->getPost('txtCodigoexpe'));
				$parametros[] = array('@id_ejec',$this->_request->getPost('txtEjecutivo'));
				$parametros[] = array('@id_auxi',$this->_request->getPost('txtAuxiliar'));
				$parametros[] = array('@fec_gen',$this->_request->getPost('txtFechaexpe'));
				$parametros[] = array('@montota',$this->_request->getPost('txtTotal'));
				$parametros[] = array('@observacion',$this->_request->getPost('txtObservaciones'));	
				$parametros[] = array('@memo',$this->_request->getPost('txtMemo'));
				$parametros[] = array('@fech_proyec_rec',$this->_request->getPost('txtFechaexpeproyec'));
				@$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente', $parametros);
				
				//Guarda detalle
				for($i=0;$i<count($d_id_valor);$i++){
					unset($parametro2);
					$parametro2[] = array('@msquery',5);
					$parametro2[] = array('@id_valor',$d_id_valor[$i]);
					$parametro2[] = array('@num_val',$d_num_val[$i]);
					$parametro2[] = array('@ano_val',$d_ano_val[$i]);					
					$parametro2[] = array('@num_exp',$this->_request->getPost('txtNroexpe'));
					$parametro2[] = array('@ano_exp',$this->_request->getPost('txtAniosexpe'));
					$parametro2[] = array('@fech_proyec_rec',$this->_request->getPost('txtFechaexpeproyec'));
					$parametros[] = array('@codigo',$this->_request->getPost('txtCodigoexpe'));
					@$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente', $parametro2);
				}
				
				echo "Se grabo correctamente";
			}
			
    	}
    }
    
    
		
    public function recepcionAction()
    {
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
    	$fn = new Libreria_Pintar();
    	
    	$evt[] = array('#btnGrabarecep',"button","");
		$evt[] = array('#btnSalidarecep',"button","");
		
		$evt[] = array('#btnGrabarecep',"click","goToFormulario('frmrecepcion');");
		
		$evt[] = array('#txtFecharecep',"datepicker","");
	 	
		$evt[] = array('#btnSalidarecep',"click","closePopup('#popuprecepcion');");
		
		$evt[] = array('#txtNotificador',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNotificador',"blur","this.value = this.value.toUpperCase();");
    	
		$evt[] = array('#txtRecepcionista',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtRecepcionista',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtParentesco',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtParentesco',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtSuministro',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtSuministro',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtObservaciones',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtObservaciones',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtDocumento',"keypress","return validaTeclas(event,'number');");
		
		$evt[] = array('#cmbDocumento',"change","validaText($(this).val());");	
						
    	$cod = $this->_request->getParam('valor','');
		$num = $this->_request->getParam('num',''); 
		$ano = $this->_request->getParam('ano','');
		
		$parametros[] = array('@msquery',1);
		$parametros[] = array('@id_valor',$cod);
		$parametros[] = array('@num_val',$num);
		$parametros[] = array('@ano_val',$ano);		
		$rowRecepcion = $cn->ejec_store_procedura_sql('Coactivo.SP_MExpediente', $parametros);
		
		$documento = $rowRecepcion[0][2]."-".$rowRecepcion[0][3]."-".$rowRecepcion[0][4];
		
		
		$val[] = array('#txtValorrecep',$cod,'val');
		$val[] = array('#txtNumrecep',$num,'val');
		$val[] = array('#txtAnorecep',$ano,'val');
		
		$val[] = array('#lblDocumentorecep',$documento,'html');
		
		
		/*
		$chkfirmo=0;
		//unset($parametros);
		 */		
		//unset($parametros);
		$chkfirmo=0;
		$parametros2[] = array('@msquery',3);
		$parametros2[] = array('@id_valor',$cod);
		$parametros2[] = array('@num_val',$num);
		$parametros2[] = array('@ano_val',$ano);	
		
		$rowsExpediente = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion', $parametros2);
		
		if(count($rowsExpediente)>0)
		{
			$id_valor = $rowsExpediente [0] [0];
			$num_val = $rowsExpediente [0] [1];			
			$ano_val = $rowsExpediente [0] [2];
			$nombnoti	=	$rowsExpediente [0] [3];
			$nombrecepci = $rowsExpediente [0] [4];					
			$nro_docu	=	$rowsExpediente [0] [6];
			$parentes =$rowsExpediente [0] [7];
			$fec_rece = $rowsExpediente [0] [8];			
			$suministro = $rowsExpediente [0] [9];
			$observacion = $rowsExpediente [0] [10];
			$chkfirmo	=	$rowsExpediente [0] [11];
			
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$cb_Documento='';
			
			for ($i=0;$i<count($rowsDocumento);$i++){
				$cad=explode('/',$rowsDocumento[$i][0]);
				
				if(trim($cad[0])==$rowsExpediente[0][5])
				{
					$cb_Documento.='<option value="'.$rowsDocumento[$i][0].'" selected>'.$rowsDocumento[$i][1].'</option>';
				}
				else{
					$cb_Documento.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';						
				}
       		}
       		$val [] = array('#cmbDocumento',$cb_Documento,'html');	
       		
       		$cad[] = array('#popup_popuprecepcion input',"readonly","true");
       		$cad[] = array('#popup_popuprecepcion select',"disabled","true");
       		$cad[] = array('#popup_popuprecepcion textarea',"disabled","true");
       		$cad[] = array('#btnGrabarecep',"disabled","true");
       	    $cad[] = array('#chbFirmo',"disabled","true");
       		
			$val[] = array('#popup_popuprecepcion input',"caja","removeClass");
			$val[] = array('#popup_popuprecepcion input',"cajaoff","addClass");
			$val[] = array('#popup_popuprecepcion textarea',"caja","removeClass");
			$val[] = array('#popup_popuprecepcion textarea',"cajaoff","addClass");
		}
		else 
		{
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$cb_Documento='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){
	            	$cb_Documento.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';
	        }
	        $val [] = array('#cmbDocumento',$cb_Documento,'html');
		}
		
				
		//$cad[] = array("#chbFirmo","checked", $chkfirmo==1 ? 'true' : 'false');
		
		
		
       // $val [] = array ('#txtValorrecep',$id_valor,'val');
	   // $val [] = array ('#txtNumrecep',$num_val,'val');
	  //	$val [] = array ('#txtAnorecep',$ano_val,'val');
		$val [] = array ('#txtNotificador',$nombnoti,'val');
		$val [] = array ('#txtRecepcionista',$nombrecepci,'val');
		$val [] = array ('#txtDocumento',$nro_docu,'val');
		$val [] = array ('#txtParentesco',$parentes,'val');
		$val [] = array ('#txtFecharecep',$fec_rece,'val');
		$val [] = array ('#txtSuministro',$suministro,'val');
		$val [] = array ('#txtObservaciones',$observacion,'val');
		
		//$val [] = array ('#chbFirmo',$chkfirmo,'val');
		
		
		
		//$chkfirmo=0;
		
		$cad[] = array("#chbFirmo","checked", $chkfirmo==1 ? 'true' : 'false');
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
    	if(count($cad)>0){
			$fn->AtributoComponente($cad);
		}
    }
    
    public function grabarrecepcionAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();

    	if($this->getRequest()->isXmlHttpRequest()){
    		
    		$cn = new Model_DbDatos_Datos();
    		
    		$parametro1[] = array('@msquery',2);
			$parametro1[] = array('@id_valor',$this->_request->getPost('txtValorrecep'));
			$parametro1[] = array('@num_val',$this->_request->getPost('txtNumrecep'));
			$parametro1[] = array('@ano_val',$this->_request->getPost('txtAnorecep'));
			$parametro1[] = array('@nombnoti',$this->_request->getPost('txtNotificador'));
			$parametro1[] = array('@nombrecepci',$this->_request->getPost('txtRecepcionista'));
			$parametro1[] = array('@tipo_doc',$this->_request->getPost('cmbDocumento'));
			$parametro1[] = array('@nro_docu',$this->_request->getPost('txtDocumento'));
			$parametro1[] = array('@parentes',$this->_request->getPost('txtParentesco'));
			$parametro1[] = array('@fec_rece',$this->_request->getPost('txtFecharecep'));
			$parametro1[] = array('@suministro',$this->_request->getPost('txtSuministro'));
			$parametro1[] = array('@observacion',$this->_request->getPost('txtObservaciones'));
			$parametro1[] = array('@firmorecep',$this->_request->getPost('chbFirmo'));
			@$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion', $parametro1);
			
			echo "Se grabo correctamente";
    	}
    }
	
	public function recepbandejaAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		$json=$this->_request->getPost('json');		
		if($this->getRequest()->isXmlHttpRequest()){
    		
			$data = json_decode($json);
			//var_dump($data );
			foreach ($data as $row){ 
				//$id=trim($row->id); 
				
			$cn = new Model_DbDatos_Datos();
			unset($parametros);
			$parametros[] = array('@msquery',4);
			$parametros[] = array('@id_tbl',trim($row->id));			 
			$rows = $cn->ejec_store_procedura_sql('Rentas.SP_MHRuta', $parametros);
				
			}			
    	}
		
		
    }
	
	public function exigibilidadAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
		
			$json = $this->_request->getPost('json');	
			$action = $this->_request->getPost('action');	
			$data = json_decode($json);

			
			foreach ($data as $key => $value){ 

				$dxml.="<row ";
				foreach ($value as $k => $v) { 
					$dxml.=$k.' = "'.$v.'" '; 
				}
				$dxml.=" />";
			}

			//echo $dxml;
			$parametros[] = array('@msquery',4);
			$parametros[] = array('@dataxml',$dxml);
			$parametros[] = array('@action',$action);
			$rows = $cn->ejec_store_procedura_sql('Coactivo.sp_Exigibilidad', $parametros);		
			
			
			/*
			foreach($data as $row){ 
				
				unset($parametros);
				$parametros[] = array('@msquery',1);
				$parametros[] = array('@codigo',trim($row->codigo));
				$parametros[] = array('@nombre',trim($row->contribuyente));
				$parametros[] = array('@montota',trim($row->monto));
				$parametros[] = array('@id_valor',trim($row->valor));
				$parametros[] = array('@num_val',trim($row->num));
				$parametros[] = array('@ano_val',trim($row->ano));
				
						
							
				
			}
			*/
			echo "Se genero exigibilidad correctamente";
			//echo $rows[0][0]."|".$rows[0][1];
			
		}
	}
	
	// public function exigibilidadgrupalAction()
    // {		
    	// $this->_helper->getHelper('ajaxContext')->initContext();
    	// $this->_helper->viewRenderer->setNoRender();
    	// $this->_helper->layout->disableLayout();
		
    	// if($this->getRequest()->isXmlHttpRequest()){
			
			// $cn = new Model_DbDatos_Datos(); 
		
			// $json = $this->_request->getPost('json');	
			// $data = json_decode($json);
			
			// $monto = 0;
			
			// //foreach($data as $row){ 
				// $codigo = $row->codigo;
				// $contri = $row->contribuyente;
				// $monto += $row->monto;
			// //}
			
			// $parametros[] = array('@msquery',6);
			// $parametros[] = array('@codigo',trim($codigo));
			// $parametros[] = array('@nombre',trim($contri));
			// $parametros[] = array('@montota',$monto);
			
			// $rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Exigibilidad', $parametros);		
			
			// //echo "Se genero exigibilidad correctamente";
			// //echo $rows[0][0]."|".$rows[0][1];
			
			// //var_dump($rows); 
			
		// }
	// }
	
	public function mostrarexigibilidadAction()
    {		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
    	
    	$cn = new Model_DbDatos_Datos();
    	//$ar = new Libreria_ArraysFunctions();
    	$fn = new Libreria_Pintar();
		
		$json = $this->_request->getPost('json');
		$data = json_decode($json);
	
		$evt[] = array('#btnGrabaexpe',"button","");
		$evt[] = array('#btnSalidaexpe',"button","");
		
		$evt[] = array('#btnImprimirexi',"click","imprimeExigibilidad()");
		
		$evt[] = array('#btnSalidaexpe',"click","closePopup('#popupexx');");
	
		unset($parametros);
		$parametros[] = array("@msquery",5);
		$parametros[] = array("@numexig",$data[0]->numexig);
		$parametros[] = array("@anoexig",$data[0]->anoexig);
		$rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Exigibilidad', $parametros);
		
		$numexig = $rows [0] [0];
		$anoexig = $rows [0] [1];	
		$codigo = $rows [0] [2];	
		$contribuyente = $rows [0] [3];
		$fecha	=	$rows [0] [4];
		$observaciones = $rows [0] [6];
		
		$cad3='';
		$suma=0;
		for($i = 0; $i < count ($rows); $i ++) 
		{
			//$suma += $rows[$i][6];
			$cad3 .= "<tr>";
			$cad3 .= "<td>".$rows[$i][5]."</td>";
			//$cad3 .= "<td align='right'>".$rows[$i][6]."</td>";
			$cad3 .= "</tr>";
		}
	
		$val[] = array('#detallesExpediente',$cad3,'append');
		
		$val[] = array('#txtNroexpe',$numexig,'val');
		$val[] = array('#txtAniosexpe',$anoexig,'val');
		$val[] = array('#txtCodigoexpe',$codigo,'val');
		$val[] = array('#txtContribuyenteexpe',$contribuyente,'val');
		$val[] = array('#txtFechaexpe',$fecha,'val');
		
		
		
    	$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		// if(count($cad)>0){
			// $fn->AtributoComponente($cad);
		// }
	}
	
	
	
}

