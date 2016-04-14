<?php

/**
 * MantpobladoController
 * 
 * @author
 * @version 
 */

require_once 'Zend/Controller/Action.php';

class RptgeneralcpsController extends Zend_Controller_Action {
	/**
	 * The default action - show the home page
	 */
	
	public function indexcontrixsectorAction()
	{
	
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "CONTRIBUYENTES POR SECTOR";
		
		$fn = new Libreria_Pintar();
		$evt[] = array('#contentBox',"tabs","");
		
		//COMBO A?O
		$bus_anno="";
		for($i=1992;$i<=date('Y');$i++){
		$bus_anno.="<option value='".$i."'";
			if($i==date('Y'))
			{
				$bus_anno.=" selected ";
			}
		$bus_anno.=">".$i."</option>";
		}
		$this->view->cb_anno=$bus_anno;
		
		$cn=new Model_DbDatos_Datos();
		
		//COMBO DISTRITO
		$nombrestore="Contenedor.SP_TblDistrito";
	    $arraydatos[]=array('@msquery',1);
	    $dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
	    $cb_sectorpredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato);$i++){
				$cb_sectorpredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';	
		}
		$this->view->cb_distrito=$cb_sectorpredio;
	    $evt[] = array('#btnExportContribuyentesXSector', "click", "exportarContrixsector();" );
		$fn->PintarEvento($evt);
	
	}
	
	public function muestrasectorAction()
    {
    	$this->_helper->getHelper('ajaxContext')->initContext();
	    $this->_helper->viewRenderer->setNoRender();
	    $this->_helper->layout->disableLayout();
	    
	    if($this->getRequest()->isXmlHttpRequest()){
    		
			$cn = new Model_DbDatos_Datos();
			
			$id_distrito = $this->_request->getPost('id_distrito');
			
			if ($id_distrito=="012")
			{
				$combostore1 ='Rentas.Rpt_Rentas_General';
				$arraydatos[]=array('@BUSC',8);
				$arraydatos[]=array('@SECTOR','');
				$rows1 = $cn->ejec_store_procedura_sql($combostore1,$arraydatos);
				
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
				for ($i=0;$i<count($rows1);$i++){
					$cb_Tipocontri.='<option value="'.$rows1[$i][0].'" >'.utf8_encode($rows1[$i][1]).'</option>';
				}
			}
			else
			{
				$cb_Tipocontri='<option value="">[Seleccione]</option>';
			}
			
		
			echo $cb_Tipocontri;
    	}  
    }
	
	public function reporte03Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    	$json   = $this->_request->getPost('json');		
		$data_query = json_decode($json);
		
		$data_codigo   = $data_query[0];
		$data_anio	   = $data_query[1];
		$data_distrito = $data_query[2];
		$data_sector   = $data_query[3];
	
	
		$parametros[] = array('@BUSC','3');
		$parametros[] = array('@CODIGO',$data_codigo);
		$parametros[] = array('@ANNO',$data_anio);
		$parametros[] = array('@DISTRITO',$data_distrito);
		$parametros[] = array('@SECTOR',$data_sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
		if($data_distrito=="012")
		{
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=5><center><b>CONTRIBUYENTES DE INDEPENDENCIA ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>ID</b></center></th>
						<th width="30%"><center><b>DISTRITO</b></center></th>
						<th width="25%"><center><b>SECTOR</b></center></th>
						<th width="25%"><center><b># CONTRIBUYENTES</b></center></th>
						<th width="10%"><center><b>OPCION</b></center></th>
					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=30%><b>".$rows[$i][2]."</b></td>";
				$cad1.=	"<td align=left width=35%><b>".utf8_encode($rows[$i][4])."</b></td>";
				$cad1.=	"<td width=25%><center><b>".$rows[$i][5]."</b></center></td>";
				//$cad1.="<td align='center'><img src='img/export.gif' title='Exporta Contribuyentes' ></td>";
				
				$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Contribuyentes' onclick='imprimeReporteContriSec(".'"'.$rows[$i][1].'"'.",".'"'.$rows[$i][3].'"'.",".'"'.$rows[$i][7].'"'.  ")'></td>";
				
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		}
		else
		{
			$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
			$cad1='';
			$parte1='<table border="1" class="hovertable" width="850PX">
					<tr>';
			$parte2="<th colspan=4><center><b>CONTRIBUYENTES X DISTRITO ".$data_anio."</b></center></th>";
			$parte3='</tr>
					<tr>
						<th width="10%"><center><b>ID</b></center></th>
						<th width="50%"><center><b>DISTRITO</b></center></th>
						<th width="20%"><center><b># CONTRIBUYENTES</b></center></th>
						<th width="20%"><center><b>OPCION</b></center></th>

					</tr>';
			$cabecera=$parte1.$parte2.$parte3;	
			
			$cad1.=$cabecera;
			
			for($i = 0; $i < count ( $rows ); $i ++) 
			{
				$cad1.='<tr>';
				$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
				$cad1.=	"<td align=left width=70%><b>".utf8_encode($rows[$i][2])."</b></td>";
				$cad1.=	"<td width=20%><center><b>".$rows[$i][3]."</b></center></td>";
				$cad1.= "<td align='center'><img src='img/export.gif' title='Exporta Contribuyentes' onclick='imprimeReporteContriDist(".$rows[$i][1].",".'"'.$rows[$i][5].'"'.")'></td>";
				$cad1.='</tr>';
			}
			echo $ini.$cad1;
		}
		
	}
	
	public function reportedetcontriAction()
	{
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();		
		
		
		$id_dist = $this->_request->getParam('id_dist','');
		$anio = $this->_request->getParam('anio',''); 
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','11');
		$parametros[] = array('@ANNO',$anio);
		$parametros[] = array('@ID_DIST',$id_dist);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
		$val[] = array('#txtId_dist',$id_dist,'val');
		$val[] = array('#txtAnio',$anio,'val');
		
		$evt[] = array('#btnExpDetCPSExcel', "click", "exportarContriDetalle();" );
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		$ini='<div align=center style="overflow:auto;height: 90%;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="90%">
				<tr>';
			
		$parte2="<th colspan=7><center><b><H1>CONTRIBUYENTES DEL DISTRITO ==>".$rows[0][4]."</H1></b></center></th>";
		$parte3='</tr>
				<tr>
					<th><center><b>NRO</b></center></th>
					<th><center><b>CODIGO</b></center></th>
					<th><center><b>NOMBRE</b></center></th>
    				<th><center><b>DIR. FISCA</b></center></th>
    				<th><center><b>B_IMPONIBLE</b></center></th>
					<th><center><b>B_EXONERADA</b><center></th>
					<th><center><b>B_AFECTA</b></center></th>
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;
		
		$cad1.=$cabecera;
		
		if(count($rows))
		{
			foreach($rows as $row){
				
				$cad1.='<tr>';
				$cad1.=	"<td><center><b>".$row[0]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[1]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[2]."</b></center></td>";
				$cad1.=	"<td align=left><b>".$row[3]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[6]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[7]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[8]."</b></td>";				
				$cad1.='</tr>';
				
			}
		}	
		echo $ini.$cad1;

	}

	public function reportedetcontrisecAction()
	{
		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$cn = new Model_DbDatos_Datos();
		
		$fn = new Libreria_Pintar();		
		
		
		$id_dist = $this->_request->getParam('id_dist','');
		$id_sector = $this->_request->getParam('id_sector',''); 
		$id_anio = $this->_request->getParam('id_anio',''); 
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','12');
		$parametros[] = array('@ANNO',$id_anio);
		$parametros[] = array('@ID_DIST',$id_dist);
		$parametros[] = array('@id_zona',$id_sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
		
		$val[] = array('#txtId_dist',$id_dist,'val');
		$val[] = array('#txtId_sect',$id_sector,'val');
		$val[] = array('#txtAnio',$id_anio,'val');
		
		$evt[] = array('#btnExpDetCPSExcelSec', "click", "exportarContriDetalleSec();" );
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		
		$ini='<div align=center style="overflow:auto;height: 90%;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="90%">
				<tr>';
			
		$parte2="<th colspan=7><center><b><H1>CONTRIBUYENTES DEL SECTOR ==>".$rows[0][5]."</H1></b></center></th>";
		$parte3='</tr>
				<tr>
					<th><center><b>NRO</b></center></th>
					<th><center><b>CODIGO</b></center></th>
					<th><center><b>NOMBRE</b></center></th>
    				<th><center><b>DIR. FISCA</b></center></th>
    				<th><center><b>B_IMPONIBLE</b></center></th>
					<th><center><b>B_EXONERADA</b><center></th>
					<th><center><b>B_AFECTA</b></center></th>
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;
		
		$cad1.=$cabecera;
		
		if(count($rows))
		{
			foreach($rows as $row){
				
				$cad1.='<tr>';
				$cad1.=	"<td><center><b>".$row[0]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[1]."</b></center></td>";
				$cad1.=	"<td><center><b>".$row[2]."</b></center></td>";
				$cad1.=	"<td align=left><b>".$row[3]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[6]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[7]."</b></td>";
				$cad1.=	"<td align=right><b>".$row[8]."</b></td>";				
				$cad1.='</tr>';
				
			}
		}	
		echo $ini.$cad1;
		
	}
	
	
	public function exportadetcontriAction()
	{
		$cn = new Model_DbDatos_Datos();
		
		$id_dist = $this->_request->getParam('id_dist','');
		$anio = $this->_request->getParam('anio',''); 
		
		unset($arraydatos);
		$parametros[] = array('@BUSC','11');
		$parametros[] = array('@ANNO',$anio);
		$parametros[] = array('@ID_DIST',$id_dist);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
				
		$cabecera="";
		
		if(count($rows)){
			$strHtml = "<table border='1'>";					
			$cabecera .= "<tr>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Nombre</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Dir_Fisca</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_imponible</th>";				
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_exonerada</th>";		
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_afecta</th>";	
			$cabecera .= "</tr>";
			
			$strHtml=$strHtml.$cabecera;
			
			$i=0;
			$j=0;
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1DE";
				else
					$color = "#FFFFFF";
				
				$strHtml .= "<tr>";
					$strHtml .= "<td style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[6]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[7]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[8]."</td>";					
				$strHtml .= "</tr>";
				
			}			
			$strHtml .= "</table>";
			
			$this->view->data = $strHtml;		
		}
		
		
	}
	
	public function exportardetcontrisecAction()
	{
		
		$cn = new Model_DbDatos_Datos();
		
		$id_dist = $this->_request->getParam('id_dist','');
		$id_sector = $this->_request->getParam('id_sector',''); 
		$id_anio = $this->_request->getParam('id_anio','');

		unset($arraydatos);
		$parametros[] = array('@BUSC','12');
		$parametros[] = array('@ANNO',$id_anio);
		$parametros[] = array('@ID_DIST',$id_dist);
		$parametros[] = array('@id_zona',$id_sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
				
		$cabecera="";
		
		if(count($rows)){
			$strHtml = "<table border='1'>";					
			$cabecera .= "<tr>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Item</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Codigo</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Nombre</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>Dir_Fisca</th>";
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_imponible</th>";				
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_exonerada</th>";		
				$cabecera .= "<th style='background-color:#4F81BD; color:#FFFFFF'>b_afecta</th>";	
			$cabecera .= "</tr>";
			
			$strHtml=$strHtml.$cabecera;
			
			$i=0;
			$j=0;
			
			foreach($rows as $row){
				$i++;
				$j++;
				if($i%2==0)
					$color = "#EBF1DE";
				else
					$color = "#FFFFFF";
				
				$strHtml .= "<tr>";
					$strHtml .= "<td style='background-color:".$color."'>".$j."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[1]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[2]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[3]."</td>";					
					$strHtml .= "<td style='background-color:".$color."'>".$row[6]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[7]."</td>";
					$strHtml .= "<td style='background-color:".$color."'>".$row[8]."</td>";					
				$strHtml .= "</tr>";
				
			}			
			$strHtml .= "</table>";
			
			$this->view->data = $strHtml;		
		}
		
	}
	
	
	public function exportarrpt04Action()
    {
		$cn   	= new Model_DbDatos_Datos();
    
		
		$codigo =  $_REQUEST['codigo'];
		$anno   = $_REQUEST['anno'];
		$distrito = $_REQUEST['distrito'];
	    $sector = $_REQUEST['sector'];
		
		

	
	
		$parametros[] = array('@BUSC','3');
		$parametros[] = array('@CODIGO',$codigo);
		$parametros[] = array('@ANNO',$anno);
		$parametros[] = array('@DISTRITO',$distrito);
		$parametros[] = array('@SECTOR',$sector);
		
		$rows = $cn->ejec_store_procedura_sql('[Rentas].[Rpt_Rentas_General]', $parametros);
	
	
		$ini='<div align=center style="overflow:auto;height: 650px;padding:20px 0 0 0">';
		$cad1='';
		$parte1='<table border="1" class="hovertable" width="850PX">
				<tr>';
		$parte2="<th colspan=7><center><b>CANTIDAD DE Contribuyentes X Sector ".$anno."</b></center></th>";
		$parte3='</tr>
				<tr>
					<th width="10%"><center><b>Nro</b></center></th>
					<th width="40%"><center><b>Id</b></center></th>
    				<th width="10%"><center><b>Distrito</b></center></th>
    				<th width="10%"><center><b>Id_Sector</b></center></th>
					<th width="10%"><center><b>Sector</b></center></th>
					<th width="10%"><center><b>Cantidad</b></center></th>
					<th width="10%"><center><b>#Contri</b></center></th>
					
			    </tr>';
		$cabecera=$parte1.$parte2.$parte3;	
		
		$cad1.=$cabecera;
		
		$tipo='U';
		
		for($i = 0; $i < count ( $rows ); $i ++) 
        {
			/*
			$tipo1=$rows[$i][0];
				
			if($tipo!=$tipo1)
			{
				$tipo=$tipo1;
				
				$cad1.=$cabecera;
				$cad1.='<br><br>';
			}*/
			
			$cad1.='<tr>';
			$cad1.=	"<td width=10%><center><b>".$rows[$i][0]."</b></center></td>";
			$cad1.=	"<td align=left width=50%><b>".utf8_encode($rows[$i][1])."</b></td>";
			$cad1.=	"<td width=15%><center><b>".$rows[$i][2]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][3]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][4]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][5]."</b></center></td>";
			$cad1.=	"<td width=25%><center><b>".$rows[$i][6]."</b></center></td>";
			
			$cad1.='</tr>';
		}
	
		//$cad1.=$data_codigo."</br>";
		echo $ini.$cad1;

	}
	
}

