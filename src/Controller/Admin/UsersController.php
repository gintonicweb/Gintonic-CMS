<?php
namespace GintonicCMS\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Permissions\Model\Entity\Role;

/**
 * Users Controller
 *
 * @property \GintonicCMS\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function initialize()
    {
        $this->Users = TableRegistry::get('Users.Users');
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(Event $event)
    {
        $this->Users->hasOne('Roles', [
            'className' => 'Permissions.Roles',
        ]);

        $action = $this->Crud->action();
        $action->config('scaffold.fields_blacklist', ['password', 'token']);
        $action->config('scaffold.fields', [
            'id',
            'email',
            'username',
            'verified',
            'deleted',
            'role.role' => [
                'formatter' => function ($name, $value, $entity) {
                    if (isset($entity->role)) {
                        return $entity->role->names($entity->role->role);
                    }
                    return 'User';
                },
                'options' => Role::names(),
            ],
        ]);
        $this->Crud->listener('relatedModels')->relatedModels(['Roles'], 'index');
    }

    /**
     * Index method
     * Don't show created and modified fields
     *
     * @return void
     */
    public function index()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.fields_blacklist', ['created', 'modified']);
        $this->Crud->execute();
    }

    /**
     * View method
     * Related models are blacklisted because the role is already present in
     * the main user panel
     *
     * @return void
     */
    public function view()
    {
        $action = $this->Crud->action();
        $action->config('scaffold.relations', false);
        $this->Crud->execute();
    }
}
