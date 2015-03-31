<?php 
use Cake\Core\Configure;

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('users/resend_code'); 
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title"><?php echo __('Resend the Email Verification')?></h1>
            <div class="account-wall">
                <?php
                echo $this->Html->image(Configure::read('site_logo_url'), ["class" => "img-responsive profile-img", "alt" => Configure::read('site_name')]);
                echo $this->Form->create('Users', ['class' => 'form-signin','url'=>['controller'=>'Users','action'=>'resend_verification'], 'id' => 'UserResendVerificationForm', 'novalidate' => 'novalidate']);
                echo $this->Flash->render();
                echo $this->Form->input('email', ['label' => 'Email', 'class' => 'form-control', 'placeholder' => 'Email Address', 'required', 'autofocus']);
                echo $this->Form->submit(__('Resend Code'), ['class' => 'btn btn-lg btn-primary btn-block']);
                ?>
                <span class="clearfix"></span>
                <?php echo $this->Form->end();?>
            </div>
            <?php echo $this->Html->link(__('Go to Profile?'), ['controller' => 'Users', 'action' => 'profile'], ['escape' => false,'class'=>'text-center new-account']); ?>
        </div>
    </div>
</div>
