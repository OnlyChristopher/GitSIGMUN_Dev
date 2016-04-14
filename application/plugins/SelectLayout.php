<?php
class Application_Plugin_SelectLayout extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();
        $layout = Zend_Layout::getMvcInstance();
				
        switch ($controller) {
            case 'index':
            case 'main':
                $layout->setLayout('layout');
                break;			
            default:
                $layout->disableLayout();
                break;
        }		
    }
}