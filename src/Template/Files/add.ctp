<?php
use Cake\View\Helper\UrlHelper;

$add = $this->Url->build(['controller' => 'files', 'action' => 'add']);
$add .= '/' . implode('/', $this->request->params['pass']);
$callback = implode('/', $this->request->params['pass']);

$this->Helpers()->load('GintonicCMS.GtwRequire');
echo $this->GtwRequire->req('files/feedback');
?>
<div class="modal fade" id="file-modal" tabindex="-1" role="dialog" aria-labelledby="file-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo $this->Form->create($file, array('url' => ['controller' => 'files', 'action' => 'add'],'id' => 'ControllerAddForm','role' =>'form','type' => 'file','target' => 'upload_target')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="file-modal-label"><?php echo __('Upload File'); ?></h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->Form->input('callBack',['value'=>$callback,'type'=>'hidden']);
                echo $this->Form->input('title', ['type' => 'text', 'label' => 'Title']);
                ?>
                <div class="input-group">
                    <span class="input-group-btn">
                        <label for="FileTmpFile" class="btn btn-primary btn-file">
                            <?php echo __('Browse'); ?>
                            <input type="file" id="FileTmpFile" style="display:none" class="form-control" name="tmpFile[]" multiple="">
                        </label>
                    </span>
                    <?php //echo $this->Form->input('f',['label'=>false,'type'=>'text','readonly','placeholder'=>__('No file uploaded'),'class'=>'form-control','id'=>'filename','div'=>false]);?>
                    <input type="text" readonly="" placeholder="No file Uploaded" class="form-control" id="filename">
                </div>
                <?php
                if ($this->request->named) {
                    echo $this->Form->input('dir', array('type' => 'hidden', 'value' => !empty($this->request->named['dir']) ? $this->request->named['dir'] : ''));
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" value="Upload" class="btn btn-primary" disabled="disabled"></button>
            </div>
            <?php echo $this->Form->end(); ?>
            <!-- Older browsers won't let us use ajax for file uploads. This is the hack -->
            <iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;"></iframe>   
        </div>
    </div>
</div>
<script type="text/javascript">
    require(['files/feedback']);
</script>