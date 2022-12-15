<?php
/**
 * Layout for error files
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <?php echo $this->Html->meta('favicon.png', '/favicon.png', ['type' => 'icon']); ?>
        <title>Tuxedo Air</title>
        <!-- Bootstrap -->
        <?php echo $this->Html->css('/admin_theme/vendors/bootstrap/dist/css/bootstrap.min'); ?>
        <!-- Font Awesome -->
        <?php echo $this->Html->css('/admin_theme/vendors/font-awesome/css/font-awesome.min'); ?>
        <!-- NProgress -->
        <?php echo $this->Html->css('/admin_theme/vendors/nprogress/nprogress'); ?>
        <!-- Custom Theme Style -->
        <?php echo $this->Html->css('/admin_theme/build/css/custom.min'); ?>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!-- page content -->
                <?php echo $this->fetch('content'); ?>
                <!-- /page content -->
            </div>
        </div>
        <!-- jQuery -->
        <?php echo $this->Html->script('/admin_theme/vendors/jquery/dist/jquery.min'); ?>
        <!-- Bootstrap -->
        <?php echo $this->Html->script('/admin_theme/vendors/bootstrap/dist/js/bootstrap.min'); ?>
        <!-- FastClick -->
        <?php echo $this->Html->script('/admin_theme/vendors/fastclick/lib/fastclick.js'); ?>
        <!-- NProgress -->
        <?php echo $this->Html->script('/admin_theme/vendors/nprogress/nprogress'); ?>
        <!-- Custom Theme Scripts -->
        <?php echo $this->Html->script('/admin_theme/build/js/custom.min'); ?>
    </body>
</html>

<?php /*

<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <title>
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css('base.css') ?>
        <?= $this->Html->css('style.css') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><?= __('Error') ?></h1>
            </div>
            <div id="content">
                <?= $this->Flash->render() ?>

                <?= $this->fetch('content') ?>
            </div>
            <div id="footer">
                <?= $this->Html->link(__('Back'), 'javascript:history.back()') ?>
            </div>
        </div>
    </body>
</html>

*/ ?>