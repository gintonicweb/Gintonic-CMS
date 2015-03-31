<?php 
use Cake\Core\Configure;
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="text-center login-title">Reset your password</h1>
            <div class="account-wall">
                <?php
                echo $this->Html->image(Configure::read('site_logo_url'), ["class" => "img-responsive profile-img", "alt" => Configure::read('.site_name')]);
                echo $this->Form->create('Users', ['class' => 'form-signin','url'=>['controller'=>'Users','action'=>'reset_password',$userId,$token], 'id' => 'UserLoginForm', 'novalidate' => 'novalidate']);
                echo $this->Flash->render();
                echo $this->Form->input('new_password', ['label' => false,'type'=>'password', 'class' => 'form-control', 'placeholder' => 'New Password', 'required', 'autofocus','style'=>['margin-bottom:0px;']]);
                echo $this->Form->input('confirm_password', ['label' => false,'type'=>'password', 'class' => 'form-control', 'placeholder' => 'Confirm Password', 'required', 'autofocus','style'=>['margin-bottom:0px;','oninput'=>'checkPassword(this)']]);
                echo $this->Form->submit(__('Reset Password'), ['class' => 'btn btn-lg btn-primary btn-block']);
                ?>
                <span class="clearfix"></span>
                <?php echo $this->Form->end();?>
            </div>
            <?php echo $this->Html->link(__('Already have an account?'), ['controller' => 'Users', 'action' => 'signin'], ['escape' => false,'class'=>'text-center new-account']); ?>
        </div>
    </div>
</div>
<script language='javascript' type='text/javascript'>
function checkPassword(input) {
	if (input.value != document.getElementById('new_password').value) {
		input.setCustomValidity('New Password and Confirm Password must be same');
    } else {
        // input is valid -- reset the error message
        input.setCustomValidity('');
   }
}
</script>
