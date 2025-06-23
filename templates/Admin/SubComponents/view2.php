<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Sub Component 1-1</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Aircraft Name:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo isset($subComps->plane->plane_code) ? h($subComps->plane->plane_code) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Aircraft Component:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo isset($subComps->airframe_component->log_book) ? h($subComps->airframe_component->log_book) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Sub Component:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo !empty($subComps->parent_id) ? h($subCompM->subCompName($subComps->parent_id)) : ''; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <label class="control-label pull-right">Sub Component 1-1:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php echo !empty($subComps->title) ? h($subComps->title) : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

