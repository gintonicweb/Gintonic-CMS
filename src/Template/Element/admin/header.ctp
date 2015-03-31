<?php 
use Cake\Core\Configure;
?>
<header class="header">
    <?php if ($this->Session->read('Auth.User.role') == 'admin'): ?>
        <div class="admin-special-header container-fluid no-padding navbar-inverse">
            <div class="navbar-header">
                <?php
                echo $this->Html->link(
                        'Admin Panel', ['controller' => 'users', 'action' => 'profile'], ['escape' => false, 'class' => ['logo navbar-inverse']]
                );
                ?>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-admin-navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse navbar-right" id="bs-admin-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <?php
                        echo $this->Html->link(
                                __('Visit Site'). '&nbsp;<i class="fa fa-sign-in">&nbsp;</i>', ['plugin' => 'GintonicCMS', 'controller' => 'users', 'action' => 'change_layout'], ['escape' => false]
                        );
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <?php echo $this->Html->link(
        $this->Html->image('GintonicCMS.gintonic-white.png',['alt'=>Configure::read('site_name')]),
        ['controller' => 'admins', 'action' => 'index'], 
        ['escape' => false, 'class' => 'logo']
    ); ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">					
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span><?php echo $this->Session->read('Auth.User.first') . ' ' . $this->Session->read('Auth.User.last') ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-danger">
                            <?php echo $this->Html->image($this->Custom->getFileUrl($this->Session->read('Auth.User.file.filename')), ['class' => 'img-circle']); ?>
                            <p>
                                <?php echo $this->Session->read('Auth.User.first') . ' ' . $this->Session->read('Auth.User.last');?>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-12 text-center">
                                <?php echo $this->Html->link('Change Password',array('plugin'=>'GintonicCMS','controller'=>'users','action'=>'change_password'));?>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php echo $this->Html->link('Profile',array('plugin'=>'GintonicCMS','controller'=>'users','action'=>'view',$this->Session->read('Auth.User.id')),array('class'=>'btn btn-default btn-flat'));?>
                            </div>
                            <div class="pull-right">
                                <?php echo $this->Html->link('Sign out', array('plugin'=>'GintonicCMS','controller' => 'users', 'action' => 'signout'), array('class' => 'btn btn-default btn-flat')) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    
</header>
