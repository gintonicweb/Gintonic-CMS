<?php
$this->assign('pagetitle', __('Edit user') . '<small>' . __('User Management') . '</small>');
$this->Html->addCrumb(__('User Management'), ['controller' => 'users', 'action' => 'index']);
$this->Html->addCrumb(__('Edit user'));
$this->start('top_links');
echo $this->Html->link('<i class="fa fa-reply">&nbsp;</i> Back', ['action' => 'admin_index'], ['class' => 'btn btn-default', 'escape' => false, 'title' => 'Click here to goto users list']);
if($this->request->session()->read('Auth.User.role') == 'admin'){
    echo $this->Html->link('<i class="fa fa-plus">&nbsp;</i> Add User', ['action' => 'admin_add'], ['class' => 'btn btn-primary', 'escape' => false, 'title' => 'Click here to add new user']);
    echo $this->Html->link('<i class="fa fa-th">&nbsp;</i> View User', ['action' => 'admin_view', $user->id], ['class' => 'btn btn-primary', 'escape' => false, 'title' => 'Click here to view this user']);
}
$this->end();

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('users/reset_password');
echo $this->GtwRequire->req('files/filepicker');
?>
<?php
echo $this->Form->create($user, ['templates' => ['inputContainer' => '<div class="form-group">{{content}}</div>', 'input' => '<input type="{{type}}" class="form-control" name="{{name}}"{{attrs}}>']]);
?>
<input id="user-id" type="hidden" value="<?php echo $user->id ?>" />
<div class="row">
    <div class="col-md-12">
        <h1><?php echo $user->first . ' ' . $user->last ?></h1>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div id = "upload-alert"></div>
        <div id="modal-loader"></div>
        <?php
        echo $this->Html->image($this->Custom->getFileUrl($this->Session->read('Auth.User.file.filename')), array('class' => 'img-responsive img-thumbnail', 'id' => 'gtwuserphoto'));
        ?>
        <button type="button" class="btn btn-default upload" data-loading-text="Loading..." data-upload-callback="users/update_avatar">Change Avatar</button>
    </div>
    <div class="col-md-8">
        <fieldset>
            <?php 
            echo $this->Form->input('email', array('label' => 'Email'));
            echo $this->Form->input('password', array('label' => 'Password','type' => 'hidden'));
            ?>
            <div class="form-group">
                <label for="decoy-password">Password</label>
                <input type="password" name="decoy-password" class="form-control" value="DefaultPassword" id="decoy-password">
            </div>
            <?php echo $this->Form->input('first', array('label' => 'First Name')); ?>
            <?php echo $this->Form->input('last', array('label' => 'Last Name')); ?>
<?php
echo $this->Form->submit('Save', array(
    'div' => false,
    'class' => 'btn btn-primary'
));
?>
        </fieldset>
    </div>
</div>

<?php echo $this->Form->end(); ?>