<?php
/* 
 * Admin Dahboard Login
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
        <meta name="robots" content="noindex,nofollow">

        <?php echo $this->Html->meta('favicon.ico', 'favicon.ico', ['type' => 'icon']); ?>

        <title>SWAS Tracking</title>
        <!-- Bootstrap -->
        <?php echo $this->Html->css('/admin_theme/vendors/bootstrap/dist/css/bootstrap.min'); ?>
        <!-- Font Awesome -->
        <?php echo $this->Html->css('/admin_theme/vendors/font-awesome/css/font-awesome.min'); ?>
        <?php echo $this->Html->css('screen'); ?>
        <!-- Animate.css -->
        <?php echo $this->Html->css('/admin_theme/vendors/animate.css/animate.min'); ?>
        <!-- Custom Theme Style -->
        <?php echo $this->Html->css('/admin_theme/build/css/custom.css'); ?>
        <!-- jQuery --> 
        <?php echo $this->Html->script('/admin_theme/vendors/jquery/dist/jquery.min'); ?>
    </head>
    <body class="login">
        <?php echo $this->fetch('content'); ?>
    
        <?php echo $this->Html->script('jquery.validate'); ?>
        <?php echo $this->Html->script('/admin_theme/vendors/bootstrap/dist/js/bootstrap.min'); ?>
    </body>
</html>    

