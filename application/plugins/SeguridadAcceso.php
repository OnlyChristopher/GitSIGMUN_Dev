<?php
class Application_Plugin_SeguridadAcceso extends Zend_Controller_Plugin_Abstract{
	
	private $_blackList = null;
	private $_auth = null;
	private $_acl = null;
	private $_ciduser = null;
	
	public function __construct(Zend_Auth $auth, array $blackList){
		$this->_auth = $auth;
		$this->_blackList = $blackList;
		
	}
	
	public function preDispatch(Zend_Controller_Request_Abstract $request){
		$controller = $request->getControllerName();
		$action = $request->getActionName();
		$resource = $controller.':'.$action;
		
		if(!in_array($resource, $this->_blackList) && !$this->_auth->hasIdentity()){
			$request->setControllerName('index')
					->setActionName('index');
		}
	}
}