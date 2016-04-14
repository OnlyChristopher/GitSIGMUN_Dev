<?php

require_once 'Zend/Controller/Action.php';

class MantrepresentanteController extends Zend_Controller_Action
{

   
    public function indexAction()
    {    	
	  	$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$codigo 	= $this->_request->getParam('codigo',''); 
		
		//
		$parametros[] = array('@buscar',3);
		$parametros[] = array('@codigo',$codigo);
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_rentasmain', $parametros);
		
		$val[] = array('#divCodigo',$rows[0][0],"html");
		$val[] = array('#divNombre',$rows[0][1],"html");
		$val[] = array('#divDireccion',$rows[0][3],"html");

    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Mantenimiento de Centro Poblado";
		$this->view->title = "Busqueda de Contribuyentes";
		
		$evt[] = array('#btnBusPoblado',"click","buscarPoblad()");
		
		$evt[] = array('#btnVerPoblado',"click","verdetalle();");
//$evt[] = array('#btnNuevoRepresentante',"click","showPopup('mantrepresentante/formu?codigo='".$codigo.", '#poprepreformu','900','500','Ingreso de Representante');");
		
		$evt[] = array('#btnSalirRepresentante',"click","closePopup('#poprepre');");
		
		
		
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		
					
    }
	
	
	
	public function consultaAction()
    {
    	$codigo = $this->_request->getParam('codigo');
		
    	$cn = new Model_DbDatos_Datos();
    	
		//Para las filas 
		unset($parametros);
    	$parametros[] = array('@busc',4);
		$parametros[] = array('@codigo',$codigo);
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);
				
		$jsonData = array('root'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'cod'=>utf8_encode($row[0]),
						'codigo'=>utf8_encode($row[1]),
						'tipo_relacion'=>$row[31],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'tipo_documento'=>utf8_encode($row[25]),
						'nro_documento'=>utf8_encode($row[16]),
						'direccion'=>utf8_encode($row[32])
						
				);
				$jsonData['root'][] = $entry;
		
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
		
		
		$evt[] = array('#btnBusqueda',"click","showPopup('mantbusqueda/buscar','#popbuscar','700','280','Busquedas','frmbuscar');");
		
		$id = $this->_request->getParam('id','');
		$codigo = $this->_request->getParam('codigo','');
		
		$id_docu="";
		if(strlen($id)>0)
		{
			$parametros[] = array('@busc',6);
			$parametros[] = array('@id',$id);
			$rowRepre = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);
			
			$id = $rowRepre[0][0]; // id de la tabala
			$codigo = $rowRepre[0][1];
			$num_doc = $rowRepre[0][3];
			$nombres = $rowRepre[0][4];
			$paterno = $rowRepre[0][5];
			$materno = $rowRepre[0][6];
			$des_urb = $rowRepre[0][9];
			$des_via = $rowRepre[0][11];
			$referencia = $rowRepre[0][15];
			$manzana = $rowRepre[0][16];
			$lote = $rowRepre[0][17];
			$sub_lote = $rowRepre[0][18];
			$numero = $rowRepre[0][19];
			$departam = $rowRepre[0][20];
			$nestado = $rowRepre[0][13];
			$operador = $rowRepre[0][14];
			$estacion = $rowRepre[0][15];
			$id_via = $rowRepre[0][14];
			$id_urba = $rowRepre[0][13];
			$id_zona = $rowRepre[0][12];
			$nomzona = $rowRepre[0][27];
			$nomurba = $rowRepre[0][28];
			$nomvia = $rowRepre[0][30];
			//$id_docu = $rowRepre[0][2];
			$arComboTipoRepre = $rowRepre[0][30];
			$id_dist = $rowRepre[0][7]; // agregar 
			$tipourb = $rowRepre[0][8];
			$tipovia = $rowRepre[0][10];
			$id_tipo_relacion = $rowRepre[0][31];
			
			$letra1=$rowRepre[0][33];
			$numero2=$rowRepre[0][34];
			$letra2=$rowRepre[0][35];
			$piso=$rowRepre[0][36];
			$numero_interno=$rowRepre[0][37];
			$letra_interno=$rowRepre[0][38];
			$tipo_interior_id=$rowRepre[0][39];
			$tipo_edificio_id=$rowRepre[0][40];
			$tipo_ingreso_id=$rowRepre[0][41];
			$tipo_agrupamiento_id =$rowRepre[0][42];
			$Edificacion=$rowRepre[0][43];
			$Ingreso=$rowRepre[0][44];
			$Agrupamiento=$rowRepre[0][45];
			
			
			
