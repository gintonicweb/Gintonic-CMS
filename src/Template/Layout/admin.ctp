<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php echo $this->fetch('title') ?>
        </title>
        <?php echo $this->Html->meta('icon') ?>
        <?php echo $this->Html->css('GintonicCMS.admin.css') ?>
        
        <?php echo $this->fetch('meta') ?>
        <?php echo $this->fetch('css') ?>
        <?php echo $this->fetch('script') ?>
    </head>
    <body class="wysihtml5-supported  pace-done skin-blue">
        <?php echo $this->element('GintonicCMS.admin/header')?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php echo $this->element('GintonicCMS.admin/left')?>
            <aside class="right-side">
                <section class="content-header">
                    <h1 style="float: left"><?php echo $this->fetch('pagetitle');?></h1>
                    <div class="top-links" style="float: right">
                        <?php echo $this->fetch('top_links'); ?>
                    </div>
                    <div class="clearfix"></div>
                </section>
                <ul class="breadcrumb">
                    <li class="first">
                        <?php
                        echo $this->Html->getCrumbs(' / ', [
                            'text' => '<i class="fa fa-dashboard">&nbsp;</i> Home',
                            'url' => ['plugin'=>'GintonicCMS','controller' => 'users', 'action' => 'profile'],
                            'escape' => false
                        ]);
                        ?>
                    </li>
                </ul>
                <section class="content content-breadcrumb">
                    <?php echo $this->Flash->render();?>
                    <?php echo $this->fetch('content'); ?>	                    
                </section>
            </aside>
        </div>
        <?php 
        echo $this->GtwRequire->req('jquery');
        echo $this->GtwRequire->req('bootstrap');
        ?>
        <?php echo $this->GtwRequire->load($this->Url->build('/',TRUE).'gintonic_c_m_s/js/config'); ?>
    </body>
</html>
