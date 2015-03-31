<?php
$this->assign('pagetitle', __('My Profile'));
$this->Html->addCrumb(__('My Profile'));
$this->start('top_links');
$this->end();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-md-6 col-md-offset-3">
            <div class="account-wall">
                <?php 
                echo $this->Html->image($this->Custom->getFileUrl($this->Session->read('Auth.User.file.filename')),['class'=>'img-responsive center-block']);
                ?>
                <h1 class="text-center login-title">
                    <?php echo __('Welcome :') . ' ' . $this->Session->read("Auth.User.first") . " " . $this->Session->read("Auth.User.last") ?>
                </h1>
                <div class="text-center">
                    <?php echo $this->Html->link('Logout', ['plugin'=>'GintonicCMS','controller' => 'users', 'action' => 'signout']); ?>
                </div>
            </div>                       
        </div>
    </div>
</div>
