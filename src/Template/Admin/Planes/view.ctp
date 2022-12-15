<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plane $plane
 */
?>

<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Aircraft Info</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div><h5><ul>Aircraft Information</ul></h5></div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Registration Code:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->plane_code); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Make & Model:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->plane_type); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Serial Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->plane_serial_number); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Airworthiness Date:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo !empty($plane->airworthiness_date) ? (date('d-M-Y', strtotime($plane->airworthiness_date))) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Schedule Revision Level:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->federal_aviation_regulation); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Hours:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->hours); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Cycles:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->cycles); ?>
                        </div>
                    </div>

                    <div><h5><ul>Operator Information</ul></h5></div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Aircraft Name:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->plane_name); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Manufacturered By:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($plane->manufacturered_by); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Manufacturered On:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo !empty($plane->manufacturered_on) ? (date('d-M-Y', strtotime($plane->manufacturered_on))) : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
