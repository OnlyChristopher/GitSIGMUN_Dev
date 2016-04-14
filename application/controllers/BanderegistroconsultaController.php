<?php

class BanderegistroconsultaController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }



	
	public function indexAction()
    {	
		$fn = new Libreria_Pintar();
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Consulta de Registro";
		
		$evt[] = array('#fec_desde',"datepicker","");
		$evt[] = array('#fec_desde','autotab',"{format:'date'}");
		$evt[] = array('#fec_hasta',"datepicker","");
		$evt[] = array('#fec_hasta','autotab',"{format:'date'}");
		
		$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
		$this->view->fec_desde = $fecharow[0][0];
		$this->view->fec_hasta = $fecharow[0][0];
		
			
		$estados = $ar->EstadoRegistro();
		$val[] = array('#registro_estado',$fn->ContenidoCombo($estados,'[Todos]',''),'html');
		
		unset($parametros);
		$parametros[] = array('@msquery',19);	
		$comboUsuario = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro', $parametros);
		$arUsuario = $ar->RegistrosCombo($comboUsuario,0,1);
		$val[] = array('#usuario_cod',$fn->ContenidoCombo($arUsuario,'',''),'html');
		
		
		
		// $arraydatos[] = array('@msquery',20);
		// $rows = $cn->ejec_store_procedura_sql('Coactivo.SP_Registro',$arraydatos);
		// $arRows = $ar->RegistrosCombo($rows,0,1);
		// $val[] = array('#usuario_cod',$fn->ContenidoCombo($arRows,'[Todos]',''),'html');
		
		$evt[] = array('#btnBusarRegistro', "click", "goToFormulario('frmBandeconsulta')" );
		$evt[] = array('#btnCancelRegistro', "click", "getEnlace('banderegistroconsulta/index');" );
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
	public function consultaregistroAction()
    {	
		$fn = new Libreria_Pintar();
				
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "Consulta de Registro";
		
		$json = $this->_request->getPost('json');
		$data = json_decode($json);
		
		$val[] = array('#num_archi',$data->num_archi,'val');
		$val[] = array('#cod_contri',$data->cod_contri,'val');
		$val[] = array('#nom_contri',$data->nom_contri,'val');
		$val[] = array('#num_expe',$data->num_expe,'val');
		$val[] = array('#ano_expe',$data->ano_expe,'val');
		$val[] = array('#fec_desde',$data->fec_desde,'val');
		$val[] = array('#fec_hasta',$data->fec_hasta,'val');
		$val[] = array('#registro_estado',$data->registro_estado,'val');
		$val[] = array('#usuario_cod',$data->usuario_cod,'val');		
				
		$evt[] = array('#btnNewSearch', "click", "getEnlace('banderegistroconsulta/index');" );
		$evt[] = array('#btnExpExcel', "click", "exportarConsulta();" );
		
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
	}
	
		
	public function reconsulAction() {
	
		$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();
		
    	$page = isset($_REQUEST['page'])   ? $_REQUEST['page'] : 1;
    	$start = isset($_REQUEST['start']) ? $_REQUEST['start'] :0;
    	$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] :10;
    	
    	$start = (($page-1) * $limit)+1;
    	$end = $start + $limit - 1;
    	
		$num_archi = $_REQUEST['num_archi'];
		$num_expe = $_REQUEST['num_expe'];
		$ano_expe = $_REQUEST['ano_expe'];	
		$cod_contri = $_REQUEST['cod_contri'];
		$nom_contri = $_REQUEST['nom_contri'];
		$usuario = $_REQUEST['usuario_cod'];     
		$estado = $_REQUEST['registro_estado'];  		
		$fec_desde = $_REQUEST['fec_desde'];
		$fec_hasta = $_REQUEST['fec_hasta'];
		
		
    	//Para el total
    	$arraydatos[] = array('@busc',2);
		$arraydatos[] = array('@num_archivador',$num_archi);
		$arraydatos[] = array('@num_expe',$num_expe);
		$arraydatos[] = array('@ano_expe',$ano_expe);
		$arraydatos[] = array('@cod_contribuyente',$cod_contri);
		$arraydatos[] = array('@razon_social',$nom_contri);
		$arraydatos[] = array('@nomdigitador',$usuario);  
		$arraydatos[] = array('@estado_coac',$estado);		
		$arraydatos[] = array('@fec_desde',$fec_desde);
		$arraydatos[] = array('@fec_hasta',$fec_hasta);
		@$rowsTotal = $cn->ejec_store_procedura_sql('Coactivo.Consultaregistro', $arraydatos);
    	
		//Para las filas
		unset($arraydatos);
    	$arraydatos[] = array('@busc',1);
		$arraydatos[] = array('@num_archivador',$num_archi);
		$arraydatos[] = array('@num_expe',$num_expe);
		$arraydatos[] = array('@ano_expe',$ano_expe);
		$arraydatos[] = array('@cod_contribuyente',$cod_contri);
		$arraydatos[] = array('@razon_social',$nom_contri);  
		$arraydatos[] = array('@nomdigitador',$usuario);  
		$arraydatos[] = array('@estado_coac',$estado);		
		$arraydatos[] = array('@fec_desde',$fec_desde);
		$arraydatos[] = array('@fec_hasta',$fec_hasta);	
		$arraydatos[] = array("@inicio",$start);
		$arraydatos[] = array("@final",$end);
		@$rows = $cn->ejec_store_procedura_sql('Coactivo.Consultaregistro', $arraydatos);
		
		if(count($rows)){
			$jsonData = array('total'=>$rowsTotal[0][0],'rows'=>array());
				foreach($rows AS $row){
					$entry = array(						
						'num_archi' => utf8_encode($row[17]),
						'num_expe' => utf8_encode($row[0]),
						'ano_expe' => utf8_encode($row[1]),
						'cod_contribuyente' => utf8_encode($row[2]),
						'razon_social' => utf8_encode($row[3]),
						'domicilio_fiscal' =>utf8_encode($row[4]),						
						'domicilio_predio' => utf8_encode($row[5]),						
						'sector_zona' => utf8_encode($row[6]),
						'ano_periodo' => utf8_encode($row[7]),
						'abreviatura' => utf8_encode($row[8]),
						'num_valor_tribu' => utf8_encode($row[9]),
						'monto' => utf8_encode($row[10]),
						'rec1' => utf8_encode($row[11]),
						'fech_rec1' => $ar->toDate($row[12]),
						'observacion' => utf8_encode($row[13]),
						'estado_desc' => utf8_encode($row[14]),
						'nomdigitador' => utf8_encode($row[15]),
						'fecha_ing' => ($row[16])
						//'fecha_ing' => $ar->toDate($row[16])
						
					);
					$jsonData['rows'][] = $entry;
			}
		}
		else{
			$jsonData = array('total'=>0,'rows'=>array());
		}
				
		$this->view->data=json_encode($jsonData);
	}
	

	public function exportaAction() 
	{
		$getlogin = new Zend_Session_Namespace('login');
        $hostname= strtoupper(gethostname());
		$username= strtoupper($getlogin->user);
		
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
		$num_archi = $_REQUEST['num_archi'];
		$num_expe = $_REQUEST['num_expe'];
		$ano_expe = $_REQUEST['ano_expe'];	
		$cod_contri = $_REQUEST['cod_contri'];
		$nom_contri = $_REQUEST['nom_contri'];
		$usuario = $_REQUEST['usuario_cod'];     
		$estado = $_REQUEST['registro_estado'];  		
		$fec_desde = $_REQUEST['fec_desde'];
		$fec_hasta = $_REQUEST['fec_hasta'];
		
		$arraydatos[] = array('@busc',1);
		$arraydatos[] = array('@num_archivador',$num_archi);
		$arraydatos[] = array('@num_expe',$num_expe);
		$arraydatos[] = array('@ano_expe',$ano_expe);
		$arraydatos[] = array('@cod_contribuyente',$cod_contri);
		$arraydatos[] = array('@razon_social',$nom_contri);  
		$arraydatos[] = array('@nomdigitador',$usuario);  
		$arraydatos[] = array('@estado_coac',$estado);		
		$arraydatos[] = array('@fec_desde',$fec_desde);
		$arraydatos[] = array('@fec_hasta',$fec_hasta);	
		@$rows = $cn->ejec_store_procedura_sql('Coactivo.Consultaregistro', $arraydatos);
		$cabecera="";
		if(count($rows)){
			$strHtml = "<table border='1'>";					
			$cabecera .= "<tr>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Contribuyente</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Expediente</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Numero Valor</th>";				
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Monto</th>";		
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Usuario</th>";	
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Fecha Ingreso</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>T. Inicio</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>T. Final</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>T. Total</th>";
			$cabecera .= "</tr>";
			
			$i=0;
			$j=0;
			$archi="";
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1DE";
				else
					$color = "#FFFFFF";
					
				if($archi!=$row[17])
				{
					$archi=$row[17];
					$strHtml .= "<tr align='center'>";
						$strHtml .= "<th colspan='11' style='background-color:#4F81BD; color:#FFFFFF'>".'Archivador '.'Nº '.$row[17]."</th>";
					$strHtml .= "</tr>";
					$strHtml=$strHtml.$cabecera;
				}
				$strHtml .= "<tr>";
					$strHtml .= "<td style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[0].'-'.$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[8].'-'.$row[9].'-'.$row[7]."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[10]."</td>";		
					$strHtml .= "<td style='background-color:".$color."'>".$row[15]."</td>";	
					$strHtml .= "<td style='background-color:".$color."'>".$row[16]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[18]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[19]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[20]."</td>";
				$strHtml .= "</tr>";
				
			}			
			
						
			$strHtml .= "</table>";
			
			$strHtml .= "<p>";
			
			$strHtml .= "<table>";
			$strHtml .= "<tr>";
							$strHtml .= "<td colspan='11' align='right' >".'Impreso Por: '.$username."</td>";							
					$strHtml .= "</tr>";
			$strHtml .= "<tr>";							
							$strHtml .= "<td colspan='11' align='right' >".'Fecha Impresión: '.date('d/m/Y')."</td>";
					$strHtml .= "</tr>";
			$strHtml .= "</table>";
			
					
			
		}
				
		$this->view->data = $strHtml;
		
		
	}
	
}