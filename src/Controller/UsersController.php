<?php
namespace GintonicCMS\Controller;

use GintonicCMS\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class UsersController extends AppController
{

    public function beforeFilter(Event $event) 
    {
        parent::beforeFilter($event);
        $this->Auth->allow([
            'signin',
            'signup',
            'signout',
            'confirmation',
            'forgot_password',
            'reset_password'
        ]);
    }
    
    public function admin_index()
    {
        if((!$this->request->session()->check('Auth.User')) || ($this->request->session()->read('Auth.User.role') != 'admin')){
            $this->FlashMessage->setWarning(__('You don\'t have permission to access this.'),['controller'=>'users','action'=>'profile']);
        }
        $arrConditions = ['Users.role <> '=>'admin'];
        $this->paginate = array(
            'conditions' => $arrConditions,
            'order' => array('Users.created' => 'desc'),
            'limit' => 5
        );
        $this->set('users', $this->paginate('Users'));
    }
    

    public function admin_view($userId = null)
    {
        if(!$this->request->session()->check('Auth.User.id') || empty($userId) || ($this->request->session()->read('Auth.User.role')!= 'admin')){
            $this->FlashMessage->setWarning(__('Invalid User.'),$this->Auth->redirectUrl());
        }
        $user = $this->Users->safeRead(['Users.id'=>$userId]);
        $this->set(compact('user'));
        $this->render('/Users/view');
    }
    
    public function view(){
        if(!$this->request->session()->check('Auth.User.id')){
            $this->FlashMessage->setWarning(__('Please Login to view this page.'),$this->Auth->redirectUrl());
        }
        $user = $this->Users->safeRead(['Users.id'=>$this->request->session()->read('Auth.User.id')]);
        $this->set(compact('user'));
    }

    public function admin_add()
    {
        if($this->request->session()->read('Auth.User.role') != 'admin'){
            $this->FlashMessage->setWarning(__('You don\'t have permission to add user'),['controller'=>'users','action'=>'profile']);
        }
        $this->request->data["validated"] = 1;
        $user = $this->Users->newEntity($this->request->data);
        if ($this->request->is('post')) {
            if ($this->Users->save($user)) {
                $this->FlashMessage->setSuccess(__('The user has been saved.'),['controller'=>'users','action' => 'admin_index']);
            }
            $this->FlashMessage->setWarning(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }
    
    public function edit() {
        if (!$this->request->session()->check('Auth.User.id')) {
            $this->FlashMessage->setWarning(__('You are not a authorised person to access this.'),$this->request->referer());
        }
        $user = $this->Users->safeRead(['Users.id'=>$this->request->session()->read('Auth.User.id')],true);
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->FlashMessage->setSuccess(__('User has been updated.'));
                return $this->redirect(['action' => 'profile']);
            }
            $this->FlashMessage->setWarning(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->render('/Users/edit_avatar');
    }
    
    public function admin_edit($userId = 0) {
        if (!empty($userId) && ($this->request->session()->read('Auth.User.role') != 'admin')) {
            $this->FlashMessage->setWarning(__('Invalid user'),$this->request->referer());
        }
        $user = $this->Users->safeRead(['Users.id'=>$userId],true);
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->FlashMessage->setSuccess(__('User has been updated.'));
                return $this->redirect(['action' => 'admin_index']);
            }
            $this->FlashMessage->setWarning(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
        $this->render('/Users/edit_avatar');
    }
    
    public function update_avatar($userId = null, $fileId =null)
    {
        if (!empty($this->request->data['id'])) {
            $userId = $this->request->data['id'];
        }
        if (!empty($this->request->data['file_id'])) {
            $fileId = $this->request->data['file_id'];
        }
        $user = $this->Users->safeRead(['Users.id'=>$userId]);
        if(!empty($user->file_id)){
            $oldFile = $user->file_id;
        }
        $user->file_id = $this->request->data['file_id'];
        if ($this->Users->save($user)) {
            $this->loadModel('GintonicCMS.Files');
            if(!empty($oldFile)){
                $file = $this->Files->get($oldFile);
                $this->Files->deleteFile($file->filename,$file->id);
            }
            $file = $this->Files->get($fileId);
            echo json_encode(array(
                    'message' => __('Profile photo has been change successfully.'),
                    'success' => True,
                    'file' => '/' . $this->Files->getUrl($file->filename),
                ));
        } else {
            echo json_encode(array(
                    'message' => __('Unable to change profile photo, Try again.'),
                    'success' => false
                ));
        }
        exit;
    }

    public function signup()
    {
        if (!empty($this->Auth->user())) {
            $this->FlashMessage->setWarning(__('You are already signed in.'),$this->Auth->redirectUrl());
        }
        $user = $this->Users->newEntity($this->request->data);
        if ($this->request->is(['post','put'])) {
            if ($this->Users->save($user)) {
                $this->Users->signupMail($this->request->data['email']);
                $this->FlashMessage->setSuccess(__('Please check your e-mail to validate your account'),$this->Auth->redirectUrl());
            }
            $this->FlashMessage->setWarning(__('Error creating your account, please contact an administrator'));
        }
        $this->set('user', $user);
    }

    public function signin()
    {
        if (!empty($this->Auth->user())) {
            $this->FlashMessage->setWarning(__('You are already signed in.'),$this->Auth->redirectUrl());
        }
        if ($this->request->is(['post', 'put'])) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->loadModel('GintonicCMS.Files');
                $user['file'] = $this->Files->find()
                                            ->where(['Files.id' => $user['file_id']])
                                            ->select(['id', 'filename'])
                                            ->first();
                if (!empty($user['file'])) {
                    $user['file'] = $user['file']->toArray();
                }else{
                    $user['file'] = ['id'=>0,'filename'=>'default'];
                }
                $this->Auth->setUser($user);
                if (isset($this->request->data['remember'])) {
                    $this->Cookie->rememberMe($this->request->session()->read('Auth'));
                }
                // User needs to be validated
                $message = 'Login successful.';
                if(empty($user['validated'])){
                    $message = 'Login successful. Please validate your email address.';
                }
                $this->FlashMessage->setSuccess(__($message),$this->Auth->redirectUrl());
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->FlashMessage->setWarning(__('Your username or password is incorrect.'));
        }
    }
    
    public function signout()
    {
        if (empty($this->Auth->user())) {
            $this->FlashMessage->setWarning(__('You are not logged in.'),$this->Auth->logout());
        }
        $this->Cookie->forgetMe();
        $this->request->session()->destroy();
        $this->FlashMessage->setSuccess(__('You are now logged out.'),$this->Auth->logout());
    }
    
    function profile() 
    {
    }
    
    public function confirmation($userId = null, $token = null) {
        if ($userId || $token) {
            $user = $this->Users->safeRead(['id'=>$userId]);
            if (!empty($user['validated'])) {
                $this->FlashMessage->setWarning(__('Your email address is already validated, please use email and password to login'),$this->Auth->redirectUrl());
            } elseif (!empty($user)) {
                $user = $this->Users->confirmation($userId, $token);
                $this->Auth->setUser($user->toArray());
                $this->FlashMessage->setSuccess(__('Email address successfuly validated'),['plugin'=>'GintonicCMS','controller'=>'users','action'=>'profile']);
            } else {
                $this->FlashMessage->setWarning(__('The authorization link provided is erroneous, please contact an administrator'),$this->Auth->redirectUrl());
            }
        }
        $this->FlashMessage->setWarning(__('Please check your e-mail for validation link'),$this->Auth->redirectUrl());
    }
    
    public function change_password()
    {
        if (!empty($this->request->data)) {
            $userDetail = $this->Users->get($this->request->session()->read('Auth.User.id'));
            if ($this->request->data['new_password'] != $this->request->data['confirm_password']) {
                $this->FlashMessage->setWarning(__('Confirm Password entered does not match.'));
            } elseif ($this->request->data['new_password'] == "") {
                $this->FlashMessage->setWarning(__('New Password Must Not Blank'));
            } else {
                $this->request->data['id'] = $this->request->session()->read('Auth.User.id');
                $this->request->data['password'] = $this->request->data['new_password'];
                $users = $this->Users->patchEntity($userDetail,$this->request->data);
                if ($this->Users->save($users)) {
                    $this->FlashMessage->setSuccess(__('Password has been updated Successfully.'),['controller'=>'users','action' => 'profile']);
                } else {
                    $this->FlashMessage->setWarning(__('Unable to Change Password, Please try again.'));
                }
            }
        }
    }
    
    public function reset_password($userId = null, $token = null) 
    {
        if ($userId && $token) {
            $arrResponse = $this->Users->checkForgotPassword($userId, $token);
            if ($arrResponse['status'] == 'fail') {
                $this->FlashMessage->setWarning(__($arrResponse['message']),$this->Auth->redirectUrl());
            }
        } else {
            $this->FlashMessage->setWarning(__($arrResponse['message']),$this->Auth->redirectUrl());
        }
        $this->set(compact('userId', 'token'));
        if ($this->request->is(['post','put'])) {
            if ($this->request->data['new_password'] != $this->request->data['confirm_password']) {
                $this->FlashMessage->setWarning(__('New Password and Confirm Password must be same'));
            } else {
                $this->request->data['id'] = $userId;
                $this->request->data['password'] = $this->request->data['new_password'];
                $this->request->data['token'] = md5(uniqid(rand(), true));
                $this->request->data['token_creation'] = date("Y-m-d H:i:s");
                $users = $this->Users->newEntity($this->request->data);
                $this->Users->save($users);
                $this->FlashMessage->setSuccess(__('Your password has been updated successfully'),$this->Auth->redirectUrl());
            }
        }
    }
    
    public function resend_verification() 
    {
        if ($this->request->is(['post','put'])) {
            $arrResponse = $this->Users->ResendVerification($this->request->data['email']);
            if (!empty($arrResponse)) {
                if ($arrResponse['status'] == 'fail') {
                    $this->FlashMessage->setWarning(__($arrResponse['message']));
                } else {
                    $this->FlashMessage->setSuccess(__($arrResponse['message']),$this->Auth->redirectUrl());
                }
            }
        }
    }
    
    public function forgot_password() 
    {
        //Check For Already Logged In
        if ($this->Auth->user()) {
            $this->FlashMessage->setWarning(__('You are already login.'));
            return $this->redirect($this->Auth->redirectUrl());
        }
        if ($this->request->is(['post','put'])) {
            $arrResponse = $this->Users->ForgotPasswordEmail($this->request->data['email']);
            if (!empty($arrResponse)) {
                if ($arrResponse['status'] == 'fail') {
                    $this->FlashMessage->setWarning($arrResponse['message']);
                } else {
                    $this->FlashMessage->setSuccess($arrResponse['message']);
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }
        }
    }
    
    public function delete($id = null) 
    {
        if (empty($this->Auth->user())) {
            $this->FlashMessage->setWarning(__('You are not login.'));
            return $this->redirect($this->Auth->redirectUrl());
        }
        $user = $this->Users->get($id);
        if (!$user) {
            $this->FlashMessage->setWarning(__('Invalid user'));
            return $this->redirect($this->request->referer());
        }
        if ($this->Users->delete($user)) {
            $this->FlashMessage->setSuccess(__('Users deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->FlashMessage->setWarning(__('Users was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }
    
    function change_layout($layout = 'default'){
        if($this->request->session()->read('Auth.User.role') == 'admin'){
            $this->request->session()->write('Site.layout','GintonicCMS.'.$layout);
        }else{
            $this->FlashMessage->setWarning(__('You are not authorised user to access this.'));
        }
        return $this->redirect(['plugin'=>'GintonicCMS','controller'=>'users','action'=>'profile']);
    }
}

?>
