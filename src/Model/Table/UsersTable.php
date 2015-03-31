<?php
namespace GintonicCMS\Model\Table;

use Cake\ORM\Table;
use Cake\Network\Session;
use Cake\Validation\Validator;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Network\Email\Email;
use Cake\I18n\Time;
use Cake\Auth\DefaultPasswordHasher;

class UsersTable extends Table
{

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('email', __('A username is required'))
            ->notEmpty('role', __('A role is required'))
            ->add('email', [
                'unique' => [
                    'rule' => ['validateUnique'],
                    'provider' => 'table'
                ]
            ])
            ->requirePresence('password')
            ->notEmpty('password',['message'=>__('Please enter password.')]);
    }
    
    public function initialize(array $config) 
    {
        parent::initialize($config);
        $this->primaryKey('id');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                    'modified' => 'always'
                ]
            ]
        ]);
        $this->addAssociations([
            'belongsTo' => ['Files'=>[
                'className' => 'GintonicCMS.Files',
                'foreignKey' => 'file_id',
                'propertyName' => 'file'
            ]],
        ]);
    }
    
    public function isValidated($email) {
        $user = $this->safeRead(['email'=>$email]);
        if (!isset($user)) {
            return false;
        }
        return $user->validated;
    }
    
    public function signupMail($email) 
    {
        $user = $this->safeRead(['email'=>$email]);
        if(!empty($user)){
            unset($user->password);
            $user->token = md5(uniqid(rand(), true));
            $user->token_creation = date("Y-m-d H:i:s");;
            $this->save($user);
            $this->sendSignupMail($user);
        }
        return true;
    }
    
    public function sendSignupMail($user) 
    {
        $email = new Email();
        $email->profile('default');
        $email->viewVars(array('userId' => $user->id, 'token' => $user->token));
        $email->template('GintonicCMS.signup')
             ->emailFormat('html')
             ->to($user->email)
             ->from(Configure::read('admin_mail'))
             ->subject('Account validation');
        return $email->send();
        
    }
    
    public function safeRead($conditions = null,$withPassword = false) 
    {
        $this->data = $this->find()
                            ->where([$conditions])
                            ->contain(['Files'=>['fields'=>['id','filename']]])
                            ->first();
        if(empty($this->data['file'])){
            $this->data['file']= ['id'=>0,'filename'=>'default'];
        }
        if (isset($this->data->password) && empty($withPassword)){
            unset($this->data->password);
        }
        return $this->data;
    }
    
    public function confirmation($userId, $token) 
    {
        $user = $this->safeRead(['id'=>$userId]);
        if (!$user) {
            return false;
        }
        if ($user['token'] != $token) {
            return false;
        }
        $user['validated'] = true;
        if (!$this->save($user)) {
            return false;
        }
        return $user;
    }
    
    public function ForgotPasswordEmail($email) 
    {
        $user = $this->safeRead(['email'=>$email]);
        $arrResponse = array('status' => 'fail', 'message' => 'Unable to send forgot password email, Please try again');
        if (empty($user)) {
            return array('status' => 'fail', 'message' => 'No matching email found');
        }
        unset($user->password);
        $user->token = md5(uniqid(rand(), true));
        $user->token_creation = date("Y-m-d H:i:s");

        $this->save($user);
        if ($this->sendForgotPasswordEmail($user)) {
            $arrResponse = array('status' => 'success', 'message' => 'Please check your e-mail for forgot password');
        }
        return $arrResponse;
    }

    public function sendForgotPasswordEmail($user) 
    {
        $email = new Email('default');
        $email->viewVars(array('userId' => $user->id, 'token' => $user->token));
        $email->template('GintonicCMS.forgot_password')
                ->emailFormat('html')
                ->to($user->email)
                ->from([Configure::read('admin_mail') => Configure::read('site_name')])
                ->subject('Forgot Password');
        return $email->send();
    }
    
    public function findCustomPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }

    public function checkForgotPassword($userId, $token) 
    {
        $arrResponse = array('status' => 'fail', 'message' => 'Invalid forgot password token');
        $user = $this->safeRead(['id'=>$userId]);
        if (!empty($user) && $user->token == $token) {
            $time = new Time($this->data->token_creation);
            if (!$time->wasWithinLast('+1 day')) {
                $arrResponse = array('status' => 'fail', 'message' => 'Forgot Password token is expired');
            } else {
                $arrResponse = array('status' => 'success', 'message' => 'Valid Token');
            }
        }
        return $arrResponse;
    }

    public function ResendVerification($email) 
    {
        $user = $this->safeRead(['email'=>$email]);
        if (empty($user)) {
            return array('status' => 'fail', 'message' => 'No matching email found. Please try with correct email address.');
        } elseif (!empty($user['validated'])) {
            return array('status' => 'fail', 'message' => 'Your email address is already validated, please use email and password to login');
        } else {
            $user['token'] = md5(uniqid(rand(), true));
            $user['token_creation'] = date("Y-m-d H:i:s");
            $this->save($user);
            $this->ResendVerificationEmail($user);
            return array('status' => 'success', 'message' => __('The email was resent. Please check your inbox.'));
        }
    }

    public function ResendVerificationEmail($user) 
    {
        $email = new Email('default');
        $email->viewVars(array('userId' => $user->id, 'token' => $user->token,'user'=>$user));
        $email->template('GintonicCMS.resend_code')
            ->emailFormat('html')
            ->to($user->email)
            ->from([Configure::read('admin_mail') => Configure::read('site_name')])
            ->subject('Account validation');
        return $email->send();
    }
    
}
?>
