<h1>Forgot Password Email</h1>
<p>
    Please visit the following link to reset your password <br>
    This link will expire in 24 Hours  <br>
    <?php 
    echo $this->Html->link(__('Click here to reset'),\Cake\Routing\Router::url(['plugin'=>'GintonicCMS','controller'=>'users','action'=>'reset_password',$userId,$token],true));
    ?>
</p>