<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Airframe Component Time Info</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Plane Name:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo isset($airCompTimes->plane->plane_code) ? h($airCompTimes->plane->plane_code) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Airframe Component:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo isset($airCompTimes->airframe_component->log_book) ? h($airCompTimes->airframe_component->log_book) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Log Date:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo !empty($airCompTimes->log_date) ? h(date('d M Y', strtotime($airCompTimes->log_date))) : ''; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Hours:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo h($airCompTimes->hours); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Cycles:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo h($airCompTimes->cycles); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

