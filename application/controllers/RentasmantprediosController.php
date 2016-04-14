<?php

class RentasmantprediosController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {    		
    	
    	$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
		$fn = new Libreria_Pintar();
		
		//Eventos Predios
		$evt[] = array('#contentBox',"tabs","");
		$evt[] = array('#contentTabsLeft',"tabs","");
		$evt[] = array('#cmbUso',"change","ShowTabPisos(this.value);");
		
		//Eventos PU
		$evt[] = array('#txtFecAdqui',"datepicker","");
		$evt[] = array('#txtFecTrans',"datepicker","");
		$evt[] = array('#btnCopiar',"button","");
		
		//Eventos Pisos
		$evt[] = array('#btnAddDetPisos',"button","");
		$evt[] = array('#btnAddDetPisos',"click","addRowPisos();");
		
		for($i=1;$i<=5;$i++)
			$val[] = array('#btnAddDetPisos',"click","trigger");
		
		//Eventos Instalaciones	
		$evt[] = array('#btnAddDetInstal',"button","");
		$evt[] = array('#btnAddDetInstal',"click","addRowInstal();");
		
		for($i=1;$i<=10;$i++)
			$val[] = array('#btnAddDetInstal',"click","trigger");
			
		$fn->PintarEvento($evt);
		$fn->PintarValor($val);
		
    }
    	
}

