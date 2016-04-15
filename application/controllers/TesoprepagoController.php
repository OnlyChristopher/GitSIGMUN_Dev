<?php

require_once 'Zend/Controller/Action.php';

class tesoprepagoController extends Zend_Controller_Action
{
	/**
	 * The default action - show the home page
	 */
	public function indexAction()
	{
		$fn = new Libreria_Pintar();
		
		$cn = new Model_DbDatos_Datos();
    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$totalpagar = $this->_request->getParam('totalpagar','');	
		//manuel
		$criterio = $this->_request->getParam('criterio','');	
		$val[] = array('#txtmicriterio',$criterio,'val');		
		//-------
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
    	
		$evt[] = array('#rdformaPago1',"click","getTipoPago();");
		$evt[] = array('#rdformaPago2',"click","getTipoPago();");
		$evt[] = array('#rdformaPago3',"click","getTipoPago();");
		$evt[] = array('#txtFecCobro',"datepicker","");
		
		$evt[] = array('#btnAceptarPago',"click","getcancelar();");
		$evt[] = array('#btnprintSalir',"click","closePopup('#popprintdeuda');");
		$evt[] = array('#txtEfectivo',"change", "restarmontosapagar();");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
	}
	
	public function imprimirrecibopagosAction(){
		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$nrorecibo = $this->_request->getParam('nrorecibo','');
		$printrecibo='';
		$arraydatos=null;
		$printrecibo='';
		$htmlhead='';
		$htmlbody='';
		$htmlfoot='';
		
		$cn = new Model_DbDatos_Datos();
		$nrotostr = new Libreria_Numerosletras();
		
    	$nombrestore01  = 'Caja.sp_Recibos_emitidos';
		$arraydatos01[] = array("@buscar", '4');
		$arraydatos01[] = array("@num_ingr ", $nrorecibo);
		$rowrecibos01 = $cn->ejec_store_procedura_sql($nombrestore01, $arraydatos01);
		
		///en caso q la impresion sea grande y se haya cortado la impresion por algun problema
		$nsuma=0;
		if($nrorecibo=='000406391'){
		$nsuma=0;//$nsuma=220;////numero en q se quedo
		}else{$nsuma=0;}
		
		$tamPag = 15;
		$count = 0;
		
		if(count($rowrecibos01) > 0){
			
			foreach($rowrecibos01 AS $rowcabecera){
				
				$count++;
				
				//Para la cabecera
				$cab[$count]="";
				$pie[$count]="";
				$partes[$count]=0;
				
				$cod_pred=$rowcabecera[2];
				$anexo=$rowcabecera[3];
				$sub_anexo=$rowcabecera[4];
				$tipo=$rowcabecera[5];
				$monto_total=$rowcabecera[8];
				
				unset($arraydatos);
				$nombrestore  = 'Caja.sp_Recibos_emitidos';
				$arraydatos[]=array("@buscar", '5');
				$arraydatos[]=array("@num_ingr", trim($nrorecibo));
				$arraydatos[]=array("@cod_pred", trim($cod_pred));
				$arraydatos[]=array("@anexo", trim($anexo));
				$arraydatos[]=array("@sub_anexo", trim($sub_anexo));
				$arraydatos[]=array("@tipo", trim($tipo));
				
				$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
				
				$local='Municipalidad de Independencia';
				$vararea='Sub Gerencia de Tesoreria';
				$varruc='20131373661';
				$recibo=$rowrecibos[0][0];
				$codigo=$rowrecibos[0][1];
				$nombre=$rowrecibos[0][2];
				$fechor=$rowrecibos[0][4];
				$nrcaja=$rowrecibos[0][3];
				$total=$rowrecibos[0][5];
				$nomcajero=$rowrecibos[0][9];
				$concepto=$rowrecibos[0][16];
				$direccionfis=$rowrecibos[0][25];
				$subtotal=0;
				$movimiento=$rowrecibos[0][26];
				
				//AGREGO MANUEL
				$p_cuenta=$rowrecibos[0][31];
				if($p_cuenta=="PC")
				{
					$variable2='PAGO A CUENTA';
				}
				else{
					$variable2='TOTAL PAGADO';
				}

				$partes[$count] = ceil(count($rowrecibos)/$tamPag);
				
				$cab[$count]='
				<br/>
				<div style="padding:10px 20px 0 60px">
				<table width="80%" cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr>
				<td width="100%" align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tbody>
				<tr><td width="100%"><h3>'.$local.'</h3></td></tr>
				<tr><td width="100%"><h4>'.$vararea.'</h4></td></tr>
				<tr>
				<td width="100%">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="text1">
				<tbody>
				<tr>
				<td nowrap="">&nbsp;</td>
				<td nowrap="">&nbsp;</td>
				<td>Mov:</td><td>'.$recibo.'</td>
				<td>P&aacute;gina:</td><td>|%| de '.$rowcabecera[10].'</td>
				</tr>
				<tr>
				<td>Fecha/Hora:</td>
				<td>'.$fechor.'</td>
				<td>Mov.Cajero:</td>
				<td>'.$movimiento.'</td>
				<td>Caja:</td>
				<td>'.$nrcaja.'</td>
				</tr>
				<tr>
				<td colspan="6">Contrib:   COD. '.$codigo.'-'.$nombre.'</td>
				</tr>
				<tr>
				<td colspan="6">Dom.Fiscal: '.$direccionfis.'</td>
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				<tr>
				<td width="100%" align="center">
				<table width="100%" cellspacing="0" cellpadding="0" border="0" class="text1">
					<tbody>
					<tr class="recibo_titulo_t1">
					<td><font class="recibo_titulo_parte_concepto">CONCEPTO: '.$concepto.'</font></td>
					</tr>
						<tr>
							<td>
								<table width="100%" cellspacing="1" cellpadding="0" border="0" class="text2">
									<tbody>
									<tr class="recibo_cabecera_columas_1"><td></td><td align="center"></td><td align="center">A&ntilde;o</td><td align="center">Per.</td><td align="center">Insol</td><td align="center">Reaj</td><td align="center">Der<br>Emi</td><td align="center">Int<br>Capi</td><td align="center">Int</td><td align="center">Benf</td><td align="center">TOT<br>PAGO</td></tr>';
				

																		
									$idx[0] = 0;	
									
									for($i=1;$i<=$partes[$count];$i++){
										
										$numRows = 0;	
										$subtotal = 0;
										foreach($rowrecibos AS $rowdetalles){
											$numRows++;
											$rowsItem++;
																				
											if($numRows>$idx[$i-1] && $numRows<=$i*$tamPag){											
												$det[$count][$i] .= '<tr><td></td><td>' . $rowdetalles[17] . '</td><td align="center" class="recibo_col_anno">' . $rowdetalles[15] . '</td><td class="recibo_col_periodos">' . $rowdetalles[19] . '</td><td align="center">' . number_format ( $rowdetalles[20], '2', '.', '' ) . '</td><td align="center">' . number_format ( $rowdetalles[21], '2', '.', '' ) . '</td><td align="center">' . number_format ( $rowdetalles[23], '2', '.', '' ) . '</td><td align="center">0.00</td><td align="center">' . number_format ( $rowdetalles[22], '2', '.', '' ) . '</td><td align="center">('.$rowdetalles[27].')</td><td align="center" class="recibo_col_total">' . number_format ( $rowdetalles[24], '2', '.', '' ) . '</td></tr>';
												$idx[$i] = $rowdetalles[28];
												$subtotal+=$rowdetalles[24];												
												$subtotalstr=$nrotostr->num2letras($subtotal);
											}																																			
										}
										
										if($i==$partes[$count]) {

/*
											$count_round  = count($rowrecibos01);

											if(count($rowrecibos01) == 4 )
											{
												$count_round_final = $count_round;
											}else{
												$count_round_final = $count_round + 1;
											}
*/

											if($count==count($rowrecibos01))
												$numRepeat = 0;
											else
												$numRepeat = $i*$tamPag - $rowdetalles[28];
											//echo $numRepeat.'-';
												
											$det[$count][$i] .= str_repeat("<tr><td colspan='10'>&nbsp;</td></tr>", $numRepeat);
										}
										$nro_cheque = $rowdetalles[30]=='' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : $rowdetalles[30];
										
										$det[$count][$i] .= '<tr><td colspan="10"><table width="100%" cellspacing="0" cellpadding="0" border="0" class="text1">
										<tr>
											<td>Cajero: '.$nomcajero.'&nbsp;&nbsp;</td>
											<td>'.$nro_cheque.'</td>
											<td align="center"><b>'.$variable2.' '.trim($rowdetalles[29]).':&nbsp;&nbsp;***</b>'.$subtotal.' Nuevos Soles</td>
										</tr>
										<tr>
											<td align="center" colspan="3">SON: '.strtoupper($subtotalstr).' Nuevos Soles</td>
										</tr>
										</table></td></tr>';
									}
																		
									
								$pie[$count].='
								</tbody>
								</table>								
							</td>
						</tr>
					</tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>
				</td>
				</tr>
				</tbody>
				</table>				
				</div>
							';
				
				
			}
			
			$printrecibo = "";
			for($i=1;$i<=$count;$i++){								
				for($j=1;$j<=$partes[$i];$j++){
					$printrecibo .= str_replace('|%|',$j,$cab[$i]);
					$printrecibo .= $det[$i][$j];
					$printrecibo .= $pie[$i];
				}
			}
			
			$printrecibo .= '<div style="margin-top: 20px; text-align:center; width:80%">
			<b>TOTAL GENERAL: '.$monto_total.'</b><br/>
			'.$nrotostr->num2letras($monto_total).'			
			</div>';
				
			$this->view->varhtml = $printrecibo;
		}
	}
	
