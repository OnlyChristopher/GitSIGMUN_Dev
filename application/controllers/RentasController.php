<?php

class RentasController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {    		
    	$this->_helper->layout->disableLayout();
    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Descripción de Predios";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#btnShow',"button","");
		
		$evt[] = array('#btnShow',"click","showPopup('rentas/predios','#popuppred','1000','580','Mantenimiento de Predios');");
		
		$fn->PintarEvento($evt);
		
    }
    
    public function gprediosAction(){
    	
    	$login = new Zend_Session_Namespace('login');
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
				
			$cn = new Model_DbDatos_Datos();
			$ar = new Libreria_ArraysFunctions();
			
			$json = $this->_request->getPost('json');
			$data = json_decode($json);
			
			$path=	new Zend_Session_Namespace('path');
		
			$codigo=$path->codigo;
			$anno=$path->anno;
			// /**************DATOS DE PREDIO**************/	
			
			$cod_pred=trim($data->hd_idanexo);
			$anexo=trim($data->hd_anexo);
			$sub_anexo=trim($data->hd_subanexo);
			$txtCp=trim($data->txtCp);
			$txtVia=trim($data->txtVia);
			$txtCvia=trim($data->txtCvia);
			$txtDir=strtoupper(trim($data->txtDir));
			$txtNro=strtoupper(trim($data->txtNro));
			$txtDpto=strtoupper(trim($data->txtDpto));
			$txtMza=strtoupper(trim($data->txtMza));
			$txtLte=strtoupper(trim($data->txtLte));
			$txtSubLte=strtoupper(trim($data->txtSubLte));
			$txtFrontis=str_replace(",","",trim($data->txtFrontis));
			$cmbUso=$data->cmbUso;
			$cmbTipPredio=trim($data->cmbTipPredio);
			$cmbEstadoConst=trim($data->cmbEstadoConst);
			$cmbCondicion=trim($data->cmbCondicion);
			$txtNroPiso=trim($data->txtNroPiso);
			$chAfectoPred=trim($data->chAfectoPred);
			$chbVendido=trim($data->chbVendido);
			$txtNroCond=trim($data->txtNroCond);
			$txtAreaTerreno=str_replace(",","",trim($data->txtAreaTerreno));
			$txtPorcenPropiedad=$data->txtPorcenPropiedad;
			$txtAreaComun=str_replace(",","",trim($data->txtAreaComun));
			$txtFecAdqui=trim($data->txtFecAdqui);
			$txtFecTrans=trim($data->txtFecTrans);
			$chbLicencia=trim($data->chbLicencia);
			$chbConformidad=trim($data->chbConformidad);
			$chbDeclaracionFab=trim($data->chbDeclaracionFab);
			//$txtAgua=strtoupper(trim($data->txtAgua));
			$txtLuz=strtoupper(trim($data->txtLuz));
			$txtObs=strtoupper(trim($data->txtObs));
			$txtAreaUso=str_replace(",","",strtoupper(trim($data->txtAreaUso)));
			///mio
			$txtNro2=strtoupper(trim($data->txtNro2));
			$txtLetra=strtoupper(trim($data->txtLetra));
			$txtLetra2=strtoupper(trim($data->txtLetra2));
			$cmbInterior=trim($data->cmbInterior);
			$txtFondo=strtoupper(trim($data->txtFondo));
			
			$txtDocEspecial=strtoupper(trim($data->txtDocEspecial));
			$txtNroDocEspecial=strtoupper(trim($data->txtNroDocEspecial));
			$txtFechDocEspecial=strtoupper(trim($data->txtFechDocEspecial));
			$txtFechDocEspecialInicial=strtoupper(trim($data->txtFechDocEspecialInicial));
			$txtFechDocEspecialFinal=strtoupper(trim($data->txtFechDocEspecialFinal));
			
			$cmbSituacionPredio=strtoupper(trim($data->cmbSituacionPredio));
			$txtSituacionDocumento=strtoupper(trim($data->txtSituacionDocumento));
			$txtSituacionNroDoc=strtoupper(trim($data->txtSituacionNroDoc));
			$txtSituacionFechDoc=strtoupper(trim($data->txtSituacionFechDoc));
			
			$txtFechaFisca=strtoupper(trim($data->txtFechaFisca));
			$txtNroFisca=strtoupper(trim($data->txtNroFisca));
			
			$cmbTipoEdificio=strtoupper(trim($data->cmbTipoEdificio));
			$txtNomEdificio=strtoupper(trim($data->txtNomEdificio));
			$txtPiso=strtoupper(trim($data->txtPiso));
			$txtNumeroInterno=strtoupper(trim($data->txtNumeroInterno));
			$txtLetraInterno=strtoupper(trim($data->txtLetraInterno));
			$cmbTipoIngreso=strtoupper(trim($data->cmbTipoIngreso));
			$txtNomIngreso=strtoupper(trim($data->txtNomIngreso));
			$cmbTipoAgrupamiento=strtoupper(trim($data->cmbTipoAgrupamiento));
			$txtNomAgrupamiento=strtoupper(trim($data->txtNomAgrupamiento)); 
			   
			$cmbCondicionpredio=trim($data->cmbCondicionpredio);
			//
			/******ARBITRIOS*****/
			$txtArbAfecto=strtoupper(trim($data->txtArbAfecto));
			$cbAfectMesDesde=strtoupper(trim($data->cbAfectMesDesde));
			$cbAfectAnnoDesde=strtoupper(trim($data->cbAfectAnnoDesde));
			$cbAfectMesHasta=strtoupper(trim($data->cbAfectMesHasta));
			$cbAfectAnnoHasta=strtoupper(trim($data->cbAfectAnnoHasta));
			$txtUbiPar=strtoupper(trim($data->txtUbiPar));
			$txtArbObs=strtoupper(trim($data->txtArbObs));
			/****USOS************/
			$cb_limpieza=strtoupper(trim($data->cb_limpieza));
			$cb_barrido=strtoupper(trim($data->cb_barrido));
			$cb_parque=strtoupper(trim($data->cb_parque));
			$cb_serenazgo=strtoupper(trim($data->cb_serenazgo));
			$cmbTipoAdqui=strtoupper(trim($data->cmbTipoAdqui));
			$cmbMotivoReg=strtoupper(trim($data->cmbMotivoReg));
			$cmbMotivoDec=strtoupper(trim($data->cmbMotivoDec));
			
			$chCalPredial=trim($data->chCalPredial);
			$chCalArbitrio=trim($data->chCalArbitrio);
					
			$tipo_mov=trim($data->hd_tipomov);
			$cb_notaria=trim($data->cb_notaria);
			
			$rj_predial=0;
			//unset($nombreprocedure);
			unset($arraydatos);
			$nombreprocedure="Rentas.Stp_correlativo";
			$arraydatos[]=array("@id",'06');
			
			$rj_rows=$cn->ejec_store_procedura_sql("Rentas.Stp_correlativo",$arraydatos);
			$rj_predial=$rj_rows[0][0];
			
			switch ($tipo_mov) {
				case 'N':
					$cod_pred="";
					$anexo="";
					$sub_anexo="";
					break;
				case 'I':
					$this->view->sub_anexo2=$sub_anexo;
					$sub_anexo="";
					break;
				case 'M':
					unset($arraycondominio);
					$arraycondominio[]=array("@codigo",trim($data->hd_codigo2));
					$arraycondominio[]=array("@anno",trim($anno));
					$arraycondominio[]=array("@cod_pred",trim($data->hd_idanexo2));
					$arraycondominio[]=array("@anexo",trim($data->hd_anexo2));
					$arraycondominio[]=array("@sub_anexo",trim($data->hd_subanexo2));
					$arraycondominio[]=array("@id_motivo_descargo",'--');				
					$arraycondominio[]=array("@porcen_propiedad",trim($data->txtPorcenPropiedad));
					$arraycondominio[]=array("@fech_transparencia",$txtFecTrans);
					$arraycondominio[]=array("@id_notaria",$cb_notaria);
					$arraycondominio[]=array("@codigo_adquiriente",$codigo);
					$arraycondominio[]=array("@direccion_predio",'');
					$arraycondominio[]=array("@operador",$login->user);
					$arraycondominio[]=array("@estacion2",php_uname('n'));
					$arraycondominio[]=array("@DJ_PREDIAL",$rj_predial);
					
					$rowcond=$cn->ejec_store_procedura_sql("[Rentas].Condominio",$arraycondominio);
					break;
				case 'E':
					$this->view->sub_anexo2=$sub_anexo;
					//$sub_anexo="";
					break;
				
			}

				 $nombreprocedure="Rentas.DatosIniciales_pu";
				 $arraydatos[0]=array("@msquery",1);
				 $arraydatos[1]=array("@codigo",$codigo);
				 $arraydatos[2]=array("@cod_pred",$cod_pred);
				 $arraydatos[3]=array("@ano_s",$anno);
				 $arraydatos[4]=array("@anexo",$anexo);
				 $arraydatos[5]=array("@sub_anexo",$sub_anexo);
				 $cn=new Model_DbDatos_Datos();
				 $datos=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
				 $cod_pred=trim($datos[0][0]);
				 $anexo=trim($datos[0][1]);
				 $sub_anexo=trim($datos[0][2]);
			
			 /*********DATOS PISOS DE PU**************/
									
			if($cmbEstadoConst<>'01'){
			
			if(!empty($data->Const)){
			$dataConst = $data->Const;			
			foreach($dataConst as $dConst){
			//var_dump($dConst);
			unset($parametros);
				
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@anno',$anno);
			$parametros[] = array('@cod_pred',$cod_pred);
			$parametros[] = array('@anexo',$anexo);
			$parametros[] = array('@sub_anexo',$sub_anexo);
			$parametros[] = array('@item_piso',$dConst->idpisos);
			$parametros[] = array('@niv_piso',$dConst->nropiso);
			$parametros[] = array('@tipo_nivel',$dConst->cidindi);
			$parametros[] = array('@mes_cons',trim($dConst->mescons)=="" ? "01" : trim($dConst->mescons) );
			$parametros[] = array('@ano_cons',trim($dConst->aniocons));
			$parametros[] = array('@anno_antig',trim($dConst->aniocons));
			$parametros[] = array('@id_clafica',$dConst->iddepcl);
			$parametros[] = array('@id_materia',$dConst->iddepma);
			$parametros[] = array('@id_estados',$dConst->iddepco);
			$parametros[] = array('@cate_muros',$dConst->esmuros);
			$parametros[] = array('@cate_techo',$dConst->estecho);
			$parametros[] = array('@cate_pisos',$dConst->acapiso);
			$parametros[] = array('@cate_puert',$dConst->acapuer);
			$parametros[] = array('@cate_reves',$dConst->acareve);
			$parametros[] = array('@cate_banno',$dConst->acabanio);
			$parametros[] = array('@cate_insel',$dConst->instele);
			$parametros[] = array('@area_const',str_replace(',','',$dConst->arconde));
			$parametros[] = array('@area_comun',$dConst->uconant);
			$parametros[] = array('@umedida',$dConst->umedida);
			$parametros[] = array('@nestado',1);
			$parametros[] = array('@operador',$login->user);
			$parametros[] = array('@estacion',php_uname('n'));
			$parametros[] = array('@referencia',$dConst->referencia);
			$parametros[] = array('@dj_predial',$rj_predial);
			$nombreprocedure1="[Rentas].[ingreso_piso]";
			$datos=$cn->ejec_store_procedura_sql($nombreprocedure1,$parametros);		
			}
		}
		}
			// /*********DATOS INSTALACIONES**************/
			if(!empty($data->Instal)){
			
			$oldInstal = explode('|',$data->oldInsItems);			
			$dataInstal = $data->Instal;
			
			foreach($dataInstal as $dInstal){
				$idcminsta = $dInstal->idinsta;
				$idcmindiv = $idsigma;
				
				if(strlen($idcminsta)>0)
					$busc = 2;
				else
					$busc = 1;
					
				unset($parametros);
				$parametros[] = array('@codigo',$codigo);
				$parametros[] = array('@anno',$anno);
				$parametros[] = array('@cod_pred',$cod_pred);
				$parametros[] = array('@anexo',$anexo);
				$parametros[] = array('@sub_anexo',$sub_anexo);
				$parametros[] = array('@busc', trim($dInstal->idinsta)=="" ? 1 : 2);
				$parametros[] = array('@id_tbl',trim($dInstal->idinsta));
				$parametros[] = array('@id_instala',$dInstal->cidinst);
				$parametros[] = array('@mes_cons',$dInstal->mescons);
				$parametros[] = array('@ano_cons',$dInstal->aniocons);
				$parametros[] = array('@id_clafica',$dInstal->iddepcl);
				$parametros[] = array('@id_materia',$dInstal->iddepma);
				$parametros[] = array('@id_estados',$dInstal->iddepco);
				$parametros[] = array('@largo',$dInstal->dmlargo);
				$parametros[] = array('@ancho',$dInstal->dmancho);
				$parametros[] = array('@alto',$dInstal->dmaltos);
				$parametros[] = array('@cantidad',str_replace(',','',$dInstal->protota));
				$parametros[] = array('@valor_instalacion',str_replace(',','',$dInstal->vdescri));
				$parametros[] = array('@insta_afect',1);
				$parametros[] = array('@uni_medida',$dInstal->vunimed);
				$parametros[] = array('@referencia',$dInstal->referenciainst);
				$parametros[] = array('@dj_predial',$rj_predial);
				
				
				$dataFicIndIns = $cn->ejec_store_procedura_sql('Rentas.sp_MInstalacion', $parametros, true);
				}
			}
			
			// /*********DATOS DOCUMENTOS**************/
			if(!empty($data->Doc)){
				$oldDoc = explode('|',$data->oldDocItems);			
				$dataDoc = $data->Doc;
				 
				foreach($dataDoc as $dDoc){
				$iddoc = trim($dDoc->iddoc);
				$idreg = trim($dDoc->idreg);
				$docnombre = trim($dDoc->docnombre);
				$docdetalle = trim($dDoc->docdetalle); 
				
				if(strlen($docdetalle)>0){
					unset($parametros);
					//$nombrestore="[Rentas].[sp_Docu]";
					$parametros[]=array("@msquery",$idreg==0 ? 1 : 2);
					$parametros[]=array("@id",$idreg);
					$parametros[]=array('@codigo',$codigo);
					$parametros[]=array('@anno',$anno);
					$parametros[]=array('@cod_pred',$cod_pred);
					$parametros[]=array('@anexo',$anexo);
					$parametros[]=array('@sub_anexo',$sub_anexo);
					$parametros[]=array("@docu_sustento_id",$iddoc);
					$parametros[]=array("@detalle",$docdetalle);
					$parametros[] = array('@dj_predial',$rj_predial);
					
					$dataFicIndIns = $cn->ejec_store_procedura_sql('[Rentas].[sp_Docu]', $parametros, true);	
				}
				else{
					if($idreg>0){
						unset($parametros);
						$parametros[]=array("@msquery",3);
						$parametros[] = array('@id',$idreg);
						$parametros[] = array('@dj_predial',$rj_predial);
						$dataFicIndIns = $cn->ejec_store_procedura_sql('[Rentas].[sp_Docu]', $parametros, true);	
					}
				}
				}
			}
			
			 $nombreprocedure3="Rentas.ingre_predio";
			 $arraydatos3[] = array('@codigo',$codigo);
			 $arraydatos3[] = array('@anno',$anno);
			 $arraydatos3[] = array('@cod_pred',$cod_pred);
			 $arraydatos3[] = array('@tipo_pred',1);
			 $arraydatos3[] = array('@anexo',$anexo);
			 $arraydatos3[] = array('@sub_anexo',$sub_anexo);
			 $arraydatos3[] = array('@id_urba','0001');
			 $arraydatos3[] = array('@id_via',$txtCvia);
			 $arraydatos3[] = array('@num_manz',$txtMza);
			 $arraydatos3[] = array('@num_call',$txtNro);
			 $arraydatos3[] = array('@num_depa',$txtDpto);
			 $arraydatos3[] = array('@num_lote',$txtLte);
			 $arraydatos3[] = array('@sub_lote',$txtSubLte);
			 $arraydatos3[] = array('@referenc',$txtDir);
			 $arraydatos3[] = array('@id_condi',$cmbCondicion);
			 $arraydatos3[] = array('@id_uso',$cmbUso);
			 $arraydatos3[] = array('@id_estado',$cmbEstadoConst);
			 $arraydatos3[] = array('@id_tipo',$cmbTipPredio);
			 $arraydatos3[] = array('@suministro_luz',$txtLuz);
			 $arraydatos3[] = array('@suministro_agua',$txtAgua);
			 $arraydatos3[] = array('@lice_cons',$chbLicencia);
			 $arraydatos3[] = array('@conf_obra',$chbConformidad);
			 $arraydatos3[] = array('@decl_fabr',$chbDeclaracionFab);
			 $arraydatos3[] = array('@num_pisos',0);
			 $arraydatos3[] = array('@num_condo',$txtNroCond);
			 $arraydatos3[] = array('@fec_compr',$txtFecAdqui);
			 $arraydatos3[] = array('@fec_venta',$txtFecTrans);
			 $arraydatos3[] = array('@venta_pre',$chbVendido);
			 $arraydatos3[] = array('@area_terr',$txtAreaTerreno);
			 $arraydatos3[] = array('@area_comun',$txtAreaComun);
			 $arraydatos3[] = array('@porcen_pro',$txtPorcenPropiedad);
			 $arraydatos3[] = array('@arancel',0);
			 $arraydatos3[] = array('@frontis',$txtFrontis);
			 $arraydatos3[] = array('@afec_pred',$chAfectoPred);
			 $arraydatos3[] = array('@observacion',$txtObs);
			 $arraydatos3[] = array('@nestado',1);
			 $arraydatos3[] = array('@afec_arbitrios',$txtArbAfecto=="" ? 0 : $txtArbAfecto);
			 $arraydatos3[] = array('@arbitrios_desde',$cbAfectMesDesde);
			 $arraydatos3[] = array('@arbitrios_hasta',$cbAfectMesHasta);
			 $arraydatos3[] = array('@afec_serenazgo',$txtArbAfecto=="" ? 0 : $txtArbAfecto);
			 $arraydatos3[] = array('@serenazgo_desde',$cbAfectMesDesde);
			 $arraydatos3[] = array('@serenazgo_hasta',$cbAfectMesHasta);
			 $arraydatos3[] = array('@arb_observacion',$txtArbObs);
			 $arraydatos3[] = array('@ubi_par',$txtUbiPar);
			 $arraydatos3[] = array('@area_ocupada',$txtAreaUso);
			 $arraydatos3[] = array('@recalculo_ip',$chCalPredial);
			 $arraydatos3[] = array('@recalculo_arb',$chCalArbitrio);
			 
			 $arraydatos3[] = array('@cond_espe_documento',$txtDocEspecial);
			 $arraydatos3[] = array('@cond_espe_nrodocumento',$txtNroDocEspecial);
			 $arraydatos3[] = array('@cond_espe_fecha',$txtFechDocEspecial);
			 $arraydatos3[] = array('@cond_espe_fecha_inicio',$txtFechDocEspecialInicial);
			 $arraydatos3[] = array('@cond_espe_fecha_fin',$txtFechDocEspecialFinal);
			 $arraydatos3[] = array('@situacion_predio_id',$cmbSituacionPredio);
			 $arraydatos3[] = array('@situacion_documento',$txtSituacionDocumento);
			 $arraydatos3[] = array('@situacion_nrodocumento',$txtSituacionNroDoc);
			 $arraydatos3[] = array('@situacion_fecha',$txtSituacionFechDoc);
			 
			 $arraydatos3[] = array('@fecha_fiscalizacion',$txtFechaFisca);
			 $arraydatos3[] = array('@nro_fiscalizacion',$txtNroFisca);
			 
			 $arraydatos3[] = array('@tipo_edificio_id',$cmbTipoEdificio);
			 $arraydatos3[] = array('@nombre_edificio',$txtNomEdificio);
			 $arraydatos3[] = array('@piso',$txtPiso);
			 $arraydatos3[] = array('@numero_interno',$txtNumeroInterno);
			 $arraydatos3[] = array('@letra_interno',$txtLetraInterno);
			 $arraydatos3[] = array('@tipo_ingreso_id',$cmbTipoIngreso);
			 $arraydatos3[] = array('@nombre_ingreso',$txtNomIngreso);
			 $arraydatos3[] = array('@tipo_agrupamiento_id',$cmbTipoAgrupamiento);
			 $arraydatos3[] = array('@nombre_agrupamiento',$txtNomAgrupamiento);
			  
			 ////////////
			 
			 $arraydatos3[] = array('@numero2',$txtNro2);
			 $arraydatos3[] = array('@letra',$txtLetra);
			 $arraydatos3[] = array('@letra2',$txtLetra2);
			 $arraydatos3[] = array('@tipo_interior_id',$cmbInterior);
			 $arraydatos3[] = array('@fondo',$txtFondo);
			 $arraydatos3[] = array('@cond_espe_predio_id',$cmbCondicionpredio);
			
			 /////////
			 
			 /****GUARDA USOS*****/
			 $arraydatos3[] = array('@uso_limpieza',$cb_limpieza);
			 $arraydatos3[] = array('@uso_barrido',$cb_barrido);
			 $arraydatos3[] = array('@uso_parque',$cb_parque);
			 $arraydatos3[] = array('@uso_serenazgo',$cb_serenazgo);
			 
			 $arraydatos3[] = array('@operador',$login->user);
			 $arraydatos3[] = array('@estacion',php_uname('n'));
			 $arraydatos3[] = array('@id_tipoadqui',$cmbTipoAdqui);
			 $arraydatos3[] = array('@id_tipo_motivo',$cmbMotivoReg);
			 $arraydatos3[] = array('@motivo_declaracion_id',$cmbMotivoDec);
			 
			 $arraydatos3[] = array('@dj_predial',$rj_predial);
			 
			 $datos=$cn->ejec_store_procedura_sql($nombreprocedure3,$arraydatos3);
		}
    }

	public function prediosAction()
    {	

    	$path=	new Zend_Session_Namespace('path');
    	
    	$this->_helper->layout->disableLayout();
    	$this->view->title = "Mantenimiento de Predios";
		
    	$cod_pred = $this->_request->getParam('cod_pred','');
		$anexo = $this->_request->getParam('anexo','');
		$sub_anexo = $this->_request->getParam('sub_anexo','');
		$tipo_mov=$this->_request->getParam('tipo','');
			
    	$path->cod_pred=$cod_pred;
		$path->anexo=$anexo;
		$path->sub_anexo=$sub_anexo;
		$fn = new Libreria_Pintar();
    	$codigo=$path->codigo;
		$nombre=$path->nombres;
		$anno=$path->anno;
		$cod_pred=$path->cod_pred;
		$anexo=$path->anexo;
		$subanexo=$path->sub_anexo;
		$arancel=0;
		$vterreno=0;
		$vcontruccion=0;
		$vinstalaciones=0;
		$autoavaluo=0;
		$cod_via="";
		$nombre_via="";
		$direccion="";
		$nro="";
		$num_dpto="";
		$num_mza="";
		$num_calle="";
		$num_sublote="";
		$num_lote="";
		$area_terreno=0;
		$area_comun=0;
		$num_condo=0;
		$piso=0;
		$install=0;
		$sum_luz="";
		$sum_agua="";
		$lice_cons=0;
		$conf_obra=0;
		$decl_fabr=0;
		$chbVendido=0;
		$fec_compra="";
		$fec_venta="";
		$max_piso=1;
		$obs="";
		$frontis=0;
		$txtPorcenPropiedad=0;
		$nombre_cp="";
		$afec_pred=1;
		$num_piso=0;
		$area_uso=0;
		/*tu papi..inicio*/
		$nro2="";
		$letra="";
		$letra2="";
		$fondo=0;
		//$tipo_interior_id="";
		
		$txtDocEspecial="";
		$txtNroDocEspecial="";
		$txtFechDocEspecial="";
		$txtFechDocEspecialInicial="";
		$txtFechDocEspecialFinal="";
		
		$txtSituacionDocumento="";
		$txtSituacionNroDoc="";
		$txtSituacionFechDoc="";
		
		$fecha_fiscalizacion="";
		$nro_fiscalizacion='';
		
		$tipo_edificio_id="";
		$nombre_edificio="";
		$piso="";
		$numero_interno="";
		$letra_interno="";
		$tipo_ingreso_id="";
		$nombre_ingreso="";
		$tipo_agrupamiento_id="";
		$nombre_agrupamiento="";
		
		/*Fin*/
		$sector_detalle="";
		$data0='';
		
		$cn=new Model_DbDatos_Datos();
		
		/**********TEXT FORMADO NUMERO**********/
		$mask[] = array("txtArancel");
		$mask[] = array("txtTerreno");
		$mask[] = array("txtConstruccion");
		$mask[] = array("txtInstalaciones");
		$mask[] = array("txtAutovaluo");
		$mask[] = array("txtAreaTerreno");
		$mask[] = array("txtAreaComun");
		$mask[] = array("txtPorcenPropiedad");
		$mask[] = array("txtAreaTerreno");
		$mask[] = array("txtFrontis");
		$mask[] = array('areacons');
		$mask[] = array('areacom');
		$mask[] = array('alto');
		$mask[] = array('largo');
		$mask[] = array('ancho');
		$mask[] = array('cantidad');
		$mask[] = array('val_instalac');
		$mask[] = array('txtAreaUso');

		/***********DATOS DE PREDIO**************/
		//if(!empty($cod_pred)){
		
		
			$nombreprocedure0="Rentas.sp_VistaPred";
			$arraydatos0[]=array("@msquery",1);
			$arraydatos0[]=array("@codigo",$codigo);
			$arraydatos0[]=array("@anno",$anno);
			$arraydatos0[]=array("@cod_pred",$cod_pred);
			$arraydatos0[]=array("@anexo",$anexo);
			$arraydatos0[]=array("@sub_anexo",$subanexo);
			$data0=$cn->ejec_store_procedura_sql("Rentas.sp_VistaPred",$arraydatos0);
		//}
				
		if(is_array($data0) and count($data0) > 0){
			$anexo=$anexo;
			$subanexo=$sub_anexo;
			$arancel=$data0[0][33];
			$vterreno=$data0[0][34];
			$vcontruccion=$data0[0][35];
			$vinstalaciones=$data0[0][36];
			$autoavaluo=$data0[0][37];
			$cod_via=$data0[0][7];
			$nombre_via=$data0[0][54];
			$nombre_cp=$data0[0][55];
			$direccion=$data0[0][13];
			$nro=$data0[0][11];
			$num_dpto=$data0[0][12];
			$num_mza=$data0[0][8];
			$num_lote=$data0[0][9];
			$num_sublote=$data0[0][10];
			$num_calle=$data0[0][11];
			$frontis=$data0[0][29];	
			$area_terreno=$data0[0][31];
			$area_comun=$data0[0][32];
			$txtPorcenPropiedad=$data0[0][38];
			$num_condo=$data0[0][24];
			$sum_luz=$data0[0][18];
			$sum_agua=$data0[0][19];
			$lice_cons=$data0[0][20];
			$conf_obra=$data0[0][21];
			$decl_fabr=$data0[0][22];
			$fec_compra=$data0[0][52];
			$fec_venta=$data0[0][53];
			$obs=$data0[0][47];
			$afec_arbitrios=$data0[0][41];
			$afec_arb_desde=$data0[0][42];
			$afec_arb_hasta=$data0[0][43];
			$arbitrios_observa=$data0[0][56];
			$afec_pred=$data0[0][40];
			$num_piso=$data0[0][23];
			$chbVendido=$data0[0][27];
			$area_uso=$data0[0][59];
			
			
			/*tu papi..inicio*/
			$nro2=$data0[0][60];
			$letra=$data0[0][61];
			$letra2=$data0[0][62];
			$fondo=$data0[0][65];
			/*Fin*/
			
			$txtDocEspecial=$data0[0][69];
			$txtNroDocEspecial=$data0[0][70];
			$txtFechDocEspecial=$data0[0][71];
			$txtFechDocEspecialInicial=$data0[0][72];
			$txtFechDocEspecialFinal=$data0[0][73];
			
			$txtSituacionDocumento=$data0[0][74];
			$txtSituacionNroDoc=$data0[0][75];
			$txtSituacionFechDoc=$data0[0][76];
			
			$fecha_fiscalizacion=$data0[0][77];
			$nro_fiscalizacion=$data0[0][78];
			
			$tipo_edificio_id=$data0[0][79];
			$nombre_edificio=$data0[0][80];
			$piso=$data0[0][81];
			$numero_interno=$data0[0][82];
			$letra_interno=$data0[0][83];
			$tipo_ingreso_id=$data0[0][84];
			$nombre_ingreso=$data0[0][85];
			$tipo_agrupamiento_id=$data0[0][86];
			$nombre_agrupamiento=$data0[0][87];
									
			$sector_detalle=$data0[0][64];
			
			//USOS PREDIO
			$nombrestoreusos="[Rentas].[sp_UsoArbitrios]";
			$arrayusos[]=array("@msquery",3);
			$arrayusos[]=array("@codigo",$codigo);
			$arrayusos[]=array("@anno",$anno);
			$arrayusos[]=array("@cod_pred",$cod_pred);
			$arrayusos[]=array("@anexo",$anexo);
			$arrayusos[]=array("@sub_anexo",$subanexo);
			$data_usos=$cn->ejec_store_procedura_sql($nombrestoreusos,$arrayusos);
			
		}
		switch ($tipo_mov) {
			case 'N':
				$cod_pred="";
				$anexo="";
				$subanexo="";
				$this->view->subanexo2="";
				break;
			case 'I':
				$this->view->subanexo2=$sub_anexo;
				$subanexo="";
				break;
			case 'E':
				$this->view->subanexo2="";				
				break;
			case 'M':
				$cod_pred="";
				$anexo="";
				$subanexo="";
				$this->view->subanexo2="";
				break;
		}
		
		/*hecho por tu papi..Inicio */
		$ar = new Libreria_ArraysFunctions();
		
		$nombrestore="rentas.sp_predio";
		$arraydatos[0]=array("@msquery",7);
		$arraydatos[1]=array("@tipo_predi",1);
		$rows1=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		$cb_Interior='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($rows1);$i++){
			if (is_array($data0) && $rows1[$i][0]==trim($data0[0][63])){
				$cb_Interior.='<option value="'.$rows1[$i][0].'" selected>'.$rows1[$i][1].'</option>';
			}
			else{
				$cb_Interior.='<option value="'.$rows1[$i][0].'">'.$rows1[$i][1].'</option>';				
			}

		}
		
		$val[] = array('#cmbInterior',$cb_Interior,'html');	
		
		
		$nombrestore="rentas.sp_predio";
		$arraydatos[0]=array("@msquery",9);
		$arraydatos[1]=array("@tipo_predi",1);
		$rows1=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		$cb_CondiPredio='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($rows1);$i++){
			if (is_array($data0) && $rows1[$i][0]==trim($data0[0][67])){
				$cb_CondiPredio.='<option value="'.$rows1[$i][0].'" selected>'.$rows1[$i][1].'</option>';
			}
			else{
				$cb_CondiPredio.='<option value="'.$rows1[$i][0].'">'.$rows1[$i][1].'</option>';				
			}

		}
		
		$val[] = array('#cmbCondicionpredio',$cb_CondiPredio,'html');
		
	   
	    /*FIN*/
		
    	$nombrestore3="rentas.sp_predio";
	    $arraydatos3[0]=array("@msquery",3);
	    $arraydatos3[1]=array("@tipo_predi",1);
	    $dato3=$cn->ejec_store_procedura_sql($nombrestore3,$arraydatos3);
	    $cb_usopredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato3);$i++){
	    	if(is_array($data0) && $dato3[$i][0]==trim($data0[0][15])){
	    		$cb_usopredio.='<option value="'.$dato3[$i][0].'" selected>'.$dato3[$i][1].'</option>';
	    	}
			else{
				$cb_usopredio.='<option value="'.$dato3[$i][0].'">'.$dato3[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbUso',$cb_usopredio,'html');
		
		$nombrestore2="rentas.sp_predio";
    	$arraydatos2[0]=array("@msquery",2);
	    $arraydatos2[1]=array("@tipo_predi",1);
	    $dato2=$cn->ejec_store_procedura_sql($nombrestore2,$arraydatos2);
	    $cb_tipopredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato2);$i++){
	    	if (is_array($data0) && $dato2[$i][0]==trim($data0[0][17])){
	    		$cb_tipopredio.='<option value="'.$dato2[$i][0].'" selected>'.$dato2[$i][1].'</option>';
	    	}
			else{
				$cb_tipopredio.='<option value="'.$dato2[$i][0].'">'.$dato2[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbTipPredio',$cb_tipopredio,'html');
		
    	$nombrestore="rentas.sp_predio";
		$arraydatos[0]=array("@msquery",1);
		$arraydatos[1]=array("@tipo_predi",1);
		$dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
		$cb_estadopredio='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($dato);$i++){
			if (is_array($data0) && $dato[$i][0]==trim($data0[0][16])){
				$cb_estadopredio.='<option value="'.$dato[$i][0].'" selected>'.$dato[$i][1].'</option>';
			}
			else{
				$cb_estadopredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';				
			}

		}
		
		$val[] = array('#cmbEstadoConst',$cb_estadopredio,'html');
		
    	$nombrestore4="rentas.sp_predio";
	    $arraydatos4[0]=array("@msquery",4);
	    $arraydatos4[1]=array("@tipo_predi",1);
	    $dato4=$cn->ejec_store_procedura_sql($nombrestore4,$arraydatos4);
	    $cb_condipredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato4);$i++){
	    	if (is_array($data0) && $dato4[$i][0]==trim($data0[0][14])){
	    		$cb_condipredio.='<option value="'.$dato4[$i][0].'" selected>'.$dato4[$i][1].'</option>';
	    	}
			else{
				$cb_condipredio.='<option value="'.$dato4[$i][0].'">'.$dato4[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbCondicion',$cb_condipredio,'html');
		
		$arraydatos5[0]=array("@msquery",5);
		$arraydatos5[1]=array("@tipo_predi",1);
		$rowtipoadqui=$cn->ejec_store_procedura_sql("rentas.sp_predio",$arraydatos5);
		$cb_tipoadqui='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($rowtipoadqui);$i++){
	    	if (is_array($data0) && $rowtipoadqui[$i][0]==trim($data0[0][57])){
	    		$cb_tipoadqui.='<option value="'.$rowtipoadqui[$i][0].'" selected>'.$rowtipoadqui[$i][1].'</option>';
	    	}
			else{
				$cb_tipoadqui.='<option value="'.$rowtipoadqui[$i][0].'">'.$rowtipoadqui[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbTipoAdqui',$cb_tipoadqui,'html');
		
		$nombrestore6="rentas.sp_predio";
	    $arraydatos6[0]=array("@msquery",6);
	    $dato6=$cn->ejec_store_procedura_sql($nombrestore6,$arraydatos6);
	    $cb_reglosa='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato6);$i++){
	    	if(is_array($data0) && $dato6[$i][0]==trim($data0[0][58])){
	    		$cb_reglosa.='<option value="'.$dato6[$i][0].'" selected>'.$dato6[$i][1].'</option>';
	    	}
			else{
				$cb_reglosa.='<option value="'.$dato6[$i][0].'">'.$dato6[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbMotivoReg',$cb_reglosa,'html');
			
			
		$nombrestore7="rentas.sp_predio";
	    $arraydatos7[0]=array("@msquery",8);
	    $dato7=$cn->ejec_store_procedura_sql($nombrestore7,$arraydatos7);
	    $cb_motivodecla='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato7);$i++){
	    	if(is_array($data0) && $dato7[$i][0]==trim($data0[0][66])){
	    		$cb_motivodecla.='<option value="'.$dato7[$i][0].'" selected>'.$dato7[$i][1].'</option>';
	    	}
			else{
				$cb_motivodecla.='<option value="'.$dato7[$i][0].'">'.$dato7[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbMotivoDec',$cb_motivodecla,'html');	
			
		$nombrestore8="rentas.sp_predio";
	    $arraydatos8[0]=array("@msquery",10);
	    $dato8=$cn->ejec_store_procedura_sql($nombrestore8,$arraydatos8);
	    $cb_situacionpredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato8);$i++){
	    	if(is_array($data0) && $dato8[$i][0]==trim($data0[0][68])){
	    		$cb_situacionpredio.='<option value="'.$dato8[$i][0].'" selected>'.$dato8[$i][1].'</option>';
	    	}
			else{
				$cb_situacionpredio.='<option value="'.$dato8[$i][0].'">'.$dato8[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbSituacionPredio',$cb_situacionpredio,'html');	
			
		unset($arraydatos);
		$arraydatos[] = array('@buscar',8);	
		$combonotaria = $cn->ejec_store_procedura_sql('Rentas.sp_rentasmain',$arraydatos);
		$arNotaria = $ar->RegistrosCombo($combonotaria,0,1);
		$val[] = array('#cb_notaria',$fn->ContenidoCombo($arNotaria,'[Seleccione]',''),'html');
		
		$nombrestore9="rentas.sp_predio";
	    $arraydatos9[0]=array("@msquery",11);
	    $dato9=$cn->ejec_store_procedura_sql($nombrestore9,$arraydatos9);
	    $cb_tipoedificacion='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato9);$i++){
	    	if(is_array($data0) && $dato9[$i][0]==trim($tipo_edificio_id)){
	    		$cb_tipoedificacion.='<option value="'.$dato9[$i][0].'" selected>'.$dato9[$i][1].'</option>';
	    	}
			else{
				$cb_tipoedificacion.='<option value="'.$dato9[$i][0].'">'.$dato9[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbTipoEdificio',$cb_tipoedificacion,'html');	
			
		$nombrestore10="rentas.sp_predio";
	    $arraydatos10[0]=array("@msquery",12);
	    $dato10=$cn->ejec_store_procedura_sql($nombrestore10,$arraydatos10);
	    $cb_tipoingreso='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato10);$i++){
	    	if(is_array($data0) && $dato10[$i][0]==trim($tipo_ingreso_id)){
	    		$cb_tipoingreso.='<option value="'.$dato10[$i][0].'" selected>'.$dato10[$i][1].'</option>';
	    	}
			else{
				$cb_tipoingreso.='<option value="'.$dato10[$i][0].'">'.$dato10[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbTipoIngreso',$cb_tipoingreso,'html');	
			
		$nombrestore11="rentas.sp_predio";
	    $arraydatos11[0]=array("@msquery",13);
	    $dato11=$cn->ejec_store_procedura_sql($nombrestore11,$arraydatos11);
	    $cb_tipoagrupamiento='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato11);$i++){
	    	if(is_array($data0) && $dato11[$i][0]==trim($tipo_agrupamiento_id)){
	    		$cb_tipoagrupamiento.='<option value="'.$dato11[$i][0].'" selected>'.$dato11[$i][1].'</option>';
	    	}
			else{
				$cb_tipoagrupamiento.='<option value="'.$dato11[$i][0].'">'.$dato11[$i][1].'</option>';	
			}
		}
			$val[] = array('#cmbTipoAgrupamiento',$cb_tipoagrupamiento,'html');	
			
		
		/********USOS DE ARBITRIOS**************/
		$nombrestorearb="Rentas.sp_UsoArbitrios";
		$arraydatosarb1[]=array("@msquery",1);
		$arraydatosarb1[]=array("@tipo",'11.01');
		$arraydatosarb1[]=array("@anno",$anno);
		
		$arraydatosarb2[]=array("@msquery",1);
		$arraydatosarb2[]=array("@tipo",'11.02');
		$arraydatosarb2[]=array("@anno",$anno);
		
		$arraydatosarb3[]=array("@msquery",1);
		$arraydatosarb3[]=array("@tipo",'11.03');
		$arraydatosarb3[]=array("@anno",$anno);
		
		$arraydatosarb4[]=array("@msquery",1);
		$arraydatosarb4[]=array("@tipo",'11.04');
		$arraydatosarb4[]=array("@anno",$anno);
		
		$datoarb1=$cn->ejec_store_procedura_sql($nombrestorearb,$arraydatosarb1);
		$datoarb2=$cn->ejec_store_procedura_sql($nombrestorearb,$arraydatosarb2);
		$datoarb3=$cn->ejec_store_procedura_sql($nombrestorearb,$arraydatosarb3);
		$datoarb4=$cn->ejec_store_procedura_sql($nombrestorearb,$arraydatosarb4);

		$usoarb1='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($datoarb1);$i++){
			$seleccion=trim($datoarb1[$i][0])==trim($data_usos[0][0]) ? 'selected' : '';
			$usoarb1.='<option value="'.$datoarb1[$i][0].'" '.$seleccion.'>'.trim($datoarb1[$i][1]).'</option>';		
		}
		$val[] = array('#cb_limpieza',$usoarb1,'html');
		
		$usoarb2='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($datoarb2);$i++){
			$seleccion=trim($datoarb1[$i][0])==trim($data_usos[0][1]) ? 'selected' : '';
			$usoarb2.='<option value="'.$datoarb2[$i][0].'" '.$seleccion.'>'.trim($datoarb2[$i][1]).'</option>';		
		}
		$val[] = array('#cb_barrido',$usoarb2,'html');
		
		$usoarb3='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($datoarb3);$i++){
			$seleccion=trim($datoarb1[$i][0])==trim($data_usos[0][2]) ? 'selected' : '';
			$usoarb3.='<option value="'.$datoarb3[$i][0].'" '.$seleccion.'>'.trim($datoarb3[$i][1]).'</option>';		
		}
		$val[] = array('#cb_parque',$usoarb3,'html');
			
		$usoarb4='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($datoarb4);$i++){
			$seleccion=trim($datoarb1[$i][0])==trim($data_usos[0][3]) ? 'selected' : '';
			$usoarb4.='<option value="'.$datoarb4[$i][0].'" '.$seleccion.'>'.trim($datoarb4[$i][1]).'</option>';		
		}

		$val[] = array('#cb_serenazgo',$usoarb4,'html');
		
		$evt[] = array('#btnAddConst',"click","eventConst('A');");
		$evt[] = array('#btnEditConst',"click","eventConst('E');");
		$evt[] = array('#btnCancelConst',"click","eventConst('C');");
		$evt[] = array('#btnSaveConst',"click","eventConst('S');");
		$evt[] = array('#btnDelConst',"click","eventConst('D');");
		
		$evt[] = array('#btnAddInstal',"click","eventInstal('A');");
		$evt[] = array('#btnEditInstal',"click","eventInstal('E');");
		$evt[] = array('#btnCancelInstal',"click","eventInstal('C');");
		$evt[] = array('#btnSaveInstal',"click","eventInstal('S');");
		$evt[] = array('#btnDelInstal',"click","eventInstal('D');");
		
		$evt[] = array('#btnEditDoc',"click","eventDoc('E');");
		$evt[] = array('#btnCancelDoc',"click","eventDoc('C');");
		$evt[] = array('#btnSaveDoc',"click","eventDoc('S');");
		//$evt[] = array('#btnDelDoc',"click","eventDoc('D');");
		
		/************CARACTERISTICAS PISOS**************/
		
		$nombrestorepiso="Rentas.caracteristicas_piso";
		$arraydatospisostipo[]=array("@msquery",1);
		$arraydatospisosmateri[]=array("@msquery",2);
		$arraydatospisosestado[]=array("@msquery",3);
		$arraydatospisosclasifi[]=array("@msquery",4);
	    $arrayunidadmedida[]=array("@msquery",5);
		
		$tipo_piso=$cn->ejec_store_procedura_sql($nombrestorepiso,$arraydatospisostipo);
		$materi_piso=$cn->ejec_store_procedura_sql($nombrestorepiso,$arraydatospisosmateri);
		$estado_piso=$cn->ejec_store_procedura_sql($nombrestorepiso,$arraydatospisosestado);
		$clasifi_piso=$cn->ejec_store_procedura_sql($nombrestorepiso,$arraydatospisosclasifi);
		$rowunidad_medida=$cn->ejec_store_procedura_sql($nombrestorepiso,$arrayunidadmedida);
		
		$nivel_p='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($tipo_piso);$i++){
			$nivel_p.='<option value="'.$tipo_piso[$i][0].'">'.trim($tipo_piso[$i][1]).'</option>';		
		}
		$val[] = array('#cb_tiponivel',$nivel_p,'html');
		
		$materia_p='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($materi_piso);$i++){
			$materia_p.='<option value="'.$materi_piso[$i][0].'" >'.trim($materi_piso[$i][1]).'</option>';		
		}
		//$val[] = array('#cb_clasifica',$materia_p,'html');
		//$val[] = array('#id_clafica_i',$materia_p,'html');
		$val[] = array('#cb_material',$materia_p,'html');
		$val[] = array('#id_materia_i',$materia_p,'html');
		
		$estado_p='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($estado_piso);$i++){
			$estado_p.='<option value="'.$estado_piso[$i][0].'">'.trim($estado_piso[$i][1]).'</option>';		
		}
		//$val[] = array('#cb_material',$estado_p,'html');
		//$val[] = array('#id_materia_i',$estado_p,'html');
		$val[] = array('#cb_estado',$estado_p,'html');
		$val[] = array('#id_estados_i',$estado_p,'html');
			
		$clasif_p='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($clasifi_piso);$i++){
			$clasif_p.='<option value="'.$clasifi_piso[$i][0].'">'.trim($clasifi_piso[$i][1]).'</option>';		
		}
		
	    $cb_unidadmedida='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($rowunidad_medida);$i++){
				$cb_unidadmedida.='<option value="'.$rowunidad_medida[$i][0].'">'.$rowunidad_medida[$i][1].'</option>';	
		}
			
		//$val[] = array('#cb_estado',$clasif_p,'html');
		//$val[] = array('#id_estados_i',$clasif_p,'html');
		$val[] = array('#cb_clasifica',$clasif_p,'html');
		$val[] = array('#id_clafica_i',$clasif_p,'html');
		$val[] = array('#cb_unidad_medida',$cb_unidadmedida,'html');	
				
		/***dinstalaciones***/
		$nombrestoredinst="Rentas.sp_MInstalacion";
		$arraydatosinstalaciones[]=array("@busc",5);
		$dinstalacion=$cn->ejec_store_procedura_sql($nombrestoredinst,$arraydatosinstalaciones);
		
		$instalacion_d='<option value="">[Seleccione]</option>';
		for($i=0;$i<count($dinstalacion);$i++){
			$instalacion_d.='<option value="'.trim($dinstalacion[$i][0]).'">'.trim($dinstalacion[$i][1]).'</option>';		
		}
		$val[] = array('#detalle_inst',$instalacion_d,'html');
		
		$this->view->codcontrib=$codigo;
		$this->view->contrib=$nombre;
		$this->view->anno=$anno;
		$this->view->cod_pred=$cod_pred;
		$this->view->anexo=$anexo;
		$this->view->subanexo=$subanexo;
		$this->view->tipo_mov=$tipo_mov;
		$this->view->arancel=$arancel;
		$this->view->nombre_via=$nombre_via;
		$this->view->nombre_cp=$nombre_cp;
		$this->view->vterreno=$vterreno;
		$this->view->vconstruccion=$vcontruccion;
		$this->view->vinstalaciones=$vinstalaciones;
		$this->view->autoavaluo=$autoavaluo;
		$this->view->cod_via=$cod_via;
		$this->view->direccion=$direccion;
		$this->view->nro=$nro;
		$this->view->num_dpto=$num_dpto;
		$this->view->num_mza=$num_mza;
		$this->view->num_calle=$num_calle;
		$this->view->num_lote=$num_lote;
		$this->view->num_sublote=$num_sublote;
		$this->view->num_piso=$num_piso;
		
		
		$this->view->frontis=$frontis;
		$this->view->area_terreno=$area_terreno;
		$this->view->area_comun=$area_comun;
		$this->view->porcen_propiedad=$txtPorcenPropiedad;
		$this->view->num_condo=$num_condo;
		$this->view->sum_luz=$sum_luz;
		$this->view->sum_agua=$sum_agua;
		$this->view->fec_compra=$fec_compra;
		$this->view->fec_venta=$fec_venta;
		$this->view->obs=$obs;
		$this->view->anno=$anno;
		$this->view->codigo=$codigo;
		$this->view->area_uso=$area_uso;
		///////////////////////////////////
		$this->view->nro2=$nro2;
		$this->view->letra=$letra;
		$this->view->letra2=$letra2;
		$this->view->fondo=$fondo;
		///////////////////////
		$this->view->txtDocEspecial=$txtDocEspecial;
		$this->view->txtNroDocEspecial=$txtNroDocEspecial;
		$this->view->txtFechDocEspecial=$txtFechDocEspecial;
		$this->view->txtFechDocEspecialInicial=$txtFechDocEspecialInicial;
		$this->view->txtFechDocEspecialFinal=$txtFechDocEspecialFinal;
			
		$this->view->txtSituacionDocumento=$txtSituacionDocumento;
		$this->view->txtSituacionNroDoc=$txtSituacionNroDoc;
		$this->view->txtSituacionFechDoc=$txtSituacionFechDoc;
		
		$this->view->$fecha_fiscalizacion;
		$this->view->$nro_fiscalizacion;
		
		$this->view->nombre_edificio=$nombre_edificio;
		$this->view->piso=$piso;
		$this->view->numero_interno=$numero_interno;
		$this->view->letra_interno=$letra_interno;
		$this->view->nombre_ingreso=$nombre_ingreso;
		$this->view->nombre_agrupamiento=$nombre_agrupamiento;
		
		//////////////////////////
		$this->view->sector_detalle=$sector_detalle;
		if($lice_cons==1){
			$cad[] = array('#chbLicencia',"checked", 'true');
		}
		else{
			$cad[] = array('#chbLicencia',"checked", 'false');
		}
		
    	if($conf_obra==1){
			$cad[] = array('#chbConformidad',"checked", 'true');
		}
		else{
			$cad[] = array('#chbConformidad',"checked", 'false');
		}
		
		if($chbVendido==1){
			$cad[] = array('#chbVendido',"checked", 'true');
		}
		else{
			$cad[] = array('#chbVendido',"checked", 'false');
		}
		
   		if($decl_fabr==1){
			$cad[] = array('#chbDeclaracionFab',"checked", 'true');
		}
		else{
			$cad[] = array('#chbDeclaracionFab',"checked", 'false');
		}
		
		if($afec_arbitrios==1){
			$cad[] = array('#txtArbAfecto',"checked", 'true');
		}
		
		if($afec_pred==1){
			$cad[] = array('#chAfectoPred',"checked", 'true');
		}
		
		//Mes afectacion Arbitrios
		$cbarb_desde_mes="<option value=''>[Seleccione]</option>";
		for($i=1;$i<13;$i++){
			$valor=$fn->add_ceros($i,2);
			$seleccion=trim($valor)==trim($afec_arb_desde) ? 'selected' : '';
			$cbarb_desde_mes.="<option value='".$valor."' ".$seleccion.">".$valor."</option>";
		}
		$val[] = array('#cbAfectMesDesde',$cbarb_desde_mes,'html');
		
		//Mes afectacion Arbitrios
		$cbarb_hasta_mes="<option value=''>[Seleccione]</option>";
		for($i=1;$i<13;$i++){
			$valor=$fn->add_ceros($i,2);
			$seleccion=trim($valor)==trim($afec_arb_hasta) ? 'selected' : '';
			$cbarb_hasta_mes.="<option value='".$fn->add_ceros($i,2)."' ".$seleccion.">".$fn->add_ceros($i,2)."</option>";
		}
		$val[] = array('#cbAfectMesHasta',$cbarb_hasta_mes,'html');
		
		$val[] = array('#detallePisos',$piso,'append');
		$val[] = array('#cDetPisos',$max_piso,'val');
		
		//Eventos Predios
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");
		$evt[] = array('#cmbUso',"change","ShowTabPisos(this.value);");
		$evt[] = array('#detalle_inst',"change","agrega_valor(this.value);");
		
		//$evt[] = array('#btnBusVia',"click","showPopup('Mantbusqueda/busqueda?pred='+1,'#popbuscar','700','280','Mantenimiento de Predios');");
		$evt[] = array('#btnBusVia',"click","mostrarVias();");
		$evt[] = array('#btnBuspred',"click","buscarPredio();");
		
		//Eventos PU
		$evt[] = array('#txtFecAdqui',"datepicker","");
		$evt[] = array('#txtFecTrans',"datepicker","");
						
		$evt[] = array('#btnSavePu',"click","goToFormulario('frmpredios');;");	
		$evt[] = array('#btnClosePu',"click","closePopup('#popuppred');");	

		//$evt[] = array('#txtNro',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#anoc',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#txtNroCond',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#mes',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#ano_cons',"keypress","return validaTeclas(event,'number');");
		$evt[] = array('#mes_cons',"keypress","return validaTeclas(event,'number');");
		
		//$evt[] = array('#mes',"blur","return rellenarceros(this.val, 2)");
		
		$evt[] = array('#btnBusVia',"button","");
		
		//Eventos Pisos
		//$evt[] = array('#btnAddDetPisos',"click","addRowPisos();");
		
		$evt[] = array('#btnAddConst',"button","");
		$evt[] = array('#btnEditConst',"button","");
		$evt[] = array('#btnDelConst',"button","");
		$evt[] = array('#btnCancelConst',"button","");
		$evt[] = array('#btnSaveConst',"button","");
		
		$evt[] = array('#btnEdtPu',"click","RemoveDisabled();");
					
		//Eventos Instalaciones	
		$this->view->arbitrios_observa=$arbitrios_observa;
		
		//Eventos Doc. Anexos
	
		
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
		$fn->PintarValor($val);
		$fn->AtributoComponente($cad);
   // }
	}
 
 	
    
  public function valorpisoAction(){
  	$this->_helper->layout->disableLayout();
  	$this->_helper->viewRenderer->setNoRender ();
	$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
	if ($this->_request->isPost())
		{
			$nivel=trim($this->_request->getPost('nivel'));
			$c=trim($this->_request->getPost('c'));
			$m=trim($this->_request->getPost('m'));
			$e=trim($this->_request->getPost('e'));
			$mes=trim($this->_request->getPost('mes'));
			$anoc=trim($this->_request->getPost('anoc'));
			$muro=trim($this->_request->getPost('muro'));
			$techo=trim($this->_request->getPost('techo'));
			$piso=trim($this->_request->getPost('piso'));
			$puerta=trim($this->_request->getPost('puerta'));
			$revestim=trim($this->_request->getPost('revestim'));
			$bano=trim($this->_request->getPost('bano'));
			$inst=trim($this->_request->getPost('inst'));
			$areacons=trim($this->_request->getPost('areacons'));
			$areacom=trim($this->_request->getPost('areacom'))=='' ? 0 : trim($this->_request->getPost('areacom'));
			$anno=trim($this->_request->getPost('anno'));	
			//$referencia=trim($this->_request->getPost('referencia'));
			if(trim($this->_request->getPost('areacom'))==''){
				echo "*";
			}
			else{
				$nombreprocedure="rentas.calculo_piso";
				$arraydatos[0]=array("@msquery",1);
				$arraydatos[1]=array("@nivel",$nivel);
				$arraydatos[2]=array("@id_depcla",$c);
				$arraydatos[3]=array("@id_depmat",$m);
				$arraydatos[4]=array("@id_depcon",$e);
				$arraydatos[5]=array("@muros",$muro);
				$arraydatos[6]=array("@techos",$techo);
				$arraydatos[7]=array("@pisos",$piso);
				$arraydatos[8]=array("@puertas",$puerta);
				$arraydatos[9]=array("@revestim",$revestim);
				$arraydatos[10]=array("@banos",$bano);
				$arraydatos[11]=array("@inst_elect",$inst);
				$arraydatos[12]=array("@_area_const",$areacons);
				$arraydatos[13]=array("@ano_c",$anoc);
				$arraydatos[14]=array("@estado",1);
				$arraydatos[15]=array("@anno",$anno);			
				$arraydatos[16]=array("@area_comun",$areacom);			
				
				
				$cn=new Model_DbDatos_Datos();
				$datos=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
					
					echo $datos[0][0]."/".$datos[0][1]."/".$datos[0][2]."/".$datos[0][3]."/".$datos[0][4];
				}
		}
  }
  public function eliminapisoAction(){
	  	$this->_helper->layout->disableLayout();
  		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		 $login = new Zend_Session_Namespace('login');
		if ($this->_request->isPost())
		{
			$codigo=trim($this->_request->getPost('codigo'));
			$anno=trim($this->_request->getPost('anno'));
			$cod_pred=trim($this->_request->getPost('cod_pred'));
			$anexo=trim($this->_request->getPost('anexo'));
			$sub_anexo=trim($this->_request->getPost('sub_anexo'));
			$id_item=trim($this->_request->getPost('id_item'));

			
			if($id_item>0){
			$nombreprocedure="rentas.calculo_piso";
			$arraydatos[]=array("@msquery",3);
			$arraydatos[]=array("@codigo",$codigo);
			$arraydatos[]=array("@cod_pred",$cod_pred);
			$arraydatos[]=array("@anexo",$anexo);
			$arraydatos[]=array("@sub_anexo",$sub_anexo);
			$arraydatos[]=array("@anno",$anno);
			$arraydatos[]=array("@item_piso",$id_item);
			$arraydatos[]=array("@operador",trim($login->user));
			$arraydatos[]=array("@estacion",php_uname('n'));
			$cn=new Model_DbDatos_Datos();
			$datos=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
			echo $datos[0][0];
			}
		}
  }
  public function gridpisosAction(){
				
		
				$codigo = $this->_request->getPost('codigo');
				$anno = $this->_request->getPost('anno');
				$cod_pred = $this->_request->getPost('cod_pred');
				$anexo = $this->_request->getPost('anexo');
				$sub_anexo = $this->_request->getPost('sub_anexo');
				
				$cn = new Model_DbDatos_Datos();
				$parametros[] = array('@msquery',2);
				$parametros[] = array('@codigo',$codigo);
				$parametros[] = array('@anno',$anno);
				$parametros[] = array('@cod_pred',$cod_pred);
				$parametros[] = array('@anexo',$anexo);
				$parametros[] = array('@sub_anexo',$sub_anexo);
				$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_VistaPred]', $parametros);
				
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idpisos'=>trim($row[4]),
							'cidindi'=>trim($row[30]),
							'nropiso'=>trim($row[5]),
							'iddepcl'=>trim($row[9]),
							'iddepma'=>trim($row[10]),
							'iddepco'=>trim($row[11]),
							'esmuros'=>trim($row[12]),
							'estecho'=>trim($row[13]),
							'acapiso'=>trim($row[14]),
							'acapuer'=>trim($row[15]),
							'acareve'=>trim($row[16]),
							'acabanio'=>trim($row[17]),
							'instele'=>trim($row[18]),
							'arconde'=>$row[24],
							'mescons'=>trim($row[6]),
							'aniocons'=>trim($row[7]),
							'uconant'=>$row[26],
							'tipon'=>trim($row[30]),
							'referencia'=>trim($row[34]),
							'umedida'=>trim($row[35])
						);
						$jsonData[] = $entry;
					}
				}
			$this->view->data = json_encode($jsonData);
  }
  
  public function gridinstalacionAction(){
			$codigo = $this->_request->getPost('codigo');
			$anno = $this->_request->getPost('anno');
			$cod_pred = $this->_request->getPost('cod_pred');
			$anexo = $this->_request->getPost('anexo');
			$sub_anexo = $this->_request->getPost('sub_anexo');
			
			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@busc',4);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@anno',$anno);
			$parametros[] = array('@cod_pred',$cod_pred);
			$parametros[] = array('@anexo',$anexo);
			$parametros[] = array('@sub_anexo',$sub_anexo);
			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_MInstalacion]', $parametros);
			
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'idinsta'=>trim($row[0]),//id_tbl
							'cidindi'=>trim($row[28]),//item_instalacion
							'cidinst'=>trim($row[4]),//id_instala
							'cidnomb'=>utf8_encode($row[26]),//instala-nombre
							'mescons'=>trim($row[5]),//mes_cons
							'aniocons'=>trim($row[6]),//ano_cons
							'iddepcl'=>trim($row[8]),//id_clafica
							'iddepma'=>trim($row[9]),//id_materia
							'iddepco'=>trim($row[10]),//id_estados
							'dmlargo'=>trim($row[12]),//largo
							'dmancho'=>trim($row[13]),//ancho
							'dmaltos'=>trim($row[11]),//alto
							'protota'=>trim($row[15]),//cantidad
							'vunimed'=>trim($row[27]),//uni_med
							'vdescri'=>$row[20],//val_instalac
							'referenciainst'=>$row[29]
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
  }
    public function eliminainstalacionAction(){
	  	$this->_helper->layout->disableLayout();
  		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->getHelper ( 'ajaxContext' )->initContext ();
		 $login = new Zend_Session_Namespace('login');
		if ($this->_request->isPost())
		{
			$codigo=trim($this->_request->getPost('codigo'));
			$anno=trim($this->_request->getPost('anno'));
			$cod_pred=trim($this->_request->getPost('cod_pred'));
			$anexo=trim($this->_request->getPost('anexo'));
			$sub_anexo=trim($this->_request->getPost('sub_anexo'));
			$id_item=trim($this->_request->getPost('id_item'));
			$operador="CRICHITO";
			$estacion="INFO04";
			
			if($id_item>0){
			$nombreprocedure="[Rentas].[sp_MInstalacion]";
			$arraydatos[]=array("@busc",3);
			$arraydatos[]=array("@codigo",$codigo);
			$arraydatos[]=array("@cod_pred",$cod_pred);
			$arraydatos[]=array("@anexo",$anexo);
			$arraydatos[]=array("@sub_anexo",$sub_anexo);
			$arraydatos[]=array("@anno",$anno);
			$arraydatos[]=array("@instalacion_item",$id_item);
			$arraydatos[]=array("@operador",trim($login->user));
			$arraydatos[]=array("@estacion",php_uname('n'));
			$cn=new Model_DbDatos_Datos();
			$datos=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
			echo $datos[0][0];
			}
		}
  }
  public function busviasAction(){
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$anno = $this->_request->getParam('anno','');
		
		$fn = new Libreria_Pintar();
				
		$evt[] = array('#btnBusquedacri',"click","buscarDatos()");
		$this->view->anno=$anno;
		$fn->PintarEvento($evt);
  }
  public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	//$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
		$anno=trim($_REQUEST['anno']);

    	$parametros[] = array('@msquery',3);
		$parametros[] = array('@nombre_via',$criterio);
		$parametros[] = array('@anno',$anno);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('[Rentas].[SP_Busvias]', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',2);
		$parametros[] = array('@nombre_via',$criterio);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		$parametros[] = array('@anno',$anno);
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.SP_Busvias', $parametros);
		
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
						'nomvia'=>utf8_encode($row[7]),
						'arancel'=>utf8_encode($row[8])
				
						//'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
				);
			
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
    }
	public function griddocumentosAction(){
			$codigo = $this->_request->getPost('codigo');
			$anno = $this->_request->getPost('anno');
			$cod_pred = $this->_request->getPost('cod_pred');
			$anexo = $this->_request->getPost('anexo');
			$sub_anexo = $this->_request->getPost('sub_anexo');
			
			$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@msquery',4);
			$parametros[] = array('@codigo',$codigo);
			$parametros[] = array('@anno',$anno);
			$parametros[] = array('@cod_pred',$cod_pred);
			$parametros[] = array('@anexo',$anexo);
			$parametros[] = array('@sub_anexo',$sub_anexo);
			$rows = $cn->ejec_store_procedura_sql('rentas.sp_Docu', $parametros);
			
				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'iddoc'=>trim($row[0]),
							'idreg'=>trim($row[1]),
							'docnombre'=>utf8_encode(trim($row[2])),
							'docdetalle'=>utf8_encode(trim($row[3]))
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
	}
	public function buspredioAction(){
			$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$mod = $this->_request->getParam('mod','');
    	$this->view->mod = $mod;
    	
		$this->view->title = "Busqueda de Contribuyentes";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBuscarPredio',"click","buscarContri2()");
		$evt[] = array('#btnNewContri',"click","showPopup('mantcontri/formu','#popcontri','720','440','Nuevo Contribuyente');");
		$evt[] = array('#btnRefreshContri',"click","actualizarContri()");
		$evt[] = array('#btnPagoTupa',"click","pagotupaNuevo()");
		
		if($mod==14){
			$evt[] = array('#btnNewContri',"hide","");
			$evt[] = array('#btnRefreshContri',"hide","");
		}
		if($mod==2)
			$evt[] = array('#btnPagoTupa',"hide","");
			
		$fn->PintarEvento($evt);
	}

	public function consultabuspredAction(){
		$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = $_REQUEST['rdcriterio'];
    	$criterio = $_REQUEST['criterio'];
		$criterionombre = $_REQUEST['criterionombre'];
		$criteriopaterno = $_REQUEST['criteriopaterno'];
		$criteriomaterno = $_REQUEST['criteriomaterno'];
		$criteriorazon = $_REQUEST['criteriorazon'];
		$documento = $_REQUEST['documento'];
		$anno = $_REQUEST['anno'];
    	
		//{rdcriterio: rdCriterio, criterio: txtCriterio, criterionombre:txtCriterioNombre,criteriopaterno: txtCriterioAPaterno,criteriomaterno:txtCriterioAMaterno,criteriorazon:txtCriterioRazon,documento:txtDocumento};
		
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio; break;
    		case 'N': $nombre = $criterio; break;
    		case 'D': $documento = $criterio; break;
			case 'R': $documento = $criterio; break;
    	}
    	
    	//Para el total
    	$parametros[] = array('@busc',6);
		$parametros[] = array('@codigo',$codigo);
		$parametros[] = array('@nombres',$nombre);
		$parametros[] = array('@num_doc',$documento);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Rentas.Bus_predio', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',5);
		$parametros[] = array('@codigo',$criterio);
		$parametros[] = array('@nombres',$criterionombre);
		$parametros[] = array('@paterno',$criteriopaterno);
		$parametros[] = array('@materno',$criteriomaterno);
		$parametros[] = array('@razon',$criteriorazon);
		$parametros[] = array('@num_doc',$documento);
		$parametros[] = array('@tipo_busqueda',$rdcriterio );
		$parametros[] = array('@anno',$anno );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Rentas.Bus_predio', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],				  
						'nombres'=>utf8_encode($row[4])." ".utf8_encode($row[5])." ".utf8_encode($row[6]),
						'documento'=>utf8_encode($row[3]),
						'direccion'=>utf8_encode($row[15]),
						'cod_pred'=>utf8_encode($row[26]),
						'anexo'=>utf8_encode($row[27]),
						'sub_anexo'=>utf8_encode($row[28]),
						'porcen_propiedad'=>utf8_encode($row[29])." %"
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
	}
	public function verpredioAction(){
		$codigo = $this->_request->getPost('codigo');
			$anno = $this->_request->getPost('anno');
			$cod_pred = $this->_request->getPost('cod_pred');
			$anexo = $this->_request->getPost('anexo');
			$sub_anexo = $this->_request->getPost('sub_anexo');
			
			$cn = new Model_DbDatos_Datos();
			$arraydatos0[]=array("@msquery",1);
			$arraydatos0[]=array("@codigo",$codigo);
			$arraydatos0[]=array("@anno",$anno);
			$arraydatos0[]=array("@cod_pred",$cod_pred);
			$arraydatos0[]=array("@anexo",$anexo);
			$arraydatos0[]=array("@sub_anexo",$sub_anexo);
			$data0=$cn->ejec_store_procedura_sql("Rentas.sp_VistaPred",$arraydatos0);
			
			$nombrestoreusos="[Rentas].[sp_UsoArbitrios]";
			$arrayusos[]=array("@msquery",3);
			$arrayusos[]=array("@codigo",$codigo);
			$arrayusos[]=array("@anno",$anno);
			$arrayusos[]=array("@cod_pred",$cod_pred);
			$arrayusos[]=array("@anexo",$anexo);
			$arrayusos[]=array("@sub_anexo",$sub_anexo);
			$data_usos=$cn->ejec_store_procedura_sql($nombrestoreusos,$arrayusos);
			
				$jsonData = array();
				if(count($data0))
				{
					foreach($data0 AS $row){
						$entry = array(
							'codigo'=>trim($codigo),
							'cod_pred'=>trim($cod_pred),
							'anexo'=>trim($anexo),
							'sub_anexo'=>trim($sub_anexo),
							'arancel'=>$data0[0][33],
							'vterreno'=>$data0[0][34],
							'vcontruccion'=>$data0[0][35],
							'vinstalaciones'=>$data0[0][36],
							'autoavaluo'=>$data0[0][37],
							'cod_via'=>$data0[0][7],
							'nombre_via'=>$data0[0][54],
							'nombre_cp'=>$data0[0][55],
							'direccion'=>$data0[0][13],
							'nro'=>$data0[0][11],
							'num_dpto'=>$data0[0][12],
							'num_mza'=>$data0[0][8],
							'num_lote'=>$data0[0][9],
							'num_sublote'=>$data0[0][10],
							'num_calle'=>$data0[0][11],
							'frontis'=>$data0[0][29],
							'area_terreno'=>$data0[0][31],
							'area_comun'=>$data0[0][32],
							'txtPorcenPropiedad'=>$data0[0][38],
							'num_condo'=>$data0[0][24],
							'sum_luz'=>$data0[0][18],
							'sum_agua'=>$data0[0][19],
							'lice_cons'=>$data0[0][20],
							'conf_obra'=>$data0[0][21],
							'decl_fabr'=>$data0[0][22],
							'fec_compra'=>$data0[0][52],
							'fec_venta'=>$data0[0][53],
							'obs'=>$data0[0][47],
							'afec_arbitrios'=>$data0[0][41],
							'afec_arb_desde'=>$data0[0][42],
							'afec_arb_hasta'=>$data0[0][43],
							'arbitrios_observa'=>$data0[0][56],
							'afec_pred'=>$data0[0][40],
							'num_piso'=>$data0[0][23],
							'chbVendido'=>$data0[0][27],
							'cbParque'=>$data_usos[0][2],
							'cmbUso'=>$data0[0][15],
							'cmbTipPredio'=>$data0[0][17],
							'cmbEstadoConst'=>$data0[0][16],
							'cmbCondicion'=>$data0[0][14],
							'cmbTipoAdqui'=>$data0[0][57],
							'nro2'=>$data0[0][60],
							'letra'=>$data0[0][61],
							'letra2'=>$data0[0][62],
							'cmbInterior'=>$data0[0][63],
							'fondo'=>$data0[0][65],//desde aqui
							'area_uso'=>$data0[0][59],
							'nom_zona'=>$data0[0][64],
							'tipo_edificio_id'=>$data0[0][79],
							'nombre_edificio'=>$data0[0][80],
							'piso'=>$data0[0][81],
							'numero_interno'=>$data0[0][82],
							'letra_interno'=>$data0[0][83],
							'tipo_ingreso_id'=>$data0[0][84],
							'nombre_ingreso'=>$data0[0][85],
							'tipo_agrupamiento_id'=>$data0[0][86],
							'nombre_agrupamiento'=>$data0[0][87]
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
				
	}
	public function propietariosAction(){
		$this->_helper->layout->disableLayout();
		$cod_pred = $this->_request->getParam('cod_pred','');
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$this->view->cod_pred=$cod_pred;
	}
	public function gridpropietariosAction(){
		$cod_pred = $this->_request->getPost('cod_pred');
		
		$cn = new Model_DbDatos_Datos();
			$parametros[] = array('@buscar',6);
			$parametros[] = array('@cod_pred',$cod_pred);

			$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_rentasmain]', $parametros);

				$jsonData = array();
				if(count($rows))
				{
					foreach($rows AS $row){
						$entry = array(
							'codigo'=>trim($row[0]),
							'Nombre'=>utf8_encode(trim($row[3])),
							'Porcen'=>trim($row[2])
						);
						$jsonData[] = $entry;
					}
				}
				$this->view->data = json_encode($jsonData);
	}
	
	public function historicopredioAction(){
		$path=	new Zend_Session_Namespace('path');
    	
    	$this->_helper->layout->disableLayout();
    	$this->view->title = "Historico de Predios";
		$codigo = $this->_request->getParam('codigo','');
		$anno = $this->_request->getParam('anno','');
    	$cod_pred = $this->_request->getParam('cod_pred','');
		$anexo = $this->_request->getParam('anexo','');
		$sub_anexo = $this->_request->getParam('sub_anexo','');
		
		unset($parametros);
		$parametros[]=array('@buscar',3);
		$parametros[]=array('@codigo',$codigo);

		$cn = new Model_DbDatos_Datos();
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[sp_rentasmain]', $parametros);
		
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		
		unset($parametros);
		$parametros[]=array('@buscar',9);
		$parametros[]=array('@codigo',$codigo);
		$parametros[]=array('@cod_pred',$cod_pred);
		$parametros[]=array('@anno',$anno);
		$parametros[]=array('@anexo',$anexo);
		$parametros[]=array('@sub_anexo',$sub_anexo);
		$rowdir = $cn->ejec_store_procedura_sql('[Rentas].[sp_rentasmain]', $parametros);
		
		
		$val[] = array("#divECodigo",$codigo,"html");
		$val[] = array("#divENombre",$rows[0][1],"html");
		$val[] = array("#divCodPred",$cod_pred,"html");
		$val[] = array("#divSAnexo",$anexo."-".$sub_anexo,"html");
		$val[] = array("#divDireccion",$rowdir[0][0],"html");
		$val[] = array("#divAnno",$anno,"html");
		
		$evt[] = array('#btnCerrar',"click","closePopup('#popuphistorial');");	
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
	}
	public function historialpredAction(){
		$path=	new Zend_Session_Namespace('path');
    	
		$codigo = $this->_request->getParam('codigo','');
    	$cod_pred = $this->_request->getParam('cod_pred','');
		$anexo = $this->_request->getParam('anexo','');
		$sub_anexo = $this->_request->getParam('sub_anexo','');
		$cn = new Model_DbDatos_Datos();
		unset($parametros);
		$parametros[]=array('@busc',1);
		$parametros[]=array('@codigo',$codigo);
		$parametros[]=array('@cod_pred',$cod_pred);
		$parametros[]=array('@anexo',$anexo);
		$parametros[]=array('@sub_anexo',$sub_anexo);
		$rows = $cn->ejec_store_procedura_sql('[Historial].[Reporte]', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>trim($row[1]),	
						'cod_pred'=>trim($row[7]),	
						'anexo'=>trim($row[8]),	
						'sub_anexo'=>trim($row[9]),	
						'dj_predial'=>trim($row[0]),	
						'anno'=>trim($row[6]),				  
						'motivo_declaracion'=>utf8_encode(trim($row[65])),
						'condicion_propiedad'=>trim($row[11]),
						'tipo_adquisicion'=>trim($row[12]),
						'fecha'=>utf8_encode(trim($row[16])),
						'porc_propiedad'=>trim($row[13]),
						'area_terreno'=>trim($row[17]),
						'registrado'=>utf8_encode(trim($row[62])),
						'fiscalizado'=>trim($row[76])
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
	}
}

