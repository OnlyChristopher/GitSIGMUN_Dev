<?php

class GpreventivaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {    	    	    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$mod = $this->_request->getParam('mod','');
    	$this->view->mod = $mod;
		
    	$val[] = array('#txtmod', $mod, 'val');
		$this->view->title = "Gestion de Preventivas";
		
		//************************
		//FECHAS INICIAL Y FINAL
		//************************
		$evt[] = array('#txtFini',"datepicker","");
		$evt[] = array('#txtFfin',"datepicker","");
		
		$fecha_ini = "01/".date("m")."/".date("Y");
		$val[] = array('#txtFini', $fecha_ini, 'val');
		
		$fecha_fin = @date('d/m/Y');
		$val[] = array('#txtFfin', $fecha_fin, 'val');
		//****************
		//Listar los anios
		//****************
		$cn = new Model_DbDatos_Datos();
	
	    $param[] = array('@busc',11);
		$rowsAnios = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $param);
		$bus_anno="<option value=''>-seleccione-</option>";
		for($i=0;$i<count($rowsAnios);$i++){
		$bus_anno.="<option value=".$rowsAnios[$i][0].">".$rowsAnios[$i][0]."</option>";
		}
		$this->view->cb_anno=$bus_anno;
		
		//*****************
		//LISTAR LAS AREAS
		//*****************
		
		$combo1[]=array ('@busc',8);
		$rowsCombo1 =$cn->ejec_store_procedura_sql('rentas.sp_multasadmin',$combo1);
		$list_combo1="<option value=''>-seleccione-</option>";
		for($i=0;$i<count($rowsCombo1);$i++)
		{
			$list_combo1.="<option value=".$rowsCombo1[$i][0].">".$rowsCombo1[$i][1]."</option>";
		}
		$this->view->cb_area=$list_combo1;
		
		$evt [] = array ('#cmbInfraccion', "change", "FiltraCodArea(this.value);" );
		$evt [] = array ('#cmbInfraccion', "change", "Muestraselect();" );
		$evt [] = array ('#cmbConcepto', "change", "FiltraTipInfrac(this.value);" );
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnBuscPreventiva',"click","buscarPreventiva()");
		
		$evt[] = array('#btnCargo',"click","ImpremeCargoRecepcion()");
		
		// $evt[] = array('#btnNewContri',"click","showPopup('mantinfractor/formu','#popinfractor','800','670','Nuevo Infractor');"); // ventana nuevo contribuyente
		// $evt[] = array('#btnRefreshContri',"click","actualizarContri()");
		// $evt[] = array('#btnPagoTupa',"click","pagotupaNuevo()");
		
		// if($mod==14){
			// $evt[] = array('#btnNewContri',"hide","");
			// $evt[] = array('#btnRefreshContri',"hide","");
		// }
		// if($mod==2)
			// $evt[] = array('#btnPagoTupa',"hide","");
			
		$fn->PintarEvento($evt);	
		$fn->PintarValor ($val);
    }
    
	
	
	public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$npreventiva 	= $_REQUEST['npreventiva'];
    	$anno 		 	= $_REQUEST['anno'];
		$tipo	 	 	= $_REQUEST['tipo'];
		$anno_ini	 	= $_REQUEST['anno_ini'];
		$anno_fin	 	= $_REQUEST['anno_fin'];
		$anno_area	 	= $_REQUEST['anno_area'];
		$res1 		 	= $_REQUEST['res1'];
		$res2  		 	= $_REQUEST['res2'];
		$res3 		 	= $_REQUEST['res3'];
    	

    	
    	//Para el total
    	$parametros[] = array('@busc',10);
		$parametros[] = array('@id_notif',$npreventiva);
		$parametros[] = array('@anno_notif',$anno);
		$parametros[] = array('@t_notif',$tipo);
		$parametros[] = array('@fecha_ini',$anno_ini);
		$parametros[] = array('@fecha_fin',$anno_fin);
		//$parametros[] = array('@anno_area',$anno_area);
		$parametros[] = array('@anno_area',2011);
		$parametros[] = array('@codigo_area',$res1);
		$parametros[] = array('@tipo_infraccion',$res2);
		$parametros[] = array('@codigo_infraccion',$res3 );
		
		$rowsTotal = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@busc',9);
		$parametros[] = array('@id_notif',$npreventiva);
		$parametros[] = array('@anno_notif',$anno);
		$parametros[] = array('@t_notif',$tipo);
		$parametros[] = array('@fecha_ini',$anno_ini);
		$parametros[] = array('@fecha_fin',$anno_fin);
		$parametros[] = array('@anno_area',$anno_area);
		$parametros[] = array('@codigo_area',$res1);
		$parametros[] = array('@tipo_infraccion',$res2);
		$parametros[] = array('@codigo_infraccion',$res3 );
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		
		$rows = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $parametros);
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'nro_resol'		=>	$row[0],
						'id_notif'		=>	$row[1],
						'anno_notif'	=>	$row[2],
						'codigo'		=>	$row[3],
						'n_notif'		=>	$row[4],
						't_notif'		=>	$row[5],
						'fec_notif'		=>	$row[6],
						'est_subsa'		=>	$row[7],
						'anno_area'		=>	$row[8],
						'codigo_area'	=>	$row[9],
						'tipo_infraccion'=>	$row[10],
						'codigo_infraccion'=>$row[11],
						'codigo_adicional'=>$row[12],
						'd_infraccion'	=>	utf8_encode($row[13]),
						'base_calc'		=>	$row[14],
						'factor'		=>	$row[15],
						'monto'			=>	$row[16],
						'codtipocontri'	=>	$row[17],
						'resolucion'	=>	$row[18],
						'pase'			=>	$row[19]
						
				);
				$jsonData['rows'][] = $entry;
			}
		}
		$this->view->data = json_encode($jsonData);
		
		//echo('manuel');
    }
	
	
	public function selectareaAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
    		$cn = new Model_DbDatos_Datos();
			
			$codigo_area = $this->_request->getPost('codigo_area');			
			$anno_area = $this->_request->getPost('anno_area');
			
			$parametros [] = array ('@anno_area', $anno_area);
			$parametros [] = array ('@codigo_area', $codigo_area);
			$rows = $cn->ejec_store_procedura_sql('stpMultas_Qry_Tipo_Infracciones_preventiva', $parametros);
			
			if(count($rows)){
				print("<option value=''>-seleccione-</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[0])."'>".utf8_encode($row[1])."</option>");
			}
			else{
				print("<option value=''>-seleccione-</option>");
			}
		}		
	}
	
	public function selectAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
		if($this->getRequest()->isXmlHttpRequest()){
		print("<option value=''>-seleccione-</option>");
		}
	}
	
	
	
	
	public function selecttipoAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
    		$cn = new Model_DbDatos_Datos();
			
			$tipo_infraccion = $this->_request->getPost('tipo_infraccion');
			$tipocontri_multa = $this->_request->getPost('tipocontri_multa');
			
			$partes = explode(':',$tipo_infraccion);
			
			$parametros [] = array ('@anno_area', '2011');
			$parametros [] = array ('@codigo_area', $partes[0]);
			$parametros [] = array ('@tipo_infraccion', $partes[1]);
			$parametros [] = array ('@id_tipocontri', $tipocontri_multa);
			$rows = $cn->ejec_store_procedura_sql('stpMultas_Qry_Detalle_Tipo_Infracciones_preventiva', $parametros);
			if(count($rows)){
				print("<option value=''>-seleccione-</option>");
				foreach($rows AS $row)
					print("<option value='".utf8_encode($row[0])."'>".utf8_encode($row[1])."</option>");
			}
			else{
				print("<option value=''>-seleccione-</option>");
			}
			//echo $partes[0]."-".$partes[1]."-".$partes[2];
		}		
	}
	
	
	public function frmgestionAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		
		$codigo  		= $this->_request->getParam('codigo');
		$npreventiva  	= $this->_request->getParam('npreventiva');
		$anno  			= $this->_request->getParam('anno');
		$tipo  			= $this->_request->getParam('tipo');
		
		if($tipo=='Acta')
		$tipo1='Acta de Constatacion';
		else
		$tipo1='Notificacion Preventiva';
		
		
		$parametros[]= array ('@busc',7);
		$parametros[]= array ('@codigo',$codigo);
		$parametros[]= array ('@id_notif',$npreventiva);
		$parametros[]= array ('@anno_notif',$anno);
		$parametros[]= array ('@t_notif',$tipo);

		$rows = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $parametros);
		
		$ninfractor	=$rows[0][6];
		$sancion	=$rows[0][10];
		$notificacion	=$rows[0][26];
		$factor			=$rows[0][23];
		$monto			=$rows[0][24];
		//Muestra los datos
		
		$this->view->codigo=$codigo;
		$val[] = array('#codigo',$codigo,'val');
		$this->view->npreventiva=$npreventiva;
		$val[] = array('#idnotif',$npreventiva,'val');
		$this->view->anno=$anno;
		$val[] = array('#annonotif',$anno,'val');
		$this->view->tipo=$tipo1;
		$val[] = array('#tnotif',$tipo1,'val');
			
		$this->view->nombre=$ninfractor;
		$this->view->sancion=$sancion;
		$this->view->notificacion=$notificacion;
		$this->view->factor=$factor;
		$this->view->monto=$monto;
		
		$evt[] = array('#btnSalirGestion',"click","closePopup('#popgestion');");
		$evt[] = array('#btnGrabaGestion',"click","goToFormulario('frmgestion');");
		
		$fn->PintarEvento($evt);
		$fn->PintarValor ($val);
		
	}
	
	public function grabargestionAction()
	{
		$login = new Zend_Session_Namespace('login');
		
		$usuario = $login->user;
		
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();	
		if($this->getRequest()->isXmlHttpRequest()){
		
		$cn = new Model_DbDatos_Datos();
		
		$idnotif = $this->_request->getPost('idnotif');
		$annonotif = $this->_request->getPost('annonotif');
		$tnotif = $this->_request->getPost('tnotif');
		$codigo = $this->_request->getPost('codigo');
		
		$chkResoluciones = $this->_request->getPost('chkResoluciones');
		if($chkResoluciones=='1')
		{
			$txtFechResolucion = $this->_request->getPost('txtFechResolucion');
			$txtFechCarta = $this->_request->getPost('txtFechCarta');
			$txtSustento = $this->_request->getPost('txtSustento');
			$txtNCarta = $this->_request->getPost('txtNCarta');
			$txaObservacion = $this->_request->getPost('txaObservacion');
		}
		
		unset($parametros);	
		$parametros[] = array('@busc',12);
		
		$parametros[] = array('@id_notif',$idnotif);
		$parametros[] = array('@anno_notif',$annonotif);
		$parametros[] = array('@codigo',$codigo);
		$parametros[] = array('@t_notif',$tnotif);
		
		
		$parametros[] = array('@est_subsa',$chkResoluciones);
		$parametros[] = array('@fec_subsa',$txtFechResolucion);
		$parametros[] = array('@fec_carta',$txtFechCarta);
		$parametros[] = array('@sustento',$txtSustento);
		$parametros[] = array('@n_carta',$txtNCarta);
		$parametros[] = array('@obs_subsa',$txaObservacion);
		$parametros[] = array('@usuario_act',$usuario);
		$IP = $_SERVER['REMOTE_ADDR'];
		$parametros[] = array('@estacion_act',$IP);
		
		
		@$rows = $cn->ejec_store_procedura_sql('Mpreventiva.Notificacion', $parametros);
		
		//echo("Se grabo correctamente");
		}
	}
	
	public function CambioAnnoAction()
	{
		$el_anno  = $this->_request->getParam('anno');
		
		$fn = new Libreria_Pintar();
		if($el_anno==date("Y"))
		{
		$fecha1="01/01/".date("Y");
		$fecha2="01/".date("m")."/".date("Y");
		}
		else
		{
		$fecha1="01/01/".date("Y");
		$fecha2="01/12/".date("Y");
		}
		
		$val[] = array('#txtFini',$fecha1,'val');
		$val[] = array('#txtFfin',$fecha2,'val');
		
		$fn->PintarValor ($val);
	}
	
}


