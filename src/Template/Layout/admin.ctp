<?php
/* 
 * Admin Dashboard Layout file
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="robots" content="noindex,nofollow">

        <?php echo $this->Html->meta('favicon.ico', 'favicon.ico', ['type' => 'icon']); ?>

        <title>SWAS Tracking</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Bootstrap tagsinput css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">

        <!-- dataTables Bootstrap css-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/css/dataTables.bootstrap.min.css">

        <!-- fixedHeader bootstrap css -->
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">

        <!-- responsive bootstrap css -->
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">

        <!-- bootstrap toggle css -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        <!-- datetimepicker -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
        
        <!-- bootstrap-daterangepicker -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css">
        
        <!-- bootstrap-select css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/css/bootstrap-select.min.css">
        
        <!-- Custom Theme Style -->
        <?php echo $this->Html->css('/admin_theme/build/css/custom'); ?>
        <?php //echo $this->Html->css('style'); ?>
        <?php echo $this->Html->css('new/style'); ?>
        <?php echo $this->Html->css('media'); ?>
        <?php echo $this->Html->css('screen'); ?>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    </head>
    <body class="nav-md">
        <div class="container-fluid">
            <?php echo $this->element('admin_nav_top'); ?>
            
            <div class="row">
                <div class="wrapper custWrapper">
                    <?php echo $this->element('admin_sidebar'); ?>
                    <div class="clearfix"></div>
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->fetch('content'); ?>
                </div>
            </div>
        </div>

        <!-- Bootstrap -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

        <!-- Bootstrap toggle-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        
        <!-- FastClick -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js"></script>
        
        <!-- iCheck -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
        
        <!-- Moment js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>

        <!-- datetimepicker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
                
        <!-- bootstrap-progressbar -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-progressbar/0.9.0/bootstrap-progressbar.min.js"></script>
        
        <!-- DateJS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
              
        <!-- validator -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>        
        
        <!-- jquery.inputmask -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.3/bindings/inputmask.binding.min.js"></script>
        
        <!-- jQuery Tags Input -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

        <!-- bootstrap select js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.17/js/bootstrap-select.min.js"></script>
                
        <!-- bootstrap-daterangepicker -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>
        
        <!-- Custom Theme Scripts -->
        <?php echo $this->Html->script('/admin_theme/build/js/custom.min'); ?>
        <?php echo $this->Html->script('common'); ?>

        <!-- Data tables -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/jquery.dataTables.min.js"></script>

        <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-fixedheader/3.1.7/dataTables.fixedHeader.min.js"></script>

        <script src="https://cdn.datatables.net/scroller/2.0.0/js/dataTables.scroller.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
        
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        
        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

        <script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
        
        <?php echo $this->Html->script('new/custom'); ?>
        
        <script type="text/javascript">
        $(document).ready(function() {
            $('.clicked').on('click', function(){
                var formId = this.form.id;
                if($('#'+formId).valid()) {
                    $('body').addClass('backgroundFixed');
                    $('.clicked').hide();
                    $('.buttonload').show();
                    $('.buttonload').attr("disabled", true);
                    var pass = $('#'+formId).valid();
                    if(pass == false){
                        return false;
                    }
                    $("#overlay, #PleaseWait").show();
                    return true;
                } else {
                    $('body').removeClass('backgroundFixed');
                    $('.clicked').attr("disabled", false);
                    return false;
                }
            });
        });
        </script>

        <div class="loader"> 
            <?php
            echo '<i class="fa fa-spinner fa-spin" style="color: #FFFFFF; top:40%; position: fixed; left:50%; font-size: 50px;"></i>';
            ?>
        </div>
    </body>
</html>