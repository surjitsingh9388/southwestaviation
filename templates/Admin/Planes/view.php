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
                    <h4>Aircraft Information</h4>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Registration Code:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->plane_code); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Make & Model:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->plane_type); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Serial Number:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->plane_serial_number); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Airworthiness Date:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo !empty($plane->airworthiness_date) ? (date('d-M-Y', strtotime($plane->airworthiness_date))) : ''; ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Schedule Revision Level:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->federal_aviation_regulation); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Hours:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->hours); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Cycles:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->cycles); ?>
                        </div>
                    </div>

                    <h4>Operator Information</h4>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Aircraft Name:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->plane_name); ?>
                        </div>
                    </div>
                    
                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Manufactured By:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo h($plane->manufacturered_by); ?>
                        </div>
                    </div>

                    <div class="row m-0">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <label class="control-label">Manufactured On:</label>
                        </div>
                        <div class="col-md-6 col-sm-8 col-xs-12">
                            <?php echo !empty($plane->manufacturered_on) ? (date('d-M-Y', strtotime($plane->manufacturered_on))) : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
