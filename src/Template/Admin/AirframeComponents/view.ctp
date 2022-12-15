<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AirframeComponent $AirframeComponent
 */
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Airframe Component Info</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Aircraft:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo isset($airComp->plane->plane_code) ? h($airComp->plane->plane_code) : ''; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Log Book:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airComp->log_book); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Position:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airComp->position); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Description:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airComp->description); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Serial Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($airComp->serial_no); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

