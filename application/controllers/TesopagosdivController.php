<?php

class TesopagosdivController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {
		$modulo=$this->_request->getParam('modulo','');
    	$codigo=$this->_request->getParam('codigo','');
		$nombre=$this->_request->getParam('nombre','');
		
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
        /*
		$nombrestore = 'calculo.sp_listacombo';
        $arraydatos[0]= array('@busc','3');        

		$pagostupa = $cn->ejec_store_procedura_sql ( $nombrestore, $arraydatos );
            
        for($i=0; $i < count($pagostupa); $i++){
            $pagos[$i+1] = array($pagostupa[$i][0].$pagostupa[$i][2],$pagostupa[$i][1]);
        }

		$val[] = array("#cmbtipos",$fn->ContenidoCombo2($pagos,'',''),"html");
		*/
		
		$val[] = array("#txtCodigo",$modulo,"val");
		
		$val[] = array("#txtCodigo",$codigo,"val");
		$val[] = array("#txtNombre",$nombre,"val");

		$evt[] = array('#btnImpPag',"click","enviadeudaTupa();");
		$evt[] = array('#txtFecPag',"datepicker","");
		$evt[] = array('#btnAddDetPago',"click","addRowPagos();");		
		$evt[] = array('#btnSalir',"click","closePopup('#popPagosDiv');");
		
