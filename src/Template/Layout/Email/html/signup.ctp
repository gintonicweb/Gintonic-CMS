<h1>Account creation confirmation</h1>
<p>
    Please visit the following link to confirm your account creation <br>
    <?php 
    echo $this->Html->link(__('Click here to Confirm'),\Cake\Routing\Router::url(['plugin'=>'GintonicCMS','controller'=>'users','action'=>'confirmation',$userId,$token],true));
    ?>
</p>