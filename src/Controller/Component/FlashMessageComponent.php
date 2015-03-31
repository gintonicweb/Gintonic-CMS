<?php

namespace GintonicCMS\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Network\Request;
use Cake\Event\Event;
use Cake\Network\Response;
use Cake\Routing\Router;

class FlashMessageComponent extends Component
{
    var $components = array('Flash');
    var $config = array();
    var $success = null;
    var $warning = null;
    var $controller = null;
    

    public function __construct(ComponentRegistry $registry, array $config = array()) {
        parent::__construct($registry, $config);
        $this->config = array_merge(array('success' => array('plugin'=>'GintonicCMS','element'=>'GintonicCMS.success'), 'warning' => array('plugin'=>'GintonicCMS','element'=>'GintonicCMS.warning')), $config);
        $this->success = $this->config['success'];
        $this->warning = $this->config['warning'];
        $this->controller = $this->_registry->getController();
    }
    
    function setSuccess($msg, $url = null) {
        $this->Flash->set(__($msg, true), $this->success);
        if (!empty($url)) {
            return $this->controller->redirect($url);
        }
    }

    function setWarning($msg, $url = null) {
        $this->Flash->set(__($msg, true), $this->warning);
        if (!empty($url)) {
            return $this->controller->redirect($url);
        }
    }

}
