<?php

class TesoreportesController extends Zend_Controller_Action
{

    public function init()
    {
			
    }

    public function indexAction()
    {

    	$fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$this->view->tesodesde = $fecharow[0][0];
		$this->view->tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$evt[] = array("#btnbuscarecibos", "click", "buscarRecibos();");
		$evt[] = array("#btnprintRpt", "click", "generarpdf();");
		$evt[] = array("#btnprintPartidas", "click", "generarpdfParidas();");
		$mask[] = array("txtArancel");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
    }
    
    public function recibosAction(){
    	
		$tesodesde = $this->_request->getParam('tesodesde','');
		$tesohasta = $this->_request->getParam('tesohasta','');
		$cmbcajas = $this->_request->getParam('cmbcajas','');
		
		$cn = new Model_DbDatos_Datos();
    	
            $nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','1');
		$parametros[] = array('@cajero',$cmbcajas);
		$parametros[] = array('@fec_desde',$tesodesde);
		$parametros[] = array('@fec_hasta',$tesohasta);
		
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
        
/*		$val[] = array("#txttotal", '1.00', "val");
        $fn->PintarValor($val);*/
        
		$jsonData = array('rows'=>array());
		if(count($rows)){
			foreach($rows AS $row){
				$entry = array(
						'recibo'=>$row[1],				  
						'hora_pago'=>utf8_encode($row[4]),
						'codigo'=>utf8_encode($row[5]),
						'nombre'=>utf8_encode($row[6]),
						'monto'=>utf8_encode($row[7]),
                        'tipo_rec' =>$row[28],
						'tipo_pago'=>utf8_encode($row[8]),
						'estado'=>utf8_encode($row[9]),
						'tipo_operacion'=>utf8_encode($row[26]),
						'deta_operacion'=>utf8_encode($row[22])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	
	public function reportexpartidaAction(){
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$fn = new Libreria_Pintar ();
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$cmbnivel = $this->_request->getParam('cmbnivel','');
		
		$val[] = array("#fecha_desde",$desde,"html");
		$val[] = array("#fecha_hasta",$hasta,"html");
		
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreriaPartidas';
    	$parametros[] = array('@buscar','1');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		/*
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@nivel',$cmbnivel);
		*/
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		if(count($rows)){
			$strHtml = "";
			foreach($rows AS $row){
				if($row[13]=='orange'){
					$strHtml .= "<tr><td colspan='3' style='border-bottom:1px #000 dotted;'>&nbsp;</td></tr>";
				}
				$strHtml .= "<tr>";
					$strHtml .= "<td style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td style='padding-top:3px'>".$row[4]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[8]."</td>";
				$strHtml .= "</tr>";
			}
		}
		$fn->PintarValor($val);
		
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[15];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		$this->view->total = $row[14];
	}
	
	public function reporterecibosemitidosAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$extornada = $this->_request->getParam('extornada','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtrecibo = $this->_request->getParam('txtrecibo','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','1');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@extorno',$extornada);
		$parametros[] = array('@codigo',$txtcodigo);
		$parametros[] = array('@movimiento',$txtrecibo);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$efectivo=0;
		$mcheques=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				/*
					if($row[13]=='orange'){
						$strHtml .= "<tr><td colspan='3' style='border-bottom:1px #000 dotted;'>&nbsp;</td></tr>";
					}
				*/
				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$extorno_efectivo+=$row[18];
				$extorno_mcheques+=$row[19];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[0]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[21]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[22]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[5]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[23]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[24]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[25]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[9]."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[0];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
		$this->view->efectivo= number_format($efectivo,2);
		$this->view->mcheques= number_format($mcheques,2);
		$this->view->total = number_format($efectivo+$mcheques,2);
		
		$this->view->extorno_efectivo= number_format($extorno_efectivo,2);
		$this->view->extorno_mcheques= number_format($extorno_mcheques,2);
		$this->view->extorno_total = number_format($extorno_efectivo+$extorno_mcheques,2);
	}
	
	public function reportemainAction(){
	
	}
	
	public function menureporteemitidosAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'RecibosEmitidos()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	public function menureportexpartidasAction(){
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'RecibosPartidas()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	
	public function estadosdecajasAction(){
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','3');
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
			
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[4]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[5]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[6]."</td>";
				$strHtml .= "</tr>";
			}
		}

		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		
		$this->view->strHtml = $strHtml;
	}
	public function menureporteemitidoscontribAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'RecibosEmitidosContrib()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	
	public function reporterecibosemitidoscontribAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$extornada = $this->_request->getParam('extornada','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtrecibo = $this->_request->getParam('txtrecibo','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','1');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@extorno',$extornada);
		$parametros[] = array('@codigo',$txtcodigo);
		$parametros[] = array('@movimiento',$txtrecibo);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$efectivo=0;
		$mcheques=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				/*
					if($row[13]=='orange'){
						$strHtml .= "<tr><td colspan='3' style='border-bottom:1px #000 dotted;'>&nbsp;</td></tr>";
					}
				*/
				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$extorno_efectivo+=$row[18];
				$extorno_mcheques+=$row[19];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[0]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[21]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[22]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[5]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[6]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[25]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[9]."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[0];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
		$this->view->efectivo= number_format($efectivo,2);
		$this->view->mcheques= number_format($mcheques,2);
		$this->view->total = number_format($efectivo+$mcheques,2);
		
		$this->view->extorno_efectivo= number_format($extorno_efectivo,2);
		$this->view->extorno_mcheques= number_format($extorno_mcheques,2);
		$this->view->extorno_total = number_format($extorno_efectivo+$extorno_mcheques,2);
	}

	public function menureporterecaudacioncajasAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'RecaudacionCajas()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	public function reporterecaudacioncajasAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','2');
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[0]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
				$strHtml .= "</tr>";
			}
		}
		$this->view->texto='';
		$this->view->cajero='';

		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
	}
	public function menureporteresumenconceptosAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ResumenConceptos()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}


	
	public function reporteresumenconceptosAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$extornada = $this->_request->getParam('extornada','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtrecibo = $this->_request->getParam('txtrecibo','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','4');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@extorno',$extornada);
		$parametros[] = array('@codigo',$txtcodigo);
		$parametros[] = array('@movimiento',$txtrecibo);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$insoluto=0;
		$reajuste=0;
		$ninteres=0;
		$gastosad=0;
		$descuent=0;
		$ntotales=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){

				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$insoluto+=$row[3];
				$reajuste+=$row[4];
				$ninteres+=$row[5];
				$gastosad+=$row[6];
				$descuent+=$row[7];
				$ntotales+=$row[2];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[9]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[10]))."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[12]))."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[2],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[3],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[4],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[5],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[6],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[7],2)."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[13];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
	
		$this->view->insoluto= number_format($insoluto,2);
		$this->view->reajuste= number_format($reajuste,2);
		$this->view->ninteres= number_format($ninteres,2);
		$this->view->gastosad= number_format($gastosad,2);
		$this->view->descuent= number_format($descuent,2);
		$this->view->ntotales= number_format($ntotales,2);

	}
	public function menureporteresumenconceptosxanioAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ResumenConceptosxanio()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	
	public function reporteresumenconceptosxanioAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$extornada = $this->_request->getParam('extornada','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtrecibo = $this->_request->getParam('txtrecibo','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','5');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@extorno',$extornada);
		$parametros[] = array('@codigo',$txtcodigo);
		$parametros[] = array('@movimiento',$txtrecibo);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$insoluto=0;
		$reajuste=0;
		$ninteres=0;
		$gastosad=0;
		$descuent=0;
		$ntotales=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){

				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$insoluto+=$row[3];
				$reajuste+=$row[4];
				$ninteres+=$row[5];
				$gastosad+=$row[6];
				$descuent+=$row[7];
				$ntotales+=$row[2];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[9]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[10]))."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[12]))."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[14]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[2],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[3],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[4],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[5],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[6],2)."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[7],2)."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[13];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
	
		$this->view->insoluto= number_format($insoluto,2);
		$this->view->reajuste= number_format($reajuste,2);
		$this->view->ninteres= number_format($ninteres,2);
		$this->view->gastosad= number_format($gastosad,2);
		$this->view->descuent= number_format($descuent,2);
		$this->view->ntotales= number_format($ntotales,2);

	}
	
	public function menureporteentradaAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
    	$nombrestore2  = 'Caja.sp_tupa_entradas';
		$parametros2[] = array('@buscar','3');
		
		$dataConceptos = $cn->ejec_store_procedura_sql($nombrestore2, $parametros2);
		
		$Conceptos[1] = array('00.00','seleccionar');
		
        for($i=1; $i < count($dataConceptos); $i++){
            $Conceptos[$i+1] = array(trim($dataConceptos[$i][7]),trim(utf8_encode($dataConceptos[$i][1])));
        }
		$val[] = array("#cmbConceptos",$fn->ContenidoCombo2($Conceptos,'',''),"html");
				
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ReporteEntrada()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}

	public function reporteentradaAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$extornada = $this->_request->getParam('extornada','');
		$txtcodigo = $this->_request->getParam('txtcodigo','');
		$txtrecibo = $this->_request->getParam('txtrecibo','');
		$cmbConceptos = $this->_request->getParam('cmbConceptos','');
		
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','6');
		$parametros[] = array('@cajero',$cajero);
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		
		$parametros[] = array('@tipo_operacion',$tipooperacion);
		$parametros[] = array('@extorno',$extornada);
		$parametros[] = array('@codigo',$txtcodigo);
		$parametros[] = array('@movimiento',$txtrecibo);
		$parametros[] = array('@tipo_rec',$cmbConceptos);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$efectivo=0;
		$mcheques=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				/*
					if($row[13]=='orange'){
						$strHtml .= "<tr><td colspan='3' style='border-bottom:1px #000 dotted;'>&nbsp;</td></tr>";
					}
				*/
				$efectivo+=$row[16];
				$mcheques+=$row[17];

				$extorno_efectivo+=$row[18];
				$extorno_mcheques+=$row[19];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[21]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[32]."&nbsp;</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[33]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[6]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[31]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[28]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[29]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[30]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[9]."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[0];
		}
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
		$this->view->efectivo= number_format($efectivo,2);
		$this->view->mcheques= number_format($mcheques,2);
		$this->view->total = number_format($efectivo+$mcheques,2);
		
		$this->view->extorno_efectivo= number_format($extorno_efectivo,2);
		$this->view->extorno_mcheques= number_format($extorno_mcheques,2);
		$this->view->extorno_total = number_format($extorno_efectivo+$extorno_mcheques,2);
	}

    public function menureportereciboemitidosdetalladoAction(){

        $fn = new Libreria_Pintar ();
        $cn = new Model_DbDatos_Datos();

        $nombrestore  = 'dbo.sp_getfecha';
        $fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);

        $nombrestore  = 'Calculo.sp_ListaCombo';
        $parametros[] = array('@busc','7');

        $datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);

        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
        $val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");

        $tesodesde = $fecharow[0][0];
        $tesohasta = $fecharow[0][0];

        $evt[] = array('#tesodesde',"datepicker","");
        $evt[] = array('#tesohasta',"datepicker","");

        $val[] = array('#tesodesde',$tesodesde,"val");
        $val[] = array('#tesohasta',$tesohasta,"val");

        $evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
        $evt[] = array("#btnaceptar", "click", 'RecibosEmitidosDetallado()');

        $fn->PintarValor($val);
        $fn->PintarEvento($evt);

    }

    public function reporterecibosemitidosdetalladoAction(){

		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$cajero = $this->_request->getParam('cajero','');
		
		//$tipooperacion = $this->_request->getParam('tipooperacion','');
		//$extornada = $this->_request->getParam('extornada','');
		//$txtcodigo = $this->_request->getParam('txtcodigo','');
        $txtmovimiento = $this->_request->getParam('txtmovimiento','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','7');
		$parametros[] = array('@movimiento',$txtmovimiento);
	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$efectivo=0;
		$mcheques=0;

		$extorno_efectivo=0;
		$extorno_mcheques=0;
		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				$efectivo+=$row[19];
				$mcheques+=$row[20];

				$extorno_efectivo+=$row[21];
				$extorno_mcheques+=$row[22];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[17]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[28]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>&nbsp;".ucfirst(strtolower($row[4]))."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[15]))."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".ucfirst(strtolower($row[16]))."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[18]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[8]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[9]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[10]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[11]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[7]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[5]."</td>";
				$strHtml .= "</tr>";
			}
		}
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[0];
		}
        $this->view->movimiento=$row[1];
        $this->view->contribuyente=$row[4];
        if($row[3]<>' '){
            $this->view->textcontri='Codigo: ';
            $this->view->codcontri=$row[3];
            $this->view->textspacio='&nbsp;&nbsp;&nbsp;';
        }

		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml;
		
		$this->view->efectivo= number_format($efectivo,2);
		$this->view->mcheques= number_format($mcheques,2);
		$this->view->total = number_format($efectivo+$mcheques,2);
		
		$this->view->extorno_efectivo= number_format($extorno_efectivo,2);
		$this->view->extorno_mcheques= number_format($extorno_mcheques,2);
		$this->view->extorno_total = number_format($extorno_efectivo+$extorno_mcheques,2);
	}
	
	public function menureporteemitidosdetalladoAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'RecibosEmitidosDetallado()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	
	public function menureportetupaentradaAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_tupa_entradas';
		$parametros[] = array('@buscar','4');
		
		$operacion[0] = array('','seleccionar');
		
		$dataoperacion = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($dataoperacion); $i++){
            $operacion[$i+1] = array(trim($dataoperacion[$i][0]),trim($dataoperacion[$i][1]));
        }
		$val[] = array("#cmbOperacion",$fn->ContenidoCombo2($operacion,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ReporteTupaEntrada()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}

	public function conceptosAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest())
		{
    		
			$cn = new Model_DbDatos_Datos();
			
			$valOperacion = $this->_request->getPost('valOperacion');
			
			$combostore1 ='Caja.sp_tupa_entradas';
			$arraydatos1[] = array("@buscar",5);
			$arraydatos1[] = array("@operacion",$valOperacion);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			
			$cb_valOperacion='<option value="">[Seleccione]</option>';
			for ($i=0;$i<count($rows1);$i++){
            	$cb_valOperacion.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
        	}
		
			echo $cb_valOperacion;
    	}  
    }
	
	public function reportetupaentradaAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$tipooperacion = $this->_request->getParam('tipooperacion','');
		$cmbOperacionDetalle = $this->_request->getParam('cmbOperacionDetalle','');
		$cmbDescripcionDetalle = $this->_request->getParam('cmbDescripcionDetalle','');

	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_rptTesoreria';
    	$parametros[] = array('@buscar','8');
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		$parametros[] = array('@tipo_rec',$cmbOperacionDetalle);

	
		$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$total=0;
		$strHtml1="";

		
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				$total+=$row[3];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[0]."</td>";
					$strHtml .= "<td align='center' style='padding-top:3px'>".$row[1]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[4]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".$row[3]."</td>";
				$strHtml .= "</tr>";
			}
		}
				$strHtml1 .= "<tr>";
					$strHtml1 .= "<td align='center' style='padding-top:3px'>&nbsp;</td>";
					$strHtml1 .= "<td align='center' style='padding-top:3px'>&nbsp;</td>";
					$strHtml1 .= "<td align='right' style='padding-top:3px'><b>Importe Total</b></td>";
					$strHtml1 .= "<td align='right' style='padding-top:3px'><b>".number_format($total,2)."</b></td>";
				$strHtml1 .= "</tr>";
		/*
		if($cajero<>'--'){
			$this->view->texto='Caja:';
			$this->view->cajero=$row[0];
		}
		*/
		$this->view->descrip=$cmbDescripcionDetalle;
		
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml.$strHtml1;
		
		$this->view->total = number_format($total,2);

	}

	public function menureporteingresoxgerenciaAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','16');
		
		$operacion[0] = array('','[----SELECIONAR----]');
		
		$dataoperacion = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($dataoperacion); $i++){
            $operacion[$i+1] = array(trim($dataoperacion[$i][0]),trim($dataoperacion[$i][1]));
        }
		$val[] = array("#cmbOperacion",$fn->ContenidoCombo2($operacion,'',''),"html");
		
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ReporteIngresoxGerencia()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	
	public function reporteingresoxgerenciaAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		$tipooperacion = $this->_request->getParam('tipooperacion','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Caja.sp_Tesoreria_Reporte_Gerencia_Tupa';
    	$parametros[] = array('@buscar','1');
		$parametros[] = array('@fec_desde',$desde);
		$parametros[] = array('@fec_hasta',$hasta);
		$parametros[] = array('@area',$tipooperacion);

	
		@$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$total=0;
		$strHtml1="";

		$this->view->nombrearea=$rows[0][15];
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				$total+=$row[7];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[2]."</td>";
					$strHtml .= "<td align='left' style='padding-top:3px'>".$row[3]."</td>";
					$strHtml .= "<td align='right' style='padding-top:3px'>".number_format($row[7],2)."</td>";
				$strHtml .= "</tr>";
			}
		}
				$strHtml1 .= "<tr>";
					$strHtml1 .= "<td align='center' style='padding-top:3px'>&nbsp;</td>";
					$strHtml1 .= "<td align='right' style='padding-top:3px'><b>Importe Total</b></td>";
					$strHtml1 .= "<td align='right' style='padding-top:3px'><b>".number_format($total,2)."</b></td>";
				$strHtml1 .= "</tr>";

	
		
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml.$strHtml1;
		
		$this->view->total = number_format($total,2);

	}
	
	public function menureportevehiculosmenoresAction(){
	
	    $fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
	/*	
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','16');
		
		$operacion[0] = array('','seleccionar');
		
		$dataoperacion = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($dataoperacion); $i++){
            $operacion[$i+1] = array(trim($dataoperacion[$i][0]),trim($dataoperacion[$i][1]));
        }
		$val[] = array("#cmbOperacion",$fn->ContenidoCombo2($operacion,'',''),"html");
	*/	
		$tesodesde = $fecharow[0][0];
		$tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$val[] = array('#tesodesde',$tesodesde,"val");
		$val[] = array('#tesohasta',$tesohasta,"val");

		$evt[] = array("#btnvolver", "click", 'goToInterno(urljs + "tesoreportes/reportemain","Reportes")');
		$evt[] = array("#btnaceptar", "click", 'ReporteVehiculosMenores()');
	
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);

	}
	
	public function reportevehiculosmenoresAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
		$username= strtoupper($getlogin->user);
		
		$desde = $this->_request->getParam('desde','');
		$hasta = $this->_request->getParam('hasta','');
		//$tipooperacion = $this->_request->getParam('tipooperacion','');
	
		$cn = new Model_DbDatos_Datos();

		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Rentas.Reporte_Multa_Admin';
    	//$parametros[] = array('@buscar','1');
		$parametros[] = array('@fecha_ini',$desde);
		$parametros[] = array('@fecha_fin',$hasta);
		//$parametros[] = array('@area',$tipooperacion);

	
		@$rows = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
		$total=0;
		$strHtml1="";

		$this->view->nombrearea=$rows[0][15];
		if(count($rows)){
			
			$strHtml = "";
			foreach($rows AS $row){
				$total+=$row[11];
				
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='width:80px;padding-top:3px;padding-right:10px'>".$row[0]."</td>";
					$strHtml .= "<td align='center' style='width:70px;padding-top:3px;padding-right:6px;'>".$row[1]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[2]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[3]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[4]."</td>";
					$strHtml .= "<td align='left' style='width:300px;padding-top:3px;padding-right:10px'>".$row[5]."</td>";
					$strHtml .= "<td align='left' style='width:700px'> ".$row[6]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[8]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[9]."</td>";
					$strHtml .= "<td align='center' style='width:100px;padding-top:3px;padding-right:10px'>".$row[10]."</td>";
					$strHtml .= "<td align='right' style='width:100px;padding-top:3px'><font size=2><b>".number_format($row[11],2)."</b></font></td>";
				$strHtml .= "</tr>";
				
			}
		}
				
				
				$strHtml1 .= "<tr>";
					$strHtml1 .= "<td align='center' colspan=11 style='padding-top:3px;padding-right:10px'>&nbsp;</td>";
				$strHtml1 .= "</tr>";
				
				$strHtml1 .= "<tr>";
					$strHtml1 .= "<td align='center' colspan=11 style='padding-top:3px;padding-right:10px'><font size=3><b>Importe Total==>   ".number_format($total,2)."</b></font></td>";
				$strHtml1 .= "</tr>";

	
		
		$this->view->fecha_imprime=$fecharow[0][1];
		$this->view->usuario=$username;
		$this->view->fecha_desde=$desde;
		$this->view->fecha_hasta=$hasta;
		
		$this->view->strHtml = $strHtml.$strHtml1;
		
		$this->view->total = number_format($total,2);

	}
	
}