	#Reimprimir Recibo
	public function reimprimirreciboAction(){

		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

		$nrorecibo = $this->_request->getParam('nrorecibo','');
				
			
		$cn = new Model_DbDatos_Datos();
		$nrotostr = new Libreria_Numerosletras();
		
		$nombrestore  = 'Caja.sp_Recibos_emitidos';
		$arraydatos[] = array("@buscar", '4');	#CABECERA DE RECIBO
		$arraydatos[] = array("@num_ingr", $nrorecibo);

    	$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
		$count_round  = count($rowrecibos);
		
		
			echo ' <script language=\'javascript\'>';
			for($i=0;$i<=$count_round;$i++){
				$cod_pred=$rowrecibos[$i][2]; 		#codido del predio
				$anexo=$rowrecibos[$i][3];			#anexo 
				$sub_anexo=$rowrecibos[$i][4];		#sub anexos
				$tipo=$rowrecibos[$i][5];			#tipo de tributo
				$destributo = $rowrecibos[$i][6];
				$periodo_anio=$rowrecibos[$i][10];	#total
				$dire_predio= $rowrecibos[$i][11];	#dire predio
				
				echo 'window.open(\'imprimirrecibopagos3?nrorecibo='.$rowrecibos[$i][1].'&cod_pred='.$cod_pred.'&anexo='.$anexo.'&sub_anexo='.$sub_anexo.'&tipo='.$tipo.'&desctrib='.$destributo.'&periodo_anio='.$periodo_anio.'&direpredio='.$dire_predio.'\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); ';
			}	
				//echo 'window.open(\'imprimirrecibopagos3?nrorecibo=000022430&cod_pred=020002562&anexo=0001&sub_anexo=0001&tipo=11.00&desctrib=ARBITRIO&periodo_anio=2016&direpredio=020002562 - JR. HUATANAY Nro. 110   Ref: Mz. D Lote 28 TAHUANTINSUYO\',"_blank","width=570, height=500, scrollbars=no, menubar=no, location=no, resizable=no,status =no,directories=no"); ';
			echo ' </script>';
		
	}
	
