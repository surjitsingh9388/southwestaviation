<div class="content sliding">
    <div class="outerWrapper">
        <div class="btnWrapper">
            <h2 class="heading">Item Type</h2>
            <?php
            echo $this->Html->link("<i class='fa fa-mail-reply'></i> Go Back", 'javascript:history.back()', array('class' => 'btn btn-default', 'escape' => false));
            ?>
        </div>
        
        <div class="page-content mt-35">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Item Type Title:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($itemTypeRes->title); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Item Type Sort Title:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($itemTypeRes->sort_title); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <label class="control-label pull-right">Status:</label>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo h($itemTypeRes->status); ?>
                        </div>
                    </div> 
                </div>
            </div> 
        </div>
    </div>
</div>