		$evt[] = array('#btnAddTupa',"click","eventPagoTupa('A');");
		$evt[] = array('#btnEditTupa',"click","eventPagoTupa('E');");
		$evt[] = array('#btnCancelTupa',"click","eventPagoTupa('C');");
		$evt[] = array('#btnSaveTupa',"click","eventPagoTupa('S');");
		$evt[] = array('#btnDelTupa',"click","eventPagoTupa('D');");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);		
		
    }
	public function autocompletarAction() {
	
		$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
		/*
		$req_tipo = $this->_request->getParam('name_type','');
		if($req_tipo=='1')
			$req_tipo = 'B';
		if($req_tipo=='0')
			$req_tipo = 'S';
		*/	
		$variable = rtrim(ltrim($this->_request->getParam('name_startsWith','')));

//		[Calculo].[sp_ListaCombo] @busc=6,@modulo='2',@variable='derech'

    	$arraydatos[] = array('@busc',6);
		$arraydatos[] = array('@modulo','2');
		$arraydatos[] = array('@variable',$variable);
		@$rows = $cn->ejec_store_procedura_sql('Calculo.sp_ListaCombo', $arraydatos);
		
		if(count($rows)){
			$jsonData = array('total'=>count($rows),'rows'=>array());
				foreach($rows AS $row){
					$entry = array(
						'id' => utf8_encode($row[0]),				  
						'name' => utf8_encode($row[3]),
						'measure' => utf8_encode($row[2])
					);
					$jsonData['rows'][] = $entry;
			}
		}
				
		$this->view->data=json_encode($jsonData);
	}
	
	public function prepagodivAction()
	{
		$fn = new Libreria_Pintar();
		
		$cn = new Model_DbDatos_Datos();
    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$totalpagar = $this->_request->getParam('totalpagar','');		
		/* Combos de banco y tarjeta */
		
        $nombrestore02 = 'calculo.sp_listacombo';
        $arraydatos02[0]= array('@busc','4');        

        $datosbancos = $cn->ejec_store_procedura_sql($nombrestore02,$arraydatos02);
		
        for($i=0; $i < count($datosbancos); $i++){
            $banco[$i+1] = array($datosbancos[$i][0],$datosbancos[$i][1]);
        }
		$val[] = array("#cmbBanco",$fn->ContenidoCombo2($banco,'',''),"html");

        $nombrestore03 = 'calculo.sp_listacombo';
        $arraydatos03[0]= array('@busc','5');        

        $datostarjeta = $cn->ejec_store_procedura_sql($nombrestore03,$arraydatos03);
		
        for($i=0; $i < count($datostarjeta); $i++){
            $tarjeta[$i+1] = array($datostarjeta[$i][0],$datostarjeta[$i][1]);
        }
		$val[] = array("#cmbTarjeta",$fn->ContenidoCombo2($tarjeta,'',''),"html");
		
		/* Combos de banco y tarjeta */
		$val[] = array('#txtCobrar',$totalpagar,'val');
		$val[] = array('#txtFecCobro',$fecharow[0][0],'val');
				
		$mask[] = array("txtCobrar");
		$mask[] = array("txtEfectivo");
		$mask[] = array("txtDevolucion");
		
		$evt[] = array('#btnEfectivo',"button","");
    	$evt[] = array('#btnCheque',"button","");
		$evt[] = array('#btnAceptarPago',"button","");
    	$evt[] = array('#btnprintSalir',"button","");
    	
		$evt[] = array('#rdformaPago1',"click","getTipoPagoTupa();");
		$evt[] = array('#rdformaPago2',"click","getTipoPagoTupa();");
		$evt[] = array('#rdformaPago3',"click","getTipoPagoTupa();");
		$evt[] = array('#txtFecCobro',"datepicker","");
		
		$evt[] = array('#btnAceptarPago',"click","getcancelarTupa();");
		$evt[] = array('#btnprintSalir',"click","closePopup('#popdeudaTupa');");
		$evt[] = array('#txtEfectivo',"change", "restarmontosapagarTupa();");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
	}
	public function registrapagotupaAction(){
	
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$this->_helper->Layout->disableLayout();		
		
		
		$codigo = $this->_request->getPost('codigo');
		$nombre=$this->_request->getPost('nombre','');
		$json = $this->_request->getPost('json');
		$formaPago = $this->_request->getPost('rdformaPago');

		$cmbTarjeta = $this->_request->getPost('cmbTarjeta');
		$cmbBanco = $this->_request->getPost('cmbBanco');
		$txtCheque = $this->_request->getPost('txtCheque');
		$txtFecCobro = $this->_request->getPost('txtFecCobro');
		$txtObservacion = $this->_request->getPost('txtObservacion');
		$txtCobrar = $this->_request->getPost('txtCobrar');
		
		
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
			
			echo $dxml;
			

			$cn = new Model_DbDatos_Datos();
	
			$nombrestore="Caja.sp_Genera_Recibos_Tupa";
			$arraydatos[]=array("@codigo", $codigo);
			$arraydatos[]=array("@cajero", $cajero);
			$arraydatos[]=array("@dataxml", $dxml);
			$arraydatos[]=array("@tipo_pago", $formaPago);
			$arraydatos[]=array("@operador", $username);
			$arraydatos[]=array("@estacion", $hostname);
			$arraydatos[]=array("@nombre", $nombre);

			$arraydatos[]=array("@banco", $cmbBanco);
			$arraydatos[]=array("@tarjeta", $cmbTarjeta);
			$arraydatos[]=array("@num_cheque", $txtCheque);
			$arraydatos[]=array("@observa", $txtObservacion);
			$arraydatos[]=array("@monto", $txtCobrar);
			
			$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);

			echo ' <script language=\'javascript\'>';
			//for($i=0;$i<2;$i++){
				echo 'window.open(\'tesopagosdiv/imprimirrecibopagostupa?idx=1&nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); ';
			//}	
			echo '  function ventanaSecundaria(){ ';
			//for($i=0;$i<2;$i++){			
				echo 'ventana=window.open(\'tesopagosdiv/imprimirrecibopagostupa?idx=1&nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no");ventana.focus();';
			//}
			echo '	} 
				    </script>				    
				   
				 <input type="button" name="imprimir" id="imprimir" value="Imprimir recibo" onClick="ventanaSecundaria()" />
				 <br />
				 <br />';
				 
		}else{
			echo 'El usuario no tiene caja asignada';
		}
		

	}

	public function registrapagotupa02Action(){// Creado copiando el INFOSAT
	
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$this->_helper->Layout->disableLayout();		
		
		$codigo = '9999999';
		$contribuyente=$this->_request->getPost('contribuyente','');
		$formaPago = $this->_request->getPost('rdformaPago');
		$cmbTarjeta = $this->_request->getPost('cmbTarjeta');
		$referencia = str_replace("'","''",$this->_request->getPost('referencia'));
		$observaciones = str_replace("'","''",$this->_request->getPost('observacion'));
		$txttotal = $this->_request->getPost('txttotal');

		$nrodocu = $this->_request->getPost('nrodocu');
		$tipooculto = $this->_request->getPost('tipooculto');
		$tiporecoculto = $this->_request->getPost('tiporecoculto');
		$txtprecio = $this->_request->getPost('txtprecio');
		$txtcantidad = $this->_request->getPost('txtcantidad');
		
		$idx = $this->_request->getPost('idx');

		$data = json_decode($json);
		$dxml = '';
		$cajero=$getlogin->caja;

		if(strlen($cajero)>0){
			
			//$tipo_operacion=($idx='1')? '2':'4';
			
			$dxml='<row tipo = "'.$tipooculto.'" tipo_rec = "'.$tiporecoculto.'" concepto = "" precio = "'.$txtprecio.'" cantidad = "'.$txtcantidad.'" pordesc = "0" totales = "'.$txttotal.'" tipo_operacion="'.$idx.'" referencia="'.$referencia.'"/>';
			
			echo $dxml;
			

			$cn = new Model_DbDatos_Datos();
	
			$nombrestore="Caja.sp_Genera_Recibos_Tupa";
			$arraydatos[]=array("@codigo", $codigo);
			$arraydatos[]=array("@cajero", $cajero);
			$arraydatos[]=array("@dataxml", $dxml);
			$arraydatos[]=array("@tipo_pago", $formaPago);
			$arraydatos[]=array("@operador", $username);
			$arraydatos[]=array("@estacion", $hostname);
			$arraydatos[]=array("@nombre", $contribuyente);
			$arraydatos[]=array("@observacion2", $observaciones);

			$arraydatos[]=array("@tarjeta", $cmbTarjeta);
			$arraydatos[]=array("@observa", $referencia);
			$arraydatos[]=array("@monto", $txttotal);
			
			$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);

			echo ' <script language=\'javascript\'>';
			//for($i=0;$i<2;$i++){
			echo 'window.open(\'tesopagosdiv/imprimirrecibopagostupa?idx='.$idx.'&nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); ';
			//}	
			echo '  function ventanaSecundaria(){ ';
			//for($i=0;$i<2;$i++){			
				echo 'ventana=window.open(\'tesopagosdiv/imprimirrecibopagostupa?idx='.$idx.'&nrorecibo='.$rowrecibos[0][0].'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no");ventana.focus();';
			//}
			echo '	} 
				    </script>				    
				   
				 <!--<input type="button" name="imprimir" id="imprimir" value="Imprimir recibo" onClick="ventanaSecundaria()" />-->
				 <br />
				 <br />';
				 
		}else{
			echo 'El usuario no tiene caja asignada';
		}
		

	}
	
	public function imprimirrecibopagostupaAction(){

		
		$nrorecibo = $this->_request->getParam('nrorecibo','');
		$idx = $this->_request->getParam('idx','');
		
		$printrecibo='';
		$arraydatos=null;
		$printrecibo='';
		$htmlhead='';
		$htmlbody='';
		$htmlfoot='';
		
		$cn = new Model_DbDatos_Datos();
		
		$cod_pred=$rowcabecera[2];
		$anexo=$rowcabecera[3];
		$sub_anexo=$rowcabecera[4];
		$tipo=$rowcabecera[5];
			
			
			
		unset($arraydatos);
		unset($nombrestore);
		unset($rowrecibos);
		
    	$nombrestore  = 'Caja.sp_Recibos_emitidos';
		$arraydatos[]=array("@buscar", '6');
		$arraydatos[]=array("@num_ingr", trim($nrorecibo));

		$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);

		$local='Municipalidad de Independencia';
		$vararea='Sub Gerencia de Tesoreria';
		$varruc='20131373661';
		$recibo=$rowrecibos[0][0];
		$codigo=$rowrecibos[0][1];
		$nombre=$rowrecibos[0][2];
		$fechor=$rowrecibos[0][4];
		$nrcaja=$rowrecibos[0][3];
		$monto_total=number_format ( $rowrecibos[20], '2', '.', '' );
		$total=number_format ( $rowrecibos[20], '2', '.', '' );
		$nomcajero=$rowrecibos[0][31];
		$concepto=$rowrecibos[0][32];
		$direccionfis=$rowrecibos[0][25];
		$subtotal=0;
		$movimiento=$rowrecibos[0][33];
		
		$concepto=$rowrecibos[0][32];
		$criterio=$rowrecibos[0][34];
		$partida=$rowrecibos[0][35];
		$referencia=$rowrecibos[0][26];
		
		$observacion2=$rowrecibos[0][37];
		//var_dump($rowrecibos);
		//echo '<br>';
		//print_r($rowrecibos);
		//unset($varhtml);