	#Imprimir nuevo formato de recibos
	public function imprimirrecibopagos3Action(){
		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;

		$this->view->icono = $path->data."img/logo_muni2.jpg";

		$nrorecibo = $this->_request->getParam('nrorecibo','');
		
		$cod_pred= $this->_request->getParam('cod_pred','');#codido del predio
		$anexo= $this->_request->getParam('anexo','');		#anexo 
		$sub_anexo= $this->_request->getParam('sub_anexo','');	#sub anexos
		$tipo= $this->_request->getParam('tipo','');		#tipo de tributo
		$tipodesc= $this->_request->getParam('desctrib','');	#descripcion del tributo
		$periodo_anio=$this->_request->getParam('periodo_anio','');#total
		$direpredio = $this->_request->getParam('direpredio','');#total
		
		
		$printrecibo='';
		$arraydatos=null;
		$printrecibo='';
		$htmlhead='';
		$htmlbody='';
		$htmlfoot='';
		
		$cn = new Model_DbDatos_Datos();
		$nrotostr = new Libreria_Numerosletras();
		
    	$nombrestore01  = 'Caja.sp_Recibos_emitidos';
		$arraydatos01[] = array("@buscar", '4');	#CABECERA DE RECIBO
		$arraydatos01[] = array("@num_ingr", $nrorecibo);

		$rowrecibos01 = $cn->ejec_store_procedura_sql($nombrestore01, $arraydatos01);

				//http://sginfo04:8076/tesoprepago/imprimirrecibopagos2?nrorecibo=000376834
		///en caso q la impresion sea grande y se haya cortado la impresion por algun problema
		$nsuma=0;
		if($nrorecibo=='000376834'){
			$nsuma=0;//$nsuma=220;////numero en q se quedo
		}else{
			$nsuma=0;}
		
		$tamPag = 12;
		$count = 0;
		
		if(count($rowrecibos01) > 0){
			
			//foreach($rowrecibos01 AS $rowcabecera){
				
				$count++;	#contar 
				
				//Para la cabecera
				$anio[$count]="";
				$cab[$count]="";
				$pie[$count]="";
				$partes[$count]=0;
				/*
				$cod_pred=$rowcabecera[2]; #codido del predio
				$anexo=$rowcabecera[3];		#anexo 
				$sub_anexo=$rowcabecera[4];	#sub anexos
				$tipo=$rowcabecera[5];		#tipo de tributo
				

				$periodo_anio=$rowcabecera[10];#total
				*/
				$monto_total=$rowcabecera[8];#total
				//$tipodesc=$rowrecibos01[0][6];	#descripcion del tributo
				
				
				unset($arraydatos);
				$nombrestore  = 'Caja.sp_Recibos_emitidos';
				$arraydatos[]=array("@buscar", '5'); 	#imprimir Recibos DETALLE
				$arraydatos[]=array("@num_ingr", trim($nrorecibo));
				$arraydatos[]=array("@cod_pred", trim($cod_pred));
				$arraydatos[]=array("@anexo", trim($anexo));
				$arraydatos[]=array("@sub_anexo", trim($sub_anexo));
				$arraydatos[]=array("@tipo", trim($tipo));
				$arraydatos[]=array("@periodo_anio", trim($periodo_anio));
				
				$rowrecibos = $cn->ejec_store_procedura_sql($nombrestore, $arraydatos);
				
				$local='MUNICIPALIDAD DISTRITAL DE <BR> INDEPENDENCIA';
				$vararea='Sub Gerencia de Tesoreria';
				$varruc='RUC 20131373661';
				$recibo=$rowrecibos[0][0];
				$codigo=$rowrecibos[0][1];	#codigo de Contribuyente
				$nombre=$rowrecibos[0][2];	#nombre de contribuyente
				$fechor=$rowrecibos[0][4];
				$nrcaja=$rowrecibos[0][3];	#numero de caja
				$total=$rowrecibos[0][5];	#total del tributo
				$estado=$rowrecibos[0][7];	#estado actual
				$nomcajero=$rowrecibos[0][9];	#nombre del cajero
				$concepto=$rowrecibos[0][16];	#concepto de pago
				$direccionfis=$rowrecibos[0][23];#direccion fiscal
				$subtotal=0;
				$movimiento=$rowrecibos[0][24];
				
				//AGREGO MANUEL
				$p_cuenta=$rowrecibos[0][31];

				if($p_cuenta=="PC"){
					$variable2='PAGO A CUENTA';
				}
				else{
					$variable2='TOTAL PAGADO';
				}

				$partes[$count] = ceil(count($rowrecibos)/$tamPag);	//ceil(4.3);
				
				$cab[$count]='
				<br/>
				<div>
				<table width="100%">
				<tbody>
				<tr>
					<td width="100%" align="center">
						<table width="100%" border="0" style="border-spacing:0;">
						<tbody>
						<tr>
							<td>
								<table style="margin:auto">
								<tr>
									<td><img src="'.$path->data."img/logo_muni2.jpg".'" width="25px" style="vertical-align:top"></td>
									<td align="center" style="font-weight:bolder; font-family:arial;font-size:12px;">'.$local.'</td>
								</tr>
								</table>
							</td>
						</tr>
						<tr><td width="100%" style="font-weight:bold;font-family:arial;font-size:11px;text-align:center; ">'.$vararea.'</td></tr>
						<tr><td width="100%" style="font-weight:bold;font-family:arial;font-size:10px;text-align:center;">'.$varruc.'</td></tr>
						
						<tr>
							<td width="100%">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" class="text1">
								<tbody>
								<tr>
									<td class="NEGRITA">N&deg; RECIBO:</td><td style="font-size:13px">'.$nrorecibo.'</td>
									<td class="NEGRITA">MOV.:</td><td style="font-size:12px">'.str_pad($movimiento, 5, "0", STR_PAD_LEFT).'</td>
								</tr>
								<tr>
									<td align="center" colspan="2"><hr></td>
								</tr>
								
								<tr>
									<td class="NEGRITA" colspan="2">NOMBRE:</td>
								</tr>
								<tr>
									<td colspan="2" style="font-size:12px">'.$nombre.'</td>
								</tr>
								<tr>
									<td class="NEGRITA">CODIGO:</td><td>'.$codigo.'</td>
								</tr>
								<tr>
									<td colspan="2" class="NEGRITA">DOMICILIO FISCAL:</td>
								</tr>
								<tr>
									<td colspan="2">'.$direccionfis.'</td>
								</tr>
								<tr>
									<td class="NEGRITA">FECHA:</td><td>'.$fechor.'</td>
								</tr>
								<tr>
									<td align="center" colspan="2"><hr></td>
								</tr>
								</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td width="100%" align="center">
								<table width="100%" cellspacing="0" cellpadding="0" border="0" class="text1">
								<tbody>
								<tr class="recibo_titulo_t1">
									<td><font class="NEGRITA">'.$tipodesc.' - '. $periodo_anio.'</font></td>
								</tr>
								<tr>
								<td><font class="NEGRITA">'.$direpredio.'</font></td>
								</tr>
								
								<tr class="recibo_titulo_t1">
									<td align="center"><font class="NEGRITA"></font></td><!-- PERIODO AÑO 1999   $rowrecibos[0][15]-->
								</tr>
									<td align="center"><hr></td>
								<tr>
									<td>
										<table width="100%" border="0" class="text2" style="border-spacing:0">
										<tbody>
										<tr class="recibo_cabecera_columas_1">
											<!--<td>Trib.</td>-->
											<td align="center">Periodo</td>
											<td align="center">Anexo</td>
											<td align="center">Emis</td>
											<td align="center">Total</td></tr>
										</tr>
											<td align="center" colspan="5"><hr></td>
										<tr>';
									
								$idx[0] = 0;	
									
								for($i=1;$i<=$partes[$count];$i++){
									$numRows = 0;	
									$subtotal = 0;
									$subtotald =0;
									foreach($rowrecibos AS $rowdetalles){
										$numRows++;
										$rowsItem++;
										//Costo de Emision
										$rowdetalles[21]>0 ? $costo_emi = $rowdetalles[21]: $costo_emi='';
										
										if($numRows>$idx[$i-1] && $numRows<=$i*$tamPag){										
									$det[$count][$i] .= '<tr><!--<td>' . $rowdetalles[16] . '</td>-->
															<td class="recibo_col_periodos" style="text-align:center">' . $rowdetalles[17] . '</td>
															<td align="center">' . $rowdetalles[13] . '</td>
															<td align="center">' . $costo_emi . '</td>
															<td align="center" class="recibo_col_total"><div style="width:90%; margin:auto; text-align: right;">' . number_format ($rowdetalles[22], '2', '.', '' ) . '</div></td></tr>';
											$idx[$i] = $rowdetalles[28];
											$subtotal+=$rowdetalles[24];	
											$subtotald+=$rowdetalles[22];							
											$subtotalstr=$nrotostr->num2letras($subtotal);
										}
									}
										
										if($i==$partes[$count]) {
											if($count==count($rowrecibos01))
												$numRepeat = 0;
											else
												$numRepeat = $i*$tamPag - $rowdetalles[26];
											//echo $numRepeat.'-';
												
											$det[$count][$i] .= str_repeat("", $numRepeat);
										}

										$nro_cheque = $rowdetalles[30]=='' ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : $rowdetalles[28];
														
					$det[$count][$i] .= '<tr>
											<td colspan="10">
												<table width="100%" border="0" class="text1" style="border-spacing:0">
												<tr>
													<td class="NEGRITA" colspan="4" align="RIGHT">--------&nbsp;&nbsp;</td>
												</tr>
												<tr>
													<td class="NEGRITA" colspan="3" align="RIGHT" style="width:78%">TOTAL '.$rowdetalles[15].' S/. </td>
													<td class="NEGRITA" align="RIGHT"><div style="width:90%; margin:auto; text-align: right;">'.trim($subtotald).'</div></td>
												</tr>
												</tr>
													<td align="center"colspan="4"><hr></td>
												</tr>
												<tr>
													<td class="NEGRITA" colspan="2">T. OPERACION:</td><td colspan="2">'.trim($rowdetalles[27]).'</td>
												</tr>
												<tr>
													<td class="NEGRITA">CAJA:</td><td>'.trim($rowdetalles[3]).'</td>
													<td class="NEGRITA">CAJERO:</td><td>'.trim($rowdetalles[8]).'</td>
												</tr>
												<tr>
													<td colspan="4" style="text-align:center; font-size:15px;">**** '.$estado.' ****</td>
												</tr>
												<tr>
													<td class="NEGRITA" colspan="4" align="CENTER" style="font-size:14px">PAGO TOTAL: S/. '.number_format (trim($rowdetalles[5]), '2', '.', ',' ).'</td>
												</tr>												
												</table>
											</td>
										</tr>';
										}																																					
						$pie[$count].='	</tbody>
										</table>								
									</td>
								</tr>
								</tbody>
								</table>
							</td>
						</tr>
						</tbody>
						</table>
					</td>
				</tr>
				</tbody>
				</table>				
				</div>
						';
				
				
			//}
			
			$printrecibo = "";
			for($i=1;$i<=$count;$i++){								
				for($j=1;$j<=$partes[$i];$j++){
					$printrecibo .= str_replace('|%|',$j,$cab[$i]);
					$printrecibo .= $det[$i][$j];
					$printrecibo .= $pie[$i];
				}
			}
								
			$this->view->varhtml = $printrecibo;
		}
	}
	

}
