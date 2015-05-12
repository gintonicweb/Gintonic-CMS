<?php
use Cake\I18n\Time;

$this->assign('pagetitle', __('Users') . '<small>' . __('User Management') . '</small>');
$this->Html->addCrumb(__('Users'));
$this->Helpers()->load('GintonicCMS.Require');
?>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->Html->link('<i class="fa fa-plus">&nbsp;</i> Add User', ['action' => 'add'], ['class' => 'btn btn-primary btn-sm pull-right', 'escape' => false]);?>
    </div>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-footer clearfix">
                <?php echo $this->element('GintonicCMS.Pagination/paginationtop'); ?>                
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('id'); ?></th>
                            <th><?php echo $this->Paginator->sort('first',__('First Name')); ?></th>
                            <th><?php echo $this->Paginator->sort('last',__('Last Name')); ?></th>
                            <th><?php echo $this->Paginator->sort('email'); ?></th>
                            <th><?php echo $this->Paginator->sort('modified',__('Last Updated')); ?></th>
                            <th class='text-center'><?php echo __('Action'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)) { ?>
                            <tr>
                                <td colspan='6' class='text-warning'><?php echo __('No record found.'); ?></td>
                            </tr>
                        <?php } else { ?>	
                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td>
                                        <?php echo $user->id; ?>
                                    </td>
                                    <td>
                                        <?php echo $user->first; ?>
                                    </td>
                                    <td>
                                        <?php echo $user->last; ?>
                                    </td>
                                    <td>
                                        <?php echo $user->email; ?>
                                    </td>
                                    <td>
                                        <?php echo $user->modified; ?>
                                    </td>
                                    <td class="actions text-center">
                                        <span class='text-left'>
                                            <?php
                                            echo $this->Html->link('<i class="fa fa-pencil btn btn-primary"></i> ', ['action' => 'edit', $user->id], ['escape' => false, 'title' => 'Click here to edit this user']);
                                            echo $this->Html->link('<i class="fa fa-file btn btn-primary"></i> ', ['controller' => 'files', 'action' => 'index', $user->id], ['title' => 'Click here to view file uploaded by ' . $user->first, 'escape' => false]);
                                            echo $this->Html->link('<i class="fa fa-times btn btn-primary"></i> ', ['action' => 'delete', $user->id], ['role' => 'button', 'escape' => false, 'title' => 'Delete this user','confirm' => __('Are you sure? You want to delete this user.')]);
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php }
                        }
                        ?>			
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <?php echo $this->element('GintonicCMS.Pagination/pagination');  ?>
            </div>
        </div>
    </div>
</div>