/*
	1=Tupa
	2=Entrada
*/
	if($idx=='1'){
		$varhtml='
		<div style="left:0px;  z-index:1" class=cuerpo_recibo >
		<table width=100%  border=0 cellspacing=0 cellpadding=0><tr><td align=center  width=100%>
		<table cellSpacing=0 cellPadding=0 width=100% border=0>
		<tr><td width="100%"><h3>'.$local.'</h3></td></tr>
		<tr><td width="100%"><h4>'.$vararea.'</h4></td></tr>
		<tr>
		<td width=100%>
		<table cellSpacing=0 cellPadding=0 width=100% border=0>
		<tr>
		<td nowrap>Fecha/Hora:</td>
		<td nowrap>'.$fechor.'</td>
		<td>Mov:</td>
		<td>'.$recibo.'</td>
		<td>P&aacute;gina:</td>
		<td>1</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>Mov.Cajero:</td>
		<td>'.$movimiento.'</td>
		<td>Caja:</td>
		<td>'.$nrcaja.'</td>
		</tr>
		<tr>
		<td colspan=6>
		<font>Contribuyente: '.$nombre.'</font>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td width=100% align=center>
		<table width=100%  class=recibo_pre_t2 cellpadding=0 cellspacing=0 border=0>
		<tr class=recibo_titulo_t1>
		<td>
		<font class="recibo_titulo_parte_concepto">PARTIDA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$partida.'
		<br>CONCEPTO: '.$concepto.'
		<br>CRITERIO:&nbsp;&nbsp;&nbsp;'.trim($referencia).trim($criterio).'</font>
		<br>OBSERVACION:&nbsp;&nbsp;&nbsp;'.trim($observacion2).'<br></font><br>
		</td>
		</tr>
		<tr>
		<td>
		<table width=100% class=recibo_pre_t3 cellpadding=0 cellspacing=3 border=0>
		<tr class=recibo_cabecera_columas_1>
		<td align=center>CANTIDAD</td>
		<td align=center>MONTO</td>
		<td align=center>TOTAL</td>
		</tr>';

				foreach($rowrecibos AS $rowdetalles){
						$varhtml .= '<tr>';
						$varhtml .= '<td align=center>' . $rowdetalles[19] . '</td>';
						$varhtml .= '<td align=center>' . number_format ( $rowdetalles[20], '2', '.', '' ) . '</td>';
						$varhtml .= '<td align=center>' . number_format ( $rowdetalles[5], '2', '.', '' ) . '</td>';
						$varhtml .= '</tr>';
						$subtotal=$rowdetalles[5];
				}
				$nrotostr=new Libreria_Numerosletras();
				$subtotalstr=$nrotostr->num2letras($subtotal);
		$varhtml.='
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td>
		<table cellSpacing=0 cellPadding=0 width=100% border=0>
		<tr>
		<td align=center width=40%>FIRMA/SELLO</td>
		<td><b>TOTAL PAGADO '.$rowdetalles[36].':&nbsp:&nbsp;***</b>'.$subtotal.' Nuevos Soles</td>
		<tr>
		<tr>
		<td colspan=2>Cajero: '.$nomcajero.'&nbsp;&nbsp;
		</td>
		<tr>
		<tr>
		<td colspan=2 align=center>SON: '.strtoupper($subtotalstr).' Nuevos Soles</td>
		<tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>';
	}else{

		$varhtml='
		<div style="left:0px;  z-index:1" class=cuerpo_recibo >
		<table width=100%  border=0 cellspacing=0 cellpadding=0>
		<tr>
		<td align=center  width=100% >
		<table cellSpacing=0 cellPadding=0 width=100% border=0>
		<tr><td width="100%"><h3>'.$local.'</h3></td></tr>
		<tr><td width="100%"><h4>'.$vararea.'</h4></td></tr>
		<tr>
		</tr>
		<tr>
		<td width=100% >
			<table cellSpacing=0 cellPadding=0 width=100% border=0>
				<tr>
					<td nowrap>Fecha/Hora:</td>
					<td nowrap>'.$fechor.'</td>
					<td>Mov:</td><td>'.$recibo.'</td>
					<td>P&aacute;gina:</td>
					<td>1</td>
				</tr>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--Per�odo:--></td>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--1995-9--></td>
					<td>Mov.Cajero:</td>
					<td>'.$movimiento.'</td>
					<td>Caja:</td>
					<td>'.$nrcaja.'</td>
				</tr>
				<tr>
					<td><!--Tipo:--></td>
					<td><!--Otros--></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=6>Nombre: '.$nombre.'</td>
				</tr>
				<tr>
					<td colspan=6>Referencia: '.$referencia.'</td>
				</tr>
			</table>
		</td>
		</tr>
		<tr>
		<td width=100% align=center>
		<table width=100%  class=recibo_pre_t2 cellpadding=0 cellspacing=0 border=0>
		<tr>
		<td>
		<table width=100% class=recibo_pre_t3 cellpadding=0 cellspacing=3 border=0><tr class=recibo_cabecera_columas_1>
		<td align=center>Item</td>
		<td align=center>Partida</td>
		<td align=center>Concepto</td>
		<td align=center>Cantidad</td>
		<td align=center>Monto</td>
		<td align=center>Total</td>
		</tr>';
				foreach($rowrecibos AS $rowdetalles){
						$varhtml .= '<tr>';
						$varhtml .= '<td align=center>1</td>';
						$varhtml .= '<td align=left>' . $rowdetalles[35] . '</td>';
						$varhtml .= '<td align=left>- ' . $rowdetalles[32] . '</td>';
						$varhtml .= '<td align=right style="padding-right:8px">' . $rowdetalles[19] . '</td>';
						$varhtml .= '<td align=right style="padding-right:8px">' . number_format ( $rowdetalles[20], '2', '.', '' ) . '</td>';
						$varhtml .= '<td align=right style="padding-right:8px"><b>' . number_format ( $rowdetalles[5], '2', '.', '' ) . '</b></td>';
						$varhtml .= '</tr>';
						$subtotal=$rowdetalles[5];
				}
				$nrotostr=new Libreria_Numerosletras();
				$subtotalstr=$nrotostr->num2letras($subtotal);

		$varhtml.='
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td>
		<table cellSpacing=0 cellPadding=0 width=100% border=0>
		<tr>
		<td align=center width=40%>FIRMA/SELLO</td>
		<td><b>TOTAL PAGADO '.$rowdetalles[36].':&nbsp&nbsp;***</b>'.$subtotal.' Nuevos Soles</td>
		<tr>
		<tr>
		<td colspan=2>Cajero: '.$nomcajero.'&nbsp;&nbsp;</td>
		<tr>
		<tr>
		<td colspan=2 align=center>SON: '.strtoupper($subtotalstr).' Nuevos Soles</td>
		<tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>';
	}
	$printrecibo.=$varhtml;
	$this->view->varhtml = $printrecibo;
	//echo $idx ;
	}
}

