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
    	$login = new Zend_Session_Namespace('login');
    	
    	$parametros[] = array('@buscar',4);
    	$parametros[] = array('@parametro',$login->user);
		$rows = $cn->ejec_store_procedura_sql('Acceso.sp_LogOut', $parametros);
		
		$jsonData = array();
		
		if(count($rows)){
			
			foreach($rows AS $row){
				
				unset($parametros);
				$parametros[] = array('@buscar',5);
				$parametros[] = array('@parametro',$row[0]);
				$parametros[] = array('@password',$login->user);
	
				$rows1 = $cn->ejec_store_procedura_sql('Acceso.sp_LogOut', $parametros);
				
					$mnuChildrens = array();
					
					if(count($rows1))
					{
						foreach($rows1 AS $row1){
							
							if(strlen($row1[2])>0)
								$url=$row1[2];
							else
								$url='main/blank';
								
							$childs = array(
								qtip=>$row1[0],
								//iconCls=>'aaa', //icono del nodo
								hrefTarget=>$url."?mod=".$row1[0], //ruta enlace
								cls=>$ar->EspecialChars($row[1]), //Text del menú padre
								text=>$ar->EspecialChars($row1[1]), //Text del menú hijo
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
						id=>$row[0],
						xtype=>'treepanel',
						title=>$ar->EspecialChars($row[1]),			  
						iconCls=>'nav',
						rootVisible=>false,
						root=>$root,
						listeners=>$listeners
					);
				
				$jsonData[] = $mnuFathers;
				
			}
		}
				
		$this->view->data = json_encode($jsonData);
    }

	public function verificaAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$usuario = $this->_request->getPost('usuario');
			$acceso = $this->_request->getPost('acceso');
			
			$parametros[] = array('@busc',7);
			$parametros[] = array('@operador',$usuario);
			$parametros[] = array('@id_acceso',$acceso);
			$rows = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
			
			$strAccesos = '';
			
			if(count($rows)){				
				foreach($rows as $row){
					if(strlen(trim($row[1]))>0)
						$strAccesos .= $row[1].'&'.$row[2].'|';
				}
			}
						
			echo $strAccesos;
		}
	}
	
	public function validaiconoAction()
    {
		$this->_helper->getHelper('ajaxContext')->initContext();
    	$this->_helper->viewRenderer->setNoRender();
    	$this->_helper->layout->disableLayout();
    	if($this->getRequest()->isXmlHttpRequest()){
			
			$cn = new Model_DbDatos_Datos();
			
			$usuario = $this->_request->getPost('usuario');
			$acceso = $this->_request->getPost('acceso');
			$icono = $this->_request->getPost('icono');
			
			$parametros[] = array('@busc',7);
			$parametros[] = array('@operador',$usuario);
			$parametros[] = array('@id_acceso',$acceso);
			$parametros[] = array('@orden',$icono);
			$rows = $cn->ejec_store_procedura_sql('Acceso.SP_MAcceso', $parametros);
			
			$strAccesos = '';
			if(count($rows)){				
				foreach($rows as $row){
					if(strlen(trim($row[1]))>0)
						$strAccesos .= $row[1].'&'.$row[2].'|';
				}
			}
						
			echo $strAccesos;
		}
	}
}