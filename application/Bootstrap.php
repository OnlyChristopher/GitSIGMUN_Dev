<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function __initSession(){
		Zend_Session::setOptions();
		Zend_Session::start();
	}
	
	protected function _initAutoload(){
		$options = $this->getOptions();
		
		$moduleLoader = new Zend_Application_Module_Autoloader(array(
					'namespace' => '',
					'basePath' => APPLICATION_PATH));
		
		$auth = Zend_Auth::getInstance();
		
		$frontController = Zend_Controller_Front::getInstance();
 		$frontController->registerPlugin(new Application_Plugin_SeguridadAcceso($auth, $options['blackList']));
		$frontController->registerPlugin(new Application_Plugin_SelectLayout());
		
		return $moduleLoader;
	}
		
	protected function _initViewHelpers(){
		$view = Zend_Layout::startMvc()->getView();
		$view->doctype=('<!DOCTYPE html>');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=iso-8859-1');		
		$view->headTitle()->setSeparator(' - ');
		$view->headTitle('SIGMUN');
		$view->headTitle('SISTEMA INTEGRAL DE GESTION MUNICIPAL');
		Zend_Session::start();
		
	}

}

