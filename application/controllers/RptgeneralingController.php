<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneralingController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	public function indexingresectorAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "INGRESOS X SECTOR ARBITRIOS";
		
		$fn = new Libreria_Pintar();
		
		$cn   	= new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		$ar = new Libreria_ArraysFunctions();
		$evt[] = array('#contentBox',"tabs","");
		
		//MUESTRA SECTOR
				$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',8);
				$arraydatos[]=array('@SECTOR','');
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_sector='<option value="">--Seleccione--</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_sector.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
				}
		
				$this->view->cb_sector=$cb_sector;
				
			//COMBO ANIO
		
				$bus_anno="";
				for($i=2014;$i<=date('Y');$i++){
				$bus_anno.="<option value='".$i."'";
					if($i==date('Y'))
					{
						$bus_anno.=" selected ";
					}
				$bus_anno.=">".$i."</option>";
				}
				$this->view->cb_anno=$bus_anno;
		
		$evt[] = array('#chkTotObligacion',"click","marcaChecks(this,'anio[]')");
		$evt[] = array('#chkTotMeses',"click","marcaChecks(this,'mes[]')");
		$evt[] = array('#chkTotSector',"click","marcaChecks(this,'sector[]')");
		$evt[] = array('#chkTotArbitrio',"click","marcaChecks(this,'chkarbitrio[]')");
	
		$evt[] = array('#btnGrafica_ingre_X_Sector',"click",'ReporteIngresoGraph()');
	
		$fn->PintarEvento($evt);
		
	}
	
	public function muestrameses2Action()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$anio=$this->_request->getParam('anio');
			
			$nombrestoremeses="Rentas.Rpt_Rentas_General 15,@ANNO='$anio'";

			$rowmeses = $cn->ejec_store_procedura_sql($nombrestoremeses, null);
			$meses='';
			for ($i=0;$i<count($rowmeses);$i++){
				$meses.='<tr> <td><label style="font-size:10px;"><input type="checkbox" name="mes[]" id="mes[]" value="'.$var.$rowmeses[$i][0].$var.'" style="float:left;"> <div style="float:left; margin:0 0 5px 5px; width:200px">'.$rowmeses[$i][1].'</div></label></td>  </tr>';
			}
			$meses=$mes='<table width="100%">'.$meses."</table>";

			echo $meses;
		}
		
	}
	
	public function muestraobligacionAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
				
			for ($i=1992;$i<=date('Y');$i++){
				$anios.='<tr> <td><label><input type="checkbox" name="anio[]" id="anio[]" value='.$var.$i.$var.'> '.$i.''.'</label></td>  </tr>';
			}
			$anios=$anio='<table width="100%">'.$anios."</table>";

			echo $anios;
		}
	}
	
	public function muestrasectorAction()
	{
		$this->_helper->getHelper('ajaxContext')->initContext();
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
		$var='*';
		if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$nombrestorsector="[Rentas].[sp_Mant_Vias] 7";

			$rowsector = $cn->ejec_store_procedura_sql($nombrestorsector, null);
			$sectores='';
			for ($i=0;$i<count($rowsector);$i++){
				$sectores.='<tr> <td><label style="font-size:10px;"><input type="checkbox" name="sector[]" id="sector[]" value="'.$var.$rowsector[$i][0].$var.'" style="float:left;"> <div style="float:left; margin:0 0 5px 5px; width:200px">'.utf8_encode($rowsector[$i][1]).'</div></label></td>  </tr>';
			}
			$sectores=$sector='<table width="100%">'.$sectores."</table>";

			echo $sectores;
		}
	}
	
	public function reporte06Action()
	{
		$cn   	= new Model_DbDatos_Datos();
		
		$str_anio='';
		$str_obligacion='';
		$str_mes='';
		$str_sector='';
		$str_arbitrio='';
		
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$str_anio  		 = $data_query[0];
		$data_obligacion = $data_query[1];
		$data_mes   	 = $data_query[2];
		$data_sector	 = $data_query[3];
		$data_arbitrio	 = $data_query[4];
	
		//anios obligacion
		for($i=0; $i < count($data_obligacion); $i++){
            $str_obligacion .=$data_obligacion[$i].',';
        }
		
		//meses
		for($i=0; $i < count($data_mes); $i++){
            $str_mes .=$data_mes[$i].',';
        }
		
		//sectores
		for($i=0; $i < count($data_sector); $i++){
            $str_sector .=$data_sector[$i].',';
        }
		
		//tipo recibos
		for($i=0; $i < count($data_arbitrio); $i++){
            $str_arbitrio .=$data_arbitrio[$i].',';
        }
	
	
		$parametros[] = array('@BUSC','6');     
		$parametros[] = array('@ANNO',$str_anio);
		$parametros[] = array('@str_obligacion',str_replace(',','',$str_obligacion));
		$parametros[] = array('@str_mes',str_replace(',','',$str_mes));
		$parametros[] = array('@str_sector',str_replace(',','',$str_sector));
		$parametros[] = array('@str_arbitrio',str_replace(',','',$str_arbitrio));
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th colspan=8><center><b>INGRESOS DEL AÑO ".$str_anio."</b></center></th>";
		$parte3='</tr>
				<tr>
					<th width="10%"><center><b>MES</b></center></th>
					<th width="10%"><center><b>ANIO OBL.</b></center></th>
					<th width="30%"><center><b>SECTOR</b></center></th>
    				<th width="10%"><center><b>RR</b></center></th>
    				<th width="10%"><center><b>BV</b></center></th>
					<th width="10%"><center><b>PJ</b></center></th>
					<th width="10%"><center><b>SS</b></center></th>
					<th width="20%"><center><b>VER</b></center></th>
					
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;
		
		$tipo=$rows[0][0];
		
		for($i = 0; $i < count ( $rows ); $i ++) 
        {
			$tipo1=$rows[$i][0];
				
			if($tipo!=$tipo1)
			{
				$tipo=$tipo1;
				
				$cad1.=$cabecera;
				$cad1.='<br><br>';
			}
			
			$cad1.='<tr>';
			$cad1.=	"<td width=10%><center><b>".utf8_encode($rows[$i][1])."</b></center></td>";
			$cad1.=	"<td width=10%><center><b>".utf8_encode($rows[$i][3])."</b></center></td>";
			$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][5])."</b></td>";
			$cad1.=	"<td align=right width=10%><b>".$rows[$i][6]."</b></td>";
			$cad1.=	"<td align=right width=10%><b>".$rows[$i][7]."</b></td>";
			$cad1.=	"<td align=right width=10%><b>".$rows[$i][8]."</b></td>";
			$cad1.=	"<td align=right width=10%><b>".$rows[$i][9]."</b></td>";
			/*$cad1.=	"<td align='center' width=20%><img src='img/lupa.png' title='Exporta Predios' ></td>";*/
			$cad1.="<td align='right'><img src='img/lupa.png' title='Ver Detallado' onclick='Ver_Detallado(".$rows[$i][2].",".$rows[$i][3].",".$rows[$i][4].",".$rows[$i][0].")'></td></tr>";
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;
	
	}

	public function detalleingAction()
	{	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		$fn = new Libreria_Pintar();
		
		$anio_pago = $this->_request->getParam('anio_pago','');
		$anio_obl = $this->_request->getParam('anio_obl',''); 
		$id_zona = $this->_request->getParam('id_zona',''); 
		$id_mes = $this->_request->getParam('id_mes',''); 
	
		$documento = "DETALLE DE INGRESOS SEGUN VIAS CONTENIDAS ";
		$val[] = array('#lblDetalle',$documento,'html');
		
		//Datos Cabecera
		$parametros[] = array('@busc',18);
		$parametros[]=array('@id_zona',$id_zona);
		$parametros[]=array('@id_mes',$id_mes);	
		$rowCabecra = $cn->ejec_store_procedura_sql('Rentas.Rpt_Rentas_General', $parametros);
		
		
		$val[] = array('#txtAnioPago',$anio_pago,"val");
		$val[] = array('#txtAnioObl',$anio_obl,"val");
		$val[] = array('#txtIdZona',$id_zona,"val");
		$val[] = array('#txtIdMes',$id_mes,"val");
		
		$val[] = array('#lblAnio_Ingreso',$anio_pago,'html');
		$val[] = array('#lblAnio_Obligacion',$anio_obl,'html');
		$val[] = array('#lblSector',$rowCabecra[0][0],'html');
		$val[] = array('#lblMes',$rowCabecra[0][1],'html');
		
		//Data Combo Via
		$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',16);
				$arraydatos[]=array('@ANNO',$anio_pago);
				$arraydatos[]=array('@anio_obligacion',$anio_obl);
				$arraydatos[]=array('@id_zona',$id_zona);
				$arraydatos[]=array('@id_mes',$id_mes);
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_Vias='<option value="">--Todos--</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_Vias.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][3]).'</option>';
				}
		
				$this->view->cb_Vias=$cb_Vias;

		//Cargamos la grilla
		
		
		
		$fn->PintarValor($val);
	}
	
	public function rptdetalladoviaAction()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$Anio_Pago   = $data_query[0];
		$Anio_Obl	 = $data_query[1];
		$Id_Zona     = $data_query[2];
		$Id_Mes      = $data_query[3];
		$cod_via     = $data_query[4];
		
		
		$parametros[] = array('@BUSC','17');
		$parametros[] = array('@ANNO',$Anio_Pago);
		$parametros[] = array('@anio_obligacion',$Anio_Obl);
		$parametros[] = array('@id_zona',$Id_Zona);
		$parametros[] = array('@id_mes',$Id_Mes);
		$parametros[] = array('@cod_via',$cod_via);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
			$ini='<div align=center style="overflow:auto;height: 90%;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="90%">
					<tr>';
			$parte2="<th colspan=6><center><b>LISTADO DE INGRESOS POR VIA</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>ABR. VIA</b></center></th>
						<th width="40%"><center><b>NOMBRE</b></center></th>
						<th width="10%"><center><b>RR</b></center></th>
						<th width="10%"><center><b>BV</b></center></th>
						<th width="10%"><center><b>PJ</b></center></th>
						<th width="10%"><center><b>SS</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][2]."</b></center></td>";
				$cad1.=	"<td align=left width=40%><b>".utf8_encode($rows[$i][3])."</b></td>";
				$cad1.=	"<td align=right width=10%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td align=right width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td align=right width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=right width=10%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	public function graprptingAction()
	{ 	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		//COMBO ANIO
		$bus_anno="";
				for($i=2014;$i<=date('Y');$i++){
				$bus_anno.="<option value='".$i."'";
					if($i==date('Y'))
					{
						$bus_anno.=" selected ";
					}
				$bus_anno.=">".$i."</option>";
				}
				$this->view->cmbAnio=$bus_anno;
	
	}
	
	public function datagraphingAction()
	{	
			$cn = new Model_DbDatos_Datos();
			$anio=$this->_request->getParam('anio','');
			$anio='2015';
			unset($parametros);
			$parametros[]=array('@BUSC',19);
			$parametros[]=array("@ANNO",$anio);
			
			$query = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);

			$category = array();
			$category['name'] = 'Month';

			$series1 = array();
			$series1['name'] = 'RR';

			$series2 = array();
			$series2['name'] = 'BV';

			$series3 = array();
			$series3['name'] = 'PJ';
			
			$series4 = array();
			$series4['name'] = 'SS';
			
			if(count($rows))
			{
				foreach($query AS $row){
					$category['data'][] = $row[0];
					$series1['data'][] = $row[1];
					$series2['data'][] = $row[2];
					$series3['data'][] = $row[3];
					$series4['data'][] = $row[4];
				}
			}
