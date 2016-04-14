<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneraltopdeudorController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */

	public function indextopdeudoripAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "TOP DEUDORES";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		$evt[] = array('#btnExportartopdeudor', "click", "exportarTopDeudaIp();" );
		$fn->PintarEvento($evt);
	}
	
	public function reporte05Action()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_combo    = $data_query[0];
		$data_concepto = $data_query[1];
		
	
		$parametros[] = array('@BUSC','5');     
		$parametros[] = array('@TOP_BUSC1',$data_combo);
		$parametros[] = array('@TIPO',$data_concepto);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
	//DATA IMPUESTO PREDIAL
	IF($data_concepto=="IP")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=19><center><b> TOP DEUDIRES PREDIAL</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						<th width="30%"><center><b>DIR. FISCA</b></center></th>
						<th width="15%"><center><b>DISTRITO</b></center></th>
						<th width="15%"><center><b>SECTOR</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						<th width="10%"><center><b>IP_1996_2008</b></center></th>
						<th width="10%"><center><b>IP_2009</b></center></th>
						<th width="10%"><center><b>IP_2010</b></center></th>
						<th width="10%"><center><b>IP_2011</b></center></th>
						<th width="10%"><center><b>IP_2012</b></center></th>
						<th width="10%"><center><b>IP_2013</b></center></th>
						<th width="10%"><center><b>IP_2014</b></center></th>
						<th width="10%"><center><b>IP_2015_I</b></center></th>
						<th width="10%"><center><b>IP_2015_II</b></center></th>
						<th width="10%"><center><b>IP_2015_III</b></center></th>
						<th width="10%"><center><b>IP_2015_IV</b></center></th>
						<th width="10%"><center><b>TOT_IP</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$color2 = "#EBF1DE";

				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][18]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=10%><b>".$rows[$i][7]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td align=left width=10%><b>".$rows[$i][12]."</b></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][13]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][14]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][15]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][16]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][17]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}

	//DATA ARBITRIOS
	IF($data_concepto=="ARB")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=28><center><b> TOP DEUDIRES ARBITRIOS</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						
						<th width="15%"><center><b>ARB LP ANT</b></center></th>
						<th width="15%"><center><b>ARB PJ ANT</b></center></th>
						<th width="15%"><center><b>ARB SS ANT</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2009</b></center></th>
						<th width="15%"><center><b>ARB PJ 2009</b></center></th>
						<th width="15%"><center><b>ARB SS 2009</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2010</b></center></th>
						<th width="15%"><center><b>ARB PJ 2010</b></center></th>
						<th width="15%"><center><b>ARB SS 2010</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2011</b></center></th>
						<th width="15%"><center><b>ARB PJ 2011</b></center></th>
						<th width="15%"><center><b>ARB SS 2011</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2012</b></center></th>
						<th width="15%"><center><b>ARB PJ 2012</b></center></th>
						<th width="15%"><center><b>ARB SS 2012</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2013</b></center></th>
						<th width="15%"><center><b>ARB PJ 2013</b></center></th>
						<th width="15%"><center><b>ARB SS 2013</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2014</b></center></th>
						<th width="15%"><center><b>ARB PJ 2014</b></center></th>
						<th width="15%"><center><b>ARB SS 2014</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2015</b></center></th>
						<th width="15%"><center><b>ARB PJ 2015</b></center></th>
						<th width="15%"><center><b>ARB SS 2015</b></center></th>
						
						<th width="15%"><center><b>TOTAL_ARB</b></center></th>
						
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$color1 = "#CEF6F5";
				$color1 = "#EBF1DE";				
						
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][27]."</b></center></td>";
				$cad1.=	"<td width=5% ><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25% ><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color1."'><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][7]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][12]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][13]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][14]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color1."'><b>".$rows[$i][15]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][16]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][17]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][18]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][19]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][20]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][21]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][22]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][23]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][24]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][25]."</b></center></td>";
				$cad1.=	"<td width=15% ><center><b>".$rows[$i][26]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	//DATA FRACCIONAMINETOS
	
	IF($data_concepto=="FRACC")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=16><center><b> TOP DEUDIRES FRACCIONAMIENTO</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						<th width="30%"><center><b>DIR. FISCA</b></center></th>
						<th width="15%"><center><b>DISTRITO</b></center></th>
						<th width="15%"><center><b>SECTOR</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						<th width="10%"><center><b>FRACCIONAMIENTO</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=30%><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	//FRACCIONAMIENTO ANTERIOR
	IF($data_concepto=="FRAC_ANT")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=16><center><b> TOP DEUDIRES FRACC ANTERIOR</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						<th width="30%"><center><b>DIR. FISCA</b></center></th>
						<th width="15%"><center><b>DISTRITO</b></center></th>
						<th width="15%"><center><b>SECTOR</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						<th width="10%"><center><b>FRACC ANTERIOR</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=30%><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
			
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
	
	
	public function exportarrpt10Action()
	{
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$limita    =  $_REQUEST['limita'];
		$concepto =  $_REQUEST['concepto'];
		
	
		$parametros[] = array('@BUSC','5');     
		$parametros[] = array('@TOP_BUSC1',$limita);
		$parametros[] = array('@TIPO',$concepto);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
	//DATA IMPUESTO PREDIAL
	IF($concepto=="IP")
	{
			$color2 = "#81BEF7";
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=16><center><b> TOP DEUDIRES PREDIAL</b></center></th>";
			$parte3="</tr>
					<tr>
						<th style='background-color:".$color2."' width='2%''><center><b>NRO</b></center></th>
						<th style='background-color:".$color2."' width='5%'><center><b>CODIGO</b></center></th>
						<th style='background-color:".$color2."' width='25%'><center><b>NOMBRE</b></center></th>
						<th style='background-color:".$color2."' width='30%'><center><b>DIR. FISCA</b></center></th>
						<th style='background-color:".$color2."' width='15%'><center><b>DISTRITO</b></center></th>
						<th style='background-color:".$color2."' width='15%'><center><b>SECTOR</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>TOT. AUTOAVALUO</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_1996_2008</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2009</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2010</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2011</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2012</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2013</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2014</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2015_I</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2015_II</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2015_III</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>IP_2015_IV</b></center></th>
						<th style='background-color:".$color2."' width='10%'><center><b>TOT_IP</b></center></th>
					</tr>";
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$color2 = "#EBF1DE";

				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][18]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td align=left width=30%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=10%><b>".$rows[$i][7]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td align=left width=10%><b>".$rows[$i][12]."</b></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][13]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][14]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][15]."</b></center></td>";
				$cad1.=	"<td style='background-color:".$color2."' width=10%><center><b>".$rows[$i][16]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][17]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}

	//DATA ARBITRIOS
	IF($concepto=="ARB")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=28><center><b> TOP DEUDIRES ARBITRIOS</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						
						<th width="15%"><center><b>ARB LP ANT</b></center></th>
						<th width="15%"><center><b>ARB PJ ANT</b></center></th>
						<th width="15%"><center><b>ARB SS ANT</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2009</b></center></th>
						<th width="15%"><center><b>ARB PJ 2009</b></center></th>
						<th width="15%"><center><b>ARB SS 2009</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2010</b></center></th>
						<th width="15%"><center><b>ARB PJ 2010</b></center></th>
						<th width="15%"><center><b>ARB SS 2010</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2011</b></center></th>
						<th width="15%"><center><b>ARB PJ 2011</b></center></th>
						<th width="15%"><center><b>ARB SS 2011</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2012</b></center></th>
						<th width="15%"><center><b>ARB PJ 2012</b></center></th>
						<th width="15%"><center><b>ARB SS 2012</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2013</b></center></th>
						<th width="15%"><center><b>ARB PJ 2013</b></center></th>
						<th width="15%"><center><b>ARB SS 2013</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2014</b></center></th>
						<th width="15%"><center><b>ARB PJ 2014</b></center></th>
						<th width="15%"><center><b>ARB SS 2014</b></center></th>
						
						<th width="15%"><center><b>ARB LP 2015</b></center></th>
						<th width="15%"><center><b>ARB PJ 2015</b></center></th>
						<th width="15%"><center><b>ARB SS 2015</b></center></th>
						
						<th width="15%"><center><b>TOTAL_ARB</b></center></th>
						
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$color1 = "#CEF6F5";
				$color1 = "#EBF1DE";				
						
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][27]."</b></center></td>";
				$cad1.=	"<td width=5% ><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25% ><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color1."'><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][7]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][8]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][9]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][10]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][11]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][12]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][13]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][14]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color1."'><b>".$rows[$i][15]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][16]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][17]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][18]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][19]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][20]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][21]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color1."'><center><b>".$rows[$i][22]."</b></center></td>";
				$cad1.=	"<td align=left width=15% style='background-color:".$color2."'><b>".$rows[$i][23]."</b></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][24]."</b></center></td>";
				$cad1.=	"<td width=15% style='background-color:".$color2."'><center><b>".$rows[$i][25]."</b></center></td>";
				$cad1.=	"<td width=15% ><center><b>".$rows[$i][26]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	//DATA FRACCIONAMINETOS
	
	IF($concepto=="FRACC")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=16><center><b> TOP DEUDIRES FRACCIONAMIENTO</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						<th width="30%"><center><b>DIR. FISCA</b></center></th>
						<th width="15%"><center><b>DISTRITO</b></center></th>
						<th width="15%"><center><b>SECTOR</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						<th width="10%"><center><b>FRACCIONAMIENTO</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=30%><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
	
	//FRACCIONAMIENTO ANTERIOR
	IF($concepto=="FRAC_ANT")
	{
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="85%">
					<tr>';
			$parte2="<th colspan=16><center><b> TOP DEUDIRES FRACC ANTERIOR</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="2%"><center><b>NRO</b></center></th>
						<th width="5%"><center><b>CODIGO</b></center></th>
						<th width="25%"><center><b>NOMBRE</b></center></th>
						<th width="30%"><center><b>DIR. FISCA</b></center></th>
						<th width="15%"><center><b>DISTRITO</b></center></th>
						<th width="15%"><center><b>SECTOR</b></center></th>
						<th width="10%"><center><b>TOT. AUTOAVALUO</b></center></th>
						<th width="10%"><center><b>FRACC ANTERIOR</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=2%><center><b>".$rows[$i][7]."</b></center></td>";
				$cad1.=	"<td width=5%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=25%><b>".utf8_encode($rows[$i][1])."</b></td>";
				$cad1.=	"<td width=30%><center><b>".utf8_encode($rows[$i][2])."</b></center></td>";
				$cad1.=	"<td width=15%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.=	"<td align=left width=15%><b>".$rows[$i][4]."</b></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][5]."</b></center></td>";
				$cad1.=	"<td width=10%><center><b>".$rows[$i][6]."</b></center></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		
	}
			
		}
	
}

