<?php

class RptgeneralcontController extends Zend_Controller_Action
{

    public function init()
    {
			
    }

    public function indexcontableAction()
    {

    	$fn = new Libreria_Pintar ();
		$cn = new Model_DbDatos_Datos();

    	$nombrestore  = 'dbo.sp_getfecha';
		$fecharow = $cn->ejec_store_procedura_sql($nombrestore, null);
		
    	$nombrestore  = 'Calculo.sp_ListaCombo';
		$parametros[] = array('@busc','7');
		
		$datacajas = $cn->ejec_store_procedura_sql($nombrestore, $parametros);
		
        for($i=0; $i < count($datacajas); $i++){
            $cajas[$i+1] = array(trim($datacajas[$i][0]),trim($datacajas[$i][1]));
        }
		$val[] = array("#cmbcajas",$fn->ContenidoCombo2($cajas,'',''),"html");
		
		$this->view->tesodesde = $fecharow[0][0];
		$this->view->tesohasta = $fecharow[0][0];
		
		$evt[] = array('#tesodesde',"datepicker","");
		$evt[] = array('#tesohasta',"datepicker","");
		
		$evt[] = array("#btnbuscarecibos", "click", "buscarRecibos();");
		$evt[] = array("#btnprintRpt", "click", "generarpdf();");
		$evt[] = array("#btnprintPartidas", "click", "generarpdfParidas();");
		$mask[] = array("txtArancel");
		
		$fn->PintarValor($val);
		$fn->PintarEvento($evt);
		$fn->CampoDinero($mask);
    }
    
	public function saldosmensualAction(){
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$this->view->title = "PREDIOS X SECTOR";
		
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
		
		//COMBO SECTOR
		$nombrestore="Rentas.Rpt_Rentas_General";
	    $arraydatos[]=array('@BUSC',8);
	    $arraydatos[]=array('@SECTOR','');
	    $dato=$cn->ejec_store_procedura_sql($nombrestore,$arraydatos);
	    $cb_usopredio='<option value="">[Seleccione]</option>';
	    for($i=0;$i<count($dato);$i++){
				$cb_usopredio.='<option value="'.$dato[$i][0].'">'.$dato[$i][1].'</option>';	
		}
		$this->view->cb_uso=$cb_usopredio;

		$evt[] = array('#btnGrapPrediosXSector',"click",'GrapIngresoxGerencia()');
		$evt[] = array('#btnExportPrediosXSector', "click", "exportarPredxsector();" );
		
		$fn->PintarEvento($evt);
	
	}

		
}