<?php
/**
 * GintonicCMS : Full Stack Content Management System (http://gintoniccms.com)
 * Copyright (c) Philippe Lafrance (http://phillafrance.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Philippe Lafrance (http://phillafrance.com)
 * @link          http://gintoniccms.com GintonicCMS Project
 * @since         0.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace GintonicCMS\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Use this controller as the base class for your apps by inheriting it in your
 * application
 */
class AppController extends Controller
{
    use \Crud\Controller\ControllerTrait;

    /**
     * Base helpers that loads javascript via require and wraps forms with
     * bootstrap markup.
     */
    public $helpers = [
        'Requirejs.Require',
        'Html' => ['className' => 'BootstrapUI.Html'],
        'Form' => ['className' => 'BootstrapUI.Form'],
        'Flash' => ['className' => 'BootstrapUI.Flash'],
        'Paginator' => ['className' => 'BootstrapUI.Paginator'],
    ];

    /**
     * We use this controller as the base controller for our whole app. Here we
     * handle everything related to authentication. It's then easy to extend it
     * from the application.
     */
    public function initialize()
    {
        $this->loadComponent('Acl.Acl');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Websockets.Websocket');

        if ($this->request->action === 'index') {
            $this->loadComponent('Search.Prg');
        }
        $this->loadComponent('Crud.Crud', [
            'actions' => [
                'Crud.Index',
                'Crud.Add',
                'Crud.Edit',
                'Crud.View',
                'Crud.Delete',
            ],
            'listeners' => [
                'CrudView.View',
                'Crud.RelatedModels',
                'Crud.Redirect',
                'CrudView.Search',
            ]
        ]);

        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'FOC/Authenticate.Cookie' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                    'userModel' => 'Users.Users',
                    //'scope' => ['User.active' => 1]
                ],
                'FOC/Authenticate.MultiColumn' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'password'
                    ],
                    'columns' => ['email'],
                    'userModel' => 'Users.Users',
                    //'scope' => ['Users.active' => 1]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'signin',
                'plugin' => 'Users',
                'prefix' => false
            ],
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'view',
                'plugin' => 'Users',
                'prefix' => false

            ],
            'logoutRedirect' => [
                'controller' => 'Pages',
                'action' => 'home',
                'plugin' => 'Users',
                'prefix' => false

            ],
            'unauthorizedRedirect' => [
                'controller' => 'Users',
                'action' => 'signin',
                'plugin' => 'Users',
                'prefix' => false

            ]
        ]);
        parent::initialize();
    }

    /**
     * Called before each action, allows everyone to use the "pages" controller
     * without specific permissions.
     *
     * @param Event $event An Event instance
     * @return void
     * @link http://book.cakephp.org/3.0/en/controllers.html#request-life-cycle-callbacks
     */
    public function beforeFilter(Event $event)
    {
        if ($this->request->params['controller'] == 'Pages') {
            $this->Auth->allow();
        }
    }

    /**
     * Authorization method. We can grant all permissions to everything
     * on the website by adding a user to the group named 'all'.
     *
     * @param array|null $user The user to check the authorization of.
     * @return bool True if $user is authorized, otherwise false
     * @link https://github.com/cakephp/acl
     */
    public function isAuthorized($user = null)
    {
        if (!empty($user)) {
            return $this->Acl->check(['Users' => $user], 'all');
        }
        return false;
    }
}