/*
			while($r = mssql_fetch_array($query)) {
				$category['data'][] = $r['month'];
				$series1['data'][] = $r['RR'];
				$series2['data'][] = $r['BV'];
				$series3['data'][] = $r['PJ'];
				$series4['data'][] = $r['SS']; 
			}
*/
			$result = array();
			array_push($result,$category);
			array_push($result,$series1);
			array_push($result,$series2);
			array_push($result,$series3);
			array_push($result,$series4);

			$this->view->data = json_encode($result);
		
		
	}
		
	
	public function exportaAction() 
	{
	
		$cn = new Model_DbDatos_Datos();
		$ar = new Libreria_ArraysFunctions();
		
		$suministro = $_REQUEST['suministro'];
		$ubicacion = $_REQUEST['ubicacion'];
		$cencos = $_REQUEST['cencos'];		
		$area = $_REQUEST['area'];
		
		if(!empty($_REQUEST['req_fech1']))
			$req_fech1 = $_REQUEST['req_fech1'];
		else
			$req_fech1 = "01/01/".date("Y");
			
		if(!empty($_REQUEST['req_fech2']))	
			$req_fech2 = $_REQUEST['req_fech2'];
		else
			$req_fech2 = "31/12/".date("Y");
			
		
		$arraydatos[] = array('@busc',8);
		$arraydatos[]=array("@gastos_desc", $suministro);
		$arraydatos[]=array("@ubicacion", $ubicacion);
		$arraydatos[]=array("@cencos_num", $cencos);
		$arraydatos[]=array("@area", $area);
		$arraydatos[] = array('@fecha_ini',$req_fech1);
		$arraydatos[] = array('@fecha_fin',$req_fech2);
		
		$rows = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $arraydatos);
		
		if(count($rows)){
			$strHtml = "<table border='1'>";	

			$strHtml .= "<tr width='250px'>";
				$strHtml .= "<td colspan='9' align=center style='background-color:white ; color:black; text-decoration: underline;  font-size:150%'>GASTOS DE ENERGIA ELECTRICA</td>";			
			$strHtml .= "</tr>";
			
			$strHtml .= "<tr>";
				$strHtml .= "<th colspan='9' ></th>";			
			$strHtml .= "</tr>";
			
			$strHtml .= "<tr>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Suministro</th>";
				$strHtml .= "<th width='250' style='background-color:#4F81BD; color:#FFFFFF'>Ubi. Camara</th>";	
				$strHtml .= "<th width='500' style='background-color:#4F81BD; color:#FFFFFF'>Dependencia</th>";	
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>CENCOS</th>";					
				$strHtml .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Meta</th>";
				$strHtml .= "<th width='70' style='background-color:#4F81BD; color:#FFFFFF'>Total</th>";					
				$strHtml .= "<th width='120' align='center' style='background-color:#4F81BD; color:#FFFFFF'>Fecha</th>";			
			$strHtml .= "</tr>";
			
			$i=0;
			$j=0;
			$total=0;
			$puntero=0;
			$new_archi='';
			$archi="";
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1AE";
				else
					$color = "#F8FBEF";
					
				if($archi!=$row[3])
				{	
					if ($puntero>0)
					{	
						unset($array);
						$array[] = array('@busc',9);
						$array[]=array("@nom_area", utf8_encode($archi));
						$fila = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $array);
						
						$strHtml .= "<tr align='center'>";
							$strHtml .= "<td colspan='7'  style='background-color:white; color:black'>".'Monto :'.' '."</th>";
							$strHtml .= "<td  align='right' style='background-color:white; color:black'>".$fila[0][0]."</th>";
						$strHtml .= "</tr>";
					}
				
					$archi=$row[3];
					$strHtml .= "<tr align='center'>";
						$strHtml .= "<th colspan='9' style='background-color:#4F81BD; color:#FFFFFF'>".'Area '.' '.$row[3]."</th>";
					$strHtml .= "</tr>";
					
					$new_archi=$archi;
				}
				$strHtml .= "<tr>";
					$strHtml .= "<td align='center' style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[0]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";				
					//$strHtml .= "<td style='mso-number-format:'#0.00';'".$color."'>".$row[4]."</td>";		
					$strHtml .= "<td style='background-color:".$color."'>".$row[5]."</td>";	
					$strHtml .= "<td align='right' style='background-color:".$color."'>".$row[6]."</td>";		
					$strHtml .= "<td align='center'style='background-color:".$color."'>".$row[7]."</td>";						
				$strHtml .= "</tr>";
				
				$puntero++;
				$registros++;	
				
			}
			
			unset($array);
			$array[] = array('@busc',9);
			$array[]=array("@nom_area", utf8_encode($new_archi));
			$fila = $cn->ejec_store_procedura_sql('dbo.[MantEdelnor]', $array);
						
			$strHtml .= "<tr align='center'>";
				$strHtml .= "<td colspan='7' style='background-color:white; color:black'>".'Monto :'.' '."</th>";
				$strHtml .= "<td align='right' style='background-color:white; color:black'>".$fila[0][0]."</th>";
						
			$strHtml .= "</tr>";
			
			$strHtml .= "</table>";
		}
		
		$this->view->data = $strHtml;
	}
	
}

