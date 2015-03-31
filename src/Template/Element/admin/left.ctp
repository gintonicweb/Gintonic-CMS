<?php 
$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('admin/admin');
?>
<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                echo $this->Html->image($this->Custom->getFileUrl($this->Session->read('Auth.User.file.filename')),['class' => 'img-circle']);
                ?>
            </div>
            <div class="pull-left info">
                <p>
                    <?php echo $this->Html->link($this->Session->read('Auth.User.first') . ' ' . $this->Session->read('Auth.User.last'),['controller'=>'users','action'=>'profile']);?>
                </p>
                <span title="<?php echo $this->Session->read('Auth.User.email');?>"><?php echo $this->Session->read('Auth.User.email');?></span>
            </div>
        </div>        
        <ul class="sidebar-menu">
            <li><?php echo $this->Html->link('<i class="fa fa-dashboard"></i> <span> ' . __('My Profile') . '</span>', ['plugin'=>'GintonicCMS','controller' => 'users', 'action' => 'profile'], ['escape' => false]) ?></li>
            <li><?php echo $this->Html->link('<i class="fa fa-user"></i> <span> ' . __('User Management') . '</span>', ['plugin'=>'GintonicCMS','controller' => 'users', 'action' => 'admin_index'], ['escape' => false]) ?></li>
            <li><?php echo $this->Html->link('<i class="fa fa-file"></i> <span> ' . __('File Management') . '</span>', ['plugin'=>'GintonicCMS','controller' => 'files', 'action' => 'admin_index'], ['escape' => false]) ?></li>
        </ul>
    </section>
</aside>