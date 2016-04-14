<?php

class RentascajardController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}
	
	public function btnindicadorAction(){
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		
			$id_registro=trim($this->_request->getPost('id_registro'));
			$cn = new Model_DbDatos_Datos();
			$nombrestore_indicador="Rentas.sp_indicadores";
			$arraydatos_indicador[]=array("@msquery",2);
			$arraydatos_indicador[]=array("@id_registro",$id_registro);
			$row_indicador=$cn->ejec_store_procedura_sql($nombrestore_indicador,$arraydatos_indicador);
			
			echo $row_indicador[0][0];
	}
	public function indexAction()
	{
		$codigo=$this->_request->getParam('codigo','');
		$this->view->codigo = $codigo;

		$path = new Zend_Session_Namespace('path');
		$login = new Zend_Session_Namespace('login');
		$this->view->ruta = $path->data;

		$fn = new Libreria_Pintar();
		$evt[] = array('#chkTotPeriodos',"click","marcaChecks(this,'chkperiodo[]')");
		$evt[] = array('#chkTotArbitrio',"click","marcaChecks(this,'chkarbitrio[]')");
		$evt[] = array('#chkTotAnios',"click","marcaChecks(this,'chkanio[]')");
		$evt[] = array('#chkTotPreddd',"click","marcaChecks(this,'predio[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");
		$evt[] = array('#chkTotConceptos',"click","marcaChecks(this,'conceptos[]')");

    	$evt[] = array('#btnPagarRecibos',"button","");
		$evt[] = array('#btnMostrar',"button","");
		$evt[] = array('#btnVerRec',"button","");
		$evt[] = array('#btnAnular',"button","");
		$evt[] = array('#btnEstCta',"button","");
		$evt[] = array('#btnCerrar',"button","");
		$evt[] = array('#btnFraccionar',"button","");
		
		$evt[] = array('#btnPagarRecibos',"click","enviadeuda();");
		$evt[] = array('#btnMostrar',"click","mostrarRecbuscarEmp();");
		$evt[] = array('#btnVerRec',"click","mostrarRecibos();");
		$evt[] = array('#btnAnular',"click","anulaRecibos()");
		$evt[] = array('#btnEstCta',"click","imprimeCuentaPdf()");
		$evt[] = array('#btnCerrar',"click","closePopup('#poptesore');");
		$evt[] = array('#btnFraccionar',"click","fraccionar();");
		//mio
		
		$evt[] = array('#btnProyeccionrd',"click","mostrarRecContri();");
		
		$evt[] = array('#btnGenerarrd',"click","generarrd();");
		
		//$evt[] = array('#txtFechaproyec',"datepicker","");

		

		$nomcombo="store_caja_framework";
		$arraydatos_nomcombo[]=array("@msquery",'1');
		$arraydatos_nomcombo[]=array("@codigo",$codigo);
		$cn = new Model_DbDatos_Datos();
	
		$rows = $cn->ejec_store_procedura_sql($nomcombo, $arraydatos_nomcombo);
		//for ($i=0;$i<count($rows);$i++){
			$codigo=$rows[0][0];
			$nombre=$rows[0][1];
			//$doc=$rows[$i][2];
			$numdoc=$rows[0][3];
			$direccion=$rows[0][2];
			//$cantpred=$rows[$i][5];
			//$apepater=$rows[$i][6];
			//$apemater=$rows[$i][7];
		//}
	//var_dump($rows);
	$contricaja = new Zend_Session_Namespace('contri');
                $contricaja->contri=$codigo;
                $contricaja->nombre=$nombre;
                //$contricaja->doc=$doc;
                $contricaja->numdoc=$numdoc;
                $contricaja->direccion=$direccion;
                //$contricaja->cantpred=$cantpred;
                //$contricaja->apepater=$apepater;
                //$contricaja->apemater=$apemater;
                
	 //divPredios
	 $val[] = array('#divCodigo',"$codigo","html");//en caso de de div html - input val
	 //$val[] = array('#divDocu',"$doc","html");
	 $val[] = array('#divContri',"$nombre","html");
	 $val[] = array('#divDirec',"$direccion","html");
	 $val[] = array('#divDocu',"$numdoc","html");
	 
	// $val[] = array('#txtFechaproyec',date('d/m/Y'),"val");
	 
	 	 
	 //$val[] = array('#divApePat',"$apepater","html");
	 //$val[] = array('#divApeMat',"$apemater","html");
	 //$val[] = array('#divnpredios',"$cantpred","html");
	 
	 
	 $nombrestore="[dbo].[sp_ActualizaInteres_proyectada]";
	$arraydatos[]=array("@buscar",2);
	$row_fecha = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);

	$val[] = array('#txtFechaproyec',$row_fecha[0][0],"val"); 	
	 
	 

	 $this->view->codigocaja=$codigo;
	 $fn->PintarValor($val);
	 $fn->PintarEvento($evt);

	}
	public function prediosAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			$codigo=$this->_request->getParam('codigocaja');

			$cn = new Model_DbDatos_Datos();
			$nombrestorepredios="store_caja_framework 4,@codigo='$codigo'";
			$rowpredios = $cn->ejec_store_procedura_sql($nombrestorepredios, null);
			$predios='';
			for ($i=0;$i<count($rowpredios);$i++){

				$predios.='<tr> <td><label style="font-size:10px;"><input type="checkbox" name="predio[]" id="predio[]" value="'.$var.$rowpredios[$i][2].$var.'" style="float:left;"> <div style="float:left; margin:0 0 5px 5px; width:280px">'.$rowpredios[$i][2].' - '.$rowpredios[$i][3].' '.$rowpredios[$i][4].'</div></label></td>  </tr>';

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
			//$this->_request->getParam('codigocaja');
			$cn = new Model_DbDatos_Datos();
			$codigo=$this->_request->getParam('codigocaja');
			$nombrestoreperiodos="store_caja_framework 5,@codigo='$codigo'";

			$rowperiodo = $cn->ejec_store_procedura_sql($nombrestoreperiodos, null);
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
			//$this->_request->getParam('codigocaja');
			$cn = new Model_DbDatos_Datos();
			$codigo=$this->_request->getParam('codigocaja');
			$nombrestoreanios="store_caja_framework 6,@codigo='$codigo'";
			$rowanios = $cn->ejec_store_procedura_sql($nombrestoreanios, null);
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
	
	public function mostrarrecibosAction(){
		
			$fn = new Libreria_Pintar();
			$cn = new Model_DbDatos_Datos();
			$codigo=$this->_request->getParam('codigo');
			
			$nombrestore='Caja.sp_Recibos_emitidos';
			$arraydatos[]=array("@buscar", '1');
			$arraydatos[]=array("@codigo", $codigo);
			
			$rowpagos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
			
			//print_r($rowpagos);
			for($i = 0; $i < count ( $rowpagos ); $i ++) {
				
//				$detalle .= '<tr ' . (($dfecha [0] == $rowpagos [$i] [3]) ? ' bgcolor="#FFFF33" ' : '') . ' >';
				$detalle .= '<tr ' . (($rowpagos[$i][5]=='0') ? ' bgcolor="#FFFF33" ' : ' ') . ' >';
				$detalle .= '<td width="87" align="center">' . $rowpagos [$i] [0] . '</td>';
				$detalle .= '<td width="40" align="center">' . $rowpagos [$i] [2] . '</td>'; //caja
				$detalle .= '<td width="78" align="center">' . $rowpagos [$i] [7] . '</td>';
				$detalle .= '<td width="150" align="center">' . $rowpagos [$i] [3] . '</td>';
				$detalle .= '<td width="100" align="right">' . number_format ( $rowpagos [$i] [4], '2', '.', '' ) . '&nbsp;</td>';
				$detalle .= '<td width="70" align="center">' . $rowpagos [$i] [6] . '</td>';
				$detalle .= '<td width="52" align="center"></td>';
				$detalle .= '</tr>';
			}
			$this->view->datarows=$detalle;
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
		
		$nombrestore='Caja.sp_Recibos_emitidos';
		$arraydatos[]=array("@buscar", '2');
		$arraydatos[]=array("@num_ingr", $recibo);
		@$rows = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		echo 'Recibo nro. '.$recibo.' anulado con exito';
	}
}

