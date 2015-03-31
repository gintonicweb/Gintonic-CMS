<?php
$this->assign('pagetitle', __('Add New user') . '<small>' . __('User Management') . '</small>');
$this->Html->addCrumb(__('User Management'), ['controller' => 'users', 'action' => 'index']);
$this->Html->addCrumb(__('Add New user'));
$this->start('top_links');
echo $this->Html->link('<i class="fa fa-reply">&nbsp;</i> Back', ['action' => 'index'], ['class' => 'btn btn-default', 'escape' => false, 'title' => 'Click here to goto users list']);
$this->end();
$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('users/add_edit');
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-body">
                <?php echo $this->Form->create($user, ['id' => 'UserAddEditForm', 'novalidate' => 'novalidate']); ?>
                <div class="row">
                    <div class="col-md-12">				
                        <?php
                        echo $this->Form->input('first', array('label' => __('First Name')));
                        echo $this->Form->input('last', array('label' => __('Last Name')));
                        echo $this->Form->input('email', array('label' => __('Email')));
                        echo $this->Form->input('password', ['label' => __('Password')]);
                        echo $this->Form->submit(__('Create User'), array('div' => false, 'class' => 'btn btn-primary'));
                        ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>