<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */			
    }

    public function indexAction()
    {	
    	$this->view->showRenderer = 0;
    		
		$path = new Zend_Session_Namespace('path');
		$this->view->ruta = $path->data;
		
    }
    
	public function loginAction()
	{
		$this->_helper->layout->disableLayout();
		
		$path = new Zend_Session_Namespace('path');
		$url = $path->data;

		$username = $this->_request->getPost('txtusuario');
    	$password = $this->_request->getPost('txtclave');

		$adapter = new Libreria_Autenticar($username, $password);
    	
    	$auth = Zend_Auth::getInstance();    		
    	$result = $auth->authenticate($adapter);
    	
		if($result->isValid())
		{
			$data = array('flag'=>1,'message'=>'','url'=>$url.'main');
		}
		else
		{
			$msj =  $result->getMessages();			
			$data = array('flag'=>0,'message'=>$msj[0],'url'=>'');    		
		}
		
		$this->view->data = json_encode($data);
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/');
	}

}