			//$id_docu = $rowContri[0][2];
			
		
			//$id_docu = '01';
			/*
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$str_docu='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){   // combo de tipo documenyo
	        	$str_docu.='<option value="'.$rowsDocumento[$i][0].'"';
				if(substr($rowsDocumento[$i][0],0,2)==$id_docu)
					$str_docu.=' selected ';
				$str_docu.='" >'.$rowsDocumento[$i][1].'</option>';
	        }
			*/
			unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$id_docu='';
			
			for ($i=0;$i<count($rowsDocumento);$i++){
				$cad=explode('/',$rowsDocumento[$i][0]);
				
				if(trim($cad[0])==$rowRepre[0][2])
				{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" selected>'.$rowsDocumento[$i][1].'</option>';
				}
				else{
					$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';						
				}
       		}
			
			
			
			
			
			/*
			$id_dist = '012';
			$nestado = 1;
			$operador = 'prueba';
			$estacion = 'prueba';*/
		}
		
		else
		{
			//$id_docu = '01';
			
			unset($parametros);
		$parametros[] = array('@msquery',1);
		$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
		$id_docu='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){
	            	$id_docu.='<option value="'.$rowsDocumento[$i][0].'" >'.$rowsDocumento[$i][1].'</option>';
	        }
				
			
			$id_dist = '012';
			$nestado = 1;
			$operador = 'prueba';
			$estacion = 'prueba';
		}
		//divCodigo
				
		$val[] = array('#codigo',$codigo,'val');
		$val[] = array('#id_pers',$id,'val');
		$val[] = array('#txtNumDocRepre',$num_doc,'val');
		$val[] = array('#txtNomRepre',$nombres,'val');
		$val[] = array('#txtApePatRepre',$paterno,'val');
		$val[] = array('#txtApeMatRepre',$materno,'val');
		$val[] = array('#txtDesUrb',$des_urb,'val');
		$val[] = array('#txtDesVia',$des_via,'val');
		$val[] = array('#',$referencia,'val');
		$val[] = array('#txtMzRepre',$manzana,'val');
		$val[] = array('#txtLtRepre',$lote,'val');
		$val[] = array('#txtSubLtRepre',$sub_lote,'val');
		$val[] = array('#txtNumRepre',$numero,'val');
		$val[] = array('#txtDptoRepre',$departam,'val');
		$val[] = array('#nestadoRepre',$nestado,'val');
		$val[] = array('#operadorRepre',$operador,'val');
		$val[] = array('#estacionRepre',$estacion,'val');
		$val[] = array('#txtViacodigoRepre',$id_via,'val'); 
		$val[] = array('#txtUrbacodigoRepre',$id_urba,'val');
		$val[] = array('#txtZonacodigoRepre',$id_zona,'val');
		$val[] = array('#txtZonaRepre',$nomzona,'val');
		$val[] = array('#txtUrbanizacionRepre',$nomurba,'val');
		$val[] = array('#txtViaRepre',$nomvia,'val');	
		$val[] = array('#txtletra1',$letra1,'val');
		$val[] = array('#txtnumero2',$numero2,'val');
		$val[] = array('#txtletra2',$letra2,'val');
		$val[] = array('#txtpiso',$piso,'val');
		$val[] = array('#txtnumero_interno',$numero_interno,'val');
		$val[] = array('#txtletra_interno',$letra_interno,'val');
		$val[] = array('#txtEdificacion',$Edificacion,'val');
		$val[] = array('#txtIngreso',$Ingreso,'val');
		$val[] = array('#txtAgrupamiento',$Agrupamiento,'val');
		
		
		//$val[] = array('#cmbDocRepre',$str_docu,'html');
		$val[] = array('#cmbDocRepre',$id_docu,'html');
		
			
		
		/*
		unset($parametros);
		$parametros[] = array('@msquery',1);	
		$documentos = $cn->ejec_store_procedura_sql('contri_doc', $parametros);
		$arDocumentos = $ar->RegistrosCombo($documentos,0,1);
		$val[] = array('#cmbDocContri',$fn->ContenidoCombo($arDocumentos,'[Seleccione]',trim($id_docu)),'html');
		*/
		/*
		$distritos['001'] = "Comas";
		$distritos['002'] = "Los Olivos";
		$distritos['003'] = "Independencia";
		$distritos['004'] = "Puente Piedra";
		$val[] = array('#cmbDisContri',$fn->ContenidoCombo($distritos,'[Seleccione]',trim($id_dist)),'html');		
		$evt[] = array('#cmbDisContri',"change","changeDisContri(this.value)");
		*/
		
		/* luis
		unset($parametros);
			$parametros[] = array('@msquery',1);
			$rowsDocumento = $cn->ejec_store_procedura_sql('Coactivo.SP_Mrecepcion',$parametros);
			$str_docu='';		
			
	    	for ($i=0;$i<count($rowsDocumento);$i++){   // combo de tipo documenyo
	        	$str_docu.='<option value="'.$rowsDocumento[$i][0].'"';
				if(substr($rowsDocumento[$i][0],0,2)==$id_docu)
				{	$str_docu.=' selected ';}
				$str_docu.='" >'.$rowsDocumento[$i][1].'</option>';
	        }
		*/
		
				
		unset($parametros);
		$parametros[] = array('@busc',5);	
		$TipoRepre = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);
		$arComboTipoRepre = $ar->RegistrosCombo($TipoRepre,0,1);
		$val[] = array('#cmbTipoRepre',$fn->ContenidoCombo($arComboTipoRepre,'[Seleccione]',trim($id_tipo_relacion)),'html');
		
		//////////////////////////////////////////////  COMBO INTERIOR   /////////////////////////////////////////////////////
		unset($parametros);
		$parametros[] = array('@busc',8);
		$TipoInterior = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);
		$arComboTipoInterior = $ar->RegistrosCombo($TipoInterior,0,1);
		$val[] = array('#cmbTipoInterior',$fn->ContenidoCombo($arComboTipoInterior,'[seleccione]',trim($tipo_interior_id)),'html');
		
		
		///////////////////////////////////////////////  TIPO EDIFICACION  /////////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',9);
		$tipoEdificacion=$cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante',$parametros);
		$arComboEdificacion=$ar->RegistrosCombo($tipoEdificacion,0,1);
		$val[] = array('#cmbTipoEdificacion',$fn->ContenidoCombo($arComboEdificacion,'[Seleccione]',trim($tipo_edificio_id)),'html');
		///////////////////////////////////////////////// TIPO DE INGRESO  //////////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',10);
		$tipoIngreso=$cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante',$parametros);
		$arComboIngreso=$ar->RegistrosCombo($tipoIngreso,0,1);
		$val[] = array('#cmbTipoIngreso',$fn->ContenidoCombo($arComboIngreso,'[Seleccione]',trim($tipo_ingreso_id)),'html');
		
		//cmbTipoIngreso
		///////////////////////////////////////////  TIPO AGRUPAMIENTO /////////////////////////////////////////////
		unset($parametros);
		$parametros []=array('@busc',11);
		$tipoAgrupamiento=$cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante',$parametros);
		$arComboEdificacion=$ar->RegistrosCombo($tipoAgrupamiento,0,1);
		$val[] = array('#cmbTipoAgrupamiento',$fn->ContenidoCombo($arComboEdificacion,'[Seleccione]',trim($tipo_agrupamiento_id)),'html');
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		unset($parametros);
		$parametros[] = array('@msquery',1);	
		$tipodistrito = $cn->ejec_store_procedura_sql('Contenedor.SP_TblDistrito', $parametros);
		$arCombodistrito = $ar->RegistrosCombo($tipodistrito,0,1);
		$val[] = array('#cmbDis',$fn->ContenidoCombo($arCombodistrito,'[Seleccione]',trim($id_dist)),'html');
		$evt[] = array('#cmbDis',"change","changeDisContri(this.value)");
		//$fun[] = array("changeDisContri('".trim($id_dist)."')");
		
		
		
		unset($parametros);
		$parametros[] = array('@msquery',5);	
		$tipourbanizacion = $cn->ejec_store_procedura_sql('Rentas.SP_MCUrba', $parametros);
		$arTipourbanizacion = $ar->RegistrosCombo($tipourbanizacion,0,1);
		$val[] = array('#cmbTipUrb',$fn->ContenidoCombo($arTipourbanizacion,'[Seleccione]',trim($tipourb)),'html');
		
		/*
		$tipurban['01'] = "Tipo Urba 1";
		$tipurban['02'] = "Tipo Urba 2";
		$tipurban['03'] = "Tipo Urba 3";
		$val[] = array('#cmbTipUrb',$fn->ContenidoCombo($tipurban,'[Seleccione]',trim($tipourb)),'html');
		$val[] = array('#cmbTipUrb',"100","width");
		*/
		
		unset($parametros);
		$parametros[] = array('@busc',7);	
		$tipvia = $cn->ejec_store_procedura_sql('Rentas.sp_MVias', $parametros);
		$arTipovia = $ar->RegistrosCombo($tipvia,0,1);
		$val[] = array('#cmbTipVia',$fn->ContenidoCombo($arTipovia,'[Seleccione]',trim($tipovia)),'html');
		
		/*
		$tipvia['01'] = "Tipo V�a 1";
		$tipvia['02'] = "Tipo V�a 2";
		$tipvia['03'] = "Tipo V�a 3";
		$val[] = array('#cmbTipVia',$fn->ContenidoCombo($tipvia,'[Seleccione]',trim($tipovia)),'html');
		$val[] = array('#cmbTipVia',"100","width");
		*/
		$zonas['01'] = "Zona 1";
		$zonas['02'] = "Zona 2";
		$zonas['03'] = "Zona 3";
		$val[] = array('#cmbZonaRepre',$fn->ContenidoCombo($zonas,'[Seleccione]',trim($id_zona)),'html');
		
		$urbans['0001'] = "Urbanizaci�n 1";
		$urbans['0002'] = "Urbanizaci�n 2";
		$urbans['0003'] = "Urbanizaci�n 3";
		$val[] = array('#cmbUrbRepre',$fn->ContenidoCombo($urbans,'[Seleccione]',trim($id_urba)),'html');
		
		$vias['0001'] = "V�a 1";
		$vias['0002'] = "V�a 2";
		$vias['0003'] = "V�a 3";
		$val[] = array('#cmbViaRepre',$fn->ContenidoCombo($vias,'[Seleccione]',trim($id_via)),'html');
		
		$evt[] = array('#txtNomRepre',"focus","");
		
		$evt[] = array('#btnGrabaRepre',"click","goToFormulario('frmrepre');");   // BOTON GUARDAR  
		$evt[] = array('#btnSalirRepre',"click","closePopup('#poprepreformu');");
		
		$evt[] = array('#txtNomRepre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtNomRepre',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#txtApePatRepre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApePatRepre',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtApeMatRepre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtApeMatRepre',"blur","this.value = this.value.toUpperCase();");
		
		//$evt[] = array('#txtRefRepre',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtRefRepre',"blur","this.value = this.value.toUpperCase();");

		$evt[] = array('#cmbDocRepre',"change","validaText($(this).val());");
		$evt[] = array('#txtNumDocRepre',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNumDocRepre',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtDesVia',"keypress","return validaTeclas(event,'alpha');");
		$evt[] = array('#txtDesVia',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtDesUrb',"keypress","return validaTeclas(event,'alpha');");
		$evt[] = array('#txtDesUrb',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtNumRepre',"keypress","return validaTeclas(event,'alpha');");		
					
		$evt[] = array('#txtMzRepre',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtLtRepre',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtSubLtRepre',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtNumRepre',"blur","this.value = this.value.toUpperCase();");
		$evt[] = array('#txtDptoRepre',"blur","this.value = this.value.toUpperCase();");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		//$fn->EjecutarFuncion($fun);
    }
  
  
  
  
  		 public function grabarAction()
   		{
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			$cn = new Model_DbDatos_Datos();
			
			$id = $this->_request->getPost('id_pers');
			$codigo = $this->_request->getPost('codigo');
			
			if(strlen($id)>0)
				$tip = 2;
			else
				$tip = 1;
				
			$parametros[] = array('@busc',$tip);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@id',$id);
			$parametros[] = array('@id_docu',$this->_request->getPost('cmbDocRepre'));
			$parametros[] = array('@num_doc',$this->_request->getPost('txtNumDocRepre'));
			$parametros[] = array('@nombres',$this->_request->getPost('txtNomRepre'));
			$parametros[] = array('@paterno',$this->_request->getPost('txtApePatRepre'));
			$parametros[] = array('@materno',$this->_request->getPost('txtApeMatRepre'));
			$parametros[] = array('@id_dist',$this->_request->getPost('cmbDis'));
			$parametros[] = array('@tipourb',$this->_request->getPost('cmbTipUrb'));
			$parametros[] = array('@des_urb',$this->_request->getPost('txtDesUrb'));
			$parametros[] = array('@tipovia',$this->_request->getPost('cmbTipVia'));
			$parametros[] = array('@des_via',$this->_request->getPost('txtDesVia'));
			$parametros[] = array('@id_zona',$this->_request->getPost('txtZonacodigoRepre'));
			$parametros[] = array('@id_urba',$this->_request->getPost('txtUrbacodigoRepre'));
			$parametros[] = array('@id_via',$this->_request->getPost('txtViacodigoRepre'));
			$parametros[] = array('@referencia',$this->_request->getPost('txtRefRepre'));
			$parametros[] = array('@manzana',$this->_request->getPost('txtMzRepre'));
			$parametros[] = array('@lote',$this->_request->getPost('txtLtRepre'));
			$parametros[] = array('@sub_lote',$this->_request->getPost('txtSubLtRepre'));
			$parametros[] = array('@numero',$this->_request->getPost('txtNumRepre'));
			$parametros[] = array('@departam',$this->_request->getPost('txtDptoRepre'));
			$parametros[] = array('@nestado',$this->_request->getPost('nestadoRepre'));
			$parametros[] = array('@operador',$this->_request->getPost('operadorRepre'));
			$parametros[] = array('@estacion',$this->_request->getPost('estacionRepre'));
			$parametros[] = array('@id_tipo_relacion',$this->_request->getPost('cmbTipoRepre'));
			$parametros[] = array('@letra1',$this->_request->getPost('txtletra1'));
			$parametros[] = array('@numero2',$this->_request->getPost('txtnumero2'));
			$parametros[] = array('@letra2',$this->_request->getPost('txtletra2'));
			$parametros[] = array('@piso',$this->_request->getPost('txtpiso'));
			$parametros[] = array('@numero_interno',$this->_request->getPost('txtnumero_interno'));
			$parametros[] = array('@letra_interno',$this->_request->getPost('txtletra_interno'));
			$parametros[] = array('@tipo_interior_id',$this->_request->getPost('cmbTipoInterior'));
			$parametros[]= array('@tipo_edificio_id',$this->_request->getPost('cmbTipoEdificacion'));
			$parametro[]= array('@tipo_ingreso_id',$this->_request->getPost('cmbTipoIngreso'));
			$parametros[] = array('@tipo_ingreso_id',$this->_request->getPost('cmbTipoIngreso'));
			$parametros[] = array('@tipo_agrupamiento_id',$this->_request->getPost('cmbTipoAgrupamiento'));
			$parametros[] =array('@nombre_edificio',$this->_request->getPost('txtEdificacion'));
			$parametros[] = array('@nombre_ingreso',$this->_request->getPost('txtIngreso'));
			$parametros[] = array('@nombre_agrupamiento',$this->_request->getPost('txtAgrupamiento'));
											
			@$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);
			
			echo "Se grabo correctamente";
    	}    	
    }
  
  
  public function eliminarAction()
    {		
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
		
    		$cn = new Model_DbDatos_Datos();
			
			
    		
			$id = $this->_request->getParam('cod','');
			$codigo = $this->_request->getParam('codigo','');
			//$id = $this->_request->getParant('id','');
			
			
			//echo $codigo."<br/>";
			$parametros[] = array('@busc',7);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@id',$id);
			
			
			@$rows = $cn->ejec_store_procedura_sql('Rentas.sp_Mrepresentante', $parametros);	
			
			echo "Registro eliminado correctamente!";
		}

	}
	
	
	
  
  
  
  
  
  
  
  
}

