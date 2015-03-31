<?php

namespace GintonicCMS\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class CookieComponent extends Component 
{
    
    public $components = ['Cookie','Auth'];
    
    public function initialize(array $config) 
    {
        parent::initialize($config);
        $this->Cookie->key = Configure::read('Cookie.key');
        $this->Cookie->name = Configure::read('Cookie.name');
        $this->Cookie->httpOnly = true;
    }
    
    public function autoAuth()
    {
        if(!$this->Cookie->read('remember_me') || $this->Auth->loggedIn()){
            return;
        }
        $user = $this->Cookie->read('remember_me');
        if(empty($this->Auth->user())){
            $this->Controller->redirect(['controller' => 'users', 'action' => 'signout']);
        }
        return $this->Controller->redirect($this->Auth->redirectUrl());
    }
    
    public function rememberMe($userInfo)
    {
        $this->Cookie->write('remember_me', $userInfo, true, Configure::read('Cookie.loginDuration'));
    }
    
    public function forgetMe()
    {
        $this->Cookie->delete('remember_me');
    }
    
}
