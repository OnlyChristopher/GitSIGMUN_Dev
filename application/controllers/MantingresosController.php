<?php

/**
 * MantingresosController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class MantingresosController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	public function indexAction() {
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Búsqueda de Contribuyentes";
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#btnBusIngreso',"button","");
		$evt[] = array('#btnNuevoIngreso',"button","");
		$evt[] = array('#btnSalirIngreso',"button","");
		
		$evt[] = array('#btnBusIngreso',"click","buscarIngresos()");
		
		$evt[] = array('#btnNuevoIngreso',"click","showPopup('mantingresos/formu?actionIngreso=A','#popupIngreso','1000','320','Ingreso de Tipos de Ingresos');");
		
		$fn->PintarEvento($evt);
	}
	
    public function formuAction()
    {    		
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		$evt[] = array('#btnGrabaIngreso',"button","");
		$evt[] = array('#btnSalirIngresos',"button","");
		
		$evt[] = array('#txtDesIngreso',"keypress","return validaTeclas(event,'text');");
		$evt[] = array('#txtDesIngreso',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtResumen',"keypress","return validaTeclas(event,'text');");				
		$evt[] = array('#txtResumen',"blur","this.value = this.value.toUpperCase();");
		
		$evt[] = array('#txtUit',"keypress","return validaTeclas(event,'numeric');");
    	
		$evt[] = array('#txtMonto',"keypress","return validaTeclas(event,'numeric');");
    	
		$evt[] = array('#btnGrabaIngreso',"click","goToFormulario('frmingreso');");
		
		$evt[] = array('#btnSalirIngresos',"click","closePopup('#popupIngreso');");
		
		$cn = new Model_DbDatos_Datos();
		$cod=$this->_request->getParam('codigo','');
		if(!empty($cod))
		{
			$nombreprocedure="dbo.t_tipo_sing";
			$arraydatos[0] = array("@msquery", 9);
			$arraydatos[1] = array("@tipo", $cod);	
			$rows = $cn->ejec_store_procedura_sql($nombreprocedure, $arraydatos);
			
			//nombre,resumen,partida,conta,porc_uit,valor,ftefin,area
			
			$this->view->cod=$cod;
			$this->view->nombre=$rows[0][0];
			$this->view->resumen=$rows[0][1];
			$this->view->partida=$rows[0][2];
			$this->view->porc_uit=$rows[0][4];
			$this->view->valor=$rows[0][5];
			//$this->view->fuente=$rows[0][6];
			
			$combostore1 ='dbo.t_tipo_sing';
			$arraydatos[0] = array("@msquery",3);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
			$cb_Partida='<option value="">[Seleccione]</option>';

			for ($i=0;$i<count($rows1);$i++){
					if(trim($rows1[$i][0])==trim($rows[0][2]))
					{
						$cb_Partida.='<option value="'.$rows1[$i][0].'" selected>'.$rows1[$i][1].'</option>';
						
					}
					else{
						$cb_Partida.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';						
					}
        		}
			
			$combostore2 ='dbo.t_tipo_sing';
	        $arraydatos[0] = array("@msquery", 4);
	        $rows2 = $cn->ejec_store_procedura_sql($combostore2,$arraydatos);
		    $cb_Contable='<option value="">[Seleccione]</option>';
	       
	
			for ($i=0;$i<count($rows2);$i++){
					if($rows2[$i][0]==$rows[0][3])
					{
						$cb_Contable.='<option value="'.$rows2[$i][0].'" selected>'.$rows2[$i][1].'</option>';
					}
					else{
            			$cb_Contable.='<option value="'.$rows2[$i][0].'" >'.$rows2[$i][1].'</option>';
					}
        		}
			/*
			$combostore3 ='dbo.t_tipo_sing';
	        $arraydatos[0] = array("@msquery", 5);
	        $rows3 = $cn->ejec_store_procedura_sql($combostore3,$arraydatos);
	        $cb_Fuente='<option value="">[Seleccione]</option>';
		    
	    
			for ($i=0;$i<count($rows3);$i++){
					if($rows3[$i][0]==$rows[0][6])
					{
						$cb_Fuente.='<option value="'.$rows3[$i][0].'" selected>'.$rows3[$i][0].'</option>';
					}
					else{
						$cb_Fuente.='<option value="'.$rows3[$i][0].'" >'.$rows3[$i][0].'</option>';
					}
        		}
        	*/
        	$valor_xxx[]=array("08");
        	$valor_xxx[]=array("09");
        	$cb_Fuente='<option value="">[Seleccione]</option>';	
			for ($i=0;$i<count($valor_xxx);$i++){
					if($valor_xxx[$i][0]==$rows[0][6])
					{
	            		$cb_Fuente.='<option value="'.$valor_xxx[$i][0].'" selected>'.$valor_xxx[$i][0].'</option>';
					}
					else{
						$cb_Fuente.='<option value="'.$valor_xxx[$i][0].'" >'.$valor_xxx[$i][0].'</option>';
					}
	        	}
        		
			$combostore4 ='dbo.t_tipo_sing';
	        $arraydatos[0] = array("@msquery", 6);
	        $rows4 = $cn->ejec_store_procedura_sql($combostore4,$arraydatos);
		    $cb_Area='<option value="">[Seleccione]</option>';
	    
			for ($i=0;$i<count($rows4);$i++){
					if($rows4[$i][0]==$rows[0][7])
					{
						$cb_Area.='<option value="'.$rows4[$i][0].'" selected>'.$rows4[$i][1].'</option>';
					}
					else{
						$cb_Area.='<option value="'.$rows4[$i][0].'" >'.$rows4[$i][1].'</option>';
					}				            	
        		}
        	$val[] = array('#cmbPartida',$cb_Partida,'html');	
	        $val[] = array('#cmbContable',$cb_Contable,'html');
	        $val[] = array('#cmbFuentes',$cb_Fuente,'html');
	        $val[] = array('#cmbArea',$cb_Area,'html');
			
		}
		else
		{
			$cn = new Model_DbDatos_Datos();
	    	
			$combostore1 ='dbo.t_tipo_sing';
			$arraydatos1[0] = array("@msquery",3);
			$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos1);
			$cb_Partida='<option value="">[Seleccione]</option>';
		   		
				for ($i=0;$i<count($rows1);$i++){
	            	$cb_Partida.='<option value="'.$rows1[$i][0].'" >'.$rows1[$i][1].'</option>';
	        		}
			
	        	$val[] = array('#cmbPartida',$cb_Partida,'html');	
			
			$combostore2 ='dbo.t_tipo_sing';
	        $arraydatos2[0] = array("@msquery", 4);
	        $rows2 = $cn->ejec_store_procedura_sql($combostore2,$arraydatos2);
		    $cb_Contable='<option value="">[Seleccione]</option>';
		    
				for ($i=0;$i<count($rows2);$i++){
	            	$cb_Contable.='<option value="'.$rows2[$i][0].'" >'.$rows2[$i][1].'</option>';
	        		}
	        	$val[] = array('#cmbContable',$cb_Contable,'html');
	 
			$valor_xxx[]=array("08");
        	$valor_xxx[]=array("09");
        	$cb_Fuente='<option value="">[Seleccione]</option>';	
			for ($i=0;$i<count($valor_xxx);$i++){	
						$cb_Fuente.='<option value="'.$valor_xxx[$i][0].'" >'.$valor_xxx[$i][0].'</option>';
	        	}
	        	$val[] = array('#cmbFuentes',$cb_Fuente,'html');
	 
	        $combostore4 ='dbo.t_tipo_sing';
	        $arraydatos4[0] = array("@msquery", 6);
	        $rows4 = $cn->ejec_store_procedura_sql($combostore4,$arraydatos4);
		    $cb_Area='<option value="">[Seleccione]</option>';
		    
				for ($i=0;$i<count($rows4);$i++){
	            	$cb_Area.='<option value="'.$rows4[$i][0].'" >'.$rows4[$i][1].'</option>';
	        		}
	        	$val[] = array('#cmbArea',$cb_Area,'html');
		}
						
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);	
		
    }
	
    public function consultaAction()
    {
    	$cn = new Model_DbDatos_Datos();
    	
    	$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
    	$rdcriterio = trim($_REQUEST['rdcriterio']);
    	$criterio = trim($_REQUEST['criterio']);
   
    	switch($rdcriterio)
    	{
    		case 'C': $codigo = $criterio;break;
    		case 'D': $descripcion = $criterio;break;
    		case 'A': $area = $criterio;break;
    		
    	}
    	
    	//Para el total
    	$parametros[] = array('@msquery',16);
		$parametros[] = array('@tipo',$codigo);
		$parametros[] = array('@nombre',$descripcion);
		$parametros[] = array('@area',$area);
		
		$rowsTotal = $cn->ejec_store_procedura_sql('dbo.t_tipo_sing', $parametros);
    	
		//Para las filas
		unset($parametros);
    	$parametros[] = array('@msquery',15);
		$parametros[] = array('@tipo',$codigo);
		$parametros[] = array('@nombre',$descripcion);
		$parametros[] = array('@area',$area);
		$parametros[] = array('@inicio',$start);
		$parametros[] = array('@final',$end);
		
		$rows = $cn->ejec_store_procedura_sql('dbo.t_tipo_sing', $parametros);
		
		/* TI.tipo,TI.nombre AS NOMBRE_TIPO,TI.partida,A.nombre,TI.ftefin,TI.valor */
		
		$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
		if(count($rows))
		{
			foreach($rows AS $row){
				$entry = array(
						'codigo'=>$row[0],
						'descripcion'=>$row[1],	
						'partida'=>$row[2],		 
						'area'=>$row[3],	
						'fuente'=>$row[4],	 
						'monto'=>$row[5],
				);
				$jsonData['rows'][] = $entry;
			}	
		}	
		$this->view->data = json_encode($jsonData);
    }
    
   
	public function grabarAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	    	
    	if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			$codingreso = $this->_request->getPost('txtCodIngreso');
			
			if(strlen($codingreso)>0)
				$tip = 10;
			else
				$tip = 7;
				
			$parametros[] = array('@msquery',$tip);
			$parametros[] = array('@tipo',$codingreso);
			$parametros[] = array('@nombre',$this->_request->getPost('txtDesIngreso'));
			$parametros[] = array('@resumen',$this->_request->getPost('txtResumen'));
			$parametros[] = array('@partida',$this->_request->getPost('txtPartida'));			
			$parametros[] = array('@conta',$this->_request->getPost('cmbContable'));
			$parametros[] = array('@porc_uit',$this->_request->getPost('txtUit'));
			$parametros[] = array('@valor',$this->_request->getPost('txtMonto'));
			$parametros[] = array('@ftefin',$this->_request->getPost('cmbFuentes'));
			$parametros[] = array('@area',$this->_request->getPost('cmbArea'));
														
			@$rows = $cn->ejec_store_procedura_sql('dbo.t_tipo_sing', $parametros);
			
			echo "Se grabo correctamente";
    	}    	
    	
    }
    
    
	public function eliminarAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	
    	if($this->getRequest()->isXmlHttpRequest()){
		$cod=$this->_request->getParam('codigo','');
		$array=explode('*',$cod);
		$cn = new Model_DbDatos_Datos();
		$nombreprocedure="dbo.t_tipo_sing";
		$arraydatos[0] = array("@msquery", 8);
		$arraydatos[1] = array("@tipo", $array[0]);
		@$rows=$cn->ejec_store_procedura_sql($nombreprocedure,$arraydatos);
		
			echo 'Se elimino correctamente';
    	}
	}
    
}

