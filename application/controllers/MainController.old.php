<?php

class MainController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {	
    	$this->view->showRenderer = 1;
		
    }
	
	public function blankAction()
    {	
    	$this->view->showRenderer = 0;
		
    }
    
    public function menuAction()
    {
    	$this->_helper->layout->disableLayout();
    	
    	$cn = new Model_DbDatos_Datos();
    	$ar = new Libreria_ArraysFunctions();    	
    	
    	$parametros[] = array('@buscar',4);				
		$rows = $cn->ejec_store_procedura_sql('Acceso.sp_LogOut', $parametros);
		
		$jsonData = array();
				
		foreach($rows AS $row){
			
			unset($parametros);
			$parametros[] = array('@buscar',5);
			$parametros[] = array('@parametro',$row[0]);		
			$rows1 = $cn->ejec_store_procedura_sql('Acceso.sp_LogOut', $parametros);
			
						
				$mnuChildrens = array();
				
				if(count($rows1))
				{
					foreach($rows1 AS $row1){
						
						if(strlen($row1[3])>0)
							$url=$row1[3];
						else
							$url='main/blank';
							
						$childs = array(
							id=>$row1[0],
							//iconCls=>'aaa', //icono del nodo
							hrefTarget=>$url."?mod=".$row1[0], //ruta enlace
							cls=>$ar->EspecialChars($row[2]), //Text del menú padre
							text=>$ar->EspecialChars($row1[2]), //Text del menú hijo
							leaf=>true
						);
						$mnuChildrens[] = $childs;
					}
				}
				
				$root = array(
					expanded=>true,
					children=>$mnuChildrens
				);
				
				$fn = array(
					fn=>'|clickFun|'
				);
				
				$listeners = array(
					itemclick=>$fn
				);
			
				$mnuFathers = array(
					xtype=>'treepanel',
					title=>$ar->EspecialChars($row[2]),			  
					iconCls=>'nav',
					rootVisible=>false,
					root=>$root,
					listeners=>$listeners
				);
			
			
			$jsonData[] = $mnuFathers;
			
		}
		
		$this->view->data = json_encode($jsonData);
    }

}

